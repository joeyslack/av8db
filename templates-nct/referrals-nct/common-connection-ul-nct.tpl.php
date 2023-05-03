<div class="gen-wht-bx in-heading fade fadeIn  common-connection-box">
	   <h3>{LBL_COMMON_CONNECTIONS}</h3>
	   <div class="common-conn cf"><?php echo $this->common_connection; ?> </div>
	<div class="center-block text-right pt-10 <?php echo $this->hidden_var; ?>">
        <div class="view-all-profile view-more-bx">
        <a href="%VIEW_ALL_LINK%" id="view_all_connection">{LBL_VIEW_ALL} <i class="icon-rgt-arrow"></i></a>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).on('click', "#remove_connection", function(){
    var parents_li = $(this).parents('li');
    var user_id = $(this).data('value');
    $.ajax({
        type: 'POST',
        url: "<?php echo SITE_URL; ?>removeConnection",
        data: {user_id: user_id,action: 'removeConnection'},
        beforeSend: function() {addOverlay();},
        complete: function() {removeOverlay();},
        dataType: 'json',
        success: function(data) {
            if (data.status) {
               //toastr['success'](data.success);
               parents_li.fadeOut(1500);
            } else {
                toastr['error'](data.error);
            }
        }
    });
});
</script>