<?php

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.referrals-nct.php");
$module = 'referrals-nct';

$include_google_maps_js = true;

/*if(isset($_GET['user_id']) && $_GET['user_id'] != '') {

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
}*/

$winTitle = '{LBL_SUB_HEADER_REFERRALS} - ' . SITE_NM;

$styles = array(
    array("image_crop_css/cropper.min.css", SITE_PLUGIN),
    array("image_crop_css/main.css", SITE_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/css/select2.min.css", SITE_PLUGIN)    
);
$scripts = array(
    array("image_crop/uploadimage.js", SITE_PLUGIN),
    array("image_crop/main.js", SITE_PLUGIN),
    array("image_crop/cropper.js", SITE_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/js/select2.full.min.js", SITE_PLUGIN)
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

if(isset($_POST['save_referral_review'])){
    extract($_POST);
    $objReferral = new Referrals();
    
    $referral_id   = isset($review_id) ? $review_id : '0';
    $sender_id     = isset($sender_id) ? $sender_id : '0';
    $receiver_id   = isset($receiver_id) ? $receiver_id : '0';
    $review_desc   = isset($referral_description) ? $referral_description : '';
    
    if($referral_id > 0 && $sender_id > 0 && $receiver_id > 0){
        $response = $objReferral->storeReferralReviews($referral_id,$sender_id,$receiver_id,$review_desc);
    }else{
        $response['status'] = "error";
        $response['redirect_url'] = SITE_URL ."referral/";
        $response['err'] = ERROR_COMPANY_RATE_REVIEW_MESSAGE;
    }
    echo json_encode($response);
    exit;
}

$objProfile = new Referrals();
$pageContent = $objProfile->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");