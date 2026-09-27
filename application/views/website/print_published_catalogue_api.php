<?php 
// get html value
$season = (!empty($this->input->post('season_name', TRUE))) ? $this->input->post('season_name', TRUE) : '0';
$auction_center = (!empty($this->input->post('auction_center', TRUE))) ? $this->input->post('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->post('sale_no', TRUE))) ? $this->input->post('sale_no', TRUE) : '';
$catalogue = (!empty($this->input->post('catalogue', TRUE))) ? $this->input->post('catalogue', TRUE) : '';
$define_brokers_id = 6;
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <title>Catalogue Sale No. - <?php print $sale_no ; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<!-- jQuery and Bootstrap JS (from CDN) -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<!-- Bootstrap CSS (from CDN) -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-glyphicons/1.0.0/bootstrap-glyphicons.css">

	<link rel="icon" href="https://www.unitybrokersltd.com/assets/img/favicon.png">
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
}
</style>
</head>

<body>
<div class="container">

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
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
<div class="row">
	<div class="form-gorup col-sm-12">
		<img src="https://www.unitybrokersltd.com/api/2.jpg" width="100%;" height="192" />
	</div>
</div>

<div class="row" style="margin-left:40px;">
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
/*
## ++++++++++++ START INDEX PAGE ++++++++++++++
$this->db->select("garden_and_company_setup.garden_name, GROUP_CONCAT(DISTINCT invoice_entry.page_no) AS lot_page_no, invoice_entry.catalouge_name, invoice_entry.sale_no, invoice_entry.season"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
($catalogue=='Full Catalogue') ? "" : $this->db->where('catalouge_name', $catalogue);
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id, invoice_entry.catalouge_name');
$this->db->order_by('garden_and_company_setup.garden_name ASC, invoice_entry.lot_no ASC');
$query = $this->db->get();
$index_result = $query->result_array();
if(count($index_result) > 0){	
?>
<div class="row">
<div class="col-sm-12">
	<h4 style="text-align:center;"><b><i>INDEX OF SALE.<?php print $sale_no; ?></i></b></h4>
	<p style="text-align:center; font-size:15px;"><b><i><u>SEASON: (<?php print $season; ?>)</u></i></b></p>
</div>
</div> 

<div class="row">
<div class="col-sm-6">
<table class="table table-bordered">
<thead>
<tr>
	<th colspan="2" style="text-align:center">Leaf</th>
</tr>
<tr>
	<th style="text-align:left">Garden Name</th>
	<th style="text-align:center">Page No.</th>
</tr>
</thead>
<tbody>
<?php
foreach($index_result as $row1){
if($row1['catalouge_name'] == 'Leaf'){
?>

<tr>
	<td><b><?php echo $row1['garden_name']; ?></b></td> 
	<td style="text-align:center;"><b><?php echo $row1['lot_page_no']; ?></b></td>
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
	<th colspan="2" style="text-align:center">Dust</th>
</tr>
<tr>
	<th style="text-align:left;">Garden Name</th>
	<th style="text-align:center;">Page No.</th>
</tr>
</thead>
<tbody>
<?php
foreach($index_result as $row1){
if($row1['catalouge_name'] == 'Dust'){
?>

<tr>
	<td><b><?php echo $row1['garden_name']; ?></b></td> 
	<td style="text-align:center;"><b><?php echo $row1['lot_page_no']; ?></b></td>
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

<div class="col-sm-12">
<table class="table table-bordered">
<thead>
<tr>
	<th colspan="2" style="text-align:center">Suppliment</th>
</tr>
<tr>
	<th style="text-align:left;">Garden Name</th>
	<th style="text-align:center;">Page No.</th>
</tr>
</thead>
<tbody>
<?php
foreach($index_result as $row1){
if($row1['catalouge_name'] == 'Supplement'){
?>

<tr>
	<td><b><?php echo $row1['garden_name']; ?></b></td> 
	<td style="text-align:center;"><b><?php echo $row1['lot_page_no']; ?></b></td>
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
*/
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
$row_limit_pp = 48;
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
	<td style="text-align:center">LOT</td>
	<td style="text-align:right">VALUE</td>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="2"></td></tr>
