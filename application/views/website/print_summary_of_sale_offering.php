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
	<p style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
		Sale Offering Report <br /> <?php print $auction_center; ?>
	</p>
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
	<b style="text-transform: uppercase; font-size:16px; font-family:courier;">Sale Date: <span id="sale_date"></span></b>
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
	<th>Catalogue Name</th>
	<th style="text-align:right;">Total Lot</th>
	<th style="text-align:right;">Total Bags</th>
	<th style="text-align:right;">Total Kgs</th>
</tr>
</thead>
<tbody>
<?php
$sl = 1;
$lot_no = 0;
$chest_number = 0;
$net_kgs = 0;
$sale_date = '';

// query
$this->db->select("
	(CASE 
		WHEN catalouge_name='Leaf' AND supplement_tea='Leaf' THEN catalouge_name 
		WHEN catalouge_name='Supplement' AND supplement_tea='Leaf' THEN CONCAT(supplement_tea,' - ',catalouge_name) 
		WHEN catalouge_name='Dust' AND supplement_tea='Dust' THEN catalouge_name 
		WHEN catalouge_name='Supplement' AND supplement_tea='Dust' THEN CONCAT(supplement_tea,' - ',catalouge_name)
		END	
	) AS catalouge_name, 
	
	(CASE 
		WHEN catalouge_name='Leaf' AND supplement_tea='Leaf' THEN 'A' 
		WHEN catalouge_name='Supplement' AND supplement_tea='Leaf' THEN 'B' 
		WHEN catalouge_name='Dust' AND supplement_tea='Dust' THEN 'C' 
		WHEN catalouge_name='Supplement' AND supplement_tea='Dust' THEN 'D'
		END	
	) AS catalouge_order,
	
	COUNT(lot_no) AS lot_no,
	SUM(chest_number) AS chest_number,
	SUM(net_kgs) AS net_kgs,
	invoice_entry.sale_date
"); 
$this->db->from('invoice_entry');
$this->db->where('season',$season);
$this->db->where('auction_center',$auction_center);
$this->db->where('sale_no',$sale_no);
$this->db->where('published','Yes');
$this->db->where('soft_delete',1);
$this->db->group_by('catalouge_name, supplement_tea');
$this->db->order_by('catalouge_order ASC');
$query = $this->db->get();
if($query->num_rows() > 0){
$result1 = $query->result_array();
foreach($result1 as $row1){
?>

<tr>
    <td><?php echo $row1['catalouge_name']; ?></td>
	<td style="text-align:right;"><?php echo $row1['lot_no']; ?></td>
	<td style="text-align:right;"><?php echo $row1['chest_number']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row1['net_kgs'],2,'.',''); ?></td>
</tr>

<?php
$lot_no += $row1['lot_no'];
$chest_number += $row1['chest_number'];
$net_kgs += $row1['net_kgs'];
$sale_date = $row1['sale_date'];

} // foreach

}else{
	print "<tr><td colspan=4>No matching record found.</td></tr>";
}
?>
</tbody>
<tfoot>
<tr>				
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo $lot_no; ?></th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
</tr>
</tfoot>
</table>
</div>
</div> <!-- /col -->
</div> <!-- /row -->
</div> 
<!-- /container -->
<script>
$("#sale_date").html('<?php print $sale_date; ?>');
</script>