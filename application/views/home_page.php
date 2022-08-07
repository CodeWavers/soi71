<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); ?>

<style>
	/*body, html{*/
	/*	height:100%;*/
	/*	padding:0;*/
	/*	margin:0;*/
	/*	background-size: cover;*/
	/*}*/
	.hero {
		position: relative;
		/*z-index: 1;*/
		top: 0;
		bottom:  0;
		left: 0;
		right: 0;

		/*background: url('https://cdn.pixabay.com/photo/2021/12/05/21/39/christmas-balls-6848782_960_720.jpg');*/
		/*background-size: cover;*/
		height:100%;
		width: 100%;

		background-repeat:no-repeat;
		background-size:contain;
		background-position:center;
		background-color: white;

		/*padding: 8rem 0 50%;*/
		text-transform: uppercase;
		opacity: 1.0;
		-webkit-transition: background 1.5s linear;
		-moz-transition: background 1.5s linear;
		-o-transition: background 1.5s linear;
		-ms-transition: background 1.5s linear;
		transition: background 1.5s linear;
	}

	.hero:before {
		content: '';
		/*height:80vh;*/
		/*width: 100%;*/
		/*height: 80vh;*/
		/*width: 100vw;*/
		/*padding: 10rem 0 13rem;*/
		position: absolute;opacity: 1.0;
		-webkit-transition: background 1.5s linear;
		-moz-transition: background 1.5s linear;
		-o-transition: background 1.5s linear;
		-ms-transition: background 1.5s linear;
		transition: background 1.5s linear;

	}



	/*@media only screen and (max-width: 767px) { html,body {*/
	/*	max-width: 767px !important;*/
	/*}*/



</style>



<section>
	<div class="hero">
	<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
		<ol class="carousel-indicators">
			<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
			<li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
		</ol>


		<div class="carousel-inner" >
			<div class="carousel-item active">
				<img class="d-block w-100" src="<?= base_url('uploads/'.$slider[0]['image']) ?>" >
			</div>
			<div class="carousel-item ">
				<img class="d-block w-100" src="<?= base_url('uploads/'.$slider[1]['image']) ?>" >
			</div>

			<div class="carousel-item">
				<img class="d-block w-100" src="<?= base_url('uploads/'.$slider[2]['image']) ?>" >
			</div>
			<div class="carousel-item">
				<img class="d-block w-100" src="<?= base_url('uploads/'.$slider[3]['image']) ?>" >
			</div>
		</div>
<!--		--><?php //echo '<pre>';print_r($slider[0]['image']);exit(); ?>
		<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	</div>
<!--	<div class="hero "></div>-->



<!--	--><?php //if (!empty($coupons)) { ?>
<!---->
<!--			<div class="container " style="margin-top:150px;" id="hahha">-->
<!--				<div class="heading-title">-->
<!--					<h2 style="color: whitesmoke">--><?php //echo $this->lang->line('latest_coupons'); ?><!--</h2>-->
<!--					<div class="slider-arrow">-->
<!--						<div id="customNav2" class="arrow"></div>-->
<!--					</div>-->
<!--				</div>-->
<!---->
<!---->
<!---->
<!---->
<!---->
<!--					<div class="row col-*" style="justify-content: center">-->
<!--						--><?php //foreach ($coupons as $key => $value) { ?>
<!--						<div class="col-sm-12 col-md-6 col-lg-3  coupon"  >-->
<!---->
<!--							<a href="--><?php //echo base_url('restaurant')?><!--"><img  class="card-img-top coupon_image" src="--><?php //echo ($value->image)?base_url().'uploads/'.$value->image:default_img;?><!--" alt="Coupon" ></a>-->
<!---->
<!--								<h1 class=" title font-weight-bold " >--><?php //echo $value->name?><!--</h1>-->
<!--								<h4 class=" des font-weight-bold " >--><?php //echo $value->description?><!--</h4>-->
<!---->
<!--						</div>-->
<!--						--><?php //} ?>
<!---->
<!--					</div>-->
<!---->
<!---->
<!---->
<!---->
<!---->
<!---->
<!--			</div>-->
<!---->
<!--	--><?php //} ?>
</section>




<section class="cp" >
	<?php if (!empty($coupons)) { ?>

		<div class="container " style="" id="hahha">






			<div class="row col-*" style="justify-content: center">
				<?php foreach ($coupons as $key => $value) { ?>
					<div class="col-sm-12 col-md-6 col-lg-3  coupon"  >

						<a href="<?php echo base_url('restaurant')?>"><img  class="card-img-top coupon_image" src="<?php echo ($value->image)?base_url().'uploads/'.$value->image:default_img;?>" alt="Coupon" ></a>

						<h1 class=" title font-weight-bold " ><?php echo $value->name?></h1>
						<h4 class=" des font-weight-bold " ><?php echo $value->description?></h4>

					</div>
				<?php } ?>

			</div>






		</div>

	<?php } ?>
