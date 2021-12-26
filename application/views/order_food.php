<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); ?>
<?php $minimum_range = 0;
$maximum_range = 50000; ?>
<section class="inner-banner order-food-banner">
	<div class="container">
		<div class="inner-pages-banner">
			<h1><?php echo $this->lang->line('food_on_fingertips') ?></h1>
			<form id="order_food_form" class="inner-pages-form">
				<div class="form-group delivery-address">	
					<input type="text" name="address" id="address" onFocus="geolocate('order_food')" placeholder="<?php echo $this->lang->line("delivery_address"); ?>" value="">	
					<input type="hidden" name="latitude" id="latitude" value="">
					<input type="hidden" name="longitude" id="longitude" value="">	
				</div>
				<div class="form-group search-restaurant">
					<input type="text" name="resdishes" id="resdishes" value="" placeholder="<?php echo $this->lang->line('search_res_dishes') ?>">
					<input type="button" name="Search" value="<?php echo $this->lang->line('search'); ?>" class="btn" onclick="fillInAddress('order_food')">
				</div>
			</form>
		</div>
	</div>
</section>

<section class="inner-pages-section order-food-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="heading-title">
					<h2><?php echo $this->lang->line('select_fav_res') ?></h2>
				</div>
			</div>
			<div class="col-md-5 col-lg-3">
				<div class="food-filter">
					<div class="filter-title-main">
						<h5><?php echo $this->lang->line('filter') ?></h5>
					</div>
					<div class="filter-box-main">
						<div class="filter-box">
							<h6><?php echo $this->lang->line('by_food_type') ?></h6>
							<div class="filter-checkbox">
								<div class="checkbox-box">
									<label>
										<input type="checkbox" name="food_veg" id="food_veg" value="1" onchange="getFavouriteResturants()">
										<span><i class="iicon-icon-15 veg"></i><?php echo $this->lang->line('veg') ?></span>
									</label>
								</div>
								<div class="checkbox-box">
									<label>
										<input type="checkbox" name="food_non_veg" id="food_non_veg" value="1" onchange="getFavouriteResturants()">
										<span><i class="iicon-icon-15 non-veg"></i><?php echo $this->lang->line('non_veg') ?></span>
									</label>
								</div>
							</div>
						</div>
						<div class="filter-box" id="distance_filter">
							<h6><?php echo $this->lang->line('by_distance') ?></h6>
							<div class="distance-slider">
								<div id="slider-range"></div>
							    <div class="distance-value value01"><span id="slider-range-value1"></span></div>
							    <div class="distance-value value02"><span id="slider-range-value2"></span></div>
							    <input type="hidden" name="minimum_range" id="minimum_range" class="form-control" value="<?php echo $minimum_range; ?>" />
							    <input type="hidden" name="maximum_range" id="maximum_range" class="form-control" value="<?php echo $maximum_range; ?>" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-7 col-lg-9">
				<div class="row restaurant-box-row" id="order_from_restaurants">
					<?php if (!empty($restaurants)) {
						foreach ($restaurants as $key => $value) { ?>
							<div class="col-lg-6">
								<div class="restaurant-box">
									<div class="popular-rest-box">
										<div class="popular-rest-img">
											<a href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>"><img src="<?php echo ($value['image'])?$value['image']:default_img;?>" alt="<?php echo $value['name']; ?>"></a>
											<div class="openclose <?php echo ($value['timings']['closing'] == "Closed")?"closed":""; ?>"> <?php echo ($value['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?> </div>
											 <!-- <?php //echo $value['timings']['closing']; ?> -->
											<?php echo ($value['ratings'] > 0)?'<strong>'.$value['ratings'].'</strong>':'<strong class="newres">'. $this->lang->line("new") .'</strong>'; ?> 
										</div>
										<div class="popular-rest-content">
											<h3><?php echo $value['name']; ?></h3>
											<div class="popular-rest-text">
												<p class="address-icon"><?php echo $value['address']; ?> </p>
												<div class="order-btn">
													<a href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>" class="btn"><?php echo $this->lang->line('order') ?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="col-sm-12 col-md-12 col-lg-12">
							<div class="pagination" id="#pagination"><?php echo $PaginationLinks; ?></div>
						</div>
					<?php } 
					else { ?>
						<div class="no-found"><h4><?php echo $this->lang->line('no_such_res_found') ?></h4></div>
					<?php } ?>
				</div>
			</div>				
		</div>
	</div>
</section>

<script type="text/javascript" src='<?php echo base_url();?>assets/front/js/range-slider.js'></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGh2j6KRaaSf96cTYekgAD-IuUG0GkMVA&libraries=places"></script>
<script type="text/javascript">
$(document).on('ready', function() { 
	initAutocomplete('address');
  	$('#distance_filter').hide();
	// auto detect location if even searched once.
	if (SEARCHED_LAT == '' && SEARCHED_LONG == '' && SEARCHED_ADDRESS == '') {
		getLocation('order_food');
	}
	else
	{
		getSearchedLocation(SEARCHED_LAT,SEARCHED_LONG,SEARCHED_ADDRESS,'order_food');
	}
});

// pagination function
function getData(page=0, noRecordDisplay=''){
	var food_veg = ($('#food_veg').is(":checked"))?1:0;
	var food_non_veg = ($('#food_non_veg').is(":checked"))?1:0;
	var resdishes = $('#resdishes').val();
	var latitude = $('#latitude').val();
	var longitude = $('#longitude').val();
	var minimum_range = $('#minimum_range').val();
	var maximum_range = $('#maximum_range').val();
	var page = page ? page : 0;
	$.ajax({
		url: "<?php echo base_url().'restaurant/ajax_restaurants'; ?>/"+page,
		data : {'latitude':latitude,'longitude':longitude,'resdishes':resdishes,'page':page,'minimum_range':minimum_range,'maximum_range':maximum_range,'food_veg':food_veg,'food_non_veg':food_non_veg},
		type: "POST",
		success: function(result){
			$('#order_from_restaurants').html(result);
			/*$('html, body').animate({
		        scrollTop: $("#order_from_restaurants").offset().top
		    }, 800);*/
		}
	});
}
</script>

<?php $this->load->view('footer'); ?>
