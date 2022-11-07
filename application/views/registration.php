<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('header');

//include_once('facebook/graph-sdk/src/Facebook/autoload.php');
include_once('vendor/autoload.php');
require 'google-api-php/vendor/autoload.php';

if (!session_id()) {
	session_start();
}


//for google
//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('1022686565372-rqu3c2fqvkm9fdp65hrmpiuc1cb7o9i3.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI
// $google_client->setRedirectUri('https://localhost/takeway/home/registration');
$google_client->setRedirectUri(base_url() . 'home/registration');

//
$google_client->addScope('email');

$google_client->addScope('profile');


$facebook = new Facebook\Facebook(array(
		'app_id' => '669076557593120', // Replace with your app id
		'app_secret' => '44e8025774f1027ed82f9351e3789d02',  // Replace with your app secret
		'default_graph_version' => 'v3.2',
));

$facebook_helper = $facebook->getRedirectLoginHelper();

//
$_SESSION['google_email_address'] = '';

if (isset($_GET['state'])) {

	if (isset($_GET['code'])) {
		// if (isset($_SESSION['access_token'])) {
		//     $access_token = $_SESSION['access_token'];
		// } else {
		$access_token = $facebook_helper->getAccessToken();

		$_SESSION['access_token'] = $access_token;

		//$facebook->setDefaultAccessToken($_SESSION['access_token']);
		// }

		$_SESSION['user_id'] = '';
		$_SESSION['user_name'] = '';
		$_SESSION['user_email_address'] = '';
		$_SESSION['user_image'] = '';

		$graph_response = $facebook->get("/me?fields=name,email", $access_token);

		$facebook_user_info = $graph_response->getGraphUser();

		if (!empty($facebook_user_info['id'])) {
			$_SESSION['fb_id'] = $facebook_user_info['id'];
			$_SESSION['user_image'] = 'https://graph.facebook.com/' . $facebook_user_info['id'] . '/picture';
		}

		if (!empty($facebook_user_info['name'])) {
			$_SESSION['user_name'] = $facebook_user_info['name'];
		}

		if (!empty($facebook_user_info['email'])) {
			$_SESSION['user_email_address'] = $facebook_user_info['email'];
		}
	}
} else {
	// Get login url
	$facebook_permissions = ['email']; // Optional permissions

	// $facebook_login_url = $facebook_helper->getLoginUrl('https://localhost/takeway/home/registration/', $facebook_permissions);
	$facebook_login_url = $facebook_helper->getLoginUrl(base_url() . 'home/registration', $facebook_permissions);

	// Render Facebook login button
	// $facebook_login_url = '<a href="' . $facebook_login_url . '"><img class="facebook" src="' . base_url() . 'assets/front/images/facebook.png" alt="Login with facebook" height="40px" width=84%></a>';
	$facebook_login_url = '<a class="common_with_sign fb btn" style="background-color:#4267B2; border:none !important"  onclick="fbCheck()" href="' . $facebook_login_url . '" ><nobr>Sign Up with Facebook</nobr></a>';
	// }
}



if (isset($_GET['scope'])) {
	//It will Attempt to exchange a code for an valid authentication token.
	$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

	//This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
	//if (!isset($token['error'])) {
	//Set the access token used for requests
	$google_client->setAccessToken($token['access_token']);


	//Store "access_token" value in $_SESSION variable for future use.
	$_SESSION['google_access_token'] = $token['access_token'];

	//Create Object of Google Service OAuth 2 class
	$google_service = new Google_Service_Oauth2($google_client);

	//Get user profile data from google
	$data = $google_service->userinfo->get();

	//Below you can find Get profile data and store into $_SESSION variable
	if (!empty($data['given_name'])) {
		$_SESSION['google_name'] = $data['given_name'] . ' ' . $data['family_name'];
	}

	if (!empty($data['email'])) {
		$_SESSION['google_email_address'] = $data['email'];
	}

	if (!empty($data['picture'])) {
		$_SESSION['google_image'] = $data['picture'];
	}
} else {

	// if ($fb_check == 1) {

	$gmail_button = '<a onclick="gmailCheck()" class="common_with_sign google btn" style="background-color:#DB4A39; border:none !important"  href="' . $google_client->createAuthUrl() . '"><nobr>Sign Up with Google</nobr></a>';
}
?>

<section class="content-area user-page quick-searches">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 user-form">
				<div class="content-wrapper">
