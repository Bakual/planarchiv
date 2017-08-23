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
?>
<div class="item-page<?php echo $this->pageclass_sfx; ?> planarchiv-plan-container<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<div class="page-header">
		<h2><?php echo $this->escape($this->item->title); ?></h2>
	</div>
	<dl class="plan-info muted">
		<dt class="plan-info-term">
			<?php echo JText::_('COM_PLANARCHIV_PLAN_INFO'); ?>
		</dt>

		<?php if ($this->item->PlanErsteller) : ?>
			<dd class="createdby">
				<?php echo JText::sprintf('COM_PLANARCHIV_CREATED_BY', $this->item->PlanErsteller); ?>
			</dd>
		<?php endif; ?>

		<dd class="category-name">
			<?php echo JText::sprintf('COM_PLANARCHIV_CATEGORY', $this->escape($this->item->category_title)); ?>
		</dd>

		<dd class="create">
			<span class="icon-calendar" aria-hidden="true"></span>
			<time datetime="<?php echo JHtml::_('date', $this->item->created, 'c'); ?>">
				<?php echo JText::sprintf('COM_PLANARCHIV_CREATED_DATE_ON', JHtml::_('date', $this->item->ErstellDatum, JText::_('DATE_FORMAT_LC3'))); ?>
			</time>
		</dd>

		<dd class="modified">
			<span class="icon-calendar" aria-hidden="true"></span>
			<time datetime="<?php echo JHtml::_('date', $this->item->modified, 'c'); ?>" itemprop="dateModified">
				<?php echo JText::sprintf('COM_PLANARCHIV_LAST_UPDATED', JHtml::_('date', $this->item->AenderungsDatum, JText::_('DATE_FORMAT_LC3'))); ?>
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

		<dd class="cad">
			<span class="icon-file-2" aria-hidden="true"></span>
			<?php echo JText::_('COM_PLANARCHIV_CAD_LABEL') . ': ' . $this->escape($this->item->CAD_Auftrag); ?>
		</dd>
	</dl>
	<h3><?php echo JText::_('COM_PLANARCHIV_ORT_LABEL'); ?></h3>
	<div class="row-fluid well well-small">
		<div class="span4">
			<h4><?php echo JText::_('COM_PLANARCHIV_DIDOK_LABEL'); ?></h4>
			<?php echo $this->escape($this->item->Ort); ?>
		</div>
		<div class="span4">
			<h4><?php echo JText::_('COM_PLANARCHIV_GEBAEUDE_LABEL'); ?></h4>
			<?php echo $this->escape($this->item->GebDfaTxt) . ' (' . $this->escape($this->item->GebDfaCode) . $this->item->GebDfaLfnr . ')'; ?>
		</div>
		<div class="span4">
			<h4><?php echo JText::_('COM_PLANARCHIV_STOCKWERK_LABEL'); ?></h4>
			<?php echo $this->escape($this->item->Stockwerk); ?>
		</div>
	</div>

	<h3><?php echo JText::_('COM_PLANARCHIV_ANLAGETYP_LABEL'); ?></h3>
	<?php echo $this->escape($this->item->AnlageTypTxt) . ' (' . $this->escape($this->item->AnlageTyp) . '-' . $this->item->AnlageLfnr . ')'; ?>

	<h3><?php echo JText::_('COM_PLANARCHIV_DOKUTYP_LABEL'); ?></h3>
	<?php echo $this->escape($this->item->DokuTypText) . ' (' . $this->escape($this->item->DokuTypNr) . ')'; ?>

	<h3><?php echo JText::_('COM_PLANARCHIV_BEMERKUNG_LABEL'); ?></h3>
	<?php echo $this->escape($this->item->Bemerkung); ?>

</div>
