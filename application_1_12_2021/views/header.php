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
	    <link rel='stylesheet' href='<?php echo base_url(); ?>assets/front/css/animate.min.css'>
	    <link rel='stylesheet' href='<?php echo base_url(); ?>assets/front/css/owl.carousel.min.css'> 
	    <!--  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"> -->
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/bootstrap.min.css" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/style.php" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/main.css" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/style.css" type="text/css">
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/front/css/responsive.css" type="text/css">

	    <!-- Required jQuery -->
	     <script type="text/javascript" src='<?php echo base_url(); ?>assets/front/js/jquery.min.js'></script> 
	    <script type="text/javascript" src='<?php echo base_url(); ?>assets/front/js/wow.min.js'></script>
	    <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/popper.min.js" defer></script>
		 <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/bootstrap.min.js"></script> 
	    <script type="text/javascript" src='<?php echo base_url(); ?>assets/front/js/owl.carousel.min.js' defer></script>
	    <script type="text/javascript" src="<?php echo base_url();?>assets/front/js/jquery.validate.min.js"></script>

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
	$cmsPages = $this->common_model->getCmsPages($lang_slug);  ?>
	<body class="<?php echo $lang_class; ?>">
		<?php if ($current_page != "Login" && $current_page != "Registration") { ?>
			<header class="header-area">
				<div class="container">
					<div class="header-inner">
						<div class="logo">
							<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/front/images/logo.png" alt=""></a>
						</div>
						<nav>
							<ul id="example-one">
								<li class="<?php echo ($current_page == 'HomePage') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('home') ?></a></li>
								<li class="<?php echo ($current_page == 'OrderFood') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'restaurant'; ?>"><?php echo $this->lang->line('order_food') ?></a></li>
								<li class="<?php echo ($current_page == 'Recipe') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'recipe'; ?>"><?php echo $this->lang->line('recipies') ?></a></li>
								<li class="<?php echo ($current_page == 'EventBooking') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'restaurant/event-booking'; ?>"><?php echo $this->lang->line('event_booking') ?></a></li>
								<?php if (!empty($cmsPages)) {
									foreach ($cmsPages as $key => $value) { 
										if($value->CMSSlug == "contact-us") { ?>
											<li class="<?php echo ($current_page == 'ContactUs') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'contact-us'; ?>"><?php echo $this->lang->line('contact_us') ?></a></li>
										<?php }
										else if ($value->CMSSlug == "about-us") { ?>
											<li class="<?php echo ($current_page == 'AboutUs') ? 'current_page_item' : ''; ?>"><a href="<?php echo base_url() . 'about-us'; ?>"><?php echo $this->lang->line('about_us') ?></a></li>
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
										<li class="cart"><a href="<?php echo base_url() . 'cart'; ?>"><i class="iicon-icon-02"></i><span id="cart_count"><?php echo $count; ?></span></a></li>
									</ul>
								</div>
								<div class="dropdown">
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
											<?php $image = ($this->session->userdata('userImage')) ? (base_url() . 'uploads/' . $this->session->userdata('userImage')) : (base_url() . 'assets/front/images/user-login.jpg');?>
                        					<img src="<?php echo $image; ?>">
										</div>
										<span class="user-menu-btn"><?php echo $this->session->userdata('userFirstname'); ?></span>
										<div class="header-user-menu">
											<ul>
												<li class="active"><a href="<?php echo base_url() . 'myprofile'; ?>"><i class="iicon-icon-31"></i><?php echo $this->lang->line('my_profile') ?></a></li>
												<li onclick="logout();"><a href="javascript:void(0)"><i class="iicon-icon-32"></i><?php echo $this->lang->line('logout') ?></a></li>
											</ul>
										</div>
									</div>
								<?php } else {?>
									<div class="signin-btn">
										<a href="<?php echo base_url() . 'home/login'; ?>" class="btn"><?php echo $this->lang->line('sign_in') ?></a>
									</div>
								<?php }?>
								<div class="mobile-icon">
									<button class="" id="nav-icon2"></button>
								</div>
							</div>
						</nav>
					</div>
				</div>
			</header>
		<?php }?>