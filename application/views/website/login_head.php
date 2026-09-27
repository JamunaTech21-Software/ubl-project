<!DOCTYPE html>
<html lang="en">
<head>
<title><?php print $website_title; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<!-- ICON css-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-glyphicons.css'; ?>">
<!-- autocomplete -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/jquery-ui.css'?>">
<!-- chosen -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css" />
<!-- datatable css-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/multi_filter_dt/jquery.dataTables.min.css'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/multi_filter_dt/fixedHeader.dataTables.min.css'; ?>">
<!-- datetime css-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/datetime/bootstrap-datetimepicker.min.css'; ?>">
<!-- Select2 Css -->
<link href="<?php echo base_url().'assets/select2plugin-4.1.0/select2.min.css'; ?>" rel="stylesheet">

<script src="<?php echo base_url().'assets/js/multi_filter_dt/jquery-3.3.1.js'; ?>"></script>		
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/bootstrap.min.js'; ?>"></script>
<!-- autocomplete -->
<script type="text/javascript" src="<?php echo base_url().'assets/js/jquery-ui.js'?>"></script>
<!-- chosen -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
<!-- datatable js-->
<script src="<?php echo base_url().'assets/js/multi_filter_dt/jquery.dataTables.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/js/multi_filter_dt/dataTables.fixedHeader.min.js'; ?>"></script>
<!-- datetime js-->
<script src="<?php echo base_url().'assets/js/datetime/bootstrap-datetimepicker.js'; ?>"></script>
<!-- Select2 JavaScript -->
<script src="<?php echo base_url().'assets/select2plugin-4.1.0/select2.min.js'; ?>"></script>

<style> 
body{
background-color: #e9ebee !important;
font-family:verdana;
}   
.navbar{
background-color: #add8e6 !important;
}
.navbar-nav a{
color: #000000 !important;
font-weight: bold !important;
}
footer {
background-color: #555;
color: white;
padding: 15px;
}
#form_group_element{
text-align:left !important;
font-family:verdana !important;
}  
.panel-body{
font-size:16px !important;
font-family:verdana !important;
}
input:required:invalid { border: 1px solid red; }
input:required:valid { border: 1px solid green; }

select:required:invalid { border: 1px solid red; }
select:required:valid { border: 1px solid green; }

textarea:required:invalid { border: 1px solid red; }
textarea:required:valid { border: 1px solid green; }

.well{ background-color: #ffffff !important; box-shadow: 0 0 1px rgba(0,0,0,0.5); }
.form-control:focus{ border: 2px solid !important; border-color: grey; } 
.has-error .form-control:focus{ border: 2px solid !important; border-color: grey; }
</style>

<?php
  include("json_function.php");
?>
</head>
<body>