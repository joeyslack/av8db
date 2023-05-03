<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.notifications-nct.php");

$module = 'notifications-nct';

$header_panel = true;
$left_panel = true;
$footer_panel = true;

$table = 'tbl_notifications';

$winTitle = '{LBL_NOTIFICATIONS} - ' . SITE_NM;

$styles = array();
$scripts = array();

$metaTag = getMetaTagsAll(array('description' => $final_description,
    'keywords' => $final_keywords,
    'og_title' => $winTitle
));

$objNotifications = new Notifications();

$pageContent = $objNotifications->getPageContent1();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
