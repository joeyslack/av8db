<div class="inner-main">
    <div class="nav-menu in-menu">
        <div class="container">
            <ul id="submenu" class="sub-menu">
                <li>
                    <a href="javascript:void(0);" class="switch_my_jobs %MY_JOBS_ACTIVE_CLASS%" title="{LBL_SUB_HEADER_MY_JOBS}" data-type="my_jobs" data-endpoint="my-jobs">
                        {LBL_SUB_HEADER_MY_JOBS}
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="switch_my_jobs %APPLIED_JOBS_ACTIVE_CLASS%" title="{LBL_APPLIED_JOBS}" data-type="applied_jobs" data-endpoint="applied-jobs">
                        {LBL_SUB_HEADER_APPLIED_JOBS}
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="switch_my_jobs %SAVED_JOBS_ACTIVE_CLASS%" title="{LBL_SUB_HEADER_SAVED_JOBS}" data-type="saved_jobs" data-endpoint="saved-jobs">
                        {LBL_SUB_HEADER_SAVED_JOBS}
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="my-jobs-sec cf">
        <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="gen-wht-bx text-center cf fix-sidebar left-first-fix" data-spy="affix" data-offset-top="0" data-offset-bottom="30">
                    <div class="in-compny-heading fade fadeIn">
                        <h1>{LBL_MY_JOB_JOBS_TITLE}</h1>
                        <p>{LBL_MY_JOB_JOBS_WELCOME_TEXT}</p>
                    </div>
                     <div class="in-create-com fade fadeIn">
                        <h3>{LBL_MY_JOB_POST_A_JOB}</h3>
                        <p>{LBL_MY_JOB_POST_A_JOB_NOTE}</p>
                        <div>
                        <a class="blue-btn" id="create_job" title="{LBL_MY_JOB_CREATE_JOB}">
                        {LBL_MY_JOB_CREATE_JOB_CREATE_BUTTON}
                        </a>
                        <!-- <a class="blue-btn" data-toggle="modal" data-target="#createjobs" title="{LBL_MY_JOB_CREATE_JOB}">
                        {LBL_MY_JOB_CREATE_JOB_CREATE_BUTTON}
                    </a> --></div>
                     </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 in-fl-rgt hidden-sm hidden-xs">
               <?php echo $this->subscribed_membership_plan_details; ?>
            </div>
            <div class="col-sm-12 col-md-6">
                <div id="jobs_container"><?php echo $this->content; ?></div>
                <!-- <div id="pagination_container"><?php //echo $this->pagination; ?></div> -->
            </div>
            
        </div>
        </div>
        <input type="hidden" name="company_count" value="%COMPANY_COUNT%" id="company_count">
        <input type="hidden" name="company_count_edit" value="%COMPANY_COUNT_EDIT%" id="company_count_edit">
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>


<!-- Modal -->
<div class="modal fade" id="createjobs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
        <h4 class="modal-title" id="myModalLabel">{LBL_REACH_QUALITY_CANDIDATE}</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo SITE_URL; ?>create-new-job" class="create-form" name="create_job_form" id="create_job_form" method="post">
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_JOB_TITLE} <sup>*</sup></label>
                    <input type="text" class="" id="job_title" name="job_title" placeholder="{LBL_JOB_TITLE}" />
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_LAST_DATE_APPLICATION} <sup>*</sup></label>
                    <input type="text" class="date-picker" id="last_date_of_application" name="last_date_of_application" placeholder="{LBL_LAST_DATE_APPLICATION}" readonly/>

                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_COMPANY_NAME} <sup>*</sup></label>
                    <select name="company_name_id" id="company_name_id" class="selectpicker show-tick form_control">
                        <option value="">{LBL_COMPANY_NAME}</option>
                        %COMPANY_NAME_OPTIONS%
                    </select>
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_CATEGORY} <sup>*</sup></label>
                    <select name="category_id" id="category_id" class="selectpicker show-tick form_control">
                        <option value="">{LBL_CATEGORY}</option>
                        %CATEGORY_OPTIONS%
                    </select>
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group">
                    <label>{LBL_LOCATIONS} <sup>*</sup></label>
                    <select id="job_location" name="job_location" class="selectpicker show-tick bootstrap-dropdowns border-field" data-error-placement="inline">                    
                        <option value="">{LBL_LOCATIONS}</option>                 
                    </select>
                </div>
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
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group">
                    <label>{LBL_SELECT_LICENSES_ENDORSEMENT} <sup>*</sup></label>
                   <select id="licenses_endorsement" name="licenses_endorsement[]" class="selectpicker show-tick bootstrap-dropdowns border-field" data-error-placement="inline" multiple="">            
                       <option value="">{LBL_SELECT_LICENSES_ENDORSEMENT}</option>
                       %LICENSES_ENDORSEMENTS_OPTIONS%           
                    </select>
                </div>
            </div>
            <div class="list-form cf">
                <div class="col-sm-12 col-md-12 ">
                    <button type="submit" class="blue-btn" name="create_job" id="create_job">
                        {LBL_START_JOB_POST}
                    </button>
                    <button type="button" class="outer-red-btn close_btn" data-dismiss="modal">{LBL_COM_DET_CLOSE}</button>
                </div>
            </div>

                <div class="form-group text-center"></div>
            </form>
      </div>
      
    </div>
  </div>
