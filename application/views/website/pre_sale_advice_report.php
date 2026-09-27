<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$company_id = (!empty($this->input->get('company_id', TRUE))) ? $this->input->get('company_id', TRUE) : '';
$garden_clause = (!empty($garden_id)) ? "invoice_entry.garden_id='".$garden_id."' " : "NOT invoice_entry.garden_id='' ";
$garden_clause2 = (!empty($garden_id)) ? "garden_id='".$garden_id."' " : "NOT garden_id='' ";

## company details
$query = $this->db->query("SELECT * FROM company_setup WHERE id='".$company_id."' ");
$company_data = $query->row_array(); 

// get garden info
$this->db->select('*'); 
$this->db->from('garden_and_company_setup');
$this->db->where('id',$garden_id);
$this->db->where('soft_delete',	1);
$query = $this->db->get();
$garden_info = $query->row_array();
$current_time = date("Y-m-d H:i:s"); 

$title = !empty($company_id) ? $company_data['company_name'].'-'.$sale_no : $garden_info['garden_name'].'-'.$sale_no;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $title ; ?></title>
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

	.page-break {
		break-before: page;
	}
}

@page{
	size: auto;
	margin-left: 100px;

	/* @bottom-left {
        content: "Date: <?php echo date('d-M-Y', strtotime($current_time)); ?> Time: <?php echo date('h:i:s A', strtotime($current_time)); ?>";
    }
	
	@bottom-right {
		content: counter(page) " of " counter(pages);
	} */

	@top-right {
		content: "Print-Date: <?php echo date('d-m-Y', strtotime($current_time)); ?> | Page " counter(page) " of " counter(pages);
		font-size: 15px;
		font-family: Arial, sans-serif;
		color: #333;
	}

}
</style>

<div class="row">
	<div class="form-group col-sm-12 text-right">
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
	invoice_entry.old_season,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: ', invoice_entry.old_sale_no, ' /', invoice_entry.old_lot_no, ' -'), '') AS re_print,
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.remarks !='W/D' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");

