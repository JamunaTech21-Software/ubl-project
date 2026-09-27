<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$old_season = (!empty($this->input->get('old_season', TRUE))) ? $this->input->get('old_season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';
$report_no = (!empty($this->input->get('report', TRUE))) ? $this->input->get('report', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';

$current_date_time= date("Y-m-d h:i:sa");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo ($report_no=='16') ? 'ALL GARDEN OFFERING SALES' : 'GARDEN SALES REPORT' ; ?></title>
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
		size:A4;
		margin-left: 100px;
	}
</style>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id=&report=3'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> In Details</a>
		
		&nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
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
$this->db->where('invoice_entry.auction_center', $auction_center);
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
$this->db->where('invoice_entry.auction_center',$auction_center);
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
$n_sold_amount += $row3['sold_amount'];

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
	<td style="text-align:left; border:0px; text-transform:uppercase;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=2'); ?>"><b><?php print ($leaf_offering > 0) ? $row1['garden_name'] : ''; ?></b></a>
	</td>
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
	<td style="text-align:left; border:0px; text-transform:uppercase;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=2'); ?>"><b><?php print ($leaf_offering == 0) ? $row1['garden_name'] : ''; ?></b></a>
	</td>
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

<div class="row" style="margin-top:5px;">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" target="_blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$garden_id.'&report=13'); ?>"><span class="glyphicon glyphicon-file text-danger" aria-hidden="true"></span> Grade Analysis</a>
		
		&nbsp; <a class="btn-default" target="_blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$garden_id.'&report=8'); ?>"><span class="glyphicon glyphicon-file text-danger" aria-hidden="true"></span> Buyer Analysis - 1</a>
		
		&nbsp; <a class="btn-default" target="_blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$garden_id.'&report=10'); ?>"><span class="glyphicon glyphicon-file text-danger" aria-hidden="true"></span> Buyer Analysis - 2</a>
		
		&nbsp; <a class="btn-default" target="_blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$garden_id.'&report=11'); ?>"><span class="glyphicon glyphicon-file text-danger" aria-hidden="true"></span> Offering vs Sales</a>

		&nbsp; <a target="_blink" style="text-decoration:none;" href="<?php echo site_url('Garden_sales_report_export/garden_sales_export'); ?>?season=<?php echo urlencode($season); ?>&auction_center=<?php echo urlencode($auction_center); ?>&sale_from=<?php echo urlencode($sale_from); ?>&sale_to=<?php echo urlencode($sale_to); ?>&garden_id=<?php echo urlencode($garden_id); ?>">
			<span class="badge badge-danger" style="cursor:pointer;">
				<span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel
			</span>
		</a>
		
		&nbsp; <a target="_blink" style="text-decoration:none;" href="<?php echo site_url('Price_realised_export_report/price_realised_export'); ?>?season=<?php echo urlencode($season); ?>&auction_center=<?php echo urlencode($auction_center); ?>&sale_from=<?php echo urlencode($sale_from); ?>&sale_to=<?php echo urlencode($sale_to); ?>&garden_id=<?php echo urlencode($garden_id); ?>">
			<span class="badge badge-danger" style="cursor:pointer;">
				<span class="glyphicon glyphicon-download" aria-hidden="true"></span> Price Realised to Excel
			</span>
		</a>

		&nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<table style="width:100%;">
	<tr>
		<td colspan="3" style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;">
			<?php print $website_title; ?> 
			<br />
			<span style="font-size:12px;"><?php print $owner_short_address; ?></span>
			<br />
			<b style="font-family:courier;">
				GARDEN SALES REPORT
			</b>
			<br />
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
		</td>	
	</tr>

<?php
## Sale schedule
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE sale_no='".$sale_from."' AND season_name='".$season."' AND auction_center='".$auction_center."' ");
$sale_sch_data = $query->row_array();
?>	
	
	<tr>
		<td style="text-align:left;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
		</td>
		<td style="text-align:center;">
			<?php if($sale_from == $sale_to){ ?>
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">
				Sale Date: <?php print date("d-m-Y",strtotime($sale_sch_data['sale_date'])); ?>
			</b>
			<?php } ?>
		</td>
		<td style="text-align:right;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
		</td>
	</tr>
</table>

<table class="table table-sm" id="table">
<?php
// garden name
$query = $this->db->query("SELECT * FROM `garden_and_company_setup` WHERE id='".$garden_id."' "); 
$garden_name_result = $query->row_array();

// reset data
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
$this->db->where('invoice_entry.auction_center', $auction_center);
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
    <th colspan="6" style="font-size:14px !important; text-transform:uppercase; border:0px !important;">
		<?php print $garden_name_result['garden_name']; ?> / <?php print $row1['short_key_word']; ?>
	</th>
</tr>
<tr>
    <th style="text-align:center;">Sale No</th>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Old Season</th>
    <th style="text-align:center;">Invoice</th>
    <th style="text-align:left;">Grade</th>
	<th style="text-align:right;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
	<th style="text-align:center;">Value</th>
	<th style="text-align:center;">Rating Liquor</th>
	<th style="text-align:center;">Scale Leaf</th>
	<th style="text-align:center;">Sold Price</th>
	<th style="text-align:left;">Buyer1</th>
	<th style="text-align:left;">Buyer2</th>
	<th style="text-align:left;">Buyer3</th>
	<th style="text-align:right;">Amount</th>
	<th style="text-align:right;">Re-Print</th>
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
$this->db->select("sale_no"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season',$season);
$this->db->where('auction_center',$auction_center);
$this->db->where("sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('w_house_id',$w_house_id);
$this->db->where('garden_id',$garden_id);
$this->db->where('soft_delete', 1);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
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

	invoice_entry.sale_no, 
	invoice_entry.lot_no, 
	invoice_entry.old_season,
	invoice_entry.w_inv_no, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	invoice_entry.valuation,
	invoice_entry.appearance, 
	invoice_entry.liquor, 
	IFNULL(sale_entry.sold_price,0) AS sold_price, 
	
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS chest_number, 
	
	invoice_entry.remarks, 
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,

	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: <b>', invoice_entry.old_sale_no, '</b> / <b>', invoice_entry.old_lot_no, '</b>'), '') AS re_print,
	
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0)
	) AS leaf_total_bag,
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0)
	) AS leaf_net_kgs, 
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0)
	) AS dust_total_bag,
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0)
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
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no',$row2['sale_no']);
$this->db->where('invoice_entry.w_house_id',$w_house_id);
$this->db->where('invoice_entry.garden_id',$garden_id);
$this->db->where('invoice_entry.soft_delete', 1);

$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
	$re_print = !empty($row3['re_print']) ? 'Re-Print' : 'Fresh';
?>

<tr>
    <td style="text-align:center;"><?php echo $row3['sale_no']; ?></td>
    <td style="text-align:center;"><?php echo $row3['lot_no']; ?></td>
    <td style="text-align:center;"><?php echo $row3['old_season']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:left; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:center;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:center; color:#FF69B4;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	<td style="text-align:center;"><?php echo $row3['liquor']; ?></td>
	<td style="text-align:center;"><?php echo $row3['appearance']; ?></td>
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
	<td style="text-align:center;"><?php echo $re_print; ?></td>
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
	<th></th>
	<th></th>
	<th style="text-align:right;"><?php echo number_format($sold_amount,2,'.',','); ?></th>
	<th></th>
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
	<th colspan="16" style="padding:10px !important; border:0px !important;"></th>
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
	<th></th>
	<th></th>
	<th style="text-align:right;"><?php echo number_format($n_sold_amount,2,'.',','); ?></th>
	<th></th>
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

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>

<?php } ?>

