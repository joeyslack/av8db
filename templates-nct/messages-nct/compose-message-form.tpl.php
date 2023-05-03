<div class="in-heading">
    <h3> {LBL_COMPOSE_MSG} </h3>
    <div class="compose-outer-bx">
    <form action="%COMPOSE_MESSAGE_FORM_ACTION_URL%" method="post" name="compose_message_form" id="compose_message_form">
        <div class="form-group cf">
            <input type="text" name="receiver_name" id="receiver_name" value="%RECEIVER_NAME%" placeholder="{LBL_PLACEHOLDER_RCV_NAME}" %RECEIVER_NAME_READONLY% />
            <input type="hidden" name="receiver_id" id="receiver_id" value="%RECEIVER_ID%" />
        </div>
        <div class="form-group cf">
            <textarea placeholder="{LBL_YOUR_MSG}" rows="2" id="message" name="message"></textarea>
        </div>
        <div class="form-group cf">
            <button type="submit" class="blue-btn" name="send_message" id="send_message">{LBL_SEND_MSG}</button>
        </div>
    </form>
    </div>
</div>
<script type="text/javascript">
    $.validator.addMethod('pagenm', function (value, element) {
            return /^(?!\s+$)/.test(value);
        }, '{ONLY_SPACE_ALLOW}');

    var autocomp_opt = {
        source: function (request, response) {
            var input = this.element;
            $("#receiver_id").val("");
            $.ajax({
                url: "<?php echo SITE_URL; ?>getConnections",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getConnections',
                    user_name: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {label: item.user_name, value: item.user_name, id: item.user_id, encrypted_id: item.encrypted_id};
                    }));
                },
                error: function (jq, status, message) {
                    //alert(message);
                }
            });
        },
        select: function (event, c) {
            $("#receiver_id").val(c.item.encrypted_id);
            $("#message").focus();
        },
        autoFocus: true
    };
    
    $(document).ready(function() {
        $("#receiver_name").autocomplete(autocomp_opt);
    });
    
    $("#compose_message_form").validate({
        ignore: [],
        rules: {
            receiver_name: {
                required: true
            },
            receiver_id: {
                required: true
            },
            message: {
                required: true,
                pagenm:true
            }
        },
        groups: {
            receiver: "receiver_name receiver_id"
        },
        messages: {
            receiver_name: {
                required: "{MSG_PLEASE_SELECT_NAME_RECEIVER}"
            },
            receiver_id: {
                required: "{MSG_PLEASE_SELECT_NAME_RECEIVER}"
            },
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
            $(element).parent("div").append(error);
        },
        submitHandler: function(form) {
            return true;
        }
    });
    
    $("#compose_message_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                //toastr["success"](obj.success);
                $("#compose_message_form")[0].reset();
                var hml = '<ul class="msg-row-list left-msg-row">'
                    hml += obj.conversations;
                    hml += '</ul>';
                $("#conversations_container").html(hml);
                $("#conversation_container").html(obj.single_conversation);
                
/*                initConversationsScrollbar();
*/                
                initSingleConversationScrollbar();
                
                $(".left-msg-cell").each(function(e) {
                    $(this).removeClass("active-left-msg");
                });
                $('*[data-conversation="'+obj.conversation_id+'"]').addClass("active-left-msg");
                


                
                window.history.pushState("", "Title", SITE_URL + "messaging/thread/" + obj.conversation_id);
                

            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    
</script>