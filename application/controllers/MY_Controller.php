<?php
class MY_Controller extends CI_Controller{

	// "global" items
	var $data;

	// Global use
	function __construct(){
		parent::__construct(); // needed when adding a constructor to a controller
        $this->data = array(
			'website_title' => 'Unity Brokers Limited',
			'owner_address' => 'Progressive Tower, 5th Floor, 1837 SK Mujib Road, Agrabad C/A, Chittagong-4100.',
			'owner_short_address' => '1837 SK Mujib Road, CTG',
			'owner_contact' => 'Tel:+880 2333320692',
			'owner_email' => 'unity.brokers@gmail.com',
			'owner_bin' => '##',
			'default_ac' => 'Chattogram',
			'custom_address' => 'Chattogram',
			'author' => 'Jamuna Tech',
			'css' => $this->config->item('css'),
			'year' => date("Y"),
			'today' => date("d-M-Y"),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'define_brokers_id' => '6', //as per brokers setup id
			'ubl_web_user_id' => $this->session->userdata('ubl_web_user_id'),
			'ubl_user_name' => $this->session->userdata('ubl_user_name'),
			'ubl_user_type' => $this->session->userdata('ubl_user_type'),
			'ubl_user_admin' => $this->session->userdata('ubl_user_admin'),
			'password' => $this->rand_string(8) // call function
		);
        // $this->data ## can be accessed from anywhere in the controller ##
		$this->load->model('ajax_model');
		$this->load->library(array('form_validation','session','email'));
		$this->load->helper(array('url','html','form','security','date','cookie','file'));
		$this->user_id = $this->session->userdata('user_id');
		$this->ubl_web_user_id = $this->session->userdata('ubl_web_user_id');
	}
	// end

	// $this->library_name->method();

	public function index(){
		$data = $this->data; 
	
		$this->load->view('website/head', $data);
		$this->load->view('website/nav', $data);
		$this->load->view('website/index', $data);
		$this->load->view('website/footer', $data);
	}

	// login action
	public function web_login_action(){
		$data = $this->data;

		// set_rules('field_name', 'Field Label', 'rule1|rule2|rule3')
		$this->form_validation->set_rules('uname', 'Username', 'trim|required|min_length[5]|max_length[50]'); 
		$this->form_validation->set_rules('psw', 'Password', 'trim|required|min_length[5]');

		// customize form error message for every rules
		// $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        // $this->form_validation->set_message('required', 'Enter %s');
		
		if ($this->form_validation->run() === FALSE){

			$this->load->view('website/head', $data);
			$this->load->view('website/nav', $data);
			$this->load->view('website/index', $data);
			$this->load->view('website/footer', $data);
			
		}else{

			$parameters = array(
				'user' => $this->input->post('uname', TRUE), // TRUE for XSS Clean
				'pass' => $this->input->post('psw', TRUE),
			);
	
			$login_success = $this->ajax_model->web_login_process_data($parameters);
			if($login_success != false){ 

				$web_user = array(
					'ubl_web_user_id' => $login_success->id,
					'ubl_user_name' => $login_success->username,
					'ubl_user_admin' => $login_success->user_admin,
					'ubl_user_type' => 'Brokers'
				);

				// insert login log data
				$data_log = array(
					'user_id' => $login_success->id, 
					'user_type' => 'Brokers',
					'datetime' => date("Y-m-d H:i:s"),
					'ip' => $data['ip']
				);
				$log_insert = $this->db->insert('user_log', $data_log);
				// end log data insert

				// set cookie
				if($this->input->post("chkremember")){

					$user = $parameters['user'];
					$pass = $parameters['pass'];
					/* 86400 = 1 day */
                    $this->input->set_cookie('uemail', $user, 86500);    /* Create cookie for store email id */
					$this->input->set_cookie('upassword', $pass, 86500); /* Create cookie for password */
					
				}
				// end cookie 

				//declaring session  
				$this->session->set_userdata($web_user);  
				redirect('MY_Controller/web_user_home'); 

			}else{  

				$data['web_error'] = 'Your Account is Invalid'; 

				$this->load->view('website/head', $data);
				$this->load->view('website/nav', $data);
				$this->load->view('website/index', $data);
				$this->load->view('website/footer', $data);
			}
		}  
	}

	// set web session user
	public function web_authorization_user(){
		if (!$this->session->userdata("ubl_web_user_id")) {
			$this->session->set_flashdata('web_data_name', "Your session has expired.Please refresh the page and try again."); // session name, flash message
			redirect("MY_Controller/index", "refresh");
		}
	}
	
	public function sa_home(){
		$this->web_authorization_user();
		$data = $this->data;
		// view page
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/navbar', $data);
		$this->load->view('super_admin/dashboard', $data);
		$this->load->view('super_admin/footer', $data);
	}

