<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Wh_wise_delivery_order extends CI_Controller {
	public function __construct() {
        parent::__construct();
		$this->load->model('Wh_wise_doModel');
    }
	public function createExcel() {
		// get html value
		$season = $this->input->get('season', TRUE);
		$auction_center = $this->input->get('auction_center', TRUE);
		$sale_from = $this->input->get('sale_from', TRUE);
		$sale_to = $this->input->get('sale_to', TRUE);
		$w_house_id = $this->input->get('w_house_id', TRUE);
		// excel arguments
		$employeeData = $this->Wh_wise_doModel->employeeList($season,$auction_center,$sale_from,$sale_to,$w_house_id);

        // Get the warehouse short key from the data (assuming only one warehouse short key)
        $warehouse_name = isset($employeeData[0]['short_key_word']) ? $employeeData[0]['short_key_word'] : '';
		$fileName = 'UBL Sale-'.$sale_from.' '.$warehouse_name.' '.$season.'.xlsx';  

		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('B1', 'Unity Brokers LTD.');
		$sheet->setCellValue('B2', 'Warehouse Wise Delivery Order');
		$sheet->setCellValue('A3', 'Buyer Name');
        $sheet->setCellValue('B3', 'Warehouse Name');
        $sheet->setCellValue('C3', 'Lot No');
        $sheet->setCellValue('D3', 'Garden Name');
		$sheet->setCellValue('E3', 'Invoice');
        $sheet->setCellValue('F3', 'Grade');       
        $sheet->setCellValue('G3', 'Pkgs');    
        $sheet->setCellValue('H3', 'Net Kgs');       
        $sheet->setCellValue('I3', 'Sample');       
        $sheet->setCellValue('J3', 'Total Kgs'); 
        $sheet->setCellValue('K3', 'Rate Per Kg');       
        $sheet->setCellValue('L3', 'Amount Taka');
        $sheet->setCellValue('M3', 'Vat @ 15% of Taka');

        // Initialize total variables
        $total_chest_number = 0;
        $total_net_kgs = 0;
        $total_buyer_amount = 0;
        $total_vat_amount = 0;

        $rows = 4;
        foreach ($employeeData as $val){
            $sheet->setCellValue('A' . $rows, $val['buyer_name']);
            $sheet->setCellValue('B' . $rows, $val['warehouse_name']);
            $sheet->setCellValue('C' . $rows, $val['lot_no']);
            $sheet->setCellValue('D' . $rows, $val['garden_name']);
			$sheet->setCellValue('E' . $rows, $val['w_inv_no']);
            $sheet->setCellValue('F' . $rows, $val['grade_name']);
            $sheet->setCellValue('G' . $rows, $val['buyer_bag']); // Total
            $sheet->setCellValue('H' . $rows, $val['net_wt_per_bag']);
            $sheet->setCellValue('I' . $rows, $val['sample']);
            $sheet->setCellValue('J' . $rows, $val['buyer_kgs']); // Total     
            $sheet->setCellValue('K' . $rows, $val['sold_price']);
            $sheet->setCellValue('L' . $rows, $val['buyer_amount']);  // Total   
            $sheet->setCellValue('M' . $rows, $val['vat_tk']);  // Total   

            // Accumulate totals
            $total_chest_number += $val['buyer_bag'];
            $total_net_kgs += $val['buyer_kgs'];
            $total_buyer_amount += $val['buyer_amount'];
            $total_vat_amount += $val['vat_tk'];

            $rows++;
        } 

        // Add the totals row
        $sheet->setCellValue('F' . $rows, 'Total:'); // Label for totals
        $sheet->setCellValue('G' . $rows, $total_chest_number); // Total for chest numbers
        $sheet->setCellValue('J' . $rows, $total_net_kgs); // Total for net kgs
        $sheet->setCellValue('L' . $rows, $total_net_kgs); // Total for buyer amount
        $sheet->setCellValue('M' . $rows, $total_net_kgs); // Total for vat tk

        // Set column auto width
        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."/upload/".$fileName);              
    } 
}
?>