// create pre_post_sale_advice @  buyer-2
$this->db->query("INSERT INTO `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	invoice_entry.old_season,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: ', invoice_entry.old_sale_no, ' /', invoice_entry.old_lot_no, ' -'), '') AS re_print,
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.remarks !='W/D' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");

// create pre_post_sale_advice @  buyer-3
$this->db->query("INSERT INTO `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	invoice_entry.old_season,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: ', invoice_entry.old_sale_no, ' /', invoice_entry.old_lot_no, '-'), '') AS re_print,
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.remarks !='W/D' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");
?>
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
if (!empty($company_id)) {
    $where = "company_id = ?";
    $params = array($company_id);
} else {
    $where = "sale_no != ''";
    $params = array(); // no parameter binding needed
}

$sql = "
SELECT 
    garden_id, 
    garden_name,

    SUM(CASE WHEN re_print = '' THEN offering_bag ELSE 0 END) AS new_off_bag,
    SUM(CASE WHEN re_print != '' THEN offering_bag ELSE 0 END) AS rp_off_bag,

    SUM(CASE WHEN re_print = '' THEN offering_kgs ELSE 0 END) AS new_off_kgs,
    SUM(CASE WHEN re_print != '' THEN offering_kgs ELSE 0 END) AS rp_off_kgs,

    SUM(CASE WHEN re_print = '' THEN sold_bag ELSE 0 END) AS new_sold_bag,
    SUM(CASE WHEN re_print != '' THEN sold_bag ELSE 0 END) AS rp_sold_bag,

    SUM(CASE WHEN re_print = '' THEN sold_kgs ELSE 0 END) AS new_sold_kgs,
    SUM(CASE WHEN re_print != '' THEN sold_kgs ELSE 0 END) AS rp_sold_kgs,

    SUM(CASE WHEN re_print = '' THEN sold_amount ELSE 0 END) AS new_sold_amount,
    SUM(CASE WHEN re_print != '' THEN sold_amount ELSE 0 END) AS rp_sold_amount,

    SUM(offering_kgs) AS offering_kgs,
    SUM(offering_bag) AS offering_bag,
    SUM(sold_bag) AS sold_bag,
    SUM(sold_kgs) AS sold_kgs,
    SUM(sold_amount) AS sold_amount

FROM (
    SELECT 
        garden_id, 
        garden_name, 
        lot_no, 
        w_inv_no, 
        grade_name, 
        re_print, 
        offering_kgs, 
        offering_bag, 
        valuation, 
        SUM(buyer_kgs) AS sold_kgs, 
        SUM(buyer_bag) AS sold_bag, 
        sold_price, 
        SUM(buyer_amount) AS sold_amount
    FROM pre_post_sale_advice
    WHERE {$where}
    GROUP BY lot_no
) AS lot_summary
GROUP BY garden_id
ORDER BY garden_name ASC
";

$query = $this->db->query($sql, $params);
$data = $query->result_array();

foreach($data as $row1){

	$net_new_off_bag = $row1['new_off_bag'];
	$net_rp_off_bag = $row1['rp_off_bag'];
	
	$net_new_off_kgs = $row1['new_off_kgs'];
	$net_rp_off_kgs = $row1['rp_off_kgs'];
	
	$net_new_sold_bag = $row1['new_sold_bag'];
	$net_rp_sold_bag = $row1['rp_sold_bag'];
	
	$net_new_sold_kgs = $row1['new_sold_kgs'];
	$net_rp_sold_kgs =  $row1['rp_sold_kgs'];
	
	$net_new_sold_amount = $row1['new_sold_amount'];
	$net_rp_sold_amount = $row1['rp_sold_amount'];
	
	$net_offering_kgs = $row1['offering_kgs']; 
	$net_offering_bag = $row1['offering_bag']; 
	
	$net_sold_kgs = $row1['sold_kgs'];
	$net_sold_bag = $row1['sold_bag']; 
	$net_sold_amount = $row1['sold_amount']; 

	$new_unsold_kgs = ($net_new_off_kgs - $net_new_sold_kgs);
	$rp_unsold_kgs = ($net_rp_off_kgs - $net_rp_sold_kgs);
?>
<div class="col-sm-12"></div>
<div class="row">
	<div class="form-group text-left col-sm-12">
		<span style="text-transform: uppercase; font-size:20px; text-align:center; font-weight:bold; font-family:courier;">
			<?php print $website_title; ?>
		</span>
		<br />
		<span style="font-size:12px; font-weight:normal; text-transform: uppercase;">"Progressive tower" (5th floor) 1837, Sk. mujib road,  agrabad C/A, Chittagong</span>
		<br />
	</div>

	<?php if (!empty($company_id)) { ?>
		<div class="col-sm-12 text-left"><p style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px;;">To: <?php print $company_data['company_name']; ?>, <?php print $company_data['company_address1']; ?></p></div>
	<?php } ?>

	<div class="col-sm-12 text-left" style="font-family: verdana; font-weight:bold; font-size:11px; text-transform: uppercase;">
		Season: <?php print $season; ?> <?php print $auction_center ; ?>, Sale No: <?php print $sale_no ; ?>, Sale Date: <?php print $prompt_date['sale_date']; ?> Prompt Date: <?php print $prompt_date['buyer_prompt_date']; ?>
	</div>

	<div class="col-sm-12">
		<table class="table table-sm" id="table" style="margin-bottom:5px;">
			<thead>
				<tr>
					<td colspan="11" style="text-align:center; text-transform:uppercase; border:0px;">
						Sale No: <?php print $sale_no; ?> <br> 
						<b style="font-size:14px;">Garden: <?php print $row1['garden_name']; ?></b>
					</td>
				</tr>

				<tr>
					<td colspan="11" class="text-left" style="border:0px;">
						<?php if(!empty($garden_info['report_to'])) { ?> 
						<p style="font-family: verdana; font-size:14px;" contenteditable="true"><?php print nl2br($garden_info['report_to']) ; ?></p>
						<?php } ?>
						<p style="font-family: verdana; font-size:14px;">Dear Sir, </p>
						We give below the grade wise Valuation / Sold prices.
					</td>
				</tr>
			

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
		$this->db->select("lot_no, w_inv_no, grade_name, re_print, old_season, offering_kgs, offering_bag, valuation, sum(buyer_kgs) AS sold_kgs, sum(buyer_bag) AS sold_bag, sold_price, sum(buyer_amount) AS sold_amount "); 	
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
			<td style="text-align:left; border:1px solid; text-transform:uppercase;"><?php echo $row3['re_print']; ?> <?php 
						// Only show old_season if it's different from current season
						echo ($row3['old_season'] != $season) ? $row3['old_season'] : '';
						?></td>
			
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
		</tbody>

		</table>
	</div> <!-- col -->
</div> <!-- row -->

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

<div class="row">
	<div class="col-sm-6">
		<p class="text-left" style="font-family: verdana; border:0px; font-size:13px;"> 
			With best wishes, <br /><br /> Your Sincerely, 
			<br />
			<br />
			<br />
			<br />
			<b style="text-transform: uppercase;"> <?php print $website_title; ?> </b>
		</p>
	</div>

	<div class="col-sm-12" style="margin-top:15px;">
		<table>
			<tr>
				<td style="font-size:13px; vertical-align: top;">CC:&nbsp;&nbsp;</td>
				<td style="font-size:13px;" contenteditable="true"><?php print nl2br($garden_info['report_cc']) ; ?></td>
			</tr>
		</table>
	</div>
</div>
<div class="page-break"></div>
<?php 
} // 1st loop 
?>

<?php
// ==== Single Garden Report at the End ====
// Change this garden ID as needed
// <-- your garden id here

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
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->group_by('invoice_entry.garden_id, invoice_entry.old_season');
$this->db->order_by('invoice_entry.old_season', 'DESC');
$query_single = $this->db->get();

if ($query_single->num_rows() > 0) {
    foreach($query_single->result_array() as $garden_data) {
        $new_balance     = $garden_data['new_offered_bags'] - $garden_data['new_sold_bags'];
        $reprint_balance = $garden_data['reprint_offered_bags'] - $garden_data['reprint_sold_bags'];
?>
  <table width="100%" style="border-collapse:collapse; margin-top:50px; margin-bottom:20px;">

    <!-- Heading row -->
    <tr>
        <th colspan="13" style="text-align:center; font-size:16px; background:#eee;">
            <?php echo $garden_data['garden_name']; ?> - Season <?php echo $garden_data['old_season']; ?>
        </th>
    </tr>

    <!-- Sub Heading Row -->
    <tr>
        <th colspan="6" style="text-align:center; border:1px solid;">Fresh</th>
        <th colspan="6" style="text-align:center; border:1px solid;">Reprint</th>
    </tr>

    <!-- Column Titles -->
    <tr>
        <th style="text-align:center; border:1px solid;">Offered</th>
        <th style="text-align:center; border:1px solid;">Unsold</th>
        <th style="text-align:center; border:1px solid;">Sold Bags</th>
        <th style="text-align:center; border:1px solid;">Sold Kgs</th>
        <th style="text-align:center; border:1px solid;">Amount Tk.</th>
        <th style="text-align:center; border:1px solid;">Avg Tk.</th>

        <th style="text-align:center; border:1px solid;">Offered</th>
        <th style="text-align:center; border:1px solid;">Unsold</th>
        <th style="text-align:center; border:1px solid;">Sold Bags</th>
        <th style="text-align:center; border:1px solid;">Sold Kgs</th>
        <th style="text-align:center; border:1px solid;">Amount Tk.</th>
        <th style="text-align:center; border:1px solid;">Avg Tk.</th>
    </tr>

    <!-- Data Row -->
    <tr>
        <!-- Fresh -->
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo $garden_data['new_offered_bags']; ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo $new_balance; ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo $garden_data['new_sold_bags']; ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo $garden_data['new_sold_kgs']; ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo number_format($garden_data['new_total_amount'], 2); ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;">
            <?php echo ($garden_data['new_total_amount'] > 0 && $garden_data['new_sold_kgs'] > 0) 
                ? number_format($garden_data['new_total_amount'] / $garden_data['new_sold_kgs'], 2) 
                : '0.00'; ?>
        </td>

        <!-- Reprint -->
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo $garden_data['reprint_offered_bags']; ?></td>
		<td style="text-align:right; border:1px solid; padding:3px;"><?php echo $reprint_balance; ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo $garden_data['reprint_sold_bags']; ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo $garden_data['reprint_sold_kgs']; ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;"><?php echo number_format($garden_data['reprint_total_amount'], 2); ?></td>
        <td style="text-align:right; border:1px solid; padding:3px;">
            <?php echo ($garden_data['reprint_total_amount'] > 0 && $garden_data['reprint_sold_kgs'] > 0) 
                ? number_format($garden_data['reprint_total_amount'] / $garden_data['reprint_sold_kgs'], 2) 
                : '0.00'; ?>
        </td>
    </tr>

</table>

<?php
    } // end foreach
} // end if
?>

<script src="<?php echo base_url().'assets/jquery/3.4.1/jquery.min.js'; ?>"></script>
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