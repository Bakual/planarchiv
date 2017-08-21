<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

JHtml::_('bootstrap.tooltip');

$user       = JFactory::getUser();
$canEdit    = ($user->authorise('core.edit', 'com_planarchiv'));
$canEditOwn = ($user->authorise('core.edit.own', 'com_planarchiv'));
$limit      = (int) $this->params->get('limit', '');
$this->document->addScriptDeclaration('Joomla.tableOrdering = function(order, dir, task, form) {
		form.filter_order.value = order;
		form.filter_order_Dir.value = dir;
		Joomla.submitform(task, form);
	}');
?>
<div class="category-list<?php echo $this->pageclass_sfx; ?> ss-plan-container<?php echo $this->pageclass_sfx; ?>">
	<?php
	if ($this->params->get('show_page_heading', 1)) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
</div>
