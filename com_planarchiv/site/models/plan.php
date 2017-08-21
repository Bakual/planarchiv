<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * Model class for the PlanArchiv Component
 *
 * @since  3.4
 */
class PlanarchivModelPlan extends JModelItem
{
	protected $_context = 'com_planarchiv.plan';

	/**
	 * Method to get an object.
	 *
	 * @param   int $id The id of the object to get.
	 *
	 * @return mixed Object on success, false on failure.
	 *
	 * @since ?
	 */
	public function &getItem($id = null)
	{
		$user = JFactory::getUser();

		// Initialise variables.
		$id = ($id) ? $id : (int) $this->getState('plan.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$id]))
		{
			try
			{
				$db    = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select();
				$query->from();

				// Join on category table.
				$query->select('c.title AS category_title, c.access AS category_access');
				$query->select('CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END AS catslug');
				$query->join('LEFT', '#__categories AS c on c.id = plan.catid');
				$query->where('(plan.catid = 0 OR c.published = 1)');

				$query->where('plan.id = ' . (int) $id);
				$query->where('plan.state = 1');

				// Join over users for the author names.
				$query->select("user.name AS author");
				$query->join('LEFT', '#__users AS user ON user.id = plan.created_by');

				$db->setQuery($query);

				$data = $db->loadObject();

				if ($error = $db->getErrorMsg())
				{
					throw new Exception($error);
				}

				if (!$data)
				{
					throw new Exception(JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
				}

				$this->_item[$id] = $data;
			}
			catch (Exception $e)
			{
				$this->setError($e);
				$this->_item[$id] = false;
			}
		}

		return $this->_item[$id];
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string $ordering  Ordering column
	 * @param   string $direction 'ASC' or 'DESC'
	 *
	 * @return  void
	 *
	 * @since ?
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		/** @var JApplicationSite $app */
		$app    = JFactory::getApplication();
		$params = $app->getParams();

		// Load the object state.
		$id = $app->input->get('id', 0, 'int');
		$this->setState('plan.id', $id);

		// Load the parameters.
		$this->setState('params', $params);
	}
}
