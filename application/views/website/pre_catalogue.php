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
							<li class="breadcrumb-item active" aria-current="page">Invoice Verify</li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form target="blink" action="<?php echo site_url('MY_Controller/get_pre_catalogue_data'); ?>" method="get">
							
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Invoice Verify <small class="text-danger">(Unpublished Sale No.)</small></label>
							</div>
							
							<hr />
							
							<div class="row">
								<div class="form-group col-sm-4">
									<label for="season">Season</label>
									<select required class="form-control input-sm" id="season" name="season" onchange="pre_catalogue_sale_no();">
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
									<select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="pre_catalogue_sale_no();">
										<option value="">Select</option>
										<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
										<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								  </select>
								</div>
								<div class="form-group col-sm-4">
									<label for="sale_no">Sale No</label>
									<div id="load_sale_no">
										<select required class="form-control input-sm" id="sale_no" name="sale_no">
											<option value="">Select Sale No</option>
										</select>	
									</div>
								</div>
							</div> 
							<div class="row">
								<div class="form-group col-sm-4">
									<label for="garden_id">Garden</label>
									<select class="form-control input-sm" id="garden_id" name="garden_id">
										<option value="" Selected>Select</option>
										<?php 
										foreach($garden_data as $garden_list){
											print "<option value=\"$garden_list[id]\">$garden_list[garden_name]</option>";
										}
										?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="catalogue">Cataloge</label>
									<select class="form-control input-sm" id="catalogue" name="catalogue">
										<option value="" Selected>Full Cataloge</option>
										<?php 
										foreach($catalogue_data as $catalogue_list){
										print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
										}
										?>
									</select>	
								</div>
								<div class="form-group col-sm-4">
									<label for="supplement_tea">Suppliment Catalogue</label>
									<select class="form-control input-sm" id="supplement_tea" name="supplement_tea">
										<option value="" Selected>Select Suppliment</option>
										<?php 
										foreach($s_catalogue_data as $s_catalogue_list){
											print "<option class=\"$s_catalogue_list[suppliment_tea]\" value=\"$s_catalogue_list[suppliment_tea]\">$s_catalogue_list[suppliment_tea]</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-4">
									<label for="ex3">Old Season</label>
									<select class="form-control input-sm bgcolor" id="old_season" name="old_season">
										<option value="" Selected>Select</option>
										<?php 
										foreach($season_data as $season_list){
											print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
										}
										?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="ex1">Withdraw</label>
									<select class="form-control input-sm" id="withdraw" name="withdraw">
										<option value="" Selected>Select</option>
										<option value="W/D">Withdraw</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-success" style="margin-top:20px;"/>
								</div>
							</div>
							<!-- /row --> 	
						</form>    
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row --> 
		</div><!-- /col -->
		<?php
		  //include("login_right_bar.php");
		?>
	</div> 
	<!-- /row -->
</div> 
<!-- /container -->

<script>
$(function(){
	pre_catalogue_sale_no();
	$("#garden_id").chosen();
});
</script>