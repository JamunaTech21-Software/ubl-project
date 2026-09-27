<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
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
	font-size:14px !important;
	padding:5px;
	vertical-align:middle;
}
#table tr th{
	border-color:black;
	font-size:14px !important;
	padding:3px;
	vertical-align:middle;
}
@media print{    
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
	.col-sm-2 { width: 16.66666667%; }
	.col-sm-1 { width: 8.33333333%; }
}
</style>

<div class="row">
<div class="form-group col-sm-11">
	<p style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?></p>
	<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Garden Sales Top Price Report</p>
</div>
<div class="form-group col-sm-1 text-left">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
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

<!-- ALL GARDEN SUMMARY -->
<?php if(empty($garden_id)){ ?>
<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:left;">SL</th>
	<th style="text-align:left;">Garden Name</th>
	<th style="text-align:right;">Avg.</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - garden_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `garden_sales_avg_report`");

// create garden_sales_avg_report
$this->db->query("CREATE TABLE `garden_sales_avg_report` (
SELECT invoice_entry.garden_id, invoice_entry.chest_number AS total_sold, invoice_entry.net_kgs AS net_kgs, (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount FROM `sale_entry` JOIN invoice_entry ON (sale_entry.invoice_id = invoice_entry.id) WHERE invoice_entry.season='".$season."' and invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY sale_entry.invoice_id) ");

$sl=1;
//query
$this->db->select("gs.garden_id, (sum(gs.total_amount)/sum(gs.net_kgs)) AS avg_price, gc.garden_name"); 
$this->db->from('garden_sales_avg_report gs');
$this->db->join('garden_and_company_setup gc','gs.garden_id=gc.id','left');
$this->db->group_by('gs.garden_id');
$this->db->order_by('avg_price DESC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:center;">
		<?php print $sl++; ?>
	</td>
	<td style="text-align:left; text-transform:uppercase;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row1['garden_id']); ?>"><b><?php print $row1['garden_name']; ?></b></a>
	</td>
	<td style="text-align:right;">
		<?php print number_format($row1['avg_price'],2,'.',','); ?>
	</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
<!-- /row -->
<?php } ?>

</div> 
<!-- container -->

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
</script>