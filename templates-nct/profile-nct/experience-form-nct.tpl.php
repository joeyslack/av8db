<form method="post" name="add_edit_experience_form" id="add_edit_experience_form" action="{SITE_URL}add-edit-experience">
    <input type="hidden" name="experience_id" id="experience_id" value="%EXPERIENCE_ID_ENCRYPTED%" />
    <div class="form-list cf">
        <div class="col-sm-12">
            <div class="form-group cf">
                <input type="text" name="company_name" id="company_name" placeholder="{LBL_COMPANY_NAME}*" value="%COMPANY_NAME%" autocomplete="off" />
                <input type="hidden" name="company_id" id="company_id" value="%COMPANY_ID%" />
            </div>
        </div>        
        <div class="col-sm-12"  id="industry_dd_container">
            <div class="form-group cf">
                <select id="industry_id" name="industry_id" class="selectpicker show-tick bootstrap-dropdowns" data-error-placement="inline">
                    <option value="">{LBL_INDUSTRY}*</option>
                    %INDUSTRY_OPTIONS%
                </select>
            </div>
        </div>

         <!-- <div class="col-sm-12 %COMPANY_SIZE_DD_CONTAINER_HIDDEN_CLASS%" id="company_size_dd_container">
            <div class="form-group cf">
                <select id="company_size_id" name="company_size_id" class="selectpicker show-tick bootstrap-dropdowns" data-error-placement="inline">
                    <option value="">{LBL_COMPANY_SIZE}*</option>
                    %COMPANY_SIZE_OPTIONS%
                </select>
            </div>
        </div> -->

        <div class="col-sm-12 %JOB_LOCATION_DD_CONTAINER_HIDDEN_CLASS%" id="job_location_dd_container">
            <div class="form-group cf">
                <select id="job_location_id" name="job_location_id" class="selectpicker show-tick bootstrap-dropdowns" data-error-placement="inline">
                    <option value="">{LBL_JOB_LOCATION_SMALL}*</option>
                    %JOB_LOCATION_OPTIONS%
                </select>
            </div>
        </div>
        
        <div class="col-sm-12">
            <div class="form-group cf">
                <input type="text" name="job_title" id="job_title" placeholder="{LBL_JOB_TITLE}*" value="%JOB_TITLE%" autocomplete="off" />
            </div>
        </div>
        
        <div class="col-sm-12 %JOB_LCOATION_CONTAINER_HIDDEN_CLASS%" id="job_location_container">
            <div class="form-group cf">
                <input type="text" name="job_location" id="job_location" class="autocomplete" placeholder="{LBL_JOB_LOCATION}*" value="%JOB_LOCATION%" />
                <input type="hidden" name="formatted_address" id="formatted_address" value="%FORMATTED_ADDRESS%" />
                <input type="hidden" name="address1" id="address1" value="%ADDRESS1%" />
                <input type="hidden" name="address2" id="address2" value="%ADDRESS2%" />
                <input type="hidden" name="country" id="country" value="%COUNTRY%" />
                <input type="hidden" name="state" id="state" value="%STATE%" />
                <input type="hidden" name="city1" id="city1" value="%CITY1%" />
                <input type="hidden" name="city2" id="city2" value="%CITY2%" />
                <input type="hidden" name="postal_code" id="postal_code" value="%POSTAL_CODE%" />
                <input type="hidden" name="latitude" id="latitude" value="%LATITUDE%" />
                <input type="hidden" name="longitude" id="longitude" value="%LONGITUDE%" />
            </div>
        </div>
        <div class="form-list cf">
            <div class="col-sm-12 col-sm-6">
                <div class="row">
                    <div class="col-sm-6 col-md-6 form-group in cf">
                        <select id="from_month" name="from_month" class="selectpicker show-tick bootstrap-dropdowns" data-live-search="true" data-error-placement="inline">
                            <option value="">{LBL_FROM}*</option>
                            %MONTH_OPTIONS_FROM%
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-6 form-group cf">
                        <select id="from_year" name="from_year" class="selectpicker show-tick bootstrap-dropdowns" data-live-search="true" data-error-placement="inline">
                           <option value="">{LBL_FROM_YEAR}*</option>
                           %FROM_YEAR%
                        </select>
                    </div>
                </div>
            </div>
            <div id="to_date_container" class="col-sm-12 col-sm-6" style="%TO_DATE_CONTAINER_DISPLAY_NONE%">
                <div class="row">
                    <div class="col-sm-6 col-md-6 form-group in cf">
                        <select id="to_month" name="to_month" class="selectpicker show-tick bootstrap-dropdowns" data-live-search="true" data-error-placement="inline">
                            <option value="">{LBL_TO}*</option>
                            %MONTH_OPTIONS_TO%
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-6 form-group in cf">
                        <select id="to_year" name="to_year" class="selectpicker show-tick bootstrap-dropdowns" data-live-search="true" data-error-placement="inline">
                           <option value="">{LBL_TO_YEAR}*</option>
                           %TO_YEAR%
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-list cf">
            <div class="col-sm-6 form-group cf">
                <div class="flat-checkbox">
                    <input type="checkbox" id="is_current" value="y" name="is_current" %IS_CURRENT_CHECKED% />
                    <label for="is_current">{LBL_I_CURRENTLY_WORKING_HERE}</label>
                </div>
            </div>
            <div class="col-sm-6 form-group cf %IS_HEADLINE_CONTAINER_HIDDEN_CLASS%" id="is_headline_container">
                <div class="flat-checkbox">
                    <input type="checkbox" id="is_headline" value="y" name="is_headline" %IS_HEADLINE_CHECKED% />
                    <label for="is_headline">{LBL_MAKE_THIS_HEADLINE}</label>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group cf">
                <textarea placeholder="{LBL_EXPERIENCE_FORM_DESCRIPTION}" id="description" name="description">%DESCRIPTION%</textarea>
            </div>
        </div>
        <div class="form-group cf text-center">
            <button type="submit" class="blue-btn" name="save_experience" id="save_experience">{BTN_EXPERIENCE_SAVE} </button>
            <div class="space-mdl"></div>
            <input type="reset" class="outer-red-btn" name="experience_form_cancel" id="experience_form_cancel" data-dismiss="modal" value="{BTN_EXPERIENCE_CANCEL}" />
        </div>

