<div class="container text-center">    
	<div class="row">

<?php
	$edit_id = (!empty($this->input->get('id', TRUE))) ? $this->input->get('id', TRUE) : '0';
	$system_id = (!empty($this->input->get('system_id', TRUE))) ? $this->input->get('system_id', TRUE) : '';
	$group_edit_ids = (!empty($group_edit_ids)) ? $group_edit_ids : '';

	$this->db->select('invoice_group_txt,invoice_group,invoice_group_upto');
	$this->db->from('pre_invoice_entry');
	$this->db->where('system_id', $system_id);
	$query = $this->db->get();
	$result = $query->row_array();
?>
<style>
	label{ font-size:12px; }
	.bgcolor{
		background-color: #EEEEEE !important;
	}
</style>

<div class="col-sm-12">

<div class="row">
<div class="col-sm-12">
<div class="well" id="form_group_element">
<form id="group_brokers_invoice_entry" action="#">

	<input type="hidden" name="group_edit_ids" id="group_edit_ids" value="<?php echo htmlspecialchars($group_edit_ids); ?>">
	<input type="hidden" readonly name="setup_id" id="setup_id">
	<input required type="hidden" name="user" id="user" value="<?php print $ubl_web_user_id; ?>">
	<input type="hidden" readonly name="org_id" id="org_id">
	<input type="hidden" readonly name="reprinted_invoice" id="reprinted_invoice">
	<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
	
	<div class="row">
		<div class="form-group col-sm-12">
			<b for="comment">
				<span class="glyphicon glyphicon-file text-primary"></span>
				<a href="<?php echo base_url().'index.php/MY_Controller/invoice_entry'; ?>"> 
                    <span id="form_title">GROUP ENTRY</span> 
                </a>
				<i id="form_mode"></i>
			</b>
		</div>
	</div>

	<hr />

	<div class="single-entry-field">
		<div class="row">
			<div class="form-group col-sm-2">
				<label for="ex1">Season</label>
				<select required class="form-control input-sm bgcolor" id="season" name="season" onchange="get_invoice_entry_sale_no();">
					<?php 
					foreach($season_data as $season_list){
						$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
						print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
					}
					?>
				</select>
			</div>
			<div class="form-group col-sm-2">
				<label for="email">Auction Center</label>
				<select required name="auction_center" id="auction_center" class="form-control input-sm bgcolor" onchange="get_invoice_entry_sale_no();">
					<option value="">Select</option>
					<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
					<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
				</select>
			</div>
			<div class="form-group col-sm-2">
				<label for="ex2">Catalogue</label>
				<select autofocus required class="form-control input-sm" id="catalouge_name" name="catalouge_name" tabindex="1" onchange="sg_hide_show(catalouge_name.value)">
					<option value="">Select Catalogue</option>
					<?php 
					foreach($catalogue_data as $catalogue_list){
					print "<option value=\"$catalogue_list[catalouge_name]\">$catalogue_list[catalouge_name]</option>";
					}
					?>
				</select>
			</div>
			<div class="form-group col-sm-2">
				<label for="ex3">Suppliment Catalogue</label>
				<div class="input-group input-group-sm">
					<select required class="form-control input-sm" id="supplement_tea" name="supplement_tea" onchange="fn_e_auction_sk(supplement_tea.value)">
						<option value="">Select Suppliment</option>
						<?php 
						foreach($s_catalogue_data as $s_catalogue_list){
							print "<option class=\"$s_catalogue_list[suppliment_tea]\" value=\"$s_catalogue_list[suppliment_tea]\">$s_catalogue_list[suppliment_tea]</option>";
						}
						?>
					</select>
					<span class="input-group-addon">	
						<input type="checkbox" name="e_auc" id="e_auc" value="E-Auc" onClick="fn_e_auction_sk(supplement_tea.value)" /> E-Auc
					</span>
				</div>
			</div>
			<div class="form-group col-sm-2">
				<label for="ex3">Chest Type</label>
				<select required class="form-control input-sm bgcolor" id="chest_name" name="chest_name">
					<option value="">Select Chest</option>
					<?php 
					foreach($chest_data as $chest_list){
					?>	
					<option <?php if($chest_list['chest_name']=='BAGS'){ ?> selected <?php } ?> value="<?php print $chest_list['chest_name']; ?>"><?php print $chest_list['chest_name']; ?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div class="form-group col-sm-2">
				<label for="ex3">Category</label>
				<select required name="category" id="category" class="form-control input-sm bgcolor">
					<option value="CTC">CTC</option>
					<option value="GREEN TEA">GREEN TEA</option>
				</select>
			</div>
		</div>
		<div class="row">	
			<div class="form-group col-sm-2">
				<label for="comment">Sale No</label>
				<div id="load_sale_no">
				<select required class="form-control input-sm" id="sale_no" name="sale_no" tabindex="2">
					<option value="">Select sale no</option>
				</select>
				</div>
			</div>
			<div class="form-group col-sm-2">
				<label for="ex2">Sale Date</label>
				<input required readonly type="date" class="form-control input-sm" id="sale_date" name="sale_date">
			</div>
			<div class="form-group col-sm-4">
				<label for="ex1">Lot No</label>
				<div class="input-group input-group-sm">
					<span class="input-group-addon">
						<div id="load_bulk_lot">
							<select name="lot_type" id="lot_type" onchange="fn_change_tabindex_dynamically(this.value)">
								<option value="Fresh" selected>Fresh</option>
								<option value="Reprint">Reprint</option>
							</select>
						</div>
					</span>	
					<input required type="number" class="form-control input-sm" id="lot_no" name="lot_no" autocomplete="off" tabindex="3" title="Type the value and press Tab"/>
					<span class="input-group-addon">
						<div id="load_bulk_lot">
							<select name="bulk_lot_id" id="bulk_lot_id" onchange="show_lot_info(bulk_lot_id.value)">
								<option value="">Bulk Lot</option>
							</select>
						</div>
					</span>	
					<span class="input-group-addon">	
						<input type="checkbox" name="bulk_lot" id="bulk_lot" value="Bulk Lot" /> Bulk
					</span>
				</div>
			</div>
		</div>
	</div> <div class="row">
		<div class="form-group col-sm-4">
			<label for="ex2">Garden Name</label>
			<select required class="form-control input-sm" id="garden_id" name="garden_id" style="text-transform:uppercase;">
				<option value="">Select Garden</option>
				<?php 
				foreach($garden_data as $garden_data_list){
				print "<option value=\"$garden_data_list[id]\">$garden_data_list[garden_name] </option>";
				}
				?>
			</select>
		</div>
		<div class="form-group col-sm-4">
			<label for="ex1">W.House Name</label>
			<select required class="form-control input-sm" id="w_house_id" name="w_house_id" style="text-transform:uppercase;">
				<option value="">Select Warehouse</option>
				<?php 
				foreach($wh_data as $wh_data_list){
				print "<option value=\"$wh_data_list[id]\">$wh_data_list[warehouse_name] - $wh_data_list[short_key_word]</option>";
				}
				?>
			</select>
		</div>
		<div class="form-group col-sm-4">
			<label for="ex3">Inv Group</label>
			<div class="input-group">
				<input type="text" class="form-control input-sm text-uppercase" id="invoice_group_txt" name="invoice_group_txt" tabindex="4" value="<?php print !empty($result['invoice_group_txt']) ? $result['invoice_group_txt'] : 'EX'; ?>"/>
				<span class="input-group-addon">-</span>
				<input type="number" class="form-control input-sm text-uppercase" id="invoice_group" name="invoice_group" autocomplete="off" tabindex="5" value="<?php print !empty($result['invoice_group']) ? $result['invoice_group'] : ''; ?>"/>
				<span class="input-group-addon">To</span>
				<input type="number" class="form-control input-sm text-uppercase" id="invoice_group_upto" name="invoice_group_upto" autocomplete="off" tabindex="6" value="<?php print !empty($result['invoice_group_upto']) ? $result['invoice_group_upto'] : ''; ?>"/>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-sm-4">
			<label for="ex3">Old Season</label>
			<select required class="form-control input-sm bgcolor" id="old_season" name="old_season">
				<?php 
				foreach($season_data as $season_list){
					$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
					print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
				}
				?>
			</select>
		</div>
	</div>
	
	<div class="single-entry-field">
		<div class="row">
			<div class="form-group col-sm-3">
				<label style="color:red;" for="ex2">Old Sale No</label>
				<div class="input-group input-group-sm">
					<span class="input-group-addon">
						<select required id="old_season2" name="old_season2" onchange="show_old_sale_lot_info()">
							<?php 
								foreach($season_data as $season_list){
									$selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
									print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
								}
							?>
						</select>
					</span>
					<span class="input-group-addon">
						<select required name="ol_ac" id="ol_ac" onchange="show_old_sale_lot_info()">
							<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sre</option>
							<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Ctg</option>
						</select>
					</span>
					<input type="number" class="form-control input-sm" id="old_sale_no" name="old_sale_no" title="Type the value and press Tab"/>
				</div>
			</div>
			<div class="form-group col-sm-3">
				<label style="color:red;" for="ex3">Old Lot <b id="sold_status" style="color:green;"></b></label>
				<input type="number" class="form-control input-sm" id="old_lot_no" name="old_lot_no" title="Type the value and press Tab"/>		
			</div>
		</div>
		<div class="row">	
			<div class="form-group col-sm-2">
				<label for="ex3">Inv No</label>
				<div class="input-group input-group-sm">
					<input required type="number" class="form-control input-sm" id="w_inv_no" name="w_inv_no" style="text-transform:uppercase;" autocomplete="off" />
					<span class="input-group-addon">	
						<input type="checkbox" name="lot_part" id="lot_part" value="Lot Part" /> Part
					</span>
				</div>
			</div>
			<div class="form-group col-sm-2">
				<label for="ex1">Grade</label>
				<select required class="form-control input-sm" id="grade_id" name="grade_id" style="text-transform:uppercase;" onchange="fn_cc_by_grade(setup_id.value)">
					<option value="">Select Grade</option>
					<?php 
					foreach($grade_data as $grade_data_list){
					print "<option title=\"$grade_data_list[catalogue_name]\" value=\"$grade_data_list[id]\">$grade_data_list[grade_name]</option>";
					}
					?>
				</select>
			</div>
			<div class="form-group col-sm-2">
				<label for="ex2">Additional Grade</label>
				<input type="text" class="form-control input-sm" id="additional_grade" name="additional_grade" style="text-transform:uppercase;" />
			</div>
			<div class="form-group col-sm-2">
				<label for="ex3">Chest</label>
				<input required type="number" class="form-control input-sm" id="chest_number" name="chest_number" onkeyup="invoice_entry_cal()" value="0" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0'" autocomplete="off" />
			</div>
			<div class="form-group col-sm-1">
				<label for="ex1">Bag</label>
				<input required type="number" step="0.00001" class="form-control input-sm" id="gross_wt_per_bag" name="gross_wt_per_bag" value="50.2" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0'" autocomplete="off" />
			</div>
			<div class="form-group col-sm-2">
				<label for="ex2">Net WT / Bag</label>
				<input required type="number" step="0.00001" class="form-control input-sm" id="net_wt_per_bag" name="net_wt_per_bag" onkeyup="invoice_entry_cal()" value="50" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0'" autocomplete="off" />
			</div>
		</div>
		<div class="row">	
			<div class="form-group col-sm-2">
				<label for="ex3">Total Kgs</label>
				<input readonly type="number" step="0.00001" class="form-control input-sm" id="total_kgs" name="total_kgs" value="0" onkeyup="invoice_entry_cal()" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0'" autocomplete="off" />
			</div>
			<div class="form-group col-sm-2">
				<label for="ex1">Sample Drawn</label>
				<input readonly type="number" step="0.00001" class="form-control input-sm" id="sample_kgs" name="sample_kgs" value="0" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0'" />
			</div>
			<div class="form-group col-sm-2">
				<label for="ex2">Add. Sample Kgs</label>
				<div class="input-group input-group-sm">
					<input required type="number" step="0.00001" class="form-control input-sm" id="additional_sample_kgs" name="additional_sample_kgs" onkeyup="invoice_entry_cal()" value="0" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0', invoice_entry_cal()" autocomplete="off" />
					<span class="input-group-addon"><input type="checkbox" name="weight_short" id="weight_short" value="Short" onclick="fn_weight_short()" /> Short</span>
				</div>	
			</div>
			<div class="form-group col-sm-2">
				<label for="ex3">Net Kgs</label>
				<input readonly type="number" step="0.00001" class="form-control input-sm" id="net_kgs" name="net_kgs" value="0">
			</div>
			<div class="form-group col-sm-2">
				<label for="ex1">Remarks</label>
				<select class="form-control input-sm" id="remarks" name="remarks">
					<option value="">Select</option>
					<option value="W/D">Withdraw</option>
					<option value="Not Printed">Not Printed</option>
				</select>
			</div>
		</div>
		<div class="row">	
			<div class="form-group col-sm-4">
				<label for="ex1">Short Note</label>
				<input type="text" class="form-control input-sm" id="short_note" name="short_note" maxlength="30">
			</div>
			<div class="form-group col-sm-3">
				<label style="color:red;" for="ex3">Show In Catalogue ?</label>
				<div class="input-group input-group-sm">
					<select disabled class="form-control input-sm" name="ol_os_sc" id="ol_os_sc" onchange="fn_ol_os_sc()">
						<option value="">Select</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
					<span class="input-group-addon">	
						<input type="checkbox" name="account_concerned" id="account_concerned" value="A/C CONCERNED" /> A/C CONCERNED
					</span>
				</div>
			</div>
		</div>
	</div> <div class="row">
		<div class="form-group col-sm-4">
			<input type="submit" name="Submit" id="Submit" value="Submit" class="btn btn-success" style="margin-top:22px;"/> 
		</div>
	</div> 
</form>  
</div>
</div>
</div> 

<div class="row single-entry-field">
	<div id="invoice_list" class="col-sm-12"></div>
</div>
		
</div>
</div>
</div> 

<script>
$(function(){
    // This code runs when the page is loaded
    const group_ids = $('#group_edit_ids').val();

    if (group_ids) {
        // ✅ GROUP EDIT MODE
        // 1. Hide all fields meant only for single entry
        $('.single-entry-field').hide();

        // 2. Update the UI title
        $('#form_title').text('GROUP EDIT MODE');
        $('#form_mode').html('<font color=blue> - (Editing ' + group_ids.split(',').length + ' lots)</font>');

        // 3. Populate the visible fields with data from the first lot
        <?php if (!empty($edit_data)) { ?>
            // Set the values using the actual IDs from the database
            $('#garden_id').val('<?php echo $edit_data["garden_id"]; ?>').trigger('change');
            $('#w_house_id').val('<?php echo $edit_data["w_house_id"]; ?>').trigger('change');
            $('#invoice_group_txt').val('<?php echo $edit_data["invoice_group_txt"]; ?>');
            $('#invoice_group').val('<?php echo $edit_data["invoice_group"]; ?>');
            $('#invoice_group_upto').val('<?php echo $edit_data["invoice_group_upto"]; ?>');
            $('#old_season').val('<?php echo $edit_data["old_season"]; ?>');
        <?php } ?>
    } else {
        // ✅ SINGLE ENTRY / EDIT MODE
        show_invoice_list('<?php print $edit_id; ?>'); 
        <?php if(empty($edit_id) && empty($system_id)){ ?> get_invoice_entry_sale_no(); <?php } ?>
        <?php if(!empty($system_id)){ ?> document.getElementById("pre_invoice_btn").click(); <?php } ?>
    }

    // Initialize Select2 dropdowns
    const selectIds = ['w_house_id', 'garden_id', 'grade_id'];
    selectIds.forEach(id => {
        $('#' + id).select2({ width: '100%', placeholder: 'Select', allowClear: false });
    });
    
    // Other existing setup code...
    fn_change_tabindex_dynamically($('#lot_type').val());
});

$('#group_brokers_invoice_entry').submit(function(e) {
	e.preventDefault();

	// Skip validation in group edit mode
	if (!$('#group_edit_ids').val()) {
		if ($("#chest_number").val() < 1) {
			alert("Sorry !! You have to must enter chest value greater than zero");
			$("#chest_number").focus();
			return false;
		}
		if ($("#lot_type").val() === "Reprint" && $("#ol_os_sc").val() === "") {	
			alert("Please select 'Yes' or 'No' for 'Show In Catalogue ?'");
			$("#ol_os_sc").focus();
			return false;
		}
	}

	$('#Submit').val('Please Wait...').prop('disabled', true);
	// Temporarily enable fields for serialization
	$('#w_house_id, #garden_id, #grade_id').prop('disabled', false);

	var formData = $(this).serialize();

	$.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/save_brokers_group_invoice_info'); ?>",
		dataType: "JSON",
		data: formData,
		success: function(ie_data) {
			if (ie_data == 1) {
				alert("Form Submitted: Success");
				// Reset logic for single entry...
				location.reload(); // Simple reload to reset form
			} else if (ie_data == 5) {
                alert("Group Update Successful: " + $('#group_edit_ids').val().split(',').length + " records have been updated.");
                if (window.opener != null && !window.opener.closed) {
                    window.opener.location.reload();
                    window.close();
                } else {
                    window.location.href = "<?php echo site_url('MY_Controller/pre_catalogue_entry_data'); ?>"; // Redirect if opener is lost
                }
			} else if (ie_data == 2) {
				alert("Form Updated: Success");
				// Reset logic for single update...
				if (window.opener != null && !window.opener.closed) {
                    window.opener.location.reload();
                    window.close();
                } else {
					location.reload();
				}
			} else if (ie_data == 3) {
				alert("Error !! Lot No already exists.");
				$("#lot_no").focus();
			} else if (ie_data == 4) {
				alert("Error !! Invoice No already exists.");
				$("#w_inv_no").focus();
			} else {
				alert(ie_data);
			}
			$('#Submit').val('Submit').prop("disabled", false);
		}
	});
});

