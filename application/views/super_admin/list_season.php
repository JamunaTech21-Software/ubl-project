<?php
if(isset($season_info)){ 
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
    <b>List of Tea Season</b>
</div>

<div class="container">
<div class="table-responsive-sm">
<table class="table table-bordered table-hover" id="mydata">
<caption>List of Tea Season</caption>
<thead class="thead-light">
<tr>
    <th style="text-align:center;">SL</th>
    <th style="text-align:center;">Season</th>
    <th style="text-align:center;">Action</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($season_info as $season_info_data){
?>    

<tr class="table-<?php echo $season_info_data['data_status']; ?>">

    <td style="text-align:center;"><?php echo $sl++; ?></td>
    <td style="text-align:center;"><?php echo $season_info_data['season_name'];?></td>
    
    <td style="text-align:center;">

        <a title="Edit" class="edit" href="javascript:get_update_data_season('<?php echo $season_info_data['id']; ?>','<?php echo $season_info_data['season_name']; ?>');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

        <a title ="Delete" class="delete" href="javascript:void(0);" data-season_id="<?php echo $season_info_data['id'];?>" data-season_name="<?php echo $season_info_data['season_name']; ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>

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
function get_update_data_season(setup_id,season_name) {

  window.opener.document.getElementById('form_mode').innerHTML = '<font color=red>(Update)</font>';
  window.opener.document.getElementById('setup_id').value = setup_id;
  window.opener.document.getElementById('season_name').value = season_name;
  window.close();
  window.opener.external_comeback();
}
</script>

<?php
} // end isset
?>