<div class="container reset-pass-page">
    <div class="page-content-main">
        <div class="head margintop30 text-center">
            <h1>{LBL_RESET_PASS} </h1>
        </div>
        <div class="page-content">
            <form id="password_reset_form" name="password_reset_form" action="" method="post">
                <div class="form-group">
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="{ENTER_NEW_PASSWORD}" />
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="{LBL_SIGNUP_CONFIRM_PASSWORD}" />
                    <input type="hidden" name="token" id="token" value="<?php print $this->hidd; ?>" />
                </div>

                <button type="submit" class="btn blue-btn" name="reset_password" id="reset_password">
                    {LBL_RESET_PASS}
                </button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $("#password_reset_form").validate({
        rules: {            
            new_password: {
                required: true,
                minlength: 6,
                maxlength: 25,
            },
            confirm_new_password: {
                required: true,
                equalTo: "#new_password"
            }
        },
        messages: {
            new_password: {
                required: "{ERROR_LOGIN_ENTER_PASSWORD}",
                minlength: "{LBL_VALIDATION_PASS_MIN}",
                maxlength: "{LBL_VALIDATION_PASS_MAX}",
                validpassword: "{MSG_PSW_VALID}"
            },
            confirm_new_password: {
                required: "{LBL_VALIDATION_CONFIRM_PASS}",
                equalTo: "{ERROR_PASSWORD_DONT_MATCH}"
            }
        }
    });
</script>