<?php
if(isset($warehouse_info)){ 
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
}
.table tr td{
	border-color:black;
	vertical-align:middle;
}
</style>

<div class="alert alert-dark" role="alert">
    <b>List of Warehouse</b>
</div>

<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of Warehouse</caption>
<thead class="thead-light">
<tr>
    <th>SL</th>
    <th>Warehouse ID</th>
    <th>Warehouse Name</th>
    <th>Short Key Word</th>
    <th>Address</th>
    <th>Contact No</th>
    <th>Email</th>
    <th>Rent Per Bag</th>
    <th>Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($warehouse_info as $warehouse_data){
?>    

<tr class="table-<?php echo $warehouse_data['data_status']; ?>">

    <td style="text-align:center;"><?php echo $sl++; ?></td>
    <td style="text-align:center;"><?php echo $warehouse_data['id']; ?></td>
    <td><?php echo $warehouse_data['warehouse_name']; ?></td>
    <td><?php echo $warehouse_data['short_key_word']; ?></td>
    <td><?php echo $warehouse_data['warehouse_address']; ?> / <?php echo $warehouse_data['warehouse_address2']; ?></td>
    <td><?php echo $warehouse_data['warehouse_contact_no']; ?></td>
    <td style="white-space:nowrap;"><?php echo $warehouse_data['warehouse_email_address']; ?></td>
    <td><?php echo number_format($warehouse_data['rent_per_bag'],2,'.',''); ?></td>
    
    <td style="text-align:center; white-space:nowrap;">

        <a title="Search" class="search" data-toggle="modal" data-target="#wh_profile_Modal" href="javascript:void(0);" data-warehouse_id="<?php echo $warehouse_data['id'];?>" data-warehouse_name="<?php echo $warehouse_data['warehouse_name']; ?> / <?php echo $warehouse_data['short_key_word']; ?>"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>

        <a title="Edit" class="edit" href="javascript:get_update_data_wh('<?php echo $warehouse_data['id']; ?>','<?php echo $warehouse_data['warehouse_name']; ?>','<?php echo $warehouse_data['short_key_word']; ?>','<?php echo $warehouse_data['warehouse_serial_1']; ?>','<?php echo $warehouse_data['warehouse_serial_2']; ?>','<?php echo $warehouse_data['warehouse_bin_no']; ?>','<?php echo $warehouse_data['warehouse_address']; ?>','<?php echo $warehouse_data['warehouse_address2']; ?>','<?php echo $warehouse_data['warehouse_contact_no']; ?>','<?php echo $warehouse_data['warehouse_email_address']; ?>','<?php echo $warehouse_data['rent_per_bag']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

        <a title ="Delete" class="delete" href="javascript:void(0);" data-warehouse_id="<?php echo $warehouse_data['id'];?>" data-warehouse_name="<?php echo $warehouse_data['warehouse_name']; ?> / <?php echo $warehouse_data['short_key_word']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

    </td>
</tr>    

<?php    
} // foreach
?>

</tbody>
</table>

</div>

<!-- ########## -->
<!-- show modal -->
<!-- ########## -->

<div class="modal fade bd-example-modal-lg" id="wh_profile_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Warehouse Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <table class="table table-bordered table-hover">
            <tbody id="profile_view">
              <tr>
                  <td><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></td>
              </tr>  
            </tbody>    
        </table>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div> <!-- modal-content -->
  </div> <!-- modal-dialog -->
</div> <!-- modal -->

<!-- ########## -->
<!-- End modal -->
<!-- ########## -->

<script>
$('#wh_profile_Modal').on('show.bs.modal', function (event) {

    var button          = $(event.relatedTarget); // Button that triggered the modal
    var recipient       = button.data('warehouse_id'); // Extract info from data-* attributes
    var warehouse_name  = button.data('warehouse_name');
    var modal = $(this);
    modal.find('.modal-title').text('Warehouse Profile of # ' + warehouse_name);
    profile_data_return('warehouse_profile',recipient); // call dunction from (json_function.php)

});

// update data
function get_update_data_wh(setup_id,warehouse_name,short_key_word,warehouse_serial_1,warehouse_serial_2,warehouse_bin_no,warehouse_address,warehouse_address2,warehouse_contact_no,warehouse_email_address,rent_per_bag) {

  window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
  window.opener.document.getElementById('setup_id').value = setup_id;
  window.opener.document.getElementById('warehouse_name').value = warehouse_name;
  window.opener.document.getElementById('short_key_word').value = short_key_word;
  window.opener.document.getElementById('warehouse_serial_1').value = warehouse_serial_1;
  window.opener.document.getElementById('warehouse_serial_2').value = warehouse_serial_2;
  window.opener.document.getElementById('warehouse_bin_no').value = warehouse_bin_no;
  window.opener.document.getElementById('warehouse_address').value = warehouse_address;
  window.opener.document.getElementById('warehouse_address2').value = warehouse_address2;
  window.opener.document.getElementById('warehouse_contact_no').value = warehouse_contact_no;
  window.opener.document.getElementById('warehouse_email_address').value = warehouse_email_address;
  window.opener.document.getElementById('rent_per_bag').value = rent_per_bag;
  window.close();
  window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>