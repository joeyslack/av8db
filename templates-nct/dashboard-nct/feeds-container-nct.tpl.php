<div id="feeds_container" class="post-scroll"><ul class="post-row"><?php echo $this->feeds_li; ?></ul></div>
<div class="modal fade" id="likers_list_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{LBL_LIKES_POPUP_TITLE}</h4>
            </div>
            <div id="likers_list_container" class="modal-body">
                <ul id="all_likers_list" class="post-row"></ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="shared_by_list_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{LBL_SHARED_BY}</h4>
            </div>
            <div id="shared_by_list_container" class="modal-body">
                <ul id="shared_by_list" class="post-row"></ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function loadLikers(url, showLoader, appendORReplace) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {if(showLoader) {addOverlay();}},
            complete: function() {if(showLoader) {removeOverlay();}},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if("r" == appendORReplace) {
                        $("#all_likers_list").html(data.likers);
                    } else {
                        $("#all_likers_list").find("li.load-more").remove();
                        $("#all_likers_list").append(data.likers);
                    }
                    $("#likers_list_popup").modal();
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }
    $("#likers_list_container").mCustomScrollbar({
        callbacks: {
            onTotalScroll: function() {
                url = $("#all_likers_list").find("li.load-more a").attr('href');
                if(url) {
                    loadLikers(url, false, "a");
                }
            },
            onTotalScrollOffset: 200
        }
    });
    $(document).on("click", ".likes", function() {
        var no_of_likes = parseInt($(this).find(".no-of-likes-container").html());
        if (no_of_likes > 0) {
            var feed_box = $(this).parents(".post-cell");
            var feed_id = feed_box.data("feed-id");

            var url = "<?php echo SITE_URL; ?>getLikers/feed_id/" + feed_id + "/currentPage/1";
            loadLikers(url, true, "r");
        }
    });    
    $(document).on("click", ".like-unlike", function() {
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
            success: function(data) {
                if (data.status) {
                    feed_box.find(".like-unlike").html(data.like_unlike_text);
                    feed_box.find(".no-of-likes-container").html(data.like_count);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(".comments-container-mcustomscroll").mCustomScrollbar({
        updateOnContentResize: true
    });
    
    $(".comment-form").each(function() {
        comment_form = $(this);
        comment_form.validate({
            ignore: [],
            rules: {comment: {required: true},},
            messages: {comment: {required: "&nbsp; {ERROR_COMMENT_ENTER_YOUR_COMMENT}"}},
            highlight: function(element) {
                //$(element).addClass('has-error');
                if (!$(element).is("select")) {
                    $(element).removeClass("valid-input").addClass("has-error");
                } else {
                    $(element).parents(".from-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
                }
            },
            unhighlight: function(element) {
                //$(element).closest('.form-group').removeClass('has-error');
                if (!$(element).is("select")) {
                    $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
                } else {
                    $(element).parents(".from-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
                }
            },
            errorPlacement: function(error, element) {
                $(element).parent("div").append('');
            },
            submitHandler: function(form) {
                var feed_box = $(form).parents(".post-cell");
                var feed_id = feed_box.data("feed-id");
                var comment = feed_box.find("#comment").val();
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
                    success: function(data) {
                        if (data.status) {
                            $(form)[0].reset();
                            feed_box.find(".no-of-comments-container").html(data.comments_count);
                            feed_box.find(".comments-container").append(data.comment_html);
                            feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("update");
                            feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("scrollTo","bottom",{scrollInertia:2500,scrollEasing:"easeInOutQuad"});                            
                        } else {
                            toastr['error'](data.error);
                        }
                        return false;
                    }
                });
            }
        });
    });
    $(document).on("click", ".load-more-comments-link", function(e) {
        e.preventDefault();
        var url = $(this).attr("href");
        var feed_box = $(this).parents(".post-cell");
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    feed_box.find(".comments-container").find(".load-more-comments").remove();
                    feed_box.find(".no-of-comments-container").html(data.comments_count);
                    feed_box.find(".comments-container").prepend(data.comments_html);
                    feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("update");
                    feed_box.find(".comments-container-mcustomscroll").mCustomScrollbar("scrollTo","top",{scrollInertia:2500,scrollEasing:"easeInOutQuad"});                            
                } else {
                    toastr['error'](data.error);
                }
                return false;
            }
        });
    });
</script>