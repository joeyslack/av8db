<div class="inner-main">
  <div class="dashboard-src cf">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-3 col-lg-3 hidden-sm hidden-xs">
          <div class="lft-profile-bx cf"> <?php echo $this->right_sidebar; ?> </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3 in-fl-rgt hidden-sm hidden-xs">
          <div class="rgt-renew-bx cf"> %MEMBERSHIP_PLAN%
            <div class="gen-wht-bx in-heading people_main_hide_list">
              <div class="people-know-outer">
                <h3>{LBL_DB_PEOPLE_YOU_MAY_KNOW}</h3>
                <div class="gen-owl-carousel owl-carousel owl-theme " id="people_owl">   <?php echo $this->people_you_may_know; ?> 
                </div>
              </div>
            </div>
            <div  id="suggestions_panel" class="gen-wht-bx in-heading right-sticky">
              <div class="suggetion-outer cf">
                <h3 class="sub-title-small clearfix">{LBL_SUGGESTIONS}</h3>
                <div class="nav-menu">
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#jobs_container" title="{LBL_DB_JOBS}" aria-controls="home" role="tab" class="owl-click" data-toggle="tab">{LBL_DB_JOBS}</a></li>
                    <li role="presentation"><a href="#groups_container" aria-controls="profile" role="tab"  class="owl-click" data-toggle="tab">{LBL_DB_GROUPS}</a></li>
                    <li role="presentation"><a href="#companies_container" aria-controls="messages" role="tab"  class="owl-click" data-toggle="tab">{LBL_DB_COMPANIES}</a></li>
                  </ul>
                </div>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active job_suggetion_div" id="jobs_container">
                    <ul class="info-row job_suggetion_ul gen-owl-carousel1 owl-carousel owl-theme" id="owl1">
                      <!-- <div class="gen-owl-carousel1 owl-carousel owl-theme"> --> 
                        <?php echo $this->job_suggestions; ?> 
                      <!-- </div> -->
                    </ul>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="groups_container">
                    <ul class="info-row group_suggetion_ul gen-owl-carousel2 owl-carousel owl-theme" id="owl2">
                      <?php echo $this->group_suggestions; ?>
                    </ul>
                  </div>
                  <div role="tabpanel" class="tab-pane" id="companies_container">
                    <ul class="info-row company_suggetion_ul gen-owl-carousel3 owl-carousel owl-theme" id="owl3">
                      <!-- <div class="gen-owl-carousel3 owl-carousel owl-theme"> --> 
                      <?php echo $this->company_suggestions; ?> 
                      <!-- </div> -->
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
          <div class="mdl-list-bx cf">
            <div class="gen-wht-bx cf">
              <div class="dash-share-bx">
                <form class="share-form fade fadeIn collapse" name="share_an_update_form" id="share_an_update_form" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
                  <ul class="share-post-tabs">
                    <li>
                      <div class="share-update-nm"> <i class="icon-s-plane"></i> {LBL_SHARE_AN_UPDATE} </div>
                    </li>
                    <!-- <li>
                      <div class="article-nm"> <a href="%PUBLISH_POST_URL%" title="{LBL_POST_AN_UPDATE}" target="_blank"> <i class="icon-article"></i> {LBL_PUBLISH_A_POST} </a> </div>
                    </li> -->
                  </ul>
                  <div class="post-article-bx">
                    <textarea name="description" class="border-field txt-area-lg" id="description" value="{LBL_WHATS_ON_YOUR_MIND}" placeholder="{LBL_WHATS_ON_YOUR_MIND}"></textarea>
                  </div>
                  <!-- <div id="image_preview_container_main" class="form-group white-box galary-bx" style="display:none">
                      <div class="view-upload">
                      <div id="image_preview_container">
                        <figure>
                        <img id="feed_image_img" src="" />
                        </figure>
                        </div>
                        <span id="feed_image_name"></span> <span id="feed_image_size" class="clearfix"></span> 
                        <div class="close-img">
                        <a href="javascript:void(0);" title="{LBL_REMOVE_IMAGE}"><i class="icon-close"></i></a>
                        </div>
                      </div>
                       </div> -->
                  <div id="image_preview_container_main" class="galary-bx" style="display:none">
                    <div class="view-upload">
                      <div id="image_preview_container">
                        <figure><img id="feed_image_img" src="" /></figure>
                        <span id="feed_image_name"></span>
                        <div class="close-img"> <a href="javascript:void(0);" title="{LBL_REMOVE_IMAGE}" class="remove-feed-image"><i class="icon-close"></i></a> </div>
                      </div>
                    </div>
                  </div>
                  <div class="vid-gallary-bx">
                    <div id="video_show" class="vid-upload"></div>
                    <div class="close-img"> <a href="javascript:void(0);" title="{LBL_REMOVE_VIDEO}" class="remove-feed-video"><i class="icon-close"></i></a> </div>
                  </div>
                  <div class="dash-btm-share">
                    <ul>
                      <li>
                        <div id="share_update_file_upload" class="upload-vid-bx" title="{UPLOAD_AN_IMAGE}"> <i class="icon-img"></i>
                          <input type="file" id="feed_image" name="feed_image" title="Upload image" accept="image/*" />
                        </div>
                      </li>
                      <li>
                        <div class="upload-vid-bx" title="{LBL_UPLOAD_VIDEO}" id="upload_video"> <a data-toggle="modal" data-target="#uploadvideo"> <i class="icon-video"></i> </a> 
                          <!-- <input type="file" id="feed_image" name="feed_image" title="Upload image" accept="video/*" /> -->
                          <input type="hidden" id="hidden_video" name="videocode" value="" />
                        </div>
                      </li>
                      <li>
                        <div class="public-select">
                          <select id="shared_with" name="shared_with" class="selectpicker">
                            <option value="p"><i class="icon-globe"></i> {LBL_SHARE_WITH_PUBLIC}</option>
                            <option value="c"><i class="icon-globe"></i> {LBL_SHARE_WITH_CONNECTIONS}</option>
                          </select>
                        </div>
                      </li>
                    </ul>
                    <button type="submit" class="blue-btn" id="share_an_update" name="share_an_update">{LBL_GENERAL_SHARE}</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="load-feed">%FEEDS%</div>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
