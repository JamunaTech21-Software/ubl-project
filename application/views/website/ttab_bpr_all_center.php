<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
$buyer_category = (!empty($this->input->get('buyer_category', TRUE))) ? $this->input->get('buyer_category', TRUE) : '';
$combine_center = (!empty($this->input->get('combine_center', TRUE))) ? $this->input->get('combine_center', TRUE) : '';

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
	
	@bottom-center {
		content: counter(page);
		font-style: italic
	}
}
</style>

<?php if($report_name=='TTAB_BP_list'){ ?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="javascript:void(0);" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php
## create temporary table - ttab_buyer_purchase
$this->db->query("DROP TABLE IF EXISTS `ttab_buyer_purchase`");

// create ttab_buyer_purchase @  buyer-1
$this->db->query("CREATE TABLE `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0)
	) AS current_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer1_bag END,0)
	) AS current_dust_bag,
	
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END,0)
	) AS upto_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END,0)
	) AS upto_dust_bag

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


// create ttab_buyer_purchase @  buyer-2
$this->db->query("INSERT INTO `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,
	sale_entry.buyer_type2 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0)
	) AS current_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer2_bag END,0)
	) AS current_dust_bag,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END,0)
	) AS upto_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END,0)
	) AS upto_dust_bag

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");


// create ttab_buyer_purchase @  buyer-3
$this->db->query("INSERT INTO `ttab_buyer_purchase` (
SELECT 

	invoice_entry.id,
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	
	/*==== CURRENT SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0)
	) AS current_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.auction_center='".$auction_center."' THEN sale_entry.buyer3_bag END,0)
	) AS current_dust_bag,
	
	/*==== UPTO SALE =====*/
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END,0)
	) AS upto_leaf_bag,
	
	(
		IFNULL(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END,0) + IFNULL(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END,0)
	) AS upto_dust_bag

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<tr>
	<th colspan="8" style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th colspan="8" style="text-align:left; font-size:14px !important; border:0px;"> 
	The Secretary<br />
	Tea Traders Association of Bangladesh<br />
	Chittagong.
	</th>
</tr>
<tr>
	<th colspan="8" style="text-align:center; font-size:14px !important; border:0px;">BUYER PURCHASE LIST</th>
</tr>
<tr>
	<th style="text-align:left; border:0px;">
		Dear Sir, <br /> We give below the purchase made by the following buyers in our catalogue upto sale No. <b><?php print $sale_no; ?> </b> &nbsp; Season: <?php print $season; ?>
	</th>
</tr>
</table>

<table class="table table-sm" id="table">
<thead>
<tr>
    <th rowspan="2" style="text-align:left; border:1px solid;">BUYER TYPE</th>
    <th rowspan="2" style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th colspan="2" style="text-align:center; border:1px solid;">CURRENT BAGS <br /> <?php print $auction_center; ?></th>
	<th colspan="2" style="text-align:center; border:1px solid;">UPTO DATE BAGS <br /><?php print substr($auction_center,0,4); ?> to <?php print substr($combine_center,0,4); ?></th>
</tr>
<tr>
	<th style="text-align:right; border:1px solid;">LEAF</th>
	<th style="text-align:right; border:1px solid;">DUST</th>
	<th style="text-align:right; border:1px solid;">LEAF</th>
	<th style="text-align:right; border:1px solid;">DUST</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;
$current_leaf_bag = 0;
$current_dust_bag = 0;
$upto_leaf_bag = 0;
$upto_dust_bag = 0;

