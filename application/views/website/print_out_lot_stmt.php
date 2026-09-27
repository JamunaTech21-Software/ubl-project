<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';

## Sale details
$query = $this->db->query("SELECT date_format(sale_date,'%d-%m-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%m-%Y') AS buyer_prompt_date FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$prompt_date = $query->row_array();
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

<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-transform: uppercase; text-align:center; border:0px;">
	<span style="font-size:16px;"><?php print $website_title; ?></span><br />
	<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
	OUT LOT STATEMENT <br /> <?php print $auction_center; ?></th>
</tr>
<tr>
	<th style="text-transform:uppercase; text-align:center; border:0px;">
		Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sale Date: <?php print $prompt_date['sale_date']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Prompt Date: <?php print $prompt_date['buyer_prompt_date']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> 
	</th>
</tr>
</table>
</div>
</div>

<?php if($report_name == 'garden_unsold'){ ?>
<div class="row">
<div class="col-sm-12">
<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
	<th style="text-align:left; color:green; text-transform: uppercase; border:1px solid;">Garden Name</th>
	<th style="text-align:left; color:green; text-transform: uppercase; border:1px solid;"></th>
	<th style="text-align:right; color:#006994; text-transform: uppercase; border:1px solid;">Bags</th>
	<th style="text-align:right; color:#006994; text-transform: uppercase; border:1px solid;">Kgs</th>
	<th style="text-align:right; color:#006994; text-transform: uppercase; border:1px solid;">%</th>
</tr>
</thead>
<tbody>
<?php
$nt_chest_number=0;
$nt_out_bag_l=0;
$nt_out_kgs_l=0;
$nt_out_bag_d=0;
$nt_out_kgs_d=0;
//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 AS $row1){

// reset value
$n_chest_number = 0;
$n_sold_bag_l = 0;
$n_sold_bag_d = 0;
$n_wd_bag_l = 0;
$n_wd_bag_d = 0;
$n_out_bag_l = 0;
$n_out_kgs_l = 0;
$n_out_bag_d = 0;
$n_out_kgs_d = 0;
// query
$this->db->select("
	invoice_entry.sale_no, 
	invoice_entry.lot_no, 
	invoice_entry.w_inv_no,  
	invoice_entry.valuation, 
	IFNULL(sale_entry.sold_price,0) AS sold_price, 
	invoice_entry.net_kgs, 
	invoice_entry.chest_number, 
	invoice_entry.remarks, 
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)
	) AS leaf_total_bag,
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN net_kgs END),0)
	) AS leaf_net_kgs, 
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)
	) AS dust_total_bag,
	
	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN net_kgs END),0)
	) AS dust_net_kgs
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no',$sale_no);
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.id');
$this->db->order_by('invoice_entry.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){

if($row3['sold_price'] > 0){
	
	$sold_bag_l = $row3['leaf_total_bag']; $sold_kgs_l = $row3['leaf_net_kgs']; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = 0; $out_kgs_l = 0;
	
	$sold_bag_d = $row3['dust_total_bag']; $sold_kgs_d = $row3['dust_net_kgs']; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = 0; $out_kgs_d = 0;
	
}else if(!empty($row3['remarks'])){
	
	$sold_bag_l = 0; $sold_kgs_l = 0; $wd_bag_l = $row3['leaf_total_bag']; $wd_kgs_l = $row3['leaf_net_kgs']; $out_bag_l = 0;$out_kgs_l = 0;
	
	$sold_bag_d = 0; $sold_kgs_d = 0; $wd_bag_d = $row3['dust_total_bag']; $wd_kgs_d = $row3['dust_net_kgs']; $out_bag_d = 0;$out_kgs_d = 0;
	
}else{
	
	$sold_bag_l = 0; $sold_kgs_l = 0; $sold_tk_l = 0; $wd_bag_l = 0; $wd_kgs_l = 0; $out_bag_l = $row3['leaf_total_bag']; $out_kgs_l = $row3['leaf_net_kgs'];
	
	$sold_bag_d = 0; $sold_kgs_d = 0; $sold_tk_d = 0; $wd_bag_d = 0; $wd_kgs_d = 0; $out_bag_d = $row3['dust_total_bag']; $out_kgs_d = $row3['dust_net_kgs'];
}

$n_chest_number += $row3['chest_number'];
$n_sold_bag_l += $sold_bag_l;
$n_sold_bag_d += $sold_bag_d;
$n_wd_bag_l += $wd_bag_l;
$n_wd_bag_d += $wd_bag_d;
$n_out_bag_l += $out_bag_l;
$n_out_kgs_l += $out_kgs_l;
$n_out_bag_d += $out_bag_d;
$n_out_kgs_d += $out_kgs_d;
	
}  // 2nd loop

