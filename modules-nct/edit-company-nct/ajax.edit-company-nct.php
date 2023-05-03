<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

$company_id='';
if(isset($requestURI[2]) && $requestURI[2]!=""){
    $company_id = $requestURI[2];
}

if($_REQUEST['action']=='update-company-details'){
    $_POST['action']='update_company_details';
    $_GET['company_id'] = $company_id;
}


if(isset($_REQUEST['action']) && 'addCompanyLocation_admin' == $_REQUEST['action']) {
    $reqAuth = false;

}else{
    $reqAuth = true;

}
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.edit-company-nct.php");
$module = 'edit-company-nct';

if((isset($_REQUEST['action']) && 'addCompanyLocation' == $_REQUEST['action']) || (isset($_REQUEST['action']) && 'addCompanyLocation_admin' == $_REQUEST['action'])) {
    $objEditCompany = new Edit_company();
    //print_r($_POST);die;
    //echo "<pre>";print_r(json_decode($_POST['place']));exit;
    if('addCompanyLocation_admin' == $_REQUEST['action']){
        $req='admin';
    }else{
        $req='front';
    }
    
    $dataInsert = $objEditCompany->insertCompanyLocation($req);
    $response = $objEditCompany->generateCompanyLocationBox();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'getConnections' == $_POST['action']) {
    $objEditCompany = new Edit_company();
    $response = $objEditCompany->getConnections();
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'getConnectionBox' == $_POST['action'] && isset ($_POST['user_id']) && $_POST['user_id'] != '') {
    $user_id = filtering($_POST['user_id'], 'input', 'int');
    
    $objEditCompany = new Edit_company();
    $response = $objEditCompany->generateCompanyAdminBox($user_id);
    echo json_encode($response);
    exit;
} else if(isset($_POST['update_company_details']) && isset($_GET['company_id']) && $_GET['company_id'] != '') {
    $company_id = filtering(decryptIt($_GET['company_id']), 'input', 'int');
    
    $objEditCompany = new Edit_company();
    $response = $objEditCompany->updateCompanyDetails($company_id);
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'deleteCompany' == $_POST['action']) {

    $company_id = decryptIt(filtering($_POST['company_id'], 'input', 'int'));
    
    $objEditCompany = new Edit_company();
    $response = $objEditCompany->deleteCompany($company_id);
    echo json_encode($response);
    exit;
}

