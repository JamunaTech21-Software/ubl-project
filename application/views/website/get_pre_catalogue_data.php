<!DOCTYPE html>
<html lang="en">
<head>
<title>Sale No - <?php print $this->input->get('sale_no', TRUE) ;?> Verify</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<script src="<?php echo base_url().'assets/js/multi_filter_dt/jquery-3.3.1.js'; ?>"></script>
<style>
	#table tr.highlighted td { background-color: cyan; } 
</style>
</head>
<body>

<?php
include("json_function.php");

// html data
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$catalogue = (!empty($this->input->get('catalogue', TRUE))) ? $this->input->get('catalogue', TRUE) : '';
$supplement_tea = (!empty($this->input->get('supplement_tea', TRUE))) ? $this->input->get('supplement_tea', TRUE) : '';
$old_season = (!empty($this->input->get('old_season', TRUE))) ? $this->input->get('old_season', TRUE) : '';
$withdraw = (!empty($this->input->get('withdraw', TRUE))) ? $this->input->get('withdraw', TRUE) : '';
?>

<div id="printableArea" class="container">

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-sm table-bordered table-hover">
		<tr>
			<th class="text-center" style="font-family: Lucida Console, Courier, monospace; font-size:18px;"> <?php print $website_title; ?> </th> 
		</tr>
		<tr>
			<th class="text-center" style="font-family: Lucida Console, Courier, monospace; font-size:16px;"> Invoice Verify</th> 
		</tr>
		<tr>
			<td class="text-center"  style="font-family: Lucida Console, Courier, monospace; font-size:14px;">Season: <?php print $season; ?> / Auction Center: <?php print $auction_center; ?> / Sale No: <?php print $sale_no; ?></td>
		</tr>
	</table>
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
}
</style>

<div class="table-responsive">
<table id="table" class="table table-sm table-bordered table-hover">
<tr>
	<th class="no-print" style="color:green; text-align:center;">Edit</th>
	<th style="text-align:center;">Lot No</th>
	<th style="text-align:center;">Invoice</th>
	<th style="text-align:center;">Grade Name</th>
	<th style="text-align:center;">WT P/Bag</th>
	<th style="text-align:center;">N.WT P/Bag</th>
	<th style="text-align:center;">Total Kgs</th>
	<th style="text-align:center;">Smpl Kgs</th>
	<th style="text-align:center;">Add.Smpl</th>
	<th style="text-align:center;">Net Kgs</th>
	<th style="text-align:center;">Chest</th>
	<th style="text-align:center;">Re-print Info</th>
	<th style="text-align:center;">Catalogue</th>
	<th style="text-align:center;">Remarks</th>
	<th class="no-print" style="color:red; text-align:center;">Delete</th>
</tr>
<?php
$sl = 1;
$g_total_kgs = 0;
$g_net_kgs = 0;
$g_chest_number = 0;
$total_sample_kgs = 0;

