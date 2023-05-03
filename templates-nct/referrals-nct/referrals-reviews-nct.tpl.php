<form id="referral_review_form" name="referral_review_form" method="post" action="<?php echo SITE_URL; ?>referral/">
    <div class="clearfix"></div>
    <div class="comp-desc-in pt-0">
        <div class="">
            <textarea placeholder="Description" name="referral_description" id="referral_description">%REVIEW_DESCRIPTOIN%</textarea>
        </div>
        <input type="hidden" name="review_id" id="review_id" value="%REFERRAL_ID%">
        <input type="hidden" name="sender_id" id="sender_id" value="%SENDER_ID%">
        <input type="hidden" name="receiver_id" id="receiver_id" value="%RECEIVER_ID%">
        <div class="form-group cf">
            <button type="submit" class="blue-btn" name="save_referral_review" id="save_referral_review">{LBL_REFERRAL_REVIEWS_SAVE} </button>
            <button type="reset" class="outer-red-btn" name="cancel_referral_review" id="cancel_referral_review">{LBL_REFERRAL_REVIEWS_CANCEL} </button>
        </div>
    </div>
</form>
<script type="text/javascript">
    $.validator.addMethod("reviewsDesc", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\'\s]*$/.test(value);
    }, "{ERROR_MESSAGE_FOR_REFERRAL_VALID_REVIEWS}");
    $("#referral_review_form").validate({
        ignore: [],
        rules: {
            referral_description: {required: true,reviewsDesc: true}
        },
        messages: {
            referral_description: {required: "{ERROR_MESSAGE_FOR_REFERRAL_REVIEW_DESCRIPTION}"}
        },
        highlight: function(element) {
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            if($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        }
    });
    $("#referral_review_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status == "suc") {
                toastr["success"](obj.message);
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.message);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    $(document).on('click','#cancel_referral_review',function(){
        $('#writeReferralReview').modal('hide');
    });
</script>