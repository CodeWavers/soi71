<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class Sslpayment extends CI_Controller { 
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('user_model');                
        $this->load->library('form_validation');
    }
  public function sslView(){
      $this->load->view('sslView');
  }
  public function sslSuccess(){
       echo '
        <html>
            <script>
                data = {
                    "message" : '.$this->input->post('tran_id').',
                    "errorCode" : null,
                }
                window.ReactNativeWebView.postMessage(JSON.stringify(data));
            </script>
        </html
      ';
  }
    public function sslFail(){
    echo '
        <html>
            <script>
                data = {
                    "message" : "Transaction Failed",
                    "errorCode" : "Error",
                }
                window.ReactNativeWebView.postMessage(JSON.stringify(data));
            </script>
        </html
      ';
  }
    public function sslCancel(){
    //   echo $this->input->post('tran_id');
      echo '
        <html>
            <script>
                data = {
                    "message" : "Cacelled",
                    "errorCode" : "Error",
                }
                window.ReactNativeWebView.postMessage(JSON.stringify(data));
            </script>
        </html
      ';
  }
  public function sslEndPoint(){
        // if you have order id generated catch the order_id key and query in your database. otherwise pass json data to postdata key of button to catch here
        $data = json_decode($this->input->raw_input_stream, true);
        $total_amount = $data['total_amount'];
        $cus_phone = $data['cus_phone'];
        $tran_id = $data['tran_id'];
        $post_data = array();
        $post_data['store_id'] = "gloriajeanscoffeesbdlive";
        $post_data['store_passwd'] = "5EBB6335C30F365259";
        $post_data['total_amount'] = $total_amount;
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $tran_id;
        $post_data['success_url'] = "http://foodaani.com/gjc/Sslpayment/sslSuccess";
        $post_data['fail_url'] = "http://foodaani.com/gjc/Sslpayment/sslFail";
        $post_data['cancel_url'] = "http://foodaani.com/gjc/Sslpayment/sslCancel";
        
        # CUSTOMER INFORMATION
        $post_data['cus_name'] = "Roton";
        $post_data['cus_email'] = "roton@gmail.com";
        $post_data['cus_add1'] = "Dhaka";
        $post_data['cus_add2'] = "Dhaka";
        $post_data['cus_city'] = "Dhaka";
        $post_data['cus_state'] = "Dhaka";
        $post_data['cus_postcode'] = "1000";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $cus_phone;
        $post_data['cus_fax'] = "";
        
        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "gloriajeanscoffeesbd";
        $post_data['ship_add1 '] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_country'] = "Bangladesh";
        $post_data['shipping_method'] = "No";
        // $post_data['emi_option'] = "0";
        
        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b '] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
        
        # EMI STATUS
        $post_data['emi_option'] = "0";
        
        # CART PARAMETERS
        $post_data['cart'] = json_encode(array(
            array("product"=>"DHK TO BRS AC A1","amount"=>"200.00"),
            array("product"=>"DHK TO BRS AC A2","amount"=>"200.00"),
            array("product"=>"DHK TO BRS AC A3","amount"=>"200.00"),
            array("product"=>"DHK TO BRS AC A4","amount"=>"200.00")
        ));
        $post_data['product_amount'] = "100";
        $post_data['product_category'] = "Food";
        $post_data['product_profile'] = "physical-goods";
        $post_data['product_name'] = "Food";
        $post_data['vat'] = "5";
        $post_data['discount_amount'] = "5";
        $post_data['convenience_fee'] = "3";
        
        
        //$post_data['allowed_bin'] = "3,4";
        //$post_data['allowed_bin'] = "470661";
        //$post_data['allowed_bin'] = "470661,376947";
        
        
        # REQUEST SEND TO SSLCOMMERZ
        // $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
        
        // $handle = curl_init();
        // curl_setopt($handle, CURLOPT_URL, $direct_api_url );
        // curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        // curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        // curl_setopt($handle, CURLOPT_POST, 1 );
        // curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        // curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
        
        
        // $content = curl_exec($handle );
        
        // $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        
        // if($code == 200 && !( curl_errno($handle))) {
        //     curl_close( $handle);
        //     $sslcommerzResponse = $content;
        // } else {
        //     curl_close( $handle);
        //     echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
        //     exit;
        // }
        
        // # PARSE THE JSON RESPONSE
        // $sslcz = json_decode($sslcommerzResponse, true );
        
        // //var_dump($sslcz); exit;
        
        // if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="") {
        // 	// this is important to show the popup, return or echo to sent json response back
        //     echo json_encode(['status' => 'success', 'data' => $sslcz['GatewayPageURL'], 'logo' => $sslcz['storeLogo'] ]);
        //   return  json_encode(['status' => 'success', 'data' => $sslcz['GatewayPageURL'], 'logo' => $sslcz['storeLogo'] ]);
        // } else {
        //     // echo json_encode(['status' => 'fail', 'data' => null, 'message' => "JSON Data parsing error!"]);
        //   echo  json_encode(['status' => 'fail', 'data' => null, 'message' =>$sslcommerzResponse ]);
        // }
        $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
        
        
        $content = curl_exec($handle );
        
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        
        if($code == 200 && !( curl_errno($handle))) {
        	curl_close( $handle);
        	$sslcommerzResponse = $content;
        } else {
        	curl_close( $handle);
        	echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
        	exit;
        }
        
        # PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true );
        // echo $sslcommerzResponse;
        
        if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="" ) {
                # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
                # echo "<script>window.location.href = '". $sslcz['GatewayPageURL'] ."';</script>";
        	echo "<meta http-equiv='refresh' content='0;url=".$sslcz['GatewayPageURL']."'>";
        	# header("Location: ". $sslcz['GatewayPageURL']);
        	exit;
        } else {
        	echo "Json parsing error";
}
                                
          }
    public function ipn_listner(){
        echo "Hello World";
    }
}