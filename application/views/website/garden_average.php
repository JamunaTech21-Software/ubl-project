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
							<li class="breadcrumb-item active" aria-current="page">Garden Average</li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element">
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Garden Average </label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="comment">Season</label>
								<select required class="form-control input-sm" id="season" onchange="after_sale_sale_no();">
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
								<select required class="form-control input-sm" id="auction_center" onchange="after_sale_sale_no();">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
									<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
								</select>
							</div>
							<div class="form-group col-sm-6">
								<label for="comment">Sale No</label>
								<div id="load_sale_no">
									<select required class="form-control input-sm" id="sale_no">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-6">
								<label for="email">Combine With</label>
								<select class="form-control input-sm" id="combine_center">
									<option value="">Select</option>
									<option value="Sreemangal">Sreemangal</option>
									<option value="Chattogram">Chattogram</option>
								</select>
							</div>
						</div>	
						<div class="row">
							<div class="form-group col-sm-12">
								<input type="button" id="Submit" value="Garden Avg. Upto" class="btn btn-sm btn-primary" onclick="fn_view_report4(season.value,auction_center.value,sale_no.value,combine_center.value);" />
								
								<input type="button" id="Submit" value="Garden Avg. Single" class="btn btn-sm btn-primary" onclick="fn_view_report5(season.value,auction_center.value,sale_no.value,combine_center.value);" />

								<input type="button" id="Submit" value="Garden Sales Summary" class="btn btn-sm btn-primary" onclick="fn_print_gs(season.value,auction_center.value,sale_no.value)" />
							</div>
							
							<div class="form-group col-sm-12">
								<input type="button" id="Submit" value="All Tea Estates" class="btn btn-sm btn-primary" onclick="fn_view_report6(season.value,auction_center.value,sale_no.value,combine_center.value);" />

								<input type="button" id="Submit" value="Garden Avg. Details" class="btn btn-sm btn-primary" onclick="fn_view_report7(season.value,auction_center.value,sale_no.value,combine_center.value);" />

								<input type="submit" id="button" value="Auction Average" style="text-transform:uppercase;" onclick="fn_view_report13(season.value,auction_center.value,sale_no.value,'sale_average');" class="btn btn-sm btn-primary" />
							</div>

							<div class="form-group col-sm-12">
								<input type="button" id="Submit" value="Grade Wise Top Price Realized" class="btn btn-sm btn-primary" onclick="fn_grade_wise_top_price_realized(season.value,auction_center.value,sale_no.value);" />

								<input type="button" id="Submit" value="Grade Wise Garden Avg" class="btn btn-sm btn-primary" onclick="fn_grade_wise_garden_avg(season.value,auction_center.value,sale_no.value);" />
							</div>
							<div class="form-group col-sm-12">
								<input type="button" id="Submit" value="All Garden Offering Vs Sales" class="btn btn-sm btn-primary" onclick="fn_print_gos(season.value,auction_center.value,sale_no.value)" />
							</div>
						</div>
					</div>
				</div>
			</div> 
		</div>
		<!-- /col -->
		
		<?php
			include("login_right_bar.php");
		?>
	</div>
</div>

<script>
$(function(){
	after_sale_sale_no();
});

// view reports
function fn_view_report4(season,auction_center,sale_no,combine_center){
	
	// html value
	if(season==''){ alert("Please Fill Out - Season."); return false; }
	if(auction_center==''){ alert("Please Fill Out - Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill Out - Sale No."); $("#sale_no").focus(); return false; }
	
	// print window
	if(combine_center=='' || auction_center==combine_center){
	
		window.open("<?php echo site_url('MY_Controller/garden_sale_account_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&combine_center="+combine_center+"&report=4'); ?>", "Garden Average Report", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
		
	}else{
		
		window.open("<?php echo site_url('MY_Controller/garden_sale_account_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&combine_center="+combine_center+"&report=5'); ?>", "Garden Average Report - Combine","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
	}
}

function fn_view_report5(season,auction_center,sale_no,combine_center){
	
	// html value
	if(season==''){ alert("Please Fill Out - Season."); return false; }
	if(auction_center==''){ alert("Please Fill Out - Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill Out - Sale No."); $("#sale_no").focus(); return false; }
	
	// print window
	window.open("<?php echo site_url('MY_Controller/garden_sale_account_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&combine_center="+combine_center+"&report=9'); ?>", "Garden Average Report - Combine","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

function fn_view_report6(season,auction_center,sale_no,combine_center){
	
	// html value
	if(season==''){ alert("Please Fill Out - Season."); return false; }
	if(auction_center==''){ alert("Please Fill Out - Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill Out - Sale No."); $("#sale_no").focus(); return false; }
	
	// print window
	window.open("<?php echo site_url('MY_Controller/garden_sale_account_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&combine_center="+combine_center+"&report=11'); ?>", "All Tea Estates Report - Combine","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

function fn_view_report7(season,auction_center,sale_no,combine_center){
	
	// html value
	if(season==''){ alert("Please Fill Out - Season."); return false; }
	if(auction_center==''){ alert("Please Fill Out - Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill Out - Sale No."); $("#sale_no").focus(); return false; }
	
	// print window
	window.open("<?php echo site_url('MY_Controller/garden_sale_account_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&combine_center="+combine_center+"&report=12'); ?>", "Garden Average Details","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

// print all garden sales
function fn_print_gs(season,auction_center,sale_no){
	window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_no+"&sale_to="+sale_no+"&report=16'); ?>");
}

// print all garden sales
function fn_print_gos(season,auction_center,sale_no){
	window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_no+"&sale_to="+sale_no+"&report=17'); ?>");
}

function fn_view_report13(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Sellers Statement", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_grade_wise_top_price_realized(season,auction_center,sale_no,report_name){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	
	// open window
	window.open("<?php echo site_url('MY_Controller/grade_wise_top_price_realized?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report_name="+report_name+"'); ?>", "Grade Wise Top Price Realized", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=100, width=1300, height=600");
}

function fn_grade_wise_garden_avg(season, auction_center, sale_no) {
    if (season == '') { 
        alert("Please Fill out Season."); 
        return false; 
    }
    if (auction_center == '') { 
        alert("Please Fill out Auction Center."); 
        return false; 
    }
    if (sale_no == '') { 
        alert("Please Fill out Sale Number."); 
        $("#sale_no").focus();  // Updated ID to match sale_no
        return false; 
    }
    
    window.open(
        "<?php echo site_url('MY_Controller/grade_wise_garden_avg?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&report=14'); ?>", 
        "Sales Report 8", 
        "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600"
    );
}
</script>