<?php if($report_no=='3'){ ?>
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
	<div class="form-group col-sm-1 text-right">
		<a class="no-print" href="#" onClick="printDiv('printableArea')">
			<span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
		</a>
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

<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th></th>
	<th></th>
	<th colspan="2" style="text-align:center; color:black;">Offering</th>
	<th colspan="5" style="text-align:center; color:green;">Sold</th>
	<th colspan="3" style="text-align:center; color:red;">W/D</th>
	<th colspan="3" style="text-align:center; color:#006994;">Out</th>
</tr>
<tr>
	<th style="text-align:left; color:green;">Garden Name</th>
	<th></th>
	
	<th style="text-align:right; color:black;">Bags</th>
	<th style="text-align:right; color:black;">Kgs</th>
	
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
<?php
$ac_clause = ($auction_center=='All Center') ? "invoice_entry.auction_center <> ''" : "invoice_entry.auction_center='".$auction_center."' ";

$nt_chest_number=0;
$nt_net_kgs=0;

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
$this->db->where($ac_clause);
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
$this->db->where($ac_clause);
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
	<td style="text-align:left;"><a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=2'); ?>"><b><?php print ($leaf_offering > 0) ? $row1['garden_name'] : ''; ?></b></a></td>
	<td style="text-align:center;"><b>Leaf</b></td>
	
	<td style="text-align:right; color:black;"><?php print $leaf_offering; ?></td>
	<td style="text-align:right; color:black;"><?php print number_format($leaf_offering_kgs,2,'.',''); ?></td>
	
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
	<td style="text-align:right; color:red;"><?php print $n_wd_kgs_l; ?></td>
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
	<td style="text-align:left; border:0px;"><a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>"><b><?php print ($leaf_offering == 0) ? $row1['garden_name'] : ''; ?></b></a></td>
	<td style="text-align:center; border:0px;"><b>Dust</b></td>
	
	<td style="text-align:right; color:black; border:0px;"><?php print $dust_offering; ?></td>
	<td style="text-align:right; color:black; border:0px;"><?php print number_format($dust_offering_kgs,2,'.',''); ?></td>
	
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
	
	<td style="text-align:right; border:0px; color:red;"><?php print $n_wd_bag_d; ?></td>
	<td style="text-align:right; border:0px; color:red;"><?php print number_format($n_wd_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; border:0px; color:red;">
	<?php 
		print ($n_wd_bag_d > 0 && $dust_offering > 0) ? number_format(($n_wd_bag_d * 100)/$dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
	
	<td style="text-align:right; border:0px; color:#006994;"><?php print $n_out_bag_d; ?></td>
	<td style="text-align:right; border:0px; color:#006994;"><?php print number_format($n_out_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; border:0px; color:#006994;">
	<?php 
		print ($n_out_bag_d > 0 && $dust_offering > 0) ? number_format(($n_out_bag_d * 100)/$dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<th colspan="2" style="text-align:right;">Sub Total: </th>
	
	<th style="text-align:right; color:black;"><?php print $n_chest_number; ?></th>
	<th style="text-align:right; color:black;"><?php print number_format($n_net_kgs,2,'.',''); ?></th>
	
	<th style="text-align:right; color:green;"><?php print $n_sold_bag_l + $n_sold_bag_d; ?></th>
	<th style="text-align:right; color:green;"><?php print number_format($n_sold_kgs_l + $n_sold_kgs_d,2,'.',','); ?></th>
	<th style="text-align:right; color:green;"><?php print number_format($n_sold_tk_l + $n_sold_tk_d,2,'.',','); ?></th>
	<th style="text-align:right; color:green;">
	<?php 
		print (($n_sold_tk_l + $n_sold_tk_d) > 0 && ($n_sold_kgs_l + $n_sold_kgs_d) > 0) ? number_format(($n_sold_tk_l + $n_sold_tk_d) / ($n_sold_kgs_l + $n_sold_kgs_d),2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; color:green;">
		<?php print number_format((($n_sold_bag_l + $n_sold_bag_d) * 100)/$n_chest_number,2,'.',''); ?>
	</th>
	
	<th style="text-align:right; color:red;"><?php print $n_wd_bag_l + $n_wd_bag_d; ?></th>
	<th style="text-align:right; color:red;"><?php print number_format($n_wd_kgs_l + $n_wd_kgs_d,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php print number_format((($n_wd_bag_l + $n_wd_bag_d) * 100)/$n_chest_number,2,'.',''); ?></th>
	
	<th style="text-align:right; color:#006994;"><?php print $n_out_bag_l + $n_out_bag_d; ?></th>
	<th style="text-align:right; color:#006994;"><?php print number_format($n_out_kgs_l + $n_out_kgs_d,2,'.',''); ?></th>
	<th style="text-align:right; color:#006994;"><?php print number_format((($n_out_bag_l + $n_out_bag_d) * 100)/$n_chest_number,2,'.',''); ?></th>
</tr>
<tr>
	<td colspan="15" style="border:0px;"></td>
</tr>
<?php
$nt_chest_number += $n_chest_number;
$nt_net_kgs += $n_net_kgs;

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
	<th colspan="2" style="text-align:right;">Net Total: </th>
	
	<th style="text-align:right; color:black;"><?php print $nt_chest_number; ?></th>
	<th style="text-align:right; color:black;"><?php print number_format($nt_net_kgs,2,'.',''); ?></th>
	
	<th style="text-align:right; color:green;"><?php print $nt_sold_bag_l + $nt_sold_bag_d; ?></th>
	<th style="text-align:right; color:green;"><?php print number_format($nt_sold_kgs_l + $nt_sold_kgs_d,2,'.',''); ?></th>
	<th style="text-align:right; color:green;"><?php print number_format($nt_sold_tk_l + $nt_sold_tk_d,2,'.',','); ?></th>
	<th style="text-align:right; color:green;">
	<?php 
		print (($nt_sold_tk_l + $nt_sold_tk_d) > 0 && ($nt_sold_kgs_l + $nt_sold_kgs_d) > 0) ? number_format(($nt_sold_tk_l + $nt_sold_tk_d) / ($nt_sold_kgs_l + $nt_sold_kgs_d),2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; color:green;">
		<?php 
			print (($nt_sold_bag_l + $nt_sold_bag_d)!='0' && $nt_chest_number!='0') ? number_format((($nt_sold_bag_l + $nt_sold_bag_d) * 100)/$nt_chest_number,2,'.','') : '0.00'; 
		?>
	</th>
	
	<th style="text-align:right; color:red;"><?php print $nt_wd_bag_l + $nt_wd_bag_d; ?></th>
	<th style="text-align:right; color:red;"><?php print $nt_wd_kgs_l + $nt_wd_kgs_d; ?></th>
	<th style="text-align:right; color:red;">
		<?php 
			print (($nt_wd_bag_l + $nt_wd_bag_d)!='0' && $nt_chest_number!='0') ? number_format((($nt_wd_bag_l + $nt_wd_bag_d) * 100)/$nt_chest_number,2,'.','') : '0.00'; 
		?>
	</th>
	
	<th style="text-align:right; color:#006994;"><?php print $nt_out_bag_l + $nt_out_bag_d; ?></th>
	<th style="text-align:right; color:#006994;"><?php print $nt_out_kgs_l + $nt_out_kgs_d; ?></th>
	<th style="text-align:right; color:#006994;">
		<?php 
			print (($nt_out_bag_l + $nt_out_bag_d)!='0' && $nt_chest_number!='0') ? number_format((($nt_out_bag_l + $nt_out_bag_d) * 100)/$nt_chest_number,2,'.','') : '0.00'; 
		?>
	</th>
</tr>	
</tfoot>
</table>
</div>
</div>
</div>
<?php } ?>


<?php if($report_no=='4'){ 
## create temporary table - purchase_analysis
$this->db->query("DROP TABLE IF EXISTS `purchase_analysis`");

// create purchase_analysis
$this->db->query("CREATE TABLE `purchase_analysis` (
SELECT 

	invoice_entry.lot_no, 
	invoice_entry.garden_id,	
	gs.garden_name,
	invoice_entry.w_inv_no, 	
	invoice_entry.chest_number, 	

	@int_pkgs:=(
		IFNULL((CASE WHEN sale_entry.buyer_type1 = 'Internal' THEN sale_entry.buyer1_bag END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type2 = 'Internal' THEN sale_entry.buyer2_bag END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type3 = 'Internal' THEN sale_entry.buyer3_bag END),0)
	) AS int_pkgs,

	@int_kgs:=(
		IFNULL((CASE WHEN sale_entry.buyer_type1 = 'Internal' THEN sale_entry.buyer1_total_kgs END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type2 = 'Internal' THEN sale_entry.buyer2_total_kgs END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type3 = 'Internal' THEN sale_entry.buyer3_total_kgs END),0)
	) AS int_kgs,
	
	((@int_pkgs / invoice_entry.chest_number) * 100) AS int_percent,
	
	@int_amount:=(
		IFNULL((CASE WHEN sale_entry.buyer_type1 = 'Internal' THEN sale_entry.buyer1_amount END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type2 = 'Internal' THEN sale_entry.buyer2_amount END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type3 = 'Internal' THEN sale_entry.buyer3_amount END),0)
	) AS int_amount,
	
	(@int_amount / @int_kgs) AS int_avg,
	
	@exp_pkgs:=(
		IFNULL((CASE WHEN sale_entry.buyer_type1 = 'Export' THEN sale_entry.buyer1_bag END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type2 = 'Export' THEN sale_entry.buyer2_bag END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type3 = 'Export' THEN sale_entry.buyer3_bag END),0)
	) AS exp_pkgs,

	@exp_kgs:=(
		IFNULL((CASE WHEN sale_entry.buyer_type1 = 'Export' THEN sale_entry.buyer1_total_kgs END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type2 = 'Export' THEN sale_entry.buyer2_total_kgs END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type3 = 'Export' THEN sale_entry.buyer3_total_kgs END),0)
	) AS exp_kgs,
	
	((@exp_pkgs / invoice_entry.chest_number) * 100) AS exp_percent,
	
	@exp_amount:=(
		IFNULL((CASE WHEN sale_entry.buyer_type1 = 'Export' THEN sale_entry.buyer1_amount END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type2 = 'Export' THEN sale_entry.buyer2_amount END),0) + 
		IFNULL((CASE WHEN sale_entry.buyer_type3 = 'Export' THEN sale_entry.buyer3_amount END),0)
	) AS exp_amount,
	
	(@exp_amount / @exp_kgs) AS exp_avg
	
FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' GROUP BY invoice_entry.id) ");
// end
?>
<div class="row">
<div class="form-group col-sm-11 text-center">
	<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
	<br /> 
	<span style="text-transform: uppercase; font-size:12px; text-align:center;"><?php print $owner_short_address; ?></span>
	<br />
	<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
		GARDEN WISE INT/EXP PURCHASE ANALYSIS
	</b>
</div>
<div class="form-group col-sm-1 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
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

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center;">Garden Name</th>
    <th style="text-align:right;">Buyer Type</th>
    <th style="text-align:right;">Total Chest</th>
    <th style="text-align:right;">Total Kgs</th>
	<th style="text-align:right;">Percent</th>
	<th style="text-align:right;">Amount (TAKA)</th>
	<th style="text-align:right;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
$total_offering_bag =0;

$total_pkgs_int =0;
$total_kgs_int =0;
$total_amount_int =0;

$total_pkgs_exp =0;
$total_kgs_exp =0;
$total_amount_exp =0;
	
$total_pkgs =0;
$total_kgs = 0;
$total_amount = 0;

//query
$query = $this->db->query("SELECT garden_name, sum(chest_number) AS offering_bag, sum(int_pkgs) AS int_pkgs, sum(int_kgs) AS int_kgs, sum(int_amount) AS int_amount, sum(exp_pkgs) AS exp_pkgs, sum(exp_kgs) AS exp_kgs, AVG(exp_percent) AS exp_percent, sum(exp_amount) AS exp_amount, avg(exp_avg) AS exp_avg FROM purchase_analysis GROUP BY garden_id ORDER BY garden_name ASC");
if($query->num_rows() > 0){
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
?>
<tr>
    <td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row_data['garden_name']; ?></td>
    <td style="text-align:right; text-transform:uppercase; border:0px;">INTERNAL</td>
	<td style="text-align:right; border:0px;"><?php echo $row_data['int_pkgs']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row_data['int_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row_data['int_pkgs'] > 0 && $row_data['offering_bag'] > 0) ? number_format(($row_data['int_pkgs']/$row_data['offering_bag'])*100,2,'.','') : '0.00'; ?>
	</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row_data['int_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row_data['int_amount'] > 0 && $row_data['int_kgs'] > 0) ? number_format($row_data['int_amount']/$row_data['int_kgs'],2,'.','') : '0.00'; ?>
	</td>
</tr>
<tr>
    <td style="text-align:center; text-transform:uppercase; border:0px;"></td>
    <td style="text-align:right; text-transform:uppercase; border:0px;">EXPORT</td>
	<td style="text-align:right; border:0px;"><?php echo $row_data['exp_pkgs']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row_data['exp_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row_data['exp_pkgs'] > 0 && $row_data['offering_bag'] > 0) ? number_format(($row_data['exp_pkgs']/$row_data['offering_bag'])*100,2,'.','') : '0.00'; ?>
	</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row_data['exp_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row_data['exp_amount'] > 0 && $row_data['exp_kgs'] > 0) ? number_format($row_data['exp_amount']/$row_data['exp_kgs'],2,'.','') : '0.00'; ?>
	</td>
</tr>
<?php
$sub_total_pkgs = $row_data['int_pkgs'] + $row_data['exp_pkgs'];
$sub_total_kgs = $row_data['int_kgs'] + $row_data['exp_kgs'];
$sub_total_amount = $row_data['int_amount'] + $row_data['exp_amount'];
?>
<tr>
	<th colspan="2" style="text-align:right; border:0px;">TOTAL : </th>
	<th style="text-align:right; border:0px;"><?php echo $sub_total_pkgs; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sub_total_kgs,2,'.',''); ?></th>
	<td style="text-align:right; border:0px;">
		<?php echo ($sub_total_pkgs > 0 && $row_data['offering_bag'] > 0) ? number_format(($sub_total_pkgs/$row_data['offering_bag'])*100,2,'.','') : '0.00'; ?>
	</td>
	<th style="text-align:right; border:0px;"><?php echo number_format($sub_total_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($sub_total_amount > 0 && $sub_total_kgs > 0) ? number_format($sub_total_amount/$sub_total_kgs,2,'.','') : '0.00'; ?>
	</th>
</tr>
<?php
	$total_offering_bag += $row_data['offering_bag'];
	
	$total_pkgs_int += $row_data['int_pkgs'];
	$total_kgs_int += $row_data['int_kgs'];
	$total_amount_int += $row_data['int_amount'];
	
	$total_pkgs_exp += $row_data['exp_pkgs'];
	$total_kgs_exp += $row_data['exp_kgs'];
	$total_amount_exp += $row_data['exp_amount'];
	
	$total_pkgs += $sub_total_pkgs;
	$total_kgs += $sub_total_kgs;
	$total_amount += $sub_total_amount;
	
} // foreach
?>
</tbody>
<tfoot>
<tr>
	<th colspan="2" style="text-align:right;">GRAND TOTAL (INT) : </th>
	<th style="text-align:right;"><?php echo $total_pkgs_int; ?></th>
	<th style="text-align:right;"><?php echo number_format($total_kgs_int,2,'.',''); ?></th>
	<th style="text-align:right;">
		<?php echo ($total_pkgs_int > 0 && $total_offering_bag > 0) ? number_format(($total_pkgs_int/$total_offering_bag)*100,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right;"><?php echo number_format($total_amount_int,2,'.',''); ?></th>
	<th style="text-align:right;">
		<?php echo ($total_amount_int > 0 && $total_kgs_int > 0) ? number_format($total_amount_int/$total_kgs_int,2,'.','') : '0.00'; ?>
	</th>
</tr>
<tr>
	<th colspan="2" style="text-align:right; border:0px;">GRAND TOTAL (EXP) : </th>
	<th style="text-align:right; border:0px;"><?php echo $total_pkgs_exp; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($total_kgs_exp,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_pkgs_exp > 0 && $total_offering_bag > 0) ? number_format(($total_pkgs_exp/$total_offering_bag)*100,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px;"><?php echo number_format($total_amount_exp,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_amount_exp > 0 && $total_kgs_exp > 0) ? number_format($total_amount_exp/$total_kgs_exp,2,'.','') : '0.00'; ?>
	</th>
</tr>
<tr>
	<th colspan="2" style="text-align:right; border:0px;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px;"><?php echo $total_pkgs; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_pkgs > 0 && $total_offering_bag > 0) ? number_format(($total_pkgs/$total_offering_bag)*100,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px;"><?php echo number_format($total_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_amount > 0 && $total_kgs > 0) ? number_format($total_amount/$total_kgs,2,'.','') : '0.00'; ?>
	</th>
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

<?php if($report_no=='5'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$garden_id.'&report=14'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Current Season</a>
		
		<?php
		$current_season = explode("-",$season);
		
		$old_season_sbt = ($current_season[0] - 1);
		$old_season_sbt2 = ($current_season[1] - 1);
		$old_season = "$old_season_sbt-$old_season_sbt2";
		
		$old_season_ext = ($current_season[0] + 1);
		$old_season_ext2 = ($current_season[1] + 1);
		$new_season = "$old_season_ext-$old_season_ext2";
		?>
		
		&nbsp; <a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$garden_id.'&old_season='.$old_season.'&report=15'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Old Season</a>
		
		&nbsp; <a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$garden_id.'&old_season='.$new_season.'&report=15'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Season</a>
		
		&nbsp; <a class="btn-default" href="javascript:void(0);" onclick="return PrintPanel();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
	<div class="form-group col-sm-12">
		<table class="table table-sm" id="table">
			<thead>
				<tr>
					<th style="text-transform: uppercase; text-align:center; border:0px;">
					<span style="font-size:16px;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
					OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>


<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="2" style="text-align:left; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//garden breakdown
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<tr>
    <th colspan="4" style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=7'); ?>"><?php print $row1['garden_name']; ?></a>
	</th>
	<th colspan="4" style="font-size:14px !important; border:0px; text-align:right;"></th>
</tr>
<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> 
<!-- /row -->


<!-- GRAND TOTAL -->
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;">GRAND TOTAL</th>
	<th colspan="2" style="text-align:center; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
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

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
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
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.soft_delete', 1);
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

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
</tbody>
</table>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>


<?php if($report_no=='6'){ 
## create temporary table - buyer_purchase_analysis
$this->db->query("DROP TABLE IF EXISTS `buyer_purchase_analysis`");

// create buyer_purchase_analysis @  buyer-1
$this->db->query("CREATE TABLE `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_purchase_analysis @  buyer-2
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_purchase_analysis @  buyer-3
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" target="blink" href="<?php echo site_url(); ?>/Buyer_purchase_export/createexcel?season=<?php print $season; ?>&auction_center=<?php print $auction_center; ?>&sale_from=<?php print $sale_from; ?>&sale_to=<?php print $sale_to; ?>"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel</span></a>
	
	&nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	BUYER PURCHASE ANALYSIS <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-align:left; border:0px;">
		Dear Sir, <br /> We give below the purchase made by the following buyers in our catalogue for sale no &nbsp;&nbsp;<b><?php print $sale_from; ?> &nbsp;&nbsp; to &nbsp;&nbsp;<?php print $sale_to; ?> </b> &nbsp;&nbsp; Season: <?php print $season; ?>
	</th>
</tr>
</table>

<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:left; white-space:nowrap; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">BAGS</th>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">KGS</th>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">AMOUNT</th>
	<th style="text-align:right; white-space:nowrap; border:1px solid;">VAT (TAKA)</th>
</tr>
</thead>
<tbody>
<?php
$net_bags = 0;
$net_kgs = 0;
$net_amount = 0;
$net_vat_tk = 0;

// query - 1
$this->db->select("buyer_category"); 	
$this->db->from('buyer_purchase_analysis');
$this->db->group_by('buyer_category');
$this->db->order_by('buyer_category ASC');
$query2 = $this->db->get();
foreach($query2->result_array() as $row2){
?>
<tr>
	<th colspan="5" style="text-transform:uppercase; text-align:left; border:0px; color:red;"><?php print $row2['buyer_category']; ?></th>
</tr>
<?php
// query -2
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat_tk = 0;

// BREAKDOWN BUYER	
$this->db->select("bpa.buyer_id, buyer_setup.buyer_name, bpa.buyer_type, sum(bpa.buyer_bag) AS total_bags, sum(bpa.buyer_kgs) AS total_kgs, sum(bpa.buyer_amount) AS total_amount, IF(bpa.buyer_type='Internal', sum(bpa.vat_tk), '0.00') AS total_vat_tk "); 	
$this->db->from('buyer_purchase_analysis bpa');
$this->db->join('buyer_setup','bpa.buyer_id = buyer_setup.id');
$this->db->where('bpa.buyer_category', $row2['buyer_category']);
$this->db->group_by('bpa.buyer_id');
$this->db->order_by('buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&buyer_id='.$row3['buyer_id'].'&report=9'); ?>"><?php echo $row3['buyer_name']; ?></a>
	</td>
	<td style="text-align:right; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_vat_tk'],2,'.',','); ?></td>
</tr>	
<?php
	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$total_vat_tk += $row3['total_vat_tk'];
}  //2nd loop
?>
<tr>
	<th style="text-align:right; border:0px; color:red; text-transform:uppercase;"><?php print $row2['buyer_category']; ?> TOTAL : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
</tr>
<?php
	$net_bags += $total_bags;
	$net_kgs += $total_kgs;
	$net_amount += $total_amount;
	$net_vat_tk += $total_vat_tk;
} //1st loop 
?>
<tr>
	<th style="text-align:right; border:1px solid; color:red;">GRAND TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $net_bags; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($net_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($net_vat_tk,2,'.',','); ?></th>
</tr>
</tbody>
</table>

</div>
</div>
<?php } ?>



<?php if($report_no=='7'){ 
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
	</th>
</tr>
</table>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="2" style="text-align:left; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//garden breakdown
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>

<tr>
    <th style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
		<?php print $row1['garden_name']; ?>
	</th>
	<th style="font-size:14px !important; border:0px;"></th>
	<th style="font-size:14px !important; border:0px;"></th>
	<th style="font-size:14px !important; border:0px;"></th>
</tr>

<?php
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//category breakdown
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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

//grade breakdown
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');

$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$chest_number += $row3['chest_number']; 
$net_kgs += $row3['net_kgs']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>

<!-- Garden wise Buyer Statement sale to sale -->
<?php if($report_no=='8'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:13px !important;
	padding:3px;
	font-family:courier;
}

#table tr th{
	border-color:black;
	font-size:13px !important;
	padding:3px;
	font-family:courier;
}

@page{
	size: legal landscape;
	margin-left: 100px;
	margin-right: 100px;
}
</style>
<?php 
// get html value
$row_limit_pp = 25;
## create temporary table - buyer_purchase_analysis
$this->db->query("DROP TABLE IF EXISTS `buyer_purchase_analysis`");

// create buyer_purchase_analysis @  buyer-1
$this->db->query("CREATE TABLE `buyer_purchase_analysis` (
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_purchase_analysis @  buyer-2
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

//create buyer_purchase_analysis @  buyer-3
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" target="_blink" href="<?php echo site_url(); ?>/Buyer_purchase_export/createexcel?season=<?php print $season; ?>&auction_center=<?php print $auction_center; ?>&sale_from=<?php print $sale_from; ?>&sale_to=<?php print $sale_to; ?>"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel</span></a>
	
	&nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>

<?php
	$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
	$fetch_gn = $query->row_array();
?>

<?php
// query
$sl=1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat_tk = 0;
$total_vat = 0;
$total_ait = 0;
$total_tea_cess = 0;

// INTERNAL BUYER	
$this->db->select("bill_no, sale_date, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk, (CASE WHEN buyer_type='Internal' THEN 'A'
WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac"); 	
$this->db->from('buyer_purchase_analysis');
$this->db->group_by('buyer_id,buyer_type');
$this->db->order_by('buyer_type_ac ASC');
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
	$count = $sl++;

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
        <td colspan="9" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:16px; font-family:courier;"><?php print $website_title; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px; font-family:courier;">
                Gardenwise buyer statement From Sale No. &nbsp; <?php print $sale_from; ?> &nbsp; Season &nbsp; <?php print $season; ?>
				&nbsp; To &nbsp; <?php print $sale_to; ?> &nbsp; Season &nbsp; <?php print $season; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
	<tr>
        <th colspan="9" style="text-transform:uppercase; text-align:left; border:0px;">
			Garden Name: <?php print $fetch_gn['garden_name']; ?>
        </th>
    </tr>
    <tr>
		<th style="text-align:center; white-space:nowrap; border:1px solid;">SL</th>
		<th style="text-align:left; white-space:nowrap; border:1px solid;">BUYER Information</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">PKGS</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Wt.(kg.)</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">AMOUNT (Tk.)</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Avg.</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">VAT</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">AIT</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Tea Cess</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px; font-weight:bold;"><?php print $count ; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['buyer_name']; ?> <span style="color: <?php echo ($row3['buyer_type']=='Export') ? 'red' : ''; ?>"><?php echo ($row3['buyer_type']=='Export') ? '- '.$row3['buyer_type'] : ''; ?></span></td>
	<td style="text-align:right; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$vat = $row3['total_amount'] / $row3['total_kgs'];
			print number_format($vat,2,'.',',') ; 
		?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_vat_tk'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$ait = $row3['total_amount'] / 100;
			print number_format($ait,2,'.',',') ; 
		?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$tea_cess = $row3['total_amount'] / 100;
			print number_format($ait,2,'.',',') ; 
		?>
	</td>
</tr>
<?php

$total_bags += $row3['total_bags'];
$total_kgs += $row3['total_kgs'];
$total_amount += $row3['total_amount'];
$total_vat_tk += $row3['total_vat_tk'];
$total_vat += $vat;
$total_ait += $ait;
$total_tea_cess += $tea_cess;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan='2' style="text-align:right; border:0px; color:red;">Sub Total : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_ait,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_tea_cess,2,'.',','); ?></th>
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
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found.</b></p></div>";
}
?>
<?php } ?>

<!-- Garden wise Buyer Statement single sale-->

<?php if($report_no=='Buyer Statement'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:13px !important;
	padding:3px;
	font-family:courier;
}

#table tr th{
	border-color:black;
	font-size:13px !important;
	padding:3px;
	font-family:courier;
}

@page{
	size: legal landscape;
	margin-left: 100px;
	margin-right: 100px;
}
</style>
<?php 
// get html value
$row_limit_pp = 25;
## create temporary table - buyer_purchase_analysis
$this->db->query("DROP TABLE IF EXISTS `buyer_purchase_analysis`");

// create buyer_purchase_analysis @  buyer-1
$this->db->query("CREATE TABLE `buyer_purchase_analysis` (
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_purchase_analysis @  buyer-2
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

//create buyer_purchase_analysis @  buyer-3
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();
?>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	&nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>

<?php
	$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
	$fetch_gn = $query->row_array();
?>

<?php
// query
$sl=1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat_tk = 0;
$total_vat = 0;
$total_ait = 0;
$total_tea_cess = 0;

// INTERNAL BUYER	
$this->db->select("bill_no, sale_date, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk, (CASE WHEN buyer_type='Internal' THEN 'A'
WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac"); 	
$this->db->from('buyer_purchase_analysis');
$this->db->group_by('buyer_id,buyer_type');
$this->db->order_by('buyer_type_ac ASC');
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
	$count = $sl++;

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
        <td colspan="9" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:16px; font-family:courier;"><?php print $website_title; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px; font-family:courier;">
                Gardenwise buyer statement Season <?php print $season; ?> Sale No. <?php print $sale_no; ?> <?php print $auction_center; ?> held on <?php print date('d-M-Y' , strtotime($result_prompt_date['sale_date'])); ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
	<tr>
        <th colspan="9" style="text-transform:uppercase; text-align:left; border:0px;">
			Garden Name: <?php print $fetch_gn['garden_name']; ?>
        </th>
    </tr>
    <tr>
		<th style="text-align:center; white-space:nowrap; border:1px solid;">SL</th>
		<th style="text-align:left; white-space:nowrap; border:1px solid;">BUYER Information</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">PKGS</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Wt.(kg.)</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">AMOUNT (Tk.)</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Avg.</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">VAT</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">AIT</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Tea Cess</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px; font-weight:bold;"><?php print $count ; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['buyer_name']; ?> <span style="color: <?php echo ($row3['buyer_type']=='Export') ? 'red' : ''; ?>"><?php echo ($row3['buyer_type']=='Export') ? '- '.$row3['buyer_type'] : ''; ?></span></td>
	<td style="text-align:right; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$vat = $row3['total_amount'] / $row3['total_kgs'];
			print number_format($vat,2,'.',',') ; 
		?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_vat_tk'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$ait = $row3['total_amount'] / 100;
			print number_format($ait,2,'.',',') ; 
		?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$tea_cess = $row3['total_amount'] / 100;
			print number_format($ait,2,'.',',') ; 
		?>
	</td>
</tr>
<?php

$total_bags += $row3['total_bags'];
$total_kgs += $row3['total_kgs'];
$total_amount += $row3['total_amount'];
$total_vat_tk += $row3['total_vat_tk'];
$total_vat += $vat;
$total_ait += $ait;
$total_tea_cess += $tea_cess;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan='2' style="text-align:right; border:0px; color:red;">Sub Total : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_ait,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_tea_cess,2,'.',','); ?></th>
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
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found.</b></p></div>";
}
?>
<?php } ?>

<?php if($report_no=='9'){ ?>

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
			<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">GARDEN WISE BUYER PURCHASE ANALYSIS</b>
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
		<a target="_blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>"><?php print $row1['garden_name']; ?></a>
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



<?php if($report_no=='10'){

## create temporary table
$this->db->query("DROP TABLE IF EXISTS `buyer_purchase_analysis`");

// create buyer-1
$this->db->query("CREATE TABLE `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END,0)
	) AS leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END,0)
	) AS dust_bag, 
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END,0)
	) AS leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END,0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount 

FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer-2
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END,0)
	) AS leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END,0)
	) AS dust_bag, 
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END,0)
	) AS leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END,0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer-3
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END,0)
	) AS leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END,0)
	) AS dust_bag, 
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END,0)
	) AS leaf_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END,0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
