<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[2];

if($_REQUEST['action']=='recent-updates'){
    $_GET['action'] = 'recent_updates';
    if(isset($requestURI[3]) && $requestURI[3]=="currentPage"){
        $_GET['currentPage'] = $requestURI[4];
    }
}else if($_REQUEST['action']=='published-posts'){
    $_GET['action'] = 'published_posts';
    if(isset($requestURI[3]) && $requestURI[3]=="currentPage"){
        $_GET['currentPage'] = $requestURI[4];
    }
}else if($_REQUEST['action']=='saved-posts'){
    $_GET['action'] = 'saved_posts';
    if(isset($requestURI[3]) && $requestURI[3]=="currentPage"){
        $_GET['currentPage'] = $requestURI[4];
    }
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.my-updates-nct.php");
$module = 'my-updates-nct';

if (isset($_GET['action']) && ( 'recent_updates' == $_GET['action'] || 'published_posts' == $_GET['action'] || 'saved_posts' == $_GET['action'] )) {
    $response = array();
    $response['status'] = false;
    
    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage'], 'input', 'int') : 1 );
    $action = filtering($_GET['action'], 'input');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $objMyUpdates = new My_updates();
    $result = $objMyUpdates->getFeedsLi($action, $user_id, $currentPage);

    $response['status'] = true;
    $response['content'] = $result;
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} 
