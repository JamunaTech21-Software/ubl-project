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
					<li class="breadcrumb-item active" aria-current="page">Sales Report</li>
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
                    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Send SMS</label>
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
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
						<label for="email">Auction Center</label>
						<select required class="form-control input-sm" name="auction_center" id="auction_center">
							<option value="">Select</option>
							<option value="All Center">All Center</option>
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
							<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="comment">Sale No</label>
						<input required type="number" class="form-control input-sm" id="sale_no" name="sale_no" />	
					</div>
                    
                    <div class="form-group col-sm-4">
                        <input type="button" id="Submit" value="Garden Sales Avg." style="text-transform:uppercase; margin-top:24px;" onclick="fn_view_report(season.value,auction_center.value,sale_no.value);" class="btn btn-sm btn-primary" />
					</div>

					<div class="form-group col-sm-4">
                        <input type="button" id="Submit" value="Company Sales Avg." style="text-transform:uppercase; margin-top:24px;" onclick="fn_view_report2(season.value,auction_center.value,sale_no.value);" class="btn btn-sm btn-primary" />
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
function fn_view_report(season,auction_center,sale_no){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }

	var url = "<?php echo site_url('MY_Controller/garden_single_sales_avg_report_list'); ?>" +"?season=" + encodeURIComponent(season) +"&auction_center=" + encodeURIComponent(auction_center) +"&sale_no=" + encodeURIComponent(sale_no);

    // If you want to open the URL in a new tab, use window.open
    window.open(url, 'blank');
    return false; // Prevent default form submission behavior
}

function fn_view_report2(season,auction_center,sale_no){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }

	var url = "<?php echo site_url('MY_Controller/company_single_sales_avg_report_list'); ?>" +"?season=" + encodeURIComponent(season) +"&auction_center=" + encodeURIComponent(auction_center) +"&sale_no=" + encodeURIComponent(sale_no);

    // If you want to open the URL in a new tab, use window.open
    window.open(url, 'blank');
    return false; // Prevent default form submission behavior
}
</script>