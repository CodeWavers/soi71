<?php $this->load->view(ADMIN_URL.'/header');?>
<div class="page-container">
<!-- BEGIN sidebar -->
<?php $this->load->view(ADMIN_URL.'/sidebar');?>
<!-- END sidebar -->
<?php
if($this->input->post()){
  foreach ($this->input->post() as $key => $value) {
    $$key = @htmlspecialchars($this->input->post($key));
  } 
} else {
  $FieldsArray = array('entity_id','name','status','delivery_charge','rider_commision');
  foreach ($FieldsArray as $key) {
    $$key = @htmlspecialchars($edit_records->$key);
  }
}
if(isset($edit_records) && $edit_records !="")
{
    $add_label    = $this->lang->line('edit').' Delivery Area ';        
    $form_action      = base_url().ADMIN_URL."/".$this->controller_name."/edit/".$this->uri->segment('4').'/'.str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encryption->encrypt($edit_records->entity_id));
}
else
{
    $add_label    = $this->lang->line('add').' Delivery Area';        
    $form_action      = base_url().ADMIN_URL."/".$this->controller_name."/add/".$this->uri->segment('4');
}?>
    <div class="page-content-wrapper">
        <div class="page-content">            
            <!-- BEGIN PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">Delivery Area</h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url().ADMIN_URL;?>/dashboard">
                            <?php echo $this->lang->line('home') ?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <a href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/view">Delivery Area</a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo $add_label;?> 
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
                            <div class="caption"><?php echo $add_label;?></div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="<?php echo $form_action;?>" id="form_add_<?php echo $this->prefix ?>" name="form_add_<?php echo $this->prefix ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
                                <div class="form-body">                                     
                                    <?php if(validation_errors()){?>
                                    <div class="alert alert-danger">
                                        <?php echo validation_errors();?>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Area Name<span class="required">*</span></label>
                                        <div class="col-md-4">
                                            <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $entity_id;?>" />
                                            
                                            <input type="text" name="name" id="name" value="<?php echo $name;?>" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label col-md-3">Delivery Charge</label>
                                        <div class="col-md-4">
                                            
                                            <input type="text" name="delivery_charge" id="delivery_charge" value="<?php echo $delivery_charge;?>" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Rider Commision</label>
                                        <div class="col-md-4">
                                            
                                            <input type="text" name="rider_commision" id="rider_commision" value="<?php echo $rider_commision;?>" maxlength="249" data-required="1" class="form-control"/>
                                        </div>
                                    </div>
                                    
                                                                 
                                </div>
                                <div class="form-actions right">
                                    <a class="btn default" href="<?php echo base_url().ADMIN_URL.'/'.$this->controller_name?>/view"><?php echo $this->lang->line('cancel') ?></a>
                                    <button type="submit" name="submit_page" id="submit_page" value="Submit" class="btn red"><?php echo $this->lang->line('submit') ?></button>
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
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/admin/pages/scripts/admin-management.js"></script>
<script>
jQuery(document).ready(function() {       
    Layout.init(); // init current layout
});
function readURL(input) {
    var fileInput = document.getElementById('Image');
    var filePath = fileInput.value;
    var extension = filePath.substr((filePath.lastIndexOf('.') + 1));
    if(input.files[0].size <= 5242880){ // 5 MB
        if(extension == 'png' || extension == 'jpg' || extension == 'jpeg' || extension == 'gif') {
            if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result).attr('style','display: inline-block;');
                $("#old").hide();
                $('#errormsg').html('').hide();
            }
            reader.readAsDataURL(input.files[0]);
            }
        }
        else{
            $('#preview').attr('src', '').attr('style','display: none;');
            $('#errormsg').html('<?php echo $this->lang->line('file_extenstion') ?>').show();
            $('#Slider_image').val('');
            $("#old").show();
        }
    }else{
        $('#preview').attr('src', '').attr('style','display: none;');
        $('#errormsg').html('<?php echo $this->lang->line('file_size_msg') ?>').show();
        $('#Slider_image').val('');
        $("#old").show();
    }
}
</script>
<?php $this->load->view(ADMIN_URL.'/footer');?>