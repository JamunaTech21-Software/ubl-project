<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Garden_sales_report_export extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model('Garden_sales_export_modal');
    }

    // Method for exporting the report to Excel
    public function garden_sales_export() {
        // Get the query parameters from the URL
        $season = $this->input->get('season');
        $auction_center = $this->input->get('auction_center');
        $sale_from = $this->input->get('sale_from');
        $sale_to = $this->input->get('sale_to');
        $garden_id = $this->input->get('garden_id');

        // Fetch the data for the report from the model
        $warehouse_data = $this->Garden_sales_export_modal->get_warehouse_data([
            'season' => $season,
            'auction_center' => $auction_center,
            'sale_from' => $sale_from,
            'sale_to' => $sale_to,
            'garden_id' => $garden_id,
        ]);

        // Set the output filename and download the file
		$fileName = $season.' SALE '.$sale_from.' to '.$sale_to.'.xlsx';  
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Report Title and General Information
        $sheet->setCellValue('A1', 'Garden Sales Report');
        $sheet->mergeCells('A1:K1'); // Merge from A1 to K1 (11 columns)
        $sheet->setCellValue('A2', 'Season: ' . $season);
        $sheet->mergeCells('A2:C2'); // Merge from A2 to C2 (3 columns)
        $sheet->setCellValue('D2', 'Auction Center: ' . $auction_center); // Start from D2 to next column
        $sheet->mergeCells('D2:H2'); // Merge from D2 to H2 (5 columns)
        $sheet->setCellValue('I2', 'Sale Range: ' . $sale_from . ' - ' . $sale_to); // Start from I2
        $sheet->mergeCells('I2:K2'); // Merge from I2 to K2 (3 columns)

        // Set Column Headers for Data
        $sheet->setCellValue('A4', 'Sale No');
        $sheet->setCellValue('B4', 'Garden');
        $sheet->setCellValue('C4', 'INV');
        $sheet->setCellValue('D4', 'Grade');
        $sheet->setCellValue('E4', 'Price');
        $sheet->setCellValue('F4', 'Season');
        $sheet->setCellValue('G4', 'Broker');
        $sheet->setCellValue('H4', 'Lot');
        $sheet->setCellValue('I4', 'Pkgs');
        $sheet->setCellValue('J4', 'VAL');
        $sheet->setCellValue('K4', 'KGS');

        // Now loop through the data and fill the rows
        $rows = 5; // Start from row 5 to add data
        foreach ($warehouse_data as $warehouse) {
            $sheet->setCellValue('A' . $rows, $warehouse['sale_no']);
            $sheet->setCellValue('B' . $rows, $warehouse['garden_name']);
            $sheet->setCellValue('C' . $rows, $warehouse['w_inv_no']);
            $sheet->setCellValue('D' . $rows, $warehouse['grade']);
            $sheet->setCellValue('E' . $rows, $warehouse['sold_price']);
            $sheet->setCellValue('F' . $rows, $warehouse['old_season']);
            $sheet->setCellValue('G' . $rows, 'Unity Brokers Limited');
            $sheet->setCellValue('H' . $rows, $warehouse['lot_no']);
            $sheet->setCellValue('I' . $rows, $warehouse['chest_number']);
            $sheet->setCellValue('J' . $rows, $warehouse['valuation']);
            $sheet->setCellValue('K' . $rows, $warehouse['net_kgs']);
            $rows++;
        }

        // Set column auto width
        foreach (range('A', 'K') as $columnID) {
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