<div class="container text-center">    
	<div class="row">

<?php
	//include("login_left_bar.php");
	$edit_id = (!empty($this->input->get('id', TRUE))) ? $this->input->get('id', TRUE) : '0';
	$system_id = (!empty($this->input->get('system_id', TRUE))) ? $this->input->get('system_id', TRUE) : '';

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
<form id="brokers_invoice_entry" action="#">

	<!-- fixed value -->
	<input type="hidden" readonly name="setup_id" id="setup_id">
	<input required type="hidden" name="user" id="user" value="<?php print $ubl_web_user_id; ?>">
	<input type="hidden" readonly name="org_id" id="org_id">
	<input type="hidden" readonly name="reprinted_invoice" id="reprinted_invoice">
	<input required type="hidden" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
	<!-- End fixed data -->
	
	<div class="row">
		<div class="form-group col-sm-12">
			<b for="comment"><span class="glyphicon glyphicon-file text-primary"></span><a href="<?php echo base_url().'index.php/MY_Controller/invoice_entry'; ?>"> INVOICE ENTRY </a><i id="form_mode"></i></b>
		</div>

		<!-- <div class="form-group col-sm-10 text-right">
			<b for="comment"><span class="glyphicon glyphicon-file text-primary"></span><a href="<?php echo base_url().'index.php/MY_Controller/pre_invoice_verify_entry'; ?>"> Invoice Parking </a><i id="form_mode"></i></b>
		</div> -->
</div>

	<hr />
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
		<label for="comment">Sale No <a title="Select Sale No" href="javascript:void(0);" data-toggle="modal" data-target="#saleNoModal">
		<span class="glyphicon glyphicon-plus text-danger"></span>
		</a></label>
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
		<label for="ex1">Lot No <span title="Click here to get previous lot data to change" style="cursor:pointer; color:green; display:none;" class="glyphicon glyphicon-edit" onclick="fn_lot_parking2()"></span></label>
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

	<div class="form-group col-sm-3" style="display:none;">
		<label for="ex3" style="color:red;">Group No</label>
		<input type="number" readonly class="form-control input-sm" id="group_no" name="group_no">
	</div>
	<div class="form-group col-sm-2" style="display:none;">
		<label for="ex3">Inv. Ext.</label>
		<input type="text" class="form-control input-sm" id="invoice" name="invoice" style="text-transform:uppercase;" />
	</div>
	<div class="form-group col-sm-3" style="display:none;">
		<label for="Pre Invoice ID">Pre Invoice ID <span title="Click here to get invoice parking data" style="cursor:pointer; color:green;" class="glyphicon glyphicon-search" id="pre_invoice_btn" onclick="fn_search_pre_invoice_id()"></span></label>
		<input type="number" class="form-control input-sm bgcolor" id="pre_invoice_id" name="pre_invoice_id" value="<?php print !empty($system_id) ? $system_id : ''; ?>" autocomplete="off" onfocus="this.select()"/>
	</div>
</div>
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
						} // foreach
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
	<div class="form-group col-sm-3">
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
	<div class="form-group col-sm-3">
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
	<div class="form-group col-sm-2">
		<label for="ex1">Bag</label>
		<div class="input-group input-group-sm">
			<input required type="number" step="0.00001" 
				class="form-control input-sm" 
				id="gross_wt_per_bag" 
				name="gross_wt_per_bag" 
				value="50.2" 
				readonly
				onfocus="if(this.value==0) this.value=''" 
				onblur="if(this.value=='') this.value='0'" 
				autocomplete="off" />
			<span class="input-group-addon">
				<input type="checkbox" id="edit_gross_wt" /> chg
			</span>
		</div>
	</div>

	<div class="form-group col-sm-2">
		<label for="ex2">Net WT / Bag</label>
		<div class="input-group input-group-sm">
			<input required type="number" step="0.00001" 
				class="form-control input-sm" 
				id="net_wt_per_bag" 
				name="net_wt_per_bag" 
				value="50" 
				readonly
				onkeyup="invoice_entry_cal()" 
				onfocus="if(this.value==0) this.value=''" 
				onblur="if(this.value=='') this.value='0'" 
				autocomplete="off" />
			<span class="input-group-addon">
				<input type="checkbox" id="edit_net_wt" /> chg
			</span>
		</div>
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
	<div class="form-group col-sm-4">
		<input type="submit" name="Submit" id="Submit" value="Submit" class="btn btn-success" style="margin-top:22px;"/> 
	</div>
</div> 
<!-- /row -->
			</form>  
		</div>
	</div>
