<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>

    <div class="col-sm-9">
		<div class="row">
			<div class="col-sm-12">
			  <div class="well">
				  <b style="font-size:18px;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; ADD CATALOG INFORMATION &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
			  </div>
			</div>
		</div> <!-- /row -->

		<div class="row">
			<div class="col-sm-3">
			  <a href="<?php print site_url("MY_Controller/page_size_setup"); ?>" style="text-decoration:none;">
				  <div class="well">
				   <p>Page Size Setup</p>
				   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="VAT, AIT & Sample Information">
				  </div>
			  </a>
			</div>
			<div class="col-sm-3">
			  <a href="<?php print site_url("MY_Controller/catalogue_header_setup"); ?>" style="text-decoration:none;">
				  <div class="well">
				   <p>Catalog Header</p>
				   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="VAT, AIT & Sample Information">
				  </div>
			  </a>
			</div>
			<div class="col-sm-3">
			  <a href="<?php print site_url("MY_Controller/catalogue_setup"); ?>" style="text-decoration:none;">
				  <div class="well">
				   <p>Catalog Setup</p>
				   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Catalogue Setup">
				  </div>
			  </a>
			</div>
			<div class="col-sm-3">
			  <a href="<?php print site_url("MY_Controller/chest_setup"); ?>" style="text-decoration:none;">
				  <div class="well">
				   <p>Chest Setup</p>
				   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="VAT, AIT & Sample Information">
				  </div>
			  </a>
			</div>
			<div class="col-sm-3">
			  <a href="<?php print site_url("MY_Controller/sale_condtion_setup"); ?>" style="text-decoration:none;">
				  <div class="well">
				   <p>Condition of sales</p>
				   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="VAT, AIT & Sample Information">
				  </div>
			  </a>
			</div>
			<div class="col-sm-3">
			  <a href="<?php print site_url("MY_Controller/brokers_setup"); ?>" style="text-decoration:none;">
				  <div class="well">
				   <p>Broker Setup</p>
				   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Buyer Setup">
				  </div>
			  </a>
			</div>
		</div> 
		<!-- /row -->
    </div> 
	<!-- /col -->

    <?php
      //include("login_right_bar.php");
    ?>

  </div> 
  <!-- end row -->
</div>