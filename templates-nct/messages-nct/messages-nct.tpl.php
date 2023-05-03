<!--Content Start-->
<div class="content-text"></div>
<div class="inner-main">
    <div class="message-sec cf">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-1"></div>
                <div class="col-sm-12 col-md-10">
                    <div class="gen-wht-bx msg-outer-bxs">
                        <div class="lft-msg-bxs">
                            <div class="select-msg-bx">
                                <select id="message_filter" name="message_filter" class="selectpicker show-tick bs-select-hidden" id="basic">
                                    <option value="All">{ALL}</option>
                                    <option value="Message">{MSG}</option>
                                    <option value="InMails">{LBL_INMAILS}</option>
                                </select>
                                <div class="compose-btn">
                                <a href="javascript:void(0);" title="{LBL_NEW_MESSAGE}" id="compose_message">
                                    <i class="icon-plus"></i>
                                </a>
                                </div>
                            </div>
                            <div id="conversations_container" class="msg-left-scroll mCustomScrollbar">
                                <ul class="msg-row-list left-msg-row">
                                    <?php echo $this->conversations; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="rgt-msg-bxs">
                            <div id="conversation_container">
                                <?php 
                                echo $this->single_conversation; 
                                echo $this->compose_message_form;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-1"></div>
            </div>
        </div>
    </div>
</div>
<div class="footer-toggle">
<a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>

