<!DOCTYPE html>
<html lang="en">
<head>
<title>Company SALES AVERAGE REPORT</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
?>

<div id="printableArea" class="container">

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
}
.table{
	font-family:verdana;
	width: 100% !important;
}
#table, #sms_table{
	border-color:black;
}
#table tr td, #sms_table tr td{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tr th, #sms_table tr th{
	border-color:black;
	font-size:12px !important;
	padding:3px;
	vertical-align:middle;
}
#table tbody tr.highlighted td { background-color: cyan; } 
#sms_table tbody tr.highlighted td { background-color: cyan; } 

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
	size: auto;
}
</style>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a class="btn-default" href="javascript:void(0)" onClick="printDiv('printableArea')">
			<span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
		</a>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm" id="table">
			<thead>
				<tr>
					<th style="text-transform: uppercase; text-align:center; border:0px;">
					<span style="font-size:16px;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
					Company SALES AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						Season: <?php print $season; ?> 
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<!-- ALL GARDEN SUMMARY -->
<?php if(empty($garden_id)){ ?>
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="sms_table">
<thead>
<tr>
	<th style="text-align:left; border:1px solid;" class="no-print"></th>
	<th style="text-align:left; border:1px solid;">Garden Name</th>
	<th style="text-align:right; border:1px solid;">Total Kgs</th>
	<th style="text-align:right; border:1px solid;">Avg.</th>
	<th style="text-align:right; border:1px solid;">%</th>
</tr>
</thead>
<tbody>
<?php
## create temporary table - company_single_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `company_single_sales_avg_report`");

// create company_single_sales_avg_report
$this->db->query("CREATE TABLE `company_single_sales_avg_report` (
SELECT 
	invoice_entry.id, 
	invoice_entry.lot_no,
	invoice_entry.garden_id, 
	invoice_entry.old_season, 
	invoice_entry.chest_number,
    C.company_id,  
	C.contact_no AS company_contact, 
	G.contact_no AS garden_contact, 
	invoice_entry.net_kgs AS net_kgs, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount
	
FROM invoice_entry 
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id)

LEFT JOIN (SELECT `garden_id`, GROUP_CONCAT(`contact_no` SEPARATOR '@') AS contact_no FROM `contact_person_list` WHERE NOT `garden_id` = 0 GROUP BY `garden_id`) G ON (garden_and_company_setup.id = G.garden_id) 

LEFT JOIN (SELECT `company_id`, GROUP_CONCAT(`contact_no` SEPARATOR '@') AS contact_no FROM `contact_person_list` WHERE NOT `company_id` = 0 GROUP BY `company_id`) C ON (garden_and_company_setup.company_name = C.company_id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no = '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
// end

$total_sold2=0;
$sold_kgs2=0;
$total_amount2=0;
$net_offering_bag=0;

// old_season breakdown query
$this->db->select("A.id, A.company_id, A.company_contact, A.company_id, company_setup.company_name"); 
$this->db->from('company_single_sales_avg_report A');
$this->db->join('company_setup', 'A.company_id = company_setup.id', 'left');
$this->db->group_by('A.company_id');
$this->db->order_by('A.id ASC');
$query = $this->db->get();
foreach($query->result_array() as $row2){
	$company_id = $row2['company_id'];
	$contact = $row2['company_contact'];
?>

<?php if(!empty($contact)) { ?>
<tr>
    <th style="border:0px;" class="no-print">
        Send All <input class="form-check-input border-primary" style="margin-left:28px;" type="checkbox" id="check_all<?php print $company_id; ?>" value="<?php print $contact; ?>" onclick="chekallInvArray('<?php print $company_id; ?>','<?php print $contact; ?>')">
    </th>
	<th colspan="4" style="border:0px;"><?php print $row2['company_name']; ?></th>
</tr>
<?php } ?>

<?php
$total_sold=0;
$sold_kgs=0;
$total_amount=0;
$total_offering_bag=0;

//query
$this->db->select("gs.id, gs.garden_id, sum(gs.total_sold) AS total_sold, sum(gs.sold_kgs) AS sold_kgs, sum(gs.total_amount) AS total_amount, gc.garden_name, inv_offering.offering_bag"); 
$this->db->from('company_single_sales_avg_report gs');
$this->db->join('garden_and_company_setup gc','gs.garden_id=gc.id','left');

$this->db->join("(SELECT garden_id, sum(chest_number) AS offering_bag FROM invoice_entry WHERE season='".$season."' AND auction_center='".$auction_center."' AND sale_no = '".$sale_no."' AND catalogue_status='prepared' AND soft_delete='1' GROUP BY garden_id) inv_offering","gs.garden_id=inv_offering.garden_id","left");

$this->db->where('gs.company_id',$company_id);
$this->db->group_by('gs.garden_id');
$this->db->order_by('gc.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
foreach($result1 as $row1){
	$avg_kgs = ($row1['total_amount'] > 0 && $row1['sold_kgs'] > 0) ? number_format($row1['total_amount'] / $row1['sold_kgs'],2,'.',',') : '0.00';
	$percentage = ($row1['total_sold'] > 0 && $row1['offering_bag'] > 0) ? number_format(($row1['total_sold'] * 100)/$row1['offering_bag'],2,'.','') : '0.00';
?>
<tr>
	<td class="no-print" style="text-align:left; text-transform:uppercase; border:0px; width:150px;">
		<?php if(!empty($contact)) { ?>
			<input class="form-check-input border-primary check_inv_group_<?php print $company_id; ?>" type="checkbox" value="<?php print $row1['id']; ?>|<?php print $sale_no; ?>|<?php print $row1['sold_kgs']; ?>|<?php print $avg_kgs; ?>|<?php print $percentage; ?>|<?php print $row1['garden_name']; ?>" id="check_inv<?php print $row1['id']; ?>">
		<?php } ?>
	</td>

	<td style="text-align:left; text-transform:uppercase; border:0px;">
        <?php print $row1['garden_name']; ?>
	</td>
	
	<td style="text-align:right; border:0px;"><?php print number_format($row1['sold_kgs'],2,'.',','); ?></td>
	<td style="text-align:right; border:0px;"><?php print $avg_kgs; ?></td>
	<td style="text-align:right; border:0px;"><?php print $percentage; ?></td>
</tr>
<?php
	$total_sold +=$row1['total_sold'];
	$sold_kgs +=$row1['sold_kgs'];
	$total_amount +=$row1['total_amount'];
	$total_offering_bag +=$row1['offering_bag'];
} // 2nd loop
?>

<tr>
	<th class="no-print" style="border:0px;">
		<?php if(!empty($contact)) { ?>
			<input type="button" value="Send SMS All" id="send_sms_all<?php print $company_id ;?>" class="btn btn-sm btn-danger" onclick="fn_all_sms_disburse_data(<?php print $company_id ;?>);"/>
		<?php } ?>
	</th>
	<th colspan="1" style="text-align:right; border:0px;">SUB TOTAL: </th>
	<th style="text-align:right; border:0px;"><?php print number_format($sold_kgs,2,'.',','); ?></th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($total_amount > 0 && $sold_kgs > 0) ? number_format($total_amount / $sold_kgs,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right; border:0px;">
	<?php 
		print ($total_sold > 0 && $total_offering_bag > 0) ? number_format(($total_sold * 100)/$total_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>

<?php
	$total_sold2 +=$total_sold;
	$sold_kgs2 +=$sold_kgs;
	$total_amount2 +=$total_amount;
	$net_offering_bag +=$total_offering_bag;
} // 1st loop
?>
</tbody>
<tfoot>
<tr>
	<td colspan="5" style="border:0px;"></td>
</tr>
<tr>
	<th class="no-print" style="border:0px;"></th>
	<th colspan="1" style="text-align:right;">NET TOTAL: </th>
	<th style="text-align:right;"><?php print number_format($sold_kgs2,2,'.',','); ?></th>
	<th style="text-align:right;">
	<?php 
		print ($total_amount2 > 0 && $sold_kgs2 > 0) ? number_format($total_amount2 / $sold_kgs2,2,'.',',') : '0.00'; 
	?>
	</th>
	<th style="text-align:right;">
	<?php 
		print ($total_sold2 > 0 && $net_offering_bag > 0) ? number_format(($total_sold2 * 100)/$net_offering_bag,2,'.','') : '0.00'; 
	?>
	</th>
</tr>	
</tfoot>
</table>
</div>
</div>
<!-- /row -->
<?php } ?>

</div> 
<!-- container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
// print area
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
}


$('#sms_table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});

// Function to handle the click event of the "Check All" checkbox
function chekallInvArray(company_id, contact) {
    // Get the state of the "Check All" checkbox
    var isChecked = $('#check_all' + company_id).prop('checked');

    // Set the state of all checkboxes in the specific group based on the "Check All" checkbox
    $('.check_inv_group_' + company_id).prop('checked', isChecked);
}

// Function to get values of checked checkboxes grouped by company, including "Check All" checkboxes
function getChecklistItems() {
    var groupedItems = {};

    // Iterate over each "Check All" checkbox
    $('input[id^="check_all"]').each(function() {
        var company_id = $(this).attr('id').replace('check_all', '');
        var result = $('.check_inv_group_' + company_id + ':checked').get();

        if (result.length > 0) {
            var columns = $.map(result, function(element) {
                return $(element).attr("value");
            });
            groupedItems[company_id] = columns.join("~");
        }

        // Include the value of the "Check All" checkbox if it's checked
        if ($(this).prop('checked')) {
            var checkAllValue = $(this).attr("value");
            if (groupedItems[company_id]) {
                groupedItems[company_id] = checkAllValue + "~" + groupedItems[company_id];
            } else {
                groupedItems[company_id] = checkAllValue;
            }
        }
    });

    // Convert the groupedItems object to a string representation
    var resultString = Object.keys(groupedItems).map(function(company_id) {
        return groupedItems[company_id];
    }).join("#");

    return resultString;
}




// Bulk SMS
function fn_all_sms_disburse_data(company_id){
	// checked attendance array data
    var inv_array = getChecklistItems();
    //alert(inv_array);
	
	// validation
    if(inv_array == ''){
        alert("Please check at least a Garden.");
        return false;
    }

	// before response
    $("#send_sms_all"+company_id).val('Please Wait...');
    $("#send_sms_all"+company_id).prop("disabled", true);

	// send to server
    $.post("<?php echo site_url('MY_Controller/bulk_sms_disburse2'); ?>", 
        { 
            inv_array: inv_array
        }, 
        function(data) {
            if (data == 1) {
                  alert("✅ SMS Sent Successfully!");
            } else {
                alert("Error: " + data);
            }
            // reset button
			$("#send_sms_all"+company_id).val('Send SMS All');
	 		$("#send_sms_all"+company_id).prop("disabled", false);
			window.location.reload();
        }
    );
}
</script>

</body>
</html>