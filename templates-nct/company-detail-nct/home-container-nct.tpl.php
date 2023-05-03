<div class="white-box no-pad" >
    <div class="detail-img-box">
        <div class="gen-wht-bx cf">
            <div class="cover-banner-comp">
            %COVER_IMG%
            </div>
            <div class="company-dtl-view">
                <div class="lft-comp-img">
                    <a title="%COMPANY_NAME%" href="javascript:void(0);">
                    %COMPANY_LOGO_URL%
                            </a>
                </div>
                <div class="in-comp-rgt-view">
                    <h4><a href="javascript:void(0);" title="%COMPANY_NAME%" class="blue-color">%COMPANY_NAME% </a></h4>
                    <div class="add-exp-bx">
                        %COMPANY_EDIT_URL%
                    </div>
                <h5>%INDUSTRY_NAME%</h5>
                <div class="addr-bx %LOCATION_HIDE%"><i class="icon-map"></i>{LBL_COM_DET_HEADQUARTER}: %COMPANY_LOCATION%</div>
                <div class="addr-bx"><i class="icon-email"></i>%COMPANY_EMAIL%
                <span class="%HIDE_WEB%"><a href="%WEBSITE_OF_COMPANY%" target="_blank" title="%COMPANY_NAME%">%WEBSITE_OF_COMPANY%</a></span>
                </div>
                <div class="addr-bx %JOB_CLASS%"><i class="icon-jobs"></i>
                <a href="%JOB_URL%" %TARGET_LINK% title="{SEE_JOB}">{SEE_JOB}</a>
                </div>
                </div>
                <div class="rgt-yr-emp">
                    <p class="%YEAR_HIDE%">{LBL_COM_DET_YEAR_FOUND}<small>%FOUNDATION_YEAR%</small></p>
                    <p>{LBL_COM_DET_EMPLOYEES_SMALL} <small>%RANGE_OF_NO_OF_EMPLOYEES%</small></p>
                </div>
            </div>
            <div class="manage-del-bx comp-share-bx cf">
                <div class="share-number">
                    <span>{LBL_COM_DET_FOLLOWERS}</span>
                    <em>%COMPANY_FOLLOWERS%</em>
                </div>
                <div class="share-part">
                    %FOLLOW_COMPANY_URL%
                    <?php echo $this->share_on_social_media; ?>
                    
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="gen-wht-bx in-heading cf %DES_HIDE%">
    <h3>{LBL_EDUCATION_FORM_DESCRIPTION}</h3>
    <div class="comp-desc-in">
        <p class="word_wrap_data">%COMPANY_DESCRIPTION%</p>
    </div>
</div>
<div class="gen-wht-bx in-heading cf %DES_HIDE%">
    <h3>Location</h3>
    <div class="comp-desc-in">
        %LOCATION%
        <div id="map_canvas" style="width: 100%; height: 300px;"></div>
    </div>
    <input type="hidden" name="lat" id="lat" value="%LAT%">
    <input type="hidden" name="lng" id="lng" value="%LNG%">
