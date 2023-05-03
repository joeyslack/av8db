<ul>
<li>
    <a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u=<?php echo CANONICAL_URL; ?>&display=popup&ref=plugin&src=share_button" class="shareOnSocialMedia" title="{LBL_SHARE_FB}">
        <i class="fa fa-facebook"></i>
    </a>
</li>
<li>
    <a href="http://twitter.com/intent/tweet?status=%JOB_TITLE% - Connectin+<?php echo CANONICAL_URL; ?>" class=" shareOnSocialMedia" title="{LBL_SHARE_TWITTER}">
        <i class="fa fa-twitter"></i>
    </a>
</li>
<!-- <li>
    <a href="https://plus.google.com/share?url=<?php //echo CANONICAL_URL; ?>" class="shareOnSocialMedia" title="{LBL_SHARE_GPLUS}">
        <i class="fa fa-google-plus"></i>
    </a>
</li> -->
<li>
    <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo CANONICAL_URL; ?>&title=%JOB_TITLE% - Connectin&source=IN/Share" class="shareOnSocialMedia" title="{LBL_SHARE_L_IN}">
        <i class="fa fa-linkedin"></i>
    </a>
</li> 
</ul>
<small>%SHARE%</small>


<script type="text/javascript">
    $(document).on('click', ".shareOnSocialMedia", function (e) {
        e.preventDefault();

        var url = $(this).attr('href');

        var width = 626;
        var height = 436;
        var l = window.screenX + (window.outerWidth - width) / 2;
        var t = window.screenY + (window.outerHeight - height) / 2;
        var winProps = ['width=' + width, 'height=' + height, 'left=' + l, 'top=' + t, 'status=no', 'resizable=yes', 'toolbar=no', 'menubar=no', 'scrollbars=yes'].join(',');
        
        window.open(url, 'Share', winProps);
    });
</script>
