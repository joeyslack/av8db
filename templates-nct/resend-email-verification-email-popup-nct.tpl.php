<div class="modal fade" id="resend_verification_email_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo SITE_URL; ?>resend_verification_email" name="resend_verification_email_form" id="resend_verification_email_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{LBL_RESEND_VERIFICATION_EMAIL}</h4>
                </div>
                <div class="modal-body">
                    <div class="login_form">
                        <div class="form-group">
                            <label for="email_address">{LBL_RESEND_EMAIL_EMAIL_ADDRESS}</label>
                            <input type="email" class="form-control" id="resend_verification_email_address" name="resend_verification_email_address" placeholder="{LBL_RESEND_EMAIL_EMAIL_ADDRESS}" />
                        </div>
                        <div class="space30"></div>
                    </div>
                </div>
                <div class="modal-footer btn-center">
                    <button type="submit" class="btn blue-btn" name="send" id="send">{LBL_RESEND_MAIL_BTN_SEND} </button>
                    <input type="reset" class="btn blue-btn cancel-btn" name="cancel" id="cancel" data-dismiss="modal" value="{LBL_RESEND_MAIL_BTN_CANCEL}" />
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#resend_verification_email_form").validate({
        rules: {resend_verification_email_address:{required: true,email: true}},
        messages: {resend_verification_email_address: {required: "{ERROR_RESEND_MAIL_ENTER_EMAIL_ADDRESS}",email: "{ERROR_RESEND_MAIL_ENTER_VALID_EMAIL_ADDRESS}"}}
    });
    $("#resend_verification_email_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                $("#resend_verification_email_form")[0].reset();
                toastr["success"](obj.success);
                $("#resend_verification_email_popup").modal('hide');
            } else {toastr["error"](obj.error);}
        },
        complete: function(xhr) {removeOverlay();return false;}
    });
</script>