<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('header');
parse_str(get_cookie('adminAuth'), $adminCook); // get Cookies


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
$google_client->setClientSecret('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');;

//Set the OAuth 2.0 Redirect URI
//$google_client->setRedirectUri('https://localhost/takeway/home/login');
$google_client->setRedirectUri(base_url() . 'home/login');

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

if (isset($_GET['state'])) {

	if (isset($_GET['code'])) {

		$access_token = $facebook_helper->getAccessToken();

		$_SESSION['access_token'] = $access_token;

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

	//$facebook_login_url = $facebook_helper->getLoginUrl('https://localhost/takeway/home/login/', $facebook_permissions);
	$facebook_login_url = $facebook_helper->getLoginUrl(base_url() . 'home/login', $facebook_permissions);
	// Render Facebook login button
	$facebook_login_url = '<a class="fb btn " style="background-color:#4267B2" onclick="fbCheck()" href="' . $facebook_login_url . '" >LogIn with Facebook</a>';
	// }
}



if (isset($_GET['scope'])) {
	//It will Attempt to exchange a code for an valid authentication token.
	$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

	//This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/

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

	$gmail_button = '<a onclick="gmailCheck()" class="google btn" style="background-color:#DB4A39" href="' . $google_client->createAuthUrl() . '">LogIn with Google + </a>';
}
?>

<?php $this->load->view('header'); ?>
<section class="content-area user-page quick-searches">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6 user-form">
				<div class="content-wrapper">
					<div class="logo"> <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/front/images/logo.png" alt="Logo"></a>
					</div>
					<h3><?php echo $this->lang->line('lets_get_started') ?></h3>

					<?php if (!empty($this->session->flashdata('error_MSG'))) { ?>
						<div class="alert alert-danger">
							<?php echo $this->session->flashdata('error_MSG'); ?>
						</div>
					<?php } ?>
					<?php if (validation_errors()) { ?>
						<div class="alert alert-danger">
							<?php echo validation_errors(); ?>
						</div>
					<?php } ?>
					<div class="container">
						<br />
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
						}
						?>

						<br></br>

						<?php
						if (isset($gmail_button)) {
							// if FB is selected than google button will not show
							if (!isset($_GET['state'])) {
								echo $gmail_button;
							}
						} else {
							echo '<img src="' . $_SESSION["google_image"] . '" class="img-responsive img-circle img-thumbnail" />';
							echo '  Continue As <strong>' . $_SESSION['google_name'] . '</strong>';
						}
						?>


					</div>

					<br></br>

					<?php if (!isset($_GET['state']) && !isset($_GET['scope'])) { ?>
						<h2 style="margin-left: 20%; color:dimgrey">____OR____</h2>
					<?php } ?>
					<br></br>

					<form action="<?php echo base_url() . 'home/login'; ?>" id="form_front_login" name="form_front_login" method="post" class="form-horizontal float-form">
						<div class="form-body">


							<!-- for fb -->
							<input type="hidden" name='fb_id' value="<?php echo ($_SESSION['fb_id']) ? $_SESSION['fb_id'] : ''; ?>" />
							<input type="hidden" name='fb_name' value="<?php echo ($_SESSION["user_name"]) ? $_SESSION["user_name"] : ''; ?>" />
							<input type="hidden" name='fb_image' value="<?php echo ($_SESSION["user_image"]) ? $_SESSION["user_image"] : ''; ?>" />

							<!-- for gmail -->

							<input type="hidden" name='gmail' value="<?php echo ($_SESSION['google_email_address']) ? $_SESSION['google_email_address'] : ''; ?>" />
							<input type="hidden" name='g_name' value="<?php echo ($_SESSION['google_name']) ? $_SESSION['google_name'] : ''; ?>" />
							<input type="hidden" name='g_image' value="<?php echo ($_SESSION["google_image"]) ? $_SESSION["google_image"] : ''; ?>" />


							<?php if (!isset($_GET['scope']) && !isset($_GET['state'])) { ?>
								<div class="form-group">
									<input type="number" name="phone_number" id="phone_number" class="form-control" placeholder=" " value="<?php echo $adminCook['usr']; ?>">
									<label><?php echo $this->lang->line('phone_number') ?></label>
								</div>
								<div class="form-group mb-0">
									<input type="password" name="password" id="password" class="form-control" placeholder=" " value="<?php echo $adminCook['hash']; ?>">
									<label><?php echo $this->lang->line('password') ?></label>
								</div>

								<div class="links text-right">
									<div class="check-box">
										<label>
											<input type="checkbox" name="rememberMe" id="rememberMe" value="1" <?php echo ($adminCook) ? "checked" : "" ?> />
											<span><?php echo $this->lang->line('remember') ?></span>
										</label>
									</div>
									<a href="" class="link" data-toggle="modal" data-target="#forgot-pass-modal"><?php echo $this->lang->line('forgot_pass') ?></a>
								</div>
							<?php } ?>

							<div class="action-button">
								<button type="submit" name="submit_page" id="submit_page" value="Login" class="btn btn-primary"><?php echo $this->lang->line('title_login') ?></button>
								<!-- <input type="submit" name="submit_page" id="submit_page" value="Login" class="btn btn-primary"> -->
								<?php if (!isset($_GET['scope']) && !isset($_GET['state'])) { ?>
									<a href="<?php echo base_url() . 'home/registration'; ?>" class="btn btn-secondary"><?php echo $this->lang->line('sign_up') ?></a>
								<?php } ?>
								<!-- title_login -->
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-6 login-bg"></div>
		</div>
	</div>
