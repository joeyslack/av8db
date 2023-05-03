<!-- BEGIN PAGE HEADER-->
<?php
    $qrySel = $this->db->pdoQuery(" SELECT * FROM  tbl_users ")->results();
    $count_users=count($qrySel);

    $qrySel = $this->db->pdoQuery(" SELECT * FROM  tbl_jobs ")->results();
    $count_jobs=count($qrySel);

    $qrySel = $this->db->pdoQuery(" SELECT * FROM  tbl_companies ")->results();
    $count_company=count($qrySel);

    $qrySel = $this->db->pdoQuery(" SELECT * FROM  tbl_groups ")->results();
    $count_groups=count($qrySel);

    $qrySel = $this->db->pdoQuery(" SELECT SUM(total_price) as total_revenue  FROM  tbl_payment_history WHERE payment_status = 'c' ")->result();
    $total_revenue=$qrySel['total_revenue'];

?>
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
                <a href="#">
                    Statistics
                </a>
            </li>
            <li class="pull-right">
                <div id="dashboard-report-range" class="dashboard-date-range tooltips" data-placement="top" data-original-title="Change dashboard date range">
                    <i class="fa fa-calendar"></i>
                    <span>
                    </span>
                    <i class="fa fa-angle-down"></i>
                </div>
            </li>
        </ul>
        <!-- END PAGE TITLE & BREADCRUMB-->
    </div>
</div>
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo SITE_ADM_MOD. 'users-nct/' ?>">
            <div class="dashboard-stat blue">
                <div class="visual"> <i class="fa fa-user"></i> </div>
                <div class="details"> 
                    <div class="number" >
                        <?php echo $count_users; ?>
                    </div>
                    <div class="desc ">Users</div>
                </div>
            </div>
        </a>
    </div>

     <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo SITE_ADM_MOD. 'jobs-nct/' ?>">
            <div class="dashboard-stat green">
                <div class="visual"> <i class="fa fa-user"></i> </div>
                <div class="details"> 
                    <div class="number" >
                        <?php echo $count_jobs; ?>
                    </div>
                    <div class="desc ">Jobs</div>
                </div>
            </div>
        </a>
    </div>

     <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo SITE_ADM_MOD. 'companies-nct/' ?>">
            <div class="dashboard-stat yellow">
                <div class="visual"> <i class="fa fa-user"></i> </div>
                <div class="details"> 
                    <div class="number" >
                        <?php echo $count_company; ?>
                    </div>
                    <div class="desc ">Companies</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="<?php echo SITE_ADM_MOD. 'groups-nct/' ?>">
            <div class="dashboard-stat purple">
                <div class="visual"> <i class="fa fa-group"></i> </div>
                <div class="details"> 
                    <div class="number" >
                        <?php echo $count_groups; ?>
                    </div>
                    <div class="desc ">Groups</div>
                </div>
            </div>
        </a>
    </div>

     <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a href="javascript:void(0);">
            <div class="dashboard-stat dark">
                <div class="visual"> <i class="fa fa-sort-amount-asc"></i> </div>
                <div class="details"> 
                    <div class="number" >
                        <?php echo CURRENCY_SYMBOL .  $total_revenue; ?>
                    </div>
                    <div class="desc ">Total Revenue</div>
                </div>
            </div>
        </a>
    </div>
</div>
