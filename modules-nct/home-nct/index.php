<?php

define("DIR_URL", "/Users/jslack/working/av8db_code/");
$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='signup'){
    if(isset($requestURI[2]) && $requestURI[2]!="" && $requestURI[2]=='user'){
        $_GET['user']='user';
        $_REQUEST['another_user']=$requestURI[3];
        $_GET['profile']=$requestURI[5];
    }
}

$reqAuth = false;
$module = 'home-nct';

//echo __DIR__."includes-nct/config-nct.php";exit;
require_once(DIR_URL."includes-nct/config-nct.php");

$include_google_maps_js = true;
$init_autocomplete = true;
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) { redirectPage(SITE_URL."dashboard"); }
$header_panel = true;
$left_panel = false;
$footer_panel = true;
// if(isset($_REQUEST['sign_up']) && $_REQUEST['sign_up'] != '') {
//     $objHome->getSignupTpl();
//     //exit;
// }
if (isset($_POST['signup'])) {
    
    $response = array();
    $response['status'] = false;


    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $gdata = array(
        'secret' => GOOGLE_CAPTCHA_SECRET_KEY,
        'response' => $_POST["g-recaptcha-response"]
    );
    $options = array(
        'http' => array (
            'method' => 'POST',
            'content' => http_build_query($gdata)
        )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success=json_decode($verify);

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        if($captcha_success->success){
            
            $first_name = filtering($_POST['first_name'], 'input');
            $last_name = filtering($_POST['last_name'], 'input');
            $email_address = filtering($_POST['signup_email_address'], 'input');
            $password = filtering($_POST['signup_password'], 'input');
            $captcha_code = filtering($_POST['captcha_code'], 'input');
            $terms_conditions = isset($_POST['terms_conditions']) ? $_POST['terms_conditions'] : 'n';
            
            $isRefferalUser = $user_profile_id = '';
            if(isset($_GET['user']) && isset($_GET['profile']) && $_GET['user'] != '' && $_GET['profile'] != ''){
                $isRefferalUser = 'y';
                $_SESSION['user_profile_id'] = $_GET['profile'];
            }else{
                $isRefferalUser = 'n';
            }
            
            if ($first_name == '') {
                $response['error'] = ERROR_SIGNUP_ENTER_YOUR_FIRST_NAME;
                echo json_encode($response);
                exit;
            }
            if ($last_name == '') {
                $response['error'] = ERROR_FEEDBACK_LAST_NAME;
                echo json_encode($response);
                exit;
            }
            if ($email_address == '') {
                $response['error'] = ERROR_EDIT_COMP_ENTER_EMAIL_ADDRESS;
                echo json_encode($response);
                exit;
            }
            if ($password == '') {
                $response['error'] = LBL_ENTER_PASS;
                echo json_encode($response);
                exit;
            }
            $email_valid = $db->select("tbl_users", "*", array("email_address" => $email_address))->result();
            if ($email_valid) {
                $response['error'] = LBL_EMAIL_EXIST;
                echo json_encode($response);
                exit;
            }

            $timestamp = time();
            $activation_key = sha1($timestamp);
            $valArray = array(
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email_address" => $email_address,
                "password" => md5($password),
                "terms_conditions" => $terms_conditions,
                "date_added" => date("Y-m-d H:i:s"),
                "date_updated" => date("Y-m-d H:i:s"),
                "activation_key" => $activation_key,
                "isReferralLink" => $isRefferalUser
            );
            $lastId = $db->insert("tbl_users", $valArray)->getLastInsertId();
            
            //mailchimp code for register 4-1-2020
            addemailtomailchimp($email_address);
            
            if ($lastId > 0) {
                
                    $data = array();
                    $data['admin_id'] = 1;
                    $data['entity_id'] = $lastId;
                    $data['type'] = 'nr';
                    $data['date_added'] = date('Y-m-d H:i:s');
                    $db->insert('tbl_admin_notifications', $data);

                    $arrayCont = array();
                    $arrayCont['greetings'] = $first_name;
                    $arrayCont['activationLink'] = LBL_CLICK." <a href='" . SITE_URL . "signin/email/" . base64_encode($email_address) . "/activation_key/" . $activation_key . "' target='_blank'>".LBL_HERE_S."</a> ".LBL_TO_ACTIVE_ACCOUNT;
                    generateEmailTemplateSendEmail("user_register", $arrayCont, $email_address);
                    $response['status'] = true;
                    $response['success'] = LBL_REGISTERED_PLEASE_ACTIVATE_EMAIL;
                    echo json_encode($response);
                    exit;

            } else {
                $response['error'] = ERROR_SOME_ISSUE_TRY_LATER;
                echo json_encode($response);
                exit;
            }

        } 
        else {
            $response['error'] = ERROR_ROBOT_VARIFICATION;
            echo json_encode($response);
            exit;
        }
    }
    else {
        $response['error'] = ERROR_CATPCHA;
        echo json_encode($response);
        exit;
    }
    exit;

    

} else if (isset($_POST['signin'])) {
    $response = array();
    $response['status'] = false;
    $email_address = $_POST['login_email_address'];
    $password = $_POST['login_password'];
    $remember = (isset($_POST['remember_me']) ) ? true : false;
    $strlen=strlen($password);
    $no_star=substr_count($password, '*');
    if($email_address != '' && $password != ''){
        if($no_star==$strlen) {
            $password = base64_decode($_COOKIE["password"]); 
        }
        $get_user_details = $db->pdoQuery("SELECT * FROM tbl_users WHERE email_address = ? AND password = ? ",array(filtering($email_address, 'input'),filtering(md5($password), 'input')))->result();
        if ($get_user_details) {
            if ('n' == $get_user_details['email_verified']) {
                

                 $response['error'] = LBL_YOUR_EMAIL_HASNT_BEEN_VARIFIED_DIDNT_GET_IT." <a title='".LBL_RESEND_VERIFICATION_EMAIL."' href='javascript:void(0);' class='resend_verification_email'>".LBL_RESEND_VERIFICATION_EMAIL."</a>";
                echo json_encode($response);
                exit;
            } else if ('d' == $get_user_details['status']) {
                /*$response['error'] = ERROR_ACCOUNT_DEACTIVATED_CONTACT_ADMIN." <a href='" . SITE_URL . "contact-us' title='".LBL_CONTACT_US."'> ".LBL_HERE_S." </a>";*/
                $response['error'] = ERROR_ACCOUNT_DEACTIVATED_CONTACT_ADMIN;
                echo json_encode($response);
                exit;
            } else {
                $_SESSION['user_id'] = filtering($get_user_details['id'], 'output', 'int');
                $_SESSION['first_name'] = filtering($get_user_details['first_name']);
                $_SESSION['last_name'] = filtering($get_user_details['last_name']);
                if ($remember) {
                    setcookie("user_id", filtering($get_user_details['id'], 'output', 'int'), time() + (604800), '/');
                    setcookie("email_address", filtering($email_address), time() + (604800), '/');
                    setcookie("password", filtering(base64_encode($password)), time() + (604800), '/');
                } else {
                    setcookie("user_id", filtering($get_user_details['id'], 'output', 'int'), time() - (604800), '/');
                    setcookie("email_address", filtering($email_address), time() - (604800), '/');
                    setcookie("password", filtering($password), time() - (604800), '/');
                }
                $response['status'] = true;
                
                if($get_user_details['isReferralLink'] == 'y'){
                    if (isset($_SESSION['user_profile_id']) && $_SESSION['user_profile_id'] != '') {
                        $redirect_url = SITE_URL . "profile/".$_SESSION['user_profile_id'];    
                    }else{
                        $redirect_url = SITE_URL . "dashboard";
                    }
                }else{
                    if(isset($_SESSION['req_uri']) && $_SESSION['req_uri'] != "")
                    {
                        $redirect_url = $_SESSION['req_uri'];
                        unset($_SESSION['req_uri']);
                    } else {
                        $redirect_url = SITE_URL . "dashboard";
                    }
                }
                
                $response['redirect_url'] = $redirect_url;
                echo json_encode($response);
                exit;
            }
        } else {
            $get_user_details = $db->pdoQuery("SELECT * FROM tbl_users WHERE email_address = ? ",array($email_address))->result();
            if ($get_user_details) {
                $response['error'] = ERROR_ENTERED_PASS_INCORRECT;
                echo json_encode($response);
                exit;
            } else {
                $response['error'] = LBL_NO_USER_FOUND;
                echo json_encode($response);
                exit;
            }
        }
    }else{
        if ($email_address == '') {
                $response['error'] = ERROR_EDIT_COMP_ENTER_EMAIL_ADDRESS;
                echo json_encode($response);
                exit;
        }
        else if ($password == '') {
            $response['error'] = LBL_ENTER_PASS;
            echo json_encode($response);
            exit;
        }else{
            $response['error'] = ERROR_EDIT_PROFILE_BASIC_FILL_ALL_MANDATORY_FIELDS;
            echo json_encode($response);
            exit;
        }
    }
} else if (isset($_POST['forgot_password_email_address']) && $_SERVER['REQUEST_METHOD'] == "POST") {
    $response = array();
    $response['status'] = false;
    $email_address = filtering($_POST['forgot_password_email_address'], 'input');
    //print_r($email_address);
    if($email_address != ''){
        
        $get_user_details =$db->select("tbl_users", "*", array("email_address" => filtering($email_address, 'input')))->result();
        if ($get_user_details) {
            $first_name = filtering($get_user_details['first_name']);
            $last_name = filtering($get_user_details['last_name']);
            if ($get_user_details['email_verified'] == 'y' && $get_user_details['status'] == 'a') {
                $password_reset_key = md5($email_address . time());

                $db->update("tbl_users", array(
                    "password_reset_key" => $password_reset_key,
                    "prk_generated_on" => date("Y-m-d H:i:s")
                        ), array("id" => $get_user_details['id']));
                $password_reset_link = SITE_URL . 'resetpassword/' . $password_reset_key;
                $arrayCont = array();
                $arrayCont['greetings'] = $first_name . " " . $last_name;
                $arrayCont['password_reset_link'] = "<a href=" . $password_reset_link . " title='".LBL_RESET_PASS."'>".LBL_RESET_PASS."</a><br>";
                //echo "one";
                generateEmailTemplateSendEmail("reset_password", $arrayCont, $email_address);
                //print_r($test);exit;
                $response['status'] = true;
                $response['success'] = LBL_RESET_PASS_LINK;
            } else {
                if ($get_user_details['email_verified'] == 'n') {
                    $response['error'] = LBL_YOU_HAVENT_VARIEIFED_EMAIL;
                } else if ($get_user_details['status'] == 'd') {
                    $response['error'] = ERROR_ACCOUNT_DEACTIVATED_CONTACT_ADMIN;
                }
            }
        } else {
            $response['error'] = LBL_NO_ACCOUNT_ASSOCIATED;
        }
    } else {
        $response['error'] = API_EMAIL_REQ;
    }
    if($_GET['platform'] == 'app'){
        $app_array['status'] = (($response['status'] == true) ? 'success' : 'error');
        $app_array['message'] = (($response['status'] == true) ? $response['success'] : $response['error']);
        echo json_encode($app_array);
        exit;
    }
    echo json_encode($response);
    exit;
}
$winTitle = 'Home - ' . SITE_NM;
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
$metaTag=getMetaTagsAll(array('description'=>$final_description,'keywords'=>$final_keywords,'og_title'=>$winTitle));
$pageContent = $objHome->getPageContent();
require_once(DIR_TMPL."parsing-nct.tpl.php");