<!--					<div class="logo">-->
<!--						<a href="--><?php //echo base_url(); ?><!--"><img src="--><?php //echo base_url(); ?><!--assets/front/images/logo.png" alt="Logo"></a>-->
<!--					</div>-->
<!--					<h3>--><?php //echo $this->lang->line('welcome_to') ?><!-- --><?php //echo $this->lang->line('site_title'); ?><!--!</h3>-->
					<div class="container" style="display: contents">
						<br />
						<!-- <a href="#" onclick="hideContainer()" class="google btn"><i class="fa fa-google fa-fw">
							</i> Sign Up With Number
						</a> -->

						<!-- <a href="#" class="google btn"><i class="fa fa-google fa-fw">
							</i> Sign Up with Google </a>
						<br></br> -->
						<!-- <div class="panel panel-default"> -->
						<?php
						//session_destroy();
						if (isset($facebook_login_url)) {

							// if google is selected than not to show fb button
							if (!isset($_GET['scope'])) {
								echo $facebook_login_url;
							}
						} else {
							echo '<img src="' . $_SESSION["user_image"] . '" class="img-responsive img-circle img-thumbnail" />';
							echo '  Continue As <strong>' . $_SESSION["user_name"] . '</strong>';
							// echo '<h6><b>Name :</b> ' . $_SESSION['user_name'] . '</h6>';
							// echo '<h6><b>Your Facebook </b> ' . $_SESSION['user_email_address'] . '</h6>';
						}
						?>

						<br></br>

						<?php
						if (isset($gmail_button)) {
							// if FB is selected than not to show google button
							if (!isset($_GET['state'])) {
								echo $gmail_button;
							}
						} else {
							echo '<img src="' . $_SESSION["google_image"] . '" class="img-responsive img-circle img-thumbnail" />';
							echo '  Continue As <strong>' . $_SESSION['google_name'] . '</strong>';
							// echo '<h5><b>Name :</b> ' . $_SESSION['google_name'] . ' ' . $_SESSION['user_last_name'] . '</h5>';
							// echo '<h5><b>Email :</b> ' . $_SESSION['google_email_address'] . '</h5>';

							// echo '<h3><a href="logout.php">Logout</h3></div>';
						}
						?>


					</div>

					<br>
					<div class="form-group">
						<div id="userExist"></div>
					</div>
					<?php if (!isset($_GET['state']) && !isset($_GET['scope'])) { ?>
						<h5 class="" style=" color:dimgrey;margin-left:27% ">OR</h5>
					<?php } ?>
					<?php if (!empty($this->session->flashdata('error_MSG'))) { ?>
						<div class="alert alert-danger">
							<?php echo $this->session->flashdata('error_MSG'); ?>
						</div>
					<?php } ?>
					<?php if (!empty($this->session->flashdata('success_MSG'))) { ?>
						<div class="alert alert-success">
							<?php echo $this->session->flashdata('success_MSG'); ?>
						</div>
					<?php } ?>
					<?php if (!empty($success)) { ?>
						<div class="alert alert-success"><?php echo $success; ?></div>
					<?php } ?>
					<?php if (!empty($error)) { ?>
						<div class="alert alert-danger"><?php echo $error; ?></div>
					<?php } ?>
					<?php if (validation_errors()) { ?>
						<div class="alert alert-danger">
							<?php echo validation_errors(); ?>
						</div>
					<?php } ?>

					<form action="" id="form_front_registration" name="form_front_registration" method="post" class="form-horizontal float-form ">
						<div class="form-body">




							<div class="form-group">
								<div id="phoneExist"></div>
							</div>
							<div class="form-group" id="name_container">
								<input type="text"  onchange="checking()"  onkeypress="checking()" onkeyup="checking()"  name="name" id="name" class="form-control" placeholder="" value="<?php echo ($_SESSION["user_name"]) ? $_SESSION["user_name"] : '' ?>">
								<label><?php echo $this->lang->line('name') ?></label>
							</div>
							<input type="hidden" name='fb_id' id='fb_id' onchange="checkExistProviderId(this.value)" value="<?php echo ($_SESSION['fb_id']) ? $_SESSION['fb_id'] : ''; ?>" />
							<input type="hidden" name='fb_name' value="<?php echo ($_SESSION["user_name"]) ? $_SESSION["user_name"] : ''; ?>" />
							<input type="hidden" name='fb_image' value="<?php echo ($_SESSION["user_image"]) ? $_SESSION["user_image"] : ''; ?>" />

							<!-- for gmail -->

							<input type="hidden" name='gmail' id='gmail_id' onchange="checkExistProviderId(this.value)" value="<?php echo ($_SESSION['google_email_address']) ? $_SESSION['google_email_address'] : ''; ?>" />
							<input type="hidden" name='g_name' value="<?php echo ($_SESSION['google_name']) ? $_SESSION['google_name'] : ''; ?>" />
							<input type="hidden" name='g_image' value="<?php echo ($_SESSION["google_image"]) ? $_SESSION["google_image"] : ''; ?>" />

							<input type="hidden" id="phnE" name='phnE' value="" />


							<!--<div class="form-group">-->
							<!--    <input type="email" name="email" id="email" class="form-control" placeholder=" " >-->
							<!--    <label><?php echo $this->lang->line('email') ?></label>-->
							<!--</div>-->
							<div class="form-group" id="number_container">
								<input type="hidden"  id="verify" class="form-control" placeholder=" " value="">
								<input type="number"  onchange="checkExistNum(this.value)" name="phone_number" id="number" class="form-control" placeholder=" ">
								<label><?php echo $this->lang->line('phone_number') ?></label>
							</div>

							<?php if (!isset($_GET['state']) && !isset($_GET['scope'])) { ?>
								<div class="form-group">
									<input type="password" onchange="checking()"  onkeypress="checking()" onkeyup="checking()" name="password" id="password" class="form-control" placeholder=" " onkeyup="checkAllFields();">
									<label><?php echo $this->lang->line('password') ?></label>
								</div>

							<?php } ?>
							<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel"></h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">


												<div class="form-body">

													<h1>Enter Verification code</h1>

													<div class="form-group">
														<input type="text" id="verificationCode" class="form-control" placeholder="">
														<label><?php echo $this->lang->line('otp') ?></label>
													</div>
													<div id="recaptcha-container"></div>

													<div class="action-button">
														<button type="submit" name ="submit_page" id="submit_page"  class="btn btn-primary"><?php echo "Verify Code" ?></button>

													</div>

												</div>

										</div>
										<div class="modal-footer">
											<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary">Save changes</button> -->
										</div>
									</div>
								</div>
							</div>

							<div id="recaptcha-container"></div>
							<div class="action-button" style="margin-top:10px;">
								<a href="<?php echo base_url() . 'home/login'; ?>" class="btn btn-secondary"><?php echo $this->lang->line('title_login') ?></a>
								<!-- <button type="submit" name="submit_page" id="submit_page" value="Register" onclick="phoneAuth();" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> <?php echo $this->lang->line('sign_up') ?></button> -->
								<button type="button" name="" id="" value="Register" onclick="checkFields();" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"  > <?php echo $this->lang->line('sign_up') ?></button>

							</div>
							<!-- <button type="button" onclick="phoneAuth();">SendCode</button> -->

						</div>

					</form>
				</div>
			</div>
			<div class="col-md-6 login-bg"></div>
		</div>
	</div>
