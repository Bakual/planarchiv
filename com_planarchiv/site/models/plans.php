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
 * @since  1.0.0
 */
class PlanarchivModelPlans extends JModelList
{
	/**
	 * @var object
	 *
	 * @since 1.0.0
	 */
	private $item;

	/**
	 * Constructor.
	 *
	 * @param   array $config An optional associative array of configuration settings.
	 *
	 * @since 1.0.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'plans.id',
				'title', 'plans.title',
				'ErstellDatum', 'plans.ErstellDatum',
				'AnlageTyp', 'plans.AnlageTyp',
				'AnlageTypTxt', 'plans.AnlageTypTxt',
				'Maengelliste', 'plans.Maengelliste',
				'original', 'plans.original',
				'created', 'plans.created',
				'checked_out', 'plans.checked_out',
				'checked_out_time', 'plans.checked_out_time',
				'category_id', 'plans.catid', 'level',
				'category_title', 'c_plans.category_title',
				'didok_title', 'didok.title',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Get the master query for retrieving a list of items subject to the model state.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since 1.0.0
	 */
	protected function getListQuery()
	{
		$user   = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields from the table.
		$query->select('`plans`.*');
		$query->from('#__planarchiv_plan AS plans');

		// Join over Plans Category.
		$query->select('`c_plan`.title AS category_title');
		$query->select('CASE WHEN CHAR_LENGTH(c_plan.alias) THEN CONCAT_WS(\':\', c_plan.id, c_plan.alias) ELSE c_plan.id END AS catslug');
		$query->join('LEFT', '#__categories AS c_plan ON c_plan.id = plans.catid');
		$query->where('(c_plan.access IN (' . $groups . ') AND c_plan.published = 1)');

		// Filter by category
		if ($categoryId = $this->getState('filter.category_id'))
		{
			if ($levels = (int) $this->getState('filter.subcategories', 0))
			{
				// Create a subquery for the subcategory list
				$subQuery = $db->getQuery(true);
				$subQuery->select('sub.id');
				$subQuery->from('#__categories AS sub');
				$subQuery->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt');
				$subQuery->where('this.id = ' . (int) $categoryId);

				if ($levels > 0)
				{
					$subQuery->where('sub.level <= this.level + ' . $levels);
				}

				// Add the subquery to the main query
				$query->where('(plans.catid = ' . (int) $categoryId
					. ' OR plans.catid IN (' . $subQuery->__toString() . '))'
				);
			}
			else
			{
				$query->where('plans.catid = ' . (int) $categoryId);
			}
		}

		// Join over users for the author names.
		$query->select("user.name AS author");
		$query->join('LEFT', '#__users AS user ON user.id = plans.created_by');

		// Join over DiDok for the Ort.
		$query->select("didok.title AS didok_title, didok");
		$query->join('LEFT', '#__planarchiv_didok AS didok ON didok.id = plans.didok_id');

		// Filter by DiDok
		if ($didok = $this->getState('filter.didok_title'))
		{
			$query->where('didok.didok = ' . $db->quote($didok));
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if ($search)
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(plans.title LIKE ' . $search . ')');
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('plans.state = ' . (int) $state);
		}

		// Filter by AnlageTyp
		if ($anlageTyp = $this->getState('filter.AnlageTypTxt'))
		{
			$query->where('plans.AnlageTypTxt = ' . $db->quote($anlageTyp));
		}

		// Filter by Strecke/Ort
		$strecke = $this->getState('filter.Strecke');

		if ($strecke == 'S')
		{
			$query->where('plans.Strecke != ""');
		}
		elseif ($strecke == 'O')
		{
			$query->where('plans.Strecke IS NULL');
		}

		// Filter by Mängelliste
		$mangel = $this->getState('filter.Maengelliste');

		if (is_numeric($mangel))
		{
			$query->where('plans.Maengelliste = ' . (int) $mangel);
		}

		// Filter by Original
		$original = $this->getState('filter.original');

		if (is_numeric($original))
		{
			$query->where('plans.original = ' . (int) $original);
		}

		// Do not show trashed links on the front-end
		$query->where('plans.state != -2');

		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->where('plans.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'ErstellDatum')) . ' ' . $db->escape($this->getState('list.direction', 'DESC')));

		return $query;
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
	 * @since 1.0.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		/** @var JApplicationSite $app */
		$app    = JFactory::getApplication();
		$params = $app->getParams();
		$this->setState('params', $params);

		// Category filter (priority on request so subcategories work)
		$id = $app->input->get('catid', $params->get('catid', 0), 'int');
		$this->setState('category.id', $id);

		// Include Subcategories or not
		$this->setState('filter.subcategories', $params->get('show_subcategory_content', 0));

		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_planarchiv')) && (!$user->authorise('core.edit', 'com_planarchiv')))
		{
			// Filter on published for those who do not have edit or edit.state rights.
			$this->setState('filter.state', 1);
		}

		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter-search', '', 'STRING');
		$this->setState('filter.search', $search);

		parent::populateState('ErstellDatum', 'DESC');
	}
}
