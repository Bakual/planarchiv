<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

// loading composer autoloader
require_once JPATH_COMPONENT_ADMINISTRATOR . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
\PhpOffice\PhpSpreadsheet\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

$sheet = $spreadsheet->getActiveSheet();

// Format Cells
$sheet->getStyle('A1:P1')->getFont()->setBold(true);
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true);
$sheet->getColumnDimension('J')->setAutoSize(true);
$sheet->getColumnDimension('K')->setAutoSize(true);
$sheet->getColumnDimension('L')->setAutoSize(true);
$sheet->getColumnDimension('N')->setAutoSize(true);
$sheet->getColumnDimension('O')->setAutoSize(true);
$sheet->getColumnDimension('P')->setAutoSize(true);
$sheet->setAutoFilter('A1:P1');

// Adding Header
$sheet->setCellValue('A1', JText::_('JGLOBAL_TITLE'));
$sheet->setCellValue('B1', JText::_('COM_PLANARCHIV_ANLAGETYP_LABEL'));
$sheet->setCellValue('C1', JText::_('COM_PLANARCHIV_ANLAGETYP_CODE_LABEL'));
$sheet->setCellValue('D1', JText::_('COM_PLANARCHIV_ORT_LABEL'));
$sheet->setCellValue('E1', JText::_('COM_PLANARCHIV_DIDOK_LABEL'));
$sheet->setCellValue('F1', JText::_('COM_PLANARCHIV_BEMERKUNG_LABEL'));
$sheet->setCellValue('G1', JText::_('COM_PLANARCHIV_FIELD_ERSTELLDATUM_LABEL'));
$sheet->setCellValue('H1', JText::_('COM_PLANARCHIV_FIELD_AENDERUNGSDATUM_LABEL'));
$sheet->setCellValue('I1', JText::_('COM_PLANARCHIV_STRECKE_LABEL'));
$sheet->setCellValue('J1', JText::_('COM_PLANARCHIV_KM_LABEL'));
$sheet->setCellValue('K1', JText::_('COM_PLANARCHIV_MANGELLISTE_LABEL'));
$sheet->setCellValue('L1', JText::_('COM_PLANARCHIV_ORIGINAL_LABEL'));
$sheet->setCellValue('M1', JText::_('COM_PLANARCHIV_FILES_LABEL'));
$sheet->setCellValue('N1', JText::_('COM_PLANARCHIV_FIELD_PLANERSTELLER_LABEL'));
$sheet->setCellValue('O1', JText::_('COM_PLANARCHIV_CAD_LABEL'));
$sheet->setCellValue('P1', JText::_('COM_PLANARCHIV_STOCKWERK_LABEL'));

$i = 2;
foreach ($this->items as $item)
{
	$sheet->setCellValue('A' . $i, $item->title);
	$sheet->setCellValue('B' . $i, $item->anlagetyp_title);
	$sheet->setCellValue('C' . $i, $item->anlagetyp_code);
	$sheet->setCellValue('D' . $i, $item->didok_title);
	$sheet->setCellValue('E' . $i, $item->didok);
	$sheet->setCellValue('F' . $i, $item->Bemerkung);
	$sheet->setCellValue('G' . $i, $item->ErstellDatum);
	$sheet->setCellValue('H' . $i, $item->AenderungsDatum);
	$sheet->setCellValue('I' . $i, $item->Strecke);
	$sheet->setCellValue('J' . $i, $item->km);
	$sheet->setCellValue('K' . $i, $item->Maengelliste);
	$sheet->setCellValue('L' . $i, $item->original);
	$sheet->setCellValue('M' . $i, $item->files);
	$sheet->setCellValue('N' . $i, $item->ersteller_name);
	$sheet->setCellValue('O' . $i, $item->CAD_Auftrag);
	$sheet->setCellValue('P' . $i, $item->stockwerk_title);

	$sheet->getStyle('G' . $i)->getNumberFormat()->setFormatCode('dd.mm.yyyy');
	$sheet->getStyle('H' . $i)->getNumberFormat()->setFormatCode('dd.mm.yyyy');
	$i++;
}

// generate File
header("200 HTTP/1.0 OK");
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . JText::_('COM_PLANARCHIV_EXPORT_FILENAME_PLANS') . '.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
