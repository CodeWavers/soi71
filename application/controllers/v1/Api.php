<?php
defined('BASEPATH') or exit('No direct script access allowed');
//error_reporting(-1);
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('v1/api_model');
        $this->load->library('form_validation');
        $this->current_lang = "en";
    }
    //common lang fucntion
    public function getLang()
    {
        $this->current_lang = ($this->post('language_slug')) ? $this->post('language_slug') : $this->current_lang;
        $languages = $this->api_model->getLanguages($this->current_lang);
        $this->lang->load('messages_lang', $languages->language_directory);
    }
    public function updateDefaultAddress_post()
    {
        $user_id = $this->post('user_id');
        $user_address_id = $this->post('user_address_id');
        $data = array('is_main' => 0);
        $this->api_model->updateUser('user_address', $data, 'user_entity_id', $user_id);
        $this->api_model->updateUser('user_address', array('is_main' => 1), 'entity_id', $user_address_id);

        $this->response(['status' => 1, 'message' => "Updated"], REST_Controller::HTTP_OK); // OK  
    }

    //get res name and address
    public function getResNameAddress_post()
    {

        $data = $this->api_model->getResName($this->post('res_id'));
        $delivery_charge= $this->api_model->getDeliveryArea();
        $this->response(['status' => 1, 'message' => "data found", 'data' => $data,'delivery_charge'=>$delivery_charge], REST_Controller::HTTP_OK);
    }
    public function getVersionNumber_post()
    {
        $ans = null;
        $os_type = $this->post("os_type");
        //$data = $this->api_model->getSystemOptoin("version");
        $android_versions = array('1.1.0', '1.1.1');
        $ios_versions = array('1.1.2', '2.0.1', '1.1.4', '1.1.5', '1.0.0');
        if (in_array($this->post('installed_version'), $os_type == "android" ? $android_versions : $ios_versions)) {
            $ans = true;
        } else {
            $ans = false;
        }

        $this->response([
            'status' => 1,
            'is_version_allowed' => true
            // 'force_logout'=> 'true'
        ], REST_Controller::HTTP_OK);
    }
    // Registration API
    public function registration_post()
    {
        $provider = 1;
        if ($this->post('provider') != null) {
            $provider = $this->post('provider');
        }

        if ($this->post('FirstName') != "" && $this->post('PhoneNumber') != "" && $this->post('Password') != "") {
            $checkRecord = $this->api_model->getRecord('users', 'mobile_number', $this->post('PhoneNumber'));
            if (empty($checkRecord)) {
                $addUser = array(
                    'mobile_number' => trim($this->post('PhoneNumber')),
                    'phone_code' => trim($this->post('phone_code')),
                    'first_name' => trim($this->post('FirstName')),
                    'last_name' => '',
                    'password' => $provider == 1 ?  md5(SALT . $this->post('Password')) :  NULL,
                    'user_type' => 'User',
                    'status' => 1,
                    'login_provider' => $provider,
                    'login_provider_id' => $this->post('providerId'),
                    'login_provider_detail' => $provider == 1 ? NULL : serialize(json_decode($this->post('providerDetail'), true)),
                );
                $UserID = $this->api_model->addRecord('users', $addUser);
                $login = $this->api_model->getRegisterRecord('users', $UserID);
                if ($UserID) {
                    $data = array('device_id' => $this->post('firebase_token'));
                    $this->api_model->updateUser('users', $data, 'entity_id', $UserID);
                    $this->response(['User' => $login, 'active' => false, 'status' => 1, 'message' => $this->lang->line('registration_success')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code 
                } else {
                    $this->response([
                        'status' => 0,

                        'message' => $this->lang->line('registration_fail')
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
                }
            } else {
                $this->response([
                    'status' => 0,
                    'userExists' => true,
                    'message' => $this->lang->line('user_exist')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
            }
        } else {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('regi_validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code                        
        }
    }
    // public function registration_post()
    // {
    //     $this->getLang();
    //     if($this->post('FirstName') !="" && $this->post('PhoneNumber') != "" && $this->post('Email') !="" && $this->post('Password') !="")
    //     {
    //         $checkRecord = $this->api_model->getRecord('users', 'mobile_number',$this->post('PhoneNumber'));
    //         $checkemail = $this->api_model->getRecord('users', 'email',$this->post('Email'));
    //         if(empty($checkRecord) && empty($checkemail))
    //         {   
    //             $addUser = array(
    //                 'mobile_number'=>trim($this->post('PhoneNumber')),
    //                 'first_name'=>trim($this->post('FirstName')),
    //                 'email'=>trim(strtolower($this->post('Email'))),
    //                 'password'=>md5(SALT.$this->post('Password')),
    //                 'last_name'=>'',
    //                 'user_type'=>'User',
    //                 'status'=>1                
    //             );
    //             $UserID = $this->api_model->addRecord('users', $addUser);
    //             $login = $this->api_model->getRegisterRecord('users',$UserID);
    //             if($UserID)
    //             {
    //                 $data = array('device_id'=>$this->post('firebase_token'));
    //                 $this->api_model->updateUser('users',$data,'entity_id',$UserID);
    //                 if($this->post('Email')){
    //                      // confirmation link
    //                     $verificationCode = random_string('alnum', 20).$UserID.random_string('alnum', 5);
    //                     $confirmationLink = '<a href='.base_url().'user/verify_account/'.$verificationCode.'>here</a>';   
    //                     $email_template = $this->db->get_where('email_template',array('email_slug'=>'verify-account','language_slug'=>'en'))->first_row();        
    //                     $arrayData = array('FirstName'=>$this->post('FirstName'),'ForgotPasswordLink'=>$confirmationLink);
    //                     $EmailBody = generateEmailBody($email_template->message,$arrayData);

    //                     //get System Option Data
    //                     $this->db->select('OptionValue');
    //                     $FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

    //                     $this->db->select('OptionValue');
    //                     $FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();

    //                     $this->load->library('email');  
    //                     $config['charset'] = "utf-8";
    //                     $config['mailtype'] = "html";
    //                     $config['newline'] = "\r\n";      
    //                     $this->email->initialize($config);  
    //                     $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);  
    //                     $this->email->to($this->post('Email'));      
    //                     $this->email->subject($email_template->subject);  
    //                     $this->email->message($EmailBody);
    //                     $this->email->send();


    //                     // update verification code
    //                     $addata = array('email_verification_code'=>$verificationCode);
    //                     $this->api_model->updateUser('users',$addata,'entity_id',$UserID);          
    //                 }
    //                 $this->response(['User' => $login,'active'=>false,'status'=>1,'message' => $this->lang->line('registration_success')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code 
    //             }
    //             else
    //             {
    //                 $this->response([
    //                     'status' => 0,
    //                     'message' => $this->lang->line('registration_fail')
    //                 ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
    //             }                        
    //         }
    //         else
    //         {
    //             $this->response([
    //                 'status' => 0,
    //                 'message' => $this->lang->line('user_exist')
    //             ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
    //         }
    //     }
    //     else
    //     {
    //         $this->response([
    //             'status' => 0,
    //             'message' => $this->lang->line('regi_validation')
    //         ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code                        
    //     }
    // }
    // Add Address
    public function addAddress_post()
    {
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $address_id = $this->post('address_id');
            $temp=$this->post('addressdetails');
          
            $add_data = array(
                'address' => $this->post('lebelName'),
                'landmark' => $this->post('landmark'),
                'latitude' => $this->post('latitude'),
                'longitude' => $this->post('longitude'),
                'zipcode' => $this->post('zipcode'),
                'city' => $this->post('city'),
                'address_detail'=>json_encode($temp),
                'user_entity_id' => $this->post('user_id')
            );
            if ($address_id) {
                $this->api_model->updateUser('user_address', $add_data, 'entity_id', $address_id);
            } else {
                $address_id = $this->api_model->addRecord('user_address', $add_data);
            }
            $this->response(['address_id' => $address_id, 'status' => 1, 'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    // Login API
    public function login_post()
    {
        $this->getLang();
        $provider = 1;
        if ($this->post('provider') != null) {
            $provider = $this->post('provider');
        }
        if ($provider == 1) {
            $login = $this->api_model->getLogin($this->post('PhoneNumber'), $this->post('Password'));
        } else {
            $login = $this->api_model->getLoginForProvider($provider, $this->post('providerId'));
        }

        if (!empty($login)) {
            if ($login->active == 1) {
                $data = array('active' => 1, 'device_id' => $this->post('firebase_token'));
                if ($login->status == 1) {
                    // update device 
                    $image = ($login->image) ? image_url . $login->image : '';
                    $this->api_model->updateUser('users', $data, 'entity_id', $login->entity_id);
                    //get rating
                    $rating = $this->api_model->getRatings($login->entity_id);
                    $review = (!empty($rating)) ? $rating->rating : '';

                    $last_name = ($login->last_name) ? $login->last_name : '';
                    $login_detail = array('FirstName' => $login->first_name, 'LastName' => $last_name, 'image' => $image, 'PhoneNumber' => $login->mobile_number, 'UserID' => $login->entity_id, 'notification' => $login->notification, 'rating' => $review, 'Email' => $login->email);
                    $this->response(['login' => $login_detail, 'status' => 1, 'active' => true, 'message' => $this->lang->line('login_success')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                } else if ($login->status == 0) {
                    $adminEmail = $this->api_model->getSystemOptoin('Admin_Email_Address');
                    $this->response(['status' => 2, 'message' => $this->lang->line('login_deactive'), 'email' => $adminEmail->OptionValue], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
                }
            } else {
                $this->response([
                    'status' => 0,
                    'active' => false,
                    'phoneNumber' => $login->mobile_number,
                    'message' => $this->lang->line('otp_inactive')
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $emailexist = $this->api_model->getRecord('users', 'mobile_number', $this->post('PhoneNumber'));
            if ($emailexist) {
                $this->response([
                    'status' => 0,
                    'notFound' => true,
                    'message' => $this->lang->line('pass_validation')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            } else {
                $this->response([
                    'status' => 0,
                    'notFound' => true,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }
    //verify OTP
    public function verifyOTP_post()
    {
        $this->getLang();
        $provider = 1;
        if ($this->post('provider') != null) {
            $provider = $this->post('provider');
        }
        if ($provider == 1) {
            $login = $this->api_model->getLogin($this->post('PhoneNumber'), $this->post('Password'));
        } else {
            $login = $this->api_model->getLoginWithPhoneOnly($this->post('PhoneNumber'));
        }
        if (!empty($login)) {
            if ($this->post('active') == 1) {
                $data = array('active' => 1);
                $this->api_model->updateUser('users', $data, 'entity_id', $login->entity_id);
                $image = ($login->image) ? image_url . $login->image : '';
                $last_name = ($login->last_name) ? $login->last_name : '';
                $login_detail = array('FirstName' => $login->first_name, 'LastName' => $last_name, 'image' => $image, 'PhoneNumber' => $login->mobile_number, 'UserID' => $login->entity_id, 'notification' => $login->notification);
                $this->response(['login' => $login_detail, 'active' => true, 'status' => 1, 'message' => $this->lang->line('success')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                $this->response([
                    'status' => 0,
                    'active' => false,
                    'message' => $this->lang->line('otp_inactive')
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //newItemSearch
    public function searchDetail_post()
    {
        $this->getLang();
        if ($this->post('restaurant_id')) {
            $cuisine_id = $this->post('cuisine_id');
            $menu_item = $this->api_model->searchMenuItem($this->post('restaurant_id'), $this->post('food'), $this->post('price'), $this->current_lang, $popular = 0, $this->post('searchText'), $cuisine_id);
            $details = $this->api_model->getRestaurantDetail($this->post('content_id'), $this->current_lang);

            $this->response(
                [
                    'restaurant' => $details,
                    // 'item_image'=>$item_image,
                    // 'popular_item'=>$popular_item,
                    'menu_item' => $menu_item,
                    // 'review'=>$review,
                    // 'package'=>$package,
                    'status' => 1,
                    'message' => $this->lang->line('found')
                ],
                REST_Controller::HTTP_OK
            ); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //get homepage
    public function getHome_post()
    {
        //for event
        $this->getLang();
        $language_slug = ($this->post('language_slug')) ? $this->post('language_slug') : '';
        if ($this->post('isEvent') == 1) {
            $latitude = ($this->post('latitude')) ? $this->post('latitude') : '';
            $longitude = ($this->post('longitude')) ? $this->post('longitude') : '';
            $searchItem = ($this->post('itemSearch')) ? $this->post('itemSearch') : '';
            $restaurant = $this->api_model->getEventRestaurant($latitude, $longitude, $searchItem, $this->current_lang, $this->post('count'), $this->post('page_no'));
            if (!empty($restaurant)) {
                $this->response([
                    'date' => date("Y-m-d g:i A"),
                    'restaurant' => $restaurant,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code 
            } else {
                $this->response([
                    'status' => 1,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
        } else { // for home page
            if ($this->post('latitude') != "" && $this->post('longitude') != "") {
                $food = $this->post('food');
                $rating = $this->post('rating');
                $distance = $this->post('distance');
                $searchItem = ($this->post('itemSearch')) ? $this->post('itemSearch') : '';
                $restaurant = $this->api_model->getHomeRestaurant($this->post('latitude'), $this->post('longitude'), $searchItem, $food, $rating, $distance, $this->current_lang, $this->post('count'), $this->post('page_no'));
                $slider = $this->api_model->getbanner();
                $feature_items = $this->api_model->getFeatureItems();
                $category = $this->api_model->getcategory($this->post('language_slug'));
                $this->response([
                    'date' => date("Y-m-d g:i A"),
                    'restaurant' => $restaurant,
                    'slider' => $slider,
                    'feature_items' => $feature_items,
                    'category' => $category,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')
                ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
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

        $checkRecord = $this->api_model->getRecordMultipleWhere('users', array('mobile_number' => $this->post('PhoneNumber'), 'status' => 1, 'login_provider' => 1));
        if (!empty($checkRecord)) {
            $activecode = substr(md5(uniqid(mt_rand(), true)), 0, 8);
            $password = random_string('alnum', 8);
            $data = array('active_code' => $activecode, 'password' => md5(SALT . $password));
            $this->api_model->updateUser('users', $data, 'mobile_number', $this->post('PhoneNumber'));
            $this->response(['status' => 1, 'password' => $password, 'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => 0,
                'message' => "User doesn't exist"
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
        }
    }
    public function updatePassword_post()
    {
        $checkRecord = $this->db->select('entity_id')->where('mobile_number', $this->post('PhoneNumber'))->get('users')->first_row();
        if (!empty($checkRecord)) {
            // $password = random_string('alnum', 8);
            if ($this->post('confirm_password') == $this->post('password')) {
                $pass = md5(SALT . $this->post('password'));
                $this->db->set('password', $pass);
                $this->db->where('entity_id', $checkRecord->entity_id);
                $this->db->update('users');
                $this->response(['status' => 1, 'phone' => $this->post('PhoneNumber'), 'entity_id' => $checkRecord->entity_id, 'password' => $this->post('password'), 'SaltedPassword' => md5(SALT . $this->post('password')), 'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK  
            } else {
                $this->response(['status' => 0, 'message' => $this->lang->line('confirm_password')], REST_Controller::HTTP_OK); // OK  
            }
        } else {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('user_not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
        }
    }
    // public function forgotpassword_post()
    // {
    //     $this->getLang();
    //     $checkRecord = $this->api_model->getRecordMultipleWhere('users', array('email'=>strtolower($this->post('Email')),'status'=>1));
    //     if(!empty($checkRecord))
    //     {
    //         // confirmation link
    //         if($this->post('Email')){
    //             $verificationCode = random_string('alnum', 20).$checkRecord->entity_id.random_string('alnum', 5);
    //             $confirmationLink = '<a href='.base_url().'user/reset/'.$verificationCode.'>here</a>';   
    //             $email_template = $this->db->get_where('email_template',array('email_slug'=>'forgot-password','language_slug'=>'en'))->first_row();        
    //             $arrayData = array('FirstName'=>$checkRecord->first_name,'ForgotPasswordLink'=>$confirmationLink);
    //             $EmailBody = generateEmailBody($email_template->message,$arrayData);


    //             //get System Option Data
    //             $this->db->select('OptionValue');
    //             $FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

    //             $this->db->select('OptionValue');
    //             $FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();

    //             $this->load->library('email');  
    //             $config['charset'] = "utf-8";
    //             $config['mailtype'] = "html";
    //             $config['newline'] = "\r\n";      
    //             $this->email->initialize($config);  
    //             $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);  
    //             $this->email->to($this->post('Email'));      
    //             $this->email->subject($email_template->subject);  
    //             $this->email->message($EmailBody);            
    //             $this->email->send();
    //             // update verification code
    //             $addata = array('email_verification_code'=>$verificationCode);
    //             $this->api_model->updateUser('users',$addata,'entity_id',$checkRecord->entity_id); 
    //         }
    //         $this->response(['status' => 1,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    //     }
    //     else
    //     {
    //         $this->response([
    //             'status' => 0,
    //             'message' => $this->lang->line('user_not_found')
    //         ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
    //     }
    // }
    // Get CMS Pages
    public function getCMSPage_post()
    {
        $this->getLang();
        $cms_slug  = $this->post('cms_slug');
        $cmsData = $this->api_model->getCMSRecord('cms', $cms_slug, $this->post('language_slug'));
        if ($cmsData) {
            $this->response([
                'cmsData' => $cmsData,
                'status' => 1,
                'message' => $this->lang->line('found')
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //add review
    public function addReview_post()
    {
        $this->getLang();
        if ($this->post('rating') != '' && $this->post('review') != '') {
            $add_data = array(
                'rating' => trim($this->post('rating')),
                'review' => trim($this->post('review')),
                'restaurant_id' => $this->post('restaurant_id'),
                'user_id' => $this->post('user_id'),
                'order_user_id' => ($this->post('driver_id')) ? $this->post('driver_id') : '',
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            $this->api_model->addRecord('review', $add_data);
            $this->response(['status' => 1, 'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //get restaurant
    public function getRestaurantDetail_post()
    {
        $this->getLang();
        $restaurant_id = $this->post('restaurant_id');
        $content_id = $this->post('content_id');
        if ($restaurant_id) {
            $cuisine_id = $this->post('cuisine_id');
            $details = $this->api_model->getRestaurantDetail($content_id, $this->current_lang);
            $item_image = $this->api_model->item_image($restaurant_id, $this->current_lang);
            $popular_item = $this->api_model->getMenuItem($restaurant_id, $this->post('food'), $this->post('price'), $this->current_lang, $popular = 1, $cuisine_id);
            $menu_item = $this->api_model->getMenuItem($restaurant_id, $this->post('food'), $this->post('price'), $this->current_lang, $popular = 0, $cuisine_id);
            $review = $this->api_model->getRestaurantReview($restaurant_id);
            $feature_items = $this->api_model->getFeatureItemsforDetails($restaurant_id);
            $package = $this->api_model->getPackage($restaurant_id, $this->current_lang);
            $this->response(
                [
                    'restaurant' => $details,
                    'item_image' => $item_image,
                    'feature_items' => $feature_items,
                    'popular_item' => $popular_item,
                    'menu_item' => $menu_item,
                    'review' => $review,
                    'package' => $package,
                    'status' => 1,
                    'message' => $this->lang->line('found')
                ],
                REST_Controller::HTTP_OK
            ); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    public function getallQuotes_post()
    {
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenusr = $this->api_model->checkToken($token, $user_id);
        if ($tokenusr) {
            $data = $this->api_model->getAllRecord('quotes', 'user_id', $user_id);
            foreach ($data as $key => $value) {
                $value->image1 = $value->image1 ? image_url . $value->image1 : '';
            }
            $this->response([
                'status' => 1,
                'data' =>  $data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => 0,
                'msg' =>  "un authenticated call"
            ], REST_Controller::HTTP_OK);
        }
    }

    public function addQuote_post()
    {
        if ($this->post('update') == true) {
            $field = $this->post('field');
            $id = $this->post('id');
            $add_data = array();
            if (!empty($_FILES['image']['name'])) {
                $this->load->library('upload');
                $config['upload_path'] = './uploads/quotes';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['encrypt_name'] = TRUE;
                // create directory if not exists
                if (!@is_dir('uploads/quotes')) {
                    @mkdir('./uploads/quotes', 0777, TRUE);
                }
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) {
                    $img = $this->upload->data();
                    $add_data[$field] = "quotes/" . $img['file_name'];
                } else {
                    $data['Error'] = $this->upload->display_errors();
                    $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                }
            }
            $data = $this->api_model->updateUser('quotes', $add_data, 'entity_id', $id);
            $this->response([
                'status' => 1,
                'id' =>  $id,
                'end' => $field == "image3" ? true : false,
                "FILES" => $_FILES

            ], REST_Controller::HTTP_OK);
        } else {
            $this->getLang();
            $token = $this->post('token');
            $user_id = $this->post('user_id');
            $tokenusr = $this->api_model->checkToken($token, $user_id);
            $lat =$this->post('lat');
            $long =$this->post('long');
            $resID =$this->post('resId');
            $restaurantAvail = $this->api_model->checkRestaurantAvailability($lat,$long,$user_id,$resID,$request = '',$order_id = '','','');
            if($restaurantAvail){
                if ($tokenusr) {
                    $add_data = array(
                        'user_id' => $this->post('user_id'),
                        'restaurant_id' =>  $resID,
                        'size' => $this->post('size'),
                        'flavour' => $this->post('flavour'),
                        'description' => $this->post('description'),
                        'delivery_time' => $this->post('date'),
                        'time_slot' => $this->post('time_slot'),
                        'address_id'=>$this->post('address_id')
                    );
                    if (!empty($_FILES['image']['name'])) {
                        $this->load->library('upload');
                        $config['upload_path'] = './uploads/quotes';
                        $config['allowed_types'] = 'jpg|png|jpeg';
                        $config['encrypt_name'] = TRUE;
                        // create directory if not exists
                        if (!@is_dir('uploads/quotes')) {
                            @mkdir('./uploads/quotes', 0777, TRUE);
                        }
                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('image')) {
                            $img = $this->upload->data();
                            $add_data['image1'] = "quotes/" . $img['file_name'];
                        } else {
                            $data['Error'] = $this->upload->display_errors();
                            $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                        }
                    }
    
    
                    $data = $this->api_model->addRecord('quotes', $add_data);
    
    
                    $this->response([
                        'status' => 1,
                        'id' =>  $data,
                        "FILES" => $_FILES,
                        'restaurantAvail'=>$restaurantAvail
    
                    ], REST_Controller::HTTP_OK);
                }
            }
            else{
                $this->response([
                    'status'=>1,
                    'restaurantAvail'=>$restaurantAvail,
                    'msg'=>"Delivery Service Is Not Available At Your Location"
                   
                ], REST_Controller::HTTP_OK);  
             }
            
        }
    }
    public function editProfile_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenusr = $this->api_model->checkToken($token, $user_id);
        if ($tokenusr) {
            $add_data = array(
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'notification' => $this->post('notification'),
            );
            if (!empty($_FILES['image']['name'])) {
                $this->load->library('upload');
                $config['upload_path'] = './uploads/profile';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['encrypt_name'] = TRUE;
                // create directory if not exists
                if (!@is_dir('uploads/profile')) {
                    @mkdir('./uploads/profile', 0777, TRUE);
                }
                $this->upload->initialize($config);
                if ($this->upload->do_upload('image')) {
                    $img = $this->upload->data();
                    $add_data['image'] = "profile/" . $img['file_name'];
                } else {
                    $data['Error'] = $this->upload->display_errors();
                    $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                }
            }
            $this->api_model->updateUser('users', $add_data, 'entity_id', $this->post('user_id'));
            $token = $this->api_model->checkToken($token, $user_id);
            $image = ($token->image) ? image_url . $token->image : '';
            $last_name = ($token->last_name) ? $token->last_name : '';
            $login_detail = array('FirstName' => $token->first_name, 'LastName' => $last_name, 'image' => $image, 'PhoneNumber' => $token->mobile_number, 'UserID' => $token->entity_id, 'notification' => $token->notification);
            $this->response(['profile' => $login_detail, 'status' => 1, 'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //package avalability
    public function bookingAvailable_post()
    {
        $this->getLang();
        if ($this->post('booking_date') != '' && $this->post('people') != '') {
            $time = date('Y-m-d H:i:s', strtotime($this->post('booking_date')));
            $date = date('Y-m-d H:i:s');
            if (date('Y-m-d', strtotime($this->post('booking_date'))) == date('Y-m-d') && date($time) < date($date)) {
                $this->response(['status' => 0, 'message' => $this->lang->line('greater_than_current_time')], REST_Controller::HTTP_OK); // OK      
            } else {
                $check = $this->api_model->getBookingAvailability($this->post('booking_date'), $this->post('people'), $this->post('restaurant_id'));
                if ($check) {
                    $this->response(['status' => 1, 'message' => $this->lang->line('booking_available')], REST_Controller::HTTP_OK); // OK  
                } else {
                    $this->response(['status' => 0, 'message' => $this->lang->line('booking_not_available')], REST_Controller::HTTP_OK); // OK  
                }
            }
        } else {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('not_found'),
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //book event
    public function bookEvent_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            if ($this->post('booking_date') != '' && $this->post('people') != '') {
                $add_data = array(
                    'name' => $this->post('name'),
                    'no_of_people' => $this->post('people'),
                    'booking_date' => date('Y-m-d H:i:s', strtotime($this->post('booking_date'))),
                    'restaurant_id' => $this->post('restaurant_id'),
                    'user_id' => $this->post('user_id'),
                    'package_id' => $this->post('package_id'),
                    'status' => 1,
                    'created_by' => $this->post('user_id'),
                    'event_status' => 'pending'
                );
                $event_id = $this->api_model->addRecord('event', $add_data);
                $users = array(
                    'first_name' => $tokenres->first_name,
                    'last_name' => ($tokenres->last_name) ? $tokenres->last_name : ''
                );
                $taxdetail = $this->api_model->getRestaurantTax('restaurant', $this->post('restaurant_id'), $flag = "order");
                $package = $this->api_model->getRecord('restaurant_package', 'entity_id', $this->post('package_id'));
                $package_detail = '';
                if (!empty($package)) {
                    $package_detail = array(
                        'package_price' => $package->price,
                        'package_name' => $package->name,
                        'package_detail' => $package->detail
                    );
                }
                $serialize_array = array(
                    'restaurant_detail' => (!empty($taxdetail)) ? serialize($taxdetail) : '',
                    'user_detail' => (!empty($users)) ? serialize($users) : '',
                    'package_detail' => (!empty($package_detail)) ? serialize($package_detail) : '',
                    'event_id' => $event_id
                );
                $this->api_model->addRecord('event_detail', $serialize_array);
                $this->response(['status' => 1, 'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK  
            } else {
                $this->response(['status' => 0, 'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK  
            }
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code   
        }
    }
    //get booking
    public function getBooking_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $data = $this->api_model->getBooking($user_id);
            $this->response(['upcoming_booking' => $data['upcoming'], 'past_booking' => $data['past'], 'status' => 1, 'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code    
        }
    }
    //delete address
    public function deleteAddress_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $this->api_model->deleteRecord('user_address', 'entity_id', $this->post('address_id'));
            $this->response(['status' => 1, 'message' => $this->lang->line('record_deleted')], REST_Controller::HTTP_OK); // OK
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    public function getSetAppleLogin_post()
    {
        $add_data = array(
            'user_id' => $this->post('userId'),
            'full_name' => $this->post('fullName'),
            'email' => $this->post('email'),
        );
        $appleLogin = $this->api_model->getRecord('apple_login', 'user_id', $this->post('userId'));
        if (!empty($appleLogin)) {
            $this->response(['appleLogin' => $appleLogin, 'status' => 1, 'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        } else {
            $id = $this->api_model->addRecord('apple_login', $add_data);
            $appleLoginFromDb = $this->api_model->getRecord('apple_login', 'entity_id', $id);
            $this->response(['appleLogin' => $appleLoginFromDb, 'status' => 1, 'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        }
    }
    //get recipe
    public function getRecipe_post()
    {
        $this->getLang();
        $searchItem = ($this->post('itemSearch')) ? $this->post('itemSearch') : '';
        $food = $this->post('food');
        $timing = $this->post('timing');
        $popular_item = $this->api_model->getRecipe($searchItem, $food, $timing, $this->post('language_slug'));
        if ($popular_item) {
            $this->response(['items' => $popular_item, 'status' => 1, 'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
        } else {
            $this->response(['status' => 0, 'message' => $this->lang->line('not_found')], REST_Controller::HTTP_OK); // OK  
        }
    }
    //delete booking
    public function deleteBooking_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $this->api_model->deleteRecord('event', 'entity_id', $this->post('event_id'));
            $this->response(['status' => 1, 'message' => $this->lang->line('record_deleted')], REST_Controller::HTTP_OK); // OK
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get Adress List
    public function getAddress_post()
    {
        $flavour=[];
        if($this->post('fetchFlavour')==1)
        {
             $flavour = $this->api_model->getFlavour();
        }
      
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $address = $this->api_model->getAddress('user_address', 'user_entity_id', $user_id);
            $this->response(['address' => $address,'flavour'=>$flavour, 'status' => 1, 'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK  
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code       
        }
    }
    /* public function addtoCart_post(){
        $this->getLang();
        $user_id = $this->post('user_id');
        $cart_id = $this->post('cart_id');
        $items = $this->post('items');
        $itemDetail = json_decode($items, true);
        $item_number=count($items);
        $item = array();
        $subtotal = 0;
        $discount = 0;
        $discount_rate=0;
        $discount_amount=0;
        $discount_delivery=0;
        $total = 0;
        $vat_total=0;
        $sd_total=0;
        $coupon_id = $coupon_amount = $coupon_type = $name  = $isApply = $coupon_discount = '';
        $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag='');
        $currencyDetails  = $this->api_model->getRestaurantCurrency($this->post('restaurant_id'));
        if(!empty($itemDetail)){
                 if($this->post('coupon')){
            $check = $this->api_model->checkCoupon($this->post('coupon'));
            if(!empty($check)){
                if(strtotime($check->end_date) > strtotime(date('Y-m-d H:i:s'))){
                    
                        if($check->coupon_type == 'discount_on_cart'){
                            if($check->amount_type == 'Percentage'){
                                $discount_rate = ($check->amount)/100;
                               
                            }else if($check->amount_type == 'Amount'){
                                $discount_amount = $check->amount/$item_number;
                                
                            }
                            
                            $coupon_id = $check->entity_id;  
                            $coupon_type = $check->amount_type;
                            $coupon_amount = $check->amount;  
                            //$coupon_discount = abs($discount);
                            $name = $check->name;     
                        }
                        if($check->coupon_type == 'free_delivery'){
                           
                            $discount_delivery = $deliveryPrice;

                            $coupon_id = $check->entity_id;  
                            $coupon_type = $check->amount_type;
                            $coupon_amount = $check->amount;  
                            //$coupon_discount = abs($discount);
                            $name = $check->name;     
                        }
                        if($check->coupon_type == 'user_registration'){
                            $checkOrderCount = $this->api_model->checkUserCountCoupon($user_id);
                            if($checkOrderCount > 0){
                                $messsage = $this->lang->line('not_applied');
                                $status = 2;
                            }else{
                                if($check->amount_type == 'Percentage'){
                                   $discount_rate = ($check->amount)/100;
                                    
                                }else if($check->amount_type == 'Amount'){
                                   $discount_amount = $check->amount/$item_number;
                                }
                                 
                                $coupon_id = $check->entity_id;  
                                $coupon_type = $check->amount_type;
                                $coupon_amount = $check->amount;  
                               // $coupon_discount = abs($discount);
                                $name = $check->name;     
                            }
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
            foreach ($itemDetail['items'] as $key => $value) {
                $data = $this->api_model->checkExist($value['menu_id']);
                if(!empty($data)){
                    $image = ($data->image)?image_url.$data->image:''; 
                    $itemTotal = 0;
                    $priceRate = ($value['offer_price'])?$value['offer_price']:$data->price;
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
                                    $price = ($value['offer_price'])?$value['offer_price']:$data->price;
                                   // $subtotal = $subtotal + ($value['quantity'] * $price);
                                    if($discount_rate>0)
                                    {
                                    $less=($value['quantity'] * $price*$discount_rate);
                                    $discounted_total=($value['quantity'] * $price)-$less;
                                    $subtotal=$subtotal+$discounted_total;
                                    $discount=$discount+$less;
                                    $SD=(($discounted_total* $data->sd)/100);
                                    $sd_add=$discounted_total+$SD;
                                    $sd_total=$sd_total+$SD;
                                    $VAT=(($sd_add*$data->vat)/100);
                                    $vat_total=$vat_total+$VAT;
                                    

                                    }
                                    else if($discount_amount>0)
                                    {
                                    $discounted_total=($value['quantity'] * $price)-$discount_amount;
                                    $subtotal=$subtotal+$discounted_total;
                                    $discount=$discount+$less;
                                    $SD=(($discounted_total* $data->sd)/100);
                                    $sd_add=$discounted_total+$SD;
                                    $sd_total=$sd_total+$SD;
                                    $VAT=(($sd_add*$data->vat)/100);
                                    $vat_total=$vat_total+$VAT;
                                    }
                                    else{
                                         $initial_total=($value['quantity'] * $price);
                                         $subtotal=$subtotal+$initial_total;
                                         $SD=(($initial_total* $data->sd)/100);
                                         $sd_add=$initial_total+$SD;
                                         $sd_total=$sd_total+$SD;
                                         $VAT=(($sd_add*$data->vat)/100);
                                         $vat_total=$$vat_total+$VAT;
                                         

                                    }
                                }else{
                                    $addonscust[] = array(
                                        'add_ons_id'=>$mn['add_ons_id'],
                                        'add_ons_name'=>$add_ons_data->add_ons_name,
                                        'add_ons_price'=>$add_ons_data->add_ons_price
                                    );
                                    $itemTotal += $add_ons_data->add_ons_price;
                                 //   $subtotal = $subtotal + ($value['quantity'] * $add_ons_data->add_ons_price);
                                     if($discount_rate>0)
                                    {
                                    $less=($value['quantity'] * $add_ons_data->add_ons_price *$discount_rate);
                                    $discounted_total=($value['quantity'] * $add_ons_data->add_ons_price )-$less;
                                    $subtotal=$subtotal+$discounted_total;
                                    $discount=$discount+$less;
                                    $SD=(($discounted_total* $data->sd)/100);
                                    $sd_add=$discounted_total+$SD;
                                    $sd_total=$sd_total+$SD;
                                    $VAT=(($sd_add*$data->vat)/100);
                                    $vat_total=$vat_total+$VAT;
                                    }
                                    else if($discount_amount>0)
                                    {
                                    $discounted_total=($value['quantity'] * $add_ons_data->add_ons_price)-$discount_amount;
                                    $subtotal=$subtotal+$discounted_total;
                                    $discount=$discount+$less;
                                    $SD=(($discounted_total* $data->sd)/100);
                                    $sd_add=$discounted_total+$SD;
                                    $sd_total=$sd_total+$SD;
                                    $VAT=(($sd_add*$data->vat)/100);
                                    $vat_total=$vat_total+$VAT;
                                    }
                                    else{
                                         $initial_total=($value['quantity'] * $add_ons_data->add_ons_price);
                                         $subtotal=$subtotal+$initial_total;
                                         $SD=(($initial_total* $data->sd)/100);
                                         $sd_add=$initial_total+$SD;
                                         $sd_total=$sd_total+$SD;
                                         $VAT=(($sd_add*$data->vat)/100);
                                         $vat_total=$$vat_total+$VAT;
                                        
                                    }
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
                            'price'=>$data->price,
                            'offer_price'=>($value['offer_price'])?$value['offer_price']:'',
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
                            'price'=>$data->price,
                            'offer_price'=>($value['offer_price'])?$value['offer_price']:'',
                            'is_veg'=>$data->is_veg,
                            'is_customize'=>0,
                            'itemTotal'=>$itemTotal,
                            'is_deal'=>$value['is_deal']
                        );
                        $price = ($value['offer_price'])?$value['offer_price']:$data->price;
                      //  $subtotal = $subtotal + ($value['quantity'] * $price);
                         if($discount_rate>0)
                                    {
                                    $less=($value['quantity'] * $price*$discount_rate);
                                    $discounted_total=($value['quantity'] * $price)-$less;
                                    $subtotal=$subtotal+$discounted_total;
                                    $discount=$discount+$less;
                                    $SD=(($discounted_total* $data->sd)/100);
                                    $sd_add=$discounted_total+$SD;
                                    $sd_total=$sd_total+$SD;
                                    $VAT=(($sd_add*$data->vat)/100);
                                    $vat_total=$vat_total+$VAT;

                                    }
                                    else if($discount_amount>0)
                                    {
                                    $discounted_total=($value['quantity'] * $price)-$discount_amount;
                                    $subtotal=$subtotal+$discounted_total;
                                    $discount=$discount+$less;
                                    $SD=(($discounted_total* $data->sd)/100);
                                    $sd_add=$discounted_total+$SD;
                                    $sd_total=$sd_total+$SD;
                                    $VAT=(($sd_add*$data->vat)/100);
                                    $vat_total=$vat_total+$VAT;
                                    }
                                    else{
                                         $initial_total=($value['quantity'] * $price);
                                         $subtotal=$subtotal+$initial_total;
                                         $SD=(($initial_total* $data->sd)/100);
                                         $sd_add=$initial_total+$SD;
                                         $sd_total=$sd_total+$SD;
                                         $VAT=(($sd_add*$data->vat)/100);
                                         $vat_total=$$vat_total+$VAT;
                                        
                                    }
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
                if($discount_delivery>0){
                 $total = $subtotal +$vat_total+$sd_total;
                  $deliveryPrice = 0;
                }
                else{
                $total = $subtotal + $check+$vat_total+$sd_total;
                $deliveryPrice = $check;
            }
            }
            else{
               $subtotal + $check+$vat_total+$sd_totall;
            }
        }else{ 
            $total = $subtotal + $check+$vat_total+$sd_total;
        }
        
   
      
        $discount = ($discount)?array('label'=>$this->lang->line('discount'),'value'=>abs($discount),'label_key'=>"Discount"):'';
        
        if($discount){
            $priceArray = array(
                array('label'=>$this->lang->line('sub_total'),'value'=>$subtotal,'label_key'=>"Sub Total"),
                $discount,
                 array('label'=>$this->lang->line('SD'),'value'=>$sd_total,'label_key'=>"SD"),
                  array('label'=>$this->lang->line('VAT'),'value'=>$vat_total,'label_key'=>"VAT"),
                ($deliveryPrice)?array('label'=>$this->lang->line('delivery_charge'),'value'=>$deliveryPrice,'label_key'=>"Delivery Charge"):'',
               //array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),
                array('label'=>$this->lang->line('total'),'value'=>$total,'label_key'=>"Total")
            );
            $isApply = true;
        }else{
            $priceArray = array(
                array('label'=>$this->lang->line('sub_total'),'value'=>$subtotal,'label_key'=>"Sub Total"),
                ($deliveryPrice)?array('label'=>$this->lang->line('delivery_charge'),'value'=>$deliveryPrice,'label_key'=>"Delivery Charge"):'',
                 array('label'=>$this->lang->line('SD'),'value'=>$sd_total,'label_key'=>"SD"),
                  array('label'=>$this->lang->line('VAT'),'value'=>$vat_total,'label_key'=>"VAT"),
                //array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),
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
    }*/
    //add address
    // public function addAddress_post(){
    //     $this->getLang();
    //     $token = $this->post('token');
    //     $user_id = $this->post('user_id');
    //     $tokenres = $this->api_model->checkToken($token, $user_id);
    //     if($tokenres){
    //         $address_id = $this->post('address_id');
    //         $add_data = array(
    //             'address'=>$this->post('address'),
    //             'landmark'=>$this->post('landmark'),
    //             'latitude'=>$this->post('latitude'),
    //             'longitude'=>$this->post('longitude'),
    //             'zipcode'=>$this->post('zipcode'),
    //             'city'=>$this->post('city'),
    //             'user_entity_id'=>$this->post('user_id')
    //         );
    //         if($address_id){
    //             $this->api_model->updateUser('user_address',$add_data,'entity_id',$address_id);
    //         }else{
    //             $address_id = $this->api_model->addRecord('user_address',$add_data);
    //         }
    //         $this->response(['address_id'=>$address_id,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK  
    //     }else{
    //         $this->response([
    //             'status' => -1,
    //             'message' => ''
    //         ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
    //     }
    // }
    // public function addtoCart_post(){
    //         $this->getLang();
    //         $user_id = $this->post('user_id');
    //         $cart_id = $this->post('cart_id');
    //         $items = $this->post('items');
    //         $itemDetail = json_decode($items, true);
    //         $item_number=count($items);

    //         $item = array();
    //         $subtotal = 0;
    //         $discount = 0;
    //         $discount_rate=0;
    //         $discount_amount=0;
    //         $discount_delivery=0;
    //         $total = 0;
    //         $vat_total=0;
    //         $sd_total=0;
    //         $coupon_id = $coupon_amount = $coupon_type = $name  = $isApply = $coupon_discount = '';
    //         $taxdetail = $this->api_model->getRestaurantTax('restaurant',$this->post('restaurant_id'),$flag='');
    //         $currencyDetails  = $this->api_model->getRestaurantCurrency($this->post('restaurant_id'));
    //         if(!empty($itemDetail)){
    //                  if($this->post('coupon')){
    //             $check = $this->api_model->checkCoupon($this->post('coupon'));
    //             if(!empty($check)){
    //                 if(strtotime($check->end_date) > strtotime(date('Y-m-d H:i:s'))){

    //                         if($check->coupon_type == 'discount_on_cart'){
    //                             if($check->amount_type == 'Percentage'){
    //                                 $discount_rate = ($check->amount)/100;

    //                             }else if($check->amount_type == 'Amount'){
    //                                 $discount_amount = $check->amount/$item_number;

    //                             }

    //                             $coupon_id = $check->entity_id;  
    //                             $coupon_type = $check->amount_type;
    //                             $coupon_amount = $check->amount;  
    //                             //$coupon_discount = abs($discount);
    //                             $name = $check->name;     
    //                         }
    //                         if($check->coupon_type == 'free_delivery'){

    //                             $discount_delivery = $deliveryPrice;

    //                             $coupon_id = $check->entity_id;  
    //                             $coupon_type = $check->amount_type;
    //                             $coupon_amount = $check->amount;  
    //                             //$coupon_discount = abs($discount);
    //                             $name = $check->name;     
    //                         }
    //                         if($check->coupon_type == 'user_registration'){
    //                             $checkOrderCount = $this->api_model->checkUserCountCoupon($user_id);
    //                             if($checkOrderCount > 0){
    //                                 $messsage = $this->lang->line('not_applied');
    //                                 $status = 2;
    //                             }else{
    //                                 if($check->amount_type == 'Percentage'){
    //                                   $discount_rate = ($check->amount)/100;

    //                                 }else if($check->amount_type == 'Amount'){
    //                                   $discount_amount = $check->amount/$item_number;
    //                                 }

    //                                 $coupon_id = $check->entity_id;  
    //                                 $coupon_type = $check->amount_type;
    //                                 $coupon_amount = $check->amount;  
    //                               // $coupon_discount = abs($discount);
    //                                 $name = $check->name;     
    //                             }
    //                         }

    //                 }else{
    //                     $messsage = $this->lang->line('coupon_expire');
    //                     $status = 2;
    //                 }
    //             }else{
    //                 $messsage = $this->lang->line('coupon_not_found');
    //                 $status = 2;
    //             }
    //         }
    //             foreach ($itemDetail['items'] as $key => $value) {
    //                 $data = $this->api_model->checkExist($value['menu_id']);
    //                 if(!empty($data)){
    //                     $image = ($data->image)?image_url.$data->image:''; 
    //                     $itemTotal = 0;
    //                     $priceRate = ($value['offer_price'])?$value['offer_price']:$data->price;
    //                     if($value['is_customize'] == 1){
    //                         $customization = array();
    //                         foreach ($value['addons_category_list'] as $k => $val) {
    //                             $addonscust = array();
    //                             foreach ($val['addons_list'] as $m => $mn) {
    //                               $add_ons_data = $this->api_model->getAddonsPrice($mn['add_ons_id']);
    //                                 if($value['is_deal'] == 1){
    //                                     $addonscust[] = array(
    //                                         'add_ons_id'=>$mn['add_ons_id'],
    //                                         'add_ons_name'=>$add_ons_data->add_ons_name,
    //                                     );
    //                                     $price = ($value['offer_price'])?$value['offer_price']:$data->price;
    //                                   // $subtotal = $subtotal + ($value['quantity'] * $price);
    //                                     if($discount_rate>0)
    //                                     {
    //                                     $less=($value['quantity'] * $price*$discount_rate);
    //                                     $discounted_total=($value['quantity'] * $price)-$less;
    //                                     $subtotal=$subtotal+$discounted_total;
    //                                     $discount=$discount+$less;
    //                                     $SD=(($discounted_total* $data->sd)/100);
    //                                     $sd_add=$discounted_total+$SD;
    //                                     $sd_total=$sd_total+$SD;
    //                                     $VAT=(($sd_add*$data->vat)/100);
    //                                     $vat_total=$vat_total+$VAT;


    //                                     }
    //                                     else if($discount_amount>0)
    //                                     {
    //                                     $discounted_total=($value['quantity'] * $price)-$discount_amount;
    //                                     $subtotal=$subtotal+$discounted_total;
    //                                     $discount=$discount+$discount_amount;
    //                                     $SD=(($discounted_total* $data->sd)/100);
    //                                     $sd_add=$discounted_total+$SD;
    //                                     $sd_total=$sd_total+$SD;
    //                                     $VAT=(($sd_add*$data->vat)/100);
    //                                     $vat_total=$vat_total+$VAT;
    //                                     }
    //                                     else{
    //                                          $initial_total=($value['quantity'] * $price);
    //                                          $subtotal=$subtotal+$initial_total;
    //                                          $SD=(($initial_total* $data->sd)/100);
    //                                          $sd_add=$initial_total+$SD;
    //                                          $sd_total=$sd_total+$SD;
    //                                          $VAT=(($sd_add*$data->vat)/100);
    //                                          $vat_total=$vat_total+$VAT;


    //                                     }
    //                                 }else{
    //                                     $addonscust[] = array(
    //                                         'add_ons_id'=>$mn['add_ons_id'],
    //                                         'add_ons_name'=>$add_ons_data->add_ons_name,
    //                                         'add_ons_price'=>$add_ons_data->add_ons_price
    //                                     );
    //                                     $itemTotal += $add_ons_data->add_ons_price;
    //                                  //   $subtotal = $subtotal + ($value['quantity'] * $add_ons_data->add_ons_price);
    //                                      if($discount_rate>0)
    //                                     {
    //                                     $less=($value['quantity'] * $add_ons_data->add_ons_price *$discount_rate);
    //                                     $discounted_total=($value['quantity'] * $add_ons_data->add_ons_price )-$less;
    //                                     $subtotal=$subtotal+$discounted_total;
    //                                     $discount=$discount+$less;
    //                                     $SD=(($discounted_total* $data->sd)/100);
    //                                     $sd_add=$discounted_total+$SD;
    //                                     $sd_total=$sd_total+$SD;
    //                                     $VAT=(($sd_add*$data->vat)/100);
    //                                     $vat_total=$vat_total+$VAT;
    //                                     }
    //                                     else if($discount_amount>0)
    //                                     {
    //                                     $discounted_total=($value['quantity'] * $add_ons_data->add_ons_price)-$discount_amount;
    //                                     $subtotal=$subtotal+$discounted_total;
    //                                     $discount=$discount+$discount_amount;
    //                                     $SD=(($discounted_total* $data->sd)/100);
    //                                     $sd_add=$discounted_total+$SD;
    //                                     $sd_total=$sd_total+$SD;
    //                                     $VAT=(($sd_add*$data->vat)/100);
    //                                     $vat_total=$vat_total+$VAT;
    //                                     }
    //                                     else{
    //                                          $initial_total=($value['quantity'] * $add_ons_data->add_ons_price);
    //                                          $subtotal=$subtotal+$initial_total;
    //                                          $SD=(($initial_total* $data->sd)/100);
    //                                          $sd_add=$initial_total+$SD;
    //                                          $sd_total=$sd_total+$SD;
    //                                          $VAT=(($sd_add*$data->vat)/100);
    //                                          $vat_total=$vat_total+$VAT;

    //                                     }
    //                                 }
    //                             }
    //                             $customization[] = array(
    //                                 'addons_category_id'=>$val['addons_category_id'],
    //                                 'addons_category'=>$val['addons_category'],
    //                                 'addons_list'=>$addonscust
    //                             );
    //                         }

    //                         if($itemTotal){
    //                             $itemTotal = ($value['quantity'])?$value['quantity'] * $itemTotal:'';
    //                         }else{
    //                             $itemTotal = ($priceRate && $value['quantity'])?$value['quantity'] * $priceRate:'';
    //                         }
    //                         $item[] = array('name'=>$data->name,
    //                             'image'=>$image,
    //                             'menu_id'=>$value['menu_id'],
    //                             'quantity'=>$value['quantity'],
    //                             'price'=>$data->price,
    //                             'offer_price'=>($value['offer_price'])?$value['offer_price']:'',
    //                             'is_veg'=>$data->is_veg,
    //                             'is_customize'=>1,
    //                             'is_deal'=>$value['is_deal'],
    //                             'itemTotal'=>$itemTotal,
    //                             'addons_category_list'=>$customization
    //                         );
    //                     }else{
    //                         $itemTotal = ($priceRate && $value['quantity'])?$value['quantity'] * $priceRate:'';
    //                         $item[] = array(
    //                             'name'=>$data->name,
    //                             'image'=>$image,
    //                             'menu_id'=>$value['menu_id'],
    //                             'quantity'=>$value['quantity'],
    //                             'price'=>$data->price,
    //                             'offer_price'=>($value['offer_price'])?$value['offer_price']:'',
    //                             'is_veg'=>$data->is_veg,
    //                             'is_customize'=>0,
    //                             'itemTotal'=>$itemTotal,
    //                             'is_deal'=>$value['is_deal']
    //                         );
    //                         $price = ($value['offer_price'])?$value['offer_price']:$data->price;
    //                       //  $subtotal = $subtotal + ($value['quantity'] * $price);
    //                          if($discount_rate>0)
    //                                     {
    //                                     $less=($value['quantity'] * $price*$discount_rate);
    //                                     $discounted_total=($value['quantity'] * $price)-$less;
    //                                     $subtotal=$subtotal+$discounted_total;
    //                                     $discount=$discount+$less;
    //                                     $SD=(($discounted_total* $data->sd)/100);
    //                                     $sd_add=$discounted_total+$SD;
    //                                     $sd_total=$sd_total+$SD;
    //                                     $VAT=($sd_add*$data->vat)/100;
    //                                     $vat_total=$vat_total+$VAT;

    //                                     }
    //                                     else if($discount_amount>0)
    //                                     {
    //                                     $discounted_total=($value['quantity'] * $price)-$discount_amount;
    //                                     $subtotal=$subtotal+$discounted_total;
    //                                     $discount=$discount+$discount_amount;
    //                                     $SD=(($discounted_total* $data->sd)/100);
    //                                     $sd_add=$discounted_total+$SD;
    //                                     $sd_total=$sd_total+$SD;
    //                                     $VAT=(($sd_add*$data->vat)/100);
    //                                     $vat_total=$vat_total+$VAT;
    //                                     }
    //                                     else{
    //                                          $initial_total=($value['quantity'] * $price);
    //                                          $subtotal=$subtotal+$initial_total;
    //                                          $SD=(($initial_total* $data->sd)/100);
    //                                          $sd_add=$initial_total+$SD;
    //                                          $sd_total=$sd_total+$SD;
    //                                          $VAT=(($sd_add*$data->vat)/100);
    //                                          $vat_total=$vat_total+$VAT;

    //                                     }
    //                     } 
    //                 }
    //             }
    //         }
    //         $messsage =  $this->lang->line('record_found');
    //         $status = 1;
    //         $subtotalCal = $subtotal;
    //         $deliveryPrice = '';
    //         if($this->post('order_delivery')=='Delivery'){ 
    //             //check delivery charge available
    //             $latitude = $this->post('latitude');
    //             $longitude = $this->post('longitude');
    //             $check = $this->checkGeoFence($latitude,$longitude,$price_charge = true,$this->post('restaurant_id'));
    //             if($check){ 
    //                 if($discount_delivery>0){
    //                  $total = $subtotal +$vat_total+$sd_total;
    //                   $deliveryPrice = 0;
    //                 }
    //                 else{
    //                 $total = $subtotal + $check+$vat_total+$sd_total;
    //                 $deliveryPrice = $check;
    //             }
    //             }
    //             else{
    //               $total= $subtotal + $check+$vat_total+$sd_totall;
    //             }
    //         }else{ 
    //             $total = $subtotal + $check+$vat_total+$sd_total;
    //         }


    //       // $total = $total - $discount;
    //       /* //get subtotal
    //         $text_amount = 0;
    //         if($taxdetail->amount_type == 'Percentage'){
    //             $text_amount = round(($subtotalCal * $taxdetail->amount) / 100);
    //         }else{
    //             $text_amount = $taxdetail->amount; 
    //         }
    //         $total = $total + $text_amount;*/

    //         /*$type = ($taxdetail->amount_type == 'Percentage')?'%':'';*/
    //         $discount = ($discount)?array('label'=>$this->lang->line('discount'),'value'=>abs($discount),'label_key'=>"Discount"):'';

    //         if($discount){
    //             $priceArray = array(
    //                 array('label'=>$this->lang->line('sub_total'),'value'=>$subtotal+$discount,'label_key'=>"Sub Total"),
    //                 $discount,
    //                  array('label'=>SD,'value'=>$sd_total,'label_key'=>"SD"),
    //                   array('label'=>VAT,'value'=>$vat_total,'label_key'=>"VAT"),
    //                 ($deliveryPrice)?array('label'=>$this->lang->line('delivery_charge'),'value'=>$deliveryPrice,'label_key'=>"Delivery Charge"):'',
    //               /* array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),*/
    //                 array('label'=>$this->lang->line('total'),'value'=>$total,'label_key'=>"Total")
    //             );
    //             $isApply = true;
    //         }else{
    //             $priceArray = array(
    //                 array('label'=>$this->lang->line('sub_total'),'value'=>$subtotal+$discount,'label_key'=>"Sub Total"),
    //                 ($deliveryPrice)?array('label'=>$this->lang->line('delivery_charge'),'value'=>$deliveryPrice,'label_key'=>"Delivery Charge"):'',
    //                  array('label'=>SD,'value'=>$sd_total,'label_key'=>"SD"),
    //                   array('label'=>VAT,'value'=>$vat_total,'label_key'=>"VAT"),
    //               /* array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),*/
    //                 array('label'=>$this->lang->line('total'),'value'=>$total,'label_key'=>"Total")
    //             ); 
    //         }
    //         $add_data = array(
    //             'user_id'=>($user_id)?$user_id:'',
    //             'items'=> serialize($item),
    //             'restaurant_id'=>($this->post('restaurant_id'))?$this->post('restaurant_id'):''
    //         );
    //         if($cart_id == ''){
    //             $cart_id = $this->api_model->addRecord('cart_detail',$add_data);
    //         }else{
    //             $this->api_model->updateUser('cart_detail',$add_data,'cart_id',$cart_id);
    //         }
    //         $this->response([
    //         'total'=>$total,
    //         'cart_id'=>$cart_id,
    //         'items'=>$item,
    //         'price'=>$priceArray,
    //         'coupon_id'=>$coupon_id,
    //         'coupon_amount'=>($coupon_amount)?$coupon_amount:'',
    //         'coupon_type'=>$coupon_type,
    //         'coupon_name'=>$name,
    //       // 'coupon_discount'=>($coupon_discount)?$coupon_discount:'',
    //         'subtotal'=>$subtotal,
    //         'vat'=>$vat_total,
    //         'sd'=>$sd_total,
    //         'currency_code'=>$currencyDetails[0]->currency_code,
    //         'currency_symbol'=>$currencyDetails[0]->currency_symbol,
    //         'delivery_charge'=>($deliveryPrice)?$deliveryPrice:'',
    //         'is_apply'=>$isApply,
    //         'status'=>$status,
    //         'message' =>$messsage], REST_Controller::HTTP_OK); // OK  
    //     }

    public function addtoCart_post()
    {
        $this->getLang();
        $user_id = $this->post('user_id');
        $cart_id = $this->post('cart_id');
        $items = $this->post('items');
        $itemDetail = json_decode($items, true);
        $item_number = $this->post('itemLength');
        $delivery_price=$this->post('delivery_price');
        $item = array();
        $subtotal = 0;
        $discount = 0;
        $dscnt = 0;
        $menuIds = array_column($itemDetail['items'], 'menu_id');
        $discount_rate = 0;
        $discount_amount = 0;
        $discount_delivery = 0;
        $total = -1;
        $vat_total = 0;
        $sd_total = 0;
        $coupon_id = $coupon_amount = $coupon_type = $name  = $isApply = $coupon_discount = '';
        $taxdetail = $this->api_model->getRestaurantTax('restaurant', $this->post('restaurant_id'), $flag = '');
        $currencyDetails  = $this->api_model->getRestaurantCurrency($this->post('restaurant_id'));

        //for discount on items
        $discounted_menu = $this->api_model->discountedItem($this->post('restaurant_id'));

        if ($discounted_menu) {

            $discounted_ids = array_column($discounted_menu, 'item_id');

            //compare to get same ids
            $check_ids = array_intersect($discounted_ids, $menuIds);
            $check_ids = array_values($check_ids);
        }

        if (!empty($itemDetail)) {
            if ($this->post('coupon')  && empty($check_ids)) {
                $check = $this->api_model->checkCoupon($this->post('coupon'));
                if (!empty($check)) {
                    if (strtotime($check->end_date) > strtotime(date('Y-m-d H:i:s'))) {
                        // $this->response([
                        //     'status'=>1,
                        //     'message' =>"ok"], REST_Controller::HTTP_OK); // OK  
                        if ($check->coupon_type == 'discount_on_cart') {
                            if ($check->amount_type == 'Percentage') {
                                $discount_rate = ($check->amount) / 100;
                            } else if ($check->amount_type == 'Amount') {
                                $discount_amount = $check->amount;
                            }

                            $coupon_id = $check->entity_id;
                            $coupon_type = $check->amount_type;
                            $coupon_amount = $check->amount;
                            $coupon_discount = $discount + $discount_amount;
                            $name = $check->name;
                        }
                        if ($check->coupon_type == 'free_delivery') {

                            $discount_delivery = 1;

                            $coupon_id = $check->entity_id;
                            $coupon_type = $check->amount_type;
                            $coupon_amount = $check->amount;
                            $coupon_discount = $discount + $discount_amount;
                            $name = $check->name;
                        }
                        if ($check->coupon_type == 'user_registration') {
                            $checkOrderCount = $this->api_model->checkUserCountCoupon($user_id);
                            if ($checkOrderCount > 0) {
                                $messsage = $this->lang->line('not_applied');
                                $status = 2;
                            } else {
                                if ($check->amount_type == 'Percentage') {
                                    $discount_rate = ($check->amount) / 100;
                                } else if ($check->amount_type == 'Amount') {
                                    $discount_amount = $check->amount;
                                }

                                $coupon_id = $check->entity_id;
                                $coupon_type = $check->amount_type;
                                $coupon_amount = $check->amount;
                                $coupon_discount = $discount + $discount_amount;
                                $name = $check->name;
                            }
                        }
                    } else {
                        $messsage = $this->lang->line('coupon_expire');
                        $status = 2;
                    }
                } else {
                    $messsage = $this->lang->line('coupon_not_found');
                    $status = 2;
                }
            }

            if (!empty($check_ids)) {

                //getting coupon name for discounted items.
                foreach ($discounted_menu as $key => $n) {
                    if ($check_ids[0] == $n->item_id) {
                        $coupon_name = $n->name;
                        break;
                    }
                }

                $check = $this->api_model->checkCoupon($coupon_name);

                if ($check->coupon_type == 'discount_on_items') {
                    if ($check->amount_type == 'Percentage') {
                        $discount_rate_item = ($check->amount) / 100;
                    } else if ($check->amount_type == 'Amount') {
                        $discount_amount_item = $check->amount;
                    }

                    $coupon_id = $check->entity_id;
                    $coupon_type = $check->amount_type;
                    $coupon_amount = $check->amount;
                    $coupon_discount = $discount + $discount_amount;
                    $name = $check->name;
                }
            }

            foreach ($itemDetail['items'] as $key => $value) {

                $data = $this->api_model->checkExist($value['menu_id']);

                //if current menu id is discounted item or not
                if ($check_ids && in_array($value['menu_id'], $check_ids)) {
                    $match = 1;
                } else {
                    $match = 0;
                }

                if (!empty($data)) {
                    $image = ($data->image) ? image_url . $data->image : '';
                    $itemTotal = 0;
                    $priceRate = ($value['offer_price']) ? $value['offer_price'] : $data->price;
                    if ($value['is_customize'] == 1) {
                        $customization = array();
                        foreach ($value['addons_category_list'] as $k => $val) {
                            $addonscust = array();
                            foreach ($val['addons_list'] as $m => $mn) {
                                $add_ons_data = $this->api_model->getAddonsPrice($mn['add_ons_id']);
                                if ($value['is_deal'] == 1) {
                                    $addonscust[] = array(
                                        'add_ons_id' => $mn['add_ons_id'],
                                        'add_ons_name' => $add_ons_data->add_ons_name,
                                    );
                                    $price = ($value['offer_price']) ? $value['offer_price'] : $data->price;
                                    // $subtotal = $subtotal + ($value['quantity'] * $price);
                                    if ($discount_rate > 0) {
                                        // $less=($value['quantity'] * $price*$discount_rate);
                                        // $discounted_total=($value['quantity'] * $price)-$less;
                                        // $subtotal=$subtotal+$discounted_total;
                                        // $discount=$discount+$less;
                                        // $SD=(($discounted_total* $data->sd)/100);
                                        // $sd_add=$discounted_total+$SD;
                                        // $sd_total=$sd_total+$SD;
                                        // $VAT=(($sd_add*$data->vat)/100);
                                        // $vat_total=$vat_total+$VAT;
                                        $ls3 = ($value['quantity'] * $price) * $discount_rate;
                                        $initial_total = ($value['quantity'] *   $price);
                                        $dscnt = $dscnt + $ls3;
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } elseif ($match == 1 && $discount_rate_item > 0) {

                                        $ls3 = ($value['quantity'] * $price) * $discount_rate_item;
                                        $initial_total = ($value['quantity'] *   $price);
                                        $dscnt = $dscnt + $ls3;
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } else if ($discount_amount > 0) {
                                        // $discounted_total=($value['quantity'] * $price)-$discount_amount;
                                        // $subtotal=$subtotal+$discounted_total;
                                        // $discount=$discount+$discount_amount;
                                        // $SD=(($discounted_total* $data->sd)/100);
                                        // $sd_add=$discounted_total+$SD;
                                        // $sd_total=$sd_total+$SD;
                                        // $VAT=(($sd_add*$data->vat)/100);
                                        // $vat_total=$vat_total+$VAT;
                                        $initial_total = ($value['quantity'] * $price);
                                        //$dscnt=$dscnt+$discount_amount;
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } elseif ($match == 1 && $discount_amount_item > 0) {

                                        $initial_total = ($value['quantity'] * $price);
                                        //$dscnt=$dscnt+$discount_amount_item;
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } else {
                                        $initial_total = ($value['quantity'] * $price);
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    }
                                } else {
                                    $addonscust[] = array(
                                        'add_ons_id' => $mn['add_ons_id'],
                                        'add_ons_name' => $add_ons_data->add_ons_name,
                                        'add_ons_price' => $add_ons_data->add_ons_price
                                    );
                                    $itemTotal += $add_ons_data->add_ons_price;
                                    //   $subtotal = $subtotal + ($value['quantity'] * $add_ons_data->add_ons_price);
                                    if ($discount_rate > 0) {
                                        // $less=($value['quantity'] * $add_ons_data->add_ons_price *$discount_rate);
                                        // $discounted_total=($value['quantity'] * $add_ons_data->add_ons_price )-$less;
                                        // $subtotal=$subtotal+$discounted_total;
                                        // $discount=$discount+$less;
                                        // $SD=(($discounted_total* $data->sd)/100);
                                        // $sd_add=$discounted_total+$SD;
                                        // $sd_total=$sd_total+$SD;
                                        // $VAT=(($sd_add*$data->vat)/100);
                                        // $vat_total=$vat_total+$VAT;
                                        $ls1 = ($value['quantity'] * $add_ons_data->add_ons_price) * $discount_rate;
                                        $dscnt = $dscnt + $ls1;
                                        $initial_total = ($value['quantity'] * $add_ons_data->add_ons_price);
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } elseif ($match == 1 && $discount_rate_item > 0) {

                                        $ls1 = ($value['quantity'] * $add_ons_data->add_ons_price) * $discount_rate_item;
                                        $dscnt = $dscnt + $ls1;
                                        $initial_total = ($value['quantity'] * $add_ons_data->add_ons_price);
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } else if ($discount_amount > 0) {
                                        // $discounted_total=($value['quantity'] * $add_ons_data->add_ons_price)-$discount_amount;
                                        // $subtotal=$subtotal+$discounted_total;
                                        // $discount=$discount+$discount_amount;
                                        // $SD=(($discounted_total* $data->sd)/100);
                                        // $sd_add=$discounted_total+$SD;
                                        // $sd_total=$sd_total+$SD;
                                        // $VAT=(($sd_add*$data->vat)/100);
                                        // $vat_total=$vat_total+$VAT;
                                        $initial_total = ($value['quantity'] * $add_ons_data->add_ons_price);
                                        //$dscnt=$dscnt+$discount_amount;
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } elseif ($match == 1 && $discount_amount_item > 0) {

                                        $initial_total = ($value['quantity'] * $add_ons_data->add_ons_price);
                                        //$dscnt=$dscnt+$discount_amount;
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    } else {
                                        $initial_total = ($value['quantity'] * $add_ons_data->add_ons_price);
                                        $subtotal = $subtotal + $initial_total;
                                        $SD = round(($initial_total * $data->sd) / 100);
                                        $sd_add = $initial_total + $SD;
                                        $sd_total = $sd_total + $SD;
                                        $VAT = round(($sd_add * $data->vat) / 100);
                                        $vat_total = $vat_total + $VAT;
                                    }
                                }
                            }
                            $customization[] = array(
                                'addons_category_id' => $val['addons_category_id'],
                                'addons_category' => $val['addons_category'],
                                'addons_list' => $addonscust
                            );
                        }

                        if ($itemTotal) {
                            $itemTotal = ($value['quantity']) ? $value['quantity'] * $itemTotal : '';
                        } else {
                            $itemTotal = ($priceRate && $value['quantity']) ? $value['quantity'] * $priceRate : '';
                        }
                        $item[] = array(
                            'name' => $data->name,
                            'note'=>$value['note'],
                            'image' => $image,
                            'menu_id' => $value['menu_id'],
                            'quantity' => $value['quantity'],
                            'price' => $data->price,
                            'offer_price' => ($value['offer_price']) ? $value['offer_price'] : '',
                            'is_veg' => $data->is_veg,
                            'is_customize' => 1,
                            'is_deal' => $value['is_deal'],
                            'itemTotal' => $itemTotal,
                            'addons_category_list' => $customization
                        );
                    } else {
                        $itemTotal = ($priceRate && $value['quantity']) ? $value['quantity'] * $priceRate : '';
                        $item[] = array(
                            'name' => $data->name,
                            'image' => $image,
                            'menu_id' => $value['menu_id'],
                            'note'=>$value['note'],
                            'quantity' => $value['quantity'],
                            'price' => $data->price,
                            'offer_price' => ($value['offer_price']) ? $value['offer_price'] : '',
                            'is_veg' => $data->is_veg,
                            'is_customize' => 0,
                            'itemTotal' => $itemTotal,
                            'is_deal' => $value['is_deal']
                        );
                        $price = ($value['offer_price']) ? $value['offer_price'] : $data->price;
                        //  $subtotal = $subtotal + ($value['quantity'] * $price);
                        if ($discount_rate > 0) {
                            //   $less=($value['quantity'] *$price*$discount_rate);

                            //     $discounted_total=($value['quantity'] * $price)-$less;
                            //     $subtotal=$subtotal+$discounted_total;
                            //     $discount=$discount+$less;
                            //     $SD=(($discounted_total* $data->sd)/100);
                            //     $sd_add=$discounted_total+$SD;
                            //     $sd_total=$sd_total+$SD;
                            //     $VAT=($sd_add*$data->vat)/100;
                            //     $vat_total=$vat_total+$VAT;
                            $ls = ($value['quantity'] * $price) * $discount_rate;
                            $initial_total = ($value['quantity'] * $price);
                            $dscnt = $dscnt + $ls;
                            //$discount=$discount+$ls;
                            $subtotal = $subtotal + $initial_total;
                            $SD = round(($initial_total * $data->sd) / 100);
                            $sd_add = $initial_total + $SD;
                            $sd_total = $sd_total + $SD;
                            $VAT = round(($sd_add * $data->vat) / 100);
                            $vat_total = $vat_total + $VAT;
                        } elseif ($match == 1 && $discount_rate_item > 0) {

                            $ls = ($value['quantity'] * $price) * $discount_rate_item;
                            $initial_total = ($value['quantity'] * $price);
                            $dscnt = $dscnt + $ls;
                            //$discount=$discount+$ls;
                            $subtotal = $subtotal + $initial_total;
                            $SD = round(($initial_total * $data->sd) / 100);
                            $sd_add = $initial_total + $SD;
                            $sd_total = $sd_total + $SD;
                            $VAT = round(($sd_add * $data->vat) / 100);
                            $vat_total = $vat_total + $VAT;
                        } else if ($discount_amount > 0) {
                            // $discounted_total=($value['quantity'] * $price)-$discount_amount;
                            // $subtotal=$subtotal+$discounted_total;
                            // $discount=$discount+$discount_amount;
                            // $SD=(($discounted_total* $data->sd)/100);
                            // $sd_add=$discounted_total+$SD;
                            // $sd_total=$sd_total+$SD;
                            // $VAT=(($sd_add*$data->vat)/100);
                            // $vat_total=$vat_total+$VAT;
                            $initial_total = ($value['quantity'] * $price);
                            //$dscnt=$dscnt+$discount_amount;
                            $subtotal = $subtotal + $initial_total;
                            $SD = round(($initial_total * $data->sd) / 100);
                            $sd_add = $initial_total + $SD;
                            $sd_total = $sd_total + $SD;
                            $VAT = round(($sd_add * $data->vat) / 100);
                            $vat_total = $vat_total + $VAT;
                        } elseif ($match == 1 && $discount_amount_item > 0) {

                            $initial_total = ($value['quantity'] * $price);
                            //$dscnt=$dscnt+$discount_amount;
                            $subtotal = $subtotal + $initial_total;
                            $SD = round(($initial_total * $data->sd) / 100);
                            $sd_add = $initial_total + $SD;
                            $sd_total = $sd_total + $SD;
                            $VAT = round(($sd_add * $data->vat) / 100);
                            $vat_total = $vat_total + $VAT;
                        } else {
                            $initial_total = ($value['quantity'] * $price);
                            $subtotal = $subtotal + $initial_total;
                            $SD = round(($initial_total * $data->sd) / 100);
                            $sd_add = $initial_total + $SD;
                            $sd_total = $sd_total + $SD;
                            $VAT = round(($sd_add * $data->vat) / 100);
                            $vat_total = $vat_total + $VAT;
                        }
                    }
                }
            }
        }
        $messsage =  $this->lang->line('record_found');
        $status = 1;
        $subtotalCal = $subtotal;
        $deliveryPrice = '';
        if ($this->post('order_delivery') == 'Delivery') {
            //check delivery charge available
            $latitude = $this->post('latitude');
            $longitude = $this->post('longitude');
            $check = $delivery_price!="undefined"&&$delivery_price!=null &&$delivery_price!=-1?$delivery_price:0;
            if ($check) {
                if ($discount_delivery > 0) {
                    $total = $subtotal + $vat_total + $sd_total;

                    $deliveryPrice = 0;
                } else {
                    $d_v = 0;
                    $vat_total = round($vat_total + $d_v);
                    $total = $subtotal + $check + $vat_total + $sd_total;
                    $deliveryPrice = $check;
                }
            } else {
                // $subtotal + $check+$vat_total+$sd_totall;
                $total = $subtotal + $check + $vat_total + $sd_total;
            }
        } else {
            $total = $subtotal + $check + $vat_total + $sd_total;
        }


        // $total = $total - $discount;
        /* //get subtotal
        $text_amount = 0;
        if($taxdetail->amount_type == 'Percentage'){
            $text_amount = round(($subtotalCal * $taxdetail->amount) / 100);
        }else{
            $text_amount = $taxdetail->amount; 
        }
        $total = $total + $text_amount;*/

        /*$type = ($taxdetail->amount_type == 'Percentage')?'%':'';*/
        $discount = ($discount) ? array('label' => $this->lang->line('discount'), 'value' => abs($discount), 'label_key' => "Discount") : '';

        if ($discount_amount > 0 || $discount_amount_item > 0) {
            if ($discount_amount > 0) {
                $dscnt = $discount_amount;
            } else {
                $dscnt = $discount_amount_item;
            }
        }

        if ($dscnt > 0 || $discount_delivery > 0) {
            $total = ($subtotal + $sd_total + $vat_total + $deliveryPrice) - $dscnt;
            $priceArray = array(
                array('label' => $this->lang->line('sub_total'), 'value' => $subtotal, 'label_key' => "Sub Total"),
                // $discount,
                array('label' => "Discount", 'value' => $dscnt, 'label_key' => "discount"),
                array('label' => "SD", 'value' => $sd_total, 'label_key' => "SD"),
                array('label' => "VAT", 'value' => $vat_total, 'label_key' => "VAT"),
                ($deliveryPrice) ? array('label' => $this->lang->line('delivery_charge'), 'value' => $deliveryPrice, 'label_key' => "Delivery Charge") : '',
                /* array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),*/
                array('label' => $this->lang->line('total'), 'value' => round($total), 'label_key' => "Total"),
                // array('label'=>"check",'value'=> $deliveryPrice,'label_key'=>"check"),
            );
            $isApply = true;
        } else {
            $priceArray = array(

                array('label' => $this->lang->line('sub_total'), 'value' => $subtotal + $discount, 'label_key' => "Sub Total"),
                ($deliveryPrice) ? array('label' => $this->lang->line('delivery_charge'), 'value' => $deliveryPrice, 'label_key' => "Delivery Charge") : '',
                array('label' => "Discount", 'value' => $dscnt, 'label_key' => "discount"),
                array('label' => "SD", 'value' => $sd_total, 'label_key' => "SD"),
                array('label' => "VAT", 'value' => $vat_total, 'label_key' => "VAT"),
                /* array('label'=>$this->lang->line('service_fee'),'value'=>$taxdetail->amount.$type,'label_key'=>'Service Fee'),*/
                array('label' => $this->lang->line('total'), 'value' => round($total), 'label_key' => "Total"),
                // array('label'=>"check",'value'=>$item_number,'label_key'=>"check"),
            );
        }
        $add_data = array(
            'user_id' => ($user_id) ? $user_id : '',
            'items' => serialize($item),
            'restaurant_id' => ($this->post('restaurant_id')) ? $this->post('restaurant_id') : ''
        );
        if ($cart_id == '') {
            $cart_id = $this->api_model->addRecord('cart_detail', $add_data);
        } else {
            $this->api_model->updateUser('cart_detail', $add_data, 'cart_id', $cart_id);
        }
        $this->response([
            'total' => round($total),
            'cart_id' => $cart_id,
            'items' => $item,
            'price' => $priceArray,
            'coupon_id' => $coupon_id,
            'coupon_amount' => ($coupon_amount) ? round($coupon_amount) : '',
            'coupon_type' => $coupon_type,
            'coupon_name' => $name,
            'coupon_discount' => ($coupon_discount) ? $coupon_discount : '',
            'subtotal' => $subtotal,
            'vat' => $vat_total,
            'sd' => $sd_total,
            'currency_code' => $currencyDetails[0]->currency_code,
            'currency_symbol' => $currencyDetails[0]->currency_symbol,
            'delivery_charge' => ($deliveryPrice) ? $deliveryPrice : '',
            'is_apply' => $isApply,
            'status' => $status,
            'message' => $messsage
        ], REST_Controller::HTTP_OK); // OK  
    }
    //get address
    // public function getAddress_post(){
    //     $this->getLang();
    //     $token = $this->post('token');
    //     $user_id = $this->post('user_id');
    //     $tokenres = $this->api_model->checkToken($token, $user_id);
    //     if($tokenres){
    //         $address = $this->api_model->getAddress('user_address','user_entity_id',$user_id);
    //         $this->response(['address'=>$address,'status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK  
    //     }else{
    //         $this->response([
    //             'status' => -1,
    //             'message' => ''
    //         ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code       
    //     }
    // }


    //change address
    public function changePassword_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            if (md5(SALT . $this->post('old_password')) == $tokenres->password) {
                if ($this->post('confirm_password') == $this->post('password')) {
                    $this->db->set('password', md5(SALT . $this->post('password')));
                    $this->db->where('entity_id', $user_id);
                    $this->db->update('users');
                    $this->response(['status' => 1, 'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK  
                } else {
                    $this->response(['status' => 0, 'message' => $this->lang->line('confirm_password')], REST_Controller::HTTP_OK); // OK  
                }
            } else {
                $this->response(['status' => 0, 'message' => $this->lang->line('old_password')], REST_Controller::HTTP_OK); // OK  
            }
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //add order
    public function addOrder_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        $temp=$this->post('addressdetail');
        //$addressdetail=json_decode($temp,true);
        if ($tokenres) {
            $taxdetail = $this->api_model->getRestaurantTax('restaurant', $this->post('restaurant_id'), $flag = "order");
            $total = 0;
            $subtotal = $this->post('subtotal');

            $rider_commission = $this->api_model->getRecord('delivery_area', 'entity_id', $temp['area_id']);
            $temp['rider_commission'] = $rider_commission->rider_commision;
            if ($this->post('address_id') == 0) {
                $addressdetail = array('address_detail'=>json_encode($temp),'user_entity_id' => $this->post('user_id'));
                $address_id = $this->api_model->addRecord('user_address', $addressdetail);
            }
            $add_data = array(
                'user_id' => $this->post('user_id'),
                'restaurant_id' => $this->post('restaurant_id'),
                'address_id' => ($address_id) ? $address_id : $this->post('address_id'),
                'coupon_id' => $this->post('coupon_id'),
                'order_status' => 'placed',
                'order_date' => date('Y-m-d H:i:s', strtotime($this->post('order_date'))),
                'subtotal' => $subtotal,
                'coupon_type' => $this->post('coupon_type'),
                'coupon_amount' => ($this->post('coupon_amount')) ? $this->post('coupon_amount') : '',
                'total_rate' => $this->post('total'),
                'status' => 0,
                'coupon_discount' => ($this->post('coupon_discount')) ? $this->post('coupon_discount') : '',
                'delivery_charge' => ($this->post('delivery_charge')) ? $this->post('delivery_charge') : '',
                'extra_comment' => $this->post('extra_comment'),
                'coupon_name' => $this->post('coupon_name'),
                'sd' => $this->post('sd'),
                'vat' => $this->post('vat'),
                'payment_option' => $this->post('paymethod'),
                'delivery_time' => $this->post('delivery_time'),
                'time_slot' => $this->post('time_slot'),
                //'address_detail'=>json_encode($temp)
            );
            if ($this->post('order_delivery') == 'Delivery') {
                $add_data['order_delivery'] = 'Delivery';
            } else {
                $add_data['order_delivery'] = 'PickUp';
            }
            $order_id = $this->api_model->addRecord('order_master', $add_data);
            //add items
            $items = $this->post('items');
            $itemDetail = json_decode($items, true);
            $add_item = array();

            if (!empty($itemDetail)) {
                foreach ($itemDetail['items'] as $key => $value) {
                    if ($value['is_customize'] == 1) {
                        $customization = array();
                        foreach ($value['addons_category_list'] as $k => $val) {
                            $addonscust = array();
                            foreach ($val['addons_list'] as $m => $mn) {
                                if ($value['is_deal'] == 1) {
                                    $addonscust[] = array(
                                        'add_ons_id' => $mn['add_ons_id'],
                                        'add_ons_name' => $mn['add_ons_name'],
                                    );
                                } else {
                                    $addonscust[] = array(
                                        'add_ons_id' => $mn['add_ons_id'],
                                        'add_ons_name' => $mn['add_ons_name'],
                                        'add_ons_price' => $mn['add_ons_price']
                                    );
                                }
                            }
                            $customization[] = array(
                                'addons_category_id' => $val['addons_category_id'],
                                'addons_category' => $val['addons_category'],
                                'addons_list' => $addonscust
                            );
                        }

                        $add_item[] = array(
                            "item_name" => $value['name'],
                            "item_id" => $value['menu_id'],
                            "qty_no" => $value['quantity'],
                            "rate" => ($value['price']) ? $value['price'] : '',
                            "offer_price" => ($value['offer_price']) ? $value['offer_price'] : '',
                            "order_id" => $order_id,
                            "is_customize" => 1,
                            "note" => $value['note'],
                            "is_deal" => $value['is_deal'],
                            "itemTotal" => $value['itemTotal'],
                            'note'=>$value['note'],
                            "addons_category_list" => $customization
                        );
                    } else {
                        $add_item[] = array(
                            "item_name" => $value['name'],
                            "item_id" => $value['menu_id'],
                            "qty_no" => $value['quantity'],
                            "rate" => ($value['price']) ? $value['price'] : '',
                            "offer_price" => ($value['offer_price']) ? $value['offer_price'] : '',
                            "order_id" => $order_id,
                            "is_customize" => 0,
                            "note" => $value['note'],
                            "itemTotal" => $value['itemTotal'],
                            "is_deal" => $value['is_deal'],
                             'note'=>$value['note'],
                        );
                    }
                }
            }

            $add_id = ($address_id) ? $address_id : $this->post('address_id');
            $address = $this->api_model->getAddress('user_address', 'entity_id', $add_id);

            $user_detail = array(
                'first_name' => $tokenres->first_name,
                'last_name' => ($tokenres->last_name) ? $tokenres->last_name : '',
                'address' => ($address) ? $address[0]->address_detail : '',
                // 'landmark' => ($address) ? $address[0]->landmark : '',
                // 'zipcode' => ($address) ? $address[0]->zipcode : '',
                // 'city' => ($address) ? $address[0]->city : '',
                // 'latitude' => ($address) ? $address[0]->latitude : '',
                // 'longitude' => ($address) ? $address[0]->longitude : '',
            );
            $order_detail = array(
                'order_id' => $order_id,
                'user_detail' => serialize($user_detail),
                'item_detail' => serialize($add_item),
                'restaurant_detail' => serialize($taxdetail),
            );
            $this->api_model->addRecord('order_detail', $order_detail);
            //send noti to res app
            $this->api_model->sendNotiRestaurant($this->post('restaurant_id'));
            $verificationCode = random_string('alnum', 25);
            $email_template = $this->db->get_where('email_template', array('email_slug' => 'order-receive-alert', 'language_slug' => 'en', 'status' => 1))->first_row();

            $this->db->select('OptionValue');
            $FromEmailID = $this->db->get_where('system_option', array('OptionSlug' => 'From_Email_Address'))->first_row();

            $this->db->select('OptionValue');
            $FromEmailName = $this->db->get_where('system_option', array('OptionSlug' => 'Email_From_Name'))->first_row();
            if (!empty($email_template)) {
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
            // send invoice to user
            $data['order_records'] = $this->api_model->getEditDetail($order_id);
            $data['menu_item'] = $this->api_model->getInvoiceMenuItem($order_id);
            $html = $this->load->view('backoffice/order_invoice', $data, true);
            if (!@is_dir('uploads/invoice')) {
                @mkdir('./uploads/invoice', 0777, TRUE);
            }
            $filepath = 'uploads/invoice/' . $order_id . '.pdf';
            $this->load->library('M_pdf');
            $mpdf = new mPDF('', 'Letter');
            $mpdf->SetHTMLHeader('');
            $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
            $mpdf->AddPage(
                '', // L - landscape, P - portrait 
                '',
                '',
                '',
                '',
                0, // margin_left
                0, // margin right
                10, // margin top
                23, // margin bottom
                0, // margin header
                0 //margin footer
            );
            $mpdf->autoScriptToLang = true;
            $mpdf->SetAutoFont();
            $mpdf->WriteHTML($html);
            $mpdf->output($filepath, 'F');

            //new invoice

            $html1 = $this->load->view('backoffice/restaurant_invoice', $data, true);
            if (!@is_dir('uploads/res_invoice')) {
                @mkdir('./uploads/res_invoice', 0777, TRUE);
            }
            $filepath1 = 'uploads/res_invoice/' . $order_id . '.pdf';
            $this->load->library('M_pdf');
            $mpdf1 = new mPDF('', 'Letter');
            $mpdf1->SetHTMLHeader('');
            $mpdf1->SetHTMLFooter('');
            //$mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
            $mpdf1->AddPage(
                '', // L - landscape, P - portrait 
                '',
                '',
                '',
                '',
                0, // margin_left
                0, // margin right
                10, // margin top
                23, // margin bottom
                0, // margin header
                0 //margin footer
            );
            $mpdf1->autoScriptToLang = true;
            $mpdf1->SetAutoFont();
            $mpdf1->WriteHTML($html1);
            $mpdf1->output($filepath1, 'F');

            //send invoice as email
            $user = $this->db->get_where('users', array('entity_id' => $this->post('user_id')))->first_row();
            $FromEmailID = $this->db->get_where('system_option', array('OptionSlug' => 'From_Email_Address'))->first_row();
            $this->db->select('OptionValue');
            $FromEmailName = $this->db->get_where('system_option', array('OptionSlug' => 'Email_From_Name'))->first_row();
            $this->db->select('subject,message');
            $Emaildata = $this->db->get_where('email_template', array('email_slug' => 'new-order-invoice', 'language_slug' => $this->session->userdata('language_slug'), 'status' => 1))->first_row();
            $arrayData = array('FirstName' => $user->first_name, 'Order_ID' => $order_id);
            $EmailBody = generateEmailBody($Emaildata->message, $arrayData);
            if (!empty($EmailBody)) {
                $this->load->library('email');
                $config['charset'] = 'iso-8859-1';
                $config['wordwrap'] = TRUE;
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);
                $this->email->to(trim($user->email));
                $this->email->subject($Emaildata->subject);
                $this->email->message($EmailBody);
                $this->email->attach($filepath);
                $this->email->send();
            }
            $this->response(['restaurant_detail' => $taxdetail, 'order_status' => $order_status, 'order_date' => date('Y-m-d H:i:s', strtotime($this->post('order_date'))), 'status' => 1, 'message' => $message], REST_Controller::HTTP_OK); // OK */
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    public function fetchCategory_get()
    {
        $data = $this->api_model->fetchCategory();

        if (!empty($data)) {
            $this->response(['categories' => $data, 'status' => 1, 'message' => $this->lang->line('record_found')]);
        } else {
            $this->response(['status' => 0, 'message' => $this->lang->line('record_not_found')]);
        }
    }

    //order detail proccess
    public function inProcessOrderDetail_post()
    {
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        $count = ($this->post('count')) ? $this->post('count') : 10;
        $page_no = ($this->post('page_no')) ? $this->post('page_no') : 1;
        if ($tokenres) {
            $result = $this->api_model->getOrderDetail('process', $user_id, $count, $page_no);
            $result2 = $this->api_model->getOrderDetail('past', $user_id, $count, $page_no);

            $this->response(['in_process' => $result, 'past' => $result2, 'status' => 1, 'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK */
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //order detail past
    public function pastOrderDetail_post()
    {
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        $count = ($this->post('count')) ? $this->post('count') : 10;
        $page_no = ($this->post('page_no')) ? $this->post('page_no') : 1;
        if ($tokenres) {
            $result = $this->api_model->getOrderDetail('past', $user_id, $count, $page_no);
            $this->response(['past' => $result, 'status' => 1, 'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK */
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get promocode list
    public function AllcouponList_post()
    {
        $this->getLang();
        //$token = $this->post('token');
        //$user_id = $this->post('user_id');
        //$tokenres = $this->api_model->checkToken($token, $user_id);

        //$subtotal = $this->post('subtotal');
        $coupon = $this->api_model->getAllcouponList();
        if (!empty($coupon)) {
            $this->response([
                'coupon_list' => $coupon,
                'status' => 1,
                'message' => $this->lang->line('record_found')
            ],  REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('promocode')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }

    public function couponList_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $subtotal = $this->post('subtotal');
            $coupon = $this->api_model->getcouponList($subtotal, $this->post('restaurant_id'), $this->post('order_delivery'), $user_id);
            if (!empty($coupon)) {
                $this->response([
                    'coupon_list' => $coupon,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')
                ],  REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('promocode')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            }
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get notification list
    function getNotification_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $notification = $this->api_model->getNotification($user_id, $this->post('count'), $this->post('page_no'));
            if (!empty($notification)) {
                $this->response([
                    'notification' => $notification['result'],
                    'status' => 1,
                    'notificaion_count' => $notification['count'],
                    'message' => $this->lang->line('record_found')
                ],  REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            }
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //check users order delivery
    public function checkOrderDelivery_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            if ($this->post('order_delivery') == 'Delivery') {
                $users_latitude = $this->post('users_latitude');
                $users_longitude = $this->post('users_longitude');
                $user_km = ($this->post('user_km')) ? $this->post('user_km') : '';
                $driver_km = ($this->post('driver_km')) ? $this->post('driver_km') : '';
                //$detail = $this->api_model->checkOrderDelivery($users_latitude,$users_longitude,$user_id,$this->post('restaurant_id'),$request = '',$order_id = '',$user_km,$driver_km);
                $detail = true;
                if ($detail) {
                    $restaurantAvail = $this->api_model->checkRestaurantAvailability($users_latitude, $users_longitude, $user_id, $this->post('restaurant_id'), $request = '', $order_id = '', $user_km, $driver_km);
                    if ($restaurantAvail) {
                        $resstatus = 1;
                        $message = $this->lang->line('delivery_available');
                    } else {
                        $resstatus = 0;
                        $message = $this->lang->line('restaurant_delivery_not_available');
                        // $message="hello";
                    }

                    $this->response([
                        'status' => ($resstatus == 1) ? 1 : 0,
                        //'restaurant_status' => $resstatus, 
                        'message' => $message
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

                } else {
                    $this->response([
                        'status' => 0,
                        'check' =>   $this->post('restaurant_id'),
                        'message' => $this->lang->line('delivery_not_available')
                        // $message=>$detail
                    ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
                }
            }
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //get driver location
    public function driverTracking_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $order_id = $this->post('order_id');
            $detail = $this->api_model->getdriverTracking($order_id, $user_id);
            if ($detail) {
                $this->response([
                    'detail' => $detail,
                    'status' => 1,
                    'message' => $this->lang->line('record_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  
            } else {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
            }
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //check if order is delivered or not
    public function checkOrderDelivered_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $order_id = $this->post('order_id');
            $is_delivered = $this->post('is_delivered');
            if ($is_delivered != 1) {
                $this->db->set('order_status', 'pending')->where('entity_id', $order_id)->update('order_master');
                $add_data = array('order_id' => $order_id, 'order_status' => 'pending', 'time' => date('Y-m-d H:i:s'), 'status_created_by' => 'User');
                $this->api_model->addRecord('order_status', $add_data);
                $this->response([
                    'status' => 1,
                    'message' => $this->lang->line('success_update')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
            } else {
                $this->response([
                    'status' => 1,
                    'message' => $this->lang->line('success_update')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code  
            }
        } else {
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
        $tokenres = $this->api_model->getRecord('users', 'entity_id', $userid);
        if ($tokenres) {
            $data = array('device_id' => "");
            $this->api_model->updateUser('users', $data, 'entity_id', $tokenres->entity_id);
            $this->response(['status' => 1, 'message' => $this->lang->line('user_logout')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //check lat long exist in area
    public function checkGeoFence($latitude, $longitude, $price_charge, $restaurant_id)
    {
        $result = $this->api_model->checkGeoFence('delivery_charge', 'restaurant_id', $restaurant_id);
        $latlongs =  array($latitude, $longitude);
        $data = '';
        $oddNodes = false;
        $delivery_charge = '';
        foreach ($result as $key => $value) {

            $lat_longs = $value->lat_long;
            $lat_longs =  explode('~', $lat_longs);
            $polygon = array();
            foreach ($lat_longs as $key => $val) {
                if ($val) {
                    $val = str_replace(array('[', ']'), array('', ''), $val);
                    $polygon[] = explode(',', $val);
                }
            }
            if ($polygon[0] != $polygon[count($polygon) - 1])
                $polygon[count($polygon)] = $polygon[0];
            $j = 0;
            $x = $longitude;
            $y = $latitude;
            $n = count($polygon);
            for ($i = 0; $i < $n; $i++) {
                $j++;
                if ($j == $n) {
                    $j = 0;
                }
                if ((($polygon[$i][0] < $y) && ($polygon[$j][0] >= $y)) || (($polygon[$j][0] < $y) && ($polygon[$i][0] >= $y))) {
                    if ($polygon[$i][1] + ($y - $polygon[$i][0]) / ($polygon[$j][0] - $polygon[$i][0]) * ($polygon[$j][1] - $polygon[$i][1]) < $x) {
                        $oddNodes = true;
                        $delivery_charge = $value->price_charge;
                    }
                }
            }
        }
        $oddNodes = ($price_charge) ? $delivery_charge : $oddNodes;
        return $oddNodes;
    }
    //get user lang
    public function getUserLanguage_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->api_model->checkToken($token, $user_id);
        if ($tokenres) {
            $data = array('language_slug' => $this->post('language_slug'));
            $this->api_model->updateUser('users', $data, 'entity_id', $user_id);
            $this->response(['status' => 1, 'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
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
        if ($tokenres) {
            $data = array('device_id' => $this->post('firebase_token'));
            $this->api_model->updateUser('users', $data, 'entity_id', $userid);
            $this->response(['status' => 1, 'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function CheckAvailable_post()
    {
        $user_id = $this->post('user_id');
        $restaurant = $this->post('restaurant_id');
        $items = $this->post('items');
        $itemDetail = json_decode($items, true);
        $message = array();
        $i = 0;
        $final = '';
        $status = 1;
        $cuisine_status = 0;

        //Check restaurant timing and menu item status and user
        if ($this->post('restaurant_id')) {
            $restaurant = $this->api_model->getRecord('restaurant', 'entity_id', $restaurant);

            $timing =  unserialize(html_entity_decode($restaurant->timings));
            $day = date("l");
            $currentTime = new DateTime(date('G:i:s'));
            foreach ($timing as $keys => $values) {
                if ($keys == strtolower($day)) {
                    $restaurantOpenTime = new DateTime($values['open']);
                    $restaurantCloseTime = new DateTime($values['close']);

                    $restaurant_status = (!empty($values['open']) && !empty($values['close'])) ? (($restaurantOpenTime <= $currentTime && $restaurantCloseTime >= $currentTime) ? 'open' : 'close') : 'close';

                    if ($restaurant_status == 'close') {
                        $message[$i] = 'Restaurant is closed.';
                        $i++;
                        //$this->response(['status' => false, 'message' => 'Restaurant is closed'], REST_Controller::HTTP_OK); // OK  
                    }
                }
            }

            if ($restaurant->status == 0) {
                $message[$i] = 'Restaurant is deactivate.';
                $i++;
            }

            foreach ($itemDetail['items'] as $m => $n) {
                $menu = $this->api_model->getRecord('restaurant_menu_item', 'entity_id', $n['menu_id']);
                $category = $this->api_model->getRecord('category', 'entity_id', $menu->category_id);
                $cuisine = $this->api_model->getCuisine('cuisine_multicategory_map', 'category_id', $menu->category_id);
                if ($menu->status == 0) {
                    $message[$i] = 'Menu is not active.';
                    $i++;
                    break;
                    //$this->response(['status' => false, 'message' => 'Menu is not active'], REST_Controller::HTTP_OK); // OK  
                }

                if ($category->status == 0) {
                    $message[$i] = 'Category is deactivated.';
                    $i++;
                    break;
                }


                
                foreach ($cuisine as $key => $value) {
                    
                    $detail = $this->api_model->getRecord('cuisine', 'entity_id', $value->cuisine_id);
                    if ($detail->status == 0) {
                        $message[$i] = 'Cuisine is deactivated.';
                        $i++;
                        $cuisine_status = 1;
                        break;
                    }
                }

                if ($cuisine_status == 1) {
                    break;
                }
            }


            $user = $this->api_model->getRecord('users', 'entity_id', $user_id);

            if ($user->status == 0) {
                $message[$i] = 'Your acount is not active';
                $i++;
                //$this->response(['status' => false, 'message' => 'Bloody user !!! you are deactivated -_- (Authorized by Shouvik Chowdhury Oni)'], REST_Controller::HTTP_OK); // OK  
            }
        }

        if (!empty($message)) {
            $final = implode('  ', $message);
            $status = 0;
        }

        $this->response(['status' => $status, 'message' => $final], REST_Controller::HTTP_OK); // OK  

    }


    public function getCusinesByRestaurantId_post()
    {
        $restaurant = $this->api_model->getNearestRestaurants($this->post('latitude'),$this->post('longitude'));
        if($restaurant){
              $result = $this->api_model->getCuisineFromResId($restaurant);
        $slider = $this->api_model->getbanner();
        $this->response(['restaurant' => $result, 'slider' => $slider, 'status' => 1], REST_Controller::HTTP_OK); // OK 
        }
        else{
          $this->response(['restaurant' => [], 'slider' => [], 'status' => 0,'msg'=>"We are not delivering near your location"], REST_Controller::HTTP_OK); // OK   
        }
      
    }
}
?>
