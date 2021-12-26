<?php
class Restaurant_model extends CI_Model {
    function __construct()
    {
        parent::__construct();      
    }
     /***************** General API's Function *****************/
    public function getLanguages($current_lang){
        $result = $this->db->select('*')->get_where('languages',array('language_slug'=>$current_lang))->first_row();
        return $result;
    }
  
    public function getInvoice($order_id){
        
        $this->db->select('order_master.commission_rate,order_master.commission_value,order_master.total_rate,order_master.vat,order_master.sd,order_master.subtotal,order_master.coupon_discount,order_master.coupon_amount,order_master.delivery_charge,order_detail.item_detail,restaurant.commission');
          $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
           $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
          $this->db->where('order_master.entity_id',$order_id);
          $data= $this->db->get('order_master')->result();
           unserialize($menu_item->receiver_details);
             foreach ($data as $key => $value) {
                 $res_subtotal = $value->subtotal + $value->vat +$value->sd ;
                 $value->res_subtotal=$res_subtotal;
                 $c=number_format(($value->commission/100),2);
                 $temp=number_format((($value->subtotal)*$c),2);
                 $value->commissioned_total=$value->commission_value;
                 $commission=$value->commission_rate;
                 $value->commissionless_total=$res_subtotal-$value->commission_value;

              $x=unserialize($value->item_detail);
              $value->item=$x;
          }
          return $data;
          
        
    }
 
   public function getAllorders($res_id){
       
        $this->db->select('entity_id,created_date,total_rate,order_status,handover,status'); 
       $this->db->where('restaurant_id',$res_id);
        $this->db->where('order_status!=',"cancel");
         $this->db->where('handover',0);
          $this->db->order_by('entity_id','desc');
        $placed=$this->db->get('order_master')->result();
         $this->db->select('entity_id,created_date,total_rate,order_status'); 
       $this->db->where('restaurant_id',$res_id);
        //$this->db->where('order_status',"placed");
        $this->db->where('DATE(created_date)',date('Y-m-d'));
         $this->db->order_by('entity_id','desc');
         $today=$this->db->get('order_master')->result();
         $data=array("placed"=>$placed,'today'=>$today);
        return $data;
     
   }
   public function getOrderHistory($res_id,$fromDate,$toDate){
       
       $this->db->select('order_master.entity_id,order_master.created_date,order_master.total_rate,order_master.subtotal');
        $this->db->where('order_master.restaurant_id',$res_id);
        $this->db->where('order_master.order_status',"delivered");
         $this->db->where('order_master.created_date >=', date('Y-m-d H:i:s', strtotime($fromDate)));
        $this->db->where('order_master.created_date <', date('Y-m-d H:i:s', strtotime($toDate)));
         $data=$this->db->get('order_master')->result();
         
          $this->db->select("COUNT(order_master.entity_id) as totalOrder,SUM(order_master.subtotal) as foodbill,restaurant.commission");
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        // $this->db->where('restaurant.entity_id',$res_id);
         $this->db->where('order_master.restaurant_id',$res_id);
        $this->db->where('order_master.order_status',"delivered");
        //$this->db->where('order_master.created_date "'. date('Y-m-d H:i:s', strtotime($fromDate)). '" and "'. date('Y-m-d H:i:s', strtotime($toDate)).'"');
          $this->db->where('order_master.created_date >=', date('Y-m-d H:i:s', strtotime($fromDate)));
        $this->db->where('order_master.created_date <=', date('Y-m-d H:i:s', strtotime($toDate)));
         $summary=$this->db->get('order_master')->result();
         $commision=intval(($summary[0]->foodbill*($summary[0]->commission/100)));
         $received=intval($summary[0]->foodbill)-intval($commision);
         $summary[0]->commision_calc=$commision>0?$commision:0;
         $summary[0]->foodbill=intval($summary[0]->foodbill);
          $summary[0]->received=$received>0?$received:0;
         
        return $rasult=array('summary'=>$summary,'data'=>$data);
       
       
   }
   
   
    public function getRecord($table,$fieldName,$where)
    {
        $this->db->where($fieldName,$where);
        return $this->db->get($table)->first_row();
    } 
    public function getLastRecord($table,$fieldName,$where)
    {
         $this->db->where($fieldName,$where);
         $this->db->order_by('id','desc');
        return $this->db->get($table)->first_row();
         
    }
    public function updateEngage($order_id)
	{
		$this->db->select('driver_id');
		$result = $this->db->get_where('order_driver_map', array('order_id' => $order_id, 'cancel_reason' => null, 'cancel' => 0, 'no_response' => 0))->first_row();
		$this->db->set('engage', 0)->where('entity_id', $result->driver_id)->update('users');
	}
      public function getMenuRecord($where)
    {


        $this->db->select('menu.entity_id as menu_id,menu.status,menu.name,menu.price,c.name as category,c.entity_id as category_id');
        $this->db->join('category as c', 'menu.category_id = c.entity_id', 'left');

        $this->db->where('menu.restaurant_id', $where);
      
        $result = $this->db->get('restaurant_menu_item as menu')->result();

        $menu = array();
        foreach ($result as $key => $value) {
            $offer_price = '';
      
            // $offer_price = ($offer_price) ? $offer_price : '';
            if (!isset($menu[$value->category_id])) {
                $menu[$value->category_id] = array();
                $menu[$value->category_id]['category_id'] = $value->category_id;
                $menu[$value->category_id]['category_name'] = $value->category;
            }
          
                $menu[$value->category_id]['items'][]  = array('menu_id' => $value->menu_id, 'name' => $value->name, 'price' => $value->price, 'offer_price' => $offer_price, 'menu_detail' => $value->menu_detail, 'image' => $image, 'recipe_detail' => $value->recipe_detail, 'availability' => $value->availability, 'is_veg' => $value->is_veg, 'is_customize' => $value->check_add_ons, 'is_deal' => $value->is_deal, 'status' => $value->status);
            //}
        }
        $finalArray = array();
        $final = array();
        $semifinal = array();
        $new = array();
        foreach ($menu as $nm => $va) {
            $final = array();
            foreach ($va['items'] as $kk => $items) {
             
                array_push($final, $items);
            }
            $va['items'] = $final;
            array_push($finalArray, $va);
        }
        return $finalArray;
        
         
    }
    
