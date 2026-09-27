<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$report_name = (!empty($this->input->get('report_name', TRUE))) ? $this->input->get('report_name', TRUE) : '';
?>

<div id="printableArea" class="container">

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
	margin-top:10px;
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

<?php if($report_name=='sstmt_sale_to_sale'){ ?>

<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

.btn-sales-qty {
    background: #007bff;
    color: #fff;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 9px;
    text-decoration: none;
    border: 1px solid #007bff;
    display: inline-block;
}

.btn-sales-qty:hover {
    background: #0069d9;
    color: #fff;
    text-decoration: none;
}

@media print {
    .no-print {
        display: none !important;
    }
    .btn-sales-qty {
        display: none !important;
    }
}

@page{
	size:A4 landscape;
	margin-left: 100px;
}
</style>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 45;

$ac_clause = ($auction_center=='All Center') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,	
	gn.garden_name,
	
	@total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
	
	@total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
	
	(@total_bags * IFNULL(invoice_entry.warehouse_charge,0)) AS warehouse_charge,
	(@total_bags * IFNULL(invoice_entry.insp_sample,0)) AS insp_sample,
	((@total_amount * IFNULL(invoice_entry.brokerage_commission,0))/100) AS brokerage_commission,
	((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<?php
$sl = 1;
$sale_date = '';
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;

// query
$this->db->select("
	bill_register.old_season, 
	bill_register.season,
	bill_register.garden_id, 
	IF(
		bill_register.season = bill_register.old_season, bill_register.garden_name, CONCAT(bill_register.garden_name, ' (', bill_register.old_season, ')')
	) AS garden_name, 
	IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
	sum(bill_register.total_bags) AS total_bags, 
	sum(bill_register.total_kgs) AS total_kgs, 
	sum(bill_register.total_amount) AS total_amount, 
	sum(bill_register.warehouse_charge) AS warehouse_charge, 
	sum(bill_register.insp_sample) AS insp_sample, 
	sum(bill_register.brokerage_commission) AS brokerage_commission, 
	sum(bill_register.tea_cess) AS tea_cess, 
	IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
	IFNULL(gat.garden_misc,0) AS garden_misc,
	IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
	IFNULL(gat.truck_fare,0) AS truck_fare,  
	IFNULL(gat.bank_charges,0) AS bank_charges 
"); 	
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, garden_id, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND $ac_clause2 AND `sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' GROUP BY `garden_id`, `old_season`) gat", "bill_register.garden_id = gat.garden_id AND bill_register.old_season = gat.old_season", "left");

$this->db->group_by('bill_register.garden_id, bill_register.old_season');
$this->db->order_by('bill_register.garden_name ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

    // total tk
	$total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

	// total deduction tk
	$total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="18" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;"><?php print $website_title; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="17" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				Account Sales From Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_to; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th>SL</th>
		<th>GARDEN NAME</th>
		<th class="no-print">SALES QTY</th>
		<th>PKGS</th>
		<th>KGS</th>
		<th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
		<th style="white-space:nowrap;">Sold <br /> Per KG</th>
		<th>Tea <br /> Cess</th>
		<th>Warehouse <br /> Charges</th>
		<th>Inspec <br /> -tion</th>
		<th>Brokerage</th>
		<th>Bank <br /> Charges</th>
		<th>Advance</th>
		<th>Truck <br /> Fare</th>
		<th>Garden <br /> Misc.</th>
		<th>Additional <br /> Rent</th>
		<th>Total <br /> Deduction</th>
		<th>Net <br /> Proceeds</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px;">
		<?php print $sl++ ; ?>
	</td>
	<td style="text-align:left; text-transform:uppercase; border:0px; white-space:nowrap;">
		<a target="blink" 
		href="<?php echo site_url(
		'MY_Controller/sellers_stmt_sale_to_sale?season='.$season.
		'&auction_center='.$auction_center.
		'&sale_from='.$sale_from.
		'&sale_to='.$sale_to.
		'&garden_id='.$row3['garden_id'].
		'&report_name=sstmt_sale_to_sale_gw'.
		'&season_type='.(($row3['season'] == $row3['old_season']) ? 'current' : 'old')
		); ?>">
		<?php echo $row3['garden_name']; ?>
		</a>
	</td>
	<td style="text-align:center; border:0px;" class="no-print">
		<a class="btn-sales-qty" target="blink" 
		href="<?php echo site_url(
		'MY_Controller/sellers_stmt_sale_to_sale?season='.$season.
		'&auction_center='.$auction_center.
		'&sale_from='.$sale_from.
		'&sale_to='.$sale_to.
		'&garden_id='.$row3['garden_id'].
		'&report_name=sstmt_sale_to_sale_sq'.
		'&season_type='.(($row3['season'] == $row3['old_season']) ? 'current' : 'old')
		); ?>">
		Sales Qty
		</a>
	</td>
	<td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($row3['total_amount'] / $row3['total_kgs']),2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['warehouse_charge'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['insp_sample'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['brokerage_commission'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['bank_charges'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['garden_adj_tk'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['truck_fare'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['garden_misc'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['add_wh_rent'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_deduction,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_tk,2,'.',','); ?></td>
</tr>
<?php

$total_bags += $row3['total_bags'];
$total_kgs += $row3['total_kgs'];
$total_amount += $row3['total_amount'];
$warehouse_charge += $row3['warehouse_charge'];
$add_wh_rent += $row3['add_wh_rent'];
$insp_sample += $row3['insp_sample'];
$brokerage_commission += $row3['brokerage_commission'];
$bank_charges += $row3['bank_charges'];
$tea_cess += $row3['tea_cess'];
$garden_misc += $row3['garden_misc'];
$garden_adj_tk += $row3['garden_adj_tk'];
$truck_fare += $row3['truck_fare'];
$net_total_deduction += $total_deduction;
$net_total_tk += $total_tk;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan="2" style="text-align:right; color:red;">TOTAL : </th>
	<th class="no-print" style="color:red;"></th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"></th>
	<th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($warehouse_charge,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($insp_sample,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($brokerage_commission,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($bank_charges,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_adj_tk,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($truck_fare,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_misc,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($add_wh_rent,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_deduction,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_tk,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF sellers ?>


<?php if($report_name=='sstmt_sale_to_sale2'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4 landscape;
	margin-left: 100px;
}
</style>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 32;

// Get all parameters
$season_from = (!empty($this->input->get('season_from', TRUE))) ? $this->input->get('season_from', TRUE) : '';
$season_to = (!empty($this->input->get('season_to', TRUE))) ? $this->input->get('season_to', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';

$ac_clause = ($auction_center=='All Center') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// SIMPLE APPROACH: If same season, use original logic. If different, handle separately
if($season_from == $season_to) {
    // Use the ORIGINAL logic for same season
    $this->db->query("CREATE TABLE `bill_register` (
    SELECT 
        invoice_entry.season,
        invoice_entry.old_season,
        invoice_entry.sale_no,
        invoice_entry.sale_date,
        invoice_entry.lot_no,
        invoice_entry.garden_id,	
        gn.garden_name,
        
        @total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
        
        @total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
        
        @total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
        
        (@total_bags * IFNULL(invoice_entry.warehouse_charge,0)) AS warehouse_charge,
        (@total_bags * IFNULL(invoice_entry.insp_sample,0)) AS insp_sample,
        ((@total_amount * IFNULL(invoice_entry.brokerage_commission,0))/100) AS brokerage_commission,
        ((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess    
    FROM invoice_entry  
    JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
    JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 
    WHERE invoice_entry.season='".$season_from."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
} else {
    // For different seasons - get data from both seasons
    $this->db->query("CREATE TABLE `bill_register` (
    SELECT 
        invoice_entry.season,
        invoice_entry.old_season,
        invoice_entry.sale_no,
        invoice_entry.sale_date,
        invoice_entry.lot_no,
        invoice_entry.garden_id,	
        gn.garden_name,
        
        @total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
        
        @total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
        
        @total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
        
        (@total_bags * IFNULL(invoice_entry.warehouse_charge,0)) AS warehouse_charge,
        (@total_bags * IFNULL(invoice_entry.insp_sample,0)) AS insp_sample,
        ((@total_amount * IFNULL(invoice_entry.brokerage_commission,0))/100) AS brokerage_commission,
        ((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess    
    FROM invoice_entry  
    JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
    JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 
    WHERE $ac_clause 
    AND invoice_entry.catalogue_status='prepared' 
    AND invoice_entry.soft_delete='1' 
    AND (
        (invoice_entry.season = '".$season_from."' AND invoice_entry.sale_no >= '".$sale_from."')
        OR
        (invoice_entry.season = '".$season_to."' AND invoice_entry.sale_no <= '".$sale_to."')
        OR
        (invoice_entry.season > '".$season_from."' AND invoice_entry.season < '".$season_to."')
    )
    GROUP BY invoice_entry.id) ");
}
?>

<?php
$sl = 1;
$sale_date = '';
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;

// Use the ORIGINAL query structure - this is proven to work!
$this->db->select("
	bill_register.old_season, 
	bill_register.season,
	bill_register.garden_id, 
	IF(
		bill_register.season = bill_register.old_season, bill_register.garden_name, CONCAT(bill_register.garden_name, ' (', bill_register.old_season, ')')
	) AS garden_name, 
	IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
	sum(bill_register.total_bags) AS total_bags, 
	sum(bill_register.total_kgs) AS total_kgs, 
	sum(bill_register.total_amount) AS total_amount, 
	sum(bill_register.warehouse_charge) AS warehouse_charge, 
	sum(bill_register.insp_sample) AS insp_sample, 
	sum(bill_register.brokerage_commission) AS brokerage_commission, 
	sum(bill_register.tea_cess) AS tea_cess, 
	IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
	IFNULL(gat.garden_misc,0) AS garden_misc,
	IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
	IFNULL(gat.truck_fare,0) AS truck_fare,  
	IFNULL(gat.bank_charges,0) AS bank_charges 
"); 	
$this->db->from('bill_register');

// Build adjustment join based on season type
if($season_from == $season_to) {
    // Original logic for same season
    $this->db->join("(SELECT old_season, garden_id, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE `season`='".$season_from."' AND $ac_clause2 AND `sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' GROUP BY `garden_id`, `old_season`) gat", "bill_register.garden_id = gat.garden_id AND bill_register.old_season = gat.old_season", "left");
} else {
    // For different seasons - get adjustments from both seasons
    $this->db->join("(SELECT old_season, garden_id, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE $ac_clause2 AND (
        (`season` = '".$season_from."' AND `sale_no` >= '".$sale_from."')
        OR
        (`season` = '".$season_to."' AND `sale_no` <= '".$sale_to."')
        OR
        (`season` > '".$season_from."' AND `season` < '".$season_to."')
    ) GROUP BY `garden_id`, `old_season`) gat", "bill_register.garden_id = gat.garden_id AND bill_register.old_season = gat.old_season", "left");
}

$this->db->group_by('bill_register.garden_id, bill_register.old_season');
$this->db->order_by('bill_register.garden_name ASC');
$query = $this->db->get();
$query3 = $query->result_array();

if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

    // total tk
	$total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

	// total deduction tk
	$total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="17" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;"><?php print $website_title; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="16" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				Account Sales From Season &nbsp; <?php print $season_from; ?>&nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season_to; ?>&nbsp; Sale No &nbsp; <?php print $sale_to; ?> &nbsp; <?php print $auction_center; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th>SL</th>
		<th>GARDEN NAME</th>
		<th>PKGS</th>
		<th>KGS</th>
		<th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
		<th style="white-space:nowrap;">Sold <br /> Per KG</th>
		<th>Tea <br /> Cess</th>
		<th>Warehouse <br /> Charges</th>
		<th>Inspec <br /> -tion</th>
		<th>Brokerage</th>
		<th>Bank <br /> Charges</th>
		<th>Advance</th>
		<th>Truck <br /> Fare</th>
		<th>Garden <br /> Misc.</th>
		<th>Additional <br /> Rent</th>
		<th>Total <br /> Deduction</th>
		<th>Net <br /> Proceeds</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px;">
		<?php print $sl++ ; ?>
	</td>
	<td style="text-align:left; text-transform:uppercase; border:0px; white-space:nowrap;">
		<a target="blink" 
		href="<?php echo site_url(
		'MY_Controller/sellers_stmt_sale_to_sale?season_from='.$season_from.
		'&season_to='.$season_to.
		'&auction_center='.$auction_center.
		'&sale_from='.$sale_from.
		'&sale_to='.$sale_to.
		'&garden_id='.$row3['garden_id'].
		'&report_name=sstmt_sale_to_sale_gw2'.
		'&season_type='.(($row3['season'] == $row3['old_season']) ? 'current' : 'old')
		); ?>">
		<?php echo $row3['garden_name']; ?>
		</a>

	</td>

	<td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format(($row3['total_amount'] / $row3['total_kgs']),2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['warehouse_charge'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['insp_sample'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['brokerage_commission'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['bank_charges'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['garden_adj_tk'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['truck_fare'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['garden_misc'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['add_wh_rent'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_deduction,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_tk,2,'.',','); ?></td>
</tr>
<?php

$total_bags += $row3['total_bags'];
$total_kgs += $row3['total_kgs'];
$total_amount += $row3['total_amount'];
$warehouse_charge += $row3['warehouse_charge'];
$add_wh_rent += $row3['add_wh_rent'];
$insp_sample += $row3['insp_sample'];
$brokerage_commission += $row3['brokerage_commission'];
$bank_charges += $row3['bank_charges'];
$tea_cess += $row3['tea_cess'];
$garden_misc += $row3['garden_misc'];
$garden_adj_tk += $row3['garden_adj_tk'];
$truck_fare += $row3['truck_fare'];
$net_total_deduction += $total_deduction;
$net_total_tk += $total_tk;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan="2" style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"></th>
	<th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($warehouse_charge,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($insp_sample,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($brokerage_commission,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($bank_charges,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_adj_tk,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($truck_fare,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_misc,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($add_wh_rent,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_deduction,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_tk,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>
<?php } // IF sellers ?>


<?php if($report_name=='sstmt_sale_to_sale_gw2'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4 landscape;
	margin-left: 100px;
}
</style>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<?php 
// Initialize all variables from GET parameters
$auction_center = isset($_GET['auction_center']) ? $_GET['auction_center'] : '';
$season_from = isset($_GET['season_from']) ? $_GET['season_from'] : '';
$season_to = isset($_GET['season_to']) ? $_GET['season_to'] : '';
$sale_from = isset($_GET['sale_from']) ? $_GET['sale_from'] : '';
$sale_to = isset($_GET['sale_to']) ? $_GET['sale_to'] : '';
$garden_id = isset($_GET['garden_id']) ? $_GET['garden_id'] : 'All';
$report_name = isset($_GET['report_name']) ? $_GET['report_name'] : '';
$season_type = isset($_GET['season_type']) ? $_GET['season_type'] : '';
$website_title = isset($website_title) ? $website_title : '';

// Set season (use season_from as main season)
$season = $season_from;

// get html value
$row_limit_pp = 35;

$ac_clause = ($auction_center=='All Center' || $auction_center=='') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center' || $auction_center=='') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

// Build garden clause - only apply if garden_id is not 'All' or empty
$garden_clause = "";
$garden_clause2 = "";
if(isset($garden_id) && $garden_id != '' && $garden_id != 'All' && $garden_id != 'undefined') {
    $garden_clause = "AND `invoice_entry`.`garden_id`='".$garden_id."' ";
    $garden_clause2 = "AND `garden_adjust_tbl`.`garden_id`='".$garden_id."' ";
}

// Build season and sale range clause with BETWEEN logic
$season_sale_clause = "(
    (`invoice_entry`.`season` = '".$season_from."' AND `invoice_entry`.`sale_no` >= '".$sale_from."')
    OR
    (`invoice_entry`.`season` > '".$season_from."' AND `invoice_entry`.`season` < '".$season_to."')
    OR
    (`invoice_entry`.`season` = '".$season_to."' AND `invoice_entry`.`sale_no` <= '".$sale_to."')
)";

$season_sale_clause2 = "(
    (`garden_adjust_tbl`.`season` = '".$season_from."' AND `garden_adjust_tbl`.`sale_no` >= '".$sale_from."')
    OR
    (`garden_adjust_tbl`.`season` > '".$season_from."' AND `garden_adjust_tbl`.`season` < '".$season_to."')
    OR
    (`garden_adjust_tbl`.`season` = '".$season_to."' AND `garden_adjust_tbl`.`sale_no` <= '".$sale_to."')
)";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 
	'A' As `type`,
	`invoice_entry`.`season`,
	`invoice_entry`.`old_season`,
	`invoice_entry`.`sale_no`,
	`invoice_entry`.`auction_center`,
	`invoice_entry`.`sale_date`,
	`invoice_entry`.`lot_no`,
	`invoice_entry`.`garden_id`,
	'0000000' AS `sample_kgs`,
	
	@total_bags:=(IFNULL(`sale_entry`.`buyer1_bag`,0) + IFNULL(`sale_entry`.`buyer2_bag`,0) + IFNULL(`sale_entry`.`buyer3_bag`,0)) AS `total_bags`,
	
	@total_kgs:=(IFNULL(`sale_entry`.`buyer1_total_kgs`,0) + IFNULL(`sale_entry`.`buyer2_total_kgs`,0) + IFNULL(`sale_entry`.`buyer3_total_kgs`,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(`sale_entry`.`buyer1_amount`,0) + IFNULL(`sale_entry`.`buyer2_amount`,0) + IFNULL(`sale_entry`.`buyer3_amount`,0)) AS `total_amount`,
	
	(@total_bags * IFNULL(`invoice_entry`.`warehouse_charge`,0)) AS `warehouse_charge`,
	(@total_bags * IFNULL(`invoice_entry`.`insp_sample`,0)) AS `insp_sample`,
	((@total_amount * IFNULL(`invoice_entry`.`brokerage_commission`,0))/100) AS `brokerage_commission`,
	((@total_amount * IFNULL(`invoice_entry`.`tea_cess`,0))/100) AS `tea_cess`	

FROM `invoice_entry`  
JOIN `sale_entry` ON (`invoice_entry`.`id` = `sale_entry`.`invoice_id`)  

WHERE $ac_clause AND $season_sale_clause $garden_clause AND `invoice_entry`.`catalogue_status`='prepared' AND `invoice_entry`.`soft_delete`='1' GROUP BY `invoice_entry`.`id`) ");


$this->db->query("
    INSERT INTO `bill_register` (
        `type`,
        `season`,
        `old_season`,
        `sale_no`,
        `auction_center`,
        `sale_date`,
        `lot_no`,
        `garden_id`,
        `total_bags`,
        `total_kgs`,
        `total_amount`,
        `sample_kgs`,
        `warehouse_charge`,
        `insp_sample`,
        `brokerage_commission`,
        `tea_cess`
    )
    SELECT 
        'B',
        `invoice_entry`.`season`,
        `invoice_entry`.`old_season`,
        `invoice_entry`.`sale_no`,
        `invoice_entry`.`auction_center`,
        `invoice_entry`.`sale_date`,
        `invoice_entry`.`lot_no`,
        `invoice_entry`.`garden_id`,
        '0',
        '0',
		'0',

        (IFNULL(`invoice_entry`.`sample_kgs`, 0) + IFNULL(CASE WHEN NOT `invoice_entry`.`weight_short` = 'y' THEN `invoice_entry`.`additional_sample_kgs` END, 0)) AS `sample_kgs`,

		'0',
		'0',
		'0',
		'0'

    FROM `invoice_entry`

    WHERE $ac_clause AND $season_sale_clause $garden_clause AND `invoice_entry`.`catalogue_status` = 'prepared' AND `invoice_entry`.`soft_delete` = '1'
");

?>

<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$sample_kgs = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;

// query - ADD season to the select statement
$this->db->select("
	bill_register.season AS season,
	bill_register.sale_no AS sale_no,
	IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
	sum(bill_register.total_bags) AS total_bags, 
	sum(bill_register.total_kgs) AS total_kgs, 
	sum(bill_register.sample_kgs) AS sample_kgs, 
	sum(bill_register.total_amount) AS total_amount, 
	sum(bill_register.warehouse_charge) AS warehouse_charge, 
	sum(bill_register.insp_sample) AS insp_sample, 
	sum(bill_register.brokerage_commission) AS brokerage_commission, 
	sum(bill_register.tea_cess) AS tea_cess, 
	bill_register.auction_center AS auction_center,
	bill_register.sale_date AS sale_date,
	IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
	IFNULL(gat.garden_misc,0) AS garden_misc,
	IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
	IFNULL(gat.truck_fare,0) AS truck_fare, 
	IFNULL(gat.bank_charges,0) AS bank_charges 
"); 	
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, sale_no, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE $ac_clause2 AND $season_sale_clause2 $garden_clause2 GROUP BY `sale_no`, `old_season`) gat", "bill_register.sale_no = gat.sale_no AND bill_register.old_season = gat.old_season", "left");

// Filter by season type (from URL param)
if ($season_type == 'old') {
    $this->db->where("bill_register.season <> bill_register.old_season");
} elseif ($season_type == 'current') {
    $this->db->where("bill_register.season = bill_register.old_season");
}
$this->db->group_by('bill_register.sale_no, bill_register.old_season, bill_register.auction_center');
$this->db->order_by('bill_register.season ASC, bill_register.sale_no ASC');
$query = $this->db->get();
$query3 = $query->result_array();

if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

    // total tk
	$total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

	// total deduction tk
	$total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));

	$sold_per_kgs = (!empty($row3['total_amount']) && !empty($row3['total_kgs'])) ? $row3['total_amount'] / $row3['total_kgs'] : '0.00';

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	

// garden details - only query if garden_id is set and not 'All'
$garden_name = "All Gardens";
if(isset($garden_id) && $garden_id != '' && $garden_id != 'All' && $garden_id != 'undefined') {
    $query_garden = $this->db->query("SELECT `garden_name` FROM `garden_and_company_setup` WHERE `id`='".$garden_id."' ");
    $garden_data = $query_garden->row_array();
    if(!empty($garden_data)) {
        $garden_name = $garden_data['garden_name'];
    }
}
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="18" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;"><?php print $website_title; ?></b><br />
            <b style="font-size:12px;">Garden Name: <?php print $garden_name; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="17" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				Account Sales From Season &nbsp; <?php print $season_from; ?>&nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season_to; ?>&nbsp; Sale No &nbsp; <?php print $sale_to; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th>SALE NO</th>
		<th>Season</th>
		<th>Sale <br /> Date</th>
		<th>PKGS</th>
		<th>KGS</th>
		<th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
		<th style="white-space:nowrap;">Sold <br /> Per KG</th>
		<th>Sample <br /> Draw</th>
		<th>Tea <br /> Cess</th>
		<th>Warehouse <br /> Charges</th>
		<th>Inspec <br /> -tion</th>
		<th>Brokerage</th>
		<th>Bank <br /> Charges</th>
		<th>Advance</th>
		<th>Truck <br /> Fare</th>
		<th>Garden <br /> Misc.</th>
		<th>Additional <br /> Rent</th>
		<th>Total <br /> Deduction</th>
		<th>Net <br /> Proceeds</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['sale_no']) ? $row3['sale_no'] : ''; ?></td>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['season']) ? $row3['season'] : ''; ?></td>
	<td style="text-align:center; border:0px; white-space:nowrap;"><?php echo isset($row3['sale_date']) ? date("d-m-y", strtotime($row3['sale_date'])) : ''; ?></td>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['total_bags']) ? $row3['total_bags'] : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['total_kgs']) ? number_format($row3['total_kgs'],2,'.','') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['total_amount']) ? number_format($row3['total_amount'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($sold_per_kgs,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['sample_kgs']) ? number_format($row3['sample_kgs'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['tea_cess']) ? number_format($row3['tea_cess'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['warehouse_charge']) ? number_format($row3['warehouse_charge'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['insp_sample']) ? number_format($row3['insp_sample'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['brokerage_commission']) ? number_format($row3['brokerage_commission'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['bank_charges']) ? number_format($row3['bank_charges'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['garden_adj_tk']) ? number_format($row3['garden_adj_tk'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['truck_fare']) ? number_format($row3['truck_fare'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['garden_misc']) ? number_format($row3['garden_misc'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo isset($row3['add_wh_rent']) ? number_format($row3['add_wh_rent'],2,'.',',') : ''; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_deduction,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_tk,2,'.',','); ?></td>
</tr>
<?php

	$total_bags += isset($row3['total_bags']) ? $row3['total_bags'] : 0;
	$total_kgs += isset($row3['total_kgs']) ? $row3['total_kgs'] : 0;
	$total_amount += isset($row3['total_amount']) ? $row3['total_amount'] : 0;
	$warehouse_charge += isset($row3['warehouse_charge']) ? $row3['warehouse_charge'] : 0;
	$add_wh_rent += isset($row3['add_wh_rent']) ? $row3['add_wh_rent'] : 0;
	$insp_sample += isset($row3['insp_sample']) ? $row3['insp_sample'] : 0;
	$brokerage_commission += isset($row3['brokerage_commission']) ? $row3['brokerage_commission'] : 0;
	$bank_charges += isset($row3['bank_charges']) ? $row3['bank_charges'] : 0;
	$sample_kgs += isset($row3['sample_kgs']) ? $row3['sample_kgs'] : 0;
	$tea_cess += isset($row3['tea_cess']) ? $row3['tea_cess'] : 0;
	$garden_misc += isset($row3['garden_misc']) ? $row3['garden_misc'] : 0;
	$garden_adj_tk += isset($row3['garden_adj_tk']) ? $row3['garden_adj_tk'] : 0;
	$truck_fare += isset($row3['truck_fare']) ? $row3['truck_fare'] : 0;
	$net_total_deduction += $total_deduction;
	$net_total_tk += $total_tk;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;

// Only show TOTAL row if it's the last page (i == total_records)
if($i==$total_records){
?>
<tr>
	<th colspan="3" style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"></th>
	<th style="text-align:right; color:red;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($warehouse_charge,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($insp_sample,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($brokerage_commission,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($bank_charges,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_adj_tk,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($truck_fare,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_misc,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($add_wh_rent,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_deduction,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_tk,2,'.',','); ?></th>
</tr>
<?php } ?>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>
<?php } // IF sellers ?>

<?php if($report_name=='sstmt_sale_to_sale_sq2'){ ?>

<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
	text-align:center !important;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
	text-align:center !important;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center !important;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4 landscape;
	margin-left: 100px;
}
</style>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<?php 
// Initialize all variables from GET parameters
$auction_center = isset($_GET['auction_center']) ? $_GET['auction_center'] : '';
$season_from = isset($_GET['season_from']) ? $_GET['season_from'] : '';
$season_to = isset($_GET['season_to']) ? $_GET['season_to'] : '';
$sale_from = isset($_GET['sale_from']) ? $_GET['sale_from'] : '';
$sale_to = isset($_GET['sale_to']) ? $_GET['sale_to'] : '';
$garden_id = isset($_GET['garden_id']) ? $_GET['garden_id'] : 'All';
$report_name = isset($_GET['report_name']) ? $_GET['report_name'] : '';
$season_type = isset($_GET['season_type']) ? $_GET['season_type'] : '';
$website_title = isset($website_title) ? $website_title : '';

// Set season (use season_from as main season)
$season = $season_from;

// get html value
$row_limit_pp = 28;

$ac_clause = ($auction_center=='All Center' || $auction_center=='') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center' || $auction_center=='') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

// Build garden clause - only apply if garden_id is not 'All' or empty
$garden_clause = "";
$garden_clause2 = "";
if(isset($garden_id) && $garden_id != '' && $garden_id != 'All' && $garden_id != 'undefined') {
    $garden_clause = "AND `invoice_entry`.`garden_id`='".$garden_id."' ";
    $garden_clause2 = "AND `garden_adjust_tbl`.`garden_id`='".$garden_id."' ";
}

// Build season and sale range clause with BETWEEN logic
$season_sale_clause = "(
    (`invoice_entry`.`season` = '".$season_from."' AND `invoice_entry`.`sale_no` >= '".$sale_from."')
    OR
    (`invoice_entry`.`season` > '".$season_from."' AND `invoice_entry`.`season` < '".$season_to."')
    OR
    (`invoice_entry`.`season` = '".$season_to."' AND `invoice_entry`.`sale_no` <= '".$sale_to."')
)";

$season_sale_clause2 = "(
    (`garden_adjust_tbl`.`season` = '".$season_from."' AND `garden_adjust_tbl`.`sale_no` >= '".$sale_from."')
    OR
    (`garden_adjust_tbl`.`season` > '".$season_from."' AND `garden_adjust_tbl`.`season` < '".$season_to."')
    OR
    (`garden_adjust_tbl`.`season` = '".$season_to."' AND `garden_adjust_tbl`.`sale_no` <= '".$sale_to."')
)";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 
	'A' As `type`,
	`invoice_entry`.`season`,
	`invoice_entry`.`old_season`,
	`invoice_entry`.`sale_no`,
	`invoice_entry`.`auction_center`,
	`invoice_entry`.`sale_date`,
	`invoice_entry`.`lot_no`,
	`invoice_entry`.`garden_id`,
	'0000000' AS `sample_kgs`,
	
	@total_bags:=(IFNULL(`sale_entry`.`buyer1_bag`,0) + IFNULL(`sale_entry`.`buyer2_bag`,0) + IFNULL(`sale_entry`.`buyer3_bag`,0)) AS `total_bags`,
	
	@total_kgs:=(IFNULL(`sale_entry`.`buyer1_total_kgs`,0) + IFNULL(`sale_entry`.`buyer2_total_kgs`,0) + IFNULL(`sale_entry`.`buyer3_total_kgs`,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(`sale_entry`.`buyer1_amount`,0) + IFNULL(`sale_entry`.`buyer2_amount`,0) + IFNULL(`sale_entry`.`buyer3_amount`,0)) AS `total_amount`,
	
	(@total_bags * IFNULL(`invoice_entry`.`warehouse_charge`,0)) AS `warehouse_charge`,
	(@total_bags * IFNULL(`invoice_entry`.`insp_sample`,0)) AS `insp_sample`,
	((@total_amount * IFNULL(`invoice_entry`.`brokerage_commission`,0))/100) AS `brokerage_commission`,
	((@total_amount * IFNULL(`invoice_entry`.`tea_cess`,0))/100) AS `tea_cess`	

FROM `invoice_entry`  
JOIN `sale_entry` ON (`invoice_entry`.`id` = `sale_entry`.`invoice_id`)  

WHERE $ac_clause AND $season_sale_clause $garden_clause AND `invoice_entry`.`catalogue_status`='prepared' AND `invoice_entry`.`soft_delete`='1' GROUP BY `invoice_entry`.`id`) ");


$this->db->query("
    INSERT INTO `bill_register` (
        `type`,
        `season`,
        `old_season`,
        `sale_no`,
        `auction_center`,
        `sale_date`,
        `lot_no`,
        `garden_id`,
        `total_bags`,
        `total_kgs`,
        `total_amount`,
        `sample_kgs`,
        `warehouse_charge`,
        `insp_sample`,
        `brokerage_commission`,
        `tea_cess`
    )
    SELECT 
        'B',
        `invoice_entry`.`season`,
        `invoice_entry`.`old_season`,
        `invoice_entry`.`sale_no`,
        `invoice_entry`.`auction_center`,
        `invoice_entry`.`sale_date`,
        `invoice_entry`.`lot_no`,
        `invoice_entry`.`garden_id`,
        '0',
        '0',
		'0',

        (IFNULL(`invoice_entry`.`sample_kgs`, 0) + IFNULL(CASE WHEN NOT `invoice_entry`.`weight_short` = 'y' THEN `invoice_entry`.`additional_sample_kgs` END, 0)) AS `sample_kgs`,

		'0',
		'0',
		'0',
		'0'

    FROM `invoice_entry`

    WHERE $ac_clause AND $season_sale_clause $garden_clause AND `invoice_entry`.`catalogue_status` = 'prepared' AND `invoice_entry`.`soft_delete` = '1'
");

?>

<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$sample_kgs = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;

// query - ADD season to the select statement
$this->db->select("
	bill_register.season AS season,
	bill_register.sale_no AS sale_no,
	IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
	sum(bill_register.total_bags) AS total_bags, 
	sum(bill_register.total_kgs) AS total_kgs, 
	sum(bill_register.sample_kgs) AS sample_kgs, 
	sum(bill_register.total_amount) AS total_amount, 
	sum(bill_register.warehouse_charge) AS warehouse_charge, 
	sum(bill_register.insp_sample) AS insp_sample, 
	sum(bill_register.brokerage_commission) AS brokerage_commission, 
	sum(bill_register.tea_cess) AS tea_cess, 
	bill_register.auction_center AS auction_center,
	bill_register.sale_date AS sale_date,
	IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
	IFNULL(gat.garden_misc,0) AS garden_misc,
	IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
	IFNULL(gat.truck_fare,0) AS truck_fare, 
	IFNULL(gat.bank_charges,0) AS bank_charges 
"); 	
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, sale_no, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE $ac_clause2 AND $season_sale_clause2 $garden_clause2 GROUP BY `sale_no`, `old_season`) gat", "bill_register.sale_no = gat.sale_no AND bill_register.old_season = gat.old_season", "left");

// Filter by season type (from URL param)
if ($season_type == 'old') {
    $this->db->where("bill_register.season <> bill_register.old_season");
} elseif ($season_type == 'current') {
    $this->db->where("bill_register.season = bill_register.old_season");
}
$this->db->group_by('bill_register.sale_no, bill_register.old_season, bill_register.auction_center');
$this->db->order_by('bill_register.season ASC, bill_register.sale_no ASC');
$query = $this->db->get();
$query3 = $query->result_array();

if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

    // total tk
	$total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

	// total deduction tk
	$total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));

	$sold_per_kgs = (!empty($row3['total_amount']) && !empty($row3['total_kgs'])) ? $row3['total_amount'] / $row3['total_kgs'] : '0.00';

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	

// garden details - only query if garden_id is set and not 'All'
$garden_name = "All Gardens";
if(isset($garden_id) && $garden_id != '' && $garden_id != 'All' && $garden_id != 'undefined') {
    $query_garden = $this->db->query("SELECT `garden_name` FROM `garden_and_company_setup` WHERE `id`='".$garden_id."' ");
    $garden_data = $query_garden->row_array();
    if(!empty($garden_data)) {
        $garden_name = $garden_data['garden_name'];
    }
}
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="9" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;"><?php print $website_title; ?></b><br />
            <b style="font-size:12px;">Garden Name: <?php print $garden_name; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				Account Sales From Season &nbsp; <?php print $season_from; ?>&nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season_to; ?>&nbsp; Sale No &nbsp; <?php print $sale_to; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th style="white-space:nowrap;">SALE NO</th>
		<th style="white-space:nowrap;">AUC</th>
		<th style="white-space:nowrap;">SALE DATE</th>
		<th style="white-space:nowrap;">PKGS</th>
		<th style="white-space:nowrap;">KGS</th>
		<th style="white-space:nowrap;">SOLD PER KG</th>
		<th style="white-space:nowrap;">SAMPLE DRAW</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['sale_no']) ? $row3['sale_no'] : ''; ?></td>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['auction_center']) ? $row3['auction_center'] : ''; ?></td>
	<td style="text-align:center; border:0px; white-space:nowrap;"><?php echo isset($row3['sale_date']) ? date("d-m-y", strtotime($row3['sale_date'])) : ''; ?></td>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['total_bags']) ? $row3['total_bags'] : ''; ?></td>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['total_kgs']) ? number_format($row3['total_kgs'],2,'.','') : ''; ?></td>
	<td style="text-align:center; border:0px;"><?php echo number_format($sold_per_kgs,2,'.',','); ?></td>
	<td style="text-align:center; border:0px;"><?php echo isset($row3['sample_kgs']) ? number_format($row3['sample_kgs'],2,'.',',') : ''; ?></td>
</tr>
<?php

	$total_bags += isset($row3['total_bags']) ? $row3['total_bags'] : 0;
	$total_kgs += isset($row3['total_kgs']) ? $row3['total_kgs'] : 0;
	$total_amount += isset($row3['total_amount']) ? $row3['total_amount'] : 0;
	$warehouse_charge += isset($row3['warehouse_charge']) ? $row3['warehouse_charge'] : 0;
	$add_wh_rent += isset($row3['add_wh_rent']) ? $row3['add_wh_rent'] : 0;
	$insp_sample += isset($row3['insp_sample']) ? $row3['insp_sample'] : 0;
	$brokerage_commission += isset($row3['brokerage_commission']) ? $row3['brokerage_commission'] : 0;
	$bank_charges += isset($row3['bank_charges']) ? $row3['bank_charges'] : 0;
	$sample_kgs += isset($row3['sample_kgs']) ? $row3['sample_kgs'] : 0;
	$tea_cess += isset($row3['tea_cess']) ? $row3['tea_cess'] : 0;
	$garden_misc += isset($row3['garden_misc']) ? $row3['garden_misc'] : 0;
	$garden_adj_tk += isset($row3['garden_adj_tk']) ? $row3['garden_adj_tk'] : 0;
	$truck_fare += isset($row3['truck_fare']) ? $row3['truck_fare'] : 0;
	$net_total_deduction += $total_deduction;
	$net_total_tk += $total_tk;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;

// Only show TOTAL row if it's the last page (i == total_records)
if($i==$total_records){
?>
<tr>
	<th style="text-align:center; color:red; white-space:nowrap;">TOTAL</th>
	<th style="text-align:center; color:red;"></th>
	<th style="text-align:center; color:red;"></th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:center; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:center; color:red;"></th>
	<th style="text-align:center; color:red;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
</tr>
<?php } ?>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF sellers ?>

<?php if($report_name=='seles_stmt_grand_total'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4 landscape;
	margin-left: 100px;
}
</style>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<?php
$ac_clause = ($auction_center=='All Center') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.season,
	invoice_entry.auction_center,
	invoice_entry.sale_no,
	invoice_entry.old_season,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,	
	gn.garden_name,
	
	@total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
	
	@total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
	
	(@total_bags * IFNULL(invoice_entry.warehouse_charge,0)) AS warehouse_charge,
	(@total_bags * IFNULL(invoice_entry.insp_sample,0)) AS insp_sample,
	((@total_amount * IFNULL(invoice_entry.brokerage_commission,0))/100) AS brokerage_commission,
	((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<table class="table table-sm" id="table">
<thead>
<tr>
	<td colspan="13" style="text-transform: uppercase; text-align:center; border:0px;">
	<b style="font-size:15px;"><?php print $website_title; ?></b><br />
	<b style="font-size:13px; font-weight:normal;"><u><?php print $owner_short_address; ?></u><br />
	SELES STMT GRAND TOTAL <br /> <?php print $auction_center; ?></b></td>
</tr>
<tr>
	<td colspan="13" style="text-transform:uppercase; text-align:center; border:0px;"><b>Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp; &nbsp; &nbsp; &nbsp; Season: <?php print $season; ?></b></td>
</tr> 

<tr>
    <th>AUCTION CENTER</th>
    <th>SALE NO.</th>
	<th>BAGS</th>
	<th>KGS</th>
	<th>AMOUNT</th>
	<th>W/H <br />RENT</th>
	<th>Add. W/H <br />RENT</th>
	<th>Insp. & <br />Sampl.</th>
	<th>Brokerage</th>
	<th>Tea Cess</th>
	<th>LEVY <br />Chrg</th>
	<th>Others</th>
	<th>Truck <br /> Fare</th>
	<th>TOTAL TK.</th>
</tr>
</thead>
<tbody>
<?php
$sale_date = '';
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$tea_cess = 0;
$lavy_charge = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_tk = 0;

// query
$this->db->select("
	bill_register.auction_center, 
	bill_register.sale_no, 
"); 	
$this->db->from('bill_register');
$this->db->group_by('bill_register.auction_center, bill_register.sale_no');
$fetch_list = $this->db->get();
foreach($fetch_list->result_array() as $fetch){
?>
<?php
$sale_date = '';
$total_bags1 = 0;
$total_kgs1 = 0;
$total_amount1 = 0;
$warehouse_charge1 = 0;
$add_wh_rent1 = 0;
$insp_sample1 = 0;
$brokerage_commission1 = 0;
$tea_cess1 = 0;
$lavy_charge1 = 0;
$garden_adj_tk1 = 0;
$truck_fare1 = 0;
$net_total_tk1 = 0;

// query
$this->db->select("
	bill_register.auction_center, 
	bill_register.sale_no, 
	bill_register.old_season, 
	bill_register.garden_id, 
	IF(
		bill_register.season = bill_register.old_season, bill_register.garden_name, CONCAT(bill_register.garden_name, ' (', bill_register.old_season, ')')
	) AS garden_name, 
	IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
	sum(bill_register.total_bags) AS total_bags, 
	sum(bill_register.total_kgs) AS total_kgs, 
	sum(bill_register.total_amount) AS total_amount, 
	sum(bill_register.warehouse_charge) AS warehouse_charge, 
	sum(bill_register.insp_sample) AS insp_sample, 
	sum(bill_register.brokerage_commission) AS brokerage_commission, 
	sum(bill_register.tea_cess) AS tea_cess, 
	IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
	IFNULL(gat.lavy_charge,0) AS lavy_charge,
	IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
	IFNULL(gat.truck_fare,0) AS truck_fare  
"); 	
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, garden_id, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Lavy Charge' THEN charge_tk END) AS lavy_charge, sum(CASE WHEN charges_name='Others' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND $ac_clause2 AND `sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' GROUP BY `garden_id`, `old_season`) gat", "bill_register.garden_id = gat.garden_id AND bill_register.old_season = gat.old_season", "left");

$this->db->where('bill_register.auction_center', $fetch['auction_center']);
$this->db->where('bill_register.sale_no', $fetch['sale_no']);
$this->db->group_by('bill_register.auction_center, bill_register.sale_no');
$this->db->order_by('bill_register.sale_no ASC');
$query3 = $this->db->get();
foreach($query3->result_array() as $row3){
	
	// total tk
	$total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['lavy_charge'] + $row3['garden_adj_tk'] + $row3['truck_fare']));
?>
<tr>
	<td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row3['auction_center']; ?></td>
	<td style="text-align:center; text-transform:uppercase; border:0px;"><?php echo $row3['sale_no']; ?></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['warehouse_charge'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['add_wh_rent'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['insp_sample'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['brokerage_commission'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['lavy_charge'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['garden_adj_tk'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['truck_fare'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_tk,2,'.',','); ?></td>
</tr>
<?php
	$total_bags1 += $row3['total_bags'];
	$total_kgs1 += $row3['total_kgs'];
	$total_amount1 += $row3['total_amount'];
	$warehouse_charge1 += $row3['warehouse_charge'];
	$add_wh_rent1 += $row3['add_wh_rent'];
	$insp_sample1 += $row3['insp_sample'];
	$brokerage_commission1 += $row3['brokerage_commission'];
	$tea_cess1 += $row3['tea_cess'];
	$lavy_charge1 += $row3['lavy_charge'];
	$garden_adj_tk1 += $row3['garden_adj_tk'];
	$truck_fare1 += $row3['truck_fare'];
	$net_total_tk1 += $total_tk;
}  // 1st loop
?>
<?php 
	$total_bags += $total_bags1;
	$total_kgs += $total_kgs1;
	$total_amount += $total_amount1;
	$warehouse_charge += $warehouse_charge1;
	$add_wh_rent += $add_wh_rent1;
	$insp_sample += $insp_sample1;
	$brokerage_commission += $brokerage_commission1;
	$tea_cess += $tea_cess1;
	$lavy_charge += $lavy_charge1;
	$garden_adj_tk += $garden_adj_tk1;
	$truck_fare += $truck_fare1;
	$net_total_tk += $net_total_tk1;
} // end 1st loop ?>
<tr>
	<th style="text-align:right; color:red;" colspan="2">TOTAL : </th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($warehouse_charge,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($add_wh_rent,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($insp_sample,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($brokerage_commission,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($lavy_charge,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_adj_tk,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($truck_fare,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_tk,2,'.',','); ?></th>
</tr>
</tbody>
</table>

</div> <!-- col -->
<div class="col-sm-12 text-left" style="font-family: Arial, sans-serif; font-size: 13px; color: #555; margin: 10px 0;">
    <span style="color: #333;">Developed by</span> 
    <strong style="color: #007bff; font-weight: bold;"><?php print $author; ?></strong>
</div>
</div> <!-- row -->
<?php } // IF sellers ?>

<?php if($report_name=='sstmt_sale_to_sale_gw'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4 landscape;
	margin-left: 100px;
}
</style>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 36;

$ac_clause = ($auction_center=='All Center') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 
	'A' As `type`,
	`invoice_entry`.`season`,
	`invoice_entry`.`old_season`,
	`invoice_entry`.`sale_no`,
	`invoice_entry`.`auction_center`,
	`invoice_entry`.`sale_date`,
	`invoice_entry`.`lot_no`,
	`invoice_entry`.`garden_id`,
	'0000000' AS `sample_kgs`,
	
	@total_bags:=(IFNULL(`sale_entry`.`buyer1_bag`,0) + IFNULL(`sale_entry`.`buyer2_bag`,0) + IFNULL(`sale_entry`.`buyer3_bag`,0)) AS `total_bags`,
	
	@total_kgs:=(IFNULL(`sale_entry`.`buyer1_total_kgs`,0) + IFNULL(`sale_entry`.`buyer2_total_kgs`,0) + IFNULL(`sale_entry`.`buyer3_total_kgs`,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(`sale_entry`.`buyer1_amount`,0) + IFNULL(`sale_entry`.`buyer2_amount`,0) + IFNULL(`sale_entry`.`buyer3_amount`,0)) AS `total_amount`,
	
	(@total_bags * IFNULL(`invoice_entry`.`warehouse_charge`,0)) AS `warehouse_charge`,
	(@total_bags * IFNULL(`invoice_entry`.`insp_sample`,0)) AS `insp_sample`,
	((@total_amount * IFNULL(`invoice_entry`.`brokerage_commission`,0))/100) AS `brokerage_commission`,
	((@total_amount * IFNULL(`invoice_entry`.`tea_cess`,0))/100) AS `tea_cess`	

FROM `invoice_entry`  
JOIN `sale_entry` ON (`invoice_entry`.`id` = `sale_entry`.`invoice_id`)  

WHERE `invoice_entry`.season='".$season."' AND $ac_clause AND `invoice_entry`.`sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `invoice_entry`.`garden_id`='".$garden_id."' AND `invoice_entry`.`catalogue_status`='prepared' AND `invoice_entry`.`soft_delete`='1' GROUP BY `invoice_entry`.`id`) ");


$this->db->query("
    INSERT INTO `bill_register` (
        `type`,
        `season`,
        `old_season`,
        `sale_no`,
        `auction_center`,
        `sale_date`,
        `lot_no`,
        `garden_id`,
        `total_bags`,
        `total_kgs`,
        `total_amount`,
        `sample_kgs`,
        `warehouse_charge`,
        `insp_sample`,
        `brokerage_commission`,
        `tea_cess`
    )
    SELECT 
        'B',
        `invoice_entry`.`season`,
        `invoice_entry`.`old_season`,
        `invoice_entry`.`sale_no`,
        `invoice_entry`.`auction_center`,
        `invoice_entry`.`sale_date`,
        `invoice_entry`.`lot_no`,
        `invoice_entry`.`garden_id`,
        '0',
        '0',
		'0',

        (IFNULL(`invoice_entry`.`sample_kgs`, 0) + IFNULL(CASE WHEN NOT `invoice_entry`.`weight_short` = 'y' THEN `invoice_entry`.`additional_sample_kgs` END, 0)) AS `sample_kgs`,

		'0',
		'0',
		'0',
		'0'

    FROM `invoice_entry`

    WHERE `invoice_entry`.`season` = '".$season."' AND $ac_clause AND `invoice_entry`.`sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `invoice_entry`.`garden_id` = '".$garden_id."' AND `invoice_entry`.`catalogue_status` = 'prepared' AND `invoice_entry`.`soft_delete` = '1'
");

?>

<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$sample_kgs = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;

// query
$this->db->select("
	IF(
		bill_register.season = bill_register.old_season, bill_register.sale_no, CONCAT(bill_register.sale_no, ' (', bill_register.old_season, ')')
	) AS `sale_no`, 
	IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
	sum(bill_register.total_bags) AS total_bags, 
	sum(bill_register.total_kgs) AS total_kgs, 
	sum(bill_register.sample_kgs) AS sample_kgs, 
	sum(bill_register.total_amount) AS total_amount, 
	sum(bill_register.warehouse_charge) AS warehouse_charge, 
	sum(bill_register.insp_sample) AS insp_sample, 
	sum(bill_register.brokerage_commission) AS brokerage_commission, 
	sum(bill_register.tea_cess) AS tea_cess, 
	bill_register.auction_center AS auction_center,
	bill_register.sale_date AS sale_date,
	IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
	IFNULL(gat.garden_misc,0) AS garden_misc,
	IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
	IFNULL(gat.truck_fare,0) AS truck_fare, 
	IFNULL(gat.bank_charges,0) AS bank_charges 
"); 	
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, sale_no, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND $ac_clause2 AND `sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `garden_id`='".$garden_id."' GROUP BY `sale_no`, `old_season`) gat", "bill_register.sale_no = gat.sale_no AND bill_register.old_season = gat.old_season", "left");
// Filter by season type (from URL param)
$season_type = $this->input->get('season_type');
if ($season_type == 'old') {
    $this->db->where("bill_register.season <> bill_register.old_season");
} elseif ($season_type == 'current') {
    $this->db->where("bill_register.season = bill_register.old_season");
}
$this->db->group_by('bill_register.sale_no, bill_register.old_season, bill_register.auction_center');
$this->db->order_by('bill_register.sale_no ASC, bill_register.sale_date ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

    // total tk
	$total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

	// total deduction tk
	$total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));

	$sold_per_kgs = (!empty($row3['total_amount']) && !empty($row3['total_kgs'])) ? $row3['total_amount'] / $row3['total_kgs'] : '0.00';

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	

// garden details
$query = $this->db->query("SELECT `garden_name` FROM `garden_and_company_setup` WHERE `id`='".$garden_id."' ");
$garden_data = $query->row_array();
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="18" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;">Garden Name: <?php print $garden_data['garden_name']; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="17" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				Account Sales From Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_to; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th>SALE NO</th>
		<th>Auc.</th>
		<th>Sale <br /> Date</th>
		<th>PKGS</th>
		<th>KGS</th>
		<th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
		<th style="white-space:nowrap;">Sold <br /> Per KG</th>
		<th>Sample <br /> Draw</th>
		<th>Tea <br /> Cess</th>
		<th>Warehouse <br /> Charges</th>
		<th>Inspec <br /> -tion</th>
		<th>Brokerage</th>
		<th>Bank <br /> Charges</th>
		<th>Advance</th>
		<th>Truck <br /> Fare</th>
		<th>Garden <br /> Misc.</th>
		<th>Additional <br /> Rent</th>
		<th>Total <br /> Deduction</th>
		<th>Net <br /> Proceeds</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px;"><?php echo $row3['sale_no']; ?></a></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['auction_center']; ?></td>
	<td style="text-align:center; border:0px; white-space:nowrap;"><?php echo date("d-m-y", strtotime($row3['sale_date'])); ?></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($sold_per_kgs,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sample_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['warehouse_charge'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['insp_sample'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['brokerage_commission'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['bank_charges'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['garden_adj_tk'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['truck_fare'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['garden_misc'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['add_wh_rent'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_deduction,2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($total_tk,2,'.',','); ?></td>
</tr>
<?php

	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$warehouse_charge += $row3['warehouse_charge'];
	$add_wh_rent += $row3['add_wh_rent'];
	$insp_sample += $row3['insp_sample'];
	$brokerage_commission += $row3['brokerage_commission'];
	$bank_charges += $row3['bank_charges'];
	$sample_kgs += $row3['sample_kgs'];
	$tea_cess += $row3['tea_cess'];
	$garden_misc += $row3['garden_misc'];
	$garden_adj_tk += $row3['garden_adj_tk'];
	$truck_fare += $row3['truck_fare'];
	$net_total_deduction += $total_deduction;
	$net_total_tk += $total_tk;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan="3" style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"></th>
	<th style="text-align:right; color:red;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($warehouse_charge,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($insp_sample,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($brokerage_commission,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($bank_charges,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_adj_tk,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($truck_fare,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($garden_misc,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($add_wh_rent,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_deduction,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($net_total_tk,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF sellers ?>

<?php if($report_name=='sstmt_sale_to_sale_sq'){ ?>

<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
	text-align:center !important;
	vertical-align:middle !important;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
	text-align:center !important;
	vertical-align:middle !important;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center !important;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
	vertical-align:middle !important;
}

@page{
	size:A4 landscape;
	margin-left: 100px;
}
</style>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 28;

$ac_clause = ($auction_center=='All Center') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1,2,3
$this->db->query("CREATE TABLE `bill_register` (
SELECT 
	'A' As `type`,
	`invoice_entry`.`season`,
	`invoice_entry`.`old_season`,
	`invoice_entry`.`sale_no`,
	`invoice_entry`.`auction_center`,
	`invoice_entry`.`sale_date`,
	`invoice_entry`.`lot_no`,
	`invoice_entry`.`garden_id`,
	'0000000' AS `sample_kgs`,
	
	@total_bags:=(IFNULL(`sale_entry`.`buyer1_bag`,0) + IFNULL(`sale_entry`.`buyer2_bag`,0) + IFNULL(`sale_entry`.`buyer3_bag`,0)) AS `total_bags`,
	
	@total_kgs:=(IFNULL(`sale_entry`.`buyer1_total_kgs`,0) + IFNULL(`sale_entry`.`buyer2_total_kgs`,0) + IFNULL(`sale_entry`.`buyer3_total_kgs`,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(`sale_entry`.`buyer1_amount`,0) + IFNULL(`sale_entry`.`buyer2_amount`,0) + IFNULL(`sale_entry`.`buyer3_amount`,0)) AS `total_amount`,
	
	(@total_bags * IFNULL(`invoice_entry`.`warehouse_charge`,0)) AS `warehouse_charge`,
	(@total_bags * IFNULL(`invoice_entry`.`insp_sample`,0)) AS `insp_sample`,
	((@total_amount * IFNULL(`invoice_entry`.`brokerage_commission`,0))/100) AS `brokerage_commission`,
	((@total_amount * IFNULL(`invoice_entry`.`tea_cess`,0))/100) AS `tea_cess`	

FROM `invoice_entry`  
JOIN `sale_entry` ON (`invoice_entry`.`id` = `sale_entry`.`invoice_id`)  

WHERE `invoice_entry`.season='".$season."' AND $ac_clause AND `invoice_entry`.`sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `invoice_entry`.`garden_id`='".$garden_id."' AND `invoice_entry`.`catalogue_status`='prepared' AND `invoice_entry`.`soft_delete`='1' GROUP BY `invoice_entry`.`id`) ");


$this->db->query("
    INSERT INTO `bill_register` (
        `type`,
        `season`,
        `old_season`,
        `sale_no`,
        `auction_center`,
        `sale_date`,
        `lot_no`,
        `garden_id`,
        `total_bags`,
        `total_kgs`,
        `total_amount`,
        `sample_kgs`,
        `warehouse_charge`,
        `insp_sample`,
        `brokerage_commission`,
        `tea_cess`
    )
    SELECT 
        'B',
        `invoice_entry`.`season`,
        `invoice_entry`.`old_season`,
        `invoice_entry`.`sale_no`,
        `invoice_entry`.`auction_center`,
        `invoice_entry`.`sale_date`,
        `invoice_entry`.`lot_no`,
        `invoice_entry`.`garden_id`,
        '0',
        '0',
		'0',

        (IFNULL(`invoice_entry`.`sample_kgs`, 0) + IFNULL(CASE WHEN NOT `invoice_entry`.`weight_short` = 'y' THEN `invoice_entry`.`additional_sample_kgs` END, 0)) AS `sample_kgs`,

		'0',
		'0',
		'0',
		'0'

    FROM `invoice_entry`

    WHERE `invoice_entry`.`season` = '".$season."' AND $ac_clause AND `invoice_entry`.`sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `invoice_entry`.`garden_id` = '".$garden_id."' AND `invoice_entry`.`catalogue_status` = 'prepared' AND `invoice_entry`.`soft_delete` = '1'
");

?>

<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$warehouse_charge = 0;
$add_wh_rent = 0;
$insp_sample = 0;
$brokerage_commission = 0;
$bank_charges = 0;
$sample_kgs = 0;
$tea_cess = 0;
$garden_misc = 0;
$garden_adj_tk = 0;
$truck_fare = 0;
$net_total_deduction = 0;
$net_total_tk = 0;

// query
$this->db->select("
	IF(
		bill_register.season = bill_register.old_season, bill_register.sale_no, CONCAT(bill_register.sale_no, ' (', bill_register.old_season, ')')
	) AS `sale_no`, 
	IF(bill_register.season = bill_register.old_season, 'Current', 'Old') AS report_link,
	sum(bill_register.total_bags) AS total_bags, 
	sum(bill_register.total_kgs) AS total_kgs, 
	sum(bill_register.sample_kgs) AS sample_kgs, 
	sum(bill_register.total_amount) AS total_amount, 
	sum(bill_register.warehouse_charge) AS warehouse_charge, 
	sum(bill_register.insp_sample) AS insp_sample, 
	sum(bill_register.brokerage_commission) AS brokerage_commission, 
	sum(bill_register.tea_cess) AS tea_cess, 
	bill_register.auction_center AS auction_center,
	bill_register.sale_date AS sale_date,
	IFNULL(gat.add_wh_rent,0) AS add_wh_rent,
	IFNULL(gat.garden_misc,0) AS garden_misc,
	IFNULL(gat.garden_adj_tk,0) AS garden_adj_tk,
	IFNULL(gat.truck_fare,0) AS truck_fare, 
	IFNULL(gat.bank_charges,0) AS bank_charges 
"); 	
$this->db->from('bill_register');

// join garden adjustment cost
$this->db->join("(SELECT old_season, sale_no, sum(CASE WHEN charges_name='Add. W/H Rent' THEN charge_tk END) AS add_wh_rent, sum(CASE WHEN charges_name='Garden Misc.' THEN charge_tk END) AS garden_misc, sum(CASE WHEN charges_name='Advance' THEN charge_tk END) AS garden_adj_tk, sum(CASE WHEN charges_name='Truck Fare' THEN charge_tk END) AS truck_fare, sum(CASE WHEN charges_name='Bank charges' THEN charge_tk END) AS bank_charges FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND $ac_clause2 AND `sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `garden_id`='".$garden_id."' GROUP BY `sale_no`, `old_season`) gat", "bill_register.sale_no = gat.sale_no AND bill_register.old_season = gat.old_season", "left");
// Filter by season type (from URL param)
$season_type = $this->input->get('season_type');
if ($season_type == 'old') {
    $this->db->where("bill_register.season <> bill_register.old_season");
} elseif ($season_type == 'current') {
    $this->db->where("bill_register.season = bill_register.old_season");
}
$this->db->group_by('bill_register.sale_no, bill_register.old_season, bill_register.auction_center');
$this->db->order_by('bill_register.sale_no ASC, bill_register.sale_date ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

    // total tk
	$total_tk = ($row3['total_amount'] - (round($row3['warehouse_charge'],2) + round($row3['add_wh_rent'],2) + round($row3['insp_sample'],2) + round($row3['brokerage_commission'],2) + round($row3['tea_cess'],2) + $row3['garden_misc'] + $row3['garden_adj_tk'] + $row3['truck_fare'] + $row3['bank_charges']));

	// total deduction tk
	$total_deduction = (round($row3['tea_cess'],2) + (round($row3['warehouse_charge'],2) + $row3['insp_sample'] + (round($row3['brokerage_commission'],2)) + $row3['bank_charges'] + $row3['garden_adj_tk'] + $row3['truck_fare' ] + $row3['garden_misc' ]+ $row3['add_wh_rent']));

	$sold_per_kgs = (!empty($row3['total_amount']) && !empty($row3['total_kgs'])) ? $row3['total_amount'] / $row3['total_kgs'] : '0.00';

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	

// garden details
$query = $this->db->query("SELECT `garden_name` FROM `garden_and_company_setup` WHERE `id`='".$garden_id."' ");
$garden_data = $query->row_array();
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="8" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;">Garden Name: <?php print $garden_data['garden_name']; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				Account Sales From Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_to; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th style="white-space:nowrap;">SALE NO</th>
		<th style="white-space:nowrap;">Auc.</th>
		<th style="white-space:nowrap;">Sale Date</th>
		<th style="white-space:nowrap;">PKGS</th>
		<th style="white-space:nowrap;">KGS</th>
		<th style="white-space:nowrap;">Sold Per KG</th>
		<th style="white-space:nowrap;">Sample Draw</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px;"><?php echo $row3['sale_no']; ?></a></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['auction_center']; ?></td>
	<td style="text-align:center; border:0px; white-space:nowrap;"><?php echo date("d-m-y", strtotime($row3['sale_date'])); ?></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:center; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:center; border:0px;"><?php echo number_format($sold_per_kgs,2,'.',','); ?></td>
	<td style="text-align:center; border:0px;"><?php echo number_format($row3['sample_kgs'],2,'.',','); ?></td>
</tr>
<?php

	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$warehouse_charge += $row3['warehouse_charge'];
	$add_wh_rent += $row3['add_wh_rent'];
	$insp_sample += $row3['insp_sample'];
	$brokerage_commission += $row3['brokerage_commission'];
	$bank_charges += $row3['bank_charges'];
	$sample_kgs += $row3['sample_kgs'];
	$tea_cess += $row3['tea_cess'];
	$garden_misc += $row3['garden_misc'];
	$garden_adj_tk += $row3['garden_adj_tk'];
	$truck_fare += $row3['truck_fare'];
	$net_total_deduction += $total_deduction;
	$net_total_tk += $total_tk;

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan="3" style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:center; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:center; color:red;"></th>
	<th style="text-align:center; color:red;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF sellers ?>

<?php if($report_name=='stea_cess_stmt'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4;
	margin-left: 100px;
}
</style>
<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 43;

$ac_clause = ($auction_center=='All Center') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

## create temporary table - tea_cess_stmt
$this->db->query("DROP TABLE IF EXISTS `tea_cess_stmt`");

// create tea_cess_stmt @  buyer-1,2,3
$this->db->query("CREATE TABLE `tea_cess_stmt` (
SELECT 

	invoice_entry.season,
	invoice_entry.old_season,
	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.garden_id,	
	gn.garden_name,
	
	@total_bags:=(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_bags,
	
	@total_kgs:=(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount,
	
	((@total_amount * IFNULL(invoice_entry.tea_cess,0))/100) AS tea_cess	

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<?php
$sl = 1;
$sale_date = '';
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$tea_cess = 0;

// query
$this->db->select("
	tea_cess_stmt.old_season, 
	tea_cess_stmt.garden_id, 
	IF(
		tea_cess_stmt.season = tea_cess_stmt.old_season, tea_cess_stmt.garden_name, CONCAT(tea_cess_stmt.garden_name, ' (', tea_cess_stmt.old_season, ')')
	) AS garden_name, 
	sum(tea_cess_stmt.total_bags) AS total_bags, 
	sum(tea_cess_stmt.total_kgs) AS total_kgs, 
	sum(tea_cess_stmt.total_amount) AS total_amount, 
	sum(tea_cess_stmt.tea_cess) AS tea_cess
"); 	
$this->db->from('tea_cess_stmt');

// join garden adjustment cost
$this->db->join("(SELECT old_season, garden_id FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND $ac_clause2 AND `sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' GROUP BY `garden_id`, `old_season`) gat", "tea_cess_stmt.garden_id = gat.garden_id AND tea_cess_stmt.old_season = gat.old_season", "left");

$this->db->group_by('tea_cess_stmt.garden_id, tea_cess_stmt.old_season');
$this->db->order_by('tea_cess_stmt.garden_name ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){
if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="6" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;"><?php print $website_title; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				Tea Cess Statement From Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_to; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th>SL</th>
		<th>GARDEN NAME</th>
		<th>PKGS</th>
		<th>KGS</th>
		<th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
		<th style="white-space:nowrap;">Tea Cess<br /> (1%)</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px;">
		<?php print $sl++ ; ?>
	</td>
	<td style="text-align:left; text-transform:uppercase; border:0px; white-space:nowrap;">
		<a target="blink" href="<?php echo site_url('MY_Controller/sellers_stmt_sale_to_sale?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row3['garden_id'].'&report_name=stea_cess_stmt_gw'); ?>"><?php echo $row3['garden_name']; ?></a>
	</td>
	<td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
</tr>
<?php

$total_bags += $row3['total_bags'];
$total_kgs += $row3['total_kgs'];
$total_amount += $row3['total_amount'];
$tea_cess += $row3['tea_cess'];

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan="2" style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF Tea Cess STMT sale to sale ?>

<?php if($report_name=='stea_cess_stmt_gw'){ ?>
<style>
#table tr td {
	border-color:black;
	font-size:10px !important;
	padding:3px;
}
#table tr th{
	border-color:black;
	font-size:10px !important;
	padding:3px;
}

#table thead tr th{
	border-top: 1px dashed #000 !important; 
	border-bottom: 1px dashed #000 !important;
	text-align:center;
	text-transform:uppercase;
	font-size:10px !important;
	padding:3px;
}

@page{
	size:A4;
	margin-left: 100px;
}
</style>

<div class="row no-print">
<div class="form-group col-sm-12 text-right">
	<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
</div>
</div>
<?php 
// get html value
$row_limit_pp = 30;

$ac_clause = ($auction_center=='All Center') ? "NOT `invoice_entry`.`auction_center`='' " : "`invoice_entry`.`auction_center`='".$auction_center."' ";

$ac_clause2 = ($auction_center=='All Center') ? "NOT `garden_adjust_tbl`.`auction_center`='' " : "`garden_adjust_tbl`.`auction_center`='".$auction_center."' ";

## create temporary table - tea_cess_stmt
$this->db->query("DROP TABLE IF EXISTS `tea_cess_stmt`");

// create tea_cess_stmt @  buyer-1,2,3
$this->db->query("CREATE TABLE `tea_cess_stmt` (
SELECT 

	`invoice_entry`.`season`,
	`invoice_entry`.`old_season`,
	`invoice_entry`.`sale_no`,
	`invoice_entry`.`auction_center`,
	`invoice_entry`.`sale_date`,
	`invoice_entry`.`lot_no`,
	`invoice_entry`.`garden_id`,
	
	@total_bags:=(IFNULL(`sale_entry`.`buyer1_bag`,0) + IFNULL(`sale_entry`.`buyer2_bag`,0) + IFNULL(`sale_entry`.`buyer3_bag`,0)) AS `total_bags`,
	
	@total_kgs:=(IFNULL(`sale_entry`.`buyer1_total_kgs`,0) + IFNULL(`sale_entry`.`buyer2_total_kgs`,0) + IFNULL(`sale_entry`.`buyer3_total_kgs`,0)) AS total_kgs,
	
	@total_amount:=(IFNULL(`sale_entry`.`buyer1_amount`,0) + IFNULL(`sale_entry`.`buyer2_amount`,0) + IFNULL(`sale_entry`.`buyer3_amount`,0)) AS `total_amount`,
	
	((@total_amount * IFNULL(`invoice_entry`.`tea_cess`,0))/100) AS `tea_cess`	

FROM `invoice_entry`  
JOIN `sale_entry` ON (`invoice_entry`.`id` = `sale_entry`.`invoice_id`)  

WHERE `invoice_entry`.season='".$season."' AND $ac_clause AND `invoice_entry`.`sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `invoice_entry`.`garden_id`='".$garden_id."' AND `invoice_entry`.`catalogue_status`='prepared' AND `invoice_entry`.`soft_delete`='1' GROUP BY `invoice_entry`.`id`) ");
?>

<?php
$sl = 1;
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$tea_cess = 0;
// query
$this->db->select("
	IF(
		tea_cess_stmt.season = tea_cess_stmt.old_season, tea_cess_stmt.sale_no, CONCAT(tea_cess_stmt.sale_no, ' (', tea_cess_stmt.old_season, ')')
	) AS `sale_no`, 
	sum(tea_cess_stmt.total_bags) AS total_bags, 
	sum(tea_cess_stmt.total_kgs) AS total_kgs, 
	sum(tea_cess_stmt.total_amount) AS total_amount, 
	sum(tea_cess_stmt.tea_cess) AS tea_cess,
	tea_cess_stmt.auction_center AS auction_center,
	tea_cess_stmt.sale_date AS sale_date,
"); 	
$this->db->from('tea_cess_stmt');

// join garden adjustment cost
$this->db->join("(SELECT old_season, sale_no FROM `garden_adjust_tbl` WHERE `season`='".$season."' AND $ac_clause2 AND `sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `garden_id`='".$garden_id."' GROUP BY `sale_no`, `old_season`) gat", "tea_cess_stmt.sale_no = gat.sale_no AND tea_cess_stmt.old_season = gat.old_season", "left");
$this->db->group_by('tea_cess_stmt.sale_no, tea_cess_stmt.old_season, tea_cess_stmt.auction_center');
$this->db->order_by('tea_cess_stmt.sale_no ASC, tea_cess_stmt.sale_date ASC');
$query = $this->db->get();
$query3 = $query->result_array();
if(count($query3) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 1;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($query3);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($query3 as $row3){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>
<!-- start top heading row -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	

// garden details
$query = $this->db->query("SELECT `garden_name` FROM `garden_and_company_setup` WHERE `id`='".$garden_id."' ");
$garden_data = $query->row_array();
?>	
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
    <tr>
        <td colspan="7" style="text-transform: uppercase; text-align:center; border:0px;">
            <b style="font-size:15px;">Garden Name: <?php print $garden_data['garden_name']; ?></b>
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-transform:uppercase; text-align:center; border:0px;">
            <b style="font-size:13px;">
				TEA CESS STATEMENT From Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_from; ?> &nbsp; To &nbsp; Season &nbsp; <?php print $season; ?>&nbsp; <?php print $auction_center; ?> &nbsp; Sale No &nbsp; <?php print $sale_to; ?>
            </b>
        </td>
        <td style="text-align:right; border:0px;"><span style="font-size:13px;">Page <?php print $page; ?></span></td>
    </tr>
    <tr>
		<th>SALE NO</th>
		<th>Auc.</th>
		<th>Sale <br /> Date</th>
		<th>PKGS</th>
		<th>KGS</th>
		<th style="white-space:nowrap;">Gross Sale <br /> Proceeds</th>
		<th>Tea Cess<br /> (1%)</th>
    </tr>
</thead>
<tbody>
<?php } //end ?>
<tr>
	<td style="text-align:center; text-transform:uppercase; border:0px;"><?php echo $row3['sale_no']; ?></a></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['auction_center']; ?></td>
	<td style="text-align:center; border:0px; white-space:nowrap;"><?php echo date("d-m-y", strtotime($row3['sale_date'])); ?></td>
	<td style="text-align:center; border:0px;"><?php echo $row3['total_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['total_amount'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['tea_cess'],2,'.',','); ?></td>
</tr>
<?php

	$total_bags += $row3['total_bags'];
	$total_kgs += $row3['total_kgs'];
	$total_amount += $row3['total_amount'];
	$tea_cess += $row3['tea_cess'];

// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>

<tr>
	<th colspan="3" style="text-align:right; color:red;">TOTAL : </th>
	<th style="text-align:center; color:red;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; color:red;"><?php echo number_format($tea_cess,2,'.',','); ?></th>
</tr>
</tbody>
</table>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
} // end foreach loop 

// end of if condition
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. </b></p></div>";
}
?>

<?php } // IF sellers ?>

</div>
<!-- /container -->

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

//row highlighted
$('#table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});
</script>