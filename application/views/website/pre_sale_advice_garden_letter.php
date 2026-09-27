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
							<li class="breadcrumb-item active" aria-current="page">Garden Letter</li>
						</ol>
					</nav>
				</b> 
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="well" id="form_group_element">
					<form action="<?php echo site_url('MY_Controller/pre_sale_advice_garden_letter_report'); ?>" method="get" target="blink">
						
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Garden Letter</label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="comment">Season</label>
								<select required class="form-control input-sm" id="season" name="season" onchange="get_universal_sale_no();">
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
								<select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="get_universal_sale_no();">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
									<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale No</label>
								<div id="load_sale_no">
									<select required class="form-control input-sm" id="sale_no" name="sale_no">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Garden Name <span title="Search Buyer Name" style="color:red; cursor:pointer" onclick="get_garden_list('sale_account')" class="glyphicon glyphicon-search"></span></label>
								<div id="load_garden_list">
									<select class="form-control input-sm" id="garden_id" name="garden_id">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Company Name</label>
								<select class="form-control input-sm" id="company_id" name="company_id" onchange="fn_garden_prop()" style="text-transform:uppercase;">
									<option value="">Select</option>
									<?php 
									foreach($company_info as $company_list){
									  print "<option value=\"$company_list[id]\">$company_list[company_name]</option>";
									}
									?>
								</select>	
							</div>
						</div>	
						<input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-success" />
					</form>    
				</div>
			</div>
		</div> 
    </div>
	<!-- end col -->
    <?php
      include("login_right_bar.php");
    ?>

	</div>
</div>

<script>
$(function(){
	get_universal_sale_no();
});
$('#company_id').select2({
	placeholder: "Select a company",
	allowClear: true,
	width: '100%' // Ensure it fits the container
});

function fn_garden_prop(){
	$("#garden_id").prop("required",false);
}
</script>