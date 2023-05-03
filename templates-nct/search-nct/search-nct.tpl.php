<div class="inner-main">
    <div class="nav-menu in-menu">
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
    </div>
    <div class="srch-sec cf search-result-main">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="width-24"><?php echo $this->search_form; ?></div>
                        <?php if($_SESSION['user_id'] > 0) { ?>
                            <div class="width-52"></div>
                        <?php } else { ?>
                            <div class="width-76"></div>
                        <?php } ?>
                    <div class="hidden-sm hidden-xs"><?php echo $this->subscribed_membership_plan_details; ?></div>
                </div>
                <div class="col-sm-12 col-md-9">
                    <div class="srch-rgt-list">
                        <div class="search-middle">
                        <h5 class="result-title"><strong id="no_of_total_results"><?php echo $this->no_of_total_results; ?></strong> {LBL_RESULTS_S}</h5>
                        <ul id="applied_filters_container" class="tag"><?php echo $this->applied_filters; ?></ul>
                        <div class="clearfix"></div>
                        <div id="search_results_container" class="result-row"><?php echo $this->search_results; ?></div>
                        <div class="clearfix"></div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<div class="modal advc-filter-bx fade" id="advanced_filter_options_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" name="advanced_filter_options_form" id="advanced_filter_options_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel">{LBL_ADVANCED_FILTERS}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 form-group cf">
                            <div class="search-box-keyword">
                                <h5>{LBL_KEYWORD}</h5>
                                <input type="text" id="s_keyword" name="keyword" placeholder="{LBL_HEADER_SEARCH_PEOPLE_JOBS_COMPANIES_AND_MORE}" autocomplete="off" value="%KEYWORD%" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 %LOCATION_FILTER_HIDDEN% form-group cf">
                            %ADV_LOCATION_FILTER%
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-12 col-md-6 form-group cf %RELATIONSHIP_FILTER_HIDDEN% filter-box">%ADV_RELATIONSHIP_FILTER%</div>
                    <div class="col-sm-12 col-md-6 form-group cf %FERRY_PILOT_USER_FILTER_HIDDEN% filter-box">
                        %ADV_FERRY_PILOT_USER_FILTER%
                    </div>
                    </div>

                    <div class="row">
                       <div class="col-sm-6 col-md-3 filter-box %EMPLOYMENT_TYPE_FILTER_HIDDEN%">%ADV_EMPLOYMENT_TYPE_FILTER%</div>
                        
                        <div class="col-sm-6 col-md-3 filter-box %INDUSTRIES_FILTER_HIDDEN%">%ADV_INDUSTRIES_FILTER%</div>
                        
                        <div class="col-sm-6 col-md-3 filter-box %COMPANY_RATING_FILTER_HIDDEN%">%ADV_COMPANY_RATING_FILTER%</div>
                        
                        <div class="col-sm-6 col-md-3 filter-box %ADV_JOB_CATEGORIES_FILTER_HIDDEN%">%ADV_JOB_CATEGORIES_FILTER%</div>
                        
                       
                        <div class="col-sm-6 col-md-3 filter-box %USER_HOME_AIRPORT_FILTER_HIDDEN%">%ADV_USER_HOME_AIRPORT_FILTER%</div>

                        <div class="col-sm-6 col-md-3 filter-box %GROUP_TYPE_FILTER_HIDDEN%">%ADV_GROUP_TYPE_FILTER%</div>
                        
                        <div class="col-sm-6 col-md-3 filter-box %SORTINGS_FILTER_HIDDEN%">%ADV_SORTINGS_FILTER%</div>

                        <div class="col-sm-6 col-md-3 filter-box %NO_OF_FOLLOWERS_FILTER_HIDDEN%">%ADV_NO_OF_FOLLOWERS_FILTER%</div>
                    </div>
                </div>
                <div class="modal-footer btn-center">
                    <button type="submit" class="blue-btn" name="apply_advanced_filters" id="apply_advanced_filters">{LBL_APPLY_FILTER}</button>
                    <div class="space-mdl"></div>
                    <input type="reset" class="outer-red-btn" name="cancel" id="cancel" data-dismiss="modal" value="{LBL_CANCEL}" />
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var urlParam = {};
    urlParam['entity'] = '<?php echo filtering($_GET["entity"]); ?>';
    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('location')),{types: ['geocode']});
        autocomplete.addListener('place_changed', function() {fillInAddress(autocomplete, "");});
        adv_autocomplete = new google.maps.places.Autocomplete((document.getElementById('adv_location')),{types: ['geocode']});
        adv_autocomplete.addListener('place_changed', function() {fillInAddress(adv_autocomplete, "adv");});

        var header_input = document.getElementById('location');
        google.maps.event.addDomListener(header_input, 'keydown', function(e) { 
           if (e.keyCode == 13) { 
                e.preventDefault(); 
            }
        });
        var input = document.getElementById('adv_location');
        google.maps.event.addDomListener(input, 'keydown', function(e) { 
           if (e.keyCode == 13) { 
                e.preventDefault(); 
            }
        });


    }
    function fillInAddress(autocomplete, type) {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            //window.alert("{ALERT_AUTOCOMPLETE_RETURN_PLACE_CONTAINS_NO_GIOMETRY}");
            //return;
        } else {
            address1 = address2 = city1 = city2 = state = country = postal_code = '';
            formatted_address = place.formatted_address;
            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();
            var arrAddress = place.address_components;
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
            $("#adv_formatted_address").val(formatted_address);
            $("#adv_address1").val(address1);
            $("#adv_address2").val(address2);
            $("#adv_country").val(country);
            $("#adv_state").val(state);
            $("#adv_city1").val(city1);
            $("#adv_city2").val(city2);
            $("#adv_postal_code").val(postal_code);
            $("#adv_latitude").val(latitude);
            $("#adv_longitude").val(longitude);
            $("#location").val(formatted_address);
            $("#adv_location").val(formatted_address);
            if(type == "") {
                getSearchResults(1);
            }
        }
    }
    $(document).on("click", "#apply_advanced_filters", function(e) {
        e.preventDefault();
        var enteredKeyword = $("#s_keyword").val();
        $("#keyword").val(enteredKeyword);
        $("#advanced_filter_options_popup").modal("hide");
        getSearchResults(1);
    });
    $(document).on("click","#advance_filter_open",function(e){
        if(urlParam['entity'] == 'companies'){
            $("#advanced_filter_options_popup #sort_lt_text").text("{LBL_SEARCH_LOWEST_RATED_FIRST}");
            $("#advanced_filter_options_popup #sort_hl_text").text("{LBL_SEARCH_HIGHEST_RATED_FIRST}");
        }else if(urlParam['entity'] == 'jobs'){
            $("#advanced_filter_options_popup #sort_lt_text").text("{SEARCH_JOB_OLDEST}");
            $("#advanced_filter_options_popup #sort_hl_text").text("{SEARCH_JOB_RECENT}");
        }else{
            $("#advanced_filter_options_popup #sort_lt_text").text("{LBL_SEARCH_LOW_TO_HIGH}");
            $("#advanced_filter_options_popup #sort_hl_text").text("{LBL_SEARCH_HIGH_TO_LOW}");
        }
        var enteredKeyword = $("#keyword").val();
        $("#s_keyword").val(enteredKeyword);
    }); 
    $(document).on('click', ".send-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>addConnection",
            data: {
                user_id: user_id,
                action: 'addConnection'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    element.html("<i class='icon-unfollower'></i>{LBL_CANCEL_CONNECTION_REQUEST}");
                    element.removeClass("send-connection-request").addClass("cancel-connection-request reject-btn");
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on("click", ".accept-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>approveConnection",
            data: {
                user_id: user_id,
                action: 'approveConnection'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    element.html('<i class=" icon-connection-close"></i>{LBL_REMOVE_FROM_CONNECTION}');
                    $('.reject-connection-request').remove();
                    element.removeClass("accept-connection-request connect-btn").addClass("remove-from-connection reject-btn");
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on("click", ".reject-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>rejectConnection",
            data: {
                user_id: user_id,
                action: 'rejectConnection'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    element.html('<i class="icon-follower"></i>{LBL_CONNECT}');
                    $('.accept-connection-request').remove();
                    element.removeClass("reject-connection-request reject-btn").addClass("send-connection-request connect-btn");
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on("click", ".remove-from-connection", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        var bootBoxCallback = function(result) {
        if(result){
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>removeConnection",
                data: {
                    user_id: user_id,
                    action: 'removeConnection'
                },
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        toastr['success'](data.success);
                        element.html('<i class="icon-follower"></i>{LBL_CONNECT}');
                        element.removeClass("remove-from-connection reject-btn").addClass("send-connection-request connect-btn");
                    } else {
                        toastr['error'](data.success);
                    }
                }
            });
        }
        }            
        initBootBox("{ALERT_REMOVE_FROM_CONNECTION}", "{ALERT_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_THE_CONNECTION}", bootBoxCallback);    
    });
    $(document).on('click', ".cancel-connection-request", function() {
        element = $(this);
        user_id = element.data('value');
        closest_li = element.closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>removeConnection",
            data: {
                user_id: user_id,
                action: 'removeConnection'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.success);
                    element.html('<i class="icon-follower"></i>{LBL_CONNECT}');
                    element.removeClass("cancel-connection-request reject-btn").addClass("send-connection-request connect-btn");
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    function loadMoreRecords(url, element) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    element.find("ul.search-rilation li.load-more").remove();
                    element.find(".search-rilation").append(data.content);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }
    $(".search-filters").mCustomScrollbar({
        callbacks: {
            onTotalScroll: function() {
                element = $(this);
                url = element.find("ul.search-rilation li.load-more a").attr('href');
                if(url) {
                    loadMoreRecords(url, element);
                }
            },
            onTotalScrollOffset: 200
        }
    });
    $(document).on('click', "#ask_to_join", function() {
        var $this = $(this);
        element = $(this);
        group_id = element.data('value');
        var og_id = $this.closest('.operation-container').data('id');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>ask_to_join",
            data: {
                action: 'ask_to_join',
                group_id: group_id,
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.success);
                    element.closest('#join_leave_group_id').html(data.html);

                    //$("#join_leave_group_id").html(data.html);
                    //$this.closest('.operation-container').html(data.html);
                   // $('.con_'+og_id+' a').attr('data-value',group_id);
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });

    $(document).on('click', "#join_group", function() {
        element = $(this);
        group_id = element.data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>join_group",
            data: {
                action: 'join_group',
                group_id: group_id,
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    console.log(data);
                    toastr['success'](data.success);
                    element.closest('#join_leave_group_id').html(data.html);

                    //$("#join_leave_group_id").html(data.html);
                   // window.location.reload();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });
    $(document).on('click', "#leave_group", function() {
        element = $(this);
        group_id = element.data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>leave_group",
            data: {
                action: 'leave_group',
                group_id: group_id,
                accessibility: '%ACCESSIBILITY%'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.success);
                    element.closest('#join_leave_group_id').html(data.html);

                    //$("#join_leave_group_id").html(data.html);
                    window.location.reload();
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(document).on('click', "#withdraw_request", function() {
        element = $(this);
        group_id = element.data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>leave_group",
            data: {
                action: 'leave_group',
                group_id: group_id,
                accessibility: '%ACCESSIBILITY%'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success']("{WITHDRAW_REQUEST_SUCCESS}");
                    element.closest('#join_leave_group_id').html(data.html);

                    //$("#join_leave_group_id").html(data.html);
                    window.location.reload();
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(document).on("click","#follow_user",function(){
        var user_id = $(this).data('value');
        var getstatus=$(this).data('status');
        var status;
        if(getstatus=='' || getstatus=='uf'){
            status='f';
        }else{
            status='uf';
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>follow_user",
            data: {
                action: 'follow_user',
                user_id: user_id,
                status:status

            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.success);
                     window.location.reload();
 
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
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
                    $("#search_results_container ").find(".load-more-data").remove();
                    $("#search_results_container").append(data.content);
                   // $("#search_results_container").find(".no-results").remove();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });


    function loadMoreRecordfordata(url) {
        $.ajax({
            type: 'GET',
            url: url,
            data: $("#advanced_filter_options_form").serialize(),
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#search_results_container").find(".view-more-btn a").remove();
                    $("#search_results_container").append(data.content);

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



    window.onscroll = function() {myFunction()};
        var header = document.getElementById("membership_plan_id");
        if(header === null){
            header = document.getElementById("membership_add_plan_id");

        }
        var sticky = header.offsetTop;
        function myFunction() {
          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
          } else {
            header.classList.remove("sticky");
          }
        }


    $(document).on('click', '.follow_company', function() {
        var company_btn = $(this);
        company_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>followCompany",
            data: {company_id: company_id,action: 'followCompany'},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    $(".company_followers").html(data.follower_count);
                    company_btn.addClass('unfollow_company');
                    company_btn.html('Unfollow');
                    company_btn.removeClass('follow_company');
                     setTimeout(function(){window.location.reload();}, 1000);

                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on('click', '.unfollow_company', function() {
        var company_btn = $(this);
        company_id = $(this).data('value');
        var bootBoxCallback = function(result) {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>unfollowCompany",
                    data: {company_id: company_id,action: 'unfollowCompany'},
                    beforeSend: function() {addOverlay();},
                    complete: function() {removeOverlay();},
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            toastr['success'](data.success);
                            $(".company_followers").html(data.follower_count);
                            company_btn.addClass('follow_company');
                            company_btn.html('Follow');
                            company_btn.removeClass('unfollow_company');
                            setTimeout(function(){window.location.reload();}, 1000);

                            

                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }
        initBootBoxForUnfollowCompany("{LBL_COM_DET_UNFOLLOW_COMPANY}", "{LBL_COM_DET_ARE_YOU_SURE_WANT_UNFOLLOW_COMPANY}", bootBoxCallback);
    });
    function initBootBoxForUnfollowCompany(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons: {
                cancel: {label: '{LBL_COM_DET_CANCEL}',className: 'outer-blue-btn '},
                confirm: {label: '{LBL_COM_DET_YES}',className: 'blue-btn'}               
            },
            callback: callbackFn
        });
    }
    
</script>