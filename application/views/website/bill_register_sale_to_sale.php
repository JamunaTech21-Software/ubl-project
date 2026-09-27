<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
?>

<?php if($report_name=='Register_sale_to_sale'){ ?>
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

#table tr td,
#table2 tr td{
	border-color:black;
	font-size:13px !important;
	padding:3px;
}
#table tr th,
#table2 tr th{
	border-color:black;
	font-size:13px !important;
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

@page {
  size: legal landscape;
  margin-left: 100px;
  margin-right: 100px;
}
</style>
<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>
<?php
// page row count
$row_limit_pp = 24;

// auction center clause
$ac_clause = ($auction_center=='All Center') ? "NOT invoice_entry.auction_center='' " : "invoice_entry.auction_center='".$auction_center."' ";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer1_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer2_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer3_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS total_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<?php
$sl = 1;
// reset value
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_brokerage = 0;
$total_vat_tk = 0;
$total_ait_tk = 0;
$total_total_tk = 0;

// query - 2
$this->db->select("buyer_id, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, sum(brokerage_tk) AS total_brokerage, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk, sum(ait_tk) AS total_ait_tk, IF(buyer_type='Internal', sum(total_tk), (IFNULL(sum(total_tk),0) - IFNULL(sum(vat_tk),0))) AS total_total_tk, (CASE WHEN buyer_type='Export' THEN 'B' WHEN buyer_type='Internal' THEN 'A' END) AS buyer_type_ac"); 	
$this->db->from('bill_register');
$this->db->group_by('buyer_id, buyer_type');
$this->db->order_by('buyer_name ASC, buyer_type_ac ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>
<div class="col-sm-12">
<table class="table table-sm" id="table" style="margin-bottom:10px;">
	<thead>
		<tr style="border-bottom:1px solid #000;">
			<td style="text-align:left; border:0; width:220px; vertical-align: bottom;">
				<span style="font-size:13px;">Season : <?php print $season; ?> </span>
			</td>
			<td style="text-transform: uppercase; text-align:center; border:0;">
				<b style="font-size:15px;"><?php print $website_title; ?></b><br>
				<span style="font-size:13px;">Buyer Wise Bill Statement - Sale No. <?php print $sale_from; ?> To <?php print $sale_to; ?></span>
			</td>
			<td style="text-align:right; border:0; width:220px; vertical-align: bottom;">
				<span style="font-size:13px;">Page <?php print $page ; ?></span>
			</td>
		</tr>
	</thead>
</table>

<table class="table table-sm" id="table">
<thead>
<tr>
    <th colspan="2" style="text-align:center; border:1px solid;"> Buyers</th>
	<th style="text-align:center; border:1px solid;">Total <br> PKgs</th>
	<th style="text-align:center; border:1px solid;">Total <br> KG</th>
	<th style="text-align:center; border:1px solid;">Amount <br> Tk.</th>
	<th style="text-align:center; border:1px solid;">Vat <br> 15 %</th>
	<th style="text-align:center; border:1px solid;">AIT <br> 1 %</th>
	<th style="text-align:center; border:1px solid;">Brokerage</th>
	<th style="text-align:center; border:1px solid;">Total <br> Amount</th>
</tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; font-weight:bold; border:0px;"><?php echo $sl++; ?></td>
	<td style="text-align:left; font-weight:bold; border:0px; text-transform:uppercase;">
		<a target="blink" href="<?php echo site_url('MY_Controller/buyer_ait_report?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&buyer_id='.$row3['buyer_id'].'&report_no=3'); ?>"><?php echo $row3['buyer_name']; ?> <?php echo ($row3['buyer_type']=='Export') ? '<b style="color:red;"> - '.$row3['buyer_type'].'</b>' : ''; ?></a>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px; text-transform:uppercase;">
		<?php echo $row3['total_bags']; ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_amount'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_vat_tk'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_ait_tk'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_brokerage'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:0px;">
		<?php echo number_format($row3['total_total_tk'],2,'.',','); ?>
	</td>
</tr>	
<?php
	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$total_brokerage += $row3['total_brokerage'];
	$total_vat_tk += $row3['total_vat_tk'];
	$total_ait_tk += $row3['total_ait_tk'];
	$total_total_tk += $row3['total_total_tk'];

	// limit of close table / div
	if($i==$lower_limit || $i==$total_records){ 
	$upper_limit = $lower_limit + $row_start;
?>
</tbody>
</table>
</div>
<?php } ?>
<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 
?>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm" id="table2">
			<tr>
				<th  style="text-align:right; border:1px solid; text-transform:uppercase; width:435px;">Sub Total : </th>
				<th style="text-align:right; border:1px solid;"><?php echo $total_bags; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_amount,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_ait_tk,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_brokerage,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_total_tk,2,'.',','); ?></th>
			</tr>
			<!-- This row is only used for two row spacing -->
			<tr><td height="10" colspan="6"></td></tr>
			<!-- This row is only used for two row spacing -->
			<tr>
				<th  style="text-align:right; border:1px solid; text-transform:uppercase; width:435px;">Sub Total : </th>
				<th style="text-align:right; border:1px solid;"><?php echo $total_bags; ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_amount,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_ait_tk,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_brokerage,2,'.',','); ?></th>
				<th style="text-align:right; border:1px solid;"><?php echo number_format($total_total_tk,2,'.',','); ?></th>
			</tr>
		</table>
	</div>
</div>
<?php
// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?> 
<!-- /row -->

</div>
<!-- /container -->
<?php } // IF Register_sale_to_sale ?>

<script>
// print area
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