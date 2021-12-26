<?php $this->load->view(ADMIN_URL . '/header'); ?>

<!-- BEGIN PAGE LEVEL STYLES -->
<link href="<?php echo base_url();?>assets/admin/css/dataTables.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/admin/css/select2.min.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->

<!-- datatables -->
<style type="text/css">
  .btn {
    color: #eee;
    background-color: #FFB300;
    border-color: #cccccc; 
    display: block;
}

 td, th {
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

                <table class="table table-striped table-bordered table-hover" id="datatable_ajax">

                  <!-- avobe code is for page template -->



                  <div class="container">

                    <div class="wrapper">
                      <h4 align="padding-left"><b><i>User Acquisition Report</i></b></h4>
                    </div>
                    <div class="Data">



                    <!-- <form method="post" action="<?php echo base_url() . ADMIN_URL ?>/Report_template/getRidersDetails"> -->
                      <!-- <?php echo form_open('','')?> -->


                        <table class="formcontrols">
                       <!--    <tr>

                          </tr> -->

                          <tr>
                            <td>
                              <label>From Date :</label>
                            </td>
                            <?php

                            date_default_timezone_set("Asia/bangladesh");
                            ?>
                            <td style="padding-left:55px;">

                              <?php if (isset($fdate)) { ?>
                                <input type="date" id="from_date" name="Fdate" value="<?php echo date("d-m-Y",strtotime($fdate)) ?>" placeholder="datetime" style="height: 30px">
                              <?php } else { ?>
                                <input type="date" id="from_date" name="Fdate" value="" placeholder="datetime" style="height: 30px">
                              <?php } ?>

                            </td>

                            <td></td>

                                               <td>
                              <label>To Date : </label>
                            </td>
                            <td style="padding-left:55px;">

                              <?php if (isset($fdate)) { ?>
                                <input type="date" id="to_date" name="Tdate" value="<?php echo date("d-m-Y",strtotime($tdate)) ?>" placeholder="datetime" style="height: 30px">
                              <?php } else { ?>
                                <input type="date" id="to_date" name="Tdate" value="" placeholder="datetime" style="height: 30px">
                              <?php } ?>

                            </td>

                          </tr>

                      <!--     <tr>
         
                          </tr> -->
                      
                        
                        </table>
                         <br>
                          <!-- <button type="button" id="btn-filter" class="btn btn-success">Find</button> -->
            

                    
                      <!-- </form>  -->
                      <!-- <?php echo form_close()?> -->

                 <br><br>
                   
                      <table id="userList" class="table table-bordered table-striped table-hover">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Name</th>
                              <th>Date</th>
                              <th>Phone</th>
                             <!--  <th>Delivery Charge</th> -->
                        
                             
                            </tr>
                          </thead>
                        <tbody>
                        </tbody>
                      <!--     <tfoot align="right">
                            <tr><th></th><th></th><th></th><th></th></tr>
                          </tfoot> -->
                      </table>
                     

                  </div>   
           <!--   <div class="text-center" id="print">
                <input type="button" class="btn btn-warning" name="btnPrint" id="btnPrint" value="Print" onclick="getPDF();"/>
            </div> -->


<!--                     <script>
                      function getPDF() {
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
 -->
<script src="<?php echo base_url();?>assets/admin/scripts/select2.min.js"></script>
<script src="<?php echo base_url();?>assets/admin/scripts/dataTables.min.js"></script>

<script type="text/javascript">
                      $(document).ready(function() {
                                 $('.restaurant').select2();
                        });
</script>

<script>

     
$(document).ready(function(){
            
            var table= $('#userList').DataTable({
             "responsive" : true,
            // "autoWidth"  : false,
            // "processing" : true,
            // "Paginate": true,
             "aLengthMenu": [ 500, 1000, 1500, 2000, 3000,5000 ],
            "iDisplayLength": 500,

            "aaSorting": [[2, "desc" ]],
            "columnDefs": [
                { "bSortable": true, "aTargets": [0,1,2,3] },

            ],
            'processing': true,
            'serverSide': true,

            dom:"'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip", buttons:[ {
                extend: "copy",footer:false,
                exportOptions: {
                    columns: [ 0,1,2,3] //Your Colume value those you want
                }, className: "btn-sm prints"
              }
     
                , {
                    extend: "pdfHtml5",footer:false,
                    exportOptions: {
                        columns: [0,1,2,3] //Your Colume value those you want print
                    }, title: "User Acquisition List", className: "btn-sm prints"
                }
                , {
                    extend: "excel",
                    exportOptions: {
                        columns: [0,1,2,3 ] //Your Colume value those you want excel
                    }, title: "user_list", className: "btn-sm prints"
                }
                , {
                    extend: "print",footer:false,
                    exportOptions: {
                        columns: [ 0,1,2,3] //Your Colume value those you want print
                    },title: "<center>User Acquisition List</center>", className: "btn-sm prints"
                }
            ],
             
            'serverMethod': 'post',

            'ajax': {
                'url' : "<?php echo base_url() ?>backoffice/Report_template/showUserAcquisition",
                // type : 'post',
              //   dataType:'json',
               "data": function(data){
              // //    // Read values
               // data.dropdown = $('#btn-filter').val();
                //var name = $('#searchByName').val();
                // data.searchRestaurant= $('#entity_id').val();
                data.searchFromDate=$('#from_date').val();
                data.searchToDate=$('#to_date').val();
               //data.csrf_test_name = $('#csrf_test_name').val();;

               //data.todate = $('#to_date').val();
                console.log(data.searchFromDate);
             
              }
             
            },
    
            'columns':[
                {data:'sl',orderable:false},
                {data:'first_name'},
                {data:'created_date'},
                {data:'mobile_number'},
           
            
                
            ],
      //       "footerCallback": function ( row, data, start, end, display ) {
      //       var api = this.api(), data;
 
      //       // converting to interger to find total
      //       var intVal = function ( i ) {
      //           return typeof i === 'string' ?
      //               i.replace(/[\$,]/g, '')*1 :
      //               typeof i === 'number' ?
      //                   i : 0;
      //       };
 
      //       // computing column Total of the complete result 
      //       var resTotal = api
      //           .column( 3 )
      //           .data()
      //           .reduce( function (a, b) {
      //               return intVal(a) + intVal(b);
      //           }, 0 );
        
      //       var cusTotal = api
      //           .column( 4 )
      //           .data()
      //           .reduce( function (a, b) {
      //               return intVal(a) + intVal(b);
      //           }, 0 );

      //       var handTotal = api
      //           .column( 5 )
      //           .data()
      //           .reduce( function (a, b) {
      //               return intVal(a) + intVal(b);
      //           }, 0 );
       
 
        
      //       // Update footer by showing the total with the reference of the column index 
      // $( api.column( 0 ).footer() ).html('Total');
        
      //       //$( api.column(2).footer() ).html(resTotal);
      //       $( api.column(3).footer() ).html(resTotal);
      //       $( api.column( 4 ).footer() ).html(cusTotal);
      //      // $( api.column( 5 ).footer() ).html(totalEarn);
      //       //$( api.column( 6 ).footer() ).html(totalDiscount);
      //       $( api.column( 5 ).footer() ).html(handTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
      //   },
        });
          
        $('#entity_id,#from_date,#to_date').change(function(){
            table.draw();

        });


     });

       
 
  </script>

<script>
</script>
