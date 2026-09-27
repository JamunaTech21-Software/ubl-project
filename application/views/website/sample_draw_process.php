<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>

		<div class="col-sm-9">
			<div class="row">
				<div class="col-sm-12">
					<b>
						<nav aria-label="breadcrumb">
						  <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_add_new"); ?>">Setup</a></li>
							<li class="breadcrumb-item active" aria-current="page">Sample Draw Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> 
			<!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="sample_draw_process_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Sample Draw Setup # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_sample_draw_p_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
	
							<hr>

							<div class="row">
								<div class="form-group col-sm-4">
								  <label for="email">Sample Name</label>
								  <select required name="sample_name" id="sample_name" onChange="get_kgs_sample(this.value);" class="form-control">
										<option value="">Select Sample</option>
										<?php 
											foreach($catalogue_data as $catalogue_list){
											  print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
											}
										?>
								  </select>
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Sample Kgs</label>
								  <input required type="number" class="form-control" name="sample_kgs" id="sample_kgs" value="" step="0.001">
								</div>
								<div class="form-group col-sm-4">
								  <input type="submit" value="Submit" id="Submit" class="btn btn-primary" style="margin-top:25px;">
								</div>
							</div> <!-- /row -->	
						</form>
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->
		
		<?php
		  //include("login_right_bar.php");
		?>
	  
	</div> <!-- /row -->	
</div> <!-- /container -->	
    
<script>
function external_comeback() {
  alert('Let your data view details.');
  window.focus();
  $("#sample_name").focus();
}
</script>