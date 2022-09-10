<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php $this->load->view('header'); ?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">
<!-- <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<?php if(empty($restaurant_details['restaurant'])) {
    redirect(base_url().'restaurant/event-booking');
} ?>
<!--<section class="inner-banner booking-detail-banner">-->
<!--	<div class="container">-->
<!--		<div class="inner-pages-banner">-->
<!--			-->
<!--		</div>-->
<!--	</div>-->
<!--</section>-->

<section class="inner-pages-section rest-detail-section">


	<div class="container">

		<div class="row " style="margin-top: 100px">

			<div class="col-lg-12">

			</div>

		</div>

		<div class="row">
<!--			<div class="  takeway_re  " >-->
<!--				<ul class="mb-2" style="align-items:center;justify-content: center ;color: white;">-->
<!---->
<!--					<a class="three_button" href="--><?php //echo base_url('restaurant/restaurant-detail/'.$slug);?><!--"><li class=" --><?php //echo ($current_page == 'ContactUs1') ? 'li_bg' : 'li_bg'; ?><!--" ><span style="font-size: 12px"  class="fas fa-check"></span><strong class="span_text"> Takeway</strong></li></a>-->
<!--					<a class="three_button" href="--><?php //echo base_url('restaurant/restaurant-detail/'.$slug);?><!--"><li class="--><?php //echo ($current_page == 'RestaurantDetails') ? 'li_bg' : 'li_bg'; ?><!--" ><span style="font-size: 12px " class="fas fa-check"></span><strong  class="span_text"> Delivery</strong></li></a>-->
<!--					<a class="three_button" href="--><?php //echo base_url() . 'restaurant/event-booking'; ?><!--"><li class="--><?php //echo ($current_page == 'EventBooking') ? 'li_sec' : 'li_bg'; ?><!--" ><span style="font-size: 12px " class="fas fa-check"></span><strong class="span_text"> Dine In</strong></li></a>-->
<!---->
<!--				</ul>-->
<!--			</div>-->
<!--			<div class="col-lg-12">-->
<!--				<div class="heading-title">-->
<!--					<h2>--><?php //echo $this->lang->line('select_package') ?><!--</h2>-->
<!--				</div>-->
<!--			</div>-->

		</div>

		<div class="row restaurant-detail-row" style="justify-content: center;">
			<div class="col-sm-12 col-md-5 col-lg-8 d-none">
				<div class="detail-list-box-main">
					<!-- <div class="detail-list-title">
						<h3>Gold Packages</h3>
					</div> -->
					<div class="detail-list-box">
						<?php if (!empty($restaurant_details['packages'])) {
							foreach ($restaurant_details['packages'] as $key => $value) { ?>
								<div class="detail-list">
									<div class="detail-list-img">
										<div class="list-img">
											<img src="<?php echo ($value['image'])?$value['image']:default_img; ?>">
										</div>
									</div>
									<div class="detail-list-content">
										<div class="detail-list-text">
											<h4><?php echo $value['name']; ?></h4>
											<p><?php echo $value['detail']; ?></p>
											<strong>৳ <?php echo $value['price']; ?></strong>
										</div>
										<div class="add-btn">
											<div class="addpackage btn" id="addpackage-<?php echo $value['entity_id']; ?>" onclick="AddPackage('<?php echo $value['entity_id']; ?>')"><?php echo $this->lang->line('add') ?></div>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php }
						else { ?>
							<div class="detail-list-title">
								<h3 class="no-results"><?php echo $this->lang->line('no_results_found') ?></h3>
							</div>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-8" style="">
				<div class="your-booking-main">
					<div class="your-booking-title">
						<h3><i class="iicon-icon-27"></i><?php echo $this->lang->line('your_booking') ?></h3>
					</div>
					<form id="check_event_availability" name="check_event_availability" method="post" class="form-horizontal float-form">
						<input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $restaurant_details['restaurant'][0]['restaurant_id']; ?>">
						<input type="hidden" name="user_id" id="user_id" value="<?php echo $this->session->userdata('UserID'); ?>">
						<input type="hidden" name="name" id="name" value="<?php echo $this->session->userdata('userFirstname').' '.$this->session->userdata('userLastname'); ?>">
						<div class="booking-option-main">
							<div class="booking-option how-many-people">
								<div class="booking-option-cont">
									<div class="option-img">
										<img src="<?php echo base_url();?>assets/front/images/avatar-man.png">
									</div>
									<div class="booking-option-text">
										<strong><?php echo $this->lang->line('how_many_people') ?></span></strong><span>
										<span id="peepid"><strong> <?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('no_of_people')))? $this->session->userdata('no_of_people'): '1' ?> <?php echo $this->lang->line('people') ?></strong></span>
										<!--<span id="peepid"><strong>1 <?php //echo $this->lang->line('people') ?></strong></span>-->
									</div>
								</div>
								<div class="add-cart-item">
									<div class="number">
										<span class="minus variant"><i class="iicon-icon-22"></i></span>
										<input type="text" name="no_of_people" id="no_of_people" value="<?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('no_of_people')))? $this->session->userdata('no_of_people'): '1' ?>" onkeyup="getPeople(this.value)">
										<!--<input type="text" name="no_of_people" id="no_of_people" value="1" onkeyup="getPeople(this.value)">-->
										<span class="plus variant"><i class="iicon-icon-21"></i></span>
									</div>
								</div>
							</div>
							<div class="booking-option pick-date">
								<div class="booking-option-cont">
									<div class="option-img">
										<img src="<?php echo base_url();?>assets/front/images/pick-date.png">
									</div>
									<div class="booking-date-font booking-option-text">
										<strong><span><?php echo $this->lang->line('pick_date') ?></span></strong>
										<div class="form-group">
								            <input type='text' class="form-control" name="date_time" id='datetimepicker1' placeholder="<?php echo $this->lang->line('pick_date') ?>"  readonly="readonly" value = "<?php echo (!empty($this->session->userdata('UserID')) && $this->session->userdata('is_user_login') == 1 && !empty($this->session->userdata('date_time')))? $this->session->userdata('date_time'): '' ?>" >
								        </div>
									</div>

								</div>



							</div>
							<div class="booking-option how-many-people">
								<div class="booking-option-cont">
									<div class="option-img">
										<img src="<?php echo base_url();?>assets/front/images/dining-time.png">
									</div>
									<div class="booking-date-font booking-option-text">
										<strong>Special Instruction</span></strong>
										<div class="form-group">
											<textarea class="form-control" name="special_ins" id="special_ins" rows="2" placeholder="Special Instruction..." tabindex="2"></textarea> <br>

										</div>
									</div>

								</div>
								<div class="add-cart-item">
								</div>
							</div>


							<div class="continue-btn">
                                <button type="submit" name="submit_page" id="submit_page" value="Check Availability" class="btn btn-success danger-btn"><?php echo $this->lang->line('check_avail') ?></button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section><!--/ end content-area section -->

