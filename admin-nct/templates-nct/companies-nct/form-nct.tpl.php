<form action="" method="post" name="company_form" id="company_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Business Name : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control required" name="company_name" id="company_name" value="%COMPANY_NAME%" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Business Logo : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="file" class="form-control" accept="image/*" name="company_logo" id="company_logo" />
            </div>
        </div>
       <div class="form-group">
            <label for="oldimage" class="control-label col-md-3">Old Image:&nbsp;</label>
            <div class="col-md-4">
                <img src="%IMAGE%" width="100px" height="44px" title="%COMPANY_NAME%" alt="%COMPANY_NAME%" />
            </div>
        </div>
        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Description : &nbsp;</label> 
            <div class="col-md-4"> 
                <textarea name="company_description" id="company_description" class="form-control required">%COMPANY_DESCRIPTION%</textarea>
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Business Email ID : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control required" name="owner_email_address" id="owner_email_address" value="%OWNER_EMAIL_ADDRESS%" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Business Industry : &nbsp;</label> 
            <div class="col-md-4"> 
                %COMPANY_INDUSTRY_DD%
            </div>
        </div>

     

        <div class="form-group"> 
            <label class="control-label col-md-3">Enter business location : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control" name="company_locations" id="company_locations" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">Business location(s) : &nbsp;</label> 
            <div class="col-md-4" id="company_locations_container"> 
                %COMPANY_LOCATIONS%
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Business Website : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control required" name="website_of_company" id="website_of_company" value="%WEBSITE_OF_COMPANY%" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Year founded : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control date-picker required" name="foundation_year" id="foundation_year" value="%FOUNDATION_YEAR%" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">Status: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg required" id="a" name="status" type="radio" value="a" %STATUS_A%> Active
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg required" id="d" name="status" type="radio" value="d" %STATUS_D%> Deactive
                    </label>
                    <span for="status" class="help-block"></span>
                </div>
                <div id="form_2_Status: _error"></div> 
            </div>
        </div>

        <div class="flclear clearfix"></div>
        <input type="hidden" name="type" id="type" value="%TYPE%"><div class="flclear clearfix"></div>
        <input type="hidden" name="id" id="id" value="%ID%"><div class="padtop20"></div>

    </div>

    <div class="form-actions fluid">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" name="submitAddForm" class="btn green" id="submitAddForm">Submit</button>
            <button type="button" name="cn" class="btn btn-toggler" id="cn">Cancel</button>
        </div>
    </div>
</form>


<script type="text/javascript">

    var autocomplete;

    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
                (document.getElementById('company_locations')),
                {types: ['geocode']}
        );

        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
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
                    toastr['error']("You already have added this location.");
                    return false;
                }
            });
            
            if(!proceed_to_add_location) {
                $("#company_locations").val();
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
            company_id=$("#id").val();
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>addCompanyLocation",
                data: {
                    action: 'addCompanyLocation_admin',
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
                    longitude: longitude,
                    company_id:company_id
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
            });
        }
    }

    $(document).ready(function() {

        var no_of_locations = %NO_OF_LOCATIONS%;
        if(no_of_locations == 5 || no_of_locations > 5) {
            $("#company_locations").parents(".form-group").fadeOut(1500);
        }
        initAutocomplete();
        $(".date-picker").datepicker({
            autoclose: true,
            format: "<?php echo BOOTSTRAP_DATEPICKER_YEAR_FORMAT; ?>",
            viewMode: "years", 
            minViewMode: "years"
        });
    });

    $(document).on("click", ".make-hq", function() {
        $(".map-box").each(function() {
            $(this).find(".hq_anchor").removeClass("is-hq").addClass("make-hq");
            $(this).find(".is_hq_hidden").val('n');
            
        });
        $(this).removeClass("make-hq").addClass("is-hq");
        $(this).parents(".map-box").find(".is_hq_hidden").val('y');
    });

    $(document).on("click", ".remove-company-location", function() {
        var map_box = $(this).parents(".map-box");
        var bootBoxCallback = function(result) {
            if(result) {
                is_hq = map_box.find(".is_hq_hidden").val();
                map_box.fadeOut(800, function() {
                    map_box.remove();
                });
                
                no_of_locations = $(".map-box").length;
            
                if('y' == is_hq && no_of_locations > 1) {
                    first_map_box = $(".map-box").first();
                    
                    first_map_box.find(".hq_anchor").removeClass("make-hq").addClass("is-hq");
                    first_map_box.find(".is_hq_hidden").val('y');
                }
               
                if(no_of_locations <= 5) {
                    $("#company_locations").parents(".form-group").fadeIn(1500);
                }
            }
        };
        initBootBox("Delete Company Location", "Are you sure you want to delete this location?", bootBoxCallback);
    });
</script>