</section>

<!-- modal -->
<!-- Modal -->
<!-- end modal -->

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>



<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#config-web-app -->

<script>
	// Your web app's Firebase configuration
	// For Firebase JS SDK v7.20.0 and later, measurementId is optional
	var firebaseConfig = {
		apiKey: "AIzaSyDcXe6AacheUcFHeV8jtanJT21nyS9e3kM",
		authDomain: "soi71-62621.firebaseapp.com",
		projectId: "soi71-62621",
		databaseURL: "https://soi71-62621.firebaseio.com",
		storageBucket: "soi71-62621.appspot.com",
		messagingSenderId: "1022686565372",
		appId: "1:1022686565372:web:cd995980b1497401b65879",
		measurementId: "G-7804JDEEXG"
	};
	// Initialize Firebase
	firebase.initializeApp(firebaseConfig);
</script>

<script>

	function codeverify() {

	}

	$('#form_front_registration').submit(function(e) {
		e.preventDefault();

		dataString = $("#form_front_registration").serialize();

		var code = document.getElementById('verificationCode').value;
		coderesult.confirm(code).then(function(result) {

			//	console.log(result)
			alert("Successfully verified");
			$('#verify').val('verified');

			var number = $('#number').val();
			ajaxCall(number);
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>home/registration",
				data: dataString,

				success: function(data) {
					window.location.href = "<?php echo base_url(); ?>home/login";

					console.log(data)
				}

			});
			// window.location.href = 'home';
			var user = result.user;
			//console.log(user);
		}).catch(function(error) {
			alert(error.message);
		});
	//	codeverify();

		// var verify=$('#verify').val();
		// if (verify === 'verified'){

		// }


		return false;
	});
</script>


