<!DOCTYPE html>
<html lang="en">
<head>
<title>BUYER CONTRACT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>

<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';
$w_house_id  = (!empty($this->input->get('warehouse_id', TRUE))) ? $this->input->get('warehouse_id', TRUE) : '';
$catalogue_name = (!empty($this->input->get('catalogue_name', TRUE))) ? $this->input->get('catalogue_name', TRUE) : '';
$buyer_con = (!empty($this->input->get('buyer_con', TRUE))) ? $this->input->get('buyer_con', TRUE) : '';

## buyer details
$query = $this->db->query("SELECT * FROM buyer_setup WHERE id='".$buyer_id."' ");
$buyer_data = $query->row_array();

## Sale details
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$sale_data = $query->row_array();
?>

<?php if(!empty($catalogue_name) && $catalogue_name=='Leaf'){ ?>

<div class="container">
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		</a>
	</div>
</div>

<?php
## create temporary table - buyer_contact
$this->db->query("DROP TABLE IF EXISTS `buyer_contact`");

// create buyer_contact
$this->db->query("CREATE TABLE `buyer_contact` (
SELECT 

	invoice_entry.lot_no, 
	invoice_entry.catalouge_name, 
	invoice_entry.w_house_id, 
	ws.warehouse_name,
	ws.short_key_word,
	ws.warehouse_address2,
	gs.garden_name,
	invoice_entry.invoice_group_txt, 
	invoice_entry.w_inv_no, 
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,	
	invoice_entry.vat, 
	
	(
		case 
			when invoice_entry.catalouge_name = 'Leaf' then 'A' 
			when invoice_entry.catalouge_name = 'Dust' then 'B'
			when invoice_entry.catalouge_name = 'Supplement' then 'C'
			else ''
		end	
	)  AS catalogue_order,

	(CASE 
		WHEN IFNULL(sale_entry.buyer1_bag,0) > 0 AND sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer_type1 
		WHEN IFNULL(sale_entry.buyer2_bag,0) > 0 AND sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer_type2 
		WHEN IFNULL(sale_entry.buyer3_bag,0) > 0 AND sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer_type3 
		ELSE ''
		END
	) AS buyer_type,
	
	@total_pkgs:=(
		IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_bag END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_bag END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_bag END),0)
	) AS total_pkgs,

	@net_kgs:=(
	CASE 
		WHEN sale_entry.buyer1 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer1_kg_dm,'<br>',sale_entry.buyer1_smpl_dm) 
		WHEN sale_entry.buyer2 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer2_kg_dm,'<br>',sale_entry.buyer2_smpl_dm) 
		WHEN sale_entry.buyer3 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer3_kg_dm,'<br>',sale_entry.buyer3_smpl_dm) 
		ELSE ''
	END	
	) AS net_kgs,

	@total_kgs:=(
		IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_total_kgs END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_total_kgs END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_total_kgs END),0)
	) AS total_kgs,	

	sale_entry.sold_price,			

	@amount:=(
		IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_amount END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_amount END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_amount END),0)
	) AS amount
	
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND (invoice_entry.catalouge_name='Leaf' OR invoice_entry.supplement_tea='Leaf') AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND (sale_entry.buyer1='".$buyer_id."' OR sale_entry.buyer2 = '".$buyer_id."'OR sale_entry.buyer3 = '".$buyer_id."') GROUP BY invoice_entry.id) ");
// end

// wh & catalog breakdown
$page = 1;	
$query = $this->db->query("SELECT * FROM buyer_contact GROUP BY buyer_type, w_house_id, catalouge_name ORDER BY catalogue_order ASC, buyer_type DESC");
if($query->num_rows() > 0){
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
	$page_counter = $page++;
?>

<div class="row print_div" id="print_div_area<?php print $page_counter; ?>">
<div class="col-sm-12">

<style>
body{
	background-color: white !important;
	font-family:verdana;
}
.table{
	font-family:verdana;
	font-size:12px !important;
	border:none;
}
#table tr td{
	border-color:#CCCCCC !important;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:#CCCCCC !important;
	font-size:10px !important;
	padding:3px;
	vertical-align:middle;
}
.custom-font{
	font-family:'Times New Roman', serif; font-weight: 400; font-style: normal;letter-spacing: 1px;
}


