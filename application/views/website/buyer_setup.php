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
							<li class="breadcrumb-item active" aria-current="page">Buyer Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> 
			<!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="buyer_setup_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<input required type="hidden" name="password" id="password" value="<?php echo $password; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label><span class="glyphicon glyphicon-edit text-primary"></span> Buyer Setup # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_buyer_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />
							
							<div class="row">
								<div class="form-group col-sm-4">
								  <label for="email">Buyer Name</label>
								  <input required type="text" class="form-control input-sm" name="buyer_name" id="buyer_name" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Short Key Word</label>
								  <input required type="text" class="form-control input-sm" name="short_key_code" id="short_key_code" maxlength="8" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Buyer Type</label>
								  <select required name="buyer_type" id="buyer_type" class="form-control input-sm">
										<option value="">Select</option>
										<option value="Internal">Internal</option>
										<option value="Export">Export</option>
										<option value="Both">Both</option>
								  </select>
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Buyer Category</label>
								  <select required name="buyer_category" id="buyer_category" class="form-control input-sm">
										<option value="">Select</option>
										<option value="Org">Org</option>
										<option value="LTD">LTD</option>
								  </select>
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Address 1</label>
								  <input required type="text" class="form-control input-sm" name="buyer_address1" id="buyer_address1" style="text-transform:uppercase;" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Address 2</label>
								  <input type="text" class="form-control input-sm" name="buyer_address2" id="buyer_address2" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Contact No.</label>
								  <input required type="text" class="form-control input-sm" name="buyer_contact_no" id="buyer_contact_no" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">TIN No.</label>
								  <input type="text" class="form-control input-sm" name="buyer_tin_no" id="buyer_tin_no" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">BIN No.</label>
								  <input type="text" class="form-control input-sm" name="buyer_bin_no" id="buyer_bin_no" />
								</div>
								<div class="form-group col-sm-4">
								  <label for="email">Buyer Email</label>
								  <input type="email" class="form-control input-sm" name="buyer_email" id="buyer_email" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Sub Buyer ?</label>
									<select required name="sub_buyer" id="sub_buyer" class="form-control input-sm" onchange="fn_main_buyer_list(sub_buyer.value)">
										<option value="">Select</option>
										<option value="Yes">Yes</option>
										<option selected value="No">No</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Main Buyer Name</label>
									<div id="load_main_buyer">
									<?php
										// query
										$this->db->select('id, buyer_name'); 
										$this->db->from('buyer_setup');
										$this->db->where('soft_delete', 1);
										$this->db->where('main_buyer_id'); // is null
										$this->db->order_by('buyer_name ASC');
										$query = $this->db->get();
										$result = $query->result_array();

										$buyer_list = "<select required class=\"form-control input-sm\" name=\"main_buyer_id\" id=\"main_buyer_id\" style=\"text-transform:uppercase;\">
										<option value=\"\">Select</option>";
										foreach($result AS $sn_row){
											$buyer_list.= "<option value=\"$sn_row[id]\"> $sn_row[buyer_name] </option>";
										}
										$buyer_list.= "</select>";
										print $buyer_list;
									?>
									</div>	
								</div>
							</div> <!-- /row -->	
							<input type="submit" value="Submit" id="Submit" class="btn btn-sm btn-primary">
						</form>
					</div>	
				</div> <!-- /col -->
			</div> <!-- /row -->

		</div> <!-- /col -->
	</div> <!-- /row -->	
</div> <!-- /container -->		

<script>
function external_comeback() {
	alert('Let your data view details.');
	window.focus();
	$("#buyer_name").focus();
}

function fn_main_buyer_list(sub_buyer){
	if(sub_buyer=='Yes'){
		$("#load_main_buyer").html("Loading...");
		$("#load_main_buyer").load("<?php echo site_url('MY_Controller/main_buyer_list'); ?>");
	}else{
		$("#load_main_buyer").html('<select name="main_buyer_id" id="main_buyer_id" class="form-control input-sm"><option value="">Select</option></select>');
	}
}

$(function(){
	fn_main_buyer_list('No');
});
</script>