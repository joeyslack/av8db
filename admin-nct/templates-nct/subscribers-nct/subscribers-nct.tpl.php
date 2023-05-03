<script type="text/javascript">
    $(function () {
        OTable = $('#dt_users').dataTable({
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
            "aaSorting" : [],
            "aoColumns": [
                { sName: "id", sTitle : 'User Id', 'bVisible': false},
                {"sName": "first_name", 'sTitle': 'First Name'},
                {"sName": "last_name", 'sTitle': 'Last Name'},
                {"sName": "email_address", 'sTitle': 'Email Address'},
                { sName: "subscribed_on", sTitle : 'Subscribed On'},
                // {"sName": "status", 'sTitle': 'Status', bSortable: false, bSearchable: false},
                {"sName": "operation", 'sTitle': 'Operation', bSortable: false, bSearchable: false}
            ],
            "fnServerParams"
                    : function (aoData) {
                        setTitle(aoData, this)
                    },
            "fnDrawCallback"
                    : function (oSettings) {
                        $('.make-switch').bootstrapSwitch();
                        $('.make-switch').bootstrapSwitch('setOnClass', 'success');
                        $('.make-switch').bootstrapSwitch('setOffClass', 'danger');

                    }

        });
        $('.dataTables_filter').css({float: 'right'});
        $('.dataTables_filter input').addClass("form-control input-inline");

        $.validator.addMethod('pagenm', function (value, element) {
            return /^[a-zA-Z0-9][a-zA-Z0-9\_\-]*$/.test(value);
        }, 'Page name is not valid. Only alphanumeric and _ are allowed'
                );
        
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
                    <?php
                    if (in_array('add', $this->Permission)) {
                        ?>
                            <!--                     <a href="ajax.<?php echo $this->module; ?>.php?action=add" class="btn blue btn-add"><i class="fa fa-plus"></i> Add</a>
                        -->                     <?php } ?>
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <table id="dt_users" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>     