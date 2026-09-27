<style>
.table{
	font-family:verdana;
	font-size:9px !important;
	width: 100% !important;
}
.table tr th{
	font-size:11px !important;
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

.table-responsive {
	width: 100%;
	margin-bottom: 15px;
	overflow-x: auto;
	overflow-y: auto;
	-webkit-overflow-scrolling: touch;
	-ms-overflow-style: -ms-autohiding-scrollbar;
	border: 1px solid #DDD;
}

#table tr.disabled td {
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
  color: #666666;
}
</style>

<div class="container-fluid">
<div class="row">
<div class="col-sm-12">

<b>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb" style="background-color:#2b8d54; color:white; font-size:14px; font-family:verdana;">
	<li class="breadcrumb-item" style="font-size:16px;"> Logo Welcome Auctioneer </li>
	<li id="countdown" class="breadcrumb-item" style="color:yellow;;">Active lots: 0</li>
	<li id="session_countdown" class="breadcrumb-item">Session time remaining: 0</li>
  </ol>
</nav>
</b>

<b>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb" style="background-color:#ceece0; color:#408147; font-size:14px; font-family:verdana;">
	<li id="heading_2" class="breadcrumb-item"></li>
	<li id="heading_3" class="breadcrumb-item"></li>
	<li id="heading_4" class="breadcrumb-item"></li>
	<li id="heading_5" class="breadcrumb-item"></li>
	<li id="heading_6" class="breadcrumb-item"></li>
  </ol>
</nav>
</b>
  	
<!-- LIVE Auction -->
<div class="table-responsive" style="height:300px;">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>				
	<th>Bro<br>kers</th>
	<th>Lot No</th>
	<th>Garden</th>
	<th>Grade</th>
	<th>Print</th>
	<th>Bags</th>
	<th>Value</th>
	<th>BQ</th>
	<th>LP</th>
	<th>HBP</th>
	<th>Bidder</th>
	<th><b id="rp_msg"></b> <br> R/P</th>
	<th>R/P &plusmn;</th>
	<th>BP &plusmn;</th>
	<th>M/A<br>A/A</th>
	<th><b id="sold_msg"></b> <br> M.Sold</th>
	<th><b id="wd_msg"></b> <br> W/D</th>
	<th><b id="out_msg"></b> <br> Out</th>
	<th>LSP</th>
	<th>Testing</th>
	<th>Comment</th>
	<th>Status</th>
	<th>W/H</th>
	<th>Origin</th>
	<th>Category</th>
	<th>Inv. No</th>
	<th>N/W</th>
	<th>T/W</th>
</tr>
</thead>

<tbody id="active_lots_list">
<tr>
	<td colspan="50" style="font-size:14px;" id="session_start_td"></td>
</tr>
</tbody>

</table>
</div>

<script>
function numberOnly(id) {
    var element = document.getElementById(id);
    var regex = /[^0-9]/gi;
    // This removes any other character but numbers as entered by user
    element.value = element.value.replace(regex, "");
}

// live stream
function fn_live_stream(){
	
	var lot_id 		= '';
	var hbp 		= '';
	var bidder		= '';
	var buyq 		= '';
	var buyer_id 	= '';
	var no_of_buyer = '';
	var rp 			= '';
	var s_wd 		= '';
	var mab 		= '';
	
	if(typeof(EventSource) !== "undefined") {
	  var source = new EventSource("<?php print site_url("MY_Controller/auction_brokers_live_stream"); ?>",{ withCredentials: true });
	  source.onmessage = function(event) {
		var str = event.data;
		var res = str.split(":");
		
		// split data
		var lot_id 	= parseFloat(res[0]);
		var hbp 	= parseFloat(res[1]);
		var bq 		= parseFloat(res[2]);
		var sq1 	= parseFloat(res[3]);
		var sq2 	= parseFloat(res[4]);
		var bq_mab 	= parseFloat(res[5]);
		var sq_mab 	= parseFloat(res[6]);
		var bidder	= res[7];
		var buyq	= parseFloat(res[8]); // total buy qty
		var rp 		= parseFloat(res[9]);
		var s_wd 	= res[10]; // sold/withdraw
		var first_bidder = res[11];
		
		// print data
		if(lot_id != '0'){
			
			var lp = parseFloat(document.getElementById("lp"+lot_id).innerHTML);
			var bp = parseFloat(document.getElementById("bp"+lot_id).innerHTML);
			
			// return data
			if(parseFloat(hbp) < parseFloat(rp)){
				document.getElementById("lp"+lot_id).innerHTML 		= hbp;
				document.getElementById("ma_aa"+lot_id).disabled 	= false;
				document.getElementById("sold"+lot_id).disabled 	= false; // temporary
			}else{
				document.getElementById("hbp"+lot_id).innerHTML 	= hbp;
				document.getElementById("ma_aa"+lot_id).disabled 	= true;
				document.getElementById("lp"+lot_id).innerHTML 		= 0;
			}

			if(s_wd=='sold' || s_wd=='wd' || s_wd=='out'){
				document.getElementById("sold"+lot_id).disabled 	= true;
				document.getElementById("wd"+lot_id).disabled 		= true;
				document.getElementById("out"+lot_id).disabled 		= true;
				document.getElementById("ma_aa"+lot_id).disabled 	= true;
			}else{
				document.getElementById("sold"+lot_id).disabled 	= false;
				document.getElementById("wd"+lot_id).disabled 		= false;
				document.getElementById("out"+lot_id).disabled 		= false;
			}	
			
			document.getElementById("bidder"+lot_id).innerHTML 	= bidder;
			document.getElementById("buyq"+lot_id).innerHTML 	= buyq;
			
			if ($("#rp"+lot_id).is(":focus")){
				// do nothing
			}else{	
				document.getElementById("rp"+lot_id).value 			= rp;
			}	
			
			document.getElementById("status"+lot_id).innerHTML 		= s_wd;
			document.getElementById("rp_minus"+lot_id).innerHTML 	= parseFloat(hbp) - parseFloat(rp);
			document.getElementById("bp_minus"+lot_id).innerHTML 	= parseFloat(hbp) - bp;
		}
		
		// end	
	  };
	}else{
	  document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
	}

}
</script>

