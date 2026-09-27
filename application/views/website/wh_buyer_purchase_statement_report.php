<!DOCTYPE html>
<html lang="en">
<head>
<title>Warehouse VAT Statement</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$warehouse_id = (!empty($this->input->get('warehouse_id', TRUE))) ? $this->input->get('warehouse_id', TRUE) : '';
$report_no = (!empty($this->input->get('report_no', TRUE))) ? $this->input->get('report_no', TRUE) : '';

## warehouse details
$query = $this->db->query("SELECT * FROM warehouse_setup WHERE id='".$warehouse_id."' ");
$warehouse_data = $query->row_array();

## Sale details
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$sale_data = $query->row_array();

## VAt
$query = $this->db->query("SELECT vat FROM invoice_entry WHERE season='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' AND w_house_id='".$warehouse_id."' ");
$vat_data = $query->row_array();
?>

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

<div class="container">

<div class="row">
	<div class="col-sm-12 text-right">
		<a class="no-print" href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span>
		</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">

<?php 
if($report_no=='2'){

## create temporary table - wh_vat_stmt
$this->db->query("DROP TABLE IF EXISTS `wh_vat_stmt`");

// create wh_vat_stmt @  buyer-1
$this->db->query("CREATE TABLE `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer1_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-2
$this->db->query("INSERT INTO `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer2_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-3
$this->db->query("INSERT INTO `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer3_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end of create table
?>

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
	size:A4 auto;
	margin-left: 100px;
}
</style>

<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="5">
		<b style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="5">
		<b style="font-size:13px; text-transform: uppercase;">To : <?php print $warehouse_data['short_key_word']; ?></b><br>
		<b style="font-size:13px; text-transform: uppercase; margin-left:35px;"><?php print $warehouse_data['warehouse_name']; ?></b>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px;" colspan="5">
		<span style="font-size:13px;">Re: Individual buyers purchase in Sale No. Form <?php print $sale_no; ?> <?php print $auction_center; ?>, Season: <?php print $season; ?> </span>
	</td>
</tr>

<tr>
	<th style="text-align:center; border:1px solid;">Buyer Information</th>
	<th style="text-align:center; border:1px solid;">Plgs.</th>
	<th style="text-align:center; border:1px solid;">Total Kg.</th>
	<th style="text-align:center; border:1px solid;">Amount</th>
	<th style="text-align:center; border:1px solid;">VAT Amount</th>
</tr>
</thead>
<tbody>	
<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat = 0;

$this->db->select("bill_no, sale_date, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, sum(brokerage_tk) AS total_brokerage, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk"); 	
$this->db->from('wh_vat_stmt');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_type DESC, buyer_id ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; font-weight:bold; border:0px; text-transform:uppercase;">
		<?php echo $row3['buyer_name']; ?> - <?php echo substr($row3['buyer_type'],0,1); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px; text-transform:uppercase;">
		<?php echo $row3['total_bags']; ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_amount'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_vat_tk'],2,'.',','); ?>
	</td>
</tr>	
<?php
	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$total_vat += $row3['total_vat_tk'];
} // foreach	
?>
<tr style="border:1px solid;">
	<th style="text-align:right; color:red;"> Sub Total: </th>
	<th style="text-align:right; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5"></td></tr>
<!-- This row is only used for two row spacing -->
<tr style="border:1px solid;">
	<th style="text-align:right; color:red;"> Shed Total: </th>
	<th style="text-align:right; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5"></td></tr>
<!-- This row is only used for two row spacing -->
<tr style="border:0px;">
	<td colspan="4" style="text-align:right; vertical-align:middle; border:none; color:red;"> VAT (<?php print $vat_data['vat']; ?> %) : </td>
	<th style="text-align:right; vertical-align:middle; border:none; color:red;">
	<?php 
		print number_format($total_vat,2,'.',','); 
	?>
	</th>
</tr>
</tbody>
</table>
<?php } ?>

