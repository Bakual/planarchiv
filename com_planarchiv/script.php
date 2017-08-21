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
}
