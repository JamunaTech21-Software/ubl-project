<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';

// Fetch market_report
$this->db->select('*');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$market_report_query = $this->db->get('market_report_entry');
$fetch = $market_report_query->row_array(); 

// Fetch market_report_offerings
$this->db->select('*');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$market_report_offerings_query = $this->db->get('market_report_offerings_entry');
$fetch_offerings = $market_report_offerings_query->row_array(); 

// Split the season string
list($start, $end) = explode('-', $season);

// Calculate previous season and sale no
$prev_start = $start - 1;
$prev_end = $end - 1;
$previous_season = $prev_start . '-' . $prev_end;

// Fetch market average
$sale_schedule_query = $this->db->query("SELECT * FROM `sale_schedule_setup` WHERE `season_name`='".$season."' AND `auction_center`='".$auction_center."' AND `sale_no`='".$sale_no."'");
$fetch_sale_schedule = $sale_schedule_query->row_array();

$sale_schedule_query2 = $this->db->query("SELECT * FROM `sale_schedule_setup` WHERE `season_name`='".$season."' AND `auction_center`='".$auction_center."'");
$fetch_sale_schedule2 = $sale_schedule_query2->row_array();
$total_rows = $sale_schedule_query2->num_rows();

$this->db->select('
    GROUP_CONCAT(`earlier_season` SEPARATOR "@") AS all_earlier_season,
    GROUP_CONCAT(`current_season` SEPARATOR "@") AS all_current_season
');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$query = $this->db->get('market_average_entry');
$fetch_average = $query->row_array();

$earlier_data_array = explode('@', $fetch_average['all_earlier_season']);
$current_data_array = explode('@', $fetch_average['all_current_season']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>MARKET REPORT SALE NO - <?php echo $sale_no ; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<style>
	body{
		font-family: verdana !important;
		background-color: white !important;
		margin-top:10px;
	}

    .banner-print {
        width: 100%;     
        height: 100px;   
        object-fit: contain;   
    }

    b {
        font-size:12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size:11px;
    }

    .table-borderless td, 
    .table-borderless tr {
        border: none !important;
        padding: 3px 3px !important;
    }

    th {
        border: 1px solid #000;
        padding: 3px 3px;
        text-align: center;
        text-transform: uppercase;
    }

    td {
        border: 1px solid #000;
        padding: 3px 3px;
        text-align: left;
        width: 11.5%;
    }

    td:nth-child(1) {
        width: 31%;
    }

	@media print{    
		thead { display: table-header-group; }
		.no-print, .no-print * {
            display: none !important;
        }
		a[href]:after { content: none !important; }

        .banner-print {   
            height: 80px !important;    
        }

        .page_break {
            page-break-after: always; /* For older browsers */
            break-after: page;        /* For modern browsers */
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
		.col-sm-2 { width: 22%; }
		.col-sm-1 { width: 8.33333333%; }
	}

    @page{
		size: A4 portrait;
        margin-top:0px;
        margin-left:50px;
	}
</style>
</head>
<body>
<div class="container">

<div class="row no-print">
   <div class="form-group col-sm-12 text-right">
       <a class="btn-default" href="#" onclick="window.print(); return false;">
           <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
       </a>
   </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<img src="<?php echo base_url().'assets/img/ubl_banner.png'; ?>" class="banner-print" />
	</div>
</div>

<div class="row">
    <div class="col-sm-12 text-center" style="margin-top:20px;">
        <b style="font-size:14px; font-weight:800;"><u>MARKET REPORT ON SALE NO <?php echo $sale_no ; ?> HELD ON <?php echo date('M d. Y', strtotime($fetch_sale_schedule['sale_date'])); ?></u></b>
    </div>  
    
    <div class="col-sm-12" style="margin-top:10px;">
        <?php echo nl2br($fetch['introduction']) ; ?>
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <b><u>LEAF:</u></b> <?php echo nl2br($fetch['leaf']) ; ?>
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <b><u>DUST:</u></b> <?php echo nl2br($fetch['dust']) ; ?>
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <b><u>OLD SEASON:</u></b> <?php echo nl2br($fetch['old_season']) ; ?>
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <b><u>SUPPLEMENT+E-AUCTION:</u></b> <?php echo nl2br($fetch['supplements_e_auction']) ; ?>
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; border: 1px solid #000; padding: 3px;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align:center; letter-spacing:5px; font-family:courier; font-size:16px;">QUOTATIONS</th>
                </tr>
                <tr>
                    <th style="text-align:center; letter-spacing:5px; font-family:courier; font-size:15px;">BROKENS</th>
                    <th style="text-align:center; letter-spacing:5px; font-family:courier; font-size:15px;">FANNINGS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #000; padding: 3px; font-size:12px;">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">BEST</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['brokens_best_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['brokens_best_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">GOOD</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['brokens_good_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['brokens_good_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">MEDIUM</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['brokens_medium_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['brokens_medium_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">PLAIN</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['brokens_plain_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['brokens_plain_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">Northern</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['brokens_northern_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['brokens_northern_amountupto'], 2, '.', ''); ?></div>
                        </div>
                    </td>

                    <td style="border: 1px solid #000; padding: 3px; font-size:12px;">
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">BEST</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['fannings_best_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['fannings_best_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">GOOD</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['fannings_good_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['fannings_good_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">MEDIUM</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['fannings_medium_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['fannings_medium_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">PLAIN</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['fannings_plain_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['fannings_plain_amountupto'], 2, '.', ''); ?></div>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div style="width: 80px;">Northern</div>
                            <div style="width: 10px;">:</div>
                            <div><?php echo number_format($fetch['fannings_northern_amount'], 2, '.', ''); ?> - <?php echo number_format($fetch['fannings_northern_amountupto'], 2, '.', ''); ?></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-sm-6" style="margin-top:10px;">
        <div class="text-center" style="border:1px solid; padding:2px;">
            <b>COMARATIVE: SALE AVERAGE LEGEND</b>

            <div style="display: flex; justify-content: center; align-items: center;">
                <canvas id="stackedChart" width="300" height="180"></canvas>
            </div>
        </div>
    </div>

    <div class="col-sm-6" style="margin-top:10px;">
        <div class="text-center" style="border:1px solid; padding:2px;">
            <b>PURCHASE PERCENTAGE SALE - <?php echo $sale_no ; ?></b>

            <div style="display: flex; justify-content: center; align-items: center; gap: 20px;">
                <div>
                    <canvas id="myPieChart" width="150" height="160"></canvas>
                </div>
                
                <div>
                    <canvas id="myPieChart2" width="150" height="160"></canvas>
                </div>
            </div>

            <div style="display: flex; justify-content: center; align-items: center; gap: 200px;">
                <b>LEAF</b>
                <b>DUST</b>
            </div>
        </div>
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <b><u>SALE NO:</u></b> <?php echo nl2br($fetch['upcomeing_sale_offering_info']) ; ?>
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <b><u>OUR CATALOGUE:</u></b> Sale No <?php echo $sale_no ; ?> Avg. Tk. <?php echo number_format($fetch['avg'], 2, '.', ''); ?>, Sold <?php echo number_format($fetch['sold_percentage'], 2, '.', ''); ?> %
    </div>

    <div class="col-sm-12" style="margin-top:10px;">
        <b><u>COMMENTS:</u></b> <?php echo nl2br($fetch['comments']) ; ?>
    </div>
</div>

<div class="page_break"></div>

<div class="col-sm-12 text-center" style="margin-top:20px;">
    <div style="border: 1px solid black; padding: 5px; font-weight: bold; width: 250px; margin: 0 auto; font-size:12px;">
        MARKET REPORT <br>
        SALE NO - <?php echo $sale_no; ?><br>
        HELD NO - <?php echo date('d-m-Y', strtotime($fetch_sale_schedule['sale_date'])); ?><br>
        SEASON - <?php echo $season; ?><br>
    </div>

    <div style="border: 1px solid black; padding: 5px; font-weight: bold; margin: 10px auto 0 auto; width: 80px; font-size:12px;">
        Leaf
    </div>
</div>

<div class="col-sm-12" style="margin-top:10px;">
    <table class="selectable-table">
        <thead>
            <tr>
                <th>BROKERS NAME</th>
                <th>OFFERINGS</th>
                <th>PACKETEER</th>
                <th>INTERNAL</th>
                <th>UNSOLD</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $tota_leaf_sold = 0;
                $tota_leaf_offerings = 0;
                $tota_leaf_packeteer = 0;
                $tota_leaf_internal = 0;
                $tota_leaf_unsold = 0;
                
                $this->db->select([
                    'market_report_offerings_entry.brokers_id',
                    'market_report_offerings_entry.brokers',
                    'market_report_offerings_entry.leaf_offerings',
                    'market_report_offerings_entry.leaf_packeteer',
                    'market_report_offerings_entry.leaf_internal',
                    'sale_order_setup.order_serial'
                ]);
                $this->db->from('market_report_offerings_entry');

                // Properly interpolate the variables
                $this->db->join(
                    'sale_order_setup',
                    "market_report_offerings_entry.brokers_id = sale_order_setup.brokers_id 
                    AND sale_order_setup.season_name = " . $this->db->escape($season) . "
                    AND sale_order_setup.auction_center = " . $this->db->escape($auction_center) . "
                    AND sale_order_setup.sale_no = " . $this->db->escape($sale_no),
                    'left'
                );

                // Main WHERE clause
                $this->db->where([
                    'market_report_offerings_entry.season' => $season,
                    'market_report_offerings_entry.auction_center' => $auction_center,
                    'market_report_offerings_entry.sale_no' => $sale_no
                ]);

                // Ordering
                $this->db->order_by('sale_order_setup.order_serial', 'ASC');

                // Final execution
                $query = $this->db->get();
                $fetch_list = $query->result_array();
                foreach ($fetch_list as $fetch) {
            ?>
            <tr>
                <td <?php echo ($define_brokers_id == $fetch['brokers_id']) ? 'style="font-weight:bold;"' : '' ;?>><?php echo sprintf("%2d", $fetch['order_serial']) . '. ' . $fetch['brokers'] ; ?></td>
                <td class="text-center"><?php echo $fetch['leaf_offerings'] ; ?></td>
                <td class="text-center"><?php echo $fetch['leaf_packeteer'] ; ?></td>
                <td class="text-center"><?php echo $fetch['leaf_internal'] ; ?></td>
                <td class="text-center">
                    <?php 
                        $unsold_amount = $fetch['leaf_offerings'] - ($fetch['leaf_packeteer'] + $fetch['leaf_internal']);
                        echo $unsold_amount; 
                    ?>
                </td>
                <td class="text-center"><?php echo $fetch['leaf_offerings'] ? round($unsold_amount * 100 / $fetch['leaf_offerings']) . '%' : '0%'; ?></td>
            </tr>
            <?php 
                $tota_leaf_offerings += $fetch['leaf_offerings'];
                $tota_leaf_packeteer += $fetch['leaf_packeteer'];
                $tota_leaf_internal += $fetch['leaf_internal'];
                $tota_leaf_unsold += $unsold_amount;
                $tota_leaf_sold = $tota_leaf_offerings - $tota_leaf_unsold;
            } ?>

            <tr style="border:0px;">
                <td colspan="6" style="height: 20px;"></td>
            </tr>

            <tr>
                <th>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="text-align: left; flex: 1; white-space:nowrap;">Leaf Sold:</span>
                        <span style="text-align: center; flex: 1;"><?php echo $tota_leaf_sold ; ?> - <?php echo round($tota_leaf_sold * 100 / $tota_leaf_offerings).'%' ; ?></span>
                    </div>
                </th>
                <th class="text-center"><?php echo $tota_leaf_offerings ; ?></th>
                <th class="text-center"><?php echo $tota_leaf_packeteer ; ?></th>
                <th class="text-center"><?php echo $tota_leaf_internal ; ?></th>
                <th class="text-center"><?php echo $tota_leaf_unsold ; ?></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th class="text-center"><?php echo round($tota_leaf_packeteer * 100 / $tota_leaf_offerings).'%' ; ?></th>
                <th class="text-center"><?php echo round($tota_leaf_internal * 100 / $tota_leaf_offerings).'%' ; ?></th>
                <th class="text-center"><?php echo round($tota_leaf_unsold * 100 / $tota_leaf_offerings).'%' ; ?></th>
                <th class="text-center"></th>
            </tr>
        </tbody>
    </table>
</div>

<div class="col-sm-12 text-center">
    <div style="border: 1px solid black; padding: 5px; font-weight: bold; margin: 10px auto 0 auto; width: 80px; font-size:12px;">
        Dust
    </div>
</div>

<div class="col-sm-12" style="margin-top:10px;">
    <table class="selectable-table">
        <tbody>
            <?php 
                $tota_dust_sold = 0;
                $tota_dust_offerings = 0;
                $tota_dust_packeteer = 0;
                $tota_dust_internal = 0;
                $tota_dust_unsold = 0;

                $this->db->select([
                    'market_report_offerings_entry.brokers_id',
                    'market_report_offerings_entry.brokers',
                    'market_report_offerings_entry.dust_offerings',
                    'market_report_offerings_entry.dust_packeteer',
                    'market_report_offerings_entry.dust_internal',
                    'sale_order_setup.order_serial'
                ]);
                $this->db->from('market_report_offerings_entry');

                // Properly interpolate the variables
                $this->db->join(
                    'sale_order_setup',
                    "market_report_offerings_entry.brokers_id = sale_order_setup.brokers_id 
                    AND sale_order_setup.season_name = " . $this->db->escape($season) . "
                    AND sale_order_setup.auction_center = " . $this->db->escape($auction_center) . "
                    AND sale_order_setup.sale_no = " . $this->db->escape($sale_no),
                    'left'
                );

                // Main WHERE clause
                $this->db->where([
                    'market_report_offerings_entry.season' => $season,
                    'market_report_offerings_entry.auction_center' => $auction_center,
                    'market_report_offerings_entry.sale_no' => $sale_no
                ]);

                // Ordering
                $this->db->order_by('sale_order_setup.order_serial', 'ASC');

                // Final execution
                $query = $this->db->get();
                $fetch_list = $query->result_array();
                foreach ($fetch_list as $fetch) {
            ?>
            <tr>
                <td <?php echo ($define_brokers_id == $fetch['brokers_id']) ? 'style="font-weight:bold;"' : '' ;?>><?php echo sprintf("%2d", $fetch['order_serial']) . '. ' . $fetch['brokers'] ; ?></td>
                <td class="text-center"><?php echo $fetch['dust_offerings'] ; ?></td>
                <td class="text-center"><?php echo $fetch['dust_packeteer'] ; ?></td>
                <td class="text-center"><?php echo $fetch['dust_internal'] ; ?></td>
                <td class="text-center">
                    <?php 
                        $unsold_amount = $fetch['dust_offerings'] - ($fetch['dust_packeteer'] + $fetch['dust_internal']);
                        echo $unsold_amount; 
                    ?>
                </td>
                <td class="text-center"><?php echo $fetch['dust_offerings'] ? round($unsold_amount * 100 / $fetch['dust_offerings']) . '%' : '0%'; ?></td>
            </tr>
            <?php 
                $tota_dust_offerings += $fetch['dust_offerings'];
                $tota_dust_packeteer += $fetch['dust_packeteer'];
                $tota_dust_internal += $fetch['dust_internal'];
                $tota_dust_unsold += $unsold_amount;
                $tota_dust_sold = $tota_dust_offerings - $tota_dust_unsold;
            } ?>

            <tr style="border:0px;">
                <td colspan="6" style="height: 20px;"></td>
            </tr>

            <tr>
                <th>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="text-align: left; flex: 1; white-space:nowrap;">Dust Sold:</span>
                        <span style="text-align: center; flex: 1;"><?php echo $tota_dust_sold ; ?> - <?php echo round($tota_dust_sold * 100 / $tota_dust_offerings).'%' ; ?></span>
                    </div>
                </th>
                <th class="text-center"><?php echo $tota_dust_offerings ; ?></th>
                <th class="text-center"><?php echo $tota_dust_packeteer ; ?></th>
                <th class="text-center"><?php echo $tota_dust_internal ; ?></th>
                <th class="text-center"><?php echo $tota_dust_unsold ; ?></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th class="text-center"><?php echo round($tota_dust_packeteer * 100 / $tota_dust_offerings).'%' ; ?></th>
                <th class="text-center"><?php echo round($tota_dust_internal * 100 / $tota_dust_offerings).'%' ; ?></th>
                <th class="text-center"><?php echo round($tota_dust_unsold * 100 / $tota_dust_offerings).'%' ; ?></th>
                <th class="text-center"></th>
            </tr>
        </tbody>
    </table>
</div>

<div class="col-sm-12 text-center">
    <div style="border: 1px solid black; padding: 5px; font-weight: bold; margin: 10px auto 0 auto; width: 120px; font-size:12px;">
        Supplement
    </div>
</div>

<div class="col-sm-12" style="margin-top:10px;">
    <table class="selectable-table">
        <tbody>
            <?php 
                $tota_supplement_sold = 0;
                $tota_supplement_offerings = 0;
                $tota_supplement_packeteer = 0;
                $tota_supplement_internal = 0;
                $tota_supplement_unsold = 0;

                $this->db->select([
                    'market_report_offerings_entry.brokers_id',
                    'market_report_offerings_entry.brokers',
                    'market_report_offerings_entry.supplement_offerings',
                    'market_report_offerings_entry.supplement_packeteer',
                    'market_report_offerings_entry.supplement_internal',
                    'sale_order_setup.order_serial'
                ]);
                $this->db->from('market_report_offerings_entry');

                // Properly interpolate the variables
                $this->db->join(
                    'sale_order_setup',
                    "market_report_offerings_entry.brokers_id = sale_order_setup.brokers_id 
                    AND sale_order_setup.season_name = " . $this->db->escape($season) . "
                    AND sale_order_setup.auction_center = " . $this->db->escape($auction_center) . "
                    AND sale_order_setup.sale_no = " . $this->db->escape($sale_no),
                    'left'
                );

                // Main WHERE clause
                $this->db->where([
                    'market_report_offerings_entry.season' => $season,
                    'market_report_offerings_entry.auction_center' => $auction_center,
                    'market_report_offerings_entry.sale_no' => $sale_no
                ]);

                // Ordering
                $this->db->order_by('sale_order_setup.order_serial', 'ASC');

                // Final execution
                $query = $this->db->get();
                $fetch_list = $query->result_array();
                foreach ($fetch_list as $fetch) {
            ?>
            <tr>
                <td <?php echo ($define_brokers_id == $fetch['brokers_id']) ? 'style="font-weight:bold;"' : '' ;?>><?php echo sprintf("%2d", $fetch['order_serial']) . '. ' . $fetch['brokers'] ; ?></td>
                <td class="text-center"><?php echo $fetch['supplement_offerings'] ; ?></td>
                <td class="text-center"><?php echo $fetch['supplement_packeteer'] ; ?></td>
                <td class="text-center"><?php echo $fetch['supplement_internal'] ; ?></td>
                <td class="text-center">
                    <?php 
                        $unsold_amount = $fetch['supplement_offerings'] - ($fetch['supplement_packeteer'] + $fetch['supplement_internal']);
                        echo $unsold_amount; 
                    ?>
                </td>
                <td class="text-center"><?php echo $fetch['supplement_offerings'] ? round($unsold_amount * 100 / $fetch['supplement_offerings']) . '%' : '0%'; ?></td>
            </tr>
            <?php 
                $tota_supplement_offerings += $fetch['supplement_offerings'];
                $tota_supplement_packeteer += $fetch['supplement_packeteer'];
                $tota_supplement_internal += $fetch['supplement_internal'];
                $tota_supplement_unsold += $unsold_amount;
                $tota_supplement_sold = $tota_supplement_offerings - $tota_supplement_unsold;
            } ?>

            <tr style="border:0px;">
                <td colspan="6" style="height: 20px;"></td>
            </tr>

            <tr>
                <th>
                    <div style="display: flex; justify-content: space-between; white-space:nowrap;">
                        <span style="text-align: left; flex: 1;">Supplement Sold:</span>
                        <span style="text-align: center; flex: 1;"><?php echo $tota_supplement_sold ; ?> - <?php echo round($tota_supplement_sold * 100 / $tota_supplement_offerings).'%' ; ?></span>
                    </div>
                </th>
                <th class="text-center"><?php echo $tota_supplement_offerings ; ?></th>
                <th class="text-center"><?php echo $tota_supplement_packeteer ; ?></th>
                <th class="text-center"><?php echo $tota_supplement_internal ; ?></th>
                <th class="text-center"><?php echo $tota_supplement_unsold ; ?></th>
                <th class="text-center"></th>
            </tr>
            <tr>
                <th colspan="2"></th>
                <th class="text-center"><?php echo round($tota_supplement_packeteer * 100 / $tota_supplement_offerings).'%' ; ?></th>
                <th class="text-center"><?php echo round($tota_supplement_internal * 100 / $tota_supplement_offerings).'%' ; ?></th>
                <th class="text-center"><?php echo round($tota_supplement_unsold * 100 / $tota_supplement_offerings).'%' ; ?></th>
                <th class="text-center"></th>
            </tr>
        </tbody>
    </table>
</div>

<?php 
    $net_offerings = $tota_leaf_offerings + $tota_dust_offerings + $tota_supplement_offerings;
    $net_sold = ($tota_leaf_offerings - $tota_leaf_unsold) + ($tota_dust_offerings - $tota_dust_unsold) + ($tota_supplement_offerings - $tota_supplement_unsold);
    $net_unsold = $tota_leaf_unsold + $tota_dust_unsold + $tota_supplement_unsold;

    $net_offerings_percentage = '100%';
    $net_sold_percentage = round($net_sold * 100 / $net_offerings).'%';
    $net_unsold_percentage = round($net_unsold * 100 / $net_offerings).'%';
?>
<div class="col-sm-12" style="margin-top:10px;">
    <table class="table table-borderless" style="width: 60%; font-weight:bold;">
        <tbody>
            <tr>
                <td>TOTAL OFFERINGS</td>
                <td><?php echo $net_offerings ; ?></td>
                <td><?php echo $net_offerings_percentage ; ?></td>
            </tr>
            <tr>
                <td>TOTAL SOLD</td>
                <td><?php echo $net_sold ; ?></td>
                <td><?php echo $net_sold_percentage ; ?></td>
            </tr>
            <tr>
                <td>TOTAL UNSOLD</td>
                <td><?php echo $net_unsold ; ?></td>
                <td><?php echo $net_unsold_percentage ; ?></td>
            </tr>
        </tbody>
    </table>
</div>
</div> 
<!-- container -->
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/chart/chart.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/chart/chartjs-plugin-datalabels.min.js'; ?>"></script>
<script>
    $(document).ready(function() {

        var dataValues1 = [
            <?php echo ($fetch_offerings['leaf_offerings'] - $fetch_offerings['leaf_packeteer'] - $fetch_offerings['leaf_internal']); ?>,
            <?php echo $fetch_offerings['leaf_packeteer']; ?>,
            <?php echo $fetch_offerings['leaf_internal']; ?>
        ];

        var total1 = dataValues1.reduce((a, b) => a + b, 0);

        var ctx1 = document.getElementById('myPieChart').getContext('2d');

        var myPieChart1 = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['UNSOLD', 'EX/PKTR', 'INTL'],
                datasets: [{
                    data: dataValues1,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    offset: [10, 10, 10]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 30
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label;
                                const value = tooltipItem.raw;
                                const percentage = ((value / total1) * 100).toFixed(1) + '%';
                                return label + ': ' + percentage;
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'end',
                        formatter: function(value, context) {
                            const label = context.chart.data.labels[context.dataIndex];
                            const percentage = ((value / total1) * 100).toFixed(1);
                            return label + '\n' + percentage + '%';
                        },
                        color: '#000',
                        font: {
                            size: 8
                        },
                        clamp: true
                    }
                },
                cutout: '0%', 
                radius: '75%'
            },
            plugins: [ChartDataLabels]
        });


        var dataValues2 = [
            <?php echo ($fetch_offerings['dust_offerings'] - $fetch_offerings['dust_packeteer'] - $fetch_offerings['dust_internal']); ?>,
            <?php echo $fetch_offerings['dust_packeteer']; ?>,
            <?php echo $fetch_offerings['dust_internal']; ?>
        ];

        var total2 = dataValues2.reduce((a, b) => a + b, 0);

        var ctx2 = document.getElementById('myPieChart2').getContext('2d');

        var myPieChart2 = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['UNSOLD', 'EX/PKTR', 'INTL'],
                datasets: [{
                    data: dataValues2,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                    offset: [10, 10, 10]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: 30
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label;
                                const value = tooltipItem.raw;
                                const percentage = ((value / total2) * 100).toFixed(1) + '%';
                                return label + ': ' + percentage;
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'end',
                        formatter: function(value, context) {
                            const label = context.chart.data.labels[context.dataIndex];
                            const percentage = ((value / total2) * 100).toFixed(1);
                            return label + '\n' + percentage + '%';
                        },
                        color: '#000',
                        font: {
                            size: 8
                        },
                        clamp: true
                    }
                },
                cutout: '0%',
                radius: '75%'
            },
            plugins: [ChartDataLabels]
        });

        var ctx3 = document.getElementById('stackedChart').getContext('2d');

        var chart = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: Array.from({length: <?php echo $total_rows; ?>}, (_, i) => (i + 1).toString()),
                datasets: [
                    {
                        label: '<?php echo $season; ?>',
                        type: 'line',
                        data: [
                            <?php
                                $filtered_current_data_array = array_filter($current_data_array, function($v) {
                                    return $v != 0;
                                });
                                echo implode(',', $filtered_current_data_array);
                            ?>
                        ],
                        borderColor: 'red',
                        borderWidth: 1,
                        tension: 0.4,
                        fill: false,
                        yAxisID: 'y',
                        pointStyle: 'star',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        spanGaps: true
                    },
                    {
                        label: '<?php echo $previous_season; ?>',
                        data: [
                            <?php
                                $filtered_earlier_data_array = array_filter($earlier_data_array, function($v) {
                                    return $v != 0;
                                });
                                echo implode(',', $filtered_earlier_data_array);
                            ?>
                        ],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        stack: 'revenue',
                        barThickness: 1
                    },
                    {
                        label: '<?php echo $previous_season; ?>',
                        type: 'line',
                        data: [
                            <?php
                                $filtered_earlier_data_array = array_filter($earlier_data_array, function($v) {
                                    return $v != 0;
                                });
                                echo implode(',', $filtered_earlier_data_array);
                            ?>
                        ],
                        borderColor: 'blue',
                        borderWidth: 1,
                        tension: 0.4,
                        fill: false,
                        yAxisID: 'y',
                        borderDash: [6, 4],
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        spanGaps: true
                    }
                ]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 15
                    }
                },
                plugins: {
                    legend: {
                       display: false,
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 8
                            },
                            callback: function(value, index) {
                                return index % 3 === 1 ? value : '';
                            }
                        }
                    },
                    y: {
                        stacked: false, 
                        min: 100,
                        max: 280,
                        ticks: {
                            stepSize: 10,
                            autoSkip: false,
                            font: {
                                size: 8
                            }
                        },
                        position: 'left',
                    }
                }
            },
            plugins: [
                {
                    id: 'verticalTitle',
                    beforeDraw: function(chart) {
                        const ctx = chart.ctx;
                        ctx.save();
                        ctx.font = '8px Arial';
                        ctx.fillStyle = 'black';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        const chartArea = chart.chartArea;
                        const startY = chartArea.top + 5;
                        const centerX = chartArea.left - 35;
                        const lineHeight = 7;

                        const labelText = 'T A K A  P R I C E S';
                        const letters = labelText.split('');

                        let drawIndex = 0;
                        for (let i = 0; i < letters.length; i++) {
                            if (letters[i] === ' ') {
                                drawIndex++;
                            } else {
                                ctx.fillText(letters[i], centerX, startY + lineHeight * drawIndex);
                                drawIndex++;
                            }
                        }
                        ctx.restore();
                    }
                },
                {
                    id: 'innerTitle',
                    beforeDraw: function(chart) {
                        const ctx = chart.ctx;
                        ctx.save();

                        ctx.font = '8px Arial';
                        ctx.fillStyle = 'black';
                        ctx.textAlign = 'center';

                        const x = (chart.chartArea.left + chart.chartArea.right) / 2;
                        const yStart = chart.chartArea.top + 10;

                        const lines = [
                            'ASTERISK CURVE: SALE WISE <?php echo $season; ?>',
                            'DOTTED CURVE CUMULATIVE: SALE WISE <?php echo $previous_season; ?>',
							'BAR : SALE WISE <?php echo $previous_season; ?>'
                        ];

                        lines.forEach((line, i) => {
                            ctx.fillText(line, x, yStart + i * 12);
                        });

                        ctx.restore();
                    }
                }
            ]
        });
    });

    $('.selectable-table tbody tr').click(function() {
        $(this).toggleClass('row-highlighted');
        $(this).css('background-color', $(this).hasClass('row-highlighted') ? 'cyan' : '');
    });
</script>
</body>
</html>