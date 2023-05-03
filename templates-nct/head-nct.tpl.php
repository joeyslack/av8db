<noscript>
<div style="position:relative;z-index:9999;background-color:#F90; border:#666; font-size:22px; padding:15px; text-align:center"> <strong>For the best performance and user experience, please enable javascript.</strong> </div>
</noscript>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">

<link rel="canonical" href="{CANONICAL_URL}" />
<title><?php echo $this->title; ?></title>
<meta name="generator" content="ConnectIn 2.0" />
<meta name="author" content="NCrypted" />
<meta name="copyright" content="NCrypted Technologies Pvt. Ltd." />
<meta name="google-signin-client_id" content="{GOOGLE_CLIENT_ID}" />
<?php echo $this->metaTag; ?>

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet" defer>
<?php if($this->module!='home-nct'){?>

<link href="{SITE_THEME_CSS}jquery-ui.min.css" rel="stylesheet" type="text/css" />
<?php } ?>

<link href="{SITE_THEME_CSS}bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- <link href="{SITE_THEME_CSS}bootstrap-theme.min.css" rel="stylesheet" type="text/css" /> -->
<link href="{SITE_THEME_CSS}select/bootstrap-select.css" rel="stylesheet" type="text/css" defer />

<link href="{SITE_THEME_CSS}font-awesome.min.css" rel="stylesheet" type="text/css" defer />
<link href="{SITE_PLUGIN}bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />

<link href="{SITE_THEME_CSS}developer-nct.css?v=1.1" rel="stylesheet" type="text/css" defer />
<link href="{SITE_PLUGIN}fancybox/jquery.fancybox.css?v=2.1.5" rel="stylesheet" type="text/css" defer />

<!-- <link href="{SITE_THEME_CSS}style-footer-nct.css?v=1.1" rel="stylesheet" type="text/css" /> -->
<?php if($this->module!='home-nct'){?>
<link href="{SITE_THEME_CSS}animate.min.css" rel="stylesheet" type="text/css"  defer />
<link href="{SITE_THEME_CSS}owl.carousel.css?v=1.2" rel="stylesheet" type="text/css" defer />
<link href="{SITE_THEME_CSS}scroll/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css" defer />
<?php } ?>

<!-- <link href="{SITE_THEME_CSS}style-nct-8-3-18.css?v=1.1" rel="stylesheet" type="text/css" /> -->
<link href="{SITE_THEME_CSS}style-nct.css?v=1.2" rel="stylesheet" type="text/css" />
<link href="{SITE_THEME_CSS}responsive.css?v=1.2" rel="stylesheet" type="text/css" />

<!--[if lt IE 9]><script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script><script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
<?php echo load_css($this->styles); ?>
<script language="javascript" type="text/javascript">SITE_URL = '{SITE_URL}';var FB_APP_ID = '{FB_APP_ID}';SITE_THEME_IMG = '{SITE_THEME_IMG}';var google_client_id = '{GOOGLE_CLIENT_ID}';var MODULE = '<?php echo $this->module; ?>';</script>
<link rel="shortcut icon" type="image/ico" href="<?php echo ('' != SITE_FAVICON) ? 'https://storage.googleapis.com/av8db/site-images-nct/' . SITE_FAVICON : ""; ?>" />
<!--[if lt IE 9]><script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script><script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

<script type="text/javascript" src="{SITE_THEME_JS}jquery.min.js" ></script>

<script type="text/javascript" src="{SITE_THEME_JS}jquery-ui.min.js" charset="UTF-8"  ></script>
<script type="text/javascript" src="{SITE_THEME_JS}bootstrap.min.js" ></script>
<?php if($this->module!='home-nct'){?>

<script type="text/javascript" src="{SITE_THEME_JS}placeholder.js" ></script>
<?php } ?>

<script type="text/javascript" src="{SITE_THEME_JS}select/bootstrap-select.js" ></script>
<script type="text/javascript" src="{SITE_PLUGIN}bootstrap-toastr/toastr.min.js" ></script>
<script type="text/javascript" src="{SITE_JS}jquery.validate.js" ></script>
<?php if($this->module!='home-nct'){?>

<script type="text/javascript" src="{SITE_JS}bootbox.min.js" ></script>
<?php } ?>

<script type="text/javascript" src="{SITE_JS}jquery.form.js" ></script>
<script type="text/javascript" src="{SITE_PLUGIN}fancybox/jquery.fancybox.js?v=2.1.5" ></script>

<script type="text/javascript" src="{SITE_THEME_JS}jquery.numeric.min.js" ></script>
<script type="text/javascript" src="{SITE_THEME_JS}custom-nct.js" ></script>
<!-- <script type="text/javascript" src="{SITE_THEME_JS}placeholdem.min.js"></script> -->
<?php if($this->module!='home-nct'){?>
<script type="text/javascript" src="{SITE_JS}jquery.jscroll.min.js" ></script>

<script type="text/javascript" src="{SITE_THEME_JS}owl.carousel.js" ></script>

<script type="text/javascript" src="{SITE_THEME_JS}scroll/jquery.mCustomScrollbar.concat.min.js" ></script>
<script type="text/javascript" src="{SITE_JS}readmore.js" ></script>
<?php } ?>

