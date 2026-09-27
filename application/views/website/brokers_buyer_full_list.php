<?php 
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$brokers_id = (!empty($this->input->get('brokers_id', TRUE))) ? $this->input->get('brokers_id', TRUE) : '';

$query = $this->db->query("SELECT brokers_name FROM brokers_setup WHERE id='".$brokers_id."'");
$broker_details = $query->row_array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>All Brokers Buyer Purchase</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">

<style>
body {
  background: #ffffff !important;
  margin-top: 5px !important;
  padding: 10px;
}
.table{
  border-color:black;
}
.table thead tr th{
  border-color:black;
  vertical-align:middle;
  text-align:center;
}
.table tbody tr td{
  border-color:black;
  vertical-align:middle;
  text-align:center;
}
#mydata tbody tr.highlighted td { 
  background-color: cyan !important; 
  color: black !important;
} 
.total-row {
  font-weight: bold;
  background-color: #f5f5f5;
}
.table-responsive-container {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  margin-bottom: 20px;
  border: 1px solid #ddd;
}
.table-responsive-sm {
  min-height: .01%;
  overflow-x: auto;
}
.editable-input {
  width: 80px; 
  text-align:right;
  display: inline-block;
}
.category-select {
  width: 120px;
  display: inline-block;
}
.saving-indicator {
  font-size: 10px;
  margin-left: 5px;
}
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
@page{
	size:auto;
}
</style>
</head>
<body>
<div class="container-fluid" id="printableArea">
<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>
<div class="row">
<div class="form-group col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
  <th style="text-transform: uppercase; text-align:center; border:0px;">
  <span style="font-size:18px; font-weight:normal;">ALL  BROKERS BUYERS PURCHASE <br /> <?php print $broker_details['brokers_name']; ?></span> 
</tr>
<tr>
  <th style="text-transform:uppercase; text-align:center; border:0px;">
    Season: <?php print $season; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print $auction_center; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sale No: <?php print $sale_no; ?>
  </th>
</tr>
</table>
</div>
</div>
<div class="table-responsive-container">
<div class="table-responsive-sm">
<table class="table table-bordered table-hover" id="mydata">
<thead class="thead-light">
<tr>
    <th>Sl</th>
    <th>Buyer</th>
    <th>Leaf Bags</th>
    <th>Leaf Kgs</th>
    <th>Dust Bags</th>
    <th>Dust Kgs</th>
    <th>Category</th>
</tr>
</thead>
<tbody>

<?php
$sl = 1;
$leaf_bags = 0;
$leaf_kgs = 0;
$dust_bags = 0;
$dust_kgs = 0;

$this->db->select("all_broker_buyer_purchase.*, IF(all_broker_buyer_purchase.soft_delete = 1, '', 'danger') AS data_status"); 
$this->db->from('all_broker_buyer_purchase');
$this->db->where('all_broker_buyer_purchase.soft_delete', 1);
$this->db->where('all_broker_buyer_purchase.season', $season);
$this->db->where('all_broker_buyer_purchase.auction_center', $auction_center);
$this->db->where('all_broker_buyer_purchase.sale_no', $sale_no);
$this->db->where('all_broker_buyer_purchase.brokers_id', $brokers_id);
$this->db->order_by('all_broker_buyer_purchase.id DESC');
$query = $this->db->get();
$bill_info = $query->result_array();

