<div class="container text-center">    
	<div class="row">

		<?php
			include("login_left_bar.php");
		?>    
    
		<div class="col-sm-6">
		
			<div class="row">
				<div class="col-sm-12">
					<b>
						<nav aria-label="breadcrumb">
						  <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>
							<li class="breadcrumb-item active" aria-current="page">Garden Accounts</li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Garden Sales Report </label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="comment">Season</label>
								<select required class="form-control input-sm" id="season" onchange="get_universal_sale_no();">
									<?php 
									foreach($season_data as $season_list){
										$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
									  	print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
									}
									?>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="email">Auction Center</label>
								<select required class="form-control input-sm" id="auction_center" onchange="get_universal_sale_no();">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
									<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale No</label>
								<div id="load_sale_no">
									<select required class="form-control input-sm" id="sale_no">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Garden Name <span title="Search Garden Name" style="color:red; cursor:pointer" onclick="get_garden_list('sale_account')" class="glyphicon glyphicon-search"></span></label>
								<div id="load_garden_list">
									<select required class="form-control input-sm" id="garden_id">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="company_name">Company Name</label>
								<select class="form-control" name="company_id" id="company_id">
									<option value="" selected>Select</option>
								<?php 
									foreach($company_data as $company_list){
									print "<option value=\"$company_list[id]\">$company_list[company_name]</option>";
								}
								?>
								</select>
							</div>
						</div>	
						<div class="row">
							<div class="form-group col-sm-12">
							
								<input type="submit" id="Submit" value="Sales Account B/S" class="btn btn-sm btn-primary" onclick="fn_view_report5(season.value,auction_center.value,sale_no.value,garden_id.value,company_id.value);" />
							</div>
						</div>	
					</div>
				</div>
			</div> 
			
		</div>
		<!-- /col -->
		<?php
			include("login_right_bar.php");
		?>
	</div>
</div>

<script>
$(function(){
	get_universal_sale_no();
});

// Garden Sales Accounts Befor sales
function fn_view_report5(season,auction_center,sale_no,garden_id,company_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales From."); $("#sale_no").focus(); return false; }
	//if(garden_id==''){ alert("Please Fill out Garden Name."); $("#garden_id").focus(); return false; }

	window.open("<?php echo site_url('MY_Controller/garden_sale_account_report'); ?>?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&garden_id="+garden_id+"&company_id="+company_id+"&report=10", 
		"Garden Sales Accounts",
		"toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600"
	);
}

</script>