</div>
<div class="gen-wht-bx in-heading cf">
    <h3>{LBL_COMPANY_DETAILS_RATE_REVIEW}</h3>
    <div class="%HIDE_RATE_REVIEW_FOR_OWNER% %HIDE_WITHOUT_LOGIN% %EDIT_USER%">
        <form id="rate_review_form" method="post" action="">
            <div class="rate">
                <input type="radio" id="star1" name="rate" value="5" />
                <label for="star1" title="text">1 star</label>
                <input type="radio" id="star2" name="rate" value="4" />
                <label for="star2" title="text">2 stars</label>
                <input type="radio" id="star3" name="rate" value="3" />
                <label for="star3" title="text">3 stars</label>
                <input type="radio" id="star4" name="rate" value="2" />
                <label for="star4" title="text">4 stars</label>
                <input type="radio" id="star5" name="rate" value="1" />
                <label for="star5" title="text">5 stars</label>
            </div>
            <div class="clearfix"></div>
            <div id="forError" class="comp-desc-in pt-0 hide"><label id="rate-error" class="error" for="rate"></label></div>
            <div class="comp-desc-in pt-0">
                <div class="">
                    <textarea placeholder="Description" name="description" id="description"></textarea>
                </div>
                <input type="hidden" name="company_id" id="company_id" value="%COMPANY_ID%">
                <div class="form-group cf">
                    <button type="submit" class="blue-btn" name="save_rate_review" id="save_rate_review">{LBL_COMPANY_DETAILS_RATE_REVIEW_SAVE} </button>
                    <button type="reset" class="blue-btn" name="cancel_rate_review" id="cancel_rate_review">{LBL_COMPANY_DETAILS_RATE_REVIEW_CANCEL} </button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12 list-review-ul">
        %GET_REVIEW_LIST%
    </div>
</div>
<div class="admin-bx-compny">
    <div class="gen-wht-bx in-heading cf">
        <div class="%SHARE_UPDATE_PANEL%" >
            <h3 class="gray-title">{LBL_COM_DET_ADMIN_CENTER}</h3>
            <form class="admin-center share-form" name="share_an_update_form" id="share_an_update_form" action="%POST_AN_UPDATE_URL%" method="post" enctype="multipart/form-data">
                <div class="col-sm-12 cf">
                    <h4 class="purple-text">{LBL_COM_DET_DRIVE_ENGAGEMENT}</h4>
                    <p>{LBL_COM_DET_DAILY_COMPANY_UPDATE_TEXT}</p>
                </div>
                <div class="post-article-bx form-group cf">
                    <textarea class="txt-area-lg" name="description" id="description" placeholder="{LBL_SHARE_AN_UPDATE}*" ></textarea>
                 
                </div>
                <div id="image_preview_container_main" class="galary-bx cf" style="display:none">
                    <div class="view-upload">
                        <div id="image_preview_container">
                            <figure>
                            <img id="feed_image_img" src="" />
                            </figure>
                            <span id="feed_image_name">test image</span>
                            <div class="close-img">
                                 <a href="javascript:void(0);" title="{LBL_REMOVE_IMAGE}" class="remove-feed-image">
                                    <i class="icon-close"></i>
                                </a>
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
                    <input type="hidden" name="company_id" id="company_id" value="%ENC_COMPANY_ID%">
                    <ul>
                        <li>
                            <div id="share_update_file_upload" class="upload-vid-bx btn-file" title="{UPLOAD_AN_IMAGE}">
                            <i class="icon-img"></i>
                            <input type="file" id="feed_image" name="feed_image" />
                            </div>
                        </li>
                        <li>
                            <div id="share_update_file_upload" class="upload-vid-bx btn-file" title="{LBL_UPLOAD_VIDEO}">
                            <a data-toggle="modal" data-target="#uploadvideo">
                                    <i class="icon-video"></i>
                            </a>
                            <input type="hidden" id="hidden_video" name="videocode" value="" />
                            </div>
                        </li>
                        <li>
                            <div class="public-select share-with-option">
                                <select id="shared_with" name="shared_with" class="selectpicker show-tick">
                                    <option value="p">{LBL_SHARE_WITH_PUBLIC}</option>
                                    <option value="c">{LBL_SHARE_WITH_CONNECTIONS}</option>
                                </select>
                            </div>
                        </li>
                    </ul>
                    <button type="submit" class="blue-btn" id="share_update_from_company" name="share_update_from_company">{LBL_COM_DET_SHARE}</button>
                </div>
                
            </form>
            <div class="clearfix"></div>
        </div>
     </div>
 </div>
<div class="in-feed-activity cf">
    <h3>{LBL_COM_DET_RECENT_ACTIVITIES}</h3>
    %FEEDS%
