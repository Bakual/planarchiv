<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('stylesheet', 'com_planarchiv/planarchiv.css', array('version' => 'auto', 'relative' => true));

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$this->tab_name         = 'com-planarchiv-planform';
$this->ignore_fieldsets = [];
$this->useCoreUI        = true;
?>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php
	if ($this->params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<form action="<?php echo Route::_('index.php?option=com_planarchiv&view=planform&id=' . (int) $this->item->id); ?>"
			method="post" name="adminForm" id="adminForm" class="form-validate form form-vertical">
		<fieldset>
			<?php echo $this->form->renderField('title'); ?>
			<?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'details', 'recall' => true, 'breakpoint' => 768]); ?>

			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'location', Text::_('COM_PLANARCHIV_TAB_LOCATION')); ?>
			<?php echo $this->form->renderField('didok_id'); ?>

			<?php echo HTMLHelper::_('uitab.startTabSet', 'planformlocation', array('active' => 'ort')); ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'planformlocation', 'ort', Text::_('COM_PLANARCHIV_ORT_LABEL')); ?>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderField('dfa_id'); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->form->renderField('GebDfaLfnr'); ?>
				</div>
			</div>
			<?php echo $this->form->renderField('stockwerk_id'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<?php echo HTMLHelper::_('uitab.addTab', 'planformlocation', 'strecke', Text::_('COM_PLANARCHIV_STRECKE_LABEL')); ?>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderField('Strecke'); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->form->renderField('km'); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->form->renderField('richtung_didok_id'); ?>
				</div>
			</div>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'basic', Text::_('COM_PLANARCHIV_TAB_BASIC')); ?>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderField('ErstellDatum'); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->form->renderField('ersteller_id'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderField('AenderungsDatum'); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->form->renderField('Index1'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
			<?php echo $this->form->renderField('CAD_Auftrag'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderField('anlagetyp_id'); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->form->renderField('AnlageLfnr'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderField('dokutyp_id'); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->form->renderField('DokuTypNr'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderField('zurzeitbei_id'); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->form->renderField('zurzeitbei_date'); ?>
				</div>
			</div>
			<?php echo $this->form->renderField('Maengelliste'); ?>
			<?php echo $this->form->renderField('Bemerkung'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'files', Text::_('COM_PLANARCHIV_TAB_FILES')); ?>
			<?php echo $this->form->renderFieldset('files'); ?>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'details', Text::_('COM_PLANARCHIV_TAB_DETAILS')); ?>
			<div class="row">
				<div class="col-md-6">
					<?php echo $this->form->renderFieldset('info'); ?>
				</div>
				<div class="col-md-6">
					<?php echo $this->form->renderFieldset('global'); ?>
				</div>
			</div>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</fieldset>
		<div class="mb-2">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('plan.save')">
				<span class="icon-ok"></span> <?php echo Text::_('JSAVE') ?>
			</button>
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('plan.save2copy')">
				<span class="icon-save-copy"></span> <?php echo Text::_('COM_PLANARCHIV_SAVE_AS_COPY') ?>
			</button>
			<button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('plan.cancel')">
				<span	 class="icon-cancel"></span> <?php echo Text::_('JCANCEL') ?>
			</button>
			<?php if ($this->item->id) : ?>
				<?php echo $this->form->getInput('contenthistory'); ?>
				<?php if (Factory::getApplication()->getIdentity()->authorise('core.delete', 'com_planarchiv.category.' . $this->item->catid)) : ?>
					<button type="button" class="btn btn-danger"
							onclick="if(confirm('<?php echo Text::_('COM_PLANARCHIV_CONFIRM_DELETE'); ?>')) {document.getElementById('jform_state').value='-2';Joomla.submitbutton('plan.save');} else {return;}">
						<span class="icon-delete"></span> <?php echo Text::_('JACTION_DELETE') ?>
					</button>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</form>
</div>
