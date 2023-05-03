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

<div id="featured_job_form_container" class="row">
    <div class="col-md-12">
        <!-- Begin: life time stats -->
        <div class="portlet box blue-dark">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-dot-circle-o"></i><?php echo $this->headTitle; ?>
                </div>
            </div>
            <div class="portlet-body">
                %ADHOC_INMAILS_FORM%
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).on('click', '#save_adhoc_inmails', function (e) {
        e.preventDefault();
        $("#adhoc_inmails_form").on('submit', function () {
            for (var instanceName in CKEDITOR.instances) {
                CKEDITOR.instances[instanceName].updateElement();
            }
        })
        $("#adhoc_inmails_form").validate({
            errorClass: 'help-block',
            errorElement: 'span',
            ignore: [],
            rules: {
                plan_description: {
                    required: true
                },
                price: {
                    required: true
                }
            },
            messages: {
                plan_description: {
                    required: "&nbsp;Please enter plan description"
                },
                price: {
                    required: "&nbsp;Please enter price"
                }
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
        
        if ($("#adhoc_inmails_form").valid()) {
            ajaxFormSubmit("#adhoc_inmails_form", false);
        } else {
            return false;
        }
        
    });
</script>
