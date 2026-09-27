<div class="container-fluid text-center">    
	<div class="row">
<style>
label{ font-size:12px; }
</style>

<div class="col-sm-12">
<div class="row">
<div class="col-sm-12">
<div class="well" id="form_group_element">
<div class="row">
<div class="form-group col-sm-10">
    <b for="comment"><span class="glyphicon glyphicon-file text-primary"></span><a href="<?php echo base_url().'index.php/MY_Controller/pre_invoice_verify_entry'; ?>"> Invoice Parking </a><i id="form_mode"></i> &nbsp;&nbsp;<span id="response_msg"></span></b>
</div>
<div class="form-group col-sm-2 text-right">
    <b for="comment"><span class="glyphicon glyphicon-file text-primary"></span><a href="<?php echo base_url().'index.php/MY_Controller/invoice_entry'; ?>"> Invoice Entry </a><i id="form_mode"></i></b>
</div>
</div>
<hr>
<div class="row">
	<div class="form-group col-sm-3">
		<label for="Garden">Garden</label>
		<select class="form-control input-sm" name="garden_id" id="garden_id" style="text-transform:uppercase;" onchange="show_invoice_parking_data();">
			<option value="">Select Garden</option>
			<?php 
				foreach($garden_data as $garden_data_list){
					print "<option value=\"$garden_data_list[id]\">$garden_data_list[garden_name] </option>";
				}
			?>
		</select>
	</div>

	<div class="form-group col-sm-3">
		<label for="Grade">Grade</label>
		<select class="form-control input-sm" name="grade_id" id="grade_id" style="text-transform:uppercase;" onchange="show_invoice_parking_data();">
		<option value="">Select Grade</option>
		<?php 
			foreach($grade_data as $grade_data_list){
				print "<option title=\"$grade_data_list[catalogue_name]\" value=\"$grade_data_list[id]\">$grade_data_list[grade_name]</option>";
			}
		?>
		</select>
	</div>

	<div class="form-group col-sm-6 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>

	<!-- fixed value -->
	<input readonly type="hidden" name="user" id="user" value="<?php print $ubl_web_user_id; ?>">
	<input readonly type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
	<!-- End fixed data -->
</div>
<div class="row">
		<div class="col-sm-12" id="pre_invoice_verify_list">
			<!-- load stock data-->
		</div>
</div>
		
		</div> <!-- /col-->
		</div> <!-- /col-->
		</div> <!-- /row-->
		</div> <!-- /col-->
	</div> <!-- /row -->
</div> 
<!-- /container -->
<script>
$(function(){
	show_invoice_parking_data(); 
});

// relaod page
function show_pre_invoice_list(){
	window.location.reload(true);
}

function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
</script>