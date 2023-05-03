<?php
$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);
$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='edit-group-form'){
    $_REQUEST['group_id'] = $requestURI[2];
    $_REQUEST['user_id'] = $requestURI[3];
    $_SESSION['user_id'] = $requestURI[3];
}else if($_REQUEST['action']=='create-group-form'){
    $_REQUEST['user_id'] = $requestURI[2];
    $_SESSION['user_id'] = $requestURI[2];
}
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.create-group-nct.php");
$module = 'create-group-nct';
if(isset($_REQUEST['group_id']) && $_REQUEST['group_id'] != '') {
    $group_id = filtering(decryptIt($_REQUEST['group_id']), 'input', 'int');
    
    $myJob = $db->select("tbl_groups", "id", array("id" => $group_id,'user_id'=>$_SESSION['user_id']))->result();
    if($myJob == 0){
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_GROUP_TRYING_TO_VIEW_ISNT_POSTED_BY_YOU}"));
        redirectPage(SITE_URL . "dashboard");
    }

    $checkIfExists = $db->select("tbl_groups", "*", array("id" => $group_id))->result();
    if(!$checkIfExists) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_GROUP_TRYING_MODIFY_DOESNT_EXIST}"));
        redirectPage(SITE_URL . "dashboard");
    }

    $winTitle = "{LBL_EDIT_GROUP} - " . SITE_NM;
    
} else {
    $group_id = 0;
    $winTitle = "{LBL_CREATE_GROUP} - " . SITE_NM;
}

$styles = array(
    array("image_crop_css/cropper.min.css", SITE_PLUGIN),
    array("image_crop_css/main.css", SITE_PLUGIN),
);
$scripts = array(
    array("image_crop/uploadimage.js", SITE_PLUGIN),
    array("image_crop/main.js", SITE_PLUGIN),
    array("image_crop/cropper.js", SITE_PLUGIN),
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

$objCreategroup = new Create_group($group_id);
$pageContent = $objCreategroup->getPageContent();
require_once(DIR_TMPL . "parsing-nct.tpl.php");