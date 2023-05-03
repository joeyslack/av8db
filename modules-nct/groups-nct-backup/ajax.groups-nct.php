<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);
echo "ajax";echo "<pre>";print_r($requestURI);
echo "ajax";echo "<pre> post ";print_r($_POST);
if ($_POST['sess_user_id'] > 0) {
    $_SESSION['user_id'] = $_POST['sess_user_id'];
}
echo "<pre>after session ";print_r($_SESSION);
$_REQUEST['action'] = $requestURI[2];
$_REQUEST['action1'] = $requestURI[1];

if($_REQUEST['action']=='getGroups_load'){
    $_REQUEST['action']='getGroups';
    $_REQUEST['currentPage']=$requestURI[4];
    $_REQUEST['type']=$requestURI[5];
}

if($_REQUEST['action1']=='getGroups'){
    $_REQUEST['action']='getGroups';
    $_REQUEST['currentPage']=$requestURI[4];
    $_REQUEST['type']=$requestURI[5];
}

$reqAuth = true;
echo "<pre>before conf ";
require_once(DIR_URL."includes-nct/config-nct.php");
echo "<pre>after conf ";
require_once("class.groups-nct.php");
echo "<pre>after class ";
$module = 'groups-nct';

if(isset($_REQUEST['action']) && 'getGroups' == $_REQUEST['action']) {
    echo "in if condition";
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

    //echo 1;exit;

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