    public function checkEngage($id)
    {
        $this->db->select('engage');
        return $this->db->get_where('users', array('entity_id' => $id))->first_row();
    }
    
    public function checkAccept($order_id)
    {
        $this->db->select('status');
        return $this->db->get_where('order_master', array('entity_id' => $order_id))->first_row();
    }
    
    public function getNumberOfRecords($rider_id){
       $this->db->where('rider_id',$rider_id);
    //$this->db->where('filed2',$filed2);
    return $this->db->get('riders_earning')->num_rows();
    }
    public function getTimeWiseRecord($start,$end,$rider_id){
        $this->db->select("COUNT(id) as count");
        $this->db->select_sum('rider_earning' );
        $this->db->select_sum('hand_cash' );
        $this->db->select_sum('weekly_bonus' );
        $this->db->where('rider_id',$rider_id);
        if(!empty($end) && $start !=$end && $end != null && $end!=undefined && $end!="null" && $end!="Invalid date"){
        $this->db->where('date>=', $start);
        $this->db->where('date <=', $end);
        }
        else{
            $this->db->where('date', $start);
            
        }
        return $this->db->get('riders_earning')->result();
    }
    //leaderboard
      public function getLeaderBoard()
    {
        $this->db->select('id,rider_id,COUNT(id) as c,users.first_name');
        $this->db->join('users','users.entity_id = riders_earning.rider_id','left');
        $this->db->where('date',date('Y-m-d'));
        $this->db->group_by('rider_id');
        $this->db->order_by(c,'desc');
         $this->db->limit(10);
        return $this->db->get('riders_earning')->result();
    }
    //get record with multiple where
    public function getRecordMultipleWhere($table,$whereArray)
    {
        $this->db->where($whereArray);
        return $this->db->get($table)->first_row();
    }
    
    
    // public function addData($tblName, $Data,$driver)
    // {
    //     $this->db->set('engage', 1)->where('entity_id', $driver)->update('users');
        
    //     $this->db->insert($tblName, $Data);
    //     return $this->db->insert_id();
    // }
    public function getWeeklyOrders($user_id){
        $this->db->select('id,date');
        $this->db->where('rider_id',$user_id);
        $this->db->where("YEARWEEK(riders_earning.date, 1) = YEARWEEK(CURDATE(), 1)");
        return $this->db->get('riders_earning')->num_rows();
    }
  
    // Login
    public function getLogin($email,$password)
    {        
        $enc_pass  = md5(SALT.$password);
        $this->db->select('users.entity_id,users.first_name ,users.email as PhoneNumber,users.last_name,users.status,users.active,users.mobile_number,users.notification,restaurant.entity_id as res_id,restaurant.name  as FirstName,restaurant.image');
        $this->db->join('restaurant','users.entity_id = restaurant.created_by','left');
        $this->db->where('users.email',$email);
        $this->db->where('password',$enc_pass);
        $this->db->where('user_type','Admin');
       $data= $this->db->get('users')->first_row(); 
       $data->image=image_url.$data->image;
       return $data;
       
    }
    // Update User
    public function updateUser($tableName,$data,$fieldName,$UserID)
    {
        $this->db->where($fieldName,$UserID);
        $this->db->update($tableName,$data);
    }
    
    // public function updateEngage($user){
    //     $this->db->set('engage', 0)->where('entity_id', $user)->update('users');
    // }
    
