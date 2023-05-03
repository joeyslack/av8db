<div class="clearfix"></div>
<footer id="%DASHBOARD_ID%" class="footer-sec cf">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6">
                %SITE_STATISTICS%
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="subsribe-bx cf">
                    <div class="col-md-3 col-sm-4">
                    <select name="language" id="language" title="%CURRENT_LANGAUGE%" class="selectpicker">
                          %LANGUAGES%
                    </select>
                    </div>
                    <div class="col-md-9 col-sm-8">
                    <form action="%SUBSCRIBE_URL%" method="post" name="nl_subscribe_form" id="nl_subscribe_form" enctype="multipart/form-data" novalidate>
                        <div class="input-group">
                        <input type="text" name="subscribe_email" id="subscribe_email" class="newsletter-textbox form-control" placeholder="{LBL_SUBSCRIBE_ENTER_EMAIL}*" data-error-container="#errorId" autocomplete="off" />
                        <span class="input-group-addon">
                        <button type="submit" name="submitNLForm" id="submitNLForm" class="newsletter-submit" title="{LBL_SUBSCRIBE_NEWSLETTER}"><i class="fa fa-envelope" aria-hidden="true"></i></button>
                        </span>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="static-page cf">
                        %CMS_PAGES%
                    </div>
            </div>
        </div>
    </div>
    <div class="copyright-outer cf">
        <div class="container">
            <div class="row">
                <div class="col-sm-2 col-md-2">
                <!-- <a href="http://ncrypted.net/connectin"  title="Networking Software" target="_blank" %LOGO_ATT%>
                    <img src="%NCT_LOGO_URL%" alt="Networking Software" />
                </a> -->
            </div>
            <div class="col-sm-7 col-md-7">
            <p>
            &COPY; {LBL_COPYRIGHT} {LBL_FOOTER_TXT_NCT} {LBL_RIGHTS_RESERVED} <!-- {LBL_CONTACT_US_TODAY} --> 
            <!-- <a href="https://www.ncrypted.net/connectin" rel="nofollow">{LBL_NAME_TAG}</a> | <a href="https://www.ncrypted.net/social-network-script" %LOGO_ATT% title="{LBL_SOCIAL_NETWORK_SCRIPT}" target="_blank">{LBL_SOCIAL_NETWORK_SCRIPT}</a>  -->
            </p>
            </div>
            <div class="col-sm-3 col-md-3">
            <div class="social-footer social-icons">
                <?php 
                if (FB_LINK != '') { ?><a href="<?php echo FB_LINK; ?>" title="{LBL_FIND} <?php echo SITE_NM; ?> {LBL_ON_FB}" target="_blank" class="fb"><i class="fa fa-facebook"></i></a><?php } 
                if (TWIITER_LINK != '') { ?><a href="<?php echo TWIITER_LINK; ?>" title="{LBL_FOLLOW} <?php echo SITE_NM; ?> {LBL_ON_TWITTER}" target="_blank" class="twit"><i class="fa fa-twitter"></i></a><?php } 
                if (LINKEDIN_LINK != '') { ?><a href="<?php echo LINKEDIN_LINK; ?>" title="{LBL_FOLLOW} <?php echo SITE_NM; ?> {LBL_ON_LINKDIEN}" target="_blank" class="linkedin"><i class="fa fa-linkedin"></i></a><?php } ?>
            </div>
            </div>
            </div>
            <a href="%PLAY_STORE_LINK%" target="_blank" class="%PLAY_STORE_CLS%"><img src="%PLAYSTRORE_LOGO_URL%" alt="" /></a>
            <a href="%APPLE_STORE_LINK%" target="_blank" class="%APPLE_STORE_CLS%"><img src="%APPLE_LOGO_URL%" alt="" /></a>
        </div>
    </div>
</footer>
<div>
</div>

<script>
    $(document).ready(function() {


        
        $(document).on('click', '#submitNLForm', function(e) { 
            e.preventDefault();
            $("#nl_subscribe_form").validate({
                ignore: [],
                errorClass: 'help-block',
                errorElement: 'span',
                rules: {subscribe_email: {required: true, checkEmail: true}},
                messages:{subscribe_email: {
                    required: lang.ERROR_FORGOT_ENTER_EMAIL_ADDRESS
                }},
                highlight: function(element) {$(element).removeClass("valid-input").addClass("has-error");},
                unhighlight: function(element) {$(element).removeClass("has-error").addClass("valid-input");},
                errorPlacement: function(error, element) { error.insertAfter(element);},
            });
            $("#nl_subscribe_form").ajaxForm({
                beforeSend: function() {addOverlay();},
                uploadProgress: function(event, position, total, percentComplete) {},
                success: function(html, statusText, xhr, $form) {obj = $.parseJSON(html);if (obj.status) {$("#nl_subscribe_form")[0].reset();
                    toastr["success"](obj.success);
                return false;} else {toastr["error"](obj.error);return false;}return false;},
                complete: function(xhr) {removeOverlay();return false;}
            }).submit();
        });
        $(document).on('change','#language',function(){
          var value = $(this).val();
          $.post('{SITE_URL}language/'+value,function(){
            window.location.reload();
          });
        });


        //delete post or feed
        $(document).on('click', ".delete_feed", function() {
        var feed_id = $(this).data('id');
        var $this = $(this);
        var feed_id_div = $(this).attr('data-id');
        var bootBoxCallback = function (result) {
        if (result) {
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>delete_post",
            data: {
                action: 'delete_post',
                feed_id: feed_id,
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $this.closest('.feed_post_delete').remove();
                    toastr['success'](data.success);

                } else {
                    toastr['error'](data.error);
                }
                window.location.reload();

            }
        });}
        }
        initBootBox("{ALERT_DELETE_POST}", "{ALERT_ARE_YOU_SURE_WANT_TO_DELETE_THIS_POST}", bootBoxCallback);
        });

        //publish post from share
        $(document).on('click', ".publish_post_save", function() {
        var feed_id = $(this).data('id');
        var $this = $(this);
        var feed_id_div = $(this).attr('data-id');
       
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_URL; ?>publish-post-save",
            data: {
                action: 'publish_post_save',
                feed_id: feed_id,
            },
            beforeSend: function() {addOverlay();},
            complete: function() {removeOverlay();},
            dataType: 'json',
            success: function(data) {
                if (data.status) {
                    $this.closest('.feed_post_delete').remove();
                    toastr['success'](data.success);

                } else {
                    toastr['error'](data.error);
                }
                setTimeout(function(){location.href="{SITE_URL}"}, 700);   

            }
        });
        });
     
    });
    function readMore() {
        $(".feed_des").shorten({
            "showChars" : 200,
            "moreText"  : '{LBL_COM_DET_VIEW_MORE}',
            "lessText"  : '{LBL_COM_DET_VIEW_LESS}',
        });
        }
</script>
