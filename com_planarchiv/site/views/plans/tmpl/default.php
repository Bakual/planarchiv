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
JHtml::_('formbehavior.chosen', 'select');

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
						<?php echo JHtml::_('searchtools.sort', 'COM_PLANARCHIV_ANLAGETYP_LABEL', 'anlagetyp_title', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_PLANARCHIV_ORT_LABEL', 'didok_title', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_FIELD_CREATED_LABEL', 'plans.ErstellDatum', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ID', 'plans.id', $listDirn, $listOrder); ?>
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
							<?php echo $item->anlagetyp_title . ' <small>(' . $item->anlagetyp_code . ')</small>'; ?>
						</td>
						<td>
							<?php echo $item->didok_title . ' <small>(' . $item->didok . ')</small>'; ?>
						</td>
						<td>
							<?php if ($item->ErstellDatum !== '0000-00-00 00:00:00') : ?>
								<?php echo JHtml::_('date', $item->ErstellDatum); ?>
							<?php endif; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
		<?php echo $this->pagination->getListFooter(); ?>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>