<?php 
if($report_no=='3'){

## create temporary table - wh_vat_stmt
$this->db->query("DROP TABLE IF EXISTS `wh_vat_stmt`");

// create wh_vat_stmt @  buyer-1
$this->db->query("CREATE TABLE `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer1_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-2
$this->db->query("INSERT INTO `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer2_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-3
$this->db->query("INSERT INTO `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer3_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end of create table
?>

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
	size:A4 auto;
	margin-left: 100px;
}
</style>

<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="5">
		<b style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="5">
		<b style="font-size:13px; text-transform: uppercase;">To : <?php print $warehouse_data['short_key_word']; ?></b><br>
		<b style="font-size:13px; text-transform: uppercase; margin-left:35px;"><?php print $warehouse_data['warehouse_name']; ?></b>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px;" colspan="5">
		<span style="font-size:13px;">Re: Individual buyers purchase in Sale No. <?php print $sale_no; ?> To <?php print $sale_to; ?> <?php print $auction_center; ?>, Season: <?php print $season; ?> </span>
	</td>
</tr>

<tr>
	<th style="text-align:center; border:1px solid;">Buyer Information</th>
	<th style="text-align:center; border:1px solid;">Plgs.</th>
	<th style="text-align:center; border:1px solid;">Total Kg.</th>
	<th style="text-align:center; border:1px solid;">Amount</th>
	<th style="text-align:center; border:1px solid;">VAT Amount</th>
</tr>
</thead>
<tbody>	
<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat = 0;

$this->db->select("bill_no, sale_date, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, sum(brokerage_tk) AS total_brokerage, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk"); 	
$this->db->from('wh_vat_stmt');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_type DESC, buyer_id ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; font-weight:bold; border:0px; text-transform:uppercase;">
		<?php echo $row3['buyer_name']; ?> - <?php echo substr($row3['buyer_type'],0,1); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px; text-transform:uppercase;">
		<?php echo $row3['total_bags']; ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_amount'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_vat_tk'],2,'.',','); ?>
	</td>
</tr>	
<?php
	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$total_vat += $row3['total_vat_tk'];
} // foreach	
?>
<tr style="border:1px solid;">
	<th style="text-align:right; color:red;"> Sub Total: </th>
	<th style="text-align:right; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5"></td></tr>
<!-- This row is only used for two row spacing -->
<tr style="border:1px solid;">
	<th style="text-align:right; color:red;"> Shed Total: </th>
	<th style="text-align:right; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5"></td></tr>
<!-- This row is only used for two row spacing -->
<tr style="border:0px;">
	<td colspan="4" style="text-align:right; vertical-align:middle; border:none; color:red;"> VAT (<?php print $vat_data['vat']; ?> %) : </td>
	<th style="text-align:right; vertical-align:middle; border:none; color:red;">
	<?php 
		print number_format($total_vat,2,'.',','); 
	?>
	</th>
</tr>
</tbody>
</table>
<?php } ?>

