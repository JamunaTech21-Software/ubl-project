<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pre_sale_advice_report_export extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model('Pre_sale_advice_report_export_modal');
    }

    // Method for exporting the report to Excel
    public function pre_sale_advice_export() {
        // Get the query parameters from the URL
        $season = $this->input->get('season');
        $auction_center = $this->input->get('auction_center');
        $sale_from = $this->input->get('sale_from');
        $garden_id = $this->input->get('garden_id');

        // Fetch the data for the report from the model
        $price_realised_data = $this->Pre_sale_advice_report_export_modal->get_pre_sale_advice_data([
            'season' => $season,
            'auction_center' => $auction_center,
            'sale_from' => $sale_from,
            'garden_id' => $garden_id
        ]);

        // Get the garden name from the data (assuming only one garden name)
        $garden_name = isset($price_realised_data[0]['garden_name']) ? $price_realised_data[0]['garden_name'] : '';

        // Set the output filename and download the file
        $fileName = $garden_name . ' Lost wise value & sale for ' . $sale_from . '.xlsx';  

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Initialize total variables for "new" and "re-print" sections
        $net_new_sold_bag = $net_new_sold_kgs = $net_new_sold_amount = 0;
        $net_new_off_bag = $net_new_off_kgs = $new_unsold_bag = $new_unsold_kgs = 0;
        $net_rp_sold_bag = $net_rp_sold_kgs = $net_rp_sold_amount = 0;
        $net_rp_off_bag = $net_rp_off_kgs = $rp_unsold_bag = $rp_unsold_kgs = 0;
        $net_sold_kgs = $net_sold_amount = $net_unsold_kgs = $net_offering_kgs = 0;

        // Set Report Title and General Information
        $sheet->setCellValue('A1', $garden_name);
        $sheet->mergeCells('A1:K1'); // Merge from A1 to K1 (11 columns)
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center text
        $sheet->setCellValue('A2', 'Sale : ' . $sale_from ); // Start from A2
        $sheet->mergeCells('A2:K2'); // Merge from A2 to K2 (11 columns)
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Center text

        // Set Column Headers for Data
        $sheet->setCellValue('A4', 'LOT');
        $sheet->setCellValue('B4', 'INV');
        $sheet->setCellValue('C4', 'GRADE');
        $sheet->setCellValue('D4', 'RE-PRINT');
        $sheet->setCellValue('E4', 'OFFERING KGS');
        $sheet->setCellValue('F4', 'OFFERING PKGS');
        $sheet->setCellValue('G4', 'OFFERING VALUE');
        $sheet->setCellValue('H4', 'SOLD KGS');
        $sheet->setCellValue('I4', 'SOLD PKGS');
        $sheet->setCellValue('J4', 'SOLD VALUE');
        $sheet->setCellValue('K4', 'TOTAL AMOUNT');

        // Initialize row count for filling data
        $rows = 5; // Start from row 5 to add data
        foreach ($price_realised_data as $val) {
            $sheet->setCellValue('A' . $rows, $val['lot_no']);
            $sheet->setCellValue('B' . $rows, $val['w_inv_no']);
            $sheet->setCellValue('C' . $rows, $val['grade_name']);
            $sheet->setCellValue('D' . $rows, $val['re_print']);
            $sheet->setCellValue('E' . $rows, $val['offering_kgs']);
            $sheet->setCellValue('F' . $rows, $val['offering_bag']);
            $sheet->setCellValue('G' . $rows, $val['valuation']);
            $sheet->setCellValue('H' . $rows, $val['sold_kgs']);
            $sheet->setCellValue('I' . $rows, $val['sold_bag']);
            $sheet->setCellValue('J' . $rows, $val['sold_price']);
            $sheet->setCellValue('K' . $rows, $val['sold_amount']);

            // reprint and new bags calculation
            $new_off_bag = (empty($val['re_print'])) ? $val['offering_bag'] : 0;
            $rp_off_bag = (!empty($val['re_print'])) ? $val['offering_bag'] : 0;
            
            $new_off_kgs = (empty($val['re_print'])) ? $val['offering_kgs'] : 0;
            $rp_off_kgs = (!empty($val['re_print'])) ? $val['offering_kgs'] : 0;
            
            $new_sold_bag = (empty($val['re_print'])) ? $val['sold_bag'] : 0;
            $rp_sold_bag = (!empty($val['re_print'])) ? $val['sold_bag'] : 0;
            
            $new_sold_kgs = (empty($val['re_print'])) ? $val['sold_kgs'] : 0;
            $rp_sold_kgs = (!empty($val['re_print'])) ? $val['sold_kgs'] : 0;
            
            $new_sold_amount = (empty($val['re_print'])) ? $val['sold_amount'] : 0;
            $rp_sold_amount = (!empty($val['re_print'])) ? $val['sold_amount'] : 0;

            // Add to new and re-print totals
            $net_new_sold_bag += $new_sold_bag;
            $net_new_sold_kgs += $new_sold_kgs;
            $net_new_sold_amount += $new_sold_amount;
            $net_new_off_bag += $new_off_bag;
            $net_new_off_kgs += $new_off_kgs;
            $new_unsold_bag += ($new_off_bag - $new_sold_bag);
            $new_unsold_kgs += ($new_off_kgs - $new_sold_kgs);

            $net_rp_sold_bag += $rp_sold_bag;
            $net_rp_sold_kgs += $rp_sold_kgs;
            $net_rp_sold_amount += $rp_sold_amount;
            $net_rp_off_bag += $rp_off_bag;
            $net_rp_off_kgs += $rp_off_kgs;
            $rp_unsold_bag += ($rp_off_bag - $rp_sold_bag);
            $rp_unsold_kgs += ($rp_off_kgs - $rp_sold_kgs);

            // Add to total sold and offering values
            $net_sold_kgs += $val['sold_kgs'];
            $net_sold_amount += $val['sold_amount'];
            $net_unsold_kgs += ($val['offering_kgs'] - $val['sold_kgs']);
            $net_offering_kgs += $val['offering_kgs'];

            $rows++; // Move to next row
        }

        // Add the totals row
        $sheet->setCellValue('D' . $rows, 'Total:'); 
        $sheet->setCellValue('E' . $rows, $net_offering_kgs); 
        $sheet->setCellValue('F' . $rows, $net_new_off_bag); 
        $sheet->setCellValue('H' . $rows, $net_sold_kgs); 
        $sheet->setCellValue('I' . $rows, $net_sold_bag); 
        $sheet->setCellValue('J' . $rows, ($net_sold_amount > 0 && $net_sold_kgs > 0) ? number_format($net_sold_amount / $net_sold_kgs, 2) : '0'); 
        $sheet->setCellValue('K' . $rows, $net_sold_amount); 

        // Set column auto width
        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Start on the desired row
        $rows += 2; // Leave some space

        // Header Row
        $sheet->setCellValue('E' . $rows, 'NEW');
        $sheet->setCellValue('F' . $rows, 'RE-PRINT');
        $sheet->setCellValue('G' . $rows, 'TOTAL');
        $sheet->getStyle('E' . $rows . ':G' . $rows)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $rows++;

        // NEW Data
        $newData = "Sold Pkgs : $net_new_sold_bag\n"
            . "Unsold Pkgs : $new_unsold_bag\n"
            . "Total Pkgs : $net_new_off_bag\n"
            . "Sold % : " . (($net_new_sold_kgs > 0 && $net_new_off_kgs > 0) ? round(($net_new_sold_kgs / $net_new_off_kgs) * 100) : '0') . "\n"
            . "Unsold % : " . (($new_unsold_kgs > 0 && $net_new_off_kgs > 0) ? round(($new_unsold_kgs / $net_new_off_kgs) * 100) : '0') . "\n"
            . "Avg. Tk : " . (($net_new_sold_amount > 0 && $net_new_sold_kgs > 0) ? number_format($net_new_sold_amount / $net_new_sold_kgs, 2) : '0');

        // RE-PRINT Data
        $reprintData = "Sold Pkgs : $net_rp_sold_bag\n"
            . "Unsold Pkgs : $rp_unsold_bag\n"
            . "Total Pkgs : $net_rp_off_bag\n"
            . "Sold % : " . (($net_rp_sold_kgs > 0 && $net_rp_off_kgs > 0) ? round(($net_rp_sold_kgs / $net_rp_off_kgs) * 100) : '0') . "\n"
            . "Unsold % : " . (($rp_unsold_kgs > 0 && $net_rp_off_kgs > 0) ? round(($rp_unsold_kgs / $net_rp_off_kgs) * 100) : '0') . "\n"
            . "Avg. Tk : " . (($net_rp_sold_amount > 0 && $net_rp_sold_kgs > 0) ? number_format($net_rp_sold_amount / $net_rp_sold_kgs, 2) : '0');

        // TOTAL Data
        $totalData = "Sold kgs : $net_sold_kgs\n"
            . "Unsold kgs : $net_unsold_kgs\n"
            . "Total kgs : $net_offering_kgs\n"
            . "Sold Avg. Tk : " . (($net_sold_amount > 0 && $net_sold_kgs > 0) ? number_format($net_sold_amount / $net_sold_kgs, 2) : '0');

        // Write Data into the row
        $sheet->setCellValue('E' . $rows, $newData);
        $sheet->setCellValue('F' . $rows, $reprintData);
        $sheet->setCellValue('G' . $rows, $totalData);

        // Enable text wrapping for all cells
        $sheet->getStyle('E' . $rows . ':G' . $rows)->getAlignment()->setWrapText(true);

        // Save and download the file
        $writer = new Xlsx($spreadsheet);
        $writer->save("upload/" . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        redirect(base_url() . "/upload/" . $fileName);
        exit;
    }
}
?>
