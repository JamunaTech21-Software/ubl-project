<?php
if(isset($resultq1)){
?>

<style>
body{
	font-family:verdana !important;
	background-color: white !important;
	margin-top:10px;
}
.table{
	font-family:verdana !important;
	font-size:12px !important;
	width: 100% !important;
}
.table tr th{
	text-align:center;
	padding:1px !important;
}
.table tr td{
	text-align:center;
	font-weight:bold;
	padding:1px !important;
}
img{
	width: 100%;
    height: 150px;
}

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
	size:auto;
}
</style>

<div class="container">

<div class="row no-print">
	<div class="col-sm-12 text-right">
		<a class="btn-default" href="javascript:void(0);" onClick="window.print();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
	</div>
</div>

<div class="row text-center">
	<div class="col-sm-12">
		<img src="<?php echo base_url().'assets/img/slider/1.jpg'; ?>" alt="Unity Brokers LTD" />
		<br />
		<br />
		<b style="font-size:20px;">Auction Schedule</b>
		<br />
		<b id="sale_season"></b> / <b id="auction_center"></b>
	</div>
</div>	


<div class="row">
	<div class="col-sm-12">
		<div class="table-responsive">
			<table class="table table-condensed table-striped">
				<thead>
					<tr>				
						<th>Sale No.</th>
						<th>Sale Date</th>
						<th>Sale Day</th>
						<th>Buyer <br> Prompt date</th>
						<th>Catalogue <br> Closing date</th>
						<th>Comments</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(count($resultq1) > 0){
							
						$sl = 1;
						$season = '';
						$auction_center = '';
						foreach($resultq1 as $sale_s_data){
					?>
					<tr>
						<td style="text-align:center;"><?php echo $sale_s_data['sale_no']; ?></td>
						<td style="text-align:center;"><?php echo date("d-M-Y",strtotime($sale_s_data['sale_date'])); ?></td>
						<td style="text-align:center;"><?php echo $sale_s_data['sale_day']; ?></td>
						<td style="text-align:center;"><?php echo date("d-M-Y",strtotime($sale_s_data['buyer_prompt_date'])); ?></td>
						<td style="text-align:center;"><?php echo date("d-M-Y",strtotime($sale_s_data['catalouge_closing_date'])); ?></td>
						<td><?php echo $sale_s_data['comments']; ?></td>
					</tr> 
					<?php
							$season = $sale_s_data['season_name'];
							$auction_center = $sale_s_data['auction_center'];
						} // foreach

						}else{
							print "<tr><td colspan=7>Sorry !! matching record not found.</td></tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div> 
<!-- /row -->


<div class="row text-center">
	<div class="col-sm-12">
		<i>System By - Jamuna Tech</i>
	</div>
</div>

<br />
</div> 
<!-- /container -->

<script>
$(document).ready(function() {
    $("#sale_season").html('Season: <?php print $season; ?>');
    $("#auction_center").html('Auction Center: <?php print $auction_center; ?>');
});
</script>

<?php } // first if brace ?>