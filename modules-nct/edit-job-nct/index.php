<?php


$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['job_id'] = $requestURI[2];

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.edit-job-nct.php");
$module = 'edit-job-nct';
$include_google_maps_js = true;
$init_autocomplete = false;


if(isset($_REQUEST['job_id']) && $_REQUEST['job_id'] != '') {
    $job_id = filtering(decryptIt($_REQUEST['job_id']), 'input', 'int');

    $myJob = $db->select("tbl_jobs", "id", array("id" => $job_id,'user_id'=>$_SESSION['user_id']))->result();
    if($myJob == 0){
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_JOB_NOT_POSTED_BY_YOU}"));
        redirectPage(SITE_URL . "dashboard");
    }
    
    $checkIfExists = $db->select("tbl_jobs", "*", array("id" => $job_id))->result();
    if(!$checkIfExists) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_JOB_YOU_R_TRYING_DOESNT_EXIST}"));
        redirectPage(SITE_URL . "dashboard");
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_JOB_ID}"));
    redirectPage(SITE_URL . "dashboard");
}


$winTitle = "{LBL_EDIT_JOB} - " . SITE_NM;

$styles = array(
    array("bootstrap3-editable-1.5.1/bootstrap3-editable/css/bootstrap-editable.css", SITE_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/css/select2.min.css", SITE_PLUGIN)
);

$scripts = array(
    array("bootstrap3-editable-1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js", SITE_PLUGIN),
    array("select2-4.0.3/select2-4.0.3/dist/js/select2.full.min.js", SITE_PLUGIN),
    array("ckeditor_4.5.10_standard/ckeditor/ckeditor.js", SITE_PLUGIN)
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

$objEditJob = new Edit_job($job_id);
$pageContent = $objEditJob->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
