<?php
// get html value
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
$buyer_category = (!empty($this->input->get('buyer_category', TRUE))) ? $this->input->get('buyer_category', TRUE) : '';
?>

<div id="printableArea" class="container">
<style>
body{
	font-family:verdana !important;
	background-color: white !important;
	margin-top:10px;
}
.table{
	font-family:verdana;
	width: 100% !important;
}
#table{
	border-color:black;
}

#table tbody tr.highlighted td { background-color: cyan; } 

@media print{    
	thead { display: table-header-group; }
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	/* DATA GRID ALIGN */
	.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
		float: left;
	}
	.col-sm-12 { width: 100%; }
	.col-sm-11 { width: 91.66666667%; }
	.col-sm-10 { width: 83.33333333%; }
	.col-sm-9 { width: 75%; }
	.col-sm-8 { width: 66.66666667%; }
	.col-sm-7 { width: 58.33333333%; }
	.col-sm-6 { width: 50%; }
	.col-sm-5 { width: 41.66666667%; }
	.col-sm-4 { width: 33.33333333%; }
	.col-sm-3 { width: 25%; }
	.col-sm-2 { width: 22%; }
	.col-sm-1 { width: 8.33333333%; }
}
</style>
<?php if($report_name=='Register'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:12px !important;
	padding:6px;
}
#table tr th{
	border-color:black;
	font-size:13px !important;
	padding:3px;
}

@page {
  size: legal landscape;
  margin-left: 70px;
  margin-right: 250px;
}

</style>
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>
<?php
// page row count
$row_limit_pp = 22;

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,

	@buyer_amount:=(
		IFNULL(sale_entry.buyer1_amount,0)
	) AS buyer_amount,

	@complete_amount:=(
		IFNULL(sale_entry.buyer1_complete_amount,0)
	) AS complete_amount,

	@brokerage_tk:=(
		(IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100
	) AS brokerage_tk,

	@vat_tk:=(
		(IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100
	) AS vat_tk,

	@ait_tk:=(
		(IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100
	) AS ait_tk,

	@total_tk:=(
		@buyer_amount + @brokerage_tk + @vat_tk + @ait_tk
	) AS total_tk,

	IF(@complete_amount > 0, @total_tk - @complete_amount,'0') AS adjustment_amount,

	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,

	@buyer_amount:=(
		IFNULL(sale_entry.buyer2_amount,0)
	) AS buyer_amount,

	@complete_amount:=(
		IFNULL(sale_entry.buyer2_complete_amount,0)
	) AS complete_amount,

	@brokerage_tk:=(
		(IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100
	) AS brokerage_tk,

	@vat_tk:=(
		(IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100
	) AS vat_tk,

	@ait_tk:=(
		(IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100
	) AS ait_tk,

	@total_tk:=(
		@buyer_amount + @brokerage_tk + @vat_tk + @ait_tk
	) AS total_tk,

	IF(@complete_amount > 0, @total_tk - @complete_amount,'0') AS adjustment_amount,

	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,

	@buyer_amount:=(
		IFNULL(sale_entry.buyer3_amount,0)
	) AS buyer_amount,

	@complete_amount:=(
		IFNULL(sale_entry.buyer3_complete_amount,0)
	) AS complete_amount,

	@brokerage_tk:=(
		(IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100
	) AS brokerage_tk,

	@vat_tk:=(
		(IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100
	) AS vat_tk,

	@ait_tk:=(
		(IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100
	) AS ait_tk,

	@total_tk:=(
		@buyer_amount + @brokerage_tk + @vat_tk + @ait_tk
	) AS total_tk,

	IF(@complete_amount > 0, @total_tk - @complete_amount,'0') AS adjustment_amount,

	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Prompt Date
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$prompt_date = $query->row_array();

$this->db->select("bill_no, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, sum(adjustment_amount) AS adjustment_amount, sum(brokerage_tk) AS total_brokerage, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk, sum(ait_tk) AS total_ait_tk, IF(buyer_type='Internal', sum(total_tk), (IFNULL(sum(total_tk),0) - IFNULL(sum(vat_tk),0))) AS total_total_tk, (CASE WHEN buyer_type='Export' THEN 'B' WHEN buyer_type='Internal' THEN 'A' END) AS buyer_type_ac ");
$this->db->from('bill_register');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_name ASC, buyer_type_ac ASC');
$query = $this->db->get();
$query3 = $query->result_array();
?>
<?php
if (count($query3) > 0) {

// Add CSS for page breaks
echo '<style>
    @media print {
        .page-break {
            page-break-before: always;
        }
    }
</style>';

// 1. New function to print the report header
function print_page_header($page_no, $prompt_date, $website_title, $sale_no, $auction_center, $season)
{
    echo '<table class="table table-sm" id="table" style="margin-bottom:10px;">
        <thead>
            <tr style="border-bottom:1px solid #000;">
                <td style="text-align:left; border:0; width:220px; vertical-align: bottom;">
                    <span style="font-size:13px;">Sale Date <span style="margin-left:16px;">: ' . date('d-M-Y', strtotime($prompt_date['sale_date'])) . '</span></span><br>
                    <span style="font-size:13px;">Prompt Date : ' . date('d-M-Y', strtotime($prompt_date['buyer_prompt_date'])) . '</span>
                </td>
                <td style="text-transform: uppercase; text-align:center; border:0;">
                    <b style="font-size:15px;">' . $website_title . '</b><br>
                    <span style="font-size:13px;">Buyer Wise Bill Statement Sale No. ' . $sale_no . ' ' . $auction_center . ' Season ' . $season . '</span>
                </td>
                <td style="text-align:right; border:0; width:220px; vertical-align: bottom;">
                    <span style="font-size:13px;">Page ' . $page_no . '</span>
                </td>
            </tr>
        </thead>
    </table>';
}

// Separate buyers into Export and Internal lists
$export_buyers = [];
$internal_buyers = [];
foreach ($query3 as $row3) {
    if ($row3['buyer_type'] === 'Export') {
        $export_buyers[] = $row3;
    } else {
        $internal_buyers[] = $row3;
    }
}

// 2. Modified function to render buyer tables with page break logic
function renderBuyerTable(
    $buyers,
    $type_label,
    $prompt_date,
    $website_title,
    $sale_no,
    $auction_center,
    $season,
    &$current_row,
    &$page_no,
    $row_limit_pp
) {
    // Subtotal variables
    $sl = 1;
    $total_bags = 0;
    $total_kgs = 0;
    $total_amount = 0;
    $total_brokerage = 0;
    $total_vat_tk = 0;
    $total_ait_tk = 0;
    $total_total_tk = 0;

    // Check if the section header needs a new page
    if ($current_row > $row_limit_pp - 3) { // Needs space for header (2) + 1 data row
        echo '<div class="page-break"></div>';
        $page_no++;
        print_page_header($page_no, $prompt_date, $website_title, $sale_no, $auction_center, $season);
        $current_row = 3; // Reset counter for new page (header takes ~3 lines)
    }

    echo "<div class='col-sm-12'>";
    echo "<table class='table table-sm' id='table' style='margin-bottom:20px;'>
        <thead>
            <tr>
                <th colspan='2' style='text-align:center; border:1px solid; width:270px;'>{$type_label} Buyers</th>
                <td style='text-align:center; border:1px solid; width:70px;'>Total <br> PKgs</td>
                <td style='text-align:center; border:1px solid; width:90px;'>Total <br> KG</td>
                <td style='text-align:center; border:1px solid; width:130px;'>Amount <br> Tk.</td>
                <td style='text-align:center; border:1px solid; width:120px;'>Vat <br> 15 %</td>
                <td style='text-align:center; border:1px solid; width:120px;'>AIT <br> 1 %</td>
                <td style='text-align:center; border:1px solid; width:120px;'>Brokerage</td>
                <td style='text-align:center; border:1px solid; width:140px;'>Total <br> Amount</td>
            </tr>
        </thead>
        <tbody>";
    $current_row += 2; // Account for table header

    foreach ($buyers as $row3) {
        if ($current_row >= $row_limit_pp) {
            echo '</tbody></table></div>'; // Close current table
            echo '<div class="page-break"></div>';
            $page_no++;
            print_page_header($page_no, $prompt_date, $website_title, $sale_no, $auction_center, $season);
            $current_row = 3; // Reset counter

            // Re-open table with a "Continued" header
            echo "<div class='col-sm-12'>";
            echo "<table class='table table-sm' id='table' style='margin-bottom:20px;'>
            <thead>
                <tr>
                    <th colspan='2' style='text-align:center; border:1px solid; width:270px;'>{$type_label} Buyers</th>
                    <td style='text-align:center; border:1px solid; width:70px;'>Total <br> PKgs</td>
                    <td style='text-align:center; border:1px solid; width:90px;'>Total <br> KG</td>
                    <td style='text-align:center; border:1px solid; width:130px;'>Amount <br> Tk.</td>
                    <td style='text-align:center; border:1px solid; width:120px;'>Vat <br> 15 %</td>
                    <td style='text-align:center; border:1px solid; width:120px;'>AIT <br> 1 %</td>
                    <td style='text-align:center; border:1px solid; width:120px;'>Brokerage</td>
                    <td style='text-align:center; border:1px solid; width:140px;'>Total <br> Amount</td>
                </tr>
            </thead>
            <tbody>";
            $current_row += 2;
        }
        
        echo "<tr>
                <td style='text-align:center; border:0px;'>" . $sl++ . "</td>
                <td style='text-align:left; border:0px; text-transform:uppercase; width:270px; font-size:10px !important;'>" . strtoupper(trim(preg_replace('/\.?\s*bin\.?\s*\d{9,}-\d{4}/i', '', $row3['buyer_name']))) . "</td>
                <td style='text-align:right; border:0px; width:70px;'>" . $row3['total_bags'] . "</td>
                <td style='text-align:right; border:0px; width:90px;'>" . number_format($row3['total_kgs'], 2, '.', '') . "</td>
                <td style='text-align:right; border:0px; width:130px;'>" . number_format($row3['total_amount'], 2, '.', ',') . "</td>
                <td style='text-align:right; border:0px; width:120px;'>" . number_format($row3['total_vat_tk'], 2, '.', ',') . "</td>
                <td style='text-align:right; border:0px; width:120px;'>" . number_format($row3['total_ait_tk'], 2, '.', ',') . "</td>
                <td style='text-align:right; border:0px; width:120px;'>" . number_format($row3['total_brokerage'], 2, '.', ',') . "</td>
                <td style='text-align:right; border:0px; width:140px;'>" . number_format($row3['total_total_tk'], 2, '.', ',') . "</td>
            </tr>";
        $current_row++;

        // Accumulate totals
        $total_bags += $row3['total_bags'];
        $total_kgs += $row3['total_kgs'];
        $total_amount += $row3['total_amount'];
        $total_vat_tk += $row3['total_vat_tk'];
        $total_ait_tk += $row3['total_ait_tk'];
        $total_brokerage += $row3['total_brokerage'];
        $total_total_tk += $row3['total_total_tk'];
    }

    echo "<tr style='border:1px solid !important;'>
            <th colspan='2' style='text-align:right;'>Sub Total : </th>
            <th style='text-align:right;'>$total_bags</th>
            <th style='text-align:right;'>" . number_format($total_kgs, 2, '.', '') . "</th>
            <th style='text-align:right;'>" . number_format($total_amount, 2, '.', ',') . "</th>
            <th style='text-align:right;'>" . number_format($total_vat_tk, 2, '.', ',') . "</th>
            <th style='text-align:right;'>" . number_format($total_ait_tk, 2, '.', ',') . "</th>
            <th style='text-align:right;'>" . number_format($total_brokerage, 2, '.', ',') . "</th>
            <th style='text-align:right;'>" . number_format($total_total_tk, 2, '.', ',') . "</th>
        </tr>";
    $current_row++;
    echo "</tbody></table></div>";

    return [
        'bags' => $total_bags, 'kgs' => $total_kgs, 'amount' => $total_amount,
        'vat' => $total_vat_tk, 'ait' => $total_ait_tk, 'brokerage' => $total_brokerage,
        'total' => $total_total_tk
    ];
}

// 3. Updated main execution logic
// Initialize page and row counters
$page_no = 1;
$current_row = 0;

// Print the first page header
print_page_header($page_no, $prompt_date, $website_title, $sale_no, $auction_center, $season);
$current_row += 3; // Header takes up about 3 lines

// Render tables
$export_total = [];
$internal_total = [];

if (!empty($export_buyers)) {
    $export_total = renderBuyerTable($export_buyers, 'Export', $prompt_date, $website_title, $sale_no, $auction_center, $season, $current_row, $page_no, $row_limit_pp);
}

// The internal buyers section will continue the count from the export section
$internal_total = renderBuyerTable($internal_buyers, 'Internal', $prompt_date, $website_title, $sale_no, $auction_center, $season, $current_row, $page_no, $row_limit_pp);

// Calculate Grand Total
$grand_total = [
    'bags' => (!empty($export_total) ? $export_total['bags'] : 0) + $internal_total['bags'],
    'kgs' => (!empty($export_total) ? $export_total['kgs'] : 0) + $internal_total['kgs'],
    'amount' => (!empty($export_total) ? $export_total['amount'] : 0) + $internal_total['amount'],
    'vat' => (!empty($export_total) ? $export_total['vat'] : 0) + $internal_total['vat'],
    'ait' => (!empty($export_total) ? $export_total['ait'] : 0) + $internal_total['ait'],
    'brokerage' => (!empty($export_total) ? $export_total['brokerage'] : 0) + $internal_total['brokerage'],
    'total' => (!empty($export_total) ? $export_total['total'] : 0) + $internal_total['total'],
];

// Check if grand total row needs a new page
if ($current_row > $row_limit_pp - 2) {
    echo '<div class="page-break"></div>';
    $page_no++;
    print_page_header($page_no, $prompt_date, $website_title, $sale_no, $auction_center, $season);
}
?>
<div class="col-sm-12">
    <table class="table table-sm" id="table" style="margin-top:10px; width:1160px !important;">
        <tbody>
            <tr style="border:1px solid !important;">
                <th colspan="2" style="text-align:right; border:0px; width:270px;">GRAND TOTAL :</th>
                <th style="text-align:right; border:0px; width:70px;"><?php echo $grand_total['bags']; ?></th>
                <th style="text-align:right; border:0px; width:90px;"><?php echo number_format($grand_total['kgs'], 2, '.', ''); ?></th>
                <th style="text-align:right; border:0px; width:130px;"><?php echo number_format($grand_total['amount'], 2, '.', ','); ?></th>
                <th style="text-align:right; border:0px; width:120px;"><?php echo number_format($grand_total['vat'], 2, '.', ','); ?></th>
                <th style="text-align:right; border:0px; width:120px;"><?php echo number_format($grand_total['ait'], 2, '.', ','); ?></th>
                <th style="text-align:right; border:0px; width:120px;"><?php echo number_format($grand_total['brokerage'], 2, '.', ','); ?></th>
                <th style="text-align:right; border:0px; width:140px;"><?php echo number_format($grand_total['total'], 2, '.', ','); ?></th>
            </tr>
        </tbody>
    </table>
</div>
<?php
} else {
    echo "<div id=\"page\"><h1 style=\"color: #444; background-color: transparent; border-bottom: 1px solid #D0D0D0; font-size: 19px; font-weight: normal; margin: 0 0 14px 0; padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1>
    <p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>
<?php } ?>



<?php if($report_name=='vat'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4;
	margin-left: 100px;
}
</style>

<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>
<?php 
// page row count
$row_limit_pp = 35;

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<?php
// query
$sale_date = '';
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat_tk = 0;
	
$this->db->select("sale_date, buyer_name, buyer_type, (CASE WHEN buyer_type='Internal' THEN 'A' WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk ");

$this->db->from('bill_register');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_type_ac ASC, buyer_id ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
	<tr>
		<td style="text-align:left; border:0px;" colspan="6">
			<b style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b><br>
			<span style="font-size:13px; text-transform: capitalize;">Agrabad C/A, Chittagong</span>
		</td>
	</tr>

	<tr>
		<td style="text-align:left; border:0px;" colspan="3">
			<span style="font-size:13px;">
				The Revenue Officer,<br>
				<b style="font-size:13px;">Custom Excise & Vat</b><br>
				Circle - 1 Area Code - 20101, Halishahar, Chittagong.
			</span>
			
		</td>
		<td style="text-align:right; border:0px;" colspan="3">
			<span style="font-size:13px;">
				Page <?php print $page; ?><br>
				Date: <?php print date('d-M-Y') ; ?>
			</span>
		</td>
	</tr>

	<tr>
		<td style="text-align:left; border:0px;" colspan="6">
			<span style="font-size:13px;">Re: Sale Wise Buyers Purchase Statement Season <?php print $season; ?></span>
		</td>
	</tr>

	<tr>
		<td style="text-align:left; border:0px;" colspan="6">
			<span style="font-size:13px;">
				Dear Sir,<br />
				With referance to the above we give below the above statement for sale No. <?php print $sale_no; ?> <?php print $auction_center ; ?> held on <span id="sale_date"></span>
			</span>
		</td>
	</tr>
	<tr>
		<th style="text-align:center; border:1px solid;">SL</th>
		<th style="text-align:left; border:1px solid;">BUYER NAME</th>
		<th style="text-align:center; border:1px solid;">PKGS</th>
		<th style="text-align:center; border:1px solid;">KGS</th>
		<th style="text-align:center; border:1px solid;">AMOUNT</th>
		<th style="text-align:right; border:1px solid;">VAT (TAKA)</th>
	</tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
    <td style="text-align:center; border:1px solid; vertical-align: middle;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:1px solid;"><?php echo $row3['buyer_name']; ?> <?php echo ($row3['buyer_type']=='Export') ? '<b style="color:red;"> - '.$row3['buyer_type'].'</b>' : ''; ?></td>
	<td style="text-align:right; text-transform:uppercase; border:1px solid;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['total_vat_tk'],2,'.',','); ?></td>
</tr>
<?php

$sale_date = date("d-M-Y",strtotime($row3['sale_date']));
$total_bags += $row3['total_bags'];
$total_kgs += $row3['total_kgs'];
$total_amount += ($row3['buyer_type']=='Internal') ? $row3['total_amount'] : '0.00';
$total_vat_tk += $row3['total_vat_tk'];

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan="2" style="text-align:right; color:red; border:1px solid;">TOTAL : </th>
	<th style="text-align:right; color:red; border:1px solid;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red; border:1px solid;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red; border:1px solid;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red; border:1px solid;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>
<div class="row">
	<div class="col-sm-1"></div>
	<div class="col-sm-11">
		<p>Note: 15% Vat paid for Internal A/C for taka <?php echo number_format($total_amount,2,'.',','); ?> &nbsp;&nbsp; Vat Taka <?php echo number_format($total_vat_tk,2,'.',','); ?></p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Export A/C for Taka</p>
	</div>

	<div class="col-sm-12" style="margin-top:10px;">
		Sincerely Yours,
	</div>
	
	<div class="col-sm-12 text-left" style="margin-top:40px;">
		<b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b><br><br>
		<span style="font-size:14px;"> CC: Superintendent, Custom Excise & VAT, Moulvibazar</span><br>
		<span style="font-size:14px;"> CC: Superintendent, Custom Excise & VAT, Panchagarh</span>
	</div>

	<div class="col-sm-12 text-left" style="margin-top:20px;">
		<i style="text-transform: uppercase; font-size:14px; font-weight:bold;"> Developed By @ Jamuna Tech </i>
	</div>
</div>
<script>
$("#sale_date").html("<?php print $sale_date; ?>");
</script>
<?php } // IF VAT ?>


<?php if($report_name=='ait'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:auto;
	margin-left: 100px;
}
</style>

<div class="row">
<div class="form-group col-sm-6 text-left">
	<a class="no-print" href="<?php echo site_url('MY_Controller/bill_regsiter_reports?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&report_name=ait&buyer_category=All'); ?>"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> All Buyers</a>
	
	&nbsp; &nbsp;
	
	<a class="no-print" href="<?php echo site_url('MY_Controller/bill_regsiter_reports?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&report_name=ait&buyer_category=Limited'); ?>"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Limited Buyers</a>
	
	&nbsp; &nbsp;
	
	<a class="no-print" href="<?php echo site_url('MY_Controller/bill_regsiter_reports?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&report_name=ait&buyer_category=Others'); ?>"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Others Buyers</a>
</div>
<div class="form-group col-sm-6 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,
	sale_entry.buyer_type1 AS buyer_type,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk, 
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3) AS bill_no	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>
<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:center; border:0px; text-transform: uppercase;" colspan="6">
		<b style="font-size:15px;"><?php print $website_title; ?></b><br>
		<span style="font-size:13px; text-transform: capitalize;">Progressive tower (5th floor) 1837, Sk. mujib road,  agrabad C/A, Chittagong</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="4">
		<span style="font-size:13px;">Ref: UBL/AIT/<?php print $sale_no ; ?>/<?php print $auction_center ; ?>/<?php print $season ; ?></span>
	</td>

	<td style="text-align:right; border:0px;" colspan="2">
		<span style="font-size:13px;">Date: <?php print date('d/m/Y') ; ?></span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="6">
		<span style="font-size:13px;">
			The Deputy Commissioner of Taxes <br />
			Taxes Zone - 2 <br />
			Chittagong
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="6">
		<b style="font-size:13px;">Re: Sale Wise Buyers Purchase Statement of Sale No. <?php print $sale_no ; ?> <?php print $auction_center ; ?> for Payment of AIT Season <?php print $season; ?></b>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="6">
		<span style="font-size:13px;">
			Dear Sir,<br />
			With referance to your letter no. TaskForce/Tea Broker<br>
			We give below the above statement for <b>sale No. <?php print $sale_no; ?> <?php print $auction_center ; ?></b> held on <span id="sale_date"></span> & AIT Realised at source as here under:
		</span>
	</td>
</tr>

<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
    <th style="text-align:center; border:1px solid;">BUYER NAME</th>
	<th style="text-align:center; border:1px solid;">PKGS</th>
	<th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:right; border:1px solid;">AMOUNT</th>
	<th style="text-align:center; border:1px solid;">1% AIT</th>
</tr>
</thead>
<tbody>
<?php 
$sl = 1;
$total_amount = 0;
$total_bags = 0;
$total_kgs = 0;
$total_ait_tk = 0;
if($buyer_category=='Limited' || $buyer_category=='All'){ 
?>
<tr>
	<td style="text-align:left; font-weight:bold; border:0px; color:red;"><u>LIMITED</u></td>
</tr>
<?php
$sale_date = '';

// LIMITED BUYER
$this->db->select("bill_register.buyer_id, bill_register.sale_date, buyer_setup.buyer_name, buyer_setup.buyer_category, buyer_setup.buyer_tin_no, bill_register.buyer_type, sum(bill_register.buyer_amount) AS total_amount, sum(bill_register.ait_tk) AS total_ait_tk, sum(bill_register.buyer_bag) AS total_bags, sum(bill_register.buyer_kgs) AS total_kgs");

$this->db->from('bill_register');
$this->db->join('buyer_setup','bill_register.buyer_id = buyer_setup.id');
$this->db->where('buyer_setup.buyer_category','LTD');
$this->db->group_by('bill_register.buyer_id');
$this->db->order_by('buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:1px solid; vertical-align: middle; width: 1%;"><?php print $sl++ ; ?></td>
	<td style="text-align:left; border:1px solid; text-transform:uppercase;  width: 69%;">
		<?php echo preg_replace('/\s*bin.*/i', '', $row3['buyer_name']);  ?> 
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_bags'],2,'.',','); ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_kgs'],2,'.',','); ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_amount'],2,'.',','); ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_ait_tk'],2,'.',','); ?>
	</td>
</tr>
<?php
	$sale_date = date("d/m/Y",strtotime($row3['sale_date']));
	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$total_ait_tk += $row3['total_ait_tk'];
	
}  // 1st loop
?>
<!--<tr>
	<th colspan='2' style="text-align:right; border:1px solid; color:red;">LIMITED TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_bags,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_ait_tk,2,'.',','); ?></th>
</tr>-->
<?php } ?>


<?php 
$total_amount2 = 0;
$total_ait_tk2 = 0;
$total_bags2 = 0;
$total_kgs2 = 0;

if($buyer_category=='Others' || $buyer_category=='All'){ 
?>
<tr>
	<td style="text-align:left; font-weight:bold; border:0px; color:red;"><u>OTHERS</u></td>
</tr>
<?php
$sl = 1;
$sale_date = '';

// OTHERS BUYER
$this->db->select("bill_register.buyer_id, bill_register.sale_date, buyer_setup.buyer_name, buyer_setup.buyer_category, buyer_setup.buyer_tin_no, bill_register.buyer_type, sum(bill_register.buyer_amount) AS total_amount, sum(bill_register.ait_tk) AS total_ait_tk, sum(bill_register.buyer_bag) AS total_bags, sum(bill_register.buyer_kgs) AS total_kgs "); 	
$this->db->from('bill_register');
$this->db->join('buyer_setup','bill_register.buyer_id = buyer_setup.id');
$this->db->where('buyer_setup.buyer_category','Org');
$this->db->group_by('bill_register.buyer_id');
$this->db->order_by('buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:1px solid; vertical-align: middle; width: 1%;">
		<?php echo $sl++; ?>
	</td>
	<td style="text-align:left; border:1px solid; text-transform:uppercase; width: 69%;">
		<?php echo preg_replace('/\s*bin.*/i', '', $row3['buyer_name']);  ?> 
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_bags'],2,'.',','); ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_kgs'],2,'.',','); ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_amount'],2,'.',','); ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['total_ait_tk'],2,'.',','); ?>
	</td>
</tr>
<?php
	$sale_date = date("d/m/Y",strtotime($row3['sale_date']));
	$total_bags2 += $row3['total_bags'];
	$total_kgs2 += $row3['total_kgs'];
	$total_amount2 += $row3['total_amount'];
	$total_ait_tk2 += $row3['total_ait_tk'];
}  // foreach


if($total_ait_tk2 > 0){
?>
<!--<tr>
	<th colspan="2" style="text-align:right; border:1px solid; color:red;">OTHERS TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_bags2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_ait_tk2,2,'.',','); ?></th>
</tr>-->
<?php 
}

} // end of others
?>

