<form action="" method="post" name="license_endorsement_form" id="license_endorsement_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">
        %LICENSES_ENDORSEMENT%

        <div class="form-group">
            <label class="control-label col-md-3">Licenses
& Endorsements: &nbsp;</label> 
            <div class="col-md-4">
                <div class="radio-list" data-error-container="#form_2_Status: _error">
                    <label class=""> 
                        <input class="" id="commercial" name="commercial" type="checkbox" value="y"  %ISCOMMERCIAl%> Commercial
                    </label>
                    <label class=""> 
                        <input class="" id="license" name="license" type="checkbox" value="y" %ISLICENSE%> License
                    </label>
                    <label class=""> 
                        <input class="" id="both" name="both" type="checkbox" value="y" %ISBOTH%> Both
                    </label>
                    <label class=""> 
                        <input class="" id="none" name="none" type="checkbox" value="y" %ISNONE%> None
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">Status: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg required" id="a" name="status" type="radio" value="y" %STATUS_A%> Active
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg required" id="d" name="status" type="radio" value="n" %STATUS_D%> Deactive
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
            <button type="submit" name="submitAddForm" class="btn green" id="submitAddForm">Save</button>
            <button type="button" name="cn" class="btn btn-toggler" id="cn">Cancel</button>
        </div>
    </div>
</form>

<!-- <script type="text/javascript">
    
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
                $("#job_location").val();
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
    $(document).ready(function() {
        loadCKE("key_responsibilities");
        loadCKE("skills_and_exp");
        initAutocomplete();

        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

        $(".date-picker").datepicker({
            autoclose: true,
            startDate: today,
            format: "<?php echo BOOTSTRAP_DATEPICKER_FORMAT; ?>"
        });
    });

    /*$(".multiple-skills").select2({
        ajax: {
            url: "<?php echo SITE_URL; ?>getSkillsForEditJob",
            dataType: 'json',
            quietMillis: 250,
            method: 'POST',
            cache: true,
            data: function (term, page) {
                return {
                    skill_name: term.term,
                    action: 'getSkills'
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.skill_id_orig, text: obj.skill_name };
                    })
                };
            }
        }
    });*/
</script>
 -->