foreach($bill_info as $bill_data){
    $leaf_bags += $bill_data['leaf_bags'];
    $leaf_kgs += $bill_data['leaf_kgs'];
    $dust_bags += $bill_data['dust_bags'];
    $dust_kgs += $bill_data['dust_kgs'];
    
    // Get buyer type from buyer_setup table
    $buyer_query = $this->db->query("SELECT buyer_type FROM buyer_setup WHERE id='".$bill_data['buyer_id']."'");
    $buyer_details = $buyer_query->row_array();
    $buyer_type = isset($buyer_details['buyer_type']) ? $buyer_details['buyer_type'] : '';
    
    // Determine available categories based on buyer type
    $category_options = [];
    if($buyer_type == 'Internal') {
        $category_options = ['INTERNAL'];
    } elseif($buyer_type == 'Export') {
        $category_options = ['EXPORT'];
    } else {
        // If buyer_type is 'Both' or empty/undefined, show both options
        $category_options = ['INTERNAL', 'EXPORT'];
    }
?>    

<tr class="table-<?php echo $bill_data['data_status']; ?>">
    <td><?php print $sl++; ?></td>
    <td style="text-transform:uppercase; text-align:left;"><?php echo $bill_data['buyer_name']; ?></td>
    
    <td style="text-align:right;">
        <input type="text" 
               class="form-control input-sm editable-field editable-input" 
               data-id="<?php echo $bill_data['id']; ?>"
               data-season="<?php echo $bill_data['season']; ?>"
               data-auction-center="<?php echo $bill_data['auction_center']; ?>"
               data-sale-no="<?php echo $bill_data['sale_no']; ?>"
               data-brokers-id="<?php echo $bill_data['brokers_id']; ?>"
               data-category="<?php echo $bill_data['category']; ?>"
               data-buyer-id="<?php echo $bill_data['buyer_id']; ?>"
               data-field-type="leaf_bags"
               value="<?php echo ($bill_data['leaf_bags'] === null || $bill_data['leaf_bags'] == 0) ? '' : number_format($bill_data['leaf_bags'], 2, '.', ''); ?>"
               onblur="savebrokersbuyerpurchaseField(this)">
    </td>
    
    <td style="text-align:right;">
        <input type="text" 
               class="form-control input-sm editable-field editable-input" 
               data-id="<?php echo $bill_data['id']; ?>"
               data-season="<?php echo $bill_data['season']; ?>"
               data-auction-center="<?php echo $bill_data['auction_center']; ?>"
               data-sale-no="<?php echo $bill_data['sale_no']; ?>"
               data-brokers-id="<?php echo $bill_data['brokers_id']; ?>"
               data-category="<?php echo $bill_data['category']; ?>"
               data-buyer-id="<?php echo $bill_data['buyer_id']; ?>"
               data-field-type="leaf_kgs"
               value="<?php echo ($bill_data['leaf_kgs'] === null || $bill_data['leaf_kgs'] == 0) ? '' : number_format($bill_data['leaf_kgs'], 2, '.', ''); ?>"
               onblur="savebrokersbuyerpurchaseField(this)">
    </td>

    <td style="text-align:right;">
        <input type="text" 
               class="form-control input-sm editable-field editable-input" 
               data-id="<?php echo $bill_data['id']; ?>"
               data-season="<?php echo $bill_data['season']; ?>"
               data-auction-center="<?php echo $bill_data['auction_center']; ?>"
               data-sale-no="<?php echo $bill_data['sale_no']; ?>"
               data-brokers-id="<?php echo $bill_data['brokers_id']; ?>"
               data-category="<?php echo $bill_data['category']; ?>"
               data-buyer-id="<?php echo $bill_data['buyer_id']; ?>"
               data-field-type="dust_bags"
               value="<?php echo ($bill_data['dust_bags'] === null || $bill_data['dust_bags'] == 0) ? '' : number_format($bill_data['dust_bags'], 2, '.', ''); ?>"
               onblur="savebrokersbuyerpurchaseField(this)">
    </td>

    <td style="text-align:right;">
        <input type="text" 
               class="form-control input-sm editable-field editable-input" 
               data-id="<?php echo $bill_data['id']; ?>"
               data-season="<?php echo $bill_data['season']; ?>"
               data-auction-center="<?php echo $bill_data['auction_center']; ?>"
               data-sale-no="<?php echo $bill_data['sale_no']; ?>"
               data-brokers-id="<?php echo $bill_data['brokers_id']; ?>"
               data-category="<?php echo $bill_data['category']; ?>"
               data-buyer-id="<?php echo $bill_data['buyer_id']; ?>"
               data-field-type="dust_kgs"
               value="<?php echo ($bill_data['dust_kgs'] === null || $bill_data['dust_kgs'] == 0) ? '' : number_format($bill_data['dust_kgs'], 2, '.', ''); ?>"
               onblur="savebrokersbuyerpurchaseField(this)">
    </td>
    
    <td style="text-align:center;">
        <select class="form-control input-sm editable-field category-select" 
                data-id="<?php echo $bill_data['id']; ?>"
                data-season="<?php echo $bill_data['season']; ?>"
                data-auction-center="<?php echo $bill_data['auction_center']; ?>"
                data-sale-no="<?php echo $bill_data['sale_no']; ?>"
                data-brokers-id="<?php echo $bill_data['brokers_id']; ?>"
                data-category="<?php echo $bill_data['category']; ?>"
                data-buyer-id="<?php echo $bill_data['buyer_id']; ?>"
                data-field-type="category"
                onchange="savebrokersbuyerpurchaseField(this)">
            <?php foreach($category_options as $option): ?>
                <option value="<?php echo $option; ?>" <?php echo ($bill_data['category'] == $option) ? 'selected' : ''; ?>>
                    <?php echo $option; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>    

<?php    
}
?>

<!-- Total row -->
<tr class="total-row">
    <td colspan="2" style="text-align: right;">TOTAL:</td>
    <td style="text-align: right;" id="total-leaf-bags"><?php echo number_format($leaf_bags, 2); ?></td>
    <td style="text-align: right;" id="total-leaf-kgs"><?php echo number_format($leaf_kgs, 2); ?></td>
    <td style="text-align: right;" id="total-dust-bags"><?php echo number_format($dust_bags, 2); ?></td>
    <td style="text-align: right;" id="total-dust-kgs"><?php echo number_format($dust_kgs, 2); ?></td>
    <td></td>
</tr>

</tbody>
</table>
</div>
</div>
</div>
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
//row highlighted - multi-select version
$(document).ready(function() {
    $('#mydata tbody tr').click(function(e) {
        // Don't highlight if clicking on the edit link or input fields
        if ($(e.target).closest('a.edit, input.editable-field, select.editable-field').length === 0) {
            // Toggle highlight on clicked row
            $(this).toggleClass('highlighted');
        }
    });
});

