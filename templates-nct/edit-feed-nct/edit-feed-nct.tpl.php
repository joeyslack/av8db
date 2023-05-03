<div class="inner-main">
   <div class="feed-sec cf">
      <div class="container">
         <div class="row">
            <div class="col-sm-1 col-md-3">
              %MEMBERSHIP_PLAN%
            </div>
            <div class="col-sm-10 col-md-9">
               <div class="fade fadeIn">
                  <div class="gen-wht-bx cf">
                     <div class="dash-share-bx">
                        <form class="share-form fade fadeIn collapse" name="share_an_update_form" id="share_an_update_form" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
                        <div class="in-feed-pro-table">
                            <div class="in-img-70">
                                %USER_PROFILE_PICTURE%
                            </div>
                            <div class="feed-pro-dtl-info">
                            <h2>
                                <a href="%USER_PROFILE_URL%" title="%USER_NAME_FULL%">%USER_NAME_FULL%</a>
                            </h2>
                            <span class="feed-headline">%HEADLINE%</span>
                            <small>%TIME_AGO%</small>
                            </div>
                        </div>
                           <div class="post-article-bx %GROUP_TITLE%">
                              <input type="text" name="post_title" id="post_title" placeholder="{LBL_GRP_DTL_ENTER_TITLE}*" class="border-field txt-area-lg" value="%POST_TITLE%">
                           </div>

                           <div class="post-article-bx">
                              <textarea name="description" class="border-field txt-area-lg" id="description" placeholder="{LBL_WHATS_ON_YOUR_MIND}">%DESCRIPTION%</textarea>
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
                            <div class="%CLASS%">
                                <img  src="%IMG%" class="" height=100 width=100 id="img" />
                                <a href="javascript:void(0);" title="{LBL_REMOVE_IMAGE}" class="image_del" data-id="%FEED_ID%"><i class="icon-close"></i></a>
                            </div>
                            <div class="video_remove vid-gallary-bx">
                                <div  class="vid-upload">%POST_VIDEO%</div>
                              <div class="close-img">
                                <a href="javascript:void(0);" title="{LBL_REMOVE_VIDEO}" class="video_del %VIDEO_CLASS%" data-id="%FEED_ID%"><i class="icon-close"></i></a>
                              </div>
                            </div>
                           <div id="image_preview_container_main" class="galary-bx" style="display:none">
                              <div class="view-upload">
                                 <div id="image_preview_container">
                                    <figure><img id="feed_image_img" src="" /></figure>
                                    <span id="feed_image_name"></span>
                                    <div class="close-img">
                                       <a href="javascript:void(0);" title="{LBL_REMOVE_IMAGE}" class="remove-feed-image"><i class="icon-close"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="vid-gallary-bx">
                              <div id="video_show" class="vid-upload"></div>
                              <div class="close-img">
                                 <a href="javascript:void(0);" title="{LBL_REMOVE_VIDEO}" class="remove-feed-video"><i class="icon-close"></i></a>
                              </div>
                           </div>
                           <div class="dash-btm-share">
                              <ul>
                                 <li class="">
                                    <div id="share_update_file_upload" class="upload-vid-bx" title="{UPLOAD_AN_IMAGE}">
                                       <i class="icon-img"></i>
                                       <input type="file" id="feed_image" name="feed_image" title="Upload image" accept="image/*" />
                                    </div>
                                 </li>
                                 <li class="%HIDE_CLASS%">
                                    <div class="upload-vid-bx" title="{UPLOAD_AN_IMAGE}" id="upload_video">
                                       <a data-toggle="modal" data-target="#uploadvideo">
                                       <i class="icon-video"></i>
                                       </a>
                                       <!-- <input type="file" id="feed_image" name="feed_image" title="Upload image" accept="video/*" /> -->
                                       <input type="hidden" id="hidden_video" name="videocode" value="" />
                                    </div>
                                 </li>
                                 <li>
                                    <div class="public-select %HIDE_CLASS%">
                                       <select id="shared_with" name="shared_with" class="selectpicker">
                                          <option value="p">{LBL_SHARE_WITH_PUBLIC}</option>
                                          <option value="c">{LBL_SHARE_WITH_CONNECTIONS}</option>
                                       </select>
                                    </div>
                                 </li>
                              </ul>
                              <input type="hidden" name="feedid" value="%FEED_ID%">
                              <button type="submit" class="blue-btn" id="share_an_update" name="share_an_update">{LBL_GENERAL_SHARE}</button>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
            <!-- <div class="col-sm-1 col-md-2"></div> -->
         </div>
      </div>
   </div>
