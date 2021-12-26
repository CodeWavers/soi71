<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class PopupSlider extends CI_Controller { 
    public $controller_name = 'PopupSlider';
    public $prefix = '_slider';
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect('home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL.'/Popup_slider_model');
    }
    //view
    
    public function view() {
        $data['meta_title'] = $this->lang->line('title_slider_image').' | '.$this->lang->line('site_title');
        $this->load->view(ADMIN_URL.'/popup_slider',$data);
    }
    // add slider images
    public function add(){
      $data['meta_title'] = $this->lang->line('title_slider_image_add').' | '.$this->lang->line('site_title');
      if($this->input->post('submit_page') == "Submit")
        { 
          $add_data = array(  
                   
            'start_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('start_date'))),
            'end_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))),
        ); 
            if (!empty($_FILES['Slider_image']['name']))
            {
                $this->load->library('upload');
                $config['upload_path'] = './uploads/popup-slider-images';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                $config['max_size'] = '5120'; //in KB    
                $config['encrypt_name'] = TRUE;               
                // create directory if not exists
                if (!@is_dir('uploads/popup-slider-images')) {
                  @mkdir('./uploads/popup-slider-images', 0777, TRUE);
                }
                $this->upload->initialize($config);                  
                if ($this->upload->do_upload('Slider_image'))
                {
                 
                  $img = $this->upload->data();
                  $add_data['image'] = "popup-slider-images/".$img['file_name'];    
                  $this->Popup_slider_model->addData('popup_slider_image',$add_data);   
                  $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                }
                else
                {
                  $data['Error'] = $this->upload->display_errors();
                  $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                }
            }
            redirect(base_url().ADMIN_URL.'/PopupSlider/view');                 
      }
      $this->load->view(ADMIN_URL.'/popup_slider_add',$data);
    }

    //ajax view
    public function edit() {
       $data['meta_title'] = $this->lang->line('title_slider_image_edit').' | '.$this->lang->line('site_title');
        //check add form is submit
        if($this->input->post('submit_page') == "Submit")
        {
            
                $updateData = array(                   
                    'start_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('start_date'))),
                    'end_date' =>date('Y-m-d H:i:s',strtotime($this->input->post('end_date'))),
                ); 
                
                if (!empty($_FILES['Slider_image']['name']))
                {
                    $this->load->library('upload');
                      $config['upload_path'] = './uploads/popup-slider-images';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';  
                $config['max_size'] = '5120'; //in KB    
                $config['encrypt_name'] = TRUE;              
                    // create directory if not exists
                    if (!@is_dir('uploads/popup-slider-images')) {
                      @mkdir('./uploads/popup-slider-images', 0777, TRUE);
                    }
                    $this->upload->initialize($config);                  
                    if ($this->upload->do_upload('Slider_image'))
                    {
                      $img = $this->upload->data();
                      $updateData['image'] = "popup-slider-images/".$img['file_name'];   
                      if($this->input->post('uploadedSliderImage')){
                        @unlink(FCPATH.'uploads/'.$this->input->post('uploadedSliderImage'));
                      }  
                    }
                    else
                    {
                      $data['Error'] = $this->upload->display_errors();
                      $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                if(empty($data['Error'])){
                    $this->Popup_slider_model->updateData($updateData,'popup_slider_image','entity_id',$this->input->post('entity_id'));
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_update'));
                    redirect(base_url().ADMIN_URL.'/PopupSlider/view');          
                }
                  
            
        }        
        $entity_id = ($this->uri->segment('4'))?$this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))):$this->input->post('entity_id');
      $data['edit_records'] = $this->Popup_slider_model->getEditDetail($entity_id);
      $this->load->view(ADMIN_URL.'/popup_slider_add',$data);
    }
    public function ajaxview() {
      $displayLength = ($this->input->post('iDisplayLength') != '')?intval($this->input->post('iDisplayLength')):'';
      $displayStart = ($this->input->post('iDisplayStart') != '')?intval($this->input->post('iDisplayStart')):'';
      $sEcho = ($this->input->post('sEcho'))?intval($this->input->post('sEcho')):'';
      $sortCol = ($this->input->post('iSortCol_0'))?intval($this->input->post('iSortCol_0')):'';
      $sortOrder = ($this->input->post('sSortDir_0'))?$this->input->post('sSortDir_0'):'ASC';
      
      $sortfields = array();
      $sortFieldName = '';
      if(array_key_exists($sortCol, $sortfields))
      {
          $sortFieldName = $sortfields[$sortCol];
      }
      //Get Recored from model
      $grid_data = $this->Popup_slider_model->getGridList($sortFieldName,$sortOrder,$displayStart,$displayLength);
      $totalRecords = $grid_data['total'];        
      $records = array();
      $records["aaData"] = array(); 
      $nCount = ($displayStart != '')?$displayStart+1:1;
      foreach ($grid_data['data'] as $key => $val) {
          $doc = "'".$val->image."'";
          $records["aaData"][] = array(
              $nCount,
               '<img id="oldpic" class="sliderimg" width="70" height="50" src="'.base_url().'uploads/'.$val->image.'">',
             ($val->status)?$this->lang->line('active'):$this->lang->line('inactive'),
               '<a class="btn btn-sm danger-btn margin-bottom" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/edit/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->entity_id)).'"><i class="fa fa-edit"></i> '.$this->lang->line('edit').'</a> 
                   <button onclick="deleteDetail('.$val->entity_id.')"  title="'.$this->lang->line('click_delete').'" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> '.$this->lang->line('delete').'</button> 
                   <button onclick="disable_record('.$val->entity_id.','.$val->status.')"  title="'.$this->lang->line('click_for').($val->status?''.$this->lang->line('inactive').'':''.$this->lang->line('active').'').' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-'.($val->status?'times':'check').'"></i> '.($val->status?''.$this->lang->line('inactive').'':''.$this->lang->line('active').'').'</button>'
           );
          $nCount++;
      }        
      $records["sEcho"] = $sEcho;
      $records["iTotalRecords"] = $totalRecords;
      $records["iTotalDisplayRecords"] = $totalRecords;
      echo json_encode($records);
    }
    // method to change status
    public function ajaxdisable() {
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        if($entity_id != ''){
            $this->Popup_slider_model->UpdatedStatus('popup_slider_image',$entity_id,$this->input->post('status'));
        }
    }
    // method for deleting
    public function ajaxDelete(){
        $entity_id = ($this->input->post('entity_id') != '')?$this->input->post('entity_id'):'';
        $Image = ($this->input->post('image') != '')?$this->input->post('image'):'';
        $this->Popup_slider_model->ajaxDelete('popup_slider_image',$entity_id);
        @unlink(FCPATH.'uploads/'.$Image);
    }

}
?>