@media print{    
	thead { display: table-header-group; }
	#page{ page-break-after: always !important; }
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
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="printDiv('print_div_area<?php print $page_counter; ?>');">
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>
	
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<b class="custom-font" style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b>
	</td>
	<td colspan="6">
		<span class="custom-font" style="font-size:13px; text-transform: capitalize;"><?php print $row_data['warehouse_name']; ?>, <?php print $row_data['warehouse_address2']; ?></span>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px;" class="custom-font" colspan="9">
		<span style="font-size:13px;">Sale No. <?php print $sale_no; ?> Prompt <?php print date("d/m/Y",strtotime($sale_data['buyer_prompt_date'])); ?> Contract / D. O. No. <?php print $sale_no.'.'.$buyer_id . '/'. $row_data['w_house_id']; ?> Date: <?php print date("d/m/Y",strtotime($sale_data['sale_date'])); ?> Shed : <?php print $row_data['short_key_word']; ?></span> <br>
		<span style="font-size:13px; text-transform: uppercase;">Messers: <?php print $buyer_data['buyer_name']; ?>, <?php print $buyer_data['buyer_address1']; ?> BIN <?php print $buyer_data['buyer_bin_no']; ?> (<?php print $row_data['buyer_type']; ?>)</span> <br>
		<span style="font-size:13px;">We have bought by your order and account the following lots of tea:(Brokerage Tk. 10.00/100 kg)</span>
	</td>
</tr>
<tr>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Lot</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Garden</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Invoice</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Grade</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Pkgs</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important; white-space:nowrap;">Net Kg.</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Total Kg.</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Price</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Amount</td>
</tr>
</thead>	
<tbody>	
<?php
$sl = 1;
$total_pkgs = 0;
$total_kgs = 0;
$total_amount = 0;

// query
$sql = $this->db->query("SELECT * FROM buyer_contact WHERE buyer_type='".$row_data['buyer_type']."' AND w_house_id='".$row_data['w_house_id']."' ORDER BY lot_no ASC");
$contact_data = $sql->result_array();
foreach($contact_data as $row2){
?>
<tr>
	<td style="text-align:center; font-size:12px; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['garden_name']; ?>
	</td>
	<td style="text-align:center; border:none; text-transform:uppercase;">
		<?php print $row2['invoice_group_txt']; ?> <?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:center; border:none;">
		<?php print $row2['total_pkgs']; ?>
	</td>
	<td style="text-align:right; border:none; white-space:nowrap;">
		<?php print $row2['net_kgs']; ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['amount'],2,'.',','); ?>
	</td>
</tr>	

<?php
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
	
} // foreach
?>

<tr class="text-danger">
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; text-transform:uppercase; border-top: 1px dashed black !important;" colspan="2"> Sub Total : </td>
	<td style="text-align:center; vertical-align:middle; border-top: 1px dashed black !important;"><?php print $total_pkgs; ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"></td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_kgs,2,'.',','); ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"> Tk.</td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_amount,2,'.',','); ?></td>
</tr>

<?php if($row_data['buyer_type']=='Internal'){ ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"> VAT <?php print $row_data['vat']; ?>% Tk. </td>
	
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;">
	<?php 
		$total_vat = (($total_amount * $row_data['vat'])/100);
		print number_format($total_vat,2,'.',','); 
	?>
	</td>
</tr>

<?php }else{ $total_vat = 0; } ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"><b> TOTAL : Tk. </b></th>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><b style="border-bottom: double black !important;"><?php print number_format($total_amount + $total_vat,2,'.',','); ?></b></td>
</tr>
</tbody>
</table>

</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<b class="custom-font" style="font-size:15px; text-transform: uppercase;"> <?php print $website_title; ?> </b>
		</p>
	</div>
</div>

</div> <!-- /col -->
</div> <!-- /row -->
<div id="page"></div>

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>
</div> 
<!-- /container -->


<?php }else if(!empty($catalogue_name) && $catalogue_name=='Dust'){ ?>

<div class="container">
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		</a>
	</div>
</div>

<?php
## create temporary table - buyer_contact
$this->db->query("DROP TABLE IF EXISTS `buyer_contact`");

// create buyer_contact
$this->db->query("CREATE TABLE `buyer_contact` (
SELECT 

	invoice_entry.lot_no, 
	invoice_entry.catalouge_name, 
	invoice_entry.w_house_id, 
	ws.warehouse_name,
	ws.short_key_word,
	ws.warehouse_address2,
	gs.garden_name,
	invoice_entry.invoice_group_txt, 
	invoice_entry.w_inv_no, 
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,	
	invoice_entry.vat, 
	
	(
		case 
			when invoice_entry.catalouge_name = 'Leaf' then 'A' 
			when invoice_entry.catalouge_name = 'Dust' then 'B'
			when invoice_entry.catalouge_name = 'Supplement' then 'C'
			else ''
		end	
	)  AS catalogue_order,

	(CASE 
		WHEN IFNULL(sale_entry.buyer1_bag,0) > 0 AND sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer_type1 
		WHEN IFNULL(sale_entry.buyer2_bag,0) > 0 AND sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer_type2 
		WHEN IFNULL(sale_entry.buyer3_bag,0) > 0 AND sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer_type3 
		ELSE ''
		END
	) AS buyer_type,
	
	@total_pkgs:=(
		IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_bag END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_bag END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_bag END),0)
	) AS total_pkgs,

	@net_kgs:=(
	CASE 
		WHEN sale_entry.buyer1 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer1_kg_dm,'<br>',sale_entry.buyer1_smpl_dm) 
		WHEN sale_entry.buyer2 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer2_kg_dm,'<br>',sale_entry.buyer2_smpl_dm) 
		WHEN sale_entry.buyer3 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer3_kg_dm,'<br>',sale_entry.buyer3_smpl_dm) 
		ELSE ''
	END	
	) AS net_kgs,

	@total_kgs:=(
		IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_total_kgs END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_total_kgs END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_total_kgs END),0)
	) AS total_kgs,	

	sale_entry.sold_price,			

	@amount:=(
		IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_amount END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_amount END),0) 
		+ 
		IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_amount END),0)
	) AS amount
	
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalouge_name IN ('Dust','Supplement') AND NOT invoice_entry.supplement_tea='Leaf' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND (sale_entry.buyer1='".$buyer_id."' OR sale_entry.buyer2 = '".$buyer_id."'OR sale_entry.buyer3 = '".$buyer_id."') GROUP BY invoice_entry.id) ");
// end

