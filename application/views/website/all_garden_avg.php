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
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_entry"); ?>">Data Entry</a></li>
							<li class="breadcrumb-item active" aria-current="page">All Garden Average <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="all_garden_avg" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> All Garden Average # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_all_garden_avg_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />

							<div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="season">Season</label>
                                    <select required class="form-control input-sm" id="season" name="season" onchange="get_invoice_entry_sale_no();">
                                        <?php 
                                        foreach($season_data as $season_list){
											$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
                                        	print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="auction_center">Auction Center</label>
                                    <select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="get_invoice_entry_sale_no();">
                                        <option value="">Select</option>
                                        <option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
                                        <option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="sale_no">Sale No</label>
                                    <div id="load_sale_no">
                                        <select required class="form-control input-sm" id="sale_no" name="sale_no">
                                            <option value="">Select</option>
                                        </select>	
                                    </div>
                                </div>
                            </div>  
                            <div class="row"> 
                                <div class="form-group col-sm-4">
                                    <label for="brokers_id">Brokers</label>
                                    <select required class="form-control input-sm" id="brokers_id" name="brokers_id">
                                        <option value="">Select</option>
                                        <?php 
                                        foreach($brokers_data_info as $brokers_list){
                                        	print "<option value=\"$brokers_list[id]\">$brokers_list[brokers_name]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="category">Category</label>
                                    <select required name="category" id="category" class="form-control input-sm bgcolor">
                                        <option value="CTC" selected>CTC</option>
                                        <option value="GREEN TEA">GREEN TEA</option>
                                    </select>
                                </div>
								<div class="form-group col-sm-4">
                                    <label for="garden_id">Garden</label>
                                    <select required class="form-control input-sm" id="garden_id" name="garden_id" tabindex="3">
                                        <option value="">Select</option>
                                        <?php 
                                        foreach($garden_data as $garden_list){
                                        	print "<option value=\"$garden_list[id]\">$garden_list[garden_name]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>  
                            <div class="row"> 
                                <div class="form-group col-sm-4">
                                    <label for="total_kgs">Kgs</label>
                                    <input type="text" required class="form-control input-sm" maxlength="9" name="total_kgs" id="total_kgs" onkeypress="return isNumberKey(this, event)" tabindex="4"/>
                                </div>
								
								<div class="form-group col-sm-4">
                                    <label for="avg_price">Average Price</label>
                                    <input type="text" required class="form-control input-sm" maxlength="9" name="avg_price" id="avg_price" onkeypress="return isNumberKey(this, event)" tabindex="5"/>
                                </div>

                                <div class="col-sm-4">
									<input type="submit" value="Submit" id="Submit" class="btn btn-primary" tabindex="6" style="margin-top:22px;">
									<input type="button" value="View" onclick="fn_view_report()" class="btn btn-primary" style="text-transform:uppercase; margin-top:22px;" />
								</div>
							</div>	
						</form>
					</div>	
				</div> <!-- /col -->
			</div> <!-- /row -->
<!-- Add this below your form -->
<div class="row">
    <div id="garden_avg_list" class="col-sm-12"></div>
</div>
		</div> <!-- /col -->
	</div> <!-- /row -->	
</div> <!-- /container -->	  

<script>
$(function(){
	get_invoice_entry_sale_no();
    $("#garden_id").select2();
});
function external_comeback() {
	alert('Let your data view details.');
	window.focus();
	$("#avg_price").focus();
}

function fn_view_report() {
    // Get values
    var season = document.getElementById('season').value;
    var auction_center = document.getElementById('auction_center').value;
    var sale_no = document.getElementById('sale_no').value;
    var brokers_id = document.getElementById('brokers_id').value;
    
    // Validate
    if(!season){ alert("Please Fill out Season."); return; }
    if(!auction_center){ alert("Please Fill out Auction Center."); return; }
    if(!sale_no){ alert("Please Fill out Sales From."); document.getElementById('sale_no').focus(); return; }
    if(!brokers_id){ alert("Please Fill out Brokers."); document.getElementById('brokers_id').focus(); return; }
    
    // Open window
    window.open(
        "<?php echo site_url('MY_Controller/garden_full_list?season='); ?>" + season + 
        "&auction_center=" + auction_center + 
        "&sale_no=" + sale_no + 
        "&brokers_id=" + brokers_id, 
        "All Garden Avg", 
        "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600"
    );
}

// Function to load the garden average table
function loadGardenAvgTable() {
    $.ajax({
        url: "<?php echo site_url('MY_Controller/get_all_garden_avg_data'); ?>",
        type: "GET",
        success: function(response) {
            $("#garden_avg_list").html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error loading table:", error);
        }
    });
}

// Function to refresh the table after operations
function refreshTable() {
    // Reload the table
    loadGardenAvgTable();
    
    // Update the total data counter
    $.ajax({
        url: "<?php echo site_url('MY_Controller/get_total_garden_avg_count'); ?>",
        type: "GET",
        success: function(count) {
            $("#total_data_counter").text(count);
        }
    });
}

// Load table on page load
$(document).ready(function() {
    loadGardenAvgTable();
});
</script>