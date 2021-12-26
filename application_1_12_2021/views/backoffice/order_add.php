<?php
$this->load->view(ADMIN_URL . '/header'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />


<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->
    <?php $this->load->view(ADMIN_URL . '/sidebar');
    $restaurant_id = isset($_POST['restaurant_id']) ? $_POST['restaurant_id'] : $restaurant_id;
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
                                            <select name="restaurant_id" class="form-control" id="restaurant_id">
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

                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $this->lang->line('phone_number') ?><span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <input type="text" onblur="checkExist(this.value)" name="mobile_number" id="mobile_number" value="<?php echo $mobile_number; ?>" data-required="1" class="form-control" />
                                            </div>
                                            <div id="phoneExist"></div>
                                        </div>

                                        <!-- <div class="form-group">
                                            <label class="control-label col-md-3">New Address</label>
                                            <div class="col-md-4">
                                                <input type="text" name="landmark" class="form-control" placeholder="Enter Your Delivery Location" id="search_input" />
                                                <input type="hidden" id="loc_lat" name="latitude" />
                                                <input type="hidden" id="loc_long" name="longitude" />
                                                <br></br>
                                                <textarea type="text" name="address" class="form-control" placeholder="Enter Additional Information"></textarea>
                                            </div>

                                        </div> -->

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
                                        <label class="control-label col-md-3">Address</label>
                                        <div class="col-md-4">
                                            <input type="radio" name="address" value="yes" onchange="address_markup()"> Add New Address
                                            <input type="radio" name="address" value="no" onchange="address_markup()" checked> Add Old Address
                                        </div>
                                    </div>

                                    <div class="hide_address" style="display: none;">

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Delivery Area</label>
                                            <div class="col-md-4">
                                                <select name="delivery_area" class="form-control" id="delivery_area">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php
                                                    if (!empty($delivery_area)) {
                                                        foreach ($delivery_area as $k => $value) { ?>
                                                            <option value="<?php echo $value->entity_id ?>"><?php echo $value->name; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">House No.</label>
                                            <div class="col-md-4">
                                                <input type="text" name="house" id="house" value="" maxlength="20" data-required="1" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Road No</label>
                                            <div class="col-md-4">
                                                <input type="text" name="road" id="road" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Flat No.</label>
                                            <div class="col-md-4">
                                                <input type="text" name="flat" id="flat" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Block / Section No.</label>
                                            <div class="col-md-4">
                                                <input type="text" name="section" id="section" class="form-control" />
                                            </div>
                                        </div>
                                        <input type="hidden" name="item_subtotal" id="item_subtotal" value="">

                                    </div>

                                    <div class="form-group address_part">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('address') ?><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <select name="address_id" class="form-control address-line" id="address_id">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php
                                                if (!empty($address)) {
                                                    foreach ($address as $key => $value) {
                                                        $address_detail = json_decode($value->address_detail);
                                                ?>
                                                        <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $address_id) ? "selected" : "" ?>><?php echo 'Road No: ' . $address_detail['RoadNo'] . ', Block No: ' . $address_detail->BlockNo . ', Flat No: ' . $address_detail->FlatNo  . ', House No: ' . $address_detail->Houseno . ' , ' . $address_detail->area; ?></option>
                                                <?php }
                                                }
                                                ?>
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
                                            <input type="text" name="whatsapp" id="whatsapp" data-required="1" class="form-control" />
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Picture Details In Words</label>
                                        <div class="col-md-8">
                                            <input type="text" name="details" id="details" maxlength="249" data-required="1" class="form-control" />
                                        </div>
                                    </div>
                                    <label class="control-label col-md-3 clone-label">Order Details</label>

                                    <div class="form-group">
                                        <?php $inc = 1; ?>
                                        <div class="clone" id="cloneItem<?php echo $inc ?>">

                                            <div class="col-md-3">
                                                <input type="text" name="name[<?php echo $inc ?>]" id="name<?php echo $inc ?>" maxlength="20" data-required="20" class="form-control name validate-class" placeholder="Product Name" />
                                                <br></br>
                                            </div>
                                            <div class="col-md-4">
                                                <input class="form-control description validate-class" name="description[<?php echo $inc ?>]" id="description<?php echo $inc ?>" placeholder="Description" maxlength="249" />
                                                <br></br>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="qty_no[<?php echo $inc ?>]" id="qty_no<?php echo $inc ?>" maxlength="3" data-required="1" onkeyup="calculation()" class="form-control qty validate-class" placeholder="<?php echo $this->lang->line('qty_no') ?>" />
                                                <br></br>
                                            </div>

                                            <div class="col-md-2">
                                                <input type="text" name="rate[<?php echo $inc ?>]" id="rate<?php echo $inc ?>" placeholder="Price" onkeyup="calculation()" maxlength="20" data-required="1" class="form-control rate validate-class" />
                                                <br></br>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" placeholder="Custom Fee" name="custom_fee[<?php echo $inc ?>]" id="custom_fee<?php echo $inc ?>" onkeyup="calculation()" maxlength="20" data-required="1" class="form-control custom_fee validate-class" />
                                                <br></br>
                                            </div>

                                            <div class="col-md-2">
                                                <input type="text" placeholder="Vat" name="add_vat[<?php echo $inc ?>]" id="add_vat<?php echo $inc ?>" onkeyup="calculation()" maxlength="20" data-required="1" class="form-control add_vat validate-class" />
                                                <br></br>
                                            </div>

                                            <!-- <div class="col-md-2">
                                                <input type="text" name="deliveryCharge[<?php echo $inc ?>]" id="deliveryCharge<?php echo $inc ?>" onkeyup="calculation(this.id,<?php echo $inc ?>)" placeholder="Delivery Charge" maxlength="10" data-required="1" class="form-control" />
                                                <br></br>
                                            </div> -->

                                            <div class="col-md-1 remove"><?php if ($inc > 1) { ?><div class="item-delete" onclick="deleteItem(<?php echo $inc ?>)"><i class="fa fa-remove"></i></div><?php } ?></div>
                                        </div>

                                        <div id="Optionplus" onclick="cloneItem()">
                                            <div class="item-plus"><img src="<?php echo base_url(); ?>assets/admin/img/plus-round-icon.png" alt="" /></div>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('delivery_charge') ?> <span class="currency-symbol"></span><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="number" name="deliveryCharge" id="deliveryCharge" onkeyup="calculation()" value="" maxlength="10" data-required="1" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('image') ?></label>
                                        <div class="col-md-4">
                                            <div class="custom-file-upload">
                                                <label for="Image" class="custom-file-upload">
                                                    <i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('no_file') ?>
                                                </label>
                                                <input type="file" name="Image" id="Image" accept="image/*" data-msg-accept="<?php echo $this->lang->line('file_extenstion') ?>" onchange="readURL(this)" />
                                            </div>
                                            <p class="help-block"><?php echo $this->lang->line('img_allow'); ?><br /> <?php echo $this->lang->line('max_file_size'); ?><br /><?php echo $this->lang->line('recommended_size') . '290 * 210.'; ?></p>
                                            <span class="error display-no" id="errormsg"></span>
                                            <div id="img_gallery"></div>
                                            <img id="preview" height='100' width='150' class="display-no" />

                                            <input type="hidden" name="uploaded_image" id="uploaded_image" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Image 2</label>
                                        <div class="col-md-4">
                                            <div class="custom-file-upload">
                                                <label for="Image2" class="custom-file-upload">
                                                    <i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('no_file') ?>
                                                </label>
                                                <input type="file" name="Image2" id="Image2" accept="image/*" data-msg-accept="<?php echo $this->lang->line('file_extenstion') ?>" onchange="readURL2(this)" />
                                            </div>
                                            <p class="help-block"><?php echo $this->lang->line('img_allow'); ?><br /> <?php echo $this->lang->line('max_file_size'); ?><br /><?php echo $this->lang->line('recommended_size') . '290 * 210.'; ?></p>
                                            <span class="error display-no" id="errormsg"></span>
                                            <div id="img_gallery"></div>
                                            <img id="preview2" height='100' width='150' class="display-no" />

                                            <input type="hidden" name="uploaded_image2" id="uploaded_image2" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Image 3</label>
                                        <div class="col-md-4">
                                            <div class="custom-file-upload">
                                                <label for="Image3" class="custom-file-upload">
                                                    <i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('no_file') ?>
                                                </label>
                                                <input type="file" name="Image3" id="Image3" accept="image/*" data-msg-accept="<?php echo $this->lang->line('file_extenstion') ?>" onchange="readURL3(this)" />
                                            </div>
                                            <p class="help-block"><?php echo $this->lang->line('img_allow'); ?><br /> <?php echo $this->lang->line('max_file_size'); ?><br /><?php echo $this->lang->line('recommended_size') . '290 * 210.'; ?></p>
                                            <span class="error display-no" id="errormsg"></span>
                                            <div id="img_gallery"></div>
                                            <img id="preview3" height='100' width='150' class="display-no" />
                                            <video controls id="v-control" class="display-no">
                                                <source id="source" src="" type="video/mp4">
                                            </video>
                                            <input type="hidden" name="uploaded_image3" id="uploaded_image3" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3">Payment Method</label>
                                        <div class="col-md-4">
                                            <input type="text" name="payment" id="payment" maxlength="100" data-required="1" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('total_rate') ?> <span class="currency-symbol"></span><span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="number" name="total_rate" id="total_rate" value="<?php echo ($total_rate) ? $total_rate : ''; ?>" maxlength="10" data-required="1" class="form-control" readonly="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Advance Paid</label>
                                        <div class="col-md-4">
                                            <input type="number" name="advance" id="advance" maxlength="10" data-required="1" onkeyup="calculation()" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Due</label>
                                        <div class="col-md-4">
                                            <input type="number" name="due" id="due" maxlength="10" data-required="1" class="form-control" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3">Delivery Date<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <div class='input-group date' id='datetimepicker' data-date-format="mm-dd-yyyy">
                                                <input size="16" type="date" name="delivery_date" class="form-control" id="delivery_date">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Delivery Name</label>
                                        <div class="col-md-4">
                                            <input type="text" name="delivery_name" id="delivery_name" maxlength="100" data-required="1" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Delivery Number</label>
                                        <div class="col-md-4">
                                            <input type="text" name="delivery_number" id="delivery_number" data-required="1" class="form-control" />
                                        </div>

                                    </div>
                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn" onclick="printPage()"><?php echo $this->lang->line('submit') ?></button>
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name; ?>/view"><?php echo $this->lang->line('cancel') ?></a>
                                    </div>
                                </div>
                            </form>

                        </div>

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

    function cloneItem() {
        var divid = $(".clone:last").attr('id');
        var getnum = divid.split('cloneItem');
        var oldNum = parseInt(getnum[1]);
        var newNum = parseInt(getnum[1]) + 1;
        newElem = $('#' + divid).clone().attr('id', 'cloneItem' + newNum).fadeIn('slow'); // create the new element via clone(), and manipulate it's ID using newNum value


        newElem.find('.item_id').last().next().next().remove(); //remove previous select2 values
        newElem.find('#rate' + oldNum).attr('id', 'rate' + newNum).attr('name', 'rate[' + newNum + ']').val('').removeClass('error');
        newElem.find('#qty_no' + oldNum).attr('id', 'qty_no' + newNum).attr('name', 'qty_no[' + newNum + ']').attr('onkeyup', 'qty(this.id,' + newNum + ')').val('').removeClass('error');
        newElem.find('#add_vat' + oldNum).attr('id', 'add_vat' + newNum).attr('name', 'add_vat[' + newNum + ']').val('').removeClass('error');
        newElem.find('#name' + oldNum).attr('id', 'name' + newNum).attr('name', 'name[' + newNum + ']').val('').removeClass('error');
        newElem.find('#custom_fee' + oldNum).attr('id', 'custom_fee' + newNum).attr('name', 'custom_fee[' + newNum + ']').val('').removeClass('error');
        newElem.find('#description' + oldNum).attr('id', 'description' + newNum).attr('name', 'description[' + newNum + ']').val('').removeClass('error');

        newElem.find('.error').remove();
        newElem.find('.clone-label').css('visibility', 'hidden');

        $(".clone:last").after(newElem);
        $('#cloneItem' + newNum + ' .remove').html('<div class="item-delete" onclick="deleteItem(' + newNum + ')"><i class="fa fa-remove"></i></div>');
    }

    function deleteItem(id) {
        $('#cloneItem' + id).remove();
        calculation();
    }

    //calculate total rate
    function calculation() {
        var i = 1;
        var total_sum = 0;
        var vat = 0;
        var custom_fee = 0;
        var delivery_charge = ($('#deliveryCharge').val()) ? $('#deliveryCharge').val() : 0;
        while (i <= 20) {
            if (document.getElementById('rate' + i)) {
                sum = (parseFloat($('#rate' + i).val()) * $('#qty_no' + i).val());
                vat = (parseFloat($('#add_vat' + i).val())) ? parseFloat($('#add_vat' + i).val()) : 0;
                custom_fee = (parseFloat($('#custom_fee' + i).val())) ? parseFloat($('#custom_fee' + i).val()) : 0;

                total_sum = parseFloat(total_sum + sum + vat + custom_fee);
                i++;
            } else {
                i++;
            }

        }

        total_sum = total_sum + (parseFloat(delivery_charge));

        if ($('#advance').val()) {
            var advance = $('#advance').val();
            var due = total_sum - (parseFloat(advance));
            $('#due').val(due);
        }
        $('#total_rate').val(total_sum);


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

    function readURL(input) {
        var fileInput = document.getElementById('Image');
        var filePath = fileInput.value;
        var fileUrl = window.URL.createObjectURL(fileInput.files[0]);
        var extension = filePath.substr((filePath.lastIndexOf('.') + 1)).toLowerCase();
        if (input.files[0].size <= 10506316) { // 10 MB
            if (extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        if (extension == 'mp4') {
                            $('#source').attr('src', e.target.result);
                            $('#v-control').show();
                            $('#preview').attr('src', '').hide();
                        } else {
                            $('#preview').attr('src', e.target.result).attr('style', 'display: inline-block;');
                            $('#v-control').hide();
                            $('#source').attr('src', '');
                        }
                        $("#old").hide();
                        $('#errormsg').html('').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                $('#preview').attr('src', '').attr('style', 'display: none;');
                $('#errormsg').html("<?php echo $this->lang->line('file_extenstion'); ?>").show();
                $('#Slider_image').val('');
                $("#old").show();
            }
        } else {
            $('#preview').attr('src', '').attr('style', 'display: none;');
            $('#errormsg').html("<?php echo $this->lang->line('file_size_msg'); ?>").show();
            $('#Slider_image').val('');
            $('#source').attr('src', '');
            $('#v-control').hide();
            $("#old").show();
        }
    }

    function readURL2(input) {
        var fileInput = document.getElementById('Image2');
        var filePath = fileInput.value;
        var fileUrl = window.URL.createObjectURL(fileInput.files[0]);
        var extension = filePath.substr((filePath.lastIndexOf('.') + 1)).toLowerCase();
        if (input.files[0].size <= 10506316) { // 10 MB
            if (extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        if (extension == 'mp4') {
                            $('#source').attr('src', e.target.result);
                            $('#v-control').show();
                            $('#preview2').attr('src', '').hide();
                        } else {
                            $('#preview2').attr('src', e.target.result).attr('style', 'display: inline-block;');
                            $('#v-control').hide();
                            $('#source').attr('src', '');
                        }
                        $("#old").hide();
                        $('#errormsg').html('').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                $('#preview2').attr('src', '').attr('style', 'display: none;');
                $('#errormsg').html("<?php echo $this->lang->line('file_extenstion'); ?>").show();
                $('#Slider_image').val('');
                $("#old").show();
            }
        } else {
            $('#preview2').attr('src', '').attr('style', 'display: none;');
            $('#errormsg').html("<?php echo $this->lang->line('file_size_msg'); ?>").show();
            $('#Slider_image').val('');
            $('#source').attr('src', '');
            $('#v-control').hide();
            $("#old").show();
        }
    }

    function readURL3(input) {
        var fileInput = document.getElementById('Image3');
        var filePath = fileInput.value;
        var fileUrl = window.URL.createObjectURL(fileInput.files[0]);
        var extension = filePath.substr((filePath.lastIndexOf('.') + 1)).toLowerCase();
        if (input.files[0].size <= 10506316) { // 10 MB
            if (extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        if (extension == 'mp4') {
                            $('#source').attr('src', e.target.result);
                            $('#v-control').show();
                            $('#preview3').attr('src', '').hide();
                        } else {
                            $('#preview3').attr('src', e.target.result).attr('style', 'display: inline-block;');
                            $('#v-control').hide();
                            $('#source').attr('src', '');
                        }
                        $("#old").hide();
                        $('#errormsg').html('').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                $('#preview3').attr('src', '').attr('style', 'display: none;');
                $('#errormsg').html("<?php echo $this->lang->line('file_extenstion'); ?>").show();
                $('#Slider_image').val('');
                $("#old").show();
            }
        } else {
            $('#preview3').attr('src', '').attr('style', 'display: none;');
            $('#errormsg').html("<?php echo $this->lang->line('file_size_msg'); ?>").show();
            $('#Slider_image').val('');
            $('#source').attr('src', '');
            $('#v-control').hide();
            $("#old").show();
        }
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


    function address_markup() {
        if ($("input[name=address]:checked").val() == "yes") {
            $(".hide_address").show();
            $(".address_part").hide();
            // $("#user_id").val('');
            // $('#address_id').val('');
        } else if ($("input[name=address]:checked").val() == "no") {
            $(".hide_address").hide();
            $(".address_part").show();
        }
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


    // $('#form_add_order').submit(function() {
    //     $.ajax({
    //         type: "POST",
    //         dataType: "html",
    //         url: BASEURL + "backoffice/order/print_page",
    //         data: $('#form_add_order').serialize(),
    //         cache: false,
    //         beforeSend: function() {
    //             $('#quotes-main-loader').show();
    //         },
    //         success: function(html) {
    //             $('#quotes-main-loader').hide();
    //             var WinPrint = window.open('<?php echo base_url() ?>' + html, '_blank', 'left=0,top=0,width=650,height=630,toolbar=0,status=0');
    //         }
    //     });
    // })
</script>
<?php $this->load->view(ADMIN_URL . '/footer'); ?>