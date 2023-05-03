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
                    <!-- <?php if (in_array('add', $this->Permission)) { ?>
                        <a href="ajax.<?php echo $this->module; ?>.php?action=add" class="btn blue btn-add"><i class="fa fa-plus"></i> Add</a>
                    <?php } ?> -->                    
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <div class="row" id="filters"> 
                <div class="col-md-12 text-right" style="margin-bottom:10px">
                    Please Check this option if you want Admin approval for a Business: <input type="checkbox" name="company_approvals" id="company_approvals" class="" value="y" %ISREQUESTRECEIVE%>
                </div>
                </div>
                <table id="dt_company" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="company1" >
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title1">Pin point on Google Map</h4>
            </div>
            <div class="modal-body-map">
                <div id="map_canvas" style="width: 100%; height: 300px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).on("click",".view_on_map",function(){
        
        var lat=$(this).data('lat');
        var lng=$(this).data('lng');
        $("#company1").modal('show');
        mapPinPoint(parseFloat(lat),parseFloat(lng));

    });

    function mapPinPoint(lat="",lng="",zoom_no=4){
        
        var myLatlng = { lat: lat, lng: lng };
        var map = new google.maps.Map(document.getElementById("map_canvas"), {
          zoom: zoom_no,
          center: myLatlng,
        });

        var marker = new google.maps.Marker({
            position: myLatlng,
            title: 'Selected Location',
            map: map
       });
    }

    $(document).ready(function() {
        
        ajaxSourceUrl = "<?php echo SITE_ADM_MOD.  $this->module; ?>/ajax.<?php echo $this->module; ?>.php";
        queryStringUrl = "";
        
        OTable = $('#dt_company').dataTable({
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
                    { sName: "airport_name", sTitle : "Business Name"},
                    { sName: "airport_name", sTitle : "Business Email ID"},
                    { sName: "airport_identifier", sTitle : "Business URL"},
                    { sName: "airport_identifier", sTitle : "Closest Airport"},
                    { sName: "location", sTitle : "Location"},
                    { sName: "location", sTitle : "Business Type"}
                    <?php if (in_array('status', $this->Permission)) { ?>
                    , { "sName": "admin_approval", 'sTitle' : 'Admin Approval', bSortable:false, bSearchable:false}
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
        $("#airport_form").validate({
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                airport_identifier: {
                    required: true,
                },
                location: {
                    required: true,
                },
                airport_name:{
                    required: true,
                }
            },
            messages: {
                airport_identifier: {
                    required: "Please enter airport identifier.",
                },
                location: {
                    required: "Please enter location.",
                },
                airport_name: {
                    required: "Please enter airport name.",
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
        if ($("#airport_form").valid()) {
            ajaxFormSubmit("#airport_form");
        } else {
            return false;
        }
    });

    $(document).on('click','.acceptRejectStatus',function()
    {
        var ele = $(this);
        var approve_ty , status;
        if($(this).attr('statusType') == 'y'){
            approve_ty = "Accept";
            status = "Accepted";

        }else{
            approve_ty = "Reject";
            status = "Rejected";
        }

        conf = confirm('Are you sure you want to '+approve_ty+' this company?');
        if(conf){
            $.ajax({
                type:'post',
                dataType: 'json',
                url:"<?php echo SITE_ADM_MOD.  $this->module; ?>/ajax.<?php echo $this->module; ?>.php",
                data:{action:'aprroveRejectCompany', com_id: $(this).attr('com_id') , approval : $(this).attr('statusType')},
                success: function(return_data){
                    if(return_data.status == 'success'){
                        toastr[return_data.status]("Company "+status+" successfully");
                        ele.closest('td').html(status);
                    }else{
                        toastr["error"]("Problem occured, Please try again");
                    }
                }
            });
        }
    });

    $(document).on('click','.activateDeactivateStatus',function()
    {
        var ele = $(this);
        var approve_ty , status;
        if($(this).attr('statusType') == 'a'){
            approve_ty = "Activate";
            status = "Activated";

        }else{
            approve_ty = "Deactivate";
            status = "Deactivated";
        }

        conf = confirm('Are you sure you want to '+approve_ty+' this company?');
        if(conf){
            $.ajax({
                type:'post',
                dataType: 'json',
                url:"<?php echo SITE_ADM_MOD.  $this->module; ?>/ajax.<?php echo $this->module; ?>.php",
                data:{action:'activeDeactivateCompany', com_id: $(this).attr('com_id') , approval : $(this).attr('statusType')},
                success: function(return_data){
                    if(return_data.status == 'success'){
                        toastr[return_data.status]("Company "+status+" successfully");
                        ele.closest('td').html(status);
                    }else{
                        toastr["error"]("Problem occured, Please try again");
                    }
                }
            });
        }
    });

    var on_off = '';
    $(document).on('click','#company_approvals',function(){
        on_off = $("input[name='company_approvals']:checked").val();
        
        if (on_off == 'y')
            on_off = 'y';
        else
            on_off = 'n';
        $.ajax({
            type: "POST",
            url: "ajax.<?php echo $this->module; ?>.php",
            data: {
              "on_off" : on_off,
              "action" : 'company_approvals' 
            },
            success: function (dataRes) {
                var data = JSON.parse(dataRes);
                if(data.status == 'success'){
                    toastr[data.status]("status changed successfully");
                }else{
                    toastr["error"]("Problem occured, Please try again");
                }
            }
        });
    })

    function changeCountry(country) {
        if (country != "") {
            $.ajax({
                type: "POST",
                url: "ajax.<?php echo $this->module; ?>.php",
                data: 'country=' + country,
                cache: false,
                success: function (dataRes) {
                    $("#statebox").html(dataRes);
                   // $('#state').addClass('error');

                    $('#city option:not(:first)').remove();
                    //$('#city').addClass('error');
                }
            });
        } else {
            $('#state option:not(:first)').remove();
            $('#state').addClass('error');

            $('#city option:not(:first)').remove();
            $('#city').addClass('error');
        }
    }

    function changeState(state) {
        if (state != "") {
            $.ajax({
                type: "POST",
                url: "ajax.<?php echo $this->module; ?>.php",
                data: 'state=' + state,
                cache: false,
                success: function (dataRes) {
                    $("#citybox").html(dataRes);
                }
            });
        } else {
            $('#city option:not(:first)').remove();
            $('#city').addClass('error');

            $('#city option:not(:first)').remove();
            $('#city').addClass('error');
        }
    }
</script>