// wh & catalog breakdown
$page = 1;	
$query = $this->db->query("SELECT * FROM buyer_contact GROUP BY buyer_type, w_house_id, catalouge_name ORDER BY catalogue_order ASC, buyer_type DESC");
if($query->num_rows() > 0){
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
	$page_counter = $page++;
?>

<div class="row print_div" id="print_div_area<?php print $page_counter; ?>">
<div class="col-sm-12">

<style>
body{
	background-color: white !important;
	font-family:verdana;
}
.table{
	font-family:verdana;
	font-size:12px !important;
	border:none;
}
#table tr td{
	border-color:#CCCCCC !important;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:#CCCCCC !important;
	font-size:10px !important;
	padding:3px;
	vertical-align:middle;
}
.custom-font{
	font-family:'Times New Roman', serif; font-weight: 400; font-style: normal;letter-spacing: 1px;
}


@media print{    
	thead { display: table-header-group; }
	#page{ page-break-after: always !important; }
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
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="printDiv('print_div_area<?php print $page_counter; ?>');">
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>
	
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="3">
		<b class="custom-font" style="font-size:15px; text-transform: uppercase;"><?php print $website_title; ?></b>
	</td>
	<td colspan="6">
		<span class="custom-font" style="font-size:13px; text-transform: capitalize;"><?php print $row_data['warehouse_name']; ?>, <?php print $row_data['warehouse_address2']; ?></span>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px;" class="custom-font" colspan="9">
		<span style="font-size:13px;">Sale No. <?php print $sale_no; ?> Prompt <?php print date("d/m/Y",strtotime($sale_data['buyer_prompt_date'])); ?> Contract / D. O. No. <?php print $sale_no.'.'.$buyer_id . '/'. $row_data['w_house_id']; ?> Date: <?php print date("d/m/Y",strtotime($sale_data['sale_date'])); ?> Shed : <?php print $row_data['short_key_word']; ?></span> <br>
		<span style="font-size:13px; text-transform: uppercase;">Messers: <?php print $buyer_data['buyer_name']; ?>, <?php print $buyer_data['buyer_address1']; ?> BIN <?php print $buyer_data['buyer_bin_no']; ?> (<?php print $row_data['buyer_type']; ?>)</span> <br>
		<span style="font-size:13px;">We have bought by your order and account the following lots of tea:(Brokerage Tk. 10.00/100 kg)</span>
	</td>
</tr>
<tr>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Lot</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Garden</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Invoice</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Grade</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Pkgs</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important; white-space:nowrap;">Net Kg.</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Total Kg.</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Price</td>
	<td style="text-align:center; vertical-align:middle; border:1px solid black !important;">Amount</td>
</tr>
</thead>	
<tbody>	
<?php
$sl = 1;
$total_pkgs = 0;
$total_kgs = 0;
$total_amount = 0;

// query
$sql = $this->db->query("SELECT * FROM buyer_contact WHERE buyer_type='".$row_data['buyer_type']."' AND w_house_id='".$row_data['w_house_id']."' ORDER BY lot_no ASC");
$contact_data = $sql->result_array();
foreach($contact_data as $row2){
?>
<tr>
	<td style="text-align:center; font-size:12px; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['garden_name']; ?>
	</td>
	<td style="text-align:center; border:none; text-transform:uppercase;">
		<?php print $row2['invoice_group_txt']; ?> <?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:center; border:none;">
		<?php print $row2['total_pkgs']; ?>
	</td>
	<td style="text-align:right; border:none; white-space:nowrap;">
		<?php print $row2['net_kgs']; ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['amount'],2,'.',','); ?>
	</td>
</tr>	

<?php
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
	
} // foreach
?>

<tr class="text-danger">
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; text-transform:uppercase; border-top: 1px dashed black !important;" colspan="2"> Sub Total : </td>
	<td style="text-align:center; vertical-align:middle; border-top: 1px dashed black !important;"><?php print $total_pkgs; ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"></td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_kgs,2,'.',','); ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"> Tk.</td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_amount,2,'.',','); ?></td>
</tr>

<?php if($row_data['buyer_type']=='Internal'){ ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"> VAT <?php print $row_data['vat']; ?>% Tk. </td>
	
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;">
	<?php 
		$total_vat = (($total_amount * $row_data['vat'])/100);
		print number_format($total_vat,2,'.',','); 
	?>
	</td>
</tr>

<?php }else{ $total_vat = 0; } ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"><b> TOTAL : Tk. </b></th>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><b style="border-bottom: double black !important;"><?php print number_format($total_amount + $total_vat,2,'.',','); ?></b></td>
</tr>
</tbody>
</table>