<!-- booking_availability -->
<div class="modal modal-main" id="booking-available">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $this->lang->line('booking_availability') ?></h4>
        <button type="button" class="close" data-dismiss="modal"><i class="iicon-icon-23"></i></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<div class="availability-popup">
      		<div class="availability-images">
      			<img src="<?php echo base_url();?>assets/front/images/booking-availability.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
      		</div>
      		<h2><?php echo $this->lang->line('booking_available') ?></h2>
      		<?php if (!empty($this->session->userdata('UserID')) && ($this->session->userdata('is_user_login') == 1)) { ?>
      			<p><?php echo $this->lang->line('proceed_further') ?></p>

				<button class="btn" data-dismiss="modal" data-toggle="modal" onclick="confirmBooking()"><?php echo $this->lang->line('confirm') ?></button>
      			<button class="btn" data-dismiss="modal" data-toggle="modal"><?php echo $this->lang->line('cancel') ?></button>
      		<?php } 
      		else { ?>
      			<p><?php echo $this->lang->line('please') ?> <a href="<?php echo base_url();?>home/login"><u><?php echo $this->lang->line('title_login') ?></u></a> <?php echo $this->lang->line('book_avail_text') ?></p>
      		<?php }?>
      		
      	</div>
      </div>
    </div>
  </div>
</div>

<!-- Booking Not Availability -->
<div class="modal modal-main" id="booking-not-available">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $this->lang->line('booking_availability') ?></h4>
        <button type="button" class="close" data-dismiss="modal"><i class="iicon-icon-23"></i></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<div class="availability-popup">
      		<div class="availability-images">
      			<img src="<?php echo base_url();?>assets/front/images/booking-availability.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
      		</div>
      		<h2><?php echo $this->lang->line('booking_not_available') ?></h2>
      		<p><?php echo $this->lang->line('no_bookings_avail') ?></p>
      		<button class="btn" data-dismiss="modal"><?php echo $this->lang->line('cancel') ?></button>
      	</div>
      </div>
    </div>
  </div>
</div>

<!-- Booking Confirmation -->
<div class="modal modal-main" id="booking-confirmation">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $this->lang->line('booking_confirmation') ?></h4>
        <button type="button" class="close" data-dismiss="modal"><i class="iicon-icon-23"></i></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      	<div class="availability-popup">
      		<div class="availability-images">
      			<img src="<?php echo base_url();?>assets/front/images/booking-confirmation.svg" alt="<?php echo $this->lang->line('booking_availability') ?>">
      		</div>
      		<h2><?php echo $this->lang->line('booking_confirmed_text1') ?></h2>
      		<p><?php echo $this->lang->line('booking_confirmed_text2') ?></p>
      		<a href="<?php echo base_url().'myprofile/view-my-bookings'; ?>" class="btn"><?php echo $this->lang->line('view_bookings') ?></a>
      	</div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

<script type="text/javascript">
$(function () {
    var dateToday = new Date();
    $('#datetimepicker1').datetimepicker({ 
		minDate: dateToday,
		ignoreReadonly: true,
		useCurrent: false,
		defaultDate: dateToday,
   });
});
</script>
<?php $this->load->view('footer'); ?>
