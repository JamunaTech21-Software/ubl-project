<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Price_realised_export_all_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('price_realised_export_all_modal');
    }

    public function price_realised_export_all() {
        $season = $this->input->get('season');
        $auction_center = $this->input->get('auction_center');
        $sale_no = $this->input->get('sale_no');
        $company_id = $this->input->get('company_id');

        $price_realised_data = $this->price_realised_export_all_modal->get_price_realised_data([
            'season' => $season,
            'auction_center' => $auction_center,
            'sale_no' => $sale_no,
            'company_id' => $company_id
        ]);

        $fileName = 'Unity Brokers Limited Price Realised of Sale ' . $sale_no . '.xlsx';  

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // First row - Company name
        $sheet->setCellValue('A1', 'Unity Brokers Limited');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Second row - Sale information
        $sheet->setCellValue('A2', 'Price Realised of Sale: ' . $sale_no);
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Column headers (keeping original columns)
        $sheet->setCellValue('A4', 'Sale No');
        $sheet->setCellValue('B4', 'Garden');
        $sheet->setCellValue('C4', 'Lot');
        $sheet->setCellValue('D4', 'INV');
        $sheet->setCellValue('E4', 'Grade');
        $sheet->setCellValue('F4', 'Pkgs');
        $sheet->setCellValue('G4', 'VAL');
        $sheet->setCellValue('H4', 'Price');
        $sheet->setCellValue('I4', 'KGS');
        $sheet->setCellValue('J4', 'Buyer-1');
        $sheet->setCellValue('K4', 'Buyer-2');
        $sheet->setCellValue('L4', 'Buyer-3');
        $sheet->setCellValue('M4', 'Type');

        $total_chest_number = 0;
        $total_net_kgs = 0;
        $rows = 5;

        foreach ($price_realised_data as $warehouse) {
            $re_print = !empty($warehouse['re_print']) ? 'Re-Print' : 'Fresh';
        
            $sheet->setCellValue('A' . $rows, $warehouse['sale_no']);
            $sheet->setCellValue('B' . $rows, $warehouse['garden_name']);
            $sheet->setCellValue('C' . $rows, $warehouse['lot_no']);
            $sheet->setCellValue('D' . $rows, $warehouse['w_inv_no']);
            $sheet->setCellValue('E' . $rows, $warehouse['grade']);
            $sheet->setCellValue('F' . $rows, $warehouse['chest_number']);
            $sheet->setCellValue('G' . $rows, $warehouse['valuation']);
            // Handle price (show UNSOLD if applicable)
            $sheet->setCellValue('H' . $rows, ($warehouse['sold_price'] === 'UNSOLD' || $warehouse['sold_price'] == 0) ? 'UNSOLD' : $warehouse['sold_price']);
            $sheet->setCellValue('I' . $rows, $warehouse['net_kgs']);
            $sheet->setCellValue('J' . $rows, $warehouse['buyer1']);
            $sheet->setCellValue('K' . $rows, $warehouse['buyer2']);
            $sheet->setCellValue('L' . $rows, $warehouse['buyer3']);
            $sheet->setCellValue('M' . $rows, $re_print);

            $total_chest_number += $warehouse['chest_number'];
            $total_net_kgs += $warehouse['net_kgs'];
            $rows++;
        }        

        // Totals row (keeping original format)
        $sheet->setCellValue('E' . $rows, 'Total:');
        $sheet->setCellValue('F' . $rows, $total_chest_number);
        $sheet->setCellValue('I' . $rows, $total_net_kgs);

        // Auto-size columns (A-M as original)
        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save("upload/".$fileName);
        header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."/upload/".$fileName);
        exit;
    }
}