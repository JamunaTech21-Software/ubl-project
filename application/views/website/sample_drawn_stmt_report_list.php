<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php 
    // Get company name function
    $get_company_name = function($company_id) {
        if(!empty($company_id)){
            $query = $this->db->query("SELECT company_full_name FROM company_setup WHERE id='".$company_id."'");
            $company_row = $query->row_array();
            if(!empty($company_row['company_full_name'])){
                return $company_row['company_full_name'];
            }
        }
        return '';
    };
    
    // Get company name first
    $company_name = $get_company_name($company_id ?? '');
    $title_parts = array();
    
    // Add company name first if exists
    if(!empty($company_name)){
        $title_parts[] = $company_name;
    }
    
    // Build sale information part
    $sale_info = '';
    
    // For Report 1 (Sale Range)
    if(isset($report_no) && $report_no == '1' && !empty($sale_from) && !empty($sale_to)){
        $sale_info = "Sale No " . $sale_from . "-" . $sale_to;
    }
    // For Report 2 (Single Sale - Specific Garden)
    elseif(isset($report_no) && $report_no == '2' && !empty($sale_from)){
        $sale_info = "Sale No " . $sale_from;
    }
    // For Report 3
    elseif(isset($report_no2) && $report_no2 == '3' && !empty($sale_from2)){
        $sale_info = "Sale No " . $sale_from2;
    }
    
    // Add sale info if exists
    if(!empty($sale_info)){
        $title_parts[] = $sale_info;
    }
    
    // Add "SAMPLE DRAWN STATEMENT" at the end (note: corrected spelling from DRWAN to DRAWN)
    $title_parts[] = "SAMPLE DRAWN STATEMENT";
    
    // Join all parts with " - " separator
    $final_title = implode(" - ", $title_parts);
    
    echo $final_title; 
    ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
    <link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<div id="printableArea" class="container">

<div class="row no-print">
    <div class="form-group col-sm-12 text-right">
        <a class="btn-default" href="javascript:void(0);" onclick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
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
}
#table tr th{
    border-color:black;
    font-size:12px !important;
    padding:3px;
}
#table tbody tr.highlighted td { background-color: cyan; } 

@media print{    
    thead { display: table-header-group; }
    .no-print, .no-print *{ display: none !important; }
    a[href]:after { content: none !important; }
    .pagebreak { page-break-before: always; }
    
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
    size: A4;
}
</style>

<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$report_no = (!empty($this->input->get('report_no', TRUE))) ? $this->input->get('report_no', TRUE) : '';

$season2 = (!empty($this->input->get('season2', TRUE))) ? $this->input->get('season2', TRUE) : '0';
$auction_center2 = (!empty($this->input->get('auction_center2', TRUE))) ? $this->input->get('auction_center2', TRUE) : '';
$sale_from2 = (!empty($this->input->get('sale_from2', TRUE))) ? $this->input->get('sale_from2', TRUE) : '';
$report_no2 = (!empty($this->input->get('report_no2', TRUE))) ? $this->input->get('report_no2', TRUE) : '';
$company_id = (!empty($this->input->get('company_id', TRUE))) ? $this->input->get('company_id', TRUE) : '';

// Determine which button was clicked
$submit2 = (!empty($this->input->get('Submit2', TRUE))) ? $this->input->get('Submit2', TRUE) : '';
$submit3 = (!empty($this->input->get('Submit3', TRUE))) ? $this->input->get('Submit3', TRUE) : '';
?>