<tr>
	<th colspan="2" style="text-align:right; border:1px solid; color:red;">GRAND TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_bags + $total_bags2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_kgs + $total_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_amount + $total_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_ait_tk + $total_ait_tk2,2,'.',','); ?></th>
</tr>
</tbody>
</table>

<br />
<br />

<div class="row">
	<div class="col-sm-12">
		We enclose herewith a cheque no............................Dt...................for TK: <?php echo number_format($total_ait_tk + $total_ait_tk2,2,'.',','); ?> drawn on UCB, Agrabad Branch, Chittagong. Please acknowledge receipt.<br><br>
		Sincerely Yours,
	</div>
	
	<div class="col-sm-12 text-left" style="margin-top:40px;">
		<b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b>
	</div>
</div>
<!-- /row -->

<br />

</div>
</div> 
<!-- /row -->

<script>
$("#sale_date").html("<?php print $sale_date; ?>");
</script>
<?php } // IF AIT ?>


<?php if($report_name=='brokerage'){ ?>
<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1) AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2) AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3) AS bill_no	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="5" style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	BUYER BROKERAGE STATEMENT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th colspan="5" style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sale Date: <span id="sale_date"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> 
	</th>
</tr>
<tr>
    <th style="text-align:left; border:1px solid;">BILL</th>
    <th style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; border:1px solid;">BAGS</th>
	<th style="text-align:right; border:1px solid;">KGS</th>
	<th style="text-align:right; border:1px solid;">BROKERAGE</th>
</tr>
</thead>
<tbody>
<?php
// query
$sale_date = '';
$total_bags = 0;
$total_kgs = 0;
$total_brokerage = 0;
	
$this->db->select("bill_no, sale_date, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(brokerage_tk) AS total_brokerage "); 	
$this->db->from('bill_register');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_type DESC, buyer_id ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
    <td style="text-align:left; border:0px;"><?php echo $row3['bill_no']; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row3['buyer_name']; ?> - <?php echo $row3['buyer_type']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_brokerage'],2,'.',','); ?></td>
</tr>	

<?php
	$sale_date = date("d-m-Y",strtotime($row3['sale_date']));
	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_brokerage += $row3['total_brokerage'];
	
}  // 1st loop
?>

<tr>
	<th></th>
	<th style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:right; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_brokerage,2,'.',','); ?></th>
</tr>

<tr>
	<th colspan="6" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

<script>
$("#sale_date").html("<?php print $sale_date; ?>");
</script>
<?php } // IF BROKERAGE ?>



