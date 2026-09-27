<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
class Catalogue_Model extends CI_Model {
	public function employeeList($season,$auction_center,$sale_no) {
		
		## create temporary table - Catalogue_export
		$this->db->query("DROP TABLE IF EXISTS `Catalogue_export`");

		// create Catalogue_export
		$this->db->query("CREATE TABLE `Catalogue_export` (
		SELECT 
			invoice_entry.sale_no,
			garden_and_company_setup.garden_name,
			warehouse_setup.warehouse_name,
			invoice_entry.lot_no,
			invoice_entry.valuation,
			invoice_entry.w_inv_no,
			CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
			invoice_entry.gross_wt_per_bag,
			invoice_entry.net_wt_per_bag,
			invoice_entry.net_kgs,
			invoice_entry.chest_number

		FROM invoice_entry  
		LEFT JOIN warehouse_setup ON (invoice_entry.w_house_id = warehouse_setup.id) 
		LEFT JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
		LEFT JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 

		WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no='".$sale_no."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

		// select table
		$this->db->select(array('sale_no', 'garden_name', 'warehouse_name', 'lot_no', 'w_inv_no', 'grade_name', 'gross_wt_per_bag', 'net_wt_per_bag', 'net_kgs', 'chest_number', 'valuation'));
		$this->db->from('Catalogue_export');
		$this->db->order_by('lot_no ASC');
		$query = $this->db->get();
		return $query->result_array();
		
	}
}
?>