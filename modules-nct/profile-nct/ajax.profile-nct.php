<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.profile-nct.php");
$module = 'profile-nct';

if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']) && '' != $_FILES['profile_picture']['name'] && 0 == $_FILES['profile_picture']['error']) {
    $objProfile = new Profile();

    $objProfile->updateProfilePicture();
} else if (isset($_REQUEST['action']) && 'remove_profile_picture' == $_REQUEST['action']) {

    $response = array();
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $get_photo_name = $db->select("tbl_user_profile_picture", "*", array("user_id" => $user_id))->result();
    if ($get_photo_name) {
        $image_name = $get_photo_name['image_name'];

        $affected_rows = $db->delete("tbl_user_profile_picture", array("user_id" => $user_id))->affectedRows();
        if ($affected_rows) {
            //rrmdir(DIR_UPD_USERS . $user_id . "/");

            unlink(DIR_UPD_USERS_PROFILE_PIC . $image_name);
            unlink(DIR_UPD_USERS_PROFILE_PIC . "th1_" . $image_name);
            unlink(DIR_UPD_USERS_PROFILE_PIC . "th2_" . $image_name);
            unlink(DIR_UPD_USERS_PROFILE_PIC . "th3_" . $image_name);
            unlink(DIR_UPD_USERS_PROFILE_PIC . "th4_" . $image_name);
            unlink(DIR_UPD_USERS_PROFILE_PIC . "th5_" . $image_name);
            $response['operation_status'] = "success";
            $response['message'] = "{LBL_YOUR_PROFILE_PICTURE_REMOVED}";
            $response['image_medium'] = getImageURL("user_profile_picture", $user_id, "th2");
            $response['image_small'] = getImageURL("user_profile_picture", $user_id, "th1");
        } else {
            $response['operation_status'] = "error";
            $response['message'] = "{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}";
        }
    } else {
        $response['operation_status'] = "error";
        $response['message'] = "{LBL_CURRENT_YOU_HAVE_NOT_SET_PROFILE}";
    }
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} 
// else if (isset($_POST['action']) && $_POST['action'] == 'getExperienceForm') {
//     if (isset($_POST['experience_id']) && $_POST['experience_id'] != '') {
//         $experience_id = filtering(decryptIt($_POST['experience_id']), 'input', 'int');
//     } else {
//         $experience_id = '';
//     }

//     $objProfile = new Profile();

