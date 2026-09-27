<?php
if(isset($company_info)){ 
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
    <b>List of Company</b>
</div>

<div class="table-responsive-sm">

<table class="table table-sm table-bordered table-hover" id="mydata">
<caption>List of Company</caption>
<thead class="thead-light">
<tr>
    <th>SL</th>
	<th>Company Short Name</th>
	<th>Company Full Name</th>
	<th>Address</th>
	<th>Contact</th>
	<th>Email</th>
    <th>Action</th>
</tr>
</thead>  
<tbody>
<?php
$sl = 1;
foreach($company_info as $company_data){
?>    

<tr class="table-<?php echo $company_data['data_status']; ?>">

	<td><?php echo $sl++; ?></td>
	<td><?php echo $company_data['company_name']; ?></td>
	<td><?php echo $company_data['company_full_name']; ?></td>
    <td><?php echo $company_data['company_address1']; ?> / <?php echo $company_data['company_address2']; ?></td>
    <td><?php echo $company_data['company_contact_no']; ?></td>
    <td style="white-space:nowrap;"><?php echo $company_data['company_email']; ?></td>
    
    <td style="text-align:center; white-space:nowrap;">

        <a title="Edit" class="edit" href="javascript:get_update_data_gc('<?php echo $company_data['id']; ?>','<?php echo $company_data['company_name']; ?>','<?php echo $company_data['company_full_name']; ?>','<?php echo $company_data['company_address1']; ?>','<?php echo $company_data['company_address2']; ?>','<?php echo $company_data['company_contact_no']; ?>','<?php echo $company_data['company_email']; ?>','<?php echo $company_data['reg_no']; ?>','<?php echo $company_data['com_report_to']; ?>','<?php echo $company_data['com_report_cc']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

        <a title ="Delete" class="delete" href="javascript:void(0);" data-company_id="<?php echo $company_data['id']; ?>" data-company_name="<?php echo $company_data['company_name']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

    </td>
</tr>
<?php } // foreach ?>
</tbody>
</table>
</div>

<script>
// update data
function get_update_data_gc(setup_id,company_name,company_full_name,company_address1,company_address2,company_contact_no,company_email,reg_no,com_report_to,com_report_cc) {
	window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
	window.opener.document.getElementById('setup_id').value = setup_id;
	window.opener.document.getElementById('company_name').value = company_name;
	window.opener.document.getElementById('company_full_name').value = company_full_name;
	window.opener.document.getElementById('company_address1').value = company_address1;
	window.opener.document.getElementById('company_address2').value = company_address2;
	window.opener.document.getElementById('company_contact_no').value = company_contact_no;
	window.opener.document.getElementById('company_email').value = company_email;
	window.opener.document.getElementById('reg_no').value = reg_no;
	window.opener.document.getElementById('com_report_to').value = com_report_to;
	window.opener.document.getElementById('com_report_cc').value = com_report_cc;
	window.close();
	window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>