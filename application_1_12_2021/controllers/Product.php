<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
    
class Product extends CI_Controller {
    public function __construct() {
        parent::__construct();        
    }
    //faq page
    public function faq() {
        $this->load->view('faq');
    }
    //documentation page
    public function documentation() {
        $this->load->view('documentation');
    }
}
?>