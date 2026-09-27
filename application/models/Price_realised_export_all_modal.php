<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Price_realised_export_all_modal extends CI_Model {

    public function get_price_realised_data($params) {
        $this->db->select("
            invoice_entry.sale_no,
            gcs.garden_name AS garden_name,  
            invoice_entry.lot_no, 
            invoice_entry.w_inv_no, 
            CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade,
            invoice_entry.valuation, 
            IFNULL(sale_entry.sold_price, 'UNSOLD') AS sold_price,
            invoice_entry.net_kgs, 
            invoice_entry.chest_number, 
            IF(IFNULL(sale_entry.buyer1_bag, 0) > 0, b1.short_key_code, '') AS buyer1,
            IF(IFNULL(sale_entry.buyer2_bag, 0) > 0, b2.short_key_code, '') AS buyer2,
            IF(IFNULL(sale_entry.buyer3_bag, 0) > 0, b3.short_key_code, '') AS buyer3,
            IF(invoice_entry.old_sale_no > 0, 1, 0) AS re_print
        "); 
        
        $this->db->from('invoice_entry');
        $this->db->join('garden_and_company_setup gcs', 'invoice_entry.garden_id = gcs.id', 'left');
        $this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
        $this->db->join('sale_entry', 'invoice_entry.id = sale_entry.invoice_id', 'left');
        $this->db->join('buyer_setup b1', 'sale_entry.buyer1 = b1.id', 'left');
        $this->db->join('buyer_setup b2', 'sale_entry.buyer2 = b2.id', 'left');
        $this->db->join('buyer_setup b3', 'sale_entry.buyer3 = b3.id', 'left');

        $this->db->where('invoice_entry.catalogue_status', 'prepared');
        $this->db->where('invoice_entry.season', $params['season']);
        $this->db->where('invoice_entry.auction_center', $params['auction_center']);
        $this->db->where('invoice_entry.sale_no', $params['sale_no']);
        
        if (!empty($params['company_id'])) {
            $this->db->where('gcs.company_name', $params['company_id']);
        }
        
        $this->db->where('invoice_entry.soft_delete', 1);
        $this->db->group_by('invoice_entry.id');
        $this->db->order_by('invoice_entry.sale_no ASC, invoice_entry.lot_no ASC');

        return $this->db->get()->result_array();
    }
}