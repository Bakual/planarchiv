<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Controller class for the PlanArchiv Component
 *
 * @since  1.0.0
 */
class PlanarchivControllerLaufnummer extends BaseController
{
    /**
     * Lookup the next free Laufnummer
     *
     * @return  void  Echoes an AJAX response
     *
     * @since 1.0.0
     */
    public function lookup()
    {
        $jinput = Factory::getApplication()->input;

        $element = $jinput->getString('element');
        $element = str_replace('jform_', '', $element);

        $didok = $jinput->getInt('didok');
        $dfa = $jinput->getInt('dfa');
        $dfaLfnr = $jinput->get('dfalfnr');
        $anlage = $jinput->getInt('anlage');
        $anlageLfnr = $jinput->get('anlagelfnr');
        $dokutyp = $jinput->getInt('dokutyp');

        if (!$element) {
            $response = array(
                'status' => '0',
                'msg' => 'No element given',
            );
            echo json_encode($response);

            return;
        }

        if (!$didok) {
            $response = array(
                'status' => '0',
                'msg' => 'No didok selected',
            );
            echo json_encode($response);

            return;
        }

        if (!$dfa) {
            $response = array(
                'status' => '0',
                'msg' => 'No location selected',
            );
            echo json_encode($response);

            return;
        }

        if (!in_array($element, array('GebDfaLfnr', 'AnlageLfnr', 'DokuTypNr'))) {
            $response = array(
                'status' => '0',
                'msg' => 'Invalid element given',
            );
            echo json_encode($response);

            return;
        }

        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select('MAX(' . $db->qn($element) . ')');
        $query->from('#__planarchiv_plan');
        $query->where('`didok_id` = ' . (int)$didok);
        $query->where('`dfa_id` = ' . (int)$dfa);
        $query->where('`state` != -2');

        if ($element == 'AnlageLfnr' || $element == 'DokuTypNr')
        {
            $query->where('`anlagetyp_id` = ' . (int)$anlage);
            $query->where('`GebDfaLfnr` = ' . $db->quote($dfaLfnr));
        }

        if ($element == 'DokuTypNr')
        {
            $query->where('`dokutyp_id` = ' . (int)$dokutyp);
            $query->where('`AnlageLfnr` = ' . $db->quote($anlageLfnr));
        }

        $db->setQuery($query);
        $id = $db->loadResult();
        $id++;
        $padLength = ($element == 'AnlageLfnr') ? 3 : 2;
        $id = str_pad($id, $padLength, 0, STR_PAD_LEFT);

        if ($id) {
            $response['id'] = $id;
            $response['status'] = 1;
        } else {
            $response = array(
                'status' => '0',
                'msg' => 'No value found',
            );
        }

        echo json_encode($response);

        return;
    }
}
