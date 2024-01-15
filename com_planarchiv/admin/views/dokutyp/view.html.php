<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * View to edit a dokutyp.
 *
 * @package        Planarchiv.Administrator
 *
 * @since          1.0.0
 */
class PlanarchivViewDokutyp extends JViewLegacy
{
	/**
	 * @var
	 *
	 * @since 1.0.0
	 */
	protected $state;
	/**
	 * @var
	 *
	 * @since 1.0.0
	 */
	protected $item;
	/**
	 * @var
	 *
	 * @since 1.0.0
	 */
	protected $form;

	/**
	 * Display the view
	 *
	 * @since  1.0.0
	 *
	 * @param null $tpl
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since    1.0.0
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user       = Factory::getUser();
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->id);
		$canDo      = PlanarchivHelper::getActions();
		JToolbarHelper::title(JText::sprintf('COM_PLANARCHIV_PAGE_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT')), JText::_('COM_PLANARCHIV_SPEAKERS_TITLE'), JText::_('COM_PLANARCHIV_SPEAKER')), 'pencil-2 dokutyps');

		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::apply('dokutyp.apply');
				JToolbarHelper::save('dokutyp.save');
				JToolbarHelper::save2new('dokutyp.save2new');
			}
			JToolbarHelper::cancel('dokutyp.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $user->id))
				{
					JToolbarHelper::apply('dokutyp.apply');
					JToolbarHelper::save('dokutyp.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('dokutyp.save2new');
					}
				}
			}

			JToolbarHelper::cancel('dokutyp.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}