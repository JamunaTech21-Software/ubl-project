<!DOCTYPE html>
<html lang="en">
<head>
<title>Garden Accounts</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$old_season = (!empty($this->input->get('old_season', TRUE))) ? $this->input->get('old_season', TRUE) : '';
$season_name = (!empty($this->input->get('season_name', TRUE))) ? $this->input->get('season_name', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$company_id = (!empty($this->input->get('company_id', TRUE))) ? $this->input->get('company_id', TRUE) : '';
$combine_center = (!empty($this->input->get('combine_center', TRUE))) ? $this->input->get('combine_center', TRUE) : '';
$report_no = (!empty($this->input->get('report', TRUE))) ? $this->input->get('report', TRUE) : '';

// Report 5 value
$datetime =  date("Y-m-d").'T'.date("H:i");
$last_update = $ubl_user_name .', Date: '. $today .', IP: '. $ip;

## garden details
$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
$garden_data = $query->row_array();

## company details
$query = $this->db->query("SELECT * FROM company_setup WHERE id='".$company_id."' ");
$company_data = $query->row_array(); 

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

<?php if($report_no=='1'){ ?>
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
#deduction_div{
	margin-right: 165px;
	float: right;
}

@media print{    
	#deduction_div{
		margin-right: 95px;
		width: 60% !important;
		float: right;
	}
}

@page{
	size:A4 portrait;
	margin-left: 70px;
	margin-right: 100px;
}
</style>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		
		<a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sale_account_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$garden_id.'&old_season='.$season.'&report=6&season_name=Current Season'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Current Season</a>
		
		<?php
		$current_season = explode("-",$season);
		
		$old_season_sbt = ($current_season[0] - 1);
		$old_season_sbt2 = ($current_season[1] - 1);
		$old_season = "$old_season_sbt-$old_season_sbt2";
		
		$old_season_ext = ($current_season[0] + 1);
		$old_season_ext2 = ($current_season[1] + 1);
		$new_season = "$old_season_ext-$old_season_ext2";
		?>
		
		&nbsp; <a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sale_account_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$garden_id.'&old_season='.$old_season.'&report=6&season_name=Old Season'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Old Season</a>
		
		&nbsp; <a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/garden_sale_account_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$garden_id.'&old_season='.$new_season.'&report=6&season_name=New Season'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Season</a>
		
		&nbsp; <a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
		
	</div>
</div>

<br />

<?php
// get html value
$row_limit_pp = 45;

$sl = 1;
$total_kgs = 0;
$total_pkgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$adjustment_total = 0;
$tea_cess = 0;
$warehouse_charge_total = 0;

// query
$this->db->select("

	invoice_entry.lot_no, 
	invoice_entry.old_season, 
	invoice_entry.w_inv_no, 
	invoice_entry.chest_number, 
	invoice_entry.warehouse_charge, 
	invoice_entry.insp_sample, 
	invoice_entry.brokerage_commission, 
	invoice_entry.tea_cess, 
	invoice_entry.valuation,
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
	@total_pkgs:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_pkgs,
	@total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,	
	sale_entry.sold_price,
	sale_entry.complete_price,
	@amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS amount,
	@compelte_amount:=(IFNULL(sale_entry.buyer1_complete_amount,0) + IFNULL(sale_entry.buyer2_complete_amount,0) + IFNULL(sale_entry.buyer3_complete_amount,0)) AS compelte_amount 		
"); 
$this->db->from('invoice_entry'); 
$this->db->join('sale_entry','invoice_entry.id = sale_entry.invoice_id');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
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
foreach($query3 as $row2){

$total_bill = $row2['amount'] + ($row2['amount'] * 16 / 100);
$net_total_bill = ($total_bill + $row2['total_kgs'] * 10 / 100) - $row2['compelte_amount'];
$adjustment_bill = ($row2['compelte_amount'] > 0) ? $net_total_bill : '0';

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
		<th colspan="8" style="text-align:left; border:0px;">
			<span style="text-transform: uppercase; font-size:20px; text-align:center; font-weight:bold; font-family:courier;">
				<?php print $website_title; ?>
			</span>
		</th>
		<td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
	</tr>
	<tr>
		<td colspan="4" class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> Sale No. <?php print $sale_no; ?> of <?php print $auction_center; ?> <?php print $season; ?></td>
		
		<td colspan="5" class="text-right" style="font-family: verdana; border:0px; font-size:12px;"><span style="padding-right:30px;">ACCOUNT SALES</span> No.<span style="border-bottom:1px solid #000; display: inline-block; width: 100px;"></span> &nbsp;&nbsp; Date : <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
	</tr>
	<tr>
		<th colspan="9" class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> Garden : <?php print $garden_data['garden_name']; ?></th>
	</tr>
	<tr>
		<td style="text-align:center; border:1px solid;">Lot No</td>
		<td style="text-align:center; border:1px solid;">Invoice</td>
		<td style="text-align:left; border:1px solid;">Grade</td>
		<td style="text-align:right; border:1px solid;">Pkgs</td>
		<td style="text-align:right; border:1px solid;">Total Kg.</td>
		<td style="text-align:right; border:1px solid;">Value</td>
		<td style="text-align:right; border:1px solid;">Price</td>
		<td style="text-align:right; border:1px solid;">Amount</td>
		<td class="no-print" style="text-align:right; border:1px solid;">Adjustment</td>
		<td style="text-align:center; border:1px solid;">O/S</td>
	</tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:center; border:none; text-transform:uppercase;">
		<?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print $row2['total_pkgs']; ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['valuation'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['amount'],2,'.',','); ?>
	</td>
	<td class="no-print" style="text-align:right; border:none;">
		<?php 
			print number_format($adjustment_bill,2,'.',','); 
		?>
	</td>
	<td style="text-align:center; border:none;">
		<?php print $row2['old_season']; ?>
	</td>
</tr>

<?php

$total_pkgs += $row2['total_pkgs'];
$total_kgs += $row2['total_kgs'];
$total_amount += $row2['amount'];
$adjustment_total += $adjustment_bill;
$warehouse_charge = $row2['warehouse_charge'];
$insp_sample = $row2['insp_sample'];
$brokerage_commission = $row2['brokerage_commission'];
$tea_cess = $row2['tea_cess'];
$invoice_warehouse_charge = $row2['total_pkgs'] * $row2['warehouse_charge'];
$warehouse_charge_total += $invoice_warehouse_charge;
// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<td style="text-align:right;" colspan="3"> Grand Total: </td>
	<td style="text-align:right;">
		<?php echo $total_pkgs; ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($total_kgs,2,'.',''); ?>
	</td>
	<td style="text-align:right;"></td>
	<td style="text-align:right;"></td>
	<td style="text-align:right;">
		<?php echo number_format($total_amount,2,'.',','); ?>
	</td>
	<td class="no-print" style="text-align:right;">
		<?php echo number_format($adjustment_total,2,'.',','); ?>
	</td>
	<td style="text-align:right;"></td>
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
<div class="col-sm-6" id="deduction_div">
<table class="table table-sm" id="table">
<tr>
	<td colspan="4" style="text-align:left; border:none;"><b> Deduction : </td>
</tr>
<tr>
	<td style="text-align:right; border:none;"> 
		Tea Cess 1% 
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;">
	<?php 
		$total_tea_cess = number_format((($total_amount * $tea_cess)/100),2,'.','');
		print number_format($total_tea_cess,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:none;"></td>
</tr>
<tr>
	<td style="text-align:right; border:none;">
		Were House Charges
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;">
	<?php 
		
		print number_format($warehouse_charge_total,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:none;"></td>
</tr>
<tr>
	<td style="text-align:right; border:none;">
		Inspection Fee
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;">
	<?php 
		$total_insp_sample = number_format(($total_pkgs * $insp_sample),2,'.','');
		print number_format($total_insp_sample,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:none;"></td>
</tr>
<tr>
	<td style="text-align:right; border:none;">
		Brokerage
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;">
	<?php 
		$total_brokerage = number_format((($total_amount * $brokerage_commission)/100),2,'.','');
		print number_format($total_brokerage,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:none;"></td>
</tr>

<tr>
	<td style="text-align:right; border:none;">
		Sales Adjustment
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;">
	<?php 
		print number_format($adjustment_total,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:none;"></td>
</tr>
<?php
$garden_adjust = 0;	
// garden adjust cost
$this->db->select("charges_name, charge_tk, particular"); 
$this->db->from('garden_adjust_tbl');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$this->db->where('garden_id', $garden_id);
$this->db->order_by('id ASC');
$query = $this->db->get();
foreach($query->result_array() as $cost_row){	
?>
<tr>
	<td style="text-align:right; border:none;">
		<?php print $cost_row['charges_name']; ?>
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;">
	<?php 
		print number_format($cost_row['charge_tk'],2,'.',',');
	?>
	</td>
	<td style="text-align:right; border:none;"></td>
</tr>
<?php 
	$garden_adjust += $cost_row['charge_tk'];
} // foreach
?>
	
<tr style="border-bottom:1px dashed #000;">
	<td style="text-align:right; border:none;"> 
		Total Charges Tk.
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;"></td>
	<td style="text-align:right; border:none;">
	<?php 
		$total_charges = $warehouse_charge_total + $total_insp_sample + $total_brokerage + $total_tea_cess + $garden_adjust + $adjustment_total;
		print number_format($total_charges,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:none;"></td>
</tr>
<tr>
	<td style="text-align:right; border:none;"> 
		Net. Sales Tk.
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<td style="text-align:right; border:none;"></td>
	<td style="text-align:right; border:none;">
	<?php 
		$net_proceed_tk = $total_amount - $total_charges;
		print number_format($net_proceed_tk,2,'.',','); 
	?>
	</td>
</tr>
</table>
</div>
</div> 
<!-- /row -->

<div class="row">
	<div class="col-sm-12">
		<p class="text-left" style="font-family: verdana; border:0px; font-weight:normal; font-size:12px;"> 
			Taka : <?php print convert_number_to_words($net_proceed_tk); ?>
		</p>
	</div>
</div>
<!-- /row -->

<br />
<br />
<br />

<div class="row">
	<div class="col-sm-12 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px; float:left;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> &nbsp; &nbsp; &nbsp; </b>
		</p>
	</div>
</div>
<!-- /row -->

<?php }else if($report_no=='2'){ ?>	
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
	size:A4 portrait;

	@bottom-right {
        content: counter(page) " of " counter(pages);
        font-style: italic;
    }
}
</style>
<div class="row">
	<div class="form-group col-sm-11 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><u><?php print $owner_short_address; ?></u></span>
		<br />
		<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			Price Realised
		</b>
	</div>
	<div class="form-group col-sm-1 text-right no-print">
		<a href="javascript:void(0);" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="col-sm-4">
		<table style="text-transform:uppercase;">
			<tr>
				<th class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE NO </th>
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<th class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $sale_no; ?></th>
			</tr>
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE DATE </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
			</tr>
		</table>
	</div>	
	<div class="col-sm-4">
		<!-- <table align="center" style="text-transform:uppercase;">
			<tr>
				<td style="font-family: verdana; border:0px; font-size:12px;"><?php print $auction_center; ?></td>
			</tr>
		</table> -->
	</div>	
	<div class="col-sm-4">
		<table style="float:right; text-transform:uppercase;">
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SEASON </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $season; ?></td>
			</tr>
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> PROMPT DATE </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<b style="font-family: verdana; border:0px; font-size:12px;"> GARDEN NAME : &nbsp; <?php print $garden_data['garden_name']; ?></b>
	</div>
</div>	

<div class="row">
<div class="col-sm-12">

<?php
// DATE VALIDATION - Check if sale date is in future
$sale_date = $sale_data['sale_date']; 
$today = date('Y-m-d');

if (strtotime($sale_date) > strtotime($today)) {
    // Show "No Data Found" message
    ?>
    <table class="table table-sm" id="table">
        <thead>
            <tr>
                <th style="text-align:center; vertical-align: middle; border:1px solid;">Lot</th>
                <th style="text-align:center; vertical-align: middle; border:1px solid;">Inv</th>
                <th style="text-align:left; vertical-align: middle; border:1px solid;">Grade</th>
                <th style="text-align:right; vertical-align: middle; border:1px solid;">Bags</th>
                <th style="text-align:right; vertical-align: middle; border:1px solid;">Value</th>
                <th style="text-align:right; vertical-align: middle; border:1px solid;">Price</th>
                <th style="text-align:right; vertical-align: middle; border:1px solid;">Total Kgs</th>
                <th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-1</th>
                <th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-2</th>
                <th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-3</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="10" style="text-align:center; padding: 30px 0; border:1px solid;">
                    <h3 style="color: #ff0000; margin: 0;">No Data Found</h3>
                    <!--<p style="font-size: 14px; margin: 10px 0 0 0; color: #666;">
                        Sale date (<?php echo date("d-m-Y", strtotime($sale_date)); ?>) is in the future.
                    </p>
                    <p style="font-size: 12px; margin: 5px 0 0 0; color: #999;">
                        Data will be available on or after <?php echo date("d-m-Y", strtotime($sale_date)); ?>
                    </p>-->
                </td>
            </tr>
        </tbody>
    </table>
    <?php
} else {
    // Your existing code continues here - UNCHANGED
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:center; vertical-align: middle; border:1px solid;">Lot</th>
	<th style="text-align:center; vertical-align: middle; border:1px solid;">Inv</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">Grade</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Bags</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Value</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Price</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Total Kgs</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-1</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-2</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-3</th>
</tr>
</thead>
<tbody>
<?php
$sl = 1;
$chest_number = 0;
$net_kgs = 0;
$internal_bags = 0;
$internal_kgs = 0;
$internal_tk = 0;
$export_bags = 0;
$export_kgs = 0;
$export_tk = 0;

// query
$this->db->select("

	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.chest_number, 
	invoice_entry.valuation, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
	IFNULL(sale_entry.sold_price,'UNSOLD') AS sold_price,
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	
	/* ++++ Internal +++ */
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_bag,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_bag,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_bag,0), '0')) AS internal_bags,
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_total_kgs,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_total_kgs,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_total_kgs,0), '0')) AS internal_kgs,
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_amount,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_amount,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_amount,0), '0')) AS internal_tk,
	
	/* ++++ Export +++ */
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_bag,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_bag,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_bag,0), '0')) AS export_bags,
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_total_kgs,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_total_kgs,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_total_kgs,0), '0')) AS export_kgs,
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_amount,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_amount,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_amount,0), '0')) AS export_tk
"); 

$this->db->from('invoice_entry'); 
$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id", 'left');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<td style="text-align:center; vertical-align:middle; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:center; vertical-align:middle; border:none; text-transform:uppercase;">
		<?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; vertical-align:middle; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print $row2['chest_number']; ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print number_format($row2['valuation'],2,'.',''); ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print ($row2['sold_price']=='UNSOLD') ? "UNSOLD" : number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print number_format($row2['net_kgs'],2,'.',','); ?>
	</td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer1']; ?></td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer2']; ?></td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer3']; ?></td>
</tr>	

<?php
	$chest_number += $row2['chest_number'];
	$net_kgs += $row2['net_kgs'];
	$internal_bags += $row2['internal_bags'];
	$internal_kgs += $row2['internal_kgs'];
	$internal_tk += $row2['internal_tk'];
	$export_bags += $row2['export_bags'];
	$export_kgs += $row2['export_kgs'];
	$export_tk += $row2['export_tk'];
} // second loop	
?>

