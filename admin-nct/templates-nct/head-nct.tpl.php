<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<?php echo $this->metaTag; ?>
<title><?php echo $this->title; ?></title>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_PLUGIN; ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_PLUGIN; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_PLUGIN; ?>uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="<?php echo SITE_ADM_CSS; ?>style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_CSS; ?>style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_CSS; ?>style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_CSS; ?>plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_CSS; ?>pages/tasks.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_CSS; ?>themes/blue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="<?php echo SITE_ADM_CSS; ?>print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="<?php echo SITE_ADM_CSS; ?>custom.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo SITE_ADM_PLUGIN; ?>bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo SITE_ADM_PLUGIN; ?>bootstrap-datepicker/css/datepicker.css"/>

<link href="<?php echo SITE_ADM_CSS; ?>toggle-switch.css" rel="stylesheet" />
<!-- END THEME STYLES -->
<link rel="shortcut icon" type="image/ico" href="<?php echo ('' != SITE_FAVICON) ? 'https://storage.googleapis.com/av8db/site-images-nct/' . SITE_FAVICON : ""; ?>" />

<?php echo load_css($this->styles); ?>
<script src="<?php echo SITE_ADM_PLUGIN; ?>jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_ADM_PLUGIN; ?>bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_JS; ?>jquery.form.js" type="text/javascript" ></script>
<script src="<?php print SITE_JAVASCRIPT; ?>jquery.numeric.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo SITE_JS; ?>bootbox.min.js"></script>


<script language="javascript" type="text/javascript">
    var SITE_URL = '<?php echo SITE_URL; ?>';
    var SITE_ADM_URL = '<?php echo SITE_ADMIN_URL; ?>';
    var SITE_ADM_MOD_URL = '<?php echo SITE_ADM_MOD; ?>';

    var SITE_ADM_URL_USERS = SITE_ADM_MOD_URL + 'users-nct/';
    var SITE_ADM_URL_JOBS = SITE_ADM_MOD_URL + 'jobs-nct/';
    var SITE_ADM_URL_COMPANIES = SITE_ADM_MOD_URL + 'companies-nct/';
    var SITE_ADM_URL_GROUPS = SITE_ADM_MOD_URL + 'groups-nct/';
    var SITE_ADM_URL_PAYMENT_HISTORY = SITE_ADM_MOD_URL + 'payment-history-nct/';

    var siteName = '<?php echo SITE_URL; ?>';
    var SITE_ADM_IMG = '<?php echo SITE_ADM_IMG; ?>';
    $(function () {
        var mBar = $('.page-sidebar-menu').find('li.sm-<?php echo $this->module; ?>');
        mBar.addClass('active');
        mBar.parents('ul.sub-menu').parent('li').addClass('active');
    });

    var CURRENCY_SYMBOL = '<?php echo CURRENCY_SYMBOL; ?>';
    var CURRENCY_CODE = '<?php echo PAYPAL_CURRENCY_CODE; ?>';
    var MONTH_NAMES
            = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var MONTH_NAMES_SHORT
            = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    function loadExtScript(src, callback) {
        var s = document.createElement('script');
        s.src = src;
        document.body.appendChild(s);
        s.onload = callback;
    }
    
    $(document).ready(function() {
        <?php 
        echo ( ( $this->include_google_maps_js ) ? includeGoogleMapsJS($this->init_autocomplete) : '' );
        ?>
    });

    function initializeTootltip() {
        $('[data-toggle="tooltip"]').tooltip();
    }

    function initBootBox(title, message, callbackFn) {
        bootbox.confirm({
            title: title,
            message: message,
            reorder: true,
            buttons: {
                cancel: {
                    label: 'Cancel',
                    className: 'btn blue-btn cancel-btn '
                },
                confirm: {
                    label: 'Delete',
                    className: 'btn blue-btn'
                }               
            },
            callback: callbackFn
        });
    }
    
    
</script>
<?php //echo GOOGLE_ANA_CODE_COM; ?>
