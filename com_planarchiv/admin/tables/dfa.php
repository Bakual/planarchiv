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
class PlanarchivTableDfa extends JTable
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
		parent::__construct('#__planarchiv_dfa', 'id', $db);

		$this->setColumnAlias('published', 'state');
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
