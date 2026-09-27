<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$w_house_id = (!empty($this->input->get('w_house_id', TRUE))) ? $this->input->get('w_house_id', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
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
#table tr td{
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
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
}
</style>

<?php if($report_name=='Summary'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="javascript:void(0);" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12">
		<p style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?></p>
		<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Warehouse Sales Report In Summary</p>
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
	<th style="text-align:left;">Warehouse Name</th>
	<th style="text-align:right;">Sold Bags</th>
	<th style="text-align:right;">Total Kgs</th>
	<th style="text-align:right;">Total Amount</th>
	<th class="no-print" style="text-align:right;">At a glance</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - wh_sales_summary
$this->db->query("DROP TABLE IF EXISTS `wh_sales_summary`");

// create wh_sales_summary
$this->db->query("CREATE TABLE `wh_sales_summary` (
SELECT invoice_entry.w_house_id, invoice_entry.chest_number AS total_sold, invoice_entry.net_kgs AS net_kgs, (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount FROM `sale_entry` JOIN invoice_entry ON (sale_entry.invoice_id = invoice_entry.id) WHERE invoice_entry.season='".$season."' and invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY sale_entry.invoice_id) ");
// end

// ++++++++++++++++++++ REPORT VIWE ++++++++++++++++++++
$total_sold=0;
$net_kgs=0;
$total_amount=0;

//query
$this->db->select("ws.w_house_id, sum(ws.total_sold) AS total_sold, sum(ws.net_kgs) AS net_kgs, sum(ws.total_amount) AS total_amount, wh.warehouse_name, inv_offering.offering_bag"); 
$this->db->from('wh_sales_summary ws');

$this->db->join('warehouse_setup wh','ws.w_house_id=wh.id','left');

$this->db->join("(SELECT w_house_id, sum(chest_number) AS offering_bag FROM `invoice_entry` WHERE season='".$season."' AND auction_center='".$auction_center."' AND sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY w_house_id) inv_offering","ws.w_house_id=inv_offering.w_house_id","left");

$this->db->group_by('ws.w_house_id');
$this->db->order_by('wh.warehouse_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:left;"><b style="text-transform:uppercase;"><?php print $row1['warehouse_name']; ?></b></td>
	<td style="text-align:right;"><?php print $row1['total_sold']; ?></td>
	<td style="text-align:right;"><?php print number_format($row1['net_kgs'],2,'.',','); ?></td>
	<td style="text-align:right;"><?php print number_format($row1['total_amount'],2,'.',','); ?></td>
	<td class="no-print" style="text-align:right;">
	
		<a target="blink" class="text-success" href="<?php echo site_url('MY_Controller/wh_sales_report?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&w_house_id='.$row1['w_house_id'].'&report_name=Sales Summary'); ?>"><b>Sales</b></a>
		
		<b class="text-danger"> &#8594; </b>
		
		<a target="blink" class="text-primary" href="<?php echo site_url('MY_Controller/wh_sales_report?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&w_house_id='.$row1['w_house_id'].'&report_name=Unsold'); ?>"><b>Unsold</b></a>
		
		<b class="text-danger"> &#8594; </b>
		
		<a target="blink" class="text-danger" href="<?php echo site_url('MY_Controller/wh_sales_report?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&w_house_id='.$row1['w_house_id'].'&report_name=Withdrawal'); ?>"><b>W/D</b></a>
		
		<b class="text-danger"> &#8594; </b>
		
		<a target="blink" class="text-success" href="<?php echo site_url('MY_Controller/wh_sales_report?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&w_house_id='.$row1['w_house_id'].'&report_name=Details'); ?>"><b>All</b></a>
	</td>
</tr>
<?php
	$total_sold +=$row1['total_sold'];
	$net_kgs +=$row1['net_kgs'];
	$total_amount +=$row1['total_amount'];
} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="5" style="border:0px;"></td>
</tr>
<tr>
	<th colspan="1" style="text-align:right;">Net Total: </th>
	<th style="text-align:right;"><?php print $total_sold; ?></th>
	<th style="text-align:right;"><?php print number_format($net_kgs,2,'.',','); ?></th>
	<th style="text-align:right;"><?php print number_format($total_amount,2,'.',','); ?></th>
	<th class="no-print" style="text-align:right;"></th>
</tr>	
</tfoot>
</table>
</div>
</div>
</div>
<!-- /row -->
<?php } ?>



<?php if($report_name=='Sales'){ ?>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a target="blink" href="<?php echo site_url(); ?>/Wh_wise_delivery_order/createexcel?season=<?php print $season; ?>&auction_center=<?php print $auction_center; ?>&sale_from=<?php print $sale_from; ?>&sale_to=<?php print $sale_to; ?>&w_house_id=<?php print $w_house_id; ?>"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel</span></a>
	
	<a href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="form-group col-sm-12">
	<p style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?></p>
	<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Warehouse Wise Sales Report</p>
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
<?php
## create temporary table - wh_sales_summary
$this->db->query("DROP TABLE IF EXISTS `wh_sales_summary`");

// create wh_sales_summary
$this->db->query("CREATE TABLE `wh_sales_summary` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	invoice_entry.net_wt_per_bag, 
	invoice_entry.net_kgs,	
	invoice_entry.w_house_id, 
	invoice_entry.garden_id,
	gs.garden_name, 
	ws.warehouse_name,
	invoice_entry.chest_number, 
	invoice_entry.valuation,	
	invoice_entry.remarks,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount
 
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE invoice_entry.season='".$season."' and invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY sale_entry.invoice_id) ");

$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;
$n_sold_amount = 0;
//query
$this->db->select("w_house_id, garden_id, garden_name, warehouse_name"); 
$this->db->from('wh_sales_summary');
$this->db->group_by('w_house_id, garden_id');
$this->db->order_by('warehouse_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>
<tr>
    <th colspan="13" style="font-size:14px !important; border:0px !important; text-transform:uppercase;"><?php print $row1['warehouse_name']; ?> / <?php print $row1['garden_name']; ?></th>
</tr>
<tr>
    <th style="text-align:center;">Sale No</th>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Inv.</th>
    <th style="text-align:center;">Grade Name</th>
	<th style="text-align:center;">Net WT P/Bag</th>
	<th style="text-align:center;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
	<th style="text-align:center;">Value</th>
	<th style="text-align:center;">Sold Price</th>
	<th style="text-align:left;">Buyer1</th>
	<th style="text-align:left;">Buyer2</th>
	<th style="text-align:left;">Buyer3</th>
	<th style="text-align:right;">Amount</th>
</tr>
</thead>
<tbody>
<?php
$g_net_kgs = 0;
$g_chest_number = 0;
$g_sold_amount = 0;
// query
$this->db->select("sale_no"); 
$this->db->from('wh_sales_summary');
$this->db->where('w_house_id', $row1['w_house_id']);
$this->db->where('garden_id', $row1['garden_id']);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
$sold_amount = 0;
$this->db->select("*"); 	
$this->db->from('wh_sales_summary');
$this->db->where('sale_no', $row2['sale_no']);
$this->db->where('w_house_id', $row1['w_house_id']);
$this->db->where('garden_id', $row1['garden_id']);
$this->db->order_by('lot_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
    <td style="text-align:center;"><?php echo $row3['sale_no']; ?></td>
    <td style="text-align:center;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:center; color:#FF69B4;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	<td style="text-align:center;">
	<?php 
		if($row3['sold_price'] > 0){
			print "<font color=green>".number_format($row3['sold_price'],2,'.','')."</font>";
		}else if(!empty($row3['remarks'])){
			print "<b style=\"color:red;\">W/D</b>";
		}else{
			print "<b style=\"color:#006994;\">Out</b>";
		}
	?>
	</td>
	<td style="text-align:left; color:#FF1493;"><?php echo $row3['buyer1']; ?></td>
	<td style="text-align:left; color:#FF1493;"><?php echo $row3['buyer2']; ?></td>
	<td style="text-align:left; color:#FF1493;"><?php echo $row3['buyer3']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
</tr>	

<?php
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	$sold_amount += $row3['sold_amount'];
	
}  // 3rd loop
?>

<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
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

} // 2nd loop	
?>

<tr>
	<th colspan="13" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
<?php
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	$n_sold_amount += $g_sold_amount;
	
} // 1st loop
?>
<tfoot>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;"><?php echo number_format($n_sold_amount,2,'.',','); ?></th>
</tr>
</tfoot>
</table>

</div>
</div> <!-- col -->
</div> <!-- row -->
<?php } // 1st foreach ?>

