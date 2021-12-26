<?php $this->load->view(ADMIN_URL . '/header'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->
<div class="page-container">

    <!-- BEGIN sidebar -->
    <?php $this->load->view(ADMIN_URL . '/sidebar'); ?>
    <!-- END sidebar -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE header-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">
                        <?php echo "Report Template"/*$this->lang->line('titleadmin_report_template')*/ ?>
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url() . ADMIN_URL ?>/dashboard">
                                <?php echo $this->lang->line('home') ?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            <?php echo "Report Template"/*$this->lang->line('titleadmin_report_template')*/ ?>
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">
                                <?php echo "REPORT"/* $this->lang->line('titleadmin_report_template') ?> <?php echo $this->lang->line('list')*/ ?>
                            </div>
                            <!--  -->

                        </div>
                        <div class="portlet-body">

                            <div class="table-container">

                                <?php
                                if ($this->session->flashdata('page_MSG')) { ?>
                                    <div class="alert alert-success">
                                        <?php echo $this->session->flashdata('page_MSG'); ?>
                                    </div>
                                <?php } ?>
                                <div id="delete-msg" class="alert alert-success hidden">
                                    <?php echo $this->lang->line('success_delete'); ?>
                                </div>

                                <table class="table table-striped table-bordered table-hover" id="datatable_ajax">

                                    <!-- avobe code is for page template -->

                                    <link rel="stylesheet" type="text/css" href="css/style.css" />
                                    <div class="container">

                                        <div class="wrapper">
                                            <h4 align="padding-left"><b><i>Canceled Report</i></b></h4>
                                        </div>
                                        <div class="Data">

                                            <form method="post" action="<?php echo base_url() . ADMIN_URL ?>/Report_template/showCancelReport">
                                                <table class="formcontrols">
                                                    <tr>
                                                        <td>
                                                            <label> Restaurent Name: </label>
                                                        </td>
                                                        <?php if ($this->session->userdata('UserType') == 'Admin') {
                                                            $restaurant_details = $this->report_model->restaurant_details($this->session->userdata('adminemail'));
                                                        ?>
                                                            <td style="padding-left:55px;">
                                                                <label><?php echo $restaurant_details[0]->name ?></label>
                                                                <input type="hidden" name="entity_id" id="entity_id" value="<?php echo $restaurant_details[0]->entity_id ?>" />
                                                            </td>
                                                        <?php } else if ($this->session->userdata('UserType') == 'MasterAdmin') { ?>
                                                            <td style="padding-left:55px;">
                                                                <select name="entity_id" id="entity_id" style="height: 30px;width: 217.59px">
                                                                    <option>Select</option>;
                                                                    <?php
                                                                    foreach ($res->result() as $row) {
                                                                        if (isset($entity_id) && $row->entity_id == $entity_id) { ?>
                                                                            <option value="<?php echo $row->entity_id; ?>" selected="selected"><?php echo $row->name; ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $row->entity_id; ?>"><?php echo $row->name; ?></option>
                                                                    <?php }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label>From Date :</label>
                                                        </td>

                                                        <td style="padding-left:55px;">

                                                            <?php if (isset($fdate)) { ?>
                                                                <input type="datetime-local" id="from_date" name="Fdate" value="<?php echo $fdate ?>" placeholder="datetime" style="height: 30px">
                                                            <?php } else { ?>
                                                                <input type="datetime-local" id="from_date" name="Fdate" value="<?php echo date('Y-m-d') ?>" placeholder="datetime" style="height: 30px">
                                                            <?php } ?>

                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label>To Date : </label>
                                                        </td>
                                                        <td style="padding-left:55px;">

                                                            <?php if (isset($fdate)) { ?>
                                                                <input type="datetime-local" id="to_date" name="Tdate" value="<?php echo $tdate ?>" placeholder="datetime" style="height: 30px">
                                                            <?php } else { ?>
                                                                <input type="datetime-local" id="to_date" name="Tdate" value="<?php echo date('Y-m-d') ?>" placeholder="datetime" style="height: 30px">
                                                            <?php } ?>

                                                        </td>
                                                    </tr>
                                                </table>

                                                <button>
                                                    <span class="title"><?php echo "Show" /*$this->lang->line('report_template');*/ ?></span>
                                                    <span class="selected"></span>
                                                </button>
                                            </form>

                                            <?php
                                            //print_r($groups);
                                            if (isset($order_data)) {
                                                $total_subtotal = 0;
                                                $delivery_charge = 0;
                                                $coupon_discount = 0;
                                                $vat = 0;
                                                $sd = 0;
                                                $total_rate = 0;
                                            ?>
                                                <div style="margin-left:calc(86.5% - 60px);">
                                                    <input type="button" name="export" class="btn btn-primary btn-xs" value="Download as PDF" onclick="getPDF('<?php echo $entity_id; ?>' ,'<?php echo $tdate; ?>','<?php echo $fdate; ?>','cancelReport')" />
                                                </div>

                                                <form method="post" action="<?php echo base_url() . ADMIN_URL ?>/Report_template/export">
                                                    <div class="col-md-6" style="left:calc(85% - 68px);">
                                                        <input type="submit" name="export" class="btn btn-primary btn-xs" value="Download as Excel" />
                                                    </div>
                                                    <input type="hidden" name="type" value="cancel_order">
                                                    <input type="hidden" name="tdata" value="<?php echo $tdate; ?>">
                                                    <input type="hidden" name="fdata" value="<?php echo $fdate; ?>">
                                                    <input type="hidden" name="entity_id" value="<?php echo $entity_id; ?>">
                                                    <div class="table-responsive" style="overflow:auto; width:100%">
                                                        <table class="table table-striped table-bordered" style="margin-top:30px;">
                                                            <tr>
                                                                <th>Serial No.</th>
                                                                <th>Order Number</th>
                                                                <th>User Name</th>
                                                                <th>Delivery Address</th>
                                                                <th>Delivery Date</th>
                                                                <th>Item Name (Quantity)</th>
                                                                <th>Food Price</th>
                                                                <th>Delivery Charge</th>
                                                                <th>Discount</th>
                                                                <th>VAT</th>
                                                                <th>SD</th>
                                                                <th>Total</th>
                                                            </tr>


                                                            <?php
                                                            $num = 1;
                                                            foreach ($order_data->result() as $row) {
                                                                $user_detail = unserialize($row->user_detail);
                                                                $items = unserialize($row->item_detail);

                                                                foreach ($items as $key => $value) {
                                                                    $product[] = "<li>" . $value['item_name'] . "(" .  $value['qty_no'] . ')';
                                                                }
                                                                $string = implode(" ", $product);
                                                                unset($product);

                                                                echo '
    <tr>
    <td>' . $num . '</td>
    <td>' . $row->entity_id . '</td>
    <td>' . $user_detail['first_name'] . ' ' . $user_detail['last_name'] . '</td>
    <td>' . $user_detail['address'] . '<br> ' . $user_detail['landmark'] . '<br>' . $user_detail['zipcode'] . '</td>
    <td>' . date('d/m/Y h:i:s A', strtotime($row->order_date)) . '</td>
    <td>' . $string . '</td>
    <td>' . $row->subtotal . '</td>
    <td>' . $row->delivery_charge . '</td>
    <td>' . $row->coupon_discount . '</td>
    <td>' . $row->vat . '</td>
    <td>' . $row->sd . '</td>
    <td>' . $row->total_rate . '</td>
    </tr>
    ';

                                                                $total_subtotal += $row->subtotal;
                                                                $delivery_charge += $row->delivery_charge;
                                                                $coupon_discount += $row->coupon_discount;
                                                                $vat += $row->vat;
                                                                $sd += $row->sd;
                                                                $total_rate += $row->total_rate;
                                                                $num++;
                                                            }
                                                            echo '
    <tr>
    <td><b>Total</b></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>' . $total_subtotal . '</td>
    <td>' . $delivery_charge . '</td>
    <td>' . $coupon_discount . '</td>
    <td>' . $vat . '</td>
    <td>' . $sd . '</td>
    <td>' . $total_rate . '</td>
    </tr>
    ';
                                                            ?>
                                                        </table>


                                                    </div>
                                                    <input type="hidden" name="total_subtotal" value="<?php echo $total_subtotal; ?>">
                                                    <input type="hidden" name="delivery_charge" value="<?php echo $delivery_charge; ?>">
                                                    <input type="hidden" name="coupon_discount" value="<?php echo $coupon_discount; ?>">
                                                    <input type="hidden" name="vat" value="<?php echo $vat; ?>">
                                                    <input type="hidden" name="sd" value="<?php echo $sd; ?>">
                                                    <input type="hidden" name="total_rate" value="<?php echo $total_rate; ?>">
                                                </form>
                                            <?php
                                            }
                                            if (isset($order_details)) {
                                                echo $order_details;
                                            }
                                            ?>
                                        </div>
                                    </div>



                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
            <!-- END PAGE CONTENT-->
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<script>
    function getPDF(entity_id, Tdate, Fdate, type) {
        $.ajax({
            type: "POST",
            dataType: "html",
            url: BASEURL + "backoffice/Report_template/getPDF",
            data: {
                'entity_id': entity_id,
                'ToDate': Tdate,
                'FromDate': Fdate,
                'type': type
            },
            cache: false,
            beforeSend: function() {
                $('#quotes-main-loader').show();
            },
            success: function(html) {
                $('#quotes-main-loader').hide();
                var WinPrint = window.open('<?php echo base_url() ?>' + html, '_blank', 'left=0,top=0,width=650,height=630,toolbar=0,status=0');
            }
        });
    }
</script>

<?php $this->load->view(ADMIN_URL . '/footer'); ?>