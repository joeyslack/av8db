<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];
if($requestURI[2]=='getCompanies_load'){
    $_REQUEST['action']='getCompanies';
    $_REQUEST['currentPage']=$requestURI[4];
    $_REQUEST['type']=$requestURI[5];
}else if ($requestURI[1]=='getAirportSuggestions') {
    $_POST['action']='getClosestAirport';
}elseif ($requestURI[1] == 'getCompanies') {
    $_REQUEST['action']='getCompanies';
}

$reqAuth = true;
$_SESSION['user_id'] = $_POST['sess_user_id'];
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.companies-nct.php");
$module = 'companies-nct';

if(isset($_REQUEST['action']) && 'getCompanies' == $_REQUEST['action']) {
    $response = array();
    $response['status'] = false;
   
    $page = isset($_REQUEST['page'])?filtering($_REQUEST['page'], 'input', 'int'):filtering($_REQUEST['currentPage'], 'input', 'int');
    $type = filtering($_REQUEST['type'], 'input');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $objCompanies = new Companies();
    $result = $objCompanies->getCompanies($user_id, $type, $page);
    
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['pagination'] = $result['pagination'];
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'unfollowCompany' == $_POST['action']) { 
    $page = ( ( isset($_POST['page']) ? filtering($_POST['page'], 'input', 'int') : 1 ) );
    $company_id = filtering(decryptIt($_POST['company_id']), 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objCompanies = new Companies();
    $response = $objCompanies->unfollowCompanies($user_id, $company_id, $page);
    $response['follower_count'] = $objCompanies->getCompanyFollowers($company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getClosestAirport') {
    $objCompanies = new Companies();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $airport_name = filtering($_POST['airport_name'], 'input');
    $response = $objCompanies->getAirportsForSuggestion($user_id, $airport_name,'web');
    
    echo json_encode($response);
    exit;
}