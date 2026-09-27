<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>

		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-12">
				  <div class="well">
					  <b style="font-size:18px;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; ADD NEW INFORMATION &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
				  </div>
				</div>
			</div> 
			<!-- /row -->

			<div class="row">
				<div class="col-sm-4">
				  <a href="<?php print site_url("MY_Controller/buyer_setup"); ?>" style="text-decoration:none;">
					  <div class="well">
					   <p>Buyer Setup</p>
					   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Buyer Setup">
					  </div>
				  </a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/company_setup"); ?>" style="text-decoration:none;">
					  <div class="well">
					   <p>Company Setup</p>
					   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Garden & Company">
					  </div>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/garden_and_company_setup"); ?>" style="text-decoration:none;">
					  <div class="well">
					   <p>Garden Setup</p>
					   <img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Garden & Company">
					  </div>
					</a>
				</div>
			</div>	
			<div class="row">
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/warehouse_setup"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Warehouse Setup</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Warehouse Setup">
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/grade_setup"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Grade Setup</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Grade Setup">
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/sample_draw_process"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Sample Draw</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="VAT, AIT & Sample Information">
						</div>
					</a>
				</div>
			</div>
			<div class="row">	
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/season_setup"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Season Setup</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Catalogue Publish">
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/current_season_setup"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Current Season</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Catalogue Publish">
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/sale_schedule_setup"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Sale Schedule</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Valuation Publish">
						</div>
					</a>
				</div>
			</div>
			<div class="row">
				<div style="display:none;" class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/regulatory_setup"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Regulatory Setup</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="VAT, AIT & Sample Information">
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=other_voucher_setup" style="text-decoration:none;">
						<div class="well">
							<p>Add Other A/C</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Add Other Ledger Accounts" />
					  </div>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/sale_order_setup"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Sale Order Setup</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Sale Order Setup">
						</div>
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?php print site_url("MY_Controller/catalogue_information"); ?>" style="text-decoration:none;">
						<div class="well">
							<p><b>Catalogue</b></p>
							<img src="<?php echo base_url().'assets/img/folder.png'; ?>" class="img-thumbnail" height="66" width="66" alt="Catalogue Information Setup" />
						</div>
					</a>
				</div>
			</div>	
			<div class="row">
				<div class="col-sm-4">
					<a target="_blink" href="<?php print site_url("MY_Controller/contact_person"); ?>" style="text-decoration:none;">
						<div class="well">
							<p>Add Contact</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Add Contact Number" />
					  </div>
					</a>
				</div>
				<div class="col-sm-4">
					<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=bank_setup" style="text-decoration:none;">
						<div class="well">
							<p>Add Bank A/C</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Add New Bank Accounts" />
					  </div>
					</a>
				</div>
				<div class="col-sm-4">
					<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=add_new_hr" style="text-decoration:none;">
						<div class="well">
							<p>Add HR Profile</p>
							<img src="<?php echo base_url().'assets/img/add-new-icon.png'; ?>" class="img-thumbnail" height="55" width="55" alt="Add New HR Profile" />
					  </div>
					</a>
				</div>
			</div> 
			<!-- /row -->
			
		</div> 
		<!-- /col -->

		<?php
			include("login_right_bar.php");
		?>
	</div> 
	<!-- /row -->
</div>