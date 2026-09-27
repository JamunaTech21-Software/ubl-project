<!DOCTYPE html>
<html lang="en">
<head>
<title>Market Report Entry</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-glyphicons.css'; ?>">

<script src="<?php echo base_url().'assets/js/multi_filter_dt/jquery-3.3.1.js'; ?>"></script>		
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/bootstrap.min.js'; ?>"></script>
<style>
body{
	font-family:verdana !important;
}
.input-msg{
	font-size: 12px;
	padding: 4px 8px;
	margin-bottom: 3px;
	border-radius: 3px;
}
</style>
</head>
<body>
<div class="container">

<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$user =  $ubl_user_name;
$last_update = $ubl_user_name .', Date: '. $today .', IP: '. $ip;

// Fetch market_report
$this->db->select('*');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$query = $this->db->get('market_report_entry');
$fetch = $query->row_array(); 

$introduction = (!empty($fetch['introduction'])) ? $fetch['introduction'] : '';
$leaf = (!empty($fetch['leaf'])) ? $fetch['leaf'] : '';
$dust = (!empty($fetch['dust'])) ? $fetch['dust'] : '';
$old_season = (!empty($fetch['old_season'])) ? $fetch['old_season'] : '';
$supplements_e_auction = (!empty($fetch['supplements_e_auction'])) ? $fetch['supplements_e_auction'] : '';
$upcomeing_sale_offering_info = (!empty($fetch['upcomeing_sale_offering_info'])) ? $fetch['upcomeing_sale_offering_info'] : '';
$avg = (!empty($fetch['avg'])) ? $fetch['avg'] : '';
$sold_percentage = (!empty($fetch['sold_percentage'])) ? $fetch['sold_percentage'] : '';
$comments = (!empty($fetch['comments'])) ? $fetch['comments'] : '';

$brokens_best_amount = (!empty($fetch['brokens_best_amount'])) ? $fetch['brokens_best_amount'] : '';
$brokens_best_amountupto = (!empty($fetch['brokens_best_amountupto'])) ? $fetch['brokens_best_amountupto'] : '';
$brokens_good_amount = (!empty($fetch['brokens_good_amount'])) ? $fetch['brokens_good_amount'] : '';
$brokens_good_amountupto = (!empty($fetch['brokens_good_amountupto'])) ? $fetch['brokens_good_amountupto'] : '';
$brokens_medium_amount = (!empty($fetch['brokens_medium_amount'])) ? $fetch['brokens_medium_amount'] : '';
$brokens_medium_amountupto = (!empty($fetch['brokens_medium_amountupto'])) ? $fetch['brokens_medium_amountupto'] : '';
$brokens_plain_amount = (!empty($fetch['brokens_plain_amount'])) ? $fetch['brokens_plain_amount'] : '';
$brokens_plain_amountupto = (!empty($fetch['brokens_plain_amountupto'])) ? $fetch['brokens_plain_amountupto'] : '';
$brokens_northern_amount = (!empty($fetch['brokens_northern_amount'])) ? $fetch['brokens_northern_amount'] : '';
$brokens_northern_amountupto = (!empty($fetch['brokens_northern_amountupto'])) ? $fetch['brokens_northern_amountupto'] : '';

$fannings_best_amount = (!empty($fetch['fannings_best_amount'])) ? $fetch['fannings_best_amount'] : '';
$fannings_best_amountupto = (!empty($fetch['fannings_best_amountupto'])) ? $fetch['fannings_best_amountupto'] : '';
$fannings_good_amount = (!empty($fetch['fannings_good_amount'])) ? $fetch['fannings_good_amount'] : '';
$fannings_good_amountupto = (!empty($fetch['fannings_good_amountupto'])) ? $fetch['fannings_good_amountupto'] : '';
$fannings_medium_amount = (!empty($fetch['fannings_medium_amount'])) ? $fetch['fannings_medium_amount'] : '';
$fannings_medium_amountupto = (!empty($fetch['fannings_medium_amountupto'])) ? $fetch['fannings_medium_amountupto'] : '';
$fannings_plain_amount = (!empty($fetch['fannings_plain_amount'])) ? $fetch['fannings_plain_amount'] : '';
$fannings_plain_amountupto = (!empty($fetch['fannings_plain_amountupto'])) ? $fetch['fannings_plain_amountupto'] : '';
$fannings_northern_amount = (!empty($fetch['fannings_northern_amount'])) ? $fetch['fannings_northern_amount'] : '';
$fannings_northern_amountupto = (!empty($fetch['fannings_northern_amountupto'])) ? $fetch['fannings_northern_amountupto'] : '';
?>

