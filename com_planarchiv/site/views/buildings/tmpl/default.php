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

JHtml::_('behavior.core');
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('stylesheet', 'com_planarchiv/planarchiv.css', array('version' => 'auto', 'relative' => true));

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$filters = $this->filterForm->getGroup('filter');
?>
<div class="category-list<?php echo $this->pageclass_sfx; ?> planarchiv-plans-container<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php endif; ?>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_planarchiv&view=buildings'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <div class="js-stools clearfix">
            <?php foreach ($filters as $fieldName => $field) : ?>
                <?php echo $field->input; ?>
            <?php endforeach; ?>
        </div>
        <hr>
        <?php if (empty($this->items)) : ?>
            <div class="alert alert-no-items">
                <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
            </div>
        <?php else : ?>
            <ul class="unstyled">
                <?php foreach ($this->items as $item) : ?>
                    <li>
                        <?php $params = '&filter[didok_id]=' . $item->didok_id; ?>
                        <a href="index.php?option=com_planarchiv&view=plans<?php echo $params; ?>">
                            <?php echo $item->dfa_title . ' (' . $item->dfa_code . '-' . $item->GebDfaLfnr . ')'; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="pull-right">
            <?php echo $this->pagination->getResultsCounter() ?>
        </div>
        <?php echo $this->pagination->getListFooter(); ?>
        <div class="clearfix"></div>
    </div>
</form>