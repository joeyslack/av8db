<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.create-job-nct.php");
$module = 'create-job-nct';
$include_google_maps_js = true;
$init_autocomplete = true;


$winTitle = "{LBL_MY_JOB_CREATE_JOB} - " . SITE_NM;

$styles = '';
$scripts = '';

$companyCount = getTableValue("tbl_companies","count(id)",array("user_id"=>$_SESSION['user_id']));
$companyLocation = $db->pdoQuery("SELECT count('cl.id') as count FROM tbl_companies c right JOIN tbl_company_locations cl on c.id = cl.company_id WHERE c.user_id = ".$_SESSION['user_id']." ")->result();

if($companyCount<=0){
	$msgType = $_SESSION["toastr_message"] = disMessage(array(
		'type' => 'err',
		'var' => PLZ_ADD_ONE_COMPANY
	));
	redirectPage(SITE_URL.'create-company');
}

if($companyCount>0 && $companyLocation['count']<=0){
	$msgType = $_SESSION["toastr_message"] = disMessage(array(
		'type' => 'err',
		'var' => PLZ_ADD_LOCATION_COM
	));
	redirectPage(SITE_URL.'company/my-companies');
}

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

$objCreateJob = new Create_job();
$pageContent = $objCreateJob->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
