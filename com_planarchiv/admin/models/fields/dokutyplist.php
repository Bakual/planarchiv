<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Dokutyplist Field class for com_planarchiv
 *
 * @since    1.0.0
 */
class JFormFieldDokutyplist extends ListField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.0.0
	 */
	protected $type = 'Dokutyplist';

	/**
	 * Method to get the field input markup
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		$attribs                   = array();
		$attribs['id']             = $this->id;
		$attribs['option.key']     = 'value';
		$attribs['option.text']    = 'text';
		$attribs['option.attr']    = 'attr';
		$attribs['list.select']    = $this->value;
		$attribs['list.attr']      = array();
		$attribs['list.translate'] = false;

		// Initialize some list attributes.
		$attribs['list.attr']['class'] = $this->class ?: 'form-select';

		if ($this->required)
		{
			$attribs['list.attr']['required']      = true;
			$attribs['list.attr']['aria-required'] = true;
		}
        if ($this->onchange)
        {
            $attribs['list.attr']['onchange'] = $this->onchange;
        }

		// Get the field options.
		$options = (array) $this->getOptions();

		return HTMLHelper::_('select.genericlist', $options, $this->name, $attribs);
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
		$db = Factory::getDbo();
		$langCode = substr(Factory::getLanguage()->getTag(), 0, 2);

		$query = $db->getQuery(true);
		$query->select('id AS value, CONCAT(title_' . $langCode . ', " (", code_' . $langCode . ', ")") AS text');
		$query->select('CONCAT("data-de-de=\"", code_de, "\" ", "data-fr-fr=\"", code_fr, "\" ", "data-it-it=\"", code_it, "\"") AS attr');
		$query->from('#__planarchiv_dokutyp');
		$query->where('state = 1');
		$query->order('title_' . $langCode);

		$db->setQuery($query);

		$options = $db->loadObjectList();

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
