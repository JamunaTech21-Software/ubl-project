<?php
$edit_id = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : '0';

if(isset($resultq1) && empty($edit_id)){
?>

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
</style>

<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>
    <th colspan="14"><?php print $resultq1['garden_name']; ?> / <?php print $resultq1['wh_sk']; ?> / <?php echo $resultq1['invoice_group_txt']; ?> <?php echo $resultq1['invoice_group']; ?><?php echo (!empty($resultq1['invoice_group_upto'])) ? '-'.$resultq1['invoice_group_upto'] : ''; ?> / <?php print $resultq1['catalouge_name']; ?> / <?php print $resultq1['supplement_tea']; ?> / <?php print $resultq1['chest_name']; ?> / <?php print $resultq1['category']; ?> / <?php print $resultq1['sale_no']; ?> / <?php print $resultq1['season']; ?> / <?php print $resultq1['invoice']; ?> <span style="color:red;"><?php print $resultq1['old_season']; ?></span></th>
</tr>
<tr>
    <th style="color:green; text-align:center; vertical-align:middle;">Edit</th>
	<th style="text-align:center; vertical-align:middle;">SL</th>
    <th style="text-align:center; vertical-align:middle;">Lot No</th>
    <th style="text-align:center; vertical-align:middle;">O/S</th>
    <th style="text-align:center; vertical-align:middle;">O/L</th>
    <th style="text-align:center; vertical-align:middle;">Invoice</th>
    <th style="text-align:center; vertical-align:middle;">Grade Name</th>
	<th style="text-align:center; vertical-align:middle;">WT P/Bag</th>
	<th style="text-align:center; vertical-align:middle;">Net WT P/Bag</th>
	<th style="text-align:center; vertical-align:middle;">Total Kgs</th>
	<th style="text-align:center; vertical-align:middle;">Net Kgs</th>
	<th style="text-align:center; vertical-align:middle;">Chest</th>
	<th style="text-align:center; vertical-align:middle;">Remarks</th>
</tr>
</thead>  
<tbody>
<?php
$sl = 1;
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$sale_no = $resultq1['sale_no'];
$sale_date = $resultq1['sale_date'];
$w_house_id = $resultq1['w_house_id'];
$garden_id = $resultq1['garden_id'];
$invoice_group_txt = $resultq1['invoice_group_txt'];
$invoice_group = $resultq1['invoice_group'];
$invoice_group_upto = $resultq1['invoice_group_upto'];

// query
$this->db->select("invoice_entry.*, CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.page_no'); // is null
$this->db->where('invoice_entry.user',$ubl_web_user_id);
$this->db->where('invoice_entry.sale_no',$sale_no);
$this->db->where('invoice_entry.sale_date',$sale_date);
$this->db->where('invoice_entry.w_house_id',$w_house_id);
$this->db->where('invoice_entry.garden_id',$garden_id);
$this->db->where('invoice_entry.invoice_group_txt',$invoice_group_txt);
$this->db->where('invoice_entry.invoice_group',$invoice_group);
$this->db->where('invoice_entry.invoice_group_upto',$invoice_group_upto);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.id DESC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
?>

<tr id="lot_no<?php echo $row2['lot_no']; ?>">

    <td style="text-align:center; vertical-align:middle;">
		<span style="cursor:pointer; color:green;" onclick="get_update_data_invoice('<?php echo $row2['id']; ?>','<?php echo $row2['season']; ?>','<?php echo $row2['auction_center']; ?>','<?php echo $row2['catalouge_name']; ?>','<?php echo $row2['supplement_tea']; ?>','<?php echo $row2['category']; ?>','<?php echo $row2['chest_name']; ?>','<?php echo $row2['old_season']; ?>','<?php echo $row2['invoice']; ?>','<?php echo $row2['sale_no']; ?>','<?php echo $row2['sale_date']; ?>','<?php echo $row2['group_no']; ?>','<?php echo $row2['lot_type']; ?>','<?php echo $row2['lot_no']; ?>','<?php echo $row2['old_sale_no']; ?>','<?php echo $row2['old_season2']; ?>','<?php echo $row2['ol_ac']; ?>','<?php echo $row2['old_lot_no']; ?>','<?php echo $row2['ol_os_sc']; ?>','<?php echo $row2['w_house_id']; ?>','<?php echo $row2['garden_id']; ?>','<?php echo $row2['invoice_group_txt']; ?>','<?php echo $row2['invoice_group']; ?>','<?php echo $row2['invoice_group_upto']; ?>','<?php echo $row2['w_inv_no']; ?>','<?php echo $row2['grade_id']; ?>','<?php echo $row2['additional_grade']; ?>','<?php echo $row2['chest_number']; ?>','<?php echo $row2['gross_wt_per_bag']; ?>','<?php echo $row2['net_wt_per_bag']; ?>','<?php echo $row2['total_kgs']; ?>','<?php echo $row2['sample_kgs']; ?>','<?php echo $row2['additional_sample_kgs']; ?>','<?php echo $row2['weight_short']; ?>','<?php echo $row2['net_kgs']; ?>','<?php echo $row2['remarks']; ?>','<?php echo $row2['short_note']; ?>','<?php echo $row2['pre_invoice_id']; ?>','<?php echo $row2['org_id']; ?>','<?php echo $row2['reprinted_invoice']; ?>','<?php echo $row2['e_auc']; ?>','<?php echo $row2['lot_part']; ?>','<?php echo $row2['bulk_lot']; ?>','<?php echo $row2['account_concerned']; ?>')" class="glyphicon glyphicon-edit"></span>
	</td>
	
	<td style="text-align:center; vertical-align:middle;"><?php echo $sl++; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $row2['lot_no']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $row2['old_sale_no']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $row2['old_lot_no']; ?></td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase;"><?php echo $row2['w_inv_no']; ?></td>
	<td style="text-align:center; vertical-align:middle; text-transform:uppercase;"><?php echo $row2['grade_name']; ?></td>
	<td style="text-align:right; vertical-align:middle;"><?php echo number_format($row2['gross_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right; vertical-align:middle;"><?php echo number_format($row2['net_wt_per_bag'],2,'.',''); ?></td>
	<td style="text-align:right; vertical-align:middle;"><?php echo number_format($row2['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; vertical-align:middle;"><?php echo number_format($row2['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; vertical-align:middle;"><?php echo $row2['chest_number']; ?></td>
	<td style="text-align:center; vertical-align:middle; color:red;"><?php echo $row2['remarks']; ?></td>
	
</tr>	

<?php
	$g_total_kgs += $row2['total_kgs'];
	$g_net_kgs += $row2['net_kgs'];
	$g_chest_number += $row2['chest_number'];
	
} // second loop	
?>

<tr>
	<th style="text-align:right; vertical-align:middle;" colspan="9"> Total : </th>
	<th style="text-align:right; vertical-align:middle;"><?php echo number_format($g_total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; vertical-align:middle;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; vertical-align:middle;"><?php echo $g_chest_number; ?></th>
	<th></th>
</tr>
</tbody>
</table>
</div>

<?php
}else{
	// do nothing
}	
?>