<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Developer_export extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('Developer_Model');
    }
	
	public function createExcel() {
		// get html value
		$season = $this->input->get('season', TRUE);
		$auction_center = $this->input->get('auction_center', TRUE);
		$sale_no = $this->input->get('sale_no', TRUE);
		
		// excel arguments
		$fileName = 'Developer_Sheet_'.$season.'_'.$sale_no.'.xlsx';  
		$employeeData = $this->Developer_Model->getDeveloperSheetData($season,$auction_center,$sale_no);
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		
		// Set headers for the required columns
		$sheet->setCellValue('A1', 'season');
		$sheet->setCellValue('B1', 'auction_center');
		$sheet->setCellValue('C1', 'sale_no');
		$sheet->setCellValue('D1', 'lot_no');
		$sheet->setCellValue('E1', 'id');
		
		$rows = 2;
		foreach ($employeeData as $val){
			$sheet->setCellValue('A' . $rows, $val['season']);
			$sheet->setCellValue('B' . $rows, $val['auction_center']);
			$sheet->setCellValue('C' . $rows, $val['sale_no']);
			$sheet->setCellValue('D' . $rows, $val['lot_no']);
			$sheet->setCellValue('E' . $rows, $val['id']);
			$rows++;
		} 
		
		// Auto-size columns
		foreach(range('A','E') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}
		
		$writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		redirect(base_url()."/upload/".$fileName);              
    } 
}
?>