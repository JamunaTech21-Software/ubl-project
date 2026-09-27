<!DOCTYPE html>
<html lang="en">
<head>
<title>After Sales Lot Print</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-glyphicons.css'; ?>">

<script src="<?php echo base_url().'assets/js/multi_filter_dt/jquery-3.3.1.js'; ?>"></script>		
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/bootstrap.min.js'; ?>"></script>
<style>
body{
	font-family:verdana !important;
}
@media print {
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	.page-break { page-break-after: always; }
	.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left; }
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
	.col-sm-2 { width: 16.66666667%; }
	.col-sm-1 { width: 8.33333333%; }
}
@page{
	size:A4;
}
</style>
</head>
<body>
<div class="container">

<?php
// get html value
$row_limit_pp = 42;
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$catalouge_name = (!empty($this->input->get('catalouge_name', TRUE))) ? $this->input->get('catalouge_name', TRUE) : '';

## create temporary table - after_sales_lot
$this->db->query("DROP TABLE IF EXISTS `after_sales_lot`");

// create after_sales_lot @  buyer-1
$this->db->query("CREATE TABLE `after_sales_lot` (
SELECT 

	'A' AS buyer_serial,
	invoice_entry.catalouge_name,
	invoice_entry.lot_no,
	IF(buyer_setup.short_key_code IS NOT NULL, buyer_setup.short_key_code, 'Out') AS buyer_name,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	sale_entry.buyer_type1 AS buyer_type

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create after_sales_lot @  buyer-2
$this->db->query("INSERT INTO `after_sales_lot` (
SELECT 
	
	'B' AS buyer_serial,
	invoice_entry.catalouge_name,
	invoice_entry.lot_no,
	buyer_setup.short_key_code AS buyer_name,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	sale_entry.buyer_type2 AS buyer_type

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create after_sales_lot @  buyer-3
$this->db->query("INSERT INTO `after_sales_lot` (
SELECT 
	
	'C' AS buyer_serial,
	invoice_entry.catalouge_name,
	invoice_entry.lot_no,
	buyer_setup.short_key_code AS buyer_name,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	sale_entry.buyer_type3 AS buyer_type

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// Sale schedule
$query = $this->db->query("SELECT * FROM `sale_schedule_setup` WHERE sale_no='".$sale_no."' AND season_name='".$season."' AND auction_center='".$auction_center."' ");
$sale_sch_data = $query->row_array();
?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="javascript:fn_export_excel()"><span style="cursor:pointer;"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel</span></a>
		&nbsp;
		<a href="#" onClick="window.print();"><span style="cursor:pointer;"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
	</div>
</div>

<?php
// query
$this->db->select("lot_no, sold_price, IF(sold_price IS NOT NULL, buyer_name, 'Out') AS buyer_name, buyer_bag, buyer_type"); 
$this->db->from('after_sales_lot');
($catalouge_name=='Full Catalogue') ? '' : $this->db->where('catalouge_name', $catalouge_name);	
$this->db->group_by('lot_no, buyer_name');
$this->db->order_by('lot_no ASC, buyer_serial ASC');
$query = $this->db->get();
$result = $query->result_array();
if(count($result) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 3;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($result);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($result as $row1){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>

<!-- start top heading row -->
<br />
<div class="row">
<div class="col-sm-12">
<table style="width:100%; border-bottom:1px solid;">
	<thead>
		<tr>
			<th style="font-weight:bold; border:0px solid; text-transform: uppercase; text-align:left;"><b style="font-size:16px;"><?php print $website_title; ?></b></th>
			<td style="border:0px solid; text-align:left;">SEASON <?php print $season; ?> &nbsp; <?php print $auction_center; ?></td>
			<td style="text-align:right;">Page <?php print $page; ?></td>
		</tr>	
		<tr>
			<td colspan="2" style="border:0px solid; text-align:left;">LOT WISE BUYER SETTLEMENT FOR SALE NO. <?php print $sale_no; ?> <?php print $catalouge_name ; ?></td>
			<td style="text-align:right;">Sale Date: <?php print date("d-m-Y", strtotime($sale_sch_data['sale_date'])); ?></td>
		</tr>
	</thead>
</table>
</div> <!-- /col -->
</div> <!-- /row -->

<br />

<!-- valuation report :: after catalouge prepared -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-4">
<table style="width:100%;">
<tr>
	<th style="text-align:center; border:1px solid; padding:1px; font-size:14px;">LOT</th>
	<th style="text-align:right; border:1px solid; padding:1px; font-size:14px;">RATE</th>
	<th style="text-align:right; border:1px solid; padding:1px; font-size:14px;">BUYER</th>
	<th style="text-align:right; border:1px solid; padding:1px; font-size:14px;">BAG</th>
</tr>
<?php } //end ?>
<tr>
	<td style="text-align:center; font-size:14px; padding:1px;"><?php echo $row1['lot_no']; ?></td> 
	<td style="text-align:right; font-size:14px; padding:1px;"><?php echo number_format($row1['sold_price'],1,'.',''); ?></td>
	<td style="text-align:right; font-size:14px; padding:1px; text-transform:uppercase;">
		<?php echo $row1['buyer_name']; ?>
		<?php echo ($row1['buyer_type']=='Export') ? '<font color=red><b> E</b></font>' : ''; ?>
	</td>
	<td style="text-align:right; font-size:14px; padding:1px;"><?php echo $row1['buyer_bag']; ?></td>
</tr>
<?php
// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>
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
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. Please Lock the Catalogue Page First. </b></p></div>";
}
?>
</div> 
<!-- /container -->

</body>
</html>