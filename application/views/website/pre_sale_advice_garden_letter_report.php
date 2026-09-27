<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$company_id = (!empty($this->input->get('company_id', TRUE))) ? $this->input->get('company_id', TRUE) : '';
$garden_clause = (!empty($garden_id)) ? "invoice_entry.garden_id='".$garden_id."' " : "NOT invoice_entry.garden_id='' ";
$garden_clause2 = (!empty($garden_id)) ? "garden_id='".$garden_id."' " : "NOT garden_id='' ";

## company details
$query = $this->db->query("SELECT * FROM company_setup WHERE id='".$company_id."' ");
$company_data = $query->row_array(); 

// get garden info
$this->db->select('*'); 
$this->db->from('garden_and_company_setup');
$this->db->where('id',$garden_id);
$this->db->where('soft_delete',	1);
$query = $this->db->get();
$garden_info = $query->row_array();
$current_time = date("Y-m-d H:i:s"); 

// Get garden letter text from garden_letter_setup
$garden_letter_text = "";
if (!empty($season) && !empty($auction_center) && !empty($sale_no)) {
    $this->db->select('garden_letter_text,gl_story_text');
    $this->db->from('garden_letter_setup');
    $this->db->where('season', $season);
    $this->db->where('auction_center', $auction_center);
    $this->db->where('sale_no', $sale_no);
    $query_letter = $this->db->get();
    
    if ($query_letter->num_rows() > 0) {
        $letter_data = $query_letter->row_array();
        $garden_letter_text = $letter_data['garden_letter_text'];
        $gl_story_text = $letter_data['gl_story_text'];
    }
}

$title = !empty($company_id) ? $company_data['company_name'].'-'.$sale_no : $garden_info['garden_name'].'-'.$sale_no;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Garden Letter - <?php echo $title ; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
</head>
<body>
<div id="printableArea" class="container">

<style>
body{
	font-family:verdana !important;
}
.table{
	font-family:verdana;
	width: 100% !important;
}
#table{
	border-color:black;
}
#table tbody tr.highlighted td { background-color: cyan; } 

@media print{    
	thead { display: table-header-group; }
	.no-print, .no-print *{ display: none !important; }
	a[href]:after { content: none !important; }
	
	/* Hide gl_story_text when summary print */
	.hide-on-summary-print { display: none !important; }
	
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

	.page-break {
		break-before: page;
	}
}

@page{
	size: legal portrait; 
	margin-left: 100px;
}
</style>

<div class="row">
	<div class="form-group col-sm-12 text-right">
		<?php if(!empty($garden_id)){ ?> 
			<!--<a target="_blank" class="no-print" style="text-decoration:none;" href="<?php echo site_url('Pre_sale_advice_report_export/pre_sale_advice_export'); ?>?season=<?php echo urlencode($season); ?>&auction_center=<?php echo urlencode($auction_center); ?>&sale_from=<?php echo urlencode($sale_no); ?>&garden_id=<?php echo urlencode($garden_id); ?>">
				<span class="badge badge-danger" style="cursor:pointer;">
					<span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel
				</span>
			</a>&nbsp;-->
		<?php } ?>
		<a class="no-print" href="#" onClick="printSummary()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Summary Print</a>&nbsp;
		<a class="no-print" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> All Print</a>
	</div>
</div>

<?php
## Prompt Date
$query = $this->db->query("SELECT date_format(sale_date,'%d-%M-%Y') AS sale_date, date_format(buyer_prompt_date,'%d-%M-%Y') AS buyer_prompt_date FROM sale_schedule_setup WHERE season_name='".$season."' AND auction_center='".$auction_center."' AND sale_no='".$sale_no."' ");
$prompt_date = $query->row_array();
?>

<?php
## create temporary table - pre_post_sale_advice
$this->db->query("DROP TABLE IF EXISTS `pre_post_sale_advice`");

