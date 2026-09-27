<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$grade_id = (!empty($this->input->get('grade_id', TRUE))) ? $this->input->get('grade_id', TRUE) : '';
$w_house_id = (!empty($this->input->get('w_house_id', TRUE))) ? $this->input->get('w_house_id', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : ''; // New buyer filter

// Get data for dropdowns
$season_data = $this->db->query("SELECT DISTINCT season_name FROM sale_schedule_setup ORDER BY season_name DESC")->result_array();
$current_season_data = !empty($season_data) ? (object)$season_data[0] : (object)['season_name' => ''];
$default_ac = 'Chattogram';
$garden_data = $this->db->query("SELECT id, garden_name FROM garden_and_company_setup ORDER BY garden_name")->result_array();
$grade_data = $this->db->query("SELECT id, grade_name, catalogue_name FROM tea_grade_setup ORDER BY grade_name")->result_array();
$wh_data = $this->db->query("SELECT id, warehouse_name, short_key_word FROM warehouse_setup ORDER BY warehouse_name")->result_array();
$buyer_data = $this->db->query("SELECT id, buyer_name FROM buyer_setup ORDER BY buyer_name")->result_array(); // Fetch buyer data

$current_date_time = date("Y-m-d h:i:sa");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Optimized Sales Report</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
    <link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
    <style>
        body {
            font-family: verdana !important;
            background-color: white !important;
            margin-top: 10px;
        }
        .table {
            font-family: verdana;
            width: 100% !important;
        }
        .filter-panel {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .table-responsive {
            overflow-x: auto;
        }
        #sales-table th, #sales-table td {
            font-size: 11px;
            padding: 5px;
            border: 1px solid #ddd;
        }
        #sales-table th {
            background-color: #f2f2f2 !important;
            text-align: center;
            font-weight: bold;
            white-space: nowrap;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .btn-print {
            background: #337ab7;
            color: white;
            padding: 5px 15px;
            border-radius: 4px;
            text-decoration: none;
            margin-bottom: 15px;
            display: inline-block;
        }
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .sold-status { color: green; font-weight: bold; }
        .wd-status { color: red; font-weight: bold; }
        .out-status { color: #006994; font-weight: bold; }

        /* Add spacing between form groups */
        .filter-row {
            margin-bottom: 15px;
        }
        .filter-row .form-group {
            margin-bottom: 0;
        }
        
        /* PRINT STYLES */
        @media print {
            @page {
                size: landscape;
                margin: 0.5cm;
            }
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .container-fluid {
                width: 100%;
                padding: 0;
            }
            .report-title {
                display: block;
                width: 100%;
                text-align: center !important;
            }
            .report-title h3, .report-title h4, .report-title p {
                text-align: center !important;
                margin: 5px 0 !important;
            }
            #sales-table {
                width: 100% !important;
                border-collapse: collapse;
            }
            #sales-table th, #sales-table td {
                border: 1px solid #000 !important;
                font-size: 10px !important;
            }
            .filter-summary {
                background: transparent !important;
                border-left: none !important;
                text-align: center !important;
            }
        }
    </style>
