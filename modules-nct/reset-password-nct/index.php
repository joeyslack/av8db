<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='resetpassword'){
    if(isset($requestURI[2]) && $requestURI[2]!=""){
        $_GET['code']=$requestURI[2];    
    }
}

$reqAuth = false;

require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.reset-password-nct.php");

$module = 'reset-password-nct';

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    redirectPage(SITE_MOD . "users-profile-nct");
}
if (!isset($_GET["code"])) {
    redirectPage(SITE_URL);
}

$activationToken = isset($_GET["code"]) ? (filtering($_GET["code"], 'input')) : "";

$header_panel = true;
$left_panel = false;
$footer_panel = true;

$table = 'tbl_users';

$winTitle = '{LBL_RESET_PASS} - ' . SITE_NM;


$styles = array();
$scripts = array();

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

//_print($_POST);exit;


if (isset($_POST['reset_password']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $token = filtering($_POST['token'], 'input');
    $password = filtering($_POST['new_password'], 'input');
    $cnpassword = filtering($_POST['confirm_new_password'], 'input');

    if ($token != '' && $password != '' && ($password == $cnpassword)) {
        $get_user_details = $db->select('tbl_users', array('id'), array('password_reset_key' => $token))->result();
        $user_id = $get_user_details['id'];
        if ($user_id > 0) {
            $db->update('tbl_users', array("password" => md5($password), 'password_reset_key' => ''), array("id" => $user_id));

            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{LBL_PASSWORD_UPDATED}"));
        } else {
            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{ERROR_SOMETHING_GOES_WRONG_WHILE_DELTING_COMPANY}"));
        }
        redirectPage(SITE_URL);
    } else {
        $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{ERROR_SOMETHING_WRONG}"));
        redirectPage(SITE_URL);
    }
} else if (isset($_GET['code'])) {

    $check_if_code_is_valid = $db->select("tbl_users", "*", array("password_reset_key" => $activationToken))->result();
    if ($check_if_code_is_valid) {
        $prk_generated_on = strtotime($check_if_code_is_valid['prk_generated_on']);
        $expiry_time = $prk_generated_on + 3600;
        if (time() > $expiry_time) {
            $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => "{LBL_PASSWORD_LINK}"));
            redirectPage(SITE_URL);
        }
    } else {
        $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => "{LBL_INVALID_LINK}"));
        redirectPage(SITE_URL);
    }
}

$objLogin = new ResetPassword();

$pageContent = $objLogin->getPageContent($activationToken);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