<?php } // IF SALES ?>



<?php if($report_name=='Sales Summary'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/wh_sales_report?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&w_house_id='.$w_house_id.'&report_name=Sales'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Sales Details</a>
		
		&nbsp; <a class="btn-default" target="blink" href="<?php echo site_url(); ?>/Wh_wise_delivery_order/createexcel?season=<?php print $season; ?>&auction_center=<?php print $auction_center; ?>&sale_from=<?php print $sale_from; ?>&sale_to=<?php print $sale_to; ?>&w_house_id=<?php print $w_house_id; ?>"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel</a>
		
		&nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
$query = $this->db->query("SELECT * FROM warehouse_setup WHERE id='".$w_house_id."' ");
$fetch_wh = $query->row_array();
?>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:14px; font-weight:bold;"><?php print $owner_address; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold;">Warehouse Wise Sales Summary</b>
		</th>
	</tr>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="5" class="text-center" style="border:0px;">
			<b style="text-transform: uppercase; font-size:14px;"><?php print $fetch_wh['warehouse_name']; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="2" class="text-left" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
		</th>
		
		<th style="border:0px;"></th>
		
		<th colspan="2" class="text-right" style="border:0px;">
			<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
		</th>
	</tr>
	<tr>
		<th colspan="5" style="border:0px;"></th>
	</tr>
</thead>
<tbody>
<?php
## create temporary table - wh_sales_summary
$this->db->query("DROP TABLE IF EXISTS `wh_sales_summary`");

// create wh_sales_summary
$this->db->query("CREATE TABLE `wh_sales_summary` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	invoice_entry.net_wt_per_bag, 
	invoice_entry.net_kgs,	
	invoice_entry.w_house_id, 
	invoice_entry.garden_id,
	gs.garden_name, 
	ws.warehouse_name,
	invoice_entry.chest_number, 
	invoice_entry.valuation,	
	invoice_entry.remarks,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount
 
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE invoice_entry.season='".$season."' and invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY sale_entry.invoice_id) ");
// end


// +++++++++++++++ REPORT VIEW ++++++++++++
$g_chest_number = 0;
$g_total_kgs = 0;
$g_sold_amount = 0;

// query
$this->db->select("sale_no"); 
$this->db->from('wh_sales_summary');
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<tr>
    <th colspan="13" style="font-size:14px !important; border:0px !important; text-transform:uppercase;">Sale No: <?php print $row1['sale_no']; ?></th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">Grade Name</th>
	<th style="text-align:right; border:1px solid;">Total Bags</th>
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">Total Amount</th>
	<th style="text-align:right; border:1px solid;">Avg.</th>
</tr>
<?php
// query
$chest_number = 0;
$total_kgs = 0;
$sold_amount = 0;

// query
$this->db->select("garden_name, sum(chest_number) AS chest_number, sum(net_kgs) AS net_kgs, sum(sold_amount) AS sold_amount, (sum(sold_amount) / sum(net_kgs)) AS avg_rate "); 	
$this->db->from('wh_sales_summary');
$this->db->where('sale_no', $row1['sale_no']);
$this->db->group_by('garden_id');
$this->db->order_by('garden_name ASC');
$query2 = $this->db->get();
foreach($query2->result_array() as $row2){
?>

<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px;"><?php echo $row2['garden_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row2['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['sold_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row2['avg_rate'],2,'.',','); ?></td>
</tr>
<?php
	$chest_number += $row2['chest_number'];
	$total_kgs += $row2['net_kgs'];
	$sold_amount += $row2['sold_amount'];
}  // 2nd loop
?>
<tr>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($sold_amount,2,'.',','); ?></th>
	<th style="text-align:right;"><?php echo ($total_kgs > 0 && $sold_amount > 0) ? number_format($sold_amount / $total_kgs,2,'.',',') : '0.00'; ?></th>
</tr>
<?php
	$g_chest_number += $chest_number;
	$g_total_kgs += $total_kgs;
	$g_sold_amount += $sold_amount;
} // 1st loop
?>
<tr>
	<td colspan="5" style="border:0px;"></td>
