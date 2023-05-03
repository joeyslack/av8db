<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='search'){
    if(isset($requestURI[2]) && $requestURI[2]!=""){
        $_GET['entity']=$requestURI[2];    
    }
}

$reqAuth = false;

if($_GET['entity']=='jobs' || $_GET['entity']=='groups'){
	$reqAuth = true;

}
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.search-nct.php");
$module = 'search-nct';
$include_google_maps_js = true;
$init_autocomplete = true;
$winTitle = "{LBL_SEARCH}- " . SITE_NM;

$styles = array(
    array("ionRangeSlider/css/ion.rangeSlider.css", SITE_PLUGIN),
    array("ionRangeSlider/css/ion.rangeSlider.skinModern.css", SITE_PLUGIN)
);

$scripts = array(
    array("ionRangeSlider/js/ion.rangeSlider.js", SITE_PLUGIN)
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

$objSearch = new Search();
$pageContent = $objSearch->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
