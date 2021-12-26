<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Custom Order List</h4>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-sm-12">

                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">Custom Item List</div>
                        </div>
                        <div class="portlet-body form">
                            <form id="form_edit_order_list" name="form_edit_order_list" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <?php if ($order_details) { ?>
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Custom <?php echo $this->lang->line('order') ?></label>
                                            <div class="col-md-4">
                                                <input type="text" name="entity_id" id="entity_id" value="<?php echo $order_details[0]->entity_id; ?>" maxlength="20" data-required="1" class="form-control" readonly />
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="date" value="<?php echo $order_details[0]->date; ?>" />
                                        <input type="hidden" name="user_id" value="<?php echo $order_details[0]->user_id; ?>" />
                                        <input type="hidden" name="restaurant_id" value="<?php echo $order_details[0]->restaurant_id; ?>" />
                                        <input type="hidden" name="address_id" value="<?php echo $order_details[0]->address_id; ?>" />
                                        <div class="form-group">
                                            <label class="control-label col-md-3"><?php echo $this->lang->line('user') ?></label>
                                            <div class="col-md-4">
                                                <input type="text" name="user_name" id="user_name" value="<?php echo $order_details[0]->fname . ' ' . $order_details[0]->lname; ?>" maxlength="20" data-required="1" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Size</label>
                                            <div class="col-md-4">
                                                <input type="text" name="size" id="size" value="<?php echo $order_details[0]->size; ?>" maxlength="20" data-required="1" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Flavour</label>
                                            <div class="col-md-4">
                                                <input type="text" name="flavour" id="flavour" value="<?php echo $order_details[0]->flavour; ?>" maxlength="20" data-required="1" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">User Description</label>
                                            <div class="col-md-4">
                                                <input name="description" id="description" value="<?php echo $order_details[0]->description; ?>" maxlength="400" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Admin Description</label>
                                            <div class="col-md-4">
                                                <textarea type="text" name="admin_description" id="admin_description" placeholder="<?php echo $order_details[0]->admin_description; ?>" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Price</label>
                                            <div class="col-md-4">
                                                <input type="number" name="price" id="price" value="<?php echo $order_details[0]->price; ?>" maxlength="249" class="form-control" required />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Vat</label>
                                            <div class="col-md-4">
                                                <input type="number" name="vat" id="vat" value="<?php echo $order_details[0]->vat; ?>" maxlength="20" data-required="1" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">SD</label>
                                            <div class="col-md-4">
                                                <input type="number" name="sd" id="sd" value="<?php echo $order_details[0]->sd; ?>" maxlength="20" data-required="1" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Delivery Charge</label>
                                            <div class="col-md-4">
                                                <input type="number" name="delivery_charge" id="delivery_charge" value="<?php echo $order_details[0]->delivery_charge; ?>" maxlength="249" class="form-control"  />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Delivery Time</label>
                                            <div class="col-md-4">
                                                <input type="date" name="delivery_time" id="delivery_time" value="<?php echo $order_details[0]->delivery_time; ?>" maxlength="249" class="form-control"  />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Time Slot</label>
                                            <div class="col-md-4">
                                                <select name="time_slot" class="form-control" id="time_slot" style="width: max-content;">
                                                    <option value="">Select Time Slot</option>
                                                    <option value="11.00 AM-2.00 PM" <?php if ($order_details[0]->time_slot == '11.00 AM-2.00 PM') { ?> selected <?php } ?>>11.00 AM-2.00 PM</option>
                                                    <option value="2.00 PM-5.00 PM" <?php if ($order_details[0]->time_slot == '2.00 PM-5.00 PM') { ?> selected <?php } ?>>2.00 PM-5.00 PM</option>
                                                    <option value="5.00 PM-8.00 PM" <?php if ($order_details[0]->time_slot == '5.00 PM-8.00 PM') { ?> selected <?php } ?>>5.00 PM-8.00 PM</option>
                                                    <option value="8.00 PM-11.00 PM" <?php if ($order_details[0]->time_slot == '8.00 PM-11.00 PM') { ?> selected <?php } ?>>8.00 PM-11.00 PM</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-actions fluid">
                                            <div class="col-md-12 text-center">
                                                <div id="loadingModal" class="loader-c" style="display: none;"><img src="<?php echo base_url() ?>assets/admin/img/loading-spinner-grey.gif" align="absmiddle"></div>
                                                <button type="submit" class="btn btn-sm  danger-btn filter-submit margin-bottom" name="submit_page" id="submit_page" value="Save"><span>Accept Order</span></button>
                                                <button type="button" class="btn btn-sm  danger-btn filter-submit margin-bottom" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $('#form_edit_order_list').submit(function() {

        $.ajax({
            type: "POST",
            dataType: "html",
            url: BASEURL + "backoffice/order/updateOrderList",
            data: $('#form_edit_order_list').serialize(),
            cache: false,
            beforeSend: function() {
                $('#quotes-main-loader').show();
            },
            success: function(html) {
                if (html == "success") {
                    $('#quotes-main-loader').hide();
                    $('#view_status_history').modal('hide');
                    grid.getDataTable().fnDraw();
                }
                return false;
            }
        });

        return false;
    });
</script>