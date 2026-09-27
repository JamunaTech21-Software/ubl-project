<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
class Wh_wise_doModel extends CI_Model {
	public function employeeList($season,$auction_center,$sale_from,$sale_to,$w_house_id) {
		
		## create temporary table - wh_wise_do
		$this->db->query("DROP TABLE IF EXISTS `wh_wise_do`");

		// create wh_wise_do @  buyer-1
		$this->db->query("CREATE TABLE `wh_wise_do` (
		SELECT 
			buyer_setup.buyer_name,
			warehouse_setup.warehouse_name,
			warehouse_setup.short_key_word,
			invoice_entry.lot_no,
			garden_and_company_setup.garden_name,
			invoice_entry.w_inv_no,
			CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
			IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
			invoice_entry.net_wt_per_bag,
			IF(sale_entry.buyer1_smpl_dm='', '', 'Y') AS sample,
			IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
			IFNULL(sale_entry.sold_price,0) AS sold_price,
			IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount,
			((IFNULL(sale_entry.buyer1_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk

		FROM invoice_entry  
		JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
		JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 
		JOIN warehouse_setup ON (invoice_entry.w_house_id = warehouse_setup.id) 
		JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
		JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 

		WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

		// create wh_wise_do @  buyer-2
		$this->db->query("INSERT INTO `wh_wise_do` (
		SELECT 
			buyer_setup.buyer_name,
			warehouse_setup.warehouse_name,
			warehouse_setup.short_key_word,
			invoice_entry.lot_no,
			garden_and_company_setup.garden_name,
			invoice_entry.w_inv_no,
			CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
			IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
			invoice_entry.net_wt_per_bag,
			IF(sale_entry.buyer2_smpl_dm='', '', 'Y') AS sample,
			IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
			IFNULL(sale_entry.sold_price,0) AS sold_price,
			IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount,
			((IFNULL(sale_entry.buyer2_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk

		FROM invoice_entry  
		JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
		JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 
		JOIN warehouse_setup ON (invoice_entry.w_house_id = warehouse_setup.id) 
		JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
		JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 

		WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

		// create wh_wise_do @  buyer-3
		$this->db->query("INSERT INTO `wh_wise_do` (
		SELECT 
			buyer_setup.buyer_name,
			warehouse_setup.warehouse_name,
			warehouse_setup.short_key_word,
			invoice_entry.lot_no,
			garden_and_company_setup.garden_name,
			invoice_entry.w_inv_no,
			CONCAT(tea_grade_setup.grade_name,invoice_entry.additional_grade) AS grade_name,
			IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
			invoice_entry.net_wt_per_bag,
			IF(sale_entry.buyer3_smpl_dm='', '', 'Y') AS sample,
			IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
			IFNULL(sale_entry.sold_price,0) AS sold_price,
			IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount,
			((IFNULL(sale_entry.buyer3_amount,0) * IFNULL(invoice_entry.vat,0)) / 100) AS vat_tk

		FROM invoice_entry  
		JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
		JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id) 
		JOIN warehouse_setup ON (invoice_entry.w_house_id = warehouse_setup.id) 
		JOIN garden_and_company_setup ON (invoice_entry.garden_id = garden_and_company_setup.id) 
		JOIN tea_grade_setup ON (invoice_entry.grade_id = tea_grade_setup.id) 

		WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.w_house_id='".$w_house_id."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
		
		// select table
		$this->db->select(array('buyer_name', 'warehouse_name', 'short_key_word', 'lot_no', 'garden_name', 'w_inv_no', 'grade_name', 'buyer_bag', 'net_wt_per_bag', 'sample', 'buyer_kgs', 'sold_price', 'buyer_amount', 'vat_tk'));
		$this->db->from('wh_wise_do');
		$this->db->order_by('lot_no ASC');
		$query = $this->db->get();
		return $query->result_array();
		
	}
}
?>