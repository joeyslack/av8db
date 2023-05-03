<script type="text/javascript">
    $(document).on('submit', '#frmSS', function (e) {
        $("#frmSS").validate({
            ignore: [],
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                2: {
                  required: true,
                  email: true
                },
                11: {
                  required: true,
                  email: true
                },
                30: {
                  required: true,
                  email: true
                },
                38: {
                  required: true,
                  email: true
                },

             },
            messages: {
                2: {
                    required: "&nbsp;Please enter admin email"
                },
                11: {
                    required: "&nbsp;Please enter from email"
                },
                30: {
                    required: "&nbsp;Please enter SMTP username"
                },
                38: {
                    required: "&nbsp;Please enter Paypal email"
                },

            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element);
                }
            }
        });
        if ($("#frmSS").valid()) {
            return true;
        } else {
            return false;
        }
    });
 
    $(document).on('keydown','.numberOnly',function(e){
         var key = e.which || e.keyCode;
         if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
         // numbers   
            key >= 48 && key <= 57 ||
         // Numeric keypad
            key >= 96 && key <= 105 ||
         // comma, period and minus, . on keypad
            key == 190 || key == 188 || key == 109 || key == 110 ||
         // Backspace and Tab and Enter
            key == 8 || key == 9 || key == 13 ||
         // Home and End
            key == 35 || key == 36 ||
         // left and right arrows
            key == 37 || key == 39 ||
         // Del and Ins
            key == 46 || key == 45)
             return true;
        return false;
    });
</script>
<!-- BEGIN PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <?php
        echo $this->breadcrumb;
        ?>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-dark">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i><?php echo $this->headTitle; ?>
                </div>
            </div>
            <div class="portlet-body form">
                <form action="" method="post" name="frmSS" id="frmSS" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-body">
                        <?php echo $this->getForm; ?>
                    </div>  
                </form> 
            </div>
        </div>   
    </div>
</div>      