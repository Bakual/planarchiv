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

/**
 * PlanArchiv Component Controller
 * @since  1.0
 */
class PlanarchivController extends JControllerLegacy
{
	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $default_view = 'plan';

	/**
	 * View method.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   array   $urlparams An array of safe url parameters and their variable types, for valid values see
	 *                             {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  A JControllerLegacy object to support chaining.
	 *
	 * @since   1.0.0
	 */
	public function display($cachable = false, $urlparams = array())
	{
		$cachable      = Factory::getUser()->get('id') ? false : true;
		$safeurlparams = array(
			'id'               => 'INT',
			'catid'            => 'INT',
			'limit'            => 'INT',
			'limitstart'       => 'INT',
			'filter_order'     => 'CMD',
			'filter_order_Dir' => 'CMD',
			'lang'             => 'CMD',
			'filter-search'    => 'STRING',
			'return'           => 'BASE64',
			'Itemid'           => 'INT',
		);

		return parent::display($cachable, $safeurlparams);
	}
}
