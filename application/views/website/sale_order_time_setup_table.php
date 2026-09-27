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
  
<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>
	<th colspan="8" style="text-align:center; font-size:18px; border:0px;">Sale Order & Time Setup</th>
</tr>

<tr>
	<th colspan="8" style="text-align:center; font-size:16px; border:0px;" id="sale_season"></th>
</tr>

<tr>				
	<th rowspan=2>Sale Order</th>
	<th rowspan=2>Brokers</th>
    <th rowspan=2>Total Lots</th>
    <th style="text-align:center;" colspan=2>Timing</th>
    <th rowspan=2>Hrs:Min</th>
	<th rowspan=2>--</th>
</tr>

<tr>
	<th> From </th>
	<th> To </th>
</tr>
</thead>

<tbody>

<?php
if(count($resultq1) > 0){
	
$sl 			= 1;
$season 		= '';
$sale_no 		= '';
$sale_date 		= '';
$sale_day 		= '';
$catalouge_name = '';
$lot_no 		= 0;
$active_lots 	= 0;

foreach($resultq1 as $sale_s_data){
	
$season 		= $sale_s_data['season'];
$sale_no 		= $sale_s_data['sale_no'];
$sale_date 		= $sale_s_data['sale_date'];
$sale_day 		= $sale_s_data['sale_day'];
$catalouge_name = $sale_s_data['catalouge_name'];	
$active_lots 	= $sale_s_data['active_lots'];	
$count 			= $sl++;

if(!empty($sale_s_data['order_serial'])){
	$sale_order = $sale_s_data['order_serial'];
}else{
	$sale_order = '';
}

if(!empty($sale_s_data['time_from'])){
	$time_from = date("H:i",strtotime($sale_s_data['time_from']));
}else{
	$time_from = date("H:i");
}

if(!empty($sale_s_data['time_to'])){
	$time_to = date("H:i",strtotime($sale_s_data['time_to']));
}else{
	$time_to = date("H:i");
}	
?>    

<tr>
    <td style="text-align:center;"><?php echo $sale_order; ?></td>
	<td><?php echo $sale_s_data['brokers_name']; ?></td>
	<td style="text-align:center; font-weight:bold;"><?php echo $sale_s_data['lot_no']; ?></td>
	
	<td>
		<div class="form-group">
			<div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-format="hh:ii">
				<input type="text" class="form-control input-sm" id="timing_from<?php echo $sale_s_data['brokers_id']; ?>" value="" placeholder="<?php print $time_from; ?>" style="font-weight:bold;" />
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
			</div>
		</div>
	</td>
	
	<td>
		<div class="form-group">
			<div class="input-group date form_time" data-date="" data-date-format="hh:ii" data-link-format="hh:ii">
				<input type="text" class="form-control input-sm" id="timing_to<?php echo $sale_s_data['brokers_id']; ?>" value="" placeholder="<?php print $time_to; ?>" style="font-weight:bold;" />
				<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
				<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
			</div>
		</div>
	</td>
	
	<td style="text-align:center;"><?php echo $sale_s_data['hrs_min']; ?></td>
	
	<td><input type="button" value="Save" class="btn btn-primary" onclick="fn_sale_order_time_setup('<?php print $season; ?>','<?php print $sale_no; ?>','<?php print $catalouge_name; ?>','<?php echo $sale_order; ?>','<?php echo $sale_s_data['brokers_id']; ?>','<?php echo $sale_s_data['lot_no']; ?>',timing_from<?php echo $sale_s_data['brokers_id']; ?>.value,timing_to<?php echo $sale_s_data['brokers_id']; ?>.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>');"></td>
</tr>

<?php
$lot_no += $sale_s_data['lot_no'];

} // foreach
?>

<tr>
	<th colspan="2"> Total </th>
	<th style="text-align:center;"><?php print $lot_no; ?></th>
	<th colspan="4"> -- </th>
</tr>

<?php
}else{
	print "<tr><td colspan=8>Sorry !! matching records not found.</td></tr>";
}
?>

</tbody>
</table>
</div>

<hr>
<p><b>Set Number of active lots for this Season</b></p>
<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<tr>
	<th>Active Lots</th>
	<th><input type="number" class="form-control input-sm" id="active_lots" value="<?php print $active_lots; ?>" /></th>
	<th><input type="button" value="Save" class="btn btn-primary" onclick="fn_no_of_active_lots('<?php print $season; ?>','<?php print $sale_no; ?>','<?php print $catalouge_name; ?>',active_lots.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>');"></th>
</tr>
</table>
</div>

</div> <!-- end col -->
</div> <!-- end row -->
</div> <!-- /container -->

<script>
$(function () {
	$('.form_time').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
    });
	
	// get heading
	$("#sale_season").html('<font color=green> Season: <?php print $season; ?> / Sale No: <?php print $sale_no; ?> / Sale Date: <?php print $sale_date; ?> / Sale Day: <?php print $sale_day; ?> / Catalogue: <?php print $catalouge_name; ?>');

});

//sale order/time data save
function fn_sale_order_time_setup(season,sale_no,catalouge_name,sale_order,brokers_id,total_lot,time_from,time_to,last_update){
	// check html value
	if(time_from == ''){
		alert("Timing From is empty.");
		$("#timing_from"+brokers_id).focus();
		return false;
	}
	if(time_to == ''){
		alert("Timing From is empty.");
		$("#timing_to"+brokers_id).focus();
		return false;
	}	
	// response
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('MY_Controller/save_sale_order_timing_data'); ?>",
			dataType : "JSON",
			data: {season: season,sale_no: sale_no,catalouge_name: catalouge_name,sale_order: sale_order,brokers_id: brokers_id,total_lot: total_lot,time_from: time_from,time_to: time_to,last_update: last_update},
			success: function(data) {
				if(data==1){
					alert("Success !!");
					window.location.reload();
				}else if(data==2){
					alert("Updated !!");
					window.location.reload();
				}
			}
		});
	return false;
}
// end

//set no. of active lots
function fn_no_of_active_lots(season,sale_no,catalouge_name,active_lots,last_update){
	// check html value
	if(active_lots == ''){
		alert("Active lots box is empty.");
		$("#active_lots").focus();
		return false;
	}
	
	// response
	$.ajax({
			type: "POST",
			url: "<?php echo site_url('MY_Controller/save_active_lots_data'); ?>",
			dataType : "JSON",
			data: {season: season,sale_no: sale_no,catalouge_name: catalouge_name,active_lots: active_lots,last_update: last_update},
			success: function(data) {
				if(data==1){
					alert("Success !!");
					window.location.reload();
				}else if(data==2){
					alert("Updated !!");
					window.location.reload();
				}
			}
		});
	return false;
}
// end
</script>

<?php
} // first if brace
?>