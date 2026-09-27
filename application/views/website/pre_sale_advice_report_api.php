<!DOCTYPE html>
<html lang="en">
<head>
 <title>Pre & Post Sale</title>
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
$season = (!empty($this->input->post('season_name', TRUE))) ? $this->input->post('season_name', TRUE) : '0';
$auction_center = (!empty($this->input->post('auction_center', TRUE))) ? $this->input->post('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->post('sale_no', TRUE))) ? $this->input->post('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->post('garden_id', TRUE))) ? $this->input->post('garden_id', TRUE) : '';
$garden_clause = (!empty($garden_id)) ? "invoice_entry.garden_id='".$garden_id."' " : "NOT invoice_entry.garden_id='' ";
$garden_clause2 = (!empty($garden_id)) ? "garden_id='".$garden_id."' " : "NOT garden_id='' ";

?>

<div id="printableArea" class="container">

<style>
body{
	font-family:verdana !important;
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
	size: auto;
	margin-left: 100px;
	
	@bottom-right {
		content: counter(page) " of " counter(pages);
	}
}
</style>

<div class="row">
	<div class="form-group text-left col-sm-9">
		<span style="text-transform: uppercase; font-size:20px; text-align:center; font-weight:bold; font-family:courier;">
			Unity Brokers Limited
		</span>
		<br />
		<span style="font-size:12px; font-weight:normal; text-transform: uppercase;">"Progressive tower" (5th floor) 1837, Sk. mujib road,  agrabad C/A, Chittagong</span>
		<br />
	</div>
	<div class="form-group col-sm-3 text-right">
		<?php if(!empty($garden_id)){ ?> 
			<a target="_blank" class="no-print" style="text-decoration:none;" href="<?php echo site_url('Pre_sale_advice_report_export/pre_sale_advice_export'); ?>?season=<?php echo urlencode($season); ?>&auction_center=<?php echo urlencode($auction_center); ?>&sale_from=<?php echo urlencode($sale_no); ?>&garden_id=<?php echo urlencode($garden_id); ?>">
				<span class="badge badge-danger" style="cursor:pointer;">
					<span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel
				</span>
			</a>&nbsp;
		<?php } ?>
		
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>