// Function to save brokers buyer purchase field (updated for both inputs and dropdowns)
function savebrokersbuyerpurchaseField(element) {
    var $el = $(element);
    var id = $el.data('id');
    var season = $el.data('season');
    var auctionCenter = $el.data('auction-center');
    var saleNo = $el.data('sale-no');
    var brokersId = $el.data('brokers-id');
    var category = $el.data('category');
    var buyerId = $el.data('buyer-id');
    var fieldType = $el.data('field-type');
    var value = $el.val();

    console.log('savebrokersbuyerpurchaseField ->', {
        id, season, auctionCenter, saleNo, brokersId, category, buyerId, fieldType, value
    });

    // For numeric fields, validate the number
    if (fieldType !== 'category') {
        if (value === '' || isNaN(parseFloat(value))) {
            console.warn('Invalid value, skipping update');
            var originalValue = $el.data('original-value') || '';
            $el.val(originalValue);
            return;
        }
        // Format the value to 2 decimal places for numeric fields
        value = parseFloat(value).toFixed(2);
        $el.val(value);
    }

    // Add saving indicator
    var $ind = $el.siblings('.saving-indicator');
    if (!$ind.length) {
        $ind = $('<span class="saving-indicator"></span>').insertAfter($el);
    }
    $ind.text('Saving...').css('color', 'gray').show();

    // Prepare data for AJAX call
    var payload = {
        id: id,
        season: season,
        auction_center: auctionCenter,
        sale_no: saleNo,
        brokers_id: brokersId,
        category: category,
        buyer_id: buyerId,
        field_type: fieldType,
        value: value
    };

    // Make AJAX call
    update_brokers_buyer_pur_field(payload, function(response) {
        console.log('update_brokers_buyer_pur_field response:', response);
        if (response && response.success) {
            $ind.text('Saved!').css('color', 'green').delay(1200).fadeOut();
            
            // Update the data-category attribute for the row
            if (fieldType === 'category') {
                $el.data('category', value);
                // Update all inputs in the same row with new category
                $el.closest('tr').find('input.editable-field').each(function() {
                    $(this).data('category', value);
                });
            }
            
            // Update totals for numeric fields
            if (fieldType !== 'category') {
                updateTotals();
            }
        } else {
            var msg = response && response.message ? response.message : 'Server error';
            $ind.text('Error: ' + msg).css('color', 'red').delay(2500).fadeOut();
            
            // Revert the value if there was an error
            if (fieldType !== 'category') {
                var originalValue = $el.data('original-value') || '';
                $el.val(originalValue);
                updateTotals(); // Update totals in case we need to revert
            } else {
                // For dropdown, revert to original selected value
                var originalCategory = $el.data('category') || '';
                $el.val(originalCategory);
            }
        }
    });
}

// AJAX function to update brokers buyer purchase field
function update_brokers_buyer_pur_field(payload, callback) {
    console.log('AJAX payload ->', payload);
    $.ajax({
        url: '<?php echo site_url("MY_Controller/update_brokers_buyer_pur_field"); ?>',
        type: 'POST',
        dataType: 'json',
        data: payload,
        success: function(response) {
            console.log('AJAX success:', response);
            if (typeof callback === 'function') callback(response);
        },
        error: function(xhr, status, err) {
            console.error('AJAX error:', status, err, xhr.responseText);
            if (typeof callback === 'function') callback({ success: false, message: 'AJAX/server error' });
        }
    });
}

// Function to update all totals display
function updateTotals() {
    var totalLeafBags = 0;
    var totalLeafKgs = 0;
    var totalDustBags = 0;
    var totalDustKgs = 0;
    
    $('input[data-field-type="leaf_bags"]').each(function() {
        var value = parseFloat($(this).val()) || 0;
        totalLeafBags += value;
    });
    
    $('input[data-field-type="leaf_kgs"]').each(function() {
        var value = parseFloat($(this).val()) || 0;
        totalLeafKgs += value;
    });
    
    $('input[data-field-type="dust_bags"]').each(function() {
        var value = parseFloat($(this).val()) || 0;
        totalDustBags += value;
    });
    
    $('input[data-field-type="dust_kgs"]').each(function() {
        var value = parseFloat($(this).val()) || 0;
        totalDustKgs += value;
    });
    
    $('#total-leaf-bags').text(totalLeafBags.toFixed(2));
    $('#total-leaf-kgs').text(totalLeafKgs.toFixed(2));
    $('#total-dust-bags').text(totalDustBags.toFixed(2));
    $('#total-dust-kgs').text(totalDustKgs.toFixed(2));
}

// Store original values on page load
$(document).ready(function() {
    $('input.editable-field, select.editable-field').each(function() {
        $(this).data('original-value', $(this).val());
    });
    
    // Update all totals on page load (in case of any formatting issues)
    updateTotals();
});

function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
}
</script>
</body>
</html>