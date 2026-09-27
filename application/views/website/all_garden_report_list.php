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
	<th colspan="7" style="border-bottom:1px solid; text-transform: uppercase; font-size:16px; text-align:center;"><?php print $ubl_user_name; ?></th>
</tr>

<tr>
	<th colspan="2" style="text-align:left; border:0px;">All Garden Offering</th>
	<th colspan="3" id="th_sn" style="text-align:center; border:0px;"></th>
	<th colspan="2" id="th_season" style="text-align:center; border:0px;"></th>
</tr>

<tr>
	<th style="text-align:center;">SL</th>
    <th style="text-align:center;">Garden <br> name</th>
	<th style="text-align:center;">Inv. total</th>
	<th style="text-align:center;">Net total</th>
	<th style="text-align:center;">Total bag</th>
	<th style="text-align:center;">Valuation</th>
	<th style="text-align:center;">Avg.</th>
</tr>
</thead>  

<tbody>
<?php
if(count($resultq1) > 0){
	
$sl = 1;
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$g_total_valuation = 0;

foreach($resultq1 as $row1){

$season = $row1['season'];
$sale_no = $row1['sale_no'];	
?>

<tr>

	<td><?php echo $sl++; ?></td>
    <td><?php echo $row1['garden_name']; ?></td>
	<td style="text-align:right;"><?php echo number_format($row1['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row1['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo round($row1['total_bag']); ?></td>
	<td style="text-align:right;"><?php echo number_format($row1['total_valuation'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php echo number_format($row1['valuation_avg'],2,'.',''); ?></td>
	
</tr>	

<?php
	$g_total_kgs += $row1['total_kgs'];
	$g_net_kgs += $row1['net_kgs'];
	$g_chest_number += $row1['total_bag'];
	$g_total_valuation += $row1['total_valuation'];

} // second loop	
?>

<tr>
	<th colspan="2"> TOTAL </th>
	<th style="text-align:right;"><?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right;"><?php echo number_format($g_total_valuation,2,'.',''); ?></th>
	<th colspan="1"></th>
</tr>

<?php
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
});
</script>

<?php
} // first if brace
?>