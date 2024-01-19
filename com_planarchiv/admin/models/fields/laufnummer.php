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
use Joomla\CMS\Form\Field\TextField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/**
 * Dfalist Field class for com_planarchiv
 *
 * @since    1.0.0
 */
class JFormFieldLaufnummer extends TextField
{
	/**
	 * The form field type.
	 *
	 * @var      string
	 * @since    1.0.0
	 */
	protected $type = 'Laufnummer';

	/**
	 * Stores if JavaScript is already loaded.
	 *
	 * @var      bool
	 * @since    1.0.0
	 */
	private static $jsLoaded;

	/**
	 * Method to get the field input markup
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0.0
	 */
	protected function getInput()
	{
		$textField = parent::getInput();

		if (!self::$jsLoaded)
		{
			$js = 'function fetchNextNumber(element) {
                    var DidokSelect = document.getElementById("jform_didok_id");
                    var DidokIndex = DidokSelect.selectedIndex;
                    var DidokValue = DidokSelect[DidokIndex].value;
                    var DfaSelect = document.getElementById("jform_dfa_id");
                    var DfaIndex = DfaSelect.selectedIndex;
                    var DfaValue = DfaSelect[DfaIndex].value;
                    var DfaLfnr = document.getElementById("jform_GebDfaLfnr").value;
                    var AnlageSelect = document.getElementById("jform_anlagetyp_id");
                    var AnlageIndex = AnlageSelect.selectedIndex;
                    var AnlageValue = AnlageSelect[AnlageIndex].value;
                    var AnlageLfnr = document.getElementById("jform_AnlageLfnr").value;
                    var DokutypSelect = document.getElementById("jform_dokutyp_id");
                    var DokutypIndex = DokutypSelect.selectedIndex;
                    var DokutypValue = DokutypSelect[DokutypIndex].value;
                    xmlhttp = new XMLHttpRequest();
			        xmlhttp.onreadystatechange=function(){
    				    if (xmlhttp.readyState==4 && xmlhttp.status==200){
        			    	var data = JSON.parse(xmlhttp.responseText);
        					if (data.status==1){
	    		    	    	document.getElementById(element).value = data.id;
                            } else {
           						alert(data.msg);
                            }
		            	}
                    }
                    var params = "&element="+element+"&didok="+DidokValue+"&dfa="+DfaValue+"&dfalfnr="+DfaLfnr+"&anlage="+AnlageValue+"&anlagelfnr="+AnlageLfnr+"&dokutyp="+DokutypValue;
			        xmlhttp.open("GET","' . Uri::root() . 'index.php?option=com_planarchiv&task=laufnummer.lookup&format=json"+params,true);
			        xmlhttp.send();
            }';
			Factory::getDocument()->addScriptDeclaration($js);
			self::$jsLoaded = true;
		}

		$reference = (string) $this->element['reference'];

		$html = '<div class="input-group">';
		$html .= $textField;
		$html .= '<button class="btn btn-primary hasTooltip" type="button" onclick="fetchNextNumber(\'' . $this->id . '\')" title="' . Text::_('COM_PLANARCHIV_FETCH_LFNR') . '"><span class="icon-flash"></span></button>';
		$html .= '</div>';

		return $html;
	}
}
