<script type="text/javascript">
    $(function () {
        
        ajaxSourceUrl = "<?php echo SITE_ADM_MOD.  $this->module; ?>/ajax.<?php echo $this->module; ?>.php";
        queryStringUrl = "";
        
        <?php if(isset($_REQUEST['day'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?day=<?php echo $_REQUEST['day']; ?>";
                } else {
                    queryStringUrl += "&day=<?php echo $_REQUEST['day']; ?>";
                }
        <?php } ?>
        
        <?php if(isset($_REQUEST['month'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?month=<?php echo $_REQUEST['month']; ?>";
                } else {
                    queryStringUrl += "&month=<?php echo $_REQUEST['month']; ?>";
                }
        <?php } ?>
        
        <?php if(isset($_REQUEST['year'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?year=<?php echo $_REQUEST['year']; ?>";
                } else {
                    queryStringUrl += "&year=<?php echo $_REQUEST['year']; ?>";
                }
        <?php } ?>
        
        ajaxSourceUrl += queryStringUrl;
        
        OTable = $('#dt_users').dataTable({
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": ajaxSourceUrl,
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
                {"sName": "user_image", 'sTitle': 'User image', bSortable: false},
                // {"sName": "user_headline", 'sTitle': 'User headline', bSortable: false},
                {"sName": "country", 'sTitle': 'User Location'},
                {"sName": "email_address", 'sTitle': 'Email Address'},
                { sName: "date_added", sTitle : 'Registered On'},
                {"sName": "status", 'sTitle': 'Status', bSortable: false, bSearchable: false},
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
        $(document).on('submit', '#frmCont', function (e) {
            $("#frmCont").on('submit', function () {
                for (var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].updateElement();
                }
            })
            $("#frmCont").validate({
                ignore: [],
                errorClass: 'help-block',
                errorElement: 'span',
                rules: {
                    page_name: {
                        pagenm: true,
                        remote: {
                            url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
                            type: "post",
                            async: false,
                            data: {ajaxvalidate: true, page_name: function () {
                                    return $("#page_name").val();
                                }, id: function () {
                                    return $("#id").val();
                                }},
                            complete: function (data) {
                                return data;
                            }
                        }
                    }
                },
                messages: {
                    page_name: {remote: 'Page name already exist'}
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
                return true;
            } else {
                return false;
            }
        });
    });

    function changeCountry(country) {
        if (country != "") {
            $.ajax({
                type: "POST",
                url: "ajax.<?php echo $this->module; ?>.php",
                data: 'action=getstate&country=' + country,
                cache: false,
                success: function (dataRes) {
                    $("#statebox").html(dataRes);
                    $('#state').addClass('error');
                }
            });
        } else {
            $('#state option:not(:first)').remove();
            $('#state').addClass('error');

        }
        $('#city option:not(:first)').remove();
        $('#city').addClass('error');
    }
    function changeState(state) {
        if (state != "") {
            var country = $("#country").val();
            $.ajax({
                type: "POST",
                url: "ajax.<?php echo $this->module; ?>.php",
                data: 'action=getcity&country=' + country + '&state=' + state,
                cache: false,
                success: function (dataRes) {
                    $("#citybox").html(dataRes);
                    $('#city').addClass('error');
                }
            });
        } else {
            $('#city option:not(:first)').remove();
            $('#city').addClass('error');
        }
    }
    
    
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
                    %VIEW_ALL_RECORDS_BTN%
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