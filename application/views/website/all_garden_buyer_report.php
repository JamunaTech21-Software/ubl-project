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
                <li class="breadcrumb-item active" aria-current="page">Buyers Report Sale To Sale</li>
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
              <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Buyers Report Sale To Sale</label>
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
              <div class="form-group col-sm-6">
                <label for="comment">Sale From</label>
                <input required type="number" class="form-control input-sm" id="sale_from" name="sale_from" />  
              </div>
              <div class="form-group col-sm-6">
                <label for="comment">Sale To</label>
                <input required type="number" class="form-control input-sm" id="sale_to" name="sale_to" />  
              </div>
			  	<div class="form-group col-sm-6">
					<label for="buyer_id">Buyer Name</label>
					<select required class="form-control input-sm" id="buyer_id" name="buyer_id" style="text-transform:uppercase;">
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
                <input type="button" id="Submit" value="All Garden Purchase Analysis" style="text-transform:uppercase;" onclick="fn_view_report(season.value,auction_center.value,sale_from.value,sale_to.value,buyer_id.value);" class="btn btn-sm btn-primary" />
				<!-- Add this button after the existing buttons -->
			<!-- Add this button after the existing buttons -->

   				 <input type="button" id="Submit" value="All Garden Grade Analysis" style="text-transform:uppercase;" onclick="fn_grade_sales_analysis(season.value,auction_center.value,sale_from.value,sale_to.value,buyer_id.value);" class="btn btn-sm btn-primary" />
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
    
  </div> <!-- closing row -->
</div> <!-- closing container -->

<script>
$(function(){
	$("#buyer_id").chosen();
});

function fn_view_report(season, auction_center, sale_from, sale_to, buyer_id){
    if(season == ''){ alert("Please Fill out Season."); return false; }
    if(auction_center == ''){ alert("Please Fill out Auction Center."); return false; }
    if(sale_from == ''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
    if(sale_to == ''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
    if(buyer_id == ''){ alert("Please Select a Buyer."); $("#buyer_id").focus(); return false; }
    
    window.open("<?php echo site_url('MY_Controller/all_garden_buyer_sale_to_sale_report?season='); ?>" + season + "&auction_center=" + auction_center + "&sale_from=" + sale_from + "&sale_to=" + sale_to + "&buyer_id=" + buyer_id + "&report=1", "Buyers Report Sale To Sale", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}


function fn_grade_sales_analysis(season, auction_center, sale_from, sale_to, buyer_id){
    if(season == ''){ alert("Please Fill out Season."); return false; }
    if(auction_center == ''){ alert("Please Fill out Auction Center."); return false; }
    if(sale_from == ''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
    if(sale_to == ''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
    if(buyer_id == ''){ alert("Please Select a Buyer."); $("#buyer_id").focus(); return false; }
    
    window.open("<?php echo site_url('MY_Controller/all_garden_buyer_sale_to_sale_report?season='); ?>" + season + "&auction_center=" + auction_center + "&sale_from=" + sale_from + "&sale_to=" + sale_to + "&buyer_id=" + buyer_id + "&report=2", "Grade Wise Sales Analysis", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
</script>