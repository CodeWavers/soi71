<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Outlet extends CI_Controller { 
    public $controller_name = 'outlet';
    public $prefix = 'out';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect('home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/outlet_model');
    }
    //view data
    public function view() {
        $data['meta_title'] =' Outlet | '.$this->lang->line('site_title');
        $data['Languages'] = $this->common_model->getLanguages();     
        $this->load->view(ADMIN_URL.'/outlet',$data);
    }
    //add data
    public function add() {
        $data['meta_title'] =' Outlet| '.$this->lang->line('site_title');
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Outlet Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Outlet Address', 'trim|required');
            if ($this->form_validation->run())
            {
                
                $add_data = array(                   
                    'name'=>$this->input->post('name'),
                    'address' => $this->input->post('address'),
                    'status'=>1,
                ); 
                
                if(empty($data['Error'])){
                    $this->outlet_model->addData('outlet',$add_data); 
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');  
                }         
            }
        }
        $this->load->view(ADMIN_URL.'/outlet_add',$data);
    }
    //edit data
    public function edit() {
        $data['meta_title'] = ' Outlet| '.$this->lang->line('site_title');
        //check add form is submit
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Outlet Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Outlet Address', 'trim|required');
            if ($this->form_validation->run())
            {
                $updateData = array(                   
                    'name'=>$this->input->post('name'),
                    'address' => $this->input->post('address'),    
                ); 
                
            
                if(empty($data['Error'])){
                    $this->outlet_model->updateData($updateData,'outlet','entity_id',$this->input->post('entity_id'));
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');          
                }
                  
            }
        }        
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->outlet_model->getEditDetail($entity_id);
        $this->load->view(ADMIN_URL.'/outlet_add',$data);
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
        $grid_data = $this->outlet_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $Languages = $this->common_model->getLanguages();        
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $cnt = 0;
        foreach ($grid_data['data'] as $key => $value) {
            
            $records["aaData"][] = array(
                $key+1,//'<input type="checkbox" name="ids[]" value="' . $value->entity_id . '">',
                $value->name,
                ($value->status)?$this->lang->line('active'):$this->lang->line('inactive'),
                '<a class="btn btn-sm danger-btn margin-bottom" title="'.$this->lang->line('edit').'" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($value->entity_id)).'"><i class="fa fa-edit"></i> '.$this->lang->line('edit').'</a> <button onclick="disableDetail('.$value->entity_id.','.$value->status.')"  title="'.$this->lang->line('click_for').' '.($value->status?''.$this->lang->line('inactive').'':''.$this->lang->line('active').'').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($value->status?'times':'check').'"></i> '.($value->status?''.$this->lang->line('inactive').'':''.$this->lang->line('active').'').'</button>'
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
        $this->category_model->ajaxDelete('category',$this->input->post('content_id'),$entity_id);
    }
    public function ajaxDeleteAll(){
        $content_id = ($this->input->post('content_id') != '')?$this->input->post('content_id'):'';
        $this->category_model->ajaxDeleteAll('category',$content_id);
    }
    // method to change restaurant status
    public function ajaxDisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->outlet_model->UpdatedStatus($this->input->post('tblname'),$entity_id,$this->input->post('status'));
        }
    }
    /*
     * Update status for All
     */
    public function ajaxDisableAll() {
        $content_id = ($this->input->post('content_id') != '')?$this->input->post('content_id'):'';
        if($content_id != ''){
            $this->category_model->UpdatedStatusAll($this->input->post('tblname'),$content_id,$this->input->post('status'));
        }
    }
}