$fetch_gn = $query->row_array();
?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
	<tr>
		<th colspan="10" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="10" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:14px; font-weight:bold;"><?php print $owner_address; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="10" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">GARDEN WISE BUYER PURCHASE ANALYSIS</b>
		</th>
	</tr>
	<tr>
		<th colspan="10" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="10" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:15px;">Garden Name: <?php print $fetch_gn['garden_name']; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="3" class="text-left" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
		</th>
		
		<th colspan="4" style="border:0px;"></th>
		
		<th colspan="3" class="text-right" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
		</th>
	</tr>
	<tr>
		<th rowspan="2" style="text-align:left; white-space:nowrap; border:1px solid;">BUYER TYPE</th>
		<th rowspan="2" style="text-align:left; white-space:nowrap; border:1px solid;">BUYER NAME</th>
		
		<th colspan="2" style="text-align:center; white-space:nowrap; border:1px solid;">LEAF</th>
		<th colspan="2" style="text-align:center; white-space:nowrap; border:1px solid;">DUST</th>
		
		<th rowspan="2" style="text-align:right; white-space:nowrap; border:1px solid;">TOTAL BAGS</th>
		<th rowspan="2" style="text-align:right; white-space:nowrap; border:1px solid;">TOTAL KGS</th>
		<th rowspan="2" style="text-align:right; white-space:nowrap; border:1px solid;">AMOUNT (TK.)</th>
		<th rowspan="2" style="text-align:right; white-space:nowrap; border:1px solid;">AVERAGE</th>
	</tr>
	<tr>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">BAGS</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">KGS</th>
		
		<th style="text-align:right; white-space:nowrap; border:1px solid;">BAGS</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">KGS</th>
	</tr>
</thead>
<tbody>
<?php
$sl = 1;
$leaf_bag = 0;
$leaf_kgs = 0;
$dust_bag = 0;
$dust_kgs = 0;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;

// INTERNAL BUYER	
$this->db->select("buyer_type, buyer_name, sum(leaf_bag) AS leaf_bag, sum(dust_bag) AS dust_bag, sum(leaf_kgs) AS leaf_kgs, sum(dust_kgs)  AS dust_kgs, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, (sum(buyer_amount) / sum(buyer_kgs)) AS average "); 	
$this->db->from('buyer_purchase_analysis');
$this->db->where('buyer_type','Internal');
$this->db->group_by('buyer_id');
$this->db->order_by('buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	$count = $sl++;
?>
<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase; font-weight:bold;">
		<?php echo ($count==1) ? $row3['buyer_type'] : ''; ?>
	</td>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['buyer_name']; ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['leaf_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['leaf_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['dust_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['dust_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['average'],2,'.',','); ?></td>
</tr>
<?php
	$leaf_bag += $row3['leaf_bag'];
	$leaf_kgs += $row3['leaf_kgs'];
	$dust_bag += $row3['dust_bag'];
	$dust_kgs += $row3['dust_kgs'];
	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
} // foreach
?>
<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:0px; color:red;">INTERNAL TOTAL : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $leaf_bag; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($leaf_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $dust_bag; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($dust_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;">
		<?php echo ($total_amount > 0 && $total_kgs > 0) ? number_format($total_amount / $total_kgs,2,'.',',') : '0.00'; ?>
	</th>
</tr>



<?php
$sl2=1;
$leaf_bag2 = 0;
$leaf_kgs2 = 0;
$dust_bag2 = 0;
$dust_kgs2 = 0;
$total_bags2 = 0;
$total_kgs2 = 0;
$total_amount2 = 0;

// EXPORT BUYER	
$this->db->select("buyer_type, buyer_name, sum(leaf_bag) AS leaf_bag, sum(dust_bag) AS dust_bag, sum(leaf_kgs) AS leaf_kgs, sum(dust_kgs)  AS dust_kgs, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, (sum(buyer_amount) / sum(buyer_kgs)) AS average "); 	
$this->db->from('buyer_purchase_analysis');
$this->db->where('buyer_type','Export');
$this->db->group_by('buyer_id');
$this->db->order_by('buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	$count2 = $sl2++;
?>

<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase; font-weight:bold;">
		<?php echo ($count2==1) ? $row3['buyer_type'] : ''; ?>
	</td>
	<td style="text-align:left; border:0px; font-weight:bold;"><?php echo $row3['buyer_name']; ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['leaf_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['leaf_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['dust_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['dust_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['average'],2,'.',','); ?></td>
</tr>	

<?php
	$leaf_bag2 += $row3['leaf_bag'];
	$leaf_kgs2 += $row3['leaf_kgs'];
	$dust_bag2 += $row3['dust_bag'];
	$dust_kgs2 += $row3['dust_kgs'];
	$total_bags2 += $row3['total_bags'];
	$total_kgs2 += $row3['total_kgs'];
	$total_amount2 += $row3['total_amount'];

} // foreach

if($total_bags2 > 0){
?>
<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:0px; color:red;">EXPORT TOTAL : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $leaf_bag2; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($leaf_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $dust_bag2; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($dust_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $total_bags2; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;">
		<?php echo ($total_amount2 > 0 && $total_kgs2 > 0) ? number_format($total_amount2 / $total_kgs2,2,'.',',') : '0.00'; ?>
	</th>
</tr>
<?php } ?>

<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:1px solid; color:red;">GRAND TOTAL : </th>
	
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $leaf_bag + $leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($leaf_kgs + $leaf_kgs2,2,'.',''); ?></th>
	
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $dust_bag + $dust_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($dust_kgs + $dust_kgs2,2,'.',''); ?></th>
	
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $total_bags + $total_bags2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_kgs + $total_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo number_format($total_amount + $total_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo (($total_amount + $total_amount2) > 0 && ($total_kgs + $total_kgs2) > 0) ? number_format(($total_amount + $total_amount2) / ($total_kgs + $total_kgs2),2,'.',',') : '0.00'; ?>
	</th>
</tr>

</tbody>
</table>

</div>
</div>
<?php } ?>



<?php if($report_no=='11'){

## create temporary table
$this->db->query("DROP TABLE IF EXISTS `garden_wise_offering_vs_sales`");

$this->db->query("CREATE TABLE `garden_wise_offering_vs_sales` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no,
	invoice_entry.catalouge_name,
	
	/* ++++ OFFERING BAG ++++ */
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN invoice_entry.chest_number END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN invoice_entry.chest_number END,0)
	) AS leaf_offering_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN invoice_entry.chest_number END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN invoice_entry.chest_number END,0)
	) AS dust_offering_bag,
	
	
	/* ++++ OFFERING KGS ++++ */
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN invoice_entry.net_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN invoice_entry.net_kgs END,0)
	) AS leaf_offering_kgs,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN invoice_entry.net_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN invoice_entry.net_kgs END,0)
	) AS dust_offering_kgs, 
	
	
	/* ++++ SOLD BAG ++++ */
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END,0) +
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END,0)
		
	) AS leaf_sold_bag, 
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END,0) +
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END,0)
		
	) AS dust_sold_bag,
	
	/* ++++ SOLD KGS ++++ */
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END,0) +
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END,0)
		
	) AS leaf_sold_kgs, 
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END,0) +
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END,0)
		
	) AS dust_sold_kgs,
	
	/* ++++ SOLD AMOUNT ++++ */
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_amount END,0) +
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_amount END,0)
		
	) AS leaf_sold_amount, 
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_amount END,0) +
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_amount END,0)
		
	) AS dust_sold_amount

FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
$fetch_gn = $query->row_array();
?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
	<tr>
		<th colspan="8" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="8" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:14px; font-weight:bold;"><?php print $owner_address; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="8" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">GARDEN WISE OFFERING VS SALES</b>
		</th>
	</tr>
	<tr>
		<th colspan="8" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="8" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:15px;">Garden Name: <?php print $fetch_gn['garden_name']; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="3" class="text-left" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
		</th>
		<th colspan="2" style="border:0px;"></th>
		<th colspan="3" class="text-right" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
		</th>
	</tr>
	<tr>
		<th rowspan="2" style="text-align:center; border:1px solid;">SALE NO</th>
		<th rowspan="2" style="text-align:center; border:1px solid;">CATALOGUE</th>
		
		<th colspan="2" style="text-align:center; border:1px solid;">OFFERING</th>
		<th colspan="5" style="text-align:center; border:1px solid;">SOLD</th>
	</tr>
	<tr>
		<th style="text-align:right; border:1px solid;">BAGS</th>
		<th style="text-align:right; border:1px solid;">KGS</th>
		
		<th style="text-align:right; border:1px solid;">BAGS</th>
		<th style="text-align:right; border:1px solid;">KGS</th>
		<th style="text-align:right; border:1px solid;">AMOUNT</th>
		<th style="text-align:right; border:1px solid;">AVG.</th>
		<th style="text-align:right; border:1px solid;">%</th>
	</tr>
