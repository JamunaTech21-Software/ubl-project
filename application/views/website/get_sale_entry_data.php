<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '0';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '0';
$lot_no = (!empty($this->input->get('lot_no', TRUE))) ? $this->input->get('lot_no', TRUE) : '0';
?>

<!-- fixed value -->
<input required type="hidden" class="form-control" name="user" id="user" value="<?php print $ubl_user_name; ?>">
<input required type="hidden" class="form-control" name="last_update" id="last_update" value="<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>">
<!-- End fixed data -->

<div class="container-fluid">
<div class="table-responsive">
	<table class="table table-sm">
		<tr>
			<th style="font-family: verdana; font-size:18px; text-align:center; border:0px !important;"> <?php print $website_title; ?> </th> 
		</tr>
		<tr>
			<th style="font-family: verdana; font-size:16px; text-align:center; border:0px !important;"> Broker Sale Entry </th> 
		</tr>
		<tr>
			<td style="font-family: courier; font-size:16px; text-align:center; border:0px !important;">Season: <?php print $season; ?> / <?php print $auction_center; ?> / Sale No: <?php print $sale_no; ?> (Lot No: <?php print $lot_no; ?>)</td>
		</tr>
	</table>
</div>

<style>
.container-fluid{
	background-color:white;
}
.table{
	font-family:verdana;
	width: 100% !important;
	white-space:nowrap;
}
#cart_data{
	border-color:black;
}
#cart_data tr td{
	border-color:black;
	font-size:12px !important;
	padding:5px;
}
#cart_data tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
</style>

<div class="table-responsive" style="height:450px;">
<table id="cart_data" class="table table-sm table-bordered table-hover">
<thead>
	<tr>
		<th style="text-align:center; vertical-align:middle;">Lot No</th>
		<th style="text-align:center; vertical-align:middle;">Garden</th>
		<th style="text-align:center; vertical-align:middle; display:none;">Smpl Kgs</th>
		<th style="text-align:center; vertical-align:middle;">Pcks</th>
		<th style="text-align:center; vertical-align:middle;">Value</th>
		<th style="text-align:center; vertical-align:middle;">Sold Price</th>
		<th style="text-align:center; vertical-align:middle;">Bags</th>
		<th style="text-align:center; vertical-align:middle;">Buyer 1</th>
		<th style="text-align:center; vertical-align:middle;">Type</th>
		<th style="text-align:center; vertical-align:middle;">Bags</th>
		<th style="text-align:center; vertical-align:middle;">Buyer 2</th>
		<th style="text-align:center; vertical-align:middle;">Type</th>
		<th style="text-align:center; vertical-align:middle;">Bags</th>
		<th style="text-align:center; vertical-align:middle;">Buyer 3</th>
		<th style="text-align:center; vertical-align:middle;">Type</th>
		<th style="text-align:center; vertical-align:middle;">Process</th>
		<th style="text-align:center; vertical-align:middle;">Details</th>
	</tr>
</thead> 
<tbody>
<?php
$sl = 1;
$g_chest_number = 0;