</section>


<div class="modal modal-main" id="myModal"></div>


<?php if(!$popular_data) { ?>
<section class="quick-searches"  style="margin-top: 10%">
	<div class="container">
		<div class="heading-title">
			<h2>Popular Items</h2>

		</div>



		<div class="home-items">

			<?php  foreach ($popular_data as $ds){ ?>

					<?php foreach ($ds['menu_items'] as $key => $value) { ?>


							<div class="home-menu-card ">
								<div class="home-menu-image"  onclick="image_show(<?php echo ($value['entity_id']) ?>)">
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



		</div>

</section>
<?php } ?>
<section class="quick-searches" style="margin-top: 5%" >
	<div class="container" style="margin-top: 5%">
		<div class="heading-title">
			<h2>Best Deals</h2>
<!--			<div class="slider-arrow">-->
<!--				<div id="customNav" class="arrow"></div>-->
<!--			</div>-->
		</div>


	<!-- Flickity HTML init -->
		<div class="carousel " data-flickity='{ "groupCells": true }'>






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
					<div class="carousel-cell">
							<div class="home-menu-card " style="height: 340px">
								<div class="home-menu-image"  onclick="image_show(<?php echo ($value['entity_id']) ?>)">
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
					</div>

						<?php }
					}?>

				<?php }?>
			<?php }?>



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
<section class="quick-searches" style="display: none">
	<div class="container">
		<div class="heading-title">
			<h2><?php echo $this->lang->line('quick_search'); ?></h2>
			<div class="slider-arrow">
				<div id="customNav" class="arrow"></div>
			</div>
		</div>
		<div class="quick-searches-slider owl-carousel">
			<?php if (!empty($categories)) {
				foreach ($categories as $key => $value) { ?>
					<div class="quick-searches-box" onclick="quickSearch(<?php echo $value->entity_id; ?>)">
						<img src="<?php echo ($value->image)?base_url().'uploads/'.$value->image:default_img;?>" alt="Chinese">
						<h5><?php echo $value->name ?></h5>
					</div>
				<?php }
			} ?>
		</div>
	</div>

</section>


<div class="modal fade imagemodaltwo" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" >
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title item_name" id="myModalLabel">Image preview</h4>
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-4 display-no" id="img1Div">
						<div class="thumbnail coupon ">

							<img id="image1" src="" alt="Lights" style="width:100%">
							<div class="det-with-price">

								<!--										<strong>--><?php //echo ($value['check_add_ons'] != 1) ? $restaurant_details['restaurant'][0]['currency_symbol'] . ' ' . $value['price'] : ''; ?><!--</strong>-->
							</div>

						</div>


					</div>
					<div class="col-md-4 display-no" id="img2Div">
						<div class="thumbnail coupon">

							<img id="image2" src="" alt="Lights" style="width:100%">


						</div>
					</div>
					<div class="col-md-4 display-no" id="img3Div">
						<div class="thumbnail coupon">

							<img id="image3" src="" alt="Lights" style="width:100%">


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
<section class="secondary_footer">

	<?php
	$business_timings =unserialize(json_decode($timings));

	?>
<div class="row col-*">

