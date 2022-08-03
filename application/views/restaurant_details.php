<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<?php $this->load->view('header');

$menu_ids = array();
if (!empty($menu_arr)) {
	$menu_ids = array_column($menu_arr, 'menu_id');
} ?>


<!--<script type="text/javascript" src="--><?php //echo base_url();?><!--assets/front/js/tab-slider.js"></script>-->

<section class="inner-banner restaurant-detail-banner">
	<div class="container">
		<div class="inner-pages-banner">

		</div>
	</div>
</section>

<section class="inner-pages-section rest-detail-section ">

	<div class="container">

		<div class="row">
<!--			<div class="  takeway_re  " >-->
<!--				<ul class="" style="align-items:center;justify-content: center ;color: white;">-->
<!---->
<!--					<a class="three_button" href="--><?php //echo base_url('restaurant/restaurant-detail/'.$slug);?><!--"><li class=" --><?php //echo ($current_page == 'ContactUs1') ? 'li_bg' : 'li_bg'; ?><!--" ><span style="font-size: 12px"  class="fas fa-check"></span><strong class="span_text"> Takeway</strong></li></a>-->
<!--					<a class="three_button" href="--><?php //echo base_url('restaurant/restaurant-detail/'.$slug);?><!--"><li class="--><?php //echo ($current_page == 'RestaurantDetails') ? 'li_bg' : 'li_bg'; ?><!--" ><span style="font-size: 12px " class="fas fa-check"></span><strong  class="span_text"> Delivery</strong></li></a>-->
<!--					<a class="three_button" href="--><?php //echo base_url() . 'restaurant/event-booking'; ?><!--"><li class="--><?php //echo ($current_page == 'EventBooking') ? 'li_sec' : 'li_bg'; ?><!--" ><span style="font-size: 12px " class="fas fa-check"></span><strong class="span_text"> Dine In</strong></li></a>-->
<!---->
<!--				</ul>-->
<!--			</div>-->
<!--			<div class="col-lg-12">-->
<!--				<div class="heading-title">-->
<!--					<h2>--><?php //echo $this->lang->line('order_food_from') ?><!-- </h2>-->
<!--				</div>-->
<!---->
<!--			</div>-->
		</div>


		<div class="row restaurant-detail-row">
			<!-- restaurant details start-->
			<div class="col-sm-12 col-md-12 col-lg-12 " id="menu" style="display: block;" >

				<div id="cat_stick">
					<div class="col-lg-12">
						<div class="heading-title">
							<h2><?php echo $this->lang->line('order_food_from') ?> </h2>
						</div>

					</div>
					<div  class="carousel"
						  data-flickity='{ "wrapAround": true }'>
						<?php foreach ($restaurant_details['categories'] as $key => $value) {?>

							<div class="gallery-cell">
								<div class="gallery_cats" id="category_id-<?php echo $value['category_id']; ?>" onclick="menuTopSearch(<?php echo $value['category_id']; ?>)">
									<span id="category_name-<?php echo $value['category_id']; ?>"  ><?php echo $value['name']; ?></span>


								</div>
							</div>
						<?php }?>

					</div>
					<div class="option-filter-tab">
						<!-- <div class="custom-control custom-checkbox">
								<input type="radio" name="filter_food" class="custom-control-input" id="filter_veg" value="filter_veg" onclick="menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>)">
								<label class="custom-control-label" for="filter_veg"><?php echo $this->lang->line('veg') ?></label>
							</div>
							<div class="custom-control custom-checkbox">
								<input type="radio" name="filter_food" class="custom-control-input" id="filter_non_veg" value="filter_non_veg" onclick="menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>)">
								<label class="custom-control-label" for="filter_non_veg"><?php echo $this->lang->line('non_veg') ?></label>
							</div> -->
						<div class="custom-control custom-checkbox">
							<input type="radio" checked="checked" name="filter_food" class="custom-control-input" id="all" value="all" onclick="menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>)">
							<label class="custom-control-label" for="all"><?php echo $this->lang->line('view_all') ?></label>
						</div>
						<div class="custom-control custom-checkbox">
							<input type="radio" checked="checked" name="filter_price" class="custom-control-input" id="filter_high_price" value="filter_high_price" onclick="menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>)">
							<label class="custom-control-label" for="filter_high_price"><?php echo $this->lang->line('sort_by_price_low') ?></label>
						</div>
						<div class="custom-control custom-checkbox">
							<input type="radio" name="filter_price" class="custom-control-input" id="filter_low_price" value="filter_low_price" onclick="menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>)">
							<label class="custom-control-label" for="filter_low_price"><?php echo $this->lang->line('sort_by_price_high') ?></label>
						</div>
						<div class="custom-control custom-checkbox">
							<input type="checkbox" name="filter_best_deal" class="custom-control-input" id="filter_best_deal" value="" onclick="best_deal()">
							<label class="custom-control-label" for="filter_best_deal">Best Deal</label>
						</div>

					</div>


				</div>
				<div class="is_close">	<?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'<span id="closedres">'.$this->lang->line('not_accepting_orders').'</span>':''; ?>
				</div>
				<div id="details_content" >
					<?php if (!empty($restaurant_details['menu_items']) || !empty($restaurant_details['packages']) || !empty($restaurant_details['categories'])) {
						if (!empty($restaurant_details['categories'])) {?>

						<?php }?>



						<div id="res_detail_content">
							<?php if(!$popular_data) { ?>
								<div class="heading-title bd">
									<h2>Popular Items</h2>
									<div class="slider-arrow">
										<div id="customNav" class="arrow"></div>
									</div>
								</div>
								<div class="home-items bd">

									<?php  foreach ($popular_data as $ds){ ?>

										<?php foreach ($ds['menu_items'] as $key => $value) { ?>


											<div class="home-menu-card ">
												<div class="home-menu-image hover"  data-id="<?php echo ($value['entity_id']) ?>">
													<img class="" src="<?php echo ($value['image']) ? (base_url('uploads/'.$value['image'])) : (base_url('assets/front/images/placeholder_image.png')); ?>" >

												</div>
												<div class="home-menu-des">
													<div class="">
														<div class="home-menu-name"><?php echo $value['name']; ?></div>

													</div>
													<div class="home-menu-details-parent">
														<div class="det-with-price">
															<p class="home-menu-details"><?php echo ($value['menu_detail']) ? $value['menu_detail'] : 'Something you won\'t regret'; ?></p>
															<strong><?php echo ($value['check_add_ons'] != 1) ? $restaurant_details['restaurant'][0]['currency_symbol'] . ' ' . $value['price'] : ''; ?></strong>

														</div>

														<div class="add-btn-div">
															<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
																if ($value['check_add_ons'] == 1) { ?>
																	<?php  $add = (in_array($value['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
																	<div class="add-btn home-add">
																		<button class="btn <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : '' ?> onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)"> <?php echo (in_array($value['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
																		<span class="cust"><?php echo $this->lang->line('customizable') ?></span>
																	</div>
																<?php } else { ?>
																	<div class="add-btn home-add">
																		<?php $add = (in_array($value['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
																		<button class="home-add btn <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?>> <?php echo (in_array($value['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
																	</div>
																<?php }
															} ?>
														</div>
													</div>
												</div>
											</div>


										<?php }?>


									<?php } ?>



								</div>

							<?php } ?>
							<div class="heading-title">
								<h2>Best Deals</h2>
								<div class="slider-arrow">
									<div id="customNav" class="arrow"></div>
								</div>
							</div>
							<div class="home-items">
								<?php if (!empty($restaurant_details['menu_items'])) {
									$popular_count = 0;
									foreach ($restaurant_details['menu_items'] as $key => $value) {
										if ($value['popular_item'] == 1) {
											$popular_count = $popular_count + 1;
										}
									}
									if ($popular_count > 0) { ?>
										<?php foreach ($restaurant_details['menu_items'] as $key => $value) {
											if ($value['popular_item'] == 1) { ?>

												<div class="home-menu-card ">
													<div class="home-menu-image hover" data-id="<?php echo ($value['entity_id']) ?>">
														<img class="" src="<?php echo ($value['image']) ? ($value['image']) : (default_img); ?>" >

													</div>
													<div class="home-menu-des">
														<div class="">
															<div class="home-menu-name"><?php echo $value['name']; ?></div>

														</div>
														<div class="home-menu-details-parent">
															<div class="det-with-price">
																<p class="home-menu-details"><?php echo ($value['menu_detail']) ? $value['menu_detail'] : 'Something you won\'t regret'; ?></p>
																<strong><?php echo ($value['check_add_ons'] != 1) ? $restaurant_details['restaurant'][0]['currency_symbol'] . ' ' . $value['price'] : ''; ?></strong>

															</div>

															<div class="add-btn-div">
																<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
																	if ($value['check_add_ons'] == 1) { ?>
																		<?php  $add = (in_array($value['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
																		<div class="add-btn home-add">
																			<button class="btn <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : '' ?> onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)"> <?php echo (in_array($value['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
																			<span class="cust"><?php echo $this->lang->line('customizable') ?></span>
																		</div>
																	<?php } else { ?>
																		<div class="add-btn home-add">
																			<?php $add = (in_array($value['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
																			<button class="home-add btn <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?>> <?php echo (in_array($value['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
																		</div>
																	<?php }
																} ?>
															</div>
														</div>
													</div>
												</div>

											<?php }
										}?>

									<?php }?>
								<?php }?>
							</div>

							<div class="heading-title bd">
								<h2>Menu Items</h2>
								<div class="slider-arrow">
									<div id="customNav" class="arrow"></div>
								</div>
							</div>
							<div class="home-items bd">
								<?php if (!empty($restaurant_details['menu_items'])) {
									$popular_count = 0;
									if (!empty($restaurant_details['categories'])) {
										foreach ($restaurant_details['categories'] as $key => $value) { ?>


											<div class="detail-list-title w-100" >
												<h3  id="category-<?php echo $value['category_id']; ?>"><?php echo $value['name']; ?></h3>
											</div><hr>

											<?php if ($restaurant_details[$value['name']]) {
												foreach ($restaurant_details[$value['name']] as $key => $mvalue) { ?>
													<div class="home-menu-card " >
														<div class="home-menu-image hover " data-id="<?php echo ($mvalue['entity_id']) ?>" >
															<img src="<?php echo ($mvalue['image']) ? (image_url . $mvalue['image']) : (default_img); ?>" <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?>?>
														</div>
														<div class="home-menu-des">
															<div class="">
																<div class="home-menu-name"><?php echo $mvalue['name']; ?></div>

															</div>
															<div class="home-menu-details-parent">
																<div class="det-with-price">
																	<p class="home-menu-details"><?php echo ($mvalue['menu_detail']) ? $mvalue['menu_detail'] : 'Something you won\'t regret'; ?></p>
																	<strong><?php echo ($mvalue['check_add_ons'] != 1) ? $restaurant_details['restaurant'][0]['currency_symbol'] . ' ' . $mvalue['price'] : ''; ?></strong>
																</div>

																<div class="add-btn-div">
																	<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
																		if ($mvalue['check_add_ons'] == 1) { ?>
																			<?php $add = (in_array($mvalue['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
																			<div class="add-btn home-add">
																				<button class="ab_btn btn <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?> onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)"> <?php echo (in_array($mvalue['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
																				<span class="cust"><?php echo $this->lang->line('customizable') ?></span>
																			</div>
																		<?php } else { ?>
																			<div class="add-btn home-add">
																				<?php $add = (in_array($mvalue['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
																				<button class="ab_btn home-add btn <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?>> <?php echo (in_array($mvalue['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
																			</div>
																		<?php }
																	} ?>
																</div>
															</div>
														</div>
													</div>
												<?php }
											} ?>


										<?php }
									}
								} ?>
							</div>
						</div>
					<?php }
					else {?>
						<div class="slider-checkbox-main">
							<div class="detail-list-title">
								<h3><?php echo $this->lang->line('no_results_found') ?></h3>
							</div>
						</div>
					<?php }?>
				</div>

			</div>
			<!-- restaurant details end -->
			<!-- ratings and review start -->
			<div class="col-sm-12 col-md-5 col-lg-8" id="review" style="display: none;" >
				<div class="detail-list-box-main">
					<div class="detail-list-title">
						<h3><?php echo $this->lang->line('review_ratings') ?></h3>
						<?php if (!empty($this->session->userdata('UserID')) && $remaining_reviews > 0) { ?>
							<button class="btn" onclick="addReview(<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>)"><?php echo $this->lang->line('title_admin_reviewadd'); ?></button>
						<?php } ?>
					</div>
					<div class="rating-review-main">
						<?php if($this->session->flashdata('review_added')){ ?>
							<div class="alert alert-success" id="review_success"><?php echo $this->session->flashdata('review_added');?></div>
						<?php } ?>
						<div class="review-progress">
							<div class="progress-main">
								<div class="review-all">
									<p class="text-center"><?php echo (!empty($restaurant_reviews))?count($restaurant_reviews):0; ?> <?php echo (!empty($restaurant_reviews))?((count($restaurant_reviews) > 1)?$this->lang->line('reviews'):$this->lang->line('review')):$this->lang->line('review'); ?></p>
								</div>
								<?php for ($i=5; $i > 0 ; $i--) { ?>
									<div class="progress-box">
										<span class="star-icon"><?php echo $i; ?></span>
										<div class="progress">
											<?php
											$noOfReviews = $this->restaurant_model->getReviewsNumber($restaurant_details['restaurant'][0]['restaurant_id'],$i);
											$percentage = $noOfReviews * 100 / count($restaurant_reviews); ?>
											<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage.'%'; ?>">
											</div>
										</div>
										<span><?php echo $noOfReviews; ?></span>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="rate-restaurant">
							<div class="star-rating-main">
								<div class="star-rating">
									<?php for ($i=1; $i < 6; $i++) {
										$activeClass = '';
										if ($i <= $restaurant_details['restaurant'][0]['ratings']) {
											$activeClass = 'active'; ?>
										<?php } ?>
										<button class="<?php echo $activeClass; ?>"><i class="iicon-icon-28"></i></button>
									<?php } ?>
								</div>
								<div class="review-all">
									<span><i class="iicon-icon-05"></i><?php echo $restaurant_details['restaurant'][0]['ratings']; ?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="review-box-main">
						<div id="limited-reviews">
							<?php if (!empty($restaurant_reviews)) {
								foreach ($restaurant_reviews as $key => $value) {
									if ($key <= 4) { ?>
										<div class="review-list">
											<div class="review-img">
												<div class="user-images">
													<img src="<?php echo ($value['image'])?$value['image']:default_img; ?>">
												</div>
											</div>
											<div class="review-content">
												<div class="user-name-date">
													<h3><?php echo $value['first_name'].' '.$value['last_name']; ?></h3>
													<div class="review-star">
														<span><i class="iicon-icon-05"></i><?php echo number_format($value['rating'],1); ?></span>
													</div>
													<div class="review-date">
														<span><?php echo date("d M Y",strtotime($value['created_date'])); ?></span>
													</div>
												</div>
												<p>"<?php echo ucfirst($value['review']); ?>"</p>
											</div>
										</div>
									<?php }
								}
							} ?>
						</div>
						<div id="all_reviews" class="display-no" >
							<?php if (!empty($restaurant_reviews)) {
								foreach ($restaurant_reviews as $key => $value) {
									if ($key > 4) { ?>
										<div class="review-list">
											<div class="review-img">
												<div class="user-images">
													<img src="<?php echo ($value['image'])?$value['image']:default_img; ?>">
												</div>
											</div>
											<div class="review-content">
												<div class="user-name-date">
													<h3><?php echo $value['first_name'].' '.$value['last_name']; ?></h3>
													<div class="review-star">
														<span><i class="iicon-icon-05"></i><?php echo number_format($value['rating'],1); ?></span>
													</div>
													<div class="review-date">
														<span><?php echo date("d M Y",strtotime($value['created_date'])); ?></span>
													</div>
												</div>
												<p>"<?php echo ucfirst($value['review']); ?>"</p>
											</div>
										</div>
									<?php }
								}
							} ?>
						</div>
						<?php if (count($restaurant_reviews) > 4) { ?>
							<button id="review_button" class="btn btn-success danger-btn" onclick="showAllReviews()"><?php echo $this->lang->line('all_reviews') ?></button>
						<?php } ?>
					</div>
				</div>
			</div>
			<!-- ratings and review end -->

			<!-- your cart -->

			<!-- your cart end -->
		</div>

		<button onclick="topFunction()" id="myBtn" title="Go to top"><span class="fa fa-arrow-circle-up"></span></button>


	</div>
</section>

<div class="modal modal-main" id="myconfirmModal">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $this->lang->line('add_to_cart') ?> ?</h4>
				<button type="button" class="close" data-dismiss="modal"><i class="iicon-icon-23"></i></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form id="custom_items_form">
					<h5><?php echo $this->lang->line('menu_already_added') ?> <br> <?php echo $this->lang->line('want_to_add_new_item') ?></h5>
					<div class="popup-radio-btn-main">
						<div class="radio-btn-box">
							<div class="radio-btn-list">
								<label>
									<input type="hidden" name="con_entity_id" id="con_entity_id" value="">
									<input type="hidden" name="con_restaurant_id" id="con_restaurant_id" value="">
									<input type="hidden" name="con_item_id" id="con_item_id" value="">
									<input type="radio" class="radio_addon" name="addedToCart" id="addnewitem" value="addnewitem">
									<span><?php echo $this->lang->line('as_new_item') ?></span>
								</label>
							</div>
							<div class="radio-btn-list">
								<label>
									<input type="radio" class="radio_addon" name="addedToCart" id="increaseitem" value="increaseitem">
									<span><?php echo $this->lang->line('increase_quantity') ?></span>
								</label>
							</div>
						</div>
					</div>
					<div class="popup-total-main">
						<div class="total-price">
							<button type="button" class="addtocart btn" id="addtocart" onclick="ConfirmCartAdd()"><?php echo $this->lang->line('add_to_cart') ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-main" id="reviewModal">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $this->lang->line('review_ratings') ?></h4>
				<button type="button" class="close" data-dismiss="modal"><i class="iicon-icon-23"></i></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form id="review_form" name="review_form" method="post" class="form-horizontal float-form">
					<div class="review-img">
						<div class="user-images">
							<img src="<?php echo base_url();?>assets/front/images/review.png">
						</div>
					</div>
					<div class="rating">
						<input type="hidden" name="review_user_id" id="review_user_id" value="<?php echo $this->session->userdata('UserID'); ?>">
						<input type="hidden" name="review_restaurant_id" id="review_restaurant_id" value="<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>">

						<span><input type="radio" name="rating" id="str5" value="5"><label for="str5"></label></span>
						<span><input type="radio" name="rating" id="str4" value="4"><label for="str4"></label></span>
						<span class="checked"><input type="radio" name="rating" id="str3" value="3"><label for="str3"></label></span>
						<span><input type="radio" name="rating" id="str2" value="2"><label for="str2"></label></span>
						<span><input type="radio" name="rating" id="str1" value="1"><label for="str1"></label></span>
					</div>
					<div>
						<input type="text" name="review_text" id="review_text" class="form-control" placeholder="<?php echo $this->lang->line('write_review') ?>">
					</div>
					<div>
						<button type="submit" name="submit_review" id="submit_review" class="btn btn-primary"><?php echo $this->lang->line('add_review') ?></button>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>


<!-- imaaaaaaaaaaaaa modal 2 -->

<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade imagemodaltwo" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" >
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title item_name" id="myModalLabel">Image preview</h4>
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-4 display-no" id="img1">
						<div class="thumbnail coupon">

							<img id="image1" src="" alt="Image" style="width:100%">
							<div class="det-with-price">

								<!--										<strong>--><?php //echo ($value['check_add_ons'] != 1) ? $restaurant_details['restaurant'][0]['currency_symbol'] . ' ' . $value['price'] : ''; ?><!--</strong>-->
							</div>

						</div>


					</div>
					<div class="col-md-4 display-no " id="img2">
						<div class="thumbnail coupon">

							<img id="image2" src="" alt="Image" style="width:100%">


						</div>
					</div>
					<div class="col-md-4 display-no" id="img3" >
						<div class="thumbnail coupon">

							<img id="image3" src="" alt="Image" style="width:100%">


						</div>
					</div>


				</div>
				<div class="det-with-price">
					<p class="home-menu-details item_details"></p>
					<strong class="item_price"></strong>

				</div>
			</div>
			<div class="modal-footer">
				<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") { ?>




					<button type="button" id="btn_add" class="btn btn-default btn_add">Add </button><br>



				<?php } ?>
			</div>
		</div>
	</div>
</div>


<input type="hidden" id="base_url" value="<?php echo base_url()?>">


<div class="modal modal-main" id="anotherRestModal">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $this->lang->line('add_to_cart') ?> ?</h4>
				<button type="button" class="close" data-dismiss="modal"><i class="iicon-icon-23"></i></button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<form id="custom_cart_restaurant_form">
					<h5><?php echo $this->lang->line('res_details_text1') ?> <br><?php echo $this->lang->line('res_details_text2') ?></h5>
					<div class="popup-radio-btn-main">
						<div class="radio-btn-box">
							<div class="radio-btn-list">
								<label>
									<input type="hidden" name="rest_entity_id" id="rest_entity_id" value="">
									<input type="hidden" name="rest_restaurant_id" id="rest_restaurant_id" value="">
									<input type="hidden" name="is_addon" id="rest_is_addon" value="">
									<input type="hidden" name="item_id" id="item_id" value="">
									<input type="radio" class="radio_addon" name="addNewRestaurant" id="discardOld" value="discardOld">
									<span><?php echo $this->lang->line('discard_old') ?></span>
								</label>
							</div>
							<div class="radio-btn-list">
								<label>
									<input type="radio" class="radio_addon" name="addNewRestaurant" id="keepOld" value="keepOld">
									<span><?php echo $this->lang->line('keep_old') ?></span>
								</label>
							</div>
						</div>
					</div>
					<div class="popup-total-main">
						<div class="total-price">
							<button type="button" class="cartrestaurant btn" id="cartrestaurant" onclick="ConfirmCartRestaurant()"><?php echo $this->lang->line('confirm') ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- imaaaaaaaaa modal end -->





<!-- all view modal -->


<!-- end all  -->
<!-- Trigger the modal with a button -->






<!-- view items modal -->

<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top: 110px;">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			</div>
			<div id="res_detail_content">
				<div class="modal-body">
					<?php if (!empty($restaurant_details['menu_items'])) {
						$popular_count = 0;
						foreach ($restaurant_details['menu_items'] as $key => $value) {
							if ($value['popular_item'] == 1) {
								$popular_count = $popular_count + 1;
							}
						}
						if ($popular_count > 0) { ?>
							<div class="detail-list-box-main">
								<div class="detail-list-title">
									<h3><?php echo $this->lang->line('popular_items') ?></h3>

								</div>
								<?php foreach ($restaurant_details['menu_items'] as $key => $value) {

									if ($value['popular_item'] == 1) { ?>
										<div class="detail-list-box ">
											<div class="detail-list ">


												<div class="gc-start"  id="pop">
													<div class="Viewmodalshow">
														<div class="row">
															<div class="col ViewImage">
																<img src="<?php echo ($value['image']) ? ($value['image']) : (default_img); ?>" class="ViewImage">
															</div>
															<div class="col" >
																<h4 class="nameStyle"><?php echo $value['name']; ?></h4>
																<strong class="nameStyle">Price: <?php echo $value['price']; ?></strong>
																<p class="menuStyle"><?php echo $value['menu_detail']; ?></p>
																<p class="menuStyle"><strong>Availability</strong> <?php echo $value['availability']; ?></p>

															</div>

														</div>

														<div class="detail-list-content">
															<div class="detail-list-text">
																<!-- <h4><?php echo $value['name']; ?></h4>

																<p><?php echo $value['menu_detail']; ?></p>
																<strong><?php echo ($value['check_add_ons'] != 1)?$restaurant_details['restaurant'][0]['currency_symbol'].' '.$value['price']:''; ?></strong> -->
															</div>

														</div>

													</div>
													<div class="col">

													</div>
												</div>

											</div>
										</div>


										<div class="">
											<p src="<?php echo ($value['availability']) ? ($value['availability']) : (default_img); ?>">
										</div>
									<?php }
								}?>
							</div>

						<?php }?>
					<?php }?>

				</div>

			</div>
		</div>
	</div>
</div>
<!-- end view items modal -->


















<!-- The Modal -->
<div class="modal modal-main" id="myModal"></div>
<?php if (!empty($restaurant_details['categories'])) {?>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/tab-slider.js"></script>
<?php }?>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js"></script>
<script src="<?php echo base_url(); ?>assets/front/js/bootstrap-tagsinput.js"></script>

<!-- for review/rating and menu -->



<script type="text/javascript">
	$(function() {

		// Check Radio-box
		$(".rating input:radio").filter('[value=3]').prop('checked', true);
		$('.rating input').click(function () {
			$(".rating span").removeClass('checked');
			$(this).parent().addClass('checked');
		});

		$('input:radio').change(
				function(){
					var userRating = this.value;
				});

		$('#menu_link').click(function(e) {
			$("#menu").delay(100).fadeIn(100);
			$("#review").fadeOut(100);
			$('#review_link').removeClass('active');
			$(this).addClass('active');
			e.preventDefault();
		});
		$('#review_link').click(function(e) {
			$("#review").delay(100).fadeIn(100);
			$("#menu").fadeOut(100);
			$('#menu_link').removeClass('active');
			$(this).addClass('active');
			e.preventDefault();
		});



	});
</script>




<script type="text/javascript">
	$("#popup").on("click", function() {
		$('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
		$('#imagemodaltwo').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
	})

	$(".mobile-icon  button").on("click", function(e) {
		 $("#example-one").toggleClass("open");
		 $(this).toggleClass('open');
	//	$("#example-one").fadeToggle();
		e.stopPropagation()
	});

	// $("#example-one").on("click", function(e) {
	// 	e.stopPropagation()
	// });
	$(".notification-btn").on("click", function(e) {
		$(".noti-popup").toggleClass("open");
		e.stopPropagation()
	});
	$(".noti-popup").on("click", function(e) {
		e.stopPropagation()
	});
	$(".user-menu-btn").on("click", function(e) {
		$(".header-user-menu").toggleClass("open");
		e.stopPropagation()
	});
	$(".header-user-menu").on("click", function(e) {
		e.stopPropagation()
	});
</script>
<script type="text/javascript">
	function itemWidth() {
		// get the larger item to set the width of every item
		var largestWidth = 0;
		$(".nav-tabs>li>a").each(function() {
			if ($(this).outerWidth() > largestWidth) {
				largestWidth = $(this).outerWidth();
			}
		});
		Wrap = $(".wrap").outerWidth(); // the wrapper full width
		someSpace = largestWidth + 10; // make some space, 5px each side
		roundFigure = Wrap / someSpace; // parent width divided by larger item width
		roundFigure = Math.round(roundFigure); // round the figure
		finalWidth = Wrap / roundFigure; // get the final width
		$(".nav-tabs>li>a").outerWidth(finalWidth); // set the final width
	}

	itemWidth(); // call the funcation

	// do the magic
	var menus = $("#menus"),
			menuWidth = menus.parent().outerWidth();
	var menupage = Math.ceil(menus[0].scrollWidth / menuWidth),
			currPage = 1;
	if (menupage > 1) {
		$("#goNext").click(function() {
			currPage < menupage && menus.stop(true).animate({
				"left": -menuWidth * currPage
			}, "slow") && currPage++
		});
		$("#goPrev").click(function() {
			currPage > 1 && menus.stop(true).animate({
				"left": -menuWidth * (currPage - 2)
			}, "slow") && currPage--;
		});
		// refresh on resize
		$(window).on("resize", function() {
			itemWidth();
			menuWidth = menus.parent().outerWidth();
			menupage = Math.ceil(menus[0].scrollWidth / menuWidth);
			currPage = Math.ceil(-parseInt(menus.css("left")) / menuWidth) + 1;
		});
	}
</script>

<script type="text/javascript">


	$(".hover").on("click", function() {

		var entity_id=$(this).data('id');
		var res_id=$(this).data('res_id');

		var base_url=$('#base_url').val();

		var default_im="<?php echo default_img?>"



		//	alert(entity_id)

		$.ajax({
			url: base_url + "restaurant/find_all_image",
			type: 'post',
			data: {entity_id: entity_id},
			success: function(response){

				var json=JSON.parse(response)

				if (json[0].image ){
					$('#img1').removeClass('display-no');

					$('#image1').attr('src', base_url+'uploads/'+json[0].image);
				}else{
					$('#image1').attr('src', default_im);
				}

				if (json[0].image2 ){
					$('#img2').removeClass('display-no');
					$('#image2').attr('src', base_url+'uploads/'+json[0].image2);
				}else{
					$('#image2').attr('src', default_im);
				}

				if (json[0].image3 ){
					$('#img3').removeClass('display-no');
					$('#image3').attr('src', base_url+'uploads/'+json[0].image3);
				}else{
					$('#image3').attr('src', default_im);
				}

				$('.item_name').html(json[0].name)
				$('.item_details').html(json[0].menu_detail)
				$('.item_price').html('৳ '+json[0].price)

				$('.imagemodaltwo').modal('show');

				var btn_id=('addtocart-'+entity_id);

				var check_add_on=json[0].check_add_ons;

				if (check_add_on == 0){
					$('.customize').addClass('d-none');

					$("#btn_add").click(function(){
						checkCartRestaurant(entity_id,104,'',btn_id);
						$('#quotes-main-loader').hide();
						$('.imagemodaltwo').modal('hide');
					});
				}else{
					$('.item_price').html('Customize')
					$("#btn_add").click(function(){
						checkCartRestaurant(entity_id,104,'addons',btn_id);
						$('#quotes-main-loader').hide();
						$('.imagemodaltwo').modal('hide');
					});

				}


				//console.log(json[0].image)



			}
		});

	});




	$('#nav-icon2').click(function () {

		$('.ab').addClass('open');

	})
	var mybutton = document.getElementById("myBtn");


	// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function() {scrollFunction()};

	function scrollFunction() {
		if (document.body.scrollTop > 1000 || document.documentElement.scrollTop > 1000) {
			mybutton.style.display = "block";

		} else {
			mybutton.style.display = "none";
		}
	}

	function topFunction() {

		$('html, body').animate({
			scrollTop: $('#details_content').offset().top -190
		}, 500);


	}


	$('.search_dish').keyup(function (e) {

		if (e.keyCode===13){

			var searchDish = this.value;

			jQuery.ajax({
				type : "POST",
				dataType :"html",
				url : BASEURL+'restaurant/getSearchDish',
				data : {'restaurant_id':104,'searchDish':searchDish},
				beforeSend: function(){
					$('#quotes-main-loader').show();
				},
				success: function(response) {
					$('#details_content').html(response);
					$('#quotes-main-loader').hide();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});

		}


	});

	function best_deal() {

		if($('#filter_best_deal').is(':checked')){

			$('#quotes-main-loader').show();
			setTimeout(function() {

				$('#quotes-main-loader').hide();
			}, 500);
			// $('#quotes-main-loader').show();
			$('.bd').hide();

		}else{

			$('#quotes-main-loader').show();
			setTimeout(function() {

				$('#quotes-main-loader').hide();
			}, 500);
			$('.bd').show();

		}
	}


	$(window).scroll(function(e){
		var $el = $('.fixedElement');
		var isPositionFixed = ($el.css('position') == 'fixed');
		if ($(this).scrollTop() > 200 && !isPositionFixed){
			$el.css({'position': 'fixed', 'top': '0px'});
		}
		if ($(this).scrollTop() < 200 && isPositionFixed){
			$el.css({'position': 'static', 'top': '0px'});
		}
	});
</script>



<?php $this->load->view('footer');?>
