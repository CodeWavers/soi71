// Add CMS Validation
jQuery("#form_add_category").validate({  
  rules: {    
    CategoryName: {
      required: true
    }
  }  
});
//Reset password
jQuery('#newPasswordform').validate({
  rules:{
    password: {
      required: true,
      //passwordcustom: true
    },
    confirm_pass: {
      required: true,
      equalTo: "#password"
    }
  }
});
//add user
jQuery('#form_add_us').validate({
  rules:{
    first_name:{
      required:true
    },
    last_name:{
      required:true
    },
    email:{
      required:true,
      emailcustom:true
    },
    mobile_number:{
      required:true,
      digits:true
    },
    /*phone_number:{
      required:true,
      digits:true
    },*/
    user_type:{
      required:true
    },
    password:{
      required:{
        depends: function(){
          if($('#entity_id').val() == ''){
              return true;
          }
        }
      },
      passwordcustome:true
    },
    confirm_password:{
      required:{
        depends: function(){
          if($('#entity_id').val() == ''){
              return true;
          }
        }
      },
      equalTo:'#password'
    }
  }
});
//add address
jQuery('#form_add_ad').validate({
  rules:{
    user_entity_id:{
      required:true
    },
    address:{
      required:true
    },
    landmark:{
      required:true
    },
    latitude:{
      required:true,
      number:true
    },
    longitude:{
      required:true,
      number:true
    },
    zipcode:{
      required:true,
      digits:true
    },
    country:{
      required:true,
    },
    state:{
      required:true,
    },
    city:{
      required:true,
    },
  }
});
//add restaurant
jQuery('#form_add_re').validate({
  rules:{
    name:{
      required:true
    },
    phone_number:{
      required:true
    },
    email:{
      required:true
    },
    capacity:{
      required:true,
      number:true
    },
    no_of_table:{
      required:true,
      number:true
    },
    address:{
      required:true,
    },
    landmark:{
      required:true,
    },
    latitude:{
      required:true,
    },
    longitude:{
      required:true,
    },
    state:{
      required:true,
    },
    country:{
      required:true,
    },
    city:{
      required:true,
    },
    currency_id:{
      required:true
    },
    zipcode:{
      required:true,
      digits:true
    },
    /*amount_type:{
      required:true,
    },
    coupon_amount:{
      required:true,
    },
    amount: {
      required: true,
      number:true,
      max: {
        param:100,
        depends: function(){
          if($("input[name=amount_type]:checked").val() == "Percentage" ){
            return true;
          }
        }
      },
      min: function(element){
          if($("input[name=amount_type]:checked").val() == "Percentage"){
              return 1;
          }else{
              return 0;
          }
      }
    },
    driver_commission:{
      required: true,
      number:true,
      max: 100,
      min:1
    },*/
    enable_hours:{
      required:true
    }
  }
});
//add category
jQuery('#form_add_cg').validate({
  rules:{
    name:{
      required:true
    }
  }
});
//add menu
jQuery('#form_add_menu').validate({
  ignore: [],
  rules:{
    name:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    category_id:{
      required:true
    },
    price:{
      required:{
          depends: function(){
          if(!$('#check_add_ons').is(':checked')){
            return true;
          }
        }
      },
      number:true,
      min:0
    },
    menu_detail:{
      required:true
    },
    ingredients:{
      ckrequired:true
    },
    recipe_detail:{
      ckrequired:true
    },
    recipe_time:{
      required : true,
      digits :true
    },
    'availability[]':{
      required:true
    },
    'addons_category_id[]':{
      required:{
          depends: function(){
          if($('#check_add_ons').is(':checked')){
            return true;
          }
        }
      },
    }
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "recipe_detail") 
    {
      error.insertAfter('#cke_recipe_detail');
      element.next().css('border', '1px solid red');
    } 
    else if (element.attr("name") == "ingredients") {
      error.insertAfter('#cke_ingredients');
      element.next().css('border', '1px solid red');
    }
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add package
jQuery('#form_add_pac').validate({
  ignore: [],
  rules:{
    name:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    category_id:{
      required:true
    },
    price:{
      required:true,
      number:true,
      min:0
    },
    detail:{
      ckrequired:true
    },
    'availability[]':{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "detail") 
    {
      error.insertAfter('#cke_detail');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add branch
jQuery('#form_add_br').validate({
  rules:{
    name:{
      required:true
    },
    branch_entity_id:{
      required:true
    },
    phone_number:{
      required:true
    },
    email:{
      required:true
    },
    capacity:{
      required:true,
      number:true
    },
    no_of_table:{
      required:true,
      number:true
    },
    address:{
      required:true,
    },
    landmark:{
      required:true,
    },
    latitude:{
      required:true,
    },
    longitude:{
      required:true,
    },
    state:{
      required:true,
    },
    country:{
      required:true,
    },
    city:{
      required:true,
    },
    currency_id:{
      required:true
    },
    zipcode:{
      required:true,
      digits:true
    },
    /*amount_type:{
      required:true,
    },
    coupon_amount:{
      required:true,
    },
    amount: {
      required: true,
      number:true,
      max: {
        param:100,
        depends: function(){
          if($("input[name=amount_type]:checked").val() == "Percentage" ){
            return true;
          }
        }
      },
      min: function(element){
          if($("input[name=amount_type]:checked").val() == "Percentage"){
              return 1;
          }else{
              return 0;
          }
      }
    },*/
    enable_hours:{
      required:true
    }
  }
});
//add coupon
jQuery('#form_add_cpn').validate({
  ignore: [],
  rules: {
    name: {
      required: true
    },
    'restaurant_id[]': {
      required: true
    },
    description: {
      ckrequired: true
    },
    amount_type: {
      required: {
        depends: function () {
          if ($("#coupon_type").val() != "free_delivery" && $("#coupon_type").val() != "gradual") {
            return true;
          }
        }
      }
    },
    'item_id[]': {
      required: {
        depends: function () {
          if ($("#coupon_type").val() == "discount_on_items" || $("#coupon_type").val() == "discount_on_combo") {
            return true;
          }
        }
      },
    },
    amount: {
      required: {
        depends: function () {
          if ($("#coupon_type").val() != "free_delivery" && $("#coupon_type").val() != "gradual") {
            return true;
          }
        }
      },
      digits: true,
      max: {
        param: 100,
        depends: function () {
          if ($("input[name=amount_type]:checked").val() == "Percentage") {
            return true;
          }
        }
      },

      min: function (element) {
        if ($("input[name=amount_type]:checked").val() == "Percentage") {
          return 1;
        } else {
          return 0;
        }
      }
    },
    max_amount:{
      required:{
        depends: function(){
          if ($("#coupon_type").val() != "free_delivery" && $("#coupon_type").val() != "gradual" && $("#coupon_type").val() != "discount_on_items") {
            return true;
          }
        }
      },
      number:true,
      min:1,
    },
    start_date: {
      required: true
    },
    end_date: {
      required: true
    },
  },
  errorPlacement: function (error, element) {
    if (element.attr("name") == "description") {
      error.insertAfter('#cke_description');
      element.next().css('border', '1px solid red');
    }
    else {
      error.insertAfter(element);
    }
    if (element.attr("id") == "restaurant_id") {
      error.insertAfter('.sumo_restaurant_id');
    }
    if (element.attr("id") == "item_id") {
      error.insertAfter('.sumo_item_id');
    }
  }
});
//add category
jQuery('#form_add_order').validate({
  rules:{
    user_id:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    address_id:{
      required:true
    },
    order_status:{
      required:true
    },
    order_date:{
      required:true
    },
    delivery_date:{
      required:true
    },
    deliveryCharge:{
      required:true
    },
    total_rate:{
      required:true
    },
    first_name: {
      required: {
        depends: function () {
          if ($("input[name=create_user]:checked").val() == "yes") {
            return true;
          }
        }
      }
    },
    mobile_number: {
      required: {
        depends: function () {
          if ($("input[name=create_user]:checked").val() == "yes") {
            return true;
          }
        }
      }
    },
    delivery_area: {
      required: {
        depends: function () {
          if ($("input[name=address]:checked").val() == "yes") {
            return true;
          }
        }
      }
    },
  }
});
jQuery('#form_add_event').validate({
  rules:{
    name:{
      required:true
    },
    no_of_people:{
      required:true,
      digits:true
    },
    no_of_table:{
      required:true,
      digits:true
    },
    booking_date:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    user_id:{
      required:true
    },
    end_date:{
      required:true
    }
  }
});
jQuery('#form_add_cms').validate({
  ignore:[],
  rules:{
    name:{
      required:true
    },
    description:{
      ckrequired:true
    }
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "description") 
    {
      error.insertAfter('#cke_description');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
// Add Email Template Validation
jQuery("#form_add_email").validate({  
  ignore:[],
  rules: {    
    title: {
      required: true
    },
    subject: {
      required: true
    },
    message: {
      required: function() 
      {
        CKEDITOR.instances.message.updateElement();
      }
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("name") == "message") 
    {
      error.insertAfter('#cke_message');
      element.next().css('border', '1px solid red');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
//add Amount
jQuery('#form_add_amount').validate({
  rules:{
    amount:{
      required:true,
      number:true,
      min:0,
    },
    subtotal:{
      required:true
    }
  }
});
//add Amount
jQuery('#form_add_notification').validate({
  rules:{
    'user_id[]':{
      required:true
    },
    notification_title:{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("id") == "user_id") 
    {
      error.insertAfter('.sumo_user_id');
    } 
    else 
    {
      error.insertAfter(element);
    }
  }
});
jQuery('#send_email').validate({
  rules:{
    'user_id[]':{
      required:true
    },
    template_id:{
      required:true
    },
  },
  errorPlacement: function(error, element) 
  {
    if (element.attr("id") == "user_id") 
    {
      error.insertAfter('.sumo_user_id');
    } 
    if (element.attr("id") == "template_id") 
    {
      error.insertAfter('.sumo_template_id');
    } 
  }
});
//generate Amount
jQuery('#generate_report').validate({
  rules:{
    restaurant_id:{
      required:true
    },
  }
});
//add addons category
jQuery('#form_add_acg').validate({
  rules:{
    name:{
      required:true
    }
  }
});
//add delviery charge
jQuery('#form_add_delivery').validate({
  rules:{
    area_name:{
      required:true
    },
    lat_long:{
      required:true
    },
    price_charge:{
      number:true,
      required:true
    }
  }
});
//add menu deal
jQuery('#form_add_deal').validate({
  ignore: [],
  rules:{
    name:{
      required:true
    },
    restaurant_id:{
      required:true
    },
    category_id:{
      required:true
    },
    price:{
      required:true,
      number:true,
      min:0
    },
    menu_detail:{
      required:true
    },
    'availability[]':{
      required:true
    },
    check_add_ons:{
      required:true
    },
    'addons_category_id[]':{
      required:{
          depends: function(){
          if($('#check_add_ons').is(':checked')){
            return true;
          }
        }
      },
    }
  },
});
$.validator.addMethod("emailcustom",function(value,element)
{
  return this.optional(element) || /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(value);
},"Please enter valid email address");

// custom password
$.validator.addMethod("passwordcustome",function(value,element)
{
  return this.optional(element) || /^(?=.*[0-9])(?=.*[!@#$%^&*)(])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*)(]{8,}$/.test(value);
},"Passwords must contain at least 8 characters, including uppercase, lowercase letters, symbols and numbers.");
// end here

// custom code for lesser than
jQuery.validator.addMethod('lesserThan', function(value, element, param) {  
  return ( parseInt(value) <= parseInt(jQuery(param).val()) );
}, 'Must be less than close time' );

// custom code for greater than
$.validator.addMethod("greaterThan", function(value, element, param) {
  return ( parseInt(value) >= parseInt(jQuery(param).val()) );    
}, "Must be greater than open time");

// custom code for greater than
$.validator.addMethod("greater", function(value, element, param) {
  return ( parseInt(value) > parseInt(jQuery(param).val()));    
}, "Must be greater than Amount");

jQuery.validator.addMethod("ckrequired", function (value, element) {  
    var idname = $(element).attr('id');  
    var editor = CKEDITOR.instances[idname];  
    var ckValue = GetTextFromHtml(editor.getData()).replace(/<[^>]*>/gi, '').trim();  
    if (ckValue.length === 0) {  
//if empty or trimmed value then remove  extra spacing to current control  
        $(element).val(ckValue);  
    } else {  
//If not empty then leave the value as it is  
        $(element).val(editor.getData());  
    }  
    return $(element).val().length > 0;  
}, "This field is required");
function GetTextFromHtml(html) {  
  var dv = document.createElement("DIV");  
  dv.innerHTML = html;  
  return dv.textContent || dv.innerText || "";  
}
// custom code for price
$.validator.addMethod("customPrice",function(value,element)
{
  return this.optional(element) || /[^0-9\-]+/.test(value);
},"Please enter valid price");


// display currency in price
function getCurrency(value){ 
  if (value) {
    $.ajax({
      type: "POST",
      url: BASEURL+"backoffice/home/getCurrencySymbol",
      data: 'restaurant_id=' + value ,
      cache: false,
      success: function(response) {
        if (response) {
          $('#currency-symbol').html('('+response+')');
          $('.currency-symbol').html('('+response+')');
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {                 
        alert(errorThrown);
      }
    });
  }
  else
  {
    $('#currency-symbol').hide();
    $('.currency-symbol').hide();
  }
} 

// display currency in price
function getEventCurrency(entity_id){
  if (entity_id) {
    $.ajax({
      type: "POST",
      url: BASEURL+"backoffice/home/getEventCurrencySymbol",
      data: 'entity_id=' + entity_id ,
      cache: false,
      success: function(response) {
        if (response) {
          $('#currency-symbol').html('('+response+')');
          $('#currency-symbol').show();
          $('.currency-symbol').html('('+response+')');
          $('.currency-symbol').show();
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {                 
        alert(errorThrown);
      }
    });
  }
  else
  {
    $('#currency-symbol').hide();
    $('.currency-symbol').hide();
  }
} 