<script type="text/javascript">
    
    $(document).on("click", ".delete-conversation", function() {
           var type = $('#message_filter').val();
           var conversation_id = $(this).data("id");
            var left_msg_cell = $(this).parents(".left-msg-cell");

           var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                url: "<?php echo SITE_MOD; ?>messages-nct/ajax.messages-nct.php",
                type: "POST",
                dataType: "json",
                data: {
                    action: "deleteConversations",conversation_id:conversation_id,type:type,currentPage: 1
                },
                success: function (data) {
                    $('.left-msg-row').html(data.messages.html);
                    
                    if(data.conversationDetail == "") {
                        $("#compose_message").click();
                    } else {
                        $('#conversation_container').html(data.conversationDetail);
                        window.history.pushState("", "Title", SITE_URL + "messaging/thread/" + data.conversation_id_encrypted);
                        initSingleConversationScrollbar();
                    }
                    left_msg_cell.fadeOut('slow');
                },
                error: function (jq, status, message) {
                    //alert(message);
                }
                }); 
              }
            }

            initBootBox("{DLT_CONVERSATION}", "{DELETE_CONVERSATION}", bootBoxCallback);
            return false;
    });
    
    $(document).on("click", "#compose_message", function() {
        $.ajax({
            url: "<?php echo SITE_URL; ?>getComposeMessageForm",
            type: "POST",
            dataType: "json",
            data: {
                action: "getComposeMessageForm"
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            success: function (data) {
                if(data.status) {
                    $("#conversation_container").html(data.form);
                    $(".left-msg-cell").each(function(e) {
                        $(this).removeClass("active-left-msg");
                    });
                    $("#message_filter").val('All');
                    $("#message_filter").selectpicker('refresh');
                    window.history.pushState("", "Title", SITE_URL+"compose-message");
                } else {
                    toastr["error"](data.error);
                }
            },
            error: function (jq, status, message) {
                //alert(message);
            }
        });
    });
    
    /* Start Single Conversation */
    function loadMessages(url) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function (data) {
                if(data.status) {

                    

                
                    //$(".msg-inbox-chat").find(".load-more-conversations-messages").after("<div id='newid_dy'></div>");
                    
                    //alert($('#msg_inbox_chat div:first-child').attr("id"))
                    var dynamic_id = '';
                    var increas = 0;
                    


                    $(".msg-inbox-chat").find(".load-more-conversations-messages").remove();
                    $(".msg-inbox-chat").prepend(data.messages);
                    
                    $( ".message-box-single" ).each(function( index ,attr ) {
                        //console.log( index + "-" + $(this).attr('id'));
                        ++increas;
                        if(increas==2)
                            dynamic_id = $(this).attr('id');
                    });
                    //$('#test').attr('id')

                    //alert($("#msg_inbox_chat:first-child").attr("id"))
                    $(".msg-right-scroll").mCustomScrollbar("scrollTo", $("#"+dynamic_id));
                    
                }
            },
            error: function (jq, status, message) {
                //alert(message);
            }
        });
    }
    
    function initSingleConversationScrollbar() {
        $(".msg-right-scroll").mCustomScrollbar({
            callbacks: {
                onTotalScrollBack: function() {
                    url = $("#conversation_messages_container").find(".load-more-conversations-messages .load-more-conversations-messages-link").attr('href');
                    if(url) {
                        loadMessages(url);
                    }
                },
                onTotalScrollOffset: 200
            }
        });
    
        $("#conversation_messages_container").mCustomScrollbar("scrollTo", "bottom");
    }
    
    
    
    /* End Single Conversation */
    
    
    function loadConversations(url) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            success: function (data) {
                $(".load-more-conversations-messages").remove();
                 $(".msg-row-list").append(data.html);
                
            },
            error: function (jq, status, message) {
                //alert(message);
            }
        });
    }
    
    function initConversationsScrollbar() {
        $(".msg-left-scroll").mCustomScrollbar({
            callbacks: {
                onTotalScroll: function() {
                    url = $(".msg-left-scroll").find(".load-more-conversations-messages .load-more-conversations-messages-link").attr('href');
                    if(url) {
                        loadConversations(url);
                    }
                },
                onTotalScrollOffset: 200
            }
        });
    }
    
    $(document).on("click", ".delete-msg", function() {
        var message_box_single = $(this).parents(".message-box-single");
        var message_id = $(this).data("message-id");
        
        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    url: "<?php echo SITE_URL; ?>deleteMessage",
                    type: "POST",
                    dataType: "json",
                    beforeSend: function() {
                        addOverlay();
                    },
                    complete: function() {
                        removeOverlay();
                    },
                    data: {
                        action: "deleteMessage",
                        message_id: message_id
                    },
                    success: function (data) {
                        //console.log(data);
                        if(data.status) {
                            message_box_single.fadeOut(800, function() {
                                message_box_single.remove();
                            });
                            //toastr["success"](data.success);
                        } else {
                            toastr["error"](data.error);

                        }
                    },
                    error: function (jq, status, message) {
                        //alert(message);
                    }
                });
            }
        }
        
        initBootBox("{LBL_DELETE_MSG}", "{DELETE_THIS_MSG}", bootBoxCallback);
        
    });
    
    $(document).on("click", ".left-msg-cell", function() {
        conversation_li = $(this);
        conversation_id = conversation_li.data("conversation");
        $.ajax({
            url: "<?php echo SITE_URL; ?>getConversation",
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            data: {
                action: "getConversation",
                conversation_id: conversation_id
            },
            success: function (data) {
                //console.log(data);
                if(data.status) {
                    $("#conversation_container").html(data.messages);
                    $(".left-msg-cell").each(function(e) {
                        $(this).removeClass("active-left-msg");
                    });
                    conversation_li.addClass("active-left-msg");
                    window.history.pushState("", "Title", SITE_URL + "messaging/thread/" + conversation_id);
                    initSingleConversationScrollbar();

                    $('html, body').animate({
            scrollTop: $("#message").offset().top
        }, 2000);
 
                    
                } else {
                    toastr["error"](data.error);

                }
            },
            error: function (jq, status, message) {
                //alert(message);
            }
        });
    });

    $(document).on("change", "#message_filter", function() {
         
        var type = $('#message_filter').val();   

        $.ajax({
            url: "<?php echo SITE_MOD; ?>messages-nct/ajax.messages-nct.php",
            type: "POST",
            dataType: "json",
            data: {
                action: "getConversations",type: type,currentPage: 1
            },
            success: function (data) {
                //alert(data.messages.html);
                if(data.status == true){
                    $('.left-msg-row').html(data.messages.html);
                    $('#conversation_container').html(data.conversationDetail);
                    initSingleConversationScrollbar();
                    if(type == 'InMails'){
                        $('.InMail').addClass('active');
                        $('.Message,.All').removeClass('active');
                    }else if(type == 'Message'){
                        $('.Message').addClass('active');
                        $('.InMail,.All').removeClass('active');
                    }else if(type == 'All'){
                        $('.All').addClass('active');
                        $('.InMail,.Message').removeClass('active');
                    }
                    /*$('html, body').animate({
                        scrollTop: $("#message").offset().top
                    }, 2000);*/
                }else{
                    $('.left-msg-row').html(data.error);
                   // $("#compose_message").click();
                   // $( "#receiver_name" ).focus();
                }
                /*if($('.msg-inbox-chat div').hasClass('load-more-conversations-messages')){
                    loadMessages('{SITE_URL}ajax/messaging/thread/MzA=/currentPage/2');
                }*/
            },
            error: function (jq, status, message) {
                //alert(message);
            }
        });
    });
    
    $(document).ready(function() {
        initConversationsScrollbar();
        initSingleConversationScrollbar();
        //$("#conversations_container").mCustomScrollbar("destroy");
        //$("#conversations_container").mCustomScrollbar('destroy');
        /*$('html, body').animate({
            scrollTop: $("#message").offset().top
        }, 2000);*/
        });
    
</script>
