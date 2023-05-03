<?php
$reqAuth = false;
$module = 'contact_us-nct';
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.contact_us-nct.php");


extract($_REQUEST);

$winTitle = 'Contact Us'.' - ' . SITE_NM;
$headTitle = 'Contact Us'.'' . SITE_NM;
$metaTag = getMetaTags(array("description" => $winTitle, "keywords" => $headTitle, "author" => AUTHOR));

if(isset($_GET['user_id']) && $_GET['user_id'] != '') {

    $user_id = filtering($_GET['user_id'], 'input', 'int');
}else{
    $user_id=$_SESSION['user_id'];
}



$object = new Contact($user_id);
$pageContent = $object->getPageContent();


require_once DIR_TMPL . "parsing-nct.tpl.php";
?>