</head>
<body>
<div id="printableArea" class="container-fluid">

    <div class="panel panel-default no-print">
        <div class="panel-heading">
            <h4 class="panel-title">Sales Report Search Filters</h4>
        </div>
        <div class="panel-body filter-panel">
            <form method="get" action="" class="form-horizontal" id="searchForm">
                <!-- ROW 1 -->
                <div class="row filter-row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="season">Season</label>
                            <select class="form-control form-control-sm" id="season" name="season">
                                <option value="">-- Select Season --</option>
                                <?php 
                                foreach($season_data as $season_list){
                                    $selected = ($season_list['season_name'] == $season) ? 'selected' : '';
                                    if(empty($season) && $season_list['season_name'] == $current_season_data->season_name){
                                        $selected = 'selected';
                                    }
                                    print "<option value=\"$season_list[season_name]\" $selected>$season_list[season_name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="auction_center">Auction Center</label>
                            <select class="form-control form-control-sm" name="auction_center" id="auction_center">
                                <option value="">Select</option>
                                <option value="All Center" <?php echo ($auction_center == 'All Center') ? 'selected' : ''; ?>>All Center</option>
                                <option value="Sreemangal" <?php echo ($auction_center == 'Sreemangal' || ($auction_center == '' && $default_ac == 'Sreemangal')) ? 'selected' : ''; ?>>Sreemangal</option>
                                <option value="Chattogram" <?php echo ($auction_center == 'Chattogram' || ($auction_center == '' && $default_ac == 'Chattogram')) ? 'selected' : ''; ?>>Chattogram</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="sale_from">Sale No From</label>
                            <input type="text" class="form-control form-control-sm" id="sale_from" name="sale_from" value="<?php echo $sale_from; ?>" placeholder="Sale No From">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="sale_to">Sale No To</label>
                            <input type="text" class="form-control form-control-sm" id="sale_to" name="sale_to" value="<?php echo $sale_to; ?>" placeholder="Sale No To">
                        </div>
                    </div>
                </div>
               
                <!-- ROW 2 -->
                <div class="row filter-row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="garden_id">Garden Name</label>
                            <select class="form-control form-control-sm" id="garden_id" name="garden_id">
                                <option value="">All</option>
                                <?php 
                                foreach($garden_data as $garden_data_list){
                                    $selected = ($garden_id == $garden_data_list['id']) ? 'selected' : '';
                                    print "<option value=\"$garden_data_list[id]\" $selected>$garden_data_list[garden_name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="grade_id">Grade</label>
                            <select class="form-control form-control-sm" id="grade_id" name="grade_id">
                                <option value="">Select Grade</option>
                                <?php 
                                foreach($grade_data as $grade_data_list){
                                    $selected = ($grade_id == $grade_data_list['id']) ? 'selected' : '';
                                    print "<option value=\"$grade_data_list[id]\" $selected>$grade_data_list[grade_name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="w_house_id">W.House Name</label>
                            <select class="form-control form-control-sm" id="w_house_id" name="w_house_id">
                                <option value="">Select Warehouse</option>
                                <?php 
                                foreach($wh_data as $wh_data_list){
                                    $selected = ($w_house_id == $wh_data_list['id']) ? 'selected' : '';
                                    print "<option value=\"$wh_data_list[id]\" $selected>$wh_data_list[warehouse_name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="buyer_id">Buyer Name</label>
                            <select class="form-control form-control-sm" id="buyer_id" name="buyer_id" style="text-transform:uppercase;">
                                <option value="">Choose</option>
                                <?php 
                                foreach($buyer_data as $buyer_data_list){
                                    $selected = ($buyer_id == $buyer_data_list['id']) ? 'selected' : '';
                                    print "<option value=\"$buyer_data_list[id]\" $selected>$buyer_data_list[buyer_name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- ROW 3 - Buttons -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                <a href="<?php echo site_url('MY_Controller/optimised_sales_report'); ?>" class="btn btn-default btn-sm">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row no-print">
        <div class="col-sm-12 text-right" style="margin-bottom: 15px;">
            <button class="btn-print" onClick="window.print()">
                <span class="glyphicon glyphicon-print"></span> Print Report
            </button>
        </div>
    </div>

    <?php
    if(!empty($season) && !empty($auction_center) && !empty($sale_from) && !empty($sale_to)){
        // Building DB query logic (same as your original)
        $ac_clause = ($auction_center == 'All Center') ? "invoice_entry.auction_center != ''" : "invoice_entry.auction_center = '$auction_center'";
        
        $this->db->select("invoice_entry.sale_no, invoice_entry.lot_no, invoice_entry.w_inv_no, invoice_entry.chest_number, invoice_entry.net_kgs, invoice_entry.valuation, invoice_entry.additional_grade, IFNULL(sale_entry.sold_price, 0) AS sold_price, garden_and_company_setup.garden_name, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, warehouse_setup.warehouse_name, warehouse_setup.short_key_word, invoice_entry.season, invoice_entry.auction_center, sale_entry.buyer1, sale_entry.buyer2, sale_entry.buyer3, sale_entry.buyer_type1, sale_entry.buyer_type2, sale_entry.buyer_type3, sale_entry.buyer1_bag, sale_entry.buyer2_bag, sale_entry.buyer3_bag, b1.short_key_code AS buyer1_code, b2.short_key_code AS buyer2_code, b3.short_key_code AS buyer3_code, invoice_entry.remarks, invoice_entry.old_sale_no");
        $this->db->from('invoice_entry');
        $this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
        $this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
        $this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
        $this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
        $this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
        $this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
        $this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');
        $this->db->where('invoice_entry.catalogue_status', 'prepared');
        $this->db->where('invoice_entry.season', $season);
        $this->db->where($ac_clause);
        $this->db->where("invoice_entry.sale_no BETWEEN '$sale_from' AND '$sale_to'");
        if(!empty($garden_id)) $this->db->where('invoice_entry.garden_id', $garden_id);
        if(!empty($grade_id)) $this->db->where('invoice_entry.grade_id', $grade_id);
        if(!empty($w_house_id)) $this->db->where('invoice_entry.w_house_id', $w_house_id);
        
        // New buyer filter logic
        if(!empty($buyer_id)) {
            $this->db->group_start();
            $this->db->where('sale_entry.buyer1', $buyer_id);
            $this->db->or_where('sale_entry.buyer2', $buyer_id);
            $this->db->or_where('sale_entry.buyer3', $buyer_id);
            $this->db->group_end();
        }
        
        $results = $this->db->order_by('invoice_entry.sale_no ASC, invoice_entry.lot_no ASC')->get()->result_array();

        // Calculate Totals
        $total_chest = array_sum(array_column($results, 'chest_number'));
        $total_kgs = array_sum(array_column($results, 'net_kgs'));
    ?>
    
    <div class="row">
        <div class="col-sm-12 report-title">
            <h3><?php echo $website_title; ?></h3>
            <p><?php echo $owner_short_address; ?></p>
            <h4>OPTIMIZED SALES REPORT</h4>
            <p><strong>Center: <?php echo $auction_center; ?></strong> | <strong>Season: <?php echo $season; ?></strong> | <strong>Sale: <?php echo $sale_from; ?> - <?php echo $sale_to; ?></strong></p>
            <p style="font-size: 10px;">Print Date: <?php echo date('d-m-Y h:i A'); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="sales-table">
                    <thead>
                        <tr>
                            <th>Sale No</th>
                            <th>Garden</th>
                            <th>Lot</th>
                            <th>INV</th>
                            <th>Grade</th>
                            <th class="text-right">Pkgs</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">KGS</th>
                            <th>Buyer-1</th>
                            <th>Buyer-2</th>
                            <th>Buyer-3</th>
                            <th>Status</th>
                            <th>W/H</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $row): ?>
                        <tr>
                            <td class="text-center"><?php echo $row['sale_no']; ?></td>
                            <td style="text-transform:uppercase;"><?php echo $row['garden_name']; ?></td>
                            <td class="text-center"><?php echo $row['lot_no']; ?></td>
                            <td class="text-center"><?php echo $row['w_inv_no']; ?></td>
                            <td style="text-transform:uppercase;"><?php echo $row['grade_name']; ?></td>
                            <td class="text-right"><?php echo number_format($row['chest_number']); ?></td>
                            <td class="text-right"><?php echo ($row['sold_price'] > 0) ? number_format($row['sold_price'], 2) : '-'; ?></td>
                            <td class="text-right"><?php echo number_format($row['net_kgs'], 2); ?></td>
                            <td><?php echo $row['buyer1_code'] ? $row['buyer1_code'] . "({$row['buyer1_bag']})" : ''; ?></td>
                            <td><?php echo $row['buyer2_code'] ? $row['buyer2_code'] . "({$row['buyer2_bag']})" : ''; ?></td>
                            <td><?php echo $row['buyer3_code'] ? $row['buyer3_code'] . "({$row['buyer3_bag']})" : ''; ?></td>
                            <td class="text-center">
                                <?php 
                                    if($row['sold_price'] > 0) echo "Sold";
                                    else echo ($row['remarks'] == 'W/D') ? "W/D" : "Out";
                                ?>
                            </td>
                            <td class="text-center"><?php echo $row['short_key_word']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f9f9f9; font-weight: bold;">
                            <td colspan="5" class="text-right">GRAND TOTAL:</td>
                            <td class="text-right"><?php echo number_format($total_chest); ?></td>
                            <td class="text-right">-</td>
                            <td class="text-right"><?php echo number_format($total_kgs, 2); ?></td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>

</div>

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
$(function(){
    $("#garden_id").chosen();
});
</script>
</body>
</html>