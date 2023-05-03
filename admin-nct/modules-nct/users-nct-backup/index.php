<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.users-nct.php");
$module = "users-nct";
$table = "tbl_users";


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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' User';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if (isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "<pre>";print_r($_POST);exit;
    extract($_POST);
    $objPost->id = isset($id) ? $id : '';
    $objPost->first_name = isset($first_name) ? $first_name : '';
    $objPost->last_name = isset($last_name) ? $last_name : '';
    $objPost->email_address = isset($email_address) ? $email_address : '';
    $objPost->gender = isset($gender) ? $gender : 'm';
    $objPost->phone_no = isset($phone_no) ? $phone_no : '';
    
    $objPost->status = isset($status) ? $status : 'a';

    if ($objPost->first_name != "" && strlen($objPost->first_name) > 0) {
        if ($type == 'edit' && $id > 0) {
            if (in_array('edit', $Permission)) {

                $db->update($table, array(
                    "first_name" => $objPost->first_name,
                    "last_name" => $objPost->last_name,
                    "gender" => $objPost->gender,
                    "phone_no" => $objPost->phone_no,
                    "status" => $objPost->status,
                    "date_updated" => date("Y-m-d H:i:s")
                        ), array("id" => $id));

                $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                add_admin_activity($activity_array);
                $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'User has been updated successfully.'));
            } else {
                $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'NoPermission'));
            }
        } else {
            if (in_array('add', $Permission)) {
                if (getTotalRows($table, "firstName='" . $objPost->fname . "'", 'uId') == 0) {
                    $objPost->created_date = date('Y-m-d H:i:s');

                    $valArray = array(
                        "first_name" => $objPost->first_name,
                        "last_name" => $objPost->last_name,
                        "email_address" => $objPost->email_address,
                        "gender" => $objPost->gender,
                        "phone_no" => $objPost->phone_no,
                        "status" => $objPost->status
                    );

                    $id = $db->insert("tbl_users", $valArray)->getLastInsertId();
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

$objUsers = new Users($module);
$pageContent = $objUsers->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
