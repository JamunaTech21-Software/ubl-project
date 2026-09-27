<?php 
class Ajax_model extends CI_Model{
	
	// company setup
	function save_company_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$company_name = trim($this->input->post('company_name', TRUE));
		$company_full_name = trim($this->input->post('company_full_name', TRUE));
		$company_address1 = trim($this->input->post('company_address1', TRUE)); // full address
		$company_address2 = trim($this->input->post('company_address2', TRUE)); // short address
		$company_contact_no = trim($this->input->post('company_contact_no', TRUE));
		$company_email = trim($this->input->post('company_email', TRUE));
		$reg_no = trim($this->input->post('reg_no', TRUE));
		$com_report_to = trim($this->input->post('com_report_to', TRUE));
		$com_report_cc = trim($this->input->post('com_report_cc', TRUE));
		$password = trim($this->input->post('password', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			$data = array(
				'company_name' => $company_name,
				'company_full_name' => $company_full_name,
				'company_address1' => $company_address1,
				'company_address2' => $company_address2,
				'company_contact_no' => $company_contact_no,
				'company_email' => $company_email,
				'reg_no' => $reg_no,
				'com_report_to' => $com_report_to,
				'com_report_cc' => $com_report_cc,
				'password' => $password,
				'user' => $user,
				'datetime' => $datetime,
				'last_update' => $last_update
			);
			$result = $this->db->insert('company_setup',$data);
			return 1;

		}else{

			$this->db->set('company_name', $company_name);
			$this->db->set('company_full_name', $company_full_name);
			$this->db->set('company_address1', $company_address1);
			$this->db->set('company_address2', $company_address2);
			$this->db->set('company_contact_no', $company_contact_no);
			$this->db->set('company_email', $company_email);
			$this->db->set('reg_no', $reg_no);
			$this->db->set('com_report_to', $com_report_to);
			$this->db->set('com_report_cc', $com_report_cc);
			//$this->db->set('password', $password);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', $setup_id);
			$result = $this->db->update('company_setup');
			return 2;
		}	
	}
	
	// garden setup
	function save_gc_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$garden_name = trim($this->input->post('garden_name', TRUE));
		$garden_type = trim($this->input->post('garden_type', TRUE));
		$main_garden_id = trim($this->input->post('main_garden_id', TRUE));
		$gardentext = trim($this->input->post('gardentext', TRUE));
		$main_garden = ($garden_type == 'Main Garden') ? $garden_name : $gardentext;
		$seller_brokers = trim($this->input->post('seller_brokers', TRUE));
		$garden_address1 = trim($this->input->post('garden_address1', TRUE));
		$garden_address2 = trim($this->input->post('garden_address2', TRUE));
		$garden_contact_no 	= trim($this->input->post('garden_contact_no', TRUE));
		$garden_email = trim($this->input->post('garden_email', TRUE));
		$origin = trim($this->input->post('origin', TRUE));
		$company_name = trim($this->input->post('company_name', TRUE));
		$report_to = trim($this->input->post('report_to', TRUE));
		$report_cc = trim($this->input->post('report_cc', TRUE));
		$report_letter_to = trim($this->input->post('report_letter_to', TRUE));
		$report_letter_cc = trim($this->input->post('report_letter_cc', TRUE));
		$password = trim($this->input->post('password', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			$data = array(
				'garden_name' => $garden_name, 
				'garden_type' => $garden_type,
				'main_garden' => $main_garden,
				'seller_brokers' => $seller_brokers,
				'garden_address1' => $garden_address1,
				'garden_address2' => $garden_address2,
				'garden_contact_no' => $garden_contact_no,
				'garden_email' => $garden_email,
				'origin' => $origin,
				'company_name' => $company_name,
				'password' => $password,
				'user' => $user,
				'report_to' => $report_to,
				'report_cc' => $report_cc,
				'report_letter_to' => $report_letter_to,
				'report_letter_cc' => $report_letter_cc,
				'datetime' => $datetime,
				'last_update' => $last_update
			);
			$result = $this->db->insert('garden_and_company_setup',$data);

			$insert_id = $this->db->insert_id();

			$final_garden_id = ($garden_type == 'Main Garden') ? $insert_id : $main_garden_id;

			$this->db->set('main_garden_id', $final_garden_id);
			$this->db->where('id', $insert_id);
			$this->db->update('garden_and_company_setup');

			return 1;

		}else{

			$final_garden_id = ($garden_type == 'Main Garden') ? $setup_id : $main_garden_id;

			$this->db->set('garden_name', $garden_name);
			$this->db->set('garden_type', $garden_type);
			$this->db->set('main_garden_id', $final_garden_id);
			$this->db->set('main_garden', $main_garden);
			$this->db->set('seller_brokers', $seller_brokers);
			$this->db->set('garden_address1', $garden_address1);
			$this->db->set('garden_address2', $garden_address2);
			$this->db->set('garden_contact_no', $garden_contact_no);
			$this->db->set('garden_email', $garden_email);
			$this->db->set('origin', $origin);
			$this->db->set('company_name', $company_name);
			$this->db->set('report_to', $report_to);
			$this->db->set('report_cc', $report_cc);
			$this->db->set('report_letter_to', $report_letter_to);
			$this->db->set('report_letter_cc', $report_letter_cc);
			//$this->db->set('password', $password);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', $setup_id);
			$result = $this->db->update('garden_and_company_setup');

			return 2;
		}	
	}

