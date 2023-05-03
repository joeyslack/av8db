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
                    %VIEW_ALL_RECORDS_BTN%
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <table id="dt_groups" class="table table-striped table-bordered table-hover"></table>
            </div>
            <div class="portlet-body portlet-toggler pageform" style="display:none;"></div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        
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

        <?php if(isset($_REQUEST['group_id'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?group_id=<?php echo $_REQUEST['group_id']; ?>";
                } else {
                    queryStringUrl += "&group_id=<?php echo $_REQUEST['group_id']; ?>";
                }
        <?php } ?>
        
        ajaxSourceUrl += queryStringUrl;
        
        OTable = $('#dt_groups').dataTable({
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
                    { "sName": "id", 'sTitle': "Group Id", 'bVisible': false},
                    { sName: "user_name", sTitle : "User Name"},
                    { sName: "group_name", sTitle : "Group Name"},
                    { sName: "group_logo", sTitle : "Group Logo", bSortable:false},
                    { sName: "total_members", sTitle : "Total Members",bSortable:false},
                    { sName: "group_type", sTitle : "Group Type"},
                    //{ sName: "industry_name", sTitle : "Group Industry",bSortable:false},
                    { sName: "privacy_text", sTitle : "Privacy"},
                    { sName: "accessibility_text", sTitle : "Accessibility"}
                    <?php if (in_array('status', $this->Permission)) { ?>
                        //,{ "sName": "status", 'sTitle' : 'Status', bSortable:false, bSearchable:false}
                    <?php } if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) { ?>
                        ,{"sName": "operation", 'sTitle': 'Operation', bSortable: false, bSearchable: false}
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
        $("#group_form").validate({
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                grpup_name: {
                    required: true,
                    remote: {
                        url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
                        data: {
                            id: $('#id').val()
                        }
                    }
                },
                group_logo: {
                    accept: "jpg|jpeg|png"
                },
                group_type_id: {
                    required: true,
                },
                // group_industry_id: {
                //     required: true,
                // },
            },
            messages: {
                group_name: {
                    required: "&nbsp; Please enter group name.",
                    remote: "&nbsp; Entered group name already exists."
                },
                group_logo: {
                    accept: "&nbsp; Please select a valid image file."
                },
                group_type_id: {
                    required: "&nbsp; Please select group type.",
                },
                // group_industry_id: {
                //     required: "&nbsp; Please select group industry.",
                // },
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
        
        if ($("#group_form").valid()) {
            ajaxFormSubmit("#group_form");
        } else {
            return false;
        }
    });
    
    $(document).on('change', 'input[name="privacy"]:radio', function () {
        if($(this).val() == 'pr') {
            $("#accessibility_container").addClass('hidden');
        } else if($(this).val() == 'pu') {
            $("#accessibility_container").removeClass('hidden');
        }
    });

    
    var _URL = window.URL || window.webkitURL;
    $(document).on('change', "#group_logo", function (e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function () {
                width = this.width;
                height = this.height;
                aspectRatio = width / height;
                if (aspectRatio == 1 && width >= 300  && height >= 300) {

                } else {
                    toastr["error"]("Please upload the group image of 300px X 300px or of the same aspect ratio.");
                    $("#group_logo").replaceWith($("#group_logo").val('').clone(true));
                    return false;
                }
                return true;
            };
            img.src = _URL.createObjectURL(file);
        }
    });
</script>
