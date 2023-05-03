<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.jobs-nct.php");
$module = "jobs-nct";
$table = "tbl_jobs";

$include_google_maps_js = true;
//$init_autocomplete = true;

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' jobs';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


if (isset($_POST["company_id"]) && isset($_POST["job_category_id"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "<pre>";print_r($_POST);exit;
    $response = array();
    $response['status'] = false;

    extract($_POST);
    $objPost->company_id = filtering($_POST['company_id'], 'input', 'int');
    $objPost->job_category_id = filtering($_POST['job_category_id'], 'input', 'int');
    $objPost->job_title = filtering($_POST['job_title'], 'input');
    
    //$objPost->relavent_experience_from = filtering($_POST['relavent_experience_from'], 'input', 'float');
    $objPost->relavent_experience_to = filtering($_POST['relavent_experience_to'], 'input', 'float');
    $objPost->employment_type = filtering($_POST['employment_type'], 'input');
    
    $objPost->key_responsibilities = filtering($_POST['key_responsibilities'], 'input', 'text');
    //$objPost->skills_and_exp = filtering($_POST['skills_and_exp'], 'input', 'text');

    $objPost->last_date_of_application = date("Y-m-d", strtotime($_POST['last_date_of_application']));

    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';
    $objPost->updated_on = date("Y-m-d H:i:s");

    // Location details
    $formatted_address = filtering($_POST['formatted_address'], 'input');
    $address1 = filtering($_POST['address1'], 'input');
    $address2 = filtering($_POST['address2'], 'input');
    $country = filtering($_POST['country'], 'input');
    $state = filtering($_POST['state'], 'input');
    $city1 = filtering($_POST['city1'], 'input');
    $city2 = filtering($_POST['city2'], 'input');
    $postal_code = filtering($_POST['postal_code'], 'input');
    $latitude = filtering($_POST['latitude'], 'input');
    $longitude = filtering($_POST['longitude'], 'input');

    if ($objPost->company_id == "") {
        $response['error'] = "Please select a company.";
        echo json_encode($response);
        exit;
    }

    if ($objPost->job_category_id == "") {
        $response['error'] = "Please select job category.";
        echo json_encode($response);
        exit;
    }

    if ($objPost->job_title == "") {
        $response['error'] = "Please enter job title.";
        echo json_encode($response);
        exit;
    }
    

    if ($objPost->last_date_of_application == "") {
        $response['error'] = "Please select the last date of application.";
        echo json_encode($response);
        exit;
    }

    if($formatted_address != '' && $latitude != '' && $longitude != '') {

        $location_details_array = array(
            "formatted_address" => $formatted_address,
            "address1" => $address1,
            "address2" => $address2,
            "country" => $country,
            "state" => $state,
            "city1" => $city1,
            "city2" => $city2,
            "postal_code" => $postal_code,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "date_added" => date("Y-m-d H:i:s"),
            "date_updated" => date("Y-m-d H:i:s")
        );

        $location_id = $db->insert("tbl_locations", $location_details_array)->getLastInsertId();

        $objPost->location_id = $location_id;

    }

    if ($type == 'edit' && $id > 0) {

        if (in_array('edit', $Permission)) {

            $objPostArray = (array) $objPost;
            $db->update($table, $objPostArray, array("id" => $id));

            //For job skills
            //$db->delete("tbl_job_skills", array("job_id" => $id));

            // if(isset($_POST['skill_id']) && $_POST['skill_id'] != '') {
            //     foreach ($_POST['skill_id'] as $key => $value) {
            //         $db->insert('tbl_job_skills', array('job_id' => $id, 'skill_id' => $value, 'added_on' => date('Y-m-d H:i:s')));
            //     }
            // }

            $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
            add_admin_activity($activity_array);

            $response['status'] = true;
            $response['success'] = "Job has been updated successfully.";
            echo json_encode($response);
            exit;
        } else {
            $response['error'] = "You don't have permission.";
            echo json_encode($response);
            exit;
        }
    } else {
        if (in_array('add', $Permission)) {
            $objPost->added_by_admin = 'y';
            $objPost->added_on = date("Y-m-d H:i:s");

            $objPostArray = (array) $objPost;
            $id = $db->insert($table, $objPostArray)->getLastInsertId();

            $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
            add_admin_activity($activity_array);

            $response['status'] = true;
            $response['success'] = "Job has been added successfully.";
            echo json_encode($response);
            exit;
        } else {
            $response['error'] = "You don't have permission.";
            echo json_encode($response);
            exit;
        }
    }

}

$searchArray = array();

$objJobs = new Jobs($module, $id, NULL, $searchArray, $type);
$pageContent = $objJobs->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
