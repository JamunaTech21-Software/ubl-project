<?php 
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>CROP REPORT SALE NO - <?php echo $sale_no ; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<style>
	body{
		font-family:verdana !important;
		background-color: white !important;
		margin-top:10px;
        border : 1px solid ;
	}

	@media print{    
		thead { display: table-header-group; }
		.no-print, .no-print * {
            display: none !important;
        }
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
		size: A4 portrait;
	}
</style>
</head>
<body>
<?php
// Split the season string
list($start, $end) = explode('-', $season);

// Calculate previous season and sale no
$prev_start = $start - 1;
$prev_end = $end - 1;
$previous_season = $prev_start . '-' . $prev_end;
$previous_sale_no = $sale_no - 1;

// Calculate new season and sale no
$new_start = $start + 1;
$new_end = $end + 1;
$new_season = $new_start . '-' . $new_end;

// Current season Leaf and Dust upto sale
$sql = "
    SELECT 
        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs,

        SUM(IF(category = 'Current Season Leaf', IFNULL(qty, 0), 0)) AS total_leaf_kgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(total_amount, 0), 0)) AS total_leaf_amount,

        SUM(IF(category = 'Current Season Dust', IFNULL(qty, 0), 0)) AS total_dust_kgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(total_amount, 0), 0)) AS total_dust_amount,

        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(avg, 0), 0)) AS total_avg
    FROM all_brokers_average_entry
    WHERE season = ?
      AND auction_center = ?
      AND sale_no <= ?
";
$query = $this->db->query($sql, [$season, $auction_center, $previous_sale_no]);
$current_season_upto = $query->row_array();

// Current season Leaf and Dust
$sql = "
    SELECT 
        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs,

        SUM(IF(category = 'Current Season Leaf', IFNULL(pkgs, 0), 0)) AS total_leaf_pkgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(qty, 0), 0)) AS total_leaf_kgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(total_amount, 0), 0)) AS total_leaf_amount,

        SUM(IF(category = 'Current Season Dust', IFNULL(pkgs, 0), 0)) AS total_dust_pkgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(qty, 0), 0)) AS total_dust_kgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(total_amount, 0), 0)) AS total_dust_amount,
        
        SUM(IF(category = 'Current Season Green Tea', IFNULL(pkgs, 0), 0)) AS total_green_tea_pkgs,
        SUM(IF(category = 'Current Season Green Tea', IFNULL(qty, 0), 0)) AS total_green_tea_kgs,
        SUM(IF(category = 'Current Season Green Tea', IFNULL(total_amount, 0), 0)) AS total_green_tea_amount,

        SUM(IF(category = 'Current Season Other Tea', IFNULL(pkgs, 0), 0)) AS total_other_tea_pkgs,
        SUM(IF(category = 'Current Season Other Tea', IFNULL(qty, 0), 0)) AS total_other_tea_kgs,
        SUM(IF(category = 'Current Season Other Tea', IFNULL(total_amount, 0), 0)) AS total_other_tea_amount,

        SUM(IF(category = 'Current Season A/C Concern', IFNULL(pkgs, 0), 0)) AS total_pkgs_current_season_ac,
        SUM(IF(category = 'Current Season A/C Concern', IFNULL(qty, 0), 0)) AS total_kgs_current_season_ac,
        SUM(IF(category = 'Current Season A/C Concern', IFNULL(total_amount, 0), 0)) AS total_amount_current_season_ac, 

        SUM(IF(category IN ('Earlier Season Leaf', 'Earlier Season Dust'), IFNULL(pkgs, 0), 0)) AS total_pkgs_earlier_season,
        SUM(IF(category IN ('Earlier Season Leaf', 'Earlier Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs_earlier_season,
        SUM(IF(category IN ('Earlier Season Leaf', 'Earlier Season Dust'), IFNULL(total_amount, 0), 0)) AS total_amount_earlier_season,

        SUM(IF(category = 'Earlier Season Green Tea', IFNULL(pkgs, 0), 0)) AS total_green_tea_pkgs_earlier_season,
        SUM(IF(category = 'Earlier Season Green Tea', IFNULL(qty, 0), 0)) AS total_green_tea_kgs_earlier_season,
        SUM(IF(category = 'Earlier Season Green Tea', IFNULL(total_amount, 0), 0)) AS total_green_tea_amount_earlier_season,

        SUM(IF(category = 'Earlier Season Other Tea', IFNULL(pkgs, 0), 0)) AS total_other_tea_pkgs_earlier_season,
        SUM(IF(category = 'Earlier Season Other Tea', IFNULL(qty, 0), 0)) AS total_other_tea_kgs_earlier_season,
        SUM(IF(category = 'Earlier Season Other Tea', IFNULL(total_amount, 0), 0)) AS total_other_tea_amount_earlier_season,
        
        SUM(IF(category = 'Earlier Season A/C Concern', IFNULL(pkgs, 0), 0)) AS total_pkgs_earlier_season_ac,
        SUM(IF(category = 'Earlier Season A/C Concern', IFNULL(qty, 0), 0)) AS total_kgs_earlier_season_ac,
        SUM(IF(category = 'Earlier Season A/C Concern', IFNULL(total_amount, 0), 0)) AS total_amount_earlier_season_ac,

        SUM(IF(category IN ('New Season Leaf', 'New Season Dust'), IFNULL(pkgs, 0), 0)) AS total_pkgs_new_season,
        SUM(IF(category IN ('New Season Leaf', 'New Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs_new_season,
        SUM(IF(category IN ('New Season Leaf', 'New Season Dust'), IFNULL(total_amount, 0), 0)) AS total_amount_new_season,

        SUM(IF(category = 'New Season Green Tea', IFNULL(pkgs, 0), 0)) AS total_green_tea_pkgs_new_season,
        SUM(IF(category = 'New Season Green Tea', IFNULL(qty, 0), 0)) AS total_green_tea_kgs_new_season,
        SUM(IF(category = 'New Season Green Tea', IFNULL(total_amount, 0), 0)) AS total_green_tea_amount_new_season,

        SUM(IF(category = 'New Season Other Tea', IFNULL(pkgs, 0), 0)) AS total_other_tea_pkgs_new_season,
        SUM(IF(category = 'New Season Other Tea', IFNULL(qty, 0), 0)) AS total_other_tea_kgs_new_season,
        SUM(IF(category = 'New Season Other Tea', IFNULL(total_amount, 0), 0)) AS total_other_tea_amount_new_season,
        
        SUM(IF(category = 'New Season A/C Concern', IFNULL(pkgs, 0), 0)) AS total_pkgs_new_season_ac,
        SUM(IF(category = 'New Season A/C Concern', IFNULL(qty, 0), 0)) AS total_kgs_new_season_ac,
        SUM(IF(category = 'New Season A/C Concern', IFNULL(total_amount, 0), 0)) AS total_amount_new_season_ac,

        SUM(IF(category = 'Buyers Purchases Analysis Export', IFNULL(pkgs, 0), 0)) AS total_export_pkgs,
        SUM(IF(category = 'Buyers Purchases Analysis Export', IFNULL(qty, 0), 0)) AS total_export_kgs,
        SUM(IF(category = 'Buyers Purchases Analysis Export', IFNULL(total_amount, 0), 0)) AS total_export_amount,

        SUM(IF(category = 'Buyers Purchases Analysis Internal', IFNULL(pkgs, 0), 0)) AS total_internal_pkgs,
        SUM(IF(category = 'Buyers Purchases Analysis Internal', IFNULL(qty, 0), 0)) AS total_internal_kgs,
        SUM(IF(category = 'Buyers Purchases Analysis Internal', IFNULL(total_amount, 0), 0)) AS total_internal_amount,

        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(avg, 0), 0)) AS total_avg
    FROM all_brokers_average_entry
    WHERE season = ?
      AND auction_center = ?
      AND sale_no = ?
";
$query = $this->db->query($sql, [$season, $auction_center, $sale_no]);
$current_season_fetch = $query->row_array();

// Previous season Leaf and Dust
$sql = "
    SELECT 
        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs,

        SUM(IF(category = 'Current Season Leaf', IFNULL(qty, 0), 0)) AS total_leaf_kgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(total_amount, 0), 0)) AS total_leaf_amount,

        SUM(IF(category = 'Current Season Dust', IFNULL(qty, 0), 0)) AS total_dust_kgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(total_amount, 0), 0)) AS total_dust_amount,

        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(avg, 0), 0)) AS total_avg
    FROM all_brokers_average_entry
    WHERE season = ?
      AND auction_center = ?
      AND sale_no = ?
