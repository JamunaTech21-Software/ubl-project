<?php
if(isset($chest_data_info)){ 
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
    <b>List of Tea Chest</b>
</div>

<div class="container">
<div class="table-responsive-sm">
<table class="table table-bordered table-hover" id="mydata">
<caption>List of Tea Chest</caption>
<thead class="thead-light">
<tr>
    <th style="text-align:center;">SL</th>
    <th style="text-align:center;">Chest name</th>
</tr>
</thead>  

<tbody>

<?php
$sl = 1;
foreach($chest_data_info as $chest_info_data){
?>    

<tr class="table-<?php echo $chest_info_data['data_status']; ?>">

    <td style="text-align:center;"><?php echo $sl++; ?></td>
    <td style="text-align:center;"><?php echo $chest_info_data['chest_name']; ?></td>
</tr>    

<?php    
} // foreach
?>

</tbody>
</table>
</div>
</div>

<?php
} // end isset
?>