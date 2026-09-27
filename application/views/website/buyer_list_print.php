<!DOCTYPE html>
<html lang="en">
<head>
<title>BUYER LIST - PRINT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<div id="printableArea" class="container">

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="<?php echo site_url('MY_Controller/buyer_list_print/all_buyer'); ?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> All Buyer List</a>
		&nbsp;
		<a href="<?php echo site_url('MY_Controller/buyer_list_print/main_buyer'); ?>"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Main Buyer List</a> 
		&nbsp;
		<a class="btn-default" href="javascript:void(0);" onclick="return PrintPanel();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
}
.table{
	font-family:verdana;
	width: 100% !important;
}
#table{
	border-color:black;
}
#table tr td{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tbody tr.highlighted td { background-color: cyan; } 

@media print{    
	thead { display: table-header-group; }
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	
	/* DATA GRID ALIGN */
	.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
		float: left;
	}
	.col-sm-12 { width: 100%; }
	.col-sm-11 { width: 91.66666667%; }
	.col-sm-10 { width: 83.33333333%; }
	.col-sm-9 { width: 75%; }
	.col-sm-8 { width: 66.66666667%; }
	.col-sm-7 { width: 58.33333333%; }
	.col-sm-6 { width: 50%; }
	.col-sm-5 { width: 41.66666667%; }
	.col-sm-4 { width: 33.33333333%; }
	.col-sm-3 { width: 25%; }
	.col-sm-2 { width: 22%; }
	.col-sm-1 { width: 8.33333333%; }
}

@page{
	size:auto;
	
	@bottom-center {
		content: counter(page);
		font-style: italic
	}
}
</style>

<div class="row">
<div class="col-sm-12">

<table class="table table-sm text-center" id="table">
<thead>
<tr>
	<td colspan="5" style="text-transform:uppercase; white-space:nowrap; border:none; padding:0px !important;">
		<i><b style="font-size:16px;"><?php print $website_title; ?></i></b> <br /> 
		<i style="font-size:14px;"><?php print $owner_short_address; ?></i> <br /> 
	</td>
</thead>	
</table>

<?php
$buyer_type = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : '';
?>


<?php if($buyer_type=='main_buyer'){ ?>
<table class="table table-sm text-center" id="table">
<tr>
	<td style="border:none; text-align;center;">
		<b style="font-family: verdana; font-size:14px; border:1px solid #CCCCCC; padding:5px;">MAIN BUYER LIST</b>
	</td>
</tr>	
</table>

<table class="table table-sm table-bordered" id="table">
<thead>
<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
	<th style="text-align:center; border:1px solid;">Buyer Name</th>
	<th style="text-align:center; border:1px solid;">Address</th>
	<th style="text-align:center; border:1px solid;">Contact</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;

// query
$this->db->select("*"); 	
$this->db->from('buyer_setup');
$this->db->where('soft_delete', '1');
$this->db->where('main_buyer_id', NULL);
$this->db->order_by('buyer_category ASC, buyer_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row){
?>
<tr>
	<td style="text-align:center;"><?php echo $sl++; ?></td>
	<td style="text-align:left; font-weight:bold; text-transform:uppercase;"><?php echo $row['buyer_name']; ?></td>
	<td style="text-align:left; text-transform:uppercase;">
		<?php if(!empty($row['buyer_address2'])){ ?>
			Address1: <?php echo $row['buyer_address1']; ?>
			<br /> Address2: <?php echo $row['buyer_address2']; ?>
		<?php 
		}else{
			echo $row['buyer_address1'];
		} 
		?>
	</td>
	<td style="text-align:left; text-transform:uppercase;">
		<?php if(!empty($row['contact_person'])){ ?>
			<?php echo $row['contact_person']; ?>
			<br />
			<?php echo $row['buyer_contact_no']; ?>
		<?php 
		}else{
			echo $row['buyer_contact_no'];
		} 
		?>
	</td>
</tr>
<?php } // foreach ?>	
</tbody>
</table>

<?php }else if($buyer_type=='all_buyer'){ ?>

<table class="table table-sm text-center" id="table">
<tr>
	<td style="border:none; text-align;center;">
		<b style="font-family: verdana; font-size:14px; border:1px solid #CCCCCC; padding:5px;">ALL BUYER LIST</b>
	</td>
</tr>	
</table>

<table class="table table-sm table-bordered" id="table">
<thead>
<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
	<th style="text-align:center; border:1px solid;">Buyer Name</th>
	<th style="text-align:center; border:1px solid;">Address</th>
	<th style="text-align:center; border:1px solid;">Contact</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;

// query
$this->db->select("*"); 	
$this->db->from('buyer_setup');
$this->db->where('soft_delete', '1');
$this->db->order_by('buyer_category ASC, buyer_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row){
?>
<tr>
	<td style="text-align:center;"><?php echo $sl++; ?></td>
	<td style="text-align:left; font-weight:bold; text-transform:uppercase;"><?php echo $row['buyer_name']; ?></td>
	<td style="text-align:left; text-transform:uppercase;">
		<?php if(!empty($row['buyer_address2'])){ ?>
			Address1: <?php echo $row['buyer_address1']; ?>
			<br /> Address2: <?php echo $row['buyer_address2']; ?>
		<?php 
		}else{
			echo $row['buyer_address1'];
		} 
		?>
	</td>
	<td style="text-align:left; text-transform:uppercase;">
		<?php if(!empty($row['contact_person'])){ ?>
			<?php echo $row['contact_person']; ?>
			<br />
			<?php echo $row['buyer_contact_no']; ?>
		<?php 
		}else{
			echo $row['buyer_contact_no'];
		} 
		?>
	</td>
</tr>
<?php } // foreach ?>	
</tbody>
</table>
<?php } // end of else ?>

</div> 
</div> 
<!-- row -->

</div> 
<!-- container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
function PrintPanel() {
	
	// Works with Chome, Firefox, IE, Safari
	// Get the HTML of div
	var title = ''; //document.title;
	var divElements = document.getElementById('printableArea').innerHTML;
	var printWindow = window.open("", "_blank", "");
	// open the window
	printWindow.document.open();
	// write the html to the new window, link to css file
	printWindow.document.write('<html><head><title>' + title + '</title><link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/3.4.1/css/bootstrap.min.css"><link rel="icon" href="<?php echo base_url(); ?>assets/img/favicons/favicon.ico"></head><body>');
	printWindow.document.write(divElements);
	printWindow.document.write('</body></html>');
	printWindow.document.close();
	printWindow.focus();
	// The Timeout is ONLY to make Safari work, but it still works with FF, IE & Chrome.
	setTimeout(function() {
		printWindow.print();
		printWindow.close();
	}, 100);
}

//row highlighted
$('#table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});
</script>

</body>
</html>