</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<b class="custom-font" style="font-size:15px; text-transform: uppercase;"> <?php print $website_title; ?> </b>
		</p>
	</div>
</div>

</div> <!-- /col -->
</div> <!-- /row -->
<div id="page"></div>

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>
</div> 
<!-- /container -->


<?php }else if(!empty($buyer_id) && $buyer_id=='ALL'){ ?>

<div class="container">
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a class="no-print" href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		</a>
	</div>
</div>
<?php
## create temporary table - buyer_contact
$this->db->query("DROP TABLE IF EXISTS `buyer_contact`");

// create buyer_contact
$this->db->query("CREATE TABLE `buyer_contact` AS

-- Buyer 1 data
SELECT 
    invoice_entry.lot_no, 
    invoice_entry.catalouge_name, 
    invoice_entry.w_house_id, 
    ws.warehouse_name,
    ws.short_key_word,
    ws.warehouse_address2,
    gs.garden_name,
    invoice_entry.invoice_group_txt, 
    invoice_entry.w_inv_no, 
    CONCAT(tg.grade_name, invoice_entry.additional_grade) AS grade_name,  
    invoice_entry.vat,
    (
        CASE 
            WHEN invoice_entry.catalouge_name = 'Leaf' THEN 'A' 
            WHEN invoice_entry.catalouge_name = 'Dust' THEN 'B'
            WHEN invoice_entry.catalouge_name = 'Supplement' THEN 'C'
            ELSE ''
        END  
    ) AS catalogue_order,
    sale_entry.buyer_type1 AS buyer_type,
    sale_entry.buyer_con1 AS buyer_con_no,
    sale_entry.buyer1 AS buyer_id,
    CONCAT(b1.buyer_name, ', ', b1.buyer_address1, ' BIN ', b1.buyer_bin_no, ' (', b1.buyer_type, ')') AS buyer_info,
    sale_entry.buyer1_bag AS total_pkgs,
    CONCAT(sale_entry.buyer1_kg_dm, '<br>', sale_entry.buyer1_smpl_dm) AS net_kgs,
    sale_entry.buyer1_total_kgs AS total_kgs,
    sale_entry.sold_price, 
    sale_entry.buyer1_amount AS amount
FROM 
    invoice_entry
JOIN 
    sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN 
    garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN 
    warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN 
    tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN 
    buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
WHERE 
    invoice_entry.season = '".$season."' 
    AND invoice_entry.auction_center = '".$auction_center."' 
    AND invoice_entry.sale_no = '".$sale_no."' 
    AND invoice_entry.catalogue_status = 'prepared' 
    AND invoice_entry.soft_delete = '1' 
    AND sale_entry.buyer1 = b1.id

UNION ALL

-- Buyer 2 data
SELECT 
    invoice_entry.lot_no, 
    invoice_entry.catalouge_name, 
    invoice_entry.w_house_id, 
    ws.warehouse_name,
    ws.short_key_word,
    ws.warehouse_address2,
    gs.garden_name,
    invoice_entry.invoice_group_txt, 
    invoice_entry.w_inv_no, 
    CONCAT(tg.grade_name, invoice_entry.additional_grade) AS grade_name,  
    invoice_entry.vat,
    (
        CASE 
            WHEN invoice_entry.catalouge_name = 'Leaf' THEN 'A' 
            WHEN invoice_entry.catalouge_name = 'Dust' THEN 'B'
            WHEN invoice_entry.catalouge_name = 'Supplement' THEN 'C'
            ELSE ''
        END  
    ) AS catalogue_order,
    sale_entry.buyer_type2 AS buyer_type,
    sale_entry.buyer_con2 AS buyer_con_no,
    sale_entry.buyer2 AS buyer_id,
    CONCAT(b2.buyer_name, ', ', b2.buyer_address1, ' BIN ', b2.buyer_bin_no, ' (', b2.buyer_type, ')') AS buyer_info,
    sale_entry.buyer2_bag AS total_pkgs,
    CONCAT(sale_entry.buyer2_kg_dm, '<br>', sale_entry.buyer2_smpl_dm) AS net_kgs,
    sale_entry.buyer2_total_kgs AS total_kgs,
    sale_entry.sold_price, 
    sale_entry.buyer2_amount AS amount
FROM 
    invoice_entry
JOIN 
    sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN 
    garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN 
    warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN 
    tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN 
    buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
WHERE 
    invoice_entry.season = '".$season."' 
    AND invoice_entry.auction_center = '".$auction_center."' 
    AND invoice_entry.sale_no = '".$sale_no."' 
    AND invoice_entry.catalogue_status = 'prepared' 
    AND invoice_entry.soft_delete = '1' 
    AND sale_entry.buyer2 = b2.id

UNION ALL

-- Buyer 3 data
SELECT 
    invoice_entry.lot_no, 
    invoice_entry.catalouge_name, 
    invoice_entry.w_house_id, 
    ws.warehouse_name,
    ws.short_key_word,
    ws.warehouse_address2,
    gs.garden_name,
    invoice_entry.invoice_group_txt, 
    invoice_entry.w_inv_no, 
    CONCAT(tg.grade_name, invoice_entry.additional_grade) AS grade_name,  
    invoice_entry.vat,
    (
        CASE 
            WHEN invoice_entry.catalouge_name = 'Leaf' THEN 'A' 
            WHEN invoice_entry.catalouge_name = 'Dust' THEN 'B'
            WHEN invoice_entry.catalouge_name = 'Supplement' THEN 'C'
            ELSE ''
        END  
    ) AS catalogue_order,
    sale_entry.buyer_type3 AS buyer_type,
    sale_entry.buyer_con3 AS buyer_con_no,
    sale_entry.buyer3 AS buyer_id,
    CONCAT(b3.buyer_name, ', ', b3.buyer_address1, ' BIN ', b3.buyer_bin_no, ' (', b3.buyer_type, ')') AS buyer_info,
    sale_entry.buyer3_bag AS total_pkgs,
    CONCAT(sale_entry.buyer3_kg_dm, '<br>', sale_entry.buyer3_smpl_dm) AS net_kgs,
    sale_entry.buyer3_total_kgs AS total_kgs,
    sale_entry.sold_price, 
    sale_entry.buyer3_amount AS amount
FROM 
    invoice_entry
JOIN 
    sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN 
    garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN 
    warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN 
    tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN 
    buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 
WHERE 
    invoice_entry.season = '".$season."' 
    AND invoice_entry.auction_center = '".$auction_center."' 
    AND invoice_entry.sale_no = '".$sale_no."' 
    AND invoice_entry.catalogue_status = 'prepared' 
    AND invoice_entry.soft_delete = '1' 
    AND sale_entry.buyer3 = b3.id;
");
// end

// wh & catalog breakdown
$page = 1;	
$query = $this->db->query("SELECT * FROM `buyer_contact` WHERE NOT `buyer_con_no` = '' GROUP BY `buyer_con_no`");
$inv_query = $query->result_array();
if($query->num_rows() > 0){
foreach($inv_query as $row_query){
?>

<style>
body{
	background-color: white !important;
	font-family:verdana;
}
.table{
	font-family:verdana;
	font-size:12px !important;
	border:none;
}
#table tr td{
	border-color:#CCCCCC !important;
	font-size:12px !important;
	padding:2px;
}
#table tr th{
	border-color:#CCCCCC !important;
	font-size:12px !important;
	padding:2px;
	vertical-align:middle;
}
.custom-font{
	font-family: Courier New; letter-spacing: 1.5px;
}


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

	.custom-font{
		font-family:'Times New Roman', serif; font-weight: 300; font-style: normal; letter-spacing: 1px;
	}

	[data-print="bill_contract_all"] {
		break-before: page;
		break-after: page;
		break-inside: avoid;
	}
}

@page {
  size: legal portrait;
  margin-top: 60px;
  margin-left: 50px;

  @top-right {
    content: "Page " counter(page) " of " counter(pages);
	font-size: 15px;
    font-family: Arial, sans-serif;
    color: #333;
  }
}

</style>
<?php
$query = $this->db->query("SELECT * FROM buyer_contact WHERE `buyer_con_no`='".$row_query['buyer_con_no']."' GROUP BY buyer_type, w_house_id, buyer_id ORDER BY `w_house_id` ASC");
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
	$page_counter = $page++;
?>
<!-- <div class="row">
	<div class="col-sm-12 text-left">
		<a href="#" style="text-decoration:none;" class="no-print" onClick="printDiv('print_div_area<?php print $page_counter; ?>');">
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </span>
		</a> 
		&nbsp; Page <?php print $page_counter; ?> 
	</div>
</div> -->
<div class="row print_div" id="print_div_area<?php print $page_counter; ?>">
<div class="col-sm-12" style="margin-bottom:100px;" data-print="bill_contract_all">
	
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td class="custom-font" style="text-align:left; border:0px; font-size:13px; text-transform: uppercase;" colspan="3">
		<?php print $website_title; ?>
	</td>
	<td colspan="6">
		<span class="custom-font" style="font-size:11px; text-transform: capitalize;"><?php print $row_data['warehouse_name']; ?>, <?php print $row_data['warehouse_address2']; ?></span>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px;" class="custom-font" colspan="9">
		<div style="font-size:11px; margin-bottom:5px;">Sale No. <?php print $sale_no; ?> &nbsp;&nbsp; Prompt <?php print date("d/m/Y",strtotime($sale_data['buyer_prompt_date'])); ?> &nbsp;&nbsp; Contract / D. O. No. <?php print $sale_no.'.'.$row_data['buyer_id'] . '/'. $row_data['w_house_id']; ?> <?php print $row_data['buyer_con_no']; ?> &nbsp;&nbsp; Date: <?php print date("d/m/Y",strtotime($sale_data['sale_date'])); ?> &nbsp;&nbsp; Shed : <?php print $row_data['short_key_word']; ?></div> 

		<div style="font-size:11px; margin-bottom:5px;">Messers: <span style="text-transform: uppercase;"><?php print $row_data['buyer_info']; ?></span> ### <b style="font-size:15px;"><?php print $page_counter; ?></b></div> 
		
		<div style="font-size:11px;">We have bought by your order and account the following lots of tea:(Brokerage Tk. 10.00/100 kg)</div>
	</td>
</tr>
<tr>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Lot</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Garden</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Invoice</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Grade</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Pkgs</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; white-space:nowrap; font-size:11px !important;">Net Kg.</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Total Kg.</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Price</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Amount</td>
</tr>
</thead>	
<tbody>	
<?php
$sl = 1;
$total_pkgs = 0;
$total_kgs = 0;
$total_amount = 0;

// query
$sql = $this->db->query("SELECT * FROM buyer_contact WHERE buyer_type='".$row_data['buyer_type']."' AND w_house_id='".$row_data['w_house_id']."' AND `buyer_con_no`='".$row_data['buyer_con_no']."' AND `buyer_id`='".$row_data['buyer_id']."' ORDER BY lot_no ASC");
$contact_data = $sql->result_array();
foreach($contact_data as $row2){
?>
<tr>
	<td style="text-align:center; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['garden_name']; ?>
	</td>
	<td style="text-align:center; border:none; text-transform:uppercase;">
		<?php print $row2['invoice_group_txt']; ?> <?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:center; border:none;">
		<?php print $row2['total_pkgs']; ?>
	</td>
	<td style="text-align:right; border:none; white-space:nowrap;">
		<?php print $row2['net_kgs']; ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['amount'],2,'.',','); ?>
	</td>
</tr>	

<?php
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
	
} // foreach
?>