</tr>
<tr>
	<th style="text-align:right;">Grand Total : </th>
	<th style="text-align:right;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($g_sold_amount,2,'.',','); ?></th>
	<th style="text-align:right;"><?php echo ($g_total_kgs > 0 && $g_sold_amount > 0) ? number_format($g_sold_amount / $g_total_kgs,2,'.',',') : '0.00'; ?></th>
</tr>
<?php }else{ ?>
<tr>
	<th colspan="5" style="padding:10px !important; border:0px !important;"></th>
</tr>
<?php } ?>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

<?php } // IF SALES SUMMARY ?>




<?php if($report_name=='Unsold'){ ?>
<div class="row">
<div class="form-group col-sm-10">
	<p style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?></p>
	<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Warehouse Wise Unsold Report</p>
</div>
<div class="form-group col-sm-2 text-right">
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
<div class="table-responsive">
<table class="table table-sm" id="table">
<?php
## create temporary table - wh_sales_summary
$this->db->query("DROP TABLE IF EXISTS `wh_sales_summary`");

// create wh_sales_summary
$this->db->query("CREATE TABLE `wh_sales_summary` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	invoice_entry.net_wt_per_bag, 
	invoice_entry.net_kgs,	
	invoice_entry.w_house_id, 
	invoice_entry.garden_id,
	gs.garden_name, 
	ws.warehouse_name,
	invoice_entry.chest_number, 
	invoice_entry.valuation,	
	invoice_entry.remarks,
	IFNULL(sale_entry.sold_price,0) AS sold_price
 
FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND NOT invoice_entry.remarks='W/D' GROUP BY invoice_entry.id HAVING(sold_price=0)) ");
// end


// +++++++++++++++ REPORT VIEW ++++++++++++
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("w_house_id, garden_id, garden_name, warehouse_name"); 
$this->db->from('wh_sales_summary');
$this->db->group_by('w_house_id, garden_id');
$this->db->order_by('warehouse_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>
<tr>
    <th colspan="9" style="font-size:14px !important; border:0px !important; text-transform:uppercase;"><?php print $row1['warehouse_name']; ?> / <?php print $row1['garden_name']; ?></th>
</tr>
<tr>
    <th style="text-align:center;">Sale No</th>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Inv.</th>
    <th style="text-align:center;">Grade Name</th>
	<th style="text-align:center;">Net WT P/Bag</th>
	<th style="text-align:center;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
	<th style="text-align:center;">Value</th>
	<th style="text-align:center;">Sold Price</th>
</tr>
</thead>
<tbody>
<?php
$g_net_kgs = 0;
$g_chest_number = 0;
// query
$this->db->select("sale_no"); 
$this->db->from('wh_sales_summary');
$this->db->where('w_house_id', $row1['w_house_id']);
$this->db->where('garden_id', $row1['garden_id']);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
$this->db->select("*"); 	
$this->db->from('wh_sales_summary');
$this->db->where('sale_no', $row2['sale_no']);
$this->db->where('w_house_id', $row1['w_house_id']);
$this->db->where('garden_id', $row1['garden_id']);
$this->db->order_by('lot_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
    <td style="text-align:center;"><?php echo $row3['sale_no']; ?></td>
    <td style="text-align:center;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:center; color:#FF69B4;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	<td style="text-align:center;">
	<?php 
		if($row3['sold_price'] > 0){
			print "<font color=green>".number_format($row3['sold_price'],2,'.','')."</font>";
		}else if(!empty($row3['remarks'])){
			print "<b style=\"color:red;\">W/D</b>";
		}else{
			print "<b style=\"color:#006994;\">Out</b>";
		}
	?>
	</td>
</tr>	

<?php
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	
}  // 3rd loop
?>

<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th></th>
	<th></th>
</tr>

<?php
	$g_net_kgs += $net_kgs;
	$g_chest_number += $chest_number;

} // 2nd loop	
?>

<tr>
	<th colspan="9" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
<?php
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	
} // 1st loop
?>
<tfoot>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
	<th></th>
	<th></th>
