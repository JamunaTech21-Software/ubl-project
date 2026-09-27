<!DOCTYPE html>
<html lang="en">
<head>
<title>DELIVERY ORDER</title>
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
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';
$catalogue_name = (!empty($this->input->get('catalogue_name', TRUE))) ? $this->input->get('catalogue_name', TRUE) : '';
$report_no = (!empty($this->input->get('report_no', TRUE))) ? $this->input->get('report_no', TRUE) : '';

## buyer details
$query = $this->db->query("SELECT * FROM buyer_setup WHERE id='".$buyer_id."' ");
$buyer_data = $query->row_array();

## Sale details
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$sale_data = $query->row_array();
?>

<?php if($report_no=='1'){ ?>

<div class="container">
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
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
	gs.garden_name,
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
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:#CCCCCC !important;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print D.O</span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>
	
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td colspan="4" style="text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<i><b style="font-size:16px;"><?php print $website_title; ?></i></b> <br /> 
		<i style="font-size:14px;"><?php print $owner_short_address; ?></i>
		<br />
		<b class="text-primary" style="font-size:14px;">
			<?php print $buyer_data['buyer_name']; ?>
		</b>
		<br />
		<span class="text-danger" style="text-transform:uppercase; font-size:12px;">(<?php print $row_data['buyer_type']; ?>)</span>
	</td>
	<td colspan="2" style="border:none; text-align;center;"><b style="font-family: verdana; font-size:16px; padding:5px;">DELIVERY ORDER</b></td>
	<td colspan="2" style="font-size:14px; text-align:right; text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<b>SALE NO : </b> <br />
		SALE DATE : <br />
		PROMPT DATE : <br />
		D.O NO : 
	</td>
	<td style="font-size:14px; text-align:left; text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<b>&nbsp;<?php print $sale_no; ?></b><br />
		&nbsp;<?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?><br />
		&nbsp;<?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?><br />
		&nbsp;<?php print $sale_no.'.'.$buyer_id . '/'. $row_data['w_house_id']; ?><br />
		&nbsp;<?php print $catalogue_name; ?>
	</td>
</tr>
<tr>
	<td colspan="9" style="border:none;">We have this day bought your order and account at Auction Sales the following lots of Tea, Viz <br /> (Terms & Condition as usual Brokerage Tk. 10 per 100 kg.)
	</td>
</tr>
<tr>
	<td colspan="9" style="border:none;"><b style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px; color:red;">SHED: <?php print $row_data['warehouse_name']; ?></b>
	</td>
</tr>
<tr>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Lot</td>
	<td style="text-align:left; vertical-align:middle; text-transform:uppercase; border:1px solid;">Garden</td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Invoice</td>
	<td style="text-align:left; vertical-align:middle; text-transform:uppercase; border:1px solid;">Grade</td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Pkgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid; white-space:nowrap;">Net Kgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Total Kgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Price</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Amount</td>
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
		<?php print $row2['w_inv_no']; ?>
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
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border-top:1px solid; border-bottom:1px solid;" colspan="2"> Sub Total : </td>
	<td style="text-align:center; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print $total_pkgs; ?></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print number_format($total_kgs,2,'.',','); ?></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"> Tk.</td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print number_format($total_amount,2,'.',','); ?></td>
</tr>

<?php if($row_data['buyer_type']=='Internal'){ ?>

<tr>
	<td style="text-align:right; vertical-align:middle; border:none;" colspan="8"> VAT <?php print $row_data['vat']; ?>% Tk. </td>
	<td style="text-align:right; vertical-align:middle; border:none;">
	<?php 
		$total_vat = (($total_amount * $row_data['vat'])/100);
		print number_format($total_vat,2,'.',','); 
	?>
	</td>
</tr>

<?php }else{ $total_vat = 0; } ?>

<tr>
	<td style="text-align:right; vertical-align:middle; border:none; color:red;" colspan="8"><b> TOTAL Tk. </b></th>
	<td style="text-align:right; vertical-align:middle; border:none; color:red;"><b><?php print number_format($total_amount + $total_vat,2,'.',','); ?></b></td>
</tr>
</tbody>
</table>

</div>
</div>

<div class="row">
	<div class="col-sm-6 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> </b>
		</p>
	</div>
</div>

</div>
</div> 
<!-- /row -->
<div id="page"></div>

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>
</div> 
<!-- /container -->


<?php }else if($report_no=='2'){ ?>

<div class="container">
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
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
	gs.garden_name,
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
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:#CCCCCC !important;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print D.O</span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>
	
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td colspan="4" style="text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<i><b style="font-size:16px;"><?php print $website_title; ?></i></b> <br /> 
		<i style="font-size:14px;"><?php print $owner_short_address; ?></i>
		<br />
		<b class="text-primary" style="font-size:14px;">
			<?php print $buyer_data['buyer_name']; ?>
		</b>
		<br />
		<span class="text-danger" style="text-transform:uppercase; font-size:12px;">(<?php print $row_data['buyer_type']; ?>)</span>
	</td>
	<td colspan="2" style="border:none; text-align;center;"><b style="font-family: verdana; font-size:16px; padding:5px;">DELIVERY ORDER</b></td>
	<td colspan="2" style="font-size:14px; text-align:right; text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<b>SALE NO : </b> <br />
		SALE DATE : <br />
		PROMPT DATE : <br />
		D.O NO : 
	</td>
	<td style="font-size:14px; text-align:left; text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<b>&nbsp;<?php print $sale_no; ?></b><br />
		&nbsp;<?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?><br />
		&nbsp;<?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?><br />
		&nbsp;<?php print $sale_no.'.'.$buyer_id . '/'. $row_data['w_house_id']; ?><br />
		&nbsp;<?php print $catalogue_name; ?>
	</td>
</tr>
<tr>
	<td colspan="9" style="border:none;">We have this day bought your order and account at Auction Sales the following lots of Tea, Viz <br /> (Terms & Condition as usual Brokerage Tk. 10 per 100 kg.)
	</td>
</tr>
<tr>
	<td colspan="9" style="border:none;"><b style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px; color:red;">SHED: <?php print $row_data['warehouse_name']; ?></b>
	</td>
</tr>
<tr>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Lot</td>
	<td style="text-align:left; vertical-align:middle; text-transform:uppercase; border:1px solid;">Garden</td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Invoice</td>
	<td style="text-align:left; vertical-align:middle; text-transform:uppercase; border:1px solid;">Grade</td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Pkgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid; white-space:nowrap;">Net Kgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Total Kgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Price</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Amount</td>
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
		<?php print $row2['w_inv_no']; ?>
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
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border-top:1px solid; border-bottom:1px solid;" colspan="2"> Sub Total : </td>
	<td style="text-align:center; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print $total_pkgs; ?></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print number_format($total_kgs,2,'.',','); ?></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"> Tk.</td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print number_format($total_amount,2,'.',','); ?></td>
</tr>

<?php if($row_data['buyer_type']=='Internal'){ ?>

<tr>
	<td style="text-align:right; vertical-align:middle; border:none;" colspan="8"> VAT <?php print $row_data['vat']; ?>% Tk. </td>
	<td style="text-align:right; vertical-align:middle; border:none;">
	<?php 
		$total_vat = (($total_amount * $row_data['vat'])/100);
		print number_format($total_vat,2,'.',','); 
	?>
	</td>
</tr>

<?php }else{ $total_vat = 0; } ?>

<tr>
	<td style="text-align:right; vertical-align:middle; border:none; color:red;" colspan="8"><b> TOTAL Tk. </b></th>
	<td style="text-align:right; vertical-align:middle; border:none; color:red;"><b><?php print number_format($total_amount + $total_vat,2,'.',','); ?></b></td>
</tr>
</tbody>
</table>

</div>
</div>

<div class="row">
	<div class="col-sm-6 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> </b>
		</p>
	</div>
</div>

</div>
</div> 
<!-- /row -->
<div id="page"></div>

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>
</div> 
<!-- /container -->


<?php }else if($report_no=='3'){ ?> 

<div class="container">
<div class="row no-print">
	<div class="col-sm-6 text-left">
		<a class="no-print" href="#" style="text-decoration:none;" onClick="fn_view_report('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $buyer_id; ?>','Leaf','1');">
			<span class="label label-primary" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Leaf D.O </span> 
		</a>
		&nbsp; &nbsp;
		<a class="no-print" href="#" style="text-decoration:none;" onClick="fn_view_report('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $buyer_id; ?>','Dust','2');">
			<span class="label label-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Dust D.O </span> 
		</a>
	</div>
	<div class="col-sm-6 text-right">
		<a class="no-print" href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
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
	gs.garden_name,
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND (sale_entry.buyer1='".$buyer_id."' OR sale_entry.buyer2 = '".$buyer_id."'OR sale_entry.buyer3 = '".$buyer_id."') GROUP BY invoice_entry.id) ");
// end

