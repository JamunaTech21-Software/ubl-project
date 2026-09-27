<div class="container text-center">    
  <div class="row">

  <?php
      include("login_left_bar.php");
  ?>    
    
    <div class="col-sm-7">
      <div class="row">
        <div class="col-sm-12">
			<b>
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
					<?php
						if($ubl_user_type == 'Brokers'){
					?>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>
					<?php
						}
					?>
					
					<?php
						if($ubl_user_type == 'Buyer'){
					?>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/buyer_reports"); ?>">Reports</a></li>
					<?php
						}
					?>
					
					<?php
						if($ubl_user_type == 'Warehouse'){
					?>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/warehouses_reports"); ?>">Reports</a></li>
					<?php
						}
					?>	
					<li class="breadcrumb-item active" aria-current="page">Sale Entry</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well" id="form_group_element">
            <form action="<?php echo site_url('MY_Controller/brokers_sale_entry'); ?>" method="post" target="blink">
				
                <div class="form-group">
					<label for="comment"><span class="glyphicon glyphicon-file"></span> Sale Entry </label>
                </div>
				<hr>
				
				<div class="row">
					<div class="form-group col-sm-12">
						<label for="comment">Season:</label>
						<select required class="form-control" id="season" name="season" onchange="pre_catalogue_sale_no();">
							<?php 
							foreach($season_data as $season_list){
							  print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group col-sm-12">
						<label for="email">Auction Center</label>
						<select required class="form-control" name="auction_center" id="auction_center" onchange="pre_catalogue_sale_no();">
							<option value="">Select</option>
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
			<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
					  </select>
					</div>
					<div class="form-group col-sm-12">
						<label for="comment">Sale No:</label>
						<div id="load_sale_no">
							<select required class="form-control" id="sale_no" name="sale_no">
								<option value="">Select sale no</option>
							</select>	
						</div>
					</div>
				</div> <!-- /row --> 
                <input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-success">
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