<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

	    <title><?php echo $page_title; ?></title>

	    <!-- SEO and SMO meta tags -->
	    <meta name="description" content="">
	    <meta name="keywords" content="">

	    <!-- Required Stylesheet -->
<!--    <link rel='stylesheet' href='--><?php //echo base_url(); ?><!--assets/front/css/font-awesome.min.css'>-->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/bootstrap-tagsinput.css">

		<link rel='stylesheet' href='<?php echo base_url(); ?>assets/front/css/animate.min.css'>
	    <link rel='stylesheet' href='<?php echo base_url(); ?>assets/front/css/owl.carousel.min.css'>
	    <!--  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"> -->
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/bootstrap.min.css" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/style.php" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/main.css" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/style.css" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/responsive.css" type="text/css">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Norican&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/flickity.min.css">

	    <!-- Required jQuery -->
		<script type="text/javascript" src="<?php echo base_url();?>assets/front/js/flickity.pkgd.min.js"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script type="text/javascript" src='<?php echo base_url(); ?>assets/front/js/jquery.min.js'></script>
	    <script type="text/javascript" src='<?php echo base_url(); ?>assets/front/js/wow.min.js'></script>
	    <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/popper.min.js" defer></script>
		 <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/bootstrap.min.js"></script> 
	    <script type="text/javascript" src='<?php echo base_url(); ?>assets/front/js/owl.carousel.min.js' defer></script>
	    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/jquery.validate.min.js"></script>
	    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/jquery.parallax-1.1.3.js"></script>
	    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/SmoothScroll.js"></script>
	    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/footer.min.js"  id='poco-handheld-footer-bar-js'></script>

	    <!-- Favicons -->
	    <link rel="shortcut icon"  sizes="40x40" href="<?php echo base_url();?>assets/admin/img/favicon.png"/>
	</head>
	<script>
	    var BASEURL = '<?php echo base_url();?>';
	    var USER_ID = '<?php echo $this->session->userdata('UserID'); ?>';
	    var IS_USER_LOGIN = '<?php echo $this->session->userdata('is_user_login'); ?>';
	    var SEARCHED_LAT = '<?php echo ($this->session->userdata('searched_lat'))?$this->session->userdata('searched_lat'):''; ?>';
	    var SEARCHED_LONG = '<?php echo ($this->session->userdata('searched_long'))?$this->session->userdata('searched_long'):''; ?>';
	    var SEARCHED_ADDRESS = '<?php echo ($this->session->userdata('searched_address'))?$this->session->userdata('searched_address'):''; ?>';
	    var ADD = '<?php echo $this->lang->line('add') ?>';
	    var ADDED = '<?php echo $this->lang->line('added') ?>';
	</script>
	<?php $lang_class = ($this->session->userdata('language_slug')) ? $this->session->userdata('language_slug') . '-lang' : 'en-lang';?>
	<?php $lang_slug = ($this->session->userdata('language_slug')) ? $this->session->userdata('language_slug') : 'en' ;
	$cmsPages = $this->common_model->getCmsPages($lang_slug);

	$slug=$this->db->select('restaurant_slug')->from('restaurant')->order_by('entity_id','asc')->limit(1)->get()->row()->restaurant_slug;
	$slug_soi=$this->db->select('restaurant_slug')->from('restaurant')->order_by('entity_id','asc')->limit(1)->get()->row()->restaurant_slug;
//	$content_id = $this->restaurant_model->getContentID($slug);
//	$restaurant_details = $this->restaurant_model->getRestaurantDetail($content_id->content_id);
	?>

	<body class="<?php echo $lang_class; ?>  " onclick="onBody()">

	<div class="loader-wrapper">
		<div class="spinner-grow spinner-grow-lg text-success" style="width: 8rem; height: 8rem;" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>
<!--	<div class="parallax" >-->

<?php
//$CI = & get_instance();
//$CI->load->model('restaurant_model');
//$content_id = $this->restaurant_model->getContentID($slug);

//echo '<pre>';print_r($content_id->content_id);exit();

//$restaurant_details=$this->db->get_where('restaurant_menu_item',array('restaurant_menu_item.status'=>1))->result_array();
//echo '<pre>';print_r($restaurant_details);exit();
//



