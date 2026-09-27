<!DOCTYPE html>
<html lang="en">
<head>
<title>GARDEN SALES REPORT</title>
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
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';
$report_no = (!empty($this->input->get('report', TRUE))) ? $this->input->get('report', TRUE) : '';
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
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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
	size:auto;
	
	@bottom-center {
		content: counter(page);
		font-style: italic
	}
}
</style>


<?php if($report_no=='1'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id=&report=3'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> In Details</a>

		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="9" style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	GARDEN SALES REPORT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th colspan="9" style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> 
	</th>
</tr>
<tr>
	<th></th>
	<th></th>
	<th colspan="2" style="text-align:center; color:black; border-right:1px solid;">Offering</th>
	<th colspan="5" style="text-align:center; color:green;">Sold</th>
</tr>
<tr>
	<th style="text-align:left; color:green;">Garden Name</th>
	<th></th>
	
	<th style="text-align:right; color:black;">Bags</th>
	<th style="text-align:right; color:black; border-right:1px solid;">Kgs</th>
	
	<th style="text-align:right; color:green;">Bags</th>
	<th style="text-align:right; color:green;">Kgs</th>
	<th style="text-align:right; color:green;">Amount</th>
	<th style="text-align:right; color:green;">Avg.</th>
	<th style="text-align:right; color:green;">%</th>
</tr>
</thead>
<tbody>
<?php
$nt_chest_number=0;
$nt_net_kgs=0;

$n_leaf_offering=0;
$n_dust_offering=0;

$n_leaf_offering_kgs=0;
$n_dust_offering_kgs=0;

$nt_sold_bag_l=0;
$nt_sold_kgs_l=0;
$nt_sold_bag_d=0;
$nt_sold_kgs_d=0;
$nt_sold_tk_l=0;
$nt_sold_tk_d=0;

$nt_wd_bag_l=0;
$nt_wd_kgs_l=0;
$nt_wd_bag_d=0;
$nt_wd_kgs_d=0;

$nt_out_bag_l=0;
$nt_out_kgs_l=0;
$nt_out_bag_d=0;
$nt_out_kgs_d=0;
//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){

// reset value
$n_net_kgs = 0;
$n_chest_number = 0;
$n_sold_amount = 0;
$n_sold_bag_l = 0;
$n_sold_kgs_l = 0;
$n_sold_bag_d = 0;
$n_sold_kgs_d = 0;
$n_sold_tk_l = 0;
$n_sold_tk_d = 0;
$n_wd_bag_l = 0;
$n_wd_kgs_l = 0;
$n_wd_bag_d = 0;
$n_wd_kgs_d = 0;
$n_out_bag_l = 0;
$n_out_kgs_l = 0;
$n_out_bag_d = 0;
$n_out_kgs_d = 0;
// query
$this->db->select("
	invoice_entry.sale_no, 
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	invoice_entry.valuation, 
	IFNULL(sale_entry.sold_price,0) AS sold_price, 
	invoice_entry.net_kgs, 
	invoice_entry.chest_number, 
	invoice_entry.remarks, 
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)
	) AS leaf_total_bag,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN net_kgs END),0)
	) AS leaf_net_kgs, 
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)
	) AS dust_total_bag,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN net_kgs END),0)
	) AS dust_net_kgs,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS leaf_sold_amount,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS dust_sold_amount
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){

if($row3['sold_price'] > 0){
	
	$sold_bag_l = $row3['leaf_total_bag']; $sold_kgs_l = $row3['leaf_net_kgs']; $sold_tk_l = $row3['leaf_sold_amount']; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = 0; $out_kgs_l = 0;
	$sold_bag_d = $row3['dust_total_bag']; $sold_kgs_d = $row3['dust_net_kgs']; $sold_tk_d = $row3['dust_sold_amount']; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = 0; $out_kgs_d = 0;
	
}else if(!empty($row3['remarks'])){
	
	$sold_bag_l = 0; $sold_kgs_l = 0; $sold_tk_l = 0; $wd_bag_l = $row3['leaf_total_bag']; $wd_kgs_l = $row3['leaf_net_kgs']; $out_bag_l = 0;$out_kgs_l = 0;
	$sold_bag_d = 0; $sold_kgs_d = 0; $sold_tk_d = 0; $wd_bag_d = $row3['dust_total_bag']; $wd_kgs_d = $row3['dust_net_kgs']; $out_bag_d = 0;$out_kgs_d = 0;
	
}else{
	
	$sold_bag_l = 0; $sold_kgs_l = 0; $sold_tk_l = 0; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = $row3['leaf_total_bag']; $out_kgs_l = $row3['leaf_net_kgs'];
	$sold_bag_d = 0; $sold_kgs_d = 0; $sold_tk_d = 0; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = $row3['dust_total_bag']; $out_kgs_d = $row3['dust_net_kgs'];
}

$n_net_kgs += $row3['net_kgs'];
$n_chest_number += $row3['chest_number'];
//$n_sold_amount += $row3['sold_amount'];

$n_sold_bag_l += $sold_bag_l;
$n_sold_kgs_l += $sold_kgs_l;
$n_sold_bag_d += $sold_bag_d;
$n_sold_kgs_d += $sold_kgs_d;
$n_sold_tk_l += $sold_tk_l;
$n_sold_tk_d += $sold_tk_d;

$n_wd_bag_l += $wd_bag_l;
$n_wd_kgs_l += $wd_kgs_l;
$n_wd_bag_d += $wd_bag_d;
$n_wd_kgs_d += $wd_kgs_d;

$n_out_bag_l += $out_bag_l;
$n_out_kgs_l += $out_kgs_l;
$n_out_bag_d += $out_bag_d;
$n_out_kgs_d += $out_kgs_d;
	
}  // 2nd loop

