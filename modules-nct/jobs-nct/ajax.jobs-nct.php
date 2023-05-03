<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($requestURI[2]=='getJobs_load'){
    $_REQUEST['action']='getJobs';
    $_REQUEST['currentPage']=$requestURI[4];
    $_REQUEST['type']=$requestURI[5];
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.jobs-nct.php");
$module = 'jobs-nct';

if(isset($_REQUEST['action']) && 'getJobs' == $_REQUEST['action']) {
    $response = array();
    $response['status'] = false;
    
    $page = isset($_REQUEST['page']) ? (int)filtering($_REQUEST['page']) : filtering($_REQUEST['currentPage'], 'input', 'int');
    $type = filtering($_REQUEST['type'], 'input');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objJobs = new Jobs();
    $result = $objJobs->getJobs($user_id, $type, $page);
    
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['pagination'] = $result['pagination'];
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'removeJobs' == $_POST['action']) {
    $response = array();
    $response['status'] = false;
    
    $job_id = decryptIt(filtering($_POST['job_id'], 'input', 'int'));
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objJobs = new Jobs();
    $result = $objJobs->removeJobs($user_id, $job_id);
    
    $response['id'] = $job_id;
    $response['status'] = $result['status'];
    $response['msg'] = $result['msg'];
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'withdrawAppliedJobs' == $_POST['action']) {
    $response = array();
    $response['status'] = false;
    
    $job_id = decryptIt(filtering($_POST['job_id'], 'input', 'int'));
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objJobs = new Jobs();
    $result = $objJobs->withdrawJobs($user_id, $job_id);
    
    $response['id'] = $job_id;
    $response['status'] = $result['status'];
    $response['msg'] = $result['msg'];

    echo json_encode($response);
    
    exit;
} else if(isset($_POST['action']) && 'deleteJob' == $_POST['action']) {
    $response = array();
    $response['status'] = false;
    
    $job_id = decryptIt(filtering($_POST['job_id'], 'input', 'int'));
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objJobs = new Jobs();
    $result = $objJobs->deleteJob($user_id, $job_id);
    
    $response['id'] = $job_id;
    $response['status'] = $result['status'];
    $response['msg'] = $result['msg'];
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    
    echo json_encode($response);
    exit;
}
