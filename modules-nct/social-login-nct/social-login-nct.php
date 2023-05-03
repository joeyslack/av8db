<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['param'] = $requestURI[2];

if($_REQUEST['param']=='google'){
    $_GET['social_media']='Google';
}

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
use \Exception as Exception;

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '') {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{LBL_ALREADY_SIGNED_IN}'));
    redirectPage(SITE_URL);
}

$enabled_social_medias = array("facebook", "google", "linkedin");


if (isset($_GET['social_media']) && $_GET['social_media'] != '') {
    $social_media_mode = filtering($_GET['social_media']);

    switch ($social_media_mode) {
        case 'Facebook': {
                $social_media_mode_short = 'f';
                break;
            }
        case 'Google': {
                $social_media_mode_short = 'g';
                break;
            }
        case 'LinkedIn': {
                $social_media_mode_short = 'l';
                break;
            }
    }
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{ERROR_INVALID_URL}'));
    echo closePopup();
    exit;
}

$config_file_path = DIR_HYBRIDAUTH . 'config.php';
require_once(DIR_HYBRIDAUTH . "Hybrid/Auth.php" );

$hybridauth = new Hybrid_Auth($config_file_path);

try {
    
    $social_login = $hybridauth->authenticate($social_media_mode);
    //echo "<pre>";print_r($social_login);exit;
    $social_user_profile = $social_login->getUserProfile();

    //echo "<pre>";print_r($social_user_profile);exit;

    $social_login->logout();

    $identifier = filtering($social_user_profile->identifier, 'input');
    $displayName = filtering($social_user_profile->displayName, 'input');
    $first_name = filtering($social_user_profile->firstName, 'input');
    $last_name = filtering($social_user_profile->lastName, 'input');
    $email = filtering($social_user_profile->email, 'input');
    $emailVerified = filtering($social_user_profile->emailVerified, 'input');

    $sql = "SELECT u.* FROM tbl_users u WHERE u.email_address = '" . $email . "' ";
    $get_user_details = $db->pdoQuery($sql)->result();
    if ($get_user_details) {
        $user_social_login_type = filtering($get_user_details['social_login_type']);
        $user_identifier = filtering($get_user_details['identifier']);

        if ($user_social_login_type == $social_media_mode_short && $identifier == $user_identifier) {

            if ('d' == $get_user_details['status']) {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_ACCOUNT_DEACTIVATED_CONTACT_ADMIN}"));
                echo closePopup();
                exit;
            } else {
                $user_id = filtering($get_user_details['id'], 'output', 'int');
                $first_name = filtering($get_user_details['first_name']);
                $last_name = filtering($get_user_details['last_name']);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                echo closePopup();
                exit;
            }
        } else if ($user_social_login_type != 'r') {
            if ($user_social_login_type == 'f') {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_ALREADY_REGISTERED_FACEBOOK}"));
            } else if ($user_social_login_type == 'g') {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_ALREADY_REGISTERED_GOOGLE}"));
            } else if ($user_social_login_type == 'l') {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_ALREADY_REGISTERED_LINKEDIN}"));
            }

            echo closePopup();
            exit;
        } else {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_ALREADY_REGISTERED_EMAIL}"));
            echo closePopup();
            exit;
        }
    } else {
        $hybridauth_response = (array) $social_user_profile;

        $first_name = filtering($hybridauth_response['firstName'], 'input');
        $last_name = filtering($hybridauth_response['lastName'], 'input');
        $email_address = filtering($hybridauth_response['email'], 'input');

        $user_details_array = array();
        $user_details_array['first_name'] = $first_name;
        $user_details_array['last_name'] = $last_name;
        $user_details_array['email_address'] = $email_address;

        $password = generatePassword();
        $user_details_array['password'] = md5($password);

        $gender = ( ( $hybridauth_response['gender'] == 'male' ) ? 'm' : 'f' );

        $user_details_array['gender'] = $gender;

        $user_details_array['status'] = 'a';
        $user_details_array['email_verified'] = 'y';

        $user_details_array['social_login_type'] = $social_media_mode_short;
        $user_details_array['identifier'] = $identifier;
        $user_details_array['date_added'] = date("Y-m-d H:i:s");
        $user_details_array['date_updated'] = date("Y-m-d H:i:s");
        
        $user_id = $db->insert("tbl_users", $user_details_array)->getLastInsertId();

        if ($user_id) {
            $log = print_r($hybridauth_response, TRUE);

            $social_login_log_insert_array = array(
                "user_id" => $user_id,
                "log" => $log,
                "added_on" => date("Y-m-d H:i:s")
            );

            $db->insert("tbl_social_login_log", $social_login_log_insert_array)->getLastInsertId();

            $array_to_be_replaced = array();
            $array_to_be_replaced['greetings'] = stripcslashes($first_name) . " " . stripcslashes($last_name);
            $array_to_be_replaced['social_login_type_text'] = $social_media_mode;
            $array_to_be_replaced['email_address'] = $email_address;
            $array_to_be_replaced['password'] = $password;

            generateEmailTemplateSendEmail("social_signup", $array_to_be_replaced, $email_address);

            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{LBL_ADD_EXPERIENCE_PROFILE}"));
            echo closePopup();
            exit;
        } else {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_SOME_ISSUE_CONTACT_ADMIN}"));
            echo closePopup();
            exit;
        }
    }
} catch (Exception $e) {
    //echo "<pre>";print_r($e);exit;
    //echo "Ooophs, we got an error: " . $e->getMessage();
    //echo " Error code: " . $e->getCode();exit;
    // Display the recived error,
    // to know more please refer to Exceptions handling section on the userguide
    switch ($e->getCode()) {
        case 0 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_UNSPEIFIED}"));
            break;
        case 1 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_HYBRID_CONFIGURATION}"));
            break;
        case 2 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_PROVIDER_NOT_PROPERLY_CONFIGURED}"));
            break;
        case 3 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_UNKNOWN_PROVIDER}"));
            break;
        case 4 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_MISSING_PROVIDER_CREDENTIALS}"));
            break;
        case 5 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_AUTHENTICATION_FAILED_PROVIDER}"));
            break;
        case 6 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_USER_PROFILE_REQUEST_FAILED}"));
            $facebook->logout();
            break;
        case 7 :
            echo "{ERROR_USER_NOT_CONNECTED_PROVIDER}";
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_PROVIDER_NOT_PROPERLY_CONFIGURED}"));
            $facebook->logout();
            break;
        case 8 :
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_PROVIDER_DOESNT_SUPORT_FEATURE}"));
            break;
    }
    echo closePopup();
    exit;
}