<!-- This row is only used for two row spacing -->
<?php } ?>
<tr>
	<td style="text-align:center; font-size:13px;"><?php echo $row1['lot_no']; ?></td> 
	<td style="text-align:right; font-size:13px;"><?php echo number_format($row1['valuation'],2,'.',''); ?> </td>
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
## ++++++ LEAF CATALOGUE +++++
if($catalogue=='Leaf' || $catalogue=='Full Catalogue'){
	
$catalouge_name = "Leaf";
$this->db->select("invoice_entry.id, invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.chest_name, invoice_entry.category, GROUP_CONCAT(invoice_entry.w_inv_no) AS w_inv_no, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto, invoice_entry.old_season, invoice_entry.account_concerned, warehouse_setup.warehouse_name, warehouse_setup.short_key_word, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
$leaf_catalogue = $query->result_array();
if(count($leaf_catalogue) > 0){
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

<?php foreach($leaf_catalogue AS $row1){ ?>

<div class="row" data-print="leaf_section" id='leafprev_<?php echo $row1['id']; ?>' title="<?php echo $row1['id']; ?>">
<div class="col-sm-1"></div>
<div class="col-sm-8" style="border-bottom: 1px dashed black !important; padding-left:0px;">
    <b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
	<b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
	<b style="font-size:12px; text-align:left; text-transform:uppercase;">
		<?php echo $row1['invoice_group_txt']; ?> <?php echo $row1['invoice_group']; ?><?php echo (!empty($row1['invoice_group_upto'])) ? '-'.$row1['invoice_group_upto'] : ''; ?> 
		<?php print $row1['category']; ?> &nbsp;&nbsp;
		(<?php print $row1['chest_name']; ?>)
	</b>
</div>
<div class="col-sm-3">
	<b style="font-size:10px; white-space:nowrap;"><?php print ($row1['account_concerned']=='Yes') ? 'A/C CONCERNED' : ''; ?></b>

	<b style="font-size:10px; white-space:nowrap;" title="G # <?php print $row1['group_no']; ?>"><?php print ($season==$row1['old_season']) ? '' : 'Season '.$row1['old_season']; ?></b>
</div>
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
	<div class="col-sm-3"></div>
</div>
<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$tpinv_no = 0;
$count_last_lot = '';

// query lot breakdown
$this->db->select("invoice_entry.id, invoice_entry.lot_no, invoice_entry.old_lot_no, invoice_entry.old_sale_no, invoice_entry.ol_os_sc, invoice_entry.w_inv_no, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, invoice_entry.gross_wt_per_bag, invoice_entry.net_wt_per_bag, invoice_entry.total_kgs, invoice_entry.net_kgs, invoice_entry.chest_number, invoice_entry.remarks, invoice_entry.last_lock, IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg, invoice_entry.bulk_lot "); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.w_house_id',$row1['w_house_id']);
$this->db->where('invoice_entry.invoice_group_txt',$row1['invoice_group_txt']);
$this->db->where('invoice_entry.invoice_group',$row1['invoice_group']);
$this->db->where('invoice_entry.invoice_group_upto',$row1['invoice_group_upto']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC, invoice_entry.id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<?php if($row2['last_lock']=='repeat'){ ?>
<div class="row" data-print="leaf_section">
<div class="col-sm-1"></div>
<div class="col-sm-8" style="border-bottom: 1px dashed black !important; padding-left:0px;">
    <b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
	<b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
	<b style="font-size:12px; text-align:left; text-transform:uppercase;">
		<?php echo $row1['invoice_group_txt']; ?> <?php echo $row1['invoice_group']; ?><?php echo (!empty($row1['invoice_group_upto'])) ? '-'.$row1['invoice_group_upto'] : ''; ?> 
		<?php print $row1['category']; ?> &nbsp;&nbsp;
		(<?php print $row1['chest_name']; ?>)
	</b>
</div>
<div class="col-sm-3"></div>
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
	<div class="col-sm-3"></div>
</div>
<?php } ?>

<div class="row" style="padding: 5px 0" data-print="leaf_section" id="leafnext_<?php echo $row2['id']; ?>">
	<div class="col-sm-1"></div>
	
	<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
	
	<div class="col-sm-1" style="padding:5px;"></div>
	<div class="col-sm-1" style="padding:5px;"></div>
	<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
	<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
	<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
	<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
	
	<?php }else{ ?>
	
	<div class="col-sm-1" id='leaflot_<?php echo $row2['id']; ?>' title="<?php echo $row2['id']; ?>" style="padding:5px; font-size:12px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
	</div>
	<div class="col-sm-1" style="padding:5px; font-size:12px;">
		<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
	</div>
	<div class="col-sm-1" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -10px !important;">
		<?php 
			// Use preg_match_all to find all occurrences of text and numbers
			preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

			// Extract text and numbers from matches
			// $text = implode('', $matches[1]);
			$w_inv_no = implode('', $matches[2]);

			print $w_inv_no; 
		?>
	</div>
	<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -40px !important;">
		<?php echo $row2['grade_name']; ?>
	</div>
	<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px; white-space:nowrap;">
		<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
	</div>
	<?php } ?>
	
	<div class="col-sm-1" style="padding:5px; text-align:right; font-size:12px; white-space:nowrap;">
		<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
	</div>
	<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px; width:60px !important;">
		<?php echo $row2['chest_number']; ?>
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
<div class="row" style="margin-bottom:20px;" data-print="leaf_section" title="<?php echo $row1['id']; ?>">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important;">
			<div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Invoice Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php echo $g_chest_number; ?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<?php } // second loop ?>
<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('catalouge_name', 'Leaf');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row" style="margin-bottom:20px;" data-print="leaf_section">
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
<div class="page-break" style="text-align:center; letter-spacing:22px;">END</div>
<?php } // group foreach ?>
<?php } // end leaf catalogue ?>


<?php
if($catalogue=='Dust' || $catalogue=='Full Catalogue'){
## ++++++ DUST CATALOGUE +++++
$catalouge_name = "Dust";
$this->db->select("invoice_entry.id, invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.chest_name, invoice_entry.category, GROUP_CONCAT(invoice_entry.w_inv_no) AS w_inv_no, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto, invoice_entry.old_season, invoice_entry.account_concerned, warehouse_setup.warehouse_name, warehouse_setup.short_key_word, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto');
$this->db->order_by('invoice_entry.lot_no ASC, invoice_entry.id ASC');
$query = $this->db->get();
$dust_catalogue = $query->result_array();
if(count($dust_catalogue) > 0){
?>
<?php foreach($dust_catalogue AS $row1){ ?>

<div class="row" data-print="dust_section" id='dustprev_<?php echo $row1['id']; ?>'>
<div class="col-sm-1"></div>
<div class="col-sm-8" style="border-bottom: 1px dashed black !important; padding-left:0px;">
    <b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
	<b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
	<b style="font-size:12px; text-align:left; text-transform:uppercase;">
		<?php echo $row1['invoice_group_txt']; ?> <?php echo $row1['invoice_group']; ?><?php echo (!empty($row1['invoice_group_upto'])) ? '-'.$row1['invoice_group_upto'] : ''; ?> 
		<?php print $row1['category']; ?> &nbsp;&nbsp;
		(<?php print $row1['chest_name']; ?>)
	</b>
</div>
<div class="col-sm-3">
	<b style="font-size:10px; white-space:nowrap;"><?php print ($row1['account_concerned']=='Yes') ? 'A/C CONCERNED' : ''; ?></b>

	<b style="font-size:10px; white-space:nowrap;" title="G # <?php print $row1['group_no']; ?>"><?php print ($season==$row1['old_season']) ? '' : 'Season '.$row1['old_season']; ?></b>
</div>
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
	<div class="col-sm-3"></div>
</div>
<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$tpinv_no = 0;
$count_last_lot = 0;

// query lot breakdown
$this->db->select("invoice_entry.id, invoice_entry.lot_no, invoice_entry.old_lot_no, invoice_entry.old_sale_no, invoice_entry.ol_os_sc, invoice_entry.w_inv_no, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, invoice_entry.gross_wt_per_bag, invoice_entry.net_wt_per_bag, invoice_entry.total_kgs, invoice_entry.net_kgs, invoice_entry.chest_number, invoice_entry.remarks, invoice_entry.last_lock, IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg "); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.group_no', $row1['group_no']);
$this->db->where('invoice_entry.w_house_id',$row1['w_house_id']);
$this->db->where('invoice_entry.invoice_group_txt',$row1['invoice_group_txt']);
$this->db->where('invoice_entry.invoice_group',$row1['invoice_group']);
$this->db->where('invoice_entry.invoice_group_upto',$row1['invoice_group_upto']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC, invoice_entry.id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<?php if($row2['last_lock']=='repeat'){ ?>
<div class="row" data-print="dust_section">
<div class="col-sm-1"></div>
<div class="col-sm-8" style="border-bottom: 1px dashed black !important; padding-left:0px;">
    <b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
	<b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
	<b style="font-size:12px; text-align:left; text-transform:uppercase;">
		<?php echo $row1['invoice_group_txt']; ?> <?php echo $row1['invoice_group']; ?><?php echo (!empty($row1['invoice_group_upto'])) ? '-'.$row1['invoice_group_upto'] : ''; ?> 
		<?php print $row1['category']; ?> &nbsp;&nbsp;
		(<?php print $row1['chest_name']; ?>)
	</b>
</div>
<div class="col-sm-3"></div>
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
	<div class="col-sm-3"></div>
</div>
<?php } ?>

<div class="row" style="padding: 5px 0" data-print="dust_section" id="dustnext_<?php echo $row2['id']; ?>">
	<div class="col-sm-1"></div>
	
	<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
	
	<div class="col-sm-1" style="padding:5px;"></div>
	<div class="col-sm-1" style="padding:5px;"></div>
	<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
	<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
	<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
	<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
	
	<?php }else{ ?>
	
	<div class="col-sm-1" id='dustlot_<?php echo $row2['id']; ?>' style="padding:5px; font-size:12px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
	</div>
	<div class="col-sm-1" style="padding:5px; font-size:12px;">
		<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
	</div>
	<div class="col-sm-1" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -10px !important;">
		<?php 
			// Use preg_match_all to find all occurrences of text and numbers
			preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

			// Extract text and numbers from matches
			// $text = implode('', $matches[1]);
			$w_inv_no = implode('', $matches[2]);

			print $w_inv_no; 
		?>
	</div>
	<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -40px!important;">
		<?php echo $row2['grade_name']; ?>
	</div>
	<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px; white-space:nowrap;">
		<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
	</div>
	<?php } ?>
	
	<div class="col-sm-1" style="padding:5px; text-align:right; font-size:12px; white-space:nowrap;">
		<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
	</div>
	<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px; width:60px !important;">
		<?php echo $row2['chest_number']; ?>
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
<div class="row" style="margin-bottom:20px;" data-print="dust_section">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important;">
			<div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Invoice Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px !important;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php echo $g_chest_number; ?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<?php } // second loop ?>
<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('catalouge_name', 'Dust');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row" style="margin-bottom:20px;" data-print="dust_section">
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
<div class="page-break" style="text-align:center; letter-spacing:22px;">END</div>
<?php } // group foreach ?>
<?php } // end dust catalogue ?>


<?php
if($catalogue=='Supplement' || $catalogue=='Full Catalogue'){
## ++++++ Supplement CATALOGUE +++++
$catalouge_name = "Supplement";
$this->db->select("invoice_entry.id, invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.chest_name, invoice_entry.category, GROUP_CONCAT(invoice_entry.w_inv_no) AS w_inv_no, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto, invoice_entry.old_season, invoice_entry.account_concerned, warehouse_setup.warehouse_name, warehouse_setup.short_key_word, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto');
$this->db->order_by('invoice_entry.lot_no ASC, invoice_entry.id ASC');
$query = $this->db->get();
$supp_catalogue = $query->result_array();
if(count($supp_catalogue) > 0){
?>

<?php foreach($supp_catalogue AS $row1){ ?>

<div class="row" data-print="sup_section" id='supprev_<?php echo $row1['id']; ?>'>
<div class="col-sm-1"></div>
<div class="col-sm-8" style="border-bottom: 1px dashed black !important; padding-left:0px;">
    <b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
	<b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
	<b style="font-size:12px; text-align:left; text-transform:uppercase;">
		<?php echo $row1['invoice_group_txt']; ?> <?php echo $row1['invoice_group']; ?><?php echo (!empty($row1['invoice_group_upto'])) ? '-'.$row1['invoice_group_upto'] : ''; ?> 
		<?php print $row1['category']; ?> &nbsp;&nbsp;
		(<?php print $row1['chest_name']; ?>)
	</b>
</div>
<div class="col-sm-3">
	<b style="font-size:10px; white-space:nowrap;"><?php print ($row1['account_concerned']=='Yes') ? 'A/C CONCERNED' : ''; ?></b>

	<b style="font-size:10px; white-space:nowrap;" title="G # <?php print $row1['group_no']; ?>"><?php print ($season==$row1['old_season']) ? '' : 'Season '.$row1['old_season']; ?></b>
</div>
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
	<div class="col-sm-3"></div>
</div>
<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$tpinv_no = 0;
$count_last_lot = 0;

// query lot breakdown
$this->db->select("invoice_entry.id, invoice_entry.lot_no, invoice_entry.old_lot_no, invoice_entry.old_sale_no, invoice_entry.ol_os_sc, invoice_entry.w_inv_no, invoice_entry.invoice_group_txt, invoice_entry.invoice_group, invoice_entry.invoice_group_upto, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, invoice_entry.gross_wt_per_bag, invoice_entry.net_wt_per_bag, invoice_entry.total_kgs, invoice_entry.net_kgs, invoice_entry.chest_number, invoice_entry.remarks, invoice_entry.last_lock, IF(invoice_entry.last_lock IS NULL, 'repeat','undo') AS last_lock_msg "); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.group_no', $row1['group_no']);
$this->db->where('invoice_entry.w_house_id',$row1['w_house_id']);
$this->db->where('invoice_entry.invoice_group_txt',$row1['invoice_group_txt']);
$this->db->where('invoice_entry.invoice_group',$row1['invoice_group']);
$this->db->where('invoice_entry.invoice_group_upto',$row1['invoice_group_upto']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC, invoice_entry.id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<?php if($row2['last_lock']=='repeat'){ ?>
<div class="row" data-print="sup_section">
<div class="col-sm-1"></div>
<div class="col-sm-8" style="border-bottom: 1px dashed black !important; padding-left:0px;">
    <b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['short_key_word']; ?> SHED</b>&nbsp;&nbsp;&nbsp;
	<b style="text-align:left; font-size:12px; text-transform:uppercase;"><?php print $row1['garden_name']; ?></b>&nbsp;&nbsp;&nbsp;
	<b style="font-size:12px; text-align:left; text-transform:uppercase;">
		<?php echo $row1['invoice_group_txt']; ?> <?php echo $row1['invoice_group']; ?><?php echo (!empty($row1['invoice_group_upto'])) ? '-'.$row1['invoice_group_upto'] : ''; ?> 
		<?php print $row1['category']; ?> &nbsp;&nbsp;
		(<?php print $row1['chest_name']; ?>)
	</b>
</div>
<div class="col-sm-3"></div>
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
	<div class="col-sm-3"></div>
</div>
<?php } ?>

<div class="row" style="padding: 5px 0" data-print="sup_section" id="supnext_<?php echo $row2['id']; ?>">
	<div class="col-sm-1"></div>
	
	<?php if($count_last_lot == $row2['lot_no'] && $row2['bulk_lot']=='y'){ ?> 
	
	<div class="col-sm-1" style="padding:5px;"></div>
	<div class="col-sm-1" style="padding:5px;"></div>
	<div class="col-sm-1" style="padding:5px; text-transform:uppercase;"></div>
	<div class="col-sm-1 text-left" style="padding:5px; text-transform:uppercase; width:100px;"></div>
	<div class="col-sm-1" style="padding:5px; text-align:right;"></div>
	<div class="col-sm-2" style="padding:5px; text-align:right;"></div>
	
	<?php }else{ ?>
	
	<div class="col-sm-1" id='suplot_<?php echo $row2['id']; ?>' style="padding:5px; font-size:12px; margin-right: -5px !important;"><?php echo $row2['lot_no']; ?>
	</div>
	<div class="col-sm-1" style="padding:5px; font-size:12px;">
		<?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_lot_no'] . '/' : ''; ?><?php echo ($row2['ol_os_sc']=='Yes') ? $row2['old_sale_no'] : ''; ?>
	</div>
	<div class="col-sm-1" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -10px !important;">
		<?php 
			// Use preg_match_all to find all occurrences of text and numbers
			preg_match_all('/([a-zA-Z]+)|(\d+)/', $row2['w_inv_no'], $matches);

			// Extract text and numbers from matches
			// $text = implode('', $matches[1]);
			$w_inv_no = implode('', $matches[2]);

			print $w_inv_no; 
		?>
	</div>
	<div class="col-sm-2 text-left" style="padding:5px; text-transform:uppercase; font-size:12px; margin-right: -40px!important;">
		<?php echo $row2['grade_name']; ?>
	</div>
	<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px; white-space:nowrap;">
		<?php echo number_format($row2['gross_wt_per_bag'],1,'.',''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo number_format($row2['net_wt_per_bag'],1,'.',''); ?>
	</div>
	<?php } ?>
	
	<div class="col-sm-1" style="padding:5px; text-align:right; font-size:12px; white-space:nowrap;">
		<?php echo number_format($row2['net_kgs'],1,'.',''); ?>
	</div>
	<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px; width:60px !important;">
		<?php echo $row2['chest_number']; ?>
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
<div class="row" style="margin-bottom:20px;" data-print="sup_section">
	<div class="col-sm-1"></div>
	<div class="col-sm-8">
		<div class="row" style="border-top: 1px dashed black !important; border-bottom: 1px dashed black !important;">
			<div class="col-sm-5" style="padding:5px; text-align:right; font-size:12px;">Invoice Total:</div>
			<div class="col-sm-4" style="padding:5px; text-align:center; font-size:12px; margin-right:-5px;"><?php echo number_format($g_total_kgs,2,'.',''); ?></div>
			<div class="col-sm-2" style="padding:5px; text-align:right; font-size:12px;"><?php echo number_format($g_net_kgs,2,'.',''); ?></div>
			<div class="col-sm-1" style="padding:5px; text-align:center; font-size:12px;"><?php echo $g_chest_number; ?></div>
		</div>
	</div>
	<div class="col-sm-3"></div>
</div>
<?php } // second loop ?>
<?php 
	$this->db->select("sum(chest_number) AS number_of_bags, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs"); 
	$this->db->from('invoice_entry');
	$this->db->where('published', 'Yes');
	$this->db->where('catalouge_name', 'Supplement');
	$this->db->where('season', $season);
	$this->db->where('auction_center', $auction_center);
	$this->db->where('sale_no', $sale_no);
	$this->db->where('soft_delete', 1);
	$this->db->order_by('lot_no ASC');
	$query = $this->db->get();
	$total_leaf_result = $query->row_array();
?>
<div class="row" style="margin-bottom:20px;" data-print="sup_section">
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
<div class="page-break" style="text-align:center; letter-spacing:22px;">END</div>
<?php } // group foreach ?>
<?php } // end supplement catalogue ?>

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
		$(this).before('<div class="row" style="margin-top:15px;"><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px;">Unity Brokers Limited</b></div><div class="form group col-sm-6 text-left" style="font-size:12px; text-transform: uppercase;">1837 SK Mujib Road, CTG</div><div class="form group col-sm-2 text-right"><b style="text-transform: uppercase; font-size:12px;">PAGE NO: ' + page_no + '</b></div></div><div class="row" style="margin-top:15px;"><div class="col-sm-12" style="text-transform: uppercase; font-size:13px; border-bottom:1px solid black;">VALUATION FOR SALE.&nbsp;<?php print $sale_no; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php print $auction_center; ?>&nbsp;&nbsp;&nbsp;&nbsp;SEASON&nbsp;&nbsp;&nbsp;&nbsp;<?php print $season; ?>&nbsp;&nbsp;&nbsp;&nbsp;TO BE HELD ON&nbsp;&nbsp;&nbsp;&nbsp;<?php print date('M-d-Y', strtotime($sale_date)); ?></div></div>');
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

// leaf section
var staticHeight = 0;
var sl = 1;
var currentPage = 1;

$('[data-print=leaf_section]').each(function () {

    if (staticHeight == 0) {
        var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="leafabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Unity Brokers Limited</b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">LEAF&nbsp;&nbsp;PAGE NO: ' + page_no + '</b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="leafol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedleafprev_' + page_no + '"></span><span style="color:green;" id="elementSelectedleafnext_' + page_no + '"></span></div></div>');
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

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 800;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});


// dust section
var staticHeight = 0;
var sl = 1;
var currentPage = 1;

$('[data-print=dust_section]').each(function () {

    if (staticHeight == 0) {
        var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="dustabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Unity Brokers Limited</b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?></b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">DUST&nbsp;&nbsp;PAGE NO: ' + page_no + '</b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="dustol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelecteddustprev_' + page_no + '"></span><span style="color:green;" id="elementSelecteddustnext_' + page_no + '"></span></div></div>');
		
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

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 800;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});

// sup section
var staticHeight = 0;
var sl = 1;
var currentPage = 1;

$('[data-print=sup_section]').each(function () {

    if (staticHeight == 0) {
        var page_no = sl++;
        $(this).before('<div class="row" style="margin-bottom:15px;" id="supabc_' + page_no + '"><div class="form group col-sm-1 text-left"></div><div class="form group col-sm-9" style="border-bottom:1px solid #000;"><div class="row"><div class="form group col-sm-12 text-center"><b style="text-transform: uppercase; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Unity Brokers Limited</b></div><div class="form group col-sm-4 text-left"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Sale NO: <?php print $sale_no; ?><br>E-AUCTION</b></div><div class="form group col-sm-4 text-center"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Season <?php print $season; ?></b></div><div class="form group col-sm-4 text-right"><b style="text-transform: uppercase; font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Supplement : ' + page_no + '</b><br><span style="font-size:13px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;"><?php print date("M,d,y", strtotime($sale_date)); ?></span></div></div></div><div class="form group col-sm-2 text-left"></div><div class="col-sm-12" id="supol_div' + page_no + '" style="padding-left:0px; padding-right:0px; margin-top:15px;"><span id="elementSelectedsupprev_' + page_no + '"></span><span style="color:green;" id="elementSelectedsupnext_' + page_no + '"></span></div></div>');
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

    staticHeight += $(this).filter(':visible').outerHeight(true);
    var pageHeight = 800;

    if (staticHeight > pageHeight) {
        $(this).after('<div class="row page-break"><div class="col-sm-1"></div><div class="col-sm-8" style="text-align:right; font-size:15px; font-family: Courier New; font-weight: 900; letter-spacing: 1px;">Contd....P..' + (++currentPage) + '</div><div class="col-sm-3"></div></div>');
        staticHeight = 0;
    }

});

</script>

</body>
</html>