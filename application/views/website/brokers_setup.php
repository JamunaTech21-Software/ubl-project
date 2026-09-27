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
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/catalogue_information"); ?>">Catalog Setup</a></li>
							<li class="breadcrumb-item active" aria-current="page">Brokers Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="brokers_setup_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" name="setup_id" id="setup_id">
							<input required type="hidden" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<input required type="hidden" name="password" id="password" value="<?php echo $password; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Brokers Setup # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_brokers_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>

							<hr />

							<div class="row">
								<div class="form-group col-sm-12 col-md-6 col-lg-4">
								  <label for="email">Brokers Name</label>
								  <input required type="text" class="form-control" name="brokers_name" id="brokers_name" style="text-transform:uppercase;" />
								</div>
								
								<div class="form-group col-sm-12 col-md-6 col-lg-4">
								  <label for="email">Short Key Word</label>
								  <input required type="text" class="form-control" name="short_key_code" id="short_key_code" maxlength="8" style="text-transform:uppercase;" />
								</div>

								<div class="form-group col-sm-12 col-md-6 col-lg-4">
								  <label for="email">Address</label>
								  <input type="text" class="form-control" name="brokers_address" id="brokers_address" />
								</div>

								<div class="form-group col-sm-12 col-md-6 col-lg-4">
								  <label for="email">Contact No.</label>
								  <input type="text" class="form-control" name="brokers_contact_no" id="brokers_contact_no" />
								</div>

								<div class="form-group col-sm-12 col-md-6 col-lg-4">
								  <label for="email">Email</label>
								  <input type="email" class="form-control" name="brokers_email" id="brokers_email" />
								</div>

								<div class="form-group col-sm-12 col-md-6 col-lg-4">
								  <label for="email">TIN No.</label>
								  <input type="text" class="form-control" name="brokers_tin_no" id="brokers_tin_no" />
								</div>

								<div class="form-group col-sm-12 col-md-6 col-lg-4">
								  <label for="email">BIN No.</label>
								  <input type="text" class="form-control" name="brokers_bin_no" id="brokers_bin_no" />
								</div>
							</div> <!-- /row -->	
							
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary">
						</form>
					</div> <!-- /well -->	
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->
	</div> <!-- /row -->	
</div> <!-- /container -->		

<script>
function external_comeback() {
	alert('Let your data view details.');
	window.focus();
	$("#brokers_name").focus();
}
</script>