<!DOCTYPE html>
<html lang="en">
<head>
<title>Market Average Entry</title>
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
.input-msg {
	font-size: 12px;
	padding: 4px 8px;
	margin-bottom: 3px;
	border-radius: 3px;
}
@media print {
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	.page-break { page-break-after: always; }
	.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left; }
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
	.col-sm-2 { width: 20%; } /* actual size: 16.66666667 */
	.col-sm-1 { width: 8.33333333%; }
}
@page{
	size:A4;
}
</style>
</head>
<body>
<div class="container">

<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$user =  $ubl_user_name;
$last_update = $ubl_user_name .', Date: '. $today .', IP: '. $ip;

// Sale Schedule Information
$query = $this->db->query("SELECT * FROM `sale_schedule_setup` WHERE `season_name`='".$season."' AND `auction_center`='".$auction_center."'");
$sale_schedule_info = $query->row_array();
$total_rows = $query->num_rows();
?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="#" onClick="window.print();"><span style="cursor:pointer;"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12">
		<h4><?php echo $auction_center ; ?> <?php echo $season ; ?></h4>
	</div>
</div>

<?php 
// Fetch all market average entries from DB for specific season & auction_center
$market_average_query = $this->db->query("
    SELECT * FROM market_average_entry 
    WHERE season = '".$season."' AND auction_center = '".$auction_center."'
");

// Build array with sale_no as key for faster lookup
$market_average_data = [];
foreach ($market_average_query->result_array() as $row) {
    $market_average_data[$row['sale_no']] = $row;
}

// Loop from 1 to total_rows (e.g., 48)
for ($sale_no = 1; $sale_no <= $total_rows; $sale_no++) {
    $row_id = "row_" . $sale_no;
    $earlier_season = "earlier_season_" . $sale_no;
    $current_season = "current_season_" . $sale_no;

    // Find data if exists
    $entry = isset($market_average_data[$sale_no]) ? $market_average_data[$sale_no] : null;

    $earlier_season_value = $entry ? number_format($entry['earlier_season'], 2, '.', '') : '';
    $current_season_value = $entry ? number_format($entry['current_season'], 2, '.', '') : '';
?>
    <div class="row" id="<?php echo $row_id; ?>">
        <div class="form-group col-sm-3">
            <input type="text" class="form-control input-sm" id="sale_no_<?php echo $sale_no; ?>" value="<?php echo $sale_no; ?>" readonly>
        </div>

        <div class="form-group col-sm-3">
            <!-- earlier_season message box -->
            <div id="msg_<?php echo $earlier_season; ?>" class="input-msg alert" style="display:none;"></div>

            <input type="text" class="form-control input-sm" id="<?php echo $earlier_season; ?>" placeholder="Enter Earlier Season Amount" autocomplete="off" 
                value="<?php echo $earlier_season_value; ?>"
                onkeyup="market_average_store(
                    '<?php echo $season; ?>',
                    '<?php echo $auction_center; ?>',
                    <?php echo $sale_no; ?>,
                    '<?php echo $user; ?>',
                    '<?php echo $last_update; ?>',
                    document.getElementById('<?php echo $earlier_season; ?>').value,
                    document.getElementById('<?php echo $current_season; ?>').value,
                    '<?php echo $earlier_season; ?>'
                )"
                onfocus="this.select()"
                onkeypress="return isNumberKey(event)">
        </div>

        <div class="form-group col-sm-3">
            <!-- current_season message box -->
            <div id="msg_<?php echo $current_season; ?>" class="input-msg alert" style="display:none;"></div>

            <input type="text" class="form-control input-sm" id="<?php echo $current_season; ?>" placeholder="Enter Current Season Amount" autocomplete="off" 
                value="<?php echo $current_season_value; ?>"
                onkeyup="market_average_store(
                    '<?php echo $season; ?>',
                    '<?php echo $auction_center; ?>',
                    <?php echo $sale_no; ?>,
                    '<?php echo $user; ?>',
                    '<?php echo $last_update; ?>',
                    document.getElementById('<?php echo $earlier_season; ?>').value,
                    document.getElementById('<?php echo $current_season; ?>').value,
                    '<?php echo $current_season; ?>'
                )"
                onfocus="this.select()"
                onkeypress="return isNumberKey(event)">
        </div>
    </div>
<?php } ?>

</div> 
<!-- /container -->
<script>
// Market average entry
function market_average_store(season, auction_center, sale_no, user, last_update, earlier_season, current_season, input_id) {
	
	const msgBox = $('#msg_' + input_id);

	$.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/save_market_average'); ?>",
		dataType: "JSON",
		data: {
			season: season,
			auction_center: auction_center,
			sale_no: sale_no,
			user: user,
			last_update: last_update,
			earlier_season: earlier_season,
			current_season: current_season
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