<div class="row">
	<div class="form-group col-sm-12 text-center">
		<h4><u>Sale No - <?php echo $sale_no ; ?> <?php echo $auction_center ; ?> <?php echo $season ; ?></u></h4>
	</div>
</div>

<div class="row">
    <div class="col-sm-12 form-group">
        <label for="introduction">Introduction <span class="input-msg alert" id="introduction_msg" style="display:none; font-weight:normal;"></span></label>
        <textarea class="form-control" id="introduction" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','introduction')"><?php echo $introduction ; ?></textarea>
    </div>

    <div class="col-sm-12 form-group">
        <label for="leaf">Leaf <span class="input-msg alert" id="leaf_msg" style="display:none; font-weight:normal;"></span></label>
        <textarea class="form-control" id="leaf" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','leaf')"><?php echo $leaf ; ?></textarea>
    </div>

    <div class="col-sm-12 form-group">
        <label for="dust">Dust <span class="input-msg alert" id="dust_msg" style="display:none; font-weight:normal;"></span></label>
        <textarea class="form-control" id="dust" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','dust')"><?php echo $dust ; ?></textarea>
    </div>

    <div class="col-sm-12 form-group">
        <label for="old_season">Old Season <span class="input-msg alert" id="old_season_msg" style="display:none; font-weight:normal;"></span></label>
        <textarea class="form-control" id="old_season" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','old_season')"><?php echo $old_season ; ?></textarea>
    </div>

    <div class="col-sm-12 form-group">
        <label for="supplements_e_auction">Supplements+E-Auction <span class="input-msg alert" id="supplements_e_auction_msg" style="display:none; font-weight:normal;"></span></label>
        <textarea class="form-control" id="supplements_e_auction" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','supplements_e_auction')"><?php echo $supplements_e_auction ; ?></textarea>
    </div>

    <div class="col-sm-12 form-group">
        <label for="upcomeing_sale_offering_info">Upcoming Sale Offering Info <span class="input-msg alert" id="upcomeing_sale_offering_info_msg" style="display:none; font-weight:normal;"></span></label>
        <textarea class="form-control" id="upcomeing_sale_offering_info" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','upcomeing_sale_offering_info')"><?php echo $upcomeing_sale_offering_info ; ?></textarea>
    </div>

</div>

