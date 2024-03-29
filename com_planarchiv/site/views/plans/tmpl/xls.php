<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

Cell::setValueBinder(new AdvancedValueBinder());

$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
$spreadsheet->getDefaultStyle()->getFont()->setSize(10);

$sheet = $spreadsheet->getActiveSheet();

// Format Cells
$sheet->getStyle('A1:AB1')->getFont()->setBold(true);
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
$sheet->getColumnDimension('Q')->setAutoSize(true);
$sheet->getColumnDimension('S')->setAutoSize(true);
$sheet->getColumnDimension('T')->setAutoSize(true);
$sheet->getColumnDimension('U')->setAutoSize(true);
$sheet->getColumnDimension('V')->setAutoSize(true);
$sheet->getColumnDimension('W')->setAutoSize(true);
$sheet->getColumnDimension('X')->setAutoSize(true);
$sheet->getColumnDimension('Y')->setAutoSize(true);
$sheet->getColumnDimension('Z')->setAutoSize(true);
$sheet->getColumnDimension('AA')->setAutoSize(true);
$sheet->getColumnDimension('AB')->setAutoSize(true);
$sheet->setAutoFilter('A1:AB1');

// Adding Header
$sheet->setCellValue('A1', Text::_('JGLOBAL_TITLE'));
$sheet->setCellValue('B1', Text::_('COM_PLANARCHIV_ANLAGETYP_LABEL'));
$sheet->setCellValue('C1', Text::_('COM_PLANARCHIV_CODE'));
$sheet->setCellValue('D1', Text::_('COM_PLANARCHIV_FIELD_LFNR'));
$sheet->setCellValue('E1', Text::_('COM_PLANARCHIV_ORT_LABEL'));
$sheet->setCellValue('F1', Text::_('COM_PLANARCHIV_DIDOK_LABEL'));
$sheet->setCellValue('G1', Text::_('COM_PLANARCHIV_BEMERKUNG_LABEL'));
$sheet->setCellValue('H1', Text::_('COM_PLANARCHIV_FIELD_ERSTELLDATUM_LABEL'));
$sheet->setCellValue('I1', Text::_('COM_PLANARCHIV_FIELD_AENDERUNGSDATUM_LABEL'));
$sheet->setCellValue('J1', Text::_('COM_PLANARCHIV_STRECKE_LABEL'));
$sheet->setCellValue('K1', Text::_('COM_PLANARCHIV_KM_LABEL'));
$sheet->setCellValue('L1', Text::_('COM_PLANARCHIV_DIRECTION_LABEL'));
$sheet->setCellValue('M1', Text::_('COM_PLANARCHIV_MANGELLISTE_LABEL'));
$sheet->setCellValue('N1', Text::_('COM_PLANARCHIV_ORIGINAL_LABEL'));
$sheet->setCellValue('O1', Text::_('COM_PLANARCHIV_FILES_LABEL'));
$sheet->setCellValue('P1', Text::_('COM_PLANARCHIV_FIELD_PLANERSTELLER_LABEL'));
$sheet->setCellValue('Q1', Text::_('COM_PLANARCHIV_CAD_LABEL'));
$sheet->setCellValue('R1', Text::_('COM_PLANARCHIV_STOCKWERK_LABEL'));
$sheet->setCellValue('S1', Text::_('COM_PLANARCHIV_DFA_LABEL'));
$sheet->setCellValue('T1', Text::_('COM_PLANARCHIV_CODE'));
$sheet->setCellValue('U1', Text::_('COM_PLANARCHIV_FIELD_LFNR'));
$sheet->setCellValue('V1', Text::_('COM_PLANARCHIV_DOKUTYP_LABEL'));
$sheet->setCellValue('W1', Text::_('COM_PLANARCHIV_CODE'));
$sheet->setCellValue('X1', Text::_('COM_PLANARCHIV_FIELD_LFNR'));
$sheet->setCellValue('Y1', Text::_('COM_PLANARCHIV_ALIGNMENT_LABEL'));
$sheet->setCellValue('Z1', Text::_('COM_PLANARCHIV_SIZE_LABEL'));
$sheet->setCellValue('AA1', Text::_('COM_PLANARCHIV_FIELD_ZUR_ZEIT_BEI_LABEL'));
$sheet->setCellValue('AB1', Text::_('COM_PLANARCHIV_FIELD_ZUR_ZEIT_BEI_SEIT_LABEL'));

$i = 2;
foreach ($this->items as $item)
{
	$sheet->setCellValue('A' . $i, $item->title);
	$sheet->setCellValue('B' . $i, $item->anlagetyp_title);
	$sheet->setCellValue('C' . $i, $item->anlagetyp_code);
	$sheet->setCellValue('D' . $i, $item->AnlageLfnr);
	$sheet->setCellValue('E' . $i, $item->didok_title);
	$sheet->setCellValue('F' . $i, $item->didok);
	$sheet->setCellValue('G' . $i, $item->Bemerkung);
	$sheet->setCellValue('H' . $i, ($item->ErstellDatum !== '0000-00-00 00:00:00') ? PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(HTMLHelper::_('date', $item->ErstellDatum, 'Y-m-d')) : '');
	$sheet->setCellValue('I' . $i, ($item->AenderungsDatum !== '0000-00-00 00:00:00') ? PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(HTMLHelper::_('date', $item->AenderungsDatum, 'Y-m-d')) : '');
	$sheet->setCellValue('J' . $i, $item->Strecke);
	$sheet->setCellValue('K' . $i, $item->km);
	$sheet->setCellValue('L' . $i, $item->richtung_title);
	$sheet->setCellValue('M' . $i, $item->Maengelliste);
	$sheet->setCellValue('N' . $i, $item->original);
	$sheet->setCellValue('O' . $i, $item->files);
	$sheet->setCellValue('P' . $i, $item->ersteller_name);
	$sheet->setCellValue('Q' . $i, $item->CAD_Auftrag);
	$sheet->setCellValue('R' . $i, $item->stockwerk_title);
	$sheet->setCellValue('S' . $i, $item->dfa_title);
	$sheet->setCellValue('T' . $i, $item->dfa_code);
	$sheet->setCellValue('U' . $i, $item->GebDfaLfnr);
	$sheet->setCellValue('V' . $i, $item->dokutyp_title);
	$sheet->setCellValue('W' . $i, $item->dokutyp_code);
	$sheet->setCellValue('X' . $i, $item->DokuTypNr);
	$sheet->setCellValue('Y' . $i, Text::_('COM_PLANARCHIV_ALIGNMENT_' . $item->alignment . '_LABEL'));
	$sheet->setCellValue('Z' . $i, $item->size);
	$sheet->setCellValue('AA' . $i, $item->zurzeitbei_name);
	$sheet->setCellValue('AB' . $i, ($item->zurzeitbei_date !== '0000-00-00 00:00:00') ? PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(HTMLHelper::_('date', $item->zurzeitbei_date, 'Y-m-d')) : '');

	$sheet->getStyle('H' . $i)->getNumberFormat()->setFormatCode('dd.mm.yyyy');
	$sheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode('dd.mm.yyyy');
	$sheet->getStyle('AB' . $i)->getNumberFormat()->setFormatCode('dd.mm.yyyy');
	$i++;
}

// generate File
http_response_code(200);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . Text::_('COM_PLANARCHIV_EXPORT_FILENAME_PLANS') . '.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
