<?php
if(isset($brokers_data_info)){ 
?>
<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
}
.table{
	border-color:black;
}
.table tr th{
	border-color:black;
	vertical-align:middle;
	text-align:center;
}
.table tr td{
	border-color:black;
	vertical-align:middle;
	text-align:center;
}
</style>

<div class="alert alert-dark" role="alert">
    <b>List of Brokers</b>
</div>

<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of Brokers</caption>
<thead class="thead-light">
<tr>
    <th>SL</th>
    <th>Brokers Name</th>
    <th>Short Key</th>
    <th>Address</th>
    <th>Contact No.</th>
    <th>Email</th>
    <th>TIN No.</th>
    <th>BIN No.</th>
    <th>Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($brokers_data_info as $brokers_info_data){
?>    

<tr class="table-<?php echo $brokers_info_data['data_status']; ?>">

    <td><?php echo $sl++; ?></td>
    <td style="text-align:left;"><?php echo $brokers_info_data['brokers_name']; ?></td>
    <td style="text-align:center;"><?php echo $brokers_info_data['short_key_code']; ?></td>
    <td style="width:200px;"><?php echo $brokers_info_data['brokers_address']; ?></td>
    <td style="text-align:left;"><?php echo $brokers_info_data['brokers_contact_no']; ?></td>
    <td style="text-align:left;"><?php echo $brokers_info_data['brokers_email']; ?></td>
    <td><?php echo $brokers_info_data['brokers_tin_no']; ?></td>
    <td><?php echo $brokers_info_data['brokers_bin_no']; ?></td>
    
    <td style="text-align:center;">

        <a title="Edit" class="edit" href="javascript:get_update_data_brokers(
          '<?php echo $brokers_info_data['id']; ?>',
          '<?php echo $brokers_info_data['brokers_name']; ?>',
          '<?php echo $brokers_info_data['short_key_code']; ?>',
          '<?php echo $brokers_info_data['brokers_address']; ?>',
          '<?php echo $brokers_info_data['brokers_contact_no']; ?>',
          '<?php echo $brokers_info_data['brokers_email']; ?>',
          '<?php echo $brokers_info_data['brokers_tin_no']; ?>',
          '<?php echo $brokers_info_data['brokers_bin_no']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

        <a title ="Delete" class="delete" href="javascript:void(0);" data-brokers_id="<?php echo $brokers_info_data['id']; ?>" data-brokers_name="<?php echo $brokers_info_data['brokers_name']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

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
function get_update_data_brokers(setup_id,brokers_name,short_key_code,brokers_address,brokers_contact_no,brokers_email,brokers_tin_no,brokers_bin_no) {

  window.opener.document.getElementById('form_mode').innerHTML = 'Update';
  window.opener.document.getElementById('setup_id').value = setup_id;
  window.opener.document.getElementById('brokers_name').value = brokers_name;
  window.opener.document.getElementById('short_key_code').value = short_key_code;
  window.opener.document.getElementById('brokers_address').value = brokers_address;
  window.opener.document.getElementById('brokers_contact_no').value = brokers_contact_no;
  window.opener.document.getElementById('brokers_email').value = brokers_email;
  window.opener.document.getElementById('brokers_tin_no').value = brokers_tin_no;
  window.opener.document.getElementById('brokers_bin_no').value = brokers_bin_no;
  window.close();
  window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>