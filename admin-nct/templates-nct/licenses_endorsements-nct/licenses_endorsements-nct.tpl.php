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
                <table id="dt_licenses" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        
        ajaxSourceUrl = "<?php echo SITE_ADM_MOD.  $this->module; ?>/ajax.<?php echo $this->module; ?>.php";
        queryStringUrl = "";
        
        OTable = $('#dt_licenses').dataTable({
            bProcessing: true,
                bServerSide: true,
                sAjaxSource: ajaxSourceUrl,
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
                    { sName: "id", 'sTitle': "Id", 'bVisible': false},
                    { sName: "licenses_endorsement_name", sTitle : "Licenses & Endorsement Name"}
                    //{ sName: "flight_hours", sTitle : "Flight Hours"}
                    // { sName: "isLicense", sTitle : "Licenses"},
                    // { sName: "isBoth", sTitle : "Both"},
                    // { sName: "isNone", sTitle : "None"}
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
        $("#license_endorsement_form").validate({
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                licenses_endorsement_name: {
                    required: true,
                },
                // commercial:{
                //     required: true,
                // }
            },
            messages: {
                licenses_endorsement_name: {
                    required: "Please enter name.",
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
                } else if (true) {}{
                    error.insertAfter(element);
                }
            }
        });
        if ($("#license_endorsement_form").valid()) {
            ajaxFormSubmit("#license_endorsement_form");
        } else {
            return false;
        }
    });
</script>
