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

// loading composer autoloader
require_once JPATH_COMPONENT_ADMINISTRATOR . '/vendor/autoload.php';

$jinput = Factory::getApplication()->input;

// Register Helperclasses for autoloading
JLoader::discover('PlanarchivHelper', JPATH_COMPONENT . '/helpers');

// Load languages and merge with fallbacks
$jlang = Factory::getLanguage();
$jlang->load('com_planarchiv', JPATH_COMPONENT, 'en-GB', true);
$jlang->load('com_planarchiv', JPATH_COMPONENT, null, true);

$controller = JControllerLegacy::getInstance('Planarchiv');
$controller->execute($jinput->get('task'));
$controller->redirect();
