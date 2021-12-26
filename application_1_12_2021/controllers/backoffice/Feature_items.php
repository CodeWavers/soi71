<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Feature_items extends CI_Controller
{
    public $controller_name = 'feature_items';
    public $prefix = '_fi';
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL . '/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL . '/feature_items_model');
    }
    // view feature item
    public function view()
    {
        $data['sort'] = $this->feature_items_model->getMenuItem();
        $data['meta_title'] = $this->lang->line('title_admin_feature_items') . ' | ' . $this->lang->line('site_title');
        $this->load->view(ADMIN_URL . '/featured_items', $data);
    }

    public function orderUpdate(){ 
        // Get id of the menus 
        $ids = $this->input->post('ids'); 
       
        if(!empty($ids)){ 
            // Generate ids array 
            
            $idArray = explode(",", $ids); 
             
            $count = 1; 
            foreach ($idArray as $id){ 
                // Update image order by id 
                $data = array('sort_value' => $count); 
                $update = $this->feature_items_model->updateMenu($data, $id); 
                $count ++;     
            } 
             
        } 
         
        return true; 
    } 

    public function add()
    {
        $data['meta_title'] = $this->lang->line('title_admin_feature_items_add') . ' | ' . $this->lang->line('site_title');
        if ($this->input->post('submit_page') == "Submit") {
            // $this->form_validation->set_rules('showAllRestaurant', 'Restaurant Name', 'trim|required');
            $this->form_validation->set_rules('menu_item_id', 'Menu Item', 'trim|required');
            // $this->form_validation->set_rules('sort_value', 'Sort Value', 'trim|required|is_unique[feature_items.sort_value]');


            //check form validation using codeigniter
            if ($this->form_validation->run()) {
                $add_data = array(
                    'restaurant_id' => $this->input->post("resId"),
                    'menu_item_id' => $this->input->post('menu_item_id'),
                    'description' => $this->input->post('description'),
                    // 'sort_value' => $this->input->post('sort_value'),

                );

                if (!empty($_FILES['cover_image']['name'])) {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/feature_image';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;
                    // create directory if not exists
                    if (!@is_dir('uploads/feature_image')) {
                        @mkdir('./uploads/feature_image', 0777, TRUE);
                    }
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('cover_image')) {
                        $img = $this->upload->data();
                        $add_data['cover_image'] = "feature_image/" . $img['file_name'];
                    }
                }
                if (empty($data['Error'])) {

                    $this->feature_items_model->addData('feature_items', $add_data);
                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url() . ADMIN_URL . '/' . $this->controller_name . '/view');
                }
            }
        }
        //$data['restaurant'] = $this->feature_items_model->getListData('restaurant',array('status'=>1));
        $data['allRestaurant'] = $this->feature_items_model->allRestaurant();
        $this->load->view(ADMIN_URL . '/featured_items_add', $data);
        $data['selectedRestaurant'] = $this->input->post("restaurant_id");
        $data['allItems'] = $this->feature_items_model->showItems($data);
    }

    //    // edit coupon
    public function edit()
    {
        $data['meta_title'] = $this->lang->line('title_admin_feature_items_edit') . ' | ' . $this->lang->line('site_title');
        // check if form is submitted 
        if ($this->input->post('submit_page') == "Submit") {
            $this->form_validation->set_rules('menu_item_id', 'Menu Item', 'trim|required');
            // $this->form_validation->set_rules('sort_value', 'Sort Value', 'trim|callback_sort_value_check');
            //check form validation using codeigniter
            if ($this->form_validation->run()) {
                $edit_data = array(
                    'restaurant_id'=>$this->input->post("resId"),
                    'menu_item_id' => $this->input->post('menu_item_id'),
                    'description' => $this->input->post('description'),
                    // 'sort_value' => $this->input->post('sort_value'),
                );
                if (!empty($_FILES['cover_image']['name'])) {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/feature_image';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;
                    // create directory if not exists
                    if (!@is_dir('uploads/feature_image')) {
                        @mkdir('./uploads/feature_image', 0777, TRUE);
                    }
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('cover_image')) {
                        $img = $this->upload->data();
                        $edit_data['cover_image'] = "feature_image/" . $img['file_name'];
                        // code for delete existing image
                        if ($this->input->post('uploadedFeatureImage')) {
                            @unlink(FCPATH . 'uploads/' . $this->input->post('uploadedFeatureImage'));
                        }
                    } else {
                        $data['Error'] = $this->upload->display_errors();
                        $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                if (empty($data['Error'])) {
                    $this->feature_items_model->updateData($edit_data, 'feature_items', 'feature_id', $this->input->post('featureId'));

                    $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                    redirect(base_url() . ADMIN_URL . '/' . $this->controller_name . '/view');
                }
            }
        }
        $entity_id = ($this->uri->segment('4')) ? $this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment(4))) : $this->input->post('id');
        $data['edit_records'] = $this->feature_items_model->getEditDetail($entity_id);
        $data['allRestaurant'] = $this->feature_items_model->allRestaurant();
        // $data['restaurant'] = $this->coupon_model->getListData('restaurant',array('status'=>1));
        // $data['restaurant_map'] = $this->coupon_model->getListData('coupon_restaurant_map',array('coupon_id'=>$entity_id));
        // $data['item_map'] = $this->coupon_model->getListData('coupon_item_map',array('coupon_id'=>$entity_id));
        $this->load->view(ADMIN_URL . '/featured_items_add', $data);
    }

    public function sort_value_check($value)
    {
        $get_sort_value = $this->feature_items_model->getSortValue($this->input->post('featureId'));

        if ($get_sort_value == $value) {
            return true;
        } else {

            $duplicateValue =  $this->feature_items_model->getDuplicateValue($this->input->post('featureId'), $value);
            if ($duplicateValue) {
                $this->form_validation->set_message('sort_value_check', 'The sort value is already taken');
                return FALSE;
            } else {
                return true;
            }
        }
    }
    //ajax view
    public function ajaxview()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '') ? intval($this->input->post('iDisplayLength')) : '';
        $displayStart = ($this->input->post('iDisplayStart') != '') ? intval($this->input->post('iDisplayStart')) : '';
        $sEcho = ($this->input->post('sEcho')) ? intval($this->input->post('sEcho')) : '';
        $sortCol = ($this->input->post('iSortCol_0')) ? intval($this->input->post('iSortCol_0')) : '';
        $sortOrder = ($this->input->post('sSortDir_0')) ? $this->input->post('sSortDir_0') : 'ASC';

        // $sortfields = array(1=>'feature_items.sort_value');
        $sortFieldName = 'feature_items.sort_value';
        // if(array_key_exists($sortCol, $sortfields))
        // {
        //     $sortFieldName = $sortfields[$sortCol];
        // }
        //Get Recored from model
        $grid_data = $this->feature_items_model->getGridList($sortFieldName, $sortOrder, $displayStart, $displayLength);
        $totalRecords = $grid_data['total'];
        $records = array();
        $records["aaData"] = array();
        $nCount = ($displayStart != '') ? $displayStart + 1 : 1;
        foreach ($grid_data['data'] as $key => $val) {

            $records["aaData"][] = array(
                $nCount,
                $val->name,
                $val->menu_name,
                ($val->status) ? $this->lang->line('active') : $this->lang->line('inactive'),
                '<a class="btn btn-sm danger-btn margin-bottom" href="' . base_url() . ADMIN_URL . '/' . $this->controller_name . '/edit/' . str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->id)) . '"><i class="fa fa-edit"></i> ' . $this->lang->line('edit') . '</a> 
                    <button onclick="deleteDetail(' . $val->id . ')"  title="' . $this->lang->line('click_delete') . '" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> ' . $this->lang->line('delete') . '</button> 
                    <button onclick="disable_record(' . $val->id . ',' . $val->status . ')"  title="' . $this->lang->line('click_for') . ($val->status ? '' . $this->lang->line('inactive') . '' : '' . $this->lang->line('active') . '') . ' " class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-' . ($val->status ? 'times' : 'check') . '"></i> ' . ($val->status ? '' . $this->lang->line('inactive') . '' : '' . $this->lang->line('active') . '') . '</button>'
            );
            $nCount++;
        }
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    // method to change feature Item status
    public function ajaxdisable()
    {
        $entity_id = ($this->input->post('feature_id') != '') ? $this->input->post('feature_id') : '';
        if ($entity_id != '') {
            $this->feature_items_model->UpdatedStatus('feature_items', $entity_id, $this->input->post('status'));
        }
    }

    // method for deleting a feature Item
    public function ajaxDelete()
    {
        $entity_id = ($this->input->post('feature_id') != '') ? $this->input->post('feature_id') : '';
        $this->feature_items_model->deleteFeatureItem('feature_items', $entity_id);
    }

    public function getItem()
    {
        $entity_id = ($this->input->post('item_id') != '') ? $this->input->post('item_id') : '';
        if ($entity_id) {
            $result =  $this->feature_items_model->getItem($entity_id);
            $data = [];
            $data['items'] = $result;
            $this->load->view(ADMIN_URL . '/featured_items_add', $data);
            //$response['items'] = $result;
            //echo json_encode($response);
        }
    }



    // public function checkExist(){
    //     $coupon = ($this->input->post('coupon') != '')?$this->input->post('coupon'):'';
    //     if($this->input->post('amount')){
    //         if($coupon != ''){
    //             $check = $this->coupon_model->checkExist($coupon,$this->input->post('entity_id'));
    //             if($check > 0){
    //                 $this->form_validation->set_message('checkExist', $this->lang->line('coupon_exist'));
    //                 return false;
    //             }
    //         } 
    //     }else{
    //         if($coupon != ''){
    //             $check = $this->coupon_model->checkExist($coupon,$this->input->post('entity_id'));
    //             echo $check;
    //         } 
    //     }
    // }

    // public function getItem(){
    //     $entity_id = $this->input->post('entity_id');
    //     $coupon_type = $this->input->post('coupon_type');
    //     $html = '';
    //     if(!empty($entity_id)){
    //         $result =  $this->coupon_model->getItem($entity_id[0],$coupon_type);
    //         if(!empty($result)){
    //             foreach ($result as $key => $value) {
    //                 $html .= '<optgroup label="'.$value[0]->restaurant_name.'">';
    //                 foreach ($value as $k => $val) {
    //                     $html .= '<option value='.$val->entity_id.'>'.$val->name.'</option>';
    //                 }
    //                 $html .= '</optgroup>';
    //             }
    //         } 
    //     }
    //     echo $html;
    // }


}
