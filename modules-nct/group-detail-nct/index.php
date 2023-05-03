<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['group_id'] = $requestURI[2]; 

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.group-detail-nct.php");
$module = 'group-detail-nct';

if(isset($_REQUEST['group_id']) && $_REQUEST['group_id'] != '') {
    $group_id = filtering($_REQUEST['group_id'], 'input', 'int');
    
    $checkIfExists = $db->select("tbl_groups", "*", array("id" => $group_id))->result();

    if($checkIfExists['privacy'] == 'pr' && $checkIfExists['user_id'] != filtering($_SESSION['user_id'], 'input', 'int')) {
        $checkIfMemberExists = $db->select("tbl_group_members", "*", array("group_id" => $group_id, "user_id" => filtering($_SESSION['user_id'], 'input', 'int')))->result();
        if(!$checkIfMemberExists) {   
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_YOU_ARE_NOT_MEMBER_PRIVATE_GROUP}"));
            redirectPage(SITE_URL . "dashboard");     
        }
    }
    
    if(!$checkIfExists) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_GROUP_TRYING_TOVIEW_DOESNT_EXIST}"));
        redirectPage(SITE_URL . "dashboard");
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_PROVIDE_GROUP_ID}"));
    redirectPage(SITE_URL . "dashboard");
}

$winTitle = "{LBL_GROUP_DETAIL} - " . SITE_NM;


$styles = array(
    array("select2-4.0.3/select2-4.0.3/dist/css/select2.min.css", SITE_PLUGIN)
);

$scripts = array(
    array("select2-4.0.3/select2-4.0.3/dist/js/select2.full.min.js", SITE_PLUGIN)
);

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

$objGroups = new Group_detail($group_id);
$pageContent = $objGroups->getGroupsPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
