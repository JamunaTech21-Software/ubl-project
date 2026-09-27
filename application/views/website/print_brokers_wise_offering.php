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

<div class="container-fluid">
<!-- start row -->
<div class="row">
<div class="col-sm-12">
  
<div class="table-responsive">

<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>
	<th colspan="8" style="text-align:center; font-size:18px; border:0px;">Brokers Wise Offering</th>
</tr>

<tr>
	<th colspan="8" style="text-align:center; font-size:16px; border:0px;" id="sale_season"></th>
</tr>

<tr>				
	<th rowspan=2>SL</th>
	<th rowspan=2>Brokers</th>
    <th style="text-align:center;" colspan=3>Leaf</th>
    <th style="text-align:center;" colspan=3>Dust & Supplement</th>
</tr>

<tr>				
	<th>Lot</th>
	<th>Bags</th>
	<th>Kgs</th>
	
	<th>Lot</th>
	<th>Bags</th>
	<th>Kgs</th>
</tr>
</thead>

<tbody>

<?php
if(count($resultq1) > 0){
	
$sl = 1;
$season = '';
$sale_no = '';
$leaf_lot_no = 0;
$leaf_bags = 0;
$leaf_kgs = 0;

$ds_lot_no = 0;
$ds_bags = 0;
$ds_kgs = 0;

foreach($resultq1 as $sale_s_data){
?>    

<tr>
    <td><?php echo $sl++; ?></td>
	<td><?php echo $sale_s_data['brokers_name']; ?></td>
	
	<td style="text-align:right;"><?php echo $sale_s_data['leaf_lot_no']; ?></td>
	<td style="text-align:right;"><?php echo $sale_s_data['leaf_bags']; ?></td>
	<td style="text-align:right;"><?php echo $sale_s_data['leaf_kgs']; ?></td>
	
	<td style="text-align:right;"><?php echo $sale_s_data['ds_lot_no']; ?></td>
	<td style="text-align:right;"><?php echo $sale_s_data['ds_bags']; ?></td>
	<td style="text-align:right;"><?php echo $sale_s_data['ds_kgs']; ?></td>
</tr> 

<?php
$season = $sale_s_data['season'];
$sale_no = $sale_s_data['sale_no'];

$leaf_lot_no += $sale_s_data['leaf_lot_no'];
$leaf_bags += $sale_s_data['leaf_bags'];
$leaf_kgs += $sale_s_data['leaf_kgs'];

$ds_lot_no += $sale_s_data['ds_lot_no'];
$ds_bags += $sale_s_data['ds_bags'];
$ds_kgs += $sale_s_data['ds_kgs'];

} // foreach
?>

<tr>
	<th colspan="2"> Total </th>
	
	<th style="text-align:right;"><?php print $leaf_lot_no; ?></th>
	<th style="text-align:right;"><?php print $leaf_bags; ?></th>
	<th style="text-align:right;"><?php print $leaf_kgs; ?></th>
	
	<th style="text-align:right;"><?php print $ds_lot_no; ?></th>
	<th style="text-align:right;"><?php print $ds_bags; ?></th>
	<th style="text-align:right;"><?php print $ds_kgs; ?></th>
</tr>

<?php
}else{
	print "<tr><td colspan=8>Sorry !! matching records not found.</td></tr>";
}
?>

</tbody>
</table>
</div>

</div> <!-- end col -->
</div> <!-- end row -->
</div> <!-- /container -->

<script>
$("document").ready(function() {
    $("#sale_season").html('<font color=green> Season: <?php print $season; ?> / Sale No: <?php print $sale_no; ?>');
});
</script>

<?php
} // first if brace
?>