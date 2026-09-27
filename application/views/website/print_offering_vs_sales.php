<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
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
	padding:5px;
	vertical-align:middle;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
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
	<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">Offering vs Sales Report</p>
</div>
<div class="form-group col-sm-1 text-left">
	<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="form-group col-sm-4 text-left">
	<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale No: <?php print $sale_no; ?></b>
</div>
<div class="form-group col-sm-4 text-center">
	<b style="text-transform: uppercase; font-size:16px; font-family:courier;"><?php print $auction_center; ?></b>
</div>
<div class="form-group col-sm-4 text-right">
	<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Season: <?php print $season; ?></b>
</div>
</div>

<div class="row">
<div class="col-sm-12">  
<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>				
	<th>Catalog Name</th>
	<th style="text-align:right;">Offering Bags</th>
	<th style="text-align:right;">Offering Kgs</th>
	<th style="text-align:right; color:green;">Sold Bags</th>
	<th style="text-align:right; color:green;">Sold Kgs</th>
	<th style="text-align:right; color:red;">W/D Bags</th>
	<th style="text-align:right; color:red;">W/D Kgs</th>
	<th style="text-align:right; color:#006994;">Out Bags</th>
	<th style="text-align:right; color:#006994;">Out Kgs</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - catalog_sales_report
$this->db->query("DROP TABLE IF EXISTS `catalog_sales_report`");

// create catalog_sales_report
$this->db->query("CREATE TABLE `catalog_sales_report` (
SELECT invoice_entry.catalouge_name, invoice_entry.supplement_tea, invoice_entry.chest_number AS total_sold, invoice_entry.net_kgs AS net_kgs, (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount FROM `sale_entry` JOIN invoice_entry ON (sale_entry.invoice_id = invoice_entry.id) WHERE invoice_entry.season='".$season."' and invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY sale_entry.invoice_id) ");

$sl = 1;
$chest_number = 0;
$net_kgs = 0;
$sold_bag = 0;
$sold_kgs = 0;
$wd_bags = 0;
$wd_kgs = 0;
$out_bags = 0;
$out_kgs = 0;

// query
$this->db->select("
	(CASE 
		WHEN invoice_entry.catalouge_name='Leaf' AND invoice_entry.supplement_tea='Leaf' THEN invoice_entry.catalouge_name 
		WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea='Leaf' THEN CONCAT(invoice_entry.supplement_tea,' - ',invoice_entry.catalouge_name) 
		WHEN invoice_entry.catalouge_name='Dust' AND invoice_entry.supplement_tea='Dust' THEN invoice_entry.catalouge_name 
		WHEN invoice_entry.catalouge_name='Supplement' AND invoice_entry.supplement_tea='Dust' THEN CONCAT(invoice_entry.supplement_tea,' - ',invoice_entry.catalouge_name)
		END	
	) AS c_catalouge_name, 
	COUNT(lot_no) AS total_lot_no,
	SUM(invoice_entry.chest_number) AS chest_number,
	SUM(invoice_entry.net_kgs) AS net_kgs,
	IFNULL(SUM(CASE WHEN invoice_entry.remarks='W/D' THEN invoice_entry.chest_number END),0) AS wd_bags,
	IFNULL(SUM(CASE WHEN invoice_entry.remarks='W/D' THEN invoice_entry.net_kgs END),0) AS wd_kgs,
	IFNULL(catalog_sales.sold_bag,0) AS sold_bag,
	IFNULL(catalog_sales.sold_kgs,0) AS sold_kgs,
	(SUM(invoice_entry.chest_number) - (IFNULL(catalog_sales.sold_bag,0) + IFNULL(SUM(CASE WHEN invoice_entry.remarks='W/D' THEN invoice_entry.chest_number END),0))) AS out_bags,
	(SUM(invoice_entry.net_kgs) - (IFNULL(catalog_sales.sold_kgs,0) + IFNULL(SUM(CASE WHEN invoice_entry.remarks='W/D' THEN invoice_entry.net_kgs END),0))) AS out_kgs
"); 
$this->db->from('invoice_entry');
// sold query
$this->db->join("(SELECT catalouge_name, supplement_tea, sum(total_sold) AS sold_bag, sum(net_kgs) AS sold_kgs FROM catalog_sales_report GROUP BY catalouge_name, supplement_tea) catalog_sales","invoice_entry.catalouge_name=catalog_sales.catalouge_name AND invoice_entry.supplement_tea=catalog_sales.supplement_tea","left");
// end
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no',$sale_no);
$this->db->where('invoice_entry.soft_delete',1);
$this->db->group_by('invoice_entry.catalouge_name, invoice_entry.supplement_tea');
$this->db->order_by('total_lot_no DESC, c_catalouge_name DESC');
$query = $this->db->get();
if($query->num_rows() > 0){
$result1 = $query->result_array();
foreach($result1 as $row1){
?>

<tr>
    <td><?php echo $row1['c_catalouge_name']; ?></td>
	<td style="text-align:right;"><?php echo $row1['chest_number']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row1['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; color:green;"><?php echo $row1['sold_bag']; ?></td>
	<td style="text-align:right; color:green;"><?php echo number_format($row1['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; color:red;"><?php echo $row1['wd_bags']; ?></td>
	<td style="text-align:right; color:red;"><?php echo number_format($row1['wd_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; color:#006994;"><?php echo $row1['out_bags']; ?></td>
	<td style="text-align:right; color:#006994;"><?php echo number_format($row1['out_kgs'],2,'.',''); ?></td>
</tr>

<?php
$chest_number += $row1['chest_number'];
$net_kgs += $row1['net_kgs'];
$sold_bag += $row1['sold_bag'];
$sold_kgs += $row1['sold_kgs'];
$wd_bags += $row1['wd_bags'];
$wd_kgs += $row1['wd_kgs'];
$out_bags += $row1['out_bags'];
$out_kgs += $row1['out_kgs'];

} // foreach

}else{
	print "<tr><td colspan=\"10\">No matching record found.</td></tr>";
}
?>
</tbody>
<tfoot>
<tr>				
	<th style="text-align:right; text-transform:uppercase;">Total : </th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:green;"><?php echo $sold_bag; ?></th>
	<th style="text-align:right; color:green;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo $wd_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($wd_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:#006994;"><?php echo $out_bags; ?></th>
	<th style="text-align:right; color:#006994;"><?php echo number_format($out_kgs,2,'.',''); ?></th>
</tr>
</tfoot>
</table>
</div>
</div> <!-- end col -->
</div> <!-- end row -->
</div> 
<!-- /container -->