<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">TOTAL: </th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
</tr>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">INTERNAL:</th>
	<th style="text-align:right;"><?php echo $internal_bags; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($internal_kgs,2,'.',''); ?></th>
	<th style="text-align:center;" colspan="3">Avg. Price: <?php echo ($internal_tk > 0 && $internal_kgs > 0) ? number_format($internal_tk / $internal_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;">EXPORTER:</th>
	<th style="text-align:right; border:none;"><?php echo $export_bags; ?></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"><?php echo number_format($export_kgs,2,'.',''); ?></th>
	<th style="text-align:center; border:none;" colspan="3">Avg. Price: <?php echo ($export_tk > 0 && $export_kgs > 0) ? number_format($export_tk / $export_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">NET TOTAL SOLD:</th>
	<th style="text-align:right;"><?php echo $internal_bags + $export_bags; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($internal_kgs + $export_kgs,2,'.',''); ?></th>
	<th style="text-align:center;" colspan="3">Avg. Price: <?php echo (($internal_tk + $export_tk) > 0 && ($internal_kgs + $export_kgs) > 0) ? number_format(($internal_tk + $export_tk) / ($internal_kgs + $export_kgs),2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none; color:red;">UNSOLD:</th>
	<th style="text-align:right; border:none; color:red;"><?php echo $chest_number - ($internal_bags + $export_bags); ?></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none; color:red;"><?php echo number_format($net_kgs - ($internal_kgs + $export_kgs),2,'.',''); ?></th>
	<th style="text-align:center; border:none;" colspan="3"></th>
</tr>
</tbody>
</table>

<?php
} // End of else condition (date validation)
?>

</div>
</div>

<?php }else if($report_no=='3'){ ?>	
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
	size:A4 portrait;

	@bottom-right {
       	content: counter(page) " of " counter(pages);
        font-style: italic;
    }
}
</style>

<?php
// DATE VALIDATION - Check if sale date is in future
$sale_date = $sale_data['sale_date']; 
$today = date('Y-m-d');

if (strtotime($sale_date) > strtotime($today)) {
    // Show "No Data Found" message
    ?>
    <div class="row">
        <div class="form-group col-sm-12 text-right no-print">
            <a href="javascript:void(0);" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
            &nbsp; <a target="_blank" style="text-decoration:none;" href="<?php echo site_url('Price_realised_export_all_report/price_realised_export_all'); ?>?season=<?php echo urlencode($season); ?>&auction_center=<?php echo urlencode($auction_center); ?>&sale_no=<?php echo urlencode($sale_no); ?>&company_id=<?php echo urlencode($company_id); ?>">
            <span class="badge badge-danger" style="cursor:pointer;">
                <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Price Realised All to Excel
            </span>
            </a>
        </div>
        <div class="form-group col-sm-12 text-center">
            <b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
            <br /> 
            <span style="text-transform: uppercase; font-size:12px; text-align:center;"><u><?php print $owner_short_address; ?></u></span>
            <br />
            <b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
                Price Realised
            </b>
        </div>
    </div>

    <div class="row">	
        <div class="col-sm-4">
            <table style="text-transform:uppercase;">
                <tr>
                    <th class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE NO </th>
                    <td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
                    <th class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $sale_no; ?></th>
                </tr>
                <tr>
                    <td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE DATE </td> 
                    <td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
                    <td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
                </tr>
            </table>
        </div>	
        <div class="col-sm-4">
            <!-- <table align="center" style="text-transform:uppercase;">
                <tr>
                    <td style="font-family: verdana; border:0px; font-size:12px;"><?php print $auction_center; ?></td>
                </tr>
            </table> -->
        </div>	
        <div class="col-sm-4">
            <table style="float:right; text-transform:uppercase;">
                <tr>
                    <td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SEASON </td> 
                    <td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td> 
                    <td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $season; ?></td>
                </tr>
                <tr>
                    <td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> PROMPT DATE </td> 
                    <td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
                    <td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 text-center" style="padding: 50px 0;">
            <h3 style="color: #ff0000;">No Data Found</h3>
           <!--- <p style="font-size: 16px;">Sale date (<?php echo date("d-m-Y", strtotime($sale_date)); ?>) is in the future.</p>
            <p style="font-size: 14px; color: #666;">Data will be available on or after <?php echo date("d-m-Y", strtotime($sale_date)); ?></p>-->
        </div>
    </div>
    <?php
} else {
    // Your existing code continues here - UNCHANGED
?>

<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a href="javascript:void(0);" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>

		 &nbsp; <a target="_blank" style="text-decoration:none;" href="<?php echo site_url('Price_realised_export_all_report/price_realised_export_all'); ?>?season=<?php echo urlencode($season); ?>&auction_center=<?php echo urlencode($auction_center); ?>&sale_no=<?php echo urlencode($sale_no); ?>&company_id=<?php echo urlencode($company_id); ?>">
		<span class="badge badge-danger" style="cursor:pointer;">
			<span class="glyphicon glyphicon-download" aria-hidden="true"></span> Price Realised All to Excel
		</span>
		</a>

	</div>
	<div class="form-group col-sm-12 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><u><?php print $owner_short_address; ?></u></span>
		<br />
		<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			Price Realised
		</b>
	</div>
</div>

<div class="row">	
	<div class="col-sm-4">
		<table style="text-transform:uppercase;">
			<tr>
				<th class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE NO </th>
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<th class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $sale_no; ?></th>
			</tr>
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE DATE </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
			</tr>
		</table>
	</div>	
	<div class="col-sm-4">
		<!-- <table align="center" style="text-transform:uppercase;">
			<tr>
				<td style="font-family: verdana; border:0px; font-size:12px;"><?php print $auction_center; ?></td>
			</tr>
		</table> -->
	</div>	
	<div class="col-sm-4">
		<table style="float:right; text-transform:uppercase;">
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SEASON </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $season; ?></td>
			</tr>
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> PROMPT DATE </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
			</tr>
		</table>
	</div>
</div>
<div class="row">
<div class="col-sm-12">
<?php
//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
if(!empty($company_id)){
$this->db->where('garden_and_company_setup.company_name', $company_id);
}
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 AS $row1){
?>
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="10" style="text-align:left; vertical-align: middle; border:0px solid; text-transform:uppercase;">GARDEN NAME : &nbsp; <?php print $row1['garden_name']; ?></th>
</tr>
<tr>
	<th style="text-align:center; vertical-align: middle; border:1px solid;">Lot</th>
	<th style="text-align:center; vertical-align: middle; border:1px solid;">Inv</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">Grade</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Bags</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Value</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Price</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Total Kgs</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-1</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-2</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-3</th>
</tr>
</thead>
<tbody>
<?php
$sl = 1;
$chest_number = 0;
$net_kgs = 0;
$internal_bags = 0;
$internal_kgs = 0;
$internal_tk = 0;
$export_bags = 0;
$export_kgs = 0;
$export_tk = 0;

// query
$this->db->select("

	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.chest_number, 
	invoice_entry.valuation, 
	invoice_entry.net_kgs, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
	IFNULL(sale_entry.sold_price,'UNSOLD') AS sold_price,
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_bag,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_bag,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_bag,0), '0')) AS internal_bags,
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_total_kgs,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_total_kgs,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_total_kgs,0), '0')) AS internal_kgs,
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_amount,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_amount,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_amount,0), '0')) AS internal_tk,
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_bag,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_bag,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_bag,0), '0')) AS export_bags,
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_total_kgs,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_total_kgs,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_total_kgs,0), '0')) AS export_kgs,
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_amount,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_amount,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_amount,0), '0')) AS export_tk
"); 
$this->db->from('invoice_entry'); 
$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id", 'left');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>
<tr>
	<td style="text-align:center; vertical-align:middle; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:center; vertical-align:middle; border:none; text-transform:uppercase;">
		<?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; vertical-align:middle; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print $row2['chest_number']; ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print number_format($row2['valuation'],2,'.',''); ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print ($row2['sold_price']=='UNSOLD') ? "UNSOLD" : number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print number_format($row2['net_kgs'],2,'.',','); ?>
	</td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer1']; ?></td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer2']; ?></td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer3']; ?></td>
</tr>
<?php
	$chest_number += $row2['chest_number'];
	$net_kgs += $row2['net_kgs'];
	$internal_bags += $row2['internal_bags'];
	$internal_kgs += $row2['internal_kgs'];
	$internal_tk += $row2['internal_tk'];
	$export_bags += $row2['export_bags'];
	$export_kgs += $row2['export_kgs'];
	$export_tk += $row2['export_tk'];
} // second loop	
?>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">TOTAL: </th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
</tr>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">INTERNAL:</th>
	<th style="text-align:right;"><?php echo $internal_bags; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($internal_kgs,2,'.',''); ?></th>
	<th style="text-align:center;" colspan="3">Avg. Price: <?php echo ($internal_tk > 0 && $internal_kgs > 0) ? number_format($internal_tk / $internal_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;">EXPORTER:</th>
	<th style="text-align:right; border:none;"><?php echo $export_bags; ?></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"><?php echo number_format($export_kgs,2,'.',''); ?></th>
	<th style="text-align:center; border:none;" colspan="3">Avg. Price: <?php echo ($export_tk > 0 && $export_kgs > 0) ? number_format($export_tk / $export_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">NET TOTAL SOLD:</th>
	<th style="text-align:right;"><?php echo $internal_bags + $export_bags; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($internal_kgs + $export_kgs,2,'.',''); ?></th>
	<th style="text-align:center;" colspan="3">Avg. Price: <?php echo (($internal_tk + $export_tk) > 0 && ($internal_kgs + $export_kgs) > 0) ? number_format(($internal_tk + $export_tk) / ($internal_kgs + $export_kgs),2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none; color:red;">UNSOLD:</th>
	<th style="text-align:right; border:none; color:red;"><?php echo $chest_number - ($internal_bags + $export_bags); ?></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none; color:red;"><?php echo number_format($net_kgs - ($internal_kgs + $export_kgs),2,'.',''); ?></th>
	<th style="text-align:center; border:none;" colspan="3"></th>
</tr>
</tbody>
</table>
<?php } // 1st foreach ?>

</div>
</div>
<!-- /row -->

<?php
} // End of else condition (date validation)
?>

<?php }else if($report_no=='4'){ ?>	

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="javascript:void(0);" onclick="fn_company_wise_avg('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $combine_center; ?>');"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Company Wise Avg.</a>
		
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><u><?php print $owner_short_address; ?></u></span>
		<br /><br />
		<b style="text-transform: uppercase; font-size:16px; font-weight:bold; text-align:center;">
			Garden Wise average statement
		</b>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-4">
		<table align="left">
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Sale No</th> 
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Sale Date</th>
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></th>
			</tr>
		</table>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:14px;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4">
		<table align="right">
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Season</th> 
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print $season; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Print Date</th>
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print date("d-m-Y"); ?></th>
			</tr>
		</table>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table" style="text-transform: uppercase;">
<thead>
<tr>
	<th style="text-align:left; border:0px;"></th>
	<th colspan="3" style="text-align:center; border:0px; font-size:14px !important;">CURRENT SALE</th>
	<th colspan="2" style="text-align:center; border:0px; font-size:14px !important;">UPTO DATE SALE</th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">Garden Name</th>
	
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">%</th>
	
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important; display:none;">%</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_avg_report`");

// create garden_sales_avg_report
$this->db->query("CREATE TABLE `garden_sales_avg_report` (SELECT 

	invoice_entry.id, 
	invoice_entry.sale_no, 
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount, 
	garden_and_company_setup.garden_name,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_bag END,0) 
	) AS internal_bags,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_bag END,0) 
	) AS export_bags,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS internal_kgs,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS export_kgs,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_amount END,0) 
	) AS internal_amount,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_amount END,0) 
	) AS export_amount

FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no <= '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id
) ");
// end of create temp table


// reset data
$current_total_sold2 = 0;
$current_net_kgs2 = 0;
$current_total_amount2 = 0;
$current_offering_bag2 = 0;
$upto_total_sold2 = 0;
$upto_net_kgs2 = 0;
$upto_total_amount2 = 0;
$upto_offering_bag2 = 0;
$internal_bags2 = 0;
$export_bags2 = 0;
$internal_kgs2 = 0;
$export_kgs2 = 0;
$internal_amount2 = 0;
$export_amount2 = 0;

