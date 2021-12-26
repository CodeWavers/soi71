<?php $this->load->view(ADMIN_URL . '/header'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo base_url(); ?>assets/admin/css/dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/admin/css/select2.min.css" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" /> -->
<style type="text/css">
  td,
  th {
    padding: 8px;
  }
</style>
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

                  <div class="container">

                    <div class="wrapper">
                      <h4 align="padding-left"><b><i>Item Sales Report (Restaurant Wise)</i></b></h4>
                    </div>

                    <div class="Data">

                      <form method="get" action="" target="_blank">
                        <table class="formcontrols">
                          <tr>
                            <td>
                              <label>Restaurants: </label>
                            </td>

                            <td>
                              <select name="entity_id" id="entity_id" class="restaurant" style="height: 30px;width: 200.59px">
                                <option value="">Select All</option>
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
                          </tr>
                          <tr>
                            <td>
                              <label>From Date :</label>
                            </td>
                            <?php
                            date_default_timezone_set("Asia/bangladesh");
                            ?>
                            <td>

                              <?php if (isset($fdate)) { ?>
                                <input type="date" id="from_date" name="from_date" value="<?php echo $fdate ?>" placeholder="datetime" style="height: 30px">
                              <?php } else { ?>
                                <input type="date" id="from_date" name="from_date" value="" placeholder="datetime" style="height: 30px">
                              <?php } ?>

                            </td>
                            <td style="padding-left:55px;">
                              <label>To Date : </label>
                            </td>
                            <td>

                              <?php if (isset($fdate)) { ?>
                                <input type="date" id="to_date" name="to_date" value="<?php echo $tdate ?>" placeholder="datetime" style="height: 30px">
                              <?php } else { ?>
                                <input type="date" id="to_date" name="to_date" value="" placeholder="datetime" style="height: 30px">
                              <?php } ?>

                            </td>

                          </tr>

                        </table>

                      </form>
                      <br>


                      <div class="table-responsive" style="overflow:auto; width:100%">
                        <table id="resItemSales" class="table table-striped table-bordered" style="display: none;">
                          <thead>

                            <tr>
                              <th>Serial No.</th>
                              <th>Item Name</th>
                              <th>Variation / Addons</th>
                              <th>Quantity</th>
                              <th>Order Value</th>
                            </tr>

                          </thead>

                          <tfoot align="right">
                            <tr>
                              <th class="text-right"></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </tfoot>


                        </table>
                      </div>

                    </div>
                  </div>


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
<script src="<?php echo base_url(); ?>assets/admin/scripts/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/dataTables.min.js"></script>
<!-- <div class="wait-loader display-no" id="quotes-main-loader"><img src="<?php echo base_url() ?>assets/admin/img/ajax-loader.gif" align="absmiddle"></div> -->


<script>
  $(document).ready(function() {
    $('.restaurant').select2();

    $('#entity_id,#to_date,#from_date').change(function() {

      $('#resItemSales').DataTable().destroy(); //So that datatable reinitialize error cant accure.
      var res = $('#entity_id').val();
      var to_date = $('#to_date').val();
      var form_date = $('#from_date').val();

      console.log(to_date, form_date, res);

      if (to_date && form_date) {
        $('#resItemSales').show();


        $('#resItemSales').DataTable({
          // 'responsive': true,

          'pageLength': 200,
          'lengthMenu': [50, 100, 500, 1000, 1500, 2000, 2500, 3000],

          "aaSorting": [
            [1, "desc"]
          ],
          'columns': [{
              orderable: false
            },
            {
              orderable: false
            },
            {
              orderable: false
            },
            {
              orderable: false
            },
            {
              orderable: false
            },

          ],
          "columnDefs": [{
              "bSortable": true,
              "aTargets": [0, 1, 2, 3, 4]
            },

          ],

          'processing': true,
          'serverSide': true,

          "ajax": {
            url: BASEURL + "backoffice/Report_template/showResItemSales",
            type: 'post',
            data: {
              'Res': res,
              'Tdate': to_date,
              'Fdate': form_date,
            },


          },
          //dataSrc: '',
          dom: 'Bfrtip',
          buttons: [
            'pdf', 'excel'
          ],
        });

      }

    });

  });
</script>

<?php $this->load->view(ADMIN_URL . '/footer'); ?>