<div class="inner-main " >
    <div class="notifi-sec cf">
        <div class="container fade fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-1"></div>
                <div class="col-sm-12 col-md-10">
                    <div class="gen-wht-bx in-heading cf">
                        <h3>{LBL_NOTIFICATIONS}</h3>
                        <div class="full-width notifications-main notify-list" id="notification">
                            <ul class="notify-list"><?php echo $this->notification; ?></ul>
                            <!-- <div id="pagination_container" class="my-paging"><?php //echo $this->pagination; ?></div> -->
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
                    $("#notification").find(".load-more-data").remove();
                    $("#notification").append(data.content);
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
                    $("#notification").find(".view-more-btn a").remove();
                    $("#notification").append(data.content);

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
            url: "<?php echo SITE_URL; ?>getNotificationAjax",
            data: {
                page: page,
                action: 'getNotification'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                $("#notification").html(data);
                if (page > 1) {
                    //console.log(1);
                    window.history.pushState("", "Title", "?page=" + page);
                } else {
                    //console.log(2);
                    window.history.pushState("", "Title", "?page=" + page);
                }
            }
        });
    });
</script>