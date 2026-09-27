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
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/buyer_ait"); ?>">Reports</a></li>
							<li class="breadcrumb-item active" aria-current="page">SELLER STATEMENT Reports</li>
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
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> SELLER STATEMENT Reports</label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="comment">Auction Center</label>
								<select required class="form-control input-sm" name="auction_center" id="auction_center">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
									<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Season From</label>
								<select required class="form-control input-sm" id="season_from" name="season_from">
									<option value="">Select Season</option>
									<?php 
									foreach($season_data as $season_list){
										print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
									}
									?>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale From</label>
								<input required type="number" class="form-control input-sm" id="sale_from" name="sale_from" placeholder="Enter Sale No" />
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Season To</label>
								<select required class="form-control input-sm" id="season_to" name="season_to">
									<option value="">Select Season</option>
									<?php 
									foreach($season_data as $season_list){
										print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
									}
									?>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale To</label>
								<input required type="number" class="form-control input-sm" id="sale_to" name="sale_to" placeholder="Enter Sale No" />
							</div>

							<div class="form-group col-sm-6">
								<label for="garden_id">Garden Name</label>
								<select required class="form-control input-sm" id="garden_id" name="garden_id" style="text-transform:uppercase;">
									<option value="">Select Garden</option>
									<?php 
									foreach($garden_data as $garden_data_list){
									print "<option value=\"$garden_data_list[id]\">$garden_data_list[garden_name] </option>";
									}
									?>
								</select>
							</div>
						</div>

						<input type="submit" id="button" value="Search WIth Garden" style="text-transform:uppercase;" onclick="garden_seller_stmt_report(auction_center.value, season_from.value, season_to.value, sale_from.value, sale_to.value,garden_id.value,'sstmt_sale_to_sale_gw2');" class="btn btn-sm btn-primary" />

						<input type="submit" id="button" value="All Garden" style="text-transform:uppercase;" onclick="generate_seller_stmt_report(auction_center.value, season_from.value, season_to.value, sale_from.value, sale_to.value,'sstmt_sale_to_sale2');" class="btn btn-sm btn-primary" />

						<input type="submit" id="button" value="Search Sales Qty" style="text-transform:uppercase;" onclick="garden_seller_stmt_report(auction_center.value, season_from.value, season_to.value, sale_from.value, sale_to.value,garden_id.value,'sstmt_sale_to_sale_sq2');" class="btn btn-sm btn-primary" />
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

<script src="<?php echo base_url(); ?>assets/js/buyer_ait.js"></script>
<script>
	$(function(){
	$("#garden_id").chosen();
});
function generate_seller_stmt_report(auction_center, season_from, season_to, sale_from, sale_to, report_name){
	if(auction_center==''){ alert("Please Select Auction Center."); return false; }
	if(season_from==''){ alert("Please Select Season From."); $("#season_from").focus(); return false; }
	if(season_to==''){ alert("Please Select Season To."); $("#season_to").focus(); return false; }
	if(sale_from==''){ alert("Please Enter Sale From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Enter Sale To."); $("#sale_to").focus(); return false; }
	
	window.open("<?php echo site_url('MY_Controller/sellers_stmt_sale_to_sale?auction_center="+auction_center+"&season_from="+season_from+"&season_to="+season_to+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>","Seller Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
function garden_seller_stmt_report(auction_center, season_from, season_to, sale_from, sale_to, garden_id, report_name){
	if(auction_center==''){ alert("Please Select Auction Center."); return false; }
	if(season_from==''){ alert("Please Select Season From."); $("#season_from").focus(); return false; }
	if(season_to==''){ alert("Please Select Season To."); $("#season_to").focus(); return false; }
	if(sale_from==''){ alert("Please Enter Sale From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Enter Sale To."); $("#sale_to").focus(); return false; }
	if(garden_id==''){ alert("Please Select Garden Name."); $("#garden_id").focus(); return false; }

	window.open("<?php echo site_url('MY_Controller/sellers_stmt_sale_to_sale?auction_center="+auction_center+"&season_from="+season_from+"&season_to="+season_to+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report_name="+report_name+"'); ?>","Garden Seller Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
</script>