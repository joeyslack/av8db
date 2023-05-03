<script type="text/javascript"  src="//maps.googleapis.com/maps/api/js?v=3.28&sensor=false&libraries=places&language=en&key={GOOGLE_MAPS_API_KEY}"></script>

<script src="{SITE_PLUGIN}raty/jquery.raty.js" type="text/javascript"></script>
<div class="inner-main">
    <div class="company-dtl-sec cf">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <div class="right-dtl-outer fade fadeIn">
                        <div class="gen-wht-bx admin-block in-heading cf %HIDE_ADMIN_CONTENT%">
                        <?php echo $this->company_admin; ?>
                        </div>
                        <div class="looking-jobs orange-code text-center cf %JOB_POST_HIDE%">
                               <h4>{LBL_COM_DET_LOOKING_FOR_TALENT}</h4>
                                <a data-target="#createjobs" data-toggle="modal" title="{LBL_COM_DET_POST_A_JOB}" class="outer-blue-btn">{LBL_COM_DET_POST_A_JOB}</a>
                        </div>
                        <?php echo $this->subscribed_membership_plan_details; ?>

                    </div>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                    <div class="nav-menu in-menu">
                    <ul id="submenu" class="detail-menu %SHOW_TABS%">
                        <li><a href="javascript:void(0);" class="switch_tab %HOME_PAGE_ACTIVE%  home" title="{LBL_COM_DET_HOME}" data-type="" data-endpoint="">{LBL_COM_DET_HOME}</a></li>
                         <li><a href="javascript:void(0);" class="switch_tab %JOBS_CONTAIER_ACTIVE%  company_jobs" title="{LBL_COM_DET_JOBS}" data-type="company-jobs" data-endpoint="company-jobs">{LBL_COM_DET_JOBS}</a></li>
                        <li><a href="javascript:void(0);" class="switch_tab %FOLLOWERS_CONTAIER_ACTIVE% company_followers" title="{LBL_COM_DET_FOLLOWERS_CAPITAL}" data-type="company-followers" data-endpoint="company-followers">{LBL_COM_DET_FOLLOWERS_CAPITAL}</a></li>
                        <li><a href="javascript:void(0);" class="switch_tab %NOTIFICATION_CONTAIER_ACTIVE% notifications" title="{LBL_COM_DET_NOTIFICATIONS}" data-type="company-notifications" data-endpoint="company-notifications">{LBL_COM_DET_NOTIFICATIONS}</a></li>
                    </ul>
                    </div>
                        <div id="left_content">
                            <div class="load-feed"><?php echo $this->home_container; ?></div>
                                          <?php echo $this->followers_container; ?>
                            <?php echo $this->jobs_container; ?>
                            </div>
                </div>
            </div>
        </div>
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
                    <select name="company_name_id" id="company_name_id" class="selectpicker show-tick">
                        <option value="">{LBL_COMPANY_NAME}</option>
                        %COMPANY_NAME_OPTIONS%
                    </select>
                </div>
                <div class="col-sm-6 col-md-6 form-group">
                    <label>{LBL_CATEGORY} <sup>*</sup></label>
                    <select name="category_id" id="category_id" class="selectpicker show-tick">
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
                        %COMPANY_LOCATION_OPTION%
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
                    <button type="button" class="outer-red-btn" data-dismiss="modal">{LBL_COM_DET_CLOSE}</button>
                </div>
            </div>

                <div class="form-group text-center"></div>
            </form>
      </div>
      
    </div>
  </div>
</div>

