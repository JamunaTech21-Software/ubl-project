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
								<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_entry"); ?>">Data Entry</a></li>
								<li class="breadcrumb-item active" aria-current="page">Tea Cup Testing</li>
							</ol>
						</nav>
					</b> 
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Tea Cup Testing </label>
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
								<label for="comment">Catalog</label>
								<select required class="form-control input-sm" id="catalouge_name" name="catalouge_name" onchange="get_cp_sale_no();">
									<!-- <option value="">Select Catalogue</option> -->
									<option value="All" selected>All</option>
									<!-- <?php 
									foreach($catalogue_data as $catalogue_list){
										print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
									}
									?> -->
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale No</label>
								<div id="load_sale_no">
									<select required class="form-control input-sm" id="sale_no" name="sale_no">
										<option value="">Select sale no</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Garden Name <span title="Search Garden Name" style="color:red; cursor:pointer" onclick="get_garden_list()" class="glyphicon glyphicon-search"></span></label>
								<div id="load_garden_list">
									<select class="form-control input-sm" id="garden_id" required>
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Prepared to do</label>
								<select required class="form-control input-sm" name="prepared_to" id="prepared_to">
									<option selected value="Tea cup testing"> Tea cup testing </option>
								</select>	
							</div>

							<div class="form-group col-sm-12">
								<input type="submit" name="Submit" id="Submit" value="Rating Entry" class="btn btn-sm btn-primary" onclick="fn_view_entry(season.value,auction_center.value,catalouge_name.value,sale_no.value,garden_id.value,prepared_to.value);"/>
								<input type="submit" name="Submit_report" id="Submit_report" value="Lot Fresh & Re-Report" class="btn btn-sm btn-primary" onclick="fn_view_report(season.value,auction_center.value,catalouge_name.value,sale_no.value,garden_id.value,'lot_fresh_and_re_print');"/>
								<input type="submit" name="Submit_report" id="Submit_report" value="Lot Fresh Report" class="btn btn-sm btn-primary" onclick="fn_view_report2(season.value,auction_center.value,catalouge_name.value,sale_no.value,garden_id.value,'lot_fresh');"/>
							</div>
							<div class="form-group col-sm-12">
								<input type="submit" name="Submit_report" id="Submit_report" value="Comment Report" class="btn btn-sm btn-primary" onclick="fn_view_report3(season.value,auction_center.value,catalouge_name.value,sale_no.value,garden_id.value,'comment_report');"/>
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
	</div> <!-- end row -->
</div>

<script>
$(function(){
	get_cp_sale_no();
});

function fn_view_entry(season,auction_center,catalouge_name,sale_no,garden_id,prepared_to){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(catalouge_name==''){ alert("Please Fill out Catalog."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); return false; }
	if(garden_id==''){ alert("Please Fill out Garden Name."); return false; }
	if(prepared_to==''){ alert("Please Fill out Prepared to do."); return false; }

	var url = "<?php echo site_url('MY_Controller/get_catalogue_prepared_data'); ?>" +"?season=" + encodeURIComponent(season) +"&auction_center=" + encodeURIComponent(auction_center) +"&catalouge_name=" + encodeURIComponent(catalouge_name) +"&sale_no=" + encodeURIComponent(sale_no) +"&garden_id=" + encodeURIComponent(garden_id) +"&prepared_to=" + encodeURIComponent(prepared_to);

    // If you want to open the URL in a new tab, use window.open
    window.open(url, 'blank');
    return false; // Prevent default form submission behavior

}

function fn_view_report(season,auction_center,catalouge_name,sale_no,garden_id,report_name){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(catalouge_name==''){ alert("Please Fill out Catalog."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); return false; }
	if(garden_id==''){ alert("Please Fill out Garden Name."); return false; }

	var url = "<?php echo site_url('MY_Controller/tea_cup_testing_report'); ?>" +"?season=" + encodeURIComponent(season) +"&auction_center=" + encodeURIComponent(auction_center) +"&catalouge_name=" + encodeURIComponent(catalouge_name) +"&sale_no=" + encodeURIComponent(sale_no) +"&garden_id=" + encodeURIComponent(garden_id)+"&report_name=" + encodeURIComponent(report_name);

    // If you want to open the URL in a new tab, use window.open
    window.open(url, '_blank');
    return false; // Prevent default form submission behavior
}

function fn_view_report2(season,auction_center,catalouge_name,sale_no,garden_id,report_name){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(catalouge_name==''){ alert("Please Fill out Catalog."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); return false; }
	if(garden_id==''){ alert("Please Fill out Garden Name."); return false; }

	var url = "<?php echo site_url('MY_Controller/tea_cup_testing_report'); ?>" +"?season=" + encodeURIComponent(season) +"&auction_center=" + encodeURIComponent(auction_center) +"&catalouge_name=" + encodeURIComponent(catalouge_name) +"&sale_no=" + encodeURIComponent(sale_no) +"&garden_id=" + encodeURIComponent(garden_id)+"&report_name=" + encodeURIComponent(report_name);

    // If you want to open the URL in a new tab, use window.open
    window.open(url, '_blank');
    return false; // Prevent default form submission behavior
}

function fn_view_report3(season,auction_center,catalouge_name,sale_no,garden_id,report_name){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(catalouge_name==''){ alert("Please Fill out Catalog."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); return false; }
	if(garden_id==''){ alert("Please Fill out Garden Name."); return false; }

	var url = "<?php echo site_url('MY_Controller/tea_cup_testing_report'); ?>" +"?season=" + encodeURIComponent(season) +"&auction_center=" + encodeURIComponent(auction_center) +"&catalouge_name=" + encodeURIComponent(catalouge_name) +"&sale_no=" + encodeURIComponent(sale_no) +"&garden_id=" + encodeURIComponent(garden_id)+"&report_name=" + encodeURIComponent(report_name);

    // If you want to open the URL in a new tab, use window.open
    window.open(url, '_blank');
    return false; // Prevent default form submission behavior
}
</script>