<?php
/**
 * @package     PlanArchiv
 * @subpackage  Module.VCardUpload
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;

require_once __DIR__ . '/helper.php';

// Bail out if user isn't allowed to create a sermon.
$user = Factory::getUser();

if (!$user->authorise('core.create', 'com_planarchiv'))
{
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''));

require JModuleHelper::getLayoutPath('mod_vcardupload', $params->get('layout', 'default'));
