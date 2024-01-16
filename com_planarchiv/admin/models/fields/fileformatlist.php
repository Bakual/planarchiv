<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Form\FormHelper;

/**
 * Fileformatlist Field class for com_planarchiv
 *
 * @since    1.0.0
 */
class JFormFieldFileformatlist extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.0.0
	 */
	protected $type = 'Fileformatlist';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		$this->value = explode(',', $this->value);

		return parent::getInput();
	}

	/**
	 * Method to get the field options.
	 *
	 * @return array The field option objects.
	 * @throws \Exception
	 *
	 * @since    1.0.0
	 */
	public function getOptions()
	{
		$params      = ComponentHelper::getParams('com_planarchiv');
		$fileformats = explode(',', $params->get('fileformats'));
		$options     = array();

		foreach ($fileformats as $format)
		{
			$format = trim($format);
			$options[$format] = $format;
		}

		return $options;
	}
}
