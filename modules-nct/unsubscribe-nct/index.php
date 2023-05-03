<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='unsubscribe'){
    $subscriberId=$requestURI[2];
}

$reqAuth = false;
$module  = 'unsubscribe-nct';
require_once DIR_URL."includes-nct/config-nct.php";
require_once "class.unsubscribe-nct.php";

extract($_REQUEST);
$winTitle = $headTitle = 'Unsubscribe ' . SITE_NM;

$metaTag = getMetaTags(array(
    "description" => $winTitle,
    "keywords"    => $headTitle,
    "author"      => AUTHOR,
));

$subscriberId = isset($subscriberId) ? $subscriberId : "";
if($subscriberId != ""){
    
    $subscriberId = base64_decode($subscriberId);
    $subscriberId = str_replace('nct_','', $subscriberId);
    //$db->pdoQuery("DELETE FROM  tbl_subscribers WHERE id = ?",array($subscriberId));
    $db->delete("tbl_subscribers",array('id'=>$subscriberId));
     
     $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => '{UNSUBSCRIBE_MSG}'));
     if($_SESSION['user_id']>0){
        redirectPage(SITE_URL."dashboard");
     }else{
            redirectPage(SITE_URL);

     }

}else{
    $msgType = $_SESSION["toastr_message"] = disMessage(array(
        'type' => 'err',
        'var'  => 'Something went wrong',
    ));
    redirectPage(SITE_URL);
}


$obj = new Unsubscribe($module, 0, isset($token));

$pageContent = $obj->getPageContent();

require_once DIR_TMPL . "parsing-nct.tpl.php";
