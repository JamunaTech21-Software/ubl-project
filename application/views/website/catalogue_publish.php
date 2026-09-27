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
							<li class="breadcrumb-item active" aria-current="page">Catalog Publish</li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> 
			<!-- row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="catalogue_published" action="#">
					
							<!-- fixed value -->
							<input required type="hidden" name="user" id="user" value="<?php print $ubl_web_user_id; ?>" />
							<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>" />
							<input type="hidden" value="All" name="catalouge_name" id="catalouge_name" />
							<input type="hidden" value="Cover Page" name="prepared_to" id="prepared_to" />
							<!-- End fixed data -->
				
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Catalogue Publish </label>
							</div>
					
							<hr />
					
							<div class="row">
								<div class="form-group col-sm-6">
									<label for="comment">Season</label>
									<select required class="form-control input-sm" id="season" name="season" onchange="get_cp_sale_no();">
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
									<select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="get_cp_sale_no();">
										<option value="">Select</option>
										<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
										<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								  </select>
								</div>
								<div class="form-group col-sm-6">
									<label for="comment">Sale No</label>
									<div id="load_sale_no">
									<select required class="form-control input-sm" id="sale_no" name="sale_no">
										<option value="">Select Sale No</option>
									</select>	
									</div>
								</div>
								<div class="form-group col-sm-6">
									<label for="comment">Catalogue Publish</label>
									<select required class="form-control input-sm" id="published" name="published">
										<option value="Yes">Yes</option>
										<option value="No">No</option>
									</select>	
								</div>
							</div>	
							<input type="submit" name="Submit" id="Submit" value="Submit" class="btn btn-success" />
						</form>    
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->

		<?php
		  //include("login_right_bar.php");
		?>

	</div> <!-- end row -->
</div>

<script>
$(function(){
	get_cp_sale_no();
});
</script>