<?php if($report_name=='TB'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - buyer_tb_stmt
$this->db->query("DROP TABLE IF EXISTS `buyer_tb_stmt`");

// create buyer_tb_stmt @  buyer-1
$this->db->query("CREATE TABLE `buyer_tb_stmt` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_tb_stmt @  buyer-2
$this->db->query("INSERT INTO `buyer_tb_stmt` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_tb_stmt @  buyer-3
$this->db->query("INSERT INTO `buyer_tb_stmt` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="8" style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	BUYER TEA BOARD STATEMENT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th colspan="8" style="text-align:left; font-size:14px !important; border:0px;"> 
	The Secretary<br />
	Bangladesh Tea Board<br />
	Nasirabad, Chittagong.
	</th>
</tr>
<tr>
	<th colspan="8" style="text-align:left; border:0px;">
		Dear Sir, <br /> We give below the purchase made by the following buyers in our catalogue for sale No. <b><?php print $sale_no; ?> </b> &nbsp; held on <span id="sale_date"></span> Season: <?php print $season; ?> 
	</th>
</tr>
<tr>
    <th style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; border:1px solid;">LEAF BAGS</th>
	<th style="text-align:right; border:1px solid;">DUST BAGS</th>
	<th style="text-align:right; border:1px solid;">LEAF KGS</th>
	<th style="text-align:right; border:1px solid;">DUST KGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL BAGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL KGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL AMOUNT</th>
</tr>
</thead>
<tbody>
<?php
// reset data
$sale_date = '';
$leaf_bag = 0;
$dust_bag = 0;
$leaf_kgs = 0;
$dust_kgs = 0;
$net_bag = 0;
$net_kgs = 0;
$total_amount = 0;

// query	
$this->db->select("buyer_tb_stmt.sale_date, buyer_setup.buyer_name, buyer_tb_stmt.buyer_type, sum(buyer_tb_stmt.leaf_bag) AS leaf_bag, sum(buyer_tb_stmt.dust_bag) AS dust_bag, sum(buyer_tb_stmt.leaf_kgs) AS leaf_kgs, sum(buyer_tb_stmt.dust_kgs) AS dust_kgs, sum(buyer_tb_stmt.buyer_amount) AS total_amount ");
$this->db->from('buyer_tb_stmt');
$this->db->join('buyer_setup','buyer_tb_stmt.buyer_id = buyer_setup.id');
$this->db->group_by('buyer_tb_stmt.buyer_id, buyer_tb_stmt.buyer_type');
$this->db->order_by('buyer_tb_stmt.buyer_type DESC, buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['buyer_name']; ?> - <?php echo substr($row3['buyer_type'],0,1); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['leaf_bag']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['dust_bag']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['leaf_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['dust_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php echo $total_bag = $row3['leaf_bag'] + $row3['dust_bag']; ?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php echo $total_kgs = number_format($row3['leaf_kgs'] + $row3['dust_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
</tr>	

<?php
	$sale_date = date("d-m-Y",strtotime($row3['sale_date']));
	$leaf_bag += $row3['leaf_bag'];
	$dust_bag += $row3['dust_bag'];
	$leaf_kgs += $row3['leaf_kgs'];
	$dust_kgs += $row3['dust_kgs'];
	$net_bag += $total_bag;
	$net_kgs += $total_kgs;
	$total_amount += $row3['total_amount'];
	
}  // 1st loop
?>

<tr>
	<th style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:right; color:red;"><?php echo $leaf_bag; ?></th>
	<th style="text-align:right; color:red;"><?php echo $dust_bag; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($leaf_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($dust_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo $net_bag; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
</tr>

<tr>
	<th colspan="8" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

<br />

<div class="row">
	<div class="col-sm-7 text-primary">
	</div>
	<div class="col-sm-5 text-primary text-center">
		<div class="text-left"> 
			<b style="text-align:center; font-size:14px;">&nbsp; &nbsp; &nbsp; Yours faithfully</b> <br /> For <b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b>
		</div>
	</div>
</div>

<br />

<div class="row">
<div class="col-sm-12">
	<div style="font-weight:bold;">C.C TO</div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">1. THE SECRETARY, TTAB, CTG</div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">2. THE DEPUTY DIRECTOR(TRADE), BTB, CTG</div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">3. The Assistant Director(TRADE), BTB, CTG </div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">4. The Accounts Officer(GARDEN CELL), BTB, CTG </div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;"></div>
</div>
</div>

<br />

<center><i>System by @ Jamuna Tech</i></center>

<script>
$("#sale_date").html("<?php print $sale_date; ?>");
</script>
<?php } // tb single sale no ?>

<?php if($report_name=='TB_S2S'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:auto;
	margin-left: 100px;
}
</style>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="9">
		<b style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b><br>
		<span style="font-size:13px; text-transform: capitalize;">Agrabad C/A, Chittagong</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="6">
		<span style="font-size:13px;">
			The Secretary,<br>
			<b>Bangladesh Tea Board</b><br>
			171/172 Baizid Bostami Road Nasirabad, Chittagong.
		</span>
		
	</td>
	<td style="text-align:right; border:0px;" colspan="3">
		<span style="font-size:13px;">
			Date: <?php print date('d-M-Y') ; ?>
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="9">
		<span style="font-size:13px;">Re: Sale Wise Buyers Purchase Statement Season <?php print $season; ?></span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="9">
		<span style="font-size:13px;">
			With referance to your letter no. BTB-FB/Trade<br>
			We give below the above statement for sale No. <?php print $sale_no; ?> To <?php print $sale_to; ?> <?php print $auction_center ; ?> held on <span id="sale_date"></span>
		</span>
	</td>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
    <th style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; border:1px solid;">LEAF PkGS</th>
	<th style="text-align:right; border:1px solid;">DUST PkGS</th>
	<th style="text-align:right; border:1px solid;">LEAF KGS</th>
	<th style="text-align:right; border:1px solid;">DUST KGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL PkGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL KGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL AMOUNT</th>
</tr>
</thead>
<tbody>
<?php
// query
$sale_date = '';
$sl = 1;
$leaf_bag = 0;
$dust_bag = 0;
$leaf_kgs = 0;
$dust_kgs = 0;
$net_bag = 0;
$net_kgs = 0;
$total_amount = 0;
	
$this->db->select("buyer_id, buyer_name, buyer_type, sum(leaf_bag) AS leaf_bag, sum(dust_bag) AS dust_bag, sum(leaf_kgs) AS leaf_kgs, sum(dust_kgs) AS dust_kgs, sum(buyer_amount) AS total_amount, (CASE WHEN buyer_type='Internal' THEN 'A' WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac"); 	
$this->db->from('bill_register');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_type_ac ASC, buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
	<td style="text-align:center; border:1px solid; vertical-align: middle;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:1px solid; font-weight:bold; white-space:nowrap;"><?php echo $row3['buyer_name']; ?> - <?php echo substr($row3['buyer_type'],0,1); ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['dust_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo number_format($row3['leaf_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo number_format($row3['dust_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;">
		<?php echo $total_bag = $row3['leaf_bag'] + $row3['dust_bag']; ?>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;">
		<?php echo $total_kgs = number_format($row3['leaf_kgs'] + $row3['dust_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
</tr>	

<?php
	$leaf_bag += $row3['leaf_bag'];
	$dust_bag += $row3['dust_bag'];
	$leaf_kgs += $row3['leaf_kgs'];
	$dust_kgs += $row3['dust_kgs'];
	$net_bag += $total_bag;
	$net_kgs += $total_kgs;
	$total_amount += $row3['total_amount'];
	
}  // 1st loop
?>

<tr>
	<th colspan="2" style="text-align:right; border:1px solid; color:red;">TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $leaf_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $dust_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($leaf_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($dust_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $net_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
</tr>

<tr>
	<th colspan="9" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

<br />

<div class="row">
	<div class="col-sm-7 text-primary">
	</div>
	<div class="col-sm-5 text-primary text-center">
		<div class="text-left"> 
			<b style="text-align:center; font-size:13px;">&nbsp; &nbsp; &nbsp; Yours faithfully</b> <br /> For <b style="text-transform: uppercase; font-size:13px;"> <?php print $website_title; ?> </b>
		</div>
	</div>
</div>

<br />

<div class="row">
<div class="col-sm-12">
	<div style="font-weight:bold; font-size:13px;">C.C TO</div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase; font-size:13px;">1. THE SECRETARY, TTAB, CTG</div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase; font-size:13px;">2. THE DEPUTY DIRECTOR(TRADE), BTB, CTG</div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase; font-size:13px;">3. The Assistant Director(TRADE), BTB, CTG </div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase; font-size:13px;">4. The Accounts Officer(GARDEN CELL), BTB, CTG </div>
	<div contenteditable="true" style="font-weight:bold; text-transform:uppercase; font-size:13px;"></div>
</div>
</div>
<?php } // TB stmt sale to sale ?>

<?php if($report_name=='tea_board_stmt'){ ?>
<style>
#table tr td, 
#table2 tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table tr th,
#table2 tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4 Portrait;
	margin-left: 100px;
}
</style>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<?php
// get html value
$row_limit_pp = 35;

// query
$sale_date = '';
$sl = 1;
$net_bag = 0;
$net_kgs = 0;
$total_amount = 0;
$net_avg = 0;
	
$this->db->select("buyer_id, buyer_name, buyer_type, sum(leaf_bag) AS leaf_bag, sum(dust_bag) AS dust_bag, sum(leaf_kgs) AS leaf_kgs, sum(dust_kgs) AS dust_kgs, sum(buyer_amount) AS total_amount, (CASE WHEN buyer_type='Internal' THEN 'A' WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac"); 	
$this->db->from('bill_register');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_type_ac ASC, buyer_name ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table" style="margin-bottom: 10px;">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="6">
		<b style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b><br>
		<span style="font-size:13px; text-transform: capitalize;">Agrabad C/A, Chittagong</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="4">
		<span style="font-size:13px;">
			The Secretary,<br>
			<b>Bangladesh Tea Board</b><br>
			171/172 Baizid Bostami Road Nasirabad, Chittagong.
		</span>
	</td>
	<td style="text-align:right; border:0px;" colspan="2">
		<span style="font-size:13px;">
			Page: <?php print $page ; ?><br>
			Print Date: <?php print date('d-M-Y') ; ?>
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="6">
		<span style="font-size:13px;">Re: Sale Wise Buyers Purchase Statement Season <?php print $season; ?></span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="6">
		<span style="font-size:13px;">
			With referance to your letter no. BTB-FB/Trade<br>
			We give below the above statement for sale No. <?php print $sale_no; ?> <?php print $auction_center ; ?> held on <span id="sale_date"></span>
		</span>
	</td>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
    <th style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; border:1px solid;">PKGS</th>
	<th style="text-align:right; border:1px solid;">KGS</th>
	<th style="text-align:right; border:1px solid;">AMOUNT</th>
	<th style="text-align:right; border:1px solid;">AVG</th>
</tr>
</thead>
<tbody>
<?php } //end ?>

<tr>
	<td style="text-align:center; border:1px solid; vertical-align: middle; font-weight:bold;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:1px solid; font-weight:bold; white-space:nowrap;"><?php echo $row3['buyer_name']; ?> - <?php echo substr($row3['buyer_type'],0,1); ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;">
		<?php echo $total_bag = $row3['leaf_bag'] + $row3['dust_bag']; ?>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;">
		<?php echo $total_kgs = number_format($row3['leaf_kgs'] + $row3['dust_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $total_avg = number_format($row3['total_amount'] / $total_kgs,2,'.',''); ?></td>
</tr>	
<?php

$net_bag += $total_bag;
$net_kgs += $total_kgs;
$total_amount += $row3['total_amount'];

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>
</tbody>
</table>

</div> <!-- col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 
?>
<?php
if ($net_kgs > 0) {
    $net_avg = $total_amount / $net_kgs;
} else {
    $net_avg = 0;
}
?>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm" id="table2">
			<tr>
				<th colspan="2" style="text-align:right; border:1px solid; font-size:11px !important; font-family:courier;">Sub Total : </th>
				<th style="text-align:right; border:1px solid;"><?php echo $net_bag; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_amount,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($net_avg,2,'.',','); ?></th>
			</tr>
			<!-- This row is only used for two row spacing -->
			<tr><td height="10" colspan="6"></td></tr>
			<!-- This row is only used for two row spacing -->
			<tr>
				<th colspan="2" style="text-align:right; border:1px solid; font-size:11px !important; font-family:courier;">Gross Total : </th>
				<th style="text-align:right; border:1px solid;"><?php echo $net_bag; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_amount,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($net_avg,2,'.',','); ?></th>
			</tr>
		</table>
	</div>
	<div class="col-sm-12" style="margin-top:10px; font-family:courier;">
		Sincerely Yours,
	</div>
	
	<div class="col-sm-12 text-left" style="margin-top:40px;">
		<b style="text-transform: uppercase; font-size:14px; font-family:courier;"> <?php print $website_title; ?> </b><br><br>
		<span style="font-size:14px; font-family:courier;"> CC: Deputy Director (Trade), Bangladesh Tea Board, Chittagong.</span>
	</div>

	<div class="col-sm-12 text-left" style="margin-top:20px;">
		<i style="text-transform: uppercase; font-size:14px; font-weight:bold;"> Developed By @ Jamuna Tech </i>
	</div>
</div>
<?php
// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>
<?php } // TB stmt sale to sale ?>

<?php if($report_name=='sellers'){ ?>
<style>
#table tr td {
    border-color:black;
    font-size:12px !important;
    padding:15px;
}
#table tr th{
    border-color:black;
    font-size:10px !important;
    padding:3px;
}

#table thead tr th{
    border-top: 1px dashed #000 !important; 
    border-bottom: 1px dashed #000 !important;
    text-align:center;
    text-transform:uppercase;
    font-size:10px !important;
    padding:3px;
}

@page {
  size: legal landscape;
  margin-left: 60px;
  margin-right: 130px;
}

/* Add page break CSS */
.page-break {
    page-break-before: always;
    page-break-after: always;
}

/* Print styles */
@media print {
    .page-break {
        page-break-before: always;
    }
    #table {
        page-break-inside: auto;
    }
    #table tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>

<div class="row">
<div class="form-group col-sm-12 text-right">
    <a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>

<?php 
// get html value
$row_limit_pp = 16;

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 
    invoice_entry.season,
    invoice_entry.old_season,
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.garden_id,    
    invoice_entry.account_concerned,
    gn.garden_name,
    @total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
    @total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
    @total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
    @compelte_amount:=(IFNULL(sale_entry.buyer1_complete_amount,0) + IFNULL(sale_entry.buyer2_complete_amount,0) + IFNULL(sale_entry.buyer3_complete_amount,0)) AS compelte_amount,         
    (@total_bags * IFNULL(invoice_entry.warehouse_charge,0)) AS warehouse_charge,
    (@total_bags * IFNULL(invoice_entry.insp_sample,0)) AS insp_sample,
    ((@total_amount * IFNULL(invoice_entry.brokerage_commission,0))/100) AS brokerage_commission,
    ((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess,
    @total_bill := (@total_amount + (@total_amount * 16 / 100)) AS total_bill,    
    @net_total_bill := ((@total_bill + @total_kgs * 10 / 100) - @compelte_amount) AS net_total_bill,    
    @adjustment_bill := IF(@compelte_amount > 0, @net_total_bill, 0) AS adjustment_bill
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 
WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();
?>

<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;
$net_adjustment_bill = 0;

// query
$this->db->select("
    bill_register.old_season, 
    bill_register.garden_id, 
    bill_register.account_concerned, 
    IF(
        bill_register.season = bill_register.old_season, bill_register.garden_name, CONCAT(bill_register.garden_name, ' (', bill_register.old_season, ')')
    ) AS garden_name, 
    IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
    sum(bill_register.total_bags) AS total_bags, 
    sum(bill_register.total_kgs) AS total_kgs, 
    sum(bill_register.total_amount) AS total_amount, 
    sum(bill_register.adjustment_bill) AS adjustment_bill, 
    sum(bill_register.warehouse_charge) AS warehouse_charge, 
    sum(bill_register.insp_sample) AS insp_sample, 
    sum(bill_register.brokerage_commission) AS brokerage_commission, 
    sum(bill_register.tea_cess) AS tea_cess, 
    IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
    IFNULL(gat.garden_misc,0) AS garden_misc,
    IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
    IFNULL(gat.truck_fare,0) AS truck_fare,  
    IFNULL(gat.bank_charges,0) AS bank_charges 
");     
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, garden_id, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND `auction_center`='".$auction_center."' AND `sale_no`='".$sale_no."' GROUP BY `garden_id`, `old_season`) gat", "bill_register.garden_id = gat.garden_id AND bill_register.old_season = gat.old_season", "left");

$this->db->group_by('bill_register.garden_id, bill_register.old_season, bill_register.account_concerned');
$this->db->order_by('bill_register.garden_name ASC');
$query = $this->db->get();
$query3 = $query->result_array();

if(count($query3) > 0){

    // manipulate row section
    $row_start = 1;
    $row_end = $row_limit_pp;
    $row_col = 1;
    $pp_limit = $row_end * $row_col;
    $total_records = count($query3);
    $i = 1;
    $page = 0; // Initialize page counter
    $row_counter = 0; // Counter for rows on current page

    // get valuation query
    foreach($query3 as $row3){

        // total tk
        $total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

        // total deduction tk
        $total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));

        // Start new page when row_counter is 0 or multiple of row_limit_pp
        if($row_counter == 0){
            $page++; // Increment page number
            ?>
            <!-- start top heading row -->
            <div class="row">
            <div class="col-sm-12">
            <table class="table table-sm" id="table">
            <thead>
                <tr>
                    <td colspan="16" style="text-transform:uppercase; text-align:center; border:0px;">
                        <b style="font-size:13px;">
                            <?php print $website_title; ?> <br>
                            Account sales for sale no &nbsp; <?php print $sale_no; ?> &nbsp; Season &nbsp; <?php print $season; ?> <br> <?php print $auction_center; ?>
                            held on &nbsp; <?php print date('d-M-Y' , strtotime($result_prompt_date['sale_date'])); ?> &nbsp; &nbsp; Prompt Date &nbsp; <?php print date('d-M-Y' , strtotime($result_prompt_date['buyer_prompt_date'])); ?>
                        </b>
                    </td>
                    <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
                </tr>
                <tr>
                    <th>SL</th>
                    <th>GARDEN NAME</th>
                    <th>PKGS</th>
                    <th>KGS</th>
                    <th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
                    <th style="white-space:nowrap;">Sold <br /> Per KG</th>
                    <th>Tea <br /> Cess</th>
                    <th>Warehouse <br /> Charges</th>
                    <th>Inspec <br /> -tion</th>
                    <th>Brokerage</th>
                    <th>Bank <br /> Charges</th>
                    <th>Advance</th>
                    <th>Truck <br /> Fare</th>
                    <th>Garden <br /> Misc.</th>
                    <th>Additional <br /> Rent</th>
                    <th>Total <br /> Deduction</th>
                    <th>Net <br /> Proceeds</th>
                </tr>
            </thead>
            <tbody>
            <?php
        }

        // Display row
        ?>
        <tr>
            <td style="text-align:left; text-transform:uppercase; border:0px;">
                <?php print $sl++ ; ?>
            </td>
            <td style="text-align:left; text-transform:uppercase; border:0px; white-space:nowrap;">
                <?php if($row3['report_link']=='Current'){ ?>
                    <a target="blink" href="<?php echo site_url('MY_Controller/garden_sale_account_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row3['garden_id'].'&account_concerned='.$row3['account_concerned'].'&report=1'); ?>"><?php echo $row3['garden_name']; ?> <?php echo ($row3['account_concerned']=='Yes') ? 'A/C' : ''; ?></a>
                <?php }else{ ?> 
                    <a target="blink" href="<?php echo site_url('MY_Controller/garden_sale_account_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row3['garden_id'].'&account_concerned='.$row3['account_concerned'].'&old_season='.$row3['old_season'].'&report=6&season_name=Old Season'); ?>"><?php echo $row3['garden_name']; ?> <?php echo ($row3['account_concerned']=='Yes') ? 'A/C' : ''; ?></a>
                <?php } ?>
            </td>
            <td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
            <td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
            <td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
            <td style="text-align:right; border:0px;"><?php echo number_format(($row3['total_amount'] / $row3['total_kgs']),2,'.',','); ?></td>
            <td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
            <td style="text-align:right; border:0px;">
               <?php echo number_format($row3['warehouse_charge'], 2, '.', ','); ?>
            </td>
            <td style="text-align:right; border:0px;"><?php echo number_format($row3['insp_sample'],2,'.',','); ?></td>
            <td style="text-align:right; border:0px;"><?php echo number_format($row3['brokerage_commission'],2,'.',','); ?></td>
            <td style="text-align:right; border:0px;">
               <?php echo number_format($row3['bank_charges'],2,'.',','); ?>
            </td>
            <td style="text-align:right; border:0px;">
               <?php echo number_format($row3['garden_adj_tk'],2,'.',','); ?>
            </td>
            <td style="text-align:right; border:0px;">
               <?php echo number_format($row3['truck_fare'],2,'.',','); ?>
            </td>
            <td style="text-align:right; border:0px;">
               <?php echo number_format($row3['garden_misc'],2,'.',','); ?>
            </td>
            <td style="text-align:right; border:0px;">
               <?php echo number_format($row3['add_wh_rent'],2,'.',','); ?>
            </td>
            <td style="text-align:right; border:0px;"><?php echo number_format($total_deduction,2,'.',','); ?></td>
            <td style="text-align:right; border:0px;"><?php echo number_format($total_tk,2,'.',','); ?></td>
        </tr>
        <?php

        // Accumulate totals
        $total_bags += $row3['total_bags'];
        $total_kgs += $row3['total_kgs'];
        $total_amount += $row3['total_amount'];
        $warehouse_charge += $row3['warehouse_charge'];
        $add_wh_rent += $row3['add_wh_rent'];
        $insp_sample += $row3['insp_sample'];
        $brokerage_commission += $row3['brokerage_commission'];
        $bank_charges += $row3['bank_charges'];
        $tea_cess += $row3['tea_cess'];
        $garden_misc += $row3['garden_misc'];
        $garden_adj_tk += $row3['garden_adj_tk'];
        $truck_fare += $row3['truck_fare'];
        $net_total_deduction += $total_deduction;
        $net_total_tk += $total_tk;

        // Increment row counter
        $row_counter++;

        // Close table and add page break if we've reached the limit
        if($row_counter == $row_limit_pp && $i < $total_records){
            ?>
            </tbody>
            </table>
            </div>
            </div>
            <div style="page-break-before: always;"></div>
            <?php
            // Reset row counter for next page
            $row_counter = 0;
        }

        $i++;
    } // end foreach loop 

    // Close the last table if there are any rows
    if($total_records > 0){
        ?>

				<!-- Render the TOTAL row safely inside the final table's layout structure -->
            <tr style="border-top: 2px solid black;">
                <th colspan="2" style="text-align:right; color:red;">TOTAL : </th>
                <th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"></th> <!-- Empty for Avg Sold Per KG -->
                <th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($warehouse_charge,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($insp_sample,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($brokerage_commission,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($bank_charges,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($garden_adj_tk,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($truck_fare,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($garden_misc,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($add_wh_rent,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($net_total_deduction,2,'.',','); ?></th>
                <th style="text-align:right; color:red;"><?php echo number_format($net_total_tk,2,'.',','); ?></th>
            </tr>
        </tbody>
        </table>
        </div>
        </div>
        <?php
    }

    // Display total row (outside the loop)
    ?>
   
    <?php

} else {
    print "<div id=\"page\"><h1 style=\"color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 19px;
    font-weight: normal;
    margin: 0 0 14px 0;
    padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
    <p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF sellers ?>

<?php if($report_name=='tea_cess_stmt'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4;
	margin-left: 100px;
}
</style>

<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 43;

## create temporary table - tea_cess_stmt
$this->db->query("DROP TABLE IF EXISTS `tea_cess_stmt`");

// create tea_cess_stmt @  buyer-1,2,3
$this->db->query("CREATE TABLE `tea_cess_stmt` (
SELECT 
    invoice_entry.season,
    invoice_entry.old_season,
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.garden_id,    
    gn.garden_name,
    
    @total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
    
    @total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
    
    @total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
    
    ((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess    

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();
?>

<?php
$sl = 1;
$new_season_bags = 0;
$new_season_kgs = 0;
$new_season_amount = 0;
$new_season_tea_cess = 0;
$old_season_bags = 0;
$old_season_kgs = 0;
$old_season_amount = 0;
$old_season_tea_cess = 0;
// query
$this->db->select("
    subquery.old_season, 
    subquery.garden_id, 
    IF(
        subquery.season = subquery.old_season, 
        subquery.garden_name, 
        CONCAT(subquery.garden_name, ' (', subquery.old_season, ')')
    ) AS garden_name, 
    SUM(subquery.total_bags) AS total_bags, 
    SUM(subquery.total_kgs) AS total_kgs, 
    SUM(subquery.total_amount) AS total_amount, 
    SUM(subquery.tea_cess) AS tea_cess,
    SUM(CASE WHEN subquery.season = subquery.old_season THEN subquery.total_bags ELSE 0 END) AS new_season_total_bags,
    SUM(CASE WHEN subquery.season = subquery.old_season THEN subquery.total_kgs ELSE 0 END) AS new_season_total_kgs,
    SUM(CASE WHEN subquery.season = subquery.old_season THEN subquery.total_amount ELSE 0 END) AS new_season_total_amount,
    SUM(CASE WHEN subquery.season = subquery.old_season THEN subquery.tea_cess ELSE 0 END) AS new_season_total_tea_cess,
    SUM(CASE WHEN subquery.season != subquery.old_season THEN subquery.total_bags ELSE 0 END) AS old_season_total_bags,
    SUM(CASE WHEN subquery.season != subquery.old_season THEN subquery.total_kgs ELSE 0 END) AS old_season_total_kgs,
    SUM(CASE WHEN subquery.season != subquery.old_season THEN subquery.total_amount ELSE 0 END) AS old_season_total_amount,
    SUM(CASE WHEN subquery.season != subquery.old_season THEN subquery.tea_cess ELSE 0 END) AS old_season_total_tea_cess,
    subquery.has_old_season,
    subquery.season
");
$this->db->from("(SELECT 
                    tea_cess_stmt.old_season, 
                    tea_cess_stmt.garden_id, 
                    tea_cess_stmt.garden_name, 
                    tea_cess_stmt.season,
                    tea_cess_stmt.total_bags, 
                    tea_cess_stmt.total_kgs, 
                    tea_cess_stmt.total_amount, 
                    tea_cess_stmt.tea_cess,
                    CASE 
                        WHEN tea_cess_stmt.season = tea_cess_stmt.old_season THEN 0 
                        ELSE 1 
                    END AS has_old_season
                 FROM tea_cess_stmt
                ) AS subquery");

// Join garden adjustment cost
$this->db->join(
    "(SELECT old_season, garden_id 
      FROM `garden_adjust_tbl` 
      WHERE `season`='" . $season . "' 
      AND `auction_center`='" . $auction_center . "' 
      AND `sale_no`='" . $sale_no . "' 
      GROUP BY `garden_id`, `old_season`
    ) AS gat", 
    "subquery.garden_id = gat.garden_id AND subquery.old_season = gat.old_season", 
    "left"
);

$this->db->group_by('subquery.garden_id, subquery.old_season');
$this->db->order_by('subquery.has_old_season ASC, subquery.garden_name ASC');

$query = $this->db->get();
$query3 = $query->result_array();

if(count($query3) > 0){
    // Group records by season type
    $new_season_records = array();
    $old_season_records = array();
    
    foreach($query3 as $record) {
        if($record['season'] == $record['old_season']) {
            $new_season_records[] = $record;
        } else {
            $old_season_records[] = $record;
        }
    }
    
    // Combine records with new season first, then old season
    $grouped_records = array_merge($new_season_records, $old_season_records);
    $total_records = count($grouped_records);
    
    // manipulate row section
    $row_start = 1;
    $row_end = $row_limit_pp;
    $row_col = 1;
    $pp_limit = $row_end * $row_col;
    $upper_limit = $row_start;
    $lower_limit = $row_end;
    $i = 1;
    $spp_limit = 1;
    $page = 0;
    
    // Reset totals
    $new_season_bags = 0;
    $new_season_kgs = 0;
    $new_season_amount = 0;
    $new_season_tea_cess = 0;
    $old_season_bags = 0;
    $old_season_kgs = 0;
    $old_season_amount = 0;
    $old_season_tea_cess = 0;
    
    // get valuation query
    foreach($grouped_records as $row3){
        if($i==$spp_limit){    
            $epp_limit = $spp_limit + ($pp_limit - 1);
            $page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading    
if($i==$upper_limit){
    $lower_limit = $upper_limit + ($row_end - 1);    
?>  
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="6" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;"><?php print $website_title; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
                Tea Cess Statement for sale no &nbsp; <?php print $sale_no; ?> &nbsp; <?php print $auction_center; ?> &nbsp; Season &nbsp; <?php print $season; ?> &nbsp; Held on <?php print date('d-M-Y' , strtotime($result_prompt_date['sale_date'])); ?><br>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
        <th>SL</th>
        <th>GARDEN NAME</th>
        <th>PKGS</th>
        <th>KGS</th>
        <th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
        <th style="white-space:nowrap;">Tea Cess<br /> (1%)</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>

<?php 
// Track when we switch from new season to old season records
if(!empty($new_season_records) && $i == count($new_season_records) + 1) { 
    // Show new season subtotal before showing old season records
?>
<tr>
    <th colspan="2" style="text-align:right; color:green;">NEW SEASON SUBTOTAL : </th>
    <th style="text-align:center; color:red;"><?php echo $new_season_bags; ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($new_season_kgs,2,'.',''); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($new_season_amount,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($new_season_tea_cess,2,'.',','); ?></th>
</tr>
<tr>
    <td colspan="6" style="text-align:center; font-weight:bold; border:0px;">OLD SEASON RECORDS</td>
</tr>
<?php } ?>

<tr>
    <td style="text-align:left; text-transform:uppercase; border:0px;">
        <?php print $sl++ ; ?>
    </td>
    <td style="text-align:left; text-transform:uppercase; border:0px; white-space:nowrap;">
        <?php echo $row3['garden_name']; ?>
    </td>
    <td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
</tr>

<?php
// Update totals based on season type
if($row3['season'] == $row3['old_season']) {
    $new_season_bags += $row3['total_bags'];
    $new_season_kgs += $row3['total_kgs'];
    $new_season_amount += $row3['total_amount'];
    $new_season_tea_cess += $row3['tea_cess'];
} else {
    $old_season_bags += $row3['total_bags'];
    $old_season_kgs += $row3['total_kgs'];
    $old_season_amount += $row3['total_amount'];
    $old_season_tea_cess += $row3['tea_cess'];
}

// limit of close table / div
if($i==$lower_limit || $i==$total_records) { 
    $upper_limit = $lower_limit + $row_start;
?>
<tr>
    <th colspan="2" style="text-align:right; color:green;">NEW SEASON TOTAL : </th>
    <th style="text-align:center; color:red;"><?php echo $new_season_bags; ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($new_season_kgs,2,'.',''); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($new_season_amount,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($new_season_tea_cess,2,'.',','); ?></th>
</tr>
<tr>
    <th colspan="2" style="text-align:right; color:green;">OLD SEASON TOTAL : </th>
    <th style="text-align:center; color:red;"><?php echo $old_season_bags; ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($old_season_kgs,2,'.',''); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($old_season_amount,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($old_season_tea_cess,2,'.',','); ?></th>
</tr>
<tr>
    <th colspan="2" style="text-align:right; color:blue;">GRAND TOTAL : </th>
    <th style="text-align:center; color:blue;"><?php echo ($new_season_bags + $old_season_bags); ?></th>
    <th style="text-align:right; color:blue;"><?php echo number_format(($new_season_kgs + $old_season_kgs),2,'.',''); ?></th>
    <th style="text-align:right; color:blue;"><?php echo number_format(($new_season_amount + $old_season_amount),2,'.',','); ?></th>
    <th style="text-align:right; color:blue;"><?php echo number_format(($new_season_tea_cess + $old_season_tea_cess),2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records) { ?>
</div> <!-- /row -->
<?php
    $spp_limit = $epp_limit + 1;
}
$i++;
} // end foreach loop 
} else {
    print "<div id=\"page\"><h1 style=\"color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 19px;
    font-weight: normal;
    margin: 0 0 14px 0;
    padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
    <p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>
<?php } // IF Tea Cess STMT single ?>


<?php if($report_name=='sale_buyer_list'){ ?>
<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - sale_buyer_list
$this->db->query("DROP TABLE IF EXISTS `sale_buyer_list`");

// create sale_buyer_list @  buyer-1
$this->db->query("CREATE TABLE `sale_buyer_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.short_key_code AS buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create sale_buyer_list @  buyer-2
$this->db->query("INSERT INTO `sale_buyer_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.short_key_code AS buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create sale_buyer_list @  buyer-3
$this->db->query("INSERT INTO `sale_buyer_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.short_key_code AS buyer_name,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Sale details
$query = $this->db->query("SELECT date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$prompt_date = $query->row_array();
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="12" style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	SALE BUYER LIST <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th colspan="12" style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sale Date: <span id="sale_date"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Prompt Date: <?php print $prompt_date['buyer_prompt_date']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> 
	</th>
</tr>
</thead>
</table>

<div style="width:950px; height:700px;">
<table class="table table-sm" id="table" style="width:150px !important; margin-left:10px; float:left;">
<thead>
<tr>
    <th style="text-align:left; font-size:13px !important; border:1px solid;">SL</th>
    <th style="text-align:left; font-size:13px !important; border:1px solid;">NAME</th>
	<th style="text-align:right; font-size:13px !important; border:1px solid;">BAGS</th>
</tr>
</thead>
<tbody>
<?php
// query
$sale_date = '';
$sl = 1;

// query
$this->db->select("sale_date, buyer_name, sum(leaf_bag) AS leaf_bag, sum(dust_bag) AS dust_bag "); 	
$this->db->from('sale_buyer_list');
$this->db->group_by('buyer_id');
$this->db->order_by('buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
	<td style="text-align:left; text-transform:uppercase; font-size:13px !important; border:0px;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase; font-size:13px !important; border:0px;"><?php echo $row3['buyer_name']; ?></td>
	<td style="text-align:right; font-size:13px !important; border:0px;"><?php echo $total_bag = $row3['leaf_bag'] + $row3['dust_bag']; ?></td>
</tr>
<?php
	$sale_date = date("d-m-Y",strtotime($row3['sale_date']));
}  // 1st loop
?>
</tbody>
</table>

</div>
</div> <!-- col -->
</div> <!-- row -->

<script>
$("#sale_date").html("<?php print $sale_date; ?>");
</script>
<?php } // IF sale buyer list ?>



<?php if($report_name=='sales_released_list'){ ?>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - sales_released_list
$this->db->query("DROP TABLE IF EXISTS `sales_released_list`");

// create sales_released_list @  buyer-1
$this->db->query("CREATE TABLE `sales_released_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.short_key_code AS buyer_name,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create sales_released_list @  buyer-2
$this->db->query("INSERT INTO `sales_released_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.short_key_code AS buyer_name,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create sales_released_list @  buyer-3
$this->db->query("INSERT INTO `sales_released_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.short_key_code AS buyer_name,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Sale details
$query = $this->db->query("SELECT date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$prompt_date = $query->row_array();
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="12" style="text-transform: uppercase; text-align:center; border:0px;">
		<span style="font-size:16px;"><?php print $website_title; ?></span><br />
		<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span><br />
		<span style="font-size:14px;">sale released list <br /> <?php print $auction_center; ?></span>
	</th>
</tr>
<tr>
	<th colspan="12" style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sale Date: <span id="sale_date"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Prompt Date: <?php print $prompt_date['buyer_prompt_date']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> 
	</th>
</tr>
</thead>
</table>

<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:left; border:1px solid;">LOT</th>
    <th style="text-align:center; border:1px solid;">PRICE</th>
	<th style="text-align:left; border:1px solid;">BUYER</th>
	<th style="text-align:right; border:1px solid;">BAG</th>
	<th style="text-align:right; border:1px solid;">KGS</th>
	<th style="text-align:right; border:1px solid;">AMOUNT</th>
</tr>
</thead>
<tbody>
<?php
$sale_date = '';
$sl = 1;
// query
$this->db->select("*"); 	
$this->db->from('sales_released_list');
$this->db->order_by('lot_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; border:0px;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; border:0px;"><?php echo number_format($row3['sold_price'],2,'.',''); ?></td>
	<td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row3['buyer_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['buyer_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['buyer_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['buyer_amount'],2,'.',','); ?></td>
</tr>
<?php
	$sale_date = date("d-m-Y",strtotime($row3['sale_date']));
} // 1st loop
?>
</tbody>
</table>
</div>
</div> 
<!-- /row -->

<script>
$("#sale_date").html("<?php print $sale_date; ?>");
</script>
<?php } // IF sales released list ?>



<?php if($report_name=='TTAB_BP_list'){ ?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
## create temporary table - ttab_buyer_purchase
$this->db->query("DROP TABLE IF EXISTS `ttab_buyer_purchase`");

// create ttab_buyer_purchase @  buyer-1
$this->db->query("CREATE TABLE `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer1_bag END,0)
	) AS current_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer1_bag END,0)
	) AS current_dust_bag,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END,0)
	) AS upto_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END,0)
	) AS upto_dust_bag

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '1' AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create ttab_buyer_purchase @  buyer-2
$this->db->query("INSERT INTO `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,
	sale_entry.buyer_type2 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer2_bag END,0)
	) AS current_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer2_bag END,0)
	) AS current_dust_bag,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END,0)
	) AS upto_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END,0)
	) AS upto_dust_bag

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '1' AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create ttab_buyer_purchase @  buyer-3
$this->db->query("INSERT INTO `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer3_bag END,0)
	) AS current_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' THEN sale_entry.buyer3_bag END,0)
	) AS current_dust_bag,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END,0)
	) AS upto_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END,0)
	) AS upto_dust_bag

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '1' AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<tr>
	<th colspan="8" style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th colspan="8" style="text-align:left; font-size:14px !important; border:0px;"> 
	The Secretary<br />
	Tea Traders Association of Bangladesh<br />
	Chittagong.
	</th>
</tr>
<tr>
	<th colspan="8" style="text-align:center; font-size:14px !important; border:0px;">BUYER PURCHASE LIST</th>
</tr>
<tr>
	<th style="text-align:left; border:0px;">
		Dear Sir, <br /> We give below the purchase made by the following buyers in our catalogue upto sale No. <b><?php print $sale_no; ?> </b> &nbsp; Season: <?php print $season; ?>
	</th>
</tr>
</table>

<table class="table table-sm" id="table">
<thead>
<tr>
    <th rowspan="2" style="text-align:left; white-space:nowrap; border:1px solid;">BUYER TYPE</th>
    <th rowspan="2" style="text-align:left; white-space:nowrap; border:1px solid;">BUYER NAME</th>
	<th colspan="2" style="text-align:center; white-space:nowrap; border:1px solid;">CURRENT BAGS</th>
	<th colspan="2" style="text-align:center; white-space:nowrap; border:1px solid;">UPTO DATE BAGS</th>
</tr>
<tr>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">LEAF</th>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">DUST</th>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">LEAF</th>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">DUST</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;
$current_leaf_bag = 0;
$current_dust_bag = 0;
$upto_leaf_bag = 0;
$upto_dust_bag = 0;

// INTERNAL BUYER	
$this->db->select("ttab_bp.buyer_id, buyer_setup.buyer_name, ttab_bp.buyer_type, sum(ttab_bp.current_leaf_bag) AS current_leaf_bag, sum(ttab_bp.current_dust_bag) AS current_dust_bag, sum(ttab_bp.upto_leaf_bag) AS upto_leaf_bag, sum(ttab_bp.upto_dust_bag) AS upto_dust_bag");
$this->db->from('ttab_buyer_purchase ttab_bp');
$this->db->join('buyer_setup','ttab_bp.buyer_id = buyer_setup.id');
$this->db->where('ttab_bp.buyer_type','Internal');
$this->db->group_by('ttab_bp.buyer_id');
$this->db->order_by('buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	$count = $sl++;
?>

<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase; font-weight:bold;"><?php echo ($count==1) ? $row3['buyer_type'] : ''; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:1px solid; font-weight:bold;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_no.'&sale_to='.$sale_no.'&buyer_id='.$row3['buyer_id'].'&report=9'); ?>"><?php echo $row3['buyer_name']; ?></a>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_dust_bag']; ?></td>
	
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_dust_bag']; ?></td>
</tr>	

<?php
	$current_leaf_bag += $row3['current_leaf_bag'];
	$current_dust_bag += $row3['current_dust_bag'];
	$upto_leaf_bag += $row3['upto_leaf_bag'];
	$upto_dust_bag += $row3['upto_dust_bag'];

} // foreach
?>
<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:1px solid; color:red;">INTERNAL TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_leaf_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_dust_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_leaf_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_dust_bag; ?></th>
</tr>


<?php
$sl2=1;
$current_leaf_bag2 = 0;
$current_dust_bag2 = 0;
$upto_leaf_bag2 = 0;
$upto_dust_bag2 = 0;

// EXPORT BUYER		
$this->db->select("ttab_bp.buyer_id, buyer_setup.buyer_name, ttab_bp.buyer_type, sum(ttab_bp.current_leaf_bag) AS current_leaf_bag, sum(ttab_bp.current_dust_bag) AS current_dust_bag, sum(ttab_bp.upto_leaf_bag) AS upto_leaf_bag, sum(ttab_bp.upto_dust_bag) AS upto_dust_bag");
$this->db->from('ttab_buyer_purchase ttab_bp');
$this->db->join('buyer_setup','ttab_bp.buyer_id = buyer_setup.id');
$this->db->where('ttab_bp.buyer_type','Export');
$this->db->group_by('ttab_bp.buyer_id');
$this->db->order_by('buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	$count2 = $sl2++;
?>

<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase; font-weight:bold;"><?php echo ($count2==1) ? $row3['buyer_type'] : ''; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:1px solid; font-weight:bold;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_no.'&sale_to='.$sale_no.'&buyer_id='.$row3['buyer_id'].'&report=9'); ?>"><?php echo $row3['buyer_name']; ?></a>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_dust_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_dust_bag']; ?></td>
</tr>	

<?php
	$current_leaf_bag2 += $row3['current_leaf_bag'];
	$current_dust_bag2 += $row3['current_dust_bag'];
	$upto_leaf_bag2 += $row3['upto_leaf_bag'];
	$upto_dust_bag2 += $row3['upto_dust_bag'];

} // foreach


if(($upto_leaf_bag2 + $upto_dust_bag2) > 0){
?>
<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:1px solid; color:red;">EXPORT TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_dust_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_dust_bag2; ?></th>
</tr>
<?php } ?>

<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:1px solid; color:red;">GRAND TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_leaf_bag + $current_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_dust_bag + $current_dust_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_leaf_bag + $upto_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_dust_bag + $upto_dust_bag2; ?></th>
</tr>
</tbody>
</table>

<i>@ Jamuna Tech</i>

<br />
<br />
<br />

<div class="row">
	<div class="col-sm-7 text-primary">
	</div>
	<div class="col-sm-5 text-primary text-center">
		<div class="text-left"> 
			<b style="text-align:center; font-size:14px;">&nbsp; &nbsp; &nbsp; Yours faithfully</b> <br /> For <b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b>
		</div>
	</div>
</div>
<!-- /row -->

<br />

</div><!-- /col -->
</div>
<?php } ?>

<?php if($report_name=='TTAB_BP_upto_list'){ ?>
<style>
#table tr td, #table2 tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th, #table2 tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

@page{
	size: legal Portrait;
	margin-bottom: 100px;
}
</style>

<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>
<?php 
// page row count
$row_limit_pp = 40;

## create temporary table - ttab_buyer_purchase
$this->db->query("DROP TABLE IF EXISTS `ttab_buyer_purchase`");

// create ttab_buyer_purchase @  buyer-1
$this->db->query("CREATE TABLE `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0)
	) AS current_leaf_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_total_kgs END,0)
	) AS current_leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0)
	) AS current_dust_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_total_kgs END,0)
	) AS current_dust_kgs,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END,0)
	) AS upto_leaf_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END,0)
	) AS upto_leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END,0)
	) AS upto_dust_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END,0)
	) AS upto_dust_kgs

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '1' AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


// create ttab_buyer_purchase @  buyer-2
$this->db->query("INSERT INTO `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,
	sale_entry.buyer_type2 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0)
	) AS current_leaf_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_total_kgs END,0)
	) AS current_leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0)
	) AS current_dust_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_total_kgs END,0)
	) AS current_dust_kgs,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END,0)
	) AS upto_leaf_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END,0)
	) AS upto_leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END,0)
	) AS upto_dust_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END,0)
	) AS upto_dust_kgs

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '1' AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


