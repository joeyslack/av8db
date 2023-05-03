<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[2];

if($_REQUEST['action']=='payment_load'){
    $_REQUEST['action']='payment_load';
    $_REQUEST['page']=$requestURI[4];
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.payment-history-nct.php");
$module = 'payment-history-nct';

if(isset($_POST['action']) && 'getTransaction' == $_POST['action']) {
    $response = array();
    $response['status'] = false;
    
    $page = filtering($_POST['page'], 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objJobs = new Payment_history();
    $result = $objJobs->getPageContent($page);
    $result = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $result);

    echo json_encode($result);
    exit;
} else if(isset($_REQUEST['action']) && 'payment_load' == $_REQUEST['action']) {
    $response = array();
    $response['status'] = false;
    
    $page = filtering($_REQUEST['page'], 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objJobs = new Payment_history();
    $response = $objJobs->getTransactions($page);

    $result['status'] = true;
    $result['content'] = $response['content'];
    $result['load']=$response['load'];
    $result = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $result);
    echo json_encode($result);
    exit;
} 