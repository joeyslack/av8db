<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['feed_id'] = $requestURI[2];

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.feed-nct.php");
$module = 'feed-nct';

if(isset($_REQUEST['feed_id']) && $_REQUEST['feed_id'] != '') {
    $feed_id = filtering(decryptIt($_REQUEST['feed_id']), 'input', 'int');
    
    $checkIfExists = $db->select("tbl_feeds", "*", array("id" => $feed_id))->result();
    
    if(!$checkIfExists) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_FEED_DOESNT_EXIST));
        redirectPage(SITE_URL . "dashboard");
    }
    
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_PROVIDE_GROUP_ID ));
    redirectPage(SITE_URL . "dashboard");
}

$winTitle = "{LBL_FEED_DETAIL}- " . SITE_NM;


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

$objFeed = new Feed($feed_id);
$pageContent = $objFeed->getFeedPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
