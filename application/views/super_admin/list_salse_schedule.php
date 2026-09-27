<?php
if(isset($sale_s_info)){ 
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
    <b>List of Tea Sales Schedule</b>
</div>

<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of Tea Sales Schedule</caption>
<thead class="thead-light">
<tr>
    <th>Season</th>
	<th>Sale No.</th>
	<th>Auction Center</th>
    <th>Sale Date</th>
    <th>Sale Day</th>
    <th>Catalogue Closing Date</th>
    <th>Buyer Prompt Date</th>
    <th>Position</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$sl = 1;
foreach($sale_s_info as $sale_s_data){
?>    

<tr class="table-<?php echo $sale_s_data['data_status']; ?>">
	
	<td><?php echo $sale_s_data['season_name']; ?></td>
	<td><?php echo $sale_s_data['sale_no']; ?></td>
    <td style="text-transform:uppercase;"><?php echo $sale_s_data['auction_center']; ?></td>
    <td><?php echo date("d-M-Y",strtotime($sale_s_data['sale_date'])); ?></td>
    <td><?php echo $sale_s_data['sale_day']; ?></td>
    <td><?php echo date("d-M-Y",strtotime($sale_s_data['catalouge_closing_date'])); ?></td>
    <td><?php echo date("d-M-Y",strtotime($sale_s_data['buyer_prompt_date'])); ?></td>
    <td><?php echo $sale_s_data['position']; ?></td>
    
    <td style="text-align:center; white-space:nowrap;">

        <a title="Edit" class="edit" href="javascript:get_update_data_ss('<?php echo $sale_s_data['id']; ?>','<?php echo $sale_s_data['auction_center']; ?>','<?php echo $sale_s_data['season_name']; ?>','<?php echo $sale_s_data['sale_no']; ?>','<?php echo $sale_s_data['sale_date']; ?>', '<?php echo $sale_s_data['sale_day']; ?>','<?php echo $sale_s_data['catalouge_closing_date']; ?>','<?php echo $sale_s_data['buyer_prompt_date']; ?>','<?php echo $sale_s_data['comments']; ?>','<?php echo $sale_s_data['auction_house']; ?>','<?php echo $sale_s_data['auction_address']; ?>','<?php echo $sale_s_data['auction_time']; ?>','<?php echo $sale_s_data['sample_display_center']; ?>','<?php echo $sale_s_data['position']; ?>');">
			<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
		</a>
		&nbsp;
        <a title ="Delete" class="delete" href="javascript:void(0);" data-sale_s_id="<?php echo $sale_s_data['id']; ?>" data-sale_s_name="<?php echo $sale_s_data['sale_no']; ?> / <?php echo $sale_s_data['sale_date']; ?>">
			<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
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
function get_update_data_ss(setup_id,auction_center,season,sale_no,sale_date,sale_day,catalouge_closing_date,buyer_prompt_date,comments,auction_house,auction_address,auction_time,	sample_display_center,position) {

	window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
	window.opener.document.getElementById('setup_id').value = setup_id;
	window.opener.document.getElementById('auction_center').value = auction_center;
	window.opener.document.getElementById('season').value = season;
	window.opener.document.getElementById('sale_no').value = sale_no;
	window.opener.document.getElementById('sale_date').value = sale_date;
	window.opener.document.getElementById('sale_day').value = sale_day;
	window.opener.document.getElementById('catalouge_closing_date').value = catalouge_closing_date;
	window.opener.document.getElementById('buyer_prompt_date').value = buyer_prompt_date;
	window.opener.document.getElementById('comments').value = comments;
	window.opener.document.getElementById('auction_house').value = auction_house;
	window.opener.document.getElementById('auction_address').value = auction_address;
	window.opener.document.getElementById('auction_time').value = auction_time;
	window.opener.document.getElementById('sample_display_center').value = sample_display_center;
	window.opener.document.getElementById('position').value = position;
	window.close();
	window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>