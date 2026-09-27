<?php
$season = (!empty($this->uri->segment(3))) ? $this->uri->segment(3) : '0';
$auction_center = (!empty($this->uri->segment(4))) ? $this->uri->segment(4) : '0';
$sale_no = (!empty($this->uri->segment(5))) ? $this->uri->segment(5) : '0';
$lot_no = (!empty($this->uri->segment(6))) ? $this->uri->segment(6) : '0';

// query
$this->db->select("id, lot_no, chest_number"); 
$this->db->from('invoice_entry');
$this->db->where('catalogue_status', 'prepared');
$this->db->where('season', $season);
$this->db->where('auction_center', $auction_center);
$this->db->where('sale_no', $sale_no);
$this->db->where('lot_no', $lot_no);
$this->db->where('soft_delete', 1);
$this->db->order_by('lot_no ASC, id ASC');
$query = $this->db->get();
$result = $query->result_array();
?>

<select name="bulk_lot_id" id="bulk_lot_id" onchange="sale_lot_info(bulk_lot_id.value);">
	<option value="">Bulk Lot</option>
	<?php foreach($result as $row1){ ?>
	<option value="<?php print $row1['id']; ?>"><?php print $row1['chest_number']; ?> B</option>
	<?php } ?>
</select>