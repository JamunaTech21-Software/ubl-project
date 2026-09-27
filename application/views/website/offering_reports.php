<div class="container text-center">    
	<div class="row">

<?php
  include("login_left_bar.php");
?>    

<div class="col-sm-9">
	<div class="row">
		<div class="col-sm-12">
		  <div class="well">
			  <b style="font-size:18px;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; OFFERING REPORTS &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
		  </div>
		</div>
	</div> 
	<div class="row">
		<div style="display:none;" class="col-sm-3">
			<a href="<?php print site_url("MY_Controller/brokers_wise_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Brokers Offering</p>
			   <img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Brokers Wise Offering">
			  </div>
			</a>
		</div>
		<div class="col-sm-3">
			<a href="<?php print site_url("MY_Controller/summary_of_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Sale Offering</p>
			   <img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Offering">
			  </div>
			</a>
		</div>
		<div style="display:none;" class="col-sm-3">
		  <a href="<?php print site_url("MY_Controller/catalogue_wise_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Catalog Offering</p>
			   <img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Catalog Wise Offering">
			  </div>
		  </a>
		</div>
		<div class="col-sm-3">
		  <a href="<?php print site_url("MY_Controller/garden_wise_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Garden Offering</p>
			   <img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Garden Wise Offering">
			  </div>
		  </a>
		</div>
		<div class="col-sm-3">
		  <a href="<?php print site_url("MY_Controller/warehouse_wise_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Warehouse Offering</p>
			   <img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Warehouse Wise Offering">
			  </div>
		  </a>
		</div>	
		<div class="col-sm-3">
		  <a href="<?php print site_url("MY_Controller/grade_offering"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Grade Offering</p>
			   <img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Grade Offering">
			  </div>
		  </a>
		</div>
		<div class="col-sm-3">
		  <a href="<?php print site_url("MY_Controller/offering_vs_sales"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Offering vs Sales</p>
			   <img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Offering vs Sales">
			  </div>
		  </a>
		</div>
	</div>
</div> 
<!-- /col -->

<?php
  //include("login_right_bar.php");
?>
</div> 
</div>