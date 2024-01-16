<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use JeroenDesloovere\VCard\VCardParser;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Utilities\ArrayHelper;

/**
 * Controller for single contact view
 *
 * @since  1.0.0
 */
class PlanarchivControllerContact extends FormController
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $view_item = 'contactform';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	protected $view_list = 'plans';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  BaseDatabaseModel  The model.
	 *
	 * @since   1.6.4
	 */
	public function getModel($name = 'contactform', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}

	/**
	 * Upload a file
	 *
	 * @return  void  Echoes an AJAX response
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function upload()
	{
		// Initialise variables.
		/** @var JApplicationSite $app */
		$app = Factory::getApplication();
		$params = $app->getParams('com_planarchiv');
		$jinput = $app->input;
		$Itemid = $jinput->get('Itemid');
		$this->checkToken();

		// Authorize User
		$user = Factory::getUser();

		if (!$user->authorise('core.create', 'com_contact'))
		{
			$app->enqueueMessage(Text::_('JGLOBAL_AUTH_ACCESS_DENIED'), 'warning');
			$app->redirect(Route::_('index.php?Itemid=' . $Itemid));
		}

		// Get some data from the request
		$file = $jinput->files->get('vcard');

		if (!$file['name'])
		{
			$app->enqueueMessage(Text::_('COM_CONTACT_UPLOAD_FAILED'), 'warning');
			$app->redirect(Route::_('index.php?Itemid=' . $Itemid));
		}

		// Check file extension
		$ext = File::getExt($file['name']);

		if ($ext !== 'vcf')
		{
			$app->enqueueMessage(Text::_('COM_CONTACT_FILETYPE_NOT_ALLOWED'), 'warning');
			$app->redirect(Route::_('index.php?Itemid=' . $Itemid));
		}

		$vcard = VCardParser::parseFromFile($file['tmp_name']);
		$item = $vcard->getCardAtIndex(0);

		$contactModel = $this->getModel();
		$contact = $contactModel->getItem();
		$contact->published   = $user->authorise('core.edit.state', 'com_contact') ? 1 : 0;
		$contact->catid   = $params->get('vcard_category');
		$contact->name   = $item->fullname;

		if (!empty($item->address['WORK'][0]))
		{
			$contact->address   = $item->address['WORK'][0]->street;
			$contact->suburb   = $item->address['WORK'][0]->city;
			$contact->postcode   = $item->address['WORK'][0]->zip;
			$contact->state   = $item->address['WORK'][0]->region;
			$contact->country   = $item->address['WORK'][0]->country;
		}

		if (!empty($item->email[0][0]))
		{
			$contact->email_to   = $item->email[0][0];
		}

		if (!empty($item->url[0][0]))
		{
			$contact->website   = $item->url[0][0];
		}

		if (!empty($item->phone[0][0]))
		{
			$contact->telephone   = $item->phone[0][0];
		}

		if (!$contactModel->save($contact->getProperties()))
		{
			$app->enqueueMessage(Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $contactModel->getError()));
			$app->redirect('index.php?Itemid=' . $Itemid);
		}

		$table = $contactModel->getTable();
		$table->load(array('name' => $item->fullname));

		$app->enqueueMessage(Text::_('JLIB_APPLICATION_SAVE_SUCCESS'));

		$redirect = 'index.php?option=com_planarchiv&task=contact.edit&id=' . $table->id . '&Itemid=' . $jinput->get('Itemid');
		$app->redirect($redirect);
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowAdd($data = array())
	{
		if ($categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int'))
		{
			$user = Factory::getUser();

			// If the category has been passed in the data or URL check it.
			return $user->authorise('core.create', 'com_contact.category.' . $categoryId);
		}

		// In the absence of better information, revert to the component permissions.
		return parent::allowAdd();
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;

		if (!$recordId)
		{
			return false;
		}

		// Need to do a lookup from the model.
		$record     = $this->getModel()->getItem($recordId);
		$categoryId = (int) $record->catid;

		if ($categoryId)
		{
			$user = Factory::getUser();

			// The category has been set. Check the category permissions.
			if ($user->authorise('core.edit', 'com_contact.category.' . $categoryId))
			{
				return true;
			}

			// Fallback on edit.own.
			if ($user->authorise('core.edit.own', 'com_contact.category.' . $categoryId))
			{
				return ($record->created_by == $user->id);
			}

			return false;
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string    The arguments to append to the redirect URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getRedirectToItemAppend($recordId = 0, $urlVar = 'id')
	{
		// Need to override the parent method completely.
		$tmpl = $this->input->get('tmpl');

		$append = '';

		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		$append .= '&layout=edit';

		$append .= '&' . $urlVar . '=' . (int) $recordId;

		$itemId = $this->input->getInt('Itemid');
		$return = $this->getReturnPage();
		$catId  = $this->input->getInt('catid');

		if ($itemId)
		{
			$append .= '&Itemid=' . $itemId;
		}

		if ($catId)
		{
			$append .= '&catid=' . $catId;
		}

		if ($return)
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string    The return URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !Uri::isInternal(base64_decode($return)))
		{
			return Uri::base();
		}

		return base64_decode($return);
	}
}
