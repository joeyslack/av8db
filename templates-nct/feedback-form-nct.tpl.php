<!-- <div class="floating-form" id="feedback_form_container" style="right: -330px;"> <div id="feedback_form_opener" class="contact-opener" title="{LBL_FEEDBACK_TITLE}">{LBL_FEEDBACK_TITLE}</div><form method="post" name="feedback_form" id="feedback_form" action="{SITE_URL}submit-feedback"> <h2 class="sub-title clearfix">{LBL_FEEDBACK_TITLE}</h2> <div id="feedback_form_message_container" class="alert alert-success fade in" role="alert"><a href="javascript:void(0)" class="close" id="feedback_form_message_close">&times;</a><div id="feedback_form_message"></div></div><div id="feedback_form_fields_container" class="row"> <div class="col-sm-12"><div class="form-group"><input type="text" name="f_first_name" id="f_first_name" class="form-control border-field" placeholder="{LBL_FEEDBACK_FIRST_NAME}*" value="%FIRST_NAME%" "%READONLY%"/></div></div><div class="col-sm-12"><div class="form-group"><input type="text" name="f_last_name" id="f_last_name" class="form-control border-field" placeholder="{LBL_FEEDBACK_LAST_NAME}*" value="%LAST_NAME%" "%READONLY%"/></div></div><div class="col-sm-12"><div class="form-group"><input type="text" name="f_email_address" id="f_email_address" class="form-control border-field" placeholder="{LBL_FEEDBACK_EMAIL_ADDRESS}*" value="%EMAIL_ADDRESS%" "%READONLY%"/></div></div><div class="col-sm-12"><div class="form-group"><textarea class="form-control border-field" name="f_message" id="f_message" placeholder="{LBL_FEEDBACK_MESSAGE}*"></textarea></div></div><div class="col-sm-6 col-md-offset-3"><div class="form-group"><button type="submit" class="btn blue-btn" name="submit_feedback" id="submit_feedback">{LBL_FEEDBACK_BTN_SEND}</button></div></div></div></form></div>
<script type="text/javascript">
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[\w.]+$/i.test(value);
    }, "Letters, numbers, and underscores only please");

    $("#feedback_form").validate({
        ignore: [],
        rules: {f_first_name:{required:true,alphanumeric:true},f_last_name:{required:true,alphanumeric:true},f_email_address:{required:true,email:true},f_message:{required:true}},
        messages: {f_first_name:{required: "{ERROR_FEEDBACK_ENTER_FIRST_NAME}"},f_last_name:{required: "{ERROR_FEEDBACK_LAST_NAME}"},f_email_address:{required: "{ERROR_FEEDBACK_ENTER_EMAIL_ADDRESS}",email: "{ERROR_FEEDBACK_ENTER_VALID_EMAIL_ADDRESS}",},f_message:{required: "{ERROR_FEEDBACK_ENTER_MESSAGE}"}},
        highlight: function(element) {if (!$(element).is("select")) {$(element).removeClass("valid-input").addClass("has-error");}else{$(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");}},
        unhighlight: function(element) {if (!$(element).is("select")) {$(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');} else {$(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');}},
        errorPlacement: function(error, element) {$(element).parent("div").append(error);},
        submitHandler: function(form) {return true;}
    });
    $("#feedback_form").ajaxForm({
        beforeSend: function(){addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {obj = $.parseJSON(html);if (obj.status) {$("#feedback_form")[0].reset();$(".form-control").removeClass("valid-input").removeClass("has-error");$("#feedback_form_message").html(obj.success);$("#feedback_form_message_container").toggle('slide', {direction: 'left'}, 100);} else { toastr["error"](obj.error); }},
        complete: function(xhr) {removeOverlay();return false;}
    });
    $(document).on("click","#feedback_form_message_close",function(){$("#feedback_form_message_container").toggle('slide', {direction: 'left'}, 100, function() {$("#feedback_form_message").html('');});})
</script> -->