<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
  
	public function __construct() {
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model(ADMIN_URL.'/common_model');  
		$this->load->model('/home_model'); 
		$this->load->model('/restaurant_model');

		if (empty($this->session->userdata('language_slug'))) {
			$data['lang'] = $this->common_model->getdefaultlang();
			$this->session->set_userdata('language_directory',$data['lang']->language_directory);
			$this->config->set_item('language', $data['lang']->language_directory);
			$this->session->set_userdata('language_slug',$data['lang']->language_slug);
  		}   
	}
	// get home page
	public function index()
	{

//		set_cookie('cart_restaurant', 104);
		$slug=$this->db->select('restaurant_slug')->from('restaurant')->order_by('entity_id','asc')->limit(1)->get()->row()->restaurant_slug;
		$data['current_page'] = 'HomePage';
		$data['page_title'] = $this->lang->line('home_page'). ' | ' . $this->lang->line('site_title');
		$this->session->set_userdata('previous_url', current_url());
		$restaurants = $this->home_model->getRestaurants();
		$cart_details = get_cookie('cart_details');
		$cart_restaurant = get_cookie('cart_restaurant');
		$data['cart_details'] = $this->getCartItems($cart_details,$cart_restaurant);
		$data['restaurant_details'] = array();


			$content_id = $this->restaurant_model->getContentID($slug);
			$data['restaurant_details'] = $this->restaurant_model->getRestaurantDetail($content_id->content_id);
			$data['categories_count'] = count($data['restaurant_details']['categories']);

			if (!empty($data['restaurant_details']['restaurant'])) {
				$ratings = $this->restaurant_model->getRestaurantReview($data['restaurant_details']['restaurant'][0]['restaurant_id']);
				$data['restaurant_reviews'] = $this->restaurant_model->getReviewsRatings($data['restaurant_details']['restaurant'][0]['restaurant_id']);
				$data['restaurant_details']['restaurant'][0]['ratings'] = $ratings;
			}
			$this->session->set_userdata(array('package_id' => ''));



		$data['restaurants'] = array_values($restaurants);
		if (!empty($data['restaurants'])) {
			foreach ($data['restaurants'] as $key => $value) {
				$ratings = $this->home_model->getRestaurantReview($value['MainRestaurantID']);
				$data['restaurants'][$key]['ratings'] = $ratings;
			}
		}
		$data['categories'] = $this->home_model->getAllCategories();
		$data['coupons'] = $this->home_model->getAllCoupons();
		$this->load->view('home_page',$data);
	}


	public function getCartItems($cart_details,$cart_restaurant){
		$cartItems = array();
		$cartTotalPrice = 0;
		if (!empty($cart_details)) {
			foreach (json_decode($cart_details) as $key => $value) {
				$details = $this->restaurant_model->getMenuItem($value->menu_id,$cart_restaurant);
				if (!empty($details)) {
					if ($details[0]['items'][0]['is_customize'] == 1) {
						$addons_category_id = array_column($value->addons, 'addons_category_id');
						$add_onns_id = array_column($value->addons, 'add_onns_id');

						if (!empty($details[0]['items'][0]['addons_category_list'])) {
							foreach ($details[0]['items'][0]['addons_category_list'] as $key => $cat_value) {
								if (!in_array($cat_value['addons_category_id'], $addons_category_id)) {
									unset($details[0]['items'][0]['addons_category_list'][$key]);
								}
								else
								{
									if (!empty($cat_value['addons_list'])) {
										foreach ($cat_value['addons_list'] as $addkey => $add_value) {
											if (!in_array($add_value['add_ons_id'], $add_onns_id)) {
												unset($details[0]['items'][0]['addons_category_list'][$key]['addons_list'][$addkey]);
											}
										}
									}
								}
							}
						}
					}
					// getting subtotal
					if ($details[0]['items'][0]['is_customize'] == 1)
					{	$subtotal = 0;
						if (!empty($details[0]['items'][0]['addons_category_list'])) {
							foreach ($details[0]['items'][0]['addons_category_list'] as $key => $cat_value) {
								if (!empty($cat_value['addons_list'])) {
									foreach ($cat_value['addons_list'] as $addkey => $add_value) {
										$subtotal += $add_value['add_ons_price'];
									}
								}
							}
						}
					}
					else
					{	$subtotal = 0;
						if ($details[0]['items'][0]['is_deal'] == 1) {
							$price = ($details[0]['items'][0]['offer_price'])?$details[0]['items'][0]['offer_price']:(($details[0]['items'][0]['price'])?$details[0]['items'][0]['price']:0);
						}
						else
						{
							$price = ($details[0]['items'][0]['price'])?$details[0]['items'][0]['price']:0;
						}
						$subtotal = $subtotal + $price;
					}
					$cartTotalPrice = ($subtotal * $value->quantity) + $cartTotalPrice;
					$cartItems[] = array(
						'menu_id' => $details[0]['items'][0]['menu_id'],
						'restaurant_id' => $cart_restaurant,
						'name' => $details[0]['items'][0]['name'],
						'quantity' => $value->quantity,
						'is_customize' => $details[0]['items'][0]['is_customize'],
						'is_veg' => $details[0]['items'][0]['is_veg'],
						'is_deal' => $details[0]['items'][0]['is_deal'],
						'price' => $details[0]['items'][0]['price'],
						'offer_price' => $details[0]['items'][0]['offer_price'],
						'subtotal' => $subtotal,
						'totalPrice' => ($subtotal * $value->quantity),
						'cartTotalPrice' => $cartTotalPrice,
						'addons_category_list' => $details[0]['items'][0]['addons_category_list'],
					);
				}
			}
		}
		$cart_details = array(
			'cart_items' => $cartItems,
			'cart_total_price' => $cartTotalPrice,
		);
		return $cart_details;
	}
	// frontend user login
	public function login()
	{	 
    	$data['page_title'] = $this->lang->line('title_login').' | '. $this->lang->line('site_title');
		if($this->input->post('submit_page') == "Login"){
			$this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required'); 
	        $this->form_validation->set_rules('password', 'Password', 'trim|required');        
	        if ($this->form_validation->run())
	        {  
	            $phone_number = trim($this->input->post('phone_number'));
	            $enc_pass = md5(SALT.trim($this->input->post('password')));

	            $this->db->where('mobile_number',$phone_number);
				$this->db->where('password',$enc_pass);
				$this->db->where("(user_type='User')");
				$val = $this->db->get('users')->first_row();  
				if(!empty($val))
				{       
					if($val->active=='1' && $val->status=='1') 
					{
						$this->session->set_userdata(
							array(
							  'UserID' => $val->entity_id,
							  'userFirstname' => $val->first_name,                            
							  'userLastname' => $val->last_name,                            
							  'userEmail' => $val->email,                                   
							  'userPhone' => $val->mobile_number,                                
							  'userImage' => $val->image,                            
							  'is_admin_login' => 0,                           
							  'is_user_login' => 1,
							  'UserType' => $val->user_type,
							  'package_id' => array(),
							)
						);
						// remember ME
						$cookie_name = "adminAuth";
						if($this->input->post('rememberMe')==1)
						{                    
							$this->input->set_cookie($cookie_name, 'usr='.$phone_number.'&hash='.trim($this->input->post('password')), 60*60*24*5); // 5 days
						} 
						else 
						{
							delete_cookie($cookie_name);
						}                
						if($this->session->userdata('previous_url'))
					    {
					        redirect($this->session->userdata('previous_url'));
					    } else {
					    	redirect(base_url().'myprofile');
					    }
					} 
					else if($val->active=='0' || $val->active=='' || $val->status=='0')
					{                
						$data['loginError'] = $this->lang->line('front_login_deactivate');
					} 
					else 
					{
						$data['loginError'] = $this->lang->line('front_login_error');
					}
				}
				else
				{
					$data['loginError'] = $this->lang->line('front_login_error');
				}
				$this->session->set_flashdata('error_MSG', $data['loginError']);
				redirect(base_url().'home/login');
				exit;
	        }
		}
		$data['current_page'] = 'Login';
		$this->load->view('login',$data);
	}
    /*
    * Server side validation check email exist
    */
	public function checkPhone($str){   
		$checkPhone = $this->home_model->checkPhone($str); 
		if($checkPhone>0){
			$this->form_validation->set_message('checkPhone', $this->lang->line('number_already_registered'));
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	/*
	* Server side validation check email exist
	*/
	public function checkEmail($str){    
		$checkEmail = $this->home_model->checkEmail($str);       
		if($checkEmail>0){
			$this->form_validation->set_message('checkEmail','User have already registered with this email!');
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	// frontend user registration
	public function registration()
	{
		$data['page_title'] = $this->lang->line('title_registration').' | '.$this->lang->line('site_title');
		if($this->input->post('submit_page') == "Register"){
			$this->form_validation->set_rules('name', 'Name', 'trim|required'); 
			$this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|callback_checkPhone');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|callback_checkEmail'); 
	        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');        
	        if ($this->form_validation->run())
	        {   
	        	$checkRecords = $this->home_model->mobileCheck(trim($this->input->post('phone_number')));
	        	if ($checkRecords == 0) {
		            $name = trim($this->input->post('name'));      
		            $namearr = explode(" ", $name);  
		            if (!empty($namearr)) {
		            	foreach ($namearr as $key => $value) {
		            		if ($key != 0) {
		            			$last_name[] = $value;
		            		}
		            	}
		            }
	                $userData = array(
	                    "first_name"=>(!empty($namearr[0]))?$namearr[0]:'',
	                    "last_name"=>(!empty($last_name))?implode(" ", $last_name):'',
	                    "password"=>md5(SALT.$this->input->post('password')),
	                    "email"=>trim($this->input->post('email')),
	                    "mobile_number"=>trim($this->input->post('phone_number')),
	                    "user_type"=>"User",
	                    "status"=>1
	                ); 
	                $entity_id = $this->common_model->addData('users',$userData);
		            if ($entity_id) {
		            	$data['success'] = $this->lang->line('registration_success');
		            	$this->session->set_flashdata('success_MSG', $data['success']);
		            }
		            if($this->input->post('email')){
                         // confirmation link
		            	$language_slug = ($this->session->userdata('language_slug'))?$this->session->userdata('language_slug'):'en';
                        $verificationCode = random_string('alnum', 20).$UserID.random_string('alnum', 5);
                        $confirmationLink = '<a href='.base_url().'user/verify_account/'.$verificationCode.'>here</a>';   
                        $email_template = $this->db->get_where('email_template',array('email_slug'=>'verify-account','language_slug'=>$language_slug))->first_row();       
                        $arrayData = array('FirstName'=>$namearr[0],'ForgotPasswordLink'=>$confirmationLink);
                        $EmailBody = generateEmailBody($email_template->message,$arrayData);
                        //get System Option Data
                        $this->db->select('OptionValue');
                        $FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

                        $this->db->select('OptionValue');
                        $FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();
                      
                        $this->load->library('email');  
                        $config['charset'] = "utf-8";
                        $config['mailtype'] = "html";
                        $config['newline'] = "\r\n";      
                        $this->email->initialize($config);  
                        $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);  
                        $this->email->to($this->input->post('email'));      
                        $this->email->subject($email_template->subject);  
                        $this->email->message($EmailBody);
                        $this->email->send();
                          
                        // update verification code
                        $addata = array('email_verification_code'=>$verificationCode);
                        $this->common_model->updateData('users',$addata,'entity_id',$entity_id);          
                    }
	        	}
	        	else
	        	{
	        		$data['error'] = $this->lang->line('front_registration_fail');
	        		$this->session->set_flashdata('error_MSG', $data['error']);
	        	}
	        	redirect(base_url().'home/registration');
	        	exit;
	        }
	    }
		$data['current_page'] = 'Registration';
		$this->load->view('registration',$data);
	}
	// user forgot password
	public function forgot_password(){ 
		if($this->input->post('forgot_submit_page') == "Submit"){ 
			$this->form_validation->set_rules('email_forgot', 'Email', 'trim|required|valid_email');      
	        if ($this->form_validation->run())
	        {   
	        	$checkRecord = $this->common_model->getRowsMultipleWhere('users', array('email'=>strtolower($this->input->post('email_forgot')),'status'=>1));
	        	$arr['forgot_success'] = '';
	        	$arr['forgot_error'] = '';
		        if(!empty($checkRecord[0]))
		        {
		            // confirmation link
		            if($this->input->post('email_forgot')){
		            	$language_slug = ($this->session->userdata('language_slug'))?$this->session->userdata('language_slug'):'en';
		                $verificationCode = random_string('alnum', 20).$checkRecord[0]->entity_id.random_string('alnum', 5);
		                $confirmationLink = '<a href='.base_url().'user/reset/'.$verificationCode.'>here</a>';   
		                $email_template = $this->db->get_where('email_template',array('email_slug'=>'forgot-password','language_slug'=>$language_slug))->first_row();        
		                $arrayData = array('FirstName'=>$checkRecord[0]->first_name,'ForgotPasswordLink'=>$confirmationLink);
		                $EmailBody = generateEmailBody($email_template->message,$arrayData);

		                //get System Option Data
		                $this->db->select('OptionValue');
		                $FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

		                $this->db->select('OptionValue');
		                $FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();
		              
		                $this->load->library('email');  
		                $config['charset'] = "utf-8";
		                $config['mailtype'] = "html";
		                $config['newline'] = "\r\n";      
		                $this->email->initialize($config);  
		                $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);  
		                $this->email->to($this->input->post('email_forgot'));      
		                $this->email->subject($email_template->subject);  
		                $this->email->message($EmailBody);            
		                $this->email->send();
		                // update verification code
		                $addata = array('email_verification_code'=>$verificationCode);
		                $this->common_model->updateData('users',$addata,'entity_id',$checkRecord[0]->entity_id); 
		            }
		            $arr['forgot_success'] = $this->lang->line('forgot_success');;
		        }
		        else
		        {
		            $arr['forgot_error'] = $this->lang->line('email_not_exist');
		        }
	        }
	    }

	    echo json_encode($arr);
	}
	// user logout
	public function logout(){ 
        $this->session->unset_userdata('UserID');
        $this->session->unset_userdata('userFirstname');
        $this->session->unset_userdata('userLastname');
        $this->session->unset_userdata('userEmail');   
        $this->session->unset_userdata('userPhone'); 
        $this->session->unset_userdata('is_user_login'); 
        $this->session->unset_userdata('package_id');   
        delete_cookie('cart_details');
		delete_cookie('cart_restaurant');          
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
    // add lat long to session once if searched by user
    public function addLatLong(){
    	if(!empty($this->input->post('lat')) && !empty($this->input->post('long')) && !empty($this->input->post('address'))){
    		$this->session->set_userdata(
				array(
				  'searched_lat' => $this->input->post('lat'),
				  'searched_long' => $this->input->post('long'),  
				  'searched_address' => $this->input->post('address'),  
				)
			);
    	}
    }
	// get Popular Resturants
	public function getPopularResturants(){
		$data['page_title'] = $this->lang->line('popular_restaurants').' | '.$this->lang->line('site_title');
		$restaurants = $this->home_model->getRestaurants();
		if (!empty($this->input->post('latitude')) && !empty($this->input->post('longitude'))) { 
			$address = $this->getAddress($this->input->post('latitude'),$this->input->post('longitude'));
			if (!empty($restaurants)) {
				foreach ($restaurants as $key => $value) {
					$distance = $this->getDistance($this->input->post('latitude'),$this->input->post('longitude'), $value['latitude'], $value['longitude']);
					if ((int)$distance < MAXIMUM_RANGE) {
						$nearbyRestaurants[] = $restaurants[$key];
					}
				}
			}
			if (!empty($nearbyRestaurants)) {
				foreach ($nearbyRestaurants as $key => $value) {
					$ratings = $this->home_model->getRestaurantReview($value['restaurant_id']);
					$nearbyRestaurants[$key]['ratings'] = $ratings;
				}
			}
			$data['nearbyRestaurants'] = $nearbyRestaurants;
		}
		else
		{
			if (!empty($restaurants)) {
				foreach ($restaurants as $key => $value) {
					$ratings = $this->home_model->getRestaurantReview($value['restaurant_id']);
					$restaurants[$key]['ratings'] = $ratings;
				}
			}
			$data['nearbyRestaurants'] = array_values($restaurants);
		}
		$this->load->view('popular_restaurants',$data);
	}
	// get user's address with lat long
	public function getUserAddress(){
		$this->session->set_userdata(
			array(
			  'latitude' => $this->input->post('latitude'),
			  'longitude' => $this->input->post('longitude'),
			)
		);
		$address = $this->getAddress($this->input->post('latitude'),$this->input->post('longitude'));
		echo json_encode($address);
	}
	// get distance between two pair of coordinates
	function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
		$earth_radius = 6371;

		$dLat = deg2rad($latitude2 - $latitude1);  
		$dLon = deg2rad($longitude2 - $longitude1);  

		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
		$c = 2 * asin(sqrt($a));  
		$d = $earth_radius * $c;  
		return $d;  
	}
	// get address from lat long
	function getAddress($latitude,$longitude){ 
	    if(!empty($latitude) && !empty($longitude)){
	        //Send request and receive json data by address
	        $geocodeFromLatLong = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&key=AIzaSyCGh2j6KRaaSf96cTYekgAD-IuUG0GkMVA'); 
	        $output = json_decode($geocodeFromLatLong);
	        $status = $output->status;
	        //Get address from json data
	        $address = ($status=="OK")?$output->results[1]->formatted_address:'';
	        //Return address of the given latitude and longitude
	        if(!empty($address)) {
	            return $address;
	        }
	        else
	        {
	            return false;
	        }
	    }
	    else
	    {
	        return false;   
	    }
	}
	// categories search
	public function quickCategorySearch(){
		$data['page_title'] = $this->lang->line('popular_restaurants').' | '.$this->lang->line('site_title');
		$restaurants = $this->home_model->searchRestaurants($this->input->post('category_id'));
		if (!empty($restaurants)) {
			foreach ($restaurants as $key => $value) {
				$distance = $this->getDistance($this->session->userdata('latitude'),$this->session->userdata('longitude'), $value['latitude'], $value['longitude']);
				if ($distance < MAXIMUM_RANGE) {
					$nearbyRestaurants[] = $restaurants[$key];
				}
			}
		}
		if (!empty($nearbyRestaurants)) {
			foreach ($nearbyRestaurants as $key => $value) {
				$ratings = $this->home_model->getRestaurantReview($value['restaurant_id']);
				$nearbyRestaurants[$key]['ratings'] = $ratings;
			}
		}
		$data['nearbyRestaurants'] = $nearbyRestaurants;
		$this->load->view('popular_restaurants',$data);
	}
	// function to get  the address
	function get_lat_long($address){
	    $address = str_replace(" ", "+", $address);
	    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
	    $json = json_decode($json);
	    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
	    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
	    $latlng = array('latitude'=>$lat,'longitude'=>$long);
	    return json_encode($latlng);
	}
	// get users notification
	public function getNotifications(){
		if (!empty($this->session->userdata('UserID'))) {
			$data['userUnreadNotifications'] = $this->common_model->getUsersNotification($this->session->userdata('UserID'),'unread');
			$data['notification_count'] = count($data['userUnreadNotifications']);
			$data['userNotifications'] = $this->common_model->getUsersNotification($this->session->userdata('UserID'));
			$this->load->view('ajax_notifications',$data);
		}
	}
	// get unread notifications
	public function unreadNotifications() { 
		if (!empty($this->session->userdata('UserID'))) { 
			$updateData = array(
				'view_status' => 1,
			);
			$this->common_model->updateData('user_order_notification',$updateData,'user_id',$this->session->userdata('UserID'));
			$this->common_model->updateData('user_event_notifications',$updateData,'user_id',$this->session->userdata('UserID'));
			$data['userUnreadNotifications'] = $this->common_model->getUsersNotification($this->session->userdata('UserID'),'unread');
			$data['notification_count'] = count($data['userUnreadNotifications']);
			$data['userNotifications'] = $this->common_model->getUsersNotification($this->session->userdata('UserID'));
		}
	}

	public function get_cart_item_no()
	{
		$cart_details = get_cookie('cart_details');
		$cart_restaurant = get_cookie('cart_restaurant');
		$data['cart_details'] = $this->getCartItems($cart_details, $cart_restaurant);

		echo count($data['cart_details']['cart_items']);
	}
}
