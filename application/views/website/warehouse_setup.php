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
							<li class="breadcrumb-item active" aria-current="page">Warehouse Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="warehouse_setup_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" name="setup_id" id="setup_id">
							<input required type="hidden" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<input required type="hidden" name="password" id="password" value="<?php echo $password; ?>">
							<!-- End fixed data -->
							
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Warehouse Setup # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_warehouse_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />
							
							<div class="row">
								<div class="form-group col-sm-4">
									<label for="email">Warehouse Name</label>
									<input required type="text" class="form-control" name="warehouse_name" id="warehouse_name" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Short Key Word</label>
									<input required type="text" class="form-control" name="short_key_word" id="short_key_word" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Address</label>
									<input type="text" class="form-control" name="warehouse_address" id="warehouse_address" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Address Short Key</label>
									<input type="text" class="form-control" style="text-transform: uppercase;" name="warehouse_address2" id="warehouse_address2" />
								</div>
								<div style="display:none;" class="form-group col-sm-4">
									<label for="email">Serial (1)</label>
									<input type="text" class="form-control" name="warehouse_serial_1" id="warehouse_serial_1" />
								</div>
								<div style="display:none;" class="form-group col-sm-4">
									<label for="email">Serial (2)</label>
									<input type="text" class="form-control" name="warehouse_serial_2" id="warehouse_serial_2" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">BIN No.</label>
									<input type="text" class="form-control" name="warehouse_bin_no" id="warehouse_bin_no" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Contact No.</label>
									<input type="text" class="form-control" name="warehouse_contact_no" id="warehouse_contact_no" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Email Address</label>
									<input type="email" class="form-control" name="warehouse_email_address" id="warehouse_email_address" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Rent Per Bag</label>
									<input required type="number" step="0.01" min="1" class="form-control" name="rent_per_bag" id="rent_per_bag" onfocus="this.select();" value="40" />
								</div>
							</div>
							
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
  $("#warehouse_name").focus();
}
</script>