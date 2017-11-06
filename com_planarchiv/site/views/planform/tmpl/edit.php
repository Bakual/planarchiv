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
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.tabstate');
JHtml::_('stylesheet', 'com_planarchiv/planarchiv.css', array('version' => 'auto', 'relative' => true));

?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'plan.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task, document.getElementById('adminForm'));
        } else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php
	if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<form
		action="<?php echo JRoute::_('index.php?option=com_planarchiv&view=planform&id=' . (int) $this->item->id); ?>"
		method="post" name="adminForm" id="adminForm" class="form-validate form form-vertical">
		<fieldset>
			<?php echo $this->form->renderField('title'); ?>

			<?php echo JHtml::_('bootstrap.startTabSet', 'planform', array('active' => 'basic')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'planform', 'location', JText::_('COM_PLANARCHIV_TAB_LOCATION')); ?>
                <?php echo $this->form->renderField('didok_id'); ?>

                <?php echo JHtml::_('bootstrap.startTabSet', 'planformlocation', array('active' => 'ort')); ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'planformlocation', 'ort', JText::_('COM_PLANARCHIV_ORT_LABEL')); ?>
                        <div class="row-fluid">
                            <div class="pull-left rightMargin">
                                <?php echo $this->form->renderField('dfa_id'); ?>
                            </div>
                            <div class="pull-left">
                                <?php echo $this->form->renderField('GebDfaLfnr'); ?>
                            </div>
                        </div>
                        <?php echo $this->form->renderField('stockwerk_id'); ?>
                        <?php echo JHtml::_('bootstrap.endTab'); ?>
                        <?php echo JHtml::_('bootstrap.addTab', 'planformlocation', 'strecke', JText::_('COM_PLANARCHIV_STRECKE_LABEL')); ?>
                        <div class="row-fluid">
                            <div class="pull-left rightMargin">
                                <?php echo $this->form->renderField('Strecke'); ?>
                            </div>
                            <div class="pull-left rightMargin">
                                <?php echo $this->form->renderField('km'); ?>
                            </div>
                            <div class="pull-left">
                                <?php echo $this->form->renderField('richtung_didok_id'); ?>
                            </div>
                        </div>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php echo JHtml::_('bootstrap.endTabSet'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'planform', 'basic', JText::_('COM_PLANARCHIV_TAB_BASIC')); ?>
				<div class="row-fluid">
					<div class="pull-left rightMargin">
						<?php echo $this->form->renderField('ErstellDatum'); ?>
					</div>
					<div class="pull-left">
						<?php echo $this->form->renderField('ersteller_id'); ?>
					</div>
				</div>
				<div class="row-fluid">
					<div class="pull-left rightMargin">
						<?php echo $this->form->renderField('AenderungsDatum'); ?>
					</div>
					<div class="pull-left">
						<?php echo $this->form->renderField('Index1'); ?>
					</div>
				</div>
				<?php echo $this->form->renderField('CAD_Auftrag'); ?>
                <div class="row-fluid">
                    <div class="pull-left rightMargin">
                        <?php echo $this->form->renderField('anlagetyp_id'); ?>
                    </div>
                    <div class="pull-left">
                        <?php echo $this->form->renderField('AnlageLfnr'); ?>
                    </div>
                </div>
				<div class="row-fluid">
					<div class="pull-left rightMargin">
						<?php echo $this->form->renderField('dokutyp_id'); ?>
					</div>
					<div class="pull-left">
						<?php echo $this->form->renderField('DokuTypNr'); ?>
					</div>
				</div>
				<div class="row-fluid">
					<div class="pull-left rightMargin">
						<?php echo $this->form->renderField('zurzeitbei_id'); ?>
					</div>
					<div class="pull-left">
						<?php echo $this->form->renderField('zurzeitbei_date'); ?>
					</div>
				</div>
				<?php echo $this->form->renderField('Maengelliste'); ?>
				<?php echo $this->form->renderField('Bemerkung'); ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'planform', 'files', JText::_('COM_PLANARCHIV_TAB_FILES')); ?>
				<?php foreach($this->form->getFieldset('files') as $field): ?>
					<?php echo $field->getControlGroup(); ?>
				<?php endforeach; ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'planform', 'details', JText::_('COM_PLANARCHIV_TAB_DETAILS')); ?>
				<div class="row-fluid">
					<div class="span6">
						<?php foreach($this->form->getFieldset('info') as $field): ?>
							<?php echo $field->getControlGroup(); ?>
						<?php endforeach; ?>
					</div>
					<div class="span6">
						<?php foreach($this->form->getFieldset('global') as $field): ?>
							<?php echo $field->getControlGroup(); ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('plan.save')">
					<i class="icon-ok"></i> <?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('plan.cancel')">
					<i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
			<?php if ($this->item->id) : ?>
				<div class="btn-group">
					<?php echo $this->form->getInput('contenthistory'); ?>
				</div>
			<?php endif; ?>
		</div>
	</form>
</div>
