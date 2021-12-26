<?php
$this->load->view(ADMIN_URL . '/header'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/multiselect/sumoselect.min.css" />

<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->
    <?php $this->load->view(ADMIN_URL . '/sidebar');

    if ($this->input->post()) {
        foreach ($this->input->post() as $key => $value) {
            $$key = @htmlspecialchars($this->input->post($key));
        }
    } else {
        $FieldsArray = array('entity_id', 'user_id', 'restaurant_id', 'address_id', 'coupon_id', 'tax_rate', 'order_status', 'order_date', 'total_rate', 'coupon_amount', 'coupon_type', 'tax_type', 'subtotal');
        foreach ($FieldsArray as $key) {
            $$key = @htmlspecialchars($edit_records->$key);
        }
    }
    if (isset($edit_records) && $edit_records != "") {
        $add_label     = $this->lang->line('title_admin_orderedit');
        $form_action   = base_url() . ADMIN_URL . '/' . $this->controller_name . "/edit/" . str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
        // $address = $this->order_model->getAddress($user_id);
    } else {
        $add_label    = $this->lang->line('title_admin_orderadd');
        $form_action      = base_url() . ADMIN_URL . '/' . $this->controller_name . "/add";
        $menu_item = 1;
    }
    $restaurant_id = isset($_POST['restaurant_id']) ? $_POST['restaurant_id'] : $restaurant_id;
    $menu_detail     = $this->order_model->getItem($restaurant_id);
    ?>

    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->lang->line('order') ?></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url() . ADMIN_URL ?>/dashboard">
                                <?php echo $this->lang->line('home') ?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/view"><?php echo $this->lang->line('order') ?></a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo $add_label; ?>
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN VALIDATION STATES-->
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption"><?php echo $add_label; ?></div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="<?php echo $form_action; ?>" id="form_add<?php echo $this->prefix ?>" name="form_add<?php echo $this->prefix ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <div id="iframeloading" style="display: none;" class="frame-load">
                                    <img src="<?php echo base_url(); ?>assets/admin/img/loading-spinner-grey.gif" alt="loading" />
                                </div>
                                <div class="form-body">
                                    <?php if (!empty($Error)) { ?>
                                        <div class="alert alert-danger"><?php echo $Error; ?></div>
                                    <?php } ?>
                                    <?php if (validation_errors()) { ?>
                                        <div class="alert alert-danger">
                                            <?php echo validation_errors(); ?>
                                        </div>
                                    <?php } ?>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('restaurant') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <select name="restaurant_id" class="form-control" id="restaurant_id" onchange="getItemDetail(this.id,this.value)">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php if ($this->session->userdata('UserType') == 'MasterAdmin') { ?>

                                                    <?php if (!empty($restaurant)) {
                                                        foreach ($restaurant as $key => $value) { ?>
                                                            <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $restaurant_id) ? "selected" : "" ?> amount="<?php echo $value->amount ?>" type="<?php echo $value->amount_type ?>"><?php echo $value->name ?></option>
                                                    <?php }
                                                    }
                                                } else { ?>
                                                    <option value="<?php echo $adminRestaurantName->entity_id; ?>" amount="<?php echo $value->amount ?>" type="<?php echo $value->amount_type ?>"><?php echo $value->name ?><?php echo $adminRestaurantName->name; ?> </option>
                                                <?php }

                                                ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3">Outlet Manager / Incharge</label>
                                        <div class="col-md-4">
                                            <input type="text" name="manager" id="manager" maxlength="20" data-required="1" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('date_of_order') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <div class='input-group date' id='datetimepicker' data-date-format="mm-dd-yyyy HH:ii P">
                                                <input size="16" type="datetime-local" name="order_date" class="form-control" id="order_date" value="<?php echo ($order_date) ? date('Y-m-d H:i', strtotime($order_date)) : '' ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Order Taking By</label>
                                        <div class="col-md-4">
                                            <input type="text" name="order_taken_by" id="order_taken_by" maxlength="20" data-required="1" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Order Validated By</label>
                                        <div class="col-md-4">
                                            <input type="text" name="order_validate_by" id="order_validate_by" maxlength="20" data-required="1" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Confirm with Factory</label>
                                        <div class="col-md-4">
                                            <input type="text" name="factory" id="factory" maxlength="249" data-required="1" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Create User</label>
                                        <div class="col-md-4">
                                            <input type="radio" name="create_user" value="yes" onchange="markup()"> Yes
                                            <input type="radio" name="create_user" value="no" onchange="markup()" checked> No
                                        </div>
                                    </div>

                                    <div class="hide_user" style="display: none;">

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $this->lang->line('first_name') ?><span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" maxlength="249" data-required="1" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $this->lang->line('last_name') ?><span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" maxlength="20" data-required="1" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Password</label>
                                            <div class="col-md-4">
                                                <input type="text" name="password" id="password" value="123456" readonly class="form-control" />
                                            </div>
                                        </div>
                                        <input type="hidden" name="item_subtotal" id="item_subtotal" value="">
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $this->lang->line('phone_number') ?><span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" onblur="checkExist(this.value)" name="mobile_number" id="mobile_number" value="<?php echo $mobile_number; ?>" data-required="1" class="form-control" />
                                            </div>
                                            <div id="phoneExist"></div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">New Address</label>
                                            <div class="col-md-4">
                                                <input type="text" name="landmark" class="form-control" placeholder="Enter Your Delivery Location" id="search_input" />
                                                <input type="hidden" id="loc_lat" name="latitude" />
                                                <input type="hidden" id="loc_long" name="longitude" />
                                                <br></br>
                                                <textarea type="text" name="address" class="form-control" placeholder="Enter Additional Information"></textarea>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="form-group user_part">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('users') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $entity_id; ?>">
                                            <select name="user_id" class="form-control" id="user_id" onchange="getAddress(this.value)">

                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php if (!empty($user)) {
                                                    foreach ($user as $key => $value) { ?>
                                                        <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $user_id) ? "selected" : "" ?>><?php echo $value->first_name . ' ' . $value->last_name ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3">Picture Attached By</label>
                                        <div class="col-md-4">
                                            <input type="text" name="picture_attached_by" id="picture_attached_by" value="" maxlength="20" data-required="1" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">What's App Number</label>
                                        <div class="col-md-4">
                                            <input type="text"  name="whatsapp" id="whatsapp" data-required="1" class="form-control" />
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Picture Details In Words</label>
                                        <div class="col-md-8">
                                            <input type="text" name="details" id="details" maxlength="249" data-required="1" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?php for ($i = 1, $inc = 1; $i <= count($menu_item); $inc++, $i++) { ?>
                                            <div class="clone" id="cloneItem<?php echo $inc ?>">
                                                <label class="control-label col-md-3 clone-label"><?php echo $this->lang->line('menu_item') ?><span class="required">*</span></label>
                                                
                                                <div class="col-md-2">
                                                    <input type="text" name="qty_no[<?php echo $inc ?>]" id="qty_no<?php echo $inc ?>" value="<?php echo isset($_POST['qty_no'][$i]) ? $_POST['qty_no'][$i] : '' ?>" maxlength="3" data-required="1" onkeyup="qty(this.id,<?php echo $inc ?>)" class="form-control qty validate-class" placeholder="<?php echo $this->lang->line('qty_no') ?>" />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" placeholder="<?php echo $this->lang->line('item_rate') ?>" name="rate[<?php echo $inc ?>]" id="rate<?php echo $inc ?>" value="<?php echo isset($_POST['rate'][$i]) ? $_POST['rate'][$i] : '' ?>" maxlength="20" data-required="1" class="form-control rate validate-class" readonly="" />
                                                </div>
                                                <div class="col-md-1 remove"><?php if ($inc > 1) { ?><div class="item-delete" onclick="deleteItem(<?php echo $inc ?>)"><i class="fa fa-remove"></i></div><?php } ?></div>
                                                <div class="col-md-2">
                                                    <input type="hidden" placeholder="add vat" name="add_vat[<?php echo $inc ?>]" id="add_vat<?php echo $inc ?>" value="" maxlength="20" data-required="1" class="form-control add_vat validate-class" readonly="" />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="hidden" placeholder="add sd" name="add_sd[<?php echo $inc ?>]" id="add_sd<?php echo $inc ?>" value="" maxlength="20" data-required="1" class="form-control add_sd validate-class" readonly="" />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="hidden" placeholder="add vat calc" name="add_vat_calc[<?php echo $inc ?>]" id="add_vat_calc<?php echo $inc ?>" value="" maxlength="20" data-required="1" class="form-control add_vat_cal validate-class" readonly="" />
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="hidden" placeholder="add sd calc" name="add_sd_calc[<?php echo $inc ?>]" id="add_sd_calc<?php echo $inc ?>" value="" maxlength="20" data-required="1" class="form-control add_sd_calc validate-class" readonly="" />
                                                </div>


                                                <div class="col-md-2">
                                                    <input type="hidden" placeholder="addOns" name="addOns[<?php echo $inc ?>]" id="addOns<?php echo $inc ?>" value="<?php echo isset($_POST['addOns'][$i]) ? $_POST['addOns'][$i] : '' ?>" maxlength="20" data-required="1" class="form-control addOns validate-class" readonly="" />
                                                </div>

                                            </div>
                                        <?php } ?>
                                        <div id="Optionplus" onclick="cloneItem()">
                                            <div class="item-plus"><img src="<?php echo base_url(); ?>assets/admin/img/plus-round-icon.png" alt="" /></div>
                                        </div>
                                    </div>


                                    <div class="form-group user_part">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('address') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <select name="address_id" class="form-control address-line" id="address_id" onchange="getUserAddLatLong(this.value)">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php if ($entity_id) {
                                                    if (!empty($address)) {
                                                        foreach ($address as $key => $value) { ?>
                                                            <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $address_id) ? "selected" : "" ?>><?php echo $value->address . ' , ' . $value->landmark . ' , ' . $value->city . ' , ' . $value->state . ' , ' . $value->country . ' ' . $value->zipcode ?></option>
                                                <?php }
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" name="actual_coupon_type" id="actual_coupon_type" />
                                    <input type="hidden" name="coupon_name" id="coupon_name" />

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('title_admin_coupon') ?></label>
                                        <div class="col-md-4">
                                            <select name="coupon_id" class="form-control coupon_id" id="coupon_id" onchange="calculation()">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php if (!empty($coupon)) {
                                                    foreach ($coupon as $key => $value) { ?>
                                                        <option value="<?php echo $value->entity_id ?>" amount="<?php echo $value->amount ?>" max_amount="<?php echo $value->max_amount; ?>" discount_amount="<?php echo $value->discount_amount ?>" type="<?php echo $value->amount_type ?>" coupon_type="<?php echo $value->coupon_type; ?>" gradual="<?php echo $value->gradual_all_items; ?>" maximum_use="<?php echo $value->maximum_use; ?>" usablity="<?php echo $value->usablity; ?>" coupon_name="<?php echo $value->name ?>"><?php echo $value->name ?></option>

                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                        <span class="error display-no" id="errormsg"></span>
                                    </div>

                                    <input type="hidden" name="coupon_amount" id="coupon_amount" />
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('coupon_discount') ?></label>
                                        <div class="col-md-4">
                                            <input type="text" data-value="" name="coupon_discount" id="coupon_discount" maxlength="10" data-required="1" class="form-control" readonly="" /><label class="coupon-type"></label>
                                            <input type="hidden" name="coupon_type" id="coupon_type" value="<?php echo $coupon_type; ?>">
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('res_tax_rate') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" data-value="" name="tax_rate" id="tax_rate" value="<?php echo $tax_rate ?>" maxlength="10" data-required="1" class="form-control" readonly="" /><label class="amount-type"><?php echo ($tax_rate == 'Percentage') ? '%' : '' ?></label>
                                            <input type="hidden" name="tax_type" id="tax_type" value="<?php echo $tax_type; ?>">
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('sub_total') ?> <span class="currency-symbol"></span><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="subtotal" id="subtotal" value="<?php echo ($subtotal) ? $subtotal : ''; ?>" maxlength="10" data-required="1" class="form-control" readonly="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Vat <span class="currency-symbol"></span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="vat" id="vat" value="0" maxlength="10" data-required="1" class="form-control" readonly="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">SD<span class="currency-symbol"></span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="sd" id="sd" value="0" maxlength="10" data-required="1" class="form-control" readonly="" />
                                        </div>
                                    </div>
                                    <!-- <input type="text" name="add_sd_count" id="add_sd" value="0" maxlength="10" data-required="1" class="form-control" readonly="" />
                                    <input type="text" name="add_vat_count" id="add_vat" value="0" maxlength="10" data-required="1" class="form-control" readonly="" /> -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('delivery_charge') ?> <span class="currency-symbol"></span><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="deliveryCharge" id="deliveryCharge" value="" maxlength="10" data-required="1" class="form-control" readonly="" />
                                        </div>
                                    </div>

                                    <input type="hidden" name="zone_id" id="zone_id" />

                                    <input type="hidden" name="selected_user" id="selected_user" value="" />
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('total_rate') ?> <span class="currency-symbol"></span><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="total_rate" id="total_rate" value="<?php echo ($total_rate) ? $total_rate : ''; ?>" maxlength="10" data-required="1" class="form-control" readonly="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('order_status') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <select name="order_status" class="form-control" id="order_status">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php $order_status = order_status($this->session->userdata('language_slug'));
                                                foreach ($order_status as $key => $value) { ?>
                                                    <option value="<?php echo $key ?>" <?php echo ($order_status == $key) ? "selected" : "" ?>><?php echo $value ?></option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('date_of_order') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <div class='input-group date' id='datetimepicker' data-date-format="mm-dd-yyyy HH:ii P">
                                                <input size="16" type="datetime-local" name="order_date" class="form-control" id="order_date" value="<?php echo ($order_date) ? date('Y-m-d H:i', strtotime($order_date)) : '' ?>">
                                                <!-- <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span> -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('start_date'); ?><span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input size="16" type="text" name="start_date" class="form-control" id="start_date" value="<?php echo ($start_date) ? date('Y-m-d H:i', strtotime($start_date)) : "" ?>" readonly="">
                                        </div>
                                    </div> -->
                                </div>
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
                                                        <img src="<?php echo base_url(); ?>assets/front/images/no-delivery.png" alt="Booking availability">
                                                    </div>
                                                    <h2><?php echo $this->lang->line('avail_text1') ?></h2>
                                                    <p><?php echo $this->lang->line('avail_text2') ?></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal modal-main" id="addOnsdetails">

                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn"><?php echo $this->lang->line('submit') ?></button>
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name; ?>/view"><?php echo $this->lang->line('cancel') ?></a>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                    <!-- END VALIDATION STATES-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>

<!-- BEGIN PAGE LEVEL PLUGINS -->

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/pages/scripts/admin-management.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/multiselect/jquery.sumoselect.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB08C5p3KkO8RXBgeCmyyP5q2vQGVHTl9s&libraries=places"></script>
<script>
    var searchInput = 'search_input';

    $(document).ready(function() {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var near_place = autocomplete.getPlace();
            document.getElementById('loc_lat').value = near_place.geometry.location.lat();
            document.getElementById('loc_long').value = near_place.geometry.location.lng();

            checkArea();
            // document.getElementById('loc_lat').innerHTML = near_place.geometry.location.lat();
            // document.getElementById('loc_long').innerHTML = near_place.geometry.location.lng();
        });
    });
    jQuery(document).ready(function() {
        Layout.init(); // init current layout
        // $(".search").select2({

        // });
        // $(".validate-class").select2({

        // });

        $("#restaurant_id").select2({});
        $("#user_id").select2({});
        $("#item_id1").select2({});

    });

    // $(function() {
    //     var date = new Date();
    //     $('#order_date').datetimepicker({
    //         format: 'yyyy-mm-dd hh:ii',
    //         autoclose: true,
    //         startDate: date
    //     });
    // });
    //clone items
    function cloneItem() {
        var divid = $(".clone:last").attr('id');
        var getnum = divid.split('cloneItem');
        var oldNum = parseInt(getnum[1]);
        var newNum = parseInt(getnum[1]) + 1;
        newElem = $('#' + divid).clone().attr('id', 'cloneItem' + newNum).fadeIn('slow'); // create the new element via clone(), and manipulate it's ID using newNum value
        // newElem.find('#item_id'+oldNum).attr('id', 'item_id' + newNum).attr('name', 'item_id[' + newNum +']').attr('onchange','getItemPrices(this.id,'+newNum+')').prop('selected',false).attr('selected',false).val('').removeClass('error');

        newElem.find('#item_id' + oldNum).select2({
            width: '100%'
        }).attr('id', 'item_id' + newNum).attr('name', 'item_id[' + newNum + ']').attr('onchange', 'getItemPrices(this.id,' + newNum + ')').removeClass('error').css('width', '100%');
        newElem.find('.item_id').last().next().next().remove(); //remove previous select2 values
        newElem.find('#rate' + oldNum).attr('id', 'rate' + newNum).attr('name', 'rate[' + newNum + ']').val('').removeClass('error');
        newElem.find('#addOns' + oldNum).attr('id', 'addOns' + newNum).attr('name', 'addOns[' + newNum + ']').val('').removeClass('error');
        newElem.find('#qty_no' + oldNum).attr('id', 'qty_no' + newNum).attr('name', 'qty_no[' + newNum + ']').attr('onkeyup', 'qty(this.id,' + newNum + ')').val('').removeClass('error');
        newElem.find('#add_vat' + oldNum).attr('id', 'add_vat' + newNum).attr('name', 'add_vat[' + newNum + ']').val('').removeClass('error');
        newElem.find('#add_sd' + oldNum).attr('id', 'add_sd' + newNum).attr('name', 'add_sd[' + newNum + ']').val('').removeClass('error');
        newElem.find('#add_vat_calc' + oldNum).attr('id', 'add_vat_calc' + newNum).attr('name', 'add_vat_calc[' + newNum + ']').val('').removeClass('error');
        newElem.find('#add_sd_calc' + oldNum).attr('id', 'add_sd_calc' + newNum).attr('name', 'add_sd_calc[' + newNum + ']').val('').removeClass('error');
        newElem.find('.error').remove();
        newElem.find('.clone-label').css('visibility', 'hidden');

        $(".clone:last").after(newElem);
        $('#cloneItem' + newNum + ' .remove').html('<div class="item-delete" onclick="deleteItem(' + newNum + ')"><i class="fa fa-remove"></i></div>');
    }

    function deleteItem(id) {
        $('#cloneItem' + id).remove();
        calculation();
    }
    //change coupon
    $('#coupon_id').change(function() {
        calculation();
    });

    // $('#restaurant_id').change(function() {

    //     var id = $(this).val();

    //     jQuery.ajax({
    //         type: "POST",
    //         dataType: "html",
    //         url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getDelivery',
    //         data: {
    //             'entity_id': id,
    //         },
    //         success: function(response) {

    //             var final = response.replace(/["']/g, "");

    //             $('#deliveryCharge').val(final);
    //         },
    //         error: function(XMLHttpRequest, textStatus, errorThrown) {
    //             alert(errorThrown);
    //         }
    //     });
    // });

    //get items
    function getItemDetail(id, entity_id) {
        jQuery.ajax({
            type: "POST",
            dataType: "html",
            url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getItem',
            data: {
                'entity_id': entity_id,
            },
            success: function(response) {
                //alert(response);
                $('.item_id').empty().append(response);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
        var element = $('#' + id).find('option:selected');
        var amount = element.attr("amount");
        var amount_type = element.attr("type");
        $('#tax_rate').val(amount).attr('data-value', amount_type);
        var sing = (amount_type == "Percentage") ? "%" : '';
        $('.amount-type').html(sing);
        $('.tax_type').val(amount_type);
        getCurrency(entity_id);
    }
    //get item price
    function getItemPrices(id, num) {

        // var element = $('#item').val();
        var element = $('#' + id).find('option:selected');
        var menu_id = $('#' + id).val();
        var extra = element.attr("data-id-addons");
        // console.log(menu_id);

        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getVatSd',
            data: {
                "entity_id": menu_id,

            },
            beforeSend: function() {
                $('#quotes-main-loader').show();
            },
            success: function(response) {
                // console.log('oo', extra);
                $('#add_vat' + num).val(response.vat_sd['vat']);
                $('#add_sd' + num).val(response.vat_sd['sd']);
                $('#qty_no' + num).val(0);
                $('#rate' + num).val(0);
                $('#quotes-main-loader').hide();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });

        if (extra == 1) {
            // $('#addOnsdetails').modal('show');
            jQuery.ajax({
                type: "POST",
                dataType: "html",
                url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/customAddOns',
                data: {
                    "entity_id": menu_id,

                },
                beforeSend: function() {
                    $('#quotes-main-loader').show();
                },
                success: function(response) {
                    //alert(response);
                    $('#addOnsdetails').html(response);
                    $('#addOnsdetails').modal('show');
                    $('#quotes-main-loader').hide();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        calculation();
    }

    function qty(id, num) {
        $('#' + id).keyup(function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        var element = $('#item_id' + num).find('option:selected');
        var extra = element.attr("data-id-addons");
        var vat = parseFloat($('#vat').val());
        var sd = parseFloat($('#sd').val());
        var add_vat = parseFloat($('#add_vat' + num).val());
        var add_sd = parseFloat($('#add_sd' + num).val());
        var final_vat = 0;
        var final_sd = 0;
        console.log("vat", extra);
        if (extra == 1) {
            // var session_subTotal = ;
            var myTag = parseFloat($('#item_subtotal').val());

            $('#addOns' + num).val(myTag);
            console.log('mytag', myTag)
            // myTag = $('#rate'+num).val();
        } else {
            var myTag = element.attr("data-id");
            $('#addOns' + num).val(myTag);
        }

        var qtydata = parseFloat($('#qty_no' + num).val());
        if (qtydata) {

            if (isNaN(qtydata)) {
                qtydata = 0;
            }
            var total = parseFloat(qtydata * myTag);
            $('#rate' + num).val(total);

        }
        calculation();
    }
    //calculate total rate
    function calculation() {
        var element = $('#coupon_id').find('option:selected');
        var type = element.attr("type");
        var coupon_id = element.attr("value");
        var coupon_type = element.attr("coupon_type");
        var amount = element.attr("amount");
        var max_amount = element.attr("max_amount");


        if (coupon_id) {
            if (coupon_type != 'user_registration') {
                var restaurant = $('#restaurant_id').find('option:selected');
                var res_id = restaurant.attr("value");
                jQuery.ajax({
                    type: "POST",
                    dataType: "html",
                    url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getCouponRestaurant',
                    data: {
                        'entity_id': coupon_id,
                        'res_id': res_id
                    },
                    success: function(response) {

                        if (response == 0) {
                            $('#errormsg').html("This coupon is not applicable for your selected restaurant").show();
                            $("#submit_page").attr("disabled", true);

                            //$("#flag").val(0);
                        } else {
                            $('#errormsg').hide();
                            $("#submit_page").attr("disabled", false);

                            // $("#flag").val(1);
                            couponCalculate();
                        }


                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });

            }

            if (coupon_type == 'user_registration') {
                var user = $('#user_id').find('option:selected');
                var user_id = user.attr("value");
                jQuery.ajax({
                    type: "POST",
                    dataType: "html",
                    url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/checkUserCountCoupon',
                    data: {
                        'user_id': user_id
                    },
                    success: function(response) {
                        // $('.address-line').empty().append(response);
                        // console.log(response);
                        if (response > 0) {
                            $('#errormsg').html("This coupon is only applicable for first order").show();
                            $("#submit_page").attr("disabled", true);

                            //$("#flag").val(0);
                        } else {
                            $('#errormsg').hide();
                            $("#submit_page").attr("disabled", false);

                            // $("#flag").val(1);
                            couponCalculate();
                        }


                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });

            }


        } else {
            var i = 1;
            var sum = 0;
            var total_vat = 0;
            var total_sd = 0;
            var total_sum = 0;

            var delivery_charge = $('#deliveryCharge').val();


            while (i <= 20) {
                if (document.getElementById('rate' + i)) {
                    sum = parseFloat($('#rate' + i).val());
                    vat = parseFloat($('#add_vat' + i).val());
                    sd = parseFloat($('#add_sd' + i).val());
                    sd_count = parseFloat((sum * sd) / 100);
                    vat_count = parseFloat(((sum + sd_count) * vat) / 100);
                    total_vat = parseFloat(total_vat + vat_count);
                    total_sd = parseFloat(total_sd + sd_count);
                    total_sum = parseFloat(total_sum + sum);
                    i++;
                } else {
                    i++;
                }

            }
            $('#subtotal').val(total_sum);

            $('#vat').val(total_vat.toFixed(2));
            // console.log("fgrg", delivery_charge);
            //final_sd = $('#sd').val(total_sd);
            $('#sd').val(total_sd.toFixed(2));
            if (delivery_charge) {
                total_sum = total_sum + parseFloat(delivery_charge) + total_vat + total_sd;
                //console.log("kjj",final_vat);
                $('#total_rate').val(total_sum.toFixed(2));
            } else {
                total_sum = parseFloat(total_sum + total_vat + total_sd);
                $('#total_rate').val(total_sum.toFixed(2));
            }

        }


    }

    function couponCalculate() {
        // 
        var element = $('#coupon_id').find('option:selected');
        var type = element.attr("type");
        var coupon_id = element.attr("value");
        var coupon_type = element.attr("coupon_type");
        var coupon_name = element.attr("coupon_name");
        var all_items = element.attr("gradual");
        var usablity = element.attr("usablity");
        var discount_upto = element.attr("discount_amount");
        var restaurant_id = $('#restaurant_id').val();
        var amount = element.attr("amount");
        var max_amount = element.attr("max_amount");
        var maximum_use = element.attr("maximum_use");
        var totalDiscountPrice = 0,
            discount_amount = 0,
            discount = 0,
            after_discount = 0;
        var delivery_charge = $('#deliveryCharge').val();
        var sum = 0;
        var total_vat = 0;
        var total_sd = 0;
        var total_sum = 0;
        var total_price = 0;
        var user_id = $('#user_id').val();
        $('#actual_coupon_type').val(coupon_type);
        $('#coupon_type').val(type);
        $('#coupon_name').val(coupon_name);
        $('#coupon_amount').val(amount);

        var sing = (type == "Percentage") ? "%" : '';
        $('.coupon-type').html(sing);

        var i = 1;

        if (coupon_type == 'selected_user') {


            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/checkCouponUser',
                data: {
                    'user_id': user_id,
                    'coupon_id': coupon_id
                },
                success: function(response) {

                    console.log('res', response)


                    if (response == null) {
                        not_applicable = 0;
                        $('#errormsg').html("This Coupon is not applicable for this user.").show();
                        $("#submit_page").attr("disabled", true);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        if (coupon_type == 'free_delivery') {

            delivery_charge = 0;
        }

        if (maximum_use > 0) {
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/checkMaximumUsage',
                data: {
                    'user_id': user_id,
                    'coupon_id': coupon_id
                },
                success: function(response) {

                    if (response >= maximum_use) {
                        $('#errormsg').html("This Coupon is not applicable for this user.").show();
                        $("#submit_page").attr("disabled", true);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }

        if (usablity == 'onetime') {
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/checkUsability',
                data: {
                    'user_id': user_id,
                    'coupon_id': coupon_id
                },
                success: function(response) {

                    if (response > 0) {
                        $('#errormsg').html("This Coupon is not applicable for this user.").show();
                        $("#submit_page").attr("disabled", true);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        }
        if (max_amount) {

            while (i <= 20) {
                if (document.getElementById('rate' + i)) {
                    sum = parseFloat($('#rate' + i).val());
                    total_sum = parseFloat(total_sum + sum);
                    i++;
                } else {
                    i++;
                }

            }

            if (total_sum >= max_amount) {

                // $('#coupon_amount').val(amount);
                // var not_applicable = 1;

                if (type == 'Percentage' && amount != '') {

                    var j = 1;

                    while (j <= 20) {
                        if (document.getElementById('rate' + j)) {
                            price = parseFloat($('#rate' + j).val());
                            vat = parseFloat($('#add_vat' + j).val());
                            sd = parseFloat($('#add_sd' + j).val());

                            discount = (price * amount) / 100;
                            discounted_price = price - discount;
                            totalDiscountPrice = totalDiscountPrice + discount;
                            sd_count = parseFloat((price * sd) / 100);
                            vat_count = parseFloat(((price + sd_count) * vat) / 100);
                            total_vat = parseFloat(total_vat + vat_count);
                            total_sd = parseFloat(total_sd + sd_count);
                            //total_price = parseFloat(total_price + sum);
                            j++;
                        } else {
                            j++
                        }

                    }
                    //total_sum = total_sum - parseFloat((total_sum * 10) / 100);
                    //total_sum = total-totalDiscountPrice;

                } else if (type == 'Amount' && amount != '') {
                    //total_sum = parseFloat(total_sum - amount);
                    //total_sum = total-totalDiscountPrice;
                    totalDiscountPrice = amount;
                }
                // else {
                //     var j = 1;

                //     while (j <= 20) {
                //         if (document.getElementById('rate' + j)) {
                //             price = parseFloat($('#rate' + j).val());
                //             vat = parseFloat($('#add_vat' + j).val());
                //             sd = parseFloat($('#add_sd' + j).val());
                //             discount = (amount / total_sum) * price;
                //             discounted_price = price - discount;
                //             sd_count = parseFloat((discounted_price * sd) / 100);
                //             vat_count = parseFloat(((discounted_price + sd_count) * vat) / 100);
                //             total_vat = parseFloat(total_vat + vat_count);
                //             total_sd = parseFloat(total_sd + sd_count);
                //             // total_price = parseFloat(total_sum - amount);
                //             j++;
                //         } else {
                //             j++;
                //         }

                //     }
                //     delivery_charge = 0;
                //     $('#deliveryCharge').val(0);
                // }

                //console.log('discountamount', discount_upto)
                if (discount_upto && totalDiscountPrice > discount_upto) {

                    totalDiscountPrice = discount_upto;
                }

                $('#vat').val(total_vat.toFixed(2));
                $('#coupon_discount').val(totalDiscountPrice);
                $('#sd').val(total_sd.toFixed(2));
                if (delivery_charge == 0) {
                    // console.log("vat", final_vat, total_sum, total_sd);
                    //$('#subtotal').val((total_sum - totalDiscountPrice).toFixed(2));
                    $('#subtotal').val(total_sum.toFixed(2));
                    total_sum = parseFloat((total_sum + total_vat + total_sd) - totalDiscountPrice);

                    $('#total_rate').val(total_sum.toFixed(2));
                } else {
                    $('#subtotal').val(total_sum.toFixed(2));
                    total_sum = (total_sum + parseFloat(delivery_charge) + total_vat + total_sd) - totalDiscountPrice;
                    $('#total_rate').val(total_sum.toFixed(2));
                }
                $("#submit_page").attr("disabled", false);
                $('#errormsg').hide();
            } else {
                //console.log('error');
                $('#errormsg').html("Condition didn't match to apply this coupon").show();
                $("#submit_page").attr("disabled", true);
            }
        } else if (coupon_type == 'discount_on_items') {

            var discounted_menu = [];
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/discountedItem',
                data: {
                    'restaurant_id': restaurant_id,
                },
                success: function(discounted_menu) {

                    while (i <= 20) {
                        if (document.getElementById('item_id' + i)) {
                            item_id = parseFloat($('#item_id' + i).val());
                            sum = parseFloat($('#rate' + i).val());
                            vat = parseFloat($('#add_vat' + i).val());
                            sd = parseFloat($('#add_sd' + i).val());
                            sd_count = parseFloat((sum * sd) / 100);
                            vat_count = parseFloat(((sum + sd_count) * vat) / 100);
                            total_vat = parseFloat(total_vat + vat_count);
                            total_sd = parseFloat(total_sd + sd_count);

                            for (let index = 0; index < discounted_menu.length; index++) {
                                if (discounted_menu[index].item_id == item_id) {
                                    discount = amount / 100;
                                    after_discount = (sum * discount);
                                    discount_amount = after_discount + discount_amount;
                                }

                            }

                            totalDiscountPrice = discount_amount;
                            total_sum = parseFloat(total_sum + sum);

                            i++;
                        } else {
                            i++;
                        }

                    }

                    $('#vat').val(total_vat.toFixed(2));
                    $('#coupon_discount').val(totalDiscountPrice);
                    $('#sd').val(total_sd.toFixed(2));
                    // $('#subtotal').val((total_sum - totalDiscountPrice).toFixed(2));
                    $('#subtotal').val(total_sum.toFixed(2));
                    total_sum = (total_sum + parseFloat(delivery_charge) + total_vat + total_sd) - totalDiscountPrice;
                    $('#total_rate').val(total_sum.toFixed(2));
                    $("#submit_page").attr("disabled", false);
                    $('#errormsg').hide();

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        } else if (coupon_type == 'gradual') {
            var user_id = $('#user_id').val();

            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/checkPreviousOrder',
                data: {
                    'user_id': user_id,
                    'coupon_id': coupon_id
                },
                success: function(response) {

                    console.log(response);
                    var not_applicable = 0;
                    while (i <= 20) {
                        if (document.getElementById('item_id' + i)) {
                            item_id = parseFloat($('#item_id' + i).val());
                            sum = parseFloat($('#rate' + i).val());
                            vat = parseFloat($('#add_vat' + i).val());
                            sd = parseFloat($('#add_sd' + i).val());
                            sd_count = parseFloat((sum * sd) / 100);
                            vat_count = parseFloat(((sum + sd_count) * vat) / 100);
                            total_vat = parseFloat(total_vat + vat_count);
                            total_sd = parseFloat(total_sd + sd_count);

                            if (all_items == 1) {
                                if (response.gradual == 0) {
                                    discount = response.sequence[0].percentage / 100;
                                    console.log('dis', discount)
                                } else {

                                    if (response.sequence[response.gradual] !== null && typeof response.sequence[response.gradual] !== 'undefined') {

                                        discount = response.sequence[response.gradual].percentage / 100;
                                    } else {
                                        not_applicable = 1;
                                    }

                                }
                            }

                            if (all_items == 0) {
                                for (let index = 0; index < response.gradual_item.length; index++) {
                                    if (response.gradual_item[index].item_id == item_id) {

                                        if (response.checkRecords == null) {
                                            discount = response.sequence[0].percentage / 100;
                                        } else {
                                            if (response.checkRecords.last_applied && response.checkRecords.count > 0) {

                                                discount = response.sequence[response.checkRecords.last_applied].percentage / 100;

                                            } else {
                                                not_applicable = 1;
                                            }
                                        }
                                        break;
                                    }

                                }
                            }
                            if (not_applicable == 0) {
                                after_discount = (sum * discount);
                                discount_amount = after_discount + discount_amount;
                                totalDiscountPrice = discount_amount;
                                total_sum = parseFloat(total_sum + sum);
                                discount = 0;
                            }


                            i++;
                        } else {
                            i++;
                        }

                    }

                    $('#vat').val(total_vat.toFixed(2));
                    $('#coupon_discount').val(totalDiscountPrice);
                    $('#sd').val(total_sd.toFixed(2));
                    // $('#subtotal').val((total_sum - totalDiscountPrice).toFixed(2));
                    $('#subtotal').val(total_sum.toFixed(2));
                    total_sum = (total_sum + parseFloat(delivery_charge) + total_vat + total_sd) - totalDiscountPrice;
                    $('#total_rate').val(total_sum.toFixed(2));
                    console.log('app', not_applicable)
                    if (not_applicable == 1) {
                        $('#errormsg').html("You Have Already Use All Options Of This Coupon.").show();
                        $("#submit_page").attr("disabled", true);
                    } else {
                        $("#submit_page").attr("disabled", false);
                        $('#errormsg').hide();
                    }


                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });

        } else {
            $("#submit_page").attr("disabled", false);
            $('#errormsg').hide();
        }
    }

    //get address
    function getAddress(entity_id) {
        jQuery.ajax({
            type: "POST",
            dataType: "html",
            url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getAddress',
            data: {
                'entity_id': entity_id,
            },
            success: function(response) {
                $('.address-line').empty().append(response);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    //validation for menu item
    $('#form_add_order').bind('submit', function(e) {
        $('.validate-class').each(function() {
            var id = $(this).attr('id');
            if ($('#' + id).val() == '') {
                $('#' + id).attr('required', true);
                $('#' + id).addClass('error');
            }
        });
    });

    function format_indonesia_currency(amt) {
        var number = amt;
        return n = number.toLocaleString('id-ID', {
            currency: 'IDR'
        });

    }

    function markup() {
        if ($("input[name=create_user]:checked").val() == "yes") {
            $(".hide_user").show();
            $(".user_part").hide();
            $("#user_id").val('');
            $('#address_id').val('');
        } else if ($("input[name=create_user]:checked").val() == "no") {
            $(".hide_user").hide();
            $(".user_part").show();
        }
    }

    function getUserAddLatLong(address_id) {
        var restaurant_id = $('#restaurant_id').val();
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getLatLongs',
            data: {
                'entity_id': address_id,
                'restaurant_id': restaurant_id
            },
            success: function(response) {
                console.log('response', response.delivery_charge)
                if (response == null) {
                    $('#delivery-not-avaliable').modal('show');
                    $('#deliveryCharge').val(0);
                } else {
                    $('#deliveryCharge').val(response.delivery_charge);
                    $('#zone_id').val(response.zone_id);
                }

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }


    function checkArea() {
        var restaurant_id = $('#restaurant_id').val();
        var latitude = $('#loc_lat').val();
        var longitude = $('#loc_long').val();
        console.log('lat', latitude)
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getArea',
            data: {
                'lat': latitude,
                'long': longitude,
                'restaurant_id': restaurant_id
            },
            success: function(response) {
                console.log('response', response)
                if (response == null) {
                    $('#delivery-not-avaliable').modal('show');
                    $('#deliveryCharge').val(0);
                } else {
                    $('#deliveryCharge').val(response.delivery_charge);
                    $('#zone_id').val(response.zone_id);
                }

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }

    function checkExist(mobile_number) {

        $.ajax({
            type: "POST",
            url: BASEURL + "<?php echo ADMIN_URL ?>/order/checkExist",
            data: 'mobile_number=' + mobile_number,
            cache: false,
            success: function(html) {
                if (html > 0) {
                    $('#phoneExist').show();
                    $('#phoneExist').html("<?php echo $this->lang->line('phone_exist'); ?>");
                    $(':input[type="submit"]').prop("disabled", true);
                } else {
                    $('#phoneExist').html("");
                    $('#phoneExist').hide();
                    $(':input[type="submit"]').prop("disabled", false);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                $('#phoneExist').show();
                $('#phoneExist').html(errorThrown);
            }
        });
    }
</script>
<?php $this->load->view(ADMIN_URL . '/footer'); ?>