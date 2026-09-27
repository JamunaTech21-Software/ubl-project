<!DOCTYPE html>
<html lang="en">
<head>
<title><?php print $website_title; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/bootstrap/4.4.1/css/bootstrap.min.css'; ?>">
<!-- ICON css-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-glyphicons.css'; ?>">
<!-- data table css-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery.dataTables.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/dataTables.bootstrap4.css'; ?>">

<script type="text/javascript" src="<?php echo base_url().'assets/jquery/3.4.1/jquery.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/popper.js/1.16.0/umd/popper.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/bootstrap/4.4.1/js/bootstrap.min.js'; ?>"></script>
<!-- data table js-->
<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery.dataTables.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/dataTables.bootstrap4.js'; ?>"></script>

<!--
important rule is used for overriding the previously assigned CSS declarations.
-->

<style>
body {
  background: #e9ebee !important;
  /* 
  margin-top: 10px !important;
  margin-bottom: 10px !important;
  */
}

.form-control{
    border: 1px solid #343a40;
} 

label{
	font-weight:bold;
	font-size:14px;
}

.bg-dark {
    background-color: #4267b2 !important; /* facebook color */
}

.navbar a {
    color: #f8f9fa !important;
}

nav .nav-item {
    border-right: 1px solid #6481b0 !important;
}

nav .nav-item:last-child {
  border-right: 0px !important;
}

nav a.nav-link:hover {
    background-color: #425691 !important;
    border-radius: 2px 2px 2px 2px;
}

.navbar a.dropdown-item {
    color: #000000 !important;
}

.navbar a.dropdown-item:hover {
    color: #FFFFFF !important;
    background-color: #4267b2 !important;
}

.navbar a.dropdown-item.active {
    color: #FFFFFF !important;
    background-color: #4267b2 !important;
}

.dropdown-divider{
	margin:1px !important;
}

.btn-outline-success {
    color: #ffffff !important;
    border-color: #ffffff !important;
}

#sticky-footer {
    background-color: #4267b2 !important;
    color: #ffffff !important;
    margin-top:10px !important;
}

/*
input:focus:required:invalid { border: 1px solid red; }
*/

input:required:invalid { border: 1px solid red; }
input:required:valid { border: 1px solid green; }

select:required:invalid { border: 1px solid red; }
select:required:valid { border: 1px solid green; }

.badge-info {
    font-size: 13px !important;
    font-family:verdana !important;
    cursor: pointer !important;
}

.table{
    font-size: 12px !important;
    font-family:verdana !important;
}

.glyphicon {
    font-size: 15px !important;
}

a.delete{
    color: red !important;
    margin: 5px;
}

a.edit{
    color: blue !important;
    margin: 5px;
}

a.search{
    color: green !important;
    margin: 5px;
}
</style>

<?php
    include("json_function.php");
?>
</head>
<body>

<div class="container-fluid">