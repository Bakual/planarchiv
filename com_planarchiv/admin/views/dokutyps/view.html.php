<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

/**
 * HTML View class for the PlanArchiv Component
 *
 * @since  1.0.0
 */
class PlanarchivViewDokutyps extends JViewLegacy
{
	/**
	 * @var
	 * @since 1.0.0
	 */
	public $filterForm;
	/**
	 * @var
	 * @since 1.0.0
	 */
	public $activeFilters;
	/**
	 * @var
	 * @since 1.0.0
	 */
	protected $items;
	/**
	 * @var
	 * @since 1.0.0
	 */
	protected $pagination;
	/**
	 * A state object
	 *
	 * @var    JObject
	 *
	 * @since  1.0.0
	 */
	protected $state;
	/**
	 * @var
	 * @since 1.0.0
	 */
	protected $sidebar;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return mixed A string if successful, otherwise a Error object.
	 *
	 * @throws Exception
	 *
	 * @since  1.0.0
	 */
	public function display($tpl = null)
	{
		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		PlanarchivHelper::addSubmenu('dokutyps');
		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since  1.0.0
	 */
	protected function addToolbar()
	{
		$canDo = PlanarchivHelper::getActions();

		JToolbarHelper::title(JText::_('COM_PLANARCHIV_DOKUTYPS_TITLE'));

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('dokutyp.add', 'JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
		{
			JToolbarHelper::editList('dokutyp.edit', 'JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::custom('dokutyps.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::custom('dokutyps.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);

			if ($this->state->get('filter.state') != 2)
			{
				JToolbarHelper::archiveList('dokutyps.archive', 'JTOOLBAR_ARCHIVE');
			}
			else
			{
				JToolbarHelper::unarchiveList('dokutyps.publish', 'JTOOLBAR_UNARCHIVE');
			}

			JToolbarHelper::checkin('dokutyps.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'dokutyps.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('dokutyps.trash', 'JTOOLBAR_TRASH');
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			JToolbarHelper::preferences('com_planarchiv');
		}
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   1.0.0
	 */
	protected function getSortFields()
	{
		return array(
			'dokutyps.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'dokutyps.state'    => JText::_('JSTATUS'),
			'dokutyps.title'    => JText::_('COM_PLANARCHIV_FIELD_NAME_LABEL'),
			'category_title'     => JText::_('JCATEGORY'),
			'dokutyps.hits'     => JText::_('JGLOBAL_HITS'),
			'dokutyps.id'       => JText::_('JGRID_HEADING_ID'),
		);
	}
}
