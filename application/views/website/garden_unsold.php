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
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>
					<li class="breadcrumb-item active" aria-current="page">Garden Unsold Report</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well" id="form_group_element">
            <form action="<?php echo site_url('MY_Controller/garden_unsold_report'); ?>" method="get" target="_blink">
				
                <div class="form-group">
                    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Garden Unsold Report</label>
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
						<select required class="form-control input-sm" id="season" name="season">
						<?php 
						foreach($season_data as $season_list){
						  print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
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
						<label for="ex2">Garden Name <span title="Search garden name" style="color:white; cursor:pointer" onclick="get_garden_list()" class="btn btn-sm btn-danger glyphicon glyphicon-search"></span></label>
						<div id="load_garden_list">
							<select class="form-control input-sm" id="garden_id" name="garden_id">
							<option value="">Select Garden</option>
							</select>
						</div>	
					</div>
				</div>
                <input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-sm btn-success">
            </form>    
          </div>
        </div>
      </div> 
      <!-- end row -->
  
    </div>
	<!-- end col -->
    <?php
      //include("login_right_bar.php");
    ?>

  </div> <!-- end row -->
</div>