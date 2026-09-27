<!DOCTYPE html>
<html lang="en">
<head>
<title>BUYER AIT STMT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>

<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';
$buyer_id2 = (!empty($this->input->get('buyer_id2', TRUE))) ? $this->input->get('buyer_id2', TRUE) : '';
$report_no = (!empty($this->input->get('report_no', TRUE))) ? $this->input->get('report_no', TRUE) : '';

## buyer details
$query = $this->db->query("SELECT buyer_name, GROUP_CONCAT(id) AS buyer_id FROM `buyer_setup` WHERE `id`='".$buyer_id."' OR `main_buyer_id`='".$buyer_id."' ");
$buyer_data = $query->row_array();
?>


<?php if($report_no=='1'){ 

// Get parameters for Report 1
$season_from = (!empty($this->input->get('season_from', TRUE))) ? $this->input->get('season_from', TRUE) : '';
$season_to = (!empty($this->input->get('season_to', TRUE))) ? $this->input->get('season_to', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';

// Get buyer details
$query = $this->db->query("SELECT buyer_name, GROUP_CONCAT(id) AS buyer_id FROM `buyer_setup` WHERE `id`='".$buyer_id."' OR `main_buyer_id`='".$buyer_id."' ");
$buyer_data = $query->row_array();

// Build season clause based on whether seasons are same or different
if ($season_from == $season_to) {
    $season_clause = "invoice_entry.season = '".$season_from."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."'";
} else {
    $season_clause = "(invoice_entry.season = '".$season_from."' AND invoice_entry.sale_no >= '".$sale_from."')
                      OR (invoice_entry.season = '".$season_to."' AND invoice_entry.sale_no <= '".$sale_to."')
                      OR (invoice_entry.season > '".$season_from."' AND invoice_entry.season < '".$season_to."')";
}
?>

<div class="container" id="printableArea">
    <div class="row no-print">
        <div class="form-group col-sm-12 text-right" style="margin-bottom: 0px !important;">
            <a href="#" onClick="printDiv('printableArea')"><span class="badge" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
        </div>
    </div>
    
<style>
    body {
        font-family: Verdana, Geneva, sans-serif !important;
        background-color: white !important;
        margin-top: 0px !important;
        color: #000;
    }

    .table {
        font-family: Verdana, Geneva, sans-serif;
        width: 100% !important;
        border-collapse: collapse;
        margin-top: 15px;
    }

    @media print {
        /* Scale the page to 88% */
        @page {
            size: A4 portrait;
            margin: 10mm 8mm 10mm 8mm;
            transform: scale(0.88);
            transform-origin: top left;
        }
        
        thead {
            display: table-header-group;
        }
        .no-print,
        .no-print * {
            display: none !important;
        }
        /* Prevent page breaks inside rows */
        #table tbody tr {
            page-break-inside: avoid;
        }
        /* Ensure table doesn't break awkwardly */
        #table {
            page-break-inside: auto;
        }
        body {
            margin: 0;
            padding: 0;
            zoom: 88%;
        }
        .container {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        /* Scale table for print */
        #table {
            font-size: 9.68px !important; /* 11px * 0.88 */
        }
        #table th {
            font-size: 9.68px !important;
            padding: 5px 3px !important;
        }
        #table tbody tr td {
            font-size: 9.68px !important;
            padding: 2px 2px !important;
        }
        #table tbody tr.total-row th, 
        #table tbody tr.total-row td {
            font-size: 9.68px !important;
            padding: 5px 3px !important;
        }
        .header-meta {
            font-size: 10.56px !important; /* 12px * 0.88 */
        }
        .col-sm-12 b {
            font-size: 14.08px !important; /* 16px * 0.88 */
        }
        .col-sm-12 b[style*="font-size: 13px"] {
            font-size: 11.44px !important; /* 13px * 0.88 */
        }
    }
    
    /* Default page settings for screen */
    @page {
        size: A4 portrait;
        margin: 10mm 8mm 10mm 8mm;
    }

    #table {
        border: none !important;
        page-break-inside: auto;
        width: 100% !important;
    }

    #table thead {
        display: table-header-group;
    }

    #table tbody tr {
        page-break-inside: avoid;
    }

    #table tbody tr.total-row {
        page-break-inside: avoid;
        page-break-after: avoid;
    }

    /* Header borders only */
    #table th {
        border: 1px solid #000 !important;
        text-align: center;
        text-transform: uppercase;
        font-size: 11px !important;
        font-weight: normal;
        padding: 6px 4px;
        background-color: #f9f9f9;
    }

    /* Data cells - NO borders */
    #table tbody tr td {
        border: none !important;
        font-size: 11px !important;
        padding: 2px 3px;
    }

    /* Total row - NO borders on cells, only top/bottom borders for the row */
    #table tbody tr.total-row th, 
    #table tbody tr.total-row td {
        border-top: 1px solid #000 !important;
        border-bottom: 1px solid #000 !important;
        border-left: none !important;
        border-right: none !important;
        font-size: 11px !important;
        padding: 6px 4px;
        
    }

    .header-meta {
        font-size: 12px;
    }
    
    /* Ensure container doesn't cause issues in print */
    .container {
        width: 100% !important;
        padding: 0 15px !important;
        margin: 0 auto !important;
    }
    
    .row {
        margin: 0 !important;
    }
    
    .col-sm-12 {
        padding: 0 !important;
    }
