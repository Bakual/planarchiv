<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class PlanarchivModelAnlagetyps extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param  array $config An optional associative array of configuration settings.
	 *
	 * @see    JController
	 * @since  1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'anlagetyps.id',
				'title',
				'title_de', 'anlagetyps.title_de',
				'title_fr', 'anlagetyps.title_fr',
				'title_it', 'anlagetyps.title_it',
				'code', 'anlagetyps.code',
				'OrtName', 'anlagetyps.OrtName',
				'checked_out', 'anlagetyps.checked_out',
				'checked_out_time', 'anlagetyps.checked_out_time',
				'catid', 'anlagetyps.catid', 'category_title',
				'state', 'anlagetyps.state',
				'created', 'anlagetyps.created',
				'created_by', 'anlagetyps.created_by',
			);

			// Searchtools
			$config['filter_fields'][] = 'category_id';
			$config['filter_fields'][] = 'level';
			$config['filter_fields'][] = 'tag';
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param string $ordering
	 * @param string $direction
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = Factory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// Load the parameters.
		$params = JComponentHelper::getParams('com_planarchiv');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('title', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param    string $id A prefix for the store id.
	 *
	 * @return    string        A store id.
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('anlagetyps.*');

		$langCode = substr(Factory::getLanguage()->getTag(), 0, 2);
		$query->select('anlagetyps.title_' . $langCode . ' AS title');
		$query->from('#__planarchiv_anlagetyp AS anlagetyps');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = anlagetyps.checked_out');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = anlagetyps.created_by');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = anlagetyps.catid');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('anlagetyps.state = ' . (int) $published);
		}
		elseif ($published === '' || $published === null)
		{
			$query->where('(anlagetyps.state IN (0, 1))');
		}

		// Filter by category.
		$baselevel  = 1;
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt       = $cat_tbl->rgt;
			$lft       = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= ' . (int) $lft)
				->where('c.rgt <= ' . (int) $rgt);
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('c.level <= ' . ((int) $level + (int) $baselevel - 1));
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('anlagetyps.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(anlagetyps.title_' . $langCode . ' LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}
}