</div>
<div class="modal" id="uploadvideo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close val_video" data-dismiss="modal" aria-label="Close">
            <i class="icon-close"></i>
        </button>
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
$('.remove-feed-video').hide();

$('.selectpicker').selectpicker('render');
    $(document).on("change", "#feed_image", function(e) {
        var file = this.files[0];
        showFeedImage(file);
    });
    $(document).on("click", ".remove-feed-image", function() {
        $("#image_preview_container_main").slideUp(1000, function() {
            $("#feed_image_img").attr("src", "");
        });
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

    /*$("#share_an_update_form").validate({
        rules: {
            description: {
                required: function(element) {
                    if($("#hidden_video").is(':blank') && $("#feed_image").is(':blank')){
                      return true;
                    }
                    else{
                      return false;
                    }
                }
            },
        },
        messages: {
            description: {
                required: "{ERROR_POST_SOME_CONTENT_IMAGE}"
            }
        },
        highlight: function (element) {
            if (!$(element).is("select")) {
                $(element).removeClass("valid-input").addClass("has-error");
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
            }
        },
        unhighlight: function (element) {
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function (error, element) {
            $(element).parent("div").append(error);
        },
        submitHandler: function (form) {
            return true;
        }
    });*/
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
                //toastr["success"](obj.success);
                /*$("#share_an_update_form")[0].reset();
                $("#image_preview_container_main").slideUp(1000, function() {
                    $("#feed_image_img").attr("src", "");
                });*/
                window.location.reload();
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
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
    
</script>

<!-- comment textarea starts--> 
<script>
$(document).ready(function() {
    var lat=$('#lat').val();
    var lng=$('#lng').val();
    mapPinPoint(parseFloat(lat),parseFloat(lng));
    

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
function mapPinPoint(lat="",lng="",zoom_no=12){ 
    var myLatlng = { lat: lat, lng: lng };
    var map = new google.maps.Map(document.getElementById("map_canvas"), {
      zoom: zoom_no,
      center: myLatlng,
    });

    var marker = new google.maps.Marker({
        position: myLatlng,
        title: 'Selected Location',
        map: map
   });
}
</script> 
<script type="text/javascript">
    $.validator.addMethod("companyNm", function(value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\.,!$'\s]*$/.test(value);
    }, "{ERROR_MESSAGE_FOR_COMPANY_VALID_REVIEW_DESCRIPTION}");
    $("#rate_review_form").validate({
        ignore: [],
        rules: {
            description: {required: true,companyNm: true},
            rate: {required: true}
        },
        messages: {
            description: {required: "{ERROR_MESSAGE_FOR_COMPANY_REVIEW_DESCRIPTION}"},
            rate: {required: "{ERR_COMPANY_RATING_NOT_SELECTED_RATING}"}
        },
        highlight: function(element) {
            if($(element).attr("type") == "radio") {
                $('#forError').removeClass('hide');
            }
            if (!$(element).is("select")) {
                $(element).addClass("has-error");
                $(element).removeClass('valid-input');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").addClass("has-error");
            }
        },
        unhighlight: function(element) {
            if (!$(element).is("select")) {
                $(element).removeClass('has-error').addClass('valid-input');
                $(element).removeClass('has-error');
            } else {
                $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
            }
        },
        errorPlacement: function(error, element) {
            $('#forError').removeClass('hide');
            if($(element).attr("type") == "radio") {
                $('#forError label').text(error);
            }
            $(element).parent("div").append(error);
        }
    });
    $("#rate_review_form").ajaxForm({
        beforeSend: function() {addOverlay();},
        uploadProgress: function(event, position, total, percentComplete) {},
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            console.log(obj);
            if (obj.status) {
                toastr["success"](obj.success);
                window.location.href = '' + obj.redirect_url + '';
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    });
    // $("#org_rating").raty({
    //     score:2
    // });
</script>
<!-- comment textarea ends--> 