<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model('ajax_model');
        
        // Set CORS headers to allow requests from any origin
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Allow-Methods: GET, POST");
        
        // Handle OPTIONS request for CORS preflight
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // Exit after sending headers to handle the preflight request
        }
    }

    public function print_auction_schedule_table_api() {
        // Retrieve parameters from the POST request
        $parameters = array(
            'season_name' => $this->input->post('season_name'),
            'auction_center' => $this->input->post('auction_center')
        );

        // Fetch the data from the model
        $data['resultq1'] = $this->ajax_model->auction_schedule_table_db($parameters);

        // Check if data is available
        if (isset($data['resultq1']) && !empty($data['resultq1'])) {
            // Load view or return the response directly
            $this->load->view('website/print_auction_schedule_table_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }

    public function get_season_data() {
        // Fetch season data
        $data['season_data'] = $this->ajax_model->season_data_table();
        
        // Return the data as JSON
        if (!empty($data['season_data'])) {
            echo json_encode($data['season_data']);
        } else {
            echo json_encode(['error' => 'No season data found']);
        }
    }

	public function after_sale_sale_no_data() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	// garden average Report
	public function garden_sale_account_average_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$combine_center = $this->input->post('combine_center');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($combine_center)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'combine_center' => $combine_center
			);
			
			// Load the view or return the response directly
			$this->load->view('website/garden_sale_account_average_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	
	// buyer contract sale no 
	public function after_sale_sale_no_data2() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api2($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// buyer Contract Report
	public function buyer_contract_report_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$buyer_id = $this->input->post('buyer_id');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($buyer_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'buyer_id' => $buyer_id
			);
			
			// Load the view or return the response directly
			$this->load->view('website/buyer_contract_report_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	
	// buyer bill sale no 
	public function after_sale_sale_no_data3() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api3($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// buyer bill Report
	public function buyer_bill_report_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$buyer_id = $this->input->post('buyer_id');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($buyer_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'buyer_id' => $buyer_id
			);
			
			// Load the view or return the response directly
			$this->load->view('website/buyer_bill_report_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	

	// garden sales account sale no 
	public function after_sale_sale_no_data4() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api4($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	// garden sales Report
	public function garden_sale_account_report_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$garden_id = $this->input->post('garden_id');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($garden_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id
			);
			
			// Load the view or return the response directly
			$this->load->view('website/garden_sale_account_report_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	
	
	// garden sales released sale no 
	public function after_sale_sale_no_data5() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api5($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// garden sales price realised list report
	public function price_realised_list_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$garden_id = $this->input->post('garden_id');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($garden_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id
			);
			
			// Load the view or return the response directly
			$this->load->view('website/price_realised_list_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	
	// out lot  sale no 
	public function after_sale_sale_no_data6() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api6($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// out lot statement report
	public function print_out_lot_stmt_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no
			);
			
			// Load the view or return the response directly
			$this->load->view('website/print_out_lot_stmt_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	// out lot page
	public function garden_unsold_report_api() {
	
	// Retrieve parameters from the GET request
	$season_name = $this->input->get('season_name');
	$auction_center = $this->input->get('auction_center');
	$sale_no = $this->input->get('sale_no');
	$garden_id = $this->input->get('garden_id');
	$report_name = $this->input->get('report_name');

	// Check if all required data is available
	if (!empty($season_name) && !empty($auction_center) && !empty($sale_no)) {
		// Data is valid, load the view with the parameters
		$data = array(
			'season_name' => $season_name,
			'auction_center' => $auction_center,
			'sale_no' => $sale_no,
			'garden_id' => $garden_id,
			'report_name' => $report_name
		);
		
		// Load the view or return the response directly
		$this->load->view('website/garden_unsold_report_api', $data);
		} else {
		echo "No data available for the provided parameters.";
		}
	}
	
	//out lot page 2
	public function garden_unsold_details_api() {
		
		// Retrieve parameters from the GET request
		$season_name = $this->input->get('season_name');
		$auction_center = $this->input->get('auction_center');
		$sale_no = $this->input->get('sale_no');

		// Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no)) {
			// Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no
			);
			
			// Load the view or return the response directly
			$this->load->view('website/garden_unsold_details_api', $data);
		} else {
			echo "No data available for the provided parameters.";
		}
	}

	//out lot page 3
	public function print_out_lot_stmt_api2() {
		
		// Retrieve parameters from the GET request
		$season_name = $this->input->get('season_name');
		$auction_center = $this->input->get('auction_center');
		$sale_no = $this->input->get('sale_no');
		$garden_id = $this->input->get('garden_id');

		// Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no)) {
			// Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id
			);
			
			$this->load->view('website/print_out_lot_stmt_api2', $data);
		} else {
			echo "No data available for the provided parameters.";
		}
	}


	// pre and post  sale no 
	public function after_sale_sale_no_data7() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api7($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// garden pre & post sale report
	public function pre_sale_advice_report_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$garden_id = $this->input->post('garden_id');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($garden_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id
			);
			
			// Load the view or return the response directly
			$this->load->view('website/pre_sale_advice_report_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	// buyer statement  sale no 
	public function after_sale_sale_no_data8() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api8($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	// buyer statement report
	public function buyer_statement_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$garden_id = $this->input->post('garden_id');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($garden_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id
			);
			
			// Load the view or return the response directly
			$this->load->view('website/buyer_statement_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	
	
	// testing report  sale no 
	public function after_sale_sale_no_data9() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE),
			'catalouge_name' => $this->input->post("catalouge_name", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api9($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// testing report
	public function tea_cup_testing_report_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$garden_id = $this->input->post('garden_id');
		$catalouge_name = $this->input->post('catalouge_name');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($garden_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id,
				'catalouge_name' => $catalouge_name
			);
			
			// Load the view or return the response directly
			$this->load->view('website/tea_cup_testing_report_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	// testing report  sale no 
	public function after_sale_sale_no_data10() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE),
			'catalouge_name' => $this->input->post("catalouge_name", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api10($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// testing report comment
	public function tea_cup_testing_report_comment_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$garden_id = $this->input->post('garden_id');
		$catalouge_name = $this->input->post('catalouge_name');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no) && !empty($garden_id)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'garden_id' => $garden_id,
				'catalouge_name' => $catalouge_name
			);
			
			// Load the view or return the response directly
			$this->load->view('website/tea_cup_testing_report_comment_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	
	// catolouge  sale no 
	public function after_sale_sale_no_data11() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api11($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// catalogue report
	public function print_published_catalogue_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');
		$catalogue = $this->input->post('catalogue');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no,
				'catalogue' => $catalogue
			);
			
			// Load the view or return the response directly
			$this->load->view('website/print_published_catalogue_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	
	// catolouge  sale no 
	public function after_sale_sale_no_data12() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api12($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	
	// after sales catalogue report
	public function after_sale_catalogue_data_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no
			);
			
			// Load the view or return the response directly
			$this->load->view('website/after_sale_catalogue_data_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }
	
	// auction average  sale no 
	public function after_sale_sale_no_data13() {
		// Fetch required data
		$parameters = array(
			'season' => $this->input->post('season', TRUE),
			'auction_center' => $this->input->post("auction_center", TRUE)
		);

		// Fetch sale numbers based on the parameters
		$sale_no_data = $this->ajax_model->after_sale_sale_no_data_api13($parameters);
		
		// Return the data as JSON
		echo json_encode($sale_no_data);
	}
	
	// auction average report
	public function bill_regsiter_reports_auction_avg_api() {
		
        // Retrieve parameters from the POST request
		$season_name = $this->input->post('season_name');
		$auction_center = $this->input->post('auction_center');
		$sale_no = $this->input->post('sale_no');

        // Check if all required data is available
		if (!empty($season_name) && !empty($auction_center) && !empty($sale_no)) {
            // Data is valid, load the view with the parameters
			$data = array(
				'season_name' => $season_name,
				'auction_center' => $auction_center,
				'sale_no' => $sale_no
			);
			
			// Load the view or return the response directly
			$this->load->view('website/bill_regsiter_reports_auction_avg_api', $data);
        } else {
            echo "No data available for the provided parameters.";
        }
    }

}
?>