<div class="col-sm-12 col-md-6 col-lg-6 ">
	<div class=" text-center" style="width: 100%;">
		<div class="card-body">
			<h5 class="card-title s-title">Opening Hours</h5><hr>
			<div class="row col-* " >
				<div class="col-6 secodary_footer_day" >
					<p class="card-text s-day">Saturday</p>
				</div>
				<div class="col-6 secodary_footer_time">
					<p class="card-text s-time"><?php echo $business_timings['saturday']['open']; ?> to <?php echo @$business_timings['saturday']['close']; ?></p>

				</div>


			</div>

			<div class="row col-* " >
				<div class="col-6 secodary_footer_day" >
					<p class="card-text s-day">Sunday</p>
				</div>
				<div class="col-6 secodary_footer_time">
					<p class="card-text s-time"><?php echo $business_timings['sunday']['open']; ?> to <?php echo @$business_timings['sunday']['close']; ?></p>

				</div>


			</div>
			<div class="row col-* " >
				<div class="col-6 secodary_footer_day" >
					<p class="card-text s-day">Monday</p>
				</div>
				<div class="col-6 secodary_footer_time">
					<p class="card-text s-time"><?php echo $business_timings['monday']['open']; ?> to <?php echo @$business_timings['monday']['close']; ?></p>

				</div>


			</div>
			<div class="row col-* " >
				<div class="col-6 secodary_footer_day" >
					<p class="card-text s-day">Tuesday</p>
				</div>
				<div class="col-6 secodary_footer_time">
					<p class="card-text s-time"><?php echo $business_timings['tuesday']['open']; ?> to <?php echo @$business_timings['tuesday']['close']; ?></p>

				</div>


			</div>
			<div class="row col-* " >
				<div class="col-6 secodary_footer_day" >
					<p class="card-text s-day">Wednesday</p>
				</div>
				<div class="col-6 secodary_footer_time">
					<p class="card-text s-time"><?php echo $business_timings['wednesday']['open']; ?> to <?php echo @$business_timings['wednesday']['close']; ?></p>

				</div>


			</div>
			<div class="row col-* " >
				<div class="col-6 secodary_footer_day" >
					<p class="card-text s-day">Thursday</p>
				</div>
				<div class="col-6 secodary_footer_time">
					<p class="card-text s-time"><?php echo $business_timings['thursday']['open']; ?> to <?php echo @$business_timings['thursday']['close']; ?></p>

				</div>


			</div>

			<div class="row col-* " >
				<div class="col-6 secodary_footer_day" >
					<p class="card-text s-day">Friday</p>
				</div>
				<div class="col-6 secodary_footer_time">
					<p class="card-text s-time"><?php echo $business_timings['friday']['open']; ?> to <?php echo @$business_timings['friday']['close']; ?></p>

				</div>


			</div>


		</div>
	</div>
</div>

<div class="col-sm-12 col-md-6 col-lg-6 ">
	<div class=" text-center" style="width: 100%;">
		<div class="card-body " id="facebook_page">
			<h5 class="card-title s-title">Facebook Page</h5><hr>
<!---->
<!--			--><?php //foreach ($delivery_area as $da) {?>
<!--			<div class="row col-* " >-->
<!--				<div class="col-6 secodary_footer_day" >-->
<!--					<p class="card-text s-day">--><?php //echo $da->name?><!--</p>-->
<!--				</div>-->
<!--				<div class="col-6 secodary_footer_time">-->
<!--					<p class="card-text s-time">৳ --><?php //echo $da->delivery_charge?><!--</p>-->
<!---->
<!--				</div>-->
<!---->
<!---->
<!--			</div>-->
<!---->
<!--			--><?php //} ?>

			<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fsoi71&tabs=timeline&width=400&height=300&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="400" height="300" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
		</div>
	</div>
</div>

</div>
<!--	<div class="rest-detail-main">-->
<!--		<div class="container">-->
<!--			<div class="row">-->
<!--				<div class="col-lg-12">-->
<!--					<div class="rest-detail_s">-->
<!--						<div class="rest-detail-img-main">-->
<!--							<div class="rest-detail-img">-->
<!--								<img src="--><?php //echo ($restaurant_details['restaurant'][0]['image'])?$restaurant_details['restaurant'][0]['image']:default_img;?><!--">-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="rest-detail-content">-->
<!---->
<!--							<p><i class="iicon-icon-20"></i>--><?php //echo $restaurant_details['restaurant'][0]['address']; ?><!--</p>-->
<!--							<ul class="">-->
<!--								<li><i class="iicon-icon-29"></i>--><?php //echo $restaurant_details['restaurant'][0]['name']; ?><!--</li>-->
<!--								<li><i class="iicon-icon-05"></i>--><?php //echo ($restaurant_details['restaurant'][0]['ratings'] > 0)?$restaurant_details['restaurant'][0]['ratings']:'<strong class="newres">'. $this->lang->line("new") .'</strong>'; ?><!--</li>-->
<!--								<li><i class="iicon-icon-18"></i>--><?php //echo $restaurant_details['restaurant'][0]['timings']['open'].'-'.$restaurant_details['restaurant'][0]['timings']['close']; ?><!--</li>-->
<!--								<li><i class="iicon-icon-19"></i>--><?php //echo $restaurant_details['restaurant'][0]['phone_number']; ?><!--</li>-->
<!---->
<!---->
<!--							</ul>-->
<!---->
<!---->
<!--						</div>-->
<!---->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->


</section>

