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
use Joomla\CMS\Uri\Uri;

HtmlHelper::_('bootstrap.tooltip');
HtmlHelper::_('stylesheet', 'com_planarchiv/planarchiv.css', array('version' => 'auto', 'relative' => true));
HtmlHelper::_('script', 'com_planarchiv/clipboard/clipboard.min.js', array('version' => 'auto', 'relative' => true));
JLoader::register('ContactHelperRoute', JPATH_SITE . '/components/com_contact/helpers/route.php');

Factory::getDocument()->addScriptDeclaration('document.addEventListener("DOMContentLoaded", function(event) {
	new ClipboardJS(\'.clipboard\');
})');

$user       = Factory::getApplication()->getIdentity();
$canEdit    = $user->authorise('core.edit', 'com_planarchiv.category.' . $this->item->catid);
$canEditOwn = $user->authorise('core.edit.own', 'com_planarchiv.category.' . $this->item->catid) && $this->item->created_by == $user->id;
?>
<div class="item-page<?php echo $this->pageclass_sfx; ?> planarchiv-plan-container<?php echo $this->pageclass_sfx; ?>">
	<a href="index.php?option=com_planarchiv&view=plans"><?php echo Text::_('COM_PLANARCHIV_BACK_TO_LIST'); ?></a>
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>
	<div class="page-header">
		<h2><?php echo $this->escape($this->item->title) ?: '<span class="text-warning">' . Text::_('COM_PLANARCHIV_NONAME') . '</span>'; ?></h2>
	</div>
	<?php if ($canEdit || $canEditOwn) : ?>
		<div class="float-end">
			<a href="<?php echo Route::_('index.php?option=com_planarchiv&task=plan.edit&id=' . $this->item->id . '&return=' . base64_encode(Uri::getInstance())); ?>">
				<?php $icon = $this->item->state ? 'edit' : 'eye-close'; ?>
				<span class="icon-<?php echo $icon; ?>"></span>
				<?php echo Text::_('JGLOBAL_EDIT'); ?>
			</a>
		</div>
	<?php endif; ?>
	<dl class="plan-info text-muted lh-1">
		<dt class="plan-info-term">
			<?php echo Text::_('COM_PLANARCHIV_PLAN_INFO'); ?>
		</dt>

		<?php if ($this->item->ersteller_id) : ?>
			<dd class="createdby">
				<span class="fa fa-user" aria-hidden="true"></span>
				<?php $erstellerLink = Route::_(ContactHelperRoute::getContactRoute($this->item->ersteller_id . ':' . $this->item->ersteller_alias, $this->item->ersteller_catid)); ?>
				<?php echo Text::sprintf('COM_PLANARCHIV_CREATED_BY', '<a href="' . $erstellerLink . '">' . $this->item->ersteller_name . '</a>'); ?>
			</dd>
		<?php endif; ?>

		<?php if ($this->item->zurzeitbei_id) : ?>
			<dd class="zurzeitbei">
				<span class="fa fa-user" aria-hidden="true"></span>
				<?php $zurzeitbeiLink = Route::_(ContactHelperRoute::getContactRoute($this->item->zurzeitbei_id . ':' . $this->item->zurzeitbei_alias, $this->item->zurzeitbei_catid)); ?>
				<?php $zurzeitbeiDate = ($this->item->zurzeitbei_date && $this->item->zurzeitbei_date !== '0000-00-00 00:00:00') ? HTMLHelper::_('date', $this->item->zurzeitbei_date, Text::_('DATE_FORMAT_LC3')) : '?'; ?>
				<?php echo Text::sprintf('COM_PLANARCHIV_ZUR_ZEIT_BEI', $zurzeitbeiDate, '<a href="' . $zurzeitbeiLink . '">' . $this->item->zurzeitbei_name . '</a>'); ?>
			</dd>
		<?php endif; ?>

		<dd class="category-name">
			<span class="fa fa-folder-open" aria-hidden="true"></span>
			<?php echo Text::sprintf('COM_PLANARCHIV_CATEGORY', $this->escape($this->item->category_title)); ?>
		</dd>

		<dd class="create">
			<span class="fa fa-calendar" aria-hidden="true"></span>
			<time datetime="<?php echo HTMLHelper::_('date', $this->item->ErstellDatum, 'c'); ?>">
				<?php $erstellDatum = ($this->item->ErstellDatum !== '0000-00-00 00:00:00') ? HTMLHelper::_('date', $this->item->ErstellDatum, Text::_('DATE_FORMAT_LC3')) : '?'; ?>
				<?php echo Text::sprintf('COM_PLANARCHIV_CREATED_DATE_ON', $erstellDatum); ?>
			</time>
		</dd>

		<dd class="modified">
			<span class="fa fa-calendar" aria-hidden="true"></span>
			<time<?php echo $this->item->AenderungsDatum ? (' datetime="' . HTMLHelper::_('date', $this->item->AenderungsDatum, 'c') . '"') : ''; ?>>
				<?php $aenderungsDatum = ($this->item->AenderungsDatum && $this->item->AenderungsDatum !== '0000-00-00 00:00:00') ? HTMLHelper::_('date', $this->item->AenderungsDatum, Text::_('DATE_FORMAT_LC3')) : '?'; ?>
				<?php echo Text::sprintf('COM_PLANARCHIV_LAST_UPDATED', $aenderungsDatum); ?>
			</time>
		</dd>

		<dd class="index">
			<span class="fa fa-copy" aria-hidden="true"></span>
			<?php echo Text::_('COM_PLANARCHIV_INDEX_LABEL') . ': ' . $this->escape($this->item->Index1); ?>
		</dd>

		<dd class="mangelliste">
			<span class="fa fa-bolt" aria-hidden="true"></span>
			<?php $mangel = $this->item->Maengelliste ? 'JYES' : 'JNO'; ?>
			<?php echo Text::_('COM_PLANARCHIV_MANGELLISTE_LABEL') . ': ' . Text::_($mangel); ?>
		</dd>

		<dd class="mangelliste">
			<span class="fa fa-paperclip" aria-hidden="true"></span>
			<?php $original = $this->item->original ? 'JYES' : 'JNO'; ?>
			<?php echo Text::_('COM_PLANARCHIV_ORIGINAL_LABEL') . ': ' . Text::_($original); ?>
		</dd>

		<dd class="cad">
			<span class="fa fa-file" aria-hidden="true"></span>
			<?php echo Text::_('COM_PLANARCHIV_CAD_LABEL') . ': ' . $this->escape($this->item->CAD_Auftrag); ?>
		</dd>
	</dl>
	<?php if ($this->item->Strecke) : ?>
		<h3><?php echo Text::_('COM_PLANARCHIV_STRECKE_LABEL'); ?></h3>
		<div class="card card-body bg-light p-2">
			<div class="row">
				<div class="col-md-3">
					<h4><?php echo Text::_('COM_PLANARCHIV_ORTSCHAFT_DIDOK_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->didok_title) . ' (' . $this->item->didok . ')'; ?>
				</div>
				<div class="col-md-3">
					<h4><?php echo Text::_('COM_PLANARCHIV_STRECKE_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->Strecke); ?>
				</div>
				<div class="col-md-3">
					<h4><?php echo Text::_('COM_PLANARCHIV_KM_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->km); ?>
				</div>
				<div class="col-md-3">
					<h4><?php echo Text::_('COM_PLANARCHIV_DIRECTION_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->richtung_title) . ' (' . strtoupper($this->item->richtung_didok) . ')'; ?>
				</div>
			</div>
		</div>
	<?php else : ?>
		<h3><?php echo Text::_('COM_PLANARCHIV_ORT_LABEL'); ?></h3>
		<div class="card mb-2">
			<div class="card-body bg-light p-2">
				<div class="row">
					<div class="col-md-4">
						<h4 class="card-title"><?php echo Text::_('COM_PLANARCHIV_ORTSCHAFT_DIDOK_LABEL'); ?></h4>
						<?php echo $this->escape($this->item->didok_title) . ' (' . $this->item->didok . ')'; ?>
					</div>
					<div class="col-md-4">
						<h4 class=""><?php echo Text::_('COM_PLANARCHIV_DFA_LABEL'); ?></h4>
						<?php echo $this->escape($this->item->dfa_title) . ' (' . $this->escape($this->item->dfa_code) . $this->item->GebDfaLfnr . ')'; ?>
					</div>
					<div class="col-md-4">
						<h4 class="card-title"><?php echo Text::_('COM_PLANARCHIV_STOCKWERK_LABEL'); ?></h4>
						<?php echo $this->escape($this->item->stockwerk_title); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="mb-2">
		<h3><?php echo Text::_('COM_PLANARCHIV_ANLAGETYP_LABEL'); ?></h3>
		<?php echo $this->escape($this->item->anlagetyp_title) . ' (' . $this->escape($this->item->anlagetyp_code) . '-' . $this->item->AnlageLfnr . ')'; ?>
	</div>

	<h3><?php echo Text::_('COM_PLANARCHIV_DOCUMENT_LABEL'); ?></h3>
	<div class="card mb-2">

		<div class="card-body bg-light p-2">
			<div class="row">
				<div class="col-md-4">
					<h4><?php echo Text::_('COM_PLANARCHIV_DOKUTYP_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->dokutyp_title) . ' (' . $this->escape($this->item->dokutyp_code) . $this->item->DokuTypNr . ')'; ?>
				</div>
				<div class="col-md-4">
					<h4><?php echo Text::_('COM_PLANARCHIV_SIZE_LABEL'); ?></h4>
					<?php echo $this->escape($this->item->size); ?>
				</div>
				<div class="col-md-4">
					<h4><?php echo Text::_('COM_PLANARCHIV_ALIGNMENT_LABEL'); ?></h4>
					<?php echo Text::_('COM_PLANARCHIV_ALIGNMENT_' . $this->item->alignment . '_LABEL'); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<h4><?php echo Text::_('COM_PLANARCHIV_DIRECTORY_LABEL'); ?></h4>
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
					<button class="btn btn-secondary btn-sm clipboard ms-2" data-clipboard-text="<?php echo $path; ?>"
							title="<?php echo Text::_('COM_PLANARCHIV_CLIPBOARD_TOOLTIP'); ?>">Copy
					</button>
				</div>
				<div class="col-md-8">
					<h4><?php echo Text::_('COM_PLANARCHIV_FILES_LABEL'); ?></h4>
					<?php if ($this->item->Strecke) : ?>
						<?php $filename = $this->item->didok . '-' . $this->item->richtung_didok; ?>
					<?php else : ?>
						<?php $filename = $this->item->didok . '-' . $this->item->dfa_code . $this->item->GebDfaLfnr; ?>
					<?php endif; ?>
					<?php $filename .= '-' . $this->item->anlagetyp_code . '-' . $this->item->AnlageLfnr . '--' . $this->item->dokutyp_code . $this->item->DokuTypNr; ?>
					<?php $filename = strtolower($filename); ?>
					<?php $linkedFile = $this->item->title ?: $filename; ?>
					<?php $files = explode(',', $this->item->files); ?>
					<?php if (($pdf = array_search('pdf', $files)) !== false) : ?>
						<?php unset($files[$pdf]); ?>
						<?php $pdf = true; ?>
					<?php endif; ?>
					<dl class="row">
						<?php if ($pdf) : ?>
							<dt class="col-sm-3 border-bottom pb-1 mb-1 text-end">pdf</dt>
							<dd class="col-sm-9 border-bottom pb-1 mb-1">
								<a target="_blank"
								   href="file:<?php echo str_replace('\\', '/', $path . $linkedFile . '.pdf'); ?>">
									<?php echo $this->escape($filename . '.pdf'); ?>
								</a>
								<button class="btn btn-secondary btn-sm clipboard ms-2"
										data-clipboard-text="<?php echo $path . $linkedFile . '.pdf'; ?>"
										title="<?php echo Text::_('COM_PLANARCHIV_CLIPBOARD_TOOLTIP'); ?>">Copy
								</button>
							</dd>
						<?php endif; ?>
						<?php foreach ($files as $ext) : ?>
							<dt class="col-sm-3 text-end"><?php echo $ext; ?></dt>
							<dd class="col-sm-9">
								<a target="_blank"
								   href="file:<?php echo str_replace('\\', '/', $path . $linkedFile . '.' . $ext); ?>">
									<?php echo $this->escape($filename . '.' . $ext); ?>
								</a>
								<button class="btn btn-secondary btn-sm clipboard ms-2"
										data-clipboard-text="<?php echo $path . $linkedFile . '.' . $ext; ?>"
										title="<?php echo Text::_('COM_PLANARCHIV_CLIPBOARD_TOOLTIP'); ?>">Copy
								</button>
							</dd>
						<?php endforeach; ?>
					</dl>
				</div>
			</div>
		</div>
	</div>

	<div class="mb-3">
		<h3><?php echo Text::_('COM_PLANARCHIV_BEMERKUNG_LABEL'); ?></h3>
		<?php echo $this->escape($this->item->Bemerkung); ?>
	</div>
</div>
