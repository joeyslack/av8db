<div class="right-part-main">
<div class="fix-sidebar" data-spy="affix" data-offset-top="0" data-offset-bottom="30">
  <div class="gen-wht-bx cf">
  <div class="profile-view-outer">
    <img src="%COVER_IMG%" alt="img" class="banner_img_change">

      <div class="edt-bx">
        <a href="%EDIT_PROFILE_URL%" title="{LBL_SUB_HEADER_EDIT_PROFILE}">
        <i class="icon-pencil"></i>
        </a>
      </div>
      <figure>
        <div class="profile-pic"><?php echo getImageURL("user_profile_picture", $_SESSION['user_id'], "th4"); ?>
        </div>
            <div class="pro-nm-addr">
                <h1>%USER_NAME_FULL%</h1>
     <!--               <p>%HEADLINE%</p> -->
            </div>
        </figure>
    <ul class="view-box">
        <li class="view-cell">
          <span class="no-of-visitors-container purple-text">%NO_OF_VISITORS%</span>
          <p >{LBL_PERSONS_VIEWED_YOUR_PROFILE_IN_PAST_DAY}</p>
          <a href="javascript:void(0);" title="%NO_OF_VISITORS%{LBL_VISITORSIN_LASTDAYS}">  </a>
        </li>
        <li class="view-cell">
          <span><a href="%CONNECTIONS_URL%" class="orange-text" title="">%NO_OF_CONNECTIONS%</a></span>
          <p>{LBL_CONNECTIONS}</p>
          <p> <a href="%ADD_CONNECTION_URL%" class="blue-color" title="{LBL_ADD_NEW_CONNECTION}">{LBL_ADD_NEW_CONNECTION}</a> </p>
        </li>
      </ul>
  </div>
</div>
  </div>
  
  </div>
<script type="text/javascript">
    $(document).on('click', ".close_job_suggestion", function() {
        closest_li = $(this).closest('li');
        closeJobSuggetion(closest_li);
    });
    $(document).on('click', ".close_company_suggestion", function() {
        closest_li = $(this).closest('li');
        closeCompanySuggetion(closest_li);
    });
    $(document).on('click', ".close_group_suggestion", function() {
        closest_li = $(this).closest('li');
        closeGroupSuggetion(closest_li);
    });
    $(document).on('click', ".close_people_you_know", function() {
        closest_li = $(this).closest('li');
        closest_li.fadeOut(500, function() {
            closest_li.remove();
        });
        if ($(".people_you_know_ul .people_you_know_li").length == 1) {
            $(".people_you_know_ul").html('<a href="<?php echo SITE_URL . "people-you-may-know" ?>" title="{LBL_VIEW_ALL}">{LBL_VIEW_ALL_SUGGESTIONS}</a>');
        }
    });
    function closeJobSuggetion(closest_li) {
        closest_li.fadeOut(500, function() {
            closest_li.remove();
        });
        if ($(".job_suggetion_ul li").length == 1) {
            $(".job_suggetion_ul").html('{LBL_NO_SUGGESTIONS}');
        }
    }
    function closeCompanySuggetion(closest_li) {
        closest_li.fadeOut(500, function() {
            closest_li.remove();
        });
        if ($(".company_suggetion_ul li").length == 1) {
            $(".company_suggetion_ul").html('{LBL_NO_MORE_SUGGESTION}');
        }
    }
    function closeGroupSuggetion(closest_li) {
        closest_li.fadeOut(500, function() {
            closest_li.remove();
        });
        if ($(".group_suggetion_ul li").length == 1) {
            $(".group_suggetion_ul").html('{LBL_NO_MORE_GROUP_SUGGESTION}');
        }
    }
    $(document).on('click', '#apply_job', function() {
            var job_btn = $(this);
            job_id = $(this).data('value');
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>saveJobApplication",
                data: {
                    job_id: job_id,
                    action: 'saveJobApplication'
                },
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    if (data.status == 'true') {
                        if(data.recommanded == 'y'){    
                            //toastr['success'](data.msg);
                            $('#apply_job').attr('id','remove_from_job_apply');
                            job_btn.html('Applied');
                            $(".no_of_applicants").html(data.no_of_applicants);
                        }else{
                            //window.location = data.url;
                            window.open(data.url, '_blank');
                        }
                    } else {
                        toastr['error'](data.msg);
                    }
                }
            });
        });
        $(document).on('click', '#remove_from_job_apply', function() {
            var job_btn = $(this);
            job_id = $(this).data('value');
            var bootBoxCallback = function(result) {
                if(result){
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo SITE_URL; ?>removeJobApplication",
                        data: {
                            job_id: job_id,
                            action: 'removeJobApplication'
                        },
                        beforeSend: function() {addOverlay();},
                        complete: function() {removeOverlay();},
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                //toastr['success'](data.msg);
                                job_btn.html('Apply');
                                $('#remove_from_job_apply').attr('id','apply_job');
                                $(".no_of_applicants").html(data.no_of_applicants);
                            } else {
                                toastr['error'](data.msg);
                            }
                        }
                    });
        }
        }            
        initBootBox("{ALERT_DELETE_APPLIED_JOB}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_THIS_JOB_FROM_APPLIED_JOB}", bootBoxCallback);
        });
    $(document).on('click', '#follow_company', function() {
        var company_btn = $(this);
        company_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>followCompany",
            data: {
                company_id: company_id,
                action: 'followCompany'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    //toastr['success'](data.msg);
                    $(".company_followers").html(data.follower_count + " followers");
                    company_btn.html('Unfollow');
                    $('#follow_company').attr('id','unfollow_company');
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on('click', '#unfollow_company', function() {
        var company_btn = $(this);
        company_id = $(this).data('value');
        var bootBoxCallback = function(result) {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>unfollowCompany",
                    data: {
                        company_id: company_id,
                        action: 'unfollowCompany'
                    },
                    beforeSend: function() {addOverlay();},
                    complete: function() {removeOverlay();},
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            //toastr['success'](data.success);
                            $(".company_followers").html(data.follower_count + " followers");
                            company_btn.html('Follow');
                            $('#unfollow_company').attr('id','follow_company');
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }            
        initBootBoxForUnfollowCompany("{LBL_UNFOLLOW_COMPANY}", "{ALERT_ARE_YOU_SURE_WANT_TO_UNFOLLOW_THIS_COMPANY}", bootBoxCallback);
    });
    function initBootBoxForUnfollowCompany(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons: {
                cancel: {
                    label: 'Cancel',
                    className: 'outer-blue-btn '
                },
                confirm: {
                    label: 'Yes',
                    className: 'blue-btn'
                }               
            },
            callback: callbackFn
        });
    }
    $(document).on('click', "#add_connection", function() {
        user_id = $(this).data('value');
        closest_li = $(this).closest('li');
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
                    //alert($(this).data('value'));
                    //toastr['success'](data.msg);
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });
                    if ($(".people_you_know_ul .people_you_know_li").length == 1) {
                        $(".people_you_know_ul").html('<a href="<?PHP echo SITE_URL . "people-you-may-know" ?>" title="View All">View all suggestions</a>');
                    }
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on('click', "#ask_to_join", function() {
        closest_li = $(this).closest('li');
        var group_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>ask_to_join",
            data: {
                action: 'ask_to_join',
                group_id: group_id
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    //toastr['success'](data.success);
                    closeGroupSuggetion(closest_li);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(document).on('click', "#join_group", function() {
        closest_li = $(this).closest('li');
        var group_id = $(this).data('value');
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
                    //toastr['success'](data.success);
                    closeGroupSuggetion(closest_li);
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });
</script>