<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Price_realised_export_report extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model('price_realised_export_modal');
    }

    // Method for exporting the report to Excel
    public function price_realised_export() {
        // Get the query parameters from the URL
        $season = $this->input->get('season');
        $auction_center = $this->input->get('auction_center');
        $sale_from = $this->input->get('sale_from');
        $sale_to = $this->input->get('sale_to');
        $garden_id = $this->input->get('garden_id');

        // Fetch the data for the report from the model
        $price_realised_data = $this->price_realised_export_modal->get_price_realised_data([
            'season' => $season,
            'auction_center' => $auction_center,
            'sale_from' => $sale_from,
            'sale_to' => $sale_to,
            'garden_id' => $garden_id
        ]);

        // Get the garden name from the data (assuming only one garden name)
        $garden_name = isset($price_realised_data[0]['garden_name']) ? $price_realised_data[0]['garden_name'] : '';

        // Set the output filename and download the file
		$fileName = $garden_name.' Price Realised of Sale '.$sale_from.' to '.$sale_to.'.xlsx';  
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Report Title and General Information
        $sheet->setCellValue('A1', $garden_name);
        $sheet->mergeCells('A1:O1'); // Merge from A1 to O1 (15 columns)
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center text
        $sheet->setCellValue('A2', 'Sale Range: ' . $sale_from . ' - ' . $sale_to); // Start from B2
        $sheet->mergeCells('A2:O2'); // Merge from B2 to O2 (15 columns)
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center text

        // Set Column Headers for Data
        $sheet->setCellValue('A4', 'Sale No');
        $sheet->setCellValue('B4', 'Garden');
        $sheet->setCellValue('C4', 'Lot');
        $sheet->setCellValue('D4', 'INV');
        $sheet->setCellValue('E4', 'Grade');
        $sheet->setCellValue('F4', 'Pkgs');
        $sheet->setCellValue('G4', 'VAL');
        $sheet->setCellValue('H4', 'Leaf Rating');
        $sheet->setCellValue('I4', 'Liquor Rating');
        $sheet->setCellValue('J4', 'Price');
        $sheet->setCellValue('K4', 'KGS');
        $sheet->setCellValue('L4', 'Buyer-1');
        $sheet->setCellValue('M4', 'Buyer-2');
        $sheet->setCellValue('N4', 'Buyer-3');
        $sheet->setCellValue('O4', 'Type');

        // Initialize total variables
        $total_chest_number = 0;
        $total_net_kgs = 0;

        // Now loop through the data and fill the rows
        $rows = 5; // Start from row 5 to add data
        foreach ($price_realised_data as $warehouse) {
            $re_print = !empty($warehouse['re_print']) ? 'Re-Print' : 'Fresh';
        
            $sheet->setCellValue('A' . $rows, $warehouse['sale_no']);
            $sheet->setCellValue('B' . $rows, $warehouse['garden_name']);
            $sheet->setCellValue('C' . $rows, $warehouse['lot_no']);
            $sheet->setCellValue('D' . $rows, $warehouse['w_inv_no']);
            $sheet->setCellValue('E' . $rows, $warehouse['grade']);
            $sheet->setCellValue('F' . $rows, $warehouse['chest_number']);
            $sheet->setCellValue('G' . $rows, $warehouse['valuation']);
            $sheet->setCellValue('H' . $rows, $warehouse['appearance']);
            $sheet->setCellValue('I' . $rows, $warehouse['liquor']);
            $sheet->setCellValue('J' . $rows, $warehouse['sold_price']);
            $sheet->setCellValue('K' . $rows, $warehouse['net_kgs']);
            $sheet->setCellValue('L' . $rows, $warehouse['buyer1']);
            $sheet->setCellValue('M' . $rows, $warehouse['buyer2']);
            $sheet->setCellValue('N' . $rows, $warehouse['buyer3']);
            $sheet->setCellValue('O' . $rows, $re_print);

            // Accumulate totals
            $total_chest_number += $warehouse['chest_number'];
            $total_net_kgs += $warehouse['net_kgs'];

            $rows++;
        }        

        // Add the totals row
        $sheet->setCellValue('E' . $rows, 'Total:'); // Label for totals
        $sheet->setCellValue('F' . $rows, $total_chest_number); // Total for chest numbers
        $sheet->setCellValue('K' . $rows, $total_net_kgs); // Total for net kgs

        // Set column auto width
        foreach (range('A', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
        redirect(base_url()."/upload/".$fileName);
        exit;
    }
}
?>