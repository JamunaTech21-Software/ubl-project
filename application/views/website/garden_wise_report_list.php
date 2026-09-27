<!DOCTYPE html>
<html lang="en">
<head>
<title>GARDEN WISE OFFERING REPORT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$company_id = (!empty($this->input->get('company_id', TRUE))) ? $this->input->get('company_id', TRUE) : '';
$report_type = (!empty($this->input->get('report_type', TRUE))) ? $this->input->get('report_type', TRUE) : '';

// garden name
$query = $this->db->query("SELECT * FROM `garden_and_company_setup` WHERE `id` = '".$garden_id."' "); 
$garden_name_result = $query->row_array();

$this->db->select("company_name"); 
$this->db->from('company_setup');
$this->db->where('id', $company_id);
$query = $this->db->get();
$fetch_company_info = $query->row_array();
?>

<div id="printableArea" class="container">
<style>
body{
	font-family:verdana !important;
	background-color: white !important;
}
.table{
	font-family:verdana;
	width: 100% !important;
}
#table{
	border-color:black;
}
#table tr td{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	GARDEN WISE OFFERING REPORT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> 
	</th>
</tr>
<?php if(!empty($company_id)){ ?> 
<tr>
	<th style="text-transform:uppercase; text-align:left; border:0px;">
		GROUP: <?php print $fetch_company_info['company_name']; ?>
	</th>
</tr>
<?php } ?>
</thead>
</table>
</div>
</div>

<!-- ALL GARDEN SUMMARY -->
<?php if(!empty($report_type) && $report_type === 'Offerings Details' && empty($garden_id)) { ?>
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th rowspan="2" style="text-align:center;">SL</th>
    <th rowspan="2" style="text-align:left;">Garden Name</th>
	<th colspan="2" style="text-align:center;">Leaf</th>
	<th colspan="2" style="text-align:center;">Dust</th>
	<th colspan="2" style="text-align:center;">Total</th>
	<th rowspan="2" class="no-print" style="text-align:right;">Grade</th>
	<th rowspan="2" class="no-print" style="text-align:right;">Warehouse</th>
</tr>
<tr>
	<th style="text-align:right;">Net Kgs</th>
	<th style="text-align:right;">Total Bag</th>
	<th style="text-align:right;">Net Kgs</th>
	<th style="text-align:right;">Total Bag</th>
	<th style="text-align:right;">Kgs</th>
	<th style="text-align:right;">Bags</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;
$leaf_net_kgs = 0;
$leaf_total_bag = 0;
$dust_net_kgs = 0;
$dust_total_bag = 0;
$this->db->select("
	garden_and_company_setup.garden_name, 
	invoice_entry.garden_id, 
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN net_kgs END),0)) AS leaf_net_kgs, 
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)) AS leaf_total_bag,
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN net_kgs END),0)) AS dust_net_kgs, 
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)) AS dust_total_bag
");
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
if(!empty($company_id)){
$this->db->where('garden_and_company_setup.company_name', $company_id);
}
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:center; border:0px;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:0px;"><a target="blink" href="<?php echo site_url('MY_Controller/garden_wise_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>"><?php echo $row1['garden_name']; ?></a></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row1['leaf_net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row1['leaf_total_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row1['dust_net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row1['dust_total_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row1['leaf_net_kgs'] + $row1['dust_net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row1['leaf_total_bag'] + $row1['dust_total_bag'],2,'.',''); ?></td>
	<td class="no-print" style="text-align:right; text-transform:uppercase; border:0px;">
		<a target="blink" href="<?php echo site_url('MY_Controller/grade_wise_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>">View</a>
	</td>
	<td class="no-print" style="text-align:right; text-transform:uppercase; border:0px;">
		<a target="blink" href="<?php echo site_url('MY_Controller/warehouse_wise_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>">View</a>
	</td>
</tr>
<?php 
	$leaf_net_kgs += $row1['leaf_net_kgs'];
	$leaf_total_bag += $row1['leaf_total_bag'];
	$dust_net_kgs += $row1['dust_net_kgs'];
	$dust_total_bag += $row1['dust_total_bag'];
}

}else{
	print "<tr><td colspan=\"8\">No matching record found.</td></tr>";
}
?>
<tr>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($leaf_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $leaf_total_bag; ?></th>
	<th style="text-align:right;"><?php echo number_format($dust_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $dust_total_bag; ?></th>
	<th style="text-align:right;"><?php echo number_format($leaf_net_kgs + $dust_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($leaf_total_bag + $dust_total_bag,2,'.',''); ?></th>
	<th class="no-print"></th>
	<th class="no-print"></th>
</tr>
</tbody>
</table>
</div>
</div>
<?php } ?>

<?php if(!empty($report_type) && $report_type === 'Offerings Bags' && empty($garden_id)) { ?>
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:center;">SL</th>
    <th style="text-align:left;">Garden Name</th>
	<th style="text-align:center;">PKGS.</th>
	<th style="text-align:center;">KGS.</th>
</tr>
<tr>
</thead>
<tbody>
<?php
$sl=1;
$total_bag = 0;
$total_kgs = 0;
$this->db->select("
	garden_and_company_setup.garden_name, 
	invoice_entry.garden_id, 
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN net_kgs END),0)) AS leaf_net_kgs, 
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)) AS leaf_total_bag,
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN net_kgs END),0)) AS dust_net_kgs, 
	
	(IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)) AS dust_total_bag
