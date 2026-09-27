<?php
if(isset($tg_info)){ 
?>
<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
}
.table{
	border-color:black;
	width:60% !important;
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
    <b>List of Tea Grade</b>
</div>

<div class="container">
<div class="table-responsive-sm">

<table class="table table-bordered table-hover" id="mydata">
<caption>List of Tea Grade</caption>
<thead class="thead-light">
<tr>
    <th style="text-align:center;">SL</th>
    <th style="text-align:center;">Grade ID</th>
    <th style="text-align:center;">Grade Name</th>
    <th style="text-align:center;">Grade Category</th>
    <th style="text-align:center;">Catalogue Name</th>
    <th style="text-align:center;">Grade Type</th>
    <th style="text-align:center;">Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($tg_info as $tg_data){
?>    

<tr class="table-<?php echo $tg_data['data_status']; ?>">
    <td style="text-align:center;"><?php echo $sl++; ?></td>
    <td style="text-align:center;"><?php echo $tg_data['id']; ?></td>
    <td style="text-align:center;"><?php echo $tg_data['grade_name']; ?></td>
    <td style="text-align:center;"><?php echo $tg_data['grade_category']; ?></td>
    <td style="text-align:center;"><?php echo $tg_data['catalogue_name']; ?></td>
    <td style="text-align:center;"><?php echo $tg_data['grade_type']; ?></td>
    <td style="text-align:center;">
	
        <a title="Edit" class="edit" href="javascript:get_update_data_tg('<?php echo $tg_data['id']; ?>','<?php echo $tg_data['grade_name']; ?>','<?php echo $tg_data['grade_category']; ?>','<?php echo $tg_data['catalogue_name']; ?>','<?php echo $tg_data['grade_type']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
		
        <a title ="Delete" class="delete" href="javascript:delete_grade_data('<?php echo $tg_data['id']; ?>','<?php echo $tg_data['grade_name']; ?>','<?php echo $tg_data['grade_category']; ?>');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
		
    </td>
</tr>    

<?php    
} // foreach
?>

</tbody>
</table>

</div>
</div>

<script>
function get_update_data_tg(setup_id,grade_name,grade_category,catalogue_name,grade_type) {
    window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
    window.opener.document.getElementById('setup_id').value = setup_id;
    window.opener.document.getElementById('grade_name').value = grade_name;
    window.opener.document.getElementById('grade_category').value = grade_category;
    window.opener.document.getElementById('catalogue_name').value = catalogue_name;
    window.opener.document.getElementById('grade_type').value = grade_type;
    window.close();
    window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>