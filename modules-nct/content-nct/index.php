<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='content'){
    $_REQUEST['page_slug']=$requestURI[2];
}

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.content-nct.php");

$module = 'content-nct';

$header_panel = true;
$left_panel = false;
$footer_panel = true;

$page_slug = $_REQUEST['page_slug'];
$get_content_page = $db->select("tbl_content", "*", array("page_slug" => $page_slug , "isActive" => 'y'))->result();

if(!$get_content_page) {
    redirectPage(SITE_URL);
}
$page_id = $get_content_page['pId'];

$objContent = new Content($page_id);

$title = $objContent->getPageTitle();

$winTitle = $title . ' - ' . SITE_NM;

$styles = array();
$scripts = array();

$metas = get_meta_keyword_description($page_id);

if($metas) {
    $final_keywords = $metas['meta_keyword'];
    $final_description = $metas['meta_description'];
} else {
    $final_description = $description;
    $final_keywords = $keywords;
}

$metaTag = getMetaTagsAll(array('description' => $final_description,
    'keywords' => $final_keywords,
    'og_title' => $winTitle
));

$pageContent = $objContent->getPageContent();
require_once(DIR_TMPL . "parsing-nct.tpl.php");