// wh & catalog breakdown
$page = 1;	
$query = $this->db->query("SELECT * FROM buyer_contact GROUP BY buyer_type, w_house_id ORDER BY catalogue_order ASC, buyer_type DESC");
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
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:#CCCCCC !important;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print D.O</span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>
	
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td colspan="4" style="text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<i><b style="font-size:16px;"><?php print $website_title; ?></i></b> <br /> 
		<i style="font-size:14px;"><?php print $owner_short_address; ?></i>
		<br />
		<b class="text-primary" style="font-size:14px;">
			<?php print $buyer_data['buyer_name']; ?>
		</b>
		<br />
		<span class="text-danger" style="text-transform:uppercase; font-size:12px;">(<?php print $row_data['buyer_type']; ?>)</span>
	</td>
	<td colspan="2" style="border:none; text-align;center; padding:0px;"><b style="font-family: verdana; font-size:16px; padding:5px;">DELIVERY ORDER</b></td>
	<td colspan="2" style="font-size:14px; text-align:right; text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<b>SALE NO : </b> <br />
		SALE DATE : <br />
		PROMPT DATE : <br />
		D.O NO : 
	</td>
	<td style="font-size:14px; text-align:left; text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<b>&nbsp;<?php print $sale_no; ?></b><br />
		&nbsp;<?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?><br />
		&nbsp;<?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?><br />
		&nbsp;<?php print $sale_no.'.'.$buyer_id . '/'. $row_data['w_house_id']; ?>
	</td>
