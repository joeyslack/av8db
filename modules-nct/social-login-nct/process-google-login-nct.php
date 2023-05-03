<?php

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

$response = array();
$response['status'] = false;

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '') {
    $response['error'] = "{LBL_ALREADY_SIGNED_IN}";
    echo json_encode($response);
    exit;
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['identifier']) && filtering($_POST['identifier'], "input") != "") {
    $first_name = filtering($_POST['first_name'], "input");
    $last_name = filtering($_POST['last_name'], "input");
    $email = filtering($_POST['email'], "input");
    $identifier = filtering($_POST['identifier'], "input");
    
    $social_media_mode_short = "g";
    
    $sql = "SELECT u.*  
                FROM tbl_users u 
                WHERE u.email_address = '" . $email . "' ";

    $get_user_details = $db->pdoQuery($sql)->result();
    if ($get_user_details) {
        $user_social_login_type = filtering($get_user_details['social_login_type']);
        $user_identifier = filtering($get_user_details['identifier']);

        if ($user_social_login_type == $social_media_mode_short && $identifier == $user_identifier) {
            if ('d' == $get_user_details['status']) {
                $response['error'] = "{LBL_ACC_DEACTIVATED_CONTACT_ADMIN}";
                echo json_encode($response);
                exit;
            } else {
                $user_id = filtering($get_user_details['id'], 'output', 'int');
                $first_name = filtering($get_user_details['first_name']);
                $last_name = filtering($get_user_details['last_name']);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                $response['status'] = true;
                $response['success'] = "{LBL_ADD_EXPERIENCE_PROFILE}";
                $response['redirect_url'] = SITE_URL;
                $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{LBL_ADD_EXPERIENCE_PROFILE}"));
                echo json_encode($response);
                exit;
            }
        } else if ($user_social_login_type != 'r') {
            if ($user_social_login_type == 'f') {
                $response['error'] = "{ALERT_ALREADY_SIGNED_IN_FACEBOOK}";
            } else if ($user_social_login_type == 'g') {
                $response['error'] = "{ALERT_ALREADY_SIGNED_IN_GOOGLEOOGLE}";
            } else if ($user_social_login_type == 'l') {
                $response['error'] = "{LBL_ALREADY_SIGNED_LINKEDIN}";
            }

            echo json_encode($response);
            exit;
        } else {
            $response['error'] = "{ALERT_ALREADY_SIGNED_IN_EMAIL}";
            echo json_encode($response);
            exit;
        }
    } else {
        $user_details_array = array();
        $user_details_array['first_name'] = $first_name;
        $user_details_array['last_name'] = $last_name;
        $user_details_array['email_address'] = $email;

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
            $array_to_be_replaced = array();
            $array_to_be_replaced['greetings'] = stripcslashes($first_name) . " " . stripcslashes($last_name);
            $array_to_be_replaced['social_login_type_text'] = $social_media_mode;
            $array_to_be_replaced['email_address'] = $email;
            $array_to_be_replaced['password'] = $password;

            generateEmailTemplateSendEmail("social_signup", $array_to_be_replaced, $email);

            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            
            $response['status'] = true;
            $response['success'] = "{LBL_ADD_EXPERIENCE_PROFILE}";
            $response['redirect_url'] = SITE_URL;
            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{LBL_ADD_EXPERIENCE_PROFILE}"));
            echo json_encode($response);
            exit;
        } else {
            $response['error'] = "{LBL_ SOME_ISSUE_UPDATING_DATA}";
            echo json_encode($response);
            exit;
        }
    }
} else {
    $response['error'] = "{LBL_PROVIDED_DATA_INSUFFICIENT}";
    echo json_encode($response);
    exit;
}
