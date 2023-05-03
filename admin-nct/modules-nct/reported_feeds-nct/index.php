<?php
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.reported_feeds-nct.php");
$module = "reported_feeds-nct";
$table = "tbl_feeds";

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
$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Reported Feed';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

// if ($_SERVER["REQUEST_METHOD"] == "POST") {

//     $response = array();
//     $response['status'] = false;
//     extract($_POST);
//     //echo "<pre>";print_r($_POST);exit();

//     $objPost->review_description = isset($_POST['review_desc']) ? $_POST['review_desc'] : '';
//     $objPost->isReported = 'n';
//     $objPost->rating = isset($_POST['score']) ? $_POST['score'] : '';

//     if ($type == 'edit' && $id > 0) {

//         if (in_array('edit', $Permission)) {
//             $objPost->updatedAt = date('Y-m-d H:i:s');            
    
//             $objPostArray = (array) $objPost;
          
//             $db->update($table, $objPostArray, array("id" => $id));

//             $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
//             add_admin_activity($activity_array);

//             $response['status'] = true;
//             $response['success'] = "Ferry pilot review has been updated successfully.";
//         } else {
//             $response['error'] = "You don't have permission.";
//         }
//     }
//     echo json_encode($response);
//     exit;
// }

$searchArray = array();

$objLicenses = new ReportedFeeds($module, $id, NULL, $searchArray, $type);
$pageContent = $objLicenses->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