<?php if($report_no=='1'){ ?>
<!-- SALE RANGE REPORT -->
<div class="row">
    <div class="form-group col-sm-12 text-center">
        <b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
        <br /> 
        <span style="text-transform: uppercase; font-size:12px; text-align:center;"><?php print $owner_short_address; ?></span>
        <br />
        <b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
            SAMPLE DRAWN STATEMENT
        </b>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4 text-left">
        <b style="text-transform: uppercase; font-size:16px !important; font-family:courier;">Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?></b>
    </div>
    <div class="form-group col-sm-4 text-center">
        <b style="text-transform: uppercase; font-size:16px !important; font-family:courier;"><?php print $auction_center; ?></b>
    </div>
    <div class="form-group col-sm-4 text-right">
        <b style="text-transform: uppercase; font-size:16px !important; font-family:courier;">Season: <?php print $season; ?></b>
    </div>
</div>

<div class="row">
<div class="col-sm-12">

<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
    <th style="text-align:center; border:1px solid;">Garden Name</th>
    <th colspan="2" style="text-align:left; border:1px solid;">Invoice Description</th>
    <th style="text-align:center; border:1px solid;">Inv.kgs</th>
    <th style="text-align:center; border:1px solid;">Sample</th>
    <th style="text-align:center; border:1px solid;">Shortage</th>
    <th style="text-align:center; border:1px solid;">Excess</th>
    <th style="text-align:right; border:1px solid;">Net Kgs</th>
    <th style="text-align:right; border:1px solid;">Chest</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;
$total_kgs2 = 0;
$sample_kgs2 = 0;
$shortage_kgs2 = 0;
$net_kgs2 = 0;
$chest_number2 = 0;

// garden wise breakdown
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name");
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result = $query->result_array();
if(count($result) > 0){
foreach($result as $row){

// invoice wise breakdown
$previous_garden='';
$total_kgs = 0;
$sample_kgs = 0;
$shortage_kgs = 0;
$net_kgs = 0;
$chest_number = 0;

// query
$this->db->select("
    
    invoice_entry.category, 
    invoice_entry.chest_name,
    IF (invoice_entry.w_inv_no REGEXP ('^[0-9]+$'), '', GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) ORDER BY SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) SEPARATOR ', ')) AS invoice_ext, 
    GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(w_inv_no, '-', -1) ORDER BY SUBSTRING_INDEX(w_inv_no, '-', -1) SEPARATOR ', ') AS invoice, 
    IFNULL(SUM(total_kgs),0) AS total_kgs, 
    (IFNULL(SUM(sample_kgs),0) + IFNULL(SUM(CASE WHEN NOT weight_short='y' THEN additional_sample_kgs END),0)) AS sample_kgs, 
    IF(invoice_entry.weight_short='y', IFNULL(SUM(invoice_entry.additional_sample_kgs),0), 0) AS shortage_kgs,
    IFNULL(SUM(net_kgs),0) AS net_kgs, 
    IFNULL(SUM(chest_number),0) AS chest_number
