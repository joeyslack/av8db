<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
        <h3 class="page-title">
            Dashboard <small>statistics and more</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                Home
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="javascript:void(0);" title="Dashboard">
                    Dashboard
                </a>
            </li>
            <li class="pull-right">
                <div id="dashboard-report-range" class="dashboard-date-range tooltips" data-placement="top" data-original-title="Change dashboard date range">
                    <i class="fa fa-calendar"></i>
                    <span></span>
                    <i class="fa fa-angle-down"></i>
                </div>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>

<div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="<?php print SITE_ADM_MOD; ?>users-nct">
                <div class="dashboard-stat light-red">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number" id="getcustomer">
                            %TOTAL_USERS%
                        </div>
                        <div class="desc">
                             Users
                        </div>
                    </div>
                    
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="<?php print SITE_ADM_MOD; ?>jobs-nct">
                <div class="dashboard-stat green">
                    <div class="visual">
                        <i class="fa fa-briefcase"></i>
                    </div>
                    <div class="details">
                        <div class="number" id="getcustomer">
                            %TOTAL_JOBS%
                        </div>
                        <div class="desc">
                             Jobs
                        </div>
                    </div>
                    
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="<?php print SITE_ADM_MOD; ?>companies-nct">
                <div class="dashboard-stat yellow">
                    <div class="visual">
                        <i class="fa fa-desktop"></i>
                    </div>
                    <div class="details">
                        <div class="number" id="getcustomer">
                            %TOTAL_COMPANIES%
                        </div>
                        <div class="desc">
                             Business
                        </div>
                    </div>
                    
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="<?php print SITE_ADM_MOD; ?>groups-nct">
                <div class="dashboard-stat blue">
                    <div class="visual">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="details">
                        <div class="number" id="getcustomer">
                            %TOTAL_GROUPS%
                        </div>
                        <div class="desc">
                             Groups
                        </div>
                    </div>
                    
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat light-green">
                <div class="visual">
                    <i class="fa fa-money"></i>
                </div>
                <div class="details">
                    <div class="number" id="getcustomer">
                        %REVENUE_EARNED%
                    </div>
                    <div class="desc">
                         Revenue Earned
                    </div>
                </div>
                
            </div>
        </div>


</div>
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS -->
<!--<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php //print SITE_ADM_MOD; ?>users-nct">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="fa fa-users"></i>
                </div>
                <div class="details">
                    <div class="number" id="getcustomer">
                        <?php //echo $this->no_of_users; ?>
                    </div>
                    <div class="desc">
                        Users
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>-->

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- BEGIN INTERACTIVE CHART PORTLET-->
        <div class="portlet box blue" data-report-type="users">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-users"></i>Users Report
                </div>
                <div class="tools">
                    <div class="col-sm-6 col-md-6">
                        %MONTH_DD_USERS_REPORT%
                    </div>
                    <div class="col-sm-6 col-md-6">
                        %YEAR_DD_USERS_REPORT%
                    </div>

                </div>
            </div>
            <div class="portlet-body">
                <div id="users_report" data-callback="generateUsersReport()" class="chart"></div>
            </div>
        </div>
        <!-- END INTERACTIVE CHART PORTLET-->
    </div>

</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- BEGIN INTERACTIVE CHART PORTLET-->
        <div class="portlet box blue" data-report-type="jobs">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i>Jobs Report
                </div>
                <div class="tools">
                    <div class="col-sm-6 col-md-6">
                        %MONTH_DD_JOBS_REPORT%
                    </div>
                    <div class="col-sm-6 col-md-6">
                        %YEAR_DD_JOBS_REPORT%
                    </div>

                </div>
            </div>
            <div class="portlet-body">
                <div id="jobs_report" data-callback="generateJobsReport()" class="chart"></div>
            </div>
        </div>
        <!-- END INTERACTIVE CHART PORTLET-->
    </div>

</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- BEGIN INTERACTIVE CHART PORTLET-->
        <div class="portlet box blue" data-report-type="companies">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i>Business Report
                </div>
                <div class="tools">
                    <div class="col-sm-6 col-md-6">
                        %MONTH_DD_COMPANIES_REPORT%
                    </div>
                    <div class="col-sm-6 col-md-6">
                        %YEAR_DD_COMPANIES_REPORT%
                    </div>

                </div>
            </div>
            <div class="portlet-body">
                <div id="companies_report" data-callback="generateCompaniesReport()" class="chart"></div>
            </div>
        </div>
        <!-- END INTERACTIVE CHART PORTLET-->
    </div>

