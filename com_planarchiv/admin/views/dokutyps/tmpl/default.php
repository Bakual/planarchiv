<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('dropdown.init');
HTMLHelper::_('formbehavior.chosen', 'select');

$user      = Factory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$archived  = $this->state->get('filter.state') == 2 ? true : false;
$trashed   = $this->state->get('filter.state') == -2 ? true : false;
?>
<form action="<?php echo JRoute::_('index.php?option=com_planarchiv&view=dokutyps'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif; ?>
		<?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="dokutypList">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
						<th width="1%" style="min-width:40px" class="nowrap center">
							<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'dokutyps.state', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'COM_PLANARCHIV_CODE_TITLE', 'code', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort',  'JGRID_HEADING_ID', 'dokutyps.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$canEdit    = $user->authorise('core.edit', 'com_planarchiv.category.'.$item->catid);
					$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own', 'com_planarchiv.category.'.$item->catid) && $item->created_by == $user->id;
					$canChange  = $user->authorise('core.edit.state', 'com_planarchiv.category.'.$item->catid) && $canCheckin;
					?>
					<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
						<td class="center hidden-phone">
							<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>
						<td class="center">
							<div class="btn-group">
								<?php echo HTMLHelper::_('jgrid.published', $item->state, $i, 'dokutyps.', $canChange, 'cb'); ?>
								<?php
								// Create dropdown items
								$action = $archived ? 'unarchive' : 'archive';
								HTMLHelper::_('actionsdropdown.' . $action, 'cb' . $i, 'dokutyps');

								$action = $trashed ? 'untrash' : 'trash';
								HTMLHelper::_('actionsdropdown.' . $action, 'cb' . $i, 'dokutyps');

								// Render dropdown list
								echo HTMLHelper::_('actionsdropdown.render', $this->escape($item->title));
								?>
							</div>
						</td>
						<td class="nowrap has-context">
							<div class="pull-left">
								<?php if ($item->checked_out) : ?>
									<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'dokutyps.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_planarchiv&task=dokutyp.edit&id=' . $item->id);?>">
										<?php echo $this->escape($item->title); ?>
									</a>
								<?php else : ?>
									<?php echo $this->escape($item->title); ?>
								<?php endif; ?>
								<div class="small">
									<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
								</div>
							</div>
							<div class="pull-left">
						</td>
						<td class="small hidden-phone">
							<?php echo $this->escape($item->code); ?>
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
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
