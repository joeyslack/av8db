<?php

$reqAuth = true;

require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.account-settings-nct.php");

$module = 'account-settings-nct';

$table = 'tbl_users';

$winTitle = '{ACCOUNT_SETTINGS} - ' . SITE_NM;

$styles = array(
    array("main.css", SITE_THEME_CSS),
    array("bootstrap-switch.css", SITE_THEME_CSS)
);

$scripts = array(
    array("highlight.js", SITE_THEME_JS),
    array("bootstrap-switch.js", SITE_THEME_JS),
    array("main.js", SITE_THEME_JS)
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

$objAccountSettings = new Account_settings();
$pageContent = $objAccountSettings->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
