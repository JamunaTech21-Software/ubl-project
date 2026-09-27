<?php
if(isset($regulatory_data_info)){ 
?>
<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
}
</style>

<div class="alert alert-dark" role="alert">
    <b>List of Regulatory</b>
</div>

<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of Regulatory</caption>
<thead class="thead-light">
<tr>
    <th>SL</th>
    <th>Regulatory Name</th>
    <th>Address</th>
    <th>Contact No.</th>
    <th>Email</th>
    <th>Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($regulatory_data_info as $regulatory_info_data){
?>    

<tr class="table-<?php echo $regulatory_info_data['data_status']; ?>">

    <td><?php echo $sl++; ?></td>
    <td><?php echo $regulatory_info_data['regulatory_name']; ?></td>
    <td><?php echo $regulatory_info_data['regulatory_address']; ?></td>
    <td><?php echo $regulatory_info_data['regulatory_contact_no']; ?></td>
    <td><?php echo $regulatory_info_data['regulatory_email']; ?></td>
    
    <td style="text-align:center;">

        <a title="Edit" class="edit" href="javascript:get_update_data_regulatory(
          '<?php echo $regulatory_info_data['id']; ?>',
          '<?php echo $regulatory_info_data['regulatory_name']; ?>',
          '<?php echo $regulatory_info_data['regulatory_address']; ?>',
          '<?php echo $regulatory_info_data['regulatory_contact_no']; ?>',
          '<?php echo $regulatory_info_data['regulatory_email']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

        <a title ="Delete" class="delete" href="javascript:void(0);" data-regulatory_id="<?php echo $regulatory_info_data['id']; ?>" data-regulatory_name="<?php echo $regulatory_info_data['regulatory_name']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

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
function get_update_data_regulatory(setup_id,regulatory_name,regulatory_address,regulatory_contact_no,regulatory_email) {

  window.opener.document.getElementById('form_mode').innerHTML = 'Update';
  window.opener.document.getElementById('setup_id').value = setup_id;
  window.opener.document.getElementById('regulatory_name').value = regulatory_name;
  window.opener.document.getElementById('regulatory_address').value = regulatory_address;
  window.opener.document.getElementById('regulatory_contact_no').value = regulatory_contact_no;
  window.opener.document.getElementById('regulatory_email').value = regulatory_email;
  window.close();
  window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>