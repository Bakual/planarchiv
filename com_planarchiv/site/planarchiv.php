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
use Joomla\CMS\MVC\Controller\BaseController;

// loading composer autoloader
require_once JPATH_ADMINISTRATOR . '/components/com_planarchiv' . '/vendor/autoload.php';

$jinput = Factory::getApplication()->input;

// Register Helperclasses for autoloading
JLoader::discover('PlanarchivHelper', JPATH_BASE . '/components/com_planarchiv/helpers');

// Load languages and merge with fallbacks
$jlang = Factory::getLanguage();
$jlang->load('com_planarchiv', JPATH_BASE . '/components/com_planarchiv', 'en-GB', true);
$jlang->load('com_planarchiv', JPATH_BASE . '/components/com_planarchiv', null, true);

$controller = BaseController::getInstance('Planarchiv');
$controller->execute($jinput->get('task'));
$controller->redirect();
