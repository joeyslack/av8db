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
        <!-- Begin: life time stats -->
        <div class="portlet box blue-dark">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="fa fa-dot-circle-o"></i><?php echo $this->headTitle; ?>
                </div>
                <div class="actions portlet-toggler">
                    <?php if (in_array('add', $this->Permission)) { ?>
                        <a href="ajax.<?php echo $this->module; ?>.php?action=add" class="btn blue btn-add"><i class="fa fa-plus"></i> Add</a>
                    <?php } ?>
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <table id="dt_job_category" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(function () {
        OTable = $('#dt_job_category').dataTable({
            bProcessing: true,
                bServerSide: true,
                sAjaxSource: "ajax.<?php echo $this->module; ?>.php",
                fnServerData: function (sSource, aoData, fnCallback) {
                    $.ajax({
                        dataType: 'json',
                        type: "POST",
                        url: sSource,
                        data: aoData,
                        success: fnCallback
                    });
                },
                "aaSorting" : [],
                aoColumns: [
                { "sName": "id", 'sTitle': "Skill ID", 'bVisible': false},
                { sName: "job_category_<?= DEFAULT_LANGUAGE_ID ?>", sTitle : "Job category name"}
<?php if (in_array('status', $this->Permission)) { ?>
                    , { "sName": "status", 'sTitle' : 'Status', bSortable:false, bSearchable:false}
<?php } ?>
<?php if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) { ?>
                    , {"sName": "operation", 'sTitle': 'Operation', bSortable: false, bSearchable: false}
<?php } ?>
                ],
                fnServerParams: function(aoData){setTitle(aoData, this)},
                fnDrawCallback: function(oSettings) {
                $('.make-switch').bootstrapSwitch();
                $('.make-switch').bootstrapSwitch('setOnClass', 'success');
                $('.make-switch').bootstrapSwitch('setOffClass', 'danger');

            }
    });
    $('.dataTables_filter').css({float: 'right'});
    $('.dataTables_filter input').addClass("form-control input-inline");
    $('.dataTables_length select').addClass("form-control");

    $.validator.addMethod('pagenm', function (value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\-\_]*$/.test(value);
    }, 'Page name is not valid. Only alphanumeric and -,_ are allowed');
    
    });
    
    $(document).on('click', '#submitAddForm', function (e) {
        e.preventDefault();
        $("#job_category_form").validate({
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                job_category: {
                    required: true,
                    remote: {
                        url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
                        data: {
                            id: $('#id').val()
                        }
                    }
                },
                job_category_description: {
                    required: true,
                }
            },
            messages: {
                job_category: {
                    required: "&nbsp; Please enter job category.",
                    remote: "&nbsp; Entered job category already exists."
                },
                job_category_description: {
                    required: "&nbsp; Please enter job category description.",
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
        
        if ($("#job_category_form").valid()) {
            ajaxFormSubmit("#job_category_form");
        } else {
            return false;
        }
    });
    
    
</script>