</thead>
<tbody>
<?php
$sl = 1;
$leaf_offering_bag = 0;
$leaf_offering_kgs = 0;
$leaf_sold_bag = 0;
$leaf_sold_kgs = 0;
$leaf_sold_amount = 0;

$dust_offering_bag = 0;
$dust_offering_kgs = 0;
$dust_sold_bag = 0;
$dust_sold_kgs = 0;
$dust_sold_amount = 0;

// INTERNAL BUYER	
$this->db->select("
	sale_no, 
	
	sum(leaf_offering_bag) AS leaf_offering_bag, 
	sum(leaf_offering_kgs) AS leaf_offering_kgs,
	
	sum(dust_offering_bag) AS dust_offering_bag,  
	sum(dust_offering_kgs)  AS dust_offering_kgs, 
	
	sum(leaf_sold_bag) AS leaf_sold_bag,
	sum(leaf_sold_kgs) AS leaf_sold_kgs, 
	
	sum(dust_sold_bag) AS dust_sold_bag,
	sum(dust_sold_kgs) AS dust_sold_kgs, 
	
	sum(leaf_sold_amount) AS leaf_sold_amount,
	sum(dust_sold_amount) AS dust_sold_amount, 
	
	(sum(leaf_sold_amount) / sum(leaf_sold_kgs)) AS leaf_sold_average,
	(sum(dust_sold_amount) / sum(dust_sold_kgs)) AS dust_sold_average,
	((sum(leaf_sold_amount) + sum(dust_sold_amount)) / (sum(leaf_sold_kgs) + sum(dust_sold_kgs))) AS total_average
"); 	
$this->db->from('garden_wise_offering_vs_sales');
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	$count = $sl++;
?>
<tr>
	<td style="text-align:center; border:0px; font-weight:bold;"><?php echo $row3['sale_no']; ?></td>
	<td style="text-align:center; border:0px; font-weight:bold;">LEAF</td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['leaf_offering_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['leaf_offering_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['leaf_sold_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['leaf_sold_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['leaf_sold_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['leaf_sold_average'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo ($row3['leaf_sold_bag'] > 0 && $row3['leaf_offering_bag'] > 0) ? round(($row3['leaf_sold_bag'] / $row3['leaf_offering_bag'])*100,2) : '0.00'; ?>%</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;"></td>
	<td style="text-align:center; border:0px; font-weight:bold;">DUST</td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['dust_offering_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['dust_offering_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['dust_sold_bag']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['dust_sold_kgs'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['dust_sold_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['dust_sold_average'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo ($row3['dust_sold_bag'] > 0 && $row3['dust_offering_bag'] > 0) ? round(($row3['dust_sold_bag'] / $row3['dust_offering_bag'])*100,2) : '0.00'; ?>%</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;"></td>
	<td style="text-align:center; border:1px solid; font-weight:bold;">TOTAL</td>
	
	<td style="text-align:right; border:1px solid;">
		<?php echo $row3['leaf_offering_bag'] + $row3['dust_offering_bag']; ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['leaf_offering_kgs'] + $row3['dust_offering_kgs'],2,'.',''); ?>
	</td>
	
	<td style="text-align:right; border:1px solid;">
		<?php echo $row3['leaf_sold_bag'] + $row3['dust_sold_bag']; ?>
	</td>
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['leaf_sold_kgs'] + $row3['dust_sold_kgs'],2,'.',''); ?>
	</td>
	
	<td style="text-align:right; border:1px solid;">
		<?php echo number_format($row3['leaf_sold_amount'] + $row3['dust_sold_amount'],2,'.',','); ?>
	</td>
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['total_average'],2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid;">
		<?php echo round((($row3['leaf_sold_bag'] + $row3['dust_sold_bag']) / ($row3['leaf_offering_bag'] + $row3['dust_offering_bag']))*100,2); ?>%
	</td>
</tr>
	
<?php
	$leaf_offering_bag += $row3['leaf_offering_bag'];
	$leaf_offering_kgs += $row3['leaf_offering_kgs'];
	$leaf_sold_bag += $row3['leaf_sold_bag'];
	$leaf_sold_kgs += $row3['leaf_sold_kgs'];
	$leaf_sold_amount += $row3['leaf_sold_amount'];
	
	$dust_offering_bag += $row3['dust_offering_bag'];
	$dust_offering_kgs += $row3['dust_offering_kgs'];
	$dust_sold_bag += $row3['dust_sold_bag'];
	$dust_sold_kgs += $row3['dust_sold_kgs'];
	$dust_sold_amount += $row3['dust_sold_amount'];
	
} // foreach
?>
<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:0px; color:red;">LEAF TOTAL : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $leaf_offering_bag; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($leaf_offering_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $leaf_sold_bag; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($leaf_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($leaf_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;">
		<?php echo ($leaf_sold_amount > 0 && $leaf_sold_kgs > 0) ? number_format($leaf_sold_amount / $leaf_sold_kgs,2,'.',',') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px; color:red;">
		<?php echo ($leaf_sold_bag > 0 && $leaf_offering_bag > 0) ? number_format(($leaf_sold_bag / $leaf_offering_bag)*100,2,'.',',') : '0.00'; ?>%
	</th>
</tr>

<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:0px; color:red;">DUST TOTAL : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $dust_offering_bag; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($dust_offering_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $dust_sold_bag; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($dust_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($dust_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;">
		<?php echo ($dust_sold_amount > 0 && $dust_sold_kgs > 0) ? number_format($dust_sold_amount / $dust_sold_kgs,2,'.',',') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px; color:red;">
		<?php echo ($dust_sold_bag > 0 && $dust_offering_bag > 0) ? number_format(($dust_sold_bag / $dust_offering_bag)*100,2) : '0.00'; ?>%
	</th>
</tr>

<tr>
	<th style="text-align:left; border:0px;"></th>
	<th style="text-align:right; border:1px solid; color:red; font-weight:bold;">GRAND TOTAL :</th>
	
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo $leaf_offering_bag + $dust_offering_bag; ?>
	</th>
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo number_format($leaf_offering_kgs + $dust_offering_kgs,2,'.',''); ?>
	</th>
	
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo $leaf_sold_bag + $dust_sold_bag; ?>
	</th>
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo number_format($leaf_sold_kgs + $dust_sold_kgs,2,'.',''); ?>
	</th>
	
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo number_format($leaf_sold_amount + $dust_sold_amount,2,'.',','); ?>
	</th>
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo (($leaf_sold_amount > 0 && $leaf_sold_kgs > 0) || ($dust_sold_amount > 0 && $dust_sold_kgs > 0)) ? number_format(($leaf_sold_amount + $dust_sold_amount) / ($leaf_sold_kgs + $dust_sold_kgs),2,'.',',') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:1px solid; color:red;">
		<?php echo (($leaf_sold_bag > 0 && $dust_sold_bag > 0) || ($leaf_offering_bag > 0 && $dust_offering_bag > 0)) ? round((($leaf_sold_bag + $dust_sold_bag) / ($leaf_offering_bag + $dust_offering_bag)*100),2) : '0.00'; ?>%
	</th>
</tr>
</tbody>
</table>

</div>
</div>
<?php } ?>


<?php if($report_no=='12'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


// total offering
$query = $this->db->query("SELECT sum(chest_number) AS total_offering FROM `invoice_entry` WHERE season='".$season."' AND sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' ");
$fetch_net_offering = $query->row_array();
?>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?></b>
	</th>
</tr>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:14px; font-weight:bold;"><?php print $owner_address; ?></b>
	</th>
</tr>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">GRADE WISE OFFERING VS SALES</b>
	</th>
</tr>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
	</th>
</tr>
<tr>
	<th colspan="4" class="text-left" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
	</th>
	<th colspan="3" style="border:0px;"></th>
	<th colspan="4" class="text-right" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
	</th>
</tr>
<tr>
    <th style="text-align:center; border:0px solid;"></th>
	<th colspan="3" style="text-align:center; border:0px solid;">OFFERING</th>
	<th colspan="7" style="text-align:center; border:0px solid;">SOLD</th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">GRADE</th>
	
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">Off. %</th>
	
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVG.</th>
    <th style="text-align:center; border:1px solid;">Sales %</th>
    <th style="text-align:center; border:1px solid;">W/D %</th>
    <th style="text-align:center; border:1px solid;">Out %</th>
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

$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
	
	$out_bags = $row3['chest_number'] - ($row3['sold_bags'] + $row3['wd']);
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px; border-right:1px solid;">
		<?php echo ($row3['chest_number'] > 0 && $row_catalogue['ctlg_wise_offering'] > 0) ? round(($row3['chest_number'] / $row_catalogue['ctlg_wise_offering'])*100,2) : '0.00'; ?>%
	</td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row3['sold_bags'] > 0 && $row3['chest_number'] > 0) ? round(($row3['sold_bags'] / $row3['chest_number'])*100,2) : '0.00'; ?>%
	</td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row3['wd'] > 0 && $row3['chest_number'] > 0) ? round(($row3['wd'] / $row3['chest_number'])*100,2) : '0.00'; ?>%
	</td>
	<td style="text-align:right; border:0px;">
		<?php echo ($out_bags > 0 && $row3['chest_number'] > 0) ? round(($out_bags / $row3['chest_number'])*100,2) : '0.00'; ?>%
	</td>
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
<tr>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; border-right:1px solid;">
		<?php echo ($chest_number > 0 && $row_catalogue['ctlg_wise_offering'] > 0) ? round(($chest_number / $row_catalogue['ctlg_wise_offering'])*100,2) : '0.00'; ?>%
	</th>
	
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($sold_amount > 0 && $sold_kgs > 0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($sold_bags > 0 && $chest_number > 0) ? round(($sold_bags / $chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_wd > 0 && $chest_number > 0) ? round(($total_wd / $chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_out_bags > 0 && $chest_number > 0) ? round(($total_out_bags / $chest_number)*100,2) : '0.00'; ?>%
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
<tr>
	<th style="text-align:left; border:1px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
	<th style="text-align:right; border:1px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($g_chest_number > 0 && $fetch_net_offering['total_offering'] > 0) ? round(($g_chest_number / $fetch_net_offering['total_offering'])*100,2) : '0.00'; ?>%
	</th>
	
	<th style="text-align:right; border:1px solid;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($sold_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($sold_amount2 > 0 && $sold_kgs2 > 0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($sold_bags2 > 0 && $g_chest_number > 0) ? round(($sold_bags2 / $g_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($total_wd2 > 0 && $g_chest_number > 0) ? round(($total_wd2 / $g_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($total_out_bags2 > 0 && $g_chest_number > 0) ? round(($total_out_bags2 / $g_chest_number)*100,2) : '0.00'; ?>%
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
<tr>
	<th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"></th>
	
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_sold_amount > 0 && $gt_sold_kgs > 0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_sold_bags > 0 && $gt_chest_number > 0) ? round(($gt_sold_bags / $gt_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_total_wd > 0 && $gt_chest_number > 0) ? round(($gt_total_wd / $gt_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_total_out_bags > 0 && $gt_chest_number > 0) ? round(($gt_total_out_bags / $gt_chest_number)*100,2) : '0.00'; ?>%
	</th>
</tr>
</tbody>
</table>
</div>
</div> 
<!-- /row -->
<?php } ?>

<?php if($report_no=='13'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


// total offering
$query = $this->db->query("SELECT sum(chest_number) AS total_offering FROM `invoice_entry` WHERE season='".$season."' AND sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND garden_id='".$garden_id."' ");
$fetch_net_offering = $query->row_array();

// garden name
$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
$fetch_gn = $query->row_array();
?>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?></b>
	</th>
</tr>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:14px; font-weight:bold;"><?php print $owner_address; ?></b>
	</th>
</tr>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">GRADE WISE OFFERING VS SALES</b>
	</th>
</tr>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
	</th>
</tr>
<tr>
	<th colspan="11" class="text-center" style="border:0px;">
		<b style="text-transform: uppercase; font-size:15px;">Garden Name: <?php print $fetch_gn['garden_name']; ?></b>
	</th>
</tr>
<tr>
	<th colspan="4" class="text-left" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
	</th>
	<th colspan="3" style="border:0px;"></th>
	<th colspan="4" class="text-right" style="border:0px;">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
	</th>
</tr>
<tr>
    <th style="text-align:center; border:0px solid;"></th>
	<th colspan="3" style="text-align:center; border:0px solid;">OFFERING</th>
	<th colspan="7" style="text-align:center; border:0px solid;">SOLD</th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">GRADE</th>
	
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">Off. %</th>
	
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVG.</th>
    <th style="text-align:center; border:1px solid;">Sales %</th>
    <th style="text-align:center; border:1px solid;">W/D %</th>
    <th style="text-align:center; border:1px solid;">Out %</th>
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
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);

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
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);

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
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);

$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
	
	$out_bags = $row3['chest_number'] - ($row3['sold_bags'] + $row3['wd']);
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px; border-right:1px solid;">
		<?php echo ($row3['chest_number'] > 0 && $row_catalogue['ctlg_wise_offering'] > 0) ? round(($row3['chest_number'] / $row_catalogue['ctlg_wise_offering'])*100,2) : '0.00'; ?>%
	</td>
	
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row3['sold_bags'] > 0 && $row3['chest_number'] > 0) ? round(($row3['sold_bags'] / $row3['chest_number'])*100,2) : '0.00'; ?>%
	</td>
	<td style="text-align:right; border:0px;">
		<?php echo ($row3['wd'] > 0 && $row3['chest_number'] > 0) ? round(($row3['wd'] / $row3['chest_number'])*100,2) : '0.00'; ?>%
	</td>
	<td style="text-align:right; border:0px;">
		<?php echo ($out_bags > 0 && $row3['chest_number'] > 0) ? round(($out_bags / $row3['chest_number'])*100,2) : '0.00'; ?>%
	</td>
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
<tr>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; border-right:1px solid;">
		<?php echo ($chest_number > 0 && $row_catalogue['ctlg_wise_offering'] > 0) ? round(($chest_number / $row_catalogue['ctlg_wise_offering'])*100,2) : '0.00'; ?>%
	</th>
	
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($sold_amount > 0 && $sold_kgs > 0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($sold_bags > 0 && $chest_number > 0) ? round(($sold_bags / $chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_wd > 0 && $chest_number > 0) ? round(($total_wd / $chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($total_out_bags > 0 && $chest_number > 0) ? round(($total_out_bags / $chest_number)*100,2) : '0.00'; ?>%
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
<tr>
	<th style="text-align:left; border:1px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
	<th style="text-align:right; border:1px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($g_chest_number > 0 && $fetch_net_offering['total_offering'] > 0) ? round(($g_chest_number / $fetch_net_offering['total_offering'])*100,2) : '0.00'; ?>%
	</th>
	
	<th style="text-align:right; border:1px solid;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($sold_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($sold_amount2 > 0 && $sold_kgs2 > 0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($sold_bags2 > 0 && $g_chest_number > 0) ? round(($sold_bags2 / $g_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($total_wd2 > 0 && $g_chest_number > 0) ? round(($total_wd2 / $g_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php echo ($total_out_bags2 > 0 && $g_chest_number > 0) ? round(($total_out_bags2 / $g_chest_number)*100,2) : '0.00'; ?>%
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
<tr>
	<th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"></th>
	
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_sold_amount > 0 && $gt_sold_kgs > 0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?>
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_sold_bags > 0 && $gt_chest_number > 0) ? round(($gt_sold_bags / $gt_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_total_wd > 0 && $gt_chest_number > 0) ? round(($gt_total_wd / $gt_chest_number)*100,2) : '0.00'; ?>%
	</th>
	<th style="text-align:right; border:0px;">
		<?php echo ($gt_total_out_bags > 0 && $gt_chest_number > 0) ? round(($gt_total_out_bags / $gt_chest_number)*100,2) : '0.00'; ?>%
	</th>
</tr>
</tbody>
</table>
</div>
</div> 
<!-- /row -->
<?php } ?>

<?php if($report_no=='14'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
	<div class="form-group col-sm-12">
		<table class="table table-sm" id="table">
			<thead>
				<tr>
					<th style="text-transform: uppercase; text-align:center; border:0px;">
					<span style="font-size:16px;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
					OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> <br /> Current Season
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>


<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="2" style="text-align:left; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//garden breakdown
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>

<tr>
    <th colspan="4" style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=7'); ?>"><?php print $row1['garden_name']; ?></a>
	</th>
	<th colspan="4" style="font-size:14px !important; border:0px; text-align:right;"></th>
</tr>

<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."' AND old_season='".$season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> 
<!-- /row -->


<!-- GRAND TOTAL -->
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;">GRAND TOTAL</th>
	<th colspan="2" style="text-align:center; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
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

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
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
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.soft_delete', 1);
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

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE old_season='".$season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
</tbody>
</table>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>

<?php if($report_no=='15'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
	<div class="form-group col-sm-12">
		<table class="table table-sm" id="table">
			<thead>
				<tr>
					<th style="text-transform: uppercase; text-align:center; border:0px;">
					<span style="font-size:16px;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
					OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
						<br /> Old / New Season
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>


<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="2" style="text-align:left; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//garden breakdown
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<tr>
    <th colspan="4" style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id'].'&report=7'); ?>"><?php print $row1['garden_name']; ?></a>
	</th>
	<th colspan="4" style="font-size:14px !important; border:0px; text-align:right;"><?php print $old_season; ?></th>
</tr>
<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
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

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."' AND old_season='".$old_season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> 
<!-- /row -->


<!-- GRAND TOTAL -->
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;">GRAND TOTAL</th>
	<th colspan="2" style="text-align:center; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
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

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
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
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.soft_delete', 1);
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

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE old_season='".$old_season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
</tbody>
</table>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>


<?php if($report_no=='16'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right">
		<a class="no-print" href="#" onClick="printDiv('printableArea')">
			<span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
		</a>
	</div>
	
	<div class="form-group col-sm-12 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><?php print $owner_short_address; ?></span>
		<br />
		<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			GARDEN OFFERING & SALES REPORT
		</b>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-4 text-left">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?></b>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4 text-right">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?> <br> Print : <?php print date('M-d-y', strtotime($current_date_time)) ; ?> <?php print date('h:i', strtotime($current_date_time)) ; ?></b>
	</div>
</div>
<style>
	#table tr td {
		font-size:12px !important;
		padding:3px;
	}
	#table tr th{
		font-size:13px !important;
		padding:3px;
	}

	@page {
		margin-bottom: 100px; /* Add a bottom gap of 100px */
	}

	@page {

		/* Top-right corner: Page X */
		@top-right {
			content: counter(page) " of " counter(pages);
			font-size: 12px;
		}
    }

	.page-break {
		page-break-before: always;
		break-before: page;
	}
</style>
<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:left; border:1px solid;">Garden Name</th>
	<th colspan="2" style="text-align:right; border:1px solid;">Offering</th>
	<th style="text-align:right; border:1px solid;">Sold Bags</th>
	<th style="text-align:right; border:1px solid;">Sold Kgs</th>
	<th style="text-align:right; border:1px solid;">Amount Tk.</th>
	<th style="text-align:right; border:1px solid;">Price/Kg</th>
</tr>
</thead>
<tbody>
<?php
$net_offering = 0;
$net_sold_bags = 0;
$net_sold_kgs = 0;
$net_amount_tk = 0;
//query
$this->db->select("invoice_entry.garden_id, invoice_entry.old_season, CASE WHEN invoice_entry.old_season='".$season."' THEN 'A' ELSE 'B' END AS serializing"); 
$this->db->from('invoice_entry');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_from);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.old_season');
$this->db->order_by('serializing ASC');
$query = $this->db->get();
$fetch_list = $query->result_array();
foreach($fetch_list as $fetch){ 
?>

<tr>
	<th colspan="7" class="text-danger" style="border:0px; font-size:13px !important; font-family:verdana;">
		SEASON: <?php print $fetch['old_season']; ?>
	</th>
</tr>

<?php
// reset value
$nt_chest_number=0;
$nt_net_kgs=0;

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

$nt_sold_bag_green_tea=0;
$nt_sold_kgs_green_tea=0;
$nt_sold_bag_orthodox=0;
$nt_sold_kgs_orthodox=0;
$nt_sold_tk_green_tea=0;
$nt_sold_tk_orthodox=0;
$nt_wd_bag_green_tea=0;
$nt_wd_kgs_green_tea=0;
$nt_wd_bag_orthodox=0;
$nt_wd_kgs_orthodox=0;
$nt_out_bag_green_tea=0;
$nt_out_kgs_green_tea=0;
$nt_out_bag_orthodox=0;
$nt_out_kgs_dorthodox=0;

$nt_leaf_offering = 0;
$nt_dust_offering = 0;
$nt_green_tea_offering = 0;

//query
$this->db->select("invoice_entry.garden_id, invoice_entry.old_season, garden_and_company_setup.garden_name, CASE WHEN invoice_entry.old_season='".$season."' THEN 'A' ELSE 'B' END AS serializing"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->join('garden_origin_setup','garden_and_company_setup.origin = garden_origin_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_from);
$this->db->where('invoice_entry.old_season', $fetch['old_season']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id, invoice_entry.old_season');
$this->db->order_by('serializing ASC, garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){ 
?>

<?php
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
$n_sold_bag_green_tea = 0;
$n_sold_kgs_green_tea = 0;	
$n_sold_bag_orthodox = 0;
$n_sold_kgs_orthodox = 0;
$n_sold_tk_green_tea = 0;
$n_sold_tk_orthodox = 0;
$n_wd_bag_green_tea = 0;
$n_wd_kgs_green_tea = 0;	
$n_wd_bag_orthodox = 0;
$n_wd_kgs_orthodox = 0;
$n_out_bag_green_tea = 0;
$n_out_kgs_green_tea = 0;	
$n_out_bag_orthodox = 0;
$n_out_kgs_orthodox = 0;
$n_leaf_offering = 0;
$n_dust_offering = 0;

$shown_gardens = [];

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
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust') THEN chest_number END),0)
	) AS dust_total_bag,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust') THEN net_kgs END),0)
	) AS dust_net_kgs,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS leaf_sold_amount,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS dust_sold_amount,

	(
		IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Green Tea') THEN chest_number END),0)
	) AS green_tea_total_bag,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Green Tea') THEN net_kgs END),0)
	) AS green_tea_net_kgs,
	(
		IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Green Tea') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS green_tea_sold_amount,
	(
		IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Orthodox') THEN chest_number END),0)
	) AS orthodox_total_bag,
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Orthodox') THEN net_kgs END),0)
	) AS orthodox_net_kgs,
	(
		IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Orthodox') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
	) AS orthodox_sold_amount
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_from);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.old_season', $row1['old_season']);
$this->db->where('invoice_entry.account_concerned','No');
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){

	//sold
	if($row3['sold_price'] > 0){ 
		
		$sold_bag_l = $row3['leaf_total_bag']; $sold_kgs_l = $row3['leaf_net_kgs']; $sold_tk_l = $row3['leaf_sold_amount']; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = 0; $out_kgs_l = 0;
		
		$sold_bag_d = $row3['dust_total_bag']; $sold_kgs_d = $row3['dust_net_kgs']; $sold_tk_d = $row3['dust_sold_amount']; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = 0; $out_kgs_d = 0;

		$sold_bag_green_tea = $row3['green_tea_total_bag']; $sold_kgs_green_tea = $row3['green_tea_net_kgs']; $sold_tk_green_tea = $row3['green_tea_sold_amount']; $wd_bag_green_tea = 0; $wd_kgs_green_tea = 0; $out_bag_green_tea = 0; $out_kgs_green_tea = 0;

		$sold_bag_orthodox = $row3['orthodox_total_bag']; $sold_kgs_orthodox = $row3['orthodox_net_kgs']; $sold_tk_orthodox = $row3['orthodox_sold_amount']; $wd_bag_orthodox = 0; $wd_kgs_orthodox = 0; $out_bag_orthodox = 0; $out_kgs_orthodox = 0;
		

	//wd	
	}else if(!empty($row3['remarks'])){
		
		$sold_bag_l = 0; $sold_kgs_l = 0; $sold_tk_l = 0; $wd_bag_l = $row3['leaf_total_bag']; $wd_kgs_l = $row3['leaf_net_kgs']; $out_bag_l = 0; $out_kgs_l = 0;
		
		$sold_bag_d = 0; $sold_kgs_d = 0; $sold_tk_d = 0; $wd_bag_d = $row3['dust_total_bag']; $wd_kgs_d = $row3['dust_net_kgs']; $out_bag_d = 0; $out_kgs_d = 0;

		$sold_bag_green_tea = 0; $sold_kgs_green_tea = 0; $sold_tk_green_tea = 0; $wd_bag_green_tea = $row3['green_tea_total_bag']; $wd_kgs_green_tea = $row3['green_tea_net_kgs']; $out_bag_green_tea = 0; $out_kgs_green_tea = 0;

		$sold_bag_orthodox = 0; $sold_kgs_orthodox = 0; $sold_tk_orthodox = 0; $wd_bag_orthodox = $row3['orthodox_total_bag']; $wd_kgs_orthodox = $row3['orthodox_net_kgs']; $out_bag_orthodox = 0; $out_kgs_orthodox = 0;

	//out	
	}else{
		
		$sold_bag_l = 0; $sold_kgs_l = 0; $sold_tk_l = 0; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = $row3['leaf_total_bag']; $out_kgs_l = $row3['leaf_net_kgs'];
		
		$sold_bag_d = 0; $sold_kgs_d = 0; $sold_tk_d = 0; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = $row3['dust_total_bag']; $out_kgs_d = $row3['dust_net_kgs'];

		$sold_bag_green_tea = 0; $sold_kgs_green_tea = 0; $sold_tk_green_tea = 0; $wd_bag_green_tea = 0; $wd_kgs_green_tea = 0; $out_bag_green_tea = $row3['green_tea_total_bag']; $out_kgs_green_tea = $row3['green_tea_net_kgs'];

		$sold_bag_orthodox = 0; $sold_kgs_orthodox = 0; $sold_tk_orthodox = 0; $wd_bag_orthodox = 0; $wd_kgs_orthodox = 0; $out_bag_orthodox = $row3['orthodox_total_bag']; $out_kgs_orthodox = $row3['orthodox_net_kgs'];
	}

	$n_net_kgs += $row3['net_kgs'];
	$n_chest_number += $row3['chest_number'];
	$n_sold_amount += $row3['sold_amount'];
	$n_sold_bag_l += $sold_bag_l;
	$n_sold_kgs_l += $sold_kgs_l;
	$n_sold_bag_d += $sold_bag_d;
	$n_sold_kgs_d += $sold_kgs_d;
	$n_sold_bag_green_tea += $sold_bag_green_tea;
	$n_sold_kgs_green_tea += $sold_kgs_green_tea;	
	$n_sold_bag_orthodox += $sold_bag_orthodox;
	$n_sold_kgs_orthodox += $sold_kgs_orthodox;
	$n_sold_tk_l += $sold_tk_l;
	$n_sold_tk_d += $sold_tk_d;
	$n_sold_tk_green_tea += $sold_tk_green_tea;
	$n_sold_tk_orthodox += $sold_tk_orthodox;

	$n_wd_bag_l += $wd_bag_l;
	$n_wd_kgs_l += $wd_kgs_l;

	$n_wd_bag_d += $wd_bag_d;
	$n_wd_kgs_d += $wd_kgs_d;

	$n_wd_bag_green_tea += $wd_bag_green_tea;
	$n_wd_kgs_green_tea += $wd_kgs_green_tea;	
	$n_wd_bag_orthodox += $wd_bag_orthodox;
	$n_wd_kgs_orthodox += $wd_kgs_orthodox;

	$n_out_bag_l += $out_bag_l;
	$n_out_kgs_l += $out_kgs_l;

	$n_out_bag_d += $out_bag_d;
	$n_out_kgs_d += $out_kgs_d;

	$n_out_bag_green_tea += $out_bag_green_tea;
	$n_out_kgs_green_tea += $out_kgs_green_tea;	
	$n_out_bag_orthodox += $out_bag_orthodox;
	$n_out_kgs_orthodox += $out_kgs_orthodox;
	
	$n_leaf_offering += $n_sold_bag_l + $n_wd_bag_l + $n_out_bag_l;
	$n_dust_offering += $n_sold_bag_d + $n_wd_bag_d + $n_out_bag_d;

}  // 2nd loop

$leaf_offering = $n_sold_bag_l + $n_wd_bag_l + $n_out_bag_l;
$leaf_offering_kgs = $n_sold_kgs_l + $n_wd_kgs_l + $n_out_kgs_l;

$dust_offering = $n_sold_bag_d + $n_wd_bag_d + $n_out_bag_d;
$dust_offering_kgs = $n_sold_kgs_d + $n_wd_kgs_d + $n_out_kgs_d;

$green_tea_offering = $n_sold_bag_green_tea + $n_wd_bag_green_tea + $n_out_bag_green_tea;
$green_tea_offering_kgs = $n_sold_kgs_green_tea + $n_wd_kgs_green_tea + $n_out_kgs_green_tea;

$orthodox_offering = $n_sold_bag_orthodox + $n_wd_bag_orthodox + $n_out_bag_orthodox;
$orthodox_offering_kgs = $n_sold_kgs_orthodox + $n_wd_kgs_orthodox + $n_out_kgs_orthodox;
?>

<?php if($leaf_offering > 0){ ?> 
<tr>
	<td style="text-align:left; border:0px;">
		<?php
		$garden_id = $row1['garden_id'];

		if (!in_array($garden_id, $shown_gardens)) {
			$shown_gardens[] = $garden_id; // mark as shown
		?>
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season=' . $season . '&auction_center=' . $auction_center . '&sale_from=' . $sale_from . '&sale_to=' . $sale_to . '&garden_id=' . $garden_id . '&report=2'); ?>">
			<b><?php echo $row1['garden_name']; ?></b>
		</a>
		<?php } ?>
	</td>
	<td style="text-align:left; border:0px;">Leaf</td>
	<td style="text-align:right; border:0px;"><?php print $leaf_offering; ?></td>
	<td style="text-align:right; border:0px;"><?php print $n_sold_bag_l; ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($n_sold_kgs_l,2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($n_sold_tk_l,2,'.',','); ?></td>
	<td style="text-align:right; border:1px; solid">
	<?php 
		print ($n_sold_tk_l > 0 && $n_sold_kgs_l > 0) ? number_format($n_sold_tk_l / $n_sold_kgs_l,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<?php } ; ?>

<?php if($dust_offering > 0){ ?> 
<tr>
	<td style="text-align:left; border:0px;">
		<?php
		$garden_id = $row1['garden_id'];

		if (!in_array($garden_id, $shown_gardens)) {
			$shown_gardens[] = $garden_id; // mark as shown
		?>
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season=' . $season . '&auction_center=' . $auction_center . '&sale_from=' . $sale_from . '&sale_to=' . $sale_to . '&garden_id=' . $garden_id . '&report=2'); ?>">
			<b><?php echo $row1['garden_name']; ?></b>
		</a>
		<?php } ?>
	</td>
	<td style="text-align:left; border:0px;">Dust</td>
	<td style="text-align:right; border:0px;"><?php print $dust_offering; ?></td>
	<td style="text-align:right; border:0px;"><?php print $n_sold_bag_d; ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($n_sold_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($n_sold_tk_d,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($n_sold_tk_d > 0 && $n_sold_kgs_d > 0) ? number_format($n_sold_tk_d / $n_sold_kgs_d,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<?php } ; ?>

<?php if($orthodox_offering > 0){ ?> 
<tr>
	<td style="text-align:left; border:0px;">
		<?php
		$garden_id = $row1['garden_id'];

		if (!in_array($garden_id, $shown_gardens)) {
			$shown_gardens[] = $garden_id; // mark as shown
		?>
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season=' . $season . '&auction_center=' . $auction_center . '&sale_from=' . $sale_from . '&sale_to=' . $sale_to . '&garden_id=' . $garden_id . '&report=2'); ?>">
			<b><?php echo $row1['garden_name']; ?></b>
		</a>
		<?php } ?>
	</td>
	<td style="text-align:left; border:0px;">Orthodox</td>
	<td style="text-align:right; border:0px;"><?php print $orthodox_offering; ?></td>
	<td style="text-align:right; border:0px;"><?php print $n_sold_bag_orthodox; ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($n_sold_kgs_orthodox,2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($n_sold_tk_orthodox,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($n_sold_tk_orthodox > 0 && $n_sold_kgs_orthodox > 0) ? number_format($n_sold_tk_orthodox / $n_sold_kgs_orthodox,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<?php } ; ?>

<?php if($leaf_offering > 0 || $dust_offering > 0 || $green_tea_offering > 0 || $orthodox_offering > 0){ ?> 
<tr>
	<td style="text-align:left; border-bottom: 1px solid #ccc; border-top: 0px; "></td>
	<td style="text-align:left; border-bottom: 1px solid #ccc; border-top:0px; font-weight: bold;">CTC Total: </td>
	<td style="text-align:right; border-bottom: 1px solid #ccc; border-top:0px; font-weight: bold;"><?php print $leaf_offering + $dust_offering; ?></td>
	<td style="text-align:right; border-bottom: 1px solid #ccc; border-top:0px; font-weight: bold;"><?php print $n_sold_bag_l + $n_sold_bag_d; ?></td>
	<td style="text-align:right; border-bottom: 1px solid #ccc; border-top:0px; font-weight: bold;"><?php print number_format($n_sold_kgs_l + $n_sold_kgs_d,2,'.',','); ?></td>
	<td style="text-align:right; border-bottom: 1px solid #ccc; border-top:0px; font-weight: bold;"><?php print number_format($n_sold_tk_l + $n_sold_tk_d,2,'.',','); ?></td>
	<td style="text-align:right; border-bottom: 1px solid #ccc; border-top:0px; font-weight: bold;">
	<?php 
		print (($n_sold_tk_l + $n_sold_tk_d) > 0 && ($n_sold_kgs_l + $n_sold_kgs_d) > 0) ? number_format(($n_sold_tk_l + $n_sold_tk_d) / ($n_sold_kgs_l + $n_sold_kgs_d),2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<?php } ; ?>

<?php if($green_tea_offering > 0){ ?> 
<tr>
	<td style="text-align:left; border:0px;">
		<?php
		$garden_id = $row1['garden_id'];

		if (!in_array($garden_id, $shown_gardens)) {
			$shown_gardens[] = $garden_id; // mark as shown
		?>
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season=' . $season . '&auction_center=' . $auction_center . '&sale_from=' . $sale_from . '&sale_to=' . $sale_to . '&garden_id=' . $garden_id . '&report=2'); ?>">
			<b><?php echo $row1['garden_name']; ?></b>
		</a>
		<?php } ?>
	</td>
	<td style="text-align:left; border-bottom:1px solid;  border-top:0px; font-weight: bold; color: green;">Green Tea</td>
	<td style="text-align:right; border-bottom:1px solid; font-weight: bold; color: green;"><?php print $green_tea_offering; ?></td>
	<td style="text-align:right; border-bottom:1px solid;  font-weight: bold; color: green;"><?php print $n_sold_bag_green_tea; ?></td>
	<td style="text-align:right; border-bottom:1px solid;  font-weight: bold; color: green;"><?php print number_format($n_sold_kgs_green_tea,2,'.',''); ?></td>
	<td style="text-align:right; border-bottom:1px solid; font-weight: bold; color: green;"><?php print number_format($n_sold_tk_green_tea,2,'.',','); ?></td>
	<td style="text-align:right; border-bottom:1px solid; font-weight: bold; color: green;">
	<?php 
		print ($n_sold_tk_green_tea > 0 && $n_sold_kgs_green_tea > 0) ? number_format($n_sold_tk_green_tea / $n_sold_kgs_green_tea,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<?php } ; ?>
<?php if($green_tea_offering > 0){ ?> 
<tr>
	<td style="text-align:left;"></td>
	<td style="text-align:left; border-bottom:2px solid; border-top:0px; font-weight: bold;">Sub Total</td>
	<td style="text-align:right; border-bottom:2px solid; border-top:0px; font-weight: bold;">
		<?php print $leaf_offering + $dust_offering + $green_tea_offering; ?>
	</td>
	<td style="text-align:right; border-bottom:2px solid; border-top:0px; font-weight: bold;">
		<?php print $n_sold_bag_l + $n_sold_bag_d + $n_sold_bag_green_tea; ?>
	</td>
	<td style="text-align:right; border-bottom:2px solid; border-top:0px; font-weight: bold;">
		<?php print number_format($n_sold_kgs_l + $n_sold_kgs_d + $n_sold_kgs_green_tea,2,'.',','); ?>
	</td>
	<td style="text-align:right; border-bottom:2px solid; border-top:0px; font-weight: bold;">
		<?php print number_format($n_sold_tk_l + $n_sold_tk_d + $n_sold_tk_green_tea,2,'.',','); ?>
	</td>
	<td style="text-align:right; border-bottom:2px solid; border-top:0px; font-weight: bold;">
	<?php 
		$total_kgs = $n_sold_kgs_l + $n_sold_kgs_d + $n_sold_kgs_green_tea;
		$total_tk  = $n_sold_tk_l + $n_sold_tk_d + $n_sold_tk_green_tea;
		print ($total_tk > 0 && $total_kgs > 0) ? number_format($total_tk / $total_kgs,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<?php } ?>
<?php
$nt_chest_number += $n_chest_number;
$nt_net_kgs += $n_net_kgs;

$nt_sold_bag_l += $n_sold_bag_l;
$nt_sold_kgs_l += $n_sold_kgs_l;
$nt_sold_bag_d += $n_sold_bag_d;
$nt_sold_kgs_d += $n_sold_kgs_d;

$nt_sold_bag_green_tea += $n_sold_bag_green_tea;
$nt_sold_kgs_green_tea += $n_sold_kgs_green_tea;
$nt_sold_bag_orthodox += $n_sold_bag_orthodox;
$nt_sold_kgs_orthodox += $n_sold_kgs_orthodox;

$nt_sold_tk_l += $n_sold_tk_l;
$nt_sold_tk_d += $n_sold_tk_d;
$nt_sold_tk_green_tea += $n_sold_tk_green_tea;
$nt_sold_tk_orthodox += $n_sold_tk_orthodox;

$nt_wd_bag_l += $n_wd_bag_l;
$nt_wd_kgs_l += $n_wd_kgs_l;
$nt_wd_bag_d += $n_wd_bag_d;
$nt_wd_kgs_d += $n_wd_kgs_d;

$nt_wd_bag_green_tea += $n_wd_bag_green_tea;
$nt_wd_kgs_green_tea += $n_wd_kgs_green_tea;
$nt_wd_bag_orthodox += $n_wd_bag_orthodox;
$nt_wd_kgs_orthodox += $n_wd_kgs_orthodox;

$nt_out_bag_l += $n_out_bag_l;
$nt_out_kgs_l += $n_out_kgs_l;
$nt_out_bag_d += $n_out_bag_d;
$nt_out_kgs_d += $n_out_kgs_d;

$nt_out_bag_green_tea += $n_out_bag_green_tea;
$nt_out_kgs_green_tea += $n_out_kgs_green_tea;
$nt_out_bag_orthodox += $n_out_bag_orthodox;
$nt_out_kgs_dorthodox+= $n_out_kgs_orthodox;

$nt_leaf_offering += $leaf_offering;
$nt_dust_offering += $dust_offering;
$nt_green_tea_offering += $green_tea_offering;
} // 1st loop
?>
<tr>
	<td style="text-align:left; border-top:0px;"></td>
	<td style="text-align:left; border-bottom:1px solid; border-top:0px; font-weight: bold; color:red;">Net Total: </td>
	<td style="text-align:right; border-bottom:1px solid; border-top:0px; font-weight: bold; color:red;"><?php print $nt_leaf_offering + $nt_dust_offering + $nt_green_tea_offering; ?></td>
	<td style="text-align:right; border-bottom:1px solid; border-top:0px; font-weight: bold; color:red;"><?php print $nt_sold_bag_l + $nt_sold_bag_d + $nt_sold_bag_green_tea; ?></td>
	<td style="text-align:right; border-bottom:1px solid; border-top:0px; font-weight: bold; color:red;"><?php print number_format($nt_sold_kgs_l + $nt_sold_kgs_d + $nt_sold_kgs_green_tea,2,'.',''); ?></td>
	<td style="text-align:right; border-bottom:1px solid; border-top:0px; font-weight: bold; color:red;"><?php print number_format($nt_sold_tk_l + $nt_sold_tk_d + $nt_sold_tk_green_tea,2,'.',','); ?></td>
	<td style="text-align:right; border-bottom:1px solid; border-top:0px; font-weight: bold; color:red;">
		<?php 
			print (($nt_sold_tk_l + $nt_sold_tk_d + $nt_sold_tk_green_tea) > 0 && ($nt_sold_kgs_l + $nt_sold_kgs_d + $nt_sold_kgs_green_tea) > 0) ? number_format(($nt_sold_tk_l + $nt_sold_tk_d + $nt_sold_tk_green_tea) / ($nt_sold_kgs_l + $nt_sold_kgs_d + $nt_sold_kgs_green_tea),2,'.',',') : '0.00'; 
		?>
	</td>
</tr>
<?php 
$net_offering += $nt_leaf_offering + $nt_dust_offering + $nt_green_tea_offering;
$net_sold_bags += $nt_sold_bag_l + $nt_sold_bag_d + $nt_sold_bag_green_tea;
$net_sold_kgs += $nt_sold_kgs_l + $nt_sold_kgs_d + $nt_sold_kgs_green_tea;
$net_amount_tk += $nt_sold_tk_l + $nt_sold_tk_d + $nt_sold_tk_green_tea;

} ; ?>

<tr>
	<td colspan="7" height="15" style="border:0px;"></td>
</tr>

<tr>
	<td colspan="2" style="text-align:right; border:1px solid; font-weight: bold;">Grand Total :</td>
	<td style="text-align:right; border:1px solid; font-weight: bold;"><?php print $net_offering; ?></td>
	<td style="text-align:right; border:1px solid; font-weight: bold;"><?php print $net_sold_bags; ?></td>
	<td style="text-align:right; border:1px solid; font-weight: bold;"><?php print number_format($net_sold_kgs,2,'.',''); ?></td>
	<td style="text-align:right; border:1px solid; font-weight: bold;"><?php print number_format($net_amount_tk,2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid; font-weight: bold;">
	<?php 
		print ($net_amount_tk > 0 && $net_sold_kgs > 0) ? number_format($net_amount_tk / $net_sold_kgs,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>	
</tbody>
</table>
</div><!-- /div responsive -->

</div><!-- /col -->
</div><!-- /row -->

<div class="page-break"></div>

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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_from."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
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
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_from."' ");
$result_prompt_date = $query->row_array();

$current_date_time= date("Y-m-d h:i:sa");
?>

<table class="table" id="table" style="margin-bottom:5px;">
<thead>
<tr>
    <td colspan="3" style="text-align:center; border:0px; text-transform:uppercase;">
		<span style="border-bottom: 1px solid #33333373 !important;">
			Garden Wise Total Of Sale No <?php print $sale_from; ?> <?php print $auction_center; ?>
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

// Get Green Tea values for bags, kgs, and amount
$this->db->select("
	SUM(offering_chest) AS green_tea_total_bags,
	SUM(sold_bags) AS green_tea_sold_bags,
	SUM(gross_kgs) AS green_tea_gross_kgs,
	SUM(shortage_kgs) AS green_tea_shortage_kgs,
	SUM(net_kgs) AS green_tea_net_kgs,
	SUM(sold_kgs) AS green_tea_sold_kgs,
	SUM(sold_amount) AS green_tea_sold_amount
");
$this->db->from('sales_summary');
$this->db->where('catalogue_name', 'Green Tea');
$green_tea_query = $this->db->get();
$green_tea_data = $green_tea_query->row_array();

$green_tea_total_bags = $green_tea_data['green_tea_total_bags'] ? $green_tea_data['green_tea_total_bags'] : 0;
$green_tea_sold_bags = $green_tea_data['green_tea_sold_bags'] ? $green_tea_data['green_tea_sold_bags'] : 0;
$green_tea_unsold_bags = $green_tea_total_bags - $green_tea_sold_bags;

$green_tea_gross_kgs = $green_tea_data['green_tea_gross_kgs'] ? $green_tea_data['green_tea_gross_kgs'] : 0;
$green_tea_shortage_kgs = $green_tea_data['green_tea_shortage_kgs'] ? $green_tea_data['green_tea_shortage_kgs'] : 0;
$green_tea_net_kgs = $green_tea_data['green_tea_net_kgs'] ? $green_tea_data['green_tea_net_kgs'] : 0;
$green_tea_sold_kgs = $green_tea_data['green_tea_sold_kgs'] ? $green_tea_data['green_tea_sold_kgs'] : 0;
$green_tea_sold_amount = $green_tea_data['green_tea_sold_amount'] ? $green_tea_data['green_tea_sold_amount'] : 0;
$green_tea_unsold_kgs = $green_tea_net_kgs - $green_tea_sold_kgs;

// Calculate regular Dust (keeping original Dust values including Green Tea)
$regular_dust_total_bags = $result_date['offering_dust_chest'];
$regular_dust_sold_bags = $result_date['sold_dust_chest'];
$regular_dust_unsold_bags = ($result_date['offering_dust_chest'] - $result_date['sold_dust_chest']);

$regular_dust_gross_kgs = $result_date['gross_dust_kgs'];
$regular_dust_shortage_kgs = $result_date['shortage_dust_kgs'];
$regular_dust_net_kgs = $result_date['actual_dust_kgs'];
$regular_dust_sold_kgs = $result_date['sold_dust_kgs'];
$regular_dust_sold_amount = $result_date['sold_dust_amount'];
$regular_dust_unsold_kgs = $result_date['actual_dust_kgs'] - $result_date['sold_dust_kgs'];
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
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_total_bags > 0) {
			echo $regular_dust_total_bags . ' + ' . $green_tea_total_bags;
		} else {
			echo $result_date['offering_dust_chest'];
		}
		?>
	</td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['offering_total_chest']; ?></td>
</tr>
<tr>	
	<td style="text-align:right; text-transform:capitalize; border:0px;">Gross Kg. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['gross_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_gross_kgs > 0) {
			echo number_format($regular_dust_gross_kgs,2) . ' + ' . number_format($green_tea_gross_kgs,2);
		} else {
			echo number_format($result_date['gross_dust_kgs'],2);
		}
		?>
	</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['gross_kgs'],2); ?></td>
</tr>
<tr>	
	<td style="text-align:right; text-transform:capitalize; border:0px;">Shortage :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['shortage_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_shortage_kgs > 0) {
			echo number_format($regular_dust_shortage_kgs,2) . ' + ' . number_format($green_tea_shortage_kgs,2);
		} else {
			echo number_format($result_date['shortage_dust_kgs'],2);
		}
		?>
	</td>
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
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_net_kgs > 0) {
			echo number_format($net_dust_kgs,2) . ' + ' . number_format($green_tea_net_kgs,2);
		} else {
			echo number_format($net_dust_kgs,2);
		}
		?>
	</td>
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
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_net_kgs > 0) {
			echo number_format($regular_dust_net_kgs,2) . ' + ' . number_format($green_tea_net_kgs,2);
		} else {
			echo number_format($result_date['actual_dust_kgs'],2);
		}
		?>
	</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['actual_total_kgs'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Sold Bags :</td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['sold_leaf_chest']; ?></td>
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_sold_bags > 0) {
			echo $regular_dust_sold_bags . ' + ' . $green_tea_sold_bags;
		} else {
			echo $result_date['sold_dust_chest'];
		}
		?>
	</td>
	<td style="text-align:right; border:0px;"><?php echo $result_date['total_sold_chest']; ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Sold Kg. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_sold_kgs > 0) {
			echo number_format($regular_dust_sold_kgs,2) . ' + ' . number_format($green_tea_sold_kgs,2);
		} else {
			echo number_format($result_date['sold_dust_kgs'],2);
		}
		?>
	</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['total_sold_kgs'],2); ?></td>
</tr>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Amount Tk. :</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_leaf_amount'],2); ?></td>
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_sold_amount > 0) {
			echo number_format($regular_dust_sold_amount,2) . ' + ' . number_format($green_tea_sold_amount,2);
		} else {
			echo number_format($result_date['sold_dust_amount'],2);
		}
		?>
	</td>
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
$total_unsold_chest = $result_date['offering_total_chest'] - $result_date['total_sold_chest'];
?>
<tr>
	<td style="text-align:right; text-transform:capitalize; border:0px;">Unsold Bags :</td>
	<td style="text-align:right; border:0px;"><?php echo $unsold_leaf_chest; ?></td>
	<td style="text-align:right; border:0px;">
		<?php 
		if($green_tea_unsold_bags > 0) {
			echo $regular_dust_unsold_bags . ' + ' . $green_tea_unsold_bags;
		} else {
			echo ($result_date['offering_dust_chest'] - $result_date['sold_dust_chest']);
		}
		?>
	</td>
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
	<td style="text-align:right; border-top:0px; border-bottom:1px solid;">
		<?php 
		if($green_tea_unsold_kgs > 0) {
			echo number_format($regular_dust_unsold_kgs,2) . ' + ' . number_format($green_tea_unsold_kgs,2);
		} else {
			echo number_format($unsold_dust_kgs,2);
		}
		?>
	</td>
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
<?php } ?>


<?php if($report_no=='17'){ ?>
<div class="row">
    <div class="form-group col-sm-12 text-right">
        <a class="no-print" href="#" onClick="printDiv('printableArea')">
            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
        </a>
    </div>
    
    <div class="form-group col-sm-12 text-center">
        <b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
        <br /> 
        <span style="text-transform: uppercase; font-size:12px; text-align:center;"><?php print $owner_short_address; ?></span>
        <br />
        <b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
            GARDEN OFFERING & SALES REPORT
        </b>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4 text-left">
        <b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?></b>
    </div>
    <div class="form-group col-sm-4 text-center">
        <b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
    </div>
    <div class="form-group col-sm-4 text-right">
        <b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?> <br> Print : <?php print date('M-d-y', strtotime($current_date_time)) ; ?> <?php print date('h:i', strtotime($current_date_time)) ; ?></b>
    </div>
</div>

<style>
    #table tr td {
        font-size:12px !important;
        padding:3px;
    }
    #table tr th{
        font-size:13px !important;
        padding:3px;
    }

    @page {
        margin-bottom: 100px; /* Add a bottom gap of 100px */
    }

    @page {
		 size: A4 landscape;
        /* Top-right corner: Page X */
        @top-right {
            content: counter(page) " of " counter(pages);
            font-size: 12px;
        }
    }
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-sm" id="table">
                <thead>
                    <tr>
                        <th style="text-align:left; border:1px solid;">Garden Name</th>
                        <th colspan="2" style="text-align:center; border:1px solid;">Offering</th>
                        <th colspan="2" style="text-align:center; border:1px solid;">Sold</th>
                        <th colspan="2" style="text-align:center; border:1px solid;">Avg. price</th>
                        <th colspan="2" style="text-align:center; border:1px solid;">Unsold</th>
                        <th colspan="2" style="text-align:center; border:1px solid;">Withdraw</th>
                    </tr>
                    <tr>
                        <th style="text-align:center; border:1px solid;"></th>
                        <th style="text-align:center; border:1px solid;">Fresh</th>
                        <th style="text-align:center; border:1px solid;">Reprint</th>
                        <th style="text-align:center; border:1px solid;">Fresh</th>
                        <th style="text-align:center; border:1px solid;">Reprint</th>
                        <th style="text-align:center; border:1px solid;">Fresh</th>
                        <th style="text-align:center; border:1px solid;">Reprint</th>
                        <th style="text-align:center; border:1px solid;">Fresh</th>
                        <th style="text-align:center; border:1px solid;">Reprint</th>
                        <th style="text-align:center; border:1px solid;">Fresh</th>
                        <th style="text-align:center; border:1px solid;">Reprint</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // First, let's get all gardens with their NEW and RE-PRINT totals
                    $garden_totals = array();
                    
                    // Query to get garden totals with amount calculations including withdrawn items from invoice_entry
                    $this->db->select("
                        invoice_entry.garden_id,
                        garden_and_company_setup.garden_name,
                        invoice_entry.old_season,
                        SUM(CASE WHEN (invoice_entry.old_sale_no = 0 OR invoice_entry.old_sale_no IS NULL) AND invoice_entry.remarks != 'W/D' THEN invoice_entry.chest_number ELSE 0 END) as new_offered_bags,
                        SUM(CASE WHEN invoice_entry.old_sale_no > 0 AND invoice_entry.remarks != 'W/D' THEN invoice_entry.chest_number ELSE 0 END) as reprint_offered_bags,
                        SUM(CASE WHEN (invoice_entry.old_sale_no = 0 OR invoice_entry.old_sale_no IS NULL) AND sale_entry.sold_price > 0 THEN invoice_entry.chest_number ELSE 0 END) as new_sold_bags,
                        SUM(CASE WHEN invoice_entry.old_sale_no > 0 AND sale_entry.sold_price > 0 THEN invoice_entry.chest_number ELSE 0 END) as reprint_sold_bags,
                        SUM(CASE WHEN (invoice_entry.old_sale_no = 0 OR invoice_entry.old_sale_no IS NULL) AND sale_entry.sold_price > 0 THEN sale_entry.buyer1_amount + sale_entry.buyer2_amount + sale_entry.buyer3_amount ELSE 0 END) as new_total_amount,
                        SUM(CASE WHEN invoice_entry.old_sale_no > 0 AND sale_entry.sold_price > 0 THEN sale_entry.buyer1_amount + sale_entry.buyer2_amount + sale_entry.buyer3_amount ELSE 0 END) as reprint_total_amount,
                        SUM(CASE WHEN (invoice_entry.old_sale_no = 0 OR invoice_entry.old_sale_no IS NULL) AND sale_entry.sold_price > 0 THEN invoice_entry.net_kgs ELSE 0 END) as new_sold_kgs,
                        SUM(CASE WHEN invoice_entry.old_sale_no > 0 AND sale_entry.sold_price > 0 THEN invoice_entry.net_kgs ELSE 0 END) as reprint_sold_kgs,
                        SUM(CASE WHEN (invoice_entry.old_sale_no = 0 OR invoice_entry.old_sale_no IS NULL) AND invoice_entry.remarks = 'W/D' THEN invoice_entry.chest_number ELSE 0 END) as new_withdrawn_bags,
                        SUM(CASE WHEN invoice_entry.old_sale_no > 0 AND invoice_entry.remarks = 'W/D' THEN invoice_entry.chest_number ELSE 0 END) as reprint_withdrawn_bags
                    ");
                    $this->db->from('invoice_entry');
                    $this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
                    $this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
                    $this->db->where('invoice_entry.catalogue_status', 'prepared');
                    $this->db->where('invoice_entry.season', $season);
                    $this->db->where('invoice_entry.auction_center', $auction_center);
                    $this->db->where('invoice_entry.sale_no', $sale_from);
                    $this->db->where('invoice_entry.soft_delete', 1);
                    $this->db->group_by('invoice_entry.garden_id, invoice_entry.old_season');
                    $query = $this->db->get();
                    
                    if ($query->num_rows() > 0) {
                        foreach ($query->result_array() as $row) {
                            $garden_id = $row['garden_id'];
                            $old_season = $row['old_season'];
                            
                            if (!isset($garden_totals[$garden_id][$old_season])) {
                                $garden_totals[$garden_id][$old_season] = $row;
                            } else {
                                // Merge data if already exists
                                $garden_totals[$garden_id][$old_season]['new_offered_bags'] += $row['new_offered_bags'];
                                $garden_totals[$garden_id][$old_season]['reprint_offered_bags'] += $row['reprint_offered_bags'];
                                $garden_totals[$garden_id][$old_season]['new_sold_bags'] += $row['new_sold_bags'];
                                $garden_totals[$garden_id][$old_season]['reprint_sold_bags'] += $row['reprint_sold_bags'];
                                $garden_totals[$garden_id][$old_season]['new_total_amount'] += $row['new_total_amount'];
                                $garden_totals[$garden_id][$old_season]['reprint_total_amount'] += $row['reprint_total_amount'];
                                $garden_totals[$garden_id][$old_season]['new_sold_kgs'] += $row['new_sold_kgs'];
                                $garden_totals[$garden_id][$old_season]['reprint_sold_kgs'] += $row['reprint_sold_kgs'];
                                $garden_totals[$garden_id][$old_season]['new_withdrawn_bags'] += $row['new_withdrawn_bags'];
                                $garden_totals[$garden_id][$old_season]['reprint_withdrawn_bags'] += $row['reprint_withdrawn_bags'];
                            }
                        }
                    }
                    
                    // Query to get withdrawn items from backup_invoice_entry with same conditions
                    $this->db->select("
                        backup_invoice_entry.garden_id,
                        garden_and_company_setup.garden_name,
                        backup_invoice_entry.old_season,
                        0 as new_offered_bags,
                        0 as reprint_offered_bags,
                        0 as new_sold_bags,
                        0 as reprint_sold_bags,
                        0 as new_total_amount,
                        0 as reprint_total_amount,
                        0 as new_sold_kgs,
                        0 as reprint_sold_kgs,
                        SUM(CASE WHEN (backup_invoice_entry.old_sale_no = 0 OR backup_invoice_entry.old_sale_no IS NULL) AND backup_invoice_entry.remarks = 'W/D' THEN backup_invoice_entry.chest_number ELSE 0 END) as new_withdrawn_bags,
                        SUM(CASE WHEN backup_invoice_entry.old_sale_no > 0 AND backup_invoice_entry.remarks = 'W/D' THEN backup_invoice_entry.chest_number ELSE 0 END) as reprint_withdrawn_bags
                    ");
                    $this->db->from('backup_invoice_entry');
                    $this->db->join('garden_and_company_setup', 'backup_invoice_entry.garden_id = garden_and_company_setup.id', 'left');
                    $this->db->where('backup_invoice_entry.catalogue_status', 'prepared');
                    $this->db->where('backup_invoice_entry.season', $season);
                    $this->db->where('backup_invoice_entry.auction_center', $auction_center);
                    $this->db->where('backup_invoice_entry.sale_no', $sale_from);
                    $this->db->where('backup_invoice_entry.soft_delete', 1);
                    $this->db->group_by('backup_invoice_entry.garden_id, backup_invoice_entry.old_season');
                    $query_backup = $this->db->get();
                    
                    if ($query_backup->num_rows() > 0) {
                        foreach ($query_backup->result_array() as $row) {
                            $garden_id = $row['garden_id'];
                            $old_season = $row['old_season'];
                            
                            if (!isset($garden_totals[$garden_id][$old_season])) {
                                $garden_totals[$garden_id][$old_season] = $row;
                            } else {
                                // Add withdrawn bags from backup table
                                $garden_totals[$garden_id][$old_season]['new_withdrawn_bags'] += $row['new_withdrawn_bags'];
                                $garden_totals[$garden_id][$old_season]['reprint_withdrawn_bags'] += $row['reprint_withdrawn_bags'];
                            }
                        }
                    }
                    
                    // Now display the gardens by season
                    $this->db->select("invoice_entry.garden_id, invoice_entry.old_season, CASE WHEN invoice_entry.old_season='".$season."' THEN 'A' ELSE 'B' END AS serializing"); 
                    $this->db->from('invoice_entry');
                    $this->db->where('invoice_entry.catalogue_status', 'prepared');
                    $this->db->where('invoice_entry.season', $season);
                    $this->db->where('invoice_entry.auction_center', $auction_center);
                    $this->db->where('invoice_entry.sale_no', $sale_from);
                    $this->db->where('invoice_entry.soft_delete', 1);
                    $this->db->group_by('invoice_entry.old_season');
                    $this->db->order_by('serializing ASC');
                    $query = $this->db->get();
                    $fetch_list = $query->result_array();
                    
                    foreach($fetch_list as $fetch){ 
                    ?>
                    <tr>
                        <th colspan="11" class="text-danger" style="border:0px; font-size:13px !important; font-family:verdana;">
                            SEASON: <?php print $fetch['old_season']; ?>
                        </th>
                    </tr>

                    <?php
                    // Get gardens for this season
                    $this->db->select("invoice_entry.garden_id, invoice_entry.old_season, garden_and_company_setup.garden_name, CASE WHEN invoice_entry.old_season='".$season."' THEN 'A' ELSE 'B' END AS serializing"); 
                    $this->db->from('invoice_entry');
                    $this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
                    $this->db->where('invoice_entry.catalogue_status', 'prepared');
                    $this->db->where('invoice_entry.season', $season);
                    $this->db->where('invoice_entry.auction_center', $auction_center);
                    $this->db->where('invoice_entry.sale_no', $sale_from);
                    $this->db->where('invoice_entry.old_season', $fetch['old_season']);
                    $this->db->where('invoice_entry.soft_delete', 1);
                    $this->db->group_by('invoice_entry.garden_id, invoice_entry.old_season');
                    $this->db->order_by('serializing ASC, garden_and_company_setup.garden_name ASC');
                    $query = $this->db->get();
                    $result1 = $query->result_array();
                    
                    foreach($result1 as $row1){ 
                        $garden_id = $row1['garden_id'];
                        $old_season = $row1['old_season'];
                        
                        // Get the totals for this garden and season
                        $garden_data = isset($garden_totals[$garden_id][$old_season]) ? $garden_totals[$garden_id][$old_season] : array();
                        
                        // Calculate balance values
                        $new_balance = (isset($garden_data['new_offered_bags']) ? $garden_data['new_offered_bags'] : 0) - 
                                      (isset($garden_data['new_sold_bags']) ? $garden_data['new_sold_bags'] : 0);
                                      
                        $reprint_balance = (isset($garden_data['reprint_offered_bags']) ? $garden_data['reprint_offered_bags'] : 0) - 
                                          (isset($garden_data['reprint_sold_bags']) ? $garden_data['reprint_sold_bags'] : 0);
                    ?>
                    <tr>
                        <td style="text-align:left; border:1px solid;">
                            <a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season=' . $season . '&auction_center=' . $auction_center . '&sale_from=' . $sale_from . '&sale_to=' . $sale_to . '&garden_id=' . $garden_id . '&report=2'); ?>">
                                <b><?php echo $row1['garden_name']; ?></b>
                            </a>
                        </td>
                        
                        <!-- Offering - Fresh -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo isset($garden_data['new_offered_bags']) ? $garden_data['new_offered_bags'] : '0'; ?>
                        </td>
                        
                        <!-- Offering - Reprint -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo isset($garden_data['reprint_offered_bags']) ? $garden_data['reprint_offered_bags'] : '0'; ?>
                        </td>

                        <!-- Sold - Fresh -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo isset($garden_data['new_sold_bags']) ? $garden_data['new_sold_bags'] : '0'; ?>
                        </td>
                        
                        <!-- Sold - Reprint -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo isset($garden_data['reprint_sold_bags']) ? $garden_data['reprint_sold_bags'] : '0'; ?>
                        </td>

                        <!-- Avg. price - Fresh -->
                        <td style="text-align:right; border:1px solid;">
                            <?php 
                            if (isset($garden_data['new_total_amount']) && $garden_data['new_total_amount'] > 0 && 
                                isset($garden_data['new_sold_kgs']) && $garden_data['new_sold_kgs'] > 0) {
                                // Calculate average price per kg for fresh
                                echo number_format($garden_data['new_total_amount'] / $garden_data['new_sold_kgs'], 2);
                            } else {
                                echo '0.00';
                            }
                            ?>
                        </td>
                        
                        <!-- Avg. price - Reprint -->
                        <td style="text-align:right; border:1px solid;">
                            <?php 
                            if (isset($garden_data['reprint_total_amount']) && $garden_data['reprint_total_amount'] > 0 && 
                                isset($garden_data['reprint_sold_kgs']) && $garden_data['reprint_sold_kgs'] > 0) {
                                // Calculate average price per kg for reprint
                                echo number_format($garden_data['reprint_total_amount'] / $garden_data['reprint_sold_kgs'], 2);
                            } else {
                                echo '0.00';
                            }
                            ?>
                        </td>

						<!-- Balance - Fresh -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo $new_balance; ?>
                        </td>
                        
                        <!-- Balance - Reprint -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo $reprint_balance; ?>
                        </td>

                        <!-- Withdraw - Fresh -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo isset($garden_data['new_withdrawn_bags']) ? $garden_data['new_withdrawn_bags'] : '0'; ?>
                        </td>
                        
                        <!-- Withdraw - Reprint -->
                        <td style="text-align:right; border:1px solid;">
                            <?php echo isset($garden_data['reprint_withdrawn_bags']) ? $garden_data['reprint_withdrawn_bags'] : '0'; ?>
                        </td>
                        
                    </tr>
                    <?php 
                    } // End garden loop
                    ?>
                    
                    <?php 
                    } // End season loop
                    ?>
                </tbody>
            </table>
        </div><!-- /div responsive -->
    </div><!-- /col -->
</div><!-- /row -->
<?php } ?>


<?php if($report_no=='18'){ ?>
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
        size:A4;
        margin-left: 100px;
    }
</style>
<div class="row no-print">
    <div class="form-group col-sm-12 text-right">
        <a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id=&report=3'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> In Details</a>
        
        &nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
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

// Get distinct gardens
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();

foreach($result1 as $row1){

    // reset values per garden
    $n_net_kgs = 0;
    $n_chest_number = 0;
    $n_offering_bags = 0; // New variable to track non-reprint total
    $n_offering_kgs = 0;  // New variable to track non-reprint kgs

    $n_sold_amount = 0;
    $n_sold_bag_l = 0;
    $n_sold_kgs_l = 0;
    $n_sold_bag_d = 0;
    $n_sold_kgs_d = 0;
    $n_sold_tk_l = 0;
    $n_sold_tk_d = 0;

    $n_wd_bag_l = 0; $n_wd_kgs_l = 0; $n_wd_bag_d = 0; $n_wd_kgs_d = 0;
    $n_out_bag_l = 0; $n_out_kgs_l = 0; $n_out_bag_d = 0; $n_out_kgs_d = 0;

    // Detailed garden query
    $this->db->select("
        invoice_entry.lot_type,
        invoice_entry.sale_no, 
        invoice_entry.lot_no, 
        invoice_entry.w_inv_no, 
        CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
        invoice_entry.valuation, 
        IFNULL(sale_entry.sold_price,0) AS sold_price, 
        invoice_entry.net_kgs, 
        invoice_entry.chest_number, 
        invoice_entry.remarks, 
        (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
        
        (IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)) AS leaf_total_bag,
        (IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN net_kgs END),0)) AS leaf_net_kgs, 
        (IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)) AS dust_total_bag,
        (IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN net_kgs END),0)) AS dust_net_kgs,
        (IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)) AS leaf_sold_amount,
        (IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)) AS dust_sold_amount
    "); 
    $this->db->from('invoice_entry');
    $this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
    $this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
    $this->db->where('invoice_entry.catalogue_status', 'prepared');
    $this->db->where('invoice_entry.season',$season);
    $this->db->where('invoice_entry.auction_center',$auction_center);
    $this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
    $this->db->where('invoice_entry.garden_id',$row1['garden_id']);
    $this->db->where('invoice_entry.soft_delete', 1);
    $this->db->group_by('invoice_entry.id');
    $this->db->order_by('invoice_entry.lot_no ASC');
    $query = $this->db->get();

    // Loop through individual lots
    $garden_leaf_offering = 0; $garden_leaf_offering_kgs = 0;
    $garden_dust_offering = 0; $garden_dust_offering_kgs = 0;

    foreach($query->result_array() as $row3){
        
        // Calculate Sold Section (Counts everything including Reprints)
        if($row3['sold_price'] > 0){
            $n_sold_bag_l += $row3['leaf_total_bag']; $n_sold_kgs_l += $row3['leaf_net_kgs']; $n_sold_tk_l += $row3['leaf_sold_amount'];
            $n_sold_bag_d += $row3['dust_total_bag']; $n_sold_kgs_d += $row3['dust_net_kgs']; $n_sold_tk_d += $row3['dust_sold_amount'];
        }

        // Calculate Offering Section (EXCLUDE Reprints)
        if($row3['lot_type'] != 'Reprint'){
            $garden_leaf_offering += $row3['leaf_total_bag'];
            $garden_leaf_offering_kgs += $row3['leaf_net_kgs'];
            $garden_dust_offering += $row3['dust_total_bag'];
            $garden_dust_offering_kgs += $row3['dust_net_kgs'];
            
            // Sub-total variables for non-reprints only
            $n_chest_number += $row3['chest_number'];
            $n_net_kgs += $row3['net_kgs'];
        }
    }

    $n_leaf_offering += $garden_leaf_offering;
    $n_leaf_offering_kgs += $garden_leaf_offering_kgs;
    $n_dust_offering += $garden_dust_offering;
    $n_dust_offering_kgs += $garden_dust_offering_kgs;

    // Running totals for the final footer
    $nt_chest_number += $n_chest_number;
    $nt_net_kgs += $n_net_kgs;
    $nt_sold_bag_l += $n_sold_bag_l; $nt_sold_kgs_l += $n_sold_kgs_l; $nt_sold_tk_l += $n_sold_tk_l;
    $nt_sold_bag_d += $n_sold_bag_d; $nt_sold_kgs_d += $n_sold_kgs_d; $nt_sold_tk_d += $n_sold_tk_d;
    ?>
    <tr>
        <td style="text-align:left; border:0px; text-transform:uppercase;">
            <b><?php print $row1['garden_name']; ?></b>
        </td>
        <td style="text-align:right; border:0px;"><b>Leaf</b></td>
        <td style="text-align:right; color:black; border:0px;"><?php print $garden_leaf_offering; ?></td>
        <td style="text-align:right; color:black; border:0px; border-right:1px solid;"><?php print number_format($garden_leaf_offering_kgs,2,'.',''); ?></td>
        <td style="text-align:right; color:green; border:0px;"><?php print $n_sold_bag_l; ?></td>
        <td style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_kgs_l,2,'.',''); ?></td>
        <td style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_tk_l,2,'.',','); ?></td>
        <td style="text-align:right; color:green; border:0px;">
            <?php print ($n_sold_tk_l > 0 && $n_sold_kgs_l > 0) ? number_format($n_sold_tk_l / $n_sold_kgs_l,2,'.',',') : '0.00'; ?>
        </td>
        <td style="text-align:right; color:green; border:0px;">
            <?php print ($n_sold_bag_l > 0 && $garden_leaf_offering > 0) ? number_format(($n_sold_bag_l * 100)/$garden_leaf_offering,2,'.','') : '0.00'; ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align:right; border:0px;"><b>Dust</b></td>
        <td style="text-align:right; color:black; border:0px;"><?php print $garden_dust_offering; ?></td>
        <td style="text-align:right; color:black; border:0px; border-right:1px solid;"><?php print number_format($garden_dust_offering_kgs,2,'.',''); ?></td>
        <td style="text-align:right; border:0px; color:green;"><?php print $n_sold_bag_d; ?></td>
        <td style="text-align:right; border:0px; color:green;"><?php print number_format($n_sold_kgs_d,2,'.',''); ?></td>
        <td style="text-align:right; border:0px; color:green;"><?php print number_format($n_sold_tk_d,2,'.',','); ?></td>
        <td style="text-align:right; border:0px; color:green;">
            <?php print ($n_sold_tk_d > 0 && $n_sold_kgs_d > 0) ? number_format($n_sold_tk_d / $n_sold_kgs_d,2,'.',',') : '0.00'; ?>
        </td>
        <td style="text-align:right; border:0px; color:green;">
            <?php print ($n_sold_bag_d > 0 && $garden_dust_offering > 0) ? number_format(($n_sold_bag_d * 100) / $garden_dust_offering,2,'.','') : '0.00'; ?>
        </td>
    </tr>
    <tr style="border-top:1px solid #ccc;">
        <th colspan="2" style="text-align:right; border:0px;">Sub Total: </th>
        <th style="text-align:right; color:black; border:0px;"><?php print $n_chest_number; ?></th>
        <th style="text-align:right; color:black; border:0px; border-right:1px solid;"><?php print number_format($n_net_kgs,2,'.',''); ?></th>
        <th style="text-align:right; color:green; border:0px;"><?php print $n_sold_bag_l + $n_sold_bag_d; ?></th>
        <th style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_kgs_l + $n_sold_kgs_d,2,'.',','); ?></th>
        <th style="text-align:right; color:green; border:0px;"><?php print number_format($n_sold_tk_l + $n_sold_tk_d,2,'.',','); ?></th>
        <th style="text-align:right; color:green; border:0px;">
            <?php print (($n_sold_tk_l + $n_sold_tk_d) > 0 && ($n_sold_kgs_l + $n_sold_kgs_d) > 0) ? number_format(($n_sold_tk_l + $n_sold_tk_d) / ($n_sold_kgs_l + $n_sold_kgs_d),2,'.',',') : '0.00'; ?>
        </th>
        <th style="text-align:right; color:green; border:0px;">
            <?php print ($n_chest_number > 0) ? number_format((($n_sold_bag_l + $n_sold_bag_d) * 100)/$n_chest_number,2,'.','') : '0.00'; ?>
        </th>
    </tr>
