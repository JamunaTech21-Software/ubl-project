<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$old_season = (!empty($this->input->get('old_season', TRUE))) ? $this->input->get('old_season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';
$report_no = (!empty($this->input->get('report', TRUE))) ? $this->input->get('report', TRUE) : '';

$current_date_time= date("Y-m-d h:i:sa");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo ($report_no=='1') ? 'ALL GARDEN BUYER SALE TO SALE REPORT' : 'GARDEN SALES REPORT' ; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
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

<?php if($report_no=='1'){ ?>

<style>
/* ==============================================
   COMPACT REPORT STYLES - REDUCE GAPS BETWEEN TEXT
   ============================================== */

/* 1. Reduce spacing inside table cells */
.table td, 
.table th {
    padding: 4px 6px !important;
    line-height: 1.5 !important;
    vertical-align: middle !important;
}

/* 2. Reduce margins and padding on all form-group and row elements */
.row, .form-group {
    margin: 0 !important;
    padding: 0 !important;
}

/* 3. Remove extra spacing from the main container */
.row.no-print, 
.row.no-print .form-group,
.row.no-print .col-sm-12 {
    margin: 0 !important;
    padding: 0 !important;
}

/* 4. Tighten the print button area */
.btn-default {
    padding: 3px 8px !important;
    margin-bottom: 5px !important;
    display: inline-block;
}

/* 5. Reduce gaps between table rows (border spacing) */
.table {
    border-collapse: collapse !important;
    margin-bottom: 0 !important;
}

/* 6. Tighten the table header and footer cells */
thead th, 
tbody td, 
tbody th {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
}

/* 7. Reduce spacing around text in headings and titles */
b, strong, .text-center, .text-left, .text-right {
    line-height: 1.3 !important;
}

/* 8. Remove extra white space from the wrapper divs */
div[class*="col-sm"], 
div[class*="col-md"] {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* 9. Compact the top area with website title and analysis headers */
table.table tbody tr td b, 
table.table thead tr th b {
    margin: 0;
    padding: 0;
}

/* 10. Reduce gap between stacked rows (no extra margins) */
tr + tr {
    margin-top: 0;
}

/* 11. Specifically for border:0px cells - keep them tight */
td[style*="border:0px"], 
th[style*="border:0px"] {
    padding: 2px 4px !important;
}

/* 12. Reduce spacing for the TOTAL row */
th, td {
    letter-spacing: normal;
    word-break: normal;
}

/* 13. Override any default bootstrap margin on .row and .col */
.row:before, .row:after,
.form-group:before, .form-group:after {
    display: none !important;
}

/* 14. Remove extra line height from the main body text */
body, table, td, th, div, span, p {
    line-height: 1.5;
}

/* 15. Optional: reduce font size slightly if needed (but keep readable) */
.table td, .table th {
    font-size: 12px;
}

/* 16. For the garden name link, keep consistent spacing */
td a {
    display: inline-block;
    padding: 0;
    margin: 0;
    line-height: 1.5;
}

/* 17. Remove extra spacing above/below the entire table */
.table-responsive {
    margin-bottom: 0;
}
</style>	
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
$query = $this->db->query("SELECT * FROM buyer_setup WHERE id='".$buyer_id."' ");
$fetch_bn = $query->row_array();
?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
	<tr>
		<th colspan="6" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="6" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">ALL GARDEN BUYER PURCHASE ANALYSIS</b>
		</th>
	</tr>
	<tr>
		<th colspan="6" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="6" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:15px;">Buyer Name: <?php print $fetch_bn['buyer_name']; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="2" class="text-left" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
		</th>
		
		<th colspan="1" style="border:0px;"></th>
		
		<th colspan="2" class="text-right" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
		</th>
	</tr>
	<tr>
		<th style="text-align:left; border:1px solid; width:40%;">Garden Name</th>
		<th style="text-align:right; border:1px solid; width:15%;">Total Bags</th>
		<th style="text-align:right; border:1px solid; width:15%;">Total Kgs</th>
		<th style="text-align:right; border:1px solid; width:15%;">Total Amount</th>
		<th style="text-align:right; border:1px solid; width:15%;">Avg.</th>
	</tr>
</thead>
<tbody>
<?php
## create temporary table - buyer_purchase_analysis
$this->db->query("DROP TABLE IF EXISTS `buyer_purchase_analysis`");

// create buyer_purchase_analysis @  buyer-1
$this->db->query("CREATE TABLE `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,
	gn.garden_name,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
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
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_purchase_analysis @  buyer-2
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,
	gn.garden_name,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,
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
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_purchase_analysis @  buyer-3
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,
	gn.garden_name,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
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
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


// +++++++++++++ REPORT VIEW ++++++++++++++
$sold_bag=0;
$sold_kgs=0;
$total_amount=0;

//query
$this->db->select("garden_id, garden_name, sum(buyer_bag) AS sold_bag, sum(buyer_kgs) AS sold_kgs, sum(buyer_amount) AS total_amount, (sum(buyer_amount) / sum(buyer_kgs)) AS avg_rate"); 
$this->db->from('buyer_purchase_analysis');
$this->db->where('buyer_id', $buyer_id);
$this->db->group_by('garden_id');
$this->db->order_by('garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px;">
		<a target="_blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>"><?php print $row1['garden_name']; ?></a>
	</td>
	<td style="text-align:right; border:0px;"><?php print $row1['sold_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($row1['sold_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($row1['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($row1['avg_rate'],2,'.',','); ?></td>
</tr>
<?php
	$sold_bag +=$row1['sold_bag'];
	$sold_kgs +=$row1['sold_kgs'];
	$total_amount +=$row1['total_amount'];
} // foreach
?>
<tr>
	<th colspan="1" style="text-align:right; border:1px solid;">TOTAL: </th>
	<th style="text-align:right; border:1px solid;"><?php print $sold_bag; ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($sold_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
	<?php 
		print ($sold_kgs > 0 && $total_amount > 0) ? number_format($total_amount / $sold_kgs,2,'.','') : '0.00'; 
	?>
	</th>
</tr>
</tbody>
</table>
</div>
</div>
<!-- /row -->
<?php } ?>

<?php if($report_no=='2'){ ?>
<style>
/* ==============================================
   COMPACT REPORT STYLES - REDUCE GAPS BETWEEN TEXT
   ============================================== */

/* 1. Reduce spacing inside table cells */
.table td, 
.table th {
    padding: 4px 6px !important;
    line-height: 1.5 !important;
    vertical-align: middle !important;
}

/* 2. Reduce margins and padding on all form-group and row elements */
.row, .form-group {
    margin: 0 !important;
    padding: 0 !important;
}

/* 3. Remove extra spacing from the main container */
.row.no-print, 
.row.no-print .form-group,
.row.no-print .col-sm-12 {
    margin: 0 !important;
    padding: 0 !important;
}

/* 4. Tighten the print button area */
.btn-default {
    padding: 3px 8px !important;
    margin-bottom: 5px !important;
    display: inline-block;
}

/* 5. Reduce gaps between table rows (border spacing) */
.table {
    border-collapse: collapse !important;
    margin-bottom: 0 !important;
}

/* 6. Tighten the table header and footer cells */
thead th, 
tbody td, 
tbody th {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
}

/* 7. Reduce spacing around text in headings and titles */
b, strong, .text-center, .text-left, .text-right {
    line-height: 1.3 !important;
}

/* 8. Remove extra white space from the wrapper divs */
div[class*="col-sm"], 
div[class*="col-md"] {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* 9. Compact the top area with website title and analysis headers */
table.table tbody tr td b, 
table.table thead tr th b {
    margin: 0;
    padding: 0;
}

/* 10. Reduce gap between stacked rows (no extra margins) */
tr + tr {
    margin-top: 0;
}

/* 11. Specifically for border:0px cells - keep them tight */
td[style*="border:0px"], 
th[style*="border:0px"] {
    padding: 2px 4px !important;
}

/* 12. Reduce spacing for the TOTAL row */
th, td {
    letter-spacing: normal;
    word-break: normal;
}

/* 13. Override any default bootstrap margin on .row and .col */
.row:before, .row:after,
.form-group:before, .form-group:after {
    display: none !important;
}

/* 14. Remove extra line height from the main body text */
body, table, td, th, div, span, p {
    line-height: 1.5;
}

/* 15. Optional: reduce font size slightly if needed (but keep readable) */
.table td, .table th {
    font-size: 12px;
}

/* 16. For the garden name link, keep consistent spacing */
td a {
    display: inline-block;
    padding: 0;
    margin: 0;
    line-height: 1.5;
}

/* 17. Remove extra spacing above/below the entire table */
.table-responsive {
    margin-bottom: 0;
}
</style>	

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
## Get buyer name if buyer_id is provided
$buyer_name_filter = '';
$buyer_filter_condition = '';
if(!empty($buyer_id)){
    $query_buyer = $this->db->query("SELECT buyer_name FROM buyer_setup WHERE id='".$buyer_id."' ");
    $buyer_info = $query_buyer->row_array();
    $buyer_name_filter = $buyer_info['buyer_name'];
    $buyer_filter_condition = " AND sale_entry.buyer1 = '".$buyer_id."' OR sale_entry.buyer2 = '".$buyer_id."' OR sale_entry.buyer3 = '".$buyer_id."' ";
}

## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report with buyer filter
$buyer_sql_condition = (!empty($buyer_id)) ? " AND (sale_entry.buyer1 = '".$buyer_id."' OR sale_entry.buyer2 = '".$buyer_id."' OR sale_entry.buyer3 = '".$buyer_id."') " : "";

$this->db->query("CREATE TABLE `grade_sales_avg_report` (
SELECT 

	invoice_entry.id, 
	invoice_entry.garden_id, 
	invoice_entry.grade_id, 
	invoice_entry.old_season, 
	invoice_entry.chest_number, 
	invoice_entry.net_kgs AS net_kgs, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount

FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' ".$buyer_sql_condition." GROUP BY invoice_entry.id) ");


// total offering
$query = $this->db->query("SELECT sum(chest_number) AS total_offering FROM `invoice_entry` WHERE season='".$season."' AND sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' ");
$fetch_net_offering = $query->row_array();
?>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table" style="width:100%;">
<thead>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">GRADE WISE SALES ANALYSIS</b>
		</th>
	</tr>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
		</th>
	</tr>
	<?php if(!empty($buyer_id)){ ?>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:15px; color:#0066cc;">Buyer: <?php print $buyer_name_filter; ?></b>
		</th>
	</tr>
	<?php } ?>
	<tr>
		<th colspan="2" class="text-left" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
		</th>
		<th colspan="1" style="border:0px;"></th>
		<th colspan="2" class="text-right" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
		</th>
	</tr>
	<tr>
		<th style="text-align:center; border:1px solid; width:30%;">GRADE</th>
		<th style="text-align:center; border:1px solid; width:17%;">SOLD BAGS</th>
		<th style="text-align:center; border:1px solid; width:18%;">SOLD KGS</th>
		<th style="text-align:center; border:1px solid; width:20%;">AMOUNT (TK.)</th>
		<th style="text-align:center; border:1px solid; width:15%;">AVG. (TK./KG)</th>
	</tr>
</thead>
<tbody>
<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 
$gt_total_wd = 0; 
$gt_total_out_bags = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order,
	sum(chest_number) AS ctlg_wise_offering
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
if(!empty($buyer_id)){
    $this->db->where("invoice_entry.id IN (SELECT invoice_id FROM sale_entry WHERE buyer1='".$buyer_id."' OR buyer2='".$buyer_id."' OR buyer3='".$buyer_id."')");
}
$this->db->group_by('tea_grade_setup.catalogue_name');
$this->db->order_by('catalogue_order ASC');
$query = $this->db->get();
foreach($query->result_array() as $row_catalogue){
	
//category breakdown
$g_chest_number = 0;
$g_net_kgs = 0;
$sold_bags2 = 0;
$sold_kgs2 = 0;
$sold_amount2 = 0;
$total_wd2 = 0;
$total_out_bags2 = 0;

//query
$this->db->select("tea_grade_setup.grade_category, 
	(CASE 
		WHEN grade_category='BROKENS' THEN 'Leaf'
		WHEN grade_category='DM' THEN 'Leaf'
		WHEN grade_category='FANNINGS' THEN 'Leaf'
		WHEN grade_category='DUST' THEN 'Dust'
		END
	) AS category_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id');

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.soft_delete', 1);
if(!empty($buyer_id)){
    $this->db->where("invoice_entry.id IN (SELECT invoice_id FROM sale_entry WHERE buyer1='".$buyer_id."' OR buyer2='".$buyer_id."' OR buyer3='".$buyer_id."')");
}
$this->db->group_by('tea_grade_setup.grade_category');
$this->db->order_by('category_order DESC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

//grade breakdown
$chest_number = 0;
$net_kgs = 0;
$sold_bags = 0;
$sold_kgs = 0;
$sold_amount = 0;
$total_wd = 0;
$total_out_bags = 0;

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, sum(CASE WHEN `remarks`='W/D' THEN invoice_entry.chest_number END) AS wd, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 

$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
if(!empty($buyer_id)){
    $this->db->where("invoice_entry.id IN (SELECT invoice_id FROM sale_entry WHERE buyer1='".$buyer_id."' OR buyer2='".$buyer_id."' OR buyer3='".$buyer_id."')");
}
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
	
	$out_bags = $row3['chest_number'] - ($row3['sold_bags'] + $row3['wd']);
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase; font-weight:bold;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_bags']); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold; color:#0066cc;"><?php echo number_format($row3['sold_avg'],2,'.',','); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
$total_wd += $row3['wd'];
$total_out_bags += $out_bags;
} // grade foreach
?>
<tr style="background-color:#f5f5f5;">
	<th style="text-align:left; border:0px; text-transform:uppercase; color:#f01212;""><?php print $row2['grade_category']; ?> SUB TOTAL : </th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_bags); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($sold_amount > 0 && $sold_kgs > 0) ? number_format($sold_amount / $sold_kgs,2,'.',',') : '0.00'; ?>
	</th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
$total_wd2 += $total_wd;
$total_out_bags2 += $total_out_bags;
} // catagory foreach
?>
<tr style="background-color:#e8e8e8;">
	<th style="text-align:left; border:1px solid; text-transform:uppercase; font-size:13px;">TOTAL <?php print strtoupper($row_catalogue['catalogue_name']); ?> : </th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($sold_bags2); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($sold_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($sold_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($sold_amount2 > 0 && $sold_kgs2 > 0) ? number_format($sold_amount2 / $sold_kgs2,2,'.',',') : '0.00'; ?>
	</th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
$gt_total_wd += $total_wd2;
$gt_total_out_bags += $total_out_bags2;
} // catalogue foreach
?>
<tr style="background-color:#d3d3d3; font-weight:bold;">
	<th style="text-align:left; border:1px solid; font-size:14px;">GRAND TOTAL : </th>
	<th style="text-align:right; border:1px solid; font-size:14px;"><?php echo number_format($gt_sold_bags); ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px;"><?php echo number_format($gt_sold_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px;"><?php echo number_format($gt_sold_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px; color:#cc0000;">
		<?php echo ($gt_sold_amount > 0 && $gt_sold_kgs > 0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.',',') : '0.00'; ?>
	</th>
</tr>
</tbody>
</table>
</div>
</div> 
<!-- /row -->
<?php } ?>

</div> 
<!-- container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
function printDiv(divName) {
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
</script>

</body>
</html>