</div>
<div class="footer-toggle">
   <a href="#toggle-footer-section" id="footer-toggle-link">{LBL_LANGUAGE}<i class="fa fa-angle-down"></i></a>
</div>
<div class="modal" id="uploadvideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close val_video" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{LBL_UPLOAD_VIDEO}</h4>
      </div>
      <div  class="modal-body">
            <textarea name="video" class="border-field txt-area-lg" id="video" placeholder="{LBL_PLZ_ENTER_VIDEO}"></textarea>

      </div>
       <div class="list-form cf">
                <div class="col-sm-12 col-md-12 form-group cf">
                    <button type="submit" class="blue-btn" name="uploadvideo_btn" id="uploadvideo_btn" data-dismiss="modal">{LBL_SUBMIT}</button>
                    <button type="button" class="outer-red-btn val_video" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
     $('.remove-feed-video').hide();

    //  $(document).on("change", "#feed_image", function(e) {
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
    $(document).on("click", ".image_del", function() {
        var feedid=$(this).data('id');
        $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>remove_edit_image",
                data: {
                    feedid: feedid,
                    action: 'remove_image'
                },
                beforeSend: function() {
                    addOverlay();
                },
                complete: function() {
                    removeOverlay();
                },
                dataType: 'json',
                success: function(data) {
                   
                }

        });
        toastr['success']("{MSG_IMG_UPLOAD}");
        $('#img').remove();
        $('.image_del').remove();

    });
    $(document).on("click", ".video_del", function() {
        var feedid=$(this).data('id');
        $.ajax({
                type: 'POST',
                url: "<?php echo SITE_URL; ?>remove_edit_video",
                data: {
                    feedid: feedid,
                    action: 'remove_video'
                },
                beforeSend: function() {
                    addOverlay();
                },
                complete: function() {
                    removeOverlay();
                },
                dataType: 'json',
                success: function(data) {
                   
                }

        });
        toastr['success']("{MSG_VIDEO_UPLOAD}");
        $('.video_remove').remove();
                        

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
                //$("#img").attr("src", this.src);

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
                buttons:{ok:{label:'OK',className:'btn blue-btn cancel-btn '}},
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
        ignore: ':hidden',
        rules: {
            description: {
                required: function(element) {
                    var check;
                    if($('#img').attr('src') == ''){
                          if($('iframe').length <=0){

                            check='y';            
                          }else{
                            check='n';
                          }
                    }else{
                        check='n';
                    }
                    if($("#hidden_video").is(':blank') && $("#feed_image").is(':blank') && check=='y'){
                      return true;
                    }
                    else{
                      
                      return false;
                    }
                },
                noSpace:{
                  depends: function(element) {
                    var check;
                    if($('#img').attr('src') == ''){
                          if($('iframe').length <=0){

                            check='y';            
                          }else{
                            check='n';
                          }
                    }else{
                        check='n';
                    }
                    
                    if($("#hidden_video").is(':blank') && $("#feed_image").is(':blank') && check=='y'){
                    
                      return true;
                    }
                    else{
                      return false;
                    }
                  },
              },
              

            },
            post_title: {
                required: true
              },

        },
        messages: {
            description: {
                required: "{ERROR_POST_SOME_CONTENT_IMAGE}"
            },
            post_title: {
                required: "{LBL_ENTER_POST_TITLE}"
            },
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
                toastr["success"]("{ERROR_POST_UPDATED_SUCCESSFULLY}");
                window.location="{SITE_URL}";
                /*$("#share_an_update_form")[0].reset();
                $("#image_preview_container_main").slideUp(1000, function() {
                    $("#feed_image_img").attr("src", "");
                });*/
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
</script>