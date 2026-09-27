<div class="container text-center">    
	<div class="row">

	  <?php
		  include("login_left_bar.php");
	  ?>    
    
		<div class="col-sm-9">
			<div class="row">
				<div class="col-sm-12">
					<b>
						<nav aria-label="breadcrumb">
						  <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
							<li class="breadcrumb-item"><a href="<?php print site_url("MY_Controller/brokers_transection"); ?>">Transaction</a></li>
							<li class="breadcrumb-item active" aria-current="page">Sale Entry</li>
						  </ol>
						</nav>
					</b> 
				</div>
			</div> 
			<div class="row">
				<div class="col-sm-12">
					<div class="well" id="form_group_element" style="font-weight:bold;">
						<div class="form-group">
							<label for="comment"><span class="glyphicon glyphicon-file text-primary"></span> Sale Entry</label>
						</div>
						
						<hr />
						
						<div class="row">
							<div class="form-group col-sm-4">
								<label for="comment">Season</label>
								<select required class="form-control input-sm" id="season" onchange="get_universal_sale_no();">
									<?php 
									foreach($season_data as $season_list){
									  print "<option value=\"$season_list[season_name]\">$season_list[season_name]</option>";
									}
									?>
								</select>
							</div>
							<div class="form-group col-sm-4">
								<label for="email">Auction Center</label>
								<select required class="form-control input-sm" id="auction_center" onchange="get_universal_sale_no();">
									<option value="">Select</option>
									<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
									<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
							  </select>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Sale No</label>
								<div id="load_sale_no">
									<select required class="form-control input-sm" id="sale_no">
										<option value="">Select</option>
									</select>	
								</div>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Lot No <b><i id="sold_status"></i></b></label>
								<div class="input-group input-group-sm">
									<input type="number" class="form-control input-sm" id="lot_no" onkeydown="if (event.key === 'Tab') { fn_lot_parking(); }" onkeypress="return isNumberKey(this, event)" tabindex="1" />
									<span class="input-group-addon">
										<div id="load_bulk_lot">
											<select name="bulk_lot_id" id="bulk_lot_id" onchange="sale_lot_info(bulk_lot_id.value)">
												<option value="">Bulk Lot</option>
											</select>
										</div>	
									</span>
								</div>
								
								<!-- hidden value -->
								<input type="hidden" id="lot_id" />
								<input type="hidden" id="inv_lot_no" />
								<input type="hidden" id="sale_date" />
								<input type="hidden" id="inv_season" />
								<input type="hidden" id="net_wt_per_bag" />
								<input type="hidden" id="sample_kgs" />
								<input type="hidden" id="net_kgs" />
								<input type="hidden" id="user" value="<?php print $ubl_user_name; ?>">
								<input type="hidden" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>" />
								<!-- /hidden value -->
							</div>
							<div class="form-group col-sm-8">
								<label for="comment">Lot Details</label>
								<span class="form-control input-sm" id="lot_details" style="background-color:#f6f6f6; font-weight:bold; text-transform:uppercase; font-size:12px;"></span>
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-3">
								<label for="comment">Value</label>
								<input readonly type="text" class="form-control input-sm" id="valuation"></span>
							</div>
							<div class="form-group col-sm-3">
								<label for="comment">Chest No.</label>
								<input readonly type="text" class="form-control input-sm" id="chest_number"></span>
							</div>
							<div class="form-group col-sm-3">
								<label for="comment">Sold Price</label>
								<input type="text" class="form-control input-sm" maxlength="9" id="sold_price" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" tabindex="2" />
							</div>
							<div class="form-group col-sm-3">
								<label for="comment">Complete Price</label>
								<input type="text" class="form-control input-sm" maxlength="9" id="complete_price" onkeypress="return isNumberKey(this, event)" value="0" />
							</div>
						</div>
						
						<!-- Buyer 1 -->
						<div class="row" style="background-color:#4169E1; color:white;">
							<div class="form-group col-sm-2">
								<label for="comment">Buyer 1</label>
								<select id="buyer1" class="form-control input-sm buyer_name" tabindex="3" onchange="fn_buyer_type_hs(buyer1.value,'Buyer1');" style="text-transform:uppercase;">
									<option value="">Select</option>
									<?php foreach($buyer_data as $buyer_list){ ?>
										<option value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Full Name</label>
								<input readonly type="text" class="form-control input-sm" id="buyer_full_name1"></span>
							</div>
							<div class="form-group col-sm-2" style="background-color:#4169E1;">
								<label for="comment">Bags</label>
								<input type="text" class="form-control input-sm" maxlength="2" id="buyer1_bag" value="0" onkeyup="if(event.keyCode === 13){ sale_entry_save(lot_id.value,'Sale'); }else{ fn_chest_managemant1(); }" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" tabindex="4" />
							</div>
							<div class="form-group col-sm-2" style="background-color:#4169E1;">
								<label for="comment">Buyer Type</label>
								<select id="buyer_type1" class="form-control input-sm" tabindex="5">
									<option value="">Select</option>
									<option class="Internal_b1" value="Internal">Internal</option>
									<option class="Export_b1" value="Export">Export</option>
								</select>
							</div>
							<div class="form-group col-sm-2">
								<label for="Con.">Con.</label>
								<select id="buyer_con1" class="form-control input-sm">
									<option value="">Select</option>
									<option value="C-1" selected>C-1</option>
									<option value="C-2">C-2</option>
									<option value="C-3">C-3</option>
								</select>
							</div>
						</div>
						
						<!-- Buyer 2 -->
						<div class="row" style="background-color:#DA4E83; margin-top:5px; color:white;">
							<div class="form-group col-sm-2">
								<label for="comment">Buyer 2</label>
								<select id="buyer2" class="form-control input-sm" tabindex="6"  onchange="fn_buyer_type_hs(buyer2.value,'Buyer2');" style="text-transform:uppercase;">
									<option value="">Select</option>
									<?php foreach($buyer_data as $buyer_list){ ?>
										<option value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Full Name</label>
								<input readonly type="text" class="form-control input-sm" id="buyer_full_name2"></span>
							</div>
							<div class="form-group col-sm-2">
								<label for="comment">Bags</label>
								<input type="text" class="form-control input-sm" maxlength="2" id="buyer2_bag" value="0" onkeyup="if(event.keyCode === 13){ sale_entry_save(lot_id.value,'Sale'); }else{ fn_chest_managemant2(); }" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" tabindex="7" />
							</div>
							<div class="form-group col-sm-2">
								<label for="comment">Buyer Type</label>
								<select id="buyer_type2" class="form-control input-sm" tabindex="8">
									<option value="">Select</option>
									<option class="Internal_b2" value="Internal">Internal</option>
									<option class="Export_b2" value="Export">Export</option>
								</select>
							</div>
							<div class="form-group col-sm-2">
								<label for="Con.">Con.</label>
								<select id="buyer_con2" class="form-control input-sm">
									<option value="">Select</option>
									<option value="C-1" selected>C-1</option>
									<option value="C-2">C-2</option>
									<option value="C-3">C-3</option>
								</select>
							</div>
						</div>
						
						<!-- Buyer 3 -->
						<div class="row" style="background-color:#0077be; margin-top:5px; color:white;">
							<div class="form-group col-sm-2">
								<label for="comment">Buyer 3</label>
								<select id="buyer3" class="form-control input-sm" tabindex="9"  onchange="fn_buyer_type_hs(buyer3.value,'Buyer3');" style="text-transform:uppercase;">
									<option value="">Select</option>
									<?php foreach($buyer_data as $buyer_list){ ?>
										<option value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group col-sm-4">
								<label for="comment">Full Name</label>
								<input readonly type="text" class="form-control input-sm" id="buyer_full_name3"></span>
							</div>
							<div class="form-group col-sm-2">
								<label for="comment">Bags</label>
								<input type="text" class="form-control input-sm" maxlength="2" id="buyer3_bag" value="0" onkeyup="if(event.keyCode === 13){ sale_entry_save(lot_id.value,'Sale'); }" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" tabindex="10" />
							</div>
							<div class="form-group col-sm-2">
								<label for="comment">Buyer Type</label>
								<select id="buyer_type3" class="form-control input-sm" tabindex="11">
									<option value="">Select</option>
									<option class="Internal_b3" value="Internal">Internal</option>
									<option class="Export_b3" value="Export">Export</option>
								</select>
							</div>
							<div class="form-group col-sm-2">
								<label for="Con.">Con.</label>
								<select id="buyer_con3" class="form-control input-sm">
									<option value="">Select</option>
									<option value="C-1" selected>C-1</option>
									<option value="C-2">C-2</option>
									<option value="C-3">C-3</option>
								</select>
							</div>
						</div>
						<!-- /row -->
						
						<br />
						
						<div class="row">
							<div class="form-group col-sm-6">
								<input type="button" id="Submit" value="Submit Data" class="btn btn-sm btn-success" onclick="sale_entry_save(lot_id.value,'Sale');" tabindex="12" />
							</div>
							<div class="form-group col-sm-6 text-right">
								<input type="button" id="Out_Submit" value="Out Lot" class="btn btn-sm btn-danger" onclick="sale_entry_save(lot_id.value,'Out');" tabindex="13" />
							</div>	
						</div>	  
					</div> <!-- /well -->
				</div> <!-- /col -->
			</div> <!-- /row --> 
		</div><!-- /col -->
		<?php
		  //include("login_right_bar.php");
		?>
	</div> 
	<!-- /row -->
</div> 
<!-- /container -->

<script>
$(function(){
	// callback
	get_universal_sale_no();
	
	// jQuery Chosen Plugin
	$("#buyer1").chosen();
	$("#buyer2").chosen();
	$("#buyer3").chosen();
});

// chest management - 1
function fn_chest_managemant1(){
	var chest_number = $("#chest_number").val();
	var buyer1_bag = $("#buyer1_bag").val();
	var buyer2_bag = $("#buyer2_bag").val();
	if(parseFloat(buyer1_bag) < 0){
		alert("Invalid Chest Number.");
		$("#buyer1_bag").val('0');
	}
	var rest_chest1 = (parseFloat(chest_number) - parseFloat(buyer1_bag));
	(rest_chest1 > 0) ? $("#buyer2_bag").val(rest_chest1) : $("#buyer2_bag").val('0');
}

// chest management - 2
function fn_chest_managemant2(){
	var chest_number = $("#chest_number").val();
	var buyer1_bag = $("#buyer1_bag").val();
	var buyer2_bag = $("#buyer2_bag").val();
	var buyer3_bag = $("#buyer3_bag").val();
	if(parseFloat(buyer2_bag) < 0){
		alert("Invalid Chest Number.");
		$("#buyer2_bag").val('0');
	}else if(parseFloat(buyer3_bag) < 0){
		alert("Invalid Chest Number.");
		$("#buyer3_bag").val('0');
	}
	var rest_chest2 = (parseFloat(chest_number) - (parseFloat(buyer1_bag) + parseFloat(buyer2_bag)));
	(rest_chest2 > 0) ? $("#buyer3_bag").val(rest_chest2) : $("#buyer3_bag").val('0');
}
</script>