<style>
.table{
	font-family:verdana;
	font-size:14px !important;
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
</style>

<!-- form action -->
<div class="card">
    <div class="card-header">
    <b class="font-weight-bold"> Auction Board # <span id="form_mode" class="badge badge-success">Settings</span></b>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="email">Enter Seconds</label>
            <input type="text" class="form-control input-sm" id="count" onfocus="if(this.value=='Time Expired') this.value=''" onblur="if(this.value=='') this.value='Time Expired'">
        </div>

        <input type="button" value="Submit" id="Submit" class="btn btn-primary" onclick="set_timer()">
    </div>
</div>
<!-- end form action -->

<div class="table-responsive">
<table class="table table-hover table-condensed table-striped table-bordered" id="table">
<thead>
<tr>
    <th colspan="10" id="sale_season" style="font-size:20px; color:blue; text-align:left;"></th>
</tr>

<tr>				
	<th style="text-align:center;">Lot No</th>
	<th style="text-align:center;">Garden name</th>
	<th style="text-align:center;">W/H name</th>
	<th style="text-align:center;">Grade</th>
	<th style="text-align:center;">Net Kgs</th>
	<th style="text-align:center;">Bags</th>
	<th style="text-align:center;">R/P</th>
	<th style="text-align:center;">H/B</th>
</tr>
</thead>

<tbody id="auction_lot_data">
</tbody>
</table>
</div>


<script> 
$(function(){
	// before response
	document.getElementById('auction_lot_data').innerHTML = "<img src=<?php echo base_url().'assets/img/loading.gif'; ?>>";
	// load cart list
	$("#auction_lot_data").load("<?php echo site_url('MY_Controller/admin_auction_lot_list'); ?>", function(){
	document.getElementById('sale_season').innerHTML = 'Season: ' + document.getElementById('season').value + ' / Sale Number: ' + document.getElementById('sale_no').value;
	});
});
</script>

</body>
</html>