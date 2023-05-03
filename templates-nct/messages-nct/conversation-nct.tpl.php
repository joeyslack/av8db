<div class="in-heading">
    <h3>{CONVERSATION_WITH}<em> %USER_NAME%</em></h3>
    <div id="conversation_messages_container" class="msg-right-scroll mCustomScrollbar">
        <div class="msg-chat-main">
            <div class="msg-inbox-chat" id="msg_inbox_chat">
                <?php echo $this->conversation_messages; ?>
            </div>
        </div>
    </div>
    <div class="comment-form-container">
    <form class="chat-form" action="%REPLY_FORM_ACTION_URL%" method="post" name="reply_form" id="reply_form">
        <div class="input-group">
            <textarea placeholder="{MSG_HERE}"  id="message" class="form-control" name="message"></textarea>
            <div class="input-group-addon">
                <button type="submit" class="btn small-btn"><i class="icon-plane"></i></button>
            </div>
        </div>
    </form>
    </div>
</div>
<script type="text/javascript">

        $.validator.addMethod('pagenm', function (value, element) {
            return /^(?!\s+$)/.test(value);
        }, '{ONLY_SPACE_ALLOW}');


    $("#reply_form").validate({
        ignore: [],
        rules: {
            message: {
                required: true,
                pagenm:true
            }
        },
        messages: {
            message: {
                required: "&nbsp; {MSG_PLEASE_ENTER_MEG}"
            }
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
            $(element).addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
            $(element).addClass('valid-input');
            $(element).removeClass('has-error');
        },
        errorPlacement: function(error, element) {

            if (element.attr("data-error-placement")) {

                if (!$(element).is("select")) {
                    element.addClass("has-error");
                } else {
                    element.parents(".form-group").find(".bootstrap-select").addClass("has-error");
                }

            } else if (element.attr("data-error-container")) {
                error.appendTo(element.attr("data-error-container"));
            } else if (element.attr("type") == "checkbox") {
                $(element).parents('.checkboxes-container').append(error);
            } else {
                //$(element).parent("div").append(error);
                $(element).parent("div").append(error);
            }
        },
        submitHandler: function(form) {
            return true;
        }
    });
    
    $("#reply_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                //toastr["success"](obj.success);              
                $(".msg-inbox-chat").append(obj.my_message);
                $(".msg-right-scroll").mCustomScrollbar("update");
                $(".msg-right-scroll").mCustomScrollbar("scrollTo","bottom",{scrollInertia:2500,scrollEasing:"easeInOutQuad"});
                
                $("#reply_form")[0].reset();
                
                return false;
            } else {
                toastr["error"](obj.error);
                return false;
            }
            return false;
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    
</script>