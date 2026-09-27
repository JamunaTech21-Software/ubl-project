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
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/catalogue_information"); ?>">Catalog Setup</a></li>
							<li class="breadcrumb-item active" aria-current="page">Condition of Sale Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="sale_condition_details" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->
							
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Condition of Sale Details </label>
							</div>
							
							<hr />
							
							<div class="row">
								<div class="form-group col-sm-12">
								  <label for="email">Terms & Conditions</label>
								  <textarea required class="form-control" name="sale_condition" id="sale_condition" rows="10"><?php print $no_of_total_data; ?></textarea>
								</div>
							</div>
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary">
						</form>
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->
		
		<?php
		  //include("login_right_bar.php");
		?>
	  
	</div> <!-- /row -->
</div> <!-- /container -->