<?php
## Prompt Date
$query = $this->db->query("SELECT date_format(sale_date,'%d-%M-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%M-%Y') AS buyer_prompt_date FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$prompt_date = $query->row_array();
?>

<div class="row">
	<div class="col-sm-9 text-left" style="font-family: verdana; font-weight:bold; font-size:11px; text-transform: uppercase;">
		Season: <?php print $season; ?> <?php print $auction_center ; ?>, Sale No: <?php print $sale_no ; ?> <br> Sale Date: <?php print $prompt_date['sale_date']; ?> Prompt Date: <?php print $prompt_date['buyer_prompt_date']; ?>
	</div>
	<div class="col-sm-3 text-right" style="font-family: verdana; font-size:12px;">
		<?php 
			$current_time = date("Y-m-d H:i:s"); 
		?>

		Date : <?php print date("d-M-Y", strtotime($current_time)); ?><br>
		Time : <?php print date("h:i:s A", strtotime($current_time)); ?>
	</div>
</div>

<?php
## create temporary table - pre_post_sale_advice
$this->db->query("DROP TABLE IF EXISTS `pre_post_sale_advice`");

// create pre_post_sale_advice @  buyer-1
$this->db->query("CREATE TABLE `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: <b>', invoice_entry.old_sale_no, '</b> / <b>', invoice_entry.old_lot_no, '</b>'), '') AS re_print,
	invoice_entry.garden_id,
	gn.garden_name,
	gn.company_name AS company_id,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	invoice_entry.valuation,
	invoice_entry.net_kgs AS offering_kgs,
	invoice_entry.chest_number AS offering_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");

// create pre_post_sale_advice @  buyer-2
$this->db->query("INSERT INTO `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: <b>', invoice_entry.old_sale_no, '</b> / <b>', invoice_entry.old_lot_no, '</b>'), '') AS re_print,
	invoice_entry.garden_id,
	gn.garden_name,
	gn.company_name AS company_id,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	invoice_entry.valuation,
	invoice_entry.net_kgs AS offering_kgs,
	invoice_entry.chest_number AS offering_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");

// create pre_post_sale_advice @  buyer-3
$this->db->query("INSERT INTO `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: <b>', invoice_entry.old_sale_no, '</b> / <b>', invoice_entry.old_lot_no, '</b>'), '') AS re_print,
	invoice_entry.garden_id,
	gn.garden_name,
	gn.company_name AS company_id,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	invoice_entry.valuation,
	invoice_entry.net_kgs AS offering_kgs,
	invoice_entry.chest_number AS offering_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");
?>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<?php
$net_new_off_bag = 0;
$net_rp_off_bag = 0;
$net_new_off_kgs = 0;
$net_rp_off_kgs = 0;
$net_new_sold_bag = 0;
$net_rp_sold_bag = 0;
$net_new_sold_kgs = 0;
$net_rp_sold_kgs = 0;
$net_new_sold_amount = 0;
$net_rp_sold_amount = 0;
$net_offering_kgs = 0;
$net_offering_bag = 0;
$net_sold_kgs = 0;
$net_sold_bag = 0;
$net_sold_amount = 0;

// query
$this->db->select("garden_id, garden_name"); 	
$this->db->from('pre_post_sale_advice');
$this->db->group_by('garden_id');
$this->db->order_by('garden_name ASC');
$query1 = $this->db->get();
foreach($query1->result_array() as $row1){
?>
<thead>
<tr>
    <td colspan="11" style="text-align:center; text-transform:uppercase; border:0px;">
		Sale No: <?php print $sale_no; ?> <br> 
		<b style="font-size:14px;">Garden: <?php print $row1['garden_name']; ?></b>
	</td>
</tr>

<tr>
	<td colspan="11" class="text-left" style="border:0px;">
		<p style="font-family: verdana; font-size:14px;">Dear Sir, </p>
		We give below the grade wise Valuation / Sold prices.
	</td>
</div>

<tr>
    <td rowspan="2" style="text-align:center; border:1px solid; vertical-align:middle;">LOT</td>
    <td rowspan="2" style="text-align:center; border:1px solid; vertical-align:middle;">INV.</td>
    <td rowspan="2" style="text-align:center; border:1px solid; vertical-align:middle;">GRADE</td>
    <td rowspan="2" style="text-align:center; border:1px solid; vertical-align:middle;">RE-PRINT</td>
	<td colspan="3" style="text-align:center; border:1px solid; vertical-align:middle;">OFFERING</td>
	<td colspan="4" style="text-align:center; border:1px solid; vertical-align:middle;">SOLD</td>
</tr>
<tr>
	<td style="text-align:center; border:1px solid; vertical-align:middle;">KGS</td>
	<td style="text-align:center; border:1px solid; vertical-align:middle;">PKGS</td>
	<td style="text-align:center; border:1px solid; vertical-align:middle;">VALUE</td>
	
	<td style="text-align:center; border:1px solid; vertical-align:middle;">KGS</td>
	<td style="text-align:center; border:1px solid; vertical-align:middle;">PKGS</td>
	<td style="text-align:center; border:1px solid; vertical-align:middle;">PRICE</td>
	<td style="text-align:center; border:1px solid; vertical-align:middle;">TOTAL <br /> AMOUNT</td>
</tr>
</thead>
<tbody>
<?php
$total_new_off_bag = 0;
$total_rp_off_bag = 0;
$total_new_off_kgs = 0;
$total_rp_off_kgs = 0;
$total_new_sold_bag = 0;
$total_rp_sold_bag = 0;
$total_new_sold_kgs = 0;
$total_rp_sold_kgs = 0;
$total_new_sold_amount = 0;
$total_rp_sold_amount = 0;
$total_offering_kgs = 0;
$total_offering_bag = 0;
$total_sold_kgs = 0;
$total_sold_bag = 0;
$total_sold_amount = 0;

// query
$this->db->select("lot_no, w_inv_no, grade_name, re_print, offering_kgs, offering_bag, valuation, sum(buyer_kgs) AS sold_kgs, sum(buyer_bag) AS sold_bag, sold_price, sum(buyer_amount) AS sold_amount "); 	
$this->db->from('pre_post_sale_advice');
$this->db->where('garden_id', $row1['garden_id']);
$this->db->group_by('lot_no');
$this->db->order_by('lot_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	
	// reprint and new bags
	$new_off_bag = (empty($row3['re_print'])) ? $row3['offering_bag'] : 0;
	$rp_off_bag = (!empty($row3['re_print'])) ? $row3['offering_bag'] : 0;
	
	$new_off_kgs = (empty($row3['re_print'])) ? $row3['offering_kgs'] : 0;
	$rp_off_kgs = (!empty($row3['re_print'])) ? $row3['offering_kgs'] : 0;
	
	$new_sold_bag = (empty($row3['re_print'])) ? $row3['sold_bag'] : 0;
	$rp_sold_bag = (!empty($row3['re_print'])) ? $row3['sold_bag'] : 0;
	
	$new_sold_kgs = (empty($row3['re_print'])) ? $row3['sold_kgs'] : 0;
	$rp_sold_kgs = (!empty($row3['re_print'])) ? $row3['sold_kgs'] : 0;
	
	$new_sold_amount = (empty($row3['re_print'])) ? $row3['sold_amount'] : 0;
	$rp_sold_amount = (!empty($row3['re_print'])) ? $row3['sold_amount'] : 0;
?>
<tr>
    <td style="text-align:center; border:1px solid;"><?php echo $row3['lot_no']; ?></td>
    <td style="text-align:center; border:1px solid; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
    <td style="text-align:left; border:1px solid; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
    <td style="text-align:left; border:1px solid; text-transform:uppercase;"><?php echo $row3['re_print']; ?></td>
	
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['offering_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo $row3['offering_bag']; ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
	
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo $row3['sold_bag']; ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['sold_price'],2,'.',''); ?></td>
	<td style="text-align:right; border:1px solid;"><?php echo number_format($row3['sold_amount'],2,'.',','); ?></td>
</tr>	
<?php 
	$total_new_off_bag += $new_off_bag;
	$total_rp_off_bag += $rp_off_bag;
	
	$total_new_off_kgs += $new_off_kgs;
	$total_rp_off_kgs += $rp_off_kgs;
	
	$total_new_sold_bag += $new_sold_bag;
	$total_rp_sold_bag += $rp_sold_bag;
	
	$total_new_sold_kgs += $new_sold_kgs;
	$total_rp_sold_kgs += $rp_sold_kgs;
	
	$total_new_sold_amount += $new_sold_amount;
	$total_rp_sold_amount += $rp_sold_amount;
	
	$total_offering_kgs += $row3['offering_kgs']; 
	$total_offering_bag += $row3['offering_bag']; 
	
	$total_sold_kgs += $row3['sold_kgs'];
	$total_sold_bag += $row3['sold_bag']; 
	$total_sold_amount += $row3['sold_amount']; 
} // 2nd loop 
?>
<tr>
	<td colspan="4" style="text-align:right; border:1px solid;"><b>TOTAL: </b></td>
	
	<td style="text-align:right; border:1px solid;"><b><?php echo number_format($total_offering_kgs,2,'.',','); ?></b></td>
	<td style="text-align:right; border:1px solid;"><b><?php echo $total_offering_bag; ?></b></td>
	<td style="text-align:right; border:1px solid;"></td>
	
	<td style="text-align:right; border:1px solid;"><b><?php echo number_format($total_sold_kgs,2,'.',','); ?></b></td>
	<td style="text-align:right; border:1px solid;"><b><?php echo $total_sold_bag; ?></b></td>
	<td style="text-align:right; border:1px solid;">
		<b><?php print ($total_sold_amount > 0 && $total_sold_kgs > 0) ? number_format($total_sold_amount / $total_sold_kgs,2) : '0'; ?></b>
	</td>
	<td style="text-align:right; border:1px solid;"><b><?php echo number_format($total_sold_amount,2,'.',','); ?></b></td>
</tr>
<tr>
	<th colspan="10" style="border:0px !important;"></th>
</tr>
</tbody>
<?php 
	$net_new_off_bag += $total_new_off_bag;
	$net_rp_off_bag += $total_rp_off_bag;
	
	$net_new_off_kgs += $total_new_off_kgs;
	$net_rp_off_kgs += $total_rp_off_kgs;
	
	$net_new_sold_bag += $total_new_sold_bag;
	$net_rp_sold_bag += $total_rp_sold_bag;
	
	$net_new_sold_kgs += $total_new_sold_kgs;
	$net_rp_sold_kgs += $total_rp_sold_kgs;
	
	$net_new_sold_amount += $total_new_sold_amount;
	$net_rp_sold_amount += $total_rp_sold_amount;
	
	$net_offering_kgs += $total_offering_kgs;
	$net_offering_bag += $total_offering_bag;
	
	$net_sold_kgs += $total_sold_kgs;
	$net_sold_bag += $total_sold_bag;
	$net_sold_amount += $total_sold_amount;
} // 1st loop 

$new_unsold_kgs = ($net_new_off_kgs - $net_new_sold_kgs);
$rp_unsold_kgs = ($net_rp_off_kgs - $net_rp_sold_kgs);
?>
</table>
</div> <!-- col -->
</div> <!-- row -->

<br />

<div class="row">
	<div class="col-sm-4">
		<table class="table table-sm" id="table">
			<tbody>
				<tr>
					<td style="text-align:right; border:0px;"></td>
					<td style="text-align:center; border:0px;"><b><u>NEW</u></b></td>
				</tr>
				<tr>
					<td style="text-align:right; border:0px;">Sold Pkgs :</td>
					<td style="text-align:center; border:1px solid;"><?php print $net_new_sold_bag; ?></td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;">Unsold Pkgs :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print $new_unsold_bag = ($net_new_off_bag - $net_new_sold_bag); ?>
					</td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;"><b>Total Pkgs </b> :</td>
					<td style="text-align:center; border:1px solid;"><?php print $net_new_off_bag; ?></td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;">Sold % :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print ($net_new_sold_kgs > 0 && $net_new_off_kgs > 0) ? round(($net_new_sold_kgs / $net_new_off_kgs) * 100) : '0'; ?>
					</td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;">Unsold % :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print ($new_unsold_kgs > 0 && $net_new_off_kgs > 0) ? round(($new_unsold_kgs / $net_new_off_kgs) * 100) : '0'; ?>
					</td>
				</tr>	
				<tr>	
					<td style="text-align:right; border:0px;">Avg. Tk :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print ($net_new_sold_amount > 0 && $net_new_sold_kgs > 0) ? number_format($net_new_sold_amount / $net_new_sold_kgs,2) : '0'; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div> <!-- col -->
	<div class="col-sm-4">
		<table class="table table-sm" id="table">
			<tbody>
				<tr>
					<td style="text-align:right; border:0px;"></td>
					<td style="text-align:center; border:0px;"><b><u>RE-PRINT</u></b></td>
				</tr>
				<tr>
					<td style="text-align:right; border:0px;">Sold Pkgs :</td>
					<td style="text-align:center; border:1px solid;"><?php print $net_rp_sold_bag; ?></td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;">Unsold Pkgs :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print $rp_unsold_bag = ($net_rp_off_bag - $net_rp_sold_bag); ?>
					</td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;"><b>Total Pkgs </b> :</td>
					<td style="text-align:center; border:1px solid;"><?php print $net_rp_off_bag; ?></td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;">Sold % :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print ($net_rp_sold_kgs > 0 && $net_rp_off_kgs > 0) ? round(($net_rp_sold_kgs / $net_rp_off_kgs) * 100) : '0'; ?>
					</td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;">Unsold % :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print ($rp_unsold_kgs > 0 && $net_rp_off_kgs > 0) ? round(($rp_unsold_kgs / $net_rp_off_kgs) * 100) : '0'; ?>
					</td>
				</tr>		
				<tr>	
					<td style="text-align:right; border:0px;">Avg. Tk :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print ($net_rp_sold_amount > 0 && $net_rp_sold_kgs > 0) ? number_format($net_rp_sold_amount / $net_rp_sold_kgs,2) : '0'; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div> <!-- col -->
	<div class="col-sm-4">
		<table class="table table-sm" id="table">
			<tbody>
				<tr>
					<td style="text-align:right; border:0px;"></td>
					<td style="text-align:center; border:0px;"><b><u>TOTAL</u></b></td>
				</tr>
				<tr>
					<td style="text-align:right; border:0px;">Sold Kgs :</td>
					<td style="text-align:center; border:1px solid;"><?php print number_format($net_sold_kgs,2); ?></td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;">Unsold Kgs :</td>
					<td style="text-align:center; border:1px solid;">
						<?php print $net_unsold_kgs = number_format($net_offering_kgs - $net_sold_kgs,2); ?>
					</td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;"><b>Total Kgs </b> :</td>
					<td style="text-align:center; border:1px solid;"><?php print number_format($net_offering_kgs,2); ?></td>
				</tr>	
				<tr>
					<td style="text-align:right; border:0px;"></td>
					<td style="text-align:center; border:0px;">&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right; border:0px;"></td>
					<td style="text-align:center; border:0px;">&nbsp;</td>
				</tr>
				<tr>
					<td style="text-align:right; border:0px;;"><b>Sold Avg. Tk :</b></td>
					<td style="text-align:center; border:1px solid;">
						<b><?php print ($net_sold_amount > 0 && $net_sold_kgs > 0) ? number_format($net_sold_amount / $net_sold_kgs,2) : '0'; ?></b>
					</td>
				</tr>	
			</tbody>
		</table>
	</div> <!-- col -->
</div> <!-- row -->

<br />

<div class="row">
	<div class="col-sm-6 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			With best wishes, <br /><br /> Your Sincerely, 
			<br />
			<br />
			<br />
			<br />
			<b style="text-transform: uppercase; font-size:14px;;"> Unity Brokers Limited </b>
		</p>
	</div>
</div>

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

</div>
</body>
</html>