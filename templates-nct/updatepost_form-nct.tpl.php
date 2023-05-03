      <div class="container">
         <div class="row">
           <!--  <div class="col-sm-1 col-md-3">
            </div> -->
            <div class="col-sm-10 col-md-12">
               <div class="fade fadeIn">
                  <div class="gen-wht-bx cf">
                     <div class="dash-share-bx">
                        <form class="share-form fade fadeIn collapse" name="share_an_update_form" id="share_an_update_form" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
                  <ul class="share-post-tabs">
                    <li>
                      <div class="share-update-nm"> <i class="icon-s-plane"></i> {LBL_SHARE_AN_UPDATE} </div>
                    </li>
                    <!-- <li>
                      <div class="article-nm"> <a href="{SITE_URL}publish-post" title="{LBL_POST_AN_UPDATE}" target="_blank"> <i class="icon-article"></i> {LBL_PUBLISH_A_POST} </a> </div>
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
                        <div class="upload-vid-bx" title="{UPLOAD_AN_IMAGE}" id="upload_video"> <a data-toggle="modal" data-target="#uploadvideo"> <i class="icon-video"></i> </a> 
                          <!-- <input type="file" id="feed_image" name="feed_image" title="Upload image" accept="video/*" /> -->
                          <input type="hidden" id="hidden_video" name="videocode" value=""/>
                        </div>
                      </li>
                      <li>
                        <div class="public-select">
                          <select id="shared_with" name="shared_with" class="selectpicker">
                            <option value="p">{LBL_SHARE_WITH_PUBLIC}</option>
                            <option value="c">{LBL_SHARE_WITH_CONNECTIONS}</option>
                          </select>
                        </div>
                      </li>
                    </ul>
                    <button type="submit" class="blue-btn" id="share_an_update" name="share_an_update">{LBL_GENERAL_SHARE}</button>
                  </div>
                </form>                     </div>
                  </div>
               </div>
            </div>
            <!-- <div class="col-sm-1 col-md-2"></div> -->
         </div>
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

     $(document).on("change", "#feed_image", function(e) {
        var file = this.files[0];
        showFeedImage(file);
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
        if(val.match(/(<iframe.+?<\/iframe>)/g)){
             $('#hidden_video').val(val);
             $('#video_show').html(val);
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
               //window.location.reload();
              window.location.href ="{SITE_URL}dashboard";

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
    });
        })
</script>