<tr class="text-danger">
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; text-transform:uppercase; border-top: 1px dashed black !important;" colspan="2"> Sub Total : </td>
	<td style="text-align:center; vertical-align:middle; border-top: 1px dashed black !important;"><?php print $total_pkgs; ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"></td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_kgs,2,'.',','); ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"> Tk.</td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_amount,2,'.',','); ?></td>
</tr>

<?php if($row_data['buyer_type']=='Internal'){ ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"> VAT <?php print $row_data['vat']; ?>% Tk. </td>
	
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;">
	<?php 
		$total_vat = (($total_amount * $row_data['vat'])/100);
		print number_format($total_vat,2,'.',','); 
	?>
	</td>
</tr>

<?php }else{ $total_vat = 0; } ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"> TOTAL : Tk. </th>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><span style="border-bottom: double black !important;"><?php print number_format($total_amount + $total_vat,2,'.',','); ?></span></td>
</tr>
</tbody>
</table>

</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<b class="custom-font" style="font-size:13px; text-transform: uppercase;"> <?php print $website_title; ?> </b>
		</p>
	</div>
</div>
</div> <!-- /col -->
</div> <!-- /row -->
<?php } ?>
<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>
</div>
<!-- /container -->

<?php }else{ ?> 

<div class="container">
<div class="row no-print">
	<!-- <div class="col-sm-6 text-left">
		<a href="#" style="text-decoration:none;" onClick="fn_view_report('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $buyer_id; ?>','Leaf');">
			<span class="label label-primary" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Leaf Contract </span> 
		</a>
		&nbsp; &nbsp;
		<a class="no-print" href="#" style="text-decoration:none;" onClick="fn_view_report('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $buyer_id; ?>','Dust');">
			<span class="label label-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Dust Contract </span> 
		</a>
	</div> -->
	<div class="col-sm-12 text-right">
		<!--<a class="no-print d-none" href="#" style="text-decoration:none;" onClick="fn_window_print('Continuously Print');">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print Continuously </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		</a>-->
		<a class="no-print" href="#" style="text-decoration:none;" onClick="fn_window_print('Break Print');">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print With Page Breaks </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		</a>
	</div>
</div>
<?php
## create temporary table - buyer_contact
$this->db->query("DROP TABLE IF EXISTS `buyer_contact`");

// Build the base WHERE clause
$where_clause = "invoice_entry.season='".$season."' 
    AND invoice_entry.auction_center='".$auction_center."' 
    AND invoice_entry.sale_no='".$sale_no."' 
    AND invoice_entry.catalogue_status='prepared' 
    AND invoice_entry.soft_delete='1' 
    AND (sale_entry.buyer1='".$buyer_id."' OR sale_entry.buyer2 = '".$buyer_id."' OR sale_entry.buyer3 = '".$buyer_id."') 
    AND (sale_entry.buyer_con1='".$buyer_con."' OR sale_entry.buyer_con2 = '".$buyer_con."' OR sale_entry.buyer_con3 = '".$buyer_con."')";

// Add warehouse filter if provided
if (!empty($w_house_id)) {
    $where_clause .= " AND invoice_entry.w_house_id = '".$w_house_id."'";
}

// create buyer_contact with the complete WHERE clause
$this->db->query("CREATE TABLE `buyer_contact` (
SELECT 
    invoice_entry.lot_no, 
    invoice_entry.catalouge_name, 
    invoice_entry.w_house_id, 
    ws.warehouse_name,
    ws.short_key_word,
    ws.warehouse_address2,
    gs.garden_name,
    invoice_entry.invoice_group_txt, 
    invoice_entry.w_inv_no, 
    CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,    
    invoice_entry.vat, 
    
    (
        case 
            when invoice_entry.catalouge_name = 'Leaf' then 'A' 
            when invoice_entry.catalouge_name = 'Dust' then 'B'
            when invoice_entry.catalouge_name = 'Supplement' then 'C'
            else ''
        end    
    )  AS catalogue_order,

    (CASE 
        WHEN IFNULL(sale_entry.buyer1_bag,0) > 0 AND sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer_type1 
        WHEN IFNULL(sale_entry.buyer2_bag,0) > 0 AND sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer_type2 
        WHEN IFNULL(sale_entry.buyer3_bag,0) > 0 AND sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer_type3 
        ELSE ''
        END
    ) AS buyer_type,

    (CASE 
        WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer_con1 
        WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer_con2 
        WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer_con3
        ELSE ''
        END
    ) AS buyer_con_no,
    
    @total_pkgs:=(
        IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_bag END),0) 
        + 
        IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_bag END),0) 
        + 
        IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_bag END),0)
    ) AS total_pkgs,

    @net_kgs:=(
    CASE 
        WHEN sale_entry.buyer1 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer1_kg_dm,'<br>',sale_entry.buyer1_smpl_dm) 
        WHEN sale_entry.buyer2 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer2_kg_dm,'<br>',sale_entry.buyer2_smpl_dm) 
        WHEN sale_entry.buyer3 = '".$buyer_id."' THEN CONCAT(sale_entry.buyer3_kg_dm,'<br>',sale_entry.buyer3_smpl_dm) 
        ELSE ''
    END    
    ) AS net_kgs,

    @total_kgs:=(
        IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_total_kgs END),0) 
        + 
        IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_total_kgs END),0) 
        + 
        IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_total_kgs END),0)
    ) AS total_kgs,    

    sale_entry.sold_price,            

    @amount:=(
        IFNULL((CASE WHEN sale_entry.buyer1 = '".$buyer_id."' THEN sale_entry.buyer1_amount END),0) 
        + 
        IFNULL((CASE WHEN sale_entry.buyer2 = '".$buyer_id."' THEN sale_entry.buyer2_amount END),0) 
        + 
        IFNULL((CASE WHEN sale_entry.buyer3 = '".$buyer_id."' THEN sale_entry.buyer3_amount END),0)
    ) AS amount
    
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