</div>

<script>
    $(document).on("click", ".load_more", function(e) {
        e.preventDefault();
        var url = $(this).attr("href");
        
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#jobs_container").find(".load-more-data").remove();
                    $("#jobs_container").append(data.content);
                   // $("#search_results_container").find(".no-results").remove();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });
    function loadMoreRecordfordata(url) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#jobs_container").find(".view-more-btn a").remove();
                    $("#jobs_container").append(data.content);

                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }

    var ajax_call = true;
   
    window.addEventListener("scroll",onScrollnew);
    
    function onScrollnew(){
         var height=$(window).height();

        if( /Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            height=window.visualViewport.height;
        }
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0) 
        {
            height=$(window).innerheight();
        }


         if (($(window).scrollTop() + height) >= $(document).height() && ajax_call==true) {

            var url = $(".view-more-btn a").attr('href');
            if(url) {

                loadMoreRecordfordata(url);
            }
            
        }
    }
    $(document).on("click",'#create_job',function(){
        if($("#company_count").val() >= 1 && $("#company_count_edit").val()>=1){
            $('#createjobs').modal('show')
        }else{
            if($("#company_count").val()==0){
                toastr['error']("{PLZ_ADD_ONE_COMPANY}");

            }else if($("#company_count_edit").val()==0){
                toastr['error']("{LBL_ERROR_INCOMPLETE_DETAIL}");

            }
            setTimeout(function(){location.href="{SITE_URL}company/my-companies"} , 1500);   

        }

    });

    $('#createjobs').on('hidden.bs.modal', function () {
        $("#create_job_form")[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $("#job_location").html('<option value="">Locations*</option>');

        $('.bootstrap-dropdowns').selectpicker('refresh');
        $('#create_job_form').validate().resetForm();
        $('#create_job_form').find('.error').removeClass('has-error');
        $('#create_job_form').find('.valid-input').removeClass('valid-input');


    });
    $('#last_date_of_application').on('change', function() {
        $('#last_date_of_application').valid(); 
    });
    $('#company_name_id').on('change', function(){
        $('#company_name_id').valid(); 

    });
    $('#category_id').on('change', function(){
        $('#category_id').valid(); 

    });
    $(document).on("click", ".switch_my_jobs", function() {
        if (!$(this).hasClass("active")) {
            type = $(this).data("type");
            getJobs(1, type, true, $(this));
        } else {
            toastr['error'](lang.ERROR_SAME_PAGE_TRYING_TO_VIEW);
        }
    });

    function getJobs(page, type, tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getJobs",
            data: {
                page: page,
                type: type,
                action: 'getJobs'
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
                    if (tab_changed) {
                        $("#submenu li").each(function() {
                            current_element = $(this).find("a.switch_my_jobs");
                            current_element.removeClass("active");

                            if (current_element.hasClass("active")) {
                                
                            }
                        });
                        
                        if(tab_changed) {
                            tab_element.addClass("active");
                            var endpoints = tab_element.data("endpoint");
                            window.history.pushState("", "Title", endpoints);
                        }
                    }

                    updatePageContent(data);
                    if (type == 'my_jobs') {
                        search_type = "my-jobs";
                    } else if(type == 'applied_jobs') {
                        search_type = "applied-jobs";
                    } else if(type == 'saved_jobs') {
                        search_type = "saved-jobs";
                    } 
                    
                    if(page > 1) {
                        console.log(1);
                        window.history.pushState("", "Title", search_type + "?page=" + page);    
                    } else {
                        console.log(2);
                        window.history.pushState("", "Title", search_type + "?page=" + page);    
                    }
                    

                } else {
                    toastr['error'](data.error);
                }

            }
        });
    }

    function updatePageContent(data) {
        $("#jobs_container").html(data.content);
        $("#pagination_container").html(data.pagination);

        height = $("#submenu").offset().top;
        scrolWithAnimation(height);
        $(window).scroll();
    }

    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        var type = $("#submenu").find("li a.active").data("type");
        getJobs(page, type, false, '');
    });

    $(document).on("click", "#removeSavedJobs", function() {
        var job_id = $(this).data('id');
        var page = $(".pagination .buttonPageActive").html() > 0 ? $(".pagination .buttonPageActive").html() : 1;

        var bootBoxCallback = function(result) {
            if (result) {

         $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>removeJobs",
            data: {
                page: page,
                job_id: job_id,
                action: 'removeJobs'
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
                    toastr['success'](data.msg);
                    $(".jobs_" + data.id).remove();
                    if(page == 1) {
                        getJobs(page, 'saved_jobs', false, '');    
                    } else {
                        getJobs(page-1, 'saved_jobs', false, '');
                    }
                    
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
     }}
     initBootBox(lang.REMOVE_SAVED_JOB, lang.ARE_YOU_SURE_T0_REMOVE_JOB, bootBoxCallback);
    });

$(document).on("click", "#withdrawAppliedJobs", function() {
        var job_id = $(this).data('id');
        var page = $(".pagination .buttonPageActive").html() > 0 ? $(".pagination .buttonPageActive").html() : 1;
        
        var bootBoxCallback = function(result) {
            if (result) {

         $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>withdrawAppliedJobs",
            data: {
                page: page,
                job_id: job_id,
                action: 'withdrawAppliedJobs'
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
                    toastr['success'](data.msg);
                    $(".jobs_" + data.id).remove();
                    if(page == 1) {
                        getJobs(page, 'applied_jobs', false, '');    
                    } else {
                        getJobs(page-1, 'applied_jobs', false, '');
                    }
                    
                } else {
                    toastr['error'](data.msg);
                }
            }
        });

         }}
         initBootBox(lang.LBL_WITHDRAW_APPLIED, lang.ALERT_SURE_WANT_TO_DELETE, bootBoxCallback);
    });

    $(document).on("click", "#deleteJob", function() {
        var job_id = $(this).data('id');
        var page = $(".pagination .buttonPageActive").html() > 0 ? $(".pagination .buttonPageActive").html() : 1;

        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>deleteJob",
                    data: {
                        page: page,
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
                            toastr['success'](data.msg);
                            $(".jobs_" + data.id).remove();
                            if(page == 1) {
                                getJobs(page, 'my_jobs', false, '');    
                            } else {
                                getJobs(page-1, 'my_jobs', false, '');
                            }
                            
                        } else {
                            toastr['error'](data.msg);
                        }
                    }
                });
            }
        }

        initBootBox(lang.LBL_DELETE_JOB, lang.ARE_YOU_SURE_T0_DELETE_JOB, bootBoxCallback);
    });
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
</script>

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
                        bootbox.dialog({
                            title: '{LBL_COMPANY_DETAIL}',
                            message: '{LBL_ERROR_INCOMPLETE_DETAIL}',
                            reorder: true,
                            buttons:{ok:{label:'{LBL_OK}',className:'outer-blue-btn',callback: function() {
                                    location.href = "{SITE_URL}edit-company/"+select_company_id;
                            }}},
                            
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

        var header = document.getElementById("membership_plan_id");
        if(header === null){
            header = document.getElementById("membership_add_plan_id");

        }
        var sticky = header.offsetTop;

        window.onscroll = function() {
            if(header != ''){
            myFunction();

            }
        };


        function myFunction() {
          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
          } else {
            header.classList.remove("sticky");
          }
        }

</script>