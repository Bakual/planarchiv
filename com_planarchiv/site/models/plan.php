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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;

/**
 * Model class for the PlanArchiv Component
 *
 * @since  3.4
 */
class PlanarchivModelPlan extends ItemModel
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
		$langCode = substr(Factory::getLanguage()->getTag(), 0, 2);

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

				$query->select('plan.*');
				$query->from('#__planarchiv_plan AS plan');

				// Join on category table.
				$query->select('c.title AS category_title, c.access AS category_access');
				$query->select('CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(\':\', c.id, c.alias) ELSE c.id END AS catslug');
				$query->join('LEFT', '#__categories AS c on c.id = plan.catid');
				$query->where('c.published = 1');

				$query->where('plan.id = ' . (int) $id);
				$query->where('plan.state = 1');

				// Join over DiDok.
				$query->select('didok.title AS didok_title, didok.didok');
				$query->join('LEFT', '#__planarchiv_didok AS didok ON didok.id = plan.didok_id');

				// Join over DiDok.
				$query->select('richtung.title AS richtung_title, richtung.didok AS richtung_didok');
				$query->join('LEFT', '#__planarchiv_didok AS richtung ON richtung.id = plan.richtung_didok_id');

				// Join over Stockwerk.
				$query->select('stockwerk.title AS stockwerk_title');
				$query->join('LEFT', '#__planarchiv_stockwerk AS stockwerk ON stockwerk.id = plan.stockwerk_id');

				// Join over Dfa.
				$query->select('dfa.title_' . $langCode . ' AS dfa_title');
				$query->select('dfa.code_' . $langCode . ' AS dfa_code');
				$query->join('LEFT', '#__planarchiv_dfa AS dfa ON dfa.id = plan.dfa_id');

				// Join over Anlagetyp.
				$query->select('anlagetyp.title_' . $langCode . ' AS anlagetyp_title');
				$query->select('anlagetyp.code AS anlagetyp_code');
				$query->join('LEFT', '#__planarchiv_anlagetyp AS anlagetyp ON anlagetyp.id = plan.anlagetyp_id');

				// Join over DokuTyp.
				$query->select('dokutyp.title_' . $langCode . ' AS dokutyp_title');
				$query->select('dokutyp.code_' . $langCode . ' AS dokutyp_code');
				$query->join('LEFT', '#__planarchiv_dokutyp AS dokutyp ON dokutyp.id = plan.dokutyp_id');

				// Join over Contact.
				$query->select('contact.name AS ersteller_name, contact.alias AS ersteller_alias, contact.catid AS ersteller_catid');
				$query->join('LEFT', '#__contact_details AS contact ON contact.id = plan.ersteller_id');

				// Join over Contact.
				$query->select('zurzeitbei.name AS zurzeitbei_name, zurzeitbei.alias AS zurzeitbei_alias, zurzeitbei.catid AS zurzeitbei_catid');
				$query->join('LEFT', '#__contact_details AS zurzeitbei ON zurzeitbei.id = plan.zurzeitbei_id');

				// Join over users for the author names.
				$query->select('user.name AS author');
				$query->join('LEFT', '#__users AS user ON user.id = plan.created_by');

				$db->setQuery($query);

				$data = $db->loadObject();

				if (!$data)
				{
					throw new Exception(Text::_('JGLOBAL_RESOURCE_NOT_FOUND'));
				}

				$this->_item[$id] = $data;
			}
			catch (Exception $e)
			{
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
		$app    = Factory::getApplication();
		$params = $app->getParams();

		// Load the object state.
		$id = $app->input->get('id', 0, 'int');
		$this->setState('plan.id', $id);

		// Load the parameters.
		$this->setState('params', $params);
	}
}
