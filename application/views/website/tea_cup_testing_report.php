<?php
// get html value
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$catalouge_name = (!empty($this->input->get('catalouge_name', TRUE))) ? $this->input->get('catalouge_name', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';

// get garden info
$this->db->select('*'); 
$this->db->from('garden_and_company_setup');
$this->db->where('id',$garden_id);
$this->db->where('soft_delete',	1);
$query = $this->db->get();
$garden_info = $query->row_array();
?>

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

<?php if($report_name=='lot_fresh_and_re_print'){ ?> 
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="5" contenteditable="true">
		<span style="font-size:13px;">
			<?php print nl2br($garden_info['report_to']) ; ?>
		</span>
	</td>
	<td style="text-align:right; border:0px;" colspan="3">
		<span style="font-size:13px;">
			Date: <?php print date('d-M-Y') ; ?><br>
			<b class="no-print"><?php print $garden_info['garden_name'] ; ?></b>
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="8">
        <span style="font-size:13px;">Dear Sir</span><br><br>
		<b style="font-size:13px; border-bottom:1px solid black;">Report on teas offered in Auction no- <?php print $sale_no; ?></b>
	</td>
</tr>
<!-- This row is only used for two row spacing -->
<tr>
	<th colspan="8" style="padding:10px !important; border:0px !important;"></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr>
    <th style="text-align:center; border:1px solid; vertical-align:middle;">LOT</th>
    <th style="text-align:center; border:1px solid; vertical-align:middle;">Print</th>
    <th style="text-align:center; border:1px solid; vertical-align:middle;">INV.NO</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">GRADE</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">KGS</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">PKGS</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">RATING <br> LIQUOR</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">SCALE <br> LEAF</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">DESCRIPTION</th>
</tr>
</thead>
<tbody>
<?php
// query
$count = 0;
$total_bag = 0;
$total_kgs = 0;

// Store comments in variables
$leaf_comments = '';
$liquor_comments = '';
$infusion = '';

// query
$this->db->select("invoice_entry.id, invoice_entry.lot_no, invoice_entry.w_inv_no, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, invoice_entry.appearance, invoice_entry.liquor, invoice_entry.chest_number, invoice_entry.total_kgs, invoice_entry.leaf_comments, invoice_entry.liquor_comments, invoice_entry.infusion, IF(invoice_entry.old_sale_no > 0, CONCAT('<b>', invoice_entry.old_sale_no, '</b>/<b>', invoice_entry.old_lot_no, '</b>'), 'Fresh') AS re_print"); 

$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$catalouge_name == "All" ? $this->db->where("invoice_entry.catalouge_name !=", '') : $this->db->where('invoice_entry.catalouge_name', $catalouge_name);	
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();

// Check if there are any rows in the query
if ($query->num_rows() > 0) {
    // Fetch the first row to get the comments
    $first_row = $query->row();
    $leaf_comments = $first_row->leaf_comments;
    $liquor_comments = $first_row->liquor_comments;
    $infusion = $first_row->infusion;

    // Set rowspan attribute for the first row
    $rowspan = 'rowspan="' . $query->num_rows() . '"';
}

foreach($query->result_array() as $fetch){
	$count++; // Increment count
?>

<tr <?php if ($count == 1) echo $rowspan; ?>>
    <td style="text-align:left; border:1px solid; vertical-align: middle; width:50px;"><?php echo $fetch['lot_no']; ?></td>
    <td style="text-align:left; border:1px solid; vertical-align: middle; width:50px;"><?php echo $fetch['re_print']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:50px;"><?php echo $fetch['w_inv_no']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:80px; text-transform: uppercase;"><?php echo $fetch['grade_name']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:80px;"><?php echo number_format($fetch['total_kgs']); ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:50px;"><?php echo number_format($fetch['chest_number']); ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; text-transform: uppercase; width:50px;"><?php echo $fetch['liquor']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; text-transform: uppercase; width:50px;"><?php echo $fetch['appearance']; ?></td>
    <?php if ($count == 1){ ?>
    <td style="text-align:left; border:1px solid; vertical-align: top;"  <?php echo $rowspan; ?>>

        <?php 
			// Display comments only for the first row
			echo (!empty($leaf_comments)) ? '<b>Leaf: </b>' . nl2br($leaf_comments).'<hr>' : '';
			echo (!empty($liquor_comments)) ? '<b><br><br> Liquor: </b>' . nl2br($liquor_comments).'<hr>' : '';
			echo (!empty($infusion)) ? '<b><br><br> Infusion: </b>' . nl2br($infusion) : '';
        ?>
        
    </td>
    <?php } ?>
</tr> 

<?php
	$total_kgs += $fetch['total_kgs'];
	$total_bag += $fetch['chest_number'];
	
}  // 1st loop
?>

<tr>
	<th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
	<th style="text-align:center; border:1px solid;"><?php echo $total_kgs; ?></th>
	<th style="text-align:center; border:1px solid;"><?php echo $total_bag; ?></th>
    <th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
</tr>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

<div class="row">
<div class="col-sm-12" style="margin-top:10px; font-size:13px;">
    Your Sincerely,
</div>

<div class="col-sm-12 text-left" style="margin-top:40px;">
    <b style="text-transform: capitalize; font-size:13px;"> <?php print $website_title; ?>.</b><br>
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
<?php } ?>

<?php if($report_name=='lot_fresh'){ ?> 
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<td style="text-align:left; border:0px;" colspan="5" contenteditable="true">
		<span style="font-size:13px;">
			<?php print nl2br($garden_info['report_to']) ; ?>
		</span>
	</td>
	<td style="text-align:right; border:0px;" colspan="3">
		<span style="font-size:13px;">
			Date: <?php print date('d-M-Y') ; ?><br>
			<b class="no-print"><?php print $garden_info['garden_name'] ; ?></b>
		</span>
	</td>
</tr>

<tr>
	<td style="text-align:left; border:0px;" colspan="8">
        <span style="font-size:13px;">Dear Sir</span><br><br>
		<b style="font-size:13px; border-bottom:1px solid black;">Report on teas offered in Auction no- <?php print $sale_no; ?></b>
	</td>
</tr>
<!-- This row is only used for two row spacing -->
<tr>
	<th colspan="8" style="padding:10px !important; border:0px !important;"></th>
</tr>
<!-- This row is only used for two row spacing -->
<tr>
    <th style="text-align:center; border:1px solid; vertical-align:middle;">LOT</th>
    <th style="text-align:center; border:1px solid; vertical-align:middle;">INV.NO</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">GRADE</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">KGS</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">PKGS</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">RATING <br> LIQUOR</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">SCALE <br> LEAF</th>
	<th style="text-align:center; border:1px solid; vertical-align:middle;">DESCRIPTION</th>
</tr>
</thead>
<tbody>
<?php
// query
$count = 0;
$total_bag = 0;
$total_kgs = 0;

// Store comments in variables
$leaf_comments = '';
$liquor_comments = '';
$infusion = '';

// query
$this->db->select("invoice_entry.id, invoice_entry.lot_no, invoice_entry.w_inv_no, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, invoice_entry.appearance, invoice_entry.liquor, invoice_entry.chest_number, invoice_entry.total_kgs, invoice_entry.leaf_comments, invoice_entry.liquor_comments, invoice_entry.infusion"); 

$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$catalouge_name == "All" ? $this->db->where("invoice_entry.catalouge_name !=", '') : $this->db->where('invoice_entry.catalouge_name', $catalouge_name);	
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->where('invoice_entry.old_sale_no', 0);
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();

// Check if there are any rows in the query
if ($query->num_rows() > 0) {
    // Fetch the first row to get the comments
    $first_row = $query->row();
    $leaf_comments = $first_row->leaf_comments;
    $liquor_comments = $first_row->liquor_comments;
    $infusion = $first_row->infusion;

    // Set rowspan attribute for the first row
    $rowspan = 'rowspan="' . $query->num_rows() . '"';
}

foreach($query->result_array() as $fetch){
	$count++; // Increment count
?>

<tr <?php if ($count == 1) echo $rowspan; ?>>
    <td style="text-align:left; border:1px solid; vertical-align: middle; width:50px;"><?php echo $fetch['lot_no']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:50px;"><?php echo $fetch['w_inv_no']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:80px; text-transform: uppercase;"><?php echo $fetch['grade_name']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:80px;"><?php echo number_format($fetch['total_kgs']); ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; width:50px;"><?php echo number_format($fetch['chest_number']); ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; text-transform: uppercase; width:50px;"><?php echo $fetch['liquor']; ?></td>
    <td style="text-align:center; border:1px solid; vertical-align: middle; text-transform: uppercase; width:50px;"><?php echo $fetch['appearance']; ?></td>
    <?php if ($count == 1){ ?>
    <td style="text-align:left; border:1px solid; vertical-align: top;"  <?php echo $rowspan; ?>>

        <?php 
			// Display comments only for the first row
			echo (!empty($leaf_comments)) ? '<b>Leaf: </b>' . nl2br($leaf_comments).'<hr>' : '';
			echo (!empty($liquor_comments)) ? '<b><br><br> Liquor: </b>' . nl2br($liquor_comments).'<hr>' : '';
			echo (!empty($infusion)) ? '<b><br><br> Infusion: </b>' . nl2br($infusion) : '';
        ?>
        
    </td>
    <?php } ?>
</tr> 

<?php
	$total_kgs += $fetch['total_kgs'];
	$total_bag += $fetch['chest_number'];
	
}  // 1st loop
?>

<tr>
	<th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
	<th style="text-align:center; border:1px solid;"><?php echo $total_kgs; ?></th>
	<th style="text-align:center; border:1px solid;"><?php echo $total_bag; ?></th>
    <th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
	<th style="border:1px solid;"></th>
</tr>
</tbody>
</table>

</div> <!-- col -->
</div> <!-- row -->

<div class="row">
<div class="col-sm-12" style="margin-top:10px; font-size:13px;">
    Your Sicerely,
</div>

<div class="col-sm-12 text-left" style="margin-top:40px;">
    <b style="text-transform: capitalize; font-size:13px;"> <?php print $website_title; ?>.</b><br>
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
<?php } ?>


<?php if($report_name=='comment_report'){ ?> 
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
    Your Sicerely,
</div>

<div class="col-sm-12 text-left" style="margin-top:40px;">
    <b style="text-transform: capitalize; font-size:13px;"> <?php print $website_title; ?>.</b><br>
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
<?php } ?>

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