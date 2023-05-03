<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.payment-history-nct.php");

$module = 'payment-history-nct';

$header_panel = true;
$left_panel = false;
$footer_panel = true;

$winTitle = '{LBL_PAYMENT_HISTORY} - ' . SITE_NM;

$styles = array();
$scripts = array();

$final_description = $description;
$final_keywords = $keywords;
    
$metaTag = getMetaTagsAll(array('description' => $final_description,
    'keywords' => $final_keywords,
    'og_title' => $winTitle
));

$objPaymentHistory = new Payment_history(0,$_SESSION['user_id'],'web');
$pageContent = $objPaymentHistory->getPageContent();
require_once(DIR_TMPL . "parsing-nct.tpl.php");
