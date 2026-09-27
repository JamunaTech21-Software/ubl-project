<?php if(isset($resultq1)){ ?>
<style>
.table{
	font-family:verdana;
	font-size:12px !important;
	width: 100% !important;
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

<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<?php
if(count($resultq1) > 0){
?>
<thead>
<tr>
	<th style="text-align:center; vertical-align:middle;">
		<input type="checkbox" id="check_all" title="Check All" onclick="lots_checkall()">
	</th>
	<th style="text-align:center; vertical-align:middle;">Garden</th>
	<th style="text-align:center; vertical-align:middle;">Inv No</th>
	<th style="text-align:center; vertical-align:middle;">Grade</th>
	<th style="text-align:center; vertical-align:middle;">Gross</th>
	<th style="text-align:center; vertical-align:middle;">Tare</th>
	<th style="text-align:center; vertical-align:middle;">Net</th>
	<th style="text-align:center; vertical-align:middle;">No.of Bag</th>
	<th style="text-align:center; vertical-align:middle;">Total kgs</th>
	<th style="text-align:center; vertical-align:middle;">Warehouse</th>
	<th style="text-align:center; vertical-align:middle;">Rcv. Date</th>
	<th style="text-align:center; vertical-align:middle;">Inv Group</th>
	<th style="text-align:center; vertical-align:middle;">Chest</th>
	<th style="text-align:center; vertical-align:middle;">Edit & Delete</th>
</tr>
</thead>
<tbody>
<?php
$total_net_kgs = 0;
$total_no_of_bag = 0;
$lots = 0;

foreach($resultq1 as $fatch){

	// lot data
	@$lots = "$lots,$fatch[id]";
	$lots_arr = '0'; //$lots;
?>
<tr id="lot_no<?php echo $fatch['id']; ?>">

	<td style="text-align:center; vertical-align:middle;">
		<input type="checkbox" class="lots_checkbox" id="lot_id<?php echo $fatch['id']; ?>" value="<?php echo $fatch['id']; ?>" onclick="get_lots_id()" />
	</td>
    <td style="text-align:left; vertical-align:middle;"><?php echo $fatch['garden_name']; ?></td>
    <td style="text-align:left; vertical-align:middle;"><?php echo $fatch['w_inv_no']; ?></td>
    <td style="text-align:left; vertical-align:middle;"><?php echo $fatch['grade_name']; ?><?php echo (!empty($fatch['additional_grade'])) ? $fatch['additional_grade'] : ''; ?></td>
    <td style="text-align:right; vertical-align:middle;"><?php echo number_format($fatch['gross_kg'],2,'.',''); ?></td>
    <td style="text-align:right; vertical-align:middle;"><?php echo number_format($fatch['tare_kg'],2,'.',''); ?></td>
    <td style="text-align:right; vertical-align:middle;"><?php echo number_format($fatch['net_kg'],2,'.',''); ?></td>
    <td style="text-align:right; vertical-align:middle;"><?php echo number_format($fatch['no_of_bag'],2,'.',''); ?></td>
    <td style="text-align:right; vertical-align:middle;"><?php echo number_format($fatch['no_of_bag'] * $fatch['net_kg'],2,'.',''); ?></td>
	<td style="text-align:left; vertical-align:middle;"><?php echo $fatch['wh_sk']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo date('d-m-Y', strtotime($fatch['challan_received_date'])); ?></td>
	<td style="text-align:center; vertical-align:middle;"><?php echo $fatch['invoice_group_txt']; ?> <?php echo $fatch['invoice_group']; ?><?php print !empty($fatch['invoice_group_upto']) ? ' - '.$fatch['invoice_group_upto'] : ''; ?></td>
	<td style="text-align:center; vertical-align:middle;"><?php echo $fatch['chest_name']; ?></td>
	<td style="text-align:center; vertical-align:middle;">
		<span style="cursor:pointer; color:green;" onclick="get_update_data_invoice('<?php echo $fatch['id']; ?>','<?php echo $fatch['system_id']; ?>','<?php echo $fatch['challan_received_date']; ?>','<?php echo $fatch['w_house_id']; ?>','<?php echo $fatch['chest_name']; ?>','<?php echo $fatch['garden_id']; ?>','<?php echo $fatch['w_inv_no']; ?>','<?php echo $fatch['grade_id']; ?>','<?php echo $fatch['additional_grade']; ?>','<?php echo $fatch['gross_kg']; ?>','<?php echo $fatch['tare_kg']; ?>','<?php echo $fatch['net_kg']; ?>','<?php echo $fatch['no_of_bag']; ?>','<?php echo $fatch['invoice_group_txt']; ?>','<?php echo $fatch['invoice_group']; ?>','<?php echo $fatch['invoice_group_upto']; ?>')" class="glyphicon glyphicon-edit"></span>
		&nbsp;&nbsp;&nbsp;
		<span style="cursor:pointer; color:red;" class="glyphicon glyphicon-trash" onclick="delete_pre_inv_cart('<?php echo $fatch['id']; ?>');"></span>
	</td>
	
</tr>
<?php
	$total_net_kgs += $fatch['no_of_bag'] * $fatch['net_kg'];
	$total_no_of_bag += $fatch['no_of_bag'];
	
} // End loop	
?>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right; vertical-align:middle;"></th>
	<th style="text-align:right; vertical-align:middle;"></th>
	<th style="text-align:right; vertical-align:middle;"></th>
	<th style="text-align:right; vertical-align:middle;"> TOTAL: 
		<input type="hidden" id="cart_lot_ids" value="<?php print $lots_arr; ?>">
	</th>
	<th style="text-align:right; vertical-align:middle;"><?php echo number_format($total_no_of_bag,2,'.',''); ?></th>
	<th style="text-align:right; vertical-align:middle;"><?php echo number_format($total_net_kgs,2,'.',''); ?></th>
    <th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
</tr>

<?php
}else{
	print "<tr>
			<td colspan=\"13\">No matching record found. <input type=\"hidden\" id=\"cart_lot_ids\" value=\"0\"></td>
		</tr>";
}
?>
</tbody>
</table>
</div>
<?php } // first if brace ?>