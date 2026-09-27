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
							<li class="breadcrumb-item active" aria-current="page">Buyer AIT Reports</li>
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
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Buyer AIT Reports</label>
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
								<label for="comment">Buyer Name <span title="Search Buyer Name" style="color:red; cursor:pointer" onclick="load_ait_buyer_list()" class="glyphicon glyphicon-search"></span></label>
								<div id="load_buyer_list">
									<select required class="form-control input-sm" id="buyer_id" name="buyer_id">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
						</div>

						<input type="submit" id="button" value="Buyer AIT STMT" style="text-transform:uppercase;" onclick="generate_ait_report(auction_center.value, season_from.value, season_to.value, sale_from.value, sale_to.value, buyer_id.value);" class="btn btn-sm btn-primary" />
					</div>
				</div>
			</div> 
			<!-- /row -->

			<!-- start row -->
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
					
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Buyer Ait Date To Date</label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="comment">Date From</label>
								<input type="date" required class="form-control input-sm" id="date_from" name="date_from" value="<?php print date("Y-m-d"); ?>" />	
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Date To</label>
								<input type="date" required class="form-control input-sm" id="date_to" name="date_to" value="<?php print date("Y-m-d"); ?>" />	
							</div>
							<div class="form-group col-sm-6">
								<label for="buyer_id2">Buyer Name</label>
								<select required class="form-control input-sm" id="buyer_id2" name="buyer_id2" style="text-transform:uppercase;">
									<option value="">Choose</option>
									<?php 
									foreach($buyer_data as $buyer_data_list){
									print "<option value=\"$buyer_data_list[id]\">$buyer_data_list[buyer_name] </option>";
									}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-12">
								<input type="submit" id="button" value="Search Data" style="text-transform:uppercase;" onclick="generate_date_range_report(date_from.value,date_to.value, buyer_id2.value);" class="btn btn-sm btn-primary" />
							</div>
						</div>	
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
	$("#buyer_id2").chosen();
});

function generate_ait_report(auction_center, season_from, season_to, sale_from, sale_to, buyer_id){
	if(auction_center==''){ alert("Please Select Auction Center."); return false; }
	if(season_from==''){ alert("Please Select Season From."); $("#season_from").focus(); return false; }
	if(season_to==''){ alert("Please Select Season To."); $("#season_to").focus(); return false; }
	if(sale_from==''){ alert("Please Enter Sale From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Enter Sale To."); $("#sale_to").focus(); return false; }
	if(buyer_id==''){ alert("Please Select Buyer Name."); $("#buyer_id").focus(); return false; }
	
	window.open("<?php echo site_url('MY_Controller/buyer_ait_report?auction_center="+auction_center+"&season_from="+season_from+"&season_to="+season_to+"&sale_from="+sale_from+"&sale_to="+sale_to+"&buyer_id="+buyer_id+"&report_no=1'); ?>","Buyer AIT Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function generate_date_range_report(date_from,date_to,buyer_id){

	if(date_from==''){ 
		alert("Please Fill out Date From"); 
		$("#date_from").focus(); 
		return false; 
	}

	if(date_to==''){ 
		alert("Please Fill out Date To"); 
		$("#date_to").focus(); 
		return false; 
	}

	if(buyer_id==''){ 
		alert("Please Fill out Buyer Name."); 
		$("#buyer_id2").focus(); 
		return false; 
	}

	window.open("<?php echo site_url('MY_Controller/buyer_ait_report?date_from="+date_from+"&date_to="+date_to+"&buyer_id="+buyer_id+"&report_no=2'); ?>",
	"Buyer AIT Statement",
	"toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");

}
</script>