<div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Advance Payment</h4>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-sm-12">

                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">Payment</div>
                        </div>
                        <div class="portlet-body form">
                            <form id="form_add_payment" name="form_add_payment" method="post" class="form-horizontal" enctype="multipart/form-data">
                                <?php if ($quotes_details) { ?>
                                    <div class="form-body">

                                        <input type="hidden" name="time_slot" value="<?php echo $quotes_details->time_slot; ?>" />
                                        <input type="hidden" name="delivery_time" value="<?php echo $quotes_details->delivery_time; ?>" />
                                        <input type="hidden" name="accepted_time" value="<?php echo $quotes_details->accepted_time; ?>" />
                                        <input type="hidden" name="date" value="<?php echo $quotes_details->date; ?>" />
                                        <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $quotes_details->entity_id; ?>" />
                                        <input type="hidden" name="minimum_order" id="minimum_order" value="" />
                                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $quotes_details->user_id; ?>" />
                                        <input type="hidden" name="restaurant_id" id="restaurant_id" value="<?php echo $quotes_details->restaurant_id; ?>" />
                                        <input type="hidden" name="address_id" id="address_id" value="<?php echo $quotes_details->address_id; ?>" />


                                        <div class="form-group">
                                            <label class="control-label col-md-3">Item Price</label>
                                            <div class="col-md-4">
                                                <input type="number" name="subtotal" id="subtotal" value="<?php echo $quotes_details->price; ?>" maxlength="20" data-required="1" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Vat</label>
                                            <div class="col-md-4">
                                                <input type="number" name="vat" id="vat" value="<?php echo $quotes_details->vat; ?>" maxlength="20" data-required="1" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">SD</label>
                                            <div class="col-md-4">
                                                <input type="number" name="sd" id="sd" value="<?php echo $quotes_details->sd; ?>" maxlength="20" data-required="1" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Delivery Charge</label>
                                            <div class="col-md-4">
                                                <input type="number" name="delivery_charge" id="delivery_charge" value="<?php echo $quotes_details->delivery_charge; ?>" maxlength="249" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <?php $total_rate = $quotes_details->delivery_charge + $quotes_details->sd + $quotes_details->vat + $quotes_details->price; ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Total Price</label>
                                            <div class="col-md-4">
                                                <input name="total_rate" id="total_rate" value="<?php echo $total_rate; ?>" maxlength="50" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Advance Payment</label>
                                            <div class="col-md-4">
                                                <input type="number" name="advance" id="advance" value="" class="form-control" onkeyup="check()" required />
                                            </div>
                                            <div id="message"></div>
                                            <div id="error"></div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Due</label>
                                            <div class="col-md-4">
                                                <input type="number" name="due" id="due" value="" maxlength="50" class="form-control" readonly />
                                            </div>
                                        </div>

                                        <div class="form-actions fluid">
                                            <div class="col-md-12 text-center">
                                                <div id="loadingModal" class="loader-c" style="display: none;"><img src="<?php echo base_url() ?>assets/admin/img/loading-spinner-grey.gif" align="absmiddle"></div>
                                                <button type="submit" class="btn btn-sm  danger-btn filter-submit margin-bottom" name="submit_page" id="submit_page" value="Save"><span>Submit</span></button>
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
    // function calculation()
    // {
    // var delivery_charge = ($('#delivery_charge').val()) ? $('#delivery_charge').val() : 0;
    // var subtotal = ($('#subtotal').val()) ? $('#subtotal').val() : 0;
    // var vat = ($('#vat').val()) ? $('#vat').val() : 0;
    // var sd = ($('#sd').val()) ? $('#sd').val() : 0;

    // var total_price = parseInt(delivery_charge) + parseInt(vat) + parseInt(sd) + parseInt(subtotal);
    // console.log(total_price)
    var total_price = $('#total_rate').val();

    var minimum_order = parseInt(total_price * 0.6);
    $('#minimum_order').val(minimum_order)
    $('#message').html("Minimum Advance Payment Amount : " + minimum_order);
    $('#message').css('color', 'green')
    $('#message').css('font-weight', 'bold')
    //}

    $(document).ready(function() {
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });


    function check() {
        var advance = $('#advance').val();
        var minimum_order = $('#minimum_order').val();
        var total_price = $('#total_rate').val();
        
        if (parseInt(advance) >= parseInt(minimum_order)) {
            var due = total_price - advance;
            $('#due').val(due)
            $('#message').hide()
            $('#error').hide()
            $(':input[type="submit"]').prop("disabled", false);
        } else {
            $('#message').show()
            $('#error').show()
            $('#error').html("<br>Advance Payment must be greater than Minumum Advance Payment Amount.");
            $('#error').css('color', 'red')
            $('#error').css('margin-left', '2%')
            $('#error').css('font-weight', 'bold')
            $(':input[type="submit"]').prop("disabled", true);
        }
    }

    $('#form_add_payment').submit(function() {

        $.ajax({
            type: "POST",
            dataType: "html",
            url: BASEURL + "backoffice/order/updatePayment",
            data: $('#form_add_payment').serialize(),
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