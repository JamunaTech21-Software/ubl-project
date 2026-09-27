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
								<li class="breadcrumb-item active" aria-current="page">All Garden Sale</li>
							</ol>
						</nav>
					</b> 
				</div>
			</div>
		  
		  <!-- start row -->
		  <div class="row">
			<div class="col-sm-12">
				<div class="well" id="form_group_element">
					<div class="form-group">
						<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> All Garden Sale </label>
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
					</div>
					<div class="row">
						<div class="form-group col-sm-12">
							<input type="button" id="Submit" value="Garden Sales Accounts" class="btn btn-sm btn-primary" onclick="fn_print_gs2(season.value,auction_center.value,sale_no.value)" />
						</div>
					</div>
				</div>
			</div>
		  </div> 
		  <!-- end row -->
  
		</div>
		<!-- end col -->
		<?php
			include("login_right_bar.php");
		?>
	</div> 
	<!-- end row -->
</div>

<script>
$(function(){
	get_universal_sale_no();
});

// print all garden sales accounts
function fn_print_gs2(season,auction_center,sale_no){
	window.open("<?php echo site_url('MY_Controller/all_garden_sales_account?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report=1'); ?>");
}
</script>