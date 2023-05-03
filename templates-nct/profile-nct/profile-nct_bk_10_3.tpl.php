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
                                                    <!-- <p>%HEADLINE%</p> -->
                                                    <p class="%HEADLINE_DISPLAY%"><a href="%JOB_TITLE_URL%">%JOB_TITLE%</a> {AT} <a href="%COMPANY_NAME_URL%">%COMPANY_NAME%</a></p>
                                                    <div class="%HIDE_IF_NOT_LOGGED%three-icons">
                                                        <?php echo $this->connections_url; ?>
                                                        <div id="remove_from_connection_url"> <?php echo $this->remove_from_connection_url; ?></div>
                                                        <?php echo $this->user_actions; ?>
                                                        <?php echo $this->follow_actions; ?>
                                                        <a class="new-msg %SEND_INMAIL_CLASS%" title="%SEND_INMAIL_TITLE%" href="%SEND_INMAIL_URL%">%SEND_INMAIL_TEXT%</a>
                                                    </div>
                                                      <ul class="view-box">
                                                        <li class="%HIDE_LI%"><a href="%URL_INDUSTRY%">
                                                        <small class="%HIDE_SMALL%">%INDUSTRY_NAME%</small></a>
                                                        <p class="%HIDE_P%">%FORMATTED_ADDRESS%</p>
                                                        </li>
                                                        <!-- <li class="view-cell">
                                                            %PERESONAL_DETAILS%
                                                        </li>
                                                        <li class="view-cell">
                                                            <label>Date of Birth :</label> %USER_DOB%
                                                        </li>
                                                        <li class="view-cell">
                                                            <label>Gender :</label> %GENDER%
                                                        </li> -->
                                                        <li class="view-cell">
                                                          <span><a href="%CONNECTIONS_URL%" title="%NO_OF_CONNECTIONS% {LBL_CONNECTIONS}" class="orange-text">%NO_OF_CONNECTIONS%</a></span>
                                                          <p>{LBL_CONNECTIONS}</p>
                                                          <p %HIDE_ACTION%> <a href="%ADD_CONNECTION_URL%" class="blue-color" title="{LBL_ADD_NEW_CONNECTION}">{LBL_ADD_NEW_CONNECTION}</a> </p>
                                                          <p> <a data-toggle="modal" data-target="#invite_friend" class="blue-color">Invite Friend</a> </p>
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
                        <div class="fade fadeIn %NO_DATA_HIDE%" >
                            <br>  
                            <center>  
                            <h1 class="view-full sub-title clearfix">{LBL_NO_DATA_FOUND}</h1>
                            <br>
                            <img src="{SITE_THEME_IMG}no_data.png" >
                            </center>

                        </div>
                        <div class="gen-wht-bx in-heading fade fadeIn">
                            <h2>Referrals</h2>
                            <div class="referrals-main">
                                <a href="javascript:void(0);" class="blue-btn invite-ref-btn" title="Invite someone for referrals" id="inviteReferrals">
                                    Invite someone for referrals
                                </a>
                            </div>
                            <h3 class="received-review-head">
                                Received Reviews
                            </h3>
                            <div class="list-review-ul list-review-ul2">
                                <div class="list-review-h">
                                    <p class="des-review">
                                        test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test 
                                    </p>
                                    <div class="two-btns">
                                        <a href="#" class="outer-blue-btn" title="Approve &amp; Publish">Approve &amp; Publish</a>
                                        <a href="#" class="outer-blue-btn" title="Ask for revision">Ask for revision</a>
                                    </div>
                                </div>
                                <div class="list-review-h">
                                    <p class="des-review">
                                        test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test 
                                    </p>
                                    <div class="two-btns">
                                        <a href="#" class="outer-blue-btn" title="Approve &amp; Publish">Approve &amp; Publish</a>
                                        <a href="#" class="outer-blue-btn" title="Ask for revision">Ask for revision</a>
                                    </div>
                                </div>
                            </div>
                            <h3 class="received-review-head">
                                Request Received for Referrals
                            </h3>
                            <div class="received-referrals">
                                <div class="received-referrals-li">
                                    <a href="#">
                                        User name 2
                                    </a>
                                    <div class="received-actions">
                                        <a href="#">
                                            <i class="fa fa-comment-o"></i>
                                        </a>
                                        <a href="#">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="experiences_main" class="gen-wht-bx in-heading fade fadeIn %EXPERIENCES_CLASS% %EXPERIENCES_HIDE%">
                            <h2>{LBL_EXPERIENCE} 
                            <div class="add-exp-bx">
                                <?php echo $this->add_experience_link; ?>
                            </div>
                            </h2>
                            <div class="developer-detail-main">
                                <div id="add_experience_container" class="edit-experience-container"></div>
                                <div id="experiences_container">%EXPERIENCES%</div>
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
                        <div class="gen-wht-bx in-heading fade fadeIn %LANGUAGE_CLASS% %LANGUAGE_HIDE%" >
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
                       <!--  <div class="gen-wht-bx in-heading fade fadeIn %SKILL_CLASS% %SKILLS_HIDE%">
                            <div class="languages-detail">
                                <h2 class="sub-title clearfix">{LBL_SKILLS}
                                    <div class="add-exp-bx">
                                        <?php echo $this->add_skill_link; ?>
                                    </div>
                                </h2>
                                <div class="lang-pro-bx">
                                    <div id="add_skill_container" class="edit-skill-container"></div>
                                    <ul class="tag" id="skills_container">%SKILLS%</ul>
                                </div>
                            </div>
                        </div> -->
                        <div id="licenses_endorsements_main" class="gen-wht-bx in-heading fade fadeIn">
                            <h2>{LBL_EDIT_PROFILE_LICENSES_ENDORSEMENT}
                                <div class="add-exp-bx">
                                    <?php echo $this->add_licenses_endorsement; ?>
                                </div>
                            </h2>
                            <div class="licenses-main">
                                <div id="add_license_container" class="edit-license-container"></div>
                                <div id="licenses_endorsements_container">
                                    %LICENSES_ENDORSEMENTS%
                                </div>
                            </div>
                        </div>
                        
                        <div id="airport_main" class="gen-wht-bx in-heading fade fadeIn">
                            <div class="languages-detail">
                                <h2 class="sub-title clearfix">{LBL_EDIT_PROFILE_SELECT_HOME_AIRPORT}
                                    <div class="add-exp-bx">
                                        <?php echo $this->add_airport_link; ?>
                                    </div>
                                </h2>
                                <div class="lang-pro-bx">
                                    <div id="add_airport_container" class="edit-airport-container"></div>
                                    <ul class="tag" id="airport_container">%AIRPORT%</ul>
                                </div>
                            </div>
                        </div>
                         <div class="gen-wht-bx in-heading fade fadeIn %ISREVIEWDISPLAY%">
                            <div class="languages-detail">
                                <h2 class="sub-title clearfix">{LBL_RATE_REVIEW_FERRY_PILOT}
                                </h2>
                                <div class="lang-pro-bx">
                                    <div class="">
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
                                            <div class="comp-desc-in pt-0">
                                                <div class="">
                                                    <textarea placeholder="Description" name="description" id="description"></textarea>
                                                </div>
                                                <div class="form-group cf">
                                                    <button type="submit" class="blue-btn" name="save_ferry_pilot_rating" id="save_ferry_pilot_rating">{LBL_COMPANY_DETAILS_RATE_REVIEW_SAVE} </button>
                                                    <button type="reset" class="outer-red-btn" name="cancel_ferry_pilot_rating" id="cancel_ferry_pilot_rating">{LBL_COMPANY_DETAILS_RATE_REVIEW_CANCEL} </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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

