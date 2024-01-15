<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_planarchiv'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Joomla doesn't autoload JFile and JFolder
JLoader::register('JFile', JPATH_LIBRARIES . '/joomla/filesystem/file.php');
JLoader::register('JFolder', JPATH_LIBRARIES . '/joomla/filesystem/folder.php');

// Register Helperclass for autoloading
JLoader::register('PlanarchivHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/planarchiv.php');

$controller = BaseController::getInstance('Planarchiv');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
