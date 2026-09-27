<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$w_house_id = (!empty($this->input->get('w_house_id', TRUE))) ? $this->input->get('w_house_id', TRUE) : '';
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

<?php if($report_name=='Summary'){ ?>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="javascript:void(0);" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12">
		<p style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?></p>
		<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Warehouse Rent Report In Summary</p>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-4 text-left">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
	</div>
	<div class="form-group col-sm-4 text-center">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
	</div>
	<div class="form-group col-sm-4 text-right">
		<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
	</div>
</div>

<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:left;">Warehouse Name</th>
	<th style="text-align:right;">Sold Bags</th>
	<th style="text-align:right;">Charge P/Bag</th>
	<th style="text-align:right;">Total Rent</th>
	<th class="no-print" style="text-align:right;">At a glance</th>
</tr>
</thead>
<tbody>
<?php
$ac_clause = ($auction_center=='All Center') ? "NOT invoice_entry.auction_center='' " : "invoice_entry.auction_center='".$auction_center."' ";

## create temporary table - wh_sales_summary
$this->db->query("DROP TABLE IF EXISTS `wh_sales_summary`");

// create wh_sales_summary
$this->db->query("CREATE TABLE `wh_sales_summary` (
SELECT 

	invoice_entry.w_house_id, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold, 
	invoice_entry.warehouse_charge, 
	((IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) * IFNULL(invoice_entry.warehouse_charge,0)) AS total_rent 
	
FROM `sale_entry` 
JOIN invoice_entry ON (sale_entry.invoice_id = invoice_entry.id) 

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY sale_entry.invoice_id)");
// end


// ++++++++++++++++++++ REPORT VIEW ++++++++++++++++++++
$total_sold = 0;
$total_rent = 0;

//query
$this->db->select("ws.w_house_id, wh.warehouse_name, sum(ws.total_sold) AS total_sold, AVG(ws.warehouse_charge) AS warehouse_charge, sum(ws.total_rent) AS total_rent"); 
$this->db->from('wh_sales_summary ws');
$this->db->join('warehouse_setup wh','ws.w_house_id=wh.id','left');
$this->db->group_by('ws.w_house_id');
$this->db->order_by('wh.warehouse_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:left;"><b style="text-transform:uppercase;"><?php print $row1['warehouse_name']; ?></b></td>
	<td style="text-align:right;"><?php print $row1['total_sold']; ?></td>
	<td style="text-align:right;"><?php print number_format($row1['warehouse_charge'],2,'.',','); ?></td>
	<td style="text-align:right;"><?php print number_format($row1['total_rent'],2,'.',','); ?></td>
	<td class="no-print" style="text-align:right;">
		<a target="blink" class="text-success" href="<?php echo site_url('MY_Controller/warehouse_rent_statement?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&w_house_id='.$row1['w_house_id'].'&report_name=Details'); ?>"><b>Details</b></a>
	</td>
</tr>
<?php
	$total_sold +=$row1['total_sold'];
	$total_rent +=$row1['total_rent'];
} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="5" style="border:0px;"></td>
</tr>
<tr>
	<th colspan="1" style="text-align:right;">Net Total: </th>
	<th style="text-align:right;"><?php print $total_sold; ?></th>
	<th style="text-align:right;"></th>
	<th style="text-align:right;"><?php print number_format($total_rent,2,'.',','); ?></th>
	<th class="no-print" style="text-align:right;"></th>
</tr>	
</tfoot>
</table>
</div>
<!-- /table-responsive -->
<?php } ?>

</div>
<!-- /container -->

<script>
// print area
function printDiv(divName) 
{
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