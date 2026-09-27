<!DOCTYPE html>
<html lang="en">
<head>
<title>All Brokers Average Entry</title>
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
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$brokers_id = (!empty($this->input->get('brokers_id', TRUE))) ? $this->input->get('brokers_id', TRUE) : '';
$user =  $ubl_user_name;
$last_update = $ubl_user_name .', Date: '. $today .', IP: '. $ip;

// Brokers Information
$query = $this->db->query("SELECT * FROM brokers_setup WHERE id='".$brokers_id."' ");
$brokers_data = $query->row_array();
?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="#" onClick="window.print();"><span style="cursor:pointer;"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
	</div>
</div>

<div class="row">
	<div class="form-group col-sm-12">
		<h4><?php echo $brokers_data['brokers_name'] ; ?>, <?php echo $sale_no ; ?> <?php echo $auction_center ; ?> <?php echo $season ; ?></h4>
	</div>
</div>

<?php 
// Fetch all brokers' average entries
$brokers_average_query = $this->db->query("SELECT * FROM all_brokers_average_entry WHERE season='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' AND brokers_id='".$brokers_id."' ");

// Make an array for easy access
$brokers_average_data = [];
foreach ($brokers_average_query->result_array() as $row) {
    $brokers_average_data[$row['category']] = $row;
}

$shown_sections = []; // Track shown headings

$section_mappings = [
    "Current Season" => "New Season",
    "Buyers Purchases Analysis" => "Buyers Purchases Analysis",
    "Earlier Season" => "Earlier Season",
    "New Season" => "New Season"
];

// Define Categories
$categories = [
	"Current Season Leaf",
	"Current Season Dust",
	"Current Season Green Tea",
	"Current Season Other Tea",
	"Current Season A/C Concern",
	"Earlier Season Leaf",
	"Earlier Season Dust",
	"Earlier Season Green Tea",
	"Earlier Season Other Tea",
	"Earlier Season A/C Concern",
	"Buyers Purchases Analysis Export",
	"Buyers Purchases Analysis Internal",
	"New Season Leaf",
	"New Season Dust",
	"New Season Green Tea",
	"New Season Other Tea",
	"New Season A/C Concern"
];

for ($i = 0; $i < count($categories); $i++) {
	$row_id = "row_" . ($i + 1);
	$category_id = "category_" . ($i + 1);
	$pkgs_id = "pkgs_" . ($i + 1);
	$qty_id = "qty_" . ($i + 1);
	$avg_id = "avg_" . ($i + 1);
	$category_value = $categories[$i];

	// Find corresponding entry if exists
    $entry = isset($brokers_average_data[$category_value]) ? $brokers_average_data[$category_value] : null;

    $pkgs_value = $entry ? number_format($entry['pkgs'], 2, '.', '') : '';
    $qty_value = $entry ? number_format($entry['qty'], 2, '.', '') : '';
    $avg_value = $entry ? number_format($entry['avg'], 2, '.', '') : '';

	// Show section header only once
    foreach ($section_mappings as $key => $label) {
        if (strpos($category_value, $key) === 0 && !in_array($key, $shown_sections)) {
            echo '<h4 style="margin-top:10px;">' . htmlspecialchars($label) . '</h4>';
            $shown_sections[] = $key;
            break;
        }
    }
?>
	<div class="row" id="<?php echo $row_id; ?>">
		<div class="form-group col-sm-3">
			<input type="text" class="form-control input-sm" id="<?php echo $category_id; ?>" value="<?php echo $category_value; ?>" readonly>
		</div>
		<div class="form-group col-sm-3">
			<!-- Message for pkgs -->
			<div id="msg_<?php echo $pkgs_id; ?>" class="input-msg alert" style="display:none;"></div>

			<input type="text" class="form-control input-sm" id="<?php echo $pkgs_id; ?>" placeholder="Enter Packages" autocomplete="off" value="<?php echo $pkgs_value; ?>"
				onkeyup="all_brokers_average_store(
					'<?php echo $season; ?>',
					'<?php echo $auction_center; ?>',
					'<?php echo $sale_no; ?>',
					'<?php echo $brokers_id; ?>',
					'<?php echo $brokers_data['brokers_name']; ?>',
					'<?php echo $user; ?>',
					'<?php echo $last_update; ?>',
					document.getElementById('<?php echo $category_id; ?>').value,
					this.value,
					document.getElementById('<?php echo $qty_id; ?>').value,
					document.getElementById('<?php echo $avg_id; ?>').value,
					'<?php echo $pkgs_id; ?>'
				)"
				onfocus="this.select()"
				onkeypress="return isNumberKey(event)">
		</div>
		<div class="form-group col-sm-3">
			<!-- Message for qty -->
			<div id="msg_<?php echo $qty_id; ?>" class="input-msg alert" style="display:none;"></div>

			<input type="text" class="form-control input-sm" id="<?php echo $qty_id; ?>" placeholder="Enter Qty.(kg)" autocomplete="off" value="<?php echo $qty_value; ?>"
				onkeyup="all_brokers_average_store(
					'<?php echo $season; ?>',
					'<?php echo $auction_center; ?>',
					'<?php echo $sale_no; ?>',
					'<?php echo $brokers_id; ?>',
					'<?php echo $brokers_data['brokers_name']; ?>',
					'<?php echo $user; ?>',
					'<?php echo $last_update; ?>',
					document.getElementById('<?php echo $category_id; ?>').value,
					document.getElementById('<?php echo $pkgs_id; ?>').value,
					this.value,
					document.getElementById('<?php echo $avg_id; ?>').value,
					'<?php echo $qty_id; ?>'
				)"
				onfocus="this.select()"
				onkeypress="return isNumberKey(event)">
		</div>
		<div class="form-group col-sm-3">
			<!-- Message for avg -->
			<div id="msg_<?php echo $avg_id; ?>" class="input-msg alert" style="display:none;"></div>

			<input type="text" class="form-control input-sm" id="<?php echo $avg_id; ?>" placeholder="Enter Avg.(TK)" autocomplete="off" value="<?php echo $avg_value; ?>"
				onkeyup="all_brokers_average_store(
					'<?php echo $season; ?>',
					'<?php echo $auction_center; ?>',
					'<?php echo $sale_no; ?>',
					'<?php echo $brokers_id; ?>',
					'<?php echo $brokers_data['brokers_name']; ?>',
					'<?php echo $user; ?>',
					'<?php echo $last_update; ?>',
					document.getElementById('<?php echo $category_id; ?>').value,
					document.getElementById('<?php echo $pkgs_id; ?>').value,
					document.getElementById('<?php echo $qty_id; ?>').value,
					this.value,
					'<?php echo $avg_id; ?>'
				)"
				onfocus="this.select()"
				onkeypress="return isNumberKey(event)">
		</div>
	</div>

<?php } ?>


</div> 
<!-- /container -->
<script>
// All brokers average entry
function all_brokers_average_store(season, auction_center, sale_no, brokers_id, brokers_name, user, last_update, category, pkgs, qty, avg, input_id) {
	
	const msgBox = $('#msg_' + input_id);

	$.ajax({
		type: "POST",
		url: "<?php echo site_url('MY_Controller/save_all_brokers_average'); ?>",
		dataType: "JSON",
		data: {
			season: season,
			auction_center: auction_center,
			sale_no: sale_no,
			brokers_id: brokers_id,
			brokers_name: brokers_name,
			user: user,
			last_update: last_update,
			category: category,
			pkgs: pkgs,
			qty: qty,
			avg: avg
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