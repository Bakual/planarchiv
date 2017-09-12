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
 * HTML View class for the PlanArchiv Component
 *
 * @since  1.0.0
 */
class PlanarchivViewPlans extends JViewLegacy
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return mixed A string if successful, otherwise a Error object.
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 */
	public function display($tpl = null)
	{
		$state = $this->get('State');
		$state->set('list.start', 0);
		$state->set('list.limit', 0);

		$this->items = $this->get('Items');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->setLayout('xls');

		return parent::display($tpl);
	}
}
