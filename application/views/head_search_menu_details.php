
<div class="heading-title">
	<h2>Search Results:</h2>
	<div class="slider-arrow">
		<div id="customNav" class="arrow"></div>
	</div>
</div>


<div class="detail-list-box-main">
	<?php if (!empty($restaurant_details['menu_items'])) {
//	$popular_count = 0;
//	foreach ($restaurant_details['menu_items'] as $key => $value) {
//		if ($value['popular_item'] == 1) {
//			$popular_count = $popular_count + 1;
//		}
//	}

	 foreach ($restaurant_details['menu_items'] as $key => $value) {
		 { ?>
			<div class="detail-list-box">
				<div class="detail-list">
					<div class="detail-list-img">
						<div class="list-img">
							<img src="<?php echo ($value['image']) ? ($value['image']) : (default_img); ?>">

						</div>
					</div>
					<div class="detail-list-content">
						<div class="detail-list-text">
							<h4><?php echo $value['name']; ?></h4>
							<p><?php echo $value['menu_detail']; ?></p>
							<strong><?php echo ($value['check_add_ons'] != 1)?$restaurant_details['restaurant'][0]['currency_symbol'].' '.$value['price']:''; ?></strong>
						</div>
						<?php if ($restaurant_details['restaurant'][0]['timings']['closing'] != "Closed") {
							if ($value['check_add_ons'] == 1) {?>
								<div class="add-btn">
									<?php $add = (in_array($value['entity_id'], $menu_ids))?'Added':'Add'; ?>
									<button class="btn <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?>  onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'addons',this.id)"> <?php echo (in_array($value['entity_id'], $menu_ids))?$this->lang->line('added'):$this->lang->line('add'); ?> </button>
									<span class="cust"><?php echo $this->lang->line('customizable') ?></span>
								</div>
							<?php } else {?>
								<div class="add-btn">
									<?php $add = (in_array($value['entity_id'], $menu_ids))?'Added':'Add'; ?>
									<button class="btn <?php echo strtolower($add); ?> addtocart-<?php echo $value['entity_id']; ?>" id="addtocart-<?php echo $value['entity_id']; ?>" onclick="checkCartRestaurant(<?php echo $value['entity_id']; ?>,<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>,'',this.id)" <?php echo ($restaurant_details['restaurant'][0]['timings']['closing'] == "Closed")?'disabled':''; ?> > <?php echo (in_array($value['entity_id'], $menu_ids))?$this->lang->line('added'):$this->lang->line('add'); ?> </button>
								</div>
							<?php } } ?>
					</div>
				</div>
			</div>
		<?php }
	}?>
</div>

<?php }?>



<script type="text/javascript">
	menuFilter(<?php echo $restaurant_details['restaurant'][0]['content_id']; ?>);
</script>
