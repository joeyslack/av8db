<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);
$_REQUEST['company_id'] = $requestURI[2];


$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.edit-company-nct.php");
$module = 'edit-company-nct';
$include_google_maps_js = true;
$init_autocomplete = true;

if(isset($_REQUEST['company_id']) && $_REQUEST['company_id'] != '') {
    $company_id = filtering(decryptIt($_REQUEST['company_id']), 'input', 'int');
    
    $myJob = $db->select("tbl_companies", "id", array("id" => $company_id,'user_id'=>$_SESSION['user_id']))->result();
    if($myJob == 0){
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_COMPANY_TRYING_EDIT_NOT_POSTED_BY_YOU}"));
        redirectPage(SITE_URL . "dashboard");
    }

    $checkIfExists = $db->select("tbl_companies", "*", array("id" => $company_id))->result();
    if(!$checkIfExists) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_COMPANY_DOESNT_EXIST}"));
        redirectPage(SITE_URL . "dashboard");
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_PROVIDE_COMPANYID}."));
    redirectPage(SITE_URL . "dashboard");
}


$winTitle = "{LBL_EDIT_COMPANY} - " . SITE_NM;

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

$objEditCompany = new Edit_company($company_id);
$pageContent = $objEditCompany->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