<hr style="border-top: 1px dotted red;">

<!-- +++++++++++++++++ -->
<!-- Brokers Pre-Auction -->	
<!-- +++++++++++++++++ -->

<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<tr>
    <td>
		Toggle column: 
		<a class="toggle-vis" data-column="0">Brokers</a> - 
		<a class="toggle-vis" data-column="1">Lot No</a> - 
		<a class="toggle-vis" data-column="2">Garden</a> - 
		<a class="toggle-vis" data-column="3">Grade</a> -
		<a class="toggle-vis" data-column="4">Print</a> - 
		<a class="toggle-vis" data-column="5">Bags</a> - 
		<a class="toggle-vis" data-column="6">Value</a> - 
		<a class="toggle-vis" data-column="7">BQ</a> - 
		<a class="toggle-vis" data-column="8">HBP</a> - 
		<a class="toggle-vis" data-column="9">Bidder</a> - 
		<a class="toggle-vis" data-column="10">R/P</a> - 
		<a class="toggle-vis" data-column="11">M/A-A/A</a> - 
		<a class="toggle-vis" data-column="12">M.Sold</a> - 
		<a class="toggle-vis" data-column="13">Out</a> - 
		<a class="toggle-vis" data-column="14">W/D</a> - 
		<a class="toggle-vis" data-column="15">HBP-RP</a> -
		<a class="toggle-vis" data-column="16">HBP-BP</a> -
		<a class="toggle-vis" data-column="17">LSP</a> -
		<a class="toggle-vis" data-column="18">Testing</a> -
		<a class="toggle-vis" data-column="19">Comment</a> -
		<a class="toggle-vis" data-column="20">Status</a> -
		<a class="toggle-vis" data-column="21">W/H</a> -
		<a class="toggle-vis" data-column="22">Origin</a> - 
		<a class="toggle-vis" data-column="23">Category</a> - 
		<a class="toggle-vis" data-column="24">Inv. No</a> - 
		<a class="toggle-vis" data-column="25">N/W</a> - 
		<a class="toggle-vis" data-column="26">T/W</a>
	</td>
</tr>
</table>

<div class="table-responsive" style="height:300px;">
<table class="table table-hover table-condensed table-striped table-bordered" id="example">
<thead>
<tr>				
	<th>Bro<br>kers</th>
	<th>Lot No</th>
	<th>Garden</th>
	<th>Grade</th>
	<th>Print</th>
	<th>Bags</th>
	<th>Value</th>
	<th>R/P</th>
	<th>M/A<br>A/A</th>
	<th>M.Sold</th>
	<th>Out</th>
	<th>W/D</th>
	<th>HBP-RP</th>
	<th>HBP-BP</th>
	<th>LSP</th>
	<th>Testing</th>
	<th>Comment</th>
	<th>Status</th>
	<th>W/H</th>
	<th>Origin</th>
	<th>Category</th>
	<th>Inv. No</th>
	<th>N/W</th>
	<th>T/W</th>
</tr>

<tr>				
	<th>Bro<br>kers</th>
	<th>Lot No</th>
	<th>Garden</th>
	<th>Grade</th>
	<th>Print</th>
	<th>Bags</th>
	<th>Value</th>
	<th>R/P</th>
	<th>M/A<br>A/A</th>
	<th>M.Sold</th>
	<th>Out</th>
	<th>W/D</th>
	<th>HBP-RP</th>
	<th>HBP-BP</th>
	<th>LSP</th>
	<th>Testing</th>
	<th>Comment</th>
	<th>Status</th>
	<th>W/H</th>
	<th>Origin</th>
	<th>Category</th>
	<th>Inv. No</th>
	<th>N/W</th>
	<th>T/W</th>
