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
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-dot-circle-o"></i><?php echo $this->headTitle; ?>
                </div>
                <div class="actions portlet-toggler">
                    
                    <div class="btn-group"></div>
                </div>

            </div>
            <div class="portlet-body portlet-toggler">
                <table id="example123" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        OTable = $('#example123').dataTable({
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
                aaSorting : [],
                aoColumns: [
                    { sName: "id", sTitle : 'Membership Plan Id', 'bVisible': false},
                    {sName: "plan_name", sTitle : 'Plan name'},
                    {sName: "plan_type_text", sTitle : 'Plan type'},
                    {sName: "plan_duration", sTitle : 'Plan duration'},
                    {sName: "no_of_inmails", sTitle : 'No. of inmails'},
                    {sName: "price", sTitle : 'Price'}
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

        $.validator.addMethod('pagenm', function (value, element) {
            return /^[a-zA-Z0-9][a-zA-Z0-9\-\_]*$/.test(value);
        }, 'Page name is not valid. Only alphanumeric and -,_ are allowed');
        
        $(document).on('click', '#submitAddForm', function (e) {
            e.preventDefault();
            $("#frmCont").on('submit', function () {
                for (var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].updateElement();
                }
            })
            $("#frmCont").validate({
                errorClass: 'help-block',
                errorElement: 'span',
                ignore: [],
                rules: {
                    plan_name: {
                        required: true
                    },
                    plan_description: {
                        required: true
                    },
                    plan_duration: {
                        required: true
                    },
                    plan_duration_unit: {
                        required: true
                    },
                    no_of_inmails: {
                        required: true
                    },
                    price: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    plan_name: {
                        required: "&nbsp;Please enter plan name"
                    },
                    plan_description: {
                        required: "&nbsp;Please enter plan description"
                    },
                    plan_duration: {
                        required: "&nbsp;Please enter plan duration"
                    },
                    plan_duration_unit: {
                        required: "&nbsp;Please select a unit for plan duration"
                    },
                    no_of_inmails: {
                        required: "&nbsp;Please enter no. of inmails"
                    },
                    price: {
                        required: "&nbsp;Please enter price"
                    },
                    status: {
                        required: "&nbsp;Please select the status of plan"
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
            
            
            if ($("#frmCont").valid()) {
                ajaxFormSubmit("#frmCont");
            } else {
                return false;
            }
            
        });
    });

</script>
