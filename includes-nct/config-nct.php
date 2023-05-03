<?php 
//echo 'config';
ob_start();
session_start();
set_time_limit(0);
error_reporting(0);
session_set_cookie_params(3600);
session_name("connectin");
date_default_timezone_set('Asia/Kolkata');


global $db, $rand_numers,$helper,$fields,$module,$adminUserId,$sessUserId,$objHome,$main_temp,$breadcrumb,$Permission;
global $head,$header,$left,$right,$footer,$content,$title,$resend_email_verification_popup;
$include_sharing_js=$include_google_maps_js=$init_autocomplete=$include_google_login_js=false;
$header_panel=$footer_panel=true;
$styles=$scripts=array();
$reqAuth = isset($reqAuth) ? $reqAuth : false;
$allowedUserType = isset($allowedUserType) ? $allowedUserType : 'a';
$adminUserId=(isset($_SESSION["adminUserId"]) && $_SESSION["adminUserId"] > 0 ? (int) $_SESSION["adminUserId"] : 0);
$sessUserId = (isset($_SESSION["user_id"]) && $_SESSION["user_id"] > 0 ? (int) $_SESSION["user_id"] : 0);
$sessFirstName = (isset($_SESSION["first_name"]) && $_SESSION["first_name"] != '' ? $_SESSION["first_name"] : NULL);
$sessLastName = (isset($_SESSION["last_name"]) && $_SESSION["last_name"] != '' ? $_SESSION["last_name"] : NULL);
$sessUserType = (isset($_SESSION["user_type"]) && $_SESSION["user_type"] != '' ? $_SESSION["user_type"] : '');
$toastr_message = isset($_SESSION["toastr_message"]) ? $_SESSION["toastr_message"] : NULL;


$_SESSION['rand_numers'] = rand(4,999999999);
$rand_numers = (isset($_SESSION["rand_numers"]) ? $_SESSION["rand_numers"] : '');
unset($_SESSION['toastr_message']);

// if (strpos($_SERVER["SERVER_NAME"], '192.168.100') !== false OR strpos($_SERVER["SERVER_NAME"], 'localhost') !== false)
//     {
//         require_once($_SERVER["DOCUMENT_ROOT"].'/install-nct/install_config.php');
//     }else{
//         require_once($_SERVER["DOCUMENT_ROOT"].'/install-nct/install_config.php');
//     }


    define('SITENAME', $_SERVER['SERVER_NAME']);
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

    // if(INSTALL_TYPE == 'local'){

    //     $rootfile = $_SERVER["DOCUMENT_ROOT"] . '/demo.txt';
    //     if(!file_exists($rootfile)){
    //         header('Location: '.$protocol.SITENAME.'/install');
    //         exit;
    //     }
    // }else{
    //     $rootfile = $_SERVER["DOCUMENT_ROOT"] . '/demo.txt';
    //     if(!file_exists($rootfile)){
    //         header('Location: '.$protocol.SITENAME.'/install');
    //         exit;
    //     }
    // }

require_once('database-nct.php');
require_once('main_nct.php');


require_once('functions-nct/class.pdohelper.php');
require_once('functions-nct/class.pdowrapper.php');

require_once('functions-nct/class.pdowrapper-child.php');

//echo getenv('CLOUDSQL_DB');
require_once('mime_type_lib.php');
$dbConfig = array("host" => DB_HOST, "dbname" => DB_NAME, "username" => DB_USER, "password" => DB_PASS,"dbdsn" => DB_DSN);
$db = new PdoWrapper($dbConfig);

//print_r($dbConfig);exit;

$helper = new PDOHelper();
$error_log = ( ( IS_LIVE ) ? false : true );
$db->setErrorLog($error_log);



$language = $db->select('tbl_language',array('id'),array('default_lan'=>'y'))->result();

$lId = ((isset($_SESSION['lid']) && $_SESSION['lid']>0) ? $_SESSION['lid'] : ( (isset($app_lid) && $app_lid>0) ? $app_lid: $language['id'] ));


require_once('language-nct/'.$lId.'.php');

require_once('constant-nct.php');

require_once('functions-nct/functions-nct.php');



require_once(DIR_FUN . 'validation.class.php');



$objValidation = new validation();
curPageURL();
curPageName();
checkIfIsActive();


Authentication($reqAuth, true, $allowedUserType);



require("class.main_template-nct.php");



require_once('class.template-nct.php');



$main = new Templater();
//echo domain_details('dir').'test';exit;
if (domain_details('dir') == 'admin-nct') {

    $left_panel = true;
    require_once(DIR_ADM_INC . 'functions-nct/fields-nct.php');
    require_once(DIR_ADM_INC . 'functions-nct/admin-function-nct.php');
    require_once(DIR_ADM_MOD . 'home-nct/class.home-nct.php');
    $objHome = new Home($module, 0);
   // print_r($objHome);exit;
} else {

    require_once(DIR_MOD . 'home-nct/class.home-nct.php');
    $objHome = new Home();
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
        require_once(DIR_MOD . 'profile-nct/class.profile-nct.php');
        require_once(DIR_MOD . 'notifications-nct/class.notifications-nct.php');
        $objNotificationsGlobal = new Notifications();
        $objNotificationsGlobal->module = "notifications-nct";
    }
}
$objPost = new stdClass();
$description = SITE_NM;
$keywords = "";