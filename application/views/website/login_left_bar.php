<div class="col-sm-3 well">

	<div class="well text-center">
    <p><a href="#"><b><?php print $ubl_user_name; ?></b></a></p>

    <?php
    $file_exists = file_exists('./assets/img/'.$ubl_user_type.'/'.$ubl_web_user_id.'.jpg');

    if($file_exists==1){
    ?>
        <img src="<?php echo base_url().'assets/img/'.$ubl_user_type.'/'.$ubl_web_user_id; ?>.jpg?<?php print rand(); ?>"
             class="img-circle"
             height="65"
             width="65"
             alt="<?php print $ubl_user_type; ?> Photo"
             data-toggle="modal"
             data-target="#profile_pic_modal"
             style="cursor:pointer;" />
    <?php
    } else {
    ?>
        <img src="<?php echo base_url().'assets/img/no-image-icon.png'; ?>"
             class="img-circle"
             height="65"
             width="65"
             alt="<?php print $ubl_user_type; ?> Photo"
             data-toggle="modal"
             data-target="#profile_pic_modal"
             style="cursor:pointer;" />
    <?php
    }
    ?>

    <br><br>

   <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle"
            type="button"
            data-toggle="dropdown">
        User Options
    </button>

    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="<?php print site_url("MY_Controller/change_password_page"); ?>">
                <i class="fa fa-key"></i> Change Password
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="<?php print site_url("MY_Controller/web_user_logout"); ?>">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </li>
    </ul>
</div>
</div>
	<div class="well text-left">
		<ul>
			<?php if($ubl_user_admin=='yes'){ ?>
			<li><a href="<?php print site_url("MY_Controller/create_user"); ?>" style="text-decoration:none; font-weight:bold;">Create User</a></li>
			<?php } ?>
			<li><a href="<?php print site_url("MY_Controller/auction_schedule_report"); ?>" style="text-decoration:none;">Auction Schedule</a></li>
		</ul>
	</div>
	<div class="well text-left">
		<ul>
			<li><a href="<?php print site_url("MY_Controller/summary_of_offering"); ?>" style="text-decoration:none;">Sale Offering</a></li>
			<li>
				<a href="<?php print site_url("MY_Controller/garden_wise_offering"); ?>" style="text-decoration:none;">Garden Wise Offering</a>
			</li>
			<li>
				<a href="<?php print site_url("MY_Controller/warehouse_wise_offering"); ?>" style="text-decoration:none;">W/H Wise Offering</a>
			</li>
			<li><a href="<?php print site_url("MY_Controller/grade_offering"); ?>" style="text-decoration:none;">Grade Wise Offering</a></li>
			<li>
				<a href="<?php print site_url("MY_Controller/offering_vs_sales"); ?>" style="text-decoration:none;">Offering vs Sales Report</a>
			</li>
		</ul>	
	</div>
	<div class="well text-left">
		<ul>
			<li>
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=other_voucher_report" style="text-decoration:none;">Other Voucher Report</a>
			</li>
			<li>
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=bank_transaction_report" style="text-decoration:none;">Bank Transaction Report</a>
			</li>
			<li>
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=hr_attendance_report_bulk" style="text-decoration:none;">HR Attendance Report</a>
			</li>
			<li>
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=salary_report_bulk" style="text-decoration:none;">HR Salary Report</a>
			</li>
			<li>
				<a target="_blink" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=daily_cash_book" style="text-decoration:none;">Daily Cash Book</a>
			</li>
		</ul>	
	</div>

	<!-- Modal -->
	<div class="modal fade" id="profile_pic_modal" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Update Profile Picture</h4>
			</div>
			<div class="modal-body">
				<div class="well" id="form_group_element">
					<div class="row">
						<div class="form-group col-sm-12 col-md-6 col-lg-6">
							<div id="uploaded_image" style="color:red;">
								<?php
									if($file_exists==1){
								?>	
									<img src="<?php echo base_url().'assets/img/'.$ubl_user_type.'/'.$ubl_web_user_id; ?>.jpg?<?php print rand(); ?>" class="img-thumbnail" width="300" height="225" alt="<?php print $ubl_user_type; ?>'s Photo" />
								<?php
									}else{
								?>
									<img src="<?php echo base_url().'assets/img/'; ?>no-image-icon.png" class="img-thumbnail" width="300" height="225" alt="<?php print $ubl_user_type; ?>'s Photo" />
								<?php	
									}
								?>
							</div>
						</div>
						<form method="post" id="profile_pic_upload_form" align="center" enctype="multipart/form-data">
							<div class="form-group col-sm-12 col-md-6 col-lg-4">
								<input type="file" name="wu_image_file" id="wu_image_file" class="form-control" accept=".jpg" /> <!-- .png, .jpg, .jpeg -->
							</div>
							<div class="form-group col-sm-12 col-md-6 col-lg-2">
								<input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			  <p style="text-align:left; color:blue; font-weight:bold;">File Format: jpg | Max size: 2MB</p> 
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		</div>
	</div>
	<!-- END Modal -->
</div>