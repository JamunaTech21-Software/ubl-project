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
					<li class="breadcrumb-item active" aria-current="page">Auction Catalogue List</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well" id="form_group_element">
            <form action="<?php echo site_url('MY_Controller/print_brokers_auction_table'); ?>" method="post" target="blink">
				
                <div class="form-group">
					<label for="comment"><span class="glyphicon glyphicon-file"></span> Auction Catalogue List </label>
                </div>
				<hr>
				
				<div class="form-group">
                    <label for="comment">Catalogue:</label>
					<select required class="form-control" id="catalogue" name="catalogue">
						<?php 
						foreach($catalogue_data as $catalogue_list){
                            if($catalogue_list['catalouge_name']=='Supplement'){
                                // do nothing
                            }else{
                                print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
                            }
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