// INTERNAL BUYER	
$this->db->select("ttab_bp.buyer_id, buyer_setup.buyer_name, ttab_bp.buyer_type, sum(ttab_bp.current_leaf_bag) AS current_leaf_bag, sum(ttab_bp.current_dust_bag) AS current_dust_bag, sum(ttab_bp.upto_leaf_bag) AS upto_leaf_bag, sum(ttab_bp.upto_dust_bag) AS upto_dust_bag");
$this->db->from('ttab_buyer_purchase ttab_bp');
$this->db->join('buyer_setup','ttab_bp.buyer_id = buyer_setup.id');
$this->db->where('ttab_bp.buyer_type','Internal');
$this->db->group_by('ttab_bp.buyer_id');
$this->db->order_by('buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	$count = $sl++;
?>

<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase; font-weight:bold;"><?php echo ($count==1) ? $row3['buyer_type'] : ''; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:1px solid; font-weight:bold;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_no.'&sale_to='.$sale_no.'&buyer_id='.$row3['buyer_id'].'&report=9'); ?>"><?php echo $row3['buyer_name']; ?></a>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_dust_bag']; ?></td>
	
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_dust_bag']; ?></td>
</tr>	

<?php
	$current_leaf_bag += $row3['current_leaf_bag'];
	$current_dust_bag += $row3['current_dust_bag'];
	$upto_leaf_bag += $row3['upto_leaf_bag'];
	$upto_dust_bag += $row3['upto_dust_bag'];

} // foreach
?>
<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:1px solid; color:red;">INTERNAL TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_leaf_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_dust_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_leaf_bag; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_dust_bag; ?></th>
</tr>


<?php
$sl2=1;
$current_leaf_bag2 = 0;
$current_dust_bag2 = 0;
$upto_leaf_bag2 = 0;
$upto_dust_bag2 = 0;

// EXPORT BUYER		
$this->db->select("ttab_bp.buyer_id, buyer_setup.buyer_name, ttab_bp.buyer_type, sum(ttab_bp.current_leaf_bag) AS current_leaf_bag, sum(ttab_bp.current_dust_bag) AS current_dust_bag, sum(ttab_bp.upto_leaf_bag) AS upto_leaf_bag, sum(ttab_bp.upto_dust_bag) AS upto_dust_bag");
$this->db->from('ttab_buyer_purchase ttab_bp');
$this->db->join('buyer_setup','ttab_bp.buyer_id = buyer_setup.id');
$this->db->where('ttab_bp.buyer_type','Export');
$this->db->group_by('ttab_bp.buyer_id');
$this->db->order_by('buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	$count2 = $sl2++;
?>

<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase; font-weight:bold;"><?php echo ($count2==1) ? $row3['buyer_type'] : ''; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:1px solid; font-weight:bold;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_no.'&sale_to='.$sale_no.'&buyer_id='.$row3['buyer_id'].'&report=9'); ?>"><?php echo $row3['buyer_name']; ?></a>
	</td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['current_dust_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_leaf_bag']; ?></td>
	<td style="text-align:right; border:1px solid; font-weight:bold;"><?php echo $row3['upto_dust_bag']; ?></td>
</tr>	

<?php
	$current_leaf_bag2 += $row3['current_leaf_bag'];
	$current_dust_bag2 += $row3['current_dust_bag'];
	$upto_leaf_bag2 += $row3['upto_leaf_bag'];
	$upto_dust_bag2 += $row3['upto_dust_bag'];

} // foreach


if(($upto_leaf_bag2 + $upto_dust_bag2) > 0){
?>
<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:1px solid; color:red;">EXPORT TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_dust_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_dust_bag2; ?></th>
</tr>
<?php } ?>

<tr>
	<th style="text-align:right; border:0px; color:red;"></th>
	<th style="text-align:right; border:1px solid; color:red;">GRAND TOTAL : </th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_leaf_bag + $current_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $current_dust_bag + $current_dust_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_leaf_bag + $upto_leaf_bag2; ?></th>
	<th style="text-align:right; border:1px solid; color:red;"><?php echo $upto_dust_bag + $upto_dust_bag2; ?></th>
</tr>
</tbody>
</table>

<i>@ Jamuna Tech</i>

<br />
<br />
<br />

<div class="row">
	<div class="col-sm-7 text-primary">
	</div>
	<div class="col-sm-5 text-primary text-center">
		<div class="text-left"> 
			<b style="text-align:center; font-size:14px;">&nbsp; &nbsp; &nbsp; Yours faithfully</b> <br /> For <b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b>
		</div>
	</div>
</div>
<!-- /row -->

<br />

</div>
</div>
<?php } ?>

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