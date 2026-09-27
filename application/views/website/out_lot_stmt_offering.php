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
						<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/offering_reports"); ?>">Reports</a></li>
						<li class="breadcrumb-item active" aria-current="page">Out Lot Statement</li>
					  </ol>
					</nav>
				</b> 
			</div>
		  </div>
		  
		  <!-- start row -->
		  <div class="row">
			<div class="col-sm-12">
			  <div class="well" id="form_group_element">
				<form target="blink" action="<?php echo site_url('MY_Controller/print_out_lot_stmt'); ?>" method="get">
					
					<input type="hidden" name="report_name" value="garden_unsold" />
					
					<div class="form-group">
						<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Out Lot Statement </label>
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
					<input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-sm btn-primary" />
				</form>    
			  </div>
			</div>

			<div class="col-sm-12">
			  <div class="well" id="form_group_element">
				<form target="blink" action="<?php echo site_url('MY_Controller/unprinted_invoice_stmt'); ?>" method="get">
					
					<input type="hidden" name="report_name" value="garden_unsold" />
					
					<div class="form-group">
						<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Out Lot Statement Upto </label>
					</div>
					
					<hr />
					
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="season">Season</label>
							<select required class="form-control input-sm" id="season" name="season">
								<?php 
								foreach($season_data as $season_list){
									$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
								  	print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
								}
								?>
							</select>
						</div>
						<div class="form-group col-sm-6">
							<label for="auction_center">Auction Center</label>
							<select required class="form-control input-sm" name="auction_center" id="auction_center">
								<option value="">Select</option>
								<option selected value="All">All</option>
								<option value="Sreemangal">Sreemangal</option>
								<option value="Chattogram">Chattogram</option>
							</select>
						</div>
						<div class="form-group col-sm-6">
							<label for="garden_id">Garden Name</label>
							<select required class="form-control input-sm" id="garden_id" name="garden_id" style="text-transform:uppercase;">
								<option value="All">All</option>
								<?php 
								foreach($garden_data as $garden_data_list){
								print "<option value=\"$garden_data_list[id]\">$garden_data_list[garden_name] </option>";
								}
								?>
							</select>
						</div>
						<div class="form-group col-sm-6">
							<label for="sale_no">Sale No</label>
							<input type="number" class="form-control input-sm" id="sale_no" name="sale_no" required>
						</div>
					</div>	

					<input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-sm btn-primary" />
				</form>    
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
	<!-- /row -->
</div>

<script>
$(function(){
	get_universal_sale_no();
	$("#garden_id").chosen();
});
</script>