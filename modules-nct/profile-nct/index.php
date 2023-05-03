<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='profile'){
    $_GET['user_id']=$requestURI[2];
}

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.profile-nct.php");
$module = 'profile-nct';

$include_google_maps_js = true;

if(isset($_GET['user_id']) && $_GET['user_id'] != '') {

    $user_id = filtering($_GET['user_id'], 'input', 'int');
    
    $checkifExists = getTotalRows("tbl_users", "id = '".$user_id."' ", "id");
    if($checkifExists) {
        
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_PROFILE_DOESNT_EXIST}"));
        redirectPage(SITE_URL);
    }   
}else{
    if($_SESSION['user_id'] > 0){

    }else{
        redirectPage(SITE_URL);
    }
}

if(isset($_POST['save_ferry_pilot_rating'])){
    extract($_POST);
    $objProfile = new Profile();
    $rating     = isset($rate) ? $rate : '0';
    $desc       = isset($description) ? $description : '';
    
    if($rating != '' && $desc != ''){
        $response = $objProfile->storeFerryPilotRateReview($rating,$desc);
    }else{
        $response['status'] = false;
        $response['redirect_url'] = SITE_URL ."profile/";
        $response['err'] = ERROR_COMPANY_RATE_REVIEW_MESSAGE;
    }    
    echo json_encode($response);
    exit;
}
if(isset($_POST['edit_rate_review'])){
     
    extract($_POST);
    
    $sender_id  = isset($sender_id) ? $sender_id : '';
    $rating     = isset($score) ? $score : '0';
    $desc       = isset($description) ? $description : '';
    $receiver_id= isset($receiver_id) ? $receiver_id : '0';
    $rate_id= isset($rate_id) ? $rate_id : '0';
    
    $objProfile = new Profile($rate_id,$review_id);
    
    $response = $objProfile->UpdateRateReview($sender_id,$rating,$desc,$receiver_id,$rate_id);
    echo json_encode($response);
    exit;
   
}

if(isset($_POST['send_invitation_off_platform'])){
    extract($_POST);       
    $objProfile = new Profile();

    $current_user_id= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $user_email = isset($user_email) ? $user_email : '';

    $response = $objProfile->sendInvitationOffPlatform($current_user_id,$user_email);
    echo json_encode($response);
    exit;
  
}

$winTitle = '{LBL_SUB_HEADER_PROFILE} - ' . SITE_NM;

$styles = array(
    array("image_crop_css/cropper.min.css", SITE_PLUGIN),
    array("image_crop_css/main.css", SITE_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/css/select2.min.css", SITE_PLUGIN),
);
$scripts = array(
    array("image_crop/uploadimage.js", SITE_PLUGIN),
    array("image_crop/main.js", SITE_PLUGIN),
    array("image_crop/cropper.js", SITE_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/js/select2.full.min.js", SITE_PLUGIN),
);

$metas = get_meta_keyword_description(1);
if ($metas) {
    $final_description = filtering($metas['meta_description']);
    $final_keywords = filtering($metas['meta_keyword']);
} else {
    $final_description = filtering($description);
    $final_keywords = filtering($keywords);
}

$metaTag = getMetaTagsAll(array('description' => $final_description,
    'keywords' => $final_keywords,
    'og_title' => $winTitle
));

$objProfile = new Profile();
$pageContent = $objProfile->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
