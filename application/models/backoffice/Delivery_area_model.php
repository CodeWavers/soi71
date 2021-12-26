<?php
class Delivery_area_model extends CI_Model {
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
        if($this->input->post('delivery_charge') != ''){
            $this->db->like('delivery_charge', $this->input->post('delivery_charge'));
        } 
        if($this->input->post('rider_commision') != ''){
            $this->db->like('rider_commision', $this->input->post('rider_commision'));
        } 
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }   
        
        $result['total'] = $this->db->count_all_results('delivery_area');
        
        if($this->input->post('page_title') != ''){
            $this->db->like('name', $this->input->post('page_title'));
        } 
        if($this->input->post('delivery_charge') != ''){
            $this->db->like('delivery_charge', $this->input->post('delivery_charge'));
        } 
        if($this->input->post('rider_commision') != ''){
            $this->db->like('rider_commision', $this->input->post('rider_commision'));
        } 
        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }        

        if ($sortFieldName != '')
			$this->db->order_by($sortFieldName, $sortOrder);
        
        if ($displayLength > 1)
			$this->db->limit($displayLength, $displayStart);
        
        $result['data'] = $this->db->get('delivery_area')->result();
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
        return $this->db->get_where('delivery_area',array('entity_id'=>$entity_id))->first_row();
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
    

}
?>