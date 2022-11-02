<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('header');
parse_str(get_cookie('adminAuth'), $adminCook); // get Cookies


include_once('vendor/autoload.php');
require 'google-api-php/vendor/autoload.php';

if (!session_id()) {
	session_start();
}


//
?>

<?php $this->load->view('header'); ?>
<section class="content-area user-page quick-searches" >
	<div class="container-fluid">
		<div class="row"  style="height: calc(100vh - 92px)">
			<div class="col-md-6 user-form">
				<div class="content-wrapper">
<!--					<div class="logo"> <a href="--><?php //echo base_url(); ?><!--"><img src="--><?php //echo base_url(); ?><!--assets/front/images/logo.png" alt="Logo"></a>-->
<!--					</div>-->
					<h3>Change Your Password</h3>



					<?php if (!empty($this->session->flashdata('error_MSG'))) { ?>
						<div class="alert alert-danger xy">
							<?php echo $this->session->flashdata('error_MSG'); ?>
						</div>
					<?php } ?>
					<form action="<?php echo base_url() . 'home/change_password'; ?>" id="form_front_login" name="form_front_login" method="post" class="form-horizontal float-form">
						<div class="form-body">



								<div class="form-group">
									<input type="number" name="phone_number" id="phone_number" class="form-control" placeholder=" " value="<?php echo $number ?>" readonly>
									<label><?php echo $this->lang->line('phone_number') ?></label>
								</div>
								<div class="form-group mb-0">
									<input type="password" name="password" id="password" class="form-control" placeholder=" " value="">
									<label>New Password</label>
								</div>

							<br>
							<div class="form-group">
								<div id="phoneExist"></div>
							</div>
								<div class="form-group ">
								<input onchange="validation_pass()" type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder=" " value="">
								<label>Confirm Password</label>
								</div>
								<br>




							<div class="action-button">
								<button type="submit" name="submit_page" id="submit_page" value="Login" class=" common_login btn btn-primary"><?php echo $this->lang->line('save') ?></button>

								<!-- title_login -->
							</div>
						</div>
					</form>



				</div>
			</div>
			<div class="col-md-6 login-bg h-100"></div>
		</div>
	</div>
</section>
<!--/ end content-area section -->
<!-- Modal -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/front/js/scripts/admin-management-front.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/scripts/front-validations.js"></script>
<?php if ($this->session->userdata("language_slug") == 'fr') {  ?>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
<?php } ?>

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
		storageBucket: "soi71-62621.appspot.com",
		messagingSenderId: "1022686565372",
		appId: "1:1022686565372:web:cd995980b1497401b65879",
		measurementId: "G-7804JDEEXG"
	};
	// Initialize Firebase
	firebase.initializeApp(firebaseConfig);
