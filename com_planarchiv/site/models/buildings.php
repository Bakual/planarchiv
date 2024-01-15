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
class PlanarchivModelBuildings extends ListModel
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
				'Maengelliste', 'plans.Maengelliste',
				'original', 'plans.original',
				'created', 'plans.created',
				'checked_out', 'plans.checked_out',
				'checked_out_time', 'plans.checked_out_time',
				'category_id', 'plans.catid', 'level',
				'category_title', 'c_plans.category_title',
				'didok_title', 'didok.title',
				'didok_id', 'richtung_didok_id', 'Strecke',
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
		$langCode = substr(Factory::getLanguage()->getTag(), 0, 2);

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields from the table.
		$query->select('`plans`.`dfa_id`, `plans`.`GebDfaLfnr`, `plans`.`didok_id`');
		$query->from('#__planarchiv_plan AS plans');
		$query->where('`plans`.`dfa_id` != 0');

		// Join over the dfa.
		$query->select('dfa.title_' . $langCode . ' AS dfa_title, dfa.code_' . $langCode . ' AS dfa_code');
		$query->join('LEFT', '#__planarchiv_dfa AS dfa ON dfa.id = plans.dfa_id');

		// Join over DiDok for the Ort.
		$query->select('didok.title AS didok_title, didok.didok');
		$query->join('LEFT', '#__planarchiv_didok AS didok ON didok.id = plans.didok_id');

		// Filter by DiDok
		if ($didok = (int) $this->getState('filter.didok_id'))
		{
			$query->where('didok.id = ' . $didok);
		}

		// Filter by state
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('plans.state = ' . (int) $state);
		}

		// Do not show trashed links on the front-end
		$query->where('plans.state != -2');

		$query->group('`plans`.`dfa_id`, `plans`.`GebDfaLfnr`');

		// Add the list ordering clause.
        $query->order($db->escape('dfa_title') . ' ASC, ' . $db->escape('GebDfaLfnr') . ' ASC');

		return $query;
	}

	/**
	 * Get the master query for retrieving a list of items subject to the model state.
	 *
	 * @return object[]
	 *
	 * @since 1.0.0
	 */
	public function getStrecken()
	{
		$langCode = substr(Factory::getLanguage()->getTag(), 0, 2);

		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select required fields from the table.
		$query->select('`plans`.`didok_id`, `plans`.`richtung_didok_id`');
		$query->from('#__planarchiv_plan AS plans');
		$query->where('`plans`.`richtung_didok_id` != 0');

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

		// Filter by state
		$state = $this->getState('filter.state');

		if (is_numeric($state))
		{
			$query->where('plans.state = ' . (int) $state);
		}

		// Do not show trashed links on the front-end
		$query->where('plans.state != -2');

		$query->group('`plans`.`didok_id`, `plans`.`richtung_didok_id`');

		// Add the list ordering clause.
		$query->order($db->escape('didok_id') . ' ASC');

		return $this->_getList($query);
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

		parent::populateState('dfa_title', 'ASC');
	}
}
