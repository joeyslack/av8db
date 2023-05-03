<style type="text/css">
    .hide_column{
        display : none;
    }
</style>
<script type="text/javascript">
    $(function () {
        OTable = $('#example123').dataTable({
        "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": "ajax.<?php echo $this->module; ?>.php",
                "fnServerData": function (sSource, aoData, fnCallback) {
                    $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource,
                        "data": aoData,
                        "success": fnCallback
                    });
                },
                "aoColumns": [
                { "sName": "pId", 'sTitle' : 'Id', "sClass": "hide_column"},
                { "sName": "pageTitle", 'sTitle' : 'Page Title'},
                { "sName": "page_slug", 'sTitle' : 'Page Slug'}
<?php if (in_array('status', $this->Permission)) { ?>
                    , { "sName": "isActive", 'sTitle' : 'Status', bSearchable:false}
<?php } ?>
<?php if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) { ?>
                    , {"sName": "operation", 'sTitle': 'Operation', bSortable: false, bSearchable: false}
<?php } ?>
                ],
                "fnServerParams"
                : function(aoData){setTitle(aoData, this)},
                "fnDrawCallback"
                : function(oSettings) {
                $('.make-switch').bootstrapSwitch();
                $('.make-switch').bootstrapSwitch('setOnClass', 'success');
                $('.make-switch').bootstrapSwitch('setOffClass', 'danger');
                }
    });
    $('.dataTables_filter').css({float: 'right'});
    $('.dataTables_filter input').addClass("form-control input-inline");

    $.validator.addMethod('pagenm', function (value, element) {
        return /^[a-zA-Z0-9][a-zA-Z0-9\_\-]*$/.test(value);
    }, 'Page name is not valid. Only alphanumeric,space and _ are allowed'
            );
    $(document).on('submit', '#frmCont', function (e) {
        $("#frmCont").on('submit', function () {
            for (var instanceName in CKEDITOR.instances) {
                CKEDITOR.instances[instanceName].updateElement();
            }
        });
        $("#frmCont").validate({
            ignore: [],
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                pageName: {
                    required: true,
                    pagenm: true,
                    remote: {
                        url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
                        type: "post",
                        async: false,
                        data: {ajaxvalidate: true, pageName: function () {
                                return $("#pageName").val();
                            }, id: function () {
                                return $("#id").val();
                            }},
                        complete: function (data) {
                            return data;
                        }
                    }
                },
                pageTitle: {
                    required: true
                },
                metaKeyword: {
                    required: true
                },
                metaDesc:{
                    required: true
                },
                pageDesc: {
                    required: {
                        depends: function () {
                            //return if($("#inkType").is(':checked'){ true; }else { false;});
                            if ($('input[name=linkType]:checked').val() == "url")
                            {
                                return false;
                            } else {
                                return true;
                            }
                        }
                    }

                },
                url: {
                    required: {
                        depends: function () {
                            //return if($("#inkType").is(':checked'){ true; }else { false;});
                            if ($('input[name=linkType]:checked').val() == "url")
                            {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    url: {
                        depends: function () {
                            //return if($("#inkType").is(':checked'){ true; }else { false;});
                            if ($('input[name=linkType]:checked').val() == "url")
                            {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }

                }
            },
            messages: {
                pageName: {required: 'Page name is required.', remote: 'Page name already exist'},
                pageTitle: {required: 'Page title is required.'},
                metaKeyword: {required: 'Meta Keywords is required.'},
                metaDesc: {required: 'Meta Description is required.'},
                pageDesc: {required: 'Page description is required.'},
                url: {
                    required: 'Please Enter URL',
                    url: 'Please Enter Proper URL'
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else {
                    error.insertAfter(element);
                }
            }
        });
        // alias required to cRequired with new message
        $.validator.addMethod("cRequired", $.validator.methods.required,
                "Page name is required.");
        // combine them both, including the parameter for minlength
        $.validator.addClassRules("pageNameValue", {cRequired: true});

        // alias required to cRequired with new message
        $.validator.addMethod("cRequiredTitle", $.validator.methods.required,
                "Page title is required.");
        // combine them both, including the parameter for minlength
        $.validator.addClassRules("pageTitleValue", {cRequiredTitle: true});

        // alias required to cRequired with new message
        $.validator.addMethod("cRequiredKeywords", $.validator.methods.required,
                "Meta Keywords is required.");
        // combine them both, including the parameter for minlength
        $.validator.addClassRules("metaKeyword", {cRequiredKeywords: true});
        
        // alias required to cRequired with new message
        $.validator.addMethod("cRequiredDescription", $.validator.methods.required,
                "Meta Description is required.");
        // combine them both, including the parameter for minlength
        $.validator.addClassRules("metaDescription", {cRequiredDescription: true});

        // alias required to cRequired with new message
        $.validator.addMethod("cRequiredDesc", $.validator.methods.required,
                "Page description is required.");
        // combine them both, including the parameter for minlength
        $.validator.addClassRules("pageDescValue", {cRequiredDesc: {
                depends: function () {
                    //return if($("#inkType").is(':checked'){ true; }else { false;});
                    if ($('input[name=linkType]:checked').val() == "url")
                    {
                        return false;
                    } else {
                        return true;
                    }
                }
            }
        });

        if ($("#frmCont").valid()) {
            return true;
        } else {
            return false;
        }
    });
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
        <!-- Begin: life time stats -->
        <div class="portlet box blue-dark">
            <div class="portlet-title ">
                <div class="caption"><i class="fa fa-list-alt"></i><?php echo $this->headTitle; ?></div>
                <div class="actions portlet-toggler">
                    <?php if (in_array('add', $this->Permission)) { ?>
                        <a href="ajax.<?php echo $this->module; ?>.php?action=add" class="btn blue btn-add"><i class="fa fa-plus"></i> Add</a>
                    <?php } ?>
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <table id="example123" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>     