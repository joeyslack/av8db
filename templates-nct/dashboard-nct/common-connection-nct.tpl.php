<div class="inner-main ">
    <div class="connections-main">
        <div class="container fade fadeIn">
            <h1><i class="fa fa-users"></i>{LBL_DB_COMMON_CONNECTION_BETWEEN_YOU} %USER_NAME% </h1>
            <div class="clearfix"></div>
            <div class="full-width common_connection flex-row" id="common_connection">
                <!-- <ul class="connections clearfix"> --><?php echo $this->common_connection; ?><!-- </ul> -->
                <!-- <div id="pagination_container"><?php //echo $this->pagination; ?></div> -->
            </div>
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
                    $("#common_connection").find(".load-more-data").remove();
                    $("#common_connection").append(data.content);
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
                    $("#common_connection").find(".view-more-btn a").remove();
                    $("#common_connection").append(data.content);

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
    $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");

        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getCommonConnectionAjax",
            data: {
                page: page,
                user_id: <?php echo $this->user_id; ?>,
                action: 'getCommonConnection'
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                $("#common_connection").html(data);

                if (page > 1) {
                    console.log(1);
                    window.history.pushState("", "Title", "?page=" + page);
                } else {
                    console.log(2);
                    window.history.pushState("", "Title", "?page=" + page);
                }
            }
        });

    });

    $(document).on('click', "#remove_connection", function() {
        var parents_li = $(this).parents('li');
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
                beforeSend: function() {
                    addOverlay();
                },
                complete: function() {
                    removeOverlay();
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                       // toastr['success'](data.success);
                        parents_li.fadeOut(1500);
                    } else {
                        toastr['error'](data.error);
                    }

                }
            });
            }
        }            
        initBootBox("{ALERT_REMOVE_FROM_CONNECTION}", "{ALERT_ARE_YOU_SURE_YOU_WANT_TO_REMOVE_THE_CONNECTION}", bootBoxCallback);
    });


</script>