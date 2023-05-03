<?php
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.licenses_endorsements-nct.php");
$module = "licenses_endorsements-nct";
$table = "tbl_license_endorsements";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Licenses & Endorsements';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


//if (isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //  echo "<pre>";print_r($_POST);

    $response = array();
    $response['status'] = false;
    extract($_POST);
    //print_r($_POST);exit();
    $licenses_endorsements_array = array();
    $error_array = '';
    $licenses_endorsements_array['licenses_endorsements_name'] = filtering($_POST['licenses_endorsement_name'][DEFAULT_LANGUAGE_ID], 'input');
    foreach ($licenses_endorsement_name as $lkey => $lvalue) {
        if($licenses_endorsement_name[$lkey] == ''){
            $error_array .= 'error';
        }
        $licenses_endorsements_array['licenses_endorsements_name_'.$lkey] = filtering($_POST['licenses_endorsement_name'][$lkey], 'input');
    }
    // $objPost->is_default = 'n';    
    //$objPost->flight_hours = isset($_POST['flight_hours']) ? $_POST['flight_hours'] : '0';    
    $objPost->isCommercial = isset($_POST['commercial']) ? $_POST['commercial'] : 'n';    
    $objPost->isLicense = isset($_POST['license']) ? $_POST['license'] : 'n';
    $objPost->isBoth = isset($_POST['both']) ? $_POST['both'] : 'n';
    $objPost->isNone = isset($_POST['none']) ? $_POST['none'] : 'n';
    $objPost->isActive = isset($status) && $status == 'y' ? 'y' : 'n';

    if ($type == 'edit' && $id > 0) {

        if (in_array('edit', $Permission)) {
            $objPost->updatedAt = date('Y-m-d H:i:s');            
    
            $objPostArray = (array) $objPost;
            $post = array_merge($licenses_endorsements_array,$objPostArray);
            $db->update($table, $post, array("id" => $id));

            $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
            add_admin_activity($activity_array);

            $response['status'] = true;
            $response['success'] = "Licenses and Endorsements has been updated successfully.";

            //$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => "Licenses and Endorsements has been updated successfully."));
            //redirectPage(SITE_ADM_MOD . $module);
            //exit;
        } else {
            $response['error'] = "You don't have permission.";
            // $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "You don't have permission."));
            // redirectPage(SITE_ADM_MOD . $module);
        }
    } else {
        if (in_array('add', $Permission)) {
            
            $objPost->createdAt = date("Y-m-d H:i:s");

            $objPostArray = (array) $objPost;
            $post = array_merge($licenses_endorsements_array,$objPostArray);
            //echo "<pre>";print_r($post);exit();
            $id = $db->insert($table, $post)->getLastInsertId();
            //print_r($id);
            $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
            add_admin_activity($activity_array);
            $response['status'] = true;
            $response['success'] = "Licenses and Endorsements has been added successfully.";
            // $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'Licenses and Endorsements has been added successfully.'));
            // redirectPage(SITE_ADM_MOD . $module);
        } else {
            $response['error'] = "You don't have permission.";
        }
    }
    echo json_encode($response);
    exit;
}

$searchArray = array();

$objLicenses = new LicensesEndorsements($module, $id, NULL, $searchArray, $type);
$pageContent = $objLicenses->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
