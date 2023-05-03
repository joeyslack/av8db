<?php


$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['param'] = $requestURI[2];

if($_REQUEST['param']=='my-groups'){
    $_REQUEST['type']='my_groups';
}else if($_REQUEST['param']=='joined-groups'){
    $_REQUEST['type']='joined_groups';
}
//echo "<pre>";print_r($_SESSION);exit();
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.groups-nct.php");
$module = 'groups-nct';

if(isset($_REQUEST['type']) && $_REQUEST['type'] != '') {
    $type = filtering($_REQUEST['type']);
    
    if('my_groups' == $type || 'joined_groups' == $type ) {
        if('my_groups' == $type) {
            $winTitle = "{LBL_SUB_HEADER_MY_GROUPS}- " . SITE_NM;
        } else if('joined_groups' == $type) {
            $winTitle = "{LBL_JOINED_GROUP} - " . SITE_NM;
        } 
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_VALID_TYPE}"));
        redirectPage(SITE_URL . "dashboard");
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "T{ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME} "));
    redirectPage(SITE_URL . "dashboard");
}


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

$objGroups = new Groups();
$pageContent = $objGroups->getGroupsPageContent($type);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
