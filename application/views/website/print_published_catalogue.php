<?php 
// get html value
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$catalogue = (!empty($this->input->get('catalogue', TRUE))) ? $this->input->get('catalogue', TRUE) : '';
$lot_from = (!empty($this->input->get('lot_from', TRUE))) ? $this->input->get('lot_from', TRUE) : '';
$lot_to = (!empty($this->input->get('lot_to', TRUE))) ? $this->input->get('lot_to', TRUE) : '';
$warehouse_id = (!empty($this->input->get('warehouse_id', TRUE))) ? $this->input->get('warehouse_id', TRUE) : '';
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Catalogue Sale No. - <?php print $sale_no ; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-glyphicons.css'; ?>">

<script src="<?php echo base_url().'assets/js/multi_filter_dt/jquery-3.3.1.js'; ?>"></script>		
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/bootstrap.min.js'; ?>"></script>

<?php
include("json_function.php");

## Sale schedule
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE sale_no='".$sale_no."' AND season_name='".$season."' AND auction_center='".$auction_center."' ");
$sale_sch_data = $query->row_array();
$sale_date = $sale_sch_data['sale_date'];

## catalog header
$query = $this->db->query("SELECT * FROM cover_page_info_setup WHERE soft_delete='1' ");
$result_header = $query->row_array();
$vat_ait_sample_info = $result_header['cover_page_text'];
?>

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
}
.table{
	font-family:verdana !important;
	font-size:12px !important;
	width: 100% !important;
}	
table.table-bordered{
    border:1px solid black;
}
table.table-bordered > thead > tr > th{
    border:1px solid black;
	padding:0px;
}
table.table-bordered > tbody > tr > td{
    border:1px solid black;
	padding:0px;
}

@media print {
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	.page-break { page-break-after: always; }
	.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left; }
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
	.col-sm-2 { width: 16.66666667%; }
	.col-sm-1 { width: 8.33333333%; }
	#cover_page_top{
		margin-top:120px !important;
	}
}
</style>
</head>

<body>
<div class="container">

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a target="blink" href="<?php echo site_url(); ?>/Developer_export/createExcel?season=<?php print $season; ?>&auction_center=<?php print $auction_center; ?>&sale_no=<?php print $sale_no; ?>">
			<span style="cursor:pointer;"> <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Developer Sheet</span>
		</a>
		<a target="blink" href="<?php echo site_url(); ?>/Catalogue_export/createExcel?season=<?php print $season; ?>&auction_center=<?php print $auction_center; ?>&sale_no=<?php print $sale_no; ?>">
			<span style="cursor:pointer;"> <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel</span>
		</a>
		<a href="#" onClick="window.print();">
			<span style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span>
		</a>
	</div>
