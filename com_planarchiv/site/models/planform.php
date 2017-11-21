<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * planform model.
 *
 * @package   Planarchiv.Administrator
 *
 * @since     1.0.0
 */
class PlanarchivModelplanform extends JModelAdmin
{
	/**
	 * @var   string  The prefix to use with controller messages.
	 *
	 * @since 1.0.0
	 */
	protected $text_prefix = 'COM_PLANARCHIV';

	/**
	 * @var string  Type Alias for Content History
	 *
	 * @since 1.0.0
	 */
	public $typeAlias = 'com_planarchiv.plan';

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
		$form = $this->loadForm('com_planarchiv.planform', 'planform', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('planform.id'))
		{
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');

			// Disable state field if not authorised.
			if (!$data)
			{
				$data = $form->getData()->toArray();
			}

			if (!$this->canEditState((object) $data))
			{
				$form->setFieldAttribute('state', 'disabled', 'true');
				$form->setFieldAttribute('state', 'filter', 'unset');
			}
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');

			// Disable state field if not authorised in at least one category.
			if (!JFactory::getUser()->getAuthorisedCategories('com_planarchiv', 'core.edit.state'))
			{
				$form->setFieldAttribute('state', 'disabled', 'true');
				$form->setFieldAttribute('state', 'filter', 'unset');
			}
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
		$user = JFactory::getUser();

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
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	protected function populateState()
	{
		parent::populateState();

		$app = JFactory::getApplication();

		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', $app->input->getString('layout'));
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
	public function getTable($type = 'plan', $prefix = 'PlanarchivTable', $config = array())
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
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_planarchiv.edit.plan.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Pre-select some filters in edit form if those have been selected in plans list
			if ($this->getState('planform.id') == 0)
			{
				$filters = (array) $app->getUserState('com_planarchiv.plans.filter');
				$data->set('didok_id', (!empty($filters['didok_id']) ? $filters['didok_id'] : null));
				$data->set('dfa_id', (!empty($filters['dfa_id']) ? $filters['dfa_id'] : null));
				$data->set('anlagetyp_id', (!empty($filters['anlagetyp_id']) ? $filters['anlagetyp_id'] : null));
				$data->set('dokutyp_id', (!empty($filters['dokutyp_id']) ? $filters['dokutyp_id'] : null));
			}

			if (!$data->language)
			{
				$data->language = JFactory::getLanguage()->getTag();
			}
		}

		$this->preprocessData('com_planarchiv.planform', $data);

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
		$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);

		// Increment the content version number.
		$table->version++;
	}
}
