<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(-1);
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('v1/api_model');                
        $this->load->library('form_validation');
        $this->current_lang = "en";
    }
    //common lang fucntion
    public function getLang(){
        $this->current_lang = ($this->post('language_slug'))?$this->post('language_slug'):$this->current_lang;
        $languages = $this->api_model->getLanguages($this->current_lang);
        $this->lang->load('messages_lang', $languages->language_directory);
    }
    // Registration API
    public function registration_post()
    {
        $this->getLang();
        if($this->post('FirstName') !="" && $this->post('PhoneNumber') != "" && $this->post('Email') !="" && $this->post('Password') !="")
        {
            $checkRecord = $this->api_model->getRecord('users', 'mobile_number',$this->post('PhoneNumber'));
            $checkemail = $this->api_model->getRecord('users', 'email',$this->post('Email'));
            if(empty($checkRecord) && empty($checkemail))
            {
                $addUser = array(
                    'mobile_number'=>trim($this->post('PhoneNumber')),
                    'first_name'=>trim($this->post('FirstName')),
                    'email'=>trim(strtolower($this->post('Email'))),
                    'password'=>md5(SALT.$this->post('Password')),
                    'last_name'=>'',
                    'user_type'=>'User',
                    'status'=>1                
                );
                $UserID = $this->api_model->addRecord('users', $addUser);
                $login = $this->api_model->getRegisterRecord('users',$UserID);
                if($UserID)
                {
                    $data = array('device_id'=>$this->post('firebase_token'));
                    $this->api_model->updateUser('users',$data,'entity_id',$UserID);
                    if($this->post('Email')){
                         // confirmation link
                        $verificationCode = random_string('alnum', 20).$UserID.random_string('alnum', 5);
                        $confirmationLink = '<a href='.base_url().'user/verify_account/'.$verificationCode.'>here</a>';   
                        $email_template = $this->db->get_where('email_template',array('email_slug'=>'verify-account','language_slug'=>'en'))->first_row();        
                        $arrayData = array('FirstName'=>$this->post('FirstName'),'ForgotPasswordLink'=>$confirmationLink);
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
                        $this->email->to($this->post('Email'));      
                        $this->email->subject($email_template->subject);  
                        $this->email->message($EmailBody);
                        $this->email->send();
                          
                        
                        // update verification code
                        $addata = array('email_verification_code'=>$verificationCode);
                        $this->api_model->updateUser('users',$addata,'entity_id',$UserID);          
                    }
                    $this->response(['User' => $login,'active'=>false,'status'=>1,'message' => $this->lang->line('registration_success')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code 
                }
                else
                {
                    $this->response([
                        'status' => 0,
                        'message' => $this->lang->line('registration_fail')
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                }                        
            }
            else
            {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('user_exist')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
            }
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('regi_validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code                        
        }
    }
    // Login API
    public function login_post()
    {
        $this->getLang();
        $login = $this->api_model->getLogin($this->post('PhoneNumber'), $this->post('Password'));

        if(!empty($login)){
            if($login->active == 1){
                $data = array('active'=>1,'device_id'=>$this->post('firebase_token'));
                if($login->status==1)
                {
                    // update device 
                    $image = ($login->image)?image_url.$login->image:'';               
                    $this->api_model->updateUser('users',$data,'entity_id',$login->entity_id);
                    //get rating
                    $rating = $this->api_model->getRatings($login->entity_id);
                    $review = (!empty($rating))?$rating->rating:'';
                    
                    $last_name = ($login->last_name)?$login->last_name:'';
                    $login_detail = array('FirstName'=>$login->first_name,'LastName'=>$last_name,'image'=>$image,'PhoneNumber'=>$login->mobile_number,'UserID'=>$login->entity_id,'notification'=>$login->notification,'rating'=>$review,'Email'=>$login->email);
                    $this->response(['login' => $login_detail,'status'=>1,'active'=>true,'message' =>$this->lang->line('login_success') ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } else if ($login->status==0){
                    $adminEmail = $this->api_model->getSystemOptoin('Admin_Email_Address');
                    $this->response(['status' => 2,'message' => $this->lang->line('login_deactive'),'email'=>$adminEmail->OptionValue], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } 
            }else{
                $this->response([
                    'status' => 0,
                    'active' => false,
                    'message' => $this->lang->line('otp_inactive')
                ], REST_Controller::HTTP_OK);
            }
        }        
        else
        {
            $emailexist = $this->api_model->getRecord('users','mobile_number',$this->post('PhoneNumber'));
            if($emailexist){
                $this->response([
                    'status' => 0,
                    'message' =>$this->lang->line('pass_validation')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            } else {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }        
    }
    //verify OTP
    public function verifyOTP_post()
    {
        $this->getLang();
        $login = $this->api_model->getLogin($this->post('PhoneNumber'), $this->post('Password'));
        if(!empty($login)){
            if($this->post('active') == 1){
                $data = array('active'=>1);
                $this->api_model->updateUser('users',$data,'entity_id',$login->entity_id);
                $image = ($login->image)?image_url.$login->image:'';  
                $last_name = ($login->last_name)?$login->last_name:'';
                $login_detail = array('FirstName'=>$login->first_name,'LastName'=>$last_name,'image'=> $image ,'PhoneNumber'=>$login->mobile_number,'UserID'=>$login->entity_id,'notification'=>$login->notification);
                $this->response(['login' => $login_detail,'active'=>true,'status'=>1,'message' => $this->lang->line('success')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    'status' => 0,
                    'active' => false,
                    'message' => $this->lang->line('otp_inactive')
                ], REST_Controller::HTTP_OK);
            }
        }else{
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //get homepage
    public function getHome_post(){
        //for event
        $this->getLang();
        $language_slug = ($this->post('language_slug'))?$this->post('language_slug'):'';
        if($this->post('isEvent') == 1){
            $latitude = ($this->post('latitude'))?$this->post('latitude'):'';
            $longitude = ($this->post('longitude'))?$this->post('longitude'):'';
            $searchItem = ($this->post('itemSearch'))?$this->post('itemSearch'):'';
            $restaurant = $this->api_model->getEventRestaurant($latitude,$longitude,$searchItem,$this->current_lang);
            if(!empty($restaurant)){
               $this->response([
                    'date'=>date("Y-m-d g:i A"),
                    'restaurant'=>$restaurant,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code 
            }else{
                $this->response([
                    'status' => 1,
                    'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }  
        }else{ // for home page
            if($this->post('latitude') !="" && $this->post('longitude') != "")
            {
                $food = $this->post('food');
                $rating = $this->post('rating');
                $distance = $this->post('distance');
                $searchItem = ($this->post('itemSearch'))?$this->post('itemSearch'):'';
                $restaurant = $this->api_model->getHomeRestaurant($this->post('latitude'),$this->post('longitude'),$searchItem,$food,$rating,$distance,$this->current_lang);
                $slider = $this->api_model->getbanner();
                $category = $this->api_model->getcategory($this->post('language_slug'));
                $this->response([
                    'date'=>date("Y-m-d g:i A"),
                    'restaurant'=>$restaurant,
                    'slider'=>$slider,
                    'category'=>$category,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }else{
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code                        
            }
        }
    }
    // Forgot Password
    public function forgotpassword_post()
    {
        $this->getLang();
        $checkRecord = $this->api_model->getRecordMultipleWhere('users', array('email'=>strtolower($this->post('Email')),'status'=>1));
        if(!empty($checkRecord))
        {
            // confirmation link
            if($this->post('Email')){
                $verificationCode = random_string('alnum', 20).$checkRecord->entity_id.random_string('alnum', 5);
                $confirmationLink = '<a href='.base_url().'user/reset/'.$verificationCode.'>here</a>';   
                $email_template = $this->db->get_where('email_template',array('email_slug'=>'forgot-password','language_slug'=>'en'))->first_row();        
                $arrayData = array('FirstName'=>$checkRecord->first_name,'ForgotPasswordLink'=>$confirmationLink);
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
                $this->email->to($this->post('Email'));      
                $this->email->subject($email_template->subject);  
                $this->email->message($EmailBody);            
                $this->email->send();
                // update verification code
                $addata = array('email_verification_code'=>$verificationCode);
                $this->api_model->updateUser('users',$addata,'entity_id',$checkRecord->entity_id); 
            }
            $this->response(['status' => 1,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('user_not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
        }
    }
    // Get CMS Pages
    public function getCMSPage_post()
    {    
        $this->getLang();
        $entity_id  = $this->post('cms_id');  
        $cmsData = $this->api_model->getCMSRecord('cms',$entity_id); 
        if ($cmsData)
        {
            $this->response([
                'cmsData'=>$cmsData,
                'status' => 1,
                'message' => $this->lang->line('found')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //add review
    public function addReview_post(){
        $this->getLang();
        if($this->post('rating') != '' && $this->post('review') != ''){
            $add_data = array(
                'rating'=>trim($this->post('rating')),
                'review'=>trim($this->post('review')),
                'restaurant_id'=>$this->post('restaurant_id'),
                'user_id'=>$this->post('user_id'),
                'order_user_id'=>($this->post('driver_id'))?$this->post('driver_id'):'',
                'status'=>1,
                'created_date'=>date('Y-m-d H:i:s')                
            );
            $this->api_model->addRecord('review', $add_data);
            $this->response(['status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //get restaurant
    public function getRestaurantDetail_post(){
        $this->getLang();
        if($this->post('restaurant_id')){
            $details = $this->api_model->getRestaurantDetail($this->post('content_id'),$this->current_lang);
            $item_image = $this->api_model->item_image($this->post('restaurant_id'),$this->current_lang);
            $popular_item = $this->api_model->getMenuItem($this->post('restaurant_id'),$this->post('food'),$this->post('price'),$this->current_lang,$popular = 1);
            $menu_item = $this->api_model->getMenuItem($this->post('restaurant_id'),$this->post('food'),$this->post('price'),$this->current_lang,$popular = 0);
            $review = $this->api_model->getRestaurantReview($this->post('restaurant_id'));
            $package = $this->api_model->getPackage($this->post('restaurant_id'),$this->current_lang);
            $this->response([
                'restaurant'=>$details,
                'item_image'=>$item_image,
                'popular_item'=>$popular_item,
                'menu_item'=>$menu_item,
                'review'=>$review,
                'package'=>$package,
                'status'=>1,
                'message' => $this->lang->line('found')], 
            REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    public function editProfile_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id =$this->post('user_id');
        $tokenusr = $this->api_model->checkToken($token, $user_id);
        if($tokenusr){
            $add_data =array(
                'first_name'=>$this->post('first_name'),
                'last_name'=>'',
                'notification'=>$this->post('notification'),
            );
            if (!empty($_FILES['image']['name']))
            {
                  $this->load->library('upload');
                  $config['upload_path'] = './uploads/profile';
                  $config['allowed_types'] = 'jpg|png|jpeg';
                  $config['encrypt_name'] = TRUE; 
                  // create directory if not exists
                  if (!@is_dir('uploads/profile')) {
                    @mkdir('./uploads/profile', 0777, TRUE);
                  }
                  $this->upload->initialize($config);                  
                  if ($this->upload->do_upload('image'))
                  {  
                    $img = $this->upload->data();
                    $add_data['image'] = "profile/".$img['file_name']; 
                  }
                  else
                  {
                    $data['Error'] = $this->upload->display_errors(); 
                    $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                  }  
            } 
            $this->api_model->updateUser('users',$add_data,'entity_id',$this->post('user_id'));
            $token = $this->api_model->checkToken($token, $user_id);
            $image = ($token->image)?image_url.$token->image:''; 
            $last_name = ($token->last_name)?$token->last_name:'';
            $login_detail = array('FirstName'=>$token->first_name,'LastName'=>$last_name,'image'=> $image ,'PhoneNumber'=>$token->mobile_number,'UserID'=>$token->entity_id,'notification'=>$token->notification);
            $this->response(['profile'=>$login_detail,'status'=>1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //package avalability
    public function bookingAvailable_post(){
        $this->getLang();
        if($this->post('booking_date') != '' && $this->post('people') != ''){
            $time = date('Y-m-d H:i:s',strtotime($this->post('booking_date')));
            $date = date('Y-m-d H:i:s');
            if(date('Y-m-d',strtotime($this->post('booking_date'))) == date('Y-m-d') && date($time) < date($date)){
                $this->response(['status'=>0,'message' => 'Time should be greater than current time'], REST_Controller::HTTP_OK); // OK      
            }else{
                $check = $this->api_model->getBookingAvailability($this->post('booking_date'),$this->post('people'),$this->post('restaurant_id'));
                if($check){
                   $this->response(['status'=>1,'message' => $this->lang->line('booking_available')], REST_Controller::HTTP_OK); // OK  
                }else{
                   $this->response(['status'=>0,'message' => $this->lang->line('booking_not_available')], REST_Controller::HTTP_OK); // OK  
                }  
            }
        }else{
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found'),
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //book event
    public function bookEvent_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
                if($this->post('booking_date') != '' && $this->post('people') != ''){
                    $add_data = array(                   
                        'name'=>$this->post('name'),
                        'no_of_people'=>$this->post('people'),
                        'booking_date'=>date('Y-m-d H:i:s',strtotime($this->post('booking_date'))),
                        'restaurant_id'=>$this->post('restaurant_id'),
                        'user_id'=>$this->post('user_id'),
                        'package_id'=>$this->post('package_id'),
                        'status'=>1,
                        'created_by' => $this->post('user_id'),
                        'event_status'=>'pending'
                    ); 
                    $event_id = $this->api_model->addRecord('event',$add_data); 
                    $users = array(
                        'first_name'=>$tokenres->first_name,
                        'last_name'=>($tokenres->last_name)?$tokenres->last_name:''
                    );
                    $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag="order");
                    $package = $this->api_model->getRecord('restaurant_package','entity_id',$this->post('package_id'));
                    $package_detail = '';
                    if(!empty($package)){
                        $package_detail = array(
                            'package_price'=>$package->price,
                            'package_name'=>$package->name,
                            'package_detail'=>$package->detail
                        );
                    }
                    $serialize_array = array(
                        'restaurant_detail'=>(!empty($taxdetail))?serialize($taxdetail):'',
                        'user_detail'=>(!empty($users))?serialize($users):'',
                        'package_detail'=>(!empty($package_detail))?serialize($package_detail):'',
                        'event_id'=>$event_id
                    );
                    $this->api_model->addRecord('event_detail',$serialize_array); 
                    $this->response(['status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK  
                }else{
                    $this->response(['status'=>0,'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK  
                }
        }
        else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code   
        }    
    }
    //get booking
    public function getBooking_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
            $data = $this->api_model->getBooking($user_id);
            $this->response(['upcoming_booking'=>$data['upcoming'],'past_booking'=>$data['past'],'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code    
        } 
    }
    //delete address
    public function deleteAddress_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
            $this->api_model->deleteRecord('user_address','entity_id',$this->post('address_id')); 
            $this->response(['status'=>1,'message' => $this->lang->line('record_deleted')], REST_Controller::HTTP_OK); // OK
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get recipe
    public function getrecipe_post(){
        $this->getLang();
        $searchItem = ($this->post('itemSearch'))?$this->post('itemSearch'):'';
        $food = $this->post('food');
        $timing = $this->post('timing');
        $popular_item = $this->api_model->getRecipe($searchItem,$food,$timing,$this->post('language_slug'));
        if($popular_item){
            $this->response(['items'=>$popular_item,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        }else{
             $this->response(['status'=>0,'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK  
        }
    }
    //delete booking
    public function deleteBooking_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);    
        if($tokenres){
            $this->api_model->deleteRecord('event','entity_id',$this->post('event_id'));
            $this->response(['status'=>1,'message' => $this->lang->line('record_deleted')], REST_Controller::HTTP_OK); // OK
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }

    public function addtoCart_post(){
        $this->getLang();
        $user_id = $this->post('user_id');
        $cart_id = $this->post('cart_id');
        $items = $this->post('items');
        $itemDetail = json_decode($items, true);

        $item = array();
        $subtotal = 0;
        $discount = 0;
        $total = 0;
        $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag='');
        $currencyDetails  = $this->api_model->getRestaurantCurrency($this->post('restaurant_id'));
        if(!empty($itemDetail)){
            foreach ($itemDetail['items'] as $key => $value) {
                $data = $this->api_model->checkExist($value['menu_id']);
                if(!empty($data)){
                    $image = ($data->image)?image_url.$data->image:''; 
                    $itemTotal = 0;
                    $priceRate = ($value['offer_price'])?str_replace('.', '', $value['offer_price']):str_replace('.', '', $data->price);
                    if($value['is_customize'] == 1){
                        $customization = array();
                        foreach ($value['addons_category_list'] as $k => $val) {
                            $addonscust = array();
                            foreach ($val['addons_list'] as $m => $mn) {
                               $add_ons_data = $this->api_model->getAddonsPrice($mn['add_ons_id']);
                                if($value['is_deal'] == 1){
                                    $addonscust[] = array(
                                        'add_ons_id'=>$mn['add_ons_id'],
                                        'add_ons_name'=>$add_ons_data->add_ons_name,
                                    );
                                    $price = ($value['offer_price'])?str_replace('.', '', $value['offer_price']):str_replace('.', '', $data->price);
                                    $subtotal = $subtotal + ($value['quantity'] * str_replace('.', '', $price));
                                }else{
                                    $addonscust[] = array(
                                        'add_ons_id'=>$mn['add_ons_id'],
                                        'add_ons_name'=>$add_ons_data->add_ons_name,
                                        'add_ons_price'=>str_replace('.', '', $add_ons_data->add_ons_price)
                                    );
                                    $itemTotal += $add_ons_data->add_ons_price;
                                    $subtotal = $subtotal + ($value['quantity'] * str_replace('.', '', $add_ons_data->add_ons_price));
                                }
                            }
                            $customization[] = array(
                                'addons_category_id'=>$val['addons_category_id'],
                                'addons_category'=>$val['addons_category'],
                                'addons_list'=>$addonscust
                            );
                        }
                        
                        if($itemTotal){
                            $itemTotal = ($value['quantity'])?$value['quantity'] * $itemTotal:'';
                        }else{
                            $itemTotal = ($priceRate && $value['quantity'])?$value['quantity'] * $priceRate:'';
                        }
                        $item[] = array('name'=>$data->name,
                            'image'=>$image,
                            'menu_id'=>$value['menu_id'],
                            'quantity'=>$value['quantity'],
                            'price'=>str_replace('.', '', $data->price),
                            'offer_price'=>($value['offer_price'])?str_replace('.', '', $value['offer_price']):'',
                            'is_veg'=>$data->is_veg,
                            'is_customize'=>1,
                            'is_deal'=>$value['is_deal'],
                            'itemTotal'=>$itemTotal,
                            'addons_category_list'=>$customization
                        );
                    }else{
                        $itemTotal = ($priceRate && $value['quantity'])?$value['quantity'] * $priceRate:'';
                        $item[] = array(
                            'name'=>$data->name,
                            'image'=>$image,
                            'menu_id'=>$value['menu_id'],
                            'quantity'=>$value['quantity'],
                            'price'=>str_replace('.', '', $data->price),
                            'offer_price'=>($value['offer_price'])?str_replace('.', '', $value['offer_price']):'',
                            'is_veg'=>$data->is_veg,
                            'is_customize'=>0,
                            'itemTotal'=>$itemTotal,
                            'is_deal'=>$value['is_deal']
                        );
                        $price = ($value['offer_price'])?str_replace('.', '', $value['offer_price']):str_replace('.', '', $data->price);
                        $subtotal = $subtotal + ($value['quantity'] * str_replace('.', '', $price));
                    } 
                }
            }
        }
        $messsage =  $this->lang->line('record_found');
        $status = 1;
        $subtotalCal = $subtotal;
        $deliveryPrice = '';
        if($this->post('order_delivery')=='Delivery'){ 
            //check delivery charge available
            $latitude = $this->post('latitude');
            $longitude = $this->post('longitude');
            $check = $this->checkGeoFence($latitude,$longitude,$price_charge = true,$this->post('restaurant_id'));
            if($check){ 
                $total = $subtotal + $check;
                $deliveryPrice = $check;
            }
            else{
                $total = $subtotal;
            }
        }else{ 
            $total = $subtotal;
        }
        $coupon_id = $coupon_amount = $coupon_type = $name  = $isApply = $coupon_discount = '';
        if($this->post('coupon')){
            $check = $this->api_model->checkCoupon($this->post('coupon'));
            if(!empty($check)){
                if(strtotime($check->end_date) > strtotime(date('Y-m-d H:i:s'))){
                    if($check->max_amount < $subtotal){ 
                        if($check->coupon_type == 'discount_on_cart'){
                            if($check->amount_type == 'Percentage'){
                                $discount = round(($subtotalCal * $check->amount)/100);
                               
                            }else if($check->amount_type == 'Amount'){
                                $discount = $check->amount;
                                
                            }
                            
                            $coupon_id = $check->entity_id;  
                            $coupon_type = $check->amount_type;
                            $coupon_amount = $check->amount;  
                            $coupon_discount = abs($discount);
                            $name = $check->name;     
                        }
                        if($check->coupon_type == 'free_delivery'){
                           
                            $discount = $deliveryPrice;

                            $coupon_id = $check->entity_id;  
                            $coupon_type = $check->amount_type;
                            $coupon_amount = $check->amount;  
                            $coupon_discount = abs($discount);
                            $name = $check->name;     
                        }
                        if($check->coupon_type == 'user_registration'){
                            $checkOrderCount = $this->api_model->checkUserCountCoupon($user_id);
                            if($checkOrderCount > 0){
                                $messsage = $this->lang->line('not_applied');
                                $status = 2;
                            }else{
                                if($check->amount_type == 'Percentage'){
                                    $discount = round(($subtotalCal * $check->amount)/100);
                                    
                                }else if($check->amount_type == 'Amount'){
                                    $discount = $check->amount;
                                }
                                 
                                $coupon_id = $check->entity_id;  
                                $coupon_type = $check->amount_type;
                                $coupon_amount = $check->amount;  
                                $coupon_discount = abs($discount);
                                $name = $check->name;     
                            }
                        }
                    }else{
                        $messsage = $this->lang->line('not_applied');
                        $status = 2;
                    }
                }else{
                    $messsage = $this->lang->line('coupon_expire');
                    $status = 2;
                }
            }else{
                $messsage = $this->lang->line('coupon_not_found');
                $status = 2;
            }
        }
        $total = $total - $discount;
       /* //get subtotal
        $text_amount = 0;
        if($taxdetail->amount_type == 'Percentage'){
            $text_amount = round(($subtotalCal * $taxdetail->amount) / 100);
        }else{
            $text_amount = $taxdetail->amount; 
        }
        $total = $total + $text_amount;*/
        
        /*$type = ($taxdetail->amount_type == 'Percentage')?'%':'';*/
        $discount = ($discount)?array('label'=>$this->lang->line('discount'),'value'=>abs($discount),'label_key'=>"Discount"):'';
        
        if($discount){
            $priceArray = array(
                array('label'=>$this->lang->line('sub_total'),'value'=>$subtotal,'label_key'=>"Sub Total"),
                $discount,
                ($deliveryPrice)?array('label'=>$this->lang->line('delivery_charge'),'value'=>$deliveryPrice,'label_key'=>"Delivery Charge"):'',
               /* array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),*/
                array('label'=>$this->lang->line('total'),'value'=>$total,'label_key'=>"Total")
            );
            $isApply = true;
        }else{
            $priceArray = array(
                array('label'=>$this->lang->line('sub_total'),'value'=>$subtotal,'label_key'=>"Sub Total"),
                ($deliveryPrice)?array('label'=>$this->lang->line('delivery_charge'),'value'=>$deliveryPrice,'label_key'=>"Delivery Charge"):'',
               /* array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),*/
                array('label'=>$this->lang->line('total'),'value'=>$total,'label_key'=>"Total")
            ); 
        }
        $add_data = array(
            'user_id'=>($user_id)?$user_id:'',
            'items'=> serialize($item),
            'restaurant_id'=>($this->post('restaurant_id'))?$this->post('restaurant_id'):''
        );
        if($cart_id == ''){
            $cart_id = $this->api_model->addRecord('cart_detail',$add_data);
        }else{
            $this->api_model->updateUser('cart_detail',$add_data,'cart_id',$cart_id);
        }
        $this->response([
        'total'=>$total,
        'cart_id'=>$cart_id,
        'items'=>$item,
        'price'=>$priceArray,
        'coupon_id'=>$coupon_id,
        'coupon_amount'=>($coupon_amount)?$coupon_amount:'',
        'coupon_type'=>$coupon_type,
        'coupon_name'=>$name,
        'coupon_discount'=>($coupon_discount)?$coupon_discount:'',
        'subtotal'=>$subtotal,
        'currency_code'=>$currencyDetails[0]->currency_code,
        'currency_symbol'=>$currencyDetails[0]->currency_symbol,
        'delivery_charge'=>($deliveryPrice)?$deliveryPrice:'',
        'is_apply'=>$isApply,
        'status'=>$status,
        'message' =>$messsage], REST_Controller::HTTP_OK); // OK  
    }
    //add address
    public function addAddress_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $address_id = $this->post('address_id');
            $add_data = array(
                'address'=>$this->post('address'),
                'landmark'=>$this->post('landmark'),
                'latitude'=>$this->post('latitude'),
                'longitude'=>$this->post('longitude'),
                'zipcode'=>$this->post('zipcode'),
                'city'=>$this->post('city'),
                'user_entity_id'=>$this->post('user_id')
            );
            if($address_id){
                $this->api_model->updateUser('user_address',$add_data,'entity_id',$address_id);
            }else{
                $address_id = $this->api_model->addRecord('user_address',$add_data);
            }
            $this->response(['address_id'=>$address_id,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get address
    public function getAddress_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $address = $this->api_model->getAddress('user_address','user_entity_id',$user_id);
            $this->response(['address'=>$address,'status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK  
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code       
        }
    }
    //change address
    public function changePassword_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            if(md5(SALT.$this->post('old_password')) == $tokenres->password){
                if($this->post('confirm_password') == $this->post('password')){
                    $this->db->set('password',md5(SALT.$this->post('password')));
                    $this->db->where('entity_id',$user_id);
                    $this->db->update('users');
                    $this->response(['status'=>1,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK  
                }else{
                    $this->response(['status'=>0,'message' => $this->lang->line('confirm_password')], REST_Controller::HTTP_OK); // OK  
                }
            }else{
                $this->response(['status'=>0,'message' => $this->lang->line('old_password')], REST_Controller::HTTP_OK); // OK  
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //add order
    public function addOrder_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag="order");
            $total = 0;
            $subtotal = str_replace('.', '', $this->post('subtotal'));   
            $add_data = array(              
                'user_id'=>$this->post('user_id'),
                'restaurant_id' =>$this->post('restaurant_id'),
                'address_id' =>$this->post('address_id'),
                'coupon_id' =>$this->post('coupon_id'),
                'order_status' =>'placed',
                'order_date' =>date('Y-m-d H:i:s',strtotime($this->post('order_date'))),
                'subtotal'=>$subtotal,
                'coupon_type'=>$this->post('coupon_type'),
                'coupon_amount'=>($this->post('coupon_amount'))?str_replace('.', '', $this->post('coupon_amount')):'',
                'total_rate' =>str_replace('.', '', $this->post('total')),
                'status'=>0,
                'coupon_discount'=>($this->post('coupon_discount'))?str_replace('.', '', $this->post('coupon_discount')):'',
                'delivery_charge'=>($this->post('delivery_charge'))?str_replace('.', '', $this->post('delivery_charge')):'',
                'extra_comment'=>$this->post('extra_comment'),
                'coupon_name'=>$this->post('coupon_name'),
            ); 
			if($this->post('order_delivery')=='Delivery'){
                $add_data['order_delivery'] = 'Delivery';
            } else {
                $add_data['order_delivery'] = 'PickUp';
            }  
            $order_id = $this->api_model->addRecord('order_master',$add_data);  
            //add items
            $items = $this->post('items');
            $itemDetail = json_decode($items,true);
            $add_item = array();
            
            if(!empty($itemDetail)){
                foreach ($itemDetail['items'] as $key => $value) {
                    if($value['is_customize'] == 1){
                        $customization = array();
                        foreach ($value['addons_category_list'] as $k => $val) {
                            $addonscust = array();
                            foreach ($val['addons_list'] as $m => $mn) {
                                if($value['is_deal'] == 1){
                                    $addonscust[] = array(
                                        'add_ons_id'=>$mn['add_ons_id'],
                                        'add_ons_name'=>$mn['add_ons_name'],
                                    );
                                }else{
                                    $addonscust[] = array(
                                        'add_ons_id'=>$mn['add_ons_id'],
                                        'add_ons_name'=>$mn['add_ons_name'],
                                        'add_ons_price'=>str_replace('.', '', $mn['add_ons_price'])
                                    );
                                }
                            }
                            $customization[] = array(
                                'addons_category_id'=>$val['addons_category_id'],
                                'addons_category'=>$val['addons_category'],
                                'addons_list'=>$addonscust
                            );
                        }
                       
                        $add_item[] = array(
                            "item_name"=>$value['name'],
                            "item_id"=>$value['menu_id'],
                            "qty_no"=>$value['quantity'],
                            "rate"=>($value['price'])?str_replace('.', '', $value['price']):'',
                            "offer_price"=>($value['offer_price'])?str_replace('.', '', $value['offer_price']):'',
                            "order_id"=>$order_id,
                            "is_customize"=>1,
                            "is_deal"=>$value['is_deal'],
                            "itemTotal"=>$value['itemTotal'],
                            "addons_category_list"=>$customization
                        );
                    }else{
                         $add_item[] = array(
                            "item_name"=>$value['name'],
                            "item_id"=>$value['menu_id'],
                            "qty_no"=>$value['quantity'],
                            "rate"=>($value['price'])?str_replace('.', '', $value['price']):'',
                            "offer_price"=>($value['offer_price'])?str_replace('.', '', $value['offer_price']):'',
                            "order_id"=>$order_id,
                            "is_customize"=>0,
                            "itemTotal"=>$value['itemTotal'],
                            "is_deal"=>$value['is_deal'],
                        );
                    } 
                }   
            }
            
            $address = $this->api_model->getAddress('user_address','entity_id',$this->post('address_id'));

            $user_detail = array(
                'first_name'=>$tokenres->first_name,
                'last_name'=>($tokenres->last_name)?$tokenres->last_name:'',
                'address'=>($address)?$address[0]->address:'',
                'landmark'=>($address)?$address[0]->landmark:'',
                'zipcode'=>($address)?$address[0]->zipcode:'',
                'city'=>($address)?$address[0]->city:'',
                'latitude'=>($address)?$address[0]->latitude:'',
                'longitude'=>($address)?$address[0]->longitude:'',
            );
            $order_detail = array(
                'order_id'=>$order_id,
                'user_detail' => serialize($user_detail),
                'item_detail' => serialize($add_item),
                'restaurant_detail' => serialize($taxdetail),
            );
            $this->api_model->addRecord('order_detail',$order_detail);
            $verificationCode = random_string('alnum',25);
            $email_template = $this->db->get_where('email_template',array('email_slug'=>'order-receive-alert','language_slug'=>'en','status'=>1))->first_row();                    
           
            $this->db->select('OptionValue');
            $FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

            $this->db->select('OptionValue');
            $FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();  
            if(!empty($email_template)){
                $this->load->library('email');  
                $config['charset'] = 'iso-8859-1';  
                $config['wordwrap'] = TRUE;  
                $config['mailtype'] = 'html';  
                $this->email->initialize($config);  
                $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);  
                $this->email->to(trim($taxdetail->email)); 
                $this->email->subject($email_template->subject);  
                $this->email->message($email_template->message);  
                $this->email->send();
            }
            $order_status = 'placed';
            $message = $this->lang->line('success_add');
            $this->response(['restaurant_detail'=>$taxdetail,'order_status'=>$order_status,'order_date'=>date('Y-m-d H:i:s',strtotime($this->post('order_date'))),'status'=>1,'message' => $message], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }  
    }
    //order detail
    public function orderDetail_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $result['in_process'] = $this->api_model->getOrderDetail('process',$user_id);  
            $result['past'] = $this->api_model->getOrderDetail('past',$user_id); 
            $this->response(['in_process'=>$result['in_process'],'past'=>$result['past'],'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }
    //get promocode list
    public function couponList_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if($tokenres){
            $subtotal = str_replace('.', '', $this->post('subtotal'));
            $coupon = $this->api_model->getcouponList($subtotal,$this->post('restaurant_id'),$this->post('order_delivery'));
            if(!empty($coupon)){
                $this->response([
                    'coupon_list'=>$coupon,
                    'status' => 1,
                    'message' =>$this->lang->line('record_found')
                ],  REST_Controller::HTTP_OK);
            }else{
                $this->response([
                'status' => 0,
                'message' => $this->lang->line('promocode')
                 ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }  
    }
    //get notification list
    function getNotification_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id); 
        if($tokenres){
            $notification = $this->api_model->getNotification($user_id,$this->post('count'),$this->post('page_no'));
            if(!empty($notification)){
                $this->response([
                    'notification'=>$notification['result'],
                    'status' => 1,
                    'notificaion_count'=>$notification['count'],
                    'message' =>$this->lang->line('record_found')
                ],  REST_Controller::HTTP_OK);
            }else{
                $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            } 
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }  
    }
    //check users order delivery
    public function checkOrderDelivery_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id); 
        if($tokenres){
            if($this->post('order_delivery') == 'Delivery'){
                $latitude = $this->post('latitude');
                $longitude = $this->post('longitude');
                $detail = $this->api_model->checkOrderDelivery($latitude,$longitude,$user_id,$this->post('restaurant_id'),$request = '',$order_id = '');
                
                //check delivery charge available
                $check = $this->checkGeoFence($latitude,$longitude,$price_charge = false,$this->post('restaurant_id'));
               
                if($detail && $check){
                    $this->response([
                        'status' => 1,
                        'message' => $this->lang->line('delivery_available')
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  
                }else{
                    $this->response([
                        'status' => 0,
                        'message' => $this->lang->line('delivery_not_available')
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
                }
            }else{
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('delivery_not_available')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code     
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get driver location
    public function driverTracking_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id); 
        if($tokenres){
            $order_id = $this->post('order_id');
            $detail = $this->api_model->getdriverTracking($order_id,$user_id);
            if($detail){
                $this->response([
                    'detail'=>$detail,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  
            }else{
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //check if order is delivered or not
    public function checkOrderDelivered_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id); 
        if($tokenres){
            $order_id = $this->post('order_id');
            $is_delivered = $this->post('is_delivered'); 
            if($is_delivered != 1){
                $this->db->set('order_status','pending')->where('entity_id', $order_id)->update('order_master');
                $add_data = array('order_id'=>$order_id,'order_status'=>'pending','time'=>date('Y-m-d H:i:s'),'status_created_by'=>'User');
                $this->api_model->addRecord('order_status',$add_data);
                $this->response([
                    'status' => 1,
                    'message' => $this->lang->line('success_update')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
            }else{
                $this->response([
                    'status' => 1,
                    'message' => $this->lang->line('success_update')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  
            }
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //Logout USER
    public function logout_post()
    {
        $token = $this->post('token');
        $userid = $this->post('user_id');        
        $tokenres = $this->api_model->getRecord('users', 'entity_id',$userid);
        if($tokenres){
            $data = array('device_id'=>"");            
            $this->api_model->updateUser('users',$data,'entity_id',$tokenres->entity_id);
            $this->response(['status' => 1,'message' => $this->lang->line('user_logout')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //check lat long exist in area
    public function checkGeoFence($latitude,$longitude,$price_charge,$restaurant_id)
    {
        $result = $this->api_model->checkGeoFence('delivery_charge','restaurant_id',$restaurant_id);
        $latlongs =  array($latitude,$longitude);
        $data = '';
        $oddNodes = false;
        $delivery_charge = '';
        foreach ($result as $key => $value) {
          
            $lat_longs = $value->lat_long;
            $lat_longs =  explode('~', $lat_longs);
            $polygon = array();
            foreach ($lat_longs as $key => $val) {
                if($val){
                    $val = str_replace(array('[',']'),array('',''),$val);
                    $polygon[] = explode(',', $val);
                }
            }
            if($polygon[0] != $polygon[count($polygon)-1])
                $polygon[count($polygon)] = $polygon[0];
            $j = 0;
            $x = $longitude;
            $y = $latitude;
            $n = count($polygon);
            for ( $i = 0; $i < $n; $i++)
            {
                $j++;
                if ($j == $n)
                {
                    $j = 0;
                }
                if ((($polygon[$i][0] < $y) && ($polygon[$j][0] >= $y)) || (($polygon[$j][0] < $y) && ($polygon[$i][0] >=$y)))
                {
                    if ($polygon[$i][1] + ($y - $polygon[$i][0]) / ($polygon[$j][0] - $polygon[$i][0]) * ($polygon[$j][1] -$polygon[$i][1]) < $x)
                    {
                        $oddNodes = true;
                        $delivery_charge = $value->price_charge;
                    }
                }
            } 
        }
        $oddNodes = ($price_charge)?$delivery_charge:$oddNodes;
        return $oddNodes;
    }
    //get user lang
    public function getUserLanguage_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id); 
        if($tokenres){
            $data = array('language_slug'=>$this->post('language_slug'));            
            $this->api_model->updateUser('users',$data,'entity_id',$user_id);
            $this->response(['status' => 1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
     //change firebase token
    public function changeToken_post()
    {
        $token = $this->post('token');
        $userid = $this->post('user_id');        
        $tokenres = $this->api_model->checkToken($token, $userid); 
        if($tokenres){
            $data = array('device_id'=>$this->post('firebase_token'));
            $this->api_model->updateUser('users',$data,'entity_id',$userid);  
            $this->response(['status' => 1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
}
