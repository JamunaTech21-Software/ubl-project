<?php
if(isset($buyer_info)){ 
?>
<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
  font-family: verdana;
}
.table{
	border:1px solid;
	font-size:12px;
}
.table tr th{
	border:1px solid;
	vertical-align:middle;
	text-align:center;
}
.table tr td{
	border:1px solid;
	vertical-align:middle;
}
</style>

<div class="alert alert-dark" role="alert">
    <b>List of Buyer</b>
</div>

<div class="table-responsive-sm">

<table class="table table-sm table-bordered table-hover" id="mydata">
<caption>List of Buyer</caption>
<thead>
<tr>
	<th>SL</th>
	<th>Buyer Name</th>
	<th>Key.W</th>
	<th>Main Buyer</th>
	<th>Type</th>
	<th>Category</th>
	<th>Address</th>
	<th>Contact</th>
	<th>TIN No</th>
	<th>BIN No</th>
	<th>Action</th>
</tr>
</thead>
<tbody>

<?php
$sl = 1;
foreach($buyer_info as $buyer_data){
?>    

<tr class="table-<?php echo $buyer_data['data_status']; ?>" title="<?php echo $buyer_data['id']; ?>">

	<td style="text-align:center;"><?php echo $sl++; ?></td>
	<td style="text-transform:uppercase;"><?php echo $buyer_data['buyer_name']; ?></td>
	<td style="text-align:center;"><?php echo $buyer_data['short_key_code']; ?></td>
	<td style="white-space:nowrap;"><?php echo $buyer_data['main_buyer_name']; ?></td>
	<td><?php echo $buyer_data['buyer_type']; ?></td>
	<td><?php echo $buyer_data['buyer_category']; ?></td>
	<td>Address1: <?php echo $buyer_data['buyer_address1']; ?> <br /> Address2: <?php echo $buyer_data['buyer_address2']; ?></td>
	<td><?php echo $buyer_data['buyer_contact_no']; ?></td>
	<td><?php echo $buyer_data['buyer_tin_no']; ?></td>
	<td><?php echo $buyer_data['buyer_bin_no']; ?></td>

	<td style="text-align:center; white-space:nowrap;">

		<a title="Search" class="search" data-toggle="modal" data-target="#buyer_profile_Modal" href="javascript:void(0);" data-buyer_id="<?php echo $buyer_data['id'];?>" data-buyer_name="<?php echo $buyer_data['buyer_name']; ?> / <?php echo $buyer_data['short_key_code']; ?>"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>

		<a title="Edit" class="edit" href="javascript:get_update_data_buyer('<?php echo $buyer_data['id']; ?>','<?php echo $buyer_data['buyer_name']; ?>','<?php echo $buyer_data['short_key_code']; ?>','<?php echo $buyer_data['buyer_type']; ?>','<?php echo $buyer_data['buyer_category']; ?>','<?php echo $buyer_data['buyer_address1']; ?>','<?php echo $buyer_data['buyer_address2']; ?>','<?php echo $buyer_data['buyer_tin_no']; ?>','<?php echo $buyer_data['buyer_bin_no']; ?>','<?php echo $buyer_data['buyer_contact_no']; ?>','<?php echo $buyer_data['buyer_email']; ?>','<?php echo $buyer_data['main_buyer_id']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

		<a title ="Delete" class="delete" href="javascript:void(0);" data-buyer_id="<?php echo $buyer_data['id'];?>" data-buyer_name="<?php echo $buyer_data['buyer_name']; ?> / <?php echo $buyer_data['short_key_code']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

	</td>
</tr>
 
<?php } // foreach ?>
</tbody>
</table>
</div>

<!-- ########## -->
<!-- show modal -->
<!-- ########## -->

<div class="modal fade bd-example-modal-lg" id="buyer_profile_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Buyer Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <table class="table table-sm table-bordered table-hover">
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
$('#buyer_profile_Modal').on('show.bs.modal', function (event) {
    var button      = $(event.relatedTarget); // Button that triggered the modal
    var recipient   = button.data('buyer_id'); // Extract info from data-* attributes
    var buyer_name  = button.data('buyer_name');
    var modal = $(this);
    modal.find('.modal-title').text('Buyer Profile of # ' + buyer_name);
    profile_data_return('buyer_profile',recipient); // call dunction from (json_function.php)
});

// update data
function get_update_data_buyer(setup_id,buyer_name,short_key_code,buyer_type,buyer_category,buyer_address1,buyer_address2,buyer_tin_no,buyer_bin_no,buyer_contact_no,buyer_email,main_buyer_id) {
	
	window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
	window.opener.document.getElementById('setup_id').value = setup_id;
	window.opener.document.getElementById('buyer_name').value = buyer_name;
	window.opener.document.getElementById('short_key_code').value = short_key_code;
	window.opener.document.getElementById('buyer_type').value = buyer_type;
	window.opener.document.getElementById('buyer_category').value = buyer_category;
	window.opener.document.getElementById('buyer_address1').value = buyer_address1;
	window.opener.document.getElementById('buyer_address2').value = buyer_address2;
	window.opener.document.getElementById('buyer_tin_no').value = buyer_tin_no;
	window.opener.document.getElementById('buyer_bin_no').value = buyer_bin_no;
	window.opener.document.getElementById('buyer_contact_no').value = buyer_contact_no;
	window.opener.document.getElementById('buyer_email').value = buyer_email;
	
	// sub buyer
	var sub_buyer = (main_buyer_id=='') ? 'No' : 'Yes';
	window.opener.document.getElementById('sub_buyer').value = sub_buyer;
	window.opener.document.getElementById('main_buyer_id').value = main_buyer_id;
	// end
	
	window.close();
	window.opener.external_comeback();
}
</script>

<?php } // end isset ?>