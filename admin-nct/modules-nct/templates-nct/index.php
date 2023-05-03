<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.templates-nct.php");
$module = "templates-nct";
$table = "tbl_email_templates";

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
    'author' => AUTHOR));

$id = isset($_GET["id"]) ? (int) trim($_GET["id"]) : 0;
$postType = isset($_POST["type"]) ? trim($_POST["type"]) : '';
$type = isset($_GET["type"]) ? trim($_GET["type"]) : $postType;

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Email Templates';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if (isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    $objPost->subject = isset($subject) ? filtering($subject, 'input') : '';
    $objPost->templates = isset($templates) ? filtering($templates, 'input', 'text') : '';
    $objPost->description = isset($description) ? filtering($description, 'input') : '';

    if ($type == 'edit' && $id > 0) {
        if ($type == 'edit' && $id > 0) {
            $db->update($table, array("subject" => $objPost->subject, "description" => $objPost->description, "templates" => $objPost->templates), array("id" => $id));
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'recEdited'));
        } else {
            $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'NoPermission'));
        }
    } else {
        if (in_array('add', $Permission)) {
            $objPost->updateDate = date('Y-m-d H:i:s');
            
            $valArray = array(
                "subject" => $objPost->subject,
                "description" => $objPost->description,
                "templates" => $objPost->templates,
                "updateDate" => $objPost->updateDate
            );
            
            $db->insert("tbl_email_templates", $valArray);
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'recAdded'));
        } else {
            $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'NoPermission'));
        }
    }
    redirectPage(SITE_ADM_MOD . $module);
}

$objTemplate = new Templates($module);
$pageContent = $objTemplate->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
