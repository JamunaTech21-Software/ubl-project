<?php
if(isset($resultq1)){

## set value
$sl = 1;
$number_of_lot_per_betting = 6;
$lots = 0;

if(count($resultq1) > 0){
foreach($resultq1 as $result){

$lot_count = $sl++;	
if($lot_count <= $number_of_lot_per_betting){
	$style = "color:red; font-weight:bold;";
	@$lots = "$lots,$result[id]";
	$lots_arr = $lots;
}else{
	$style = "color:#545252;";
}	

$season = $result['season'];
$sale_no = $result['sale_no'];
?>

<tr style="<?php print $style; ?>">

	<td><?php print $result['lot_no']; ?></td>
	<td><?php print $result['garden_name']; ?></td>
	<td><?php print $result['warehouse_name']; ?></td>
	<td><?php print $result['grade_name']; ?></td>
	<td style="text-align:right;"><?php print number_format($result['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php print number_format($result['chest_number'],2,'.',''); ?></td>
	<td tyle="text-align:right;"></td>
	<td tyle="text-align:right;"></td>

</tr>			
	
<?php 
} //foreach 
?>

<input type="hidden" id="active_lots" value="<?php print $lots_arr; ?>">
<input type="hidden" id="season" value="<?php print $season; ?>">
<input type="hidden" id="sale_no" value="<?php print $sale_no; ?>">

<?php
}else{
	print "<tr><td colspan=8>No matching record found.</td></tr>";
}

} // first if brace
?>