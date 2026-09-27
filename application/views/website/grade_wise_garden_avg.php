<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$old_season = (!empty($this->input->get('old_season', TRUE))) ? $this->input->get('old_season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_from = (!empty($this->input->get('sale_from', TRUE))) ? $this->input->get('sale_from', TRUE) : '';
$sale_to = (!empty($this->input->get('sale_to', TRUE))) ? $this->input->get('sale_to', TRUE) : '';
$garden_id = (!empty($this->input->get('garden_id', TRUE))) ? $this->input->get('garden_id', TRUE) : '';
$buyer_id = (!empty($this->input->get('buyer_id', TRUE))) ? $this->input->get('buyer_id', TRUE) : '';
$report_no = (!empty($this->input->get('report', TRUE))) ? $this->input->get('report', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';

$current_date_time= date("Y-m-d h:i:sa");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo ($report_no=='16') ? 'ALL GARDEN OFFERING SALES' : 'GARDEN SALES REPORT' ; ?></title>
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
		background-color: white !important;
		margin-top:10px;
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
</style>

<?php if($report_no=='14'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<!--<a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/grade_wise_garden_avg?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$garden_id.'&report=16'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Current Season</a>
		
		<?php
		$current_season = explode("-",$season);
		
		$old_season_sbt = ($current_season[0] - 1);
		$old_season_sbt2 = ($current_season[1] - 1);
		$old_season = "$old_season_sbt-$old_season_sbt2";
		
		$old_season_ext = ($current_season[0] + 1);
		$old_season_ext2 = ($current_season[1] + 1);
		$new_season = "$old_season_ext-$old_season_ext2";
		?>
		
		&nbsp; <a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/grade_wise_garden_avg?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$garden_id.'&old_season='.$old_season.'&report=17'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Old Season</a>
		
		&nbsp; <a class="btn-default" target="blink" href="<?php echo site_url('MY_Controller/grade_wise_garden_avg?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$garden_id.'&old_season='.$new_season.'&report=17'); ?>"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> New Season</a>-->
		
		&nbsp; <a class="btn-default" href="javascript:void(0);" onclick="return PrintPanel();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
	<div class="form-group col-sm-12">
		<table class="table table-sm" id="table">
			<thead>
				<tr>
					<th style="text-transform: uppercase; text-align:center; border:0px;">
					<span style="font-size:16px;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
					OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report_range`");

// create grade_sales_avg_report for current sale
$this->db->query("CREATE TABLE `grade_sales_avg_report` (
SELECT 
    invoice_entry.id, 
    invoice_entry.garden_id, 
    invoice_entry.grade_id, 
    invoice_entry.old_season, 
    invoice_entry.chest_number, 
    invoice_entry.net_kgs AS net_kgs, 
    (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
    (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
    (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount
FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create grade_sales_avg_report_range for sale range 1 to current sale_no
$this->db->query("CREATE TABLE `grade_sales_avg_report_range` (
SELECT 
    invoice_entry.id, 
	invoice_entry.sale_no,
    invoice_entry.garden_id, 
    invoice_entry.grade_id, 
    invoice_entry.old_season, 
    invoice_entry.chest_number, 
    invoice_entry.net_kgs AS net_kgs, 
    (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
    (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
    (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount
FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN 1 AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD (Current Sale - <?php print $sale_no; ?>)</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD (Upto Sale 1 to Sale <?php echo $sale_no; ?>)</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">%</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">%</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//garden breakdown
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
    
    // Get garden totals for current sale and range for percentage calculation
    $garden_current = $this->db->query("SELECT SUM(total_sold) AS total_bags, SUM(sold_kgs) AS total_kgs FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."'")->row();
    $garden_range = $this->db->query("SELECT SUM(total_sold) AS total_bags, SUM(sold_kgs) AS total_kgs FROM grade_sales_avg_report_range WHERE garden_id='".$row1['garden_id']."'")->row();
    
    $gt_sold_bags = $garden_current->total_bags;
    $gt_sold_kgs = $garden_current->total_kgs;
    $gt_range_sold_bags = $garden_range->total_bags;
    $gt_range_sold_kgs = $garden_range->total_kgs;
?>
<tr>
    <th colspan="10" style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
   	 <a target="blink" href="<?php echo site_url('MY_Controller/grade_wise_garden_avg?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id'].'&report=15'); ?>"><?php print $row1['garden_name']; ?></a>
	</th>
</tr>
<?php
//catalogue breakdown 
$gt_sold_amount = 0; 
$gt_range_sold_amount = 0; 

// Get all distinct catalogue names from range data to ensure we show all categories
$this->db->select("DISTINCT(tea_grade_setup.catalogue_name), 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order", FALSE);
$this->db->from('grade_sales_avg_report_range');
$this->db->join('tea_grade_setup','grade_sales_avg_report_range.grade_id = tea_grade_setup.id','left');
$this->db->where('grade_sales_avg_report_range.garden_id', $row1['garden_id']);
$this->db->order_by('catalogue_order ASC');
$catalogue_query = $this->db->get();
$catalogues = $catalogue_query->result_array();

foreach($catalogues as $row_catalogue){
	
//category breakdown
$sold_bags2 = 0;
$sold_kgs2 = 0;
$sold_amount2 = 0;
$range_sold_bags2 = 0;
$range_sold_kgs2 = 0;
$range_sold_amount2 = 0;

// Get all distinct grade categories from range data
$this->db->select("DISTINCT(tea_grade_setup.grade_category), 
	(CASE 
		WHEN grade_category='BROKENS' THEN 'Leaf'
		WHEN grade_category='DM' THEN 'Leaf'
		WHEN grade_category='FANNINGS' THEN 'Leaf'
		WHEN grade_category='DUST' THEN 'Dust'
		END
	) AS category_order", FALSE);
$this->db->from('grade_sales_avg_report_range');
$this->db->join('tea_grade_setup','grade_sales_avg_report_range.grade_id = tea_grade_setup.id','left');
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('grade_sales_avg_report_range.garden_id', $row1['garden_id']);
$this->db->order_by('category_order DESC');
$category_query = $this->db->get();
$categories = $category_query->result_array();

foreach($categories as $row2){

// grade breakdown
$sold_bags = 0;
$sold_kgs = 0;
$sold_amount = 0;
$range_sold_bags = 0;
$range_sold_kgs = 0;
$range_sold_amount = 0;

// Get all grades for this category from range data
$this->db->select("gs.grade_id, tea_grade_setup.grade_name");
$this->db->from('grade_sales_avg_report_range gs');
$this->db->join('tea_grade_setup', 'gs.grade_id = tea_grade_setup.id', 'left');
$this->db->where('gs.garden_id', $row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category', $row2['grade_category']);
$this->db->group_by('gs.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$grade_query = $this->db->get();
$grades = $grade_query->result_array();

foreach($grades as $row_grade){
    // Get current sale data for this grade
    $current_data = $this->db->query("
        SELECT 
            SUM(total_sold) AS sold_bags, 
            SUM(sold_kgs) AS sold_kgs, 
            SUM(total_amount) AS sold_amount,
            (CASE WHEN SUM(sold_kgs) > 0 THEN SUM(total_amount) / SUM(sold_kgs) ELSE 0 END) AS sold_avg
        FROM grade_sales_avg_report 
        WHERE garden_id='".$row1['garden_id']."' 
        AND grade_id='".$row_grade['grade_id']."'
    ")->row();
    
    // Get range data for this grade
    $range_data = $this->db->query("
        SELECT 
            SUM(total_sold) AS range_sold_bags, 
            SUM(sold_kgs) AS range_sold_kgs, 
            SUM(total_amount) AS range_sold_amount,
            (CASE WHEN SUM(sold_kgs) > 0 THEN SUM(total_amount) / SUM(sold_kgs) ELSE 0 END) AS range_sold_avg
        FROM grade_sales_avg_report_range 
        WHERE garden_id='".$row1['garden_id']."' 
        AND grade_id='".$row_grade['grade_id']."'
    ")->row();
    
    $current_sold_bags = $current_data->sold_bags ?: 0;
    $current_sold_kgs = $current_data->sold_kgs ?: 0;
    $current_sold_amount = $current_data->sold_amount ?: 0;
    $current_avg = $current_data->sold_avg ?: 0;
    
    $range_sold_bags_val = $range_data->range_sold_bags ?: 0;
    $range_sold_kgs_val = $range_data->range_sold_kgs ?: 0;
    $range_sold_amount_val = $range_data->range_sold_amount ?: 0;
    $range_avg = $range_data->range_sold_avg ?: 0;
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row_grade['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $current_sold_bags; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($current_sold_kgs,2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo ($gt_sold_kgs > 0 && $current_sold_kgs > 0) ? number_format(($current_sold_kgs*100/$gt_sold_kgs),2,'.','') : '0.00'; ?>%</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($current_avg,2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $range_sold_bags_val; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs_val,2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo ($gt_range_sold_kgs > 0 && $range_sold_kgs_val > 0) ? number_format(($range_sold_kgs_val*100/$gt_range_sold_kgs),2,'.','') : '0.00'; ?>%</td>
	<td style="text-align:right; border:0px;"><?php echo number_format($range_avg,2,'.',''); ?></td>
</tr>
<?php 
$sold_bags += $current_sold_bags; 
$sold_kgs += $current_sold_kgs; 
$sold_amount += $current_sold_amount;
$range_sold_bags += $range_sold_bags_val; 
$range_sold_kgs += $range_sold_kgs_val; 
$range_sold_amount += $range_sold_amount_val;
} // grade foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_kgs > 0 && $sold_kgs > 0) ? number_format(($sold_kgs*100/$gt_sold_kgs),2,'.','') : '0.00'; ?>%</th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
	<th style="text-align:right; border:0px;"><?php echo $range_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_kgs > 0 && $range_sold_kgs > 0) ? number_format(($range_sold_kgs*100/$gt_range_sold_kgs),2,'.','') : '0.00'; ?>%</th>
	<th style="text-align:right; border:0px;"><?php echo ($range_sold_amount > 0 && $range_sold_kgs >0) ? number_format($range_sold_amount / $range_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php  
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
$range_sold_bags2 += $range_sold_bags; 
$range_sold_kgs2 += $range_sold_kgs; 
$range_sold_amount2 += $range_sold_amount;
} // catagory foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_kgs > 0 && $sold_kgs2 > 0) ? number_format(($sold_kgs2*100/$gt_sold_kgs),2,'.','') : '0.00'; ?>%</th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
	<th style="text-align:right; border:0px;"><?php echo $range_sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_kgs > 0 && $range_sold_kgs2 > 0) ? number_format(($range_sold_kgs2*100/$gt_range_sold_kgs),2,'.','') : '0.00'; ?>%</th>
	<th style="text-align:right; border:0px;"><?php echo ($range_sold_amount2 > 0 && $range_sold_kgs2 >0) ? number_format($range_sold_amount2 / $range_sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_sold_amount += $sold_amount2;
$gt_range_sold_amount += $range_sold_amount2;
} // catalogue foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_range_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_range_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_amount > 0 && $gt_range_sold_kgs >0) ? number_format($gt_range_sold_amount / $gt_range_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> 
<!-- /row -->


<!-- GRAND TOTAL -->
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;">GRAND TOTAL</th>
   	<th colspan="4" style="text-align:center; border:0px;">SOLD (Current Sale - <?php print $sale_no; ?>)</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD (Upto Sale 1 to Sale <?php echo $sale_no; ?>)</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">%</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">%</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
// First calculate grand totals
$this->db->select("
    SUM(gs_avg.sold_bags) AS gt_sold_bags,
    SUM(gs_avg.sold_kgs) AS gt_sold_kgs,
    SUM(gs_avg.sold_amount) AS gt_sold_amount,
    SUM(gs_range.sold_bags) AS gt_range_sold_bags,
    SUM(gs_range.sold_kgs) AS gt_range_sold_kgs,
    SUM(gs_range.sold_amount) AS gt_range_sold_amount
");
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount FROM grade_sales_avg_report GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");
$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount FROM grade_sales_avg_report_range GROUP BY grade_id) gs_range", "invoice_entry.grade_id = gs_range.grade_id", "left");
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$gt_query = $this->db->get();
$gt_row = $gt_query->row_array();

$gt_sold_bags = $gt_row['gt_sold_bags'] ?? 0;
$gt_sold_kgs = $gt_row['gt_sold_kgs'] ?? 0;
$gt_sold_amount = $gt_row['gt_sold_amount'] ?? 0;
$gt_range_sold_bags = $gt_row['gt_range_sold_bags'] ?? 0;
$gt_range_sold_kgs = $gt_row['gt_range_sold_kgs'] ?? 0;
$gt_range_sold_amount = $gt_row['gt_range_sold_amount'] ?? 0;

// Get all distinct catalogue names from range data for grand total
$this->db->select("DISTINCT(tea_grade_setup.catalogue_name), 
    (CASE 
        WHEN catalogue_name='Leaf' THEN 'A'
        WHEN catalogue_name='Dust' THEN 'B'
        END
    ) AS catalogue_order", FALSE);
$this->db->from('grade_sales_avg_report_range');
$this->db->join('tea_grade_setup','grade_sales_avg_report_range.grade_id = tea_grade_setup.id','left');
$this->db->order_by('catalogue_order ASC');
$catalogue_query = $this->db->get();
$catalogues = $catalogue_query->result_array();

foreach($catalogues as $row_catalogue){
    
    //category breakdown
    $sold_bags2 = 0;
    $sold_kgs2 = 0;
    $sold_amount2 = 0;
    $range_sold_bags2 = 0;
    $range_sold_kgs2 = 0;
    $range_sold_amount2 = 0;

    // Get all distinct grade categories from range data for grand total
    $this->db->select("DISTINCT(tea_grade_setup.grade_category), 
        (CASE 
            WHEN grade_category='BROKENS' THEN 'Leaf'
            WHEN grade_category='DM' THEN 'Leaf'
            WHEN grade_category='FANNINGS' THEN 'Leaf'
            WHEN grade_category='DUST' THEN 'Dust'
            END
        ) AS category_order", FALSE);
    $this->db->from('grade_sales_avg_report_range');
    $this->db->join('tea_grade_setup','grade_sales_avg_report_range.grade_id = tea_grade_setup.id','left');
    $this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
    $this->db->order_by('category_order DESC');
    $category_query = $this->db->get();
    $categories = $category_query->result_array();

    foreach($categories as $row2){

        //grade breakdown
        $sold_bags = 0;
        $sold_kgs = 0;
        $sold_amount = 0;
        $range_sold_bags = 0;
        $range_sold_kgs = 0;
        $range_sold_amount = 0;

        // Get all grades for this category from range data for grand total
        $this->db->select("gs.grade_id, tea_grade_setup.grade_name");
        $this->db->from('grade_sales_avg_report_range gs');
        $this->db->join('tea_grade_setup', 'gs.grade_id = tea_grade_setup.id', 'left');
        $this->db->where('tea_grade_setup.grade_category', $row2['grade_category']);
        $this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
        $this->db->group_by('gs.grade_id');
        $this->db->order_by('tea_grade_setup.grade_name ASC');
        $grade_query = $this->db->get();
        $grades = $grade_query->result_array();

        foreach($grades as $row_grade){
            // Get current sale data for this grade for grand total
            $current_data = $this->db->query("
                SELECT 
                    SUM(total_sold) AS sold_bags, 
                    SUM(sold_kgs) AS sold_kgs, 
                    SUM(total_amount) AS sold_amount,
                    (CASE WHEN SUM(sold_kgs) > 0 THEN SUM(total_amount) / SUM(sold_kgs) ELSE 0 END) AS sold_avg
                FROM grade_sales_avg_report 
                WHERE grade_id='".$row_grade['grade_id']."'
            ")->row();
            
            // Get range data for this grade for grand total
            $range_data = $this->db->query("
                SELECT 
                    SUM(total_sold) AS range_sold_bags, 
                    SUM(sold_kgs) AS range_sold_kgs, 
                    SUM(total_amount) AS range_sold_amount,
                    (CASE WHEN SUM(sold_kgs) > 0 THEN SUM(total_amount) / SUM(sold_kgs) ELSE 0 END) AS range_sold_avg
                FROM grade_sales_avg_report_range 
                WHERE grade_id='".$row_grade['grade_id']."'
            ")->row();
            
            $current_sold_bags = $current_data->sold_bags ?: 0;
            $current_sold_kgs = $current_data->sold_kgs ?: 0;
            $current_sold_amount = $current_data->sold_amount ?: 0;
            $current_avg = $current_data->sold_avg ?: 0;
            
            $range_sold_bags_val = $range_data->range_sold_bags ?: 0;
            $range_sold_kgs_val = $range_data->range_sold_kgs ?: 0;
            $range_sold_amount_val = $range_data->range_sold_amount ?: 0;
            $range_avg = $range_data->range_sold_avg ?: 0;
?>
<tr>
    <td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row_grade['grade_name']; ?></td>
    <td style="text-align:right; border:0px;"><?php echo $current_sold_bags; ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($current_sold_kgs,2,'.',''); ?></td>
    <td style="text-align:right; border:0px;"><?php echo ($gt_sold_bags > 0 && $current_sold_bags > 0) ? number_format(($current_sold_bags * 100 / $gt_sold_bags), 2, '.', '') : '0.00'; ?>%</td>
    <td style="text-align:right; border:0px;"><?php echo number_format($current_avg,2,'.',''); ?></td>
    <td style="text-align:right; border:0px;"><?php echo $range_sold_bags_val; ?></td>
    <td style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs_val,2,'.',''); ?></td>
    <td style="text-align:right; border:0px;"><?php echo ($gt_range_sold_bags > 0 && $range_sold_bags_val > 0) ? number_format(($range_sold_bags_val * 100 / $gt_range_sold_bags), 2, '.', '') : '0.00'; ?>%</td>
    <td style="text-align:right; border:0px;"><?php echo number_format($range_avg,2,'.',''); ?></td>
</tr>
<?php 
            $sold_bags += $current_sold_bags; 
            $sold_kgs += $current_sold_kgs; 
            $sold_amount += $current_sold_amount;
            $range_sold_bags += $range_sold_bags_val; 
            $range_sold_kgs += $range_sold_kgs_val; 
            $range_sold_amount += $range_sold_amount_val;
        } // grade foreach
?>
<tr>
    <th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
    <th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
    <th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
    <th style="text-align:right; border:0px;"><?php echo ($gt_sold_bags > 0 && $sold_bags > 0) ? number_format(($sold_bags * 100 / $gt_sold_bags), 2, '.', '') : '0.00'; ?>%</th>
    <th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
    <th style="text-align:right; border:0px;"><?php echo $range_sold_bags; ?></th>
    <th style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs,2,'.',''); ?></th>
    <th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_bags > 0 && $range_sold_bags > 0) ? number_format(($range_sold_bags * 100 / $gt_range_sold_bags), 2, '.', '') : '0.00'; ?>%</th>
    <th style="text-align:right; border:0px;"><?php echo ($range_sold_amount > 0 && $range_sold_kgs >0) ? number_format($range_sold_amount / $range_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
        $sold_bags2 += $sold_bags; 
        $sold_kgs2 += $sold_kgs; 
        $sold_amount2 += $sold_amount;
        $range_sold_bags2 += $range_sold_bags; 
        $range_sold_kgs2 += $range_sold_kgs; 
        $range_sold_amount2 += $range_sold_amount;
    } // catagory foreach
?>
<tr>
    <th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
    <th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
    <th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
    <th style="text-align:right; border:0px;"><?php echo ($gt_sold_bags > 0 && $sold_bags2 > 0) ? number_format(($sold_bags2 * 100 / $gt_sold_bags), 2, '.', '') : '0.00'; ?>%</th>
    <th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
    <th style="text-align:right; border:0px;"><?php echo $range_sold_bags2; ?></th>
    <th style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs2,2,'.',''); ?></th>
    <th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_bags > 0 && $range_sold_bags2 > 0) ? number_format(($range_sold_bags2 * 100 / $gt_range_sold_bags), 2, '.', '') : '0.00'; ?>%</th>
    <th style="text-align:right; border:0px;"><?php echo ($range_sold_amount2 > 0 && $range_sold_kgs2 >0) ? number_format($range_sold_amount2 / $range_sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
} // catalogue foreach
?>
<tr>
    <th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
    <th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
    <th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
    <th style="text-align:right; border:0px;">100.00%</th>
    <th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
    <th style="text-align:right; border:0px;"><?php echo $gt_range_sold_bags; ?></th>
    <th style="text-align:right; border:0px;"><?php echo number_format($gt_range_sold_kgs,2,'.',''); ?></th>
    <th style="text-align:right; border:0px;">100.00%</th>
    <th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_amount > 0 && $gt_range_sold_kgs >0) ? number_format($gt_range_sold_amount / $gt_range_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
</tbody>
</table>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>


<?php if($report_no=='15'){ 

## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report_range`");

// create grade_sales_avg_report for current sale
$this->db->query("CREATE TABLE `grade_sales_avg_report` (
SELECT 
    invoice_entry.id, 
    invoice_entry.garden_id, 
    invoice_entry.grade_id, 
    invoice_entry.old_season, 
    invoice_entry.chest_number, 
    invoice_entry.net_kgs AS net_kgs, 
    (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
    (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
    (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount
FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

// create grade_sales_avg_report_range for sale range 1 to current sale_no
$this->db->query("CREATE TABLE `grade_sales_avg_report_range` (
SELECT 
    invoice_entry.id, 
    invoice_entry.sale_no,
    invoice_entry.garden_id, 
    invoice_entry.grade_id, 
    invoice_entry.old_season, 
    invoice_entry.chest_number, 
    invoice_entry.net_kgs AS net_kgs, 
    (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
    (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
    (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount
FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN 1 AND '".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>

<div class="row no-print">
    <div class="form-group col-sm-12 text-right">
        &nbsp; <a class="btn-default" href="javascript:void(0);" onclick="return PrintPanel();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-12">
        <table class="table table-sm" id="table">
            <thead>
                <tr>
                    <th style="text-transform: uppercase; text-align:center; border:0px;">
                    <span style="font-size:16px;"><?php print $website_title; ?></span><br />
                    <span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
                    OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
                </tr>
                <tr>
                    <th style="text-transform:uppercase; text-align:center; border:0px;">
                        Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-sm" id="table">
            <thead>
                <tr>
                    <th style="text-align:center; border:0px;"></th>
                    <th style="text-align:center; border:0px;"></th>
                    <th style="text-align:center; border:0px;"></th>
                    <th colspan="4" style="text-align:center; border:0px;">SOLD (Current Sale - <?php print $sale_no; ?>)</th>
                    <th colspan="4" style="text-align:center; border:0px;">SOLD (Upto Sale 1 to Sale <?php echo $sale_no; ?>)</th>
                </tr>
                <tr>
                    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
                    <th style="text-align:center; border:1px solid;">GRADE</th>
                    <th style="text-align:center; border:1px solid;">BAGS</th>
                    <th style="text-align:center; border:1px solid;">KGS</th>
                    <th style="text-align:center; border:1px solid;">%</th>
                    <th style="text-align:center; border:1px solid;">AVERAGE</th>
                    <th style="text-align:center; border:1px solid;">BAGS</th>
                    <th style="text-align:center; border:1px solid;">KGS</th>
                    <th style="text-align:center; border:1px solid;">%</th>
                    <th style="text-align:center; border:1px solid;">AVERAGE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //garden breakdown
                $n_total_kgs = 0;
                $n_net_kgs = 0;
                $n_chest_number = 0;

                //query
                $this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
                $this->db->from('invoice_entry');
                $this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
                $this->db->where('invoice_entry.catalogue_status', 'prepared');
                $this->db->where('invoice_entry.season', $season);
                $this->db->where('invoice_entry.auction_center', $auction_center);
                $this->db->where('invoice_entry.sale_no', $sale_no);
                $this->db->where('invoice_entry.garden_id', $garden_id);
                $this->db->where('invoice_entry.soft_delete', 1);
                $this->db->group_by('invoice_entry.garden_id');
                $this->db->order_by('garden_and_company_setup.garden_name ASC');
                $query = $this->db->get();
                $result1 = $query->result_array();
                
                if(count($result1) > 0){
                    foreach($result1 as $row1){
                        
                        // Get garden totals for current sale and range for percentage calculation
                        $garden_current = $this->db->query("SELECT SUM(total_sold) AS total_bags, SUM(sold_kgs) AS total_kgs FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."'")->row();
                        $garden_range = $this->db->query("SELECT SUM(total_sold) AS total_bags, SUM(sold_kgs) AS total_kgs FROM grade_sales_avg_report_range WHERE garden_id='".$row1['garden_id']."'")->row();
                        
                        $gt_sold_bags = $garden_current->total_bags;
                        $gt_sold_kgs = $garden_current->total_kgs;
                        $gt_range_sold_bags = $garden_range->total_bags;
                        $gt_range_sold_kgs = $garden_range->total_kgs;
                ?>
                <tr>
                    <th colspan="10" style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
                        <?php print $row1['garden_name']; ?>
                    </th>
                </tr>
                <?php
                        $gt_sold_amount = 0; 
                        $gt_range_sold_amount = 0; 

                        // Get all distinct catalogue names from range data to ensure we show all categories
                        $this->db->select("DISTINCT(tea_grade_setup.catalogue_name), 
                            (CASE 
                                WHEN catalogue_name='Leaf' THEN 'A'
                                WHEN catalogue_name='Dust' THEN 'B'
                                END
                            ) AS catalogue_order", FALSE);
                        $this->db->from('grade_sales_avg_report_range');
                        $this->db->join('tea_grade_setup','grade_sales_avg_report_range.grade_id = tea_grade_setup.id','left');
                        $this->db->where('grade_sales_avg_report_range.garden_id', $row1['garden_id']);
                        $this->db->order_by('catalogue_order ASC');
                        $catalogue_query = $this->db->get();
                        $catalogues = $catalogue_query->result_array();
                        
                        foreach($catalogues as $row_catalogue){
                            
                            $sold_bags2 = 0;
                            $sold_kgs2 = 0;
                            $sold_amount2 = 0;
                            $range_sold_bags2 = 0;
                            $range_sold_kgs2 = 0;
                            $range_sold_amount2 = 0;

                            // Get all distinct grade categories from range data
                            $this->db->select("DISTINCT(tea_grade_setup.grade_category), 
                                (CASE 
                                    WHEN grade_category='BROKENS' THEN 'Leaf'
                                    WHEN grade_category='DM' THEN 'Leaf'
                                    WHEN grade_category='FANNINGS' THEN 'Leaf'
                                    WHEN grade_category='DUST' THEN 'Dust'
                                    END
                                ) AS category_order", FALSE);
                            $this->db->from('grade_sales_avg_report_range');
                            $this->db->join('tea_grade_setup','grade_sales_avg_report_range.grade_id = tea_grade_setup.id','left');
                            $this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
                            $this->db->where('grade_sales_avg_report_range.garden_id', $row1['garden_id']);
                            $this->db->order_by('category_order DESC');
                            $category_query = $this->db->get();
                            $categories = $category_query->result_array();
                            
                            foreach($categories as $row2){

                                $sold_bags = 0;
                                $sold_kgs = 0;
                                $sold_amount = 0;
                                $range_sold_bags = 0;
                                $range_sold_kgs = 0;
                                $range_sold_amount = 0;

                                // Get all grades for this category from range data
                                $this->db->select("gs.grade_id, tea_grade_setup.grade_name");
                                $this->db->from('grade_sales_avg_report_range gs');
                                $this->db->join('tea_grade_setup', 'gs.grade_id = tea_grade_setup.id', 'left');
                                $this->db->where('gs.garden_id', $row1['garden_id']);
                                $this->db->where('tea_grade_setup.grade_category', $row2['grade_category']);
                                $this->db->group_by('gs.grade_id');
                                $this->db->order_by('tea_grade_setup.grade_name ASC');
                                $grade_query = $this->db->get();
                                $grades = $grade_query->result_array();
                                
                                foreach($grades as $row_grade){
                                    // Get current sale data for this grade
                                    $current_data = $this->db->query("
                                        SELECT 
                                            SUM(total_sold) AS sold_bags, 
                                            SUM(sold_kgs) AS sold_kgs, 
                                            SUM(total_amount) AS sold_amount,
                                            (CASE WHEN SUM(sold_kgs) > 0 THEN SUM(total_amount) / SUM(sold_kgs) ELSE 0 END) AS sold_avg
                                        FROM grade_sales_avg_report 
                                        WHERE garden_id='".$row1['garden_id']."' 
                                        AND grade_id='".$row_grade['grade_id']."'
                                    ")->row();
                                    
                                    // Get range data for this grade
                                    $range_data = $this->db->query("
                                        SELECT 
                                            SUM(total_sold) AS range_sold_bags, 
                                            SUM(sold_kgs) AS range_sold_kgs, 
                                            SUM(total_amount) AS range_sold_amount,
                                            (CASE WHEN SUM(sold_kgs) > 0 THEN SUM(total_amount) / SUM(sold_kgs) ELSE 0 END) AS range_sold_avg
                                        FROM grade_sales_avg_report_range 
                                        WHERE garden_id='".$row1['garden_id']."' 
                                        AND grade_id='".$row_grade['grade_id']."'
                                    ")->row();
                                    
                                    $current_sold_bags = $current_data->sold_bags ?: 0;
                                    $current_sold_kgs = $current_data->sold_kgs ?: 0;
                                    $current_sold_amount = $current_data->sold_amount ?: 0;
                                    $current_avg = $current_data->sold_avg ?: 0;
                                    
                                    $range_sold_bags_val = $range_data->range_sold_bags ?: 0;
                                    $range_sold_kgs_val = $range_data->range_sold_kgs ?: 0;
                                    $range_sold_amount_val = $range_data->range_sold_amount ?: 0;
                                    $range_avg = $range_data->range_sold_avg ?: 0;
                ?>
                <tr>
                    <td style="text-align:center; border:0px; text-transform:uppercase;"></td>
                    <td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row_grade['grade_name']; ?></td>
                    <td style="text-align:right; border:0px;"><?php echo $current_sold_bags; ?></td>
                    <td style="text-align:right; border:0px;"><?php echo number_format($current_sold_kgs,2,'.',''); ?></td>
                    <td style="text-align:right; border:0px;"><?php echo ($gt_sold_kgs > 0 && $current_sold_kgs > 0) ? number_format(($current_sold_kgs*100/$gt_sold_kgs),2,'.','') : '0.00'; ?>%</td>
                    <td style="text-align:right; border:0px;"><?php echo number_format($current_avg,2,'.',''); ?></td>
                    <td style="text-align:right; border:0px;"><?php echo $range_sold_bags_val; ?></td>
                    <td style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs_val,2,'.',''); ?></td>
                    <td style="text-align:right; border:0px;"><?php echo ($gt_range_sold_kgs > 0 && $range_sold_kgs_val > 0) ? number_format(($range_sold_kgs_val*100/$gt_range_sold_kgs),2,'.','') : '0.00'; ?>%</td>
                    <td style="text-align:right; border:0px;"><?php echo number_format($range_avg,2,'.',''); ?></td>
                </tr>
                <?php 
                                    $sold_bags += $current_sold_bags; 
                                    $sold_kgs += $current_sold_kgs; 
                                    $sold_amount += $current_sold_amount;
                                    $range_sold_bags += $range_sold_bags_val; 
                                    $range_sold_kgs += $range_sold_kgs_val; 
                                    $range_sold_amount += $range_sold_amount_val;
                                } // grade foreach
                ?>
                <tr>
                    <td style="text-align:center; border:0px; text-transform:uppercase;"></td>
                    <th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
                    <th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
                    <th style="text-align:right; border:0px;"><?php echo ($gt_sold_kgs > 0 && $sold_kgs > 0) ? number_format(($sold_kgs*100/$gt_sold_kgs),2,'.','') : '0.00'; ?>%</th>
                    <th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo $range_sold_bags; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs,2,'.',''); ?></th>
                    <th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_kgs > 0 && $range_sold_kgs > 0) ? number_format(($range_sold_kgs*100/$gt_range_sold_kgs),2,'.','') : '0.00'; ?>%</th>
                    <th style="text-align:right; border:0px;"><?php echo ($range_sold_amount > 0 && $range_sold_kgs >0) ? number_format($range_sold_amount / $range_sold_kgs,2,'.','') : '0.00'; ?></th>
                </tr>
                <?php  
                                $sold_bags2 += $sold_bags; 
                                $sold_kgs2 += $sold_kgs; 
                                $sold_amount2 += $sold_amount;
                                $range_sold_bags2 += $range_sold_bags; 
                                $range_sold_kgs2 += $range_sold_kgs; 
                                $range_sold_amount2 += $range_sold_amount;
                            } // category foreach
                ?>
                <tr>
                    <td style="text-align:center; border:0px; text-transform:uppercase;"></td>
                    <th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
                    <th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
                    <th style="text-align:right; border:0px;"><?php echo ($gt_sold_kgs > 0 && $sold_kgs2 > 0) ? number_format(($sold_kgs2*100/$gt_sold_kgs),2,'.','') : '0.00'; ?>%</th>
                    <th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo $range_sold_bags2; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo number_format($range_sold_kgs2,2,'.',''); ?></th>
                    <th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_kgs > 0 && $range_sold_kgs2 > 0) ? number_format(($range_sold_kgs2*100/$gt_range_sold_kgs),2,'.','') : '0.00'; ?>%</th>
                    <th style="text-align:right; border:0px;"><?php echo ($range_sold_amount2 > 0 && $range_sold_kgs2 >0) ? number_format($range_sold_amount2 / $range_sold_kgs2,2,'.','') : '0.00'; ?></th>
                </tr>
                <?php
                            $gt_sold_amount += $sold_amount2;
                            $gt_range_sold_amount += $range_sold_amount2;
                        } // catalogue foreach
                ?>
                <tr>
                    <td style="text-align:center; border:0px; text-transform:uppercase;"></td>
                    <th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
                    <th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
                    <th style="text-align:right; border:0px;"></th>
                    <th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo $gt_range_sold_bags; ?></th>
                    <th style="text-align:right; border:0px;"><?php echo number_format($gt_range_sold_kgs,2,'.',''); ?></th>
                    <th style="text-align:right; border:0px;"></th>
                    <th style="text-align:right; border:0px;"><?php echo ($gt_range_sold_amount > 0 && $gt_range_sold_kgs >0) ? number_format($gt_range_sold_amount / $gt_range_sold_kgs,2,'.','') : '0.00'; ?></th>
                </tr>
                <?php 
                    } // garden foreach
                } else {
                    print "<tr><td colspan='10' style='text-align:center;'><b>No matching record found.</b></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
<?php } ?>


<?php if($report_no=='16'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
	<div class="form-group col-sm-12">
		<table class="table table-sm" id="table">
			<thead>
				<tr>
					<th style="text-transform: uppercase; text-align:center; border:0px;">
					<span style="font-size:16px;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
					OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?> <br /> Current Season
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
$this->db->query("CREATE TABLE `grade_sales_avg_report` (
SELECT 

	invoice_entry.id, 
	invoice_entry.garden_id, 
	invoice_entry.grade_id, 
	invoice_entry.old_season, 
	invoice_entry.chest_number, 
	invoice_entry.net_kgs AS net_kgs, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount

FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>


<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="2" style="text-align:left; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//garden breakdown
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>

<tr>
    <th colspan="4" style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
    	<a target="blink" href="<?php echo site_url('MY_Controller/grade_wise_garden_avg?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id'].'&report=15'); ?>"><?php print $row1['garden_name']; ?></a>
	</th>
	<th colspan="4" style="font-size:14px !important; border:0px; text-align:right;"></th>
</tr>

<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.catalogue_name');
$this->db->order_by('catalogue_order ASC');
$query = $this->db->get();
foreach($query->result_array() as $row_catalogue){
	
//category breakdown
$g_chest_number = 0;
$g_net_kgs = 0;
$sold_bags2 = 0;
$sold_kgs2 = 0;
$sold_amount2 = 0;

//query
$this->db->select("tea_grade_setup.grade_category, 
	(CASE 
		WHEN grade_category='BROKENS' THEN 'Leaf'
		WHEN grade_category='DM' THEN 'Leaf'
		WHEN grade_category='FANNINGS' THEN 'Leaf'
		WHEN grade_category='DUST' THEN 'Dust'
		END
	) AS category_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.grade_category');
$this->db->order_by('category_order DESC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

//grade breakdown
$chest_number = 0;
$net_kgs = 0;
$sold_bags = 0;
$sold_kgs = 0;
$sold_amount = 0;

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."' AND old_season='".$season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> 
<!-- /row -->


<!-- GRAND TOTAL -->
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;">GRAND TOTAL</th>
	<th colspan="2" style="text-align:center; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.catalogue_name');
$this->db->order_by('catalogue_order ASC');
$query = $this->db->get();
foreach($query->result_array() as $row_catalogue){
	
//category breakdown
$g_chest_number = 0;
$g_net_kgs = 0;
$sold_bags2 = 0;
$sold_kgs2 = 0;
$sold_amount2 = 0;

//query
$this->db->select("tea_grade_setup.grade_category, 
	(CASE 
		WHEN grade_category='BROKENS' THEN 'Leaf'
		WHEN grade_category='DM' THEN 'Leaf'
		WHEN grade_category='FANNINGS' THEN 'Leaf'
		WHEN grade_category='DUST' THEN 'Dust'
		END
	) AS category_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.grade_category');
$this->db->order_by('category_order DESC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

//grade breakdown
$chest_number = 0;
$net_kgs = 0;
$sold_bags = 0;
$sold_kgs = 0;
$sold_amount = 0;

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE old_season='".$season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season', $season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
</tbody>
</table>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>

<?php if($report_no=='17'){ ?>
<div class="row">
	<div class="form-group col-sm-12 text-right no-print">
		<a class="btn-default" href="#" onClick="printDiv('printableArea')"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
	<div class="form-group col-sm-12">
		<table class="table table-sm" id="table">
			<thead>
				<tr>
					<th style="text-transform: uppercase; text-align:center; border:0px;">
					<span style="font-size:16px;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px; font-weight:normal;"><u><?php print $owner_short_address; ?></u></span> <br />
					OTC WISE GARDEN AVERAGE REPORT <br /> <?php print $auction_center; ?></th>
				</tr>
				<tr>
					<th style="text-transform:uppercase; text-align:center; border:0px;">
						Sale No: <?php print $sale_from; ?> - <?php print $sale_to; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
						<br /> Old / New Season
					</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<?php
## create temporary table - grade_sales_avg_report
$this->db->query("DROP TABLE IF EXISTS `grade_sales_avg_report`");

// create grade_sales_avg_report
$this->db->query("CREATE TABLE `grade_sales_avg_report` (
SELECT 

	invoice_entry.id, 
	invoice_entry.garden_id, 
	invoice_entry.grade_id, 
	invoice_entry.old_season, 
	invoice_entry.chest_number, 
	invoice_entry.net_kgs AS net_kgs, 
	(IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS total_sold,
	(IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS sold_kgs,
	(IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS total_amount

FROM invoice_entry 
JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 

WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
?>


<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
    <th style="text-align:center; border:0px;"></th>
	<th colspan="2" style="text-align:left; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
    <th style="text-align:center; border:1px solid;">GARDEN NAME</th>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//garden breakdown
$n_total_kgs = 0;
$n_net_kgs = 0;
$n_chest_number = 0;

//query
$this->db->select("invoice_entry.garden_id, garden_and_company_setup.garden_name"); 
$this->db->from('invoice_entry');
$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.garden_id');
$this->db->order_by('garden_and_company_setup.garden_name ASC');
$query = $this->db->get();
$result1 = $query->result_array();
if(count($result1) > 0){
foreach($result1 as $row1){
?>
<tr>
    <th colspan="4" style="font-size:14px !important; text-transform:uppercase; text-align:left; border:0px;">
    	<a target="blink" href="<?php echo site_url('MY_Controller/grade_wise_garden_avg?season='.$season.'&auction_center='.$auction_center.'&sale_no='.$sale_no.'&garden_id='.$row1['garden_id'].'&report=15'); ?>"><?php print $row1['garden_name']; ?></a>
	</th>
	<th colspan="4" style="font-size:14px !important; border:0px; text-align:right;"><?php print $old_season; ?></th>
</tr>
<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.catalogue_name');
$this->db->order_by('catalogue_order ASC');
$query = $this->db->get();
foreach($query->result_array() as $row_catalogue){
	
//category breakdown
$g_chest_number = 0;
$g_net_kgs = 0;
$sold_bags2 = 0;
$sold_kgs2 = 0;
$sold_amount2 = 0;

//query
$this->db->select("tea_grade_setup.grade_category, 
	(CASE 
		WHEN grade_category='BROKENS' THEN 'Leaf'
		WHEN grade_category='DM' THEN 'Leaf'
		WHEN grade_category='FANNINGS' THEN 'Leaf'
		WHEN grade_category='DUST' THEN 'Dust'
		END
	) AS category_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.garden_id', $row1['garden_id']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.grade_category');
$this->db->order_by('category_order DESC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

//grade breakdown
$chest_number = 0;
$net_kgs = 0;
$sold_bags = 0;
$sold_kgs = 0;
$sold_amount = 0;

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');

$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE garden_id='".$row1['garden_id']."' AND old_season='".$old_season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");

$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.garden_id',$row1['garden_id']);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?>: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<td style="text-align:center; border:0px; text-transform:uppercase;"></td>
	<th style="text-align:left; border:0px solid;">GARDEN TOTAL: </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php } ?>
</tbody>
</table>

<?php
}else{
	print "<p><b>No matching record found.</b></p>";
}
?>
</div>
</div> 
<!-- /row -->


<!-- GRAND TOTAL -->
<div class="row">
<div class="col-sm-12">
<table class="table table-sm" id="table">
<thead>
<tr>
    <th style="text-align:center; border:0px;">GRAND TOTAL</th>
	<th colspan="2" style="text-align:center; border:0px;">OFFERING</th>
	<th colspan="4" style="text-align:center; border:0px;">SOLD</th>
</tr>
<tr>
	<th style="text-align:center; border:1px solid;">GRADE</th>
    <th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
	<th style="text-align:center; border:1px solid;">BAGS</th>
    <th style="text-align:center; border:1px solid;">KGS</th>
    <th style="text-align:center; border:1px solid;">AMOUNT</th>
    <th style="text-align:center; border:1px solid;">AVERAGE</th>
</tr>
</thead>
<tbody>
<?php
//catalogue breakdown
$gt_chest_number = 0; 
$gt_net_kgs = 0;  
$gt_sold_bags = 0;  
$gt_sold_kgs = 0; 
$gt_sold_amount = 0; 

//query
$this->db->select("tea_grade_setup.catalogue_name, 
	(CASE 
		WHEN catalogue_name='Leaf' THEN 'A'
		WHEN catalogue_name='Dust' THEN 'B'
		END
	) AS catalogue_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.catalogue_name');
$this->db->order_by('catalogue_order ASC');
$query = $this->db->get();
foreach($query->result_array() as $row_catalogue){
	
//category breakdown
$g_chest_number = 0;
$g_net_kgs = 0;
$sold_bags2 = 0;
$sold_kgs2 = 0;
$sold_amount2 = 0;

//query
$this->db->select("tea_grade_setup.grade_category, 
	(CASE 
		WHEN grade_category='BROKENS' THEN 'Leaf'
		WHEN grade_category='DM' THEN 'Leaf'
		WHEN grade_category='FANNINGS' THEN 'Leaf'
		WHEN grade_category='DUST' THEN 'Dust'
		END
	) AS category_order
"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup','invoice_entry.grade_id = tea_grade_setup.id','left');
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season', $season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center', $auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('tea_grade_setup.catalogue_name', $row_catalogue['catalogue_name']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('tea_grade_setup.grade_category');
$this->db->order_by('category_order DESC');
$query = $this->db->get();
foreach($query->result_array() as $row2){

//grade breakdown
$chest_number = 0;
$net_kgs = 0;
$sold_bags = 0;
$sold_kgs = 0;
$sold_amount = 0;

//query
$this->db->select("invoice_entry.sale_no, tea_grade_setup.grade_name, sum(invoice_entry.net_kgs) AS net_kgs, sum(invoice_entry.chest_number) AS chest_number, gs_avg.sold_bags, gs_avg.sold_kgs, gs_avg.sold_amount, gs_avg.sold_avg"); 
$this->db->from('invoice_entry');
$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
$this->db->join("(SELECT grade_id, sum(total_sold) AS sold_bags, sum(sold_kgs) AS sold_kgs, sum(total_amount) AS sold_amount, (sum(total_amount) / sum(sold_kgs)) AS sold_avg FROM grade_sales_avg_report WHERE old_season='".$old_season."' GROUP BY grade_id) gs_avg", "invoice_entry.grade_id = gs_avg.grade_id", "left");
$this->db->where('invoice_entry.catalogue_status', 'prepared');
$this->db->where('invoice_entry.season',$season);
$this->db->where('invoice_entry.old_season',$old_season);
$this->db->where('invoice_entry.auction_center',$auction_center);
$this->db->where('invoice_entry.sale_no', $sale_no);
$this->db->where('tea_grade_setup.grade_category',$row2['grade_category']);
$this->db->where('invoice_entry.soft_delete', 1);
$this->db->group_by('invoice_entry.grade_id');
$this->db->order_by('tea_grade_setup.grade_name ASC');
$query = $this->db->get();
foreach($query->result_array() as $row3){
?>
<tr>
	<td style="text-align:left; border:0px; text-transform:uppercase;"><?php echo $row3['grade_name']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['chest_number']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['net_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo $row3['sold_bags']; ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_kgs'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_amount'],2,'.',''); ?></td>
	<td style="text-align:right; border:0px;"><?php echo number_format($row3['sold_avg'],2,'.',''); ?></td>
</tr>
<?php 
$net_kgs += $row3['net_kgs']; 
$chest_number += $row3['chest_number']; 
$sold_bags += $row3['sold_bags']; 
$sold_kgs += $row3['sold_kgs']; 
$sold_amount += $row3['sold_amount'];
} // grade foreach
?>
<tr>
	<th style="text-align:left; border:0px;"><?php print $row2['grade_category']; ?> : </th>
	<th style="text-align:right; border:0px;"><?php echo $chest_number; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount > 0 && $sold_kgs >0) ? number_format($sold_amount / $sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
<?php 
$g_chest_number += $chest_number; 
$g_net_kgs += $net_kgs; 
$sold_bags2 += $sold_bags; 
$sold_kgs2 += $sold_kgs; 
$sold_amount2 += $sold_amount;
} // catagory foreach
?>
<tr>
	<th style="text-align:left; border:0px solid; text-transform:uppercase;">TOTAL <?php print $row_catalogue['catalogue_name']; ?> : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $g_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($g_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $sold_bags2; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_kgs2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($sold_amount2,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($sold_amount2 > 0 && $sold_kgs2 >0) ? number_format($sold_amount2 / $sold_kgs2,2,'.','') : '0.00'; ?></th>
</tr>
<?php
$gt_chest_number += $g_chest_number; 
$gt_net_kgs += $g_net_kgs; 
$gt_sold_bags += $sold_bags2; 
$gt_sold_kgs += $sold_kgs2; 
$gt_sold_amount += $sold_amount2;
} // catalogue foreach
?>
<tr>
	<th style="text-align:left; border:0px solid;">GRAND TOTAL : </th>
	<th style="text-align:right; border:0px solid;"><?php echo $gt_chest_number; ?></th>
	<th style="text-align:right; border:0px solid;"><?php echo number_format($gt_net_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo $gt_sold_bags; ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_kgs,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo number_format($gt_sold_amount,2,'.',''); ?></th>
	<th style="text-align:right; border:0px;"><?php echo ($gt_sold_amount > 0 && $gt_sold_kgs >0) ? number_format($gt_sold_amount / $gt_sold_kgs,2,'.','') : '0.00'; ?></th>
</tr>
</tbody>
</table>
</div> <!-- col -->
</div> <!-- row -->
<?php } ?>

</div>
<!-- container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
function printDiv(divName) {
	var printContents = document.getElementById(divName).innerHTML;
	var originalContents = document.body.innerHTML;
	document.body.innerHTML = printContents;
	window.print();
	document.body.innerHTML = originalContents;
	window.location.reload();
}

//row highlighted
$('#table tbody tr').click(function(e) {
    if($(this).hasClass("highlighted")){
    	$(this).removeClass('highlighted');
    }else{
    	$(this).addClass('highlighted');
    }
});
</script>

</body>
</html>