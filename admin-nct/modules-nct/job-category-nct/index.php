<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.job-category-nct.php");
$module = "job-category-nct";
$table = "tbl_job_category";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' job categories';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();
    $response['status'] = false;

    extract($_POST);
    $job_category_array = $job_category_description_array = array();
    $error_array = '';
    $job_category_array['job_category'] =filtering($_POST['job_category'][DEFAULT_LANGUAGE_ID], 'input');
    foreach ($job_category as $lkey => $lvalue) {
        if($job_category[$lkey] == ''){
            $error_array .= 'error';
        }
        if($job_category_description[$lkey] == ''){
            $error_array .= 'error';
        }
        $job_category_array['job_category_'.$lkey] = filtering($_POST['job_category'][$lkey], 'input');
        $job_category_description_array['job_category_description_'.$lkey] = filtering($_POST['job_category_description'][$lkey], 'input');
    }
    $job_category_array['job_category'] = filtering($_POST['job_category'][DEFAULT_LANGUAGE_ID], 'input');
    $job_category_description_array['job_category_description'] = filtering($_POST['job_category_description'][DEFAULT_LANGUAGE_ID], 'input');
    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';

    if($error_array == ''){
        $query = "SELECT *  FROM  ".$table." WHERE job_category_".DEFAULT_LANGUAGE_ID." = '" . $job_category[DEFAULT_LANGUAGE_ID] . "' ";        
        if($id > 0) {
            $query .= " AND id != '".$id."' ";
        }
        $checkIfExists = $db->pdoQuery($query)->result();
        if ($checkIfExists) {
            $response['error'] = "Entered job category already exists.";
            echo json_encode($response);
            exit;
        }
        
        if ($type == 'edit' && $id > 0) {
            if (in_array('edit', $Permission)) {
                $objPostArray = (array) $objPost;
                
                $post = array_merge($job_category_array,$job_category_description_array,$objPostArray);

                $db->update($table, $post, array("id" => $id));
                $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Job category has been updated successfully.";
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
                $post = array_merge($job_category_array,$job_category_description_array,$objPostArray);
                $id = $db->insert($table, $post)->getLastInsertId();
                $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Job category has been added successfully.";
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

$searchArray = array();

$objJobCategories = new Job_categories($module, $id, NULL, $searchArray, $type);
$pageContent = $objJobCategories->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