<div class="row">
    <div class="col-sm-12">
        <label for="comments">OUR CATALOGUE <span class="input-msg alert" id="our_catalouse_msg" style="display:none; font-weight:normal;"></span></label>
    </div>

    <div class="col-sm-4 form-group">
        <input type="number" class="form-control input-sm" id="avg" value="<?php echo $avg ; ?>" placeholder="Avg." onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','our_catalouse')"/>
    </div>

    <div class="col-sm-4 form-group">
        <input type="number" class="form-control input-sm" id="sold_percentage" placeholder="Sold Percentage" value="<?php echo $sold_percentage ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','our_catalouse')"/>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 form-group">
        <label for="comments">Comments <span class="input-msg alert" id="comments_msg" style="display:none; font-weight:normal;"></span></label>
        <textarea class="form-control" id="comments" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','comments')"><?php echo $comments ; ?></textarea>
    </div>

    <div class="col-sm-6">
        <label for="Brokens">Brokens <span class="input-msg alert" id="brokens_msg" style="display:none; font-weight:normal;"></span></label>
    
        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Best</span>
            <input type="number" class="form-control input-sm" id="brokens_best_amount" value="<?php echo $brokens_best_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="brokens_best_amountupto" value="<?php echo $brokens_best_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Good</span>
            <input type="number" class="form-control input-sm" id="brokens_good_amount" value="<?php echo $brokens_good_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="brokens_good_amountupto" value="<?php echo $brokens_good_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Medium</span>
            <input type="number" class="form-control input-sm" id="brokens_medium_amount" value="<?php echo $brokens_medium_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="brokens_medium_amountupto" value="<?php echo $brokens_medium_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Plain</span>
            <input type="number" class="form-control input-sm" id="brokens_plain_amount" value="<?php echo $brokens_plain_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="brokens_plain_amountupto" value="<?php echo $brokens_plain_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Northern</span>
            <input type="number" class="form-control input-sm" id="brokens_northern_amount" value="<?php echo $brokens_northern_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="brokens_northern_amountupto" value="<?php echo $brokens_northern_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','brokens')"/>
        </div>
    </div>
    <div class="col-sm-6">
        <label for="Fannings">Fannings <span class="input-msg alert" id="fannings_msg" style="display:none; font-weight:normal;"></span></label>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Best</span>
            <input type="number" class="form-control input-sm" id="fannings_best_amount" value="<?php echo $fannings_best_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="fannings_best_amountupto" value="<?php echo $fannings_best_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Good</span>
            <input type="number" class="form-control input-sm" id="fannings_good_amount" value="<?php echo $fannings_good_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="fannings_good_amountupto" value="<?php echo $fannings_good_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Medium</span>
            <input type="number" class="form-control input-sm" id="fannings_medium_amount" value="<?php echo $fannings_medium_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="fannings_medium_amountupto" value="<?php echo $fannings_medium_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Plain</span>
            <input type="number" class="form-control input-sm" id="fannings_plain_amount" value="<?php echo $fannings_plain_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="fannings_plain_amountupto" value="<?php echo $fannings_plain_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
        </div>

        <div class="input-group form-group">
            <span class="input-group-addon" style="width:100px;">Northern</span>
            <input type="number" class="form-control input-sm" id="fannings_northern_amount" value="<?php echo $fannings_northern_amount ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
            <span class="input-group-addon">-</span>
            <input type="number" class="form-control input-sm" id="fannings_northern_amountupto" value="<?php echo $fannings_northern_amountupto ; ?>" onblur="fn_save_market_report('<?php echo $season ; ?>','<?php echo $auction_center ; ?>','<?php echo $sale_no ; ?>','<?php echo $user ; ?>','<?php echo $last_update ; ?>','fannings')"/>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-12 text-center">
		<h4><u>Offerings Entry</u></h4>
	</div>
