<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//require 'vendor/autoload.php';
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Report_template extends CI_Controller
{
    public $module_name = 'Report Template';
    public $controller_name = 'report_template';
    public $prefix = '_report';
    public $value;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(ADMIN_URL . '/report_model');
    }

    public function view()
    {
        $data['meta_title'] = 'Orders Report' . '|' . $this->lang->line('site_title');
        $data['res'] = $this->report_model->getAllRestaurant();

        $this->load->view(ADMIN_URL . '/sales_report_template', $data);
    }

    public function showallReports()
    {
        $this->load->model('report_model');
        $postData = $this->input->post();
        $data = $this->report_model->getAllOrderList($postData);
        echo json_encode($data); 
    }

    public function viewRiders()
    {
        $data['meta_title'] = 'Riders Report' . '|' . $this->lang->line('site_title');
        $data['groups'] = $this->report_model->getAllGroups();


        $this->load->view(ADMIN_URL . '/riders_report_template', $data);
    }


    // public function show()
    // {
    //     $data['res'] = $this->report_model->getAllRestaurant();

    //     if (isset($_POST['Tdate']) && isset($_POST['Fdate']) && isset($_POST['entity_id'])) {
    //         $Tdate = $_POST['Tdate'];
    //         $Fdate = $_POST['Fdate'];
    //         $restaurent = $_POST['entity_id'];
    //         $data['tdate'] = $_POST['Tdate'];
    //         $data['fdate'] = $_POST['Fdate'];
    //         $data['entity_id'] = $_POST['entity_id'];

    //         $data['order_data'] = $this->report_model->fetch($Fdate, $Tdate, $restaurent);
    //     } else {
    //         $data['order_data'] = $this->report_model->fetch("1970-12-12", "2050-12-12");
    //     }

    //     $this->load->view(ADMIN_URL . '/sales_report_template', $data);
    // }

    public function showRiders()
    {
        $this->load->model('report_model');
        $postData = $this->input->post();
        $data = $this->report_model->getAllRiderList($postData);
        echo json_encode($data); 

    }

    public function viewDeliveredReport()
    {
        $data['meta_title'] = 'Delivered Order' . '|' . $this->lang->line('site_title');
        $data['res'] = $this->report_model->getAllRestaurant();

        $this->load->view(ADMIN_URL . '/delivered_order_report', $data);
    }


    public function showDeliveredReport()
    {
        $this->load->model('report_model');
        $postData = $this->input->post();
        $data = $this->report_model->getAllDelieredList($postData);
        echo json_encode($data); 
        // $data['res'] = $this->report_model->getAllRestaurant();

        // if (isset($_POST['Tdate']) && isset($_POST['Fdate']) && isset($_POST['entity_id'])) {
        //     $Tdate = $_POST['Tdate'];
        //     $Fdate = $_POST['Fdate'];
        //     $restaurent = $_POST['entity_id'];
        //     $data['tdate'] = $_POST['Tdate'];
        //     $data['fdate'] = $_POST['Fdate'];
        //     $data['entity_id'] = $_POST['entity_id'];
        //     $data['order_data'] = $this->report_model->fetchdelivered($Fdate, $Tdate, $restaurent);
        // } else {
        //     $data['order_data'] = $this->report_model->fetchdelivered("1970-12-12", "2050-12-12");
        // }

        // $this->load->view(ADMIN_URL . '/delivered_order_report', $data);
    }


    public function export_rider()
    {
        $Tdate = $this->input->post('tdata');
        $Fdate = $this->input->post('fdata');
        $riderId = $this->input->post('entity_id');
        $total_rate = $this->input->post('total_rate');
        $commission = $this->input->post('commission');
        $total = $this->input->post('total');
        $to_date = date('d-M-Y H:i:s', strtotime($Tdate));
        $from_date = date('d-M-Y  H:i:s', strtotime($Fdate));
        $rider_report = $this->report_model->riders_report($Fdate, $Tdate, $riderId);
        $file_name = 'rider_' . $from_date . '_to_' . $to_date . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/csv;");

        $file = fopen('php://output', 'w');

        $header = array("Order ID", "Driver Earnings", "Commission", "Net Received");
        fputcsv($file, $header);

        foreach ($rider_report->result_array() as $key => $value) {
            fputcsv($file, $value);
        }
        $data = array(
            'Order ID' => 'Total',
            "Driver Earnings" => $total_rate,
            "Commission" => $commission,
            'net' => $total
        );
        // fputcsv($file,$total_rate,$commission,$total);
        fputcsv($file, $data);
        fclose($file);
        exit;
    }
    public function viewCancelReport()
    {
        $data['meta_title'] = 'Customer Report' . '|' . $this->lang->line('site_title');
        $data['cus'] = $this->report_model->getAllCustomer();

        $this->load->view(ADMIN_URL . '/customer_order_report', $data);
    }

    public function showCusOrderReports()
    {
        $this->load->model('report_model');
        $postData = $this->input->post();
        $data = $this->report_model->getCusOrderList($postData);
        echo json_encode($data); 
    }


    public function showCancelReport()
    {
        $data['res'] = $this->report_model->getAllRestaurant();

        if (isset($_POST['Tdate']) && isset($_POST['Fdate']) && isset($_POST['entity_id'])) {
            $Tdate = $_POST['Tdate'];
            $Fdate = $_POST['Fdate'];
            $restaurent = $_POST['entity_id'];
            $data['tdate'] = $_POST['Tdate'];
            $data['fdate'] = $_POST['Fdate'];
            $data['entity_id'] = $_POST['entity_id'];
            $data['order_data'] = $this->report_model->fetchExportData($Fdate, $Tdate, $restaurent);
        } else {
            $data['order_data'] = $this->report_model->fetchExportData("1970-12-12", "2050-12-12");
        }

        $this->load->view(ADMIN_URL . '/cancel_order_report', $data);
    }
    public function viewResOrders()
    {
        $data['meta_title'] = 'Restaurant Report' . '|' . $this->lang->line('site_title');
        $data['res'] = $this->report_model->getAllRestaurant();

        $this->load->view(ADMIN_URL . '/res_order_report', $data); 
    }

    public function showResOrderReports()
    {
        $this->load->model('report_model');
        $postData = $this->input->post();
        $data = $this->report_model->getResOrderList($postData);
        echo json_encode($data); 
    }

    public function viewUserAcquisition()
    {
        $data['meta_title'] = 'User Acquisition Report' . '|' . $this->lang->line('site_title');
        $data['cus'] = $this->report_model->getAllCustomer();

        $this->load->view(ADMIN_URL . '/user_acquisition_report', $data);
    }

    public function showUserAcquisition(){
        $this->load->model('report_model');
        $postData= $this->input->post();
        $data=$this->report_model->getAcquisitionUserList($postData);
        echo json_encode($data);
    }


  function export()
    {
        $Tdate = $this->input->post('tdata');
        $Fdate = $this->input->post('fdata');
        $restaurent = $this->input->post('entity_id');
        $type = $this->input->post('type');
        $total_subtotal = $this->input->post('total_subtotal');
        $delivery_charge = $this->input->post('delivery_charge');
        $coupon_discount = $this->input->post('coupon_discount');
        $vat = $this->input->post('vat');
        $sd = $this->input->post('sd');
        $total = $this->input->post('total_rate');
        $to_date = date('d-M-Y H:i:s', strtotime($Tdate));
        $from_date = date('d-M-Y  H:i:s', strtotime($Fdate));
        if ($type == 'cancel_order') {
            $type_data = $this->report_model->fetchExportData($Fdate, $Tdate, $restaurent);
        }
        if ($type == 'delivered_order') {
            $type_data = $this->report_model->fetchdelivered($Fdate, $Tdate, $restaurent);
        }
        if ($type == 'all_order') {
            $type_data = $this->report_model->exportAllData($Fdate, $Tdate, $restaurent);
        }

        $file_name = $type . '_' . $from_date . '_to_' . $to_date . '.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/csv;");

        // get data 

        // file creation 
        $file = fopen('php://output', 'w');

        $header = array("Order Number", "User Name", "Delivery Address", "Delivery Date", "Item Name (Quantity)", "Food Price", "Delivery Charge", "Discount", "VAT", "SD", "Total");
        fputcsv($file, $header);
        foreach ($type_data->result_array() as $key => $value) {

            $users = unserialize($value['user_detail']);
            $items = unserialize($value['item_detail']);

            foreach ($items as $key => $values) {
                // $product[] = $values['item_name'] . "(" .  $values['qty_no'] . ')' . "\n" ;
                $product[] = $values['item_name'] . "(" .  $values['qty_no'] . ')' . '  ' ;
            }
            $string = implode(" ", $product);
            unset($product);
          
            $array = array(
                'id' => $value['entity_id'],
                'name' => $users['first_name'] . '  ' . $users['last_name'],
                'address' => $users['address'] . '  ' . $users['landmark'] . '  ' . $users['zipcode'],
                'date' => $value['order_date'],
                'items' => $string,
                'price' => $value['subtotal'],
                "delicery_charge" => $value['delivery_charge'],
                'discount' => $value['coupon_discount'],
                'vat' => $value['vat'],
                'sd' => $value['sd'],
                'net' => $value['total_rate']
            );
            // $value['user_detail'] =  $users['first_name'] . ' ' . $users['last_name'];
            // $value['items'] = $string;
             fputcsv($file, $array);
        }
        $data = array(
            'total' => 'Total',
            'name' => '',
            'address' => '',
            'date' => '',
            'items' => '',
            "price" => $total_subtotal,
            "delicery_charge" => $delivery_charge,
            'discount' => $coupon_discount,
            'vat' => $vat,
            'sd' => $sd,
            'net' => $total
        );
        fputcsv($file, $data);
        fclose($file);
        exit;

        // $this->load->library("excel");
        // //$this->load->library('phpexcel');
        // //$this->load->library('IOFactory.php');
        // $object = new PHPExcel();

        // $object->setActiveSheetIndex(0);

        // $table_columns = array("Order Number", "Food Price", "Delivery Charge", "Discount", "VAT", "SD", "Total");

        // $column = 0;

        // foreach ($table_columns as $field) {
        //     $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
        //     $column++;
        // }
        //  $date = date('d-M-Y', strtotime($Tdate));
        // $employee_data = $this->report_model->fetchExportData($Fdate, $Tdate, $restaurent);

        // $excel_row = 2;

        // foreach ($employee_data->result_array() as $row) {
        //     $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->entity_id);
        //     $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->subtotal);
        //     $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->delivery_charge);
        //     $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->coupon_discount);
        //     $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->vat);
        //     $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->sd);
        //     $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->total_rate);
        //     $excel_row++;
        // }

        // $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');


        // header('Content-Type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename="Employee Data.xls"');
        // $object_writer->save('php://output');


        // $fileName = 'employee.xlsx';
        // $employeeData = $this->report_model->fetchExportData($Fdate, $Tdate, $restaurent);
        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Id');
        // $sheet->setCellValue('B1', 'Name');
        // $sheet->setCellValue('C1', 'Skills');
        // $sheet->setCellValue('D1', 'Address');
        // $sheet->setCellValue('E1', 'Age');
        // $sheet->setCellValue('F1', 'Designation');
        // $rows = 2;
        // foreach ($employeeData as $val) {
        //     $sheet->setCellValue('A' . $rows, $val['entity_id']);
        //     $sheet->setCellValue('B' . $rows, $val['subtotal']);
        //     $sheet->setCellValue('C' . $rows, $val['delivery_charge']);
        //     $sheet->setCellValue('D' . $rows, $val['coupon_discount']);
        //     $sheet->setCellValue('E' . $rows, $val['vat']);
        //     $sheet->setCellValue('F' . $rows, $val['sd']);
        //     $rows++;
        // }
        // $writer = new Xlsx($spreadsheet);
        // $writer->save("upload/" . $fileName);
        // header("Content-Type: application/vnd.ms-excel");
        // redirect(base_url() . "/upload/" . $fileName);
    }

    public function getPDF()
    {
        $entity_id = ($this->input->post('entity_id')) ? $this->input->post('entity_id') : '';
        $to_date = ($this->input->post('ToDate')) ? $this->input->post('ToDate') : '';
        $from_date = ($this->input->post('FromDate')) ? $this->input->post('FromDate') : '';
        $type = ($this->input->post('type')) ? $this->input->post('type') : '';
        if ($type == "allOrder") {
            $data['report'] = $this->report_model->exportAllData($from_date, $to_date, $entity_id);
        }
        if ($type == "deliveryReport") {
            $data['report'] = $this->report_model->fetchdelivered($from_date, $to_date, $entity_id);
        }
        if ($type == "cancelReport") {
            $data['report'] = $this->report_model->fetchExportData($from_date, $to_date, $entity_id);
        }

        if ($type == "riderReport") {
            $data['rider'] = $this->report_model->riders_report($from_date, $to_date, $entity_id);
        }


        $html = $this->load->view('backoffice/report_pdf', $data, true);
        if (!@is_dir('uploads/report')) {
            @mkdir('./uploads/report', 0777, TRUE);
        }
        // $filepath = 'uploads/report/' . $entity_id . '.pdf';
        $filepath = 'uploads/report/' . $type . '.pdf';
        $this->load->library('M_pdf');
        $mpdf = new mPDF('', 'Letter');
        $mpdf->SetHTMLHeader('');
        // $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
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

        public function allOrderPDF()
    {
        $data['res'] = $this->report_model->getAllRestaurant();
        $entity_id = ($this->input->get('entity_id')) ? $this->input->get('entity_id') : '';
        $order_status = ($this->input->get('order_status')) ? $this->input->get('order_status') : '';
        $to_date = ($this->input->get('to_date')) ? $this->input->get('to_date') : '';
        $from_date = ($this->input->get('from_date')) ? $this->input->get('from_date') : '';
         $data['title']= 'All'.' '.'Order'.' '.'Report';
         $data['entity_id']=$entity_id;
         $data['order_status']=$order_status;
         $data['from_date']=$from_date;
         $data['to_date']=$to_date;
         $data['report'] = $this->report_model->allOrder_pdf($entity_id,$order_status,$to_date,$from_date);
        // echo "<pre>";print_r($data['report']->result());exit();
        // print_r($data['reports']);die();
        $html = $this->load->view('backoffice/report_all_order_pdf', $data, true);
        $this->load->library('M_pdf');
        $mpdf = new mPDF('', 'Letter');
        $mpdf->SetHTMLHeader('');
        // $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
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
        $mpdf->output();
    }

    public function allRiderPDF()
    {
        $data['groups'] = $this->report_model->getAllGroups();
        $entity_id = ($this->input->get('entity_id')) ? $this->input->get('entity_id') : '';
        $order_status = ($this->input->get('order_status')) ? $this->input->get('order_status') : '';
        $to_date = ($this->input->get('to_date')) ? $this->input->get('to_date') : '';
        $from_date = ($this->input->get('from_date')) ? $this->input->get('from_date') : '';
         $data['title']= 'All'.' '.'Rider'.' '.'Report';
         $data['entity_id']=$entity_id;
         $data['order_status']=$order_status;
         $data['from_date']=$from_date;
         $data['to_date']=$to_date;
         $data['report'] = $this->report_model->allRider_pdf($entity_id,$to_date,$from_date);
        // echo "<pre>";print_r($data['report']->result());exit();
        // print_r($data['reports']);die();
        $html = $this->load->view('backoffice/report_rider_pdf', $data, true);
        $this->load->library('M_pdf');
        $mpdf = new mPDF('', 'Letter');
        $mpdf->SetHTMLHeader('');
        // $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
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
        $mpdf->output();
    }

    public function alldeliveredPDF()
    {
        $data['res'] = $this->report_model->getAllRestaurant();
        $entity_id = ($this->input->get('entity_id')) ? $this->input->get('entity_id') : '';
        $order_status = ($this->input->get('order_status')) ? $this->input->get('order_status') : '';
        $to_date = ($this->input->get('to_date')) ? $this->input->get('to_date') : '';
        $from_date = ($this->input->get('from_date')) ? $this->input->get('from_date') : '';
         $data['title']= 'All'.' '.'Order'.' '.'Report';
         $data['entity_id']=$entity_id;
         $data['order_status']=$order_status;
         $data['from_date']=$from_date;
         $data['to_date']=$to_date;
         $data['report'] = $this->report_model->alldeliveredOrder_pdf($entity_id,$order_status,$to_date,$from_date);
        // echo "<pre>";print_r($data['report']->result());exit();
        // print_r($data['reports']);die();
        $html = $this->load->view('backoffice/report_delivered_pdf', $data, true);
        $this->load->library('M_pdf');
        $mpdf = new mPDF('', 'Letter');
        $mpdf->SetHTMLHeader('');
        // $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
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
        $mpdf->output();
    }

        public function cusOrderPDF()
    {
        $data['groups'] = $this->report_model->getAllGroups();
        $entity_id = ($this->input->get('entity_id')) ? $this->input->get('entity_id') : '';
        $to_date = ($this->input->get('to_date')) ? $this->input->get('to_date') : '';
        $from_date = ($this->input->get('from_date')) ? $this->input->get('from_date') : '';
         $data['title']= 'Order'.' '.'Report(Customer Wise)';
         $data['entity_id']=$entity_id;
         $data['from_date']=$from_date;
         $data['to_date']=$to_date;
         $data['report'] = $this->report_model->cusOrder_pdf($entity_id,$to_date,$from_date);
        // echo "<pre>";print_r($data['report']->result());exit();
        // print_r($data['reports']);die();
        $html = $this->load->view('backoffice/report_customer_order_pdf', $data, true);
        $this->load->library('M_pdf');
        $mpdf = new mPDF('', 'Letter');
        $mpdf->SetHTMLHeader('');
        // $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
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
        $mpdf->output();
    }

        public function resOrderPDF()
    {
        $data['res'] = $this->report_model->getAllRestaurant();
        $entity_id = ($this->input->get('entity_id')) ? $this->input->get('entity_id') : '';
        $to_date = ($this->input->get('to_date')) ? $this->input->get('to_date') : '';
        $from_date = ($this->input->get('from_date')) ? $this->input->get('from_date') : '';
         $data['title']= 'Order'.' '.'Report(Restaurant Wise)';
         $data['entity_id']=$entity_id;
         $data['from_date']=$from_date;
         $data['to_date']=$to_date;
         $data['report'] = $this->report_model->resOrder_pdf($entity_id,$to_date,$from_date);
        // echo "<pre>";print_r($data['report']->result());exit();
        // print_r($data['reports']);die();
        $html = $this->load->view('backoffice/report_res_order_pdf', $data, true);
        $this->load->library('M_pdf');
        $mpdf = new mPDF('', 'Letter');
        $mpdf->SetHTMLHeader('');
        // $mpdf->SetHTMLFooter('<div style="padding:30px" class="endsign">Signature ____________________</div><div class="page-count" style="text-align:center;font-size:12px;">Page {PAGENO} out of {nb}</div><div class="pdf-footer-section" style="text-align:center;background-color: #000000;"><img src="http://restaura.evdpl.com/~restaura/assets/admin/img/logo.png" alt="" width="80" height="40"/></div>');
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
        $mpdf->output();
    }

    // function export_all_delivered_orders()
    // {
    //     $Tdate = $this->input->post('tdata');
    //     $Fdate = $this->input->post('fdata');
    //     $restaurent = $this->input->post('entity_id');
    //     $total_subtotal = $this->input->post('total_subtotal');
    //     $delivery_charge = $this->input->post('delivery_charge');
    //     $coupon_discount = $this->input->post('coupon_discount');
    //     $vat = $this->input->post('vat');
    //     $sd = $this->input->post('sd');
    //     $total = $this->input->post('total_rate');
    //     $to_date = date('d-M-Y H:i:s', strtotime($Tdate));
    //     $from_date = date('d-M-Y  H:i:s', strtotime($Fdate));
    //     $delivered_data = $this->report_model->exportAllDeleveredData($Fdate, $Tdate, $restaurent);
    //     $file_name = 'all_deliverd_order_' . $from_date . '_to_' . $to_date . '.csv';
    //     header("Content-Description: File Transfer");
    //     header("Content-Disposition: attachment; filename=$file_name");
    //     header("Content-Type: application/csv;");

    //     // get data 

    //     // file creation 
    //     $file = fopen('php://output', 'w');

    //     $header = array("Order Number", "Food Price", "Delivery Charge", "Discount", "VAT", "SD", "Total");
    //     fputcsv($file, $header);
    //     foreach ($delivered_data->result_array() as $key => $value) {
    //         fputcsv($file, $value);
    //     }
    //     $data = array(
    //         'total' => 'Total',
    //         "price" => $total_subtotal,
    //         "delicery_charge" => $delivery_charge,
    //         'discount' => $coupon_discount,
    //         'vat' => $vat,
    //         'sd' => $sd,
    //         'net' => $total
    //     );
    //     fputcsv($file, $data);
    //     fclose($file);
    //     exit;
    // }

    // function export_allorders()
    // {
    //     $Tdate = $this->input->post('tdata');
    //     $Fdate = $this->input->post('fdata');
    //     $restaurent = $this->input->post('entity_id');
    //     $total_subtotal = $this->input->post('total_subtotal');
    //     $delivery_charge = $this->input->post('delivery_charge');
    //     $coupon_discount = $this->input->post('coupon_discount');
    //     $vat = $this->input->post('vat');
    //     $sd = $this->input->post('sd');
    //     $total = $this->input->post('total_rate');
    //     $to_date = date('d-M-Y H:i:s', strtotime($Tdate));
    //     $from_date = date('d-M-Y  H:i:s', strtotime($Fdate));
    //     $all_data = $this->report_model->exportAllData($Fdate, $Tdate, $restaurent);
    //     $file_name = 'all_order_' . $from_date . '_to_' . $to_date . '.csv';
    //     header("Content-Description: File Transfer");
    //     header("Content-Disposition: attachment; filename=$file_name");
    //     header("Content-Type: application/csv;");

    //     // get data 

    //     // file creation 
    //     $file = fopen('php://output', 'w');

    //     $header = array("Order Number", "Food Price", "Delivery Charge", "Discount", "VAT", "SD", "Total");
    //     fputcsv($file, $header);
    //     foreach ($all_data->result_array() as $key => $value) {
    //         fputcsv($file, $value);
    //     }
    //     $data = array(
    //         'total' => 'Total',
    //         "price" => $total_subtotal,
    //         "delicery_charge" => $delivery_charge,
    //         'discount' => $coupon_discount,
    //         'vat' => $vat,
    //         'sd' => $sd,
    //         'net' => $total
    //     );
    //     fputcsv($file, $data);
    //     fclose($file);

    //     exit;
    // }
}
?>