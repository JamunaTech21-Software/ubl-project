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
							<li class="breadcrumb-item active" aria-current="page">Grade Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> 
			<!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="tea_grade_details" action="#">

							<!-- fixed value -->
							<input type="hidden" name="setup_id" id="setup_id" value="">
							<input required type="hidden" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->
							
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Grade Details # </label>
								<span style="cursor:pointer;" onclick="window.open ('<?php echo site_url('MY_Controller/get_tea_grade_data'); ?>', ''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
							
							<hr />
							
							<div class="form-group">
								<label for="email">Grade Name </label>
								<input required type="text" class="form-control" name="grade_name" id="grade_name" maxlength="20" style="text-transform:uppercase;" />
							</div>
							
							<div class="form-group">
								<label for="email">Grade Category </label>
								<select required class="form-control" name="grade_category" id="grade_category">
									<option value="">Select</option>
									<option value="BROKS">BROKS</option>
									<option value="BROKS(C)">BROKS(C)</option>
									<option value="FNGS">FNGS</option>
									<option value="FNGS(C)">FNGS(C)</option>
									<option value="DM(PF)">DM(PF)</option>
									<option value="DM(PF)(C)">DM(PF)(C)</option>
									<option value="DM">DM</option>
									<option value="DM(C)">DM(C)</option>
									<option value="DUST">DUST</option>
									<option value="DUST(C)">DUST(C)</option>
									<option value="CD">CD</option>
									<option value="CD(C)">CD(C)</option>
									<option value="PD">PD</option>
									<option value="PD(C)">PD(C)</option>
									<option value="RD">RD</option>
									<option value="RD(C)">RD(C)</option>
									<option value="ORTHODOX">ORTHODOX</option>
									<option value="Green Tea">Green Tea</option>
									<option value="White Tea">White Tea</option>
								</select>
							</div>
							
							<div class="form-group">
								<label for="email">Catalogue Name </label>
								<select required class="form-control" name="catalogue_name" id="catalogue_name">
									<option value="">Select</option>
									<option value="Leaf">Leaf</option>
									<option value="Dust">Dust</option>
								</select>
							</div>

							<div class="form-group">
								<label for="email">Grade Type </label>
								<select required class="form-control" name="grade_type" id="grade_type">
									<option value="">Select</option>
									<option value="General">General</option>
									<option value="Clone">Clone</option>
								</select>
							</div>
							<input type="submit" value="Submit" id="Submit" class="btn btn-primary" />
						</form>
					</div>	
				</div>
			</div> 
			<!-- /row -->
		</div> 
		<!-- /col -->
		
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
  $("#grade_name").focus();
}
</script>