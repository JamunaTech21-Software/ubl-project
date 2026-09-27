<?php
if(isset($resultq1)){	
?>

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
}

.table{
	font-family:verdana !important;
	font-size:12px !important;
	width: 100% !important;
	white-space:nowrap !important;
}

.row{
	margin:auto !important;
}

#page{
	overflow:hidden;
}
</style>

<div class="container">

<!-- start row -->
<div class="row">
<div class="col-sm-12">
<div id="page">
<br />
<table class="table">
<tr>
	<td colspan="9" style="border:0px; text-transform: uppercase; text-align:center;">
		<b style="font-size:16px;"><?php print $website_title; ?></b> <br />
		<b style="font-size:14px;"><?php print $owner_short_address; ?></b>
	</td>
</tr>

<?php if(count($resultq1) > 0){ ?>
	
<tr>
	<th colspan="5" id="th_arg1" style="text-align:left; border:0px;"></th>
	<th colspan="4" id="th_arg3" style="text-align:right; border:0px; text-transform: uppercase;"></th>
</tr>

<tr>	
    <th colspan="5" id="th_arg2" style="text-align:left; border:0px;"></th>
	<th colspan="4" id="th_arg4" style="text-align:right; border:0px;"></th>
</tr>

<?php if($how_many_page == 0){ ?>
<tr>
	<td colspan="9"><p style="font-family:verdana; font-size:14px; white-space:normal !important;"><?php print nl2br($vat_ait_sample_info); ?></p></td>
</tr>
	
<tr>
    <th style="border:0px;">Lot</th>
	<th style="border:0px;">O/L</th>
	<th style="border:0px;">O/S</th>
    <th style="border:0px;">Inv</th>
    <th style="border:0px;">Grade</th>
	<th style="text-align:left; border:0px;">GT</th>
	<th style="text-align:left; border:0px;">NT</th>
	<th style="text-align:left; border:0px;">TL WT</th>
	<th style="text-align:left; border:0px;">BAGS</th>
</tr>
<?php	
}	
foreach($resultq1 as $row1){
?>

<tr id="group_row" title="Group No # <?php print $row1['group_no']; ?>">
    <th colspan="4" style="border:0px; text-align:left;"><?php print mb_strimwidth($row1['short_key_word'],0,25,".."); ?></th>
	<th colspan="5" style="border:0px; text-align:left;"><?php print mb_strimwidth($row1['garden_name'],0,25,".."); ?> &nbsp; <b style="font-weight:normal; font-size:11px;">Inv: <?php print $row1['w_inv_no']; ?> &nbsp; <?php print $row1['chest_name']; ?> &nbsp; <?php print $row1['remarks']; ?></b>
	</th>
</tr>

<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$group_no = $row1['group_no'];
$season = $row1['season'];
$auction_center = $row1['auction_center'];
$catalouge_name = $row1['catalouge_name'];
$sale_no = $row1['sale_no'];
$sale_date_local = date("d-M-Y",strtotime($row1['sale_date']));
$chest_name = $row1['chest_name'];
$sale_date = $row1['sale_date'];
$w_house_id = $row1['w_house_id'];
$garden_id = $row1['garden_id'];

// query
$this->db->select("
	invoice_entry.lot_no,
	invoice_entry.page_no,
	invoice_entry.old_lot_no,
	invoice_entry.old_sale_no,
	invoice_entry.w_inv_no,
	CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name,	  
	invoice_entry.gross_wt_per_bag, 
	invoice_entry.net_wt_per_bag, 
	invoice_entry.total_kgs, 
	invoice_entry.net_kgs, 
	invoice_entry.chest_number
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.page_no'); // is null
$this->db->where('invoice_entry.group_no',$group_no);
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.sale_date', $sale_date);
$this->db->where('invoice_entry.w_house_id',$w_house_id);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

if($row2['page_no'] > 0){
	// do nothing
}else{
?>
	
<tr>
    <td class="col-md-1"><?php echo $row2['lot_no']; ?> <a title="Lock Page" style="color:red; text-decoration:none;" onclick="fn_page_lock('<?php echo $season; ?>','<?php echo $auction_center; ?>','<?php echo $sale_no; ?>','<?php echo $row2['lot_no']; ?>','<?php print $catalouge_name; ?>')" href="javascript:void(0);"><span class="glyphicon glyphicon-lock"></span></a></td>
	<td class="col-md-1"><?php echo $row2['old_lot_no']; ?></td>
	<td class="col-md-1"><?php echo $row2['old_sale_no']; ?></td>
	<td class="col-md-1" style="text-transform:uppercase;"><?php echo $row2['w_inv_no']; ?></td>
	<td class="col-md-1"><?php echo mb_strimwidth($row2['grade_name'],0,16,".."); ?></td>
	<td class="col-md-1" style="text-align:left;"><?php echo number_format($row2['gross_wt_per_bag'],2,'.',''); ?></td>
	<td class="col-md-1" style="text-align:left;"><?php echo number_format($row2['net_wt_per_bag'],2,'.',''); ?></td>
	<td class="col-md-1" style="text-align:left;"><?php echo number_format($row2['net_kgs'],2,'.',''); ?></td>
	<td class="col-md-2" style="text-align:left;"><?php echo $row2['chest_number']; ?></td>
</tr>	
<?php
}

// group total
$g_total_kgs += $row2['total_kgs'];
$g_net_kgs += $row2['net_kgs'];
$g_chest_number += $row2['chest_number'];

} // second loop	
?>

<tr>
	<th colspan="7" style="text-align:center; border:0px;"> Invoice Total: <?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:left; border:0px;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:left; border:0px;"><?php echo $g_chest_number; ?></th>
</tr>
	
<?php
} // first loop

}else{
?>

<tr> 
	<th colspan="9"> <h1 style="color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;"> An Error Was Encountered </h1> 
	<p style="margin: 12px 15px 12px 15px;"> No matching record found. Please enter new lot in invoice entry. </p>
	</th>
</tr>
	
<?php	
}
?>

</table>
</div> 
<!-- page -->

<p style="text-align:center;"> &copy; <?php print $year; ?> | <?php print $author; ?> </p> 

</div> <!-- col -->
</div> <!-- row -->
</div> <!-- container -->

<script>
$( document ).ready(function() {
    $("#th_arg1").html('SALE NO. &nbsp;&nbsp; <?php print $sale_no; ?>');
	$("#th_arg2").html('SEASON: &nbsp;&nbsp; <?php print $season; ?>');
	$("#th_arg3").html('<?php print $catalouge_name; ?>');
	$("#th_arg4").html('<?php print $sale_date_local; ?>');
});
</script>

<?php
} // end group exists
?>

</body>
</html>