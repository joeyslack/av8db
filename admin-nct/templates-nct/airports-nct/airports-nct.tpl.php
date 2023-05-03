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
                <table id="dt_airports" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        
        ajaxSourceUrl = "<?php echo SITE_ADM_MOD.  $this->module; ?>/ajax.<?php echo $this->module; ?>.php";
        queryStringUrl = "";
        
        OTable = $('#dt_airports').dataTable({
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
                    { sName: "airport_identifier", sTitle : "Airport ICAO Code"},
                    { sName: "location", sTitle : "Location"}
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
                    required: "Please enter airport ICAO code.",
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
                    //$('#city').addClass('error');

                    //$('#city option:not(:first)').remove();
                    //$('#city').addClass('error');
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