");
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
if(!empty($company_id)){
$this->db->where('garden_and_company_setup.company_name', $company_id);
}
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:center; border:0px;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:0px;"><a target="blink" href="<?php echo site_url('MY_Controller/garden_wise_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>"><?php echo $row1['garden_name']; ?></a></td>
	<td style="text-align:center; border:0px;"><?php echo number_format($row1['leaf_total_bag'] + $row1['dust_total_bag'],0,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row1['leaf_net_kgs'] + $row1['dust_net_kgs'],2,'.',''); ?></td>
</tr>
<?php 
	$total_bag += $row1['leaf_total_bag'] + $row1['dust_total_bag'];
	$total_kgs += $row1['leaf_net_kgs'] + $row1['dust_net_kgs'];
}

}else{
	print "<tr><td colspan=\"4\">No matching record found.</td></tr>";
}
?>
<tr>
	<th style="text-align:right;" colspan="2">Total : </th>
	<th style="text-align:center;"><?php echo $total_bag; ?></th>
	<th style="text-align:right;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
</tr>
</tbody>
</table>
</div>
</div>
<?php } ?>


<!-- GARDEN WISE DETAILS -->
<?php if(!empty($garden_id)){ ?>
<div class="row">
<div class="col-sm-12">
<?php
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;
//query
$this->db->select("invoice_entry.w_house_id, warehouse_setup.short_key_word, invoice_entry.old_season"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id, invoice_entry.old_season');
$this->db->order_by('invoice_entry.id ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<table class="table table-sm" id="table">
<thead>
<tr>
    <th colspan="9" style="font-size:14px !important; border:0px !important; text-transform:uppercase;"><?php print $garden_name_result['garden_name']; ?> / <?php print $row1['short_key_word']; ?> / <?php print $row1['old_season']; ?></th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">Sale No</th>
    <th style="text-align:center; border:1px solid;">Lot No</th>
    <th style="text-align:center; border:1px solid;">Inv.</th>
    <th style="text-align:center; border:1px solid;">Grade Name</th>
	<th style="text-align:right; border:1px solid;">WT P/Bag</th>
	<th style="text-align:right; border:1px solid;">Net WT P/Bag</th>
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">Net Kgs</th>
	<th style="text-align:right; border:1px solid;">Chest</th>
</tr>
</thead>
<tbody>
<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;

// query
$this->db->select("sale_no"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season',$season);
$this->db->where('auction_center',$auction_center);
$this->db->where("sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('garden_id',$garden_id);
$this->db->where('w_house_id',$row1['w_house_id']);
$this->db->where('old_season',$row1['old_season']);
$this->db->where('soft_delete', 1);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
$this->db->select("invoice_entry.catalouge_name, invoice_entry.sale_no, invoice_entry.lot_no, invoice_entry.w_inv_no, CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, invoice_entry.gross_wt_per_bag, invoice_entry.net_wt_per_bag, invoice_entry.total_kgs, invoice_entry.net_kgs, invoice_entry.chest_number"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no',$row2['sale_no']);
$this->db->where('invoice_entry.garden_id',$garden_id);
$this->db->where('w_house_id',$row1['w_house_id']);
$this->db->where('old_season',$row1['old_season']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
	
	if($row3['catalouge_name']=='Leaf'){
		$text_color = "color:green;";
	}else if($row3['catalouge_name']=='Dust'){
		$text_color = "color:red;";
	}else if($row3['catalouge_name']=='Supplement'){
		$text_color = "color:blue;";
	}
?>

<tr style="<?php print $text_color; ?>">

    <td style="text-align:center; border:0px;"><?php echo $row3['sale_no']; ?></td>
    <td style="text-align:center; border:0px;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase; border:0px;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase; border:0px;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['gross_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	
</tr>	

<?php
	$total_kgs += $row3['total_kgs'];
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	
}  // 3rd loop
?>

<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
</tr>

<?php
	$g_total_kgs += $total_kgs;
	$g_net_kgs += $net_kgs;
	$g_chest_number += $chest_number;

} // 2nd loop	
?>

<tr>
	<th colspan="9" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
</table>
<?php
	$n_total_kgs += $g_total_kgs;
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	
} // 1st loop
?>
<table class="table table-sm" id="table">
<tfoot>
<tr>
	<th style="text-align:center; font-family:courier; font-size:16px !important; border:0px;">TOTAL KGS: <?php echo number_format($n_total_kgs,2,'.',''); ?> / NET KGS: <?php echo number_format($n_net_kgs,2,'.',''); ?> / TOTAL BAGS: <?php echo $n_chest_number; ?></th>
</tr>
</tfoot>
</table>
<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>

</div> <!-- col -->
</div> <!-- row -->
<?php } ?>

</div> 
<!-- container -->

<script>
// print area
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
}
</script>

</body>
</html>