<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Restaurant extends CI_Controller {
  
	public function __construct() {
		parent::__construct();  
		$this->load->library('form_validation');
        $this->load->library('ajax_pagination'); 
		$this->load->model(ADMIN_URL.'/common_model');  
		$this->load->model('/restaurant_model');    
	}
	// get the restaurants
	public function index()
	{

		$slug=$this->db->select('restaurant_slug')->from('restaurant')->order_by('entity_id','asc')->limit(1)->get()->row()->restaurant_slug;

		redirect(base_url('restaurant/restaurant-detail/'.$slug));

        $data['page_title'] = $this->lang->line('order_food').' | '.$this->lang->line('site_title');
		$data['current_page'] = 'OrderFood';
		$page = 0; 
		$result = $this->restaurant_model->getRestaurantsForOrder(6,$page,'','','','','','','','pagination');
		if (!empty($result)) {
			foreach ($result as $key => $value) {
				$ratings = $this->restaurant_model->getRestaurantReview($value['MainRestaurantID']);
				$result[$key]['ratings'] = $ratings;
			}
		}
		$countResult = $this->restaurant_model->getRestaurantsForOrder(6,$page,'','','','','','','','');
		$data['restaurants'] = $result;
        $data['TotalRecord'] = count($result);
        $config = array();
        $config["base_url"] = base_url() . "restaurant/index";        
        $config["total_rows"] = count($countResult);
        $config["per_page"] = 6;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';               
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';        
        $config['cur_tag_open'] = '<li class="active"><a class="active">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['uri_segment'] = 3;
        $this->ajax_pagination->initialize($config);
        $data['PaginationLinks'] = $this->ajax_pagination->create_links(); 
		$this->load->view('order_food',$data);
	}
	// ajax restaurants



	public function find_all_image(){

		$entity_id=$this->input->post('entity_id');
		$result=$this->restaurant_model->findImage($entity_id);

		echo json_encode($result);
	}

	public function ajax_restaurants()
	{
        $data['page_title'] = $this->lang->line('order_food').' | '.$this->lang->line('site_title');
		$data['current_page'] = 'OrderFood';
		$page = ($this->input->post('page') !="")?$this->input->post('page'):0;
		$resdishes = ($this->input->post('resdishes'))?$this->input->post('resdishes'):'';
		$latitude = ($this->input->post('latitude'))?$this->input->post('latitude'):'';
		$longitude = ($this->input->post('longitude'))?$this->input->post('longitude'):'';
		$minimum_range = ($this->input->post('minimum_range'))?str_replace( ',', '', $this->input->post('minimum_range') ):0;
		$maximum_range = ($this->input->post('maximum_range'))?str_replace( ',', '', $this->input->post('maximum_range') ):0;
		$food_veg = ($this->input->post('food_veg'))?$this->input->post('food_veg'):0;
		$food_non_veg = ($this->input->post('food_non_veg'))?$this->input->post('food_non_veg'):0;
		$result = $this->restaurant_model->getRestaurantsForOrder(6,$page,$resdishes,$latitude,$longitude,$minimum_range,$maximum_range,$food_veg,$food_non_veg,'pagination');
		if (!empty($result)) {
			foreach ($result as $key => $value) {
				$ratings = $this->restaurant_model->getRestaurantReview($value['MainRestaurantID']);
				$result[$key]['ratings'] = $ratings;
			}
		}
		$countResult = $this->restaurant_model->getRestaurantsForOrder(6,$page,$resdishes,$latitude,$longitude,$minimum_range,$maximum_range,$food_veg,$food_non_veg,'');
		$data['restaurants'] = $result;
        $data['TotalRecord'] = count($result);
        $config = array();
        $config["base_url"] = base_url() . "restaurant/index";        
        $config["total_rows"] = count($countResult);
        $config["per_page"] = 6;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';               
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';        
        $config['cur_tag_open'] = '<li class="active"><a class="active">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['uri_segment'] = 3;
        $this->ajax_pagination->initialize($config);
        $data['PaginationLinks'] = $this->ajax_pagination->create_links(); 
        //echo '<pre>'; print_r($data); exit;
		$this->load->view('ajax_order_food',$data);
	}
	// get restaurant details
	public function restaurant_detail()
	{	
        $data['page_title'] = 'Menu'.' | '.$this->lang->line('site_title');
		$data['current_page'] = 'RestaurantDetails';
		$data['restaurant_details'] = array();
		if (!empty($this->uri->segment('3'))) {
			$content_id = $this->restaurant_model->getContentID($this->uri->segment('3'));
			$data['restaurant_details'] = $this->restaurant_model->getRestaurantDetail($content_id->content_id);
			$data['categories_count'] = count($data['restaurant_details']['categories']);

			if (!empty($data['restaurant_details']['restaurant'])) {
				$ratings = $this->restaurant_model->getRestaurantReview($data['restaurant_details']['restaurant'][0]['restaurant_id']);
				$data['restaurant_reviews'] = $this->restaurant_model->getReviewsRatings($data['restaurant_details']['restaurant'][0]['restaurant_id']);
				$data['restaurant_details']['restaurant'][0]['ratings'] = $ratings;
			}
			$this->session->set_userdata(array('package_id' => ''));
		}
		$cart_details = get_cookie('cart_details');
		$cart_restaurant = get_cookie('cart_restaurant');
		$data['cart_details'] = $this->getCartItems($cart_details,$cart_restaurant);

		$menu_arr = array();
		if (!empty($data['cart_details']['cart_items'])) {
			foreach ($data['cart_details']['cart_items'] as $key => $value) {
				$menu_arr[] = array(
					'menu_id' => $value['menu_id'],
					'quantity' => $value['quantity'],

				);
			}
		}
		$items = $this->restaurant_model->popular_items();

		$item_all = array_count_values(array_column($items, 'item_id'));

		$a = array_column($items, 'item_id');


		arsort($item_all);
//

		foreach ($item_all as $x => $x_value) {

			$menu_data = $this->db->select('*')->from('restaurant_menu_item')->where('entity_id', $x)->get()->result_array();


			$popular_items[] = array(

				'menu_items' => $menu_data

			);
		}

		$main_data = array_slice($popular_items, 0, 8);

		//echo '<pre>';print_r($main_data);exit();

		$data['menu_arr'] = $menu_arr;
		$data['popular_data'] = $main_data;
		// for adding review functionality
		$total_orders = $this->restaurant_model->getTotalOrders($this->session->userdata('UserID'),$data['restaurant_details']['restaurant'][0]['restaurant_id']);
		$total_reviews = $this->restaurant_model->getTotalReviews($this->session->userdata('UserID'),$data['restaurant_details']['restaurant'][0]['restaurant_id']);
		$data['remaining_reviews'] = $total_orders - $total_reviews;

		//echo '<pre>';print_r($data['cart_details']);exit();
		$this->load->view('restaurant_details',$data);
	}
	// get ajax restaurant details
	public function ajax_restaurant_details(){ 
        $data['page_title'] = $this->lang->line('restaurant_details').' | '.$this->lang->line('site_title');
		$data['current_page'] = 'Restaurant Details';
		$searchDish = array();
		if (!empty($this->input->post('searchDish'))) {
			$searchDish = explode(",", $this->input->post('searchDish'));
		}
		$data['restaurant_details'] = array();
		if (!empty($this->input->post('content_id'))) {
			$data['restaurant_details'] = $this->restaurant_model->getRestaurantDetail($this->input->post('content_id'),$searchDish,$this->input->post('food'),$this->input->post('price'));
			$data['categories_count'] = count($data['restaurant_details']['categories']);
			if (!empty($data['restaurant_details']['restaurant'])) {
				$ratings = $this->restaurant_model->getRestaurantReview($data['restaurant_details']['restaurant'][0]['restaurant_id']);
				$data['restaurant_details']['restaurant'][0]['ratings'] = $ratings;
			}
		} 
		$cart_details = get_cookie('cart_details');
		$cart_restaurant = get_cookie('cart_restaurant');
		$data['cart_details'] = $this->getCartItems($cart_details,$cart_restaurant);
		$menu_arr = array();
		if (!empty($data['cart_details']['cart_items'])) {
			foreach ($data['cart_details']['cart_items'] as $key => $value) {
				$menu_arr[] = array(
					'menu_id' => $value['menu_id'],
					'quantity' => $value['quantity'],
				);
			}
		}
		$data['menu_arr'] = $menu_arr;
		$this->load->view('ajax_restaurant_detail',$data);
	}
	// get Cart items
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
					$vat=$details[0]['items'][0]['vat'];
					$cartItems[] = array(
						'menu_id' => $details[0]['items'][0]['menu_id'],
						'restaurant_id' => $cart_restaurant,
						'name' => $details[0]['items'][0]['name'],
						'quantity' => $value->quantity,
						'is_customize' => $details[0]['items'][0]['is_customize'],
						'is_veg' => $details[0]['items'][0]['is_veg'],
						'is_deal' => $details[0]['items'][0]['is_deal'],
						'price' => $details[0]['items'][0]['price'],
						'vat' => ($vat * $value->quantity),
						'offer_price' => $details[0]['items'][0]['offer_price'],
						'subtotal' => $subtotal,
						'totalPrice' => ($subtotal * $value->quantity),
						'cartTotalPrice' => $cartTotalPrice,
						'addons_category_list' => $details[0]['items'][0]['addons_category_list'],
					);
				}
			}
		}

		//echo '<pre>';print_r(array_sum(array_column($cartItems,'vat')));exit();
		$cart_details = array(
			'cart_items' => $cartItems,
			'cart_total_price' => $cartTotalPrice,
			'total_vat' => array_sum(array_column($cartItems,'vat'))
		);
		return $cart_details;
	}
	// event booking page
	public function event_booking()

	{


		$slug=$this->db->select('restaurant_slug')->from('restaurant')->order_by('entity_id','asc')->limit(1)->get()->row()->restaurant_slug;

		redirect(base_url('restaurant/event-booking-detail/'.$slug));

        $data['page_title'] = $this->lang->line('book_event').' | '.$this->lang->line('site_title');
		$data['current_page'] = 'EventBooking';
		$page = 0; 
		$result = $this->restaurant_model->getAllRestaurants(8,$page);
		if (!empty($result['data'])) {
			foreach ($result['data'] as $key => $value) {
				$ratings = $this->restaurant_model->getRestaurantReview($value['restaurant_id']);
				$result['data'][$key]['ratings'] = $ratings;
			}
		}
		$data['restaurants'] = $result['data'];
		$count = count($data['restaurants']);
        $data['TotalRecord'] = $count;
        $config = array();
        $config["base_url"] = base_url() . "restaurant/event-booking";        
        $config["total_rows"] = $result['count'];
        $config["per_page"] = 8;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';               
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';        
        $config['cur_tag_open'] = '<li class="active"><a class="active">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['uri_segment'] = 3;
        $this->ajax_pagination->initialize($config);
        $data['PaginationLinks'] = $this->ajax_pagination->create_links(); 
		$this->load->view('event_booking',$data);
	}
	// ajax events page
	public function ajax_events()
	{
        $data['page_title'] = $this->lang->line('book_event').' | '.$this->lang->line('site_title');
		$data['current_page'] = 'EventBooking';
		$page = ($this->input->post('page') !="")?$this->input->post('page'):0;
        $result = $this->restaurant_model->getAllRestaurants(8,$page,$this->input->post('searchEvent'));
		if (!empty($result['data'])) {
			foreach ($result['data'] as $key => $value) {
				$ratings = $this->restaurant_model->getRestaurantReview($value['restaurant_id']);
				$result['data'][$key]['ratings'] = $ratings;
			}
		}
		$data['restaurants'] = $result['data'];
		$count = count($data['restaurants']);
        $data['TotalRecord'] = $count;
        $config = array();
        $config["base_url"] = base_url() . "restaurant/event-booking";        
        $config["total_rows"] = $result['count'];
        $config["per_page"] = 8;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';               
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';        
        $config['cur_tag_open'] = '<li class="active"><a class="active">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['uri_segment'] = 3;
        $this->ajax_pagination->initialize($config);
        $data['PaginationLinks'] = $this->ajax_pagination->create_links(); 
		$this->load->view('ajax_events',$data);
	}
	// get restaurant dishes
	public function getResturantsDish(){
        $data['page_title'] = $this->lang->line('menu_details').' | '.$this->lang->line('site_title');
		$searchDish = array();
		if (!empty($this->input->post('searchDish'))) {
			$searchDish = explode(",", $this->input->post('searchDish'));
		}
		$content_id = $this->restaurant_model->getRestContentID($this->input->post('restaurant_id'));
		$data['restaurant_details'] = $this->restaurant_model->getRestaurantDetail($content_id->content_id,$searchDish,$this->input->post('food'),$this->input->post('price'));
		$data['categories_count'] = count($data['restaurant_details']['categories']);
		if (!empty($data['restaurant_details']['restaurant'])) {
			$ratings = $this->restaurant_model->getRestaurantReview($data['restaurant_details']['restaurant'][0]['restaurant_id']);
			$data['restaurant_details']['restaurant'][0]['ratings'] = $ratings;
		}
		$cart_details = get_cookie('cart_details');
		$cart_restaurant = get_cookie('cart_restaurant');
		$data['cart_details'] = $this->getCartItems($cart_details,$cart_restaurant);
		$menu_arr = array();
		if (!empty($data['cart_details']['cart_items'])) {
			foreach ($data['cart_details']['cart_items'] as $key => $value) {
				$menu_arr[] = array(
					'menu_id' => $value['menu_id'],
					'quantity' => $value['quantity'],
				);
			}
		}
		$data['menu_arr'] = $menu_arr;
		$this->load->view('head_search_menu_details',$data);
	}

	public function getSearchDish(){
        $data['page_title'] = $this->lang->line('menu_details').' | '.$this->lang->line('site_title');
		$searchDish = array();
		if (!empty($this->input->post('searchDish'))) {
			$searchDish = explode(",", $this->input->post('searchDish'));
		}
		$content_id = $this->restaurant_model->getRestContentID($this->input->post('restaurant_id'));
		$data['restaurant_details'] = $this->restaurant_model->getSearchRestaurantDetail($content_id->content_id,$searchDish);
		$data['categories_count'] = count($data['restaurant_details']['categories']);
		if (!empty($data['restaurant_details']['restaurant'])) {
			$ratings = $this->restaurant_model->getRestaurantReview($data['restaurant_details']['restaurant'][0]['restaurant_id']);
			$data['restaurant_details']['restaurant'][0]['ratings'] = $ratings;
		}
		$cart_details = get_cookie('cart_details');
		$cart_restaurant = get_cookie('cart_restaurant');
		$data['cart_details'] = $this->getCartItems($cart_details,$cart_restaurant);
		$menu_arr = array();
		if (!empty($data['cart_details']['cart_items'])) {
			foreach ($data['cart_details']['cart_items'] as $key => $value) {
				$menu_arr[] = array(
					'menu_id' => $value['menu_id'],
					'quantity' => $value['quantity'],
				);
			}
		}
		$data['menu_arr'] = $menu_arr;
		$this->load->view('head_search_menu_details',$data);
	}
	// event booking detail page
	public function event_booking_detail(){
        $data['page_title'] ='Event Booking'.' | '.$this->lang->line('site_title');
		$data['current_page'] = 'EventBooking';
		$this->session->set_userdata('previous_url', current_url());
		$data['restaurant_details'] = array();
		if (!empty($this->uri->segment('3'))) {
			$content_id = $this->restaurant_model->getContentID($this->uri->segment('3'));
			$data['restaurant_details'] = $this->restaurant_model->getRestaurantDetail($content_id->content_id);
			$data['categories_count'] = count($data['restaurant_details']['categories']);
			if (!empty($data['restaurant_details']['restaurant'])) {
				$ratings = $this->restaurant_model->getRestaurantReview($data['restaurant_details']['restaurant'][0]['restaurant_id']);
				$data['restaurant_reviews'] = $this->restaurant_model->getReviewsRatings($data['restaurant_details']['restaurant'][0]['restaurant_id']);
				$data['restaurant_details']['restaurant'][0]['ratings'] = $ratings;
			}
			$this->session->set_userdata(array('package_id' => ''));
		} 
	    if($this->session->userdata('is_user_login') != 1) {
        	$this->session->unset_userdata('no_of_people');
        	$this->session->unset_userdata('booking_date');
        	$this->session->unset_userdata('dining_time');
        	$this->session->unset_userdata('date_time');
        }
		$this->load->view('event_booking_detail',$data); 
	}
	// checkEventAvailability
	public function checkEventAvailability(){ 
		if (!empty($this->input->post('no_of_people')) && !empty($this->input->post('date_time'))) {
			$booking_date = date("Y-m-d H:i:s",strtotime($this->input->post('date_time')));
			$check = $this->restaurant_model->getBookingAvailability($booking_date,$this->input->post('no_of_people'),$this->input->post('restaurant_id'));
			$date_value = date("Y-m-d",strtotime($this->input->post('date_time')));
			$time_value = date("H:i:s",strtotime($this->input->post('date_time')));

            //check availability data in session
            $this->session->set_userdata('no_of_people', $this->input->post('no_of_people'));
            $this->session->set_userdata('date_time', $this->input->post('date_time'));
			$this->session->set_userdata('booking_date', $date_value);
			$this->session->set_userdata('dining_time', $time_value);
			
			
			if ($check) {
				echo 'success';
			}
			else
			{
				echo 'fail';
			}
			exit;
		}
		else
		{
			echo 'error';
		}
	}
	// add package item to book event
	public function add_package(){
		if ($this->input->post('action') == "add") {
			$this->session->set_userdata('package_id', $this->input->post('entity_id'));
			echo 'success';
		}
		else
		{
			$this->session->set_userdata('package_id','');
			echo 'success';
		}
		exit;
	}
	// book event
	public function bookEvent(){
		if($this->input->post('date_time') != '' && $this->input->post('no_of_people') != ''){
			$booking_date = date("Y-m-d H:i:s",strtotime($this->input->post('date_time')));
            $add_data = array(                   
                'name'=>$this->input->post('name'),
                'no_of_people'=>$this->input->post('no_of_people'),
                'booking_date'=>$booking_date,
                'restaurant_id'=>$this->input->post('restaurant_id'),
                'user_id'=>$this->input->post('user_id'),
                'special_instruction'=>$this->input->post('special_ins'),
                'package_id'=>$this->session->userdata('package_id'),
                'status'=>1,
                'created_by' => $this->input->post('user_id'),
                'event_status'=>'pending'
            );

         //   echo '<pre>';print_r($add_data);exit();
            $event_id = $this->common_model->addData('event',$add_data); 
            $users = array(
                'first_name'=>$this->session->userdata('userFirstname'),
                'last_name'=>($this->session->userdata('userLastname'))?$this->session->userdata('userLastname'):''
            );
            $taxdetail = $this->restaurant_model->getRestaurantTax('restaurant',$this->input->post('restaurant_id'),$flag="order");
            $package = $this->common_model->getSingleRow('restaurant_package','entity_id',$this->session->userdata('package_id'));
            $package_detail = '';
            if(!empty($package)){
                $package_detail = array(
                    'package_price'=>$package->price,
                    'package_name'=>$package->name,
                    'package_detail'=>$package->detail,
                    'package_image'=>$package->image,
                );
            }
            $serialize_array = array(
                'restaurant_detail'=>(!empty($taxdetail))?serialize($taxdetail):'',
                'user_detail'=>(!empty($users))?serialize($users):'',
                'package_detail'=>(!empty($package_detail))?serialize($package_detail):'',
                'event_id'=>$event_id
            );
            $this->common_model->addData('event_detail',$serialize_array); 
            echo 'success';  
        }
	}
	// get Favourite Resturants
	public function getFavouriteResturants(){
        $data['page_title'] = $this->lang->line('fav_restaurants').' | '.$this->lang->line('site_title');
		$data['current_page'] = 'EventBooking';
		$page = 0; 
		$result = $this->restaurant_model->getAllRestaurants(6,$page);
		if (!empty($result['data'])) {
			foreach ($result['data'] as $key => $value) {
				$ratings = $this->restaurant_model->getRestaurantReview($value['restaurant_id']);
				$result['data'][$key]['ratings'] = $ratings;
			}
		}
		$data['restaurants'] = $result['data'];
		$count = count($data['restaurants']);
        $data['TotalRecord'] = $count;
        $config = array();
        $config["base_url"] = base_url() . "restaurant/event-booking";        
        $config["total_rows"] = $result['count'];
        $config["per_page"] = 6;
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';               
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';        
        $config['cur_tag_open'] = '<li class="active"><a class="active">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['uri_segment'] = 3;
        $this->ajax_pagination->initialize($config);
        $data['PaginationLinks'] = $this->ajax_pagination->create_links(); 
		$this->load->view('event_booking',$data);
	}
	// get add ons
	public function getCustomAddOns(){
		$data['page_title'] = $this->lang->line('custom_addons').' | '.$this->lang->line('site_title');
		if (!empty($this->input->post('entity_id')) && !empty($this->input->post('restaurant_id'))) {
			$data['result'] = $this->restaurant_model->getMenuItem($this->input->post('entity_id'),$this->input->post('restaurant_id'));
			$data['currency_symbol'] = $this->common_model->getRestaurantCurrencySymbol($this->input->post('restaurant_id'));
			$this->load->view('ajax_custom_items',$data); 
		}
	}
	// add review
	public function addReview(){
		$add_data = array(                   
	        'restaurant_id'=>$this->input->post('review_restaurant_id'),
	        'user_id'=>$this->input->post('review_user_id'),
	        'review'=>$this->input->post('review_text'),
	        'rating'=>$this->input->post('rating'),
	        'status'=>1,
	        'created_by' => $this->input->post('review_user_id'),
	    ); 
	    $review_id = $this->common_model->addData('review',$add_data); 
	    $this->session->set_flashdata('review_added', $this->lang->line('review_added'));
	    echo 'success';
	}
}
?>