$leaf_offering = $n_sold_bag_l + $n_wd_bag_l + $n_out_bag_l;
$leaf_offering_kgs = $n_sold_kgs_l + $n_wd_kgs_l + $n_out_kgs_l;

$dust_offering = $n_sold_bag_d + $n_wd_bag_d + $n_out_bag_d;
$dust_offering_kgs = $n_sold_kgs_d + $n_wd_kgs_d + $n_out_kgs_d;
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><a target="blink" href="<?php echo site_url('MY_Controller/gs_report_list_all_center?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=2'); ?>"><b><?php print ($leaf_offering > 0) ? $row1['garden_name'] : ''; ?></b></a></td>
	<td style="text-align:right; border:0px;"><b>Leaf</b></td>
	
	<td style="text-align:right; color:black; border:0px;"><?php print $leaf_offering; ?></td>
	<td style="text-align:right; color:black; border:0px; border-right:1px solid;"><?php print number_format($leaf_offering_kgs,2,'.',''); ?></td>
	
	<td style="text-align:right; color:green; border:0px;"><?php print $n_sold_bag_l; ?></td>
	<td style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_kgs_l,2,'.',''); ?></td>
	<td style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_tk_l,2,'.',','); ?></td>
	<td style="text-align:right; color:green; border:0px;">
	<?php 
		print ($n_sold_tk_l > 0 && $n_sold_kgs_l > 0) ? number_format($n_sold_tk_l / $n_sold_kgs_l,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; color:green; border:0px;">
	<?php 
		print ($n_sold_bag_l > 0 && $leaf_offering > 0) ? number_format(($n_sold_bag_l * 100)/$leaf_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=2'); ?>"><b><?php print ($leaf_offering == 0) ? $row1['garden_name'] : ''; ?></b></a></td>
	<td style="text-align:right; border:0px;"><b>Dust</b></td>
	
	<td style="text-align:right; color:black; border:0px;"><?php print $dust_offering; ?></td>
	<td style="text-align:right; color:black; border:0px; border-right:1px solid;"><?php print number_format($dust_offering_kgs,2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px; color:green;"><?php print $n_sold_bag_d; ?></td>
	<td style="text-align:right; border:0px; color:green;"><?php print number_format($n_sold_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; border:0px; color:green;"><?php print number_format($n_sold_tk_d,2,'.',','); ?></td>
	<td style="text-align:right; border:0px; color:green;">
	<?php 
		print ($n_sold_tk_d > 0 && $n_sold_kgs_d > 0) ? number_format($n_sold_tk_d / $n_sold_kgs_d,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; color:green;">
	<?php 
		print ($n_sold_bag_d > 0 && $dust_offering > 0) ? number_format(($n_sold_bag_d * 100) / $dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<th colspan="2" style="text-align:right; border:0px;">Sub Total: </th>
	
	<th style="text-align:right; color:black; border:0px;"><?php print $n_chest_number; ?></th>
	<th style="text-align:right; color:black; border:0px; border-right:1px solid;"><?php print number_format($n_net_kgs,2,'.',''); ?></th>
	
	<th style="text-align:right; color:green; border:0px;"><?php print $n_sold_bag_l + $n_sold_bag_d; ?></th>
	<th style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_kgs_l + $n_sold_kgs_d,2,'.',','); ?></th>
	<th style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_tk_l + $n_sold_tk_d,2,'.',','); ?></th>
	<th style="text-align:right; color:green; border:0px;">
	<?php 
		print (($n_sold_tk_l + $n_sold_tk_d) > 0 && ($n_sold_kgs_l + $n_sold_kgs_d) > 0) ? number_format(($n_sold_tk_l + $n_sold_tk_d) / ($n_sold_kgs_l + $n_sold_kgs_d),2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; color:green; border:0px;">
		<?php print number_format((($n_sold_bag_l + $n_sold_bag_d) * 100)/$n_chest_number,2,'.',''); ?>
	</th>
</tr>
<tr>
	<td colspan="15" style="border:0px;"></td>
</tr>
<?php
$nt_chest_number += $n_chest_number;
$nt_net_kgs += $n_net_kgs;

$n_leaf_offering +=$leaf_offering;
$n_dust_offering +=$dust_offering;

$n_leaf_offering_kgs += $leaf_offering_kgs;
$n_dust_offering_kgs += $dust_offering_kgs;

$nt_sold_bag_l += $n_sold_bag_l;
$nt_sold_kgs_l += $n_sold_kgs_l;
$nt_sold_bag_d += $n_sold_bag_d;
$nt_sold_kgs_d += $n_sold_kgs_d;
$nt_sold_tk_l += $n_sold_tk_l;
$nt_sold_tk_d += $n_sold_tk_d;

$nt_wd_bag_l += $n_wd_bag_l;
$nt_wd_kgs_l += $n_wd_kgs_l;
$nt_wd_bag_d += $n_wd_bag_d;
$nt_wd_kgs_d += $n_wd_kgs_d;

$nt_out_bag_l += $n_out_bag_l;
$nt_out_kgs_l += $n_out_kgs_l;
$nt_out_bag_d += $n_out_bag_d;
$nt_out_kgs_d += $n_out_kgs_d;
	
} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="15" style="border:0px;"></td>
</tr>
<tr>
	<th colspan="2" style="text-align:right; font-size:13px !important;">LEAF TOTAL: </th>
	
	<th style="text-align:right; color:black; font-size:13px !important;"><?php print $n_leaf_offering; ?></th>
	<th style="text-align:right; color:black; font-size:13px !important;"><?php print number_format($n_leaf_offering_kgs,2,'.',''); ?></th>
	
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print $nt_sold_bag_l; ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print number_format($nt_sold_kgs_l,2,'.',''); ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print number_format($nt_sold_tk_l,2,'.',','); ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;">
	<?php 
		print ($nt_sold_tk_l > 0 && $nt_sold_kgs_l > 0) ? number_format($nt_sold_tk_l / $nt_sold_kgs_l,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; color:green; font-size:13px !important;">
		<?php 
			print ($nt_sold_bag_l!='0' && $nt_chest_number!='0') ? number_format(($nt_sold_bag_l * 100)/$nt_chest_number,2,'.','') : '0.00'; 
		?>
	</th>
</tr>
<tr>
	<th colspan="2" style="text-align:right; font-size:13px !important;">DUST TOTAL: </th>
	
	<th style="text-align:right; color:black; font-size:13px !important;"><?php print $n_dust_offering; ?></th>
	<th style="text-align:right; color:black; font-size:13px !important;"><?php print number_format($n_dust_offering_kgs,2,'.',''); ?></th>
	
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print $nt_sold_bag_d; ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print number_format($nt_sold_kgs_d,2,'.',''); ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print number_format($nt_sold_tk_d,2,'.',','); ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;">
	<?php 
		print ($nt_sold_tk_d > 0 && $nt_sold_kgs_d > 0) ? number_format($nt_sold_tk_d / $nt_sold_kgs_d,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; color:green; font-size:13px !important;">
		<?php 
			print ($nt_sold_bag_d!='0' && $nt_chest_number!='0') ? number_format(($nt_sold_bag_d * 100)/$nt_chest_number,2,'.','') : '0.00'; 
		?>
	</th>
</tr>
<tr>
	<th colspan="2" style="text-align:right; font-size:13px !important;">NET TOTAL: </th>
	
	<th style="text-align:right; color:black; font-size:13px !important;"><?php print $nt_chest_number; ?></th>
	<th style="text-align:right; color:black; font-size:13px !important;"><?php print number_format($nt_net_kgs,2,'.',''); ?></th>
	
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print $nt_sold_bag_l + $nt_sold_bag_d; ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print number_format($nt_sold_kgs_l + $nt_sold_kgs_d,2,'.',''); ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;"><?php print number_format($nt_sold_tk_l + $nt_sold_tk_d,2,'.',','); ?></th>
	<th style="text-align:right; color:green; font-size:13px !important;">
	<?php 
		print (($nt_sold_tk_l + $nt_sold_tk_d) > 0 && ($nt_sold_kgs_l + $nt_sold_kgs_d) > 0) ? number_format(($nt_sold_tk_l + $nt_sold_tk_d) / ($nt_sold_kgs_l + $nt_sold_kgs_d),2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; color:green; font-size:13px !important;">
		<?php 
			print (($nt_sold_bag_l + $nt_sold_bag_d)!='0' && $nt_chest_number!='0') ? number_format((($nt_sold_bag_l + $nt_sold_bag_d) * 100)/$nt_chest_number,2,'.','') : '0.00'; 
		?>
	</th>
</tr>	
</tfoot>
</table>
</div>
</div>
</div>
<?php } ?>


<?php if($report_no=='2'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-11 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><?php print $owner_short_address; ?></span>
		<br />
		<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			GARDEN SALES REPORT
		</b>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-4 text-left">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4 text-right">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
	</div>
</div>

<?php
$query = $this->db->query("SELECT * FROM `garden_and_company_setup` WHERE id='".$garden_id."' "); 
$garden_name_result = $query->row_array();
?>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<?php
$n_net_kgs = 0;
$n_chest_number = 0;
$n_sold_amount = 0;
$n_sold_bag_l = 0;
$n_sold_kgs_l = 0;
$n_sold_bag_d = 0;
$n_sold_kgs_d = 0;
$n_sold_tk_l = 0;
$n_sold_tk_d = 0;
$n_wd_bag_l = 0;
$n_wd_kgs_l = 0;
$n_wd_bag_d = 0;
$n_wd_kgs_d = 0;
$n_out_bag_l = 0;
$n_out_kgs_l = 0;
$n_out_bag_d = 0;
$n_out_kgs_d = 0;
//query
$this->db->select("invoice_entry.w_house_id, warehouse_setup.short_key_word"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id');
$this->db->order_by('warehouse_setup.short_key_word ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>
<tr>
    <th colspan="11" style="font-size:14px !important; text-transform:uppercase; border:0px !important;">
		<?php print $garden_name_result['garden_name']; ?> / <?php print $row1['short_key_word']; ?>
	</th>
</tr>
</thead>
<tbody>	
<?php
$g_net_kgs = 0;
$g_chest_number = 0;
$g_sold_amount = 0;
$g_sold_bag_l = 0;
$g_sold_kgs_l = 0;
$g_sold_bag_d = 0;
$g_sold_kgs_d = 0;
$g_sold_tk_l = 0;
$g_sold_tk_d = 0;
$g_wd_bag_l = 0;
$g_wd_kgs_l = 0;
$g_wd_bag_d = 0;
$g_wd_kgs_d = 0;
$g_out_bag_l = 0;
$g_out_kgs_l = 0;
$g_out_bag_d = 0;
$g_out_kgs_d = 0;
$w_house_id = $row1['w_house_id'];

// query
$this->db->select("sale_no, auction_center, sale_date"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season',$season);
$this->db->where("sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('w_house_id',$w_house_id);
$this->db->where('garden_id',$garden_id);
$this->db->where('soft_delete', 1);
$this->db->group_by('sale_no, auction_center');
$this->db->order_by('sale_date ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<td colspan="11" style="text-align:left;"><b>Sale No: <?php echo $row2['sale_no']; ?> &nbsp; &nbsp; Center: <?php echo $row2['auction_center']; ?> &nbsp; &nbsp; Sale Date: <?php echo date("d-m-Y",strtotime($row2['sale_date'])); ?></b></td>
</tr>
<tr>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Invoice</th>
    <th style="text-align:left;">Grade</th>
	<th style="text-align:right;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
	<th style="text-align:center;">Value</th>
	<th style="text-align:center;">Sold Price</th>
	<th style="text-align:left;">Buyer1</th>
	<th style="text-align:left;">Buyer2</th>
	<th style="text-align:left;">Buyer3</th>
	<th style="text-align:right;">Amount</th>
</tr>

<?php
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
$sold_amount = 0;
$total_sold_bag_l = 0;
$total_sold_kgs_l = 0;
$total_sold_bag_d = 0;
$total_sold_kgs_d = 0;
$total_sold_tk_l = 0;
$total_sold_tk_d = 0;
$total_wd_bag_l = 0;
$total_wd_kgs_l = 0;
$total_wd_bag_d = 0;
$total_wd_kgs_d = 0;
$total_out_bag_l = 0;
$total_out_kgs_l = 0;
$total_out_bag_d = 0;
$total_out_kgs_d = 0;
// query
$this->db->select(" 
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	invoice_entry.valuation, 
	IFNULL(sale_entry.sold_price,0) AS sold_price, 
	invoice_entry.net_kgs, 
	invoice_entry.chest_number, 
	invoice_entry.remarks, 
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)
	) AS leaf_total_bag,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN net_kgs END),0)
	) AS leaf_net_kgs, 
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)
	) AS dust_total_bag,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN net_kgs END),0)
	) AS dust_net_kgs,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS leaf_sold_amount,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS dust_sold_amount
");
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.sale_no',$row2['sale_no']);
$this->db->where('invoice_entry.auction_center',$row2['auction_center']);
$this->db->where('invoice_entry.w_house_id',$w_house_id);
$this->db->where('invoice_entry.garden_id',$garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.sale_date ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
    <td style="text-align:center;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:left; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:center;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:center; color:#FF69B4;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	<td style="text-align:center;">
	<?php 
		if($row3['sold_price'] > 0){
			
			print "<font color=green>".number_format($row3['sold_price'],2,'.','')."</font>";
			$sold_bag_l = $row3['leaf_total_bag']; $sold_kgs_l = $row3['leaf_net_kgs']; $sold_tk_l = $row3['leaf_sold_amount']; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = 0; $out_kgs_l = 0;
			$sold_bag_d = $row3['dust_total_bag']; $sold_kgs_d = $row3['dust_net_kgs']; $sold_tk_d = $row3['dust_sold_amount']; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = 0; $out_kgs_d = 0;
			
		}else if(!empty($row3['remarks'])){
			
			print "<b style=\"color:red;\">W/D</b>";
			$sold_bag_l = 0; $sold_kgs_l = 0; $sold_tk_l = 0; $wd_bag_l = $row3['leaf_total_bag']; $wd_kgs_l = $row3['leaf_net_kgs']; $out_bag_l = 0;$out_kgs_l = 0;
			$sold_bag_d = 0; $sold_kgs_d = 0; $sold_tk_d = 0; $wd_bag_d = $row3['dust_total_bag']; $wd_kgs_d = $row3['dust_net_kgs']; $out_bag_d = 0;$out_kgs_d = 0;
			
		}else{
			
			print "<b style=\"color:#006994;\">Out</b>";
			$sold_bag_l = 0; $sold_kgs_l = 0; $sold_tk_l = 0; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = $row3['leaf_total_bag']; $out_kgs_l = $row3['leaf_net_kgs'];
			$sold_bag_d = 0; $sold_kgs_d = 0; $sold_tk_d = 0; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = $row3['dust_total_bag']; $out_kgs_d = $row3['dust_net_kgs'];
		}
	?>
	</td>
	<td style="text-align:left; color:#FF1493; text-transform:uppercase;"><?php echo $row3['buyer1']; ?></td>
	<td style="text-align:left; color:#FF1493; text-transform:uppercase;"><?php echo $row3['buyer2']; ?></td>
	<td style="text-align:left; color:#FF1493; text-transform:uppercase;"><?php echo $row3['buyer3']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
</tr>
<?php
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	$sold_amount += $row3['sold_amount'];
	
	$total_sold_bag_l += $sold_bag_l;
	$total_sold_kgs_l += $sold_kgs_l;
	$total_sold_bag_d += $sold_bag_d;
	$total_sold_kgs_d += $sold_kgs_d;
	$total_sold_tk_l += $sold_tk_l;
	$total_sold_tk_d += $sold_tk_d;
	
	$total_wd_bag_l += $wd_bag_l;
	$total_wd_kgs_l += $wd_kgs_l;
	$total_wd_bag_d += $wd_bag_d;
	$total_wd_kgs_d += $wd_kgs_d;
	
	$total_out_bag_l += $out_bag_l;
	$total_out_kgs_l += $out_kgs_l;
	$total_out_bag_d += $out_bag_d;
	$total_out_kgs_d += $out_kgs_d;
	
}  // 3rd loop
?>

<tr>
	<th></th>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:center;"><?php echo $chest_number; ?></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;"><?php echo number_format($sold_amount,2,'.',','); ?></th>
</tr>

<?php
	$g_net_kgs += $net_kgs;
	$g_chest_number += $chest_number;
	$g_sold_amount += $sold_amount;
	
	$g_sold_bag_l += $total_sold_bag_l;
	$g_sold_kgs_l += $total_sold_kgs_l;
	$g_sold_bag_d += $total_sold_bag_d;
	$g_sold_kgs_d += $total_sold_kgs_d;
	$g_sold_tk_l += $total_sold_tk_l;
	$g_sold_tk_d += $total_sold_tk_d;
	
	$g_wd_bag_l += $total_wd_bag_l;
	$g_wd_kgs_l += $total_wd_kgs_l;
	$g_wd_bag_d += $total_wd_bag_d;
	$g_wd_kgs_d += $total_wd_kgs_d;
	
	$g_out_bag_l += $total_out_bag_l;
	$g_out_kgs_l += $total_out_kgs_l;
	$g_out_bag_d += $total_out_bag_d;
	$g_out_kgs_d += $total_out_kgs_d;

} // 2nd loop	
?>
<tr>
	<th colspan="11" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
<?php
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	$n_sold_amount += $g_sold_amount;
	
	$n_sold_bag_l += $g_sold_bag_l;
	$n_sold_kgs_l += $g_sold_kgs_l;
	$n_sold_bag_d += $g_sold_bag_d;
	$n_sold_kgs_d += $g_sold_kgs_d;
	$n_sold_tk_l += $g_sold_tk_l;
	$n_sold_tk_d += $g_sold_tk_d;
	
	$n_wd_bag_l += $g_wd_bag_l;
	$n_wd_kgs_l += $g_wd_kgs_l;
	$n_wd_bag_d += $g_wd_bag_d;
	$n_wd_kgs_d += $g_wd_kgs_d;
	
	$n_out_bag_l += $g_out_bag_l;
	$n_out_kgs_l += $g_out_kgs_l;
	$n_out_bag_d += $g_out_bag_d;
	$n_out_kgs_d += $g_out_kgs_d;
	
} // 1st loop

$leaf_offering = $n_sold_bag_l + $n_wd_bag_l + $n_out_bag_l;
$dust_offering = $n_sold_bag_d + $n_wd_bag_d + $n_out_bag_d;
?>
<tfoot>
<tr>
	<th></th>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
	<th style="text-align:center;"><?php echo $n_chest_number; ?></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;"><?php echo number_format($n_sold_amount,2,'.',','); ?></th>
</tr>
</tfoot>
</table>

<br />
<br />

<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="6" style="text-align:center; color:green;">Sold</th>
	<th colspan="3" style="text-align:center; color:red;">W/D</th>
	<th colspan="3" style="text-align:center; color:#006994;">Out</th>
</tr>
<tr>
	<th style="text-align:center; color:green;"></th>
	<th style="text-align:right; color:green;">Bags</th>
	<th style="text-align:right; color:green;">Kgs</th>
	<th style="text-align:right; color:green;">Amount</th>
	<th style="text-align:right; color:green;">Avg.</th>
	<th style="text-align:right; color:green;">%</th>
	
	<th style="text-align:right; color:red;">Bags</th>
	<th style="text-align:right; color:red;">Kgs</th>
	<th style="text-align:right; color:red;">%</th>
	
	<th style="text-align:right; color:#006994;">Bags</th>
	<th style="text-align:right; color:#006994;">Kgs</th>
	<th style="text-align:right; color:#006994;">%</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align:center;"><b>Leaf</b></td>
	<td style="text-align:right; color:green;"><?php print $n_sold_bag_l; ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_kgs_l,2,'.',''); ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_tk_l,2,'.',','); ?></td>
	<td style="text-align:right; color:green;">
	<?php 
		print ($n_sold_tk_l > 0 && $n_sold_kgs_l > 0) ? number_format($n_sold_tk_l / $n_sold_kgs_l,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; color:green;">
	<?php 
		print ($n_sold_bag_l > 0 && $leaf_offering > 0) ? number_format(($n_sold_bag_l * 100)/$leaf_offering,2,'.','') : '0.00'; 
	?>
	</td>
	
	<td style="text-align:right; color:red;"><?php print $n_wd_bag_l; ?></td>
	<td style="text-align:right; color:red;"><?php print number_format($n_wd_kgs_l,2,'.',','); ?></td>
	<td style="text-align:right; color:red;">
	<?php 
		print ($n_wd_bag_l > 0 && $leaf_offering > 0) ? number_format(($n_wd_bag_l * 100)/$leaf_offering,2,'.','') : '0.00'; 
	?>
	</td>
	
	<td style="text-align:right; color:#006994;"><?php print $n_out_bag_l; ?></td>
	<td style="text-align:right; color:#006994;"><?php print $n_out_kgs_l; ?></td>
	<td style="text-align:right; color:#006994;">
	<?php 
		print ($n_out_bag_l > 0 && $leaf_offering > 0) ? number_format(($n_out_bag_l * 100)/$leaf_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:center;"><b>Dust</b></td>
	<td style="text-align:right; color:green;"><?php print $n_sold_bag_d; ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_tk_d,2,'.',','); ?></td>
	<td style="text-align:right; color:green;">
	<?php 
		print ($n_sold_tk_d > 0 && $n_sold_kgs_d > 0) ? number_format($n_sold_tk_d / $n_sold_kgs_d,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; color:green;">
	<?php 
		print ($n_sold_bag_d > 0 && $dust_offering > 0) ? number_format(($n_sold_bag_d * 100) / $dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
	
	<td style="text-align:right; color:red;"><?php print $n_wd_bag_d; ?></td>
	<td style="text-align:right; color:red;"><?php print $n_wd_kgs_d; ?></td>
	<td style="text-align:right; color:red;">
	<?php 
		print ($n_wd_bag_d > 0 && $dust_offering > 0) ? number_format(($n_wd_bag_d * 100)/$dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
	
	<td style="text-align:right; color:#006994;"><?php print $n_out_bag_d; ?></td>
	<td style="text-align:right; color:#006994;"><?php print number_format($n_out_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; color:#006994;">
	<?php 
		print ($n_out_bag_d > 0 && $dust_offering > 0) ? number_format(($n_out_bag_d * 100)/$dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:center;"><b>Net Total</b></td>
	<td style="text-align:right; color:green;"><?php print $n_sold_bag_l + $n_sold_bag_d; ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_kgs_l + $n_sold_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_tk_l + $n_sold_tk_d,2,'.',','); ?></td>
	<td style="text-align:right; color:green;">
	<?php 
		print (($n_sold_tk_l + $n_sold_tk_d) > 0 && ($n_sold_kgs_l + $n_sold_kgs_d) > 0) ? number_format(($n_sold_tk_l + $n_sold_tk_d) / ($n_sold_kgs_l + $n_sold_kgs_d),2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; color:green;"><?php print number_format((($n_sold_bag_l + $n_sold_bag_d) * 100)/$n_chest_number,2,'.',''); ?></td>
	
	<td style="text-align:right; color:red;"><?php print $n_wd_bag_l + $n_wd_bag_d; ?></td>
	<td style="text-align:right; color:red;"><?php print number_format($n_wd_kgs_l + $n_wd_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; color:red;"><?php print number_format((($n_wd_bag_l + $n_wd_bag_d) * 100)/$n_chest_number,2,'.',''); ?></td>
	
	<td style="text-align:right; color:#006994;"><?php print $n_out_bag_l + $n_out_bag_d; ?></td>
	<td style="text-align:right; color:#006994;"><?php print number_format($n_out_kgs_l + $n_out_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; color:#006994;"><?php print number_format((($n_out_bag_l + $n_out_bag_d) * 100)/$n_chest_number,2,'.',''); ?></td>
</tr>
</tbody>
</table>

<br />
<br />

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