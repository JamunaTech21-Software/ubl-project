<?php
// get html value
$season = (!empty($this->input->post('season_name', TRUE))) ? $this->input->post('season_name', TRUE) : '0';
$auction_center = (!empty($this->input->post('auction_center', TRUE))) ? $this->input->post('auction_center', TRUE) : '';
$catalouge_name = (!empty($this->input->post('catalouge_name', TRUE))) ? $this->input->post('catalouge_name', TRUE) : '';
$sale_no = (!empty($this->input->post('sale_no', TRUE))) ? $this->input->post('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->post('garden_id', TRUE))) ? $this->input->post('garden_id', TRUE) : '';

// get garden info
$this->db->select('*'); 
$this->db->from('garden_and_company_setup');
$this->db->where('id',$garden_id);
$this->db->where('soft_delete',	1);
$query = $this->db->get();
$garden_info = $query->row_array();
?>
<head>
 <title>Testing Report Comment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<!-- jQuery and Bootstrap JS (from CDN) -->
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	<!-- Bootstrap CSS (from CDN) -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-glyphicons/1.0.0/bootstrap-glyphicons.css">

	<link rel="icon" href="https://www.unitybrokersltd.com/assets/img/favicon.png">
</head>
<div id="printableArea" class="container">
<style>
body{
	font-family:verdana !important;
	background-color: white !important;
	margin-top:12px;
}
.table{
	font-family:verdana;
	width: 100% !important;
}
#table{
	border-color:black;
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
</style>
<style>
#table tr td {
	border-color:black;
	font-size:12px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
}

@page{
	size:auto;
	margin-left: 100px;
}
</style>
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" contenteditable="true">
		<span style="font-size:13px;">
			<?php print nl2br($garden_info['report_to']) ; ?>
		</span>
	</td>
	<td style="text-align:right; border:0px;">
		<span style="font-size:13px;">
			Date: <?php print date('d-M-Y') ; ?>
		</span>
	</td>
</tr>

<!-- This row is only used for two row spacing -->
<tr>
	<th colspan="2" style="padding:10px !important; border:0px !important;"></th>
</tr>
<!-- This row is only used for two row spacing -->

<tr>
	<td style="text-align:center; border:0px;" colspan="2">
		<span style="font-size:13px;">
			<b><u>Testing Report for Sale <?php print $sale_no  ; ?></u></b>
		</span>
	</td>
</tr>

<!-- This row is only used for two row spacing -->
<tr>
	<th colspan="2" style="padding:10px !important; border:0px !important;"></th>
</tr>
<!-- This row is only used for two row spacing -->

<tr>
	<td style="text-align:center; border:0px;" colspan="2">
		<span style="font-size:13px;">
			<b><u><?php print $garden_info['garden_name'] ; ?>:</u></b>
		</span>
	</td>
</tr>

</thead>
<tbody>
<?php
// query
$this->db->select("invoice_entry.leaf_comments, invoice_entry.liquor_comments, invoice_entry.infusion"); 

$this->db->from('invoice_entry');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$catalouge_name == "All" ? $this->db->where("invoice_entry.catalouge_name !=", '') : $this->db->where('invoice_entry.catalouge_name', $catalouge_name);	
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.old_sale_no', 0);
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
$result = $query->row_array();
?>
<?php if(!empty($result['leaf_comments'])){ ?> 
<tr>
    <td style="text-align:left; border:0;" colspan="2">
		<b><u>Leaf: </u></b> <?php echo nl2br($result['leaf_comments']); ?>
    </td>
</tr> 
<?php } ?>

<!-- This row is only used for two row spacing -->
<tr>
	<th colspan="2" style="padding:10px !important; border:0px !important;"></th>
</tr>
<!-- This row is only used for two row spacing -->

<?php if(!empty($result['liquor_comments'])){ ?> 
<tr>
    <td style="text-align:left; border:0;" colspan="2">
		<b><u>Liquor: </u></b> <?php echo nl2br($result['liquor_comments']); ?>
    </td>
</tr> 
<?php } ?>

<!-- This row is only used for two row spacing -->
<tr>
	<th colspan="2" style="padding:10px !important; border:0px !important;"></th>
</tr>
<!-- This row is only used for two row spacing -->

<?php if(!empty($result['infusion'])){ ?> 
<tr>
    <td style="text-align:left; border:0;" colspan="2">
		<b><u>Infusion: </u></b> <?php echo nl2br($result['infusion']); ?>
    </td>
</tr> 
<?php } ?>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

<div class="row">
<div class="col-sm-12" style="margin-top:10px; font-size:13px;">
    Your Sincerely,
</div>

<div class="col-sm-12 text-left" style="margin-top:40px;">
    <b style="text-transform: capitalize; font-size:13px;"> Unity Brokers Limited.</b><br>
</div>
<div class="col-sm-12" style="margin-top:15px;">
    <table>
        <tr>
            <td style="font-size:13px; vertical-align: top;">CC:&nbsp;&nbsp;</td>
            <td style="font-size:13px;" contenteditable="true"><?php print nl2br($garden_info['report_cc']) ; ?></td>
        </tr>
    </table>
</div>
</div>

</div>
<!-- /container -->

<script>
function printDiv(divName){
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
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