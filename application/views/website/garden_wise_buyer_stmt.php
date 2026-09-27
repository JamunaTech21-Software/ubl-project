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
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/garden_wise_buyer_stmt"); ?>">Reports</a></li>
							<li class="breadcrumb-item active" aria-current="page">Garden wise Buyer Statement</li>
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
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Garden wise Buyer Statement Reports</label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="comment">Season</label>
								<select required class="form-control input-sm" id="season" name="season" onchange="after_sale_sale_no();">
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
								<select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="after_sale_sale_no();">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
									<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale From</label>
								<input required type="text" class="form-control input-sm" id="sale_from" name="sale_from" />
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale To</label>
								<input required type="text" class="form-control input-sm" id="sale_to" name="sale_to" />
							</div>
							<div class="form-group col-sm-6">
                                <label for="comment">Garden Name <span title="Search Garden Name" style="color:red; cursor:pointer" onclick="get_garden_list_sale_to_sale('sale_account')" class="glyphicon glyphicon-search"></span></label>
								<div id="load_garden_list">
									<select required class="form-control input-sm" id="garden_id">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
						</div>

						<input type="submit" id="button" value="Garden wise Buyer STMT" style="text-transform:uppercase;" onclick="fn_view_report(season.value,auction_center.value,sale_from.value,sale_to.value,garden_id.value);" class="btn btn-sm btn-primary" />
					</div>
				</div>
			</div> 
			<!-- /row -->
  
		</div>
		<!-- /col -->

		<?php
		  include("login_right_bar.php");
		?>
	</div> 
	<!-- /row -->
</div>

<script>
function fn_view_report(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales No."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales No."); $("#sale_to").focus(); return false; }
	if(garden_id==''){ alert("Please Fill out Garden Name."); $("#garden_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report=8'); ?>","Garden wise Buyer Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
</script>