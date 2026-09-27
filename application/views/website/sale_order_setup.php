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
							<li class="breadcrumb-item active" aria-current="page">Sale Order Setup <b id="form_mode"></b></li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> <!-- /row -->
			
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<form action="#">

							<!-- fixed value -->
							<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
							<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
							<!-- End fixed data -->

							<div class="form-group">
								<label for="comment"><span class="glyphicon glyphicon-edit text-primary"></span> Sale order setup </label>
							</div>
	
							<hr />

							<div class="row">
								<div class="form-group col-sm-4">
									<label for="comment">Season</label>
									<select required class="form-control input-sm" id="season" name="season" onchange="get_invoice_entry_sale_no();">
										<?php 
										foreach($season_data as $season_list){
											print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
										}
										?>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<label for="email">Auction Center</label>
									<select required name="auction_center" id="auction_center" class="form-control input-sm" onchange="get_invoice_entry_sale_no();">
										<option value="">Select</option>
										<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
										<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								  </select>
								</div>
								<div class="form-group col-sm-4">
									<label for="comment">Sale No</label>
									<div id="load_sale_no">
										<select required class="form-control input-sm" id="sale_no" name="sale_no">
											<option value="">Select</option>
										</select>	
									</div>
								</div>
		
								<?php
									for($i=1; $i<=count($brokers_data); $i++){
								?>	
								<div class="form-group col-sm-4">
									<label for="brokers">Order <?php print $i; ?> <small class="text-muted" id="response_message<?php print $i; ?>"></small></label>
									<select required class="form-control input-sm" id="brokers_id<?php print $i; ?>" name="brokers_id<?php print $i; ?>" onchange="fn_sale_order_insert('<?php print $i; ?>','brokers_id<?php print $i; ?>')">
										<option value="">Select Brokers</option>
										<?php 
										foreach($brokers_data as $brokers_list){
											print "<option value=\"$brokers_list[id]\">$brokers_list[brokers_name]</option>";
										}
										?>
									</select>
								</div>
								<?php
									}
								?>
							</div> <!-- /row -->
						</form>
					</div> <!-- /well -->	
				</div> <!-- /col -->
			</div> <!-- /row -->
		</div> <!-- /col -->
	</div> <!-- /row -->
</div> 
<!-- /container -->	
  
<script>
$(function(){
	get_invoice_entry_sale_no();
});
</script>