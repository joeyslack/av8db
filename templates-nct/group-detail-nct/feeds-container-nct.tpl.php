<div id="feeds_container" class="post-scroll">
    <ul class="post-row">
        <?php echo $this->feeds_li; ?>
    </ul>
</div>

<div class="modal fade" id="likers_list_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{LBL_LIKES}</h4>
            </div>
            <div id="likers_list_container" class="modal-body">
                <ul id="all-likers-list" class="post-row"></ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function loadMoreRecords(url) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $(".view-more-btn a").remove();
                    $(".load-feed").append(data.content);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }

    var ajax_call = true;
   
    window.addEventListener("scroll",onScrollnew);
    
    function onScrollnew(){
        
         var height=$(window).height();

        if( /Android|webOS|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            height=window.visualViewport.height;
        }
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0) 
        {
            height=$(window).innerheight();
        }


         if (($(window).scrollTop() + height) >= $(document).height() && ajax_call==true) {


            var url = $(".view-more-btn a").attr('href');
            if(url) {

                loadMoreRecords(url);
            }
            
        }
    }

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
                        $("#all-likers-list").html(data.likers);
                    } else {
                        $("#all-likers-list").find("li.load-more").remove();
                        $("#all-likers-list").append(data.likers);
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
                url = $("#all-likers-list").find("li.load-more a").attr('href');
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

    $(document).on("click", ".shares", function() {
        var no_of_likes = parseInt($(this).find(".no-of-likes-container").html());
        if (no_of_likes > 0) {
            var feed_box = $(this).parents(".post-cell");
            var feed_id = feed_box.data("feed-id");

            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>getLikers",
                data: {
                    feed_id: feed_id,
                    currentPage: 1
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
                        $("#all-likers-list").append(data.likers);
                        $("#likers_list_popup").modal();
                    } else {
                        toastr['error'](data.error);
                    }

                }
            });
        }

    });
</script>