// old_season breakdown query
$this->db->select("old_season"); 
$this->db->from('garden_sales_avg_report');
$this->db->group_by('old_season');
$this->db->order_by('id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>
<tr>
	<th colspan="7" class="text-danger" style="border:0px; font-size:14px !important;">SEASON: <?php print $row2['old_season']; ?></th>
</tr>
<?php
$current_total_sold = 0;
$current_net_kgs = 0;
$current_total_amount = 0;
$current_offering_bag = 0;
$upto_total_sold = 0;
$upto_net_kgs = 0;
$upto_total_amount = 0;
$upto_offering_bag = 0;
$internal_bags = 0;
$export_bags = 0;
$internal_kgs = 0;
$export_kgs = 0;
$internal_amount = 0;
$export_amount = 0;

//query
$this->db->select("
	gs.garden_id, 
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,
	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.export_bags) AS export_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_amount) AS export_amount,
	gs.garden_name, 
	inv_offering.current_offering_bag,
	inv_offering.upto_offering_bag
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(chest_number) AS upto_offering_bag FROM invoice_entry WHERE season='".$season."' AND auction_center='".$auction_center."' AND sale_no <= '".$sale_no."' AND old_season='".$row2['old_season']."' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY garden_id) inv_offering","gs.garden_id=inv_offering.garden_id","left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-size:14px !important;">
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id']); ?>"><?php print $row1['garden_name']; ?></a>
	</td>
	
	<td style="text-align:right; border:0px; font-size:14px !important;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? number_format($row1['current_total_amount'] / $row1['current_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['current_total_sold'] > 0 && $row1['current_offering_bag'] > 0) ? number_format(($row1['current_total_sold'] * 100)/$row1['current_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
	
	<td style="text-align:right; border:0px; font-size:14px !important;">
		<?php print ($row1['upto_net_kgs'] > 0) ? number_format($row1['upto_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['upto_total_amount'] > 0 && $row1['upto_net_kgs'] > 0) ? number_format($row1['upto_total_amount'] / $row1['upto_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important; display:none;">
	<?php 
		print ($row1['upto_total_sold'] > 0 && $row1['upto_offering_bag'] > 0) ? number_format(($row1['upto_total_sold'] * 100)/$row1['upto_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
</tr>
<?php
	$current_total_sold +=$row1['current_total_sold'];
	$current_net_kgs +=$row1['current_net_kgs'];
	$current_total_amount +=$row1['current_total_amount'];
	$current_offering_bag +=$row1['current_offering_bag'];
	$upto_total_sold +=$row1['upto_total_sold'];
	$upto_net_kgs +=$row1['upto_net_kgs'];
	$upto_total_amount +=$row1['upto_total_amount'];
	$upto_offering_bag +=$row1['upto_offering_bag'];
	$internal_bags +=$row1['internal_bags'];
	$export_bags +=$row1['export_bags'];
	$internal_kgs +=$row1['internal_kgs'];
	$export_kgs +=$row1['export_kgs'];
	$internal_amount +=$row1['internal_amount'];
	$export_amount +=$row1['export_amount'];
} // 2nd loop
?>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; border:0px; font-size:14px !important;">SUB TOTAL: </th>
	<th style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($current_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($current_total_amount > 0 && $current_net_kgs > 0) ? number_format($current_total_amount / $current_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($current_total_sold > 0 && $current_offering_bag > 0) ? number_format(($current_total_sold * 100)/$current_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($upto_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($upto_total_amount > 0 && $upto_net_kgs > 0) ? number_format($upto_total_amount / $upto_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important; display:none;">
	<?php 
		print ($upto_total_sold > 0 && $upto_offering_bag > 0) ? number_format(($upto_total_sold * 100)/$upto_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>
<?php
	$current_total_sold2 +=$current_total_sold;
	$current_net_kgs2 +=$current_net_kgs;
	$current_total_amount2 +=$current_total_amount;
	$current_offering_bag2 +=$current_offering_bag;
	$upto_total_sold2 +=$upto_total_sold;
	$upto_net_kgs2 +=$upto_net_kgs;
	$upto_total_amount2 +=$upto_total_amount;
	$upto_offering_bag2 +=$upto_offering_bag;
	$internal_bags2 +=$internal_bags;
	$export_bags2 +=$export_bags;
	$internal_kgs2 +=$internal_kgs;
	$export_kgs2 +=$export_kgs;
	$internal_amount2 +=$internal_amount;
	$export_amount2 +=$export_amount;
} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="7" style="border:0px;"></td>
</tr>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; font-size:14px !important;">NET TOTAL: </th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($current_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_total_amount2 > 0 && $current_net_kgs2 > 0) ? number_format($current_total_amount2 / $current_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_total_sold2 > 0 && $current_offering_bag2 > 0) ? number_format(($current_total_sold2 * 100)/$current_offering_bag2,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($upto_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($upto_total_amount2 > 0 && $upto_net_kgs2 > 0) ? number_format($upto_total_amount2 / $upto_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important; display:none;">
	<?php 
		print ($upto_total_sold2 > 0 && $upto_offering_bag2 > 0) ? number_format(($upto_total_sold2 * 100)/$upto_offering_bag2,2,'.','') : '0.00'; 
	?>
	</th>
</tr>	
</tfoot>
</table>

<br />

<table class="table table-sm" id="table" style="margin: 0 auto; width: 60%;">
<tr>
	<th colspan="5" style="text-align:center; border:1px solid; font-size:14px !important;">UP-TO-SALE <?php print $sale_no; ?></th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">Buyers Type</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Bags</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">%</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Average</th>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid; font-size:14px !important;">INTERNAL</td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print $internal_bags2; ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($internal_kgs2,2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print $int_avg = ($internal_kgs2 > 0 && $upto_net_kgs2 > 0) ? number_format(($internal_kgs2 * 100)/$upto_net_kgs2,2,'.','') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($internal_amount2 > 0 && $internal_kgs2 > 0) ? number_format($internal_amount2 / $internal_kgs2,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid; font-size:14px !important;">EXPORT</td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print $export_bags2; ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($export_kgs2,2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print $exp_avg = ($export_kgs2 > 0 && $upto_net_kgs2 > 0) ? number_format(($export_kgs2 * 100)/$upto_net_kgs2,2,'.','') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($export_amount2 > 0 && $export_kgs2 > 0) ? number_format($export_amount2 / $export_kgs2,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">TOTAL: </th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;"><?php print $upto_total_sold2; ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;"><?php print number_format($upto_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">
	<?php 
		print number_format($int_avg + $exp_avg,2,'.',''); 
	?>
	</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">
	<?php 
		print ($upto_total_amount2 > 0 && $upto_net_kgs2 > 0) ? number_format($upto_total_amount2 / $upto_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
</tr>
</table>

<br />

</div>
</div>

<?php }else if($report_no=='5'){ ?>	
<style>
.table{
	font-family:verdana;
	width: 100% !important;
}
#table tr td {
	border-color:black;
	font-size:13px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:13px !important;
	padding:3px;
}
</style>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="javascript:void(0);" onclick="fn_company_wise_avg('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $combine_center; ?>');"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Company Wise Avg.</a>
		
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm" id="table" style="margin-bottom:10px;">
			<thead>
				<tr style="border-bottom:1px solid #000;">
					<td style="text-transform: uppercase; text-align:center; border:0;">
						<b style="font-size:15px;"><?php print $website_title; ?></b><br>
						<span style="font-size:13px;"><?php print $owner_short_address; ?> <br> Garden Wise average statement</span>
					</td>
				</tr>
			</thead>
		</table>
	</div>
	<div class="form-group col-sm-4">
		<table align="left">
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Sale No</th> 
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Sale Date</th>
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></th>
			</tr>
		</table>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:13px;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4">
		<table align="right">
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Season</th> 
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print $season; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Print Date</th>
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print date("d-m-Y"); ?></th>
			</tr>
		</table>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table" style="text-transform: uppercase;">
<thead>
<tr>
	<th style="text-align:left; border:0px;"></th>
	<th colspan="3" style="text-align:center; border:0px;">CURRENT SALE <br /> <?php print $auction_center; ?></th>
	<th colspan="2" style="text-align:center; border:0px;">UPTO DATE SALE <br /><?php print substr($auction_center,0,4); ?> to <?php print substr($combine_center,0,4); ?></th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid;">Garden Name</th>
	
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">Avg.</th>
	<th style="text-align:right; border:1px solid;">%</th>
	
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">Avg.</th>
	<th style="text-align:right; border:1px solid; display:none;">%</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_avg_report`");

// create garden_sales_avg_report
$this->db->query("CREATE TABLE `garden_sales_avg_report` (SELECT 

	invoice_entry.id, 
	invoice_entry.sale_no, 
	invoice_entry.auction_center, 
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	invoice_entry.category, 
	invoice_entry.account_concerned, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount, 
	garden_and_company_setup.garden_name,
	garden_and_company_setup.main_garden_id,
	garden_and_company_setup.main_garden,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_bag END,0) 
	) AS internal_bags,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_bag END,0) 
	) AS export_bags,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS internal_kgs,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS export_kgs,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_amount END,0) 
	) AS internal_amount,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_amount END,0) 
	) AS export_amount

FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_no <= '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id
) ");

// view data
$current_total_sold2 =0;
$current_net_kgs2 =0;
$current_total_amount2 =0;
$current_offering_bag2 =0;
$upto_total_sold2 =0;
$upto_net_kgs2 =0;
$upto_total_amount2 =0;
$upto_offering_bag2 =0;
$internal_bags2 =0;
$export_bags2 =0;
$internal_kgs2 =0;
$export_kgs2 =0;
$internal_amount2 =0;
$export_amount2 =0;

// old_season breakdown query
$this->db->select("old_season, category, CASE WHEN category = 'CTC' THEN 'A' ELSE 'B' END AS category_wise", false);
$this->db->from('garden_sales_avg_report');
$this->db->group_by(['old_season', 'category']);
$this->db->order_by('old_season', 'DESC');
$this->db->order_by('category_wise', 'ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<th colspan="7" class="text-danger" style="border:0px; font-size:13px !important; font-family:verdana;">
		SEASON: <?php print $row2['old_season']; ?><br>
		<?php print $row2['category']; ?>
	</th>
</tr>

<?php
$current_total_sold =0;
$current_net_kgs =0;
$current_total_amount =0;
$current_offering_bag =0;
$upto_total_sold =0;
$upto_net_kgs =0;
$upto_total_amount =0;
$upto_offering_bag =0;
$internal_bags =0;
$export_bags =0;
$internal_kgs =0;
$export_kgs =0;
$internal_amount =0;
$export_amount =0;

//query
$this->db->select("
	gs.garden_id, 
	gs.main_garden_id, 
	gs.main_garden, 
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,
	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.export_bags) AS export_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_amount) AS export_amount,
	gs.garden_name, 
	inv_offering.current_offering_bag,
	inv_offering.upto_offering_bag,
	gs.old_season
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(chest_number) AS upto_offering_bag FROM invoice_entry WHERE season='".$season."' AND sale_no <= '".$sale_no."' AND old_season='".$row2['old_season']."' AND catalogue_status='prepared' AND account_concerned='No' AND soft_delete='1' GROUP BY garden_id) inv_offering", "gs.garden_id=inv_offering.garden_id", "left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->where('gs.category',$row2['category']);
$this->db->where('gs.account_concerned','No');
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){

	$current_avg = ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? $row1['current_total_amount'] / $row1['current_net_kgs'] : '0'; 
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px;">
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id']); ?>"><?php print $row1['garden_name']; ?></a>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($current_avg,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($row1['current_total_sold'] > 0 && $row1['current_offering_bag'] > 0) ? number_format(($row1['current_total_sold'] * 100)/$row1['current_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_net_kgs'] > 0) ? number_format($row1['upto_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($row1['upto_total_amount'] > 0 && $row1['upto_net_kgs'] > 0) ? number_format($row1['upto_total_amount'] / $row1['upto_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; border:0px; display:none;">
	<?php 
		print ($row1['upto_total_sold'] > 0 && $row1['upto_offering_bag'] > 0) ? number_format(($row1['upto_total_sold'] * 100)/$row1['upto_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
</tr>

<?php
$current_total_sold +=$row1['current_total_sold'];
$current_net_kgs +=$row1['current_net_kgs'];
$current_total_amount +=$row1['current_total_amount'];
$current_offering_bag +=$row1['current_offering_bag'];
$upto_total_sold +=$row1['upto_total_sold'];
$upto_net_kgs +=$row1['upto_net_kgs'];
$upto_total_amount +=$row1['upto_total_amount'];
$upto_offering_bag +=$row1['upto_offering_bag'];
$internal_bags +=$row1['internal_bags'];
$export_bags +=$row1['export_bags'];
$internal_kgs +=$row1['internal_kgs'];
$export_kgs +=$row1['export_kgs'];
$internal_amount +=$row1['internal_amount'];
$export_amount +=$row1['export_amount'];

} // 2nd loop
?>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; border:0px;">SUB TOTAL: </th>
	<th style="text-align:right; border:0px;"><?php print number_format($current_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($current_total_amount > 0 && $current_net_kgs > 0) ? number_format($current_total_amount / $current_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($current_total_sold > 0 && $current_offering_bag > 0) ? number_format(($current_total_sold * 100)/$current_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px;"><?php print number_format($upto_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($upto_total_amount > 0 && $upto_net_kgs > 0) ? number_format($upto_total_amount / $upto_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; display:none;">
	<?php 
		print ($upto_total_sold > 0 && $upto_offering_bag > 0) ? number_format(($upto_total_sold * 100)/$upto_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>

<?php
$current_total_sold2 +=$current_total_sold;
$current_net_kgs2 +=$current_net_kgs;
$current_total_amount2 +=$current_total_amount;
$current_offering_bag2 +=$current_offering_bag;
$upto_total_sold2 +=$upto_total_sold;
$upto_net_kgs2 +=$upto_net_kgs;
$upto_total_amount2 +=$upto_total_amount;
$upto_offering_bag2 +=$upto_offering_bag;
$internal_bags2 +=$internal_bags;
$export_bags2 +=$export_bags;
$internal_kgs2 +=$internal_kgs;
$export_kgs2 +=$export_kgs;
$internal_amount2 +=$internal_amount;
$export_amount2 +=$export_amount;

} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="7" style="border:0px;"></td>
</tr>
<tr style="color:red;">
	<th colspan="1" style="text-align:right;">NET TOTAL: </th>
	<th style="text-align:right;"><?php print number_format($current_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right;">
	<?php 
		print ($current_total_amount2 > 0 && $current_net_kgs2 > 0) ? number_format($current_total_amount2 / $current_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right;">
	<?php 
		print ($current_total_sold2 > 0 && $current_offering_bag2 > 0) ? number_format(($current_total_sold2 * 100)/$current_offering_bag2,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right;"><?php print number_format($upto_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right;">
	<?php 
		print ($upto_total_amount2 > 0 && $upto_net_kgs2 > 0) ? number_format($upto_total_amount2 / $upto_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; display:none;">
	<?php 
		print ($upto_total_sold2 > 0 && $upto_offering_bag2 > 0) ? number_format(($upto_total_sold2 * 100)/$upto_offering_bag2,2,'.','') : '0.00'; 
	?>
	</th>
</tr>	
</tfoot>
</table>

<br />

<table class="table table-sm" id="table" style="margin: 0 auto; width: 60%;">
<tr>
	<th colspan="5" style="text-align:center; border:1px solid;">UP-TO-SALE <?php print $sale_no; ?></th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid;">Buyers Type</th>
	<th style="text-align:right; border:1px solid;">Total Bags</th>
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">%</th>
	<th style="text-align:right; border:1px solid;">Average</th>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid;">INTERNAL</td>
	<td style="text-align:right; border:0px;"><?php print $internal_bags2; ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($internal_kgs2,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;">
	<?php 
		print $int_avg = ($internal_kgs2 > 0 && $upto_net_kgs2 > 0) ? number_format(($internal_kgs2 * 100)/$upto_net_kgs2,2,'.','') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid;">
	<?php 
		print ($internal_amount2 > 0 && $internal_kgs2 > 0) ? number_format($internal_amount2 / $internal_kgs2,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid;">EXPORT</td>
	<td style="text-align:right; border:0px;"><?php print $export_bags2; ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($export_kgs2,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;">
	<?php 
		print $exp_avg = ($export_kgs2 > 0 && $upto_net_kgs2 > 0) ? number_format(($export_kgs2 * 100)/$upto_net_kgs2,2,'.','') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid;">
	<?php 
		print ($export_amount2 > 0 && $export_kgs2 > 0) ? number_format($export_amount2 / $export_kgs2,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<th style="text-align:left; border:1px solid;">TOTAL: </th>
	<th style="text-align:right; border:1px solid;"><?php print $upto_total_sold2; ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($upto_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
	<?php 
		print number_format($int_avg + $exp_avg,2,'.',''); 
	?>
	</th>
	<th style="text-align:right; border:1px solid;">
	<?php 
		print ($upto_total_amount2 > 0 && $upto_net_kgs2 > 0) ? number_format($upto_total_amount2 / $upto_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
</tr>
</table>

<br />

</div>
</div>

<?php }else if($report_no=='6'){ ?>

<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<br />

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="7" style="text-align:center; border:0px;">
		<span style="text-transform: uppercase; font-size:20px; text-align:center; font-weight:bold; font-family:courier;">
			<?php print $website_title; ?>
		</span>
		<br />
		<span style="font-size:16px; font-weight:normal;">
			<u><?php print $owner_short_address; ?></u>
		</span>
		<br />
		<span style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			Garden Wise Sale Accounts <br />(<?php print $season_name; ?>)
		</span>
		<br />
		<span style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			AUCTION CENTER : <?php print $auction_center; ?>
		</span>	
	</th>
</tr>
<tr>
	<td colspan="3" class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SEASON : <?php print $season; ?></td>
	
	<td colspan="4" class="text-right" style="font-family: verdana; border:0px; font-size:12px;"> SALE DATE : <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
</tr>
<tr>
	<th colspan="3" class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE NO : <?php print $sale_no; ?></th>
	
	<td colspan="4" class="text-right" style="font-family: verdana; border:0px; font-size:12px;"> PROMPT DATE : <?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
</tr>
<tr>
	<th colspan="3" class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> GARDEN NAME : <?php print $garden_data['garden_name']; ?></th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">LOT</th>
	<th style="text-align:center; border:1px solid;">INVOICE</th>
	<th style="text-align:left; border:1px solid;">GRADE</th>
	<th style="text-align:right; border:1px solid;">BAGS</th>
	<th style="text-align:right; border:1px solid;">KGS</th>
	<th style="text-align:right; border:1px solid;">PRICE</th>
	<th style="text-align:right; border:1px solid;">AMOUNT</th>
</tr>
</thead>
<tbody>
<?php
$sl = 1;
$total_kgs = 0;
$total_pkgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$tea_cess = 0;

// query
$this->db->select("

	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.chest_number, 
	invoice_entry.warehouse_charge, 
	invoice_entry.insp_sample, 
	invoice_entry.brokerage_commission, 
	invoice_entry.tea_cess, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
	@total_pkgs:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_pkgs,
	@total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,	
	sale_entry.sold_price,
	@amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS amount 		
"); 
$this->db->from('invoice_entry'); 
$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<th style="text-align:center; border:none;">
		<?php print $row2['lot_no']; ?>
	</th>
	<th style="text-align:center; border:none; text-transform:uppercase;">
		<?php print $row2['w_inv_no']; ?>
	</th>
	<th style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</th>
	<th style="text-align:right; border:none;">
		<?php print $row2['total_pkgs']; ?>
	</th>
	<th style="text-align:right; border:none;">
		<?php print number_format($row2['total_kgs'],2,'.',''); ?>
	</th>
	<th style="text-align:right; border:none;">
		<?php print number_format($row2['sold_price'],2,'.',''); ?>
	</th>
	<th style="text-align:right; border:none;">
		<?php print number_format($row2['amount'],2,'.',','); ?>
	</th>
</tr>	

<?php
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
	$warehouse_charge = $row2['warehouse_charge'];
	$insp_sample = $row2['insp_sample'];
	$brokerage_commission = $row2['brokerage_commission'];
	$tea_cess = $row2['tea_cess'];

} // second loop	
?>

<tr>
	<th style="text-align:right;" colspan="3"> TOTAL: </th>
	<th style="text-align:right;">
		<?php echo $total_pkgs; ?>
	</th>
	<th style="text-align:right;">
		<?php echo number_format($total_kgs,2,'.',''); ?>
	</th>
	<th style="text-align:right;">
		<?php echo ($total_amount > 0 && $total_kgs > 0) ? number_format($total_amount / $total_kgs,2,'.','') : ''; ?>
	</th>
	<th style="text-align:right;">
		<?php echo number_format($total_amount,2,'.',','); ?>
	</th>
</tr>
</tbody>
</table>
</div>
</div>

<div class="row">
<div class="col-sm-7">
<table class="table table-sm" id="table">
<tr>
	<td colspan="4" style="text-align:left; border:none;"><b> Add Charges : </td>
</tr>
<tr>
	<td style="text-align:left; border:none;">1. </td>
	<td style="text-align:left; border:none;">
		W/H charges @ Tk. <?php print $warehouse_charge; ?> Per Bags 
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<th style="text-align:right; border:none;">
	<?php 
		$total_warehouse_charge = number_format(($total_pkgs * $warehouse_charge),2,'.','');
		print number_format($total_warehouse_charge,2,'.',','); 
	?>
	</th>
</tr>
<tr>
	<td style="text-align:left; border:none;">2. </td>
	<td style="text-align:left; border:none;">
		Inspection & Sampling @ Tk. <?php print $insp_sample; ?> Per Bags
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<th style="text-align:right; border:none;">
	<?php 
		$total_insp_sample = number_format(($total_pkgs * $insp_sample),2,'.','');
		print number_format($total_insp_sample,2,'.',','); 
	?>
	</th>
</tr>
<tr>
	<td style="text-align:left; border:none;">3. </td>
	<td style="text-align:left; border:none;">
		Brokerage <?php print number_format($brokerage_commission,2,'.',''); ?> % @ Amount
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<th style="text-align:right; border:none;">
	<?php 
		$total_brokerage = number_format((($total_amount * $brokerage_commission)/100),2,'.','');
		print number_format($total_brokerage,2,'.',','); 
	?>
	</th>
</tr>
<tr>
	<td style="text-align:left; border:none;">4. </td>
	<td style="text-align:left; border:none;"> 
		Tea Cess <?php print number_format($tea_cess,2,'.',''); ?> % @ Amount 
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<th style="text-align:right; border:none;">
	<?php 
		$total_tea_cess = number_format((($total_amount * $tea_cess)/100),2,'.','');
		print number_format($total_tea_cess,2,'.',','); 
	?>
	</th>
</tr>
<?php
$sl=5;
$garden_adjust = 0;	
// garden adjust cost
$this->db->select("charges_name, charge_tk, particular"); 
$this->db->from('garden_adjust_tbl');
$this->db->where('season', $season);
$this->db->where('old_season', $old_season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$this->db->where('garden_id', $garden_id);
$this->db->order_by('id ASC');
$query = $this->db->get();
foreach($query->result_array() as $cost_row){	
?>
<tr>
	<td style="text-align:left; border:none;"><?php print $sl++; ?>. </td>
	<td style="text-align:left; border:none;">
		<?php print $cost_row['charges_name']; ?> (<?php print $cost_row['particular']; ?>)
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<th style="text-align:right; border:none;">
	<?php 
		print number_format($cost_row['charge_tk'],2,'.',',');
	?>
	</th>
</tr>
<?php 
	$garden_adjust += $cost_row['charge_tk'];
} // foreach
?>
	
<tr>
	<td style="text-align:left; border:none;"></td>
	<td style="text-align:left; border:none;"> 
		<b>Total Charges</b>
	</td>
	<td class="text-center" style="font-family: verdana; border:0px;"> : </td>
	<th style="text-align:right; border:none;">
	<?php 
		$total_charges = $total_warehouse_charge + $total_insp_sample + $total_brokerage + $total_tea_cess + $garden_adjust;
		print number_format($total_charges,2,'.',','); 
	?>
	</th>
</tr>
</table>
</div>
<div class="col-sm-5">
<table class="table table-sm" id="table">
<tr>
	<td style="text-align:right; border:none;"> Charges : &nbsp; </td>
	<th style="text-align:right; border:none;">
	<?php 
		print number_format($total_charges,2,'.',','); 
	?>
	</th>
	<tr>
	<td style="text-align:right; border:none; color:red;"><b>Net. Proceeds Tk. : &nbsp;</b> </td>
	<th style="text-align:right; border:none; color:red;">
	<?php 
		$net_proceed_tk = $total_amount - $total_charges;
		print number_format($net_proceed_tk,2,'.',','); 
	?>
	</th>
</tr>
</tr>
</table>
</div> <!-- col -->
</div> 
<!-- /row -->

<div class="row">
	<div class="col-sm-12">
		<p class="text-left" style="font-family: verdana; border:0px; font-weight:normal; font-size:14px;"> 
			<b>Taka : <?php print convert_number_to_words($net_proceed_tk); ?></b>
		</p>
	</div>
</div>
<!-- /row -->

<br />
<br />
<br />

<div class="row">
	<div class="col-sm-12 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px; float:right;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> &nbsp; &nbsp; &nbsp; </b>
		</p>
	</div>
</div>
<!-- /row -->

<?php }else if($report_no=='7'){ ?>	

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><u><?php print $owner_short_address; ?></u></span>
		<br /><br />
		<b style="text-transform: uppercase; font-size:16px; font-weight:bold; text-align:center;">
			Garden Wise average statement
		</b>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-4">
		<table align="left">
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Sale No</th> 
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Sale Date</th>
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></th>
			</tr>
		</table>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:14px;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4">
		<table align="right">
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Season</th> 
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print $season; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Print Date</th>
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print date("d-m-Y"); ?></th>
			</tr>
		</table>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table" style="text-transform: uppercase;">
<thead>
<tr>
	<th style="text-align:left; border:0px;"></th>
	<th colspan="3" style="text-align:center; border:0px; font-size:14px !important;">CURRENT SALE</th>
	<th colspan="2" style="text-align:center; border:0px; font-size:14px !important;">UPTO DATE SALE</th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">Garden Name</th>
	
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">%</th>
	
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important; display:none;">%</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_avg_report`");

// create garden_sales_avg_report
$this->db->query("CREATE TABLE `garden_sales_avg_report` (SELECT 

	invoice_entry.id, 
	invoice_entry.sale_no, 
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount, 
	garden_and_company_setup.garden_name,
	IF(company_setup.company_name IS NULL, 'Others', company_setup.company_name) AS company_name,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_bag END,0)) AS internal_bags,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_total_kgs END,0)) AS internal_kgs,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_amount END,0)) AS internal_amount,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_bag END,0)) AS export_bags,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_total_kgs END,0)) AS export_kgs,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_amount END,0)) AS export_amount

FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
LEFT JOIN company_setup ON (garden_and_company_setup.company_name = company_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no <= '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id
) ");
// end of create temp table

// reset data
$current_offering_bag3 =0;
$current_offering_kgs3 =0;
$current_total_sold3 =0;
$current_net_kgs3 =0;
$current_total_amount3 =0;

$upto_offering_bag3 =0;
$upto_offering_kgs3 =0;
$upto_total_sold3 =0;
$upto_net_kgs3 =0;
$upto_total_amount3 =0;

$internal_bags3 =0;
$internal_kgs3 = 0;
$internal_amount3 = 0;

$export_bags3 = 0;
$export_kgs3 = 0;
$export_amount3 = 0;

