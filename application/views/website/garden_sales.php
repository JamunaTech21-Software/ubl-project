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
					<li class="breadcrumb-item active" aria-current="page">Sales Report</li>
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
                    <label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Sales Report</label>
                </div>
				
				<hr />
				
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
						<select required class="form-control input-sm" id="season" name="season">
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
						<select required class="form-control input-sm" name="auction_center" id="auction_center">
							<option value="">Select</option>
							<option value="All Center">All Center</option>
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
							<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Sale From</label>
						<input required type="number" class="form-control input-sm" id="sale_from" name="sale_from" />	
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Sale To</label>
						<input required type="number" class="form-control input-sm" id="sale_to" name="sale_to" />	
					</div>
					<div style="display:none;" class="form-group col-sm-12">
						<label for="ex2">Garden Name <span title="Search garden name" style="color:white; cursor:pointer" onclick="get_garden_list()" class="btn btn-sm btn-danger glyphicon glyphicon-search"></span></label>
						<div id="load_garden_list">
							<select class="form-control input-sm" id="garden_id" name="garden_id">
							<option value="">Select Garden</option>
							</select>
						</div>	
					</div>
				</div>
				
				<div class="row">
					<div class="form-group col-sm-12">
						<input type="button" id="Submit" value="Garden Sales" style="text-transform:uppercase;" onclick="fn_view_report(season.value,auction_center.value,sale_from.value,sale_to.value,garden_id.value);" class="btn btn-sm btn-primary" />
						
						<input type="button" id="Submit" value="Garden Sales Avg." style="text-transform:uppercase;" onclick="fn_view_report2(season.value,auction_center.value,sale_from.value,sale_to.value,garden_id.value);" class="btn btn-sm btn-primary" />
						
						<input type="button" id="Submit" value="Garden Top Price" style="text-transform:uppercase;" onclick="fn_view_report6(season.value,auction_center.value,sale_from.value,sale_to.value,garden_id.value);" class="btn btn-sm btn-primary" />
					</div>	
					<div class="form-group col-sm-12">
						<input type="button" id="Submit" value="Garden W/D" style="text-transform:uppercase;" onclick="fn_view_report4(season.value,auction_center.value,sale_from.value,sale_to.value,garden_id.value);" class="btn btn-sm btn-primary" /> 
						
						<input type="submit" id="button" value="Buyer TB STMT" style="text-transform:uppercase;" title="Buyer Purchase Tea Board Statement" onclick="fn_view_report10(season.value,auction_center.value,sale_from.value,sale_to.value,'TB_S2S');" class="btn btn-sm btn-primary" /> 
						
						<input type="button" id="Submit" value="Warehouse Sales" style="text-transform:uppercase;" onclick="fn_view_report5(season.value,auction_center.value,sale_from.value,sale_to.value,'Summary');" class="btn btn-sm btn-primary" />
					</div>
					<div class="form-group col-sm-12">
						<input type="button" id="Submit" value="Garden Wise INT/EXP Report" style="text-transform:uppercase;" onclick="fn_view_report7(season.value,auction_center.value,sale_from.value,sale_to.value,'');" class="btn btn-sm btn-primary" />
						
						<input type="button" id="Submit" value="OTC wise garden avg" style="text-transform:uppercase;" onclick="fn_view_report8(season.value,auction_center.value,sale_from.value,sale_to.value,'');" class="btn btn-sm btn-primary" />
					</div>
				</div>	
				<div class="row">
					<div class="form-group col-sm-12">	
						<input type="button" id="Submit" value="Buyer purchase analysis" style="text-transform:uppercase;" onclick="fn_view_report9(season.value,auction_center.value,sale_from.value,sale_to.value,'');" class="btn btn-sm btn-primary" />
						
						<input type="button" id="Submit" value="Grade Wise Sales" style="text-transform:uppercase;" onclick="fn_view_report11(season.value,auction_center.value,sale_from.value,sale_to.value,'12');" class="btn btn-sm btn-primary" />
						
						<input type="button" id="Submit" value="Warehouse Rent" style="text-transform:uppercase;" onclick="fn_view_report12(season.value,auction_center.value,sale_from.value,sale_to.value,'Summary');" class="btn btn-sm btn-primary" />
					</div>	
				</div>

				<div class="row">
					<div class="form-group col-sm-12">	
						<input type="button" id="Submit" value="Sellers STMT" style="text-transform:uppercase;" onclick="fn_view_report15(season.value,auction_center.value,sale_from.value,sale_to.value,'sstmt_sale_to_sale');" class="btn btn-sm btn-primary" />
						
						<input type="button" id="Submit" value="Tea Cess STMT" style="text-transform:uppercase;" onclick="fn_view_report16(season.value,auction_center.value,sale_from.value,sale_to.value,'stea_cess_stmt');" class="btn btn-sm btn-primary" />

						<input type="button" id="Submit" value="Bill Register" style="text-transform:uppercase;" onclick="fn_view_report14(season.value,auction_center.value,sale_from.value,sale_to.value,'Register_sale_to_sale');" class="btn btn-sm btn-primary" />
					</div>
					<div class="form-group col-sm-12">	
						<input type="button" id="Submit" value="Seles STMT Grand Total" style="text-transform:uppercase;" onclick="fn_view_report17(season.value,auction_center.value,sale_from.value,sale_to.value,'seles_stmt_grand_total');" class="btn btn-sm btn-primary" />

						<input type="button" id="Submit" value="Fresh Offerings Vs Total Sales" style="text-transform:uppercase;" onclick="fn_view_report18(season.value,auction_center.value,sale_from.value,sale_to.value,garden_id.value);" class="btn btn-sm btn-primary" />
					</div>
				</div>
			</div>
		</div>
    </div> 
    <!-- end row -->
  
    </div>
	<!-- end col -->
    <?php
      include("login_right_bar.php");
    ?>

  </div> <!-- end row -->
