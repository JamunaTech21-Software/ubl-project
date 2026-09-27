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
							<li class="breadcrumb-item active" aria-current="page">Add Contact Number <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="contact_person_setup" action="#">

							<!-- fixed value -->
							<input required type="hidden" name="setup_id" id="setup_id">
							<input required type="hidden" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Contact Details # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/contact_person_setup_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />
							
							<div class="row">
								<div class="form-group col-sm-4">
								    <label for="Contact Type">Contact Type</label>
                                    <select required class="form-control" name="contact_type" id="contact_type" onchange="fn_hide_show(contact_type.value)">
                                        <option value="" selected>Select</option>
                                        <option value="1">Company</option>
                                        <option value="2">Garden</option>
                                        <option value="3">Buyer</option>
                                        <option value="4">Warehouse</option>
                                    </select>
								</div>
								<div class="form-group col-sm-4 company_div">
								    <label for="Company Name">Company Name</label>
									<select class="form-control" id="company_id" name="company_id" style="width:100%;">
                                        <option value="0" selected>Select</option>
                                        <?php foreach($company_data As $company_list) { ?> 
                                            <option value="<?php print $company_list['id'] ;?>"><?php print $company_list['company_name'] ;?></option>
                                        <?php } ?>
                                    </select>
								</div>
                                <div class="form-group col-sm-4 garden_div">
								    <label for="Garden Name">Garden Name</label>
									<select class="form-control" id="garden_id" name="garden_id" style="width:100%;">
                                        <option value="0" selected>Select</option>
                                        <?php foreach($garden_data As $garden_list) { ?> 
                                            <option value="<?php print $garden_list['id'] ;?>"><?php print $garden_list['garden_name'] ;?></option>
                                        <?php } ?>
                                    </select>
								</div>
								<div class="form-group col-sm-4 buyer_div">
								    <label for="Buyer Name">Buyer Name</label>
									<select class="form-control" id="buyer_id" name="buyer_id" style="width:100%;">
                                        <option value="0" selected>Select</option>
                                        <?php 
										$this->db->select('id, buyer_name');
										$this->db->from('buyer_setup');
										$this->db->where('soft_delete', 1);
										$this->db->where('main_buyer_id IS NULL', null, false);
										$this->db->order_by('buyer_name', 'ASC');
										$query = $this->db->get();
										$result = $query->result_array();
										foreach($result As $buyer_list) { 
										?> 
                                            <option value="<?php print $buyer_list['id'] ;?>"><?php print $buyer_list['buyer_name'] ;?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
								 <div class="form-group col-sm-4 warehouse_div">
								    <label for="Warehouse">Warehouse</label>
									<select class="form-control" id="warehouse_id" name="warehouse_id" style="width:100%;">
                                        <option value="0" selected>Select</option>
                                        <?php foreach($warehouse_data As $warehouse_list) { ?> 
                                            <option value="<?php print $warehouse_list['id'] ;?>"><?php print $warehouse_list['warehouse_name'] ;?></option>
                                        <?php } ?>
                                    </select>
								</div>
                                <div class="form-group col-sm-4">
                                    <label for="Contact Person Name">Contact Person Name</label>
                                    <input required class="form-control" name="contact_details" id="contact_details" placeholder="Enter contact person name" />
								</div>
								<div class="form-group col-sm-4">
                                    <label for="Contact Designation">Contact Designation</label>
                                    <input required class="form-control" name="contact_designation" id="contact_designation"  placeholder="Enter contact person designation..." />
								</div>
							</div> 
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="Contact No.">Contact No.</label>
                                    <input required type="text" class="form-control" name="contact_no" id="contact_no" autocomplete="off"/>
								</div>
                                <div class="form-group col-sm-4">
                                    <input type="submit" value="Submit" id="Submit" class="btn btn-primary" style="margin-top:24px;"/>
								</div>
                            </div>
							<!-- /row -->
						</form>
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->
	</div> <!-- /row -->
</div> <!-- /container -->

<script>
$(function(){
    $("#company_id").select2({allowClear: false,width: "100%",selectOnClose: true});
    $("#garden_id").select2({allowClear: false,width: "100%",selectOnClose: true});
    $("#buyer_id").select2({allowClear: false,width: "100%",selectOnClose: true});
    $("#warehouse_id").select2({allowClear: false,width: "100%",selectOnClose: true});
    $(".company_div").hide();
    $(".garden_div").hide();
    $(".buyer_div").hide();
    $(".warehouse_div").hide();
})
function external_comeback() {
  alert('Let your data view details.');
  window.focus();
  $("#contact_no").focus();
}

function fn_hide_show(value) {
    if (value === '1') {
        $(".company_div").show();
        $(".garden_div").hide();
		$(".buyer_div").hide();
		$(".warehouse_div").hide();
    } else if (value === '2') {
        $(".company_div").hide();
        $(".garden_div").show();
		$(".buyer_div").hide();
		$(".warehouse_div").hide();
    } else if (value === '3') {
        $(".company_div").hide();
        $(".garden_div").hide();
        $(".warehouse_div").hide();
        $(".buyer_div").show();
    }else if (value === '4') {
        $(".company_div").hide();
        $(".garden_div").hide();
        $(".buyer_div").hide();
        $(".warehouse_div").show();
    } else {
        $(".company_div").hide();
        $(".garden_div").hide();
        $(".buyer_div").hide();
        $(".warehouse_div").hide();
    }
}
</script>