<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>

<!-- Order Confirmation -->
<div class="modal modal-main order-confirmation" id="order-confirmation">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $this->lang->line('order_confirmation') ?></h4>
				<button type="button" class="close" data-dismiss="modal" onclick="document.location.href='<?php echo base_url(); ?>restaurant';"><i class="iicon-icon-23"></i></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="availability-popup">
					<div class="availability-images">
						<img src="<?php echo base_url(); ?>assets/front/images/order-confirmation.svg" alt="Booking availability">
					</div>
					<h2><?php echo $this->lang->line('thankyou_for_order') ?></h2>
					<p><?php echo $this->lang->line('order_placed') ?></p>
					<span id="track_order"><a href="<?php echo base_url(); ?>myprofile" class="btn"><?php echo $this->lang->line("track_order"); ?></a></span>
				</div>
			</div>
		</div>
	</div>
</div>
<section class="inner-pages-section cart-section">

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<!-- Modal Header -->
				<!-- <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div> -->
				<div class="modal-body">
					<form action="" class="form-horizontal float-form">
						<div class="form-body verify">

							<h1>Enter OTP</h1>

							<div class="form-group">
								<input type="text" id="verificationCode" class="form-control" placeholder="">
								<label><?php echo $this->lang->line('otp') ?></label>
							</div>
							<b>
								<p id="otp_time">
									The OTP will expire in
									<span id="minutes" class="expire text-dark "></span>m: <span id="seconds" class="expire text-dark "></span>s

								</p>
							</b>
							<b>
								<p id="r_otp_time" class="d-none">
									The OTP will expire in
									<span id="r_minutes" class="expire text-dark "></span>m: <span id="r_seconds" class="expire text-dark "></span>s

								</p>
							</b>
							<div id="recaptcha-container"></div>
							<input type="hidden" name="count_number" id="count_number" class="form-control" placeholder="" value="0">
							<div class="action-button">
								<button type="button" onclick="checkout_forgot_verify();" class="btn btn-primary"><?php echo "Verify Code" ?></button>
								<button type="button" onclick="forgot_verify_Resend();" class="btn btn-warning">Resend Code</button>

							</div>
						</div>
					</form>
				</div>

			</div>
		</div>
	</div>
	<div class="container" id="ajax_checkout">
		<div class="row">
			<div class="col-lg-12">
				<div class="heading-title">
					<h2><?php echo $this->lang->line('checkout') ?></h2>
				</div>
			</div>
		</div>
		<div class="row cart-row">
			<div class="col-lg-8">
				<div class="checkout-account">
					<div class="account-title">
						<img src="<?php echo base_url(); ?>assets/front/images/boy.svg">
						<h3><?php echo $this->lang->line('account') ?></h3>
					</div>
					<?php if ($this->session->userdata('is_user_login') != 1) { ?>
						<div class="account-tag-line">
							<p><?php echo $this->lang->line('acc_tag_line') ?></p>
						</div>
						<div id="login_form">
							<form id="form_front_login_checkout" name="form_front_login_checkout" method="post" class="form-horizontal float-form">
								<div class="form-body">
									<?php if (!empty($this->session->flashdata('success_MSG'))) { ?>
										<div class="alert alert-success xy">
											<?php echo $this->session->flashdata('success_MSG'); ?>
										</div>
									<?php } ?>
									<?php if (!empty($this->session->flashdata('error_MSG'))) { ?>
										<div class="alert alert-danger xy">
											<?php echo $this->session->flashdata('error_MSG'); ?>
										</div>
									<?php } ?>
									<?php if (!empty($loginError)) { ?>
										<div class="alert alert-danger xy">
											<?php echo $loginError; ?>
										</div>
									<?php } ?>
									<?php if (validation_errors()) { ?>
										<div class="alert alert-danger login-validations xy">
											<?php echo validation_errors(); ?>
										</div>
									<?php } ?>
									<div class="alert alert-success display-no" id="forgot_success"></div>
									<div class="login-details">
										<div class="form-group">
											<input type="number" name="login_phone_number" id="login_phone_number" class="form-control" placeholder=" ">
											<label><?php echo $this->lang->line('phone_number') ?></label>
										</div>
										<!-- <div class="form-group mb-0">
											<input type="password" name="login_password" id="login_password" class="form-control" placeholder=" ">
											<label><?php echo $this->lang->line('password') ?></label>
											<a href="" class="link" data-toggle="modal" data-target="#forgot-pass-modal"><?php echo $this->lang->line('forgot_pass') ?></a>

										</div> -->
									</div>
									<div class="action-button account-btn">
										<button type="submit" name="submit_login_page" id="submit_login_page" value="Login" class="btn btn-primary"><?php echo "Submit" ?></button>

										<!-- <a href="<?php echo base_url() . 'home/registration'; ?>" class="btn btn-secondary"><?php echo $this->lang->line('sign_up') ?></a> -->

									</div>
								</div>
							</form>
						</div>
					<?php } else { ?>
						<div class="login-complete">
							<div class="login-img-main">
								<div class="user-img">
									<img src="<?php echo default_user_img; ?>">
								</div>
							</div>
							<div class="logged-in">
								<strong><?php echo $this->lang->line('logged_in') ?></strong>
								<p><?php echo $this->session->userdata('userFirstname') . ' ' . $this->session->userdata('userLastname'); ?></p>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="account-accordion">
					<div class="accordion" id="accordionExampleOne">
						<div class="card" id="ajax_your_items">
							<div class="card-header" id="headingOne">
								<div class="card-header-title" data-toggle="collapse" data-target="#collapseOne">
									<img src="<?php echo base_url(); ?>assets/front/images/picnic-basket.svg">
									<h3><?php echo $this->lang->line('your_items') ?></h3>
								</div>
							</div>
							<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExampleOne">
								<div class="card-body">
									<div class="cart-content-table">
										<table>
											<tbody>
												<?php if (!empty($cart_details['cart_items'])) {
													foreach ($cart_details['cart_items'] as $cart_key => $value) { ?>
														<tr>
															<td class="item-img-main">
																<div>
																	<i class="iicon-icon-15 <?php echo ($value['is_veg'] == 1) ? 'veg' : 'non-veg'; ?>"></i>
																</div>
															</td>
															<td class="item-name">
																<?php echo $value['name']; ?>
																<ul class="ul-disc">
																	<?php if (!empty($value['addons_category_list'])) {
																		foreach ($value['addons_category_list'] as $key => $cat_value) { ?>
																			<li>
																				<h6><?php echo $cat_value['addons_category']; ?></h6>
																			</li>
																			<ul class="ul-cir">
																				<?php if (!empty($cat_value['addons_list'])) {
																					foreach ($cat_value['addons_list'] as $key => $add_value) { ?>
																						<li><?php echo $add_value['add_ons_name']; ?><?php echo $currency_symbol->currency_symbol; ?><?php echo $add_value['add_ons_price']; ?></li>
																				<?php }
																				} ?>
																			</ul>
																	<?php }
																	} ?>
																</ul>
															</td>
															<td>
																<strong><?php echo $currency_symbol->currency_symbol; ?><?php echo $value['totalPrice']; ?></strong>
															</td>
															<td>
																<div class="add-cart-item">
																	<div class="number">
																		<input type="hidden" name="total_cart_items" id="total_cart_items" value="<?php echo count($cart_details['cart_items']); ?>">
																		<span class="minus" id="minusQuantity" onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'minus',<?php echo $cart_key; ?>)"><i class="iicon-icon-22"></i></span>
																		<input type="text" name="item_count_check" id="item_count_check" value="<?php echo $value['quantity']; ?>" class="pointer-none" />
																		<span class="plus" id="plusQuantity" onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'plus',<?php echo $cart_key; ?>)"><i class="iicon-icon-21"></i></span>
																	</div>
																</div>
															</td>
															<td class="close-btn-cart">
																<button class="close-btn" onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'remove',<?php echo $cart_key; ?>)">
																	<i class="iicon-icon-38"></i></button>
															</td>
														</tr>
													<?php }
												} else { ?>
													<div class="cart-empty text-center">
														<img src="<?php echo base_url(); ?>assets/front/images/empty-cart.png">
														<h6><?php echo $this->lang->line("cart_empty"); ?>
															<br> <?php $this->lang->line("add_some_dishes"); ?>
														</h6>
													</div>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="order_mode_method">
						<form id="checkout_form" name="checkout_form" method="post" class="form-horizontal float-form">
							<input type="hidden" name="subtotal" id="subtotal" value="<?php echo $cart_details['cart_total_price']; ?>">
							<input type="hidden" name="service_charge" id="service_charge" value="<?php echo "0" ?>">
							<input type="hidden" id="vat" name="vat" value="<?php echo $cart_details['total_vat']; ?>">

							<input type="hidden" name="sd" id="sd" value="<?php echo $cart_details['sd']; ?>">

							<input type="hidden" name="dc" id="delivery_charges_val" value="">
							<?php if ($this->session->userdata('is_user_login') == 1 && !empty($cart_details['cart_items'])) { ?>
								<div class="accordion" id="accordionExampleTwo">
									<div class="card" id="order_mode_content">
										<div class="card-header" id="headingTwo">
											<div class="card-header-title" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true">
												<img src="<?php echo base_url(); ?>assets/front/images/order-mode.svg">
												<h3><?php echo $this->lang->line('order_mode') ?></h3>
											</div>
										</div>
										<div id="collapseTwo" class="collapse in show" aria-labelledby="headingTwo" data-parent="#accordionExampleTwo">
											<div class="card-body">
												<div class="choose-order-mode">
													<div class="choose-order-title">
														<h6><?php echo $this->lang->line('choose_order_mode') ?></h6>
													</div>

													<!--													--><?php //echo print_r($cart_details);exit();
																												?>
													<div class="order-mode">
														<div class="card">
															<div class="radio-btn-list">
																<label>


																	<input type="radio" name="choose_order" id="pickup" value="pickup" required onclick="showPickup(<?php echo $cart_details['cart_total_price']; ?>);">
																	<span><?php echo $this->lang->line('pickup') ?></span>
																</label>
																<!-- </div>
																<div class="radio-btn-list"> -->
																<label>
																	<input type="radio" name="choose_order" id="delivery" value="delivery" required onclick="showDelivery(<?php echo $cart_details['cart_total_price']; ?>);">
																	<span><?php echo $this->lang->line('delivery') ?></span>
																</label>
															</div>
															<div class="delivery-form display-no" id="delivery-form">
																<div class="form-group">
																	<input type="hidden" name="add_latitude" id="add_latitude">
																	<input type="hidden" name="add_longitude" id="add_longitude">
																	<select class="form-control" name="your_address" id="your_address" onchange="delievry_charge(this.value)" required="">
																		<option value="">Select...</option>

																		<?php foreach ($delivery_area as $ds) { ?>
																			<option value="<?php echo $ds->entity_id ?>"><?php echo $ds->name ?></option>
																		<?php } ?>
																	</select>
																	<label><?php echo $this->lang->line('delivery_area') ?></label>
																</div>
																<div class="radio-btn-list">
																	<label>
																		<input type="radio" name="add_new_address" value="add_new_address" class="add_new_address" onclick="showAddAdress();">
																		<span><?php echo $this->lang->line('add_address') ?></span>
																	</label>
																</div>


																<?php $address = $this->checkout_model->getUsersAddress($this->session->userdata('UserID'));
																if (!empty($address)) { ?>
																	<div class="radio-btn-list">
																		<label>
																			<input type="radio" name="add_new_address" value="add_your_address" class="add_new_address" onclick="showYourAdress();">
																			<span><?php echo $this->lang->line('choose_your_address') ?></span>
																		</label>
																	</div>
																	<div id="your_address_content" class="display-no">
																		<h5><?php echo $this->lang->line('choose_your_address') ?></h5>
																		<div class="login-details">
																			<div class="form-group">
																				<select class="form-control" name="ch_address" id="ch_address" ">
																					<option value=""><?php echo $this->lang->line('select') ?></option>
																					<?php foreach ($address as $key => $value) { ?>
																						<option value=" <?php echo $value['entity_id']; ?>"><?php echo $value['address']; ?></option>
																				<?php } ?>
																				</select>
																				<label><?php echo $this->lang->line('your_address') ?></label>
																			</div>
																		</div>
																	</div>
																<?php } ?>
																<div id="add_address_content" class="display-no">
																	<h5><?php echo $this->lang->line('add_address') ?></h5>
																	<div class="login-details">




																		<div class="form-group">
																			<input type="text" name="add_address" id="" class="form-control" placeholder=" ">
																			<label><?php echo $this->lang->line('your_location') ?></label>
																		</div>



																		<!--																		-->
																	</div>
																</div>

															</div>
														</div>
														<div class="card card2">
															<div>
																<div class="current-location">
																	<h5><?php echo $this->lang->line('apply_coupon') ?></h5>
																	<p id="your_coupons"><?php echo $this->lang->line('no_coupons_available') ?></p>
																</div>

															</div>
														</div>
														<div class="card">
															<div>
																<div class="current-location">
																	<h5><?php echo $this->lang->line('extra_comment') ?></h5>
																</div>
																<div>
																	<div class="form-group">
																		<input type="text" name="extra_comment" id="extra_comment" class="form-control" placeholder=" ">
																		<label><?php echo $this->lang->line('extra_comment') ?></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="accordion" id="accordionExampleThree">
									<div class="card">
										<div class="card-header" id="headingThree">
											<div class="card-header-title" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true">
												<img src="<?php echo base_url(); ?>assets/front/images/payment.png">
												<h3><?php echo $this->lang->line('payment_method') ?></h3>
											</div>
										</div>
										<div id="collapseThree" class="collapse in show" aria-labelledby="headingThree" data-parent="#accordionExampleThree">
											<div class="card-body">
												<div class="payment-mode">
													<div class="payment-title">
														<h6><?php echo $this->lang->line('choose_payment_method') ?></h6>
													</div>
													<div class="order-mode">
														<div class="card">
															<div class="radio-btn-list">
																<label>
																	<input type="radio" name="payment_option" id="payment_option1" value="cod" required />
																	<span><?php echo $this->lang->line('cod') ?></span>
																</label>
															</div>


														</div>
													</div>
												</div>

												<!--												<div class="custom-control custom-checkbox" style="font-weight: bolder">-->
												<!--													<input type="checkbox" name="terms" class="custom-control-input" id="terms"  onchange="activateButton(this)" required>-->
												<!--													<label class="custom-control-label" for="terms"><span>I Agree with <span class="agree_terms"><a  href="cms/Terms-and-Conditions.html">Terms and Conditions</a></span>,<span class="agree_terms"> <a  href="cms/Privacy-Policy.html">Privacy policy</a></span> & <span class="agree_terms"></span><span class="agree_terms"><a  href="cms/Refund-and-Return-Policy.html">Refund and Return Policy.</a></span></span> </label>-->
												<!--												</div>-->

												<div class="proceed-btn">


													<button type="submit" name="submit_order" id="submit_order" value="Proceed" class="btn btn-primary"><?php echo $this->lang->line('proceed') ?></button>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>

						</form>
					</div>
				</div>
			</div>
			<?php if (!empty($cart_details['cart_items'])) { ?>
				<div class="col-lg-4" id="ajax_order_summary">
					<div class="order-summary">
						<div class="order-summary-title">
							<h3><i class="iicon-icon-02"></i><?php echo $this->lang->line('order_summary') ?></h3>
						</div>
						<div class="order-summary-content">
							<table>
								<tbody>
									<tr>
										<td><?php echo $this->lang->line('no_of_items') ?></td>
										<td><strong><?php echo count($cart_details['cart_items']); ?></strong></td>
									</tr>
									<tr>
										<td><?php echo $this->lang->line('sub_total') ?></td>
										<td>
											<strong><?php echo $currency_symbol->currency_symbol; ?><?php echo $cart_details['cart_total_price']; ?></strong>
										</td>
									</tr>
									<tr hidden>
										<td><?php echo "Service Charge" ?></td>
										<td>
											<strong><?php
													echo $currency_symbol->currency_symbol; ?><?php

																								echo ceil($cart_details['cart_total_price'] * $service_charge) / 100; ?></strong>
										</td>
									</tr>
									<tr>
										<td>Vat</td>
										<td>
											<strong><?php echo $currency_symbol->currency_symbol; ?><?php echo $total_vat = $cart_details['total_vat']; ?></strong>
										</td>
									</tr>

								</tbody>
								<tfoot>
									<tr>
										<td><?php echo $this->lang->line('to_pay') ?></td>
										<?php $to_pay = $cart_details['cart_total_price'] + $delivery_charges + $total_vat ;
										$this->session->set_userdata(array('total_price' => $to_pay)); ?>
										<td>

											<span id=""><strong><?php echo $currency_symbol->currency_symbol; ?><?php echo $to_pay; ?></strong></span>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="modal fade" id="forgot-pass-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
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
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Save changes</button> -->
				</div>
			</div>
		</div>
	</div>


</section>

<input type="hidden" value="" id="existing_user" class="form-control" placeholder="">
<input type="hidden" value="" id="new_user" class="form-control" placeholder="">
<!-- <input type="hidden" value="" id="chk_first_name" class="form-control" placeholder="">
<input type="hidden" value="" id="chk_last_name" class="form-control" placeholder=""> -->
<!--/ end content-area section -->


<div class="modal fade" id="Checkout_user_reg" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Enter Your Information</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div id="new_user_reg">
					<form id="new_user_registration" name="new_user_registration" method="post" class="form-horizontal float-form">
						<div class="form-body">

							<div class="form-group" style="display: none;">
								<input type="text" name="chk_mobile_number" id="chk_mobile_number" class="form-control" placeholder="">
								<input type="text" name="user_mobile" id="user_mobile" class="form-control" placeholder="">

								<!-- <label>Mobile Number</label> -->
							</div>
							<div class="form-group">
								<input type="text" name="chk_first_name" id="chk_first_name" value="" class="form-control" placeholder="">
								<label>Your First Name</label>
							</div>
							<div class="form-group">
								<input type="text" name="chk_last_name" id="chk_last_name" value="" class="form-control" placeholder="">
								<label>Your Last Name</label>
							</div>
							<div class="form-group">
								<input type="text" name="chk_address" id="chk_address" value="" class="form-control" placeholder="">
								<label>Your Adrress</label>
							</div>
							<div class="action-button">
								<button type="submit" name="signup_submit_page" id="signup_submit_page" value="Registration" class="btn red"><?php echo $this->lang->line('submit') ?></button>
							</div>
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Save changes</button> -->
			</div>
		</div>
	</div>
</div>



<!-- order delivery not available -->
<div class="modal modal-main" id="delivery-not-avaliable">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $this->lang->line('delivery_not_available') ?></h4>
				<button type="button" class="close" data-dismiss="modal"><i class="iicon-icon-23"></i></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="availability-popup">
					<div class="availability-images">
						<img src="<?php echo base_url(); ?>assets/front/images/no-delivery.png" alt="Booking availability">
					</div>
					<h2><?php echo $this->lang->line('avail_text1') ?></h2>
					<p><?php echo $this->lang->line('avail_text2') ?></p>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
<?php if ($this->session->userdata('is_user_login') == 1) { ?>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGh2j6KRaaSf96cTYekgAD-IuUG0GkMVA&libraries=places"></script>
<?php } ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/front/js/scripts/admin-management-front.js"></script>

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
<script type="text/javascript">
	$(document).ready(function() {
		// document.getElementById("submit_order").disabled = true;

		jQuery("#payment_option").prop('required', true);
		$('#signup_form').hide();
		var page = '<?php echo $page; ?>';
		if (page == "login") {
			$('#login_form').show();
			$('#signup_form').hide();
		}
		if (page == "register") {
			$('#login_form').hide();
			$('#signup_form').show();
		}
		$(window).keydown(function(event) {
			if (event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});

	$("#form_front_forgotpass").on("submit", function(event) {
		event.preventDefault();
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: BASEURL + 'home/forgot_password',
			data: {
				'number_forgot': $('#number_forgot').val(),
				'forgot_submit_page': $('#forgot_submit_page').val()
			},
			beforeSend: function() {
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
						$('#forgot-pass-modal').modal('hide');
						$('.modal-backdrop').hide();
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

	function delievry_charge(value) {


		var base_url = "<?= base_url() ?>";
		var csrf_test_name = $('[name="csrf_test_name"]').val();


		$.ajax({
			url: base_url + "checkout/delivery_charge",
			method: 'post',
			data: {
				area_id: value,
				csrf_test_name: csrf_test_name
			},

			cache: false,
			success: function(data) {
				let obj = jQuery.parseJSON(data);


				var dc = parseFloat(obj[0].delivery_charge);


				var sub_total = parseFloat($('#sub_total').val());
				var total_vat = parseFloat($('#total_vat').val());
				var service_charge = parseFloat($('#service_charge').val());

				if (coupon_discount) {
					var coupon_discount = parseFloat($('#coupon_discount').val());
				} else {
					var coupon_discount = 0;
				}

				if (dc >= 0) {
					$('.dc').removeClass('d-none');
					$('#delivery_charges').html('৳ ' + dc);
					$('#delivery_charges_val').val(dc);
				}

				// console.log(total_vat)
				//
				var to_pay = (sub_total + dc + total_vat + service_charge) - coupon_discount;


				//console.log(to_pay)
				$('#to_pay').html('৳ ' + to_pay);
			}
		})


	}



	function activateButton(element) {

		if (element.checked) {
			document.getElementById("submit_order").disabled = false;
		} else {
			document.getElementById("submit_order").disabled = true;
		}

	}
</script>


<script>
	function showClock(target) {
		const distance = target - new Date().getTime();
		const mins = distance < 0 ? 0 : Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		const secs = distance < 0 ? 0 : Math.floor((distance % (1000 * 60)) / 1000);

		// Output the results
		document.getElementById("minutes").innerHTML = mins;
		document.getElementById("seconds").innerHTML = secs;
	}

	function showClock_r(target) {
		const distance = target - new Date().getTime();
		const mins = distance < 0 ? 0 : Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		const secs = distance < 0 ? 0 : Math.floor((distance % (1000 * 60)) / 1000);

		// Output the results
		document.getElementById("r_minutes").innerHTML = mins;
		document.getElementById("r_seconds").innerHTML = secs;
	}

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
			type: "POST",
			dataType: "json",
			url: BASEURL + 'home/forgot_password',
			data: {
				'number_forgot': $('#number_forgot').val(),
				'forgot_submit_page': $('#forgot_submit_page').val()
			},
			beforeSend: function() {
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
						$('#forgot-pass-modal').modal('hide');
						$('.modal-backdrop').hide();
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
		window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
			'size': 'invisible'
		});
		recaptchaVerifier.render();
	}


	function forgot_verify() {
		const url_segment = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

		var countDownTarget = new Date().getTime() + 2 * 60 * 1000;
		//	showClock(countDownTarget);
		var x = setInterval(function() {
			showClock(countDownTarget);
			if (countDownTarget - new Date().getTime() < 0) {
				clearInterval(x);
				$('#verificationCode').prop("readonly", true);
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
			$('#exampleModal').modal('hide');

			window.location.href = 'home/forgot_page/' + url_segment + '/' + main_number;


			$('.modal-backdrop').hide();
			//$('#forgot_success').show();

			$('.xy').addClass('display-no');
			$('.verify').addClass('display-no');

		}).catch(function(error) {
			alert(error.message);
		});
	}

	function forgot_verify_Resend() {
		const url_segment = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

		var otp = $('#verificationCode').val();
		if (otp.length > 0) {
			return

		} else {
			$('#verificationCode').prop("readonly", false);
			$('#otp_time').addClass('d-none');
			$('#r_otp_time').removeClass('d-none');

			var countDownTarget = new Date().getTime() + 2 * 60 * 1000;
			showClock_r(countDownTarget);
			var x = setInterval(function() {
				showClock_r(countDownTarget);
				if (countDownTarget - new Date().getTime() < 0) {
					clearInterval(x);
					$('#verificationCode').prop("readonly", true);
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
				$('#exampleModal').modal('hide');
				window.location.href = 'home/forgot_page/' + url_segment + '/' + main_number;

				$('#forgot-pass-modal').hide();
				$('.modal-backdrop').hide();
				//$('#forgot_success').show();

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

	}
	//newly added
	$("#form_front_login_checkout").on("submit", function(event) {
		event.preventDefault();
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: BASEURL + 'home/checkUser',
			data: {
				'login_phone_number': $('#login_phone_number').val(),
				'submit_login_page': $('#submit_login_page').val()
			},
			beforeSend: function() {
				$('#quotes-main-loader').show();
			},
			success: function(response) {

				$('#forgot_error').hide();
				$('#forgot_success').hide();
				$('#quotes-main-loader').hide();
				if (response) {
					console.log(response);
					$('#existing_user').val(response.existing_user);
					$('#new_user').val(response.new_user);
					$('#chk_first_name').val(response.first_name);
					$('#chk_last_name').val(response.last_name);
					$('#chk_address').val(response.address);

					if (response) {
						// $("#forgot_success").html(response.forgot_success);
						$("#forgot_error").hide();
						$("#forgot_success").hide();
						$("#forgot_password_section").hide();
						$('#forgot-pass-modal').modal('hide');
						$('.modal-backdrop').hide();
						$('#forgot_password_section').hide();
						$('#exampleModal').modal('show');

						checkout_forgot_verify();


					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
			}
		});

	});

	function checkout_forgot_verify() {
		// alert("test");
		var latest_count = $('#count_number').val();
		const url_segment = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);

		var countDownTarget = new Date().getTime() + 2 * 60 * 1000;
		//	showClock(countDownTarget);
		var x = setInterval(function() {
			showClock(countDownTarget);
			if (countDownTarget - new Date().getTime() < 0) {
				clearInterval(x);
				$('#verificationCode').prop("readonly", true);
			}
		}, 1000);

		var countrycode = "+88";
		var main_number = document.getElementById('login_phone_number').value;
		var number = document.getElementById('login_phone_number').value;
		var number = countrycode.concat(number);
		//phone number authentication function of firebase
		//it takes two parameter first one is number,,,second one is recaptcha
		if (latest_count == 0) {
			firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function(confirmationResult) {
				//s is in lowercase
				window.confirmationResult = confirmationResult;
				coderesult = confirmationResult;
				console.log(coderesult);
			}).catch(function(error) {
				alert(error.message);
			});
		}
			var code = document.getElementById('verificationCode').value;

			coderesult.confirm(code).then(function(result) {
				$('#quotes-main-loader').hide();
				$('#user_mobile').val(main_number);
				$('#chk_mobile_number').val(main_number);


				alert("Successfully verified");
				$('#exampleModal').modal('hide');
				var user_exist = $("#existing_user").val();
				var first_name = $("#chk_first_name").val();
				if (user_exist == 1 && first_name != '') {
					// alert("test case 1");
					window.location.href = BASEURL + 'checkout/';
				} else if (user_exist == 1 && first_name == '') {
					// alert("test case 2");

					$('#Checkout_user_reg').modal('show');
				} else {
					//alert("test case 3");

					jQuery.ajax({
						type: "POST",
						dataType: "json",
						url: BASEURL + 'home/UserReg',
						data: {
							'login_phone_number': main_number,
						},
						beforeSend: function() {
							$('#quotes-main-loader').show();
						},
						success: function(response) {
							console.log(response);
							if (response) {
								$('#quotes-main-loader').hide();
								//console.log("success");
								$('#Checkout_user_reg').modal('show');
							}
							//alert("test");
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							alert(errorThrown);
						}
					});
				}
				// $('.modal-backdrop').hide();
				//$('#forgot_success').show();

				// $('.xy').addClass('display-no');
				// $('.verify').addClass('display-no');

			}).catch(function(error) {
				alert(error.message);
			});
		}
		$("#new_user_registration").on("submit", function(event) {
			// alert("test");
			event.preventDefault();
			jQuery.ajax({
				type: "POST",
				dataType: "json",
				url: BASEURL + 'home/CheckoutUserReg',
				data: {
					'chk_mobile_number': $('#chk_mobile_number').val(),
					'signup_submit_page': $('#signup_submit_page').val(),
					'first_name': $('#chk_first_name').val(),
					'last_name': $('#chk_last_name').val(),
					'address': $('#chk_address').val(),
				},
				beforeSend: function() {
					$('#quotes-main-loader').show();
				},
				success: function(response) {
					//alert("test");
					location.reload();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});
		});
</script>

<?php $this->load->view('footer'); ?>