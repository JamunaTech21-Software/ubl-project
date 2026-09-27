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
	<th>Lot <br> No</th>
	<th>Garden</th>
	<th>Grade</th>
	<th>T/W</th>
	<th>Bags</th>
	<th>Value</th>
	<th>BUY <br> QTY</th>
	<th>BQ.Q</th>
	<th>SQ.Q</th>
	<th>SQ.Q2</th>
	<th>HBP</th>
	<th>BQ @ <br> MAB</th>
	<th>SQ @ <br> MAB</th>
	<th>R/P</th>
	<th>A/A</th>
	<th>BIDDER</th>
	<!--
	<th>Origin</th>
	<th>Category</th>
	-->
	<th>Print</th>
	<th>W/H</th>
	<th>Knock</th>
	<th>BQ</th>
	<th>SQ@<br>HBP</th>
	<th><b id="bidding_msg"></b> <br> MY BID</th>
	<th>BID UP</th>
	<th>AUTO BID</th>
	<th>Inv. No</th>
	<th>N/W</th>
	<th>Status</th>
	<th>My <br> Rating</th>
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

// my bid
function fn_my_bid(lot_id,bidding_rate,bidding_log,user_id,sq,user_sc,bq_hbpma,share_id,chest_number,buyq,hbp){
	
	// validation
	if(parseFloat(sq) == ''){
		
		alert("Please Enter - BQ");
		document.getElementById("sq"+lot_id).focus();
		return false;
		
	}else if(parseFloat(bidding_rate) == ''){
		
		alert("Please Enter - Bid Rate");
		document.getElementById("bidding_rate"+lot_id).focus();
		return false;
		
	}else if(parseFloat(sq) > parseFloat(chest_number)){
		
		alert("Sorry !! maximum qty is " + chest_number);
		document.getElementById("sq"+lot_id).focus();
		return false;
		
	}else if(parseFloat(bidding_rate) <= parseFloat(hbp)){
		
		alert("Press in Bid Up Button");
		document.getElementById("bidding_rate"+lot_id).value = '';
		return false;
		
	}else{
		// call back
		buyer_bidding_price(lot_id,bidding_rate,bidding_log,user_id,sq,user_sc,bq_hbpma,share_id,chest_number,buyq,'bid_up')
	}
}
	
// SHARE SQ @ HBP
function fn_bq_hbpma(lot_id,bidding_rate,user_log,user_id,sq,user_sc,buyq,chest_number,no_of_buyer,ab_value){
	
	// get html content
	var total_sq 	= parseFloat(sq) + parseFloat(buyq);
	var bq_hbpma 	= sq;
	var share_id 	= user_id;
	var first_b 	= document.getElementById("bidder"+lot_id).innerHTML.split("/"); // first bidder

	// sq check
	if(first_b[0] == '<?php print $web_user_sc; ?>'){
		
		console.log("Sorry ! you are base bidder.");
		return false;
		
	}else if(parseFloat(sq) < 5){
		
		alert("Enter at least 5 SQ");
		document.getElementById("bq_hbpma"+lot_id).focus();
		document.getElementById("sq"+lot_id).value 				= '0';
		document.getElementById("bidding_rate"+lot_id).value 	= '';
		document.getElementById("sq"+lot_id).readOnly			= false;
		document.getElementById("bidding_rate"+lot_id).readOnly	= false;
		return false;
		
	}else if(parseFloat(buyq) == ''){
		
		console.log("Enter - BQ and My Bid");
		return false;
		
	}else if(total_sq > parseFloat(chest_number)){
		
		console.log("SQ is not available.Enter BQ and Press Bid Up");
		return false;

	}else if(parseFloat(chest_number) > 19 && parseFloat(chest_number) <=30 && first_b.length > 1 && parseFloat(ab_value) < parseFloat(bidding_rate)){
		
		console.log("Bidder is full. Please Enter Auto Bid.");
		return false;

	}else if(parseFloat(chest_number) > 30 && first_b.length > 2 && parseFloat(ab_value) < parseFloat(bidding_rate)){
		
		console.log("Bidder is full. Please Enter Auto Bid.");
		return false;

	}else{
		
		// call back
		buyer_bidding_price(lot_id,bidding_rate,user_log,user_id,sq,user_sc,bq_hbpma,share_id,chest_number,buyq,ab_value);
	}	
}	

