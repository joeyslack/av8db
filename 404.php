<?php
$reqAuth = false;
require_once("includes-nct/config-nct.php");
require_once(DIR_MOD . "not-found-nct/class.not-found-nct.php");

$module = 'not-found-nct';

$header_panel = true;
$left_panel = false;
$footer_panel = true;

$winTitle = '404 Not Found - ' . SITE_NM;

$styles = array();
$scripts = array();

$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    'author' => AUTHOR));

$objNotFound = new Not_found();
$pageContent = $objNotFound->getPageContent();
require_once(DIR_TMPL . "parsing-nct.tpl.php");