<form action="" method="post" name="job_form" id="job_form" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
    <div class="form-body">

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Select business : &nbsp;</label> 
            <div class="col-md-4"> 
                %COMPANY_DD%
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Job category : &nbsp;</label> 
            <div class="col-md-4"> 
                %JOB_CATEGORY_DD%
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Job title : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control required" name="job_title" id="job_title" value="%JOB_TITLE%" />
            </div>
        </div>

        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Employment type: &nbsp;</label> 
            <div class="col-md-4"> 
                <div class="radio-list" data-error-container="#form_2_Status: _error"> 
                    <label class=""> 
                        <input class="radioBtn-bg privacy-radioBtn required" id="employment_type_p" name="employment_type" type="radio" value="p" %EMPOYMENT_TYPE_P% /> Part Time
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg privacy-radioBtn required" id="employment_type_f" name="employment_type" type="radio" value="f" %EMPOYMENT_TYPE_F% /> Full Time
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg privacy-radioBtn required" id="employment_type_c" name="employment_type" type="radio" value="c" %EMPOYMENT_TYPE_C% /> Contract
                    </label>
                    <span for="status" class="help-block"></span>

                    <label class="">
                        <input class="radioBtn-bg privacy-radioBtn required" id="employment_type_t" name="employment_type" type="radio" value="t" %EMPOYMENT_TYPE_T% /> Temporary
                    </label>
                    <span for="status" class="help-block"></span>
                </div>
                <div id="form_2_Status: _error"></div> 
            </div>
        </div>
        

        <div class="form-group">
            <label class="control-label col-md-3">%MEND_SIGN%Key Responsibilities : &nbsp;</label> 
            <div class="col-md-9">
                <textarea placeholder="Add 4 to 6 bullets to describe the role, and help potential applicants learn what makes it a 
                      great opportunity.  " rows="4" name="key_responsibilities" id="key_responsibilities" class="form-control border-field required" data-error-container="#res_editor_error" >%KEY_RESPONSIBILITIES%</textarea>   
               <div id="res_editor_error"></div>
            </div>
        </div>


        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Job location : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control required" name="job_location" id="job_location" value="%JOB_LOCATION%" />
            </div>
        </div>
        
        <div class="form-group"> 
            <label class="control-label col-md-3">%MEND_SIGN%Last date of application : &nbsp;</label> 
            <div class="col-md-4"> 
                <input type="text" class="form-control date-picker required" readonly="" name="last_date_of_application" id="last_date_of_application" value="%LAST_DATE_OF_APPLICATION%" />
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
