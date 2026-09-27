<?php
// get html value
$season = (!empty($this->input->post('season_name', TRUE))) ? $this->input->post('season_name', TRUE) : '0';
$auction_center = (!empty($this->input->post('auction_center', TRUE))) ? $this->input->post('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->post('sale_no', TRUE))) ? $this->input->post('sale_no', TRUE) : '';
?>
<head>
 <title>Auction Average</title>
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
<style>
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
@page{
	size:auto;
	margin-left: 100px;
	margin-right: 100px;
}
</style>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">	
<div class="col-sm-12">
<?php
## create temporary table - sale_average
$this->db->query("DROP TABLE IF EXISTS `sale_average`");

// create sale_average @ buyer-1
$this->db->query("CREATE TABLE `sale_average` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.supplement_tea AS catalogue_name,
	@sold_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS sold_bags,
	@sold_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	@sold_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
	sale_entry.buyer_type1 AS buyer_type

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// insert sale_average @ buyer-2
$this->db->query("INSERT INTO `sale_average` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.supplement_tea AS catalogue_name,
	@sold_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS sold_bags,
	@sold_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	@sold_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
	sale_entry.buyer_type2

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// insert sale_average @ buyer-3
$this->db->query("INSERT INTO `sale_average` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.supplement_tea AS catalogue_name,
	@sold_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS sold_bags,
	@sold_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	@sold_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
	sale_entry.buyer_type3

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>	

<table class="table" style="margin-bottom:5px;">
	<thead>
		<tr>
			<td style="text-transform: uppercase; text-align:center;">
				<div style="display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #33333373 !important;">
					<span>
						<img src="https://www.unitybrokersltd.com/api/ubl_logo.png" class="img-fluid" style="height:61px; width:61px;" alt="ubl_logo">
					</span>
					<span style="padding: 0 5px;"></span>
					<span>
						<b style="font-size:15px; text-transform:uppercase;">Unity Brokers Limited</b><br>
						<span style="font-size:13px; text-transform: capitalize;">Progressive tower (5th floor) <br> 1837, Sk. mujib road,  agrabad C/A, Chittagong</span>
					</span>
				</div>
			</td>
		</tr>
	</thead>
</table>

<?php
## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%M-%d-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();

$current_date_time= date("Y-m-d h:i:sa");
?>

<table class="table" id="table" style="margin-bottom:5px;">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="2">
		<span style="font-size:13px;">
			UBL/ <?php print $sale_no ; ?> / <?php print $auction_center ; ?> / <?php print $season ; ?>
		</span>
	</td>
	<td style="text-align:right; border:0px;">
		<span style="font-size:13px;">
			Print Date: <?php print date('d-M-Y') ; ?>
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<span style="font-size:13px;">
			The Secretary,<br>
			<b>Bangladesh Tea Board</b><br>
			171/172 Baizid Bostami Road Nasirabad, Chittagong.
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<b style="font-size:13px;">Re: Sale Average of Teas Season <?php print $season; ?> Sale No. <?php print $sale_no; ?> <?php print $auction_center ; ?> held on <?php print $result_prompt_date['sale_date'] ; ?>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<span style="font-size:13px;">
			Dear Sir<br>
			Average price realised for teas in the above sale are given below.
		</span>
	</td>
</tr>
<?php
// query
$this->db->select("
	
	sum(CASE WHEN catalogue_name='Leaf' THEN sold_bags END) AS sold_leaf_chest, 
	sum(CASE WHEN catalogue_name='Dust' THEN sold_bags END) AS sold_dust_chest,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN sold_kgs END) AS sold_leaf_kgs, 
	sum(CASE WHEN catalogue_name='Dust' THEN sold_kgs END) AS sold_dust_kgs,
	
	sum(CASE WHEN catalogue_name='Leaf' THEN sold_amount END) AS sold_leaf_amount, 
	sum(CASE WHEN catalogue_name='Dust' THEN sold_amount END) AS sold_dust_amount
"); 	
$this->db->from('sale_average');
$sql = $this->db->get();
$result_date = $sql->row_array();
?>
</thead>
</table>

<table class="table table-sm" id="table">
<thead>
<?php
$avg_price_leaf = ($result_date['sold_leaf_amount'] > 0 && $result_date['sold_leaf_kgs'] > 0) ? $result_date['sold_leaf_amount'] / $result_date['sold_leaf_kgs'] : '0.00';
$avg_price_dust = ($result_date['sold_dust_amount'] > 0 && $result_date['sold_dust_kgs'] > 0) ? $result_date['sold_dust_amount'] / $result_date['sold_dust_kgs'] : '0.00';

$avg_sub_total = ($result_date['sold_leaf_amount'] + $result_date['sold_dust_amount']) / ($result_date['sold_leaf_kgs'] + $result_date['sold_dust_kgs']);
?>
<tr>
    <th style="text-align:left; border:0px;"><u>CTC Teas :</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Chest</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Kg</u></th>
	<th style="text-align:right; border:0px;"><u>Amount Tk</u></th>
	<th style="text-align:right; border:0px;"><u>Average Tk</u></th>
</tr>
</thead>
<tbody>
<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">LEAF</th>
	<td style="text-align:right; border:0px;"><?php echo $result_date['sold_leaf_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_leaf_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_leaf_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_leaf,2); ?></td>
</tr>

<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">DUST</th>
	<td style="text-align:right; border:0px;"><?php echo $result_date['sold_dust_chest']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_dust_kgs'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($result_date['sold_dust_amount'],2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_price_dust,2); ?></td>
</tr>

<tr style="border-top:1px dashed #000;">
	<th style="text-align:left; text-transform:capitalize; border:0px;">Sub Total :</th>
	<td style="text-align:right; border:0px;"><?php echo ($result_date['sold_leaf_chest'] + $result_date['sold_dust_chest']); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($result_date['sold_leaf_kgs'] + $result_date['sold_dust_kgs']),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($result_date['sold_leaf_amount'] + $result_date['sold_dust_amount']),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_sub_total,2); ?></td>
</tr>

<tr style="border-top:1px dashed #000; border-bottom:1px dashed #000;">
	<th style="text-align:left; text-transform:capitalize; border:0px;">Grand Total :</th>
	<td style="text-align:right; border:0px;"><?php echo ($result_date['sold_leaf_chest'] + $result_date['sold_dust_chest']); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($result_date['sold_leaf_kgs'] + $result_date['sold_dust_kgs']),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($result_date['sold_leaf_amount'] + $result_date['sold_dust_amount']),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($avg_sub_total,2); ?></td>
</tr>
</tbody>
</table>

<table class="table table-sm" id="table" style="margin-bottom:0px;">
<thead>
<tr>
    <th style="text-align:left; border:0px;"><u>Buyers Purchase :</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Chest</u></th>
	<th style="text-align:right; border:0px;"><u>Sold Kg</u></th>
	<th style="text-align:right; border:0px;"><u>Amount Tk</u></th>
	<th style="text-align:right; border:0px;"><u>Average Tk</u></th>
</tr>
</thead>
<tbody>

<?php
	// query
	$this->db->select("
		
		sum(CASE WHEN catalogue_name='Leaf' THEN sold_bags END) AS sold_leaf_chest, 
		sum(CASE WHEN catalogue_name='Dust' THEN sold_bags END) AS sold_dust_chest,

		sum(CASE WHEN catalogue_name='Leaf' THEN sold_kgs END) AS sold_leaf_kgs, 
		sum(CASE WHEN catalogue_name='Dust' THEN sold_kgs END) AS sold_dust_kgs,
		
		sum(CASE WHEN catalogue_name='Leaf' THEN sold_amount END) AS sold_leaf_amount, 
		sum(CASE WHEN catalogue_name='Dust' THEN sold_amount END) AS sold_dust_amount
	"); 	
	$this->db->from('sale_average');
	$this->db->where('buyer_type','Internal');
	$sql = $this->db->get();
	$result_date_l = $sql->row_array();

	$total_pkgs_l = $result_date_l['sold_leaf_chest'] + $result_date_l['sold_dust_chest'];
	$total_kgs_l = $result_date_l['sold_leaf_kgs'] + $result_date_l['sold_dust_kgs'];
	$total_amount_l = $result_date_l['sold_leaf_amount'] + $result_date_l['sold_dust_amount'];

	$total_avg_price_l = $total_amount_l / $total_kgs_l;
?>

<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">Local Buyers :</th>
	<td style="text-align:right; border:0px;"><?php echo $total_pkgs_l; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_kgs_l,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_amount_l,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_avg_price_l,2); ?></td>
</tr>

<?php
	// query
	$this->db->select("
		
		sum(CASE WHEN catalogue_name='Leaf' THEN sold_bags END) AS sold_leaf_chest, 
		sum(CASE WHEN catalogue_name='Dust' THEN sold_bags END) AS sold_dust_chest,
		
		sum(CASE WHEN catalogue_name='Leaf' THEN sold_kgs END) AS sold_leaf_kgs, 
		sum(CASE WHEN catalogue_name='Dust' THEN sold_kgs END) AS sold_dust_kgs,
		
		sum(CASE WHEN catalogue_name='Leaf' THEN sold_amount END) AS sold_leaf_amount, 
		sum(CASE WHEN catalogue_name='Dust' THEN sold_amount END) AS sold_dust_amount
	"); 	
	$this->db->from('sale_average');
	$this->db->where('buyer_type','Export');
	$sql = $this->db->get();
	$result_date_e = $sql->row_array();

	$total_pkgs_e = $result_date_e['sold_leaf_chest'] + $result_date_e['sold_dust_chest'];
	$total_kgs_e = $result_date_e['sold_leaf_kgs'] + $result_date_e['sold_dust_kgs'];
	$total_amount_e = $result_date_e['sold_leaf_amount'] + $result_date_e['sold_dust_amount'];

	// $total_avg_price_e = $total_amount_e / $total_kgs_e;
	// Example using ternary operator to prevent division by zero error
	$total_avg_price_e = ($total_amount_e > 0 && $total_kgs_e > 0)? ($total_amount_e / $total_kgs_e) : 0;

	$grand_total_avg = ($total_amount_l + $total_amount_e) / ($total_kgs_l + $total_kgs_e);
?>

<tr>
	<th style="text-align:left; text-transform:capitalize; border:0px;">Export Buyers :</th>
	<td style="text-align:right; border:0px;"><?php echo $total_pkgs_e; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_kgs_e,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_amount_e,2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_avg_price_e,2); ?></td>
</tr>

<tr style="border-top:1px dashed #000; border-bottom:1px dashed #000;">
	<th style="text-align:left; text-transform:capitalize; border:0px;">Grand Total :</th>
	<td style="text-align:right; border:0px;"><?php echo ($total_pkgs_l + $total_pkgs_e); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($total_kgs_l + $total_kgs_e),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($total_amount_l + $total_amount_e),2); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($grand_total_avg,2); ?></td>
</tr>
</tbody>
</table>
</div> <!-- col -->

<div class="col-sm-12" style="margin-top:20px;">
	Sincerely Yours,
</div>

<div class="col-sm-12 text-left" style="margin-top:50px;">
	<b style="text-transform: uppercase; font-size:14px;"> Unity Brokers Limited </b><br><br>
	<span style="font-size:14px;">CC: National Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">Produce Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">Purba Bangla Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">Progressive Brokers Ltd. Ctg.</span><br>
	<span style="font-size:14px; margin-left:30px;">K.S Brokers Limited Chittagong</span><br>
	<span style="font-size:14px; margin-left:30px;">Planters Brokers Limited Ctg.</span>
</div>

<div class="col-sm-12 text-left" style="margin-top:20px;">
	<span style="font-size:14px;">Noo: Secretary, Bangladesh Cha Sangsad, Chittagong</span><br>
	<span style="font-size:14px; margin-left:38px;">Managing Director, National Tea Co. Ltd, Dhaka.</span><br>
	<span style="font-size:14px; margin-left:38px;">Asstt. Director (Trade), Bangladesh Tea Board, Chittagong.</span><br>
	<span style="font-size:14px; margin-left:38px;">Deputy Director (Trade), Bangladesh Tea Board, Chittagong.</span>
</div>

<div class="col-sm-12 text-left" style="margin-top:40px;">
	<i style="text-transform: uppercase; font-size:14px; font-weight:bold;"> Jamuna Tech </i>
</div>

</div> <!-- row -->

</div>
<!-- /container -->

<script>
function printDiv(divName){
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