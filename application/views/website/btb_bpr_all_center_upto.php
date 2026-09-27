<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
$buyer_category = (!empty($this->input->get('buyer_category', TRUE))) ? $this->input->get('buyer_category', TRUE) : '';
$combine_center = (!empty($this->input->get('combine_center', TRUE))) ? $this->input->get('combine_center', TRUE) : '';

## Sale details
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$sale_data = $query->row_array();
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


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

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


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

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
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
		<th colspan="6" style="text-align:center; border:1px solid;">CURRENT BAGS <br /> <?php print $auction_center; ?></th>
		<th colspan="4" style="text-align:center; border:1px solid;">UPTO DATE BAGS <br /><?php print substr($auction_center,0,4); ?> to <?php print substr($combine_center,0,4); ?></th>
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
</script>