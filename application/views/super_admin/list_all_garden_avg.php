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
    <b>List of All Garden Average</b>
</div>

<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of All Garden Average</caption>
<thead class="thead-light">
<tr>
    <th>Season</th>
	<th>Sale No.</th>
    <th>Brokers</th>
    <th>Garden</th>
    <th>Kgs</th>
    <th>Average Price</th>
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
    <td style="text-transform:uppercase;"><?php echo $bill_data['garden_name']; ?></td>
    <td style="text-align:center;"><?php echo $bill_data['total_kgs']; ?></td>
    <td style="text-align:center;"><?php echo $bill_data['avg_price']; ?></td>
    
    <td style="text-align:center; white-space:nowrap;">

        <a title="Edit" class="edit" href="javascript:get_update_data_ss('<?php echo $bill_data['id']; ?>','<?php echo $bill_data['season']; ?>','<?php echo $bill_data['sale_no']; ?>','<?php echo $bill_data['auction_center']; ?>','<?php echo $bill_data['brokers_id']; ?>','<?php echo $bill_data['category']; ?>','<?php echo $bill_data['garden_id']; ?>','<?php echo $bill_data['total_kgs']; ?>','<?php echo $bill_data['avg_price']; ?>');">
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
function get_update_data_ss(setup_id,season,sale_no,auction_center,brokers_id,category,garden_id,total_kgs,avg_price) {
    // show update mode
    $("#form_mode").html('<font color=red>(Update)</font>');

    // fill form values
    $("#setup_id").val(setup_id);
    $("#season").val(season);
    $("#sale_no").val(sale_no);
    $("#auction_center").val(auction_center);
    $("#brokers_id").val(brokers_id);
    $("#category").val(category);
    $("#garden_id").val(garden_id).trigger('change');
    $("#total_kgs").val(total_kgs);
    $("#avg_price").val(avg_price);

    // focus on first field
    $("#garden_id").focus();
}
</script>

<?php
} // end isset
?>