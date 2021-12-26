<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<!-- <title>Restaurant Report</title> -->
<style type="text/css">

 
    body {
        font-family: Arial
    }

    .pdf_main {
        background: #fff;
        margin-left: 25px;
        margin-right: 25px;
    }

    th,td{
 border: 1px solid;
 padding-left: 5px;
 font-size: 12px;
}
    .div-thead {
        color: #ffffff;
        font-size: 14px;
        background-color: #FFB300;
    }



img {
  float: left;
  width: 5%;
  height: 5%
}


</style>

<div class="head-main">
    <!-- <div class="logo"> <img src="<?php echo base_url(); ?>/assets/admin/img/logo.png" alt="" width="240" height="122"/> </div> -->

</div>


<!-- Header -->
<div class="pdf_main">

  <div style="text-align: right;font-size: 12px" >
   
    <div><img style="margin-top: -15px; margin-left: 360px;" src="assets/admin/img/logo.png" alt=""></div>
   
  </div>
   <p style="text-align: center;"><?php echo strtoupper($title); ?><?php if(!empty($from_date && $to_date)){
    echo ' From '.date('d/m/Y',strtotime($from_date))." To ".date('d/m/Y',strtotime($to_date));
    } elseif (!empty($from_date)) {
     echo ' From '.date('d/m/Y',strtotime($from_date));
    }elseif(!empty($to_date)){
     echo " Till ".date('d/m/Y',strtotime($to_date));
    }?>

   </p>   
    <div class="table-responsive">
        <table class="table table-striped table-bordered">

            <thead>
                <tr class="div-thead">
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
           <?php  
                foreach($report->result() as $key=>$row)  
                {  
                   $restaurant_payable=$row->subtotal-$row->delivery_charge; 
                   $trp=$restaurant_payable+$row->vat+$row->sd;

                   $totalFoodBill+=$row->subtotal;
                   $totalDeliveryCharge+=$row->delivery_charge;
                   $totalVat+=$row->vat;
                   $totalSD+=$row->sd;
                   $totalRP+=$trp;
                   $totalCD+=$row->coupon_discount;
                    //name has to be same as in the database.  
                    echo '<tr>  
                                <td>'.++$key.'</td>  
                                <td>'.$row->name.'</td> 
                                <td>'.$row->e_id.'</td>  
                                <td>'.date("d-m-Y H:i:s",strtotime($row->order_date)).'</td>  
                                <td>'.$row->subtotal.'</td>    
                                <td>'.$row->delivery_charge.'</td> 
                                <td>'.$trp.'</td>  
                               
                               
                               
                    </tr>';  
                }  
            ?>  
            </tbody>
            <tfoot>
                <?php
                     echo   '<tr>
                              <td colspan="4">'.Total.'</td>
                              <td>'.$totalFoodBill.'</td>
                              <td>'.$totalDeliveryCharge.'</td>
                              <td>'.$totalRP.'</td>
                            </tr>';     
                ?>
            </tfoot>
  
        </table>
    </div>
    <!-- </div> -->

    <!-- body -->
    <div>

    </div>


    <!-- Footer part for Price end -->