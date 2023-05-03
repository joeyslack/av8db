<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.degrees-nct.php");
$module = "degrees-nct";
$table = "tbl_degrees";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Education';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();
    $response['status'] = false;

    extract($_POST);
    
    $degree_name_array = array();
    $error_array = '';
    foreach ($degree_name as $lkey => $lvalue) {
        if($degree_name[$lkey] == ''){
            $error_array .= 'error';
        }
        $degree_name_array['degree_name_'.$lkey] = filtering($_POST['degree_name'][$lkey], 'input');
    }
    $degree_name_array['degree_name'] = filtering($_POST['degree_name'][DEFAULT_LANGUAGE_ID], 'input');
    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';

    if($error_array == ''){
        $query = "SELECT * FROM  ".$table." WHERE degree_name_".DEFAULT_LANGUAGE_ID." = '" . $degree_name[DEFAULT_LANGUAGE_ID] . "' ";
        
        if($id > 0) {
            $query .= " AND id != '".$id."' ";
        }
        
        $checkIfExists = $db->pdoQuery($query)->result();
        
        if ($checkIfExists) {
            $response['error'] = "Entered degree name already exists.";
            echo json_encode($response);
            exit;
        }

        
        if ($type == 'edit' && $id > 0) {

            if (in_array('edit', $Permission)) {

                $objPostArray = (array) $objPost;
                $post = array_merge($degree_name_array,$objPostArray);
                $db->update($table, $post, array("id" => $id));

                $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Degree has been updated successfully.";
                echo json_encode($response);
                exit;
            } else {
                $response['error'] = "You don't have permission.";
                echo json_encode($response);
                exit;
            }
        } else {
            if (in_array('add', $Permission)) {


                $objPost->added_on = date("Y-m-d H:i:s");

                $objPostArray = (array) $objPost;
                $post = array_merge($degree_name_array,$objPostArray);
                $id = $db->insert($table, $post)->getLastInsertId();

                $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Degree has been added successfully.";
                echo json_encode($response);
                exit;
            } else {
                $response['error'] = "You don't have permission.";
                echo json_encode($response);
                exit;
            }
        }
    } else {
        $response['error'] = "Please enter all the details.";
        echo json_encode($response);
        exit;
    }
}

$objDegrees = new Degrees($module, $id, NULL);
$pageContent = $objDegrees->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
