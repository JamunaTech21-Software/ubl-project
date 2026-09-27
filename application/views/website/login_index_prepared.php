<?php
if(isset($resultq1)){
?>

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
}

.table{
	font-family:verdana !important;
	font-size:12px !important;
	width: 100% !important;
	/* white-space:nowrap !important; */
}	

table.table-bordered{
    border:1px solid black;
}
table.table-bordered > thead > tr > th{
    border:1px solid black;
	padding:0px;
}
table.table-bordered > tbody > tr > td{
    border:1px solid black;
	padding:0px;
}

.row{
	margin:auto !important;
}

@media print{
		
	/* DATA GRID ALIGN */
	.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
		float: left;
	}
	.col-sm-12 {
		width: 100%;
	}
	.col-sm-11 {
		width: 91.66666667%;
	}
	.col-sm-10 {
		width: 83.33333333%;
	}
	.col-sm-9 {
		width: 75%;
	}
	.col-sm-8 {
		width: 66.66666667%;
	}
	.col-sm-7 {
		width: 58.33333333%;
	}
	.col-sm-6 {
		width: 50%;
	}
	.col-sm-5 {
		width: 41.66666667%;
	}
	.col-sm-4 {
		width: 33.33333333%;
	}
	.col-sm-3 {
		width: 25%;
	}
	.col-sm-2 {
		width: 16.66666667%;
	}
	.col-sm-1 {
		width: 8.33333333%;
	}
	
}	
</style>
	
<div class="container">
<div id="page">
<?php
if(count($resultq1) > 0){
?>	
<div class="row">
<div class="col-sm-12">
	<h1 style="text-align:center; letter-spacing: 10px;">INDEX</h1>
	<p style="text-align:center; font-size:15px;"> <b>SEASON <span id="th_arg2"></span></b> </p>
	<p style="text-align:center; font-size:15px;"> <b>SALE NO. <span id="th_arg1"></span></b> </p>
</div> <!-- col -->
</div> <!-- row -->


<div class="row">

<!-- index leaf :: after catalouge prepared -->
<div class="col-sm-4">
<table class="table table-bordered">
<thead>
<tr>
	<th colspan="2" style="text-align:center">Leaf</th>
</tr>
<tr>
	<th style="text-align:left">Garden Name</th>
	<th style="text-align:right">Cat. Page No.</th>
</tr>
</thead>
<tbody>
<?php
foreach($resultq1 as $row1){

$sale_no = $row1['sale_no'];
$season = $row1['season'];	

if($row1['catalouge_name'] == 'Leaf'){
?>

<tr>
	<td><?php echo $row1['garden_name']; ?></td> 
	<td style="text-align:left"><?php echo $row1['lot_page_no']; ?> </td>
</tr>

<?php
}else{
	// do nothing
}

} // loop
?>
</tbody>
</table>
</div> <!-- col end leaf-->


<!-- index dust :: after catalouge prepared -->
<div class="col-sm-4">
<table class="table table-bordered">
<thead>
<tr>
	<th colspan="2" style="text-align:center">Dust</th>
</tr>
<tr>
	<th style="text-align:left">Garden Name</th>
	<th style="text-align:right">Cat. Page No.</th>
</tr>
</thead>
<tbody>
<?php
foreach($resultq1 as $row1){

$sale_no = $row1['sale_no'];
$season = $row1['season'];	

if($row1['catalouge_name'] == 'Dust'){
?>

<tr>
	<td><?php echo $row1['garden_name']; ?></td> 
	<td style="text-align:left"><?php echo $row1['lot_page_no']; ?> </td>
</tr>

<?php
}else{
	// do nothing
}

} // loop
?>
</tbody>
</table>
</div> <!-- col end dust-->


<!-- index suppliment :: after catalouge prepared -->
<div class="col-sm-4">
<table class="table table-bordered">
<thead>
<tr>
	<th colspan="2" style="text-align:center">Suppliment</th>
</tr>
<tr>
	<th style="text-align:left">Garden Name</th>
	<th style="text-align:right">Cat. Page No.</th>
</tr>
</thead>
<tbody>
<?php
foreach($resultq1 as $row1){

$sale_no = $row1['sale_no'];
$season = $row1['season'];	

if($row1['catalouge_name'] == 'Supplement'){
?>

<tr>
	<td><?php echo $row1['garden_name']; ?></td> 
	<td style="text-align:left"><?php echo $row1['lot_page_no']; ?> </td>
</tr>

<?php
}else{
	// do nothing
}

} // loop
?>
</tbody>
</table>
</div> <!-- col end Suppliment-->
</div> <!-- row -->

<?php
}else{
	
	print "<h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. Please Lock the Catalogue Page First. </b></p>";
	
}
?>

</div> <!-- page -->
</div> <!-- container -->

<script>
$( document ).ready(function() {
    $("#th_arg1").html('<?php print $sale_no; ?>');
	$("#th_arg2").html('<?php print $season; ?>');
});
</script>

<?php
} // end first if
?>
</body>
</html>