// query
$this->db->select("

	invoice_entry.id, 
	invoice_entry.lot_no, 
	invoice_entry.old_season, 
	invoice_entry.old_season2, 
	invoice_entry.pre_invoice_id, 
	CONCAT(invoice_entry.old_sale_no,' / ',invoice_entry.old_lot_no, ' / ', SUBSTRING(invoice_entry.ol_ac,1,3)) AS os_ol, 
	
	(
		CASE 
			WHEN invoice_entry.ol_os_sc='No' THEN '<b title=DNOT-SHOW-IN-CATALOGUE><font color=red>No</font></b>' 
			WHEN invoice_entry.ol_os_sc='Yes' THEN '<b title=SHOW-IN-CATALOGUE><font color=green>Yes</font></b>'
			ELSE ''
		END	
	) AS ol_os_sc, 
	
	invoice_entry.w_inv_no, 
	CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name, 
	invoice_entry.gross_wt_per_bag, 
	invoice_entry.net_wt_per_bag, 
	invoice_entry.total_kgs,
	invoice_entry.sample_kgs,
	invoice_entry.additional_sample_kgs,
	
	(
		CASE 
			WHEN invoice_entry.weight_short='y' THEN '<b title=এই-ওজনটি-স্যাম্পল-থাকে-কাটা-হবে-না><font color=red>Short</font></b>' 
			ELSE ''
		END	
	) AS weight_short,
	
	invoice_entry.net_kgs, 
	invoice_entry.chest_number, 
	invoice_entry.catalouge_name, 
	invoice_entry.supplement_tea, 
	invoice_entry.remarks,
	invoice_entry.e_auc,
    warehouse_setup.short_key_word AS wh_sk,
	garden_and_company_setup.garden_name,
    CONCAT(invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto) AS invoice_group,
    CONCAT(garden_and_company_setup.garden_name,' ',warehouse_setup.short_key_word,' ',invoice_entry.invoice_group_txt,' ', invoice_entry.invoice_group,'-',invoice_entry.invoice_group_upto,' ',invoice_entry.old_season) AS sequence_group
	
"); 

$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');

$this->db->where('invoice_entry.catalogue_status', 'prepared');
//$this->db->where('invoice_entry.published'); // is null
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
if (!empty($garden_id)) {
    $this->db->where('invoice_entry.garden_id', $garden_id);
}
if (!empty($catalogue)) {
    $this->db->where('invoice_entry.catalouge_name', $catalogue);
}
if (!empty($supplement_tea)) {
    $this->db->where('invoice_entry.supplement_tea', $supplement_tea);
}
if (!empty($old_season)) {
    $this->db->where('invoice_entry.old_season', $old_season);
}
if (!empty($withdraw)) {
    $this->db->where('invoice_entry.remarks', $withdraw);
}
$this->db->where('invoice_entry.sale_no', $sale_no);

$this->db->where('invoice_entry.soft_delete', 1);
$this->db->order_by('invoice_entry.lot_no ASC');

$lot_query = $this->db->get();
// Start Set lot sequence
$lots = $lot_query->result_array();

$groups = [];
$current_group = [];
$prev_lot_no = null;
$prev_group = null;

foreach($lots as $row2){

    if (($prev_lot_no !== null && $prev_group !== null) && ($row2['sequence_group'] != $prev_group || ($row2['sequence_group'] == $prev_group && $row2['lot_no'] != ($prev_lot_no + 1)))) {
            // Lot break detected
            $groups[] = $current_group;
            $current_group = [];
    }
    $current_group[] = $row2;
    $prev_lot_no = $row2['lot_no'];
    $prev_group = $row2['sequence_group'];
}

if (!empty($current_group)) {
    $groups[] = $current_group;
}

foreach($groups as $single_group){

	$g_total_kgs = 0;
	$g_net_kgs = 0;
	$g_chest_number = 0;
	$total_sample_kgs = 0;

    $group_head = $single_group[0]; // First row of the group for heading
	$sequence_group = $group_head['sequence_group'];

    echo '<tr>
			<th colspan="15" style="border:0px; text-transform:uppercase;" title="'.$sequence_group.'">
				'.$sequence_group.'
			</th>
    </tr>';

	$ids_array = [];

foreach($single_group as $row2){

	$g_total_kgs += $row2['total_kgs'];
	$g_net_kgs += $row2['net_kgs'];
	$g_chest_number += $row2['chest_number'];
	$total_sample_kgs += $row2['sample_kgs'];
	$ids_array[] = $row2['id'];
?>
<tr id="row_id<?php echo $row2['id']; ?>">

	<td class="no-print" style="text-align:center;">
		<a target="_blink" href="<?php echo site_url('MY_Controller/invoice_entry'); ?>?id=<?php echo $row2['id']; ?>"><span style="cursor:pointer; color:green;" class="glyphicon glyphicon-edit no-print"> </span></a>
	</td>
	<td style="text-align:center;">
		<?php echo $row2['lot_no']; ?>
	</td>
	<td style="text-align:center;">
		<?php echo $row2['w_inv_no']; ?>
	</td>
	<td style="text-align:center; text-transform:uppercase;">
		<?php echo $row2['grade_name']; ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($row2['gross_wt_per_bag'],2,'.',''); ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($row2['net_wt_per_bag'],2,'.',''); ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($row2['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($row2['sample_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($row2['additional_sample_kgs'],2,'.',''); ?> <?php echo $row2['weight_short']; ?>
	</td>
	<td style="text-align:right;">
		<?php echo number_format($row2['net_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right;">
		<?php echo $row2['chest_number']; ?>
	</td>
	<td style="text-align:center;"><?php echo $row2['old_season2']; ?> <?php echo $row2['os_ol']; ?> <?php echo $row2['ol_os_sc']; ?></td>
	<td style="text-align:center;">
		<?php echo $row2['catalouge_name']; ?> - <?php echo $row2['supplement_tea']; ?> <b style="color:red;"><?php echo ($row2['e_auc']=='Yes') ? 'E' : ''; ?></b>
	</td>
	<td style="text-align:center; color:red;">
		<?php echo $row2['remarks']; ?>
	</td>
	<td class="no-print" style="text-align:center;">
		<a href="javascript:void(0);" onClick="fn_delete_lot_data('<?php echo $row2['id']; ?>', '<?php echo $row2['pre_invoice_id']; ?>');"><span style="cursor:pointer; color:red;" class="glyphicon glyphicon-trash"></span></a>
	</td>
</tr>
<?php } ?>
	<tr>
		<th style="text-align:center;" class="no-print"><a  href="<?php echo site_url('MY_Controller/group_invoice_entry'); ?>?ids=<?php print implode(",", $ids_array); ?>">
        <span style="cursor:pointer; color:red;" class="glyphicon glyphicon-edit no-print"></span>
    	</a></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th style="text-align:right;"> TOTAL : </th>
		<th style="text-align:right;"><?php echo number_format($g_total_kgs,2,'.',''); ?></th>
		<th style="text-align:right;"><?php echo number_format($total_sample_kgs,2,'.',''); ?></th>
		<th></th>
		<th style="text-align:right;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
		<th style="text-align:right;"><?php echo $g_chest_number; ?></th>
		<th></th>
		<th></th>
		<th></th>
		<th class="no-print"></th>
	</tr>
<?php } ?>
</table>
</div>
</div> 
<!-- /container -->

<script>
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}

//row highlighted
$('#table tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});
</script>

</body>
</html>