";
$query = $this->db->query($sql, [$previous_season, $auction_center, $sale_no]);
$previous_season_fetch = $query->row_array();

// Previous upto season Leaf and Dust
$sql = "
    SELECT 
        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs,

        SUM(IF(category = 'Current Season Leaf', IFNULL(qty, 0), 0)) AS total_leaf_kgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(total_amount, 0), 0)) AS total_leaf_amount,

        SUM(IF(category = 'Current Season Dust', IFNULL(qty, 0), 0)) AS total_dust_kgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(total_amount, 0), 0)) AS total_dust_amount,

        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(avg, 0), 0)) AS total_avg
    FROM all_brokers_average_entry
    WHERE season = ?
      AND auction_center = ?
      AND sale_no <= ?
";
$query = $this->db->query($sql, [$previous_season, $auction_center, $previous_sale_no]);
$previous_season_upto = $query->row_array();

// Upto Current sale no
$sql = "
    SELECT 
        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs,

        SUM(IF(category = 'Current Season Leaf', IFNULL(pkgs, 0), 0)) AS total_leaf_pkgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(qty, 0), 0)) AS total_leaf_kgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(total_amount, 0), 0)) AS total_leaf_amount,

        SUM(IF(category = 'Current Season Dust', IFNULL(pkgs, 0), 0)) AS total_dust_pkgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(qty, 0), 0)) AS total_dust_kgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(total_amount, 0), 0)) AS total_dust_amount,

        SUM(IF(category = 'Current Season Green Tea', IFNULL(pkgs, 0), 0)) AS total_green_tea_pkgs,
        SUM(IF(category = 'Current Season Green Tea', IFNULL(qty, 0), 0)) AS total_green_tea_kgs,
        SUM(IF(category = 'Current Season Green Tea', IFNULL(total_amount, 0), 0)) AS total_green_tea_amount,

        SUM(IF(category = 'Current Season Other Tea', IFNULL(pkgs, 0), 0)) AS total_other_tea_pkgs,
        SUM(IF(category = 'Current Season Other Tea', IFNULL(qty, 0), 0)) AS total_other_tea_kgs,
        SUM(IF(category = 'Current Season Other Tea', IFNULL(total_amount, 0), 0)) AS total_other_tea_amount,

        SUM(IF(category = 'Current Season A/C Concern', IFNULL(pkgs, 0), 0)) AS total_pkgs_current_season_ac,
        SUM(IF(category = 'Current Season A/C Concern', IFNULL(qty, 0), 0)) AS total_kgs_current_season_ac,
        SUM(IF(category = 'Current Season A/C Concern', IFNULL(total_amount, 0), 0)) AS total_amount_current_season_ac, 

        SUM(IF(category IN ('Earlier Season Leaf', 'Earlier Season Dust'), IFNULL(pkgs, 0), 0)) AS total_pkgs_earlier_season,
        SUM(IF(category IN ('Earlier Season Leaf', 'Earlier Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs_earlier_season,
        SUM(IF(category IN ('Earlier Season Leaf', 'Earlier Season Dust'), IFNULL(total_amount, 0), 0)) AS total_amount_earlier_season,
		
		SUM(IF(category = 'Earlier Season Green Tea', IFNULL(pkgs, 0), 0)) AS total_green_tea_pkgs_earlier_season,
        SUM(IF(category = 'Earlier Season Green Tea', IFNULL(qty, 0), 0)) AS total_green_tea_kgs_earlier_season,
        SUM(IF(category = 'Earlier Season Green Tea', IFNULL(total_amount, 0), 0)) AS total_green_tea_amount_earlier_season,

        SUM(IF(category = 'Earlier Season Other Tea', IFNULL(pkgs, 0), 0)) AS total_other_tea_pkgs_earlier_season,
        SUM(IF(category = 'Earlier Season Other Tea', IFNULL(qty, 0), 0)) AS total_other_tea_kgs_earlier_season,
        SUM(IF(category = 'Earlier Season Other Tea', IFNULL(total_amount, 0), 0)) AS total_other_tea_amount_earlier_season,
		
		SUM(IF(category = 'Earlier Season A/C Concern', IFNULL(pkgs, 0), 0)) AS total_pkgs_earlier_season_ac,
        SUM(IF(category = 'Earlier Season A/C Concern', IFNULL(qty, 0), 0)) AS total_kgs_earlier_season_ac,
        SUM(IF(category = 'Earlier Season A/C Concern', IFNULL(total_amount, 0), 0)) AS total_amount_earlier_season_ac,

        SUM(IF(category = 'Buyers Purchases Analysis Export', IFNULL(pkgs, 0), 0)) AS total_export_pkgs,
        SUM(IF(category = 'Buyers Purchases Analysis Export', IFNULL(qty, 0), 0)) AS total_export_kgs,
        SUM(IF(category = 'Buyers Purchases Analysis Export', IFNULL(total_amount, 0), 0)) AS total_export_amount,

        SUM(IF(category = 'Buyers Purchases Analysis Internal', IFNULL(pkgs, 0), 0)) AS total_internal_pkgs,
        SUM(IF(category = 'Buyers Purchases Analysis Internal', IFNULL(qty, 0), 0)) AS total_internal_kgs,
        SUM(IF(category = 'Buyers Purchases Analysis Internal', IFNULL(total_amount, 0), 0)) AS total_internal_amount,

        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(avg, 0), 0)) AS total_avg
    FROM all_brokers_average_entry
    WHERE season = ?
      AND auction_center = ?
      AND sale_no <= ?
";
$query = $this->db->query($sql, [$season, $auction_center, $sale_no]);
$upto_current_sale = $query->row_array();

// Previous season current sale no
$sql = "
    SELECT 
        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(qty, 0), 0)) AS total_kgs,

        SUM(IF(category = 'Current Season Leaf', IFNULL(qty, 0), 0)) AS total_leaf_kgs,
        SUM(IF(category = 'Current Season Leaf', IFNULL(total_amount, 0), 0)) AS total_leaf_amount,

        SUM(IF(category = 'Current Season Dust', IFNULL(qty, 0), 0)) AS total_dust_kgs,
        SUM(IF(category = 'Current Season Dust', IFNULL(total_amount, 0), 0)) AS total_dust_amount,

        SUM(IF(category IN ('Current Season Leaf', 'Current Season Dust'), IFNULL(avg, 0), 0)) AS total_avg
    FROM all_brokers_average_entry
    WHERE season = ?
      AND auction_center = ?
      AND sale_no <= ?
