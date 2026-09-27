<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$catalogue = (!empty($this->input->get('catalogue', TRUE))) ? $this->input->get('catalogue', TRUE) : '';
$warehouse_id = (!empty($this->input->get('warehouse_id', TRUE))) ? $this->input->get('warehouse_id', TRUE) : '';

## Sale schedule
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE sale_no='".$sale_no."' AND season_name='".$season."' AND auction_center='".$auction_center."' ");
$sale_sch_data = $query->row_array();
$sale_date = $sale_sch_data['sale_date'];

## catalog header
$query = $this->db->query("SELECT * FROM cover_page_info_setup WHERE soft_delete='1' ");
$result_header = $query->row_array();
$vat_ait_sample_info = $result_header['cover_page_text'];

## Get warehouse name if selected
$warehouse_name = '';
if($warehouse_id) {
    $query = $this->db->query("SELECT warehouse_name FROM warehouse_setup WHERE id='".$warehouse_id."'");
    $warehouse_data = $query->row_array();
    $warehouse_name = $warehouse_data['warehouse_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>After Sale Catalogue</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<style type="text/css">
table {border-collapse: collapse; width:90%; background-color:white; font-family:verdana; font-size:12px; table-layout:fixed; }
th, td { font-family:verdana; font-size:12px; border:0px solid; font-weight:bold; }
tbody tr td { padding: 10px; }
td { border-bottom: 0px solid; }
.thead { display: table-header-group; }

@media print {
	.page-break { page-break-after: always; }
	.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left; }
	.col-sm-12 { width: 100%; }
	.col-sm-11 { width: 91.66666667%; }
	.col-sm-10 { width: 83.33333333%; }
	.col-sm-9 { width: 75%; }
	.col-sm-8 { width: 66.66666667%; }
	.col-sm-7 { width: 58.33333333%; }
	.col-sm-6 { width: 50%; }
	.col-sm-5 { width: 41.66666667%; }
	.col-sm-4 { width: 33.33333333%; }
	.col-sm-3 { width: 25%; }
	.col-sm-2 { width: 16.66666667%; }
	.col-sm-1 { width: 8.33333333%; }
}
</style>
</head>
<body>
<?php
$leaf_tbag2 = 0;
$leaf_tkgs2 = 0;
$leaf_ttk2 = 0;
$catalouge_name = "Leaf";
$this->db->select("invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.chest_name, invoice_entry.category, GROUP_CONCAT(invoice_entry.w_inv_no) AS w_inv_no, invoice_entry.old_season, warehouse_setup.warehouse_name, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
if($warehouse_id) {
    $this->db->where('invoice_entry.w_house_id', $warehouse_id);
}
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
$leaf_catalogue = $query->result_array();
if(count($leaf_catalogue) > 0){
?>
<table>
<tr>
	<td style="text-align:center;"><b style="font-size:16px; text-transform: uppercase;"><?php print $website_title; ?></b> 	<br />
		<b style="font-size:14px; text-transform: uppercase;"><?php print $owner_short_address; ?></b> <br />
		<b style="font-size:14px; text-transform: uppercase;"><?php print $catalouge_name; ?> CATALOGUE</b>
		<?php if($warehouse_id) { ?>
		<br /><b style="font-size:14px; text-transform: uppercase;">WAREHOUSE: <?php echo $warehouse_name; ?></b>
		<?php } ?>
		<br />
		<b style="text-transform: uppercase; font-size:14px;">SEASON: <?php print $season; ?></b>
	</td>
</tr>	
<tr>
	<td style="font-family:verdana; font-size:14px; white-space:normal !important;"><?php print nl2br($vat_ait_sample_info); ?></td>
</tr>
</table>

<?php foreach($leaf_catalogue AS $row1){ ?>
<table>
<thead>
<tr title="Group No: <?php print $row1['group_no']; ?>">
	<th style="text-transform:uppercase; text-align:center;" colspan="11"><?php print $row1['garden_name']; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:left; padding:10px;" colspan="11">
		<?php print $row1['warehouse_name']; ?> &nbsp;&nbsp;&nbsp;<?php print $row1['category']; ?> <?php print $row1['chest_name']; ?> Inv: <?php print preg_replace('/[^0-9]/', ' ', $row1['w_inv_no']); ?>
	</th>
</tr>
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
</tr>
</thead>
<tbody>
<?php
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$leaf_tbag = 0;
$leaf_tkgs = 0;
$leaf_ttk = 0;

// query lot breakdown
$this->db->select("
	invoice_entry.lot_no, 
	invoice_entry.old_lot_no, 
	invoice_entry.old_sale_no, 
	invoice_entry.w_inv_no, 
	CONCAT(tea_grade_setup.grade_name,' ',invoice_entry.additional_grade) AS grade_name, 
	invoice_entry.gross_wt_per_bag, 
	invoice_entry.net_wt_per_bag, 
	invoice_entry.total_kgs, 
	invoice_entry.net_kgs, 
	invoice_entry.chest_number, 
	invoice_entry.valuation, 
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer1_bag,'0') AS buyer1_bag,
	IFNULL(sale_entry.buyer2_bag,'0') AS buyer2_bag,
	IFNULL(sale_entry.buyer3_bag,'0') AS buyer3_bag,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer1_amount,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer2_amount,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer3_amount,
	b1.short_key_code AS buyer1, 
	b2.short_key_code AS buyer2, 
	b3.short_key_code AS buyer3,
	(IFNULl(sale_entry.sold_price,invoice_entry.valuation) - invoice_entry.valuation) AS plus_minus,
	invoice_entry.remarks,
	(IFNULL(sale_entry.buyer1_bag,'0') + IFNULL(sale_entry.buyer2_bag,'0') + IFNULL(sale_entry.buyer3_bag,'0')) AS leaf_tbag,
	(IFNULL(sale_entry.buyer1_total_kgs,'0') + IFNULL(sale_entry.buyer2_total_kgs,'0') + IFNULL(sale_entry.buyer3_total_kgs,'0')) AS leaf_tkgs,
	(IFNULL(sale_entry.buyer1_amount,'0') + IFNULL(sale_entry.buyer2_amount,'0') + IFNULL(sale_entry.buyer3_amount,'0')) AS leaf_ttk
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
$this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
$this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
$this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
$this->db->where('invoice_entry.group_no', $row1['group_no']);
$this->db->where('invoice_entry.w_house_id',$row1['w_house_id']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>	
<tr>
	<td><?php print number_format($row2['valuation'],1,'.',''); ?></td>
	<td><?php print $row2['lot_no']; ?> <font color="red"><?php echo $row2['remarks']; ?></font></td>
	<td><?php print preg_replace('/[^0-9]/', ' ', $row2['w_inv_no']); ?></td>
	<td style="text-transform:uppercase; white-space:nowrap;"><?php print $row2['grade_name']; ?></td>
	<td><?php print number_format($row2['net_wt_per_bag'],1,'.',''); ?></td>
	<td style="text-align:right;"><?php print number_format($row2['net_kgs'],1,'.',''); ?></td>
	<td style="text-align:right;"><?php print $row2['chest_number']; ?></td>
	<td style="text-transform:uppercase; text-align;right;">
	<?php
		if($row2['buyer1_bag'] > 0){
			print ($row2['buyer1_bag'] == '0') ? "" : "<b style=\"color:#32CD32;\">".number_format($row2['sold_price'],1,'.','')."</b><br />";
			print ($row2['buyer2_bag'] == '0') ? "" : "<b style=\"color:#32CD32;\"></b><br />";
			print ($row2['buyer3_bag'] == '0') ? "" : "<b style=\"color:#32CD32;\"></b>";
		}else{
			print "<b style=\"color:#006994;\">Out</b>"; 
		}	
	?>
	</td>
	<td style="text-transform:uppercase;">
	<?php
		if($row2['buyer1_bag'] > 0){
			print ($row2['buyer1_bag'] == 0) ? "" : "<b style=\"color:#32CD32;\">$row2[buyer1]</b><br />";
			print ($row2['buyer2_bag'] == 0) ? "" : "<b style=\"color:#32CD32;\">$row2[buyer2]</b><br />";
			print ($row2['buyer3_bag'] == 0) ? "" : "<b style=\"color:#32CD32;\">$row2[buyer3]</b>";
		}	
	?>
	</td>
	<td style="text-transform:uppercase; text-align;left;">
	<?php
		if($row2['buyer1_bag'] > 0){
			print ($row2['buyer1_bag'] == 0) ? "" : "<b style=\"color:#32CD32;\">$row2[buyer1_bag]</b><br />";
			print ($row2['buyer2_bag'] == 0) ? "" : "<b style=\"color:#32CD32;\">$row2[buyer2_bag]</b><br />";
			print ($row2['buyer3_bag'] == 0) ? "" : "<b style=\"color:#32CD32;\">$row2[buyer3_bag]</b>";
		}	
	?>
	</td>
	<td style="text-transform:uppercase; text-align;left; white-space:nowrap;">
	<?php
		if($row2['buyer1_bag'] > 0){
			print ($row2['buyer1_bag'] == '0') ? "" : "<b style=\"color:#32CD32;\">".number_format($row2['buyer1_amount'],1,'.','')."</b><br />";
			print ($row2['buyer2_bag'] == '0') ? "" : "<b style=\"color:#32CD32;\">".number_format($row2['buyer2_amount'],1,'.','')."</b><br />";
			print ($row2['buyer3_bag'] == '0') ? "" : "<b style=\"color:#32CD32;\">".number_format($row2['buyer3_amount'],1,'.','')."</b>";
		}	
	?>
	</td>
</tr>
<?php 
$g_total_kgs += $row2['total_kgs'];
$g_net_kgs += $row2['net_kgs'];
$g_chest_number += $row2['chest_number'];
$leaf_tbag += $row2['leaf_tbag'];
$leaf_tkgs += $row2['leaf_tkgs'];
$leaf_ttk += $row2['leaf_ttk'];
} // lot foreach ?>
<tr>
	<td colspan="5"><b>TOTAL INV. KGS: <?php echo number_format($g_total_kgs,2,'.',''); ?></b></td>
	<td style="text-align:right;"><b><?php echo number_format($g_net_kgs,2,'.',''); ?></b></td>
	<td style="text-align:right;"><b><?php echo $g_chest_number; ?></b></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
</tbody>
</table>
<?php 
$leaf_tbag2 += $leaf_tbag;
$leaf_tkgs2 += $leaf_tkgs;
$leaf_ttk2 += $leaf_ttk;
} // group foreach 
?>
<div class="page-break" style="text-align:center;"><b>**** END OF LEAF CATALOGUE ****</b></div>
<?php } // end leaf catalogue ?>

<!-- Repeat the same warehouse filter for Dust and Supplement sections -->
<!-- Just add the warehouse filter condition in the initial query for each section like we did for Leaf -->

<!-- For Dust Catalogue -->
<?php
$dust_tbag2 = 0;
$dust_tkgs2 = 0;
$dust_ttk2 = 0;
$catalouge_name = "Dust";
$this->db->select("invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.chest_name, invoice_entry.category, GROUP_CONCAT(invoice_entry.w_inv_no) AS w_inv_no, invoice_entry.old_season, warehouse_setup.warehouse_name, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
if($warehouse_id) {
    $this->db->where('invoice_entry.w_house_id', $warehouse_id);
}
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
$dust_catalogue = $query->result_array();
if(count($dust_catalogue) > 0){
?>
<!-- Rest of Dust catalogue code remains the same, just add warehouse name in header like we did for Leaf -->
<?php } ?>

<!-- For Supplement Catalogue -->
<?php
$supp_tbag2 = 0;
$supp_tkgs2 = 0;
$supp_ttk2 = 0;
$catalouge_name = "Supplement";
$this->db->select("invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.chest_name, invoice_entry.category, GROUP_CONCAT(invoice_entry.w_inv_no) AS w_inv_no, invoice_entry.old_season, warehouse_setup.warehouse_name, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.published', 'Yes');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
if($warehouse_id) {
    $this->db->where('invoice_entry.w_house_id', $warehouse_id);
}
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
$supp_catalogue = $query->result_array();
if(count($supp_catalogue) > 0){
?>
<!-- Rest of Supplement catalogue code remains the same, just add warehouse name in header like we did for Leaf -->
<?php } ?>

<!-- Grand Total Table -->
<table>
<tr>
	<td style="text-align:center;"><b style="font-size:16px; text-transform: uppercase;"><?php print $website_title; ?></b> 	<br />
		<b style="font-size:14px; text-transform: uppercase;">Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp; Sale Date: <?php print date("d-m-Y",strtotime($sale_date)); ?></b> <br />
		<b style="font-size:14px; text-transform: uppercase;">CATALOGUE GRAND TOTAL</b>
		<?php if($warehouse_id) { ?>
		<br /><b style="font-size:14px; text-transform: uppercase;">WAREHOUSE: <?php echo $warehouse_name; ?></b>
		<?php } ?>
	</td>
</tr>
</table>

<table>
	<tr>
		<th style="text-align:right; border:1px solid;"></th>
		<th style="text-align:right; border:1px solid;">BAGS</th>
		<th style="text-align:right; border:1px solid;">KGS</th>
		<th style="text-align:right; border:1px solid;">AMOUNT</th>
	</tr>
	<tr>
		<th>LEAF</th>
		<th style="text-align:right; padding:5px;"><?php print $leaf_tbag2; ?></th>
		<th style="text-align:right; padding:5px;"><?php print number_format($leaf_tkgs2,2,'.',''); ?></th>
		<th style="text-align:right; padding:5px;"><?php print number_format($leaf_ttk2,2,'.',','); ?></th>
	</tr>
	<tr>
		<th>DUST</th>
		<th style="text-align:right; padding:5px;"><?php print $dust_tbag2; ?></th>
		<th style="text-align:right; padding:5px;"><?php print number_format($dust_tkgs2,2,'.',''); ?></th>
		<th style="text-align:right; padding:5px;"><?php print number_format($dust_ttk2,2,'.',','); ?></th>
	</tr>
	<tr>
		<th>SUPPLEMENT</th>
		<th style="text-align:right; padding:5px;"><?php print $supp_tbag2; ?></th>
		<th style="text-align:right; padding:5px;"><?php print number_format($supp_tkgs2,2,'.',''); ?></th>
		<th style="text-align:right; padding:5px;"><?php print number_format($supp_ttk2,2,'.',','); ?></th>
	</tr>
</table>

</body>
</html>