<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];
$company_id='';
if(isset($requestURI[2]) && $requestURI[2]!=""){
    $company_id = $requestURI[2];
}

if($_REQUEST['action']=='company'){
    $_REQUEST['action']='company';
    $_REQUEST['company_id']=$company_id;
}else if($_REQUEST['action']=='rate_review'){
    
}


$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.company-detail-nct.php");
$module = 'company-detail-nct';
if(isset($_REQUEST['company_id']) && $_REQUEST['company_id'] != '') {
    $company_id = filtering($_REQUEST['company_id'], 'input', 'int');
    
    $checkIfExists = $db->select("tbl_companies", "*", array("id" => $company_id))->result();
    
    if(!$checkIfExists) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_COMPANY_DOESNT_EXIST}"));
        redirectPage(SITE_URL . "dashboard");
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_PROVIDE_COMPANYID}"));
    redirectPage(SITE_URL . "dashboard");
}

if(isset($_POST['save_rate_review'])){
    extract($_POST);
    $objCompanyDetail = new Company_detail($company_id,$_SESSION['user_id']);

    $sender_id  = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $rating     = isset($rate) ? $rate : '0';
    $desc       = filtering($description);
    $company_id = isset($company_id) ? $company_id : '0';
    if ($rating > 0 && $desc != '') {
        $response = $objCompanyDetail->storeRateReview($sender_id,$rating,$desc,$company_id);    
    }else{
        $response['status'] = false;
        $response['redirect_url'] = SITE_URL ."company/".$company_id;
        $response['err'] = ERR_COMPANY_RATING_NOT_SELECTED_RATING;
    }
    echo json_encode($response);
    exit;
}   
if(isset($_POST['edit_rate_review'])){
     
    extract($_POST);
    
    $objCompanyDetail = new Company_detail($company_id,$_SESSION['user_id']);

    $sender_id  = isset($user_id) ? $user_id : '';
    $rating     = isset($score) ? $score : '0';
    $desc       = filtering($description);
    $company_id = isset($company_id) ? $company_id : '0';
    if ($desc != '') {
        $response = $objCompanyDetail->UpdateRateReview($sender_id,$rating,$desc,$company_id);    
    }else{
        $response['status'] = "err";
        $response['redirect_url'] = SITE_URL ."company/".$company_id;
        $response['message'] = ERROR_PLEASE_ENTER_DESCRIPTION;
    }
    
    echo json_encode($response);
    exit;
}
$winTitle = "{LBL_COMPANY_DETAIL} - " . SITE_NM;

$include_sharing_js = false;

$styles = array();
$scripts = array();

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

$objCompany = new Company_detail($company_id,$_SESSION['user_id']);
$pageContent = $objCompany->getCompanyPageContent(1);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
