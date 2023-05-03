<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.membership-plans-nct.php");
$module = 'membership-plans-nct';

$winTitle = '{LBL_MEMBERSHIP_PLANS} - ' . SITE_NM;

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

$objMembershipPlans = new Membership_plans();
$pageContent = $objMembershipPlans->getPageContent();

require_once(DIR_TMPL . "parsing-nct.tpl.php");