//     $response = $objProfile->getExperienceForm($experience_id);
//     $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
//                             return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
//                 }, $response);
//     echo json_encode($response);
//     exit;
// } 
else if (isset($_POST['action']) && $_POST['action'] == 'getEducationForm') {
    if (isset($_POST['education_id']) && $_POST['education_id'] != '') {
        $education_id = filtering(decryptIt($_POST['education_id']), 'input', 'int');
    } else {
        $education_id = '';
    }

    $objProfile = new Profile();

    $response = $objProfile->getEducationForm($education_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getSkillForm') {
    $objProfile = new Profile();

    $response = $objProfile->getSkillForm();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getAirportForm') {
    $objProfile = new Profile();

    $response = $objProfile->getAirportForm();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getSkills') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $skill_name = filtering($_POST['skill_name'], 'input');
    $skill_id = str_replace("'",'',$_REQUEST['skill_id']);
    $response = $objProfile->getSkillsForSuggestion($user_id, $skill_name, $skill_id);
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getAirports') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $airport_name = filtering($_POST['airport_name'], 'input');
    $airport_id = str_replace("'",'',$_REQUEST['airport_id']);
    $response = $objProfile->getAirportsForSuggestion($user_id, $airport_name, $airport_id);
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getLicensesEndorsementForm') {
    if (isset($_POST['licenses_id']) && $_POST['licenses_id'] != '') {
        $licenses_id = filtering(decryptIt($_POST['licenses_id']), 'input', 'int');
    } else {
        $licenses_id = '';
    }

    $objProfile = new Profile();

    $response = $objProfile->getAddedLicensesEndorsementForm($licenses_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getHomeAirportForm') {
    if (isset($_POST['airport_id']) && $_POST['airport_id'] != '') {
        $airport_id = filtering(decryptIt($_POST['airport_id']), 'input', 'int');
    } else {
        $airport_id = '';
    }

    $objProfile = new Profile();

    $response = $objProfile->getAirportForm($airport_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getCompanies') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $company_name = filtering($_POST['company_name'], 'input');

    $response = $objProfile->getCompaniesForSuggestion($user_id, $company_name);
    
    echo json_encode($response);
    exit;
}
else if (isset($_POST['action']) && $_POST['action'] == 'getLicenses') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $licenses_name = filtering($_POST['licenses_name'], 'input');

    $response = $objProfile->getLicensesForSuggestion($user_id, $licenses_name);
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getCompaniesExp') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $company_name = filtering($_POST['company_name'], 'input');

    $response = $objProfile->getCompaniesForSuggestion($user_id, $company_name,'web','e');
    
    echo json_encode($response);
    exit;
}
//else if (isset($_POST['save_experience'])) {
//     $objProfile = new Profile();

//     $add_experience_response = $objProfile->addExperience();
//     $add_experience_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
//                             return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
//                 }, $add_experience_response);
//     echo json_encode($add_experience_response);
//     exit;
// }
else if (isset($_POST['save_licenses'])) {
    $objProfile = new Profile();
    //print_r($_POST);exit();
    $add_experience_response = $objProfile->addLicenses();
    $add_experience_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $add_experience_response);
    echo json_encode($add_experience_response);
    exit;
} else if (isset($_POST['save_education'])) {
    $objProfile = new Profile();

    $add_education_response = $objProfile->addEducation();
    $add_education_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $add_education_response);
    echo json_encode($add_education_response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'removeConnection') {

    $objProfile = new Profile();

    $first_user_id = decryptIt(filtering($_POST['user_id'], 'input', 'int'));
    $second_user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $remove_connection_response = $objProfile->removeConnection($first_user_id, $second_user_id);
    $remove_connection_response =preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $remove_connection_response);
    echo json_encode($remove_connection_response);
    exit;
} else if (isset($_POST['add_skill'])) {
    $objProfile = new Profile();
    $add_skill_response = $objProfile->addSkills();
    $add_skill_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $add_skill_response);
    echo json_encode($add_skill_response);
    exit;
} else if(isset($_POST['add_skill_multiple'])){
    $objProfile = new Profile();
    $add_skill_response = $objProfile->add_skills_multiple();
    $add_skill_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $add_skill_response);
    echo json_encode($add_skill_response);
    exit;
} else if(isset($_POST['add_airport_multiple'])){
    $objProfile = new Profile();
    $add_skill_response = $objProfile->add_airports_multiple();
    $add_skill_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $add_skill_response);
    echo json_encode($add_skill_response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getLanguageForm') {
    $objProfile = new Profile();

    $response = $objProfile->getLanguageForm();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getLanguages') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $language = filtering($_POST['language'], 'input');
    $language_id = str_replace("'",'',$_REQUEST['language_id']);

    $response = $objProfile->getLanguagesForSuggestion($user_id, $language,$language_id);
    echo json_encode($response);
    exit;
} else if (isset($_POST['add_language'])) {
    $objProfile = new Profile();

    $add_language_response = $objProfile->addLanguages();
    $add_language_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $add_language_response);
    echo json_encode($add_language_response);
    exit;
} else if(isset($_POST['add_language_multiple'])){
    $objProfile = new Profile();
    $add_language_response = $objProfile->addLanguagesMultiple();
    $add_language_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $add_language_response);
    echo json_encode($add_language_response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'removeLanguage') {

    $objProfile = new Profile();

    $language_id = decryptIt(filtering($_POST['language_id'], 'input', 'int'));
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $remove_language_response = $objProfile->removeLanguage($language_id, $user_id);
    $remove_language_response =preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $remove_language_response);
    echo json_encode($remove_language_response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'removeSkill') {
    //_print($_POST);exit;

    $objProfile = new Profile();

    $skill_id = decryptIt(filtering($_POST['skill_id'], 'input', 'int'));
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $remove_skill_response = $objProfile->removeSkill($skill_id, $user_id);
    $remove_skill_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $remove_skill_response);
    echo json_encode($remove_skill_response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'saveUserName') {
    //_print($_REQUEST);exit;

    $affectedRows = $db->update('tbl_users', array($_REQUEST['name'] => filtering($_REQUEST['value'], 'input', 'text')), array("id" => decryptIt($_REQUEST['pk'])))->affectedRows();

    if ($affectedRows) {
        
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'getCompanyLocations') {
    $objProfile = new Profile();

    $company_id = decryptIt(filtering($_POST['company_id'], 'input', 'int'));

    $response = $objProfile->getCompanyLocations($company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getUserDetailForm') {
   
    $objProfile = new Profile();
    $response = $objProfile->getUserDetailForm();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
}  else if (isset($_POST['save_user_detail'])) {
    $objProfile = new Profile();

    $response = $objProfile->updateUserDetails();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
 }  
 //else if (isset($_POST['action']) && $_POST['action'] == 'deleteExperience') {
//     //_print($_POST);exit;

//     $objProfile = new Profile();

//     $experience_id = decryptIt(filtering($_POST['experience_id'], 'input', 'int'));
//     $delete_experience_response = $objProfile->deleteExperience($experience_id);
//     $delete_experience_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
//                             return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
//                 }, $delete_experience_response);
//     echo json_encode($delete_experience_response);
//     exit;
// }  
else if (isset($_POST['action']) && $_POST['action'] == 'deleteEducation') {
    //_print($_POST);exit;

    $objProfile = new Profile();

    $education_id = decryptIt(filtering($_POST['education_id'], 'input', 'int'));
    $delete_education_response = $objProfile->deleteEducation($education_id);
    $delete_education_response =  preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $delete_education_response);
    echo json_encode($delete_education_response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'deleteLicense') {
    //_print($_POST);exit;

    $objProfile = new Profile();

    $licenses_id = decryptIt(filtering($_POST['licenses_id'], 'input', 'int'));
    $delete_education_response = $objProfile->deleteLicense($licenses_id);
    $delete_education_response =  preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $delete_education_response);
    echo json_encode($delete_education_response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'deleteAirport') {
    //_print($_POST);exit;

    $objProfile = new Profile();

    $airport_id = decryptIt(filtering($_POST['airport_id'], 'input', 'int'));
    $delete_education_response = $objProfile->deleteAirport($airport_id);
    $delete_education_response =  preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $delete_education_response);
    echo json_encode($delete_education_response);
    exit;
} else if(isset($_POST['action']) && $_POST['action'] == 'removeImage') {
    $response = array();
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $get_photo_name = $db->select("tbl_users", "*", array("id" => $user_id))->result();

     if ($get_photo_name) {
        $image_name = $get_photo_name['profile_picture_name'];

        $affected_rows = $db->update('tbl_users',array('profile_picture_name'=>''),array('id'=>$_SESSION['user_id']))->affectedRows();
        if ($affected_rows) {
            $img_arr= explode(".", $image_name); 
//echo $img_arr[0].".webp";die;
            unlink(DIR_UPD_USERS . $user_id."/" . $image_name);
            unlink(DIR_UPD_USERS . $user_id . "/th1_" . $image_name);
            unlink(DIR_UPD_USERS . $user_id. "/th2_" . $image_name);
            unlink(DIR_UPD_USERS . $user_id . "/th3_" . $image_name);
            unlink(DIR_UPD_USERS . $user_id. "/th4_" . $image_name);
            unlink(DIR_UPD_USERS . $user_id . "/th5_" . $image_name);
            unlink(DIR_UPD_USERS . "th1_" .$img_arr[0].".webp");
            unlink(DIR_UPD_USERS . "th2_" . $img_arr[0].".webp");
            unlink(DIR_UPD_USERS . "th3_" . $img_arr[0].".webp");
            unlink(DIR_UPD_USERS . "th4_" . $img_arr[0].".webp");
            unlink(DIR_UPD_USERS . "th5_" . $img_arr[0].".webp");



            $response['operation_status'] = "success";
            $response['message'] = "{LBL_YOUR_PROFILE_PICTURE_REMOVED}";
            $response['image_medium'] = '<span class="profile-picture-character">' . ucfirst(mb_substr($get_photo_name['first_name'], 0, 1, 'utf-8')) . '</span>';
            
        } else {
            $response['operation_status'] = "error";
            $response['message'] = "{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}";
        }
    } else {
        $response['operation_status'] = "error";
        $response['message'] = "{LBL_CURRENT_YOU_HAVE_NOT_SET_PROFILE}";
    }
    
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;

}else if (isset($_POST['action']) && $_POST['action'] == 'follow_user') {

    $objProfile = new Profile();
    $user_id = decryptIt(filtering($_POST['user_id'], 'input', 'int'));
    $status=$_POST['status'];
    $follow_user = $objProfile->followuser($user_id,$status,$_SESSION['user_id']);
    $follow_user = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $follow_user);
    echo json_encode($follow_user);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'removeFollowing') {

    $objProfile = new Profile();

    $first_user_id = decryptIt(filtering($_POST['user_id'], 'input', 'int'));
    $second_user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $remove_following_response = $objProfile->removeFollowing($first_user_id, $second_user_id);
    $remove_following_response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $remove_following_response);
    echo json_encode($remove_following_response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getClosestAirport') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $airport_identifier = filtering($_POST['airport_identifier'], 'input');

    $response = $objProfile->getAirportsForSuggestion1($user_id, $airport_identifier,'web');
    
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'requestAirportAddition')
{
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $requested_airport_name = filtering($_POST['requested_airport_name'], 'input');
    $response = $objProfile->requestForAirportAddition($user_id, $requested_airport_name,'web');
    
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'searchForReferrals') {
    $objProfile = new Profile();
    print_r($_POST);exit();
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $requested_airport_name = filtering($_POST['requested_airport_name'], 'input');
    $response = $objProfile->requestForAirportAddition($user_id, $requested_airport_name,'web');
    
    echo json_encode($response);
    exit;
}else if($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "checkReview"){
    $response = array();
    $response['status'] = true;
    
    $senderId = filtering($_POST['senderId'], "input", "int");
    $user_id = filtering($_POST['user_id'], 'input', 'int');

    $objProfile = new Profile($senderId,$user_id);

    $response['content'] = $objProfile->getEditReviewModal($senderId, $user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;   
}else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchInviteUser') {
    $user_id = ($_REQUEST['user_id']);
    $keyword = ($_REQUEST['keyword']);
    $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
    //print_r($user_id);exit();
    $objDashboard = new Profile();
    $response = $objDashboard->getInviteUserList($user_id, $page, true, $keyword);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'verifyLicense') {
    $license_id = ($_REQUEST['license_id']);
    $user_id = ($_REQUEST['user_id'] != '') ? $_REQUEST['user_id'] : $_SESSION['user_id'];
    $objDashboard = new Profile();
    $response = $objDashboard->verifyLicenseEndorsement($license_id,$user_id,'web');
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'inviteUserOnPlatform') {
    $user_id = ($_REQUEST['user_id']);
    $selected_license = ($_REQUEST['selected_license']);
    $objProfile = new Profile();
    $response = $objProfile->inviteUserOnPlatform($user_id,$selected_license,'web');
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getInstitute') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $institute_name = filtering($_POST['institute_name'], 'input');

    $response = $objProfile->getInstituteSuggestion($user_id, $institute_name,'web');
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'getLicenseList') {
    $objProfile = new Profile();

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $response['content'] = $objProfile->getLicenseList($user_id);
    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'reportFerryPilotReviews') {
    $objProfile = new Profile();

    $receiverId = filtering($_POST['receiverId'], 'input', 'int');
    $review_id = filtering($_POST['review_id'], 'input', 'int');

    $response = $objProfile->reportFerryPilotReview($receiverId,$review_id);
    echo json_encode($response);
    exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'upload_image'){
    require_once("storage.php");
    $userimage_storage = new storage();
    $which_types = (isset($_POST['which_types']) && $_POST['which_types'] != '') ? $_POST['which_types'] : '';
    $file_name = (isset($_POST['file_name']) && $_POST['file_name'] != '') ? $_POST['file_name'] : '';
    $user_id = (isset($_POST['user_id']) && $_POST['user_id'] != '') ? $_POST['user_id'] : '';
    $crop = $_POST['crop_data'];
    $main_url = $_POST['main_url'];
    if($file_name !="" || $file_name !=NULL){
        // echo 'in file_name if';
        $userimage_storage = new storage();
        $src2 = "user_cover-nct/".$user_id.'/';
        $crop_img_res = $resize_img = '';
        if ($user_id > 0) {
            // echo 'in user_id 1 if';
            $image_resize_array=array(array("newWidth"=>250,"newHeight"=>80),array("newWidth"=>792,"newHeight"=>198));
        
            $targetdir = $main_url;
            $my_image = $main_url;
            if (!extension_loaded('imagick')) {
                  echo "imagick not installed...";
            }else{
                // echo 'loaded';
                $im = new Imagick($my_image);
                // echo 'loaded1';
                $im->readImage($my_image);
                // echo 'loaded2';
                if ($crop['new_data']['rotate'] > 0) {
                    $im->rotateImage(new \ImagickPixel(), $crop['new_data']['rotate']); // This makes resulting image bigger
                    $im->setImagePage($im->getImageWidth(), $im->getImageHeight(), 0, 0);
                    // $im->rotateimage('', $crop['new_data']['rotate']);
                    // $crop_img = $userimage_storage->upload_objectBlob('av8db',$file_name,$im->getImageBlob(),$src2);
                    // $crop_img_res = $userimage_storage->getImageUrl1('av8db',$file_name,$src2);
                }
                // echo 'loaded3';
                $im->cropImage($crop['new_data']['width'], $crop['new_data']['width'], $crop['new_data']['x'], $crop['new_data']['y']); 
                // echo 'in user_id 2 if';
                $crop_img = $userimage_storage->upload_objectBlob('av8db',$file_name,$im->getImageBlob(),$src2);
                $crop_img_res = $userimage_storage->getImageUrl1('av8db',$file_name,$src2);
                // echo 'printing crop img response';
                // print_r($crop_img_res);

                $th_arr = array();        
                $th_arr[0] = array('width' => '250', 'height' => '80');
                $im->resizeImage($th_arr[0]['width'], $th_arr[0]['height'], Imagick::FILTER_LANCZOS, 1);
                $resize_img = $userimage_storage->upload_objectBlob('av8db','th1_'.$file_name,$im->getImageBlob(),$src2);

                 
                $resize_img_res = $userimage_storage->getImageUrl1('av8db','th1_'.$file_name,$src2);
                // echo 'printing resise img res';
                // print_r($resize_img_res);
                
                $im->clear();
                $im->destroy();
                $user_logo = $file_name;

                $old_profile_picture_name=getTableValue("tbl_users", "cover_photo", array("id" => $user_id));
                $profile_picture_name = $file_name;

                $affected_rows = $db->update("tbl_users", array("cover_photo" => $profile_picture_name,"date_updated" => date("Y-m-d H:i:s")), array("id" => $user_id))->affectedRows();
                // echo 'printing affectedRows';
                // print_r($affectedRows);
                $result1 = $userimage_storage->getImageUrl1('av8db','th1_'.$file_name,$src2);
                if ($affected_rows) {
                    // echo 'in affected_rows';
                    $del = $userimage_storage->delete_object('av8db',$file_name,'');
                    // echo 'after delete';
                    $response['status'] = true;
                    $response['updated_profile_pic_src'] = $result1;
                    $response['success'] = LBL_PROFILE_UPDATED;
                }
                // echo 'final response';
                // print_r($response);
            }
        }else{
            // echo 'main else';
            $del = $userimage_storage->delete_object('av8db',$file_name,'');
            $response['status'] = false;
        }
    }
    echo json_encode($response);
    exit;
}