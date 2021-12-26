<?php
class report_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function restaurant_details($email)
    {
        $this->db->select('entity_id,name');
        $this->db->where('email', $email);
        return $this->db->get('restaurant')->result();
    }

    function fetch($Fdate, $Tdate, $restaurent)
    {

        $this->db->select('o.*,order.user_detail,order.	item_detail');
        $this->db->join('order_detail as order','order.order_id = o.entity_id','left');
        $this->db->where('o.order_date >=', $Fdate);
        $this->db->where('o.order_date <=', $Tdate);
        $this->db->where('o.restaurant_id', $restaurent);
        return $this->db->get('order_master as o');
        // $query="select * from  order_master  WHERE order_date BETWEEN $Fdate AND $Tdate AND  restaurant_id=$Gjc"
        // return $this->db->query('select * from  order_master where BETWEEN '.$Fdate.' AND '.$Tdate.'restaurant_id='.$Gjc.'');

    }

    function getDrivers($mail)
    {
        $this->db->select('entity_id');
        $this->db->where('email', $mail);
        $restaurentId = $this->db->get('restaurant')->row()->entity_id;
        $this->db->select('users.entity_id, users.first_name');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('order_driver_map','order_driver_map.order_id = order_master.entity_id','left');
        $this->db->join('users','order_driver_map.driver_id = users.entity_id','left');
        $this->db->where('order_master.restaurant_id', $restaurentId);
        $this->db->group_by('users.entity_id');
        return $this->db->get('order_master');
    }
    // function exportAllDeleveredData($Fdate, $Tdate, $restaurent)
    // {
    //     $this->db->select('entity_id,subtotal,delivery_charge,coupon_discount,vat,sd,total_rate');
    //     $this->db->where('order_date >=', $Fdate);
    //     $this->db->where('order_date <=', $Tdate);
    //     $this->db->where('order_status="delivered"');
    //     $this->db->where('restaurant_id', $restaurent);
    //     return $this->db->get('order_master');
    // }

    function exportAllData($Fdate, $Tdate, $restaurent)
    {
       $this->db->select('o.entity_id,order.user_detail,order.item_detail,o.subtotal,o.order_date,o.delivery_charge,o.coupon_discount,o.vat,o.sd,o.total_rate,');
        $this->db->join('order_detail as order','order.order_id = o.entity_id','left');
        $this->db->where('o.order_date >=', $Fdate);
        $this->db->where('o.order_date <=', $Tdate);
        $this->db->where('o.restaurant_id', $restaurent);
        return $this->db->get('order_master as o');
    }

    function riders_report($Fdate, $Tdate, $dropdown)
    {
        $this->db->select('order_master.entity_id,order_master.total_rate,order_driver_map.commission,(order_master.total_rate-order_driver_map.commission) as total');
        $this->db->from('order_master');
        $this->db->join('order_driver_map', 'order_master.entity_id = order_driver_map.order_id');
        $this->db->where('driver_id', $dropdown);
        $this->db->where('is_accept=1');
        $this->db->where('order_date >=', $Fdate);
        $this->db->where('order_date <=', $Tdate);
        return $this->db->get();
    }

    function fetchRiders($Fdate, $Tdate, $dropdown)
    {

        $this->db->select('*');
        $this->db->from('order_master');
        $this->db->join('order_driver_map', 'order_master.entity_id = order_driver_map.order_id');
        $this->db->where('driver_id', $dropdown);
        $this->db->where('is_accept=1');
        $this->db->where('order_date >=', $Fdate);
        $this->db->where('order_date <=', $Tdate);
        return $this->db->get();
    }


    function getAllGroups()
    {

        return $this->db->query("select entity_id,first_name,mobile_number from users where user_type='Driver'");
    }

    function getAllRestaurant()
    {

        return $this->db->query("select entity_id,name from restaurant");
    }

    function getAllCustomer()
    {

        return $this->db->query("select entity_id,first_name,mobile_number from users where user_type='User'");
    }

    function allOrder_pdf($entity_id,$order_status,$to_date,$from_date)
    {  
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id, restaurant.name,order_driver_map.order_id,order_driver_map.driver_id');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->from('order_master');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
       // $this->db->or_where('order_status.order_status','delivered');
        if (!empty($entity_id)) {
        $this->db->where('restaurant_id', $entity_id);
        }
        if (!empty($from_date)) {
        $this->db->where('DATE(order_date) >=', $from_date);
        }
        if (!empty($to_date)) {
        $this->db->where('DATE(order_date) <=', $to_date);
        }
        
        if ($order_status) {
        $this->db->where('order_status', $order_status);
        }
        
        $this->db->order_by('order_master.entity_id','DESC');

        return $this->db->get();
    }

    function allRider_pdf($entity_id,$to_date,$from_date)
    {  
       $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as e_id,order_master.*,order_status.order_id as osid,order_status.*,restaurant.*');
        $this->db->from('order_master');
        $this->db->join('order_driver_map','order_driver_map.order_id = order_master.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('users','order_driver_map.driver_id= users.entity_id','left');
        $this->db->where('order_driver_map.is_accept=1');
        $this->db->where('order_status.order_status','delivered');

        if (!empty($entity_id)) {
        $this->db->where('users.entity_id', $entity_id);
        }
        if (!empty($from_date)) {
        $this->db->where('DATE(order_date) >=', $from_date);
        }
        if (!empty($to_date)) {
        $this->db->where('DATE(order_date) <=', $to_date);
        }
        
        if ($order_status) {
        $this->db->where('order_status', $order_status);
        }
        
        $this->db->order_by('order_status.time','DESC');

        return $this->db->get();
    }

        function alldeliveredOrder_pdf($entity_id,$order_status,$to_date,$from_date)
    {  
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id,restaurant.name,order_driver_map.order_id,order_driver_map.driver_id,order_status.*');
        $this->db->from('order_master');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_status.order_status','delivered');
       
        if (!empty($entity_id)) {
        $this->db->where('restaurant_id', $entity_id);
        }
        if (!empty($from_date)) {
        $this->db->where('DATE(order_date) >=', $from_date);
        }
        if (!empty($to_date)) {
        $this->db->where('DATE(order_date) <=', $to_date);
        }
        
        if ($order_status) {
        $this->db->where('order_status', $order_status);
        }
        
        $this->db->order_by('order_master.entity_id','DESC');

        return $this->db->get();
    }

        function cusOrder_pdf($entity_id,$to_date,$from_date)
    {  
        $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as e_id,order_master.*');
        $this->db->from('order_master');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
  

        if (!empty($entity_id)) {
        $this->db->where('users.entity_id', $entity_id);
        }
        if (!empty($from_date)) {
        $this->db->where('DATE(order_date) >=', $from_date);
        }
        if (!empty($to_date)) {
        $this->db->where('DATE(order_date) <=', $to_date);
        }
        
        if ($order_status) {
        $this->db->where('order_status', $order_status);
        }
        
        $this->db->order_by('accept_order_time','DESC');

        return $this->db->get();
    }

    function resOrder_pdf($entity_id,$to_date,$from_date)
    {  
        $this->db->select('restaurant.entity_id,restaurant.name,order_master.entity_id as e_id,order_master.order_date,order_master.subtotal,order_master.delivery_charge,order_master.order_status,order_master.sd,order_master.vat');
        $this->db->from('order_master');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
  

        if (!empty($entity_id)) {
        $this->db->where('restaurant.entity_id', $entity_id);
        }
        if (!empty($from_date)) {
        $this->db->where('DATE(order_date) >=', $from_date);
        }
        if (!empty($to_date)) {
        $this->db->where('DATE(order_date) <=', $to_date);
        }
        
        
        $this->db->order_by('accept_order_time','DESC');

        return $this->db->get();
    }



    function fetchdelivered($Fdate, $Tdate, $restaurent)
    {
        $this->db->select('o.entity_id,order.user_detail,order.item_detail,o.subtotal,o.order_date,o.delivery_charge,o.coupon_discount,o.vat,o.sd,o.total_rate,');
        $this->db->join('order_detail as order','order.order_id = o.entity_id','left');
        $this->db->where('o.order_date >=', $Fdate);
        $this->db->where('o.order_date <=', $Tdate);
        $this->db->where('o.order_status="delivered"');
        $this->db->where('o.restaurant_id', $restaurent);
        return $this->db->get('order_master as o');

        // $query="select * from  order_master  WHERE order_date BETWEEN $Fdate AND $Tdate AND  restaurant_id=$Gjc"


        // return $this->db->query('select * from  order_master where BETWEEN '.$Fdate.' AND '.$Tdate.'restaurant_id='.$Gjc.'');


    }

    function fetchExportData($Fdate, $Tdate, $restaurent)
    {
        $this->db->select('o.entity_id,order.user_detail,order.item_detail,o.subtotal,o.order_date,o.delivery_charge,o.coupon_discount,o.vat,o.sd,o.total_rate,');
        $this->db->join('order_detail as order','order.order_id = o.entity_id','left');
        $this->db->where('o.order_date >=', $Fdate);
        $this->db->where('o.order_date <=', $Tdate);
        $this->db->where('o.order_status="cancel"');
        $this->db->where('o.restaurant_id', $restaurent);
        return $this->db->get('order_master as o');
    }

    public function getAllOrderList($postData=null)
    {

         $response = array();
         if($postData['searchFromDate']!=''&&$postData['searchToDate']!='' ){

        ## Read value
         $draw = $postData['draw'];
         $start = $postData['start'];
         $rowperpage = $postData['length']; // Rows display per page
         $columnIndex = $postData['order'][0]['column']; // Column index
         $columnName = $postData['columns'][$columnIndex]['data']; // Column name
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue = $postData['search']['value']; // Search value

        // Custom search filter 
         $searchTypes=$postData['searchTypes'];
         $searchRestaurant = $postData['searchRestaurant'];
         $searchFromDate = $postData['searchFromDate'];
         $searchToDate = $postData['searchToDate'];

            ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
         $search_arr[] = " (users.first_name like '%".$searchValue."%' or users.mobile_number like '%".$searchValue."%' or order_master.entity_id like '%".$searchValue."%' or u.first_name like '%".$searchValue."%' or 
         restaurant.name like '%".$searchValue."%' or order_master.delivery_charge like'%".$searchValue."%' ) ";
         }

        if($searchTypes != ''){
        $search_arr[] = "order_master.order_status='".$searchTypes."' ";
        }
        if($searchRestaurant != ''){
        $search_arr[] = "restaurant.entity_id='".$searchRestaurant."' ";
        }
        if($searchFromDate != ''){
        $search_arr[] = "DATE(order_master.order_date) >='".$searchFromDate."'";
        }
        if($searchToDate != ''){
        $search_arr[] = "DATE(order_master.order_date) <='".$searchToDate."'";
        }
  
        if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
        }

        ## Total number of records without filtering
    
        $this->db->select('count(*) as allcount');
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id, restaurant.name,order_driver_map.order_id,order_driver_map.driver_id');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id, restaurant.name,order_driver_map.order_id,order_driver_map.driver_id');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        // $this->db->or_where('order_status.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecordwithFilter = $records[0]->allcount;
        
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id, restaurant.name,order_driver_map.order_id,order_driver_map.driver_id');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
       // $this->db->or_where('order_status.order_status','delivered');
        $this->db->order_by('order_master.entity_id','DESC');

       
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('order_master')->result();

         $data = array();
         
         foreach($records as $key=>$record ){
           $food_bill= $record->subtotal+$record->coupon_discount;
           $resto_pay=$record->subtotal+$record->vat+$record->sd-$record->commission_value;
           if (!empty($record->r_name)) {
               $rider_name=$record->r_name;
           } else{
              $rider_name="Not assigned by system admin";      
           }
    
               
            $data[] = array( 
                'sl'               =>++$key,
                'e_id'             =>$record->e_id,
                'order_date'       =>date("d-m-Y H:i:s",strtotime($record->order_date)),
                'first_name'       =>$record->first_name,   
                'name'             =>$record->name,
                'r_name'           =>$rider_name,
                'food_bill'        =>$food_bill,
                'vat'              =>$record->vat,
                'sd'               =>$record->sd,
                'resto_pay'        =>$resto_pay,
                'delivery_charge'  =>$record->delivery_charge,
                'coupon_discount'  =>$record->coupon_discount,
                'customer_pay'     =>$record->total_rate,
                'order_status'     =>strtoupper($record->order_status),
                
                
            ); 
          
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response;

        }
        else{
            $response = array(
          "draw" => intval($postData['draw']),
          "iTotalRecords" => 0,
          "iTotalDisplayRecords" =>0,
          "aaData" => array()
       );

       return $response;
       }

    }

    public function getAllRiderList($postData=null)
    {

         $response = array();

         ## Read value
         $draw = $postData['draw'];
         $start = $postData['start'];
         $rowperpage = $postData['length']; // Rows display per page
         $columnIndex = $postData['order'][0]['column']; // Column index
         $columnName = $postData['columns'][$columnIndex]['data']; // Column name
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue = $postData['search']['value']; // Search value

         // Custom search filter 
         $searchRider = $postData['searchRider'];
         $searchFromDate = $postData['searchFromDate'];
         $searchToDate = $postData['searchToDate'];
   
         ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
         $search_arr[] = " (users.first_name like '%".$searchValue."%' or order_master.entity_id like '%".$searchValue."%' or
         mobile_number like '%".$searchValue."%' ) ";
         }
        if($searchRider != ''){
        $search_arr[] = "users.entity_id='".$searchRider."' ";
        }
        if($searchFromDate != ''){
        $search_arr[] = "DATE(order_status.time) >='".$searchFromDate."'";
        }
        if($searchToDate != ''){
        $search_arr[] = "DATE(order_status.time) <='".$searchToDate."'";
        }
        if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
        }

         ## Total number of records without filtering
    
        $this->db->select('count(*) as allcount');
        $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as eid,order_master.*,order_status.order_id as osid,order_status.*,restaurant.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('order_driver_map','order_driver_map.order_id = order_master.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('users','order_driver_map.driver_id= users.entity_id','left');
      
        $this->db->where('order_driver_map.is_accept=1');
        $this->db->where('order_status.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecords = $records[0]->allcount;

         ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as eid,order_master.*,order_status.order_id as osid,order_status.*,restaurant.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('order_driver_map','order_driver_map.order_id = order_master.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('users','order_driver_map.driver_id= users.entity_id','left');
      
        $this->db->where('order_driver_map.is_accept=1');
        $this->db->where('order_status.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecordwithFilter = $records[0]->allcount;

         ## Fetch records
        $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as e_id,order_master.*,order_status.order_id as osid,order_status.*,restaurant.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('order_driver_map','order_driver_map.order_id = order_master.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('users','order_driver_map.driver_id= users.entity_id','left');
      
        $this->db->where('order_driver_map.is_accept=1');
        $this->db->where('order_status.order_status','delivered');
        $this->db->order_by('order_status.time','DESC');
       
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('order_master')->result();
        $data = array();
         
         foreach($records as $key => $record ){
          $restaurant_pay=$record->subtotal+$record->vat+$record->sd-$record->commission_value;
          $hand_cash=$record->total_rate-$restaurant_pay;
          $rider_payable=$hand_cash-$record->delivery_charge;
               
            $data[] = array( 
                'sl'               =>++$key,
                'first_name'       =>$record->first_name,
                'mobile_number'    =>$record->mobile_number,
                'e_id'             =>$record->e_id,
                'time'             =>$record->time,
                'name'             =>$record->name,
                'customer_pay'     =>$record->total_rate,
                'restaurant_pay'   =>$restaurant_pay,
                'hand_cash'        =>number_format((float)$hand_cash, 2, '.', ''),
                'rider_earning'    =>$record->delivery_charge,
                'rider_payable'    =>number_format((float)$rider_payable, 2, '.', ''),
                
            ); 
          
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response; 

    }

     public function getAllDelieredList($postData=null)
    {

         $response = array();

         if($postData['searchFromDate']!=''&&$postData['searchToDate']!='' ){

        ## Read value
         $draw = $postData['draw'];
         $start = $postData['start'];
         $rowperpage = $postData['length']; // Rows display per page
         $columnIndex = $postData['order'][0]['column']; // Column index
         $columnName = $postData['columns'][$columnIndex]['data']; // Column name
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue = $postData['search']['value']; // Search value

        // Custom search filter 
         $searchTypes=$postData['searchTypes'];
         $searchRestaurant = $postData['searchRestaurant'];
         $searchFromDate = $postData['searchFromDate'];
         $searchToDate = $postData['searchToDate'];

            ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
         $search_arr[] = " (users.first_name like '%".$searchValue."%' or users.mobile_number like '%".$searchValue."%' or order_master.entity_id like '%".$searchValue."%' or u.first_name like '%".$searchValue."%' or 
         restaurant.name like '%".$searchValue."%' or order_master.delivery_charge like'%".$searchValue."%' ) ";
         }

        if($searchRestaurant != ''){
        $search_arr[] = "restaurant.entity_id='".$searchRestaurant."' ";
        }
        if($searchFromDate != ''){
        $search_arr[] = "DATE(order_master.order_date) >='".$searchFromDate."'";
        }
        if($searchToDate != ''){
        $search_arr[] = "DATE(order_master.order_date) <='".$searchToDate."'";
        }
  
        if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
        }

        ## Total number of records without filtering
    
        $this->db->select('count(*) as allcount');
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id,restaurant.name,order_driver_map.order_id,order_driver_map.driver_id,order_status.*');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_status.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id,restaurant.name,order_driver_map.order_id,order_driver_map.driver_id,order_status.*');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_status.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecordwithFilter = $records[0]->allcount;
        
        $this->db->select('u.entity_id as r_id,u.first_name as r_name,users.entity_id, users.first_name,order_master.entity_id as e_id,order_master.*,restaurant.entity_id,restaurant.name,order_driver_map.order_id,order_driver_map.driver_id,order_status.*');
        // $this->db->join('order_status','order_master.entity_id= order_status.order_id');
        $this->db->join('order_driver_map','order_master.entity_id=order_driver_map.order_id','left');
        $this->db->join('users as u','order_driver_map.driver_id=u.entity_id','left');
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->join('order_status','order_master.entity_id= order_status.order_id','left');
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_status.order_status','delivered');
        $this->db->order_by('order_master.entity_id','DESC');

       
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('order_master')->result();

         $data = array();
         
         foreach($records as $key=>$record ){
           $d1=strtotime($record->time);
           $d2=strtotime($record->accept_order_time);
           $duration=round(abs($d1 - $d2) / 60,2). " m";
           $food_bill= $record->subtotal+$record->coupon_discount;
           $resto_pay=$record->subtotal+$record->vat+$record->sd-$record->commission_value;
           if (!empty($record->r_name)) {
               $rider_name=$record->r_name;
           } else{
              $rider_name="Not assigned by system admin";      
           }
    
               
            $data[] = array( 
                'sl'               =>++$key,
                'e_id'             =>$record->e_id,
                'duration'         =>$duration,
                'order_date'       =>date("d-m-Y H:i:s",strtotime($record->time)),
                'first_name'       =>$record->first_name,   
                'name'             =>$record->name,
                'r_name'           =>$rider_name,
                'food_bill'        =>$food_bill,
                'vat'              =>$record->vat,
                'sd'               =>$record->sd,
                'resto_pay'        =>$resto_pay,
                'delivery_charge'  =>$record->delivery_charge,
                'coupon_discount'  =>$record->coupon_discount,
                'customer_pay'     =>$record->total_rate,
                'order_status'     =>strtoupper($record->order_status),
                
                
            ); 
          
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response;

        }
        else{
             $response = array(
           "draw" => intval($postData['draw']),
           "iTotalRecords" => 0,
           "iTotalDisplayRecords" =>0,
           "aaData" => array()
        );

        return $response;
        }

    }

    //Order Report Customer wise

     public function getCusOrderList($postData=null){

         $response = array();

        ## Read value
         $draw = $postData['draw'];
         $start = $postData['start'];
         $rowperpage = $postData['length']; // Rows display per page
         $columnIndex = $postData['order'][0]['column']; // Column index
         $columnName = $postData['columns'][$columnIndex]['data']; // Column name
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue = $postData['search']['value']; // Search value

        // Custom search filter 
         $searchCustomer = $postData['searchCustomer'];
         $searchFromDate = $postData['searchFromDate'];
         $searchToDate = $postData['searchToDate'];

            ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
         $search_arr[] = " (users.first_name like '%".$searchValue."%' or users.mobile_number like '%".$searchValue."%' or order_master.entity_id like '%".$searchValue."%' or order_master.subtotal like '%".$searchValue."%' or 
         order_master.vat like '%".$searchValue."%' or 
         order_master.delivery_charge like'%".$searchValue."%' ) ";
         }
        if($searchCustomer != ''){
        $search_arr[] = "users.entity_id='".$searchCustomer."' ";
        }
        if($searchFromDate != ''){
        $search_arr[] = "DATE(order_master.accept_order_time) >='".$searchFromDate."'";
        }
        if($searchToDate != ''){
        $search_arr[] = "DATE(order_master.accept_order_time) <='".$searchToDate."'";
        }
  
        if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
        }

        ## Total number of records without filtering
    
        $this->db->select('count(*) as allcount');
        $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as e_id,order_master.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as e_id,order_master.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecordwithFilter = $records[0]->allcount;
        
        $this->db->select('users.entity_id, users.first_name,users.mobile_number,order_master.entity_id as e_id,order_master.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('users','order_master.user_id = users.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
        $this->db->order_by('accept_order_time','DESC');
        
       
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('order_master')->result();

         $data = array();
         
         foreach($records as $key=>$record ){
    
               
            $data[] = array( 
                'sl'               =>++$key,
                'first_name'       =>$record->first_name,
                'mobile_number'    =>$record->mobile_number,
                'e_id'         =>$record->e_id,
                'accept_order_time'=>date("m-d-Y",strtotime($record->accept_order_time)),
                'subtotal'       =>$record->subtotal,
                'vat'              =>$record->vat,
                'sd'               =>$record->sd,
                'delivery_charge'  =>$record->delivery_charge,
                'discount'         =>$record->coupon_discount,
                'total_rate'       =>$record->total_rate,
            
                
            ); 
          
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response;

    }

    //Order Report (restaurant wise)

    public function getResOrderList($postData=null){

        $response = array();

        ## Read value
         $draw = $postData['draw'];
         $start = $postData['start'];
         $rowperpage = $postData['length']; // Rows display per page
         $columnIndex = $postData['order'][0]['column']; // Column index
         $columnName = $postData['columns'][$columnIndex]['data']; // Column name
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue = $postData['search']['value']; // Search value

        // Custom search filter 
         $searchRestaurant = $postData['searchRestaurant'];
         $searchFromDate = $postData['searchFromDate'];
         $searchToDate = $postData['searchToDate'];

            ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
         $search_arr[] = " (restaurant.name like '%".$searchValue."%' or order_master.entity_id like '%".$searchValue."%') ";
         }
        if($searchRestaurant != ''){
        $search_arr[] = "restaurant.entity_id='".$searchRestaurant."' ";
        }
        if($searchFromDate != ''){
        $search_arr[] = "DATE(order_master.order_date)>='$searchFromDate'";
        }
        if($searchToDate != ''){
        $search_arr[] = "DATE(order_master.order_date)<='$searchToDate'";
        }
  
        if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
        }

        ## Total number of records without filtering
    
        $this->db->select('count(*) as allcount');
        $this->db->select('restaurant.entity_id, restaurant.name,order_master.entity_id as e_id,order_master.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->select('restaurant.entity_id, restaurant.name,order_master.entity_id as e_id,order_master.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
         if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('order_master')->result();
        $totalRecordwithFilter = $records[0]->allcount;
        
        $this->db->select('restaurant.entity_id,restaurant.name,order_master.entity_id as e_id,order_master.order_date,order_master.subtotal,order_master.delivery_charge,order_master.order_status,order_master.sd,order_master.vat');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->join('restaurant','order_master.restaurant_id = restaurant.entity_id','left');
        $this->db->where('order_master.order_status','delivered');
        $this->db->order_by('accept_order_time','DESC');
       
       
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('order_master')->result();

         $data = array();
         
         foreach($records as $key=>$record ){
            $restaurant_payable=$record->subtotal-$record->delivery_charge; 
               
            $data[] = array( 
                'sl'         =>++$key,
                'name'       =>$record->name,
                'entity_id'   =>$record->e_id,
                'order_date' =>date("m-d-Y",strtotime($record->order_date)),
                'subtotal'   =>$record->subtotal,
                'commission_value' =>$record->delivery_charge,
                'restaurant_payable'=>$restaurant_payable+$record->vat+$record->sd,              
            
                
            ); 
          
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
         );

         return $response;

    }

    //User Acquisition Report

      public function getAcquisitionUserList($postData=null){

        $response = array();

        ## Read value
         $draw = $postData['draw'];
         $start = $postData['start'];
         $rowperpage = $postData['length']; // Rows display per page
         $columnIndex = $postData['order'][0]['column']; // Column index
         $columnName = $postData['columns'][$columnIndex]['data']; // Column name
         $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
         $searchValue = $postData['search']['value']; // Search value

        // Custom search filter 
         //$searchRestaurant = $postData['searchRestaurant'];
         $searchFromDate = $postData['searchFromDate'];
         $searchToDate = $postData['searchToDate'];

            ## Search 
         $search_arr = array();
         $searchQuery = "";
         if($searchValue != ''){
         $search_arr[] = " (first_name like '%".$searchValue."%' or 
         mobile_number like '%".$searchValue."%') ";
         }

        if($searchFromDate != ''){
        $search_arr[] = "DATE(users.created_date)>='$searchFromDate'";
        }
        if($searchToDate !=''){
            $search_arr[]="DATE(users.created_date)<='$searchToDate'";
        }
      
     
        if(count($search_arr) > 0){
        $searchQuery = implode(" and ",$search_arr);
        }

        ## Total number of records without filtering
    
        $this->db->select('count(*) as allcount');
        $this->db->select('users.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->where('user_type','User');
          if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('users')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        $this->db->select('users.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->where('user_type','User');
          if($searchQuery != '')
        $this->db->where($searchQuery);
        $records = $this->db->get('users')->result();
        $totalRecordwithFilter = $records[0]->allcount;
        
        $this->db->select('users.*');
        //$this->db->join('order_master','order_master.restaurant_id =' .$restaurentId);
        $this->db->where('user_type','User');
        $this->db->order_by('created_date','dsc');
       
        if($searchQuery != '')
        $this->db->where($searchQuery);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('users')->result();

         $data = array();
         $sl =1;
         foreach($records as $record ){
            $name = $record->first_name." ".$record->last_name; 
               
            $data[] = array( 
                'sl'=>$sl,
                'first_name'   =>$name,
                'created_date' =>date("d-m-Y",strtotime($record->created_date)),
                'mobile_number'=>$record->mobile_number
                         
            ); 
            $sl++;
         }

         ## Response
         $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => intval($totalRecordwithFilter),
            "iTotalDisplayRecords" => intval($totalRecords),
            "aaData" => $data
         );

         return $response;

    }


    // function fetchcancel($Fdate, $Tdate, $restaurent)
    // {
    //     $this->db->where('order_date >=', $Fdate);
    //     $this->db->where('order_date <=', $Tdate);
    //     $this->db->where('order_status="cancel"');
    //     $this->db->where('restaurant_id', $restaurent);
    //     return $this->db->get('order_master');

    //     // $query="select * from  order_master  WHERE order_date BETWEEN $Fdate AND $Tdate AND  restaurant_id=$Gjc"
    //     // return $this->db->query('select * from  order_master where BETWEEN '.$Fdate.' AND '.$Tdate.'restaurant_id='.$Gjc.'');

    // }
}
?>