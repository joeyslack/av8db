<?php echo $this->actions; ?>
<script type="text/javascript">
    $(document).on('click', ".send-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>addConnection",
            data: {user_id: user_id,action: 'addConnection'},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    element.html("<i class='icon-unfollower'></i>");
                    element.removeClass("send-connection-request").addClass("cancel-connection-request");
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on("click", ".accept-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>approveConnection",
            data: {user_id: user_id,action: 'approveConnection'},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    element.html('<i class="icon-connection-close"></i>');
                    $('.reject-connection-request').remove();
                    element.removeClass("accept-connection-request").addClass("remove-from-connection");
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on("click", ".reject-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>rejectConnection",
            data: {user_id: user_id,action: 'rejectConnection'},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    element.html('<i class="icon-follower"></i>');
                    $('.accept-connection-request').remove();
                    element.removeClass("reject-connection-request").addClass("send-connection-request");
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on("click", ".remove-from-connection", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        var bootBoxCallback = function(result) {
        if(result){
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>removeConnection",
                data: {user_id: user_id,action: 'removeConnection'},
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        
                        toastr['success'](data.success);
                        element.html('<i class="icon-follower"></i>');
                        element.removeClass("remove-from-connection").addClass("send-connection-request");
                    } else {
                        toastr['error'](data.error);
                    }
                }
            });    
        }
        }            
        initBootBox("{ALERT_REMOVE_FROM_CONNECTION}", "{ALERT_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_THE_CONNECTION}", bootBoxCallback);
    });
    $(document).on('click', ".cancel-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>removeConnection",
            data: {user_id: user_id,action: 'removeConnection'},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.success);
                    element.html('<i class="icon-follower"></i>');
                    element.removeClass("cancel-connection-request").addClass("send-connection-request");
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });    
</script>