?>
<!--	--><?php //if ($current_page != "Login" && $current_page != "Registration") { ?>

		<div class="manu-icon" >
			<form id="home_search_form" class="search-formd" style="display: none">
				<div class="form-group">

					<input class="search_menu head_search"  type="search" id="head_search" placeholder="  Search..." />
					<input class="search_menu d-none search_dish"  type="search" id="search_dish" placeholder="  Search..." />


				</div>
			</form>
			<ul>
<!--				<li class="--><?php //echo ($current_page == 'HomePage') ? 'current' : ''?><!--"><a href="--><?php //echo base_url()?><!--"><span class="fas fa-home"></span></a><span>Home</span></li>-->
				<li class="<?php echo ($current_page == 'RestaurantDetails') ? 'current' : ''?>"><a href="<?php echo base_url('restaurant/restaurant-detail/'.$slug);?>"> <span class="fas fa-bars"></span></a><span>Menu</span></li>
				<li class="<?php echo ($current_page == 'RestaurantDetails') ? 'current' : ''?>"><a href="<?php echo base_url('restaurant/restaurant-detail/'.$slug);?>"> <span class="fas fa-shopping-bag"></span></a><span>Takeway</span></li>
				<li class="footer_search"><a> <span class="fas fa-search"></span></a><span>Search</span></li>
				<li class="<?php echo ($current_page == 'RestaurantDetails') ? 'current' : ''?>"><a  href="<?php echo base_url('restaurant/restaurant-detail/'.$slug);?>"> <span class="fas fa-motorcycle"></span></a><span>Delivery</span></li>
				<li class="<?php echo ($current_page == 'EventBooking') ? 'current' : ''?>"><a href="<?php echo base_url() . 'restaurant/event-booking'; ?>"> <i class="iicon-icon-27 font-weight-bolder"></i></a><span>Dine In</span></li>
