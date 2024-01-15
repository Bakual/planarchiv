<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

// Base this model on the backend version.
JLoader::register('ContactModelContact', JPATH_ADMINISTRATOR . '/components/com_contact/models/contact.php');

/**
 * Contact Component Contact Model
 *
 * @since  __DEPLOY_VERSION__
 */
class PlanarchivModelContactform extends ContactModelContact
{
	/**
	 * Model typeAlias string. Used for version history.
	 *
	 * @var  string
	 * @since  __DEPLOY_VERSION__
	 */
	public $typeAlias = 'com_contact.contact';

	/**
	 * Name of the form
	 *
	 * @var string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $formName = 'contactform';

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Contact', $prefix = 'ContactTable', $config = array())
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_contact/tables');

		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get contact data.
	 *
	 * @param   integer  $itemId  The id of the contact.
	 *
	 * @return  mixed  Contact item data object on success, false on failure.
	 *
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('contact.id');

		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		try
		{
			if (!$table->load($itemId))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage());

			return false;
		}

		$properties = $table->getProperties();
		$value      = ArrayHelper::toObject($properties, 'stdClass');

		// Convert field to Registry.
		$value->params = new Registry($value->params);

		// Convert the metadata field to an array.
		$registry        = new Registry($value->metadata);
		$value->metadata = $registry->toArray();

		if ($itemId)
		{
			$value->tags = new Joomla\CMS\Helper\TagsHelper;
			$value->tags->getTagIds($value->id, 'com_contact.contact');
			$value->metadata['tags'] = $value->tags;
		}

		return $value;
	}

	/**
	 * Get the return URL.
	 *
	 * @return  string  The return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @throws Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public function save($data)
	{
		// Associations are not edited in frontend ATM so we have to inherit them
		if (Associations::isEnabled() && !empty($data['id'])
			&& $associations = Associations::getAssociations('com_contact', '#__contact_details', 'com_contact.item', $data['id']))
		{
			foreach ($associations as $tag => $associated)
			{
				$associations[$tag] = (int) $associated->id;
			}

			$data['associations'] = $associations;
		}

		return parent::save($data);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('contact.id', $pk);

		$this->setState('contact.catid', $app->input->getInt('catid'));

		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', $app->input->getString('layout'));
	}
}
