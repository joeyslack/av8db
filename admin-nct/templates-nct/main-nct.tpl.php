<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><?= $this->head; ?></head>
    <?php $class = ($this->module == "login-nct") ? "login" : "page-header-fixed"; ?>
    <body class="<?php echo $class; ?>">
        <?= $this->site_header; ?>
        <?php if ($this->adminUserId > 0) {
            echo '<div class="page-container">';
        } ?>
        <?= $this->left; ?>
        <div class="page-content-wrapper">
            <?php if ($this->adminUserId > 0) {
                echo '<div class="page-content">';
            } ?>
            <?= $this->body; ?>
            <?php if ($this->adminUserId > 0) {
                echo '</div>';
            } ?>
        </div>
        <?= $this->right; ?>
        <?php if ($this->adminUserId > 0) {
            echo '</div>';
        } ?>
        <?= $this->footer; ?>
        <!--[if lt IE 9]>
            <script src="<?= SITE_ADM_PLUGIN; ?>respond.min.js"></script>
            <script src="<?= SITE_ADM_PLUGIN; ?>excanvas.min.js"></script> 
        <![endif]-->
        <!-- <script src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.min.js" type="text/javascript"></script>-->
        <script src="<?= SITE_ADM_PLUGIN; ?>jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>jquery.cokie.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_PLUGIN; ?>jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>

        <!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> -->

        <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>jquery-validation/dist/additional-methods.min.js"></script>
        <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>select2/select2.min.js"></script>
        <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>bootstrap-toastr/toastr.min.js"></script>
        <?php if($this->module == 'home-nct') { ?>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.resize.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.pie.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.stack.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.crosshair.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.categories.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.time.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/jquery.flot.grow.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_PLUGIN; ?>flot/plugins/jquery_flot_animator/jquery.flot.animator.min.js"></script>
            <script type="text/javascript" src="<?= SITE_ADM_JS; ?>custom/charts.js"></script>
        <?php } ?>
        <script type="text/javascript">toastr.options={"closeButton":true,"debug":false,"positionClass":"toast-top-full-width","onclick":null,"showDuration":"300","hideDuration":"1000","timeOut":"5000","extendedTimeOut":"1000","showEasing":"swing","hideEasing":"linear","showMethod":"fadeIn","hideMethod":"fadeOut"}</script>

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="<?= SITE_ADM_JS; ?>core/app.js" type="text/javascript"></script>
        <script src="<?= SITE_ADM_JS; ?>core/admin.js" type="text/javascript"></script>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                <?php echo $this->toastr_message; ?>
                App.init();
            });
        </script>
        <?php echo load_js($this->scripts); ?>
    </body>
</html>