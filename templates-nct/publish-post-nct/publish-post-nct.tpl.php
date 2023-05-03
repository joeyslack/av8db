<!--Content Start-->
<div class="inner-main">
    <div class="publish-post-sec cf">
        <div class="container fade fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-1">
                    
                </div>
                <div class="col-sm-12 col-md-10">
                   <div class="row">
                     <div class="col-sm-4 col-md-4">
                        <div class="crete-post-bx cf">
                            <a href="javascript:void(0);" title="{ADD_NEW_POST}" id="add_new_post">
                                {ADD_NEW_POST}<i class="icon-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="gen-wht-bx in-heading">
                            <div id="left_sidebar_container" class="saved-list msg-left-scroll mCustomScrollbar">
                                <h3>{SAVED_POST}</h3>
                                <ul class="" id="left_sidebar_content">
                                    <?php echo $this->left_sidebar; ?>
                                </ul>
                            </div>
                        </div>
                     </div>
                     <div class="col-sm-8 col-md-8">
                        <div class="gen-wht-bx cf">
                        <div id="right_sidebar_content">
                            <?php echo $this->right_sidebar; ?>
                        </div> 
                        </div>
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

     var postPageCount = 2;

    $left_sidebar_container = $("#left_sidebar_container");

    $left_sidebar_container.mCustomScrollbar({
        scrollInertia: 1000,
        callbacks: {
            onTotalScroll: function() {
                loadMorePost(postPageCount);
                postPageCount++;
            }
        }
    });

    function loadMorePost(postPageCount) {
        $.ajax({
            url: "<?php echo SITE_URL; ?>getPreviousPosts",
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            data: {
                action: "getPreviousPosts",
                currentPage: postPageCount
            },
            success: function (data) {
                if(data != null){
                    if(data.status) {
                        $(".left-msg-row").append(data.messages);
                    } else {
                        
                    }
    
                }
                            },
            error: function (jq, status, message) {
                //alert(message);
            }
        });
    }

    

    //$("#publish_post_form").validate(validateOptions);
    
    var ajaxFormOptions = {
        beforeSend: function() {
            addOverlay();
        },
        uploadProgress: function(event, position, total, percentComplete) {

        },
        success: function(html, statusText, xhr, $form) {
            obj = $.parseJSON(html);
            if (obj.status) {
                toastr["success"]("{LBL_POST_ADDED}");
                if(obj.post_type=='s'){
                    $("#publish_post_form")[0].reset();
                    window.location.reload();
                }else{
                    window.location="{SITE_URL}";

                }
                

                if(typeof(obj.feed_id) != "undefined" && obj.feed_id !== null) {
                    $('#post_id').val(obj.feed_id);
                }
            } else {
                toastr["error"](obj.error);
            }
        },
        complete: function(xhr) {
            removeOverlay();
            return false;
        }
    };

    $(document).ready(function() {
        $("#publish_post_form").ajaxForm(ajaxFormOptions);
    });

    $(document).on('click', "#remove_saved_post", function(event) {
          event.stopPropagation();

        post_id = $(this).data('value');
        closest_li = $(this).closest('li');

        var bootBoxCallback = function(result) {
            if(result) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>remove_saved_post",
                    data: {
                        post_id: post_id,
                        action: 'remove_saved_post'
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
                            $("#publish_post_form").ajaxForm(ajaxFormOptions);
                            closest_li.fadeOut(500, function() {
                                closest_li.remove();
                            });
                            $('#publish_post_form')[0].reset();

                        } else {
                            toastr['error'](data.error);
                        }
                    }
                });
            }
        }
        
        initBootBox("{DELETE_POST}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_THIS_POST}", bootBoxCallback);

    });

    $(document).on('click', ".post_title_li", function() {
        $(".post_title_li" ).each(function() {
            $(this).removeClass( "active-left-msg" );
        });
        $(this).addClass("active-left-msg");

        var post_id = $(this).data('post-id');
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>edit_post",
            data: {
                action: 'edit_post',
                post_id: post_id
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                $("#right_sidebar_content").html(data);
                $("#publish_post_form").ajaxForm(ajaxFormOptions);
            }
        });
    });

    // $(document).on("click", "#change_feed_image", function() {
    //     $("#feed_image").click();
    // });

    $(document).on("change", "#feed_image", function(e) {
        var file1 = this.files[0];
        var _this = $(this);
        var value = _this.val();
        var allowedFiles = ["jpg", "jpeg", "png"];
        var extension = value.split('.').pop().toLowerCase();
        const fi = document.getElementById('feed_image');
        if (jQuery.inArray(extension, allowedFiles) !== -1) {
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
        } else {
            toastr['error']("{ERROR_YOU_CAN_ONLY_UPDLOAD_JPG_PNG}");
            $("#feed_image").val("");
        }
    });

    $(document).on("click", "#remove_feed_image", function() {
        
        var post_id = $(this).data("id");
        
        var bootBoxCallback = function(result) {
            if(result) {
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo SITE_URL; ?>removeFeedImage",
                    data: {
                        action: 'removeImage',
                        post_id: post_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        
                    }
                });

                $("#select_feed_image_container").removeClass("hidden");
                $("#feed_image_img").attr("src", "");
                $("#feed_image_preview_container").addClass("hidden");
                $(".post-img").css("min-height",280);

                
            }
        }
        
        initBootBox("{REMOVE_POST_IMAGE}", "{ALERT_REMOVE_POST_IMAGE}", bootBoxCallback);
        
    });

    $(document).on("change", "#feed_image", function(e) {
        var file = this.files[0];
        var _this = $(this);
        var value = _this.val();
        var allowedFiles = ["jpg", "jpeg", "png"];
        var extension = value.split('.').pop().toLowerCase();
        if (jQuery.inArray(extension, allowedFiles) !== -1) {
            
            showFeedImage(file);
        } else {
            toastr['error']("{ERROR_YOU_CAN_ONLY_UPDLOAD_JPG_PNG}");
            $("#feed_image").val("");


        }
    });

    /*************************************************************************************/

    function showFeedImage(file) {
        readFile(file, function(e) {

            var image = new Image();
            image.src = e.target.result;

            image.onload = function() {
                // access image size here 
                width = this.width;
                height = this.height;

                aspectRatio = width / height;
                //if (aspectRatio == 3) {
                    var height_img=0;
                    $("#select_feed_image_container").addClass("hidden");
                    $("#feed_image_img").attr("src", this.src).load (function(){
                        height_img=this.height;

                    });
                    

                    $(".post-img").css("min-height",height_img);
                    $("#feed_image_preview_container").removeClass("hidden");
                /*} else {
                    $("#feed_image").val("");
                    toastr["error"]("{ERROR_NOTI_VALID_SIZE_IMAGE_PUBLISH_POST}");
                }*/

            };

        });
    }

    $(document).on('click', "#add_new_post", function() {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>add_new_post",
            data: {
                action: 'add_new_post'
            },
            beforeSend: function() {
                addOverlay();
            },
            complete: function() {
                removeOverlay();
            },
            dataType: 'json',
            success: function(data) {
                $("#right_sidebar_content").html(data);
                $("#publish_post_form").ajaxForm(ajaxFormOptions);
                height = $('#white-box').offset().top;
                scrolWithAnimation(height);
            }
        });
    }); 
</script>