<?php
class Coupon_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		        
    }	
      //ajax view      
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 25)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('coupon.name', $this->input->post('page_title'));
        }
        if($this->input->post('amount') != ''){
            $this->db->like('amount', $this->input->post('amount'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID'));
        } 
        $this->db->select('coupon.*,restaurant.currency_id');
        $this->db->join('coupon_restaurant_map','coupon.entity_id = coupon_restaurant_map.coupon_id','left');
        $this->db->join('restaurant','coupon_restaurant_map.restaurant_id = restaurant.entity_id','left');
        $this->db->group_by('coupon.entity_id');
        $result['total'] = $this->db->count_all_results('coupon');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('coupon.name', $this->input->post('page_title'));
        }
        if($this->input->post('amount') != ''){
            $this->db->like('amount', $this->input->post('amount'));
        }
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);     
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('created_by',$this->session->userdata('UserID'));  
        }  
        $this->db->select('coupon.*,restaurant.currency_id');
        $this->db->join('coupon_restaurant_map','coupon.entity_id = coupon_restaurant_map.coupon_id','left');
        $this->db->join('restaurant','coupon_restaurant_map.restaurant_id = restaurant.entity_id','left');
        $this->db->group_by('coupon.entity_id');
        $result['data'] = $this->db->get('coupon')->result();       
        return $result;
    }  
    //add to db
    public function addData($tblName,$Data)
    {   
        $this->db->insert($tblName,$Data);            
        return $this->db->insert_id();
    } 
    //get single data
    public function getEditDetail($entity_id)
    {
        $this->db->select('c.*');
        return $this->db->get_where('coupon as c',array('c.entity_id'=>$entity_id))->first_row();
    }
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }
   


    // updating the changed status
    public function UpdatedStatus($tblname,$entity_id,$status){
        if($status==0){
            $userData = array('status' => 1);
        } else {
            $userData = array('status' => 0);
        }        
        $this->db->where('entity_id',$entity_id);
        $this->db->update($tblname,$userData);
        return $this->db->affected_rows();
    }
    // delete user
    public function deleteUser($tblname,$entity_id)
    {
        $this->db->delete($tblname,array('entity_id'=>$entity_id));  
    }
    //get list
    public function getListData($tblname,$where){
        $this->db->where($where);
        return $this->db->get($tblname)->result_array();
    }
    public function checkExist($coupon,$entity_id){
        $this->db->where('name',$coupon);
        $this->db->where('entity_id !=',$entity_id);
        return $this->db->get('coupon')->num_rows();
    }
    //insert batch 
    public function insertBatch($tblname,$data,$id){
        if($id){
            $this->db->where('coupon_id',$id);
            $this->db->delete($tblname);
        }
        $this->db->insert_batch($tblname,$data);           
        return $this->db->insert_id();
    }
    //get items
    public function getItem($entity_id,$coupon_type,$type,$coupon_id){

        if (($coupon_type == 'discount_on_items' || $coupon_type == 'gradual') && $type == 'add') {
            $discounted_items = $this->discountedItem($entity_id,$coupon_type);
            $discounted_ids = array_column($discounted_items, 'item_id');
         }

         if ($coupon_type == 'gradual' && $type == 'edit') {
            $items = $this->getActiveGradualItems($entity_id,$coupon_id);
            $other_gradual_items = array_column($items, 'item_id');
         }
 
         
         $this->db->select('restaurant_menu_item.entity_id,restaurant_menu_item.name,restaurant_menu_item.price,restaurant.name as restaurant_name,restaurant_menu_item.restaurant_id');
         $this->db->join('restaurant','restaurant_menu_item.restaurant_id = restaurant.entity_id','left');
         $this->db->where_in('restaurant_menu_item.restaurant_id',$entity_id);
         if ($type=='add' && $discounted_ids) {
             $this->db->where_not_in('restaurant_menu_item.entity_id', $discounted_ids);
         }
         if ($coupon_type == 'gradual' && $type == 'edit' && $other_gradual_items) {
            $this->db->where_not_in('restaurant_menu_item.entity_id', $other_gradual_items);
         }
              
         $this->db->where('restaurant_menu_item.status',1);
         if($coupon_type == 'discount_on_combo'){
             $this->db->where('restaurant_menu_item.is_deal',1);
         }
         $result =  $this->db->get('restaurant_menu_item')->result();
         $res = array();
         if(!empty($result)){
             foreach ($result as $key => $value) {
                 if(!isset($res[$value->restaurant_id])){
                     $res[$value->restaurant_id] = array();
                 }
                 array_push($res[$value->restaurant_id], $value);
             }
         }
         return $res;
    }

    public function discountedItem($entity_id,$coupon_type)
    {
        $a = new DateTime();
        $currentTime = $a->format('Y-m-d H:i:s');

       $this->db->select('items.item_id');
       $this->db->join('coupon as c','c.entity_id = items.coupon_id','left');
       $this->db->join('coupon_restaurant_map as res','c.entity_id = res.coupon_id','left');
       $this->db->where('c.coupon_type',$coupon_type);
       $this->db->where_in('res.restaurant_id',$entity_id);
       $this->db->where('c.end_date >', $currentTime);
       $this->db->where('c.start_date <', $currentTime);
       $this->db->where('c.status', 1);

       return $this->db->get('coupon_item_map as items')->result();
    }

    public function getActiveGradualItems($entity_id,$coupon_id)
    {
        $a = new DateTime();
        $currentTime = $a->format('Y-m-d H:i:s');

        $where = array('end_date >' => $currentTime, 'start_date <' => $currentTime, 'coupon_type' => "gradual", 'status' => 1);
        
        $this->db->select('item.item_id');
        $this->db->join('coupon_restaurant_map as map', 'map.coupon_id = coupon.entity_id', 'left');
        $this->db->join('coupon_item_map as item', 'item.coupon_id = coupon.entity_id', 'left');
        $this->db->where_in('map.restaurant_id',$entity_id);
        $this->db->where('item.coupon_id !=', $coupon_id);
        $this->db->where($where);
        return $this->db->get('coupon')->result();
    }

    public function getNonGradualRestaurant()
    {
        $a = new DateTime();
        $currentTime = $a->format('Y-m-d H:i:s');

        $where = array('end_date >' => $currentTime, 'start_date <' => $currentTime, 'coupon_type' => "gradual", 'status' => 1, 'gradual_all_items' => 1);

        $this->db->select('map.restaurant_id');
        $this->db->join('coupon_restaurant_map as map', 'map.coupon_id = coupon.entity_id', 'left');
        $this->db->join('coupon_item_map as item', 'item.coupon_id = coupon.entity_id', 'left');
        $this->db->where($where);
        $result = $this->db->get('coupon')->result();

        $restaurant_id = array_column($result, 'restaurant_id');

        $this->db->select('entity_id,name');
        if ($restaurant_id) {
            $this->db->where_not_in('entity_id', $restaurant_id);
        }      
        return $this->db->get('restaurant')->result_array();
    }


    public function restaurantMap($entity_id)
    {
        $result = $this->db->get_where('coupon',array('entity_id'=>$entity_id))->first_row();
        $res_map = array();

        if ($result->gradual_all_items == 1 && $result->coupon_type == 'gradual') {
            $a = new DateTime();
            $currentTime = $a->format('Y-m-d H:i:s');
    
            $where = array('end_date >' => $currentTime, 'start_date <' => $currentTime, 'coupon_type' => "gradual", 'status' => 1, 'gradual_all_items' => 1);
    
            $this->db->select('map.restaurant_id');
            $this->db->join('coupon_restaurant_map as map', 'map.coupon_id = coupon.entity_id', 'left');
            $this->db->where($where);
            $this->db->where('map.coupon_id !=',$entity_id);
            $res_map = $this->db->get('coupon')->result();
            $res = array_column($res_map, 'restaurant_id');
        }

        $this->db->select('entity_id,name');
        if ($res_map) {
            $this->db->where_not_in('entity_id', $res);
        }
        return $this->db->get('restaurant')->result_array();
     
    }
}
?>