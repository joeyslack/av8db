<div class="in-login-inner">
<form class="login-form" name="login_form" id="login_form" action="{SITE_URL}login" method="post">
    <div class="list-login cf">
        <div class="md-input form-group cf">
            <label>{LBL_LOGIN_EMAIL_ADDRESS}</label>
            <input type="email" autocomplete="off" class="form-control" name="login_email_address" id="login_email_address" value="%LOGIN_EMAIL_ADDRESS%" />
        </div>
    </div>
    <div class="list-login cf">
        <div class="md-input form-group cf">
            <label>{LBL_LOGIN_PASSWORD}</label>
            <input type="password" class="form-control" name="login_password" id="login_password" value="%LOGIN_PASSWORD%" />
            <span toggle="#login_password" class="fa fa-fw fa-eye field-icon toggle-password1" title="Reveal password"></span>
        </div>
    </div>
    <div class="list-login cf">
        <div class="form-group cf">
            <div class="flat-checkbox">
            <input type="checkbox" id="remember_me" name="remember_me" %CHECKED_STATUS% />
            <label for="remember_me">{LBL_LOGIN_REMEMBER_ME}</label>
            </div>
            <a href="javascript:void(0);" class="forget-link" title="{LBL_LOGIN_FORGOT_PASSWORD}" id="forgot_password">{LBL_LOGIN_FORGOT_PASSWORD}</a>
        </div>
    </div>
    <div class="list-login cf">
        <div class="in-submit-btn form-group cf">
            <input type="submit" name="signin" id="signin" class="blue-btn" value="{LBL_LOGIN_BUTTON_SIGNIN}" />
            <em>{LBL_LOGIN_CREATE} <a href="javascript:void(0)" class="move-signup">{LBL_LOGIN_FREE}</a> {LBL_LOGIN_ACC_HERE}</em>
        </div>
    </div>

    <div class="form-group cf">
        <div class="social-icons signup-social cf">
            <div class="fb-link">
                <a href="javascript:void(0);" onclick="login();" class="fb-btn" title="{LBL_LOGIN_WITH_FACEBOOK}">
                <small class="fb-icon"><i class="fa fa-facebook"></i></small>
                <p>{LBL_FACEBOOK}</p>
                </a>
            </div>
            <div class="linkedin-link">
                <a href="{SITE_URL}signin/linkedin" class="fb-btn linkedin-btn loginWithSocialMedia" title="{LBL_LINKED_TITLE}">
                    <small class="linkedin-icon"><i class="fa fa-linkedin"></i></small>
                    <p>{LBL_LINKEDIN}</p>
                </a>
            </div>
        </div>
    </div>
</form>
</div>
<div class="fg-pass-bx cf">
<form action="{SITE_URL}forgot_password" name="forgot_password_form" id="forgot_password_form" class="forgot_password_form" method="post">
    <div class="list-login cf">
        <div class="md-input form-group cf">
            <label>{LBL_LOGIN_EMAIL_ADDRESS}</label>
            <input type="email" id="forgot_password_email_address" name="forgot_password_email_address" />
        </div>
    </div>
    <div class="list-login form-group cf">
        <button type="submit" name="send" id="send" class="blue-btn">{LBL_SEND_RESET_LINK}</button>
        <button type="reset" name="cancel_forgot_password" id="cancel_forgot_password" class="outer-red-btn cancel-btn">{LBL_FORGOT_PASSWORD_CANCEL_BUTTON}</button>
    </div>
 
</form>
</div>
<script type="text/javascript">
    $(document).on('click','#login_link',function(){
        if($('#login_password').val() != ""){
            if($('#login_password').val() == "Password"){$('#login_password').val('');}
            $('#login_password').attr('type', 'password');
        }
    });
    $("#login_form").validate({
        rules: {
            login_email_address: {required: true, checkEmail: true},
            login_password: {required: true,minlength: 6,maxlength: 40,}
        },
        messages: {
            login_email_address: {required: "{ERROR_LOGIN_ENTER_EMAIL_ADDRESS}"},
            login_password: {required: "{ERROR_LOGIN_ENTER_PASSWORD}",minlength: "{ERROR_LOGIN_PASSWORD_MUST_SIX_CHARACTER}",maxlength: "{ERROR_LOGIN_PASSWORD_MAXIMUM}",}
        }
    });
    $("#login_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                if(MODULE == 'profile-nct')
                        window.location.reload();
                else
                    window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {removeOverlay();return false;}
    });
    $(document).on("click", '.resend_verification_email', function() {
        $("#login_popup").modal('hide');
        $("#resend_verification_email_popup").modal();
    });
    $("#forgot_password_form").validate({
        rules: {forgot_password_email_address:
            {required: true,email: true}
        },
        messages: {forgot_password_email_address: 
        {required: "{ERROR_FORGOT_ENTER_EMAIL_ADDRESS}",
        email: "{LBL_ENTER_VALID_EMAIL}"}}
    });
    $("#forgot_password_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                $("#forgot_password_form")[0].reset();
                $("#forgot_password_form").toggle('slide', {direction: 'right'}, 100, function () {
                    $("#login_form").toggle('slide', {direction: 'left'}, 100);
                    $("#login_link").click();
                });
                $("#fotgot_password_buyer_details_contianer").addClass("hidden");
                toastr["success"](obj.success);
                $(".text_chg_fg").text("{LBL_LOGIN_BUTTON_SIGNIN}");

            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {removeOverlay();return false;}
    });
    $(document).on("click", "#forgot_password", function () {
        $("#login_form").toggle('slide', {direction: 'left'}, 400, function () {
            $("#forgot_password_form").toggle('slide', {direction: 'right'}, 400);
            $(".text_chg_fg").text("{LBL_FORGOT_PSW}");
        });
    });
    $(document).on("click", "#cancel_forgot_password", function () {
        $("#forgot_password_form").toggle('slide', {direction: 'right'}, 400, function () {
            $("#login_form").toggle('slide', {direction: 'left'}, 400);
            $(".text_chg_fg").text("{LBL_LOGIN_BUTTON_SIGNIN}");

        });
    });
    $('#login_dropdown').on('hide.bs.dropdown', function () {
        if($("#login_form").css('display') == 'none') {
            $("#cancel_forgot_password").click();
        }
    });
    $(".toggle-password1").click(function() {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var input = $($(this).attr("toggle"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
</script>