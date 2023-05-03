<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.skills-nct.php");
$module = "skills-nct";
$table = "tbl_skills";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Skills';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $response = array();
    $response['status'] = false;

    extract($_POST);
    
    $skill_name_array = $skill_description_array = array();
    $error_array = '';
    foreach ($skill_name as $lkey => $lvalue) {
        if($skill_name[$lkey] == ''){
            $error_array .= 'error';
        }
        if($skill_description[$lkey] == ''){
            $error_array .= 'error';
        }
        $skill_name_array['skill_name_'.$lkey] = filtering($_POST['skill_name'][$lkey], 'input');
        $skill_description_array['skill_description_'.$lkey] = filtering($_POST['skill_description'][$lkey], 'input');
    }
    $skill_name_array['skill_name']=filtering($_POST['skill_name'][DEFAULT_LANGUAGE_ID], 'input');
    $skill_description_array['skill_description'] = filtering($_POST['skill_description'][DEFAULT_LANGUAGE_ID], 'input');

    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';

    if($error_array == ''){
        $query="SELECT * FROM  ".$table." WHERE skill_name_".DEFAULT_LANGUAGE_ID." = '" . $skill_name[DEFAULT_LANGUAGE_ID]."'";
        if($id > 0) {
            $query .= " AND id != '".$id."' ";
        }
        $checkIfExists = $db->pdoQuery($query)->result();
        if ($checkIfExists) {
            $response['error'] = "Entered skill name already exists.";
            echo json_encode($response);
            exit;
        }
        
        if ($type == 'edit' && $id > 0) {

            if (in_array('edit', $Permission)) {
                $objPostArray = (array) $objPost;
                $post = array_merge($skill_name_array,$skill_description_array,$objPostArray);
                $db->update($table, $post, array("id" => $id));

                $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Skill has been updated successfully.";
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
                $post = array_merge($skill_name_array,$skill_description_array,$objPostArray);
                
                $id = $db->insert($table, $post)->getLastInsertId();

                $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                add_admin_activity($activity_array);

                $response['status'] = true;
                $response['success'] = "Skill has been added successfully.";
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

$objSkills = new Skills($module, $id, NULL);
$pageContent = $objSkills->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
