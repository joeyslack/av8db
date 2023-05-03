<div class="inner-main">
    <div class="user-profile-sec cf">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div class="right-part-main">
                        <div class="fix-sidebar" data-spy="affix" data-offset-top="0" data-offset-bottom="30">
                          <div class="gen-wht-bx cf">
                             <div class="user-detail fade fadeIn">
                                <div class="profile-view-outer" >
                                    <img src="%COVER_IMG%" alt="img" class="banner_img_change" id="%CLASS_PIC_OTH%">
                                    <form enctype="multipart/form-data" action="{SITE_URL}update-profile-picture" method="post" name="update_profile_pic_form" id="update_profile_pic_form">
                                        <figure>
                                         <div id="profile_picture_container" class="user-pic profile-pic %CLASS_PIC_OTH%">
                                            
                                            %USER_PROFILE_PICTURE%
                                            <div class="profile-overlay"><?php echo $this->profile_picture_actions; ?></div>
                                            <div class="progressBar">
                                                <div class="bar"></div>
                                                <div class="percent">0%</div>
                                            </div>
                                            <?php echo $this->connection_level; ?>
                                            </div>
                                        </figure>
                                         <div id="update_user_details" class="edt-pro-form"></div>
                                        <div id="user_details_container">
                                                <h1>%USER_NAME_FULL% </h1>
                                                <p class="%HEADLINE_DISPLAY%"><a href="%JOB_TITLE_URL%"></a> <a href="%COMPANY_NAME_URL%"></a></p>
                                                <div class="edt-del-bx %HIDE_IF_NOT_LOGGED%">
                                                    <?php echo $this->connections_url; ?>
                                                    <div id="remove_from_connection_url"> <?php echo $this->remove_from_connection_url; ?></div>
                                                    <?php echo $this->user_actions; ?>
                                                    <?php echo $this->follow_actions; ?>
                                                </div>
                                                <ul class="view-box">
                                                    <li class="%HIDE_LI%"><a href="%URL_INDUSTRY%">
                                                            <small class="%HIDE_SMALL%">%INDUSTRY_NAME%</small></a>
                                                        <p class="%HIDE_P%">%FORMATTED_ADDRESS%</p>
                                                    </li>
                                                    <li class="view-cell">
                                                      <span><a href="%CONNECTIONS_URL%" title="%NO_OF_CONNECTIONS% {LBL_CONNECTIONS}" class="orange-text">%NO_OF_CONNECTIONS%</a></span>
                                                      <p>{LBL_CONNECTIONS}</p>
                                                    </li>
                                                </ul>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 in-fl-rgt">
                   %RIDHT_SIDEBAR%
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="mdl-pro-view cf">
                        <div class="gen-wht-bx fade fadeIn %VIEW_FULL_PROFILE_CLASS%" style="cursor: pointer;">
                            <br>  
                            <center>  
                            <strong class="view-full sub-title clearfix"> <a href="{SITE_URL}signin" title="{LBL_LOGIN_BUTTON_SIGNIN}" >{LBL_VIEW_FULL_PROFILE}</a></strong>
                            </center>
                            <br>
                        </div>
                        <div class="gen-wht-bx in-heading fade fadeIn">
                            <h2>{LBL_REFERRAL}</h2>
                            <div class="referrals-main">
                                <a href="javascript:void(0);" class="blue-btn invite-ref-btn" title="{LBL_INVITE_SOMEONE_FOR_REFERRALS}" id="inviteReferrals" data-toggle="modal" data-target="#invite_referrals">
                                    {LBL_INVITE_SOMEONE_FOR_REFERRALS}
                                </a>
                            </div>
                            <h3 class="received-review-head">
                                {LBL_RECEIVED_REVIEWS}
                            </h3>
                            <div class="list-review-ul list-review-ul2">
                                %RECEIVED_REFERRALS_REVIEWS%
                            </div>
                            <h3 class="received-review-head">
                                {LBL_REQUEST_RECEIVED_FOR_REFERRALS}
                            </h3>
                            %REQUEST_RECEIVED%
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
<div class="modal fade in modal-h" id="invite_referrals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg inviteUSer-class" role="document">
        <div class="modal-content">
            <form action="" name="advanced_filter_options_form" id="advanced_filter_options_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel">{LBL_MODAL_INVITE_SOMEONE_FOR_REFERRAL}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="srch-conn-bx col-md-12">
                            <div class="form-group cf">
                                <i class="icon-srch"></i>
                                <input type="text" id="searchForReferrals" name="searchForReferrals" placeholder="Search">
                            </div>
                        </div>
                    </div>
                    <div class="flex-row" id="people_send_referrals">
                    </div>
                </div>
                <div class="modal-footer btn-center">
                    <div class="space-mdl"></div>
                    <input type="reset" class="outer-red-btn" name="cancel" id="cancel" data-dismiss="modal" value="{LBL_MODAL_CANCEL_REFERRAL_BUTTON}">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="writeReferralReview" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{LBL_REFERRALS_WRITE_A_REVIEW}</h4>
      </div>
      <div class="modal-body referrals_review"></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("#searchForReferrals").keyup(function(){
        var keyword = $("#searchForReferrals").val();
        var user_id = '<?php echo $_SESSION['user_id']; ?>';
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>searchForReferrals",
            data: {
                keyword: keyword,
                user_id: user_id,
                action: 'searchForReferrals'
            },
            dataType: 'json',
            success: function(data) {
                $('#people_send_referrals').html(data);
            }
        });
    });
    $(document).on('click',"#send_request_for_referral",function(){
        var user_id = $(this).attr('data-userId');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>sendReferralsRequest",
            data: {
                user_id: user_id,
                action: 'sendReferralsRequest'
            },
            dataType: 'json',
            success: function(data) {
                var res = JSON.parse(data);
                if(res.status == "suc"){
                    toastr["success"](res.message);
                    window.location.href = '' + res.redirect_url + '';
                }else{
                    toastr["error"](res.message);
                    window.location.href = '' + res.redirect_url + '';
                }
            }
        });
    });
    $(document).on('click','.accept_write_referral_review',function(){
        let referral_id = $(this).attr('id');
        let ref_id = $(this).attr('data-referralsid');
        let sender_id = $(this).attr('data-senderid');
        
        if(ref_id > 0){
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>getReferralReviewModal",
                data: {
                    referral_id: referral_id,
                    ref_id     : ref_id,
                    sender_id  : sender_id,
                    action     : 'getReferralReviewModal'
                },
                dataType: 'json',
                success: function(data) {
                    $('.referrals_review').html(data);
                    $("#writeReferralReview").modal("show");
                }
            });
        }
    });
    $(document).on('click','.remove_refferal_request',function(){
        let remove_referral_id = $(this).attr('id');
        let ref_id = $(this).attr('data-referralsid');
        let sender_id = $(this).attr('data-senderid');
        
        var bootBoxCallback = function(result) {
            if (result) {
                if(ref_id > 0){
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo SITE_URL; ?>rejectReferralRequest",
                        data: {
                            referral_id: remove_referral_id,
                            ref_id     : ref_id,
                            sender_id  : sender_id,
                            action     : 'rejectReferralRequest'
                        },
                        dataType: 'json',
                        success: function(data) {
                            var res = JSON.parse(data);
                            if(res.status == "suc"){
                                toastr["success"](res.message);
                                window.location.href = '' + res.redirect_url + '';
                            }else{
                                toastr["error"](res.message);
                                window.location.href = '' + res.redirect_url + '';
                            }
                        }
                    });
                }
            }}
            initBootBox("{ALERT_REJECT_USER_REFERRAL_REQUEST}", "{ALERT_ARE_YOU_SURE_YOU_WANT_TO_REJECT_THIS_REFERRAL_REQUEST}", bootBoxCallback);
    });
    $(document).on('click','.approvePublish',function(){
        var app_referral_id = $(this).attr('id');
        var referral_id     = $(this).attr('data-referralId');
        var review_id     = $(this).attr('data-reviewid');
        if(referral_id > 0){
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL;?>approvepublishreferral",
                data: {
                    referral_id: referral_id,
                    review_id  : review_id,
                    action     : 'approvepublishreferral'
                },
                dataType: 'json',
                success: function(data) {
                    var res = JSON.parse(data);
                    if(res.status == "suc"){
                        toastr["success"](res.message);
                        window.location.href = '' + res.redirect_url + '';
                    }else{
                        toastr["error"](res.message);
                        window.location.href = '' + res.redirect_url + '';
                    }
                }
            });
        }
    });
    $(document).on('click',".askRevision",function(){
        var app_referral_id = $(this).attr('id');
        var referral_id     = $(this).attr('data-referralId');
        var review_id     = $(this).attr('data-reviewid');
        if(referral_id > 0){
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>resendReferralsRequest",
                data: {
                    referral_id: referral_id,
                    review_id  : review_id,
                    action: 'resendReferralsRequest'
                },
                dataType: 'json',
                success: function(data) {
                    var res = JSON.parse(data);
                    if(res.status == "suc"){
                        toastr["success"](res.message);
                        window.location.href = '' + res.redirect_url + '';
                    }else{
                        toastr["error"](res.message);
                        window.location.href = '' + res.redirect_url + '';
                    }
                }
            });
        }
    });
</script>