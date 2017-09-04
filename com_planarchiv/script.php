<?php
/**
 * Scriptfile for the PlanArchiv installation
 *
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * Class Com_PlanarchivInstallerScript
 *
 * @since  4.x
 */
class Com_PlanarchivInstallerScript extends JInstallerScript
{
	/**
	 * @var  JApplicationCms  Holds the application object
	 *
	 * @since 1.0.0
	 */
	private $app;

	/**
	 * The extension name. This should be set in the installer script.
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $extension = 'com_planarchiv';

	/**
	 * Minimum PHP version required to install the extension
	 *
	 * @var    string
	 * @since  1.0.0
	 */
	protected $minimumPhp = '5.3.10';

	/**
	 * @var  string  During an update, it will be populated with the old release version
	 *
	 * @since 1.0.0
	 */
	private $oldRelease;

	/**
	 *  Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->app = JFactory::getApplication();
	}

	/**
	 * Method to install the component
	 *
	 * @param   JInstallerAdapterComponent $parent Installerobject
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function install($parent)
	{
		// Notice $parent->getParent() returns JInstaller object
		$parent->getParent()->setRedirectUrl('index.php?option=com_planarchiv');
	}

	/**
	 * Method to uninstall the component
	 *
	 * @param   JInstallerAdapterComponent $parent Installerobject
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function uninstall($parent)
	{
	}

	/**
	 * method to update the component
	 *
	 * @param   JInstallerAdapterComponent $parent Installerobject
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function update($parent)
	{
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @param   string                     $type   'install', 'update' or 'discover_install'
	 * @param   JInstallerAdapterComponent $parent Installerobject
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function postflight($type, $parent)
	{
	}

	private function saveContentTypes()
	{
		// Adding content_type for content history
		$table = JTable::getInstance('Contenttype', 'JTable');

		// FieldMappings
		$common                       = new stdClass;
		$common->core_content_item_id = 'id';
		$common->core_title           = 'title';
		$common->core_state           = 'state';
		$common->core_alias           = null;
		$common->core_created_time    = 'created';
		$common->core_modified_time   = 'modified';
		$common->core_body            = 'Bemerkung';
		$common->core_hits            = null;
		$common->core_publish_up      = null;
		$common->core_publish_down    = null;
		$common->core_access          = null;
		$common->core_params          = null;
		$common->core_featured        = null;
		$common->core_metadata        = null;
		$common->core_language        = null;
		$common->core_images          = null;
		$common->core_urls            = null;
		$common->core_version         = 'version';
		$common->core_ordering        = null;
		$common->core_metakey         = null;
		$common->core_metadesc        = null;
		$common->core_catid           = 'catid';
		$common->core_xreference      = null;
		$common->asset_id             = null;
		$field_mappings               = new stdClass;
		$field_mappings->common       = $common;
		$field_mappings->special      = new stdClass;

		// History
		$history                      = new stdClass;
		$history->form_file           = 'components/com_planarchiv/models/forms/plan.xml';
		$history->hide_fields         = array('checked_out', 'checked_out_time', 'version');
		$history->display_lookup      = array();
		$source_user1                 = new stdClass;
		$source_user1->source_column  = 'created_by';
		$source_user1->target_table   = '#__users';
		$source_user1->target_column  = 'id';
		$source_user1->display_column = 'name';
		$source_user2                 = clone $source_user1;
		$source_user2->source_column  = 'modified_by';
		$source_catid                 = clone $source_user1;
		$source_catid->source_column  = 'catid';
		$source_catid->target_table   = '#__categories';
		$source_catid->display_column = 'title';
		$history->display_lookup[]    = $source_user1;
		$history->display_lookup[]    = $source_user2;
		$history->display_lookup[]    = $source_catid;

		// Create/Update Plan Type
		$table->load(array('type_alias' => 'com_planarchiv.plan'));

		$special          = new stdClass;
		$special->dbtable = '#__planarchiv_plan';
		$special->key     = 'id';
		$special->type    = 'Plan';
		$special->prefix  = 'PlanarchivTable';
		$special->config  = 'array()';

		$table_object          = new stdClass;
		$table_object->special = $special;

		$contenttype['type_id']                 = ($table->type_id) ? $table->type_id : 0;
		$contenttype['type_title']              = 'Plan';
		$contenttype['type_alias']              = 'com_planarchiv.plan';
		$contenttype['table']                   = json_encode($table_object);
		$contenttype['rules']                   = '';
		$contenttype['router']                  = '';
		$contenttype['field_mappings']          = json_encode($field_mappings);
		$contenttype['content_history_options'] = json_encode($history);

		$table->save($contenttype);

		// Create/Update Category Type
		$table->type_id = 0;
		$table->load(array('type_alias' => 'com_planarchiv.category'));

		$field_mappings->common->core_state         = 'published';
		$field_mappings->common->core_created_time  = 'created_time';
		$field_mappings->common->core_modified_time = 'modified_time';
		$field_mappings->common->core_body          = 'description';
		$field_mappings->common->core_images        = null;
		$field_mappings->common->core_access        = 'access';
		$field_mappings->common->core_params        = 'params';
		$field_mappings->common->core_metadata      = 'metadata';
		$field_mappings->common->core_ordering      = null;
		$field_mappings->common->core_catid         = 'parent_id';
		$field_mappings->common->asset_id           = 'asset_id';
		$field_mappings->special->parent_id         = 'parent_id';
		$field_mappings->special->lft               = 'lft';
		$field_mappings->special->rgt               = 'rgt';
		$field_mappings->special->level             = 'level';
		$field_mappings->special->path              = 'path';
		$field_mappings->special->extension         = 'extension';
		$field_mappings->special->note              = 'note';

		$special          = new stdClass;
		$special->dbtable = '#__categories';
		$special->key     = 'id';
		$special->type    = 'Category';
		$special->prefix  = 'JTable';
		$special->config  = 'array()';

		$history                = new stdClass;
		$history->form_file     = 'administrator/components/com_categories/models/forms/category.xml';
		$history->hideFields    = array('asset_id', 'checked_out', 'checked_out_time', 'version', 'lft', 'rgt', 'level', 'path', 'extension');
		$history->ignoreChanges = array('modified_user_id', 'modified_time', 'checked_out', 'checked_out_time', 'version', 'hits', 'path');
		$history->convertToInt  = array('publish_up', 'publish_down');

		$displayLookup1                = new stdClass;
		$displayLookup1->sourceColumn  = 'created_user_id';
		$displayLookup1->targetTable   = '#__users';
		$displayLookup1->targetColumn  = 'id';
		$displayLookup1->displayColumn = 'name';
		$history->displayLookup[]      = $displayLookup1;

		$displayLookup2               = clone $displayLookup1;
		$displayLookup2->sourceColumn = 'modified_user_id';
		$displayLookup2->targetTable  = '#__users';
		$history->displayLookup[]     = $displayLookup2;

		$displayLookup3                = clone $displayLookup1;
		$displayLookup3->sourceColumn  = 'access';
		$displayLookup3->targetTable   = '#__viewlevels';
		$displayLookup3->displayColumn = 'title';
		$history->displayLookup[]      = $displayLookup3;

		$displayLookup4               = clone $displayLookup1;
		$displayLookup4->sourceColumn = 'parent_id';
		$displayLookup4->targetTable  = '#__categories';
		$history->displayLookup[]     = $displayLookup4;

		$table_object          = new stdClass;
		$table_object->special = $special;

		$contenttype['type_id']                 = ($table->type_id) ? $table->type_id : 0;
		$contenttype['type_title']              = 'Planarchiv Category';
		$contenttype['type_alias']              = 'com_planarchiv.category';
		$contenttype['table']                   = json_encode($table_object);
		$contenttype['rules']                   = '';
		$contenttype['router']                  = '';
		$contenttype['field_mappings']          = json_encode($field_mappings);
		$contenttype['content_history_options'] = json_encode($history);

		$table->save($contenttype);

		return;
	}
}
