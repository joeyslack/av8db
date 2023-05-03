
<div class="inner-main ">
    <div class="job-detail-main">
        <div class="container fade fadeIn">
            <h1><i class="fa fa-users"></i>{LBL_JOB_APPLICANTS}</h1>
            <div class="clearfix"></div>
            <div class="full-width">
                <ul class="similar-jobs-row clearfix job_applicants_container flex-row "><?php echo $this->job_applicants; ?></ul>
<!--                 <div id="pagination_container"><?php echo $this->pagination; ?></div>
 -->            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

     $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        getJobApplicants(page);
    });

      function getJobApplicants(url) {
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                //page: page,
                job_id: <?php echo $this->job_id; ?>,
               // action: 'getJobApplicants'
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
                    updatePageContent(data);
                    
                    /*if(page > 1) {
                        console.log(1);
                        window.history.pushState("", "Title",  "?page=" + page);    
                    } else {
                        console.log(2);
                        window.history.pushState("", "Title",  "?page=" + page);    
                    }*/
                    

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

                getJobApplicants(url);
            }
            
        }
    }
        
    function updatePageContent(data) {
       // $(".job_applicants_container").html(data.content);
       // $("#pagination_container").html(data.pagination);
        $(".job_applicants_container").find(".view-more-btn a").remove();
        $(".job_applicants_container").append(data.content);

        //height = $("#submenu").offset().top;
        //scrolWithAnimation(height);
        $(window).scroll();
    }

</script>