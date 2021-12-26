<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/plugins/data-tables/DT_bootstrap.css" />
<!-- <title>All Order Report</title> -->
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
                    <th>Serial No.</th>
                    <th>Order Number</th>
                    <th>Delivery Date</th>
                    <th>Customer Name</th>
                    <th>Restaurants</th>
                    <th>Rider Name</th>
                    <th>Food Price</th>
                    <th>VAT</th>
                    <th>SD</th>
                    <th>Restaurant Pay</th>
                    <th>Delivery Charge</th>
                    <th>Discount</th>
                    <th>Customer Pay</th>
                    <th>Status</th>

                </tr>

            </thead>
            
            <tbody>
           <?php  
                foreach($report->result() as $key=>$row)  
                {  
                 $food_bill= $row->subtotal+$row->coupon_discount;
                 $resto_pay=$row->subtotal+$row->vat+$row->sd-$row->commission_value;
                   if (!empty($row->r_name)) {
                       $rider_name=$row->r_name;
                   } else{
                      $rider_name="Not assigned by system admin";      
                   }
    
                   $totalFoodBill+=$food_bill;
                   $totalDeliveryCharge+=$row->delivery_charge;
                   $totalCouponDiscount+=$row->coupon_discount;
                   $totalVat+=$row->vat;
                   $totalSD+=$row->sd;
                   $totalRestoPay+=$resto_pay;
                   $totalCusPay+=$row->total_rate;
                    //name has to be same as in the database.  
                    echo '<tr>  
                                <td>'.++$key.'</td>  
                                <td>'.$row->e_id.'</td>  
                                <td>'.date("d-m-Y H:i:s",strtotime($row->order_date)).'</td>  
                                <td>'.$row->first_name.'</td>  
                                <td>'.$row->name.'</td>  
                                <td>'.$rider_name.'</td>  
                                <td>'.$food_bill.'</td>  
                                <td>'.$row->vat.'</td>  
                                <td>'.$row->sd.'</td>  
                                <td>'.$resto_pay.'</td>  
                                <td>'.$row->delivery_charge.'</td>  
                                <td>'.$row->coupon_discount.'</td>  
                                <td>'.$row->total_rate.'</td>     
                                <td>'.strtoupper($row->order_status).'</td>  
                               
                               
                    </tr>';  
                }  
            ?>  
            </tbody>
            <tfoot>
                <?php
                     echo   '<tr>
                              <td colspan="6">'.Total.'</td>
                              <td>'.$totalFoodBill.'</td>
                              <td>'.$totalVat.'</td>
                              <td>'.$totalSD.'</td>
                              <td>'.$totalRestoPay.'</td>
                              <td>'.$totalDeliveryCharge.'</td>
                               <td>'.$totalCouponDiscount.'</td>
                              <td>'.$totalCusPay.'</td>
                              <td></td>
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