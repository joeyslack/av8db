<div class="inner-main">
    <div class="group-dtl-sec cf">
        <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-3">
                <?php echo $this->group_admin; ?>
                <div class="gen-wht-bx in-heading text-center cf fade fadeIn %INVITE_MEMBER_HIDDEN%">
                        <h3>{LBL_GRP_DTL_MEMBERS_TITLE}</h3>
                        <div class="conn-outer-img">
                            <div class="member_list"><?php echo $this->group_members_list; ?></div>
                            <div class="member-nm">
                                {LBL_GRP_DTL_MEMBERS_TITLE} <em>%GROUP_MEMBERS%</em>
                            </div>
                        </div>
                        <p>{LBL_BRING_YOUR_CONVERSATION_INTO_COMUNITY}</p>
                        <!-- <div class="center-block text-center">
                            <a href="javascript:void(0);" id="invite_member_link" class="btn small-btn">Invite</a>
                        </div> -->
                        %INVITE_LINK%
                </div>
                <?php echo $this->group_dec; ?>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-9">
                <div class="nav-menu in-menu">
                    <ul id="submenu" class="detail-menu">
                        <li><a href="javascript:void(0);" class="switch_tab %NEWS_FEED_ACTIVE% news_feed" title="{LBL_GROUP_NEWS_FEED}" data-type="" data-endpoint="">{LBL_GROUP_NEWS_FEED}</a></li>
                        <li class="%GROUP_MEMBER_HIDDEN%"><a href="javascript:void(0);" class="switch_tab %GROUP_MEMBER_ACTIVE% group_member" title="{LBL_GROUP_MEMBER}" data-type="group_members" data-endpoint="group-members">{LBL_GROUP_MEMBER}</a></li>
                        <li class="%RECEIVED_INVITATION_HIDDEN%"><a href="javascript:void(0);" class="switch_tab %RECEIVED_INVITATION_ACTIVE% received_invitation" title="{LBL_GROUP_RECEIVED_INVITATION}" data-type="received_invitation" data-endpoint="received-invitation">{LBL_GROUP_RECEIVED_INVITATION}</a></li>
                    </ul>
                </div>

                <div class="gen-wht-bx cf">
                    <?php echo $this->group_detail; ?>
                </div>
                <div class="fade fadeIn" id="left_part_content">
                    <div class="load-feed"><?php echo $this->news_feed; ?></div>
                    <?php echo $this->members; ?>
                    <?php echo $this->received_invitation; ?>
                </div>
            </div>
        </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="invite_members_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo SITE_URL; ?>add-invitation" name="invite_members_form" id="invite_members_form" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{LBL_GROUP_DETAIL_INVITE_MEMBERS}</h4>
                </div>
                <div class="modal-body">
                    <div class="login_form">
                        <div class="form-group">
                            <select name="invite_members_name[]" id="invite_members_name" class=" js-example-basic-multiple multiple-members " multiple="multiple" style="width:100%;">
                            </select>
                            <!--<input type="text" id="invite_members_name" name="invite_members_name" placeholder="Start typing a name" class="form-control border-field ui-autocomplete-input" autocomplete="off">-->
                        </div>
                        <div class="space30"></div>
                    </div>
                </div>
                <input type="hidden" name="group_id" id="group_id" value="%ENCRYPTED_GROUP_ID%">
                <div class="modal-footer btn-center">
                    <button type="submit" class="blue-btn" name="send_invitation" id="send_invitation">{LBL_GROUP_DETAIL_INVITE_LABEL_SEND} </button>
                    <input type="reset" class="outer-blue-btn" name="cancel" id="cancel" data-dismiss="modal" value="{LBL_GROUP_DETAIL_INVITE_LABEL_CANCEL}" />
                </div>
            </form>
        </div>
    </div>
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
                    $("#all_members_list").find(".load-more-data").remove();
                    $("#all_members_list").find(".flex-row").append(data.content);
                   // $("#search_results_container").find(".no-results").remove();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });

    $(document).ready(function() {
        initmCustomMembers();
        //initmCustomReceivedInvitation();
        var showChar = 500;
        var ellipsestext = "...";
        var moretext = "View More";
        var lesstext = "View Less";
        $('.more').each(function() {
            var content = $(this).html();
            if (content.length > showChar) {
                var c = content.substr(0, showChar);
                var h = content.substr(showChar - 1, content.length - showChar);
                var html = c + '<span class="moreelipses">' + ellipsestext + '</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
                $(this).html(html);
            }

        });

        $(".morelink").click(function() {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });

    $(document).on('click', "#invite_member_link", function() {
        $("#invite_members_popup").modal();
        $("#invite_members_name").val('');
        $(".multiple-members").select2({
            ajax: { 
                url: "<?php echo SITE_URL; ?>getInvitationForGroups",
                dataType: 'json',
                quietMillis: 250,
                minimumInputLength: 1,
                method: 'POST',
                cache: true,
                data: function (term, page) {
                    return {
                        user_name: term.term,
                        action: 'getInvitationForGroups',
                        group_id:'%ENCRYPTED_GROUP_ID%'

                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function(obj) {
                            return { id: obj.user_id, text: obj.user_name };
                        })
                    };
                }
            }
        });
    });

     $("#invite_members_form").validate({
        ignore: [],
        rules: {
            "invite_members_name[]": {
                required: true
            },
        },
        messages: {
            "invite_members_name[]": {
                required: "{ERROR_GROUP_DETAIL_PLEASE_ENTER_INVITE_MEMBER_NAME}"
            },
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
        submitHandler: function(form) {
            return true;
        }
    });

    $("#invite_members_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                $("#invite_members_form")[0].reset();
                toastr["success"](obj.success);
                $("#invite_members_popup").modal('hide');
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });

   

    $(document).on('click', "#accept_group_invitation", function() {

        closest_li = $(this).closest('div.remove_invite');

        var group_id = $(this).data('group-id');
        var user_id = $(this).data('user-id');

        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>accept_group_invitation",
            data: {
                action: 'accept_group_invitation',
                group_id: group_id,
                user_id: user_id
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
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });
                    $(".member-nm").html(''+data.member_count.text+'<em>'+data.member_count.count+'</em>');
                    $(".member_list").html(data.member_list);
                } else {
                    toastr['error'](data.error);
                }

            }
        });

    });

    $(document).on('click', "#reject_group_invitation", function() {

        closest_li = $(this).closest('div.remove_invite');

        var group_id = $(this).data('group-id');
        var user_id = $(this).data('user-id');

        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>reject_group_invitation",
            data: {
                action: 'reject_group_invitation',
                group_id: group_id,
                user_id: user_id
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
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });

                } else {
                    toastr['error'](data.error);
                }

            }
        });

    });
    
    
    $(document).on("click", ".switch_tab", function() {
        if (!$(this).hasClass("active")) {
            if ($(this).hasClass("group_member")) {
                getGroupMember(true, $(this));
            } else if ($(this).hasClass("received_invitation")) {
                var url = "<?php echo SITE_URL; ?>getGroupInvitations/group/%ENCRYPTED_GROUP_ID%";
                getContent(url, true, $(this));
            } else {
                getNewsFeed(true, $(this));
            }

        } else {
            toastr['error']("{ERROR_YOUR_ARE_SAME_PAGE_TRYING_TO_VIEW}");
        }
    });

    function getContent(url, tab_changed, tab_element) {
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
                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");

                        if (current_element.hasClass("active")) {

                        }
                    });

                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }
                $("#left_part_content").html(data);

                initmCustomReceivedInvitation();
            }
        });
    }
    
    function initmCustomReceivedInvitation() {
        $("#received_invitation_container").mCustomScrollbar({
            callbacks: {
                onTotalScroll: function() {
                    url = $("#all_received_invitation_list").find("li.load-more a").attr('href');
                    if (url) {
                        getReceivedInvitations(url, false, "a", false, '');
                    }
                },
                onTotalScrollOffset: 200
            }
        });
    }
    
    function getNewsFeed(tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getNewsFeed",
            data: {
                group_id: $("#group_id").val(),
                action: 'getNewsFeed'
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {

                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");

                        if (current_element.hasClass("active")) {

                        }
                    });

                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }

                $("#left_part_content").html(data);

            }
        });
    }

    function getMembersContainer(url, showLoader, appendORReplace,tab_changed, tab_element) {
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

                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");

                        if (current_element.hasClass("active")) {

                        }
                    });

                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }

                if (data.status) {
                    if("r" == appendORReplace) {
                        $("#all_members_list").html(data.member);
                    } else {
                        $("#all_members_list").find("li.load-more").remove();
                        $("#all_members_list").append(data.member);
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }

    function getReceivedInvitations(url, showLoader, appendORReplace,tab_changed, tab_element) {
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

                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");

                        if (current_element.hasClass("active")) {

                        }
                    });

                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }

                if (data.status) {
                    if("r" == appendORReplace) {
                        $("#all_received_invitation_list").html(data.received_invitation);
                    } else {
                        $("#all_received_invitation_list").find("li.load-more").remove();
                        $("#all_received_invitation_list").append(data.received_invitation);
                    }
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }

    function getGroupMember(tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getGroupMember",
            data: {
                group_id: %GROUP_ID% ,
                action: 'getGroupMember'
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {

                if (tab_changed) {
                    $("#submenu li").each(function() {
                        current_element = $(this).find("a.switch_tab");
                        current_element.removeClass("active");

                        if (current_element.hasClass("active")) {

                        }
                    });

                    if (tab_changed) {
                        tab_element.addClass("active");
                        var endpoints = tab_element.data("endpoint");
                        window.history.pushState("", "Title", "?" + endpoints);
                    }
                }

                $("#left_part_content").html(data);

                initmCustomMembers();

            }
        });
    }

    function initmCustomMembers() {
        $("#member_container").mCustomScrollbar({
            callbacks: {
                onTotalScroll: function() {
                    url = $("#all_members_list").find("li.load-more a").attr('href');
                    if (url) {
                        getMembersContainer(url, false, "a", false, '');
                    }
                },
                onTotalScrollOffset: 200
            }
        });
    }

    $(document).on('click', "#ask_to_join", function() {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>ask_to_join",
            data: {
                action: 'ask_to_join',
                group_id: '%ENCRYPTED_GROUP_ID%',
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
                    $("#join_leave_group_id").html(data.html);

                    //window.location.reload();
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });

    $(document).on('click', "#join_group", function() {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>join_group",
            data: {
                action: 'join_group',
                group_id: '%ENCRYPTED_GROUP_ID%',
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
                    $("#join_leave_group_id").html(data.html);

                    window.location.reload();
                } else {
                    toastr['error'](data.error);
                }

            }
        });

    });

    $(document).on('click', "#leave_group", function() {
        var bootBoxCallback = function(result) {
        if (result) {
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>leave_group",
                data: {
                    action: 'leave_group',
                    group_id: '%ENCRYPTED_GROUP_ID%',
                    accessibility: '%ACCESSIBILITY%'
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
                        //toastr['success'](data.success);
                        $("#join_leave_group_id").html(data.html);

                        window.location.reload();
                    } else {
                        toastr['error'](data.error);
                    }

                }
            });
        }
        }
        initBootBox_group("{LBL_LEAVE_GROUP}", "{LBL_ARE_YOU_SURE_WANT_LEAVE_GROUP}", bootBoxCallback);
    });
    $(document).on('click', "#withdraw_request", function() {
        var bootBoxCallback = function(result) {
        if (result) {
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>leave_group",
                data: {
                    action: 'leave_group',
                    group_id: '%ENCRYPTED_GROUP_ID%',
                    accessibility: '%ACCESSIBILITY%'
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
                        //toastr['success'](data.success);
                        $("#join_leave_group_id").html(data.html);

                        window.location.reload();
                    } else {
                        toastr['error'](data.error);
                    }

                }
            });
        }
        }
        initBootBox_group("{LBL_WITHDRAW_REQUEST}", "{LBL_ARE_YOU_SURE_WANT_WITHDRAW_GROUP}", bootBoxCallback);
    });
    

    $(document).on('click', "#remove_group_member", function() {

        closest_li = $(this).closest('div.remove_member_add');

        var group_id = $(this).data('group-id');
        var user_id = $(this).data('user-id');

        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>remove_group_member",
            data: {
                action: 'remove_group_member',
                group_id: group_id,
                user_id: user_id
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
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });
                    $(".member-nm").html(''+data.member_count.text+'<em>'+data.member_count.count+'</em>');
                    $(".member_list").html(data.member_list);


                } else {
                    toastr['error'](data.error);
                }

            }
        });

    });
    window.onscroll = function() {myFunction()};
        var header = document.getElementById("grp_dec_id");
        var sticky = header.offsetTop;
        function myFunction() {
          if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
          } else {
            header.classList.remove("sticky");
          }
        }

</script>