";
$query = $this->db->query($sql, [$previous_season, $auction_center, $sale_no]);
$previous_season_current_sale_no = $query->row_array();
?>

<div class="container">

<div class="row no-print">
   <div class="form-group col-sm-12 text-right">
       <a class="btn-default" href="#" onclick="window.print(); return false;">
           <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
       </a>
   </div>
</div>

<div class="row">
    <div class="col-sm-12" style="margin-bottom: 10px;">
        <b>UNITY BROKERS LTD,"PROGRESSIVE TOWER" (5TH FLOOR)1837,SK.MUJIB ROAD CTG</b> <br>
        <b>WE GIVE BELOW THE COMPARATIVE AVERAGE PRICES OF BANGLADESH TEAS SOLD</b> <br>
        <b>IN CHITTAGONG AUCTION SALE NO. <?php echo $sale_no ; ?></b>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="selectable-table" style="width: 100%; border-collapse: collapse;">
            <tr style="vertical-align: bottom; border-bottom:1px solid black; font-weight:bold;">
                <td>SALE NO: CATEGORY</td>
                <td style="text-align: right;"><?php echo $season ; ?> <br> <br> QTY.IN KGS</td>
                <td style="text-align: right; padding-right: 30px;">AVG/TK <br> <br> TAKA</td>
                <td style="text-align: right;"><?php echo $previous_season ; ?> <br> <br> QTY.IN KGS</td>
                <td style="text-align: right;">AVG/TK <br> <br> TAKA</td>
                <td style="text-align: right;">+ OR - <br> <br> TAKA</td>
            </tr>

            <tr>
                <td colspan="6" style="height: 20px;"></td>
            </tr>

            <tr style="vertical-align: middle;">
                <td>UP TO SALE NO. <?php echo $previous_sale_no ; ?> (CTC)</td>
                <td style="text-align: right;"><?php echo number_format($current_season_upto['total_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; padding-right: 30px;">
                    <?php 
                        $current_season_upto_total_amount = $current_season_upto['total_leaf_amount'] + $current_season_upto['total_dust_amount'];
                        $current_season_upto_total_kgs = $current_season_upto['total_leaf_kgs'] + $current_season_upto['total_dust_kgs'];
                        $current_season_upto_avg = ($current_season_upto_total_amount > 0 && $current_season_upto_total_kgs > 0) ? number_format($current_season_upto_total_amount / $current_season_upto_total_kgs,2,'.',',') : '0.00';
                        echo $current_season_upto_avg ; 
                    ?>
                </td>
                <td style="text-align: right;"><?php echo number_format($previous_season_upto['total_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php 
                        $previous_season_upto_total_amount = $previous_season_upto['total_leaf_amount'] + $previous_season_upto['total_dust_amount'];
                        $previous_season_upto_total_kgs = $previous_season_upto['total_leaf_kgs'] + $previous_season_upto['total_dust_kgs'];
                        $previous_season_upto_avg = ($previous_season_upto_total_amount > 0 && $previous_season_upto_total_kgs > 0) ? number_format($previous_season_upto_total_amount / $previous_season_upto_total_kgs,2,'.',',') : '0.00';
                        echo $previous_season_upto_avg ; 
                    ?>
                </td>
                <td style="text-align: right;">
                    <?php
                        echo ($diff = $current_season_upto_avg - $previous_season_upto_avg) != 0 ? (($diff > 0 ? '+' : '') . number_format($diff, 2, '.', ',')): '0.00';
                    ?>
                </td>
            </tr>

            <tr>
                <td colspan="6" style="height: 20px;"></td>
            </tr>

            <tr style="vertical-align: middle;">
                <td>SALE NO. <?php echo $sale_no ; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(CTC)</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; padding-right: 30px;">
                    <?php 
                        $current_season_total_amount = $current_season_fetch['total_leaf_amount'] + $current_season_fetch['total_dust_amount'];
                        $current_season_total_kgs = $current_season_fetch['total_leaf_kgs'] + $current_season_fetch['total_dust_kgs'];
                        $current_season_avg = ($current_season_total_amount > 0 && $current_season_total_kgs > 0) ? number_format($current_season_total_amount / $current_season_total_kgs,2,'.',',') : '0.00';
                        echo $current_season_avg ; 
                    ?>
                </td>
                <td style="text-align: right;"><?php echo number_format($previous_season_fetch['total_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php 
                        $previous_season_total_amount = $previous_season_fetch['total_leaf_amount'] + $previous_season_fetch['total_dust_amount'];
                        $previous_season_total_kgs = $previous_season_fetch['total_leaf_kgs'] + $previous_season_fetch['total_dust_kgs'];
                        $previous_season_avg = ($previous_season_total_amount > 0 && $previous_season_total_kgs > 0) ? number_format($previous_season_total_amount / $previous_season_total_kgs,2,'.',',') : '0.00';
                        echo $previous_season_avg ; 
                    ?>
                </td>
                <td style="text-align: right;"> 
                    <?php
                        echo ($diff = $current_season_avg - $previous_season_avg) != 0 ? (($diff > 0 ? '+' : '') . number_format($diff, 2, '.', ',')): '0.00';
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 20px;"></td>
            </tr>
            <tr style="vertical-align: middle; ">
                <td style="border-top:1px solid black; white-space:nowrap;">UP TO SALE NO. <?php echo $sale_no ; ?> (CTC)</td>
                <td style="text-align: right; border-top:1px solid black;"><?php echo number_format($upto_current_sale['total_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; border-top:1px solid black; padding-right: 30px;">
                    <?php 
                        $upto_current_sale_total_amount = $upto_current_sale['total_leaf_amount'] + $upto_current_sale['total_dust_amount'];
                        $upto_current_sale_total_kgs = $upto_current_sale['total_leaf_kgs'] + $upto_current_sale['total_dust_kgs'];
                        $upto_current_sale_avg = ($upto_current_sale_total_amount > 0 && $upto_current_sale_total_kgs > 0) ? number_format($upto_current_sale_total_amount / $upto_current_sale_total_kgs,2,'.',',') : '0.00';
                        echo $upto_current_sale_avg ; 
                    ?>
                </td>
                <td style="text-align: right; border-top:1px solid black;"><?php echo number_format($previous_season_current_sale_no['total_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; border-top:1px solid black;">
                    <?php 
                        $previous_season_current_sale_no_total_amount = $previous_season_current_sale_no['total_leaf_amount'] + $previous_season_current_sale_no['total_dust_amount'];

                        $previous_season_current_sale_no_total_kgs = $previous_season_current_sale_no['total_leaf_kgs'] + $previous_season_current_sale_no['total_dust_kgs'];

                        $previous_season_current_sale_no_avg = ($previous_season_current_sale_no_total_amount > 0 && $previous_season_current_sale_no_total_kgs > 0) ? number_format($previous_season_current_sale_no_total_amount / $previous_season_current_sale_no_total_kgs,2,'.',',') : '0.00';

                        echo $previous_season_current_sale_no_avg ; 
                    ?>
                </td>
                <td style="text-align: right; border-top:1px solid black;"> 
                    <?php
                        echo ($diff = $upto_current_sale_avg - $previous_season_current_sale_no_avg) != 0 ? (($diff > 0 ? '+' : '') . number_format($diff, 2, '.', ',')): '0.00';
                    ?>
                </td>
            </tr>

            <tr>
                <td colspan="6" style="height: 20px;"></td>
            </tr>

            <tr style="vertical-align: middle; border-top:1px solid black; border-bottom:1px solid black; font-weight:bold;">
                <td colspan="2">AVERAGE PRICE SALE NO. <?php echo $sale_no ; ?></td>
                <td style="text-align: center;">CATEGORY</td>
                <td style="text-align: center;">PACKAGES</td>
                <td style="text-align: center;">KILOGRAM</td>
                <td style="text-align: center;">AVG/TK</td>
            </tr>

            <tr style="vertical-align: middle;">
                <td colspan="2"></td>
                <td style="text-align: center;">Leaf</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_leaf_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_leaf_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php 
                        echo ($current_season_fetch['total_leaf_amount'] > 0 && $current_season_fetch['total_leaf_kgs'] > 0) ? number_format($current_season_fetch['total_leaf_amount'] / $current_season_fetch['total_leaf_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>

            <tr style="vertical-align: middle;">
                <td colspan="2"></td>
                <td style="text-align: center; border-bottom:1px solid #ccc;">Dust</td>
                <td style="text-align: right; border-bottom:1px solid #ccc;"><?php echo number_format($current_season_fetch['total_dust_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right; border-bottom:1px solid #ccc;"><?php echo number_format($current_season_fetch['total_dust_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; border-bottom:1px solid #ccc;">
                    <?php echo ($current_season_fetch['total_dust_amount'] > 0 && $current_season_fetch['total_dust_kgs'] > 0) ? number_format($current_season_fetch['total_dust_amount'] / $current_season_fetch['total_dust_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px; border-bottom:1px solid;"></td>
            </tr>

            <tr style="vertical-align: middle; font-weight:bold;">
                <td colspan="2"></td>
                <td style="text-align: center;">TOTAL</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_leaf_pkgs'] + $current_season_fetch['total_dust_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_leaf_kgs'] + $current_season_fetch['total_dust_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo $current_season_avg ; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>

            <!-- Current Season -->
            <?php if($current_season_fetch['total_green_tea_pkgs'] > 0 || $current_season_fetch['total_green_tea_kgs'] > 0 ||$current_season_fetch['total_green_tea_amount'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                
                <td colspan="3" style="text-align: left;">GREEN TEA............</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_green_tea_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_green_tea_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_green_tea_amount'] > 0 && $current_season_fetch['total_green_tea_kgs'] > 0) ? number_format($current_season_fetch['total_green_tea_amount'] / $current_season_fetch['total_green_tea_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($current_season_fetch['total_other_tea_pkgs'] > 0 || $current_season_fetch['total_other_tea_kgs'] > 0 ||$current_season_fetch['total_other_tea_amount'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">OTHER TEAS............</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_other_tea_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_other_tea_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_other_tea_amount'] > 0 && $current_season_fetch['total_other_tea_kgs'] > 0) ? number_format($current_season_fetch['total_other_tea_amount'] / $current_season_fetch['total_other_tea_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($current_season_fetch['total_pkgs_current_season_ac'] > 0 || $current_season_fetch['total_kgs_current_season_ac'] > 0 ||$current_season_fetch['total_amount_current_season_ac'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">A/C CONCERNED........</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_pkgs_current_season_ac'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_kgs_current_season_ac'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_amount_current_season_ac'] > 0 && $current_season_fetch['total_kgs_current_season_ac'] > 0) ? number_format($current_season_fetch['total_amount_current_season_ac'] / $current_season_fetch['total_kgs_current_season_ac'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
           
            <?php } ; ?>
            <!-- Current Season End-->
             <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>
            <!-- Earlier Season -->
            <?php if($current_season_fetch['total_pkgs_earlier_season'] > 0 || $current_season_fetch['total_kgs_earlier_season'] > 0 ||$current_season_fetch['total_amount_earlier_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">SEASON <?php echo $previous_season; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_pkgs_earlier_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_kgs_earlier_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_amount_earlier_season'] > 0 && $current_season_fetch['total_kgs_earlier_season'] > 0) ? number_format($current_season_fetch['total_amount_earlier_season'] / $current_season_fetch['total_kgs_earlier_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($current_season_fetch['total_green_tea_pkgs_earlier_season'] > 0 || $current_season_fetch['total_green_tea_kgs_earlier_season'] > 0 ||$current_season_fetch['total_green_tea_amount_earlier_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">GREEN TEA SEASON <?php echo $previous_season ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_green_tea_pkgs_earlier_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_green_tea_kgs_earlier_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_green_tea_amount_earlier_season'] > 0 && $current_season_fetch['total_green_tea_kgs_earlier_season'] > 0) ? number_format($current_season_fetch['total_green_tea_amount_earlier_season'] / $current_season_fetch['total_green_tea_kgs_earlier_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($current_season_fetch['total_other_tea_pkgs_earlier_season'] > 0 || $current_season_fetch['total_other_tea_kgs_earlier_season'] > 0 ||$current_season_fetch['total_other_tea_amount_earlier_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">OTHERS TEA SEASON <?php echo $previous_season ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_other_tea_pkgs_earlier_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_other_tea_kgs_earlier_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_other_tea_amount_earlier_season'] > 0 && $current_season_fetch['total_other_tea_kgs_earlier_season'] > 0) ? number_format($current_season_fetch['total_other_tea_amount_earlier_season'] / $current_season_fetch['total_other_tea_kgs_earlier_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>
           
            <?php if($current_season_fetch['total_pkgs_earlier_season_ac'] > 0 || $current_season_fetch['total_kgs_earlier_season_ac'] > 0 ||$current_season_fetch['total_amount_earlier_season_ac'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">A/C CONCERNED <?php echo $previous_season; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_pkgs_earlier_season_ac'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_kgs_earlier_season_ac'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_amount_earlier_season_ac'] > 0 && $current_season_fetch['total_kgs_earlier_season_ac'] > 0) ? number_format($current_season_fetch['total_amount_earlier_season_ac'] / $current_season_fetch['total_kgs_earlier_season_ac'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>
            <!-- Earlier Season End-->

            <!-- New Season -->
            <?php if($current_season_fetch['total_pkgs_new_season'] > 0 || $current_season_fetch['total_kgs_new_season'] > 0 ||$current_season_fetch['total_amount_new_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">SEASON <?php echo $new_season; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_pkgs_new_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_kgs_new_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_amount_new_season'] > 0 && $current_season_fetch['total_kgs_new_season'] > 0) ? number_format($current_season_fetch['total_amount_new_season'] / $current_season_fetch['total_kgs_new_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($current_season_fetch['total_green_tea_pkgs_new_season'] > 0 || $current_season_fetch['total_green_tea_kgs_new_season'] > 0 ||$current_season_fetch['total_green_tea_amount_new_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">GREEN TEA SEASON <?php echo $new_season ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_green_tea_pkgs_new_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_green_tea_kgs_new_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_green_tea_amount_new_season'] > 0 && $current_season_fetch['total_green_tea_kgs_new_season'] > 0) ? number_format($current_season_fetch['total_green_tea_amount_new_season'] / $current_season_fetch['total_green_tea_kgs_new_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($current_season_fetch['total_other_tea_pkgs_new_season'] > 0 || $current_season_fetch['total_other_tea_kgs_new_season'] > 0 ||$current_season_fetch['total_other_tea_amount_new_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">OTHERS TEA SEASON <?php echo $new_season ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_other_tea_pkgs_new_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_other_tea_kgs_new_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_other_tea_amount_new_season'] > 0 && $current_season_fetch['total_other_tea_kgs_new_season'] > 0) ? number_format($current_season_fetch['total_other_tea_amount_new_season'] / $current_season_fetch['total_other_tea_kgs_new_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($current_season_fetch['total_pkgs_new_season_ac'] > 0 || $current_season_fetch['total_kgs_new_season_ac'] > 0 ||$current_season_fetch['total_amount_new_season_ac'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">A/C CONCERNED <?php echo $new_season; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_pkgs_new_season_ac'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_kgs_new_season_ac'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($current_season_fetch['total_amount_new_season_ac'] > 0 && $current_season_fetch['total_kgs_new_season_ac'] > 0) ? number_format($current_season_fetch['total_amount_new_season_ac'] / $current_season_fetch['total_kgs_new_season_ac'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>
            <!-- New Season End -->

            <?php 
                // Pkgs
                $current_season_grand_total_pkgs = $current_season_fetch['total_leaf_pkgs'] + $current_season_fetch['total_dust_pkgs'] + $current_season_fetch['total_green_tea_pkgs'] + $current_season_fetch['total_other_tea_pkgs'] + $current_season_fetch['total_pkgs_current_season_ac'];

                $earlier_season_grand_total_pkgs = $current_season_fetch['total_pkgs_earlier_season'] + $current_season_fetch['total_green_tea_pkgs_earlier_season'] + $current_season_fetch['total_other_tea_pkgs_earlier_season'] + $current_season_fetch['total_pkgs_earlier_season_ac'];

                $new_season_grand_total_pkgs = $current_season_fetch['total_pkgs_new_season'] + $current_season_fetch['total_green_tea_pkgs_new_season'] + $current_season_fetch['total_other_tea_pkgs_new_season'] + $current_season_fetch['total_pkgs_new_season_ac'];

                // kgs
                $current_season_grand_total_kgs = $current_season_fetch['total_leaf_kgs'] + $current_season_fetch['total_dust_kgs'] + $current_season_fetch['total_green_tea_kgs'] + $current_season_fetch['total_other_tea_kgs'] + $current_season_fetch['total_kgs_current_season_ac'];

                $earlier_season_grand_total_kgs = $current_season_fetch['total_kgs_earlier_season'] + $current_season_fetch['total_green_tea_kgs_earlier_season'] + $current_season_fetch['total_other_tea_kgs_earlier_season'] + $current_season_fetch['total_kgs_earlier_season_ac'];

                $new_season_grand_total_kgs = $current_season_fetch['total_kgs_new_season'] + $current_season_fetch['total_green_tea_kgs_new_season'] + $current_season_fetch['total_other_tea_kgs_new_season'] + $current_season_fetch['total_kgs_new_season_ac'];

                // Total Amount
                $current_season_grand_total_amount = $current_season_fetch['total_leaf_amount'] + $current_season_fetch['total_dust_amount'] + $current_season_fetch['total_green_tea_amount'] + $current_season_fetch['total_other_tea_amount'] + $current_season_fetch['total_amount_current_season_ac'];

                $earlier_season_grand_total_amount = $current_season_fetch['total_amount_earlier_season'] + $current_season_fetch['total_green_tea_amount_earlier_season'] + $current_season_fetch['total_other_tea_amount_earlier_season'] + $current_season_fetch['total_amount_earlier_season_ac'];

                $new_season_grand_total_amount = $current_season_fetch['total_amount_new_season'] + $current_season_fetch['total_green_tea_amount_new_season'] + $current_season_fetch['total_other_tea_amount_new_season'] + $current_season_fetch['total_amount_new_season_ac'];

                $grand_total_pkgs = $current_season_grand_total_pkgs + $earlier_season_grand_total_pkgs + $new_season_grand_total_pkgs;
                $grand_total_kgs = $current_season_grand_total_kgs + $earlier_season_grand_total_kgs + $new_season_grand_total_kgs;
                $grand_total_amount = $current_season_grand_total_amount + $earlier_season_grand_total_amount + $new_season_grand_total_amount;
                $grand_total_avg = ($grand_total_amount > 0 && $grand_total_kgs > 0) ? $grand_total_amount / $grand_total_kgs : '0.00';
            ?>

            <tr style="vertical-align: middle; font-weight:bold;">
                <td colspan="2"></td>
                <td style="text-align: center; border-top:1px solid black;">GRAND TOTAL</td>
                <td style="text-align: right; border-top:1px solid black;"><?php echo number_format($grand_total_pkgs,0,'.',','); ?></td>
                <td style="text-align: right; border-top:1px solid black;"><?php echo number_format($grand_total_kgs,1,'.',','); ?></td>
                <td style="text-align: right; border-top:1px solid black;"><?php echo number_format($grand_total_avg,2,'.',','); ?></td>
            </tr>

            <tr>
                <td colspan="6" style="height: 20px; border-bottom:1px solid;"></td>
            </tr>
             <tr>
                <td colspan="6" style="height: 5px;"></td>
            </tr>
            <tr style="vertical-align: middle;">
                <td colspan="2" rowspan="3"><span style="border-bottom:1px solid black; font-weight:bold;">BUYER'S PURCHASE</span></td>
                <td>EXPORT</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_export_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_export_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; white-space:nowrap;">
                    <?php 
                        $denominator = $current_season_fetch['total_export_pkgs'] + $current_season_fetch['total_internal_pkgs'];
                        $export_percentage = $denominator != 0 ? ($current_season_fetch['total_export_pkgs'] * 100 / $denominator) : 0;
                        echo number_format($export_percentage, 2, '.', ',') . ' %';
                    ?>
                </td>
            </tr>
              <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>
            <tr style="vertical-align: middle;">
                <td>INTERNAL</td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_internal_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($current_season_fetch['total_internal_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; white-space:nowrap;">
                    <?php 
                        $denominator = $current_season_fetch['total_export_pkgs'] + $current_season_fetch['total_internal_pkgs'];
                        $internal_percentage = $denominator != 0 ? ($current_season_fetch['total_internal_pkgs'] * 100 / $denominator) : 0;
                        echo number_format($internal_percentage, 2, '.', ',') . ' %';
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px; border-bottom: 1px solid;"></td>
            </tr>

            <tr style="vertical-align: middle; font-weight:bold;">
                <td colspan="2"></td>
                <td style="">GRAND TOTAL</td>
                <td style="text-align: right;">
                    <?php echo number_format($current_season_fetch['total_export_pkgs'] + $current_season_fetch['total_internal_pkgs'],0,'.',',') ; ?>
                </td>
                <td style="text-align: right;">
                    <?php echo number_format($current_season_fetch['total_export_kgs'] + $current_season_fetch['total_internal_kgs'],1,'.',',') ; ?>
                </td>
                <td style="text-align: right; white-space:nowrap;">
                    <?php echo number_format($export_percentage + $internal_percentage,2,'.',',').' %'; ?>
                </td>
            </tr>

            <tr>
                <td colspan="6" style="height: 30px;"></td>
            </tr>

            <tr style="vertical-align: middle; border-top:1px solid black; border-bottom:1px solid black; font-weight:bold;">
                <td colspan="2">UP TO SALE NO. <?php echo $sale_no ; ?></td>
                <td style="text-align: center;">CATEGORY</td>
                <td style="text-align: center;">PACKAGES</td>
                <td style="text-align: center;">KILOGRAM</td>
                <td style="text-align: center;">AVG/TK</td>
            </tr>
            <tr>
                <td colspan="6" style="height: 5px;"></td>
            </tr>
            <tr style="vertical-align: middle;">
                <td colspan="2"></td>
                <td style="text-align: center;">Leaf</td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_leaf_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_leaf_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_leaf_amount'] > 0 && $upto_current_sale['total_leaf_kgs'] > 0) ? number_format($upto_current_sale['total_leaf_amount'] / $upto_current_sale['total_leaf_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
             <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>
            <tr style="vertical-align: middle;">
                <td colspan="2"></td>
                <td style="text-align: center;">Dust</td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_dust_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_dust_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_dust_amount'] > 0 && $upto_current_sale['total_dust_kgs'] > 0) ? number_format($upto_current_sale['total_dust_amount'] / $upto_current_sale['total_dust_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px; border-bottom:1px solid black;"></td>
            </tr>

            <tr style="vertical-align: middle; font-weight:bold;">
                <td colspan="2"></td>
                <td style="text-align: center; border-bottom:1px solid #ccc;">TOTAL</td>
                <td style="text-align: right; border-bottom:1px solid #ccc;"><?php echo number_format($upto_current_sale['total_leaf_pkgs'] + $upto_current_sale['total_dust_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right; border-bottom:1px solid #ccc;"><?php echo number_format($upto_current_sale['total_leaf_kgs'] + $upto_current_sale['total_dust_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; border-bottom:1px solid #ccc;">
                    <?php echo $current_season_upto_avg ; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>

            <?php if($upto_current_sale['total_green_tea_pkgs'] > 0 || $upto_current_sale['total_green_tea_kgs'] > 0 ||$upto_current_sale['total_green_tea_amount'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">GREEN TEA............</td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_green_tea_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_green_tea_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_green_tea_amount'] > 0 && $upto_current_sale['total_green_tea_kgs'] > 0) ? number_format($upto_current_sale['total_green_tea_amount'] / $upto_current_sale['total_green_tea_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($upto_current_sale['total_other_tea_pkgs'] > 0 || $upto_current_sale['total_other_tea_kgs'] > 0 ||$upto_current_sale['total_other_tea_amount'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">OTHER TEAS..........</td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_other_tea_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_other_tea_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_other_tea_amount'] > 0 && $upto_current_sale['total_other_tea_kgs'] > 0) ? number_format($upto_current_sale['total_other_tea_amount'] / $upto_current_sale['total_other_tea_kgs'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>
			
			<?php if($upto_current_sale['total_pkgs_current_season_ac'] > 0 || $upto_current_sale['total_kgs_current_season_ac'] > 0 ||$upto_current_sale['total_amount_current_season_ac'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">A/C CONCERNED........</td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_pkgs_current_season_ac'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_kgs_current_season_ac'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_amount_current_season_ac'] > 0 && $upto_current_sale['total_kgs_current_season_ac'] > 0) ? number_format($upto_current_sale['total_amount_current_season_ac'] / $upto_current_sale['total_kgs_current_season_ac'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>
            <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>

            <?php if($upto_current_sale['total_pkgs_earlier_season'] > 0 || $upto_current_sale['total_kgs_earlier_season'] > 0 ||$upto_current_sale['total_amount_earlier_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">SEASON <?php echo $previous_season; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_pkgs_earlier_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_kgs_earlier_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_amount_earlier_season'] > 0 && $upto_current_sale['total_kgs_earlier_season'] > 0) ? number_format($upto_current_sale['total_amount_earlier_season'] / $upto_current_sale['total_kgs_earlier_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>
		
			
			 <?php if($upto_current_sale['total_green_tea_pkgs_earlier_season'] > 0 || $upto_current_sale['total_green_tea_kgs_earlier_season'] > 0 ||$upto_current_sale['total_green_tea_amount_earlier_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">GREEN TEA SEASON <?php echo $previous_season ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_green_tea_pkgs_earlier_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_green_tea_kgs_earlier_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_green_tea_amount_earlier_season'] > 0 && $upto_current_sale['total_green_tea_kgs_earlier_season'] > 0) ? number_format($upto_current_sale['total_green_tea_amount_earlier_season'] / $upto_current_sale['total_green_tea_kgs_earlier_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <?php if($upto_current_sale['total_other_tea_pkgs_earlier_season'] > 0 || $upto_current_sale['total_other_tea_kgs_earlier_season'] > 0 ||$upto_current_sale['total_other_tea_amount_earlier_season'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">OTHERS TEA SEASON <?php echo $previous_season ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_other_tea_pkgs_earlier_season'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_other_tea_kgs_earlier_season'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_other_tea_amount_earlier_season'] > 0 && $upto_current_sale['total_other_tea_kgs_earlier_season'] > 0) ? number_format($upto_current_sale['total_other_tea_amount_earlier_season'] / $upto_current_sale['total_other_tea_kgs_earlier_season'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>
			
			 <?php if($upto_current_sale['total_pkgs_earlier_season_ac'] > 0 || $upto_current_sale['total_kgs_earlier_season_ac'] > 0 ||$upto_current_sale['total_amount_earlier_season_ac'] > 0){ ?> 
            <tr style="vertical-align: middle;">
                <td colspan="3" style="text-align: left;">A/C CONCERNED <?php echo $previous_season; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_pkgs_earlier_season_ac'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_kgs_earlier_season_ac'],1,'.',',') ; ?></td>
                <td style="text-align: right;">
                    <?php echo ($upto_current_sale['total_amount_earlier_season_ac'] > 0 && $upto_current_sale['total_kgs_earlier_season_ac'] > 0) ? number_format($upto_current_sale['total_amount_earlier_season_ac'] / $upto_current_sale['total_kgs_earlier_season_ac'],2,'.',',') : '0.00' ; ?>
                </td>
            </tr>
            <?php } ; ?>

            <tr>
                <td colspan="6" style="height: 20px;"></td>
            </tr>

            <?php 
    // Pkgs
    $current_season_grand_total_pkgs2 = $upto_current_sale['total_leaf_pkgs'] + $upto_current_sale['total_dust_pkgs'] + $upto_current_sale['total_green_tea_pkgs'] + $upto_current_sale['total_other_tea_pkgs'] + $upto_current_sale['total_pkgs_current_season_ac'];

    $earlier_season_grand_total_pkgs2 = $upto_current_sale['total_pkgs_earlier_season'] + $upto_current_sale['total_green_tea_pkgs_earlier_season'] + $upto_current_sale['total_other_tea_pkgs_earlier_season'] + $upto_current_sale['total_pkgs_earlier_season_ac'];

    // kgs
    $current_season_grand_total_kgs2 = $upto_current_sale['total_leaf_kgs'] + $upto_current_sale['total_dust_kgs'] + $upto_current_sale['total_green_tea_kgs'] + $upto_current_sale['total_other_tea_kgs'] + $upto_current_sale['total_kgs_current_season_ac'];

    $earlier_season_grand_total_kgs2 = $upto_current_sale['total_kgs_earlier_season'] + $upto_current_sale['total_green_tea_kgs_earlier_season'] + $upto_current_sale['total_other_tea_kgs_earlier_season'] + $upto_current_sale['total_kgs_earlier_season_ac'];

    // Total Amount
    $current_season_grand_total_amount2 = $upto_current_sale['total_leaf_amount'] + $upto_current_sale['total_dust_amount'] + $upto_current_sale['total_green_tea_amount'] + $upto_current_sale['total_other_tea_amount'] + $upto_current_sale['total_amount_current_season_ac'];

    $earlier_season_grand_total_amount2 = $upto_current_sale['total_amount_earlier_season'] + $upto_current_sale['total_green_tea_amount_earlier_season'] + $upto_current_sale['total_other_tea_amount_earlier_season'] + $upto_current_sale['total_amount_earlier_season_ac'];

    $grand_total_pkgs2 = $current_season_grand_total_pkgs2 + $earlier_season_grand_total_pkgs2;
    $grand_total_kgs2 = $current_season_grand_total_kgs2 + $earlier_season_grand_total_kgs2;
    $grand_total_amount2 = $current_season_grand_total_amount2 + $earlier_season_grand_total_amount2;
    $grand_total_avg2 = ($grand_total_amount2 > 0 && $grand_total_kgs2 > 0) ? $grand_total_amount2 / $grand_total_kgs2 : '0.00';
?>

            <tr style="vertical-align: middle; font-weight:bold;">
                <td colspan="2" style=" border-top:1px solid black; font-weight:bold;">BUYER'S PURCHASE</td>
                <td style="text-align: right; border-top:1px solid black;">GRAND TOTAL</td>
                <td style="text-align: right; border-top:1px solid black;">
                    <?php echo number_format($grand_total_pkgs2,0,'.',',') ; ?>
                </td>
                <td style="text-align: right; border-top:1px solid black;">
                    <?php echo number_format($grand_total_kgs2,1,'.',',') ; ?>
                </td>
                <td style="text-align: right; border-top:1px solid black;">
                    <?php echo number_format($grand_total_avg2,2,'.',',') ; ?>
                </td>
            </tr>

            <tr>
                <td colspan="6" style="height: 20px; border-bottom:1px solid black;"></td>
            </tr>

            <tr style="vertical-align: middle;">
                 <td colspan="2"></td>
                <td>EXPORT</td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_export_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_export_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; white-space:nowrap;">
                    <?php 
                        $denominator2 = $upto_current_sale['total_export_pkgs'] + $upto_current_sale['total_internal_pkgs'];
                        $export_percentage2 = $denominator2 != 0 ? ($upto_current_sale['total_export_pkgs'] * 100 / $denominator2) : 0;
                        echo number_format($export_percentage2, 2, '.', ',') . ' %';
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>  
            <tr style="vertical-align: middle;">
                <td colspan="2"></td>
                <td>INTERNAL</td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_internal_pkgs'],0,'.',',') ; ?></td>
                <td style="text-align: right;"><?php echo number_format($upto_current_sale['total_internal_kgs'],1,'.',',') ; ?></td>
                <td style="text-align: right; white-space:nowrap;">
                    <?php 
                        $denominator2 = $upto_current_sale['total_export_pkgs'] + $upto_current_sale['total_internal_pkgs'];
                        $internal_percentage2 = $denominator2 != 0 ? ($upto_current_sale['total_internal_pkgs'] * 100 / $denominator2) : 0;
                        echo number_format($internal_percentage2, 2, '.', ',') . ' %';
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px; border-bottom:1px solid black;"></td>
            </tr>

            <tr style="vertical-align: middle; font-weight:bold;">
                <td colspan="2"></td>
                <td style="">GRAND TOTAL</td>
                <td style="text-align: right;">
                    <?php echo number_format($upto_current_sale['total_export_pkgs'] + $upto_current_sale['total_internal_pkgs'],0,'.',',') ; ?>
                </td>
                <td style="text-align: right;">
                    <?php echo number_format($upto_current_sale['total_export_kgs'] + $upto_current_sale['total_internal_kgs'],1,'.',',') ; ?>
                </td>
                <td style="text-align: right; white-space:nowrap;">
                    <?php echo number_format($export_percentage2 + $internal_percentage2,2,'.',',').' %'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="height: 10px;"></td>
            </tr>
        </table>
    </div>
</div>

<span>www.unitybrokersltd.com</span>
</div> 
<!-- container -->
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
    $('.selectable-table tbody tr').click(function() {
        $(this).toggleClass('row-highlighted');
        $(this).css('background-color', $(this).hasClass('row-highlighted') ? 'cyan' : '');
    });
</script>
</body>
</html>