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
					<li class="breadcrumb-item active" aria-current="page">Bill Register</li>
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
                    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Bill Register </label>
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
						<select required class="form-control input-sm" id="season" name="season" onchange="after_sale_sale_no();">
							<?php 
							foreach($season_data as $season_list){
								$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
							  	print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="email">Auction Center</label>
						<select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="after_sale_sale_no();">
							<option value="">Select</option>
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
							<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Sale No</label>
						<div id="load_sale_no">
							<select required class="form-control input-sm" id="sale_no" name="sale_no">
								<option value="">Select</option>
							</select>	
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<input type="submit" id="button" value="Bill Register" style="text-transform:uppercase;" onclick="fn_view_report(season.value,auction_center.value,sale_no.value,'Register');" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="Buyer VAT" style="text-transform:uppercase;" onclick="fn_view_report2(season.value,auction_center.value,sale_no.value,'vat');" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="Buyer AIT" style="text-transform:uppercase;" onclick="fn_view_report3(season.value,auction_center.value,sale_no.value,'ait');" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="Buyer Brokerage" style="text-transform:uppercase;" onclick="fn_view_report4(season.value,auction_center.value,sale_no.value,'brokerage');" class="btn btn-sm btn-primary" />
					</div>
					<div class="form-group col-sm-12">
						<input type="submit" id="button" value="SELLERS STMT" style="text-transform:uppercase;" title="Sellers Statement" onclick="fn_view_report6(season.value,auction_center.value,sale_no.value,'sellers');" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="Sale Buyer List" style="text-transform:uppercase;" onclick="fn_view_report7(season.value,auction_center.value,sale_no.value,'sale_buyer_list');" class="btn btn-sm btn-primary" />
						
						<input type="button" id="Submit" value="Out Lot STMT" style="text-transform:uppercase;" onclick="fn_view_report8(season.value,auction_center.value,sale_no.value,'garden_unsold');" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="Sales Summary" style="text-transform:uppercase;" onclick="fn_view_report11(season.value,auction_center.value,sale_no.value,'sales_summary');" class="btn btn-sm btn-primary" />
					</div>	
					<div class="form-group col-sm-12">
						<input type="button" id="Submit" value="Sale Released List" style="text-transform:uppercase;" onclick="fn_view_report9(season.value,auction_center.value,sale_no.value,'sales_released_list');" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="Buyer Defaulter List" style="text-transform:uppercase;" onclick="fn_view_report10(season.value,auction_center.value,sale_no.value,'buyer_defaulter_list');" class="btn btn-sm btn-primary" />

						<input type="submit" id="button" value="Tea Cess STMT" style="text-transform:uppercase;" onclick="fn_view_report12(season.value,auction_center.value,sale_no.value,'tea_cess_stmt');" class="btn btn-sm btn-primary" />
					</div>
					<div class="form-group col-sm-12">
						<input type="submit" id="button" value="Tea Board STMT" style="text-transform:uppercase;" onclick="fn_view_report14(season.value,auction_center.value,sale_no.value,'tea_board_stmt');" class="btn btn-sm btn-primary" />
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
$(function(){
	after_sale_sale_no();
});

function fn_view_report(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Register", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report2(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "VAT", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report3(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"&buyer_category=All"+"'); ?>", "AIT", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report4(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Brokerage","toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report6(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sellers Statement", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report7(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sale Buyer List", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report8(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/garden_unsold_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Out Lot Statement", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report9(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sale Released List", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=950, height=600");
}

function fn_view_report10(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/buyer_accounts_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sale Released List", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1200, height=600");
}

function fn_view_report11(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sellers Statement", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report12(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sellers Statement", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report13(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sellers Statement", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_view_report14(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Tea Board STMT", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}
</script>