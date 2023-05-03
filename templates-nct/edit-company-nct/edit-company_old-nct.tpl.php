<div class="inner-main">
    <div class="create-group-main">
        <form action="{SITE_URL}create-new-group" class="group-form" method="post" name="create_group_form" id="create_group_form">
            <div class="container">
                <div class="row fade fadeIn">
                    <div class="col-sm-8  clearfix"><h1>%TITLE_TEXT%</h1></div>
                    <div class="col-sm-4  clearfix">
                        <ul class="circle-btns text-right">
                            <li>
                                <button type="submit" name="create_group" id="create_group" class="button-circle" title="{BTN_EDIT_COMP_SUBMIT}">
                                    <i class="fa fa-check"></i>
                                </button>
                            </li>
                            <li class="%DELETEURL_HIDDEN%">
                                <a href="javascript:void(0);" id="deleteGroup" class="fa fa-trash-o" title="{LBL_DELETE_GROUP}"></a>
                            </li>
                            <li>
                                <a href="{SITE_URL}groups/my-groups" class="fa fa-arrow-left" title="{BTN_EDIT_COMP_BACK_TO_LISTING}" class="button-circle"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="full-width">
                    <div class="row">
                        <div class="col-sm-3 pr"><p class="text-right text-12 gray-text"><i>* {LBL_INDICATES_REQUIRED_FIELDS}</i></p></div>
                        <div class="col-sm-9">
                            <div class="form-group clearfix fade fadeIn">
                                <label class="group-label">{LBL_GROUP_LOGO}</label>
                                <div class="group-logo-edit">
                                    <div class="group-logo-pic">
                                        <div id="select_logo_container" class="%LOGO_SELECT_CONTAINER_HIDDEN_CLASS%">
                                            <div class="browse-btn">
                                                <span class="upload-btn btn-file"><i class="fa fa-plus-circle plus-upload"></i>
                                                    <input type="file" name="group_logo" id="group_logo">
                                                </span>
                                            </div>
                                            <span class="img-note">300 x 300 {LBL_PIXELS}</span>
                                        </div>
                                        <div id="logo_preview_container" class="logo-preview-contianer %LOGO_PREVIEW_CONTAINER_HIDDEN_CLASS%">
                                            <img id="group_logo_img" src="%GROUP_LOGO_URL%" alt="%GROUP_NAME%" />
                                            <div class="company_logo_actions">
                                                <a href="javascript:void(0);" title="Change" id="change_group_logo" class="fa fa-pencil-square-o"></a>
                                                <a href="javascript:void(0);" title="Remove" id="remove_group_logo" class="fa fa-trash"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="light-gray-text text-12 logo-note"><i class="fa fa-info-circle"></i> {LBL_YOUR_LOGO_WILL_APPEAR_IN_GROUP_DIRECTORY}</p>
                                    <div class="clearfix"></div>
                                    <div class="form-group browse-field">
                                    <!--<input type="text" class="form-control border-field" placeholder="Select image">
                                    <div class="upload-logo small-btn">
                                        <span class="upload-btn btn-file">Browse
                                            <input type="file">
                                        </span>
                                    </div>-->
                                    <p class="form-note light-gray-text">{LBL_NOTE_PNG_JPEG_OR_GIF_MAX_SIZE}</p>
                                </div>
                                <div class="form-group">
                                    <div class="radio-btn-small mr-35">
                                        <input type="checkbox" id="acknowledge_check" name="acknowledge_check" %AGREEMENT%>
                                        <label for="acknowledge_check"><span></span><p class="text-12 light-gray-text">* {LBL_GROUP_AGREE_NOTE_IN_FOOTER}</p> </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group clearfix fade fadeIn">
                            <label class="group-label">{LBL_GROUP_NAME} *</label>
                            <input type="text" value="%GROUP_NAME%" placeholder="{PH_ENTER_GROUP_NAME}" name="group_name" class="form-control border-field">
                            <p class="small-note light-gray-text">%LBL_NOTE_SITE_IS_NOTE_ALLOWED_TO_YOUR_GROUP_NAME%</p>
                        </div>
                        <div class="form-group clearfix fade fadeIn">
                            <label class="group-label">{LBL_GROUP_FORM_DESCRIPTION} *</label>
                            <p class="small-note light-gray-text">{LBL_YOUR_FULL_DESCRIPTION_OF_GROUP_WILL_APPEAR_ON_GROUP_PAGE}</p>
                            <textarea rows="4" class="form-control border-field" name="group_decription" id="group_decription" required>%GROUP_DESCRIPTION%</textarea>
                        </div>
                        <div class="form-group clearfix fade fadeIn"> 
                            <label class="group-label">{LBL_GROUP_TYPE} *</label>                   
                            <select name="group_type_id" id="group_type_id" class="form-control selectpicker show-tick">
                                <option value="">{LBL_SELECT_GROUP_TYPE}</option>
                                %GROUP_TYPE_OPTIONS%
                            </select>
                        </div>
                        <div class="form-group clearfix fade fadeIn"> 
                            <label class="group-label">{LBL_GROUP_INDUSTRY} *</label>                   
                            <select name="group_industry_id" id="group_industry_id" class="form-control selectpicker show-tick">
                                <option value="">{LBL_SELECT_GROUP_INDUSTRY}</option>
                                %GROUP_INDUSTRY_OPTIONS%
                            </select>
                        </div>
                        <div class="form-group clearfix fade fadeIn">
                            <label class="group-label">{LBL_GROUP_PRIVACY} *</label>                   
                            <div class="radio-btn-small mr-35">
                                <input type="radio"  name="privacy" id="privacy_pr" value="privacy_pr" %PRIVACY_PR_CHECKED%>
                                <label for="privacy_pr"><span></span>{LBL_GROUP_PRIVATE}</label>
                            </div>
                            <div class="radio-btn-small mr-35">
                                <input type="radio"  name="privacy" id="privacy_pu" value="privacy_pu" %PRIVACY_PU_CHECKED%>
                                <label for="privacy_pu"><span></span>{LBL_GROUP_PUBLIC}</label>
                            </div>
                            <label id="privacy-error" class="error" for="privacy"></label>
                        </div>
                        <div class="form-group clearfix fade fadeIn " id="accessibility_div">
                            <label class="group-label">{LBL_GROUP_ACCESSIBILLITY} *</label>                   
                            <div class="radio-btn-small mr-35">
                                <input type="checkbox"  name="accessibility" id="accessibility" value="accessibility_a" %AUTO_JOIN_CHECKED%>
                                <label for="accessibility"><span></span>{LBL_AUTO_JOIN}</label>
                            </div>
                            <!--<div class="radio-btn-small mr-35">
                                <input type="radio"  name="accessibility" id="accessibility_a" value="accessibility_a" %AUTO_JOIN_CHECKED%>
                                <label for="accessibility_a"><span></span>Auto join</label>
                                
                            </div>
                            <div class="radio-btn-small mr-35">
                                <input type="radio"  name="accessibility" id="accessibility_rj" value="accessibility_rj" %REQUEST_JOIN_CHECKED%>
                                <label for="accessibility_rj"><span></span>Request to join</label>
                            </div>-->
                        </div>
                        <div class="form-group clearfix fade fadeIn" id="approve_members_div">
                            <div class="form-group">
                                <label class="group-label">{LBL_PREAPPROVE_MEMBERS_WITH_FOLLOWING_EMAILS}:</label>
                                <input type="text" class="form-control border-field" placeholder="{LBL_FORM_EDIT_COMP_START_TYPING_NAME}" name="approve_members" id="approve_members">
                            </div>
                            <ul id="approve_members_container" class="admin-row">
                                %APPROVE_MEMBERS%
                            </ul>
                        </div>
                        <div class="form-group clearfix fade fadeIn">
                            <label class="group-label">{LBL_GROUP_AGREEMENT} *</label>
                            <div class="radio-btn-small mr-35">
                                <input type="checkbox"  name="agreement" id="agreement" %AGREEMENT%>
                                <label for="agreement"><span></span><i class="text-12 light-gray-text">{LBL_CHECK_TO_CONFIRM_YOU_HAVE_READ_AND_ACCEPT_THE} <a href="%TERMS_CONDITION_URL%" title="{LBL_TERMS_OF_SERVICE}" target="_blank" class="blue-color underline">{LBL_TERMS_OF_SERVICE}.</a></i> </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="group_id" id="group_id" value="%GROUP_ID%">
    </form>