</div>

<script>
function fn_view_report(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	
	if(auction_center=='All Center'){
		
		window.open("<?php echo site_url('MY_Controller/gs_report_list_all_center?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report=1'); ?>","Sales Report 14","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
		
	}else{
		
		window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report=1'); ?>","Sales Report 1","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
	}
}

function fn_view_report2(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_sales_avg_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"'); ?>","Sales Report 2","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report4(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_wd_report?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"'); ?>","Sales Report 4","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report5(season,auction_center,sale_from,sale_to,report_name){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/wh_sales_report?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>","Sales Report 5","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report6(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_sales_top_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"'); ?>","Sales Report 6","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report7(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report=4'); ?>","Sales Report 7","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report8(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report=5'); ?>","Sales Report 8","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report9(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report=6'); ?>","Sales Report 9","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report10(season,auction_center,sale_no,sale_to,report_name){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales No."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/bill_regsiter_reports?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>","Tea Board Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report11(season,auction_center,sale_from,sale_to,report){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales No."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales No."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report="+report+"'); ?>", "Grade Wise Sales Statement","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

function fn_view_report12(season,auction_center,sale_from,sale_to,report_name){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/warehouse_rent_statement?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>", "Warehouse Rent Statement","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

function fn_view_report14(season,auction_center,sale_from,sale_to,report_name){
	
	// validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	
	// view report
	window.open("<?php echo site_url('MY_Controller/bill_register_sale_to_sale?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>", "Bill Register Statement Sale To Sale","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}


function fn_view_report15(season,auction_center,sale_from,sale_to,report_name){
	
	// validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	
	// view report
	window.open("<?php echo site_url('MY_Controller/sellers_stmt_sale_to_sale?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>", "Sellers Statement Sale To Sale","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

function fn_view_report16(season,auction_center,sale_from,sale_to,report_name){
	
	// validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	
	// view report
	window.open("<?php echo site_url('MY_Controller/sellers_stmt_sale_to_sale?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>", "Sellers Statement Sale To Sale","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

function fn_view_report17(season,auction_center,sale_from,sale_to,report_name){
	
	// validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	
	// view report
	window.open("<?php echo site_url('MY_Controller/sellers_stmt_sale_to_sale?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&report_name="+report_name+"'); ?>", "Seles STMT Grand Total","toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1300, height=600");
}

function fn_view_report18(season,auction_center,sale_from,sale_to,garden_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_from==''){ alert("Please Fill out Sales From."); $("#sale_from").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales To."); $("#sale_to").focus(); return false; }
	
		window.open("<?php echo site_url('MY_Controller/garden_sales_report_list?season="+season+"&auction_center="+auction_center+"&sale_from="+sale_from+"&sale_to="+sale_to+"&garden_id="+garden_id+"&report=18'); ?>","Sales Report 1","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
</script>