</tr>
</thead>

<tbody id="reserve_lots">
</tbody>

</table>
</div>

</div> <!-- end col -->
</div> <!-- end row -->
</div> <!-- /container -->

<script>
// session start clock
function session_start_clock(target_to,start_from){
	
	// set timer
	var countDownDate 	= parseFloat(target_to) * 1000;
	var now 			= parseFloat(start_from) * 1000;
	
	// Update the count down every 1 second
	var z = setInterval(function() {
		
		now = now + 1000;

		// Find the distance between now an the count down date
		var distance = countDownDate - now;

		// Time calculations for days, hours, minutes and seconds
		//var days 	= Math.floor(distance  / (1000 * 60 * 60 * 24));
		var hours 	= Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		// Output the result in an element with id="countdown"
		document.getElementById("session_start_td").innerHTML = /*days + "d " +*/ '<font color=red><b> Session time will be start at : ' + hours + "h " + minutes + "m " + seconds + "s </b></font>";

		// If the count down is over, write some text 
		if(distance < 0){
			
			document.getElementById("session_start_td").innerHTML = "<font color=green><b>Get ready to auction. </b></font>";
			clearInterval(z);
			//call back
			fn_active_list('yes');
		}	
		
	}, 1000);
}
// end

// session end clock
function session_remaining_clock(target_time,current_time){
	
	// set timer
	var countDownDate 	= parseFloat(target_time) * 1000;
	var now 			= parseFloat(current_time) * 1000;
	
	// Update the count down every 1 second
	var y = setInterval(function() {
		
		now = now + 1000;

		// Find the distance between now an the count down date
		var distance = countDownDate - now;

		// Time calculations for days, hours, minutes and seconds
		//var days 	= Math.floor(distance  / (1000 * 60 * 60 * 24));
		var hours 	= Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		// Output the result in an element with id="countdown"
		document.getElementById("session_countdown").innerHTML 	= /*days + "d " +*/ 'Session time remaining: ' + hours + "h " + minutes + "m " + seconds + "s ";
		document.getElementById("countdown").innerHTML 			= /*days + "d " +*/ 'Active lots: ' + '0' + "h " + '0' + "m " + seconds + "s ";
		
		// if seconds is over
		if(seconds == 1){
			//fn_active_list('no');
		}
		
		// If the count down is over, write some text 
		if (distance < 0) {
			clearInterval(y);
			document.getElementById("session_countdown").innerHTML = "Session time is expired";
			fn_reserve_list();
		}
		
	}, 1000);
}
// end

// show active lots list
function fn_active_list(clock_run){
	$('#active_lots_list').load("<?php echo site_url('MY_Controller/auction_active_lot_list'); ?>", {catalogue: '<?php print $catalogue_name; ?>'},
	function(){
		
		if(clock_run=='yes'){
			var target_time 	= document.getElementById("target_end").value;
			var current_time 	= document.getElementById("current_time").value;
			// call back
			session_remaining_clock(target_time,current_time);
			fn_live_stream();
		}
		// hide lots from reserve table
		var active_lots_id 	= document.getElementById("active_lots_id").value;
		var mylots = active_lots_id.split(',');
		for(var i = 0; i < mylots.length; i++){
		   $("#r_lots"+mylots[i]).hide();
		}
		// end
		
	}); 
}

// show reserve lots list
function fn_reserve_list(){
	$('#reserve_lots').load("<?php echo site_url('MY_Controller/auction_reserve_lot_list'); ?>", {catalogue: '<?php print $catalogue_name; ?>'}, 
	function(){ 
		
		// set heading
		var season 			= document.getElementById("season").value;
		var sale_no 		= document.getElementById("sale_no").value;
		var catalouge_name 	= document.getElementById("catalouge_name").value;
		var sale_date 		= document.getElementById("sale_date").value;
		var session_time 	= document.getElementById("session_time").value;
		var target_to 		= document.getElementById("target_to").value;
		var start_from 		= document.getElementById("start_from").value;
		
		$("#heading_2").html("Season: " + season); 
		$("#heading_3").html("Sale No: " + sale_no); 
		$("#heading_4").html("Catalogue: " + catalouge_name); 
		$("#heading_5").html("Auction Date: " + sale_date); 
		$("#heading_6").html("Session Time: " + session_time); 
		
		// call back
		session_start_clock(target_to,start_from); 
	});
}

// auto call back
$(function(){
	
	// call back
	fn_reserve_list();

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
		$(this).html( '<input type="text" style="width:100%;" placeholder="Search '+title+'" id="column_search" />' );
	});

	// Apply the search
	$('#example thead').on( 'keyup', "#column_search",function () {
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

});	
</script>