<!DOCTYPE html>
<html lang="en">
<?php include('amount_in_word.php'); ?>
<head>
 <title>Garden Average</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<!-- jQuery and Bootstrap JS (from CDN) -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<!-- Bootstrap CSS (from CDN) -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-glyphicons/1.0.0/bootstrap-glyphicons.css">

	<link rel="icon" href="https://www.unitybrokersltd.com/assets/img/favicon.png">
</head>
<body>
<?php
$season = (!empty($this->input->post('season_name', TRUE))) ? $this->input->post('season_name', TRUE) : '';
$auction_center = (!empty($this->input->post('auction_center', TRUE))) ? $this->input->post('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->post('sale_no', TRUE))) ? $this->input->post('sale_no', TRUE) : '';
$combine_center = (!empty($this->input->post('combine_center', TRUE))) ? $this->input->post('combine_center', TRUE) : '';


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


<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;">Unity Brokers Limited</b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><u>1837 SK Mujib Road, CTG</u></span>
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
	<th colspan="3" style="text-align:center; border:0px; font-size:13px !important;">CURRENT SALE <br /> <?php print $auction_center; ?></th>
	<th colspan="2" style="text-align:center; border:0px; font-size:13px !important;">UPTO DATE SALE <br /><?php print substr($auction_center,0,4); ?> to <?php print substr($combine_center,0,4); ?></th>
</tr>
<tr>
	<th style="text-align:left; border:1px solid; font-size:13px !important;">Garden Name</th>
	
	<th style="text-align:right; border:1px solid; font-size:13px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:13px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:13px !important;">%</th>
	
	<th style="text-align:right; border:1px solid; font-size:13px !important;">Total Kgs</th>
	<th style="text-align:right; border:1px solid; font-size:13px !important;">Avg.</th>
	<th style="text-align:right; border:1px solid; font-size:13px !important; display:none;">%</th>
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id
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
	inv_offering.upto_offering_bag
"); 
$this->db->from('garden_sales_avg_report gs');

// offering bags
$this->db->join("(SELECT garden_id, sum(CASE WHEN auction_center='".$auction_center."' AND sale_no='".$sale_no."' THEN chest_number END) AS current_offering_bag, sum(chest_number) AS upto_offering_bag FROM invoice_entry WHERE season='".$season."' AND sale_date <= '".$sale_data['sale_date']."' AND old_season='".$row2['old_season']."' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY garden_id) inv_offering", "gs.garden_id=inv_offering.garden_id", "left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->group_by('gs.garden_id');
$this->db->order_by('gs.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-size:14px !important;">
		<?php print $row1['garden_name']; ?>
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


</div> 
<!-- /container -->
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

</script>
</body>
</html>