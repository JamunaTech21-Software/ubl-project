<!DOCTYPE html>
<html lang="en">
<head>
<title>All Garden Sales Accounts</title>
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
$report_no = (!empty($this->input->get('report', TRUE))) ? $this->input->get('report', TRUE) : '';

// Sale details
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$sale_data = $query->row_array();
?>

<div id="printableArea" class="container">

<?php if($report_no=='1'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="btn-default" href="javascript:void(0);" onclick="return PrintPanel('printableArea');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All</a>
	</div>
</div>

<br />

<?php
// query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry'); 
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row){
	
	// garden id
	$garden_id = $row['garden_id'];
?>
<div id="page<?php print $garden_id; ?>" class="page_break">	

<style>
body{
	font-family:verdana;
}
.table{
	font-family:verdana;
	font-size:12px !important;
	border:none;
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
#table tbody tr.highlighted td { 
	background-color: cyan; 
}
@media print{  
	thead { display: table-header-group; }
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	.page_break{ page-break-after: always !important; }
	
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

<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="btn-default" href="javascript:void(0);" onclick="return PrintPanel('page<?php print $garden_id; ?>');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="7" style="text-align:left; border:0px;">
		<span style="text-transform: uppercase; font-size:20px; text-align:center; font-weight:bold; font-family:courier;">
			<?php print $website_title; ?>
		</span>
		<br />
		<span style="font-size:14px; font-weight:normal;"><?php print $owner_address; ?></span>
		<br />
		<span style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			Garden Wise Sale Accounts
		</span>	
	</th>
</tr>
<tr>
	<td colspan="4" class="text-left" style="font-family: verdana; border:0px; font-size:14px !important;">Sale No : <b><?php print $sale_no; ?> &nbsp;&nbsp; of &nbsp;&nbsp; <?php print $season; ?></b> &nbsp;&nbsp; <?php print $auction_center; ?> Auction Center</td>
	
	<td colspan="3" class="text-right" style="font-family: verdana; border:0px; font-size:14px !important;">Sale Date : <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
</tr>
<tr>
	<td colspan="4" class="text-left" style="font-family: verdana; border:0px; font-size:14px !important;">Garden Name : <b><?php print $row['garden_name']; ?></b></td>
	
	<td colspan="3" class="text-right" style="font-family: verdana; border:0px; font-size:14px !important;">Prompt Date : <?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">LOT</th>
	<th style="text-align:center; border:1px solid;">INVOICE</th>
	<th style="text-align:left; border:1px solid;">GRADE</th>
	<th style="text-align:right; border:1px solid;">BAGS</th>
	<th style="text-align:right; border:1px solid;">KGS</th>
	<th style="text-align:right; border:1px solid;">VALUE</th>
	<th style="text-align:right; border:1px solid;">PRICE</th>
	<th style="text-align:right; border:1px solid;">AMOUNT</th>
</tr>
</thead>
<tbody>
<?php
// lot wise breakdown
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
	invoice_entry.warehouse_charge, 
	invoice_entry.insp_sample, 
	invoice_entry.brokerage_commission, 
	invoice_entry.tea_cess, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
	@total_pkgs:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_pkgs,
	@total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,	
	invoice_entry.valuation,
	sale_entry.sold_price,
	@amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS amount 		
"); 
$this->db->from('invoice_entry'); 
$this->db->join("sale_entry", "invoice_entry.id = sale_entry.invoice_id");
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
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
	<td style="text-align:center; border:none;"><b><?php print $row2['lot_no']; ?></b></td>
	<td style="text-align:center; border:none; text-transform:uppercase;"><b><?php print $row2['w_inv_no']; ?></b></td>
	<td style="text-align:left; border:none; text-transform:uppercase;"><b><?php print $row2['grade_name']; ?></b></td>
	<td style="text-align:right; border:none;"><b><?php print $row2['total_pkgs']; ?></b></td>
	<td style="text-align:right; border:none;"><b><?php print number_format($row2['total_kgs'],2,'.',','); ?></b></td>
	<td style="text-align:right; border:none;"><b><?php print number_format($row2['valuation'],2,'.',''); ?></b></td>
	<td style="text-align:right; border:none;"><b><?php print number_format($row2['sold_price'],2,'.',''); ?></b></td>
	<td style="text-align:right; border:none;"><b><?php print number_format($row2['amount'],2,'.',','); ?></b></td>
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
	<th style="text-align:right;"><?php echo $total_pkgs; ?></th>
	<th style="text-align:right;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">
		<?php echo ($total_amount > 0 && $total_kgs > 0) ? number_format($total_amount / $total_kgs,2,'.','') : ''; ?>
	</th>
	<th style="text-align:right;"><?php echo number_format($total_amount,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div><!-- /col -->
</div><!-- /row -->

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
</div><!-- /col -->
</div><!-- /row -->

<div class="row">
	<div class="col-sm-12">
		<p class="text-left" style="font-family: verdana; border:0px; font-weight:normal; font-size:14px;"> 
			<b>Taka : <?php print convert_number_to_words($net_proceed_tk); ?></b>
		</p>
	</div><!-- /col -->
</div><!-- /row -->

<br />
<br />
<br />

<div class="row">
	<div class="col-sm-12 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px; float:right;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> &nbsp; &nbsp; &nbsp; </b>
		</p>
	</div><!-- /col -->
</div><!-- /row -->
</div><!-- /page -->
<?php } // 1st loop ?>

<!-- /END OF PAGE-->

<?php } // end of report 1 ?>

</div> 
<!-- /container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
// print div
function PrintPanel(printableArea) {
	
	// Works with Chome, Firefox, IE, Safari
	// Get the HTML of div
	var title = document.title;
	var divElements = document.getElementById(printableArea).innerHTML;
	var printWindow = window.open("", "_blank", "");
	// open the window
	printWindow.document.open();
	// write the html to the new window, link to css file
	printWindow.document.write('<html><head><title>' + title + '</title><link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/3.4.1/css/bootstrap.min.css"><link rel="icon" href="<?php echo base_url(); ?>assets/img/favicons/favicon.ico"></head><body>');
	printWindow.document.write(divElements);
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	printWindow.focus();
	// The Timeout is ONLY to make Safari work, but it still works with FF, IE & Chrome.
	setTimeout(function() {
		printWindow.print();
		printWindow.close();
	}, 100);
}

// row highlighted
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