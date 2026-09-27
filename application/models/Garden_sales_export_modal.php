<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Garden_sales_export_modal extends CI_Model {

    // Fetch sales data based on the input parameters
    public function get_warehouse_data($params) {

        $this->db->select("
            invoice_entry.sale_no,
            gcs.garden_name AS garden_name,  
            invoice_entry.lot_no, 
            invoice_entry.w_inv_no, 
            CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade,
            invoice_entry.old_season,
            invoice_entry.valuation, 
            IFNULL(sale_entry.sold_price,0) AS sold_price, 
            
            (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) AS net_kgs, 
            
            (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) AS chest_number, 
            
            invoice_entry.remarks, 
            
            IF(IFNULL(sale_entry.buyer1_bag,0) > 0, CONCAT(b1.short_key_code,' ',sale_entry.buyer1_bag,' - ',LEFT(sale_entry.buyer_type1,1)), '') AS buyer1, 
            
            IF(IFNULL(sale_entry.buyer2_bag,0) > 0, CONCAT(b2.short_key_code,' ',sale_entry.buyer2_bag,' - ',LEFT(sale_entry.buyer_type2,1)), '') AS buyer2, 
            
            IF(IFNULL(sale_entry.buyer3_bag,0) > 0, CONCAT(b3.short_key_code,' ',sale_entry.buyer3_bag,' - ',LEFT(sale_entry.buyer_type3,1)), '') AS buyer3,
            
            (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) AS sold_amount,
            
            (
                IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0)
            ) AS leaf_total_bag,
            
            (
                IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0)
            ) AS leaf_net_kgs, 
            
            (
                IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_bag,0) + IFNULL(sale_entry.buyer2_bag,0) + IFNULL(sale_entry.buyer3_bag,0)) END),0)
            ) AS dust_total_bag,
            
            (
                IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_total_kgs,0) + IFNULL(sale_entry.buyer2_total_kgs,0) + IFNULL(sale_entry.buyer3_total_kgs,0)) END),0)
            ) AS dust_net_kgs,
            
            (
                IFNULL(SUM(CASE WHEN catalouge_name = 'Leaf' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULL(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Leaf') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
            ) AS leaf_sold_amount,
            
            (
                IFNULL(SUM(CASE WHEN catalouge_name = 'Dust' THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0) + IFNULl(SUM(CASE WHEN catalouge_name = 'Supplement' AND supplement_tea IN ('Dust','Green Tea','Orthodox') THEN (IFNULL(sale_entry.buyer1_amount,0) + IFNULL(sale_entry.buyer2_amount,0) + IFNULL(sale_entry.buyer3_amount,0)) END),0)
            ) AS dust_sold_amount
        "); 
        $this->db->from('invoice_entry');

        $this->db->join('garden_and_company_setup gcs', 'invoice_entry.garden_id = gcs.id', 'left');
        $this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
        $this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
        $this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
        $this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
        $this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');

        // Apply conditions based on the passed parameters
        $this->db->where('invoice_entry.catalogue_status', 'prepared');
        $this->db->where('invoice_entry.season', $params['season']);
        $this->db->where('invoice_entry.auction_center', $params['auction_center']);
        $this->db->where('invoice_entry.sale_no >=', $params['sale_from']);
        $this->db->where('invoice_entry.sale_no <=', $params['sale_to']);
        $this->db->where('invoice_entry.garden_id', $params['garden_id']);
        $this->db->where('invoice_entry.soft_delete', 1);

        $this->db->group_by('invoice_entry.id');
        $this->db->order_by('invoice_entry.sale_no ASC,invoice_entry.lot_no ASC');

        $query = $this->db->get();

        return $query->result_array();
    }
}
?>