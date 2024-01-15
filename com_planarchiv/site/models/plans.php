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
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Model class for the PlanArchiv Component
 *
 * @since  1.0.0
 */
class PlanarchivModelPlans extends ListModel
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
				'anlagetyp_id', 'plans.anlagetyp_id',
				'anlagetyp_title',
                'dokutyp_id', 'plans.dokutyp_id',
                'dfa_id', 'plans.dfa_id',
                'dfa_title', 'plans.dfa_title',
				'Maengelliste', 'plans.Maengelliste',
				'Bemerkung', 'plans.Bemerkung',
				'original', 'plans.original',
				'created', 'plans.created',
				'checked_out', 'plans.checked_out',
				'checked_out_time', 'plans.checked_out_time',
				'category_id', 'plans.catid', 'level',
				'category_title', 'c_plans.category_title',
				'didok_title', 'didok.title',
				'didok_id', 'richtung_didok_id', 'Strecke',
				'ownedits', 'extern',
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
		$user     = Factory::getUser();
		$groups   = implode(',', $user->getAuthorisedViewLevels());
		$langCode = substr(Factory::getLanguage()->getTag(), 0, 2);

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
		$query->select('user.name AS author');
		$query->join('LEFT', '#__users AS user ON user.id = plans.created_by');

		// Join over contacts for the Ersteller names.
		$query->select('contact.name AS ersteller_name');
		$query->join('LEFT', '#__contact_details AS contact ON contact.id = plans.ersteller_id');

		// Join over contacts for the ZurZeitBei names.
		$query->select('zurzeitbei.name AS zurzeitbei_name');
		$query->join('LEFT', '#__contact_details AS zurzeitbei ON zurzeitbei.id = plans.zurzeitbei_id');

        // Filter by ZurZeitBei
        if ($this->getState('filter.extern'))
        {
            $query->where('plans.zurzeitbei_id > 0');
        }

        // Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = plans.checked_out');

		// Filter by user
		if ($this->getState('filter.ownedits'))
		{
			$query->where('(plans.modified_by = ' . $user->id . ' OR plans.created_by = ' . $user->id . ')');
		}

		// Join over the Stockwerk.
		$query->select('sw.title AS stockwerk_title');
		$query->join('LEFT', '#__planarchiv_stockwerk AS sw ON sw.id = plans.stockwerk_id');

		// Join over the dfa.
		$query->select('dfa.title_' . $langCode . ' AS dfa_title, dfa.code_' . $langCode . ' AS dfa_code');
		$query->join('LEFT', '#__planarchiv_dfa AS dfa ON dfa.id = plans.dfa_id');

        // Filter by Dfa
        if ($dfa = (int) $this->getState('filter.dfa_id'))
        {
            $query->where('plans.dfa_id = ' . $dfa);
        }

        // Filter by Dfa Lfnr
        if ($dfaLfnr = $this->getState('filter.GebDfaLfnr'))
        {
            $query->where('plans.GebDfaLfnr = ' . $db->quote($dfaLfnr));
        }

        // Join over the dokutyp.
		$query->select('dokutyp.title_' . $langCode . ' AS dokutyp_title, dokutyp.code_' . $langCode . ' AS dokutyp_code');
		$query->join('LEFT', '#__planarchiv_dokutyp AS dokutyp ON dokutyp.id = plans.dokutyp_id');

        // Filter by AnlageTyp
        if ($dokutyp = (int) $this->getState('filter.dokutyp_id'))
        {
            $query->where('plans.dokutyp_id = ' . $dokutyp);
        }

        // Join over DiDok for the Richtung.
		$query->select('richtung.title AS richtung_title');
		$query->join('LEFT', '#__planarchiv_didok AS richtung ON richtung.id = plans.richtung_didok_id');

		// Join over DiDok for the Ort.
		$query->select('didok.title AS didok_title, didok.didok');
		$query->join('LEFT', '#__planarchiv_didok AS didok ON didok.id = plans.didok_id');

		// Filter by DiDok
		if ($didok = (int) $this->getState('filter.didok_id'))
		{
			$query->where('(didok.id = ' . $didok . ' OR richtung.id = ' . $didok . ')');
		}

		if ($richtung = (int) $this->getState('filter.richtung_didok_id'))
		{
			$query->where('(richtung.id = ' . $richtung . ')');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if ($search)
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(plans.title LIKE ' . $search . ' OR plans.Bemerkung LIKE ' . $search . ')');
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('plans.state = ' . (int) $state);
		}

		// Do not show trashed links on the front-end
		$query->where('plans.state != -2');

		// Join over AnlageTyp.
		$query->select('anlagetyp.title_' . $langCode . ' AS anlagetyp_title');
		$query->select('anlagetyp.code AS anlagetyp_code');
		$query->join('LEFT', '#__planarchiv_anlagetyp AS anlagetyp ON anlagetyp.id = plans.anlagetyp_id');

		// Filter by AnlageTyp
		if ($anlagetyp = (int) $this->getState('filter.anlagetyp_id'))
		{
			$query->where('plans.anlagetyp_id = ' . $anlagetyp);
		}

		// Filter by Strecke/Ort
		$strecke = $this->getState('filter.Strecke');

		if ($strecke == 'S')
		{
			$query->where('plans.Strecke != ""');
		}
		elseif ($strecke == 'O')
		{
			$query->where('(plans.Strecke IS NULL OR plans.Strecke = "")');
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
		$app    = Factory::getApplication();
		$params = $app->getParams();
		$this->setState('params', $params);

		// Include Subcategories or not
		$this->setState('filter.subcategories', $params->get('show_subcategory_content', 0));

		$user = Factory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_planarchiv')) && (!$user->authorise('core.edit', 'com_planarchiv')))
		{
			// Filter on published for those who do not have edit or edit.state rights.
			$this->setState('filter.state', 1);
		}

		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter-search', '', 'STRING');
		$this->setState('filter.search', $search);

		parent::populateState('id', 'DESC');
	}
}
