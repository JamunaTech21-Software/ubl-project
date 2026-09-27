<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$report_type = (!empty($this->input->get('report_type', TRUE))) ? $this->input->get('report_type', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
?>

<div id="printableArea" class="container">

<style>
body{
	background-color: white !important;
	font-family:verdana;
}
.table{
	font-family:verdana;
	font-size:12px !important;
	border:none;
}
#table tr td{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
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

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>


<?php if($report_type=='Summary'){ ?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	GRADE WISE OFFERING REPORT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
	</th>
</tr>
</table>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-sm" id="table">
<?php
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;
// query
$this->db->select("tea_grade_setup.grade_category"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.grade_category');
$this->db->order_by('tea_grade_setup.grade_category ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>
<tr>
    <th colspan="5" style="font-size:14px !important; border:0px !important;"><?php print $row1['grade_category']; ?></th>
</tr>
<tr>
	<th style="text-align:center;">SL</th>
    <th style="text-align:left;">Grade Name</th>
	<th style="text-align:right;">Total Kgs</th>
	<th style="text-align:right;">Net Kgs</th>
	<th style="text-align:right;">Total Bag</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$this->db->select("tea_grade_setup.grade_name, invoice_entry.grade_id, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs, sum(chest_number) AS total_bag"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('tea_grade_setup.grade_category', $row1['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
$result2 = $query->result_array();
foreach($result2 as $row2){
?>
<tr>
	<td style="text-align:center;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase;"><?php echo $row2['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row2['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row2['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row2['total_bag']; ?></td>
</tr>
<?php 
	$g_total_kgs += $row2['total_kgs'];
	$g_net_kgs += $row2['net_kgs'];
	$g_chest_number += $row2['total_bag'];
} //2nd loop
?>

<tr>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $g_chest_number; ?></th>
</tr>

<?php
	$n_total_kgs += $g_total_kgs;
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
} //1st loop
}else{
	print "<tr><td colspan=5>No matching record found.</td></tr>";
}
?>
</tbody>
<tfoot>
<tr>
	<th colspan="5" style="padding:10px !important; border:0px !important;"></th>
</tr>
<tr>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo number_format($n_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
<?php } ?>


<?php if($report_type=='Details'){ ?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	GRADE WISE GARDEN OFFERING REPORT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
	</th>
</tr>
</table>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table" style="width:80%">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="2" style="text-align:center; border:0px;">OFFERING</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
</tr>
</thead>
<tbody>
<?php
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//garden breakdown
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>

<tr>
    <th style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
		<?php print $row1['garden_name']; ?>
	</th>
	<th style="font-size:14px !important; border:0px;"></th>
	<th style="font-size:14px !important; border:0px;"></th>
	<th style="font-size:14px !important; border:0px;"></th>
</tr>

<?php
$g_net_kgs = 0;
$g_chest_number = 0;

//category breakdown
$this->db->select("tea_grade_setup.grade_category, 
	(CASE 
		WHEN grade_category='BROKENS' THEN 'Leaf'
		WHEN grade_category='DM' THEN 'Leaf'
		WHEN grade_category='FANNINGS' THEN 'Leaf'
		WHEN grade_category='DUST' THEN 'Dust'
		END
	) AS category_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.grade_category');
$this->db->order_by('category_order DESC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;

//grade breakdown
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
</tr>
<?php $net_kgs += $row3['net_kgs']; $chest_number += $row3['chest_number']; } ?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
</tr>
<?php $g_chest_number += $chest_number; $g_net_kgs += $net_kgs; } ?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>


<?php if(!empty($garden_id)){ ?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	GRADE WISE OFFERING REPORT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
	</th>
</tr>
</table>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-sm" id="table">
<?php
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;
//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name, tea_grade_setup.grade_category"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id, tea_grade_setup.grade_category');
$this->db->order_by('garden_and_company_setup.garden_name, tea_grade_setup.grade_category ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>
<tr>
    <th colspan="6" style="font-size:14px !important; border:0px !important;"><?php print $row1['garden_name']; ?> / <?php print $row1['grade_category']; ?></th>
</tr>
<tr>
    <th style="text-align:center;">Sale No</th>
    <th style="text-align:center;">Grade Name</th>
	<th style="text-align:right;">Chest</th>
	<th style="text-align:right;">Net Kgs</th>
</tr>
</thead>
<tbody>
<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;

// query
$this->db->select("invoice_entry.sale_no"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row1['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.sale_no');
$this->db->order_by('invoice_entry.sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no',$row2['sale_no']);
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row1['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>

<tr>
    <td style="text-align:center; border:0px !important;"><?php echo $row3['sale_no']; ?></td>
	<td style="text-align:center; border:0px !important; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px !important;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px !important;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
</tr>

<?php
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	
}  // 3rd loop
?>

<tr>
	<th></th>
	<th style="text-align:right;">Total <?php print $row1['grade_category']; ?> : </th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
</tr>

<?php
	$g_net_kgs += $net_kgs;
	$g_chest_number += $chest_number;

} // 2nd loop	
?>

<tr>
	<th colspan="4" style="padding:10px !important; border:0px !important;"></th>
</tr>
</tbody>
<?php
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	
} // 1st loop
?>
<tfoot>
<tr>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
</tr>
</tfoot>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> <!-- col -->
</div> <!-- row -->
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