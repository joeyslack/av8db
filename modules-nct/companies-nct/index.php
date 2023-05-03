<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);
$_REQUEST['type'] = $requestURI[2];

$request_id=0;
if(isset($requestURI[3]) && $requestURI[3]!=""){
    $_REQUEST['com_id'] = $requestURI[3];
}

if($_REQUEST['type']=='my-companies'){
    $_REQUEST['type']='my_companies';
}else if($_REQUEST['type']=='following-companies'){
    $_REQUEST['type']='following_companies';
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.companies-nct.php");
$module = 'companies-nct';

$styles = array(
    array("jasny/dist/css/jasny-bootstrap.min.css", SITE_PLUGIN),
    array("bootstrap-datepicker/css/datepicker.css", SITE_ADM_PLUGIN),
    array("image_crop_css/cropper.min.css", SITE_PLUGIN),
    array("image_crop_css/main.css", SITE_PLUGIN),
);

$scripts = array(
    array("jasny/dist/js/jasny-bootstrap.min.js", SITE_PLUGIN),
    array("jasny/js/fileinput.js", SITE_PLUGIN),
    array("javascript-nct/bootstrap-datepicker.min.js", SITE_INC),
    array("image_crop/uploadimage.js", SITE_PLUGIN),
    array("image_crop/main.js", SITE_PLUGIN),
    array("image_crop/cropper.js", SITE_PLUGIN),
    //array("bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js", SITE_ADM_PLUGIN),
);

if(isset($_REQUEST['com_id']) && $_REQUEST['com_id'] != ''){
    $com_id = filtering(decryptIt($_REQUEST['com_id']), 'input', 'int');

    $total_rows = $db->count('tbl_companies',array('id'=>$com_id));
    if($total_rows > 0){
        $com_details = $db->select("tbl_companies", "id", array("id" => $com_id,'user_id'=>$_SESSION['user_id']))->result();
        
        if($com_details == ''){
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_ERROR_COMPANY_EMAIL_VERIFICATION}"));
            redirectPage(SITE_URL . "company/my-companies");
        }else{
            $objPost = new stdClass();
            $objPost->isCompanyEmailVerify = 'y';
            
            $valArray = array('isCompanyEmailVerify'=>$objPost->isCompanyEmailVerify);
            $db->update('tbl_companies',$valArray,array('id'=>$com_id));
            
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => "{LBL_SUCESS_COMPANY_EMAIL_VERIFICATION}"));
            
            redirectPage(SITE_URL . "company/my-companies");
        }
    }
}

if(isset($_REQUEST['type']) && $_REQUEST['type'] != '') {
    $type = filtering($_REQUEST['type']);
    if('my_companies' == $type || 'following_companies' == $type) {
        if('my_companies' == $type) {
            $winTitle = "{LBL_SUB_HEADER_MY_COMPANIES} - " . SITE_NM;
        } else {
            $winTitle = "{LBL_FOLLOWING_COMPANIES} - " . SITE_NM;
        }
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_VALID_TYPE}"));
        redirectPage(SITE_URL . "dashboard");
    }
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME}"));
    redirectPage(SITE_URL . "dashboard");
}
if(isset($_POST['create_company'])) {
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objCreateCompany = new Create_company();
    $response = $objCreateCompany->processCompnayCreation($user_id);
    echo json_encode($response);
    exit;
}


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
$objCompanies = new Companies();
$pageContent = $objCompanies->getCompaniesPageContent($type);
require_once(DIR_TMPL . "parsing-nct.tpl.php");