<!--				<li   class="--><?php //echo ($current_page == 'Cart') ? 'current' : ''?><!--"><a href="--><?php //echo base_url() . 'cart'; ?><!--"> <span class="fas fa-shopping-cart"></span><span class="manu_span cart_count " id="cart_count" >--><?php //echo $count; ?><!--</span></a><span>Cart</span></li>-->

			</ul>
		</div>
			<header class="header-area ">
				<div class="container">
					<div class="header-inner">
						<div class="logo">
							<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/front/images/logo.png" alt=""></a>
						</div>
						<nav class="navbar navbar-expand-lg ">
							<ul id="example-one" >
								<li ><a class="<?php echo ($current_page == 'HomePage') ? 'active_color' : ''; ?>" href="<?php echo base_url(); ?>"><span class="mobile-nav fas fa-home"></span> <?php echo $this->lang->line('home') ?></a></li>

								<?php if ($current_page == 'RestaurantDetails') { ?>
								<li class="nav-item dropdown mobile_menu">
<!--									nav-link dropdown-toggle //a class-->
									<a class="<?php echo ($current_page == 'RestaurantDetails') ? 'active_color' : ''; ?>"  href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="mobile-nav fas fa-bars"></span> 	Menu
									</a>
									<div class="dropdown-menu" aria-labelledby="navbarDropdown">
										<a class="dropdown-item " href="<?php echo base_url('restaurant/restaurant-detail/'.$slug) ?>"   >Main Menu</a>

										<?php foreach ($restaurant_details['categories'] as $key => $value) {?>

												<a class="dropdown-item " href="#"  id="category_id-<?php echo $value['category_id']; ?>" onclick="menuTopSearch(<?php echo $value['category_id']; ?>)"><?php echo $value['name']; ?></a>


										<?php }?>
									</div>
								</li>
								<?php } else {?>
									<li class="mobile_menu"><a <?php echo ($current_page == 'RestaurantDetails') ? 'active_color' : ''; ?> href="<?php echo base_url('restaurant/restaurant-detail/'.$slug) ?>"><span class="mobile-nav fa fa-bars"></span>  Menu</a></li>


								<?php } ?>

								<li id="pc_menu" class="pc_menu"><a  class="<?php echo ($current_page == 'RestaurantDetails') ? 'active_color' : ''; ?>" href="<?php echo base_url('restaurant/restaurant-detail/'.$slug) ?>"><span class="mobile-nav fas fa-bars"></span>  Menu</a></li>


								<li><a  class="<?php echo ($current_page == 'EventBooking') ? 'active_color' : ''; ?>" href="<?php echo base_url() . 'restaurant/event-booking'; ?>"><strong> <i class="mobile-nav iicon-icon-27"></i></strong>   Reservation</a></li>
								<?php if (!empty($cmsPages)) {
									foreach ($cmsPages as $key => $value) {
										if($value->CMSSlug == "contact-us") { ?>
											<li ><a  class="<?php echo ($current_page == 'ContactUs') ? 'active_color' : ''; ?>" href="<?php echo base_url() . 'contact-us'; ?>"><span class="mobile-nav fa fa-phone"></span>  <?php echo $this->lang->line('contact_us') ?></a></li>
										<?php }
										else if ($value->CMSSlug == "about-us") { ?>
											<li><a  class="<?php echo ($current_page == 'AboutUs') ? 'active_color' : ''; ?>" href="<?php echo base_url() . 'about-us'; ?>"><span class="mobile-nav fas fa-address-card"></span>  <?php echo $this->lang->line('about_us') ?></a></li>
										<?php }
									}
								} ?>
							</ul>
							<div class="header-right">
								<div class="noti-cart">
									<ul>
										<?php if ($this->session->userdata('is_user_login') && !empty($this->session->userdata('UserID'))) {
											$userUnreadNotifications = $this->common_model->getUsersNotification($this->session->userdata('UserID'),'unread');
											$notification_count = count($userUnreadNotifications);
											$userNotifications = $this->common_model->getUsersNotification($this->session->userdata('UserID')); ?>
											<li class="notification">
												<div id="notifications_list">
													<?php if (!empty($userNotifications)) { ?>
														<a href="javascript:void(0)" class="notification-btn"><i class="iicon-icon-01"></i><span class="notification_count"><?php echo $notification_count; ?></span></a>
														<div class="noti-popup">
															<div class="noti-title">
																<h5><?php echo $this->lang->line('notification') ?></h5>
																<div class="bell-icon">
																	<i class="iicon-icon-01"></i>
																	<span class="notification_count"><?php echo $notification_count; ?></span>
																</div>
															</div>
															<div class="noti-list">
																<?php if (!empty($userNotifications)) {
																    foreach ($userNotifications as $key => $value) {
																        if (date("Y-m-d", strtotime($value['datetime'])) == date("Y-m-d")) {
																            $noti_time = date("H:i:s") - date("H:i:s", strtotime($value['datetime']));
																            $noti_time = abs($noti_time) . ' '.$this->lang->line('mins_ago');
																        } else {
																            $d1 = strtotime(date("Y-m-d",strtotime($value['datetime'])));
																			$d2 = strtotime(date("Y-m-d"));

																			$noti_time = ($d2 - $d1)/86400;
																			$noti_time = ($noti_time > 1 )?$noti_time.' '.$this->lang->line('days_ago'):$noti_time.' '.$this->lang->line('day_ago');
																        }
																        ?>
																		<div class="noti-list-box">
																			<?php $view_class = ($value['view_status'] == 0)?'unread':'read'; ?>
																			<div class="noti-list-text <?php echo $view_class; ?>">
																				<h6><?php echo $this->session->userdata('userFirstname') . ' ' . $this->session->userdata('userLastname'); ?></h6>
																				<span class="min"><?php echo $noti_time; ?></span>
																				<h6><?php echo ($value['notification_type'] == "order")?$this->lang->line('orderid'):$this->lang->line('eventid'); ?>: #<?php echo $value['entity_id']; ?></h6>
																				<p><?php echo ($value['notification_slug'] == "event_cancelled")?$this->lang->line('event_cancelled_noti'):$this->lang->line($value['notification_slug']); ?></p>
																			</div>
																		</div>
																	<?php }
																}?>
															</div>
														</div>
													<?php }
													else { ?>
														<a href="javascript:void(0)" class="notification-btn"><i class="iicon-icon-01"></i><span>0</span></a>
														<div class="noti-popup">
															<div class="noti-title">
																<h5><?php echo $this->lang->line('notification') ?></h5>
																<div class="bell-icon">
																	<i class="iicon-icon-01"></i>
																	<span>0</span>
																</div>
															</div>
															<div class="viewall-btn">
																<a href="javascript:void(0)" class="btn"><?php echo $this->lang->line('no_notifications') ?></a>
															</div>
														</div>
													<?php }?>
												</div>
											</li>
										<?php }?>
									<?php $cart_details = get_cookie('cart_details');
										$cart_restaurant = get_cookie('cart_restaurant');
										$cart = $this->common_model->getCartItems($cart_details,$cart_restaurant);
										$count = count($cart['cart_items']); ?>
										<li class="">
											<form id="home_search_form" class="search-form">
												<div class="form-group">

													<input class="search head_search" type="search" id="head_search" placeholder="  Search..." />
													<input class="search search_dish d-none " type="search " id="search_dish" placeholder="  Search..." />


												</div>
											</form>
										</li>
										<li class="cart"><a href="<?php echo base_url() . 'cart'; ?>"><i class="iicon-icon-02"></i><span class="cart_count" id="cart_count"><?php echo $count; ?></span></a></li>



									</ul>
								</div>
								<div class="dropdown " style="display: none">
									<?php $language = $this->common_model->getLang($this->session->userdata('language_slug'));?>
							    	<button class="dropbtn"><img src="<?php echo base_url(); ?>assets/front/images/translate.png"><?php echo ($language) ? strtoupper($language->language_slug) : 'EN'; ?></button>
							    	<div class="dropdown-content">
										<?php $langs = $this->common_model->getLanguages();
    									foreach ($langs as $slug => $language) {?>
					                        <div onclick="setLanguage('<?php echo $language->language_slug ?>')"><a href="javascript:void(0)"><i class="glyphicon bfh-flag-<?php echo $language->language_slug ?>"></i><?php echo $language->language_name; ?>
					                        </a></div>
					                    <?php }?>
					                </div>
				                </div>
								<?php if ($this->session->userdata('is_user_login')) {?>
									<div class="header-user">
										<div class="user-img">
											<?php

											$ses_image=$this->session->userdata('userImage');
											$sc_image=$this->session->userdata('social_image');



											$image = ((!empty($sc_image)) ? $sc_image : (!empty($sc_image) ) ? $sc_image : $ses_image);
											?>

<!--											--><?php //$image = ($this->session->userdata('userImage')) ? ($this->session->userdata('userImage')) :($this->session->userdata('social_image')) ? ($this->session->userdata('social_image')) : default_img; ?>
<!--											--><?php //$image = ($this->session->userdata('userImage')) ? ( $this->session->userdata('userImage')) :($this->session->userdata('social_image')) ? ($this->session->userdata('social_image')): (base_url() . 'assets/front/images/user-login.jpg');?>
											<img src="<?php echo $image; ?>">
										</div>

										<?php

										$firstName=$this->session->userdata('userFirstname');
										$lastName=$this->session->userdata('userLastname');

										$first_word = preg_split("/[\s,&_-]+/", $firstName);
										$last_word = preg_split("/[\s,&_-]+/", $lastName);
										$first_new_words = "";
										$last_new_words = "";

										foreach ($first_word as $w) {
											$first_new_words .= $w[0];
										}

										foreach ($last_word as $w) {
											$last_new_words .= $w[0];
										}


										?>
										<span class="user-menu-btn"><?php echo $first_new_words.$last_new_words; ?></span>
										<div class="header-user-menu">
											<ul>
												<li class="active"><a href="<?php echo base_url() . 'myprofile'; ?>"><i class="iicon-icon-31"></i><?php echo $this->lang->line('my_profile') ?></a></li>
												<li onclick="logout();"><a href="javascript:void(0)"><i class="iicon-icon-32"></i><?php echo $this->lang->line('logout') ?></a></li>
											</ul>
										</div>
									</div>
								<?php } else {?>
									<div class=" signin-btn">
										<a href="<?php echo base_url() . 'home/login'; ?>" class="common "><i class="iicon-icon-32 "></i> <?php echo $this->lang->line('sign_in') ?></a>
									</div>
								<?php }?>

								<?php $closed = ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'closed':''; ?>
