<div id="feeds_container" class="post-scroll"><ul class="post-row"><?php echo $this->feeds_li; ?></ul></div>
<div class="modal fade" id="likers_list_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="{LBL_COM_DET_CLOSE}"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">{LBL_COM_DET_LIKES}</h4></div><div id="likers_list_container" class="modal-body"><ul id="all_likers_list" class="post-row"></ul></div></div>
    </div>
</div>
<div class="modal fade" id="shared_by_list_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="{LBL_COM_DET_CLOSE}"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">{LBL_COM_DET_SHARED_BY}</h4></div><div id="shared_by_list_container" class="modal-body"><ul id="shared_by_list" class="post-row"></ul></div></div>
    </div>
</div>
<script type="text/javascript">
    function loadLikers(url, showLoader, appendORReplace) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {
                if(showLoader) {
                    addOverlay();
                }
            },
            complete: function() {
                if(showLoader) {
                    removeOverlay();
                }
            },
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
            url: %LIKE_UNLIKE_URL%,
            data: {
                feed_id: feed_id
            }
            beforeSend: function() {
                if(showLoader) {
                    addOverlay();
                }
            },
            complete: function() {
                if(showLoader) {
                    removeOverlay();
                }
            },
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
    });
</script>