<?php } ?>
</tbody>
<tfoot>
    <tr><td colspan="9" style="border:0px;">&nbsp;</td></tr>
    <tr>
        <th colspan="2" style="text-align:right;">LEAF TOTAL: </th>
        <th style="text-align:right;"><?php print $n_leaf_offering; ?></th>
        <th style="text-align:right;"><?php print number_format($n_leaf_offering_kgs,2,'.',''); ?></th>
        <th style="text-align:right; color:green;"><?php print $nt_sold_bag_l; ?></th>
        <th style="text-align:right; color:green;"><?php print number_format($nt_sold_kgs_l,2,'.',''); ?></th>
        <th style="text-align:right; color:green;"><?php print number_format($nt_sold_tk_l,2,'.',','); ?></th>
        <th style="text-align:right; color:green;"><?php print ($nt_sold_kgs_l > 0) ? number_format($nt_sold_tk_l / $nt_sold_kgs_l,2,'.',',') : '0.00'; ?></th>
        <th style="text-align:right; color:green;"><?php print ($n_leaf_offering > 0) ? number_format(($nt_sold_bag_l * 100)/$n_leaf_offering,2,'.','') : '0.00'; ?></th>
    </tr>
    <tr>
        <th colspan="2" style="text-align:right;">DUST TOTAL: </th>
        <th style="text-align:right;"><?php print $n_dust_offering; ?></th>
        <th style="text-align:right;"><?php print number_format($n_dust_offering_kgs,2,'.',''); ?></th>
        <th style="text-align:right; color:green;"><?php print $nt_sold_bag_d; ?></th>
        <th style="text-align:right; color:green;"><?php print number_format($nt_sold_kgs_d,2,'.',''); ?></th>
        <th style="text-align:right; color:green;"><?php print number_format($nt_sold_tk_d,2,'.',','); ?></th>
        <th style="text-align:right; color:green;"><?php print ($nt_sold_kgs_d > 0) ? number_format($nt_sold_tk_d / $nt_sold_kgs_d,2,'.',',') : '0.00'; ?></th>
        <th style="text-align:right; color:green;"><?php print ($n_dust_offering > 0) ? number_format(($nt_sold_bag_d * 100)/$n_dust_offering,2,'.','') : '0.00'; ?></th>
    </tr>
    <tr style="background:#f9f9f9;">
        <th colspan="2" style="text-align:right;">NET TOTAL: </th>
        <th style="text-align:right;"><?php print $nt_chest_number; ?></th>
        <th style="text-align:right;"><?php print number_format($nt_net_kgs,2,'.',''); ?></th>
        <th style="text-align:right; color:green;"><?php print $nt_sold_bag_l + $nt_sold_bag_d; ?></th>
        <th style="text-align:right; color:green;"><?php print number_format($nt_sold_kgs_l + $nt_sold_kgs_d,2,'.',''); ?></th>
        <th style="text-align:right; color:green;"><?php print number_format($nt_sold_tk_l + $nt_sold_tk_d,2,'.',','); ?></th>
        <th style="text-align:right; color:green;">
            <?php print (($nt_sold_kgs_l + $nt_sold_kgs_d) > 0) ? number_format(($nt_sold_tk_l + $nt_sold_tk_d) / ($nt_sold_kgs_l + $nt_sold_kgs_d),2,'.',',') : '0.00'; ?>
        </th>
        <th style="text-align:right; color:green;">
            <?php print ($nt_chest_number > 0) ? number_format((($nt_sold_bag_l + $nt_sold_bag_d) * 100)/$nt_chest_number,2,'.','') : '0.00'; ?>
        </th>
    </tr>
</tfoot>
</table>
</div>
</div>
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