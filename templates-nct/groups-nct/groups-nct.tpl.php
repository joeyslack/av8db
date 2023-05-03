<div class="inner-main">
    <div class="nav-menu in-menu">
        <div class="container">
            <ul id="submenu" class="sub-menu">
                <li><a href="javascript:void(0);" class="switch_my_groups %MY_GROUPS_ACTIVE_CLASS%" title="{LBL_SUB_HEADER_MY_GROUPS}" data-type="my_groups" data-endpoint="my-groups">{LBL_MY_GROUPS}</a></li>
                <li><a href="javascript:void(0);" class="switch_my_groups %JOINED_GROUPS_ACTIVE_CLASS%" title="{LBL_JOINED_GROUPS}" data-type="joined_groups" data-endpoint="joined-groups">{LBL_JOINED_GROUPS}</a></li>
            </ul>
        </div>
    </div>
    <div class="my-group-sec cf">
        <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="gen-wht-bx text-center cf fix-sidebar left-first-fix" data-spy="affix" data-offset-top="0" data-offset-bottom="30">
                    <div class="in-compny-heading fade fadeIn">
                        <h1>{LBL_GROUPS}</h1>
                        <p>{LBL_GROUP_WELCOME_TEXT}</p>
                    </div>
                    <div class="in-create-com fade fadeIn">
                        <h3>{LBL_CREATE_A_GROUP_PAGE}</h3>
                        <p>{LBL_CREATE_A_GROUP_PAGE_NOTE}</p>
                        <div>
                            <a class="blue-btn" href="{SITE_URL}create-group-form/<?php echo $_SESSION['user_id'];?>" title="{LBL_CREATE_GROUP}">{LBL_GROUP_CREATE}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3 in-fl-rgt hidden-sm hidden-xs">
               %SUBSCRIBED_MEMBERSHIP_PLAN_DETAILS%
            </div>
            <div class="col-sm-12 col-md-6">
                <div id="groups_container">%CONTENT%</div>
               <!--  <div id="pagination_container"> %PAGINATION%</div> -->
            </div>
            
        </div>
       </div>
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<script type="text/javascript">
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
                    $("#groups_container").find(".load-more-data").remove();
                    $("#groups_container").append(data.content);
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
                    $("#groups_container").find(".view-more-btn a").remove();
                    $("#groups_container").append(data.content);

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
    $(document).on("click", ".switch_my_groups", function() {
        if (!$(this).hasClass("active")) {
            type = $(this).data("type");
            getGroups(1, type, true, $(this));
        } else {
            toastr['error']("{ERROR_SAME_PAGE_TRYING_TO_VIEW}");
        }
    });
    function getGroups(page, type, tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getGroups",
            data: {
                page: page,
                type: type,
                action: 'getGroups',
                sess_user_id: '<?php echo $_SESSION['user_id']?>',
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if (tab_changed) {
                        $("#submenu li").each(function() {
                            current_element = $(this).find("a.switch_my_groups");
                            current_element.removeClass("active");
                            if (current_element.hasClass("active")) {
                            }
                        });
                        if(tab_changed) {
                            tab_element.addClass("active");
                            var endpoints = tab_element.data("endpoint");
                            window.history.pushState("", "Title", endpoints);
                        }
                    }
                    updatePageContent(data);
                    if (type == 'my_groups') {
                        search_type = "my-groups";
                    } else if(type == 'joined_groups') {
                        search_type = "joined-groups";
                    } 
                    if(page > 1) {
                        console.log(1);
                        window.history.pushState("", "Title", search_type + "?page=" + page);    
                    } else {
                        console.log(2);
                        window.history.pushState("", "Title", search_type);    
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }
    function updatePageContent(data) {
        $("#groups_container").html(data.content);
        $("#pagination_container").html(data.pagination);
        height = $("#submenu").offset().top;
        scrolWithAnimation(height);
        $(window).scroll();
    }
    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        var type = $("#submenu").find("li a.active").data("type");
        getGroups(page, type, false, '');
    });
    $(document).on("click", "#removeJoinedGroup", function() {
        var group_id = $(this).data('id');
        var page = $(".pagination .buttonPageActive").html() > 0 ? $(".pagination .buttonPageActive").html() : 1;
         $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>removeJoinedGroup",
            data: {
                page: page,
                group_id: group_id,
                action: 'removeJoinedGroup'
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
                    toastr['success'](data.msg);
                    $(".groups_" + data.id).remove();
                    if(page == 1) {
                        getGroups(page, 'joined_groups', false, '');    
                    } else {
                        getGroups(page-1, 'joined_groups', false, '');
                    }
                    
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });

    ///delete group
    $(document).on('click', ".deleteGroup", function() {
        var group_id = $(this).data("id");
        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "{SITE_URL}deleteGroup",
                    data: {
                        group_id: group_id,
                        sess_user_id: '<?php echo $_SESSION['user_id'];?>',
                        grp: 'grp',
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
    $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })

        var header = document.getElementById("membership_plan_id");
        if(header === null){
            header = document.getElementById("membership_add_plan_id");

        }
        var sticky = header.offsetTop;
        window.onscroll = function() {
            if(header != ''){
                myFunction();

            }

        };
        function myFunction() {

          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
          } else {
            header.classList.remove("sticky");
          }
        }

</script>