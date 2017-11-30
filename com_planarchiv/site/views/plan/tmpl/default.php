<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

JHtml::_('bootstrap.tooltip');
JHtml::_('stylesheet', 'com_planarchiv/planarchiv.css', array('version' => 'auto', 'relative' => true));
JLoader::register('ContactHelperRoute', JPATH_SITE . '/components/com_contact/helpers/route.php');

$user       = JFactory::getUser();
$canEdit    = $user->authorise('core.edit', 'com_planarchiv.category.' . $this->item->catid);
$canEditOwn = $user->authorise('core.edit.own', 'com_planarchiv.category.' . $this->item->catid) && $this->item->created_by == $user->id;
?>
<div class="item-page<?php echo $this->pageclass_sfx; ?> planarchiv-plan-container<?php echo $this->pageclass_sfx; ?>">
    <a href="index.php?option=com_planarchiv&view=plans"><?php echo JText::_('COM_PLANARCHIV_BACK_TO_LIST'); ?></a>
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<div class="page-header">
		<h2><?php echo $this->escape($this->item->title) ?: '<span class="text-warning">' . JText::_('COM_PLANARCHIV_NONAME') . '</span>'; ?></h2>
	</div>
	<?php if ($canEdit || $canEditOwn) : ?>
		<div class="pull-right">
			<a href="<?php echo JRoute::_('index.php?option=com_planarchiv&task=plan.edit&id=' . $this->item->id . '&return=' . base64_encode(JUri::getInstance())); ?>">
				<?php $icon = $this->item->state ? 'edit' : 'eye-close'; ?>
				<span class="icon-<?php echo $icon; ?>"></span>
				<?php echo JText::_('JGLOBAL_EDIT'); ?>
			</a>
		</div>
	<?php endif; ?>
	<dl class="plan-info muted">
		<dt class="plan-info-term">
			<?php echo JText::_('COM_PLANARCHIV_PLAN_INFO'); ?>
		</dt>

		<?php if ($this->item->ersteller_id) : ?>
			<dd class="createdby">
				<?php $erstellerLink = JRoute::_(ContactHelperRoute::getContactRoute($this->item->ersteller_id . ':' . $this->item->ersteller_alias, $this->item->ersteller_catid)); ?>
				<?php echo JText::sprintf('COM_PLANARCHIV_CREATED_BY', '<a href="' . $erstellerLink . '">' . $this->item->ersteller_name . '</a>'); ?>
			</dd>
		<?php endif; ?>

		<?php if ($this->item->zurzeitbei_id) : ?>
			<dd class="zurzeitbei">
				<?php $zurzeitbeiLink = JRoute::_(ContactHelperRoute::getContactRoute($this->item->zurzeitbei_id . ':' . $this->item->zurzeitbei_alias, $this->item->zurzeitbei_catid)); ?>
				<?php $zurzeitbeiDate = ($this->item->zurzeitbei_date !== '0000-00-00 00:00:00') ? JHtml::_('date', $this->item->zurzeitbei_date, JText::_('DATE_FORMAT_LC3')) : '?'; ?>
				<?php echo JText::sprintf('COM_PLANARCHIV_ZUR_ZEIT_BEI', $zurzeitbeiDate, '<a href="' . $zurzeitbeiLink . '">' . $this->item->zurzeitbei_name . '</a>'); ?>
			</dd>
		<?php endif; ?>

		<dd class="category-name">
			<?php echo JText::sprintf('COM_PLANARCHIV_CATEGORY', $this->escape($this->item->category_title)); ?>
		</dd>

		<dd class="create">
			<span class="icon-calendar" aria-hidden="true"></span>
			<time datetime="<?php echo JHtml::_('date', $this->item->ErstellDatum, 'c'); ?>">
				<?php $erstellDatum = ($this->item->ErstellDatum !== '0000-00-00 00:00:00') ? JHtml::_('date', $this->item->ErstellDatum, JText::_('DATE_FORMAT_LC3')) : '?'; ?>
				<?php echo JText::sprintf('COM_PLANARCHIV_CREATED_DATE_ON', $erstellDatum); ?>
			</time>
		</dd>

		<dd class="modified">
			<span class="icon-calendar" aria-hidden="true"></span>
			<time datetime="<?php echo JHtml::_('date', $this->item->AenderungsDatum, 'c'); ?>">
				<?php $aenderungsDatum = ($this->item->AenderungsDatum !== '0000-00-00 00:00:00') ? JHtml::_('date', $this->item->AenderungsDatum, JText::_('DATE_FORMAT_LC3')) : '?'; ?>
				<?php echo JText::sprintf('COM_PLANARCHIV_LAST_UPDATED', $aenderungsDatum); ?>
			</time>
		</dd>

		<dd class="index">
			<span class="icon-stack" aria-hidden="true"></span>
			<?php echo JText::_('COM_PLANARCHIV_INDEX_LABEL') . ': ' . $this->escape($this->item->Index1); ?>
		</dd>

		<dd class="mangelliste">
			<span class="icon-lightning" aria-hidden="true"></span>
			<?php $mangel = $this->item->Maengelliste ? 'JYES' : 'JNO'; ?>
			<?php echo JText::_('COM_PLANARCHIV_MANGELLISTE_LABEL') . ': ' . JText::_($mangel); ?>
		</dd>

		<dd class="mangelliste">
			<span class="icon-paperclip" aria-hidden="true"></span>
			<?php $original = $this->item->original ? 'JYES' : 'JNO'; ?>
			<?php echo JText::_('COM_PLANARCHIV_ORIGINAL_LABEL') . ': ' . JText::_($original); ?>
		</dd>

		<dd class="cad">
			<span class="icon-file-2" aria-hidden="true"></span>
			<?php echo JText::_('COM_PLANARCHIV_CAD_LABEL') . ': ' . $this->escape($this->item->CAD_Auftrag); ?>
		</dd>
	</dl>
	<?php if ($this->item->Strecke) : ?>
		<h3><?php echo JText::_('COM_PLANARCHIV_STRECKE_LABEL'); ?></h3>
		<div class="well well-small">
			<div class="row-fluid">
				<div class="span3">
					<h4><?php echo JText::_('COM_PLANARCHIV_ORTSCHAFT_DIDOK_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->didok_title) . ' (' . $this->item->didok . ')'; ?>
				</div>
				<div class="span3">
					<h4><?php echo JText::_('COM_PLANARCHIV_STRECKE_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->Strecke); ?>
				</div>
				<div class="span3">
					<h4><?php echo JText::_('COM_PLANARCHIV_KM_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->km); ?>
				</div>
				<div class="span3">
					<h4><?php echo JText::_('COM_PLANARCHIV_DIRECTION_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->richtung_title) . ' (' . strtoupper($this->item->richtung_didok) . ')'; ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<h3><?php echo JText::_('COM_PLANARCHIV_ORT_LABEL'); ?></h3>
		<div class="well well-small">
			<div class="row-fluid">
				<div class="span4">
					<h4><?php echo JText::_('COM_PLANARCHIV_ORTSCHAFT_DIDOK_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->didok_title) . ' (' . $this->item->didok . ')'; ?>
				</div>
				<div class="span4">
					<h4><?php echo JText::_('COM_PLANARCHIV_DFA_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->dfa_title) . ' (' . $this->escape($this->item->dfa_code) . $this->item->GebDfaLfnr . ')'; ?>
				</div>
				<div class="span4">
					<h4><?php echo JText::_('COM_PLANARCHIV_STOCKWERK_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->stockwerk_title); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<h3><?php echo JText::_('COM_PLANARCHIV_ANLAGETYP_LABEL'); ?></h3>
	<?php echo $this->escape($this->item->anlagetyp_title) . ' (' . $this->escape($this->item->anlagetyp_code) . '-' . $this->item->AnlageLfnr . ')'; ?>

	<h3><?php echo JText::_('COM_PLANARCHIV_DOCUMENT_LABEL'); ?></h3>
	<div class="well well-small">
		<div class="row-fluid">
			<div class="span4">
				<h4><?php echo JText::_('COM_PLANARCHIV_DOKUTYP_LABEL'); ?></h4>
				<?php echo $this->escape($this->item->dokutyp_title) . ' (' . $this->escape($this->item->dokutyp_code) . $this->item->DokuTypNr . ')'; ?>
			</div>
			<div class="span4">
				<h4><?php echo JText::_('COM_PLANARCHIV_SIZE_LABEL'); ?></h4>
				<?php echo $this->escape($this->item->size); ?>
			</div>
			<div class="span4">
				<h4><?php echo JText::_('COM_PLANARCHIV_ALIGNMENT_LABEL'); ?></h4>
				<?php echo JText::_('COM_PLANARCHIV_ALIGNMENT_' . $this->item->alignment . '_LABEL'); ?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<h4><?php echo JText::_('COM_PLANARCHIV_DIRECTORY_LABEL'); ?></h4>
				<?php $path = $this->params->get('filepath') . '\\' . $this->item->didok[0] . '\\' . $this->item->didok; ?>
				<?php if ($this->item->Strecke) : ?>
					<?php $path .= '\\' . $this->item->didok . '-(' . $this->item->richtung_didok . ')_km' . number_format($this->item->km, 3, '.', '') . '\\'; ?>
				<?php else : ?>
					<?php $path .= '\\' . $this->item->dfa_code . $this->item->GebDfaLfnr . '\\'; ?>
				<?php endif; ?>
				<?php $path = strtolower($path); ?>
				<a href="file:<?php echo str_replace('\\', '/', $path); ?>">
					<?php echo $path; ?>
				</a>
			</div>
			<div class="span8">
				<h4><?php echo JText::_('COM_PLANARCHIV_FILES_LABEL'); ?></h4>
				<?php if ($this->item->Strecke) : ?>
					<?php $filename = $this->item->didok . '-' . $this->item->richtung_didok; ?>
				<?php else : ?>
					<?php $filename = $this->item->didok . '-' . $this->item->dfa_code . $this->item->GebDfaLfnr; ?>
				<?php endif; ?>
				<?php $filename .= '-' . $this->item->anlagetyp_code . '-' . $this->item->AnlageLfnr . '--' . $this->item->dokutyp_code . $this->item->DokuTypNr; ?>
				<?php $filename = strtolower($filename); ?>
				<?php $linkedFile = $this->item->title ?: $filename; ?>
				<?php $files = explode(',', $this->item->files); ?>
				<dl class="dl-horizontal">
					<?php foreach ($files AS $ext) : ?>
						<dt><?php echo $ext; ?></dt>
						<dd>
							<a target="_blank" href="file:<?php echo str_replace('\\', '/', $path . $linkedFile . '.' . $ext); ?>">
								<?php echo $this->escape($filename . '.' . $ext); ?>
							</a>
						</dd>
					<?php endforeach; ?>
				</dl>
			</div>
		</div>
	</div>

	<h3><?php echo JText::_('COM_PLANARCHIV_BEMERKUNG_LABEL'); ?></h3>
	<?php echo $this->escape($this->item->Bemerkung); ?>

</div>
