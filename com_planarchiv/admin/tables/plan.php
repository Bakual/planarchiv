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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Versioning\VersionableTableInterface;

/**
 * Plan Table class
 *
 * @package        Planarchiv.Administrator
 *
 * @since          1.0.0
 */
class PlanarchivTablePlan extends Table implements VersionableTableInterface
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
			throw new Exception(Text::_('COM_PLANARCHIV_ERROR_ORT_OR_STRECKE'));
		}

		if (!$this->dfa_id && !$this->GebDfaLfnr && !$this->Stockwerk && !$this->Strecke && !$this->km && !$this->richtung_didok_id)
		{
			throw new Exception(Text::_('COM_PLANARCHIV_ERROR_ORT_OR_STRECKE_REQUIRED'));
		}

		// Verify that the title is unique
		$table = Table::getInstance('Plan', 'PlanarchivTable');

		if ($table->load(array('title' => $this->title, 'state' => '1')) && ($table->id != $this->id || $this->id == 0))
		{
			throw new Exception(Text::_('COM_PLANARCHIV_ERROR_UNIQUE_TITLE'));
		}

		if ($this->GebDfaLfnr)
        {
            $this->GebDfaLfnr = str_pad($this->GebDfaLfnr, 2, 0, STR_PAD_LEFT);
        }

		if ($this->DokuTypNr)
        {
            $this->DokuTypNr = str_pad($this->DokuTypNr, 2, 0, STR_PAD_LEFT);
        }

        if ($this->AnlageLfnr)
        {
            $this->AnlageLfnr = str_pad($this->AnlageLfnr, 2, 0, STR_PAD_LEFT);
        }

        $this->Index1 = strtoupper($this->Index1);

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
		$date = Factory::getDate();
		$user = Factory::getApplication()->getIdentity();

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

		// Nullable fields, convert empty string to NULL
		$nullable = [
			'zurzeitbei_id',
			'zurzeitbei_date',
			'ersteller_id',
			'ErstellDatum',
			'AenderungsDatum',
			'km'
		];

		foreach ($nullable as $field)
		{
			if (!$this->$field)
			{
				$this->$field = null;
			}
		}

		return parent::store($updateNulls);
	}

	/**
	 * Get the type alias for the history table
	 *
	 * @return  string  The alias as described above
	 *
	 * @since   2.0.0
	 */
	public function getTypeAlias()
	{
		return $this->typeAlias;
	}
}