<script>
	var fb_id = $('#fb_id').val();
	var gmail_id = $('#gmail_id').val();
	var provider_id = fb_id ? fb_id : gmail_id ? gmail_id : '';

	console.log('Gmail: ', gmail_id);
	console.log('Fb: ', fb_id);
	console.log('Provider Id: ', provider_id);

	if (provider_id) {
		checkExistProviderId(provider_id);
	}

	window.onload = function() {
		render();
	};

	function render() {
		window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container',{
			'size':'invisible'
		});
		recaptchaVerifier.render();
	}
	//Check if all fields are filled up
	function checkAllFields() {
		var name = $('#name').val();
		var number = $('#number').val();
		var pass = $('#password').val();

		if (name == "" || number == "" || pass == "") {

			$(':input[name="submit_page"]').prop("disabled", true);
			$('#phoneExist').show();
			$('#phoneExist').html("Please fill up all the fields.");
			$('#phoneExist').css({
				'color': 'red',
				'font-size': '20px',
				'font-weight': 'bold'
			});
		} else {
			$(':input[name="submit_page"]').prop("disabled", false);
			$('#phoneExist').hide();
		}
	}

	function checkFields() {
		var name = $('#name').val();
		var number = $('#number').val();
		var pass = $('#password').val();
		var phnE=$('#phnE').val();
		// if (phnE > 0){
		// 	$(':input[type="submit"]').prop("disabled", true);
		// }else{
		// 	$(':input[type="submit"]').prop("disabled", false);
		// }
		if (name == "" || number == "" || pass == "") {

			$(':input[name="submit_page"]').prop("disabled", true);
			$('#phoneExist').show();
			$('#phoneExist').html("Please fill up all the fields.");
			$('#phoneExist').css({
				'color': 'red',
				'font-size': '20px',
				'font-weight': 'bold'
			});
		} else {
			$(':input[name="submit_page"]').prop("disabled", false);
			$('#phoneExist').hide();
			phoneAuth();
		}
	}


	function phoneAuth() {
		//get the number
		var countrycode = "+88";
		var number = document.getElementById('number').value;
		var number = countrycode.concat(number);
		//phone number authentication function of firebase
		//it takes two parameter first one is number,,,second one is recaptcha
		firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function(confirmationResult) {
			//s is in lowercase
			window.confirmationResult = confirmationResult;
			coderesult = confirmationResult;
			console.log(coderesult);
		}).catch(function(error) {
			alert(error.message);
		});
	}


	function ajaxCall(number) {
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>home/updateuser",
			data: 'mobile_number=' + number,
			cache: false,
			success: function(html) {
				console.log('bla', html);
				window.location("<?php echo base_url(); ?>home/login");

				//   if(html > 0){
				//     $('#phoneExist').show();
				//     $('#phoneExist').html("<?php echo $this->lang->line('phone_exist'); ?>");
				//     $(':input[type="submit"]').prop("disabled",true);
				//   } else {
				//     $('#phoneExist').html("");
				//     $('#phoneExist').hide();
				//     $(':input[type="submit"]').prop("disabled",false);
				//   }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				//   $('#phoneExist').show();
				//   $('#phoneExist').html(errorThrown);
				alert(errorThrown);
			}
		});
	}

	function hideContainer() {
		$('#form_front_registration').css('display', 'block');
		$('.container').css('display', 'none');
	}

	function checking() {

		var mobile_number=$('#number').val();


		checkExistNum(mobile_number);

	}


	function checkExistNum(mobile_number) {
		// var entity_id = $('#entity_id').val();

		if (mobile_number != '') {
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>home/checkPhone",
				data: 'mobile_number=' + mobile_number,
				cache: false,
				success: function (html) {
					console.log(html);
					if (html > 0) {
						$('#phnE').val(html);
						$(':input[type="submit"]').prop("disabled", true);
						$('#phoneExist').show();
						$('#phoneExist').html("<?php echo $this->lang->line('phone_exist'); ?>");
						$('#phoneExist').css({
							'color': 'red',
							'font-size': '20px',
							'font-weight': 'bold'
						});

					} else {
						$('#phoneExist').html("");
						$('#phoneExist').hide();
						$(':input[type="submit"]').prop("disabled", false);
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
					$('#phoneExist').show();
					$('#phoneExist').html(errorThrown);
				}
			});
		}
	}

	function checkExistProviderId(login_provide_id) {
		// var entity_id = $('#entity_id').val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>home/deleteMatchingNonActiveUsers",
			data: 'login_provider_id=' + login_provide_id,
			cache: false,
			success: function(response) {
				console.log("Existed Users:", response);
				if (response) {

					console.log("User Exists:", response);

					$(':input[name="submit_page"]').prop("disabled", true);
					$('#userExist').show();
					$('#userExist').html("This user already exists. Please login.<br>");
					$('#userExist').css({
						'color': 'red',
						'font-size': '20px',
						'font-weight': 'bold'
					});

					$('#name_container').hide();
					$('#number_container').hide();
					$('#recaptcha-container').hide();
				} else {
					console.log("No user:", response);

					$(':input[name="submit_page"]').prop("disabled", false);
					$('#userExist').hide();
				}
			}
		});
	}
</script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/NumberAuthentication.js"></script> -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/front/js/scripts/admin-management-front.js"></script>
<?php if ($this->session->userdata("language_slug") == 'fr') {  ?>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
<?php } ?>
</body>

</html>
