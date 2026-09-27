<?php
if(isset($catalogue_info)){ 
?>
<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
}
</style>

<div class="alert alert-dark" role="alert">
    <b>List of Tea Catalogue</b>
</div>

<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of Tea Catalogue</caption>
<thead class="thead-light">
<tr>
    <th style="text-align:center;">SL</th>
    <th style="text-align:center;">Catalogue name</th>
    <th style="text-align:center;">Suppliment Tea</th>
    <th style="text-align:center;">Auction</th>
    <th style="text-align:center;">Lot Start</th>
    <th style="text-align:center;">Lot End</th>
    <th style="text-align:center;">Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($catalogue_info as $catalogue_info_data){
?>    

<tr class="table-<?php echo $catalogue_info_data['data_status']; ?>">

    <td style="text-align:center;"><?php echo $sl++; ?></td>
    <td><?php echo $catalogue_info_data['catalouge_name']; ?></td>
    <td><?php echo $catalogue_info_data['suppliment_tea']; ?></td>
    <td style="text-align:center;"><?php echo $catalogue_info_data['auction']; ?></td>
    <td style="text-align:center;"><?php echo $catalogue_info_data['lot_no_start']; ?></td>
    <td style="text-align:center;"><?php echo $catalogue_info_data['lot_no_end']; ?></td>
    
    <td style="text-align:center;">

        <a title="Edit" class="edit" href="javascript:get_update_data_cat('<?php echo $catalogue_info_data['id']; ?>','<?php echo $catalogue_info_data['catalouge_name']; ?>','<?php echo $catalogue_info_data['suppliment_tea']; ?>','<?php echo $catalogue_info_data['auction']; ?>','<?php echo $catalogue_info_data['lot_no_start']; ?>','<?php echo $catalogue_info_data['lot_no_end']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

        <a title ="Delete" class="delete" href="javascript:void(0);" data-catalogue_id="<?php echo $catalogue_info_data['id']; ?>" data-catalogue_name="<?php echo $catalogue_info_data['catalouge_name']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

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
function get_update_data_cat(setup_id,catalouge_name,suppliment_tea,auction,lot_no_start,lot_no_end) {

  window.opener.document.getElementById('form_mode').innerHTML = 'Update';
  window.opener.document.getElementById('setup_id').value = setup_id;
  window.opener.document.getElementById('catalouge_name').value = catalouge_name;
  window.opener.document.getElementById('suppliment_tea').value = suppliment_tea;
  window.opener.document.getElementById('auction').value = auction;
  window.opener.document.getElementById('lot_no_start').value = lot_no_start;
  window.opener.document.getElementById('lot_no_end').value = lot_no_end;
  window.close();
  window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>