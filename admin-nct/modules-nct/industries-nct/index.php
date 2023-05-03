<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.industries-nct.php");
$module = "industries-nct";
$table = "tbl_industries";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' business type';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();
    $response['status'] = false;

    extract($_POST);
    /*$objPost->industry_name = filtering($_POST['industry_name'], 'input');
    $objPost->industry_description = filtering($_POST['industry_description'], 'input');*/

    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';
    $default_lang_id = get_languages('default');

    $industry_name_array = $industry_description_array = array();
    $error_array = '';
    foreach ($industry_name as $lkey => $lvalue) {
        if($industry_name[$lkey] == ''){
            $error_array .= 'error';
        }
        if($industry_description[$lkey] == ''){
            $error_array .= 'error';
        }
        $industry_name_array['industry_name_'.$lkey] = filtering($_POST['industry_name'][$lkey], 'input');
        $industry_description_array['industry_description_'.$lkey] = filtering($_POST['industry_description'][$lkey], 'input');
    }
    $industry_name_array['industry_name'] = filtering($_POST['industry_name'][DEFAULT_LANGUAGE_ID], 'input');
    $industry_description_array['industry_description'] = filtering($_POST['industry_description'][DEFAULT_LANGUAGE_ID], 'input');
    
    if($error_array == ''){
        $query = "SELECT * FROM  ".$table." WHERE industry_name_".DEFAULT_LANGUAGE_ID." = '" . $industry_name[DEFAULT_LANGUAGE_ID] . "' ";
        if($id > 0) {
            $query .= " AND id != '".$id."' ";
        }
        $checkIfExists = $db->pdoQuery($query)->result();
        if ($checkIfExists) {
            $response['error'] = "Entered business type name already exists.";
        } else {
            if ($type == 'edit' && $id > 0) {
                if (in_array('edit', $Permission)) {
                    //$objPostArray = (array) $objPost;
                    $post = array_merge($industry_name_array,$industry_description_array);
                    $post['status'] = $objPost->status;
                    $post['updated_on'] = date('Y-m-d H:i:s');
                    $db->update($table, $post, array("id" => $id));
                    $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                    add_admin_activity($activity_array);
                    $response['status'] = true;
                    $response['success'] = "Business type name has been updated successfully.";
                } else {
                    $response['error'] = "You don't have permission.";
                }
            } else {
                if (in_array('add', $Permission)) {
                    $post = array_merge($industry_name_array,$industry_description_array);
                    $post['added_on'] = date('Y-m-d H:i:s');
                    $post['status'] = $objPost->status;
                    $id = $db->insert($table, $post)->getLastInsertId();
                    $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                    add_admin_activity($activity_array);
                    $response['status'] = true;
                    $response['success'] = "Business type name has been added successfully.";
                } else {
                    $response['error'] = "You don't have permission.";
                }
            }
        }
    } else {
        $response['error'] = "Please enter all the details.";
    }
    echo json_encode($response);
    exit;

   
}

$objIndustries = new Industries($module, $id, NULL);
$pageContent = $objIndustries->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
