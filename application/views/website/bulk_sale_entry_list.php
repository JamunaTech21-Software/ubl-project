<?php 
    // get html value
    $season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
    $auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
    $catalouge_name = (!empty($this->input->get('catalouge_name', TRUE))) ? $this->input->get('catalouge_name', TRUE) : '';
    $sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
    $lot_no = (!empty($this->input->get('lot_no', TRUE))) ? $this->input->get('lot_no', TRUE) : '';
    $lot_no_upto = (!empty($this->input->get('lot_no_upto', TRUE))) ? $this->input->get('lot_no_upto', TRUE) : '';
?>
<div class="table-responsive" style="height:500px;">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
    <tbody>
<?php	
    // query
    $this->db->select("id,sale_no,lot_no,chest_number"); 
    $this->db->from('invoice_entry');
    $this->db->where("lot_no BETWEEN $lot_no AND $lot_no_upto");
    $this->db->where('catalogue_status', 'prepared');
    $this->db->where('season', $season);
    $catalouge_name == "All" ? $this->db->where("catalouge_name !=", '') : $this->db->where('catalouge_name', $catalouge_name);	
    $this->db->where('sale_no', $sale_no);
    $this->db->where('auction_center', $auction_center);
    $this->db->where('soft_delete', 1);
    $this->db->order_by('lot_no ASC');
    $query = $this->db->get();
    $fetch_list = $query->result_array();
    foreach($fetch_list as $fetch){
?>
        <tr id="row_no<?php print $fetch['id']?>">
            <td class="text-center" style="background-color:#fcfc; color:white;">

                <input type="text" class="form-control input-sm" id="lot_no<?php print $fetch['id']?>" onkeydown="if (event.key === 'Tab') { fn_bulk_lot_parking('<?php print $fetch['id']?>'); }" onkeypress="return isNumberKey(this, event)" value="<?php print $fetch['lot_no']?>" style="width:70px;"/>

                <!-- hidden value -->
                <input type="hidden" class="form-control input-sm" id="lot_id<?php print $fetch['id']?>"/>
                <input type="hidden" class="form-control input-sm" id="net_wt_per_bag<?php print $fetch['id']?>" />
                <input type="hidden" class="form-control input-sm" id="sample_kgs<?php print $fetch['id']?>" />
                <input type="hidden" class="form-control input-sm" id="net_kgs<?php print $fetch['id']?>" />
                <!-- /hidden value -->
            </td>
            <td class="text-center" style="background-color:#fcfc; color:white;">
                <input readonly type="text" class="form-control input-sm" id="chest_number<?php print $fetch['id']?>" style="width:50px;" value="<?php print $fetch['chest_number']?>">
            </td>
            <td class="text-center" style="background-color:#fcfc; color:white;" title="Sold Price">
                <input type="text" class="form-control input-sm" maxlength="9" id="sold_price<?php print $fetch['id']?>" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" style="width:70px;"/>
            </td>
            <td class="text-center" style="background-color:#fcfc; color:white;" title="Complete Price">
                <input type="text" class="form-control input-sm" maxlength="9" id="complete_price<?php print $fetch['id']?>" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" style="width:70px;"/>
            </td>
            <!-- Buyer 1 -->
            <td class="text-center" style="background-color:#4169E1; color:white;" title="Buyer 1">
                <select id="buyer1<?php print $fetch['id']?>" class="form-control input-sm buyer_name" onchange="fn_bulk_buyer_type_hs('<?php print $fetch['id']?>',this.value,'Buyer1');" style="text-transform:uppercase; width:100px;">
                    <option value="">Select</option>
                    <?php foreach($buyer_data as $buyer_list){ ?>
                        <option value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td class="text-center" style="background-color:#4169E1; color:white;" title="Buyer 1">
                <input type="text" class="form-control input-sm" maxlength="2" id="buyer1_bag<?php print $fetch['id']?>" value="0" onkeyup="if(event.keyCode === 13){ bulk_sale_entry_save('<?php print $fetch['id']?>','Sale'); }else{ fn_chest_managemant1('<?php print $fetch['id']?>'); }" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" style="width:50px;"/>
            </td>
            <td class="text-center" style="background-color:#4169E1; color:white;" title="Buyer 1">
                <select id="buyer_type1<?php print $fetch['id']?>" class="form-control input-sm" style="width:60px;">
                    <option value="">I/E</option>
                    <option id="Internal_b1<?php print $fetch['id']?>" value="Internal">INT</option>
                    <option id="Export_b1<?php print $fetch['id']?>" value="Export">EXP</option>
                </select>
            </td>
            <td class="text-center" style="background-color:#4169E1; color:white;" title="Buyer 1">
                <select id="buyer_con1<?php print $fetch['id']?>" class="form-control input-sm" style="width:60px;">
                    <option value="">CD</option>
                    <option value="C-1" selected>C1</option>
                    <option value="C-2">C2</option>
                    <option value="C-3">C3</option>
                </select>
            </td>
            <!-- End Buyer 1 -->

            <!-- Buyer 2 -->
            <td class="text-center" style="background-color:#DA4E83; color:white;" title="Buyer 2">
                <select id="buyer2<?php print $fetch['id']?>" class="form-control input-sm buyer_name" onchange="fn_bulk_buyer_type_hs('<?php print $fetch['id']?>',this.value,'Buyer2');" style="text-transform:uppercase; width:100px;">
                    <option value="">Select</option>
                    <?php foreach($buyer_data as $buyer_list){ ?>
                        <option value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td class="text-center" style="background-color:#DA4E83; color:white;" title="Buyer 2">
                <input type="text" class="form-control input-sm" maxlength="2" id="buyer2_bag<?php print $fetch['id']?>" value="0" onkeyup="if(event.keyCode === 13){ bulk_sale_entry_save('<?php print $fetch['id']?>','Sale'); }else{ fn_chest_managemant2('<?php print $fetch['id']?>'); }" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" style="width:50px;"/>
            </td>
            <td class="text-center" style="background-color:#DA4E83; color:white;" title="Buyer 2">
                <select id="buyer_type2<?php print $fetch['id']?>" class="form-control input-sm" style="width:60px;">
                    <option value="">I/E</option>
                    <option id="Internal_b2<?php print $fetch['id']?>" value="Internal">INT</option>
                    <option id="Export_b2<?php print $fetch['id']?>" value="Export">EXP</option>
                </select>
            </td>
            <td class="text-center" style="background-color:#DA4E83; color:white;" title="Buyer 2">
                <select id="buyer_con2<?php print $fetch['id']?>" class="form-control input-sm" style="width:60px;">
                    <option value="">CD</option>
                    <option value="C-1" selected>C1</option>
                    <option value="C-2">C2</option>
                    <option value="C-3">C3</option>
                </select>
            </td>
            <!-- End Buyer 2 -->

            <!-- Buyer 3 -->
            <td class="text-center" style="background-color:#0077be; color:white;" title="Buyer 3">
                <select id="buyer3<?php print $fetch['id']?>" class="form-control input-sm buyer_name" onchange="fn_bulk_buyer_type_hs('<?php print $fetch['id']?>',this.value,'Buyer3');" style="text-transform:uppercase; width:100px;">
                    <option value="">Select</option>
                    <?php foreach($buyer_data as $buyer_list){ ?>
                        <option value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td class="text-center" style="background-color:#0077be; color:white;" title="Buyer 3">
                <input type="text" class="form-control input-sm" maxlength="2" id="buyer3_bag<?php print $fetch['id']?>" value="0" onkeyup="if(event.keyCode === 13){ bulk_sale_entry_save('<?php print $fetch['id']?>','Sale'); }" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" style="width:50px;"/>
            </td>
            <td class="text-center" style="background-color:#0077be; color:white;" title="Buyer 3">
                <select id="buyer_type3<?php print $fetch['id']?>" class="form-control input-sm" style="width:60px;">
                    <option value="">I/E</option>
                    <option id="Internal_b3<?php print $fetch['id']?>" value="Internal">INT</option>
                    <option id="Export_b3<?php print $fetch['id']?>" value="Export">EXP</option>
                </select>
            </td>
            <td class="text-center" style="background-color:#0077be; color:white;" title="Buyer 3">
                <select id="buyer_con3<?php print $fetch['id']?>" class="form-control input-sm" style="width:60px;">
                    <option value="">CD</option>
                    <option value="C-1" selected>C1</option>
                    <option value="C-2">C2</option>
                    <option value="C-3">C3</option>
                </select>
            </td>
            <!-- End Buyer 3 -->

            <td class="text-center">
                <b style="width:80px;"><i id="sold_status<?php print $fetch['id']?>">Status</i></b>
            </td>

            <td class="text-center">
                <input type="button" id="Submit<?php print $fetch['id']?>" value="Submit" class="btn btn-sm btn-success" onclick="bulk_sale_entry_save('<?php print $fetch['id']?>','Sale');" style="width:80px;"/>
            </td>

            <td class="text-center">
                <input type="button" id="Out_Submit<?php print $fetch['id']?>" value="Out Lot" class="btn btn-sm btn-danger" onclick="bulk_sale_entry_save('<?php print $fetch['id']?>','Out');" style="width:80px;"/>
            </td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>