<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

JHtml::_('stylesheet', 'com_planarchiv/planarchivadmin.css', array('version' => 'auto', 'relative' => true));
?>
<div id="j-main-container" class="planarchiv">
	<ul class="thumbnails">
		<li class="span2">
			<a class="thumbnail" href="index.php?option=com_planarchiv&view=dfas">
				<span class="icon-home"> </span>
				<h3 class="center"><?php echo JText::_('COM_PLANARCHIV_DFAS_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span2">
			<a class="thumbnail" href="index.php?option=com_planarchiv&view=didoks">
				<span class="icon-home-2"> </span>
				<h3 class="center"><?php echo JText::_('COM_PLANARCHIV_DIDOKS_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span2">
			<a class="thumbnail" href="index.php?option=com_planarchiv&view=stockwerks">
				<span class="icon-menu-3"> </span>
				<h3 class="center"><?php echo JText::_('COM_PLANARCHIV_STOCKWERKS_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span2">
			<a class="thumbnail" href="index.php?option=com_planarchiv&view=anlagetyps">
				<span class="icon-flash"> </span>
				<h3 class="center"><?php echo JText::_('COM_PLANARCHIV_ANLAGETYPS_TITLE'); ?></h3>
			</a>
		</li>
		<li class="span2">
			<a class="thumbnail" href="index.php?option=com_planarchiv&view=dokutyps">
				<span class="icon-stack"> </span>
				<h3 class="center"><?php echo JText::_('COM_PLANARCHIV_DOKUTYPS_TITLE'); ?></h3>
			</a>
		</li>
	</ul>
	<ul class="thumbnails">
		<li class="span2">
			<a class="thumbnail" href="index.php?option=com_categories&extension=com_planarchiv">
				<span class="icon-folder"> </span>
				<h3 class="center"><?php echo JText::_('JCATEGORIES'); ?></h3>
			</a>
		</li>
	</ul>
</div>