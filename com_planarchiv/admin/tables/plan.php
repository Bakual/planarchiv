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
 * Speaker Table class
 *
 * @package        Planarchiv.Administrator
 *
 * @since          1.0.0
 */
class PlanarchivTablePlan extends JTable
{
	/**
	 * Constructor
	 *
	 * @param  JDatabaseDriver $db JDatabaseDriver object.
	 *
	 * @since 1.0.0
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__planarchiv_plan', 'id', $db);

		$this->setColumnAlias('published', 'state');

		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_planarchiv.plan'));
	}

	/**
	 * Method to perform sanity checks on the Table instance properties to ensure they are safe to store in the database.
	 *
	 * Child classes should override this method to make sure the data they are storing in the database is safe and as expected before storage.
	 * @return bool True if the instance is sane and able to be stored in the database.
	 *
	 * @throws \Exception
	 *
	 * @since   1.0.0
	 */
	public function check()
	{
		// Check that either "Ort" or "Strecke" is given, but not both.
		if (($this->dfa_id || $this->GebDfaLfnr || $this->Stockwerk) && ($this->Strecke || $this->km || $this->richtung_didok_id))
		{
			throw new Exception(JText::_('COM_PLANARCHIV_ERROR_ORT_OR_STRECKE'));
		}

		if (!$this->dfa_id && !$this->GebDfaLfnr && !$this->Stockwerk && !$this->Strecke && !$this->km && !$this->richtung_didok_id)
		{
			throw new Exception(JText::_('COM_PLANARCHIV_ERROR_ORT_OR_STRECKE_REQUIRED'));
		}

		return true;
	}

	/**
	 * Method to store a row in the database from the Table instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property
	 * values. If no primary key value is set a new row will be inserted into the database with the properties from the
	 * Table instance.
	 *
	 * @param   boolean $updateNulls True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.0.0
	 */
	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if (is_array($this->files))
		{
			$this->files = implode(',', $this->files);
		}

		if (empty($this->created_by))
		{
			$this->created_by = $user->id;
		}

		if (!intval($this->created))
		{
			$this->created = $date->toSql();
		}

		if ($this->id)
		{
			$this->modified    = $date->toSql();
			$this->modified_by = $user->id;
		}

		return parent::store($updateNulls);
	}
}
