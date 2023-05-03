<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.companies-nct.php");
$module = "companies-nct";
$table = "tbl_companies";

$include_google_maps_js = true;

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

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Business';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


if (isset($_POST["company_name"]) && isset($_POST["company_description"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "<pre>";print_r($_POST);exit;
    $response = array();
    $response['status'] = false;

    extract($_POST);
    $objPost->company_name = filtering($_POST['company_name'], 'input');
    $objPost->company_description = filtering($_POST['company_description'], 'input');

    $objPost->company_industry_id = filtering($_POST['company_industry_id'], 'input', 'int');
   
    $objPost->website_of_company = filtering($_POST['website_of_company'], 'input', 'int');

    $objPost->foundation_year = filtering($_POST['foundation_year'], 'input', 'int');
    $objPost->owner_email_address = filtering($_POST['owner_email_address'], 'input');

    $objPost->status = isset($status) && $status == 'a' ? 'a' : 'd';

    if ($objPost->company_name == "") {
        $response['error'] = "Please enter business name.";
        echo json_encode($response);
        exit;
    }

    $query = "SELECT * FROM  " . $table . " WHERE company_name = '" . $objPost->company_name . "' ";

    if ($id > 0) {
        $query .= " AND id != '" . $id . "' ";
    }

    $checkIfExists = $db->pdoQuery($query)->result();

    if ($checkIfExists) {
        $response['error'] = "Entered business name already exists.";
        echo json_encode($response);
        exit;
    }
    // Company Location data
    $cl_id = ( ( isset($_POST['cl_id']) ) ? $_POST['cl_id'] : array() );
    $formatted_address = ( ( isset($_POST['formatted_address']) ) ? $_POST['formatted_address'] : array() );
    $address1 = ( ( isset($_POST['address1']) ) ? $_POST['address1'] : array() );
    $address2 = ( ( isset($_POST['address2']) ) ? $_POST['address2'] : array() );
    $country = ( ( isset($_POST['country']) ) ? $_POST['country'] : array() );
    $state = ( ( isset($_POST['state']) ) ? $_POST['state'] : array() );
    $city1 = ( ( isset($_POST['city1']) ) ? $_POST['city1'] : array() );
    $city2 = ( ( isset($_POST['city2']) ) ? $_POST['city2'] : array() );
    $postal_code = ( ( isset($_POST['postal_code']) ) ? $_POST['postal_code'] : array() );
    $latitude = ( ( isset($_POST['latitude']) ) ? $_POST['latitude'] : array() );
    $longitude = ( ( isset($_POST['longitude']) ) ? $_POST['longitude'] : array() );
    $is_hq = ( ( isset($_POST['is_hq']) ) ? $_POST['is_hq'] : array() );

    if (!empty($latitude) && !empty($latitude)) {
        if (count($formatted_address) == count($latitude) && count($latitude) == count($longitude)) {
            $cl_ids_array = array();
            
            $no_of_locations_to_be_inserted = ( ( count($latitude) <= 5 ) ? count($latitude) : 5);
            
            for ($i = 0; $i < $no_of_locations_to_be_inserted; $i++) {
                $cl_id = filtering(decryptIt($_POST['cl_id'][$i]), 'input', 'int');

                $job_location_details_array = array(
                    "formatted_address" => filtering($_POST['formatted_address'][$i]),
                    "address1" => filtering($_POST['address1'][$i]),
                    "address2" => filtering($_POST['address2'][$i]),
                    "country" => filtering($_POST['country'][$i]),
                    "state" => filtering($_POST['state'][$i]),
                    "city1" => filtering($_POST['city1'][$i]),
                    "city2" => filtering($_POST['city2'][$i]),
                    "postal_code" => filtering($_POST['postal_code'][$i]),
                    "latitude" => filtering($_POST['latitude'][$i]),
                    "longitude" => filtering($_POST['latitude'][$i]),
                    "date_updated" => date("Y-m-d H:i:s")
                );

                $company_location_array = array(
                    "company_id" => $id,
                    "is_hq" => filtering($_POST['is_hq'][$i]),
                    "updated_on" => date("Y-m-d H:i:s")
                );

                if ($cl_id > 0) {
                    $db->update("tbl_company_locations", $company_location_array, array("id" => $cl_id))->affectedRows();
                    $cl_ids_array[] = $cl_id;
                } else {
                    $job_location_details_array['date_added'] = date("Y-m-d H:i:s");
                    
                    $location_id = $db->insert("tbl_locations", $job_location_details_array)->getLastInsertId();

                    $company_location_array['location_id'] = $location_id;
                    $company_location_array['updated_on'] = date("Y-m-d H:i:s");
                    
                    $cl_id = $db->insert("tbl_company_locations", $company_location_array)->getLastInsertId();
                    $cl_ids_array[] = $cl_id;
                }
            }
            
            if (!empty($cl_ids_array)) {
                
                $cl_ids_array_imploded = implode(",", $cl_ids_array);

                $query = "SELECT * FROM tbl_company_locations WHERE company_id = '" . $id . "' AND id NOT IN ( ".$cl_ids_array_imploded." ) ";
                $clinic_locations = $db->pdoQuery($query)->results();
                if ($clinic_locations) {
                    for ($i = 0; $i < count($clinic_locations); $i++) {
                        $id = $clinic_locations[$i]['id'];
                        $location_id = $clinic_locations[$i]['location_id'];
                        
                        $db->delete("tbl_locations", array("id" => $location_id ));
                        $db->delete("tbl_company_locations", array("id" => $id ));
                    }
                }
            }
        } else {
            $response['error'] = "Opps..! Something went wrong while saving your business locations.";
            return $response;
        }
    }
    
    if (isset($_FILES['company_logo']) && !($_FILES['company_logo']['error'])) {
        $file_array = $_FILES["company_logo"];
        $upload_dir = DIR_UPD_COMPANY_LOGOS;

        $image_resize_array = unserialize(COMPANY_LOGO_RESIZE_ARRAY);
        
        $file_name = filtering($file_array['name'], 'input');
        $tmp_name = $file_array['tmp_name'];
        $image_type = $file_array['type'];
        
        $allowedExts = array("jpg", "jpeg", "JPG", "JPEG", "png", "PNG");
        $name_ext = explode(".", $file_array["name"]);
        $extension = end($name_ext);
        
        require_once(DIR_ADM_MOD . 'storage.php');
        $edit_comp_logo_storage1 = new storage();

        if (( ($image_type == "image/jpeg" || $image_type == "image/png" || $image_type == "image/x-png" || $image_type == "image/jpg" || $image_type == "image/x-png" || $image_type == "image/x-jpeg" || $image_type == "image/pjpeg") ) && ($file_array["size"] < 2097152) && in_array($extension, $allowedExts)) {
                
            //upload code for bucket
            $img_name = date('YmdHis') . '.original' . '.png';
            $temp_src = DIR_NAME_COMPANY_LOGOS."/".$img_name;
            $temp_src2 = DIR_NAME_COMPANY_LOGOS."/";
            $get_main_img = '';
            
            $main_img = $edit_comp_logo_storage1->upload_object('av8db','',$tmp_name,$temp_src);
            $get_main_img = $edit_comp_logo_storage1->getImageUrl1('av8db',$img_name,$temp_src2);
            $length = count($image_resize_array);

            if (!extension_loaded('imagick')) {
                echo "imagick not installed...";
            }else{
                $im1 = new Imagick($get_main_img);
                for ($i = 0; $i < $length; $i++) {
                    $im1->readImage($get_main_img);
                    $im1->resizeImage($image_resize_array[$i]['width'], $image_resize_array[$i]['height'], Imagick::FILTER_LANCZOS, 1);
                    $resize_img = $edit_comp_logo_storage1->upload_objectBlob('av8db','th'.($i+1).'_'.$img_name,$im1->getImageBlob(),$temp_src2);
                    $im1->clear();
                    $im1->destroy();
                }
            }

            // $company_logo = uploadImage($file_array, $upload_dir, $image_resize_array);
            if ($get_main_img != '') {
                $objPost->company_logo = $img_name;
            } else {
                $response['error'] = "There seems to be an issue while uploading the logo of your business.";
                echo json_encode($response);
                exit;
            }
            
        } else {
            $response['error'] = "Please select either a jpg, jpeg or png file for business logo.";
            echo json_encode($response);
            exit;
        }
    }
    
    if ($objPost->company_description == "") {
        $response['error'] = "Please enter business description.";
        echo json_encode($response);
        exit;
    }

    if ($objPost->company_industry_id == "") {
        $response['error'] = "Please select the industry of business.";
        echo json_encode($response);
        exit;
    }
    if ($objPost->website_of_company == "") {
        $response['error'] = "Please enter the website of business.";
        echo json_encode($response);
        exit;
    }

    if (in_array('edit', $Permission)) {

        $objPostArray = (array) $objPost;
        
        $db->update($table, $objPostArray, array("id" => $id));

        $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
        add_admin_activity($activity_array);

        $response['status'] = true;
        $response['success'] = "Business details has been updated successfully.";
        echo json_encode($response);
        exit;
    } else {
        $response['error'] = "You don't have permission.";
        echo json_encode($response);
        exit;
    }
}
$objCompanies = new Companies($module, $id, NULL);
$pageContent = $objCompanies->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");