// BID UP
function bid_up_count(lot_id,hbp,user_log,user_id,sq,user_sc,inc_value,chest_number,buyq){
	
	// validation
	if(parseFloat(hbp) == '0'){
		
		alert("Sorry ! must be required HBP");
		document.getElementById("bidding_rate"+lot_id).focus();
		return false;
		
	}else if(parseFloat(sq) == '0'){
		
		alert("Please Enter - BQ");
		document.getElementById("sq"+lot_id).focus();
		document.getElementById("bq_hbpma"+lot_id).value = '';
		// attribute reset
		document.getElementById("sq"+lot_id).readOnly			=	false; 
		document.getElementById("bidding_rate"+lot_id).readOnly	=	false; 
		document.getElementById("bid_up"+lot_id).disabled		=	false;
		return false;
		
	}else if(parseFloat(sq) > parseFloat(chest_number)){
		
		alert("Sorry !! maximum qty is " + chest_number);
		document.getElementById("sq"+lot_id).focus();
		return false;
		
	}else{
		
		var bidding_rate = parseFloat(hbp) + parseFloat(inc_value);
		document.getElementById("bidding_rate"+lot_id).value = bidding_rate;
		// call back
		buyer_bidding_price(lot_id,bidding_rate,user_log,user_id,sq,user_sc,'0','0',chest_number,'0','bid_up');
	}	
}

function auto_bid_count(lot_id){
	
	var chest_number 	= parseFloat(document.getElementById('bag'+lot_id).innerHTML);
	var sq 				= parseFloat(document.getElementById('sq'+lot_id).value);
	var hbp 			= parseFloat(document.getElementById('hbp'+lot_id).innerHTML);
	var auto_bid 		= parseFloat(document.getElementById('auto_bid'+lot_id).value);

	// BQ check
	if(isNaN(sq)){
		
		alert("Please Enter - BQ");
		document.getElementById("sq"+lot_id).focus();
		document.getElementById("bq_hbpma"+lot_id).value = '';
		document.getElementById("auto_bid"+lot_id).value = '';
		// attribute reset
		document.getElementById("sq"+lot_id).readOnly			= false; 
		document.getElementById("bidding_rate"+lot_id).readOnly	= false; 
		document.getElementById("bid_up"+lot_id).disabled		= false;
		
	}else if(sq > chest_number){
		
		alert("Sorry !! maximum qty is " + chest_number);
		document.getElementById("sq"+lot_id).value='';
		document.getElementById("sq"+lot_id).focus();
		
	}else if(auto_bid < hbp){
		
		alert("Sorry !! AUTO BID must be greater than HBP");
		document.getElementById("auto_bid"+lot_id).value = '';
		
	}
}	

// knock me
function knock_me(lot_id,hbp,user_log,user_id,sq,user_sc,chest_number,buyq){
	
	// validation
	if(parseFloat(hbp) == '0'){
		
		alert("Sorry ! must be required HBP");
		return false;
		
	}else if(parseFloat(sq) == '0'){
		
		alert("Sorry ! must be required HBP");
		return false;
		
	}
	
	// call back
	buyer_bidding_price(lot_id,hbp,user_log,user_id,sq,user_sc,'0','0',chest_number,'0',hbp);
}	
	