</div> 
<!-- /row-->
<div class="modal fade" id="saleNoModal" tabindex="-1" role="dialog" aria-labelledby="saleNoModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="saleNoModalLabel">Select Sale Number</h4>
      </div>
      <div class="modal-body">
        <form id="modalSaleForm">
          <div class="row">
            <div class="form-group col-sm-6">
              <label for="modal_season">Season</label>
              <select class="form-control input-sm" id="modal_season" name="modal_season" onchange="get_modal_sale_no()">
                <?php 
                foreach($season_data as $season_list){
                  $selected = ($season_list['season_name'] == $current_season_data->season_name) ? 'selected' : '';
                  print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group col-sm-6">
              <label for="modal_auction_center">Auction Center</label>
              <select class="form-control input-sm" id="modal_auction_center" name="modal_auction_center" onchange="get_modal_sale_no()">
                <option value="">Select</option>
                <option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
                <option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-12">
              <label for="modal_sale_no">Sale No</label>
              <div id="load_modal_sale_no">
                <select class="form-control input-sm" id="modal_sale_no" name="modal_sale_no">
                  <option value="">Select Season and Auction Center first</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="saveSaleNo()">Save</button>
      </div>
    </div>
  </div>
</div>
<div class="row">
	<div id="invoice_list" class="col-sm-12"></div>
</div>
		
		</div> <!-- /col-->
	</div> <!-- /row -->
</div> 
<!-- /container -->

<script>
$(function(){
    show_invoice_list('<?php print $edit_id; ?>'); 
    <?php if(empty($edit_id) && empty($system_id)){ ?> get_invoice_entry_sale_no(); <?php } ?>

    <?php if(!empty($system_id)){ ?> document.getElementById("pre_invoice_btn").click(); <?php } ?>

    // Tab key handling for lot_no, old_sale_no, old_lot_no
    $('#lot_no, #old_sale_no, #old_lot_no').on('keydown', function (e) {
        if (e.key === 'Tab') {
            const elementId = $(this).attr('id');

            setTimeout(function () {
                if (elementId === 'lot_no') {
                    fn_change_lot_wise_catalogue();
                } else if (elementId === 'old_sale_no' || elementId === 'old_lot_no') {
                    show_old_sale_lot_info();
                }
            }, 10);
        }
    });

    const selectIds = ['w_house_id', 'garden_id', 'grade_id'];

    // Initialize Select2 for all select inputs
    selectIds.forEach(id => {
        $('#' + id).select2({
            width: '100%',
            placeholder: 'Select',
            allowClear: false
        });
    });

    // Automatically open Select2 on focus
    $(document).on('focusin', '.select2-container', function (e) {
        const $select = $(this).prev('select');
        if (!$select.data('select2').isOpen()) {
            $select.select2('open');
        }
    });

    // Handle Tab key navigation manually within Select2
    $(document).on('keydown', '.select2-search__field', function (e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const $container = $(this).closest('.select2-container');
            const next = $('[tabindex="' + (parseInt($container.attr('tabindex')) + 1) + '"]');
            $(this).closest('select').select2('close');
            next.focus();
        }
    });

    // After selecting an option, move focus to next tabindex element
    $(document).on('select2:close', 'select', function () {
        const tabIndex = parseInt($(this).next('.select2-container').attr('tabindex')) + 1;
        const $next = $('[tabindex="' + tabIndex + '"]');
        setTimeout(() => $next.focus(), 50);
    });

    // Call your tabindex setup function dynamically
    fn_change_tabindex_dynamically($('#lot_type').val());
});

// checkbox for edit bag / net wt bag
$(document).ready(function() {
    // Toggle for Bag
    $('#edit_gross_wt').change(function() {
        $('#gross_wt_per_bag').prop('readonly', !$(this).is(':checked'));
    });

    // Toggle for Net WT / Bag
    $('#edit_net_wt').change(function() {
        $('#net_wt_per_bag').prop('readonly', !$(this).is(':checked'));
    });
});

