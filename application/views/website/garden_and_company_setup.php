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
							<li class="breadcrumb-item active" aria-current="page">Garden & Company Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="garden_company_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" name="setup_id" id="setup_id">
							<input required type="hidden" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<input required type="hidden" name="password" id="password" value="<?php echo $password; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Garden Details # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_garden_and_company_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />
							
							<div class="row">
								<div class="form-group col-sm-4">
									<label for="email">Garden Name</label>
									<input required type="text" class="form-control" name="garden_name" id="garden_name" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
									<label for="garden_type">Garden Type</label>
									<select required class="form-control" name="garden_type" id="garden_type">
										<option value="">Select</option>
										<option value="Main Garden">Main Garden</option>
										<option value="A/C Garden">A/C Garden</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
                                    <label for="main_garden_id">Main Garden</label>
									<select required class="form-control" id="main_garden_id" name="main_garden_id">
										<option value="0">Select Garden</option>
										<?php 
										foreach($garden_data as $garden_data_list){
										print "<option value=\"$garden_data_list[id]\">$garden_data_list[garden_name] </option>";
										}
										?>
									</select>	
                                </div>
								<div class="form-group col-sm-4">
									<label for="seller_brokers">Seller Brokers</label>
									<select required class="form-control" name="seller_brokers" id="seller_brokers">
										<option value="">Select</option>
										<option value="UBL">UBL</option>
										<option value="Others">Others</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Full Address</label>
									<input type="text" class="form-control" name="garden_address1" id="garden_address1" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Short Address</label>
									<input type="text" class="form-control" name="garden_address2" id="garden_address2" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Contact No.</label>
									<input type="text" class="form-control" name="garden_contact_no" id="garden_contact_no" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Email</label>
									<input type="email" class="form-control" name="garden_email" id="garden_email" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">
										Origin 
										<span class="glyphicon glyphicon-plus text-danger" style="cursor:pointer;" data-toggle="modal" data-target="#myModal"></span>
									</label>
									<div id="load_origin_list"><select class="form-control" name="origin" id="origin">
										<option value="">Select</option>
									<?php 
										foreach($origin_data as $origin_list){
										print "<option value=\"$origin_list[id]\">$origin_list[origin_name]</option>";
									}
									?>
									</select></div>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">BIN</label>
									<input type="text" class="form-control" name="bin_no" id="bin_no" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Company Name</label>
									<select class="form-control" name="company_name" id="company_name">
										<option value="">Select</option>
									<?php 
										foreach($company_data as $company_list){
										print "<option value=\"$company_list[id]\">$company_list[company_name]</option>";
									}
									?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="Tasting To">Tasting To</label>
									<textarea class="form-control" name="report_to" id="report_to" rows="1"></textarea>
								</div>
								<div class="form-group col-sm-4">
									<label for="Tasting CC">Tasting CC</label>
									<textarea class="form-control" name="report_cc" id="report_cc" rows="1"></textarea>
								</div>
								<div class="form-group col-sm-4">
									<label for="Letter To">Letter To</label>
									<textarea class="form-control" name="report_letter_to" id="report_letter_to" rows="1"></textarea>
								</div>
								<div class="form-group col-sm-4">
									<label for="Letter CC">Letter CC</label>
									<textarea class="form-control" name="report_letter_cc" id="report_letter_cc" rows="1"></textarea>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-12 text-right">
									<input type="submit" value="Submit" id="Submit" class="btn btn-primary"/>
								</div>
							</div>
						</form>
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->
	</div> <!-- /row -->
</div> <!-- /container -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Garden Origin Setup</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="email">Origin Name</label>
						<input type="text" class="form-control" name="origin_name" id="origin_name" />
						<input type="hidden" name="origin_edit_id" id="origin_edit_id" />
					</div>
					<div class="form-group col-sm-6">
						<input type="button" value="Save Data" id="modal_Submit" class="btn btn-danger" style="margin-top:25px;" onclick="fn_origin_save();" />
					</div>
				</div>	
				<div class="row">
					<div class="form-group col-sm-12" id="load_origin_table">
					</div>
				</div>	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
function external_comeback() {
  alert('Let your data view details.');
  window.focus();
  $("#garden_name").focus();
}

// callback
fn_garden_origin_table();
</script>