// live stream
function fn_live_stream(){
	
	if(typeof(EventSource) !== "undefined") {
	  var source = new EventSource("<?php print site_url("MY_Controller/auction_brokers_live_stream"); ?>",{ withCredentials: true });
	  source.onmessage = function(event) {
		var str 	= event.data;
		var res		= str.split(":");
	
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
			
			$("#buyq"+lot_id).text(buyq);
			$("#bqq"+lot_id).text(bq);
			$("#sqq"+lot_id).text(sq1);
			$("#sqqs"+lot_id).text(sq2);
			$("#hbp"+lot_id).text(hbp);
			$("#mab"+lot_id).text(bq_mab);
			$("#sq_mab"+lot_id).text(sq_mab);
			$("#bidder"+lot_id).text(bidder);
			$("#rp"+lot_id).text(rp);
			$("#status"+lot_id).text(s_wd);

			// get html values
			var ab_value 		= parseFloat(document.getElementById('auto_bid'+lot_id).value);
			var sq 				= parseFloat(document.getElementById('sq'+lot_id).value);
			var actual_rp 		= parseFloat(document.getElementById('rp'+lot_id).innerHTML);
			var chest_number 	= parseFloat(document.getElementById('bag'+lot_id).innerHTML);
			
			// CHANGE AUTO ACTIVE
			if((hbp >= rp)){
				document.getElementById("aa"+lot_id).innerHTML = 'Active';
			}
			
			
			// ****** ACTION AUTO BID (Buy Qty) ******
			if(
				(sq <= chest_number) && 
				(sq > 0) && 
				(ab_value >= actual_rp) && 
				(hbp < ab_value) && 
				(first_bidder != '<?php print $web_user_sc; ?>')
			){
				
				var prv_mab = parseFloat(document.getElementById("mab"+lot_id).innerHTML);
				
				if(prv_mab != '' && prv_mab < ab_value){
					var new_hbp = prv_mab + 1;
				}else{
					var new_hbp = hbp + 1;
				}	
				
				// call back
				buyer_bidding_price(lot_id,new_hbp,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','<?php print $ubl_web_user_id; ?>',sq,'<?php print $web_user_sc; ?>','0','0',chest_number,buyq,ab_value);
				
			}	
			// ****** end ******
			
			
			// +++++ ACTION AUTO BID (Share Qty) ++++
			var bq_hbpma 	= parseFloat(document.getElementById("bq_hbpma"+lot_id).value);
			var sq_limit 	= parseFloat(document.getElementById("auto_bid"+lot_id).value);
			var prv_mab 	= parseFloat(document.getElementById("sq_mab"+lot_id).innerHTML);
			
			// SQ - auto bid limit with in share balance
			if(
				(bq_hbpma > 0) && 
				(sq_limit > 0) && 
				(hbp <= sq_limit) && 
				(sq_limit > prv_mab) && 
				(first_bidder != '<?php print $web_user_sc; ?>')
			){
				// call back
				buyer_bidding_price(lot_id,hbp,'<?php print $ubl_user_name .', Date: '. $today .', IP: '. $ip; ?>','<?php print $ubl_web_user_id; ?>',bq_hbpma,'<?php print $web_user_sc; ?>',bq_hbpma,'<?php print $ubl_web_user_id; ?>',chest_number,buyq,ab_value);
			}	
			// ++++ end ++++
			
			
			// TR ENABLE / DISABLE
			if(s_wd=='sold' || s_wd=='wd' || s_wd=='out'){
				$("#tr"+lot_id).addClass("disabled");
				$("#tr"+lot_id).on("keydown keypress keyup click contextmenu", false);
			}else{
				$("#tr"+lot_id).addClass("enabled");
				//$("#tr"+lot_id).on("keydown keypress keyup click contextmenu", true);
			}
			// end
			
			
			// KNOCK ME ENABLE / DISABLE
			if(hbp < rp){
				document.getElementById("knock"+lot_id).disabled = false;
			}else{
				document.getElementById("knock"+lot_id).disabled = true;
			}	
			// end
			
		}
		
	  };
	}else{
	  document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
	}

}
// end
</script>

<hr style="border-top: 1px dotted red;">

<!-- +++++++++++++++++ -->
<!-- Buyer Pre-Auction -->	
<!-- +++++++++++++++++ -->

<div class="table-responsive" style="height:300px;">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>				
	<th>Bro<br>kers</th>
	<th>Lot <br> No</th>
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
			//fn_update_auction_history();
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
});
</script>