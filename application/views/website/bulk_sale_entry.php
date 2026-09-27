<div class="container-fluid text-center">    
	<div class="row">
<style>
label{ font-size:12px; }
.table{
	font-family:verdana;
	font-size:12px !important;
	width: 100% !important;

}
#table{
	border-color:black;
	white-space:nowrap;
}
#table tr td{
	border-color:black;
    vertical-align:middle;
}
#table tr th{
	border-color:black;
    vertical-align:middle;
}

@media print{
    .no-print, .no-print * { display: none !important; }
}

@page{
    size: auto;
}
</style>

<div class="col-sm-12">
<div class="row">
<div class="col-sm-12">
<div class="well" id="form_group_element">
<div class="row">
<div class="form-group col-sm-12">
    <b for="comment"><span class="glyphicon glyphicon-file text-primary"></span><a href="<?php echo base_url().'index.php/MY_Controller/bulk_sale_entry'; ?>"> Bulk Sale Entry </a><i id="form_mode"></i> &nbsp;&nbsp;<span id="response_msg"></span></b>
</div>
</div>
<hr>
<div class="row">
    <!-- fixed value -->
	<input readonly type="hidden" id="user" value="<?php print $ubl_user_name; ?>">
	<input readonly type="hidden" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>" />
	<!-- End fixed data -->

    <div class="form-group col-sm-2">
        <label for="comment">Season</label>
        <select required class="form-control input-sm" id="season" onchange="bulk_sale_entry_sale_no();">
            <?php 
            foreach($season_data as $season_list){
                print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group col-sm-2">
        <label for="email">Auction Center</label>
        <select required class="form-control input-sm" id="auction_center" onchange="bulk_sale_entry_sale_no();">
            <option value="">Select</option>
            <option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
            <option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
        </select>
    </div>
    <div class="form-group col-sm-2">
        <label for="comment">Sale No</label>
        <div id="load_sale_no">
            <select required class="form-control input-sm" id="sale_no">
                <option value="">Select</option>
            </select>	
        </div>
    </div>
    <div class="form-group col-sm-2">
		<label for="ex2">Catalogue</label>
		<select required class="form-control input-sm" id="catalouge_name">
            <option value="All" selected>All</option>
			<?php 
			foreach($catalogue_data as $catalogue_list){
			  print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
			}
			?>
		</select>
	</div>
	<div class="form-group col-sm-3">
        <label for="comment">Lot No.</label>
        <div class="input-group">
			<input type="number" required class="form-control input-sm text-uppercase" id="lot_no"/>
			<span class="input-group-addon">To</span>
			<input type="number" required class="form-control input-sm text-uppercase" id="lot_no_upto" onkeyup="if(event.keyCode === 13) { show_bulk_sale_entry(season.value,auction_center.value,sale_no.value,catalouge_name.value,lot_no.value,lot_no_upto.value);}"/>
		</div>
    </div>
	<div class="form-group col-sm-1">
		<button class="btn btn-success btn-sm" style="margin-top:22px;" onclick="show_bulk_sale_entry(season.value,auction_center.value,sale_no.value,catalouge_name.value,lot_no.value,lot_no_upto.value)">Search</button>
    </div>
</div>

<div class="row">
<div class="col-sm-12">
    <div id="load_bulk_sale_entry_list"></div>
</div>
</div>
		
		</div> <!-- /col-->
		</div> <!-- /col-->
		</div> <!-- /row-->
		</div> <!-- /col-->
	</div> <!-- /row -->
</div> 
<!-- /container -->
<script>
$(function(){
	// callback
	bulk_sale_entry_sale_no();
});

// chest management - 1
function fn_chest_managemant1(row_id){
	var chest_number = $("#chest_number"+row_id).val();
	var buyer1_bag = $("#buyer1_bag"+row_id).val();
	var buyer2_bag = $("#buyer2_bag"+row_id).val();
	if(parseFloat(buyer1_bag) < 0){
		alert("Invalid Chest Number.");
		$("#buyer1_bag"+row_id).val('0');
	}
	var rest_chest1 = (parseFloat(chest_number) - parseFloat(buyer1_bag));
	(rest_chest1 > 0) ? $("#buyer2_bag"+row_id).val(rest_chest1) : $("#buyer2_bag"+row_id).val('0');
}

// chest management - 2
function fn_chest_managemant2(row_id){
	var chest_number = $("#chest_number"+row_id).val();
	var buyer1_bag = $("#buyer1_bag"+row_id).val();
	var buyer2_bag = $("#buyer2_bag"+row_id).val();
	var buyer3_bag = $("#buyer3_bag"+row_id).val();
	if(parseFloat(buyer2_bag) < 0){
		alert("Invalid Chest Number.");
		$("#buyer2_bag"+row_id).val('0');
	}else if(parseFloat(buyer3_bag) < 0){
		alert("Invalid Chest Number.");
		$("#buyer3_bag"+row_id).val('0');
	}
	var rest_chest2 = (parseFloat(chest_number) - (parseFloat(buyer1_bag) + parseFloat(buyer2_bag)));
	(rest_chest2 > 0) ? $("#buyer3_bag"+row_id).val(rest_chest2) : $("#buyer3_bag"+row_id).val('0');
}

// load bulk sale entry list
function show_bulk_sale_entry(season,auction_center,sale_no,catalouge_name,lot_no,lot_no_upto){

    if(season==''){ alert("Please fill out season."); $("#season").focus(); return false; }
	if(auction_center==''){ alert("Please fill out auction center."); $("#auction_center").focus(); return false; }
	if(catalouge_name==''){ alert("Please fill out catalog."); $("#catalouge_name").focus(); return false; }
	if(sale_no==''){ alert("Please fill out sales no."); $("#sale_no").focus(); return false; }
	if(lot_no==''){ alert("Please fill out lot no."); $("#lot_no").focus(); return false; }
	if(lot_no_upto==''){ alert("Please fill out lot no. upto"); $("#lot_no_upto").focus(); return false; }

    document.getElementById('load_bulk_sale_entry_list').innerHTML = "<img src='<?php echo base_url(); ?>assets/img/loading.gif'>";
    
    // Fetch the catalogue prepared data via AJAX
    $('#load_bulk_sale_entry_list').load("<?php echo site_url('MY_Controller/bulk_sale_entry_list'); ?>" +"?season=" + encodeURIComponent(season) +"&auction_center=" + encodeURIComponent(auction_center) +"&catalouge_name=" + encodeURIComponent(catalouge_name) +"&sale_no=" + encodeURIComponent(sale_no)+"&lot_no=" + encodeURIComponent(lot_no)+"&lot_no_upto=" + encodeURIComponent(lot_no_upto), function() {
		$(".buyer_name").chosen();
	});
}


function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}

</script>