// create pre_post_sale_advice @  buyer-1
$this->db->query("CREATE TABLE `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	invoice_entry.old_season,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: ', invoice_entry.old_sale_no, ' /', invoice_entry.old_lot_no, ' -'), '') AS re_print,
	invoice_entry.garden_id,
	gn.garden_name,
	gn.company_name AS company_id,
	sale_entry.buyer1 AS buyer_id,	
	sale_entry.buyer_type1 AS buyer_type,
	invoice_entry.valuation,
	invoice_entry.net_kgs AS offering_kgs,
	invoice_entry.chest_number AS offering_bag,
	IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.remarks !='W/D' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");

// create pre_post_sale_advice @  buyer-2
$this->db->query("INSERT INTO `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	invoice_entry.old_season,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: ', invoice_entry.old_sale_no, ' /', invoice_entry.old_lot_no, ' -'), '') AS re_print,
	invoice_entry.garden_id,
	gn.garden_name,
	gn.company_name AS company_id,
	sale_entry.buyer2 AS buyer_id,	
	sale_entry.buyer_type2 AS buyer_type,
	invoice_entry.valuation,
	invoice_entry.net_kgs AS offering_kgs,
	invoice_entry.chest_number AS offering_bag,
	IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.remarks !='W/D' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");

// create pre_post_sale_advice @  buyer-3
$this->db->query("INSERT INTO `pre_post_sale_advice` (
SELECT 

	invoice_entry.sale_no,
	invoice_entry.sale_date,
	invoice_entry.lot_no,
	invoice_entry.w_inv_no,
	invoice_entry.old_season,
	CONCAT(tg.grade_name,invoice_entry.additional_grade) AS grade_name,
	IF(invoice_entry.old_sale_no > 0, CONCAT('Sale: ', invoice_entry.old_sale_no, ' /', invoice_entry.old_lot_no, '-'), '') AS re_print,
	invoice_entry.garden_id,
	gn.garden_name,
	gn.company_name AS company_id,
	sale_entry.buyer3 AS buyer_id,	
	sale_entry.buyer_type3 AS buyer_type,
	invoice_entry.valuation,
	invoice_entry.net_kgs AS offering_kgs,
	invoice_entry.chest_number AS offering_bag,
	IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
	IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
	IFNULL(sale_entry.sold_price,0) AS sold_price,
	IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount

FROM invoice_entry  
LEFT JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
LEFT JOIN tea_grade_setup tg ON (invoice_entry.grade_id = tg.id) 
LEFT JOIN garden_and_company_setup gn ON (invoice_entry.garden_id = gn.id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.remarks !='W/D' AND invoice_entry.soft_delete='1' AND $garden_clause GROUP BY invoice_entry.id) ");
?>
<?php
// query
if (!empty($company_id)) {
    $where = "company_id = ?";
    $params = array($company_id);
} else {
    $where = "sale_no != ''";
    $params = array(); // no parameter binding needed
}

$sql = "
SELECT 
    garden_id, 
    garden_name,

    SUM(CASE WHEN re_print = '' THEN offering_bag ELSE 0 END) AS new_off_bag,
    SUM(CASE WHEN re_print != '' THEN offering_bag ELSE 0 END) AS rp_off_bag,

    SUM(CASE WHEN re_print = '' THEN offering_kgs ELSE 0 END) AS new_off_kgs,
    SUM(CASE WHEN re_print != '' THEN offering_kgs ELSE 0 END) AS rp_off_kgs,

    SUM(CASE WHEN re_print = '' THEN sold_bag ELSE 0 END) AS new_sold_bag,
    SUM(CASE WHEN re_print != '' THEN sold_bag ELSE 0 END) AS rp_sold_bag,

    SUM(CASE WHEN re_print = '' THEN sold_kgs ELSE 0 END) AS new_sold_kgs,
    SUM(CASE WHEN re_print != '' THEN sold_kgs ELSE 0 END) AS rp_sold_kgs,

    SUM(CASE WHEN re_print = '' THEN sold_amount ELSE 0 END) AS new_sold_amount,
    SUM(CASE WHEN re_print != '' THEN sold_amount ELSE 0 END) AS rp_sold_amount,

    SUM(offering_kgs) AS offering_kgs,
    SUM(offering_bag) AS offering_bag,
    SUM(sold_bag) AS sold_bag,
    SUM(sold_kgs) AS sold_kgs,
    SUM(sold_amount) AS sold_amount

FROM (
    SELECT 
        garden_id, 
        garden_name, 
        lot_no, 
        w_inv_no, 
        grade_name, 
        re_print, 
        offering_kgs, 
        offering_bag, 
        valuation, 
        SUM(buyer_kgs) AS sold_kgs, 
        SUM(buyer_bag) AS sold_bag, 
        sold_price, 
        SUM(buyer_amount) AS sold_amount
    FROM pre_post_sale_advice
    WHERE {$where}
    GROUP BY lot_no
) AS lot_summary
GROUP BY garden_id
ORDER BY garden_name ASC
";

$query = $this->db->query($sql, $params);
$data = $query->result_array();

// Filter out gardens with 0 kgs and 0 avg TK when company search
if (!empty($company_id)) {
    $filtered_data = array();
    foreach($data as $row) {
        if ($row['sold_kgs'] > 0 && $row['sold_amount'] > 0) {
            $filtered_data[] = $row;
        }
    }
    $data = $filtered_data;
}

// Calculate totals for company report
$company_totals = array(
    'total_kgs' => 0,
    'total_amount' => 0
);

foreach($data as $row) {
    $company_totals['total_kgs'] += $row['sold_kgs'];
    $company_totals['total_amount'] += $row['sold_amount'];
}

$company_avg_tk = ($company_totals['total_kgs'] > 0 && $company_totals['total_amount'] > 0) 
    ? number_format($company_totals['total_amount'] / $company_totals['total_kgs'], 2) 
    : '0.00';
?>
<div class="col-sm-12"></div>
<div class="row">
    <table class="table no-print">
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

    <?php if (!empty($company_id)) { ?>
       <!-- <div class="col-sm-12 text-left"><p style="font-family: verdana; font-weight:bold; text-transform: uppercase; font-size:14px;;">To: <?php print $company_data['company_name']; ?>, <?php print $company_data['company_address1']; ?></p></div>-->
    <?php } ?>

    <div class="col-sm-12">
        <table class="table table-sm" id="table" style="margin-bottom:5px; margin-top:5px;">
            <thead>
                <tr>
                    <td colspan="3" class="text-left" style="border:0px;">
                        <?php if(!empty($company_id) && !empty($company_data['com_report_to'])) { ?> 
                            <p style="font-family: verdana; font-size:14px;" contenteditable="true"><?php print nl2br($company_data['com_report_to']) ; ?></p>
                        <?php } elseif(!empty($garden_info['report_to'])) { ?> 
                            <p style="font-family: verdana; font-size:14px;" contenteditable="true"><?php print nl2br($garden_info['report_to']) ; ?></p>
                        <?php } ?>
                        <div style="text-align:center;">
                            <b style="font-size:14px;">Sale No: <?php print $sale_no; ?> </b>
                        </div>
                        <p style="font-family: verdana; font-size:14px;" contenteditable="true">Dear Sir, </p>
                        <?php if(!empty($garden_letter_text)) { ?>
                            <p style="font-family: verdana; font-size:14px;" contenteditable="true"><?php echo nl2br($garden_letter_text); ?></p>
                        <?php } else { ?>
                            We give below the grade wise Valuation / Sold prices.
                        <?php } ?>
                         <?php if(!empty($gl_story_text)) { ?>
                            <p class="gl-story-text" style="font-family: verdana; font-size:14px;" contenteditable="true"><?php echo nl2br($gl_story_text); ?></p>
                        <?php } ?>
                         <p style="font-family: verdana; font-size:14px;" contenteditable="true">Given below are the statistics of your teas sold in our Catalogue in Sale No.<?php print $sale_no; ?> </p>
                    </td>
                </tr>
                
                <tr class="spacer"><td colspan="3" style="height: 10px; border: none;"></td></tr>            
                <!-- Simple table header for garden summary -->
                <tr>
                    <th style="text-align:center;  vertical-align:middle;">MARKS</th>
                    <th style="text-align:center; vertical-align:middle;">TOTAL Kgs.</th>
                    <th style="text-align:center; vertical-align:middle;">Av./Tk</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($company_id)) { ?>
                    <!-- Show all gardens in one table for company (excluding 0 kgs gardens) -->
                    <?php if (!empty($data)) { ?>
                        <?php foreach($data as $row) { 
                            $garden_total_kgs = $row['sold_kgs'];
                            $garden_avg_tk = ($row['sold_amount'] > 0 && $row['sold_kgs'] > 0) 
                                ? number_format($row['sold_amount'] / $row['sold_kgs'], 2) 
                                : '0.00';
                        ?>
                        <tr>
                            <td style="text-align:left;  text-transform:uppercase; font-weight:bold;"><?php print $row['garden_name']; ?></td>
                            <td style="text-align:center; font-weight:bold;"><?php echo number_format($garden_total_kgs, 2, '.', ','); ?></td>
                            <td style="text-align:center;  font-weight:bold;"><?php echo $garden_avg_tk; ?></td>
                        </tr>
                        <?php } ?>
                        <!-- Company Total Row -->
                       <!-- <tr style="border-top: 2px solid #000;">
                            <td style="text-align:center;  text-transform:uppercase; font-weight:bold;">TOTAL</td>
                            <td style="text-align:center; font-weight:bold;"><?php echo number_format($company_totals['total_kgs'], 2, '.', ','); ?></td>
                            <td style="text-align:center;  font-weight:bold;"><?php echo $company_avg_tk; ?></td>
                        </tr>-->
                    <?php } else { ?>
                        <tr>
                            <td colspan="3" style="text-align:center; font-weight:bold;">No data available for this sale</td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <!-- Show single garden for garden search -->
                    <?php if (!empty($data)) { ?>
                        <?php foreach($data as $row) { 
                            $garden_total_kgs = $row['sold_kgs'];
                            $garden_avg_tk = ($row['sold_amount'] > 0 && $row['sold_kgs'] > 0) 
                                ? number_format($row['sold_amount'] / $row['sold_kgs'], 2) 
                                : '0.00';
                        ?>
                        <tr>
                            <td style="text-align:center;  text-transform:uppercase; font-weight:bold;"><?php print $row['garden_name']; ?></td>
                            <td style="text-align:center; font-weight:bold;"><?php echo number_format($garden_total_kgs, 2, '.', ','); ?></td>
                            <td style="text-align:center;  font-weight:bold;"><?php echo $garden_avg_tk; ?></td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3" style="text-align:center; font-weight:bold;">No data available for this sale</td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div> <!-- col -->
</div> <!-- row -->

<div class="row">
    <div class="col-sm-6" style="margin-top:30px;" contenteditable="true">
        <p class="text-left" style="font-family: verdana; border:0px; font-size:13px;"> 
            With best wishes, <br /><br /> Your Sincerely, 
            <br />
            <br />
            <br />
            <br />
            <b style="text-transform: uppercase;"> <?php print $website_title; ?> </b>
        </p>
    </div>

    <div class="col-sm-12" style="margin-top:15px;">
        <table>
            <tr>
                <td style="font-size:13px; vertical-align: top;">CC:&nbsp;&nbsp;</td>
                <td style="font-size:13px;" contenteditable="true">
                    <?php 
                    if(!empty($company_id) && !empty($company_data['com_report_cc'])) {
                        print nl2br($company_data['com_report_cc']);
                    } elseif(!empty($garden_info['report_cc'])) {
                        print nl2br($garden_info['report_cc']);
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="page-break"></div>

<script src="<?php echo base_url().'assets/jquery/3.4.1/jquery.min.js'; ?>"></script>
<script>
// print area
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}

// Summary print function - hides gl_story_text
function printSummary() {
    // Add class to hide gl_story_text
    $('.gl-story-text').addClass('hide-on-summary-print');
    
    // Now print
    printDiv('printableArea');
    
    // Remove class after printing (optional, for future toggling)
    setTimeout(function() {
        $('.gl-story-text').removeClass('hide-on-summary-print');
    }, 500);
}

// row highlighted
$('#table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
        $(this).removeClass('highlighted');
    }else{
        $(this).addClass('highlighted');
    }
});
</script>

</div>
</body>
</html>