<!--<section class="restaurant-app quick-searches "  >-->
<!--	<div class="container">-->
<!--		<div class="">-->
<!--			<div class="row">-->
<!---->
<!--				<div class="col-md-6 col-sm-12">-->
<!--					<div class="">-->
<!--						<div class="heading-title-02">-->
<!--							<h4>--><?php //echo $this->lang->line('welcome_to') ?><!-- <br><span>--><?php //echo $this->lang->line('site_title'); ?><!-- --><?php //echo $this->lang->line('res_app') ?><!--</span></h4>-->
<!--						</div>-->
<!--						<p>--><?php //echo $this->lang->line('site_title'); ?><!-- --><?php //echo $this->lang->line('home_text1') ?><!--</p>-->
<!--						<div class="">-->
<!---->
<!--							<a href="https://play.google.com/store/apps/details?id=com.gjc&hl=en"><img src="--><?php //echo base_url();?><!--assets/front/images/app-store.png" alt="App store"></a>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--</section>-->


<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGh2j6KRaaSf96cTYekgAD-IuUG0GkMVA&libraries=places"></script>
<script>
$(document).on('ready', function() {
	var owl = $('.owl-carousel');
	owl.owlCarousel({
		items:4,
		loop:true,
		margin:10,
		autoplay:true,
		autoplayTimeout:1000,
		autoplayHoverPause:true
	});
	initAutocomplete('address');
	// auto detect location if even searched once.
	if (SEARCHED_LAT == '' && SEARCHED_LONG == '' && SEARCHED_ADDRESS == '') {
		getLocation('home_page');
	}
	else
	{
		getSearchedLocation(SEARCHED_LAT,SEARCHED_LONG,SEARCHED_ADDRESS,'home_page');
	}

	$(window).keydown(function(event){
		if(event.keyCode == 13) {
		  event.preventDefault();
		  return false;
		}
	});


});


$(window).scroll(function(e){
	//parallax();
});

function parallax(){
	var scrolled = $(window).scrollTop();
	// $('.hero').css('background-image',-(scrolled*-0.1)+'rem');
	// $('.hero').css('background-image',1-(scrolled*.00175)+'rem');
	$('.hero').css('top',-(scrolled*0.0444)+'rem');
	$('.hero').css('opacity',1-(scrolled*.00175)+'rem');
	$('.hero > div > div > h1').css('top',-(scrolled*-0.1)+'rem');
	$('.hero > div > div > h1').css('opacity',1-(scrolled*.00175));
	$('.hero > div > div > p').css('top',-(scrolled*-0.1)+'rem');
	$('.hero > div > div > p').css('opacity',1-(scrolled*.00175));
}




//Array of images which you want to show: Use path you want.
// var images=new Array('Assets/BGImages/head_sandwichman1.jpg','Assets/BGImages/head_sandwichman2.jpg','Assets/BGImages/head_sandwichman3.jpg');
var images=new Array("<?= base_url('assets/front/images/sl1.jpg') ?>","<?= base_url('assets/front/images/sl2.jpg') ?>","<?= base_url('assets/front/images/sl3.jpg') ?>","<?= base_url('assets/front/images/sl4.jpg') ?>");
var nextimage=0;

	// doSlideshow();



function doSlideshow(){
	if(nextimage>=images.length){nextimage=0;}
	$('.hero')
			.css('background-image','url("'+images[nextimage++]+'")')


			.slide(100,function(){
				setTimeout(doSlideshow,5000);

			});



}

function image_show(entity_id){


	var base_url=$('#base_url').val();
	var default_im="<?php echo default_img?>"
	//	alert(base_url)

	$.ajax({
		url: base_url + "restaurant/find_all_image",
		type: 'post',
		data: {entity_id: entity_id},
		success: function(response){

			var json=JSON.parse(response)

			if (json[0].image ){
				$('#img1Div').removeClass('display-no');

				$('#image1').attr('src', base_url+'uploads/'+json[0].image);
			}else{
				$('#image1').attr('src', default_im);
			}

			if (json[0].image2 ){
				$('#img2Div').removeClass('display-no');

				$('#image2').attr('src', base_url+'uploads/'+json[0].image2);
			}else{
				$('#image2').attr('src', default_im);
			}

			if (json[0].image3 ){
				$('#img3Div').removeClass('display-no');

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

}




</script>

<script type="text/javascript">
	$(document).on('ready', function() {

		var count = '<?php echo count($cart_details['cart_items']); ?>';
		$('.cart_count').html(count);

		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				return false;
			}
		});
	});


	function homeCartUpdate() {
		$.ajax({
			url: '<?php echo base_url('home/get_cart_item_no') ?>',
			type: 'POST',
			success: function(n) {
				$('#cart_count').html(parseInt(n));

			}
		})
	}
</script>

<?php $this->load->view('footer');?>