<div class="footer-toggle"> <a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a> </div>
<div class="modal fade" id="visitors_list_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="icon-close"></i> </button>
        <h4 class="modal-title" id="myModalLabel">{LBL_VISITORS}</h4>
      </div>
      <div id="visitors_list_container" class="modal-body">
        <ul id="all-visitors-list" class="post-row">
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="uploadvideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close val_video" data-dismiss="modal" aria-label="Close"> <i class="icon-close"></i> </button>
        <h4 class="modal-title" id="myModalLabel">{LBL_UPLOAD_VIDEO}</h4>
      </div>
      <div  class="modal-body cf">
        <textarea name="video" class="border-field txt-area-lg" id="video" placeholder="{LBL_PLZ_ENTER_VIDEO}"></textarea>
      </div>
      <div class="list-form cf">
        <div class="col-sm-12 col-md-12 form-group cf">
          <button type="submit" class="blue-btn" name="uploadvideo_btn" id="uploadvideo_btn" data-dismiss="modal">{LBL_SUBMIT}</button>
          <div class="space-mdl"></div>
          <button type="button" class="outer-red-btn val_video" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    // $(document).on("change", "#feed_image", function(e) {
    //     var file = this.files[0];
    //     showFeedImage(file);
    // });
    $(document).on("change", "#feed_image", function(e) {
        var file1 = this.files[0];
        const fi = document.getElementById('feed_image');
        if (fi.files.length > 0) {
            for (const i = 0; i <= fi.files.length - 1; i++) {
                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                if (file >= 4096) {
                    toastr["error"]('File size is too large, please select a file less than 4 MB');
                    $('#feed_image').val('');
                }else{
                    showFeedImage(file1);
                }
            }
        }
    });

    $(document).on("click", ".remove-feed-image", function() {
        $("#image_preview_container_main").slideUp(1000, function() {
            $("#feed_image_img").attr("src", "");
        });
    });
    $(document).on("click", ".remove-feed-video", function() {
        alert(1);
        $("#video_show").hide();
        $(".remove-feed-video").hide();
        $('#hidden_video').val('');
    });
    $(document).on("click", "#uploadvideo_btn", function() {
        var val=$('#video').val();
        //val.match(/(<iframe.+?<\/iframe>)/g),l=b.length,i=0;
      if(val.match(/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/)){
             $('#hidden_video').val(val);
            
            VID_REGEX = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
            var video_id=val.match(VID_REGEX)[1];
            var html_video='<iframe width="560" height="315" src="https://www.youtube.com/embed/'+video_id+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
             $('#video_show').html(html_video);
             $('.remove-feed-video').show();
       
        }else{
            toastr.error("{LBL_ERROR_VIDEO_MSG}");
        }
        $('#video').val('');

        //  $('#uploadvideo').hide();
    });
    $(document).on("click", ".val_video", function() {
        $('#video').val('');
    });

    function showFeedImage(file) {
        readFile(file, function(e) {
            var image = new Image();
            image.src = e.target.result;
            file_name = file.name;
            image.onload = function() {
                // access image size here 
                width = this.width;
                height = this.height;
                $("#feed_image_img").attr("src", this.src);
                $("#feed_image_name").html(file_name);
                $("#feed_image_size").html(width + " X " + height);
                $("#image_preview_container_main").slideDown(1000);
            };
        });
    }

    $('#feed_image').change(function(e) {
      //$('#title').val(this.value ? this.value.match(/([\w-_]+)(?=\.)/)[0] : '');
      var fileName = e. target. files[0]. name;
      var ext = fileName.substring(fileName.lastIndexOf('.'));
      if(ext == '.png' || ext == '.jpeg' || ext == '.jpg' || ext == '.gif' || ext == '.PNG' || ext == '.JPEG' || ext == '.JPG'){

      }else{
        bootbox.alert({
                title: 'Alert',
                message: 'Only image is allowed',
                reorder: true,
                buttons:{ok:{label:'OK',className:'outer-blue-btn '}},
            });
            return false;
      }

    });
    
    jQuery.validator.addMethod("noSpace", function(value, element) { 
      var reg =/<(.|\n)*?>/g; 
      if(reg.test($('#description').val()) == true){
        return;
      }else{
              return $.trim(value); 

      }
    }, "{NO_SPACE_ALLOW_ERROR}");
    $("#share_an_update_form").validate({
        ignore: [],
        rules: {
            description: {
                required: function(element) {
                    if($("#hidden_video").is(':blank') && $("#feed_image").is(':blank')){
                      return true;
                    }
                    else{
                      return false;
                    }
                },
                noSpace:{
                  depends: function(element) {
                    if($("#hidden_video").is(':blank') && $("#feed_image").is(':blank')){
                      return true;
                    }
                    else{
                      return false;
                    }
                  },
                }
            },
        },
        messages: {
            description: {
                required: "{ERROR_POST_SOME_CONTENT_IMAGE}"
            }
        },
        highlight: function(element) {
            //$(element).addClass('has-error');
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            //$(element).closest('.form-group').removeClass('has-error');
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            if($(element).attr("type") == "checkbox") {
                $(element).parent("div").append(error);
            }
            $(element).parent("div").append(error);
        },
        
        submitHandler: function (form) {
            if ($(form).valid()) {
                return !0
            } else {
                return !1
            }
        }
    });

    $("#share_an_update_form").ajaxForm({
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"]("{LBL_POST_ADDED}");
               window.location.reload();
                $("#share_an_update_form")[0].reset();
                $("#image_preview_container_main").slideUp(1000, function() {
                    $("#feed_image_img").attr("src", "");
                });
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    $("#feeds_container").mCustomScrollbar({
        mouseWheelPixels: 200,
    });
    $(window).scroll(function() {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {}
    });
    function loadVisitors(url, showLoader, appendORReplace) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {if(showLoader) {addOverlay();}},
            complete: function() {if(showLoader) {removeOverlay();}},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    if("r" == appendORReplace) {
                        $("#all-visitors-list").html(data.visitors);
                    } else {
                        $("#all-visitors-list").find("li.load-more").remove();
                        $("#all-visitors-list").append(data.visitors);
                    }
                    
                    $("#visitors_list_popup").modal();
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }
    $("#visitors_list_container").mCustomScrollbar({
        callbacks: {
            onTotalScroll: function() {
                url = $("#all-visitors-list").find("li.load-more a").attr('href');
                if(url) {
                    loadVisitors(url, false, "a");
                }
            },
            onTotalScrollOffset: 200
        }
    });
    $(document).on("click", ".visitors", function() {
        var no_of_visitors = parseInt($(this).find(".no-of-visitors-container").html());
        if (no_of_visitors > 0) {
            var url = "<?php echo SITE_URL; ?>getVisitors/currentPage/1";
            loadVisitors(url, true, "r");
        }
    });

    function loadMoreRecords(url) {
        $.ajax({
            type: 'POST',
            url: url,
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $(".view-more-btn a").remove();
                    $(".load-feed").append(data.content);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    }

    var ajax_call = true;

    /*$('#footer-toggle-link').click(function(){
        ajax_call = false;
        $('#toggle-footer-section').toggle('slow');
        // $('html,body').animate({
        //     scrollTop: $("#toggle-footer-section").offset().top},
        //     'slow');
        $(this).find('i').toggleClass('fa-long-arrow-up fa-long-arrow-down')
        setTimeout(function(){ ajax_call=true }, 5000);
    });
*/

    window.addEventListener("scroll",onScroll);
    function onScroll(){
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
                loadMoreRecords(url);
                readMore();
            }
            
        }
    }

    
</script> 

<!-- comment textarea starts--> 

<script>
$(document).ready(function() {
    readMore();
    $('.remove-feed-video').hide();
    $('.txt-area-lg').height(30);
    $('.txt-area-lg').focus(function() {
        $(this).animate({
            'height': 110,
            'padding-top' : 8,
            'padding-right' : 10,
            'padding-bottom' : 8,
            'padding-left' : 10
        })
        $('.share-with-option').attr('style','display:block !important');
    });
    $('#share_update_file_upload').click(function() {
        $('.txt-area-lg').animate({
            'height': 110,
            'padding-top' : 8,
            'padding-right' : 10,
            'padding-bottom' : 8,
            'padding-left' : 10
        })
        $('.share-with-option').attr('style','display:block !important');
    });
    $('.txt-area-lg').blur(function() {
        $(this).animate({
            'height': 36,
            'padding-top' : 3,
            'padding-right' : 8,
            'padding-bottom' : 3,
            'padding-left' : 8
        })
    });

    
});
</script> 
<!-- comment textarea ends--> 

<script>

$(document).on("click", function(e){
    if($(e.target).is("#period_select_range_btn")){
      $("#selectPeriodRangePanel").show();
    }else{
        $("#selectPeriodRangePanel").hide();
    }
});
</script> 
<script type="text/javascript">
    $(document).on('click', ".close_job_suggestion", function() {
        closest_li = $(this).closest('li');
        closeJobSuggetion(closest_li);
    });
    $(document).on('click', ".close_company_suggestion", function() {
        closest_li = $(this).closest('li');
        closeCompanySuggetion(closest_li);
    });
    $(document).on('click', ".close_group_suggestion", function() {
        closest_li = $(this).closest('li');
        closeGroupSuggetion(closest_li);

    });
    $(document).on('click', ".close_people_you_know", function() {
        closest_li = $(this).closest('li');
        closest_li.fadeOut(500, function() {
            closest_li.remove();
        });
        if ($(".people_you_know_ul .people_you_know_li").length == 1) {
            $(".people_you_know_ul").html('<a href="<?php echo SITE_URL . "people-you-may-know" ?>" title="{LBL_VIEW_ALL}">{LBL_VIEW_ALL_SUGGESTIONS}</a>');
        }
    });
    function closeJobSuggetion(closest_li) {
        /*closest_li.fadeOut(500, function() {
            closest_li.remove();
        });*/
        $('#owl1').trigger('next.owl.carousel');
        var totalItems = $('#owl1').find('.owl-item').length;
        if ($(".job_suggetion_ul li").length == 1) {
            $(".job_suggetion_ul").html('<div class="text-center">{LBL_NO_SUGGESTIONS}</div>');
        }
    }
    $('#owl1').on('change.owl.carousel', function(e) { 
       var carousel = e.currentTarget,
           totalPageNumber = e.page.count, // count start from 1
           currentPageNumber = e.page.index + 1; // index start from 0
       if(currentPageNumber === totalPageNumber - 1){
         //console.log('last page reached!');
         $(document).on("click",'.close_job_suggestion',function(){
                   $(".job_suggetion_ul").html('<div class="text-center">{LBL_NO_SUGGESTIONS}</div>');

         });
       }
    });
    function closeCompanySuggetion(closest_li) {
        /*closest_li.fadeOut(500, function() {
            closest_li.remove();
        });*/
        $('#owl3').trigger('next.owl.carousel');

        if ($(".company_suggetion_ul li").length == 1) {
            $(".company_suggetion_ul").html('<div class="text-center">{LBL_NO_MORE_SUGGESTION}</div>');
        }
    }
    $('#owl3').on('change.owl.carousel', function(e) { 
       var carousel = e.currentTarget,
           totalPageNumber = e.page.count, // count start from 1
           currentPageNumber = e.page.index + 1; // index start from 0
       if(currentPageNumber === totalPageNumber - 1){
         //console.log('last page reached!');
         $(document).on("click",'.close_company_suggestion',function(){
            $(".company_suggetion_ul").html('<div class="text-center">{LBL_NO_MORE_SUGGESTION}</div>');

         });
       }
    });
    function closeGroupSuggetion(closest_li) {
        /*closest_li.fadeOut(500, function() {
            closest_li.remove();
        });*/
        $('#owl2').trigger('next.owl.carousel');
                if ($(".group_suggetion_ul li").length == 1) {
            $(".group_suggetion_ul").html('<div class="text-center">{LBL_NO_MORE_GROUP_SUGGESTION}</div>');
        }
    }
    $('#owl2').on('change.owl.carousel', function(e) { 
       var carousel = e.currentTarget,
           totalPageNumber = e.page.count, // count start from 1
           currentPageNumber = e.page.index + 1; // index start from 0
       if(currentPageNumber === totalPageNumber - 1){
         //console.log('last page reached!');
         $(document).on("click",'.close_group_suggestion',function(){
            $(".group_suggetion_ul").html('<div class="text-center">{LBL_NO_MORE_GROUP_SUGGESTION}</div>');

         });
       }
    });
    $(document).on('click', '#apply_job', function() {
            var job_btn = $(this);
            job_id = $(this).data('value');
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>saveJobApplication",
                data: {
                    job_id: job_id,
                    action: 'saveJobApplication'
                },
                beforeSend: function() {addOverlay();},
                complete: function() {removeOverlay();},
                dataType: 'json',
                success: function(data) {
                    if (data.status == 'true') {
                        if(data.recommanded == 'y'){    
                            toastr['success'](data.msg);
                            $('#apply_job').attr('id','remove_from_job_apply');
                            job_btn.html('{LBL_WITHDRAW}');
                            $(".no_of_applicants").html(data.no_of_applicants);
                        }else{
                            //window.location = data.url;
                            window.open(data.url, '_blank');
                        }
                    } else {
                        toastr['error'](data.msg);
                    }
                }
            });
        });
        $(document).on('click', '#remove_from_job_apply', function() {
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
                        beforeSend: function() {addOverlay();},
                        complete: function() {removeOverlay();},
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                toastr['success'](data.msg);
                                job_btn.html('{LBL_APPLY}');
                                $('#remove_from_job_apply').attr('id','apply_job');
                                $(".no_of_applicants").html(data.no_of_applicants);
                            } else {
                                toastr['error'](data.msg);
                            }
                        }
                    });
        }
        }            
        initBootBox("{ALERT_DELETE_APPLIED_JOB}", "<?php echo ARE_YOU_SURE_T0_REMOVE_APPLIED_JOB; ?>", bootBoxCallback);
        });
    $(document).on('click', '#follow_company', function() {
        var company_btn = $(this);
        company_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>followCompany",
            data: {
                company_id: company_id,
                action: 'followCompany'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    toastr['success'](data.msg);
                    $(".company_followers").html(data.follower_count + " followers");
                    company_btn.html('{LBL_UNFOLLOW}');
                    company_btn.attr('id','unfollow_company');
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on('click', '#unfollow_company', function() {
        var company_btn = $(this);
        company_id = $(this).data('value');
        var bootBoxCallback = function(result) {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>unfollowCompany",
                    data: {
                        company_id: company_id,
                        action: 'unfollowCompany'
                    },
                    beforeSend: function() {addOverlay();},
                    complete: function() {removeOverlay();},
                    dataType: 'json',
                    success: function(data) {
                        if (data.status) {
                            toastr['success'](data.success);
                            $(".company_followers").html(data.follower_count + " followers");
                            company_btn.html('{LBL_FOLLOW}');
                            company_btn.attr('id','follow_company');
                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }            
        initBootBoxForUnfollowCompany("{LBL_UNFOLLOW_COMPANY}", "{ALERT_ARE_YOU_SURE_WANT_TO_UNFOLLOW_THIS_COMPANY}", bootBoxCallback);
    });
    function initBootBoxForUnfollowCompany(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons: {
                cancel: {
                    label: 'Cancel',
                    className: 'outer-blue-btn '
                },
                confirm: {
                    label: 'Yes',
                    className: 'blue-btn'
                }               
            },
            callback: callbackFn
        });
    }
    
    $(document).on('click', "#add_connection", function() {
        user_id = $(this).data('value');
        closest_li = $(this).closest('li');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>addConnection",
            data: {
                user_id: user_id,
                action: 'addConnection'
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {

                    toastr['success'](data.msg);
                    closest_li.fadeOut(500, function() {
                        closest_li.remove();
                    });


                    if ($(".people_you_know_ul .people_you_know_li").length == 1) {
                        $(".people_you_know_ul").html('<a href="<?PHP echo SITE_URL . "people-you-may-know" ?>" title="View All">View all suggestions</a>');
                    }
                } else {
                    toastr['error'](data.msg);
                }
            }
        });
    });
    $(document).on('click', "#ask_to_join", function() {
        closest_li = $(this).closest('li');

        var group_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>ask_to_join",
            data: {
                action: 'ask_to_join',
                group_id: group_id
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                  console.log(data);
                    toastr['success'](data.success);
                    $("#ask_to_join").html('{LBL_WITHDRAW_REQUEST}');
                    $('#ask_to_join').attr('id','withdraw_request');
                    //closeGroupSuggetion(closest_li);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    $(document).on('click', "#join_group", function() {
        closest_li = $(this).closest('li');
        var group_id = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>join_group",
            data: {
                action: 'join_group',
                group_id: group_id,
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                  console.log(data);
                    toastr['success'](data.success);
                    $("#join_group").html('{LBL_LEAVE_GROUP}');
                    $('#join_group').attr('id','leave_group');
                    //closeGroupSuggetion(closest_li);
                } else {
                    toastr['error'](data.error);
                }

            }
        });
    });
   $(document).on('click', "#leave_group", function() {
      var group_id = $(this).data('value');

        var bootBoxCallback = function(result) {
        if (result) {
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>leave_group",
                data: {
                    action: 'leave_group',
                    group_id: group_id,
                    //accessibility: '%ACCESSIBILITY%'
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
      var group_id = $(this).data('value');

        var bootBoxCallback = function(result) {
        if (result) {
            $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>leave_group",
                data: {
                    action: 'leave_group',
                    group_id: group_id,
                    //accessibility: '%ACCESSIBILITY%'
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

        $('.gen-owl-carousel').owlCarousel({
            items:1,
            margin:0,
            nav:true,
            onInitialized: data_hide,


            //autoHeight:true
         });
        function data_hide(event) {
        var totalItems = $('.gen-owl-carousel').find('.owl-item').length;
        if(totalItems<=1){
                $('.gen-owl-carousel').find(".owl-controls").attr("class","hidden");

        }   

    }
    });
    
   $(document).on('click', ".close_people_you_know_list", function() {
        
        $('#people_owl').trigger('next.owl.carousel');
               
    });

    $(document).ready(function () {

        initialize_owl($('#owl1'));

        $('a[href="#jobs_container"]').on('shown.bs.tab', function () {
            initialize_owl($('#owl1'));

        }).on('hide.bs.tab', function () {
            destroy_owl($('#owl1'));
        });

        $('a[href="#groups_container"]').on('shown.bs.tab', function () {
            initialize_owl($('#owl2'));
        }).on('hide.bs.tab', function () {
            destroy_owl($('#owl2'));
        });

        $('a[href="#companies_container"]').on('shown.bs.tab', function () {
            initialize_owl($('#owl3'));
        }).on('hide.bs.tab', function () {
            destroy_owl($('#owl3'));
        });

    
    });

    function initialize_owl(el) {
        
        el.owlCarousel({

            nav: true,
            items: 1,
            margin: 10,
            onInitialized: callback,
            //loop:true,
        });
          //var totalItems = $('.tab-pane.active').find('.owl-item').length;


    }
    function callback(event) {
        var totalItems = $('.tab-pane.active').find('.owl-item').length;
        if(totalItems<=1){
                $('.tab-pane.active').find(".owl-controls").attr("class","hidden");

        }


    }
    
    
    

    function destroy_owl(el) {

      //alert(1);
      // el.data('owlCarousel').destroy();
        el.trigger("destroy.owl.carousel");
        el.find('.owl-stage-outer').children(':eq(0)').unwrap();
    }
    
 /*   $(window).scroll(function(){
      if($(window).scrollTop() > 100){
        $("#suggestions_panel").addClass("suggestions_panel");
      }

    });*/
  
  window.onscroll = function() {myFunction()};
      var header = document.getElementById("suggestions_panel");
    var sticky = header.offsetTop;
    function myFunction() {
      if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
      } else {
      header.classList.remove("sticky");
      }
    }

</script> 
<script type="text/javascript">
  $(document).on('click','.requestPostFlag',function(){
      var feed_id = $(this).attr('id');
      var user_id = "<?php echo $_SESSION['user_id'];?>";
      if(feed_id > 0){
         var bootBoxCallback = function(result) {
            if (result) {
              $.ajax({
                 type: "POST",
                 url: "<?php echo SITE_URL; ?>reportFeedPost",
                 dataType:'json',
                 data:{
                      'action':'reportFeedPost',
                      'feed_id':feed_id,
                      'user_id':user_id,
                  },
                 success: function(response)
                 {    
                  if (response.status == 'suc') {
                      toastr["success"](response.message);
                      window.location.href = '' + response.redirect_url + '';
                  }else{
                      toastr["error"](response.message);
                      window.location.href = '' + response.redirect_url + '';
                  }
                 }
              });
            }
        }
        initBootBox_flag("{ALERT_REPORT_FEED_POST}", "{ALERT_ARE_YOU_SURE_YOU_WISH_TO_FLAG_THIS_POST}", bootBoxCallback);
      }  
  });
</script>
