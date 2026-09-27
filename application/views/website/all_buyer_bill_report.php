<!DOCTYPE html>
<html lang="en">
<head>
<title>BUYER BILL</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>

<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';

## Sale details
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$sale_data = $query->row_array();
?>

<style>
body{
    background-color: white !important;
    font-family:verdana;
}
</style>

<div class="container">
<div class="row no-print">
    <div class="col-sm-12 text-right">
        <a href="#" style="text-decoration:none;" onClick="window.print();">
            <span class="label label-success" style="cursor:pointer;" id="page_counter"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print All </span> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        </a>
    </div>
</div>

<?php
// Get all buyers for this sale if buyer_id is 'all'
if($buyer_id == 'all') {
    $query = $this->db->query("
        SELECT DISTINCT b.id, b.buyer_name 
        FROM sale_entry s
        JOIN buyer_setup b ON (s.buyer1 = b.id OR s.buyer2 = b.id OR s.buyer3 = b.id)
        JOIN invoice_entry i ON (s.invoice_id = i.id)
        WHERE i.season='".$season."' 
        AND i.auction_center='".$auction_center."' 
        AND i.sale_no='".$sale_no."'
		AND b.buyer_category = 'Org'
        ORDER BY b.buyer_name
    ");
    $all_buyers = $query->result_array();
} else {
    // Single buyer case
    $query = $this->db->query("SELECT * FROM buyer_setup WHERE id='".$buyer_id."' AND buyer_category = 'Org' ");
    $all_buyers = array($query->row_array());
}

// Initialize page counter outside the loop
$page_counter = 1;

foreach($all_buyers as $buyer) {
    $current_buyer_id = $buyer['id'];
    $buyer_data = $buyer;
    
    ## create temporary table - buyer_bill
    $this->db->query("DROP TABLE IF EXISTS `buyer_bill`");

    // create buyer_bill
    $this->db->query("CREATE TABLE `buyer_bill` (
    SELECT 
        invoice_entry.lot_no, 
        invoice_entry.catalouge_name, 
        invoice_entry.w_house_id, 
        ws.warehouse_name,
        gs.garden_name,
        invoice_entry.w_inv_no, 
        CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,    
        IFNULL(invoice_entry.vat,0) AS vat,
        IFNULL(invoice_entry.ait,0) AS ait,
        IFNULL(invoice_entry.brokerage,0) AS brokerage,

        (CASE 
            WHEN IFNULL(sale_entry.buyer1_bag,0) > 0 AND sale_entry.buyer1 = '".$current_buyer_id."' THEN sale_entry.buyer_type1 
            WHEN IFNULL(sale_entry.buyer2_bag,0) > 0 AND sale_entry.buyer2 = '".$current_buyer_id."' THEN sale_entry.buyer_type2 
            WHEN IFNULL(sale_entry.buyer3_bag,0) > 0 AND sale_entry.buyer3 = '".$current_buyer_id."' THEN sale_entry.buyer_type3 
            ELSE ''
            END
        ) AS buyer_type, 
        
        @total_pkgs:=(
            IFNULL((CASE WHEN sale_entry.buyer1 = '".$current_buyer_id."' THEN sale_entry.buyer1_bag END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer2 = '".$current_buyer_id."' THEN sale_entry.buyer2_bag END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer3 = '".$current_buyer_id."' THEN sale_entry.buyer3_bag END),0)
        ) AS total_pkgs,

        @net_kgs:=(
        CASE 
            WHEN sale_entry.buyer1 = '".$current_buyer_id."' THEN CONCAT(sale_entry.buyer1_kg_dm,'<br>',sale_entry.buyer1_smpl_dm) 
            WHEN sale_entry.buyer2 = '".$current_buyer_id."' THEN CONCAT(sale_entry.buyer2_kg_dm,'<br>',sale_entry.buyer2_smpl_dm) 
            WHEN sale_entry.buyer3 = '".$current_buyer_id."' THEN CONCAT(sale_entry.buyer3_kg_dm,'<br>',sale_entry.buyer3_smpl_dm) 
            ELSE ''
        END    
        ) AS net_kgs,

        @total_kgs:=(
            IFNULL((CASE WHEN sale_entry.buyer1 = '".$current_buyer_id."' THEN sale_entry.buyer1_total_kgs END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer2 = '".$current_buyer_id."' THEN sale_entry.buyer2_total_kgs END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer3 = '".$current_buyer_id."' THEN sale_entry.buyer3_total_kgs END),0)
        ) AS total_kgs,    

        sale_entry.sold_price,            
        sale_entry.complete_price,            

        @amount:=(
            IFNULL((CASE WHEN sale_entry.buyer1 = '".$current_buyer_id."' THEN sale_entry.buyer1_amount END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer2 = '".$current_buyer_id."' THEN sale_entry.buyer2_amount END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer3 = '".$current_buyer_id."' THEN sale_entry.buyer3_amount END),0)
        ) AS amount,

        @complete_amount:=(
            IFNULL((CASE WHEN sale_entry.buyer1 = '".$current_buyer_id."' THEN sale_entry.buyer1_complete_amount END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer2 = '".$current_buyer_id."' THEN sale_entry.buyer2_complete_amount END),0) 
            + 
            IFNULL((CASE WHEN sale_entry.buyer3 = '".$current_buyer_id."' THEN sale_entry.buyer3_complete_amount END),0)
        ) AS complete_amount,

        @brokerage_tk:=(
            (@total_kgs * IFNULL(invoice_entry.brokerage,0)) / 100
        ) AS brokerage_tk,

        @vat_tk:=(
            (@amount * IFNULL(invoice_entry.vat,0)) / 100
        ) AS vat_tk,

        @ait_tk:=(
            (@amount * IFNULL(invoice_entry.ait,0)) / 100
        ) AS ait_tk,

        @total_amount:=(
            @amount + @brokerage_tk + @vat_tk + @ait_tk
        ) AS total_amount,

        IF(@complete_amount > 0, @total_amount - @complete_amount,'0') AS adjustment_amount
        
    FROM invoice_entry  
    JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
    LEFT JOIN garden_and_company_setup gs ON (invoice_entry.garden_id = gs.id) 
    LEFT JOIN warehouse_setup ws ON (invoice_entry.w_house_id = ws.id) 
    LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
    LEFT JOIN buyer_setup b1 ON (sale_entry.buyer1 = b1.id) 
    LEFT JOIN buyer_setup b2 ON (sale_entry.buyer2 = b2.id) 
    LEFT JOIN buyer_setup b3 ON (sale_entry.buyer3 = b3.id) 

    WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' 
    AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' 
    AND (sale_entry.buyer1='".$current_buyer_id."' OR sale_entry.buyer2 = '".$current_buyer_id."' OR sale_entry.buyer3 = '".$current_buyer_id."') 
    GROUP BY invoice_entry.id) ");

    // bill breakdown
    $query = $this->db->query("SELECT buyer_type, sum(total_pkgs) AS total_pkgs, sum(total_kgs) AS total_kgs, sum(amount) AS total_amount, 
                              sum(adjustment_amount) AS adjustment_amount, IF(buyer_type='Export','0',sum(vat_tk)) AS total_vat, 
                              sum(ait_tk) AS total_ait, sum(brokerage_tk) AS total_brokerage, vat, ait, brokerage 
                              FROM buyer_bill GROUP BY buyer_type ORDER BY buyer_type DESC");
    
    if($query->num_rows() > 0) {
        $inv_query = $query->result_array();
        foreach($inv_query as $row_data) {
            $total_pkgs = $row_data['total_pkgs'];
            $total_kgs = $row_data['total_kgs'];
            $total_amount = $row_data['total_amount'];
            $adjustment_amount = $row_data['adjustment_amount'];
            $total_vat = $row_data['total_vat'];
            $total_ait = $row_data['total_ait'];
            $total_brokerage = $row_data['total_brokerage'];
            
            $vat = $row_data['vat'];
            $ait = $row_data['ait'];
            $brokerage = $row_data['brokerage'];
?>
<div class="row print_div" id="print_div_area<?php print $page_counter; ?>">
<div class="col-sm-12">

<style>
.table{
    font-family:verdana;
    border:none;
    font-family: Courier New; 
    letter-spacing: 1.5px;
}    
@media print {
    
    .table {
        border: solid white !important;
    }
    #page{ 
        page-break-after: always !important; 
    }
    .no-print{ 
        display:none; 
    }
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
    .col-sm-2 { width: 16.66666667%; }
    .col-sm-1 { width: 8.33333333%; }
    
}

@page{
    size: auto;
}    
</style>

<div class="row no-print">
    <div class="col-sm-12 text-right">
        <a href="#" style="text-decoration:none;" onClick="printDiv('print_div_area<?php print $page_counter; ?>');"><span class="label label-danger" style="cursor:pointer;"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print </span></a> &nbsp; Page <?php print $page_counter; ?> 
    </div>
</div>

<br />

<div class="row">
    <table class="table">
        <thead>
            <tr>
                <td style="text-transform: uppercase; text-align:center; margin-bottom:10px;">
                    <div style="display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #33333373 !important;">
                        <span>
                            <img src="<?php echo base_url().'assets/img/ubl_logo.png'; ?>" class="img-fluid" style="height:61px; width:61px;" alt="ubl_logo">
                        </span>
                        <span style="padding: 0 5px;"></span>
                        <span>
                            <b style="font-size:15px; text-transform:uppercase; font-family: Courier New; letter-spacing: 1.5px;"><?php print $website_title; ?></b><br>
                            <span style="font-size:13px; text-transform: capitalize; font-family: Courier New; letter-spacing: 1.5px;">Progressive tower (5th floor) <br> 1837, Sk. mujib road,  agrabad C/A, Chittagong</span>
                        </span>
                    </div>
                </td>
            </tr>
        </thead>
    </table>
    <div class="col-sm-6">
        <table style="float:left; font-size:13px;">
            <tr>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"> BILL NO </td> 
                <td class="text-center" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;">&nbsp;:&nbsp;</td>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"><?php print $sale_no.'/'.$current_buyer_id; ?><?php print ($row_data['buyer_type']=='Export') ? 'E' : ''; ?></td>
            </tr>
            <tr>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"> BILL DATE </td> 
                <td class="text-center" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;">&nbsp;:&nbsp;</td>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"><?php print date('d-m-y'); ?></td>
            </tr>
            <tr>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"> HELD ON </td> 
                <td class="text-center" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;">&nbsp;:&nbsp;</td>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px; "><?php print date("d-m-Y",strtotime($sale_data['sale_date'])); ?></td>
            </tr>
        </table>
    </div>
    <div class="col-sm-6" >
        <table style="float:right; font-size:13px;">
            <tr>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"> SEASON </td> 
                <td class="text-center" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;">&nbsp;:&nbsp;</td>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"><?php print $season; ?></td>
            </tr>
            <tr>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"> SALE NO </td> 
                <td class="text-center" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;">&nbsp;:&nbsp;</td>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"><?php print $sale_no; ?>, <?php print $auction_center; ?></td>
            </tr>
            <tr>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"> PROMPT DATE </td> 
                <td class="text-center" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;">&nbsp;:&nbsp;</td>
                <td class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px;"><?php print date("d-m-Y",strtotime($sale_data['buyer_prompt_date'])); ?></td>
            </tr>
        </table>
    </div>
</div>
    
<div class="row">
<div class="col-sm-12" style="padding-top:50px;">
<table class="table" style="font-size:14px;">
    <tr>
        <th colspan="5" style="text-align:left; vertical-align:middle; padding:1px; border:none;">TO: <?php print $buyer_data['buyer_name']; ?></th>
    </tr>
    <tr>
        <td colspan="5" style="text-align:left; vertical-align:middle; padding:1px; border:none;">Bill for tea purchase from our catalogue</td>
    </tr>
    <tr>
        <td style="text-align:left; vertical-align:middle; padding:1px; border-top:none;">As per our contract.</td>
        <td style="text-align:right; vertical-align:middle; padding:1px; border-top:none;">Total</td>
        <td style="text-align:right; vertical-align:middle; padding:1px; border-top:none; border-bottom:1px solid #33333373;">PKGS</td>
        <td style="text-align:right; vertical-align:middle; padding:1px; border-top:none; border-bottom:1px solid #33333373;">QTY/KGS</td>
        <td style="text-align:right; vertical-align:middle; padding:1px; border-top:none; border-bottom:1px solid #33333373;">AMOUNT(TAKA)</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:left; vertical-align:middle; padding:1px; border:none;"></td>
        <th style="text-align:right; vertical-align:middle; padding:1px; border:none;"><?php print $total_pkgs; ?></th>
        <th style="text-align:right; vertical-align:middle; padding:1px; border:none;"><?php print number_format($total_kgs,2,'.',','); ?></th>
        <th style="text-align:right; vertical-align:middle; padding:1px; border:none;"><?php print number_format($total_amount,2,'.',','); ?></th>
    </tr>

    <tr>
        <td style="text-align:right; vertical-align:middle; padding:1px; border:none;" colspan="4"> Brokerage : Tk.</td>
        <th style="text-align:right; vertical-align:middle; padding:1px; border:none;">
        <?php 
            print number_format($total_brokerage,2,'.',','); 
        ?>
        </th>
    </tr>
    
    <?php if($row_data['buyer_type']=='Internal'){ ?>
    <tr>
        <td colspan="2" style="text-align:left; vertical-align:middle; padding:1px; border:none;" colspan="1"> Add Charges : </td>
        <td style="text-align:right; vertical-align:middle; padding:1px; border:none;" colspan="2"> VAT : Tk.</td>
        <th style="text-align:right; vertical-align:middle; padding:1px; border:none;">
        <?php 
            print number_format($total_vat,2,'.',','); 
        ?>
        </th>
    </tr>
    <?php } ?>
    
    <tr>
        <td style="text-align:right; vertical-align:middle; padding:1px; border:none;" colspan="4"> AIT : Tk.</td>
        <th style="text-align:right; vertical-align:middle; padding:1px; border-top:none; border-bottom:1px solid #33333373;">
        <?php 
            print number_format($total_ait,2,'.',','); 
        ?>
        </th>
    </tr>

    <tr>
        <td style="text-align:right; vertical-align:middle; border:none; padding:1px; color:red;" colspan="4"> Net. Amount : Tk. </td>
        <th style="text-align:right; vertical-align:middle; border-top:none; padding:1px; color:red; border-bottom:3px double #33333373;">
        <?php 
            $net_proceed_tk = $total_amount + $total_vat + $total_ait + $total_brokerage;
            print number_format($net_proceed_tk,2,'.',','); 
        ?>
        </th>
    </tr>

    <tr class="no-print">
        <td style="text-align:right; vertical-align:middle; border:none; padding:1px; color:blue;" colspan="4"> Bill Adjustment : Tk. </td>
        <th style="text-align:right; vertical-align:middle; border-top:none; padding:1px; color:blue; border-bottom:3px double #33333373;">
        <?php 
            print number_format($adjustment_amount,2,'.',','); 
        ?>
        </th>
    </tr>

    <tr class="no-print">
        <td style="text-align:right; vertical-align:middle; border:none; padding:1px; color:green;" colspan="4"> Final Net. Amount : Tk. </td>
        <th style="text-align:right; vertical-align:middle; border-top:none; padding:1px; color:green; border-bottom:3px double #33333373;">
        <?php 
            $final_net_proceed_tk = $net_proceed_tk - $adjustment_amount;
            print number_format($final_net_proceed_tk,2,'.',','); 
        ?>
        </th>
    </tr>
</table>
</div>
</div>
    
<div class="row">
<div class="col-sm-12">
    <p class="text-left" style="font-family: Courier New; letter-spacing: 1.5px; border:0px; font-weight:normal; font-size:14px;"> 
        In Words : <?php print convert_number_to_words($net_proceed_tk); ?>
    </p>
</div>
</div>

<div class="row">
<div class="col-sm-12 text-primary" style="margin-top:80px;">
    <p class="text-left"> 
        <b style="text-transform: uppercase; font-size:14px; font-family: Courier New; letter-spacing: 1.5px;"> <?php print $website_title; ?></b>
    </p>
</div>
</div>

</div> <!-- col -->
</div> <!-- row -->
<br />
<div id="page"></div>
<?php
            $page_counter++; // Increment page counter after each bill
        } // foreach invoice query
    } else {
        print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found for buyer: ".$buyer_data['buyer_name']."</div></div>";
    }
} // foreach all_buyers

if(empty($all_buyers)) {
    print "<div class=\"row\"><div class=\"col-sm-4\">No matching record found.</div></div>";
}
?>

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
}
</script>

</body>
</html>