$leaf_offering = $n_sold_bag_l + $n_wd_bag_l + $n_out_bag_l;
$dust_offering = $n_sold_bag_d + $n_wd_bag_d + $n_out_bag_d;
?>

<?php if($n_out_bag_l > 0){ ?>
<tr>
	<td style="text-align:left; border:0px;">
		<a target="blink" href="<?php echo site_url('MY_Controller/print_out_lot_stmt?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id'].'&report_name=unsold_details'); ?>"><b style="text-transform:uppercase;"><?php print $row1['garden_name']; ?></b></a>
	</td>
	<td style="text-align:right; border:0px; text-transform:uppercase;"><b>Leaf</b></td>
	<td style="text-align:right; color:#006994; border:0px;"><?php print $n_out_bag_l; ?></td>
	<td style="text-align:right; color:#006994; border:0px;"><?php print number_format($n_out_kgs_l,2,'.',''); ?></td>
	<td style="text-align:right; color:#006994; border:0px;">
	<?php 
		print ($n_out_bag_l > 0 && $leaf_offering > 0) ? number_format(($n_out_bag_l * 100)/$leaf_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<?php } ?>


<?php if($n_out_bag_d > 0){ ?>
<tr>
	<td style="text-align:left; border:0px;">
		<a target="blink" href="<?php echo site_url('MY_Controller/garden_unsold_report?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id'].'&report_name=unsold_details'); ?>"><b style="text-transform:uppercase;"><?php print ($n_out_bag_l == 0) ? $row1['garden_name'] : ''; ?></b></a>
	</td>
	<td style="text-align:right; border:0px; text-transform:uppercase;"><b>Dust</b></td>
	<td style="text-align:right; border:0px; color:#006994;"><?php print $n_out_bag_d; ?></td>
	<td style="text-align:right; border:0px; color:#006994;"><?php print number_format($n_out_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; border:0px; color:#006994;">
	<?php 
		print ($n_out_bag_d > 0 && $dust_offering > 0) ? number_format(($n_out_bag_d * 100)/$dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<?php } ?>


<?php if(($n_out_bag_l + $n_out_bag_d) > 0){ ?>
<tr>
	<th colspan="2" style="text-align:right; border:0px; text-transform:uppercase;">Sub Total</th>
	<th style="text-align:right; color:#006994; border:0px;"><?php print $n_out_bag_l + $n_out_bag_d; ?></th>
	<th style="text-align:right; color:#006994; border:0px;"><?php print number_format($n_out_kgs_l + $n_out_kgs_d,2,'.',''); ?></th>
	<th style="text-align:right; color:#006994; border:0px;"><?php print number_format((($n_out_bag_l + $n_out_bag_d) * 100)/$n_chest_number,2,'.',''); ?></th>
</tr>

<tr>
	<td colspan="5" style="border:0px;"></td>
</tr>
<?php } ?>

<?php
$nt_chest_number += $n_chest_number;
$nt_out_bag_l += $n_out_bag_l;
$nt_out_kgs_l += $n_out_kgs_l;
$nt_out_bag_d += $n_out_bag_d;
$nt_out_kgs_d += $n_out_kgs_d;
	
} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="5" style="border:0px;"></td>
</tr>
<tr>
	<th colspan="2" style="text-align:right; text-transform:uppercase; border:1px solid;">Net Total: </th>
	<th style="text-align:right; color:#006994; border:1px solid;"><?php print $nt_out_bag_l + $nt_out_bag_d; ?></th>
	<th style="text-align:right; color:#006994; border:1px solid;"><?php print number_format($nt_out_kgs_l + $nt_out_kgs_d,2,'.',''); ?></th>
	<th style="text-align:right; color:#006994; border:1px solid;">
		<?php 
			print (($nt_out_bag_l + $nt_out_bag_d)!='0' && $nt_chest_number!='0') ? number_format((($nt_out_bag_l + $nt_out_bag_d) * 100)/$nt_chest_number,2,'.','') : '0.00'; 
		?>
	</th>
</tr>	
</tfoot>
</table>
</div>
</div>
</div>

<div class="row no-print">
<div class="form-group col-sm-6">
</div>
<div class="form-group col-sm-6 text-right">
	<a class="text-danger" target="blink" href="<?php echo site_url('MY_Controller/garden_unsold_details?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&report_name=unsold_details'); ?>"><span class="glyphicon glyphicon-search text-danger" aria-hidden="true"></span><b> In Details... </b></a>
</div>
</div>


<?php }else if($report_name=='unsold_details'){ 


// garden name
$query = $this->db->query("SELECT * FROM `garden_and_company_setup` WHERE id='".$garden_id."' "); 
$garden_name_result = $query->row_array();
?>
<div class="row">
<div class="col-sm-12">
<?php
## create temporary table - garden_unsold_report
$this->db->query("DROP TABLE IF EXISTS `garden_unsold_report`");

// create garden_unsold_report
$this->db->query("CREATE TABLE `garden_unsold_report` (
SELECT invoice_entry.id, invoice_entry.sale_no, invoice_entry.lot_no, invoice_entry.w_inv_no, invoice_entry.w_house_id, invoice_entry.valuation, invoice_entry.chest_number, invoice_entry.net_kgs, IFNULL(sale_entry.sold_price,0) AS sold_price, invoice_entry.remarks, invoice_entry.catalouge_name, invoice_entry.grade_id, invoice_entry.additional_grade, invoice_entry.supplement_tea 

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.garden_id='".$garden_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' AND NOT invoice_entry.remarks='W/D' GROUP BY invoice_entry.id HAVING(sold_price=0)) ");

$n_net_kgs = 0;
$n_chest_number = 0;
// warehouse breakdown query
$this->db->select("garden_unsold_report.w_house_id, warehouse_setup.short_key_word"); 
$this->db->from('garden_unsold_report');
$this->db->join('warehouse_setup','garden_unsold_report.w_house_id = warehouse_setup.id','left');
$this->db->group_by('garden_unsold_report.w_house_id');
$this->db->order_by('warehouse_setup.short_key_word ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<table class="table table-sm" id="table">
<thead>
<tr>
    <th colspan="6" style="font-size:14px !important; border:0px !important; text-transform:uppercase;"><?php print $garden_name_result['garden_name']; ?> / <?php print $row1['short_key_word']; ?></th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid; text-transform:uppercase;">Lot No</th>
    <th style="text-align:center; border:1px solid; text-transform:uppercase;">Invoice</th>
    <th style="text-align:left; border:1px solid; text-transform:uppercase;">Grade</th>
	<th style="text-align:right; border:1px solid; text-transform:uppercase;">Net Kgs</th>
	<th style="text-align:center; border:1px solid; text-transform:uppercase;">Chest</th>
	<th style="text-align:center; border:1px solid; text-transform:uppercase;">Value</th>
</tr>
</thead>
<tbody>
<?php
$g_net_kgs = 0;
$g_chest_number = 0;
// sale no breakdown query
$this->db->select("sale_no"); 
$this->db->from('garden_unsold_report');
$this->db->where('w_house_id',$row1['w_house_id']);
$this->db->group_by('sale_no');
$this->db->order_by('sale_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	
// query
$total_kgs = 0;
$net_kgs = 0;
$chest_number = 0;
// lot no breakdown query
$this->db->select("
	garden_unsold_report.sale_no, 
	garden_unsold_report.lot_no, 
	garden_unsold_report.w_inv_no, 
	CONCAT(tea_grade_setup.grade_name,garden_unsold_report.additional_grade) AS grade_name, 
	garden_unsold_report.valuation, 
	IFNULL(garden_unsold_report.sold_price,0) AS sold_price, 
	garden_unsold_report.net_kgs, 
	garden_unsold_report.chest_number, 
	garden_unsold_report.remarks
"); 
$this->db->from('garden_unsold_report');
$this->db->join('tea_grade_setup', 'garden_unsold_report.grade_id = tea_grade_setup.id', 'left');
$this->db->where('w_house_id',$row1['w_house_id']);
$this->db->where('sale_no',$row2['sale_no']);
$this->db->group_by('garden_unsold_report.id');
$this->db->order_by('garden_unsold_report.lot_no ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>

<tr>
    <td style="text-align:center; border:0px;"><?php echo $row3['lot_no']; ?></td>
	<td style="text-align:center; border:0px; text-transform:uppercase;"><?php echo $row3['w_inv_no']; ?></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:center; border:0px; color:#FF69B4;"><?php echo number_format($row3['valuation'],2,'.',''); ?></td>
</tr>

<?php
	$net_kgs += $row3['net_kgs'];
	$chest_number += $row3['chest_number'];
}  // 3rd loop
?>

<tr>
	<th style="border:0px;"></th>
	<th style="border:0px;"></th>
	<th style="text-align:right; text-transform:uppercase; border:0px;">Total : </th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:center; border:0px;"><?php echo $chest_number; ?></th>
	<th style="border:0px;"></th>
</tr>

<?php
	$g_net_kgs += $net_kgs;
	$g_chest_number += $chest_number;
} // 2nd loop	
?>

<tr>
	<th colspan="6" style="padding:10px !important; border:0px !important;"></th>
</tr>
<?php
	$n_net_kgs += $g_net_kgs;
	$n_chest_number += $g_chest_number;
?>
</tbody>
</table>
<?php } // 1st loop ?>

<table class="table table-sm" id="table">
<tbody>
<tr>
	<th style="text-align:center; font-family:courier; font-size:16px !important; text-transform:uppercase; border:1px solid;">Net Total KGS: <?php echo number_format($n_net_kgs,2,'.',''); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Bags:<?php echo $n_chest_number; ?></th>
</tr>
<tbody>
</table>

<br />
<br />

<?php
// total unsold catalog wise
$query = $this->db->query("SELECT 

	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)
	) AS leaf_total_bag,

	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN net_kgs END),0)
	) AS leaf_net_kgs, 

	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)
	) AS dust_total_bag,

	(
		IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN net_kgs END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN net_kgs END),0)
	) AS dust_net_kgs 
	
