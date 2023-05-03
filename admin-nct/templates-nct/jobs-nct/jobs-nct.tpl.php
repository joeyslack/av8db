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
                    <?php /*if (in_array('add', $this->Permission)) { ?>
                        <a href="ajax.<?php echo $this->module; ?>.php?action=add" class="btn blue btn-add"><i class="fa fa-plus"></i> Add</a>
                    <?php }*/ ?>
                    %VIEW_ALL_RECORDS_BTN%
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="portlet-body portlet-toggler">
                <table id="dt_jobs" class="table table-striped table-bordered table-hover"></table>
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

        <?php if(isset($_REQUEST['job_id'])) { ?>
                if(queryStringUrl == "") {
                    queryStringUrl = "?job_id=<?php echo $_REQUEST['job_id']; ?>";
                } else {
                    queryStringUrl += "&job_id=<?php echo $_REQUEST['job_id']; ?>";
                }
        <?php } ?>
        
        ajaxSourceUrl += queryStringUrl;
        
        
        OTable = $('#dt_jobs').dataTable({
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
                    { sName: "job_title", sTitle : "Job Title"},
                    { sName: "job_category", sTitle : "Job Category"},
                    { sName: "job_location", sTitle : "Job Location"},
                    { sName: "is_featured", sTitle : "Featured"},
                    { sName: "company_name", sTitle : "Business"},
                    { sName: "last_date_of_application", sTitle : "Last Date of Application"}
                    <?php if (in_array('status', $this->Permission)) { ?>
                        ,{ "sName": "status", 'sTitle' : 'Status', bSortable:false, bSearchable:false}
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

        for (var instanceName in CKEDITOR.instances) {
            CKEDITOR.instances[instanceName].updateElement();
        }

        $("#job_form").validate({
            ignore:[],
            errorClass: 'help-block',
            errorElement: 'span',
            rules: {
                company_id: {
                    required: true
                },
                job_category_id: {
                    required: true
                },
                job_title: {
                    required: true
                },
                job_location: {
                    required: true
                },
                employment_type: {
                    required: true
                },
                last_date_of_application: {
                    required: true
                },
                status: {
                    required: true
                },
                // relavent_experience_from: {
                //     required: true
                // },
                relavent_experience_to: {
                    required: true
                },
                key_responsibilities: {
                    required: true
                },
                // skills_and_exp: {
                //     required: true
                // },
            },
            // groups: {
            //     experience: "relavent_experience_from relavent_experience_to"
            // },
            messages: {
                company_id: {
                    required: "&nbsp; Please select a company.",
                },
                job_category_id: {
                    required: "&nbsp; Please select a job category.",
                },
                job_title: {
                    required: "&nbsp; Please enter job title.",
                },
                job_location: {
                    required: "&nbsp; Please enter job location.",
                },
                employment_type: {
                    required: "&nbsp; Please select employment type.",
                },
                last_date_of_application: {
                    required: "&nbsp; Please select last date of application.",
                },
                status: {
                    required: "&nbsp; Please select status.",
                },
                // relavent_experience_from: {
                //     required: "&nbsp; Please enter experience."
                // },
                relavent_experience_to: {
                    required: "&nbsp; Please enter experience."
                },
                key_responsibilities: {
                    required: "&nbsp; Please enter key responsibilities.",  
                },
                // skills_and_exp: {
                //     required: "&nbsp; Please enter desired skills and experience.",  
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
        
        if ($("#job_form").valid()) {
            ajaxFormSubmit("#job_form");
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
    
    $(document).on('change', '#country_id', function (e) {
        var country = $(this).val();
        $("#city_id").find("option:gt(0)").remove();
        if (country != "") {
            $.ajax({
                type: "POST",
                url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
                data: 'action=getstate&country_id=' + country,
                cache: false,
                success: function (dataRes) {
                    $("#states_container").html(dataRes);
                    $('#state_id').addClass('error');
                    $('#state_id').focus();
                }
            });
        } else {
            $('#state_id option:not(:first)').remove();
            $('#state_id').addClass('error');
        }
    });

    $(document).on('change', '#state_id', function (e) {
        var state = $(this).val();
        if (state != "") {
            var country = $("#country_id").val();
            $.ajax({
                type: "POST",
                url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
                data: 'action=getcity&country_id=' + country + '&state_id=' + state,
                cache: false,
                success: function (dataRes) {
                    $("#cities_container").html(dataRes);
                    $('#city_id').addClass('error');
                    $('#city_id').focus();
                }
            });
        } else {
            $('#city_id option:not(:first)').remove();
            $('#city_id').addClass('error');
        }
    });

    
    
</script>
