<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.create-company-nct.php");
$module = 'create-company-nct';

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

// print_r($_REQUEST);
if($_REQUEST['action']=='create-company'){
    $_POST['create_company']='create_company';
}


if(isset($_POST['create_company']) && $_POST['create_company'] == 'create_company') {
    
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $objCreateCompany = new Create_company();
    $response = $objCreateCompany->processCompnayCreation($user_id);
    echo json_encode($response);
    exit;
}


$winTitle = "{BTN_MYC_CREATE_COMPANY_TITLE} - " . SITE_NM;

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

$objCreateCompany = new Create_company();
$pageContent = $objCreateCompany->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
