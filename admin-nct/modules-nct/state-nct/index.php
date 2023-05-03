<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

include("class.state-nct.php");
$module = "state-nct";
$table = "tbl_state";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' States';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);
if (isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $objPost->stateName = isset($stateName) ? filtering($stateName, 'input') : '';
    $objPost->CountryID = isset($CountryID) ? filtering($CountryID, 'input', 'int') : '';
    $objPost->isActive = isset($isActive) && $isActive == 'y' ? 'y' : 'n';

    if ($objPost->stateName != "") {
        if ($type == 'edit' && $id > 0) {
            if (in_array('edit', $Permission)) {
                
                $db->update($table, array('stateName' => $objPost->stateName, "CountryID" => $objPost->CountryID, 'isActive' => $objPost->isActive), array("StateID" => $id));
                $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                add_admin_activity($activity_array);
                $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'recEdited'));
            } else {
                $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'NoPermission'));
            }
        } else {
            if (in_array('add', $Permission)) {

                if (getTotalRows("tbl_state", "CountryID='".$objPost->CountryID."' AND stateName='" . $objPost->stateName . "'", 'StateID') == 0) {
                    $valArray = array("stateName" => $objPost->stateName, "CountryID" => $objPost->CountryID, "isActive" => $objPost->isActive);
                    $id = $db->insert("tbl_state", $valArray)->getLastInsertId();
                    $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                    add_admin_activity($activity_array);
                    $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'recAdded'));
                } else {
                    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'recExist'));
                }
            } else {
                $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'NoPermission'));
            }
        }
        redirectPage(SITE_ADM_MOD . $module);
    } else {
        $toastr_message = array('type' => 'err', 'var' => 'fillAllvalues');
    }
}

$objState = new State($module, $id, NULL);
$pageContent = $objState->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