</tr>
<tr>
	<td colspan="9" style="border:none;">We have this day bought your order and account at Auction Sales the following lots of Tea, Viz <br /> (Terms & Condition as usual Brokerage Tk. 10 per 100 kg.)
	</td>
</tr>
<tr>
	<td colspan="9" style="border:none;"><b style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px; color:red;">SHED: <?php print $row_data['warehouse_name']; ?></b>
	</td>
</tr>
<tr>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Lot</td>
	<td style="text-align:left; vertical-align:middle; text-transform:uppercase; border:1px solid;">Garden</td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Invoice</td>
	<td style="text-align:left; vertical-align:middle; text-transform:uppercase; border:1px solid;">Grade</td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase; border:1px solid;">Pkgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid; white-space:nowrap;">Net Kgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Total Kgs</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Price</td>
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border:1px solid;">Amount</td>
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
		<?php print $row2['w_inv_no']; ?>
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
	<td style="text-align:right; vertical-align:middle; text-transform:uppercase; border-top:1px solid; border-bottom:1px solid;" colspan="2"> Sub Total : </td>
	<td style="text-align:center; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print $total_pkgs; ?></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print number_format($total_kgs,2,'.',','); ?></td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"> Tk.</td>
	<td style="text-align:right; vertical-align:middle; border-top:1px solid; border-bottom:1px solid;"><?php print number_format($total_amount,2,'.',','); ?></td>
