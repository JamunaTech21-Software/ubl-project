<!DOCTYPE html>
<html lang="en">
<head>
 <title>Buyer Statement</title>
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
<body>
<?php
$season = (!empty($this->input->post('season_name', TRUE))) ? $this->input->post('season_name', TRUE) : '';
$auction_center = (!empty($this->input->post('auction_center', TRUE))) ? $this->input->post('auction_center', TRUE) : '';
$garden_id = (!empty($this->input->post('garden_id', TRUE))) ? $this->input->post('garden_id', TRUE) : '';
$sale_no = (!empty($this->input->post('sale_no', TRUE))) ? $this->input->post('sale_no', TRUE) : '';
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

<!-- Garden wise Buyer Statement single sale-->
<style>
#table tr td {
	border-color:black;
	font-size:13px !important;
	padding:3px;
	font-family:courier;
}

#table tr th{
	border-color:black;
	font-size:13px !important;
	padding:3px;
	font-family:courier;
}

@page{
	size: legal landscape;
	margin-left: 100px;
	margin-right: 100px;
}
</style>
<?php 
// get html value
$row_limit_pp = 25;
## create temporary table - buyer_purchase_analysis
$this->db->query("DROP TABLE IF EXISTS `buyer_purchase_analysis`");

// create buyer_purchase_analysis @  buyer-1
$this->db->query("CREATE TABLE `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer1)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create buyer_purchase_analysis @  buyer-2
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer2)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

//create buyer_purchase_analysis @  buyer-3
$this->db->query("INSERT INTO `buyer_purchase_analysis` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	buyer_setup.buyer_name,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	CONCAT(invoice_entry.sale_no,'.',sale_entry.buyer3)	AS bill_no

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM `sale_schedule_setup` WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$result_prompt_date = $query->row_array();
?>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	&nbsp; <a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
</div>
</div>

<?php
	$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
	$fetch_gn = $query->row_array();
?>

<?php
// query
$sl=1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_vat_tk = 0;
$total_vat = 0;
$total_ait = 0;
$total_tea_cess = 0;

// INTERNAL BUYER	
$this->db->select("bill_no, sale_date, buyer_name, buyer_type, sum(buyer_bag) AS total_bags, sum(buyer_kgs) AS total_kgs, sum(buyer_amount) AS total_amount, IF(buyer_type='Internal', sum(vat_tk), '0.00') AS total_vat_tk, (CASE WHEN buyer_type='Internal' THEN 'A'
WHEN buyer_type='Export' THEN 'B' END) AS buyer_type_ac"); 	
$this->db->from('buyer_purchase_analysis');
$this->db->group_by('buyer_id,buyer_type');
$this->db->order_by('buyer_type_ac ASC');
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
	$count = $sl++;

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
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="9" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:16px; font-family:courier;">Unity Brokers Limited</b>
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px; font-family:courier;">
                Gardenwise buyer statement Season <?php print $season; ?> Sale No. <?php print $sale_no; ?> <?php print $auction_center; ?> held on <?php print date('d-M-Y' , strtotime($result_prompt_date['sale_date'])); ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
	<tr>
        <th colspan="9" style="text-transform:uppercase; text-align:left; border:0px;">
			Garden Name: <?php print $fetch_gn['garden_name']; ?>
        </th>
    </tr>
    <tr>
		<th style="text-align:center; white-space:nowrap; border:1px solid;">SL</th>
		<th style="text-align:left; white-space:nowrap; border:1px solid;">BUYER Information</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">PKGS</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Wt.(kg.)</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">AMOUNT (Tk.)</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Avg.</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">VAT</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">AIT</th>
		<th style="text-align:right; white-space:nowrap; border:1px solid;">Tea Cess</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px; font-weight:bold;"><?php print $count ; ?></td>
	<td style="text-align:left; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['buyer_name']; ?> <span style="color: <?php echo ($row3['buyer_type']=='Export') ? 'red' : ''; ?>"><?php echo ($row3['buyer_type']=='Export') ? '- '.$row3['buyer_type'] : ''; ?></span></td>
	<td style="text-align:right; text-transform:uppercase; border:0px; font-weight:bold;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$vat = $row3['total_amount'] / $row3['total_kgs'];
			print number_format($vat,2,'.',',') ; 
		?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;"><?php echo number_format($row3['total_vat_tk'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$ait = $row3['total_amount'] / 100;
			print number_format($ait,2,'.',',') ; 
		?>
	</td>
	<td style="text-align:right; border:0px; font-weight:bold;">
		<?php 
			$tea_cess = $row3['total_amount'] / 100;
			print number_format($ait,2,'.',',') ; 
		?>
	</td>
</tr>
<?php

$total_bags += $row3['total_bags'];
$total_kgs += $row3['total_kgs'];
$total_amount += $row3['total_amount'];
$total_vat_tk += $row3['total_vat_tk'];
$total_vat += $vat;
$total_ait += $ait;
$total_tea_cess += $tea_cess;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan='2' style="text-align:right; border:0px; color:red;">Sub Total : </th>
	<th style="text-align:right; border:0px; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_vat,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_ait,2,'.',','); ?></th>
	<th style="text-align:right; border:0px; color:red;"><?php echo number_format($total_tea_cess,2,'.',','); ?></th>
</tr>

</tbody>
</table>
</div> <!-- /col -->
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

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found.</b></p></div>";
}
?>

</div> 
<!-- container -->

<script>
function printDiv(divName) {
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

</body>
</html>