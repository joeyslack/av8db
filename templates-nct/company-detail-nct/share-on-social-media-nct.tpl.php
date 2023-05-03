<ul>
<li><a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u={CANONICAL_URL}&display=popup&ref=plugin&src=share_button" class="shareOnSocialMedia" title="Share on Facebook">
    <i class="fa fa-facebook"></i>
</a></li>
<li><a href="http://twitter.com/intent/tweet?status=%COMPANY_NAME% - Connectin+{CANONICAL_URL}" class="shareOnSocialMedia" title="Share on Twitter">
    <i class="fa fa-twitter"></i>
</a></li>
<!-- <li><a href="https://plus.google.com/share?url={CANONICAL_URL}" class="shareOnSocialMedia" title="Share on Google+">
    <i class="fa fa-google-plus"></i>
</a></li> -->
<li><a href="http://www.linkedin.com/shareArticle?mini=true&url={CANONICAL_URL}&title=%COMPANY_NAME% - Connectin&source=IN/Share" class="shareOnSocialMedia" title="Share on LinkedIn">
    <i class="fa fa-linkedin"></i>
</a></li> 
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