<div class="container text-center">    
	<div class="row">

		<?php
			include("login_left_bar.php");
		?>    
    
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
			<b>
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/buyer_accounts_stmt"); ?>">Reports</a></li>
					<li class="breadcrumb-item active" aria-current="page">Buyer Accounts Statement</li>
				  </ol>
				</nav>
			</b> 
        </div>
      </div>
      
      <!-- start row -->
      <div class="row">
        <div class="col-sm-12">
			<div class="well" id="form_group_element">
            
                <div class="form-group">
                    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Buyer Accounts Statement</label>
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Date From</label>
						<input type="date" required class="form-control input-sm" id="date_from" name="date_from" value="<?php print date("Y-m-d"); ?>" />	
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Date To</label>
						<input type="date" required class="form-control input-sm" id="date_to" name="date_to" value="<?php print date("Y-m-d"); ?>" />	
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<input type="submit" id="button" value="Buyer Balance List" style="text-transform:uppercase;" onclick="fn_view_report1(date_from.value,date_to.value,'buyer_balance');" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="Buyer Receive List" style="text-transform:uppercase;" onclick="fn_view_report1(date_from.value,date_to.value,'buyer_receive');" class="btn btn-sm btn-primary" />

						<input type="submit" id="button" value="Buyer List" style="text-transform:uppercase;" onclick="fn_view_report1(date_from.value,date_to.value,'buyer_list');" class="btn btn-sm btn-primary" />
					</div>
				</div>	
			</div>
        </div>
      </div> 
      <!-- /row -->
  
    </div>
	<!-- /col -->
    <?php
      include("login_right_bar.php");
    ?>

	</div> 
	<!-- \row -->
</div>

<script>
function fn_view_report1(date_from,date_to,report_name){
	
	// validation
	if(date_from==''){ alert("Please Fill out Date From"); $("#date_from").focus(); return false; }
	if(date_to==''){ alert("Please Fill out Date To"); $("#date_to").focus(); return false; }
	
	// window open
	if(report_name=='buyer_balance'){
		
		window.open("<?php echo site_url('MY_Controller/buyer_accounts_report?date_from="+date_from+"&date_to="+date_to+"&report_name="+report_name+"'); ?>", "Buyer Accounts Statement", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1200, height=600");
		
	}else if(report_name=='buyer_receive'){
		
		window.open("../../../ubl_accounts/report_login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=buyer_ledger_details.php?date="+date_from+"&to="+date_to);
	}else if(report_name=='buyer_list'){
		
		window.open("<?php echo site_url('MY_Controller/buyer_list_print/main_buyer'); ?>", "_blink");
	}
}
</script>