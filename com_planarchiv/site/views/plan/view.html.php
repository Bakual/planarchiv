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
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Router\Route;

/**
 * HTML View class for the PlanArchiv Component
 *
 * @since  3.4
 */
class PlanarchivViewPlan extends HtmlView
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return mixed A string if successful, otherwise a Error object.
	 *
	 * @throws \Exception
	 * @since ?
	 */
	public function display($tpl = null)
	{
		$app = Factory::getApplication();

		if (!$app->input->get('id', 0, 'int'))
		{
			$app->redirect(Route::_('index.php?view=plans'), Text::_('JGLOBAL_RESOURCE_NOT_FOUND'), 'error');
		}

		// Get data from the model
		$state        = $this->get('State');
		$this->item   = $this->get('Item');
		$user         = Factory::getApplication()->getIdentity();
		$this->params = $state->get('params');

		if (!$this->item)
		{
			$app->enqueueMessage(Text::_('JGLOBAL_RESOURCE_NOT_FOUND'));
			$app->redirect(Route::_('index.php?view=plans'));
		}

		// Check if access is not public
		if ($this->item->category_access)
		{
			$groups = $user->getAuthorisedViewLevels();

			if (!in_array($this->item->category_access, $groups))
			{
				$app->redirect(Route::_('index.php?view=plans'), Text::_('JERROR_ALERTNOAUTHOR'), 'error');
			}
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx', ''));
		$this->maxLevel      = $this->params->get('maxLevel', -1);
		$this->_prepareDocument();

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 *
	 * @since ?
	 */
	protected function _prepareDocument()
	{
		$app   = Factory::getApplication();
		$menus = $app->getMenu();

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', Text::_('COM_PLANARCHIV_PLAN_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		// If the menu item does not concern this article
		if ($menu && ($menu->query['option'] != 'com_planarchiv' || $menu->query['view'] != 'plan' || $menu->query['id'] != $this->item->id))
		{
			if ($this->item->title)
			{
				$title = $this->item->title;
			}
		}

		// Check for empty title and add site name if param is set
		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->title;
		}

		$this->document->setTitle($title);

		// Add Breadcrumbs
		$pathway = $app->getPathway();
		$pathway->addItem($this->item->title);
	}
}
