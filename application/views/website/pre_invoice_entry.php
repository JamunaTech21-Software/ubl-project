<div class="container text-center">    
	<div class="row">
<?php
	//include("login_left_bar.php");
	$edit_id = (!empty($this->input->get('id', TRUE))) ? $this->input->get('id', TRUE) : '0';
?>
<style>
label{ font-size:12px; }
</style>

<div class="col-sm-12">

<div class="row">
<div class="col-sm-12">
<div class="well" id="form_group_element">
<form id="pre_invoice_entry" action="#">

	<!-- fixed value -->
	<input type="hidden" name="setup_id" id="setup_id">
	<input type="hidden" name="edit_system_id" id="edit_system_id">
	<input required type="hidden" name="user" id="user" value="<?php print $ubl_web_user_id; ?>">
	<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
	<!-- End fixed data -->
	
	<div class="form-group">
		<b for="comment"><span class="glyphicon glyphicon-file text-primary"></span><a href="<?php echo base_url().'index.php/MY_Controller/pre_invoice_entry'; ?>"> Pre Invoice Entry </a><i id="form_mode"></i></b>
	</div>

	<hr />

				<div class="row">
					<div class="form-group col-sm-2">
						<label for="Challan Received Date">Challan Received Date</label>
						<input type="date" class="form-control input-sm" id="challan_received_date" name="challan_received_date" tabindex="1" value="<?php print date('Y-m-d');?>"/>
					</div>

					<div class="form-group col-sm-4">
						<label for="ex3">Inv Group</label>
						<div class="input-group">
							<input type="text" class="form-control input-sm text-uppercase" id="invoice_group_txt" name="invoice_group_txt" tabindex="2" value="EX"/>
							<span class="input-group-addon">-</span>
							<input type="number" class="form-control input-sm text-uppercase" tabindex="3" id="invoice_group" name="invoice_group"/>
							<span class="input-group-addon">To</span>
							<input type="number" class="form-control input-sm text-uppercase" id="invoice_group_upto" name="invoice_group_upto" tabindex="4"/>
						</div>
					</div>
					
					<div class="form-group col-sm-4">
						<label for="ex1">W.House Name</label>
						<select required class="form-control input-sm" id="w_house_id" name="w_house_id" tabindex="5" onchange="get_group_no()" style="text-transform:uppercase;">
							<option value="">Select Warehouse</option>
							<?php 
							foreach($wh_data as $wh_data_list){
							print "<option value=\"$wh_data_list[id]\">$wh_data_list[warehouse_name] - $wh_data_list[short_key_word]</option>";
							}
							?>
						</select>
					</div>

					<div class="form-group col-sm-2">
						<label for="Chest Type">Chest Type</label>
						<select required class="form-control input-sm" name="chest_name" id="chest_name" tabindex="6">
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

					<div class="form-group col-sm-3">
						<label for="Garden">Garden</label>
						<select required class="form-control input-sm" name="garden_id" id="garden_id" tabindex="7" style="text-transform:uppercase;">
							<option value="">Select Garden</option>
							<?php 
							foreach($garden_data as $garden_data_list){
							print "<option value=\"$garden_data_list[id]\">$garden_data_list[garden_name] </option>";
							}
							?>
						</select>
					</div>

					<div class="form-group col-sm-3">
						<label for="ex3">Inv No</label>
						<input required type="number" class="form-control input-sm" id="w_inv_no" name="w_inv_no" tabindex="8" style="text-transform:uppercase;" />
					</div>

					<div class="form-group col-sm-3">
						<label for="Grade">Grade</label>
						<select required class="form-control input-sm" name="grade_id" id="grade_id" tabindex="9" style="text-transform:uppercase;">
						<option value="">Select Grade</option>
						<?php 
						foreach($grade_data as $grade_data_list){
						print "<option title=\"$grade_data_list[catalogue_name]\" value=\"$grade_data_list[id]\">$grade_data_list[grade_name]</option>";
						}
						?>
						</select>
					</div>

					<div class="form-group col-sm-3">
						<label for="ex2">Additional Grade</label>
						<input type="text" class="form-control input-sm" id="additional_grade" name="additional_grade" tabindex="10" style="text-transform:uppercase;" />
					</div>

					<div class="form-group col-sm-3">
						<label for="No. of Bag">No. of Bag</label>
						<input required type="number" class="form-control input-sm" id="no_of_bag" name="no_of_bag" onkeypress="return isNumberKey(event)" value="10" onfocus="this.select()" onblur="if(this.value=='') this.value='0'" tabindex="10" maxlength="" />
					</div>

					<div class="form-group col-sm-3">
						<label for="Gross Kgs">Gross Kgs</label>
						<input required type="number" step="0.00001" class="form-control input-sm" id="gross_kg" name="gross_kg" onkeypress="return isNumberKey(event)" value="50.20" onfocus="this.select()" onblur="if(this.value=='') this.value='0'" tabindex="12" maxlength="" />
					</div>

					<div class="form-group col-sm-3">
						<label for="Tare Kgs">Tare Kgs</label>
						<input required type="number" step="0.00001" class="form-control input-sm" id="tare_kg" name="tare_kg" onkeypress="return isNumberKey(event)" value="0.20" onfocus="this.select()" onblur="if(this.value=='') this.value='0'" tabindex="13" maxlength="" />
					</div>

					<div class="form-group col-sm-3">
						<label for="Net Kgs">Net Kgs</label>
						<input required type="number" step="0.00001" class="form-control input-sm" id="net_kg" name="net_kg" onkeypress="return isNumberKey(event)" value="50" onfocus="this.select()" onblur="if(this.value=='') this.value='0'" tabindex="14" maxlength="" />
					</div>

					<div class="form-group col-sm-12">
						<input type="submit" name="Submit" id="Submit" value="Submit" class="btn btn-success" style="float:right;" tabindex="15" /> 
					</div>
				</div>
			</form>  
		</div>
	</div>
