<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->ignore_fieldsets = array('general', 'basic', 'info');

// Check if tmpl=component was set (needed for com_associations)
$jinput = Factory::getApplication()->input;
$tmpl   = $jinput->getCmd('tmpl') === 'component' ? '&tmpl=component' : '';
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'dfa.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_planarchiv&layout=edit&id='.(int) $this->item->id . $tmpl); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span5">
				<?php foreach($this->form->getFieldset('basic') as $field): ?>
					<?php echo $field->getControlGroup(); ?>
				<?php endforeach; ?>
			</div>
			<div class="span4">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $jinput->getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>