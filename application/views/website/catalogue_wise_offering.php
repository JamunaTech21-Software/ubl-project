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
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>
					<li class="breadcrumb-item active" aria-current="page">Catalogue Wise Offering</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well" id="form_group_element">
            <form action="<?php echo site_url('MY_Controller/catalogue_wise_report_list'); ?>" method="post" target="blink">
				
                <div class="form-group">
                    <label for="comment"><span class="glyphicon glyphicon-file"></span> Catalogue Wise Offering</label>
                </div>
				<hr>
				<div class="form-group">
                    <label for="comment">Season:</label>
                    <select required class="form-control" id="season" name="season" onchange="get_cp_sale_no();">
						<?php 
						foreach($season_data as $season_list){
						  print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
						}
						?>
                    </select>
                </div>
				
				<input type="hidden" value="All" name="catalouge_name" id="catalouge_name">
				<input type="hidden" value="Cover Page" name="prepared_to" id="prepared_to">
				
                <div class="form-group">
                    <label for="comment">Sale No:</label>
                    <div id="load_sale_no">
						<select required class="form-control" id="sale_no" name="sale_no">
							<option value="">Select sale no</option>
						</select>	
					</div>
                </div>
				
				<div class="form-group">
					<label for="ex2">Catalogue Name </label>
					<select required class="form-control" id="catalouge_name" name="catalouge_name">
                        <option value="">Select Catalogue</option>
						<?php 
						foreach($catalogue_data as $catalogue_list){
						  print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
						}
						?>
                     </select>
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
      include("login_right_bar.php");
    ?>

  </div> <!-- end row -->
</div>

<script>
$(function(){
	get_cp_sale_no();
});
</script>