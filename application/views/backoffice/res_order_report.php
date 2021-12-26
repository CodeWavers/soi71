<?php $this->load->view(ADMIN_URL . '/header'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo base_url(); ?>assets/admin/css/dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/admin/css/select2.min.css" rel="stylesheet" type="text/css" />

<!-- END PAGE LEVEL STYLES -->

<!-- datatables -->
<style type="text/css">
  .btn {
    color: #eee;
    background-color: #FFB300;
    border-color: #cccccc;
    display: block;
  }

  td,
  th {
    padding: 3px;
  }
</style>
<!-- datatables -->
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
            <?php echo "Report Template" ?>
          </h3>
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="fa fa-home"></i>
              <a href="<?php echo base_url() . ADMIN_URL ?>/dashboard">
                <?php echo $this->lang->line('home') ?> </a>
              <i class="fa fa-angle-right"></i>
            </li>
            <li>
              <?php echo "Report Template" ?>
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

                <div style="overflow-x: auto;">
                  <table class="table table-striped table-bordered table-hover" id="datatable_ajax">

                    <!-- avobe code is for page template -->



                    <div class="container">

                      <div class="wrapper">
                        <h4 align="padding-left"><b><i>Order Report(Restaurant Wise)</i></b></h4>
                      </div>
                      <div class="Data">


                        <form method="get" action="<?php echo base_url() . ADMIN_URL ?>/Report_template/resOrderPDF" target="_blank">
                          <table class="formcontrols">
                            <tr>

                              <td>
                                <label>Restaurants: </label>
                              </td>
                              <td style="">
                                <!-- <input type="" id="entity_id" name=""> -->
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
                              <td style="padding-left:55px;">

                                <?php if (isset($fdate)) { ?>
                                  <input type="date" id="from_date" name="from_date" value="<?php echo $fdate ?>" placeholder="datetime" style="height: 30px">
                                <?php } else { ?>
                                  <input type="date" id="from_date" name="from_date" value="" placeholder="datetime" style="height: 30px">
                                <?php } ?>

                              </td>
                              <td>
                                <label>To Date : </label>
                              </td>
                              <td style="padding-left:55px;">

                                <?php if (isset($fdate)) { ?>
                                  <input type="date" id="to_date" name="to_date" value="<?php echo $tdate ?>" placeholder="datetime" style="height: 30px">
                                <?php } else { ?>
                                  <input type="date" id="to_date" name="to_date" value="" placeholder="datetime" style="height: 30px">
                                <?php } ?>

                              </td>
                            </tr>

                          </table>
                          <button type="submit" class=" btn btn-outline-success mr-1 mb-1 waves-effect waves-light">

                            <i class="fa fa-printer"></i> PDF
                          </button>
                        </form>

                        <br>

                        <table id="orderList" class="table table-bordered table-striped table-hover">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Restaurant</th>
                              <th>Order ID</th>
                              <th>Order Date</th>
                              <th>Order Amount</th>
                              <th>Restaurant Commission</th>
                              <th>Restaurant Payable</th>

                            </tr>
                          </thead>
                          <tbody>
                          </tbody>

                          <tfoot align="right">
                            <tr>
                              <th class="text-right"></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th></th>
                            </tr>
                          </tfoot>

                        </table>

                      </div>
                    </div>

                  </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/admin/scripts/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/scripts/dataTables.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('.restaurant').select2();
  });
</script>

<script>
  $(document).ready(function() {

    var table = $('#orderList').DataTable({
      "responsive": false,
      // "autoWidth"  : false,
      // "processing" : true,


      "aaSorting": [
        [2, "desc"]
      ],
      "columnDefs": [{
          "bSortable": true,
          "aTargets": [0, 1, 2, 3, 4, 5, 6]
        },

      ],
      'pageLength': 200,
      'lengthMenu': [5, 10, 20, 50, 100, 200, 500],
      'processing': true,
      'serverSide': true,
      dom: 'Bfrtip',
      buttons: [{
          extend: "excel",
          footer: true,
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6] //Your Colume value those you want excel
          },
          title: "RestaurantOrderList",
          className: "btn-md prints"
        }



      ],

      'serverMethod': 'post',

      'ajax': {
        'url': "<?php echo base_url() ?>backoffice/Report_template/showResOrderReports",
        // type : 'post',
        //   dataType:'json',
        "data": function(data) {
          // //    // Read values
          // data.dropdown = $('#btn-filter').val();
          //var name = $('#searchByName').val();
          data.searchRestaurant = $('#entity_id').val();
          data.searchFromDate = $('#from_date').val();
          data.searchToDate = $('#to_date').val();
          //data.csrf_test_name = $('#csrf_test_name').val();;

          //data.todate = $('#to_date').val();
          console.log(data.searchRestaurant);

        }

      },

      'columns': [{
          data: 'sl',
          orderable: false
        },
        {
          data: 'name'
        },
        {
          data: 'entity_id'
        },
        {
          data: 'order_date'
        },
        {
          data: 'subtotal'
        },
        {
          data: 'commission_value'
        },
        {
          data: 'restaurant_payable',
          'width': '10%',
          orderable: false
        },


      ],
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api(),
          data;

        // converting to interger to find total
        var intVal = function(i) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
            i : 0;
        };

        // computing column Total of the complete result 
        var resTotal = api
          .column(4)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        var cusTotal = api
          .column(5)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        var handTotal = api
          .column(6)
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);



        // Update footer by showing the total with the reference of the column index 
        $(api.column(0).footer()).html('Total');

        //$( api.column(2).footer() ).html(resTotal);
        $(api.column(4).footer()).html(resTotal);
        $(api.column(5).footer()).html(cusTotal);
        // $( api.column( 5 ).footer() ).html(totalEarn);
        //$( api.column( 6 ).footer() ).html(totalDiscount);
        $(api.column(6).footer()).html(handTotal.toLocaleString(undefined, {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        }));
      },
    });

    $('#entity_id,#from_date,#to_date').change(function() {
      table.draw();

    });


  });
</script>

<script>
</script>