<div class="inner-main">
    <div class="job-detail-main">
        <div class="container">
            <div class="clearfix"></div>
            <div class="full-width mg-top10">
                <h2 class="sub-title clearfix">
        {LBL_SIMILAR_JOBS}                    <!--<a class="edit-link blue-color" title="View all" href="%ALL_SIMILAR_JOBS_LINK%">View all <i class="fa fa-mail-forward"></i></a>-->
                </h2>
                <div class="clearfix"></div>
                <ul class="similar-jobs-row clearfix similar_jobs_container fade fadeIn">
                    <?php echo $this->similar_jobs; ?>
                </ul>
                <div id="pagination_container">
                    <?php echo $this->pagination; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.job_apply', function() {
            var job_btn = $(this);
            job_id = $(this).data('value');

            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>saveJobApplication",
                data: {
                    job_id: job_id,
                    action: 'saveJobApplication'
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
                        //alert($(this).data('value'));
                        toastr['success'](data.msg);
                        job_btn.addClass('remove_from_job_apply');
                        job_btn.html("{LBL_WITHDRAW}");
                        job_btn.removeClass('job_apply');
                        
                        
                    } else {
                        toastr['error'](data.msg);
                    }
                }
            });
        });

        $(document).on('click', '.remove_from_job_apply', function() {
            var job_btn = $(this);
            job_id = $(this).data('value');

            var bootBoxCallback = function(result) {
                if(result){
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo SITE_URL; ?>removeJobApplication",
                        data: {
                            job_id: job_id,
                            action: 'removeJobApplication'
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
                                
                                job_btn.addClass('job_apply');
                                job_btn.html("{LBL_APPLY}");
                                job_btn.removeClass('remove_from_job_apply');
                                
                                
                            } else {
                                toastr['error'](data.msg);
                            }
                        }
                    });
                }
            }            

            initBootBox("{ALERT_DELETE_APPLIED_JOBS}", "{ALERT_SURE_WANT_TO_DELETE}", bootBoxCallback);
        });

        $(document).on('click', '.job_save', function() {
            var job_btn = $(this);
             job_id = $(this).data('value');
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>saveJob",
                data: {
                    job_id: job_id,
                    action: 'saveJob'
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
                        
                        job_btn.addClass('remove_from_job_save');
                        job_btn.html("{LBL_SAVED}");
                        job_btn.removeClass('job_save');

                        
                    } else {
                        toastr['error'](data.msg);
                    }
                }
            });   
        });

         $(document).on('click', '.remove_from_job_save', function() {
            var job_btn = $(this);
             job_id = $(this).data('value');

              var bootBoxCallback = function(result) {
                if(result) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo SITE_URL; ?>removeSavedJob",
                        data: {
                            job_id: job_id,
                            action: 'removeSavedJob'
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
                                job_btn.addClass('job_save');
                                
                                job_btn.html("{LBL_SAVE}");
                                job_btn.removeClass('remove_from_job_save');
                                
                                
                            } else {
                                toastr['error'](data.msg);
                            }
                        }
                    });
                }
            } 

            initBootBox("{ALERT_DELETE_SAVED_JOB}", "{ALERT_ARE_YOU_SURE}", bootBoxCallback);
        });
    });

     $(document).on("click", ".buttonPage", function() {
        var page = $(this).data("page");
        getSimilarJobs(page);
    });

      function getSimilarJobs(page) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>getSimilarJobs",
            data: {
                page: page,
                job_id: <?php echo $this->job_id; ?>,
                industry_id: <?php echo $this->industry_id; ?>,
                action: 'getSimilarJobs'
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
                    
                    if(page > 1) {
                        console.log(1);
                        window.history.pushState("", "Title",  "?page=" + page);    
                    } else {
                        console.log(2);
                        window.history.pushState("", "Title",  "?page=" + page);    
                    }
                    

                } else {
                    toastr['error'](data.error);
                }

            }
        });
    }

    function updatePageContent(data) {
        $(".similar_jobs_container").html(data.content);
        $("#pagination_container").html(data.pagination);

        //height = $("#submenu").offset().top;
        //scrolWithAnimation(height);
        $(window).scroll();
    }

</script>