<?php
class Available_flavours_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //ajax view      
    public function getGridList($sortFieldName = '', $sortOrder = 'ASC', $displayStart = 0, $displayLength = 10)
    {
        if ($this->input->post('page_title') != '') {
            $this->db->like('name', $this->input->post('page_title'));
        }

        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }

        if ($this->session->userdata('UserType') == 'Admin') {
            $this->db->where('created_by', $this->session->userdata('UserID'));
        }
        $result['total'] = $this->db->count_all_results('available_flavours');

        if ($displayLength > 1)
            $this->db->limit($displayLength, $displayStart);

        if ($this->input->post('page_title') != '') {
            $this->db->like('name', $this->input->post('page_title'));
        }

        if($this->input->post('Status') != ''){
            $this->db->like('status', $this->input->post('Status'));
        }
        
        if ($this->session->userdata('UserType') == 'Admin') {
            $this->db->where('created_by', $this->session->userdata('UserID'));
        }
      
       
        $result['data'] = $this->db->get('available_flavours')->result();
        return $result;
    }
    //add to db
    public function addData($tblName, $Data)
    {
        $this->db->insert($tblName, $Data);
        return $this->db->insert_id();
    }
    //get single data
    public function getEditDetail($entity_id)
    {
        return $this->db->get_where('available_flavours', array('entity_id' => $entity_id))->first_row();
    }
    // update data common function
    public function updateData($Data, $tblName, $fieldName, $ID)
    {
        $this->db->where($fieldName, $ID);
        $this->db->update($tblName, $Data);
        return $this->db->affected_rows();
    }
    // delete 
    public function ajaxDelete($tblname, $content_id, $entity_id)
    {
        // check  if last record
        if ($content_id) {
            $vals = $this->db->get_where($tblname, array('content_id' => $content_id))->num_rows();
            if ($vals == 1) {
                $this->db->where(array('content_general_id' => $content_id));
                $this->db->delete('content_general');
            }
        }
        $this->db->where('entity_id', $entity_id);
        $this->db->delete($tblname);

        $this->db->where('category_id', $entity_id);
        $this->db->delete('add_ons_master');
    }
    // delete all records
    public function ajaxDeleteAll($tblname, $content_id)
    {
        $this->db->select('entity_id');
        $this->db->where('content_id', $content_id);
        $result = $this->db->get($tblname)->result_array();
        $result = array_column($result, 'entity_id');

        $this->db->where(array('content_general_id' => $content_id));
        $this->db->delete('content_general');

        $this->db->where('content_id', $content_id);
        $this->db->delete($tblname);

        /*$this->db->where_in('category_id',$result);
        $this->db->delete($tblname);*/
    }
    // updating the changed status
    public function UpdatedStatus($tblname, $entity_id, $status)
    {
        if ($status == 0) {
            $userData = array('status' => 1);
        } else {
            $userData = array('status' => 0);
        }
        $this->db->where('entity_id', $entity_id);
        $this->db->update($tblname, $userData);
        return $this->db->affected_rows();
    }
    // updating the changed status
    public function UpdatedStatusAll($tblname, $ContentID, $Status)
    {
        if ($Status == 0) {
            $Data = array('status' => 1);
        } else {
            $Data = array('status' => 0);
        }

        $this->db->where('content_id', $ContentID);
        $this->db->update($tblname, $Data);
        return $this->db->affected_rows();
    }
}
