<?php  
// get method data
$garden_id = !empty($this->uri->segment('3')) ? $this->uri->segment('3') : '';
$grade_id = !empty($this->uri->segment('4')) ? $this->uri->segment('4') : '';
?>
<div id="printableArea" class="table-responsive">
<style>
.table{
	font-family:verdana;
	font-size:12px !important;
	width: 100% !important;
}
#table tbody tr:hover td {
  	background-color: #E9967A;
}
#table{
	border-color:black;
	white-space:nowrap;
}
#table tr td{
	border-color:black;
}
#table tr th{
	border-color:black;
}

@media print{
    .no-print, .no-print * { display: none !important; }
}

@page{
    size: auto;
}
</style>
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>
	<th style="text-align:center; vertical-align:middle;">Garden</th>
	<th style="text-align:center; vertical-align:middle;">Grade</th>
	<th style="text-align:center; vertical-align:middle;">Inv No</th>
	<th style="text-align:center; vertical-align:middle;">Inv Group</th>
	<th style="text-align:center; vertical-align:middle;">Sale No</th>
	<th style="text-align:center; vertical-align:middle;">Lot No</th>
	<th style="text-align:center; vertical-align:middle;">Group No</th>
	<th style="text-align:center; vertical-align:middle;">CTA</th>
	<th style="text-align:center; vertical-align:middle;">SCTA</th>
	<th style="text-align:center; vertical-align:middle;">Action</th>
	<th style="text-align:center; vertical-align:middle;">Category</th>
	<th style="text-align:center; vertical-align:middle;">Season</th>
	<th style="text-align:center; vertical-align:middle;">A/C</th>
	<th style="text-align:center; vertical-align:middle;">W/H</th>
	<th style="text-align:center; vertical-align:middle;">E & D</th>
	<th style="text-align:center; vertical-align:middle;">Bags</th>
	<th style="text-align:center; vertical-align:middle;">Gross WT-B</th>
	<th style="text-align:center; vertical-align:middle;">Net WT-B</th>
	<th style="text-align:center; vertical-align:middle;">Total Kgs</th>
	<th style="text-align:center; vertical-align:middle;">SMP DRW</th>
	<th style="text-align:center; vertical-align:middle;">Net Kgs</th>
	<th style="text-align:center; vertical-align:middle;">Chest</th>
	<th style="text-align:center; vertical-align:middle;">Sale Date</th>
	<th style="text-align:center; vertical-align:middle;">Old Season</th>
</tr>
</thead>
<tbody>
<?php
$total_net_kg = 0;
$total_no_of_bag = 0;
$total_gross_kg = 0;

