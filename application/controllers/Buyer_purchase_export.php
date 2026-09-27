<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Buyer_purchase_export extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('Buyer_purchase_doModel');
    }
	public function createExcel() {
		// get html value
		$season = $this->input->get('season', TRUE);
		$auction_center = $this->input->get('auction_center', TRUE);
		$sale_from = $this->input->get('sale_from', TRUE);
		$sale_to = $this->input->get('sale_to', TRUE);
		// excel arguments
		$fileName = $season.' BUYER PURCHASE SALE '.$sale_from.' To '.$sale_to.'.xlsx';  
		$employeeData = $this->Buyer_purchase_doModel->employeeList($season,$auction_center,$sale_from,$sale_to);
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('B1', 'Unity Brokers LTD.');
		$sheet->setCellValue('B2', 'BUYER PURCHASE ANALYSIS');
		$sheet->setCellValue('A3', 'The Secretary');
		$sheet->setCellValue('A4', 'Bangladesh Tea Board');
		$sheet->setCellValue('A5', 'Nasirabad,Chittagong.');
		$sheet->setCellValue('A6', 'Dear Sir,');
		$sheet->setCellValue('A7', 'We give below the puchases made by the following buyers in our catalogue for sale No. ' . $sale_from . ' To ' . $sale_to . ' Season: ' . $season);
		$sheet->setCellValue('A8', 'Buyer Type');
		$sheet->setCellValue('B8', 'Buyer Name');
        $sheet->setCellValue('C8', 'Leaf Pkgs');
        $sheet->setCellValue('D8', 'Dust Pkgs');
        $sheet->setCellValue('E8', 'Leaf Kgs');
		$sheet->setCellValue('F8', 'Dust Kgs');
        $sheet->setCellValue('G8', 'Total Pkgs');       
        $sheet->setCellValue('H8', 'Total Kgs');       
        $sheet->setCellValue('I8', 'Total Amount');
        $rows = 9;
        foreach ($employeeData as $val){
            $sheet->setCellValue('A' . $rows, $val['buyer_type']);
            $sheet->setCellValue('B' . $rows, $val['buyer_name']);
            $sheet->setCellValue('C' . $rows, $val['leaf_pkgs']);
            $sheet->setCellValue('D' . $rows, $val['dust_pkgs']);
            $sheet->setCellValue('E' . $rows, $val['leaf_kgs']);
			$sheet->setCellValue('F' . $rows, $val['dust_kgs']);
            $sheet->setCellValue('G' . $rows, $val['buyer_bag']);
            $sheet->setCellValue('H' . $rows, $val['buyer_kgs']);
            $sheet->setCellValue('I' . $rows, $val['buyer_amount']);
            $rows++;
        }
        $writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."/upload/".$fileName);              
    } 
}
?>