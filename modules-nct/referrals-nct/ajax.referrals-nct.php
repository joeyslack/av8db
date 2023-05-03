<?php

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.referrals-nct.php");
$module = 'referrals-nct';

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchForReferrals') {
    $user_id = ($_REQUEST['user_id']);
    $keyword = ($_REQUEST['keyword']);
    $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

    $objReferrals = new Referrals();
    $response = $objReferrals->getPeopleSearchForReferrals($page,true, true,'web',0,$keyword);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'sendReferralsRequest') {
    $user_id = ($_POST['user_id']);
    $page = isset($_POST['page'])?$_POST['page']:1;

    $objReferrals = new Referrals();
    $response = $objReferrals->sendReferralsRequest($page,true, true,'web',0,$user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && $_POST['action'] == 'getReferralReviewModal') {
    $referral_id = ($_POST['referral_id']);
    $ref_id = ($_POST['ref_id']);
    $sender_id = ($_POST['sender_id']);
    $page = isset($_POST['page'])?$_POST['page']:1;

    $objReferrals = new Referrals();
    $response = $objReferrals->getReferralModal($page,true, true,'web',0,$referral_id,$ref_id,$sender_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'rejectReferralRequest') {
    $referral_id = ($_POST['referral_id']);
    $ref_id = ($_POST['ref_id']);
    $sender_id = ($_POST['sender_id']);
    $page = isset($_POST['page'])?$_POST['page']:1;

    $objReferrals = new Referrals();
    $response = $objReferrals->rejectReferralRequest($page,true, true,'web',0,$referral_id,$ref_id,$sender_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'approvepublishreferral') {
    $referral_id = ($_POST['referral_id']);
    $review_id = ($_POST['review_id']);
    $page        = isset($_POST['page'])?$_POST['page']:1;
    //print_r($referral_id);exit();
    $objReferrals = new Referrals();
    $response = $objReferrals->approvePublishReferral($page,true, true,'web',0,$referral_id,$review_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'resendReferralsRequest') {
    $referral_id = ($_POST['referral_id']);
    $review_id = ($_POST['review_id']);
    $page        = isset($_POST['page'])?$_POST['page']:1;

    $objReferrals = new Referrals();
    $response = $objReferrals->resendReferralsRequest($page,true, true,'web',0,$referral_id,$review_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} 

$objProfile = new Profile();