</div>
<?php 
// Brokers Information: Get all brokers
$query = $this->db->query("
    SELECT 
        `id`, 
        `brokers_name`,
        CASE 
            WHEN `id` = '7' THEN 'A'
            WHEN `id` = '6' THEN 'B'
            WHEN `id` = '5' THEN 'C'
            WHEN `id` = '4' THEN 'D'
            WHEN `id` = '1' THEN 'E'
            WHEN `id` = '2' THEN 'F'
            WHEN `id` = '3' THEN 'G'
        END AS custom_serial
    FROM `brokers_setup` 
    ORDER BY `custom_serial` ASC
");
$brokers_info = $query->result_array();
$brokers_total_rows = $query->num_rows();

// Fetch all market offerings entries from DB for specific season, auction_center & sale_no
$market_offerings_query = $this->db->query("
    SELECT * FROM market_report_offerings_entry 
    WHERE season = '".$season."' AND auction_center = '".$auction_center."' AND sale_no = '".$sale_no."'
");

// Build array with brokers_id as key
$market_offerings_data = [];
foreach ($market_offerings_query->result_array() as $row) {
    $market_offerings_data[$row['brokers_id']] = $row;
}

// Loop through all brokers
foreach ($brokers_info as $broker) {
    $brokers_id = $broker['id'];
    $row_id = "row_" . $brokers_id;

    $brokers = "brokers_" . $brokers_id;
    $leaf_offerings = "leaf_offerings_" . $brokers_id;
    $leaf_packeteer = "leaf_packeteer_" . $brokers_id;
    $leaf_internal = "leaf_internal_" . $brokers_id;
    $dust_offerings = "dust_offerings_" . $brokers_id;
    $dust_packeteer = "dust_packeteer_" . $brokers_id;
    $dust_internal = "dust_internal_" . $brokers_id;
    $supplement_offerings = "supplement_offerings_" . $brokers_id;
    $supplement_packeteer = "supplement_packeteer_" . $brokers_id;
    $supplement_internal = "supplement_internal_" . $brokers_id;

    $entry = isset($market_offerings_data[$brokers_id]) ? $market_offerings_data[$brokers_id] : null;

    $leaf_offerings_value = $entry ? number_format($entry['leaf_offerings'], 2, '.', '') : '';
    $leaf_packeteer_value = $entry ? number_format($entry['leaf_packeteer'], 2, '.', '') : '';
    $leaf_internal_value = $entry ? number_format($entry['leaf_internal'], 2, '.', '') : '';
    $dust_offerings_value = $entry ? number_format($entry['dust_offerings'], 2, '.', '') : '';
    $dust_packeteer_value = $entry ? number_format($entry['dust_packeteer'], 2, '.', '') : '';
    $dust_internal_value = $entry ? number_format($entry['dust_internal'], 2, '.', '') : '';
    $supplement_offerings_value = $entry ? number_format($entry['supplement_offerings'], 2, '.', '') : '';
    $supplement_packeteer_value = $entry ? number_format($entry['supplement_packeteer'], 2, '.', '') : '';
    $supplement_internal_value = $entry ? number_format($entry['supplement_internal'], 2, '.', '') : '';
?>
<div class="row" id="<?php echo $row_id; ?>">
    <div class="col-sm-12 form-group">
        <input type="text" class="form-control input-sm" id="brokers_<?php echo $brokers_id; ?>" value="<?php echo $broker['brokers_name']; ?>" readonly>
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $leaf_offerings; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $leaf_offerings; ?>" placeholder="Enter Leaf Offerings" autocomplete="off" 
        value="<?php echo $leaf_offerings_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $leaf_offerings; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $leaf_packeteer; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $leaf_packeteer; ?>" placeholder="Enter Leaf Packeteer" autocomplete="off" 
        value="<?php echo $leaf_packeteer_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $leaf_packeteer; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $leaf_internal; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $leaf_internal; ?>" placeholder="Enter Leaf Internal" autocomplete="off" 
        value="<?php echo $leaf_internal_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $leaf_internal; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $dust_offerings; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $dust_offerings; ?>" placeholder="Enter Dust Offerings" autocomplete="off" 
        value="<?php echo $dust_offerings_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $dust_offerings; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $dust_packeteer; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $dust_packeteer; ?>" placeholder="Enter Dust Packeteer" autocomplete="off" 
        value="<?php echo $dust_packeteer_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $dust_packeteer; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $dust_internal; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $dust_internal; ?>" placeholder="Enter Dust Internal" autocomplete="off" 
        value="<?php echo $dust_internal_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $dust_internal; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $supplement_offerings; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $supplement_offerings; ?>" placeholder="Enter Supplement Offerings" autocomplete="off" 
        value="<?php echo $supplement_offerings_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $supplement_offerings; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $supplement_packeteer; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $supplement_packeteer; ?>" placeholder="Enter Supplement Packeteer" autocomplete="off" 
        value="<?php echo $supplement_packeteer_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $supplement_packeteer; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>

    <div class="col-sm-4 form-group">
        <div id="msg_<?php echo $supplement_internal; ?>" class="input-msg alert" style="display:none;"></div>
        <input type="text" class="form-control input-sm" id="<?php echo $supplement_internal; ?>" placeholder="Enter Supplement Internal" autocomplete="off" 
        value="<?php echo $supplement_internal_value; ?>"
        onkeyup="fn_save_market_offerings(
            '<?php echo $season; ?>',
            '<?php echo $auction_center; ?>',
            <?php echo $sale_no; ?>,
            '<?php echo $user; ?>',
            '<?php echo $last_update; ?>',
            '<?php echo $brokers_id; ?>',
            document.getElementById('<?php echo $brokers; ?>').value,
            document.getElementById('<?php echo $leaf_offerings; ?>').value,
            document.getElementById('<?php echo $leaf_packeteer; ?>').value,
            document.getElementById('<?php echo $leaf_internal; ?>').value,
            document.getElementById('<?php echo $dust_offerings; ?>').value,
            document.getElementById('<?php echo $dust_packeteer; ?>').value,
            document.getElementById('<?php echo $dust_internal; ?>').value,
            document.getElementById('<?php echo $supplement_offerings; ?>').value,
            document.getElementById('<?php echo $supplement_packeteer; ?>').value,
            document.getElementById('<?php echo $supplement_internal; ?>').value,
            '<?php echo $supplement_internal; ?>'
        )"
        onfocus="this.select()"
        onkeypress="return isNumberKey(event)">
    </div>
</div>
<?php } ?>

</div> 
<!-- /container -->

<script type="text/javascript">
    function fn_save_market_report(season, auction_center, sale_no, user, last_update, input_id) {

        // Get all values from inputs
        var introduction = $('#introduction').val();
        var leaf = $('#leaf').val();
        var dust = $('#dust').val();
        var old_season = $('#old_season').val();
        var supplements_e_auction = $('#supplements_e_auction').val();
        var upcomeing_sale_offering_info = $('#upcomeing_sale_offering_info').val();
        var avg = $('#avg').val();
        var sold_percentage = $('#sold_percentage').val();
        var comments = $('#comments').val();

        var brokens_best_amount = $('#brokens_best_amount').val();
        var brokens_best_amountupto = $('#brokens_best_amountupto').val();
        var brokens_good_amount = $('#brokens_good_amount').val();
        var brokens_good_amountupto = $('#brokens_good_amountupto').val();
        var brokens_medium_amount = $('#brokens_medium_amount').val();
        var brokens_medium_amountupto = $('#brokens_medium_amountupto').val();
        var brokens_plain_amount = $('#brokens_plain_amount').val();
        var brokens_plain_amountupto = $('#brokens_plain_amountupto').val();
        var brokens_northern_amount = $('#brokens_northern_amount').val();
        var brokens_northern_amountupto = $('#brokens_northern_amountupto').val();

        var fannings_best_amount = $('#fannings_best_amount').val();
        var fannings_best_amountupto = $('#fannings_best_amountupto').val();
        var fannings_good_amount = $('#fannings_good_amount').val();
        var fannings_good_amountupto = $('#fannings_good_amountupto').val();
        var fannings_medium_amount = $('#fannings_medium_amount').val();
        var fannings_medium_amountupto = $('#fannings_medium_amountupto').val();
        var fannings_plain_amount = $('#fannings_plain_amount').val();
        var fannings_plain_amountupto = $('#fannings_plain_amountupto').val();
        var fannings_northern_amount = $('#fannings_northern_amount').val();
        var fannings_northern_amountupto = $('#fannings_northern_amountupto').val();

        var leaf_offerings = $('#leaf_offerings').val();
        var leaf_packeteer = $('#leaf_packeteer').val();
        var leaf_internal = $('#leaf_internal').val();
        var dust_offerings = $('#dust_offerings').val();
        var dust_packeteer = $('#dust_packeteer').val();
        var dust_internal = $('#dust_internal').val();

        // Build JSON object
        var postData = {
            season,
            auction_center,
            sale_no,
            user,
            last_update,
            introduction,
            leaf,
            dust,
            old_season,
            supplements_e_auction,
            upcomeing_sale_offering_info,
            avg,
            sold_percentage,
            comments,

            brokens_best_amount,
            brokens_best_amountupto,
            brokens_good_amount,
            brokens_good_amountupto,
            brokens_medium_amount,
            brokens_medium_amountupto,
            brokens_plain_amount,
            brokens_plain_amountupto,
            brokens_northern_amount,
            brokens_northern_amountupto,

            fannings_best_amount,
            fannings_best_amountupto,
            fannings_good_amount,
            fannings_good_amountupto,
            fannings_medium_amount,
            fannings_medium_amountupto,
            fannings_plain_amount,
            fannings_plain_amountupto,
            fannings_northern_amount,
            fannings_northern_amountupto,

            leaf_offerings,
            leaf_packeteer,
            leaf_internal,
            dust_offerings,
            dust_packeteer,
            dust_internal
        };

        const msgBox = $('#' + input_id + '_msg'); // Target the message div
        
        //  Send via AJAX POST
        $.ajax({
            url: '<?php echo site_url('MY_Controller/save_market_report'); ?>',
            type: 'POST',
            dataType: "JSON",
            data: postData,
            success: (res) => {
                const statusClass = res.status ? 'alert-success' : 'alert-danger';
                const message = res.message;

                msgBox
                    .removeClass('alert-success alert-danger')
                    .addClass(statusClass)
                    .html(message)
                    .fadeIn(200);

                setTimeout(() => {
                    msgBox.fadeOut(300);
                }, 800);
            },
            error: () => {
                msgBox
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('Server error. Please try again!')
                    .fadeIn(200);

                setTimeout(() => {
                    msgBox.fadeOut(300);
                }, 800);
            }
        });

    }
    
    function fn_save_market_offerings(season, auction_center, sale_no, user, last_update, brokers_id, brokers, leaf_offerings, leaf_packeteer, leaf_internal, dust_offerings, dust_packeteer, dust_internal, supplement_offerings, supplement_packeteer, supplement_internal, input_id) {
        
        const msgBox = $('#msg_' + input_id);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('MY_Controller/save_market_offerings'); ?>",
            dataType: "JSON",
            data: {
                season: season,
                auction_center: auction_center,
                sale_no: sale_no,
                user: user,
                last_update: last_update,
                brokers_id: brokers_id,
                brokers: brokers,
                leaf_offerings: leaf_offerings,
                leaf_packeteer: leaf_packeteer,
                leaf_internal: leaf_internal, 
                dust_offerings: dust_offerings,
                dust_packeteer: dust_packeteer,
                dust_internal: dust_internal, 
                supplement_offerings: supplement_offerings,
                supplement_packeteer: supplement_packeteer,
                supplement_internal: supplement_internal
            },
            success: (res) => {
                const statusClass = res.status ? 'alert-success' : 'alert-danger';
                const message = res.message;

                msgBox
                    .removeClass('alert-success alert-danger')
                    .addClass(statusClass)
                    .html(message)
                    .fadeIn(200);

                setTimeout(() => {
                    msgBox.fadeOut(300);
                }, 800);
            },
            error: () => {
                msgBox
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html('Server error. Please try again!')
                    .fadeIn(200);

                setTimeout(() => {
                    msgBox.fadeOut(300);
                }, 800);
            }
        });
        return false;
    }

    // allow number only in textbox
    function isNumberKey(key) {
        
        //getting key code of pressed key
        var keycode = (key.which) ? key.which : key.keyCode;
        
        //comparing pressed keycodes
        if (keycode > 31 && (keycode < 48 || keycode > 57) && keycode != 46) {
            alert(" You can enter only characters 0 to 9 ");
            return false;
        }else{ 
            return true;
        }
    }
</script>

</body>
</html>