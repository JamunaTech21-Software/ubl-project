<?php
if(isset($gc_info)){ 
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
    <b>List of Garden & Company</b>
</div>

<div class="table-responsive-sm">

<table class="table table-sm table-bordered table-hover" id="mydata">
<caption>List of Garden & Company</caption>
<thead class="thead-light">
<tr>
  <th>SL</th>
  <th>Garden ID</th>
  <th>Main Name</th>
  <th>Garden Name</th>
  <th>Seller Brokers</th>
  <th>Address</th>
  <th>Contact</th>
  <th>Email</th>
  <th>Company Name</th>
  <th>Origin</th>
  <th>Status</th>
  <th>Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($gc_info as $gc_data){
	$row_status = (!empty($gc_data['data_status'])) ? "Active" : "Deactivate";
	$garden_status = (!empty($gc_data['data_status'])) ? "Deactivate" : "Active";
?>    

<tr class="table-<?php echo $gc_data['data_status']; ?>">

  <td style="text-align:center;"><?php echo $sl++; ?></td>
  <td style="text-align:center;"><?php echo $gc_data['id']; ?></td>
  <td style="text-transform:uppercase;"><?php echo $gc_data['main_garden']; ?></td>
  <td style="text-transform:uppercase;"><?php echo $gc_data['garden_name']; ?></td>
  <td><?php echo $gc_data['seller_brokers']; ?></td>
  <td><?php echo $gc_data['garden_address1']; ?> / <?php echo $gc_data['garden_address2']; ?></td>
  <td><?php echo $gc_data['garden_contact_no']; ?></td>
  <td><?php echo $gc_data['garden_email']; ?></td>
  <td><?php echo $gc_data['company_name']; ?></td>
  <td><?php echo $gc_data['origin_name']; ?></td>
  <td><?php echo $garden_status; ?></td>

  <td style="text-align:center; white-space:nowrap;">

      <a title="Details View" class="search" data-toggle="modal" data-target="#gc_profile_Modal" href="javascript:void(0);" data-gc_id="<?php echo $gc_data['id']; ?>" data-gc_name="<?php echo $gc_data['garden_name']; ?> / <?php echo $gc_data['company_name']; ?>"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>

      <a title="Edit" class="edit-gc" href="#" 
        data-id="<?php echo htmlspecialchars($gc_data['id'], ENT_QUOTES); ?>"
        data-garden_name="<?php echo htmlspecialchars($gc_data['garden_name'], ENT_QUOTES); ?>"
        data-garden_type="<?php echo htmlspecialchars($gc_data['garden_type'], ENT_QUOTES); ?>"
        data-main_garden_id="<?php echo htmlspecialchars($gc_data['main_garden_id'], ENT_QUOTES); ?>"
        data-seller_brokers="<?php echo htmlspecialchars($gc_data['seller_brokers'], ENT_QUOTES); ?>"
        data-garden_address1="<?php echo htmlspecialchars($gc_data['garden_address1'], ENT_QUOTES); ?>"
        data-garden_address2="<?php echo htmlspecialchars($gc_data['garden_address2'], ENT_QUOTES); ?>"
        data-garden_contact_no="<?php echo htmlspecialchars($gc_data['garden_contact_no'], ENT_QUOTES); ?>"
        data-garden_email="<?php echo htmlspecialchars($gc_data['garden_email'], ENT_QUOTES); ?>"
        data-origin="<?php echo htmlspecialchars($gc_data['origin'], ENT_QUOTES); ?>"
        data-company_id="<?php echo htmlspecialchars($gc_data['company_id'], ENT_QUOTES); ?>"
        data-report_to="<?php echo htmlspecialchars($gc_data['report_to'], ENT_QUOTES); ?>"
        data-report_cc="<?php echo htmlspecialchars($gc_data['report_cc'], ENT_QUOTES); ?>"
        data-report_letter_to="<?php echo htmlspecialchars($gc_data['report_letter_to'], ENT_QUOTES); ?>"
        data-report_letter_cc="<?php echo htmlspecialchars($gc_data['report_letter_cc'], ENT_QUOTES); ?>">
        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
      </a>
      
      <a title="<?php echo $row_status; ?>" class="delete" href="javascript:void(0);" onclick="fn_delete_garden('<?php echo $gc_data['id']; ?>','<?php echo $row_status; ?>')"><span class="glyphicon glyphicon-<?php print ($row_status=='Deactivate') ? 'trash' : 'ok'; ?>" aria-hidden="true"></span></a>

  </td>
</tr>    

<?php    
} // foreach
?>

</tbody>
</table>

</div>

<div class="modal fade bd-example-modal-lg" id="gc_profile_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Garden and Company Profile</h5>
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

    </div> </div> </div> <script>
// view modal
$('#gc_profile_Modal').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget); // Button that triggered the modal
    var recipient = button.data('gc_id'); // Extract info from data-* attributes
    var gc_name   = button.data('gc_name');
    var modal = $(this);
    modal.find('.modal-title').text('Garden & Company Profile of # ' + gc_name);
    profile_data_return('gc_profile',recipient); // call dunction from (json_function.php)

});

// update data
function get_update_data_gc(setup_id,garden_name,garden_type,main_garden_id,seller_brokers,garden_address1,garden_address2,garden_contact_no,garden_email,origin,company_name,report_to,report_cc,report_letter_to,report_letter_cc) {
	window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
	window.opener.document.getElementById('setup_id').value = setup_id;
	window.opener.document.getElementById('garden_name').value = garden_name;
	window.opener.document.getElementById('garden_type').value = garden_type;
	window.opener.document.getElementById('main_garden_id').value = main_garden_id;
	window.opener.document.getElementById('seller_brokers').value = seller_brokers;
	window.opener.document.getElementById('garden_address1').value = garden_address1;
	window.opener.document.getElementById('garden_address2').value = garden_address2;
	window.opener.document.getElementById('garden_contact_no').value = garden_contact_no;
	window.opener.document.getElementById('garden_email').value = garden_email;
	window.opener.document.getElementById('origin').value = origin;
	window.opener.document.getElementById('company_name').value = company_name;
	window.opener.document.getElementById('report_to').value = report_to;
	window.opener.document.getElementById('report_cc').value = report_cc;
	window.opener.document.getElementById('report_letter_to').value = report_letter_to;
	window.opener.document.getElementById('report_letter_cc').value = report_letter_cc;
	window.close();
	window.opener.external_comeback();
}


// ########## MODIFIED CODE [START] ##########
// New click listener for the edit buttons
$(document).ready(function() {
    // Use event delegation on the table for efficiency
    $('#mydata').on('click', '.edit-gc', function(e) {
        e.preventDefault(); // Stop the link's default '#' behavior

        var $btn = $(this); // The link that was clicked

        // Call your existing function with data from data-attributes
        // jQuery's .data() automatically reads and decodes the attributes
        get_update_data_gc(
            $btn.data('id'),
            $btn.data('garden_name'),
            $btn.data('garden_type'),
            $btn.data('main_garden_id'),
            $btn.data('seller_brokers'),
            $btn.data('garden_address1'),
            $btn.data('garden_address2'),
            $btn.data('garden_contact_no'),
            $btn.data('garden_email'),
            $btn.data('origin'),
            $btn.data('company_id'), // Make sure this matches the 11th param in your JS function
            $btn.data('report_to'),
            $btn.data('report_cc'),
            $btn.data('report_letter_to'),
            $btn.data('report_letter_cc')
        );
    });
});
// ########## MODIFIED CODE [END] ##########

</script>

<?php
} // end isset
?>