<?php
// query
$this->db->select('id, buyer_name, short_key_code'); 
$this->db->from('buyer_setup');
$this->db->where('soft_delete', 1);
$this->db->where('main_buyer_id'); // is null
$this->db->order_by('buyer_name ASC');
$query = $this->db->get();
$result = $query->result_array();

$buyer_list = "<select required class=\"form-control input-sm\" name=\"main_buyer_id\" id=\"main_buyer_id\" style=\"text-transform:uppercase;\">
<option value=\"\">Select</option>";
foreach($result AS $sn_row){
	$buyer_list.= "<option value=\"$sn_row[id]\">$sn_row[buyer_name], $sn_row[short_key_code]</option>";
}
$buyer_list.= "</select>";

print $buyer_list;
?>