<script type="text/javascript" src="{SITE_THEME_JS}general.js" ></script>




<?php 
if($this->module!='home-nct'){

echo load_js($this->scripts);
echo includeSharingJs($this->include_sharing_js);
}
echo includeGoogleLoginJS($this->include_google_login_js); ?>
<script type="text/javascript">
    var select_enter_js = '{ALERT_SELECT_ENTER_JS}';
    var select_or_more_js = '{ALERT_SELECT_OR_MORE_JS}';
    var alert_searching_js = '{ALERT_SEARCHING_JS}';
    var alert_js_result_could_not_be_loaded = '{ALERT_JS_RESULT_COULD_NOT_BE_LOADED}';
    toastr.options={"closeButton":true,"debug":false,"positionClass":"toast-top-full-width","onclick":null,"showDuration":"300","hideDuration":"1000","timeOut":"10000","extendedTimeOut":"1000","showEasing":"swing","hideEasing":"linear","showMethod":"fadeIn","hideMethod":"fadeOut"};
    var oldConfirm = bootbox.confirm;
    bootbox.confirm = function(options) {
        if(options.reorder) {
            options = $.extend({}, options, {show: false});
            var $dialog = oldConfirm(options),
                $cancel = $dialog.find('[data-bb-handler="cancel"]');
            $cancel.parent().append($cancel.detach());
            $dialog.modal('show');
        } else {
            oldConfirm(options);
        }
    };
    function addOverlay() {
        $(".loader").show();
    }
    function removeOverlay() {
        setTimeout(function() { $(".loader").fadeOut(); }, 1000);
    }
    function scrolWithAnimation(height) {$('html,body').animate({scrollTop:height},1500);}
    function readFile(file, callback){
        var reader = new FileReader();
        reader.onload = callback
        reader.readAsDataURL(file);
    }

    $(window).scroll(function() {
        $(".fadeIn").each(function() {
            var objectTop = $(this).offset().top;
            var objectBottom = $(this).offset().top + $(this).outerHeight();
            var windowBottom = $(window).scrollTop() + $(window).innerHeight();
            if(objectTop<windowBottom){if($(this).css("opacity")==0){$(this).fadeTo(500,1);}}
        });
    });
    function loadExtScript(src, callback) {var s = document.createElement('script');s.src = src;document.body.appendChild(s);s.onload = callback;}
    function initializeTootltip(){$('[data-toggle="tooltip"]').tooltip();}
    function initBootBox(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons:{cancel:{label:'{LBL_CANCEL}',className:'btn blue-btn cancel-btn '},confirm:{label:'{LBL_DELETE}',className:'btn blue-btn'}},
            callback: callbackFn
        });
    }
    function initBootBox_flag(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons:{cancel:{label:'{LBL_CANCEL}',className:'btn blue-btn cancel-btn '},confirm:{label:'{LBL_COM_DET_YES}',className:'btn blue-btn'}},
            callback: callbackFn
        });
    }
    function initBootBox_company(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons:{cancel:{label:'{LBL_CANCEL}',className:'btn blue-btn cancel-btn '},confirm:{label:'{LBL_UNFOLLOW}',className:'btn blue-btn'}},
            callback: callbackFn
        });
    }
    function initBootBox_group(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons:{cancel:{label:'{LBL_CANCEL}',className:'btn blue-btn cancel-btn '},confirm:{label:'{LBL_LEAVE_GROUP}',className:'btn blue-btn'}},
            callback: callbackFn
        });
    }
    function initBootBox_group_withdraw(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons:{cancel:{label:'{LBL_CANCEL}',className:'btn blue-btn cancel-btn '},confirm:{label:'{LBL_WITHDRAW_REQUEST}',className:'btn blue-btn'}},
            callback: callbackFn
        });
    }
    function loadMore(url, data, callbackfn) {
        $.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            data: data,
            success: callbackfn
        });
        return false;
    }
    function showElements() {
        $(".fadeIn").each(function() {
            var objectTop = $(this).offset().top;
            var objectBottom = $(this).offset().top + $(this).outerHeight();
            var windowBottom = $(window).scrollTop() + $(window).innerHeight();
            if (objectTop < windowBottom) {
                if ($(this).css("opacity") == 0) {
                    $(this).fadeTo(500, 1);
                }
            }
        });
    }
    $(document).ready(function() {
        <?php  echo ( ( $this->include_google_maps_js ) ? includeGoogleMapsJS($this->init_autocomplete) : '' ); echo $this->toastr_message; ?>
        $(window).scroll();
        var myselect = "div.form-control select";
        $(myselect).each(function(index){$(this).closest("div.root").find("span.value").text($(this).val());});
        $(document).on("change",myselect,function(e){$(this).closest("div.root").find("span.value").text($(this).val());});
        $(document).on("focus", ".dropdown-nav a.has-submenu", function(e) {
            var dropdownMenu = $(this).parent(".dropdown-nav").find(".hover-dropdown");
            dropdownMenu.css("opacity", 1);
            dropdownMenu.css("max-height", "200px");
        });
        $(document).on("focusout", ".dropdown-nav a.has-submenu", function(e) {
            var dropdownMenu = $(this).parent(".dropdown-nav").find(".hover-dropdown");
            dropdownMenu.css("opacity", "");
            dropdownMenu.css("max-height", "");
        });
        $(document).on("focus", ".hover-dropdown li a", function(e) {
            var dropdownMenu = $(this).parent("li").parent(".hover-dropdown").parent(".dropdown-nav").find(".hover-dropdown");
            dropdownMenu.css("opacity", 1);
            dropdownMenu.css("max-height", "200px");
        });
        $(document).on("focusout", ".hover-dropdown li a", function(e) {
            var dropdownMenu = $(this).parent("li").parent(".hover-dropdown").parent(".dropdown-nav").find(".hover-dropdown");
            dropdownMenu.css("opacity", "");
            dropdownMenu.css("max-height", "");
        });
    });

    //like-unlike in feed
    $(document).on("click", ".like-unlike", function () {
        var feed_box = $(this).parents(".post-cell");
        var feed_id = feed_box.data("feed-id");
        var url = "<?php echo SITE_URL?>"+"like-unlike";
        var likeClass = feed_box.find(".like-unlike i").attr('class');
        var html;
        if(likeClass == 'fa fa-thumbs-up'){
            title='{LBL_UNLIKE}';          
            html = '<i class="fa fa-thumbs-down"></i> ';
        }else{
            title='{LBL_COM_DET_LIKE}';
            html = '<i class="fa fa-thumbs-up"></i> ';
        }

        feed_box.find(".like-unlike").html(html);
        feed_box.find(".like-unlike").attr("title",title);

        feed_box.find("a.like-button").removeClass('like-unlike');
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                action: 'like_unlike',
                feed_id: feed_id
            },
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    feed_box.find("a.like-button").addClass('like-unlike');

                    feed_box.find(".like-unlike").html();
                    feed_box.find(".no-of-likes-container").html(data.like_count);
                } else {
                    toastr['error'](data.error);
                }
            }
        });
    });
    //delete comment
    $(document).on("click", ".del_comment", function () {
        var comment_id=$(this).data("id");
        var feed_box = $(this).parents(".comment-main");
        var feed_id = $(this).parents(".post-cell").data("feed-id");

        var bootBoxCallback = function(result) {
            if (result) {

                var url = "<?php echo SITE_URL?>"+"del_comment";
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        action: 'del_comment',
                        comment_id: comment_id,
                        feed_id:feed_id,
                        sess_user_id: "<?php echo $_SESSION['user_id'];?>",
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.status) {
                            toastr['success']("{COMMENT_DELETE_SUCCESSFULLY}");
                            feed_box.remove();
                            $("#"+feed_id).find(".no-of-comments-container").html(data.comments_count);


                        } else {
                            toastr['error']("{ERROR_SOME_ISSUE_TRY_LATER}");
                        }
                    }
                });
            }
        }
        initBootBox("{ALERT_DELETE_COMMENT}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_THIS_COMMENT}", bootBoxCallback);

        

    });
    //edit comment
    $(document).on("click",".edit_comment",function(){
        var comment_id=$(this).data('id');
        var comment=$("#comment_edit_"+comment_id).text();
        var title='<div class="input-group"><input type="text" class="form-control border-field comment-txtfield update_comment" name="comment" id="comment_'+comment_id+'" placeholder="{LBL_ADD_A_COMMENT}" value="'+comment+'"><span class="input-group-addon" id="basic-addon1"><button class="btn small-btn comment_post"  title="{LBL_COMMENT}" data-id='+comment_id+'><i class="icon-plane"></i></button></span></div>';    
        $('#comment_edit_'+comment_id).html(title);

    });
    $(document).on("click",".comment_post",function(){

        var comment_update_id=$(this).data('id');
        var comment=$("#comment_"+comment_update_id).val();
        var update_date=new Date();
        if(comment != '' && comment.length <= 150){
          var url = "<?php echo SITE_URL?>"+"edit_comment";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    action: 'edit_comment',
                    comment_id: comment_update_id,
                    comment:comment,
                    date:update_date

                },
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        toastr['success']("{EDIT_COMMENT_SUCCESS}");
                        $('#comment_edit_'+comment_update_id).html('<p>'+comment+'</p>');
                        $("#comment_"+comment_update_id).val('');
                        $("#time_ago_"+comment_update_id).text(data.date);

                    } else {
                        toastr['error']("{ERROR_SOME_ISSUE_TRY_LATER}");
                    }
                }
            });  
        }else{
                if(comment.length > 150){
                  toastr['error']("{LBL_LIMIT_CHAR}");

                }else{
                  toastr['error']("{ADD_COMMENT_MSG}");

                }
        }
        
        
    });

</script>
<?php
global $lId;

?>
<script src="<?php echo SITE_INC.'language-nct/'.$lId; ?>.js" ></script>