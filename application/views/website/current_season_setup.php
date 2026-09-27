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
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_add_new"); ?>">Setup</a></li>
							<li class="breadcrumb-item active" aria-current="page">Current Season Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="current_season_setup_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->
							
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Current Season Details </label>
							</div>
							
							<hr>
							
							<div class="form-group">
								<label for="season_name">Current Season</label>
								<select required class="form-control" id="season_name" name="season_name">
                                    <option value="" selected>Select Current Season</option>
                                    <?php 
                                    foreach($season_data as $season_list){
                                        print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
                                    }
                                    ?>
                                </select>
							</div>
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary">
						</form>
					</div>	
				</div> <!-- /col -->
			</div> <!-- /row -->

		</div> <!-- /col -->
		
		<?php
			//include("login_right_bar.php");
		?>
		
	</div> 
	<!-- /row -->
</div> 
<!-- /container -->	