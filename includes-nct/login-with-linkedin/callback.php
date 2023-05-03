<?php

include 'Qassim_HTTP.php'; // include Qassim_HTTP() function
include 'config.php'; // include app data

if ($_GET['error'] == 'access_denied') {
    $_SESSION["toastr_message"] = 'toastr["error"]("Authentification failed. The user has canceled the authentication or the provider refused the connection.");';
    ?>
    <script type="text/javascript">window.close();</script>
    <?php
}

$code = $_GET['code'];
$method_ = 1; // method = 1, because we want POST method

//$url_ = "https://www.linkedin.com/uas/oauth2/accessToken";
$url_="https://www.linkedin.com/oauth/v2/accessToken";


$header_ = array("Content-Type: application/x-www-form-urlencoded");

$data_ = http_build_query(array(
    "client_id" => $client_id,
    "client_secret" => $client_secret,
    "redirect_uri" => $redirect_uri,
    "grant_type" => "authorization_code",
    "code" => $code
        ));

$json_ = 1; // json = 1, because we want JSON response

$get_access_token = Qassim_HTTP($method_, $url_, $header_, $data_, $json_);

$access_token = $get_access_token['access_token']; // user access token


/* Get User Info */

$method = 0; // method = 0, because we want GET method


$url = 'https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))';

$header = array("Authorization: Bearer $access_token");

$data = 0; // data = 0, because we do not have data

$json = 1; // json = 1, because we want JSON response

$user_basic_info = Qassim_HTTP($method, $url, $header, $data, $json);



$url2 = 'https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))';

$header = array("Authorization: Bearer $access_token");

$data = 0; // data = 0, because we do not have data

$json = 1; // json = 1, because we want JSON response

$email_info = Qassim_HTTP($method, $url2, $header, $data, $json);
$user_info = array_merge($user_basic_info,$email_info);

//echo '<pre>';
//print_r($user_info);exit;

if (!empty($user_info)) {

    $email = $user_info['elements'][0]['handle~']['emailAddress'];
    $firstName = $user_info['firstName']['localized']['en_US'];
    $lastName = $user_info['lastName']['localized']['en_US'];

    $sql = "SELECT u.*  
                FROM tbl_users u 
                WHERE u.email_address = '" . $email . "' ";

    $get_user_details = $db->pdoQuery($sql)->result();
    
    if ($get_user_details) {
        if ('d' == $get_user_details['status']) {
            $response['message'] = disMessage(array('type' => 'err', 'var' => "Your account is deactivated. Please contact admin."));
            $response['url'] = SITE_URL;
        } else {
            $user_id = filtering($get_user_details['id'], 'output', 'int');
            $first_name = filtering($get_user_details['first_name']);
            $last_name = filtering($get_user_details['last_name']);

            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => "You have been successfully logged in! "));
            //redirectPage(SITE_URL . "dashboard/");
            echo closePopup();
            exit;
        }
    } else {

        $user_details_array = array();
        $user_details_array['first_name'] = $firstName;
        $user_details_array['last_name'] = $lastName;
        $user_details_array['email_address'] = $email;

        $password = generatePassword();
        $user_details_array['password'] = md5($password);

        $user_details_array['status'] = 'a';
        $user_details_array['email_verified'] = 'y';

        $user_details_array['social_login_type'] = 'l';
        $user_details_array['date_added'] = date("Y-m-d H:i:s");
        $user_details_array['date_updated'] = date("Y-m-d H:i:s");

        $img = $user_info['profilePicture']['displayImage~']['elements'][3]['identifiers'][0]['identifier'];
        if($img != ''){
        $imgNm = md5(time().rand()); 
        $image = $imgNm.'.JPG'; 
        $user_details_array['profile_picture_name'] = $image;
        $content = file_get_contents($img); 
        $fp = fopen(DIR_UPD_USERS.$image, "w"); 
        fwrite($fp, $content); 
        fclose($fp);


        require_once(DIR_INC.'functions-nct/php_image_magician.php');
        $magicianObj = new imageLib(DIR_UPD_USERS.$image);
        $magicianObj->resizeImage(28, 28, array('crop', 'm'), true);
        $magicianObj->saveImage(DIR_UPD_USERS.'th1_'.$image, 100);
        $magicianObj->reset();

        $magicianObj->resizeImage(40, 40, array('crop', 'm'), true);
        $magicianObj->saveImage(DIR_UPD_USERS.'th2_'.$image, 100);
        $magicianObj->reset();

        $magicianObj->resizeImage(60, 60, array('crop', 'm'), true);
        $magicianObj->saveImage(DIR_UPD_USERS.'th3_'.$image, 100);
        $magicianObj->reset();

        $magicianObj->resizeImage(90, 90, array('crop', 'm'), true);
        $magicianObj->saveImage(DIR_UPD_USERS.'th4_'.$image, 100);
        $magicianObj->reset();

        $magicianObj->resizeImage(130, 130, array('crop', 'm'), true);
        $magicianObj->saveImage(DIR_UPD_USERS.'th5_'.$image, 100);
        $magicianObj->reset();
        }

        $user_id = $db->insert("tbl_users", $user_details_array)->getLastInsertId();
        
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
            $array_to_be_replaced['greetings'] = $firstName . " " . $lastName;
            $array_to_be_replaced['social_login_type_text'] = 'LinkedIn';
            $array_to_be_replaced['email_address'] = $email;
            $array_to_be_replaced['password'] = $password;

            generateEmailTemplateSendEmail("social_signup", $array_to_be_replaced, $email);

            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $firstName;
            $_SESSION['last_name'] = $lastName;

            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "Please add experience in your profile for better results."));
            //redirectPage(SITE_URL . "dashboard");
            echo closePopup();
            exit;
        } else {
            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "There seems to be some issue while updating your data to our database. Please contact site Admin."));
            echo closePopup();
            exit;
        }
    }
}
?>