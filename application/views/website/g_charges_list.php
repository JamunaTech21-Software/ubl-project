<?php
$season = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : '';
$auction_center = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : '';
$sale_no = (!empty($this->uri->segment(5))) ? $this->uri->segment(5) : '0';
$garden_id = (!empty($this->uri->segment(6))) ? $this->uri->segment(6) : '';

## garden details
$query = $this->db->query("SELECT * FROM garden_and_company_setup WHERE id='".$garden_id."' ");
$garden_data = $query->row_array();
?>

<style>
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
}
#table tr th{
	border-color:black;
}
</style>

<div class="table-responsive">
<table class="table table-sm table-bordered" id="table">
<thead>
<tr>
	<td colspan="6" style="color:red; text-transform:uppercase; text-align:left; vertical-align:middle;"><?php print $season; ?> / <?php print $auction_center; ?> / <?php print $sale_no; ?> / <?php print $garden_data['garden_name']; ?></td>
</tr>
<tr>
    <th style="color:green; text-align:center; vertical-align:middle;">Edit</th>
	<th style="text-align:center; vertical-align:middle;">SL</th>
	<th style="text-align:center; vertical-align:middle;">Old Season</th>
    <th style="text-align:center; vertical-align:middle;">Charges Name</th>
	<th style="text-align:center; vertical-align:middle;">Charges Amount</th>
	<th style="text-align:center; vertical-align:middle;">Note</th>
	<th style="color:red; text-align:center; vertical-align:middle;">Delete</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;
// query
$this->db->select("*"); 
$this->db->from('garden_adjust_tbl');
$this->db->where('season',$season);
$this->db->where('auction_center',$auction_center);
$this->db->where('sale_no',$sale_no);
$this->db->where('garden_id',$garden_id);
$query = $this->db->get();
foreach($query->result_array() as $row){
?>	
<tr>
    <td style="color:green; text-align:center; vertical-align:middle;">
		<span style="cursor:pointer; color:green;" onclick="get_update_data_invoice('<?php echo $row['id']; ?>','<?php echo $row['season']; ?>','<?php echo $row['old_season']; ?>','<?php echo $row['auction_center']; ?>','<?php echo $row['sale_no']; ?>','<?php echo $row['garden_id']; ?>','<?php echo $row['charges_name']; ?>','<?php echo $row['charge_tk']; ?>','<?php echo $row['particular']; ?>','<?php echo $row['user']; ?>','<?php echo $row['last_update']; ?>')" class="glyphicon glyphicon-edit"></span>
	</td>
	<td style="text-align:center; vertical-align:middle;"><?php print $sl++; ?></td>
    <td style="text-align:left; vertical-align:middle;"><?php print $row['old_season']; ?></td>
    <td style="text-align:left; vertical-align:middle;"><?php print $row['charges_name']; ?></td>
	<td style="text-align:right; vertical-align:middle;"><?php print number_format($row['charge_tk'],2,'.',''); ?></td>
	<td style="text-align:left; vertical-align:middle;"><?php print $row['particular']; ?></td>
	<td style="color:red; text-align:center; vertical-align:middle; cursor:pointer;" onclick="fn_delete_garden_adjust('<?php print $row['id']; ?>','<?php print $row['season']; ?>','<?php print $row['auction_center']; ?>','<?php print $row['sale_no']; ?>','<?php print $row['garden_id']; ?>');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>