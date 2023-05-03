<script src="{SITE_PLUGIN}raty/jquery.raty.js" type="text/javascript"></script>
<div class="inner-main">
    <div class="user-profile-sec cf">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div class="right-part-main">
                        <div class="fix-sidebar" data-spy="affix" data-offset-top="0" data-offset-bottom="30">
                            <div class="gen-wht-bx cf">
                                <div class="user-detail fade fadeIn">
                                    <div class="profile-view-outer">
                                        <img src="%COVER_IMG%" alt="{SITE_THEME_IMG}{USER_DEFAULT_AVATAR}" class="banner_img_change" id="%CLASS_PIC_OTH%">
                                        <form enctype="multipart/form-data" action="{SITE_URL}update-profile-picture" method="post" name="update_profile_pic_form" id="update_profile_pic_form">
                                            <div class="edt-bx">
                                                <a href="javascript:void(0);" class="%CLASS% " title="{UPDATE_COVER_PHOTO}" id="cover_picture"><i class="fa fa-camera"></i></a>
                                                <?php echo $this->actions; ?>
                                            </div>
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
                                                <div class="%HIDE_IF_NOT_LOGGED%three-icons">
                                                    <?php echo $this->connections_url; ?>
                                                    <div id="remove_from_connection_url"> <?php echo $this->remove_from_connection_url; ?></div>
                                                    <?php echo $this->user_actions; ?>
                                                    <?php echo $this->follow_actions; ?>
                                                    <a class="new-msg %SEND_INMAIL_CLASS%" title="%SEND_INMAIL_TITLE%" href="%SEND_INMAIL_URL%">%SEND_INMAIL_TEXT%</a>
                                                </div>
                                                <ul class="view-box">
                                                    <li class="%HIDE_LI%"><a href="javascript:void(0);">
                                                            <small class="%HIDE_SMALL%">%INDUSTRY_NAME%</small></a>
                                                        <p class="%HIDE_P%">%FORMATTED_ADDRESS%</p>
                                                    </li>
                                                    <li><a href="javascript:void(0);">
                                                        <p class="%HIDE_P%">%PERESONAL_DETAILS%</p>
                                                    </li>
                                                    <li class="view-cell">
                                                        <span><a href="%CONNECTIONS_URL%" title="%NO_OF_CONNECTIONS% {LBL_CONNECTIONS}" class="orange-text">%NO_OF_CONNECTIONS%</a></span>
                                                        <p>{LBL_CONNECTIONS}</p>
                                                        <p %HIDE_ACTION%> <a href="%ADD_CONNECTION_URL%" class="blue-color" title="{LBL_ADD_NEW_CONNECTION}">{LBL_ADD_NEW_CONNECTION}</a> </p>
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
                                <strong class="view-full sub-title clearfix"> <a href="{SITE_URL}signin" title="{LBL_LOGIN_BUTTON_SIGNIN}">{LBL_VIEW_FULL_PROFILE}</a></strong>
                            </center>
                            <br>
                        </div>
                        <div id="airport_main" class="gen-wht-bx in-heading fade fadeIn">
                            <h2>{LBL_EDIT_PROFILE_SELECT_HOME_AIRPORT}
                                <div class="add-exp-bx %ISCONTAINSAIRPORT%">
                                    <?php echo $this->add_airport_link; ?>
                                </div>
                            </h2>
                            <div class="airport-main">
                                <div id="add_airport_container" class="edit-airport-container"></div>
                                <div id="airport_container" class="tb-responsive-h exp-dtl cf">
                                    <table class="table %ISAIRPORTTABLE% tb-full-border">
                                        <thead>
                                            <tr>
                                                <th scope="col">{LBL_AIRPORT_NAME}</th>
                                                <th scope="col">{LBL_CITY_NAME}</th>
                                                <th scope="col">{LBL_COUNTRY_NAME}</th>
                                                <th scope="col" class="%HIDE_OPERATIONS_TO_OTHER%"></th>
                                            </tr>
                                        </thead>
                                        %AIRPORT%
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="licenses_endorsements_main" class="gen-wht-bx in-heading fade fadeIn">
                            <h2>{LBL_EDIT_PROFILE_LICENSES_ENDORSEMENT}
                                <div class="add-exp-bx">
                                    <?php echo $this->add_licenses_endorsement; ?>
                                </div>
                            </h2>
                            <div class="licenses-main">
                                <div id="add_license_container" class="edit-license-container"></div>
                                <div id="licenses_endorsements_container" class="tb-responsive-h exp-dtl cf">
                                    <div class="edit-licenses-container"></div>
                                    <table class="table %ISLICENSESTABLE% tb-full-border">
                                        <thead>
                                            <tr>
                                                <th scope="col">{LBL_TYPE}</th>
                                                <th scope="col">{LBL_VERIFICATION_STATUS}</th>
                                                <th scope="col">{LBL_TRAINING_INSTITUTION}</th>
                                                <th scope="col">{LBL_FLIGHT_TIME}</th>
                                                <th scope="col">{LBL_LAST_UPDATED}</th>
                                                <th scope="col" class="%HIDE_OPERATIONS_TO_OTHER%"></th>
                                            </tr>
                                        </thead>
                                        %LICENSES_ENDORSEMENTS%
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div id="educations_main" class="gen-wht-bx in-heading fade fadeIn %EDUCATION_CLASS% %EDUCATION_HIDE%">
                            <h2>{LBL_EDUCATION}
                                <div class="add-exp-bx">
                                    <?php echo $this->add_education_link; ?>
                                </div>
                            </h2>
                            <div class="education-main">
                                <div id="add_education_container" class="edit-education-container"></div>
                                <div id="educations_container">%EDUCATIONS%</div>
                            </div>
                        </div>
                        <div class="gen-wht-bx in-heading fade fadeIn %LANGUAGE_CLASS% %LANGUAGE_HIDE%">
                            <div class="languages-detail">
                                <h2 class="sub-title clearfix">{LBL_LANGUAGE}
                                    <div class="add-exp-bx">
                                        <?php echo $this->add_language_link; ?>
                                    </div>
                                </h2>
                                <div class="lang-pro-bx">
                                    <div id="add_language_container" class="edit-language-container"></div>
                                    <ul class="tag" id="languages_container">%LANGUAGES%</ul>
                                </div>
                            </div>
                        </div>
                        <div id="experiences_main" class="gen-wht-bx in-heading fade fadeIn %HIDE_REFERRAL_FOR_OWNER%">
                            <h2>{LBL_PROFILE_REFERRAL}
                            </h2>
                            <div class="developer-detail-main">
                                <div id="add_referral_container" class="edit-referral-container"></div>
                                <div id="referral_container">%REFERRAL_DETAILS%</div>
                            </div>
                        </div>
                    </div>
                    <div class="gen-wht-bx in-heading fade fadeIn %HIDE_OWNER_FERRY_PILOT_REVIEWS% %ALREADY_REVIEW_ADDED% %IS_FERRY_AND_ACCTIVE_SUBSCRIPTION_PLAN%">
                        <div class="languages-detail">
                            <h2 class="sub-title clearfix">{LBL_RATE_REVIEW_FERRY_PILOT}
                            </h2>
                            <div class="lang-pro-bx pt-0">
                                <div class="row">
                                    <form id="ferry_pilot_rating_form" method="post" action="">
                                        <div class="rate">
                                            <input type="radio" id="star1" name="rate" value="5" />
                                            <label for="star1" title="text">1 star</label>
                                            <input type="radio" id="star2" name="rate" value="4" />
                                            <label for="star2" title="text">2 stars</label>
                                            <input type="radio" id="star3" name="rate" value="3" />
                                            <label for="star3" title="text">3 stars</label>
                                            <input type="radio" id="star4" name="rate" value="2" />
                                            <label for="star4" title="text">4 stars</label>
                                            <input type="radio" id="star5" name="rate" value="1" />
                                            <label for="star5" title="text">5 stars</label>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div id="forError" class="comp-desc-in pt-0 hide"><label id="rate-error" class="error" for="rate"></label></div>
                                        <div class="comp-desc-in pt-0">
                                            <div class="">
                                                <textarea placeholder="Description" name="description" id="description"></textarea>
                                            </div>
                                            <div class="cf">
                                                <button type="submit" class="blue-btn" name="save_ferry_pilot_rating" id="save_ferry_pilot_rating">{LBL_COMPANY_DETAILS_RATE_REVIEW_SAVE} </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gen-wht-bx in-heading fade fadeIn %IS_FERRY_AND_ACCTIVE_SUBSCRIPTION_PLAN%">
                        <div class="languages-detail">
                                <h2 class="sub-title clearfix">{LBL_FERRY_PILOT_REVIEWS}</h2>
                        </div>
                        %FERRY_PILOT_RATE_REVIEW%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="footer-toggle">
    <a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<div class="modal fade modal_profile_pic" id="img_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body clearfix img_src">
                <img src="" alt="">
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal_cover_pic" id="cover_img_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body clearfix cover_img_src">
                <img src="" alt="">
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
                <h4 class="modal-title">{LBL_ADD_REVIEW}</h4>
            </div>
            <div class="modal-body product_content"></div>
        </div>
    </div>
