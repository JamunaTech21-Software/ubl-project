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
						<li class="breadcrumb-item active" aria-current="page">Buyer Reports</li>
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
						<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Buyer Reports</label>
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
							<label for="comment">Buyer Name <span title="Search Buyer Name" style="color:red; cursor:pointer" onclick="fn_contract_buyer_list()" class="glyphicon glyphicon-search"></span></label>
							<div id="load_buyer_list">
								<select required class="form-control input-sm" id="buyer_id" name="buyer_id">
									<option value="">Select</option>
								</select>	
							</div>
						</div>
					</div>
					<div class="row">
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
						<div class="col-sm-12 form-group">
							<input type="submit" id="button" value="Buyer Contract" style="text-transform:uppercase;" 
								onclick="fn_view_report(season.value, auction_center.value, sale_no.value, buyer_id.value, warehouse_id.value);" 
								class="btn btn-sm btn-primary" />

							<input type="submit" id="button" value="ALL Buyer Contract" style="text-transform:uppercase;" onclick="fn_view_report5(season.value,auction_center.value,sale_no.value,'ALL');" class="btn btn-sm btn-primary" />
							
							<input type="submit" id="button" value="Buyer Bill" style="text-transform:uppercase;" onclick="fn_view_report2(season.value,auction_center.value,sale_no.value,buyer_id.value);" class="btn btn-sm btn-primary" />
						</div>

						<div class="col-sm-12 form-group">
							<input type="submit" id="button" value="All Buyer Bill ORG" style="text-transform:uppercase;" onclick="fn_view_report8(season.value,auction_center.value,sale_no.value,'all');" class="btn btn-sm btn-primary" />

							<input type="submit" id="button" value="All Buyer Bill LTD" style="text-transform:uppercase;" onclick="fn_view_report9(season.value,auction_center.value,sale_no.value,'all');" class="btn btn-sm btn-primary" />
							
							<input type="submit" id="button" value="Buyer Delivery" style="text-transform:uppercase;" onclick="fn_view_report3(season.value,auction_center.value,sale_no.value,buyer_id.value);" class="btn btn-sm btn-primary" />
							
						</div>
						<div class="col-sm-12 form-group">
							<input type="submit" id="button" value="Special Challan" style="text-transform:uppercase;" onclick="fn_view_report4(season.value,auction_center.value,sale_no.value,buyer_id.value);" class="btn btn-sm btn-primary" />
							
							<input type="submit" id="button" value="Export Contract" style="text-transform:uppercase;" onclick="fn_view_report6(season.value,auction_center.value,sale_no.value,buyer_id.value);" class="btn btn-sm btn-primary" />
							
							<input type="submit" id="button" value="Internal Contract" style="text-transform:uppercase;" onclick="fn_view_report7(season.value,auction_center.value,sale_no.value,buyer_id.value);" class="btn btn-sm btn-primary" />
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

	</div> 
  <!-- end row -->
</div>

<script>
$(function(){
	after_sale_sale_no();
});

function fn_view_report(season, auction_center, sale_no, buyer_id, warehouse_id) {
    var buyer_con = $('#buyer_id option:selected').attr('title');

    if (season == '') { alert("Please Fill out Season."); return false; }
    if (auction_center == '') { alert("Please Fill out Auction Center."); return false; }
    if (sale_no == '') { alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
    if (buyer_id == '') { alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }

    var url = "<?php echo site_url('MY_Controller/buyer_contract_report'); ?>";
    url += "?season=" + season;
    url += "&auction_center=" + auction_center;
    url += "&sale_no=" + sale_no;
    url += "&buyer_id=" + buyer_id;
    url += "&buyer_con=" + encodeURIComponent(buyer_con);

    // Only add warehouse_id if it's not empty
    if (warehouse_id && warehouse_id !== '') {
        url += "&warehouse_id=" + warehouse_id;
    }

    window.open(url, "Buyer Contract", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}



function fn_view_report5(season,auction_center,sale_no,buyer_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	var url = "<?php echo site_url('MY_Controller/buyer_contract_report'); ?>";
	url += "?season=" + season;
	url += "&auction_center=" + auction_center;
	url += "&sale_no=" + sale_no;
	url += "&buyer_id=" + buyer_id;

	window.open(url, "Buyer Contract", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");

}

function fn_view_report2(season,auction_center,sale_no,buyer_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(buyer_id==''){ alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/buyer_bill_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&buyer_id="+buyer_id+"'); ?>","Buyer Bill","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report8(season,auction_center,sale_no,buyer_id){
    if(season==''){ alert("Please Fill out Season."); return false; }
    if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
    if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
    
    // Modified to handle 'all' buyers case
    var buyerParam = buyer_id === 'all' ? 'all' : buyer_id;
    window.open("<?php echo site_url('MY_Controller/all_buyer_bill_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&buyer_id="+buyerParam+"'); ?>","Buyer Bill","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}
function fn_view_report9(season,auction_center,sale_no,buyer_id){
    if(season==''){ alert("Please Fill out Season."); return false; }
    if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
    if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
    
    // Modified to handle 'all' buyers case
    var buyerParam = buyer_id === 'all' ? 'all' : buyer_id;
    window.open("<?php echo site_url('MY_Controller/all_buyer_bill_report_ltd?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&buyer_id="+buyerParam+"'); ?>","Buyer Bill","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report3(season,auction_center,sale_no,buyer_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(buyer_id==''){ alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/buyer_delivery_order_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&buyer_id="+buyer_id+"&report_no=3'); ?>","Buyer Delivery","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report4(season,auction_center,sale_no,buyer_id){
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(buyer_id==''){ alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }
	window.open("<?php echo site_url('MY_Controller/buyer_delivery_order_report?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&buyer_id="+buyer_id+"&report_no=4'); ?>","Special Challan","toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");
}

function fn_view_report6(season,auction_center,sale_no,buyer_id){
	var buyer_id = $('#buyer_id').val();
	var buyer_con = $('#buyer_id option:selected').attr('title');
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(buyer_id==''){ alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }
	var url = "<?php echo site_url('MY_Controller/export_contract_report'); ?>";
	url += "?season=" + season;
	url += "&auction_center=" + auction_center;
	url += "&sale_no=" + sale_no;
	url += "&buyer_id=" + buyer_id;
	url += "&buyer_con=" + encodeURIComponent(buyer_con); // always encode URI components

	window.open(url, "Export Contract", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");

}

function fn_view_report7(season,auction_center,sale_no,buyer_id){
	var buyer_id = $('#buyer_id').val();
	var buyer_con = $('#buyer_id option:selected').attr('title');
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(buyer_id==''){ alert("Please Fill out Buyer Name."); $("#buyer_id").focus(); return false; }
	var url = "<?php echo site_url('MY_Controller/internal_contract_report'); ?>";
	url += "?season=" + season;
	url += "&auction_center=" + auction_center;
	url += "&sale_no=" + sale_no;
	url += "&buyer_id=" + buyer_id;
	url += "&buyer_con=" + encodeURIComponent(buyer_con); // always encode URI components

	window.open(url, "Internal Contract", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1300,height=600");

}
</script>