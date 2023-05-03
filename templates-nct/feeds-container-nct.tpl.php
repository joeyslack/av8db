<div id="feeds_container1" class="post-scroll">
  <div class="post-row gen-feed-list cf"> <?php echo $this->feeds_li; ?> </div>
</div>
<div class="modal fade" id="users_list_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="icon-close"></i>
        </button>
        <h4 class="modal-title" id="myModalLabel">{LBL_GENERAL_LIKES}</h4>
      </div>
      <div id="users_list_container" class="modal-body">
        <ul id="all_users_list" class="post-row">
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="share_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="icon-close"></i>
        </button>
        <h4 class="modal-title" id="myModalLabel">{LBL_GENERAL_SHARE}</h4>
      </div>
      <div class="modal-body clearfix">
        <form class="share-form fade fadeIn" name="share_an_update_form_popup" id="share_an_update_form_popup" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
          <input type="hidden" name="shared_feed_id" id="shared_feed_id" value=""/>
          <div class="form-group cf">
            <textarea name="description_popup" id="description_popup" placeholder="{LBL_WHATS_ON_YOUR_MIND}"></textarea>
          </div>
          <div id="image_preview_container_main_popup" class="form-group white-box">
            <div id="image_preview_container_popup">
              <h5 id="feed_title"></h5>
              <p id="feed_description"></p>
              <img id="feed_image_img_popup" src=""/> </div>
            <div class="col-sm-10 col-md-10"><span id="feed_image_name_popup"></span><span id="feed_image_size_popup" class="clearfix"></span> </div>
          </div>
          <div class="vid-gallary-bx">
            <div id="video_show" class="vid-upload"></div>
                    
         </div>
          <div class="clearfix"></div>
          <div class="form-group cf">
            <select id="shared_with_popup" name="shared_with_popup" class="selectpicker">
              <option value="p">{LBL_SHARE_WITH_PUBLIC}</option>
              <option value="c">{LBL_SHARE_WITH_CONNECTIONS}</option>
            </select>
            
          </div>
          <div class="form-group cf">
            <button type="submit" class="blue-btn" id="share_an_update_popup" name="share_an_update_popup">{LBL_GENERAL_SHARE}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).on("change", "#feed_image_popup", function (e) {
    var file = this.files[0];
    showFeedImagePopup(file);
});
$(document).on("click", ".share", function () {

    validShareForm.resetForm();
    $('#description_popup').removeClass('has-error');
    var feed_box = $(this).parents(".post-cell");
    var feed_id = feed_box.data("feed-id");
    $("#shared_feed_id").val(feed_id);
    $("#share_popup").modal();
    var post_image = $(this).closest(".post-cell").find('.post_image').attr("src");
    var post_title = $(this).closest(".post-cell").find('.post_title').last().html();
    var post_description = $(this).closest(".post-cell").find('.post_description').last().html();
    showFeedImagePopup(post_image,post_title,post_description);
    var video_html='';
    if($(this).closest(".post-cell").find('.embed-responsive').hasClass('hidden')){
        video_html='';
    }else{
        video_html=$(this).closest(".post-cell").find('.embed-responsive').html();
    }
    $("#share_popup").find('#video_show').html(video_html); 



});
$(document).on("click", ".remove-feed-image-popup", function () {
    $("#image_preview_container_main_popup").slideUp(1000, function () {
        $("#feed_image_img_popup").attr("src", "");
    });
});