<script type="text/javascript">
    $(document).on('click','#inviteReferrals',function(){
        $('#invite_referrals').modal('show');
    });
     $.validator.addMethod("companyNm", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\'\s]*$/.test(value);
    }); 
    $("#ferry_pilot_rating_form").validate({
        ignore: [],
        rules: {
            description: {required: true,companyNm: true}
        },
        messages: {
            description: {required: "{ERROR_MESSAGE_FOR_COMPANY_REVIEW_DESCRIPTION}"}
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
            if($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        }
    });
    $("#ferry_pilot_rating_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"](obj.success);
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
</script>

<script type="text/javascript">
    $('#avatar-modal').on('hidden.bs.modal', function () {
        $('.avatar-wrapper img').cropper('destroy');
        $('.avatar-wrapper').empty();
    });
    $('#avatar-modal').on('shown.bs.modal', function () {
        $('.avatar-wrapper img').cropper({
            aspectRatio: 1,
            strict: true,
            minCropBoxWidth: 130,
            minCropBoxHeight: 130,
            viewMode: 1,
            crop: function (e) {
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
    $(document).on('change', '#avatarInput', function (e) {
        var _this = $(this);
        var value = _this.val();
        var allowedFiles = ["jpg", "jpeg", "png"];
        var extension = value.split('.').pop().toLowerCase();
        if (jQuery.inArray(extension, allowedFiles) !== -1) {
            var url = (typeof FileReader == "undefined") ? webkitURL.createObjectURL(e.target.files[0]) : URL.createObjectURL(e.target.files[0]);
           // $('.avatar-wrapper').empty().html('<img src="' + url + '">');
            //$('#avatar-modal').modal('show');
        } else {
            toastr['error']("{ERROR_YOU_CAN_ONLY_UPDLOAD_JPG_PNG}");
            $(".loading").hide();
            $("#profile_picture").val("");

        }
    });

    /*$(document).on('click', '#cover_picture', function (e) {
          $("#Edit_Profile1").show(); 
        
    });*/

    $(document).on('click', '#btnCrop', function (e) {
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
            beforeSend: function () {
                addOverlay();
                _this.attr('disabled', true);
            },
            success: function (data) {
                if (data.state == 200) {
                    $('#profile_picture_container').html(data.updated_profile_pic_src);
                    $('#avatar-modal').modal('hide');
                } else {
                    toastr['info'](data.message);
                }
            },
            complete: function () {
                _this.attr('disabled', false);
                removeOverlay();
                $('.loading').fadeOut();
            }
        });
        e.stopImmediatePropagation();
    });
    $(document).on("click", "#removeUserImage", function () {
        $("#profile_picture_container").html("");
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}removeProfileImage",
            data: {action: 'removeImage'},
            dataType: 'json',
            success: function (data) { 
                //console.log(data.image_medium);
               // $("#profile_picture_container").html(data.image_medium);
                $(".user-img").html(data.image_medium);
                $('#profile_picture_container').html(data.image_medium+'<div class="profile-overlay"><a href="javascript:void(0);" title="Edit" id="change_profile_picture"><div class="btn-file active"><i class="fa fa-pencil"></i><input type="file" class="places_image" accept="image/x-png,image/jpeg" name="profile_picture" id="profile_picture" tabindex="-1"></div></a></div>');

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
            beforeSend: function () {
                $("#add_experience_container").html("");
                $(".developer-detail").each(function () {
                    var view_experience_details = $(this).find(".view-experience-details");
                    var edit_experience_container = $(this).find(".edit-experience-container");

                    view_experience_details.fadeIn(1500, function () {
                        edit_experience_container.html("").fadeOut(1000);
                    });

                });
                addOverlay();
            },
            complete: function () {
                removeOverlay();
            },
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    if (type == 'add') {
                        $("#add_experience_container").html(data.experience_form).fadeIn(1500, function () {
                            $("#add_experience").hide();
                            var expLength = $("#experiences_container").find(".developer-detail").length;
                            //alert(expLength);
                            if(expLength == 0){
                                $("#experiences_container").hide();
                            }
                            height = $("#add_experience_container").offset().top - 70;                    
                            scrolWithAnimation(height);
                        });
                    } else {
                        experience_box_element.find(".edit-experience-container").html(data.experience_form).fadeIn(1500, function () {
                            experience_box_element.find(".view-experience-details").hide();
                            console.log(experience_box_element);
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
    $(document).on("click", "#add_experience", function () { handleAddEditExperience('add', '', ''); });
    $(document).on("click", ".edit-experience-icon", function () {
        experience_box_element = $(this).parents(".developer-detail");
        experience_id = experience_box_element.data("experience-id");
        handleAddEditExperience('edit', experience_id, experience_box_element);
    });
    $(document).on("click", ".delete-experience-icon", function () {
        experience_box_element = $(this).parents(".developer-detail");
        experience_id = experience_box_element.data("experience-id");
        var bootBoxCallback = function (result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteExperience",
                    data: {
                        experience_id: experience_id,
                        action: 'deleteExperience'
                    },
                    beforeSend: function () {addOverlay();},
                    complete: function () {removeOverlay();},
                    dataType: 'json',
                    success: function (data) {
                        if (data.status) {
                            toastr['success'](data.success);
                            experience_box_element.removeClass('developer-detail');
                            experience_box_element.hide();
                            if(data.msg != ''){
                                $("#experiences_container").html('<div class="no-data text-center">'+data.msg+'</div>');
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
    $(document).on("click", ".delete-education-icon", function () {
        education_box_element = $(this).parents(".education-detail");
        education_id = education_box_element.data("education-id");
        var bootBoxCallback = function (result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteEducation",
                    data: {
                        education_id: education_id,
                        action: 'deleteEducation'
                    },
                    beforeSend: function () {addOverlay();},
                    complete: function () {removeOverlay();},
                    dataType: 'json',
                    success: function (data) {
                        if (data.status) {
                            toastr['success'](data.success);
                            education_box_element.hide();
                            if(data.msg != ''){
                                $("#educations_container").html('<div class="no-data text-center">'+data.msg+'</div>');
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
    $(document).on("click", ".delete-licenses-icon", function () {
        education_box_element = $(this).parents(".licenses-detail");
        licenses_id = education_box_element.data("licenses-id");
        var bootBoxCallback = function (result) {
            if (result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteLicense",
                    data: {
                        licenses_id: licenses_id,
                        action: 'deleteLicense'
                    },
                    beforeSend: function () {addOverlay();},
                    complete: function () {removeOverlay();},
                    dataType: 'json',
                    success: function (data) {
                        if (data.status) {
                            toastr['success'](data.success);
                            education_box_element.hide();
                            if(data.msg != ''){
                                $("#licenses_endorsements_container").html('<div class="no-data text-center">'+data.msg+'</div>');
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
    $(document).on("click", ".edit-user-details-icon", function () {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getUserDetailForm",
            data: {action: 'getUserDetailForm',},
            beforeSend: function () {$("#user_details_container").fadeOut();addOverlay();},
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    $("#update_user_details").html(data.user_detail_form).fadeIn(1500, function () {
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
            beforeSend: function () {
                $("#add_education_container").html("");
                $(".education-detail").each(function () {
                    var view_education_details = $(this).find(".view-education-details");
                    var edit_education_container = $(this).find(".edit-education-container");
                    view_education_details.fadeIn(1500, function () {
                        edit_education_container.html("").fadeOut(1000);
                    });
                });
                addOverlay();
            },
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    if (type == 'add') {
                        $("#add_education_container").html(data.education_form).fadeIn(1500, function () {
                            $("#add_education").hide();
                            var expLength = $("#educations_container").find(".developer-detail").length;
                            if(expLength == 0){
                                $("#educations_container").hide();
                            }
                            height = $("#add_education_container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    } else {
                        console.log(education_box_element);
                        education_box_element.find(".edit-education-container").html(data.education_form).fadeIn(1500, function () {
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
    $(document).on("click", "#add_education", function () {handleAddEditEducation('add', '', '');});
    $(document).on("click", ".edit-education-icon", function () {
        education_box_element = $(this).parents(".education-detail");
        education_id = education_box_element.data("education-id");
        handleAddEditEducation('edit', education_id, education_box_element);
    });

    $(document).on("click", "#add_licenses_endorsement", function () {handleAddLicensesEndorsement('add', '', '');});
    $(document).on("click", ".edit-licenses-icon", function () {
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
                licenses_id: licenses_id},
            beforeSend: function () {
                $("#add_license_container").html("");
                $(".licenses-detail").each(function () {
                    var view_education_details = $(this).find(".view-license-details");
                    var edit_education_container = $(this).find(".edit-licenses-container");
                    view_education_details.fadeIn(1500, function () {
                        edit_education_container.html("").fadeOut(1000);
                    });
                });
                addOverlay();
            },
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.status) {
                    if (type == 'add') {
                        $("#add_license_container").html(data.licenses_form).fadeIn(1500, function () {
                            $("#add_licenses_endorsement").hide();
                            var expLength = $("#licenses_endorsements_container").find(".developer-detail").length;
                            if(expLength == 0){
                                $("#licenses_endorsements_container").hide();
                            }
                            height = $("#add_license_container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    } else {
                        //console.log(education_box_element);
                        education_box_element.find(".edit-licenses-container").html(data.licenses_form).fadeIn(1500, function () {
                            education_box_element.find(".view-license-details").hide();
                            height = education_box_element.find(".edit-licenses-container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    }
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    $(document).on("click", ".edit-airports-icon", function () {
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
                airport_id: airport_id},
            beforeSend: function () {
                $("#add_airport_container").html("");
                $(".airport-detail").each(function () {
                    var view_education_details = $(this).find(".view-airport-details");
                    var edit_education_container = $(this).find(".edit-airport-container");
                    view_education_details.fadeIn(1500, function () {
                        edit_education_container.html("").fadeOut(1000);
                    });
                });
                addOverlay();
            },
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.status) {
                    if (type == 'add') {
                        $("#add_airport_container").html(data.airport_form).fadeIn(1500, function () {
                            $("#add_airport").hide();
                            var expLength = $("#airport_container").find(".developer-detail").length;
                            if(expLength == 0){
                                $("#airport_container").hide();
                            }
                            height = $("#add_airport_container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    } else {
                        education_box_element.find(".edit-airport-container").html(data.airport_form).fadeIn(1500, function () {
                            education_box_element.find(".view-airport-details").hide();
                            height = education_box_element.find(".edit-airport-container").offset().top - 70;
                            scrolWithAnimation(height);
                        });
                    }
                } else {
                    toastr["error"](data.error);
                }
            }
        });
    }
    function handleAddEditSkills() {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getSkillForm",
            data: {action: 'getSkillForm'},
            beforeSend: function () {addOverlay();},
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    $("#add_skill_container").html(data.skill_form).fadeIn(1500, function () {
                       // $("#add_skills").hide();
                        var langLength = $("#skills_container").find("li").length;
                        if(langLength == 0){
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
    $(document).on("click", "#add_skills", function () {handleAddEditSkills();});
    function handleAddEditAirports() {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getAirportForm",
            data: {action: 'getAirportForm'},
            beforeSend: function () {addOverlay();},
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    $("#add_airport_container").html(data.airport_form).fadeIn(1500, function () {
                       // $("#add_skills").hide();
                        var langLength = $("#airport_container").find("li").length;
                        if(langLength == 0){
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
    $(document).on("click", "#add_airport", function () {handleAddEditAirports();});
    $(document).on("click", "#add_languages", function () {handleAddEditLanguages();});
    function handleAddEditLanguages() {
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}getLanguageForm",
            data: {action: 'getLanguageForm'},
            beforeSend: function () {addOverlay();},
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    $("#add_language_container").html(data.language_form).fadeIn(1500, function () {
                        $("#add_languages").hide();
                        var langLength = $("#languages_container").find("li").length;
                        if(langLength == 0){
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
    $(document).on('click', ".remove_language", function () {
        var parents_li = $(this).parents('li');
        var language_id = $(this).data('language-id');
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}removeLanguage",
            data: {language_id: language_id,action: 'removeLanguage'},
            beforeSend: function () {addOverlay();},
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    toastr['success'](data.success);
                    parents_li.fadeOut(1500);
                    if(data.msg != ''){
                        $("#languages_container").html('<div class="no-data text-center">'+data.msg+'</div>');
                    }  
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(document).on('click', ".remove_skill", function () {
        var parents_li = $(this).parents('li');
        var skill_id = $(this).data('skill-id');
        $.ajax({
            type: 'POST',
            url: "{SITE_URL}removeSkill",
            data: {skill_id: skill_id,action: 'removeSkill'},
            beforeSend: function () {addOverlay();},
            complete: function () {removeOverlay();},
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    toastr['success'](data.success);
                    parents_li.fadeOut(1500);
                    if(data.msg != ''){
                        $("#skills_container").html('<div class="no-data text-center">'+data.msg+'</div>');
                    } 
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    /*$(document).on("focus", "#change_profile_picture, #removeUserImage", function (e) {$(this).closest(".profile-overlay").css("opacity", 1);});*/
    $("#change_profile_picture").focus().keyup(function (e) {
        if (e.keyCode === 13) {
            console.log($("#profile_picture"));
            $("#profile_picture").click();
        }
    });
    $(document).on("focusout", "#change_profile_picture, #removeUserImage", function (e) {
        $(this).closest(".profile-overlay").css("opacity", "");
    });
    $(document).on("focus", ".edit-user-details-icon,.edit-experience-icon,.delete-experience-icon,.edit-education-icon,.delete-education-icon,.edit-licenses-icon,.delete-licenses-icon", function (e) {
        $(this).css("opacity", 1);
    });
    $(document).on("focusout", ".edit-user-details-icon,.edit-experience-icon, .delete-experience-icon,.edit-education-icon, .delete-education-icon,.edit-licenses-icon,.delete-licenses-icon", function (e) {
        $(this).css("opacity", "");
    });
</script>

<!-- Image crop model end-->
<script type="text/javascript">
  $(document).on("click","#close_popup",function(e){
           $("#Edit_Profile1").hide();
          //$(".close").click();
        });
var img_incr=-1;
function showdata()
{
    var formnew=document.getElementById("avtar_form");

    //var a=form.imageupload.value;
    //var formdata = $("#projectCreatefield").serialize();
    var formData = new FormData(formnew);
    var pathname = window.location.pathname.split('/');
    var mod=pathname['4'];
    var which_types=$("#hidden_image_id").html();
    $("#which_types").val(which_types);
    console.log(which_types);
    if(which_types=='images' || which_types=='header_slider' || which_types == 'activity_image' || which_types=='slider_home'){ var url_send='crop.php'; }
    
    var url_send = "<?php echo SITE_URL; ?>modules-nct/profile-nct/crop.php";
    $(window).scrollTop(0);
    /*$(".avatar-wrapper").append("<img class='loading' src='<?php echo SITE_THEME_IMG;?>/ajax-loader-transparent.gif' style='margin-left:300px; margin-top:100px;'/>");*/
    var id = $("#id").val();
    
    formData.append('id',id);
    jQuery.ajax({
      url: url_send,
      type: 'post',
      dataType:'json',
      data:  formData,
      processData: false,  // tell jQuery not to process the data
      contentType: false ,  // tell jQuery not to set contentType
      enctype: 'multipart/form-data',
      mimeType: 'multipart/form-data',
      cache: false,
      beforeSend: function() {
                addOverlay();
        },

      success: function(data) {
        //window.location.href=data.url;
        //$('#thumb_video').attr('src', data)
            removeOverlay();

            $("#Edit_Profile1").hide();
      
            var site_url = "<?php echo SITE_URL; ?>";
            var dir_url = "<?php echo DIR_URL; ?>";

            var str = data.result;
            var final_img_url = str.replace(dir_url, site_url);
            console.log(data.updated_profile_pic_src);
            if(which_types == 'images'){

              $('#tmp_img').val(data.filename);
              $("#company_logo_img").attr("src",final_img_url);
              $(".user-img").html('<img src="' +final_img_url+ '"  />')
              $('#profile_picture_container').html(data.updated_profile_pic_src+'<div class="profile-overlay"><a href="javascript:void(0);" title="Edit" id="change_profile_picture"><div class="btn-file active"><i class="fa fa-pencil"></i><input type="file" class="places_image" accept="image/x-png,image/jpeg" name="profile_picture" id="profile_picture" tabindex="-1"></div></a><a href="javascript:void(0);" id="removeUserImage" title="Remove">  <i class="fa fa-close active"></i></a></div>');
            }else{
                //$('.profile-view-outer').css('background-image','url('+data.updated_profile_pic_src+')');

                $(".banner_img_change").attr("src",data.updated_profile_pic_src);
                toastr['success']("{UPDATE_COVER_PHOTO_MSG}");


            }
            $(".close").click();
            $(".loading").hide();
        }

    });
}
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
$(document).ready(function() {
        if($("#profile_picture_container").find("span").length){
         // $("#removeUserImage").hide();
        }
        $('.gen-owl-carousel').owlCarousel({
            items:1,
            margin:10,
            nav:true,
            onInitialized: data_hide,   

            //autoHeight:true
         });
        function data_hide(event) {
        var totalItems = $('.gen-owl-carousel').find('.owl-item').length;
        if(totalItems<=1){
                $('.gen-owl-carousel').find(".owl-controls").attr("class","hidden");

        }   

    }
    });
  $(document).on('click','.otheruser_picture_container',function(){

    var src_img=$(".otheruser_picture_container").find('img').attr('src');
    var src_img_new=src_img.replace("th4", "th5");
    $(".img_src").find('img').attr("src",src_img_new);
    $("#img_popup").modal('show');

  });
  $(document).on('click','#otheruser_picture_container',function(){
    var src_img=$("#otheruser_picture_container").attr('src');
    var src_img_new=src_img.replace("th1", "th2");
    $(".cover_img_src").find('img').attr("src",src_img_new);
    $("#cover_img_popup").modal('show');

  });
  /*window.onscroll = function() {
        myfunctionsimilar();
    };
        
    var similar = document.getElementById("similar-pro-id");

    var sticky_new=similar.offsetTop;
    

    function myfunctionsimilar() {
      if (window.pageYOffset > sticky_new) {
        similar.classList.add("sticky");
      } else {
        similar.classList.remove("sticky");
      }
    }*/
</script>
<div class="modal fade in modal-h" id="invite_friend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog  is-width-set" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
            <h4 class="modal-title" id="myModalLabel">Invite Friends</h4>
        </div>
        <div class="modal-body">
            <div class="form-list cf row">
                <div class="col-sm-12">
                    <div class="form-group cf">
                        <input type="text" placeholder="Email Address" value="">
                    </div>
                    <div class="form-group cf">
                        <textarea placeholder="Message">Message</textarea>
                    </div>
                </div>
                <div class="form-group cf text-center">
                    <button type="submit" class="blue-btn" name="save_education" id="save_education">Save </button>
                    <input type="reset" class="outer-red-btn" name="education_form_cancel" id="education_form_cancel" data-dismiss="modal" value="Cancel">
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<div class="modal fade in modal-h" id="invite_referrals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog  is-width-set" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close"></i></button>
            <h4 class="modal-title" id="myModalLabel">Invite Referrals</h4>
        </div>
        <div class="modal-body">
            <!-- <div class="srch-conn-bx">
                <div class="form-group cf"> -->
                    <i class="icon-srch"></i>
                    <input type="text" id="searchForReferrals" name="searchForReferrals" placeholder="{LBL_SEARCH}">
                <!-- </div>
            </div> -->
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("#searchForReferrals").keyup(function(){
        var keyword = $("#searchForReferrals").val();
        var user_id = $(this).data('value');
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
                $('#connection').html(data);
                setHeights();
            }
        });
    });
</script>