<!--								--><?php //$closed = ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'closed':''; ?>

								<?php if ($closed == 'closed') {?>
								<div class="header-user open_closed  <?php echo $closed; ?>">

									<div class="user-img">
										<img src="<?php echo base_url(); ?>assets/front/images/close.jpeg">
									</div>




<!--									<a href="" class="common btn btn-danger">Closed</a>-->
								</div>
								<?php }else{?>
								<div class="header-user open_closed  <?php echo $closed; ?>">
									<div class="user-img">
										<img src="<?php echo base_url(); ?>assets/front/images/open.jpeg">
									</div>



<!--									<a href="" class="common btn ">Open</a>-->
								</div>
								<?php } ?>

<!--									<div class="openclose --><?php //echo $closed; ?><!--">--><?php //echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'Closed Now':$this->lang->line('open'); ?><!--</div>-->



								<div class="mobile-icon">
									<button class="" id="nav-icon2"></button>
								</div>

							</div>
						</nav>
					</div>
				</div>

<!--				--><?php //if ($current_page == 'HomePage')  {?>
<!--					<div class="  takeway  " >-->
<!--						<ul class="mb-2" style="align-items:center;justify-content: center ;color: white;">-->
<!---->
<!--							<a class="three_button" href="--><?php //echo base_url('restaurant/restaurant-detail/'.$slug_soi);?><!--"><li class=" --><?php //echo ($current_page == 'ContactUs1') ? 'li_sec' : 'li_bg'; ?><!--" ><span style="font-size: 12px"  class="fas fa-check"></span><strong class="span_text"> Takeway</strong></li></a>-->
<!--							<a class="three_button" href="--><?php //echo base_url('restaurant/restaurant-detail/'.$slug_soi);?><!--"><li class="--><?php //echo ($current_page == 'RestaurantDetails') ? 'li_sec' : 'li_bg'; ?><!--" ><span style="font-size: 12px " class="fas fa-check"></span><strong  class="span_text"> Delivery</strong></li></a>-->
<!--							<a class="three_button" href="--><?php //echo base_url() . 'restaurant/event-booking'; ?><!--"><li class="--><?php //echo ($current_page == 'ContactUs3') ? 'li_sec' : 'li_bg'; ?><!--" ><span style="font-size: 12px " class="fas fa-check"></span><strong class="span_text"> Dine In</strong></li></a>-->
<!---->
<!--						</ul>-->
<!--					</div>-->
<!--				--><?php //} ?>





			</header>
