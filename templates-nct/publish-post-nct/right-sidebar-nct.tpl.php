<div id="white-box">
	<form class=" " name="publish_post_form" id="publish_post_form" action="%PUBLISH_POST_URL%" method="post" enctype="multipart/form-data">
        <div class="post-img cf" style="background-image: url({SITE_THEME_IMG}post-bg-img.jpg)">
            
            <div id="select_feed_image_container" class="upload-img-post  %FEED_IMAGE_SELECT_CONTAINER_HIDDEN_CLASS%">
                <div class="upload-bg-bx">
                    <figure>
                    <img src="{SITE_THEME_IMG}upload-bg.png" alt="Post Image" id="img_post">
                    </figure>
                    <label class="post-label">{LBL_SELECT_IMAGE}</label>
                </div>
                <div class="border-box">
                    <input type="file" id="feed_image" name="feed_image" />
                </div>
            </div>

            <div id="feed_image_preview_container" class="banner-image-preview-contianer %FEED_IMAGE_PREVIEW_CONTAINER_HIDDEN_CLASS%">
                <img id="feed_image_img" src="%FEED_IMAGE_URL%" alt="%POST_TITLE%" />
                <div class="upper-img-overlap">
                <div class="banner_actions">
                    <a href="javascript:void(0);" title="Change" id="change_feed_image" class="icon-edit"></a>
                    <a href="javascript:void(0);" title="Remove" data-id="%POST_ID%" id="remove_feed_image" class="icon-close"></a>
                </div>
                </div>
            </div>
        </div>
		<div class="col-sm-12 form-group cf">
			<label>{LBL_POST_TITLE} <sup>*</sup></label>
			<input type="text" name="post_title" id="post_title" placeholder="{LBL_PLACEHOLDER_TITLE}" value="%POST_TITLE%" >
		</div>
		<div class="col-sm-12 form-group cf">
			<label >{LBL_POST_DESC} <sup>*</sup></label>
			<textarea placeholder="" rows="4" name="post_description" id="post_description">%POST_DESC%</textarea>
		</div>

		<script>
			CKEDITOR.replace( 'post_description' );
		</script>

		<input type="hidden" id="shared_with" name="shared_with" value="p" />
		<input type="hidden" id="post_id" name="post_id" value="%POST_ID%" />
        <input type="hidden" name="type" value="a"/>
		<div class="col-sm-12 form-group cf">
		    <button type="submit" class="blue-btn %SAVE_BTN_CLASS%" id="save_post" name="save_post">{LBL_SAVE}</button>
            <div class="space-mdl"></div>
		    <button type="submit" class="outer-blue-btn" id="publish_post" name="publish_post">{LBL_PUBLISH}</button>
		</div>
	</form>
</div>
<script type="text/javascript">
    $(document).on('click','#save_post', function() { 
        var desc = CKEDITOR.instances.post_description.getData();
        desc=desc.replace(/(<([^>]+)>)/ig,"");
        desc=desc.replace(/(&nbsp;)/ig,"");
        var check;
        if($('#feed_image_img').attr('src') == ''){
                check='y';            
        }else{
            check='n';
        }
         $('#post_description').rules('add', {  
            required: function() {
                            CKEDITOR.instances.post_description.updateElement();
                            if($("#feed_image").is(':blank') && check=='y'){
                                    return true;
                            }else if(check=='y'){
                                return true;
                            }
                            else{
                              return false;
                            }
                        },
            messages : { required : '{ERROR_POST_SOME_CONTENT_IMAGE_PUBLISH}' }

            
        });
       
        $('#publish_post_form').valid();
    });
    $(document).on('click','#publish_post', function() { 
        
        //alert(desc);
        var desc = CKEDITOR.instances.post_description.getData();
        desc=desc.replace(/(<([^>]+)>)/ig,"");
        desc=desc.replace(/(&nbsp;)/ig,"");
                              
         $('#post_description').rules('add', {  
                required:function() {
                            //CKEDITOR.instances.post_description.updateElement();
                            if(CKEDITOR.instances.post_description.updateElement() || desc != ''){
                                return true;
                            }else{
                                return false;
                            }

                        },
            messages : { required : '{LBL_ENTER_POST_DESC}' }

        });
        
        $('#publish_post_form').valid();
    });
  // $(document).on('click','#save_post', function() { 
        /*var editor_val =$("#post_description").val(); 
        //CKEDITOR.instances.post_description.document.getBody().getChild(0).getText() ;
        //alert(editor_val);
        if(editor_val != ''){
   */         $("#publish_post_form").validate({
                ignore: [],
                rules: {
                    post_title: {
                        required: true,
                        pagenm:true
                    },
                    /*post_description: {
                        required: function() {
                            CKEDITOR.instances.post_description.updateElement();
                            if($("#feed_image").is(':blank')){
                                    return true;
                            }
                            else{
                              return false;
                            }
                        },
                    }*/
                },
                messages: {
                    post_title: {
                        required: "{LBL_ENTER_POST_TITLE} ."
                    },
                    /*post_description: {
                        required: "{LBL_ENTER_POST_DESC} ."
                    }*/
                },
                highlight: function(element) {
                    //$(element).addClass('has-error');

                    if (!$(element).is("select")) {
                        $(element).removeClass("valid-input").addClass("has-error");
                    } else {
                        $(element).parents(".form-group").find(".bootstrap-select").removeClass("valid-input").addClass("has-error");
                    }
                    if($(element).attr("name") == "post_description") {
                        $("#cke_post_description").addClass("has-error");
                    }
                },
                unhighlight: function(element) {
                    //$(element).closest('.form-group').removeClass('has-error');
                    if (!$(element).is("select")) {
                        $(element).removeClass('has-error').removeClass("has-error").addClass('valid-input');
                    } else {
                        $(element).parents(".form-group").find(".bootstrap-select").removeClass('has-error').addClass('valid-input');
                    }
                    if($(element).attr("name") == "post_description") {
                        $("#cke_post_description").removeClass("has-error");
                    }
                },
                errorPlacement: function(error, element) {
                    $(element).parent("div").append(error);
                },
                submitHandler: function(form) {
                    return true;
                }
            });

        /*}else{
            //alert(1);
        }*/
   // });




    $.validator.addMethod('pagenm', function (value, element) {
            return /^[a-zA-Z0-9][a-zA-Z0-9\-\_\s' ]*$/.test(value);
        }, '{ENTER_VALID_PUBLISH}');
	
</script>