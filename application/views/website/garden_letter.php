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
							<li class="breadcrumb-item active" aria-current="page">Garden Letter Entry <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
	  
			<div class="row">
				<div class="col-sm-12">
				  <div class="well" id="form_group_element">
					<form id="garden_letter_information" action="#">

						<!-- fixed value -->
						<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
						<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_web_user_id; ?>">
						<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
						<!-- End fixed data -->
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="season">Season</label>
                                <select required class="form-control input-sm" id="season" name="season" onchange="get_gl_entry_sale_no();">
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
                                <select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="get_gl_entry_sale_no();">
                                    <option value="">Select</option>
                                    <option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
                                    <option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="sale_no">Sale No</label>
                                <div id="load_sale_no">
                                    <select required class="form-control input-sm" id="sale_no" name="sale_no" onchange="loadGardenLetterText();">
                                        <option value="">Select</option>
                                    </select>	
                                </div>
                            </div>
                        </div>
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Market Summary </label>
							<textarea required class="form-control" rows="8" name="garden_letter_text" id="garden_letter_text"></textarea>
						</div>
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Story</label>
							<textarea required class="form-control" rows="8" name="gl_story_text" id="gl_story_text"></textarea>
						</div>
						<input type="submit" name="Submit" id="Submit" value="Submit" class="btn btn-success">
					</form>    
				  </div>
				</div>
			</div> <!-- /row -->

		</div> <!-- /col -->

		<?php
		  //include("login_right_bar.php");
		?>

	</div> <!-- /row -->
</div> <!-- /container -->

<script>
	// Add this to your JavaScript (after the existing functions)
$(document).ready(function() {
    // Load sale numbers when page loads if season and auction center are selected
    if($("#season").val() && $("#auction_center").val()) {
        get_gl_entry_sale_no();
    }
});
</script>