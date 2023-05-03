<form method="post" name="edit_user_detail_form" id="edit_user_detail_form" action="{SITE_URL}edit_user_detail">
    <div class="form-group cf">
        <label><b>{LBL_PROFILE_ACCOUNT_CREATED_DATE}</b></label>
        <br>%CREATED_DATE%
    </div>
    <div class="form-group cf">
        <input type="text" name="first_name" id="first_name" class="" placeholder="{LBL_EDIT_PROFILE_FIRST_NAME}" value="%FIRST_NAME%" />
    </div>
    <div class="form-group cf">
        <input type="text" name="last_name" id="last_name" class="" placeholder="{LBL_EDIT_PROFILE_LAST_NAME}" value="%LAST_NAME%" />
    </div>
    <div class="form-group cf">
        <input type="text" name="user_email" id="user_email" class="" placeholder="{LBL_EDIT_PROFILE_EMAIL_ADDRESS}" value="%USER_EMAIL_ADDRESS%" readonly="readonly" />
    </div>
    <div class="form-group cf">
        <input type="text" name="contact_no" id="contact_no" class="" placeholder="{LBL_EDIT_PROFILE_PHONE_NUMBER}" value="%USER_CONTACT_NO%" />
    </div>
     <div class="form-group cf">
        <textarea name="personal_details" id="personal_details" placeholder="Enter Personal Summary/Description">%PERSONAL_DETAILS%</textarea>
    </div>
    <div class="form-group cf">
        <div id="user_location_container">
            <input type="text" name="user_location" id="user_location" class="autocomplete" placeholder="Select Location" value="%USER_LOCATION%" />
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
    <div class="form-group cf">
        <input type="text" name="user_DOB" id="user_DOB" class="date_picker" placeholder="Enter or Select Date of birth(dd-mm-yy)" value="%USER_DOB%" />
    </div>
    <div class="form-group cf">
        <select class="form-control" id="gender" name="gender">
            <option value="">{LBL_PROFILE_SELECT_GENDER}</option>
            <option value="m" %MALE_SELECTED%>{LBL_PROFILE_MALE}</option>
            <option value="f" %FEMALE_SELECTED%>{LBL_PROFILE_FEMALE}</option>
        </select>
    </div>
    <div class="form-group cf custom-radio text-left %HIDE_COMMERCIAL_NOT_VERIFIED%">
        <input type="radio" id="is_ferry_y" name="is_ferry1" value="y" %FERRY_Y% class=""/>
        <label for="is_ferry_y">{LBL_PROFILE_IS_FERRY_YES}</label>

        <input type="radio" id="is_ferry_n" name="is_ferry1" value="n" %FERRY_N% class=""/>
        <label for="is_ferry_n">{LBL_PROFILE_IS_FERRY_NO}</label>
    </div>
    <div class="form-group cf">
        <button type="submit" class="blue-btn" name="save_user_detail" id="save_user_detail">{BTN_EDIT_PROFILE_SAVE} </button>
        <input type="reset" class="outer-red-btn" name="user_detail_form_cancel" id="user_detail_form_cancel" data-dismiss="modal" value="{BTN_EDIT_PROFILE_CANCEL}" />
    </div>
</form>
<script type="text/javascript">
    $(".date_picker").datepicker({
        maxDate: new Date(),
        autoclose: true,
        dateFormat: "dd-mm-yy",
        language: "fr"
    });

    $.validator.addMethod("addname", function(value, element) {
       // return this.optional(element) || /[a-z]+[0-9]*$/i.test(value);
        return /^[a-zA-Z0-9][a-zA-Z0-9\'\s]*$/.test(value);
    }, "{PLEASE_ENTER_VALIDATION}");
    $.validator.addMethod('pagenm', function (value, element) {
        return /^[0-9\-]*$/.test(value);
    },'{PLEASE_ENTER_VALID_NUMBERS}');
    $(document).ready(function() {
        initAutocomplete();
    });
    var autocomplete;
    var IsplaceChange = true;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('user_location')),
                {types: ['geocode']}
        );
        autocomplete.addListener('place_changed', fillInAddress);
        var input = document.getElementById('user_location');
        google.maps.event.addDomListener(input, 'keydown', function(e) { 
            if (e.keyCode == 13) { 
                e.preventDefault(); 
            }
        }); 
    }
    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            //window.alert("{ALERT_AUTOCOMPLETE_RETURN_PLACE_CONTAINS_NO_GIOMETRY}");
            //return;
        } else {
            address1 = '';
            address2 = '';
            city1 = '';
            city2 = '';
            state = '';
            country = '';
            postal_code = '';
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
            IsplaceChange = true;

        }
    }
    $(document).ready(function() {
      $("#user_location").keydown(function () {
            IsplaceChange = false;
        });
    });

    $(document).on('click', "#user_detail_form_cancel", function() {
        $("#update_user_details").fadeOut();
        $("#user_details_container").fadeIn(1500, function() {
            height = $("#user_details_container").offset().top - 10;
            scrolWithAnimation(height);
        });
    });
     $("#edit_user_detail_form").validate({
        rules: {
            first_name: {
                required: true,
                minlength: 3,
                maxlength: 25,
                addname:true
            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 25,
                addname:true
            },
            user_email: {
                required: true,
                checkEmail: true
            },
            contact_no: {
                required: true,
                pagenm: true,
                minlength: 10,
                maxlength: 15,
            }
        },
        messages: {
            first_name: {
                required: lang.ERROR_SIGNUP_ENTER_YOUR_FIRST_NAME,
                minlength: lang.ERROR_SIGNUP_FIRST_NAME_MINIMUM_CHARACHTERS,
                maxlength: lang.ERROR_SIGNUP_FIRST_NAME_MAXIMUM_CHARACHTERS
            },
            last_name: {
                required: lang.ERROR_FEEDBACK_LAST_NAME,
                minlength: lang.ERROR_SIGNUP_LAST_NAME_MINIMUM_CHARACHTERS,
                maxlength: lang.ERROR_SIGNUP_LAST_NAME_MAXIMUM_CHARACHTERS
            },
            user_email: {
                required: lang.ERROR_USER_EMAIL_ADDRESS,
            },
            contact_no: {
                required: lang.ERROR_USER_PHONE_NUMBER,
                minlength: lang.ERROR_PHONE_NUMBER_MINIMUM_DIGITS,
                maxlength: lang.ERROR_PHONE_NUMBER_MAXIMUM_DIGITS  
            }
        },
        highlight: function (element) {
            //$(element).closest('.form-group').addClass('has-error');
            $(element).addClass('has-error');
        },
        unhighlight: function (element) {
            //$(element).closest('.form-group').removeClass('has-error');
            $(element).addClass('valid-input');
            $(element).removeClass('has-error');
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
                //$(element).parent("div").append(error);
                $(element).parent("div").append(error);
            }
        },
        submitHandler: function(form) {
                if (IsplaceChange == false) {
                    $("#user_location").val('');
                    toastr["error"]("{LOCATION_ERROR_MSG_VAILD}");
                    IsplaceChange=true;
                }
                else {
                        return true;
                }        
            }
    });
    $("#edit_user_detail_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                window.location.reload();
                //toastr["success"](obj.success);
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