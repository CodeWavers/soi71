<?php
$this->load->view(ADMIN_URL . '/header'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/css/datetimepicker.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->
    <?php $this->load->view(ADMIN_URL . '/sidebar');

    if ($this->input->post()) {
        foreach ($this->input->post() as $key => $value) {
            $$key = @htmlspecialchars($this->input->post($key));
        }
    } else {
        $FieldsArray = array('content_id', 'entity_id', 'name', 'ingredients', 'restaurant_id', 'category_id', 'price', 'menu_detail', 'recipe_detail', 'popular_item', 'availability', 'image', 'image2' , 'image3','is_veg', 'recipe_time', 'check_add_ons', 'item_slug', 'vat', 'sd', 'end_date');
        foreach ($FieldsArray as $key) {
            $$key = @htmlspecialchars($edit_records->$key);
        }
    }
    if (isset($edit_records) && $edit_records != "") {
        $add_label    = $this->lang->line('edit') . ' ' . $this->lang->line('menu');
        $form_action  = base_url() . ADMIN_URL . '/' . $this->controller_name . "/edit_menu/" . $this->uri->segment('4') . '/' . str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
        $add_ons = array_keys($add_ons_detail);
    } else {
        $add_label    = $this->lang->line('add') . ' ' . $this->lang->line('menu');
        $form_action  = base_url() . ADMIN_URL . '/' . $this->controller_name . "/add_menu/" . $this->uri->segment('4');
        $addons_detail = 1;
        $add_ons = array();
        $add_ons_detail = array();
    }
    $usertypes = getUserTypeList($this->session->userdata('language_slug'));
    ?>

    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title"><?php echo $this->lang->line('menu') ?></h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url() . ADMIN_URL ?>/dashboard">
                                <?php echo $this->lang->line('home') ?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url() . ADMIN_URL ?>/restaurant/view_menu"><?php echo $this->lang->line('menu') ?></a>
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
                            <form action="<?php echo $form_action; ?>" id="form_add<?php echo $this->menu_prefix ?>" name="form_add<?php echo $this->menu_prefix ?>" method="post" class="form-horizontal horizontal-form-deal" enctype="multipart/form-data">
                                <div id="iframeloading" class="frame-load display-no">
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
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('res_name') ?><span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <select name="restaurant_id" class="form-control" id="restaurant_id" onchange="getCurrency(this.value)">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php if (!empty($restaurant)) {
                                                    foreach ($restaurant as $key => $value) { ?>
                                                        <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $restaurant_id) ? "selected" : "" ?>><?php echo $value->name ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('category') ?><span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <select name="category_id" class="form-control" id="category_id">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php if (!empty($category)) {
                                                    foreach ($category as $key => $value) { ?>
                                                        <option value="<?php echo $value->entity_id ?>" <?php echo ($value->entity_id == $category_id) ? "selected" : "" ?>><?php echo $value->name ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="clone" id="cloneItem1">
                                            <label class="control-label col-md-3"><?php echo $this->lang->line('name') ?><span class="required">*</span></label>
                                            <div class="col-md-8">
                                                <input type="hidden" id="entity_id" name="entity_id" value="<?php echo $entity_id; ?>" />
                                                <input type="hidden" id="content_id" name="content_id" value="<?php echo ($content_id) ? $content_id : $this->uri->segment('5'); ?>" />
                                                <input type="hidden" id="item_slug" name="item_slug" value="<?php echo ($item_slug) ? $item_slug : ''; ?>" />
                                                <input type="text" name="name" id="name" value="<?php echo $name; ?>" maxlength="249" data-required="1" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('add_add_ons') ?></label>
                                        <div class="col-md-8">
                                            <input type="checkbox" name="check_add_ons" id="check_add_ons" value="1" <?php echo ($check_add_ons == 1) ? 'checked' : '' ?> class="add_ons">
                                        </div>
                                    </div>
                                    <?php if (!empty($addons_category)) { ?>
                                        <div class="form-group category_wrap <?php echo ($check_add_ons == 1) ? 'display-yes' : 'display-no' ?>">
                                            <label class="control-label col-md-3"><?php echo $this->lang->line('addons_category') ?><span class="required">*</span></label>
                                            <div class="col-md-8">
                                                <?php $is_multiple = '';
                                                $j = 1;
                                               
                                                foreach ($addons_category as $key => $value) {
                                                    $k = 0;
                                                    $addons_detail = (array_key_exists($value->entity_id, $add_ons_detail)) ? $add_ons_detail[$value->entity_id] : 1; ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="checkbox" class="category_checkbox" <?php echo (in_array($value->entity_id, $add_ons)) ? 'checked' : '' ?> name="addons_category_id[]" id="addons_category_id<?php echo $value->entity_id ?>" value="<?php echo $value->entity_id ?>" onchange="addAddons('<?php echo $j ?>','<?php echo $value->entity_id ?>',this.id)"> <?php echo $value->name ?>

                                                            <div id="add_ons_category<?php echo $j; ?>" class="repeater_wrap add_ons_category<?php echo $value->entity_id ?> <?php echo (in_array($value->entity_id, $add_ons)) ? 'display-yes' : 'display-no' ?>">
                                                                <?php $is_multiple = (array_key_exists($value->entity_id, $add_ons_detail)) ? $addons_detail[$k]->is_multiple : '';  ?>
                                                                <input type="checkbox" class="is_multiple" name="is_multiple[<?php echo $value->entity_id ?>]" id="is_multiple<?php echo $value->entity_id ?>" value="1" <?php echo ($is_multiple) ? 'checked' : '' ?>> <?php echo $this->lang->line('is_multiple') ?>
                                                                <div data-repeater-list="add_ons_list[<?php echo $value->entity_id ?>]" class="add_ons_detail<?php echo $value->entity_id ?>">
                                                                    <?php
                                                                    for ($i = 0; $i < count($addons_detail); $i++) {
                                                                        // $is_multiple = (array_key_exists($value->entity_id, $add_ons_detail)) ? $addons_detail[$i]->is_multiple : '';
                                                                    
                                                                    ?>
                                                                    <div data-repeater-item class="outer-repeater">
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <label class="control-label"><?php echo $this->lang->line('add_ons_name') ?><span class="required">*</span></label>
                                                                                <input type="text" name="add_ons_name" id="add_ons_name<?php echo $j ?>" value="<?php echo (!empty($addons_detail[$i])) ? $addons_detail[$i]->add_ons_name : ''; ?>" class="form-control repeater_field name_repeater" maxlength="249">
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label class="control-label"><?php echo $this->lang->line('price') ?><span class="required">*</span></label>
                                                                                <input type="text" name="add_ons_price" id="add_ons_price<?php echo $j ?>" value="<?php echo (!empty($addons_detail[$i])) ? $addons_detail[$i]->add_ons_price : ''; ?>" class="form-control repeater_field digits price_repeater" min="0" maxlength="19">
                                                                            </div>
                                                                            <div class="col-sm-2 delete-repeat <?php echo ($i > 0 && !empty($add_ons_detail)) ? 'display-yes' : 'display-no'; ?>">
                                                                                <label class="control-label">&nbsp;</label>
                                                                                <input data-repeater-delete class="btn btn-danger <?php echo ($i > 0 && !empty($add_ons_detail)) ? 'delete_repeater' : '' ?>" type="button" value="<?php echo $this->lang->line('delete') ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                </div>
                                                                <?php //if($i == 0){ 
                                                                ?>
                                                                <div class="form-group">
                                                                    <div class="col-md-12 add_ons_detail<?php echo $value->entity_id ?>">
                                                                        <input data-repeater-create class="btn btn-green" type="button" value="<?php echo $this->lang->line('add') ?>" />
                                                                    </div>
                                                                </div>
                                                                <?php //} 
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php $j++;
                                                }   ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group price_tag <?php echo ($check_add_ons == 1) ? 'display-no' : 'display-yes' ?>">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('price') ?> <span id="currency-symbol"></span><span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="text" name="price" id="price" value="<?php echo ($price) ? $price : '' ?>" maxlength="19" data-required="1" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">VAT(%)</label>
                                        <div class="col-md-8">
                                            <input type="text" name="vat" id="vat" value="<?php echo $vat ?>" maxlength="10" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">SD(%)</label>
                                        <div class="col-md-8">
                                            <input type="text" name="sd" id="sd" value="<?php echo $sd ?>" maxlength="10" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('detail') ?></label>
                                        <div class="col-md-8">
                                            <input type="text" name="menu_details" id="menu_details" value="<?php echo $menu_detail; ?>" maxlength="249" data-required="1" class="form-control" />
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
                                            
                                            <input type="hidden" name="uploaded_image" id="uploaded_image" value="<?php echo isset($image) ? $image : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="old">
                                        <label class="control-label col-md-3"></label>
                                        <div class="col-md-4">
                                            <?php if (isset($image) && $image != '') { ?>
                                                <span class="block"><?php echo $this->lang->line('selected_image'); ?></span>
                                                <img id='oldpic' class="img-responsive" src="<?php echo base_url() . 'uploads/' . $image; ?>">
                                            <?php }  ?>
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
                                           
                                            <input type="hidden" name="uploaded_image2" id="uploaded_image2" value="<?php echo isset($image2) ? $image2 : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="old2">
                                        <label class="control-label col-md-3"></label>
                                        <div class="col-md-4">
                                            <?php if (isset($image2) && $image2 != '') { ?>
                                                <span class="block"><?php echo $this->lang->line('selected_image'); ?></span>
                                                <img id='oldpic' class="img-responsive" src="<?php echo base_url() . 'uploads/' . $image2; ?>">
                                            <?php }  ?>
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
                                           
                                            <input type="hidden" name="uploaded_image3" id="uploaded_image3" value="<?php echo isset($image3) ? $image3 : ''; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="old3">
                                        <label class="control-label col-md-3"></label>
                                        <div class="col-md-4">
                                            <?php if (isset($image3) && $image3 != '') { ?>
                                                <span class="block"><?php echo $this->lang->line('selected_image'); ?></span>
                                                <img id='oldpic' class="img-responsive" src="<?php echo base_url() . 'uploads/' . $image3; ?>">
                                            <?php }  ?>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('ingredients'); ?></label>
                                        <div class="col-md-8">
                                           <textarea name="ingredient" id="ingredient" class="form-control ckeditor"><?php echo $ingredient ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('recipe_detail'); ?></label>
                                        <div class="col-md-8">
                                           <textarea name="recipe_details" id="recipe_details" class="form-control ckeditor"><?php echo $recipe_details ?></textarea>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('recipe_time'); ?><span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control" name="recipe_time" id="recipe_time" value="<?php echo $recipe_time ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('popular_item'); ?></label>
                                        <div class="col-md-1">
                                            <input type="checkbox" name="popular_item" id="popular_item" value="1" <?php echo (isset($popular_item) && $popular_item == 1) ? 'checked' : '' ?> />
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('food_type'); ?><span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <input type="radio" name="is_veg" id="is_veg" value="1" checked="" <?php echo ($is_veg) ? ($is_veg == '1') ? 'checked' : '' : 'checked' ?>>Veg
                                            <input type="radio" name="is_veg" id="non-veg" value="0" <?php echo ($is_veg == '0') ? 'checked' : '' ?>>Non veg
                                        </div>
                                    </div>     -->
                                    <div class="form-group">
                                        <label class="control-label col-md-3"><?php echo $this->lang->line('availability'); ?><span class="required">*</span></label>
                                        <div class="col-md-8">
                                            <?php $availability = explode(',', @$availability); ?>
                                            <select name="availability[]" class="form-control" id="availability" multiple="">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <option value="Morning" <?php echo @in_array('Morning', $availability) ? 'selected' : ''; ?>><?php echo $this->lang->line('morning'); ?></option>
                                                <option value="Lunch" <?php echo @in_array('Lunch', $availability) ? 'selected' : ''; ?>><?php echo $this->lang->line('lunch'); ?></option>
                                                <option value="Dinner" <?php echo @in_array('Dinner', $availability) ? 'selected' : ''; ?>><?php echo $this->lang->line('dinner'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                          <label class="control-label col-md-3"><?php echo $this->lang->line('end_date'); ?></label>
                                          <div class="col-md-8">
                                              <input size="16" type="text" name="end_date" class="form-control" id="end_date" value="<?php echo ($end_date)?date('Y-m-d H:i',strtotime($end_date)):"" ?>" >
                                              <button type="button" name="reset_end_date" id="reset_end_date" value="reset_end_date" class="btn btn-success danger-btn" onclick="resetEndDate('end_date')"><?php echo $this->lang->line('reset_end_date'); ?></button>
                                          </div>
                                      </div>  -->
                                </div>
                                <div class="form-actions fluid">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" name="submit_page" id="submit_page" value="Submit" class="btn btn-success danger-btn"><?php echo $this->lang->line('submit'); ?></button>
                                        <a class="btn btn-danger danger-btn" href="<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/view_menu"><?php echo $this->lang->line('cancel'); ?></a>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/pages/scripts/admin-management.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/repeater/jquery.repeater.js"></script>
<script>
    jQuery(document).ready(function() {
        Layout.init(); // init current layout

        $("#restaurant_id").select2({

        });

        $("#category_id").select2({

        });
    });

//     $(function() {
//     $('#start_date').datetimepicker({
//         format: 'yyyy-mm-dd hh:ii',
//         autoclose: true,
//     });
//      $('#end_date').datetimepicker({
//         format: 'yyyy-mm-dd hh:ii',
//         autoclose: true,
//     });
// });

// $(function() {

// $('#end_date').datetimepicker({
//     format: 'yyyy-mm-dd hh:ii',
//     autoclose: true,

// });

// });

function resetEndDate(id){
        // $(id).val('');
        $('#end_date').val('')
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
                            $('#preview').attr('src', '').hide();
                        } else {
                            $('#preview2').attr('src', e.target.result).attr('style', 'display: inline-block;');
                            $('#v-control').hide();
                            $('#source').attr('src', '');
                        }
                        $("#old2").hide();
                        $('#errormsg').html('').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                $('#preview2').attr('src', '').attr('style', 'display: none;');
                $('#errormsg').html("<?php echo $this->lang->line('file_extenstion'); ?>").show();
                $('#Slider_image').val('');
                $("#old2").show();
            }
        } else {
            $('#preview2').attr('src', '').attr('style', 'display: none;');
            $('#errormsg').html("<?php echo $this->lang->line('file_size_msg'); ?>").show();
            $('#Slider_image').val('');
            $('#source').attr('src', '');
            $('#v-control').hide();
            $("#old2").show();
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
                            $('#preview').attr('src', '').hide();
                        } else {
                            $('#preview3').attr('src', e.target.result).attr('style', 'display: inline-block;');
                            $('#v-control').hide();
                            $('#source').attr('src', '');
                        }
                        $("#old3").hide();
                        $('#errormsg').html('').hide();
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            } else {
                $('#preview3').attr('src', '').attr('style', 'display: none;');
                $('#errormsg').html("<?php echo $this->lang->line('file_extenstion'); ?>").show();
                $('#Slider_image').val('');
                $("#old3").show();
            }
        } else {
            $('#preview3').attr('src', '').attr('style', 'display: none;');
            $('#errormsg').html("<?php echo $this->lang->line('file_size_msg'); ?>").show();
            $('#Slider_image').val('');
            $('#source').attr('src', '');
            $('#v-control').hide();
            $("#old3").show();
        }
    }
    //repeater 
    $('.repeater_wrap').repeater({
        <?php if ($entity_id == '') { ?>
            isFirstItemUndeletable: true,
        <?php } ?>
        show: function() {
            var count = $('.outer-repeater').length;
            $(this).slideDown();
            $(this).find('.delete-repeat').show();
            $(this).find('.repeater_field').attr('required', true);
            $(this).find('.repeater_field').addClass('error');
            $(this).find('.name_repeater').attr('id', 'add_ons_name' + count + 1);
            $(this).find('.price_repeater').attr('id', 'add_ons_price' + count + 1);
        },
        hide: function(deleteElement) {
            
            $(this).slideUp(deleteElement);
        }
    });
    //add add ons
    function addAddons(key, entity_id, id) {
        if ($('#' + id).is(':checked')) {
            $('#add_ons_category' + key).show();
            $('.add_ons_category' + entity_id).find('.repeater_field').attr('required', true);
            $('.add_ons_category' + entity_id).find('.repeater_field').addClass('error');
        } else {
            $('#add_ons_category' + key).hide();
            $('.add_ons_category' + entity_id).find('.repeater_field').val('');
            $('.add_ons_category' + entity_id).find('.delete_repeater').trigger('click');
            $('.add_ons_category' + entity_id).find('.repeater_field').attr('required', false);
            $('.add_ons_category' + entity_id).find('.repeater_field').removeClass('error');
            $('#is_multiple' + entity_id).attr('checked', false);
            $('label.error').remove();
        }
    }
    $('.add_ons').change(function() {
        if ($(this).is(':checked')) {
            $('.category_wrap').show();
            $('.price_tag').hide();
            $('#price').val('');
        } else {
            $('.category_wrap').hide();
            $('.price_tag').show();
            $('.repeater_field').val('');
            $('.delete_repeater').trigger('click');
            $('.category_checkbox').attr('checked', false);
            $('.repeater_wrap').hide();
            $('.repeater_field').attr('required', false);
            $('.repeater_field').removeClass('error');
            $('label.error').remove();
            $('.is_multiple').attr('checked', false);
        }
    });
</script>
<?php $this->load->view(ADMIN_URL . '/footer'); ?>