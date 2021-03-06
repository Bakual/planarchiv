<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Administrator
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Anlagetyplist Field class for com_planarchiv
 *
 * @since    1.0.0
 */
class JFormFieldAnlagetyplist extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.0.0
	 */
	protected $type = 'Anlagetyplist';

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
		if (!empty($this->class))
		{
			$attribs['list.attr']['class'] = $this->class;
		}
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

		return JHtml::_('select.genericlist', $options, $this->name, $attribs);
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
		$db = JFactory::getDbo();
		$langCode = substr(JFactory::getLanguage()->getTag(), 0, 2);

		$query = $db->getQuery(true);
		$query->select('id AS value, CONCAT(title_' . $langCode . ', " (", code, ")") AS text, CONCAT("data-code=\"", code, "\"") AS attr');
		$query->from('#__planarchiv_anlagetyp');
		$query->where('state = 1');
		$query->order('title_' . $langCode);

		$db->setQuery($query);

		$options = $db->loadObjectList();

		return array_merge(parent::getOptions(), $options);
	}
}
