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
</style>

<div class="well">
	<div class="table-responsive">
		<table class="table table-hover table-condensed table-striped table-bordered" id="table">
			<thead>
				<tr>
					<th style="text-align:center;">SL</th>
					<th style="text-align:center;">Origin Name</th>
					<th style="text-align:center;">Edit</th>
				</tr>
			</thead>
			<tbody>
			
<?php 
$sl=1;
// query
$this->db->select("*"); 
$this->db->from('garden_origin_setup');
$query = $this->db->get();
foreach($query->result_array() as $row){
?>
			<tr>
					<td style="text-align:center;"><?php print $sl++; ?></td>
					<td style="text-align:left;" title="<?php print $row['last_update']; ?>"><?php print $row['origin_name']; ?></td>
					<td style="text-align:center;">
						<span style="cursor:pointer; color:green;" class="glyphicon glyphicon-edit" onclick="fn_edit_origin('<?php print $row['id']; ?>','<?php print $row['origin_name']; ?>');"></span>
					</td>
				</tr>
<?php } // forerach ?>
			</tbody>
		</table>
	</div>
</div>