</tr>

<?php if($row_data['buyer_type']=='Internal'){ ?>

<tr>
	<td style="text-align:right; vertical-align:middle; border:none;" colspan="8"> VAT <?php print $row_data['vat']; ?>% Tk. </td>
	<td style="text-align:right; vertical-align:middle; border:none;">
	<?php 
		$total_vat = (($total_amount * $row_data['vat'])/100);
		print number_format($total_vat,2,'.',','); 
	?>
	</td>
</tr>

<?php }else{ $total_vat = 0; } ?>

<tr>
	<td style="text-align:right; vertical-align:middle; border:none; color:red;" colspan="8"><b> TOTAL Tk. </b></th>
	<td style="text-align:right; vertical-align:middle; border:none; color:red;"><b><?php print number_format($total_amount + $total_vat,2,'.',','); ?></b></td>
</tr>
</tbody>
</table>

</div>
</div>

<div class="row">
	<div class="col-sm-6 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> </b>
		</p>
	</div>
</div>

</div>
</div> 
<!-- /row -->
<div id="page"></div>

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>
</div>
<!-- /container -->

<?php }else if($report_no=='4'){ ?> 

<div class="container">
<div class="row">
	<div class="col-sm-6 text-left">
		<a class="no-print" href="#" style="text-decoration:none;" onClick="fn_view_report('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $buyer_id; ?>','Leaf','5');">
			<span class="label label-primary" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Leaf Challan </span> 
		</a>
		&nbsp; &nbsp;
		<a class="no-print" href="#" style="text-decoration:none;" onClick="fn_view_report('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $buyer_id; ?>','Dust','6');">
			<span class="label label-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Dust Challan </span> 
		</a>
	</div>
	<div class="col-sm-6 text-right">
		<a class="no-print" href="#" style="text-decoration:none;" onClick="window.print();">
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
	gs.garden_name,
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

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND (sale_entry.buyer1='".$buyer_id."' OR sale_entry.buyer2 = '".$buyer_id."'OR sale_entry.buyer3 = '".$buyer_id."') GROUP BY invoice_entry.id) ");
// end

// wh & catalog breakdown
$page = 1;	
$query = $this->db->query("SELECT * FROM buyer_contact GROUP BY buyer_type, w_house_id ORDER BY catalogue_order ASC, buyer_type DESC");
if($query->num_rows() > 0){
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
	$page_counter = $page++;
?>

<div class="row">
<div class="col-sm-12">

<div id="special_challan<?php print $page_counter; ?>">
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
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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
</style>

<?php
$total_pkgs = 0;
$total_kgs = 0;
$total_amount = 0;
// query
$sql = $this->db->query("SELECT * FROM buyer_contact WHERE buyer_type='".$row_data['buyer_type']."' AND w_house_id='".$row_data['w_house_id']."' ORDER BY lot_no ASC");
$contact_data = $sql->result_array();
foreach($contact_data as $row2){
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
} // foreach

$total_vat = (($total_amount * $row_data['vat'])/100);
?>

<div class="row">
	<div class="col-sm-12 text-right">
		<a class="no-print" href="#" style="text-decoration:none;" onClick="printDiv('special_challan<?php print $page_counter; ?>');">
			<span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>

<div class="row">
	<div class="col-sm-12 text-center"> 
		<span style="font-family: verdana; border:0px;"> 
			<b style="font-size:16px;">ফরম - গ  </b>
			<br />
			<b style="font-size:16px;">SPECIAL CHALLAN TEA</b> 
			<br /> 
			<b style="font-size:18px; letter-spacing: 3px; text-transform:uppercase;"><?php print $website_title; ?></b> 
			<br /> 
			<i><?php print $owner_short_address; ?></i>
		</span>
	</div>
</div>

<br />
	
<div class="row">	
	<div class="col-sm-6">
		<span class="text-left" style="font-family: verdana; border:0px;">
			<b style="font-size:14px; text-transform:uppercase;"><?php print $buyer_data['buyer_name']; ?></b> <br /> 
			<span style="text-transform:uppercase;"><?php print $row_data['buyer_type']; ?></span>
		</span>
	</div>
	<div class="col-sm-6">
		<table style="float:right;">
			<tr>
				<th class="text-right" style="font-family: verdana; border:0px; font-size:14px;"> SALE NO :&nbsp;</th> 
				<th class="text-left" style="font-family: verdana; border:0px; font-size:14px;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> SEASON :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print $season; ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> SALE DATE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> PROMPT DATE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px; font-size:14px;;"> VAT REGISTRATION NO :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:14px;;">
					002406917
				</td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px; font-size:14px;;"> AREA CODE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:14px;;">
					0503
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 text-left"><p style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px;;">SHED: <?php print $row_data['warehouse_name']; ?></p></div>
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table" style="font-size:12px;">
			<tr style="border:1px solid #CCCCCC;">
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Pkgs</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Net Kg.</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Total Amount</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">VAT @ <?php print $row_data['vat']; ?>% Tk.</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">TOTAL VALUE INCLUDING VAT</td>
			</tr>
			<tr>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print $total_pkgs; ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_kgs,2,'.',','); ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_amount,2,'.',','); ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;">
				<?php 
					if($row_data['buyer_type']=='Internal'){ print number_format($total_vat,2,'.',','); }else{ print $total_vat='0.00'; }
				?>
				</th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_amount + $total_vat,2,'.',','); ?></th>
			</tr>
		</table>
	</div>
