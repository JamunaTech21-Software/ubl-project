<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pre_sale_advice_report_export_modal extends CI_Model {

    // Fetch sales data based on the input parameters
    public function get_pre_sale_advice_data($params) {
        $this->db->select("lot_no, w_inv_no, grade_name, garden_name, re_print, offering_kgs, offering_bag, valuation, sum(buyer_kgs) AS sold_kgs, sum(buyer_bag) AS sold_bag, sold_price, sum(buyer_amount) AS sold_amount ");   
        $this->db->from('pre_post_sale_advice');
        $this->db->where('garden_id', $params['garden_id']);
        $this->db->group_by('lot_no');
        $this->db->order_by('lot_no ASC');

        $query = $this->db->get();

        return $query->result_array();
    }
}

?>
