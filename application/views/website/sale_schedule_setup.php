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
							<li class="breadcrumb-item active" aria-current="page">Sale Schedule Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="sale_schedule_setup_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Sale Schedule Setup # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_salse_schedule_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />

							<div class="row">
								<div class="form-group col-sm-4">
									<label for="season">Season</label>
									<select required class="form-control" id="season" name="season">
										<?php 
										foreach($season_data as $season_list){
										  print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
										}
										?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Sale No.</label>
									<input required type="number" class="form-control " name="sale_no" id="sale_no" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Auction Center</label>
									<select required name="auction_center" id="auction_center" class="form-control" onchange="fn_ac_house()">
										<option value="">Select</option>
										<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
										<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								  </select>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Sale Date</label>
									<input required type="date" class="form-control" name="sale_date" id="sale_date" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Sale Day</label>
									<select required name="sale_day" id="sale_day" class="form-control">
										<option value="">Select Day</option>
										<option value="Sat">Sat</option>
										<option value="Sun">Sun</option>
										<option value="Mon">Mon</option>
										<option value="Tue">Tue</option>
										<option value="Wed">Wed</option>
										<option value="Thu">Thu</option>
										<option value="Fri">Fri</option>
								  </select>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Catalouge Closing Date</label>
									<input required type="date" class="form-control" name="catalouge_closing_date" id="catalouge_closing_date" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Buyer Prompt Date</label>
									<input required type="date" class="form-control" name="buyer_prompt_date" id="buyer_prompt_date" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Position</label>
									<select name="position" id="position" class="form-control">
										<option value="">Select Position</option>
										<option value="1st">1st</option>
										<option value="2nd">2nd</option>
										<option value="3rd">3rd</option>
										<option value="4th">4th</option>
										<option value="5th">5th</option>
										<option value="6th">6th</option>
										<option value="7th">7th</option>
								  </select>
								</div>
								
								<div class="form-group col-sm-4">
									<label for="email">Comments</label>
									<input type="text" class="form-control" name="comments" id="comments" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Auction House</label>
									<input type="text" class="form-control" name="auction_house" id="auction_house" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Auction House Address</label>
									<textarea class="form-control" name="auction_address" id="auction_address"></textarea>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Auction Time</label>
									<input type="time" class="form-control" name="auction_time" id="auction_time" />
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Sample Display Center</label>
									<textarea class="form-control" name="sample_display_center" id="sample_display_center"></textarea>
								</div>
							</div>	
							
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary">
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
	$("#sale_no").focus();
}
</script>