</div>	

<br />

<div class="row">
	<div class="col-sm-12 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px; float:right;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> &nbsp; &nbsp; &nbsp; </b>
		</p>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<small style="text-align:left;">C.C.TO. </small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> CUSTOM, EXCISE & VAT COLLECTORATE, <?php print $custom_address; ?></b>
		</p>
	</div>
</div>

</div>
<!-- /special_challan-->

</div>
</div> 
<!-- /row -->
<div id="page"></div>
<hr class="no-print" />

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>

</div>
<!-- /container-->

<?php }else if($report_no=='5'){ ?> 

<div class="container">
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
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
	gs.garden_name,
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
$query = $this->db->query("SELECT * FROM buyer_contact GROUP BY buyer_type, w_house_id ORDER BY catalogue_order ASC, buyer_type DESC");
if($query->num_rows() > 0){
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
	$page_counter = $page++;
?>

<div class="row">
<div class="col-sm-12">

<div id="special_challan<?php print $page_counter; ?>">
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
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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
</style>

<?php
$total_pkgs = 0;
$total_kgs = 0;
$total_amount = 0;
// query
$sql = $this->db->query("SELECT * FROM buyer_contact WHERE buyer_type='".$row_data['buyer_type']."' AND w_house_id='".$row_data['w_house_id']."' ORDER BY lot_no ASC");
$contact_data = $sql->result_array();
foreach($contact_data as $row2){
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
} // foreach

$total_vat = (($total_amount * $row_data['vat'])/100);
?>
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="printDiv('special_challan<?php print $page_counter; ?>');"><span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print Challan</span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>
	
<div class="row">
	<div class="col-sm-12 text-center"> 
		<span style="font-family: verdana; border:0px;"> 
			<b style="font-size:16px;">ফরম - গ  </b>
			<br />
			<b style="font-size:16px;">SPECIAL CHALLAN TEA</b> 
			<br /> 
			<b style="font-size:18px; letter-spacing: 3px; text-transform:uppercase;"><?php print $website_title; ?></b> 
			<br /> 
			<i><?php print $owner_short_address; ?></i>
		</span>
	</div>
</div>

<br />
	
<div class="row">	
	<div class="col-sm-6">
		<span class="text-left" style="font-family: verdana; border:0px;">
			<b style="font-size:14px; text-transform:uppercase;"><?php print $buyer_data['buyer_name']; ?></b> <br /> 
			<span style="text-transform:uppercase;"><?php print $row_data['buyer_type']; ?></span>
			<br /><span style="text-transform:uppercase;"><?php print $catalogue_name; ?></span>
		</span>
	</div>
	<div class="col-sm-6">
		<table style="float:right;">
			<tr>
				<th class="text-right" style="font-family: verdana; border:0px; font-size:14px;"> SALE NO :&nbsp;</th> 
				<th class="text-left" style="font-family: verdana; border:0px; font-size:14px;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> SEASON :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print $season; ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> SALE DATE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> PROMPT DATE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px; font-size:14px;;"> VAT REGISTRATION NO :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:14px;;">
					002406917
				</td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px; font-size:14px;;"> AREA CODE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:14px;;">
					0503
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 text-left"><p style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px;;">SHED: <?php print $row_data['warehouse_name']; ?></p></div>
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table" style="font-size:12px;">
			<tr style="border:1px solid #CCCCCC;">
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Pkgs</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Net Kg.</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Total Amount</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">VAT @ <?php print $row_data['vat']; ?>% Tk.</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">TOTAL VALUE INCLUDING VAT</td>
			</tr>
			<tr>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print $total_pkgs; ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_kgs,2,'.',','); ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_amount,2,'.',','); ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;">
				<?php 
					if($row_data['buyer_type']=='Internal'){ print number_format($total_vat,2,'.',','); }else{ print $total_vat='0.00'; }
				?>
				</th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_amount + $total_vat,2,'.',','); ?></th>
			</tr>
		</table>
	</div>
