<?php
if(isset($cup_testing_data)){
?>

<style>
.table{
	font-family:verdana;
	font-size:11px !important;
	width: 100% !important;
}

.table tr th{
	font-size:11px !important;
}

#example tbody tr:hover td {
  background-color: #E9967A;
}

#example{
	white-space:nowrap;
}

.table-responsive {
	width: 100%;
	margin-bottom: 15px;
	overflow-x: auto;
	overflow-y: auto;
	-webkit-overflow-scrolling: touch;
	-ms-overflow-style: -ms-autohiding-scrollbar;
	border: 1px solid #DDD;
}

#example tr.disabled td {
  background: rgba(0, 0, 0, 0.2);
   pointer-events: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

input:disabled,
input[disabled]{
  border: 1px solid #999999;
  background-color: #cccccc;
  color: red;
}

input:-moz-read-only { /* For Firefox */
  background-color: #cccccc;
}

input:read-only {
  background-color: #cccccc;
}
</style>

<div class="container-fluid">
<div class="row">
<div class="col-sm-12">

<?php
if(count($cup_testing_data) > 0){
	
	$season = $this->input->get('season', TRUE);
	$auction_center = $this->input->get('auction_center', TRUE);
	$catalouge_name = $this->input->get('catalouge_name', TRUE);
	$sale_no = $this->input->get('sale_no', TRUE);
	$garden_id = $this->input->get('garden_id', TRUE);
?>

<b>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb" style="background-color:#ceece0; color:#212321; font-size:14px; font-family:courier;">
	<li class="breadcrumb-item"><span class="glyphicon glyphicon-list"></span> Tea Cup Testing & Pre Auction </li>
	<li class="breadcrumb-item">Season: <?php print $season; ?></li>
	<li class="breadcrumb-item">Auction Center: <?php print $auction_center; ?></li>
	<li class="breadcrumb-item">Sale No: <?php print $sale_no; ?></li>
	<li class="breadcrumb-item">Catalogue: <?php print $catalouge_name; ?></li>
	<li class="breadcrumb-item" id="rating_msg"></li>
  </ol>
</nav>
</b>

<div class="table-responsive">
	<table class="table table-hover table-condensed table-striped table-bordered" id="example">
		<thead>
			<tr>
				<th style="text-align:center; vertical-align:middle;">Lot</th>
				<th style="text-align:center; vertical-align:middle;">Inv</th>
				<th style="text-align:center; vertical-align:middle;">Garden</th>
				<th style="text-align:center; vertical-align:middle;">Grade</th>
				<th style="text-align:center; vertical-align:middle;">Value</th>
				<th style="text-align:center; vertical-align:middle;">Rating <br> Liquor</th>
				<th style="text-align:center; vertical-align:middle;">Scale <br> Leaf</th>
			</tr>
		</thead>

		<tbody>
		<?php	
			foreach($cup_testing_data as $row1){
		?>

			<tr>
				<td style="text-align:center; vertical-align:middle;"><?php echo $row1['lot_no']; ?></td>
				<td style="text-align:center; vertical-align:middle;"><?php echo $row1['w_inv_no']; ?></td>
				<td style="text-align:left; vertical-align:middle;"><?php echo $row1['garden_name']; ?> <?php echo $row1['re_print']; ?></td>
				<td style="text-align:left; vertical-align:middle; text-transform: uppercase;"><?php echo $row1['grade_name']; ?></td>
				<td style="text-align:right; vertical-align:middle;"><?php echo number_format($row1['valuation'],2,'.',''); ?></td>
				<td style="text-align:left; vertical-align:middle;">
					<input type="text" id="liquor<?php echo $row1['id']; ?>" onblur="tea_cup_testing_data('<?php print $row1['id']; ?>',liquor<?php echo $row1['id']; ?>.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','liquor')" maxlength="8" value="<?php print $row1['liquor']; ?>" style="width:50px; text-transform: uppercase;" />
				</td>
				
				<td style="text-align:left; vertical-align:middle;">
					<input type="text" id="appearance<?php echo $row1['id']; ?>" onblur="tea_cup_testing_data('<?php print $row1['id']; ?>',appearance<?php echo $row1['id']; ?>.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','appearance')" value="<?php print $row1['appearance']; ?>" maxlength="8" style="width:50px; text-transform: uppercase;" />
				</td>
			</tr>	

		<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="form-group col-sm-4">
		<label for="Leaf Comments">Leaf Comments</label>
		<textarea class="form-control form-control-sm" id="leaf_comments" onblur="tea_cup_testing_data2('<?php print $season; ?>','<?php print $catalouge_name; ?>','<?php print $sale_no; ?>','<?php print $garden_id; ?>',leaf_comments.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','leaf_comments')" rows="3"><?php print $cup_testing_data2['leaf_comments'] ;?></textarea>
	</div>
	
	<div class="form-group col-sm-4">
		<label for="Liquor Comments">Liquor Comments</label>
		<textarea class="form-control form-control-sm" id="liquor_comments" onblur="tea_cup_testing_data2('<?php print $season; ?>','<?php print $catalouge_name; ?>','<?php print $sale_no; ?>','<?php print $garden_id; ?>',liquor_comments.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','liquor_comments')" rows="3"><?php print $cup_testing_data2['liquor_comments'] ;?></textarea>
	</div>
	
	<div class="form-group col-sm-4">
		<label for="Infusion Comments">Infusion Comments</label>
		<textarea class="form-control form-control-sm" id="infusion" onblur="tea_cup_testing_data2('<?php print $season; ?>','<?php print $catalouge_name; ?>','<?php print $sale_no; ?>','<?php print $garden_id; ?>',infusion.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','infusion')" rows="3"><?php print $cup_testing_data2['infusion'] ;?></textarea>
	</div>
	
</div>

		<?php }else{ ?>

			<div class="row">
				<div class="col-sm-12">
					<h1 style="color: #444; background-color: transparent; border-bottom: 1px solid #D0D0D0; font-size: 19px; 
					font-weight: normal; margin: 0 0 14px 0; padding: 14px 15px 10px 15px;"> An Error Was Encountered </h1> 
					<p style="margin: 12px 15px 12px 15px;"> No matching record found. Please enter new lot in invoice entry. </p>
				</div>
			</div>
			
		<?php	
		}
		?>

</div> <!-- col -->
</div> <!-- row -->
</div> <!-- container -->

<script>
$("document").ready(function() {
	
	// page reload
	window.onblur= function() {window.onfocus= function () {location.reload(true)}};
	
	// DataTable
    var table = $('#example').DataTable({
		"searching": true,
		"fixedHeader": true,
		"ordering": false,
		"paging": false
	});
	// end

});

// set number format
function numberOnly(id) {
    var element = document.getElementById(id);
    var regex = /[^0-9]/gi;
    // This removes any other character but numbers as entered by user
    element.value = element.value.replace(regex, "");
}
</script>

<?php
}
?>