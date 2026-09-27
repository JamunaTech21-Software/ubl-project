<select class="form-control" name="origin" id="origin">
	<option value="">Select</option>
<?php 
// query
$this->db->select("*"); 
$this->db->from('garden_origin_setup');
$query = $this->db->get();
foreach($query->result_array() as $row){
	print "<option value=\"$row[id]\">$row[origin_name]</option>";
} // forerach
?>
</select>