	function save_grade_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$grade_name = trim($this->input->post('grade_name', TRUE));
		$grade_category = trim($this->input->post('grade_category', TRUE));
		$catalogue_name = trim($this->input->post('catalogue_name', TRUE));
		$grade_type = trim($this->input->post('grade_type', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// check grade name
			$this->db->where('grade_name', $grade_name);
			$this->db->where('grade_category', $grade_category);
			$this->db->where('catalogue_name', $catalogue_name);
			$this->db->where('grade_type', $grade_type);
			$this->db->where('soft_delete', 1);
			$grade_data = $this->db->get('tea_grade_setup');

			if($grade_data->num_rows() > 0) {
				return 3;
			}else{
				
				$data = array(
					'grade_name' => $grade_name, 
					'grade_category' => $grade_category, 
					'catalogue_name' => $catalogue_name, 
					'grade_type' => $grade_type, 
					'user' => $user,
					'datetime' => $datetime,
					'last_update' => $last_update
				);
				$result = $this->db->insert('tea_grade_setup',$data);
				return 1;
			}

		}else{

			// update data
			$this->db->where('id!=', $setup_id);
			$this->db->where('grade_name', $grade_name);
			$this->db->where('grade_category', $grade_category);
			$this->db->where('catalogue_name', $catalogue_name);
			$this->db->where('soft_delete', 1);
			$grade_data = $this->db->get('tea_grade_setup');

			if($grade_data->num_rows() > 0) {
				return 3;
			}else{

				$this->db->set('grade_name', $grade_name);
				$this->db->set('grade_category', $grade_category);
				$this->db->set('catalogue_name', $catalogue_name);
				$this->db->set('grade_type', $grade_type);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);
				$result = $this->db->update('tea_grade_setup');
				return 2;
			}
		}
	}

	function save_contact_list_table(){

		$setup_id = trim($this->input->post('setup_id', TRUE));
		$contact_type = trim($this->input->post('contact_type', TRUE));
		$company_id = trim($this->input->post('company_id', TRUE));
		$company_name = trim($this->input->post('company_name', TRUE));
		$garden_id = trim($this->input->post('garden_id', TRUE));
		$garden_name = trim($this->input->post('garden_name', TRUE));
		$buyer_id = trim($this->input->post('buyer_id', TRUE));
		$buyer_name = trim($this->input->post('buyer_name', TRUE));
		$warehouse_id = trim($this->input->post('warehouse_id', TRUE));
		$warehouse_name = trim($this->input->post('warehouse_name', TRUE));
		$contact_details = trim($this->input->post('contact_details', TRUE));
		$contact_designation = trim($this->input->post('contact_designation', TRUE));
		$contact_no = trim($this->input->post('contact_no', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d") . 'T' . date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// Prepare data array for insertion
		$data = array(
			'contact_type' => $contact_type,
			'company_id' => $company_id,
			'company_name' => $company_name,
			'garden_id' => $garden_id,
			'garden_name' => $garden_name,
			'buyer_id' => $buyer_id,
			'buyer_name' => $buyer_name,
			'warehouse_id' => $warehouse_id,
			'warehouse_name' => $warehouse_name,
			'contact_details' => $contact_details,
			'contact_designation' => $contact_designation,
			'contact_no' => $contact_no,
			'user' => $user,
			'datetime' => $datetime,
			'last_update' => $last_update
		);

		// Insert or Update in Localhost Database
		if (empty($setup_id)) {
			$this->db->insert('contact_person_list', $data);
		} else {
			$this->db->where('id', $setup_id);
			$this->db->update('contact_person_list', $data);
		}

		// Send Data to cPanel REST API
		$api_url = "https://www.unitybrokersltd.com/api/contact_api.php"; // Replace with your API URL
		$postData = $data;
		$postData['setup_id'] = $setup_id; // Add setup_id for update check

		// Use cURL to send POST request
		$ch = curl_init($api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

		// Execute request and get response
		$response = curl_exec($ch);
		curl_close($ch);

		// Decode API response
		$result = json_decode($response, true);

		// Return success or failure message
		if ($result && isset($result['status']) && $result['status'] === "success") {
			return 1; // Success
		} else {
			return 0; // Error
		}
	}
	
	function save_buyer_setup_table(){
    
    // get html value
    $setup_id = trim($this->input->post('setup_id', TRUE));
    $buyer_name = trim($this->input->post('buyer_name', TRUE));
    $short_key_code = trim($this->input->post('short_key_code', TRUE));
    $buyer_type = trim($this->input->post('buyer_type', TRUE));
    $buyer_category = trim($this->input->post('buyer_category', TRUE));
    $buyer_address1 = trim($this->input->post('buyer_address1', TRUE));
    $buyer_address2 = trim($this->input->post('buyer_address2', TRUE));
    $buyer_tin_no = trim($this->input->post('buyer_tin_no', TRUE));
    $buyer_bin_no = trim($this->input->post('buyer_bin_no', TRUE));
    $buyer_contact_no = trim($this->input->post('buyer_contact_no', TRUE));
    $buyer_email = trim($this->input->post('buyer_email', TRUE));
    $main_buyer_id_ext = trim($this->input->post('main_buyer_id', TRUE));
    $main_buyer_id = empty($main_buyer_id_ext) ? NULL : $main_buyer_id_ext;
    $password = trim($this->input->post('password', TRUE));
    $user = trim($this->input->post('user', TRUE));
    $datetime = date("Y-m-d H:i:s");
    $last_update = trim($this->input->post('last_update', TRUE));
    
    // Prepare data array
    $data = array(
        'buyer_name' => $buyer_name, 
        'short_key_code' => $short_key_code,
        'buyer_type' => $buyer_type,
        'buyer_category' => $buyer_category,
        'buyer_address1' => $buyer_address1,
        'buyer_address2' => $buyer_address2,
        'buyer_tin_no' => $buyer_tin_no,
        'buyer_bin_no' => $buyer_bin_no,
        'buyer_contact_no' => $buyer_contact_no,
        'buyer_email' => $buyer_email,
        'main_buyer_id' => $main_buyer_id,
        'password' => $password,
        'user' => $user,
        'datetime' => $datetime,
        'last_update' => $last_update
    );
    
    $is_existing_in_remote = false;
    $buyer_id_for_sync = null;
    
    // Insert or Update in Localhost Database
    if (empty($setup_id)) {
        // New insert
        $this->db->insert('buyer_setup', $data);
        $buyer_id_for_sync = $this->db->insert_id();
        $action = 'insert'; // New record, always insert
    } else {
        // Update existing - check if it exists in remote
        $this->db->where('id', $setup_id);
        $this->db->update('buyer_setup', $data);
        $buyer_id_for_sync = $setup_id;
        
        // Check if this buyer already exists in remote table
        $ch = curl_init("https://www.unitybrokersltd.com/api/check_buyer_exists.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['buyer_id' => $buyer_id_for_sync]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $check_result = json_decode($response, true);
        $is_existing_in_remote = ($check_result && $check_result['exists'] === true);
        
        $action = $is_existing_in_remote ? 'update' : 'insert';
    }
    
    // Send Data to Remote API
    $api_url = "https://www.unitybrokersltd.com/api/buyer_sync_api.php";
    $postData = array(
        'setup_id' => $setup_id,
        'buyer_id' => $buyer_id_for_sync,
        'buyer_name' => $buyer_name,
        'short_key_code' => $short_key_code,
        'buyer_type' => $buyer_type,
        'datetime' => $datetime,
        'action' => $action // Tell API whether to insert or update
    );
    
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if (empty($setup_id)) {
        return 1;
    } else {
        return 2;
    }
}
	
	
	function save_warehouse_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$warehouse_name = trim($this->input->post('warehouse_name', TRUE));
		$short_key_word = trim($this->input->post('short_key_word', TRUE));
		$warehouse_serial_1 = trim($this->input->post('warehouse_serial_1', TRUE));
		$warehouse_serial_2 = trim($this->input->post('warehouse_serial_2', TRUE));
		$warehouse_bin_no = trim($this->input->post('warehouse_bin_no', TRUE));
		$warehouse_address = trim($this->input->post('warehouse_address', TRUE));
		$warehouse_address2 = strtoupper(trim($this->input->post('warehouse_address2', TRUE)));
		$warehouse_contact_no = trim($this->input->post('warehouse_contact_no', TRUE));
		$warehouse_email_address = trim($this->input->post('warehouse_email_address', TRUE));
		$rent_per_bag = trim($this->input->post('rent_per_bag', TRUE));
		$password = trim($this->input->post('password', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			$data = array(
				'warehouse_name' => $warehouse_name, 
				'short_key_word' => $short_key_word,
				'warehouse_serial_1' => $warehouse_serial_1,
				'warehouse_serial_2' => $warehouse_serial_2,
				'warehouse_bin_no' => $warehouse_bin_no,
				'warehouse_address' => $warehouse_address,
				'warehouse_address2' => $warehouse_address2,
				'warehouse_contact_no' => $warehouse_contact_no,
				'warehouse_email_address' => $warehouse_email_address,
				'rent_per_bag' => $rent_per_bag,
				'password' => $password,
				'user' => $user,
				'datetime' => $datetime,
				'last_update' => $last_update
			);
			$result = $this->db->insert('warehouse_setup',$data);
			return 1;

		}else{

			$this->db->set('warehouse_name', $warehouse_name);
			$this->db->set('short_key_word', $short_key_word);
			$this->db->set('warehouse_serial_1', $warehouse_serial_1);
			$this->db->set('warehouse_serial_2', $warehouse_serial_2);
			$this->db->set('warehouse_bin_no', $warehouse_bin_no);
			$this->db->set('warehouse_address', $warehouse_address);
			$this->db->set('warehouse_address2', $warehouse_address2);
			$this->db->set('warehouse_contact_no', $warehouse_contact_no);
			$this->db->set('warehouse_email_address', $warehouse_email_address);
			$this->db->set('rent_per_bag', $rent_per_bag);
			//$this->db->set('password', $password);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', $setup_id);
			$result = $this->db->update('warehouse_setup');
			return 2;
			
		}
	}

	function save_season_setup_table(){
    
    $setup_id = trim($this->input->post('setup_id', TRUE));
    $season_name = trim($this->input->post('season_name', TRUE));
    $user = trim($this->input->post('user', TRUE));
    $datetime = date("Y-m-d H:i:s");
    $last_update = trim($this->input->post('last_update', TRUE));
    
    $is_existing_in_remote = false;
    $season_id_for_sync = null;
    
    // insert data
    if(empty($setup_id)){
        
        // check season name
        $this->db->where('season_name', $season_name);
        $this->db->where('soft_delete', 1);
        $season_data = $this->db->get('season_setup');
        
        if($season_data->num_rows() > 0) {
            return 3;
        }else{
            
            $data = array(
                'season_name' => $season_name, 
                'user' => $user,
                'datetime' => $datetime,
                'last_update' => $last_update
            );
            $result = $this->db->insert('season_setup', $data);
            $season_id_for_sync = $this->db->insert_id();
            $action = 'insert'; // New record, always insert
            
            if($result) {
                // Send Data to Remote API
                $api_url = "https://www.unitybrokersltd.com/api/season_sync_api.php";
                $postData = array(
                    'setup_id' => $setup_id,
                    'season_id' => $season_id_for_sync,
                    'season_name' => $season_name,
                    'datetime' => $datetime,
                    'action' => $action
                );
                
                $ch = curl_init($api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                return 1;
            }
            return 0;
        }
        
    }else{
        
        // check season name
        $this->db->where('id!=', $setup_id);
        $this->db->where('season_name', $season_name);
        $this->db->where('soft_delete', 1);
        $season_data = $this->db->get('season_setup');
        
        if($season_data->num_rows() > 0) {
            return 3;
        }else{
            
            $this->db->set('season_name', $season_name);
            $this->db->set('datetime', $datetime);
            $this->db->set('last_update', $last_update);
            $this->db->where('id', $setup_id);
            $result = $this->db->update('season_setup');
            $season_id_for_sync = $setup_id;
            
            if($result) {
                // Check if this season already exists in remote table
                $ch = curl_init("https://www.unitybrokersltd.com/api/check_season_exists.php");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['season_id' => $season_id_for_sync]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                $check_result = json_decode($response, true);
                $is_existing_in_remote = ($check_result && $check_result['exists'] === true);
                
                $action = $is_existing_in_remote ? 'update' : 'insert';
                
                // Send Data to Remote API
                $api_url = "https://www.unitybrokersltd.com/api/season_sync_api.php";
                $postData = array(
                    'setup_id' => $setup_id,
                    'season_id' => $season_id_for_sync,
                    'season_name' => $season_name,
                    'datetime' => $datetime,
                    'action' => $action
                );
                
                $ch = curl_init($api_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                
                $response = curl_exec($ch);
                curl_close($ch);
                
                return 2;
            }
            return 0;
        }
        
    }
}
	
	function save_current_season_setup(){
		
		$season_name = trim($this->input->post('season_name', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// check if the season already exists and is not soft-deleted
		$this->db->where('season_name', $season_name);
		$this->db->where('soft_delete', 1);
		$season_data = $this->db->get('current_season_setup');
		$query = $this->db->get('current_season_setup');

		if($query->num_rows() > 0) {

			$this->db->set('season_name', $season_name);
			$this->db->set('datetime', $datetime);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', 1);
			$this->db->update('current_season_setup');
			return 2; // updated
			
		}else{

			$data = array(
				'season_name'  => $season_name,
				'user'         => $user,
				'datetime'     => $datetime,
				'last_update'  => $last_update
			);
			$this->db->insert('current_season_setup', $data);
			return 1; // inserted
		}
		
	}
	
	function save_current_sale_no_data(){

    $season = trim($this->input->post('season', TRUE));
    $auction_center = trim($this->input->post('auction_center', TRUE));
    $sale_no = trim($this->input->post('sale_no', TRUE));
    $datetime = date("Y-m-d") . 'T' . date("H:i");
    $last_update = trim($this->input->post('last_update', TRUE));

    // check if record already exists and not soft-deleted
    $this->db->where('soft_delete', 1);
    $query = $this->db->get('current_sale_no');

    if($query->num_rows() > 0) {

        // update existing record
        $this->db->set('season', $season);
        $this->db->set('auction_center', $auction_center);
        $this->db->set('sale_no', $sale_no);
        $this->db->set('datetime', $datetime);
        $this->db->set('last_update', $last_update);
        $this->db->where('id', 1);
        $this->db->update('current_sale_no');
        return 2; // updated

    } else {

        // insert new record
        $data = array(
            'season'         => $season,
            'auction_center' => $auction_center,
            'sale_no'        => $sale_no,
            'datetime'       => $datetime,
            'last_update'    => $last_update,
            'soft_delete'    => 1
        );
        $this->db->insert('current_sale_no', $data);
        return 1; // inserted
    }
}


	function save_sale_schedule_setup_table(){
    
    $setup_id = trim($this->input->post('setup_id', TRUE));
    $auction_center = trim($this->input->post('auction_center', TRUE));
    $season_name = trim($this->input->post('season', TRUE));
    $sale_no = trim($this->input->post('sale_no', TRUE));
    $sale_date = trim($this->input->post('sale_date', TRUE));
    $sale_day = trim($this->input->post('sale_day', TRUE));
    $catalouge_closing_date = trim($this->input->post('catalouge_closing_date', TRUE));
    $buyer_prompt_date = trim($this->input->post('buyer_prompt_date', TRUE));
    $comments = trim($this->input->post('comments', TRUE));
    $auction_house = trim($this->input->post('auction_house', TRUE));
    $auction_address = trim($this->input->post('auction_address', TRUE));
    $auction_time = trim($this->input->post('auction_time', TRUE));
    $sample_display_center = trim($this->input->post('sample_display_center', TRUE));
    $position = trim($this->input->post('position', TRUE));
    $user = trim($this->input->post('user', TRUE));
    $datetime = date("Y-m-d").'T'.date("H:i");
    $last_update = trim($this->input->post('last_update', TRUE));
    
    $is_existing_in_remote = false;
    $sale_schedule_id_for_sync = null;
    
    // Insert or Update in Localhost Database
    if(empty($setup_id)){
        // Check duplicate in local
        $this->db->where('auction_center', $auction_center);
        $this->db->where('season_name', $season_name);
        $this->db->where('sale_no', $sale_no);
        $this->db->where('soft_delete', 1);
        $sss_data = $this->db->get('sale_schedule_setup');
        
        if($sss_data->num_rows() > 0) {
            return 3;
        }else{
            $data = array(
                'auction_center' => $auction_center,
                'season_name' => $season_name,
                'sale_no' => $sale_no,
                'sale_date' => $sale_date,
                'sale_day' => $sale_day,
                'catalouge_closing_date' => $catalouge_closing_date,
                'buyer_prompt_date' => $buyer_prompt_date, 
                'comments' => $comments, 
                'auction_house' => $auction_house, 
                'auction_address' => $auction_address, 
                'auction_time' => $auction_time, 
                'sample_display_center' => $sample_display_center, 
                'position' => $position,
                'user' => $user,
                'datetime' => $datetime,
                'last_update' => $last_update
            );
            $result = $this->db->insert('sale_schedule_setup',$data);
            $sale_schedule_id_for_sync = $this->db->insert_id();
            $action = 'insert'; // New record, always insert
        }
    }else{
        // Check duplicate in local (excluding current)
        $this->db->where('id!=', $setup_id);
        $this->db->where('auction_center', $auction_center);
        $this->db->where('season_name', $season_name);
        $this->db->where('sale_no', $sale_no);
        $this->db->where('soft_delete', 1);
        $sss_data = $this->db->get('sale_schedule_setup');
        
        if($sss_data->num_rows() > 0) {
            return 3;
        }else{
            $this->db->set('auction_center', $auction_center);
            $this->db->set('season_name', $season_name);
            $this->db->set('sale_no', $sale_no);
            $this->db->set('sale_date', $sale_date);
            $this->db->set('sale_day', $sale_day);
            $this->db->set('catalouge_closing_date', $catalouge_closing_date);
            $this->db->set('buyer_prompt_date', $buyer_prompt_date);
            $this->db->set('comments', $comments);
            $this->db->set('auction_house', $auction_house);
            $this->db->set('auction_address', $auction_address);
            $this->db->set('auction_time', $auction_time);
            $this->db->set('sample_display_center', $sample_display_center);
            $this->db->set('position', $position);
            $this->db->set('last_update', $last_update);
            $this->db->where('id', $setup_id);
            $result = $this->db->update('sale_schedule_setup');
            $sale_schedule_id_for_sync = $setup_id;
            
            // Check if this sale schedule already exists in remote table
            $ch = curl_init("https://www.unitybrokersltd.com/api/check_sale_schedule_exists.php");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['setup_id' => $sale_schedule_id_for_sync]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $check_result = json_decode($response, true);
            $is_existing_in_remote = ($check_result && isset($check_result['exists']) && $check_result['exists'] === true);
            
            $action = $is_existing_in_remote ? 'update' : 'insert';
        }
    }
    
    // Send Data to Remote API
    $api_url = "https://www.unitybrokersltd.com/api/sale_schedule_sync_api.php";
    $postData = array(
        'setup_id' => $sale_schedule_id_for_sync,
        'auction_center' => $auction_center,
        'season_name' => $season_name,
        'sale_no' => $sale_no,
        'sale_date' => $sale_date,
        'sale_day' => $sale_day,
        'catalouge_closing_date' => $catalouge_closing_date,
        'buyer_prompt_date' => $buyer_prompt_date,
        'comments' => $comments,
        'auction_house' => $auction_house,
        'auction_address' => $auction_address,
        'auction_time' => $auction_time,
        'sample_display_center' => $sample_display_center,
        'position' => $position,
        'user' => $user,
        'datetime' => $datetime,
        'last_update' => $last_update,
        'action' => $action // Tell API whether to insert or update
    );
    
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Log the sync result if needed (optional)
    if ($response === false) {
        // Log error
        error_log("Sale schedule sync failed for ID: " . $sale_schedule_id_for_sync);
    } else {
        $sync_result = json_decode($response, true);
        if ($sync_result && $sync_result['status'] == 'error') {
            error_log("Sale schedule sync error: " . $sync_result['message'] . " for ID: " . $sale_schedule_id_for_sync);
        }
    }
    
    if(empty($setup_id)){
        return 1;
    }else{
        return 2;
    }
}

	function save_manage_page_number_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$season = trim($this->input->post('season', TRUE));
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$catalogue = trim($this->input->post('catalogue', TRUE));
		$lot_no_start = trim($this->input->post('lot_no_start', TRUE));
		$lot_no_end = trim($this->input->post('lot_no_end', TRUE));
		$page_no = trim($this->input->post('page_no', TRUE));
		$catalogue_part = trim($this->input->post('catalogue_part', TRUE));
		$published = trim($this->input->post('published', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// check page no is available
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);		
		$this->db->where('soft_delete', 1);
		$page_no_data = $this->db->get('invoice_entry');

		if($page_no_data->num_rows() < 1) {
			return 2;
		}else{
			// insert data
			$this->db->set('page_no', $page_no);
			$this->db->set('catalogue_part', $catalogue_part);
			$this->db->set('published', $published);
			$this->db->set('published_log', $last_update);
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('catalouge_name', $catalogue);
			$this->db->where("lot_no BETWEEN '$lot_no_start' AND '$lot_no_end'");
			$this->db->update('invoice_entry');

			return 1; 
		}
	}

	function save_all_garden_avg_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$season = trim($this->input->post('season', TRUE));
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$brokers_id = trim($this->input->post('brokers_id', TRUE));
		$brokers_name = trim($this->input->post('brokers_name', TRUE));
		$category = trim($this->input->post('category', TRUE));
		$garden_id = trim($this->input->post('garden_id', TRUE));
		$garden_name = trim($this->input->post('garden_name', TRUE));
		$total_kgs = trim($this->input->post('total_kgs', TRUE));
		$avg_price = trim($this->input->post('avg_price', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d\TH:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// check duplicate data
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('brokers_id', $brokers_id);
			$this->db->where('garden_id', $garden_id);
			$this->db->where('soft_delete', 1);
			$check_duplicate_data = $this->db->get('all_garden_avg');

			if($check_duplicate_data->num_rows() > 0) {
				return 3;
			}else{

				// Insert data
				$data = array(
					'season' => $season,
					'auction_center' => $auction_center,
					'sale_no' => $sale_no,
					'brokers_id' => $brokers_id,
					'brokers_name' => $brokers_name,
					'category' => $category,
					'garden_id' => $garden_id,
					'garden_name' => $garden_name,
					'total_kgs' => $total_kgs,
					'avg_price' => $avg_price,
					'user' => $user,
					'datetime' => $datetime,
					'last_update' => $last_update
				);

				$this->db->insert('all_garden_avg', $data);
				return 1; 
			}
		}else{
			
			// check duplicate data
			$this->db->where('id <>', $setup_id);
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('brokers_id', $brokers_id);
			$this->db->where('garden_id', $garden_id);
			$this->db->where('soft_delete', 1);
			$check_duplicate_data = $this->db->get('all_garden_avg');

			if($check_duplicate_data->num_rows() > 0) {
				return 3;
			}else{

				// Update existing record
				$this->db->set('season', $season);
				$this->db->set('auction_center', $auction_center);
				$this->db->set('sale_no', $sale_no);
				$this->db->set('brokers_id', $brokers_id);
				$this->db->set('brokers_name', $brokers_name);
				$this->db->set('category', $category);
				$this->db->set('garden_id', $garden_id);
				$this->db->set('garden_name', $garden_name);
				$this->db->set('total_kgs', $total_kgs);
				$this->db->set('avg_price', $avg_price);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);

				$this->db->update('all_garden_avg');
				return 2; 
			}
		}
	}

	function save_all_broker_buyer_purchase_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$season = trim($this->input->post('season', TRUE));
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$brokers_id = trim($this->input->post('brokers_id', TRUE));
		$brokers_name = trim($this->input->post('brokers_name', TRUE));
		$category = trim($this->input->post('category', TRUE));
		$buyer_id = trim($this->input->post('buyer_id', TRUE));
		$buyer_name = trim($this->input->post('buyer_name', TRUE));
		$leaf_bags = trim($this->input->post('leaf_bags', TRUE));
		$leaf_kgs = trim($this->input->post('leaf_kgs', TRUE));
		$dust_bags = trim($this->input->post('dust_bags', TRUE));
		$dust_kgs = trim($this->input->post('dust_kgs', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d\TH:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// check duplicate data
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('brokers_id', $brokers_id);
			$this->db->where('buyer_id', $buyer_id);
			$this->db->where('category', $category);
			$this->db->where('soft_delete', 1);
			$check_duplicate_data = $this->db->get('all_broker_buyer_purchase');

			if($check_duplicate_data->num_rows() > 0) {
				return 3;
			}else{

				// Insert data
				$data = array(
					'season' => $season,
					'auction_center' => $auction_center,
					'sale_no' => $sale_no,
					'brokers_id' => $brokers_id,
					'brokers_name' => $brokers_name,
					'category' => $category,
					'buyer_id' => $buyer_id,
					'buyer_name' => $buyer_name,
					'leaf_bags' => $leaf_bags,
					'leaf_kgs' => $leaf_kgs,
					'dust_bags' => $dust_bags,
					'dust_kgs' => $dust_kgs,
					'user' => $user,
					'datetime' => $datetime,
					'last_update' => $last_update
				);

				$this->db->insert('all_broker_buyer_purchase', $data);
				return 1; 
			}
		}else{
			
			// check duplicate data
			$this->db->where('id <>', $setup_id);
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('brokers_id', $brokers_id);
			$this->db->where('buyer_id', $buyer_id);
			$this->db->where('soft_delete', 1);
			$check_duplicate_data = $this->db->get('all_broker_buyer_purchase');

			if($check_duplicate_data->num_rows() > 0) {
				return 3;
			}else{

				// Update existing record
				$this->db->set('season', $season);
				$this->db->set('auction_center', $auction_center);
				$this->db->set('sale_no', $sale_no);
				$this->db->set('brokers_id', $brokers_id);
				$this->db->set('brokers_name', $brokers_name);
				$this->db->set('category', $category);
				$this->db->set('buyer_id', $buyer_id);
				$this->db->set('buyer_name', $buyer_name);
				$this->db->set('leaf_bags', $leaf_bags);
				$this->db->set('leaf_kgs', $leaf_kgs);
				$this->db->set('dust_bags', $dust_bags);
				$this->db->set('dust_kgs', $dust_kgs);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);

				$this->db->update('all_broker_buyer_purchase');
				return 2; 
			}
		}
	}
	
	function report_to_save_garden_avg_table() {
		$garden_avg_data = trim($this->input->post('garden_avg_data', TRUE));
		$rows = explode('|', $garden_avg_data);
	
		foreach ($rows as $row) {
			if (empty($row)) continue;
	
			$values = explode('~', $row);
	
			// Skip if not enough data
			if (count($values) < 12) continue;
	
			// Create key fields to check for duplicates
			$season         = $values[0];
			$auction_center = $values[1];
			$sale_no        = $values[2];
			$brokers_id     = $values[3];
			$garden_id      = $values[5];
			$category       = $values[12];
	
			// Check for duplicate entry in the database
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('brokers_id', $brokers_id);
			$this->db->where('garden_id', $garden_id);
			$this->db->where('category', $category);
			$query = $this->db->get('all_garden_avg');
	
			if ($query->num_rows() > 0) {
				// Already exists, skip insert
				continue;
			}
	
			// If not duplicate, then insert
			$data = array(
				'season'         => $season,
				'auction_center' => $auction_center,
				'sale_no'        => $sale_no,
				'brokers_id'     => $brokers_id,
				'brokers_name'   => $values[4],
				'category'   	 => $values[12],
				'garden_id'      => $garden_id,
				'garden_name'    => $values[6],
				'total_kgs'      => $values[7],
				'avg_price'      => $values[8],
				'user'           => $values[9],
				'datetime'       => $values[10],
				'last_update'    => $values[11]
			);
	
			$this->db->insert('all_garden_avg', $data);
		}
	
		return 1;
	}

	// New delete function (matching your save pattern)
function report_to_delete_garden_avg_table() {
    $season = trim($this->input->post('season', TRUE));
    $auction_center = trim($this->input->post('auction_center', TRUE));
    $sale_no = trim($this->input->post('sale_no', TRUE));
    $brokers_id = trim($this->input->post('brokers_id', TRUE));
    
    // Validate required fields
    if (empty($season) || empty($auction_center) || empty($sale_no) || empty($brokers_id)) {
        return 0;
    }
    
    // Delete records based on the criteria (matching your save logic)
    $this->db->where('season', $season);
    $this->db->where('auction_center', $auction_center);
    $this->db->where('sale_no', $sale_no);
    $this->db->where('brokers_id', $brokers_id);
    
    $result = $this->db->delete('all_garden_avg');
    
    return $result ? 1 : 0;
}
	
	function save_sale_order_table(){
		
		// html value
		$season_name = trim($this->input->post('season', TRUE));
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$brokers_id = trim($this->input->post('brokers_id', TRUE));
		$order_serial = trim($this->input->post('order_serial', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));
		
		// Duplicate sale no
		$this->db->where('season_name', $season_name);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$is_sale_no = $this->db->get('sale_schedule_setup');

		if($is_sale_no->num_rows() < 1) {
			return 3;
		}else{
			
			// Duplicate sale order
			$this->db->where('season_name', $season_name);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('order_serial',$order_serial);
			$this->db->where('soft_delete', 1);
			$duplicate_data = $this->db->get('sale_order_setup');

			if($duplicate_data->num_rows() > 0) {

				$this->db->set('brokers_id', $brokers_id);
				$this->db->set('last_update', $last_update);
				$this->db->where('season_name', $season_name);
				$this->db->where('auction_center', $auction_center);
				$this->db->where('sale_no', $sale_no);
				$this->db->where('order_serial',$order_serial);
				$this->db->where('soft_delete', 1);
				$result = $this->db->update('sale_order_setup');
				return 2;
				
			}else{
				
				$data = array(
					'season_name' => $season_name,
					'auction_center' => $auction_center,
					'sale_no' => $sale_no,
					'brokers_id' => $brokers_id,
					'order_serial' => $order_serial,
					'user' => $user,
					'datetime' => $datetime,
					'last_update' => $last_update
				);
				$result = $this->db->insert('sale_order_setup',$data);
				return 1;
			}
		}
	}

	function save_catalogue_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$catalouge_name = trim($this->input->post('catalouge_name', TRUE));
		$suppliment_tea = trim($this->input->post('suppliment_tea', TRUE));
		$lot_no_start = trim($this->input->post('lot_no_start', TRUE));
		$lot_no_end = trim($this->input->post('lot_no_end', TRUE));
		$auction = trim($this->input->post('auction', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// check catalogue name and suppliment
			$this->db->where('catalouge_name', 	$catalouge_name);
			$this->db->where('suppliment_tea', 	$suppliment_tea);
			$this->db->where('auction', 		$auction);
			$this->db->where('soft_delete', 1);
			$cs_data = $this->db->get('catalogue_setup');

			if($cs_data->num_rows() > 0) {
				return 3;
			}else{

				// associative array = KeyName => KeyValue
				$data = array(
					'catalouge_name' 	=> $catalouge_name,
					'suppliment_tea' 	=> $suppliment_tea,
					'lot_no_start' 		=> $lot_no_start,
					'lot_no_end' 		=> $lot_no_end,
					'auction' 			=> $auction,
					'user' 				=> $user,
					'datetime' 			=> $datetime,
					'last_update' 		=> $last_update
				);
				$result = $this->db->insert('catalogue_setup',$data);
				return 1;
			}

		}else{

			// update data
			// check catalogue name and suppliment
			$this->db->where('id <>', $setup_id);
			$this->db->where('catalouge_name', $catalouge_name);
			$this->db->where('suppliment_tea', $suppliment_tea);
			$this->db->where('auction', 	   $auction);
			$this->db->where('soft_delete', 1);
			$cs_data = $this->db->get('catalogue_setup');

			if($cs_data->num_rows() > 0) {
				return 3;
			}else{

				$this->db->set('catalouge_name', $catalouge_name);
				$this->db->set('suppliment_tea', $suppliment_tea);
				$this->db->set('lot_no_start', 	 $lot_no_start);
				$this->db->set('lot_no_end', 	 $lot_no_end);
				$this->db->set('auction', 	 	 $auction);
				$this->db->set('last_update', 	 $last_update);
				$this->db->where('id', $setup_id);
				$result = $this->db->update('catalogue_setup');
				return 2;
			}

		}
		
	}

	function save_sample_draw_process_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$sample_name = trim($this->input->post('sample_name', TRUE));
		$sample_kgs = trim($this->input->post('sample_kgs', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// check sample name
			$this->db->where('sample_name', $sample_name);
			$this->db->where('soft_delete', 1);
			$sdp_data = $this->db->get('sample_draw_process');

			if($sdp_data->num_rows() > 0) {
				return 3;
			}else{

				$data = array(
					'sample_name' => $sample_name,
					'sample_kgs' => $sample_kgs,
					'user' => $user,
					'datetime' => $datetime,
					'last_update' => $last_update
				);
				$result = $this->db->insert('sample_draw_process',$data);
				return 1;
			}

		}else{
			
			// check sample name
			$this->db->where('id!=', $setup_id);
			$this->db->where('sample_name', $sample_name); 
			$this->db->where('soft_delete', 1);
			$sdp_data = $this->db->get('sample_draw_process');

			if($sdp_data->num_rows() > 0) {
				return 3;
			}else{

				$this->db->set('sample_name', $sample_name);
				$this->db->set('sample_kgs', $sample_kgs);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);
				$result = $this->db->update('sample_draw_process');
				return 2;
			}
		}
	}

	function save_chest_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$chest_name = trim($this->input->post('chest_name', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// check chest name
			$this->db->where('chest_name', $chest_name);
			$this->db->where('soft_delete', 1);
			$chs_data = $this->db->get('chest_setup');
			if($chs_data->num_rows() > 0) {
				return 3;
			}else{

				// associative array = KeyName => KeyValue
				$data = array(
					'chest_name' 	=> $chest_name,
					'user' 			=> $user,
					'datetime' 		=> $datetime,
					'last_update' 	=> $last_update
				);
				$result = $this->db->insert('chest_setup',$data);
				return 1;
			}

		}else{

			// update data
			// check chest name
			$this->db->where('id <>', $setup_id);
			$this->db->where('chest_name', $chest_name);
			$this->db->where('soft_delete', 1);
			$chs_data = $this->db->get('chest_setup');
			if($chs_data->num_rows() > 0) {
				return 3;
			}else{

				$this->db->set('chest_name', $chest_name);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);
				$result = $this->db->update('chest_setup');
				return 2;
			}

		}
		
	}

	function save_brokers_setup_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$brokers_name = trim($this->input->post('brokers_name', TRUE));
		$short_key_code = trim($this->input->post('short_key_code', TRUE));
		$brokers_address = trim($this->input->post('brokers_address', TRUE));
		$brokers_contact_no = trim($this->input->post('brokers_contact_no', TRUE));
		$brokers_email = trim($this->input->post('brokers_email', TRUE));
		$password = trim($this->input->post('password', TRUE));
		$brokers_tin_no = trim($this->input->post('brokers_tin_no', TRUE));
		$brokers_bin_no = trim($this->input->post('brokers_bin_no', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){
			
			$data = array(
				'brokers_name' => $brokers_name, 
				'short_key_code' => $short_key_code, 
				'brokers_address' => $brokers_address,
				'brokers_contact_no' => $brokers_contact_no,
				'brokers_email' => $brokers_email,
				'password' => $password,
				'brokers_tin_no' => $brokers_tin_no,
				'brokers_bin_no' => $brokers_bin_no,
				'user' => $user,
				'datetime' => $datetime,
				'last_update' => $last_update
			);
			$result = $this->db->insert('brokers_setup',$data);
			return 1;

		}else{

			$this->db->set('brokers_name', $brokers_name);
			$this->db->set('short_key_code', $short_key_code);
			$this->db->set('brokers_address', $brokers_address);
			$this->db->set('brokers_contact_no',$brokers_contact_no);
			$this->db->set('brokers_email', $brokers_email);
			//$this->db->set('password', $password);
			$this->db->set('brokers_tin_no', $brokers_tin_no);
			$this->db->set('brokers_bin_no', $brokers_bin_no);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', $setup_id);
			$result = $this->db->update('brokers_setup');
			return 2;
		}	
	}

	function save_sale_condtion_setup_table(){

		$sale_condition = $this->input->post('sale_condition', TRUE);
		$user = $this->input->post('user', TRUE);
		$last_update = $this->input->post('last_update', TRUE);

		$this->db->set('sale_condition', $sale_condition);
		$this->db->set('user', $user);
		$this->db->set('last_update', $last_update);
		$this->db->where('id', 1);
		$result = $this->db->update('condition_of_sale');
		return 1;
	}

	function save_vat_tax_sample_setup_table(){

		$vat_tax_sample = $this->input->post('vat_tax_sample', 	TRUE);
		$user 			= $this->input->post('user', 			TRUE);
		$last_update 	= $this->input->post('last_update', 	TRUE);

		$this->db->set('vat_tax_sample', 	$vat_tax_sample);
		$this->db->set('user', 				$user);
		$this->db->set('last_update', 		$last_update);
		$this->db->where('id', 1);
		$result = $this->db->update('vat_tax_sample_setup');
		return 1;
	}

	function no_of_total_data($form_name){

		if($form_name == 'company_setup'){

			$query = $this->db->query('SELECT * FROM company_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'gc_setup'){

			$query = $this->db->query('SELECT * FROM garden_and_company_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'grade_setup'){

			$query = $this->db->query('SELECT * FROM tea_grade_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'buyer_setup'){

			$query = $this->db->query('SELECT * FROM buyer_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'warehouse_setup'){

			$query = $this->db->query('SELECT * FROM warehouse_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'season_setup'){

			$query = $this->db->query('SELECT * FROM season_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'sale_schedule_setup'){

			$query = $this->db->query('SELECT * FROM sale_schedule_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'catalogue_setup'){

			$query = $this->db->query('SELECT * FROM catalogue_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'sample_draw_process'){

			$query = $this->db->query('SELECT * FROM sample_draw_process WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'chest_setup'){

			$query = $this->db->query('SELECT * FROM chest_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'brokers_setup'){

			$query = $this->db->query('SELECT * FROM brokers_setup WHERE soft_delete = 1');
			return $query->num_rows();

		}else if($form_name == 'sale_condtion_setup'){

			$this->db->select('*'); 
			$this->db->from('condition_of_sale');
			$this->db->where('id',	1);
			$query = $this->db->get();
			$result = $query->row_array();
			if(!empty($result['sale_condition'])){
				return $result['sale_condition'];
			}else{
				return '';
			}
			
		}else if($form_name == 'vat_tax_sample_setup'){

			$this->db->select('*'); 
			$this->db->from('vat_tax_sample_setup');
			$this->db->where('id',	1);
			$query = $this->db->get();
			$result = $query->row_array();
			if(!empty($result['vat_tax_sample'])){
				return $result['vat_tax_sample'];
			}else{
				return '';
			}
		}else if($form_name == 'contact_person_setup'){

			$query = $this->db->query('SELECT * FROM `contact_person_list` WHERE `soft_delete` = 1');
			return $query->num_rows();

		}else if($form_name == 'all_garden_avg'){

			$query = $this->db->query('SELECT * FROM `all_garden_avg` WHERE `soft_delete` = 1');
			return $query->num_rows();

		}
	}
	
	// company setup list
	function get_company_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status");
		$this->db->from('company_setup');
		$this->db->order_by('company_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	// Contact peroson list
	function get_contact_person_data_table(){

        $this->db->select("contact_person_list.*,company_setup.company_name,garden_and_company_setup.garden_name");
		$this->db->from('contact_person_list');
		$this->db->join('company_setup', 'contact_person_list.company_id = company_setup.id', 'left');
		$this->db->join('garden_and_company_setup', 'contact_person_list.garden_id = garden_and_company_setup.id', 'left');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// garden setup list
	function get_garden_and_company_table(){

        $this->db->select("garden_and_company_setup.*, IF(garden_and_company_setup.soft_delete = 1, '', 'danger') AS data_status, company_setup.id AS company_id, company_setup.company_name, company_setup.company_address1, company_setup.company_address2, company_setup.company_contact_no, company_setup.company_email, company_setup.reg_no, garden_origin_setup.origin_name");
		$this->db->from('garden_and_company_setup');
		$this->db->join('company_setup', 'garden_and_company_setup.company_name = company_setup.id', 'left');
		$this->db->join('garden_origin_setup', 'garden_and_company_setup.origin = garden_origin_setup.id', 'left');
		$this->db->group_by('garden_and_company_setup.id');
		$this->db->order_by('garden_and_company_setup.soft_delete DESC, garden_and_company_setup.garden_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	function get_active_garden_table(){

        $this->db->select("*");
		$this->db->from('garden_and_company_setup');
		$this->db->where('soft_delete','1');
		$this->db->group_by('id');
		$this->db->order_by('garden_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_tea_grade_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('tea_grade_setup');
		$this->db->order_by('catalogue_name DESC, grade_category ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_buyer_table(){

        $this->db->select("buyer_setup.*, IF(buyer_setup.soft_delete = 1, '', 'danger') AS data_status, tbl.short_key_code AS main_buyer_name");
		$this->db->from('buyer_setup');
		$this->db->join('buyer_setup tbl','buyer_setup.main_buyer_id = tbl.id','left');
		$this->db->group_by('buyer_setup.id');
		$this->db->order_by('buyer_setup.buyer_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_main_buyers() {
    // OLD // $this->db->select('id, buyer_name, short_key_code'); 
    
    // NEW: Add buyer_type to the select
    $this->db->select('id, buyer_name, short_key_code, buyer_type');

	$this->db->from('buyer_setup');
	$this->db->where('main_buyer_id IS NULL', null, false); // main buyers have no parent
	$this->db->where('soft_delete', 1); // only active buyers
	$this->db->group_by('id');
	$this->db->order_by('buyer_name', 'ASC');
	$query = $this->db->get();
	return $query->result_array();
}




	function get_warehouse_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('warehouse_setup');
		$this->db->order_by('warehouse_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_season_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('season_setup');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_salse_schedule_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('sale_schedule_setup');
		$this->db->order_by('auction_center ASC, season_name DESC, sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	function get_all_garden_avg_data_table() {

		$this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('all_garden_avg');
		$this->db->order_by('id DESC');
		$this->db->limit(5); 
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function get_all_brokers_buyer_purchase_data_table() {

		$this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('all_broker_buyer_purchase');
		$this->db->order_by('id DESC');
		$this->db->limit(5); 
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}

	function auction_schedule_table_db($parameters){
		
		$season_name = $parameters['season_name'];
		$auction_center = $parameters['auction_center'];
		
        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('sale_schedule_setup');
		$this->db->where('season_name',$season_name);
		$this->db->where('auction_center',$auction_center);
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_catalogue_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('catalogue_setup');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_lot_wise_catalogue_table($data_lot_no){
	
		$lot_no = $data_lot_no['get_lot_no'];

		$this->db->select('`catalouge_name`,`suppliment_tea`,`auction`');
		$this->db->from('catalogue_setup');
		$this->db->where('lot_no_start <=', $lot_no);
		$this->db->where('lot_no_end >=', $lot_no);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row();
		return $result;
	}

	function get_sample_draw_p_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('sample_draw_process');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_chest_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('chest_setup');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_brokers_data_table(){
        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('brokers_setup');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function brokers_list_without_ubl(){
        $this->db->select("id, brokers_name"); 
		$this->db->from('brokers_setup');
		$this->db->where('id != 6');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_brokers_list_table(){
        $this->db->select("*"); 
		$this->db->from('brokers_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	function get_single_brokers_data_table($catp_data_arg){
		//post value
		$brokers = $catp_data_arg['brokers']; // ID
		// run query
        $this->db->select("brokers_name,brokers_address,brokers_contact_no,brokers_email"); 
		$this->db->from('brokers_setup');
		$this->db->where('id', $brokers);
		$this->db->order_by('brokers_name ASC');
		$query = $this->db->get();
		$result = $query->row_array();
        return $result['brokers_name'].'~'.$result['brokers_address'].'~'.$result['brokers_contact_no'].'~'.$result['brokers_email'];
	}
	
	function get_sale_schedule_data_table($catp_data_arg){
		//post value
		$sale_no = $catp_data_arg['sale_no'];
		// run query
        $this->db->select("*"); 
		$this->db->from('sale_schedule_setup');
		$this->db->where('sale_no', $sale_no);
		$query = $this->db->get();
		$result = $query->row_array();
        return $result['buyer_prompt_date'];
	}

	function get_regulatory_data_table(){

        $this->db->select("*, IF(soft_delete = 1, '', 'danger') AS data_status"); 
		$this->db->from('regulatory_setup');
		$this->db->order_by('regulatory_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_profile_data_table($profile_arg){

		$profile_name = $profile_arg['profile_name'];
		$profile_id = $profile_arg['profile_id'];

		if($profile_name=='gc_profile'){

			$this->db->select("garden_and_company_setup.*, company_setup.id AS company_id, company_setup.company_name, company_setup.company_address1, company_setup.company_address2, company_setup.company_contact_no, company_setup.company_email, company_setup.reg_no");
			$this->db->from('garden_and_company_setup');
			$this->db->join('company_setup','garden_and_company_setup.company_name = company_setup.id','left');
			$this->db->where('garden_and_company_setup.id',	$profile_id);
			$query = $this->db->get();
			$result = $query->row_array();
			return "<tr>
						<th style=\"vertical-align:middle;\">Garden name</th>
						<td style=\"vertical-align:middle;\">$result[garden_name]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Garden type</th>
						<td style=\"vertical-align:middle;\">$result[garden_type]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Garden address</th>
						<td style=\"vertical-align:middle;\"> 1) $result[garden_address1] <hr> 2) $result[garden_address2]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Garden contact</th>
						<td style=\"vertical-align:middle;\">$result[garden_contact_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Garden email</th>
						<td style=\"vertical-align:middle;\">$result[garden_email]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Garden origin</th>
						<td style=\"vertical-align:middle;\">$result[origin]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Company name</th>
						<td style=\"vertical-align:middle;\">$result[company_name]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Company address</th>
						<td style=\"vertical-align:middle;\"> 1) $result[company_address1] <hr> 2) $result[company_address2]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Company contact</th>
						<td style=\"vertical-align:middle;\">$result[company_contact_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Company email</th>
						<td style=\"vertical-align:middle;\">$result[company_email]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Company reg.no.</th>
						<td style=\"vertical-align:middle;\">$result[reg_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Last update</th>
						<td style=\"vertical-align:middle;\">$result[last_update]</td>
					</tr>";
		
		}else if($profile_name=='buyer_profile'){

			$this->db->select('*'); 
			$this->db->from('buyer_setup');
			$this->db->where('id',	$profile_id);
			$query = $this->db->get();
			$result = $query->row_array();
			return "<tr>
						<th style=\"vertical-align:middle;\">Buyer name</th>
						<td style=\"vertical-align:middle;\">$result[buyer_name] / $result[short_key_code]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Buyer type</th>
						<td style=\"vertical-align:middle;\">$result[buyer_type]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Buyer category</th>
						<td style=\"vertical-align:middle;\">$result[buyer_category]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Address</th>
						<td style=\"vertical-align:middle;\"> 1) $result[buyer_address1] <hr> 2) $result[buyer_address2]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Buyer contact</th>
						<td style=\"vertical-align:middle;\">$result[buyer_contact_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">TIN No.</th>
						<td style=\"vertical-align:middle;\">$result[buyer_tin_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">BIN No.</th>
						<td style=\"vertical-align:middle;\">$result[buyer_bin_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Email</th>
						<td style=\"vertical-align:middle;\">$result[buyer_email]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Last update</th>
						<td style=\"vertical-align:middle;\">$result[last_update]</td>
					</tr>";
		
		}else if($profile_name=='warehouse_profile'){

			$this->db->select('*'); 
			$this->db->from('warehouse_setup');
			$this->db->where('id',	$profile_id);
			$query = $this->db->get();
			$result = $query->row_array();
			return "<tr>
						<th style=\"vertical-align:middle;\">Warehouse name</th>
						<td style=\"vertical-align:middle;\">$result[warehouse_name] / $result[short_key_word]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Warehouse SL 1</th>
						<td style=\"vertical-align:middle;\">$result[warehouse_serial_1]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Warehouse SL 2</th>
						<td style=\"vertical-align:middle;\">$result[warehouse_serial_2]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Address</th>
						<td style=\"vertical-align:middle;\">$result[warehouse_address]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Warehouse contact</th>
						<td style=\"vertical-align:middle;\">$result[warehouse_contact_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">BIN No.</th>
						<td style=\"vertical-align:middle;\">$result[warehouse_bin_no]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Email</th>
						<td style=\"vertical-align:middle;\">$result[warehouse_email_address]</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Rent Per Bag</th>
						<td style=\"vertical-align:middle;\">$result[rent_per_bag] Tk</td>
					</tr>
					<tr>
						<th style=\"vertical-align:middle;\">Last update</th>
						<td style=\"vertical-align:middle;\">$result[last_update]</td>
					</tr>";
		
		}

	}



	## ++++++++++++++++++++++++++++
	## ++++++ BEGUN Web Module ++++
	## ++++++++++++++++++++++++++++

	function web_login_process_data($parameters){

		$user = $parameters['user'];
		$pass = $parameters['pass'];
		
		// query
		$this->db->select('*'); 
		$this->db->from('super_admin');
		$this->db->where('`username` = "'. $user. '" AND `password` = "'. $pass. '" ');
		$query = $this->db->get();
		$result = $query->row();
        return $result;

	}
	
	// Catalogue Header information
	function save_cover_page_info_table(){
		
		// html data
		$cover_page_text = $this->input->post('cover_page_text', TRUE);
		$user = $this->input->post('user', TRUE);
		$last_update = $this->input->post('last_update', TRUE);
		$datetime  = date("Y-m-d").'T'.date("H:i");

		//duplicate data check
		$this->db->where('soft_delete', 1);
		$duplicate_data = $this->db->get('cover_page_info_setup');

		if($duplicate_data->num_rows() < 1) {

			$data = array(
				'cover_page_text' 	=> $cover_page_text,
				'user' 				=> $user,
				'last_update' 		=> $last_update,
				'datetime' 			=> $datetime
			);
			$result = $this->db->insert('cover_page_info_setup',$data);
			return 1;

		}else{

			$this->db->set('cover_page_text', $cover_page_text);
			$this->db->set('last_update', $last_update);
			$this->db->where('soft_delete', 1);
			$result = $this->db->update('cover_page_info_setup');
			return 1;
		}
	}

	//garden letter entry
	function save_garden_letter_table(){
		
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$season = trim($this->input->post('season', TRUE));
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$garden_letter_text = trim($this->input->post('garden_letter_text', TRUE));
		$gl_story_text = trim($this->input->post('gl_story_text', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d\TH:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// check duplicate data
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('soft_delete', 1);
			$check_duplicate_data = $this->db->get('garden_letter_setup');

			if($check_duplicate_data->num_rows() > 0) {
				return 3;
			}else{

				// Insert data
				$data = array(
					'season' => $season,
					'auction_center' => $auction_center,
					'sale_no' => $sale_no,
					'garden_letter_text' => $garden_letter_text,
					'gl_story_text' => $gl_story_text,
					'user' => $user,
					'datetime' => $datetime,
					'last_update' => $last_update
				);

				$this->db->insert('garden_letter_setup', $data);
				return 1; 
			}
		}else{
			
			// check duplicate data
			$this->db->where('id <>', $setup_id);
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('soft_delete', 1);
			$check_duplicate_data = $this->db->get('garden_letter_setup');

			if($check_duplicate_data->num_rows() > 0) {
				return 3;
			}else{

				// Update existing record
				$this->db->set('season', $season);
				$this->db->set('auction_center', $auction_center);
				$this->db->set('sale_no', $sale_no);
				$this->db->set('garden_letter_text', $garden_letter_text);
				$this->db->set('gl_story_text', $gl_story_text);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);

				$this->db->update('garden_letter_setup');
				return 2; 
			}
		}
	}
	
	// save data
	function save_catalogue_published_info_table(){
		
		// html data
		$season = $this->input->post('season',TRUE);
		$auction_center = $this->input->post('auction_center',TRUE);
		$sale_no = $this->input->post('sale_no',TRUE);
		$published = $this->input->post('published',TRUE);
		$user = $this->input->post('user',TRUE);
		$last_update = $this->input->post('last_update',TRUE);

		// check page no is available
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);		
		$this->db->where('soft_delete', 1);
		$page_no_data = $this->db->get('invoice_entry');

		if($page_no_data->num_rows() < 1) {
			return 2;
		}else{
			
			// update query
			$this->db->set('published', $published);
			$this->db->set('published_log', $last_update);
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);		
			$this->db->where('soft_delete', 1);
			$result = $this->db->update('invoice_entry');
			return 1;
		}
	}
	
	// save data
	function save_valuation_published_info_table(){
		
		// html value
		$season = $this->input->post('season',TRUE);
		$auction_center = $this->input->post('auction_center',TRUE);
		$sale_no = $this->input->post('sale_no',TRUE);
		$published = $this->input->post('published',TRUE);
		$user = $this->input->post('user',TRUE);
		$last_update = $this->input->post('last_update',TRUE);
		
		// update query
		($published=='No') ? $this->db->set('valuation_publish', NULL) : $this->db->set('valuation_publish', $published);
		$this->db->set('valuation_publish_log', $last_update);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$result = $this->db->update('invoice_entry');
		return 1;
	}
	
	// save data
	function save_lot_valuation_table(){
		
		// html data
		$season = $this->input->post('season', TRUE);
		$auction_center = $this->input->post('auction_center', TRUE);
		$sale_no = $this->input->post('sale_no', TRUE);
		$lot_no = $this->input->post('lot_no', TRUE);
		$net_kgs = $this->input->post('net_kgs', TRUE);
		$valuation = $this->input->post('valuation', TRUE);
		$total_valuation = $net_kgs * $valuation;
		
		// run query
		$this->db->set('valuation', $valuation);
		$this->db->set('total_valuation', $total_valuation);
		$this->db->set('valuation_date', date("Y-m-d H:i:s"));
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('lot_no', $lot_no);
		$result = $this->db->update('invoice_entry');
		return 1;
	}
	
	// save data
	function save_all_brokers_average_table() {
		// html data
		$season = $this->input->post('season', TRUE);
		$auction_center = $this->input->post('auction_center', TRUE);
		$sale_no = $this->input->post('sale_no', TRUE);
		$brokers_id = $this->input->post('brokers_id', TRUE);
		$brokers_name = $this->input->post('brokers_name', TRUE);
		$category = $this->input->post('category', TRUE);
		$pkgs = $this->input->post('pkgs', TRUE);
		$qty =  $this->input->post('qty', TRUE);
		$avg =  $this->input->post('avg', TRUE);
		$total_amount = (is_numeric($avg) ? (float)$avg : 0) * (is_numeric($qty) ? (float)$qty : 0);
		$user = $this->input->post('user', TRUE);
		$datetime = date("Y-m-d\TH:i");
		$last_update = $this->input->post('last_update', TRUE);
	
		// check if record exists
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('brokers_id', $brokers_id);
		$this->db->where('category', $category);
		$num_rows = $this->db->get('all_brokers_average_entry');
	
		if ($num_rows->num_rows() > 0) {
			// Update
			$this->db->set('season', $season);
			$this->db->set('auction_center', $auction_center);
			$this->db->set('sale_no', $sale_no);
			$this->db->set('brokers_id', $brokers_id);
			$this->db->set('brokers_name', $brokers_name);
			$this->db->set('category', $category);
			$this->db->set('pkgs', $pkgs);
			$this->db->set('qty', $qty);
			$this->db->set('avg', $avg);
			$this->db->set('total_amount', $total_amount);
			$this->db->set('user', $user);
			$this->db->set('datetime', $datetime);
			$this->db->set('last_update', $last_update);
			
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('brokers_id', $brokers_id);
			$this->db->where('category', $category);
	
			if ($this->db->update('all_brokers_average_entry')) {
				return ['status' => true, 'message' => 'Saved successfully!'];
			} else {
				return ['status' => false, 'message' => 'Failed to save.'];
			}
	
		} else {
			// Insert
			$data = array(
				'season'         => $season,
				'auction_center' => $auction_center,
				'sale_no'        => $sale_no,
				'brokers_id'     => $brokers_id,
				'brokers_name'   => $brokers_name,
				'category'   	 => $category,
				'pkgs'           => $pkgs,
				'qty'            => $qty,
				'avg'            => $avg,
				'total_amount'   => $total_amount,
				'user'           => $user,
				'datetime'       => $datetime,
				'last_update'    => $last_update
			);
			if ($this->db->insert('all_brokers_average_entry', $data)) {
				return ['status' => true, 'message' => 'Saved successfully!'];
			} else {
				return ['status' => false, 'message' => 'Failed to save.'];
			}
		}
	}
	
	// save data
	function save_market_average_table() {
		// html data
		$season = $this->input->post('season', TRUE);
		$auction_center = $this->input->post('auction_center', TRUE);
		$sale_no = $this->input->post('sale_no', TRUE);
		$earlier_season = $this->input->post('earlier_season', TRUE);
		$current_season = $this->input->post('current_season', TRUE);
		$total_amount = (is_numeric($earlier_season) ? (float)$earlier_season : 0) * (is_numeric($current_season) ? (float)$current_season : 0);
		$user = $this->input->post('user', TRUE);
		$datetime = date("Y-m-d\TH:i");
		$last_update = $this->input->post('last_update', TRUE);
	
		// check if record exists
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$num_rows = $this->db->get('market_average_entry');
	
		if ($num_rows->num_rows() > 0) {
			// Update
			$this->db->set('season', $season);
			$this->db->set('auction_center', $auction_center);
			$this->db->set('sale_no', $sale_no);
			$this->db->set('earlier_season', $earlier_season);
			$this->db->set('current_season', $current_season);
			$this->db->set('total_amount', $total_amount);
			$this->db->set('user', $user);
			$this->db->set('datetime', $datetime);
			$this->db->set('last_update', $last_update);
			
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
	
			if ($this->db->update('market_average_entry')) {
				return ['status' => true, 'message' => 'Saved successfully!'];
			} else {
				return ['status' => false, 'message' => 'Failed to save.'];
			}
	
		} else {
			// Insert
			$data = array(
				'season'        	=> $season,
				'auction_center' 	=> $auction_center,
				'sale_no'        	=> $sale_no,
				'earlier_season'    => $earlier_season,
				'current_season'    => $current_season,
				'total_amount'   	=> $total_amount,
				'user'           	=> $user,
				'datetime'       	=> $datetime,
				'last_update'    	=> $last_update
			);
			if ($this->db->insert('market_average_entry', $data)) {
				return ['status' => true, 'message' => 'Saved successfully!'];
			} else {
				return ['status' => false, 'message' => 'Failed to save.'];
			}
		}
	}
	
	// save data
	function save_market_report_table(){
		$season         = $this->input->post('season', TRUE);
		$auction_center = $this->input->post('auction_center', TRUE);
		$sale_no        = $this->input->post('sale_no', TRUE);
		$user           = $this->input->post('user', TRUE);
		$last_update    = $this->input->post('last_update', TRUE);
		$introduction   = $this->input->post('introduction', TRUE);
		$leaf           = $this->input->post('leaf', TRUE);
		$dust           = $this->input->post('dust', TRUE);
		$old_season     = $this->input->post('old_season', TRUE);
		$supplements_e_auction = $this->input->post('supplements_e_auction', TRUE);
		$upcomeing_sale_offering_info = $this->input->post('upcomeing_sale_offering_info', TRUE);
		$avg = $this->input->post('avg', TRUE);
		$sold_percentage = $this->input->post('sold_percentage', TRUE);
		$comments       = $this->input->post('comments', TRUE);

		$brokens_best_amount = $this->input->post('brokens_best_amount', TRUE);
		$brokens_best_amountupto = $this->input->post('brokens_best_amountupto', TRUE);
		$brokens_good_amount = $this->input->post('brokens_good_amount', TRUE);
		$brokens_good_amountupto = $this->input->post('brokens_good_amountupto', TRUE);
		$brokens_medium_amount = $this->input->post('brokens_medium_amount', TRUE);
		$brokens_medium_amountupto = $this->input->post('brokens_medium_amountupto', TRUE);
		$brokens_plain_amount = $this->input->post('brokens_plain_amount', TRUE);
		$brokens_plain_amountupto = $this->input->post('brokens_plain_amountupto', TRUE);
		$brokens_northern_amount = $this->input->post('brokens_northern_amount', TRUE);
		$brokens_northern_amountupto = $this->input->post('brokens_northern_amountupto', TRUE);

		$fannings_best_amount = $this->input->post('fannings_best_amount', TRUE);
		$fannings_best_amountupto = $this->input->post('fannings_best_amountupto', TRUE);
		$fannings_good_amount = $this->input->post('fannings_good_amount', TRUE);
		$fannings_good_amountupto = $this->input->post('fannings_good_amountupto', TRUE);
		$fannings_medium_amount = $this->input->post('fannings_medium_amount', TRUE);
		$fannings_medium_amountupto = $this->input->post('fannings_medium_amountupto', TRUE);
		$fannings_plain_amount = $this->input->post('fannings_plain_amount', TRUE);
		$fannings_plain_amountupto = $this->input->post('fannings_plain_amountupto', TRUE);
		$fannings_northern_amount = $this->input->post('fannings_northern_amount', TRUE);
		$fannings_northern_amountupto = $this->input->post('fannings_northern_amountupto', TRUE);
		$datetime       = date("Y-m-d\TH:i");

		// Step 2: Build data array for insert/update
		$data = array(
			'season' => $season,
			'auction_center' => $auction_center,
			'sale_no' => $sale_no,
			'user' => $user,
			'last_update' => $last_update,
			'introduction' => $introduction,
			'leaf' => $leaf,
			'dust' => $dust,
			'old_season' => $old_season,
			'supplements_e_auction' => $supplements_e_auction,
			'upcomeing_sale_offering_info' => $upcomeing_sale_offering_info,
			'avg' => $avg,
			'sold_percentage' => $sold_percentage,
			'comments' => $comments,

			'brokens_best_amount' => $brokens_best_amount,
			'brokens_best_amountupto' => $brokens_best_amountupto,
			'brokens_good_amount' => $brokens_good_amount,
			'brokens_good_amountupto' => $brokens_good_amountupto,
			'brokens_medium_amount' => $brokens_medium_amount,
			'brokens_medium_amountupto' => $brokens_medium_amountupto,
			'brokens_plain_amount' => $brokens_plain_amount,
			'brokens_plain_amountupto' => $brokens_plain_amountupto,
			'brokens_northern_amount' => $brokens_northern_amount,
			'brokens_northern_amountupto' => $brokens_northern_amountupto,

			'fannings_best_amount' => $fannings_best_amount,
			'fannings_best_amountupto' => $fannings_best_amountupto,
			'fannings_good_amount' => $fannings_good_amount,
			'fannings_good_amountupto' => $fannings_good_amountupto,
			'fannings_medium_amount' => $fannings_medium_amount,
			'fannings_medium_amountupto' => $fannings_medium_amountupto,
			'fannings_plain_amount' => $fannings_plain_amount,
			'fannings_plain_amountupto' => $fannings_plain_amountupto,
			'fannings_northern_amount' => $fannings_northern_amount,
			'fannings_northern_amountupto' => $fannings_northern_amountupto
		);

		// Check if already exists
		$this->db->where([
			'season' => $season,
			'auction_center' => $auction_center,
			'sale_no' => $sale_no
		]);
		$query = $this->db->get('market_report_entry');

		if ($query->num_rows() > 0) {
			// Update existing
			$this->db->where([
				'season' => $season,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no
			]);
			$this->db->update('market_report_entry', $data);
			return ['status' => true, 'message' => 'Updated successfully!'];
		} else {
			// Insert new
			$data['datetime'] = $datetime;
			$this->db->insert('market_report_entry', $data);
			return ['status' => true, 'message' => 'Inserted successfully!'];
		}
	}

	// save data
	function save_market_offerings_table() {
		// html data
		$season = $this->input->post('season', TRUE);
		$auction_center = $this->input->post('auction_center', TRUE);
		$sale_no = $this->input->post('sale_no', TRUE);
		$brokers_id = $this->input->post('brokers_id', TRUE);
		$brokers = $this->input->post('brokers', TRUE);
		$leaf_offerings = $this->input->post('leaf_offerings', TRUE);
		$leaf_packeteer = $this->input->post('leaf_packeteer', TRUE);
		$leaf_internal = $this->input->post('leaf_internal', TRUE);
		$dust_offerings = $this->input->post('dust_offerings', TRUE);
		$dust_packeteer = $this->input->post('dust_packeteer', TRUE);
		$dust_internal = $this->input->post('dust_internal', TRUE);
		$supplement_offerings = $this->input->post('supplement_offerings', TRUE);
		$supplement_packeteer = $this->input->post('supplement_packeteer', TRUE);
		$supplement_internal = $this->input->post('supplement_internal', TRUE);
		$user = $this->input->post('user', TRUE);
		$datetime = date("Y-m-d\TH:i");
		$last_update = $this->input->post('last_update', TRUE);
	
		// check if record exists
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('brokers_id', $brokers_id);
		$num_rows = $this->db->get('market_report_offerings_entry');
	
		if ($num_rows->num_rows() > 0) {
			// Update
			$this->db->set('season', $season);
			$this->db->set('auction_center', $auction_center);
			$this->db->set('sale_no', $sale_no);
			$this->db->set('brokers_id', $brokers_id);
			$this->db->set('brokers', $brokers);
			$this->db->set('leaf_offerings', $leaf_offerings);
			$this->db->set('leaf_packeteer', $leaf_packeteer);
			$this->db->set('leaf_internal', $leaf_internal);
			$this->db->set('dust_offerings', $dust_offerings);
			$this->db->set('dust_packeteer', $dust_packeteer);
			$this->db->set('dust_internal', $dust_internal);
			$this->db->set('supplement_offerings', $supplement_offerings);
			$this->db->set('supplement_packeteer', $supplement_packeteer);
			$this->db->set('supplement_internal', $supplement_internal);
			$this->db->set('user', $user);
			$this->db->set('datetime', $datetime);
			$this->db->set('last_update', $last_update);
			
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('sale_no', $sale_no);
			$this->db->where('brokers_id', $brokers_id);
	
			if ($this->db->update('market_report_offerings_entry')) {
				return ['status' => true, 'message' => 'Saved successfully!'];
			} else {
				return ['status' => false, 'message' => 'Failed to save.'];
			}
	
		} else {
			// Insert
			$data = array(
				'season'        		=> $season,
				'auction_center' 		=> $auction_center,
				'sale_no'        		=> $sale_no,
				'brokers_id'    		=> $brokers_id,
				'brokers'    			=> $brokers,
				'leaf_offerings'    	=> $leaf_offerings,
				'leaf_packeteer'    	=> $leaf_packeteer,
				'leaf_internal'   		=> $leaf_internal,
				'dust_offerings'    	=> $dust_offerings,
				'dust_packeteer'    	=> $dust_packeteer,
				'dust_internal'   		=> $dust_internal,
				'supplement_offerings'  => $supplement_offerings,
				'supplement_packeteer'  => $supplement_packeteer,
				'supplement_internal'   => $supplement_internal,
				'user'           		=> $user,
				'datetime'       		=> $datetime,
				'last_update'    		=> $last_update
			);
			if ($this->db->insert('market_report_offerings_entry', $data)) {
				return ['status' => true, 'message' => 'Saved successfully!'];
			} else {
				return ['status' => false, 'message' => 'Failed to save.'];
			}
		}
	}

	// save data
	function save_sold_wd_table(){
		
		// variable define
		$lot_id = $this->input->post('lot_id', TRUE);
		$sold_wd = $this->input->post('sold_wd', TRUE);
		$sold_wd_log = $this->input->post('user_log', TRUE);
		$sold_wd_datetime = date("Y-m-d").'T'.date("H:i");
		
		// run update query
		$this->db->set('sold_wd', $sold_wd); 
		$this->db->set('sold_wd_log', $sold_wd_log);
		$this->db->set('sold_wd_datetime', $sold_wd_datetime);
		
		$this->db->where('id', $lot_id);
		
		$result = $this->db->update('invoice_entry');
		return 1;
	}
	
	// save data
	function save_page_setup_info_table(){

		$page_name 		= $this->input->post('page_name', TRUE);
		$page_size 		= $this->input->post('page_size', TRUE);
		$user 			= $this->input->post('user', TRUE);
		$datetime 		= date("Y-m-d").'T'.date("H:i");
		$last_update 	= $this->input->post('last_update', TRUE);
		
		// check brokers data
		$this->db->where('page_name',$page_name);	
		$this->db->where('soft_delete', 1);
		$psize_data = $this->db->get('page_size_info_setup');
		
		if($psize_data->num_rows() > 0) {

			$this->db->set('page_size', 	$page_size);
			$this->db->set('last_update', 	$last_update);
			
			$this->db->where('page_name',	$page_name);
			$this->db->where('user',		$user); // brokers id		
			$this->db->where('soft_delete', 1);
			$result = $this->db->update('page_size_info_setup');
			return 1;
			
		}else{
			
			// associative array = KeyName(Column) => KeyValue(HTML value)
			$data = array(
				'page_name' 	=> $page_name,
				'page_size' 	=> $page_size,
				'user' 			=> $user,
				'datetime'  	=> $datetime,
				'last_update'	=> $last_update
			);
			$result = $this->db->insert('page_size_info_setup',$data);
			return 1;
		}
	}
	
	// INVOICE ENTRY
	function save_brokers_invoice_info_table(){
		
		// html data
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$season = trim($this->input->post('season', TRUE));
		$catalouge_name = trim($this->input->post('catalouge_name', TRUE));
		$supplement_tea = trim($this->input->post('supplement_tea', TRUE));
		$chest_name = trim($this->input->post('chest_name', TRUE));
		$old_season = trim($this->input->post('old_season', TRUE));
		$category = trim($this->input->post('category', TRUE));
		$invoice = trim($this->input->post('invoice', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$sale_date = trim($this->input->post('sale_date', TRUE));
		$group_no = trim($this->input->post('group_no', TRUE));
		$lot_no = trim($this->input->post('lot_no', TRUE));
		$lot_type = trim($this->input->post('lot_type', TRUE));
		$bulk_lot_cb = trim($this->input->post('bulk_lot', TRUE));
		$bulk_lot = (!empty($bulk_lot_cb)) ? 'y' : 'n';
		$old_sale_no = trim($this->input->post('old_sale_no', TRUE));
		$old_season2 = trim($this->input->post('old_season2', TRUE));
		$ol_ac = trim($this->input->post('ol_ac', TRUE));
		$old_lot_no = trim($this->input->post('old_lot_no', TRUE));
		$ol_os_sc = trim($this->input->post('ol_os_sc', TRUE));
		$w_house_id = trim($this->input->post('w_house_id', TRUE));
		$garden_id = trim($this->input->post('garden_id', TRUE));
		$invoice_group_txt = strtoupper(trim($this->input->post('invoice_group_txt', TRUE)));
		$invoice_group = trim($this->input->post('invoice_group', TRUE));
		$invoice_group_upto = trim($this->input->post('invoice_group_upto', TRUE));
		$w_inv_no = trim($this->input->post('w_inv_no', TRUE));
		$grade_id = trim($this->input->post('grade_id', TRUE));
		$additional_grade = trim($this->input->post('additional_grade', TRUE));
		$chest_number = trim($this->input->post('chest_number', TRUE));
		$gross_wt_per_bag = trim($this->input->post('gross_wt_per_bag', TRUE));
		$net_wt_per_bag = trim($this->input->post('net_wt_per_bag', TRUE));
		$total_kgs = trim($this->input->post('total_kgs', TRUE));
		$sample_kgs = trim($this->input->post('sample_kgs', TRUE));
		$additional_sample_kgs = trim($this->input->post('additional_sample_kgs', TRUE));
		$weight_short_cb = trim($this->input->post('weight_short', TRUE));
		$weight_short = (!empty($weight_short_cb)) ? 'y' : 'n';
		$net_kgs = trim($this->input->post('net_kgs', TRUE));
		$remarks = trim($this->input->post('remarks', TRUE));
		$short_note = trim($this->input->post('short_note', TRUE));
		$pre_invoice_id = trim($this->input->post('pre_invoice_id', TRUE));
		$org_id = trim($this->input->post('org_id', TRUE));
		$reprinted_invoice = trim($this->input->post('reprinted_invoice', TRUE));
		$gross_wt_kgs = $chest_number * $net_wt_per_bag;
		$net_sample_kgs = $gross_wt_kgs - $net_kgs;
		$e_auc = !empty(trim($this->input->post('e_auc', TRUE))) ? "Yes" : "No";
		$lot_part = !empty(trim($this->input->post('lot_part', TRUE))) ? "Yes" : "No";
		$account_concerned = !empty(trim($this->input->post('account_concerned', TRUE))) ? "Yes" : "No";
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// Duplicate Lot No
			$this->db->where('season',$season);
			$this->db->where('auction_center',$auction_center);
			$this->db->where('catalouge_name',$catalouge_name);
			$this->db->where('sale_no',$sale_no);
			$this->db->where('lot_no',$lot_no);			
			$this->db->where('soft_delete', 1);
			$lot_no_d_data = $this->db->get('invoice_entry');
			$number_of_lot = ($bulk_lot=='y') ? '0' : $lot_no_d_data->num_rows();
			
			// Duplicate Invoice No
			$this->db->where('season', $season);
			$this->db->where('auction_center', $auction_center);
			$this->db->where('catalouge_name', $catalouge_name);
			$this->db->where('sale_no',	$sale_no);
			$this->db->where('garden_id', $garden_id);
			$this->db->where('w_inv_no', $w_inv_no);
			$this->db->where('grade_id', $grade_id);						
			$this->db->where('soft_delete', 1);
			$w_inv_d_data = $this->db->get('invoice_entry');
			$number_of_invoice = ($bulk_lot=='y' || $lot_part=='Yes') ? '0' : $w_inv_d_data->num_rows();

			if($number_of_lot > 0) {
				return 3;
			}else if($number_of_invoice > 0){
				return 4;
			}else{

				$data = array(
					'season' => $season,
					'auction_center' => $auction_center,
					'catalouge_name' => $catalouge_name,
					'supplement_tea' => $supplement_tea,
					'chest_name' => $chest_name,
					'old_season' => $old_season,
					'sale_no' => $sale_no,
					'sale_date' => $sale_date,
					'invoice' => $invoice,
					'category' => $category,
					'lot_no' => $lot_no,
					'old_sale_no' => $old_sale_no,
					'old_season2' => $old_season2,
					'ol_ac' => $ol_ac,
					'old_lot_no' => $old_lot_no,
					'ol_os_sc' => $ol_os_sc,
					'w_house_id' => $w_house_id,
					'garden_id' => $garden_id,
					'invoice_group_txt' => $invoice_group_txt,
					'invoice_group' => $invoice_group,
					'invoice_group_upto' => $invoice_group_upto,
					'w_inv_no' => $w_inv_no,
					'grade_id' => $grade_id,
					'additional_grade' => $additional_grade,
					'chest_number' => $chest_number,
					'gross_wt_per_bag' => $gross_wt_per_bag,
					'net_wt_per_bag' => $net_wt_per_bag,
					'gross_wt_kgs' => $gross_wt_kgs,
					'total_kgs' => $total_kgs,
					'sample_kgs' => $sample_kgs,
					'net_sample_kgs' => $net_sample_kgs,
					'additional_sample_kgs' => $additional_sample_kgs,
					'weight_short' => $weight_short,
					'bulk_lot' => $bulk_lot,
					'net_kgs' => $net_kgs,
					'datetime' => $datetime,
					'user' => $user,
					'last_update' => $last_update,
					'group_no' => $group_no,
					'remarks' => $remarks,
					'pre_invoice_id' => $pre_invoice_id,
					'org_id' => $org_id,
					'reprinted_invoice' => $reprinted_invoice,
					'e_auc' => $e_auc,
					'lot_part' => $lot_part,
					'account_concerned' => $account_concerned,
					'short_note' => $short_note,
					'lot_type' => $lot_type
				);
				$result = $this->db->insert('invoice_entry',$data);
				
				//update catalogue entry
				$this->db->set('catalogue_entry', 'yes');
				$this->db->where('system_id', $pre_invoice_id);
				$result = $this->db->update('pre_invoice_entry');

				return 1;
			}

		}else{

			// Duplicate Lot No
			$this->db->where('id !=',$setup_id);
			$this->db->where('season',$season);
			$this->db->where('auction_center',$auction_center);
			$this->db->where('catalouge_name',$catalouge_name);
			$this->db->where('sale_no',	$sale_no);
			$this->db->where('lot_no',$lot_no);			
			$this->db->where('soft_delete', 1);
			$lot_no_d_data = $this->db->get('invoice_entry');
			$number_of_lot = ($bulk_lot=='y') ? '0' : $lot_no_d_data->num_rows();
			
			// Duplicate Invoice No
			$this->db->where('id !=', $setup_id);
			$this->db->where('season',	$season);
			$this->db->where('auction_center',$auction_center);
			$this->db->where('catalouge_name',$catalouge_name);
			$this->db->where('sale_no',$sale_no);
			$this->db->where('garden_id',$garden_id);
			$this->db->where('w_inv_no',$w_inv_no);
			$this->db->where('grade_id',$grade_id);	
			$this->db->where('soft_delete', 1);
			$w_inv_d_data = $this->db->get('invoice_entry');
			$number_of_invoice = ($bulk_lot=='y' || $lot_part=='Yes') ? '0' : $w_inv_d_data->num_rows();

			if($number_of_lot > 0) {
				return 3;
			}else if($number_of_invoice > 0){
				return 4;
			}else{

				$this->db->set('season', $season);
				$this->db->set('auction_center', $auction_center);
				$this->db->set('catalouge_name', $catalouge_name);
				$this->db->set('supplement_tea', $supplement_tea);
				$this->db->set('chest_name', $chest_name);
				$this->db->set('old_season', $old_season);
				$this->db->set('sale_no', $sale_no);
				$this->db->set('sale_date', $sale_date);
				$this->db->set('invoice', $invoice);
				$this->db->set('category', $category);
				$this->db->set('lot_no', $lot_no);
				$this->db->set('old_sale_no', $old_sale_no);
				$this->db->set('old_season2', $old_season2);
				$this->db->set('ol_ac', $ol_ac);
				$this->db->set('old_lot_no', $old_lot_no);
				$this->db->set('ol_os_sc', $ol_os_sc);
				$this->db->set('w_house_id', $w_house_id);
				$this->db->set('garden_id', $garden_id);
				$this->db->set('invoice_group_txt', $invoice_group_txt);
				$this->db->set('invoice_group', $invoice_group);
				$this->db->set('invoice_group_upto', $invoice_group_upto);
				$this->db->set('w_inv_no', $w_inv_no);
				$this->db->set('grade_id', $grade_id);
				$this->db->set('additional_grade', $additional_grade);
				$this->db->set('chest_number', $chest_number);
				$this->db->set('gross_wt_per_bag', $gross_wt_per_bag);
				$this->db->set('net_wt_per_bag', $net_wt_per_bag);
				$this->db->set('gross_wt_kgs', $gross_wt_kgs);
				$this->db->set('total_kgs', $total_kgs);
				$this->db->set('sample_kgs', $sample_kgs);
				$this->db->set('net_sample_kgs', $net_sample_kgs);
				$this->db->set('additional_sample_kgs', $additional_sample_kgs);
				$this->db->set('weight_short', $weight_short);
				$this->db->set('bulk_lot', $bulk_lot);
				$this->db->set('net_kgs', $net_kgs);
				$this->db->set('last_update', $last_update);
				$this->db->set('group_no', $group_no);
				$this->db->set('remarks', $remarks);
				$this->db->set('pre_invoice_id', $pre_invoice_id);
				$this->db->set('org_id', $org_id);
				$this->db->set('reprinted_invoice', $reprinted_invoice);
				$this->db->set('e_auc', $e_auc);
				$this->db->set('lot_part', $lot_part);
				$this->db->set('account_concerned', $account_concerned);
				$this->db->set('short_note', $short_note);
				$this->db->set('lot_type', $lot_type);
				$this->db->where('id', $setup_id);
				$result = $this->db->update('invoice_entry');
				return 2;
			}
		}
	}


	//group update ids
	function update_group_invoice_info_table($ids, $garden_id, $w_house_id, $invoice_group_txt, $invoice_group, $invoice_group_upto, $old_season) {
		
		// Prepare update data - only include fields that have values
		$update_data = array();
		
		if (!empty($garden_id)) $update_data['garden_id'] = $garden_id;
		if (!empty($w_house_id)) $update_data['w_house_id'] = $w_house_id;
		if (!empty($invoice_group_txt)) $update_data['invoice_group_txt'] = $invoice_group_txt;
		if (!empty($invoice_group)) $update_data['invoice_group'] = $invoice_group;
		if (!empty($invoice_group_upto)) $update_data['invoice_group_upto'] = $invoice_group_upto;
		if (!empty($old_season)) $update_data['old_season'] = $old_season;
		
		// If no fields to update, return error
		if (empty($update_data)) {
			return array('status' => 'error', 'message' => 'No changes specified.');
		}
		
		// Add last update info
		$update_data['last_update'] = $this->session->userdata('ubl_user_name') . ', Date: ' . date('Y-m-d') . ', IP: ' . $_SERVER['REMOTE_ADDR'];
		
		// Update the records
		$this->db->where_in('id', $ids);
		$result = $this->db->update('invoice_entry', $update_data);
		
		if ($result) {
			return array('status' => 'success', 'message' => 'Successfully updated ' . count($ids) . ' records.');
		} else {
			return array('status' => 'error', 'message' => 'Failed to update records.');
		}
	}

	// Pre INVOICE ENTRY
	function save_pre_invoice_entry_info_table(){
		
		// html data
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$edit_system_id = trim($this->input->post('edit_system_id', TRUE)); // true xxs_clean
		$challan_received_date = trim($this->input->post('challan_received_date', TRUE));
		$invoice_group_txt = trim($this->input->post('invoice_group_txt', TRUE));
		$invoice_group = trim($this->input->post('invoice_group', TRUE));
		$invoice_group_upto = trim($this->input->post('invoice_group_upto', TRUE));
		$w_house_id = trim($this->input->post('w_house_id', TRUE));
		$chest_name = trim($this->input->post('chest_name', TRUE));
		$garden_id = trim($this->input->post('garden_id', TRUE));
		$w_inv_no = trim($this->input->post('w_inv_no', TRUE));
		$grade_id = trim($this->input->post('grade_id', TRUE));
		$additional_grade = trim($this->input->post('additional_grade', TRUE));
		$gross_kg = trim($this->input->post('gross_kg', TRUE));
		$tare_kg = trim($this->input->post('tare_kg', TRUE));
		$net_kg = trim($this->input->post('net_kg', TRUE));
		$no_of_bag = trim($this->input->post('no_of_bag', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// check system_id is edited
		if(!empty($edit_system_id)){
			$system_id = $edit_system_id;
		}else{
			// system_id auto incement
			$today_date = date('d-m-Y');
			$this->db->select('system_id'); 
			$this->db->from('pre_invoice_entry');
			$this->db->where("DATE_FORMAT(datetime,'%d-%m-%Y')", $today_date);
			$this->db->order_by('system_id DESC');
			$this->db->limit(1);
			$query = $this->db->get();
			$result = $query->row_array();
			$last = $result['system_id']; 
			$last++;
			$system_id = !empty($result['system_id']) ? $last : date('dmY').'0'.$last;
		}

		// insert data
		if(empty($setup_id)){

			$data = array(
				'system_id' => $system_id,
				'challan_received_date' => $challan_received_date,
				'invoice_group_txt' => $invoice_group_txt,
				'invoice_group' => $invoice_group,
				'invoice_group_upto' => $invoice_group_upto,
				'w_house_id' => $w_house_id,
				'chest_name' => $chest_name,
				'garden_id' => $garden_id,
				'additional_grade' => $additional_grade,
				'w_inv_no' => $w_inv_no,
				'grade_id' => $grade_id,
				'gross_kg' => $gross_kg,
				'tare_kg' => $tare_kg,
				'net_kg' => $net_kg,
				'no_of_bag' => $no_of_bag,
				'datetime' => $datetime,
				'user' => $user,
				'last_update' => $last_update,
			);
			$result = $this->db->insert('pre_invoice_entry',$data);
			return 1;

		}else{

			$this->db->set('system_id', $system_id);
			$this->db->set('challan_received_date', $challan_received_date);
			$this->db->set('invoice_group_txt', $invoice_group_txt);
			$this->db->set('invoice_group', $invoice_group);
			$this->db->set('invoice_group_upto', $invoice_group_upto);
			$this->db->set('w_house_id', $w_house_id);
			$this->db->set('chest_name', $chest_name);
			$this->db->set('garden_id', $garden_id);
			$this->db->set('w_inv_no', $w_inv_no);
			$this->db->set('grade_id', $grade_id);
			$this->db->set('additional_grade', $additional_grade);
			$this->db->set('gross_kg', $gross_kg);
			$this->db->set('tare_kg', $tare_kg);
			$this->db->set('net_kg', $net_kg);
			$this->db->set('no_of_bag', $no_of_bag);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', $setup_id);
			$result = $this->db->update('pre_invoice_entry');
			return 2;

		}
	}

	// Market average entry
	function market_average_info_table(){
		
		// html data
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$season_type = trim($this->input->post('season_type', TRUE));
		$season = trim($this->input->post('season', TRUE));
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$broker_id = trim($this->input->post('broker_id', TRUE));
		$tea_category1 = trim($this->input->post('tea_category1', TRUE));
		$package1 = trim($this->input->post('package1', TRUE));
		$kgs1 = trim($this->input->post('kgs1', TRUE));
		$value_tk1 = trim($this->input->post('value_tk1', TRUE));
		$avg_tk1 = trim($this->input->post('avg_tk1', TRUE));
		$tea_category2= trim($this->input->post('tea_category2', TRUE));
		$package2 = trim($this->input->post('package2', TRUE));
		$kgs2 = trim($this->input->post('kgs2', TRUE));
		$value_tk2 = trim($this->input->post('value_tk2', TRUE));
		$avg_tk2 = trim($this->input->post('avg_tk2', TRUE));
		$tea_category3 = trim($this->input->post('tea_category3', TRUE));
		$package3 = trim($this->input->post('package3', TRUE));
		$kgs3 = trim($this->input->post('kgs3', TRUE));
		$value_tk3 = trim($this->input->post('value_tk3', TRUE));
		$avg_tk3 = trim($this->input->post('avg_tk3', TRUE));
		$tea_category4 = trim($this->input->post('tea_category4', TRUE));
		$package4 = trim($this->input->post('package4', TRUE));
		$kgs4 = trim($this->input->post('kgs4', TRUE));
		$value_tk4 = trim($this->input->post('value_tk4', TRUE));
		$avg_tk4 = trim($this->input->post('avg_tk4', TRUE));
		$tea_category5 = trim($this->input->post('tea_category5', TRUE));
		$package5 = trim($this->input->post('package5', TRUE));
		$kgs5 = trim($this->input->post('kgs5', TRUE));
		$value_tk5 = trim($this->input->post('value_tk5', TRUE));
		$avg_tk5 = trim($this->input->post('avg_tk5', TRUE));
		$season2 = trim($this->input->post('season2', TRUE));
		$user = trim($this->input->post('user', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// Duplicate season
			$this->db->where('season_type',$season_type);
			$this->db->where('season',$season);
			$this->db->where('auction_center',$auction_center);
			$this->db->where('sale_no',$sale_no);
			$this->db->where('broker_id',$broker_id);			
			$this->db->where('season2',$season2);			
			$this->db->where('soft_delete', 1);
			$season_data = $this->db->get('market_average');
			$number_of_row = $season_data->num_rows();

			if($number_of_row > 0) {
				return 3;
			}else{

				$data = array(
					'season_type' => $season_type,
					'season' => $season,
					'auction_center' => $auction_center,
					'sale_no' => $sale_no,
					'broker_id' => $broker_id,
					'tea_category1' => $tea_category1,
					'package1' => $package1,
					'kgs1' => $kgs1,
					'value_tk1' => $value_tk1,
					'avg_tk1' => $avg_tk1,
					'tea_category2' => $tea_category2,
					'package2' => $package2,
					'kgs2' => $kgs2,
					'value_tk2' => $value_tk2,
					'avg_tk2' => $avg_tk2,
					'tea_category3' => $tea_category3,
					'package3' => $package3,
					'kgs3' => $kgs3,
					'value_tk3' => $value_tk3,
					'avg_tk3' => $avg_tk3,
					'tea_category4' => $tea_category4,
					'package4' => $package4,
					'kgs4' => $kgs4,
					'value_tk4' => $value_tk4,
					'avg_tk4' => $avg_tk4,
					'tea_category5' => $tea_category5,
					'package5' => $package5,
					'kgs5' => $kgs5,
					'value_tk5' => $value_tk5,
					'avg_tk5' => $avg_tk5,
					'season2' => $season2,
					'datetime' => $datetime,
					'user' => $user,
					'last_update' => $last_update
				);
				$result = $this->db->insert('market_average',$data);

				return 1;
			}

		}else{

			/// Duplicate season
			$this->db->where('id !=',$setup_id);
			$this->db->where('season_type',$season_type);
			$this->db->where('season',$season);
			$this->db->where('auction_center',$auction_center);
			$this->db->where('sale_no',$sale_no);
			$this->db->where('broker_id',$broker_id);			
			$this->db->where('season2',$season2);			
			$this->db->where('soft_delete', 1);
			$season_data = $this->db->get('market_average');
			$number_of_row = $season_data->num_rows();
		
			if($number_of_row > 0) {
				return 3;
			}else{

				$this->db->set('season_type', $season_type);
				$this->db->set('season', $season);
				$this->db->set('auction_center', $auction_center);
				$this->db->set('sale_no', $sale_no);
				$this->db->set('broker_id', $broker_id);
				$this->db->set('tea_category1', $tea_category1);
				$this->db->set('package1', $package1);
				$this->db->set('kgs1', $kgs1);
				$this->db->set('value_tk1', $value_tk1);
				$this->db->set('avg_tk1', $avg_tk1);
				$this->db->set('tea_category2', $tea_category2);
				$this->db->set('package2', $package2);
				$this->db->set('kgs2', $kgs2);
				$this->db->set('value_tk2', $value_tk2);
				$this->db->set('avg_tk2', $avg_tk2);
				$this->db->set('tea_category3', $tea_category3);
				$this->db->set('package3', $package3);
				$this->db->set('kgs3', $kgs3);
				$this->db->set('value_tk3', $value_tk3);
				$this->db->set('avg_tk3', $avg_tk3);
				$this->db->set('tea_category4', $tea_category4);
				$this->db->set('package4', $package4);
				$this->db->set('kgs4', $kgs4);
				$this->db->set('value_tk4', $value_tk4);
				$this->db->set('avg_tk4', $avg_tk4);
				$this->db->set('tea_category5', $tea_category5);
				$this->db->set('package5', $package5);
				$this->db->set('kgs5', $kgs5);
				$this->db->set('value_tk5', $value_tk5);
				$this->db->set('avg_tk5', $avg_tk5);
				$this->db->set('season2', $season2);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);
				$result = $this->db->update('market_average');
				return 2;
			}
		}
	}

	// Buyer purchase entry
	function buyers_purchases_info_table(){
		
		// html data
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$season = trim($this->input->post('season', TRUE));
		$auction_center = trim($this->input->post('auction_center', TRUE));
		$sale_no = trim($this->input->post('sale_no', TRUE));
		$buying_type1 = trim($this->input->post('buying_type1', TRUE));
		$package1 = trim($this->input->post('package1', TRUE));
		$kgs1 = trim($this->input->post('kgs1', TRUE));
		$percentage1 = trim($this->input->post('percentage1', TRUE));
		$avg_tk1 = trim($this->input->post('avg_tk1', TRUE));
		$buying_type2= trim($this->input->post('buying_type2', TRUE));
		$package2 = trim($this->input->post('package2', TRUE));
		$kgs2 = trim($this->input->post('kgs2', TRUE));
		$percentage2 = trim($this->input->post('percentage2', TRUE));
		$avg_tk2 = trim($this->input->post('avg_tk2', TRUE));
		$datetime = date("Y-m-d").'T'.date("H:i");
		$last_update = trim($this->input->post('last_update', TRUE));

		// insert data
		if(empty($setup_id)){

			// Duplicate season
			$this->db->where('season',$season);
			$this->db->where('auction_center',$auction_center);
			$this->db->where('sale_no',$sale_no);
			$this->db->where('soft_delete', 1);
			$season_data = $this->db->get('buyers_purchases');
			$number_of_row = $season_data->num_rows();

			if($number_of_row > 0) {
				return 3;
			}else{

				$data = array(
					'season' => $season,
					'auction_center' => $auction_center,
					'sale_no' => $sale_no,
					'buying_type1' => $buying_type1,
					'package1' => $package1,
					'kgs1' => $kgs1,
					'percentage1' => $percentage1,
					'avg_tk1' => $avg_tk1,
					'buying_type2' => $buying_type2,
					'package2' => $package2,
					'kgs2' => $kgs2,
					'percentage2' => $percentage2,
					'avg_tk2' => $avg_tk2,
					'datetime' => $datetime,
					'user' => $user,
					'last_update' => $last_update
				);
				$result = $this->db->insert('buyers_purchases',$data);

				return 1;
			}

		}else{

			/// Duplicate season
			$this->db->where('id !=',$setup_id);
			$this->db->where('season',$season);
			$this->db->where('auction_center',$auction_center);
			$this->db->where('sale_no',$sale_no);			
			$this->db->where('soft_delete', 1);
			$season_data = $this->db->get('buyers_purchases');
			$number_of_row = $season_data->num_rows();
		
			if($number_of_row > 0) {
				return 3;
			}else{

				$this->db->set('season', $season);
				$this->db->set('auction_center', $auction_center);
				$this->db->set('sale_no', $sale_no);
				$this->db->set('buying_type1', $buying_type1);
				$this->db->set('package1', $package1);
				$this->db->set('kgs1', $kgs1);
				$this->db->set('percentage1', $percentage1);
				$this->db->set('avg_tk1', $avg_tk1);
				$this->db->set('buying_type2', $buying_type2);
				$this->db->set('package2', $package2);
				$this->db->set('kgs2', $kgs2);
				$this->db->set('percentage2', $percentage2);
				$this->db->set('avg_tk2', $avg_tk2);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $setup_id);
				$result = $this->db->update('buyers_purchases');
				return 2;
			}
		}
	}

	// pre invoice entry final
	function pre_invoice_entry_final_table(){
		
		// table  id
		$cart_lot_ids = $this->input->post('cart_lot_ids', TRUE);

		$this->db->set('catalogue_status', 'final');
		$this->db->where_in('id', $cart_lot_ids, FALSE);
		$result = $this->db->update('pre_invoice_entry');
		return 1;
	}
	
	// catalogue header
	function catalogue_header_setup_data(){
		$this->db->select('*'); 
		$this->db->from('cover_page_info_setup');
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!empty($result['cover_page_text'])){
			return $result['cover_page_text'];
		}else{
			return '';
		}	
	}
	
	function get_psize_data_table($pn_arg){
		
		$page_name = $pn_arg['page_name'];
		$this->db->select('*'); 
		$this->db->from('page_size_info_setup');
		$this->db->where('page_name', $page_name);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!empty($result['page_size'])){
			return $result['page_size'];
		}else{
			return '';
		}
	}
	
	// season name dropdown
	function season_data_table(){

        $this->db->select("*"); 
		$this->db->from('season_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('datetime DESC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	// current season name
	function current_season_data(){

        $this->db->select("*"); 
		$this->db->from('current_season_setup');
		$this->db->where('id', 1);
		$query = $this->db->get();
		$result = $query->row();
        return $result;
	}
	
	// company dropdown
	function company_data_table(){

        $this->db->select("*"); 
		$this->db->from('company_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// garden origin dropdown
	function origin_data_table(){

        $this->db->select("*"); 
		$this->db->from('garden_origin_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// cataloge name dropdown
	function catalogue_data_table(){

        $this->db->select("*"); 
		$this->db->from('catalogue_setup');
		$this->db->where('soft_delete', 1);
		$this->db->group_by("catalouge_name");
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// suppliment catalogue dropdown
	function s_catalogue_data_table(){

        $this->db->select("*"); 
		$this->db->from('catalogue_setup');
		$this->db->where('catalouge_name', 'Supplement');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// chest name dropdown
	function chest_data_table(){

        $this->db->select("*"); 
		$this->db->from('chest_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report
	function wh_data_table(){

        $this->db->select("*"); 
		$this->db->from('warehouse_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	// report
	function inv_wh_data_table($garden_id, $season, $auction_center, $sale_no) {
    $this->db->select('warehouse_setup.id, warehouse_setup.short_key_word'); 
    $this->db->from('warehouse_setup');
    $this->db->join('invoice_entry', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
    
    // Conditions
    $this->db->where('invoice_entry.garden_id', $garden_id);
    $this->db->where('invoice_entry.season', $season);
    $this->db->where('invoice_entry.auction_center', $auction_center);
    $this->db->where('invoice_entry.sale_no', $sale_no);
    $this->db->where('invoice_entry.soft_delete', 1);
    
    $this->db->group_by('warehouse_setup.id');
    $this->db->order_by('warehouse_setup.warehouse_name', 'ASC');
    
    $query = $this->db->get();
    return $query->result_array();
}

	
	// report
	function get_ubl_gardens(){

        $this->db->select("*"); 
		$this->db->from('garden_and_company_setup');
		$this->db->where('soft_delete', 1);
		$this->db->where('seller_brokers', 'UBL');
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function garden_data_table(){

        $this->db->select("*"); 
		$this->db->from('garden_and_company_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report
	function brokers_data_table(){

        $this->db->select("*"); 
		$this->db->from('brokers_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report
	function grade_data_table(){

        $this->db->select("*"); 
		$this->db->from('tea_grade_setup');
		$this->db->where('soft_delete', 1);
		$this->db->order_by('id ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report
	function get_sample_kgs_data_table($sdata_arg){
		// vd
		$CatalogName = $sdata_arg['CatalogName'];

		$this->db->select('*'); 
		$this->db->from('sample_draw_process');
		$this->db->where('sample_name', $CatalogName);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!empty($result['sample_kgs'])){
			return number_format($result['sample_kgs'],3,'.','');
		}else{
			return '';
		}
	}
	
	// get sample kgs data pre invoice parking
	function get_sample_kgs_data_table_pre_invoice($sdata_arg){
		// vd
		$catalouge_name = $sdata_arg['catalouge_name'];
		$pre_invoice_id = $sdata_arg['pre_invoice_id'];

		$this->db->select('*'); 
		$this->db->from('sample_draw_process');
		$this->db->where('sample_name', $catalouge_name);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $pre_invoice_id.'@'.$result['sample_kgs'];
	}

	// get - E-auction sample data
	function get_sample_kgs_data_eauction_table($sdata_arg){

		// html data
		$e_auction_cat = $sdata_arg['e_auction_cat'];

		// query
		$this->db->select('*'); 
		$this->db->from('sample_draw_process');
		$this->db->where('sample_name', $e_auction_cat);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!empty($result['sample_kgs'])){
			return number_format($result['sample_kgs'],3,'.','');
		}else{
			return '';
		}
	}
	
	// get - E-auction sample data pre invoice parking
	function get_sample_kgs_data_eauction_table2($sdata_arg){

		// html data
		$supplement_tea = $sdata_arg['supplement_tea'];
		$pre_invoice_id = $sdata_arg['pre_invoice_id'];

		// query
		$this->db->select('*'); 
		$this->db->from('sample_draw_process');
		$this->db->where('sample_name', $supplement_tea);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $pre_invoice_id.'@'.$result['sample_kgs'];
	}
	
	// report
	function get_save_invoice_verify_table($sverify_data_arg){
		// vd
		$row_id = $sverify_data_arg['row_id'];
		$season = $sverify_data_arg['season'];
		$auction_center = $sverify_data_arg['auction_center'];
		$sale_no = $sverify_data_arg['sale_no'];
		$lot_no = $sverify_data_arg['lot_no'];
		$group_no = $sverify_data_arg['group_no'];
		$catalouge_name = $sverify_data_arg['catalouge_name'];
		$supplement_tea = $sverify_data_arg['supplement_tea'];
		$e_auc = $sverify_data_arg['e_auc'];
		$category = $sverify_data_arg['category'];
		$total_kgs = $sverify_data_arg['total_kgs'];
		$sample_kgs = $sverify_data_arg['sample_kgs'];
		$net_kgs = $sverify_data_arg['net_kgs'];
		$sale_date = $sverify_data_arg['sale_date'];
		$old_season = $sverify_data_arg['old_season'];
		$invoice_group_txt = $sverify_data_arg['invoice_group_txt'];
		$invoice_group = $sverify_data_arg['invoice_group'];
		$invoice_group_upto = $sverify_data_arg['invoice_group_upto'];

		$this->db->set('season', $season);
		$this->db->set('auction_center', $auction_center);
		$this->db->set('sale_no', $sale_no);
		$this->db->set('lot_no', $lot_no);
		$this->db->set('group_no', $group_no);
		$this->db->set('catalouge_name', $catalouge_name);
		$this->db->set('supplement_tea', $supplement_tea);
		$this->db->set('e_auc', $e_auc);
		$this->db->set('category', $category);
		$this->db->set('total_kgs', $total_kgs);
		$this->db->set('sample_kgs', $sample_kgs);
		$this->db->set('net_kgs', $net_kgs);
		$this->db->set('sale_date', $sale_date);
		$this->db->set('old_season', $old_season);
		$this->db->set('invoice_group_txt', $invoice_group_txt);
		$this->db->set('invoice_group', $invoice_group);
		$this->db->set('invoice_group_upto', $invoice_group_upto);
		$this->db->where('id', $row_id);
		$result = $this->db->update('pre_invoice_entry');
		return 1;
	}
	
	// save invoice entry and update pre invoice entry
	function get_save_pre_invoice_verify_table($sverify_data_arg){
		// vd
		$row_id = $sverify_data_arg['row_id'];
		$season = $sverify_data_arg['season'];
		$auction_center = $sverify_data_arg['auction_center'];
		$sale_no = $sverify_data_arg['sale_no'];
		$lot_no = $sverify_data_arg['lot_no'];
		$group_no = $sverify_data_arg['group_no'];
		$catalouge_name = $sverify_data_arg['catalouge_name'];
		$supplement_tea = $sverify_data_arg['supplement_tea'];
		$e_auc = $sverify_data_arg['e_auc'];
		$category = $sverify_data_arg['category'];
		$total_kgs = $sverify_data_arg['total_kgs'];
		$sample_kgs = $sverify_data_arg['sample_kgs'];
		$net_kgs = $sverify_data_arg['net_kgs'];
		$sale_date = $sverify_data_arg['sale_date'];
		$old_season = $sverify_data_arg['old_season'];
		$user = $sverify_data_arg['user'];
		$last_update = $sverify_data_arg['last_update'];
		$garden_id = $sverify_data_arg['garden_id'];
		$invoice_group_txt = $sverify_data_arg['invoice_group_txt'];
		$invoice_group = $sverify_data_arg['invoice_group'];
		$invoice_group_upto = $sverify_data_arg['invoice_group_upto'];
		$w_inv_no = $sverify_data_arg['w_inv_no'];
		$grade_id = $sverify_data_arg['grade_id'];
		$additional_grade = $sverify_data_arg['additional_grade'];
		$w_house_id = $sverify_data_arg['w_house_id'];
		$chest_number = $sverify_data_arg['chest_number'];
		$gross_wt_per_bag = $sverify_data_arg['gross_wt_per_bag'];
		$net_wt_per_bag = $sverify_data_arg['net_wt_per_bag'];
		$chest_name = $sverify_data_arg['chest_name'];
		$old_season2 = $sverify_data_arg['old_season2'];
		$ol_ac = $sverify_data_arg['ol_ac'];
		$old_sale_no = $sverify_data_arg['old_sale_no'];
		$old_lot_no = $sverify_data_arg['old_lot_no'];
		$account_concerned = $sverify_data_arg['account_concerned'];
		$datetime = date("Y-m-d").'T'.date("H:i");

		// Duplicate Lot No
		$this->db->where('season',$season);
		$this->db->where('auction_center',$auction_center);
		$this->db->where('catalouge_name',$catalouge_name);
		$this->db->where('sale_no',$sale_no);
		$this->db->where('lot_no',$lot_no);			
		$this->db->where('soft_delete', 1);
		$lot_no_d_data = $this->db->get('invoice_entry');
		$number_of_lot = $lot_no_d_data->num_rows();
		
		// Duplicate Invoice No
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('catalouge_name', $catalouge_name);
		$this->db->where('sale_no',	$sale_no);
		$this->db->where('garden_id', $garden_id);
		$this->db->where('w_inv_no', $w_inv_no);
		$this->db->where('grade_id', $grade_id);	
		$this->db->where('soft_delete', 1);
		$w_inv_d_data = $this->db->get('invoice_entry');
		$number_of_invoice = $w_inv_d_data->num_rows();

		if($number_of_lot > 0) {
			return 2;
		}else if($number_of_invoice > 0){
			return 3;
		}else{

			$data = array(
				'season' => $season,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'lot_no' => $lot_no,
				'group_no' => $group_no,
				'catalouge_name' => $catalouge_name,
				'supplement_tea' => $supplement_tea,
				'e_auc' => $e_auc,
				'category' => $category,
				'total_kgs' => $total_kgs,
				'sample_kgs' => $sample_kgs,
				'net_kgs' => $net_kgs,
				'sale_date' => $sale_date,
				'old_season' => $old_season,
				'datetime' => $datetime,
				'user' => $user,
				'last_update' => $last_update,
				'garden_id' => $garden_id,
				'invoice_group_txt' => $invoice_group_txt,
				'invoice_group' => $invoice_group,
				'invoice_group_upto' => $invoice_group_upto,
				'w_inv_no' => $w_inv_no,
				'grade_id' => $grade_id,
				'additional_grade' => $additional_grade,
				'w_house_id' => $w_house_id,
				'chest_number' => $chest_number,
				'gross_wt_per_bag' => $gross_wt_per_bag,
				'net_wt_per_bag' => $net_wt_per_bag,
				'chest_name' => $chest_name,
				'old_season2' => $old_season2,
				'ol_ac' => $ol_ac,
				'old_sale_no' => $old_sale_no,
				'old_lot_no' => $old_lot_no,
				'pre_invoice_id' => $row_id,
				'account_concerned' => $account_concerned
			);
			$result = $this->db->insert('invoice_entry',$data);
			
			//update catalogue entry
			$this->db->set('catalogue_entry', 'yes');
			$this->db->where('id', $row_id);
			$result = $this->db->update('pre_invoice_entry');

			return 1;
		}
	}
	

	// Invoice entry :: get old sale + lot no data
	function get_old_sale_lot_data_table($old_sl_data_arg){
		
		// html value
		$season = $old_sl_data_arg['season'];
		$old_sale_no = $old_sl_data_arg['old_sale_no'];
		$ol_ac = $old_sl_data_arg['ol_ac'];
		$old_lot_no = $old_sl_data_arg['old_lot_no'];
		
		// query - invoice entry
		$this->db->select('*'); 
		$this->db->from('invoice_entry');
		$this->db->where('season', $season);
		$this->db->where('sale_no', $old_sale_no);
		$this->db->where('lot_no', $old_lot_no);
		$this->db->where('auction_center', $ol_ac);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		
		// query - sale entry
		$this->db->select('*'); 
		$this->db->from('sale_entry');
		$this->db->where('invoice_id', $result['id']);
		$query = $this->db->get();
		$result_sale = $query->row_array();
		$sold_status = (!empty($result_sale['id'])) ? "Sale No ".$result['sale_no'].' Lot No '.$result['lot_no'] : "";
		
		// return data
		if(!empty($result['w_house_id']) && !empty($result['garden_id'])){
			
			$old_sl_data['org_id'] = !empty($result['org_id']) ? $result['org_id'] : $result['id'];
			$old_sl_data['reprinted_invoice'] = $result['id'];
			$old_sl_data['w_house_id'] = $result['w_house_id'];
			$old_sl_data['garden_id'] = $result['garden_id'];
			$old_sl_data['w_inv_no'] = $result['w_inv_no'];
			$old_sl_data['grade_id'] = $result['grade_id'];
			$old_sl_data['additional_grade'] = $result['additional_grade'];
			$old_sl_data['chest_number'] = $result['chest_number'];
			$old_sl_data['gross_wt_per_bag'] = $result['gross_wt_per_bag'];
			$old_sl_data['net_wt_per_bag'] = $result['net_wt_per_bag'];
			$old_sl_data['total_kgs'] = $result['net_kgs'];
			$old_sl_data['sold_status'] = $sold_status;
			$old_sl_data['old_season'] = $result['old_season'];
			$old_sl_data['invoice_group_txt'] = $result['invoice_group_txt'];
			$old_sl_data['invoice_group'] = $result['invoice_group'];
			$old_sl_data['invoice_group_upto'] = $result['invoice_group_upto'];
			return $old_sl_data;
			
		}else{
			return '';
		}
	}
	
	// lot details
	function get_lot_info_data_table($lot_data_arg){
		
		// get value
		$lot_id = $lot_data_arg['lot_id'];
		
		// query
		$this->db->select('*'); 
		$this->db->from('invoice_entry');
		$this->db->where('id', $lot_id);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		
		// return data
		if(!empty($result['id'])){
			
			$lot_data['id'] = $result['id'];
			$lot_data['catalouge_name'] = $result['catalouge_name'];
			$lot_data['supplement_tea'] = $result['supplement_tea'];
			$lot_data['chest_name'] = $result['chest_name'];
			$lot_data['old_season'] = $result['old_season'];
			$lot_data['invoice'] = $result['invoice'];
			$lot_data['group_no'] = $result['group_no'];
			$lot_data['old_sale_no'] = $result['old_sale_no'];
			$lot_data['old_lot_no'] = $result['old_lot_no'];
			$lot_data['ol_os_sc'] = $result['ol_os_sc'];
			$lot_data['w_house_id'] = $result['w_house_id'];
			$lot_data['garden_id'] = $result['garden_id'];
			$lot_data['w_inv_no'] = $result['w_inv_no'];
			$lot_data['grade_id'] = $result['grade_id'];
			$lot_data['additional_grade'] = $result['additional_grade'];
			$lot_data['chest_number'] = $result['chest_number'];
			$lot_data['gross_wt_per_bag'] = $result['gross_wt_per_bag'];
			$lot_data['net_wt_per_bag'] = $result['net_wt_per_bag'];
			$lot_data['total_kgs'] = $result['total_kgs'];
			$lot_data['sample_kgs'] = $result['sample_kgs'];
			$lot_data['additional_sample_kgs'] = $result['additional_sample_kgs'];
			$lot_data['weight_short'] = $result['weight_short'];
			$lot_data['bulk_lot'] = $result['bulk_lot'];
			$lot_data['net_kgs'] = $result['net_kgs'];
			$lot_data['remarks'] = $result['remarks'];
			return $lot_data;
		}else{
			return '404';
		}
	}
	
	
	// sale lot info
	function sale_lot_info_table($parameters){
		
		// get value
		$lot_id = $parameters['lot_id'];
		
		// query 1 (current lot)
		$this->db->select("invoice_entry.id, invoice_entry.season, invoice_entry.sale_no, invoice_entry.lot_no, invoice_entry.sale_date, invoice_entry.old_sale_no, invoice_entry.ol_ac, invoice_entry.old_lot_no, invoice_entry.w_inv_no, invoice_entry.gross_wt_per_bag, invoice_entry.net_wt_per_bag, invoice_entry.total_kgs, invoice_entry.net_kgs, invoice_entry.chest_number, invoice_entry.valuation, IFNULL(invoice_entry.net_sample_kgs,0) AS net_sample_kgs, 
		(IFNULL(invoice_entry.sample_kgs,0) + IFNULL(invoice_entry.additional_sample_kgs,0)) AS sample_kgs, 
		IF(invoice_entry.remarks='', 'Out', invoice_entry.remarks) AS remarks, invoice_entry.bulk_lot, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, warehouse_setup.short_key_word AS wh_name, 
		garden_and_company_setup.garden_name, sale_entry.sold_price, IFNULL(sale_entry.complete_price,0) AS complete_price, sale_entry.buyer1, IFNULL(sale_entry.buyer1_bag,0) AS buyer1_bag, sale_entry.buyer_type1, sale_entry.buyer2, IFNULL(sale_entry.buyer2_bag,0) AS buyer2_bag, sale_entry.buyer_type2, sale_entry.buyer3, IFNULL(sale_entry.buyer3_bag,0) AS buyer3_bag, sale_entry.buyer_type3"
		);
		
		$this->db->from('invoice_entry');
		$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
		$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
		$this->db->join("(SELECT invoice_id, sold_price, complete_price, buyer1, buyer1_bag, buyer_type1, buyer2, buyer2_bag, buyer_type2, buyer3, buyer3_bag, buyer_type3 FROM sale_entry GROUP BY invoice_id) sale_entry", "invoice_entry.id = sale_entry.invoice_id", "left");
		
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		$this->db->where('invoice_entry.published IS NOT NULL', NULL,FALSE);
		$this->db->where('invoice_entry.id', $lot_id);
		$this->db->where('invoice_entry.soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		
		// return data
		$lot_data['id'] = $result['id'];
		$lot_data['inv_lot_no'] = $result['lot_no'];
		$lot_data['sale_date'] = $result['sale_date'];
		$lot_data['inv_season'] = $result['season'];
		$lot_data['chest_number'] = $result['chest_number'];
		$lot_data['net_wt_per_bag'] = $result['net_wt_per_bag'];
		$lot_data['sample_kgs'] = $result['net_sample_kgs'];
		$lot_data['net_kgs'] = $result['net_kgs'];
		$lot_data['valuation'] = $result['valuation'];
		$lot_data['sold_price'] = $result['sold_price'];
		$lot_data['complete_price'] = $result['complete_price'];
		$lot_data['buyer1'] = $result['buyer1'];
		$lot_data['buyer1_bag'] = $result['buyer1_bag'];
		$lot_data['buyer_type1'] = $result['buyer_type1'];
		$lot_data['buyer2'] = $result['buyer2'];
		$lot_data['buyer2_bag'] = $result['buyer2_bag'];
		$lot_data['buyer_type2'] = $result['buyer_type2'];
		$lot_data['buyer3'] = $result['buyer3'];
		$lot_data['buyer3_bag'] = $result['buyer3_bag'];
		$lot_data['buyer_type3'] = $result['buyer_type3'];
		$lot_data['sold_status'] = ($result['buyer1_bag']=='0') ? "<font color=red> - $result[remarks]</font>" : "<font color=green> - Sold</font>";
		$lot_data['sss'] = $result['remarks'];
		
		if(!empty($result['id'])){
			$lot_data['lot_details'] = $result['garden_name'] . ', ' . $result['grade_name'] . ', Inv: ' . $result['w_inv_no'] . ', ' . $result['wh_name'] . ', ' . $result['net_wt_per_bag'] . ', ' . $result['total_kgs'] . ', ' . $result['net_kgs'];
		}else{
			$lot_data['lot_details'] = "<font color=\"red\">Lot number not available.</font>";
		}
		return $lot_data;
	}
	
	// bulk sale lot info
	function sale_lot_info_table2($parameters){
		
		// get value
		$lot_id = $parameters['lot_id'];
		
		// query 1 (current lot)
		$this->db->select("invoice_entry.id, invoice_entry.season, invoice_entry.sale_no, invoice_entry.lot_no, invoice_entry.old_sale_no, invoice_entry.ol_ac, invoice_entry.old_lot_no, invoice_entry.w_inv_no, invoice_entry.gross_wt_per_bag, invoice_entry.net_wt_per_bag, invoice_entry.total_kgs, invoice_entry.net_kgs, invoice_entry.chest_number, invoice_entry.valuation, 
		(IFNULL(invoice_entry.sample_kgs,0) + IFNULL(invoice_entry.additional_sample_kgs,0)) AS sample_kgs, IFNULL(invoice_entry.net_sample_kgs,0) AS net_sample_kgs, 
		IF(invoice_entry.remarks='', 'Out', invoice_entry.remarks) AS remarks, invoice_entry.bulk_lot, CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name, warehouse_setup.short_key_word AS wh_name, 
		garden_and_company_setup.garden_name, sale_entry.sold_price, sale_entry.complete_price, sale_entry.buyer1, IFNULL(sale_entry.buyer1_bag,0) AS buyer1_bag, sale_entry.buyer_type1, sale_entry.buyer2, IFNULL(sale_entry.buyer2_bag,0) AS buyer2_bag, sale_entry.buyer_type2, sale_entry.buyer3, IFNULL(sale_entry.buyer3_bag,0) AS buyer3_bag, sale_entry.buyer_type3"
		);
		
		$this->db->from('invoice_entry');
		$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
		$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
		$this->db->join("(SELECT invoice_id, sold_price, complete_price, buyer1, buyer1_bag, buyer_type1, buyer2, buyer2_bag, buyer_type2, buyer3, buyer3_bag, buyer_type3 FROM sale_entry GROUP BY invoice_id) sale_entry", "invoice_entry.id = sale_entry.invoice_id", "left");
		
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		$this->db->where('invoice_entry.published IS NOT NULL', NULL,FALSE);
		$this->db->where('invoice_entry.id', $lot_id);
		$this->db->where('invoice_entry.soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		
		// return data
		$lot_data['id'] = $result['id'];
		$lot_data['chest_number'] = $result['chest_number'];
		$lot_data['net_wt_per_bag'] = $result['net_wt_per_bag'];
		$lot_data['sample_kgs'] = $result['net_sample_kgs'];
		$lot_data['net_kgs'] = $result['net_kgs'];
		$lot_data['sold_price'] = $result['sold_price'];
		$lot_data['complete_price'] = $result['complete_price'];
		$lot_data['buyer1'] = $result['buyer1'];
		$lot_data['buyer1_bag'] = $result['buyer1_bag'];
		$lot_data['buyer_type1'] = $result['buyer_type1'];
		$lot_data['buyer2'] = $result['buyer2'];
		$lot_data['buyer2_bag'] = $result['buyer2_bag'];
		$lot_data['buyer_type2'] = $result['buyer_type2'];
		$lot_data['buyer3'] = $result['buyer3'];
		$lot_data['buyer3_bag'] = $result['buyer3_bag'];
		$lot_data['buyer_type3'] = $result['buyer_type3'];
		$lot_data['sold_status'] = ($result['buyer1_bag']=='0') ? "<font color=red> - $result[remarks]</font>" : "<font color=green> - Sold</font>";
		$lot_data['sss'] = $result['remarks'];

		return $lot_data;

	}
	
	// sale lot parking info
	function sale_lot_parking_table($parameters){
		
		// get value
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_no = $parameters['sale_no'];
		$lot_no = $parameters['lot_no'];
		
		// query
		$this->db->select("id, bulk_lot");
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		$this->db->where('published IS NOT NULL', NULL,FALSE);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('lot_no', $lot_no);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		
		// return data
		$lot_data['lot_id'] = $result['id'];
		$lot_data['bulk_lot'] = $result['bulk_lot'];

		return $lot_data;
	}

	// bulk sale lot parking info
	function sale_lot_parking_table2($parameters){
		
		// get value
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_no = $parameters['sale_no'];
		$lot_no = $parameters['lot_no'];
		$row_id = $parameters['row_id'];
		
		// query
		$this->db->select("id, bulk_lot");
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		$this->db->where('published IS NOT NULL', NULL,FALSE);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('lot_no', $lot_no);
		$this->db->where('id', $row_id);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		
		// return data
		$lot_data['lot_id'] = $result['id'];
		return $lot_data;
	}

	// search pre inv data
	function search_pre_inv_details_table($parameters){
		
		// get value
		$pre_invoice_id = $parameters['pre_invoice_id'];
		
		// query
		$this->db->select("*");
		$this->db->from('pre_invoice_entry');
		$this->db->where('system_id', $pre_invoice_id);
		$this->db->where('catalogue_status', 'final');
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		
		// return data
		$pre_inv_data['pre_invoice_id'] = $result['system_id'];
		$pre_inv_data['season'] = $result['season'];
		$pre_inv_data['auction_center'] = $result['auction_center'];
		$pre_inv_data['sale_no'] = $result['sale_no'];
		$pre_inv_data['lot_no'] = $result['lot_no'];
		$pre_inv_data['catalouge_name'] = $result['catalouge_name'];
		$pre_inv_data['supplement_tea'] = $result['supplement_tea'];
		$pre_inv_data['w_house_id'] = $result['w_house_id'];
		$pre_inv_data['chest_name'] = $result['chest_name'];
		$pre_inv_data['garden_id'] = $result['garden_id'];
		$pre_inv_data['w_inv_no'] = $result['w_inv_no'];
		$pre_inv_data['grade_id'] = $result['grade_id'];
		$pre_inv_data['additional_grade'] = $result['additional_grade'];
		$pre_inv_data['gross_wt_per_bag'] = $result['gross_kg'];
		$pre_inv_data['chest_number'] = $result['no_of_bag'];
		$pre_inv_data['net_wt_per_bag'] = $result['net_kg'];
		return $pre_inv_data;
	}
	
	// report
	function get_sale_date_data_table($sn_arg){
		// vd
		$auction_center = $sn_arg['auction_center'];
		$season_name = $sn_arg['season'];
		$sale_no = $sn_arg['sale_no'];
		// run query
		$this->db->select('*'); 
		$this->db->from('sale_schedule_setup');
		$this->db->where('auction_center', $auction_center);
		$this->db->where('season_name', $season_name);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!empty($result['sale_date'])){
			return $result['sale_date'];
		}else{
			return '';
		}
	}

	// In your ajax_model.php - update get_garden_letter_data_table function
	function get_garden_letter_data_table($gl_arg){
    // vd
    $auction_center = $gl_arg['auction_center'];
    $season_name = $gl_arg['season'];
    $sale_no = $gl_arg['sale_no'];
    
    // run query
    $this->db->select('*'); 
    $this->db->from('garden_letter_setup');
    $this->db->where('auction_center', $auction_center);
    $this->db->where('season', $season_name);
    $this->db->where('sale_no', $sale_no);
    $query = $this->db->get();
    $result = $query->row_array();
    
    if(!empty($result)){
        return array(
            'setup_id' => $result['id'],
            'garden_letter_text' => $result['garden_letter_text'],
            'gl_story_text' => $result['gl_story_text']
        );
    }else{
        return array(
            'setup_id' => '',
            'garden_letter_text' => '',
            'gl_story_text' => ''
        );
    }
}

	// sale date return Invoice Parking
	function get_sale_date_data_table_invoice_parking($sn_arg){
		// vd
		$auction_center = $sn_arg['auction_center'];
		$season_name = $sn_arg['season'];
		$sale_no = $sn_arg['sale_no'];
		$pre_invoice_id = $sn_arg['pre_invoice_id'];
		// run query
		$this->db->select('*'); 
		$this->db->from('sale_schedule_setup');
		$this->db->where('auction_center', $auction_center);
		$this->db->where('season_name', $season_name);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $pre_invoice_id.'@'.$result['sale_date'];
	}
	
	// invoice entry :: cart list
	function get_invoice_group_data_table($invoice_cart_data_arg){
		
		// html data
		$user = $invoice_cart_data_arg['user']; // broker id
		
		// query
		$sql = "
		SELECT 
			invoice_entry.season, invoice_entry.catalouge_name, invoice_entry.supplement_tea, invoice_entry.chest_name, 
			invoice_entry.category, invoice_entry.group_no, invoice_entry.sale_no, invoice_entry.sale_date, 
			invoice_entry.w_house_id, invoice_entry.garden_id, invoice_entry.invoice, invoice_entry.invoice_group_txt, 
			invoice_entry.invoice_group, invoice_entry.invoice_group_upto, invoice_entry.old_season, 
			warehouse_setup.short_key_word AS wh_sk, garden_and_company_setup.garden_name
		FROM invoice_entry
		LEFT JOIN warehouse_setup ON invoice_entry.w_house_id = warehouse_setup.id
		LEFT JOIN garden_and_company_setup ON invoice_entry.garden_id = garden_and_company_setup.id
		WHERE invoice_entry.id = (
			SELECT id FROM (
				SELECT MAX(id) AS id
				FROM invoice_entry
				WHERE catalogue_status = 'prepared' AND soft_delete = 1 AND user = ?
				GROUP BY sale_no, sale_date, w_house_id, garden_id, invoice_group_txt, invoice_group, invoice_group_upto
				ORDER BY MAX(id) DESC
				LIMIT 1
			) AS temp
		)
		";

		$query = $this->db->query($sql, [$user]);
		return $query->row_array();

	}

	// pre invoice entry :: cart list
	function get_pre_invoice_group_data_table($pre_invoice_cart_data_arg){
		
		// html data
		$user = $pre_invoice_cart_data_arg['user']; // broker id
		
		// query
		$this->db->select("pre_invoice_entry.*,garden_and_company_setup.garden_name,tea_grade_setup.grade_name,warehouse_setup.short_key_word AS wh_sk"); 
		$this->db->from('pre_invoice_entry');
		$this->db->join('warehouse_setup','pre_invoice_entry.w_house_id = warehouse_setup.id','left');
		$this->db->join('garden_and_company_setup','pre_invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->join('tea_grade_setup', 'pre_invoice_entry.grade_id = tea_grade_setup.id', 'left');
		$this->db->where('pre_invoice_entry.catalogue_status','prepared');
		$this->db->where('pre_invoice_entry.soft_delete', 1);
		$this->db->order_by('pre_invoice_entry.datetime DESC, pre_invoice_entry.system_id DESC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	// Market average :: cart list
	function market_average_data_table($market_average_arg){
		
		// html data
		$user = $market_average_arg['user']; // broker id
		
		// query
		$this->db->select("market_average.*,brokers_setup.brokers_name, (CASE WHEN market_average.season_type='1' THEN 'Current Season' WHEN market_average.season_type='2' THEN 'Old Season' WHEN market_average.season_type='3' THEN 'New Season' END) AS type"); 
		$this->db->from('market_average');
		$this->db->join('brokers_setup','market_average.broker_id = brokers_setup.id','left');
		$this->db->where('market_average.soft_delete', 1);
		$this->db->where('DATE(market_average.datetime)', date('Y-m-d'));
		$this->db->order_by('market_average.id DESC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	// Buyer purchage :: cart list
	function buyers_purchases_data_table($buyers_purchases_arg){
		
		// html data
		$user = $buyers_purchases_arg['user']; // broker id
		
		// query
		$this->db->select("*"); 
		$this->db->from('buyers_purchases');
		$this->db->where('soft_delete', 1);
		$this->db->where('DATE(datetime)', date('Y-m-d'));
		$this->db->order_by('id DESC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// upcoming catalogue
	function get_upcoming_catalogue(){
		
		// query date
		$date = date("Y-m-d");

		$this->db->select('season_name, sale_no, auction_center, DATEDIFF(sale_date,CURRENT_DATE()) as days_left, date_format(sale_date,"%d-%M-%Y") as auction_date'); 
		$this->db->from('sale_schedule_setup');
		$this->db->where("sale_date >=" . "'" . $date . "'");
		$this->db->where('soft_delete', 1);
		$this->db->order_by('sale_date ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		$uc_result = $query->row_array();

		if(!empty($uc_result['season_name'])){

			if($uc_result['days_left'] > 0){
				return "SALE NO: $uc_result[sale_no] ($uc_result[auction_center]) <br /> $uc_result[auction_date] <br /> $uc_result[season_name]";
			}else{
				return "<font color=green> SALE NO: $uc_result[sale_no] ($uc_result[auction_center]) <br /> Auction is live </font>";
			}
			
		}else{
			return "Sorry ! upcoming catalogue not available.";
		}
	}
	
	// report :: catalogue wise report
	function catalogue_wise_report_list_table($parameters){
		// vd
		$user = $parameters['user']; // broker id
		$season = $parameters['season'];
		$sale_no = $parameters['sale_no'];
		$catalouge_name = $parameters['catalogue_name'];
		// query
		$this->db->select("invoice_entry.season, invoice_entry.catalouge_name, invoice_entry.group_no, invoice_entry.sale_no, invoice_entry.sale_date, invoice_entry.w_house_id, invoice_entry.garden_id, warehouse_setup.short_key_word, warehouse_setup.warehouse_name, garden_and_company_setup.garden_name"); 
		$this->db->from('invoice_entry');
		$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id','left');
		$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		$this->db->where('invoice_entry.user', $user);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
		$this->db->where('invoice_entry.page_no IS NOT NULL',NULL,FALSE); //set to FALSE to not escape your query.
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.group_no, invoice_entry.sale_no, invoice_entry.sale_date, invoice_entry.w_house_id, invoice_entry.garden_id');
		$this->db->order_by('invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report
	function get_group_no_data_table($group_no_arg){
		// vd
		$user 	= $group_no_arg['user']; // broker id
		$season = $group_no_arg['season'];
		$sale_no = $group_no_arg['sale_no'];
		// query
		$this->db->select('MAX(group_no) AS `group_no`');
		$this->db->from('invoice_entry');
		$this->db->where('user', $user);
		$this->db->where('season', $season);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		// return data
		if(!empty($result['group_no'])){
			$group_no = $result['group_no']++;
			return $result['group_no'];
		}else{
			return '1';
		}
	}
	
	// group no data pre invoice parking
	function get_group_no_data_table2($group_no_arg){
		// vd
		$user 	= $group_no_arg['user']; // broker id
		$season = $group_no_arg['season'];
		$sale_no = $group_no_arg['sale_no'];
		$pre_invoice_id = $group_no_arg['pre_invoice_id'];
		// query
		$this->db->select('MAX(group_no) AS `group_no`');
		$this->db->from('invoice_entry');
		$this->db->where('user', $user);
		$this->db->where('season', $season);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		// return data
		if(!empty($result['group_no'])){
			$group_no = $result['group_no']++;
			return $pre_invoice_id.'@'.$result['group_no'];
		}else{
			return $pre_invoice_id.'@'.'1';
		}

		
	}
	
	// report :: catalogue after prepared
	function get_catalogue_prepared_data_table($catp_data_arg){
		
		// html data
		$user = $catp_data_arg['user']; // broker id
		$season = $catp_data_arg['season'];
		$auction_center = $catp_data_arg['auction_center'];
		$catalouge_name = $catp_data_arg['catalouge_name'];
		$sale_no = $catp_data_arg['sale_no'];
		
		// query
		$this->db->select("
			invoice_entry.season, 
			invoice_entry.auction_center, 
			invoice_entry.catalouge_name, 
			invoice_entry.group_no,			
			invoice_entry.sale_no, 
			invoice_entry.sale_date, 
			invoice_entry.w_house_id, 
			invoice_entry.garden_id, 
			invoice_entry.chest_name,
			CONCAT(invoice_entry.old_season,' / ',invoice_entry.remarks) AS remarks,
			GROUP_CONCAT(invoice_entry.w_inv_no) AS `w_inv_no`,
			warehouse_setup.short_key_word, 
			garden_and_company_setup.garden_name
		"); 
		$this->db->from('invoice_entry');
		$this->db->join('warehouse_setup', 'invoice_entry.w_house_id = warehouse_setup.id', 'left');
		$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		$this->db->where('invoice_entry.page_no'); // is null
		//$this->db->where('invoice_entry.user', $user);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.catalouge_name', $catalouge_name);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.group_no, invoice_entry.w_house_id, invoice_entry.garden_id');
		$this->db->order_by('invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	function get_tea_cup_testing_data_table($parameters){
		
		// vd
		$user = $parameters['user']; // broker id
		$season = $parameters['season'];
		$catalouge_name = $parameters['catalouge_name'];
		$sale_no = $parameters['sale_no'];
		$garden_id = $parameters['garden_id'];
		
		// query
		$this->db->select("
		
			invoice_entry.id,
			invoice_entry.season,
			invoice_entry.sale_no,
			invoice_entry.catalouge_name,
			invoice_entry.sale_date,
			invoice_entry.lot_no,
			invoice_entry.old_lot_no,
			invoice_entry.old_sale_no,
			invoice_entry.w_inv_no,
			garden_and_company_setup.garden_name,
			CONCAT(tea_grade_setup.grade_name, invoice_entry.additional_grade) AS grade_name,	  
			invoice_entry.valuation,
			invoice_entry.reserve_price,
			invoice_entry.last_sale,
			invoice_entry.appearance,
			invoice_entry.liquor,
			invoice_entry.sold_wd,
			IF(invoice_entry.old_sale_no > 0, CONCAT('<b> Re Print Sale: ', invoice_entry.old_sale_no, '</b> / <b> Lot ', invoice_entry.old_lot_no, '</b>'), '') AS re_print 
			
		"); 
		$this->db->from('invoice_entry');
		$this->db->join('tea_grade_setup', 'invoice_entry.grade_id = tea_grade_setup.id', 'left');
		$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		$this->db->where('invoice_entry.season', $season);
		$catalouge_name == "All" ? $this->db->where("invoice_entry.catalouge_name !=", '') : $this->db->where('invoice_entry.catalouge_name', $catalouge_name);	
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.garden_id', $garden_id);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->order_by('invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_tea_cup_testing_data_table2($parameters){
		
		// vd
		$user = $parameters['user']; // broker id
		$season = $parameters['season'];
		$catalouge_name = $parameters['catalouge_name'];
		$sale_no = $parameters['sale_no'];
		$garden_id = $parameters['garden_id'];
		
		// query
		$this->db->select("id,season,sale_no,catalouge_name,garden_id,infusion,leaf_comments,liquor_comments"); 
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		$this->db->where('season', $season);	
		$catalouge_name == "All" ? $this->db->where("catalouge_name !=", '') : $this->db->where('catalouge_name', $catalouge_name);	
		$this->db->where('sale_no', $sale_no);
		$this->db->where('garden_id', $garden_id);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('garden_id');
		$query = $this->db->get();
		$result = $query->row_array();
        return $result;
	}

	function save_cup_testing_table(){
		
		// variable define
		$lot_id = $this->input->post('lot_id', TRUE);
		$testing_rating = $this->input->post('testing_rating', TRUE);
		$testing_rating_log = $this->input->post('testing_rating_log', TRUE);
		$testing_status = $this->input->post('testing_status', TRUE);
		$testing_rating_dt = date("Y-m-d").'T'.date("H:i");
		
		// query
		($testing_status == 'appearance') ? $this->db->set('appearance', strip_tags($testing_rating)) : '';
		($testing_status == 'liquor') ? $this->db->set('liquor', strip_tags($testing_rating)) : '';

		$this->db->set('testing_rating_log', $testing_rating_log);
		$this->db->set('testing_rating_dt', $testing_rating_dt);
		$this->db->where('id', $lot_id);
		
		$result = $this->db->update('invoice_entry');
		return 1;
	}
	
	function save_cup_testing_table2(){
		
		// variable define
		$season = $this->input->post('season', TRUE);
		$catalouge_name = $this->input->post('catalouge_name', TRUE);
		$sale_no = $this->input->post('sale_no', TRUE);
		$garden_id = $this->input->post('garden_id', TRUE);
		$testing_rating = $this->input->post('testing_rating', TRUE);
		$testing_rating_log = $this->input->post('testing_rating_log', TRUE);
		$testing_status = $this->input->post('testing_status', TRUE);
		$testing_rating_dt = date("Y-m-d").'T'.date("H:i");
		
		// query
		($testing_status == 'infusion') ? $this->db->set('infusion', strip_tags($testing_rating)) : '';
		($testing_status == 'leaf_comments') ? $this->db->set('leaf_comments', strip_tags($testing_rating)) : '';
		($testing_status == 'liquor_comments') ? $this->db->set('liquor_comments', strip_tags($testing_rating)) : '';
		
		$this->db->set('testing_rating_log', $testing_rating_log);
		$this->db->set('testing_rating_dt', $testing_rating_dt);
		$this->db->where('season', $season);
		$catalouge_name == "All" ? $this->db->where("catalouge_name !=", '') : $this->db->where('catalouge_name', $catalouge_name);	
		$this->db->where('sale_no', $sale_no);
		$this->db->where('garden_id', $garden_id);
		
		$result = $this->db->update('invoice_entry');
		return 1;
	}
	
	function get_catalogue_print_data_table($catp_data_arg){
		
		// html data
		$user = $catp_data_arg['user']; // broker id
		$season = $catp_data_arg['season'];
		$auction_center = $catp_data_arg['auction_center'];
		$catalouge_name = $catp_data_arg['catalouge_name'];
		$sale_no = $catp_data_arg['sale_no'];
		
		// query
		$this->db->select("page_no,user,season,catalouge_name,sale_no,sale_date,MAX(lot_no) AS `last_lot`"); 
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		$this->db->where('page_no IS NOT NULL', NULL, FALSE);
		//$this->db->where('user', $user);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('catalouge_name', $catalouge_name);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('page_no');
		$this->db->order_by('page_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_catalogue_published_data_table($published_data_arg){
		$user = $published_data_arg['brokers'];
		$season = $published_data_arg['season'];
		$auction_center = $published_data_arg['auction_center'];
		$sale_no = $published_data_arg['sale_no'];
		$catalogue = $published_data_arg['catalogue'];
		$this->db->select("page_no, user, season, auction_center, catalouge_name, sale_no, sale_date, MAX(lot_no) AS last_lot"); 
		$this->db->from('invoice_entry');
		$this->db->where('published', 'Yes');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		if($catalogue=='Full Catalogue'){
			// do nothing
		}else{
			$this->db->where('catalouge_name', $catalogue);	
		}
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('page_no, catalouge_name');
		$this->db->order_by('page_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report :: how many page in catalogue
	function get_how_many_page_table($catp_data_arg){
		
		// html data
		$user = $catp_data_arg['user']; // broker id
		$season = $catp_data_arg['season'];
		$auction_center = $catp_data_arg['auction_center'];
		$catalouge_name = $catp_data_arg['catalouge_name'];
		$sale_no = $catp_data_arg['sale_no'];
		
		// query
		$this->db->select("page_no"); 
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		$this->db->where('page_no IS NOT NULL', NULL, FALSE);
		//$this->db->where('user', $user);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		//$this->db->where('catalouge_name', $catalouge_name);
		$this->db->where('sale_no', $sale_no);
		$this->db->where('soft_delete', 1);
		$this->db->order_by('page_no DESC');
		$query = $this->db->get();
		$result = $query->row_array();
		if(!empty($result['page_no'])){
			return $result['page_no'];
		}else{
			return '0';
		}
	}
	
	// report :: valuation after prepared
	function get_valuation_prepared_data_table($catp_data_arg){
		
		// get html value
		$user = $catp_data_arg['user']; // broker id
		$season = $catp_data_arg['season'];
		$auction_center = $catp_data_arg['auction_center'];
		$catalouge_name = $catp_data_arg['catalouge_name'];
		$sale_no = $catp_data_arg['sale_no'];
		
		$this->db->select("
			invoice_entry.id,
			invoice_entry.auction_center,
			invoice_entry.season,
			invoice_entry.lot_no, 
			invoice_entry.catalouge_name, 
			invoice_entry.sale_no, 
			invoice_entry.sale_date,
			invoice_entry.valuation,
			invoice_entry.valuation_publish,
			invoice_entry.net_kgs,
			invoice_entry.user
		"); 
		$this->db->from('invoice_entry');
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		//$this->db->where('invoice_entry.user', $user);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		if($catalouge_name=='Full Catalogue'){
			// do nothing
		}else{
			$this->db->where('invoice_entry.catalouge_name', $catalouge_name);	
		}
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->order_by('invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	
	// report :: catalogue index after prepared
	function get_index_prepared_data_table($catp_data_arg){
		
		// html data
		$user = $catp_data_arg['user']; // broker id
		$season = $catp_data_arg['season'];
		$auction_center = $catp_data_arg['auction_center'];
		$sale_no = $catp_data_arg['sale_no'];
		
		// query
		$this->db->select("
			garden_and_company_setup.garden_name, 
			GROUP_CONCAT(DISTINCT invoice_entry.index_pageNo) AS lot_page_no,
			invoice_entry.catalouge_name,
			invoice_entry.sale_no,
			invoice_entry.season
		"); 
		$this->db->from('invoice_entry');
		$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		$this->db->where('invoice_entry.index_pageNo IS NOT NULL', NULL, FALSE);
		//$this->db->where('invoice_entry.user', $user);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.garden_id, invoice_entry.catalouge_name');
		$this->db->order_by('garden_and_company_setup.garden_name ASC, invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report :: cover page published
	function get_cover_page_published_data_table($published_data_arg){
		$season = $published_data_arg['season'];
		$auction_center = $published_data_arg['auction_center'];
		$sale_no = $published_data_arg['sale_no'];
		$brokers = $published_data_arg['brokers']; // broker id
		$catalogue = $published_data_arg['catalogue'];
		$this->db->select("
			invoice_entry.sale_no,
			invoice_entry.season,
			invoice_entry.auction_center,
			invoice_entry.catalouge_name,
			COUNT(invoice_entry.lot_no) AS number_of_lot,
			sum(invoice_entry.chest_number) AS number_of_bags, 
			sum(invoice_entry.net_kgs) AS net_kgs
		"); 
		$this->db->from('invoice_entry');
		if($catalogue=='Full Catalogue'){
			// do nothing
		}else{
			$this->db->where('invoice_entry.catalouge_name', $catalogue);	
		}
		$this->db->where('invoice_entry.published', 'Yes');
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.catalouge_name');
		$this->db->order_by('invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	function get_index_published_data_table($published_data_arg){
		$season = $published_data_arg['season'];
		$auction_center = $published_data_arg['auction_center'];
		$sale_no = $published_data_arg['sale_no'];
		$brokers = $published_data_arg['brokers'];
		$catalogue = $published_data_arg['catalogue'];
		$this->db->select("
			garden_and_company_setup.garden_name, 
			GROUP_CONCAT(DISTINCT invoice_entry.index_pageNo) AS lot_page_no,
			invoice_entry.catalouge_name,
			invoice_entry.sale_no,
			invoice_entry.season
		"); 
		$this->db->from('invoice_entry');
		$this->db->join('garden_and_company_setup', 'invoice_entry.garden_id = garden_and_company_setup.id', 'left');
		$this->db->where('invoice_entry.published', 'Yes');
		if($catalogue=='Full Catalogue'){
			// do nothing
		}else{
			$this->db->where('invoice_entry.catalouge_name', $catalogue);	
		}
		//$this->db->where('invoice_entry.user', $brokers);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.garden_id, invoice_entry.catalouge_name');
		$this->db->order_by('garden_and_company_setup.garden_name ASC, invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	function get_valuation_published_data_table($published_data_arg){
		$season = $published_data_arg['season'];
		$auction_center = $published_data_arg['auction_center'];
		$sale_no = $published_data_arg['sale_no'];
		$brokers = $published_data_arg['brokers'];
		$catalogue = $published_data_arg['catalogue'];
		$this->db->select("
			invoice_entry.id,
			invoice_entry.season,
			invoice_entry.lot_no, 
			invoice_entry.catalouge_name, 
			invoice_entry.sale_no, 
			invoice_entry.sale_date,
			invoice_entry.valuation,
			invoice_entry.net_kgs,
			invoice_entry.user
		"); 
		$this->db->from('invoice_entry');
		$this->db->where('invoice_entry.published', 'Yes');
		if($catalogue=='Full Catalogue'){
			// do nothing
		}else{
			$this->db->where('invoice_entry.catalouge_name', $catalogue);	
		}
		//$this->db->where('invoice_entry.user', $brokers);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->order_by('invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}

	function get_cover_page_prepared_data_table($catp_data_arg){
		$user = $catp_data_arg['user']; // broker id
		$season = $catp_data_arg['season'];
		$auction_center = $published_data_arg['auction_center'];
		$sale_no = $catp_data_arg['sale_no'];
		$this->db->select("
			invoice_entry.sale_no,
			invoice_entry.season,
			invoice_entry.catalouge_name,
			COUNT(invoice_entry.lot_no) AS number_of_lot,
			sum(invoice_entry.chest_number) AS number_of_bags, 
			sum(invoice_entry.net_kgs) AS net_kgs
		"); 
		$this->db->from('invoice_entry');
		$this->db->where('invoice_entry.catalogue_status', 'prepared');
		//$this->db->where('invoice_entry.user', $user);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.catalouge_name');
		$this->db->order_by('invoice_entry.lot_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	function get_sale_no_cp_data_table($cp_sl_arg){
		
		// html data
		$user = $cp_sl_arg['user']; // broker id
		$season = $cp_sl_arg['season'];
		$auction_center = $cp_sl_arg['auction_center'];
		$catalouge_name = $cp_sl_arg['catalouge_name'];
		
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		//$this->db->where('user', $user);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		($catalouge_name=='All') ? '' : $this->db->where('catalouge_name', $catalouge_name);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\">
		<option value=\"\"> Select </option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function get_universal_sale_no_data_table($unv_sale_no){
		
		// html data
		$season = $unv_sale_no['season'];
		$auction_center = $unv_sale_no['auction_center'];
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('published', 'Yes');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\">
		<option value=\"\"> Select </option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}

	// bulk sale entry
	function bulk_sale_entry_sale_no_data_table($unv_sale_no){
		
		// html data
		$season = $unv_sale_no['season'];
		$auction_center = $unv_sale_no['auction_center'];
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('published', 'Yes');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\">
		<option value=\"\"> Select </option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
// invoice entry sale no
function get_invoice_entry_sale_no_data_table($cp_sl_arg, $edit_sale_no = null){
    
    // html data
    $season_name = $cp_sl_arg['season'];
    $auction_center = $cp_sl_arg['auction_center'];
    
    // Get current sale number from current_sale_no table
    $this->db->select('sale_no');
    $this->db->from('current_sale_no');
    $this->db->where('id', 1);
    $current_sale_query = $this->db->get();
    $current_sale_data = $current_sale_query->row();
    $current_sale_no = $current_sale_data->sale_no;
    
    // query run for available sale numbers
    $this->db->select('sale_no'); 
    $this->db->from('sale_schedule_setup');
    $this->db->where('season_name', $season_name);
    $this->db->where('auction_center', $auction_center);
    $this->db->where('soft_delete', 1);
    $this->db->group_by('sale_no');
    $this->db->order_by('sale_no ASC');
    $query = $this->db->get();
    $result = $query->result_array();
    
    // show data
    $sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\" onchange=\"get_sale_date()\" tabindex=\"2\">
    <option value=\"\"> Select </option>";
    
    $has_selected = false;
    foreach($result AS $sn_row){
        // If edit_sale_no is provided, use it; otherwise use current_sale_no
        if($edit_sale_no !== null) {
            $selected = ($sn_row['sale_no'] == $edit_sale_no) ? 'selected' : '';
        } else {
            $selected = ($sn_row['sale_no'] == $current_sale_no) ? 'selected' : '';
        }
        if($selected) $has_selected = true;
        $sale_no_list.= "<option value=\"$sn_row[sale_no]\" $selected> $sn_row[sale_no] </option>";
    }
    $sale_no_list.= "</select>";
    
    // Add JavaScript to trigger get_sale_date if a sale_no is preselected (only for new entries)
    if($has_selected && $edit_sale_no === null) {
        $sale_no_list .= "<script>
            $(document).ready(function() {
                setTimeout(function() {
                    if($('#sale_no').val() != '') {
                        get_sale_date();
                    }
                }, 100);
            });
        </script>";
    }
    
    return $sale_no_list;
}
	function get_modal_entry_sale_no_data_table($cp_sl_arg){
		$season_name = $cp_sl_arg['season'];
		$auction_center = $cp_sl_arg['auction_center'];

		$this->db->select('sale_no'); 
		$this->db->from('sale_schedule_setup');
		$this->db->where('season_name', $season_name);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();

		// Fix: Use correct ID for modal and remove onchange if not needed
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"modal_sale_no\" id=\"modal_sale_no\">
			<option value=\"\">Select Sale No</option>";

		foreach($result as $sn_row){
			$sale_no_list .= "<option value=\"{$sn_row['sale_no']}\">{$sn_row['sale_no']}</option>";
		}

		$sale_no_list .= "</select>";

		return $sale_no_list;
	}


	// garden letter sale no 
	function get_gl_entry_sale_no_data_table($cp_sl_arg){
		
		// html data
		$season_name = $cp_sl_arg['season'];
		$auction_center = $cp_sl_arg['auction_center'];
		
		// query run
        $this->db->select('sale_no'); 
		$this->db->from('sale_schedule_setup');
		$this->db->where('season_name', $season_name);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\" onchange=\"loadGardenLetterText()\" tabindex=\"2\">
		<option value=\"\"> Select </option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}

	
	// Report :: garden list
	function get_garden_list_data_table($parameters){
		// html data
		$user = $parameters['user'];
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_no = $parameters['sale_no'];
		// query run
        $this->db->select('invoice_entry.garden_id, garden_and_company_setup.garden_name'); 
		$this->db->from('invoice_entry');
		$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.garden_id');
		$this->db->order_by('garden_and_company_setup.garden_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$garden_list = "<select required class=\"form-control input-sm\" name=\"garden_id\" id=\"garden_id\" style=\"text-transform:uppercase;\">
		<option value=\"\"> Select Garden</option>";
        foreach($result AS $g_list){
			$garden_list.= "<option value=\"$g_list[garden_id]\"> $g_list[garden_name] </option>";
		}
		$garden_list.= "</select>";
		return $garden_list;
	}

	// Report :: garden list sale to sale
	function get_garden_list_sale_to_sale_data_table($parameters){
		// html data
		$user = $parameters['user'];
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_from = $parameters['sale_from'];
		$sale_to = $parameters['sale_to'];
		// query run
        $this->db->select('invoice_entry.garden_id, garden_and_company_setup.garden_name'); 
		$this->db->from('invoice_entry');
		$this->db->join('garden_and_company_setup','invoice_entry.garden_id = garden_and_company_setup.id','left');
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.season', $season);
		$this->db->where("invoice_entry.sale_no BETWEEN $sale_from AND $sale_to");
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.garden_id');
		$this->db->order_by('garden_and_company_setup.garden_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$garden_list = "<select required class=\"form-control input-sm\" name=\"garden_id\" id=\"garden_id\" style=\"text-transform:uppercase;\">
		<option value=\"\"> Select Garden</option>";
        foreach($result AS $g_list){
			$sl=1;
			$garden_list.= "<option value=\"$g_list[garden_id]\"> $g_list[garden_name] </option>";
		}
		$garden_list.= "</select>";
		return $garden_list;
	}
	
	// Report :: warehouse list
	function get_warehouse_list_data_table($parameters){
		// html value
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_no = $parameters['sale_no'];
		// query run
        $this->db->select('invoice_entry.w_house_id, warehouse_setup.warehouse_name'); 
		$this->db->from('invoice_entry');
		$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id','left');
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.w_house_id');
		$this->db->order_by('warehouse_setup.warehouse_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$warehouse_list = "<select required class=\"form-control input-sm\" name=\"warehouse_id\" id=\"warehouse_id\" style=\"text-transform:uppercase;\">
		<option value=\"\"> Select Warehouse</option>";
        foreach($result AS $wh_list){
			$warehouse_list.= "<option value=\"$wh_list[w_house_id]\"> $wh_list[warehouse_name] </option>";
		}
		$warehouse_list.= "</select>";
		return $warehouse_list;
	}

	// Report :: warehouse list sale to sale
	function get_warehouse_list_sale_to_sale_data_table($parameters){
		// html value
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_no = $parameters['sale_no'];
		$sale_to = $parameters['sale_to'];
		// query run
        $this->db->select('invoice_entry.w_house_id, warehouse_setup.warehouse_name'); 
		$this->db->from('invoice_entry');
		$this->db->join('warehouse_setup','invoice_entry.w_house_id = warehouse_setup.id','left');
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where("invoice_entry.sale_no BETWEEN $sale_no AND $sale_to");
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.w_house_id');
		$this->db->order_by('warehouse_setup.warehouse_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$warehouse_list = "<select required class=\"form-control input-sm\" name=\"warehouse_id2\" id=\"warehouse_id2\" style=\"text-transform:uppercase;\">
		<option value=\"\"> Select Warehouse</option>";
        foreach($result AS $wh_list){
			$warehouse_list.= "<option value=\"$wh_list[w_house_id]\"> $wh_list[warehouse_name] </option>";
		}
		$warehouse_list.= "</select>";
		return $warehouse_list;
	}
	
	// catalogue page lock
	function get_page_lock_data_table($page_lock_arg){
		// html data
		$lot_id = $page_lock_arg['lot_id'];
		// get last_lock data
        $this->db->select('last_lock'); 
		$this->db->from('invoice_entry');
		$this->db->where('id', $lot_id);
		$query = $this->db->get();
		$result_last_lock = $query->row_array();
		// run update query
		($result_last_lock['last_lock']=='repeat') ? $this->db->set('last_lock',NULL) : $this->db->set('last_lock','repeat');
		$this->db->where('id', $lot_id);
		$result = $this->db->update('invoice_entry');
		return 1;
	}
	
	function get_sale_order_data_table($catp_data_arg){
		//html data
		$season = $catp_data_arg['season'];
		$auction_center = $catp_data_arg['auction_center'];
		$sale_no = $catp_data_arg['sale_no'];
		//query
        $this->db->select("sale_order_setup.order_serial, brokers_setup.brokers_name"); 
		$this->db->from('sale_order_setup');
		$this->db->join('brokers_setup','sale_order_setup.brokers_id = brokers_setup.id','left');
		$this->db->where('sale_order_setup.soft_delete', 1);
		$this->db->where('sale_order_setup.season_name', $season);
		$this->db->where('sale_order_setup.auction_center', $auction_center);
		$this->db->where('sale_order_setup.sale_no', $sale_no);
		$this->db->order_by('sale_order_setup.order_serial ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
	}
	
	// report :: Sale order sale no - ADMIN
	function get_sale_no_admin_table($so_sl_arg){
		// vd
		$season = $so_sl_arg['season'];
		// query
        $this->db->select('sale_no'); 
		$this->db->from('sale_schedule_setup');
		$this->db->where('season_name', $season);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\">
		<option value=\"\"> Select </option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	// pre catalogue sale no
	function pre_catalogue_sale_no_data_table($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		//$this->db->where('published'); // is null
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	// pre valuation sale no
	function pre_valuation_sale_no_data_table($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('valuation_publish'); // is null
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	// save sale entry data
	function sale_entry_save_data_table(){
		
		// html data
		$data_category = $this->input->post('data_category', TRUE);
		$invoice_id = $this->input->post('invoice_id', TRUE);
		$inv_lot_no = $this->input->post('inv_lot_no', TRUE);
		$sale_date = $this->input->post('sale_date', TRUE);
		$inv_season = $this->input->post('inv_season', TRUE);
		$net_wt_per_bag = $this->input->post('net_wt_per_bag', TRUE);
		$sample_kgs = $this->input->post('sample_kgs', TRUE);
		$net_kgs = $this->input->post('net_kgs', TRUE);
		$chest_number = $this->input->post('chest_number', TRUE);
		$sold_price = $this->input->post('sold_price', TRUE);
		$complete_price = $this->input->post('complete_price', TRUE);
		$buyer1 = (!empty($this->input->post('buyer1_bag', TRUE))) ? $this->input->post('buyer1', TRUE) : '0';
		$buyer1_bag = $this->input->post('buyer1_bag', TRUE);
		$buyer_type1 = $this->input->post('buyer_type1', TRUE);
		$buyer_con1 = $this->input->post('buyer_con1', TRUE);
		$buyer2 = (!empty($this->input->post('buyer2_bag', TRUE))) ? $this->input->post('buyer2', TRUE) : '0';
		$buyer2_bag = $this->input->post('buyer2_bag', TRUE);
		$buyer_type2 = $this->input->post('buyer_type2', TRUE);
		$buyer_con2 = $this->input->post('buyer_con2', TRUE);
		$buyer3 = (!empty($this->input->post('buyer3_bag', TRUE))) ? $this->input->post('buyer3', TRUE) : '0';
		$buyer3_bag = $this->input->post('buyer3_bag', TRUE);
		$buyer_type3 = $this->input->post('buyer_type3', TRUE);
		$buyer_con3 = $this->input->post('buyer_con3', TRUE);
		
		// SALE DATA
		if($data_category=='Sale'){
			
			// buyer 1
			$buyer1_kg_dm = ($sample_kgs > 0 && $buyer1 > 0 && $buyer2 == 0 && $buyer3 == 0) ? ($buyer1_bag - 1) . ' X ' . number_format($net_wt_per_bag,2,'.','') : $buyer1_bag . ' X ' . number_format($net_wt_per_bag,2,'.','');
			
			$buyer1_smpl_dm = ($sample_kgs > 0 && $buyer1 > 0 && $buyer2 == 0 && $buyer3 == 0) ? '1' . ' X ' . number_format(($net_wt_per_bag - $sample_kgs),2,'.','') : '';
			
			$total_kgs = ($sample_kgs > 0 && $buyer1 > 0 && $buyer2 == 0 && $buyer3 == 0) ? ($buyer1_bag - 1) * $net_wt_per_bag : $buyer1_bag * $net_wt_per_bag;
			
			$total_sample_kgs = ($sample_kgs > 0 && $buyer1 > 0 && $buyer2 == 0 && $buyer3 == 0) ? 1 * ($net_wt_per_bag - $sample_kgs) : 0;
			
			$buyer1_total_kgs = $total_kgs + $total_sample_kgs;
			$buyer1_amount = $buyer1_total_kgs * $sold_price;
			$buyer1_complete_amount = $buyer1_total_kgs * $complete_price;
		
			// buyer 2
			$buyer2_kg_dm = ($sample_kgs > 0 && $buyer2 > 0 && $buyer3 == 0) ? ($buyer2_bag - 1) . ' X ' . number_format($net_wt_per_bag,2,'.','') : $buyer2_bag . ' X ' . number_format($net_wt_per_bag,2,'.','');
			
			$buyer2_smpl_dm = ($sample_kgs > 0 && $buyer2 > 0 && $buyer3 == 0) ? '1' . ' X ' . number_format(($net_wt_per_bag - $sample_kgs),2,'.','') : '';
			
			$total_kgs = ($sample_kgs > 0 && $buyer2 > 0 && $buyer3 == 0) ? ($buyer2_bag - 1) * $net_wt_per_bag : $buyer2_bag * $net_wt_per_bag;
			
			$total_sample_kgs = ($sample_kgs > 0 && $buyer2 > 0 && $buyer3 == 0) ? 1 * ($net_wt_per_bag - $sample_kgs) : 0;
			
			$buyer2_total_kgs = $total_kgs + $total_sample_kgs;
			$buyer2_amount = $buyer2_total_kgs * $sold_price;
			$buyer2_complete_amount = $buyer2_total_kgs * $complete_price;
		
			// buyer 3
			$buyer3_kg_dm = ($sample_kgs > 0 && $buyer3 > 0) ? ($buyer3_bag - 1) . ' X ' . number_format($net_wt_per_bag,2,'.','') : $buyer3_bag . ' X ' . number_format($net_wt_per_bag,2,'.','');
			
			$buyer3_smpl_dm = ($sample_kgs > 0 && $buyer3 > 0) ? '1' . ' X ' . number_format(($net_wt_per_bag - $sample_kgs),2,'.','') : '';
			
			$total_kgs = ($sample_kgs > 0 && $buyer3 > 0) ? ($buyer3_bag - 1) * $net_wt_per_bag : $buyer3_bag * $net_wt_per_bag;
			
			$total_sample_kgs = ($sample_kgs > 0 && $buyer3 > 0) ? 1 * ($net_wt_per_bag - $sample_kgs) : 0;
			
			$buyer3_total_kgs = $total_kgs + $total_sample_kgs;
			$buyer3_amount = $buyer3_total_kgs * $sold_price;
			$buyer3_complete_amount = $buyer3_total_kgs * $complete_price;
			// end
			
			$user = $this->input->post('user', TRUE);
			$last_update = $this->input->post('last_update', TRUE);
			$datetime = date("Y-m-d").'T'.date("H:i");
		
			// check duplicate data
			$this->db->where('invoice_id',$invoice_id);
			$duplicate_data = $this->db->get('sale_entry');
			if($duplicate_data->num_rows() > 0){
		
				$this->db->set('lot_no', $inv_lot_no);
				$this->db->set('sale_date', $sale_date);
				$this->db->set('season', $inv_season);
				$this->db->set('sold_price', $sold_price);
				$this->db->set('complete_price', $complete_price);
				
				$this->db->set('buyer1', $buyer1);
				$this->db->set('buyer1_bag', $buyer1_bag);
				$this->db->set('buyer1_kg_dm', $buyer1_kg_dm);
				$this->db->set('buyer1_smpl_dm', $buyer1_smpl_dm);
				$this->db->set('buyer1_total_kgs', $buyer1_total_kgs);
				$this->db->set('buyer1_amount', $buyer1_amount);
				$this->db->set('buyer1_complete_amount', $buyer1_complete_amount);
				$this->db->set('buyer_type1', $buyer_type1);
				$this->db->set('buyer_con1', $buyer_con1);
				
				$this->db->set('buyer2', $buyer2);
				$this->db->set('buyer2_bag', $buyer2_bag);
				$this->db->set('buyer2_kg_dm', $buyer2_kg_dm);
				$this->db->set('buyer2_smpl_dm', $buyer2_smpl_dm);
				$this->db->set('buyer2_total_kgs', $buyer2_total_kgs);
				$this->db->set('buyer2_amount', $buyer2_amount);
				$this->db->set('buyer2_complete_amount', $buyer2_complete_amount);
				$this->db->set('buyer_type2', $buyer_type2);
				$this->db->set('buyer_con2', $buyer_con2);
				
				$this->db->set('buyer3', $buyer3);
				$this->db->set('buyer3_bag', $buyer3_bag);
				$this->db->set('buyer3_kg_dm', $buyer3_kg_dm);
				$this->db->set('buyer3_smpl_dm', $buyer3_smpl_dm);
				$this->db->set('buyer3_total_kgs', $buyer3_total_kgs);
				$this->db->set('buyer3_amount', $buyer3_amount);
				$this->db->set('buyer3_complete_amount', $buyer3_complete_amount);
				$this->db->set('buyer_type3', $buyer_type3);
				$this->db->set('buyer_con3', $buyer_con3);
				
				$this->db->set('user', $user);
				$this->db->set('last_update', $last_update);
				$this->db->where('invoice_id',$invoice_id);
				$result = $this->db->update('sale_entry');
				return 1;
			
			}else{
				
				$data = array(
					'invoice_id' => $invoice_id,
					'lot_no' => $inv_lot_no,
					'sale_date' => $sale_date,
					'season' => $inv_season,
					'sold_price' => $sold_price,
					'complete_price' => $complete_price,

					'buyer1' => $buyer1,
					'buyer1_bag' => $buyer1_bag,
					'buyer1_kg_dm' => $buyer1_kg_dm,
					'buyer1_smpl_dm' => $buyer1_smpl_dm,
					'buyer1_total_kgs' => $buyer1_total_kgs,
					'buyer1_amount' => $buyer1_amount,
					'buyer1_complete_amount' => $buyer1_complete_amount,
					'buyer_type1' => $buyer_type1,
					'buyer_con1' => $buyer_con1,

					'buyer2' => $buyer2,
					'buyer2_bag' => $buyer2_bag,
					'buyer2_kg_dm' => $buyer2_kg_dm,
					'buyer2_smpl_dm' => $buyer2_smpl_dm,
					'buyer2_total_kgs' => $buyer2_total_kgs,
					'buyer2_amount' => $buyer2_amount,
					'buyer2_complete_amount' => $buyer2_complete_amount,
					'buyer_type2' => $buyer_type2,
					'buyer_con2' => $buyer_con2,

					'buyer3' => $buyer3,
					'buyer3_bag' => $buyer3_bag,
					'buyer3_kg_dm' => $buyer3_kg_dm,
					'buyer3_smpl_dm' => $buyer3_smpl_dm,
					'buyer3_total_kgs' => $buyer3_total_kgs,
					'buyer3_amount' => $buyer3_amount,
					'buyer3_complete_amount' => $buyer3_complete_amount,
					'buyer_type3' => $buyer_type3,
					'buyer_con3' => $buyer_con3,

					'user' => $user,
					'last_update' => $last_update,
					'datetime' => $datetime
				);
				$result = $this->db->insert('sale_entry',$data);
				return 1;
			}
			
		}else if($data_category=='Out'){
			
			$tables = array('sale_entry');
			$this->db->where('invoice_id', $invoice_id);
			$this->db->delete($tables);
			return 1;
		}
	}

	function after_sale_sale_no_data_table($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no\" id=\"sale_no\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function contract_buyer_list_data_table($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_no = $parameters['sale_no'];
		// query
        $this->db->select("
			buyer_setup.id, 
			buyer_setup.buyer_name, 
			(CASE
				WHEN buyer_setup.id = sale_entry.buyer1 THEN sale_entry.buyer_con1
				WHEN buyer_setup.id = sale_entry.buyer2 THEN sale_entry.buyer_con2
				WHEN buyer_setup.id = sale_entry.buyer3 THEN sale_entry.buyer_con3
			END) AS buyer_con
		"); 
		$this->db->from("invoice_entry");
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->join("buyer_setup", "
			buyer_setup.id = sale_entry.buyer1 OR 
			buyer_setup.id = sale_entry.buyer2 OR 
			buyer_setup.id = sale_entry.buyer3
		");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.sale_no', $sale_no);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('buyer_setup.id, buyer_con');
		$this->db->order_by('buyer_setup.buyer_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$buyer_list = "<select required class=\"form-control input-sm\" name=\"buyer_id\" id=\"buyer_id\" style=\"text-transform:uppercase;\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$buyer_list.= "<option value=\"$sn_row[id]\" title=\"$sn_row[buyer_con]\"> $sn_row[buyer_name] $sn_row[buyer_con] </option>";
		}
		$buyer_list.= "</select>";
		return $buyer_list;
	}
	
	function ait_buyer_list_data_table($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$sale_from = $parameters['sale_from'];
		$sale_to = $parameters['sale_to'];
		
		// query
        $this->db->select('buyer_setup.id, buyer_setup.buyer_name'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->join("buyer_setup","sale_entry.buyer1 = buyer_setup.id OR sale_entry.buyer2 = buyer_setup.id OR sale_entry.buyer3 = buyer_setup.id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where("invoice_entry.sale_no BETWEEN $sale_from AND $sale_to");
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->where('buyer_setup.main_buyer_id'); // is null
		$this->db->group_by('buyer_setup.id');
		$this->db->order_by('buyer_setup.buyer_name ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// show data
		$buyer_list = "<select required class=\"form-control input-sm\" name=\"buyer_id\" id=\"buyer_id\" style=\"text-transform:uppercase;\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$buyer_list.= "<option value=\"$sn_row[id]\"> $sn_row[buyer_name] </option>";
		}
		$buyer_list.= "</select>";
		return $buyer_list;
	}

	function get_buyer_list_data_table($parameters){
	
	$auction_center = $parameters['auction_center'];
	$season_from = $parameters['season_from'];
	$season_to = $parameters['season_to'];
	$sale_from = $parameters['sale_from'];
	$sale_to = $parameters['sale_to'];
	
	// Get all buyers between season range and sale range
    $this->db->select('buyer_setup.id, buyer_setup.buyer_name'); 
	$this->db->from('invoice_entry');
	$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
	$this->db->join("buyer_setup","sale_entry.buyer1 = buyer_setup.id OR sale_entry.buyer2 = buyer_setup.id OR sale_entry.buyer3 = buyer_setup.id");
	$this->db->where('invoice_entry.auction_center', $auction_center);
	$this->db->where('invoice_entry.soft_delete', 1);
	$this->db->where('buyer_setup.main_buyer_id IS NULL'); // Only show main buyers
	
	// Season and Sale range condition
	$this->db->where("
		(invoice_entry.season = '$season_from' AND invoice_entry.sale_no >= $sale_from) 
		OR 
		(invoice_entry.season = '$season_to' AND invoice_entry.sale_no <= $sale_to)
	");
	
	$this->db->group_by('buyer_setup.id');
	$this->db->order_by('buyer_setup.buyer_name ASC');
	$query = $this->db->get();
	$result = $query->result_array();
	
	$buyer_list = "<select required class=\"form-control input-sm\" name=\"buyer_id\" id=\"buyer_id\" style=\"text-transform:uppercase;\">
	<option value=\"\">Select Buyer</option>";
    foreach($result AS $row){
		$buyer_list.= "<option value=\"$row[id]\">$row[buyer_name]</option>";
	}
	$buyer_list.= "</select>";
	return $buyer_list;
}
	
	function vat_ait_sample_info_data($catp_data_arg){
		$user = $catp_data_arg['user'];
		$this->db->select('*'); 
		$this->db->from('cover_page_info_setup');
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!empty($result['cover_page_text'])){
			return $result['cover_page_text'];
		}else{
			return '';
		}	
	}
	
	function get_buyer_type_table($arg){
		
		// get html value
		$buyer_id = $arg['buyer_id'];
		
		// query
		$this->db->select('*'); 
		$this->db->from('buyer_setup');
		$this->db->where('id', $buyer_id);
		$this->db->where('soft_delete', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['buyer_type'].'@'.$result['buyer_name'];
	}
	
	// delete setup data
	function delete_setup_data_table($c_details){
		// HTML VALUE
		$id = $c_details['delete_id'];
		$delete_name = $c_details['delete_name'];
		$data_status = $c_details['data_status'];
		$last_update = $c_details['last_update'];
		$soft_delete = ($data_status=='Deactivate') ? "0" : "1";
		// run query
		if($delete_name=='garden_list'){
			$this->db->set('soft_delete', $soft_delete);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', $id);
			$result = $this->db->update('garden_and_company_setup');
		}
		return 1;
	}
	
	// delete garden adjust
	function delete_garden_adjust_table($c_details){
		// HTML VALUE
		$id = $c_details['delete_id'];
		// run query
		$this->db->where('id', $id);
		$this->db->delete('garden_adjust_tbl');
		return 1;
	}

	// delete contact person
	// delete contact person
	function delete_contact_person_table($c_details) {
		$id = $c_details['delete_id'];
	
		// Delete from Localhost Database
		$this->db->where('id', $id);
		$this->db->delete('contact_person_list');
	
		// Delete from cPanel Database using API
		$url = "https://www.unitybrokersltd.com/api/delete_contact_api.php";
		$postData = json_encode(["delete_id" => $id]);
	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
			"Content-Length: " . strlen($postData)
		]);
	
		// Execute request and get response
		$response = curl_exec($ch);
		curl_close($ch);

		// Decode API response
		$result = json_decode($response, true);

		// Return success or failure message
		if ($result && isset($result['status']) && $result['status'] === "success") {
			return 1; // Success
		} else {
			return 0; // Error
		}
	}
	
	// delete lot list
	function delete_lot_table($c_details){
		// HTML VALUE
		$id = $c_details['delete_id'];
		$pre_invoice_id = $c_details['pre_invoice_id'];
		// run query
		$this->db->where('id', $id);
		$this->db->delete('invoice_entry');

		//update catalogue entry
		$this->db->set('catalogue_entry', 'no');
		$this->db->where('id', $pre_invoice_id);
		$result = $this->db->update('pre_invoice_entry');

		return 1;
	}

	// pre invoice delete
	function delete_pre_inv_table($c_details){
		// HTML VALUE
		$id = $c_details['delete_id'];
		// run query
		$this->db->where('id', $id);
		$this->db->delete('pre_invoice_entry');
		return 1;
	}

	// Market average delete
	function delete_mavg_table($c_details){
		// HTML VALUE
		$id = $c_details['delete_id'];
		// run query
		$this->db->where('id', $id);
		$this->db->delete('market_average');
		return 1;
	}
	
	// autocomplte
	function search_inv_no($inv_no){
        $this->db->like('w_inv_no', $inv_no , 'both');
        $this->db->group_by('w_inv_no');
        $this->db->order_by('w_inv_no', 'ASC');
        $this->db->limit(10);
        return $this->db->get('invoice_entry')->result();
    }
	
	// save garden adjust data
	function garden_adjust_entry_save_data(){
		
		// html data
		$setup_id = trim($this->input->post('setup_id', TRUE)); // true xxs_clean
		$season = $this->input->post('season', TRUE);
		$old_season = $this->input->post('old_season', TRUE);
		$auction_center = $this->input->post('auction_center', TRUE);
		$sale_no = $this->input->post('sale_no', TRUE);
		$garden_id = $this->input->post('garden_id', TRUE);
		$charges_name = $this->input->post('charges_name', TRUE);
		$charge_tk = $this->input->post('charge_tk', TRUE);
		$particular = $this->input->post('particular', TRUE);
		$user = $this->input->post('user', TRUE);
		$last_update = $this->input->post('last_update', TRUE);
		$datetime = date("Y-m-d").'T'.date("H:i");

		// insert data
		if(empty($setup_id)){

			// insert data
			$data = array(
				'season' => $season, 
				'old_season' => $old_season, 
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id,
				'charges_name' => $charges_name,
				'charge_tk' => $charge_tk,
				'particular' => $particular,
				'user' => $user,
				'last_update' => $last_update,
				'datetime' => $datetime
			);
			$result = $this->db->insert('garden_adjust_tbl',$data);
			return 1;

		}else{

			$this->db->set('season', $season);
			$this->db->set('old_season', $old_season);
			$this->db->set('auction_center', $auction_center);
			$this->db->set('sale_no', $sale_no);
			$this->db->set('garden_id', $garden_id);
			$this->db->set('charges_name', $charges_name);
			$this->db->set('charge_tk', $charge_tk);
			$this->db->set('particular', $particular);
			$this->db->set('user', $user);
			$this->db->set('last_update', $last_update);
			$this->db->where('id', $setup_id);
			$result = $this->db->update('garden_adjust_tbl');
			return 2;
		}
    }
	
	// save and update user data
	function save_user_table(){
		
		// html data
		$edit_user_id = $this->input->post('edit_user_id', TRUE);
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);
		$edit_privilege = $this->input->post('edit_privilege', TRUE);
		$delete_privilege = $this->input->post('delete_privilege', TRUE);
		
		// check grade name
		$this->db->where('username', $username);
		$this->db->where('id !=', $edit_user_id);
		$check_user = $this->db->get('super_admin');

		if($check_user->num_rows() > 0) {
			return 2;
		}else{

			// update data
			if(!empty($edit_user_id)){
				
				$this->db->set('username', $username);
				$this->db->set('password', $password);
				$this->db->set('edit_privilege', $edit_privilege);
				$this->db->set('delete_privilege', $delete_privilege);
				$this->db->where('id', $edit_user_id);
				$result = $this->db->update('super_admin');
				return 1;
				
			}else{
				
				// insert data
				$data = array(
					'username' => $username, 
					'password' => $password,
					'edit_privilege' => $edit_privilege,
					'delete_privilege' => $delete_privilege
				);
				$result = $this->db->insert('super_admin',$data);
				return 1;
			}
		}
    }

// Change password
function change_password_table($ubl_web_user_id, $old_password, $new_password){
    // Check if old password matches
    $this->db->where('id', $ubl_web_user_id);
    $this->db->where('password', $old_password);
    $query = $this->db->get('super_admin');
    
    if($query->num_rows() == 0) {
        return 0; // Old password incorrect
    }
    
    // Check if new password is same as old password
    if($old_password == $new_password) {
        return "New password cannot be same as old password.";
    }
    
    // Update password
    $this->db->set('password', $new_password);
    $this->db->where('id', $ubl_web_user_id);
    $result = $this->db->update('super_admin');
    
    if($result) {
        return 1; // Success
    } else {
        return "Failed to change password. Please try again.";
    }
}
	
	// save garden origin data
	function garden_origin_save_table(){
		
		// html data
		$origin_edit_id = $this->input->post('origin_edit_id', TRUE);
		$origin_name = $this->input->post('origin_name', TRUE);
		$last_update = $this->input->post('last_update', TRUE);
		
		// check garden origin name
		$this->db->where('origin_name', $origin_name);
		$this->db->where('id !=', $origin_edit_id);
		$check_origin_name = $this->db->get('garden_origin_setup');

		if($check_origin_name->num_rows() > 0) {
			return 2;
		}else{

			// update data
			if(!empty($origin_edit_id)){
				
				$this->db->set('origin_name', $origin_name);
				$this->db->set('last_update', $last_update);
				$this->db->where('id', $origin_edit_id);
				$result = $this->db->update('garden_origin_setup');
				return 1;
				
			}else{
				
				// insert data
				$data = array(
					'origin_name' => $origin_name, 
					'last_update' => $last_update
				);
				$result = $this->db->insert('garden_origin_setup',$data);
				return 1;
			}
		}
    }
	
	// Single SMS
	function single_sms_disburse_table($c_details) {
		// HTML VALUE
		$invoice_id = $c_details['invoice_id'];
		$sale_no = $c_details['sale_no'];
		$sold_kgs = number_format($c_details['sold_kgs'],2,'.',',');
		$avg_kgs = number_format($c_details['avg_kgs'],2,'.',',');
		$percentage = number_format($c_details['percentage'],2,'.',',');
		$garden_name = $c_details['garden_name'];
		$contact_no = $c_details['contact_no'];

		// Ensure the SMS API file path is correct
        $sms_api_path = APPPATH . '../api/sms_api.php';

        if (!file_exists($sms_api_path)) {
            return 'SMS API file not found';  // Corrected the error message string
        }
	
		// Contact array data
		$exp_contact_array = explode("@", $contact_no);
	
		foreach ($exp_contact_array as $mobile_no) {
			// Ensure the contact number is not empty
			if (!empty($mobile_no)) {
				// Prepare the necessary variables for sms_api.php
				$mobile_no = $mobile_no;
				$msg = "Sale $sale_no $garden_name $sold_kgs Kgs Avg $avg_kgs Sold pct. $percentage Unity Brokers Ltd.";

				// Include the SMS API file
                include($sms_api_path);
			}
		} // end of foreach
	
		return 1;
	}	

	// Bulk SMS Garden
	function bulk_sms_disburse_table($c_details){
		// HTML VALUE
		$inv_array = $c_details['inv_array'];
		
		// Ensure the SMS API file path is correct
		$sms_api_path = APPPATH . '../api/sms_api.php';
		
		if (!file_exists($sms_api_path)) {
			return 'SMS API file not found';  // Corrected the error message string
		}
		
		// Total Invoice array data
		$invoice_contacts = explode("~", $inv_array);
		
		foreach ($invoice_contacts as $contact_details) {
			// Contact details array data
			$contact_data = explode("|", $contact_details);

			$invoice_id = $contact_data[0];
			$sale_no = $contact_data[1];
			$sold_kgs = number_format($contact_data[2],2,'.',',');
			$avg_kgs = number_format($contact_data[3],2,'.',',');
			$percentage = number_format($contact_data[4],2,'.',',');
			$garden_name = $contact_data[5];

			$contact_info = explode("@", $contact_data[6]); // Explode contact data at index 2
			
			foreach ($contact_info as $mobile_no) {
				// Ensure the contact number is not empty
				if (!empty($mobile_no)) {
					// Prepare the necessary variables for sms_api.php
					$mobile_no = $mobile_no;
					$msg = "Sale $sale_no $garden_name $sold_kgs Kgs Avg $avg_kgs Sold pct. $percentage Unity Brokers Ltd.";
		
					// Include the SMS API file
					include($sms_api_path);
				}
			} // end of foreach
		} // end of foreach
		
		return 1;
	}


	// Bulk SMS Garden
	function bulk_sms_disburse2_table($c_details) {

		$inv_array = $c_details['inv_array'];
	
		// Ensure the SMS API file path is correct
		$sms_api_path = APPPATH . '../api/bulk_sms_api.php';
	
		if (!file_exists($sms_api_path)) {
			return 'SMS API file not found';
		}
	
		// Include the SMS API file once
		include($sms_api_path);
	
		// Split group array
		$group_array = explode("#", $inv_array);
	
		foreach ($group_array as $group_wise_contact) {
			// Split contact and invoice parts
			$group_parts = explode("~", $group_wise_contact);
	
			// Ensure we have at least two parts (contact info and invoice details)
			if (count($group_parts) < 2) {
				continue; // Skip invalid group formats
			}
	
			// The first part is the contact information
			$contact_info = explode("@", $group_parts[0]);
	
			// All remaining parts are invoice details
			$invoice_details_array = array_slice($group_parts, 1);
	
			// Create a variable to accumulate messages
			$msg = '';
	
			foreach ($invoice_details_array as $invoice_details) {
				// Split each invoice data
				$invoice_data = explode("|", $invoice_details);
	
				// Check if the invoice data array has exactly six elements
				if (count($invoice_data) != 6) {
					continue; // Skip invalid invoice data
				}
	
				// Extract invoice details
				$invoice_id = $invoice_data[0];
				$sale_no = $invoice_data[1];
				$sold_kgs = number_format($invoice_data[2], 2, '.', ',');
				$avg_kgs = number_format($invoice_data[3], 2, '.', ',');
				$percentage = number_format($invoice_data[4], 2, '.', ',');
				$garden_name = $invoice_data[5];
	
				// Append the message for this invoice to the accumulated message
				$msg .= "Sale $sale_no $garden_name $sold_kgs Kgs Avg $avg_kgs Sold pct. $percentage Unity Brokers Ltd.\n";
			} // end of foreach invoice_details_array
	
			// Send the accumulated message to each contact number
			foreach ($contact_info as $mobile_no) {
				// Ensure the contact number is not empty and is valid
				if (!empty($mobile_no)) {
					// Send SMS using the included SMS API
					send_sms($mobile_no, $msg); // Assuming send_sms() is defined in the included SMS API
				}
			} // end of foreach contact_info
		} // end of foreach group_array
	
		return 1;
	}
	// Assume send_sms function is defined in sms_api.php
	
	function after_sale_sale_no_data_api($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no14\" id=\"sale_no14\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	
	function after_sale_sale_no_data_api2($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no19\" id=\"sale_no19\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api3($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no20\" id=\"sale_no20\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api4($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no21\" id=\"sale_no21\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api5($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no22\" id=\"sale_no22\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api6($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('published', 'Yes');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no23\" id=\"sale_no23\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api7($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
		$this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('published', 'Yes');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no24\" id=\"sale_no24\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api8($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no25\" id=\"sale_no25\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	
	function after_sale_sale_no_data_api9($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$catalouge_name = $parameters['catalouge_name'];
		
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		//$this->db->where('user', $user);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		($catalouge_name=='All') ? '' : $this->db->where('catalouge_name', $catalouge_name);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no26\" id=\"sale_no26\">
		<option value=\"\"> Select </option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api10($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		$catalouge_name = $parameters['catalouge_name'];
		
		// query
        $this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('catalogue_status', 'prepared');
		//$this->db->where('user', $user);
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		($catalouge_name=='All') ? '' : $this->db->where('catalouge_name', $catalouge_name);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		
		// show data
		$sale_no_list = "<select required class=\"form-control input-sm\" name=\"sale_no27\" id=\"sale_no27\">
		<option value=\"\"> Select </option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api11($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
		$this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('published', 'Yes');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no28\" id=\"sale_no28\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api12($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
		$this->db->select('sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->where('published', 'Yes');
		$this->db->where('season', $season);
		$this->db->where('auction_center', $auction_center);
		$this->db->where('soft_delete', 1);
		$this->db->group_by('sale_no');
		$this->db->order_by('sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no29\" id=\"sale_no29\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}
	
	function after_sale_sale_no_data_api13($parameters){
		
		// html data
		$season = $parameters['season'];
		$auction_center = $parameters['auction_center'];
		// query
        $this->db->select('invoice_entry.sale_no'); 
		$this->db->from('invoice_entry');
		$this->db->join("sale_entry","invoice_entry.id = sale_entry.invoice_id");
		$this->db->where('invoice_entry.season', $season);
		$this->db->where('invoice_entry.auction_center', $auction_center);
		$this->db->where('invoice_entry.soft_delete', 1);
		$this->db->group_by('invoice_entry.sale_no');
		$this->db->order_by('invoice_entry.sale_no ASC');
		$query = $this->db->get();
		$result = $query->result_array();
		// show data
		$sale_no_list = "<select required class=\"form-control form-control-sm\" name=\"sale_no30\" id=\"sale_no30\">
		<option value=\"\">Select</option>";
        foreach($result AS $sn_row){
			$sale_no_list.= "<option value=\"$sn_row[sale_no]\"> $sn_row[sale_no] </option>";
		}
		$sale_no_list.= "</select>";
		return $sale_no_list;
	}

	// Assign page numbers to lot
	public function set_catalogue_index_table($parameters) {
		$pageNo = $parameters['page'];              // Target page number
		$lotArray = $parameters['lotArray'];        // Array of IDs to update
	
		// Safety: ensure array is valid
		if (is_array($lotArray) && count($lotArray) > 0) {
			// Update index_pageNo for all matching IDs
			$this->db->where_in('id', $lotArray);   // Change 'id' to your actual column name if needed
			$this->db->set('index_pageNo', $pageNo);
			$this->db->update('invoice_entry');
	
			return 1; // Success
		} else {
			return 0; // Invalid lotArray
		}
	}	
	
function update_warehouse_charge_data() {
    $season = $this->input->post('season', TRUE);
    $auction_center = $this->input->post('auction_center', TRUE);
    $sale_no = $this->input->post('sale_no', TRUE);
    $garden_id = $this->input->post('garden_id', TRUE);
    $old_season = $this->input->post('old_season', TRUE);
    $w_house_id = $this->input->post('w_house_id', TRUE);
    $warehouse_charge = $this->input->post('warehouse_charge', TRUE);

    if ($warehouse_charge === '' || !is_numeric($warehouse_charge)) {
        return array('success' => false, 'message' => 'Invalid warehouse charge value');
    }

    $this->db->set('warehouse_charge', $warehouse_charge);
    $this->db->where('season', $season);
    $this->db->where('auction_center', $auction_center);
    $this->db->where('sale_no', $sale_no);
    $this->db->where('garden_id', $garden_id);
    $this->db->where('w_house_id', $w_house_id);
    $this->db->update('invoice_entry');

    $affected = $this->db->affected_rows();
    if ($affected > 0) {
        return array('success' => true, 'message' => 'Warehouse charge updated', 'affected' => $affected);
    } else {
        return array('success' => false, 'message' => 'No matching invoice_entry row found (no rows updated)');
    }
}




function update_garden_charge_data() {
    $season = $this->input->post('season', TRUE);
    $auction_center = $this->input->post('auction_center', TRUE);
    $sale_no = $this->input->post('sale_no', TRUE);
    $garden_id = $this->input->post('garden_id', TRUE);
    $old_season = $this->input->post('old_season', TRUE);
    $charge_type = $this->input->post('charge_type', TRUE);
    $charge_amount = $this->input->post('charge_amount', TRUE);
    $datetime = date("Y-m-d\TH:i");

    if ($charge_amount === '' || !is_numeric($charge_amount)) {
        return array('success' => false, 'message' => 'Invalid charge value');
    }

    // Check if record exists
    $this->db->where([
        'season' => $season,
        'auction_center' => $auction_center,
        'sale_no' => $sale_no,
        'garden_id' => $garden_id,
        'old_season' => $old_season,
        'charges_name' => $charge_type
    ]);
    $exists = $this->db->get('garden_adjust_tbl')->num_rows();

    if ($exists) {
        // Update
        $this->db->where([
            'season' => $season,
            'auction_center' => $auction_center,
            'sale_no' => $sale_no,
            'garden_id' => $garden_id,
            'old_season' => $old_season,
            'charges_name' => $charge_type
        ]);
        $result = $this->db->update('garden_adjust_tbl', ['charge_tk' => $charge_amount]);
        $action = 'updated';
    } else {
        // Insert
        $data = [
            'season' => $season,
            'auction_center' => $auction_center,
            'sale_no' => $sale_no,
            'garden_id' => $garden_id,
            'old_season' => $old_season,
            'charges_name' => $charge_type,
            'charge_tk' => $charge_amount,
            'datetime' => $datetime
        ];
        $result = $this->db->insert('garden_adjust_tbl', $data);
        $action = 'inserted';
    }

    return $result
        ? ['success' => true, 'message' => "Charge $action successfully"]
        : ['success' => false, 'message' => "Failed to $action charge"];
}

function update_garden_avg_field_data() {
    $id = $this->input->post('id', TRUE);
    $season = $this->input->post('season', TRUE);
    $auction_center = $this->input->post('auction_center', TRUE);
    $sale_no = $this->input->post('sale_no', TRUE);
    $brokers_id = $this->input->post('brokers_id', TRUE);
    $category = $this->input->post('category', TRUE);
    $garden_id = $this->input->post('garden_id', TRUE);
    $field_type = $this->input->post('field_type', TRUE);
    $value = $this->input->post('value', TRUE);

    // Validate input
    if ($value === '' || !is_numeric($value)) {
        return array('success' => false, 'message' => 'Invalid value');
    }

    // Update the specific field
    $this->db->set($field_type, $value);
    $this->db->where('id', $id);
    $this->db->where('season', $season);
    $this->db->where('auction_center', $auction_center);
    $this->db->where('sale_no', $sale_no);
    $this->db->where('brokers_id', $brokers_id);
    $this->db->where('category', $category);
    $this->db->where('garden_id', $garden_id);
    
    $result = $this->db->update('all_garden_avg');

    if ($result) {
        return array('success' => true, 'message' => 'Field updated successfully');
    } else {
        return array('success' => false, 'message' => 'Failed to update field');
    }
}

//ajax model function - updated to handle category field
function update_brokers_buyer_pur_field_data() {
    $id = $this->input->post('id', TRUE);
    $season = $this->input->post('season', TRUE);
    $auction_center = $this->input->post('auction_center', TRUE);
    $sale_no = $this->input->post('sale_no', TRUE);
    $brokers_id = $this->input->post('brokers_id', TRUE);
    $category = $this->input->post('category', TRUE);
    $buyer_id = $this->input->post('buyer_id', TRUE);
    $field_type = $this->input->post('field_type', TRUE);
    $value = $this->input->post('value', TRUE);

    // For numeric fields, validate the number
    if ($field_type !== 'category') {
        if ($value === '' || !is_numeric($value)) {
            return array('success' => false, 'message' => 'Invalid numeric value');
        }
        $value = floatval($value);
    }

    // Update the specific field
    $this->db->set($field_type, $value);
    $this->db->where('id', $id);
    $this->db->where('season', $season);
    $this->db->where('auction_center', $auction_center);
    $this->db->where('sale_no', $sale_no);
    $this->db->where('brokers_id', $brokers_id);
    $this->db->where('buyer_id', $buyer_id);
    
    // For category updates, we don't use the old category in WHERE clause
    if ($field_type !== 'category') {
        $this->db->where('category', $category);
    }
    
    $result = $this->db->update('all_broker_buyer_purchase');

    if ($result) {
        return array('success' => true, 'message' => 'Field updated successfully');
    } else {
        return array('success' => false, 'message' => 'Failed to update field');
    }
}
	
} 
// end model class

?>