</div>	

<br />

<div class="row">
	<div class="col-sm-12 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px; float:right;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> &nbsp; &nbsp; &nbsp; </b>
		</p>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<small style="text-align:left;">C.C.TO. </small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> CUSTOM, EXCISE & VAT COLLECTORATE, <?php print $custom_address; ?></b>
		</p>
	</div>
</div>

</div>
<!-- /special_challan-->

</div>
</div> 
<!-- /row -->
<div id="page"></div>

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>

</div>
<!-- /container-->

<?php }else if($report_no=='6'){ ?> 

<div class="container">
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="window.print();">
			<span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
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
	gs.garden_name,
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
$query = $this->db->query("SELECT * FROM buyer_contact GROUP BY buyer_type, w_house_id ORDER BY catalogue_order ASC, buyer_type DESC");
if($query->num_rows() > 0){
$inv_query = $query->result_array();
foreach($inv_query as $row_data){
	$page_counter = $page++;
?>

<div class="row">
<div class="col-sm-12">

<div id="special_challan<?php print $page_counter; ?>">
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
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
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
</style>

<?php
$total_pkgs = 0;
$total_kgs = 0;
$total_amount = 0;
// query
$sql = $this->db->query("SELECT * FROM buyer_contact WHERE buyer_type='".$row_data['buyer_type']."' AND w_house_id='".$row_data['w_house_id']."' ORDER BY lot_no ASC");
$contact_data = $sql->result_array();
foreach($contact_data as $row2){
	$total_pkgs += $row2['total_pkgs'];
	$total_kgs += $row2['total_kgs'];
	$total_amount += $row2['amount'];
} // foreach

$total_vat = (($total_amount * $row_data['vat'])/100);
?>

<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a href="#" style="text-decoration:none;" onClick="printDiv('special_challan<?php print $page_counter; ?>');"><span class="label label-danger" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print Challan</span>
		</a> &nbsp; Page <?php print $page_counter; ?> 
	</div>
</div>

<div class="row">
	<div class="col-sm-12 text-center"> 
		<span style="font-family: verdana; border:0px;"> 
			<b style="font-size:16px;">ফরম - গ  </b>
			<br />
			<b style="font-size:16px;">SPECIAL CHALLAN TEA</b> 
			<br /> 
			<b style="font-size:18px; letter-spacing: 3px; text-transform:uppercase;"><?php print $website_title; ?></b> 
			<br /> 
			<i><?php print $owner_short_address; ?></i>
		</span>
	</div>
</div>

<br />
	
<div class="row">	
	<div class="col-sm-6">
		<span class="text-left" style="font-family: verdana; border:0px;">
			<b style="font-size:14px; text-transform:uppercase;"><?php print $buyer_data['buyer_name']; ?></b> <br /> 
			<span style="text-transform:uppercase;"><?php print $row_data['buyer_type']; ?></span>
			<br /><span style="text-transform:uppercase;"><?php print $catalogue_name; ?></span>
		</span>
	</div>
	<div class="col-sm-6">
		<table style="float:right;">
			<tr>
				<th class="text-right" style="font-family: verdana; border:0px; font-size:14px;"> SALE NO :&nbsp;</th> 
				<th class="text-left" style="font-family: verdana; border:0px; font-size:14px;"><?php print $sale_no; ?></th>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> SEASON :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print $season; ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> SALE DATE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px;"> PROMPT DATE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px;"><?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px; font-size:14px;;"> VAT REGISTRATION NO :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:14px;;">
					002406917
				</td>
			</tr>
			<tr>
				<td class="text-right" style="font-family: verdana; border:0px; font-size:14px;;"> AREA CODE :&nbsp;</td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:14px;;">
					0503
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 text-left"><p style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px;;">SHED: <?php print $row_data['warehouse_name']; ?></p></div>
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table" style="font-size:12px;">
			<tr style="border:1px solid #CCCCCC;">
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Pkgs</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Net Kg.</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">Total Amount</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">VAT @ <?php print $row_data['vat']; ?>% Tk.</td>
				<td style="text-align:right; vertical-align:middle; padding:1px; border:none;">TOTAL VALUE INCLUDING VAT</td>
			</tr>
			<tr>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print $total_pkgs; ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_kgs,2,'.',','); ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_amount,2,'.',','); ?></th>
				<th style="text-align:right; vertical-align:middle; border:none;">
				<?php 
					if($row_data['buyer_type']=='Internal'){ print number_format($total_vat,2,'.',','); }else{ print $total_vat='0.00'; }
				?>
				</th>
				<th style="text-align:right; vertical-align:middle; border:none;"><?php print number_format($total_amount + $total_vat,2,'.',','); ?></th>
			</tr>
		</table>
	</div>
</div>	

<br />

<div class="row">
	<div class="col-sm-12 text-primary">
		<p class="text-left" style="font-family: verdana; border:0px; float:right;"> 
			<small style="text-align:center;">&nbsp; &nbsp; &nbsp; Yours faithfully</small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> <?php print $website_title; ?> &nbsp; &nbsp; &nbsp; </b>
		</p>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<p class="text-left" style="font-family: verdana; border:0px;"> 
			<small style="text-align:left;">C.C.TO. </small> <br /> For <b style="text-transform: uppercase; font-size:14px;;"> CUSTOM, EXCISE & VAT COLLECTORATE, <?php print $custom_address; ?></b>
		</p>
	</div>
</div>

</div>
<!-- /special_challan-->

</div>
</div> 
<!-- /row -->
<div id="page"></div>

<?php
} // foreach

}else{
	print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>

</div>
<!-- /container-->

<?php }else if($report_no=='7'){ ?>
<p>UNDER CONSTRUCTION....</p>
<?php } ?>


<script>
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}

function fn_view_report(season,auction_center,sale_no,buyer_id,catalogue_name,report_no){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(buyer_id==''){ alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }
	if(catalogue_name==''){ alert("Please Fill out Catalogue Name."); $("#catalogue_name").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/buyer_delivery_order_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&buyer_id="+buyer_id+"&catalogue_name="+catalogue_name+"&report_no="+report_no+"'); ?>","Calalogue Wise D.O","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
</script>

</body>
</html>