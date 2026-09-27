<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Valuation_export extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('Valuation_doModel');
    }
	public function createExcel() {
		// get html value
		$season = $this->input->get('season', TRUE);
		$auction_center = $this->input->get('auction_center', TRUE);
		$sale_no = $this->input->get('sale_no', TRUE);
		// excel arguments
		$fileName = $season.' VALUATION FOR SALE NO. '.$sale_no.'.xlsx';  
		$employeeData = $this->Valuation_doModel->employeeList($season,$auction_center,$sale_no);
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('B1', 'Unity Brokers LTD.');
		$sheet->setCellValue('B2', 'VALUATION FOR SALE NO: ' . $sale_no);
		$sheet->setCellValue('B3', 'SEASON:' . $season);
		$sheet->setCellValue('A5', 'LOT');
		$sheet->setCellValue('B5', 'VALUE');
		
		$rows = 6;
		foreach ($employeeData as $val){
			$sheet->setCellValue('A' . $rows, $val['lot_no']);
			$sheet->setCellValue('B' . $rows, number_format($val['valuation'],2,'.',''));
			$rows++;
		}
		
        $writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."/upload/".$fileName);              
    } 
}
?>