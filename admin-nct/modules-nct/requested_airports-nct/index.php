<?php
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.requested_airports-nct.php");
$module = "requested_airports-nct";
$table = "tbl_airport";

$include_google_maps_js = true;

$styles = array(array("data-tables/DT_bootstrap.css", SITE_ADM_PLUGIN),
    array("bootstrap-switch/css/bootstrap-switch.min.css", SITE_ADM_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/css/select2.min.css", SITE_PLUGIN));

$scripts = array("core/datatable.js",
    array("data-tables/jquery.dataTables.js", SITE_ADM_PLUGIN),
    array("data-tables/DT_bootstrap.js", SITE_ADM_PLUGIN),
    array("bootstrap-switch/js/bootstrap-switch.min.js", SITE_ADM_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/js/select2.full.min.js", SITE_PLUGIN),
    array("ckeditor_4.5.10_standard/ckeditor/ckeditor.js", SITE_PLUGIN));

chkPermission($module);
$Permission = chkModulePermission($module);
$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    "author" => SITE_NM));

$id = isset($_GET["id"]) ? (int) trim($_GET["id"]) : 0;
$postType = isset($_POST["type"]) ? trim($_POST["type"]) : '';
$type = isset($_GET["type"]) ? trim($_GET["type"]) : $postType;

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Airports';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

$searchArray = array();
    
$objLicenses = new RequestedLicensesEndorsements($module, $id, NULL, $searchArray, $type);
$pageContent = $objLicenses->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");