</div> 
<!-- /row-->

<div class="row">
	<div id="pre_invoice_list" class="col-sm-12"></div>

	<div class="col-sm-12 text-right form-group">
		<input type="button" name="final_button" id="final_button" value="Final Data" onclick="final_pre_invoice_data();" class="btn btn-sm btn-danger" tabindex="14" /> 
	</div>	
</div>
		
		</div> <!-- /col-->
	</div> <!-- /row -->
</div> 
<!-- /container -->

<?php
// get method data
$invoice_id = !empty($this->uri->segment('3')) ? $this->uri->segment('3') : '';

// query
if(!empty($invoice_id)){
	$this->db->select("*");
	$this->db->from('pre_invoice_entry');
	$this->db->where('id', $invoice_id);
	$query = $this->db->get();
	$result = $query->row_array();
} // if brace
?>

<script>
$(function(){
	show_pre_invoice_list('<?php print $edit_id; ?>'); 

	// when edit id
	<?php if(!empty($invoice_id)){ ?>
		get_update_data_invoice('<?php echo $result['id']; ?>','<?php echo $result['system_id']; ?>','<?php echo $result['challan_received_date']; ?>','<?php echo $result['w_house_id']; ?>','<?php echo $result['chest_name']; ?>','<?php echo $result['garden_id']; ?>','<?php echo $result['w_inv_no']; ?>','<?php echo $result['grade_id']; ?>','<?php echo $result['additional_grade']; ?>','<?php echo $result['gross_kg']; ?>','<?php echo $result['tare_kg']; ?>','<?php echo $result['net_kg']; ?>','<?php echo $result['no_of_bag']; ?>','<?php echo $result['invoice_group_txt']; ?>','<?php echo $result['invoice_group']; ?>','<?php echo $result['invoice_group_upto']; ?>');
	<?php } // if brace ?>
	// end
});

// update data
function get_update_data_invoice(setup_id,system_id,challan_received_date,w_house_id,chest_name,garden_id,w_inv_no,grade_id,additional_grade,gross_kg,tare_kg,net_kg,no_of_bag,invoice_group_txt,invoice_group,invoice_group_upto) {

	$('#form_mode').html('<font color=red> - (Update)</font>');
	$('#setup_id').val(setup_id);
	$('#edit_system_id').val(system_id);
	$('#challan_received_date').val(challan_received_date);
	$('#w_house_id').val(w_house_id);
	$('#chest_name').val(chest_name);
	$('#garden_id').val(garden_id);
	$('#w_inv_no').val(w_inv_no);
	$('#grade_id').val(grade_id);
	$('#additional_grade').val(additional_grade);
	$('#gross_kg').val(gross_kg);
	$('#tare_kg').val(tare_kg);
	$('#net_kg').val(net_kg);
	$('#no_of_bag').val(no_of_bag);
	$('#invoice_group_txt').val(invoice_group_txt);
	$('#invoice_group').val(invoice_group);
	$('#invoice_group_upto').val(invoice_group_upto);
	$('html, body').animate({ scrollTop: $('#form_mode').offset().top }, 'slow');
}

// check all
function lots_checkall() {
	var array = document.getElementsByTagName("input");
	for (var ii = 0; ii < array.length; ii++) {
		if (array[ii].type == "checkbox") {
			if (array[ii].className == 'lots_checkbox') {
				if(document.getElementById('check_all').checked){
					array[ii].checked = true;
				}else{
					array[ii].checked = false;
				}
			}
		}
	}
	get_lots_id();
}

// lots id
function get_lots_id(){
	document.getElementById('cart_lot_ids').value = '0';
	$(".lots_checkbox:checked").each(function () {
		var id = $(this).attr("id");
		var lots_id = $("#"+id).val();
		document.getElementById('cart_lot_ids').value += ','+lots_id;
	});	
}

// pre invoice cart final
function final_pre_invoice_data(){
        
	// get html value
	var cart_lot_ids = $("#cart_lot_ids").val();

	// form validation
	if(cart_lot_ids=='0'){
		alert("Sorry !! invoice cart data is empty.");
		return false;
	}
	
	//use ajax to run the check
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/pre_invoice_entry_final'); ?>",
		dataType : "JSON",
		data: { cart_lot_ids: cart_lot_ids },
		success: function(data) {
			if(data==1){
				alert("Invoice Final: Success");
				show_pre_invoice_list();
			}else{
				alert(data);
			}
		}
	});
	return false;
}
</script>