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
                { sName: "id", sTitle : 'Payment Id', 'bVisible': false},
                { sName: "first_name", sTitle : 'User name'},
                { sName: "invoice_id", sTitle : 'Invoice ID'},
                { sName: "transaction_id", sTitle : 'Transaction ID'},
                { sName: "added_on", sTitle : 'Payment date'},
                { sName: "plan_type", sTitle : 'Payment for'},
                { sName: "payment_status", sTitle : 'Transaction status'},
                { sName: "total_price", sTitle : 'Amount'}
            ],
            fnServerParams: function(aoData){setTitle(aoData, this)},
        });

        $('.dataTables_filter').css({float: 'right'});
        $('.dataTables_filter input').addClass("form-control input-inline");

        $.validator.addMethod('pagenm', function (value, element) {
            return /^[a-zA-Z0-9][a-zA-Z0-9\-\_]*$/.test(value);
        }, 'Page name is not valid. Only alphanumeric and -,_ are allowed');
    
    });
</script>
