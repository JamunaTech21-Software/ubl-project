<?php
if(isset($resultq1)){	
?>

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
	margin-top:10px;
}

.table{
	font-family:verdana !important;
	font-size:12px !important;
	width: 100% !important;
	white-space:nowrap !important;
}

#table tbody tr:hover td {
  background-color: #E9967A;
}
#table{
	border-color:black;
	white-space:nowrap;
}
#table tr td{
	border-color:black;
}
#table tr th{
	border-color:black;
}
</style>

<div class="container">
<!-- start row -->
<div class="row">
<div class="col-sm-12">

<?php
if(count($resultq1) > 0){	
?>

<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>
	<th colspan="9" style="border-bottom:1px solid; text-transform: uppercase; font-size:16px; text-align:center;"><?php print $ubl_user_name; ?></th>
</tr>

<tr>
	<th colspan="3" style="text-align:left; border:0px;">Catalogue Wise Offering</th>
	<th colspan="2" id="th_sn" style="text-align:left; border:0px;"></th>
	<th colspan="2" id="th_season" style="text-align:center; border:0px;"></th>
	<th colspan="2" id="th_catalogue" style="text-align:right; border:0px;"></th>
</tr>

<tr>
	<th style="text-align:center;">SL</th>
    <th style="text-align:center;">Lot No</th>
    <th style="text-align:center;">Inv.</th>
    <th style="text-align:center;">Grade <br> name</th>
	<th style="text-align:center;">WT <br> P/Bag</th>
	<th style="text-align:center;">Net <br> WT <br> P/Bag</th>
	<th style="text-align:center;">Total <br> Kgs</th>
	<th style="text-align:center;">Net <br> Kgs</th>
	<th style="text-align:center;">Chest</th>
</tr>
</thead>  

<tbody>
<?php
if(count($resultq1) > 0){
foreach($resultq1 as $row1){
?>

<tr>
    <th title="Group No # <?php print $row1['group_no']; ?>" colspan="9"><?php print $row1['garden_name']; ?> / <?php print $row1['short_key_word']; ?></th>
</tr>

<?php
$sl = 1;
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$group_no = $row1['group_no'];
$season = $row1['season'];
$sale_no = $row1['sale_no'];
$sale_date = $row1['sale_date'];
$w_house_id = $row1['w_house_id'];
$garden_id = $row1['garden_id'];
$catalouge_name = $row1['catalouge_name'];

// query
$this->db->select("invoice_entry.*, CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.page_no IS NOT NULL',NULL,FALSE);
$this->db->where('invoice_entry.user', 		$ubl_web_user_id);
$this->db->where('invoice_entry.group_no', 	$group_no);
$this->db->where('invoice_entry.sale_no', 	$sale_no);
$this->db->where('invoice_entry.sale_date', $sale_date);
$this->db->where('invoice_entry.w_house_id',$w_house_id);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr id="lot_no<?php echo $row2['lot_no']; ?>">

	<td><?php echo $sl++; ?></td>
    <td><?php echo $row2['lot_no']; ?></td>
	<td><?php echo $row2['w_inv_no']; ?></td>
	<td><?php echo $row2['grade_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row2['gross_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row2['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row2['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row2['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo $row2['chest_number']; ?></td>
	
</tr>	

<?php
	$g_total_kgs += $row2['total_kgs'];
	$g_net_kgs += $row2['net_kgs'];
	$g_chest_number += $row2['chest_number'];

} // second loop	
?>

<tr>
	<th style="text-align:center;" colspan="4"> TOTAL </th>
	<th colspan="2"></th>
	<th style="text-align:right;"><?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $g_chest_number; ?></th>
</tr>

<?php
} // first loop

}else{
	print "<tr><td colspan=11>No matching record found.</td></tr>";
}
?>

</tbody>
</table>
</div>

<?php
}else{
	
	print "<h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. Please Lock the Catalogue Page First. </b></p>";
}
?>

</div> <!-- col -->
</div> <!-- row -->

</div> <!-- container -->

<script>
$("document").ready(function() {
    $("#th_sn").html('SALE NO.: <?php print $sale_no; ?>');
	$("#th_season").html('SEASON: <?php print $season; ?>');
	$("#th_catalogue").html('CATALOGUE NAME: <?php print $catalouge_name; ?>');
});
</script>

<?php
} // first if brace
?>