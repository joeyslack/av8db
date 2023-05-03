<!-- <div class="fix-sidebar" data-spy="affix" data-offset-top="300" data-offset-bottom="200"> -->
<div class="gen-wht-bx in-heading fade fadeIn search-mobile">
    <h3>{LBL_SEARCH}
        <?php if($_SESSION['user_id'] > 0) { ?>
        <div class="advance-filer"><a href="javascript:void(0);" id="advance_filter_open" title="{LBL_ADVANCED}" data-toggle="modal" data-target="#advanced_filter_options_popup">{LBL_ADVANCED}</a></div>
    <?php } ?>
    </h3>
    
    <div class="search-left hidden-sm hidden-xs">
    
        <form action="" name="search_form" id="search_form">
            <div class="search-box">
                <ul class="search-tab">
                    <li><a href="javascript:void(0);" class="current" title="{LBL_SEARCH}"></a></li>
                </ul>
            </div>            
            <!-- <div class="search-box">
                <ul id="entity_selection_ul" class="search-names">
                    <li><a href="javascript:void(0);" title="{LBL_HEADER_PEOPLE}" data-entity="users" class="%USERS_LI_ACTIVE_CLASS% entity-selection">{LBL_HEADER_PEOPLE}</a></li>
                    <?php if($_SESSION['user_id'] > 0) { ?>
                        <li><a href="javascript:void(0);" title="{LBL_DB_JOBS}" data-entity="jobs" class="%JOBS_LI_ACTIVE_CLASS% entity-selection">{LBL_DB_JOBS}</a></li>
                    <?php } ?>
                    <li><a href="javascript:void(0);" title="{LBL_HEADER_COMPANY}" data-entity="companies" class="%COMPANY_LI_ACTIVE_CLASS% entity-selection">{LBL_HEADER_COMPANY}</a></li>
                    <?php if($_SESSION['user_id'] > 0) { ?>
                        <li><a href="javascript:void(0);" title="{LBL_HEADER_GROUP}" data-entity="groups" class="%GROUP_LI_ACTIVE_CLASS% entity-selection">{LBL_HEADER_GROUP}</a></li>
                    <?php } ?>
                </ul>
            </div> -->
            <div class="srch-inner-bx">
            %RELATIONSHIP_FILTER%
            %LOCATION_FILTER%
            %SORTINGS_FILTER%
            %COMPANY_SIZES_FILTER%
            %INDUSTRIES_FILTER%
            %EMPLOYMENT_TYPE_FILTER%
            <!-- %COMPANY_RATING_FILTER% -->
           <!--  %SORTING_FILTER% -->
            </div>
        </form>        
    </div>
</div>
<!-- <div class="gen-wht-bx in-heading fade fadeIn search-mobile">
    <h3>SORT</h3>
     %SORTING_FILTER_HIDDEN%