</div>
</div>
<script type="text/javascript">
$(document).on("click", "#change_group_logo", function() { $("#group_logo").click();});
$(document).on("click", "#remove_group_logo", function() {
    $("#select_logo_container").removeClass("hidden");
    $("#group_logo_img").attr("src", "");
    $("#logo_preview_container").addClass("hidden");
});
$(document).on("change", "#group_logo", function(e) {
    var file = this.files[0];
    showGroupLogo(file);
});

function showGroupLogo(file) {
    readFile(file, function(e) {
        var image = new Image();
        image.src = e.target.result;
        image.onload = function() {
            // access image size here 
            width = this.width;
            height = this.height;
            aspectRatio = width / height;
            if (aspectRatio == 1 && width >= 300 && height >= 300) {
                $("#select_logo_container").addClass("hidden");
                $("#group_logo_img").attr("src", this.src);
                $("#logo_preview_container").removeClass("hidden");
            } else {
                $("#group_logo").val("");
                toastr["error"]("{LBL_PLEASE_UPLOAD_THE_GROUP_IMAGE_SAME_ASPECT_RATIO}");
            }
        };
    });
}

$(document).on('blur', "#group_name", function() {
    if($(this).val().toUpperCase() == "CONNECTIN") {
        toastr["error"]("{LBL_SITE_IS_ALLOWED_TO_BE_USED_IN_YOUR_GROUP_NAME}");
        $(this).val('');
    }
});

