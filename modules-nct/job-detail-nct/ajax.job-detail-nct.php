<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];
$_SESSION['user_id'] = $_POST['sess_user_id'];
if($_REQUEST['action']=='getJobs_applicant'){
    $_REQUEST['action']='getJobApplicants';
    $_REQUEST['currentPage']=$requestURI[4];
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.job-detail-nct.php");
$module = 'job-detail-nct'; 
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'saveJobApplication') {
	//_print($_REQUEST);exit;
	$job_id = decryptIt($_REQUEST['job_id']);

	$objJobDetail = new Job_detail();
	$response = $objJobDetail->saveJobApplication($job_id);
	$response['no_of_applicants'] = $objJobDetail->getNoOFApplicants($job_id);
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
	echo json_encode($response); exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'removeJobApplication') {
	//_print($_REQUEST);exit;
	$job_id = decryptIt($_REQUEST['job_id']);

	$objJobDetail = new Job_detail();
	$response = $objJobDetail->removeJobApplication($job_id);
	$response['no_of_applicants'] = $objJobDetail->getNoOFApplicants($job_id);
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
	echo json_encode($response);
	exit;
}  else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'saveJob') {
	//_print($_REQUEST);exit;
	$job_id = decryptIt($_REQUEST['job_id']);

	$objJobDetail = new Job_detail();
	$response = $objJobDetail->saveJob($job_id);
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
	echo json_encode($response);
	exit;
}
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'removeSavedJob') {
	//_print($_REQUEST);exit;
	$job_id = decryptIt($_REQUEST['job_id']);

	$objJobDetail = new Job_detail();
	$response = $objJobDetail->removeSavedJob($job_id);
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
	echo json_encode($response);exit;
} else if(isset($_REQUEST['action']) && 'getSimilarJobs' == $_REQUEST['action']) {
    $response = array();
    $response['status'] = false;
    
    $page = filtering($_REQUEST['page'], 'input', 'int');
    
    $objJobDetail = new Job_detail();
    $result = $objJobDetail->getSimilarJobs(($_REQUEST['job_id']), ($_REQUEST['industry_id']), $limit_flag = false, $page);
    
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['pagination'] = $result['pagination'];
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
	echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && 'getJobApplicants' == $_REQUEST['action']) {
    $response = array();
    $response['status'] = false;
    
    $page = filtering($_REQUEST['currentPage'], 'input', 'int');
    
    $objJobDetail = new Job_detail();

    $result = $objJobDetail->getJobApplicants(($_REQUEST['job_id']), $page);
    
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['pagination'] = $result['pagination'];

	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'shareNewsFeed') {
	//_print($_REQUEST);exit;
	$job_id = $_REQUEST['job_id'];

	$objJobDetail = new Job_detail();
	$response = $objJobDetail->shareNewsFeed($job_id);

	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

	echo json_encode($response);exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'saveDirectJobApplication'){
    //print_r($_FILES);
// 	_print($_REQUEST);
	$job_id = decryptIt($_REQUEST['job_id']);

	$objJobDetail = new Job_detail();
	$response = $objJobDetail->saveDirectJobApplication($job_id,$_FILES);
	$response['no_of_applicants'] = $objJobDetail->getNoOFApplicants($job_id);
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    //print_r($response);exit;   
	echo json_encode($response);
	exit;
}