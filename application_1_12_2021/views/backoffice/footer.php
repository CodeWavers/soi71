<div class="page-footer">
    <div class="page-footer-inner">
          <?php echo $this->lang->line('copyright');?>&copy; <?php echo date('Y');?>  <?php echo $this->lang->line('site_footer');?>
    </div>
    <div class="page-footer-tools">
        <span class="go-top">
        <i class="fa fa-angle-up"></i>
        </span>
    </div>
</div>
<!-- END footer -->
</body>
<!-- END BODY -->
<script type="text/javascript">
$(document).ready(function(){
    /*var obj = document.createElement("audio");
                    obj.src = "<?php //echo base_url() ?>assets/admin/img/notification_sound.wav"; 
                    obj.play(); */
  var obj = document.createElement("audio");
  obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav";
   var i = setInterval(function(){
      jQuery.ajax({
        type : "POST",
        dataType : "json",
        async: false,
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/ajaxNotification',
        success: function(response) {
            var past_count = $('.notification span.count').html();
            if(response != null){
              if(response.order_count != '' && response.order_count != null){
                if(past_count < response.order_count){
                    // var obj = document.createElement("audio");
                    // obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav"; 
                    obj.play(); 
                }
                var count = (response.order_count >= 100)?'99+':response.order_count;
                $('.notification span.count').html(count);
              }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
      });
    },10000); 

    var j = setInterval(function(){
      jQuery.ajax({
        type : "POST",
        dataType : "json",
        async: false,
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/ajaxEventNotification',
        success: function(response) {
            var past_count = $('.event-notification span.event-count').html();
            if(response != null){
              if(response.event_count != '' && response.event_count != null){
                if(past_count < response.event_count){
                    // var obj = document.createElement("audio");
                    // obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav"; 
                    obj.play(); 
                }
                var count = (response.event_count >= 100)?'99+':response.event_count;
                $('.event-notification span.event-count').html(count);
              }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
      });
    },10000);  

    var k = setInterval(function(){
        var past_count = $('.notification span.count').html();
        var event_count = $('.event-notification span.event-count').html();
        if(past_count >= 1 || event_count >= 1){ 
            // var obj = document.createElement("audio");
            // obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav"; 
            obj.play(); 
        }
    },20000);

});

$(document).ready(function(){
    /*var obj = document.createElement("audio");
                    obj.src = "<?php //echo base_url() ?>assets/admin/img/notification_sound.wav"; 
                    obj.play(); */
  var obj = document.createElement("audio");
  obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav";
   var i = setInterval(function(){
      jQuery.ajax({
        type : "POST",
        dataType : "json",
        async: false,
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/ajaxCustomNotification',
        success: function(response) {
            var past_count = $('.notification span.custom_count').html();
            if(response != null){
              if(response.order_count != '' && response.order_count != null){
                if(past_count < response.order_count){
                    
                    obj.play(); 
                }
                var count = (response.order_count >= 100)?'99+':response.order_count;
                $('.notification span.custom_count').html(count);
              }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
      });
    },10000); 

    var j = setInterval(function(){
      jQuery.ajax({
        type : "POST",
        dataType : "json",
        async: false,
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/ajaxEventNotification',
        success: function(response) {
            var past_count = $('.event-notification span.event-count').html();
            if(response != null){
              if(response.event_count != '' && response.event_count != null){
                if(past_count < response.event_count){
                    // var obj = document.createElement("audio");
                    // obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav"; 
                    obj.play(); 
                }
                var count = (response.event_count >= 100)?'99+':response.event_count;
                $('.event-notification span.event-count').html(count);
              }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
      });
    },10000);  

    var k = setInterval(function(){
        var past_count = $('.notification span.count').html();
        var event_count = $('.event-notification span.event-count').html();
        if(past_count >= 1 || event_count >= 1){ 
            // var obj = document.createElement("audio");
            // obj.src = "<?php echo base_url() ?>assets/admin/img/notification_sound.wav"; 
            obj.play(); 
        }
    },20000);

});
function changeViewStatus(){
    jQuery.ajax({
        type : "POST",
        dataType : "html",
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/changeViewStatus',
        success: function(response) {
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
    });
}
function changeCustomViewStatus(){
    jQuery.ajax({
        type : "POST",
        dataType : "html",
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/changeCustomViewStatus',
        success: function(response) {
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
    });
}
function changeEventStatus(){
    jQuery.ajax({
        type : "POST",
        dataType : "html",
        url : '<?php echo base_url().ADMIN_URL?>/dashboard/changeEventStatus',
        success: function(response) {
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {           
        }
    });
}
</script>
<?php if($this->session->userdata("language_slug")=='ar'){  ?>
<script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_ar.js"> </script>
<?php } ?>
<?php if($this->session->userdata("language_slug")=='fr'){  ?>
<script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_fr.js"> </script>
<?php } ?>
<?php if($this->session->userdata("language_slug")=='bn'){  ?>
<script type="text/javascript" src="<?php echo base_url()?>assets/admin/pages/scripts/localization/messages_bn.js"> </script>
<?php } ?>
</html>