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
					<li class="breadcrumb-item active" aria-current="page">Buyer Delivery Order</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well" id="form_group_element">
            <form action="<?php echo site_url('MY_Controller/buyer_delivery_order_report'); ?>" method="get" target="blink">
				
                <div class="form-group">
                    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Buyer Delivery Order</label>
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
						<select required class="form-control input-sm" id="season" name="season" onchange="after_sale_sale_no();">
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
						<label for="comment">Sale No</label>
						<div id="load_sale_no">
							<select required class="form-control input-sm" id="sale_no" name="sale_no">
								<option value="">Select</option>
							</select>	
						</div>
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Buyer Name <span title="Search Buyer Name" style="color:white; cursor:pointer" onclick="fn_contract_buyer_list()" class="btn btn-sm btn-danger glyphicon glyphicon-search"></span></label>
						<div id="load_buyer_list">
							<select required class="form-control input-sm" id="buyer_id" name="buyer_id">
								<option value="">Select</option>
							</select>	
						</div>
					</div>
				</div>	

                <input type="submit" name="Submit" id="Submit" value="Search Data" class="btn btn-success">
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

<script>
$(function(){
	after_sale_sale_no();
});
</script>