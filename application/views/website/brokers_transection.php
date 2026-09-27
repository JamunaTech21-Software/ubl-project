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
					<b style="font-size:18px; text-transform:uppercase;"> <span class="glyphicon glyphicon-hand-right"></span> &nbsp; Transaction &nbsp; <span class="glyphicon glyphicon-hand-left"></span></b>
				</div>
			</div>
		</div> 
		<!-- end row -->
	  
		<!-- start row -->
		<div class="row">
			<div class="col-sm-4">
				<a href="<?php print site_url("MY_Controller/sale_entry"); ?>" style="text-decoration:none;">
					<div class="well">
						<p>Sale Entry</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Sale Entry" />
					</div>
				</a>
			</div>
			<div class="col-sm-4">
				<a href="<?php print site_url("MY_Controller/bulk_sale_entry"); ?>" style="text-decoration:none;">
					<div class="well">
						<p>Bulk Sale Entry</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Bulk Sale Entry" />
					</div>
				</a>
			</div>
			<div class="col-sm-4">
				<a href="<?php print site_url("MY_Controller/garden_adjustment"); ?>" style="text-decoration:none;" title="Garden Adjustment">
					<div class="well">
						<p>Garden Adj.</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Garden Adjustment" />
					</div>
				</a>
			</div>
		</div>
		<div class="row">	
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=buyer_receive" style="text-decoration:none;">
					<div class="well">
						<p>Buyer Receive</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Buyer Receive" />
					</div>
				</a>
			</div>
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=garden_payment" style="text-decoration:none;">
					<div class="well">
						<p>Garden Payment</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Garden Payment" />
					</div>
				</a>
			</div>
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=vat_payment" style="text-decoration:none;">
					<div class="well">
						<p>VAT Payment</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="VAT Payment" />
					</div>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=ait_payment" style="text-decoration:none;">
					<div class="well">
						<p>AIT Payment</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="AIT Payment" />
					</div>
				</a>
			</div>	
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=tea_board_payment" style="text-decoration:none;" title="Tea Board Payment">
					<div class="well">
						<p>Tea Brd. Payment</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Tea Board Payment" />
					</div>
				</a>
			</div>
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_payment" style="text-decoration:none;">
					<div class="well">
						<p>W/H Payment</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Warehouse Payment" />
					</div>
				</a>
			</div>
		</div>
		<div class="row">	
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=other_receive_voucher" style="text-decoration:none;">
					<div class="well">
						<p>Other Receive</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Other Receive" />
					</div>
				</a>
			</div>
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=other_payment_voucher" style="text-decoration:none;">
					<div class="well">
						<p>Other Payment</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Other Payment" />
				  </div>
				</a>
			</div>
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=bank_deposit" style="text-decoration:none;">
					<div class="well">
						<p>Bank Deposit</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Bank Deposit" />
					</div>
				</a>
			</div>
		</div>
		<div class="row">	
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=bank_withdraw" style="text-decoration:none;">
					<div class="well">
						<p>Bank Withdraw</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="Bank Withdraw" />
				  </div>
				</a>
			</div>
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=attendance_bulk_entry" style="text-decoration:none;">
					<div class="well">
						<p>Attendance Entry</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="HR Attendance Entry" />
				  </div>
				</a>
			</div>
			<div class="col-sm-4">
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=salary_payment" style="text-decoration:none;">
					<div class="well">
						<p>Salary Payment</p>
						<img src="<?php echo base_url().'assets/img/transaction.png'; ?>" class="img-thumbnail" height="55" width="55" title="HR Salary Payment" />
				  </div>
				</a>
			</div>
		</div>
		<!-- end row -->
    </div>

		<?php
			include("login_right_bar.php");
		?>

	</div> 
	<!-- /row -->
</div>