<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='publish-editpost'){
    if(isset($requestURI[2]) && $requestURI[2]!=""){
    	$_POST['feed_id']=$requestURI[2];
    }
}else{
    $_SESSION['user_id'] = $requestURI[2];
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.publish-post-nct.php");
$module = 'publish-post-nct';



$winTitle = "publish post - " . SITE_NM;

$styles = array();
$scripts = array(
    array("ckeditor_4.5.10_standard/ckeditor/ckeditor.js", SITE_PLUGIN)
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

$objPublishPost = new Publish_post();
$pageContent = $objPublishPost->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
