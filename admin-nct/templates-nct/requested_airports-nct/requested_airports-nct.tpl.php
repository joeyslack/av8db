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
                    { sName: "airport_name", sTitle : "Airport Name"},
                    { sName: "airport_identifier", sTitle : "Airport Identifier"},
                    { sName: "location ", sTitle : "Airport Location "},
                    { sName: "user_id", sTitle : "Username"}
                    <?php if (in_array('status', $this->Permission)) { ?>
                    , { "sName": "status", 'sTitle' : 'Status', bSortable:false, bSearchable:false}
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

       
    });    
    

$(document).on('change','.switch-toggle input[type=radio]', function(event, state){
    var val = $(this).val();
    var action = $(this).data('action');
    if(confirm('Are you sure to '+(val == 'y' ? 'accept' : 'reject')+' airport?')){
        $.ajax({
            dataType: 'json',
            type: "GET",
            url: action+'&value='+val,
            beforeSend: function(){
                addOverlay();
            },
            success: function (r) {
                toastr[r.type](r.message);      
                OTable.fnDraw();
                return false;
            },
            complete: function (xhr) {
                removeOverlay();
                return false;
            }
        });
    }
    else{
        OTable.fnDraw();
        return false;
    }
});
</script>
