<div class="inner-main ">
   <div class="pay-his-sec cf">
      <div class="container fade fadeIn" style="opacity: 1;">
          <div class="row">
            <div class="col-sm-12 col-md-1"></div>
            <div class="col-sm-12 col-md-10 get_all_data">
                <h1>{LBL_PAYMENT_HISTORY}</h1>
                <div class="gen-wht-bx cf">
                    <div class="payment-history histry-table" id="no-more-tables">
                      <div class="divtable">
                        <div class="divtable-heading">
                         <div class="divtable-row">
                            <div class="divtable-head">{TRANSACTION_ID}</div>
                            <div class="divtable-head">{LBL_DATE}</div>
                            <div class="divtable-head">{LBL_AMOUNT}($)</div>
                            <div class="divtable-head">{LBL_PAYMENT_FOR}</div>
                         </div>
                        </div>
<!--                         <div id="add_payment_data">
 -->                         <?php echo $this->transactions; ?>
<!--                          </div>
 -->                        <div class="divtable-head <?php echo $this->class_msg; ?>"> <?php echo $this->message; ?></div>
                      </div>
                   </div>
                </div>
                <div id="pagination_container">
                      <?php echo $this->load; ?>
                  </div>
            </div>
            <div class="col-sm-12 col-md-1"></div>
          </div>
         
         <hr class="connections-mainn">
         <div class="clearfix"></div>
         <div class="full-width">
            <div class="fade fadeIn">
               
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
                    $(".get_all_data").find(".load-more-data").remove();
                    $(".data_pay:last").after(data.content);

                    $("#pagination_container").html(data.load);
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
                    $("#get_all_data").find(".view-more-btn a").remove();
                    $(".data_pay:last").after(data.content);
                    $("#pagination_container").html(data.load);


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
        getJobs(page, false, '');
    });

    function getJobs(page, tab_changed, tab_element) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getTransaction",
            data: {
                page: page,
                action: 'getTransaction'
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
              //alert(data);
                updatePageContent(data);
                    if(page > 1) {
                        console.log(1);
                        window.history.pushState("", "Title", "?page=" + page);    
                    } else {
                        console.log(2);
                        window.history.pushState("", "Title", "?page=" + page);    
                    }
                    

                

            }
        });
    }

    function updatePageContent(data) {
        //  alert(data);
        $(".inner-main").html(data);
        //$("#pagination_container").html(data.pagination);

        $(window).scroll();
    }

</script>
