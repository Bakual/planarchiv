<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

/**
 * Planarchiv Helper
 *
 * @since  1.0.0
 */
class PlanarchivHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param string $vName The name of the active view.
	 *
	 * @return void
	 * @since  1.0.0
	 *
	 */
	public static function addSubmenu($vName = 'main')
	{
		Sidebar::addEntry(
			Text::_('COM_PLANARCHIV_MENU_DFAS'),
			'index.php?option=com_planarchiv&view=dfas',
			$vName == 'dfas'
		);
		Sidebar::addEntry(
			Text::_('COM_PLANARCHIV_MENU_DIDOKS'),
			'index.php?option=com_planarchiv&view=didoks',
			$vName == 'didoks'
		);
		Sidebar::addEntry(
			Text::_('COM_PLANARCHIV_MENU_STOCKWERKS'),
			'index.php?option=com_planarchiv&view=stockwerks',
			$vName == 'stockwerks'
		);
		Sidebar::addEntry(
			Text::_('COM_PLANARCHIV_MENU_ANLAGETYPS'),
			'index.php?option=com_planarchiv&view=anlagetyps',
			$vName == 'anlagetyps'
		);
		Sidebar::addEntry(
			Text::_('COM_PLANARCHIV_MENU_DOKUTYPS'),
			'index.php?option=com_planarchiv&view=dokutyps',
			$vName == 'dokutyps'
		);
		Sidebar::addEntry(
			Text::_('COM_PLANARCHIV_MENU_CATEGORY'),
			'index.php?option=com_categories&extension=com_planarchiv',
			$vName == 'categories'
		);
	}

	/**
	 * Get the actions for ACL
	 *
	 * @param int $categoryId
	 *
	 * @return \JObject
	 * @since 1.0.0
	 *
	 */
	public static function getActions($categoryId = 0)
	{
		$user   = Factory::getApplication()->getIdentity();
		$result = new Registry();

		if (empty($categoryId))
		{
			$assetName = 'com_planarchiv';
		}
		else
		{
			$assetName = 'com_planarchiv.category.' . (int) $categoryId;
		}

		$actions = Access::getActionsFromFile(
			JPATH_ADMINISTRATOR . '/components/com_planarchiv/access.xml',
			"/access/section[@name='component']/"
		);

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
