<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$warehouse_id = (!empty($this->input->get('w_house_id', TRUE))) ? $this->input->get('w_house_id', TRUE) : '';
$garden_id_whd = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
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
	-fs-table-paginate: paginate;
}
#table{
	border-color:black;
}
#table tr td{
	border-color:black;
	font-size:12px !important;
	padding:5px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
}

@media print{    
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	
	/* header repeat */
	.thead1 { display: table-header-group; }
	.thead2 { display: table-header-group; }
	.thead3 { display: table-header-group; }
	.thead4 { display: table-header-group; }
	.thead5 { display: table-header-group; }
	.thead6 { display: table-header-group; }
	.thead7 { display: table-header-group; }
	
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
	<div class="form-group col-sm-12 text-right">
		<a class="no-print" href="javascript:void(0);" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<?php if(empty($warehouse_id) && empty($garden_id_whd)){ ?>

<div class="row">
<div class="col-sm-12">

<table class="table table-sm" id="table">
<thead>
	<tr>
		<th colspan="3" style="font-size:16px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			<?php print $website_title; ?>
		</th>
	</tr>
	<tr>
		<th colspan="3" style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			Warehouse Wise Offering Report
		</th>
	</tr>
	<tr>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:left;">
			Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?>
		</th>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			<?php print $auction_center; ?>
		</th>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:right;">
			Season: <?php print $season; ?>
		</th>
	</tr>
</thead>
</table>
	
<table class="table table-sm" id="table">
<thead>	
	<tr>
		<th style="text-align:center;">SL</th>
		<th style="text-align:left;">Warehouse Name</th>
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

// query
$this->db->select("warehouse_setup.warehouse_name, invoice_entry.w_house_id, sum(total_kgs) AS total_kgs, sum(net_kgs) AS net_kgs, sum(chest_number) AS total_bag"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id');
$this->db->order_by('warehouse_setup.warehouse_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<tr>
	<td style="text-align:center;"><?php echo $sl++; ?></td>
	<td style="text-align:left; text-transform:uppercase;"><a target="_blink" href="<?php echo site_url('MY_Controller/warehouse_wise_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&w_house_id='.$row1['w_house_id']); ?>"><?php echo $row1['warehouse_name']; ?></a></td>
	<td style="text-align:right;"><?php echo number_format($row1['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row1['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row1['total_bag']; ?></td>
</tr>
<?php 
	$g_total_kgs += $row1['total_kgs'];
	$g_net_kgs += $row1['net_kgs'];
	$g_chest_number += $row1['total_bag'];
}

}else{
	print "<tr><td colspan=5>No matching record found.</td></tr>";
}
?>
<tr>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $g_chest_number; ?></th>
</tr>
</tbody>
</table>
</div>
</div>
<?php } ?>




<?php if(!empty($warehouse_id)){ 

// warehouse name
$query = $this->db->query("SELECT * FROM `warehouse_setup` WHERE `id` = '".$warehouse_id."' "); 
$wh_name_result = $query->row_array();
?>
<div class="row">
<div class="col-sm-12">

<table class="table table-sm" id="table">
<thead>
	<tr>
		<th colspan="3" style="font-size:16px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			<?php print $website_title; ?>
		</th>
	</tr>
	<tr>
		<th colspan="3" style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			Warehouse Wise Offering Report
		</th>
	</tr>
	<tr>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:left;">
			Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?>
		</th>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			<?php print $auction_center; ?>
		</th>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:right;">
			Season: <?php print $season; ?>
		</th>
	</tr>
</thead>
</table>

<table class="table table-sm" id="table">
<?php
$sl = 1;
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.w_house_id', $warehouse_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead class="thead<?php print $sl++; ?>">
<tr>
    <th colspan="8" style="font-size:14px !important; border:0px !important; text-transform:uppercase;"><?php print $wh_name_result['warehouse_name']; ?> / <?php print $row1['garden_name']; ?></th>
</tr>
<tr>
    <th style="text-align:center;">Sale No</th>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Inv.</th>
    <th style="text-align:center;">Grade Name</th>
	<th style="text-align:center;">WT P/Bag</th>
	<th style="text-align:center;">Net WT P/Bag</th>
	<th style="text-align:center;">Total Kgs</th>
	<th style="text-align:center;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
</tr>
</thead>
<tbody>
<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$garden_id = $row1['garden_id'];

// query
$this->db->select("invoice_entry.sale_no"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season',$season);
$this->db->where('auction_center',$auction_center);
$this->db->where("sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('w_house_id',$warehouse_id);
$this->db->where('garden_id',$garden_id);
$this->db->where('soft_delete', 1);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
$this->db->select("invoice_entry.sale_no, invoice_entry.lot_no, invoice_entry.w_inv_no, CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, invoice_entry.gross_wt_per_bag, invoice_entry.net_wt_per_bag, invoice_entry.total_kgs, invoice_entry.net_kgs, invoice_entry.chest_number"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no',$row2['sale_no']);
$this->db->where('invoice_entry.w_house_id',$warehouse_id);
$this->db->where('invoice_entry.garden_id',$garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
?>
<tr>
    <td style="text-align:center;"><?php echo $row3['sale_no']; ?></td>
    <td style="text-align:center;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:center; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['gross_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row3['chest_number']; ?></td>
</tr>
<?php
	$total_kgs += $row3['total_kgs'];
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
	
}  // 3rd loop
?>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
</tr>
<?php
	$g_total_kgs += $total_kgs;
	$g_net_kgs += $net_kgs;
	$g_chest_number += $chest_number;

} // 2nd loop	
?>
<tr>
	<th colspan="9" style="padding:10px !important; border:0px !important;"></th>
</tr>
<?php
	$n_total_kgs += $g_total_kgs;
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
	
} // 1st loop
?>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo number_format($n_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
</tr>
</tbody>
</table>
<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> 
<!-- /row -->
<?php } ?>




<?php if(!empty($garden_id_whd)){ 

// garden name
$query = $this->db->query("SELECT * FROM `garden_and_company_setup` WHERE `id` = '".$garden_id_whd."' "); 
$garden_name_result = $query->row_array();
?>

<div class="row">
<div class="col-sm-12">

<table class="table table-sm" id="table">
<thead>
	<tr>
		<th colspan="3" style="font-size:16px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			<?php print $website_title; ?>
		</th>
	</tr>
	<tr>
		<th colspan="3" style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			Warehouse Wise Offering Report
		</th>
	</tr>
	<tr>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:left;">
			Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?>
		</th>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:center;">
			<?php print $auction_center; ?>
		</th>
		<th style="font-size:13px !important; border:0px !important; text-transform:uppercase; text-align:right;">
			Season: <?php print $season; ?>
		</th>
	</tr>
</thead>
</table>


<table class="table table-sm" id="table">
<?php
$n_net_kgs = 0;
$n_chest_number = 0;
//query
$this->db->select("invoice_entry.w_house_id, warehouse_setup.short_key_word"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $garden_id_whd);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.w_house_id');
$this->db->order_by('warehouse_setup.short_key_word ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<thead>
<tr>
    <th colspan="3" style="font-size:14px !important; border:0px !important;"><?php print $garden_name_result['garden_name']; ?> / <?php print $row1['short_key_word']; ?></th>
</tr>
<tr>
    <th style="text-align:center;">Sale No</th>
	<th style="text-align:right;">Chest</th>
	<th style="text-align:right;">Net Kgs</th>
</tr>
</thead>
<tbody>
<?php
$net_kgs = 0;
$chest_number = 0;
$w_house_id = $row1['w_house_id'];

// query
$this->db->select("invoice_entry.sale_no, sum(invoice_entry.chest_number) AS chest_number, sum(invoice_entry.net_kgs) AS net_kgs"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season',$season);
$this->db->where('auction_center',$auction_center);
$this->db->where("sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('w_house_id',$w_house_id);
$this->db->where('garden_id',$garden_id_whd);
$this->db->where('soft_delete', 1);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query2 = $this->db->get();
foreach($query2->result_array() as $row2){
?>
<tr>
    <td style="text-align:center; border:0px !important;"><?php echo $row2['sale_no']; ?></td>
	<td style="text-align:right; border:0px !important;"><?php echo $row2['chest_number']; ?></td>
	<td style="text-align:right; border:0px !important;"><?php echo number_format($row2['net_kgs'],2,'.',''); ?></td>
</tr>	
<?php
	$net_kgs += $row2['net_kgs'];
	$chest_number += $row2['chest_number'];
	
}  // 2rd loop
?>
<tr>
	<th style="text-align:right;">Total : </th>
	<th style="text-align:right;"><?php echo $chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
</tr>
<tr>
	<th colspan="3" style="padding:10px !important; border:0px !important;"></th>
</tr>
<?php
	$n_net_kgs += $net_kgs;
	$n_chest_number += $chest_number;
	
} // 1st loop
?>
<tr>
	<th style="text-align:right;">Net Total : </th>
	<th style="text-align:right;"><?php echo $n_chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($n_net_kgs,2,'.',''); ?></th>
</tr>
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
</script>