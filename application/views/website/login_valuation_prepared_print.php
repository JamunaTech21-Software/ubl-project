<!DOCTYPE html>
<html lang="en">
<head>
<title>VALUATION PRINT</title>
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
// get html value
$row_limit_pp = 49;
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$catalouge_name = (!empty($this->input->get('catalouge_name', TRUE))) ? $this->input->get('catalouge_name', TRUE) : '';

// Sale schedule
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE sale_no='".$sale_no."' AND season_name='".$season."' AND auction_center='".$auction_center."' ");
$sale_sch_data = $query->row_array();
$sale_date = $sale_sch_data['sale_date'];
?>

<div class="row no-print">
	<div class="form-group col-sm-12 text-right">
		<a href="javascript:fn_export_excel()"><span style="cursor:pointer;"><span class="glyphicon glyphicon-download" aria-hidden="true"></span> Export to Excel</span></a>
		&nbsp;
		<a href="#" onClick="window.print();"><span style="cursor:pointer;"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</span></a>
	</div>
</div>

<?php
// query
$this->db->select("lot_no, valuation, valuation_publish, net_kgs"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
($catalouge_name=='Full Catalogue') ? '' : $this->db->where('catalouge_name', $catalouge_name);	
$this->db->where('sale_no', $sale_no);
$this->db->where('soft_delete', 1);
$this->db->group_by('lot_no');
$this->db->order_by('lot_no ASC');
$query = $this->db->get();
$result = $query->result_array();
if(count($result) > 0){

// manipulate row section
$row_start = 1;
$row_end = $row_limit_pp;
$row_col = 5;
$pp_limit = $row_end * $row_col;
$upper_limit = $row_start;
$lower_limit = $row_end;
$total_records = count($result);
$i = 1;
$spp_limit = 1;
$page = 0;

// get valuation query
foreach($result as $row1){

if($i==$spp_limit){	
$epp_limit = $spp_limit + ($pp_limit - 1);
$page += 1;
?>

<!-- start top heading row -->
<div class="row">
<div class="col-sm-12">
<table style="width:100%; border-bottom:1px solid;">
	<thead>
		<tr>
			<th style="font-weight:bold; border:0px solid; text-transform: uppercase; text-align:left;"><b style="font-size:15px;"><?php print $website_title; ?></b></th>
			<td style="border:0px solid; text-align:left; font-size:13px;"><?php print $owner_short_address; ?></td>
			<td style="text-align:right; font-size:13px;">Page <?php print $page; ?></td>
		</tr>	
		<tr>
			<td style="border:0px solid; text-align:left; font-size:13px;">VALUATION FOR SALE NO. <?php print $sale_no; ?></td>
			<td style="border:0px solid; text-align:left; font-size:13px;">SEASON <?php print $season; ?> &nbsp; <?php print $auction_center; ?></td>
			<td style="text-align:right;">Sale Date: <?php print date("d-m-Y", strtotime($sale_sch_data['sale_date'])); ?></td>
		</tr>
	</thead>
</table>
</div> <!-- /col -->
</div> <!-- /row -->

<!-- valuation report :: after catalouge prepared -->
<div class="row">
<?php } // if ?>

<?php
// limit of show heading	
if($i==$upper_limit){
$lower_limit = $upper_limit + ($row_end - 1);	
?>	
<div class="col-sm-2">
<table style="width:100%;">
<tr>
	<th style="text-align:center; font-size:13px;;">LOT</th>
	<th style="text-align:right; font-size:13px;;">VALUE</th>
</tr>
<?php } //end ?>
<tr>
	<td style="text-align:center; font-size:14px;;"><?php echo $row1['lot_no']; ?></td> 
	<td style="text-align:right; font-size:14px;;"><?php echo number_format($row1['valuation'],1,'.',''); ?> </td>
</tr>
<?php
// limit of close table / div
if($i==$lower_limit || $i==$total_records){ 
$upper_limit = $lower_limit + $row_start;
?>
</table>
<?php print ($i==$total_records) ? "*** END ***" : ""; ?>
</div> <!-- /col -->
<?php } ?>

<?php if($i==$epp_limit || $i==$total_records){ ?>
</div> <!-- /row -->
<?php
$spp_limit = $epp_limit + 1;
}
?>

<?php
// post assign value 
$i++;
 
} // end foreach loop 
?>

<script>
function fn_export_excel(){
	window.open("<?php echo site_url(); ?>/Valuation_export/createexcel?season=<?php print $season; ?>&auction_center=<?php print $auction_center; ?>&sale_no=<?php print $sale_no; ?>","_blink");
}
</script>

<?php
}else{
	
	print "<div id=\"page\"><h1 style=\"color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;\">An Error Was Encountered</h1> 
	<p style=\"margin: 12px 15px 12px 15px;\"> <b>No matching record found. Please Lock the Catalogue Page First. </b></p></div>";
}
?>
</div> 
<!-- /container -->

</body>
</html>