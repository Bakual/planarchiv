<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * @since       1.0.0
 */
class PlanarchivController extends BaseController
{
	/**
	 * @var string
	 * @since 1.0.0
	 */
	protected $default_view = 'main';

	/**
	 * @param bool $cachable
	 * @param bool $urlparams
	 *
	 * @return bool|\JControllerLegacy
	 *
	 * @since 1.0.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view   = $this->input->get('view', 'main');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		$views  = array('gebaeude', 'stockwerk', 'anlage', 'planart');

		// Check for edit form.
		if (in_array($view, $views) && $layout == 'edit' && !$this->checkEditId('com_planarchiv.edit.' . $view, $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_planarchiv&view=main', false));

			return false;
		}

		return parent::display();
	}
}