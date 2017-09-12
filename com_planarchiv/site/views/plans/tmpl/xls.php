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
$sheet->getStyle('A1:H1')->getFont()->setBold(true);
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->setAutoFilter('A1:F1');

// Adding Header
$sheet->setCellValue('A1', JText::_('JGLOBAL_TITLE'));
$sheet->setCellValue('B1', JText::_('COM_PLANARCHIV_ANLAGETYP_LABEL'));
$sheet->setCellValue('C1', JText::_('COM_PLANARCHIV_ANLAGETYP_CODE_LABEL'));
$sheet->setCellValue('D1', JText::_('COM_PLANARCHIV_ORT_LABEL'));
$sheet->setCellValue('E1', JText::_('COM_PLANARCHIV_BEMERKUNG_LABEL'));
$sheet->setCellValue('F1', JText::_('COM_PLANARCHIV_FIELD_ERSTELLDATUM_LABEL'));

$i = 2;
foreach ($this->items as $item)
{
	$sheet->setCellValue('A' . $i, $item->title);
	$sheet->setCellValue('B' . $i, $item->anlagetyp_title);
	$sheet->setCellValue('C' . $i, $item->anlagetyp_code);
	$sheet->setCellValue('D' . $i, $item->didok_title);
	$sheet->setCellValue('E' . $i, $item->Bemerkung);
	$sheet->setCellValue('F' . $i, $item->ErstellDatum);
	$sheet->getStyle('F' . $i)->getNumberFormat()->setFormatCode('dd.mm.yyyy');
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