    // check token for every API Call
    public function checkToken($token, $userid)
    {
        return $this->db->get_where('users',array('email'=>$token,' entity_id'=>$userid))->first_row();
    }
    public function getSystemOptoin($OptionSlug)
    {
        $this->db->select('OptionValue');
        $this->db->where('OptionSlug', $OptionSlug);
        return $this->db->get('system_option')->first_row();
    }
     public function getSystemMultiOptoin($OptionSlug,$type)
    {
        $this->db->select('OptionValue');
        $this->db->where('OptionSlug', $OptionSlug);
        $this->db->where('user_type', $type);
        return $this->db->get('system_option')->first_row();
    }
    //availavle
     public function isOrderAvailable($orderid,$driverid){
         $x=0;
        $this->db->select('driver_id,order_id');
        $this->db->where('order_driver_map.order_id',$orderid);
         $this->db->order_by('order_driver_map.driver_map_id','desc');
        $this->db->limit(1);
        $data =  $this->db->get('order_driver_map')->result();
        if(!empty($data)){
             foreach ($data as $key => $value) {
              $x=$value->driver_id;
          }
        if( $driverid == $x)
        {
            return true;
            // return  $driverid;
        }
        else{
           return false;
            //return $data;
        }
        }
        else{
            return false;
        }
        
        
    }
    // Common Add Records
    public function addRecord($table,$data)
    {
        $this->db->insert($table,$data);
        return $this->db->insert_id();
    }
    // Common Add Records Batch
    public function addRecordBatch($table,$data)
    {
        return $this->db->insert_batch($table, $data);
    }
    public function deleteRecord($table,$fieldName,$where)
    {
        $this->db->where($fieldName,$where);
        return $this->db->delete($table);
    }
    //get event
    public function getallOrder($user_id){
        $currentDateTime = date('Y-m-d H:i:s');
        //current
        $this->db->select('order_detail.restaurant_detail,order_detail.item_detail, order_detail.receiver_details,order_detail.order_id,order_driver_map.driver_map_id,order_master.order_status,order_master.payee,order_master.delivery_charge,order_master.business_type,order_master.vat,order_master.sd,order_master.commission_rate,order_master.total_rate,order_master.subtotal,order_master.commission_value,order_master.created_date,order_detail.user_detail,users.mobile_number,users.image,restaurant_address.latitude,restaurant_address.longitude,currencies.currency_symbol,currencies.currency_code,order_master.transaction_id,order_master.payment_option');
        $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
        $this->db->join('order_driver_map','order_master.entity_id = order_driver_map.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->join('restaurant_address','order_master.restaurant_id = restaurant_address.resto_entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('currencies','restaurant.currency_id = currencies.currency_id','left');
        $this->db->where('order_driver_map.driver_id',$user_id);
        $this->db->where('(order_master.order_status != "delivered" AND order_master.order_status != "cancel")');
        $this->db->where('order_master.order_delivery','Delivery');
        $this->db->where('order_driver_map.cancel_reason =', NULL);
        $this->db->where('order_driver_map.no_response', 0);
        // $this->db->where('DATE(order_master.order_date)',date('Y-m-d'));
        $this->db->order_by('order_master.entity_id','desc');
        $current_order = $this->db->get('order_master')->result();
  
        $current = array();
        if(!empty($current_order)){
            foreach ($current_order as $key => $value) {
                if(!isset($value->order_id)){
                    $current[$value->order_id] = array();
                }
                if(isset($value->order_id)){
                    $business_type = $value->business_type;
                    $item_detail=unserialize($value->item_detail);
                   // $itms=($item_detail);
                    
                    $restaurant_detail = unserialize($value->restaurant_detail);
                    $user_detail = unserialize($value->user_detail);
                    $receiver_details = unserialize($value->receiver_details);
                    $receiver = $receiver_details;
                    $current[$value->order_id]['item_detail'] = $item_detail;
                    $current[$value->order_id]['commission_value'] = $value->commission_value;
                    $current[$value->order_id]['payment_option'] = $value->payment_option;
                    $current[$value->order_id]['vat'] = round($value->vat);
                    $current[$value->order_id]['sd'] = round($value->sd);
                    $current[$value->order_id]['commission_rate'] = $value->commission_rate;
                    $current[$value->order_id]['delivery_charge'] = $value->delivery_charge;
                    $current[$value->order_id]['business_type'] = $business_type;
                    // $current[$value->order_id]['name'] = $restaurant_detail->name;
                    $current[$value->order_id]['name'] = ($business_type == 1) ? $restaurant_detail->name : "Parcel";
                    $current[$value->order_id]['res_phone_number'] = $restaurant_detail->phone_number;
                    $current[$value->order_id]['res_address'] = $restaurant_detail->address;
                    $current[$value->order_id]['image'] = ($restaurant_detail->image)?image_url.$restaurant_detail->image:'';
                    $current[$value->order_id]['res_latitude'] = $value->latitude;
                    $current[$value->order_id]['res_longitude'] = $value->longitude;
                    $current[$value->order_id]['order_id'] = $value->order_id;
                    $current[$value->order_id]['driver_map_id'] = $value->driver_map_id;
                    $current[$value->order_id]['subtotal'] = $value->subtotal;
                    $current[$value->order_id]['total_rate'] = $value->total_rate;
                    $current[$value->order_id]['currency_code'] = $value->currency_code;
                    $current[$value->order_id]['currency_symbol'] = $value->currency_symbol;
                    $current[$value->order_id]['order_status'] = $value->order_status;
                    $current[$value->order_id]['user_name'] = $user_detail['first_name'];
                    $current[$value->order_id]['latitude'] = (isset($user_detail['latitude']))?$user_detail['latitude']:'';
                    $current[$value->order_id]['longitude'] = (isset($user_detail['longitude']))?$user_detail['longitude']:'';
                    $current[$value->order_id]['address'] = $user_detail['address'].' '.$user_detail['landmark'].' '.$user_detail['zipcode'].' '.$user_detail['city'];
                    $current[$value->order_id]['phone_number'] = $value->mobile_number;
                    $current[$value->order_id]['user_image'] = ($value->image)?image_url.$value->image:'';
                    $current[$value->order_id]['date'] = date('Y-m-d H:i',strtotime($value->created_date));
                    $current[$value->order_id]['transaction_id'] = $value->transaction_id;
                    $current[$value->order_id]['order_type'] = ($value->transaction_id)?'paid':'cod';
                    $current[$value->order_id]['payee'] = ($business_type == 2) ? $value->payee : "0";
                    $current[$value->order_id]['sender_details'] = ($business_type == 2) ? $user_detail : "";
                    $current[$value->order_id]['receiver_details'] = ($business_type == 2) ? $receiver : "";
                }
            }
        }
        $finalArray = array();
        foreach ($current as $key => $val) {
           $finalArray[] = $val; 
        }
        $data['current'] = $finalArray;
        //past
        $this->db->select('order_detail.restaurant_detail,order_detail.item_detail,order_detail.receiver_details,order_detail.order_id,order_driver_map.driver_map_id,order_master.business_type,order_master.order_status,order_master.payee,order_driver_map.cancel_reason,order_master.vat,order_master.sd,order_master.commission_value,order_master.total_rate,order_master.subtotal,order_master.created_date,order_detail.user_detail,users.mobile_number,users.image,restaurant_address.latitude,restaurant_address.longitude,currencies.currency_symbol,currencies.currency_code,order_master.transaction_id');
        $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
        $this->db->join('order_driver_map','order_master.entity_id = order_driver_map.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
		$this->db->join('restaurant_address','order_master.restaurant_id = restaurant_address.resto_entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('currencies','restaurant.currency_id = currencies.currency_id','left');
        $this->db->where('order_driver_map.driver_id',$user_id);
        $this->db->where('order_driver_map.is_accept',1);
        $this->db->where('(order_master.order_status = "delivered" OR order_master.order_status = "cancel")');
         $this->db->where('order_master.order_delivery','Delivery');
        $this->db->order_by('order_master.entity_id','desc');
        $past_order = $this->db->get('order_master')->result();
        $past = array();
        if(!empty($past_order)){
            foreach ($past_order as $key => $value) {
                if(!isset($value->order_id)){
                    $past[$value->order_id] = array();
                }
                if(isset($value->order_id)){
                    $restaurant_detail = unserialize($value->restaurant_detail);
                    $user_detail = unserialize($value->user_detail);
                    $receiver_details = unserialize($value->receiver_details);
                    $item_detail=unserialize($value->item_detail);
                    
                    $past[$value->order_id]['name'] = ($business_type == 1) ? $restaurant_detail->name : "Parcel";
                    $past[$value->order_id]['res_phone_number'] = $restaurant_detail->phone_number;
                    $past[$value->order_id]['res_address'] = $restaurant_detail->address;
                     $past[$value->order_id]['itm_dtl'] =$item_detail;
                      $past[$value->order_id]['vat'] = round($value->vat);
                    $past[$value->order_id]['sd'] = round($value->sd);
                    $past[$value->order_id]['commission_value'] = $value->commission_value;
                    $past[$value->order_id]['image'] = ($restaurant_detail->image)?image_url.$restaurant_detail->image:'';
                    $past[$value->order_id]['res_latitude'] = $value->latitude;
                    $past[$value->order_id]['res_longitude'] = $value->longitude;
                    $past[$value->order_id]['order_id'] = $value->order_id;
                    $past[$value->order_id]['driver_map_id'] = $value->driver_map_id;
                    $past[$value->order_id]['subtotal'] = $value->subtotal;
                    $past[$value->order_id]['total_rate'] = $value->total_rate;
                    $past[$value->order_id]['currency_code'] = $value->currency_code;
                    $past[$value->order_id]['currency_symbol'] = $value->currency_symbol;
                    $past[$value->order_id]['order_status'] = $value->order_status;
                    $past[$value->order_id]['user_name'] = $user_detail['first_name'];
                    $past[$value->order_id]['latitude'] = (isset($user_detail['latitude']))?$user_detail['latitude']:'';
                    $past[$value->order_id]['longitude'] = (isset($user_detail['longitude']))?$user_detail['longitude']:'';
                    $past[$value->order_id]['address'] = $user_detail['address'].' '.$user_detail['landmark'].' '.$user_detail['zipcode'].' '.$user_detail['city'];
                    $past[$value->order_id]['phone_number'] = $value->mobile_number;
                    $past[$value->order_id]['user_image'] = ($value->image)?image_url.$value->image:'';
                    $past[$value->order_id]['date'] = date('Y-m-d H:i',strtotime($value->created_date));
                    $past[$value->order_id]['transaction_id'] = $value->transaction_id;
                    $past[$value->order_id]['order_type'] = ($value->transaction_id)?'paid':'cod';
                    $past[$value->order_id]['payee'] = ($business_type == 2) ? $value->payee : "0";
                    $past[$value->order_id]['sender_details'] = ($business_type == 2) ? $user_detail : "";
                    $past[$value->order_id]['receiver_details'] = ($business_type == 2) ? $receiver_details : "";
                }
            }

        }
        $final = array();
        foreach ($past as $key => $val) {
           $final[] = $val; 
        }
        $data['past'] = $final;
        return $data;
    } 
    //accept order
    public function acceptOrder($order_id,$driver_map_id,$user_id)
    {
        //$this->db->set('engage', 1)->where('entity_id', $user_id)->update('users');
        $this->db->set('is_accept', 1)->where('driver_id', $user_id)->where('order_id', $order_id)->where('driver_map_id', $driver_map_id)->update('order_driver_map');
        // $count = $this->db->set('is_accept',1)->where('driver_id',$user_id)->where('order_id', $order_id)->where('driver_map_id',$driver_map_id)->update('order_driver_map');
        // if($count == 1){
        //     $this->db->where('order_id', $order_id);
        //     $this->db->where('is_accept !=',1);
        //     $this->db->where('driver_id !=',$user_id);
        //     $this->db->delete('order_driver_map');
        // }
        $this->db->set('order_status','preparing')->where('entity_id', $order_id)->update('order_master');
        //get users to send notifcation
        $this->db->select('users.entity_id,users.device_id,users.language_slug,users.first_name,users.last_name,users.mobile_number,order_detail.user_detail,restaurant_address.latitude,restaurant_address.longitude');
        $this->db->join('order_master','users.entity_id = order_master.user_id','left');
        $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
        $this->db->join('restaurant_address','order_master.restaurant_id = restaurant_address.resto_entity_id','left');
        $this->db->where('order_master.entity_id',$order_id);
        $device = $this->db->get('users')->first_row();
        
        // load language
        $languages = $this->db->select('*')->get_where('languages',array('language_slug'=>$device->language_slug))->first_row();
        $this->lang->load('messages_lang', $languages->language_directory);
        
        $info = array();
        if($device->device_id){  
            #prep the bundle
            $fields = array();            
            $message = $this->lang->line('order_preparing');
            $fields['to'] = $device->device_id; // only one user to send push notification
            $fields['notification'] = array ('body'  => $message,'sound'=>'default');
            $fields['data'] = array ('screenType'=>'order');
           
            $headers = array (
                'Authorization: key=' . Driver_FCM_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server    
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($ch);
            curl_close($ch);  
        } 
        $user_detail = unserialize($device->user_detail);
        $info['address'] = $user_detail['address'].' '.$user_detail['landmark'].' '.$user_detail['zipcode'].' '.$user_detail['city'];
        $info['latitude'] = (isset($user_detail['latitude']))?$user_detail['latitude']:'';
        $info['longitude'] = (isset($user_detail['longitude']))?$user_detail['longitude']:'';
        $info['phone_number'] = $device->mobile_number;
        $info['res_latitude'] = $device->latitude;
        $info['res_longitude'] = $device->longitude;
        $info['name'] = $device->first_name.' '.$device->last_name; 
        $info['order_user_id'] = $device->entity_id;
        return $info;
    }
    //order delivered
    // public function deliveredOrder($order_id,$status,$subtotal)
    // {   
    //     $this->db->set('order_status',$status)->where('entity_id', $order_id)->update('order_master');
    //     if($status == 'delivered'){
    //         $this->db->select('order_driver_map.distance');
    //         $this->db->join('order_driver_map','order_master.entity_id = order_driver_map.order_id','left');
    //         $this->db->where('order_master.entity_id',$order_id);
    //         $distance = $this->db->get('order_master')->first_row();
            
    //         $comsn = '';
    //         if($distance->distance > 3){
    //             $this->db->select('OptionValue');
    //             $comsn = $this->db->get_where('system_option',array('OptionSlug'=>'driver_commission_more'))->first_row();
    //         }else{
    //             $this->db->select('OptionValue');
    //             $comsn = $this->db->get_where('system_option',array('OptionSlug'=>'driver_commission_less'))->first_row(); 
    //         }
    //         if($comsn){
    //             $data = array('driver_commission'=>$comsn->OptionValue,'commission'=>$comsn->OptionValue);
    //             $this->db->where('order_id', $order_id);
    //             $this->db->update('order_driver_map',$data);
    //         } 
    //     }

    //     $this->db->select('item_detail,user_detail,currencies.currency_symbol,currencies.currency_code,order_master.restaurant_id');
    //     $this->db->join('order_master','order_detail.order_id = order_master.entity_id','left');
    //     $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
    //     $this->db->join('currencies','restaurant.currency_id = currencies.currency_id','left');
    //     $this->db->where('order_id',$order_id);
    //     $detail =  $this->db->get('order_detail')->first_row();
    //     $info = array();
    //     if(!empty($detail)){
    //         $order_detail = unserialize($detail->item_detail);
    //         $user_detail = unserialize($detail->user_detail);
    //         $info['order_detail'] = $order_detail;
    //         $info['currency_code'] = $detail->currency_code;
    //         $info['currency_symbol'] = $detail->currency_symbol;
    //         $info['address'] = $user_detail['address'].' '.$user_detail['landmark'].' '.$user_detail['zipcode'].' '.$user_detail['city'];
    //     }

    //     //get users to send notifcation
    //     $this->db->select('users.entity_id,users.device_id,users.language_slug');
    //     $this->db->join('order_master','users.entity_id = order_master.user_id','left');
    //     $this->db->where('order_master.entity_id',$order_id);
    //     $device = $this->db->get('users')->first_row();
    //     // load language
    //     $languages = $this->db->select('*')->get_where('languages',array('language_slug'=>$device->language_slug))->first_row();
    //     $this->lang->load('messages_lang', $languages->language_directory);
 
    //     if($device->device_id){  
    //         #prep the bundle
    //         $fields = array();            
    //         $message = $this->lang->line('push_order_delived');
    //         $fields['to'] = $device->device_id; // only one user to send push notification
    //         $fields['notification'] = array ('body'  => $message,'sound'=>'default');
    //         $fields['data'] = array ('screenType'=>'delivery','restaurant_id'=>$detail->restaurant_id);
           
    //         $headers = array (
    //             'Authorization: key=' . Driver_FCM_KEY,
    //             'Content-Type: application/json'
    //         );
    //         #Send Reponse To FireBase Server    
    //         $ch = curl_init();
    //         curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    //         curl_setopt( $ch,CURLOPT_POST, true );
    //         curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    //         curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    //         curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    //         curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    //         $result = curl_exec($ch);
    //         curl_close($ch);  
    //     } 
    //     return $info;

    // }

    public function deliveredOrder($order_id, $user_id, $status, $subtotal)
    {   
        
        $this->db->set('order_status',$status)->where('entity_id', $order_id)->update('order_master');
        if($status == 'delivered'){
            $this->db->set('engage', 0)->where('entity_id', $user_id)->update('users');
            
            $this->db->select('order_driver_map.distance');
            $this->db->join('order_driver_map','order_master.entity_id = order_driver_map.order_id','left');
            $this->db->where('order_master.entity_id',$order_id);
            $distance = $this->db->get('order_master')->first_row();
            
            $comsn = '';
            if($distance->distance > 3){
                $this->db->select('OptionValue');
                $comsn = $this->db->get_where('system_option',array('OptionSlug'=>'driver_commission_more'))->first_row();
            }else{
                $this->db->select('OptionValue');
                $comsn = $this->db->get_where('system_option',array('OptionSlug'=>'driver_commission_less'))->first_row(); 
            }
            if($comsn){
                $data = array('driver_commission'=>$comsn->OptionValue,'commission'=>$comsn->OptionValue);
                $this->db->where('order_id', $order_id);
                $this->db->update('order_driver_map',$data);
            } 
        }

        $this->db->select('item_detail,user_detail,currencies.currency_symbol,currencies.currency_code,order_master.restaurant_id');
        $this->db->join('order_master','order_detail.order_id = order_master.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('currencies','restaurant.currency_id = currencies.currency_id','left');
        $this->db->where('order_id',$order_id);
        $detail =  $this->db->get('order_detail')->first_row();
        $info = array();
        if(!empty($detail)){
            $order_detail = unserialize($detail->item_detail);
            $user_detail = unserialize($detail->user_detail);
            $info['order_detail'] = $order_detail;
            $info['currency_code'] = $detail->currency_code;
            $info['currency_symbol'] = $detail->currency_symbol;
            $info['address'] = $user_detail['address'].' '.$user_detail['landmark'].' '.$user_detail['zipcode'].' '.$user_detail['city'];
        }

        //get users to send notifcation
        $this->db->select('users.entity_id,users.device_id,users.language_slug');
        $this->db->join('order_master','users.entity_id = order_master.user_id','left');
        $this->db->where('order_master.entity_id',$order_id);
        $device = $this->db->get('users')->first_row();
        // load language
        $languages = $this->db->select('*')->get_where('languages',array('language_slug'=>$device->language_slug))->first_row();
        $this->lang->load('messages_lang', $languages->language_directory);
        if($status == "delivered"){
            if($device->device_id){  
                #prep the bundle
                $fields = array();            
                $message = $this->lang->line('push_order_delived');
                $fields['to'] = $device->device_id; // only one user to send push notification
                $fields['notification'] = array ('body'  => $message,'sound'=>'default');
                $fields['data'] = array ('screenType'=>'delivery','restaurant_id'=>$detail->restaurant_id);
               
                $headers = array (
                    'Authorization: key=' . Driver_FCM_KEY,
                    'Content-Type: application/json'
                );
                #Send Reponse To FireBase Server    
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $result = curl_exec($ch);
                curl_close($ch);  
            }
        }
          if($status == "ongoing"){
            if($device->device_id){  
                #prep the bundle
                $fields = array();            
                $message =" Your order is on the way";
                $fields['to'] = $device->device_id; // only one user to send push notification
                $fields['notification'] = array ('body'  => $message,'sound'=>'default');
                $fields['data'] = array ('screenType'=>'order','restaurant_id'=>$detail->restaurant_id);
               
                $headers = array (
                    'Authorization: key=' . Driver_FCM_KEY,
                    'Content-Type: application/json'
                );
                #Send Reponse To FireBase Server    
                $ch = curl_init();
                curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
                curl_setopt( $ch,CURLOPT_POST, true );
                curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
                curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
                $result = curl_exec($ch);
                curl_close($ch);  
            }
        }
 
        return $info;

    }
   

    //get commission list
    public function getCommissionList($user_id)
    {
        //last order
        $this->db->select('order_master.total_rate,order_master.order_status,order_status.time,order_detail.restaurant_detail,order_detail.user_detail,order_driver_map.order_id,order_driver_map.driver_id,order_driver_map.commission,order_master.order_status,order_master.total_rate,currencies.currency_symbol,currencies.currency_code,riders_earning.customer_pay,riders_earning.restaurant_pay');
        
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('currencies','restaurant.currency_id = currencies.currency_id','left');
        $this->db->join('order_driver_map','order_master.entity_id = order_driver_map.order_id','left');
        $this->db->join('order_status','order_driver_map.order_id = order_status.order_id','left');
        $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
        $this->db->join('riders_earning','order_master.entity_id = riders_earning.order_id','left');
        $this->db->where('order_driver_map.driver_id',$user_id);
        $this->db->where('(order_master.order_status = "delivered" OR order_master.order_status = "cancel")');
        $this->db->order_by('order_master.entity_id','desc');
        $this->db->limit(1);
        $details =  $this->db->get('order_master')->result();
        $last_address = array();
        $last_user_id = '';
        $finalArray = array();
        if(!empty($details)){
            foreach ($details as $key => $value) {
                $last_user_id = $value->order_id;
                if(!isset($value->order_id)){
                    $last_address[$value->order_id] = array();
                }
                if(isset($value->order_id)){
                    $address = unserialize($value->user_detail);
                    $restaurant_detail = unserialize($value->restaurant_detail);
                    $last_address[$value->order_id]['time'] = ($value->time)?date('h:i A',strtotime($value->time)):'';
                    $last_address[$value->order_id]['date'] =  ($value->time)?date('l j M',strtotime($value->time)):'';
                    $last_address[$value->order_id]['order_status'] = ucfirst($value->order_status);
                    $last_address[$value->order_id]['total_rate'] = $value->total_rate;
                    $last_address[$value->order_id]['order_id'] = $value->order_id;
                    $last_address[$value->order_id]['restaurant_pay'] = $value->restaurant_pay;
                     $last_address[$value->order_id]['customer_pay'] = $value->customer_pay;
                    $last_address[$value->order_id]['commission'] = ($value->commission)?$value->commission:'';
                    $last_address[$value->order_id]['name'] = $restaurant_detail->name;
                    $last_address[$value->order_id]['image'] = ($restaurant_detail->image)?image_url.$restaurant_detail->image:'';
                    $last_address[$value->order_id]['address'] = $address['address'].' '.$address['landmark'].' '.$address['zipcode'].' '.$address['city'];
                    $last_address[$value->order_id]['currency_symbol'] = $restaurant_detail->currency_symbol;
                    $last_address[$value->order_id]['currency_code'] = $restaurant_detail->currency_code;
                }
            }
            foreach ($last_address as $key => $val) {
               $finalArray[] = $val; 
            }
        }
       
        $data['last'] = $finalArray;
        //previous order
        $this->db->select('order_master.total_rate,order_master.order_status,order_status.time,order_detail.restaurant_detail,order_detail.user_detail,order_driver_map.order_id,order_driver_map.driver_id,order_driver_map.commission,order_master.order_status,order_master.total_rate,riders_earning.customer_pay,riders_earning.restaurant_pay');
        $this->db->join('order_driver_map','order_master.entity_id = order_driver_map.order_id','left');
        $this->db->join('order_status','order_driver_map.order_id = order_status.order_id','left');
        $this->db->join('order_detail','order_master.entity_id = order_detail.order_id','left');
         $this->db->join('riders_earning','order_master.entity_id = riders_earning.order_id','left');
        $this->db->where('order_driver_map.driver_id',$user_id);
        if($last_user_id){
             $this->db->where('order_driver_map.order_id !=',$last_user_id);
        }
        $this->db->where('(order_master.order_status = "delivered" OR order_master.order_status = "cancel")');
        $this->db->where('order_driver_map.is_accept',1);
        $this->db->order_by('order_master.entity_id','desc');
        $details =  $this->db->get('order_master')->result();
        $user_address = array();
        $final = array();
        if(!empty($details)){
            foreach ($details as $key => $value) {
                if(!isset($value->order_id)){
                    $user_address[$value->order_id] = array();
                }
                if(isset($value->order_id)){
                    $address = unserialize($value->user_detail);
                    $restaurant_detail = unserialize($value->restaurant_detail);
                    $user_address[$value->order_id]['time'] = ($value->time)?date('h:i A',strtotime($value->time)):'';
                    $user_address[$value->order_id]['date'] =  ($value->time)?date('l j M',strtotime($value->time)):'';
                    $user_address[$value->order_id]['order_status'] = ucfirst($value->order_status);
                    $user_address[$value->order_id]['total_rate'] = $value->total_rate;
                    $user_address[$value->order_id]['order_id'] = $value->order_id;
                     $user_address[$value->order_id]['restaurant_pay'] = $value->restaurant_pay;
                     $user_address[$value->order_id]['customer_pay'] = $value->customer_pay;
                    $user_address[$value->order_id]['commission'] = ($value->commission)?$value->commission:'';
                    $user_address[$value->order_id]['name'] = $restaurant_detail->name;
                    $user_address[$value->order_id]['image'] = ($restaurant_detail->image)?image_url.$restaurant_detail->image:'';
                    $user_address[$value->order_id]['address'] = $address['address'].' '.$address['landmark'].' '.$address['zipcode'].' '.$address['city'];
                    $user_address[$value->order_id]['currency_symbol'] = $restaurant_detail->currency_symbol;
                    $user_address[$value->order_id]['currency_code'] = $restaurant_detail->currency_code;
                }
            }
            foreach ($user_address as $key => $val) {
               $final[] = $val; 
            }
        }
        $data['previous'] = $final;
        return $data;
    }
    //get user of order
    public function getUserofOrder($order_id){
        $this->db->select('users.device_id,users.language_slug');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_master.entity_id',$order_id);
        return $this->db->get('order_master')->first_row();
    }
      public function getSingleRow($tablename,$wherefieldname,$wherefieldvalue)
    {
        $this->db->where($wherefieldname,$wherefieldvalue);
        return $this->db->get($tablename)->first_row();
    }
    public function addData($tablename,$data)
    {   
        $this->db->insert($tablename,$data);            
        return $this->db->insert_id();
    }
    
    // updating status and send request to driver
    public function UpdatedStatus($tblname, $order_id, $order_status)
    {
        if ($order_status == 'preorder') {
            $this->db->set('order_status', 'placed')->where('entity_id', $order_id)->update('order_master');
        }
        $this->db->set('status', 1)->where('entity_id', $order_id)->update('order_master');
        $this->db->set('accept_order_time', date("Y-m-d H:i:s"))->where('entity_id', $order_id)->update('order_master');
        //send notification to user
        $this->db->select('users.entity_id,users.device_id,order_delivery,users.language_slug');
        $this->db->join('users', 'order_master.user_id = users.entity_id', 'left');
        $this->db->where('order_master.entity_id', $order_id);
        $device = $this->db->get('order_master')->first_row();
        // print_r($device);
        if ($device->device_id) {
            //get langauge
            //$languages = $this->db->select('*')->get_where('languages', array('language_slug' => $device->language_slug))->first_row();
            //$this->lang->load('messages_lang', $languages->language_directory);
            #prep the bundle
            $fields = array();
            $message = "Your order is accepted";
            $fields['to'] = $device->device_id; // only one user to send push notification
            $fields['notification'] = array('body'  => $message, 'sound' => 'default');
            $fields['data'] = array('screenType' => 'order');

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


    public function getData($order_id)
    {
        $this->db->select('restaurant_id,user_id');
        $this->db->where('entity_id',$order_id);
        return $this->db->get('order_master')->first_row();
    }


    	//get user data
	public function getUserDate($entity_id)
	{
		$this->db->select('device_id,language_slug');
		$this->db->where('entity_id', $entity_id);
		return $this->db->get('users')->first_row();
	}
}
?>
