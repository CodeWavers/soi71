<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Available_flavours extends CI_Controller { 
    public $controller_name = 'available_flavours';
    public $prefix = 'avf';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect('home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/available_flavours_model');
    }
    //view data
    public function view() {
        $data['meta_title'] = ' Available Flavours | '.$this->lang->line('site_title');
        $data['Languages'] = $this->common_model->getLanguages();     
        $this->load->view(ADMIN_URL.'/available_flavours',$data);
    }
    //add data
    public function add() {
        $data['meta_title'] = ' Add New Flavour | '.$this->lang->line('site_title');
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Flavour Name', 'trim|required');
            if ($this->form_validation->run())
            {
                
                $add_data = array(                   
                    'name'=>$this->input->post('name'),
                    'status'=>1,
                    'created_by' => $this->session->userdata('UserID')
                ); 
                $this->available_flavours_model->addData('available_flavours',$add_data); 
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');          
            }
        }
        $this->load->view(ADMIN_URL.'/available_flavours_add',$data);
    }
    //edit data
    public function edit() {
        $data['meta_title'] = ' Available Flavours | '.$this->lang->line('site_title');
        //check add form is submit
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Flavour Name', 'trim|required');
            if ($this->form_validation->run())
            {
                $updateData = array(                   
                    'name'=>$this->input->post('name'),
                    'updated_date'=>date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('UserID')
                ); 
                $this->available_flavours_model->updateData($updateData,'available_flavours','entity_id',$this->input->post('entity_id'));
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));
                redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');          
                  
            }
        }        
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->available_flavours_model->getEditDetail($entity_id);
        $this->load->view(ADMIN_URL.'/available_flavours_add',$data);
    }
    //ajax view
    public function ajaxview() {
        $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
        $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
        $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
        $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
        $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
        
        $sortfields = array(1=>'name','2'=>'status');
        $sortFieldName = '';
        if(array_key_exists($sortCol, $sortfields))
        {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->available_flavours_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $Languages = $this->common_model->getLanguages();        
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        foreach ($grid_data['data'] as $key => $val) {
            $records["aaData"][] = array(
                $nCount,
                $val->name,
                ($val->status)?$this->lang->line('active'):$this->lang->line('inactive'),
                '<a class="btn btn-sm danger-btn margin-bottom" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->entity_id)).'"><i class="fa fa-edit"></i> '.$this->lang->line('edit').'</a> <button onclick="deleteDetail('.$val->entity_id.')"  title="'.$this->lang->line('click_delete').'" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> '.$this->lang->line('delete').'</button>
                <button onclick="disable_record(' . $val->entity_id . ',' . $val->status . ')"  title="' . $this->lang->line('click_for') . ($val->status ? '' . $this->lang->line('inactive') . '' : '' . $this->lang->line('active') . '') . ' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-' . ($val->status ? 'times' : 'check') . '"></i> ' . ($val->status ? '' . $this->lang->line('inactive') . '' : '' . $this->lang->line('active') . '') . '</button>'
            );
            $nCount++;
        }        
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }
    // method for deleting a category
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $this->available_flavours_model->ajaxDelete('available_flavours',$this->input->post('content_id'),$entity_id);
    }
    public function ajaxDeleteAll(){
        $content_id = ($this->input->post('content_id') != '')?$this->input->post('content_id'):'';
        $this->available_flavours_model->ajaxDeleteAll('available_flavours',$content_id);
    }
    // method to change restaurant status
    public function ajaxDisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->available_flavours_model->UpdatedStatus($this->input->post('tblname'),$entity_id,$this->input->post('status'));
        }
    }
    /*
     * Update status for All
     */
    public function ajaxDisableAll() {
        $content_id = ($this->input->post('content_id') != '')?$this->input->post('content_id'):'';
        if($content_id != ''){
            $this->available_flavours_model->UpdatedStatusAll($this->input->post('tblname'),$content_id,$this->input->post('status'));
        }
    }
}