</div> -->
<!-- </div> -->
<script type="text/javascript">
    var urlParam = {};
    urlParam['entity'] = '<?php echo filtering($_GET["entity"]); ?>';
    function getCheckedCheckBoxValues(checkboxName) {
        var values = [];
        $.each($("#advanced_filter_options_form input[name='" + checkboxName + "']:checked"), function () {
            values.push($(this).val());
        });
        return values;
    }    
    function getSearchResults(currentPage) {
        var entity = urlParam['entity'];
        var keyword = $("#keyword").val();
        
        if(keyword != ''){
            keyword = '&keyword=' + keyword;
        }else{
            keyword = '';
        }
        $.ajax({
            type: 'GET',
            url: SITE_URL + "ajax/" + entity,
            data: $("#advanced_filter_options_form").serialize() + "&currentPage=" + currentPage + keyword,
            dataType: 'json',
            async: false,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            success: function(data) {
                $("#search_results_container").html(data.content);
                $("#pagination_container").html(data.pagination);
                $("#no_of_total_results").html(data.total_records);
                $("#applied_filters_container").html(data.applied_filters);
                if (currentPage > 1) {
                    urlParam['currentPage'] = currentPage;
                } else {
                    delete urlParam['currentPage'];
                }
                var keyword = $("#s_keyword").val();
                if(keyword != "") {
                    urlParam['keyword'] = keyword;
                } else {
                    delete urlParam['keyword'];
                }
                
                // var sorting_value = $('#sortings_lt').val();
                // // console.log(sorting_value);
                // if(sorting_value != "") {
                //     urlParam['sorting'] = sorting_value;
                // } else {
                //     delete urlParam['sorting'];
                // }

                switch (urlParam['entity']) {
                    case 'users':
                        $("#search_selected_entity").attr("class", "fa fa-user");
                        $("#selected_entity_container").attr("data-entity", "users");
                        urlParam['relationship'] = getCheckedCheckBoxValues("relationship[]");
                       
                        urlParam['sorting'] = getCheckedCheckBoxValues("sorting[]");
                       
                        if($("#location").val() != "") {
                            urlParam['location'] = $("#location").val();
                        } else {
                            delete urlParam['location'];
                        }
                        urlParam['industries'] = getCheckedCheckBoxValues("industries[]");
                        urlParam['ratings'] = getCheckedCheckBoxValues("ratings[]");
                        urlParam['homeairport'] = getCheckedCheckBoxValues("homeairport[]");
                        urlParam['company'] = getCheckedCheckBoxValues("company[]");
                        urlParam['groups'] = getCheckedCheckBoxValues("groups[]");
                        
                        $("#relationship_filters_container").removeClass("hidden");
                        $("#adv_relationship_filters_container").parents(".filter-box").removeClass("hidden");

                        $("#relationship_sorting_container").removeClass("hidden");
                        $("#adv_relationship_sorting_container").parents(".filter-box").removeClass("hidden");

                        $("#employment_type_filters_container").addClass("hidden");
                        $("#adv_employment_type_filters_container").parents(".filter-box").addClass("hidden");
                        $("#location_filters_container").removeClass("hidden");
                        $("#adv_location_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#industry_filters_container").addClass("hidden");
                        $("#adv_industry_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#company_size_filters_container").addClass("hidden");
                        $("#adv_company_size_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_current_company_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_group_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_job_category_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_no_of_followers_filters_container").parents(".filter-box").addClass("hidden");
                        $("#company_rating_filter_container").addClass("hidden");
                        $("#adv_company_rating_filter_container").parents(".filter-box").removeClass("hidden");

                        $("#home_airport_filters_container").addClass("hidden");
                        $("#adv_home_airport_filters_container").parents(".filter-box").removeClass("hidden");
                        break;
                    case 'jobs':
                        urlParam['employment_type'] = getCheckedCheckBoxValues("employment_type[]");
                        if($("#location").val() != "") {
                            urlParam['location'] = $("#location").val();
                        } else {
                            delete urlParam['location'];
                        }
                        urlParam['industries'] = getCheckedCheckBoxValues("industries[]");
                        urlParam['homeairport'] = getCheckedCheckBoxValues("homeairport[]");
                        urlParam['company'] = getCheckedCheckBoxValues("company[]");
                        urlParam['job_category'] = getCheckedCheckBoxValues("job_category[]");
                        $("#search_selected_entity").attr("class", "fa fa-briefcase");
                        $("#selected_entity_container").attr("data-entity", "jobs");
                        $("#relationship_filters_container").addClass("hidden");
                        $("#adv_relationship_filters_container").parents(".filter-box").addClass("hidden");
                        $("#employment_type_filters_container").removeClass("hidden");
                        $("#adv_employment_type_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#company_size_filters_container").addClass("hidden");
                        $("#industry_filters_container").addClass("hidden");
                        $("#adv_company_size_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_relationship_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_current_company_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_industry_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_group_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_job_category_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_no_of_followers_filters_container").parents(".filter-box").addClass("hidden");

                        $("#company_rating_filter_container").addClass("hidden");
                        $("#adv_company_rating_filter_container").parents(".filter-box").removeClass("hidden");

                        $("#home_airport_filters_container").addClass("hidden");
                        $("#adv_home_airport_filters_container").parents(".filter-box").removeClass("hidden");
                        break;
                    case 'companies':
                        $("#search_selected_entity").attr("class", "fa fa-building");
                        $("#selected_entity_container").attr("data-entity", "companies");
                        
                        if($("#location").val() != "") {
                            urlParam['location'] = $("#location").val();
                        } else {
                            delete urlParam['location'];
                        }

                        if($("#sortings_lt").val() != "") {
                            urlParam['sorting'] = $("#sortings_lt").val();
                        } else {
                            delete urlParam['sorting'];
                        }

                        if($("#max_no_of_followers").val() != "0") {
                            urlParam['min_no_of_followers'] = $("#min_no_of_followers").val();
                            urlParam['max_no_of_followers'] = $("#max_no_of_followers").val();
                        } else {
                            delete urlParam['min_no_of_followers'];
                            delete urlParam['max_no_of_followers'];    
                        }
                        urlParam['company_sizes'] = getCheckedCheckBoxValues("company_sizes[]");
                        urlParam['industries'] = getCheckedCheckBoxValues("industries[]");                        
                        urlParam['homeairport'] = getCheckedCheckBoxValues("homeairport[]");                        
                        urlParam['ratings'] = getCheckedCheckBoxValues("ratings[]");                        
                        $("#relationship_filters_container").addClass("hidden");
                        $("#adv_relationship_filters_container").parents(".filter-box").addClass("hidden");
                        $("#employment_type_filters_container").addClass("hidden");
                        $("#adv_employment_type_filters_container").parents(".filter-box").addClass("hidden");
                        $("#industry_filters_container").addClass("hidden");
                        $("#adv_industry_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_current_company_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_group_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_job_category_filters_container").parents(".filter-box").addClass("hidden");
                        $("#location_filters_container").removeClass("hidden");
                        $("#adv_location_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#company_size_filters_container").removeClass("hidden");
                        $("#adv_company_size_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_no_of_followers_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#company_rating_filter_container").addClass("hidden");
                        $("#adv_company_rating_filter_container").parents(".filter-box").removeClass("hidden");

                        $("#home_airport_filters_container").addClass("hidden");
                        $("#adv_home_airport_filters_container").parents(".filter-box").removeClass("hidden");

                        $("#sortings_filters_container").removeClass("hidden");
                        $("#adv_sortings_filters_container").parents(".filter-box").removeClass("hidden");
                        break;
                    case 'groups':
                        $("#search_selected_entity").attr("class", "fa fa-users");
                        $("#selected_entity_container").attr("data-entity", "groups");
                        urlParam['industries'] = getCheckedCheckBoxValues("industries[]");
                        urlParam['homeairport'] = getCheckedCheckBoxValues("homeairport[]");
                        urlParam['ratings'] = getCheckedCheckBoxValues("ratings[]");
                        $("#relationship_filters_container").addClass("hidden");
                        $("#adv_relationship_filters_container").parents(".filter-box").addClass("hidden");
                        $("#employment_type_filters_container").addClass("hidden");
                        $("#adv_employment_type_filters_container").parents(".filter-box").addClass("hidden");
                        $("#location_filters_container").addClass("hidden");
                        $("#adv_location_filters_container").parents(".filter-box").addClass("hidden");
                        $("#company_size_filters_container").addClass("hidden");
                        $("#adv_company_size_filters_container").parents(".filter-box").addClass("hidden");
                        $("#industry_filters_container").removeClass("hidden");
                        $("#adv_industry_filters_container").parents(".filter-box").removeClass("hidden");
                        $("#adv_current_company_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_group_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_job_category_filters_container").parents(".filter-box").addClass("hidden");
                        $("#adv_no_of_followers_filters_container").parents(".filter-box").addClass("hidden");
                        $("#company_rating_filter_container").addClass("hidden");
                        $("#adv_company_rating_filter_container").parents(".filter-box").removeClass("hidden");

                        $("#home_airport_filters_container").addClass("hidden");
                        $("#adv_home_airport_filters_container").parents(".filter-box").removeClass("hidden");
                        break;
                }
                console.log(urlParam);
                var newurlParam = jQuery.extend({}, urlParam);
                console.log(newurlParam);
                delete newurlParam.entity;
                var newParam = decodeURIComponent($.param(newurlParam));
                if (newParam != "") {
                    window.history.pushState("", "Title", urlParam['entity'] + "?" + newParam);
                } else {
                    window.history.pushState("", "Title", urlParam['entity']);
                }
                height = $(".search-result-main").offset().top;
                scrolWithAnimation(height);
            }
        });
    }

    $(document).on("change", ".chkbx-sortings-filter", function() {
        checkBoxChanged($(this));
    });
    $(document).on("click", ".entity-selection", function() {
        resetForm();
        var current_entity_li = $(this);
        var entity = current_entity_li.data("entity");
        $("#entity_selection_ul li").each(function() {
            $(this).find("a").removeClass("active");
        });
        current_entity_li.addClass("active");
        urlParam.entity = entity;
        /*alert(urlParam.entity);*/
        getSearchResults(1);
    });
    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        getSearchResults(page);
    });
    function checkBoxChanged(e) {
        var elementId = e.attr("id");
    //    console.log(elementId);
        if (elementId.indexOf("adv_") !== -1) {
            var elementId = elementId.replace("adv_", "");
        } else {
            var elementId = "adv_" + elementId;
        }
        
        if (e.prop('checked') == true){
            $("#" + elementId).prop('checked', true);
        } else {
            $("#" + elementId).prop('checked', false);
        }        
        if (elementId.indexOf("adv_") !== -1) {
            getSearchResults(1);
        }        
    }    
    $(document).on("change", ".chkbx-relationship-filter", function() {
        checkBoxChanged($(this));
    });
    $(document).on("change", ".chkbx-company-size-filter", function() {
        checkBoxChanged($(this));
    });
    $(document).on("change", ".chkbx-industry-filter", function() {
        checkBoxChanged($(this));
    });
    $(document).on("change", ".chkbx-ratings-filter", function() {
        checkBoxChanged($(this));
    });
    $(document).on("change", ".chkbx-airport-filter", function() {
        checkBoxChanged($(this));
    });
    $(document).on("change", ".company-size", function() {
        checkBoxChanged($(this));
    });
    $(document).on("change", ".company-size", function() {
        checkBoxChanged($(this));
    });
    $(document).on("click", ".remove-single-filter", function() {
        var elementId = $(this).data("id");
        $("#" + elementId).prop('checked', false);
        if (elementId.indexOf("adv_") !== -1) {
            // Advanced checked
            var elementId = elementId.replace("adv_", "");
        } else {
            var elementId = "adv_" + elementId;
        }
        $("#" + elementId).prop('checked', false);
        getSearchResults(1);
    });
    $(document).on("click", ".remove-location-filter", function() {
        $("#formatted_address").val("");
        $("#address1").val("");
        $("#address2").val("");
        $("#country").val("");
        $("#state").val("");
        $("#city1").val("");
        $("#city2").val("");
        $("#postal_code").val("");
        $("#latitude").val("");
        $("#longitude").val("");
        $("#adv_formatted_address").val("");
        $("#adv_address1").val("");
        $("#adv_address2").val("");
        $("#adv_country").val("");
        $("#adv_state").val("");
        $("#adv_city1").val("");
        $("#adv_city2").val("");
        $("#adv_postal_code").val("");
        $("#adv_latitude").val("");
        $("#adv_longitude").val("");
        $("#location").val("");
        $("#adv_location").val("");
        getSearchResults(1);
    });
    $(document).on("click", ".remove-no-of-followers-filter", function() {        
        var slider = $("#no_of_followers_range").data("ionRangeSlider");
        slider.reset();
        getSearchResults(1);
    });
    $(document).on("click", "#reset_all_filters", function() {
        resetForm();
        getSearchResults(1);
        $("#keyword").val("");
        $("#s_keyword").val("");
    });
    function resetForm() {
        $.each($("input[type='checkbox']:checked"), function () {
            var elementId = $(this).attr("id");
            $("#" + elementId).prop('checked', false);
        });        
        //$('#advanced_filter_options_form')[0].reset();
        $("#search_form").find("input[type=text], textarea, input[type=hidden]").val("");
       // $("#keyword").val("");
       // $("#s_keyword").val("");
        $("#advanced_filter_options_form").find("input[type=text], textarea, input[type=hidden]").val("");
        var slider = $("#no_of_followers_range").data("ionRangeSlider");
        if(slider){
            slider.reset();    
        }
    }
    $(document).on('click', '.job_save', function() {
        var job_btn = $(this);
        job_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>saveJob",
            data: {
                job_id: job_id,
                action: 'saveJob'
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
                    job_btn.addClass('remove_from_job_save');
                    job_btn.html('{LBL_SAVED}');
                    job_btn.removeClass('job_save');
                } else {
                    toastr['error'](data.msg);
                }
            }
        });   
    });
    $(document).on('click', '.remove_from_job_save', function() {
        var job_btn = $(this);
        job_id = $(this).data('value');
        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>removeSavedJob",
                    data: {
                        job_id: job_id,
                        action: 'removeSavedJob'
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
                            job_btn.addClass('job_save');

                            job_btn.html('{LBL_SAVE}');
                            job_btn.removeClass('remove_from_job_save');


                        } else {
                            toastr['error'](data.msg);
                        }
                    }
                });
            }
        } 
        initBootBox("{ALERT_DELETE_SAVED_JOB}","{ALERT_ARE_YOU_SURE}", bootBoxCallback);
    });
</script>