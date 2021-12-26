<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(-1);
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

class Restaurant extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('v1/Restaurant_model'); 
        $this->load->model('v1/api_model');        
        $this->load->model('backoffice/Common_model');
        $this->load->library('form_validation');
    }
    //common lang fucntion
    public function getLang(){
        $this->current_lang = ($this->post('language_slug'))?$this->post('language_slug'):$this->current_lang;
        $languages = $this->driver_api_model->getLanguages($this->current_lang);
        $this->lang->load('messages_lang', $languages->language_directory);
    }
    //order history
    public function GetOrderHistory_post(){
        $res_id=$this->post('res_id');
        $fromDate=$this->post('from_date');
        $toDate=$this->post('to_date');
        $data=$this->Restaurant_model->getOrderHistory($res_id,$fromDate,$toDate);
         $this->response([
                'status' => 1,
                'data' => $data
            ], REST_Controller::HTTP_OK);
        
    }
    //menu on off
        public function MenuOnOff_post()
        {
            $value=$this->post('value');
            $menu_id=$this->post('menu_id');
            if( !empty($menu_id)){
                $data=array(
                    'status'=>$value
                    );
                
                $result=$this->Restaurant_model->updateUser('restaurant_menu_item',$data,'entity_id', $menu_id);
                  $this->response([
                'status' => 1,
                'data' => $result
            ], REST_Controller::HTTP_OK);
                
                
            }
            else{
                  $this->response([
                'status' => -1,
                'data' => "inappropiate call"
            ], REST_Controller::HTTP_OK);
            }
            
            
        }
    //menu fetching
    public function getMenu_post(){
        $rest_id= $this->post('res_id');
  
        $data= $this->Restaurant_model->getMenuRecord($rest_id);
          $this->response([
                'status' => 1,
                'data' => $data
            ], REST_Controller::HTTP_OK);
    }
    //get order api
    public function getAllorders_post(){
        $token=$this->post('email');
        $user_id=$this->post('user_id');
        $res_id=$this->post('res_id');
        
        
         $tokenusr = $this->Restaurant_model->checkToken($token, $user_id);
         if($tokenusr){
             $data=$this->Restaurant_model->getAllorders($res_id);
              $this->response([
                'status' => 1,
                'data' => $data
            ], REST_Controller::HTTP_OK);
             
         }
         else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
    }
    //invoice api
    public function getInvoice_post(){
        $order_id=$this->post('order_id');
        if(!empty($order_id)){
           $data=$this->Restaurant_model->getInvoice($order_id);
            $this->response([
                'status' => 1,
                'data' => $data
            ], REST_Controller::HTTP_OK);
           
        }
        else{
          $this->response([
                'status' => -1,
                'message' => 'void'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code 
        }
        
        
        
        
    }
    // Login API
    public function login_post()
    {
       // $this->getLang();
        $login = $this->Restaurant_model->getLogin($this->post('email'), $this->post('Password'));
        if(!empty($login)){
            $data = array('device_id'=>$this->post('firebase_token'));
            if($login->status==1)
            {
                $data = array('device_id'=>$this->post('firebase_token'));
                $this->Restaurant_model->updateUser('restaurant', $data,'entity_id',$login->res_id);
 
                $this->response(['login' => $login,'status'=>1,'message' =>$this->lang->line('login_success') ], REST_Controller::HTTP_OK); 
            } else if ($login->status==0){
              
                $this->response(['status' => 2,'message' =>  "Wrong Password"], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } 
        }        
        else
        {
             $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK);
           
        }        
    }
    public function getRefCode_post()
    {
        $data = $this->driver_api_model->getRecord('users','mobile_number',$this->post('PhoneNumber'));
          if($data){
                $this->response([
                    'status' => 1,
                    'ref' =>$data->referral_code
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            } else {
                $this->response([
                    'status' => 0,
                    'message' => $this->lang->line('not_found')
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
            }
    }
    // Forgot Password
    public function forgotpassword_post()
    {
        $this->getLang();
        $checkRecord = $this->driver_api_model->getRecordMultipleWhere('users', array('mobile_number'=>$this->post('mobile_number'),'status'=>1));
        if(!empty($checkRecord))
        {
            $activecode = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
            $password = random_string('alnum', 8);
            $data = array('active_code'=>$activecode,'password'=>md5(SALT.$password));
            $this->driver_api_model->updateUser('users',$data,'mobile_number',$this->post('mobile_number'));
            $this->response(['status' => 1,'password'=>$password,'message' => $this->lang->line('success_password_change')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => 0,
                'message' => $this->lang->line('user_not_found')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code            
        }
    }
    
    public function OnOff_post()
    {
        $data = $this->Restaurant_model->getRecord('restaurant', 'entity_id', $this->post('res_id'));
        $this->response([
            'status' => 1,
            'datas' => $data->status,
        ], REST_Controller::HTTP_OK);
    }
    public function editOnOff_post()
    {
        $data = array('status' => $this->post('onoff'));
        $this->Restaurant_model->updateUser('restaurant', $data, 'entity_id', $this->post('res_id'));
        $this->response([
            'status' => 1,

        ], REST_Controller::HTTP_OK);
    }
    
    //add review
    public function addReview_post(){
        $this->getLang();
        if($this->post('rating') != '' && $this->post('review') != ''){
            $add_data = array(
                'rating'=>trim($this->post('rating')),
                'review'=>trim($this->post('review')),
                'order_user_id'=>$this->post('order_user_id'),
                'user_id'=>$this->post('user_id'),
                'status'=>1,
                'created_date'=>date('Y-m-d H:i:s')                
            );
            $this->driver_api_model->addRecord('review', $add_data);
            $this->response(['status'=>1,'message' => $this->lang->line('success_add')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }else{
            $this->response([
                'status' => 0,
                'message' =>  $this->lang->line('validation')
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    public function editProfile_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id =$this->post('user_id');
        $tokenusr = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenusr){
            $add_data =array(
                'first_name'=>$this->post('first_name'),
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
            $this->driver_api_model->updateUser('users',$add_data,'entity_id',$this->post('user_id'));
            $token = $this->driver_api_model->checkToken($token, $user_id);
            $image = ($token->image)?image_url.$token->image:''; 
            $login_detail = array('FirstName'=>$token->first_name,'image'=> $image ,'PhoneNumber'=>$token->mobile_number,'UserID'=>$token->entity_id);
            $this->response(['profile'=>$login_detail,'status'=>1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
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
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
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
    //accept order
    // public function acceptOrder_post(){
    //     $this->getLang();
    //     $token = $this->post('token');
    //     $user_id = $this->post('user_id');
    //     $tokenres = $this->driver_api_model->checkToken($token, $user_id);
    //     if($tokenres){
    //         $order_id = $this->post('order_id');
    //         $driver_map_id = $this->post('driver_map_id');
    //         if($order_id){
    //             $details = $this->driver_api_model->getRecordMultipleWhere('order_driver_map',array('driver_map_id'=>$driver_map_id));
    //             if(!empty($details)){
    //                 if($this->post('order_status') == 'cancel'){
    //                     if($this->post('cancel_reason') != ''){
    //                         $add_data = array('cancel_reason'=>$this->post('cancel_reason'));
    //                         $this->driver_api_model->updateUser('order_driver_map',$add_data,'driver_map_id',$driver_map_id);
                            
    //                         $data = array('order_id'=>$order_id,'order_status'=>'cancel','time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
    //                         $this->driver_api_model->addRecord('order_status',$data);

    //                         $this->db->set('order_status','cancel')->where('entity_id', $order_id)->update('order_master');
    //                         //get user of order
    //                         $userData = $this->driver_api_model->getUserofOrder($order_id);
    //                         // load language
    //                         $languages = $this->db->select('*')->get_where('languages',array('language_slug'=>$userData->language_slug))->first_row();
    //                         $this->lang->load('messages_lang', $languages->language_directory);
        
    //                         if(!empty($userData) && $userData->device_id){
    //                             #prep the bundle
    //                             $fields = array();            
    //                             $message = $this->lang->line('push_order_cancel');
    //                             $fields['to'] = $userData->device_id; // only one user to send push notification
    //                             $fields['notification'] = array ('body'  => $message,'sound'=>'default');
    //                             $fields['data'] = array ('screenType'=>'order');
                               
    //                             $headers = array (
    //                                 'Authorization: key=' . Driver_FCM_KEY,
    //                                 'Content-Type: application/json'
    //                             );
    //                             #Send Reponse To FireBase Server    
    //                             $ch = curl_init();
    //                             curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    //                             curl_setopt( $ch,CURLOPT_POST, true );
    //                             curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    //                             curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    //                             curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    //                             curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    //                             $result = curl_exec($ch);
    //                             curl_close($ch);  
    //                         }
    //                     }else{
    //                         $this->driver_api_model->deleteRecord('order_driver_map','driver_map_id',$driver_map_id); 
    //                     }
    //                     // adding notification for website
    //                     $order_detail = $this->driver_api_model->getRecord('order_master','entity_id',$order_id);
    //                     $notification = array(
    //                         'order_id' => $order_id,
    //                         'user_id' => $order_detail->user_id,
    //                         'notification_slug' => 'order_canceled',
    //                         'view_status' => 0,
    //                         'datetime' => date("Y-m-d H:i:s"),
    //                     );
    //                     $this->driver_api_model->addRecord('user_order_notification',$notification);

    //                     $this->response(['status'=>1,'message' => $this->lang->line('order_cancel')], REST_Controller::HTTP_OK); // OK */
    //                 }else{
    //                     $add_data = array('order_id'=>$order_id,'order_status'=>'preparing','time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
    //                     $this->driver_api_model->addRecord('order_status',$add_data);
    //                     $detail = $this->driver_api_model->acceptOrder($order_id,$driver_map_id,$user_id);
    //                     // adding notification for website
    //                     $order_detail = $this->driver_api_model->getRecord('order_master','entity_id',$order_id);
    //                     $notification = array(
    //                         'order_id' => $order_id,
    //                         'user_id' => $order_detail->user_id,
    //                         'notification_slug' => 'order_preparing',
    //                         'view_status' => 0,
    //                         'datetime' => date("Y-m-d H:i:s"),
    //                     );
    //                     $this->driver_api_model->addRecord('user_order_notification',$notification);
    //                     $this->response(['user_detail'=>$detail,'status'=>1,'message' => $this->lang->line('order_accept')], REST_Controller::HTTP_OK); // OK */
    //                 }
    //             }else{
    //                 $this->response(['status'=>0,'message' => $this->lang->line('order_accepted')], REST_Controller::HTTP_OK); // OK */
    //             } 
    //         }else{
    //             $this->response([
    //                 'status' => 0,
    //                 'message' => $this->lang->line('not_found')
    //             ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code   
    //         }
    //     }else{
    //         $this->response([
    //             'status' => -1,
    //             'message' => ''
    //         ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
    //     }   
    // }

    // public function acceptOrder_post(){
    //     $this->getLang();
    //     $token = $this->post('token');
    //     $user_id = $this->post('user_id');
    //     $tokenres = $this->driver_api_model->checkToken($token, $user_id);
    //     if($tokenres){
    //         $order_id = $this->post('order_id');
    //         $driver_map_id = $this->post('driver_map_id');
    //         if($order_id){
    //             $details = $this->driver_api_model->getRecordMultipleWhere('order_driver_map',array('driver_map_id'=>$driver_map_id));
    //             if(!empty($details)){
    //                 if($this->post('order_status') == 'cancel'){
    //                     if($this->post('cancel_reason') != ''){
    //                         $add_data = array('cancel_reason'=>$this->post('cancel_reason'));
    //                         $this->driver_api_model->updateUser('order_driver_map',$add_data,'driver_map_id',$driver_map_id);
                            
    //                         $data = array('order_id'=>$order_id,'order_status'=>'placed','time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
    //                         $this->driver_api_model->addRecord('order_status',$data);

    //                         $this->db->set('order_status','placed')->where('entity_id', $order_id)->update('order_master');
    //                         //get user of order
    //                         $userData = $this->driver_api_model->getUserofOrder($order_id);
    //                         // load language
    //                         $languages = $this->db->select('*')->get_where('languages',array('language_slug'=>$userData->language_slug))->first_row();
    //                         $this->lang->load('messages_lang', $languages->language_directory);
        
    //                         if(!empty($userData) && $userData->device_id){
    //                             #prep the bundle
    //                             $fields = array();            
    //                             $message = $this->lang->line('push_order_cancel');
    //                             $fields['to'] = $userData->device_id; // only one user to send push notification
    //                             $fields['notification'] = array ('body'  => $message,'sound'=>'default');
    //                             $fields['data'] = array ('screenType'=>'order');
                               
    //                             $headers = array (
    //                                 'Authorization: key=' . Driver_FCM_KEY,
    //                                 'Content-Type: application/json'
    //                             );
    //                             #Send Reponse To FireBase Server    
    //                             $ch = curl_init();
    //                             curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    //                             curl_setopt( $ch,CURLOPT_POST, true );
    //                             curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    //                             curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    //                             curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    //                             curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    //                             $result = curl_exec($ch);
    //                             curl_close($ch);  
    //                         }
    //                     }else{
    //                         $this->driver_api_model->deleteRecord('order_driver_map','driver_map_id',$driver_map_id); 
    //                     }
    //                     // adding notification for website
    //                     $order_detail = $this->driver_api_model->getRecord('order_master','entity_id',$order_id);
    //                     $notification = array(
    //                         'order_id' => $order_id,
    //                         'user_id' => $order_detail->user_id,
    //                         'notification_slug' => 'order_canceled',
    //                         'view_status' => 0,
    //                         'datetime' => date("Y-m-d H:i:s"),
    //                     );
    //                     $this->driver_api_model->addRecord('user_order_notification',$notification);

    //                     $this->response(['status'=>1,'message' => $this->lang->line('order_cancel')], REST_Controller::HTTP_OK); // OK */
    //                 }else{
    //                     $add_data = array('order_id'=>$order_id,'order_status'=>'preparing','time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
    //                     $this->driver_api_model->addRecord('order_status',$add_data);
    //                     $detail = $this->driver_api_model->acceptOrder($order_id,$driver_map_id,$user_id);
    //                     // adding notification for website
    //                     $order_detail = $this->driver_api_model->getRecord('order_master','entity_id',$order_id);
    //                     $notification = array(
    //                         'order_id' => $order_id,
    //                         'user_id' => $order_detail->user_id,
    //                         'notification_slug' => 'order_preparing',
    //                         'view_status' => 0,
    //                         'datetime' => date("Y-m-d H:i:s"),
    //                     );
    //                     $this->driver_api_model->addRecord('user_order_notification',$notification);
    //                     $this->response(['user_detail'=>$detail,'status'=>1,'message' => $this->lang->line('order_accept')], REST_Controller::HTTP_OK); // OK */
    //                 }
    //             }else{
    //                 $this->response(['status'=>0,'message' => $this->lang->line('order_accepted')], REST_Controller::HTTP_OK); // OK */
    //             } 
    //         }else{
    //             $this->response([
    //                 'status' => 0,
    //                 'message' => $this->lang->line('not_found')
    //             ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code   
    //         }
    //     }else{
    //         $this->response([
    //             'status' => -1,
    //             'message' => ''
    //         ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
    //     }   
    // }


    //get order of driver
    public function getallOrder_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
             $commissionget = $this->driver_api_model->getSystemOptoin($this->post('type'));
            $commission=$commissionget->OptionValue;
            $detail = $this->driver_api_model->getallOrder($user_id);
            $this->response(['order_list'=>$detail,'status'=>1,'rider_commission'=>$commission,'message' => $this->lang->line('record_found'),'suspend'=>$tokenres->suspend], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }
    
    
    public function acceptOrder_post()
    {
       $order_id = ($this->post('order_id') != '') ? $this->post('order_id') : '';
        $order_status = ($this->post('order_status') != '') ? $this->post('order_status') : '';
        if ($order_id != '' && $order_status != '') {
            $this->Restaurant_model->UpdatedStatus('order_master', $order_id, $order_status);
            // adding order status
            $addData = array(
                'order_id' => $order_id,
                'order_status' => 'accepted_by_restaurant',
                'time' => date('Y-m-d H:i:s'),
                'status_created_by' => 'Admin'
            );
            $status_id = $this->Restaurant_model->addData('order_status', $addData);
            // adding notification for website
            $order_detail = $this->Restaurant_model->getSingleRow('order_master', 'entity_id', $order_id);
            $notification = array(
                'order_id' => $order_id,
                'user_id' => $order_detail->user_id,
                'notification_slug' => 'order_accepted',
                'view_status' => 0,
                'datetime' => date("Y-m-d H:i:s"),
            );
            $this->Restaurant_model->addData('user_order_notification', $notification);

            $this->response([
                'status' => 1,
                'msg'=>'accepted'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //change status after delivery
    public function deliveredOrder_post()
    {
        
        $this->getLang();
        $HC=$this->post('hand_cash');
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $weeklyBonus=0;
        $TotalBonus=0;
        $customer_pay =$this->post('customer_pay');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
            $order_id = $this->post('order_id');
            $status = $this->post('status');
            $subtotal = $this->post('subtotal');
            $add_data = array('order_id'=>$order_id,'order_status'=>$status,'time'=>date('Y-m-d H:i:s'),'status_created_by'=>'Driver');
            $this->driver_api_model->addRecord('order_status',$add_data);
           $detail = $this->driver_api_model->deliveredOrder($order_id, $user_id, $status, $subtotal);
            // adding notification for website
            $order_detail = $this->driver_api_model->getRecord('order_master','entity_id',$order_id);
            $notification = array(
                'order_id' => $order_id,
                'user_id' => $order_detail->user_id,
                'notification_slug' => 'order_delivered',
                'view_status' => 0,
                'datetime' => date("Y-m-d H:i:s"),
            );
            $this->driver_api_model->addRecord('user_order_notification',$notification);
             if(!empty($customer_pay)){
                 $lastdata = $this->driver_api_model->getLastRecord('riders_earning', 'rider_id', $user_id);
                 $weeklyOrders=$this->driver_api_model->getWeeklyOrders($this->post('user_id'));
                 $questOrder=$this->driver_api_model->getSystemMultiOptoin("questride",$this->post('type'));
                 if($weeklyOrders==$questOrder->OptionValue){
                     $bonus=$this->driver_api_model->getSystemMultiOptoin("questbonus",$this->post('type'));
                     $weeklyBonus=$bonus->OptionValue;
                     $TotalBonus=$lastdata->total_bonus?($lastdata->total_bonus + $weeklyBonus ) : $weeklyBonus;
                 }
                 else{
                     $TotalBonus=$lastdata->total_bonus;
                 }
                 $earn_total=$lastdata->total_earn;
                 $hand_total=$lastdata->total_hand;
                 $commissionget = $this->driver_api_model->getSystemOptoin($this->post('type'));
                 $commission=$commissionget->OptionValue;
                 $tot_earn= $earn_total !=null? $earn_total+ $commission:$commission ;
                 $tot_hand = $hand_total !=null?$HC+$hand_total:$HC;
                 //$hand_cash= ($this->post('rider_earn')) - $commission ;
                 $data = array(
                     'rider_id'=>$this->post('user_id'),
                     'order_id'=>$this->post('order_id'),
                     'customer_pay'=>$this->post('customer_pay'),
                     'restaurant_pay'=>$this->post('rest_pay'),
                     'hand_cash'=>$this->post('hand_cash'),
                      'rider_earning'=>$commission,
                      'total_earn'=>$tot_earn,
                      'total_hand'=>$tot_hand,
                      'weekly_bonus'=>$weeklyBonus,
                      'total_bonus'=>$TotalBonus,
                      'date' => date("Y-m-d"),
                     
                     );
                     $this->driver_api_model->addRecord('riders_earning',$data);
             }
            
            $this->response(['order_detail'=>$detail,'status'=>1,'message' =>$status,'totearn'=>$lastdata,'th'=>$tot_hand], REST_Controller::HTTP_OK); // OK */
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }   
    }
    //get ride history
    public function getRidingHistory_post(){
        $total_order= $this->driver_api_model->getNumberOfRecords($this->post('user_id'));
        $lastdata = $this->driver_api_model->getLastRecord('riders_earning', 'rider_id', $this->post('user_id'));
         $this->response(['tot_ride'=>$total_order,'last_data'=>$lastdata,'status'=>1,'message'=>"record found"], REST_Controller::HTTP_OK);
    }
    
    public function getRidingDateHistory_post(){
        $order= $this->driver_api_model->getTimeWiseRecord($this->post('start'),$this->post('end'),$this->post('user_id'));
       
         $this->response(['tot_ride'=>$order,'status'=>1,'message'=>"record found"], REST_Controller::HTTP_OK);
    }
    public function weeklyRide_post(){
        $data = $this->driver_api_model->getWeeklyOrders($this->post('user_id'));
         $this->response(['ride'=>$data,'status'=>1,'message'=>"record found"], REST_Controller::HTTP_OK);
    }
    //leader Board
    public function getLeaderBoard_post(){
        $data= $this->driver_api_model->getLeaderBoard();
         $this->response(['leader'=>$data,'status'=>1,'message'=>"record found"], REST_Controller::HTTP_OK);
        
    }
    //get order commission
    public function getCommissionList_post()
    {
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id);
        if($tokenres){
            $detail = $this->driver_api_model->getCommissionList($user_id);
            $this->response(['CommissionList'=>$detail,'status'=>1,'message' => $this->lang->line('record_found')], REST_Controller::HTTP_OK); // OK */
            $this->response(['status'=>1,'message'=> $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
        }else{
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code      
        }
    }
    //track driver location
    public function driverTracking_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id); 
        $foo=$this->post('foo');
        if($tokenres){
            if($this->post('latitude') && $this->post('longitude')){
                //$data = array('latitude'=>$this->post('latitude'),'longitude'=>$this->post('longitude'));
               // $this->driver_api_model->updateUser('driver_traking_map',$data,'driver_id',$user_id);
                $traking_data = array(
                    'latitude'=>$this->post('latitude'),
                    'longitude'=>$this->post('longitude'),
                    'driver_id'=>$user_id,
                    'foo'=>$foo?$foo:'empty',
                    'user_status'=>$tokenres->status,
                    'onoff'=>$tokenres->onoff,
                    'suspend'=>$tokenres->suspend,
                    'engage'=>$tokenres->engage
                );  
                $this->driver_api_model->addRecord('driver_traking_map',$traking_data);
                $this->response(['status'=>1,'message'=> $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) 
            }else{
                $this->response([
                    'status' => 0,
                    'message' =>  $this->lang->line('validation')
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
        $this->getLang();
        $token = $this->post('token');
        $userid = $this->post('user_id');        
        $tokenres = $this->driver_api_model->getRecord('users', 'entity_id',$userid);
        if($tokenres){
            $data = array('device_id'=>"",'onoff'=>0);            
            $this->driver_api_model->updateUser('users',$data,'entity_id',$tokenres->entity_id);
            $this->response(['status' => 1,'message' => $this->lang->line('user_logout')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    //get user lang
    public function getUserLanguage_post(){
        $this->getLang();
        $token = $this->post('token');
        $user_id = $this->post('user_id');
        $tokenres = $this->driver_api_model->checkToken($token, $user_id); 
        if($tokenres){
            $data = array('language_slug'=>$this->post('language_slug'));            
            $this->driver_api_model->updateUser('users',$data,'entity_id',$user_id);
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
        $this->getLang();
        $token = $this->post('token');
        $userid = $this->post('user_id');        
        $tokenusr = $this->driver_api_model->checkToken($token, $userid);
        if($tokenusr){
            $data = array('device_id'=>$this->post('firebase_token'));
            $this->driver_api_model->updateUser('users',$data,'entity_id',$userid);  
            $this->response(['status' => 1,'message' => $this->lang->line('success_update')], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
       public function handover_post(){
        $order_id = ($this->post('entity_id') != '') ? $this->post('entity_id') : ''; 
         if ($order_id) {
              $this->db->set('handover', 1)->where('entity_id', $order_id)->update('order_master');
               $this->db->set('handover_time', date('Y-m-d H:i:s'))->where('entity_id', $order_id)->update('order_master');

             $this->response([
                'status' => 1,
                
            ], REST_Controller::HTTP_OK);
         }
         else{
              $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK);
         }
        
        
        
    }
    
       public function rejectOrder_post()
    {
        $order_id = ($this->post('order_id') != '') ? $this->post('order_id') : '';
        if ($order_id) {
            $this->db->set('order_status', 'cancel')->where('entity_id', $order_id)->update('order_master');
            $addData = array(
                'order_id' => $order_id,
                'order_status' => 'cancel',
                'time' => date('Y-m-d H:i:s'),
                'status_created_by' => 'Admin'
            );

            $data = $this->Restaurant_model->getData($order_id);
            $this->Restaurant_model->addData('order_status', $addData);
            //$this->Restaurant_model->updateEngage($order_id);
            $userdata = $this->Restaurant_model->getUserDate($data->user_id);
            $message = $this->lang->line('order_canceled');
            $device_id = $userdata->device_id;
            $this->sendFCMRegistration($device_id, $message, 'cancel', $data->restaurant_id);

            $this->response([
                'status' => 1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
        else {
            $this->response([
                'status' => -1,
                'message' => ''
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }

    function sendFCMRegistration($registrationIds, $message, $order_status, $restaurant_id)
    {
        if ($registrationIds) {
            #prep the bundle
            $fields = array();

            $fields['to'] = $registrationIds; // only one user to send push notification
            $fields['notification'] = array('body'  => $message, 'sound' => 'default');
            if ($order_status == "delivered") {
                $fields['data'] = array('screenType' => 'delivery', 'restaurant_id' => $restaurant_id);
            } else {
                $fields['data'] = array('screenType' => 'order');
            }
            $headers = array(
                'Authorization: key=' . Driver_FCM_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
}
?>
