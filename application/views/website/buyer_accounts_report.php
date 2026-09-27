<!DOCTYPE html>
<html lang="en">
<head>
<title>BUYER ACCOUNTS REPORT</title>
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
$date_from = (!empty($this->input->get('date_from', TRUE))) ? $this->input->get('date_from', TRUE) : '';
$date_to = (!empty($this->input->get('date_to', TRUE))) ? $this->input->get('date_to', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
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
}
</style>

<?php if($report_name=='buyer_balance'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - buyer_balance_list
$this->db->query("DROP TABLE IF EXISTS `buyer_balance_list`");

// create buyer_balance_list @  buyer-1
$this->db->query("CREATE TABLE `buyer_balance_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	(
		IFNULL(sale_entry.buyer1_amount,0) + 
		((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) + 
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) +
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) 
	) AS buyer_amount,
	'00000000000' AS receive_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.sale_date <= '".$date_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_balance_list @  buyer-2
$this->db->query("INSERT INTO `buyer_balance_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	(
		IFNULL(sale_entry.buyer2_amount,0) + 
		((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) + 
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) +
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) 
	) AS buyer_amount,
	'00000000000' AS receive_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.sale_date <= '".$date_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_balance_list @  buyer-3
$this->db->query("INSERT INTO `buyer_balance_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	(
		IFNULL(sale_entry.buyer3_amount,0) + 
		((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) + 
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) +
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) 
	) AS buyer_amount,
	'00000000000' AS receive_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.sale_date <= '".$date_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// buyer receive data
$this->db->query("INSERT INTO `buyer_balance_list` (
SELECT 

	'' AS sale_no,
	date_format(buyer_receive_tbl.datetime,'%Y-%m-%d') AS sale_date,
	'' AS lot_no,
	'' AS grade_id,
	buyer_receive_tbl.buyer_id AS buyer_id,	
	buyer_receive_tbl.buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	'' AS buyer_amount,
	sum(buyer_receive_tbl.voucher_receive) AS receive_tk	

FROM buyer_receive_tbl   
JOIN buyer_setup ON (buyer_receive_tbl.buyer_id = buyer_setup.id)

WHERE date_format(buyer_receive_tbl.datetime,'%Y-%m-%d') <= '".$date_to."' GROUP BY buyer_receive_tbl.id) ");
?>
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="4" style="text-transform: uppercase; text-align:center; border:0px;">
		<span style="font-size:16px;"><?php print $website_title; ?></span><br />
		<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span><br />
		<span style="font-size:16px;">BUYER BALANCE LIST</span>
	</th>
</tr>
<tr>
	<th colspan="4" style="text-transform:uppercase; text-align:center; border:0px; font-size:14px !important;">Upto: <?php print date("d-m-Y",strtotime($date_to)); ?></th>
</tr>
<tr>
    <th style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; border:1px solid;">ADDRESS</th>
	<th style="text-align:right; border:1px solid;">CONTACT</th>
	<th style="text-align:right; border:1px solid;">BALANCE</th>
</tr>
</thead>
<tbody>
<?php
// set value
$total_amount = 0;

// query
$this->db->select("buyer_id, buyer_name, short_key_code, buyer_contact_no, (IFNULL(sum(buyer_amount),0) - IFNULL(sum(receive_tk),0)) AS total_amount "); 	
$this->db->from('buyer_balance_list');
$this->db->group_by('buyer_id');
$this->db->order_by('buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;">
		<a target="_blink" href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=buyer_ledger_details.php?buyer_id=<?php print $row3['buyer_id']; ?>" title="View Buyer Ledger"><?php echo $row3['buyer_name']; ?></a>
	</td>
	<td style="text-align:left; border:0px; text-transform: uppercase;"><?php echo $row3['short_key_code']; ?></td>
	<td style="text-align:left; border:0px;"><?php echo $row3['buyer_contact_no']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
</tr>	
<?php
	$total_amount += $row3['total_amount'];
}  // 1st loop
?>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
</tr>
<tr>
	<th colspan="4" style="padding:10px !important; border:0px !important;"></th>
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
<?php } // Buyer Balance amount ?>


