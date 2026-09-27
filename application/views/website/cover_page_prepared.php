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
}	

table.table-bordered{
    border:1px solid black;
}
table.table-bordered > thead > tr > th{
    border:1px solid black;
	padding:0px;
}
table.table-bordered > tbody > tr > td{
    border:1px solid black;
	padding:0px;
}

.row{
	margin:auto !important;
}
</style>

<div class="container">
<div id="page">

<!-- start row -->
<div class="row">
<div class="col-sm-12">
<h1 style="text-align:center;"><?php print $ubl_user_name; ?></h1>
<p style="text-align:center;"> Address </p>
<p style="text-align:center;"> Phone # Email </p>
<hr style="border-color:#000000;">

<h1 style="text-align:center; font-size:50px;"> SALE NO. <span id="th_arg1"></span> </h1>
<p style="text-align:center; font-size:35px;"> BANGLADESH TEA </p>
<p style="text-align:center; font-size:15px;"> <b>SEASON <span id="th_arg2"></span> MANUFACTURE</b> </p>
<p style="text-align:center; font-size:14px;"> (Except were otherwise shown) </p>
<p style="text-align:center; font-size:15px;"> <b>TO BE SOLD BY AUCTION</b> </p>
<p style="text-align:center; font-size:15px;"> <b>AUCTION ADDRESS</b> </p>
<p style="text-align:center; font-size:15px;"> <b>AUCTION DATE</b> </p>
<p style="text-align:center; font-size:15px;"> <b>AUCTION TIME / ZONE </b> </p>
<p style="text-align:center; font-size:15px;"> SUBJECT TO THE RECEIPET OF SELLERS INSTRUCTION </p>
</div> <!-- col -->
</div> <!-- row -->

<!-- valuation report :: after catalouge prepared -->
<div class="row">
<div class="col-sm-12">
<table class="table table-bordered" style="white-space:nowrap;">
<thead>
<tr>
	<th style="text-align:left">ITEM</th>
	<th style="text-align:right">LOT</th>
	<th style="text-align:right">BAGS</th>
	<th style="text-align:right">WT.(KGS)</th>
</tr>
</thead>
<tbody>
<?php
$number_of_lot = 0;
$number_of_bags = 0;
$net_kgs = 0;

foreach($resultq1 as $row1){
	
$sale_no = $row1['sale_no'];
$season = $row1['season'];
?>
<tr>
	<td><?php echo $row1['catalouge_name']; ?></td>
	<td style="text-align:right"><?php echo $row1['number_of_lot']; ?></td>
	<td style="text-align:right"><?php echo $row1['number_of_bags']; ?></td> 
	<td style="text-align:right"><?php echo number_format($row1['net_kgs'],2,'.',''); ?> </td>
</tr>
<?php
$number_of_lot += $row1['number_of_lot'];
$number_of_bags += $row1['number_of_bags'];
$net_kgs += $row1['net_kgs'];
}
?>
<tr>
	<td style="text-align:left; font-weight:bold;">TOTAL</td>
	<td style="text-align:right; font-weight:bold;"><?php print $number_of_lot; ?></td>
	<td style="text-align:right; font-weight:bold;"><?php print $number_of_bags; ?></td>
	<td style="text-align:right; font-weight:bold;"><?php print number_format($net_kgs,2,'.',''); ?></td>
</tr>
</tbody>
</table>

<h3 style="text-align:center; font-size:15px;"> <b>PROMPT, <?php print date("jS F Y",strtotime($sale_schedule_data)); ?></b> </h3>
<p style="text-align:center; font-size:15px;"> <b>CONDITIONS OF SALE AS PRINTED</b> </p>

<h3 style="text-align:center; font-size:20px;"> <b><u>SALE ORDER</u></b> </h3>
<table style="margin:auto;">
<?php
foreach($so_list_data as $brokers_list){
?>	
<tr>
	<td style="text-align:left;">
	<?php 
	if($ubl_user_name == $brokers_list['brokers_name']){
		print "<b>" . sprintf("%02d", $brokers_list['order_serial']) . '-' . $brokers_list['brokers_name'] . "</b>";
	}else{
		print sprintf("%02d", $brokers_list['order_serial']) . '-' . $brokers_list['brokers_name'];
	}
	?>
	</td>
</tr>
<?php
}
?>
</table>

</div> <!-- col -->
</div> <!-- row -->

<script>
$( document ).ready(function() {
    $("#th_arg1").html('<?php print $sale_no; ?>');
	$("#th_arg2").html('<?php print $season; ?>');
});
</script>

</div> <!-- page -->
</div> <!-- container -->
<?php
}
?>

</body>
</html>