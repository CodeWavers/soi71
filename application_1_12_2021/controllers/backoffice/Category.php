<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Category extends CI_Controller { 
    public $controller_name = 'category';
    public $prefix = 'cg';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect('home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/category_model');
    }
    //view data
    public function view() {
        $data['allRestaurant'] = $this->category_model->getAllRestaurant();
        $data['meta_title'] = $this->lang->line('title_category').' | '.$this->lang->line('site_title');
        $data['Languages'] = $this->common_model->getLanguages();     
        $this->load->view(ADMIN_URL.'/category',$data);
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
        $data['meta_title'] = $this->lang->line('title_category_add').' | '.$this->lang->line('site_title');
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Category Name', 'trim|required');
            if ($this->form_validation->run())
            {
                if(!$this->input->post('content_id')){
                    //ADD DATA IN CONTENT SECTION
                    $add_content = array(
                      'content_type'=>$this->uri->segment('2'),
                      'created_by'=>$this->session->userdata("UserID"),  
                      'created_date'=>date('Y-m-d H:i:s')                      
                    );
                    $ContentID = $this->category_model->addData('content_general',$add_content);
                }else{                    
                    $ContentID = $this->input->post('content_id');
                }
                $add_data = array(                   
                    'name'=>$this->input->post('name'),
                    'content_id'=>$ContentID,
                    'language_slug'=>$this->uri->segment('4'),
                    'status'=>1,
                    // 'deal_category'=>($this->input->post('deal_category'))?$this->input->post('deal_category'):0,
                    'created_by' => $this->session->userdata('UserID')
                ); 
                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/category';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/category')) {
                      @mkdir('./uploads/category', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $add_data['image'] = "category/".$img['file_name'];    
                    }
                    else
                    {
                      $data['Error'] = $this->upload->display_errors();
                      $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                if(empty($data['Error'])){
                    $entity_id = $this->category_model->addData('category',$add_data); 

                    if(!empty($this->input->post('cuisine_id'))){
                        $cuisine_data = array();
                        foreach ($this->input->post('cuisine_id') as $key => $value) {
                            $cuisine_data[] = array(
                                'cuisine_id'=>$value,
                                'category_id'=>$entity_id
                            );
                        }
                        $this->category_model->insertBatch('cuisine_multicategory_map',$cuisine_data,$id = '');
                    }
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');  
                }         
            }
        }

        $data['allCuisine'] = $this->category_model->getAllCuisine();
        $this->load->view(ADMIN_URL.'/category_add',$data);
    }
    //edit data
    public function edit() {
        $data['meta_title'] = $this->lang->line('title_category_edit').' | '.$this->lang->line('site_title');
        //check add form is submit
        if($this->input->post('submit_page') == "Submit")
        {
            $this->form_validation->set_rules('name', 'Category Name', 'trim|required');
            if ($this->form_validation->run())
            {
                $updateData = array(                   
                    'name'=>$this->input->post('name'),
                    // 'deal_category'=>($this->input->post('deal_category'))?$this->input->post('deal_category'):0,
                    'updated_date'=>date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata('UserID')
                ); 
                
                if (!empty($_FILES['Image']['name']))
                {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/category';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;               
                    // create directory if not exists
                    if (!@is_dir('uploads/category')) {
                      @mkdir('./uploads/category', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Image'))
                    {
                      $img = $this->upload->data();
                      $updateData['image'] = "category/".$img['file_name'];   
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
                    $this->category_model->updateData($updateData,'category','entity_id',$this->input->post('entity_id'));

                    if(!empty($this->input->post('cuisine_id'))){
                        $cuisine_data = array();
                        foreach ($this->input->post('cuisine_id') as $key => $value) {
                            $cuisine_data[] = array(
                                'cuisine_id'=>$value,
                                'category_id'=>$this->input->post('entity_id')
                            );
                        }
                        $this->category_model->insertBatch('cuisine_multicategory_map',$cuisine_data,$this->input->post('entity_id'));
                    }

                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));
                    redirect(base_url().ADMIN_URL.'/'.$this->controller_name.'/view');          
                }
                  
            }
        }        
        $entity_id = ($this->uri->segment('5'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(5))):$this->input->post('entity_id');
        $data['edit_records'] = $this->category_model->getEditDetail($entity_id);
        $data['allCuisine'] = $this->category_model->getAllCuisine();
        $data['cuisine_map'] = $this->category_model->getListData('cuisine_multicategory_map',array('category_id'=>$entity_id));
        $this->load->view(ADMIN_URL.'/category_add',$data);
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
        $grid_data = $this->category_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
        $Languages = $this->common_model->getLanguages();        
        $totalRecords = $grid_data['total'];        
        $records = array();
        $records["aaData"] = array(); 
        $nCount = ($displayStart != '')?$displayStart+1:1;
        $cnt = 0;
        foreach ($grid_data['data'] as $key => $value) {
            $edit_active_access = '<button onclick="deleteAll('.$value['content_id'].')"  title="'.$this->lang->line('click_delete').'" class="delete btn btn-sm danger-btn margin-bottom red"><i class="fa fa-times"></i> '.$this->lang->line('delete').'</button>';
            $edit_active_access .='<button onclick="disableAll('.$value['content_id'].','.$value['status'].')"  title="'.$this->lang->line('click_for').' ' .($value['status']?$this->lang->line('inactive'):$this->lang->line('active')).'" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($value['status']?'times':'check').'"></i> '.($value['status']?$this->lang->line('inactive'):$this->lang->line('active')).'</button>';
            $records["aaData"][] = array(
                $nCount,
                $value['name'],
                $edit_active_access
            ); 
            $cusLan = array();
            foreach ($Languages as $lang) { 
                if(array_key_exists($lang->language_slug,$value['translations'])){
                    $cusLan[] = '<a href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.$lang->language_slug.'/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($value['translations'][$lang->language_slug]['translation_id'])).'" title="'.$this->lang->line('click_edit').'"><i class="fa fa-edit"></i> </a>
                    <a style="cursor:pointer;" onclick="disable_record('.$value['translations'][$lang->language_slug]['translation_id'].','.$value['translations'][$lang->language_slug]['status'].')"  title="'.$this->lang->line('click_for').' ' .($value['translations'][$lang->language_slug]['status']?''.$this->lang->line('inactive').'':''.$this->lang->line('active').'').'"><i class="fa fa-toggle-'.($value['translations'][$lang->language_slug]['status']?'on':'off').'"></i> </a>
                    <a style="cursor:pointer;" onclick="deleteDetail('.$value['translations'][$lang->language_slug]['translation_id'].','.$value['content_id'].')"  title="'.$this->lang->line('click_delete').'"><i class="fa fa-times"></i> </a>
                    ( '.$value['translations'][$lang->language_slug]['name'].' )';
                }else{
                    $cusLan[] = '<a href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/add/'.$lang->language_slug.'/'.$value['content_id'].'" title="'.$this->lang->line('click_add').'"><i class="fa fa-plus"></i></a>';
                }                    
            }
            // added to specific position
            array_splice( $records["aaData"][$cnt], 2, 0, $cusLan);
            $cnt++;
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
            $this->category_model->UpdatedStatus($this->input->post('tblname'),$entity_id,$this->input->post('status'));
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