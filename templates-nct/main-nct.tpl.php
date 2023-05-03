<!DOCTYPE html><html lang="en"><head>%HEAD%</head><body <?php if($this->module != 'home-nct') { ?> class="gray-bg" <?php } ?>>
        <div class="loader">
        <div class="loader-bx">
         {LOADER}
         <div class="cin-loader"></div>
         </div>
        </div>
        <?php if (preg_match('/(?i)msie [5-8]/', $_SERVER['HTTP_USER_AGENT'])) { ?>
            <div class="modal fade" id="browser_compatibility_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">{LBL_NOTICE}</h4></div><div class="modal-body">{LBL_IE}</div></div></div></div>
            <script type="text/javascript">$(document).ready(function(){$("#browser_compatibility_popup").modal({backdrop:'static',keyboard:false},'show');});</script>
        <?php } ?>
        <div class="page-wrap">

    %SITE_HEADER%
    <!-- Global site tag (gtag.js) - Google Analytics -->

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113066638-1"></script>

<script>

  window.dataLayer = window.dataLayer || [];

  function gtag(){dataLayer.push(arguments);}

  gtag('js', new Date());



  gtag('config', 'UA-113066638-1');

</script>

     <!-- Global site tag (gtag.js) - Google Analytics -->
    %BODY%</div>
        %RESEND_EMAIL_VERIFICATION_POPUP%
        %FOOTER%
    </body>
</html>