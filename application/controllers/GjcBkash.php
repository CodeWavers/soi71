 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
class GjcBkash extends CI_Controller { 
	function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('user_model');                
        $this->load->library('form_validation');
    }
 
 public function bKashGJC(){
     $strJsonFileContents = file_get_contents("http://foodaani.com/bKashConfig.json");
	 $array = json_decode($strJsonFileContents, true);
     $data = json_decode($this->input->raw_input_stream, true);
     $total_amount = $data['total_amount'];
     echo ' <html>
        <head> 
          <meta name="viewport" content="width=device-width" ,="" initial-scale="2.0/">
          <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> 
          <script src="https://scripts.sandbox.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout-sandbox.js"></script> 
        </head>
        <body>
          <button id="bKash_button" disabled="disabled" style="opacity:0;">Pay With Bkash</button>
        </body>
        <script>
        let grantTokenUrl = "http://foodaani.com/gjc/GjcBkash/bkash_Get_Token";
        let createCheckoutUrl = "http://foodaani.com/gjc/GjcBkash/createPayment";
        let executeCheckoutUrl = "http://foodaani.com/gjc/GjcBkash/executePayment";
        
        $(document).ready(function () {
            var xhr = new XMLHttpRequest();
            
            xhr.addEventListener("readystatechange", function () {
              if (this.readyState === this.DONE) {
                grantToken = JSON.parse(this.responseText).id_token;
                initBkash();
              }
            });
            
            xhr.open("POST", grantTokenUrl);
            xhr.setRequestHeader("accept", "application/json");
            xhr.setRequestHeader("content-type", "application/json");
            xhr.send();

        });
        function initBkash() {
            // alert("HELLO");
            bKash.init({
              paymentMode: "checkout", // Performs a single checkout.
              paymentRequest: {"amount": '.$total_amount.', "intent": "sale", "currency":"BDT","merchantInvoiceNumber": "MI015454","token" : grantToken},
        
              createRequest: function (request) {
                $.ajax({
                  url: createCheckoutUrl,
                  type: "POST",
                  data: JSON.stringify(request),
                  success: function (data) { 
                    data = JSON.parse(data)
                    if (data && data.paymentID != null) {
                      paymentID = data.paymentID;
                      bKash.create().onSuccess(data);
                    } 
                    else {
                      bKash.create().onError(); // Run clean up code
                      alert(data.errorMessage + " Tag should be 2 digit, Length should be 2 digit, Value should be number of character mention in Length, ex. MI041234 , supported tags are MI, MW, RF");
                    }
        
                  },
                  error: function (e) {
                    bKash.create().onError(); // Run clean up code
                    // alert(e);
                    data1 = {
                      message: "Something went wrong",
                      errorCode:"Error",
                    }
                    window.ReactNativeWebView.postMessage(JSON.stringify(data1));
                    bKash.execute().onError();//run clean up code
                  }
                });
              },
              executeRequestOnAuthorization: function () {
                $.ajax({
                  url: executeCheckoutUrl,
                  type: "POST",
                  "contentType": "application/json",
                  data: JSON.stringify({"paymentID": paymentID}),
                  success: function (data) {
                    data = JSON.parse(data)
                    if (data && data.paymentID != null) {
                      // On success, perform your desired action
                      // alert("[SUCCESS] data : " + JSON.stringify(data));
                      window.ReactNativeWebView.postMessage(JSON.stringify(data));
                      // window.location.href = "/success_page.html";
        
                    } else {
                      alert("[ERROR] data : " + JSON.stringify(data));
                      data1 = {
                        message: data,
                        errorCode:"Error",
                      }
                      window.ReactNativeWebView.postMessage(JSON.stringify(data1));
                      bKash.execute().onError();//run clean up code
                    }
        
                  },
                  error: function () {
                    // alert("An alert has occurred during execute");
                    data = {
                      message:"Error while processing transaction",
                      errorCode:"Error",
                    }
                    window.ReactNativeWebView.postMessage(JSON.stringify(data));
                    bKash.execute().onError(); // Run clean up code
                  }
                });
              },
              onClose: function () {
                // alert("User has clicked the close button");
                data = {
                  message: "Cancelled",
                  errorCode:"Error",
                }
                window.ReactNativeWebView.postMessage(JSON.stringify(data));
              }
            });
        
            // $("#bKash_button").removeAttr("disabled");
            $("#bKash_button").click();
        }
        
        </script>
</html>   
';
    
 }
 public function bkash_Get_Token(){
    // session_start();
	$strJsonFileContents = file_get_contents("http://foodaani.com/bKashConfig.json");
	$array = json_decode($strJsonFileContents, true);
	
	$post_token=array(
        'app_key'=>$array["app_key"],                                              
		'app_secret'=>$array["app_secret"]                  
	);	
    
    $url=curl_init($array["tokenURL"]);
	$proxy = $array["proxy"];
	$posttoken=json_encode($post_token);
	$header=array(
		'Content-Type:application/json',
		'password:'.$array["password"],                                                               
        'username:'.$array["username"]                                                           
    );				
    
    curl_setopt($url,CURLOPT_HTTPHEADER, $header);
	curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($url,CURLOPT_POSTFIELDS, $posttoken);
	curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($url, CURLOPT_PROXY, $proxy);
	$resultdata=curl_exec($url);
	curl_close($url);
// 	return json_encode($resultdata);
	echo $resultdata;
// 	return json_encode($resultdata,true);
}
public function createPayment(){
    $strJsonFileContents = file_get_contents("http://foodaani.com/bKashConfig.json");
    $array = json_decode($strJsonFileContents, true);
    $data = json_decode($this->input->raw_input_stream, true);
    $amount = $data['amount'];
    $token = $data["token"];
    $invoice = "MI46f647h7"; // must be unique
    $intent = $data["intent"];
    $proxy = $array["proxy"];
    $createpaybody=array('amount'=>$amount, 'currency'=>'BDT', 'merchantInvoiceNumber'=>$invoice,'intent'=>$intent);   
        $url = curl_init($array["createURL"]);
    
        $createpaybodyx = json_encode($createpaybody);
    
        $header=array(
            'Content-Type:application/json',
            'authorization:'.$token,
            'x-app-key:'.$array["app_key"]
        );
        echo $_GET["token"];
        curl_setopt($url,CURLOPT_HTTPHEADER, $header);
    	curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
    	curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($url,CURLOPT_POSTFIELDS, $createpaybodyx);
        curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($url, CURLOPT_PROXY, $proxy);
        
        $resultdata = curl_exec($url);
        curl_close($url);
        echo $resultdata;
    
}
public function executePayment(){
    $strJsonFileContents = file_get_contents("http://foodaani.com/bKashConfig.json");
    $array = json_decode($strJsonFileContents, true);
    $data = json_decode($this->input->raw_input_stream, true);
    $paymentID = $data["paymentID"];
    $proxy = $array["proxy"];
    
    $url = curl_init($array["executeURL"].$paymentID);
    
    $header=array(
        'Content-Type:application/json',
        'authorization:'.$array["token"],
        'x-app-key:'.$array["app_key"]              
    );	
        
    curl_setopt($url,CURLOPT_HTTPHEADER, $header);
    curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
    //curl_setopt($url, CURLOPT_PROXY, $proxy);
    
    $resultdatax=curl_exec($url);
    curl_close($url);
    echo $resultdatax;  
}
}