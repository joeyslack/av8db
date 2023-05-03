<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='post_recent-updates'){
    $_REQUEST['action']='post_recent_updates';
}else if($_REQUEST['action']=='post_published-posts'){
    $_REQUEST['action']='post_published_posts';
}else if($_REQUEST['action']=='post_saved-posts'){
    $_REQUEST['action']='post_saved_posts';
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.post-activity-nct.php");
$module = 'post-activity-nct';

if(isset($_REQUEST['action']) && $_REQUEST['action'] != '') {
    $action = filtering($_REQUEST['action']);
    
    if(!in_array($action, array("post_recent_updates", "post_published_posts", "post_saved_posts","post_all_activity"))) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => LBL_URL_DOESNT_EXIST));
        redirectPage(SITE_URL . "dashboard"); 
    } else {
        if('post_recent_updates' == $action) {
            $winTitle = LBL_RECENT_UIPDATES." - " . SITE_NM;
        } else if('post_published_posts' == $action) {
            $winTitle = LBL_PUBLISHED_POSTS." - " . SITE_NM;
        } else if('post_saved_posts' == $action) {
            $winTitle = LBL_SAVED_POSTS." - " . SITE_NM;
        }else if('post_all_activity' == $action) {
            $winTitle = LBL_ALL_ACTIVITY." - " . SITE_NM;
        }
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_SOME_ISSUE_TRY_LATER));
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

$objMyUpdates = new postactivity($_SESSION['user_id'],'web');
$pageContent = $objMyUpdates->getpostactivityPageContent($action);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