</style>

<div class="row">
    <div class="col-sm-12" style="text-align: left;">
        <b style="font-size: 16px; text-transform: uppercase;"><?php print $website_title; ?></b><br />
        <span style="font-size: 13px; text-transform: uppercase;">BUYERS PURCHASE STATEMENT</span>&nbsp;&nbsp; From Season : <?php print $season_from; ?> Sale No. <?php print $sale_from; ?> To Season : <?php print $season_to; ?> Sale No. <?php print $sale_to; ?><br />
        <div class="header-meta">
            <b>BUYER: <?php print $buyer_data['buyer_name']; ?></b>
        </div>
    </div>
</div>

<?php
## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @ buyer-1 with season range
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

    invoice_entry.season,
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.auction_center,
    sale_entry.buyer1 AS buyer_id,  
    sale_entry.buyer_type1 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    ((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)  

WHERE invoice_entry.auction_center='".$auction_center."' 
AND invoice_entry.soft_delete='1' 
AND invoice_entry.catalogue_status='prepared' 
AND ($season_clause)
AND sale_entry.buyer1 IN ($buyer_data[buyer_id]) 
GROUP BY invoice_entry.id) ");

// create bill_register @ buyer-2 with season range
$this->db->query("INSERT INTO `bill_register` (
SELECT 

    invoice_entry.season,
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.auction_center,
    sale_entry.buyer2 AS buyer_id,  
    sale_entry.buyer_type2 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    ((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)  

WHERE invoice_entry.auction_center='".$auction_center."' 
AND invoice_entry.soft_delete='1' 
AND invoice_entry.catalogue_status='prepared' 
AND ($season_clause)
AND sale_entry.buyer2 IN ($buyer_data[buyer_id]) 
GROUP BY invoice_entry.id) ");

// create bill_register @ buyer-3 with season range
$this->db->query("INSERT INTO `bill_register` (
SELECT 

    invoice_entry.season,
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.auction_center,
    sale_entry.buyer3 AS buyer_id,  
    sale_entry.buyer_type3 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    ((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 

WHERE invoice_entry.auction_center='".$auction_center."' 
AND invoice_entry.soft_delete='1' 
AND invoice_entry.catalogue_status='prepared' 
AND ($season_clause)
AND sale_entry.buyer3 IN ($buyer_data[buyer_id]) 
GROUP BY invoice_entry.id) ");
?>

<table class="table table-sm" id="table">
    <thead>
        <tr>
            <th style="text-align:center; border:1px solid #000;">Sale No</th>
            <th style="text-align:center; border:1px solid #000;">Sale Date</th>
            <th style="text-align:center; border:1px solid #000;">Pkgs</th>
            <th style="text-align:center; border:1px solid #000;">Kilogram</th>
            <th style="text-align:center; border:1px solid #000;">Sold Amount</th>
            <th style="text-align:center; border:1px solid #000;">VAT</th>
            <th style="text-align:center; border:1px solid #000;">AIT</th>
            <th style="text-align:center; border:1px solid #000;">Brokerage</th>
            <th style="text-align:center; border:1px solid #000;">Total Amount</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $total_bag = 0; 
    $total_kgs = 0; 
    $total_amount = 0; 
    $total_ait = 0; 
    $total_vat = 0; 
    $total_brokerage = 0; 
    $grand_total = 0;
    
    $this->db->select("sale_no, season, sale_date, auction_center, buyer_type, 
        sum(buyer_bag) AS buyer_bag, 
        sum(buyer_kgs) AS buyer_kgs, 
        sum(buyer_amount) AS buyer_amount, 
        sum(ait_tk) AS total_ait_tk,
        sum(vat_tk) AS total_vat_tk,
        sum(brokerage_tk) AS total_brokerage_tk");     
    $this->db->from('bill_register');
    $this->db->group_by('sale_no, season');
    $this->db->order_by('season ASC, sale_no ASC');
    $query = $this->db->get();
    
    foreach($query->result_array() as $row){
        // Calculate total amount for each row
        $total_amount_row = $row['buyer_amount'] + $row['total_vat_tk'] + $row['total_ait_tk'] + $row['total_brokerage_tk'];
    ?>  
        <tr>
            <td style="text-align:center;"><?php echo $row['sale_no']; ?></td>
            <td style="text-align:center;"><?php echo date('d-m-Y', strtotime($row['sale_date'])); ?></td>
            <td style="text-align:right;"><?php echo $row['buyer_bag']; ?></td>
            <td style="text-align:right;"><?php echo number_format($row['buyer_kgs'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['buyer_amount'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['total_vat_tk'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['total_ait_tk'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['total_brokerage_tk'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($total_amount_row,2,'.',','); ?></td>
        </tr>   
    <?php 
        $total_bag += $row['buyer_bag']; 
        $total_kgs += $row['buyer_kgs'];
        $total_amount += $row['buyer_amount']; 
        $total_ait += $row['total_ait_tk'];
        $total_vat += $row['total_vat_tk'];
        $total_brokerage += $row['total_brokerage_tk'];
        $grand_total += $total_amount_row;
    } ?>
        <tr class="total-row">
            <td colspan="2" style="text-align:left; ">Total:</td>
            <td style="text-align:right; "><?php echo $total_bag; ?></td>
            <td style="text-align:right; "><?php echo number_format($total_kgs,2,'.',','); ?></td>
            <td style="text-align:right; "><?php echo number_format($total_amount,2,'.',','); ?></td>
            <td style="text-align:right; "><?php echo number_format($total_vat,2,'.',','); ?></td>
            <td style="text-align:right; "><?php echo number_format($total_ait,2,'.',','); ?></td>
            <td style="text-align:right; "><?php echo number_format($total_brokerage,2,'.',','); ?></td>
            <td style="text-align:right; "><?php echo number_format($grand_total,2,'.',','); ?></td>
        </tr>
    </tbody>
</table>

</div>
<?php } ?>


<?php if($report_no=='2'){ 
    // Capture the date and buyer parameters from the URL
    $date_from = (!empty($this->input->get('date_from', TRUE))) ? $this->input->get('date_from', TRUE) : '';
    $date_to = (!empty($this->input->get('date_to', TRUE))) ? $this->input->get('date_to', TRUE) : '';
    $buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';

    // Re-fetch buyer details specifically for this ID if needed
    $query = $this->db->query("SELECT buyer_name, GROUP_CONCAT(id) AS buyer_id FROM `buyer_setup` WHERE `id`='".$buyer_id."' OR `main_buyer_id`='".$buyer_id."' ");
    $buyer_data = $query->row_array();
?>

<div class="container" id="printableArea">
    <div class="row no-print">
        <div class="form-group col-sm-12 text-right" style="margin-bottom: 0px !important;">
            <a href="#" onClick="printDiv('printableArea')"><span class="badge" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
        </div>
    </div>
    
<style>
    body {
        font-family: Verdana, Geneva, sans-serif !important;
        background-color: white !important;
        margin-top: 0px !important;
        color: #000;
    }

    .table {
        font-family: Verdana, Geneva, sans-serif;
        width: 100% !important;
        border-collapse: collapse;
        margin-top: 15px;
    }

    @media print {
        /* Scale the page to 88% */
        @page {
            size: A4 portrait;
            margin: 10mm 8mm 10mm 8mm;
            transform: scale(0.88);
            transform-origin: top left;
        }
        
        thead {
            display: table-header-group;
        }
        .no-print,
        .no-print * {
            display: none !important;
        }
        /* Prevent page breaks inside rows */
        #table tbody tr {
            page-break-inside: avoid;
        }
        /* Ensure table doesn't break awkwardly */
        #table {
            page-break-inside: auto;
        }
        body {
            margin: 0;
            padding: 0;
            zoom: 88%;
        }
        .container {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        /* Scale table for print */
        #table {
            font-size: 9.68px !important; /* 11px * 0.88 */
        }
        #table th {
            font-size: 9.68px !important;
            padding: 5px 3px !important;
        }
        #table tbody tr td {
            font-size: 9.68px !important;
            padding: 2px 2px !important;
        }
        #table tbody tr.total-row th, 
        #table tbody tr.total-row td {
            font-size: 9.68px !important;
            padding: 5px 3px !important;
        }
        .header-meta {
            font-size: 10.56px !important; /* 12px * 0.88 */
        }
        .col-sm-12 b {
            font-size: 14.08px !important; /* 16px * 0.88 */
        }
        .col-sm-12 b[style*="font-size: 13px"] {
            font-size: 11.44px !important; /* 13px * 0.88 */
        }
    }
    
    /* Default page settings for screen */
    @page {
        size: A4 portrait;
        margin: 10mm 8mm 10mm 8mm;
    }

    #table {
        border: none !important;
        page-break-inside: auto;
        width: 100% !important;
    }

    #table thead {
        display: table-header-group;
    }

    #table tbody tr {
        page-break-inside: avoid;
    }

    #table tbody tr.total-row {
        page-break-inside: avoid;
        page-break-after: avoid;
    }

    /* Header borders only */
    #table th {
        border: 1px solid #000 !important;
        text-align: center;
        text-transform: uppercase;
        font-size: 11px !important;
        font-weight: normal;
        padding: 6px 4px;
        background-color: #f9f9f9;
    }

    /* Data cells - NO borders */
    #table tbody tr td {
        border: none !important;
        font-size: 11px !important;
        padding: 2px 3px;
    }

    /* Total row - NO borders on cells, only top/bottom borders for the row */
    #table tbody tr.total-row th, 
    #table tbody tr.total-row td {
        border-top: 1px solid #000 !important;
        border-bottom: 1px solid #000 !important;
        border-left: none !important;
        border-right: none !important;
        font-size: 11px !important;
        padding: 6px 4px;
    }

    .header-meta {
        font-size: 12px;
    }
    
    /* Ensure container doesn't cause issues in print */
    .container {
        width: 100% !important;
        padding: 0 15px !important;
        margin: 0 auto !important;
    }
    
    .row {
        margin: 0 !important;
    }
    
    .col-sm-12 {
        padding: 0 !important;
    }