</tr>
</tfoot>
</table>

</div>
</div> <!-- col -->
</div> <!-- row -->
<?php } // 1st foreach ?>

<?php } // IF UNSOLD ?>


<?php if($report_name=='Withdrawal'){ ?>
<div class="row">
<div class="form-group col-sm-10">
	<p style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?></p>
	<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Warehouse Wise Withdrawal Report</p>
</div>
<div class="form-group col-sm-2 text-right">
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
<div class="table-responsive">
<table class="table table-sm" id="table">
<?php
## create temporary table - wh_sales_summary
$this->db->query("DROP TABLE IF EXISTS `wh_sales_summary`");

// create wh_sales_summary
$this->db->query("CREATE TABLE `wh_sales_summary` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	invoice_entry.net_wt_per_bag, 
	invoice_entry.net_kgs,	
	invoice_entry.w_house_id, 
	invoice_entry.garden_id,
	gs.garden_name, 
	ws.warehouse_name,
	invoice_entry.chest_number, 
	invoice_entry.valuation,	
	invoice_entry.remarks,
	IFNULL(sale_entry.sold_price,0) AS sold_price
 
FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE invoice_entry.season='".$season."' and invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND invoice_entry.remarks='W/D' GROUP BY sale_entry.invoice_id) ");

$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;
//query
$this->db->select("w_house_id, garden_id, garden_name, warehouse_name"); 
$this->db->from('wh_sales_summary');
$this->db->group_by('w_house_id, garden_id');
$this->db->order_by('warehouse_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>
<tr>
    <th colspan="9" style="font-size:14px !important; border:0px !important; text-transform:uppercase;"><?php print $row1['warehouse_name']; ?> / <?php print $row1['garden_name']; ?></th>
</tr>
<tr>
    <th style="text-align:center;">Sale No</th>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Inv.</th>
    <th style="text-align:center;">Grade Name</th>
	<th style="text-align:center;">Net WT P/Bag</th>
	<th style="text-align:center;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
	<th style="text-align:center;">Value</th>
	<th style="text-align:center;">Sold Price</th>
</tr>
</thead>
<tbody>
<?php
$g_net_kgs = 0;
$g_chest_number = 0;
// query
$this->db->select("sale_no"); 
$this->db->from('wh_sales_summary');
$this->db->where('w_house_id', $row1['w_house_id']);
$this->db->where('garden_id', $row1['garden_id']);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
$this->db->select("*"); 	
$this->db->from('wh_sales_summary');
$this->db->where('sale_no', $row2['sale_no']);
$this->db->where('w_house_id', $row1['w_house_id']);
$this->db->where('garden_id', $row1['garden_id']);
$this->db->order_by('lot_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
    <td style="text-align:center;"><?php echo $row3['sale_no']; ?></td>
    <td style="text-align:center;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:center; color:#FF69B4;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	<td style="text-align:center;">
	<?php 
		if($row3['sold_price'] > 0){
			print "<font color=green>".number_format($row3['sold_price'],2,'.','')."</font>";
		}else if(!empty($row3['remarks'])){
			print "<b style=\"color:red;\">W/D</b>";
		}else{
			print "<b style=\"color:#006994;\">Out</b>";
		}
	?>
	</td>
</tr>	

<?php
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	
}  // 3rd loop
?>

<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th></th>
	<th></th>
</tr>

<?php
	$g_net_kgs += $net_kgs;
	$g_chest_number += $chest_number;

} // 2nd loop	
?>

<tr>
	<th colspan="9" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
<?php
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	
} // 1st loop
?>
<tfoot>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
	<th></th>
	<th></th>
