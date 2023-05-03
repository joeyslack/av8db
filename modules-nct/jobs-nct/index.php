<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['type'] = $requestURI[2];

if($_REQUEST['type']=='my-jobs'){
    $_REQUEST['type']='my_jobs';
}else if($_REQUEST['type']=='applied-jobs'){
    $_REQUEST['type']='applied_jobs';
}else if($_REQUEST['type']=='saved-jobs'){
    $_REQUEST['type']='saved_jobs';
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.jobs-nct.php");
$module = 'jobs-nct';

if(isset($_REQUEST['type']) && $_REQUEST['type'] != '') {
    $type = filtering($_REQUEST['type']);
    
    if('my_jobs' == $type || 'applied_jobs' == $type || 'saved_jobs' == $type) {
        if('my_jobs' == $type) {
            $winTitle = " {LBL_SUB_HEADER_MY_JOBS} - " . SITE_NM;
        } else if('saved_jobs' == $type) {
            $winTitle = "{LBL_SUB_HEADER_SAVED_JOBS} - " . SITE_NM;
        } else {
            $winTitle = "{LBL_APPLIED_JOBS} - " . SITE_NM;
        }
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => LBL_VALID_TYPE));
        redirectPage(SITE_URL . "dashboard");
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_SOME_ISSUE_TRY_LATER ));
    redirectPage(SITE_URL . "dashboard");
}


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

$objJobs = new Jobs();
$pageContent = $objJobs->getJobsPageContent($type);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