	// Grade Setup
	public function grade_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('grade_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/grade_setup', $data);
		$this->load->view('website/login_footer', $data);
	}
	
	// Warehouse Setup
	public function warehouse_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('warehouse_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/warehouse_setup', $data);
		$this->load->view('website/login_footer', $data);
	}
	
	// Buyer Setup
	public function buyer_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('buyer_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/buyer_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	// Season Setup
	public function season_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('season_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/season_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	// Current Season Setup
	public function current_season_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_data'] = $this->ajax_model->season_data_table();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/current_season_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	// Sale Schedule Setup
	public function sale_schedule_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('sale_schedule_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/sale_schedule_setup',$data);
		$this->load->view('website/login_footer', $data);
	}
	
	// Sale order Information
	public function sale_order_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['brokers_data'] = $this->ajax_model->brokers_data_table();
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/sale_order_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	// Catalogue Setup
	public function catalogue_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('catalogue_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/catalogue_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	// Sample Draw Setup
	public function sample_draw_process(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('sample_draw_process');
		$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/sample_draw_process', $data);
		$this->load->view('website/login_footer', $data);
	}

	// super admin Chest Information
	public function chest_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('chest_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/chest_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	// super admin Regulatory Information
	public function regulatory_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('regulatory_setup');
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/regulatory_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	// super admin Sale Condition Information
	public function sale_condtion_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('sale_condtion_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/sale_condtion_setup',$data);
		$this->load->view('website/login_footer', $data);
	}

	// super admin Vat / Tax / Sample Information
	public function vat_tax_sample_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('vat_tax_sample_setup');
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/navbar', $data);
		$this->load->view('super_admin/vat_tax_sample_setup', $data);
		$this->load->view('super_admin/footer', $data);
	}
	
	// rand data
	public function rand_string($length){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);
	}
	
	// send mail
	public function send_mail($from,$identification,$to,$subject,$message){

		if($_SERVER['HTTP_HOST']=='localhost'){
			
			//Setting Email Preferences
			$config = array();
			$config['protocol'] 	= 'smtp';
			$config['smtp_host'] 	= 'ssl://smtp.googlemail.com';
			$config['smtp_user'] 	= 'mwsbdbackup@gmail.com';
			$config['smtp_pass'] 	= 'mywebsolutionbd1234';
			$config['smtp_port'] 	= 25;
			$config['mailtype'] 	= 'html';
			$config['charset'] 		= 'utf-8';
			$config['wordwrap'] 	= true;
			$this->email->initialize($config);

		}
		
		//send mail
		$show_message = 'no';
        //Load email library
        $this->email->from($from, $identification);
        $this->email->to($to);
        $this->email->subject($subject);
		$this->email->message($message);
		$this->email->set_newline("\r\n");
		$send_mail = $this->email->send();
		//Send mail
		
		if($show_message=='yes'){
			if($send_mail){
				$this->session->set_flashdata("email_sent","Congragulation Email Send Successfully.");
			}else{
				$this->session->set_flashdata("email_sent","You have encountered an error");
			}  
		} 
	}
	
	// save company
	public function save_company_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$gcs_data = $this->ajax_model->save_company_setup_table();
		echo json_encode($gcs_data);
	}
	
	public function save_gc_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$gcs_data = $this->ajax_model->save_gc_setup_table();
		echo json_encode($gcs_data);
	}

	public function save_grade_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$grade_s_data = $this->ajax_model->save_grade_setup_table();
		echo json_encode($grade_s_data);
	}

	public function save_contact_list_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$contact_list = $this->ajax_model->save_contact_list_table();
		echo json_encode($contact_list);
	}

	public function save_buyer_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$buyer_s_data = $this->ajax_model->save_buyer_setup_table();
		echo json_encode($buyer_s_data);
	}

	public function save_warehouse_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$warehouse_s_data = $this->ajax_model->save_warehouse_setup_table();
		echo json_encode($warehouse_s_data);
	}

	public function save_season_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$season_s_data = $this->ajax_model->save_season_setup_table();
		echo json_encode($season_s_data);
	}
	
	public function save_current_season_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$current_season_setup_data = $this->ajax_model->save_current_season_setup();
		echo json_encode($current_season_setup_data);
	}

	public function save_current_sale_no(){
    $this->web_authorization_user();
    $data = $this->data;
    $current_sale_no_data = $this->ajax_model->save_current_sale_no_data();
    echo json_encode($current_sale_no_data);
	}


	public function save_sale_schedule_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$ss_s_data = $this->ajax_model->save_sale_schedule_setup_table();
		echo json_encode($ss_s_data);
	}

	public function save_manage_page_number(){
		$this->web_authorization_user();
		$data = $this->data;
		$ss_s_data = $this->ajax_model->save_manage_page_number_table();
		echo json_encode($ss_s_data);
	}

	public function save_all_garden_avg(){
		$this->web_authorization_user();
		$data = $this->data;
		$all_garden_avg_data = $this->ajax_model->save_all_garden_avg_table();
		echo json_encode($all_garden_avg_data);
	}

	public function save_garden_letter(){
		$this->web_authorization_user();
		$data = $this->data;
		$all_garden_letter_data = $this->ajax_model->save_garden_letter_table();
		echo json_encode($all_garden_letter_data);
	}

	public function save_all_broker_buyer_purchase(){
		$this->web_authorization_user();
		$data = $this->data;
		$all_broker_buyer_purchase_data = $this->ajax_model->save_all_broker_buyer_purchase_table();
		echo json_encode($all_broker_buyer_purchase_data);
	}

	public function report_to_save_garden_avg() {
		$this->web_authorization_user();
		$result = $this->ajax_model->report_to_save_garden_avg_table();
		echo $result; // 1 or 0
	}

	public function report_to_delete_garden_avg() {
		$this->web_authorization_user();
		$result = $this->ajax_model->report_to_delete_garden_avg_table();
		echo $result; // 1 or 0
	}
	
	public function save_sale_order(){
		$this->web_authorization_user();
		$data = $this->data;
		$model_resp = $this->ajax_model->save_sale_order_table();
		echo json_encode($model_resp);
	}
	
	public function save_catalogue_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$cs_data = $this->ajax_model->save_catalogue_setup_table();
		echo json_encode($cs_data);
	}

	public function save_sample_draw_process_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$sdps_data = $this->ajax_model->save_sample_draw_process_setup_table();
		echo json_encode($sdps_data);
	}

	public function save_chest_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$chs_data = $this->ajax_model->save_chest_setup_table();
		echo json_encode($chs_data);
	}

	public function save_brokers_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$brs_data = $this->ajax_model->save_brokers_setup_table();
		echo json_encode($brs_data);
	}

	public function save_regulatory_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$res_data = $this->ajax_model->save_regulatory_setup_table();
		echo json_encode($res_data);
	}

	public function save_sale_condtion_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$sc_data = $this->ajax_model->save_sale_condtion_setup_table();
		echo json_encode($sc_data);
	}

	public function save_vat_tax_sample_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$vts_data = $this->ajax_model->save_vat_tax_sample_setup_table();
		echo json_encode($vts_data);
	}

	public function get_total_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$form_name = $this->input->post('form_name', TRUE);
		$no_of_data = $this->ajax_model->no_of_total_data($form_name);
		echo json_encode($no_of_data);
	}
	
	public function get_company_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['company_info'] = $this->ajax_model->get_company_data_table();	
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_company', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function contact_person_setup_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('super_admin/head', $data);
		$data['contact_info'] = $this->ajax_model->get_contact_person_data_table();	
		$this->load->view('website/contact_person_setup_list', $data);
		$this->load->view('super_admin/footer', $data);
	}
	
	// garden setup list
	public function get_garden_and_company_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['gc_info'] = $this->ajax_model->get_garden_and_company_table();	
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_garden_and_company', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_tea_grade_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['tg_info'] = $this->ajax_model->get_tea_grade_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_tea_grade', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_buyer_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['buyer_info'] = $this->ajax_model->get_buyer_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_buyer', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function buyer_list_print(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/buyer_list_print', $data);
	}

	public function get_warehouse_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['warehouse_info'] = $this->ajax_model->get_warehouse_data_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_warehouse', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_season_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_info'] = $this->ajax_model->get_season_data_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_season', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_salse_schedule_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['sale_s_info'] = $this->ajax_model->get_salse_schedule_data_table();
		$this->load->view('super_admin/head', 					$data);
		$this->load->view('super_admin/list_salse_schedule', 	$data);
		$this->load->view('super_admin/footer', 				$data);
	}
	
	public function get_all_garden_avg_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['bill_info'] = $this->ajax_model->get_all_garden_avg_data_table();
		$this->load->view('super_admin/list_all_garden_avg', 	$data);
	}

	public function get_all_brokers_buyer_purchase_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['bill_info'] = $this->ajax_model->get_all_brokers_buyer_purchase_data_table();
		$this->load->view('super_admin/list_all_brokers_buyer_purchase', 	$data);
	}
	
	public function garden_full_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/garden_full_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function brokers_buyer_full_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/brokers_buyer_full_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}


	public function get_catalogue_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['catalogue_info'] = $this->ajax_model->get_catalogue_data_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_catalogue', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_sample_draw_p_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['sample_draw_p_info'] = $this->ajax_model->get_sample_draw_p_data_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_sample_draw_process', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_chest_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['chest_data_info'] = $this->ajax_model->get_chest_data_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_chest', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_brokers_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['brokers_data_info'] = $this->ajax_model->get_brokers_data_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_brokers', $data);
		$this->load->view('super_admin/footer', $data);
	}

	public function get_regulatory_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['regulatory_data_info'] = $this->ajax_model->get_regulatory_data_table();
		$this->load->view('super_admin/head', $data);
		$this->load->view('super_admin/list_regulatory', $data);
		$this->load->view('super_admin/footer', $data);
	}

	// show profile
	public function get_profile_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$profile_arg = array(
			'profile_name' => $this->input->post('profile_name', TRUE),
			'profile_id' => $this->input->post('profile_id', TRUE)
		);
		$profile_data = $this->ajax_model->get_profile_data_table($profile_arg);
		echo json_encode($profile_data);
	}
	
	// get sale no :: sale order
	public function get_sale_no_admin(){
		$this->web_authorization_user();
		$data = $this->data;
		$so_sl_arg = array(
			'season' => $this->input->post('season', TRUE)
		);
		$so_sl_data = $this->ajax_model->get_sale_no_admin_table($so_sl_arg);
		echo json_encode($so_sl_data);
	}

	// super admin logout
	public function logout(){  
		// to unset a session variable
		$this->session->unset_userdata('user');
		// to destroy the whole session
		//$this->session->sess_destroy(); 
		// set flashdata session
		$this->session->set_flashdata('data_name', "Your session is closed. To get new session please login again.");
		// redirect to index
        redirect('MY_Controller/sa_login_form','refresh');  
	}  
	
	public function web_user_home(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/login_home', $data);
		$this->load->view('website/login_footer', $data);
	}

	public function web_user_add_new(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
		
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/login_add_new', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function catalogue_information(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/catalogue_information', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function offering_reports(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/offering_reports', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function company_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('company_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/company_setup', $data);
		$this->load->view('website/login_footer', $data);
	}

	public function contact_person(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('contact_person_setup');
		$data['garden_data'] = $this->ajax_model->garden_data_table();
		$data['company_data'] = $this->ajax_model->company_data_table();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/contact_person_setup', $data);
		$this->load->view('website/login_footer', $data);
	}	

	public function manage_page_number(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/manage_page_number',$data);
		$this->load->view('website/login_footer', $data);
	}

	public function all_garden_avg(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$data['brokers_data_info'] = $this->ajax_model->brokers_list_without_ubl();
		$data['garden_data'] = $this->ajax_model->garden_data_table();
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('all_garden_avg');
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/all_garden_avg',$data);
		$this->load->view('website/login_footer', $data);
	}

	public function all_broker_buyer_purchase(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$data['brokers_data_info'] = $this->ajax_model->brokers_list_without_ubl();
		$data['buyer_data'] = $this->ajax_model->get_main_buyers();
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('all_garden_avg');
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/all_broker_buyer_purchase',$data);
		$this->load->view('website/login_footer', $data);
	}
	
	// garden setup form
	public function garden_and_company_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['company_data'] = $this->ajax_model->company_data_table();
		$data['origin_data'] = $this->ajax_model->origin_data_table();
		$data['garden_data'] = $this->ajax_model->garden_data_table();
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('gc_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/garden_and_company_setup', $data);
		$this->load->view('website/login_footer', $data);
	}
	

	public function brokers_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['no_of_total_data'] = $this->ajax_model->no_of_total_data('brokers_setup');
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/brokers_setup', $data);
		$this->load->view('website/login_footer', $data);
	}
	// end
	
	// NAVBAR :: INVOICE ENTRY
	public function invoice_entry(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['s_catalogue_data'] = $this->ajax_model->s_catalogue_data_table();
			$data['chest_data'] = $this->ajax_model->chest_data_table();
			$data['wh_data'] = $this->ajax_model->wh_data_table();
			$data['garden_data'] = $this->ajax_model->get_ubl_gardens();
			$data['grade_data'] = $this->ajax_model->grade_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/login_invoice_entry', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// group edit
	public function group_invoice_entry() {
		$this->web_authorization_user();
		$data = $this->data;
		
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$ids = $this->input->get('ids', TRUE);
			$data['ids'] = $ids;
			
			// Get the invoice data for these IDs
			$this->db->select('ie.*, g.garden_name, w.short_key_word');
			$this->db->from('invoice_entry ie');
			$this->db->join('garden_and_company_setup g', 'ie.garden_id = g.id', 'left');
			$this->db->join('warehouse_setup w', 'ie.w_house_id = w.id', 'left');
			$this->db->where_in('ie.id', explode(',', $ids));
			$this->db->where('ie.soft_delete', 1);
			$query = $this->db->get();
			$data['invoice_data'] = $query->result_array();
			
			// Get all gardens and warehouses for dropdowns
			$data['garden_data'] = $this->ajax_model->get_ubl_gardens();
			$data['warehouse_data'] = $this->ajax_model->wh_data_table();
			$data['season_data'] = $this->ajax_model->season_data_table();
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/group_invoice_entry', $data);
			$this->load->view('website/login_footer', $data);
		} else {
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}

	// NAVBAR :: Pre INVOICE ENTRY
	public function pre_invoice_entry(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['wh_data'] = $this->ajax_model->wh_data_table();
			$data['chest_data'] = $this->ajax_model->chest_data_table();
			$data['garden_data'] = $this->ajax_model->garden_data_table();
			$data['grade_data'] = $this->ajax_model->grade_data_table();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['s_catalogue_data'] = $this->ajax_model->s_catalogue_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/pre_invoice_entry', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// NAVBAR :: Pre INVOICE Verify ENTRY
	public function pre_invoice_verify_entry(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$pre_invoice_cart_data_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id')
			);
			$data['garden_data'] = $this->ajax_model->garden_data_table();
			$data['grade_data'] = $this->ajax_model->grade_data_table();
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/pre_invoice_verify_entry', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// Market average entry
	public function market_average(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['brokers_list'] = $this->ajax_model->get_brokers_list_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/market_average', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// Buyers purchases entry
	public function buyers_purchases(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/buyers_purchases', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// Catalogue Header information
	public function catalogue_header_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['catalogue_header'] = $this->ajax_model->catalogue_header_setup_data();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/catalogue_header_setup', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// garden letter information
	public function garden_letter_entry(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_letter', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// PAGE SIZE SETUP
	public function page_size_setup(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/page_size_setup', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function brokers_entry(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/brokers_entry', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}
	
	public function brokers_transection(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/brokers_transection', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}
	
	public function brokers_reports(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/brokers_reports', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function buyer_ait(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['buyer_data'] = $this->ajax_model->get_main_buyers();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/buyer_ait', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function seller_statement(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['garden_data'] = $this->ajax_model->garden_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/seller_statement', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_wise_buyer_stmt(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_wise_buyer_stmt', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// Catalogue Header information
	public function save_cover_page_info(){
		$this->web_authorization_user();
		$data = $this->data;
		$cpis_data = $this->ajax_model->save_cover_page_info_table();
		echo json_encode($cpis_data);
	}
	
	public function save_catalogue_published_info(){
		$this->web_authorization_user();
		$data = $this->data;
		$cpub_data = $this->ajax_model->save_catalogue_published_info_table();
		echo json_encode($cpub_data);
	}
	
	public function save_valuation_published_info(){
		$this->web_authorization_user();
		$data = $this->data;
		$cpub_data = $this->ajax_model->save_valuation_published_info_table();
		echo json_encode($cpub_data);
	}
	
	// SAVE :: catalogue lot no. valuation
	public function save_lot_valuation(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$clv_data 	= $this->ajax_model->save_lot_valuation_table();
		echo json_encode($clv_data);
	}

	// SAVE :: All brokers average
	public function save_all_brokers_average() {
		$this->web_authorization_user();
		$data = $this->data;
		$all_brokers_average = $this->ajax_model->save_all_brokers_average_table();
		echo json_encode($all_brokers_average);
	}	
	
	// SAVE :: Market average
	public function save_market_average() {
		$this->web_authorization_user();
		$data = $this->data;
		$market_average = $this->ajax_model->save_market_average_table();
		echo json_encode($market_average);
	}	
	
	// SAVE :: Market report
	public function save_market_report() {
		$this->web_authorization_user();
		$data = $this->data;
	
		// Call model function to save content
		$save_market_report = $this->ajax_model->save_market_report_table();
	
		// Output result as JSON
		echo json_encode($save_market_report);
	}

	// SAVE :: Market report offerings
	public function save_market_offerings() {
		$this->web_authorization_user();
		$data = $this->data;
		$market_offerings = $this->ajax_model->save_market_offerings_table();
		echo json_encode($market_offerings);
	}	
	
	// SAVE :: pre-auction : save my bid
	public function save_my_bid(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$clv_data 	= $this->ajax_model->save_my_bid_table();
		echo json_encode($clv_data);
	}
	
	// SAVE :: pre-auction : save reserve price
	public function save_reserve_price(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$clv_data 	= $this->ajax_model->save_reserve_price_table();
		echo json_encode($clv_data);
	}
	
	// SAVE :: auction : save sold / wd
	public function save_sold_wd(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$clv_data 	= $this->ajax_model->save_sold_wd_table();
		echo json_encode($clv_data);
	}
	
	// SAVE :: cup testing
	public function save_cup_testing(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$clv_data 	= $this->ajax_model->save_cup_testing_table();
		echo json_encode($clv_data);
	}

	public function save_cup_testing2(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$clv_data 	= $this->ajax_model->save_cup_testing_table2();
		echo json_encode($clv_data);
	}
	
	// SAVE :: buyer bidding
	public function save_buyer_bidding(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$clv_data 	= $this->ajax_model->save_buyer_bidding_table();
		echo json_encode($clv_data);
	}

	// SAVE :: Page setup information
	public function save_page_setup_info(){
		$this->web_authorization_user(); // call function
		$data 		= $this->data;
		$psis_data 	= $this->ajax_model->save_page_setup_info_table();
		echo json_encode($psis_data);
	}

	// INVOICE ENTRY
	public function save_brokers_invoice_info(){
		$this->web_authorization_user();
		$data = $this->data;
		$brokers_inv_data = $this->ajax_model->save_brokers_invoice_info_table();
		echo json_encode($brokers_inv_data);
	}

	// In MY_Controller.php
	public function update_group_invoice_info() {
		$this->web_authorization_user();
		
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$ids = explode(',', $this->input->post('ids', TRUE));
			$garden_id = $this->input->post('garden_id', TRUE);
			$w_house_id = $this->input->post('w_house_id', TRUE);
			$invoice_group_txt = $this->input->post('invoice_group_txt', TRUE);
			$invoice_group = $this->input->post('invoice_group', TRUE);
			$invoice_group_upto = $this->input->post('invoice_group_upto', TRUE);
			$old_season = $this->input->post('old_season', TRUE);
			
			// Call the model method to handle the database operations
			$result = $this->ajax_model->update_group_invoice_info_table($ids, $garden_id, $w_house_id, $invoice_group_txt, $invoice_group, $invoice_group_upto, $old_season);
			
			echo json_encode($result);
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'Permission denied.'));
		}
	}

	// Market average entry
	public function market_average_info(){
		$this->web_authorization_user();
		$data = $this->data;
		$market_average_data = $this->ajax_model->market_average_info_table();
		echo json_encode($market_average_data);
	}
	
	// Buyer purchase entry
	public function buyers_purchases_info(){
		$this->web_authorization_user();
		$data = $this->data;
		$buyers_purchases_data = $this->ajax_model->buyers_purchases_info_table();
		echo json_encode($buyers_purchases_data);
	}
	
	// Pre INVOICE ENTRY
	public function save_pre_invoice_entry_info(){
		$this->web_authorization_user();
		$data = $this->data;
		$brokers_pre_inv_data = $this->ajax_model->save_pre_invoice_entry_info_table();
		echo json_encode($brokers_pre_inv_data);
	}

	// save Pre invoice final
	public function pre_invoice_entry_final(){
		$this->web_authorization_user();
		$data = $this->data;
		$inv_data = $this->ajax_model->pre_invoice_entry_final_table();
		echo json_encode($inv_data);
	}
	//warehouse_charge_update
	public function update_warehouse_charge() {
		$this->web_authorization_user(); // call function
		$data = $this->ajax_model->update_warehouse_charge_data();
		echo json_encode($data);
	}

	//multiple_charge_update
	public function update_garden_charge() {
		$this->web_authorization_user(); // call function
		$data = $this->ajax_model->update_garden_charge_data();
		echo json_encode($data);
	}

	// Update garden average field
	public function update_garden_avg_field() {
		$this->web_authorization_user(); // call function
		$data = $this->ajax_model->update_garden_avg_field_data();
		echo json_encode($data);
	}

	// Update brokers buyers purchase field
	public function update_brokers_buyer_pur_field() {
		$this->web_authorization_user(); // call function
		$data = $this->ajax_model->update_brokers_buyer_pur_field_data();
		echo json_encode($data);
	}
	// REPORT :: get page size data
	public function get_page_size_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$pn_arg = array(
				'page_name' => $this->input->post('page_name', 	TRUE)
			);
			$psize_data = $this->ajax_model->get_psize_data_table($pn_arg);
			echo json_encode($psize_data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// REPORT :: get sample kgs data
	public function get_sample_kgs_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$sdata_arg = array(
			'CatalogName' => $this->input->post('CatalogName', TRUE)
		);
		$sample_kgs_data = $this->ajax_model->get_sample_kgs_data_table($sdata_arg);
		echo json_encode($sample_kgs_data);
	}

	public function get_lot_wise_catalogue(){
		$this->web_authorization_user();
		$data = $this->data;
		$data_lot_no = array(
			'get_lot_no' => $this->input->post('get_lot_no', TRUE)
		);
		$lot_wise_catalogue = $this->ajax_model->get_lot_wise_catalogue_table($data_lot_no);
		echo json_encode($lot_wise_catalogue);
	}

	// get sample kgs data pre invoice parking
	public function get_sample_kgs_data_pre_invoice(){
		$this->web_authorization_user();
		$data = $this->data;
		$sdata_arg = array(
			'catalouge_name' => $this->input->post('catalouge_name', TRUE),
			'pre_invoice_id' => $this->input->post('get_pre_invoice_id', TRUE)
		);
		$sample_kgs_data = $this->ajax_model->get_sample_kgs_data_table_pre_invoice($sdata_arg);
		echo json_encode($sample_kgs_data);
	}

	// get - E-auction sample kgs
	public function get_sample_kgs_data_eauction(){
		$this->web_authorization_user();
		$data = $this->data;
		$sdata_arg = array(
			'e_auction_cat' => $this->input->post('e_auction_cat', TRUE)
		);
		$sample_kgs_data = $this->ajax_model->get_sample_kgs_data_eauction_table($sdata_arg);
		echo json_encode($sample_kgs_data);
	}


	// get - E-auction sample kgs pre invoice parking
	public function get_sample_kgs_data_eauction2(){
		$this->web_authorization_user();
		$data = $this->data;
		$sdata_arg = array(
			'supplement_tea' => $this->input->post('supplement_tea', TRUE),
			'pre_invoice_id' => $this->input->post('get_pre_invoice_id', TRUE)
		);
		$sample_kgs_data = $this->ajax_model->get_sample_kgs_data_eauction_table2($sdata_arg);
		echo json_encode($sample_kgs_data);
	}

	// REPORT :: get Catalogue & S.Catalogue data
	public function get_save_invoice_verify_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$sverify_data_arg = array(
			'row_id' => $this->input->post('row_id', TRUE),
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE),
			'lot_no' => $this->input->post('lot_no', TRUE),
			'group_no' => $this->input->post('group_no', TRUE),
			'catalouge_name' => $this->input->post('catalouge_name', TRUE),
			'supplement_tea' => $this->input->post('supplement_tea', TRUE),
			'e_auc' => $this->input->post('e_auc', TRUE),
			'category' => $this->input->post('category', TRUE),
			'total_kgs' => $this->input->post('total_kgs', TRUE),
			'sample_kgs' => $this->input->post('sample_kgs', TRUE),
			'net_kgs' => $this->input->post('net_kgs', TRUE),
			'sale_date' => $this->input->post('sale_date', TRUE),
			'old_season' => $this->input->post('old_season', TRUE),
			'invoice_group_txt' => $this->input->post('invoice_group_txt', TRUE),
			'invoice_group' => $this->input->post('invoice_group', TRUE),
			'invoice_group_upto' => $this->input->post('invoice_group_upto', TRUE)
		);
		$save_invoice_verify_data = $this->ajax_model->get_save_invoice_verify_table($sverify_data_arg);
		echo json_encode($save_invoice_verify_data);
	}

	// save invoice entry and update pre invoice entry
	public function get_save_pre_invoice_verify_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$sverify_data_arg = array(
			'row_id' => $this->input->post('row_id', TRUE),
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE),
			'lot_no' => $this->input->post('lot_no', TRUE),
			'group_no' => $this->input->post('group_no', TRUE),
			'catalouge_name' => $this->input->post('catalouge_name', TRUE),
			'supplement_tea' => $this->input->post('supplement_tea', TRUE),
			'e_auc' => $this->input->post('e_auc', TRUE),
			'category' => $this->input->post('category', TRUE),
			'total_kgs' => $this->input->post('total_kgs', TRUE),
			'sample_kgs' => $this->input->post('sample_kgs', TRUE),
			'net_kgs' => $this->input->post('net_kgs', TRUE),
			'sale_date' => $this->input->post('sale_date', TRUE),
			'old_season' => $this->input->post('old_season', TRUE),
			'user' => $this->input->post('user', TRUE),
			'last_update' => $this->input->post('last_update', TRUE),
			'garden_id' => $this->input->post('garden_id', TRUE),
			'invoice_group_txt' => $this->input->post('invoice_group_txt', TRUE),
			'invoice_group' => $this->input->post('invoice_group', TRUE),
			'invoice_group_upto' => $this->input->post('invoice_group_upto', TRUE),
			'w_inv_no' => $this->input->post('w_inv_no', TRUE),
			'grade_id' => $this->input->post('grade_id', TRUE),
			'additional_grade' => $this->input->post('additional_grade', TRUE),
			'w_house_id' => $this->input->post('w_house_id', TRUE),
			'chest_number' => $this->input->post('chest_number', TRUE),
			'gross_wt_per_bag' => $this->input->post('gross_wt_per_bag', TRUE),
			'net_wt_per_bag' => $this->input->post('net_wt_per_bag', TRUE),
			'chest_name' => $this->input->post('chest_name', TRUE),
			'old_season2' => $this->input->post('old_season2', TRUE),
			'ol_ac' => $this->input->post('ol_ac', TRUE),
			'old_sale_no' => $this->input->post('old_sale_no', TRUE),
			'old_lot_no' => $this->input->post('old_lot_no', TRUE),
			'account_concerned' => $this->input->post('account_concerned', TRUE)
		);
		$save_pre_invoice_verify_data = $this->ajax_model->get_save_pre_invoice_verify_table($sverify_data_arg);
		echo json_encode($save_pre_invoice_verify_data);
	}

	// Invoice entry :: get old sale + lot no data
	public function get_old_sale_lot_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$old_sl_data_arg = array(
				'season' => $this->input->post('season', TRUE),
				'old_sale_no' => $this->input->post('old_sale_no', TRUE),
				'ol_ac' => $this->input->post('ol_ac', TRUE),
				'old_lot_no' => $this->input->post('old_lot_no', TRUE),
				'user' => $this->input->post('user', TRUE)
			);
			$old_sl_data = $this->ajax_model->get_old_sale_lot_data_table($old_sl_data_arg);
			echo json_encode($old_sl_data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function get_lot_info_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$lot_data_arg = array(
				'lot_id' => $this->input->post('lot_id', TRUE)
			);
			$lot_data = $this->ajax_model->get_lot_info_data_table($lot_data_arg);
			echo json_encode($lot_data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}
	
	// sale lot info details
	public function sale_lot_info_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'lot_id' => $this->input->post('lot_id', TRUE)
			);
			$lot_data = $this->ajax_model->sale_lot_info_table($parameters);
			echo json_encode($lot_data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}
	
	// bulk sale lot info details
	public function sale_lot_info_data2(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'lot_id' => $this->input->post('lot_id', TRUE)
			);
			$lot_data = $this->ajax_model->sale_lot_info_table2($parameters);
			echo json_encode($lot_data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}


	// sale lot parking data
	public function sale_lot_parking_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'sale_no' => $this->input->post('sale_no', TRUE),
				'lot_no' => $this->input->post('lot_no', TRUE)
			);
			$lot_data = $this->ajax_model->sale_lot_parking_table($parameters);
			echo json_encode($lot_data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}

	// bulk sale lot parking data
	public function sale_lot_parking_data2(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'sale_no' => $this->input->post('sale_no', TRUE),
				'lot_no' => $this->input->post('lot_no', TRUE),
				'row_id' => $this->input->post('row_id', TRUE)
			);
			$lot_data = $this->ajax_model->sale_lot_parking_table2($parameters);
			echo json_encode($lot_data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}

	// pre invoice data
	public function search_pre_invoice_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'pre_invoice_id' => $this->input->post('pre_invoice_id', TRUE)
			);
			$pre_inv_data = $this->ajax_model->search_pre_inv_details_table($parameters);
			echo json_encode($pre_inv_data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}

	// REPORT :: get sale date data
	public function get_sale_date_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$sn_arg = array(
			'auction_center' => $this->input->post('auction_center', TRUE),
			'season'  => $this->input->post('season', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE)
		);
		$sn_data = $this->ajax_model->get_sale_date_data_table($sn_arg);
		echo json_encode($sn_data);
	}

	// In your MY_Controller - update get_garden_letter_data method
	public function get_garden_letter_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$gl_arg = array(
			'auction_center' => $this->input->post('auction_center', TRUE),
			'season' => $this->input->post('season', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE)
		);
		$gl_data = $this->ajax_model->get_garden_letter_data_table($gl_arg);
		echo json_encode($gl_data);
	}

	// sale date return Invoice Parking
	public function get_sale_date_data_invoice_parking(){
		$this->web_authorization_user();
		$data = $this->data;
		$sn_arg = array(
			'auction_center' => $this->input->post('auction_center', TRUE),
			'season'  => $this->input->post('season', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE),
			'pre_invoice_id' => $this->input->post('get_pre_invoice_id', TRUE)
		);
		$sn_data = $this->ajax_model->get_sale_date_data_table_invoice_parking($sn_arg);
		echo json_encode($sn_data);
	}
	
	// REPORT :: group no data
	public function get_group_no_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
		
			$group_no_arg = array(
				'user' 		=> $this->session->userdata('ubl_web_user_id'),
				'season' 	=> $this->input->post('season', TRUE),
				'sale_no' 	=> $this->input->post('sale_no', TRUE)
			);
			$group_no = $this->ajax_model->get_group_no_data_table($group_no_arg);
			echo json_encode($group_no);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// group no data pre invoice parking
	public function get_group_no_data2(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
		
			$group_no_arg = array(
				'user' 		=> $this->session->userdata('ubl_web_user_id'),
				'season' 	=> $this->input->post('season', TRUE),
				'sale_no' 	=> $this->input->post('sale_no', TRUE),
				'pre_invoice_id' => $this->input->post('get_pre_invoice_id', TRUE)
			);
			$group_no = $this->ajax_model->get_group_no_data_table2($group_no_arg);
			echo json_encode($group_no);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function get_invoice_entry_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$invoice_cart_data_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id')
			);
			$data['resultq1'] = $this->ajax_model->get_invoice_group_data_table($invoice_cart_data_arg);
			$this->load->view('website/login_invoice_cart_list', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function get_pre_invoice_entry_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$pre_invoice_cart_data_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id')
			);
			$data['resultq1'] = $this->ajax_model->get_pre_invoice_group_data_table($pre_invoice_cart_data_arg);
			$this->load->view('website/pre_invoice_entry_cart', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function get_market_average_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$market_average_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id')
			);
			$data['market_average'] = $this->ajax_model->market_average_data_table($market_average_arg);
			$this->load->view('website/market_average_cart.php', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function get_buyers_purchases_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$buyers_purchases_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id')
			);
			$data['buyers_purchases'] = $this->ajax_model->buyers_purchases_data_table($buyers_purchases_arg);
			$this->load->view('website/buyers_purchases_cart.php', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function get_invoice_verify_list_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['s_catalogue_data'] = $this->ajax_model->s_catalogue_data_table();
			$this->load->view('website/pre_invoice_verify_data', $data);
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	

	// bulk lot list
	public function bulk_lot_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			// page load
			$this->load->view('website/bulk_lot_list', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// bulk lot list
	public function bulk_lot_list2(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			// page load
			$this->load->view('website/bulk_lot_list2', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// create user
	public function create_user(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/create_user', $data);
		$this->load->view('website/login_footer', $data);
	}

	// list of user
	public function user_list(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/user_list', $data);
	}

	// Change Password Page
public function change_password_page(){
    $this->web_authorization_user();
    $data = $this->data; // This already has ubl_user_name from session
    $data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
    
    $this->load->view('website/login_head', $data);
    $this->load->view('website/login_nav', $data);
    $this->load->view('website/change_password', $data);
    $this->load->view('website/login_footer', $data);
}
	// auction schedule
	public function auction_schedule_report(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/auction_schedule_report', $data);
		$this->load->view('website/login_footer', $data);
	}

	public function print_brokers_wise_offering(){
		$this->web_authorization_user();
		$data = $this->data;
			
		$parameters = array(
			'season' 	=> $this->input->post('season'),
			'sale_no' 	=> $this->input->post('sale_no')
		);
		$data['resultq1'] = $this->ajax_model->print_brokers_wise_offering_db($parameters);
		// page load
		$this->load->view('website/login_head', $data);
		$this->load->view('website/print_brokers_wise_offering', $data);
			
	}
	
	public function print_summary_of_sale_offering(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/login_head', $data);
		$this->load->view('website/print_summary_of_sale_offering', $data);
	}
	
	public function print_offering_vs_sales(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/login_head', $data);
		$this->load->view('website/print_offering_vs_sales', $data);
	}
	
	public function print_auction_schedule_table(){
		$this->web_authorization_user();
		$data = $this->data;
		$parameters = array(
			'season_name' => $this->input->post('season_name'),
			'auction_center' => $this->input->post('auction_center')
		);
		$data['resultq1'] = $this->ajax_model->auction_schedule_table_db($parameters);
		$this->load->view('website/login_head', $data);
		$this->load->view('website/print_auction_schedule_table', $data);
	}

	public function print_auction_schedule_table_with_position(){
		$this->web_authorization_user();
		$data = $this->data;
		$parameters = array(
			'season_name' => $this->input->post('season_name'),
			'auction_center' => $this->input->post('auction_center')
		);
		$data['resultq1'] = $this->ajax_model->auction_schedule_table_db($parameters);
		$this->load->view('website/login_head', $data);
		$this->load->view('website/print_auction_schedule_table_with_position', $data);
	}
	
	public function garden_wise_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/garden_wise_report_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function sample_drawn_stmt_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/sample_drawn_stmt_report_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function warehouse_wise_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/warehouse_wise_report_list', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function grade_wise_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/grade_wise_report_list', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_sales_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/garden_sales_report_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function all_garden_buyer_sale_to_sale_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/all_garden_buyer_sale_to_sale_report', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function gs_report_list_all_center(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/gs_report_list_all_center', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function wh_sales_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/wh_sales_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function sellers_stmt_sale_to_sale(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/sellers_stmt_sale_to_sale', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function wh_single_sales_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/wh_single_sales_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function warehouse_rent_statement(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/warehouse_rent_statement', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_sales_avg_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/garden_sales_avg_report_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function bill_register_sale_to_sale(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/bill_register_sale_to_sale', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_sales_top_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/garden_sales_top_list', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_unsold_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/garden_unsold_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_wd_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/garden_wd_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_unsold_details(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/garden_unsold_details', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_wd_details(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/login_head', $data);
			$this->load->view('website/garden_wd_details', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function catalogue_wise_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season'),
				'sale_no' => $this->input->post('sale_no'),
				'catalogue_name' => $this->input->post('catalouge_name')
			);
			$data['resultq1'] = $this->ajax_model->catalogue_wise_report_list_table($parameters);
			$this->load->view('website/login_head', $data);
			$this->load->view('website/catalogue_wise_report_list', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// catalogue prepared data
	public function get_catalogue_prepared_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			// send model data
			$catp_data_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'catalouge_name' => $this->input->post('catalouge_name', TRUE),
				'sale_no' => $this->input->post('sale_no', TRUE),
				'prepared_to' => $this->input->post('prepared_to', TRUE),
				'page_name' => $this->input->post('prepared_to', TRUE)
			);
			
			if($this->input->post('prepared_to', TRUE) == 'Catalogue'){
				
				// model load
				$data['resultq1'] = $this->ajax_model->get_catalogue_prepared_data_table($catp_data_arg);
				$data['vat_ait_sample_info'] = $this->ajax_model->catalogue_header_setup_data($catp_data_arg);
				$data['psize_data'] = $this->ajax_model->get_psize_data_table($catp_data_arg);
				$data['how_many_page'] = $this->ajax_model->get_how_many_page_table($catp_data_arg);
				
				// page load
				$this->load->view('website/login_head', $data);
				$this->load->view('website/page_size_declare', $data);
				$this->load->view('website/login_catalogue_prepared', $data);		
				
			}else if($this->input->post('prepared_to', TRUE) == 'Catalogue Print'){
				
				// model load
				$data['resultq1'] = $this->ajax_model->get_catalogue_print_data_table($catp_data_arg);
				$data['vat_ait_sample_info'] = $this->ajax_model->catalogue_header_setup_data($catp_data_arg);
				
				// new array value assign
				$catp_data_arg = array(
					'user' => $this->session->userdata('ubl_web_user_id'),
					'page_name' => 'Catalogue'
				);
				$data['psize_data'] = $this->ajax_model->get_psize_data_table($catp_data_arg);
				
				// page load
				$this->load->view('website/login_head', $data);
				$this->load->view('website/page_size_declare', $data);
				$this->load->view('website/login_catalogue_print', $data);		
				
			}else if($this->input->get('prepared_to', TRUE) == 'Valuation'){
				
				// page load
				$this->load->view('website/login_valuation_prepared', $data);
				
			}else if($this->input->get('prepared_to', TRUE) == 'Valuation Print'){
				
				// page load
				$this->load->view('website/login_valuation_prepared_print', $data);
				
			}else if($this->input->get('prepared_to', TRUE) == 'Tea cup testing'){
				
				// model load
				$parameters = array(
					'user' => $this->session->userdata('ubl_web_user_id'),
					'season' => $this->input->get('season', TRUE),
					'catalouge_name' => $this->input->get('catalouge_name', TRUE),
					'sale_no' => $this->input->get('sale_no', TRUE),
					'garden_id' => $this->input->get('garden_id', TRUE),
					'prepared_to' => $this->input->get('prepared_to', TRUE),
					'page_name' => $this->input->get('prepared_to', TRUE)
				);
				$data['cup_testing_data'] = $this->ajax_model->get_tea_cup_testing_data_table($parameters);
				$data['cup_testing_data2'] = $this->ajax_model->get_tea_cup_testing_data_table2($parameters);
				$this->load->view('website/login_head', $data);
				$this->load->view('website/login_nav', $data);
				$this->load->view('website/login_tea_cup_testing_prepared', $data);
				$this->load->view('website/login_footer', $data);
				
			}else if($this->input->post('prepared_to', TRUE) == 'Index'){
				
				$data['resultq1'] = $this->ajax_model->get_index_prepared_data_table($catp_data_arg);
				$data['psize_data'] = $this->ajax_model->get_psize_data_table($catp_data_arg);
				$this->load->view('website/login_head', $data);
				$this->load->view('website/page_size_declare', $data);
				$this->load->view('website/login_index_prepared', $data);
				
			}else{
				
				$data = array(
					'heading' => 'An Error Was Encountered',
					'message' => "<p>Server cannot find resource you are looking for. Please search again.</p>"
				);
				$this->load->view('website/login_head', $data);
				$this->load->view('errors/html/error_404', $data);
			}
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function print_published_catalogue(){
		
		//$this->web_authorization_user();
		$data = $this->data;
		$data['brokers_id'] = $this->input->post('brokers', TRUE);
		$published_data_arg = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE),
			'brokers' => $this->input->post('brokers', TRUE),
			'user' => $this->input->post('brokers', TRUE),
			'catalogue' => $this->input->post('catalogue', TRUE),
		);
		$data['brokers_info'] = $this->ajax_model->get_single_brokers_data_table($published_data_arg);
		$data['cover_page_result'] = $this->ajax_model->get_cover_page_published_data_table($published_data_arg);
		$data['sale_schedule_data'] = $this->ajax_model->get_sale_schedule_data_table($published_data_arg);
		$data['index_result'] = $this->ajax_model->get_index_published_data_table($published_data_arg);
		$data['valuation_result'] = $this->ajax_model->get_valuation_published_data_table($published_data_arg);
		$data['catalogue_result'] = $this->ajax_model->get_catalogue_published_data_table($published_data_arg);
		$data['so_list_data'] = $this->ajax_model->get_sale_order_data_table($published_data_arg);
		$data['vat_ait_sample_info'] = $this->ajax_model->vat_ait_sample_info_data($published_data_arg);
		
		//view page
		$this->load->view('website/number_range', $data);
		$this->load->view('website/print_published_catalogue', $data);
	}
	public function print_published_catalogue_tc(){
		
		//$this->web_authorization_user();
		$data = $this->data;
		$data['brokers_id'] = $this->input->post('brokers', TRUE);
		$published_data_arg = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE),
			'brokers' => $this->input->post('brokers', TRUE),
			'user' => $this->input->post('brokers', TRUE),
			'catalogue' => $this->input->post('catalogue', TRUE),
		);
		$data['brokers_info'] = $this->ajax_model->get_single_brokers_data_table($published_data_arg);
		$data['cover_page_result'] = $this->ajax_model->get_cover_page_published_data_table($published_data_arg);
		$data['sale_schedule_data'] = $this->ajax_model->get_sale_schedule_data_table($published_data_arg);
		$data['index_result'] = $this->ajax_model->get_index_published_data_table($published_data_arg);
		$data['valuation_result'] = $this->ajax_model->get_valuation_published_data_table($published_data_arg);
		$data['catalogue_result'] = $this->ajax_model->get_catalogue_published_data_table($published_data_arg);
		$data['so_list_data'] = $this->ajax_model->get_sale_order_data_table($published_data_arg);
		$data['vat_ait_sample_info'] = $this->ajax_model->vat_ait_sample_info_data($published_data_arg);
		
		//view page
		$this->load->view('website/number_range', $data);
		$this->load->view('website/print_published_catalogue_tc', $data);
	}

	

	// Assign page numbers to lot
	public function set_catalogue_index() {
		$this->web_authorization_user(); // Ensure authorized access
		$data = $this->data;
	
		// Get sanitized POST data
		$parameters = array(
			'page' => $this->input->post('page', TRUE),
			'lotArray' => $this->input->post('lotArray', TRUE)
		);
	
		// Call model to update DB
		$result = $this->ajax_model->set_catalogue_index_table($parameters);
	
		// Respond with JSON
		echo json_encode(['success' => $result]);
	}	
	
	// page generate test purpose
	public function published_catalogue_test_page(){
		$this->load->view('website/published_catalogue_test_page');
	}

	public function get_cover_page_prepared_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			// send model data
			$catp_data_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season', TRUE),
				'catalouge_name' => $this->input->post('catalouge_name', TRUE),
				'sale_no' => $this->input->post('sale_no', TRUE),
				'prepared_to' => $this->input->post('prepared_to', TRUE),
				'page_name' => $this->input->post('prepared_to', TRUE)
			);
			// get model	
			$data['resultq1'] = $this->ajax_model->get_cover_page_prepared_data_table($catp_data_arg);
			$data['psize_data'] = $this->ajax_model->get_psize_data_table($catp_data_arg);
			$data['so_list_data'] = $this->ajax_model->get_sale_order_data_table($catp_data_arg);
			$data['sale_schedule_data'] = $this->ajax_model->get_sale_schedule_data_table($catp_data_arg);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('website/page_size_declare', $data);
			$this->load->view('website/cover_page_prepared', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function get_sale_no_cp_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$cp_sl_arg = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'catalouge_name' => $this->input->post('catalouge_name', TRUE)
			);
			$cp_sl_data = $this->ajax_model->get_sale_no_cp_data_table($cp_sl_arg);
			echo json_encode($cp_sl_data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function get_universal_sale_no_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$unv_sale_no = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE)
		);
		$unv_sale_no_data = $this->ajax_model->get_universal_sale_no_data_table($unv_sale_no);
		echo json_encode($unv_sale_no_data);
	}

	public function bulk_sale_entry_sale_no_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$unv_sale_no = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE)
		);
		$unv_sale_no_data = $this->ajax_model->bulk_sale_entry_sale_no_data_table($unv_sale_no);
		echo json_encode($unv_sale_no_data);
	}
	
	public function get_invoice_entry_sale_no_data(){
    $this->web_authorization_user();
    $data = $this->data;
    
    // Get edit_sale_no if passed
    $edit_sale_no = $this->input->post('edit_sale_no', TRUE);
    $edit_sale_no = (!empty($edit_sale_no)) ? $edit_sale_no : null;
    
    $cp_sl_arg = array(
        'season' => $this->input->post('season', TRUE),
        'auction_center' => $this->input->post('auction_center', TRUE)
    );
    $cp_sl_data = $this->ajax_model->get_invoice_entry_sale_no_data_table($cp_sl_arg, $edit_sale_no);
    echo json_encode($cp_sl_data);
}

	public function get_gl_entry_sale_no_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$cp_sl_arg = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE)
		);
		$cp_sl_data = $this->ajax_model->get_gl_entry_sale_no_data_table($cp_sl_arg);
		echo json_encode($cp_sl_data);
	}

	public function get_modal_entry_sale_no_data() {
		$this->web_authorization_user();
		
		$cp_sl_arg = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE)
		);

		$cp_sl_data = $this->ajax_model->get_modal_entry_sale_no_data_table($cp_sl_arg);

		// Return HTML directly - make sure it's properly formatted
		echo $cp_sl_data;
		exit; // Add exit to prevent any additional output
	}

	
	public function get_garden_list_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'sale_no' => $this->input->post('sale_no', TRUE)
			);
			$garden_list = $this->ajax_model->get_garden_list_data_table($parameters);
			echo json_encode($garden_list);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function get_garden_list_sale_to_sale_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'sale_from' => $this->input->post('sale_from', TRUE),
				'sale_to' => $this->input->post('sale_to', TRUE)
			);
			$garden_list = $this->ajax_model->get_garden_list_sale_to_sale_data_table($parameters);
			echo json_encode($garden_list);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function get_warehouse_list_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'sale_no' => $this->input->post('sale_no', TRUE)
			);
			$warehouse_list = $this->ajax_model->get_warehouse_list_data_table($parameters);
			echo json_encode($warehouse_list);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function get_warehouse_list_sale_to_sale_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$parameters = array(
				'user' => $this->session->userdata('ubl_web_user_id'),
				'season' => $this->input->post('season', TRUE),
				'auction_center' => $this->input->post('auction_center', TRUE),
				'sale_no' => $this->input->post('sale_no', TRUE),
				'sale_to' => $this->input->post('sale_to', TRUE)
			);
			$warehouse_list = $this->ajax_model->get_warehouse_list_sale_to_sale_data_table($parameters);
			echo json_encode($warehouse_list);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function get_page_lock_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$page_lock_arg = array('lot_id' => $this->input->post('lot_id', TRUE));
			$plock_data = $this->ajax_model->get_page_lock_data_table($page_lock_arg);
			echo json_encode($plock_data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function pre_catalogue(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['s_catalogue_data'] = $this->ajax_model->s_catalogue_data_table();
			$data['garden_data'] = $this->ajax_model->garden_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/pre_catalogue', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => "An Error Was Encountered",
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function cover_page(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			// load model
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			
			// load view
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/cover_page', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}
	
	public function catalogue_publish(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/catalogue_publish', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}
	
	public function valuation_publish(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/valuation_publish', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}		
	}
	
	// universal function
	public function list_of_catalogue(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['brokers_data'] = $this->ajax_model->brokers_data_table();
		$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/list_of_catalogue', $data);
		$this->load->view('website/login_footer', $data);	
	}

	// Pre-Auction - Buyer
	public function buyer_pre_auction_table(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Buyer'){
			
			// get catalogue data
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/buyer_pre_auction_table', $data);
			$this->load->view('website/login_footer', $data);	
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	// Pre-Auction - Brokers
	public function brokers_pre_auction_table(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			// get catalogue data
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/brokers_pre_auction_table', $data);
			$this->load->view('website/login_footer', $data);	
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// Auction - Brokers
	public function brokers_auction_table(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			// get catalogue data
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/brokers_auction_table', $data);
			$this->load->view('website/login_footer', $data);	
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function cat_index_page(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			// get season data
			$data['season_data'] = $this->ajax_model->season_data_table();
			// get catalogue data
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/cat_index_page', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
		
	}
	
	public function valuation_page(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/valuation_page', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	//  All brokers average entry searching
	public function all_brokers_average(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['brokers_data_info'] = $this->ajax_model->get_brokers_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/brokers_average_entry', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	//  Market report entry searching
	public function market_report_ent(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/market_report_ent', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	//  Market report searching
	public function market_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/market_report', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function market_report_view(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/market_report_view', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function crop_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/crop_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function grade_wise_top_price_realized(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/grade_wise_top_price_realized', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function grade_wise_garden_avg(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/grade_wise_garden_avg', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function all_garden_avg_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/all_garden_avg_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	//  All brokers average entry
	public function all_brokers_average_entry_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
		
			// page load
			$this->load->view('website/all_brokers_average_entry_list', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	//  Market Report entry
	public function market_report_entry(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
		
			// page load
			$this->load->view('website/market_report_entry', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	//  Market Average entry
	public function market_average_entry(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
		
			// page load
			$this->load->view('website/market_average_entry', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function valuation_entry(){
		$this->valuation_page();
	}
	
	public function tea_cup_testing(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/tea_cup_testing', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// catalogue page
	public function catalogue_page(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/catalogue_page', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// load view
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function catalogue_lock(){
		$this->catalogue_page();
	}
	
	public function garden_wise_offering(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['company_data'] = $this->ajax_model->company_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_wise_offering', $data);
			$this->load->view('website/login_footer', $data);
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function sample_drawn_stmt(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['company_info'] = $this->ajax_model->get_company_data_table();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/sample_drawn_stmt', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function warehouse_wise_offering(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/warehouse_wise_offering', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function grade_offering(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/grade_offering', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_sales(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_sales', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_single_sales(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_single_sales', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function garden_single_sales_avg_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/garden_single_sales_avg_report_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function company_single_sales_avg_report_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/company_single_sales_avg_report_list', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_sales_avg(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_sales_avg', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_unsold(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_unsold', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function garden_w_d(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_w_d', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function catalogue_wise_offering(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/catalogue_wise_offering', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function brokers_wise_offering(){
		$this->web_authorization_user();
		$data = $this->data;

		$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
		$data['season_data'] = $this->ajax_model->season_data_table();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/brokers_wise_offering', $data);
		$this->load->view('website/login_footer', $data);
	}
	
	public function summary_of_offering(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/summary_of_offering', $data);
		$this->load->view('website/login_footer', $data);
	}
	
	public function out_lot_stmt_offering(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$data['garden_data'] = $this->ajax_model->garden_data_table();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/out_lot_stmt_offering', $data);
		$this->load->view('website/login_footer', $data);
	}
	
	public function print_out_lot_stmt(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/login_head', $data);
		$this->load->view('website/print_out_lot_stmt', $data);
	}

	public function unprinted_invoice_stmt(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/login_head', $data);
		$this->load->view('website/unprinted_invoice_stmt', $data);
	}
	
	public function garden_unprinted_invoice_stmt(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/login_head', $data);
		$this->load->view('website/garden_unprinted_invoice_stmt', $data);
	}
	
	public function offering_vs_sales(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
		$data['current_season_data'] = $this->ajax_model->current_season_data();
		$data['season_data'] = $this->ajax_model->season_data_table();
		$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/login_nav', $data);
		$this->load->view('website/offering_vs_sales', $data);
		$this->load->view('website/login_footer', $data);
	}
	
	public function sale_order_time_setup(){
		$this->web_authorization_user(); // call function
		$data = $this->data;
		
		if($this->session->userdata('ubl_user_type')=='TTAB CTG'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			// get season data
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/sale_order_time_setup', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	// web user profile picture upload
	public function wu_pro_pic_upload(){
	   $data = $this->data;
	   if(isset($_FILES["wu_image_file"]["name"])){
		   /* Conf */
			$config['file_name']        = $data['ubl_web_user_id'];
			$config['upload_path'] 		= './assets/img/'.$data['ubl_user_type'].'/';  
			$config['allowed_types'] 	= 'jpg'; // |jpeg|png|gif 
			$config['max_size']         = '2000'; // kilobytes
			$config['max_width']        = '';
			$config['max_height']       = '';
			$config['file_ext_tolower'] = TRUE;
			$config['overwrite'] 		= TRUE;	// if a file with the same name as the one you are uploading exists, it will be overwritten.
			
			/* Load the upload library */
			$this->load->library('upload', $config);  
			if(!$this->upload->do_upload('wu_image_file')){ // form field name
				echo $this->upload->display_errors();  
			}else{
				echo 'success';  
			}  
	   }  
	}  
	  
	// LOGOUT :: web user logout
	public function web_user_logout(){
		$data = $this->data;
		// insert logout log data
		$data_log = array(
			'user_id' 	=> $data['ubl_web_user_id'], 
			'user_type' => $data['ubl_user_type'],
			'datetime' 	=> date("Y-m-d H:i:s"),
			'ip' 		=> $data['ip'],
			'login_in'	=> 'logout'
		);
		$log_insert = $this->db->insert('user_log',$data_log);
		// end log data insert

		// to unset a session variable
		$this->session->unset_userdata('ubl_web_user_id');

		// to destroy the whole session
		//$this->session->sess_destroy(); 

		// set flashdata session
		$this->session->set_flashdata('web_data_name', "Your session is closed. To get new session please login again.");

		// redirect to index
        redirect('MY_Controller/index','refresh');  
	}  
	
	// pre catalogue sale no
	public function pre_catalogue_sale_no_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE)
		);
		$sale_no = $this->ajax_model->pre_catalogue_sale_no_data_table($parameters);
		echo json_encode($sale_no);
	}
	
	// pre valuation sale no
	public function pre_valuation_sale_no_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE)
		);
		$sale_no = $this->ajax_model->pre_valuation_sale_no_data_table($parameters);
		echo json_encode($sale_no);
	}
	
	// pre catalogue data
	public function get_pre_catalogue_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/get_pre_catalogue_data', $data);	
	}
	
	public function sale_entry(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['buyer_data'] = $this->ajax_model->get_buyer_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/sale_entry', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => "An Error Was Encountered",
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}

	public function bulk_sale_entry(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['season_data'] = $this->ajax_model->season_data_table();
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/bulk_sale_entry', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => "An Error Was Encountered",
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function get_sale_entry_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$data['buyer_data'] = $this->ajax_model->get_buyer_table();
		$this->load->view('website/login_head', $data);
		$this->load->view('website/get_sale_entry_data', $data);	
	}
	
	// save sale entry data
	public function sale_entry_save_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$clv_data = $this->ajax_model->sale_entry_save_data_table();
		echo json_encode($clv_data);
	}
	
	public function buyer_contract(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/buyer_contract', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function after_sale_sale_no_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center")
		);
		$sale_no = $this->ajax_model->after_sale_sale_no_data_table($parameters);
		echo json_encode($sale_no);
	}
	
	public function contract_buyer_list_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE)
		);
		$buyer_list = $this->ajax_model->contract_buyer_list_data_table($parameters);
		echo json_encode($buyer_list);
	}
	
	public function ait_buyer_list_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post('auction_center', TRUE),
			'sale_from' => $this->input->post('sale_from', TRUE),
			'sale_to' => $this->input->post('sale_to', TRUE)
		);
		$buyer_list = $this->ajax_model->ait_buyer_list_data_table($parameters);
		echo json_encode($buyer_list);
	}
	
	public function get_buyer_list_data(){
	$this->web_authorization_user();
	$parameters = array(
		'auction_center' => $this->input->post('auction_center', TRUE),
		'season_from' => $this->input->post('season_from', TRUE),
		'season_to' => $this->input->post('season_to', TRUE),
		'sale_from' => $this->input->post('sale_from', TRUE),
		'sale_to' => $this->input->post('sale_to', TRUE)
	);
	$buyer_list = $this->ajax_model->get_buyer_list_data_table($parameters);
	echo json_encode($buyer_list);
}

	public function buyer_contract_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/buyer_contract_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function export_contract_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/export_contract_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function internal_contract_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/internal_contract_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	
	public function buyer_ait_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$this->load->view('website/buyer_ait_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function buyer_bill(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/buyer_bill', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// garden cost entry
	public function garden_cost_entry(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_cost_entry', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// buyer bill report
	public function buyer_bill_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/buyer_bill_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// buyer bill report org
	public function all_buyer_bill_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/all_buyer_bill_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// buyer bill report ltf
	public function all_buyer_bill_report_ltd(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/all_buyer_bill_report_ltd', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function ttab_buyer_purchase(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/ttab_buyer_purchase', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function buyer_accounts_stmt(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/buyer_accounts_stmt', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function buyer_accounts_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/buyer_accounts_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	// buyer delivery order form
	public function buyer_delivery_order(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/buyer_delivery_order', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// buyer delivery order report
	public function buyer_delivery_order_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/buyer_delivery_order_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function after_sale_catalogue(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['catalogue_data'] = $this->ajax_model->catalogue_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/after_sale_catalogue', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// after sales catalogue
	public function after_sale_catalogue_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/after_sale_catalogue_data', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	// garden sale account
	public function garden_sale_account(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['company_data'] = $this->ajax_model->company_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_sale_account', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// garden sale account
	public function garden_sale_account_bs(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['company_data'] = $this->ajax_model->company_data_table();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_sale_account_bs', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// Garden Sale Account Report
	public function garden_sale_account_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/garden_sale_account_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// garden average
	public function garden_average(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_average', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// pre sales advice
	public function pre_sale_advice(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['company_info'] = $this->ajax_model->get_company_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/pre_sale_advice', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// pre sales advice garden letter
	public function pre_sale_advice_garden_letter(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){

			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['company_info'] = $this->ajax_model->get_company_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/pre_sale_advice_garden_letter', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// optimised sales report
	public function optimised_sales_report(){
		$this->web_authorization_user();
		$data = $this->data;

			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['garden_data'] = $this->ajax_model->garden_data_table();
			$data['wh_data'] = $this->ajax_model->wh_data_table();
			$data['grade_data'] = $this->ajax_model->grade_data_table();
			$data['buyer_data'] = $this->ajax_model->get_main_buyers();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/optimised_sales_report', $data);
			$this->load->view('website/login_footer', $data);
				
	}

	public function all_garden_buyer_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$data['buyer_data'] = $this->ajax_model->get_main_buyers();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/all_garden_buyer_report', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// pre sales advice report
	public function pre_sale_advice_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/pre_sale_advice_report', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// pre sales advice report garden letter
	public function pre_sale_advice_garden_letter_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/pre_sale_advice_garden_letter_report', $data);
		}else{
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function get_buyer_type_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$arg = array(
			'buyer_id' => $this->input->post('buyer_id', TRUE)
		);
		$buyer_type = $this->ajax_model->get_buyer_type_table($arg);
		echo json_encode($buyer_type);
	}

	public function wh_buyer_purchase_statement(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/wh_buyer_purchase_statement', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function whs_buyer_purchase_statements(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['current_season_data'] = $this->ajax_model->current_season_data();
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/whs_buyer_purchase_statements', $data);
			$this->load->view('website/login_footer', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function wh_buyer_purchase_statement_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
	
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/wh_buyer_purchase_statement_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	public function bill_regsiter_reports(){
		$this->web_authorization_user();
		$data = $this->data;
		//$data['inv_wh_data'] = $this->ajax_model->inv_wh_data_table($row3['garden_id']);
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/bill_regsiter_reports', $data);
			
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function tea_cup_testing_report(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/tea_cup_testing_report', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function bulk_sale_entry_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$data['buyer_data'] = $this->ajax_model->get_buyer_table();
			$this->load->view('website/bulk_sale_entry_list', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function ttab_bpr_all_center(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/ttab_bpr_all_center', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	public function btb_bpr_all_center_upto(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/login_head', $data);
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/btb_bpr_all_center_upto', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	
	public function tb_bpr_all_center(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/tb_bpr_all_center', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	
	public function garden_adjustment(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$data['ubl_web_user_id'] = $this->session->userdata("ubl_web_user_id");
			$data['season_data'] = $this->ajax_model->season_data_table();
			$data['garden_data'] = $this->ajax_model->get_active_garden_table();
			$data['upcoming_catalogue'] = $this->ajax_model->get_upcoming_catalogue();
			$this->load->view('website/login_head', $data);
			$this->load->view('website/login_nav', $data);
			$this->load->view('website/garden_adjustment', $data);
			$this->load->view('website/login_footer', $data);
		
		}else{
			
			$data = array(
				'heading' => "An Error Was Encountered",
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	public function garden_adjust_entry_save(){
		$this->web_authorization_user();
		$data = $this->data;
		$ga_data = $this->ajax_model->garden_adjust_entry_save_data();
		echo json_encode($ga_data);
	}
	
	public function get_g_charges_list(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			$this->load->view('website/g_charges_list', $data);
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}
	
	// delete setup data
	public function delete_setup_data(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'delete_id' => $this->input->post('delete_id', TRUE),
			'delete_name' => $this->input->post('delete_name', TRUE),
			'data_status' => $this->input->post('data_status', TRUE),
			'last_update' => $this->session->userdata('ubl_user_name').', '.date("d-M-Y").', '.$_SERVER['REMOTE_ADDR'],
		);
		$result = $this->ajax_model->delete_setup_data_table($c_details);
		echo json_encode($result);
	}
	
	// delete garden adjust
	public function delete_garden_adjust(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'delete_id' => $this->input->post('delete_id', TRUE)
		);
		$result = $this->ajax_model->delete_garden_adjust_table($c_details);
		echo json_encode($result);
	}

	// delete contact person
	public function delete_contact_person(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'delete_id' => $this->input->post('delete_id', TRUE)
		);
		$result = $this->ajax_model->delete_contact_person_table($c_details);
		echo json_encode($result);
	}
	
	// delete lot list
	public function delete_lot_list(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'delete_id' => $this->input->post('delete_id', TRUE),
			'pre_invoice_id' => $this->input->post('pre_invoice_id', TRUE)
		);
		$result = $this->ajax_model->delete_lot_table($c_details);
		echo json_encode($result);
	}

	// pre invoice delete
	public function delete_pre_inv_list(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'delete_id' => $this->input->post('delete_id', TRUE)
		);
		$result = $this->ajax_model->delete_pre_inv_table($c_details);
		echo json_encode($result);
	}

	// Market average delete
	public function delete_mavg_list(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'delete_id' => $this->input->post('delete_id', TRUE)
		);
		$result = $this->ajax_model->delete_mavg_table($c_details);
		echo json_encode($result);
	}
	
	public function main_buyer_list(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/main_buyer_list', $data);
	}
	
	// autocomplete
	function get_inv_autocomplete(){
        if (isset($_GET['term'])) {
            $result = $this->ajax_model->search_inv_no($_GET['term']);
            if (count($result) > 0) {
            foreach ($result as $row)
                $arr_result[] = $row->w_inv_no;
                echo json_encode($arr_result);
            }
        }
    }
	
	// create and update user
	public function save_user(){
		$this->web_authorization_user();
		$data = $this->data;
		$u_details = array(
			'edit_user_id' => $this->input->post('edit_user_id', TRUE),
			'username' => $this->input->post('username', TRUE),
			'password' => $this->input->post('password', TRUE),
			'edit_privilege' => $this->input->post('edit_privilege', TRUE),
			'delete_privilege' => $this->input->post('delete_privilege', TRUE)
		);
		$result = $this->ajax_model->save_user_table($u_details);
		echo json_encode($result);
	}

	// Change password
public function change_password(){
    // Don't call web_authorization_user here to avoid any session issues
    // $this->web_authorization_user();
    
    $old_password = $this->input->post('old_password', TRUE);
    $new_password = $this->input->post('new_password', TRUE);
    $ubl_web_user_id = $this->session->userdata('ubl_web_user_id');
    
    // Send to model
    $result = $this->ajax_model->change_password_table($ubl_web_user_id, $old_password, $new_password);
    echo json_encode($result);
}
	
	// list of garden origin
	public function garden_origin_list(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/garden_origin_list', $data);
	}
	
	// save garden origin data
	public function garden_origin_save(){
		$this->web_authorization_user();
		$data = $this->data;
		$data_details = array(
			'origin_edit_id' => $this->input->post('origin_edit_id', TRUE),
			'origin_name' => $this->input->post('origin_name', TRUE),
			'last_update' => $this->input->post('last_update', TRUE)
		);
		$result = $this->ajax_model->garden_origin_save_table($data_details);
		echo json_encode($result);
	}
	
	// list of garden origin table
	public function garden_origin_table(){
		$this->web_authorization_user();
		$data = $this->data;
		$this->load->view('website/garden_origin_table', $data);
	}
	
	// after sales lot data
	public function after_sale_lot_data(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/after_sale_lot_data', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}

	// test vat page
	public function test_vat_page(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/vat', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}
	}
	
	// all garden sales accounts
	public function all_garden_sales_account(){
		$this->web_authorization_user();
		$data = $this->data;
		if($this->session->userdata('ubl_user_type')=='Brokers'){
			
			$this->load->view('website/amount_in_word', $data);
			$this->load->view('website/all_garden_sales_account', $data);
			
		}else{
			
			$data = array(
				'heading' => 'An Error Was Encountered',
				'message' => "<p>You have no permission to access this resource.</p>"
			);
			// page load
			$this->load->view('website/login_head', $data);
			$this->load->view('errors/html/error_404', $data);
		}	
	}

	// Single SMS
	public function single_sms_disburse(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'invoice_id' => $this->input->post('invoice_id', TRUE),
			'sale_no' => $this->input->post('sale_no', TRUE),
			'sold_kgs' => $this->input->post('sold_kgs', TRUE),
			'avg_kgs' => $this->input->post('avg_kgs', TRUE),
			'percentage' => $this->input->post('percentage', TRUE),
			'garden_name' => $this->input->post('garden_name', TRUE),
			'contact_no' => $this->input->post('contact_no', TRUE)
		);
		$result = $this->ajax_model->single_sms_disburse_table($c_details);
		echo json_encode($result);
	}

	// Bulk SMS Garden
	public function bulk_sms_disburse(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'inv_array' => $this->input->post('inv_array', TRUE)
		);
		$result = $this->ajax_model->bulk_sms_disburse_table($c_details);
		echo json_encode($result);
	}

	// Bulk SMS Commpany
	public function bulk_sms_disburse2(){
		$this->web_authorization_user();
		$data = $this->data;
		$c_details = array(
			'inv_array' => $this->input->post('inv_array', TRUE)
		);
		$result = $this->ajax_model->bulk_sms_disburse2_table($c_details);
		echo json_encode($result);
	}
	
} 
// end controller class
?>