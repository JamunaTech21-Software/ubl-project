<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$w_house_id = (!empty($this->input->get('w_house_id', TRUE))) ? $this->input->get('w_house_id', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
?>

<div id="printableArea" class="container">
<?php if($report_name=='wh_single_sales'){ ?>
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
	size:legal Portrait;
	margin-left: 80px;
	margin-bottom: 100px;
}
</style>
<?php 
//query
$this->db->select("warehouse_name, warehouse_address, short_key_word"); 
$this->db->from('warehouse_setup');
$this->db->where('id', $w_house_id);
$this->db->where('soft_delete', 1);
$this->db->group_by('id');
$this->db->order_by('warehouse_name ASC');
$query = $this->db->get();
$fetch_wh = $query->row_array();
?>
<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<?php
## create temporary table - wh_stmt_garder_ws
$this->db->query("DROP TABLE IF EXISTS `wh_stmt_garder_ws`");

// create wh_stmt_garder_ws @  buyer-1
$this->db->query("CREATE TABLE `wh_stmt_garder_ws` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.garden_id, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 	
	sale_entry.buyer_type1 AS buyer_type,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	garden_and_company_setup.garden_name

FROM invoice_entry  
JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 
JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_stmt_garder_ws @  buyer-2
$this->db->query("INSERT INTO `wh_stmt_garder_ws` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.garden_id, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	sale_entry.buyer_type2 AS buyer_type,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	garden_and_company_setup.garden_name

FROM invoice_entry  
JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 
JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_stmt_garder_ws @  buyer-3
$this->db->query("INSERT INTO `wh_stmt_garder_ws` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.garden_id, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,  	
	sale_entry.buyer_type3 AS buyer_type,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
    garden_and_company_setup.garden_name

FROM invoice_entry  
JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 
JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end of create table 
?>

<?php
// get html value
$row_limit_pp = 40;

// query
$total_pkgs = 0;
$net_kgs = 0;
$sold_amount = 0;
$total_vat = 0;
$this->db->select("garden_name, buyer_type, SUM(buyer_bag) AS buyer_bag, SUM(buyer_kgs) AS buyer_kgs, SUM(buyer_amount) AS sold_amount, (CASE WHEN buyer_type='Internal' THEN 'A' WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac"); 
$this->db->from('wh_stmt_garder_ws');
$this->db->group_by('garden_id, buyer_type');
$this->db->order_by('buyer_type_ac ASC, lot_no ASC');
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

$vat = ($row3['buyer_type']=='Export') ? '0' : (($row3['sold_amount'] * 15) / 100);

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
    <td style="border:0px;" colspan="4">
		<b style="text-transform: uppercase; font-size:20px; text-align:left; font-family:courier;">
			<?php print $website_title; ?>
		</b>
	</td>
    <td style="border:0px; text-align:right">Page <?php print $page; ?></td>
</tr>

<tr>
    <td style="border:0px;" colspan="5">
		<b style="text-transform: uppercase; font-size:15px; font-family:courier; text-align:left;">To : <?php print $fetch_wh['short_key_word']; ?> <br> <span style="margin-left:38px;"><?php print $fetch_wh['warehouse_name'] ; ?>, <?php print $fetch_wh['warehouse_address'] ; ?></span></b><br>
    	<span style="font-size:15px; font-family:courier; text-align:left;">Re : Individual garden wise purchase is Sale No. <?php print $sale_no; ?> <?php print $auction_center ; ?> Season <?php print $season; ?></span>
	</td>
</tr>

<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5" style="border:0px;"></td></tr>
<!-- This row is only used for two row spacing -->

<tr>
    <td style="text-align:center; border:1px solid;">Garden Information</td>
    <td style="text-align:center; border:1px solid;">Pkgs.</td>
    <td style="text-align:center; border:1px solid;">Total Kg.</td>
    <td style="text-align:center; border:1px solid;">Amount</td>
	<td style="text-align:center; border:1px solid;">Vat 15%</td>
</tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:left;border:0"><?php echo $row3['garden_name']; ?> <?php echo ($row3['buyer_type']=='Export') ? '<b style="color:red;"> - '.$row3['buyer_type'].'</b>' : ''; ?></td>
    <td style="text-align:right;border:0"><?php echo number_format($row3['buyer_bag'],2,'.',''); ?></td>
	<td style="text-align:right;border:0"><?php echo number_format($row3['buyer_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;border:0"><?php print number_format($row3['sold_amount'],2,'.',',');?></td>
    <td style="text-align:right;border:0"><?php echo $vat = number_format($vat,2,'.',''); ?></td>
</tr>

<?php
$total_pkgs += $row3['buyer_bag'];
$net_kgs += $row3['buyer_kgs'];
$sold_amount += $row3['sold_amount'];
$total_vat += $vat;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>
</tbody>
</table>
</div> <!-- col -->
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
<table class="table table-sm" id="table2">
	<tbody>
		<tr style="border:1px solid;">
			<td style="text-align:right; border:1px solid:">Shed Total :</td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($total_pkgs,2,'.',''); ?></td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($net_kgs,2,'.',''); ?></td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($sold_amount,2,'.',','); ?></td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($total_vat,2,'.',','); ?></td>
		</tr>
		<!-- This row is only used for two row spacing -->
		<tr><td height="30" colspan="5"></td></tr>
		<!-- This row is only used for two row spacing -->
		<tr>
			<td style="text-align:right; border:0px;" colspan="3">VAT (15%) :</td>
			<td style="text-align:right; border:0px;"><?php echo number_format($total_vat,2,'.',','); ?></td>
			<td style="border:0px;"></td>
		</tr>
	</tbody>
</table>
<div class="row">
	<div class="col-sm-12 text-left" style="margin-top:50px; font-family:courier;">
		<b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b><br><br>
		<span style="font-size:14px;">Copy to: 1. Superintendant, Custom, Excise & VAT.</span><br>
		<span style="font-size:14px; margin-left:75px;">2. Shed In-Charge.</span><br>
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
<?php } // single sale ?>


<?php if($report_name=='wh_single_sales_to_sale'){ ?>
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
	size:legal Portrait;
	margin-left: 80px;
	margin-bottom: 100px;
}
</style>
<?php 
//query
$this->db->select("warehouse_name, warehouse_address, short_key_word"); 
$this->db->from('warehouse_setup');
$this->db->where('id', $w_house_id);
$this->db->where('soft_delete', 1);
$this->db->group_by('id');
$this->db->order_by('warehouse_name ASC');
$query = $this->db->get();
$fetch_wh = $query->row_array();
?>
<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<?php
## create temporary table - wh_stmt_garder_ws
$this->db->query("DROP TABLE IF EXISTS `wh_stmt_garder_ws`");

// create wh_stmt_garder_ws @  buyer-1
$this->db->query("CREATE TABLE `wh_stmt_garder_ws` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.garden_id, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 	
	sale_entry.buyer_type1 AS buyer_type,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	garden_and_company_setup.garden_name

FROM invoice_entry  
JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 
JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_stmt_garder_ws @  buyer-2
$this->db->query("INSERT INTO `wh_stmt_garder_ws` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.garden_id, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 	
	sale_entry.buyer_type2 AS buyer_type,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	garden_and_company_setup.garden_name

FROM invoice_entry  
JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 
JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create wh_stmt_garder_ws @  buyer-3
$this->db->query("INSERT INTO `wh_stmt_garder_ws` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.garden_id, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	sale_entry.buyer_type3 AS buyer_type,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
    garden_and_company_setup.garden_name

FROM invoice_entry  
JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 
JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_no."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end of create table 
?>

<?php
// get html value
$row_limit_pp = 40;

// query
$total_pkgs = 0;
$net_kgs = 0;
$sold_amount = 0;
$total_vat = 0;
$this->db->select("garden_name, buyer_type, SUM(buyer_bag) AS buyer_bag, SUM(buyer_kgs) AS buyer_kgs, SUM(buyer_amount) AS sold_amount, (CASE WHEN buyer_type='Internal' THEN 'A' WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac"); 
$this->db->from('wh_stmt_garder_ws');
$this->db->group_by('garden_id, buyer_type');
$this->db->order_by('buyer_type_ac ASC, lot_no ASC');
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

$vat = ($row3['buyer_type']=='Export') ? '0' : (($row3['sold_amount'] * 15) / 100);

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
    <td style="border:0px;" colspan="4">
		<b style="text-transform: uppercase; font-size:20px; text-align:left; font-family:courier;">
			<?php print $website_title; ?>
		</b>
	</td>
    <td style="border:0px; text-align:right">Page <?php print $page; ?></td>
</tr>

<tr>
    <td style="border:0px;" colspan="5">
		<b style="text-transform: uppercase; font-size:15px; font-family:courier; text-align:left;">To : <?php print $fetch_wh['short_key_word'] ; ?> <br> <span style="margin-left:38px;"><?php print $fetch_wh['warehouse_name'] ; ?>, <?php print $fetch_wh['warehouse_address'] ; ?></span></b><br>
    	<span style="font-size:15px; font-family:courier; text-align:left;">Re : Individual garden wise purchase is Sale No. <?php print $sale_no; ?> To <?php print $sale_to; ?> <?php print $auction_center ; ?> Season <?php print $season; ?></span>
	</td>
</tr>

<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="5" style="border:0px;"></td></tr>
<!-- This row is only used for two row spacing -->

<tr>
    <td style="text-align:center; border:1px solid;">Garden Information</td>
    <td style="text-align:center; border:1px solid;">Pkgs.</td>
    <td style="text-align:center; border:1px solid;">Total Kg.</td>
    <td style="text-align:center; border:1px solid;">Amount</td>
	<td style="text-align:center; border:1px solid;">Vat 15%</td>
</tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:left;border:0"><?php echo $row3['garden_name']; ?> <?php echo ($row3['buyer_type']=='Export') ? '<b style="color:red;"> - '.$row3['buyer_type'].'</b>' : ''; ?></td>
    <td style="text-align:right;border:0"><?php echo number_format($row3['buyer_bag'],2,'.',''); ?></td>
	<td style="text-align:right;border:0"><?php echo number_format($row3['buyer_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;border:0"><?php print number_format($row3['sold_amount'],2,'.',',');?></td>
    <td style="text-align:right;border:0"><?php echo $vat = number_format($vat,2,'.',''); ?></td>
</tr>

<?php
$total_pkgs += $row3['buyer_bag'];
$net_kgs += $row3['buyer_kgs'];
$sold_amount += $row3['sold_amount'];
$total_vat += $vat;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>
</tbody>
</table>
</div> <!-- col -->
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
<table class="table table-sm" id="table2">
	<tbody>
		<tr style="border:1px solid;">
			<td style="text-align:right; border:1px solid:">Shed Total :</td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($total_pkgs,2,'.',''); ?></td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($net_kgs,2,'.',''); ?></td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($sold_amount,2,'.',','); ?></td>
			<td style="text-align:right; border:1px solid"><?php echo number_format($total_vat,2,'.',','); ?></td>
		</tr>
		<!-- This row is only used for two row spacing -->
		<tr><td height="30" colspan="5"></td></tr>
		<!-- This row is only used for two row spacing -->
		<tr>
			<td style="text-align:right; border:0px;" colspan="3">VAT (15%) :</td>
			<td style="text-align:right; border:0px;"><?php echo number_format($total_vat,2,'.',','); ?></td>
			<td style="border:0px;"></td>
		</tr>
	</tbody>
</table>
<div class="row">
	<div class="col-sm-12 text-left" style="margin-top:50px; font-family:courier;">
		<b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b><br><br>
		<span style="font-size:14px;">Copy to: 1. Superintendant, Custom, Excise & VAT.</span><br>
		<span style="font-size:14px; margin-left:75px;">2. Shed In-Charge.</span><br>
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
<?php } // sale to sale ?>

<?php if($report_name=='wh_multi_sales'){ ?>
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
	size:legal Portrait;
	margin-left: 80px;
	margin-bottom: 100px;
    @top-right {
			content: counter(page) " of " counter(pages);
			font-size: 18px;
		}
}
</style>

<div class="row">
<div class="form-group col-sm-12 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<?php
## create temporary table - wh_stmt_garder_ws
$this->db->query("DROP TABLE IF EXISTS `wh_stmt_garder_ws`");

/**
 * Build a temp table for all warehouses (no w_house_id filter).
 * Include warehouse details so we can print the header per warehouse:
 *   warehouse_name, warehouse_address, short_key_word
 */
$this->db->query("CREATE TABLE `wh_stmt_garder_ws` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.lot_no, 
    invoice_entry.w_inv_no, 
    invoice_entry.garden_id,
    invoice_entry.w_house_id,
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name,
    sale_entry.buyer_type1 AS buyer_type,
    IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.sold_price,0) AS sold_price,
    IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
    garden_and_company_setup.garden_name,
    warehouse_setup.warehouse_name,
    warehouse_setup.warehouse_address,
    warehouse_setup.short_key_word
FROM invoice_entry
JOIN tea_grade_setup            ON invoice_entry.grade_id   = tea_grade_setup.id
JOIN garden_and_company_setup   ON invoice_entry.garden_id  = garden_and_company_setup.id
JOIN warehouse_setup            ON invoice_entry.w_house_id = warehouse_setup.id
JOIN sale_entry                 ON invoice_entry.id         = sale_entry.invoice_id
JOIN buyer_setup                ON sale_entry.buyer1        = buyer_setup.id
WHERE invoice_entry.season='".$season."'
  AND invoice_entry.auction_center='".$auction_center."'
  AND invoice_entry.sale_no='".$sale_no."'
  AND invoice_entry.catalogue_status='prepared'
  AND invoice_entry.soft_delete='1'
GROUP BY invoice_entry.id)");

$this->db->query("INSERT INTO `wh_stmt_garder_ws` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.lot_no, 
    invoice_entry.w_inv_no, 
    invoice_entry.garden_id,
    invoice_entry.w_house_id,
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name,
    sale_entry.buyer_type2 AS buyer_type,
    IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.sold_price,0) AS sold_price,
    IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
    garden_and_company_setup.garden_name,
    warehouse_setup.warehouse_name,
    warehouse_setup.warehouse_address,
    warehouse_setup.short_key_word
FROM invoice_entry
JOIN tea_grade_setup            ON invoice_entry.grade_id   = tea_grade_setup.id
JOIN garden_and_company_setup   ON invoice_entry.garden_id  = garden_and_company_setup.id
JOIN warehouse_setup            ON invoice_entry.w_house_id = warehouse_setup.id
JOIN sale_entry                 ON invoice_entry.id         = sale_entry.invoice_id
JOIN buyer_setup                ON sale_entry.buyer2        = buyer_setup.id
WHERE invoice_entry.season='".$season."'
  AND invoice_entry.auction_center='".$auction_center."'
  AND invoice_entry.sale_no='".$sale_no."'
  AND invoice_entry.catalogue_status='prepared'
  AND invoice_entry.soft_delete='1'
GROUP BY invoice_entry.id)");

$this->db->query("INSERT INTO `wh_stmt_garder_ws` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.lot_no, 
    invoice_entry.w_inv_no, 
    invoice_entry.garden_id,
    invoice_entry.w_house_id,
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name,
    sale_entry.buyer_type3 AS buyer_type,
    IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.sold_price,0) AS sold_price,
    IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
    garden_and_company_setup.garden_name,
    warehouse_setup.warehouse_name,
    warehouse_setup.warehouse_address,
    warehouse_setup.short_key_word
FROM invoice_entry
JOIN tea_grade_setup            ON invoice_entry.grade_id   = tea_grade_setup.id
JOIN garden_and_company_setup   ON invoice_entry.garden_id  = garden_and_company_setup.id
JOIN warehouse_setup            ON invoice_entry.w_house_id = warehouse_setup.id
JOIN sale_entry                 ON invoice_entry.id         = sale_entry.invoice_id
JOIN buyer_setup                ON sale_entry.buyer3        = buyer_setup.id
WHERE invoice_entry.season='".$season."'
  AND invoice_entry.auction_center='".$auction_center."'
  AND invoice_entry.sale_no='".$sale_no."'
  AND invoice_entry.catalogue_status='prepared'
  AND invoice_entry.soft_delete='1'
GROUP BY invoice_entry.id)");

// ===== Display section (group by warehouse) =====

$grand_pkgs = 0;
$grand_kgs = 0;
$grand_amount = 0;
$grand_vat = 0;

// Pull all rows aggregated at garden+buyer_type, but ordered by warehouse then garden
$this->db->select("
    w_house_id,
    warehouse_name,
    warehouse_address,
    short_key_word,
    garden_name,
    buyer_type,
    SUM(buyer_bag)    AS buyer_bag,
    SUM(buyer_kgs)    AS buyer_kgs,
    SUM(buyer_amount) AS sold_amount,
    (CASE WHEN buyer_type='Internal' THEN 'A' WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac
");
$this->db->from('wh_stmt_garder_ws');
$this->db->group_by('w_house_id, garden_id, buyer_type');
$this->db->order_by('warehouse_name ASC, buyer_type_ac ASC, garden_name ASC');
$query  = $this->db->get();
$rows   = $query->result_array();

if(count($rows) > 0){

    $current_wh_id = null;
    $wh_pkgs = 0; $wh_kgs = 0; $wh_amount = 0; $wh_vat = 0;
    $table_open = false;

    foreach($rows as $r){

        // Compute VAT per row (0% for Export, 15% otherwise)
        $row_vat = ($r['buyer_type'] == 'Export') ? 0 : (($r['sold_amount'] * 15) / 100);

        // When warehouse changes, close previous and print "Shed Total" + footer
        if($current_wh_id !== null && $current_wh_id != $r['w_house_id']){
            // close body/table and print warehouse subtotal & footer
            ?>
            </tbody>
            </table>

            <table class="table table-sm" id="table2" style="margin-top:5px;">
                <tbody>
                    <tr style="border:1px solid;">
                        <td style="text-align:right;">Shed Total :</td>
                        <td style="text-align:right;"><?php echo number_format($wh_pkgs,2,'.',''); ?></td>
                        <td style="text-align:right;"><?php echo number_format($wh_kgs,2,'.',''); ?></td>
                        <td style="text-align:right;"><?php echo number_format($wh_amount,2,'.',','); ?></td>
                        <td style="text-align:right;"><?php echo number_format($wh_vat,2,'.',','); ?></td>
                    </tr>
                    <tr><td height="30" colspan="5"></td></tr>
                    <tr>
                        <td style="text-align:right;" colspan="3">VAT (15%) :</td>
                        <td style="text-align:right;"><?php echo number_format($wh_vat,2,'.',','); ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-sm-12 text-left" style="margin-top:30px; font-family:courier;">
                    <b style="text-transform: uppercase; font-size:14px;"><?php print $website_title; ?></b><br><br>
                    <span style="font-size:14px;">Copy to: 1. Superintendant, Custom, Excise & VAT.</span><br>
                    <span style="font-size:14px; margin-left:75px;">2. Shed In-Charge.</span><br>
                </div>
            </div>

            <!-- Page break after each warehouse -->
            <div style="page-break-after: always;"></div>
            <?php
            // reset per-warehouse totals
            $wh_pkgs = 0; $wh_kgs = 0; $wh_amount = 0; $wh_vat = 0;
            $table_open = false;
        }

        // On new warehouse, print header
        if(!$table_open){
            $current_wh_id = $r['w_house_id'];
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-sm" id="table">
                        <thead>
                            <tr>
                                <td style="border:0px;" colspan="4">
                                    <b style="text-transform: uppercase; font-size:20px; font-family:courier;">
                                        <?php print $website_title; ?>
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td style="border:0px;" colspan="5">
                                    <b style="text-transform: uppercase; font-size:15px; font-family:courier;">
                                        To : <?php echo $r['short_key_word']; ?>
                                    </b><br>
                                    <span style="font-size:15px; font-family:courier;">
                                        <?php echo $r['warehouse_name']; ?>, <?php echo $r['warehouse_address']; ?>
                                    </span><br>
                                    <span style="font-size:15px; font-family:courier;">
                                        Re : Individual garden wise purchase in Sale No. <?php print $sale_no; ?> <?php print $auction_center; ?> Season <?php print $season; ?>
                                    </span>
                                </td>
                            </tr>
                            <tr><td height="10" colspan="5" style="border:0px;"></td></tr>
                            <tr>
                                <td style="text-align:center; border:1px solid;">Garden Information</td>
                                <td style="text-align:center; border:1px solid;">Pkgs.</td>
                                <td style="text-align:center; border:1px solid;">Total Kg.</td>
                                <td style="text-align:center; border:1px solid;">Amount</td>
                                <td style="text-align:center; border:1px solid;">Vat 15%</td>
                            </tr>
                        </thead>
                        <tbody>
            <?php
            $table_open = true;
        }

        // print data row for this garden/buyer_type
        ?>
        <tr>
            <td style="text-align:left;border:0">
                <?php echo $r['garden_name']; ?>
                <?php echo ($r['buyer_type']=='Export') ? '<b style="color:red;"> - '.$r['buyer_type'].'</b>' : ''; ?>
            </td>
            <td style="text-align:right;border:0"><?php echo number_format($r['buyer_bag'],2,'.',''); ?></td>
            <td style="text-align:right;border:0"><?php echo number_format($r['buyer_kgs'],2,'.',''); ?></td>
            <td style="text-align:right;border:0"><?php echo number_format($r['sold_amount'],2,'.',','); ?></td>
            <td style="text-align:right;border:0"><?php echo number_format($row_vat,2,'.',''); ?></td>
        </tr>
        <?php

        // accumulate per-warehouse totals
        $wh_pkgs   += $r['buyer_bag'];
        $wh_kgs    += $r['buyer_kgs'];
        $wh_amount += $r['sold_amount'];
        $wh_vat    += $row_vat;

        // accumulate grand totals
        $grand_pkgs   += $r['buyer_bag'];
        $grand_kgs    += $r['buyer_kgs'];
        $grand_amount += $r['sold_amount'];
        $grand_vat    += $row_vat;
    }

    // Close the last warehouse section with its Shed Total + footer
    if($table_open){
        ?>
        </tbody>
        </table>

        <table class="table table-sm" id="table2" style="margin-top:5px;">
            <tbody>
                <tr style="border:1px solid;">
                    <td style="text-align:right;">Shed Total :</td>
                    <td style="text-align:right;"><?php echo number_format($wh_pkgs,2,'.',''); ?></td>
                    <td style="text-align:right;"><?php echo number_format($wh_kgs,2,'.',''); ?></td>
                    <td style="text-align:right;"><?php echo number_format($wh_amount,2,'.',','); ?></td>
                    <td style="text-align:right;"><?php echo number_format($wh_vat,2,'.',','); ?></td>
                </tr>
                <tr><td height="30" colspan="5"></td></tr>
                <tr>
                    <td style="text-align:right;" colspan="3">VAT (15%) :</td>
                    <td style="text-align:right;"><?php echo number_format($wh_vat,2,'.',','); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <div class="col-sm-12 text-left" style="margin-top:30px; font-family:courier;">
                <b style="text-transform: uppercase; font-size:14px;"><?php print $website_title; ?></b><br><br>
                <span style="font-size:14px;">Copy to: 1. Superintendant, Custom, Excise & VAT.</span><br>
                <span style="font-size:14px; margin-left:75px;">2. Shed In-Charge.</span><br>
            </div>
        </div>
        
        <!-- Page break after last warehouse -->
        <div style="page-break-after: always;"></div>
        <?php
    }
    ?>

    <!-- GRAND TOTALS (all warehouses) -->
    <hr style="margin:30px 0;">
    <table class="table table-sm" id="table2">
        <tbody>
            <tr style="border:1px solid;">
                <td style="text-align:right;">Shed Total :</td>
                <td style="text-align:right;"><?php echo number_format($grand_pkgs,2,'.',''); ?></td>
                <td style="text-align:right;"><?php echo number_format($grand_kgs,2,'.',''); ?></td>
                <td style="text-align:right;"><?php echo number_format($grand_amount,2,'.',','); ?></td>
                <td style="text-align:right;"><?php echo number_format($grand_vat,2,'.',','); ?></td>
            </tr>
            <tr><td height="30" colspan="5"></td></tr>
            <tr>
                <td style="text-align:right;" colspan="3">VAT (15%) :</td>
                <td style="text-align:right;"><?php echo number_format($grand_vat,2,'.',','); ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
<?php
} else {
    print "<div id=\"page\"><h1>An Error Was Encountered</h1><p><b>No matching record found. </b></p></div>";
}
?>
<?php } ?>



</div>
<!-- /container -->

<script>
// print area
function printDiv(divName) 
{
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