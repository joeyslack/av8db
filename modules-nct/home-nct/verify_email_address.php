<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='signin'){
    if($requestURI[2]=='email'){
        $_REQUEST['email_address']=$requestURI[3];
        $_REQUEST['activation_key']=$requestURI[5];
    }
}

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.home-nct.php");
$module = 'home-nct';

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '') {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{LBL_ALREADY_SIGNED_IN}'));
    redirectPage(SITE_URL);
}

if (isset($_REQUEST["email_address"]) && !empty($_REQUEST["email_address"]) && isset($_REQUEST["activation_key"]) && !empty($_REQUEST["activation_key"])) {

    $email_address = base64_decode($_REQUEST["email_address"]);
    $activation_key = $_REQUEST["activation_key"];

    $regexp = '/([a-zA-Z0-9._%+-]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
    if (preg_match($regexp, trim($email_address))) {
        
        $email_valid = $db->select("tbl_users", "*", array(
                    "email_address" => filtering($email_address, 'input'),
                    "activation_key" => filtering($activation_key, 'input')))->result();
        
        if ($email_valid) {
            if ('y' == $email_valid['email_verified']) {
                $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => '{LBL_EMAIL_VERIFIED}'));
            } else {
                
                $db->update("tbl_users", array(
                    "email_verified" => 'y',
                    "status" => 'a'
                        ), array(
                    "id" => $email_valid['id']
                        )
                );
                
                $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => '{LBL_CONGRATES_ACOUNT_ACTIVATED_SUCCESSFULLY}'));
            }
        } else {
            $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => '{ERROR_COM_ACTIVE_LINK}.'));
        }
    } else {
        $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => '{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}'));
    }

    redirectPage(SITE_URL."signin");
} else {
    $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => '{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}'));
    redirectPage(SITE_URL."signin");
}
