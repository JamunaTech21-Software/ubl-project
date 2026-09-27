<?php
if(isset($resultq1)){
?>

<style>
.table{
	font-family:verdana;
	font-size:12px !important;
	width: 100% !important;
}
#table tbody tr:hover td {
  background-color: #E9967A;
}
#table{
	border-color:black;
	white-space:nowrap;
}
#table tr td{
	border-color:black;
}
#table tr th{
	border-color:black;
}
a{
	cursor:pointer;
}	
</style>
<div class="container-fluid">
<div class="row">
<div class="col-sm-12">
  
<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<tr>
    <th id="sale_season" style="font-size:14px; text-align:left;"></th>
</tr>
<tr>
    <td>
		Toggle column: 
		<a class="toggle-vis" data-column="0">Brokers</a> - 
		<a class="toggle-vis" data-column="1">Lot No</a> - 
		<a class="toggle-vis" data-column="2">Garden</a> - 
		<a class="toggle-vis" data-column="3">Origin</a> - 
		<a class="toggle-vis" data-column="4">Catagory</a> - 
		<a class="toggle-vis" data-column="5">Grade</a> -
		<a class="toggle-vis" data-column="6">W/H</a> - 
		<a class="toggle-vis" data-column="7">Inv No.</a> - 
		<a class="toggle-vis" data-column="8">N/W</a> - 
		<a class="toggle-vis" data-column="9">T/W</a> - 
		<a class="toggle-vis" data-column="10">Print</a> - 
		<a class="toggle-vis" data-column="11">Bags</a> - 
		<a class="toggle-vis" data-column="12">Value</a> - 
		<a class="toggle-vis" data-column="13">My Bid</a> - 
		<a class="toggle-vis" data-column="14">Buy Share</a> - 
		<a class="toggle-vis" data-column="15">My Rating</a>
	</td>
</tr>
</table>

<table class="table table-hover table-condensed table-striped table-bordered" id="example">
<thead>
<tr>				
	<th>Bro<br>kers</th>
	<th>Lot No</th>
	<th>Garden</th>
	<th>Origin</th>
	<th>Catagory</th>
	<th>Grade</th>
	<th>W/H</th>
	<th>Inv No.</th>
	<th>N/W</th>
	<th>T/W</th>
	<th>Print</th>
	<th>Bags</th>
	<th>Value</th>
	<th>My Bid</th>
	<th>Buy Share</th>
	<th>My Rating</th>
</tr>

<tr>
	<th>Bro<br>kers</th>
	<th>Lot No</th>
	<th>Garden</th>
	<th>Origin</th>
	<th>Catagory</th>
	<th>Grade</th>
	<th>W/H</th>
	<th>Inv No.</th>
	<th>N/W</th>
	<th>T/W</th>
	<th>Print</th>
	<th>Bags</th>
	<th>Value</th>
	<th>My Bid</th>
	<th>Buy Share</th>
	<th>My Rating</th>
</tr>
</thead>

<tbody>

<?php
$season = '';
$sale_no = '';