// old season wise breakdown
$this->db->select("old_season"); 
$this->db->from('garden_sales_avg_report');
$this->db->group_by('old_season');
$this->db->order_by('id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>
<tr>
	<th colspan="7" style="border:0px; font-size:14px !important; color:red;">
		<u>Season: <?php print $row2['old_season']; ?></u>
	</th>
</tr>
<?php
// reset data
$current_offering_bag2 =0;
$current_offering_kgs2 =0;
$current_total_sold2 =0;
$current_net_kgs2 =0;
$current_total_amount2 =0;

$upto_offering_bag2 =0;
$upto_offering_kgs2 =0;
$upto_total_sold2 =0;
$upto_net_kgs2 =0;
$upto_total_amount2 =0;

$internal_bags2 =0;
$internal_kgs2 = 0;
$internal_amount2 = 0;

$export_bags2 = 0;
$export_kgs2 = 0;
$export_amount2 = 0;

// company wise breakdown
$this->db->select("company_name"); 	
$this->db->from('garden_sales_avg_report');
$this->db->where('old_season', $row2['old_season']);
$this->db->group_by('company_name');
$this->db->order_by('company_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $company_row){
?>
<tr>
	<th colspan="7" class="text-danger" style="border:0px; font-size:14px !important;"><?php print $company_row['company_name']; ?></th>
</tr>
<?php
// garden wise breakdown
$current_offering_bag =0;
$current_offering_kgs =0;
$current_total_sold =0;
$current_net_kgs =0;
$current_total_amount =0;

$upto_offering_bag =0;
$upto_offering_kgs =0;
$upto_total_sold =0;
$upto_net_kgs =0;
$upto_total_amount =0;

$internal_bags =0;
$internal_kgs = 0;
$internal_amount = 0;

$export_bags = 0;
$export_kgs = 0;
$export_amount = 0;

// qry
$this->db->select("
	gs.garden_id, 
	gs.garden_name,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,
	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	inv_offering.current_offering_bag,
	inv_offering.current_offering_kgs,
	inv_offering.upto_offering_bag,
	inv_offering.upto_offering_kgs,
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_bags) AS export_bags,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.export_amount) AS export_amount,
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(CASE WHEN sale_no='".$sale_no."' THEN net_kgs END) AS current_offering_kgs, sum(chest_number) AS upto_offering_bag, sum(net_kgs) AS upto_offering_kgs FROM `invoice_entry` WHERE `season`='".$season."' AND `auction_center`='".$auction_center."' AND `sale_no` <= '".$sale_no."' AND `old_season`='".$row2['old_season']."' AND `catalogue_status`='prepared' AND `soft_delete`='1' GROUP BY garden_id) inv_offering", "gs.garden_id = inv_offering.garden_id", "left");

$this->db->where('gs.old_season', $row2['old_season']);
$this->db->where('gs.company_name', $company_row['company_name']);
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-size:14px !important;">
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id']); ?>"><?php print $row1['garden_name']; ?></a>
	</td>
	
	<td style="text-align:right; border:0px; font-size:14px !important;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? number_format($row1['current_total_amount'] / $row1['current_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['current_total_sold'] > 0 && $row1['current_offering_bag'] > 0) ? number_format(($row1['current_total_sold'] * 100)/$row1['current_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
	
	<td style="text-align:right; border:0px; font-size:14px !important;">
		<?php print ($row1['upto_net_kgs'] > 0) ? number_format($row1['upto_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['upto_total_amount'] > 0 && $row1['upto_net_kgs'] > 0) ? number_format($row1['upto_total_amount'] / $row1['upto_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important; display:none;">
	<?php 
		print ($row1['upto_total_sold'] > 0 && $row1['upto_offering_bag'] > 0) ? number_format(($row1['upto_total_sold'] * 100)/$row1['upto_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
</tr>
<?php
	$current_offering_bag +=$row1['current_offering_bag'];
	$current_offering_kgs +=$row1['current_offering_kgs'];
	$current_total_sold +=$row1['current_total_sold'];
	$current_net_kgs +=$row1['current_net_kgs'];
	$current_total_amount +=$row1['current_total_amount'];
	
	$upto_offering_bag +=$row1['upto_offering_bag'];
	$upto_offering_kgs +=$row1['upto_offering_kgs'];
	$upto_total_sold +=$row1['upto_total_sold'];
	$upto_net_kgs +=$row1['upto_net_kgs'];
	$upto_total_amount +=$row1['upto_total_amount'];
	
	$internal_bags +=$row1['internal_bags'];
	$internal_kgs +=$row1['internal_kgs'];
	$internal_amount +=$row1['internal_amount'];
	
	$export_bags +=$row1['export_bags'];
	$export_kgs +=$row1['export_kgs'];
	$export_amount +=$row1['export_amount'];
} // garden wise breakdown
?>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; border:0px; font-size:14px !important;">
		<?php print $company_row['company_name']; ?> TOTAL: 
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($current_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($current_total_amount > 0 && $current_net_kgs > 0) ? number_format($current_total_amount / $current_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($current_total_sold > 0 && $current_offering_bag > 0) ? number_format(($current_total_sold * 100)/$current_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($upto_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($upto_total_amount > 0 && $upto_net_kgs > 0) ? number_format($upto_total_amount / $upto_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important; display:none;">
	<?php 
		print ($upto_total_sold > 0 && $upto_offering_bag > 0) ? number_format(($upto_total_sold * 100)/$upto_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>
<?php
	$current_offering_bag2 +=$current_offering_bag;
	$current_offering_kgs2 +=$current_offering_kgs;
	$current_total_sold2 +=$current_total_sold;
	$current_net_kgs2 +=$current_net_kgs;
	$current_total_amount2 +=$current_total_amount;
	
	$upto_offering_bag2 +=$upto_offering_bag;
	$upto_offering_kgs2 +=$upto_offering_kgs;
	$upto_total_sold2 +=$upto_total_sold;
	$upto_net_kgs2 +=$upto_net_kgs;
	$upto_total_amount2 +=$upto_total_amount;
	
	$internal_bags2 +=$internal_bags;
	$internal_kgs2 +=$internal_kgs;
	$internal_amount2 +=$internal_amount;
	
	$export_bags2 +=$export_bags;
	$export_kgs2 +=$export_kgs;
	$export_amount2 +=$export_amount;
} //company wise breakdown
?>
<tr>
	<td colspan="7" style="border:0px;"></td>
</tr>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; font-size:14px !important;">NET TOTAL: </th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($current_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_total_amount2 > 0 && $current_net_kgs2 > 0) ? number_format($current_total_amount2 / $current_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_net_kgs2 > 0 && $current_offering_kgs2 > 0) ? number_format(($current_net_kgs2 * 100)/$current_offering_kgs2,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($upto_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($upto_total_amount2 > 0 && $upto_net_kgs2 > 0) ? number_format($upto_total_amount2 / $upto_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important; display:none;">
	<?php 
		print ($upto_net_kgs2 > 0 && $upto_offering_kgs2 > 0) ? number_format(($upto_net_kgs2 * 100)/$upto_offering_kgs2,2,'.','') : '0.00'; 
	?>
	</th>
</tr>	
<?php 
	$current_offering_bag3 +=$current_offering_bag2;
	$current_offering_kgs3 +=$current_offering_kgs2;
	$current_total_sold3 +=$current_total_sold2;
	$current_net_kgs3 +=$current_net_kgs2;
	$current_total_amount3 +=$current_total_amount2;
	
	$upto_offering_bag3 +=$upto_offering_bag2;
	$upto_offering_kgs3 +=$upto_offering_kgs2;
	$upto_total_sold3 +=$upto_total_sold2;
	$upto_net_kgs3 +=$upto_net_kgs2;
	$upto_total_amount3 +=$upto_total_amount2;
	
	$internal_bags3 +=$internal_bags2;
	$internal_kgs3 +=$internal_kgs2;
	$internal_amount3 +=$internal_amount2;
	
	$export_bags3 +=$export_bags2;
	$export_kgs3 +=$export_kgs2;
	$export_amount3 +=$export_amount2;
} // old season wise breakdown 
?>
<tr>
	<td colspan="7" style="border:0px;"></td>
</tr>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; font-size:14px !important;">GRAND TOTAL: </th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($current_net_kgs3,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_total_amount3 > 0 && $current_net_kgs3 > 0) ? number_format($current_total_amount3 / $current_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_net_kgs3 > 0 && $current_offering_kgs3 > 0) ? number_format(($current_net_kgs3 * 100)/$current_offering_kgs3,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($upto_net_kgs3,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($upto_total_amount3 > 0 && $upto_net_kgs3 > 0) ? number_format($upto_total_amount3 / $upto_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important; display:none;">
	<?php 
		print ($upto_net_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($upto_net_kgs3 * 100)/$upto_offering_kgs3,2,'.','') : '0.00'; 
	?>
	</th>
</tr>
</tbody>
</table>

<br />

<div class="row">
<div class="col-sm-6">
<table class="table table-sm" id="table">
<tr>
	<th colspan="5" style="text-align:center; border:1px solid; font-size:14px !important;">CURRENT SALE # <?php print $sale_no; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Offering Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Sold Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Sold %</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
</tr>
<tr>
	<td style="text-align:right; border:1px solid; font-size:14px !important;"><?php print number_format($current_offering_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid; font-size:14px !important;"><?php print number_format($current_net_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($current_net_kgs3 > 0 && $current_offering_kgs3 > 0) ? number_format(($current_net_kgs3 / $current_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:1px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($current_total_amount3 > 0 && $current_net_kgs3 > 0) ? number_format($current_total_amount3 / $current_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
</table>
</div>

<div class="col-sm-6">
<table class="table table-sm" id="table">
<tr>
	<th colspan="5" style="text-align:center; border:1px solid; font-size:14px !important;">UP-TO-SALE # <?php print $sale_no; ?></th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">Buyers Type</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Bags</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">%</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid; font-size:14px !important;">INTERNAL</td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print $internal_bags3; ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($internal_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:0px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($internal_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($internal_kgs3 / $upto_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($internal_amount3 > 0 && $internal_kgs3 > 0) ? number_format($internal_amount3 / $internal_kgs3,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid; font-size:14px !important;">EXPORT</td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print $export_bags3; ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($export_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:0px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($export_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($export_kgs3 / $upto_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($export_amount3 > 0 && $export_kgs3 > 0) ? number_format($export_amount3 / $export_kgs3,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">TOTAL: </th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;"><?php print $upto_total_sold3; ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;"><?php print number_format($upto_net_kgs3,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">
		<?php 
		print ($upto_net_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($upto_net_kgs3 / $upto_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">
	<?php 
		print ($upto_total_amount3 > 0 && $upto_net_kgs3 > 0) ? number_format($upto_total_amount3 / $upto_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</th>
</tr>
</table>
</div>
</div>
<!-- /row-->

<br />

</div>
</div>
<!-- /row-->

<?php }else if($report_no=='8'){ ?>	

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><u><?php print $owner_short_address; ?></u></span>
		<br /><br />
		<b style="text-transform: uppercase; font-size:16px; font-weight:bold; text-align:center;">
			Garden Wise average statement
		</b>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-4">
		<table align="left">
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Sale No</th> 
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Sale Date</th>
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></th>
			</tr>
		</table>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:14px;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4">
		<table align="right">
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Season</th> 
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print $season; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:14px !important;">Print Date</th>
				<th style="text-transform: uppercase; font-size:14px !important;">&nbsp; : &nbsp;</th> 
				<th style="text-transform: uppercase; font-size:14px !important; text-align:left;"><?php print date("d-m-Y"); ?></th>
			</tr>
		</table>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table" style="text-transform: uppercase;">
<thead>
<tr>
	<th style="text-align:left; border:0px;"></th>
	<th colspan="3" style="text-align:center; border:0px; font-size:14px !important;">CURRENT SALE <br /> <?php print $auction_center; ?></th>
	<th colspan="2" style="text-align:center; border:0px; font-size:14px !important;">UPTO DATE SALE <br /><?php print substr($auction_center,0,4); ?> to <?php print substr($combine_center,0,4); ?></th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">Garden Name</th>
	
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">%</th>
	
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important; display:none;">%</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_avg_report`");

// create garden_sales_avg_report
$this->db->query("CREATE TABLE `garden_sales_avg_report` (SELECT 

	invoice_entry.id, 
	invoice_entry.sale_no, 
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount, 
	garden_and_company_setup.garden_name,
	IF(company_setup.company_name IS NULL, 'Others', company_setup.company_name) AS company_name,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_bag END,0)) AS internal_bags,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_total_kgs END,0)) AS internal_kgs,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_amount END,0)) AS internal_amount,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_bag END,0)) AS export_bags,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_total_kgs END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_total_kgs END,0)) AS export_kgs,
	
	(IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_amount END,0) + IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_amount END,0)) AS export_amount

FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
LEFT JOIN company_setup ON (garden_and_company_setup.company_name = company_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id
) ");
// end of create temp table

// reset data
$current_offering_bag3 =0;
$current_offering_kgs3 =0;
$current_total_sold3 =0;
$current_net_kgs3 =0;
$current_total_amount3 =0;

$upto_offering_bag3 =0;
$upto_offering_kgs3 =0;
$upto_total_sold3 =0;
$upto_net_kgs3 =0;
$upto_total_amount3 =0;

$internal_bags3 =0;
$internal_kgs3 = 0;
$internal_amount3 = 0;

$export_bags3 = 0;
$export_kgs3 = 0;
$export_amount3 = 0;

// old season wise breakdown
$this->db->select("old_season"); 
$this->db->from('garden_sales_avg_report');
$this->db->group_by('old_season');
$this->db->order_by('id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>
<tr>
	<th colspan="7" style="border:0px; font-size:14px !important; color:red;">
		<u>Season: <?php print $row2['old_season']; ?></u>
	</th>
</tr>
<?php
// reset data
$current_offering_bag2 =0;
$current_offering_kgs2 =0;
$current_total_sold2 =0;
$current_net_kgs2 =0;
$current_total_amount2 =0;

$upto_offering_bag2 =0;
$upto_offering_kgs2 =0;
$upto_total_sold2 =0;
$upto_net_kgs2 =0;
$upto_total_amount2 =0;

$internal_bags2 =0;
$internal_kgs2 = 0;
$internal_amount2 = 0;

$export_bags2 = 0;
$export_kgs2 = 0;
$export_amount2 = 0;

// company wise breakdown
$this->db->select("company_name"); 	
$this->db->from('garden_sales_avg_report');
$this->db->where('old_season', $row2['old_season']);
$this->db->group_by('company_name');
$this->db->order_by('company_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $company_row){
?>
<tr>
	<th colspan="7" class="text-danger" style="border:0px; font-size:14px !important;"><?php print $company_row['company_name']; ?></th>
</tr>
<?php
// garden wise breakdown
$current_offering_bag =0;
$current_offering_kgs =0;
$current_total_sold =0;
$current_net_kgs =0;
$current_total_amount =0;

$upto_offering_bag =0;
$upto_offering_kgs =0;
$upto_total_sold =0;
$upto_net_kgs =0;
$upto_total_amount =0;

$internal_bags =0;
$internal_kgs = 0;
$internal_amount = 0;

$export_bags = 0;
$export_kgs = 0;
$export_amount = 0;

// qry
$this->db->select("
	gs.garden_id, 
	gs.garden_name,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,
	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	inv_offering.current_offering_bag,
	inv_offering.current_offering_kgs,
	inv_offering.upto_offering_bag,
	inv_offering.upto_offering_kgs,
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_bags) AS export_bags,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.export_amount) AS export_amount,
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN net_kgs END) AS current_offering_kgs, sum(chest_number) AS upto_offering_bag, sum(net_kgs) AS upto_offering_kgs FROM `invoice_entry` WHERE `season`='".$season."' AND `sale_date` <= '".$sale_data['sale_date']."' AND `old_season`='".$row2['old_season']."' AND `catalogue_status`='prepared' AND `soft_delete`='1' GROUP BY garden_id) inv_offering", "gs.garden_id = inv_offering.garden_id", "left");