</div>
<div class="modal advc-filter-bx fade in" id="inviteUSer_popup" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg inviteUSer-class" role="document">
        <div class="modal-content">
            <form action="" name="advanced_filter_options_form" id="advanced_filter_options_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel">{LBL_MODAL_ON_PLATFORM_INVITE_USER}</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="srch-conn-bx col-md-12">
                            <div class="form-group cf">
                                <i class="icon-srch"></i>
                                <input type="text" id="searchInviteUser" name="searchInviteUser" placeholder="Search">
                            </div>
                        </div>
                    </div>

                    <div class="flex-row" id="invite_users">

                    </div>
                </div>
                <div class="modal-footer btn-center">

                    <div class="space-mdl"></div>
                    <input type="reset" class="outer-red-btn" name="cancel" id="cancel" data-dismiss="modal" value="Cancel">
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal advc-filter-bx fade in" id="inviteUSer_popup2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md inviteUSer-class" role="document">
        <div class="modal-content">
            <form action="{SITE_URL}profile/" method="post" name="send_invitation_another_user_form" id="send_invitation_another_user_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
                    <h4 class="modal-title" id="myModalLabel">{LBL_MODAL_INVITE_USER_OFF_PLATFORM}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <input type="text" placeholder="Enter email" name="user_email" id="user_email">
                        </div>
                    </div>
                </div>
                <div class="modal-footer btn-center">
                    <button type="submit" class="blue-btn" name="send_invitation_off_platform" id="send_invitation_off_platform">{LBL_MODAL_INVITE_USER_OFF_PLATFORM_SEND_BUTTON}</button>
                    <div class="space-mdl"></div>
                    <input type="reset" class="outer-red-btn" name="cancel" id="cancel" data-dismiss="modal" value="Cancel">
                </div>
            </form>
            <!--    </form> -->
        </div>
    </div>
