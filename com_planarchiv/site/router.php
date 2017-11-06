<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * Routing class of com_planarchiv
 *
 * @since  1.0.0
 */
class PlanarchivRouter extends JComponentRouterView
{
	protected $noIDs = false;

	/**
	 * Planarchiv Component router constructor
	 *
	 * @param   JApplicationCms  $app   The application object
	 * @param   JMenu            $menu  The menu object to work with
	 *
	 * @since   1.0.0
	 */
	public function __construct($app = null, $menu = null)
	{
		$buildings = new JComponentRouterViewconfiguration('buildings');
		$this->registerView($buildings);
		$plans = new JComponentRouterViewconfiguration('plans');
		$this->registerView($plans);
		$plan = new JComponentRouterViewconfiguration('plan');
		$plan->setKey('id');
		$this->registerView($plan);
		$form = new JComponentRouterViewconfiguration('planform');
		$form->setKey('id');
		$this->registerView($form);

		parent::__construct($app, $menu);

		$this->attachRule(new JComponentRouterRulesMenu($this));
		$this->attachRule(new JComponentRouterRulesStandard($this));
		$this->attachRule(new JComponentRouterRulesNomenu($this));
	}

	/**
	 * Method to get the segment(s) for a form
	 *
	 * @param   string  $id     ID of the plan form to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since   1.0.0
	 */
	public function getPlanformSegment($id, $query)
	{
		return array((int) $id => $id);
	}

	/**
	 * Method to get the segment(s) for a plan
	 *
	 * @param   string  $id     ID of the plan form to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since   1.0.0
	 */
	public function getPlanSegment($id, $query)
	{
		return array((int) $id => $id);
	}
}
