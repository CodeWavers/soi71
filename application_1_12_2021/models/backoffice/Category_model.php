<?php
class Category_model extends CI_Model {
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
        $this->db->group_by('content_id');
        if($this->session->userdata('UserType') == 'Admin'){
            $this->db->where('category.created_by',$this->session->userdata('UserID'));   
        }         
        $result['total'] = $this->db->count_all_results('category');
        
        if($this->input->post('page_title')==""){
            $this->db->select('content_general_id,category.*');   
            $this->db->join('category','category.content_id = content_general.content_general_id','left');
            $this->db->group_by('category.content_id');
            if($this->session->userdata('UserType') == 'Admin'){     
                $this->db->where('category.created_by',$this->session->userdata('UserID'));
            } 
            $this->db->where('content_type','category');
            if($displayLength>1)
                $this->db->limit($displayLength,$displayStart);
            $dataCmsOnly = $this->db->get('content_general')->result();    
            $content_general_id = array();
            foreach ($dataCmsOnly as $key => $value) {
                $content_general_id[] = $value->content_general_id;
            }
            if($content_general_id){
                $this->db->where_in('content_id',$content_general_id);    
            }            
        }else{          
            if($this->input->post('page_title') != ''){
                $this->db->like('name', $this->input->post('page_title'));
            }    
            $this->db->select('content_general_id,category.*');   
            $this->db->join('content_general','content_general.content_general_id = category.content_id','left');
            if($this->session->userdata('UserType') == 'Admin'){     
                $this->db->where('category.created_by',$this->session->userdata('UserID'));
            } 
            $this->db->where('content_type','category');
            $this->db->group_by('category.content_id');
            if($displayLength>1)
                $this->db->limit($displayLength,$displayStart);
            $cmsData = $this->db->get('category')->result();                      
            $ContentID = array();               
            foreach ($cmsData as $key => $value) {
                $OrderByID = $OrderByID.','.$value->entity_id;
                $ContentID[] = $value->content_id;
            }   
            if($OrderByID && $ContentID){            
                $this->db->order_by('FIELD ( entity_id,'.trim($OrderByID,',').') DESC');                
                $this->db->where_in('content_id',$ContentID);
            }else{              
                if($this->input->post('page_title') != ''){
                    $this->db->like('name', trim($this->input->post('page_title')));
                } 
            }
        }  
        if($this->session->userdata('UserType') == 'Admin'){     
            $this->db->where('category.created_by',$this->session->userdata('UserID'));
        }   
        $cmdData = $this->db->get('category')->result_array();         
        $cmsLang = array();        
        if(!empty($cmdData)){
            foreach ($cmdData as $key => $value) {                
                if(!array_key_exists($value['content_id'],$cmsLang))
                {
                    $cmsLang[$value['content_id']] = array(
                        'entity_id'=>$value['entity_id'],
                        'content_id' => $value['content_id'],
                        'name' => $value['name'],          
                        'status' => $value['status'],                       
                    );
                }
                $cmsLang[$value['content_id']]['translations'][$value['language_slug']] = array(
                    'translation_id' => $value['entity_id'],
                    'name' => $value['name'],        
                    'status' => $value['status'],    
                );
            }
        }         
        $result['data'] = $cmsLang;        
        return $result;
    }  
    
    // public function updateMenu($data = array(), $id)
    // {

    //     $this->db->where('entity_id', $id);
    //     $this->db->update('category', $data);
    //     return $this->db->affected_rows();
    // }

    
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
        return $this->db->get_where('category',array('entity_id'=>$entity_id))->first_row();
    }
    public function getEditDetailParcel($entity_id)
    {
        return $this->db->get_where('parcel_type',array('id'=>$entity_id))->first_row();
    }
    // update data common function
    public function updateData($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
    }

    public function getAllRestaurant(){
        $this->db->select("name,entity_id");
        $this->db->from("restaurant");
        return $this->db->get()->result();
    }


    public function updateDataParcel($Data,$tblName,$fieldName,$ID)
    {        
        $this->db->where($fieldName,$ID);
        $this->db->update($tblName,$Data);            
        return $this->db->affected_rows();
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
    public function UpdatedStatusforParcel($tblname,$entity_id,$status){
        if($status==0){
            $userData = array('status' => 1);
        } else {
            $userData = array('status' => 0);
        }        
        $this->db->where('id',$entity_id);
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
    public function ajaxDelete($tblname,$content_id,$entity_id)
    {
        // check  if last record
        if($content_id){
            $vals = $this->db->get_where($tblname,array('content_id'=>$content_id))->num_rows();    
            if($vals==1){
                $this->db->where(array('content_general_id' => $content_id));
                $this->db->delete('content_general');        
            }            
        } 
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

    // delete all records
    public function ajaxDeleteAll($tblname,$content_id)
    {
        $this->db->where(array('content_general_id' => $content_id));
        $this->db->delete('content_general');                   

        $this->db->where('content_id',$content_id);
        $this->db->delete($tblname);  
    }
    public function getGridListforparcel($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
         
        // $cmdData = $this->db->get('parcel_type')->result_array();         
        // $cmsLang = array();        
        // if(!empty($cmdData)){
        //     foreach ($cmdData as $key => $value) {                
        //         if(!array_key_exists($value['id'],$cmsLang))
        //         {
        //             $cmsLang[$value['id']] = array(
        //                 'entity_id'=>$value['id'],
        //                 'content_id' => $value['id'],
        //                 'name' => $value['name'],          
        //                 'status' => $value['status'],                       
        //             );
        //         }
        //         $cmsLang[$value['content_id']]['translations'][$value['language_slug']] = array(
        //             'translation_id' => $value['id'],
        //             'name' => $value['name'],        
        //             'status' => $value['status'],    
        //         );
        //     }
        // }         
        // $result['data'] = $cmsLang;        
        // return $result;

        if($this->input->post('name') != ''){
            $this->db->like('name', $this->input->post('name'));
        }
        $result['total'] = $this->db->count_all_results('parcel_type');
        if($sortFieldName != '')
            $this->db->order_by($sortFieldName, $sortOrder);
        
        if($this->input->post('name') != ''){
            $this->db->like('name', $this->input->post('name'));
        }
        if($displayLength>1)
            $this->db->limit($displayLength,$displayStart);        
        $result['data'] = $this->db->get('parcel_type')->result();        
        return $result;
    } 

    public function deleteRecord($entity_id){          
        $this->db->where('id',$entity_id);
        $this->db->delete('parcel_type');
        return $this->db->affected_rows();
    }
    
    public function ajaxDeleteAllparcel($tblname,$content_id)
    {
        // $this->db->where(array('content_general_id' => $content_id));
        // $this->db->delete('content_general');                   

        $this->db->where('id',$content_id);
        $this->db->delete($tblname);  
        // $this->db->delete($tblname, array('id' => $content_id));
    }

    public function getAllCuisine()
    {
        $this->db->select("name,entity_id");
        $this->db->from("cuisine");
        return $this->db->get()->result();
    }
    

     //insert batch 
    public function insertBatch($tblname,$data,$id){
        if($id){
            $this->db->where('category_id',$id);
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