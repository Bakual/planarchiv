<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;

defined('_JEXEC') or die;

/**
 * Routing class of com_planarchiv
 *
 * @since  1.0.0
 */
class PlanarchivRouter extends RouterView
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
		$buildings = new RouterViewconfiguration('buildings');
		$this->registerView($buildings);
		$plans = new RouterViewconfiguration('plans');
		$this->registerView($plans);
		$plan = new RouterViewconfiguration('plan');
		$plan->setKey('id');
		$this->registerView($plan);
		$form = new RouterViewconfiguration('planform');
		$form->setKey('id');
		$this->registerView($form);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
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