<div class="modal fade" id="GiveReview" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Review</h4>
      </div>
      <div class="modal-body product_content"></div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    $(document).on('click','.requestFlag',function(){
        var review_id = $(this).attr('id');
        var company_id = $(this).attr('data-companyid');
        if(review_id > 0){
            var bootBoxCallback = function(result) {
            if (result) {
                $.ajax({
                   type: "POST",
                   url: "<?php echo SITE_URL; ?>reportCompanyReviews",
                   dataType:'json',
                   data:{
                        'action':'reportCompanyReviews',
                        'company_id':company_id,
                        'review_id':review_id},
                   success: function(response)
                   {    
                    if (response.status == 'suc') {
                        toastr["success"](response.message);
                        window.location.href = '' + response.redirect_url + '';
                    }else{
                        toastr["error"](response.message);
                        window.location.href = '' + response.redirect_url + '';
                    }
                   }
                });
            }
        }
        initBootBox_flag("{ALERT_REPORT_COMPANY_REVIEW}", "{ALERT_ARE_YOU_SURE_YOU_WISH_TO_FLAG_THIS_COMPANY_REVIEW}", bootBoxCallback);
      }  
    });
    $(document).on("click",".edit_review",function(){
      var edit_id  = $(this).attr('id');
      var company_id  = $(this).attr('data-companyId');
      console.log(company_id);
      if(company_id>0){
        $.ajax({
           type: "POST",
           url: "<?php echo SITE_URL; ?>getUserAddedReviews",
           dataType:'json',
           data:{action:'checkReview','company_id':company_id,'user_id':edit_id},
           success: function(response)
           {    
              $(".modal-title").html("Edit Review");
              console.log(response.content);
              $('.product_content').html(response.content);
              $("#GiveReview").modal("show");
              var r = $('#rating').val();
              $("#org_rating").raty({
                score:r
              });
           }
        });
      }      
    });
    $(document).ready(function() {
        var showChar = 500;
        var ellipsestext = "...";
        var moretext = "{LBL_COM_DET_VIEW_MORE}";
        var lesstext = "{LBL_COM_DET_VIEW_LESS}";
        $('.more').each(function() {
            var content = $(this).html();
            if (content.length > showChar) {
                var c = content.substr(0, showChar);
                var h = content.substr(showChar - 1, content.length - showChar);
                var html = c + '<span class="moreelipses">' + ellipsestext + '</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                $(this).html(html);
            }
        });
        $(".morelink").click(function() {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });
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
                    $(".company_followers").html(data.follower_count + " followers");
                    company_btn.addClass('unfollow_company');
                    company_btn.html('Unfollow');
                    company_btn.removeClass('follow_company');
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
                            $(".company_followers").html(data.follower_count + " {LBL_COM_DET_FOLLOWERS}");
                            company_btn.addClass('follow_company');
                            company_btn.html('Follow');
                            company_btn.removeClass('unfollow_company');
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
    initmCustomFollowerContainer();
    initmCustomJobContainer();
    $(document).on("click", ".switch_tab", function() {
        if (!$(this).hasClass("active")) {
            if ($(this).hasClass("company_followers")) {
                var url = "<?php echo SITE_URL; ?>getFollowerContent/company/%ENCRYPTED_COMPANY_ID%";
                getContent(url, true, $(this));
            } else if ($(this).hasClass("company_jobs")) {
                var url = "<?php echo SITE_URL; ?>getJobContent/company/%ENCRYPTED_COMPANY_ID%";
                getContent(url, true, $(this));
            }else if ($(this).hasClass("notifications")) {
                var url = "<?php echo SITE_URL; ?>getNotificationContent/company/%ENCRYPTED_COMPANY_ID%";
                getContent(url, true, $(this));
            } else {
                var url = "<?php echo SITE_URL; ?>getCompanyActivities/company/%ENCRYPTED_COMPANY_ID%";
                getContent(url, true, $(this));
                
                
                


            }
        } else {
            toastr['error']("{ERROR_COM_DET_YOU_ARE_ON_SAME_PAGE_TYR_TO_VIEW}");
        }
    });
    function getContent(url, tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");
                        if (current_element.hasClass("active")) {
                        }
                    });
                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }
                $("#left_content").html(data);
                initmCustomFollowerContainer();
                initmCustomJobContainer();
            }
        });
    }
    function initmCustomJobContainer() {
        $("#jobs_list_container").mCustomScrollbar({
            callbacks: {
                onTotalScroll: function() {
                    url = $("#all-jobs-list").find("li.load-more a").attr('href');
                    if(url) {
                        loadCompanyJobs(url, false, "a", false, '');
                    }
                },
                onTotalScrollOffset: 200
            }
        });
    }
    function initmCustomFollowerContainer() {
        $("#followers_list_container").mCustomScrollbar({
            callbacks: {
                onTotalScroll: function() {
                    url = $("#all-followers-list").find("li.load-more a").attr('href');
                    if(url) {
                        loadFollowers(url, false, "a", false, '');
                    }
                },
                onTotalScrollOffset: 200
            }
        });
    }
    function loadCompanyJobs(url, showLoader, appendORReplace,tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {if(showLoader) {addOverlay();}},
            complete: function() {if(showLoader) {removeOverlay();}},
            dataType: 'json',
            success: function(data) {
                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");
                        if (current_element.hasClass("active")) {
                        }
                    });
                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }
                if (data.status) {
                    if("r" == appendORReplace) {
                        $("#all-jobs-list").html(data.jobs);
                    } else {
                        $("#all-jobs-list").find("li.load-more").remove();
                        $("#all-jobs-list").append(data.jobs);
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }
    function loadFollowers(url, showLoader, appendORReplace,tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {if(showLoader) {addOverlay();}},
            complete: function() {if(showLoader) {removeOverlay();}},
            dataType: 'json',
            success: function(data) {
                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");
                        if (current_element.hasClass("active")) {
                        }
                    });
                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }
                if (data.status) {
                    if("r" == appendORReplace) {
                        $("#all-followers-list").html(data.followers);
                    } else {
                        $("#all-followers-list").find("li.load-more").remove();
                        $("#all-followers-list").append(data.followers);
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }
    $(document).on('click', '#share_news_feed', function() {
        company_id = $(this).data('value');
        var bootBoxCallback = function(result) {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>shareCompanyNewsFeed",
                    data: {company_id: company_id,action: 'shareNewsFeed'},
                    beforeSend: function() {addOverlay();},
                    complete: function() {removeOverlay();},
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            toastr['success'](data.msg);
                        } else {
                            toastr['error'](data.msg);
                        }
                    }
                });
            }
        }
        initBootBoxForSharing("{ALERT_COM_DET_SHARE_COMPANY}", "{ALERT_COM_DET_ARE_YOU_SURE_YOU_WANT_TO_SHARE_THIS_COMPANY_ON_FEED}", bootBoxCallback);
    });
    function initBootBoxForSharing(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons: {
                cancel: {label: '{LBL_COM_DET_CANCEL}',className: 'btn blue-btn cancel-btn '},
                confirm: {label: '{LBL_COM_DET_SHARE}',className: 'btn blue-btn'}               
            },
            callback: callbackFn
        });
    }
    $(document).on('click', "#remove_company_follower", function() {
        closest_li = $(this).closest('li');
        var company_id = $(this).data('company-id');
        var user_id = $(this).data('user-id');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>remove_company_follower",
            data: {action: 'remove_company_follower',company_id: company_id,user_id: user_id},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.success);
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });

    function loadMoreRecords(url) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $(".view-more-btn a").remove();
                    $(".load-feed").append(data.content);
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

                loadMoreRecords(url);
            }
            
        }
    }


    
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
                        bootbox.alert({
                            title: 'Alert',
                            message: 'Company details are incomplete',
                            reorder: true,
                            buttons:{ok:{label:'OK',className:'btn blue-btn cancel-btn '}},
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
    $('#job_location').on('change', function(){
        $('#job_location').valid(); 

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
            // job_location: {
            //     required: true
            // },
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
            // job_location: {
            //     required: lang.ERROR_ENTER_JOB_LOCATION
            // },
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
            //window.alert(lang.ALERT_AUTOCOMPLETE_RETURN_PLACE_CONTAINS_NO_GIOMETRY);
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

</script>