<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Cuisine extends CI_Controller { 
    public $controller_name = 'cuisine';
    public $prefix = 'cu';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect('home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/cuisine_model');
    }
    //view data
    public function view() {
        
        $data['meta_title'] = ' Cuisine| '.$this->lang->line('site_title');
        $data['Languages'] = $this->common_model->getLanguages();     
        $this->load->view(ADMIN_URL.'/cuisine',$data);
    }
    
    //get menu catagory for sorting
    public function getMenuCategory(){
        $entity_id = ($this->input->post('item_id') != '') ? $this->input->post('item_id') : '';
        if ($entity_id) {
            $data['items'] =  $this->category_model->getCategory($entity_id);
            // print_r($result);
            header('Content-Type: application/json');
             
            echo json_encode($data['items']);
           
            $this->load->view(ADMIN_URL . '/category', $data['items'] ,true);
           
        }
    }
    //sorting categorty update
    public function orderUpdate(){
        $ids = $this->input->post('ids'); 
        $res_id = $this->input->post('res_id');
       
        if(!empty($ids)){ 
            // Generate ids array 
            
            $idArray = explode(",", $ids); 
             
            $count = 1; 
            foreach ($idArray as $id){ 
                $data = array('sort_value' => $count); 
                $check = $this->category_model->isRestaurantCategory_IN_RESCATSORT($id, $res_id);
                if($check){
                    $update = $this->category_model->updateMenu($data, $id, $res_id);
                }
                else{
                    
                    $data = array('restaurant_id'=>$res_id, 'category_id' => $id, 'sort_value'=>$count);
                    $this->category_model->insertIntoResCat_Sort($data);
                }
                
                 
                $count ++;     
            } 
             
        } 
         
        return true; 
    }

    //add data
    public function add() {
        $data['meta_title'] = ' Add Cuisine| '.$this->lang->line('site_title');
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Cuisine Name', 'trim|required');
            $this->form_validation->set_rules('defination', 'Defination', 'trim|required');
            if ($this->form_validation->run())
            {
                
                $add_data = array(                   
                    'name'=>$this->input->post('name'),
                    'defination' =>$this->input->post('defination'),
                    'status'=>1,
                    'created_by' => $this->session->userdata('UserID'),
                    'created_date' => date('Y-m-d H:i:s')
                ); 
                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/cuisine';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/cuisine')) {
                      @mkdir('./uploads/cuisine', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $add_data['cover_image'] = "cuisine/".$img['file_name'];    
                    }
                    else
                    {
                      $data['Error'] = $this->upload->display_errors();
                      $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }

                
                if(empty($data['Error'])){
                    $entity_id = $this->cuisine_model->addData('cuisine',$add_data); 

                    if(!empty($this->input->post('category_id'))){
                        $cat_data = array();
                        foreach ($this->input->post('category_id') as $key => $value) {
                            $cat_data[] = array(
                                'category_id'=>$value,
                                'cuisine_id'=>$entity_id
                            );
                        }
                        $this->cuisine_model->insertBatch('cuisine_multicategory_map',$cat_data,$id = '');
                    }
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');  
                }         

                
            }
        }
        $data['allCategory'] = $this->cuisine_model->getAllCategory();
        $this->load->view(ADMIN_URL.'/cuisine_add',$data);
    }
    //edit data
    public function edit() {
        $data['meta_title'] = ' Edit Cuisine | '.$this->lang->line('site_title');
        //check add form is submit
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Category Name', 'trim|required');
            $this->form_validation->set_rules('defination', 'Defination', 'trim|required');
            if ($this->form_validation->run())
            {
                $updateData = array(                   
                    'name'=>$this->input->post('name'),
                    'defination' =>$this->input->post('defination'),
                    'updated_date'=>date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('UserID')
                ); 
                
                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/cuisine';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/cuisine')) {
                      @mkdir('./uploads/cuisine', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $updateData['cover_image'] = "cuisine/".$img['file_name'];   
                      if($this->input->post('uploaded_image')){
                        @unlink(FCPATH.'uploads/'.$this->input->post('uploaded_image'));
                      }  
                    }
                    else
                    {
                      $data['Error'] = $this->upload->display_errors();
                      $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                if(empty($data['Error'])){
                    $this->cuisine_model->updateData($updateData,'cuisine','entity_id',$this->input->post('entity_id'));

                    if(!empty($this->input->post('category_id'))){
                        $cat_data = array();
                        foreach ($this->input->post('category_id') as $key => $value) {
                            $cat_data[] = array(
                                'category_id'=>$value,
                                'cuisine_id'=>$this->input->post('entity_id')
                            );
                        }
                        $this->cuisine_model->insertBatch('cuisine_multicategory_map',$cat_data,$this->input->post('entity_id'));
                    }
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');          
                }
                  
            }
        }        
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
        $data['edit_records'] = $this->cuisine_model->getEditDetail($entity_id);
        $data['allCategory'] = $this->cuisine_model->getAllCategory();
        $data['category_map'] = $this->cuisine_model->getListData('cuisine_multicategory_map',array('cuisine_id'=>$entity_id));
        $this->load->view(ADMIN_URL.'/cuisine_add',$data);
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
        $grid_data = $this->cuisine_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
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
        $this->cuisine_model->ajaxDelete('cuisine',$entity_id);
    }
    public function ajaxDeleteAll(){
        $content_id = ($this->input->post('content_id') != '')?$this->input->post('content_id'):'';
        $this->category_model->ajaxDeleteAll('category',$content_id);
    }
    // method to change restaurant status
    public function ajaxDisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->cuisine_model->UpdatedStatus($this->input->post('tblname'),$entity_id,$this->input->post('status'));
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
?>