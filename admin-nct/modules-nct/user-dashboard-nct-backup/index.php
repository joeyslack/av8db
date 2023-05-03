<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='admin-nct'){
    if(isset($requestURI[2]) && $requestURI[2]=='user-dashboard'){
        //$_GET['user']='user';
        $_REQUEST['id']=$_GET['id']=$requestURI[3];
        if(isset($requestURI[4]) && $requestURI[4]!=""){
            $_REQUEST['action']=$_GET['action']=$requestURI[4];    
        }
    }
}
/*print_r($_REQUEST);
print_r($requestURI);exit;*/

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

include("class.user-dashboard-nct.php");
$module = "user-dashboard-nct";
$table = "tbl_users";

$styles = array(array("data-tables/DT_bootstrap.css", SITE_ADM_PLUGIN),
    array("bootstrap-switch/css/bootstrap-switch.min.css", SITE_ADM_PLUGIN));

$scripts = array("core/datatable.js",
    array("data-tables/jquery.dataTables.js", SITE_ADM_PLUGIN),
    array("data-tables/DT_bootstrap.js", SITE_ADM_PLUGIN),
    array("bootstrap-switch/js/bootstrap-switch.min.js", SITE_ADM_PLUGIN));

chkPermission($module);
$Permission = chkModulePermission($module);

$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    'author' => AUTHOR));

//echo "<pre>";print_r($_REQUEST);exit;

if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0) {
    $user_id = filtering($_REQUEST['id'], 'input', 'int');
    
} else {
    
}

$id = isset($_GET["id"]) ? (int) trim($_GET["id"]) : 0;
$postType = isset($_POST["type"]) ? trim($_POST["type"]) : '';
$type = isset($_GET["type"]) ? trim($_GET["type"]) : $postType;

$headTitle = "User Dashboard";
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


$objUserDashboard = new User_dashboard($module, $user_id);
$pageContent = $objUserDashboard->getPageContent();

require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
