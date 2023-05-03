<div class="inner-main">
  <div class="container contact-page" id="contact_form_container" >
    <form method="post" name="contactus_form" id="contactus_form" action="{SITE_URL}submit-contactus">
      <h2 class="text-center">{LBL_CONTACT_US}</h2>
      <div class="form-group">
        <input type="text" name="c_first_name" id="c_first_name" class="form-control border-field" placeholder="{LBL_CONTACT_US_FIRST_NAME}*" value="%FIRST_NAME%" %READONLY%/>
      </div>
      <div class="form-group">
        <input type="text" name="c_last_name" id="c_last_name" class="form-control border-field" placeholder="{LBL_CONTACT_US_LAST_NAME}*" value="%LAST_NAME%" %READONLY%/>
      </div>
      <div class="form-group">
        <input type="text" name="c_email_address" id="c_email_address" class="form-control border-field" placeholder="{LBL_CONTACT_US_EMAIL_ADDRESS}*" value="%EMAIL_ADDRESS%" %READONLY%/>
      </div>
      <div class="form-group">
        <input type="text" name="c_subject" id="c_subject" class="form-control border-field" placeholder="{LBL_CONTACT_US_SUBJECT}*"/>
      </div>
      <div class="form-group">
        <textarea class="form-control border-field" name="c_message" id="c_message" placeholder="{LBL_CONTACT_US_MESSAGE}*"></textarea>
      </div>
      <div class="form-group">
        <button type="submit" class="btn blue-btn" name="submit_contact_form" id="submit_contact_form" disabled="disabled">{LBL_CONTACT_US_BTN_SEND}</button>
        <a href="{SITE_URL}" class="btn blue-btn" name="close_contact_form" >{BTN_LANGUAGE_CANCEL}</a> </div>
    </form>
  </div>
</div>
<script type="text/javascript">
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^[\w.]+$/i.test(value);
}, "Letters, numbers, and underscores only please");
$(document).ready(function() {
    $("#submit_contact_form").removeAttr('disabled');
});
$.validator.addMethod('pagenm', function (value, element) {
            return /^(?!\s+$)/.test(value);
        }, '{ONLY_SPACE_ALLOW}');


$("#contactus_form").validate({
    ignore: [],
    rules: {
        c_first_name: {
            alphanumeric: true,
            required: true
        },
        c_last_name: {
            alphanumeric: true,
            required: true
        },
        c_email_address: {
            required: true,
            email: true
        },
        c_subject: {
            required: true,
            pagenm:true
        },
        c_message: {
            required: true,
            pagenm:true
        }
    },
    messages: {
        c_first_name: {
            required: "{ERROR_CONTACT_US_ENTER_FIRST_NAME}"
        },
        c_last_name: {
            required: "{ERROR_CONTACT_US_ENTER_LAST_NAME}"
        },
        c_email_address: {
            required: "{ERROR_CONTACT_US_ENTER_EMAIL_ADDRESS}",
            email: "{ERROR_CONTACT_US_ENTER_VALID_EMAIL}",
        },
        c_subject: {
            required: "{ERROR_CONTACT_US_ENTER_SUBJECT}",
        },
        c_message: {
            required: "{ERROR_CONTACT_US_ENTER_MESSAGE}"
        }
    },
    highlight: function(element) {
        if (!$(element).is("select")) {
            $(element).removeClass("valid-input").addClass("has-error");
        } else {
            $(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
        }
    },
    unhighlight: function(element) {
        if (!$(element).is("select")) {
            $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
        } else {
            $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
        }
    },
    errorPlacement: function(error, element) {
        $(element).parent("div").append(error);
    },
    submitHandler: function(form) {
        return true;
    }
});
$("#contactus_form").ajaxForm({
    beforeSend: function() {
        addOverlay();
    },
    uploadProgress: function(event, position, total, percentComplete) {},
    success: function(html, statusText, xhr, $form) {
        obj = $.parseJSON(html);
        if (obj.status) {
            toastr['success'](obj.success);
            $("#contactus_form")[0].reset();
            $("#contactus_form .form-control").removeClass("valid-input").removeClass("has-error");
            setTimeout(function(){location.href="{SITE_URL}"} , 1000);   

        } else {
            toastr["error"](obj.error);
        }
    },
    complete: function(xhr) {
        removeOverlay();
        return false;
    }
});
$(document).on("click", "#contactus_form_message_close", function() {
    $("#contactus_form_message_container").toggle('slide', {
        direction: 'left'
    }, 100, function() {
        $("#contactus_form_message").html('');
    });
})
</script>