<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>    
    
    <div class="col-sm-6">
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="well">
              <b style="font-size:18px;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; DATA ENTRY &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
          </div>
        </div>
      </div> 
      <!-- end row -->
	  
	  <!-- start row -->
      <div class="row">
	  	<div class="form-group col-sm-4" style="display:none;">
          <a href="<?php print site_url("MY_Controller/pre_invoice_entry"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Pre Invoice Entry</p>
			   <img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Invoice Entry">
			  </div>
		  </a>
        </div>
		<div class="form-group col-sm-4" style="display:none;">
		  <a href="<?php print site_url("MY_Controller/pre_invoice_verify_entry"); ?>" style="text-decoration:none;">
			  <div class="well">
				<p>Invoice Parking</p>
				<img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Invoice Verify">
			  </div>
		  </a>
		</div>
        <div class="form-group col-sm-4">
          <a href="<?php print site_url("MY_Controller/invoice_entry"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Invoice Entry</p>
			   <img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Invoice Entry">
			  </div>
		  </a>
        </div>
		<div class="form-group col-sm-4">
		  <a href="<?php print site_url("MY_Controller/pre_catalogue"); ?>" style="text-decoration:none;">
			  <div class="well">
				<p>Invoice Verify</p>
				<img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Invoice Verify">
			  </div>
		  </a>
		</div>
        <div class="form-group col-sm-4">
          <a href="<?php print site_url("MY_Controller/valuation_entry"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Valuation Entry</p>
			   <img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Valuation Entry">
			  </div>
		  </a>
        </div>
    </div>
	<div class="row">
		<div class="form-group col-sm-4" style="display:none;">
          <a href="<?php print site_url("MY_Controller/catalogue_lock"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Catalog Page Lock</p>
			   <img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Catalog Lock">
			  </div>
		  </a>
        </div>
		<div class="form-group col-sm-4">
          <a href="<?php print site_url("MY_Controller/tea_cup_testing"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Tea Cup Testing</p>
			   <img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Tea Cup Testing">
			  </div>
		  </a>
        </div>
        <div class="form-group col-sm-4" style="display:none;">
          <a href="<?php print site_url("MY_Controller/catalogue_publish"); ?>" style="text-decoration:none;">
			  <div class="well">
			   <p>Catalog Publish</p>
			   <img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Catalog Publish">
			  </div>
		  </a>
        </div>
		<div class="col-sm-4">
			<a target="_blink" href="<?php print site_url("MY_Controller/manage_page_number"); ?>" style="text-decoration:none;">
				<div class="well">
					<p>Page Number</p>
					<img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Page Number" />
				</div>
			</a>
		</div>
		<div class="form-group col-sm-4">
			<a href="<?php print site_url("MY_Controller/all_garden_avg"); ?>" style="text-decoration:none;">
				<div class="well">
					<p>All Garden Average</p>
					<img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="All Garden Average" />
				</div>
			</a>
        </div>
	</div>
	<div class="row">	
		<div class="form-group col-sm-4" style="display:none;">
			<a href="<?php print site_url("MY_Controller/garden_bill_process"); ?>" style="text-decoration:none;">
				<div class="well">
					<p>Garden Bill Proc.</p>
					<img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Garden Bill Process" />
				</div>
			</a>
        </div>
		<div class="form-group col-sm-4">
			<a href="<?php print site_url("MY_Controller/all_brokers_average"); ?>" style="text-decoration:none;">
				<div class="well">
					<p>All Brokers Average</p>
					<img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="All Brokers Average" />
				</div>
			</a>
        </div>
		<div class="form-group col-sm-4">
			<a href="<?php print site_url("MY_Controller/market_report"); ?>" style="text-decoration:none;">
				<div class="well">
					<p>Market Report</p>
					<img src="<?php echo base_url().'assets/img/catalogue-page.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Market Report" />
				</div>
			</a>
        </div>
      </div>
	  <!-- /row -->
	  
    </div>

    <?php
      include("login_right_bar.php");
    ?>

	</div> 
	<!-- /row -->
</div>