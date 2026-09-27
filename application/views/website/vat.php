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
$combine_center = (!empty($this->input->get('combine_center', TRUE))) ? $this->input->get('combine_center', TRUE) : '';
$report_no = (!empty($this->input->get('report', TRUE))) ? $this->input->get('report', TRUE) : '';

## garden details
$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
$garden_data = $query->row_array();

## Sale details
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$sale_data = $query->row_array();
?>

<div class="container">
<?php
// get html value
$row_limit_pp = 10;

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
	@amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS amount 		
"); 
$this->db->from('invoice_entry'); 
$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
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
	<td style="text-align:center; border:none;">
		<?php print $row2['old_season']; ?>
	</td>
</tr>
<?php

$total_pkgs += $row2['total_pkgs'];
$total_kgs += $row2['total_kgs'];
$total_amount += $row2['amount'];
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
	<td style="text-align:right;">
		<?php echo ($total_amount > 0 && $total_kgs > 0) ? number_format($total_amount / $total_kgs,2,'.','') : ''; ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($total_amount,2,'.',','); ?>
	</td>
	<td style="text-align:right;"></td>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->

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
		$total_warehouse_charge = number_format(($total_pkgs * $warehouse_charge),2,'.','');
		print number_format($total_warehouse_charge,2,'.',','); 
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
<?php
$sl=5;
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
		$total_charges = $total_warehouse_charge + $total_insp_sample + $total_brokerage + $total_tea_cess + $garden_adjust;
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
			Taka : <?php //print convert_number_to_words($net_proceed_tk); ?>
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
</div> 
<!-- /container -->

</body>
</html>