WHERE ".$where_clause." GROUP BY invoice_entry.id) ");
// end

// wh & catalog breakdown
$page = 1;	
$query = $this->db->query("SELECT * FROM `buyer_contact` WHERE NOT `buyer_con_no` = '' GROUP BY `buyer_con_no`");
$inv_query = $query->result_array();
if($query->num_rows() > 0){
foreach($inv_query as $row_query){
?>

<style>
body{
	background-color: white !important;
	font-family:verdana;
}
.table{
	font-family:verdana;
	font-size:12px !important;
	border:none;
}
#table tr td{
	border-color:#CCCCCC !important;
	font-size:12px !important;
	padding:2px;
}
#table tr th{
	border-color:#CCCCCC !important;
	font-size:12px !important;
	padding:2px;
	vertical-align:middle;
}
.custom-font{
	font-family: Courier New; letter-spacing: 1.5px;
}


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

	.custom-font{
		font-family:'Times New Roman', serif; font-weight: 300; font-style: normal; letter-spacing: 1px;
	}

	.break-page {
		break-before: page;
		break-after: page;
		break-inside: avoid;
	}
}

@page {
  size: legal portrait;
  margin-top: 60px;
  margin-left: 50px;

  @top-right {
    content: "Page " counter(page) " of " counter(pages);
	font-size: 13px;
	margin-top: 30px;
    font-family: Arial, sans-serif;
    color: #333;
  }
}

