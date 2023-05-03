<?php

$reqAuth = true;

require_once(DIR_URL."includes-nct/config-nct.php");
include("class.statistics-nct.php");

$module = "statistics-nct";
$page_name = "statistics";

$winTitle = 'Welcome to Admin Panel - ' . SITE_NM;
$headTitle = 'Welcome to Admin Panel';
$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    'author' => AUTHOR));

$breadcrumb = array("Dashboard");

$mainObj = new Statistics();
$pageContent = $mainObj->getPageContent();


require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
