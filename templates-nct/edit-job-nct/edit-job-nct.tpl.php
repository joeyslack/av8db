<div class="inner-main">
    <div class="edit-company-sec">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-1"></div>
                <div class="col-sm-12 col-md-10">
                    <form action="<?php echo SITE_URL; ?>edit-job" method="post" name="edit_job_form" id="edit_job_form" class="post-form">
                        <div class="edt-comp-info">
                            <div class="gen-wht-bx cf">
                                <h1>{EDIT_JOB_PAGE}</h1>
                                <div class="back-btn-bx text-right">
                                    <a href="javascript:void(0);"  class="trash-ico" data-id="%ENCRYPTED_JOB_ID%" id="deleteJob">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                    <a href="<?php echo SITE_URL; ?>jobs/my-jobs" title="{BTN_EDIT_COMP_BACK_TO_LISTING}" class="back-ico" title="Back to listing">
                                        <i class="icon-back-arrow"></i>
                                    </a>
                                </div>
                                <div class="company-edit-view">
                                    <div class="company-edt-pic">
                                        %COMPANY_LOGO_URL%
                                    </div>
                                    <div class="edt-dtl-company">
                                            <h3><a class="job-title-edit" href="javascript:void(0);" id="job_title" data-type="text" data-pk="%JOB_ID%" data-url="<?php echo SITE_URL; ?>saveJobData" data-title="{LBL_EDIT_ENTER_JOB_TITLE}">%JOB_TITLE%</a> </h3>
                                            <h5>%COMPANY_NAME%</h5>
                                            <a href="javascript:void(0);" id="job_category_id" data-type="select" data-pk="%JOB_ID%" data-url="<?php echo SITE_URL; ?>saveJobData" data-title="{LBL_SELECT_JOB_CATEGORY}"><h5 >%JOB_CATEGORY%</h5></a>
                                            <div class="edt-bx">
                                            <a href="javascript:void(0);" id="edit_info" title="{LBL_EDIT_JOB}"><i class="icon-edit"></i></a>
                                            </div>

                                            <a href="javascript:void(0);" id="formatted_address" data-type="text"  data-title="Select job location">
                                            <div class="addr-bx" id="location_id"><i class="icon-map"></i>%LOCATION%</div></a>
                                        </div>
                                </div>
                            </div>
                            <div class="gen-wht-bx cf">
                                <div class="other-form-list">
                                    <div class="list-form cf">
                                        <div class="col-sm-12 col-md-6">
                                        <h5>{LBL_EMPLOYMENT_TYPE} <sup>*</sup></h5>
                                        <div class=" form-group">
                                            <div class="custom-radio">
                                                <input type="radio" id="ft" name="employment_type"  value="f" %EMPL_TYPE_F_CHECKED%>
                                                <label for="ft">{LBL_FULL_TIME}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" id="pt" name="employment_type" value="p" %EMPL_TYPE_P_CHECKED%>
                                                <label for="pt">{LBL_PART_TIME}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" id="ct" name="employment_type"  value="c" %EMPL_TYPE_C_CHECKED%>
                                                <label for="ct">{LBL_EMPLOYMENTTYPE_CONTRACT}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" id="tt" name="employment_type"  value="t" %EMPL_TYPE_T_CHECKED%>
                                                <label for="tt">{LBL_EMPLOYMENTTYPE_TEMPORARY}</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 form-group">
                                            <h5>{LBL_EXPERIENCE} </h5>
                                                <ul class="row">
                                                    <li class="col-sm-12 col-md-12">
                                                        <div class="form-group">
                                                            <input type="number" name="relavent_experience_from" id="relavent_experience_from" class="checkNumber" value="%EXP_FROM%" min="0">
                                                        </div>
                                                    </li>
                                                </ul>
                                        </div>
                                    </div>
                                    <div class="list-form cf">
                                        <div class="list-form cf">
                                            <div class="col-sm-12 col-md-12 form-group">
                                                <label>{LBL_EDIT_LICENSES_ENDORSEMENTS} <sup>*</sup></label>
                                                <select id="licenses_endorsement" name="licenses_endorsement[]" class="selectpicker show-tick bootstrap-dropdowns border-field" data-error-placement="inline" multiple="">       
                                                    <option value="">{LBL_EDIT_LICENSES_ENDORSEMENTS}</option> 
                                                    %LICENSES_ENDORSEMENTS_OPTIONS%          
                                                </select>
                                            </div>
                                            <input type="hidden" name="selected_value_array" id="selected_value_array" value="%GET_ADDED_LICENSE_VALUES%">
                                        </div>
                                        <div class="list-form cf">
                                            <div class="col-sm-12 col-md-12 form-group">
                                                <div class="row"  id="selected_license">
                                                %GET_ADDED_LICENSE_LIST%
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 form-group">
                                        <h5>{LBL_EDUCATION} </h5>
                                            <select name="degree_id[]" id="degree_id" data-placeholder="{LBL_EDUCATION}" class=" js-example-basic-multiple multiple-education " multiple="multiple" style="width:100%;">
                                            <?php echo $this->degress; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="list-form cf">
                                        <div class="col-sm-12 col-md-12 form-group">
                                            <h5>{LBL_KEY_RESPONSIBILITY}<sup>*</sup></h5>
                                            <textarea placeholder="{LBL_BULLETS_TO_DESCRIBE_ROLE}" rows="4" name="key_responsibilities" id="key_responsibilities" class="" >%RESPONSIBILITY%</textarea>
                                            <script>
                                                CKEDITOR.replace( 'key_responsibilities' );
                                            </script>
                                        </div>
                                    </div>
                                    <div class="list-form cf">
                                        <div class="col-sm-12 col-md-12 form-group">
                                        <h5>{LBL_FORM_EDIT_COMP_COMPANY_DESCRIPTION}</h5>
                                        <p class="company_desc word_wrap_data">%COMPANY_DESC%</p>
                                        </div>
                                    </div>
                                    <div class="list-form cf">
                                        <h4 class="col-sm-12">{LBL_LIKE_PEOPLE_TO_APPLY}</h4>
                                        <div class="col-sm-12 col-md-6 form-group">
                                            <div class="custom-radio">
                                                <input type="radio"  name="apply_flag" id="apply_r" value="r" %APPLY_FLAG_R_CHECKED%>
                                                <label for="apply_r">{LBL_LET_CANDIDATES_APPLY_PROFILE_NOTIFY_BY_EMAIL}</label>
                                                </div>
                                                <input type="text" name="email_recommended" class="" placeholder="Email ID" value="%APPLY_EMAIL_R%">
                                            </div>
                                        <div class="col-sm-12 col-md-6 form-group">
                                            <div class="custom-radio">
                                                <input type="radio"  name="apply_flag" id="apply_nr" value="nr" %APPLY_FLAG_NR_CHECKED%>
                                                <label for="apply_nr">{LBL_DIRECT_APPLICANTS_TO_SITE}</label>
                                            </div>
                                            <input type="text" name="url_not_recommended" class="" placeholder="Site url" value="%APPLY_URL_NR%">
                                        </div>
                                </div>
                                <div class="list-form cf">
                                        <div class="col-sm-12 col-md-12 form-group">
                                        <h5>{LBL_LAST_DATE_APPLICATION}</h5>
                                        <input type="text" class="date-picker" id="last_date_of_application" name="last_date_of_application" placeholder="{LBL_LAST_DATE_APPLICATION}*" value="%LASTDATE%" readonly/>
                                        </div>
                                </div>
                                <div class="list-form cf %HIDE_CLASS_FEATURED% featured_class">
                                    <div class="col-sm-12 col-md-8 form-group">
                                    <div class="flat-checkbox">
                                        <input type="checkbox" id="is_featured" name="is_featured" value="y" <?php echo $this->featured_checked; ?> /> 
                                        <label for="is_featured">
                                            {LBL_MAKE_FEATURED_JOB}
                                        </label>
                                    </div>
                                        <div id="radio_btn_disp" style='%DISPLAY_TARIFF%'>
                                            <?php echo $this->tariff_plan; ?>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="list-form cf">
                                    <div class="col-sm-12 col-md-12 form-group">
                                    <button type="submit" class="blue-btn" name="edit_job" id="edit_job">{LBL_SAVE}</button>
                                <button type="submit" class="outer-red-btn" name="cancel-btn" id="cancel-btn">{LBL_CANCEL}</button>
                                <input type="hidden" name="job_id" id="job_id" value="%ENCRYPTED_JOB_ID%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>                
                </div>
                <div class="col-sm-12 col-md-1"></div>
            </div>
        </div>
           
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $(".date-picker").datepicker({
            minDate: 0,
            autoclose: true,
            dateFormat: "M d, yy",
            language: "fr"
        });
        $(document).on("change",".date-picker",function(){
            $(".featured_class").removeClass('hidden');
        });
        $(".checkNumber").keydown(function (e) {
            //alert(1);
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                 // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        //go to previous link
        $(document).on("click", "#cancel-btn", function(e) {
            window.history.back();
            return false;
        });

        //$.fn.editable.defaults.mode = 'inline';

        //modify buttons style
        $.fn.editableform.buttons = 
          '<button type="submit" class="blue-btn editable-submit"><i class="icon-check"></i></button>' +
         '<button type="button" class="outer-red-btn cancel-btn editable-cancel"><i class="icon-close"></i></button>';         

        $(document).on('click', "#edit_info", function(e) {
            $('#job_title').editable('show'); 
            return false;
        });

         $('#job_title').editable({
                clear :false,
                validate: function(value) {
                  if ($.trim(value) === null || $.trim(value) === '') {
                        return '{LBL_EDIT_ENTER_JOB_TITLE}';
                  }else if(!/^[A-Za-z0-9][A-Za-z0-9_ ]*$/i.test(value)){
                     return '{ENTER_CHAR_OR_NUM}';
                  }
                },
                display: function(value) {
                  $(this).html(value);
                },
                tpl:'<input class="form-control input-sm border-field" type="text">' 
         });

         $('#job_title').on('hidden', function(e, reason) {
            if(reason === 'save' || reason === 'cancel' || reason === 'nochange') {
                //auto-open next editable
                $('#job_category_id').editable('show');
                $(".bootstrap-dropdowns").selectpicker('refresh');
            } 
        });

         $(document).on('click', "#job_category_id", function() {
                $('#job_category_id').editable('show');
                $(".bootstrap-dropdowns").selectpicker('refresh');
         });

         $('#job_category_id').editable({
            value: '<?php echo $this->category_selected; ?>',    
            source: '<?php echo json_encode($this->category_option); ?>',
            display: function(value, source) {
                var html = [],
                checked = $.fn.editableutils.itemsByValue(value, source);
                $.each(checked, function(i, v) { html.push($.fn.editableutils.escape(v.text)); });
                $(this).html('<h5>' + html.join(', ') + '</h5>');
            },
            tpl:'<select class="selectpicker show-tick form-control bootstrap-dropdowns border-field input-sm">' 
        });

         $('#job_category_id').on('hidden', function(e, reason) {
            if(reason === 'save' || reason === 'cancel' || reason === 'nochange') {
                //auto-open next editable
                $('#formatted_address').trigger('click');
                $('#formatted_address').editable('show');
            } 
        });

         $.fn.editable.defaults.mode = 'inline';
         $('#formatted_address').editable({
                clear :false,
                validate: function(value) {
                  if ($.trim(value) === null || $.trim(value) === '') {
                        return 'Empty values not allowed';
                  }
                },
                display: function(value) {
                  $(this).html('<div class="addr-bx" id="location_id"><i class="icon-map"></i>' + value + '</div>');
                },
                tpl: '<input type="text" id="job_location" style="width:500px;">',
                showbuttons: false
         });

         $(document).on('click', "#formatted_address", function() {
            initAutocomplete();
         });

         //$(".js-example-basic-multiple").select2();
         
    });

    jQuery.validator.addMethod('greaterThan1', function(value, element, param) {
    return (  jQuery(param).val() == 0 || value > jQuery(param).val());
    //return ( value > jQuery(param).val() );
    }, '{LBL_GREATER_EXP}' );
        

    $("#edit_job_form").validate({
        ignore: [],
        rules: {
            employment_type: {
                required: true
            },
            key_responsibilities: {
                required: function() {
                    CKEDITOR.instances.key_responsibilities.updateElement();
                }
            },
            // skills_and_exp: {
            //     required: function() {
            //         CKEDITOR.instances.skills_and_exp.updateElement();
            //     }
            // },
            'licenses_endorsement[]': {required: true},
            email_recommended: {
                required: function() {
                    if($("[name=apply_flag]:checked").val() == 'r') {
                        return true;
                    } else {
                        return false;
                    }
                },
                email: function() {
                    if($("[name=apply_flag]:checked").val() == 'r') {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            url_not_recommended: {
                required: function() {
                    if($("[name=apply_flag]:checked").val() == 'nr') {
                        return true;
                    } else {
                        return false;
                    }
                },
                url: function() {
                    if($("[name=apply_flag]:checked").val() == 'nr') {
                        return true;
                    } else {
                        return false;
                    }
                },
            },
            // relavent_experience_to: {    
            //     greaterThan1:"#relavent_experience_from",
            // },
        },
        messages: {
            employment_type: {
                required: lang.LBL_PLEASE_SELECT_EMPLOYMENT_TYPE
            },
            key_responsibilities: {
                required: lang.LBL_ENTER_REPONSIBILITY
            },
            // skills_and_exp: {
            //     required: lang.PLEASE_ENTER_SKILLS_AND_EXP
            // },
            'licenses_endorsement[]': {required: lang.ERROR_MESSAGE_SELECT_LICENSE_ENDORSEMENT},
            url_not_recommended: {
                required: lang.ERROR_EDIT_COMP_ENTER_VALID_URL,
                url: lang.ERROR_EDIT_COMP_ENTER_VALID_URL
            },
            email_recommended: {
                required: lang.ERROR_SIGNUP_ENTER_EMAIL_ADDRESS,
                email: lang.LBL_ENTER_VALID_EMAIL,
            }
        },
        highlight: function(element) {
            //$(element).addClass('has-error');

            if (!$(element).is("select")) {
                if($(element).attr("name") == "key_responsibilities") {
                    $("#cke_key_responsibilities").addClass("has-error");   
                } 
                // if($(element).attr("name") == "skills_and_exp") {
                //     $("#cke_skills_and_exp").addClass("has-error");
                // }

                $(element).removeClass("valid-input").addClass("has-error");
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            //$(element).closest('.form-group').removeClass('has-error');
            if (!$(element).is("select")) {

                if($(element).attr("name") == "key_responsibilities") {
                    $("#cke_key_responsibilities").removeClass("has-error");   
                } 
                // if($(element).attr("name") == "skills_and_exp") {
                //     $("#cke_skills_and_exp").removeClass("has-error");
                // }

                $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            //console.log(error);
            //console.log(element);
            $(element).parent("div").append(error);
            scrolWithAnimation(800); 
        },
        submitHandler: function(form) {
            return true;
        }
    });

    $(document).on('click', "#is_featured", function() {
        
        if(this.checked) {
            $("#radio_btn_disp").show();
        } else {
            $("#radio_btn_disp").hide();
        }
    });

    $("#edit_job_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                //toastr["success"](obj.success);
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
           // window.alert(lang.ALERT_AUTOCOMPLETE_RETURN_PLACE_CONTAINS_NO_GIOMETRY);
           // return;
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

            
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>addJobLocation",
                data: {
                    action: 'addJobLocation',
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
                    job_id: %JOB_ID%
                },
                beforeSend: function() {
                    addOverlay();
                },
                complete: function() {
                    removeOverlay();
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $("#job_location").val(data.location);
                    $("#location_id").html(data.location);
                }
            });
        }
    }

     $(".multiple-skills").select2({
          ajax: {
            url: "<?php echo SITE_URL; ?>getSkillsForEditJob",
            dataType: 'json',
            delay: 250,
            type:'post',
            data: function (params) {
              return {
                skill_name: params.term, // search term
                skill_id: "'"+$(".multiple-skills").val()+"'", // search term
                action: 'getSkills' // search term
              };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.skill_id_orig, text: obj.skill_name };
                    })
                };
            },
            cache: true
          },    
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1
    });
    
    $(".multiple-licenses-endorsements").select2({
          ajax: {
            url: "<?php echo SITE_URL; ?>getLicensesEndorsements",
            dataType: 'json',
            delay: 250,
            type:'post',
            data: function (params) {
              return {
                licenses_endorsement_name: params.term, // search term
                licenses_endorsement_id: "'"+$(".multiple-licenses-endorsements").val()+"'", // search term
                action: 'getLicensesEndorsements' // search term
              };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.licenses_endorsement_id_orig, text: obj.licenses_endorsements_name };
                    })
                };
            },
            cache: true
          },    
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1
    });
    
     $(".multiple-education").select2({
          ajax: {
            url: "<?php echo SITE_URL; ?>getDegreesForSuggestion",
            dataType: 'json',
            delay: 250,
            type:'post',
            data: function (params) {
              return {
                degree_name: params.term, // search term
                degree_id: "'"+$(".multiple-education").val()+"'", // search term
                action: 'getDegrees', // search term
                sess_user_id: "<?php echo $_SESSION['user_id']; ?>",
              };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return { id: obj.degree_id_orig, text: obj.degree_name };
                    })
                };
            },
            cache: true
          },    
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1
    });

    $(document).on("click", "#deleteJob", function() {
        var job_id = $(this).data('id');
       

        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>deleteJob",
                    data: {

                        job_id: job_id,
                        action: 'deleteJob'
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
                            //toastr['success'](data.msg);
                            window.location.href = '{SITE_URL}'+'jobs/my-jobs';
                           
                        } else {
                            toastr['error'](data.msg);
                        }
                    }
                });
            }
        }
        initBootBox(lang.LBL_DELETE_JOB, lang.ARE_YOU_SURE_T0_DELETE_JOB, bootBoxCallback);
    });
    var map = $("#licenses_endorsement").on("change",function(){
        var arr = [];
        var str = $('#selected_value_array').val();
        var array = str.split(',');
        //console.log(array);
        var comp = $("#licenses_endorsement option:selected").map(function() {
                return this.value;
            }).get(),
            set1 = map.filter(function(i) {
                return comp.indexOf(i) < 0;
            }),
            set2 = comp.filter(function(i) {
                return map.indexOf(i) < 0;
            }),
            last = (set1.length ? set1 : set2)[0];   
             $('#licenses_endorsement option').each(function() {
                if($(this).is(':selected')){
                    selText = $(this).filter(':selected').text();
                    selVal = $(this).filter(':selected').val();
                    arr.push({
                        val1:  selVal,
                        text1: selText
                    });
                }
            });
            array.push(last);
            var str1 = array.toString();
            
            $.each(arr, function( key, value )
            {   
                if ($("#selected_license #license_"+value.val1).length > 0){    
                    
                }else{
                    $('#selected_value_array').val(str1);
                    $('#selected_license').append('<div class="col-md-4"><div id="license_'+value.val1+'" class="form-group"><label>'+value.text1+' {MINIMUM_REQUIRED_HOURS}</label><a class="deleteLicense" data-licenseId='+value.val1+'><i class="icon-close"></i></a> <input type="text" name="license_hours[]" class="positive-integer" id="hours_'+value.val1+'" placeholder="{MINIMUM_REQUIRED_HOURS}" value=""></div></div>');
                }
            });
        map = comp; 
    }).find('option:selected').map(function() {return this.value}).get();

    $(document).on('click','.deleteLicense',function(){
        var license_id = $(this).attr('data-licenseId');
        var selected_vales = $('#selected_value_array').val();
        var removed = selected_vales.replace(license_id,'');
        $('#selected_value_array').val(removed);
        $("#license_l_"+license_id).remove();
        $('#licenses_endorsement option[value=l_'+license_id+']').removeAttr('selected');
        $('#licenses_endorsement').selectpicker('refresh');
    });
</script>