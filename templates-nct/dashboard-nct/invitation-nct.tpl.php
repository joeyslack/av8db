<div class="inner-main">
    <div class="my-update-sec cf">

        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-3 hidden-sm hidden-xs">
                  %PROFILE_DATA%
                </div>
                <div class="col-sm-12 col-md-9">
                    <div class="nav-menu in-menu">
                        <div class="container">
                            <ul id="submenu" class="sub-menu">
                                <li>
                                    <a href="javascript:void(0);" class="switch_my_following_company      %RECEIVE_INVITATION_CLASS%" title="{LBL_GROUP_RECEIVED_INVITATION}" data-action="getPendingInvitations" data-endpoint="getPendingInvitations" id="getPendingInvitations">
                                        {LBL_GROUP_RECEIVED_INVITATION}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="switch_my_following_company %SENT_INVITATION_CLASS%" title="{SENT_INVITATION}" data-action="getSentInvitations" data-endpoint="getSentInvitations" id="getSentInvitations">
                                        {SENT_INVITATION}
                                    </a>
                                </li>


                            </ul>
                            </div>
                        </div>
                    <div  id="invitation" class="flex-row">
                            <?php echo $this->invitation; ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>






<!-- <div class="inner-main " >
    <div class="my-conn-sec cf">
        <div class="container fade fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-1"></div>
                <div class="col-sm-12 col-md-10">
                    <h1>{LBL_YOUR_INVITATIONS} </h1>
                    <div class="srch-conn-bx">
                        <div class="form-group cf">
                        <select name="invitation_type" id="invitation_type" class="form-control selectpicker show-tick">
                            <option value="Pending">{LBL_PENDING}</option>
                            <option value="Sent">{LBL_SENT}</option>
                        </select>
                        </div>
                    </div>
                    <div  class="flex-row" id="invitation">
                        <?php// echo $this->invitation; ?>

                    </div>
                </div>
                <div class="col-sm-12 col-md-1"></div>
            </div>

        </div>
    </div>
</div> -->
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">Languages<i class="fa fa-angle-down"></i></a>
</div>

<script type="text/javascript">
    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        var invitation_type = $("#invitation_type").val();
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>get" + invitation_type + "InvitationsAjax",
            data: {
                page: page,
                action: "get" + invitation_type + "Invitations"
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                $("#invitation").html(data);
                if (page > 1) {
                    //console.log(1);
                    //window.history.pushState("", "Title",  "?page=" + page);
                } else {
                    //console.log(2);
                    //window.history.pushState("", "Title",  "?page=" + page);
                }
            }
        });
    });
    /*$(document).on('change', "#invitation_type", function() {
        var action = '';
        if ($(this).val() == 'Pending') {
            action = "getPendingInvitations";
        } else {
            action = "getSentInvitations";
        }

        $.ajax({
            type: 'POST',
            url: "<?php //echo SITE_URL; ?>" + action,
            data: {action: action},
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {$("#invitation").html(data);}
        });
    });*/
    $(document).on("click", ".switch_my_following_company", function() {
        if (!$(this).hasClass("active")) {
            action = $(this).data("endpoint");

            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>" + action,
                data: {action: action},
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    $("#invitation").html(data);
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_my_following_company");
                        current_element.removeClass("active");
                    });
                    $("#"+action).addClass("active");

                }
            });



           // getFeeds(1, action, $(this));
        } else {
            toastr['error']("You are on the same page you are trying to view.");
        }
    });






    $(document).on('click', "#approve_invitation", function() {
        var parents_li = $(this).parents('div.test');
        var user_id = $(this).data('value');
        var action = $(this).attr('id');
        performInvitationAction(parents_li, user_id, action);
    });
    $(document).on('click', "#deny_invitation", function() {
        var parents_li = $(this).parents('div.test');
        var user_id = $(this).data('value');
        var action = $(this).attr('id');
        performInvitationAction(parents_li, user_id, action);
    });
    $(document).on('click', "#cancel_request", function() {
        var parents_li = $(this).parents('div.test');
        var user_id = $(this).data('value');
        var action = $(this).attr('id');
        performInvitationAction(parents_li, user_id, action);
    });
    function performInvitationAction(parents_li, user_id, action) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>" + action,
            data: {
                user_id: user_id,
                action: action
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
                    $("#invitation").find(".load-more-data").remove();
                    $("#invitation").append(data.content);
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
                    $("#invitation").find(".view-more-btn a").remove();
                    $("#invitation").append(data.content);

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
</script>