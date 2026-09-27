<div class="container text-center">    
  <div class="row">

  <?php
      include("login_left_bar.php");
  ?>    
    
    <div class="col-sm-7">
      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-default text-left">
            <div class="panel-body">
              <p contenteditable="true">Status: Feeling Blue</p>
              <button type="button" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-thumbs-up"></span> Like
              </button>     
            </div>
          </div>
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well">
              <b style="font-size:18px;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; BUSINESS ANALYSIS REPORTS &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
          </div>
        </div>
      </div> 
      <!-- end row -->
	  
	  <!-- start row -->
      <div class="row">
		<div class="col-sm-3">
			<a href="<?php print site_url("MY_Controller/auction_schedule_report"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p style="font-size:12px;"><b>Auction <br> Schedule </b></p>
			   <img src="<?php echo base_url().'assets/img/auction.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Auction Schedule">
			  </div>
		  </a>
		</div>
		<div class="col-sm-3">
			<a href="<?php print site_url("MY_Controller/brokers_wise_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p style="font-size:12px;"><b>Brokers Wise <br> Offering </b></p>
			   <img src="<?php echo base_url().'assets/img/offering.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Brokers Wise Offering">
			  </div>
			</a>
		</div>
		<div class="col-sm-3">
			<a href="<?php print site_url("MY_Controller/summary_of_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p style="font-size:12px;"><b>Sale <br> Offering </b></p>
			   <img src="<?php echo base_url().'assets/img/offering.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Offering">
			  </div>
			</a>
		</div>
      </div>
	  <!-- end row -->
	  
	  <!-- start row -->
      <div class="row">
        <div class="col-sm-3">
          <div class="well">
           <p>Bo</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
        <div class="col-sm-3">
          <div class="well">
           <p>Jane</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
        <div class="col-sm-3">
          <div class="well">
           <p>Anja</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
		<div class="col-sm-3">
          <div class="well">
           <p>Anja</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
      </div>
	  <!-- end row -->
	  
	  <!-- start row -->
      <div class="row">
        <div class="col-sm-3">
          <div class="well">
           <p>Bo</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
        <div class="col-sm-3">
          <div class="well">
           <p>Jane</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
        <div class="col-sm-3">
          <div class="well">
           <p>Anja</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
		<div class="col-sm-3">
          <div class="well">
           <p>Anja</p>
           <img src="" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
      </div>
	  <!-- end row -->
	  
    </div>

    <?php
      include("login_right_bar.php");
    ?>

  </div> <!-- end row -->
</div>