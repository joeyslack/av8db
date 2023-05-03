<?php
require_once(DIR_URL."includes-nct/config-nct.php");

/**
 * Store the following user data
 */
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $valid_status = array('connected', 'not_authorized');
    //print_r($_POST);exit;
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    $name = explode(" ",$name);
    $firstName = $name[0];
    $lastName = $name[1];
    $gender = $gender == 'female'?'f':'m';

    $sql = "SELECT u.* FROM tbl_users u WHERE u.email_address = '" . $email . "' ";

    $get_user_details = $db->pdoQuery($sql)->result();
    //print_r($get_user_details);exit;
    if ($get_user_details) {

            if ('d' == $get_user_details['status']) {
                $response['message'] = disMessage(array('type' => 'err', 'var' => "{ERROR_ACCOUNT_DEACTIVATED_CONTACT_ADMIN}"));
                $response['url'] = SITE_URL;

            } else {
                $user_id = filtering($get_user_details['id'], 'output', 'int');
                $first_name = filtering($get_user_details['first_name']);
                $last_name = filtering($get_user_details['last_name']);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;

                $response['url'] = SITE_URL."dashboard/";

            }

    } else {

        $user_details_array = array();
        $user_details_array['first_name'] = $firstName;
        $user_details_array['last_name'] = $lastName;
        $user_details_array['email_address'] = $email;

        $password = generatePassword();
        $user_details_array['password'] = md5($password);
        $user_details_array['gender'] = $gender;

        $user_details_array['status'] = 'a';
        $user_details_array['email_verified'] = 'y';

        $user_details_array['social_login_type'] = 'f';
        $user_details_array['date_added'] = date("Y-m-d H:i:s");
        $user_details_array['date_updated'] = date("Y-m-d H:i:s");



        $img = 'http://graph.facebook.com/'.$id.'/picture?width=650&height=650';
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
            $array_to_be_replaced['greetings'] = stripcslashes($firstName)." ".stripcslashes($lastName);
            $array_to_be_replaced['social_login_type_text'] = 'Facebook';
            $array_to_be_replaced['email_address'] = $email;
            $array_to_be_replaced['password'] = $password;

            generateEmailTemplateSendEmail("social_signup", $array_to_be_replaced, $email);

            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $firstName;
            $_SESSION['last_name'] = $lastName;

            $_SESSION['toastr_message'] = disMessage(array('type' => 'suc', 'var' => "{LBL_ADD_EXPERIENCE_PROFILE}"));
            $response['url'] = SITE_URL."dashboard";


        } else {
            $response['message'] = disMessage(array('type' => 'err', 'var' => "{LBL_ SOME_ISSUE_UPDATING_DATA}"));

        }
    }
    echo json_encode($response);
}