</section>
<!--/ end content-area section -->
<!-- Modal -->
<div class="modal std-modal" tabindex="-1" role="dialog" id="forgot-pass-modal">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="row align-items-center">
				<div class="col-12">
					<div class="modal-header">
						<h5 class="modal-title"><?php echo $this->lang->line('forgot_password') ?></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span> </button>
					</div>
				</div>
			</div>
			<div class="row align-items-center">
				<div class="col-md-5 col-sm-12">
					<div class="modal-body">
						<div class="text-center forgot-image"> <img src="<?php echo base_url(); ?>assets/front/images/fp-popup-image.png" alt="Forgot Password Image"> </div>
					</div>
				</div>
				<div class="col-md-7 col-sm-12">
					<div class="modal-form">
						<div class="alert alert-success display-no" id="forgot_success"></div>
						<div id="forgot_password_section">
							<h2 class="text-left">Enter Your Mobile Number</h2>
							<!-- action="<?php //echo base_url().'home/forgot_password';
							?>" -->
							<form id="form_front_forgotpass" name="form_front_forgotpass" method="post" class="form-horizontal float-form">
								<div class="form-body">
									<div class="alert alert-danger display-no" id="forgot_error"></div>
									<?php if (validation_errors()) { ?>
										<div class="alert alert-danger">
											<?php echo validation_errors(); ?>
										</div>
									<?php } ?>
									<div id="phoneExist"></div>
									<div class="form-group">
										<input type="text" name="number_forgot" id="number_forgot" class="form-control" placeholder="">
										<label>Mobile Number</label>
									</div>
									<div class="action-button">
										<button type="submit" name="forgot_submit_page" id="forgot_submit_page" value="Submit" class="btn red"><?php echo $this->lang->line('submit') ?></button>
									</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/front/js/scripts/admin-management-front.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/scripts/front-validations.js"></script>
<?php if ($this->session->userdata("language_slug") == 'fr') {  ?>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
<?php } ?>
<script>
	function checkExist(mobile_number) {
		// var entity_id = $('#entity_id').val();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>home/checkPhone",
			data: 'mobile_number=' + mobile_number,
			cache: false,
			success: function(html) {
				console.log(html);
				if (html == 0) {
					$('#phoneExist').show();
					$('#phoneExist').html("<?php echo $this->lang->line('phone_exist'); ?>");
					$('#phoneExist').css({
						'color': 'red',
						'font-size': '20px',
						'font-weight': 'bold'
					});
					$(':input[type="submit"]').prop("disabled", true);
				} else {
					$('#phoneExist').html("");
					$('#phoneExist').hide();
					$(':input[type="submit"]').prop("disabled", false);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				$('#phoneExist').show();
				$('#phoneExist').html(errorThrown);
			}
		});
	}
</script>
</body>

</html>