</div>
<!-- This content show only when Full Catalogue print -->
<?php if($catalogue=='Full Catalogue'){ ?>
<?php
## +++++++++++++++ START COVER PAGE ++++++++++++++
$this->db->select("IF(season != old_season, old_season, '') AS old_season, catalouge_name, account_concerned, COUNT(DISTINCT lot_no) AS number_of_lot, sum(chest_number) AS number_of_bags, sum(net_kgs) AS net_kgs, (CASE WHEN catalouge_name = 'Supplement' AND supplement_tea = 'Leaf' THEN 'LEAF(SUPP)' WHEN catalouge_name = 'Supplement' AND supplement_tea = 'Dust' THEN 'DUST(SUPP)' WHEN catalouge_name = 'Supplement' AND supplement_tea = 'Green Tea' THEN 'Green(SUPP)'  WHEN catalouge_name = 'Supplement' AND supplement_tea = 'Orthodox' THEN 'Orthodox(SUPP)' ELSE catalouge_name END) AS adjusted_catalouge_name"); 
$this->db->from('invoice_entry');
($catalogue=='Full Catalogue') ? "" : $this->db->where('catalouge_name', $catalogue);	
$this->db->where('published', 'Yes');
$this->db->where('remarks !=', 'W/D');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$this->db->where('soft_delete', 1);
$this->db->group_by('season,auction_center,sale_no,catalouge_name,supplement_tea,old_season,account_concerned');
$this->db->order_by('lot_no ASC');
$query = $this->db->get();
$cover_page_result = $query->result_array();
if(count($cover_page_result) > 0){		
?>
<!-- <div class="row">
	<div class="form-gorup col-sm-12">
		<img src="<?php echo base_url().'assets/img/slider/1.jpg'; ?>" width="100%;" height="192" />
	</div>
</div> -->

<div class="row" id="cover_page_top" style="margin-left:40px;">
	<div class="form-gorup col-sm-12">
		<h1 style="text-align:center; font-size:30px; margin-right:100px;"><span style="border-bottom:1px solid black;">SALE NUMBER - <?php print $sale_no; ?></span></h1>
		<h1 style="text-align:left; font-size:25px; letter-spacing:22px;">BANGLADESH TEA</h1>
		<p style="text-align:left; font-size:14px; text-transform: uppercase;">Season&nbsp;&nbsp;&nbsp;<?php print $season; ?>&nbsp;&nbsp;&nbsp;Manufacture (Except where otherwise shown)</p>
		<p style="text-align:left; font-size:14px; text-transform: uppercase; word-spacing: 74px; margin: 15px 0;">To be sold by auction at</p>
		<p style="text-align:left; font-size:14px; text-transform: uppercase;">
			<?php print $sale_sch_data['auction_address']; ?>
		</p>
		<p style="text-align:left; font-size:14px; text-transform: uppercase; margin: 15px 0;"><span style="margin-right:100px;">On <?php print date("l", strtotime($sale_sch_data['sale_date'])); ?></span> <span style="word-spacing: 100px;"><?php print date("F j, Y", strtotime($sale_sch_data['sale_date'])); ?></span> </p>
		<p style="text-align:left; font-size:14px; text-transform: uppercase; word-spacing: 50px;">At 08:30 AM Bangladesh Standard Time</p>
		<p style="text-align:left; font-size:14px; text-transform: uppercase; word-spacing: 28px; margin: 15px 0;">Subject to the receipt of Sellers instructions</p>
	</div> 
</div> 

<div class="row" style="margin-left:40px;">
	<?php
	$number_of_lot = 0;
	$number_of_bags = 0; // Initialize the total bags variable
	$net_kgs = 0; // Initialize the total net kgs variable
	?>

	<div class="col-sm-12">
		<div style="display: flex; font-size: 14px; text-transform: uppercase;">
			<div style="display: flex; flex-direction: column; width: 300px;">
				<b style="text-align:left; font-size:14px;">Total: <span id="totalBags"></span> PACKAGES</b>
			</div>
			<div style="display: flex; flex-direction: column; width: 300px; text-transform: uppercase;">
				<b style="text-align:left; font-size:14px;"><span id="totalKgs"></span> KGS</b>
			</div>
		</div>
	</div>

	<?php
	$number_of_lot = 0;
	$number_of_bags = 0;
	$net_kgs = 0;
	foreach($cover_page_result as $row1){
		$number_of_lot += $row1['number_of_lot'];
		$number_of_bags += $row1['number_of_bags'];
		$net_kgs += $row1['net_kgs'];
	?>
		<div class="col-sm-12">
			<div style="display: flex; font-size: 14px;">
				<div style="display: flex; flex-direction: column; width: 250px;">
					<div style="display: flex;">
						<div style="width: 100px; text-align: right;"><?php echo $row1['number_of_bags']; ?></div>
						<div style="margin-left: 10px; width: 150px;"><?php echo 'BAGS ' . $row1['adjusted_catalouge_name']; ?></div>
					</div>
				</div>
				<div style="display: flex; flex-direction: column; width: 450px;">
					<div style="display: flex;">
						<div style="width: 100px; text-align: right;"><?php echo $row1['number_of_lot']; ?></div>
						<div style="margin-left: 10px; width: 50px;">LOTS</div>
						<div style="width: 100px; text-align: center;"><?php echo number_format($row1['net_kgs'], 2, '.', ''); ?></div>
						<div style="margin-left: 10px; width: 50px;">KGS</div>
						<div style="margin-left: 10px; width: 150px; white-space:nowrap;"><?php echo $row1['old_season']; ?> <?php print ($row1['account_concerned']=='Yes') ? 'A/C CON' : ''; ?></div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
	?>
	<div class="form-gorup col-sm-12">
		<h3 style="text-align:left; font-size:14px; word-spacing: 85px; text-transform: uppercase;"> 
			<span>PROMPT: <?php print date("l j F, Y", strtotime($sale_sch_data['buyer_prompt_date'])); ?></span><br><br>
			<span>Conditions of Sale as usual</span>
		</h3>
	</div>
</div>

<div class="row" style="margin-top: 60px;">
	<div class="form-gorup col-sm-12" style="text-align:center; font-size:14px;">
		<span style="border-bottom:1px solid black;">SALE ORDER</span>
	</div>	
	<div class="form-gorup col-sm-12">
		<div class="row">
			<div class="col-sm-4"></div>
			<div class="col-sm-4" style="font-size:14px !important;">
				<table>
					<?php
						$this->db->select("sale_order_setup.order_serial, sale_order_setup.brokers_id, brokers_setup.brokers_name"); 
						$this->db->from('sale_order_setup');
						$this->db->join('brokers_setup','sale_order_setup.brokers_id = brokers_setup.id','left');
						$this->db->where('sale_order_setup.soft_delete', 1);
						$this->db->where('sale_order_setup.season_name', $season);
						$this->db->where('sale_order_setup.auction_center', $auction_center);
						$this->db->where('sale_order_setup.sale_no', $sale_no);
						$this->db->where('sale_order_setup.order_serial <=', 7);
						$this->db->order_by('sale_order_setup.order_serial ASC');
						$query = $this->db->get();
						$so_list_data = $query->result_array();
						foreach($so_list_data as $brokers_list){ 
					?>	
					<tr>
						<td style="text-align:left; text-transform:uppercase; white-space:nowrap; padding:5px 0 !important;">
						<?php 
						if($define_brokers_id == $brokers_list['brokers_id']){
							print "<b style='font-family: Courier New; font-weight: 900; letter-spacing: 1px; font-size:20px !important;'>" . sprintf("%2d", $brokers_list['order_serial']) . ' ' . $brokers_list['brokers_name'] . "</b>";
						}else{
							print sprintf("%2d", $brokers_list['order_serial']) . ' ' . $brokers_list['brokers_name'];
						}
						?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-sm-4"></div>
		</div>
	</div>
	<div class="form-gorup col-sm-12">
		<div class="row">
			<div class="col-sm-4"></div>
			<div class="col-sm-4" style="font-size:14px !important;">
				<table>
					<?php
						$this->db->select("sale_order_setup.order_serial, sale_order_setup.brokers_id, brokers_setup.brokers_name"); 
						$this->db->from('sale_order_setup');
						$this->db->join('brokers_setup','sale_order_setup.brokers_id = brokers_setup.id','left');
						$this->db->where('sale_order_setup.soft_delete', 1);
						$this->db->where('sale_order_setup.season_name', $season);
						$this->db->where('sale_order_setup.auction_center', $auction_center);
						$this->db->where('sale_order_setup.sale_no', $sale_no);
						$this->db->where('sale_order_setup.order_serial >', 7);
						$this->db->order_by('sale_order_setup.order_serial ASC');
						$query = $this->db->get();
						$so_list_data = $query->result_array();
						foreach($so_list_data as $brokers_list){ 
					?>	
					<tr>
						<td style="text-align:left; text-transform:uppercase; padding:5px 0 !important;">
						<?php 
						if($define_brokers_id == $brokers_list['brokers_id']){
							print "<b style='font-family: Courier New; font-weight: 900; letter-spacing: 1px; font-size:20px !important;'>" . sprintf("%02d", $brokers_list['order_serial']) . ' - ' . $brokers_list['brokers_name'] . "</b>";
						}else{
							print sprintf("%02d", $brokers_list['order_serial']) . ' - ' . $brokers_list['brokers_name'];
						}
						?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-sm-4"></div>
		</div>
	</div>
</div>
<br />
<div class="page-break"></div>
<?php } // end of cover page ?>


<?php
## ++++++++++++ START INDEX PAGE ++++++++++++++
$this->db->select("
garden_and_company_setup.garden_name, 

GROUP_CONCAT(DISTINCT CASE 
	WHEN invoice_entry.catalouge_name = 'Leaf' THEN invoice_entry.index_pageNo 
	WHEN invoice_entry.catalouge_name = 'Supplement' AND invoice_entry.supplement_tea = 'Leaf' THEN CONCAT('S-',invoice_entry.index_pageNo)
END SEPARATOR ', ') AS leaf_lot_page_no,

GROUP_CONCAT(DISTINCT CASE 
	WHEN invoice_entry.catalouge_name = 'Dust' THEN invoice_entry.index_pageNo 
	WHEN invoice_entry.catalouge_name = 'Supplement' AND invoice_entry.supplement_tea = 'Dust' THEN CONCAT('S-',invoice_entry.index_pageNo)
END SEPARATOR ', ') AS dust_lot_page_no,

GROUP_CONCAT(DISTINCT CASE WHEN invoice_entry.catalouge_name = 'Supplement' THEN invoice_entry.index_pageNo END SEPARATOR ', ') AS supplement_lot_page_no,

(CASE
	WHEN invoice_entry.catalouge_name = 'Leaf' AND invoice_entry.supplement_tea = 'Leaf' OR invoice_entry.catalouge_name = 'Supplement' AND invoice_entry.supplement_tea = 'Leaf' THEN 'Leaf'
	WHEN invoice_entry.catalouge_name = 'Dust' AND invoice_entry.supplement_tea = 'Dust' OR invoice_entry.catalouge_name = 'Supplement' AND invoice_entry.supplement_tea = 'Dust' THEN 'Dust'
END) AS catalouge_name,

invoice_entry.sale_no, 
invoice_entry.season"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
($catalogue=='Full Catalogue') ? "" : $this->db->where('catalouge_name', $catalogue);
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id, invoice_entry.supplement_tea');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$index_result = $query->result_array();
if(count($index_result) > 0){	
?>
<div class="row">
<div class="col-sm-12">
	<h1 style="text-align:center; font-size:25px;"><span style="border-bottom:1px solid black;">INDEX FOR SALE NO.<?php print $sale_no; ?></span></h1>
</div>
</div> 

<div class="row">
<div class="col-sm-6">
<table class="table table-bordered">
<thead>
<tr>
	<th colspan="2" style="text-align:center">LEAF</th>
</tr>
<tr>
	<th style="text-align:center; letter-spacing:3px;"><u>MARK</u></th>
	<th style="text-align:center; letter-spacing:3px;"><u>PAGE</u></th>
</tr>
</thead>
<tbody>
<?php
foreach($index_result as $row1){
if($row1['catalouge_name'] == 'Leaf'){
	// Raw string like "3,1,5,S-2"
	$pageNos = explode(',', $row1['leaf_lot_page_no']);
	// Trim any spaces
	$pageNos = array_map('trim', $pageNos);
	// Natural sort to handle numeric and S- prefixes properly
	natsort($pageNos);
	// Convert back to comma-separated string
	$leafsortedPages = implode(', ', $pageNos);
?>

<tr>
	<td style="width:210px; vertical-align: middle; padding: 0 5px; font-size:10px !important;"><b><?php echo $row1['garden_name']; ?></b></td> 
	<td style="text-align:right; vertical-align: middle; padding: 0 5px; font-size:10px !important;"><b><?php echo $leafsortedPages; ?></b></td>
</tr>

<?php
}else{
	// do nothing
}

} // loop
?>
</tbody>
</table>
</div> 

<div class="col-sm-6">
<div class="row">
<div class="col-sm-12">
<table class="table table-bordered">
<thead>
<tr>
	<th colspan="2" style="text-align:center">DUST</th>
</tr>
<tr>
	<th style="text-align:center; letter-spacing:3px;"><u>MARK</u></th>
	<th style="text-align:center; letter-spacing:3px;"><u>PAGE</u></th>
</tr>
</thead>
<tbody>
<?php
foreach($index_result as $row1){
if($row1['catalouge_name'] == 'Dust'){
	// Raw string like "3,1,5,S-2"
	$pageNos = explode(',', $row1['dust_lot_page_no']);
	// Trim any spaces
	$pageNos = array_map('trim', $pageNos);
	// Natural sort to handle numeric and S- prefixes properly
	natsort($pageNos);
	// Convert back to comma-separated string
	$dustsortedPages = implode(', ', $pageNos);
?>

<tr>
	<td style="width:210px; vertical-align: middle; padding: 0 5px; font-size:10px !important;"><b><?php echo $row1['garden_name']; ?></b></td> 
	<td style="text-align:right; vertical-align: middle; padding: 0 5px; font-size:10px !important;"><b><?php echo $dustsortedPages; ?></b></td>
</tr>

<?php
}else{
	// do nothing
}

} // loop
?>
</tbody>
</table>
</div> 
<!-- /col-12 -->
</div>
<!-- /row -->
</div>
<!-- /col-6 -->
</div> 
<!-- /row -->
<div class="page-break"></div>
<?php 
}
?>

<?php
## ++++++++++++++ START VALUATION PAGE ++++++++++++++++++++++
$this->db->select("lot_no, catalouge_name, valuation, net_kgs"); 
$this->db->from('invoice_entry');
$this->db->where('published', 'Yes');
($catalogue=='Full Catalogue') ? "" : $this->db->where('catalouge_name', $catalogue);	
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$this->db->where('soft_delete', 1);
$this->db->group_by('lot_no');
$this->db->order_by('lot_no ASC');
$query = $this->db->get();
$valuation_result = $query->result_array();
if(count($valuation_result) > 0){	

// set default
$row_limit_pp = 49;
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 5;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($valuation_result);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($valuation_result as $row1){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- /row -->
<div class="row" data-print="valuation_section">
<?php } ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-2" style="width: 20%;">
<table style="width:100%;">
<tr>
	<th style="text-align:center; font-size:13px;">LOT</th>
	<th style="text-align:right; font-size:13px;">VALUE</th>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="2"></td></tr>
<!-- This row is only used for two row spacing -->
<?php } ?>
<tr>
	<td style="text-align:center; font-size:14px;"><?php echo $row1['lot_no']; ?></td> 
	<td style="text-align:right; font-size:14px;"><?php echo number_format($row1['valuation'],2,'.',''); ?> </td>
</tr>

<?php
// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>
</table>
<?php print ($i==$total_records) ? "*** END ***" : ""; ?>
</div> 
<!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> 
<!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
// post assign value 
$i++;
} // end foreach loop
?>
<div class="page-break" style="text-align:center;"></div>
<?php } // end of valuation ?>
<?php } ?>

<?php
## ++++++ LEAF CATALOGUE FIRST PART +++++
if($catalogue=='Leaf' || $catalogue=='Full Catalogue'){
$catalouge_name = "Leaf";
?>
<div class="row" style="margin-bottom:15px;" data-print="leaf_section">
	<div class="col-sm-1"></div>
	<div class="col-sm-9">
		<span style="font-family:verdana; font-size:12px !important; white-space:normal !important;">
		<?php print nl2br($vat_ait_sample_info); ?></span>
	</div>
	<div class="col-sm-2"></div>
</div>

<div class="row" style="margin-bottom:20px;" data-print="leaf_section">
	<div class="col-sm-1"></div>
	<div class="col-sm-9">
		<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important;">
			<div class="col-sm-3 text-left" style="padding:5px; font-size:12px; margin-right: -20px !important;">Lot</div>
			<div class="col-sm-1 text-center" style="padding:5px; font-size:12px; margin-right: -18px !important;">Invoice <br> No</div>
			<div class="col-sm-3 text-center" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -25px!important;">Grade</div>
			<div class="col-sm-3" style="padding: 5px; font-size: 12px; text-align: center;">
				<span style="display: inline-block; text-align: center;">Grose <br> Each</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="display: inline-block; text-align: center;">Net <br> Each</span>
			</div>
			<div class="col-sm-1 text-center" style="padding:5px; font-size:12px;">Total <br> Net</div>
			<div class="col-sm-1 text-center" style="padding:5px; font-size:12px;">No.of <br> Chest</div>
		</div>
	</div>
	<div class="col-sm-2"></div>
</div>
<?php
// query lot breakdown
$this->db->select("
    invoice_entry.id, 
    invoice_entry.lot_no, 
    invoice_entry.old_lot_no, 
    invoice_entry.old_sale_no, 
    invoice_entry.ol_os_sc, 
    invoice_entry.w_inv_no, 
    invoice_entry.invoice_group_txt, 
    invoice_entry.invoice_group, 
    invoice_entry.invoice_group_upto, 
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
    invoice_entry.gross_wt_per_bag, 
    invoice_entry.net_wt_per_bag, 
    invoice_entry.total_kgs, 
    invoice_entry.net_kgs, 
    invoice_entry.chest_number, 
    invoice_entry.page_no, 
    invoice_entry.catalogue_part, 
    invoice_entry.remarks, 
    invoice_entry.last_lock, 
    IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
    invoice_entry.bulk_lot,
    invoice_entry.old_season,
    invoice_entry.account_concerned,
    invoice_entry.category,
    invoice_entry.chest_name,
    garden_and_company_setup.garden_name, 
    invoice_entry.short_note,
    warehouse_setup.short_key_word,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.catalogue_part', '1st');
$this->db->order_by('invoice_entry.lot_no ASC');
$lot_query = $this->db->get();

// Start Set lot sequence
$lots = $lot_query->result_array();
if(count($lots) > 0){
	
$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

// Grouping lots
foreach ($lots as $row2) {
    if (($prev_lot_no !== null && $prev_group !== null) && 
        ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
        // Lot break detected
        $groups[] = $current_group;
        $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

// Start printing groups
foreach ($groups as $single_group) {

    // your variables
    $g_total_kgs = 0;
    $g_net_kgs = 0;
    $g_chest_number = 0;
    $count_last_lot = '';

    $group_head = $single_group[0]; // First row of the group for heading

    // heading echo
    ?>
    <div class="row" data-print="leaf_section" id="leafprev_<?php echo $group_head['id']; ?>" title="<?php echo $group_head['id']; ?>">
        <div class="col-sm-1"></div>
        <div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
            <b><?php echo $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
            <b><?php echo $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
            <b>
                <?php echo $group_head['invoice_group_txt'] . ' ' . $group_head['invoice_group']; ?>
                <?php echo (!empty($group_head['invoice_group_upto']) ? '-' . $group_head['invoice_group_upto'] : ''); ?>
                <?php echo $group_head['category']; ?>&nbsp;&nbsp;
                (<?php echo $group_head['chest_name']; ?>)
            </b>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="row" data-print="leaf_section">
        <div class="col-sm-1"></div>
        <div class="col-sm-1" style="margin-right:-10px;"></div>
        <div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
			<b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
			<b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>
		</div>
    </div>

    <?php
    foreach ($single_group as $row2) {
    ?>
        <div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="leaf_section" id="leafnext_<?php echo $row2['id']; ?>">
            <div class="col-sm-1"></div>

            <?php if ($count_last_lot == $row2['lot_no'] && $row2['bulk_lot'] == 'y') { ?> 
                <div class="col-sm-1" style="padding:5px;"></div>
                <div class="col-sm-1" style="padding:5px;"></div>
                <div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
                <div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
                <div class="col-sm-1" style="padding:5px; text-align:right;"></div>
                <div class="col-sm-2" style="padding:5px; text-align:right;"></div>
            <?php } else { ?>
                <div class="col-sm-1" id='leaflot_<?php echo $row2['id']; ?>' title="<?php echo $row2['id']; ?>" style="padding:5px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?></div>
                <div class="col-sm-1" style="padding:5px;">
                    <?php echo ($row2['ol_os_sc'] == 'Yes') ? $row2['old_lot_no'] . '/' . $row2['old_sale_no'] : ''; ?>
                </div>
                <div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
                    <?php
                    preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);
                    $w_inv_no = implode('', $matches[2]);
                    echo $w_inv_no;
                    ?>
                </div>
                <div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
                    <?php echo $row2['grade_name']; ?>
                </div>
                <div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
                    <?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
                </div>
            <?php } ?>

            <div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
                <?php echo number_format($row2['net_kgs'],1,'.',''); ?>
            </div>
            <div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
                <?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <?php
        $g_total_kgs += $row2['total_kgs'];
        $g_net_kgs += $row2['net_kgs'];
        $g_chest_number += $row2['chest_number'];
        $count_last_lot = $row2['lot_no'];
    }
    ?>

    <div class="row" style="margin-bottom:10px;" data-print="leaf_section" title="<?php echo $row2['id']; ?>">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
                <div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
                <div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
                <div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
                <div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>

    <div class="row" style="margin-bottom:10px;" data-print="leaf_section">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
        </div>
        <div class="col-sm-3"></div>
    </div>

<?php
} // closing foreach($groups as $single_group)
?>

<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('remarks !=', 'W/D');
	$this->db->where('catalouge_name', 'Leaf');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row targetleafend" style="margin-bottom:20px;" data-print="leaf_section">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border: 1px solid black !important;">
			<div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Grand Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px;"><?php print number_format($total_leaf_result['total_kgs'],2,'.','') ;?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php print number_format($total_leaf_result['net_kgs'],2,'.','') ;?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php print $total_leaf_result['number_of_bags'] ;?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<div class="page-break" style="text-align:center; letter-spacing:22px;"><span class="targetleafend2">END</span></div>
<?php } // end row count ?>
<?php } // end leaf catalogue first part ?>

<?php
## ++++++ LEAF CATALOGUE SECOUND PART +++++
if($catalogue=='Leaf' || $catalogue=='Full Catalogue'){
$catalouge_name = "Leaf";
?>

<?php
// query lot breakdown
$this->db->select("
    invoice_entry.id, 
    invoice_entry.lot_no, 
    invoice_entry.old_lot_no, 
    invoice_entry.old_sale_no, 
    invoice_entry.ol_os_sc, 
    invoice_entry.w_inv_no, 
    invoice_entry.invoice_group_txt, 
    invoice_entry.invoice_group, 
    invoice_entry.invoice_group_upto, 
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
    invoice_entry.gross_wt_per_bag, 
    invoice_entry.net_wt_per_bag, 
    invoice_entry.total_kgs, 
    invoice_entry.net_kgs, 
    invoice_entry.chest_number, 
    invoice_entry.page_no, 
    invoice_entry.catalogue_part, 
    invoice_entry.remarks, 
    invoice_entry.last_lock, 
    IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
    invoice_entry.bulk_lot,
    invoice_entry.old_season,
    invoice_entry.account_concerned,
    invoice_entry.category,
    invoice_entry.chest_name,
    garden_and_company_setup.garden_name, 
    invoice_entry.short_note,
    warehouse_setup.short_key_word,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.catalogue_part', '2nd');
$this->db->order_by('invoice_entry.lot_no ASC');
$lot_query = $this->db->get();

// Start Set lot sequence
$lots = $lot_query->result_array();
if(count($lots) > 0){

$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

// Grouping lots
foreach ($lots as $row2) {
    if (($prev_lot_no !== null && $prev_group !== null) && 
        ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
        // Lot break detected
        $groups[] = $current_group;
        $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

// Start printing groups
foreach ($groups as $single_group) {

    // your variables
    $g_total_kgs = 0;
    $g_net_kgs = 0;
    $g_chest_number = 0;
    $count_last_lot = '';

    $group_head = $single_group[0]; // First row of the group for heading

    // heading echo
    ?>

	<div class="row" data-print="leaf_section2" id='leafprev2_<?php echo $group_head['id']; ?>' title="<?php echo $group_head['id']; ?>">
		<div class="col-sm-1"></div>
		<div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
			<b><?php print $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
			<b><?php print $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
			<b>
				<?php echo $group_head['invoice_group_txt']; ?> <?php echo $group_head['invoice_group']; ?><?php echo (!empty($group_head['invoice_group_upto'])) ? '-'.$group_head['invoice_group_upto'] : ''; ?> 
				<?php print $group_head['category']; ?> &nbsp;&nbsp;
				(<?php print $group_head['chest_name']; ?>)
			</b>
		</div>
		<div class="col-sm-2"></div>
	</div>

	<div class="row" data-print="leaf_section2">
		<div class="col-sm-1"></div>
		<div class="col-sm-1" style="margin-right:-10px;"></div>
		<div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
			<b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
			<b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>
		</div>
	</div>

	<?php
	$targetleafPageNo = null;
	foreach($single_group as $row2){
		if ($targetleafPageNo === null && !empty($row2['page_no'])) {
			$targetleafPageNo = $row2['page_no'];
		}
	?>

	<div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="leaf_section2" id="leafnext2_<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		
		<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
		
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
		<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
		<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
		<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
		
		<?php }else{ ?>
		
		<div class="col-sm-1" id='leaflot2_<?php echo $row2['id']; ?>' title="<?php echo $row2['id']; ?>" style="padding:5px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
		</div>
		<div class="col-sm-1" style="padding:5px;">
			<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
			<?php 
				// Use preg_match_all to find all occurrences of text and numbers
				preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

				// Extract text and numbers from matches
				// $text = implode('', $matches[1]);
				$w_inv_no = implode('', $matches[2]);

				print $w_inv_no; 
			?>
		</div>
		<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
			<?php echo $row2['grade_name']; ?>
		</div>
		<div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
		</div>
		<?php } ?>
		
		<div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
			<?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
		</div>
		<div class="col-sm-2"></div>
	</div>	
	<?php
		$g_total_kgs += $row2['total_kgs'];
		$g_net_kgs += $row2['net_kgs'];
		$g_chest_number += $row2['chest_number'];
		$count_last_lot = $row2['lot_no'];
		
	} // 3rd loop	
	?>
	<div class="row" style="margin-bottom:10px;" data-print="leaf_section2" title="<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
				<div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
				<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
				<div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
				<div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
			</div>
		</div>
		<div class="col-sm-3"></div>
	</div>

	<div class="row" style="margin-bottom:10px;" data-print="leaf_section2">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
		</div>
		<div class="col-sm-3"></div>
	</div>

<?php
} // closing foreach($groups as $single_group)
?>

<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('remarks !=', 'W/D');
	$this->db->where('catalouge_name', 'Leaf');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row" style="margin-bottom:20px;" data-print="leaf_section2">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border: 1px solid black !important; font-size:12px; font-weight: 500;">
			<div class="col-sm-5" style="padding:5px; text-align:right;">Grand Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px;"><?php print number_format($total_leaf_result['total_kgs'],2,'.','') ;?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right;"><?php print number_format($total_leaf_result['net_kgs'],2,'.','') ;?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center;"><?php print $total_leaf_result['number_of_bags'] ;?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<div class="page-break" style="text-align:center; letter-spacing:22px;">END</div>
<?php } // end row count ?>
<?php } // end leaf catalogue secound part ?>

<?php
## ++++++ DUST CATALOGUE FIRST PART +++++
if($catalogue=='Dust' || $catalogue=='Full Catalogue'){
$catalouge_name = "Dust";
?>

<?php
// query lot breakdown
$this->db->select("
    invoice_entry.id, 
    invoice_entry.lot_no, 
    invoice_entry.old_lot_no, 
    invoice_entry.old_sale_no, 
    invoice_entry.ol_os_sc, 
    invoice_entry.w_inv_no, 
    invoice_entry.invoice_group_txt, 
    invoice_entry.invoice_group, 
    invoice_entry.invoice_group_upto, 
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
    invoice_entry.gross_wt_per_bag, 
    invoice_entry.net_wt_per_bag, 
    invoice_entry.total_kgs, 
    invoice_entry.net_kgs, 
    invoice_entry.chest_number, 
    invoice_entry.page_no, 
    invoice_entry.catalogue_part, 
    invoice_entry.remarks, 
    invoice_entry.last_lock, 
    IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
    invoice_entry.bulk_lot,
    invoice_entry.old_season,
    invoice_entry.account_concerned,
    invoice_entry.category,
    invoice_entry.chest_name,
    garden_and_company_setup.garden_name, 
    invoice_entry.short_note,
    warehouse_setup.short_key_word,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.catalogue_part', '1st');
$this->db->order_by('invoice_entry.lot_no ASC');
$lot_query = $this->db->get();

// Start Set lot sequence
$lots = $lot_query->result_array();
if(count($lots) > 0){

$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

// Grouping lots
foreach ($lots as $row2) {
    if (($prev_lot_no !== null && $prev_group !== null) && 
        ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
        // Lot break detected
        $groups[] = $current_group;
        $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

// Start printing groups
foreach ($groups as $single_group) {

    // your variables
    $g_total_kgs = 0;
    $g_net_kgs = 0;
    $g_chest_number = 0;
    $count_last_lot = '';

    $group_head = $single_group[0]; // First row of the group for heading

    // heading echo
    ?>

	<div class="row" data-print="dust_section" id='dustprev_<?php echo $group_head['id']; ?>'>
		<div class="col-sm-1"></div>
		<div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
			<b><?php print $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
			<b><?php print $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
			<b>
				<?php echo $group_head['invoice_group_txt']; ?> <?php echo $group_head['invoice_group']; ?><?php echo (!empty($group_head['invoice_group_upto'])) ? '-'.$group_head['invoice_group_upto'] : ''; ?> 
				<?php print $group_head['category']; ?> &nbsp;&nbsp;
				(<?php print $group_head['chest_name']; ?>)
			</b>
		</div>
		<div class="col-sm-2"></div>
	</div>

	<div class="row" data-print="dust_section">
		<div class="col-sm-1"></div>
		<div class="col-sm-1" style="margin-right:-10px;"></div>
		<div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
			<b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
			<b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>

		</div>
	</div>
	<?php
    foreach ($single_group as $row2) {
    ?>

	<div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="dust_section" id="dustnext_<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		
		<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
		
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
		<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
		<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
		<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
		
		<?php }else{ ?>
		
		<div class="col-sm-1" id='dustlot_<?php echo $row2['id']; ?>' style="padding:5px;  margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
		</div>
		<div class="col-sm-1" style="padding:5px;">
			<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
			<?php 
				// Use preg_match_all to find all occurrences of text and numbers
				preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

				// Extract text and numbers from matches
				// $text = implode('', $matches[1]);
				$w_inv_no = implode('', $matches[2]);

				print $w_inv_no; 
			?>
		</div>
		<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
			<?php echo $row2['grade_name']; ?>
		</div>
		<div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
		</div>
		<?php } ?>
		
		<div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
			<?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
		</div>
		<div class="col-sm-2"></div>
	</div>	
	<?php
		$g_total_kgs += $row2['total_kgs'];
		$g_net_kgs += $row2['net_kgs'];
		$g_chest_number += $row2['chest_number'];
		$count_last_lot = $row2['lot_no'];
	} // 3rd loop	
	?>
	<div class="row" style="margin-bottom:10px;" data-print="dust_section" title="<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
				<div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
				<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
				<div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
				<div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
			</div>
		</div>
		<div class="col-sm-3"></div>
	</div>

	<div class="row" style="margin-bottom:10px;" data-print="dust_section">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
		</div>
		<div class="col-sm-3"></div>
	</div>

<?php
} // closing foreach($groups as $single_group)
?>

<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('remarks !=', 'W/D');
	$this->db->where('catalouge_name', 'Dust');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row targetdustend" style="margin-bottom:20px;" data-print="dust_section">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border: 1px solid black !important;">
			<div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Grand Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px;"><?php print number_format($total_leaf_result['total_kgs'],2,'.','') ;?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php print number_format($total_leaf_result['net_kgs'],2,'.','') ;?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php print $total_leaf_result['number_of_bags'] ;?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<div class="page-break" style="text-align:center; letter-spacing:22px;"><span class="targetdustend2">END</span></div>
<?php } // end row count ?>
<?php } // end dust catalogue first part ?>

<?php
## ++++++ DUST CATALOGUE SECOUND PART +++++
if($catalogue=='Dust' || $catalogue=='Full Catalogue'){
$catalouge_name = "Dust";
?>
<?php
// query lot breakdown
$this->db->select("
    invoice_entry.id, 
    invoice_entry.lot_no, 
    invoice_entry.old_lot_no, 
    invoice_entry.old_sale_no, 
    invoice_entry.ol_os_sc, 
    invoice_entry.w_inv_no, 
    invoice_entry.invoice_group_txt, 
    invoice_entry.invoice_group, 
    invoice_entry.invoice_group_upto, 
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
    invoice_entry.gross_wt_per_bag, 
    invoice_entry.net_wt_per_bag, 
    invoice_entry.total_kgs, 
    invoice_entry.net_kgs, 
    invoice_entry.chest_number, 
    invoice_entry.page_no, 
    invoice_entry.catalogue_part, 
    invoice_entry.remarks, 
    invoice_entry.last_lock, 
    IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
    invoice_entry.bulk_lot,
    invoice_entry.old_season,
    invoice_entry.account_concerned,
    invoice_entry.category,
    invoice_entry.chest_name,
    garden_and_company_setup.garden_name, 
    invoice_entry.short_note,
    warehouse_setup.short_key_word,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.catalogue_part', '2nd');
$this->db->order_by('invoice_entry.lot_no ASC');
$lot_query = $this->db->get();

// Start Set lot sequence
$lots = $lot_query->result_array();
if(count($lots) > 0){

$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

// Grouping lots
foreach ($lots as $row2) {
    if (($prev_lot_no !== null && $prev_group !== null) && 
        ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
        // Lot break detected
        $groups[] = $current_group;
        $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

// Start printing groups
foreach ($groups as $single_group) {

    // your variables
    $g_total_kgs = 0;
    $g_net_kgs = 0;
    $g_chest_number = 0;
    $count_last_lot = '';

    $group_head = $single_group[0]; // First row of the group for heading

    // heading echo
    ?>

	<div class="row" data-print="dust_section2" id='dustprev2_<?php echo $group_head['id']; ?>'>
	<div class="col-sm-1"></div>
	<div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
		<b><?php print $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
		<b><?php print $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
		<b>
			<?php echo $group_head['invoice_group_txt']; ?> <?php echo $group_head['invoice_group']; ?><?php echo (!empty($group_head['invoice_group_upto'])) ? '-'.$group_head['invoice_group_upto'] : ''; ?> 
			<?php print $group_head['category']; ?> &nbsp;&nbsp;
			(<?php print $group_head['chest_name']; ?>)
		</b>
	</div>
	<div class="col-sm-2"></div>
	</div>

	<div class="row" data-print="dust_section2">
		<div class="col-sm-1"></div>
		<div class="col-sm-1" style="margin-right:-10px;"></div>
		<div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
			<b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
			<b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>
		</div>
	</div>
	<?php
	$targetDustPageNo = null;
    foreach ($single_group as $row2) {

		if ($targetDustPageNo === null && !empty($row2['page_no'])) {
			$targetDustPageNo = $row2['page_no'];
		}
	?>

	<div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="dust_section2" id="dustnext2_<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		
		<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
		
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
		<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
		<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
		<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
		
		<?php }else{ ?>
		
		<div class="col-sm-1" id='dustlot2_<?php echo $row2['id']; ?>' style="padding:5px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
		</div>
		<div class="col-sm-1" style="padding:5px;">
			<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
			<?php 
				// Use preg_match_all to find all occurrences of text and numbers
				preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

				// Extract text and numbers from matches
				// $text = implode('', $matches[1]);
				$w_inv_no = implode('', $matches[2]);

				print $w_inv_no; 
			?>
		</div>
		<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
			<?php echo $row2['grade_name']; ?>
		</div>
		<div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
		</div>
		<?php } ?>
		
		<div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
			<?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
		</div>
		<div class="col-sm-2"></div>
	</div>	
	<?php
		$g_total_kgs += $row2['total_kgs'];
		$g_net_kgs += $row2['net_kgs'];
		$g_chest_number += $row2['chest_number'];
		$count_last_lot = $row2['lot_no'];
	} // 3rd loop	
	?>
	<div class="row" style="margin-bottom:10px;" data-print="dust_section2" title="<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
				<div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
				<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
				<div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
				<div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
			</div>
		</div>
		<div class="col-sm-3"></div>
	</div>

	<div class="row" style="margin-bottom:10px;" data-print="dust_section2">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
		</div>
		<div class="col-sm-3"></div>
	</div>

<?php
} // closing foreach($groups as $single_group)
?>

<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('remarks !=', 'W/D');
	$this->db->where('catalouge_name', 'Dust');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row" style="margin-bottom:20px;" data-print="dust_section2">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border: 1px solid black !important; font-size:12px; font-weight: 500;">
			<div class="col-sm-5" style="padding:5px; text-align:right;">Grand Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px;"><?php print number_format($total_leaf_result['total_kgs'],2,'.','') ;?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right;"><?php print number_format($total_leaf_result['net_kgs'],2,'.','') ;?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center;"><?php print $total_leaf_result['number_of_bags'] ;?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<div class="page-break" style="text-align:center; letter-spacing:22px;">END</div>
<?php } // end row count ?>
<?php } // end dust secound first part ?>

<?php
## ++++++ Supplement CATALOGUE FIRST PART +++++
if($catalogue=='Supplement' || $catalogue=='Full Catalogue'){
$catalouge_name = "Supplement";
?>

<?php
// query lot breakdown
$this->db->select("
    invoice_entry.id, 
    invoice_entry.lot_no, 
    invoice_entry.old_lot_no, 
    invoice_entry.old_sale_no, 
    invoice_entry.ol_os_sc, 
    invoice_entry.w_inv_no, 
    invoice_entry.invoice_group_txt, 
    invoice_entry.invoice_group, 
    invoice_entry.invoice_group_upto, 
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
    invoice_entry.gross_wt_per_bag, 
    invoice_entry.net_wt_per_bag, 
    invoice_entry.total_kgs, 
    invoice_entry.net_kgs, 
    invoice_entry.chest_number, 
    invoice_entry.page_no, 
    invoice_entry.catalogue_part, 
    invoice_entry.remarks, 
    invoice_entry.last_lock, 
    IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
    invoice_entry.bulk_lot,
    invoice_entry.old_season,
    invoice_entry.account_concerned,
    invoice_entry.category,
    invoice_entry.chest_name,
    garden_and_company_setup.garden_name, 
    invoice_entry.short_note,
    warehouse_setup.short_key_word,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.catalogue_part', '1st');
$this->db->order_by('invoice_entry.lot_no ASC');
$lot_query = $this->db->get();

// Start Set lot sequence
$lots = $lot_query->result_array();
if(count($lots) > 0){

$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

// Grouping lots
foreach ($lots as $row2) {
    if (($prev_lot_no !== null && $prev_group !== null) && 
        ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
        // Lot break detected
        $groups[] = $current_group;
        $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

// Start printing groups
foreach ($groups as $single_group) {

    // your variables
    $g_total_kgs = 0;
    $g_net_kgs = 0;
    $g_chest_number = 0;
    $count_last_lot = '';

    $group_head = $single_group[0]; // First row of the group for heading

    // heading echo
    ?>

	<div class="row" data-print="sup_section" id='supprev_<?php echo $group_head['id']; ?>'>
		<div class="col-sm-1"></div>
		<div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
			<b><?php print $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
			<b><?php print $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
			<b>
				<?php echo $group_head['invoice_group_txt']; ?> <?php echo $group_head['invoice_group']; ?><?php echo (!empty($group_head['invoice_group_upto'])) ? '-'.$group_head['invoice_group_upto'] : ''; ?> 
				<?php print $group_head['category']; ?> &nbsp;&nbsp;
				(<?php print $group_head['chest_name']; ?>)
			</b>
		</div>
		<div class="col-sm-2"></div>
	</div>

	<div class="row" data-print="sup_section">
		<div class="col-sm-1"></div>
		<div class="col-sm-1" style="margin-right:-10px;"></div>
		<div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
			<b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
			<b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>
		</div>
	</div>

	<?php
    foreach ($single_group as $row2) {
    ?>

	<div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="sup_section" id="supnext_<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		
		<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
		
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
		<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
		<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
		<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
		
		<?php }else{ ?>
		
		<div class="col-sm-1" id='suplot_<?php echo $row2['id']; ?>' style="padding:5px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
		</div>
		<div class="col-sm-1" style="padding:5px;">
			<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
			<?php 
				// Use preg_match_all to find all occurrences of text and numbers
				preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

				// Extract text and numbers from matches
				// $text = implode('', $matches[1]);
				$w_inv_no = implode('', $matches[2]);

				print $w_inv_no; 
			?>
		</div>
		<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
			<?php echo $row2['grade_name']; ?>
		</div>
		<div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
		</div>
		<?php } ?>
		
		<div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
			<?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
		</div>
		<div class="col-sm-2"></div>
	</div>	
	<?php
		$g_total_kgs += $row2['total_kgs'];
		$g_net_kgs += $row2['net_kgs'];
		$g_chest_number += $row2['chest_number'];
		$count_last_lot = $row2['lot_no'];
	} // 3rd loop	
	?>
	<div class="row" style="margin-bottom:10px;" data-print="sup_section" title="<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
				<div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
				<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
				<div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
				<div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
			</div>
		</div>
		<div class="col-sm-3"></div>
	</div>

	<div class="row" style="margin-bottom:10px;" data-print="sup_section">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
		</div>
		<div class="col-sm-3"></div>
	</div>

<?php
} // closing foreach($groups as $single_group)
?>

<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('remarks !=', 'W/D');
	$this->db->where('catalouge_name', 'Supplement');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row targetsupend2" style="margin-bottom:20px;" data-print="sup_section">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border: 1px solid black !important;">
			<div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Grand Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px;"><?php print number_format($total_leaf_result['total_kgs'],2,'.','') ;?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php print number_format($total_leaf_result['net_kgs'],2,'.','') ;?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php print $total_leaf_result['number_of_bags'] ;?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<div class="page-break" style="text-align:center; letter-spacing:22px;"><span class="targetsupend2">END</span></div>
<?php } // end row count ?>
<?php } // end supplement catalogue first part ?>

<?php
## ++++++ Supplement CATALOGUE SECOUND PART +++++
if($catalogue=='Supplement' || $catalogue=='Full Catalogue'){
$catalouge_name = "Supplement";
?>
<?php
// query lot breakdown
$this->db->select("
    invoice_entry.id, 
    invoice_entry.lot_no, 
    invoice_entry.old_lot_no, 
    invoice_entry.old_sale_no, 
    invoice_entry.ol_os_sc, 
    invoice_entry.w_inv_no, 
    invoice_entry.invoice_group_txt, 
    invoice_entry.invoice_group, 
    invoice_entry.invoice_group_upto, 
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
    invoice_entry.gross_wt_per_bag, 
    invoice_entry.net_wt_per_bag, 
    invoice_entry.total_kgs, 
    invoice_entry.net_kgs, 
    invoice_entry.chest_number, 
    invoice_entry.page_no, 
    invoice_entry.catalogue_part, 
    invoice_entry.remarks, 
    invoice_entry.last_lock, 
    IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
    invoice_entry.bulk_lot,
    invoice_entry.old_season,
    invoice_entry.account_concerned,
    invoice_entry.category,
    invoice_entry.chest_name,
    garden_and_company_setup.garden_name, 
    invoice_entry.short_note,
    warehouse_setup.short_key_word,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.catalogue_part', '2nd');
$this->db->order_by('invoice_entry.lot_no ASC');
$lot_query = $this->db->get();

// Start Set lot sequence
$lots = $lot_query->result_array();
if(count($lots) > 0){

$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

// Grouping lots
foreach ($lots as $row2) {
    if (($prev_lot_no !== null && $prev_group !== null) && 
        ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
        // Lot break detected
        $groups[] = $current_group;
        $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

// Start printing groups
foreach ($groups as $single_group) {

    // your variables
    $g_total_kgs = 0;
    $g_net_kgs = 0;
    $g_chest_number = 0;
    $count_last_lot = '';

    $group_head = $single_group[0]; // First row of the group for heading

    // heading echo
    ?>

	<div class="row" data-print="sup_section2" id='supprev2_<?php echo $group_head['id']; ?>'>
		<div class="col-sm-1"></div>
		<div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
			<b><?php print $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
			<b><?php print $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
			<b>
				<?php echo $group_head['invoice_group_txt']; ?> <?php echo $group_head['invoice_group']; ?><?php echo (!empty($group_head['invoice_group_upto'])) ? '-'.$group_head['invoice_group_upto'] : ''; ?> 
				<?php print $group_head['category']; ?> &nbsp;&nbsp;
				(<?php print $group_head['chest_name']; ?>)
			</b>
		</div>
		<div class="col-sm-2"></div>
	</div>

	<div class="row" data-print="sup_section2">
		<div class="col-sm-1"></div>
		<div class="col-sm-1" style="margin-right:-10px;"></div>
		<div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-1"></div>
		<div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
			<b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
			<b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>
		</div>
	</div>
	<?php
	$targetSupplementPageNo = null;

	foreach ($single_group as $row2) {
		if ($targetSupplementPageNo === null && !empty($row2['page_no'])) {
			$targetSupplementPageNo = $row2['page_no'];
		}
	?>

	<div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="sup_section2" id="supnext2_<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		
		<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
		
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px;"></div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
		<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
		<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
		<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
		
		<?php }else{ ?>
		
		<div class="col-sm-1" id='suplot2_<?php echo $row2['id']; ?>' style="padding:5px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
		</div>
		<div class="col-sm-1" style="padding:5px;">
			<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
			<?php 
				// Use preg_match_all to find all occurrences of text and numbers
				preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

				// Extract text and numbers from matches
				// $text = implode('', $matches[1]);
				$w_inv_no = implode('', $matches[2]);

				print $w_inv_no; 
			?>
		</div>
		<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
			<?php echo $row2['grade_name']; ?>
		</div>
		<div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
		</div>
		<?php } ?>
		
		<div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
			<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
		</div>
		<div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
			<?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
		</div>
		<div class="col-sm-2"></div>
	</div>	
	<?php
		$g_total_kgs += $row2['total_kgs'];
		$g_net_kgs += $row2['net_kgs'];
		$g_chest_number += $row2['chest_number'];
		$count_last_lot = $row2['lot_no'];
	} // 3rd loop	
	?>
	<div class="row" style="margin-bottom:10px;" data-print="sup_section2" title="<?php echo $row2['id']; ?>">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
				<div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
				<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
				<div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
				<div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
			</div>
		</div>
		<div class="col-sm-3"></div>
	</div>

	<div class="row" style="margin-bottom:10px;" data-print="sup_section2">
		<div class="col-sm-1"></div>
		<div class="col-sm-8">
			<b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
		</div>
		<div class="col-sm-3"></div>
	</div>

<?php
} // closing foreach($groups as $single_group)
?>

<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('remarks !=', 'W/D');
	$this->db->where('catalouge_name', 'Supplement');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row" style="margin-bottom:20px;" data-print="sup_section2">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border: 1px solid black !important; font-size:12px; font-weight: 500;">
			<div class="col-sm-5" style="padding:5px; text-align:right;">Grand Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px;"><?php print number_format($total_leaf_result['total_kgs'],2,'.','') ;?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right;"><?php print number_format($total_leaf_result['net_kgs'],2,'.','') ;?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center;"><?php print $total_leaf_result['number_of_bags'] ;?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<div class="page-break" style="text-align:center; letter-spacing:22px;">END</div>
<?php } // end row count ?>
<?php } // end supplement catalogue secound part ?>

<?php
## ++++++ LOT WISE FULL CATALOGUE +++++
if($catalogue=='Lot Wise Catalogue'){
?>
<div class="row" style="margin-bottom:20px;" data-print="lot_wise_catalogue">
	<div class="col-sm-1"></div>
	<div class="col-sm-9">
		<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important;">
			<div class="col-sm-3 text-left" style="padding:5px; font-size:12px; margin-right: -20px !important;">Lot</div>
			<div class="col-sm-1 text-center" style="padding:5px; font-size:12px; margin-right: -18px !important;">Invoice <br> No</div>
			<div class="col-sm-3 text-center" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -25px!important;">Grade</div>
			<div class="col-sm-3" style="padding: 5px; font-size: 12px; text-align: center;">
				<span style="display: inline-block; text-align: center;">Grose <br> Each</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="display: inline-block; text-align: center;">Net <br> Each</span>
			</div>
			<div class="col-sm-1 text-center" style="padding:5px; font-size:12px;">Total <br> Net</div>
			<div class="col-sm-1 text-center" style="padding:5px; font-size:12px;">No.of <br> Chest</div>
		</div>
	</div>
	<div class="col-sm-2"></div>
</div>
<?php
// query lot breakdown
$this->db->select("
    invoice_entry.id, 
    invoice_entry.lot_no, 
    invoice_entry.old_lot_no, 
    invoice_entry.old_sale_no, 
    invoice_entry.ol_os_sc, 
    invoice_entry.w_inv_no, 
    invoice_entry.invoice_group_txt, 
    invoice_entry.invoice_group, 
    invoice_entry.invoice_group_upto, 
    CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
    invoice_entry.gross_wt_per_bag, 
    invoice_entry.net_wt_per_bag, 
    invoice_entry.total_kgs, 
    invoice_entry.net_kgs, 
    invoice_entry.chest_number, 
    invoice_entry.page_no, 
    invoice_entry.catalogue_part, 
    invoice_entry.remarks, 
    invoice_entry.last_lock, 
    IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
    invoice_entry.bulk_lot,
    invoice_entry.old_season,
    invoice_entry.account_concerned,
    invoice_entry.category,
    invoice_entry.chest_name,
    garden_and_company_setup.garden_name, 
    invoice_entry.short_note,
    warehouse_setup.short_key_word,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where("invoice_entry.lot_no BETWEEN $lot_from AND $lot_to");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$lot_query = $this->db->get();

// Start Set lot sequence
$lots = $lot_query->result_array();
if(count($lots) > 0){
	
$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

// Grouping lots
foreach ($lots as $row2) {
    if (($prev_lot_no !== null && $prev_group !== null) && 
        ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
        // Lot break detected
        $groups[] = $current_group;
        $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

// Start printing groups
foreach ($groups as $single_group) {

    // your variables
    $g_total_kgs = 0;
    $g_net_kgs = 0;
    $g_chest_number = 0;
    $count_last_lot = '';

    $group_head = $single_group[0]; // First row of the group for heading

    // heading echo
    ?>
    <div class="row" data-print="lot_wise_catalogue" id="lwcprev_<?php echo $group_head['id']; ?>" title="<?php echo $group_head['id']; ?>">
        <div class="col-sm-1"></div>
        <div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
            <b><?php echo $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
            <b><?php echo $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
            <b>
                <?php echo $group_head['invoice_group_txt'] . ' ' . $group_head['invoice_group']; ?>
                <?php echo (!empty($group_head['invoice_group_upto']) ? '-' . $group_head['invoice_group_upto'] : ''); ?>
                <?php echo $group_head['category']; ?>&nbsp;&nbsp;
                (<?php echo $group_head['chest_name']; ?>)
            </b>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="row" data-print="lot_wise_catalogue">
        <div class="col-sm-1"></div>
        <div class="col-sm-1" style="margin-right:-10px;"></div>
        <div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
			<b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
			<b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>
		</div>
    </div>

    <?php
    foreach ($single_group as $row2) {
    ?>
        <div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="lot_wise_catalogue" id="lwcnext_<?php echo $row2['id']; ?>">
            <div class="col-sm-1"></div>

            <?php if ($count_last_lot == $row2['lot_no'] && $row2['bulk_lot'] == 'y') { ?> 
                <div class="col-sm-1" style="padding:5px;"></div>
                <div class="col-sm-1" style="padding:5px;"></div>
                <div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
                <div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
                <div class="col-sm-1" style="padding:5px; text-align:right;"></div>
                <div class="col-sm-2" style="padding:5px; text-align:right;"></div>
            <?php } else { ?>
                <div class="col-sm-1" id='lwclot_<?php echo $row2['id']; ?>' title="<?php echo $row2['id']; ?>" style="padding:5px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?></div>
                <div class="col-sm-1" style="padding:5px;">
                    <?php echo $row2['old_lot_no'] . '/' . $row2['old_sale_no']; ?>
                </div>
                <div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
                    <?php
                    preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);
                    $w_inv_no = implode('', $matches[2]);
                    echo $w_inv_no;
                    ?>
                </div>
                <div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
                    <?php echo $row2['grade_name']; ?>
                </div>
                <div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
                    <?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
                </div>
            <?php } ?>

            <div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
                <?php echo number_format($row2['net_kgs'],1,'.',''); ?>
            </div>
            <div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
                <?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <?php
        $g_total_kgs += $row2['total_kgs'];
        $g_net_kgs += $row2['net_kgs'];
        $g_chest_number += $row2['chest_number'];
        $count_last_lot = $row2['lot_no'];
    }
    ?>

    <div class="row" style="margin-bottom:10px;" data-print="lot_wise_catalogue" title="<?php echo $row2['id']; ?>">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
                <div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
                <div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
                <div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
                <div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>

    <div class="row" style="margin-bottom:10px;" data-print="lot_wise_catalogue">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
        </div>
        <div class="col-sm-3"></div>
    </div>

<?php
} // closing foreach($groups as $single_group)
?>

<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('remarks !=', 'W/D');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where("lot_no BETWEEN $lot_from AND $lot_to");
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row targetleafend" style="margin-bottom:20px;" data-print="lot_wise_catalogue">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border: 1px solid black !important;">
			<div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Grand Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px;"><?php print number_format($total_leaf_result['total_kgs'],2,'.','') ;?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php print number_format($total_leaf_result['net_kgs'],2,'.','') ;?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php print $total_leaf_result['number_of_bags'] ;?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<div class="page-break" style="text-align:center; letter-spacing:22px;"><span>END</span></div>
<?php } // end row count ?>
<?php } // end LOT WISE FULL CATALOGUE ?>


<?php
if($catalogue=='Warehouse wise Catalogue') {
    // Get warehouse ID from request
    
    if($warehouse_id) {
        // Query for warehouse wise catalogue
        $this->db->select("
            invoice_entry.id, 
            invoice_entry.lot_no, 
            invoice_entry.old_lot_no, 
            invoice_entry.old_sale_no, 
            invoice_entry.ol_os_sc, 
            invoice_entry.w_inv_no, 
            invoice_entry.invoice_group_txt, 
            invoice_entry.invoice_group, 
            invoice_entry.invoice_group_upto, 
            CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, 
            invoice_entry.gross_wt_per_bag, 
            invoice_entry.net_wt_per_bag, 
            invoice_entry.total_kgs, 
            invoice_entry.net_kgs, 
            invoice_entry.chest_number, 
            invoice_entry.page_no, 
            invoice_entry.catalogue_part, 
            invoice_entry.remarks, 
            invoice_entry.last_lock, 
            IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, 
            invoice_entry.bulk_lot,
            invoice_entry.old_season,
            invoice_entry.account_concerned,
            invoice_entry.category,
            invoice_entry.chest_name,
            garden_and_company_setup.garden_name, 
            invoice_entry.short_note,
            warehouse_setup.short_key_word,
            warehouse_setup.warehouse_name,
            CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
        "); 
        $this->db->from('invoice_entry');
        $this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
        $this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
        $this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
        $this->db->where('invoice_entry.published', 'Yes');
        $this->db->where('invoice_entry.season', $season);
        $this->db->where('invoice_entry.auction_center', $auction_center);
        $this->db->where('invoice_entry.sale_no', $sale_no);
        $this->db->where('invoice_entry.w_house_id', $warehouse_id);
        $this->db->where('invoice_entry.soft_delete', 1);
        $this->db->order_by('invoice_entry.lot_no ASC');
        $warehouse_query = $this->db->get();
        $warehouse_lots = $warehouse_query->result_array();
        
        if(count($warehouse_lots) > 0) {
            // Display header (same as Lot Wise Catalogue)
            ?>
            <div class="row" style="margin-bottom:20px;" data-print="wh_wise_catalogue">
                <div class="col-sm-1"></div>
                <div class="col-sm-9">
                    <div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important;">
                        <div class="col-sm-3 text-left" style="padding:5px; font-size:12px; margin-right: -20px !important;">Lot</div>
                        <div class="col-sm-1 text-center" style="padding:5px; font-size:12px; margin-right: -18px !important;">Invoice <br> No</div>
                        <div class="col-sm-3 text-center" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -25px!important;">Grade</div>
                        <div class="col-sm-3" style="padding: 5px; font-size: 12px; text-align: center;">
                            <span style="display: inline-block; text-align: center;">Grose <br> Each</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="display: inline-block; text-align: center;">Net <br> Each</span>
                        </div>
                        <div class="col-sm-1 text-center" style="padding:5px; font-size:12px;">Total <br> Net</div>
                        <div class="col-sm-1 text-center" style="padding:5px; font-size:12px;">No.of <br> Chest</div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <?php

            // Group lots by sequence_group (same as Lot Wise Catalogue)
            $groups = [];
            $current_group = [];
            $prev_lot_no = null;
            $prev_group = null;

            foreach ($warehouse_lots as $row2) {
                if (($prev_lot_no !== null && $prev_group !== null) && 
                    ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
                    $groups[] = $current_group;
                    $current_group = [];
                }
                $current_group[] = $row2;
                $prev_lot_no = $row2['lot_no'];
                $prev_group = $row2['sequence_group'];
            }

            if (!empty($current_group)) {
                $groups[] = $current_group;
            }

            // Start printing groups (same as Lot Wise Catalogue)
            foreach ($groups as $single_group) {
                $g_total_kgs = 0;
                $g_net_kgs = 0;
                $g_chest_number = 0;
                $count_last_lot = '';

                $group_head = $single_group[0]; // First row of the group for heading

                // heading echo - modified to show warehouse name in same style
                ?>
                <div class="row" data-print="wh_wise_catalogue" id="lwcprev_<?php echo $group_head['id']; ?>" title="<?php echo $group_head['id']; ?>">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-9" style="border-bottom: 1px dashed black !important; padding-left:0px; white-space:nowrap; text-align:left; font-size:13px; text-transform:uppercase;">
                        <b><?php echo $group_head['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
                        <b><?php echo $group_head['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
                        <b>
                            <?php echo $group_head['invoice_group_txt'] . ' ' . $group_head['invoice_group']; ?>
                            <?php echo (!empty($group_head['invoice_group_upto']) ? '-' . $group_head['invoice_group_upto'] : ''); ?>
                            <?php echo $group_head['category']; ?>&nbsp;&nbsp;
                            (<?php echo $group_head['chest_name']; ?>)
                        </b>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="row" data-print="wh_wise_catalogue">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1" style="margin-right:-10px;"></div>
                    <div class="col-sm-1" style="font-size:11px; white-space:nowrap;">O/L &nbsp; O/S</div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-3" style="font-size:13px; white-space:nowrap;">
                        <b><?php echo ($group_head['account_concerned'] == 'Yes' ? 'A/C CONCERNED' : ''); ?></b>
                        <b><?php echo ($season == $group_head['old_season'] ? '' : 'SEASON ' . $group_head['old_season']); ?></b>
                    </div>
                </div>

                <?php
                foreach ($single_group as $row2) {
                ?>
                    <div class="row" style="padding: 5px 0; font-size:12px; font-weight: 500;" data-print="wh_wise_catalogue" id="lwcnext_<?php echo $row2['id']; ?>">
                        <div class="col-sm-1"></div>

                        <?php if ($count_last_lot == $row2['lot_no'] && $row2['bulk_lot'] == 'y') { ?> 
                            <div class="col-sm-1" style="padding:5px;"></div>
                            <div class="col-sm-1" style="padding:5px;"></div>
                            <div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
                            <div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
                            <div class="col-sm-1" style="padding:5px; text-align:right;"></div>
                            <div class="col-sm-2" style="padding:5px; text-align:right;"></div>
                        <?php } else { ?>
                            <div class="col-sm-1" id='lwclot_<?php echo $row2['id']; ?>' title="<?php echo $row2['id']; ?>" style="padding:5px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?></div>
                            <div class="col-sm-1" style="padding:5px;">
                                <?php echo $row2['old_lot_no'] . '/' . $row2['old_sale_no']; ?>
                            </div>
                            <div class="col-sm-1" style="padding:5px; text-transform:uppercase; margin-right: -10px !important;">
                                <?php
                                preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);
                                $w_inv_no = implode('', $matches[2]);
                                echo $w_inv_no;
                                ?>
                            </div>
                            <div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; margin-right: -40px !important;">
                                <?php echo $row2['grade_name']; ?>
                            </div>
                            <div class="col-sm-2" style="padding:5px; text-align:right; white-space:nowrap;">
                                <?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
                            </div>
                        <?php } ?>

                        <div class="col-sm-1" style="padding:5px; text-align:right; white-space:nowrap;">
                            <?php echo number_format($row2['net_kgs'],1,'.',''); ?>
                        </div>
                        <div class="col-sm-1" style="padding:5px; text-align:center; width:60px !important;">
                            <?php echo $row2['chest_number']; ?> <small><?php echo ($row2['remarks']=='W/D') ? $row2['remarks'] : ''; ?></small>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <?php
                    $g_total_kgs += $row2['total_kgs'];
                    $g_net_kgs += $row2['net_kgs'];
                    $g_chest_number += $row2['chest_number'];
                    $count_last_lot = $row2['lot_no'];
                }
                ?>

                <div class="row" style="margin-bottom:10px;" data-print="wh_wise_catalogue" title="<?php echo $row2['id']; ?>">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-8">
                        <div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important; font-size:12px; font-weight: 500;">
                            <div class="col-sm-5" style="padding:5px; text-align:right;">Invoice Total:</div>
                            <div class="col-sm-4" style="padding:5px; text-align:center; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
                            <div class="col-sm-2" style="padding:5px; text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
                            <div class="col-sm-1" style="padding:5px; text-align:center;"><?php echo $g_chest_number; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>

                <div class="row" style="margin-bottom:10px;" data-print="wh_wise_catalogue">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-8">
                        <b style="font-size:13px;"><?php echo $row2['short_note']; ?></b>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
            <?php
            } // closing foreach($groups as $single_group)

            // Grand total for the warehouse
            $this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
            $this->db->from('invoice_entry');
            $this->db->where('published', 'Yes');
            $this->db->where('remarks !=', 'W/D');
            $this->db->where('season', $season);
            $this->db->where('auction_center', $auction_center);
            $this->db->where('w_house_id', $warehouse_id);
            $this->db->where('sale_no', $sale_no);
            $this->db->where('soft_delete', 1);
            $this->db->order_by('lot_no ASC');
            $query = $this->db->get();
            $total_warehouse_result = $query->row_array();
            ?>
            <div class="row targetleafend" style="margin-bottom:20px;" data-print="wh_wise_catalogue">
                <div class="col-sm-1"></div>
                <div class="col-sm-8">
                    <div class="row" style="border: 1px solid black !important;">
                        <div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Warehouse Total:</div>
                        <div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px;"><?php print number_format($total_warehouse_result['total_kgs'],2,'.','') ;?></div>
                        <div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php print number_format($total_warehouse_result['net_kgs'],2,'.','') ;?></div>
                        <div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php print $total_warehouse_result['number_of_bags'] ;?></div>
                    </div>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="page-break" style="text-align:center; letter-spacing:22px;"><span>END</span></div>
            <?php
        } else {
            echo "<div class='alert alert-info'>No lots found for the selected warehouse.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Please select a warehouse.</div>";
    }
}
?>
</div> 
<!-- /container -->

<script>
	
// This content show only when Full Catalogue print
<?php if($catalogue=='Full Catalogue'){ ?>
// Update the total values using JavaScript
document.getElementById('totalBags').textContent = '<?php echo $number_of_bags; ?>';
document.getElementById('totalKgs').textContent = '<?php echo number_format($net_kgs, 2, '.', ''); ?>';

// valuation section
var staticHeight = 0;
var sl=1;
$('[data-print=valuation_section]').each(function() {
	
	if(staticHeight==0){
		var page_no=sl++;
		$(this).before('<div class="row" style="margin-top:30px;"><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:15px;"><?php print $website_title; ?></b></div><div class="form group col-sm-6 text-left" style="font-size:13px; text-transform: uppercase;"><?php print $owner_short_address; ?></div><div class="form group col-sm-2 text-right"><b style="text-transform: uppercase; font-size:13px;">PAGE NO: ' + page_no + '</b></div></div><div class="row" style="margin-top:10px;"><div class="col-sm-12" style="text-transform: uppercase; font-size:13px; font-weight:bold; border-bottom:1px solid black;">VALUATION FOR SALE.&nbsp;<?php print $sale_no; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $auction_center; ?>&nbsp;&nbsp;&nbsp;&nbsp;SEASON&nbsp;&nbsp;&nbsp;&nbsp;<?php print $season; ?>&nbsp;&nbsp;&nbsp;&nbsp;TO BE HELD ON&nbsp;&nbsp;&nbsp;&nbsp;<?php print date('M-d-Y', strtotime($sale_date)); ?></div></div>');
	}
	
    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 850;
    console.log(staticHeight);

    if (staticHeight > pageHeight) {
        $(this).after(' <div class="page-break" style="text-align:right;"></div> ');
        staticHeight = 0;
    }

});
<?php } ?>

// leaf section first part
var staticHeight = 0;
var sl = 1;
var currentPage = 1;

$('[data-print=leaf_section]').each(function () {

    if (staticHeight == 0) {
		var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="leafabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">LEAF&nbsp;&nbsp;<a href="javascript:void(0);" data-page-no="' + page_no + '" data-lot-section="leaflot" onclick="assignPageTolots(' + page_no + ', \'leaflot\')">PAGE NO: ' + page_no + '</a></b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="leafol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedleafprev_' + page_no + '"></span><span style="color:green;" id="elementSelectedleafnext_' + page_no + '"></span></div></div>');
        currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('leafprev_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('leafnext_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('leafabc_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any leafprev_ elements between leafabc_ and leafnext_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('leafprev_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no leafprev_ elements between leafabc_ and leafnext_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='leaflot_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelectedleafprev_' + page_no);
					var nextElement = document.getElementById('elementSelectedleafnext_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the leafol_div
						document.getElementById('leafol_div'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the leafol_div
					document.getElementById('leafol_div'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the leafol_div
				document.getElementById('leafol_div'+ page_no).style.display = 'none';
			}
		});
    }

	// Assign page number to all leaflot_ children inside it
	$(this).find("div[id^='leaflot_']").attr('data-page', currentPage);

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 920;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});

<?php if(!empty($targetleafPageNo) && $targetleafPageNo > 0) { ?>
$('.targetleafend').hide();
$('.targetleafend2').hide();

// leaf section secound part
var staticHeight = 0;
var targetPageNo = <?php echo $targetleafPageNo; ?>;
var sl = targetPageNo;
var currentPage = targetPageNo;

$('[data-print=leaf_section2]').each(function () {

    if (staticHeight == 0) {
		var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="leafabc2_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">LEAF&nbsp;&nbsp;<a href="javascript:void(0);" data-page-no="' + page_no + '" data-lot-section="leaflot2" onclick="assignPageTolots(' + page_no + ', \'leaflot2\')">PAGE NO: ' + page_no + '</a></b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="leafol_div2' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedleafprev2_' + page_no + '"></span><span style="color:green;" id="elementSelectedleafnext2_' + page_no + '"></span></div></div>');
        currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('leafprev2_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('leafnext2_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('leafabc2_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any leafprev2_ elements between leafabc2_ and leafnext2_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('leafprev2_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no leafprev2_ elements between leafabc2_ and leafnext2_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='leaflot2_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelectedleafprev2_' + page_no);
					var nextElement = document.getElementById('elementSelectedleafnext2_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the leafol_div2
						document.getElementById('leafol_div2'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the leafol_div2
					document.getElementById('leafol_div2'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the leafol_div2
				document.getElementById('leafol_div2'+ page_no).style.display = 'none';
			}
		});

    }

	// Assign page number to all leaflot2_ children inside it
	$(this).find("div[id^='leaflot2_']").attr('data-page', currentPage);

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 920;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});
<?php } ?>

// dust section firt part
var staticHeight = 0;
var sl = 1;
var currentPage = 1;

$('[data-print=dust_section]').each(function () {

    if (staticHeight == 0) {
        var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="dustabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">DUST&nbsp;&nbsp;<a href="javascript:void(0);" data-page-no="' + page_no + '" data-lot-section="dustlot" onclick="assignPageTolots(' + page_no + ', \'dustlot\')">PAGE NO: ' + page_no + '</a></b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="dustol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelecteddustprev_' + page_no + '"></span><span style="color:green;" id="elementSelecteddustnext_' + page_no + '"></span></div></div>');
		
        currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('dustprev_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('dustnext_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('dustabc_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any dustprev_ elements between dustabc_ and dustnext_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('dustprev_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no dustprev_ elements between dustabc_ and dustnext_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='dustlot_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelecteddustprev_' + page_no);
					var nextElement = document.getElementById('elementSelecteddustnext_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the dustol_div
						document.getElementById('dustol_div'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the dustol_div
					document.getElementById('dustol_div'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the dustol_div
				document.getElementById('dustol_div'+ page_no).style.display = 'none';
			}
		});

    }

	// Assign page number to all dustlot_ children inside it
	$(this).find("div[id^='dustlot_']").attr('data-page', currentPage);

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 920;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});

<?php if(!empty($targetDustPageNo) && $targetDustPageNo > 0) { ?>
$('.targetdustend').hide();
$('.targetdustend2').hide();

// dust section secound part
var staticHeight = 0;
var targetPageNo = <?php echo $targetDustPageNo; ?>;
var sl = targetPageNo;
var currentPage = targetPageNo;

$('[data-print=dust_section2]').each(function () {

	if (staticHeight == 0) {
		var page_no = sl++;
		$(this).before('<div class="row" style="margin-bottom:15px;" id="dustabc2_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">DUST&nbsp;&nbsp;<a href="javascript:void(0);" data-page-no="' + page_no + '" data-lot-section="dustlot2" onclick="assignPageTolots(' + page_no + ', \'dustlot2\')">PAGE NO: ' + page_no + '</a></b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="dustol_div2' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelecteddustprev2_' + page_no + '"></span><span style="color:green;" id="elementSelecteddustnext2_' + page_no + '"></span></div></div>');
		
		currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('dustprev2_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('dustnext2_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('dustabc2_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any dustprev2_ elements between dustabc2_ and dustnext2_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('dustprev2_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no dustprev2_ elements between dustabc2_ and dustnext2_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='dustlot2_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelecteddustprev2_' + page_no);
					var nextElement = document.getElementById('elementSelecteddustnext2_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the dustol_div2
						document.getElementById('dustol_div2'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the dustol_div2
					document.getElementById('dustol_div2'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the dustol_div2
				document.getElementById('dustol_div2'+ page_no).style.display = 'none';
			}
		});

	}

	// Assign page number to all dustlot2_ children inside it
	$(this).find("div[id^='dustlot2_']").attr('data-page', currentPage);

	staticHeight += $(this).filter(':visible').outerHeight(true);
	var pageHeight = 920;

	if (staticHeight > pageHeight) {
		$(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
		staticHeight = 0;
	}

});
<?php } ?>

// sup section first part
var staticHeight = 0;
var sl = 1;
var currentPage = 1;

$('[data-print=sup_section]').each(function () {

    if (staticHeight == 0) {
        var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="supabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><a href="javascript:void(0);" data-page-no="' + page_no + '" data-lot-section="suplot" onclick="assignPageTolots(' + page_no + ', \'suplot\')">Supplement: ' + page_no + '</a></b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="supol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedsupprev_' + page_no + '"></span><span style="color:green;" id="elementSelectedsupnext_' + page_no + '"></span></div></div>');
        currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('supprev_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('supnext_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('supabc_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any supprev_ elements between supabc_ and supnext_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('supprev_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no supprev_ elements between supabc_ and supnext_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='suplot_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelectedsupprev_' + page_no);
					var nextElement = document.getElementById('elementSelectedsupnext_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the supol_div
						document.getElementById('supol_div'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the supol_div
					document.getElementById('supol_div'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the supol_div
				document.getElementById('supol_div'+ page_no).style.display = 'none';
			}
		});

    }

	// Assign page number to all suplot_ children inside it
	$(this).find("div[id^='suplot_']").attr('data-page', currentPage);

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 920;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});

<?php if(!empty($targetSupplementPageNo) && $targetSupplementPageNo > 0) { ?>
$('.targetsupend').hide();
$('.targetsupend2').hide();

// sup section secound part
var staticHeight = 0;
var targetPageNo = <?php echo $targetSupplementPageNo; ?>;
var sl = targetPageNo;
var currentPage = targetPageNo;

$('[data-print=sup_section2]').each(function () {

	if (staticHeight == 0) {
		var page_no = sl++;
		$(this).before('<div class="row" style="margin-bottom:15px;" id="supabc2_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><a href="javascript:void(0);" data-page-no="' + page_no + '" data-lot-section="suplot2" onclick="assignPageTolots(' + page_no + ', \'suplot2\')">Supplement: ' + page_no + '</a></b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="supol_div2' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedsupprev2_' + page_no + '"></span><span style="color:green;" id="elementSelectedsupnext2_' + page_no + '"></span></div></div>');
		currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('supprev2_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('supnext2_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('supabc2_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any supprev2_ elements between supabc2_ and supnext2_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('supprev2_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no supprev2_ elements between supabc2_ and supnext2_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='suplot2_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelectedsupprev2_' + page_no);
					var nextElement = document.getElementById('elementSelectedsupnext2_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the supol_div2
						document.getElementById('supol_div2'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the supol_div2
					document.getElementById('supol_div2'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the supol_div2
				document.getElementById('supol_div2'+ page_no).style.display = 'none';
			}
		});

	}

	// Assign page number to all suplot2_ children inside it
	$(this).find("div[id^='suplot2_']").attr('data-page', currentPage);

	staticHeight += $(this).filter(':visible').outerHeight(true);
	var pageHeight = 920;

	if (staticHeight > pageHeight) {
		$(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
		staticHeight = 0;
	}

});
<?php } ?>

// Assign page numbers to lot
function assignPageTolots(pageNo, lotsection) {
	var lotArray = [];

	// Collect lot IDs based on lotsection type
	if(lotsection=='leaflot'){

		$("div[id^='leaflot_'][data-page='" + pageNo + "']").each(function () {
			var id = $(this).attr("id").split("_")[1];
			lotArray.push(id);
		});

	}else if(lotsection=='leaflot2'){

		$("div[id^='leaflot2_'][data-page='" + pageNo + "']").each(function () {
			var id = $(this).attr("id").split("_")[1];
			lotArray.push(id);
		});

	}else if(lotsection=='dustlot'){

		$("div[id^='dustlot_'][data-page='" + pageNo + "']").each(function () {
			var id = $(this).attr("id").split("_")[1];
			lotArray.push(id);
		});

	}else if(lotsection=='dustlot2'){

		$("div[id^='dustlot2_'][data-page='" + pageNo + "']").each(function () {
			var id = $(this).attr("id").split("_")[1];
			lotArray.push(id);
		});

	}else if(lotsection=='suplot'){

		$("div[id^='suplot_'][data-page='" + pageNo + "']").each(function () {
			var id = $(this).attr("id").split("_")[1];
			lotArray.push(id);
		});

	}else if(lotsection=='suplot2'){

		$("div[id^='suplot2_'][data-page='" + pageNo + "']").each(function () {
			var id = $(this).attr("id").split("_")[1];
			lotArray.push(id);
		});

	}

	// console.log("Page: " + pageNo + ' Section: ' + lotsection);
	// console.log("Found lotArray IDs:", lotArray);

	// If lots found, proceed with Ajax POST
	if (lotArray.length > 0) {
		$.post("<?php echo site_url('MY_Controller/set_catalogue_index'); ?>", {
            page: pageNo,
            lotArray: lotArray
        }, function (response) {

            // Parse response and handle success
            if (response.success === 1) {
                alert("Page " + pageNo + " assigned successfully!");

                // Change the link style, disable it, and update its text
				const link = $('a[data-page-no="' + pageNo + '"][data-lot-section="' + lotsection + '"]');

				link.css({
					"color": "green",
					"pointer-events": "none",
					"font-weight": "bold",
					"text-decoration": "none"
				});
				link.html("PAGE NO: " + pageNo);

            } else {
                alert("Failed to assign page. Please try again.");
            }

        }, 'json');
    } else {
        alert("No lotArray found for Page " + pageNo);
    }
}


// Lot Wise Catalogue
var staticHeight = 0;
var sl = 1;
var currentPage = 1;

$('[data-print=lot_wise_catalogue]').each(function () {

    if (staticHeight == 0) {
		var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="lwcabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">PAGE NO: ' + page_no + '</b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="lwcol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedlwcprev_' + page_no + '"></span><span style="color:green;" id="elementSelectedlwcnext_' + page_no + '"></span></div></div>');
        currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('lwcprev_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('lwcnext_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('lwcabc_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any lwcprev_ elements between lwcabc_ and lwcnext_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('lwcprev_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no lwcprev_ elements between lwcabc_ and lwcnext_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='lwclot_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelectedlwcprev_' + page_no);
					var nextElement = document.getElementById('elementSelectedlwcnext_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the lwcol_div
						document.getElementById('lwcol_div'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the lwcol_div
					document.getElementById('lwcol_div'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the lwcol_div
				document.getElementById('lwcol_div'+ page_no).style.display = 'none';
			}
		});
    }

	// Assign page number to all lwclot_ children inside it
	$(this).find("div[id^='lwclot_']").attr('data-page', currentPage);

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 870;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});

$('[data-print=wh_wise_catalogue]').each(function () {

    if (staticHeight == 0) {
		var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="lwcabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print $website_title; ?></b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">PAGE NO: ' + page_no + '</b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="lwcol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedlwcprev_' + page_no + '"></span><span style="color:green;" id="elementSelectedlwcnext_' + page_no + '"></span></div></div>');
        currentPage = page_no;

		// Element repeat system
		function hasId(el) {
			return el && el.id !== undefined && el.id !== '';
		}

		function getPrevious(el) {
			var previous = el.previousElementSibling;
			while (previous && (!hasId(previous) || !previous.id.startsWith('lwcprev_'))) {
				previous = previous.previousElementSibling;
			}
			return previous || (el.parentElement && el.parentElement.previousElementSibling && el.parentElement.previousElementSibling.lastElementChild);
		}

		function getNext(el) {
			var next = el.nextElementSibling;
			while (next && (!hasId(next) || !next.id.startsWith('lwcnext_'))) {
				next = next.nextElementSibling;
			}
			return next || (el.parentElement && el.parentElement.nextElementSibling && el.parentElement.nextElementSibling.firstElementChild);
		}

		document.addEventListener('DOMContentLoaded', function () {
			var el = document.getElementById('lwcabc_' + page_no);
			var prev = getPrevious(el);
			var next = getNext(el);

			// Check if there are any lwcprev_ elements between lwcabc_ and lwcnext_
			var hasPrevBetween = false;
			var sibling = el.nextElementSibling;
			while (sibling && sibling !== next) {
				if (sibling.id.startsWith('lwcprev_')) {
					hasPrevBetween = true;
					break;
				}
				sibling = sibling.nextElementSibling;
			}

			// If there are no lwcprev_ elements between lwcabc_ and lwcnext_, execute the condition
			if (!hasPrevBetween) {
				var lotElement = next.querySelector("div[id^='lwclot_']");
				var lotId = lotElement ? parseInt(lotElement.innerHTML) : NaN; // Parse the lot ID as an integer

				if (Number.isInteger(lotId)) {
					var prevElement = document.getElementById('elementSelectedlwcprev_' + page_no);
					var nextElement = document.getElementById('elementSelectedlwcnext_' + page_no);

					if (prevElement && nextElement) {
						// Get the direct values without the count
						var prevValue = prev ? prev.innerHTML : '';
						var nextValue = next ? next.innerHTML : '';

						// Set prevElement.innerHTML
						// prevElement.innerHTML = prevValue + '#' + lotId;
						prevElement.innerHTML = prevValue;
						
						// Show the lwcol_div
						document.getElementById('lwcol_div'+ page_no).style.display = 'block';
					}
				} else {
					// Hide the lwcol_div
					document.getElementById('lwcol_div'+ page_no).style.display = 'none';
				}
			} else {
				// Hide the lwcol_div
				document.getElementById('lwcol_div'+ page_no).style.display = 'none';
			}
		});
    }

	// Assign page number to all lwclot_ children inside it
	$(this).find("div[id^='lwclot_']").attr('data-page', currentPage);

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 870;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});
</script>

</body>
</html>