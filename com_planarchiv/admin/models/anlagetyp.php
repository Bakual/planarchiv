<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Anlagetyp model.
 *
 * @package   Planarchiv.Administrator
 *
 * @since     1.0.0
 */
class PlanarchivModelAnlagetyp extends AdminModel
{
	/**
	 * @var   string  The prefix to use with controller messages.
	 *
	 * @since 1.0.0
	 */
	protected $text_prefix = 'COM_PLANARCHIV';

	/**
	 * The context used for the associations table
	 *
	 * @var     string
	 * @since   1.0.0
	 */
	protected $associationsContext = 'com_planarchiv.anlagetyp';

	/**
	 * Method to get the record form.
	 *
	 * @param    array   $data     An optional array of data for the form to interogate.
	 * @param    boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return bool|JForm
	 * @since    1.0.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_planarchiv.anlagetyp', 'anlagetyp', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('anlagetyp.id'))
		{
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Modify the form based on Edit State access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an article you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to test whether a records state can be changed.
	 *
	 * @param    $record  object    A record object.
	 *
	 * @return    boolean    True if allowed to change the state of the record. Defaults to the permission set in the
	 *                       component.
	 * @since    1.0.0
	 */
	protected function canEditState($record)
	{
		$user = Factory::getUser();

		// Check against the category.
		if (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_planarchiv.category.' . (int) $record->catid);
		}
		// Default to component settings if neither item nor category known.
		else
		{
			return parent::canEditState($record);
		}
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param    string $type   The table type to instantiate
	 * @param    string $prefix A prefix for the table class name. Optional.
	 * @param    array  $config Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 * @since    1.0.0
	 */
	public function getTable($type = 'Anlagetyp', $prefix = 'PlanarchivTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return    mixed    The data for the form.
	 * @since    1.0.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = Factory::getApplication();
		$data = $app->getUserState('com_planarchiv.edit.anlagetyp.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters (Status, Category) in edit form if those have been selected in Anlagetyp Manager: Anlagetyps
			if ($this->getState('anlagetyp.id') == 0)
			{
				$filters = (array) $app->getUserState('com_planarchiv.anlagetyps.filter');
				$data->set('state', $app->input->getInt('state', ((isset($filters['state']) && $filters['state'] !== '') ? $filters['state'] : null)));
				$data->set('catid', $app->input->getInt('catid', (!empty($filters['category_id']) ? $filters['category_id'] : null)));
			}
		}

		$this->preprocessData('com_planarchiv.anlagetyp', $data);

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param JTable $table
	 *
	 * @since    1.0.0
	 */
	protected function prepareTable($table)
	{
		$table->title_de = htmlspecialchars_decode($table->title_de, ENT_QUOTES);
		$table->title_fr = htmlspecialchars_decode($table->title_fr, ENT_QUOTES);
		$table->title_it = htmlspecialchars_decode($table->title_it, ENT_QUOTES);

		// Increment the content version number.
		$table->version++;
	}
}
