<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers');

HTMLHelper::_('behavior.core');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('stylesheet', 'com_planarchiv/planarchiv.css', array('version' => 'auto', 'relative' => true));

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$filters = $this->filterForm->getGroup('filter');
?>
<div class="category-list<?php echo $this->pageclass_sfx; ?> planarchiv-plans-container<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php endif; ?>
</div>

<form action="<?php echo Route::_('index.php?option=com_planarchiv&view=buildings'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <div class="js-stools clearfix">
            <?php foreach ($filters as $fieldName => $field) : ?>
                <?php echo $field->input; ?>
            <?php endforeach; ?>
        </div>
        <hr>
		<div class="row-fluid">
			<div class="span6">
				<fieldset>
					<legend><?php echo Text::_('COM_PLANARCHIV_DFA_LABEL'); ?></legend>
					<?php if (empty($this->items)) : ?>
						<div class="alert alert-no-items">
							<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
						</div>
					<?php else : ?>
						<ul class="unstyled">
							<?php $didok = $this->activeFilters['didok_id']; ?>
							<?php foreach ($this->items as $item) : ?>
								<li>
									<?php $params = '&filter[didok_id]=' . $item->didok_id . '&filter[dfa_id]=' . $item->dfa_id . '&filter[GebDfaLfnr]=' . $item->GebDfaLfnr; ?>
									<a href="<?php echo Route::_('index.php?option=com_planarchiv&view=plans' . $params); ?>">
										<?php echo $item->dfa_title . ' (' . $item->dfa_code . $item->GebDfaLfnr . ')'; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<div>
						<?php echo $this->pagination->getResultsCounter() ?>
					</div>
					<?php echo $this->pagination->getListFooter(); ?>
				</fieldset>
			</div>
			<div class="span6">
				<fieldset>
					<legend><?php echo Text::_('COM_PLANARCHIV_STRECKE_LABEL'); ?></legend>
					<?php if (empty($this->strecken)) : ?>
						<div class="alert alert-no-items">
							<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
						</div>
					<?php else : ?>
						<ul class="unstyled">
							<?php foreach ($this->strecken as $item) : ?>
								<li>
									<?php $params = '&filter[Strecke]=S&filter[didok_id]=' . $item->didok_id . '&filter[richtung_didok_id]=' . $item->richtung_didok_id; ?>
									<a href="<?php echo Route::_('index.php?option=com_planarchiv&view=plans' . $params); ?>">
										<?php echo $item->didok_title . ' - ' . $item->richtung_title; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
        <div class="clearfix"></div>
    </div>
</form>