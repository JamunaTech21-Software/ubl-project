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
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_transection"); ?>">Transaction</a></li>
							<li class="breadcrumb-item active" aria-current="page">Garden Adjustment Entry</li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element" style="font-weight:bold;">
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Garden Adjustment Entry</label> <i id="form_mode"></i>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-4">
								<label for="comment">Season</label>
								<select required class="form-control input-sm" id="season" onchange="get_universal_sale_no()">
									<?php 
									foreach($season_data as $season_list){
										print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
									}
									?>
								</select>
								<input type="hidden" readonly name="setup_id" id="setup_id">
								<input type="hidden" id="user" value="<?php print $ubl_user_name; ?>" />
								<input type="hidden" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>" />
							</div>
							<div class="form-group col-sm-4">
								<label for="ex3">Old Season</label>
								<select required class="form-control input-sm" id="old_season">
									<?php 
									foreach($season_data as $season_list){
									  print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
									}
									?>
								</select>
							</div>
							<div class="form-group col-sm-4">
								<label for="email">Auction Center</label>
								<select required class="form-control input-sm" id="auction_center" style="text-transform:uppercase;" onchange="get_universal_sale_no()">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
									<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
							  </select>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Sale No</label>
								<div id="load_sale_no">
									<select class="form-control input-sm" id="sale_no">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Garden Name</label>
								<select required class="form-control input-sm" id="garden_id" style="text-transform:uppercase;" tabindex="1" onchange="fn_g_charges_list()">
									<option value="">Select</option>
									<?php 
									foreach($garden_data as $garden_list){
										print "<option value=\"$garden_list[id]\">$garden_list[garden_name]</option>";
									}
									?>
								</select>
							</div>
							<div class="form-group col-sm-4">
								<label for="email">Charges Name</label>
								<select required class="form-control input-sm" id="charges_name" style="text-transform:uppercase;" tabindex="2">
									<option value="">Select</option>
									<option value="Garden Misc.">Garden Misc.</option>
									<option value="Truck Fare">Truck Fare</option>
									<option value="Add. W/H Rent">Add. W/H Rent</option>
									<option value="Advance">Advance</option>
									<option value="Bank charges">Bank charges</option>
							  </select>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Charges Amount</label>
								<input required type="text" class="form-control input-sm" maxlength="9" id="charge_tk" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" tabindex="3" />
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Note</label>
								<textarea required type="text" class="form-control input-sm" id="particular" tabindex="4"></textarea>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-6">
								<input type="button" id="Submit" value="Submit Data" class="btn btn-sm btn-primary" onclick="garden_adjust_entry_save();" tabindex="5" />
							</div>
						</div>	
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-12">
								<span id="load_charges_list"></span>
							</div>
						</div>							
					</div> 
					<!-- /well -->
				</div> 
				<!-- /col -->
			</div> 
			<!-- /row --> 
		</div>
		<!-- /col -->
		
		<?php
		  //include("login_right_bar.php");
		?>
		
	</div> 
	<!-- /row -->
</div> 
<!-- /container -->

<script>
$(function(){
	get_universal_sale_no();
});

function fn_g_charges_list(){
	
	// html data
	var season = $("#season").val();
	var auction_center = $("#auction_center").val();
	var sale_no = ($("#sale_no").val()=='') ? '0' : $("#sale_no").val();
	var garden_id = $("#garden_id").val();
	
	// load cart
	get_g_charges_list(season,auction_center,sale_no,garden_id)
}

// update data
function get_update_data_invoice(setup_id,season,old_season,auction_center,sale_no,garden_id,charges_name,charge_tk,particular,user,last_update) {

	$('#form_mode').html('<font color=red> - (Update)</font>');
	$('#setup_id').val(setup_id);
	$('#season').val(season);
	$('#old_season').val(old_season);
	$('#auction_center').val(auction_center);
	$('#sale_no').val(sale_no);
	$('#garden_id').val(garden_id);
	$('#charges_name').val(charges_name);
	$('#charge_tk').val(charge_tk);
	$('#particular').val(particular);
	$('#user').val(user);
	$('#last_update').val(last_update);
	$('html, body').animate({ scrollTop: $('#form_mode').offset().top }, 'slow');
}
</script>