<!DOCTYPE html>
<html lang="en">
<head>
<title>BUYER TEA BOARD STATEMENT</title>
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
}
</style>

<?php if($report_name=='TB'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="PrintPanel('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - buyer_tb_stmt
$this->db->query("DROP TABLE IF EXISTS `buyer_tb_stmt`");

// create buyer_tb_stmt @  buyer-1
$this->db->query("CREATE TABLE `buyer_tb_stmt` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer1) AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer1_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer1_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer1_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_tb_stmt @  buyer-2
$this->db->query("INSERT INTO `buyer_tb_stmt` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer2) AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer2_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer2_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer2_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_tb_stmt @  buyer-3
$this->db->query("INSERT INTO `buyer_tb_stmt` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	IFNULL(buyer_setup.main_buyer_id,sale_entry.buyer3) AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_bag END),0)
	) AS leaf_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_bag END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_bag END),0)
	) AS dust_bag,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Leaf' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Leaf') THEN sale_entry.buyer3_total_kgs END),0)
	) AS leaf_kgs,
	
	(
		IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Dust' THEN sale_entry.buyer3_total_kgs END),0) + IFNULL(SUM(CASE WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea IN ('Dust','Green Tea','Orthodox') THEN sale_entry.buyer3_total_kgs END),0)
	) AS dust_kgs,
	
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.sale_date <= '".$sale_data['sale_date']."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="8" style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	BUYER TEA BOARD STATEMENT</th>
</tr>
<tr>
	<th colspan="8" style="text-align:left; font-size:14px !important; border:0px;"> 
	The Secretary<br />
	Bangladesh Tea Board<br />
	Nasirabad, Chittagong.
	</th>
</tr>
<tr>
	<th colspan="8" style="text-align:left; border:0px;">
		Dear Sir, <br /> We give below the purchase made by the following buyers in our catalogue for sale No. <b><?php print $sale_no; ?> (<?php print $auction_center; ?>) </b> &nbsp; held on <span><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></span> &nbsp; Season: <?php print $season; ?> (<?php print substr($auction_center,0,4); ?> to <?php print substr($combine_center,0,4); ?>)
	</th>
</tr>
<tr>
    <th style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; border:1px solid;">LEAF BAGS</th>
	<th style="text-align:right; border:1px solid;">DUST BAGS</th>
	<th style="text-align:right; border:1px solid;">LEAF KGS</th>
	<th style="text-align:right; border:1px solid;">DUST KGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL BAGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL KGS</th>
	<th style="text-align:right; border:1px solid;">TOTAL AMOUNT</th>
</tr>
</thead>
<tbody>
<?php
// query
$leaf_bag = 0;
$dust_bag = 0;
$leaf_kgs = 0;
$dust_kgs = 0;
$net_bag = 0;
$net_kgs = 0;
$total_amount = 0;

// query
$this->db->select("buyer_tb_stmt.sale_date, buyer_setup.buyer_name, buyer_tb_stmt.buyer_type, sum(buyer_tb_stmt.leaf_bag) AS leaf_bag, sum(buyer_tb_stmt.dust_bag) AS dust_bag, sum(buyer_tb_stmt.leaf_kgs) AS leaf_kgs, sum(buyer_tb_stmt.dust_kgs) AS dust_kgs, sum(buyer_tb_stmt.buyer_amount) AS total_amount ");
$this->db->from('buyer_tb_stmt');
$this->db->join('buyer_setup','buyer_tb_stmt.buyer_id = buyer_setup.id');
$this->db->group_by('buyer_tb_stmt.buyer_id, buyer_tb_stmt.buyer_type');
$this->db->order_by('buyer_tb_stmt.buyer_type DESC, buyer_setup.buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>

<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['buyer_name']; ?> - <?php echo substr($row3['buyer_type'],0,1); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['leaf_bag']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $row3['dust_bag']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['leaf_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['dust_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo $total_bag = $row3['leaf_bag'] + $row3['dust_bag']; ?></td>
	<?php
		$total_kgs = $row3['leaf_kgs'] + $row3['dust_kgs'];
	?>	
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($total_kgs,2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
</tr>	

<?php
	$leaf_bag += $row3['leaf_bag'];
	$dust_bag += $row3['dust_bag'];
	$leaf_kgs += $row3['leaf_kgs'];
	$dust_kgs += $row3['dust_kgs'];
	$net_bag += $total_bag;
	$net_kgs += $total_kgs;
	$total_amount += $row3['total_amount'];
	
}  // 1st loop
?>

<tr>
	<th style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:right; color:red;"><?php echo $leaf_bag; ?></th>
	<th style="text-align:right; color:red;"><?php echo $dust_bag; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($leaf_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($dust_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo $net_bag; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
</tr>

<tr>
	<th colspan="8" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

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

<br />

<div class="row">
	<div class="col-sm-12">
		<div style="font-weight:bold;">C.C TO</div>
		<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">1. THE SECRETARY, TTAB, CTG</div>
		<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">2. THE DEPUTY DIRECTOR(TRADE), BTB, CTG</div>
		<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">3. The Assistant Director(TRADE), BTB, CTG </div>
		<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;">4. The Accounts Officer(GARDEN CELL), BTB, CTG </div>
		<div contenteditable="true" style="font-weight:bold; text-transform:uppercase;"></div>
	</div>
</div>
<center><i>System by @ Jamuna Tech</i></center>
<?php } // tb single sale no ?>

</div>
<!-- /container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
// print div
function PrintPanel(printableArea) {
	
	// Works with Chome, Firefox, IE, Safari
	// Get the HTML of div
	var title = document.title;
	var divElements = document.getElementById(printableArea).innerHTML;
	var printWindow = window.open("", "_blank", "");
	// open the window
	printWindow.document.open();
	// write the html to the new window, link to css file
	printWindow.document.write('<html><head><title>' + title + '</title><link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/3.4.1/css/bootstrap.min.css"><link rel="icon" href="<?php echo base_url(); ?>assets/img/favicons/favicon.ico"></head><body>');
	printWindow.document.write(divElements);
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	printWindow.focus();
	// The Timeout is ONLY to make Safari work, but it still works with FF, IE & Chrome.
	setTimeout(function() {
		printWindow.print();
		printWindow.close();
	}, 100);
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

</body>
</html>