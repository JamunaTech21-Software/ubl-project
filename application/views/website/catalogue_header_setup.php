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
							<li class="breadcrumb-item active" aria-current="page">Catalog Header Information <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
	  
			<div class="row">
				<div class="col-sm-12">
				  <div class="well" id="form_group_element">
					<form id="cover_page_information" action="#">

						<!-- fixed value -->
						<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
						<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_web_user_id; ?>">
						<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
						<!-- End fixed data -->

						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Catalog Header Information</label>
							<textarea required class="form-control" rows="8" name="cover_page_text" id="cover_page_text"><?php print $catalogue_header; ?></textarea>
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