// query
$this->db->select("pre_invoice_entry.id, pre_invoice_entry.garden_id, pre_invoice_entry.grade_id, pre_invoice_entry.w_house_id, pre_invoice_entry.system_id, pre_invoice_entry.gross_kg, pre_invoice_entry.tare_kg, pre_invoice_entry.net_kg, pre_invoice_entry.no_of_bag, pre_invoice_entry.invoice_group_txt, pre_invoice_entry.invoice_group, pre_invoice_entry.invoice_group_upto, pre_invoice_entry.challan_received_date, pre_invoice_entry.chest_name, pre_invoice_entry.w_inv_no, pre_invoice_entry.catalouge_name, pre_invoice_entry.supplement_tea, pre_invoice_entry.additional_grade, pre_invoice_entry.sale_no, pre_invoice_entry.lot_no, pre_invoice_entry.season, garden_and_company_setup.garden_name, tea_grade_setup.grade_name, warehouse_setup.short_key_word AS wh_sk, pre_invoice_entry.e_auc, pre_invoice_entry.group_no, pre_invoice_entry.category, pre_invoice_entry.total_kgs, pre_invoice_entry.sample_kgs, pre_invoice_entry.net_kgs, pre_invoice_entry.sale_date, pre_invoice_entry.old_season"); 
$this->db->from('pre_invoice_entry');
$this->db->join('warehouse_setup','pre_invoice_entry.w_house_id = warehouse_setup.id','left');
$this->db->join('garden_and_company_setup','pre_invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->join('tea_grade_setup', 'pre_invoice_entry.grade_id = tea_grade_setup.id', 'left');
!empty($garden_id) ? $this->db->where('pre_invoice_entry.garden_id', $garden_id) : '';
!empty($grade_id) ? $this->db->where('pre_invoice_entry.grade_id', $grade_id) : '';
$this->db->where('pre_invoice_entry.catalogue_status','final');
$this->db->where('pre_invoice_entry.catalogue_entry','no');
$this->db->where('pre_invoice_entry.soft_delete', 1);
$this->db->order_by('pre_invoice_entry.datetime ASC, pre_invoice_entry.system_id ASC');
$query = $this->db->get();
$result = $query->result_array();
foreach($result as $fetch){
?>
<tr id="system_row_id<?php echo $fetch['id']; ?>">
	<td style="text-align:left; vertical-align:middle;">
		<?php echo $fetch['garden_name']; ?>
		<input readonly type="hidden" id="garden_id<?php echo $fetch['id']; ?>" value="<?php echo $fetch['garden_id']; ?>">
	</td>

	<td style="text-align:left; vertical-align:middle;">
		<?php echo $fetch['grade_name']; ?><?php echo (!empty($fetch['additional_grade'])) ? $fetch['additional_grade'] : ''; ?>
		<input readonly type="hidden" id="grade_id<?php echo $fetch['id']; ?>" value="<?php echo $fetch['grade_id']; ?>">
		<input readonly type="hidden" id="additional_grade<?php echo $fetch['id']; ?>" value="<?php echo $fetch['additional_grade']; ?>">
		<input readonly type="hidden" id="account_concerned<?php echo $fetch['id']; ?>" value="No">
	</td>

	<td style="text-align:center; vertical-align:middle;">
		<?php echo $fetch['w_inv_no']; ?>
		<input readonly type="hidden" id="w_inv_no<?php echo $fetch['id']; ?>" value="<?php echo $fetch['w_inv_no']; ?>">
	</td>

	<td style="text-align:center; vertical-align:middle;">
		<div class="input-group" style="width:200px;">
			<input type="text" class="form-control input-sm text-uppercase" id="invoice_group_txt<?php echo $fetch['id']; ?>" name="invoice_group_txt" value="<?php print !empty($fetch['invoice_group_txt']) ? $fetch['invoice_group_txt'] : 'EX'; ?>" onkeyup="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')"/>
			<span class="input-group-addon">-</span>
			<input type="text" class="form-control input-sm text-uppercase" id="invoice_group<?php echo $fetch['id']; ?>" name="invoice_group" value="<?php print !empty($fetch['invoice_group']) ? $fetch['invoice_group'] : ''; ?>" onkeyup="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')" onkeypress="return isNumberKey(this, event)"/>
			<span class="input-group-addon">To</span>
			<input type="text" class="form-control input-sm text-uppercase" id="invoice_group_upto<?php echo $fetch['id']; ?>" name="invoice_group_upto" value="<?php print !empty($fetch['invoice_group_upto']) ? $fetch['invoice_group_upto'] : ''; ?>" onkeyup="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')" onkeypress="return isNumberKey(this, event)"/>
		</div>
	</td>

	<td style="text-align:left; vertical-align:middle;">
		<input type="number" class="form-control" style="width:70px; font-size:11px;" id="sale_no<?php echo $fetch['id']; ?>" value="<?php echo (!empty($fetch['sale_no'])) ? $fetch['sale_no'] : ''; ?>" autocomplete="off" onkeyup="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')" onblur="get_sale_date_invoice_parking('<?php echo $fetch['id']; ?>')"/>
	</td>

    <td style="text-align:left; vertical-align:middle;">
		<input type="number" class="form-control" style="width:80px; font-size:11px;" id="lot_no<?php echo $fetch['id']; ?>" value="<?php echo (!empty($fetch['lot_no'])) ? $fetch['lot_no'] : ''; ?>" autocomplete="off" onkeyup="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')"/>
	</td>

	<td style="text-align:left; vertical-align:middle;">
		<input type="number" class="form-control" id="group_no<?php echo $fetch['id']; ?>" name="group_no<?php echo $fetch['id']; ?>" onkeyup="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')" value="<?php echo (!empty($fetch['group_no'])) ? $fetch['group_no'] : ''; ?>" style="width:80px; font-size:11px;">
	</td>

	<td style="text-align:left; vertical-align:middle;">
		<select class="form-control" style="width:80px; font-size:11px;" id="catalouge_name<?php echo $fetch['id']; ?>" onchange="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')">
			<option value="">Select Catalogue</option>
			<?php foreach($catalogue_data as $catalogue_list){ ?>
				<option value="<?php print $catalogue_list['catalouge_name'] ; ?>" <?php if($fetch['catalouge_name']==$catalogue_list['catalouge_name']){?> selected <?php } ?>><?php print $catalogue_list['catalouge_name'] ; ?></option>
			<?php } //foreach ?>
		</select>
	</td>

    <td style="text-align:left; vertical-align:middle;">
		<div class="input-group input-group-sm">
			<select class="form-control" style="width:80px; font-size:11px;" id="supplement_tea<?php echo $fetch['id']; ?>" onchange="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')">
				<option value="">Select Suppliment</option>
				<?php foreach($s_catalogue_data as $s_catalogue_list){ ?>
					<option class="<?php print $s_catalogue_list['suppliment_tea'] ; ?>" value="<?php print $s_catalogue_list['suppliment_tea'] ; ?>" <?php if($fetch['supplement_tea']==$s_catalogue_list['suppliment_tea']){?> selected <?php } ?>><?php print $s_catalogue_list['suppliment_tea'] ; ?></option>
				<?php } //foreach ?>
			</select>
			<span class="input-group-addon">	
				<input type="checkbox" <?php echo ($fetch['e_auc']=='yes') ? 'checked' : ''; ?> name="e_auc<?php echo $fetch['id']; ?>" id="e_auc<?php echo $fetch['id']; ?>" value="E-Auc" onClick="fn_e_auction_sk2('<?php echo $fetch['id']; ?>')"/> E-Auc
			</span>
		</div>
	</td> 

	<td style="text-align:center; vertical-align:middle;" style="width:80px;">
		<input type="submit" class="btn btn-success btn-sm" id="submit<?php echo $fetch['id']; ?>" value="Save" onclick="fn_save_verify_ctl('<?php echo $fetch['id']; ?>')">
	</td>

	<td style="text-align:left; vertical-align:middle;">
		<select name="category<?php echo $fetch['id']; ?>" id="category<?php echo $fetch['id']; ?>" class="form-control" style="width:80px; font-size:11px;" onchange="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')">
			<option value="CTC" selected>CTC</option>
			<option value="GREEN TEA" <?php if($fetch['category']=="GREEN TEA"){ ?> selected <?php } ?>>GREEN TEA</option>
		</select>
	</td>

	<td style="text-align:left; vertical-align:middle;">
		<select class="form-control" style="width:80px; font-size:11px;" id="season<?php echo $fetch['id']; ?>" onchange="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')">
			<?php foreach($season_data as $season_list){ ?>
				<option value="<?php print $season_list['season_name'] ; ?>" <?php if($fetch['season']==$season_list['season_name']){?> selected <?php } ?>><?php print $season_list['season_name'] ; ?></option>
			<?php } //foreach ?>
		</select>
	</td>

	<td style="text-align:left; vertical-align:middle;">
		<select class="form-control" style="width:80px; font-size:11px;" id="auction_center<?php echo $fetch['id']; ?>" onchange="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')">
			<option value="">Select</option>
			<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sreemangal</option>
			<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Chattogram</option>
		</select>
	</td>

    <td style="text-align:left; vertical-align:middle;">
		<?php echo $fetch['wh_sk']; ?>
		<input readonly type="hidden" id="w_house_id<?php echo $fetch['id']; ?>" value="<?php echo $fetch['w_house_id']; ?>">
	</td>

	<td class="no-print" style="text-align:center; vertical-align:middle;">
		<a target="blink" title="Edit" href="<?php echo site_url('MY_Controller/pre_invoice_entry/'.$fetch['id']); ?>" style="color:green;"><i class="glyphicon glyphicon-edit"></i></a>
		&nbsp;&nbsp;&nbsp;
		<span style="cursor:pointer; color:red;" title="Delete" class="glyphicon glyphicon-trash" onclick="delete_pre_inv_cart('<?php echo $fetch['id']; ?>');"></span>
	</td>

	<td style="text-align:right; vertical-align:middle;">
		<?php echo number_format($fetch['no_of_bag'],2,'.',''); ?>
		<input readonly type="hidden" id="chest_number<?php echo $fetch['id']; ?>" value="<?php echo $fetch['no_of_bag']; ?>">
	</td>

    <td style="text-align:right; vertical-align:middle;">
		<?php echo number_format($fetch['gross_kg'],2,'.',''); ?>
		<input readonly type="hidden" id="gross_wt_per_bag<?php echo $fetch['id']; ?>" value="<?php echo $fetch['gross_kg']; ?>">
	</td>

    <td style="text-align:right; vertical-align:middle;">
		<?php echo number_format($fetch['net_kg'],2,'.',''); ?>
		<input readonly type="hidden" id="net_wt_per_bag<?php echo $fetch['id']; ?>" value="<?php echo $fetch['net_kg']; ?>">
	</td>

    <td style="text-align:right; vertical-align:middle;">
		<input readonly type="number" step="0.00001" class="form-control" id="total_kgs<?php echo $fetch['id']; ?>" name="total_kgs<?php echo $fetch['id']; ?>" value="<?php echo number_format($fetch['net_kg'] * $fetch['no_of_bag'],2,'.',''); ?>" style="width:80px; font-size:11px;">
	</td>

	<td style="text-align:center; vertical-align:middle;">
		<input readonly type="number" step="0.00001" class="form-control" id="sample_kgs<?php echo $fetch['id']; ?>" name="sample_kgs<?php echo $fetch['id']; ?>" value="<?php echo (!empty($fetch['sample_kgs'])) ? $fetch['sample_kgs'] : '0'; ?>" onfocus="if(this.value==0) this.value=''" onblur="if(this.value=='') this.value='0'" style="width:80px; font-size:11px;"/>
	</td>

	<td style="text-align:center; vertical-align:middle;">
		<input readonly type="number" step="0.00001" class="form-control" id="net_kgs<?php echo $fetch['id']; ?>" name="net_kgs<?php echo $fetch['id']; ?>" value="<?php echo (!empty($fetch['net_kgs'])) ? $fetch['net_kgs'] : ''; ?>" style="width:80px; font-size:11px;">
	</td>

	<td style="text-align:center; vertical-align:middle;">
		<?php echo $fetch['chest_name']; ?>
		<input readonly type="hidden" id="chest_name<?php echo $fetch['id']; ?>" value="<?php echo $fetch['chest_name']; ?>">
	</td>

	<td style="text-align:center; vertical-align:middle;"><input type="date" readonly class="form-control" id="sale_date<?php echo $fetch['id']; ?>" name="sale_date<?php echo $fetch['id']; ?>" value="<?php echo (!empty($fetch['sale_date'])) ? $fetch['sale_date'] : ''; ?>" style="width:110px; font-size:11px;"></td>

	<td style="text-align:center; vertical-align:middle;">
		<select class="form-control" id="old_season<?php echo $fetch['id']; ?>" name="old_season<?php echo $fetch['id']; ?>" style="width:80px; font-size:11px;" onchange="invoice_verify_sg_hide_show('<?php echo $fetch['id']; ?>')">

			<?php foreach($season_data as $season_list){ ?>
				<option value="<?php print $season_list['season_name'] ; ?>" <?php if($fetch['old_season']==$season_list['season_name']){?> selected <?php } ?>><?php print $season_list['season_name'] ; ?></option>
			<?php } //foreach ?>

		</select>


		<select id="old_season2<?php echo $fetch['id']; ?>" class="form-control" style="width:80px; font-size:11px; display:none;">
			<?php foreach($season_data as $season_list){ ?>
				<option value="<?php print $season_list['season_name'] ; ?>" <?php if($fetch['old_season2']==$season_list['season_name']){?> selected <?php } ?>><?php print $season_list['season_name'] ; ?></option>
			<?php } //foreach ?>
		</select>
		<select id="ol_ac<?php echo $fetch['id']; ?>" class="form-control" style="width:80px; font-size:11px; display:none;">
			<option <?php if($default_ac=="Sreemangal"){ ?> selected <?php } ?> value="Sreemangal">Sre</option>
			<option <?php if($default_ac=="Chattogram"){ ?> selected <?php } ?> value="Chattogram">Ctg</option>
		</select>

		<input type="hidden" readonly class="form-control" id="old_sale_no<?php echo $fetch['id']; ?>"/>
		<input type="hidden" readonly class="form-control" id="old_lot_no<?php echo $fetch['id']; ?>"/>	
	</td>
</tr>
<?php
	$total_net_kg += $fetch['net_kg'];
	$total_no_of_bag += $fetch['no_of_bag'];
	$total_gross_kg += $fetch['gross_kg'];
	
} // End loop	
?>
<tr>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th style="text-align:right; vertical-align:middle;"><?php echo number_format($total_no_of_bag,2,'.',''); ?></th>
	<th style="text-align:right; vertical-align:middle;"><?php echo number_format($total_gross_kg,2,'.',''); ?></th>
	<th style="text-align:right; vertical-align:middle;"><?php echo number_format($total_net_kg,2,'.',''); ?></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
</tr>
</tbody>
</table>
</div>