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
 * Orts list controller class.
 *
 * @package        PlanArchiv.Administrator
 *
 * @since          1.0.0
 */
class PlanarchivControllerOrts extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string $name   The model name. Optional.
	 * @param   string $prefix The class prefix. Optional.
	 * @param   array  $config Configuration array for model. Optional.
	 *
	 * @return  PlanarchivModelOrt|boolean  Model object on success; otherwise false on failure.
	 *
	 * @since 1.0.0
	 */
	public function &getModel($name = 'Ort', $prefix = 'PlanarchivModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}