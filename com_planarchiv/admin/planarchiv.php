<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_planarchiv'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Joomla doesn't autoload JFile and JFolder
JLoader::register('JFile', JPATH_LIBRARIES . '/joomla/filesystem/file.php');
JLoader::register('JFolder', JPATH_LIBRARIES . '/joomla/filesystem/folder.php');

// Register Helperclass for autoloading
JLoader::register('PlanarchivHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/planarchiv.php');

JHtml::_('behavior.tabstate');

$controller = JControllerLegacy::getInstance('Planarchiv');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
