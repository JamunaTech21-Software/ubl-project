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
							<li class="breadcrumb-item active" aria-current="page">Page Size Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
	  
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="all_page_setup_information" action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="setup_id" id="setup_id">
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_web_user_id; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Page Size Setup</label>
							</div>
							<hr>
							<div class="form-group">
								<label for="comment">Page Name</label>
								<select required class="form-control" name="page_name" id="page_name" onchange="page_size_return(page_name.value);">
									<option value=""> Select Page Name</option>
									<option value="Catalogue"> Catalogue </option>
									<option value="Valuation"> Valuation </option>
									<option value="Index"> Index </option>
									<option value="Cover Page"> Cover Page</option>
									<option value="After Sales Catalogue"> After Sales Catalogue </option>
								</select>
							</div>

							<div class="form-group">
								<label for="comment">Page Size</label>
								<select required class="form-control" name="page_size" id="page_size">
									<option value=""> Select page size</option>
									<option value="A4"> A4 </option>
									<option value="Legal"> Legal </option>
								</select>
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