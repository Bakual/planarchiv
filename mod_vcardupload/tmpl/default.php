<?php
/**
 * @package     PlanArchiv
 * @subpackage  Module.VCardUpload
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

Factory::getDocument()->addStyleDeclaration('
	#vcardSelect {
		height: 0;
		overflow: hidden;
		width: 0;
	}
');
?>
<div class="vcardupload<?php echo $moduleclass_sfx; ?>">
	<div id="upload_limit" class="well well-small">
		<?php echo Text::sprintf('MOD_VCARDUPLOAD_UPLOAD_LIMIT', ModVcarduploadHelper::getMaxUploadValue()); ?>
	</div>
	<form action="<?php echo Route::_('index.php?option=com_planarchiv&task=contact.upload'); ?>" method="post", name="vcardUpload" class="form-vertical" enctype="multipart/form-data">
		<input type="file" name="vcard" id="vcardSelect" accept="text/vcard" onchange="form.submit()">
		<label for="vcardSelect" class="btn"><?php echo Text::_('MOD_VCARDUPLOAD_UPLOAD'); ?></label>
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>
</div>