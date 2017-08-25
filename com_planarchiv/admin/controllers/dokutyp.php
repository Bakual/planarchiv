<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * Dokutyp controller class.
 *
 * @package        PlanArchiv.Administrator
 *
 * @since          1.0.0
 */
class PlanarchivControllerDokutyp extends JControllerForm
{
	/**
	 * Method to save a record.
	 *
	 * @param    string $key    The name of the primary key of the URL variable.
	 * @param    string $urlVar The name of the URL variable if different from the primary key (sometimes required to
	 *                          avoid router collisions).
	 *
	 * @return    Boolean    True if successful, false otherwise.
	 * @since    1.0.0
	 */
	public function save($key = null, $urlVar = 'id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
		if ($result && ($return = $this->getReturnPage()))
		{
			$this->setRedirect($return);
		}

		return $result;
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param    array $data An array of input data.
	 *
	 * @return    boolean
	 *
	 * @since 1.0.0
	 */
	protected function allowAdd($data = array())
	{
		$user       = JFactory::getUser();
		$categoryId = Joomla\Utilities\ArrayHelper::getValue($data, 'catid', JFactory::getApplication()->input->get('filter_category_id'), 'int');
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
	 * Method to check if you can add a new record.
	 *
	 * @param   array  $data An array of input data.
	 * @param   string $key  The name of the key for the primary key.
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
			return parent::allowEdit($data, $key);
		}

		// Need to do a lookup from the model.
		/** @var PlanarchivModelDokutyp $model */
		$model      = $this->getModel();
		$record     = $model->getItem($recordId);
		$categoryId = (int) $record->catid;

		if (!$categoryId)
		{
			// No category set, fall back to component permissions
			return parent::allowEdit($data, $key);
		}

		$user = JFactory::getUser();

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

		return false;
	}

	/**
	 * @param null $recordId
	 * @param null $urlVar
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$modal  = JFactory::getApplication()->input->get('modal', 0, 'int');
		$return = $this->getReturnPage();

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
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return    string    The return URL.
	 * @since    1.0.0
	 */
	protected function getReturnPage()
	{
		$return = JFactory::getApplication()->input->get('return', '', 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return false;
		}
		else
		{
			return base64_decode($return);
		}
	}
}