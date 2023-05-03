<?php
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.airports-nct.php");
$module = "airports-nct";
$table = "tbl_airport";

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
$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Airports';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();
    $response['status'] = false;
    extract($_POST);
    //echo "<pre>";print_r($_POST);exit();
    $airport_name_array = array();
    $error_array = '';
    
    foreach ($airport_name as $lkey => $lvalue) {
        if($airport_name[$lkey] == ''){
            $error_array .= 'error';
        }
        $airport_name_array['airport_name_'.$lkey] = filtering($_POST['airport_name'][$lkey], 'input');
    }
    $airport_name_array['airport_name'] = filtering($_POST['airport_name'][DEFAULT_LANGUAGE_ID], 'input');
    
    $objPost->country_id = isset($_POST['country']) ? $_POST['country'] : '0';    
    $objPost->state_id = isset($_POST['state']) ? $_POST['state'] : '0';
    $objPost->city_id = isset($_POST['city']) ? $_POST['city'] : '0';
    $objPost->location = isset($_POST['location']) ? $_POST['location'] : '';
    $objPost->airport_identifier = isset($_POST['airport_identifier']) ? $_POST['airport_identifier'] : '';
    $objPost->status = isset($_POST['status']) && $_POST['status'] == 'a' ? 'a' : 'd';

    if ($type == 'edit' && $id > 0) {

        if (in_array('edit', $Permission)) {
            $objPost->updatedAt = date('Y-m-d H:i:s');            
    
            $objPostArray = (array) $objPost;
            $post = array_merge($airport_name_array,$objPostArray);
            $db->update($table, $post, array("id" => $id));

            $user_airport_id = $db->select("tbl_user_airports", "*", array("airport_id" => $id,"isActive" => 'n'))->result();
            
            if ($user_airport_id['id'] > 0 ){
                if($objPost->status == 'a')
                    $status = 'y';
                else
                    $status = 'n';
                $setVal1 = array('isActive'=>$status);
                $db->update('tbl_user_airports', $setVal1, array("airport_id" => $id));

                $user_info = $db->select("tbl_users", "*", array("id" => $user_airport_id['user_id']))->result();
                if($user_info['id'] > 0){
                    $arrayCont['greetings'] = ucwords($user_info['first_name']);
                    $arrayCont['link'] = "<a href='".SITE_URL."profile/"."' target='_blank'>Click here</a>";
                    generateEmailTemplateSendEmail("airport_accepted", $arrayCont, $user_info['email_address']);
                }
            }

            $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
            add_admin_activity($activity_array);

            $response['status'] = true;
            $response['success'] = "Airport has been updated successfully.";

        } else {
            $response['error'] = "You don't have permission.";
        }
    } else {
        if (in_array('add', $Permission)) {
            
            $objPost->createdAt = date("Y-m-d H:i:s");

            $objPostArray = (array) $objPost;
            $post = array_merge($airport_name_array,$objPostArray);
            
            $id = $db->insert($table, $post)->getLastInsertId();

            $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
            add_admin_activity($activity_array);
            $response['status'] = true;
            $response['success'] = "Airport has been added successfully.";
        } else {
            $response['error'] = "You don't have permission.";
        }
    }
    echo json_encode($response);
    exit;
}

$searchArray = array();

$objLicenses = new Airports($module, $id, NULL, $searchArray, $type);
$pageContent = $objLicenses->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
