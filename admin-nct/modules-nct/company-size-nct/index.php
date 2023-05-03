<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.company-size-nct.php");
$module = "company-size-nct";
$table = "tbl_company_sizes";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' company sizes';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if (isset($_POST["minimum_no_of_employee"]) && isset($_POST["maximum_no_of_employee"]) && $_SERVER["REQUEST_METHOD"] =="POST") {
    $response = array();
    $response['status'] = false;

    extract($_POST);
    //$objPost->company_size = filtering($_POST['company_size'], 'input');
    $objPost->minimum_no_of_employee = filtering($_POST['minimum_no_of_employee'], 'input');
    $objPost->maximum_no_of_employee = filtering($_POST['maximum_no_of_employee'], 'input');

    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';

    $company_size_array = array();
    $error_array = '';
    foreach ($company_size as $lkey => $lvalue) {
        if($company_size[$lkey] == ''){
            $error_array .= 'error';
        }
        $company_size_array['company_size_'.$lkey] = filtering($_POST['company_size'][$lkey], 'input');
    }
    $company_size_array['company_size'] = filtering($_POST['company_size'][DEFAULT_LANGUAGE_ID], 'input');
    if ($objPost->minimum_no_of_employee != "" && $objPost->maximum_no_of_employee != "" && $error_array == '') {
        $query = "SELECT * FROM  ".$table." WHERE company_size_".DEFAULT_LANGUAGE_ID." = '" . $company_size[DEFAULT_LANGUAGE_ID] . "' ";
        if($id > 0) {
            $query .= " AND id != '".$id."' ";
        }
        
        $checkIfExists = $db->pdoQuery($query)->result();
        
        if ($checkIfExists) {
            $response['error'] = "Entered company size already exists.";
            echo json_encode($response);
            exit;
        }

        if ($objPost->minimum_no_of_employee > $objPost->maximum_no_of_employee) {
            $response['error'] = "Please enter value of Maximum no. of employee greater than Minimum no. of employees.";
            echo json_encode($response);
            exit;
        }
        
        $query_to_check_overlap = "SELECT * FROM  ".$table." WHERE  ( 
            ( minimum_no_of_employee <= '".$objPost->minimum_no_of_employee."' AND '".$objPost->minimum_no_of_employee."' <= maximum_no_of_employee ) OR  
            ( minimum_no_of_employee <= '".$objPost->maximum_no_of_employee."' AND '".$objPost->maximum_no_of_employee."' <= maximum_no_of_employee )) ";
        
        if($id > 0) {
            $query_to_check_overlap .= " AND id != '".$id."' ";
        }
        //echo $query_to_check_overlap;exit;
        $checkIfOverlaps = $db->pdoQuery($query_to_check_overlap)->result();
        
        if($checkIfOverlaps) {
            $response['error'] = "Entered number of employees overlaps with already added company size.";
            echo json_encode($response);
            exit;
        }
        
        if ($type == 'edit' && $id > 0) {

            if (in_array('edit', $Permission)) {
                

                $objPostArray = (array) $objPost;
                $post = array_merge($company_size_array,$objPostArray);
                $db->update($table, $post, array("id" => $id));

                $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Company size has been updated successfully.";
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
                $post = array_merge($company_size_array,$objPostArray);
                $id = $db->insert($table, $post)->getLastInsertId();

                $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Company size has been added successfully.";
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

$objCompanySize = new Company_size($module, $id, NULL);
$pageContent = $objCompanySize->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