if(count($resultq1) > 0){
foreach($resultq1 as $result){

$season = $result['season'];
$sale_no = $result['sale_no'];
$sale_date = date("d-m-Y",strtotime($result['sale_date']));
?>

<tr>
	<td title="<?php print $result['brokers_name']; ?>"><?php print $result['short_key_code']; ?></td>
	<td><?php print $result['lot_no']; ?></td>
	<td><?php print $result['garden_name']; ?></td>
	<td><?php print $result['origin']; ?></td>
	<td></td>
    <td><?php print $result['grade_name']; ?></td>
	<td><?php print $result['warehouse_name']; ?></td>
    <td><?php print $result['w_inv_no']; ?></td>
    <td style="text-align:right;"><?php print $result['net_wt_per_bag']; ?></td>
	<td style="text-align:right;"><?php print number_format($result['net_kgs'],2,'.',''); ?></td>
	<td><?php print $result['print']; ?></td>
	<td style="text-align:right;"><?php print number_format($result['chest_number'],2,'.',''); ?></td>
	<td style="text-align:right;"><?php print number_format($result['valuation'],2,'.',''); ?></td>
	
    <td style="text-align:right;">
		<div class="input-group">
			<span class="input-group-addon"><b id="bid_msg<?php echo $result['id']; ?>"><?php if(!empty($result['my_bid'])){ print $result['my_bid']; }else{ print "0"; } ?></span>
			<input type="number" step="0.01" class="form-control input-sm" style="width:100px;" id="my_bid<?php echo $result['id']; ?>" placeholder="my bid" onkeyup="my_bid_store('<?php print $result['id']; ?>',my_bid<?php echo $result['id']; ?>.value,buy_share<?php echo $result['id']; ?>.value,my_rating<?php echo $result['id']; ?>.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','bid_msg<?php echo $result['id']; ?>','<?php print $ubl_web_user_id; ?>')" title="LOT <?php echo $result['lot_no']; ?>">
		</div>
	</td>
	
    <td style="text-align:right;">
		<div class="input-group">
			<span class="input-group-addon"><b id="share_msg<?php echo $result['id']; ?>"><?php if(!empty($result['buy_share'])){ print $result['buy_share']; }else{ print "0"; } ?></span>
			<input type="number" step="0.01" class="form-control input-sm" style="width:100px;" id="buy_share<?php echo $result['id']; ?>" placeholder="buy share" onkeyup="my_bid_store('<?php print $result['id']; ?>',my_bid<?php echo $result['id']; ?>.value,buy_share<?php echo $result['id']; ?>.value,my_rating<?php echo $result['id']; ?>.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','share_msg<?php echo $result['id']; ?>','<?php print $ubl_web_user_id; ?>')" title="LOT <?php echo $result['lot_no']; ?>">
		</div>
	</td>
	
    <td>
		<div class="input-group">
			<span class="input-group-addon"><b id="rating_msg<?php echo $result['id']; ?>"><?php if(!empty($result['my_rating'])){ print $result['my_rating']; }else{ print "N/R"; } ?></b></span>
			<input type="text" class="form-control input-sm" style="width:70px;" id="my_rating<?php echo $result['id']; ?>" placeholder="my rating" onkeyup="my_bid_store('<?php print $result['id']; ?>',my_bid<?php echo $result['id']; ?>.value,buy_share<?php echo $result['id']; ?>.value,my_rating<?php echo $result['id']; ?>.value,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','rating_msg<?php echo $result['id']; ?>','<?php print $ubl_web_user_id; ?>')" title="LOT <?php echo $result['lot_no']; ?>">
		</div>
	</td>
</tr>

<?php
} // foreach

}else{
	print "<tr><td colspan=30>Sorry !! upcoming sale schedule not found.</td></tr>";
}
?>

</tbody>
</table>
</div>

<script>
$(document).ready(function() {
	
	// DataTable
    var table = $('#example').DataTable({
		"searching": true,
		"fixedHeader": true,
		"ordering": false,
		"paging": false
	});
	// end
	
    // Setup - add a text input to each footer cell
    $('#example thead tr:eq(1) th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" class="form-control input-sm" id="column_search" />' );
    } );
 
	// Apply the search
    $( '#example thead').on( 'keyup', "#column_search",function () {
        table
            .column( $(this).parent().index() )
            .search( this.value )
            .draw();
    });
	// end
	
	// Show / hide columns dynamically
	$('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Toggle the visibility
        column.visible( ! column.visible() );
    });
	// end
    
} );
</script>

</div> <!-- end col -->
</div> <!-- end row -->
</div> <!-- /container -->

<script>
$("document").ready(function() {
    $("#sale_season").html('<font color=green>Pre-Auction (Buyer Interface) </font> &nbsp; / &nbsp; Season: <?php print $season; ?> &nbsp; / &nbsp; Sale No: <?php print $sale_no; ?> &nbsp; / &nbsp; Auction Date: <?php print $sale_date; ?>');
});
</script>

<?php
} // first if brace
?>