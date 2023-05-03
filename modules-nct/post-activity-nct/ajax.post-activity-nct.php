<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_GET['action'] = $requestURI[2];

if(isset($requestURI[4]) && $requestURI[4]!=""){
    $_GET['currentPage'] = $requestURI[4];    
}

if($_GET['action']=='post_recent-updates'){
    $_GET['action']='post_recent_updates';
}else if($_GET['action']=='post_published-posts'){
    $_GET['action']='post_published_posts';
}else if($_GET['action']=='post_saved-posts'){
    $_GET['action']='post_saved_posts';
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.post-activity-nct.php");
$module = 'post-activity-nct';

if (isset($_GET['action']) && ( 'post_recent_updates' == $_GET['action'] || 'post_published_posts' == $_GET['action'] || 'post_saved_posts' == $_GET['action'] || 'post_all_activity' == $_GET['action'])) {
    
    $response = array();
    $response['status'] = false;
    
    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage'], 'input', 'int') : 1 );
    $action = filtering($_GET['action'], 'input');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $objMyUpdates = new postactivity($_SESSION['user_id'],'web');
    $result = $objMyUpdates->getFeedsLi($action, $user_id, $currentPage);

    $response['status'] = true;
    $response['content'] = $result;
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} 