</script>
<script>


	function validation_pass(){

		var password=$('#password').val();
		var confirm_password=$('#confirm_password').val();

		if (password != confirm_password){

			$('#phoneExist').html("Password do not match!");
			$('#phoneExist').css({
				'color': 'red',
				'font-size': '20px',
				'font-weight': 'bold'
			});
		}else{
			$(':input[name="submit_page"]').prop("disabled", false);
			$('#phoneExist').hide();

		}





	}


	function showClock(target) {
		const distance = target - new Date().getTime();
		const mins = distance < 0 ? 0: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		const secs = distance < 0 ? 0: Math.floor((distance % (1000 * 60)) / 1000);

		// Output the results
		document.getElementById("minutes").innerHTML = mins;
		document.getElementById("seconds").innerHTML = secs;
	}
	function showClock_r(target) {
		const distance = target - new Date().getTime();
		const mins = distance < 0 ? 0: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		const secs = distance < 0 ? 0: Math.floor((distance % (1000 * 60)) / 1000);

		// Output the results
		document.getElementById("r_minutes").innerHTML = mins;
		document.getElementById("r_seconds").innerHTML = secs;
	}



	// Update the count down every 1 second


	$(".mobile-icon  button").on("click", function(e) {
		$("#example-one").toggleClass("open");
		$(this).toggleClass('open');
		//	$("#example-one").fadeToggle();
		e.stopPropagation()
	});
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
	$("#form_front_forgotpass").on("submit", function(event) {
		event.preventDefault();
		jQuery.ajax({
			type : "POST",
			dataType :"json",
			url : BASEURL+'home/forgot_password',
			data : {'number_forgot':$('#number_forgot').val(), 'forgot_submit_page':$('#forgot_submit_page').val() },
			beforeSend: function(){
				$('#quotes-main-loader').show();
			},
			success: function(response) {

				$('#forgot_error').hide();
				$('#forgot_success').hide();
				$('#quotes-main-loader').hide();
				if (response) {
					if (response.forgot_error != '') {
						$('#forgot_error').html(response.forgot_error);
						$('#forgot_success').hide();
						$('#forgot_error').show();
					}
					if (response.forgot_success != '') {
						$("#forgot_success").html(response.forgot_success);
						$("#forgot_error").hide();
						$("#forgot_success").hide();
						$("#forgot_password_section").hide();
						 // $('#forgot_password_section').hide();
						$('#exampleModal').modal('show');

						forgot_verify();




					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});

	});
	function ajaxCall(number) {
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>home/updateuser",
			data: 'mobile_number=' + number,
			cache: false,
			success: function(html) {
				console.log('bla', html);
				window.location.replace("<?php echo base_url(); ?>home/login");

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


	window.onload = function() {
		render();
	};


		function render() {
			window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container',{
				'size':'invisible'
			});
			recaptchaVerifier.render();
		}


	function forgot_verify() {
		var countDownTarget = new Date().getTime() + 2 * 60 * 1000;
		//	showClock(countDownTarget);
		var x = setInterval(function() {
			showClock(countDownTarget);
			if (countDownTarget - new Date().getTime() < 0) {
				clearInterval(x);
			}
		}, 1000);

		var countrycode = "+88";
		var main_number = document.getElementById('number_forgot').value;
		var number = document.getElementById('number_forgot').value;
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
		var code = document.getElementById('verificationCode').value;
		coderesult.confirm(code).then(function(result) {
			 alert("Successfully verified");
			// $('#exampleModal').modal('hide')
			window.location.href = 'home/forgot_page/'+main_number;
			 $('#forgot-pass-modal').hide();
			$('.modal-backdrop').hide();
			$('#forgot_success').show();

			$('.xy').addClass('display-no');
			$('.verify').addClass('display-no');

			// $('#forgot-pass-modal').modal('hide');



			//var number = $('#number_forgot').val();
			//
			////alert(number)
			//ajaxCall(number);
			//// window.location.href = 'home';

			//var user = result.user;
			//console.log(user);
		}).catch(function(error) {
			alert(error.message);
		});
	}
	function forgot_verify_Resend() {

			$('#otp_time').addClass('d-none');
			$('#r_otp_time').removeClass('d-none');

		var countDownTarget = new Date().getTime() + 2 * 60 * 1000;
		showClock_r(countDownTarget);
		var x = setInterval(function() {
			showClock_r(countDownTarget);
			if (countDownTarget - new Date().getTime() < 0) {
				clearInterval(x);
			}
		}, 1000);

		var countrycode = "+88";
		var number = document.getElementById('number_forgot').value;
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
		var code = document.getElementById('verificationCode').value;
		coderesult.confirm(code).then(function(result) {
			alert("Successfully verified");
			// $('#exampleModal').modal('hide')

			 $('#forgot-pass-modal').hide();
			$('.modal-backdrop').hide();
			$('#forgot_success').show();

			$('.xy').addClass('display-no');
			$('.verify').addClass('display-no');

			// $('#forgot-pass-modal').modal('hide');



			//var number = $('#number_forgot').val();
			//
			////alert(number)
			//ajaxCall(number);
			//// window.location.href = 'home';

			//var user = result.user;
			//console.log(user);
		}).catch(function(error) {
			alert(error.message);
		});
	}
</script>
</body>

</html>
