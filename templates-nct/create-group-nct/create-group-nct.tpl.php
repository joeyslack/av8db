<div class="inner-main">
    <div class="edit-company-sec cf">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-1"></div>
                <div class="col-sm-12 col-md-10">
                    <form action="{SITE_URL}create-new-group" class="group-form" method="post" name="create_group_form" id="create_group_form">
                         <div class="edt-comp-info">
                            <div class="gen-wht-bx cf">
                                <h1>%TITLE_TEXT%</h1>
                                <div class="back-btn-bx text-right">
                                    <a href="javascript:void(0);" id="deleteGroup" class="trash-ico %DELETEURL_HIDDEN%" title="{LBL_DELETE_GROUP}">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                    <a href="{SITE_URL}groups/my-groups" title="{BTN_EDIT_COMP_BACK_TO_LISTING}" class="back-ico">
                                        <i class="icon-back-arrow"></i>
                                    </a>
                                </div>

                                <div class="company-edit-view">
                                    <div class="company-edt-pic">
                                            <div id="select_logo_container" class="blank-logo-bx %LOGO_SELECT_CONTAINER_HIDDEN_CLASS%">
                                                <div class="browse-btn">
                                                    <span class="upload-btn btn-file"><i class="fa fa-plus plus-upload"></i>
                                                        <input type="file" class="places_image" name="group_logo" id="group_logo" title="Upload image" >
                                                    </span>
                                                </div>
                                                <span class="img-note">300 x 300 {LBL_PIXELS}</span>
                                            </div>
                                            <div id="logo_preview_container" class="logo-preview-contianer %LOGO_PREVIEW_CONTAINER_HIDDEN_CLASS%">
                                                <img id="company_logo_img" src="%GROUP_LOGO_URL%" alt="%GROUP_NAME%" />
                                                <div class="company_logo_actions">
                                                    <a href="javascript:void(0);" title="{CHANGE}" id="change_group_logo">
                                                        <i class="icon-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" title="{LBL_REMOVE}" id="remove_group_logo">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    <input type="hidden" name="is_logo_removed" id="is_logo_removed" value="false" />
                                                </div>
                                            </div>
                                    </div>
                                    <div class="edt-dtl-company">
                                    <div class="flat-checkbox">
                                        <input type="checkbox" id="acknowledge_check" name="acknowledge_check" %AGREEMENT%>
                                        <label for="acknowledge_check"><p class="text-12 light-gray-text">* {LBL_GROUP_AGREE_NOTE_IN_FOOTER}</p></label>
                                    </div>
                                    <p class="form-note light-gray-text">{LBL_NOTE_PNG_JPEG_OR_GIF_MAX_SIZE}</p>
                                    <div class="addr-bx"><i class="fa fa-info-circle"></i>{LBL_YOUR_LOGO_WILL_APPEAR_IN_GROUP_DIRECTORY}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="gen-wht-bx cf">
                                <div class="other-form-list">
                                    <div class="list-form cf">
                                        <div class="col-sm-12 col-md-12 form-group">
                                        <label>{LBL_GROUP_NAME}<sup>*</sup></label>
                                        <small>%LBL_NOTE_SITE_IS_NOTE_ALLOWED_TO_YOUR_GROUP_NAME%</small>
                                        <input type="text" value="%GROUP_NAME%" placeholder="{PH_ENTER_GROUP_NAME}" name="group_name" id="group_name" >
                                        </div>
                                    </div>
                                    <div class="list-form cf">
                                        <div class="col-sm-12 col-md-12 form-group">
                                            <label class="group-label">{LBL_GROUP_TYPE}<sup>*</sup></label>                   
                                            <select name="group_type_id" id="group_type_id" class="selectpicker show-tick">
                                                <option value="">{LBL_SELECT_GROUP_TYPE}</option>
                                                %GROUP_TYPE_OPTIONS%
                                            </select>
                                        </div>
                                        <!-- <div class="col-sm-12 col-md-6 form-group">
                                            <label class="group-label">{LBL_GROUP_INDUSTRY}<sup>*</sup></label>                   
                                            <select name="group_industry_id" id="group_industry_id" class="selectpicker show-tick">
                                                <option value="">{LBL_SELECT_GROUP_INDUSTRY}</option>
                                                %GROUP_INDUSTRY_OPTIONS%
                                            </select>
                                        </div> -->
                                    </div>
                                    <div class="list-form cf">
                                        <div class="col-sm-12 col-md-12 form-group">
                                        <label>{LBL_GROUP_FORM_DESCRIPTION}<sup>*</sup></label>
                                        <small>{LBL_YOUR_FULL_DESCRIPTION_OF_GROUP_WILL_APPEAR_ON_GROUP_PAGE}</small>
                                        <textarea rows="4" name="group_decription" id="group_decription" required>%GROUP_DESCRIPTION%</textarea>
                                        </div>
                                    </div>
                                    <div class="list-form cf">
                                        <div class="col-sm-12 col-md-12">
                                        <label>{LBL_GROUP_PRIVACY}<sup>*</sup></label>
                                        <div class="form-group cf">
                                            <div class="custom-radio">
                                                <input type="radio"  name="privacy" id="privacy_pr" value="privacy_pr" %PRIVACY_PR_CHECKED%>
                                                <label for="privacy_pr">{LBL_GROUP_PRIVATE}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio"  name="privacy" id="privacy_pu" value="privacy_pu" %PRIVACY_PU_CHECKED%>
                                                <label for="privacy_pu">{LBL_GROUP_PUBLIC}</label>
                                            </div>
                                             <div id="privacy-error" class="error" for="privacy"></div>
                                        </div>
                                        <div class="form-group clearfix fade fadeIn " id="accessibility_div">
                                        <label class="group-label cf">{LBL_GROUP_ACCESSIBILLITY} *</label>                   
                                        <div class="flat-checkbox">
                                            <input type="checkbox"  name="accessibility" id="accessibility" value="accessibility_a" %AUTO_JOIN_CHECKED%>
                                            <label for="accessibility">{LBL_AUTO_JOIN}</label>
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
                                            <input type="text" placeholder="{LBL_FORM_EDIT_COMP_START_TYPING_NAME}" name="approve_members" id="approve_members">
                                        </div>
                                        <ul id="approve_members_container" class="admin-list-group">
                                            %APPROVE_MEMBERS%
                                        </ul>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="list-form cf">
                                            <div class="col-sm-12 col-md-12 form-group">
                                                <div class="flat-checkbox">
                                                <input type="checkbox"  name="agreement" id="agreement" %AGREEMENT%>
                                                <label for="agreement"><i class="text-12 light-gray-text">{LBL_CHECK_TO_CONFIRM_YOU_HAVE_READ_AND_ACCEPT_THE} <a href="%TERMS_CONDITION_URL%" title="{LBL_TERMS_OF_SERVICE}" target="_blank" class="blue-color underline">{LBL_TERMS_OF_SERVICE}.</a></i> </label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="list-form cf">
                                            <input type="hidden" name="sess_user_id" id="sess_user_id" value="<?php echo $_SESSION['user_id'];?>">
                                            <div class="col-sm-12 col-md-12 form-group">
                                            <button type="submit" class="blue-btn" name="create_group" id="create_group">{BTN_EDIT_COMP_SUBMIT}</button>
                                            <a href="{SITE_URL}groups/my-groups"><button type="button" class="outer-red-btn" name="cancel-btn" id="cancel-btn">{LBL_CANCEL}</button></a>
                                            </div>
                                    </div>
                            </div>
                         </div>
                    <input type="hidden" name="group_id" id="group_id" value="%GROUP_ID%">
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
$.validator.addMethod('pagenm', function (value, element) {
            return /^[a-zA-Z0-9][a-zA-Z0-9\-\_\' ]*$/.test(value);
        }, '{ENTER_VALID}');

$(document).on("click", "#change_group_logo", function() { $("#group_logo").click();});

$(document).on("click", "#remove_group_logo", function() {
    $("#is_logo_removed").val(true);
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

$(document).on('change', "#group_name", function() {
    var name="{SITE_NM}";
    if($(this).val().toUpperCase() == name.toUpperCase()) {
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
jQuery.validator.addMethod("noSpace", function(value, element) { 
  return $.trim(value); 
}, "{NO_SPACE_ALLOW_ERROR}");
    $(document).on('change',"#group_type_id",function(){
        $("#group_type_id").valid();

    });
    // $(document).on('change',"#group_industry_id",function(){
    //     $("#group_industry_id").valid();

    // });
    $("#create_group_form").validate({
        ignore: [],
        rules: {
            group_name: {required: true,pagenm:true},
            group_decription: {required: true,noSpace:true},
            group_type_id: {required: true},
            //group_industry_id: {required: true},
            privacy: {required: true},
            acknowledge_check: "required",
            agreement: "required",
        },
        messages: {
            group_name: {required: "{ERROR_ENTER_GROUP_NAME}"},
            group_decription: {required: "{ERROR_GROUP_DESCRIPTION}"},
            group_type_id: {required: "{ERROR_SELECT_GROUP_TYPE}"},
            //group_industry_id: {required: "{ERROR_SELECT_GROUP_INDUSTRY}"},
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
        var user_id=$(this).data("user-id");
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
                            $('#member_'+user_id).remove();                    
                        } else {
                            //toastr['error'](data.error);
                           // $('#member_'+user_id).remove();
                        }                
                    }
                });
            }  
        }
        initBootBox("{LBL_DELETE_GROUP_MEMBER}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_GROUP_MEMBER}", bootBoxCallback);
    });
</script>
<script type="text/javascript">
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
            $("#group_logo").val("");

        }
    });

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
    if(which_types=='images' || which_types=='header_slider' || which_types == 'activity_image' || which_types=='slider_home'){ var url_send='crop.php'; }
    
    var url_send = "<?php echo SITE_URL; ?>modules-nct/create-group-nct/crop.php";
    $(window).scrollTop(0);
    /*$(".avatar-wrapper").append("<img class='loading' src='<?php //echo SITE_THEME_IMG;?>/ajax-loader-transparent.gif' style='margin-left:300px; margin-top:100px;'/>");*/
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
        $("#Edit_Profile1").hide();
        removeOverlay();

        var site_url = "<?php echo SITE_URL; ?>";
        var dir_url = "<?php echo DIR_URL; ?>";

        var str = data.result;
                //console.log(data);  

        if(data.filename != ''){
           var final_img_url = str.replace(dir_url, site_url);
            $('#tmp_img').val(data.filename);
            $("#select_logo_container").addClass("hidden");
            $("#company_logo_img").attr("src", final_img_url);
            $("#logo_preview_container").removeClass("hidden");
           
        }

            $(".close").click();
            $(".loading").hide();
        }

    });
}
</script>