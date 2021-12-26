<?php
class Feature_items_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    //update menu
    public function updateMenu($data = array(), $id)
    {

        $this->db->where('feature_id', $id);
        $this->db->update('feature_items', $data);
        return $this->db->affected_rows();
    }

    //get menu item
    public function getMenuItem()
    {

        $this->db->select('resItem.name as menu_name,feature_items.sort_value,feature_items.feature_id ');
        $this->db->join('restaurant_menu_item as resItem', 'resItem.entity_id = feature_items.menu_item_id', 'left');
        $this->db->order_by('feature_items.sort_value', 'asc');
        return $this->db->get('feature_items')->result();
    }

    //get list
    public function getListData($tblname, $where)
    {
        $this->db->where($where);
        return $this->db->get($tblname)->result_array();
    }

    public function allRestaurant()
    {
        $this->db->select("name,entity_id");
        $this->db->from("restaurant");
        return $this->db->get();
    }

    public function showItems($value)
    {
        $this->db->select('name, entity_id');
        $this->db->from('restaurant_menu_item');
        $this->db->where('restaurant_id', $value['selectedRestaurant']);
        return $this->db->get();
    }

    public function addData($tblName, $Data)
    {
        $this->db->insert($tblName, $Data);
        return $this->db->insert_id();

        // $this->db->insert($tblName, $Data);
        // $query = $this->db->query('SELECT LAST_INSERT_ID()');
        // $row = $query->row_array();
        // $id = $row['LAST_INSERT_ID()'];
        // $data = ['sort_value' => $id,];
        // $this->db->where('feature_id', $id);   
        // $this->db->update($tblName,$data);          
        // return $this->db->affected_rows();
    }

    //ajax view      
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {

        // if($this->session->userdata('UserType') == 'Admin'){
        //     $this->db->where('created_by',$this->session->userdata('UserID'));
        // } 
        $this->db->select('res.name as name,resItem.name as menu_name, feature_items.status as status,feature_items.feature_id as id,feature_items.sort_value');
        $this->db->join('restaurant as res', 'res.entity_id = feature_items.restaurant_id', 'left');
        $this->db->join('restaurant_menu_item as resItem', 'resItem.entity_id = feature_items.menu_item_id', 'left');
        $this->db->group_by('feature_items.feature_id');
        $result['total'] = $this->db->count_all_results('feature_items');
        if ($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);

        if ($this->input->post('page_title') != '') {
            $this->db->like('res.name', $this->input->post('page_title'));
        }
        if ($this->input->post('menu') != '') {
            $this->db->like('resItem.name', $this->input->post('menu'));
        }
        if ($this->input->post('Status') != '') {
            $this->db->like('feature_items.status', $this->input->post('Status'));
        }
        if ($displayLength > 1)
            $this->db->limit($displayLength, $displayStart);
        // if ($this->session->userdata('UserType') == 'Admin') {
        //     $this->db->where('created_by', $this->session->userdata('UserID'));
        // }
        $this->db->select('res.name as name,resItem.name as menu_name, feature_items.status as status,feature_items.feature_id as id,feature_items.sort_value');
        $this->db->join('restaurant as res', 'res.entity_id = feature_items.restaurant_id', 'left');
        $this->db->join('restaurant_menu_item as resItem', 'resItem.entity_id = feature_items.menu_item_id', 'left');
        $this->db->group_by('feature_items.feature_id');
        $result['data'] = $this->db->get('feature_items')->result();
        return $result;
    }
    // //add to db

    //get single data
    public function getEditDetail($entity_id)
    {
        $this->db->select('*');
        return $this->db->get_where('feature_items', array('feature_id' => $entity_id))->first_row();
    }
    // update data common function
    public function updateData($Data, $tblName, $fieldName, $ID)
    {
        $this->db->where($fieldName, $ID);
        $this->db->update($tblName, $Data);
        return $this->db->affected_rows();
    }

    //check duplicate sort value

    public function getDuplicateValue($id, $sortValue)
    {
        $this->db->select('sort_value');
        $this->db->from('feature_items');
        $array = ['feature_id !=' => $id, 'sort_value' => $sortValue];
        $this->db->where($array);
        return $this->db->get()->result();
    }

    public function getItem($entity_id)
    {
        $this->db->select('entity_id,name');
        $this->db->where('restaurant_id', $entity_id);
        $this->db->where('status', 1);
        return $this->db->get('restaurant_menu_item')->result();
    }

    //Check sort value
    public function getSortValue($value)
    {
        $this->db->select('sort_value');
        $this->db->from('feature_items');
        $this->db->where('feature_id', $value);
        return $this->db->get()->result();
    }
    // updating the changed status
    public function UpdatedStatus($tblname, $entity_id, $status)
    {
        if ($status == 0) {
            $userData = array('status' => 1);
        } else {
            $userData = array('status' => 0);
        }
        $this->db->where('feature_id', $entity_id);
        $this->db->update($tblname, $userData);
        return $this->db->affected_rows();
    }
    // delete user
    public function deleteFeatureItem($tblname, $entity_id)
    {
        $this->db->delete($tblname, array('feature_id' => $entity_id));
    }
    // //get list
    // public function getListData($tblname,$where){
    //     $this->db->where($where);
    //     return $this->db->get($tblname)->result_array();
    // }
    // public function checkExist($coupon,$entity_id){
    //     $this->db->where('name',$coupon);
    //     $this->db->where('entity_id !=',$entity_id);
    //     return $this->db->get('coupon')->num_rows();
    // }
    // //insert batch 
    // public function insertBatch($tblname,$data,$id){
    //     if($id){
    //         $this->db->where('coupon_id',$id);
    //         $this->db->delete($tblname);
    //     }
    //     $this->db->insert_batch($tblname,$data);           
    //     return $this->db->insert_id();
    // }
    // //get items
    // public function getItem($entity_id,$coupon_type){
    //     $this->db->select('restaurant_menu_item.entity_id,restaurant_menu_item.name,restaurant_menu_item.price,restaurant.name as restaurant_name,restaurant_menu_item.restaurant_id');
    //     $this->db->join('restaurant','restaurant_menu_item.restaurant_id = restaurant.entity_id','left');
    //     $this->db->where_in('restaurant_menu_item.restaurant_id',$entity_id);
    //     $this->db->where('restaurant_menu_item.status',1);
    //     if($coupon_type == 'discount_on_combo'){
    //         $this->db->where('restaurant_menu_item.is_deal',1);
    //     }
    //     $result =  $this->db->get('restaurant_menu_item')->result();
    //     $res = array();
    //     if(!empty($result)){
    //         foreach ($result as $key => $value) {
    //             if(!isset($res[$value->restaurant_id])){
    //                 $res[$value->restaurant_id] = array();
    //             }
    //             array_push($res[$value->restaurant_id], $value);
    //         }
    //     }
    //     return $res;
    // }
}
