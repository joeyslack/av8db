<form method="post" name="add_edit_licenses_form" id="add_edit_licenses_form" action="{SITE_URL}add-edit-licenses">
    <input type="hidden" name="licenses_endorsement_id" id="licenses_endorsement_id" value="%LICENSES_ID_ENCRYPTED%" />
    <div class="form-list cf">
        <div class="col-sm-12 %HIDDEN_FIELDS%">
            <div class="form-group cf">
                <select id="licenses_name" name="licenses_name" class="show-tick bootstrap-dropdowns">
                    <option value="">{LBL_PLEASE_SELECT_LICENSE_ENDORSEMENT}*</option>
                    %LICENSE_OPTION%
                </select>
            </div>
            <input type="hidden" name="hidden_licenses_name" id="hidden_licenses_name" value="%SELECTED_LICENSE_ID%">
        </div>
       
        <div class="col-sm-12 %HIDDEN_FIELDS%">
            <div class="form-group cf">
                <input type="text" name="date_obtain" id="date_obtain" placeholder="{LBL_DATE_OBTAINED}*" value="%DATE_OBTAIN%" autocomplete="off" class="date-picker"/>
            </div>
            <input type="hidden" name="selected_date" id="selected_date" value="%DATE_OBTAIN%">
        </div>
        <div class="col-sm-12 %HIDDEN_FIELDS%">
            <div class="form-group cf">
                <select id="institute_name12" name="institute_name12" class="show-tick bootstrap-dropdowns">
                    <option value="">{LBL_INSTITUTE_NAME}*</option>
                   %INDUSTRY_LIST%
                </select>
            </div>
            <input type="hidden" name="hidden_institute_name" id="hidden_institute_name" value="%INSTITUTE_NAME%">
        </div>
       
        <div class="col-sm-12">
            <div class="form-group cf">
                <label for="license_hours" class="%HIDE_UPDATE%">{LBL_PROFILE_UPDATE_FLIGHT_HOURS}</label>
                <input type="text" name="license_hours" id="license_hours" placeholder="{LBL_HOURS}*" value="%HOURS%" autocomplete="off" class=""/>
            </div>
        </div>
        <div class="col-sm-12 %HIDDEN_FIELDS%">
            <div class="form-group cf">
                <label name="verification_status" id="verification_status %HIDDEN_FIELDS%">{LBL_VERIFICATION_STATUS}: %VERIFICATION_STATUS%</label>
            </div>
        </div>
        <div class="col-sm-12 %HIDDEN_FIELDS%">
            <div class="form-group cf">
                <select id="country_id" name="country_id" class="show-tick bootstrap-dropdowns">
                    <option value="">{LBL_ISSUING_COUNTRY}*</option>
                    %COUNTRY_OPTIONS%
                </select>
            </div>
            <input type="hidden" name="selected_country_id" id="selected_country_id" value="%SELECTED_COUNTRY_ID%">
        </div>
        <div class="col-sm-12 form-group %HIDDEN_FIELDS% %HIDE_SEND_INVITATION_LINKS%">
            <div class="request-btns-h-div">
                <div class="row">
                    <div class="col-sm-6 col-xs-6 request-btns-h">
                        <div class="form-group cf">
                            %VERIFICATION_LINKS%
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-6 text-right request-btns-h">
                        <div class="form-group cf">
                            %VERIFICATION_LINKS1%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group cf text-center">
            <button type="submit" class="blue-btn" name="save_licenses" id="save_licenses">{BTN_EXPERIENCE_SAVE} </button>
            <div class="space-mdl"></div>
            <input type="reset" class="outer-red-btn" name="license_form_cancel" id="license_form_cancel" data-dismiss="modal" value="{BTN_EXPERIENCE_CANCEL}" />
        </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {

        var autocomp_opt = {
            source: function (request, response) {
                var input = this.element;
                $("#licenses_id").val("");
                //$("#industry_dd_container").removeClass("hidden");
                $("#company_size_dd_container").removeClass("hidden");
                $("#job_location_dd_container").addClass("hidden");
                $("#job_location_container").removeClass("hidden");
                $.ajax({
                    url: "<?php echo SITE_URL; ?>getLicenseSuggestions",
                    type: "POST",
                    minLength: 2,
                    dataType: "json",
                    data: {
                        action: 'getLicenses',
                        licenses_name: request.term
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {label: item.licenses_endorsements_name, value: item.licenses_endorsements_name, id: item.licenses_id};
                        }));
                    },
                    error: function (jq, status, message) {
                    }
                });
            },
            select: function (event, c) {
                licenses_id = c.item.id;
                $("#licenses_id").val(licenses_id);
            },
            autoFocus: true
        };

        var autocomp_opt1 = {
            source: function (request, response) {
                var input = this.element;
                $("#institute_name").val("");
                $("#company_size_dd_container").removeClass("hidden");
                $("#job_location_dd_container").addClass("hidden");
                $("#job_location_container").removeClass("hidden");
                $.ajax({
                    url: "<?php echo SITE_URL; ?>getInstituteSuggestion",
                    type: "POST",
                    minLength: 2,
                    dataType: "json",
                    data: {
                        action: 'getInstitute',
                        institute_name: request.term
                    },
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {label: item.company_type, value: item.company_type, id: item.company_type};
                        }));
                    },
                    error: function (jq, status, message) {
                    }
                });
            },
            select: function (event, c) {
                institute_name = c.item.id;
                $("#institute_name").val(institute_name);
            },
            autoFocus: true
        };

        $("#licenses_name").autocomplete(autocomp_opt);
        $("#institute_name1").autocomplete(autocomp_opt1);
       // initAutocomplete();
        //$(".bootstrap-dropdowns").selectpicker('refresh');
        $("#is_headline_container").fadeOut(1000);
        $(".checkNumber").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                    return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    $(document).on('click', "#license_form_cancel", function() {
        $("#licenses_endorsements_container").show();
        licenses_id = $(this).parents("#add_edit_licenses_form").find("#licenses_endorsement_id").val();
        var edit_experience_container ='';
        if(licenses_id != '') {
            type = "edit";
             edit_experience_container= $(this).parents(".licenses-main").find(".edit-licenses-container");
        } else {
            type = "add";
            edit_experience_container = $(this).parents(".licenses-main").find("#add_license_container");
        }
        edit_experience_container.fadeOut(1500, function() {
            if(type == 'edit') {
                edit_experience_container.parents(".licenses-main").find(".view-licenses-details").fadeIn(1500);
            }
            edit_experience_container.html("");
            $("#add_licenses_endorsement").show();
        });
    });
    $(document).on('change',"#industry_id",function(){
        $("#industry_id").valid();
    });
    $("#add_edit_licenses_form").validate({
        // ignore: ["experience_id", "company_id"],
        rules: {
            licenses_name: {required: true},
            date_obtain: {required: true},
            //institute_name1: {required: true},
            country_id: {required: true},
        },
        messages: {
            licenses_name: {required: "{ERROR_ENTER_LICENSE_ENDORSEMENT_NAME}"},
            date_obtain:{required: "{ERROR_SELECT_DATE_OBTAINDED}"},
            //institute_name1: {required: "{ERROR_ENTER_INSTITUTE_NAME}"},
            country_id: {required: "{ERROR_PLEASE_SELECT_COUNTRY}"},
        },
        highlight: function (element) {
            if (!$(element).is("select")) {
                $(element).addClass('has-error');
                $(element).removeClass('valid-input');
            }  else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error").removeClass('valid-input');
            }
        },
        unhighlight: function (element) {
            if (!$(element).is("select")) {
                $(element).addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
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
            } else if (element.attr("name") == "formatted_address" ||
                    element.attr("name") == "country" ||
                    element.attr("name") == "state" ||
                    element.attr("name") == "latitude" ||
                    element.attr("name") == "longitude"
                    ) {
                $("#google_map").parent("div").append(error);
            } else {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        },
        submitHandler: function(form) {
            return true;
        }
    });
    $(document).on('change', "#is_current", function() {
        var ischecked = $(this).is(':checked');
        if (!ischecked) {
            $("#to_date_container").fadeIn(1000);
            $("#is_headline_container").fadeOut(1000);
        } else {
            $("#to_date_container").fadeOut(1000);
            $("#is_headline_container").fadeIn(1000);
            
        }
    });
    $("#add_edit_licenses_form").ajaxForm({
        beforeSend: function() {
            addOverlay();

        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        success: function(html, statusText, xhr, $form) {
            
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"](obj.success);
                location.reload(true);
                $("#licenses_endorsements_container").show();
                $(".edit-licenses-container").html("");
                $("#licenses_endorsements_container").html(obj.licenses);
                $("#add_licenses_endorsement").show();
                $("#add_license_container").hide();
                height = $("#licenses_endorsements_main").offset().top;
                scrolWithAnimation(height);
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
    $(".date-picker").datepicker({
        maxDate: new Date(),
        autoclose: true,
        dateFormat: "d-m-yy",
        language: "fr"
    });
</script>