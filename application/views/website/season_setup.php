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
							<li class="breadcrumb-item active" aria-current="page">Season Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="season_setup_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->
							
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Season Details # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_season_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr>
							
							<div class="form-group">
								<label for="email">Season</label>
								<input required type="text" class="form-control" name="season_name" id="season_name" placeholder="Enter Season" maxlength="9">
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

<script>
function external_comeback() {
  alert('Let your data view details.');
  window.focus();
  $("#season_name").focus();
}
</script>