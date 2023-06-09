<noscript>
    <div style="background-color:#F90; border:#666; font-size:22px; padding:15px; text-align:center">
        <strong>Please enable your javascript to get better performance.</strong>
    </div>
</noscript>

<div class="modal fade" id="myModal_autocomplete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"></h4>  
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="header navbar navbar-fixed-top custom_navbar">
    <div class="header-inner">
        <!-- BEGIN LOGO -->
        <a class="navbar-brand custom_navbar-brand" href="<?php echo SITE_URL; ?>" title="<?php echo SITE_NM; ?>" target="_blank">
            <?php 
                $user_cover = '';
                require_once(DIR_ADM_MOD . 'storage.php');
                $header_storage = new storage();
                $temp_src2 = "site-images-nct/";
                
                $user_cover = $header_storage->getImageUrl1('av8db',SITE_LOGO,$temp_src2);
            ?>
            <div class="custom" style="margin-top:10px;/*margin-left:20px;*/">
            <img src="<?php echo $user_cover; ?>" alt="<?php echo SITE_NM; ?>" />
            </div>
        </a>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <img src="<?php echo SITE_ADM_IMG ?>menu-toggler.png" alt=""/>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <ul class="nav navbar-nav pull-right">

                <?php
                    
                    $adminUserId = $_SESSION['adminUserId'];
                    $uName = getTableValue("tbl_admin", "uName", array("id" => $adminUserId));

                    require_once(DIR_ADM_MOD . 'notifications-nct/class.notifications-nct.php');
                    $objNotifications = new Notifications('notifications-nct', 0, NULL, array(), '');

                    if ($uName != "tempuser") {
                        $unread_notification_count = $objNotifications->getNotificationsCount();
                        
                        ?>
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <li class="dropdown" id="header_notification_bar">
                            <a href="<?php echo SITE_ADM_MOD; ?>notifications-nct/" class="dropdown-toggle" data-hover="dropdown" data-close-others="true">
                                
                                <i class="fa fa-bell-o" id="font-25"></i>
                                
                                <span class="badge <?php if(!$unread_notification_count) { ?> hidden <?php } ?>"><?php echo $unread_notification_count; ?></span>
                            </a>
                            <ul class="dropdown-menu extended notification">
                                <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                        <?php echo $objNotifications->getNotifications();  ?>
                                    </ul>
                                </li>
                                <li class="external">
                                    <a href="<?php echo SITE_ADM_MOD ?>notifications-nct" title="See all notifications">
                                        See all notifications <i class="m-icon-swapright"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END NOTIFICATION DROPDOWN -->
                    <?php } ?>

            <!-- BEGIN USER LOGIN DROPDOWN -->
            <li class="dropdown user">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <img alt="" src="<?php echo SITE_ADM_IMG ?>avatar.png" width="28"/>
                    <span class="username">
                        Hi, <?php echo ucfirst($_SESSION['uName']); ?>
                    </span>
                    <i class="fa fa-angle-down"></i>
                </a>
                <ul class="dropdown-menu">

                    <li>
                        <a href="javascript:;" id="trigger_fullscreen">
                            <i class="fa fa-arrows"></i> Full Screen
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo SITE_ADM_INC; ?>logout-nct.php">
                            <i class="fa fa-key"></i> Log Out
                        </a>
                    </li>
                </ul>
            </li>
            <!-- END USER LOGIN DROPDOWN -->
        </ul>
        <!-- END TOP NAVIGATION MENU -->
    </div>
</div>   
<div class="clearfix"></div>

