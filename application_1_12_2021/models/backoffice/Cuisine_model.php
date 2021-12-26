<?php
class Cuisine_model extends CI_Model {
    function __construct()
    {
        parent::__construct();		
    } 
    //ajax view      
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if($this->input->post('page_title') != ''){
            $this->db->like('name', $this->input->post('page_title'));
        }

        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('cuisine.created_by',$this->session->userdata('UserID'));   
        }         
        $result['total'] = $this->db->count_all_results('cuisine');
        
       
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('page_title') != ''){
            $this->db->like('cuisine.name', $this->input->post('page_title'));
        }
        
        if($this->input->post('Status') != ''){
            $this->db->like('cuisine.status', $this->input->post('Status'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);     
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('cuisine.created_by',$this->session->userdata('UserID'));  
        }  
        $this->db->select('cuisine.name,cuisine.status,cuisine.entity_id');
        $this->db->join('cuisine_multicategory_map','cuisine.entity_id = cuisine_multicategory_map.cuisine_id','left');
        $this->db->group_by('cuisine.entity_id');
        $result['data'] = $this->db->get('cuisine')->result();       
        return $result;
           
        
    }  
    
   
    public function updateMenu($data = array(), $category_id, $res_id)
    {
      
            $this->db->where('category_id', $category_id);
            $this->db->where('restaurant_id', $res_id);
            $this->db->update('restaurant_category_sort', $data);
            return $this->db->affected_rows();
        
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
        return $this->db->get_where('cuisine',array('entity_id'=>$entity_id))->first_row();
    }
    
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }

    public function getAllCategory(){
        $this->db->select("name,entity_id");
        $this->db->from("category");
        return $this->db->get()->result();
    }


    // updating the changed
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
    
    // updating the changed status
    public function UpdatedStatusAll($tblname,$ContentID,$Status){
        if($Status==0){
            $Data = array('status' => 1);
        } else {
            $Data = array('status' => 0);
        }

        $this->db->where('content_id',$ContentID);
        $this->db->update($tblname,$Data);
        return $this->db->affected_rows();
    }
    // delete 
    public function ajaxDelete($tblname,$entity_id)
    {     
        $this->db->where('entity_id',$entity_id);
        $this->db->delete($tblname);     
    }
     
    public function isRestaurantCategory_IN_RESCATSORT($cat_id, $res_id)
    {
        $this->db->where('category_id', $cat_id);
        $this->db->where('restaurant_id', $res_id);
        return $this->db->get('restaurant_category_sort')->result_array();
    }

    public function insertIntoResCat_Sort($data = array())
    {      
        $this->db->insert('restaurant_category_sort',$data);
    }
    //
    public function getCategory($id){
    
        $this->db->distinct();
        $this->db->select('cat.entity_id as id, cat.name as name, sort.sort_value');
        $this->db->join('restaurant_menu_item as res_menu', 'res_menu.category_id = cat.entity_id');
        $this->db->join('restaurant_category_sort as sort', 'sort.restaurant_id = res_menu.restaurant_id and sort.category_id = res_menu.category_id', 'left');
        $this->db->where('res_menu.restaurant_id', $id);
        $this->db->order_by('sort.sort_value', 'asc');
        return $this->db->get('category as cat')->result();
    }

 
    //insert batch 
    public function insertBatch($tblname,$data,$id){
        if($id){
            $this->db->where('cuisine_id',$id);
            $this->db->delete($tblname);
        }
        $this->db->insert_batch($tblname,$data);           
        return $this->db->insert_id();
    }   

    //get list
    public function getListData($tblname,$where){
        $this->db->where($where);
        return $this->db->get($tblname)->result_array();
    }

}
?>