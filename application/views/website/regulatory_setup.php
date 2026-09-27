<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>

		<div class="col-sm-7">
			<div class="row">
				<div class="col-sm-12">
					<b>
						<nav aria-label="breadcrumb">
						  <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_add_new"); ?>">Setup</a></li>
							<li class="breadcrumb-item active" aria-current="page">Regulatory Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="regulatorty_setup_details" action="#">

						  <!-- fixed value -->
						  <input type="hidden" class="form-control" name="setup_id" id="setup_id">
						  <input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
						  <input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
						  <input required type="hidden" name="password" id="password" value="<?php echo $password; ?>">
						  <!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit"></span> Regulatory Setup Details # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_regulatory_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr>
							
							<div class="row">
								<div class="form-group col-sm-12 col-md-6 col-lg-6">
								  <label for="email">Name</label>
								  <input required type="text" class="form-control" name="regulatory_name" id="regulatory_name" placeholder="Enter Regulatory Name">
								</div>
								<div class="form-group col-sm-12 col-md-6 col-lg-6">
								  <label for="email">Address</label>
								  <input required type="text" class="form-control" name="regulatory_address" id="regulatory_address" placeholder="Enter Regulatory Address">
								</div>
								<div class="form-group col-sm-12 col-md-6 col-lg-6">
								  <label for="email">Contact No.</label>
								  <input required type="text" class="form-control" name="regulatory_contact_no" id="regulatory_contact_no" placeholder="Enter Contact No.">
								</div>
								<div class="form-group col-sm-12 col-md-6 col-lg-6">
								  <label for="email">Email</label>
								  <input required type="email" class="form-control" name="regulatory_email" id="regulatory_email" placeholder="Enter Regulatory Email">
								</div>
							</div> <!-- /row -->
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary">
						</form>
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->
		
		<?php
		  include("login_right_bar.php");
		?>
	  
	</div> <!-- /row -->
</div> <!-- /container -->

<script>
function external_comeback() {
  alert('Let your data view details.');
  window.focus();
  $("#regulatory_name").focus();
}
</script>