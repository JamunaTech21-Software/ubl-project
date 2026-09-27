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
						<li class="breadcrumb-item active" aria-current="page">Warehouse VAT Statement</li>
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
					<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Warehouse VAT Statement</label>
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
					<div class="form-group col-sm-6">
						<label for="comment">Warehouse Name <span title="Search Warehouse Name" style="color:red; cursor:pointer" onclick="get_warehouse_list('purchase_statement')" class="glyphicon glyphicon-search"></span></label>
						<div id="load_warehouse_list">
							<select required class="form-control input-sm" id="warehouse_id" name="warehouse_id">
							<option value="">Select Warehouse</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<input type="submit" id="button" value="VAT STMT Summary" style="text-transform:uppercase;" onclick="fn_view_report(season.value,auction_center.value,sale_no.value,warehouse_id.value);" class="btn btn-sm btn-primary" />
						
						<input type="submit" id="button" value="VAT STMT in details" style="text-transform:uppercase;" onclick="fn_view_report2(season.value,auction_center.value,sale_no.value,warehouse_id.value);" class="btn btn-sm btn-primary" />

						<input type="submit" id="button" value="Garden Wise Stmt" style="text-transform:uppercase;" onclick="fn_view_report3(season.value,auction_center.value,sale_no.value,warehouse_id.value);" class="btn btn-sm btn-primary" />
					</div>
					<div class="form-group col-sm-12">
						<input type="submit" id="button" value="VAT STMT in details All" style="text-transform:uppercase;" onclick="fn_view_report4(season.value,auction_center.value,sale_no.value,'ALL');" class="btn btn-sm btn-primary" />

						<input type="submit" id="button" value="Garden Wise Stmt All" style="text-transform:uppercase;" 
							onclick="fn_view_report5(season.value,auction_center.value,sale_no.value,'ALL');" 
							class="btn btn-sm btn-primary" />
					</div>
				</div>
			  </div>
			</div>
		  </div> 
		  <!-- /row -->
	  
		  <div class="row">
			<div class="col-sm-12">
			  <div class="well" id="form_group_element">
				<div class="form-group">
					<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Warehouse VAT Statement</label>
				</div>
				<hr />
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="comment">Season</label>
						<select required class="form-control input-sm" id="season2" name="season2">
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
						<select required class="form-control input-sm" name="auction_center2" id="auction_center2">
							<option value="">Select</option>
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
							<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
						</select>
					</div>
					<div class="form-group col-sm-6">
						<label for="Sale From">Sale From</label>
						<input required type="number" class="form-control input-sm" id="sale_no2" name="sale_no2" />	
					</div>
					<div class="form-group col-sm-6">
						<label for="Sale To">Sale To</label>
						<input required type="number" class="form-control input-sm" id="sale_to2" name="sale_to2" />	
					</div>
					<div class="form-group col-sm-6">
						<label for="comment">Warehouse Name <span title="Search Warehouse Name" style="color:red; cursor:pointer" onclick="get_warehouse_list2('purchase_statement')" class="glyphicon glyphicon-search"></span></label>
						<div id="load_warehouse_list2">
							<select required class="form-control input-sm" id="warehouse_id2" name="warehouse_id2">
							<option value="">Select Warehouse</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-12">
						<input type="submit" id="button" value="VAT STMT in details" style="text-transform:uppercase;" onclick="fn_view_report22(season2.value,auction_center2.value,sale_no2.value,sale_to2.value,warehouse_id2.value);" class="btn btn-sm btn-primary" />

						<input type="submit" id="button" value="Garden Wise Stmt" style="text-transform:uppercase;" onclick="fn_view_report33(season2.value,auction_center2.value,sale_no2.value,sale_to2.value,warehouse_id2.value);" class="btn btn-sm btn-primary" />
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
	<!-- /row -->
</div>

<script>
$(function(){
	after_sale_sale_no();
});

function fn_view_report(season,auction_center,sale_no,warehouse_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(warehouse_id==''){ alert("Please Fill out Warehouse Name."); $("#warehouse_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/wh_buyer_purchase_statement_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&warehouse_id="+warehouse_id+"&report_no=1"+"'); ?>","Warehouse VAT Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report2(season,auction_center,sale_no,warehouse_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(warehouse_id==''){ alert("Please Fill out Warehouse Name."); $("#warehouse_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/wh_buyer_purchase_statement_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&warehouse_id="+warehouse_id+"&report_no=2"+"'); ?>","Warehouse VAT Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report3(season,auction_center,sale_no,warehouse_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(warehouse_id==''){ alert("Please Fill out Warehouse Name."); $("#warehouse_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/wh_single_sales_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&w_house_id="+warehouse_id+"&report_name=wh_single_sales"+"'); ?>","Garden Wise Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report22(season,auction_center,sale_no,sale_to,warehouse_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no2").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales No."); $("#sale_no2").focus(); return false; }
	if(warehouse_id==''){ alert("Please Fill out Warehouse Name."); $("#warehouse_id2").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/wh_buyer_purchase_statement_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&sale_to="+sale_to+"&warehouse_id="+warehouse_id+"&report_no=3"+"'); ?>","Warehouse VAT Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report33(season,auction_center,sale_no,sale_to,warehouse_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no2").focus(); return false; }
	if(sale_to==''){ alert("Please Fill out Sales No."); $("#sale_no2").focus(); return false; }
	if(warehouse_id==''){ alert("Please Fill out Warehouse Name."); $("#warehouse_id2").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/wh_single_sales_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&sale_to="+sale_to+"&w_house_id="+warehouse_id+"&report_name=wh_single_sales_to_sale"+"'); ?>","Garden Wise Statement","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report4(season,auction_center,sale_no,warehouse_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	//if(warehouse_id==''){ alert("Please Fill out Warehouse Name."); $("#warehouse_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/wh_buyer_purchase_statement_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&warehouse_id="+warehouse_id+"&report_no=4"+"'); ?>","Warehouse VAT Statement ALL","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report5(season,auction_center,sale_no,warehouse_id){
    if(season==''){ alert("Please Fill out Season."); return false; }
    if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
    if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
    window.open("<?php echo site_url('MY_Controller/wh_single_sales_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&w_house_id="+warehouse_id+"&report_name=wh_multi_sales"+"'); ?>",
    "Garden Wise Statement All","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
</script>