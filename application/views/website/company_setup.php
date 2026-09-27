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
							<li class="breadcrumb-item active" aria-current="page">Company Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="company_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" name="setup_id" id="setup_id">
							<input required type="hidden" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<input required type="hidden" name="password" id="password" value="<?php echo $password; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Company Details # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_company_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />
							
							<div class="row">
								<div class="form-group col-sm-4">
								  <label for="email">Company Short Name</label>
									<input required type="text" class="form-control" name="company_name" id="company_name" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Company Full Name</label>
									<input required type="text" class="form-control" name="company_full_name" id="company_full_name" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Full Address</label>
								  <input type="text" class="form-control" name="company_address1" id="company_address1" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Short Address</label>
								  <input type="text" class="form-control" name="company_address2" id="company_address2" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Contact No.</label>
								  <input type="text" class="form-control" name="company_contact_no" id="company_contact_no" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Email</label>
								  <input type="text" class="form-control" name="company_email" id="company_email" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Reg. No.</label>
								  <input type="text" class="form-control" name="reg_no" id="reg_no" />
								</div>
								<div class="form-group col-sm-4">
									<label for="To">To</label>
									<textarea class="form-control" name="com_report_to" id="com_report_to" rows="1"></textarea>
								</div>
								<div class="form-group col-sm-4">
									<label for="CC">CC</label>
									<textarea class="form-control" name="com_report_cc" id="com_report_cc" rows="1"></textarea>
								</div>
							</div> 
							<!-- /row -->
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary" />
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
  $("#company_name").focus();
}
</script>