<?php 
if($report_no=='1'){
	
## create temporary table - wh_vat_stmt
$this->db->query("DROP TABLE IF EXISTS `wh_vat_stmt`");

// create wh_vat_stmt @  buyer-1
$this->db->query("CREATE TABLE `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer1_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-2
$this->db->query("INSERT INTO `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer2_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-3
$this->db->query("INSERT INTO `wh_vat_stmt` (
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
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer3_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$warehouse_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end of create table


// query of internal buyers
$query = $this->db->query("SELECT sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk FROM wh_vat_stmt WHERE buyer_type='Internal' ");
$internal_result = $query->row_array();

$total_packages = $internal_result['total_bags'];
$total_kgs = $internal_result['total_kgs'];
$total_amount = $internal_result['total_amount'];
$total_vat = $internal_result['total_vat_tk'];

if($total_packages > 0){
?>
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:left; vertical-align:middle; border:none;">INTERNAL</th>
	<th style="text-align:right; vertical-align:middle; border:none;">PKGS</th>
	<th style="text-align:right; vertical-align:middle; border:none;">QTY/KGS</th>
	<th style="text-align:right; vertical-align: middle; border:none;">AMOUNT (TAKA)</th>
	<th style="text-align:right; vertical-align: middle; border:none;">VAT (<?php print $vat_data['vat']; ?>% TAKA)</th>
</tr>
</thead>
<tbody>	
<tr>
	<th colspan="2" style="text-align:right; color:red;"><?php echo $total_packages; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
</tr>	
<tr>
	<td colspan="4" style="text-align:right; vertical-align:middle; border:none; color:red;"> <?php print $vat_data['vat']; ?>% VAT ON INTERNAL BUYERS: </td>
	<th style="text-align:right; vertical-align:middle; border:none; color:red;">
	<?php 
		print number_format($total_vat,2,'.',','); 
	?>
	</th>
</tr>
</tbody>
</table>
<?php }


// query of Exports buyers
$query = $this->db->query("SELECT sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk FROM wh_vat_stmt WHERE buyer_type='Export' ");
$internal_result = $query->row_array();

$total_packages2 = $internal_result['total_bags'];
$total_kgs2 = $internal_result['total_kgs'];
$total_amount2 = $internal_result['total_amount'];
$total_vat2 = $internal_result['total_vat_tk'];
?>

<?php if($total_packages2 > 0){ ?>
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:left; vertical-align:middle; border:none;">EXPORT</th>
	<th style="text-align:right; vertical-align:middle; border:none;">PKGS</th>
	<th style="text-align:right; vertical-align:middle; border:none;">QTY/KGS</th>
	<th style="text-align:right; vertical-align: middle; border:none;">AMOUNT (TAKA)</th>
	<th style="text-align:right; vertical-align: middle; border:none;">VAT </th>
</tr>
</thead>
<tbody>	
<tr>
	<th colspan="2" style="text-align:right; color:red;"><?php echo $total_packages2; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount2,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_vat2,2,'.',','); ?></th>
</tr>	
</tbody>
</table>
<?php }

} // end of report 1
?>

<?php if($report_no=='4'){

## create temporary table - wh_vat_stmt
$this->db->query("DROP TABLE IF EXISTS `wh_vat_stmt`");

// create wh_vat_stmt @  buyer-1
$this->db->query("CREATE TABLE `wh_vat_stmt` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.w_house_id,
    warehouse_setup.warehouse_name,
    warehouse_setup.short_key_word,
    sale_entry.buyer1 AS buyer_id,    
    sale_entry.buyer_type1 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
    ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    (
        IFNULL(sale_entry.buyer1_amount,0) 
        + 
        ((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
        +
        ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
        +
        ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
    ) AS total_tk,
    CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1) AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)
JOIN warehouse_setup ON (invoice_entry.w_house_id = warehouse_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-2
$this->db->query("INSERT INTO `wh_vat_stmt` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.w_house_id,
    warehouse_setup.warehouse_name,
    warehouse_setup.short_key_word,
    sale_entry.buyer2 AS buyer_id,    
    sale_entry.buyer_type2 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
    ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    (
        IFNULL(sale_entry.buyer2_amount,0) 
        + 
        ((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
        +
        ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
        +
        ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
    ) AS total_tk,
    CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2) AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)
JOIN warehouse_setup ON (invoice_entry.w_house_id = warehouse_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_vat_stmt @  buyer-3
$this->db->query("INSERT INTO `wh_vat_stmt` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.w_house_id,
    warehouse_setup.warehouse_name,
    warehouse_setup.short_key_word,
    sale_entry.buyer3 AS buyer_id,    
    sale_entry.buyer_type3 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
    ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    (
        IFNULL(sale_entry.buyer3_amount,0) 
        + 
        ((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
        +
        ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
        +
        ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
    ) AS total_tk,
    CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3) AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)
JOIN warehouse_setup ON (invoice_entry.w_house_id = warehouse_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end of create table
?>

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
	margin-top:10px;
}
.table{
	font-family:courier;
	width: 100% !important;
}
#table, #table2{
	border-color:black;
}
#table tr td, #table2 tr td{
	border-color:black;
	font-size:14px !important;
	padding:3px;
}
#table tr th, #table2 tr th{
	border-color:black;
	font-size:14px !important;
	padding:3px;
}

#table tr td:first-child, #table2 tr td:first-child{
	width: 300px !important;
}

#table tr td:not(:first-child), #table2 tr td:not(:first-child) {
  width: 100px !important;
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

@page{
    size:A4 auto;
    margin-left: 100px;
	@top-right {
			content: counter(page) " of " counter(pages);
			font-size: 18px;
		}
}
</style>

<?php
// Get all warehouses for this sale
$this->db->select('DISTINCT(wh_vat_stmt.w_house_id), warehouse_setup.warehouse_name, warehouse_setup.short_key_word');
$this->db->from('wh_vat_stmt');
$this->db->join('warehouse_setup', 'wh_vat_stmt.w_house_id = warehouse_setup.id');
$warehouses = $this->db->get()->result_array();

foreach($warehouses as $warehouse) {
    $warehouse_id = $warehouse['w_house_id'];
    $warehouse_name = $warehouse['warehouse_name'];
    $warehouse_short = $warehouse['short_key_word'];
?>

<table class="table table-sm" id="table">
<thead>
<tr>
    <td style="text-align:left; border:0px;" colspan="5">
        <b style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b>
    </td>
</tr>

<tr>
    <td style="text-align:left; border:0px;" colspan="5">
        <b style="font-size:13px; text-transform: uppercase;">To : <?php print $warehouse_short; ?></b><br>
        <b style="font-size:13px; text-transform: uppercase; margin-left:35px;"><?php print $warehouse_name; ?></b>
    </td>
</tr>
<tr>
    <td style="text-align:left; border:0px;" colspan="5">
        <span style="font-size:13px;">Re: Individual buyers purchase in Sale No. Form <?php print $sale_no; ?> <?php print $auction_center; ?>, Season: <?php print $season; ?> </span>
    </td>
</tr>

<tr>
    <th style="text-align:center; border:1px solid;">Buyer Information</th>
    <th style="text-align:center; border:1px solid;">Plgs.</th>
    <th style="text-align:center; border:1px solid;">Total Kg.</th>
    <th style="text-align:center; border:1px solid;">Amount</th>
    <th style="text-align:center; border:1px solid;">VAT Amount</th>
</tr>
</thead>
<tbody>    
<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat = 0;

$this->db->select("bill_no, sale_date, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, sum(brokerage_tk) AS total_brokerage, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk");     
$this->db->from('wh_vat_stmt');
$this->db->where('w_house_id', $warehouse_id);
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_type DESC, buyer_id ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase;">
        <?php echo preg_replace('/\s*bin.*/i', '', $row3['buyer_name']);  ?>   <?php echo $row3['buyer_type'] == 'Internal' ? ''  : ($row3['buyer_type'] == 'Export'  ? '<span style="color:red;">- EXPORT</span>'  : $row3['buyer_type']);?>
		
    </td>
    <td style="text-align:right; border:0px; text-transform:uppercase;">
        <?php echo $row3['total_bags']; ?>
    </td>
    <td style="text-align:right; border:0px;">
        <?php echo number_format($row3['total_kgs'],2,'.',''); ?>
    </td>
    <td style="text-align:right; border:0px;">
        <?php echo number_format($row3['total_amount'],2,'.',','); ?>
    </td>
    <td style="text-align:right; border:0px;">
        <?php echo number_format($row3['total_vat_tk'],2,'.',','); ?>
    </td>
</tr>    
<?php
    $total_bags += $row3['total_bags'];
    $total_kgs += $row3['total_kgs'];
    $total_amount += $row3['total_amount'];
    $total_vat += $row3['total_vat_tk'];
} // foreach    
?>
<tr style="border:1px solid;">
    <th style="text-align:right; color:red;"> Sub Total: </th>
    <th style="text-align:right; color:red;"><?php echo $total_bags; ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5"></td></tr>
<!-- This row is only used for two row spacing -->
<tr style="border:1px solid;">
    <th style="text-align:right; color:red;"> Shed Total: </th>
    <th style="text-align:right; color:red;"><?php echo $total_bags; ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
    <th style="text-align:right; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5"></td></tr>
<!-- This row is only used for two row spacing -->
<tr style="border:0px;">
    <td colspan="4" style="text-align:right; vertical-align:middle; border:none; color:red;"> VAT (<?php print $vat_data['vat']; ?> %) : </td>
    <th style="text-align:right; vertical-align:middle; border:none; color:red;">
    <?php 
        print number_format($total_vat,2,'.',','); 
    ?>
    </th>
</tr>
</tbody>
</table>

<!-- Add page break between warehouses -->
<div style="page-break-after: always;"></div>

<?php
} // end foreach warehouses
?>
<?php
} // end 
?>
</div> 
</div> 
<!-- row -->

<br />
<br />

<div class="row">
	<div class="col-sm-12">
		<p class="text-left" style="font-family: verdana; border:0px; float:left;"> 
			<b style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b>
			<br /> 
			<span style="font-size:13px;"> Copy To : 1. The Superintendent, Customs, Excise & VAT.</span> <br>
			<span style="font-size:14px; margin-left:65px;">2. Shed In-Charge.</span>
		</p>
	</div>
</div> 
<!-- /row -->
	
</div>
<!-- /container -->
</body>
</html>