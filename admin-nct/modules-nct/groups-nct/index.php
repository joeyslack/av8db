<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.groups-nct.php");
$module = "groups-nct";
$table = "tbl_groups";

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' groups';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


if (isset($_POST["group_name"]) && isset($_POST["group_type_id"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "<pre>";print_r($_POST);exit;
    $response = array();
    $response['status'] = false;

    extract($_POST);
    $objPost->group_name = filtering($_POST['group_name'], 'input');
    $objPost->group_description = filtering($_POST['group_description'], 'input');

    $objPost->group_type_id = filtering($_POST['group_type_id'], 'input', 'int');
    //$objPost->group_industry_id = filtering($_POST['group_industry_id'], 'input', 'int');

    $objPost->privacy = filtering($_POST['privacy']);
    $objPost->accessibility = filtering($_POST['accessibility']);

    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';

    if ($objPost->group_name == "") {
        $response['error'] = "Please enter group name.";
        echo json_encode($response);
        exit;
    }

    $query = "SELECT * 
                    FROM  " . $table . "
                    WHERE group_name = '" . $objPost->group_name . "' ";

    if ($id > 0) {
        $query .= " AND id != '" . $id . "' ";
    }

    $checkIfExists = $db->pdoQuery($query)->result();

    if ($checkIfExists) {
        $response['error'] = "Entered group name already exists.";
        echo json_encode($response);
        exit;
    }

    if (isset($_FILES['group_logo']) && !($_FILES['group_logo']['error'])) {
        $file_array = $_FILES["group_logo"];
        $upload_dir = DIR_UPD_GROUP_LOGOS;
        $image_resize_array = unserialize(GROUP_LOGO_RESIZE_ARRAY);


        $file_name = filtering($file_array['name'], 'input');
        $tmp_name = $file_array['tmp_name'];
        $image_type = $file_array['type'];

        $allowedExts = array("jpg", "jpeg", "JPG", "JPEG", "png", "PNG");
        $name_ext = explode(".", $file_array["name"]);
        $extension = end($name_ext);

        require_once(DIR_ADM_MOD . 'storage.php');
        $edit_logo_storage1 = new storage();

        if (( ($image_type == "image/jpeg" || $image_type == "image/png" || $image_type == "image/x-png" || $image_type == "image/jpg" || $image_type == "image/x-png" || $image_type == "image/x-jpeg" || $image_type == "image/pjpeg") ) && ($file_array["size"] < 2097152) && in_array($extension, $allowedExts)) {
            
            //upload code for bucket
            $img_name = date('YmdHis') . '.original' . '.png';
            $temp_src = "group-logos-nct/".$img_name;
            $temp_src2 = "group-logos-nct/";
            $get_main_img = '';
            
            $main_img = $edit_logo_storage1->upload_object('av8db','',$tmp_name,$temp_src);
            $get_main_img = $edit_logo_storage1->getImageUrl1('av8db',$img_name,$temp_src2);
            $length = count($image_resize_array);

            if (!extension_loaded('imagick')) {
                echo "imagick not installed...";
            }else{
                $im1 = new Imagick($get_main_img);
                for ($i = 0; $i < $length; $i++) {
                    $im1->readImage($get_main_img);
                    $im1->resizeImage($image_resize_array[$i]['width'], $image_resize_array[$i]['height'], Imagick::FILTER_LANCZOS, 1);
                    $resize_img = $edit_logo_storage1->upload_objectBlob('av8db','th'.($i+1).'_'.$img_name,$im1->getImageBlob(),$temp_src2);
                    $im1->clear();
                    $im1->destroy();
                }
            }
            // $group_logo = uploadImage($file_array, $upload_dir, $image_resize_array);

            if ($get_main_img != '') {
                // $objPost->group_logo = $group_logo['image_name'];
                $objPost->group_logo = $img_name;
            } else {
                $response['error'] = "There seems to be an issue while uploading the logo of your company.";
                echo json_encode($response);
                exit;
            }
        } else {
            $response['error'] = "Please select either a jpg, jpeg or png file for company logo.";
            echo json_encode($response);
            exit;
        }
    }


    if ($objPost->group_description == "") {
        $response['error'] = "Please enter group description.";
        echo json_encode($response);
        exit;
    }

    if ($objPost->group_type_id == "") {
        $response['error'] = "Please select group type.";
        echo json_encode($response);
        exit;
    }

    if ($objPost->privacy == "") {
        $response['error'] = "Please select the privacy of group.";
        echo json_encode($response);
        exit;
    }

    if ($objPost->privacy == 'pu') {
        if ($objPost->accessibility == "") {
            $response['error'] = "Please select the accessibility of your public group.";
            echo json_encode($response);
            exit;
        }
        
        if ($objPost->accessibility != "a" && $objPost->accessibility != "rj") {
            $response['error'] = "There seems to be some issue with accessibility of the group.";
            echo json_encode($response);
            exit;
        }
        
    } else {
        $objPost->accessibility = 'awa';
    }


    if (in_array('edit', $Permission)) {

        $objPostArray = (array) $objPost;

        $db->update($table, $objPostArray, array("id" => $id));

        $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
        add_admin_activity($activity_array);

        $response['status'] = true;
        $response['success'] = "Group details has been updated successfully.";
        echo json_encode($response);
        exit;
    } else {
        $response['error'] = "You don't have permission.";
        echo json_encode($response);
        exit;
    }
}

$objGroups = new Groups($module, $id, NULL);
$pageContent = $objGroups->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
