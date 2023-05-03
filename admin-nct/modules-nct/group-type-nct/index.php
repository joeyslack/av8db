<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.group-type-nct.php");
$module = "group-type-nct";
$table = "tbl_group_types";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' group types';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    
    $response = array();
    $response['status'] = false;

    extract($_POST);

    $objPost->group_type = filtering($_POST['group_type'], 'input');
    $objPost->group_type_description = filtering($_POST['group_type_description'], 'input');
    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';
    $default_lang_id = get_languages('default');

    $group_type_array = $group_type_description_array = array();
    $error_array = '';
    foreach ($group_type as $lkey => $lvalue) {
        if($group_type[$lkey] == ''){
            $error_array .= 'error';
        }
        if($group_description[$lkey] == ''){
            $error_array .= 'error';
        }
        $group_type_array['group_type_'.$lkey] = filtering($_POST['group_type'][$lkey], 'input');
        $group_type_description_array['group_type_description_'.$lkey] = filtering($_POST['group_description'][$lkey], 'input');
    }
    $group_type_array['group_type'] = filtering($_POST['group_type'][DEFAULT_LANGUAGE_ID], 'input');
    $group_type_description_array['group_type_description'] = filtering($_POST['group_description'][DEFAULT_LANGUAGE_ID], 'input');
    
    
    if($error_array == ''){
        $query = "SELECT * FROM  ".$table." WHERE group_type_".$default_lang_id[0]['id']." = '" . $group_type[$default_lang_id[0]['id']] . "' ";
        if($id > 0) {
            $query .= " AND id != '".$id."' ";
        }
        $checkIfExists = $db->pdoQuery($query)->result();
        if ($checkIfExists) {
            $response['error'] = "Entered group type already exists.";
        } else {
            if ($type == 'edit' && $id > 0) {
                if (in_array('edit', $Permission)) {
                    //$objPostArray = (array) $objPost;
                    $post = array_merge($group_type_array,$group_type_description_array);
                    $post['status'] = $objPost->status;
                    $post['updated_on'] = date('Y-m-d H:i:s');
                    $db->update($table, $post, array("id" => $id));
                    $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                    add_admin_activity($activity_array);
                    $response['status'] = true;
                    $response['success'] = "Group type has been updated successfully.";
                } else {
                    $response['error'] = "You don't have permission.";
                }
            } else {
                if (in_array('add', $Permission)) {
                    $post = array_merge($group_type_array,$group_type_description_array);
                    $post['added_on'] = date('Y-m-d H:i:s');
                    $post['status'] = $objPost->status;
                    $id = $db->insert($table, $post)->getLastInsertId();
                    $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                    add_admin_activity($activity_array);
                    $response['status'] = true;
                    $response['success'] = "Group type has been added successfully.";
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

$objGroupTypes = new Group_type($module, $id, NULL);
$pageContent = $objGroupTypes->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
