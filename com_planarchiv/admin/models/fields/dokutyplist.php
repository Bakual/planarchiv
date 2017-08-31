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
 * Dokutyplist Field class for com_planarchiv
 *
 * @since    1.0.0
 */
class JFormFieldDokutyplist extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since    1.0.0
	 */
	protected $type = 'Dokutyplist';

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
		$query->select('id AS value, CONCAT(title_' . $langCode . ', " (", code_' . $langCode . ', ")") AS text');
		$query->from('#__planarchiv_dokutyp');
		$query->where('state = 1');
		$query->order('title_' . $langCode);

		$db->setQuery($query);

		return $db->loadObjectList();
	}
}
