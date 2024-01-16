<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * Controller class for the PlanArchiv Component
 *
 * @since  1.0.0
 */
class PlanarchivControllerPlan extends FormController
{
	/**
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $view_item = 'planform';

	/**
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $view_list = 'plans';

	/**
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $context = 'plan';

	/**
	 * Method to add a new record
	 *
	 * @return  boolean  True if the article can be added, false if not
	 *
	 * @since 1.0.0
	 */
	public function add()
	{
		$return = parent::add();

		if (!$return)
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}

		return $return;
	}

	/**
	 * Method override to check if you can add a new record
	 *
	 * @param array $data An array of input data
	 *
	 * @return  boolean
	 *
	 * @since 1.0.0
	 */
	protected function allowAdd($data = array())
	{
		$user       = Factory::getApplication()->getIdentity();
		$categoryId = Joomla\Utilities\ArrayHelper::getValue($data, 'catid', Factory::getApplication()->input->get('filter_category_id'), 'int');
		$allow      = null;

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', $this->option . '.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method to check if you can add a new record
	 *
	 * @param array  $data An array of input data
	 * @param string $key  The name of the key for the primary key
	 *
	 * @return  boolean
	 *
	 * @since 1.0.0
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
			$user = Factory::getApplication()->getIdentity();

			// The category has been set. Check the category permissions.
			if ($user->authorise('core.edit', $this->option . '.category.' . $categoryId))
			{
				return true;
			}

			// Fallback on edit.own.
			if ($user->authorise('core.edit.own', $this->option . '.category.' . $categoryId))
			{
				return ($record->created_by == $user->id);
			}
		}
		else
		{
			// Since there is no asset tracking, revert to the component permission
			return parent::allowEdit($data, $key);
		}

		return false;
	}

	/**
	 * Method to cancel an edit
	 *
	 * @param string $key The name of the primary key of the URL variable
	 *
	 * @return  Boolean  True if access level checks pass, false otherwise
	 *
	 * @since 1.0.0
	 */
	public function cancel($key = 'id')
	{
		$return = parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());

		return $return;
	}

	/**
	 * Method to edit an existing record
	 *
	 * @param string $key      The name of the primary key of the URL variable
	 * @param string $urlVar   The name of the URL variable if different from the primary key (sometimes required to
	 *                         avoid router collisions)
	 *
	 * @return  Boolean  True if access level check and checkout passes, false otherwise
	 *
	 * @since 1.0.0
	 */
	public function edit($key = null, $urlVar = 'id')
	{
		return parent::edit($key, $urlVar);
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param string $name   The model name. Optional
	 * @param string $prefix The class prefix. Optional
	 * @param array  $config Configuration array for model. Optional
	 *
	 * @return  object  The model
	 *
	 * @since 1.0.0
	 */
	public function getModel($name = 'Planform', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Gets the URL arguments to append to an item redirect
	 *
	 * @param int    $recordId The primary key id for the item
	 * @param string $urlVar   The name of the URL variable for the id
	 *
	 * @return  string  The arguments to append to the redirect URL
	 *
	 * @since 1.0.0
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$jinput = Factory::getApplication()->input;
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$itemId = $jinput->get('Itemid', 0, 'int');
		$modal  = $jinput->get('modal', 0, 'int');
		$return = $this->getReturnPage();

		if ($itemId)
		{
			$append .= '&Itemid=' . $itemId;
		}

		if ($modal)
		{
			$append .= '&tmpl=component';
		}

		if ($return)
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string  The return URL
	 *
	 * @since 1.0.0
	 */
	protected function getReturnPage()
	{
		$return = Factory::getApplication()->input->get('return', '', 'base64');

		if (empty($return) || !Uri::isInternal(base64_decode($return)))
		{
			return Uri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved
	 *
	 * @param BaseDatabaseModel $model     The data model object
	 * @param array             $validData The validated data
	 *
	 * @since 1.0.0
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = array())
	{
		$task = $this->getTask();

		if ($task === 'save2copy')
		{
			$model->checkin($model->getState('planform.id'));
		}

		if ($task === 'save')
		{
			$this->setRedirect(Route::_('index.php?option=com_planarchiv&view=plans', false));
		}
	}

	/**
	 * Method to save a record
	 *
	 * @param string $key      The name of the primary key of the URL variable
	 * @param string $urlVar   The name of the URL variable if different from the primary key (sometimes required to
	 *                         avoid router collisions)
	 *
	 * @return  Boolean  True if successful, false otherwise
	 *
	 * @since 1.0.0
	 */
	public function save($key = null, $urlVar = 'id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page
		if ($result)
		{
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}
}