</tr>
</tfoot>
</table>

</div>
</div> <!-- col -->
</div> <!-- row -->
<?php } // 1st foreach ?>
<?php } // IF W/D ?>


<?php if($report_name=='Details'){ ?>
<style>
	@page{
		size:A4 landscape;
	}
</style>
<div class="row">
<div class="form-group col-sm-10">
	<p style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?></p>
	<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Warehouse Wise Sales Report In Details</p>
</div>
<div class="form-group col-sm-2 text-right">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<?php 
$this->db->select("warehouse_name"); 
$this->db->from('warehouse_setup');
$this->db->where('id', $w_house_id);
$query = $this->db->get();
$fetch_wh_info = $query->row_array();
?>

<div class="row">
<div class="col-sm-12 text-center"><b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php echo $fetch_wh_info['warehouse_name'] ; ?></b></div>
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
<?php
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;
$n_sold_amount = 0;
//query
$this->db->select("invoice_entry.w_house_id, invoice_entry.garden_id, garden_and_company_setup.garden_name, warehouse_setup.warehouse_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.w_house_id', $w_house_id);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id, invoice_entry.garden_id');
$this->db->order_by('warehouse_setup.warehouse_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>

<tr>
    <th style="text-align:center;">Garden</th>
    <th style="text-align:center;">Sale No</th>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Inv.</th>
    <th style="text-align:center;">Grade Name</th>
	<th style="text-align:center;">Net WT P/Bag</th>
	<th style="text-align:center;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
	<th style="text-align:center;">Value</th>
	<th style="text-align:center;">Sold Price</th>
	<th style="text-align:left;">Buyer1</th>
	<th style="text-align:left;">Buyer2</th>
	<th style="text-align:left;">Buyer3</th>
	<th style="text-align:right;">Amount</th>
</tr>
</thead>
<tbody>
<?php
$g_net_kgs = 0;
$g_chest_number = 0;
$g_sold_amount = 0;
// query
$this->db->select("invoice_entry.sale_no"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season',$season);
$this->db->where('auction_center',$auction_center);
$this->db->where("sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('garden_id',$row1['garden_id']);
$this->db->where('w_house_id',$row1['w_house_id']);
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
$this->db->select("
	
	invoice_entry.sale_no, 
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	garden_and_company_setup.garden_name,
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	invoice_entry.net_wt_per_bag, 
	invoice_entry.net_kgs, 
	invoice_entry.chest_number,
	invoice_entry.valuation,	
	invoice_entry.remarks,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount
	
"); 
	
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no',$row2['sale_no']);
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('invoice_entry.w_house_id',$row1['w_house_id']);
// $this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$query3 = $this->db->get();
$rows = $query3->result_array();
foreach($rows as $row3){
?>
<tr>
	<td style="text-align:center;"><?php echo $row1['garden_name']; ?></td>
    <td style="text-align:center;"><?php echo $row3['sale_no']; ?></td>
    <td style="text-align:center;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:left; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:center; color:#FF69B4;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	<td style="text-align:center;">
	<?php 
		if($row3['sold_price'] > 0){
			print "<font color=green>".number_format($row3['sold_price'],2,'.','')."</font>";
		}else if(!empty($row3['remarks'])){
			print "<b style=\"color:red;\">W/D</b>";
		}else{
			print "<b style=\"color:#006994;\">Out</b>";
		}
	?>
	</td>
	<td style="text-align:left; color:#FF1493;"><?php echo $row3['buyer1']; ?></td>
	<td style="text-align:left; color:#FF1493;"><?php echo $row3['buyer2']; ?></td>
	<td style="text-align:left; color:#FF1493;"><?php echo $row3['buyer3']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
</tr>	

<?php
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	$sold_amount += $row3['sold_amount'];
	
}  // 3rd loop
?>

<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
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

} // 2nd loop	
?>

<tr>
	<th colspan="13" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
<?php
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	$n_sold_amount += $g_sold_amount;
	
} // 1st loop
?>
<tfoot>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;"><?php echo number_format($n_sold_amount,2,'.',','); ?></th>
</tr>
</tfoot>
</table>

</div>
</div> <!-- col -->
</div> <!-- row -->
<?php } // 1st foreach ?>
<?php } // IF DETAILS ?>


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