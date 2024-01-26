<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('stylesheet', 'com_planarchiv/planarchivadmin.css', array('version' => 'auto', 'relative' => true));
?>
<div id="j-main-container" class="j-main-container planarchiv-container">
	<div class="row row-cols-1 row-cols-md-2 g-2">
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-house fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_planarchiv&view=dfas">
					<h3 class="card-title"><?php echo Text::_('COM_PLANARCHIV_DFAS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-train fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_planarchiv&view=didoks">
					<h3 class="card-title"><?php echo Text::_('COM_PLANARCHIV_DIDOKS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-list-ol fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_planarchiv&view=stockwerks">
					<h3 class="card-title"><?php echo Text::_('COM_PLANARCHIV_STOCKWERKS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-bolt fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_planarchiv&view=anlagetyps">
					<h3 class="card-title"><?php echo Text::_('COM_PLANARCHIV_ANLAGETYPS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-copy fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_planarchiv&view=dokutyps">
					<h3 class="card-title"><?php echo Text::_('COM_PLANARCHIV_DOKUTYPS_TITLE'); ?></h3>
					</a>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card text-center">
				<div class="card-header bg-light">
					<span class="fas fa-folder-open fa-4x m-auto"></span>
				</div>
				<div class="card-body">
					<a class="stretched-link" href="index.php?option=com_categories&extension=com_planarchiv">
					<h3 class="card-title"><?php echo Text::_('JCATEGORIES'); ?></h3>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>