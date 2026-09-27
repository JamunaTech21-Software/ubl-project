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
								
								<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>
								
								<li class="breadcrumb-item active" aria-current="page">Warehouse Accounts</li>
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
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Warehouse Accounts Statement</label>
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
								<input type="submit" id="button" value="W/H Balance List" style="text-transform:uppercase;" onclick="fn_view_report4(date_from.value,date_to.value,'warehouse_balance');" class="btn btn-sm btn-primary" />
								
								<input type="submit" id="button" value="W/H VAT Payment List" style="text-transform:uppercase;" onclick="fn_view_report4(date_from.value,date_to.value,'warehouse_vat_payment');" class="btn btn-sm btn-primary" />
								
								<input type="submit" id="button" value="W/H Tax Payment List" style="text-transform:uppercase;" onclick="fn_view_report4(date_from.value,date_to.value,'warehouse_tax_payment');" class="btn btn-sm btn-primary" />
							</div>
							<div class="form-group col-sm-12">
								<input type="submit" id="button" value="W/H Rent Payment List" style="text-transform:uppercase;" onclick="fn_view_report4(date_from.value,date_to.value,'warehouse_rent_payment');" class="btn btn-sm btn-primary" />
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
	<!-- /row -->
</div>

<script>
$(function(){
	after_sale_sale_no();
});


// warehouse accounts statement
function fn_view_report4(date_from,date_to,report_name){
	
	// validation
	if(date_from==''){ alert("Please Fill out Date From"); $("#date_from").focus(); return false; }
	if(date_to==''){ alert("Please Fill out Date To"); $("#date_to").focus(); return false; }
	
	// window open
	if(report_name=='warehouse_balance'){
		
		window.open("../../../ubl_accounts/report_login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_balance_details.php?rt=whr"+"&date="+date_from+"&to="+date_to);
		
	}else if(report_name=='warehouse_vat_payment'){
		
		window.open("../../../ubl_accounts/report_login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_ledger_details.php?rt=whr"+"&date="+date_from+"&to="+date_to);
		
	}else if(report_name=='warehouse_tax_payment'){
		
		window.open("../../../ubl_accounts/report_login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_ledger_details3.php?rt=whr"+"&date="+date_from+"&to="+date_to);
		
	}else if(report_name=='warehouse_rent_payment'){
		
		window.open("../../../ubl_accounts/report_login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_ledger_details2.php?rt=whr"+"&date="+date_from+"&to="+date_to);
	}
}
</script>