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
use Joomla\CMS\Form\Field\TextField;
use Joomla\CMS\Form\FormHelper;

/**
 * Docuformatlist Field class for com_planarchiv
 *
 * @since    1.0.0
 */
class JFormFieldDocuformatlist extends TextField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.0.0
	 */
	protected $type = 'Docuformatlist';

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
		$fileformats = explode(',', $params->get('docuformats', ''));
		$options     = array();
		$options[]   = '';

		foreach ($fileformats as $format)
		{
			$format        = trim($format);
			$option        = new stdClass();
			$option->value = $format;
			$option->text  = $format;
			$options[]     = $option;
		}

		return $options;
	}
}
