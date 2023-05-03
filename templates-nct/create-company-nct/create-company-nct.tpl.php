<div class="inner-main">
    <div class="create-company-main">
        <div class="container">
            <h1>{LBL_FORM_COMPANY_WELCOME_TEXT}</h1>
            <form action="<?php echo SITE_URL; ?>create-company" class="create-form" name="create_company_form" id="create_company_form" method="post">
                <div class="form-group"><input type="text" class="form-control" id="company_name" name="company_name" placeholder="{LBL_FORM_COMPANY_COMPANY_NAME}*" /></div>
                <div class="form-group"><input type="text" class="form-control" id="owner_email_address" name="owner_email_address" placeholder="{LBL_FORM_COMPANY_YOUR_EMAIL_ADDRESS_AT_COMPANY}*" /></div>
                <div class="form-group">
                    <select name="company_industry_id" id="company_industry_id" class="form-control selectpicker show-tick">
                        <option value="">{LBL_FORM_COMPANY_COMPANY_INDUSTRY}*</option>
                        %COMPANY_INDUSTRY_OPTIONS%
                    </select>
                </div>

                <div class="form-group">
                    <select name="company_size_id" id="company_size_id" class="form-control selectpicker show-tick">
                        <option value="">{LBL_FORM_COMPANY_COMPANY_SIZE}*</option>
                        %COMPANY_SIZE_OPTIONS%
                    </select>
                </div>
                <div class="form-group">
                    <div class="radio-btn-small">
                        <input type="checkbox" id="accept_terms" name="accept_terms" />
                        <label for="accept_terms">
                            <span></span>
                            {LBL_FORM_COMPANY_VERIFY_RIGHTS_CHECKBOX}
                        </label>
                    </div>
                </div>
                <div class="form-group text-center"><button type="submit" class="blue-btn" name="create_company" id="create_company">{BTN_CREATE_COMPANY_CREATE}</button></div>
            </form>
        </div>
        <div class="company-bg"></div>
    </div>
</div>
<script type="text/javascript">

    $("#create_company_form").validate({
        ignore: [],
        rules: {
            company_name: {required: true,onlyCharNum:true,companyNm:true},
            owner_email_address: {required: true,checkEmail: true,},
            company_industry_id: {required: true},
            company_size_id: {required: true},
            accept_terms: "required",
        },
        messages: {
            company_name: {required: "{ERROR_FROM_COMPANY_ENTER_COMPANY_NAME}",onlyCharNum:"{PLEASE_ENTER_ALPHANUMERIC_VALUE}"},
            owner_email_address: {required: "{ERROR_FORM_CREATE_COMPANY_ENTER_EMAIL_ADDRESS}"},
            company_industry_id: {required: "{ERROR_FORM_CREATE_COMPANY_SELECT_INDUSTRY}"},
            company_size_id: {required: "{ERROR_FORM_CREATE_COMPANY_SELECT_SIZE_OF_COMPANY}"},
            accept_terms: "{ERROR_FORM_CREATE_COMPANY_ACCEPT_TERMS_AND_CONDITIONS}",
        },
        highlight: function(element) {
            //$(element).addClass('has-error');
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            //$(element).closest('.form-group').removeClass('has-error');
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
        },
        submitHandler: function(form) {
            return true;
        }
    });
    $("#create_company_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                window.location.href = '' + obj.redirect_url + '';
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