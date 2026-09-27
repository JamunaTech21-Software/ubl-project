<?php
// 1st part
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';

// where clause
$custom_clause = "`invoice_entry`.`season`='".$season."' AND `invoice_entry`.`sale_no` <= '".$sale_no."' AND `invoice_entry`.`catalogue_status`='prepared' AND `invoice_entry`.`soft_delete`='1' ";
$custom_clause .= ($auction_center=='All') ? "" : " AND `invoice_entry`.`auction_center`='".$auction_center."' "; 
$custom_clause .= ($garden_id=='All') ? "" : " AND `invoice_entry`.`garden_id`='".$garden_id."' "; 
?>

<div id="printableArea" class="container">

<style>
body{
	background-color: white !important;
	font-family:verdana;
}
.table{
	font-family:verdana;
	font-size:12px !important;
	border:none;
	table-layout:fixed;
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

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="#" onClick="printDiv('printableArea')"><span class="badge badge-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
	</div>
</div>
<?php
// create temporary table - unsold_unprint_tbl
$this->db->query("DROP TABLE IF EXISTS `unsold_unprint_tbl`");

// create unsold_unprint_tbl
$this->db->query("CREATE TABLE `unsold_unprint_tbl` (
SELECT 
	`invoice_entry`.`id` AS inv_primay_id,
	`invoice_entry`.`org_id`,
	`invoice_entry`.`reprinted_invoice`,
	`invoice_entry`.`sale_no`,
	`invoice_entry`.`lot_no`,
	`garden_and_company_setup`.`garden_name`,
	`tea_grade_setup`.`grade_name`,
	`invoice_entry`.`w_inv_no`,
	`invoice_entry`.`chest_number`,
	`warehouse_setup`.`short_key_word`,
	`invoice_entry`.`old_season`,
	CONCAT(`invoice_entry`.`sale_no`,', ',`invoice_entry`.`auction_center`,', ', `invoice_entry`.`season`) AS `last_print`,
	CONCAT(`invoice_entry`.`old_sale_no`,', ', `invoice_entry`.`old_lot_no`,', ',`invoice_entry`.`ol_ac`) AS `status`

FROM `invoice_entry`  

LEFT JOIN `sale_entry` ON (`invoice_entry`.`id` = `sale_entry`.`invoice_id`)  
LEFT JOIN `garden_and_company_setup` ON (`invoice_entry`.`garden_id` = `garden_and_company_setup`.`id`)  
LEFT JOIN `tea_grade_setup` ON (`invoice_entry`.`grade_id` = `tea_grade_setup`.`id`)  
LEFT JOIN `warehouse_setup` ON (`invoice_entry`.`w_house_id` = `warehouse_setup`.`id`)  

WHERE $custom_clause AND IFNULL(`sale_entry`.`sold_price`, 0) = 0 AND NOT EXISTS (SELECT 1 FROM `invoice_entry` AS `ie2` WHERE `ie2`.`reprinted_invoice` = `invoice_entry`.`id`)) ");
?>

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	OUT LOT STATEMENT UPTO <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> 
	</th>
</tr>
</table>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:center; border:1px solid;">SL</th>
	<th style="text-align:center; border:1px solid;">Sale No</th>
	<th style="text-align:center; border:1px solid;">Lot No</th>
	<th style="text-align:center; border:1px solid;">Garden Name</th>
	<th style="text-align:center; border:1px solid;">Grade</th>
	<th style="text-align:center; border:1px solid;">Inv No</th>
	<th style="text-align:center; border:1px solid;">Chest</th>
	<th style="text-align:center; border:1px solid;">W.House Name</th>
	<th style="text-align:center; border:1px solid;">Old Season</th>
	<th style="text-align:center; border:1px solid;">Last Print</th>
	<th style="text-align:center; border:1px solid;">Status</th>
</tr>
</thead>
<tbody>
<?php
$sl = 1;

// query
$this->db->select('*');
$this->db->from('unsold_unprint_tbl');
$this->db->order_by('sale_no ASC, lot_no ASC');
$query = $this->db->get();
if ($query->num_rows() > 0) {
foreach($query->result_array() as $fetch){
?>

<tr>
	<td style="text-align:center; vertical-align:middle;"><?php echo $sl++; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $fetch['sale_no']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $fetch['lot_no']; ?></td>
    <td style="text-align:left; vertical-align:middle;"><?php echo $fetch['garden_name']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $fetch['grade_name']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $fetch['w_inv_no']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $fetch['chest_number']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $fetch['short_key_word']; ?></td>
    <td style="text-align:center; vertical-align:middle;"><?php echo $fetch['old_season']; ?></td>
    <td style="text-align:left; vertical-align:middle;"><?php echo $fetch['last_print']; ?></td>
    <td style="text-align:left; vertical-align:middle;"><?php echo $fetch['status']; ?></td>
	
</tr>	

<?php } // end loop	?>
	
<?php }else{ ?> 
	<tr><td colspan="11" style="text-align:center; vertical-align:middle;">No matching record found.</td></tr>
<?php } ?>

</tbody>
</table>
</div>
</div>
</div>

</div> 
<!-- container -->

<script>
// print area
function printDiv(divName) 
{
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
}
</script>