<!--		--><?php //}?>

		<div class="search_result d-none " >
			<div class="container">

				<div class="details_content">

				</div>
			</div>
		</div>


	<script>

		$('.search').click(function() { $('.search').toggleClass('expanded'); });

		$('.footer_search').click(function() {

			$(".search-formd").fadeToggle('slow');

		});

		$('.head_search').keyup(function (e) {

			if (e.keyCode===13){

				var searchDish = this.value;

				// alert('Hello');
				// return

				jQuery.ajax({
					type : "POST",
					dataType :"html",
					url : BASEURL+'restaurant/getSearchDish',
					data : {'restaurant_id':104,'searchDish':searchDish},
					beforeSend: function(){
						$('#quotes-main-loader').show();

					},
					success: function(response) {

						// $('#quotes-main-loader').hide();

						 // console.log(response)
						//
						// return
						// $('#quotes-main-loader').hide();
						$('.search_result').removeClass('d-none')
						  $('.details_content').html(response);

						// $('#details_content').html(response);

							//
							$('section').hide();

							$('.hero').addClass('d-none');

							// $('.quick-searches').hide();
							// $('.restaurant-app').hide();
							// $('.driver-app').hide();
							// $('.cp').addClass('d-none');



					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert(errorThrown);
					}
				});

			}


		});
	</script>
		<script type="text/javascript">
			$(window).on("load",function(){
				$(".loader-wrapper").fadeOut("slow");
			});
			$(document).on('ready', function() {

				var count = '<?php echo count($cart_details['cart_items']); ?>';
				$('#cart_count').html(count);

				$(window).keydown(function(event){
					if(event.keyCode == 13) {
						event.preventDefault();
						return false;
					}
				});
			});

			function myFunction(x) {
				if (x.matches) {
					$('#pc_menu').hide();
					$('.mobile_menu').show();
				} else {
					$('.mobile_menu').hide();
					$('.pc_menu').show();
				}
			}

			var x = window.matchMedia("(max-width: 1200px)")
			myFunction(x) // Call listener function at run time
			x.addListener(myFunction)
		</script>
