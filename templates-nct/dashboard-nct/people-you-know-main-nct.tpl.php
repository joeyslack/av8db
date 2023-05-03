<div class="inner-main">
    <div class="my-update-sec cf">

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 hidden-sm hidden-xs">
                  %PROFILE_DATA%
                </div>
                <div class="col-sm-12 col-md-9 dash-search">

                    %NAV_MENU%
                    <div class="srch-conn-bx">
                        <div class="form-group cf">
                        <i class="icon-srch"></i>
                        <input type="text" id="searchConnection" name="searchConnection" placeholder="{LBL_SEARCH}">
                        </div>
                    </div>
                    <div  id="people_you_know" class="flex-row">
                            <?php echo $this->people_you_may_know; ?>
                            
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
    $("#searchConnection").keyup(function(){
        var keyword = $("#searchConnection").val();
        var user_id = '<?php echo $_SESSION['user_id']; ?>';
        
        if($(".switch_my_following_company").hasClass("active")){
            opt =$("#submenu").find(".active").attr('data-endpoint')
            if(opt=='getConnection'){
                action_url='searchConnection';
            }else if(opt=='getPeopleYouKnow'){

                action_url='searchPeopleyoumayknow';
            }else if(opt=='getFollowing'){
                action_url='searchFollowing';
            }else if(opt=='getFollower'){
                action_url='searchFollower';
            }

        }
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>"+action_url,
            data: {
                keyword: keyword,
                 user_id:user_id,
                 action: action_url
            },
            dataType: 'json',
            success: function(data) {
                $('#people_you_know').html(data);
                //setHeights();
            }
        });
    });
    $(document).on('click', "#add_connection", function() {
        user_id = $(this).data('value');
        closest_li = $(this).closest('div.test');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>addConnection",
            data: {
                user_id: user_id,
                action: 'addConnection'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    //alert($(this).data('value'));
                    toastr['success'](data.msg);
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on('click', ".close_people_you_know", function() {
        closest_li = $(this).closest('div.test');
        closest_li.fadeOut(500, function() {
            closest_li.remove();
        });
        if ($(".people_you_know_ul .people_you_know_li").length == 1) {
            $(".people_you_know_ul").html('<a href="{SITE_URL}people-you-may-know" title="{LBL_VIEW_ALL}">{LBL_VIEW_ALL_SUGGESTIONS}</a>');
        }
    });
    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getPeopleYouKnowAjax",
            data: {
                page: page,
                action: 'getPeopleYouKnow'
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                $("#people_you_know").html(data);

                if (page > 1) {
                    window.history.pushState("", "Title", "?page=" + page);
                } else {
                    window.history.pushState("", "Title", "?page=" + page);
                }
            }
        });

    });
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
                    $("#people_you_know ").find(".load-more-data").remove();
                    $("#people_you_know").append(data.content);
                   // $("#search_results_container").find(".no-results").remove();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });
    function loadMoreRecordfordata(url) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $("#people_you_know").find(".view-more-btn a").remove();
                    $("#people_you_know").append(data.content);

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

                loadMoreRecordfordata(url);
            }
            
        }
    }
     $(document).on("click", ".switch_my_following_company", function() {

        if (!$(this).hasClass("active")) {
            $("#searchConnection").val('');
            action = $(this).data("endpoint");
            if(action=='getConnection'){
                action_url='getConnectionAjax';
            }else if(action=='getPeopleYouKnow'){

                action_url='getPeopleYouKnowAjax';
            }else if(action=='getFollowing'){
                action_url='getFollowingAjax';
            }else if(action=='getFollower'){
                action_url='getFollowerAjax';
            }




            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>"+action_url,
                data: {action: action},
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    $("#people_you_know").html(data);
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_my_following_company");
                        current_element.removeClass("active");
                    });
                    $("#"+action).addClass("active");
                    //window.history.pushState("", "Title", action);

                }
            });



           // getFeeds(1, action, $(this));
        } else {
            toastr['error']("{ERROR_COM_DET_YOU_ARE_ON_SAME_PAGE_TYR_TO_VIEW}");
        }
    });
    $(document).on('click', "#remove_connection", function() {
        var parents_li = $(this).parents('div.test');
        var user_id = $(this).data('value');
        var bootBoxCallback = function(result) {
        if(result){
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>removeConnection",
                data: {
                    user_id: user_id,
                    action: 'removeConnection'
                },
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        toastr['success'](data.success);
                        parents_li.remove();
                    } else {
                        toastr['error'](data.error);
                    }
                }
            });
            }
        }            
        initBootBox("{ALERT_REMOVE_FROM_CONNECTION}", "{ALERT_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_THE_CONNECTION}", bootBoxCallback);
    });
    $(document).on('click', "#remove_following", function() {
        var parents_li = $(this).parents('div.test');
        var user_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>removeFollowing",
            data: {
                user_id: user_id,
                action: 'removeFollowing'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.success);
                    parents_li.remove();
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
</script>