</form>
<script type="text/javascript">
    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('job_location')),
                {types: ['geocode']}
        );
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
           // window.alert("{ALERT_AUTOCOMPLETE_RETURN_PLACE_CONTAINS_NO_GIOMETRY}");
           // return;
        } else {
            address1 = address2 = city1 = city2 = state = country = postal_code = '';
            formatted_address = place.formatted_address;
            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();
            var arrAddress = place.address_components;
            $.each(arrAddress, function(i, address_component) {
                if (address_component.types[0] == "route") {address1 = address_component.long_name;}
                if (address_component.types[0] == "sublocality") {address2 = address_component.long_name;}
                if (address_component.types[0] == "locality") {city1 = address_component.long_name;}
                if (address_component.types[0] == "administrative_area_level_2") {city2 = address_component.long_name;}
                if (address_component.types[0] == "administrative_area_level_1") {state = address_component.long_name;}
                if (address_component.types[0] == "country") {country = address_component.long_name;}
                if (address_component.types[0] == "postal_code") {postal_code = address_component.long_name;}
            });
            $("#formatted_address").val(formatted_address);
            $("#address1").val(address1);
            $("#address2").val(address2);
            $("#country").val(country);
            $("#state").val(state);
            $("#city1").val(city1);
            $("#city2").val(city2);
            $("#postal_code").val(postal_code);
            $("#latitude").val(latitude);
            $("#longitude").val(longitude);
        }
    }    
    var autocomp_opt = {
        source: function (request, response) {
            var input = this.element;
            $("#company_id").val("");
            //$("#industry_dd_container").removeClass("hidden");
            $("#company_size_dd_container").removeClass("hidden");
            $("#job_location_dd_container").addClass("hidden");
            $("#job_location_container").removeClass("hidden");
            $.ajax({
                url: "<?php echo SITE_URL; ?>getCompanySuggestions",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getCompanies',
                    company_name: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {label: item.company_name, value: item.company_name, id: item.company_id};
                    }));
                },
                error: function (jq, status, message) {
                }
            });
        },
        select: function (event, c) {
            company_id = c.item.id;
            $("#company_id").val(company_id);
            //$("#industry_dd_container").addClass("hidden");
            $("#company_size_dd_container").addClass("hidden");
            $("#job_location_container").addClass("hidden");
            $("#job_location_dd_container").removeClass("hidden");
            $.ajax({
                url: "<?php echo SITE_URL; ?>getCompanyLocations",
                type: "POST",
                dataType: "json",
                data: {
                    action: 'getCompanyLocations',
                    company_id: $("#company_id").val()
                },
                success: function (data) {
                    if(data == ''){
                        bootbox.alert({
                            title: 'Alert',
                            message: '{ERROR_COMPANY_DETAIL_MSG}',
                            reorder: true,
                            buttons:{ok:{label:'OK',className:'outer-blue-btn '}},
                        });
                        return false;
                    }

                    $("#job_location_id").html(data);
                    $("#job_location_id").prepend('<option value="">{LBL_JOB_LOCATIONS_SMALL}</option>');
                    $('.bootstrap-dropdowns').selectpicker('refresh')
                }
            });
        },
        autoFocus: true
    };
    $(document).ready(function() {
        $("#company_name").autocomplete(autocomp_opt);
        initAutocomplete();
        $(".bootstrap-dropdowns").selectpicker('refresh');
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
    $(document).on('click', "#experience_form_cancel", function() {
        $("#experiences_container").show();
        experience_id = $(this).parents("#add_edit_experience_form").find("#experience_id");
        if(experience_id) {
            type = "edit";
        } else {
            type = "add";
        }
        var edit_experience_container = $(this).parents(".developer-detail-main").find(".edit-experience-container");
        edit_experience_container.fadeOut(1500, function() {
            if(type == 'edit') {
                edit_experience_container.parents(".developer-detail").find(".view-experience-details").fadeIn(1500);
            }
            edit_experience_container.html("");
            $("#add_experience").show();
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
            if($("#is_current").is(':checked')) {
                return true;
            } else {
                return parseInt(value) >= parseInt($min.val());
            }      
        },"{ERROR_ADDED_EXP_YEAR}");
    $.validator.addMethod("greaterThanMonth",function (value, element, param) {            
            var $min = $(param);
            var from_year = $("#from_year").val();
            var to_year = $("#to_year").val();
            if (this.settings.onfocusout) {
                $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
                    $(element).valid();
                });
            }      
            if($("#is_current").is(':checked')) {
                return true;
            }
            else if(parseInt(from_year) < parseInt(to_year)){
                return true;   
            } else {
                return parseInt(value) > parseInt($min.val());
            }      
        }, "{ERROR_ADDED_EXP_YEAR}");
    $(document).on('change',"#from_year",function(){
        $("#from_year").valid();
        $("#from_month").valid();


    });
    $(document).on('change',"#to_year",function(){
        $("#to_year").valid();
        $("#to_month").valid();


    });
    
    $(document).on('change',"#industry_id",function(){
        $("#industry_id").valid();

    });
    $(document).on('change',"#company_size_id",function(){
        $("#company_size_id").valid();

    });
    var monthNames = ["January", "February", "March", "April", "May","June","July", "August", "September", "October", "November","December"];
     
    $.validator.addMethod("greatercurrentMont",function (value, element, param) {     
        
            var to_year = $("#to_year").val();
            var current_year= new Date().getFullYear();
            if(current_year == to_year){
                var to_month=$("#to_month").val();
                var current_month= new Date().getMonth() + 1;
                if(to_month >current_month    ){
                    return false;
                }else{
                    return true;
                }
            }else{
                return true;
            }
                  
        }, "{SELECT_MONTH} "+monthNames[new Date().getMonth()]+" "+new Date().getFullYear());
    $.validator.addMethod("greatercurrentMonth",function (value, element, param) {     
        
            var to_year = $("#from_year").val();
            var current_year= new Date().getFullYear();

                if(current_year == to_year){
                    var to_month=$("#from_month").val();
                    var current_month= new Date().getMonth() + 1;
                    
                    if(to_month >current_month){
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
            
                  
        }, "{SELECT_MONTH} "+monthNames[new Date().getMonth()]+" "+new Date().getFullYear());


    $("#add_edit_experience_form").validate({
        ignore: ["experience_id", "company_id"],
        rules: {
            company_name: {required: true},
            job_location_id:{
                required: function() {
                    if($("#formatted_address").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            industry_id: {
                required: true
            
            },
            company_size_id: {
                required: function() {
                    if($("#company_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            job_title: {required: true,alphanumeric: true},
            job_location: {
                required: function() {
                    if($("#company_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            formatted_address: {
                required: function() {
                    if($("#company_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            /*country: {
                required: function() {
                    if($("#company_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            state: {
                required: function() {
                    if($("#company_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            latitude: {
                required: function() {
                    if($("#company_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },
            longitude: {
                required: function() {
                    if($("#company_id").val() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            },*/
            from_month: {required: true,
                        greatercurrentMonth:true

            },
            from_year: {required: true,number: true},
            to_month: {
                required: function() {
                    if($("#is_current").is(':checked')) {
                        return false;
                    } else {
                        return true;
                        
                    }
                },
                greaterThanMonth: '#from_month',
                greatercurrentMont:true
            },
            to_year: {
                required: function() {
                    if($("#is_current").is(':checked')) {
                        return false;
                    } else {
                        return true;
                    }
                },
                greaterThan: '#from_year'
            }
        },
        groups: {clinic_location: "{LBL_FORMATTED_ADDRESS_COUNTRY_STATE_LONGITUDE}"},
        messages: {
            company_name: {required: "{ERROR_ENTER_COMPANY_NAME}"},
            job_location_id:{required: "{ERROR_SELECT_JOB_LOCATION}"},
            industry_id: {required: "{LBL_PLEASE_SELECT_INDUSTRY}"},
            company_size_id: {required: "{ERROR_SELECT_COMPANY_SIZE}"},
            job_title: {required: "{ERROR_ENTER_JOB_TITLE}"},
            job_location: {required: "{ERROR_ENTER_JOB_LOCATION}"},
            formatted_address: {required: "{ERROR_SELECT_JOB_LOCATION}"},
            /*country: {required: "{ERROR_SELECT_JOB_LOCATION}"},
            state: {required: "{ERROR_SELECT_JOB_LOCATION}"},
            latitude: {required: "{ERROR_SELECT_JOB_LOCATION}"},
            longitude: {required: "{ERROR_SELECT_JOB_LOCATION}"},*/
            from_month: {required: "{ERROR_SELECT_FROM_MONTH}"},
            from_year: {required: "{ERROR_SELECT_FROM_YEAR}",number: "{ERROR_ENTER_VALID_YEAR}"},
            to_month: {required: "{ERROR_ENTER_SELECT_TO_MONTH}"},
            to_year: {required: "{ERROR_SELECT_FROM_YEAR}"},
            description: {required: "{ERROR_ENTER_DESCRIPTION}"}
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
    $("#add_edit_experience_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                //toastr["success"](obj.success);
                $("#experiences_container").show();
                $(".edit-experience-container").html("");
                $("#experiences_container").html(obj.experiences);
                $("#add_experience").show();
                
                height = $("#experiences_main").offset().top;
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