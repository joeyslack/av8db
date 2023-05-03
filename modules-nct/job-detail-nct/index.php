<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];
$_REQUEST['job_id'] = $requestURI[2];

if($_REQUEST['action']=='similar-jobs'){
    $_REQUEST['job_id'] = $requestURI[3];
    $_REQUEST['industry_id'] = $requestURI[5];
    $_REQUEST['action'] = 'getSimilarJob';
}else if($_REQUEST['action']=='job-applicants'){
    $_REQUEST['job_id'] = $requestURI[3];
    $_REQUEST['action'] = 'getJobApplicants';
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.job-detail-nct.php");
$module = 'job-detail-nct';

if(isset($_REQUEST['job_id']) && $_REQUEST['job_id'] != '') {
    $job_id = filtering($_REQUEST['job_id'], 'input', 'int');
   // print_r($job_id);exit();
    $checkIfExists = $db->select("tbl_jobs", "*", array("id" => $job_id))->result();
    
    if(!$checkIfExists) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_JOB_YOU_R_TRYING_DOESNT_EXIST}"));
        redirectPage(SITE_URL . "dashboard");
    } else {
        if($checkIfExists['status'] == 'd' && $checkIfExists['user_id'] != $_SESSION['user_id']) {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' =>" {ERROR_JOB_TRYING_NOT_ACTIVE}"));
            redirectPage(SITE_URL . "dashboard");
        }
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_JOB_ID));
    redirectPage(SITE_URL . "dashboard");
}

$winTitle = "{LBL_JOB_DETAIL} - " . SITE_NM;

$include_sharing_js = true;


$styles = '';
$scripts = '';

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

$objJobs = new Job_detail($job_id);
$pageContent = $objJobs->getJobsPageContent();

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSimilarJob') {

    $winTitle = "{LBL_SIMILAR_JOBS} - " . SITE_NM;

    if(isset($_GET['page']) && $_GET['page'] != "" && $_GET['page'] > 1) {
        $page = filtering($_GET['page'], 'input', 'int');
    } else {
        $page = 1;
    }

    $response = $objJobs->getSimilarJobs(decryptIt($_REQUEST['job_id']), decryptIt($_REQUEST['industry_id']), false, $page);
    $pageContent = $objJobs->getSimilarJobsPageContent($response);
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getJobApplicants') {

    $winTitle = "{LBL_JOB_APPLICANTS} - " . SITE_NM;

    if(isset($_GET['page']) && $_GET['page'] != "" && $_GET['page'] > 1) {
        $page = filtering($_GET['page'], 'input', 'int');
    } else {
        $page = 1;
    }

    $response = $objJobs->getJobApplicants($_REQUEST['job_id'], $page);
    $pageContent = $objJobs->getJobApplicantsPageContent($response);
} 

require_once(DIR_TMPL . "parsing-nct.tpl.php");