</style>
<?php
$query = $this->db->query("SELECT * FROM buyer_contact 
            WHERE `buyer_con_no`='".$row_query['buyer_con_no']."' 
            ".($w_house_id ? "AND w_house_id = '".$w_house_id."'" : "")."
            GROUP BY buyer_type, w_house_id 
            ORDER BY w_house_id ASC");
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
	$page_counter = $page++;
?>
<!-- <div class="row">
	<div class="col-sm-12 text-left">
		<a href="#" style="text-decoration:none;" class="no-print" onClick="printDiv('print_div_area<?php print $page_counter; ?>');">
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </span>
		</a> 
		&nbsp; Page <?php print $page_counter; ?> 
	</div>
</div> -->
<div class="row print_div" id="print_div_area<?php print $page_counter; ?>">
<div class="col-sm-12" style="margin-bottom:60px;" data-print="bill_contract_all">
	
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td class="custom-font" style="text-align:left; border:0px; font-size:13px; text-transform: uppercase;" colspan="3">
		<?php print $website_title; ?>
	</td>
	<td colspan="6">
		<span class="custom-font" style="font-size:11px; text-transform: capitalize;"><?php print $row_data['warehouse_name']; ?>, <?php print $row_data['warehouse_address2']; ?></span>
	</td>
</tr>
<tr>
	<td style="text-align:left; border:0px;" class="custom-font" colspan="9">
		<div style="font-size:11px; margin-bottom:10px;">Sale No. <?php print $sale_no; ?> &nbsp;&nbsp; Prompt <?php print date("d/m/Y",strtotime($sale_data['buyer_prompt_date'])); ?> &nbsp;&nbsp; Contract / D. O. No. <?php print $sale_no.'.'.$buyer_id . '/'. $row_data['w_house_id']; ?> <?php print $row_data['buyer_con_no']; ?> &nbsp;&nbsp; Date: <?php print date("d/m/Y",strtotime($sale_data['sale_date'])); ?> &nbsp;&nbsp; Shed : <?php print $row_data['short_key_word']; ?></div> 

		<div style="font-size:11px; margin-bottom:10px;">Messers: <span style="text-transform: uppercase;"><?php print $buyer_data['buyer_name']; ?>, <?php print $buyer_data['buyer_address1']; ?> BIN <?php print $buyer_data['buyer_bin_no']; ?> (<?php print $row_data['buyer_type']; ?>)</span></div> 
		
		<div style="font-size:11px;">We have bought by your order and account the following lots of tea:(Brokerage Tk. 10.00/100 kg)</div>
	</td>
</tr>
<tr>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Lot</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Garden</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Invoice</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Grade</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Pkgs</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; white-space:nowrap; font-size:11px !important;">Net Kg.</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Total Kg.</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Price</td>
	<td class="custom-font" style="text-align:center; vertical-align:middle; border:1px solid black !important; font-size:11px !important;">Amount</td>
</tr>
</thead>	
<tbody>	
<?php
$sl = 1;
$total_pkgs = 0;
$total_kgs = 0;
$total_amount = 0;

// query
$sql = $this->db->query("SELECT * FROM buyer_contact 
    WHERE buyer_type='".$row_data['buyer_type']."' 
    AND w_house_id='".$row_data['w_house_id']."' 
    AND `buyer_con_no`='".$row_data['buyer_con_no']."' 
    ORDER BY lot_no ASC");
$contact_data = $sql->result_array();
foreach($contact_data as $row2){
?>
<tr>
	<td style="text-align:center; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['garden_name']; ?>
	</td>
	<td style="text-align:center; border:none; text-transform:uppercase;">
		<?php print $row2['invoice_group_txt']; ?> <?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:center; border:none;">
		<?php print $row2['total_pkgs']; ?>
	</td>
	<td style="text-align:right; border:none; white-space:nowrap;">
		<?php print $row2['net_kgs']; ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; border:none;">
		<?php print number_format($row2['amount'],2,'.',','); ?>
	</td>
</tr>	

<?php
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
	
} // foreach
?>

<tr class="text-danger">
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; text-transform:uppercase; border-top: 1px dashed black !important;" colspan="2"> Sub Total : </td>
	<td style="text-align:center; vertical-align:middle; border-top: 1px dashed black !important;"><?php print $total_pkgs; ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"></td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_kgs,2,'.',','); ?></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"> Tk.</td>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><?php print number_format($total_amount,2,'.',','); ?></td>
</tr>

<?php if($row_data['buyer_type']=='Internal'){ ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"> VAT <?php print $row_data['vat']; ?>% Tk. </td>
	
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;">
	<?php 
		$total_vat = (($total_amount * $row_data['vat'])/100);
		print number_format($total_vat,2,'.',','); 
	?>
	</td>
</tr>

<?php }else{ $total_vat = 0; } ?>

<tr>
	<td style="border:none;"></td>
	<td style="border:none;"></td>
	<td class="custom-font" style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;" colspan="6"> TOTAL : Tk. </th>
	<td style="text-align:right; vertical-align:middle; border-top: 1px dashed black !important;"><span style="border-bottom: double black !important;"><?php print number_format($total_amount + $total_vat,2,'.',','); ?></span></td>
</tr>
</tbody>
</table>

</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<p class="text-left" style="font-family: verdana; border:0px; margin-top:30px;"> 
			<b class="custom-font" style="font-size:13px; text-transform: uppercase;"> <?php print $website_title; ?> </b>
		</p>
	</div>
</div>
</div> <!-- /col -->
</div> <!-- /row -->
<?php } ?>
<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>
</div>
<!-- /container -->

<?php } ?>

<script>	
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}

// Dynamically print
function fn_window_print(print_type) {
	const elements = document.querySelectorAll('[data-print="bill_contract_all"]');

	if (print_type === 'Break Print') {
		elements.forEach(el => el.classList.add('break-page'));
	} else {
		elements.forEach(el => el.classList.remove('break-page'));
	}

	window.print();
}


function fn_view_report(season,auction_center,sale_no,buyer_id,catalogue_name){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(buyer_id==''){ alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }
	if(catalogue_name==''){ alert("Please Fill out Catalogue Name."); $("#catalogue_name").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/buyer_contract_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&buyer_id="+buyer_id+"&catalogue_name="+catalogue_name+"'); ?>","Calalogue Wise Contract","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
</script>

</body>
</html>