// =============================================
// CHANGED: Modified to accept edit_sale_no parameter
// =============================================
// Get Sale No
function get_invoice_entry_sale_no(edit_sale_no = null){
    // variable define
    var season = $("#season").val();
    var auction_center = $("#auction_center").val();
    
    // before response
    $('#load_sale_no').html("<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>");
    
    //use ajax to run the check
    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/get_invoice_entry_sale_no_data'); ?>",
        dataType : "JSON",
        data: { 
            season: season, 
            auction_center: auction_center,
            edit_sale_no: edit_sale_no  // CHANGED: Pass edit_sale_no to controller
        },
        success: function(data) {
            $('#load_sale_no').html(data);
            
            // If editing, manually trigger get_sale_date after dropdown is loaded
            if(edit_sale_no !== null) {
                setTimeout(function() {
                    if($('#sale_no').val() != '') {
                        get_sale_date();
                    }
                }, 300);
            }
        }
    });
    return false;
}
// =============================================
// END OF CHANGED FUNCTION
// =============================================

// Function to load sale numbers in the modal
function get_modal_sale_no() {
    var season = $("#modal_season").val();
    var auction_center = $("#modal_auction_center").val();
    
    if(season && auction_center) {
        $('#load_modal_sale_no').html("<img src='<?php echo base_url().'assets/img/loading.gif'; ?>' width='20'>");
        
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/get_modal_entry_sale_no_data'); ?>",
            data: { 
                season: season, 
                auction_center: auction_center 
            },
            success: function(htmlResponse) {
                // Directly insert the HTML response
                $('#load_modal_sale_no').html(htmlResponse);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#load_modal_sale_no').html('<select class="form-control input-sm"><option value="">Error loading data</option></select>');
            }
        });
    } else {
        $('#load_modal_sale_no').html('<select class="form-control input-sm"><option value="">Select Season and Auction Center first</option></select>');
    }
}

function saveSaleNo() {
    // collect modal values
    var modal_season = $('#modal_season').val();
    var modal_auction_center = $('#modal_auction_center').val();
    var modal_sale_no = $('#modal_sale_no').val();
    var last_update = "<?php print $ubl_user_name . ', Date: ' . $today . ', IP: ' . $ip; ?>";

    if(modal_sale_no == "" || modal_auction_center == "" || modal_season == ""){
        alert("Please select all fields before saving.");
        return;
    }

    // disable button while saving
    $('.btn-primary').prop('disabled', true).text('Saving...');

    $.ajax({
        type: "POST",
        url: "<?php echo site_url('MY_Controller/save_current_sale_no'); ?>",
        dataType: "JSON",
        data: {
            season: modal_season,
            auction_center: modal_auction_center,
            sale_no: modal_sale_no,
            last_update: last_update
        },
        success: function(data) {
            if (data == 1) {
                alert("Sale No saved successfully.");
            } else if (data == 2) {
                alert("Sale No updated successfully.");
            } else {
                alert(data);
            }

            // re-enable button and close modal
            $('.btn-primary').prop('disabled', false).text('Save');
            $('#saleNoModal').modal('hide');

            // reload page after small delay
            setTimeout(function() {
                location.reload();
            }, 500);
        },
        error: function() {
            alert("Error while saving Sale No.");
            $('.btn-primary').prop('disabled', false).text('Save');
        }
    });
}

// Initialize modal with current form values when opened
$('#saleNoModal').on('show.bs.modal', function (e) {
    // Set modal values to match current form values
    $("#modal_season").val($("#season").val());
    $("#modal_auction_center").val($("#auction_center").val());
    
    // Load sale numbers for the current selection
    get_modal_sale_no();
});

// Main tabindex assigner based on lot_type
function fn_change_tabindex_dynamically(lot_type) {
    
    if (lot_type === 'Fresh') {
        
        $('#old_sale_no, #old_lot_no, #ol_os_sc').removeAttr('tabindex');
        setSelect2Tabindex('w_house_id', 7);
        setSelect2Tabindex('garden_id', 8);
        $('#w_inv_no').attr('tabindex', 9);
        setSelect2Tabindex('grade_id', 10);
        $('#additional_grade').attr('tabindex', 11);
        $('#chest_number').attr('tabindex', 12);
        $('#gross_wt_per_bag').attr('tabindex', 13);
        $('#net_wt_per_bag').attr('tabindex', 14);
        $('#additional_sample_kgs').attr('tabindex', 15);
        $('#Submit').attr('tabindex', 16);
        
        $("#old_sale_no").prop("readonly", true);
        $("#old_sale_no").val('');
        $("#w_house_id").prop("disabled", false);
        $("#sample_kgs").prop("readonly", false);

    } else {
        
        $('#old_sale_no').attr('tabindex', 7);
        $('#old_lot_no').attr('tabindex', 8);
        $('#ol_os_sc').attr('tabindex', 9);
        $('#Submit').attr('tabindex', 10);
        $('#invoice_group_txt, #invoice_group, #invoice_group_upto').removeAttr('tabindex');
        removeSelect2Tabindex('w_house_id');
        removeSelect2Tabindex('grade_id');
        $("#old_sale_no").prop("readonly", false);
        $("#sample_kgs").prop("readonly", true);
        $("#sample_kgs").val('0.00');
        $("#w_house_id").prop("disabled", true);
    }
}

