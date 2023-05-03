<form method="post" name="add_edit_education_form" id="add_edit_education_form" action="{SITE_URL}add-edit-education">
    <input type="hidden" name="education_id" id="education_id" value="%EDUCATION_ID_ENCRYPTED%" />
    <div class="form-list cf">
        <div class="col-sm-12">
            <div class="form-group cf">
                <input type="text" name="university_name" id="university_name" placeholder="{LBL_UNIVERSITY_NAME}*" value="%UNIVERSITY_NAME%" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group cf">
                <input type="text" name="degree_name" id="degree_name" placeholder="{LBL_DEGREE}*" value="%DEGREE_NAME%" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group cf">
                <input type="text" name="field_of_study" id="field_of_study" placeholder="{LBL_FIELD_OF_STUDY}* " value="%FIELD_OF_STUDY%" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group cf">
                <input type="text" name="grade_or_percentage" id="grade_or_percentage" placeholder="{LBL_GRAD_OR_PERCENTAGE}*" value="%GRADE_OR_PERCENTAGE%" />
            </div>
        </div>
        <div class="form-list cf">
            <div class="col-sm-12 col-sm-12">
                <div class="row">
                    <div class="col-sm-6 col-md-4 form-group in cf">
                            <select id="from_year" name="from_year" class="selectpicker show-tick bootstrap-dropdowns" data-live-search="true" data-error-placement="inline">
                            <option value="">{LBL_FROM}*</option>
                            %YEAR_OPTIONS_FROM%
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4 form-group in cf">
                        <select id="to_year" name="to_year" class="selectpicker show-tick bootstrap-dropdowns" data-live-search="true" data-error-placement="inline">
                        <option value="">{LBL_TO}*</option>
                        %YEAR_OPTIONS_TO%
                    </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group cf">
            <textarea placeholder="{LBL_EDUCATION_FORM_DESCRIPTION}*" id="description" name="description">%DESCRIPTION%</textarea>
            </div>
        </div>
        <div class="form-group cf text-center">
            <button type="submit" class="blue-btn" name="save_education" id="save_education">{BTN_EDUCATION_SAVE} </button>
            <div class="space-mdl"></div>
            <input type="reset" class="outer-red-btn" name="education_form_cancel" id="education_form_cancel" data-dismiss="modal" value="{BTN_EDUCATION_CANCEL}" />
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {$(".bootstrap-dropdowns").selectpicker('refresh');});
    $(document).on('click', "#education_form_cancel", function() {
        $("#educations_container").show();
        education_id = $(this).parents("#add_edit_education_form").find("#education_id");
        if(education_id) {
            type = "edit";
        } else {
            type = "add";
        }
        var edit_education_container = $(this).parents(".education-main").find(".edit-education-container");
        edit_education_container.fadeOut(1500, function() {
            if(type == 'edit') {
                edit_education_container.parents(".education-main").find(".view-education-details").fadeIn(1500);
            }
            edit_education_container.html("");
            $("#add_education").show();
        });
    });
    $.validator.addMethod("greaterThan",
    function (value, element, param) {
      var $min = $(param);
      if (this.settings.onfocusout) {
        $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
          $(element).valid();
        });
      }
      return parseInt(value) > parseInt($min.val());
    }, "{LBL_MAX_MUST_BE_GREATER_THAN_MIN}");
    $(document).on('change',"#from_year",function(){
        $("#from_year").valid();

    });
    $(document).on('change',"#to_year",function(){
        $("#to_year").valid();

    });
    $("#add_edit_education_form").validate({
        ignore: ["education_id"],
        rules: {
            university_name: {required: true},
            degree_name: {required: true},
            field_of_study: {required: true},
            grade_or_percentage: {required: true,onlyCharNum:true},
            //from_year: {required: true},
            //to_year: {required: true,greaterThan: '#from_year'},
            description: {required: true}
        },
        messages: {
            university_name: {required: "{ERROR_PLEASE_ENTER_UNIVERSITY_NAME}"},
            degree_name: {required: "{ERROR_ENTER_DEGREE}"},
            field_of_study: {required: "{ERROR_ENTER_FIELD_OF_STUDY}"},
            grade_or_percentage: {required: "{ERROR_GRADE_OR_PERCENTAGE}"},
            //from_year: {required: "{ERROR_SELECT_FROM_YEAR}"},
            //to_year: {required: "{ERROR_SELECT_TO_YEAR}"},
            description: {required: "{ERROR_SELECT_DESCRIPTION}"}
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
            } else {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        },
        submitHandler: function(form) {
            return true;
        }
    });
    $("#add_edit_education_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                //toastr["success"](obj.success);
                $("#educations_container").show();
                $(".edit-education-container").html("");
                $("#educations_container").html(obj.experiences);
                $("#add_education").show();
                height = $("#educations_main").offset().top;
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
</script>