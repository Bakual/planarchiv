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

$user       = JFactory::getUser();
$canEdit    = ($user->authorise('core.edit', 'com_planarchiv'));
$canEditOwn = ($user->authorise('core.edit.own', 'com_planarchiv'));
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
?>
<div class="category-list<?php echo $this->pageclass_sfx; ?> planarchiv-plans-container<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
	<?php endif; ?>
</div>

<form action="<?php echo JRoute::_('index.php?option=com_planarchiv&view=plans'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="planList">
				<thead>
				<tr>
					<th class="hidden"></th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'plans.title', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_PLANARCHIV_ORT_LABEL', 'didok_title', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_PLANARCHIV_DFA_LABEL', 'dfa_title', $listDirn, $listOrder); ?>
					</th>
                    <th>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PLANARCHIV_ANLAGETYP_LABEL', 'anlagetyp_title', $listDirn, $listOrder); ?>
                    </th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$canEdit    = $user->authorise('core.edit', 'com_planarchiv.category.'.$item->catid);
					$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own', 'com_planarchiv.category.'.$item->catid) && $item->created_by == $user->id;
					$canChange  = $user->authorise('core.edit.state', 'com_planarchiv.category.'.$item->catid) && $canCheckin;
					$returnPage = base64_encode(JUri::getInstance());
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="hidden">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php if ($item->zurzeitbei_id) : ?>
									<?php $date = ($item->zurzeitbei_date != '0000-00-00 00:00:00') ? JHtml::_('date', $item->zurzeitbei_date, JText::_('DATE_FORMAT_LC4')) : '?'; ?>
									<?php $tooltip = JText::sprintf('COM_PLANARCHIV_ZUR_ZEIT_BEI', $date, $item->zurzeitbei_name); ?>
									<span class="icon-warning-2 hasTooltip" title="<?php echo $tooltip; ?>"> </span>
								<?php endif; ?>
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'plans.', $canCheckin); ?>
								<?php endif; ?>
								<a href="<?php echo JRoute::_('index.php?option=com_planarchiv&view=plan&id=' . $item->id);?>">
									<?php echo $this->escape($item->title) ?: '<span class="label label-info">' . JText::_('COM_PLANARCHIV_NONAME') . '</span>'; ?>
								</a>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_planarchiv&task=plan.edit&id=' . $item->id . '&return=' . $returnPage); ?>">
										<span class="icon-edit"> </span>
									</a>
								<?php endif; ?>
								<div class="small">
									<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
								</div>
							</div>
						</td>
						<td>
							<?php echo $item->didok_title . ' <small>(' . $item->didok . ')</small>'; ?>
						</td>
						<td>
							<?php echo $item->dfa_title . ' <small>(' . $item->dfa_code . $item->GebDfaLfnr . ')</small>'; ?>
						</td>
                        <td>
                            <?php echo $item->anlagetyp_title . ' <small>(' . $item->anlagetyp_code . '-' . $item->AnlageLfnr  . ')</small>'; ?>
                        </td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
        <div class="pull-right">
            <?php echo $this->pagination->getResultsCounter() ?>
        </div>
        <?php echo $this->pagination->getListFooter(); ?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
        <div class="clearfix"></div>
    </div>
</form>