function removeSelect2Tabindex(selectId) {
    $('#' + selectId).next('.select2-container').removeAttr('tabindex');
}

// Assign tabindex to visible Select2 container
function setSelect2Tabindex(selectId, tabIndex) {
    const $select = $('#' + selectId);
    const $container = $select.next('.select2-container');

    $select.removeAttr('tabindex'); // hide original
    $container.attr('tabindex', tabIndex); // assign visible tabindex
    $container.find('.select2-selection').removeAttr('tabindex'); // avoid inner conflict
}

// update data
function get_update_data_invoice(setup_id,season,auction_center,catalouge_name,supplement_tea,category,chest_name,old_season,invoice,sale_no,sale_date,group_no,lot_type,lot_no,old_sale_no,old_season2,ol_ac,old_lot_no,ol_os_sc,w_house_id,garden_id,invoice_group_txt,invoice_group,invoice_group_upto,w_inv_no,grade_id,additional_grade,chest_number,gross_wt_per_bag,net_wt_per_bag,total_kgs,sample_kgs,additional_sample_kgs,weight_short,net_kgs,remarks,short_note,pre_invoice_id,org_id,reprinted_invoice,e_auc,lot_part,bulk_lot,account_concerned) {

    $('#form_mode').html('<font color=red> - (Update)</font>');
    $('#setup_id').val(setup_id);
    $('#season').val(season);
    $('#auction_center').val(auction_center);
    $('#catalouge_name').val(catalouge_name);
    $('#supplement_tea').val(supplement_tea);
    $('#category').val(category);
    $('#chest_name').val(chest_name);
    $('#old_season').val(old_season);
    $('#invoice').val(invoice);
    $('#sale_no').val(sale_no);
    $('#sale_date').val(sale_date);
    $('#group_no').val(group_no);
    $("#group_no").attr("readonly", false);
    $('#lot_type').val(lot_type);
    $('#lot_no').val(lot_no);
    $('#old_sale_no').val(old_sale_no);
    $('#old_season2').val(old_season2);
    $('#ol_ac').val(ol_ac);
    $('#old_lot_no').val(old_lot_no);
    $('#ol_os_sc').val(ol_os_sc);
    $("#ol_os_sc").prop("disabled",false);

    $('#invoice_group_txt').val(invoice_group_txt);
    $('#invoice_group').val(invoice_group);
    $('#invoice_group_upto').val(invoice_group_upto);
    $('#w_inv_no').val(w_inv_no);
    $('#additional_grade').val(additional_grade);
    $('#chest_number').val(chest_number);
    $('#gross_wt_per_bag').val(gross_wt_per_bag);
    $('#net_wt_per_bag').val(net_wt_per_bag);
    $('#total_kgs').val(total_kgs);
    $('#sample_kgs').val(sample_kgs);
    $('#additional_sample_kgs').val(additional_sample_kgs);
    (weight_short=='y') ? $('#weight_short').prop("checked", true) : '';
    $('#net_kgs').val(net_kgs);
    $('#remarks').val(remarks);
    $('#short_note').val(short_note);
    $('#pre_invoice_id').val(pre_invoice_id);
    $('#org_id').val(org_id);
    $('#reprinted_invoice').val(reprinted_invoice);
    (e_auc=='Yes') ? $('#e_auc').prop("checked", true) : '';
    (lot_part=='Yes') ? $('#lot_part').prop("checked", true) : '';
    (bulk_lot=='y') ? $('#bulk_lot').prop("checked", true) : '';
    (account_concerned=='Yes') ? $('#account_concerned').prop("checked", true) : '';
    $('html, body').animate({ scrollTop: $('#form_mode').offset().top }, 'slow');

    setTimeout(function() {
        if (setup_id && old_lot_no && ol_os_sc) {
            // To disable
            $('#w_house_id').val(w_house_id).trigger('change').prop('disabled', true);
            $('#garden_id').val(garden_id).trigger('change').prop('disabled', true);
            $('#grade_id').val(grade_id).trigger('change').prop('disabled', true);

            $('#w_inv_no, #additional_grade').prop('readonly', true);
        } else {
            // To enable
            $('#w_house_id').val(w_house_id).trigger('change').prop('disabled', false);
            $('#garden_id').val(garden_id).trigger('change').prop('disabled', false);
            $('#grade_id').val(grade_id).trigger('change').prop('disabled', false);

            $('#w_inv_no, #additional_grade').prop('readonly', false);
        }

        // callback
        fn_change_tabindex_dynamically(lot_type);
    }, 100);

}

