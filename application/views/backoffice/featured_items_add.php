<?php
$this->load->view(ADMIN_URL . '/header'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/css/datetimepicker.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
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
        $FieldsArray = array('feature_id', 'restaurant_id', 'menu_item_id', 'description', 'cover_image', 'sort_value');
        foreach ($FieldsArray as $key) {
            $$key = @htmlspecialchars($edit_records->$key);
        }
    }
    if (isset($edit_records) && $edit_records != "" ) {
        $add_label    = $this->lang->line('title_admin_feature_items_edit');
        $user_action  = base_url() . ADMIN_URL . '/' . $this->controller_name . "/edit/" . str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
        //$restaurant_map = array_column($restaurant_map, 'restaurant_id');
        //$item_map = ($coupon_type == 'discount_on_combo')?array_column($item_map,'package_id'):array_column($item_map,'item_id');
        //$itemDetail = $this->coupon_model->getItem($restaurant_map,$coupon_type);
    } else {
        $add_label    = $this->lang->line('title_admin_feature_items_add');
        $user_action  = base_url() . ADMIN_URL . '/' . $this->controller_name . "/add";
        $restaurant_map = array();
        $item_map = array();
        $itemDetail = array();
    }

    ?>

    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->lang->line('title_admin_feature_items'); ?></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url() . ADMIN_URL ?>/dashboard">
                                <?php echo $this->lang->line('home'); ?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/view"><?php echo $this->lang->line('title_admin_feature_items'); ?></a>
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

                            <!--Restaurant dropdown -->

                            <form method="post" action="<?php echo $user_action; ?>" class="form-horizontal" name="restaurantId" value="resId" id="restaurantForm">
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
                                        <div class="col-auto my-3">
                                            <label class="control-label col-md-3" for=""><?php echo $this->lang->line('restaurant') ?> <span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <select class="form-control" name="restaurant_id" id="restaurantDropdown" onchange="this.form.submit()">
                                                    <option value="none">Select</option>
                                                    <?php

                                                    foreach ($allRestaurant->result() as $row) {
                                                    ?>

                                                        <option value="<?php echo $row->entity_id ?>" <?php if ($restaurant_id == $row->entity_id) {  ?> selected <?php } ?>>

                                                            <?php echo $row->name  ?>
                                                        </option>
                                                    <?php
                                                    }

                                                    ?>

                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>


                            <!-- BEGIN FORM-->
                            <form action="<?php echo $user_action; ?>" id="form_add<?php echo $this->prefix ?>" name="form_add<?php echo $this->prefix ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <div id="iframeloading" class="frame-load display-no">
                                    <img src="<?php echo base_url(); ?>assets/img/loading-spinner-grey.gif" alt="loading" />
                                </div>
                                <div class="form-body">



                                    <!--Menu Item -->

                                    <div class="form-group">
                                        <div class="col-auto my-3">
                                            <label class="control-label col-md-3" id="hideItemLabel"><?php echo $this->lang->line('menu_item'); ?><span class="required">*</span></label>
                                            <div class="col-md-4">
                                                <?php if ($restaurant_id) {
                                                    $conn = new mysqli('localhost', $this->db->username, $this->db->password, $this->db->database);
                                                   // $conn = new mysqli('localhost', 'foodaani_hopper', 'Hopper@#2020', 'foodaani_hopper');
                                                    // Check connection
                                                    if ($conn->connect_error) {
                                                        die("Connection failed: " . $conn->connect_error);
                                                    }

                                                    $sql = "select name, entity_id FROM restaurant_menu_item where restaurant_id = '" . $restaurant_id . "'";
                                                    $result = $conn->query($sql);
                                                    // print_r($result);
                                                }

                                                ?>
                                                <select class="form-control" name="menu_item_id" id="selectedItemId">
                                                    <option value="">Select Item</option>
                                                    <?php
                                                    while ($row = $result->fetch_array()) {

                                                    ?>
                                                        <option value="<?php echo $row['entity_id'] ?>" <?php if ($menu_item_id == $row['entity_id']) {
                                                                                                        ?> selected <?php } ?>>
                                                            <?php echo $row['name'];

                                                            ?>

                                                        </option>
                                                    <?php

                                                    }
                                                    ?>
                                                </select>
                                                <?php $conn->close(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"> <?php echo $this->lang->line('title_slider_image') ?></label>
                                        <div class="col-md-4">
                                            <input type="hidden" name="uploadedFeatureImage" id="uploadedFeatureImage" value="<?php echo $cover_image; ?>" />
                                            <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $entity_id; ?>" />

                                            <div class="custom-file-upload">
                                                <label for="cover_image" class="custom-file-upload">
                                                    <i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('no_file') ?>
                                                </label>
                                                <input type="file" name="cover_image" id="cover_image" onchange="readURL(this);" />
                                            </div>
                                            <span class="help-block"> <?php echo $this->lang->line('img_allow') ?></span>
                                            <span class="error display-no" id="errormsg"></span>
                                            <img id="preview" height='100' width='150' class="display-no" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="old">
                                        <label class="control-label col-md-3"></label>
                                        <div class="col-md-4">
                                            <?php if ($cover_image) { ?>
                                                <span class="block"><?php echo $this->lang->line('selected_image') ?></span>
                                                <?php $path_info = pathinfo($cover_image);
                                                $type = $path_info['extension'];
                                                if ($type == 'png' || $type == 'jpg' || $type == 'jpeg' || $type == 'gif') { ?>
                                                    <img id='oldpic' class="img-responsive" src="<?php echo base_url() . 'uploads/' . $cover_image; ?>">
                                            <?php }
                                            } ?>
                                        </div>
                                    </div>

                                    <!-- Menu Detail -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('detail') ?></label>
                                        <div class="col-md-8">
                                            <input type="text" name="description" id="description" value="<?php echo $description; ?>" maxlength="249" class="form-control" />
                                        </div>
                                       
                                        <input type="hidden" name="featureId" value="<?php echo $feature_id ?>" />
                                        <input type="hidden" name="resId" value="<?php echo $restaurant_id ?>" />
                                    </div>

                                    <!-- Sort Value -->
                                    <!-- <div class="form-group">
                                        <label class="control-label col-md-3">Sort Value</label>
                                        <div class="col-md-8">
                                            <input type="text" name="sort_value" id="sort_value" value="<?php echo $sort_value; ?>" class="form-control" />
                                        </div>
                                    </div> -->


                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" name="submit_page" id="submit_page" value="Submit" class="btn red"><?php echo $this->lang->line('submit') ?></button>
                                        <a class="btn default" href="<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name; ?>/view"><?php echo $this->lang->line('cancel') ?></a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/plugins/multiselect/jquery.sumoselect.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/pages/scripts/admin-management.js"></script>
<?php if ($this->session->userdata("language_slug") == 'ar') {  ?>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/admin/pages/scripts/localization/messages_ar.js"> </script>
<?php } ?>
<?php if ($this->session->userdata("language_slug") == 'fr') {  ?>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
<?php } ?>
<script>
    jQuery(document).ready(function() {
       
    });

    

    function readURL(input) {

        var fileInput = document.getElementById('cover_image');
        var filePath = fileInput.value;
        var extension = filePath.substr((filePath.lastIndexOf('.') + 1));
        if (input.files[0].size <= 5242880) { // 5 MB
            if (extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result).attr('style', 'display: inline-block;');
                        $("#old").hide();
                        $('#errormsg').html('').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                $('#preview').attr('src', '').attr('style', 'display: none;');
                $('#errormsg').html('<?php echo $this->lang->line('file_extenstion') ?>').show();
                $('#cover_image').val('');
                $("#old").show();
            }
        } else {
            $('#preview').attr('src', '').attr('style', 'display: none;');
            $('#errormsg').html('<?php echo $this->lang->line('file_size_msg') ?>').show();
            $('#cover_image').val('');
            $("#old").show();
        }
    }
</script>
<?php $this->load->view(ADMIN_URL . '/footer'); ?>