<?php if($report_name=='buyer_defaulter_list'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - buyer_balance_list
$this->db->query("DROP TABLE IF EXISTS `buyer_balance_list`");

// create buyer_balance_list @  buyer-1
$this->db->query("CREATE TABLE `buyer_balance_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	(
		IFNULL(sale_entry.buyer1_amount,0) + 
		((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) + 
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) +
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) 
	) AS buyer_amount,
	'00000000000' AS receive_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_balance_list @  buyer-2
$this->db->query("INSERT INTO `buyer_balance_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	(
		IFNULL(sale_entry.buyer2_amount,0) + 
		((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) + 
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) +
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) 
	) AS buyer_amount,
	'00000000000' AS receive_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_balance_list @  buyer-3
$this->db->query("INSERT INTO `buyer_balance_list` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.grade_id,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	(
		IFNULL(sale_entry.buyer3_amount,0) + 
		((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) + 
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) +
		((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) 
	) AS buyer_amount,
	'00000000000' AS receive_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// buyer receive data
$this->db->query("INSERT INTO `buyer_balance_list` (
SELECT 

	'' AS sale_no,
	date_format(buyer_receive_tbl.datetime,'%Y-%m-%d') AS sale_date,
	'' AS lot_no,
	'' AS grade_id,
	buyer_receive_tbl.buyer_id AS buyer_id,	
	buyer_receive_tbl.buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.short_key_code,
	buyer_setup.buyer_contact_no,
	'' AS buyer_amount,
	sum(buyer_receive_tbl.voucher_receive) AS receive_tk	

FROM buyer_receive_tbl   
JOIN buyer_setup ON (buyer_receive_tbl.buyer_id = buyer_setup.id)

WHERE buyer_receive_tbl.season='".$season."' AND buyer_receive_tbl.auction_center='".$auction_center."' AND buyer_receive_tbl.sale_no='".$sale_no."' GROUP BY buyer_receive_tbl.id) ");
?>
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="4" style="text-transform: uppercase; text-align:center; border:0px;">
		<span style="font-size:16px;"><?php print $website_title; ?></span><br />
		<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span><br />
		<span style="font-size:16px;">BUYER DEFAULTER LIST</span>
	</th>
</tr>
<tr>
	<th colspan="4" style="text-transform:uppercase; text-align:center; border:0px; font-size:14px !important;">Season: <?php print $season; ?> / Auction Center: <?php print $auction_center; ?> / Sale No: <?php print $sale_no; ?></th>
</tr>
<tr>
    <th style="text-align:left; border:1px solid;">BUYER NAME</th>
	<th style="text-align:right; border:1px solid;">ADDRESS</th>
	<th style="text-align:right; border:1px solid;">CONTACT</th>
	<th style="text-align:right; border:1px solid;">BALANCE</th>
</tr>
</thead>
<tbody>
<?php
// set value
$total_amount = 0;

// query
$this->db->select("buyer_id, buyer_name, short_key_code, buyer_contact_no, (IFNULL(sum(buyer_amount),0) - IFNULL(sum(receive_tk),0)) AS total_amount "); 	
$this->db->from('buyer_balance_list');
$this->db->group_by('buyer_id');
$this->db->having('round(total_amount,2) > 0');
$this->db->order_by('buyer_name ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;">
		<a target="_blink" href="../../../pbl_accounts/report_login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=buyer_ledger_details.php?buyer_id=<?php print $row3['buyer_id']; ?>" title="View Buyer Ledger"><?php echo $row3['buyer_name']; ?></a>
	</td>
	<td style="text-align:left; border:0px; text-transform: uppercase;"><?php echo $row3['short_key_code']; ?></td>
	<td style="text-align:left; border:0px;"><?php echo $row3['buyer_contact_no']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
</tr>	
<?php
	$total_amount += $row3['total_amount'];
}  // 1st loop
?>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
</tr>
<tr>
	<th colspan="4" style="padding:10px !important; border:0px !important;"></th>
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
<?php } // Buyer Balance amount ?>

</div>
<!-- /container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
function printDiv(divName){
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
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