<div class="gen-wht-bx in-heading cf">
<div class="convers-admin-outer">
    <h3>{LBL_GRP_DTL_START_CONVERSATION_WITH_GROUP}</h3>
    <a href="%USER_PROFILE_URL%" title="%USER_PROFILE_URL%" class="comment-img">%USER_PROFILE_PICTURE%</a>
    
    <form class="share-form" name="share_an_update_form" id="share_an_update_form" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
        <div class="col-sm-12 form-group cf">
            <input type="text" name="post_title" id="post_title" placeholder="{LBL_GRP_DTL_ENTER_TITLE}*">
        </div>

        <div class="col-sm-12 form-group cf">
            <textarea class="txt-area-lg" name="description" id="description" placeholder="{LBL_GRP_DTL_SHARE_AN_UPDATE}*" ></textarea>
        </div>
        <div id="image_preview_container_main" class="galary-bx" style="display:none">
            <div class="view-upload">
            <div id="image_preview_container">
                <figure>
                <img id="feed_image_img" src="" />
                </figure>
                <span id="feed_image_name">{LBL_TEST_IMAGE}</span>
                <!-- <div id="feed_image_size" class="clearfix">{LBL_TEST_IMAGE}</div> -->
                <div class="close-img">
                    <a href="javascript:void(0);" title="Remove image" class="remove-feed-image"><i class="icon-close"></i></a>
                </div>
            </div>
            </div>
        </div>
        <div class="dash-btm-share group-share">
            <div class="start-img-upload">
                <div id="share_update_file_upload" class="upload-vid-bx" title="{UPLOAD_AN_IMAGE}">
                    <i class="icon-img"></i>
                    <input type="file" id="feed_image" name="feed_image" />
                </div>
            </div>
            <button type="submit" class="blue-btn" id="share_update_from_group" name="share_update_from_group">{LBL_GRP_DTL_SHARE}</button>
            <input type="hidden" name="shared_with" id="shared_with" value="p">
        <input type="hidden" name="group_id" id="group_id" value="%ENC_GROUP_ID%">
        </div>
        
    </form>
</div>
</div>
<div class="feed-gropu-outer cf" id="recent_activity_div">
    <h3>{LBL_GRP_DTL_RECENT_ACTIVITIES}</h3>
     %FEEDS%
</div>

<script type="text/javascript">
    $.validator.addMethod('customvalidation',function (value, element) {
        if(this.optional(element) || /<\/?[^>]+(>|$)/g.test(value)){
            return false;
        } else {
            return true;
        }
        },"{PLEASE_ENTER_ALPHANUMERIC_VALUE}"
    ); 
    $(document).on("change", "#feed_image", function(e) {
        var file = this.files[0];
        showFeedImage(file);
    });

    $(document).on("click", ".remove-feed-image", function() {
        $("#image_preview_container_main").slideUp(1000, function() {
            $("#feed_image_img").attr("src", "");
        });
    });

    function showFeedImage(file) {
        readFile(file, function(e) {

            var image = new Image();
            image.src = e.target.result;

            file_name = file.name;

            image.onload = function() {
                // access image size here 
                width = this.width;
                height = this.height;
                $("#feed_image_img").attr("src", this.src);
                $("#feed_image_name").html(file_name);
                $("#feed_image_size").html(width + " X " + height);
                $("#image_preview_container_main").slideDown(1000);

            };

        });
    }
    jQuery.validator.addMethod("noSpace", function(value, element) { 
      var reg =/<(.|\n)*?>/g; 

      if(reg.test($('#description').val()) == true){
        return;
      }else{
              return $.trim(value); 

      }
    }, "{NO_SPACE_ALLOW_ERROR}");

    $("#share_an_update_form").validate({
        rules: {
            description: {
                required: function(element) {
                    return $("#feed_image").is(':blank');
                },
                noSpace:{
                  depends: function(element) {
                    return $("#feed_image").is(':blank');

                  }
                },
                customvalidation:{
                  depends: function(element) {
                    return $("#feed_image").is(':blank');

                  }
                }
            },
            post_title: {
                required: true,
                customvalidation:true,
                noSpace:true
            }
        },
        messages: {
            description: {
                required: "{ERROR_POST_SOME_CONTENT_IMAGE}"
            },
            post_title: {
                required: "{LBL_ENTER_POST_TITLE}"
            }
        },
        highlight: function (element) {
            if (!$(element).is("select")) {
                $(element).removeClass("valid-input").addClass("has-error");
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
            }
        },
        unhighlight: function (element) {
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function (error, element) {
            $(element).parent("div").append(error);
        },
        submitHandler: function (form) {
            return true;
        }
    });

    $("#share_an_update_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                //toastr["success"](obj.success);
                /*$("#share_an_update_form")[0].reset();
                $("#image_preview_container_main").slideUp(1000, function() {
                    $("#feed_image_img").attr("src", "");
                });*/
                window.location.reload();
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

<!-- comment textarea starts--> 

<script>
$(document).ready(function() {
    $('.txt-area-lg').height(30);
    $('.txt-area-lg').focus(function() {
        $(this).animate({
            'height': 110,
			'padding-top' : 8,
			'padding-right' : 10,
			'padding-bottom' : 8,
			'padding-left' : 10
        })
		$('.share-with-option').attr('style','display:block !important');
    });
    $('#share_update_file_upload').click(function() {
        $('.txt-area-lg').animate({
            'height': 110,
            'padding-top' : 8,
            'padding-right' : 10,
            'padding-bottom' : 8,
            'padding-left' : 10
        })
        $('.share-with-option').attr('style','display:block !important');
    });
    $('.txt-area-lg').blur(function() {
        $(this).animate({
            'height': 36,
			'padding-top' : 3,
			'padding-right' : 8,
			'padding-bottom' : 3,
			'padding-left' : 8
        })
    });
});
</script> 
<!-- comment textarea ends--> 