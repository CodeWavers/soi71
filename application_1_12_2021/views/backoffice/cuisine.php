<?php $this->load->view(ADMIN_URL . '/header'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<script src="<?php echo base_url(); ?>assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- END PAGE LEVEL STYLES -->
<div class="page-container">
    <!-- BEGIN sidebar -->
    <?php $this->load->view(ADMIN_URL . '/sidebar'); ?>
    <!-- END sidebar -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <!-- BEGIN PAGE header-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                    <h3 class="page-title">
                        Cuisine
                    </h3>
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="<?php echo base_url() ?>dashboard">
                                <?php echo $this->lang->line('home') ?> </a>
                            <i class="fa fa-angle-right"></i>
                        </li>
                        <li>
                            Cuisine
                        </li>
                    </ul>
                    <!-- END PAGE TITLE & BREADCRUMB-->
                </div>
            </div>
            <!-- END PAGE header-->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet box red">
                        <div class="portlet-title">
                            <div class="caption">Cuisine</div>
                            <div class="actions c-dropdown">
                                <button type="button" class="btn btn-sm danger-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $this->lang->line('add') ?><span class="caret"></span>
                                </button>
                                <!-- <a class="btn danger-btn btn-sm" id="sortButton" data-toggle="modal" data-target="#sortModal"><i class="fa fa-sort"></i> Sort </a> -->

                                <ul class="dropdown-menu pull-right" role="menu">
                                    <?php foreach ($Languages as $lang) { ?>
                                        <li><a href="<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name . '/add/' . $lang->language_slug ?>"><?php echo $lang->language_name ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="modal" tabindex="-1" role="dialog" id="sortModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sort Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="col-auto my-3">
                                            <label class="control-label col-md-3" for=""><?php echo $this->lang->line('restaurant') ?></label>
                                            <div class="col-md-8">

                                                <select class="form-control" name="restaurant_id" id="restaurantDropdown">
                                                    <option value="none">Select</option>
                                                    <?php

                                                    foreach ($allRestaurant as $row) {
                                                    ?>

                                                        <option value="<?php echo $row->entity_id ?>">

                                                            <?php echo $row->name  ?>
                                                        </option>
                                                    <?php
                                                    }

                                                    ?>

                                                </select>

                                            </div>
                                        </div>

                                        <br><br>


                                        <ul class="reorder_ul reorder_menu_list">
                                            <!-- <li style="color: black;">gdgd</li> -->

                                            <li class="ui-sortable-handle">

                                                <a href="javascript:void(0);" class="menu_link">
                                                    <h4></h4>
                                                </a>

                                            </li>

                                        </ul>



                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                        <button type="button" id="saveButton" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <div class="table-container">
                                <?php if ($this->session->flashdata('page_MSG')) { ?>
                                    <div class="alert alert-success">
                                        <?php echo $this->session->flashdata('page_MSG'); ?>
                                    </div>
                                <?php } ?>
                                <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th class="table-checkbox">#</th>
                                            <th>Cuisine Name</th>
                                            <th><?php echo $this->lang->line('status') ?></th>
                                            <th>Action</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td></td>
                                            <td><input type="text" class="form-control form-filter input-sm" name="page_title"></td>
                                            <td>
                                                <select name="Status" class="form-control form-filter input-sm">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <option value="1"><?php echo $this->lang->line('active') ?></option>
                                                    <option value="0"><?php echo $this->lang->line('inactive') ?></option>
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm red filter-submit"><i class="fa fa-search"></i> <?php echo $this->lang->line('search') ?></button>
                                                <button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> <?php echo $this->lang->line('reset') ?></button>
                                            </td>
                                        </tr>
                                    </thead>
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
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/admin/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/datatable.js"></script>
<script>
    var grid;
    jQuery(document).ready(function() {
        Layout.init(); // init current layout    
        grid = new Datatable();
        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function(grid) {
                // execute some code after table records loaded
            },
            onError: function(grid) {
                // execute some code on network or other general error  
            },
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                "sDom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                "aoColumns": [{
                        "bSortable": false
                    },
                    {
                        "bSortable": false
                    },
                    {
                        "bSortable": false
                    },
                    {
                        "bSortable": false
                    }
                ],
                "sPaginationType": "bootstrap_full_number",
                "oLanguage": {
                    "sProcessing": sProcessing,
                    "sLengthMenu": sLengthMenu,
                    "sInfo": sInfo,
                    "sInfoEmpty": sInfoEmpty,
                    "sGroupActions": sGroupActions,
                    "sAjaxRequestGeneralError": sAjaxRequestGeneralError,
                    "sEmptyTable": sEmptyTable,
                    "sZeroRecords": sZeroRecords,
                    "oPaginate": {
                        "sPrevious": sPrevious,
                        "sNext": sNext,
                        "sPage": sPage,
                        "sPageOf": sPageOf,
                        "sFirst": sFirst,
                        "sLast": sLast
                    }
                },
                "bServerSide": true, // server side processing
                "sAjaxSource": "ajaxview", // ajax source
                "aaSorting": [
                    [3, "desc"]
                ] // set first column as a default sort by asc
            }
        });
        $('#datatable_ajax_filter').addClass('hide');
        $('input.form-filter, select.form-filter').keydown(function(e) {
            if (e.keyCode == 13) {
                grid.addAjaxParam($(this).attr("name"), $(this).val());
                grid.getDataTable().fnDraw();
            }
        });
    });
    // method for deleting
    function deleteDetail(entity_id, content_id) {
        bootbox.confirm({
            message: "<?php echo $this->lang->line('delete_module'); ?>",
            buttons: {
                confirm: {
                    label: '<?php echo $this->lang->line('ok'); ?>',
                },
                cancel: {
                    label: '<?php echo $this->lang->line('cancel'); ?>',
                }
            },
            callback: function(deleteConfirm) {
                if (deleteConfirm) {
                    jQuery.ajax({
                        type: "POST",
                        dataType: "html",
                        url: 'ajaxDelete',
                        data: {
                            'entity_id': entity_id
                        },
                        success: function(response) {
                            grid.getDataTable().fnDraw();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                }
            }
        });
    }

    function deleteAll(content_id) {
        bootbox.confirm({
            message: "<?php echo $this->lang->line('delete_module'); ?>",
            buttons: {
                confirm: {
                    label: '<?php echo $this->lang->line('ok'); ?>',
                },
                cancel: {
                    label: '<?php echo $this->lang->line('cancel'); ?>',
                }
            },
            callback: function(deleteConfirm) {
                if (deleteConfirm) {
                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: 'ajaxDeleteAll',
                        data: {
                            'content_id': content_id
                        },
                        success: function(response) {
                            grid.getDataTable().fnDraw();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                }
            }
        });
    }

    function disable_record(ID, Status) {
        var StatusVar = (status == 0) ? "<?php echo $this->lang->line('active_module'); ?>" : "<?php echo $this->lang->line('deactive_module'); ?>";
        bootbox.confirm({
            message: StatusVar,
            buttons: {
                confirm: {
                    label: '<?php echo $this->lang->line('ok'); ?>',
                },
                cancel: {
                    label: '<?php echo $this->lang->line('cancel'); ?>',
                }
            },
            callback: function(disableConfirm) {
                if (disableConfirm) {
                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: 'ajaxDisable',
                        data: {
                            'entity_id': ID,
                            'status': Status,
                            'tblname': 'cuisine'
                        },
                        success: function(response) {
                            grid.getDataTable().fnDraw();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                }
            }
        });
    }

    function disableAll(ContentID, Status) {
        var StatusVar = (status == 0) ? "<?php echo $this->lang->line('active_module'); ?>" : "<?php echo $this->lang->line('deactive_module'); ?>";
        bootbox.confirm({
            message: StatusVar,
            buttons: {
                confirm: {
                    label: '<?php echo $this->lang->line('ok'); ?>',
                },
                cancel: {
                    label: '<?php echo $this->lang->line('cancel'); ?>',
                }
            },
            callback: function(disableConfirm) {
                if (disableConfirm) {
                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: 'ajaxDisableAll',
                        data: {
                            'content_id': ContentID,
                            'status': Status,
                            'tblname': 'category'
                        },
                        success: function(response) {
                            grid.getDataTable().fnDraw();
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                }
            }
        });
    }

    $('#restaurantDropdown').change(function() {
        // $('#myForm').submit();
        // $('#myForm').serialize();
        var item_id = $(this).val();
        $.ajax({
            type: "POST",
            // async: true,
            dataType: 'json',
            // dataType: "html",
            url: '<?php echo base_url() . ADMIN_URL . '/' . $this->controller_name ?>/getMenuCategory',
            data: {
                'item_id': item_id,
            },
            success: function(data) {

                // var data = JSON.stringify(data);
                // var value = json_decode(data);
                var html = '';
                var i;
                for (i in data) {
                    html += '<ul class="reorder_ul reorder_menu_list"><li class="ui-sortable-handle" id=' + data[i].id + '><a href="javascript:void(0);" class="menu_link"><h4>' + data[i].name + '</h4></a></li></ul>';

                }


                $('ul.reorder_menu_list').html(html);


            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });

    })

    $('#sortButton').on('click', function() {
        $("ul.reorder_menu_list").sortable({
            tolerance: 'pointer',
        });
        $('.menu_link').attr("href", "javascript:void(0);");
        $('.menu_link').css("cursor", "move");
        $("#saveButton").click(function(e) {


            var list = [];
            var res_id = $('#restaurantDropdown').val();

            $("ul.reorder_menu_list li").each(function() {
                list.push($(this).attr('id'));
            });

            $.ajax({
                type: "POST",
                url: "<?php echo base_url() . ADMIN_URL . '/category/orderUpdate'; ?>",
                data: {
                    ids: " " + list + "",
                    res_id: res_id
                },
                success: function() {
                    window.location.reload();
                },
                error: function() {
                    alert(console.error());
                }
            });
            return false;
            // }	
            e.preventDefault();
        });
    });
</script>
<?php $this->load->view(ADMIN_URL . '/footer'); ?>