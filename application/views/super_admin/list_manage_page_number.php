<?php
if(isset($page_info)){ 
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
    <th>Catalogue</th>
    <th>Lot No Start</th>
    <th>Lot No End</th>
    <th>Page Number</th>
    <th>Cataloge Part</th>
    <!-- <th>Action</th> -->
</tr>
</thead>
<tbody>

<?php
$sl = 1;
foreach($page_info as $page_data){
?>    

<tr class="table-<?php echo $page_data['data_status']; ?>">
	
	<td><?php echo $page_data['season']; ?></td>
	<td><?php echo $page_data['sale_no']; ?></td>
    <td style="text-transform:uppercase;"><?php echo $page_data['auction_center']; ?></td>
    <td style="text-transform:uppercase;"><?php echo $page_data['catalogue']; ?></td>
    <td><?php echo $page_data['lot_no_start']; ?></td>
    <td><?php echo $page_data['lot_no_end']; ?></td>
    <td><?php echo $page_data['page_no']; ?></td>
    <td><?php echo $page_data['catalogue_part']; ?></td>
    
    <!-- <td style="text-align:center; white-space:nowrap;">

        <a title="Edit" class="edit" href="javascript:get_update_data_ss('<?php echo $page_data['id']; ?>','<?php echo $page_data['season']; ?>','<?php echo $page_data['sale_no']; ?>','<?php echo $page_data['auction_center']; ?>','<?php echo $page_data['catalogue']; ?>','<?php echo $page_data['lot_no_start']; ?>','<?php echo $page_data['lot_no_end']; ?>','<?php echo $page_data['page_no']; ?>','<?php echo $page_data['catalogue_part']; ?>');">
			<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
		</a>

    </td> -->
</tr>    

<?php    
} // foreach
?>

</tbody>
</table>

</div>

<script>
// update data
function get_update_data_ss(setup_id,season,sale_no,auction_center,catalogue,lot_no_start,lot_no_end,page_no,catalogue_part) {

	window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
	window.opener.document.getElementById('setup_id').value = setup_id;
	window.opener.document.getElementById('season').value = season;
	window.opener.document.getElementById('sale_no').value = sale_no;
	window.opener.document.getElementById('auction_center').value = auction_center;
	window.opener.document.getElementById('catalogue').value = catalogue;
	window.opener.document.getElementById('lot_no_start').value = lot_no_start;
	window.opener.document.getElementById('lot_no_end').value = lot_no_end;
	window.opener.document.getElementById('page_no').value = page_no;
	window.opener.document.getElementById('catalogue_part').value = catalogue_part;
	window.close();
	window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>