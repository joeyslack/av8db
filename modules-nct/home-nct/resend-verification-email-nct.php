<?php

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.home-nct.php");
$module = 'home-nct';

if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '') {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{LBL_ALREADY_SIGNED_IN}'));
    redirectPage(SITE_URL);
}

if (isset($_POST['resend_verification_email_address']) && isset($_POST['resend_verification_email_address']) && $_POST['resend_verification_email_address'] != '') {
    $response = array();
    $response['status'] = false;

    //echo "<pre>";print_r($_POST);exit;
    
    $email_address = $_POST["resend_verification_email_address"];

    $regexp = '/([a-zA-Z0-9._%+-]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/';
    if (preg_match($regexp, trim($email_address))) {
        
        /*$sql = "SELECT u.* 
                FROM tbl_users u 
                WHERE u.email_address = '" . filtering($email_address, 'input') . "' ";
        
        $email_valid = $db->pdoQuery($sql)->result();*/
        $email_valid =$db->select("tbl_users", "*", array("email_address" => filtering($email_address, 'input')))->result();
        if ($email_valid) {
            if ('y' == $email_valid['email_verified']) {

                $response['error'] = "{LBL_YOUR_EMAIL_VERIFIED}";
                echo json_encode($response);
                exit;
            } else {
                $timestamp = time();
                $activation_key = sha1($timestamp);
                $db->update("tbl_users",array("activation_key"=>$activation_key),array("id"=>$email_valid['id']));
                $arrayCont = array();
                $arrayCont['greetings'] = $email_valid['first_name'] . " " . $email_valid['last_name'];
                $arrayCont['activationLink'] = "Click <a href='" . SITE_URL . "signin/email/" . encryptIt($email_valid['email_address']) . "/activation_key/" . $activation_key . "' target='_blank'>here</a> to activate account.";

                generateEmailTemplateSendEmail("user_register", $arrayCont, $email_valid['email_address']);

                $response['status'] = true;
                $response['success'] = LBL_ACCOUNT_VERIFICATION_EMAIL_SENT ." ". $email_valid['email_address'];
                echo json_encode($response);
                exit;
            }
        } else {
            $response['error'] = LBL_EITHER_VERIFIED_OR_DOESNT_MATCH;
            echo json_encode($response);
            exit;
        }
    } else {
        $response['error'] = "{LBL_VALID_EMAIL}";
        echo json_encode($response);
        exit;
    }
}
