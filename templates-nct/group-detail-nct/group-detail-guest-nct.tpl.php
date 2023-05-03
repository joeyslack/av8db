
<div class="inner-main">
    <div class="group-dtl-sec cf">
        <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-3">
                <?php echo $this->group_admin; ?>
                <div class="gen-wht-bx in-heading text-center cf fade fadeIn">
                        <div class="conection-box">
                        <h3 class="sub-title-small clearfix">{LBL_GRP_DTL_YOUR_CONNECTIONS}</h3>
                        <div id="connection_container">
                            <ul id="all_connection_list" class="conection-row mCustomScrollbar"><?php echo $this->connection; ?></ul>
                            <div class="clearfix"></div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-9">
                

                <div class="gen-wht-bx cf">
                    <?php echo $this->group_detail; ?>
                </div>
                <?php echo $this->group_dec; ?>

                <div class="gen-wht-bx fade fadeIn text-center" >
                        <br>  
                        <strong class="view-full sub-title clearfix"> {LBL_PLZ_JOIN_GROUP}</a></strong>
                        <br>
                </div>
                        
            </div>
        </div>
            
        </div>
    </div>
</div>






<script type="text/javascript">

        $("#connection_container").mCustomScrollbar({
            callbacks: {
                onTotalScroll: function() {
                    url = $("#all_connection_list").find("li.load-more a").attr('href');

                    if(url) {
                        loadConnections(url, false, "a");
                    }
                },
                onTotalScrollOffset: 200
            }
        });

        function loadConnections(url, showLoader, appendORReplace) {
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
                            $("#all_connection_list").html(data.connection);
                        } else {
                            $("#all_connection_list").find("li.load-more").remove();
                            $("#all_connection_list").append(data.connection);
                        }
                    } else {
                        toastr['error'](data.error);
                    }
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
        initBootBox_group_withdraw("{LBL_WITHDRAW_REQUEST}", "{LBL_ARE_YOU_SURE_WANT_WITHDRAW_GROUP}", bootBoxCallback);
    });
    

    $(document).ready(function() {
        var showChar = 500;
        var ellipsestext = "...";
        var moretext = "View More";
        var lesstext = "View Less";
        $('.more').each(function() {

            var content = $(this).html();

            if(content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar-1, content.length - showChar);

                var html = c + '<span class="moreelipses">'+ellipsestext+'</span>&nbsp;<span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
                $(this).html(html);
            }

        });

        $(".morelink").click(function(){
            if($(this).hasClass("less")) {
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
</script>