FROM garden_unsold_report "); 
$catalog_out_result = $query->row_array();

$n_out_bag_l = $catalog_out_result['leaf_total_bag'];
$n_out_kgs_l = $catalog_out_result['leaf_net_kgs'];

$n_out_bag_d = $catalog_out_result['dust_total_bag'];
$n_out_kgs_d = $catalog_out_result['dust_net_kgs'];

// total offering garden wise
$query = $this->db->query("SELECT 

(IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN chest_number END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN chest_number END),0)) AS leaf_offering, 

(IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN chest_number END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN chest_number END),0)) AS dust_offering 

FROM `invoice_entry` WHERE season='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' AND garden_id='".$garden_id."' AND catalogue_status='prepared' AND soft_delete='1'"); 
$offering_result = $query->row_array();

$leaf_offering = $offering_result['leaf_offering'];
$dust_offering = $offering_result['dust_offering'];
$total_offering = $leaf_offering + $dust_offering;
?>
<table class="table table-sm" id="table">
<thead>
<tr>
	<th colspan="4" style="text-align:center; text-transform:uppercase; border:0px; color:#006994;">OUT LOTS SUMMARY</th>
</tr>
<tr>
	<th style="text-align:right; border:1px solid; color:green;"></th>
	<th style="text-align:right; text-transform:uppercase; border:1px solid; color:#006994;">Bags</th>
	<th style="text-align:right; text-transform:uppercase; border:1px solid; color:#006994;">Kgs</th>
	<th style="text-align:right; text-transform:uppercase; border:1px solid; color:#006994;">%</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="text-align:right; text-transform:uppercase; border:0px;"><b>Leaf</b></td>
	<td style="text-align:right; border:0px; color:#006994;"><?php print $n_out_bag_l; ?></td>
	<td style="text-align:right; border:0px; color:#006994;"><?php print number_format($n_out_kgs_l,2,'.',''); ?></td>
	<td style="text-align:right; border:0px; color:#006994;">
	<?php 
		print ($n_out_bag_l > 0 && $leaf_offering > 0) ? number_format(($n_out_bag_l * 100)/$leaf_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:right; text-transform:uppercase; border:0px;"><b>Dust</b></td>
	<td style="text-align:right; border:0px; color:#006994;"><?php print $n_out_bag_d; ?></td>
	<td style="text-align:right; border:0px; color:#006994;"><?php print number_format($n_out_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; border:0px; color:#006994;">
	<?php 
		print ($n_out_bag_d > 0 && $dust_offering > 0) ? number_format(($n_out_bag_d * 100)/$dust_offering,2,'.','') : '0.00'; 
	?>
	</td>
</tr>
<tr>
	<td style="text-align:right; border:1px solid; text-transform:uppercase;"><b>Net Total:</b></td>
	<td style="text-align:right; border:1px solid; color:#006994;"><?php print $n_out_bag_l + $n_out_bag_d; ?></td>
	<td style="text-align:right; border:1px solid; color:#006994;"><?php print number_format($n_out_kgs_l + $n_out_kgs_d,2,'.',''); ?></td>
	<td style="text-align:right; border:1px solid; color:#006994;"><?php print number_format((($n_out_bag_l + $n_out_bag_d) * 100)/$total_offering,2,'.',''); ?></td>
</tr>
</tbody>
</table>

<br />
<br />

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>

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