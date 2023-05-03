<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.membership-plans-nct.php");
$module = "membership-plans-nct";

$table = "tbl_tariff_plans";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Membership Plans';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    if(isset($_POST['save_featured_job_form'])) {
        $response = array();
        $response['status'] = false;

        extract($_POST);

        if ($type == 'edit' && $id > 0) {

            $plan_details = $db->select($table, "*", array("plan_type" => "fj"))->results();
            if ($plan_details) {
                
                $plan_id_count = count($_POST['plan_id']);
                $price_count = count($_POST['price']);

                if($plan_id_count == $price_count) {
                    for($i = 0;$i < $plan_id_count;$i++) {
                        $plan_id = filtering($_POST['plan_id'][$i], 'output', 'int');
                        $price = filtering($_POST['price'][$i], 'output', 'float');

                        $objPost->plan_description = isset($plan_description) ? filtering($plan_description, 'input') : '';
                        $objPost->price = isset($price) ? filtering($price, 'input', 'int') : '';
                        $objPost->updated_on = date("Y-m-d H:i:s");

                        $objPostArray = (array) $objPost;

                        $db->update($table, $objPostArray, array("id" => $plan_id));

                        $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                        add_admin_activity($activity_array);
                    }

                    $response['status'] = true;
                    $response['success'] = "Featured job pricings are updated successfully.";
                    echo json_encode($response);
                    exit;
                } else {
                    $response['error'] = "There seems to be some issue.";
                    echo json_encode($response);
                    exit;
                }

            } else {
                $response['error'] = "The plan you are trying to edit doens't exists.";
                echo json_encode($response);
                exit;
            }
        } else {
            $response['error'] = "You can not add the membership plan.";
            echo json_encode($response);
            exit;
        }
    } else if(isset($_POST['save_adhoc_inmails_form']) || isset($_POST['save_regular_plan_form'])) {
        $response = array();
        $response['status'] = false;
        
        extract($_POST);
       // print_r($_POST);exit();
        if ($type == 'edit' && $id > 0) {

            $plan_details = $db->select($table, "*", array("id" => $id))->result();
            if ($plan_details) {
                $plan_type = filtering($plan_details['plan_type']);

                //$objPost->plan_name = isset($plan_name) ? filtering($plan_name, 'input') : '';
               // $objPost->plan_description = isset($plan_description) ? filtering($plan_description, 'input') : '';

                if ($plan_type == 'r') {
                    $objPost->plan_duration = isset($plan_duration) ? filtering($plan_duration, 'input', 'int') : '';
                    $objPost->plan_duration_unit = isset($plan_duration_unit) ? filtering($plan_duration_unit, 'input', 'int') : '';
                    if($plan_type == 'r' && $plan_name == 'Ferry Pilot' || $id == '8'){
                        $objPost->no_of_inmails = '0';    
                    }else{
                        $objPost->no_of_inmails = isset($no_of_inmails) ? filtering($no_of_inmails, 'input', 'int') : '';
                    }
                }

                $objPost->price = isset($price) ? filtering($price, 'input', 'int') : '';

                $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';
                $objPost->updated_on = date("Y-m-d H:i:s");

                if (in_array('edit', $Permission)) {
                    if ($plan_type == 'r') {
                        if (getTotalRows($table, " plan_name_".DEFAULT_LANGUAGE_ID." = '" . $plan_name[DEFAULT_LANGUAGE_ID] . "' AND plan_duration = '" . $objPost->plan_duration . "' AND plan_duration_unit = '" . $objPost->plan_duration_unit . "' AND id != '" . $id . "'", 'id') > 0) {
                            $response['error'] = "Membership Plan with same name and duration already exists!";
                            echo json_encode($response);
                            exit;
                        }
                    }

                    $plan_name_array = $plan_description_array = array();
                    $error_array = '';
                    foreach ($plan_name as $lkey => $lvalue) {
                        if($plan_name[$lkey] == ''){
                            $error_array .= 'error';
                        }
                        if($plan_description[$lkey] == ''){
                            $error_array .= 'error';
                        }
                        $plan_name_array['plan_name_'.$lkey] = filtering($_POST['plan_name'][$lkey], 'input');
                        $plan_description_array['plan_description_'.$lkey] = filtering($_POST['plan_description'][$lkey], 'input');
                    }
                    $plan_name_array['plan_name'] = filtering($_POST['plan_name'][DEFAULT_LANGUAGE_ID], 'input');
                    $plan_description_array['plan_description'] = filtering($_POST['plan_description'][DEFAULT_LANGUAGE_ID], 'input');

                    $post = array_merge($plan_name_array,$plan_description_array,(array) $objPost);
                    $post['plan_name']=$_POST['plan_name_r'];
                    $post['plan_description']=$_POST['plan_description_r'];
                    if($error_array == ''){
                        $db->update($table, $post, array("id" => $id));
                        $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                        add_admin_activity($activity_array);

                        $response['status'] = true;
                        $response['success'] = "Tariff Plan has been updated successfully.";
                    } else {
                        $response['error'] = "Please enter all the details.";
                    }
                    
                    echo json_encode($response);
                    exit;
                } else {
                    $response['error'] = "You don't have permission to update membership plan.";
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['error'] = "The plan you are trying to edit doens't exists.";
                echo json_encode($response);
                exit;
            }
        } else {
            $response['error'] = "You can not add the membership plan.";
            echo json_encode($response);
            exit;
        }
    }

}

$objMembershipPlan = new membershipplan($module, $id, NULL);
$pageContent = $objMembershipPlan->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
