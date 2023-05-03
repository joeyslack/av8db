<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

if ($_POST['sess_user_id'] > 0) {
    $_SESSION['user_id'] = $_POST['sess_user_id'];
}

$_REQUEST['action'] = $requestURI[2];
$_REQUEST['action1'] = $requestURI[1];

if($_REQUEST['action']=='getGroups_load'){
    $_REQUEST['action']='getGroups';
    $_REQUEST['currentPage']=$_REQUEST['page'];
    $_REQUEST['type']=$_REQUEST['type'];
}

if($_REQUEST['action1']=='getGroups'){
    $_REQUEST['action']='getGroups';
    $_REQUEST['currentPage']=$_REQUEST['page'];
    $_REQUEST['type']=$_REQUEST['type'];
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.groups-nct.php");
$module = 'groups-nct';

if(isset($_REQUEST['action']) && 'getGroups' == $_REQUEST['action']) {
    $response = array();
    $response['status'] = false;
    $page = isset($_REQUEST['page']) ? (int)filtering($_REQUEST['page']) : filtering($_REQUEST['currentPage'], 'input', 'int');
    $type = filtering($_REQUEST['type'], 'input');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objGroups = new Groups();
    
    $result = $objGroups->getGroups($user_id, $type, $page);
    
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['pagination'] = $result['pagination'];

     $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'removeJoinedGroup' == $_POST['action']) {
    $response = array();
    $response['status'] = false;
    
    $group_id = filtering($_POST['group_id'], 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objGroups = new Groups();
    $result = $objGroups->removeJoinedGroup($user_id, $group_id);
    
    $response['id'] = $group_id;
    $response['status'] = $result['status'];
    $response['msg'] = $result['msg'];

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
}