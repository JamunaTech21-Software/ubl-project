<!DOCTYPE html>
<html lang="en">
<head>
<title>GARDEN SALES AVERAGE REPORT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
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
#table, #sms_table{
	border-color:black;
}
#table tr td, #sms_table tr td{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tr th, #sms_table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tbody tr.highlighted td { background-color: cyan; } 
#sms_table tbody tr.highlighted td { background-color: cyan; } 

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
	size: auto;
}
</style>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="javascript:void(0)" onClick="printDiv('printableArea')">
			<span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
		</a>
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
					GARDEN SALES AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						Season: <?php print $season; ?> 
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<!-- ALL GARDEN SUMMARY -->
<?php if(empty($garden_id)){ ?>
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="sms_table">
<thead>
<tr>
	<th style="text-align:left; border:1px solid;" class="no-print">
		Send All <input class="form-check-input border-primary" style="margin-left:28px;" type="checkbox" id="check_all" onclick="chekallInvArray()">
	</th>
	<th style="text-align:left; border:1px solid;">Garden Name</th>
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">Avg.</th>
	<th style="text-align:right; border:1px solid;">%</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_single_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_single_sales_avg_report`");

// create garden_single_sales_avg_report
$this->db->query("CREATE TABLE `garden_single_sales_avg_report` (
SELECT 
	invoice_entry.id, 
	invoice_entry.lot_no,
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	invoice_entry.chest_number, 
	contact_person_list.contact_no, 
	invoice_entry.net_kgs AS net_kgs, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount
	
FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN (SELECT `garden_id`, GROUP_CONCAT(`contact_no` SEPARATOR '@') AS contact_no FROM `contact_person_list` WHERE NOT `garden_id` = 0 GROUP BY `garden_id`) contact_person_list ON (invoice_entry.garden_id = contact_person_list.garden_id) 
WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no = '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end

$total_sold2=0;
$sold_kgs2=0;
$total_amount2=0;
$net_offering_bag=0;

// old_season breakdown query
$this->db->select("old_season"); 
$this->db->from('garden_single_sales_avg_report');
$this->db->group_by('old_season');
$this->db->order_by('id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<th colspan="4" style="border:0px;"><?php print $row2['old_season']; ?></th>
</tr>

<?php
$total_sold=0;
$sold_kgs=0;
$total_amount=0;
$total_offering_bag=0;

//query
$this->db->select("gs.id, gs.garden_id, gs.contact_no, sum(gs.total_sold) AS total_sold, sum(gs.sold_kgs) AS sold_kgs, sum(gs.total_amount) AS total_amount, gc.garden_name, inv_offering.offering_bag"); 
$this->db->from('garden_single_sales_avg_report gs');
$this->db->join('garden_and_company_setup gc','gs.garden_id=gc.id','left');

$this->db->join("(SELECT garden_id, sum(chest_number) AS offering_bag FROM invoice_entry WHERE season='".$season."' AND auction_center='".$auction_center."' AND sale_no = '".$sale_no."' AND old_season='".$row2['old_season']."' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY garden_id) inv_offering","gs.garden_id=inv_offering.garden_id","left");

$this->db->where('gs.old_season',$row2['old_season']);
$this->db->group_by('gs.garden_id');
$this->db->order_by('gc.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
	$avg_kgs = ($row1['total_amount'] > 0 && $row1['sold_kgs'] > 0) ? number_format($row1['total_amount'] / $row1['sold_kgs'],2,'.',',') : '0.00';
	$percentage = ($row1['total_sold'] > 0 && $row1['offering_bag'] > 0) ? number_format(($row1['total_sold'] * 100)/$row1['offering_bag'],2,'.','') : '0.00';
?>
<tr>
	<td class="no-print" style="text-align:left; text-transform:uppercase; border:0px; width:150px;">
		<input type="button" value="Send SMS" id="send_sms<?php print $row1['id']; ?>" class="btn btn-sm btn-primary" onclick="fn_sms_disburse_data('<?php print $row1['id']; ?>','<?php print $sale_no; ?>','<?php print $row1['sold_kgs']; ?>','<?php print $avg_kgs; ?>','<?php print $percentage; ?>','<?php print $row1['garden_name']; ?>','<?php print $row1['contact_no']; ?>');"/>

		<input class="form-check-input border-primary" type="checkbox" value="<?php print $row1['id']; ?>|<?php print $sale_no; ?>|<?php print $row1['sold_kgs']; ?>|<?php print $avg_kgs; ?>|<?php print $percentage; ?>|<?php print $row1['garden_name']; ?>|<?php print $row1['contact_no']; ?>" id="check_inv<?php print $row1['id']; ?>">
	</td>

	<td style="text-align:left; text-transform:uppercase; border:0px;">
		<a target="blink" style="color:<?php echo (!empty($row1['contact_no'])) ? 'red' : 'black'; ?>;" href="<?php echo site_url('MY_Controller/garden_single_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id']); ?>">
			<?php print $row1['garden_name']; ?>
		</a>
	</td>
	
	<td style="text-align:right; border:0px;"><?php print number_format($row1['sold_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php print $avg_kgs; ?></td>
	<td style="text-align:right; border:0px;"><?php print $percentage; ?></td>
</tr>
<?php
	$total_sold +=$row1['total_sold'];
	$sold_kgs +=$row1['sold_kgs'];
	$total_amount +=$row1['total_amount'];
	$total_offering_bag +=$row1['offering_bag'];
} // 2nd loop
?>

<tr>
	<th class="no-print" style="border:0px;">
		<input type="button" value="Send SMS All" id="send_sms_all" class="btn btn-sm btn-danger" onclick="fn_all_sms_disburse_data();"/>
	</th>
	<th colspan="1" style="text-align:right; border:0px;">SUB TOTAL: </th>
	<th style="text-align:right; border:0px;"><?php print number_format($sold_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($total_amount > 0 && $sold_kgs > 0) ? number_format($total_amount / $sold_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($total_sold > 0 && $total_offering_bag > 0) ? number_format(($total_sold * 100)/$total_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>

<?php
	$total_sold2 +=$total_sold;
	$sold_kgs2 +=$sold_kgs;
	$total_amount2 +=$total_amount;
	$net_offering_bag +=$total_offering_bag;
} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="5" style="border:0px;"></td>
</tr>
<tr>
	<th class="no-print" style="border:0px;"></th>
	<th colspan="1" style="text-align:right;">NET TOTAL: </th>
	<th style="text-align:right;"><?php print number_format($sold_kgs2,2,'.',','); ?></th>
	<th style="text-align:right;">
	<?php 
		print ($total_amount2 > 0 && $sold_kgs2 > 0) ? number_format($total_amount2 / $sold_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right;">
	<?php 
		print ($total_sold2 > 0 && $net_offering_bag > 0) ? number_format(($total_sold2 * 100)/$net_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>	
</tfoot>
</table>
</div>
</div>
<!-- /row -->
<?php } ?>



<!-- GARDEN WISE DETAILS -->
<?php if(!empty($garden_id)){ 

// garden name
$query = $this->db->query("SELECT * FROM `garden_and_company_setup` WHERE `id` = '".$garden_id."' "); 
$garden_name_result = $query->row_array();
?>
<div class="row">
<div class="col-sm-12">

<?php
## create temporary table - garden_sales_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_report`");

// create garden_sales_report
$this->db->query("CREATE TABLE `garden_sales_report` (
SELECT 
	invoice_entry.id, 
	invoice_entry.sale_no, 
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.w_house_id, 
	invoice_entry.valuation, 
	invoice_entry.chest_number, 
	invoice_entry.net_kgs, 
	IFNULL(sale_entry.sold_price,0) AS sold_price, 
	invoice_entry.remarks, 
	invoice_entry.catalouge_name, 
	invoice_entry.grade_id, 
	invoice_entry.additional_grade, 
	invoice_entry.supplement_tea, 
	invoice_entry.old_season,

	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 

	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 

	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
	
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount 

FROM `invoice_entry` 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no = '".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// END

$n_g_sold_kgs = 0;
$n_total_sold = 0;
$n_sold_amount = 0;

// warehouse breakdown query
$this->db->select("garden_sales_report.w_house_id, warehouse_setup.short_key_word, garden_sales_report.old_season"); 
$this->db->from('garden_sales_report');
$this->db->join('warehouse_setup','garden_sales_report.w_house_id = warehouse_setup.id','left');
$this->db->group_by('garden_sales_report.w_house_id, garden_sales_report.old_season');
$this->db->order_by('garden_sales_report.id ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<table class="table table-sm" id="table">
<thead>
<tr>
    <th colspan="12" style="font-size:14px !important; border:0px !important; text-transform:uppercase;"><?php print $garden_name_result['garden_name']; ?> / <?php print $row1['short_key_word']; ?> / <?php print $row1['old_season']; ?></th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">Sale No</th>
    <th style="text-align:center; border:1px solid;">Lot No</th>
    <th style="text-align:center; border:1px solid;">Invoice</th>
    <th style="text-align:left; border:1px solid;">Grade</th>
	<th style="text-align:right; border:1px solid;">Net Kgs</th>
	<th style="text-align:center; border:1px solid;">Chest</th>
	<th style="text-align:center; border:1px solid;">Value</th>
	<th style="text-align:center; border:1px solid;">Sold Price</th>
	<th style="text-align:left; border:1px solid;">Buyer1</th>
	<th style="text-align:left; border:1px solid;">Buyer2</th>
	<th style="text-align:left; border:1px solid;">Buyer3</th>
	<th style="text-align:right; border:1px solid;">Amount</th>
</tr>
</thead>
<tbody>
<?php
$g_sold_kgs = 0;
$g_total_sold = 0;
$g_sold_amount = 0;

// sale no breakdown query
$this->db->select("sale_no"); 
$this->db->from('garden_sales_report');
$this->db->where('w_house_id',$row1['w_house_id']);
$this->db->where('old_season',$row1['old_season']);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$sold_kgs = 0;
$total_sold = 0;
$sold_amount = 0;

// lot no breakdown query
$this->db->select("
	garden_sales_report.catalouge_name, 
	garden_sales_report.sale_no, 
	garden_sales_report.lot_no, 
	garden_sales_report.w_inv_no, 
	CONCAT(tea_grade_setup.grade_name,garden_sales_report.additional_grade) AS grade_name, 
	garden_sales_report.valuation, 
	IFNULL(garden_sales_report.sold_price,0) AS sold_price, 
	garden_sales_report.sold_kgs, 
	garden_sales_report.total_sold, 
	garden_sales_report.remarks,
	garden_sales_report.buyer1,
	garden_sales_report.buyer2,
	garden_sales_report.buyer3,
	garden_sales_report.sold_amount 
"); 
$this->db->from('garden_sales_report');
$this->db->join('tea_grade_setup', 'garden_sales_report.grade_id = tea_grade_setup.id', 'left');
$this->db->where('sale_no',$row2['sale_no']);
$this->db->where('old_season',$row1['old_season']);
$this->db->where('old_season',$row1['old_season']);
$this->db->group_by('garden_sales_report.id');
$this->db->order_by('garden_sales_report.sale_no ASC, garden_sales_report.lot_no ASC');
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
	<td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['total_sold']; ?></td>
	<td style="text-align:center; border:0px;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	<td style="text-align:center; border:0px;"><?php print number_format($row3['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:left; border:0px; white-space:nowrap; text-transform:uppercase;"><?php echo $row3['buyer1']; ?></td>
	<td style="text-align:left; border:0px; white-space:nowrap; text-transform:uppercase;"><?php echo $row3['buyer2']; ?></td>
	<td style="text-align:left; border:0px; white-space:nowrap; text-transform:uppercase;"><?php echo $row3['buyer3']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
</tr>

<?php
	$sold_kgs += $row3['sold_kgs'];
	$total_sold += $row3['total_sold'];
	$sold_amount += $row3['sold_amount'];
}  // 3rd loop
?>

<tr>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">TOTAL : </th>
	<th style="text-align:right;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:center;"><?php echo $total_sold; ?></th>
	<th colspan="5" style="text-align:center;">Avg. Price: <?php echo ($sold_amount > 0 && $sold_kgs > 0) ? number_format($sold_amount/$sold_kgs,2,'.',',') : '0.00'; ?></th>
	<th style="text-align:right;"><?php echo number_format($sold_amount,2,'.',','); ?></th>
</tr>

<?php
	$g_sold_kgs += $sold_kgs;
	$g_total_sold += $total_sold;
	$g_sold_amount += $sold_amount;
} // 2nd loop	
?>

<tr>
	<th colspan="12" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
</table>
<?php
	$n_g_sold_kgs += $g_sold_kgs;
	$n_total_sold += $g_total_sold;
	$n_sold_amount += $g_sold_amount;
} // 1st loop
?>
<table class="table table-sm" id="table">
<tfoot>
<tr>
	<th style="text-align:center; font-family:courier; font-size:16px !important; border:0px;">NET KGS: <?php echo number_format($n_g_sold_kgs,2,'.',''); ?> / TOTAL BAGS: <?php echo $n_total_sold; ?> / TOTAL AMOUNT: <?php echo number_format($n_sold_amount,2,'.',''); ?> / AVG PRICE: <?php echo ($n_sold_amount > 0 && $n_g_sold_kgs > 0) ? number_format($n_sold_amount/$n_g_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
</tfoot>
</table>

<br />
<br />

<?php
// total unsold catalog wise
$query = $this->db->query("SELECT 

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
	) AS dust_net_kgs 
	
FROM garden_sales_report "); 
$catalog_sold_result = $query->row_array();

$n_sold_bag_l = $catalog_sold_result['leaf_total_bag'];
$n_sold_kgs_l = $catalog_sold_result['leaf_net_kgs'];

$n_sold_bag_d = $catalog_sold_result['dust_total_bag'];
$n_sold_kgs_d = $catalog_sold_result['dust_net_kgs'];

// total offering garden wise
$query = $this->db->query("SELECT 

(IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)) AS leaf_offering, 

(IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)) AS dust_offering 

FROM `invoice_entry` 
WHERE season='".$season."' and auction_center='".$auction_center."' AND sale_no = '".$sale_no."' AND garden_id='".$garden_id."' AND catalogue_status='prepared' AND soft_delete='1'"); 
$offering_result = $query->row_array();

$leaf_offering = $offering_result['leaf_offering'];
$dust_offering = $offering_result['dust_offering'];
$total_offering = $leaf_offering + $dust_offering;
?>

<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="4" style="text-align:center; color:red;">SOLD (UNDER CONSTRUCTION)</th>
</tr>
<tr>
	<th style="text-align:center; color:green;"></th>
	<th style="text-align:right; color:green;">Bags</th>
	<th style="text-align:right; color:green;">Kgs</th>
	<th style="text-align:right; color:green;">%</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align:center;"><b>Leaf</b></td>
	<td style="text-align:right; color:green;"><?php print $n_sold_bag_l; ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_kgs_l,2,'.',''); ?></td>
	<td style="text-align:right; color:green;">
	<?php 
		print ($n_sold_bag_l > 0 && $leaf_offering > 0) ? number_format(($n_sold_bag_l * 100)/$leaf_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:center;"><b>Dust</b></td>
	<td style="text-align:right; color:green;"><?php print $n_sold_bag_d; ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; color:green;">
	<?php 
		print ($n_sold_bag_d > 0 && $dust_offering > 0) ? number_format(($n_sold_bag_d * 100)/$dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:center;"><b>Net Total</b></td>
	<td style="text-align:right; color:green;"><?php print $n_sold_bag_l + $n_sold_bag_d; ?></td>
	<td style="text-align:right; color:green;"><?php print number_format($n_sold_kgs_l + $n_sold_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; color:green;"><?php print number_format((($n_sold_bag_l + $n_sold_bag_d) * 100)/$total_offering,2,'.',''); ?></td>
</tr>
</tbody>
</table>
</div>

<br />
<br />

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div>
<!-- /row -->
<?php } ?>

</div> 
<!-- container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
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

//row highlighted
$('#table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});

$('#sms_table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});

// Function to handle the click event of the "Check All" checkbox
function chekallInvArray() {
    // Get the state of the "Check All" checkbox
    var isChecked = $('#check_all').prop('checked');

    // Set the state of all checkboxes in the table based on the "Check All" checkbox
    $('input[type="checkbox"]').prop('checked', isChecked);
}

// multiple radio button value
function getChecklistItems() {
    var result = $("#sms_table input:checkbox:checked").get(); // get only checked radio button
    var columns = $.map(result, function(element) {
        return $(element).attr("value");
    });
    return columns.join("~");
}

// Single SMS
function fn_sms_disburse_data(invoice_id, sale_no, sold_kgs, avg_kgs, percentage, garden_name, contact_no) {
    // validation
    if (invoice_id === '' || contact_no === '') {
        alert("Sender ID is missing");
        return false;
    }

    // before response
    $("#send_sms" + invoice_id).val('Please Wait...');
    $("#send_sms" + invoice_id).prop("disabled", true);

    // send to server
    $.post("<?php echo site_url('MY_Controller/single_sms_disburse'); ?>", 
        { 
            invoice_id: invoice_id, 
            sale_no: sale_no, 
            sold_kgs: sold_kgs, 
            avg_kgs: avg_kgs,
            percentage: percentage,
            garden_name: garden_name,
            contact_no: contact_no
        }, 
        function(data) {
            if (data == 1) {
                 alert("✅ SMS Sent Successfully!");
            } else {
                alert("Error: " + data);
            }
            // reset button
            $("#send_sms" + invoice_id).val('Send SMS');
            $("#send_sms" + invoice_id).prop("disabled", false);
			window.location.reload();
        }
    )
}


// Bulk SMS
function fn_all_sms_disburse_data(){
	// checked attendance array data
    var inv_array = getChecklistItems();
    // alert(inv_array);
	
	// validation
    if(inv_array == ''){
        alert("Please check at least a Garden.");
        return false;
    }

	// before response
    $("#send_sms_all").val('Please Wait...');
    $("#send_sms_all").prop("disabled", true);

	// send to server
    $.post("<?php echo site_url('MY_Controller/bulk_sms_disburse'); ?>", 
        { 
            inv_array: inv_array
        }, 
        function(data) {
            if (data == 1) {
                 alert("✅ SMS Sent Successfully!");
            } else {
                alert("Error: " + data);
            }
            // reset button
			$("#send_sms_all").val('Send SMS All');
	 		$("#send_sms_all").prop("disabled", false);
			window.location.reload();
        }
    );
}
</script>

</body>
</html>