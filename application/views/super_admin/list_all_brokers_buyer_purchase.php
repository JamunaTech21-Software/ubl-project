<?php
if(isset($bill_info)){ 
?>
<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
}
.table{
	border-color:black;
}
.table thead tr th{
	border-color:black;
	vertical-align:middle;
	text-align:center;
}
.table tbody tr td{
	border-color:black;
	vertical-align:middle;
	text-align:center;
}
</style>

<div class="alert alert-dark" role="alert">
    <b>List Of  All Brokers Buyer Purchase</b>
</div>

<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of All Brokers Buyer Purchase</caption>
<thead class="thead-light">
<tr>
    <th>Season</th>
	<th>Sale No.</th>
    <th>Brokers</th>
    <th>Buyer</th>
    <th>Leaf Bags</th>
    <th>Leaf Kgs</th>
    <th>Dust Bags</th>
    <th>Dust Kgs</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$sl = 1;
foreach($bill_info as $bill_data){
?>    

<tr class="table-<?php echo $bill_data['data_status']; ?>">
	
	<td><?php echo $bill_data['season']; ?></td>
	<td><?php echo $bill_data['sale_no']; ?></td>
    <td style="text-transform:uppercase;"><?php echo $bill_data['brokers_name']; ?></td>
    <td style="text-transform:uppercase;"><?php echo $bill_data['buyer_name']; ?></td>
    <td style="text-align:center;"><?php echo $bill_data['leaf_bags']; ?></td>
    <td style="text-align:center;"><?php echo $bill_data['leaf_kgs']; ?></td>
    <td style="text-align:center;"><?php echo $bill_data['dust_bags']; ?></td>
    <td style="text-align:center;"><?php echo $bill_data['dust_kgs']; ?></td>
    
    <td style="text-align:center; white-space:nowrap;">

        <a title="Edit" class="edit" href="javascript:get_update_data_ss('<?php echo $bill_data['id']; ?>','<?php echo $bill_data['season']; ?>','<?php echo $bill_data['sale_no']; ?>','<?php echo $bill_data['auction_center']; ?>','<?php echo $bill_data['brokers_id']; ?>','<?php echo $bill_data['buyer_id']; ?>','<?php echo $bill_data['leaf_bags']; ?>','<?php echo $bill_data['leaf_kgs']; ?>','<?php echo $bill_data['dust_bags']; ?>','<?php echo $bill_data['dust_kgs']; ?>','<?php echo $bill_data['category']; ?>');">
			<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
		</a>

    </td>
</tr>    

<?php    
} // foreach
?>

</tbody>
</table>

</div>

<script>
// update data
function get_update_data_ss(setup_id,season,sale_no,auction_center,brokers_id,buyer_id,leaf_bags,leaf_kgs,dust_bags,dust_kgs,category) {
    // show update mode
    $("#form_mode").html('<font color=red>(Update)</font>');

    // fill form values
    $("#setup_id").val(setup_id);
    $("#season").val(season);
    $("#sale_no").val(sale_no);
    $("#auction_center").val(auction_center);
    $("#brokers_id").val(brokers_id);
    $("#buyer_id").val(buyer_id).trigger('change');
    $("#leaf_bags").val(leaf_bags);
    $("#leaf_kgs").val(leaf_kgs);
    $("#dust_bags").val(dust_bags);
    $("#dust_kgs").val(dust_kgs);
    $("#category").val(category);

    // focus on first field
    $("#buyer_id").focus();
}
</script>

<?php
} // end isset
?>