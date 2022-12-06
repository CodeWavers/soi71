<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_us extends CI_Controller {
  
	public function __construct() {
		parent::__construct();        
		$this->load->library('form_validation');
		$this->load->model(ADMIN_URL.'/common_model');  
		$this->load->model('/home_model');    
	}

	public function send_mail_two() {



	}
	// contact us page
	public function index()
	{

		$to_email ="arman.cikatech@gmail.com";
		$from_email ='rayanahm2020@gmail.com';
		//Load email library
		$this->load->library('email');
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";
		$config['newline'] = "\r\n";
		$this->email->initialize($config);
		$this->email->from($from_email, 'Arman');
		$this->email->to('mdarmancse@gmail.com','amd55077@gmail.com');
		$this->email->subject('Send Email Codeigniter');
		$this->email->message('The email send using codeigniter library');
        $result=$this->email->send();
		//Send mail
		if($result == true) {
			echo $result;
		}else{
			echo $result;
		}
		exit();
		$type = $this->uri->segment(2);



		$data['page_title'] = $this->lang->line('contact_us'). ' | ' . $this->lang->line('site_title');

		if ($type == 1){
			$data['current_page'] = 'ContactUs1';
		}elseif ($type == 2){
			$data['current_page'] = 'ContactUs2';
		}elseif ($type == 3){
			$data['current_page'] = 'ContactUs3';
		}else{
			$data['current_page'] = 'ContactUs';
		}






		if($this->input->post('submit_page') == "Submit"){
			$this->form_validation->set_rules('name', 'Name', 'trim|required'); 
			$this->form_validation->set_rules('email', 'Email', 'trim|required'); 
	        $this->form_validation->set_rules('message', 'Message', 'trim|required');        
	        if ($this->form_validation->run())
	        {   
	        	//get System Option Data
				$this->db->select('OptionValue');
				$FromEmailID = $this->db->get_where('system_option',array('OptionSlug'=>'From_Email_Address'))->first_row();

				$this->db->select('OptionValue');
				$FromEmailName = $this->db->get_where('system_option',array('OptionSlug'=>'Email_From_Name'))->first_row();

				$this->db->select('subject,message');
                $Emaildata = $this->db->get_where('email_template',array('email_slug'=>'contact-us','status'=>1))->first_row();

				// admin email 
				$this->db->select('OptionValue');
				$AdminEmailAddress = $this->db->get_where('system_option',array('OptionSlug'=>'Admin_Email_Address'))->first_row();



                $arrayData = array('FirstName'=>trim($this->input->post('name')),'Email'=>trim($this->input->post('email')),'Message'=>trim($this->input->post('message')));
                $EmailBody = generateEmailBody($Emaildata->message,$arrayData);  
	        	
                $this->load->library('email'); 
                $config['charset'] = "utf-8";
                $config['mailtype'] = "html";
                $config['newline'] = "\r\n";      
                $this->email->initialize($config);
                $this->email->from('mdarmancse@gmail.com','Arman');
                $this->email->to('amd55077@gmail.com');
                $this->email->subject('Hello');
                $this->email->message('jdskdksdksmdks');

                $result=$this->email->send();
              //  echo $result;exit();
				if($this->email->send()){
					$data['success_msg'] = $this->lang->line('message_sent');
					$this->session->set_flashdata('contactUsMSG', $this->lang->line('message_sent'));
					redirect(base_url().'contact_us');
				}else{
                    $data['error_msg'] = $this->lang->line('message_sent');
                    $this->session->set_flashdata('contactUsMSG', $this->lang->line('message_sent'));
                    redirect(base_url().'contact_us');
                }


	        }
	    }
		$language_slug = ($this->session->userdata('language_slug')) ? $this->session->userdata('language_slug') : 'en' ;
		$data['contact_us'] = $this->common_model->getCmsPages($language_slug,'contact-us');
		$this->load->view('contact_us',$data);
	}


}
?>