</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- BEGIN INTERACTIVE CHART PORTLET-->
        <div class="portlet box blue" data-report-type="groups">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-users"></i>Groups Report
                </div>
                <div class="tools">
                    <div class="col-sm-6 col-md-6">
                        %MONTH_DD_GROUPS_REPORT%
                    </div>
                    <div class="col-sm-6 col-md-6">
                        %YEAR_DD_GROUPS_REPORT%
                    </div>

                </div>
            </div>
            <div class="portlet-body">
                <div id="groups_report" data-callback="generateGroupsReport()" class="chart"></div>
            </div>
        </div>
        <!-- END INTERACTIVE CHART PORTLET-->
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- BEGIN INTERACTIVE CHART PORTLET-->
        <div class="portlet box blue" data-report-type="revenue_earned">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-money"></i>Revenue Earned
                </div>
                <div class="tools">
                    <div class="col-sm-6 col-md-6">
                        %MONTH_DD_REVENUE_EARNED_REPORT%
                    </div>
                    <div class="col-sm-6 col-md-6">
                        %YEAR_DD_REVENUE_EARNED_REPORT%
                    </div>

                </div>
            </div>
            <div class="portlet-body">
                <div id="revenue_earned_report" data-callback="generateRevenueEarnedReport()" class="chart"></div>
            </div>
        </div>
        <!-- END INTERACTIVE CHART PORTLET-->
    </div>
</div>

<script type="text/javascript">
    
    users_report_array = %USER_REPORT_ARRAY%;
    jobs_report_array = %JOBS_REPORT_ARRAY%;
    companies_report_array = %COMPANIES_REPORT_ARRAY%;
    groups_report_array = %GROUPS_REPORT_ARRAY%;
    revenue_earned_report_array = %REVENUE_EARNED_REPORT_ARRAY%;
    
    jQuery(document).ready(function () {
        console.log(users_report_array);
        Charts.generateUsersReport();
        Charts.generateJobsReport();
        Charts.generateCompaniesReport();
        Charts.generateGroupsReport();
        Charts.generateRevenueEarnedReport();
        
        //$(".month").prop("disabled", true);
        //$(".year").prop("disabled", true);
    });
    
    function updateReport(portlet) {
        //portlet = $(this).parents('.portlet');

        report_type = portlet.data('report-type');

        month = portlet.find('.month').val();
        year = portlet.find('.year').val();
        
        $.ajax({
            type: 'POST',
            url: "<?php echo SITE_ADM_MOD . $this->module ?>/ajax.<?php echo $this->module; ?>.php",
            data: {
                action: 'getReportData',
                report_type: report_type,
                month: month,
                year: year,
            },
            beforeSend: function () {
                addOverlay();
                //portlet.find('.month').prop("disabled", true);
                //portlet.find('.year').prop("disabled", true);
            },
            complete: function () {
                removeOverlay();
            },
            dataType: 'json',
            success: function (result) {
                if (result.status) {
                    report_data = result.report_data;
                    
                    if (report_type == 'users') {
                        users_report_array = report_data;
                        
                        Charts.unBindClickEvent($("#users_report"));
                        Charts.generateUsersReport();
                        return false;
                    } else if (report_type == 'jobs') {
                        jobs_report_array = report_data;
                        
                        Charts.unBindClickEvent($("#jobs_report"));
                        Charts.generateJobsReport();
                        return false;
                    } else if (report_type == 'companies') {
                        companies_report_array = report_data;
                        
                        Charts.unBindClickEvent($("#companies_report"));
                        Charts.generateCompaniesReport();
                        return false;
                    } else if (report_type == 'groups') {
                        groups_report_array = report_data;
                        
                        Charts.unBindClickEvent($("#groups_report"));
                        Charts.generateGroupsReport();
                        return false;
                    } else if (report_type == 'revenue_earned') {
                        revenue_earned_report_array = report_data;
                        
                        Charts.unBindClickEvent($("#revenue_earned_report"));
                        Charts.generateRevenueEarnedReport();
                        return false;
                    }
                    return false;

                } else {
                    toastr["error"](result.message);
                }

            }
        });
    }
    
    $(".chart").on("animatorComplete", function() {
        portlet = $(this).parents('.portlet');
        portlet.find('.month').prop("disabled", false);
        portlet.find('.year').prop("disabled", false);
        return false;
    });

    $(document).on('change', ".month", function () {
        portlet = $(this).parents('.portlet');
        
        updateReport(portlet);
    });
    
    $(document).on('change', ".year", function () {
        portlet = $(this).parents('.portlet');
        updateReport(portlet);
    });

</script>
