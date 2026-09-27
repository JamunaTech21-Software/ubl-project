<div class="container text-center">    
	<div class="row">

		<?php
		  include("login_left_bar.php");
		?>    

		<div class="col-sm-6">
		
			<div class="row">
				<div class="col-sm-12">
				  <div class="well">
					  <b style="font-size:18px;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; REPORTS &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
				  </div>
				</div>
			</div>
			
			<div class="row">
				<div style="display:none;" class="form-group col-sm-4">
					<a href="<?php print site_url("MY_Controller/buyer_delivery_order"); ?>" style="text-decoration:none;">
					<div class="well">
						<p>Buyer Delivery Order</p>
						<img src="<?php echo base_url().'assets/img/report.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Buyer Delivery Order">
					</div>
					</a>
				</div>
			</div>
			<!-- end row -->
			
		</div> 
		<!-- /col -->

		<?php
			include("login_right_bar.php");
		?>
	</div> 
</div>