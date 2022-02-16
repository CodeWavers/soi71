<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('header'); ?>

<section class="inner-pages-section cart-section">
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
							<form action="<?php echo base_url() . 'checkout'; ?>" id="form_front_login_checkout"
								  name="form_front_login_checkout" method="post" class="form-horizontal float-form">
								<div class="form-body">
									<?php if (!empty($this->session->flashdata('error_MSG'))) { ?>
										<div class="alert alert-danger">
											<?php echo $this->session->flashdata('error_MSG'); ?>
										</div>
									<?php } ?>
									<?php if (!empty($loginError)) { ?>
										<div class="alert alert-danger">
											<?php echo $loginError; ?>
										</div>
									<?php } ?>
									<?php if (validation_errors()) { ?>
										<div class="alert alert-danger login-validations">
											<?php echo validation_errors(); ?>
										</div>
									<?php } ?>
									<div class="login-details">
										<div class="form-group">
											<input type="number" name="login_phone_number" id="login_phone_number"
												   class="form-control" placeholder=" ">
											<label><?php echo $this->lang->line('phone_number') ?></label>
										</div>
										<div class="form-group mb-0">
											<input type="password" name="login_password" id="login_password"
												   class="form-control" placeholder=" ">
											<label><?php echo $this->lang->line('password') ?></label>
										</div>
									</div>
									<div class="action-button account-btn">
										<button type="submit" name="submit_login_page" id="submit_login_page"
												value="Login"
												class="btn btn-primary"><?php echo $this->lang->line('title_login') ?></button>
										<a href="<?php echo base_url() . 'home/registration'; ?>"
										   class="btn btn-secondary"><?php echo $this->lang->line('sign_up') ?></a>
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
							<div id="collapseOne" class="collapse" aria-labelledby="headingOne"
								 data-parent="#accordionExampleOne">
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
																	<input type="hidden" name="total_cart_items"
																		   id="total_cart_items"
																		   value="<?php echo count($cart_details['cart_items']); ?>">
																	<span class="minus" id="minusQuantity"
																		  onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'minus',<?php echo $cart_key; ?>)"><i
																				class="iicon-icon-22"></i></span>
																	<input type="text" name="item_count_check"
																		   id="item_count_check"
																		   value="<?php echo $value['quantity']; ?>"
																		   class="pointer-none"/>
																	<span class="plus" id="plusQuantity"
																		  onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'plus',<?php echo $cart_key; ?>)"><i
																				class="iicon-icon-21"></i></span>
																</div>
															</div>
														</td>
														<td class="close-btn-cart">
															<button class="close-btn"
																	onclick="customCheckoutItemCount(<?php echo $value['menu_id']; ?>,<?php echo $value['restaurant_id']; ?>,'remove',<?php echo $cart_key; ?>)">
																<i class="iicon-icon-38"></i></button>
														</td>
													</tr>
												<?php }
											} else { ?>
												<div class="cart-empty text-center">
													<img src="<?php echo base_url(); ?>assets/front/images/empty-cart.png">
													<h6><?php echo $this->lang->line("cart_empty"); ?>
														<br> <?php $this->lang->line("add_some_dishes"); ?></h6>
												</div>
											<?php } ?>
											</tbody>
										</table>f
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="order_mode_method" >
						<form id="checkout_form" name="checkout_form" method="post" class="form-horizontal float-form">
							<?php if ($this->session->userdata('is_user_login') == 1 && !empty($cart_details['cart_items'])) { ?>
								<div class="accordion" id="accordionExampleTwo">
									<div class="card" id="order_mode_content">
										<div class="card-header" id="headingTwo">
											<div class="card-header-title" data-toggle="collapse"
												 data-target="#collapseTwo" aria-expanded="true">
												<img src="<?php echo base_url(); ?>assets/front/images/order-mode.svg">
												<h3><?php echo $this->lang->line('order_mode') ?></h3>
											</div>
										</div>
										<div id="collapseTwo" class="collapse in show" aria-labelledby="headingTwo"
											 data-parent="#accordionExampleTwo">
											<div class="card-body">
												<div class="choose-order-mode">
													<div class="choose-order-title">
														<h6><?php echo $this->lang->line('choose_order_mode') ?></h6>
													</div>
													<div class="order-mode">
														<div class="card">
															<div class="radio-btn-list">
																<label>
																	<input type="hidden" name="subtotal" id="subtotal"
																		   value="<?php echo $cart_details['cart_total_price']; ?>">

																	<input type="radio" name="choose_order" id="pickup"
																		   value="pickup"
																		   onclick="showPickup(<?php echo $cart_details['cart_total_price']; ?>);">
																	<span><?php echo $this->lang->line('pickup') ?></span>
																</label>
																<!-- </div>
																<div class="radio-btn-list"> -->
																<label>
																	<input type="radio" name="choose_order"
																		   id="delivery" value="delivery"
																		   onclick="showDelivery(<?php echo $cart_details['cart_total_price']; ?>);">
																	<span><?php echo $this->lang->line('delivery') ?></span>
																</label>
															</div>
															<div class="delivery-form display-no" id="delivery-form">
																<div class="form-group">
																	<input type="hidden" name="add_latitude"
																		   id="add_latitude">
																	<input type="hidden" name="add_longitude"
																		   id="add_longitude">
																	<select class="form-control"
																			name="your_address"
																			id="your_address"
																			onchange="delievry_charge(this.value)"
																			required="">
																		<option value="">Select...</option>

																		<?php foreach ($delivery_area as $ds) { ?>
																			<option value="<?php echo $ds->entity_id ?>"><?php echo $ds->name ?></option>
																		<?php } ?>
																	</select>
																	<label><?php echo $this->lang->line('delivery_area') ?></label>
																</div>
																<div class="radio-btn-list">
																	<label>
																		<input type="radio" name="add_new_address"
																			   value="add_new_address"
																			   class="add_new_address"
																			   onclick="showAddAdress();">
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
																						<option value="<?php echo $value['entity_id']; ?>"><?php echo $value['address']; ?></option>
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
																			<input type="text" name="add_address"
																				   id="" class="form-control"
																				   placeholder=" ">
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
																		<input type="text" name="extra_comment"
																			   id="extra_comment" class="form-control"
																			   placeholder=" ">
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
											<div class="card-header-title" data-toggle="collapse"
												 data-target="#collapseThree" aria-expanded="true">
												<img src="<?php echo base_url(); ?>assets/front/images/payment.png">
												<h3><?php echo $this->lang->line('payment_method') ?></h3>
											</div>
										</div>
										<div id="collapseThree" class="collapse in show" aria-labelledby="headingThree"
											 data-parent="#accordionExampleThree">
											<div class="card-body">
												<div class="payment-mode">
													<div class="payment-title">
														<h6><?php echo $this->lang->line('choose_payment_method') ?></h6>
													</div>
													<div class="order-mode">
														<div class="card">
															<div class="radio-btn-list">
																<label>
																	<input type="radio" name="payment_option"
																		   id="payment_option1" value="cod" required/>
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


													<button type="submit" name="submit_order" id="submit_order"
															value="Proceed"
															class="btn btn-primary"><?php echo $this->lang->line('proceed') ?></button>
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
								<tr>
									<td>Vat</td>
									<td>
										<strong><?php echo $currency_symbol->currency_symbol; ?><?php echo $total_vat=$cart_details['total_vat']; ?></strong>
									</td>
								</tr>

								</tbody>
								<tfoot>
								<tr>
									<td><?php echo $this->lang->line('to_pay') ?></td>
									<?php $to_pay = $cart_details['cart_total_price'] + $delivery_charges+$total_vat;
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
</section>
<!--/ end content-area section -->

<!-- Order Confirmation -->
<div class="modal modal-main" id="order-confirmation">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $this->lang->line('order_confirmation') ?></h4>
				<button type="button" class="close" data-dismiss="modal"
						onclick="document.location.href='<?php echo base_url(); ?>restaurant';"><i
							class="iicon-icon-23"></i></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<div class="availability-popup">
					<div class="availability-images">
						<img src="<?php echo base_url(); ?>assets/front/images/order-confirmation.svg"
							 alt="Booking availability">
					</div>
					<h2><?php echo $this->lang->line('thankyou_for_order') ?></h2>
					<p><?php echo $this->lang->line('order_placed') ?></p>
					<span id="track_order"><a href="<?php echo base_url(); ?>myprofile"
											  class="btn"><?php echo $this->lang->line("track_order"); ?></a></span>
				</div>
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
						<img src="<?php echo base_url(); ?>assets/front/images/no-delivery.png"
							 alt="Booking availability">
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
	<script type="text/javascript"
			src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGh2j6KRaaSf96cTYekgAD-IuUG0GkMVA&libraries=places"></script>
<?php } ?>

<script type="text/javascript"
		src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/front/js/scripts/admin-management-front.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
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
		$(window).keydown(function (event) {
			if (event.keyCode == 13) {
				event.preventDefault();
				return false;
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
			success: function (data) {
				let obj = jQuery.parseJSON(data);


				var dc = parseFloat(obj[0].delivery_charge);


				var sub_total = parseFloat($('#sub_total').val());
				 var total_vat = parseFloat($('#total_vat').val());


				if (coupon_discount) {
					var coupon_discount = parseFloat($('#coupon_discount').val());
				} else {
					var coupon_discount = 0;
				}

				if (dc > 0) {
					$('.dc').removeClass('d-none');
					$('#delivery_charges').html('৳ ' + dc);
					$('#delivery_charges_val').val(dc);
				}

				// console.log(total_vat)
				//
				var to_pay = (sub_total + dc+total_vat) - coupon_discount;


				//console.log(to_pay)
				$('#to_pay').html('৳ ' + to_pay);
			}
		})


	}



	function activateButton(element) {

		if (element.checked) {
			document.getElementById("submit_order").disabled = false;
		}
		else  {
			document.getElementById("submit_order").disabled = true;
		}

	}
</script>
<?php $this->load->view('footer'); ?>