// query
$this->db->select("
	invoice_entry.id, 
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no, 
	invoice_entry.gross_wt_per_bag, 
	invoice_entry.net_wt_per_bag, 
	invoice_entry.total_kgs, 
	invoice_entry.net_kgs, 
	invoice_entry.chest_number, 
	invoice_entry.valuation, 
	invoice_entry.sample_kgs, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	warehouse_setup.short_key_word AS wh_name, 
	garden_and_company_setup.garden_name,
	sale_entry.sold_price,
	sale_entry.buyer1,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer1_bag,
	sale_entry.buyer2,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer2_bag,
	sale_entry.buyer3,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer3_bag
"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join("(SELECT invoice_id, sold_price, buyer1, buyer1_bag, buyer2, buyer2_bag, buyer3, buyer3_bag FROM sale_entry GROUP BY invoice_id) sale_entry", "invoice_entry.id = sale_entry.invoice_id", "left");
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.published IS NOT NULL', NULL,FALSE);
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.lot_no', $lot_no);
$this->db->where('invoice_entry.soft_delete', 1);
$query = $this->db->get();
if($query->num_rows() > 0){
foreach($query->result_array() as $row2){
?>

	<tr id="row_id<?php echo $row2['id']; ?>">
		
		<td style="text-align:center; vertical-align:middle;"><?php echo $row2['lot_no']; ?></td>
		<td style="text-align:center; vertical-align:middle;"><?php echo $row2['garden_name']; ?></td>
		<td style="text-align:right; vertical-align:middle; display:none;"><?php echo $row2['sample_kgs']; ?></td>
		<td id="chest_number<?php echo $row2['id']; ?>" style="text-align:center; vertical-align:middle;"><?php echo $row2['chest_number']; ?></td>
		<td style="text-align:center; vertical-align:middle;"><?php echo $row2['valuation']; ?></td>
		<td style="text-align:center; vertical-align:middle;">
			<input type="text" class="form-control input-sm" maxlength="3" id="sold_price<?php echo $row2['id']; ?>" style="width:80px;" value="<?php print $row2['sold_price']; ?>" onkeypress="return isNumberKey(this, event)" onfocus="if(this.value == '0') { this.value = ''; } this.select();">
		</td>
		
		<!-- Buyer 1 -->
		<td style="text-align:center; vertical-align:middle; background-color:#4169E1;">
			<input type="text" class="form-control input-sm" maxlength="2" id="buyer1_bag<?php echo $row2['id']; ?>" style="width:50px;" onkeypress="return isNumberKey(this, event)" value="<?php print $row2['buyer1_bag']; ?>" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" />
		</td>
		<td style="text-align:center; vertical-align:middle; background-color:#4169E1;">
			<select id="buyer1<?php echo $row2['id']; ?>" class="form-control input-sm chosen">
					<option value="">--</option>
				<?php foreach($buyer_data as $buyer_list){ ?>
					<option <?php print ($buyer_list['id'] == $row2['buyer1']) ? 'selected' : ''; ?> value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
				<?php } ?>
			</select>
		</td>
		<td style="text-align:center; vertical-align:middle; background-color:#0077be;">
			<select id="buyer_type1<?php echo $row2['id']; ?>" class="form-control input-sm">
					<option value="Internal">Internal</option>
					<option value="Export">Export</option>
					<option value="Both">Both</option>
			</select>
		</td>
		
		<!-- Buyer 2 -->
		<td style="text-align:center; vertical-align:middle; background-color:#DA4E83;">
			<input type="text" class="form-control input-sm" maxlength="2" id="buyer2_bag<?php echo $row2['id']; ?>" style="width:50px;" onkeypress="return isNumberKey(this, event)" value="<?php print $row2['buyer2_bag']; ?>" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }" />
		</td>
		<td style="text-align:center; vertical-align:middle; background-color:#DA4E83;">
			<select id="buyer2<?php echo $row2['id']; ?>" class="form-control input-sm chosen">
					<option value="">--</option>
				<?php foreach($buyer_data as $buyer_list){ ?>
					<option <?php print ($buyer_list['id'] == $row2['buyer2']) ? 'selected' : ''; ?> value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
				<?php } ?>
			</select>
		</td>
		<td style="text-align:center; vertical-align:middle; background-color:#0077be;">
			<select id="buyer_type2<?php echo $row2['id']; ?>" class="form-control input-sm">
					<option value="Internal">Internal</option>
					<option value="Export">Export</option>
					<option value="Both">Both</option>
			</select>
		</td>
		
		<!-- Buyer 3 -->
		<td style="text-align:center; vertical-align:middle; background-color:#0077be;">
			<input type="text" class="form-control input-sm" maxlength="2" id="buyer3_bag<?php echo $row2['id']; ?>" style="width:50px;" onkeypress="return isNumberKey(this, event)" value="<?php print $row2['buyer3_bag']; ?>" onfocus="if(this.value == '0') { this.value = ''; } this.select();" onblur="if(this.value == '') { this.value = '0'; }">
		</td>
		<td style="text-align:center; vertical-align:middle; background-color:#0077be;">
			<select id="buyer3<?php echo $row2['id']; ?>" class="form-control input-sm">
					<option value="">--</option>
				<?php foreach($buyer_data as $buyer_list){ ?>
					<option <?php print ($buyer_list['id'] == $row2['buyer3']) ? 'selected' : ''; ?> value="<?php print $buyer_list['id']; ?>"><?php print $buyer_list['short_key_code']; ?></option>
				<?php } ?>
			</select>
		</td>
		<td style="text-align:center; vertical-align:middle; background-color:#0077be;">
			<select id="buyer_type3<?php echo $row2['id']; ?>" class="form-control input-sm">
					<option value="Internal">Internal</option>
					<option value="Export">Export</option>
					<option value="Both">Both</option>
			</select>
		</td>
		
		<td><input type="button" value="Submit" onclick="sale_entry_save('<?php echo $row2['id']; ?>');" /></td>
		
		<td style="text-align:center; vertical-align:middle;">
			<b style="cursor:pointer;" data-toggle="popover" data-placement="bottom" data-content="<?php echo $row2['grade_name']; ?> / <?php echo $row2['w_inv_no']; ?> / <?php echo $row2['wh_name']; ?> / <?php print $row2['net_wt_per_bag']; ?> / <?php echo $row2['total_kgs']; ?> / <?php echo $row2['net_kgs']; ?>">View</b>
		</td>
	</tr>

<?php
	$g_chest_number += $row2['chest_number'];
} // foreach
?>

<tr>
	<th style="text-align:right; vertical-align:middle;" colspan="2"> Total : </th>
	<th style="text-align:center; vertical-align:middle;"><?php echo $g_chest_number; ?></th>
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
</tr>

<?php
}else{ 
	print "<tr><td colspan=\"16\">No matching record found.</td></tr>"; 
}
?>
</tbody>
</table>
</div>
</div> 
<!-- container --> 

<script>
$(function () {
	$('[data-toggle="popover"]').popover();
});

// popover dismiss on click outside
$("html").on("mouseup", function (e) {
    var l = $(e.target);
    if (l[0].className.indexOf("popover") == -1) {
        $(".popover").each(function () {
            $(this).popover("hide");
        });
    }
});
// end
</script>