<?php
if(isset($sample_draw_p_info)){ 
?>
<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
}
.table{
	border-color:black;
	width:50% !important;
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
    <b>List of Sample Draw Process</b>
</div>

<div class="container">
<div class="table-responsive-sm">
<table class="table table-bordered table-hover" id="mydata">
<caption>List of Sample Draw Process</caption>
<thead class="thead-light">
<tr>
    <th style="text-align:center;">SL</th>
    <th>Sample name</th>
    <th>Sample Kgs</th>
    <th style="text-align:center;">Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($sample_draw_p_info as $sample_draw_p_data){
?>    

<tr class="table-<?php echo $sample_draw_p_data['data_status']; ?>">

    <td style="text-align:center;"><?php echo $sl++; ?></td>
    <td><?php echo $sample_draw_p_data['sample_name']; ?></td>
    <td><?php echo number_format($sample_draw_p_data['sample_kgs'],3); ?></td>
    
    <td style="text-align:center;">

        <a title="Edit" class="edit" href="javascript:get_update_data_sdp('<?php echo $sample_draw_p_data['id']; ?>','<?php echo $sample_draw_p_data['sample_name']; ?>','<?php echo $sample_draw_p_data['sample_kgs']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

        <a title ="Delete" class="delete" href="javascript:void(0);" data-sample_id="<?php echo $sample_draw_p_data['id']; ?>" data-sample_name="<?php echo $sample_draw_p_data['sample_name']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

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
// update data
function get_update_data_sdp(setup_id,sample_name,sample_kgs) {

  window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
  window.opener.document.getElementById('setup_id').value = setup_id;
  window.opener.document.getElementById('sample_name').value = sample_name;
  window.opener.document.getElementById('sample_kgs').value = sample_kgs;
  window.close();
  window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>