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
					<th style="text-align:center;">User Name</th>
					<th style="text-align:center;">Password</th>
					<th style="text-align:center;">Edit Prvlg.</th>
					<th style="text-align:center;">Delete Prvlg.</th>
					<th style="text-align:center;">Edit</th>
					<th style="text-align:center;">User Role</th>
				</tr>
			</thead>
			<tbody>
<?php
$sl = 1;

// query
$this->db->select("*"); 
$this->db->from('super_admin');
$query = $this->db->get();
foreach($query->result_array() as $row){
?>			
				<tr>
					<td style="text-align:center;"><?php print $sl++; ?></td>
					<td style="text-align:center;"><?php print $row['username']; ?></td>
					<td style="text-align:center;"><?php print $row['password']; ?></td>
					<td style="text-align:center;"><?php print $row['edit_privilege']; ?></td>
					<td style="text-align:center;"><?php print $row['delete_privilege']; ?></td>
					<td style="text-align:center;">
						<span style="cursor:pointer; color:green;" class="glyphicon glyphicon-edit" onclick="fn_edit_user('<?php print $row['id']; ?>','<?php print $row['username']; ?>','<?php print $row['password']; ?>','<?php print $row['edit_privilege']; ?>','<?php print $row['delete_privilege']; ?>');"></span>
					</td>
					<td style="text-align:center;">
						<span style="cursor:pointer; color:red;" class="glyphicon glyphicon-tasks"></span>
					</td>
				</tr>
<?php } ?>				
			</tbody>
		</table>
	</div>	
</div>	