// create ttab_buyer_purchase @  buyer-3
$this->db->query("INSERT INTO `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0)
	) AS current_leaf_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_total_kgs END,0)
	) AS current_leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0)
	) AS current_dust_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_total_kgs END,0)
	) AS current_dust_kgs,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END,0)
	) AS upto_leaf_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END,0)
	) AS upto_leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END,0)
	) AS upto_dust_bag,

	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END,0)
	) AS upto_dust_kgs

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '1' AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<?php
// query
$sl=1;
$current_leaf_bag = 0;
$current_leaf_kgs = 0;
$current_dust_bag = 0;
$current_dust_kgs = 0;
$upto_leaf_bag = 0;
$upto_leaf_kgs = 0;
$upto_dust_bag = 0;
$upto_dust_kgs = 0;

$this->db->select("ttab_bp.buyer_id, buyer_setup.buyer_name, ttab_bp.buyer_type, sum(ttab_bp.current_leaf_bag) AS current_leaf_bag, sum(ttab_bp.current_leaf_kgs) AS current_leaf_kgs, sum(ttab_bp.current_dust_bag) AS current_dust_bag, sum(ttab_bp.current_dust_kgs) AS current_dust_kgs, sum(ttab_bp.upto_leaf_bag) AS upto_leaf_bag, sum(ttab_bp.upto_leaf_kgs) AS upto_leaf_kgs, sum(ttab_bp.upto_dust_bag) AS upto_dust_bag, sum(ttab_bp.upto_dust_kgs) AS upto_dust_kgs, (CASE WHEN ttab_bp.buyer_type='Internal' THEN 'A' WHEN ttab_bp.buyer_type='Export' THEN 'B' END) AS buyer_type_ac");
$this->db->from('ttab_buyer_purchase ttab_bp');
$this->db->join('buyer_setup','ttab_bp.buyer_id = buyer_setup.id');
$this->db->group_by('ttab_bp.buyer_id, ttab_bp.buyer_type');
$this->db->order_by('buyer_type_ac ASC, buyer_setup.buyer_name ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
	<tr>
		<td style="text-align:left; border:0px; font-family:courier;" colspan="10">
			<b style="font-size:17px; text-transform: uppercase;"><?php print $website_title; ?></b><br>
			<span style="font-size:14px; text-transform: capitalize;">Agrabad C/A, Chittagong</span>
		</td>
		<td style="text-align:right; !important; border:0px;" colspan="1">
			<span style="font-size:13px;">Page <?php print $page; ?></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:left; !important; border:0px; font-family:courier;" colspan="11"> 
			<b style="font-size:15px">To: Secretary, Bangladesh Tea Board, Chittagong.</b><br>
			<span style="margin-left:28px; font-size:14px ">171/172 Baizid Bostami Road Nasirabad, Chittagong</span><br><br>
			<span style="margin-left:28px; font-size:14px ">Ref : <u>BUYERS PURCHASE STATEMENT, SALE NO.<?php print $sale_no; ?> SEASON <?php print $season; ?></u></span>
		</td>
	</tr>
	<tr>
		<th rowspan="3" style="text-align:left; border:1px solid; vertical-align: middle;">BUYER NAME</th>
		<th colspan="6" style="text-align:center; border:1px solid;">CURRENT BAGS</th>
		<th colspan="4" style="text-align:center; border:1px solid;">UPTO DATE BAGS</th>
	</tr>
	<tr>
		<th style="text-align:center; border:1px solid;" colspan="2">LEAF</th>
		<th style="text-align:center; border:1px solid;" colspan="2">DUST</th>
		<th style="text-align:center; border:1px solid;" colspan="2">Total</th>
		<th style="text-align:center; border:1px solid;" colspan="4">Total</th>
	</tr>
	<tr>
		<th style="text-align:center; border:1px solid;">Pkgs.</th>
		<th style="text-align:center; border:1px solid;">Kg.</th>
		<th style="text-align:center; border:1px solid;">Pkgs.</th>
		<th style="text-align:center; border:1px solid;">Kg.</th>
		<th style="text-align:center; border:1px solid;">Pkgs.</th>
		<th style="text-align:center; border:1px solid;">Kg.</th>
		<th style="text-align:center; border:1px solid;">Pkgs.</th>
		<th style="text-align:center; border:1px solid;">Kg.</th>
		<th style="text-align:center; border:1px solid;">Rate</th>
		<th style="text-align:center; border:1px solid;">(%)</th>
	</tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_no.'&sale_to='.$sale_no.'&buyer_id='.$row3['buyer_id'].'&report=9'); ?>"><?php echo $row3['buyer_name']; ?> <?php echo ($row3['buyer_type']=='Export') ? '<b style="color:red;"> - '.$row3['buyer_type'].'</b>' : ''; ?></a>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['current_leaf_bag']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['current_leaf_kgs']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['current_dust_bag']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['current_dust_kgs']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo ($row3['current_leaf_bag'] + $row3['current_dust_bag']); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo ($row3['current_leaf_kgs'] + $row3['current_dust_kgs']); ?></td>
	<!-- up to date -->
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo ($row3['upto_leaf_bag'] + $row3['upto_dust_bag']); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo ($row3['upto_leaf_kgs'] + $row3['upto_dust_kgs']); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">0.00</td>
	<td style="text-align:right; border:0px; font-weight:bold;">0.00</td>