/*function showFeedImagePopup(file) {
    readFile(file, function (e) {
        var image = new Image();
        image.src = e.target.result;
        file_name = file.name;
        image.onload = function () {
            width = this.width;
            height = this.height;
            $("#feed_image_img_popup").attr("src", this.src);
            $("#feed_image_name_popup").html(file_name);
            $("#feed_image_size_popup").html(width + " X " + height);
            $("#image_preview_container_main_popup").slideDown(1000);
        };
    });
}*/
function showFeedImagePopup(src,title,description) {
    var src1 = src;
     if(src1 != undefined){
        $("#feed_image_img_popup").attr("src", src);
        $("#feed_image_img_popup").removeClass("hide");
        //$("#image_preview_container_main_popup").slideDown(1000);
    }else{
        $("#feed_image_img_popup").addClass("hide");
        //$("#image_preview_container_main_popup").slideUp();
    }

    $("#feed_description").html(description);
    $("#feed_title").html(title);
    $("#image_preview_container_main_popup").slideDown(1000);
   
}
var validShareForm = $("#share_an_update_form_popup").validate({
    rules: {
        description_popup: {
            required: true
        }
    },
    messages: {
        description_popup: {
            required: "{PLEASE_WRITE_SOMETHING}"
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
$("#share_an_update_form_popup").ajaxForm({
    beforeSend: function () {
        addOverlay();
    },
    uploadProgress: function (event, position, total, percentComplete) {},
    success: function (html, statusText, xhr, $form) {
        obj = $.parseJSON(html);
        if (obj.status) {
            window.location.reload();
            toastr["success"](obj.success);
            var feed_id = $("#shared_feed_id").val();
            $("#share_an_update_form_popup")[0].reset();
            $("#image_preview_container_main_popup").slideUp(1000, function () {
                $("#shared_feed_id").val("");
                $("#feed_image_img_popup").attr("src", "");
            });
            $("#share_popup").modal("hide");
            $('*[data-feed-id="' + feed_id + '"]').find(".no-of-shares-container").html(obj.shares_count);
        } else {
            toastr["error"](obj.error);
        }
    },
    complete: function (xhr) {
        removeOverlay();
        return false;
    }
});

function loadLikersSharedBy(url, showLoader, appendORReplace) {
    $.ajax({
        type: 'POST',
        url: url,
        beforeSend: function () {
            if (showLoader) {
                addOverlay();
            }
        },
        complete: function () {
            if (showLoader) {
                removeOverlay();
            }
        },
        dataType: 'json',
        success: function (data) {
            if (data.status) {
                if ("r" == appendORReplace) {
                    $("#all_users_list").html(data.likers);
                } else {
                    $("#all_users_list").find("li.load-more").remove();
                    $("#all_users_list").append(data.likers);
                }
                $("#users_list_popup").modal();
            } else {
                toastr['error'](data.error);
            }
        }
    });
}
$("#users_list_container").mCustomScrollbar({
    callbacks: {
        onTotalScroll: function () {
            url = $("#all_users_list").find("li.load-more a").attr('href');
            if (url) {
                loadLikersSharedBy(url, false, "a");
            }
        },
        onTotalScrollOffset: 200
    }
});
$(document).on("click", ".likes", function () {
    var no_of_likes = parseInt($(this).find(".no-of-likes-container").html());
    if (no_of_likes > 0) {
        var feed_box = $(this).parents(".post-cell");
        var feed_id = feed_box.data("feed-id");
        var url = "<?php echo SITE_URL; ?>getLikers/feed_id/" + feed_id + "/currentPage/1";
        loadLikersSharedBy(url, true, "r");
    }
});
$(document).on("click", ".shares", function () {
    var no_of_shares = parseInt($(this).find(".no-of-shares-container").html());
    if (no_of_shares > 0) {
        var feed_box = $(this).parents(".post-cell");
        var feed_id = feed_box.data("feed-id");
        var url = "<?php echo SITE_URL; ?>getSharedBy/feed_id/" + feed_id + "/currentPage/1";
        loadLikersSharedBy(url, true, "r");
    }
});
/*$(document).on("click", ".like-unlike", function () {
    var feed_box = $(this).parents(".post-cell");
    var feed_id = feed_box.data("feed-id");
    $.ajax({
        type: 'POST',
        url: "%LIKE_UNLIKE_URL%",
        data: {
            action: 'like_unlike',
            feed_id: feed_id
        },
        dataType: 'json',
        success: function (data) {
            if (data.status) {
                feed_box.find(".like-unlike").html(data.like_unlike_text);
                feed_box.find(".no-of-likes-container").html(data.like_count);
            } else {
                toastr['error'](data.error);
            }
        }
    });
});*/

function initCommentAjaxForm() {
    $(".comments-container-mcustomscroll").mCustomScrollbar({
        updateOnContentResize: true
    });
    $(".comment-txtfield").keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            $(this).parents("#post_comment_form").submit();

            return false;
        }
    });
    $(".comment-form").each(function () {
        comment_form = $(this);
        comment_form.validate({
            ignore: [],
            rules: {
                comment: {
                    required: true,
                    maxlength:150
                },
            },
            messages: {
                comment: {
                    required: lang.ERROR_COMMENT_ENTER_YOUR_COMMENT,
                    maxlength:"{LBL_LIMIT_CHAR}"                
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
                var feed_box = $(form).parents(".post-cell");
                var feed_id = feed_box.data("feed-id");
                var comment = feed_box.find("#comment").val();
                feed_box.find("#comment").val('');
                feed_box.find("button[type='submit']").attr('disabled',true);

                $.ajax({
                    type: 'POST',
                    url: "%POST_COMMENT_URL%",
                    data: {
                        action: 'postComment',
                        feed_id: feed_id,
                        comment: comment,
                        sess_user_id: '<?php echo $_SESSION["user_id"];?>',
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status) {
                            $(form)[0].reset();
                            feed_box.find(".no-of-comments-container").html(data.comments_count);
                            feed_box.find(".comments-container").append(data.comment_html);
                            feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("update");
                            feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("scrollTo", "bottom", {
                                scrollInertia: 2500,
                                scrollEasing: "easeInOutQuad"
                            });
                        } else {
                            toastr['error'](data.error);
                        }
                        feed_box.find("button[type='submit']").attr('disabled',false);
                        return false;
                    }
                });
            }
        });
    });
    readMore();

}
$(document).on("click", ".load-more-comments-link", function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    var feed_box = $(this).parents(".post-cell");
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        success: function (data) {
            if (data.status) {
                feed_box.find(".comments-container").find(".load-more-comments").remove();
                feed_box.find(".no-of-comments-container").html(data.comments_count);
                feed_box.find(".comments-container").prepend(data.comments_html);
                feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("update");
                feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("scrollTo", "top", {
                    scrollInertia: 2500,
                    scrollEasing: "easeInOutQuad"
                });
            } else {
                toastr['error'](data.error);
            }
            return false;
        }
    });
});
$(document).on("click", ".delete-comment", function () {
    var comment_div = $(this).parents(".comment-main");
    var comment_id = comment_div.data("comment-id");
    var feed_box = comment_div.parents(".post-cell");
    var feed_id = feed_box.data("feed-id");
    var bootBoxCallback = function (result) {
        if (result) {
            $.ajax({
                type: 'POST',
                url: "%DELETE_COMMENT_URL%",
                dataType: 'json',
                data: {
                    action: "deleteComment",
                    comment_id: comment_id,
                    feed_id: feed_id
                },
                beforeSend: function () {
                    addOverlay();
                },
                success: function (data) {
                    if (data.status) {
                        comment_div.fadeOut(1500, function () {
                            comment_div.remove();
                            feed_box.find(".no-of-comments-container").html(data.comments_count);
                        });
                        toastr['success'](data.success);
                        return false;
                    } else {
                        toastr['error'](data.error);
                    }
                    return false;
                },
                complete: function (xhr) {
                    removeOverlay();
                    return false;
                }
            });
        }
    }
    initBootBox("{ALERT_DELETE_COMMENT}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_THIS_COMMENT}", bootBoxCallback);
});
$(document).on("click", ".delete-feed", function () {
    var feed_li = $(this).parents(".post-cell");
    var feed_id = feed_li.data("feed-id");
    var bootBoxCallback = function (result) {
        if (result) {
            $.ajax({
                type: 'POST',
                url: "%DELETE_FEED_URL%",
                dataType: 'json',
                data: {
                    action: "deletePost",
                    feed_id: feed_id
                },
                beforeSend: function () {
                    addOverlay();
                },
                success: function (data) {
                    if (data.status) {
                        feed_li.fadeOut(1500, function () {
                            feed_li.remove();
                        });
                        toastr['success'](data.success);
                        return false;
                    } else {
                        toastr['error'](data.error);
                    }
                    return false;
                },
                complete: function (xhr) {
                    removeOverlay();
                    return false;
                }
            });
        }
    }
    initBootBox("{ALERT_DELETE_POST}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_THIS_POST}", bootBoxCallback);
});
$(document).ready(function () {
    initCommentAjaxForm();
});
</script>