// weight short
function fn_weight_short(){
    
    if($('#weight_short').is(':checked')){
        alert("বাগান থাকে ওজনে কম আসলে এই চেকবক্সে ক্লিক করে কম দেখাবেন । এই ওজনটি স্যাম্পল থাকে কাটা হবে না । ");
    }else{
        alert("এই ওজনটি স্যাম্পলের সাথে এডজাস্ট হবে । ");
    }    
}
</script>


<?php
// edit invoice
if(!empty($edit_id)){
    
// invoice entry data
$query = $this->db->query("SELECT * FROM invoice_entry WHERE id='".$edit_id."' ");
$row2 = $query->row_array();
?>
<!-- ============================================= -->
<!-- CHANGED: Edit script modified to pass edit_sale_no -->
<!-- ============================================= -->
<script>
    // Store edit values
    var editSaleNo = '<?php echo $row2['sale_no']; ?>';
    var editSaleDate = '<?php echo $row2['sale_date']; ?>';
    
    // First populate all fields
    get_update_data_invoice('<?php echo $row2['id']; ?>','<?php echo $row2['season']; ?>','<?php echo $row2['auction_center']; ?>','<?php echo $row2['catalouge_name']; ?>','<?php echo $row2['supplement_tea']; ?>','<?php echo $row2['category']; ?>','<?php echo $row2['chest_name']; ?>','<?php echo $row2['old_season']; ?>','<?php echo $row2['invoice']; ?>','<?php echo $row2['sale_no']; ?>','<?php echo $row2['sale_date']; ?>','<?php echo $row2['group_no']; ?>','<?php echo $row2['lot_type']; ?>','<?php echo $row2['lot_no']; ?>','<?php echo $row2['old_sale_no']; ?>','<?php echo $row2['old_season2']; ?>','<?php echo $row2['ol_ac']; ?>','<?php echo $row2['old_lot_no']; ?>','<?php echo $row2['ol_os_sc']; ?>','<?php echo $row2['w_house_id']; ?>','<?php echo $row2['garden_id']; ?>','<?php echo $row2['invoice_group_txt']; ?>','<?php echo $row2['invoice_group']; ?>','<?php echo $row2['invoice_group_upto']; ?>','<?php echo $row2['w_inv_no']; ?>','<?php echo $row2['grade_id']; ?>','<?php echo $row2['additional_grade']; ?>','<?php echo $row2['chest_number']; ?>','<?php echo $row2['gross_wt_per_bag']; ?>','<?php echo $row2['net_wt_per_bag']; ?>','<?php echo $row2['total_kgs']; ?>','<?php echo $row2['sample_kgs']; ?>','<?php echo $row2['additional_sample_kgs']; ?>','<?php echo $row2['weight_short']; ?>','<?php echo $row2['net_kgs']; ?>','<?php echo $row2['remarks']; ?>','<?php echo $row2['short_note']; ?>','<?php echo $row2['pre_invoice_id']; ?>','<?php echo $row2['org_id']; ?>','<?php echo $row2['reprinted_invoice']; ?>','<?php echo $row2['e_auc']; ?>','<?php echo $row2['lot_part']; ?>','<?php echo $row2['bulk_lot']; ?>','<?php echo $row2['account_concerned']; ?>');
    
    // Load sale numbers with edit mode - pass the sale_no from database
    $.when(get_invoice_entry_sale_no(editSaleNo)).then(function() {
        // Use multiple timeouts to ensure the date is set correctly
        setTimeout(function() {
            // Set sale number
            $('#sale_no').val(editSaleNo);
            // Force sale date from database
            $('#sale_date').val(editSaleDate);
        }, 100);
        
        // Extra safety: set again after any AJAX completes
        setTimeout(function() {
            $('#sale_no').val(editSaleNo);
            $('#sale_date').val(editSaleDate);
        }, 500);
        
        // Final safety: set again after everything loads
        setTimeout(function() {
            $('#sale_no').val(editSaleNo);
            $('#sale_date').val(editSaleDate);
        }, 1000);
    });
</script>
<!-- ============================================= -->
<!-- END OF CHANGED EDIT SCRIPT -->
<!-- ============================================= -->
<?php } ?>