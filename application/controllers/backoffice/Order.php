<?php error_reporting(1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . "/third_party/excelclasses/PHPExcel.php";
require_once APPPATH . "/third_party/excelclasses/PHPExcel/IOFactory.php";
class Order extends CI_Controller
{
    public $module_name = 'Order';
    public $controller_name = 'order';
    public $prefix = '_order';
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('is_admin_login')) {
            redirect(ADMIN_URL . '/home');
        }
        $this->load->library('form_validation');
        $this->load->model(ADMIN_URL . '/order_model');
        $this->load->model('v1/api_model');
    }
    // view order
    public function view()
    {
        $data['meta_title'] = $this->lang->line('title_admin_order') . ' | ' . $this->lang->line('site_title');
        $data['restaurant'] = $this->order_model->getRestaurantList();
        $data['drivers'] = $this->order_model->getDrivers();
        $this->load->view(ADMIN_URL . '/order', $data);
    }
    // add order
    public function add()
    {
        $data['meta_title'] = $this->lang->line('title_admin_orderadd') . ' | ' . $this->lang->line('site_title');
        if ($this->input->post('submit_page') == "Submit") {
            // $this->form_validation->set_rules('user_id', 'User', 'trim|required');
            $this->form_validation->set_rules('restaurant_id', 'Restaurant', 'trim|required');
            // $this->form_validation->set_rules('address_id', 'Address', 'trim|required');
            // $this->form_validation->set_rules('order_status', 'Order Status', 'trim|required');
            $this->form_validation->set_rules('order_date', 'Date Of Order', 'trim|required');
            $this->form_validation->set_rules('delivery_date', 'Delivery Date', 'trim|required');
            $this->form_validation->set_rules('total_rate', 'Total', 'trim|required');
            $this->form_validation->set_rules('deliveryCharge', 'Delivery Charge', 'trim|required');

            if ($this->input->post('create_user') == "yes") {
                $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
                $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required');
            }

            if ($this->input->post('address') == "yes") {
                $this->form_validation->set_rules('delivery_area', 'Delivery Area', 'trim');
            }

            //check form validation using codeigniter

            if ($this->input->post('first_name') && $this->input->post('mobile_number')) {
                $userdata = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'mobile_number' => $this->input->post('mobile_number'),
                    'user_type' => 'User',
                    'status' => 1,
                    'active' => 1,
                    'password' => md5(SALT . $this->input->post('password')),
                    'created_by' => $this->session->userdata("UserID"),
                );


                $user_id = $this->order_model->addData('users', $userdata);
            }

            // if ($this->input->post('address') && $this->input->post('landmark')) {
            //     $add_address = array(
            //         'address' => $this->input->post('address'),
            //         'landmark' => $this->input->post('landmark'),
            //         'user_entity_id' => $user_id,
            //         'latitude' => $this->input->post('latitude'),
            //         'longitude' => $this->input->post('longitude'),
            //     );

            //     $address_id = $this->order_model->addData('user_address', $add_address);
            // }

            $userID = $this->input->post('user_id') ? $this->input->post('user_id') : $user_id;

            if ($this->form_validation->run()) {
                $add_data = array(
                    'user_id' => $this->input->post('user_id') ? $this->input->post('user_id') : $user_id,
                    'restaurant_id' => $this->input->post('restaurant_id'),
                    'address_id' => '', //($this->input->post('address_id')) ? $this->input->post('address_id') : $address_id,
                    'order_date' => date('Y-m-d H:i:s', strtotime($this->input->post('order_date'))),
                    'total_rate' => ($this->input->post('total_rate')) ? $this->input->post('total_rate') : '',
                    'created_by' => $this->session->userdata("UserID"),
                    'status' => 1,
                    'picture_attached_by' => $this->input->post('picture_attached_by'),
                    'whatsapp' => $this->input->post('whatsapp'),
                    'details' => $this->input->post('details'),
                    'delivery_charge' => $this->input->post('deliveryCharge'),
                    "delivery_time" => $this->input->post('delivery_date'),
                    'advance' => $this->input->post('advance'),
                    'due' => $this->input->post('due'),
                    'payment_option' => $this->input->post('payment'),
                    'manual_order' => 1,
                    'time_slot' => $this->input->post('time_slot'),
                    'order_status' => 'placed'
                );

                if (!empty($_FILES['Image']['name'])) {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/order';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;
                    // create directory if not exists
                    if (!@is_dir('uploads/order')) {
                        @mkdir('./uploads/order', 0777, TRUE);
                    }
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('Image')) {
                        $img = $this->upload->data();
                        $add_data['image1'] = "order/" . $img['file_name'];
                    } else {
                        $data['Error'] = $this->upload->display_errors();
                        $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }

                if (!empty($_FILES['Image2']['name'])) {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/order';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;
                    // create directory if not exists
                    if (!@is_dir('uploads/order')) {
                        @mkdir('./uploads/order', 0777, TRUE);
                    }
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('Image2')) {
                        $img = $this->upload->data();
                        $add_data['image2'] = "order/" . $img['file_name'];
                    } else {
                        $data['Error'] = $this->upload->display_errors();
                        $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }

                if (!empty($_FILES['Image3']['name'])) {
                    $this->load->library('upload');
                    $config['upload_path'] = './uploads/order';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = '5120'; //in KB    
                    $config['encrypt_name'] = TRUE;
                    // create directory if not exists
                    if (!@is_dir('uploads/order')) {
                        @mkdir('./uploads/order', 0777, TRUE);
                    }
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('Image3')) {
                        $img = $this->upload->data();
                        $add_data['image3'] = "order/" . $img['file_name'];
                    } else {
                        $data['Error'] = $this->upload->display_errors();
                        $this->form_validation->set_message('upload_invalid_filetype', 'Error Message');
                    }
                }
                $order_id = $this->order_model->addData('order_master', $add_data);

                $order_status = array(
                    'order_id' => $order_id,
                    'order_status' => 'accepted_by_restaurant',
                    'time' => date('Y-m-d H:i:s'),
                    'status_created_by' => 'Admin'
                );
                
                $this->order_model->addData('order_status', $order_status);

                $order_admin = array(
                    'order_id' => $order_id,
                    'factory' => $this->input->post('factory'),
                    'order_validate_by' => $this->input->post('order_validate_by'),
                    'order_taken_by' => $this->input->post('order_taken_by'),
                    'manager' => $this->input->post('manager'),
                );
                $this->order_model->addData('order_admin', $order_admin);

                //add items
                $items = $this->input->post('name');
                $add_item = array();
                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        $add_item[] = array(
                            "item_name" => $this->input->post('name')[$key],
                            "qty_no" => $this->input->post('qty_no')[$key],
                            "description" => $this->input->post('description')[$key],
                            "rate" => ($this->input->post('rate')[$key]) ? $this->input->post('rate')[$key] : '',
                            "custom_fee" => $this->input->post('custom_fee')[$key],
                            "add_vat" => $this->input->post('add_vat')[$key],
                            "order_id" => $order_id
                        );
                    }
                }
                //get user detail            

                if ($this->input->post('address') == "yes") {
                    $area_name = $this->order_model->getRecord('delivery_area', 'entity_id', $this->input->post('delivery_area'));
                    $address_detail = array(
                        'area_id' => $this->input->post('delivery_area'),
                        'area' => $area_name->name,
                        'RoadNo' => $this->input->post('road'),
                        'FlatNo' => $this->input->post('flat'),
                        'BlockNo' => $this->input->post('section'),
                        'Houseno' => $this->input->post('house'),
                        'delivery_price' => $this->input->post('delivery_charge'),
                        'rider_commission' => $area_name->rider_commision
                    );

                    $address = array('address_detail' => json_encode($address_detail), 'user_entity_id' => $userID);
                    $address_id = $this->api_model->addRecord('user_address', $address);
                }

                $addressID = ($this->input->post('address_id')) ? $this->input->post('address_id') : $address_id;
                $address = $this->api_model->getAddress('user_address', 'entity_id', $addressID);

                $tokenres = $this->order_model->checkToken($userID);
                $user_detail = array(
                    'first_name' => $tokenres->first_name,
                    'last_name' => ($tokenres->last_name) ? $tokenres->last_name : '',
                    'address' => ($address) ? $address[0]->address_detail : '',
                    // 'landmark' => ($address) ? $address[0]->landmark : '',
                    // 'zipcode' => ($address) ? $address[0]->zipcode : '',
                    // 'city' => ($address) ? $address[0]->city : '',
                    // 'latitude' => ($address) ? $address[0]->latitude : '',
                    // 'longitude' => ($address) ? $address[0]->longitude : '',
                    "delivery_name" => $this->input->post('delivery_name'),
                    "delivery_number" => $this->input->post('delivery_number'),
                );
                //get restaurant detail
                $rest_detail = $this->order_model->getRestaurantDetail($this->input->post('restaurant_id'));
                $order_detail = array(
                    'order_id' => $order_id,
                    'item_detail' => serialize($add_item),
                    'restaurant_detail' => serialize($rest_detail),
                    'user_detail' => serialize($user_detail),
                );
                $this->order_model->addData('order_detail', $order_detail);
                $this->session->set_flashdata('page_MSG', $this->lang->line('success_add'));
                // // send invoice to user
                // $data['order_records'] = $this->order_model->getEditDetail($order_id);
                // $data['menu_item'] = $this->order_model->getInvoiceMenuItem($order_id);
                // $html = $this->load->view('backoffice/manual_order_print', $data, true);
                // if (!@is_dir('uploads/invoice')) {
                //     @mkdir('./uploads/invoice', 0777, TRUE);
                // }
                // $filepath = 'uploads/invoice/' . $order_id . '.pdf';
                // $this->load->library('M_pdf');
                // $mpdf = new mPDF('', 'Letter');
                // $mpdf->SetHTMLHeader('');
                // $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="' . base_url() . '/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
                // $mpdf->AddPage(
                //     '', // L - landscape, P - portrait 
                //     '',
                //     '',
                //     '',
                //     '',
                //     0, // margin_left
                //     0, // margin right
                //     10, // margin top
                //     23, // margin bottom
                //     0, // margin header
                //     0 //margin footer
                // );
                // $mpdf->autoScriptToLang = true;
                // $mpdf->SetAutoFont();
                // $mpdf->WriteHTML($html);
                // $mpdf->output($filepath, 'F');

                // //send invoice as email
                // $user = $this->db->get_where('users', array('entity_id' => $this->input->post('user_id')))->first_row();
                // $FromEmailID = $this->db->get_where('system_option', array('OptionSlug' => 'From_Email_Address'))->first_row();
                // $this->db->select('OptionValue');
                // $FromEmailName = $this->db->get_where('system_option', array('OptionSlug' => 'Email_From_Name'))->first_row();
                // $this->db->select('subject,message');
                // $Emaildata = $this->db->get_where('email_template', array('email_slug' => 'new-order-invoice', 'language_slug' => $this->session->userdata('language_slug'), 'status' => 1))->first_row();
                // $arrayData = array('FirstName' => $user->first_name, 'Order_ID' => $order_id);
                // $EmailBody = generateEmailBody($Emaildata->message, $arrayData);
                // if (!empty($EmailBody)) {
                //     $this->load->library('email');
                //     $config['charset'] = 'iso-8859-1';
                //     $config['wordwrap'] = TRUE;
                //     $config['mailtype'] = 'html';
                //     $this->email->initialize($config);
                //     $this->email->from($FromEmailID->OptionValue, $FromEmailName->OptionValue);
                //     $this->email->to(trim($user->email));
                //     $this->email->subject($Emaildata->subject);
                //     $this->email->message($EmailBody);
                //     $this->email->attach($filepath);
                //     $this->email->send();
                // }


                redirect(base_url() . ADMIN_URL . '/' . $this->controller_name . '/view');
            }
        }
        $data['restaurant'] = $this->order_model->getListData('restaurant');
        $data['user'] = $this->order_model->getListData('users');
        $data['delivery_area'] = $this->order_model->getListData('delivery_area');
        //$data['coupon'] = $this->order_model->getListData('coupon');
        $this->load->view(ADMIN_URL . '/order_add', $data);
    }
    //ajax view
    public function ajaxview()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '') ? intval($this->input->post('iDisplayLength')) : '';
        $displayStart = ($this->input->post('iDisplayStart') != '') ? intval($this->input->post('iDisplayStart')) : '';
        $sEcho = ($this->input->post('sEcho')) ? intval($this->input->post('sEcho')) : '';
        $sortCol = ($this->input->post('iSortCol_0')) ? intval($this->input->post('iSortCol_0')) : '';
        $sortOrder = ($this->input->post('sSortDir_0')) ? $this->input->post('sSortDir_0') : 'ASC';
        $order_status = ($this->uri->segment('4')) ? $this->uri->segment('4') : '';
        $sortfields = array(1 => 'o.entity_id', '2' => 'restaurant.name', '3' => ' u.first_name', '4' => 'o.total_rate', '5' => 'driver.first_name', '6' => 'o.order_status', '7' => 'o.created_date', '8' => 'o.order_delivery', '9' => 'o.status');
        $sortFieldName = '';
        if (array_key_exists($sortCol, $sortfields)) {
            $sortFieldName = $sortfields[$sortCol];
        }
        //Get Recored from model
        $grid_data = $this->order_model->getGridList($sortFieldName, $sortOrder, $displayStart, $displayLength, $order_status);
        $totalRecords = $grid_data['total'];
        $records = array();
        $records["aaData"] = array();
        $nCount = ($displayStart != '') ? $displayStart + 1 : 1;
        foreach ($grid_data['data'] as $key => $val) {
            $currency_symbol = $this->common_model->getCurrencySymbol($val->currency_id);
            $disabled = ($val->ostatus == 'delivered' || $val->ostatus == 'cancel') ? 'disabled' : '';
            $assignDisabled = ($val->first_name != '' || $val->last_name != '' || $val->order_delivery != "Delivery") ? 'disabled' : '';
            $trackDriver = (($val->first_name != '' || $val->last_name != '') && $val->order_delivery == "Delivery") ? '<a target="_blank" href="' . base_url() . ADMIN_URL . '/order/track_order/' . str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($val->entity_id)) . '" title="Click here to view driver live position" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-eye"></i> ' . $this->lang->line('track_driver') . '</a>' : '';
            $assignDisabledStatus = ($val->status != 1) ? 'disabled' : '';
            $ostatus = ($val->ostatus) ? "'" . $val->ostatus . "'" : '';
            $restaurant = ($val->restaurant_detail) ? unserialize($val->restaurant_detail) : '';
            $accept = ($val->status != 1 && $val->restaurant_id && $val->ostatus != 'delivered' && $val->ostatus != 'cancel') ? '<button onclick="disableDetail(' . $val->entity_id . ',' . $val->restaurant_id . ',' . $val->entity_id . ')"  title="' . $this->lang->line('accept') . '" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-check"></i> ' . $this->lang->line('accept') . '</button>' : '';
            $reject = ($val->ostatus != 'delivered' && $val->ostatus != 'cancel' && $val->status != 1) ? '<button onclick="rejectOrder(' . $val->user_id . ',' . $val->restaurant_id . ',' . $val->entity_id . ')"  title="' . $this->lang->line('reject') . '" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> ' . $this->lang->line('reject') . '</button>' : '';
            //$print = ($val->status == 1) ? '<a class="btn btn-sm danger-btn margin-bottom" href="'.base_url().ADMIN_URL.'/'.$this->controller_name.'/print_page" <i class="fa fa-edit"></i> </a>' : '';
            $print = ($val->status == 1 && $val->manual_order == 1) ? '<button onclick="print_page(' . $val->entity_id . ')"  title="Click here to print order details" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-print" aria-hidden="true"></i> Print </button>' : '';
            $updateStatus = ($val->status == 1) ? '<button onclick="updateStatus(' . $val->entity_id . ',' . $ostatus . ',' . $val->user_id . ')" ' . $disabled . ' title="Click here for update status" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-edit"></i> ' . $this->lang->line('change_status') . '</button>' : '';
            $viewComment = ($val->extra_comment != '') ? '<button onclick="viewComment(' . $val->entity_id . ')" title="Click here to view comment" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-eye"></i> ' . $this->lang->line('view_comment') . '</button>' : '';
            if ($val->ostatus == "placed") {
                $ostatuslng = $this->lang->line('placed');
            }
            if ($val->ostatus == "delivered") {
                $ostatuslng = $this->lang->line('delivered');
            }
            if ($val->ostatus == "onGoing") {
                $ostatuslng = $this->lang->line('onGoing');
            }
            if ($val->ostatus == "cancel") {
                $ostatuslng = $this->lang->line('cancel');
            }
            if ($val->ostatus == "preparing") {
                $ostatuslng = $this->lang->line('preparing');
            }
            if ($val->ostatus == "pending") {
                $ostatuslng = $this->lang->line('pending');
            }
            if ($val->order_delivery == "Delivery") {
                $order_delivery = $this->lang->line('delivery');
            }
            if ($val->order_delivery == "PickUp") {
                $order_delivery = $this->lang->line('pickup');
            }

            $records["aaData"][] = array(
                '<input type="checkbox" name="ids[]" value="' . $val->entity_id . '">',
                $val->entity_id,
                ($restaurant) ? $restaurant->name : $val->name,
                ($val->fname || $val->lname) ? $val->fname . ' ' . $val->lname : 'Order by Restaurant',
                ($val->rate) ? $currency_symbol->currency_symbol . number_format_unchanged_precision($val->rate, $currency_symbol->currency_code) : '',
                $val->first_name . ' ' . $val->last_name,
                $ostatuslng,
                ($val->created_date) ? date('d-m-Y g:i A', strtotime($val->created_date)) : '',
                ($val->delivery_time) ? '<b>Date </b> : ' . date('d-m-Y', strtotime($val->delivery_time)) . ' <b>Time</b>: ' . $val->time_slot : '',
                $order_delivery,
                ($val->status) ? $this->lang->line('active') : $this->lang->line('inactive'),
                ($this->session->userdata('UserType') == 'MasterAdmin') ? ' ' . $accept . $reject . '<button onclick="deleteDetail(' . $val->entity_id . ')"  title="' . $this->lang->line('click_delete') . '" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> ' . $this->lang->line('delete') . '</button> <button onclick="getInvoice(' . $val->entity_id . ')"  title="' . $this->lang->line('download_invoice') . '" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> ' . $this->lang->line('invoice') . '</button>' . $updateStatus . '
                    <button onclick="statusHistory(' . $val->entity_id . ')" title="Click here for view status history" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-history"></i> ' . $this->lang->line('status_history') . '</button>' . $viewComment . '<button onclick="updateDriver(' . $val->entity_id . ')" ' . $assignDisabled . ' ' . $assignDisabledStatus . ' title="Click here to assign driver" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-user"></i> ' . $this->lang->line('assign_driver') . '</button>' . $trackDriver . '
                    ' . $print  : ' ' . $accept . $reject . '<button onclick="getInvoice(' . $val->entity_id . ')"  title="' . $this->lang->line('download_invoice') . '" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-times"></i> ' . $this->lang->line('invoice') . '</button>' . $print
            );
            $nCount++;
        }
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }
    public function track_order()
    {
        $data['meta_title'] = $this->lang->line('track_order') . ' | ' . $this->lang->line('site_title');
        $order_id = ($this->uri->segment('4')) ? $this->encryption->decrypt(str_replace(array('-', '_', '~'), array('+', '/', '='), $this->uri->segment('4'))) : '';
        if (!empty($order_id)) {
            $data['latestOrder'] = $this->order_model->getLatestOrder($order_id);
            $data['order_id'] = $order_id;
            $this->load->view(ADMIN_URL . '/track_order', $data);
        }
    }
    // ajax track user's order
    public function ajax_track_order()
    {
        $data['meta_title'] = $this->lang->line('track_order') . ' | ' . $this->lang->line('site_title');
        $data['latestOrder'] = array();
        if (!empty($this->input->post('order_id'))) {
            $data['latestOrder'] = $this->order_model->getLatestOrder($this->input->post('order_id'));
        }
        $data['order_id'] = $this->input->post('order_id');
        $this->load->view(ADMIN_URL . '/ajax_track_order', $data);
    }
    // updating status to reject a order
    public function ajaxReject()
    {
        $user_id = ($this->input->post('user_id') != '') ? $this->input->post('user_id') : '';
        $restaurant_id = ($this->input->post('restaurant_id') != '') ? $this->input->post('restaurant_id') : '';
        $order_id = ($this->input->post('order_id') != '') ? $this->input->post('order_id') : '';
        if ($user_id && $restaurant_id && $order_id) {
            $this->db->set('order_status', 'cancel')->where('entity_id', $order_id)->update('order_master');
            $addData = array(
                'order_id' => $order_id,
                'order_status' => 'cancel',
                'time' => date('Y-m-d H:i:s'),
                'status_created_by' => 'Admin'
            );
            $this->order_model->addData('order_status', $addData);
            $userdata = $this->order_model->getUserDate($user_id);
            $message = $this->lang->line('order_canceled');
            $device_id = $userdata->device_id;
            $this->sendFCMRegistration($device_id, $message, 'cancel', $restaurant_id);
        }
    }
    // assign driver
    public function assignDriver()
    {
        if (!empty($this->input->post('order_entity_id')) && !empty($this->input->post('driver_id'))) {
            $distance = $this->order_model->getOrderDetails($this->input->post('order_entity_id'));
            $comsn = 0;
            if ($distance[0]->distance > 3) {
                $this->db->select('OptionValue');
                $comsn = $this->db->get_where('system_option', array('OptionSlug' => 'driver_commission_more'))->first_row();
            } else {
                $this->db->select('OptionValue');
                $comsn = $this->db->get_where('system_option', array('OptionSlug' => 'driver_commission_less'))->first_row();
            }
            $order_detail = array(
                'driver_commission' => $comsn->OptionValue,
                'commission' => $comsn->OptionValue,
                'distance' => $distance[0]->distance,
                'driver_id' => $this->input->post('driver_id'),
                'order_id' => $this->input->post('order_entity_id'),
                'is_accept' => 1
            );
            $driver_map_id = $this->order_model->addData('order_driver_map', $order_detail);
            if (!empty($driver_map_id)) {
                // after assigning a driver need to update the order status
                $order_status = "preparing";
                $this->db->set('order_status', $order_status)->where('entity_id', $this->input->post('order_entity_id'))->update('order_master');
                $addData = array(
                    'order_id' => $this->input->post('order_entity_id'),
                    'order_status' => $order_status,
                    'time' => date('Y-m-d H:i:s'),
                    'status_created_by' => 'Admin'
                );
                $order_id = $this->order_model->addData('order_status', $addData);
                // adding notification for website
                $order_status = 'order_preparing';
                if ($order_status != '') {
                    $order_detail = $this->common_model->getSingleRow('order_master', 'entity_id', $this->input->post('order_entity_id'));
                    $notification = array(
                        'order_id' => $this->input->post('order_entity_id'),
                        'user_id' => $order_detail->user_id,
                        'notification_slug' => $order_status,
                        'view_status' => 0,
                        'datetime' => date("Y-m-d H:i:s"),
                    );
                    $this->common_model->addData('user_order_notification', $notification);
                }
                //notification to user
                $device = $this->order_model->getDevice($order_detail->user_id);
                $languages = $this->db->select('*')->get_where('languages', array('language_slug' => $device->language_slug))->first_row();
                $this->lang->load('messages_lang', $languages->language_directory);
                $message = $this->lang->line($order_status);
                $device_id = $device->device_id;
                $restaurant = $this->order_model->orderDetails($this->input->post('order_entity_id'));
                $this->sendFCMRegistration($device_id, $message, 'preparing', $restaurant[0]->restaurant_id);

                //notification to driver
                $device = $this->order_model->getDevice($this->input->post('driver_id'));
                if ($device->device_id) {
                    //get langauge
                    $languages = $this->db->select('*')->get_where('languages', array('language_slug' => $device->language_slug))->first_row();
                    $this->lang->load('messages_lang', $languages->language_directory);
                    #prep the bundle
                    $fields = array();
                    $message = $this->lang->line('order_assigned');
                    $fields['to'] = $device->device_id; // only one user to send push notification
                    $fields['notification'] = array('body'  => $message, 'sound' => 'default');
                    $fields['data'] = array('screenType' => 'order');

                    $headers = array(
                        'Authorization: key=' . FCM_KEY,
                        'Content-Type: application/json'
                    );
                    #Send Reponse To FireBase Server    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $result = curl_exec($ch);
                    curl_close($ch);
                }
                echo 'success';
            }
        }
    }
    // view comment
    public function viewComment()
    {
        $entity_id = ($this->input->post('entity_id') != '') ? $this->input->post('entity_id') : '';
        if ($entity_id) {
            $comment = $this->order_model->getOrderComment($entity_id);
            echo $comment->extra_comment;
        }
    }
    // updating status and send request to driver
    public function ajaxdisable()
    {
        $entity_id = ($this->input->post('entity_id') != '') ? $this->input->post('entity_id') : '';
        $restaurant_id = ($this->input->post('restaurant_id') != '') ? $this->input->post('restaurant_id') : '';
        $order_id = ($this->input->post('order_id') != '') ? $this->input->post('order_id') : '';
        if ($entity_id != '' && $restaurant_id != '' && $order_id != '') {
            $this->order_model->UpdatedStatus('order_master', $entity_id, $restaurant_id, $order_id);
            // adding order status
            $addData = array(
                'order_id' => $order_id,
                'order_status' => 'accepted_by_restaurant',
                'time' => date('Y-m-d H:i:s'),
                'status_created_by' => 'Admin'
            );
            $status_id = $this->order_model->addData('order_status', $addData);
            // adding notification for website
            $order_detail = $this->common_model->getSingleRow('order_master', 'entity_id', $order_id);
            $notification = array(
                'order_id' => $order_id,
                'user_id' => $order_detail->user_id,
                'notification_slug' => 'order_accepted',
                'view_status' => 0,
                'datetime' => date("Y-m-d H:i:s"),
            );
            $this->common_model->addData('user_order_notification', $notification);
        }
    }
    // method for deleting
    public function ajaxDelete()
    {
        $entity_id = ($this->input->post('entity_id') != '') ? $this->input->post('entity_id') : '';
        $this->order_model->ajaxDelete('order_master', $entity_id);
    }
    //get item of restro
    public function getItem()
    {
        $entity_id = ($this->input->post('entity_id') != '') ? $this->input->post('entity_id') : '';
        if ($entity_id) {
            $result =  $this->order_model->getItem($entity_id);
            $html = '<option value="">' . $this->lang->line('select') . '</option>';
            foreach ($result as $key => $value) {
                $html .= '<option value="' . $value->entity_id . '" data-id="' . $value->price . '">' . $value->name . '</option>';
            }
        }
        echo $html;
    }
    //get address
    public function getAddress()
    {
        $entity_id = ($this->input->post('entity_id') != '') ? $this->input->post('entity_id') : '';
        if ($entity_id) {
            $result =  $this->order_model->getAddress($entity_id);
            $html = '<option value="">' . $this->lang->line('select') . '</option>';
            foreach ($result as $key => $value) {
                $address_detail = json_decode($value->address_detail);
                $html .= '<option value="' . $value->entity_id . '">' . 'Road No: ' . $address_detail->RoadNo . ', Block No: ' . $address_detail->BlockNo . ', Flat No: ' . $address_detail->FlatNo  . ', House No: ' . $address_detail->Houseno . ' , ' . $address_detail->area . '</option>';
            }
        }
        echo $html;
    }
    //pending
    public function pending()
    {
        $data['meta_title'] = $this->lang->line('title_admin_pending') . ' | ' . $this->lang->line('site_title');
        $this->load->view(ADMIN_URL . '/pending_order', $data);
    }
    public function customize_items()
    {
        $data['meta_title'] = ' Customized Items| ' . $this->lang->line('site_title');
        $this->load->view(ADMIN_URL . '/customize_items', $data);
    }
    //delivered
    public function delivered()
    {
        $data['meta_title'] = $this->lang->line('title_admin_delivered') . ' | ' . $this->lang->line('site_title');
        $this->load->view(ADMIN_URL . '/delivered_order', $data);
    }
    //on going
    public function on_going()
    {
        $data['meta_title'] = $this->lang->line('title_admin_ongoing') . ' | ' . $this->lang->line('site_title');
        $this->load->view(ADMIN_URL . '/ongoing_order', $data);
    }
    //cancel
    public function cancel()
    {
        $data['meta_title'] = $this->lang->line('title_admin_cancel') . ' | ' . $this->lang->line('site_title');
        $this->load->view(ADMIN_URL . '/cancel_order', $data);
    }
    //create invoice
    public function getInvoice()
    {
        $entity_id = ($this->input->post('entity_id')) ? $this->input->post('entity_id') : '';
        $data['order_records'] = $this->order_model->getEditDetail($entity_id);
        $data['menu_item'] = $this->order_model->getInvoiceMenuItem($entity_id);
        if ($data['order_records']->manual_order == 0 && $data['order_records']->was_customize == 0) {
            $html = $this->load->view('backoffice/normal_order_invoice', $data, true);
        }

        if ($data['order_records']->was_customize == 1) {
            $html = $this->load->view('backoffice/custom_order_invoice', $data, true);
        }
        if ($data['order_records']->manual_order == 1) {
            $html = $this->load->view('backoffice/manual_order_invoice', $data, true);
        }

        if (!@is_dir('uploads/invoice')) {
            @mkdir('./uploads/invoice', 0777, TRUE);
        }
        $filepath = 'uploads/invoice/' . $entity_id . '.pdf';
        $this->load->library('M_pdf');
        $mpdf = new mPDF('', 'Letter');
        $mpdf->SetHTMLHeader('');
        $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
        $mpdf->AddPage(
            '', // L - landscape, P - portrait 
            '',
            '',
            '',
            '',
            0, // margin_left
            0, // margin right
            10, // margin top
            23, // margin bottom
            0, // margin header
            0 //margin footer
        );
        $mpdf->autoScriptToLang = true;
        $mpdf->SetAutoFont();
        $mpdf->WriteHTML($html);
        $mpdf->output($filepath, 'F');
        echo $filepath;
    }
    //add status
    public function updateOrderStatus()
    {
        $entity_id = ($this->input->post('entity_id')) ? $this->input->post('entity_id') : '';
        $order_status = ($this->input->post('order_status')) ? $this->input->post('order_status') : '';
        $user_id = ($this->input->post('user_id')) ? $this->input->post('user_id') : '';
        if ($entity_id && $order_status) {
            $this->db->set('order_status', $this->input->post('order_status'))->where('entity_id', $entity_id)->update('order_master');
            $addData = array(
                'order_id' => $entity_id,
                'order_status' => $this->input->post('order_status'),
                'time' => date('Y-m-d H:i:s'),
                'status_created_by' => 'Admin'
            );
            $order_id = $this->order_model->addData('order_status', $addData);
            // adding notification for website
            $order_status = '';
            if ($this->input->post('order_status') == "complete") {
                $this->common_model->deleteData('user_order_notification', 'order_id', $entity_id);
            } else if ($this->input->post('order_status') == "preparing") {
                $order_status = 'order_preparing';
            } else if ($this->input->post('order_status') == "onGoing") {
                $order_status = 'order_ongoing';
            } else if ($this->input->post('order_status') == "delivered") {
                $order_status = 'order_delivered';
            } else if ($this->input->post('order_status') == "cancel") {
                $order_status = 'order_canceled';
            }
            if ($order_status != '') {
                $order_detail = $this->common_model->getSingleRow('order_master', 'entity_id', $entity_id);
                $notification = array(
                    'order_id' => $entity_id,
                    'user_id' => $order_detail->user_id,
                    'notification_slug' => $order_status,
                    'view_status' => 0,
                    'datetime' => date("Y-m-d H:i:s"),
                );
                $this->common_model->addData('user_order_notification', $notification);
            }

            $userdata = $this->order_model->getUserDate($user_id);
            //get langauge
            $device = $this->order_model->getDevice($user_id);
            $languages = $this->db->select('*')->get_where('languages', array('language_slug' => $device->language_slug))->first_row();
            $this->lang->load('messages_lang', $languages->language_directory);
            $message = $this->lang->line($order_status);
            $device_id = $userdata->device_id;
            $restaurant = $this->order_model->orderDetails($entity_id);
            $this->sendFCMRegistration($device_id, $message, $this->input->post('order_status'), $restaurant[0]->restaurant_id);
        }
    }
    // Send notification
    function sendFCMRegistration($registrationIds, $message, $order_status, $restaurant_id)
    {
        if ($registrationIds) {
            #prep the bundle
            $fields = array();

            $fields['to'] = $registrationIds; // only one user to send push notification
            $fields['notification'] = array('body'  => $message, 'sound' => 'default');
            if ($order_status == "delivered") {
                $fields['data'] = array('screenType' => 'delivery', 'restaurant_id' => $restaurant_id);
            } else {
                $fields['data'] = array('screenType' => 'order');
            }
            $headers = array(
                'Authorization: key=' . Driver_FCM_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
    public function deleteMultiOrder()
    {
        $orderId = ($this->input->post('arrayData')) ? $this->input->post('arrayData') : "";
        if ($orderId) {
            $order_id = explode(',', $orderId);
            $data = $this->order_model->deleteMultiOrder($order_id);
            echo json_encode($data);
        }
    }
    //get status history
    public function statusHistory()
    {
        $entity_id = ($this->input->post('order_id')) ? $this->input->post('order_id') : '';
        if ($entity_id) {
            $data['history'] = $this->order_model->statusHistory($entity_id);
            $this->load->view(ADMIN_URL . '/view_status_history', $data);
        }
    }
    //generate report
    public function generate_report()
    {
        $restaurant_id = $this->input->post('restaurant_id');
        $order_type = $this->input->post('order_delivery');
        $order_date = $this->input->post('order_date');
        $results = $this->order_model->generate_report($restaurant_id, $order_type, $order_date);
        if (!empty($results)) {
            // export as an excel sheet
            $this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('Reports');
            $headers = array("Restaurant", "User Name", "Order Total", "Order Delivery", "Order Date", "Order Status", "Status");

            for ($h = 0, $c = 'A'; $h < count($headers); $h++, $c++) {
                $this->excel->getActiveSheet()->setCellValue($c . '1', $headers[$h]);
                $this->excel->getActiveSheet()->getStyle($c . '1')->getFont()->setBold(true);
            }
            $row = 2;
            for ($r = 0; $r < count($results); $r++) {
                $status = ($results[$r]->status) ? 'Active' : 'Deactive';
                $this->excel->getActiveSheet()->setCellValue('A' . $row, $results[$r]->name);
                $this->excel->getActiveSheet()->setCellValue('B' . $row, $results[$r]->first_name . ' ' . $results[$r]->last_name);
                $this->excel->getActiveSheet()->setCellValue('C' . $row, number_format_unchanged_precision($results[$r]->total_rate, $results[$r]->currency_code));
                $this->excel->getActiveSheet()->setCellValue('D' . $row, $results[$r]->order_delivery);
                $this->excel->getActiveSheet()->setCellValue('E' . $row, $results[$r]->order_date);
                $this->excel->getActiveSheet()->setCellValue('F' . $row, ucfirst($results[$r]->order_status));
                $this->excel->getActiveSheet()->setCellValue('G' . $row, $status);
                $row++;
            }
            $filename = 'report-export.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type
            header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache   
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');

            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
            exit;
        } else {
            $this->session->set_flashdata('not_found', $this->lang->line('not_found'));
            redirect(base_url() . ADMIN_URL . '/' . $this->controller_name . '/view');
        }
    }


    public function getCustomizedItems()
    {
        $displayLength = ($this->input->post('iDisplayLength') != '') ? intval($this->input->post('iDisplayLength')) : '';
        $displayStart = ($this->input->post('iDisplayStart') != '') ? intval($this->input->post('iDisplayStart')) : '';
        $sEcho = ($this->input->post('sEcho')) ? intval($this->input->post('sEcho')) : '';
        $sortCol = ($this->input->post('iSortCol_0')) ? intval($this->input->post('iSortCol_0')) : '';
        $sortOrder = ($this->input->post('sSortDir_0')) ? $this->input->post('sSortDir_0') : 'ASC';
        $sortfields = array(1 => 'q.entity_id', '2' => 'restaurant.name', '3' => ' u.first_name', '4' => 'q.size', '5' => 'q.flavour', '6' => 'q.image1', '7' => 'q.image2', '8' => 'q.image3', '9' => 'q.description', '10' => 'q.date', '11' => 'q.delivery_time', '12' => 'time_slot');
        $sortFieldName = '';
        // if(array_key_exists($sortCol, $sortfields))
        // {
        //     $sortFieldName = $sortfields[$sortCol];
        // }
        //Get Recored from model
        $grid_data = $this->order_model->getCustomizedItems($sortFieldName, $sortOrder, $displayStart, $displayLength);
        $totalRecords = $grid_data['total'];
        $records = array();
        $records["aaData"] = array();
        $nCount = ($displayStart != '') ? $displayStart + 1 : 1;
        foreach ($grid_data['data'] as $key => $val) {

            $scrollLeft = '<button onclick="scrollbarLeft()" title="Click here to scroll" class="delete btn btn-sm danger-btn margin-bottom"><i class="fas fa-arrow-circle-right"></i></i></button>';
            $scrollRight = '<button onclick="scrollbarRight()" title="Click here to scroll" class="delete btn btn-sm danger-btn margin-bottom"><i class="fas fa-arrow-circle-left"></i></button>';
            $edit = '<button onclick="edit(' . $val->q_id . ')"  title="Click here for edit status" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-edit"></i> Edit </button>';
            $payment = ($val->accepted_time) ? '<button onclick="payment(' . $val->q_id . ')"  title="Click here for payment" class="delete btn btn-sm danger-btn margin-bottom"><i class="fa fa-credit-card" aria-hidden="true"></i> Advance Payment </button>' : '';
            $records["aaData"][] = array(
                '<input type="checkbox" name="ids[]" value="' . $val->q_id . '">',
                $scrollLeft,
                $val->q_id,
                $val->res_name,
                ($val->fname || $val->lname) ? $val->fname . ' ' . $val->lname : '',
                $val->mobile_number,
                $val->address . '<br> ' . $val->landmark . '<br>' . $val->city . ' ' . $val->zipcode,
                $val->size,
                $val->flavour,
                ($val->image1) ? "<img src=" . image_url . $val->image1 . " width='200' height='200' onclick='window.open(this.src)' style='cursor:pointer' alt='Image 1'/>" : "",
                ($val->image2) ? "<img src=" . image_url . $val->image2 . " width='200' height='200' onclick='window.open(this.src)' style='cursor:pointer' alt='Image 2'/>" : "",
                ($val->image3) ? "<img src=" . image_url . $val->image3 . " width='200' height='200' onclick='window.open(this.src)' style='cursor:pointer' alt='Image 3'/>" : "",
                $val->description,

                ($val->date) ? date('d-m-Y g:i A', strtotime($val->date)) : '',
                ($val->delivery_time) ? '<b>Date </b> : ' . date('d-m-Y', strtotime($val->delivery_time)) . ' <b>Time</b>: ' . $val->time_slot : '',
                ($val->status) ? $this->lang->line('active') : $this->lang->line('inactive'),
                $edit . $payment,
                $scrollRight

            );
            $nCount++;
        }
        $records["sEcho"] = $sEcho;
        $records["iTotalRecords"] = $totalRecords;
        $records["iTotalDisplayRecords"] = $totalRecords;
        echo json_encode($records);
    }

    public function editOrderList()
    {
        $order_id = ($this->input->post('order_id')) ? $this->input->post('order_id') : '';
        if ($order_id) {
            $data['order_details'] = $this->order_model->order_details($order_id);
            $this->load->view(ADMIN_URL . '/edit_order_list', $data);
        }
    }

    public function updateOrderList()
    {
        // $total_rate = ($this->input->post('delivery_charge')) ? $this->input->post('price') + $this->input->post('delivery_charge') : $this->input->post('price');
        // $add_data = array(
        //     'user_id' => $this->input->post('user_id'),
        //     'restaurant_id' => $this->input->post('restaurant_id'),
        //     'address_id' => $this->input->post('address_id'),
        //     'subtotal' => $this->input->post('price'),
        //     'total_rate' => $total_rate,
        //     'status' => 1,
        //     'delivery_charge' => ($this->input->post('delivery_charge')) ? $this->input->post('delivery_charge') : '',
        //     'quotes_id' => $this->input->post('entity_id'),
        //     'was_customize' => 1,
        //     'order_date' => $this->input->post('date'),
        //     'accept_order_time' => date('Y-m-d H:i:s'),
        //     'order_status' => 'preparing'
        // );
        // $order_id = $this->order_model->addData('order_master', $add_data);

        // $addData = array(
        //     'order_id' => $order_id,
        //     'order_status' => 'preparing',
        //     'time' => date('Y-m-d H:i:s'),
        //     'status_created_by' => 'Admin'
        // );
        // $this->order_model->addData('order_status', $addData);

        //if ($order_id) {
        $updateData = array(
            //'status' => 1,
            'price' => $this->input->post('price'),
            'time_slot' => $this->input->post('time_slot'),
            'delivery_time' => $this->input->post('delivery_time'),
            'description' => $this->input->post('description'),
            'admin_description' => $this->input->post('admin_description'),
            'flavour' => $this->input->post('flavour'),
            'size' => $this->input->post('size'),
            'vat' => $this->input->post('vat'),
            'sd' => $this->input->post('sd'),
            'delivery_charge' => $this->input->post('delivery_charge'),
            'accepted_time' => date('Y-m-d H:i:s')
        );
        $this->common_model->updateData('quotes', $updateData, 'entity_id', $this->input->post('entity_id'));

        // $address = $this->api_model->getAddress('user_address', 'entity_id', $this->input->post('address_id'));
        $user =  $this->api_model->getRecord('users', 'entity_id', $this->input->post('user_id'));
        // $quotes = $this->api_model->getRecord('quotes', 'entity_id', $this->input->post('entity_id'));
        // $user_detail = array(
        //     'first_name' => $user->first_name,
        //     'last_name' => ($user->last_name) ? $user->last_name : '',
        //     'address' => ($address) ? $address[0]->address : '',
        //     'landmark' => ($address) ? $address[0]->landmark : '',
        //     'zipcode' => ($address) ? $address[0]->zipcode : '',
        //     'city' => ($address) ? $address[0]->city : '',
        //     'latitude' => ($address) ? $address[0]->latitude : '',
        //     'longitude' => ($address) ? $address[0]->longitude : '',
        // );

        // $add_item = array();

        // $add_item[] = array(
        //     'size' => $quotes->size,
        //     'flavour' => $quotes->flavour,
        //     'description' => $quotes->description,
        //     'admin_description' => $quotes->admin_description,
        //     'delivery_time' => $quotes->date,
        //     'time_slot' => $quotes->time_slot,
        //     'price' => $quotes->price
        // );

        // $taxdetail = $this->api_model->getRestaurantTax('restaurant', $this->input->post('restaurant_id'), $flag = "order");
        // $order_detail = array(
        //     'order_id' => $order_id,
        //     'user_detail' => serialize($user_detail),
        //     'item_detail' => serialize($add_item),
        //     'restaurant_detail' => serialize($taxdetail),
        // );
        // $this->api_model->addRecord('order_detail', $order_detail);


        if ($user->device_id) {
            //get langauge
            $languages = $this->db->select('*')->get_where('languages', array('language_slug' => $user->language_slug))->first_row();
            $this->lang->load('messages_lang', $languages->language_directory);
            #prep the bundle
            $fields = array();
            $message = 'Your quotation no: ' . $this->input->post('entity_id') . ' is accepted'; //$this->lang->line('push_order_accept');
            $fields['to'] = $user->device_id; // only one user to send push notification
            $fields['notification'] = array('body'  => $message, 'sound' => 'default');
            $fields['data'] = array('screenType' => 'order');

            $headers = array(
                'Authorization: key=' . Driver_FCM_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }
        echo 'success';
        //}
    }

    public function checkExist()
    {
        $mobile_number = ($this->input->post('mobile_number') != '') ? $this->input->post('mobile_number') : '';

        if ($mobile_number != '') {
            $check = $this->order_model->checkExist($mobile_number);
            echo $check;
        }
    }

    public function print_page()
    {
        $data['meta_title'] = ' Print | ' . $this->lang->line('site_title');
        $entity_id = ($this->uri->segment('4')) ? $this->uri->segment('4') : '';
        $data['order_records'] = $this->order_model->getEditDetail($entity_id);
        $data['menu_item'] = $this->order_model->getInvoiceMenuItem($entity_id);
        $this->load->view(ADMIN_URL . '/manual_order_print', $data);
    }

    public function addPayment()
    {
        $quote_id = ($this->input->post('quote_id')) ? $this->input->post('quote_id') : '';
        if ($quote_id) {
            $data['quotes_details'] = $this->order_model->getRecord('quotes', 'entity_id', $quote_id);
            $this->load->view(ADMIN_URL . '/add_payment', $data);
        }
    }

    public function updatePayment()
    {
        $add_data = array(
            'user_id' => $this->input->post('user_id'),
            'restaurant_id' => $this->input->post('restaurant_id'),
            'address_id' => $this->input->post('address_id'),
            'subtotal' => $this->input->post('subtotal'),
            'total_rate' => $this->input->post('total_rate'),
            'status' => 1,
            'delivery_charge' => ($this->input->post('delivery_charge')) ? $this->input->post('delivery_charge') : '',
            'vat' => $this->input->post('vat'),
            'sd' => $this->input->post('sd'),
            'advance' => $this->input->post('advance'),
            'due' => $this->input->post('due'),
            'quotes_id' => $this->input->post('entity_id'),
            'was_customize' => 1,
            'order_date' => $this->input->post('date'),
            'accept_order_time' => $this->input->post('accepted_time'),
            'order_status' => 'placed',
            'delivery_time' => $this->input->post('delivery_time'),
            'time_slot' => $this->input->post('time_slot'),
        );
        $order_id = $this->order_model->addData('order_master', $add_data);

        $updateData = array(
            'status' => 1,
        );
        $this->common_model->updateData('quotes', $updateData, 'entity_id', $this->input->post('entity_id'));

        $addData[0] = array(
            'order_id' => $order_id,
            'order_status' => 'accepted_by_restaurant',
            'time' => $this->input->post('accepted_time'),
            'status_created_by' => 'Admin'
        );
        $addData[1] = array(
            'order_id' => $order_id,
            'order_status' => 'preparing',
            'time' => date('Y-m-d H:i:s'),
            'status_created_by' => 'Admin'
        );
        $this->order_model->addBatch('order_status', $addData);

        $address = $this->api_model->getAddress('user_address', 'entity_id', $this->input->post('address_id'));
        $user =  $this->api_model->getRecord('users', 'entity_id', $this->input->post('user_id'));
        $quotes = $this->api_model->getRecord('quotes', 'entity_id', $this->input->post('entity_id'));
        $user_detail = array(
            'first_name' => $user->first_name,
            'last_name' => ($user->last_name) ? $user->last_name : '',
            'address' => ($address) ? $address[0]->address_detail : '',
            // 'landmark' => ($address) ? $address[0]->landmark : '',
            // 'zipcode' => ($address) ? $address[0]->zipcode : '',
            // 'city' => ($address) ? $address[0]->city : '',
            // 'latitude' => ($address) ? $address[0]->latitude : '',
            // 'longitude' => ($address) ? $address[0]->longitude : '',
        );

        $add_item = array();

        $add_item[] = array(
            'size' => $quotes->size,
            'flavour' => $quotes->flavour,
            'description' => $quotes->description,
            'admin_description' => $quotes->admin_description,
            'delivery_time' => $quotes->date,
            'time_slot' => $quotes->time_slot,
            'price' => $quotes->price
        );

        $taxdetail = $this->api_model->getRestaurantTax('restaurant', $this->input->post('restaurant_id'), $flag = "order");
        $order_detail = array(
            'order_id' => $order_id,
            'user_detail' => serialize($user_detail),
            'item_detail' => serialize($add_item),
            'restaurant_detail' => serialize($taxdetail),
        );
        $this->api_model->addRecord('order_detail', $order_detail);


        if ($user->device_id) {
            //get langauge
            $languages = $this->db->select('*')->get_where('languages', array('language_slug' => $user->language_slug))->first_row();
            $this->lang->load('messages_lang', $languages->language_directory);
            #prep the bundle
            $fields = array();
            $message = 'Advance payment tk ' . $this->input->post('advance') . ' received. Your Quotes ID: ' . $this->input->post('entity_id') . ' is being prepared'; //$this->lang->line('push_order_accept');
            $fields['to'] = $user->device_id; // only one user to send push notification
            $fields['notification'] = array('body'  => $message, 'sound' => 'default');
            $fields['data'] = array('screenType' => 'order');

            $headers = array(
                'Authorization: key=' . Driver_FCM_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }
        echo 'success';
    }
}
?>