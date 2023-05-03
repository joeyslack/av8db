<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='email'){
    $_REQUEST['email'] = $requestURI[2];
    $_REQUEST['hash'] = $requestURI[4];    
}else if($_REQUEST['action']=='unsubscribe_email'){
    $_REQUEST['unsubscribe_email'] = $requestURI[2];
    $_REQUEST['hash'] = $requestURI[4]; 
}


$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['subscribe_email'] != '') {
    extract($_POST);

    $flagstatus = false;

    $response = array();
    $response['status'] = false;

    $hash = md5(rand(0, 1000));
    $email = $objPost->email = isset($subscribe_email) ? filtering($subscribe_email, 'input') : '';
    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0) {
        $user_id = 0;
    } else {
        $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    }

    $subscribed_on = date("Y-m-d H:i:s");
    $status = 'a';


    //if ($email != "") {

        $qrySel = $db->select("tbl_subscribers", "email")->results();

        foreach ($qrySel as $fetchRes) {
            if ($fetchRes['email'] == $email) {
                $response['error'] = LBL_ALREADY_SUBCRIBED_NEWSLETTER;
                echo json_encode($response);
                exit;
            }
        }
        $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
        if($email_address==$subscribe_email){
            //mailchimp code 28-12-2020
            $list_id = MAILCHIMP_LIST_ID;
            $api_key = MAILCHIMP_API_KEY;
            $data_center = substr($api_key,strpos($api_key,'-')+1);
            $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
            $json = json_encode([
                'email_address' => $subscribe_email,
                'status'        => 'subscribed', //pass 'subscribed' or 'pending'
            ]);
            try{
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                $result = curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
             //   echo $status_code;exit;
                if ($status_code == 200) {
                    $valArray = array(
                        "email" => $subscribe_email,
                        "subscribed_on" => date("Y-m-d H:i:s"),
                        "hash" => $hash,
                        "status" => 'a'
                    );
        
                    if($user_id) {
                        $valArray['user_id'] = $user_id;
                    }
        
                    $id = $db->insert("tbl_subscribers", $valArray)->getLastInsertId();
        
                    if ($id) {
                        $arrayCont = array();
                        $arrayCont['subject'] = SITE_NM;
                        /*$arrayCont['verification_url'] = SITE_URL . "email/" . encryptIt($subscribe_email) . "/hash/" . $hash;*/
                        generateEmailTemplateSendEmail("newsletter_subscription_same_email", $arrayCont, $subscribe_email);
                        $flagstatus = true;
                    }
                }
                else if ($status_code == 400) {
                    $flagstatus = false;
                }
                else {
                    $flagstatus = false;
                }
            }catch(Exception $e) {
                $flagstatus = false;
            }
             
        }else{
            //mailchimp code 28-12-2020
            $list_id = MAILCHIMP_LIST_ID;
            $api_key = MAILCHIMP_API_KEY;
            $data_center = substr($api_key,strpos($api_key,'-')+1);
            $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
            $json = json_encode([
                'email_address' => $subscribe_email,
                'status'        => 'subscribed', //pass 'subscribed' or 'pending'
            ]);
            try{
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                $result = curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
             //   echo $status_code;exit;
                if ($status_code == 200) {
                    $valArray = array(
                        "email" => $subscribe_email,
                        "subscribed_on" => date("Y-m-d H:i:s"),
                        "hash" => $hash,
                        "status" => 'd'
                    );
        
                    if($user_id) {
                        $valArray['user_id'] = $user_id;
                    }
        
                    $id = $db->insert("tbl_subscribers", $valArray)->getLastInsertId();
        
                    if ($id) {
                        $arrayCont = array();
                        $arrayCont['subject'] = SITE_NM;
                        $arrayCont['verification_url'] = SITE_URL . "email/" . encryptIt($subscribe_email) . "/hash/" . $hash;
                        generateEmailTemplateSendEmail("newsletter_subscription", $arrayCont, $subscribe_email);
        
                        $flagstatus = true;
                    }
                }
                else if ($status_code == 400) {
                    $flagstatus = false;
                }
                else {
                    $flagstatus = false;
                }
            }catch(Exception $e) {
                $flagstatus = false;
            }
        }
   // }

    if ($flagstatus) {
        $response['status'] = true;
        $response['success'] = LBL_NEWSLETTER_SUBSCRIBED;
        echo json_encode($response);
        exit;
    } else {
        $response['error'] = LBL_SOME_ISSUE_NEWSLETTER;
        echo json_encode($response);
        exit;
    }
}

if (isset($_REQUEST['email']) && isset($_REQUEST['hash'])) {

    if (filter_var(decryptIt($_REQUEST['email']), FILTER_VALIDATE_EMAIL)) {
        $checkIfSubscribed = $db->select("tbl_subscribers", "*", array("email" => decryptIt($_REQUEST['email'])))->result();
        if ($checkIfSubscribed) {
            if ($checkIfSubscribed['status'] == "a") {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_EMAIL_VERIFIED_NEWSLETTER}"));
            } else {
                $affectedRows = $db->update("tbl_subscribers", array("status" => 'a'), array("email" => decryptIt($_REQUEST['email'])))->affectedRows();

                if ($affectedRows && $affectedRows > 0) {
                    $arrayCont = array();
                    $arrayCont['subject'] = SITE_NM;
                    $arrayCont['unsubscribe_url'] = SITE_URL . "unsubscribe_email/" . $_REQUEST['email'] . "/hash/" . $_REQUEST['hash'];
                    generateEmailTemplateSendEmail("email_verification_successful", $arrayCont, decryptIt($_REQUEST['email']));

                    $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => '{LBL_EMAIL_VARIFIED}'));
                } else {
                    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_SOME_ISSUE_VERFIYING_EMAIL_NEWSLETTER}"));
                }
            }
        } else {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{LBL_EMAIL_DOESNT_EXIST}'));
        }
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{LBL_EMAIL_NOT_VALID}'));
    }

    redirectPage(SITE_URL . "dashboard");
}


if (isset($_REQUEST['unsubscribe_email']) && isset($_REQUEST['hash'])) {

    if (filter_var(decryptIt($_REQUEST['unsubscribe_email']), FILTER_VALIDATE_EMAIL)) {
        $affectedRows = $db->delete("tbl_subscribers", array("email" => decryptIt($_REQUEST['unsubscribe_email'])))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => '{LBL_EMAIL_VARIFIED}'));
        } else {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{LBL_EMAIL_DOESNT_EXIST}'));
        }
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => '{LBL_EMAIL_NOT_VALID}'));
    }

    redirectPage(SITE_URL);
}

require_once(DIR_TMPL . "parsing-nct.tpl.php");
