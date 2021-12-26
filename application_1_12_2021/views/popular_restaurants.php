<section class="popular-restaurants">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="heading-title">
					<h2><?php echo $this->lang->line('nearby_restaurants') ?></h2>
					<?php if (!empty($nearbyRestaurants)) {
					    if(count($nearbyRestaurants) > 9){ ?>
					        <a href="<?php echo base_url() . 'restaurant'; ?>"><div class="view-all btn"> <?php echo $this->lang->line('view_all'); ?></div> </a>
					    <?php }
					}?>
				</div>
			</div>
		</div>
		<div class="row rest-box-row">
			<?php if (!empty($nearbyRestaurants)) {
				foreach ($nearbyRestaurants as $key => $value) { 
					if ($key <= 8) { ?>
						<div class="col-sm-12 col-md-6 col-lg-4">
							<div class="popular-rest-box">
								<a href="<?php echo base_url().'restaurant/restaurant-detail/'.$value['restaurant_slug'];?>">
									<div class="popular-rest-img">
										<img src="<?php echo ($value['image'])?$value['image']:default_img;?>" alt="<?php echo $value['name']; ?>">
										<?php echo ($value['ratings'] > 0)?'<strong>'.$value['ratings'].'</strong>':'<strong class="newres">'. $this->lang->line("new") .'</strong>'; ?>
										<div class="openclose-btn">
											<div class="openclose <?php echo ($value['timings']['closing'] == "Closed")?"closed":""; ?>"> <?php echo ($value['timings']['closing'] == "Closed")?$this->lang->line('closed'):$this->lang->line('open'); ?> </div>
											<!-- <?php //echo $value['timings']['closing']; ?> -->
										</div>
									</div>
									<div class="popular-rest-content">
										<h3><?php echo $value['name']; ?></h3>
										<div class="popular-rest-text">
											<p class="address-icon"><?php echo $value['address']; ?> </p>
										</div>
									</div>
								</a>
							</div>
						</div>
				<?php } }
			} else { ?>
				<div class="">
					<div class="col-lg-12">
						<div><h6 class="h6-title"><?php echo $this->lang->line('no_such_res_found') ?></h6></div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</section>