");
$this->db->from('invoice_entry');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where("invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'");
$this->db->where('invoice_entry.garden_id', $row['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.group_no');
$this->db->order_by('invoice_entry.group_no ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
    <td style="text-align:center; border:0px;"><?php if($previous_garden != $row['garden_id']){ echo $sl++; } ?></td>
    <td style="text-align:left; text-transform:uppercase; border:0px;">
        <?php if($previous_garden != $row['garden_id']){ ?>
        <a target="blink" href="<?php echo site_url('MY_Controller/sample_drawn_stmt_report_list?season='.$season.'&auction_center='.$auction_center.'&sale_from='.$sale_from.'&sale_to='.$sale_to.'&garden_id='.$row['garden_id'].'&report_no=2'); ?>">
            <?php echo mb_strimwidth($row['garden_name'], 0, 20, "."); ?>
        </a>
        <?php } ?>
    </td>
    <td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row1['invoice_ext']; ?></td>
    <td style="text-align:left; text-transform:uppercase; border:0px;">
        <?php echo $row1['invoice']; ?> <?php print $row1['category']; ?> <?php print substr($row1['chest_name'],3); ?>
    </td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['total_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['sample_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['shortage_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;">0.00</td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['net_kgs'],2,'.',''); ?></td>
    <td style="text-align:right; border:0px;"><?php echo $row1['chest_number']; ?></td>
</tr>
<?php 
    $total_kgs += $row1['total_kgs'];
    $sample_kgs += $row1['sample_kgs'];
    $shortage_kgs += $row1['shortage_kgs'];
    $net_kgs += $row1['net_kgs'];
    $chest_number += $row1['chest_number'];
    $previous_garden = $row['garden_id'];
} // row1
?>
<tr>
    <th style="border:0px; border-bottom:1px solid;"></th>
    <th style="border:0px; border-bottom:1px solid;"></th>
    <th style="border:0px; border-bottom:1px solid;"></th>
    <th style="border:0px; border-bottom:1px solid; text-align:right; text-transform:uppercase;">GARDEN TOTAL : </th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($shortage_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;">0.00</th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($net_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo $chest_number; ?></th>
</tr>
<?php
    $total_kgs2 += $total_kgs;
    $sample_kgs2 += $sample_kgs;
    $shortage_kgs2 += $shortage_kgs;
    $net_kgs2 += $net_kgs;
    $chest_number2 += $chest_number;
} // row
?>
<tr>
    <th style="text-align:right; border:0px; border-top:1px solid;"></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"></th>
    <th style="text-align:right; border:0px; border-top:1px solid;">NET TOTAL : </th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($total_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($sample_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($shortage_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;">0.00</th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($net_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo $chest_number2; ?></th>
</tr>
<?php
}else{
    print "<tr><td colspan=\"10\">No matching record found.</td></tr>";
}
?>
</tbody>
</table>
</div><!-- /div responsive -->

</div><!-- /col -->
</div><!-- /row -->
<?php } // end of report 1 ?>


<?php 
if($report_no=='2'){
// SINGLE SALE - SPECIFIC GARDEN
// page no
$page_no=1;

// garden breakdown
$this->db->select("invoice_entry.w_house_id, warehouse_setup.warehouse_name, warehouse_setup.warehouse_address2");
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id', 'left');

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_from);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.soft_delete', 1);

$this->db->group_by('invoice_entry.w_house_id');
$this->db->order_by('invoice_entry.w_house_id ASC');
$query = $this->db->get();
$result = $query->result_array();
if(count($result) > 0){
foreach($result as $row){
?>  
<div class="row pagebreak">
<div class="col-sm-12">

<table class="table table-sm" id="table">
    <thead>
        <tr>
            <th colspan="2" style="text-transform:uppercase; text-align:left; border:0px;">
                <b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?> </b>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; border:0px;"></th>
        </tr>

<?php
// garden name
$query = $this->db->query("SELECT * FROM `garden_and_company_setup` WHERE `id`='".$garden_id."' ");
$result_garden = $query->row_array();
?>      
        
        <tr>
            <th colspan="2" style="text-align:left; border:0px; font-size:13px !important;">
                To Manager: <span style="text-transform:uppercase;"><?php print $result_garden['garden_name']; ?></span>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; border:0px;"></th>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">
                Re: Sample drawn & shortage statement Sale No. &nbsp;&nbsp;&nbsp; <?php print $sale_from; ?> &nbsp;&nbsp;&nbsp; Season &nbsp;<?php print $season; ?>
            </td>
            <td style="text-align:right; border:0px; font-size:13px !important;">Page <?php print $page_no++; ?></td>
        </tr>
    </thead>
</table>

<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th colspan="2" style="text-align:center; border:1px solid;">Invoice Description</th>
    <th style="text-align:center; border:1px solid;">Shed</th>
    <th style="text-align:center; border:1px solid;">Invoice Kg.</th>
    <th style="text-align:center; border:1px solid;">Sample Kg.</th>
    <th style="text-align:center; border:1px solid;">Shortage</th>
    <th style="text-align:center; border:1px solid;">Excess</th>
    <th style="text-align:center; border:1px solid;">Total Smp.</th>
    <th style="text-align:center; border:1px solid;">Net Kg.</th>
    <th style="text-align:center; border:1px solid;">Chest</th>
</tr>
</thead>
<tbody>
<?php
$total_kgs = 0;
$sample_kgs = 0;
$shortage_kgs = 0;
$net_sample = 0;
$net_kgs = 0;
$chest_number = 0;

// query
$this->db->select("

    IF (invoice_entry.w_inv_no REGEXP ('^[0-9]+$'), '', GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) ORDER BY SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) SEPARATOR ', ')) AS invoice_ext, 
    GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', -1) ORDER BY SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', -1) SEPARATOR ', ') AS invoice, 
    warehouse_setup.short_key_word,
    IFNULL(SUM(invoice_entry.total_kgs),0) AS total_kgs,
    (IFNULL(SUM(invoice_entry.sample_kgs),0) + IFNULL(SUM(CASE WHEN NOT invoice_entry.weight_short='y' THEN invoice_entry.additional_sample_kgs END),0)) AS sample_kgs,
    IF(invoice_entry.weight_short='y', IFNULL(SUM(invoice_entry.additional_sample_kgs),0), 0) AS shortage_kgs, 
    IFNULL(SUM(invoice_entry.net_kgs),0) AS net_kgs, 
    IFNULL(SUM(invoice_entry.chest_number),0) AS chest_number 
");
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_from);
$this->db->where('invoice_entry.garden_id', $garden_id);
$this->db->where('invoice_entry.w_house_id', $row['w_house_id']);
$this->db->where('invoice_entry.soft_delete', 1);

$this->db->group_by('invoice_entry.group_no');
$this->db->order_by('invoice_entry.group_no ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
    
    // net sample
    $total_sample = $row1['sample_kgs'] + $row1['shortage_kgs'];
    
?>
<tr>
    <td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row1['invoice_ext']; ?></td>
    <td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row1['invoice']; ?></td>
    <td style="text-align:center; border:0px;"><?php echo $row1['short_key_word']; ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['total_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['sample_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['shortage_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;">0.00</td>
    <td style="text-align:right; border:0px;"><?php echo number_format($total_sample,2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['net_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo $row1['chest_number']; ?></td>
</tr>
<?php 
    $total_kgs += $row1['total_kgs'];
    $sample_kgs += $row1['sample_kgs'];
    $shortage_kgs += $row1['shortage_kgs'];
    $net_sample += $total_sample;
    $net_kgs += $row1['net_kgs'];
    $chest_number += $row1['chest_number'];
} // row1
?>
<tr>
    <th colspan="3" style="text-align:right; border:1px solid;">TOTAL : </th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($shortage_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;">0.00</th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($net_sample,2,'.',''); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($net_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo $chest_number; ?></th>
</tr>
</tbody>
</table>
</div><!-- /div responsive -->

<table class="table table-sm" id="table">
    <tbody>
        <tr>
            <td style="text-transform:uppercase; text-align:left; border:0px; font-size:13px !important;"><?php print $website_title; ?></td>
        </tr>
        <tr>
            <th style="text-align:left; border:0px; font-size:13px !important;"></th>
        </tr>   
        <tr>
            <th style="text-align:left; border:0px; font-size:13px !important;">CC. &nbsp;&nbsp; Managing Director</th>
        </tr>

<?php
// garden name
$query = $this->db->query("SELECT * FROM `company_setup` WHERE `id`='".$result_garden['company_name']."' ");
$result_company = $query->row_array();
?>  
        
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php print $result_company['company_full_name']; ?></td>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php print $result_company['company_address2']; ?></td>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Supdt. Custom Excise & VAT, <?php print $custom_address; ?></td>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php print $row['warehouse_name']; ?>, <?php print $row['warehouse_address2']; ?></td>
        </tr>
    </tbody>
</table>
<?php } // warehouse foreach ?>
<?php } // warehouse if count ?>

</div><!-- /col -->
</div><!-- /row -->
<?php } // end of report 2 ?>

<?php if($report_no2=='3'){ 
// Check if Submit2 or Submit3 was clicked
if(!empty($submit3) || (!empty($garden_id) && $garden_id == 'all')){
    // SEARCH DATA ALL - Show all gardens (with company filter if selected)
    // page no
    $page_no=1;

    // Get all gardens for this sale
    $this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name, garden_and_company_setup.company_name");
    $this->db->from('invoice_entry');
    $this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id', 'left');
    $this->db->where('invoice_entry.catalogue_status', 'prepared');
    $this->db->where('invoice_entry.season', $season2);
    $this->db->where('invoice_entry.auction_center', $auction_center2);
    $this->db->where('invoice_entry.sale_no', $sale_from2);
    $this->db->where('invoice_entry.soft_delete', 1);
    
    // Apply company filter if selected
    if(!empty($company_id)){
        $this->db->where('garden_and_company_setup.company_name', $company_id);
    }
    
    $this->db->group_by('invoice_entry.garden_id');
    $this->db->order_by('garden_and_company_setup.garden_name ASC');
    $query = $this->db->get();
    $gardens = $query->result_array();

    if(count($gardens) > 0){
        foreach($gardens as $garden_row){
            
            // Get warehouses for this garden
            $this->db->select("invoice_entry.w_house_id, warehouse_setup.warehouse_name, warehouse_setup.warehouse_address2");
            $this->db->from('invoice_entry');
            $this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id', 'left');
            $this->db->where('invoice_entry.catalogue_status', 'prepared');
            $this->db->where('invoice_entry.season', $season2);
            $this->db->where('invoice_entry.auction_center', $auction_center2);
            $this->db->where('invoice_entry.sale_no', $sale_from2);
            $this->db->where('invoice_entry.garden_id', $garden_row['garden_id']);
            $this->db->where('invoice_entry.soft_delete', 1);
            $this->db->group_by('invoice_entry.w_house_id');
            $this->db->order_by('invoice_entry.w_house_id ASC');
            $query = $this->db->get();
            $warehouses = $query->result_array();
            
            if(count($warehouses) > 0){
                foreach($warehouses as $row){
?>
<div class="row pagebreak">
<div class="col-sm-12">

<table class="table table-sm" id="table">
    <thead>
        <tr>
            <th colspan="2" style="text-transform:uppercase; text-align:left; border:0px;">
                <b style="text-transform: uppercase; font-size:16px; font-weight:bold;"><?php print $website_title; ?> </b>
                <br/>
                <span style="text-transform: uppercase; font-size:13px; font-weight:bold;">
                    SAMPLE DRWAN STATEMENT Sale No: <?php print $sale_from2; ?> | Season: <?php print $season2; ?>
                </span>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; border:0px;"></th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; border:0px; font-size:13px !important;">
                To Manager: <span style="text-transform:uppercase;"><?php print $garden_row['garden_name']; ?></span>
            </th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; border:0px;"></th>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">
                Re: Sample drawn & shortage statement Sale No. &nbsp;&nbsp;&nbsp; <?php print $sale_from2; ?> &nbsp;&nbsp;&nbsp; Season &nbsp;<?php print $season2; ?>
            </td>
            <td style="text-align:right; border:0px; font-size:13px !important;">Page <?php print $page_no++; ?></td>
        </tr>
    </thead>
</table>

<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th colspan="2" style="text-align:center; border:1px solid;">Invoice Description</th>
    <th style="text-align:center; border:1px solid;">Shed</th>
    <th style="text-align:center; border:1px solid;">Invoice Kg.</th>
    <th style="text-align:center; border:1px solid;">Sample Kg.</th>
    <th style="text-align:center; border:1px solid;">Shortage</th>
    <th style="text-align:center; border:1px solid;">Excess</th>
    <th style="text-align:center; border:1px solid;">Total Smp.</th>
    <th style="text-align:center; border:1px solid;">Net Kg.</th>
    <th style="text-align:center; border:1px solid;">Chest</th>
</tr>
</thead>
<tbody>
<?php
$total_kgs = 0;
$sample_kgs = 0;
$shortage_kgs = 0;
$net_sample = 0;
$net_kgs = 0;
$chest_number = 0;

// query for invoice data
$this->db->select("
    IF (invoice_entry.w_inv_no REGEXP ('^[0-9]+$'), '', GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) ORDER BY SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) SEPARATOR ', ')) AS invoice_ext, 
    GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', -1) ORDER BY SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', -1) SEPARATOR ', ') AS invoice, 
    warehouse_setup.short_key_word,
    IFNULL(SUM(invoice_entry.total_kgs),0) AS total_kgs,
    (IFNULL(SUM(invoice_entry.sample_kgs),0) + IFNULL(SUM(CASE WHEN NOT invoice_entry.weight_short='y' THEN invoice_entry.additional_sample_kgs END),0)) AS sample_kgs,
    IF(invoice_entry.weight_short='y', IFNULL(SUM(invoice_entry.additional_sample_kgs),0), 0) AS shortage_kgs, 
    IFNULL(SUM(invoice_entry.net_kgs),0) AS net_kgs, 
    IFNULL(SUM(invoice_entry.chest_number),0) AS chest_number 
");
$this->db->from('invoice_entry');
$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season2);
$this->db->where('invoice_entry.auction_center', $auction_center2);
$this->db->where('invoice_entry.sale_no', $sale_from2);
$this->db->where('invoice_entry.garden_id', $garden_row['garden_id']);
$this->db->where('invoice_entry.w_house_id', $row['w_house_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.group_no');
$this->db->order_by('invoice_entry.group_no ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
    
    // net sample
    $total_sample = $row1['sample_kgs'] + $row1['shortage_kgs'];
    
?>
<tr>
    <td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row1['invoice_ext']; ?></td>
    <td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row1['invoice']; ?></td>
    <td style="text-align:center; border:0px;"><?php echo $row1['short_key_word']; ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['total_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['sample_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['shortage_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;">0.00</td>
    <td style="text-align:right; border:0px;"><?php echo number_format($total_sample,2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['net_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo $row1['chest_number']; ?></td>
</tr>
<?php 
    $total_kgs += $row1['total_kgs'];
    $sample_kgs += $row1['sample_kgs'];
    $shortage_kgs += $row1['shortage_kgs'];
    $net_sample += $total_sample;
    $net_kgs += $row1['net_kgs'];
    $chest_number += $row1['chest_number'];
} // row1
?>
<tr>
    <th colspan="3" style="text-align:right; border:1px solid;">TOTAL : </th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($shortage_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;">0.00</th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($net_sample,2,'.',''); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo number_format($net_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:1px solid;"><?php echo $chest_number; ?></th>
</tr>
</tbody>
</table>
</div><!-- /div responsive -->

<table class="table table-sm" id="table">
    <tbody>
        <tr>
            <td style="text-transform:uppercase; text-align:left; border:0px; font-size:13px !important;"><?php print $website_title; ?></td>
        </tr>
        <tr>
            <th style="text-align:left; border:0px; font-size:13px !important;"></th>
        </tr>   
        <tr>
            <th style="text-align:left; border:0px; font-size:13px !important;">CC. &nbsp;&nbsp; Managing Director</th>
        </tr>

<?php
// garden company details
$query = $this->db->query("SELECT garden_and_company_setup.company_name, company_setup.company_full_name, company_setup.company_address2 
                           FROM garden_and_company_setup 
                           LEFT JOIN company_setup ON garden_and_company_setup.company_name = company_setup.id 
                           WHERE garden_and_company_setup.id='".$garden_row['garden_id']."' ");
$result_company = $query->row_array();
?>      
        
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php print $result_company['company_full_name']; ?></td>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php print $result_company['company_address2']; ?></td>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Supdt. Custom Excise & VAT, <?php print $custom_address; ?></td>
        </tr>
        <tr>
            <td style="text-align:left; border:0px; font-size:13px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php print $row['warehouse_name']; ?>, <?php print $row['warehouse_address2']; ?></td>
        </tr>
    </tbody>
</table>
<?php 
                } // warehouse foreach
            } // warehouse if count
        } // garden foreach
    } else {
        print "<div class='row'><div class='col-sm-12'><p>No gardens found for this sale.</p></div></div>";
    }
} else {
    // SEARCH DATA (Single Sale Summary - Report 3 style but for one sale)
    // This shows the summary for a single sale (company filter does NOT apply here)
?>
<div class="row">
    <div class="form-group col-sm-12 text-center">
        <b style="text-transform: uppercase; font-size:16px; text-align:center; font-weight:bold;"><?php print $website_title; ?> </b> 
        <br /> 
        <span style="text-transform: uppercase; font-size:12px; text-align:center;"><?php print $owner_short_address; ?></span>
        <br />
        <b style="text-transform: uppercase; font-size:16px; font-family:courier; font-weight:bold; text-align:center;">
            SAMPLE DRWAN STATEMENT Sale No: <?php print $sale_from2; ?> | Season: <?php print $season2; ?>
        </b>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-4 text-left">
        <b style="text-transform: uppercase; font-size:16px !important; font-family:courier;">Sale No: <?php print $sale_from2; ?></b>
    </div>
    <div class="form-group col-sm-4 text-center">
        <b style="text-transform: uppercase; font-size:16px !important; font-family:courier;"><?php print $auction_center2; ?></b>
    </div>
    <div class="form-group col-sm-4 text-right">
        <b style="text-transform: uppercase; font-size:16px !important; font-family:courier;">Season: <?php print $season2; ?></b>
    </div>
</div>

<div class="row">
<div class="col-sm-12">

<div class="table-responsive">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
    <th style="text-align:center; border:1px solid;">Garden Name</th>
    <th colspan="2" style="text-align:left; border:1px solid;">Invoice Description</th>
    <th style="text-align:center; border:1px solid;">Inv.kgs</th>
    <th style="text-align:center; border:1px solid;">Sample</th>
    <th style="text-align:center; border:1px solid;">Shortage</th>
    <th style="text-align:center; border:1px solid;">Excess</th>
    <th style="text-align:right; border:1px solid;">Net Kgs</th>
    <th style="text-align:right; border:1px solid;">Chest</th>
</tr>
</thead>
<tbody>
<?php
$sl=1;
$total_kgs2 = 0;
$sample_kgs2 = 0;
$shortage_kgs2 = 0;
$net_kgs2 = 0;
$chest_number2 = 0;

// garden wise breakdown
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name");
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id', 'left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season2);
$this->db->where('invoice_entry.auction_center', $auction_center2);
$this->db->where('invoice_entry.sale_no', $sale_from2);
$this->db->where('invoice_entry.soft_delete', 1);
// NO company filter here - it only applies to "Search Data All"
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result = $query->result_array();
if(count($result) > 0){
foreach($result as $row){

// invoice wise breakdown
$previous_garden='';
$total_kgs = 0;
$sample_kgs = 0;
$shortage_kgs = 0;
$net_kgs = 0;
$chest_number = 0;

// query
$this->db->select("
    
    invoice_entry.category, 
    invoice_entry.chest_name,
    IF (invoice_entry.w_inv_no REGEXP ('^[0-9]+$'), '', GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) ORDER BY SUBSTRING_INDEX(invoice_entry.w_inv_no, '-', 1) SEPARATOR ', ')) AS invoice_ext, 
    GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(w_inv_no, '-', -1) ORDER BY SUBSTRING_INDEX(w_inv_no, '-', -1) SEPARATOR ', ') AS invoice, 
    IFNULL(SUM(total_kgs),0) AS total_kgs, 
    (IFNULL(SUM(sample_kgs),0) + IFNULL(SUM(CASE WHEN NOT weight_short='y' THEN additional_sample_kgs END),0)) AS sample_kgs, 
    IF(invoice_entry.weight_short='y', IFNULL(SUM(invoice_entry.additional_sample_kgs),0), 0) AS shortage_kgs,
    IFNULL(SUM(net_kgs),0) AS net_kgs, 
    IFNULL(SUM(chest_number),0) AS chest_number
");
$this->db->from('invoice_entry');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season2);
$this->db->where('invoice_entry.auction_center', $auction_center2);
$this->db->where('invoice_entry.sale_no', $sale_from2);
$this->db->where('invoice_entry.garden_id', $row['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.group_no');
$this->db->order_by('invoice_entry.group_no ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
?>
<tr>
    <td style="text-align:center; border:0px;"><?php if($previous_garden != $row['garden_id']){ echo $sl++; } ?></td>
    <td style="text-align:left; text-transform:uppercase; border:0px;">
        <?php if($previous_garden != $row['garden_id']){ ?>
        <a target="blink" href="<?php echo site_url('MY_Controller/sample_drawn_stmt_report_list?season='.$season2.'&auction_center='.$auction_center2.'&sale_from='.$sale_from2.'&garden_id='.$row['garden_id'].'&report_no=2'); ?>">
            <?php echo mb_strimwidth($row['garden_name'], 0, 20, "."); ?>
        </a>
        <?php } ?>
    </td>
    <td style="text-align:left; text-transform:uppercase; border:0px;"><?php echo $row1['invoice_ext']; ?></td>
    <td style="text-align:left; text-transform:uppercase; border:0px;">
        <?php echo $row1['invoice']; ?> <?php print $row1['category']; ?> <?php print substr($row1['chest_name'],3); ?>
    </td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['total_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['sample_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['shortage_kgs'],2,'.',','); ?></td>
    <td style="text-align:right; border:0px;">0.00</td>
    <td style="text-align:right; border:0px;"><?php echo number_format($row1['net_kgs'],2,'.',''); ?></td>
    <td style="text-align:right; border:0px;"><?php echo $row1['chest_number']; ?></td>
</tr>
<?php 
    $total_kgs += $row1['total_kgs'];
    $sample_kgs += $row1['sample_kgs'];
    $shortage_kgs += $row1['shortage_kgs'];
    $net_kgs += $row1['net_kgs'];
    $chest_number += $row1['chest_number'];
    $previous_garden = $row['garden_id'];
} // row1
?>
<tr>
    <th style="border:0px; border-bottom:1px solid;"></th>
    <th style="border:0px; border-bottom:1px solid;"></th>
    <th style="border:0px; border-bottom:1px solid;"></th>
    <th style="border:0px; border-bottom:1px solid; text-align:right; text-transform:uppercase;">GARDEN TOTAL : </th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($total_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($sample_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($shortage_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;">0.00</th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo number_format($net_kgs,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-bottom:1px solid;"><?php echo $chest_number; ?></th>
</tr>
<?php
    $total_kgs2 += $total_kgs;
    $sample_kgs2 += $sample_kgs;
    $shortage_kgs2 += $shortage_kgs;
    $net_kgs2 += $net_kgs;
    $chest_number2 += $chest_number;
} // row
?>
<tr>
    <th style="text-align:right; border:0px; border-top:1px solid;"></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"></th>
    <th style="text-align:right; border:0px; border-top:1px solid;">NET TOTAL : </th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($total_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($sample_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($shortage_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;">0.00</th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo number_format($net_kgs2,2,'.',','); ?></th>
    <th style="text-align:right; border:0px; border-top:1px solid;"><?php echo $chest_number2; ?></th>
</tr>
<?php
}else{
    print "<tr><td colspan=\"10\">No matching record found.</td></tr>";
}
?>
</tbody>
</table>
</div><!-- /div responsive -->

</div><!-- /col -->
</div><!-- /row -->
<?php 
} // end of else (Submit2 - single sale summary)
} // end of report 3 ?>
</div> 
<!-- /container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
function printDiv(divName){
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    var originalTitle = document.title;
    
    // Set the new title for PDF
    <?php 
    // Get company name function for JS title
    $get_company_name_js = function($company_id) {
        if(!empty($company_id)){
            $query = $this->db->query("SELECT company_full_name FROM company_setup WHERE id='".$company_id."'");
            $company_row = $query->row_array();
            if(!empty($company_row['company_full_name'])){
                return $company_row['company_full_name'];
            }
        }
        return '';
    };
    
    $company_name_js = $get_company_name_js($company_id ?? '');
    $title_parts_js = array();
    
    // Add company name first
    if(!empty($company_name_js)){
        $title_parts_js[] = $company_name_js;
    }
    
    // Build sale info
    $sale_info_js = '';
    if(isset($report_no) && $report_no == '1' && !empty($sale_from) && !empty($sale_to)){
        $sale_info_js = "Sale No " . $sale_from . "-" . $sale_to;
    }
    elseif(isset($report_no) && $report_no == '2' && !empty($sale_from)){
        $sale_info_js = "Sale No " . $sale_from;
    }
    elseif(isset($report_no2) && $report_no2 == '3' && !empty($sale_from2)){
        $sale_info_js = "Sale No " . $sale_from2;
    }
    
    if(!empty($sale_info_js)){
        $title_parts_js[] = $sale_info_js;
    }
    
    $title_parts_js[] = "SAMPLE DRAWN STATEMENT";
    $final_title_js = implode(" - ", $title_parts_js);
    ?>
    document.title = "<?php echo $final_title_js; ?>";
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    document.title = originalTitle;
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

</body>
</html>