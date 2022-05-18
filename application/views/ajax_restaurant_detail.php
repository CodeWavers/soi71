<?php $menu_ids = array();
if (!empty($menu_arr)) {
	$menu_ids = array_column($menu_arr, 'menu_id');
}
if (!empty($restaurant_details['menu_items']) && !empty($restaurant_details['categories'])) {
	if (!empty($restaurant_details['menu_items'])) {
	    $popular_count = 0;
	    foreach ($restaurant_details['menu_items'] as $key => $value) {
	        if ($value['popular_item'] == 1) {
	            $popular_count = $popular_count + 1;
	        }
	    }
	    if ($popular_count > 0) { ?>
			<div class="heading-title">
				<h2>Best Deals</h2>
				<div class="slider-arrow">
					<div id="customNav" class="arrow"></div>
				</div>
			</div>
			<div class="home-items">

				<?php foreach ($restaurant_details['menu_items'] as $key => $value) {
					if ($value['popular_item'] == 1) { ?>
						<div class="home-menu-card">
							<div class="home-menu-image hover" data-id="<?php echo ($value['entity_id']) ?>">
								<img class="" src="<?php echo ($value['image']) ? ($value['image']) : (default_img); ?>">

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
												<?php $add = (in_array($value['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
												<div class="add-btn home-add">
													<button class="btn <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?> onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)"> <?php echo (in_array($value['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
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
			</div>
		<?php }?>
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
	<?php }?>
	<?php if (!empty($restaurant_details['categories'])) {
	    foreach ($restaurant_details['categories'] as $key => $value) { ?>
			<div class="heading-title bd">
				<h2>Menu Items</h2>
				<div class="slider-arrow">
					<div id="customNav" class="arrow"></div>
				</div>
			</div>
			<div class="detail-list-title font-weight-bold bd">
				<h3><?php echo $value['name']; ?></h3>
			</div>
			<div class="home-items bd" id="category-<?php echo $value['category_id']; ?>" >


					<?php if ($restaurant_details[$value['name']]) {
						foreach ($restaurant_details[$value['name']] as $key => $mvalue) {?>

								<div class="home-menu-card detail-list <?php echo ($mvalue['is_veg'] == 1) ? 'veg' : 'non-veg'; ?>" >
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
															<button class="btn <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?> onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)"> <?php echo (in_array($mvalue['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
															<span class="cust"><?php echo $this->lang->line('customizable') ?></span>
														</div>
													<?php } else { ?>
														<div class="add-btn home-add">
															<?php $add = (in_array($mvalue['entity_id'], $menu_ids)) ? 'Added' : 'Add'; ?>
															<button class="home-add btn <?php echo strtolower($add); ?> addtocart-<?php echo $mvalue['entity_id']; ?>" id="addtocart-<?php echo $mvalue['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $mvalue['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed") ? 'disabled' : ''; ?>> <?php echo (in_array($mvalue['entity_id'], $menu_ids)) ? $this->lang->line('added') : $this->lang->line('add'); ?> </button>
														</div>
													<?php }
												} ?>
											</div>
										</div>
									</div>
								</div>

						<?php }
					}?>

			</div>
		<?php }
	} 
}
else
{ ?>
	<div><?php echo $this->lang->line('no_items_found') ?></div>
<?php } ?>
