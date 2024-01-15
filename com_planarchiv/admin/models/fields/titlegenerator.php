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
use Joomla\CMS\Language\Text;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');

/**
 * Dfalist Field class for com_planarchiv
 *
 * @since    1.0.0
 */
class JFormFieldTitlegenerator extends JFormFieldText
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.0.0
	 */
	protected $type = 'Titlegenerator';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since    1.0.0
	 */
	public function getInput()
	{
		// Create JavaScript
		$js = 'function generateTitle() {'
			. 'let value = "";'
			. 'let didokSelect = document.getElementById(\'jform_didok_id\');'
			. 'value += didokSelect.options[didokSelect.selectedIndex].getAttribute(\'data-didok\').toLowerCase();'
			. 'let languageSelect = document.getElementById(\'jform_language\');'
			. 'let language = languageSelect.options[languageSelect.selectedIndex].value.toLowerCase();'
			. 'let strecke = document.getElementById(\'jform_Strecke\').value;'
			. 'if (strecke) {'
				. 'let richtungSelect = document.getElementById(\'jform_richtung_didok_id\');'
				. 'let richtungSelectValue = richtungSelect.options[richtungSelect.selectedIndex].getAttribute(\'data-didok\');'
				. 'if (richtungSelectValue) {'
					. 'value += "-" + richtungSelectValue.toLowerCase();'
				. '} else {'
					. 'alert ("' . Text::sprintf('COM_PLANARCHIV_BUTTON_GENERATE_TITLE_ERROR', Text::_('COM_PLANARCHIV_DIRECTION_LABEL')) . '");'
				. '}'
			. '} else {'
				. 'let dfaSelect = document.getElementById(\'jform_dfa_id\');'
				. 'let dfaSelectValue = dfaSelect.options[dfaSelect.selectedIndex].getAttribute(\'data-\' + language);'
				. 'if (dfaSelectValue) {'
					. 'value += "-" + dfaSelectValue.toLowerCase();'
					. 'value += document.getElementById(\'jform_GebDfaLfnr\').value;'
				. '} else {'
					. 'alert ("' . Text::sprintf('COM_PLANARCHIV_BUTTON_GENERATE_TITLE_ERROR', Text::_('COM_PLANARCHIV_DFA_LABEL')) . '");'
				. '}'
			. '}'
			. 'let anlageSelect = document.getElementById(\'jform_anlagetyp_id\');'
			. 'value += "-" + anlageSelect.options[anlageSelect.selectedIndex].getAttribute(\'data-code\').toLowerCase();'
			. 'value += "-" + document.getElementById(\'jform_AnlageLfnr\').value;'
			. 'let dokutypSelect = document.getElementById(\'jform_dokutyp_id\');'
			. 'value += "--" + dokutypSelect.options[dokutypSelect.selectedIndex].getAttribute(\'data-\' + language).toLowerCase();'
			. 'value += document.getElementById(\'jform_DokuTypNr\').value;'
			. 'document.getElementById("' . $this->id . '").value = value;'
		. '}';

		Factory::getDocument()->addScriptDeclaration($js);

		// Add Button to generate the title
		$html = '<div class="input-append">';
		$html .= parent::getInput();
		$html .= '<button class="btn" type="button" id="' . $this->id . '_btn" onclick="generateTitle()"><span class="icon-lightning"> </span>' . Text::_('COM_PLANARCHIV_BUTTON_GENERATE_TITLE') . '</button>';

		return $html;
	}
}
