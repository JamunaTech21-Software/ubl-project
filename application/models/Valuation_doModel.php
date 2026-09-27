<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
class Valuation_doModel extends CI_Model {
	public function employeeList($season,$auction_center,$sale_no) {
		
		// select table
		$this->db->select(array('lot_no', 'valuation'));
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		$this->db->where('soft_delete', '1');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->order_by('lot_no ASC');
		$query = $this->db->get();
		return $query->result_array();
		
	}
}
?>