<!DOCTYPE html>
<html lang="en">
<head>
 <title>Price Realised List</title>
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
$sale_no = (!empty($this->input->post('sale_no', TRUE))) ? $this->input->post('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->post('garden_id', TRUE))) ? $this->input->post('garden_id', TRUE) : '';

## garden details
$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
$garden_data = $query->row_array();

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

<div class="row">
	<div class="form-group col-sm-11 text-center">
		<b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;">Unity Brokers Limited </b> 
		<br /> 
		<span style="text-transform: uppercase; font-size:12px; text-align:center;"><u>1837 SK Mujib Road, CTG</u></span>
		<br />
		<b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
			Price Realised List - Single Garden
		</b>
	</div>
	<div class="form-group col-sm-1 text-right no-print">
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="col-sm-4">
		<table style="text-transform:uppercase;">
			<tr>
				<th class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE NO </th>
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<th class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $sale_no; ?></th>
			</tr>
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SALE DATE </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
			</tr>
		</table>
	</div>	
	<div class="col-sm-4">
		<table align="center" style="text-transform:uppercase;">
			<tr>
				<td style="font-family: verdana; border:0px; font-size:12px;"><?php print $auction_center; ?></td>
			</tr>
		</table>
	</div>	
	<div class="col-sm-4">
		<table style="float:right; text-transform:uppercase;">
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> SEASON </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td> 
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print $season; ?></td>
			</tr>
			<tr>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;"> PROMPT DATE </td> 
				<td class="text-center" style="font-family: verdana; border:0px; font-size:12px;"> : </td>
				<td class="text-left" style="font-family: verdana; border:0px; font-size:12px;">&nbsp; <?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<b style="font-family: verdana; border:0px; font-size:12px;"> GARDEN NAME : &nbsp; <?php print $garden_data['garden_name']; ?></b>
	</div>
</div>	

<div class="row">
<div class="col-sm-12">
	
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:center; vertical-align: middle; border:1px solid;">Lot</th>
	<th style="text-align:center; vertical-align: middle; border:1px solid;">Inv</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">Grade</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Bags</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Value</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Price</th>
	<th style="text-align:right; vertical-align: middle; border:1px solid;">Total Kgs</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-1</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-2</th>
	<th style="text-align:left; vertical-align: middle; border:1px solid;">&nbsp; Buyer-3</th>
</tr>
</thead>
<tbody>
<?php
$sl = 1;
$chest_number = 0;
$net_kgs = 0;
$internal_bags = 0;
$internal_kgs = 0;
$internal_tk = 0;
$export_bags = 0;
$export_kgs = 0;
$export_tk = 0;

// query
$this->db->select("

	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.chest_number, 
	invoice_entry.valuation, 
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
	IFNULL(sale_entry.sold_price,'UNSOLD') AS sold_price,
	
	IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
	
	IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
	
	IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
	
	/* ++++ Internal +++ */
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_bag,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_bag,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_bag,0), '0')) AS internal_bags,
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_total_kgs,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_total_kgs,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_total_kgs,0), '0')) AS internal_kgs,
	
	(IF(sale_entry.buyer_type1='Internal', IFNULL(sale_entry.buyer1_amount,0), '0') + IF(sale_entry.buyer_type2='Internal', IFNULL(sale_entry.buyer2_amount,0), '0') + IF(sale_entry.buyer_type3='Internal', IFNULL(sale_entry.buyer3_amount,0), '0')) AS internal_tk,
	
	/* ++++ Export +++ */
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_bag,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_bag,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_bag,0), '0')) AS export_bags,
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_total_kgs,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_total_kgs,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_total_kgs,0), '0')) AS export_kgs,
	
	(IF(sale_entry.buyer_type1='Export', IFNULL(sale_entry.buyer1_amount,0), '0') + IF(sale_entry.buyer_type2='Export', IFNULL(sale_entry.buyer2_amount,0), '0') + IF(sale_entry.buyer_type3='Export', IFNULL(sale_entry.buyer3_amount,0), '0')) AS export_tk
"); 

$this->db->from('invoice_entry'); 
$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id", 'left');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr>
	<td style="text-align:center; vertical-align:middle; border:none;">
		<?php print $row2['lot_no']; ?>
	</td>
	<td style="text-align:center; vertical-align:middle; border:none; text-transform:uppercase;">
		<?php print $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:left; vertical-align:middle; border:none; text-transform:uppercase;">
		<?php print $row2['grade_name']; ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print $row2['chest_number']; ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print number_format($row2['valuation'],2,'.',''); ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print ($row2['sold_price']=='UNSOLD') ? "UNSOLD" : number_format($row2['sold_price'],2,'.',''); ?>
	</td>
	<td style="text-align:right; vertical-align:middle; border:none;">
		<?php print number_format($row2['net_kgs'],2,'.',','); ?>
	</td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer1']; ?></td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer2']; ?></td>
	<td style="text-align:left; color:#FF1493; border:none; text-transform:uppercase;">&nbsp; <?php echo $row2['buyer3']; ?></td>
</tr>	

<?php
	$chest_number += $row2['chest_number'];
	$net_kgs += $row2['net_kgs'];
	$internal_bags += $row2['internal_bags'];
	$internal_kgs += $row2['internal_kgs'];
	$internal_tk += $row2['internal_tk'];
	$export_bags += $row2['export_bags'];
	$export_kgs += $row2['export_kgs'];
	$export_tk += $row2['export_tk'];
} // second loop	
?>

<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">TOTAL: </th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
</tr>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">INTERNAL:</th>
	<th style="text-align:right;"><?php echo $internal_bags; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($internal_kgs,2,'.',''); ?></th>
	<th style="text-align:center;" colspan="3">Avg. Price: <?php echo ($internal_tk > 0 && $internal_kgs > 0) ? number_format($internal_tk / $internal_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;">EXPORTER:</th>
	<th style="text-align:right; border:none;"><?php echo $export_bags; ?></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"><?php echo number_format($export_kgs,2,'.',''); ?></th>
	<th style="text-align:center; border:none;" colspan="3">Avg. Price: <?php echo ($export_tk > 0 && $export_kgs > 0) ? number_format($export_tk / $export_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;">NET TOTAL SOLD:</th>
	<th style="text-align:right;"><?php echo $internal_bags + $export_bags; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php echo number_format($internal_kgs + $export_kgs,2,'.',''); ?></th>
	<th style="text-align:center;" colspan="3">Avg. Price: <?php echo (($internal_tk + $export_tk) > 0 && ($internal_kgs + $export_kgs) > 0) ? number_format(($internal_tk + $export_tk) / ($internal_kgs + $export_kgs),2,'.','') : '0.00'; ?></th>
</tr>
<tr>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none; color:red;">UNSOLD:</th>
	<th style="text-align:right; border:none; color:red;"><?php echo $chest_number - ($internal_bags + $export_bags); ?></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none;"></th>
	<th style="text-align:right; border:none; color:red;"><?php echo number_format($net_kgs - ($internal_kgs + $export_kgs),2,'.',''); ?></th>
	<th style="text-align:center; border:none;" colspan="3"></th>
</tr>
</tbody>
</table>

</div>
</div>


</div> 
<!-- /container -->

<script>
// print area
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
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