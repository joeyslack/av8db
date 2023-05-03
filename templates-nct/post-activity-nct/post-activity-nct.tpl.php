
<div class="inner-main">
    <div class="my-update-sec cf">
        
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 hidden-sm hidden-xs  ">
                  %PROFILE_DATA%
                </div>
                <div class="col-sm-12 col-md-9">
                    <div class="nav-menu in-menu">
            <div class="container">
                <ul id="submenu" class="sub-menu">
                    <li>
                        <a href="javascript:void(0);" class="switch_my_following_company %RECENT_UPDATES_ACTIVE_CLASS%" title="{LBL_RECENT_UIPDATES}" data-action="recent_updates" data-endpoint="post_recent-updates">
                            {LBL_RECENT_UIPDATES}
                        </a>
                    </li>
                    <!-- <li>
                        <a href="javascript:void(0);" class="switch_my_following_company %PUBLISHED_POSTS_ACTIVE_CLASS%" title="{LBL_PUBLISHED_POSTS}" data-action="published_posts" data-endpoint="post_published-posts">
                            {LBL_PUBLISHED_POSTS}
                        </a>
                    </li> -->
                   <!--  <li>
                        <a href="javascript:void(0);" class="switch_my_following_company %SAVED_POSTS_ACTIVE_CLASS%" title="{LBL_SAVED_POSTS}" data-action="saved_posts" data-endpoint="post_saved-posts">
                            {LBL_SAVED_POSTS}
                        </a>
                    </li> -->
                    <li>
                        <a href="javascript:void(0);" class="switch_my_following_company %ALL_ACTIVITY_CLASS%" title="{LBL_ALL_ACTIVITY}" data-action="all_activity" data-endpoint="post_all_activity">
                            {LBL_ALL_ACTIVITY}
                        </a>
                    </li>
                    
                </ul>
            </div>
        </div>
                    <div id="feeds_container" class="full-width white-box fade fadeIn load-feed">
                        %FEEDS%
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<script type="text/javascript">
    
    function updatePageContent(data) {
        $("#feeds_container div.post-row").html(data.content);

        height = $("#submenu").offset().top;
        scrolWithAnimation(height);
        $(window).scroll();
        initCommentAjaxForm();
    }

    function getFeeds(page, action, clickedElement) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>ajax/"+action,
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_my_following_company");
                        current_element.removeClass("active");
                    });
                    
                    clickedElement.addClass("active");
                    var endpoints = clickedElement.data("endpoint");
                    window.history.pushState("", "Title", endpoints);

                    updatePageContent(data);
                    
                    if(page > 1) {
                        window.history.pushState("", "Title", action + "?page=" + page);    
                    } else {
                        window.history.pushState("", "Title", action);
                    }
                    
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    }
    
    $(document).on("click", ".switch_my_following_company", function() {
        if (!$(this).hasClass("active")) {
            action = $(this).data("endpoint");
            getFeeds(1, action, $(this));
        } else {
            toastr['error']("You are on the same page you are trying to view.");
        }
    });
    
    $(document).on("click", ".load_more", function(e) {
        e.preventDefault();
        var url = $(this).attr("href");
        
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#feeds_container div.post-row").find(".load-more-feeds").remove();
                    $("#feeds_container div.post-row").append(data.content);
                    
                    initCommentAjaxForm();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });
    $(document).ready(function() {
        readMore();
    });

    function loadMoreRecords(url) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#feeds_container div.post-row").find(".view-more-btn a").remove();
                    $("#feeds_container div.post-row").append(data.content);
                    initCommentAjaxForm();

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

</script>
