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

@media print {
  #page{page-break-after: always !important;}
}
</style>

<div class="container">

<!-- start row -->
<div class="row">
<div class="col-sm-12">

<?php
if(count($resultq1) > 0){		
foreach($resultq1 as $page_row){
	
$user = $page_row['user'];
$season = $page_row['season'];
$catalouge_name = $page_row['catalouge_name'];
$sale_no = $page_row['sale_no'];
$sale_date = $page_row['sale_date'];
$sale_date_local = date("d-M-Y",strtotime($page_row['sale_date']));	
?>

<div id="page">

<div class="row">
<div class="col-sm-12">

<table class="table">
<tr>
	<th colspan="9" style="border:0px; text-transform: uppercase; font-size:16px; text-align:center;"><?php print $ubl_user_name; ?></th>
</tr>

<tr>
	<th colspan="5" id="th_arg1" style="text-align:left; border:0px;">Sale NO: <?php print $sale_no; ?></th>
	<th colspan="4" id="th_arg3" style="text-align:right; border:0px; text-transform: uppercase;"><?php print $catalouge_name; ?> PAGE <?php print $page_row['page_no']; ?></th>
</tr>

<tr>	
    <th colspan="5" id="th_arg2" style="text-align:left; border:0px;">SEASON: <?php print $season; ?></th>
	<th colspan="4" id="th_arg4" style="text-align:right; border:0px;"><?php print $sale_date_local; ?></th>
</tr>

<?php
if($page_row['page_no']==1){
?>
<tr>
	<td colspan="9"><p style="font-family:verdana; font-size:12px; white-space:normal !important;"><?php print nl2br($vat_ait_sample_info); ?></p></td>
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
?>

<?php
// query group breakdown
$this->db->select(" 
	invoice_entry.group_no,
	invoice_entry.w_house_id, 
	invoice_entry.garden_id, 
	invoice_entry.chest_name,
	CONCAT(invoice_entry.old_season,' / ',invoice_entry.remarks) AS remarks,
	GROUP_CONCAT(invoice_entry.w_inv_no) AS `w_inv_no`,
	warehouse_setup.short_key_word, 
	garden_and_company_setup.garden_name
"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 			'invoice_entry.w_house_id 	= warehouse_setup.id', 			'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id 	= garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.page_no', $page_row['page_no']);
$this->db->where('invoice_entry.user', $user);
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row1){	
?>

<tr id="group_row" title="Group No # <?php print $row1['group_no']; ?>">
    <th colspan="4" style="border:0px; text-align:left;"><?php print mb_strimwidth($row1['short_key_word'],0,25,".."); ?></th>
	<th colspan="5" style="border:0px; text-align:left;"><?php print mb_strimwidth($row1['garden_name'],0,25,".."); ?> &nbsp; <b style="font-weight:normal; font-size:11px;">Inv: <?php print $row1['w_inv_no']; ?> &nbsp; <?php print $row1['chest_name']; ?> &nbsp; <?php print $row1['remarks']; ?></b></th>
</tr>

<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$group_no = $row1['group_no'];
$chest_name = $row1['chest_name'];
$w_house_id = $row1['w_house_id'];
$garden_id = $row1['garden_id'];
$tpinv_no = 0;

// query lot breakdown
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
$this->db->where('invoice_entry.user', 		$user);
$this->db->where('invoice_entry.group_no', 	$group_no);
$this->db->where('invoice_entry.season', 	$season);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.sale_no', 	$sale_no);
$this->db->where('invoice_entry.sale_date', $sale_date);
$this->db->where('invoice_entry.w_house_id',$w_house_id);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

// same page data 	
if($page_row['page_no'] == $row2['page_no']){	
?>
<tr <?php if($page_row['last_lot'] == $row2['lot_no']){ ?> title="last lot of page <?php print $page_row['page_no']; ?>" <?php } ?>>
    <td class="col-md-1"><?php echo $row2['lot_no']; ?></td>
	<td class="col-md-1"><?php echo $row2['old_lot_no']; ?></td>
	<td class="col-md-1"><?php echo $row2['old_sale_no']; ?></td>
	<td class="col-md-1"><?php echo $row2['w_inv_no']; ?></td>
	<td class="col-md-1"><?php echo mb_strimwidth($row2['grade_name'],0,16,".."); ?></td>
	<td class="col-md-1" style="text-align:left;"><?php echo number_format($row2['gross_wt_per_bag'],2,'.',''); ?></td>
	<td class="col-md-1" style="text-align:left;"><?php echo number_format($row2['net_wt_per_bag'],2,'.',''); ?></td>
	<td class="col-md-1" style="text-align:left;"><?php echo number_format($row2['net_kgs'],2,'.',''); ?></td>
	<td class="col-md-2" style="text-align:left;"><?php echo $row2['chest_number']; ?></td>
</tr>	
<?php
$tpinv_no += 1;
}else{	
	$tpinv_no = 0;
}

// lots total
$g_total_kgs += $row2['total_kgs'];
$g_net_kgs += $row2['net_kgs'];
$g_chest_number += $row2['chest_number'];
} // 3rd loop	
?>

<?php
if($tpinv_no > 0){
?>	
<tr>
	<th colspan="7" style="text-align:center; border:0px;"> Invoice Total: <?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:left; border:0px;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:left; border:0px;"><?php echo $g_chest_number; ?></th>
</tr>
<?php
}
?>

<?php
} // second loop
?>
	
</table>
</div> <!-- col -->
</div> <!-- row -->

</div>  <!-- page -->

<?php
} // first loop

}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. Please Lock the Catalogue Page First. </b></p></div>";
}
?>

</div> <!-- col -->
</div> <!-- row -->

<script>
$( document ).ready(function() {
    // window.print();
});
</script>

</div> <!-- container -->

<?php
} // end group exists
?>

</body>
</html>