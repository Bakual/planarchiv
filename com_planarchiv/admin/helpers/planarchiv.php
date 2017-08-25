<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

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
	 * @param  string $vName The name of the active view.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = 'main')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_PLANARCHIV_MENU_GEBAEUDES'),
			'index.php?option=com_planarchiv&view=gebaeudes',
			$vName == 'gebaeudes'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PLANARCHIV_MENU_DIDOKS'),
			'index.php?option=com_planarchiv&view=didoks',
			$vName == 'didoks'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PLANARCHIV_MENU_STOCKWERKS'),
			'index.php?option=com_planarchiv&view=stockwerks',
			$vName == 'stockwerks'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PLANARCHIV_MENU_ANLAGETYPS'),
			'index.php?option=com_planarchiv&view=anlagetyps',
			$vName == 'anlagetyps'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PLANARCHIV_MENU_DOKUTYPS'),
			'index.php?option=com_planarchiv&view=dokutyps',
			$vName == 'dokutyps'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_PLANARCHIV_MENU_CATEGORY'),
			'index.php?option=com_categories&extension=com_planarchiv',
			$vName == 'categories'
		);
	}

	/**
	 * Get the actions for ACL
	 *
	 * @since 1.0.0
	 *
	 * @param int $categoryId
	 *
	 * @return \JObject
	 */
	public static function getActions($categoryId = 0)
	{
		$user = JFactory::getUser();
		$result = new JObject;

		if (empty($categoryId))
		{
			$assetName = 'com_planarchiv';
		}
		else
		{
			$assetName = 'com_planarchiv.category.' . (int) $categoryId;
		}

		$actions = JAccess::getActionsFromFile(
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
