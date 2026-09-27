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
							<li class="breadcrumb-item active" aria-current="page">Catalog Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form id="catalogue_setup_details" action="#">

						  <!-- fixed value -->
						  <input required type="hidden" class="form-control" name="setup_id" id="setup_id">
						  <input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
						  <input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
						  <!-- End fixed data -->
    
							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Catalog Setup # </label>
								<span style="cursor:pointer;" onclick="window.open('<?php echo site_url('MY_Controller/get_catalogue_data'); ?>',''); return false;" class="label label-danger">Total Data: <b id="total_data_counter"><?php print $no_of_total_data; ?></b></span>
							</div>
	
							<hr>

							<div class="row">
								<div class="form-group col-sm-4">
								  <label for="email">Catalog Name</label>
									<select required name="catalouge_name" id="catalouge_name" class="form-control" onchange="catalogue_setup_supplement(this.value)">
										<option value="">Select Catalog</option>
										<option value="Leaf">Leaf</option>
										<option value="Dust">Dust</option>
										<option value="Supplement">Supplement</option>
									</select>
								</div>
								<div id="suppliment_group" class="form-group col-sm-4">
									<label for="email">Supplement</label>
									<select name="suppliment_tea" id="suppliment_tea" class="form-control">
										<option value="">Select Supplement</option>
										<option value="Leaf">Leaf</option>
										<option value="Dust">Dust</option>
										<option value="Green Tea">Green Tea</option>
										<option value="Orthodox">Orthodox</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="auction">Auction</label>
									<select required name="auction" id="auction" class="form-control input-sm">
										<option value="Live Auc" selected>Live Auc</option>
										<option value="Main Catalogue E-Auc">Main Catalogue E-Auc</option>
										<option value="Supple Catalogue E-Auc">Supple Catalogue E-Auc</option>
									</select>
								</div>
							</div> 
							<div class="row">
								<div class="form-group col-sm-4">
									<label for="lot_no_start">Lot No</label>
									
									<div class="input-group input-group-sm">
										<input required type="number" class="form-control input-sm" id="lot_no_start" name="lot_no_start" autocomplete="off"/>
										<span class="input-group-addon">-</span>
										<input required type="number" class="form-control input-sm" id="lot_no_end" name="lot_no_end" autocomplete="off"/>
									</div>
								</div>
								<div class="form-group col-sm-4">
									<input type="submit" value="Submit" id="Submit" class="btn btn-primary" style="margin-top:25px;">
								</div>
							</div><!-- /row -->	
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

<script>
function external_comeback() {
	alert('Let your data view details.');
	window.focus();
	$("#catalouge_name").focus();
	var catalouge_name = $("#catalouge_name").val();
	catalogue_setup_supplement(catalouge_name);
}
</script>