// All other helper functions like get_update_data_invoice, fn_change_tabindex_dynamically, etc.
// remain unchanged from the previous version. They are not needed for the group edit UI but are
// required for single entry/edit to work correctly.
// ... (paste your other JS functions here) ...

</script>

<?php
// This part is only for populating a single-edit form
if(!empty($edit_id)){
	$query = $this->db->query("SELECT * FROM invoice_entry WHERE id='".$edit_id."' ");
	$row2 = $query->row_array();
?>
<script>
	// This function populates the form for a SINGLE lot update
	get_update_data_invoice('<?php echo $row2['id']; ?>','<?php echo $row2['season']; ?>','<?php echo $row2['auction_center']; ?>','<?php echo $row2['catalouge_name']; ?>','<?php echo $row2['supplement_tea']; ?>','<?php echo $row2['category']; ?>','<?php echo $row2['chest_name']; ?>','<?php echo $row2['old_season']; ?>','<?php echo $row2['invoice']; ?>','<?php echo $row2['sale_no']; ?>','<?php echo $row2['sale_date']; ?>','<?php echo $row2['group_no']; ?>','<?php echo $row2['lot_type']; ?>','<?php echo $row2['lot_no']; ?>','<?php echo $row2['old_sale_no']; ?>','<?php echo $row2['old_season2']; ?>','<?php echo $row2['ol_ac']; ?>','<?php echo $row2['old_lot_no']; ?>','<?php echo $row2['ol_os_sc']; ?>','<?php echo $row2['w_house_id']; ?>','<?php echo $row2['garden_id']; ?>','<?php echo $row2['invoice_group_txt']; ?>','<?php echo $row2['invoice_group']; ?>','<?php echo $row2['invoice_group_upto']; ?>','<?php echo $row2['w_inv_no']; ?>','<?php echo $row2['grade_id']; ?>','<?php echo $row2['additional_grade']; ?>','<?php echo $row2['chest_number']; ?>','<?php echo $row2['gross_wt_per_bag']; ?>','<?php echo $row2['net_wt_per_bag']; ?>','<?php echo $row2['total_kgs']; ?>','<?php echo $row2['sample_kgs']; ?>','<?php echo $row2['additional_sample_kgs']; ?>','<?php echo $row2['weight_short']; ?>','<?php echo $row2['net_kgs']; ?>','<?php echo $row2['remarks']; ?>','<?php echo $row2['short_note']; ?>','<?php echo $row2['pre_invoice_id']; ?>','<?php echo $row2['org_id']; ?>','<?php echo $row2['reprinted_invoice']; ?>','<?php echo $row2['e_auc']; ?>','<?php echo $row2['lot_part']; ?>','<?php echo $row2['bulk_lot']; ?>','<?php echo $row2['account_concerned']; ?>');
	
	$.when(get_invoice_entry_sale_no()).then(function() {
		setTimeout(function() {$('#sale_no').val('<?php echo $row2['sale_no']; ?>')}, 500); 
	});
</script>
<?php } ?>