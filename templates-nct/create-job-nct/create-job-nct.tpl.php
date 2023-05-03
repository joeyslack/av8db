<div class="inner-main">
    <div class="create-company-main">
        <div class="container">
            <h1>{LBL_REACH_QUALITY_CANDIDATE}</h1>
            <form action="<?php echo SITE_URL; ?>create-new-job" class="create-form" name="create_job_form" id="create_job_form" method="post">

                <div class="form-group">
                    <input type="text" class="form-control" id="job_title" name="job_title" placeholder="{LBL_JOB_TITLE}*" />
                </div> 

                <div class="form-group">
                    <input type="text" class="form-control date-picker" id="last_date_of_application" name="last_date_of_application" placeholder="{LBL_LAST_DATE_APPLICATION}*" readonly/>
                </div>

                <div class="form-group">                    
                    <select name="company_name_id" id="company_name_id" class="form-control selectpicker show-tick">
                        <option value="">{LBL_COMPANY_NAME}*</option>
                        %COMPANY_NAME_OPTIONS%
                    </select>
                </div>
                
                <div class="form-group">
                    <select name="category_id" id="category_id" class="form-control selectpicker show-tick">
                        <option value="">{LBL_CATEGORY}*</option>
                        %CATEGORY_OPTIONS%
                    </select>
                </div>  

                <div class="form-group">
                    <!--<input type="text" class="form-control" id="job_location" name="job_location" placeholder="Location*" />-->
                    <select id="job_location" name="job_location" class="selectpicker show-tick form-control bootstrap-dropdowns border-field" data-error-placement="inline">                    
                    <option value="">{LBL_LOCATIONS}*</option>                    
                </select>
                </div>   

                

                 <input type="hidden"  id="formatted_address" name="formatted_address" val="" />
                 <input type="hidden"  id="address1" name="address1" val="" />
                 <input type="hidden"  id="address2" name="address2" val="" />
                 <input type="hidden"  id="country" name="country" val="" />
                 <input type="hidden"  id="state" name="state" val="" />
                 <input type="hidden"  id="city1" name="city1" val="" />
                 <input type="hidden"  id="city2" name="city2" val="" />
                 <input type="hidden"  id="postal_code" name="postal_code" val="" />
                 <input type="hidden"  id="latitude" name="latitude" val="" />
                 <input type="hidden"  id="longitude" name="longitude" val="" />

                
                <div class="form-group text-center">
                    <button type="submit" class="blue-btn" name="create_job" id="create_job">
                        {LBL_START_JOB_POST}
                    </button>
                </div>
            </form>
        </div>
        <div class="company-bg"></div>
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>
<script type="text/javascript">

     $(".date-picker").datepicker({
        minDate: 0,
        autoclose: true,
        dateFormat: "M d, yy",
        language: "fr"
    });

    $(document).on('change','#company_name_id',function(){
        var select_company_id = $(this).val();
        if(select_company_id != ""){
            $.ajax({
                url: "<?php echo SITE_URL; ?>getCompanyLocations",
                type: "POST",
                dataType: "json",
                data: {
                    action: 'getCompanyLocations',
                    company_id: select_company_id
                },
                success: function (data) {
                    if(data == ''){
                        bootbox.alert({
                            title: 'Alert',
                            message: 'Company details are incomplete',
                            reorder: true,
                            buttons:{ok:{label:'OK',className:'blue-btn cancel-btn '}},
                        });
                        return false;
                    }
                    $("#job_location").html(data);
                   // $("#job_location").prepend('<option value="">Locations*</option>');
                    $('.bootstrap-dropdowns').selectpicker('refresh')
                    
                }
            });
        }        
    });
    
    $("#create_job_form").validate({
        ignore: [],
        rules: {
            company_name_id: {
                required: true
            },
            category_id: {
                required: true
            },
            job_title: {
                required: true,
                onlyChar: true
            },
            job_location: {
                required: true
            },
            last_date_of_application: {
              required: true  
            }
        },
        messages: {
            company_name_id: {
                required: lang.LBL_SELECT_COMPANY_NAME
            },
            category_id: {
                required: lang.LBL_SELECT_CATEGORY
            },
            job_title: {
                required: lang.ERROR_ENTER_JOB_TITLE
            },
            job_location: {
                required: lang.ERROR_ENTER_JOB_LOCATION
            },
            last_date_of_application: {
                required: lang.ERROR_LAST_DATE_APPLICATION
            }
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
    
    $("#create_job_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
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

    var autocomplete;

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('job_location')),
                {types: ['geocode']}
        );

        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert(lang.ALERT_AUTOCOMPLETE_RETURN_PLACE_CONTAINS_NO_GIOMETRY);
            return;
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
            
            proceed_to_add_location = true;
            
            $(".map-box").each(function() {
                
                var added_latitude = parseFloat($(this).find(".latitude").val()).toFixed(2);
                var added_longitude = parseFloat($(this).find(".longitude").val()).toFixed(2);
                
                if(added_latitude == parseFloat(latitude).toFixed(2) && added_longitude == parseFloat(longitude).toFixed(2) ) {
                    proceed_to_add_location = false;
                    toastr['error'](lang.ERROR_ALREADY_ADDED_LOCATION);
                    return false;
                }
            });
            
            if(!proceed_to_add_location) {
                //$("#job_location").val();
                return true;
            }
            
            $.each(arrAddress, function(i, address_component) {
                if (address_component.types[0] == "route") {
                    address1 = address_component.long_name;
                }
                if (address_component.types[0] == "sublocality") {
                    address2 = address_component.long_name;
                }

                if (address_component.types[0] == "locality") {
                    //alert("city1:"+address_component.long_name);
                    city1 = address_component.long_name;
                }
                if (address_component.types[0] == "administrative_area_level_2") {
                    city2 = address_component.long_name;
                }

                if (address_component.types[0] == "administrative_area_level_1") {
                    state = address_component.long_name;
                }
                if (address_component.types[0] == "country") {
                    country = address_component.long_name;
                }
                if (address_component.types[0] == "postal_code") {
                    postal_code = address_component.long_name;
                }
            });
            
            no_of_locations = $(".map-box").length;
            if(no_of_locations > 0) {
                is_hq = "n";
            } else {
                is_hq = "y";
            }

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

            
            /*$.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>addCompanyLocation",
                data: {
                    action: 'addCompanyLocation',
                    is_hq: is_hq,
                    formatted_address: formatted_address,
                    address1: address1,
                    address2: address2,
                    country: country,
                    state: state,
                    city1: city1,
                    city2: city2,
                    postal_code: postal_code,
                    latitude: latitude,
                    longitude: longitude
                },
                beforeSend: function() {
                    addOverlay();
                },
                complete: function() {
                    removeOverlay();
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $("#company_locations").val('').focus();
                        $("#company_locations_container").append(data.content);
                        initializeTootltip();
                        no_of_locations = $(".map-box").length;
                        if(no_of_locations == 5 || no_of_locations > 5) {
                            $("#company_locations").parents(".form-group").fadeOut(1500);
                        }
                    } else {
                        toastr['error'](data.error);
                    }

                }
            });*/
        }
    }
</script>