$this->db->where('gs.old_season', $row2['old_season']);
$this->db->where('gs.company_name', $company_row['company_name']);
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-size:14px !important;">
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id']); ?>"><?php print $row1['garden_name']; ?></a>
	</td>
	
	<td style="text-align:right; border:0px; font-size:14px !important;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? number_format($row1['current_total_amount'] / $row1['current_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['current_total_sold'] > 0 && $row1['current_offering_bag'] > 0) ? number_format(($row1['current_total_sold'] * 100)/$row1['current_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
	
	<td style="text-align:right; border:0px; font-size:14px !important;">
		<?php print ($row1['upto_net_kgs'] > 0) ? number_format($row1['upto_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($row1['upto_total_amount'] > 0 && $row1['upto_net_kgs'] > 0) ? number_format($row1['upto_total_amount'] / $row1['upto_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
	<td style="text-align:right; border:0px; font-size:14px !important; display:none;">
	<?php 
		print ($row1['upto_total_sold'] > 0 && $row1['upto_offering_bag'] > 0) ? number_format(($row1['upto_total_sold'] * 100)/$row1['upto_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
</tr>
<?php
	$current_offering_bag +=$row1['current_offering_bag'];
	$current_offering_kgs +=$row1['current_offering_kgs'];
	$current_total_sold +=$row1['current_total_sold'];
	$current_net_kgs +=$row1['current_net_kgs'];
	$current_total_amount +=$row1['current_total_amount'];
	
	$upto_offering_bag +=$row1['upto_offering_bag'];
	$upto_offering_kgs +=$row1['upto_offering_kgs'];
	$upto_total_sold +=$row1['upto_total_sold'];
	$upto_net_kgs +=$row1['upto_net_kgs'];
	$upto_total_amount +=$row1['upto_total_amount'];
	
	$internal_bags +=$row1['internal_bags'];
	$internal_kgs +=$row1['internal_kgs'];
	$internal_amount +=$row1['internal_amount'];
	
	$export_bags +=$row1['export_bags'];
	$export_kgs +=$row1['export_kgs'];
	$export_amount +=$row1['export_amount'];
} // garden wise breakdown
?>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; border:0px; font-size:14px !important;">
		<?php print $company_row['company_name']; ?> TOTAL: 
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($current_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($current_total_amount > 0 && $current_net_kgs > 0) ? number_format($current_total_amount / $current_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($current_total_sold > 0 && $current_offering_bag > 0) ? number_format(($current_total_sold * 100)/$current_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($upto_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; font-size:14px !important;">
	<?php 
		print ($upto_total_amount > 0 && $upto_net_kgs > 0) ? number_format($upto_total_amount / $upto_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px; font-size:14px !important; display:none;">
	<?php 
		print ($upto_total_sold > 0 && $upto_offering_bag > 0) ? number_format(($upto_total_sold * 100)/$upto_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>
<?php
	$current_offering_bag2 +=$current_offering_bag;
	$current_offering_kgs2 +=$current_offering_kgs;
	$current_total_sold2 +=$current_total_sold;
	$current_net_kgs2 +=$current_net_kgs;
	$current_total_amount2 +=$current_total_amount;
	
	$upto_offering_bag2 +=$upto_offering_bag;
	$upto_offering_kgs2 +=$upto_offering_kgs;
	$upto_total_sold2 +=$upto_total_sold;
	$upto_net_kgs2 +=$upto_net_kgs;
	$upto_total_amount2 +=$upto_total_amount;
	
	$internal_bags2 +=$internal_bags;
	$internal_kgs2 +=$internal_kgs;
	$internal_amount2 +=$internal_amount;
	
	$export_bags2 +=$export_bags;
	$export_kgs2 +=$export_kgs;
	$export_amount2 +=$export_amount;
} //company wise breakdown
?>
<tr>
	<td colspan="7" style="border:0px;"></td>
</tr>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; font-size:14px !important;">NET TOTAL: </th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($current_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_total_amount2 > 0 && $current_net_kgs2 > 0) ? number_format($current_total_amount2 / $current_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_net_kgs2 > 0 && $current_offering_kgs2 > 0) ? number_format(($current_net_kgs2 * 100)/$current_offering_kgs2,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($upto_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($upto_total_amount2 > 0 && $upto_net_kgs2 > 0) ? number_format($upto_total_amount2 / $upto_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important; display:none;">
	<?php 
		print ($upto_net_kgs2 > 0 && $upto_offering_kgs2 > 0) ? number_format(($upto_net_kgs2 * 100)/$upto_offering_kgs2,2,'.','') : '0.00'; 
	?>
	</th>
</tr>	
<?php 
	$current_offering_bag3 +=$current_offering_bag2;
	$current_offering_kgs3 +=$current_offering_kgs2;
	$current_total_sold3 +=$current_total_sold2;
	$current_net_kgs3 +=$current_net_kgs2;
	$current_total_amount3 +=$current_total_amount2;
	
	$upto_offering_bag3 +=$upto_offering_bag2;
	$upto_offering_kgs3 +=$upto_offering_kgs2;
	$upto_total_sold3 +=$upto_total_sold2;
	$upto_net_kgs3 +=$upto_net_kgs2;
	$upto_total_amount3 +=$upto_total_amount2;
	
	$internal_bags3 +=$internal_bags2;
	$internal_kgs3 +=$internal_kgs2;
	$internal_amount3 +=$internal_amount2;
	
	$export_bags3 +=$export_bags2;
	$export_kgs3 +=$export_kgs2;
	$export_amount3 +=$export_amount2;
} // old season wise breakdown 
?>
<tr>
	<td colspan="7" style="border:0px;"></td>
</tr>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; font-size:14px !important;">GRAND TOTAL: </th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($current_net_kgs3,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_total_amount3 > 0 && $current_net_kgs3 > 0) ? number_format($current_total_amount3 / $current_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($current_net_kgs3 > 0 && $current_offering_kgs3 > 0) ? number_format(($current_net_kgs3 * 100)/$current_offering_kgs3,2,'.','') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important;"><?php print number_format($upto_net_kgs3,2,'.',','); ?></th>
	<th style="text-align:right; font-size:14px !important;">
	<?php 
		print ($upto_total_amount3 > 0 && $upto_net_kgs3 > 0) ? number_format($upto_total_amount3 / $upto_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; font-size:14px !important; display:none;">
	<?php 
		print ($upto_net_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($upto_net_kgs3 * 100)/$upto_offering_kgs3,2,'.','') : '0.00'; 
	?>
	</th>
</tr>
</tbody>
</table>

<br />

<div class="row">
<div class="col-sm-6">
<table class="table table-sm" id="table">
<tr>
	<th colspan="5" style="text-align:center; border:1px solid; font-size:14px !important;">CURRENT SALE # <?php print $sale_no; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Offering Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Sold Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Sold %</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
</tr>
<tr>
	<td style="text-align:right; border:1px solid; font-size:14px !important;"><?php print number_format($current_offering_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid; font-size:14px !important;"><?php print number_format($current_net_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:1px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($current_net_kgs3 > 0 && $current_offering_kgs3 > 0) ? number_format(($current_net_kgs3 / $current_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:1px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($current_total_amount3 > 0 && $current_net_kgs3 > 0) ? number_format($current_total_amount3 / $current_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
</table>
</div>

<div class="col-sm-6">
<table class="table table-sm" id="table">
<tr>
	<th colspan="5" style="text-align:center; border:1px solid; font-size:14px !important;">UP-TO-SALE # <?php print $sale_no; ?></th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">Buyers Type</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Bags</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">%</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">Avg.</th>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid; font-size:14px !important;">INTERNAL</td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print $internal_bags3; ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($internal_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:0px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($internal_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($internal_kgs3 / $upto_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($internal_amount3 > 0 && $internal_kgs3 > 0) ? number_format($internal_amount3 / $internal_kgs3,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px; border-left:1px solid; font-size:14px !important;">EXPORT</td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print $export_bags3; ?></td>
	<td style="text-align:right; border:0px; font-size:14px !important;"><?php print number_format($export_kgs3,2,'.',','); ?></td>
	<td style="text-align:right; border:0px solid; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($export_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($export_kgs3 / $upto_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</td>
	<td style="text-align:right; border:0px; border-right:1px solid; font-size:14px !important;">
	<?php 
		print ($export_amount3 > 0 && $export_kgs3 > 0) ? number_format($export_amount3 / $export_kgs3,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:14px !important;">TOTAL: </th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;"><?php print $upto_total_sold3; ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;"><?php print number_format($upto_net_kgs3,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">
		<?php 
		print ($upto_net_kgs3 > 0 && $upto_offering_kgs3 > 0) ? number_format(($upto_net_kgs3 / $upto_offering_kgs3)*100,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:1px solid; font-size:14px !important;">
	<?php 
		print ($upto_total_amount3 > 0 && $upto_net_kgs3 > 0) ? number_format($upto_total_amount3 / $upto_net_kgs3,2,'.',',') : '0.00'; 
	?>
	</th>
</tr>
</table>
</div>
</div>
<!-- /row-->

<br />

</div>
</div>
<!-- /row-->
<?php }else if($report_no=='9'){ ?>
<style>
.table{
	font-family:verdana;
	width: 100% !important;
}
#table tr td, #garden_avg_table tr td {
	border-color:black;
	font-size:13px !important;
	padding:3px;
}
#table tr th, #garden_avg_table tr th{
	border-color:black;
	font-size:13px !important;
	padding:3px;
}
</style>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
		&nbsp;|&nbsp;
		<a class="no-print text-danger" href="#" onClick="fn_delete_garden_avg();"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete Saved Data</a>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm" id="table" style="margin-bottom:10px;">
			<thead>
				<tr style="border-bottom:1px solid #000;">
					<td style="text-transform: uppercase; text-align:center; border:0;">
						<b style="font-size:15px;"><?php print $website_title; ?></b><br>
						<span style="font-size:13px;"><?php print $owner_short_address; ?> <br> Garden Wise average statement</span>
					</td>
				</tr>
			</thead>
		</table>
	</div>
	<div class="form-group col-sm-4">
		<table align="left">
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Sale No</th> 
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Sale Date</th>
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></th>
			</tr>
		</table>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:13px;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4">
		<table align="right">
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Season</th> 
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print $season; ?></th>
			</tr>
			<tr>
				<th style="text-transform: uppercase; font-size:13px !important;">Print Date</th>
				<th style="text-transform: uppercase; font-size:13px !important;">&nbsp; : &nbsp;</th>
				<th style="text-transform: uppercase; font-size:13px !important; text-align:left;"><?php print date("d-m-Y"); ?></th>
			</tr>
		</table>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="garden_avg_table" style="text-transform: uppercase;">
<thead>
<tr>
	<th style="text-align:left; border:1px solid;">Garden Name</th>
	
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">Avg.</th>
	<th style="text-align:right; border:1px solid;">%</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_avg_report`");

// create garden_sales_avg_report
$this->db->query("CREATE TABLE `garden_sales_avg_report` (SELECT 

	invoice_entry.id, 
	invoice_entry.sale_no, 
	invoice_entry.auction_center, 
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	invoice_entry.category, 
	invoice_entry.account_concerned, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount, 
	garden_and_company_setup.garden_name,
	garden_and_company_setup.main_garden_id,
	garden_and_company_setup.main_garden,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_bag END,0) 
	) AS internal_bags,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_bag END,0) 
	) AS export_bags,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS internal_kgs,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS export_kgs,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_amount END,0) 
	) AS internal_amount,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_amount END,0) 
	) AS export_amount

FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_no <= '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id
) ");

// view data
$current_total_sold2 =0;
$current_net_kgs2 =0;
$current_total_amount2 =0;
$current_offering_bag2 =0;
$upto_total_sold2 =0;
$upto_net_kgs2 =0;
$upto_total_amount2 =0;
$upto_offering_bag2 =0;
$internal_bags2 =0;
$export_bags2 =0;
$internal_kgs2 =0;
$export_kgs2 =0;
$internal_amount2 =0;
$export_amount2 =0;

// old_season breakdown query
$this->db->select("old_season, category, CASE WHEN category = 'CTC' THEN 'A' ELSE 'B' END AS category_wise", false);
$this->db->from('garden_sales_avg_report');
$this->db->group_by(['old_season', 'category']);
$this->db->order_by('old_season', 'DESC');
$this->db->order_by('category_wise', 'ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<th colspan="4" class="text-danger" style="border:0px;">
		<?php if($row2['old_season']==$season){ ?>
			<a href="javascript:void(0);" onClick="fn_set_garden_avg();">SEASON: <?php print $row2['old_season']; ?></a><br>
			<?php print $row2['category']; ?>
		<?php }else{ ?> 
			SEASON: <?php print $row2['old_season']; ?><br>
			<?php print $row2['category']; ?>
		<?php } ?>
	</th>
</tr>

<?php
$current_total_sold =0;
$current_net_kgs =0;
$current_total_amount =0;
$current_offering_bag =0;
$upto_total_sold =0;
$upto_net_kgs =0;
$upto_total_amount =0;
$upto_offering_bag =0;
$internal_bags =0;
$export_bags =0;
$internal_kgs =0;
$export_kgs =0;
$internal_amount =0;
$export_amount =0;

//query
$this->db->select("
	gs.garden_id, 
	gs.main_garden_id, 
	gs.main_garden, 
	gs.category, 
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,
	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.export_bags) AS export_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_amount) AS export_amount,
	gs.garden_name, 
	inv_offering.current_offering_bag,
	inv_offering.upto_offering_bag,
	gs.old_season
"); 

$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(chest_number) AS upto_offering_bag FROM invoice_entry WHERE season='".$season."' AND sale_date <= '".$sale_data['sale_date']."' AND old_season='".$row2['old_season']."' AND catalogue_status='prepared' AND account_concerned='No' AND soft_delete='1' GROUP BY garden_id) inv_offering", "gs.garden_id=inv_offering.garden_id", "left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->where('gs.category',$row2['category']);
$this->db->where('gs.account_concerned','No');
$this->db->having('current_net_kgs != 0');
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
	$current_avg = ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? $row1['current_total_amount'] / $row1['current_net_kgs'] : '0'; 
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px;">
		<a target="blink" style="color:black;" href="<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id']); ?>"><?php print $row1['garden_name']; ?></a>

		<?php if($row2['old_season']==$season){ ?>
			<input type="hidden" readonly id="garden_avg_row<?php echo $row1['garden_id']; ?>" value="<?php echo $row1['old_season'].'~'.$auction_center.'~'.$sale_no.'~'.'6'.'~'.'Unity brokers limited'.'~'.$row1['garden_id'].'~'.$row1['garden_name'].'~'.$row1['current_net_kgs'].'~'.$current_avg.'~'.$ubl_user_name.'~'.$datetime.'~'.$last_update.'~'.$row1['category']; ?>">
		<?php } ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($current_avg ,2,'.',','); 
	?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($row1['current_total_sold'] > 0 && $row1['current_offering_bag'] > 0) ? number_format(($row1['current_total_sold'] * 100)/$row1['current_offering_bag'],2,'.','') : '0'; 
	?>
	</td>
</tr>

<?php
$current_total_sold +=$row1['current_total_sold'];
$current_net_kgs +=$row1['current_net_kgs'];
$current_total_amount +=$row1['current_total_amount'];
$current_offering_bag +=$row1['current_offering_bag'];
$upto_total_sold +=$row1['upto_total_sold'];
$upto_net_kgs +=$row1['upto_net_kgs'];
$upto_total_amount +=$row1['upto_total_amount'];
$upto_offering_bag +=$row1['upto_offering_bag'];
$internal_bags +=$row1['internal_bags'];
$export_bags +=$row1['export_bags'];
$internal_kgs +=$row1['internal_kgs'];
$export_kgs +=$row1['export_kgs'];
$internal_amount +=$row1['internal_amount'];
$export_amount +=$row1['export_amount'];

} // 2nd loop
?>
<tr style="color:red;">
	<th colspan="1" style="text-align:right; border:0px;">SUB TOTAL: </th>
	<th style="text-align:right; border:0px;"><?php print number_format($current_net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($current_total_amount > 0 && $current_net_kgs > 0) ? number_format($current_total_amount / $current_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($current_total_sold > 0 && $current_offering_bag > 0) ? number_format(($current_total_sold * 100)/$current_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>

<?php
$current_total_sold2 +=$current_total_sold;
$current_net_kgs2 +=$current_net_kgs;
$current_total_amount2 +=$current_total_amount;
$current_offering_bag2 +=$current_offering_bag;
$upto_total_sold2 +=$upto_total_sold;
$upto_net_kgs2 +=$upto_net_kgs;
$upto_total_amount2 +=$upto_total_amount;
$upto_offering_bag2 +=$upto_offering_bag;
$internal_bags2 +=$internal_bags;
$export_bags2 +=$export_bags;
$internal_kgs2 +=$internal_kgs;
$export_kgs2 +=$export_kgs;
$internal_amount2 +=$internal_amount;
$export_amount2 +=$export_amount;

} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="4" style="border:0px;"></td>
</tr>
<tr style="color:red;">
	<th colspan="1" style="text-align:right;">NET TOTAL: </th>
	<th style="text-align:right;"><?php print number_format($current_net_kgs2,2,'.',','); ?></th>
	<th style="text-align:right;">
	<?php 
		print ($current_total_amount2 > 0 && $current_net_kgs2 > 0) ? number_format($current_total_amount2 / $current_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right;">
	<?php 
		print ($current_total_sold2 > 0 && $current_offering_bag2 > 0) ? number_format(($current_total_sold2 * 100)/$current_offering_bag2,2,'.','') : '0.00'; 
	?>
	</th>
</tr>	
</tfoot>
</table>

<br />

</div>
</div>
<?php }else if($report_no=='10'){ ?>
<?php $current_time = date("Y-m-d H:i:s");  ?>
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
    size:A4 portrait;
    margin-left: 70px;
    margin-right: 100px;

	@top-right {
		content: "Print-Date: <?php echo date('d-m-Y', strtotime($current_time)); ?> | Page " counter(page) " of " counter(pages);
		font-size: 15px;
		font-family: Arial, sans-serif;
		color: #333;
	}
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

    .page-break {
        break-before: page;
    }
}
</style>
<div class="row">
    <div class="form-group col-sm-12 text-right no-print">
        &nbsp; <a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
    </div>
</div>

<br />

<?php
// Note: Assuming $company_id, $sale_no, $auction_center, $season, $website_title, $sale_data are defined globally or passed in.

// get html value
$row_limit_pp = 45;

// --- START: MODIFIED LOGIC FOR COMPANY/GARDEN FILTERING ---

$garden_where_clause = '';

if (!empty($company_id)) {
    // 1. If company_id is provided, get all gardens under that company
    $this->db->select('id');
    $this->db->from('garden_and_company_setup');
    $this->db->where('company_name', $company_id);
    $this->db->where('soft_delete', 1);
    $garden_query_result = $this->db->get()->result_array();
    
    $garden_ids_for_company = array_column($garden_query_result, 'id');
    
    if (!empty($garden_ids_for_company)) {
        $garden_where_clause = "invoice_entry.garden_id IN ('" . implode("','", $garden_ids_for_company) . "')";
    } else {
        $garden_where_clause = "1 = 0"; // No gardens found, force no results
    }
} else if (!empty($garden_id)) {
    // 2. If only garden_id is provided
    $garden_where_clause = "invoice_entry.garden_id = '{$garden_id}'";
} else {
    // 3. Fallback to ensure the report doesn't run wide if no filter is set
    $garden_where_clause = "1 = 0"; 
}

// Fetch the list of unique gardens based on the selection criteria
$this->db->select("
    invoice_entry.garden_id,
    garden_and_company_setup.garden_name
");
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.remarks !=', 'W/D');
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where($garden_where_clause);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$garden_groups = $this->db->get()->result_array();

if(count($garden_groups) > 0){
    
    $garden_counter = 0;
    $total_gardens = count($garden_groups);
    
    // Outer loop to process data for each garden
    foreach ($garden_groups as $garden_group) {
        $garden_counter++;
        $current_garden_id = $garden_group['garden_id'];
        $current_garden_name = $garden_group['garden_name'];
        
        // Main data query - run for the current garden ID
        $this->db->select("
            invoice_entry.lot_no, 
            invoice_entry.old_season, 
            invoice_entry.w_inv_no, 
            invoice_entry.chest_number, 
            invoice_entry.warehouse_charge, 
            invoice_entry.insp_sample, 
            invoice_entry.brokerage_commission, 
            invoice_entry.tea_cess, 
            invoice_entry.valuation,
            CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
            invoice_entry.chest_number AS total_pkgs,
            invoice_entry.net_kgs AS total_kgs
        "); 
        $this->db->from('invoice_entry'); 
        $this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
        $this->db->where('invoice_entry.season', $season);
        $this->db->where('invoice_entry.auction_center', $auction_center);
        $this->db->where('invoice_entry.sale_no', $sale_no);
        $this->db->where('invoice_entry.garden_id', $current_garden_id);
        $this->db->where('invoice_entry.remarks !=', 'W/D');
        $this->db->where('invoice_entry.soft_delete', 1);
        $this->db->group_by('invoice_entry.id');
        $this->db->order_by('invoice_entry.lot_no ASC');
        $query = $this->db->get();
        $query3 = $query->result_array();
        
        $total_records = count($query3);
        
        if($total_records > 0){
            // Reset pagination variables for this garden
            $sl = 1;
            $total_kgs = 0;
            $total_pkgs = 0;
            $warehouse_charge = 0;
            $insp_sample = 0;
            $brokerage_commission = 0;
            $tea_cess = 0;
            
            $row_start = 1;
            $row_end = $row_limit_pp;
            $row_col = 1;
            $pp_limit = $row_end * $row_col;
            $upper_limit = $row_start;
            $lower_limit = $row_end;
            $i = 1;
            $spp_limit = 1;
            $page = 0;

            // get valuation query (the report data loop)
            foreach($query3 as $row2){

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
                    <th colspan="8" style="text-align:left; border:0px;">
                        <span style="text-transform: uppercase; font-size:20px; text-align:center; font-weight:bold; font-family:courier;">
                            <?php print $website_title; ?>
                        </span>
                    </th>
                    <!--<td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>-->
                </tr>
                <tr>
                    <td colspan="4" class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> Sale No. <?php print $sale_no; ?> of <?php print $auction_center; ?> <?php print $season; ?></td>
                    
                    <td colspan="5" class="text-right" style="font-family: verdana; border:0px; font-size:12px;"> Sale Date : <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
                </tr>
                <tr>
                    <th colspan="9" class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> Garden : <?php print $current_garden_name; ?></th>
                </tr>
                <tr>
                    <td style="text-align:center; border:1px solid;">Lot No</td>
                    <td style="text-align:center; border:1px solid;">Invoice</td>
                    <td style="text-align:left; border:1px solid;">Grade</td>
                    <td style="text-align:right; border:1px solid;">Pkgs</td>
                    <td style="text-align:right; border:1px solid;">Total Kg.</td>
                    <td style="text-align:right; border:1px solid;">Value</td>
                    <td style="text-align:center; border:1px solid;">O/S</td>
                </tr>
            </thead>
            <tbody>
<?php } //end ?>

                <tr>
                    <td style="text-align:center; border:none;">
                        <?php print $row2['lot_no']; ?>
                    </td>
                    <td style="text-align:center; border:none; text-transform:uppercase;">
                        <?php print $row2['w_inv_no']; ?>
                    </td>
                    <td style="text-align:left; border:none; text-transform:uppercase;">
                        <?php print $row2['grade_name']; ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print $row2['total_pkgs']; ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print number_format($row2['total_kgs'],2,'.',''); ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print number_format($row2['valuation'],2,'.',''); ?>
                    </td>
                    <td style="text-align:center; border:none;">
                        <?php print $row2['old_season']; ?>
                    </td>
                </tr>

<?php

                $total_pkgs += $row2['total_pkgs'];
                $total_kgs += $row2['total_kgs'];
                $warehouse_charge = $row2['warehouse_charge'];
                $insp_sample = $row2['insp_sample'];
                $brokerage_commission = $row2['brokerage_commission'];
                $tea_cess = $row2['tea_cess'];

                // limit of close table / div
                if($i==$lower_limit || $i==$total_records){ 
                    $upper_limit = $lower_limit + $row_start;
?>
                <tr>
                    <td style="text-align:right;" colspan="3"> Grand Total: </td>
                    <td style="text-align:right;">
                        <?php echo $total_pkgs; ?>
                    </td>
                    <td style="text-align:right;">
                        <?php echo number_format($total_kgs,2,'.',''); ?>
                    </td>
                    <td style="text-align:right;"></td>
                    <td style="text-align:right;"></td>
                </tr>
            </tbody>
        </table>
    </div> <!-- /col -->
<?php } ?>

<?php 
                if($i==$epp_limit || $i==$total_records){ 
?>
</div> <!-- /row -->
<?php
                    $spp_limit = $epp_limit + 1;
                }
?>

<?php
                // post assign value 
                $i++;
            } // end foreach loop $query3 (data records for current garden)
            
            // Add footer for this garden
            ?>
            <div class="row">
                <div class="col-sm-12" style="margin-top:20px;">
                    <i style="text-transform: uppercase; font-size:10px;">  www.unitybrokersltd.com — Developed by @ Jamuna Tech </i>
                </div>
            </div>
            
            <?php
            // Add page break if there are more gardens to print
            if($garden_counter < $total_gardens){
                echo '<div class="page-break"></div>';
            }
            
        } // end if (data found for current garden)
        
    } // end foreach loop $garden_groups (garden groups)

// end of if condition (overall garden groups found)
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
// --- END: MODIFIED LOGIC ---
?>
<?php }else if($report_no=='11'){ ?>
<style>
    @media print {
        @page {
            size: legal portrait;
            margin: 0.4in 0.2in; /* Added slight padding to safe-zone print boundaries */
        }
    }
    /* Added styles to ensure columns stay inline and don't wrap unexpectedly */
    .tea-report-col {
        width: 33.3333%; 
        float: left; 
        padding-left: 3px; 
        padding-right: 3px; 
        box-sizing: border-box;
    }
    .tea-report-table {
        width: 100%; 
        table-layout: fixed; 
        border-collapse: collapse;
    }
</style>

<div class="row">
    <div class="form-group col-sm-12 text-right no-print">
        <a class="btn btn-default" href="<?php echo site_url('MY_Controller/all_garden_avg_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no); ?>" target="_blank">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span> All Garden Avg
        </a>
        &nbsp;
        <a class="btn btn-default" href="#" onClick="printDiv('printableArea')">
            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
        </a>
    </div>
</div> 

<?php
// FIRST CHECK: Does the searched sale number itself have data?
$check_searched_sql = "SELECT COUNT(*) as total FROM all_garden_avg 
              WHERE season='".$season."' 
              AND sale_no = '".$sale_no."' 
              AND auction_center = '".$auction_center."' 
              AND soft_delete != 0 
              AND category != 'GREEN TEA'";

$check_searched_query = $this->db->query($check_searched_sql);
$check_searched_result = $check_searched_query->row();

// SECOND CHECK: Does data exist from sale 1 to searched sale no?
$check_range_sql = "SELECT COUNT(*) as total FROM all_garden_avg 
              WHERE season='".$season."' 
              AND sale_no <= '".$sale_no."' 
              AND auction_center = '".$auction_center."' 
              AND soft_delete != 0 
              AND category != 'GREEN TEA'";

$check_range_query = $this->db->query($check_range_sql);
$check_range_result = $check_range_query->row();

// Only proceed if BOTH conditions are met:
// 1. Searched sale number has data
// 2. There is data in the range (1 to searched sale no)
if($check_searched_result->total > 0 && $check_range_result->total > 0) {

    // Create temporary table - All Tea Estates Report
    $this->db->query("DROP TABLE IF EXISTS `all_tea_estates_report`");

    // Create all_tea_estates_report - Keep the <= logic for 1 to sale_no
    $this->db->query("CREATE TABLE `all_tea_estates_report` (SELECT 
        all_garden_avg.id, 
        all_garden_avg.season, 
        all_garden_avg.auction_center, 
        all_garden_avg.sale_no, 
        all_garden_avg.brokers_id, 
        all_garden_avg.brokers_name, 
        all_garden_avg.category, 
        all_garden_avg.garden_id, 
        all_garden_avg.garden_name, 
        garden_and_company_setup.main_garden_id, 
        garden_and_company_setup.main_garden,
        ROUND(all_garden_avg.total_kgs, 2) AS total_kgs,
        ROUND(all_garden_avg.avg_price, 2) AS avg_price,
        (ROUND(all_garden_avg.total_kgs, 2) * ROUND(all_garden_avg.avg_price, 2)) total_amount
    FROM all_garden_avg 
    LEFT JOIN garden_and_company_setup ON (all_garden_avg.garden_id = garden_and_company_setup.id) 
    WHERE all_garden_avg.season='".$season."' 
    AND all_garden_avg.sale_no <= '".$sale_no."' 
    AND all_garden_avg.auction_center = '".$auction_center."' 
    AND all_garden_avg.soft_delete != 0) ");

    $sl = 1;
    $row_limit_pp = 60;

    // query
    $sql = "SELECT 
        id, 
        category, 
        sale_no, 
        main_garden_id, 
        main_garden, 
        SUM(IFNULL(total_kgs, 0)) AS total_kgs,  
        SUM(IFNULL(total_amount, 0)) AS total_amount,
        (SUM(IFNULL(total_amount, 0)) / SUM(IFNULL(total_kgs, 0))) AS total_average
    FROM 
        all_tea_estates_report
    WHERE category != 'GREEN TEA'
    GROUP BY 
        main_garden_id, 
        category
    ORDER BY 
        total_average DESC";

    $query = $this->db->query($sql);
    $fetch_list = $query->result_array();

    // Initialize grand totals
    $grand_total_kgs = 0;
    $grand_total_amount = 0;

    if(count($fetch_list) > 0){

    // manipulate row section
    $row_start = 1;
    $row_end = $row_limit_pp;
    $row_col = 3;
    $pp_limit = $row_end * $row_col;
    $upper_limit = $row_start;
    $lower_limit = $row_end;
    $total_records = count($fetch_list);
    $i = 1;
    $spp_limit = 1;
    $page = 0;

    // get valuation query
    foreach($fetch_list as $fetch){

    // Calculate grand totals
    $grand_total_kgs += $fetch['total_kgs'];
    $grand_total_amount += $fetch['total_amount'];

    if($i==$spp_limit){ 
    $epp_limit = $spp_limit + ($pp_limit - 1);
    $page += 1;
    ?>

    <br />
	
    <div class="row">
    <div class="col-sm-12">
    <table style="width:100%;">
        <thead>
            <tr>
                <th style="font-weight:bold; text-transform: uppercase; text-align:center; font-size:15px; position: relative;">
                    <?php print $website_title; ?>
                    <span style="position: absolute; right: 0; top: 0; font-weight: normal; font-size: 13px;">
                        Page <?php print $page; ?>
                    </span>
                </th>
            </tr>
            <tr>
                <td style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">ALL TEA ESTATES AVERAGES Sale No. 1 TO <?php print $sale_no; ?> SEASON <?php print $season; ?></td>
            </tr>
            <tr>
                <td style="text-align:center; font-size:12px; color: #999090;">
                    (TEAS OF ANY GARDEN MANUFACTURED AT OTHER FACTORIES HAS BEEN INCLUDED IN ORIGINAL GARDEN NAME)
                </td>
            </tr>
        </thead>
    </table>
    </div> </div> <br />

    <div class="row" style="margin-left: -5px; margin-right: -5px;">
    <?php } // if ?>

    <?php
    // limit of show heading    
    if($i==$upper_limit){
    $lower_limit = $upper_limit + ($row_end - 1);   
    ?>  
    <div class="tea-report-col">
    <table class="tea-report-table">
        <thead>
            <tr>
                <th style="text-align:center; padding:1px; font-size:10px; width: 8%;">SL</th>
                <th style="text-align:left; padding:1px; font-size:10px; width: 40%;">GARDEN NAME</th>
                <th style="text-align:right; padding:1px; font-size:10px; width: 32%;">KGS</th>
                <th style="text-align:center; padding:1px; font-size:10px; width: 20%;">AVG/TK</th>
            </tr>
        </thead>
        <tbody>
    <?php } //end ?>

        <tr>
            <td style="text-align:center; font-size:10px; padding:2px 1px;"><?php echo $sl++; ?></td> 
            <td style="text-align:left; font-size:10px; padding:2px 1px; text-transform:uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo $fetch['main_garden']; ?>">
                <?php echo substr($fetch['main_garden'], 0, 14); ?><?php echo $fetch['category']=='GREEN TEA' ? ' - '.$fetch['category'] : ''; ?>
            </td>
            <td style="text-align:right; font-size:10px; padding:2px 1px; text-transform:uppercase; white-space: nowrap; overflow: hidden;">
                <?php echo number_format($fetch['total_kgs'],1,'.',''); ?>
            </td>
            <td style="text-align:center; font-size:10px; padding:2px 1px; text-transform:uppercase; white-space: nowrap; overflow: hidden;">
                <?php echo number_format($fetch['total_average'],2,'.',''); ?>
            </td>
        </tr>

    <?php
    // Close table
    if($i==$lower_limit || $i==$total_records){ 
    $upper_limit = $lower_limit + 1;

    // Check if this is the LAST record (end of all data)
    if($i == $total_records){
        // Calculate grand total average
        $grand_total_avg = 0;
        if($grand_total_kgs > 0) {
            $grand_total_avg = $grand_total_amount / $grand_total_kgs;
        }
    ?>

    <tr style="background-color:#f9f9f9;">
        <td style="text-align:left; font-size:10px; padding:5px 0px; font-weight:bold; white-space: nowrap;" colspan="2">
            TOTAL&nbsp;&nbsp;CTC:
        </td>
        <td style="text-align:right; font-size:10px; padding:5px 1px; font-weight:bold; white-space: nowrap;">
            <?php echo number_format($grand_total_kgs, 1, '.', ','); ?>
        </td>
        <td style="text-align:center; font-size:10px; padding:5px 1px; font-weight:bold; white-space: nowrap;">
            <?php echo number_format($grand_total_avg, 2, '.', ','); ?>
        </td>
    </tr>

    <?php } ?>

        </tbody>
    </table>
    </div> <?php } ?>

    <?php if($i==$epp_limit || $i==$total_records){ 
    $spp_limit = $epp_limit + 1;
    ?>
    </div> <?php
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
    
} else {
    // No data found for the specific sale number
    print "<div id=\"page\"><h1 style=\"color: #444;
    background-color: transparent;
    border-bottom: 1px solid #D0D0D0;
    font-size: 19px;
    font-weight: normal;
    margin: 0 0 14px 0;
    padding: 14px 15px 10px 15px;\">No Data Found</h1> 
    <p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found for Sale No. " . $sale_no . " in Season " . $season . ".</b></p></div>";
}
?>

<div class="row">
    <div class="col-sm-12 text-center" style="margin-top:20px;">
        <i style="text-transform: uppercase; font-size:12px; font-weight:bold;">  www.unitybrokersltd.com — Developed by @ Jamuna Tech </i>
    </div>
</div>
<?php }else if($report_no=='12'){ ?>
<style>
.table{
	font-family:verdana;
	width: 100% !important;
}
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

@media print {
	@page {
		size: A4 landscape;
		/* Top-right corner: Page X */
		@top-right {
			content: counter(page) " of " counter(pages);
			font-size: 18px;
		}
	}
}
</style>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm" id="table" style="margin-bottom:10px;">
			<thead>
				<tr>
					<td style="text-transform: uppercase; text-align:center; border:0;" colspan="2">
						<b style="font-size:15px;"><?php print $website_title; ?>, CHITTAGONG</b><br>
						<span style="font-size:13px;">Garden Average for Sale No. <?php print $sale_no; ?> held on <?php print date("F j, Y", strtotime($sale_data['sale_date'])); ?></span>
					</td>
				</tr>
				<tr>
					<td style="text-transform: uppercase; text-align:left; border:0;">
						<span style="font-size:13px;">Season <?php print $season; ?></span>
					</td>

					<td style="text-transform: uppercase; text-align:right; border:0;">
						<span style="font-size:13px;"><?php print date("F j, Y"); ?></span>
					</td>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table" style="text-transform: uppercase;">
<thead>
	<tr>
		<th rowspan="2" style="text-align:center; letter-spacing: 20px; border:1px solid; vertical-align: middle;">GARDEN</th>
		<th colspan="4" style="border:1px solid; text-align:center;">LEAF</th>
		<th colspan="4" style="border:1px solid; text-align:center;">DUST</th>
		<th colspan="4" style="border:1px solid; text-align:center;">TOTAL</th>
		<th colspan="4" style="border:1px solid; text-align:center;">UP TO DATE</th>
	</tr>

	<tr>
		<th style="border:1px solid; text-align:center;">Pkgs</th>
		<th style="border:1px solid; text-align:center;">Kgs</th>
		<th style="border:1px solid; text-align:center;">Amount</th>
		<th style="border:1px solid; text-align:center;">Avg</th>

		<th style="border:1px solid; text-align:center;">Pkgs</th>
		<th style="border:1px solid; text-align:center;">Kgs</th>
		<th style="border:1px solid; text-align:center;">Amount</th>
		<th style="border:1px solid; text-align:center;">Avg</th>

		<th style="border:1px solid; text-align:center;">Pkgs</th>
		<th style="border:1px solid; text-align:center;">Kgs</th>
		<th style="border:1px solid; text-align:center;">Amount</th>
		<th style="border:1px solid; text-align:center;">Avg</th>

		<th style="border:1px solid; text-align:center;">Pkgs</th>
		<th style="border:1px solid; text-align:center;">Kgs</th>
		<th style="border:1px solid; text-align:center;">Amount</th>
		<th style="border:1px solid; text-align:center;">Avg</th>
	</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_avg_report`");

// create garden_sales_avg_report
$this->db->query("CREATE TABLE `garden_sales_avg_report` (SELECT 

	invoice_entry.id, 
	invoice_entry.sale_no, 
	invoice_entry.lot_no, 
	invoice_entry.auction_center, 
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	invoice_entry.supplement_tea, 
	invoice_entry.category, 
	invoice_entry.account_concerned, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount, 
	garden_and_company_setup.garden_name,
	garden_and_company_setup.main_garden_id,
	garden_and_company_setup.main_garden,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_bag END,0) 
	) AS internal_bags,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_bag END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_bag END,0) 
	) AS export_bags,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS internal_kgs,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_total_kgs END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_total_kgs END,0) 
	) AS export_kgs,
	
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Internal' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Internal' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Internal' THEN sale_entry.buyer3_amount END,0) 
	) AS internal_amount,
	(
		IFNULL(CASE WHEN sale_entry.buyer_type1='Export' THEN sale_entry.buyer1_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type2='Export' THEN sale_entry.buyer2_amount END,0) + 
		IFNULL(CASE WHEN sale_entry.buyer_type3='Export' THEN sale_entry.buyer3_amount END,0) 
	) AS export_amount

FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_no <= '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id
) ");

// view data
$current_total_sold2 =0;
$current_net_kgs2 =0;
$current_total_amount2 =0;

$leaf_current_total_sold2=0;
$dust_current_total_sold2=0;
$leaf_current_net_kgs2=0;
$dust_current_net_kgs2=0;
$leaf_current_total_amount2=0;
$dust_current_total_amount2=0;

$current_offering_bag2 =0;
$upto_total_sold2 =0;
$upto_net_kgs2 =0;
$upto_total_amount2 =0;
$upto_offering_bag2 =0;
$internal_bags2 =0;
$export_bags2 =0;
$internal_kgs2 =0;
$export_kgs2 =0;
$internal_amount2 =0;
$export_amount2 =0;
$green_current_total_sold2 = 0;
$green_upto_total_sold2 = 0;
$green_current_net_kgs2 = 0;
$green_current_total_amount2 = 0;
$green_upto_net_kgs2 = 0;
$green_upto_total_amount2 = 0;

// old_season breakdown query
$this->db->select("old_season,category"); 
$this->db->from('garden_sales_avg_report');
$this->db->group_by('old_season');
$this->db->order_by('id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<th colspan="7" class="text-danger" style="border:0px; font-size:13px !important; font-family:verdana;">
		SEASON: <?php print $row2['old_season']; ?>
	</th>
</tr>

<?php
$current_total_sold =0;
$current_net_kgs =0;
$current_total_amount =0;

$leaf_current_total_sold =0;
$dust_current_total_sold =0;
$leaf_current_net_kgs =0;
$dust_current_net_kgs =0;
$leaf_current_total_amount =0;
$dust_current_total_amount =0;

$current_offering_bag =0;
$upto_total_sold =0;
$upto_net_kgs =0;
$upto_total_amount =0;
$upto_offering_bag =0;
$internal_bags =0;
$export_bags =0;
$internal_kgs =0;
$export_kgs =0;
$internal_amount =0;
$export_amount =0;
$green_current_total_sold = 0;
$green_upto_total_sold = 0;
$green_current_net_kgs = 0;
$green_current_total_amount = 0;
$green_upto_net_kgs = 0;
$green_upto_total_amount = 0;

//query
$this->db->select("
	gs.garden_id, 
	gs.main_garden_id, 
	gs.main_garden, 

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Leaf' THEN gs.total_sold END) AS leaf_current_total_sold,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Dust' THEN gs.total_sold END) AS dust_current_total_sold,

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Leaf' THEN gs.net_kgs END) AS leaf_current_net_kgs,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Dust' THEN gs.net_kgs END) AS dust_current_net_kgs,

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Leaf' THEN gs.total_amount END) AS leaf_current_total_amount,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Dust' THEN gs.total_amount END) AS dust_current_total_amount,

	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.export_bags) AS export_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_amount) AS export_amount,
	gs.garden_name, 
	inv_offering.current_offering_bag,
	inv_offering.upto_offering_bag,
	gs.old_season
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(chest_number) AS upto_offering_bag FROM invoice_entry WHERE season='".$season."' AND sale_date <= '".$sale_data['sale_date']."' AND old_season='".$row2['old_season']."' AND category='CTC' AND account_concerned='No' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY garden_id) inv_offering", "gs.garden_id=inv_offering.garden_id", "left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->where('gs.account_concerned','No');
$this->db->where('gs.category','CTC');
$this->db->having('upto_total_sold != 0');
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){

	$current_avg = ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? $row1['current_total_amount'] / $row1['current_net_kgs'] : '0'; 

	$leaf_avg = ($row1['leaf_current_total_amount'] > 0 && $row1['leaf_current_net_kgs'] > 0) ? $row1['leaf_current_total_amount'] / $row1['leaf_current_net_kgs'] : '0'; 

	$dust_avg = ($row1['dust_current_total_amount'] > 0 && $row1['dust_current_net_kgs'] > 0) ? $row1['dust_current_total_amount'] / $row1['dust_current_net_kgs'] : '0'; 
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px;">
		<?php print $row1['garden_name']; ?>
	</td>
	<!-- leaf -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['leaf_current_total_sold'] > 0) ? number_format($row1['leaf_current_total_sold'],0,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['leaf_current_net_kgs'] > 0) ? number_format($row1['leaf_current_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['leaf_current_total_amount'] > 0) ? number_format($row1['leaf_current_total_amount'],2,'.',',') : '0'; ?>
	</td>

	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($leaf_avg,2,'.',','); 
	?>
	</td>

	<!-- Dust -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['dust_current_total_sold'] > 0) ? number_format($row1['dust_current_total_sold'],0,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['dust_current_net_kgs'] > 0) ? number_format($row1['dust_current_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['dust_current_total_amount'] > 0) ? number_format($row1['dust_current_total_amount'],2,'.',',') : '0'; ?>
	</td>

	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($dust_avg,2,'.',','); 
	?>
	</td>
	
	<!-- Total -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_total_sold'] > 0) ? number_format($row1['current_total_sold'],0,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_total_amount'] > 0) ? number_format($row1['current_total_amount'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($current_avg,2,'.',','); 
	?>
	</td>

	<!-- UP TO DATE -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_total_sold'] > 0) ? number_format($row1['upto_total_sold'],0,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_net_kgs'] > 0) ? number_format($row1['upto_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_total_amount'] > 0) ? number_format($row1['upto_total_amount'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($row1['upto_total_amount'] > 0 && $row1['upto_net_kgs'] > 0) ? number_format($row1['upto_total_amount'] / $row1['upto_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
</tr>

<?php
$current_total_sold +=$row1['current_total_sold'];
$current_net_kgs +=$row1['current_net_kgs'];
$current_total_amount +=$row1['current_total_amount'];

$leaf_current_total_sold += $row1['leaf_current_total_sold'];
$dust_current_total_sold += $row1['dust_current_total_sold'];
$leaf_current_net_kgs += $row1['leaf_current_net_kgs'];
$dust_current_net_kgs += $row1['dust_current_net_kgs'];
$leaf_current_total_amount += $row1['leaf_current_total_amount'];
$dust_current_total_amount += $row1['dust_current_total_amount'];

$current_offering_bag +=$row1['current_offering_bag'];
$upto_total_sold +=$row1['upto_total_sold'];
$upto_net_kgs +=$row1['upto_net_kgs'];
$upto_total_amount +=$row1['upto_total_amount'];
$upto_offering_bag +=$row1['upto_offering_bag'];
$internal_bags +=$row1['internal_bags'];
$export_bags +=$row1['export_bags'];
$internal_kgs +=$row1['internal_kgs'];
$export_kgs +=$row1['export_kgs'];
$internal_amount +=$row1['internal_amount'];
$export_amount +=$row1['export_amount'];

} // 2nd loop
?>
<tr>
	<th colspan="1" style="text-align:right; border:1px solid;">Sub Total: </th>
	<!-- Leaf -->
	<th style="text-align:right; border:1px solid;"><?php print number_format($leaf_current_total_sold,0,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($leaf_current_net_kgs,1,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($leaf_current_total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
		<?php 
			print ($leaf_current_total_amount > 0 && $leaf_current_net_kgs > 0) ? number_format($leaf_current_total_amount / $leaf_current_net_kgs,2,'.',',') : '0.00'; 
		?>
	</th>
	<!-- Dust -->
	<th style="text-align:right; border:1px solid;"><?php print number_format($dust_current_total_sold,0,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($dust_current_net_kgs,1,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($dust_current_total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
		<?php 
			print ($dust_current_total_amount > 0 && $dust_current_net_kgs > 0) ? number_format($dust_current_total_amount / $dust_current_net_kgs,2,'.',',') : '0.00'; 
		?>
	</th>
	<!-- Total -->
	<th style="text-align:right; border:1px solid;"><?php print number_format($current_total_sold,0,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($current_net_kgs,1,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($current_total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
	<?php 
		print ($current_total_amount > 0 && $current_net_kgs > 0) ? number_format($current_total_amount / $current_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<!-- UP TO DATE -->
	<th style="text-align:right; border:1px solid;"><?php print number_format($upto_total_sold,0,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($upto_net_kgs,1,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($upto_total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
	<?php 
		print ($upto_total_amount > 0 && $upto_net_kgs > 0) ? number_format($upto_total_amount / $upto_net_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
</tr>

<!-- GREEN TEA -->
<?php
//query - UPDATED to include internal/export breakdown for Green Tea
$this->db->select("
	gs.garden_id, 
	gs.main_garden_id, 
	gs.main_garden, 

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,

	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	
	-- Internal/Export breakdown for Green Tea
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.export_bags) AS export_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_amount) AS export_amount,
	
	gs.garden_name, 
	inv_offering.current_offering_bag,
	inv_offering.upto_offering_bag,
	gs.old_season,
	CASE 
        WHEN gs.supplement_tea = 'Green Tea' THEN ' - GREEN'
        WHEN gs.supplement_tea = 'Orthodox' THEN ' - ORTH'
        ELSE '' 
    END AS supplement
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(chest_number) AS upto_offering_bag FROM invoice_entry WHERE season='".$season."' AND sale_date <= '".$sale_data['sale_date']."' AND old_season='".$row2['old_season']."' AND category !='CTC' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY garden_id) inv_offering", "gs.garden_id=inv_offering.garden_id", "left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->where('gs.category !=', 'CTC');
$this->db->having('upto_total_sold != 0');
$this->db->group_by('gs.supplement_tea, gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
?>
<tr>
      <th class="text-success" style="text-align:left; text-transform:uppercase; border:0px;">
              GREEN TEA
      </th>
</tr>
<?php
}
?>

<?php
foreach($result1 as $row1){

	$current_avg = ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? $row1['current_total_amount'] / $row1['current_net_kgs'] : '0'; 

	// Accumulate Green Tea values - UPDATED to include internal/export
   	$green_current_total_sold += $row1['current_total_sold'];
    $green_current_net_kgs += $row1['current_net_kgs'];
    $green_current_total_amount += $row1['current_total_amount'];
    $green_upto_total_sold += $row1['upto_total_sold'];
    $green_upto_net_kgs += $row1['upto_net_kgs'];
    $green_upto_total_amount += $row1['upto_total_amount'];
    
    // Accumulate internal/export for Green Tea
    $internal_bags += $row1['internal_bags'];
    $export_bags += $row1['export_bags'];
    $internal_kgs += $row1['internal_kgs'];
    $export_kgs += $row1['export_kgs'];
    $internal_amount += $row1['internal_amount'];
    $export_amount += $row1['export_amount'];
?>

<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px;">
		<?php print $row1['garden_name']; ?> <?php print $row1['supplement']; ?>
	</td>
	<!-- leaf -->
	<td style="text-align:right; border:0px;">
		0.00
	</td>
	
	<td style="text-align:right; border:0px;">
		0.00
	</td>
	
	<td style="text-align:right; border:0px;">
		0.00
	</td>

	<td style="text-align:right; border:0px;">
		0.00
	</td>

	<!-- Dust -->
	<td style="text-align:right; border:0px;">
		0.00
	</td>
	
	<td style="text-align:right; border:0px;">
		0.00
	</td>
	
	<td style="text-align:right; border:0px;">
		0.00
	</td>

	<td style="text-align:right; border:0px;">
		0.00
	</td>
	
	<!-- Total -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_total_sold'] > 0) ? number_format($row1['current_total_sold'],0,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_total_amount'] > 0) ? number_format($row1['current_total_amount'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($current_avg,2,'.',','); 
	?>
	</td>

	<!-- UP TO DATE -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_total_sold'] > 0) ? number_format($row1['upto_total_sold'],0,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_net_kgs'] > 0) ? number_format($row1['upto_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_total_amount'] > 0) ? number_format($row1['upto_total_amount'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($row1['upto_total_amount'] > 0 && $row1['upto_net_kgs'] > 0) ? number_format($row1['upto_total_amount'] / $row1['upto_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
</tr>

<?php } ?>

<!-- Account Concerned -->
<?php
//query
$this->db->select("
	gs.garden_id, 
	gs.main_garden_id, 
	gs.main_garden, 

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_sold END) AS current_total_sold,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.net_kgs END) AS current_net_kgs,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' THEN gs.total_amount END) AS current_total_amount,

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Leaf' THEN gs.total_sold END) AS leaf_current_total_sold,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Dust' THEN gs.total_sold END) AS dust_current_total_sold,

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Leaf' THEN gs.net_kgs END) AS leaf_current_net_kgs,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Dust' THEN gs.net_kgs END) AS dust_current_net_kgs,

	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Leaf' THEN gs.total_amount END) AS leaf_current_total_amount,
	sum(CASE WHEN gs.auction_center='".$auction_center."' AND gs.sale_no='".$sale_no."' AND gs.supplement_tea='Dust' THEN gs.total_amount END) AS dust_current_total_amount,

	sum(gs.total_sold) AS upto_total_sold, 
	sum(gs.net_kgs) AS upto_net_kgs, 
	sum(gs.total_amount) AS upto_total_amount,
	sum(gs.internal_bags) AS internal_bags,
	sum(gs.export_bags) AS export_bags,
	sum(gs.internal_kgs) AS internal_kgs,
	sum(gs.export_kgs) AS export_kgs,
	sum(gs.internal_amount) AS internal_amount,
	sum(gs.export_amount) AS export_amount,
	gs.garden_name, 
	inv_offering.current_offering_bag,
	inv_offering.upto_offering_bag,
	gs.old_season
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(chest_number) AS upto_offering_bag FROM invoice_entry WHERE season='".$season."' AND sale_date <= '".$sale_data['sale_date']."' AND old_season='".$row2['old_season']."' AND account_concerned ='Yes' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY garden_id) inv_offering", "gs.garden_id=inv_offering.garden_id", "left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->where('gs.account_concerned =','Yes');
$this->db->having('upto_total_sold != 0');
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
$row_count = $query->num_rows();
?>

<?php if($row_count > 0){ ?>
	
<tr>
	<th colspan="17" style="border:0;">Account Concerned</th>
</tr>

<?php } ?>

<?php
foreach($result1 as $row1){

	$current_avg = ($row1['current_total_amount'] > 0 && $row1['current_net_kgs'] > 0) ? $row1['current_total_amount'] / $row1['current_net_kgs'] : '0'; 

	$leaf_avg = ($row1['leaf_current_total_amount'] > 0 && $row1['leaf_current_net_kgs'] > 0) ? $row1['leaf_current_total_amount'] / $row1['leaf_current_net_kgs'] : '0'; 

	$dust_avg = ($row1['dust_current_total_amount'] > 0 && $row1['dust_current_net_kgs'] > 0) ? $row1['dust_current_total_amount'] / $row1['dust_current_net_kgs'] : '0'; 
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px;">
		<?php print $row1['garden_name']; ?>
	</td>
	<!-- leaf -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['leaf_current_total_sold'] > 0) ? number_format($row1['leaf_current_total_sold'],0,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['leaf_current_net_kgs'] > 0) ? number_format($row1['leaf_current_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['leaf_current_total_amount'] > 0) ? number_format($row1['leaf_current_total_amount'],2,'.',',') : '0'; ?>
	</td>

	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($leaf_avg,2,'.',','); 
	?>
	</td>

	<!-- Dust -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['dust_current_total_sold'] > 0) ? number_format($row1['dust_current_total_sold'],0,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['dust_current_net_kgs'] > 0) ? number_format($row1['dust_current_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	
	<td style="text-align:right; border:0px;">
		<?php print ($row1['dust_current_total_amount'] > 0) ? number_format($row1['dust_current_total_amount'],2,'.',',') : '0'; ?>
	</td>

	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($dust_avg,2,'.',','); 
	?>
	</td>
	
	<!-- Total -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_total_sold'] > 0) ? number_format($row1['current_total_sold'],0,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_net_kgs'] > 0) ? number_format($row1['current_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['current_total_amount'] > 0) ? number_format($row1['current_total_amount'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print number_format($current_avg,2,'.',','); 
	?>
	</td>

	<!-- UP TO DATE -->
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_total_sold'] > 0) ? number_format($row1['upto_total_sold'],0,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_net_kgs'] > 0) ? number_format($row1['upto_net_kgs'],1,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
		<?php print ($row1['upto_total_amount'] > 0) ? number_format($row1['upto_total_amount'],2,'.',',') : '0'; ?>
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($row1['upto_total_amount'] > 0 && $row1['upto_net_kgs'] > 0) ? number_format($row1['upto_total_amount'] / $row1['upto_net_kgs'],2,'.',',') : '0'; 
	?>
	</td>
</tr>

<?php } ?>

<?php
$current_total_sold2 +=$current_total_sold;
$current_net_kgs2 +=$current_net_kgs;
$current_total_amount2 +=$current_total_amount;

$leaf_current_total_sold2 += $leaf_current_total_sold;
$dust_current_total_sold2 += $dust_current_total_sold;
$leaf_current_net_kgs2 += $leaf_current_net_kgs;
$dust_current_net_kgs2 += $dust_current_net_kgs;
$leaf_current_total_amount2 += $leaf_current_total_amount;
$dust_current_total_amount2 += $dust_current_total_amount;

$current_offering_bag2 +=$current_offering_bag;
$upto_total_sold2 +=$upto_total_sold;
$upto_net_kgs2 +=$upto_net_kgs;
$upto_total_amount2 +=$upto_total_amount;
$upto_offering_bag2 +=$upto_offering_bag;
$internal_bags2 +=$internal_bags;
$export_bags2 +=$export_bags;
$internal_kgs2 +=$internal_kgs;
$export_kgs2 +=$export_kgs;
$internal_amount2 +=$internal_amount;
$export_amount2 +=$export_amount;
$green_current_total_sold2 += $green_current_total_sold;
$green_current_net_kgs2 += $green_current_net_kgs;
$green_current_total_amount2 += $green_current_total_amount;
$green_upto_total_sold2 += $green_upto_total_sold;
$green_upto_net_kgs2 += $green_upto_net_kgs;
$green_upto_total_amount2 += $green_upto_total_amount;

} // 1st loop
?>

<tr>
	<td colspan="17" style="border:0px;"></td>
</tr>
<tr>
	<th colspan="1" style="text-align:right; border:1px solid;">Grand TOTAL: </th>
	<!-- Leaf -->
	<th style="text-align:right; border:1px solid;"><?php print number_format($leaf_current_total_sold2,0,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($leaf_current_net_kgs2,1,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($leaf_current_total_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
	<?php 
		print ($leaf_current_total_amount2 > 0 && $leaf_current_net_kgs2 > 0) ? number_format($leaf_current_total_amount2 / $leaf_current_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>

	<!-- Dust -->
	<th style="text-align:right; border:1px solid;"><?php print number_format($dust_current_total_sold2,0,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($dust_current_net_kgs2,1,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php print number_format($dust_current_total_amount2,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;">
	<?php 
		print ($dust_current_total_amount2 > 0 && $dust_current_net_kgs2 > 0) ? number_format($dust_current_total_amount2 / $dust_current_net_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>

	<!-- Total -->
	<th style="text-align:right; border:1px solid;">
		<?php print number_format($current_total_sold2 + $green_current_total_sold2,0,'.',','); ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php print number_format($current_net_kgs2 + $green_current_net_kgs2,1,'.',','); ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php print number_format($current_total_amount2 + $green_current_total_amount2,2,'.',','); ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php 
			$total_kgs = $current_net_kgs2 + $green_current_net_kgs2;
			$total_amount = $current_total_amount2 + $green_current_total_amount2;
			print ($total_amount > 0 && $total_kgs > 0) ? number_format($total_amount / $total_kgs,2,'.',',') : '0.00'; 
		?>
	</th>

	<!-- UP TO DATE -->
	<th style="text-align:right; border:1px solid;">
		<?php print number_format($upto_total_sold2 + $green_upto_total_sold2,0,'.',','); ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php print number_format($upto_net_kgs2 + $green_upto_net_kgs2,1,'.',','); ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php print number_format($upto_total_amount2 + $green_upto_total_amount2,2,'.',','); ?>
	</th>
	<th style="text-align:right; border:1px solid;">
		<?php 
			$upto_total_kgs = $upto_net_kgs2 + $green_upto_net_kgs2;
			$upto_total_amt = $upto_total_amount2 + $green_upto_total_amount2;
			print ($upto_total_amt > 0 && $upto_total_kgs > 0) ? number_format($upto_total_amt / $upto_total_kgs,2,'.',',') : '0.00'; 
		?>
	</th>
</tr>	
</tbody>
</table>

<br />

<table id="table" style="margin: 0 auto; width: 40%;">
<tr>
	<th colspan="5" style="text-align:center; text-transform: uppercase;"><u>Buyer Purchase Upto Date Sale:</u></th>
</tr>
<tr>
	<td style="text-align:left; border:0px;">EXPORT</td>
	<td style="text-align:right; border:0px;"><?php print number_format($export_bags2,0,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($export_kgs2,2,'.',','); ?> KGS</td>
	<td style="text-align:right; border:0px;">
		<?php 
			$total_upto_kgs = $upto_net_kgs2 + $green_upto_net_kgs2;
			print $exp_avg = ($export_kgs2 > 0 && $total_upto_kgs > 0) ? number_format(($export_kgs2 * 100)/$total_upto_kgs,2,'.','') : '0.00'; 
		?> %
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($export_amount2 > 0 && $export_kgs2 > 0) ? number_format($export_amount2 / $export_kgs2,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px;">INTERNAL</td>
	<td style="text-align:right; border:0px;"><?php print number_format($internal_bags2,0,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php print number_format($internal_kgs2,2,'.',','); ?> KGS</td>
	<td style="text-align:right; border:0px;">
		<?php 
			$total_upto_kgs = $upto_net_kgs2 + $green_upto_net_kgs2;
			print $int_avg = ($internal_kgs2 > 0 && $total_upto_kgs > 0) ? number_format(($internal_kgs2 * 100)/$total_upto_kgs,2,'.','') : '0.00'; 
		?> %
	</td>
	<td style="text-align:right; border:0px;">
	<?php 
		print ($internal_amount2 > 0 && $internal_kgs2 > 0) ? number_format($internal_amount2 / $internal_kgs2,2,'.',',') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<th style="text-align:left; border-top:1px solid;">TOTAL </th>
	<th style="text-align:right; border-top:1px solid;"><?php print number_format($upto_total_sold2 + $green_upto_total_sold2,0,'.',','); ?></th>
	<th style="text-align:right; border-top:1px solid;"><?php print number_format($upto_net_kgs2 + $green_upto_net_kgs2,1,'.',','); ?> KGS</th>
	<th style="text-align:right; border-top:1px solid;">
	<?php 
		print number_format($int_avg + $exp_avg,2,'.',''); 
	?>
	</th>
	<th style="text-align:right; border-top:1px solid;">
	<?php 
		$total_upto_kgs = $upto_net_kgs2 + $green_upto_net_kgs2;
		$total_upto_amount = $upto_total_amount2 + $green_upto_total_amount2;
		print ($total_upto_amount > 0 && $total_upto_kgs > 0) ? number_format($total_upto_amount / $total_upto_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
</tr>
</table>

<br />

</div>
</div>

<div class="row">
	<div class="col-sm-6 text-left" style="margin-top:20px;">
		<i style="text-transform: uppercase; font-size:12px; font-weight:bold;"> www.unitybrokersltd.com </i>
	</div>
	<div class="col-sm-6 text-right" style="margin-top:20px;">
		<i style="text-transform: uppercase; font-size:12px; font-weight:bold;"> Developed by @ Jamuna Tech </i>
	</div>
</div>

<?php } else if($report_no=='13'){ ?>
<style>
#table tr td {
    border-color:black;
    font-size:14px !important;
    padding:3px;
}
#table tr th{
    border-color:black;
    font-size:14px !important;
    padding:3px;
}
#deduction_div{
    margin-right: 165px;
    float: right;
}

@media print{    
    #deduction_div{
        margin-right: 95px;
        width: 60% !important;
        float: right;
    }
    .page-break {
        page-break-after: always;
    }
}

@page{
    size:legal portrait;
    margin-left: 70px;
    margin-right: 100px;

	@top-right {
        content: counter(page) " of " counter(pages);
        font-style: italic;
		font-size: 20px !important;
    }
}


</style>

<div class="row">
    <div class="form-group col-sm-12 text-right no-print">
        <?php
        $current_season = explode("-",$season);
        $old_season_sbt = ($current_season[0] - 1);
        $old_season_sbt2 = ($current_season[1] - 1);
        $old_season = "$old_season_sbt-$old_season_sbt2";
        $old_season_ext = ($current_season[0] + 1);
        $old_season_ext2 = ($current_season[1] + 1);
        $new_season = "$old_season_ext-$old_season_ext2";
        ?>
        &nbsp; <a class="no-print" href="#" onClick="printDiv('printableArea')">
            <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
        </a>
    </div>
</div>

<br />

<?php
// Get list of all gardens in this sale
$this->db->select('DISTINCT(invoice_entry.garden_id), garden_and_company_setup.garden_name');
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$gardens = $this->db->get()->result_array();

foreach($gardens as $garden) {
    // For each garden, get the sales data
    $this->db->select("
        invoice_entry.lot_no, 
    invoice_entry.old_season, 
    invoice_entry.w_inv_no, 
    invoice_entry.chest_number, 
    invoice_entry.warehouse_charge, 
    invoice_entry.insp_sample, 
    invoice_entry.brokerage_commission, 
    invoice_entry.tea_cess, 
    invoice_entry.valuation,
    CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
    (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_pkgs,
    (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,    
    sale_entry.sold_price,
    sale_entry.complete_price,
    (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS amount,
    (IFNULL(sale_entry.buyer1_complete_amount,0) + IFNULL(sale_entry.buyer2_complete_amount,0) + IFNULL(sale_entry.buyer3_complete_amount,0)) AS compelte_amount
    "); 
    $this->db->from('invoice_entry'); 
    $this->db->join('sale_entry','invoice_entry.id = sale_entry.invoice_id');
    $this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
    $this->db->where('invoice_entry.season', $season);
    $this->db->where('invoice_entry.auction_center', $auction_center);
    $this->db->where('invoice_entry.sale_no', $sale_no);
    $this->db->where('invoice_entry.garden_id', $garden['garden_id']);
    $this->db->where('invoice_entry.soft_delete', 1);
    $this->db->group_by('invoice_entry.id');
    $this->db->order_by('invoice_entry.lot_no ASC');
    $query = $this->db->get();
    $garden_sales = $query->result_array();
    
    if(count($garden_sales) > 0) {
        // Initialize totals for this garden
        $total_pkgs = 0;
        $total_kgs = 0;
        $total_amount = 0;
        $adjustment_total = 0;
        $warehouse_charge = 0;
        $insp_sample = 0;
        $brokerage_commission = 0;
        $tea_cess = 0;
        $warehouse_charge_total = 0;
        
        // Get garden data
        $this->db->select('*');
        $this->db->from('garden_and_company_setup');
        $this->db->where('id', $garden['garden_id']);
        $garden_data = $this->db->get()->row_array();
        
        // Pagination variables
        $row_limit_pp = 40;
        $sl = 1;
        $row_start = 1;
        $row_end = $row_limit_pp;
        $row_col = 1;
        $pp_limit = $row_end * $row_col;
        $upper_limit = $row_start;
        $lower_limit = $row_end;
        $total_records = count($garden_sales);
        $i = 1;
        $spp_limit = 1;
        $page = 0;
        ?>
        
        <div class="page-break" id="printableArea">
            <?php foreach($garden_sales as $row2) {
                $total_bill = $row2['amount'] + ($row2['amount'] * 16 / 100);
                $net_total_bill = ($total_bill + $row2['total_kgs'] * 10 / 100) - $row2['compelte_amount'];
                $adjustment_bill = ($row2['compelte_amount'] > 0) ? $net_total_bill : '0';

                if($i == $spp_limit) {
                    $epp_limit = $spp_limit + ($pp_limit - 1);
                    $page += 1;
                    ?>
                    <div class="row">
                <?php } ?>

                <?php if($i == $upper_limit) {
                    $lower_limit = $upper_limit + ($row_end - 1);
                    ?>
                    <div class="col-sm-12">
                        <table class="table table-sm" id="table">
                            <thead>
                                <tr>
                                    <th colspan="8" style="text-align:left; border:0px;">
                                        <span style="text-transform: uppercase; font-size:20px; text-align:center; font-weight:bold; font-family:courier;">
                                            <?php print $website_title; ?>
                                        </span>
                                    </th>
                                    <td style="text-align:right; border:0px;"><span style="font-size:15px;"></span></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-left" style="font-family: verdana; border:0px; font-size:15px;">
                                        Sale No. <?php print $sale_no; ?> of <?php print $auction_center; ?> <?php print $season; ?><br>
                                    </td>
                                    <td colspan="5" class="text-right" style="font-family: verdana; border:0px; font-size:15px;">
                                        <span style="padding-right:30px;">ACCOUNT SALES</span> 
                                        No.<span style="border-bottom:1px solid #000; display: inline-block; width: 100px;"></span> <br> 
                                    </td>
                                </tr>
                                <tr>
									<th colspan="5" class="text-left" style="font-family: verdana; border:0px; font-size:20px;">
										Garden : <?php echo $garden_data['garden_name']; ?>
									</th>
									<td colspan="4" class="text-right" style="font-family: verdana; border:0px; font-size:15px;">
										Date : <?php echo date("d-m-Y", strtotime($sale_data['sale_date'])); ?>
									</td>
								</tr>

                                <tr>
                                    <th style="text-align:center; border:1px solid;">Lot No</th>
                                    <th style="text-align:center; border:1px solid;">Invoice</th>
                                    <th style="text-align:left; border:1px solid;">Grade</th>
                                    <th style="text-align:right; border:1px solid;">Pkgs</th>
                                    <th style="text-align:right; border:1px solid;">Total Kg.</th>
                                    <th style="text-align:right; border:1px solid;">Value</th>
                                    <th style="text-align:right; border:1px solid;">Price</th>
                                    <th style="text-align:right; border:1px solid;">Amount</th>
                                    <th style="text-align:center; border:1px solid;">O/S</th>
                                </tr>
                            </thead>
                            <tbody>
                <?php } ?>
                
                <tr>
                    <td style="text-align:center; border:none;">
                        <?php print $row2['lot_no']; ?>
                    </td>
                    <td style="text-align:center; border:none; text-transform:uppercase;">
                        <?php print $row2['w_inv_no']; ?>
                    </td>
                    <td style="text-align:left; border:none; text-transform:uppercase;">
                        <?php print $row2['grade_name']; ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print $row2['total_pkgs']; ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print number_format($row2['total_kgs'],2,'.',''); ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print number_format($row2['valuation'],1,'.',''); ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print number_format($row2['sold_price'],1,'.',''); ?>
                    </td>
                    <td style="text-align:right; border:none;">
                        <?php print number_format($row2['amount'],2,'.',','); ?>
                    </td>
                   	<td style="text-align:center; border:none;">
						<?php 
						// Only show old_season if it's different from current season
						echo ($row2['old_season'] != $season) ? $row2['old_season'] : '';
						?>
					</td>
                </tr>
                
                <?php
                // Update totals
                $total_pkgs += $row2['total_pkgs'];
                $total_kgs += $row2['total_kgs'];
                $total_amount += $row2['amount'];
                $adjustment_total += $adjustment_bill;
                $warehouse_charge = $row2['warehouse_charge'];
                $insp_sample = $row2['insp_sample'];
                $brokerage_commission = $row2['brokerage_commission'];
                $tea_cess = $row2['tea_cess'];
				$invoice_warehouse_charge = $row2['total_pkgs'] * $row2['warehouse_charge'];
				$warehouse_charge_total += $invoice_warehouse_charge;
                // Close table if we've reached the limit or end of records
                if($i == $lower_limit || $i == $total_records) {
                    $upper_limit = $lower_limit + $row_start;
                    ?>
                            <tr>
                                <td style="text-align:right;" colspan="3"> Grand Total: </td>
                                <td style="text-align:right;">
                                    <?php echo $total_pkgs; ?>
                                </td>
                                <td style="text-align:right;">
                                    <?php echo number_format($total_kgs,2,'.',''); ?>
                                </td>
                                <td style="text-align:right;"></td>
                                <td style="text-align:right;"></td>
                                <td style="text-align:right;">
                                    <?php echo number_format($total_amount,2,'.',','); ?>
                                </td>
                                <td style="text-align:right;"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if($i == $epp_limit || $i == $total_records) { ?>
                    </div> <!-- Close row div -->
                    <?php
                    $spp_limit = $epp_limit + 1;
                }
                
                $i++;
            } // End foreach garden_sales ?>
            
            <!-- Deductions Section -->
            <div class="row">
                <div class="col-sm-6" id="deduction_div">
                    <table class="table table-sm" id="table">
                        <tr>
                            <td colspan="4" style="text-align:left; border:none;"><b> Deduction : </td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none;"> 
                                Tea Cess 1% 
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                    $total_tea_cess = number_format((($total_amount * $tea_cess)/100),2,'.','');
                                    print number_format($total_tea_cess,2,'.',','); 
                                ?>
                            </td>
                            <td style="text-align:right; border:none;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none;">
                                Were House Charges
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                   
                                    print number_format($warehouse_charge_total,2,'.',','); 
                                ?>
                            </td>
                            <td style="text-align:right; border:none;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none;">
                                Inspection Fee
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                    $total_insp_sample = number_format(($total_pkgs * $insp_sample),2,'.','');
                                    print number_format($total_insp_sample,2,'.',','); 
                                ?>
                            </td>
                            <td style="text-align:right; border:none;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none;">
                                Brokerage
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                    $total_brokerage = number_format((($total_amount * $brokerage_commission)/100),2,'.','');
                                    print number_format($total_brokerage,2,'.',','); 
                                ?>
                            </td>
                            <td style="text-align:right; border:none;"></td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none;">
                                Sales Adjustment
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                    print number_format($adjustment_total,2,'.',','); 
                                ?>
                            </td>
                            <td style="text-align:right; border:none;"></td>
                        </tr>
                        <?php
                        $garden_adjust = 0;    
                        // garden adjust cost
                        $this->db->select("charges_name, charge_tk, particular"); 
                        $this->db->from('garden_adjust_tbl');
                        $this->db->where('season', $season);
                        $this->db->where('auction_center', $auction_center);
                        $this->db->where('sale_no', $sale_no);
                        $this->db->where('garden_id', $garden['garden_id']);
                        $this->db->order_by('id ASC');
                        $query = $this->db->get();
                        foreach($query->result_array() as $cost_row){    
                        ?>
                        <tr>
                            <td style="text-align:right; border:none;">
                                <?php print $cost_row['charges_name']; ?>
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                    print number_format($cost_row['charge_tk'],2,'.',',');
                                ?>
                            </td>
                            <td style="text-align:right; border:none;"></td>
                        </tr>
                        <?php 
                            $garden_adjust += $cost_row['charge_tk'];
                        }
                        ?>
                            
                        <tr style="border-bottom:1px dashed #000;">
                            <td style="text-align:right; border:none;"> 
                                Total Charges Tk.
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;"></td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                    $total_charges = $warehouse_charge_total + $total_insp_sample + $total_brokerage + $total_tea_cess + $garden_adjust + $adjustment_total;
                                    print number_format($total_charges,2,'.',','); 
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:right; border:none;"> 
                                Net. Sales Tk.
                            </td>
                            <td class="text-center" style="font-family: verdana; border:0px;"> : </td>
                            <td style="text-align:right; border:none;"></td>
                            <td style="text-align:right; border:none;">
                                <?php 
                                    $net_proceed_tk = $total_amount - $total_charges;
                                    print number_format($net_proceed_tk,2,'.',','); 
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Amount in Words -->
            <div class="row">
                <div class="col-sm-12">
                    <p class="text-left" style="font-family: verdana; border:0px; font-weight:normal; font-size:12px;"> 
                        Taka : <?php print convert_number_to_words($net_proceed_tk); ?>
                    </p>
                </div>
            </div>
            
            <!-- Signature Section -->
            <br /><br /><br />
            <div class="row">
                <div class="col-sm-12 text-primary">
                    <p class="text-left" style="font-family: verdana; border:0px; float:left;"> 
                        <small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> 
                        For <b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> &nbsp; &nbsp; &nbsp; </b>
                    </p>
                </div>
            </div>
        </div> <!-- end printableArea div -->
        <?php
    } // End if count(garden_sales) > 0
} // End foreach gardens

if(count($gardens) == 0) {
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
<?php } ?>

</div> 
<!-- /container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
// print area
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}

// row highlighted
$('#table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});

// company wise average
function fn_company_wise_avg(season,auction_center,sale_no,combine_center){
	
	// print window
	if(combine_center=='' || auction_center==combine_center){
		
		window.open("<?php echo site_url('MY_Controller/garden_sale_account_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&combine_center="+combine_center+"&report=7'); ?>", "Company Wise Average Report - Combine", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
		
	}else{
		
		window.open("<?php echo site_url('MY_Controller/garden_sale_account_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&combine_center="+combine_center+"&report=8'); ?>", "Company Wise Average Report - Combine", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
	}	
}

const reportNo = '<?php echo $report_no; ?>';
const gardenName = '<?php echo addslashes($garden_data['garden_name']); ?>';
const saleNo = '<?php echo $sale_no; ?>';
const companyName = '<?php echo isset($company_data['company_name']) ? addslashes($company_data['company_name']) : ""; ?>';

if (reportNo === '2' || reportNo === '3') {
    // For reports 2 and 3
    document.title = gardenName + ' Price Realised List Sale No-' + saleNo;
} else if (reportNo === '10') {
    // For report 10 - show company or garden name with valuation
    const displayName = companyName ? companyName : gardenName;
    document.title = displayName + ' VALUATION FOR SALE - ' + saleNo;
} else {
    // For other reports
    document.title = 'Garden Accounts';
}

function fn_set_garden_avg() {

	var joinedValues = $('#garden_avg_table [id^="garden_avg_row"]').map(function() { return $(this).val(); }).get().join('|');

    // console.log("Joined Values:", joinedValues);

    // Send to PHP using jQuery POST
	$.post('<?php echo site_url('MY_Controller/report_to_save_garden_avg'); ?>', 
        { garden_avg_data: joinedValues }, 
        function(response) {
            // Check if server responded with success (response should be "1")
            if (response === "1") {
                alert("Data saved successfully!");
				window.location.reload();
            } else {
                alert("Failed to save data. Server returned unexpected response.");
                console.log("Server Response:", response);
            }
        }
    ).fail(function(xhr, status, error) {
        console.log("AJAX Error:", status, error);
    });
}

// New delete function (matching your save pattern)
function fn_delete_garden_avg() {
    if (!confirm('Are you sure you want to delete all saved garden average data for this sale?')) {
        return;
    }
    
    // Get the current sale parameters (matching your save pattern)
    var season = '<?php echo $season; ?>';
    var auction_center = '<?php echo $auction_center; ?>';
    var sale_no = '<?php echo $sale_no; ?>';
    var brokers_id = '6'; // Hardcoded as in your save function
    
    // Send to PHP using jQuery POST (matching your save pattern)
    $.post('<?php echo site_url('MY_Controller/report_to_delete_garden_avg'); ?>', 
        { 
            season: season,
            auction_center: auction_center,
            sale_no: sale_no,
            brokers_id: brokers_id
        }, 
        function(response) {
            if (response === "1") {
                alert("Data deleted successfully!");
                window.location.reload();
            } else {
                alert("Failed to delete data. Server returned: " + response);
                console.log("Server Response:", response);
            }
        }
    ).fail(function(xhr, status, error) {
        alert("Error: " + error);
        console.log("AJAX Error:", status, error);
    });
}

</script>
</body>
</html>