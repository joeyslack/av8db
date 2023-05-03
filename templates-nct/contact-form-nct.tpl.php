<!--<div class="floating-form" id="contact_form_container" style="right: -330px;"> <div id="contact_form_opener" class="contact-opener" title="{LBL_CONTACT_US}">{LBL_CONTACT_US}</div><form method="post" name="contactus_form" id="contactus_form" action="{SITE_URL}submit-contactus"> <h2 class="sub-title clearfix">{LBL_CONTACT_US}</h2> <div id="contactus_form_message_container" class="alert alert-success fade in" role="alert"><a href="javascript:void(0)" class="close" id="contactus_form_message_close">&times;</a><div id="contactus_form_message"></div></div><div class="row"> <div class="col-sm-12"><div class="form-group"><input type="text" name="c_first_name" id="c_first_name" class="form-control border-field" placeholder="{LBL_CONTACT_US_FIRST_NAME}*" value="%FIRST_NAME%" %READONLY%/></div></div><div class="col-sm-12"><div class="form-group"><input type="text" name="c_last_name" id="c_last_name" class="form-control border-field" placeholder="{LBL_CONTACT_US_LAST_NAME}*" value="%LAST_NAME%" %READONLY%/></div></div><div class="col-sm-12"><div class="form-group"><input type="text" name="c_email_address" id="c_email_address" class="form-control border-field" placeholder="{LBL_CONTACT_US_EMAIL_ADDRESS}*" value="%EMAIL_ADDRESS%" %READONLY%/></div></div><div class="col-sm-12"><div class="form-group"><input type="text" name="c_subject" id="c_subject" class="form-control border-field" placeholder="{LBL_CONTACT_US_SUBJECT}*"/></div></div><div class="col-sm-12"><div class="form-group"><textarea class="form-control border-field" name="c_message" id="c_message" placeholder="{LBL_CONTACT_US_MESSAGE}*"></textarea></div></div><div class="col-sm-6 col-md-offset-3"><div class="form-group"><button type="submit" class="btn blue-btn" name="submit_contact_form" id="submit_contact_form">{LBL_CONTACT_US_BTN_SEND}</button></div></div></div></form></div>
<script type="text/javascript">
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^[\w.]+$/i.test(value);
    }, "Letters, numbers, and underscores only please");

    $("#contactus_form").validate({
        ignore: [],
        rules: {c_first_name:{alphanumeric: true,required:true},c_last_name:{alphanumeric: true,required:true},c_email_address:{required:true,email:true},c_subject:{required:true},c_message:{required:true}},
        messages: {c_first_name:{required: "{ERROR_CONTACT_US_ENTER_FIRST_NAME}"},c_last_name:{required: "{ERROR_CONTACT_US_ENTER_LAST_NAME}"},c_email_address:{required: "{ERROR_CONTACT_US_ENTER_EMAIL_ADDRESS}",email:"{ERROR_CONTACT_US_ENTER_VALID_EMAIL}",},c_subject:{required: "{ERROR_CONTACT_US_ENTER_SUBJECT}",},c_message:{required: "{ERROR_CONTACT_US_ENTER_MESSAGE}"}},
        highlight: function(element) {if (!$(element).is("select")) {$(element).removeClass("valid-input").addClass("has-error");}else{$(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");}},
        unhighlight: function(element) {if (!$(element).is("select")) {$(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');}else{$(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');}},
        errorPlacement: function(error, element) {$(element).parent("div").append(error);},
        submitHandler: function(form) {return true;}
    });
    $("#contactus_form").ajaxForm({
        beforeSend: function(){addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {obj = $.parseJSON(html);
            if (obj.status) {$("#contactus_form")[0].reset();$("#contactus_form .form-control").removeClass("valid-input").removeClass("has-error");$("#contactus_form_message").html(obj.success);$("#contactus_form_message_container").toggle('slide', {direction: 'left'}, 100);} else {toastr["error"](obj.error);}
        },
        complete: function(xhr) {removeOverlay();return false;}
    });
    $(document).on("click", "#contactus_form_message_close", function() {$("#contactus_form_message_container").toggle('slide', {direction: 'left'}, 100, function() {$("#contactus_form_message").html('');});})
</script>-->