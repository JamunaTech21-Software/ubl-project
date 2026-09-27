<?php
if(isset($contact_info)){ 
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
    <b>List of Contact Person</b>
</div>

<div class="table-responsive-sm">

<table class="table table-sm table-bordered table-hover" id="mydata">
<caption>List of Contact Person</caption>
<thead class="thead-light">
<tr>
    <th>SL</th>
	<th>Type</th>
	<th>Company</th>
	<th>Garden</th>
	<th>Contact Details</th>
	<th>Contact</th>
    <th>Action</th>
</tr>
</thead>  
<tbody>
<?php
$sl = 1;
foreach($contact_info as $contact_data){
?>    

<tr id="row_id<?php echo $contact_data['id']; ?>">

	<td class="text-center"><?php echo $sl++; ?></td>
	<td class="text-left">
		<?php 
			if($contact_data['contact_type']=='1'){
				echo 'Company'; 
			}elseif ($contact_data['contact_type']=='2') {
				echo 'Garden'; 
			}else{
				echo 'Buyer'; 
			}
		?>
	</td>
	<td class="text-left"><?php echo $contact_data['company_name']; ?></td>
	<td class="text-left"><?php echo $contact_data['garden_name']; ?></td>
    <td class="text-left"><?php echo $contact_data['contact_details']; ?></td>
    <td class="text-center"><?php echo $contact_data['contact_no']; ?></td>
    
    <td style="text-align:center; white-space:nowrap;">

        <a title="Edit" class="edit" href="javascript:get_update_data_contact('<?php echo $contact_data['id']; ?>','<?php echo $contact_data['contact_type']; ?>','<?php echo $contact_data['company_id']; ?>','<?php echo $contact_data['garden_id']; ?>','<?php echo $contact_data['buyer_id']; ?>','<?php echo $contact_data['contact_details']; ?>','<?php echo $contact_data['contact_no']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
		&nbsp;&nbsp;
		<span title="Delete" style="cursor:pointer; color:red;" class="glyphicon glyphicon-trash" onclick="fn_delete_contact_person('<?php echo $contact_data['id']; ?>');"></span>

    </td>
</tr>
<?php } // foreach ?>
</tbody>
</table>
</div>

<script>
// update data
function get_update_data_contact(setup_id, contact_type, company_id, garden_id, buyer_id, contact_details, contact_no) {
    window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
    window.opener.document.getElementById('setup_id').value = setup_id;
    window.opener.document.getElementById('contact_type').value = contact_type;
    
	// Set the value of company_id and garden_id and trigger Select2
	var companySelect = window.opener.$('#company_id');
    var gardenSelect = window.opener.$('#garden_id');
    var buyerSelect = window.opener.$('#buyer_id');

    companySelect.val(company_id).trigger('change');
    gardenSelect.val(garden_id).trigger('change');
    buyerSelect.val(buyer_id).trigger('change');
    
    window.opener.document.getElementById('contact_details').value = contact_details;
    window.opener.document.getElementById('contact_no').value = contact_no;

    window.close();
    window.opener.external_comeback();

    // Call the fn_hide_show function in the parent window
    window.opener.fn_hide_show(contact_type);
}

function fn_delete_contact_person(delete_id){
	var r = confirm("Are you sure to delete it?");
	if (r == true) {
		$.ajax({
				type: "POST",
				url: "<?php echo site_url('MY_Controller/delete_contact_person'); ?>",
				dataType : "JSON",
				data: { delete_id: delete_id },
				success: function(data) {
					if(data==1){
						alert("Delete is success!!");
                        $("#row_id"+delete_id).hide(500);
					}else{
						alert(data);
					}
				}
			});
		return false;
	}else{
		// do nothing
	}
}

</script>

<?php
} // end isset
?>