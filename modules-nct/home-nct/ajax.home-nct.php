<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.home-nct.php");
$module = 'home-nct';

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'validate_captcha' && isset($_REQUEST['captcha_code']) && $_REQUEST['captcha_code'] != '') {
    $captcha_code = filtering($_REQUEST['captcha_code']);
    $signup_captcha_code = filtering($_SESSION['signup_captcha_code']);
    
    if ($captcha_code != $signup_captcha_code) {
        $valid = 'false'; // Not Allowed
    } else {
        $valid = 'true'; // Allowed
    }

    echo $valid;
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'checkIfEmailExists' && isset($_REQUEST['signup_email_address']) && $_REQUEST['signup_email_address'] != '') {
    $email_address = filtering($_REQUEST['signup_email_address']);

    $email_valid = $db->select("tbl_users", "*", array("email_address" => $email_address))->result();

    if ($email_valid) {
        echo 'false';
        //echo LBL_EMAIL_EXIST;
        exit;
    } else {
        echo 'true';


        exit;
    }
} else if (isset($_POST['submit_feedback'])) {
    $objHome = new Home();
    $user_id = ( ( isset($_SESSION['user_id']) ) ? filtering($_SESSION['user_id'], "input", "int") : '');
    $response = $objHome->processContactFeedbackForm('f', $user_id);

    echo json_encode($response);
    exit;
} else if (isset($_POST['submit_contact_form'])) {
    $objHome = new Home();
    $user_id = ( ( isset($_SESSION['user_id']) ) ? filtering($_SESSION['user_id'], "input", "int") : '');
    $response = $objHome->processContactFeedbackForm('c', $user_id);
    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getCompanies') {
    $objHome = new Home();

    $company_name = filtering($_POST['company_name'], 'input');

    $response = $objHome->getCompany($company_name);
    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getCompanyLocations') {
    $objHome = new Home();

    $company_id = decryptIt(filtering($_POST['company_id'], 'input', 'int'));

    $response = $objHome->getJobLocation($company_id);

    echo json_encode($response);
    exit;
} else if($_POST['action'] == 'gplus-post'){
   
    extract($_POST);
    $name=explode(' ',$name);
    //print_r($name);die;
    $first_name = isset($name[0]) ? $name[0] : '';
    $last_name = isset($name[1]) ? $name[1] : '';
    $email = isset($email) ? $email : '';
    $gplus_id = isset($gplus_id) ? $gplus_id : '';
    $q = $db->select('tbl_users',array('*'),array('email_address'=>$email));
    $exist = $q->affectedRows();
    $fetch = $q->result();
    if($exist==0){
        $insert = array();
        $insert['first_name'] = $first_name;
        $insert['last_name'] = $last_name;
        $insert['email_address'] = $email;
        $password = generatePassword();
        $insert['password'] = md5($password);
        $insert['gender'] = 'm';
        $insert['status'] = 'a';
        $insert['email_verified'] = 'y';
        $insert['social_login_type'] = 'g';
        $insert['identifier'] = $gplus_id;
        $insert['date_added'] = date("Y-m-d H:i:s");
        $insert['date_updated'] = date("Y-m-d H:i:s");

        $user_id = $db->insert("tbl_users", $insert)->getLastInsertId();
        
        //mailchimp code for register 4-1-2020
        addemailtomailchimp($email);
        
        if ($user_id) {

            //For admin notification
            $data = array();
            $data['admin_id'] = 1;
            $data['entity_id'] = $user_id;
            $data['type'] = 'nr';
            $data['date_added'] = date('Y-m-d H:i:s');
            $db->insert('tbl_admin_notifications', $data);



            
            $array_to_be_replaced = array();
            $array_to_be_replaced['greetings'] = stripcslashes($first_name) . " " . stripcslashes($last_name);
            $array_to_be_replaced['social_login_type_text'] = 'Google';
            $array_to_be_replaced['email_address'] = $email;
            $array_to_be_replaced['password'] = $password;
            generateEmailTemplateSendEmail("social_signup", $array_to_be_replaced, $email);
            doLogin($user_id,$first_name,$last_name);
            $response['status'] = 'success';
            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{LBL_SUCCESSFULLY_SIGNUP}, {ERROR_PLEASE_ADD_EXPERIENCE_TO_GET_THE_GROUP_SUGGESTIONS}"));
            
        } else {
            $response['status'] = 'error';
            $response['message'] = ERROR_SOME_ISSUE_CONTACT_ADMIN;
        }
    } else {
        if($fetch['status'] == 'd'){
            $response['status'] = 'error';
            $response['message'] = ERROR_ACCOUNT_DEACTIVATED_CONTACT_ADMIN;
        } else {
            doLogin($fetch['id'],$fetch['first_name'],$fetch['last_name']);
            $response['status'] = 'success';
            //$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => "{LBL_SUCCESS_LOGIN}"));
        }
    }

    
    echo json_encode($response);exit;
}