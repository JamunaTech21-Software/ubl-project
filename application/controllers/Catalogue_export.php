<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Catalogue_export extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('Catalogue_Model');
    }
	public function createExcel() {
		// get html value
		$season = $this->input->get('season', TRUE);
		$auction_center = $this->input->get('auction_center', TRUE);
		$sale_no = $this->input->get('sale_no', TRUE);
		// excel arguments
		$fileName = $season.' CATALOGUE - SALE NO '.$sale_no.'.xlsx';  
		$employeeData = $this->Catalogue_Model->employeeList($season,$auction_center,$sale_no);
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('D1', 'Unity Brokers LTD.');
		$sheet->setCellValue('D2', 'Progressive Tower (5th Floor), 1837 SK Mujib Road, Agrabad, Chattogram - 4100.');
		$sheet->setCellValue('D3', 'CATALOGUE');
		$sheet->setCellValue('D4', 'SEASON: '.$season);
		$sheet->setCellValue('A5', 'Sale No');
        $sheet->setCellValue('B5', 'Garden Name');
        $sheet->setCellValue('C5', 'Warehouse Name');
        $sheet->setCellValue('D5', 'Lot No');
		$sheet->setCellValue('E5', '');
		$sheet->setCellValue('F5', 'Value');
		$sheet->setCellValue('G5', 'Invoice');
        $sheet->setCellValue('H5', 'Grade');       
        $sheet->setCellValue('I5', 'Gross WT/Bag');       
        $sheet->setCellValue('J5', 'Net WT/Bag');       
        $sheet->setCellValue('K5', 'Net Kgs');       
        $sheet->setCellValue('L5', 'Chest');    
        $rows = 6;
        foreach ($employeeData as $val){
            $sheet->setCellValue('A' . $rows, $val['sale_no']);
            $sheet->setCellValue('B' . $rows, $val['garden_name']);
            $sheet->setCellValue('C' . $rows, $val['warehouse_name']);
            $sheet->setCellValue('D' . $rows, $val['lot_no']);
            $sheet->setCellValue('E' . $rows, '');
			$sheet->setCellValue('F' . $rows, $val['valuation']);
			$sheet->setCellValue('G' . $rows, preg_replace('/[^0-9]/', '', $val['w_inv_no']));
            $sheet->setCellValue('H' . $rows, $val['grade_name']);
            $sheet->setCellValue('I' . $rows, $val['gross_wt_per_bag']);
            $sheet->setCellValue('J' . $rows, $val['net_wt_per_bag']);
            $sheet->setCellValue('K' . $rows, $val['net_kgs']);
            $sheet->setCellValue('L' . $rows, $val['chest_number']);
            $rows++;
        } 
        $writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."/upload/".$fileName);              
    } 
}
?>