</div>
</div>
<div class="modal fade in modal-h" id="invite_referrals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  is-width-set" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
                <h4 class="modal-title" id="myModalLabel">{LBL_MODAL_INVITE_REFERRAL}</h4>
            </div>
            <div class="modal-body">
                <i class="icon-srch"></i>
                <input type="text" id="searchForReferrals" name="searchForReferrals" placeholder="{LBL_SEARCH}">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click','.requestFerryPilotFlag',function(){
        var review_id = $(this).attr('id');
        var receiverId = $(this).attr('data-receiverId');
        if(review_id > 0){
            var bootBoxCallback = function(result) {
            if (result) {
                $.ajax({
                   type: "POST",
                   url: "<?php echo SITE_URL; ?>reportFerryPilotReviews",
                   dataType:'json',
                   data:{
                        'action':'reportFerryPilotReviews',
                        'receiverId':receiverId,
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
    $(document).on('click', '.requestOnPlatform', function() {
        $('#inviteUSer_popup').modal('show');
    });
    $(document).on('click', '.requestOffPlatform', function() {
        $('#inviteUSer_popup2').modal('show');
    });
    $(document).on('show.bs.modal', '#inviteUSer_popup', function() {
        $.ajax({
            type: "POST",
            url: "<?php echo SITE_URL; ?>getLicenseList",
            dataType: 'json',
            data: {
                action: 'getLicenseList'
            },
            success: function(response) {
                $("#selected_license_id").append(response.content);
            }
        });
    });
    $("#selected_license_id").change(function() {
        var id = $("#selected_license_id option:selected").val();
    });
    $(document).on("click", ".edit_review", function() {
        var edit_id = $(this).attr('id');
        var senderId = $(this).attr('data-senderId');
        if (senderId > 0) {
            $.ajax({
                type: "POST",
                url: "<?php echo SITE_URL; ?>getFerryPilotReviews",
                dataType: 'json',
                data: {
                    action: 'checkReview',
                    'senderId': senderId,
                    'user_id': edit_id
                },
                success: function(response) {
                    $(".modal-title").html("Edit Review");
                    $('.product_content').html(response.content);
                    $("#GiveReview").modal("show");
                    var r = $('#rating').val();
                    $("#org_rating").raty({
                        score: r
                    });
                }
            });
        }
    });
    $('#inviteUSer_popup2').on('hidden.bs.modal', function() {
        var form_var = $('#send_invitation_another_user_form');
        form_var.validate().resetForm();
        form_var.find('.error').removeClass('error');
    });
    $("#send_invitation_another_user_form").validate({
        ignore: [],
        rules: {
            user_email: {
                required: true,
                checkEmail: true
            }
        },
        messages: {
            user_email: {
                required: "{ERR_MODAL_INVITE_USER_SEND_INVITATION_EMAIL}",
                checkEmail: "{ERR_MODAL_INVITATION_VALID_EMAIL}"
            }
        },
        highlight: function(element) {
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            if ($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        }
    });
    $("#send_invitation_another_user_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status == 'suc') {
                toastr["success"](obj.message);
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.message);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    $(document).on('click', '.verify_license_endorsement', function() {
        var license_id = $(this).attr('data-licenseId');
        var user_id = '<?php echo $_GET['user_id']; ?>';
        if (license_id != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo SITE_URL; ?>verifyLicense",
                dataType: 'json',
                data: {
                    action: 'verifyLicense',
                    license_id: license_id,
                    user_id: user_id
                },
                success: function(response) {
                    res = response['status'];
                    if (res == 'suc') {
                        toastr["success"](response['message']);
                        window.location.href = '' + response['redirect_url'] + '';
                    } else {
                        toastr["error"](response['message']);
                        window.location.href = '' + response['redirect_url'] + '';
                    }
                }
            });
        }
    });
    $(document).on('click', "#invite_user_license_endorsement", function() {
        var user_id = $(this).attr('data-userId');
        var selected_license = $('#hidden_licenses_name').val();
        //var selected_license = $("#selected_license_id option:selected").val();
        if (user_id > 0 && selected_license > 0) {
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>inviteUserOnPlatform",
                data: {
                    user_id: user_id,
                    selected_license: selected_license,
                    action: 'inviteUserOnPlatform'
                },
                dataType: 'json',
                success: function(data) {
                    var res = JSON.parse(data);
                    if (res.status == "suc") {
                        window.location.href = '' + res.redirect_url + '';
                        toastr["success"](res.message);

                    } else {

                        toastr["error"](res.message);

                    }
                }
            });
        }else{
            toastr["error"]("{ERROR_MESSAGE_PLEASE_SELECT_LICENSES_ENDORSEMENT_FOR_VERIFICATION}");
        }
    });
</script>
<script type="text/javascript">
    $(document).on('click', '#inviteReferrals', function() {
        $('#invite_referrals').modal('show');
    });
    $.validator.addMethod("companyNm", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\'\s]*$/.test(value);
    },"{ERROR_MESSAGE_FOR_FERRY_PILOT_VALID_RATE_REVIEWS}");
    $("#ferry_pilot_rating_form").validate({
        ignore: [],
        rules: {
            description: {
                required: true,
                companyNm: true
            },
            rate: {required: true}
        },
        messages: {
            description: {
                required: "{ERROR_MESSAGE_FOR_COMPANY_REVIEW_DESCRIPTION}"
            },
            rate: {required: "{ERR_FERRY_PILOT_NOT_SELECTED_RATING}"}
        },
        highlight: function(element) {
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            $('#forError').removeClass('hide');
            if($(element).attr("type") == "radio") {
                $('#forError label').text(error);
            }
            if ($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        }
    });
    $("#ferry_pilot_rating_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"](obj.success);
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.success);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
</script>

<script type="text/javascript">
    $('#avatar-modal').on('hidden.bs.modal', function() {
        $('.avatar-wrapper img').cropper('destroy');
        $('.avatar-wrapper').empty();
    });
    $('#avatar-modal').on('shown.bs.modal', function() {
        $('.avatar-wrapper img').cropper({
            aspectRatio: 1,
            strict: true,
            minCropBoxWidth: 130,
            minCropBoxHeight: 130,
            viewMode: 1,
            crop: function(e) {
                var json = [
                    '{"x":' + e.x,
                    '"y":' + e.y,
                    '"height":' + e.height,
                    '"width":' + e.width,
                    '"rotate":' + e.rotate + '}'
                ].join();
                $('.avatar-data').val(json);
            }
        });
    });
    $(document).on('change', '#avatarInput', function(e) {
        var _this = $(this);
        var value = _this.val();
        var allowedFiles = ["jpg", "jpeg", "png"];
        var extension = value.split('.').pop().toLowerCase();
        if (jQuery.inArray(extension, allowedFiles) !== -1) {
            var url = (typeof FileReader == "undefined") ? webkitURL.createObjectURL(e.target.files[0]) : URL.createObjectURL(e.target.files[0]);
        } else {
            toastr['error']("{ERROR_YOU_CAN_ONLY_UPDLOAD_JPG_PNG}");
            $(".loading").hide();
            $("#profile_picture").val("");

        }
    });
    $(document).on('click', '#btnCrop', function(e) {
        var _this = $(this);
        var avatarForm = $('.avatar-form');
        var frmCont = $('form#update_profile_pic_form');
        var url = avatarForm.attr('action');
        var data = new FormData(frmCont[0]);
        data.append('avatar_src', $('#avatar_src').val());
        data.append('avatar_data', $('#avatar_data').val());
        $.ajax(url, {
            type: 'post',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                addOverlay();
                _this.attr('disabled', true);
            },
            success: function(data) {
                if (data.state == 200) {
                    $('#profile_picture_container').html(data.updated_profile_pic_src);
                    $('#avatar-modal').modal('hide');
                } else {
                    toastr['info'](data.message);
                }
            },
            complete: function() {
                _this.attr('disabled', false);
                removeOverlay();
                $('.loading').fadeOut();
            }
        });
        e.stopImmediatePropagation();
    });
    $(document).on("click", "#removeUserImage", function() {
        $("#profile_picture_container").html("");
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}removeProfileImage",
            data: {
                action: 'removeImage'
            },
            dataType: 'json',
            success: function(data) {
                $(".user-img").html(data.image_medium);
                $('#profile_picture_container').html(data.image_medium + '<div class="profile-overlay"><a href="javascript:void(0);" title="Edit" id="change_profile_picture"><div class="btn-file active"><i class="fa fa-pencil"></i><input type="file" class="places_image" accept="image/x-png,image/jpeg" name="profile_picture" id="profile_picture" tabindex="-1"></div></a></div>');

                toastr[data.operation_status](data.message);

            }
        });
    });
    /*
     * Experiences
     */
    function handleAddEditExperience(type, experience_id, experience_box_element) {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getExperienceForm",
            data: {
                action: 'getExperienceForm',
                experience_id: experience_id
            },
            beforeSend: function() {
                $("#add_experience_container").html("");
                $(".developer-detail").each(function() {
                    var view_experience_details = $(this).find(".view-experience-details");
                    var edit_experience_container = $(this).find(".edit-experience-container");

                    view_experience_details.fadeIn(1500, function() {
                        edit_experience_container.html("").fadeOut(1000);
                    });

                });
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if (type == 'add') {
                        $("#add_experience_container").html(data.experience_form).fadeIn(1500, function() {
                            $("#add_experience").hide();
                            var expLength = $("#experiences_container").find(".developer-detail").length;
                            //alert(expLength);
                            if (expLength == 0) {
                                $("#experiences_container").hide();
                            }
                            height = $("#add_experience_container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    } else {
                        experience_box_element.find(".edit-experience-container").html(data.experience_form).fadeIn(1500, function() {
                            experience_box_element.find(".view-experience-details").hide();
                            height = experience_box_element.find(".edit-experience-container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    }
                    $("#company_name").focus();
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on("click", "#add_experience", function() {
        handleAddEditExperience('add', '', '');
    });
    $(document).on("click", ".edit-experience-icon", function() {
        experience_box_element = $(this).parents(".developer-detail");
        experience_id = experience_box_element.data("experience-id");
        handleAddEditExperience('edit', experience_id, experience_box_element);
    });
    $(document).on("click", ".delete-experience-icon", function() {
        experience_box_element = $(this).parents(".developer-detail");
        experience_id = experience_box_element.data("experience-id");
        var bootBoxCallback = function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteExperience",
                    data: {
                        experience_id: experience_id,
                        action: 'deleteExperience'
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
                            toastr['success'](data.success);
                            experience_box_element.removeClass('developer-detail');
                            experience_box_element.hide();
                            if (data.msg != '') {
                                $("#experiences_container").html('<div class="no-data text-center">' + data.msg + '</div>');
                            }
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }
        initBootBox("{ALERT_DELETE_USER_EXPERIENCE}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELTE_THIS_EXPERIENCE}", bootBoxCallback);
    });
    $(document).on("click", ".delete-education-icon", function() {
        education_box_element = $(this).parents(".education-detail");
        education_id = education_box_element.data("education-id");
        var bootBoxCallback = function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteEducation",
                    data: {
                        education_id: education_id,
                        action: 'deleteEducation'
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
                            toastr['success'](data.success);
                            education_box_element.hide();
                            if (data.msg != '') {
                                $("#educations_container").html('<div class="no-data text-center">' + data.msg + '</div>');
                            }
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }
        initBootBox("{ALERT_DELTE_USER_EDUCATION}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELTE_THIS_EDUCATION}", bootBoxCallback);
    });
    $(document).on("click", ".delete-licenses-icon", function() {
        education_box_element = $(this).parents(".licenses-detail");
        licenses_id = education_box_element.data("licenses-id");
        var bootBoxCallback = function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteLicense",
                    data: {
                        licenses_id: licenses_id,
                        action: 'deleteLicense'
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
                            toastr['success'](data.success);
                            education_box_element.hide();
                            if (data.msg != '') {
                                $("#licenses_endorsements_container").html('<div class="no-data text-center">' + data.msg + '</div>');
                            }
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }
        initBootBox("{ALERT_DELTE_USER_LICENSES}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELTE_THIS_LICENSE}", bootBoxCallback);
    });
    $(document).on("click", ".edit-user-details-icon", function() {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getUserDetailForm",
            data: {
                action: 'getUserDetailForm',
            },
            beforeSend: function() {
                $("#user_details_container").fadeOut();
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#update_user_details").html(data.user_detail_form).fadeIn(1500, function() {
                        height = $("#update_user_details").offset().top - 10;
                        //scrolWithAnimation(height);
                    });
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    });
    /*
     * Education 
     */
    function handleAddEditEducation(type, education_id, education_box_element) {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getEducationForm",
            data: {
                action: 'getEducationForm',
                education_id: education_id
            },
            beforeSend: function() {
                $("#add_education_container").html("");
                $(".education-detail").each(function() {
                    var view_education_details = $(this).find(".view-education-details");
                    var edit_education_container = $(this).find(".edit-education-container");
                    view_education_details.fadeIn(1500, function() {
                        edit_education_container.html("").fadeOut(1000);
                    });
                });
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if (type == 'add') {
                        $("#add_education_container").html(data.education_form).fadeIn(1500, function() {
                            $("#add_education").hide();
                            var expLength = $("#educations_container").find(".developer-detail").length;
                            if (expLength == 0) {
                                $("#educations_container").hide();
                            }
                            height = $("#add_education_container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    } else {
                        education_box_element.find(".edit-education-container").html(data.education_form).fadeIn(1500, function() {
                            education_box_element.find(".view-education-details").hide();
                            height = education_box_element.find(".edit-education-container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    }
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on("click", "#add_education", function() {
        handleAddEditEducation('add', '', '');
    });
    $(document).on("click", ".edit-education-icon", function() {
        education_box_element = $(this).parents(".education-detail");
        education_id = education_box_element.data("education-id");
        handleAddEditEducation('edit', education_id, education_box_element);
    });
    $(document).on("click", "#add_licenses_endorsement", function() {
        handleAddLicensesEndorsement('add', '', '');
    });
    $(document).on("click", ".edit-licenses-icon", function() {
        education_box_element = $(this).parents(".licenses-detail");
        licenses_id = education_box_element.data("licenses-id");
        handleAddLicensesEndorsement('edit', licenses_id, education_box_element);
    });
    function handleAddLicensesEndorsement(type, licenses_id, education_box_element) {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getLicensesEndorsementForm",
            data: {
                action: 'getLicensesEndorsementForm',
                licenses_id: licenses_id
            },
            beforeSend: function() {
                $("#add_license_container").html("");
                $(".licenses-detail").each(function() {
                    var view_education_details = $(this).find(".view-license-details");
                    var edit_education_container = $(this).find(".edit-licenses-container");
                    view_education_details.fadeIn(1500, function() {
                        edit_education_container.html("").fadeOut(1000);
                    });
                });
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if (type == 'add') {
                        $("#add_license_container").html(data.licenses_form).fadeIn(1500, function() {
                            $("#add_licenses_endorsement").hide();
                            var expLength = $("#licenses_endorsements_container").find(".developer-detail").length;
                            if (expLength == 0) {
                                $("#licenses_endorsements_container").hide();
                            }
                            height = $("#add_license_container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    } else {
                        $(".edit-licenses-container").html(data.licenses_form).fadeIn(1500, function() {
                            education_box_element.find(".view-license-details").hide();
                            height = $(".edit-licenses-container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    }
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on("click", "#add_airport", function() {
        handleAddHomeAirport('add', '', '');
    });
    $(document).on("click", ".edit-airports-icon", function() {
        education_box_element = $(this).parents(".airport-detail");
        airport_id = education_box_element.data("airport-id");
        handleAddHomeAirport('edit', airport_id, education_box_element);
    });
    function handleAddHomeAirport(type, airport_id, education_box_element) {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getHomeAirportForm",
            data: {
                action: 'getHomeAirportForm',
                airport_id: airport_id
            },
            beforeSend: function() {
                $("#add_airport_container").html("");
                $(".airport-detail").each(function() {
                    var view_education_details = $(this).find(".view-airport-details");
                    var edit_education_container = $(this).find(".edit-airport-container");
                    view_education_details.fadeIn(1500, function() {
                        edit_education_container.html("").fadeOut(1000);
                    });
                });
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if (type == 'add') {
                        $("#add_airport_container").html(data.airport_form).fadeIn(1500, function() {
                            $("#add_airport").hide();
                            var expLength = $("#airport_container").find(".developer-detail").length;
                            if (expLength == 0) {
                                $("#airport_container").hide();
                            }
                            height = $("#add_airport_container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    } else {
                        $(".edit-airport-container").html(data.airport_form).fadeIn(1500, function() {
                            $(".view-airport-details").hide();
                            height = $(".edit-airport-container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    }
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on("click", ".delete-airports-icon", function() {
        education_box_element = $(this).parents(".airport-detail");
        airport_id = education_box_element.data("airport-id");
        var bootBoxCallback = function(result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteAirport",
                    data: {
                        airport_id: airport_id,
                        action: 'deleteAirport'
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
                            toastr['success'](data.success);
                            $('#add_airport').parent().removeClass('hide');
                            education_box_element.hide();
                            if (data.msg != '') {
                                $("#airport_container").html('<div class="no-data text-center">' + data.msg + '</div>');
                            }
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }
        initBootBox("{ALERT_DELTE_USER_HOME_AIRPORT}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELTE_THIS_HOME_AIRPORT}", bootBoxCallback);
    });

    function handleAddEditSkills() {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getSkillForm",
            data: {
                action: 'getSkillForm'
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
                    $("#add_skill_container").html(data.skill_form).fadeIn(1500, function() {
                        // $("#add_skills").hide();
                        var langLength = $("#skills_container").find("li").length;
                        if (langLength == 0) {
                            $("#skills_container").hide();
                        }
                        height = $("#add_skill_container").offset().top - 70;
                        scrolWithAnimation(height);
                    });
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on("click", "#add_skills", function() {
        handleAddEditSkills();
    });

    function handleAddEditAirports() {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getAirportForm",
            data: {
                action: 'getAirportForm'
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
                    $("#add_airport_container").html(data.airport_form).fadeIn(1500, function() {
                        // $("#add_skills").hide();
                        var langLength = $("#airport_container").find("li").length;
                        if (langLength == 0) {
                            $("#airport_container").hide();
                        }
                        height = $("#add_airport_container").offset().top - 70;
                        scrolWithAnimation(height);
                    });
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on("click", "#add_languages", function() {
        handleAddEditLanguages();
    });

    function handleAddEditLanguages() {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getLanguageForm",
            data: {
                action: 'getLanguageForm'
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
                    $("#add_language_container").html(data.language_form).fadeIn(1500, function() {
                        $("#add_languages").hide();
                        var langLength = $("#languages_container").find("li").length;
                        if (langLength == 0) {
                            $("#languages_container").hide();
                        }
                        height = $("#add_language_container").offset().top - 70;
                        scrolWithAnimation(height);
                    });
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on('click', ".remove_language", function() {
        var parents_li = $(this).parents('li');
        var language_id = $(this).data('language-id');
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}removeLanguage",
            data: {
                language_id: language_id,
                action: 'removeLanguage'
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
                    toastr['success'](data.success);
                    parents_li.fadeOut(1500);
                    if (data.msg != '') {
                        $("#languages_container").html('<div class="no-data text-center">' + data.msg + '</div>');
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(document).on('click', ".remove_skill", function() {
        var parents_li = $(this).parents('li');
        var skill_id = $(this).data('skill-id');
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}removeSkill",
            data: {
                skill_id: skill_id,
                action: 'removeSkill'
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
                    toastr['success'](data.success);
                    parents_li.fadeOut(1500);
                    if (data.msg != '') {
                        $("#skills_container").html('<div class="no-data text-center">' + data.msg + '</div>');
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    /*$(document).on("focus", "#change_profile_picture, #removeUserImage", function (e) {$(this).closest(".profile-overlay").css("opacity", 1);});*/
    $("#change_profile_picture").focus().keyup(function(e) {
        if (e.keyCode === 13) {
            $("#profile_picture").click();
        }
    });
    $(document).on("focusout", "#change_profile_picture, #removeUserImage", function(e) {
        $(this).closest(".profile-overlay").css("opacity", "");
    });
    $(document).on("focus", ".edit-user-details-icon,.edit-experience-icon,.delete-experience-icon,.edit-education-icon,.delete-education-icon,.edit-licenses-icon,.delete-licenses-icon", function(e) {
        $(this).css("opacity", 1);
    });
    $(document).on("focusout", ".edit-user-details-icon,.edit-experience-icon, .delete-experience-icon,.edit-education-icon, .delete-education-icon,.edit-licenses-icon,.delete-licenses-icon", function(e) {
        $(this).css("opacity", "");
    });
</script>

<!-- Image crop model end-->
<script type="text/javascript">
    $(document).on("click", "#close_popup", function(e) {
        $("#Edit_Profile1").hide();
    });
    var img_incr = -1;

    function showdata() {
        var formnew = document.getElementById("avtar_form");
        var formData = new FormData(formnew);
        var pathname = window.location.pathname.split('/');
        var mod = pathname['4'];
        var which_types = $("#hidden_image_id").html();
        $("#which_types").val(which_types);
        if (which_types == 'images' || which_types == 'header_slider' || which_types == 'activity_image' || which_types == 'slider_home') {
            var url_send = 'crop.php';
        }

        var url_send = "<?php echo SITE_URL; ?>modules-nct/profile-nct/crop.php";
        $(window).scrollTop(0);
        /*$(".avatar-wrapper").append("<img class='loading' src='<?php echo SITE_THEME_IMG; ?>/ajax-loader-transparent.gif' style='margin-left:300px; margin-top:100px;'/>");*/
        var id = $("#id").val();
        var user_id = '<?php echo $_SESSION['user_id']?>';

        formData.append('id', id);
        formData.append('user_id', user_id);
        jQuery.ajax({
            url: url_send,
            type: 'post',
            dataType: 'json',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            enctype: 'multipart/form-data',
            mimeType: 'multipart/form-data',
            cache: false,
            beforeSend: function() {
                addOverlay();
            },

            success: function(data) {                
                removeOverlay();

                $("#Edit_Profile1").hide();

                var site_url = "<?php echo SITE_URL; ?>";
                var dir_url = "<?php echo DIR_URL; ?>";

                var str = data.result;
                var final_img_url = str.replace(dir_url, site_url);
                // $.ajax({
                //     type: 'POST',
                //     url: "<?php echo SITE_URL; ?>uploadProfile",
                //     data: {
                //         action: 'upload_image',
                //         user_id: user_id,
                //         which_types: which_types,
                //         file_name:data.filename,
                //         crop_data:data.crop_data,
                //         main_url:data.result,
                //         status: status
                //     },
                //     complete: function() {},
                //     dataType: 'json',
                //     success: function(data) {
                //         console.log(data);
                //         // if (data.status) {
                //         //     toastr['success'](data.success);
                //         //     window.location.reload();

                //         // } else {
                //         //     toastr['error'](data.error);
                //         // }
                //     }
                // });
                if (which_types == 'images') {
                    if(data.status){
                        toastr['success'](data.success);
                        $('#tmp_img').val(data.filename);
                        $("#company_logo_img").attr("src", final_img_url);
                        $(".user-img").html('<img src="' + data.updated_profile_pic_src + '"  />');
                        $('#profile_picture_container').html('<img src="'+data.updated_profile_pic_src + '"><div class="profile-overlay"><a href="javascript:void(0);" title="Edit" id="change_profile_picture"><div class="btn-file active"><i class="fa fa-pencil"></i><input type="file" class="places_image" accept="image/x-png,image/jpeg" name="profile_picture" id="profile_picture" tabindex="-1"></div></a><a href="javascript:void(0);" id="removeUserImage" title="Remove">  <i class="fa fa-close active"></i></a></div>');
                    }else{
                        toastr['error'](data.error);
                    }
                } else {
                    // location.reload();
                    if (data.status) {
                        $(".banner_img_change").attr("src", data.updated_profile_pic_src);
                        //$(".banner_img_change").attr("src", data.result);
                        toastr['success']("{UPDATE_COVER_PHOTO_MSG}");
                    }else{
                        window.location.href = site_url;
                        toastr['error']("{LBL_LOGIN_TO_CONTINUE}");
                    }
                }
                $(".close").click();
                $(".loading").hide();
            }

        });
    }
    $(document).on("click", "#follow_user", function() {
        var user_id = $(this).data('value');
        var getstatus = $(this).data('status');
        var status;
        if (getstatus == '' || getstatus == 'uf') {
            status = 'f';
        } else {
            status = 'uf';
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>follow_user",
            data: {
                action: 'follow_user',
                user_id: user_id,
                status: status

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
                    toastr['success'](data.success);
                    window.location.reload();

                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(document).ready(function() {
        if ($("#profile_picture_container").find("span").length) {
            // $("#removeUserImage").hide();
        }
        $('.gen-owl-carousel').owlCarousel({
            items: 1,
            margin: 10,
            nav: true,
            onInitialized: data_hide,
        });

        function data_hide(event) {
            var totalItems = $('.gen-owl-carousel').find('.owl-item').length;
            if (totalItems <= 1) {
                $('.gen-owl-carousel').find(".owl-controls").attr("class", "hidden");

            }

        }
    });
    $(document).on('click', '.otheruser_picture_container', function() {

        var src_img = $(".otheruser_picture_container").find('img').attr('src');
        var src_img_new = src_img.replace("th4", "th5");
        $(".img_src").find('img').attr("src", src_img_new);
        $("#img_popup").modal('show');

    });
    $(document).on('click', '#otheruser_picture_container', function() {
        var src_img = $("#otheruser_picture_container").attr('src');
        var src_img_new = src_img.replace("th1", "th2");
        $(".cover_img_src").find('img').attr("src", src_img_new);
        $("#cover_img_popup").modal('show');

    });
</script>
<script type="text/javascript">
    $("#searchInviteUser").keyup(function() {
        var keyword = $("#searchInviteUser").val();
        var user_id = '<?php echo $_SESSION['user_id']; ?>';

        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>searchInviteUser",
            data: {
                keyword: keyword,
                user_id: user_id,
                action: 'searchInviteUser'
            },
            dataType: 'json',
            success: function(data) {
                $('#invite_users').html(data);
            }
        });
    });
</script>