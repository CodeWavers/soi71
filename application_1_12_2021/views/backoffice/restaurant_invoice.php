<style type="text/css">
body {
	font-family: Arial
}
.pdf_main {
	background: #fff;
	margin-left: 25px;
	margin-right: 25px;
}
.clearfix {
	clear: both;
}
ul, li {
	list-style: none;
	margin: 0px;
	padding: 0px;
}
.head-main {
	align-items: center;
	width: 100%;
	margin-bottom: 5px;
}
.pdf_main .logo {
	float: left;
	padding-top: 24px;
	width: 30%;
}
.pdf_main .logo:hover {
	opacity: 1;
}
.pdf_main .head-right {
	float: right;
	width: 330px;
}
.pdf_main .quote-title {
	float: right;
	text-align: right;
	width: 100%;
	padding-bottom: 15px;
}
.pdf_main .col-li {
	float: left;
	display: inline-block;
	text-align: center;
	padding: 0 5px;
	font-size: 38px;
	font-weight: 700;
	width:120px;
}
.pdf_main .col-li span {
	font-weight: 400;
}
.pdf_main .col-li .icon {
	display: block;
	padding-bottom: 5px;
}
.pdf_main .main-container {
	float: left;
	width: 100%;
}
.pdf_main .head-main h3 {
	text-align: right;
	margin-bottom: 20px;
	float: right;
}
.pdf_main .head-right li.last, .pdf_main .head-right li:last-child {
	padding-right: 0px;
}
.bill-ship-details {
	margin: 0 -4%;
	clear: both;
}
.pdf_main .colm {
	float: left;
	padding: 0 4%;
	width: 40%;
}
.pdf_main .footer {
	background-color: #0076c0;
	float: left;
	text-align: center;
	display: block;
	padding: 12px 0 0px;
	box-sizing: border-box;
	margin-top: 30px;
	width: 650px;
}
.foot-li {
	color: #fff;
	font-size: 38px;
	font-weight: bold;
}
.foot-li.last {
	border-right: none;
}
.pdf_main table {
	border: 2px #bebcbc solid;
	border-collapse: collapse;
}
.pdf_main table tbody td {
	border: none !important;
}
.pdf_main table th {
	border: none !important;
}
.pdf_main .pdf_table {
	margin-bottom: 50px;
	margin-bottom: 30pt;
}
.pdf_main .pdf_table p {
	color: #000000;
	font-size: 38px;
	font-weight: 400;
	margin-bottom: 10px;
}
.bill-ship-details .colm h3 {
	border-bottom: 2px solid #000000;
	font-size: 38px;
	padding-bottom: 7px;
	margin-bottom: 12px;
}
.bill-ship-details p {
	font-size: 38px;
	color: #000
}
.pdf_main .pdf_table table td[colspan="3"] {
	padding-top: 24px;
}
.pdf_main .pdf_table thead th, .pdf_main .pdf_table tfoot td.grand-total,.div-thead {
	color: #ffffff;
	font-size: 38px;
	background-color: #ffb300;
}
.div-thead-black{
  color: #ffffff;
  font-size: 38px;
  background-color: #000000;
}
.pdf_main .pdf_table {
	margin-bottom: 50px;
	margin-bottom: 30pt;
}
.pdf_main .pdf_table p {
	color: #000000;
	font-size: 38px;
	font-weight: 400;
	margin-bottom: 10px;
}
.signature h4.signature-heading {
	font-size: 38px;
	display: inline-block;
	margin: 0px;
}
.signature .signature-line {
	border-bottom: 1px solid black;
	display: inline-block;
	vertical-align: middle;
	width: 311px;
	margin-left: 8px;
.black-theme.pdf_main .pdf_table thead th {
  color: #ffffff;
  font-size: 38px;
  background-color: #000000;
  text-align:left;
}
.black-theme.pdf_main tfoot td.grand-total {
	color: #ffffff;
	background-color: #000000;
}
.black-theme.pdf_main .footer {
	background-color: #000000;
	border-bottom: 3px #000000 solid;
}
.black-theme.pdf_main .footer li {
	border-right: 0;
}
.black-theme.pdf_main table tbody td {
	border: none !important;
}
.black-theme.pdf_main table th {
	border: none !important;
}
.lenth-sec {

	margin-left: 5px;
}
.lenth-sec > label {
	font-weight: 400;
}
.lenth-sec {
	height: 31px;
	vertical-align: top;
}
tr, td, th {
	border: 1px solid #bebcbc;
}
/*.pdf_main {
	margin-left: 38px;
	margin-right: 38px
}*/
.table-style tr td, .table-style tr td{padding-top:4px; padding-bottom:4px;}
.table-style tr .border-line{padding-bottom:7px;}
.segment-main {
  width: 100%;
  border: 2px solid #bebcbc;
  font-size: 38px;
}

/*new*/
.fright{float: right;}
.fleft{float: left;}
.full-width100{width: 100px;}
.clr{clear:both; height:10px}
.div_1{text-align:left;width:5%;float:left;padding:5px 0 5px 10px;font-size: 38px;}
.div_2{text-align:left;width:35%;float:left;padding:5px 0 5px 10px;font-size: 38px;}
.div_3{text-align:center;width:15%;float:left;padding:5px 0 5px 0;font-size: 38px;}
.div_4{text-align:center;width:15%;float:left;padding:5px 0 5px 0;font-size: 38px;}
.div_5{text-align:center;width:20%;float:left;padding:5px 0 5px 0;font-size: 38px;}
.b0{border-bottom: 0}
.width60{width: 60%}
.width15{width: 15%}
.width20{width: 20%}
</style>

<div class="pdf_main">
    <div class="head-main">
         <?php $restaurant_detail = unserialize($menu_item->restaurant_detail);?>
	
	    <div  style="width:100%;  align-items: center;">
	       <p style="	font-size: 38px;"><?php echo $restaurant_detail->name;?></p>
	       <p style="	font-size: 38px;">ORDER ID:<?php echo $order_records->entity_id;?></p>
	        <p style="	font-size: 38px;">DATE:<?php $date = date("d-m-Y h:i A",strtotime($order_records->order_date)); echo $date; ?></p>
	    </div>
	  
	    
    </div>

	<div class="segment-main">
		<!-- Header -->
        <div class="div-thead">
          	<div>
          		<div class="div_1">#</div>
	            <div class="div_2"><?php echo $this->lang->line("item"); ?></div>
	            <div class="div_3"><?php echo $this->lang->line("price"); ?></div>
	            <div class="div_4"><?php echo $this->lang->line("qty"); ?></div>
	            <div class="div_5"><?php echo $this->lang->line("total"); ?></div>
            </div>
        </div>
        <!-- body -->
        <div>
        	<?php $item_detail = unserialize($menu_item->item_detail);
        	 if(!empty($item_detail)){ $Subtotal = 0; $i = 1;
        	 	$addons_name_list = '';
        		foreach($item_detail as $key => $value){ 
        			if($value['is_customize'] == 1){
			            foreach ($value['addons_category_list'] as $k => $val) {
			                $addons_name = '';
			                foreach ($val['addons_list'] as $m => $mn) {
			                    $addons_name .= $mn['add_ons_name'].', ';
			                    if($value['is_deal'] != 1){
			                    	$Subtotal = $Subtotal + $mn['add_ons_price'];
			                    }
			                }
			                if($value['is_deal'] != 1){
			                	$addons_name_list .= '<p><b>'.$val['addons_category'].'</b>:'.substr($addons_name, 0, -2).'</p>';
			            	}else{
			            		$addons_name_list .= '<p>'.substr($addons_name, 0, -2).'</p>';
			            	}
			            }
			    	}
			    	$price = ($value['offer_price'])?$value['offer_price']:$value['rate']; ?>
	            <div  class="b0">
	            	<div  class="div_1"><?php echo $i ?></div>
		            <div class="div_2"><?php echo $value['item_name']; ?><?php echo $addons_name_list; ?></div>
		            <div class="center div_3"><?php echo ($Subtotal)?number_format_unchanged_precision($Subtotal,$restaurant_detail->currency_code):number_format_unchanged_precision($price,$restaurant_detail->currency_code) ?></div>
		            <div class="center div_4"><?php echo $value['qty_no'] ?></div>
		            <div  class="center div_5"><?php echo ($Subtotal)?number_format_unchanged_precision($Subtotal * $value['qty_no'],$restaurant_detail->currency_code):number_format_unchanged_precision($price * $value['qty_no'],$restaurant_detail->currency_code);
		             $Subtotal=0;
		             $addons_name_list = ''; 
		             $i=$i+1;
		             ?></div>
	           </div>
           <?php }  } ?>
        </div>
	</div>
	<!-- Footer part for Price -->
    <table border="3" cellpadding="10" cellspacing="0" width="100%" class="table-style">
          <tr>
            <td rowspan="6"  class="width60"><?php echo ($order_records->extra_comment)?'Preferences: '.$order_records->extra_comment:'' ?></td>
            <td class="align-right" style="font-size:38" class="width15"><strong>Subtotal</strong></td>
            <td class="align-left" style="font-size:38" class="width20"><?php echo number_format_unchanged_precision($order_records->subtotal,$restaurant_detail->currency_code) ?></td>
          </tr>
          <tr>
            <td class="align-right" style="font-size:38"><strong>Delivery Charge</strong></td>
            <td class="align-left" style="font-size:38"><?php echo ($order_records->delivery_charge)?number_format_unchanged_precision($order_records->delivery_charge,$restaurant_detail->currency_code):'-'; ?></td>
          </tr>
          <tr>
            <td class="align-right" style="font-size:38"><strong>Discount</strong></td>
            <td class="align-left" style="font-size:38"><?php echo ($order_records->coupon_amount)?(($order_records->coupon_type == 'Amount')?:'').number_format_unchanged_precision($order_records->coupon_amount,$restaurant_detail->currency_code):'-'; ?><?php echo ($order_records->coupon_type == 'Percentage')?'% ':'' ?></td>
          </tr>
           <tr>
            <td class="align-right" style="font-size:38"><strong>SD</strong></td>
            <td class="align-left" style="font-size:38"><?php echo number_format_unchanged_precision($order_records->sd,$restaurant_detail->currency_code); ?></td>
          </tr>
           <tr>
            <td class="align-right" style="font-size:38" ><strong>VAT</strong></td>
            <td class="align-left"  style="font-size:38" ><?php echo number_format_unchanged_precision($order_records->vat,$restaurant_detail->currency_code); ?></td>
          </tr>
          <!-- <tr>
            <td class="align-right"><strong>Sales Tax</strong></td>
            <td class="align-left"><?php echo ($order_records->tax_rate)?number_format_unchanged_precision($order_records->tax_rate):'-'; ?><?php echo ($order_records->tax_type == 'Percentage')?'%':'' ?></td>
          </tr> -->
          <tr>
            <td class="align-right grand-total" style="font-size:38"><strong>TOTAL</strong></td>
            <td class="align-left grand-total" style="font-size:38"><?php echo number_format_unchanged_precision($order_records->total_rate,$restaurant_detail->currency_code); ?></td>
          </tr>
         
    </table>
    <!-- Footer part for Price end -->
</div>