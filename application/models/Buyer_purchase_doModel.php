<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');
class Buyer_purchase_doModel extends CI_Model {
	public function employeeList($season,$auction_center,$sale_from,$sale_to) {
		
		## create temporary table - buyer_purchase_analysis
		$this->db->query("DROP TABLE IF EXISTS `buyer_purchase_analysis`");

		// create buyer_purchase_analysis @  buyer-1
		$this->db->query("CREATE TABLE `buyer_purchase_analysis` (
		SELECT 

			invoice_entry.sale_no,
			invoice_entry.sale_date,
			invoice_entry.lot_no,
			sale_entry.buyer1 AS buyer_id,	
			sale_entry.buyer_type1 AS buyer_type,
			buyer_setup.buyer_name,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Leaf','Supplement') AND NOT invoice_entry.supplement_tea='Dust' THEN sale_entry.buyer1_bag END,0) AS leaf_pkgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Dust','Supplement') AND NOT invoice_entry.supplement_tea='Leaf' THEN sale_entry.buyer1_bag END,0) AS dust_pkgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Leaf','Supplement') AND NOT invoice_entry.supplement_tea='Dust' THEN sale_entry.buyer1_total_kgs END,0) AS leaf_kgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Dust','Supplement') AND NOT invoice_entry.supplement_tea='Leaf' THEN sale_entry.buyer1_total_kgs END,0) AS dust_kgs,
			
			IFNULL(sale_entry.buyer1_bag,0) AS buyer_bag,
			IFNULL(sale_entry.buyer1_total_kgs,0) AS buyer_kgs,
			IFNULL(sale_entry.buyer1_amount,0) AS buyer_amount

		FROM invoice_entry  
		JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
		JOIN buyer_setup ON (sale_entry.buyer1 = buyer_setup.id) 

		WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

		// create buyer_purchase_analysis @  buyer-2
		$this->db->query("INSERT INTO `buyer_purchase_analysis` (
		SELECT 

			invoice_entry.sale_no,
			invoice_entry.sale_date,
			invoice_entry.lot_no,
			sale_entry.buyer2 AS buyer_id,	
			sale_entry.buyer_type2 AS buyer_type,
			buyer_setup.buyer_name,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Leaf','Supplement') AND NOT invoice_entry.supplement_tea='Dust' THEN sale_entry.buyer2_bag END,0) AS leaf_pkgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Dust','Supplement') AND NOT invoice_entry.supplement_tea='Leaf' THEN sale_entry.buyer2_bag END,0) AS dust_pkgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Leaf','Supplement') AND NOT invoice_entry.supplement_tea='Dust' THEN sale_entry.buyer2_total_kgs END,0) AS leaf_kgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Dust','Supplement') AND NOT invoice_entry.supplement_tea='Leaf' THEN sale_entry.buyer2_total_kgs END,0) AS dust_kgs,
			
			IFNULL(sale_entry.buyer2_bag,0) AS buyer_bag,
			IFNULL(sale_entry.buyer2_total_kgs,0) AS buyer_kgs,
			IFNULL(sale_entry.buyer2_amount,0) AS buyer_amount

		FROM invoice_entry  
		JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
		JOIN buyer_setup ON (sale_entry.buyer2 = buyer_setup.id) 

		WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");

		// create buyer_purchase_analysis @  buyer-3
		$this->db->query("INSERT INTO `buyer_purchase_analysis` (
		SELECT 

			invoice_entry.sale_no,
			invoice_entry.sale_date,
			invoice_entry.lot_no,
			sale_entry.buyer3 AS buyer_id,	
			sale_entry.buyer_type3 AS buyer_type,
			buyer_setup.buyer_name,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Leaf','Supplement') AND NOT invoice_entry.supplement_tea='Dust' THEN sale_entry.buyer3_bag END,0) AS leaf_pkgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Dust','Supplement') AND NOT invoice_entry.supplement_tea='Leaf' THEN sale_entry.buyer3_bag END,0) AS dust_pkgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Leaf','Supplement') AND NOT invoice_entry.supplement_tea='Dust' THEN sale_entry.buyer3_total_kgs END,0) AS leaf_kgs,
			
			IFNULL(CASE WHEN invoice_entry.catalouge_name IN ('Dust','Supplement') AND NOT invoice_entry.supplement_tea='Leaf' THEN sale_entry.buyer3_total_kgs END,0) AS dust_kgs,
			
			IFNULL(sale_entry.buyer3_bag,0) AS buyer_bag,
			IFNULL(sale_entry.buyer3_total_kgs,0) AS buyer_kgs,
			IFNULL(sale_entry.buyer3_amount,0) AS buyer_amount

		FROM invoice_entry  
		JOIN sale_entry ON (invoice_entry.id = sale_entry.invoice_id) 
		JOIN buyer_setup ON (sale_entry.buyer3 = buyer_setup.id)  

		WHERE invoice_entry.season='".$season."' AND invoice_entry.auction_center='".$auction_center."' AND invoice_entry.sale_no BETWEEN '".$sale_from."' AND '".$sale_to."' AND invoice_entry.catalogue_status='prepared' AND invoice_entry.soft_delete='1' GROUP BY invoice_entry.id) ");
		
		// select table
		$this->db->select(array('buyer_type', 'buyer_name', 'sum(leaf_pkgs) AS leaf_pkgs', 'sum(dust_pkgs) AS dust_pkgs', 'sum(leaf_kgs) AS leaf_kgs', 'sum(dust_kgs) AS dust_kgs', 'sum(buyer_bag) AS buyer_bag', 'sum(buyer_kgs) AS buyer_kgs', 'sum(buyer_amount) AS buyer_amount'));
		$this->db->from('buyer_purchase_analysis');
		$this->db->group_by('buyer_id, buyer_type');
		$this->db->order_by('buyer_type DESC, buyer_name ASC');
		$query = $this->db->get();
		return $query->result_array();
		
	}
}
?>