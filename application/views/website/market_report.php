<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
		  $uri_segment = $this->uri->segment(2);
	  ?>    
    
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
			<b>
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
					<!--<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_entry"); ?>">Data Entry</a></li>-->
					<li class="breadcrumb-item active">Market Report</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well" id="form_group_element">
            <form target="blink" action="<?php echo site_url('MY_Controller/market_report_entry'); ?>" method="get">

                <div class="form-group">
				    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Market Report</label>
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
							<option value="">Select Sale No</option>
						</select>	
						</div>
					</div>
				</div>	
				<input type="button" value="Market Report View" class="btn btn-sm btn-primary" onclick="fn_view_market_report(season.value, auction_center.value, sale_no.value);">
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
});

function fn_view_market_report(season,auction_center,sale_no){
	
	// html value
	if(season==''){ alert("Please Fill Out - Season."); return false; }
	if(auction_center==''){ alert("Please Fill Out - Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill Out - Sale No."); $("#sale_no").focus(); return false; }
	
	// print window
	var url = "<?php echo site_url('MY_Controller/market_report_view'); ?>";
	url += "?season=" + encodeURIComponent(season);
	url += "&auction_center=" + encodeURIComponent(auction_center);
	url += "&sale_no=" + encodeURIComponent(sale_no);

	window.open(url);

}

</script>