</tr>
<?php

$current_leaf_bag += $row3['current_leaf_bag'];
$current_leaf_kgs += $row3['current_leaf_kgs'];
$current_dust_bag += $row3['current_dust_bag'];
$current_dust_kgs += $row3['current_dust_kgs'];
$upto_leaf_bag += $row3['upto_leaf_bag'];
$upto_leaf_kgs += $row3['upto_leaf_kgs'];
$upto_dust_bag += $row3['upto_dust_bag'];
$upto_dust_kgs += $row3['upto_dust_kgs'];

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>
<?php
// post assign value 
$i++;
} // end foreach loop 
?>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm" id="table2">
			<tr>
				<th style="text-align:center; border:1px solid;">Sub Total : </th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_leaf_bag; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_leaf_kgs; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_dust_bag; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_dust_kgs; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo ($current_leaf_bag + $current_dust_bag); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo ($current_leaf_kgs + $current_dust_kgs); ?></th>
				<!-- up to date -->
				<th style="text-align:right; border:1px solid;"><?php echo ($upto_leaf_bag + $upto_dust_bag); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo ($upto_leaf_kgs + $upto_dust_kgs); ?></th>
				<th style="text-align:right; border:1px solid;">0.00</th>
				<th style="text-align:right; border:1px solid;">0.00</th>
			</tr>
			<!-- This row is only used for two row spacing -->
			<tr><td height="10" colspan="6"></td></tr>
			<!-- This row is only used for two row spacing -->
			<tr>
				<th style="text-align:center; border:1px solid;">Gross Total : </th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_leaf_bag; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_leaf_kgs; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_dust_bag; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo $current_dust_kgs; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo ($current_leaf_bag + $current_dust_bag); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo ($current_leaf_kgs + $current_dust_kgs); ?></th>
				<!-- up to date -->
				<th style="text-align:right; border:1px solid;"><?php echo ($upto_leaf_bag + $upto_dust_bag); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo ($upto_leaf_kgs + $upto_dust_kgs); ?></th>
				<th style="text-align:right; border:1px solid;">0.00</th>
				<th style="text-align:right; border:1px solid;">0.00</th>
			</tr>
		</table>
	</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-11" style="margin-top:10px; font-family:courier;">
		Sincerely Yours,
	</div>

	<div class="col-sm-1"></div>
	<div class="col-sm-11 text-left" style="margin-top:40px;">
		<b style="text-transform: uppercase; font-size:14px; font-family:courier;"> <?php print $website_title; ?> </b><br><br>
		<span style="font-size:14px; font-family:courier;"> CC: Deputy Director (Trade), Bangladesh Tea Board, Chittagong.</span>
	</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-11 text-left" style="margin-top:20px;">
		<i style="text-transform: uppercase; font-size:14px; font-weight:bold;"> Developed By @ Jamuna Tech </i>
	</div>
</div>
<?php
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>
<?php } // IF TTAB_BP_list ?>