$(document).ready(function() {
    $("#approve_members").autocomplete(autocomp_opt);
    if($("input[name='privacy']:checked").val() == 'privacy_pu') {
        $("#accessibility_div").removeClass("hidden");
        $("#approve_members_div").addClass("hidden");
    }else {
        $("#accessibility_div").addClass("hidden");
        $("#approve_members_div").removeClass("hidden");
    }
});

$(document).on('change', $("input[name='privacy']"), function(){
    if($("input[name='privacy']:checked").val() == 'privacy_pu') {
        $("#accessibility_div").removeClass("hidden");
        $("#approve_members_div").addClass("hidden");
    }else {
        $("#accessibility_div").addClass("hidden");
        $("#approve_members_div").removeClass("hidden");
    }
});

    var autocomp_opt = {
        source: function (request, response) {
            var input = this.element;
            var approve_member_ids = $("input[name='approve_member_ids[]']").map(function(){return $(this).val();}).get();
            $.ajax({
                url: "{SITE_URL}getConnectionsForGropus",
                type: "POST",
                minLength: 2,
                dataType: "json",
                data: {
                    action: 'getConnectionsForGropus',
                    user_name: request.term,
                    approve_member_ids: approve_member_ids
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {label: item.user_name, value: item.user_name, id: item.user_id};
                    }));
                },
                error: function (jq, status, message) {
                    //alert(message);
                }
            });
        },
        select: function (event, c) {
            //console.log(c.item.id);
            user_id = c.item.id;
            $.ajax({
                url: "{SITE_URL}getConnectionBoxForGropus",
                type: "POST",
                dataType: "json",
                beforeSend: function() {
                    addOverlay();
                },
                complete: function() {
                    removeOverlay();
                },
                data: 'action=getConnectionBoxForGropus&user_id=' + user_id,
                success: function (data) {
                    if(data.status) {
                        $("#approve_members").val("");
                        $("#approve_members_container").append(data.content);
                    } else {
                        toastr["error"](data.error);
                    }
                },
                error: function (jq, status, message) {
                    //alert(message);
                }
            });
        },
        autoFocus: true
    };
    $("#create_group_form").validate({
        ignore: [],
        rules: {
            group_name: {required: true},
            group_decription: {required: true},
            group_type_id: {required: true},
            group_industry_id: {required: true},
            privacy: {required: true},
            acknowledge_check: "required",
            agreement: "required",
        },
        messages: {
            group_name: {required: "{ERROR_ENTER_GROUP_NAME}"},
            group_decription: {required: "{ERROR_GROUP_DESCRIPTION}"},
            group_type_id: {required: "{ERROR_SELECT_GROUP_TYPE}"},
            group_industry_id: {required: "{ERROR_SELECT_GROUP_INDUSTRY}"},
            privacy: {required: "{ERROR_SELECT_PRIVACY}"},
            acknowledge_check: "{ERROR_ACCEPT_USER_AGGREMENT_CONDITIONS}",
            agreement: "{ERROR_GROUP_ACCEPT_TERMS_SERVICE}",
        },
        highlight: function(element) {
            //$(element).addClass('has-error');
            if (!$(element).is("select")) {
                $(element).removeClass("valid-input").addClass("has-error");
            }  else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            //$(element).closest('.form-group').removeClass('has-error');
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            if($(element).attr("type") == "checkbox" || $(element).attr("type") == "radio") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        },
        submitHandler: function(form) {
            return true;
        }
    });
    $("#create_group_form").ajaxForm({
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
    $(document).on('click', "#deleteGroup", function() {
        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteGroup",
                    data: {
                        group_id: '%ENCRYPTED_GROUP_ID%',
                        action: "deleteGroup"
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
                            window.location = "{SITE_URL}groups/my-groups";
                            
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }  
        }
        initBootBox("{LBL_DELETE_GROUP}", "{LBL_ARE_YOU_SURE_DELETE_GROUP}", bootBoxCallback);
    });
    $(document).on("click", ".remove", function (e) {
        var id = $(this).data("id");
        var bootBoxCallback = function(result) {
            if(result) {                
                $.ajax({
                    url: "{SITE_URL}deleteMember",
                    type: "POST",
                    dataType: "json",
                    data: {
                        action: 'deleteMember',
                        group_id: '%ENCRYPTED_GROUP_ID%',
                        id: id
                    },
                    beforeSend: function() {
                        addOverlay();
                    },
                    complete: function() {
                        removeOverlay();
                    },            
                    success: function (data) {
                        if (data.status) {
                            toastr['success'](data.success);
                            $('#member_'+id).remove();                    
                        } else {
                            //toastr['error'](data.error);
                            $('#member_'+id).remove();
                        }                
                    }
                });
            }  
        }
        initBootBox("{LBL_DELETE_GROUP_MEMBER}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_GROUP_MEMBER}", bootBoxCallback);
    });
</script>