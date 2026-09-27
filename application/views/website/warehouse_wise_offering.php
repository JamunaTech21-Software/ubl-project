<div class="container text-center">    
  <div class="row">

  <?php
      include("login_left_bar.php");
  ?>    
    
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
			<b>
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/offering_reports"); ?>">Reports</a></li>
					<li class="breadcrumb-item active" aria-current="page">Warehouse Wise Offering</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
		<!-- start row -->
		<div class="row">
			<div class="col-sm-12">
				<div class="well" id="form_group_element">
					<form action="<?php echo site_url('MY_Controller/warehouse_wise_report_list'); ?>" method="get" target="blink">
						
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Warehouse Wise Offering</label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="comment">Season</label>
								<select required class="form-control input-sm" id="season" name="season">
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
								<select required class="form-control input-sm" name="auction_center" id="auction_center">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
			<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale From</label>
								<input required type="number" class="form-control input-sm" id="sale_from" name="sale_from" />	
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale To</label>
								<input required type="number" class="form-control input-sm" id="sale_to" name="sale_to" />	
							</div>
							<div style="display:none;" class="form-group col-sm-12">
								<label for="ex2">Warehouse Name <span title="Search garden name" style="color:white; cursor:pointer" onclick="get_warehouse_list()" class="btn btn-sm btn-danger glyphicon glyphicon-search"></span></label>
								<div id="load_warehouse_list">
									<select class="form-control" id="warehouse_id" name="warehouse_id">
									<option value="">Select Warehouse</option>
									</select>
								</div>	
							</div>
						</div>
						<input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-sm btn-primary" />
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

  </div> <!-- end row -->
</div>