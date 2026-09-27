<!DOCTYPE html>
<html lang="en">
<head>
<title>VALUATION ENTRY</title>
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
	background-color: white !important;
}
.row{ margin:auto !important; }
.form-control{ font-size:12px; font-weight:bold; font-family:verdana !important; }
</style>
</head>
<body>
<div class="container">

<?php
// get html value
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '0';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
$catalouge_name = (!empty($this->input->get('catalouge_name', TRUE))) ? $this->input->get('catalouge_name', TRUE) : '';

// Sale schedule
$query = $this->db->query("SELECT * FROM sale_schedule_setup WHERE sale_no='".$sale_no."' AND season_name='".$season."' AND auction_center='".$auction_center."' ");
$sale_sch_data = $query->row_array();
$sale_date = $sale_sch_data['sale_date'];

include("json_function.php");
?>

<!-- start row -->
<div class="row">
	<div class="form-group col-sm-4">
		<p style="font-weight:bold; text-transform: uppercase; text-align:center;">SALE DATE: <b><?php print date("d-m-Y", strtotime($sale_sch_data['sale_date'])); ?></b></p>
	</div>

	<div class="form-group col-sm-4">
		<p style="font-weight:bold; text-transform: uppercase; text-align:center;"><b style="font-size:16px;"><?php print $website_title; ?></b>
		<br> VALUATION FOR SALE NO. <b><?php print $sale_no; ?></b></p>
		<p style="font-weight:bold; text-transform: uppercase; text-align:center;">SEASON <b><?php print $season; ?></b></p>
		<p style="font-weight:bold; text-transform: uppercase; text-align:center;"><b><?php print $auction_center; ?></b></p>
	</div>

	<div class="form-group col-sm-4">
		<p style="font-weight:bold; text-transform: uppercase; text-align:center;"><b><?php print $catalouge_name; ?></b></p>
	</div>
</div> 
<!-- /row -->

<!-- start row -->
<div class="row">
<div class="col-sm-12">
<div class="form-group row">
<?php	
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
foreach($result as $row1){
$readonly = ($row1['valuation_publish']=='Yes') ? 'readonly' : '';
?>

<div style="border-bottom:1px solid #CCCCCC; padding:10px;" class="col-xs-2">
	<label for="ex1">LOT <?php echo $row1['lot_no']; ?> <b id="msg<?php echo $row1['lot_no']; ?>"></b></label>
	<input <?php print $readonly; ?> type="text" 
	class="form-control input-sm" 
	id="<?php echo $row1['lot_no']; ?>" 
	placeholder="Enter Valuation" 
	value="<?php if(!empty($row1['valuation'])){ print $row1['valuation']; } ?>" 
	onblur="lot_valuation_store('<?php print $season; ?>','<?php print $auction_center; ?>','<?php print $sale_no; ?>','<?php print $row1['lot_no']; ?>','<?php print $row1['net_kgs']; ?>',this.value)" 
	onfocus="this.select()" onkeypress="return isNumberKey(event)" />
</div>
<?php } ?>	

<?php }else{ ?> <p> No matching record found. </p> <?php } ?>
</div> <!-- form-group row -->
</div> <!-- col -->
</div> <!-- row -->

<script>
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

</div> 
<!-- /container -->

</body>
</html>