<?php if($report_name=='sales_summary'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
@page{
	size:auto;
}
</style>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">	
<div class="col-sm-12">
<?php
## create temporary table - sales_summary
$this->db->query("DROP TABLE IF EXISTS `sales_summary`");

// create sales_summary @ buyer-1,2,3
$this->db->query("CREATE TABLE `sales_summary` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.supplement_tea AS catalogue_name,
	invoice_entry.chest_number AS offering_chest,
	invoice_entry.total_kgs AS gross_kgs,
	IF(invoice_entry.weight_short='y', IFNULL(invoice_entry.additional_sample_kgs,0), 0) AS shortage_kgs,
	invoice_entry.sample_kgs,
	invoice_entry.net_kgs,
	@sold_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS sold_bags,
	@sold_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	@sold_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
	IFNULL(invoice_entry.vat,0) AS vat_percent,
	((@sold_amount * IFNULL(invoice_entry.vat,0))/100) AS vat_amount,
	((@sold_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess,
	((@sold_kgs * IFNULL(invoice_entry.brokerage,0))/100) AS buyer_brokerage,
	((@sold_amount * IFNULL(invoice_entry.brokerage_commission,0))/100) AS seller_brokerage,
	(@sold_bags * IFNULL(invoice_entry.insp_sample,0)) AS insp_sample,
	(@sold_bags * IFNULL(invoice_entry.warehouse_charge,0)) AS warehouse_charge

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<table class="table" style="margin-bottom:5px;">
	<thead>
		<tr>
			<td style="text-transform: uppercase; text-align:center;">
				<div style="display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #33333373 !important;">
					<span>
						<img src="<?php echo base_url().'assets/img/ubl_logo.png'; ?>" class="img-fluid" style="height:61px; width:61px;" alt="ubl_logo">
					</span>
					<span style="padding: 0 5px;"></span>
					<span>
						<b style="font-size:15px; text-transform:uppercase;"><?php print $website_title; ?></b><br>
						<span style="font-size:13px; text-transform: capitalize;">Progressive tower (5th floor) <br> 1837, Sk. mujib road,  agrabad C/A, Chittagong</span>
					</span>
				</div>
			</td>
		</tr>
	</thead>
</table>

<?php
## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();

$current_date_time= date("Y-m-d h:i:sa");
?>

<table class="table" id="table" style="margin-bottom:5px;">
<thead>
<tr>
    <td colspan="3" style="text-align:center; border:0px; text-transform:uppercase;">
		<span style="border-bottom: 1px solid #33333373 !important;">
			Garden Wise Total Of Sale No <?php print $sale_no; ?> <?php print $auction_center; ?>
		</span>
	</td>
</tr>
<tr>
	<td style="text-transform:uppercase; text-align:left; border:0px;">Held On: <?php print $result_prompt_date['sale_date']; ?></td>
	<td style="text-transform:uppercase; text-align:center; border:0px;">Season: <?php print $season; ?></td>
	<td style="text-transform:uppercase; text-align:right; border:0px;">Print : <?php print date('M-d-y', strtotime($current_date_time)) ; ?> <?php print date('h:i', strtotime($current_date_time)) ; ?></td>
</tr>
<?php
// query
$this->db->select("
	sum(CASE WHEN catalogue_name='Leaf' THEN offering_chest END) AS offering_leaf_chest, 
	sum(CASE WHEN catalogue_name='Dust' THEN offering_chest END) AS offering_dust_chest,
	sum(offering_chest) AS offering_total_chest,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN gross_kgs END) AS gross_leaf_kgs, 
	sum(CASE WHEN catalogue_name='Dust' THEN gross_kgs END) AS gross_dust_kgs,
	sum(gross_kgs) AS gross_kgs,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN shortage_kgs END) AS shortage_leaf_kgs, 
	sum(CASE WHEN catalogue_name='Dust' THEN shortage_kgs END) AS shortage_dust_kgs,
	sum(shortage_kgs) AS shortage_total_kgs,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN sample_kgs END) AS sample_leaf_kgs, 
	sum(CASE WHEN catalogue_name='Dust' THEN sample_kgs END) AS sample_dust_kgs,
	sum(sample_kgs) AS sample_total_kgs,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN net_kgs END) AS actual_leaf_kgs, 
	sum(CASE WHEN catalogue_name='Dust' THEN net_kgs END) AS actual_dust_kgs,
	sum(net_kgs) AS actual_total_kgs,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN sold_bags END) AS sold_leaf_chest, 
	sum(CASE WHEN catalogue_name='Dust' THEN sold_bags END) AS sold_dust_chest,
	sum(sold_bags) AS total_sold_chest,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN sold_kgs END) AS sold_leaf_kgs, 
	sum(CASE WHEN catalogue_name='Dust' THEN sold_kgs END) AS sold_dust_kgs,
	sum(sold_kgs) AS total_sold_kgs,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN sold_amount END) AS sold_leaf_amount, 
	sum(CASE WHEN catalogue_name='Dust' THEN sold_amount END) AS sold_dust_amount,
	sum(sold_amount) AS total_sold_amount,
	
	vat_percent,
	sum(CASE WHEN catalogue_name='Leaf' THEN vat_amount END) AS vat_amount_leaf, 
	sum(CASE WHEN catalogue_name='Dust' THEN vat_amount END) AS vat_amount_dust,
	sum(vat_amount) AS total_vat_amount,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN tea_cess END) AS tea_cess_leaf, 
	sum(CASE WHEN catalogue_name='Dust' THEN tea_cess END) AS tea_cess_dust,
	sum(tea_cess) AS total_tea_cess,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN buyer_brokerage END) AS buyer_brokerage_leaf, 
	sum(CASE WHEN catalogue_name='Dust' THEN buyer_brokerage END) AS buyer_brokerage_dust,
	sum(buyer_brokerage) AS total_buyer_brokerage,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN seller_brokerage END) AS seller_brokerage_leaf, 
	sum(CASE WHEN catalogue_name='Dust' THEN seller_brokerage END) AS seller_brokerage_dust,
	sum(seller_brokerage) AS total_seller_brokerage,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN insp_sample END) AS inspection_leaf, 
	sum(CASE WHEN catalogue_name='Dust' THEN insp_sample END) AS inspection_dust,
	sum(insp_sample) AS total_inspection
"); 	
$this->db->from('sales_summary');
$sql = $this->db->get();
$result_date = $sql->row_array();

$avg_price_net = ($result_date['total_sold_amount'] > 0 && $result_date['total_sold_kgs'] > 0) ? $result_date['total_sold_amount'] / $result_date['total_sold_kgs'] : '0.00';
?>

<tr style="border:1px solid;">
	<td style="text-align:right; width:30%;">
		Grand Total :
	</td>
	<td colspan="2" style="text-align:left; width:70%;">
		<span style="padding-left:100px;"><?php echo $result_date['total_sold_chest']; ?></span>
		<span style="padding-left:30px;"><?php echo number_format($result_date['total_sold_kgs'],2); ?></span>
		<span style="padding-left:30px;"><?php echo number_format($result_date['total_sold_amount'],2); ?></span>
		<span style="padding-left:30px;"><?php echo number_format($avg_price_net,2); ?></span>
	</td>
</tr>

<tr>
	<td colspan="3" style="border:0px; text-align:center; text-transform:uppercase;">
		<span style="border-bottom: 1px solid #33333373 !important;">Summary Of The Whole Sale</span>
	</td>
</tr>
</thead>
</table>

<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:1px solid;">PARTICULAR</th>
	<th style="text-align:center; border:1px solid;">LEAF</th>
	<th style="text-align:center; border:1px solid;">DUST</th>
	<th style="text-align:center; border:1px solid;">TOTAL</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;"> Total Bags :</td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['offering_leaf_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['offering_dust_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['offering_total_chest']; ?></td>
</tr>
<tr>	
	<td style="text-align:right; text-transform:capitalize; border:0px;">Gross Kg. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['gross_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['gross_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['gross_kgs'],2); ?></td>
</tr>
<tr>	
	<td style="text-align:right; text-transform:capitalize; border:0px;">Shortage :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['shortage_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['shortage_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['shortage_total_kgs'],2); ?></td>
