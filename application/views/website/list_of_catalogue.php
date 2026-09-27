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
							<li class="breadcrumb-item active" aria-current="page">Catalogue</li>
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
						<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Catalogue </label>
					</div>
					
					<hr />
					
					<div class="row">
						<div class="form-group col-sm-6">
							<label for="comment">Season</label>
							<select required class="form-control input-sm" id="season" name="season" onchange="get_universal_sale_no();">
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
							<select required class="form-control input-sm" name="auction_center" id="auction_center" onchange="get_universal_sale_no();">
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
							<label for="comment">Cataloge</label>
							<select required class="form-control input-sm" id="catalogue" name="catalogue" onchange="fn_hide_show();">
								<option value="Full Catalogue">Full Cataloge</option>
								<?php 
								foreach($catalogue_data as $catalogue_list){
								  print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
								}
								?>
								<option value="Lot Wise Catalogue">Lot Wise Catalogue</option>
								<option value="Tasting Catalogue">Tasting Catalogue</option>
								<option value="Warehouse wise Catalogue">Warehouse wise Catalogue</option>
							</select>	
						</div>
						<div class="form-group col-sm-6 lot_div">
							<label for="lot_from">Lot From</label>
							<input type="number" class="form-control input-sm" id="lot_from" name="lot_from" />	
						</div>
						<div class="form-group col-sm-6 lot_div">
							<label for="lot_to">Lot To</label>
							<input type="number" class="form-control input-sm" id="lot_to" name="lot_to" />	
						</div>
						<div class="form-group col-sm-6 wh_div">
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
							<input type="button" id="Submit" value="Catalogue" class="btn btn-sm btn-primary" onclick="fn_print_catalogue(season.value,auction_center.value,sale_no.value,catalogue.value,lot_from.value,lot_to.value)" />
							
							<input type="submit" id="Submit2" value="After Sales Catalogue" class="btn btn-sm btn-primary"  onclick="fn_print_catalogue2(season.value,auction_center.value,sale_no.value)" />
							
							<input type="submit" id="Submit3" value="Lot Wise Buyer Settlement" class="btn btn-sm btn-primary"  onclick="fn_print_catalogue3(season.value,auction_center.value,sale_no.value,catalogue.value)" />
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
	get_universal_sale_no();
	fn_hide_show();
});

// hide show div
function fn_hide_show() {
    var catalogue = $('#catalogue').val();

    if (catalogue === 'Lot Wise Catalogue') {
        $('.lot_div').show(); // Show lot divs
        $('.wh_div').hide(); // Hide warehouse div
        $('#lot_from, #lot_to').attr('required', true);
        $('#warehouse_id').removeAttr('required'); // Remove required from warehouse dropdown
        $('#warehouse_id').val(''); // Clear selection
    } 
    else if (catalogue === 'Warehouse wise Catalogue') {
        $('.wh_div').show(); // Show warehouse div
        $('.lot_div').hide(); // Hide lot divs
        $('#warehouse_id').attr('required', true); // Make warehouse dropdown required
        $('#lot_from, #lot_to').removeAttr('required');
        $('#lot_from, #lot_to').val(''); // Clear lot inputs
    } 
    else {
        // Hide both if neither is selected
        $('.lot_div, .wh_div').hide();
        $('#lot_from, #lot_to, #warehouse_id').removeAttr('required');
        $('#lot_from, #lot_to, #warehouse_id').val(''); // Clear all
    }
}

// print catalogue
function fn_print_catalogue(season,auction_center,sale_no,catalogue,lot_from,lot_to){
    // form validation
    if(season==''){ alert("Please Fill out Season."); return false; }
    if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
    if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
    if(catalogue==''){ alert("Please Fill out Catalogue."); $("#catalogue").focus(); return false; }

    if (catalogue == 'Lot Wise Catalogue') {
        if (lot_from == '') { alert("Please Fill out Lot From"); $("#lot_from").focus(); return false; }
        if (lot_to == '') { alert("Please Fill out Lot To"); $("#lot_to").focus(); return false; }
    }
    
    if (catalogue == 'Warehouse wise Catalogue') {
        var warehouse_id = $('#warehouse_id').val();
        if (warehouse_id == '') { alert("Please select a Warehouse"); $("#warehouse_id").focus(); return false; }
    }
    
    // Build base URL
    var baseUrl = (catalogue == 'Tasting Catalogue') 
        ? "<?php echo site_url('MY_Controller/print_published_catalogue_tc'); ?>"
        : "<?php echo site_url('MY_Controller/print_published_catalogue'); ?>";
    
    var url = baseUrl + "?season=" + encodeURIComponent(season);
    url += "&auction_center=" + encodeURIComponent(auction_center);
    url += "&sale_no=" + encodeURIComponent(sale_no);
    url += "&catalogue=" + encodeURIComponent(catalogue);
    
    if (catalogue == 'Lot Wise Catalogue') {
        url += "&lot_from=" + encodeURIComponent(lot_from);
        url += "&lot_to=" + encodeURIComponent(lot_to);
    }
    
    if (catalogue == 'Warehouse wise Catalogue') {
        url += "&warehouse_id=" + encodeURIComponent(warehouse_id);
    }
    
    url += "&report_no=1";

    window.open(url);
}

// print after sales catalogue
function fn_print_catalogue2(season,auction_center,sale_no){
    // form validation
    if(season==''){ alert("Please Fill out Season."); return false; }
    if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
    if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
    
    // Get warehouse_id if Warehouse wise Catalogue is selected
    var warehouse_id = '';
    var catalogue = $('#catalogue').val();
    if (catalogue === 'Warehouse wise Catalogue') {
        warehouse_id = $('#warehouse_id').val();
        if (warehouse_id == '') { 
            alert("Please select a Warehouse"); 
            $("#warehouse_id").focus(); 
            return false; 
        }
    }
    
    // Build URL with warehouse_id if provided
    var url = "<?php echo site_url('MY_Controller/after_sale_catalogue_data?season='); ?>" + encodeURIComponent(season);
    url += "&auction_center=" + encodeURIComponent(auction_center);
    url += "&sale_no=" + encodeURIComponent(sale_no);
    url += "&report_no=1";
    
    if (warehouse_id) {
        url += "&warehouse_id=" + encodeURIComponent(warehouse_id);
    }
    
    window.open(url);
}

// print after sales catalogue
function fn_print_catalogue3(season,auction_center,sale_no,catalogue){
	
	// form validation
	if(season==''){ alert("Please Fill out Season."); return false; }
	if(auction_center==''){ alert("Please Fill out Auction Center."); return false; }
	if(sale_no==''){ alert("Please Fill out Sales No."); $("#sale_no").focus(); return false; }
	if(catalogue==''){ alert("Please Fill out Catalogue."); $("#catalogue").focus(); return false; }
	
	// print report
	window.open("<?php echo site_url('MY_Controller/after_sale_lot_data?season="+season+"&auction_center="+auction_center+"&sale_no="+sale_no+"&catalouge_name="+catalogue+"&report_no=1'); ?>");
}
</script>