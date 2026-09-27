<?php
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>BROKER WISE SUMMARY</title>
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
	.broker-summary-table {
		width: 100% !important;
		border-collapse: collapse;
		margin-top: 20px;
	}
	.broker-summary-table th {
		background-color: #f2f2f2;
		font-weight: bold;
		text-align: center;
		border: 1px solid #000;
		padding: 8px 10px;
		font-size: 13px;
		text-transform: uppercase;
	}
	.broker-summary-table td {
		padding: 6px 10px;
		border: 1px solid #000;
		font-size: 12px;
	}
	.broker-summary-table .text-right {
		text-align: right;
	}
	.broker-summary-table .text-center {
		text-align: center;
	}
	.broker-summary-table .text-left {
		text-align: left;
	}
	.broker-summary-table .grand-total-row td {
		background-color: #e8e8e8;
		font-weight: bold;
	}
	.broker-summary-table .no-data td {
		text-align: center;
		padding: 15px;
	}
	.section-title {
		text-align: center;
		margin-top: 20px;
		margin-bottom: 15px;
		text-decoration: underline;
		font-weight: bold;
		font-size: 16px;
		text-transform: uppercase;
	}
	.header-title {
		text-transform: uppercase;
		text-align: center;
		font-size: 16px;
		font-weight: bold;
	}
	.sub-header {
		text-transform: uppercase;
		text-align: center;
		font-size: 14px;
	}
	.no-print {
		text-align: right;
		margin-bottom: 10px;
	}
	.btn-default {
		padding: 5px 10px;
		background: #f0f0f0;
		border: 1px solid #ccc;
		cursor: pointer;
		text-decoration: none;
		color: #333;
	}
	.btn-default:hover {
		background: #ddd;
	}

	@media print{    
		thead { display: table-header-group; }
		.no-print, .no-print *{ display: none !important; }
		a[href]:after { content: none !important; }
	}
</style>

<!-- Print Button -->
<div class="no-print">
	<a class="btn-default" href="javascript:void(0);" onclick="window.print();">
		<span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
	</a>
</div>

<!-- Header -->
<div class="row">
	<div class="col-sm-12">
		<table style="width:100%;">
			<tr>
				<td style="text-align:center; border:0px;">
					<span style="font-size:16px; font-weight:bold; text-transform:uppercase;"><?php print $website_title; ?></span><br />
					<span style="font-size:14px;"><u><?php print $owner_short_address; ?></u></span> <br />
					<span style="font-size:14px; font-weight:bold; text-transform:uppercase;">BROKER WISE SUMMARY</span> <br />
					<span style="font-size:13px;"><?php print $auction_center; ?></span>
				</td>
			</tr>
			<tr>
				<td style="text-align:center; border:0px; font-size:13px;">
					Sale No: <?php print $sale_no; ?> &nbsp;&nbsp;&nbsp; Season: <?php print $season; ?>
				</td>
			</tr>
		</table>
	</div>
</div>

<!-- Broker Wise Summary Table -->
<div class="row">
	<div class="col-sm-12">
		<table class="broker-summary-table">
			<thead>
				<tr>
					<th style="width:8%;">SL</th>
					<th style="width:37%;">BROKER NAME</th>
					<th style="width:20%;">TOTAL KGS</th>
					<th style="width:20%;">TOTAL AMOUNT (৳)</th>
					<th style="width:15%;">TOTAL AVG (৳/KG)</th>
				</tr>
			</thead>
			<tbody>
			<?php
			// Fetch Broker Wise Data using CodeIgniter DB
			$this->db->select("
				ag.brokers_id,
				bs.brokers_name,
				SUM(ag.total_kgs) as total_kgs,
				SUM(ag.total_kgs * ag.avg_price) as total_amount,
				CASE 
					WHEN SUM(ag.total_kgs) > 0 THEN SUM(ag.total_kgs * ag.avg_price) / SUM(ag.total_kgs)
					ELSE 0 
				END as total_avg
			");
			$this->db->from('all_garden_avg ag');
			$this->db->join('brokers_setup bs', 'ag.brokers_id = bs.id', 'left');
			$this->db->where('ag.season', $season);
			$this->db->where('ag.auction_center', $auction_center);
			$this->db->where('ag.sale_no', $sale_no);
			$this->db->where('ag.category !=', 'GREEN TEA');
			$this->db->where('ag.soft_delete', '1');
			$this->db->group_by('ag.brokers_id, bs.brokers_name');
			$this->db->order_by('bs.brokers_name', 'ASC');
			
			$query = $this->db->get();
			$broker_results = $query->result_array();
			
			$sl = 1;
			$grand_total_kgs = 0;
			$grand_total_amount = 0;
			
			if(count($broker_results) > 0){
				foreach($broker_results as $row){
					$grand_total_kgs += $row['total_kgs'];
					$grand_total_amount += $row['total_amount'];
			?>
				<tr>
					<td class="text-center"><?php echo $sl++; ?></td>
					<td class="text-left" style="text-transform:uppercase;"><?php echo $row['brokers_name']; ?></td>
					<td class="text-right"><?php echo number_format($row['total_kgs'], 2, '.', ','); ?></td>
					<td class="text-right"><?php echo number_format($row['total_amount'], 2, '.', ','); ?></td>
					<td class="text-right"><?php echo number_format($row['total_avg'], 2, '.', ','); ?></td>
				</tr>
			<?php 
				}
			} else {
			?>
				<tr class="no-data">
					<td colspan="5"><b>No matching record found.</b></td>
				</tr>
			<?php 
			}
			
			// Grand Total Row
			if(count($broker_results) > 0){
				$grand_total_avg = ($grand_total_kgs > 0) ? ($grand_total_amount / $grand_total_kgs) : 0;
			?>
				<tr class="grand-total-row">
					<td colspan="2" class="text-right">GRAND TOTAL :</td>
					<td class="text-right"><?php echo number_format($grand_total_kgs, 2, '.', ','); ?></td>
					<td class="text-right"><?php echo number_format($grand_total_amount, 2, '.', ','); ?></td>
					<td class="text-right"><?php echo number_format($grand_total_avg, 2, '.', ','); ?></td>
				</tr>
			<?php 
			}
			?>
			</tbody>
		</table>
	</div>
</div>

<!-- Footer -->
<div class="row">
	<div class="col-sm-12 text-center" style="margin-top:20px;">
		<i style="text-transform: uppercase; font-size:11px; font-weight:bold;">
			www.unitybrokersltd.com — Developed by @ Jamuna Tech
		</i>
	</div>
</div>

</div> <!-- container -->

<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
function PrintPanel() {
	window.print();
}
</script>

</body>
</html>