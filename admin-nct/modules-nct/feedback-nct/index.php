<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.feedback-nct.php");
$module = "feedback-nct";
$table = "tbl_contact_us";
$styles = array(array("data-tables/DT_bootstrap.css", SITE_ADM_PLUGIN),
    array("bootstrap-switch/css/bootstrap-switch.min.css", SITE_ADM_PLUGIN));

$scripts = array("core/datatable.js",
    array("data-tables/jquery.dataTables.js", SITE_ADM_PLUGIN),
    array("data-tables/DT_bootstrap.js", SITE_ADM_PLUGIN),
    array("bootstrap-switch/js/bootstrap-switch.min.js", SITE_ADM_PLUGIN));

chkPermission($module);
$Permission = chkModulePermission($module);
$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    "author" => SITE_NM));

$id = isset($_GET["id"]) ? (int) trim($_GET["id"]) : 0;
$postType = isset($_POST["type"]) ? trim($_POST["type"]) : '';
$type = isset($_GET["type"]) ? trim($_GET["type"]) : $postType;

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Feedback';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

$objFeedback = new Feedback($module, $id, NULL);
$pageContent = $objFeedback->getPageContent();

require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