</style>

<div class="row">
    <div class="col-sm-12" style="text-align: left;">
        <b style="font-size: 16px; text-transform: uppercase;"><?php print $website_title; ?></b><br />
        <span style="font-size: 13px; text-transform: uppercase;">BUYERS PURCHASE STATEMENT</span>&nbsp;&nbsp; From Date: <?php print date('d-m-Y', strtotime($date_from)); ?> To Date: <?php print date('d-m-Y', strtotime($date_to)); ?><br />
        <div class="header-meta">
            <b>BUYER: <?php print $buyer_data['buyer_name']; ?></b>
        </div>
    </div>
</div>

<?php
## create temporary table - bill_register based on sale_date
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @ buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.season,
    invoice_entry.auction_center,
    sale_entry.buyer1 AS buyer_id,  
    sale_entry.buyer_type1 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    ((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)  
WHERE invoice_entry.sale_date BETWEEN '".$date_from."' AND '".$date_to."' 
AND sale_entry.buyer1 IN ($buyer_data[buyer_id]) 
AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// insert for buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.season,
    invoice_entry.auction_center,
    sale_entry.buyer2 AS buyer_id,  
    sale_entry.buyer_type2 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    ((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)  
WHERE invoice_entry.sale_date BETWEEN '".$date_from."' AND '".$date_to."' 
AND sale_entry.buyer2 IN ($buyer_data[buyer_id]) 
AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// insert for buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 
    invoice_entry.sale_no,
    invoice_entry.sale_date,
    invoice_entry.lot_no,
    invoice_entry.season,
    invoice_entry.auction_center,
    sale_entry.buyer3 AS buyer_id,  
    sale_entry.buyer_type3 AS buyer_type,
    buyer_setup.buyer_name,
    IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
    IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
    IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
    ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
    ((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
    ((IFNULL(sale_entry.buyer3_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk
FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 
WHERE invoice_entry.sale_date BETWEEN '".$date_from."' AND '".$date_to."' 
AND sale_entry.buyer3 IN ($buyer_data[buyer_id]) 
AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<table class="table table-sm" id="table">
    <thead>
        <tr>
            <th style="text-align:center; border:1px solid #000;">Sale No</th>
            <th style="text-align:center; border:1px solid #000;">Sale Date</th>
            <th style="text-align:center; border:1px solid #000;">Pkgs</th>
            <th style="text-align:center; border:1px solid #000;">Kilogram</th>
            <th style="text-align:center; border:1px solid #000;">Sold Amount</th>
            <th style="text-align:center; border:1px solid #000;">VAT</th>
            <th style="text-align:center; border:1px solid #000;">AIT</th>
            <th style="text-align:center; border:1px solid #000;">Brokerage</th>
            <th style="text-align:center; border:1px solid #000;">Total Amount</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $total_bag = 0; 
    $total_kgs = 0; 
    $total_amount = 0; 
    $total_ait = 0; 
    $total_vat = 0; 
    $total_brokerage = 0; 
    $grand_total = 0;
    
    $this->db->select("sale_no, sale_date, season, auction_center, buyer_type, 
        sum(buyer_bag) AS buyer_bag, 
        sum(buyer_kgs) AS buyer_kgs, 
        sum(buyer_amount) AS buyer_amount, 
        sum(ait_tk) AS total_ait_tk,
        sum(vat_tk) AS total_vat_tk,
        sum(brokerage_tk) AS total_brokerage_tk");     
    $this->db->from('bill_register');
    $this->db->group_by('sale_no, season');
    $this->db->order_by('sale_date ASC');
    $query = $this->db->get();
    
    foreach($query->result_array() as $row){
        // Calculate total amount for each row
        $total_amount_row = $row['buyer_amount'] + $row['total_vat_tk'] + $row['total_ait_tk'] + $row['total_brokerage_tk'];
    ?>  
        <tr>
            <td style="text-align:center;"><?php echo $row['sale_no']; ?></td>
            <td style="text-align:center;"><?php echo date('d-m-Y', strtotime($row['sale_date'])); ?></td>
            <td style="text-align:right;"><?php echo $row['buyer_bag']; ?></td>
            <td style="text-align:right;"><?php echo number_format($row['buyer_kgs'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['buyer_amount'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['total_vat_tk'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['total_ait_tk'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($row['total_brokerage_tk'],2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($total_amount_row,2,'.',','); ?></td>
        </tr>   
    <?php 
        $total_bag += $row['buyer_bag']; 
        $total_kgs += $row['buyer_kgs'];
        $total_amount += $row['buyer_amount']; 
        $total_ait += $row['total_ait_tk'];
        $total_vat += $row['total_vat_tk'];
        $total_brokerage += $row['total_brokerage_tk'];
        $grand_total += $total_amount_row;
    } ?>
        <tr class="total-row">
            <td colspan="2" style="text-align:left;">Total:</td>
            <td style="text-align:right;"><?php echo $total_bag; ?></td>
            <td style="text-align:right;"><?php echo number_format($total_kgs,2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($total_amount,2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($total_vat,2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($total_ait,2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($total_brokerage,2,'.',','); ?></td>
            <td style="text-align:right;"><?php echo number_format($grand_total,2,'.',','); ?></td>
        </tr>
    </tbody>
</table>

</div>
<?php } ?>

<?php if($report_no=='3'){ ?>
<div class="container" id="printableArea">
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

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

@page{
	size:A4 landscape;
	margin-left: 100px;
}	
</style>

<div class="row">
<div class="col-sm-12">

<table class="table table-sm text-center" id="table" style="margin-bottom:0px;">
<thead>
<tr>
	<td colspan="2" style="text-transform: uppercase; text-align:center; border:0px;">
		<b style="font-size:15px;"><?php print $website_title; ?></b>
	</td>
</tr>
<tr style="border-bottom:1px solid #000;">
	<td style="text-transform:uppercase; text-align:center; border:0px;">
		<b style="font-size:13px;">
			Buyer Wise Bill Statement &nbsp; <?php print $sale_from; ?> &nbsp; <?php print $auction_center; ?> Season &nbsp; <?php print $season; ?> &nbsp; To &nbsp; <?php print $sale_to; ?> &nbsp; <?php print $auction_center; ?> Season &nbsp; <?php print $season; ?>
		</b>
	</td>
	<td style="text-align:right; border:0px;"><span style="font-size:13px;"></span></td>
</tr>
<!-- This row is only used for two row spacing -->
<tr><td height="10" colspan="2"></td></tr>
<!-- This row is only used for two row spacing -->
<?php
// buyer details
$query = $this->db->query("SELECT `buyer_name`, GROUP_CONCAT(`id`) AS `buyer_id` FROM `buyer_setup` WHERE `id`='".$buyer_id."' OR `main_buyer_id`='".$buyer_id."' ");
$buyer_data = $query->row_array();
?>
<tr>
	<td colspan="2" style="border:none; text-align:left;">
		<b style="font-family: verdana; font-size:13px; text-transform:uppercase;">Buyer Name: <?php print $buyer_data['buyer_name']; ?></b>
	</td>
</tr>
</thead>	
</table>

<?php
$ac_clause = ($auction_center=='All Center') ? "NOT invoice_entry.auction_center='' " : "invoice_entry.auction_center='".$auction_center."' ";

## create temporary table - bill_register
$this->db->query("DROP TABLE IF EXISTS `bill_register`");

// create bill_register @  buyer-1
$this->db->query("CREATE TABLE `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	buyer_setup.buyer_tin_no,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer1_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer1_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS `total_tk`

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND sale_entry.buyer1 IN ($buyer_data[buyer_id]) AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-2
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	buyer_setup.buyer_name,
	buyer_setup.buyer_category,
	buyer_setup.buyer_tin_no,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
	((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) AS brokerage_tk,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk,
	((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100) AS ait_tk,
	(
		IFNULL(sale_entry.buyer2_amount,0) 
		+ 
		((IFNULL(sale_entry.buyer2_total_kgs,0) * IFNULL(invoice_entry.brokerage,0)) / 100) 
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100)
		+
		((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.ait,0)) / 100)
	) AS `total_tk`

FROM invoice_entry  
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id)  

WHERE invoice_entry.season='".$season."' AND $ac_clause AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND sale_entry.buyer2 IN ($buyer_data[buyer_id]) AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create bill_register @  buyer-3
$this->db->query("INSERT INTO `bill_register` (
SELECT 

	`invoice_entry`.`sale_no`,
	`invoice_entry`.`sale_date`,
	`invoice_entry`.`lot_no`,
	`sale_entry`.`buyer3` AS `buyer_id`,	
	`sale_entry`.`buyer_type3` AS `buyer_type`,
	`buyer_setup`.`buyer_name`,
	`buyer_setup`.`buyer_category`,
	`buyer_setup`.`buyer_tin_no`,
	IFNULL(`sale_entry`.`buyer3_bag`,0) AS `buyer_bag`,
	IFNULL(`sale_entry`.`buyer3_total_kgs`,0) AS `buyer_kgs`,
	IFNULL(`sale_entry`.`buyer3_amount`,0) AS `buyer_amount`,
	((IFNULL(`sale_entry`.`buyer3_total_kgs`,0) * IFNULL(`invoice_entry`.`brokerage`,0)) / 100) AS `brokerage_tk`,
	((IFNULL(`sale_entry`.`buyer3_amount`,0) * IFNULL(`invoice_entry`.`vat`,0)) / 100) AS `vat_tk`,
	((IFNULL(`sale_entry`.`buyer3_amount`,0) * IFNULL(`invoice_entry`.`ait`,0)) / 100) AS `ait_tk`,
	(
		IFNULL(`sale_entry`.`buyer3_amount`,0) 
		+ 
		((IFNULL(`sale_entry`.`buyer3_total_kgs`,0) * IFNULL(`invoice_entry`.`brokerage`,0)) / 100) 
		+
		((IFNULL(`sale_entry`.`buyer3_amount`,0) * IFNULL(`invoice_entry`.`vat`,0)) / 100)
		+
		((IFNULL(`sale_entry`.`buyer3_amount`,0) * IFNULL(`invoice_entry`.`ait`,0)) / 100)
	) AS `total_tk`

FROM `invoice_entry`  
JOIN `sale_entry` ON (`invoice_entry`.`id` = `sale_entry`.`invoice_id`) 
JOIN `buyer_setup` ON (`sale_entry`.`buyer3` = `buyer_setup`.`id`) 

WHERE `invoice_entry`.`season`='".$season."' AND $ac_clause AND `invoice_entry`.`auction_center`='".$auction_center."' AND `invoice_entry`.`sale_no` BETWEEN '".$sale_from."' AND '".$sale_to."' AND `sale_entry`.`buyer3` IN ($buyer_data[buyer_id]) AND `invoice_entry`.`catalogue_status`='prepared' AND `invoice_entry`.`soft_delete`='1' GROUP BY `invoice_entry`.`id`) ");
?>

<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:1px solid;">SL</th>
	<th style="text-align:center; border:1px solid;">Total <br> PKgs</th>
	<th style="text-align:center; border:1px solid;">Total <br> KG</th>
	<th style="text-align:center; border:1px solid;">Amount <br> Tk.</th>
	<th style="text-align:center; border:1px solid;">Vat <br> 15 %</th>
	<th style="text-align:center; border:1px solid;">AIT <br> 1 %</th>
	<th style="text-align:center; border:1px solid;">Brokerage</th>
	<th style="text-align:center; border:1px solid;">Total <br> Amount</th>
</tr>
</thead>
<tbody>
<?php
// reset value
$total_bags = 0;
$total_kgs = 0;
$total_amount = 0;
$total_brokerage = 0;
$total_vat_tk = 0;
$total_ait_tk = 0;
$total_total_tk = 0;
	
// query
$this->db->select("`sale_no`, `buyer_type`, sum(`buyer_bag`) AS `total_bags`, sum(`buyer_kgs`) AS `total_kgs`, sum(`buyer_amount`) AS `total_amount`, sum(`brokerage_tk`) AS `total_brokerage`, IF(`buyer_type`='Internal', sum(`vat_tk`), '0.00') AS `total_vat_tk`, sum(`ait_tk`) AS `total_ait_tk`, IF(`buyer_type`='Internal', sum(`total_tk`), (IFNULL(sum(`total_tk`),0) - IFNULL(sum(`vat_tk`),0))) AS `total_total_tk` "); 	
$this->db->from('`bill_register`');
$this->db->group_by('`sale_no`');
$this->db->order_by('`sale_no` ASC');
$query = $this->db->get();
foreach($query->result_array() as $row){
?>	
<tr>
	<td style="text-align:center; font-weight:bold; border:1px solid;">
		<?php echo $row['sale_no']; ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:1px solid;">
		<?php echo $row['total_bags']; ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:1px solid;">
		<?php echo number_format($row['total_kgs'],2,'.',''); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:1px solid;">
		<?php echo number_format($row['total_amount'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:1px solid;">
		<?php echo number_format($row['total_vat_tk'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:1px solid;">
		<?php echo number_format($row['total_ait_tk'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:1px solid;">
		<?php echo number_format($row['total_brokerage'],2,'.',','); ?>
	</td>
	<td style="text-align:right; font-weight:bold; border:1px solid;">
		<?php echo number_format($row['total_total_tk'],2,'.',','); ?>
	</td>
</tr>	
<?php 
	$total_bags += $row['total_bags'];
	$total_kgs += $row['total_kgs'];
	$total_amount += $row['total_amount'];
	$total_brokerage += $row['total_brokerage'];
	$total_vat_tk += $row['total_vat_tk'];
	$total_ait_tk += $row['total_ait_tk'];
	$total_total_tk += $row['total_total_tk'];
} // foreach 
?>
<tr>
	<th style="text-align:right; border:1px solid;">TOTAL : </th>
	<th style="text-align:right; border:1px solid;"><?php echo $total_bags; ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($total_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($total_amount,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($total_vat_tk,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($total_ait_tk,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($total_brokerage,2,'.',','); ?></th>
	<th style="text-align:right; border:1px solid;"><?php echo number_format($total_total_tk,2,'.',','); ?></th>
</tr>
</tbody>
</table>

</div>
</div>
<!-- /row -->

<br />
<br />
<div class="row">
	<div class="col-sm-12 text-primary text-left">
		<div class="text-left"> 
			<b style="text-align:center; font-size:14px;">&nbsp; &nbsp; &nbsp; Yours faithfully</b> <br /> For <b style="text-transform: uppercase; font-size:14px;"> <?php print $website_title; ?> </b>
		</div>
	</div>
</div>
<!-- /row -->

</div>
<!-- /container -->
<?php } // end of report 3 ?>

<script>
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
}
</script>

</body>
</html>