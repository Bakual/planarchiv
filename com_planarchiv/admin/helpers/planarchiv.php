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
use Joomla\Registry\Registry;

/**
 * Planarchiv Helper
 *
 * @since  1.0.0
 */
class PlanarchivHelper
{
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