</tr>
<?php
$net_leaf_kgs = $result_date['gross_leaf_kgs'] - $result_date['shortage_leaf_kgs'];
$net_dust_kgs = $result_date['gross_dust_kgs'] - $result_date['shortage_dust_kgs'];
$net_kgs = $result_date['gross_kgs'] - $result_date['shortage_total_kgs'];
?>
<tr>	
	<td style="text-align:right; text-transform:capitalize; border:0px;">Net Kg. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($net_leaf_kgs,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($net_dust_kgs,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2); ?></td>
</tr>
<tr>	
	<td style="text-align:right; text-transform:capitalize; border:0px;">Total Sample :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sample_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sample_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sample_total_kgs'],2); ?></td>
</tr>
<tr>	
	<td style="text-align:right; text-transform:capitalize; border:0px;">Actual Net :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['actual_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['actual_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['actual_total_kgs'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Sold Bags :</td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['sold_leaf_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['sold_dust_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['total_sold_chest']; ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Sold Kg. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_sold_kgs'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Amount Tk. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_leaf_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_dust_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_sold_amount'],2); ?></td>
</tr>
<?php
$avg_price_leaf = ($result_date['sold_leaf_amount'] > 0 && $result_date['sold_leaf_kgs'] > 0) ? $result_date['sold_leaf_amount'] / $result_date['sold_leaf_kgs'] : '0.00';
$avg_price_dust = ($result_date['sold_dust_amount'] > 0 && $result_date['sold_dust_kgs'] > 0) ? $result_date['sold_dust_amount'] / $result_date['sold_dust_kgs'] : '0.00';
?>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Avg. price/kg. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_leaf,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_dust,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_net,2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">VAT (<?php print $result_date['vat_percent']; ?>%) :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['vat_amount_leaf'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['vat_amount_dust'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_vat_amount'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Tea Cess :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['tea_cess_leaf'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['tea_cess_dust'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_tea_cess'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Buy Brokerage :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['buyer_brokerage_leaf'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['buyer_brokerage_dust'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_buyer_brokerage'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Sell Brokerage :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['seller_brokerage_leaf'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['seller_brokerage_dust'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_seller_brokerage'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Inspection :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['inspection_leaf'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['inspection_dust'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_inspection'],2); ?></td>
</tr>
<?php
$unsold_leaf_chest = $result_date['offering_leaf_chest'] - $result_date['sold_leaf_chest'];
$unsold_dust_chest = $result_date['offering_dust_chest'] - $result_date['sold_dust_chest'];
$total_unsold_chest = $result_date['offering_total_chest'] - $result_date['total_sold_chest'];
?>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Unsold Bags :</td>
	<td style="text-align:right; border:0px;"><?php echo $unsold_leaf_chest; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $unsold_dust_chest; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $total_unsold_chest; ?></td>
</tr>
<?php
$unsold_leaf_kgs = $result_date['actual_leaf_kgs'] - $result_date['sold_leaf_kgs'];
$unsold_dust_kgs = $result_date['actual_dust_kgs'] - $result_date['sold_dust_kgs'];
$total_unsold_kgs = $result_date['actual_total_kgs'] - $result_date['total_sold_kgs'];
?>
<tr>
	<td style="text-align:right; text-transform:capitalize; border-top:0px; border-bottom:1px solid;">Unsold Kg. :</td>
	<td style="text-align:right; border-top:0px; border-bottom:1px solid;"><?php echo number_format($unsold_leaf_kgs,2); ?></td>
	<td style="text-align:right; border-top:0px; border-bottom:1px solid;"><?php echo number_format($unsold_dust_kgs,2); ?></td>
	<td style="text-align:right; border-top:0px; border-bottom:1px solid;"><?php echo number_format($total_unsold_kgs,2); ?></td>
</tr>

<?php
$leaf_sold_percent = ($result_date['actual_leaf_kgs'] > 0 && $result_date['sold_leaf_kgs'] > 0) ? (($result_date['sold_leaf_kgs']/$result_date['actual_leaf_kgs'])*100) : '0.00';
$dust_sold_percent = ($result_date['actual_dust_kgs'] > 0 && $result_date['sold_dust_kgs'] > 0) ? (($result_date['sold_dust_kgs']/$result_date['actual_dust_kgs'])*100) : '0.00';
$total_sold_percent = ($result_date['actual_total_kgs'] > 0 && $result_date['total_sold_kgs'] > 0) ? (($result_date['total_sold_kgs']/$result_date['actual_total_kgs'])*100) : '0.00';

$leaf_unsold_percent = ($unsold_leaf_kgs > 0 && $result_date['actual_leaf_kgs'] > 0) ? (($unsold_leaf_kgs/$result_date['actual_leaf_kgs'])*100) : '0.00';
$dust_unsold_percent = ($unsold_dust_kgs > 0 && $result_date['actual_dust_kgs'] > 0) ? (($unsold_dust_kgs/$result_date['actual_dust_kgs'])*100) : '0.00';
$total_unsold_percent = ($total_unsold_kgs > 0 && $result_date['actual_total_kgs'] > 0) ? (($total_unsold_kgs/$result_date['actual_total_kgs'])*100) : '0.00';
?>

<tr>
	<th style="text-align:right; text-transform:capitalize; border-top:0px; border-bottom:0px;">Percentage of sold :</th>
	<th style="text-align:right; border-top:0px; border-bottom:1px solid;">Leaf&nbsp;&nbsp;<?php echo number_format($leaf_sold_percent,2); ?> %</th>
	<th style="text-align:right; border-top:0px; border-bottom:1px solid;">Dust&nbsp;&nbsp;<?php echo number_format($dust_sold_percent,2); ?> %</th>
	<th style="text-align:right; border-top:0px; border-bottom:1px solid;"><?php echo number_format($total_sold_percent,2); ?> %</th>
</tr>

<tr>
	<th style="text-align:right; text-transform:capitalize; border-top:0px; border-bottom:0px;">Percentage of Unsold :</th>
	<th style="text-align:right; border-top:0px; border-bottom:1px solid;">Leaf&nbsp;&nbsp;<?php echo number_format($leaf_unsold_percent,2); ?> %</th>
	<th style="text-align:right; border-top:0px; border-bottom:1px solid;">Dust&nbsp;&nbsp;<?php echo number_format($dust_unsold_percent,2); ?> %</th>
	<th style="text-align:right; border-top:0px; border-bottom:1px solid;"><?php echo number_format($total_unsold_percent,2); ?> %</th>
</tr>
</tbody>
</table>
</div> <!-- col -->
</div> <!-- row -->
<?php } // summary of the whole sale ?>

<?php if($report_name=='sale_average'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
@page{
	size:auto;
	margin-left: 100px;
	margin-right: 100px;
}
</style>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">	
<div class="col-sm-12">
<?php
## create temporary table - sale_average
$this->db->query("DROP TABLE IF EXISTS `sale_average`");

// create sale_average @ buyer-1
$this->db->query("CREATE TABLE `sale_average` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,
	garden_and_company_setup.garden_name,
	invoice_entry.account_concerned,
	invoice_entry.category,
	invoice_entry.supplement_tea AS catalogue_name,
	IFNULL(sale_entry.buyer1_bag,0) AS sold_bags,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS sold_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS sold_amount,
	sale_entry.buyer_type1 AS buyer_type

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// insert sale_average @ buyer-2
$this->db->query("INSERT INTO `sale_average` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,
	garden_and_company_setup.garden_name,
	invoice_entry.account_concerned,
	invoice_entry.category,
	invoice_entry.supplement_tea AS catalogue_name,
	IFNULL(sale_entry.buyer2_bag,0) AS sold_bags,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS sold_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS sold_amount,
	sale_entry.buyer_type2

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// insert sale_average @ buyer-3
$this->db->query("INSERT INTO `sale_average` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,
	garden_and_company_setup.garden_name,
	invoice_entry.account_concerned,
	invoice_entry.category,
	invoice_entry.supplement_tea AS catalogue_name,
	IFNULL(sale_entry.buyer3_bag,0) AS sold_bags,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS sold_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS sold_amount,
	sale_entry.buyer_type3

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>	

<table class="table" style="margin-bottom:5px;">
	<thead>
		<tr>
			<td style="text-transform: uppercase; text-align:center;">
				<div style="display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #33333373 !important;">
					<span>
						<img src="<?php echo base_url().'assets/img/ubl_logo.png'; ?>" class="img-fluid" style="height:61px; width:61px;" alt="ubl_logo">
					</span>
					<span style="padding: 0 5px;"></span>
					<span>
						<b style="font-size:15px; text-transform:uppercase;"><?php print $website_title; ?></b><br>
						<span style="font-size:13px; text-transform: capitalize;">Progressive tower (5th floor) <br> 1837, Sk. mujib road,  agrabad C/A, Chittagong</span>
					</span>
				</div>
			</td>
		</tr>
	</thead>
</table>

<?php
## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%M-%d-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();

$current_date_time= date("Y-m-d h:i:sa");
?>

<table class="table" id="table" style="margin-bottom:5px;">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="2">
		<span style="font-size:13px;">
			UBL/ <?php print $sale_no ; ?> / <?php print $auction_center ; ?> / <?php print $season ; ?>
		</span>
	</td>
	<td style="text-align:right; border:0px;">
		<span style="font-size:13px;">
			Print Date: <?php print date('d-M-Y') ; ?>
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<span style="font-size:13px;">
			The Secretary,<br>
			<b>Bangladesh Tea Board</b><br>
			171/172 Baizid Bostami Road Nasirabad, Chittagong.
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<b style="font-size:13px;">Re: Sale Average of Teas Season <?php print $season; ?> Sale No. <?php print $sale_no; ?> <?php print $auction_center ; ?> held on <?php print $result_prompt_date['sale_date'] ; ?>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<span style="font-size:13px;">
			Dear Sir<br>
			Average price realised for teas in the above sale are given below.
		</span>
	</td>
</tr>
</thead>
</table>

<table class="table table-sm" id="table">
<?php 
$total_sold_chest = 0;
$total_sold_kgs = 0;
$total_sold_amount = 0;
$total_sold_avg = 0;
// query
$this->db->select("*"); 	
$this->db->from('sale_average');
$this->db->group_by('old_season');
$this->db->order_by('old_season DESC');
$sql = $this->db->get();
$result_date = $sql->result_array();
foreach($result_date as $row2){
	$old_season = $row2['old_season'];
?>
<thead>
<tr>
	<th colspan="5" class="text-danger" style="border:0px; font-size:13px !important; font-family:verdana;">
		SEASON: <?php print $old_season; ?>
	</th>
</tr>

<tr>
    <th style="text-align:left; border:0px;"><u>CTC Teas :</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Chest</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Kg</u></th>
	<th style="text-align:right; border:0px;"><u>Amount Tk</u></th>
	<th style="text-align:right; border:0px;"><u>Average Tk</u></th>
</tr>
</thead>

<tbody>
<?php 
// query
$this->db->select("
	
	SUM(CASE WHEN catalogue_name='Leaf' THEN IFNULL(sold_bags,0) END) AS sold_leaf_chest,
	SUM(CASE WHEN catalogue_name='Leaf' THEN IFNULL(sold_kgs,0) END) AS sold_leaf_kgs,  
	SUM(CASE WHEN catalogue_name='Leaf' THEN IFNULL(sold_amount,0) END) AS sold_leaf_amount, 

	SUM(CASE WHEN catalogue_name='Dust' THEN IFNULL(sold_bags,0) END) AS sold_dust_chest,
	SUM(CASE WHEN catalogue_name='Dust' THEN IFNULL(sold_kgs,0) END) AS sold_dust_kgs,
	SUM(CASE WHEN catalogue_name='Dust' THEN IFNULL(sold_amount,0) END) AS sold_dust_amount
"); 	
$this->db->from('sale_average');
$this->db->where('old_season',$old_season);
$this->db->where('account_concerned','No');
$this->db->where('category','CTC');
$sql = $this->db->get();
$result_date = $sql->result_array();
foreach($result_date as $row2){

	$avg_price_leaf = ($row2['sold_leaf_amount'] > 0 && $row2['sold_leaf_kgs'] > 0) ? $row2['sold_leaf_amount'] / $row2['sold_leaf_kgs'] : '0.00';
	$avg_price_dust = ($row2['sold_dust_amount'] > 0 && $row2['sold_dust_kgs'] > 0) ? $row2['sold_dust_amount'] / $row2['sold_dust_kgs'] : '0.00';

	$sold_kgs = $row2['sold_leaf_kgs'] + $row2['sold_dust_kgs'];
	$sold_amount = $row2['sold_leaf_amount'] + $row2['sold_dust_amount'];

	$avg_sub_total = ($sold_amount > 0 && $sold_kgs > 0) ? $sold_amount / $sold_kgs : '0.00';

	// Grand total
	$total_sold_chest += $row2['sold_leaf_chest'] + $row2['sold_dust_chest'];
	$total_sold_kgs += $row2['sold_leaf_kgs'] + $row2['sold_dust_kgs'];
	$total_sold_amount += $row2['sold_leaf_amount'] + $row2['sold_dust_amount'];
	$total_sold_avg = $total_sold_amount / $total_sold_kgs;
?> 
<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">LEAF</th>
	<td style="text-align:right; border:0px;"><?php echo $row2['sold_leaf_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_leaf_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_leaf,2); ?></td>
</tr>

<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">DUST</th>
	<td style="text-align:right; border:0px;"><?php echo $row2['sold_dust_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_dust_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_dust,2); ?></td>
</tr>

<tr style="border-top:1px dashed #000;">
	<th style="text-align:left; text-transform:capitalize; border:0px;">Sub Total :</th>
	<td style="text-align:right; border:0px;"><?php echo $row2['sold_leaf_chest'] + $row2['sold_dust_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_leaf_kgs'] + $row2['sold_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_leaf_amount'] + $row2['sold_dust_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_sub_total,2); ?></td>
</tr>
<?php } ; ?>

<?php 
// query
$this->db->select("

	SUM(CASE WHEN catalogue_name='Green Tea' THEN IFNULL(sold_bags,0) END) AS sold_green_tea_chest,
	SUM(CASE WHEN catalogue_name='Green Tea' THEN IFNULL(sold_kgs,0) END) AS sold_green_tea_kgs,
	SUM(CASE WHEN catalogue_name='Green Tea' THEN IFNULL(sold_amount,0) END) AS sold_green_tea_amount,

	SUM(CASE WHEN catalogue_name='Orthodox' THEN IFNULL(sold_bags,0) END) AS sold_orthodox_chest,
	SUM(CASE WHEN catalogue_name='Orthodox' THEN IFNULL(sold_kgs,0) END) AS sold_orthodox_kgs,
	SUM(CASE WHEN catalogue_name='Orthodox' THEN IFNULL(sold_amount,0) END) AS sold_orthodox_amount
"); 	
$this->db->from('sale_average');
$this->db->where('old_season',$old_season);
$this->db->where('account_concerned','No');
$this->db->where('category','GREEN TEA');
$sql = $this->db->get();
$result_date = $sql->result_array();
foreach($result_date as $row2){

	$avg_price_green_tea = ($row2['sold_green_tea_amount'] > 0 && $row2['sold_green_tea_kgs'] > 0) ? $row2['sold_green_tea_amount'] / $row2['sold_green_tea_kgs'] : '0.00';
	$avg_price_orthodox = ($row2['sold_orthodox_amount'] > 0 && $row2['sold_orthodox_kgs'] > 0) ? $row2['sold_orthodox_amount'] / $row2['sold_orthodox_kgs'] : '0.00';

	$sold_kgs = $row2['sold_green_tea_kgs'] + $row2['sold_orthodox_kgs'];
	$sold_amount = $row2['sold_green_tea_amount'] + $row2['sold_orthodox_amount'];

	$avg_sub_total = ($sold_amount > 0 && $sold_kgs > 0) ? $sold_amount / $sold_kgs : '0.00';

	// Grand total
	$total_sold_chest += $row2['sold_green_tea_chest'] + $row2['sold_orthodox_chest'];
	$total_sold_kgs += $row2['sold_green_tea_kgs'] + $row2['sold_orthodox_kgs'];
	$total_sold_amount += $row2['sold_green_tea_amount'] + $row2['sold_orthodox_amount'];
	$total_sold_avg = $total_sold_amount / $total_sold_kgs;
?> 

<?php if($row2['sold_green_tea_chest'] > 0) {?> 
<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">GREEN TEA</th>
	<td style="text-align:right; border:0px;"><?php echo $row2['sold_green_tea_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_green_tea_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_green_tea_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_green_tea,2); ?></td>
</tr>
<?php } ?>

<?php if($row2['sold_orthodox_chest'] > 0) {?> 
<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">ORTHODOX</th>
	<td style="text-align:right; border:0px;"><?php echo $row2['sold_orthodox_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_orthodox_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_orthodox_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_orthodox,2); ?></td>
</tr>
<?php } ?>
<?php } ?>

<?php 
// query
$this->db->select("
	
	SUM(CASE WHEN catalogue_name IN ('Leaf', 'Dust') THEN IFNULL(sold_bags,0) END) AS sold_leaf_chest,
	SUM(CASE WHEN catalogue_name IN ('Leaf', 'Dust') THEN IFNULL(sold_kgs,0) END) AS sold_leaf_kgs,  
	SUM(CASE WHEN catalogue_name IN ('Leaf', 'Dust') THEN IFNULL(sold_amount,0) END) AS sold_leaf_amount
"); 	
$this->db->from('sale_average');
$this->db->where('old_season',$old_season);
$this->db->where('account_concerned','Yes');
$sql = $this->db->get();
$result_date = $sql->result_array();
foreach($result_date as $row2){

	$avg_price_leaf = ($row2['sold_leaf_amount'] > 0 && $row2['sold_leaf_kgs'] > 0) ? $row2['sold_leaf_amount'] / $row2['sold_leaf_kgs'] : '0.00';

	$sold_kgs = $row2['sold_leaf_kgs'];
	$sold_amount = $row2['sold_leaf_amount'];

	$avg_sub_total = ($sold_amount > 0 && $sold_kgs > 0) ? $sold_amount / $sold_kgs : '0.00';

	// Grand total
	$total_sold_chest += $row2['sold_leaf_chest'];
	$total_sold_kgs += $row2['sold_leaf_kgs'];
	$total_sold_amount += $row2['sold_leaf_amount'];
	$total_sold_avg = $total_sold_amount / $total_sold_kgs;
?> 

<?php if($row2['sold_leaf_chest'] > 0) { ?> 
<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">Account Concerned</th>
	<td style="text-align:right; border:0px;"><?php echo $row2['sold_leaf_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_leaf_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_leaf,2); ?></td>
</tr>
<?php } ?>
<?php } ?>

<?php } ?>
<tr style="border-top:1px dashed #000; border-bottom:1px dashed #000;">
	<th style="text-align:left; text-transform:capitalize; border:0px;">Grand Total :</th>
	<td style="text-align:right; border:0px;"><?php echo $total_sold_chest; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_sold_kgs,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_sold_amount,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_sold_avg,2); ?></td>
</tr>
</tbody>
</table>

<table class="table table-sm" id="table" style="margin-bottom:0px;">
<thead>
<tr>
    <th style="text-align:left; border:0px;"><u>Buyers Purchase :</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Chest</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Kg</u></th>
	<th style="text-align:right; border:0px;"><u>Average</u></th>
	<th style="text-align:right; border:0px;"><u>Percentage</u></th>
</tr>
</thead>
<tbody>
<?php
	// query
	$this->db->select("
		
		SUM(CASE WHEN catalogue_name='Leaf' THEN sold_bags END) AS sold_leaf_chest,
		SUM(CASE WHEN catalogue_name='Leaf' THEN sold_kgs END) AS sold_leaf_kgs,  
		SUM(CASE WHEN catalogue_name='Leaf' THEN sold_amount END) AS sold_leaf_amount, 

		SUM(CASE WHEN catalogue_name='Dust' THEN sold_bags END) AS sold_dust_chest,
		SUM(CASE WHEN catalogue_name='Dust' THEN sold_kgs END) AS sold_dust_kgs,
		SUM(CASE WHEN catalogue_name='Dust' THEN sold_amount END) AS sold_dust_amount,

		SUM(CASE WHEN catalogue_name='Green Tea' THEN sold_bags END) AS sold_green_tea_chest,
		SUM(CASE WHEN catalogue_name='Green Tea' THEN sold_kgs END) AS sold_green_tea_kgs,
		SUM(CASE WHEN catalogue_name='Green Tea' THEN sold_amount END) AS sold_green_tea_amount,

		SUM(CASE WHEN catalogue_name='Orthodox' THEN sold_bags END) AS sold_orthodox_chest,
		SUM(CASE WHEN catalogue_name='Orthodox' THEN sold_kgs END) AS sold_orthodox_kgs,
		SUM(CASE WHEN catalogue_name='Orthodox' THEN sold_amount END) AS sold_orthodox_amount
	"); 	
	$this->db->from('sale_average');
	$this->db->where('buyer_type','Export');
	$sql = $this->db->get();
	$result_date_e = $sql->row_array();

	$total_pkgs_e = $result_date_e['sold_leaf_chest'] + $result_date_e['sold_dust_chest'] + $result_date_e['sold_green_tea_chest'] + $result_date_e['sold_orthodox_chest'];
	$total_kgs_e = $result_date_e['sold_leaf_kgs'] + $result_date_e['sold_dust_kgs'] + $result_date_e['sold_green_tea_kgs'] + $result_date_e['sold_green_tea_amount'];

	// query
	$this->db->select("

		SUM(CASE WHEN catalogue_name='Leaf' THEN sold_bags END) AS sold_leaf_chest,
		SUM(CASE WHEN catalogue_name='Leaf' THEN sold_kgs END) AS sold_leaf_kgs,  
		SUM(CASE WHEN catalogue_name='Leaf' THEN sold_amount END) AS sold_leaf_amount, 

		SUM(CASE WHEN catalogue_name='Dust' THEN sold_bags END) AS sold_dust_chest,
		SUM(CASE WHEN catalogue_name='Dust' THEN sold_kgs END) AS sold_dust_kgs,
		SUM(CASE WHEN catalogue_name='Dust' THEN sold_amount END) AS sold_dust_amount,

		SUM(CASE WHEN catalogue_name='Green Tea' THEN sold_bags END) AS sold_green_tea_chest,
		SUM(CASE WHEN catalogue_name='Green Tea' THEN sold_kgs END) AS sold_green_tea_kgs,
		SUM(CASE WHEN catalogue_name='Green Tea' THEN sold_amount END) AS sold_green_tea_amount,

		SUM(CASE WHEN catalogue_name='Orthodox' THEN sold_bags END) AS sold_orthodox_chest,
		SUM(CASE WHEN catalogue_name='Orthodox' THEN sold_kgs END) AS sold_orthodox_kgs,
		SUM(CASE WHEN catalogue_name='Orthodox' THEN sold_amount END) AS sold_orthodox_amount
	"); 	
	$this->db->from('sale_average');
	$this->db->where('buyer_type','Internal');
	$sql = $this->db->get();
	$result_date_l = $sql->row_array();

	$total_pkgs_l = $result_date_l['sold_leaf_chest'] + $result_date_l['sold_dust_chest'] + $result_date_l['sold_green_tea_chest'] + $result_date_l['sold_orthodox_chest'];
	$total_kgs_l = $result_date_l['sold_leaf_kgs'] + $result_date_l['sold_dust_kgs'] + $result_date_l['sold_green_tea_kgs'] + $result_date_l['sold_orthodox_kgs'];

	$total_amount_e = $result_date_e['sold_leaf_amount'] + $result_date_e['sold_dust_amount'] + $result_date_e['sold_green_tea_amount'] + $result_date_e['sold_orthodox_amount'];

	$total_amount_l = $result_date_l['sold_leaf_amount'] + $result_date_l['sold_dust_amount'] + $result_date_l['sold_green_tea_amount'] + $result_date_l['sold_orthodox_amount'];

	$total_pkgs = $total_pkgs_l + $total_pkgs_e;
	$export_percentage = $total_pkgs > 0 ? ($total_pkgs_e * 100) / $total_pkgs : 0;
	$internal_percentage = $total_pkgs > 0 ? ($total_pkgs_l * 100) / $total_pkgs : 0;
	$export_average   = ($total_amount_e != 0) ? ($total_amount_e / $total_kgs_e) : 0;
	$internal_average = ($total_amount_l != 0) ? ($total_amount_l / $total_kgs_l) : 0;
	$total_le_kgs =  ($total_kgs_l + $total_kgs_e);
	$total_le_amount =  ($total_amount_l + $total_amount_e);
	$total_le_average = ($total_le_amount != 0) ? ($total_le_amount / $total_le_kgs) : 0;

?>

<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">Export Buyers :</th>
	<td style="text-align:right; border:0px;"><?php echo $total_pkgs_e; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_kgs_e,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($export_average,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($export_percentage,2); ?> %</td>
</tr>

<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">Local Buyers :</th>
	<td style="text-align:right; border:0px;"><?php echo $total_pkgs_l; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_kgs_l,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($internal_average,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($internal_percentage,2); ?> %</td>
</tr>

<tr style="border-top:1px dashed #000; border-bottom:1px dashed #000;">
	<th style="text-align:left; text-transform:capitalize; border:0px;">Grand Total :</th>
	<td style="text-align:right; border:0px;"><?php echo ($total_pkgs_l + $total_pkgs_e); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($total_kgs_l + $total_kgs_e),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($total_le_average),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($export_percentage + $internal_percentage),2); ?> %</td>
</tr>
</tbody>
</table>
</div> <!-- col -->

<div class="col-sm-12" style="margin-top:20px;">
	Sincerely Yours,
</div>

<div class="col-sm-12 text-left" style="margin-top:50px;">
	<b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b><br><br>
	<span style="font-size:14px;">CC: National Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">Produce Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">Purba Bangla Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">Progressive Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">K.S Brokers Limited Chittagong</span><br>
	<span style="font-size:14px; margin-left:30px;">Planters Brokers Limited Ctg.</span>
</div>

<div class="col-sm-12 text-left" style="margin-top:20px;">
	<span style="font-size:14px;">Noo: Secretary, Bangladesh Cha Sangsad, Chittagong</span><br>
	<span style="font-size:14px; margin-left:38px;">Managing Director, National Tea Co. Ltd, Dhaka.</span><br>
	<span style="font-size:14px; margin-left:38px;">Asstt. Director (Trade), Bangladesh Tea Board, Chittagong.</span><br>
	<span style="font-size:14px; margin-left:38px;">Deputy Director (Trade), Bangladesh Tea Board, Chittagong.</span>
</div>

<div class="col-sm-12 text-left" style="margin-top:40px;">
	<i style="text-transform: uppercase; font-size:14px; font-weight:bold;"> Jamuna Tech </i>
</div>

</div> <!-- row -->
<?php } // sale average ?>

<?php if($report_name=='garden_cost'){ ?>
<style>
#table tr td {
    border-color:black;
    font-size:10px !important;
    padding:15px;
}
#table tr th{
    border-color:black;
    font-size:10px !important;
    padding:3px;
}

#table thead tr th{
    border-top: 1px dashed #000 !important; 
    border-bottom: 1px dashed #000 !important;
    text-align:center;
    text-transform:uppercase;
    font-size:10px !important;
    padding:3px;
}

@page {
  size: legal landscape;
  margin-left: 10px;
  margin-right: 100px;
}
</style>

<div class="row">
<div class="form-group col-sm-12 text-right">
    <a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 15;

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

    invoice_entry.season,
    invoice_entry.old_season,
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.garden_id,    
    invoice_entry.account_concerned,
    gn.garden_name,

    @total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
    
    @total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
    
    @total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
    @compelte_amount:=(IFNULL(sale_entry.buyer1_complete_amount,0) + IFNULL(sale_entry.buyer2_complete_amount,0) + IFNULL(sale_entry.buyer3_complete_amount,0)) AS compelte_amount,         
    
    (@total_bags * IFNULL(invoice_entry.warehouse_charge,0)) AS warehouse_charge,
    (@total_bags * IFNULL(invoice_entry.insp_sample,0)) AS insp_sample,
    ((@total_amount * IFNULL(invoice_entry.brokerage_commission,0))/100) AS brokerage_commission,
    ((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess,

    @total_bill := (@total_amount + (@total_amount * 16 / 100)) AS total_bill,    
    @net_total_bill := ((@total_bill + @total_kgs * 10 / 100) - @compelte_amount) AS net_total_bill,    
    @adjustment_bill := IF(@compelte_amount > 0, @net_total_bill, 0) AS adjustment_bill

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();
?>

<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;
$net_adjustment_bill = 0;

// query
$this->db->select("
    bill_register.old_season, 
    bill_register.garden_id, 
    bill_register.account_concerned, 
    IF(
        bill_register.season = bill_register.old_season, bill_register.garden_name, CONCAT(bill_register.garden_name, ' (', bill_register.old_season, ')')
    ) AS garden_name, 
    IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
    sum(bill_register.total_bags) AS total_bags, 
    sum(bill_register.total_kgs) AS total_kgs, 
    sum(bill_register.total_amount) AS total_amount, 
    sum(bill_register.adjustment_bill) AS adjustment_bill, 
    sum(bill_register.warehouse_charge) AS warehouse_charge, 
    sum(bill_register.insp_sample) AS insp_sample, 
    sum(bill_register.brokerage_commission) AS brokerage_commission, 
    sum(bill_register.tea_cess) AS tea_cess, 
    IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
    IFNULL(gat.garden_misc,0) AS garden_misc,
    IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
    IFNULL(gat.truck_fare,0) AS truck_fare,  
    IFNULL(gat.bank_charges,0) AS bank_charges 
");     
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, garden_id, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND `auction_center`='".$auction_center."' AND `sale_no`='".$sale_no."' GROUP BY `garden_id`, `old_season`) gat", "bill_register.garden_id = gat.garden_id AND bill_register.old_season = gat.old_season", "left");

$this->db->group_by('bill_register.garden_id, bill_register.old_season, bill_register.account_concerned');
$this->db->order_by('bill_register.garden_name ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){
?>
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="17" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;"><?php print $website_title; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="16" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
                Account sales for sale no &nbsp; <?php print $sale_no; ?> &nbsp; <?php print $auction_center; ?> Season &nbsp; <?php print $season; ?> <br>
                held on &nbsp; <?php print date('d-M-Y' , strtotime($result_prompt_date['sale_date'])); ?> &nbsp; &nbsp; Prompt Date &nbsp; <?php print date('d-M-Y' , strtotime($result_prompt_date['buyer_prompt_date'])); ?>
            </b>
        </td>
    </tr>
    <tr>
        <th>SL</th>
        <th>GARDEN NAME</th>
        <th>PKGS</th>
        <th>Warehouse <br /> Charges</th>
        <th>Bank <br /> Charges</th>
        <th>Advance</th>
        <th>Truck <br /> Fare</th>
        <th>Garden <br /> Misc.</th>
        <th>Additional <br /> Rent</th>
    </tr>
</thead>
<tbody>
<?php 
foreach($query3 as $row3):
    // total tk
    $total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

    // total deduction tk
    $total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));
?>
<tr>
    <td style="text-align:left; text-transform:uppercase; border:0px;">
        <?php print $sl++ ; ?>
    </td>
    <td style="text-align:left; text-transform:uppercase; border:0px; white-space:nowrap;">
        <?php if($row3['report_link']=='Current'){ ?>
            <a target="blink" href="<?php echo site_url('MY_Controller/garden_sale_account_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row3['garden_id'].'&account_concerned='.$row3['account_concerned'].'&report=1'); ?>"><?php echo $row3['garden_name']; ?> <?php echo ($row3['account_concerned']=='Yes') ? 'A/C' : ''; ?></a>
        <?php }else{ ?> 
            <a target="blink" href="<?php echo site_url('MY_Controller/garden_sale_account_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row3['garden_id'].'&account_concerned='.$row3['account_concerned'].'&old_season='.$row3['old_season'].'&report=6&season_name=Old Season'); ?>"><?php echo $row3['garden_name']; ?> <?php echo ($row3['account_concerned']=='Yes') ? 'A/C' : ''; ?></a>
        <?php } ?>
    </td>
    <td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
    <td style="text-align:right; border:0px; width:400px; vertical-align:middle;">
    <div class="input-group input-group-sm" style="display:flex; align-items:center;">
        
        <!-- Warehouse select -->
        <select required class="form-control" name="w_house_id" style="text-transform:uppercase; width:150px; margin-right:5px; height:32px;">
            <option value="">Select</option>
            <?php 
           $inv_wh_data = $this->ajax_model->inv_wh_data_table(
                $row3['garden_id'],
                $season,
                $auction_center,
                $sale_no
            );
            foreach ($inv_wh_data as $wh_data_list) {
                echo "<option title=\"{$wh_data_list['id']}\" value=\"{$wh_data_list['id']}\">{$wh_data_list['short_key_word']}</option>";
            }
            ?>
        </select>

        <!-- Editable warehouse charge -->
        <input type="text" 
               class="form-control editable-field" 
               style="text-align:right; width:100px; height:32px;" 
               data-garden-id="<?php echo $row3['garden_id']; ?>"
               data-old-season="<?php echo $row3['old_season']; ?>"
               data-season="<?php echo $season; ?>"
               data-auction-center="<?php echo $auction_center; ?>"
               data-sale-no="<?php echo $sale_no; ?>"
               data-field-type="warehouse_charge"
              value="<?php echo ($row3['warehouse_charge'] === null || $row3['warehouse_charge'] == 0) ? '' : number_format($row3['warehouse_charge'], 2, '.', ''); ?>"
               placeholder="Enter charge"
               onblur="saveOnKeyup(this)">
    </div>
</td>

    <td style="text-align:right; border:0px;">
        <input type="text" 
               class="form-control input-sm editable-field" 
               style="width: 80px; text-align:right;"
               data-garden-id="<?php echo $row3['garden_id']; ?>"
               data-old-season="<?php echo $row3['old_season']; ?>"
               data-season="<?php echo $season; ?>"
               data-auction-center="<?php echo $auction_center; ?>"
               data-sale-no="<?php echo $sale_no; ?>"
               data-field-type="bank_charges"
               value="<?php echo ($row3['bank_charges'] === null || $row3['bank_charges'] == 0) ? '' : number_format($row3['bank_charges'], 2, '.', ''); ?>"
               placeholder="Bank"
               onblur="saveOnKeyup(this)">
    </td>
    <td style="text-align:right; border:0px;">
        <input type="text" 
               class="form-control input-sm editable-field" 
               style="width: 80px; text-align:right;"
               data-garden-id="<?php echo $row3['garden_id']; ?>"
               data-old-season="<?php echo $row3['old_season']; ?>"
               data-season="<?php echo $season; ?>"
               data-auction-center="<?php echo $auction_center; ?>"
               data-sale-no="<?php echo $sale_no; ?>"
               data-field-type="garden_adj_tk"
               value="<?php echo ($row3['garden_adj_tk'] === null || $row3['garden_adj_tk'] == 0) ? '' : number_format($row3['garden_adj_tk'], 2, '.', ''); ?>"
               placeholder="Advance"
               onblur="saveOnKeyup(this)">
    </td>
    <td style="text-align:right; border:0px;">
        <input type="text" 
               class="form-control input-sm editable-field" 
               style="width: 80px; text-align:right;"
               data-garden-id="<?php echo $row3['garden_id']; ?>"
               data-old-season="<?php echo $row3['old_season']; ?>"
               data-season="<?php echo $season; ?>"
               data-auction-center="<?php echo $auction_center; ?>"
               data-sale-no="<?php echo $sale_no; ?>"
               data-field-type="truck_fare"
               value="<?php echo ($row3['truck_fare'] === null || $row3['truck_fare'] == 0) ? '' : number_format($row3['truck_fare'], 2, '.', ''); ?>"
               placeholder="Truck"
               onblur="saveOnKeyup(this)">
    </td>
    <td style="text-align:right; border:0px;">
        <input type="text" 
               class="form-control input-sm editable-field" 
               style="width: 80px; text-align:right;"
               data-garden-id="<?php echo $row3['garden_id']; ?>"
               data-old-season="<?php echo $row3['old_season']; ?>"
               data-season="<?php echo $season; ?>"
               data-auction-center="<?php echo $auction_center; ?>"
               data-sale-no="<?php echo $sale_no; ?>"
               data-field-type="garden_misc"
               value="<?php echo ($row3['garden_misc'] === null || $row3['garden_misc'] == 0) ? '' : number_format($row3['garden_misc'], 2, '.', ''); ?>"
               placeholder="Misc."
               onblur="saveOnKeyup(this)">
    </td>
    <td style="text-align:right; border:0px;">
        <input type="text" 
               class="form-control input-sm editable-field" 
               style="width: 80px; text-align:right;"
               data-garden-id="<?php echo $row3['garden_id']; ?>"
               data-old-season="<?php echo $row3['old_season']; ?>"
               data-season="<?php echo $season; ?>"
               data-auction-center="<?php echo $auction_center; ?>"
               data-sale-no="<?php echo $sale_no; ?>"
               data-field-type="add_wh_rent"
               value="<?php echo ($row3['add_wh_rent'] === null || $row3['add_wh_rent'] == 0) ? '' : number_format($row3['add_wh_rent'], 2, '.', ''); ?>"
               placeholder="Add.Rent"
               onblur="saveOnKeyup(this)">
    </td>
</tr>
<?php
$total_bags += $row3['total_bags'];
$warehouse_charge += $row3['warehouse_charge'];
$add_wh_rent += $row3['add_wh_rent'];
$bank_charges += $row3['bank_charges'];
$garden_misc += $row3['garden_misc'];
$garden_adj_tk += $row3['garden_adj_tk'];
$truck_fare += $row3['truck_fare'];
endforeach;
?>

<tr>
    <th colspan="2" style="text-align:right; color:red;">TOTAL : </th>
    <th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($warehouse_charge,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($bank_charges,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($garden_adj_tk,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($truck_fare,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($garden_misc,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($add_wh_rent,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
</div> <!-- /row -->
<?php
// end of if condition
}else{
    
    print "<div id=\"page\"><h1 style=\"color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 19px;
    font-weight: normal;
    margin: 0 0 14px 0;
    padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
    <p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF garden_cost ?>

</div>
<!-- /container -->

<script>
function printDiv(divName){
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
}

//row highlighted
$('#table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});


// Save on blur (debug-friendly)
function saveOnKeyup(element) {
    var $el = $(element);
    var gardenId = $el.data('garden-id');
    var oldSeason = $el.data('old-season');
    var season = $el.data('season');
    var auctionCenter = $el.data('auction-center');
    var saleNo = $el.data('sale-no');
    var fieldType = $el.data('field-type');
    var value = $el.val();

    // Try to find the select inside same cell, fallback to row
    var $select = $el.closest('td').find('select[name="w_house_id"]');
    if (!$select.length) $select = $el.closest('tr').find('select[name="w_house_id"]');
    var wHouseId = $select.length ? $select.val() : null;

    console.log('saveOnKeyup ->', {
        gardenId, oldSeason, season, auctionCenter, saleNo, fieldType, value, wHouseId
    });

    // Validate number
    if (isNaN(value) || value === '') {
        console.warn('Invalid value, skipping update');
        return;
    }

    // reuse existing indicator if present (prevents stacking)
    var $ind = $el.siblings('.saving-indicator');
    if (!$ind.length) $ind = $('<span class="saving-indicator" style="font-size:10px; margin-left:5px;"></span>').insertAfter($el);
    $ind.text('Saving...').css('color','gray').show();

    if (fieldType === 'warehouse_charge') {
        // call ajax with a callback to show server message
        update_warehouse_charge({
            season, auction_center: auctionCenter, sale_no: saleNo,
            garden_id: gardenId, old_season: oldSeason,
            w_house_id: wHouseId, warehouse_charge: value
        }, function(resp) {
            console.log('update_warehouse_charge response:', resp);
            if (resp && resp.success) {
                $ind.text('Saved!').css('color','green').delay(1200).fadeOut();
            } else {
                var msg = resp && resp.message ? resp.message : 'Server error';
                $ind.text('Error: ' + msg).css('color','red').delay(2500).fadeOut();
            }
        });
    } else {
        // same as before for garden charges
        var chargeType = '';
        switch (fieldType) {
            case 'bank_charges': chargeType = 'Bank charges'; break;
            case 'garden_adj_tk': chargeType = 'Advance'; break;
            case 'truck_fare': chargeType = 'Truck Fare'; break;
            case 'garden_misc': chargeType = 'Garden Misc.'; break;
            case 'add_wh_rent': chargeType = 'Add. W/H Rent'; break;
            default: return;
        }
        update_garden_charge(season, auctionCenter, saleNo, gardenId, oldSeason, chargeType, value);
    }
}

// Unified AJAX function (debug)
function update_warehouse_charge(payload, callback) {
    console.log('AJAX payload ->', payload);
    $.ajax({
        url: '<?php echo site_url("MY_Controller/update_warehouse_charge"); ?>',
        type: 'POST',
        dataType: 'json',
        data: payload,
        success: function(response) {
            console.log('AJAX success:', response);
            if (typeof callback === 'function') callback(response);
        },
        error: function(xhr, status, err) {
            console.error('AJAX error:', status, err, xhr.responseText);
            if (typeof callback === 'function') callback({ success: false, message: 'AJAX/server error' });
        }
    });
}

// Function to update garden charge via AJAX
function update_garden_charge(season, auction_center, sale_no, garden_id, old_season, charge_type, charge_amount) {
    $.ajax({
        url: '<?php echo site_url("MY_Controller/update_garden_charge"); ?>',
        type: 'POST',
        data: {
            season: season,
            auction_center: auction_center,
            sale_no: sale_no,
            garden_id: garden_id,
            old_season: old_season,
            charge_type: charge_type,
            charge_amount: charge_amount
        },
        success: function(response) {
            $('.saving-indicator').text('Saved!').delay(1000).fadeOut();
        },
        error: function() {
            $('.saving-indicator').text('Error!').css('color', 'red').delay(1000).fadeOut();
        }
    });
}

</script>