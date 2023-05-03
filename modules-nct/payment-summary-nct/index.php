<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='payment-summary'){
    $_GET['ph_id'] = $requestURI[2];
}

$reqAuth = true;
$allowedUserType = 'tcm';
if ($_REQUEST['custom'] != '' || $_REQUEST['cm'] != '') {
    $_SESSION['user_id'] = $_REQUEST['cm'];
}

require_once(DIR_URL."includes-nct/config-nct.php");
require_once(DIR_FUN . "paypal_class.php");
require_once("class.payment-summary-nct.php");
$module = 'payment-summary-nct';

/*if(isset($_SESSION['toastr_message'])){
    $_SESSION['toastr_message']=$_SESSION['toastr_message'];
}*/

if (isset($_GET['ph_id']) && $_GET['ph_id'] != "") {
    $ph_id = filtering(decryptIt($_GET['ph_id']), 'input', 'int');
    $payment_history = $db->select("tbl_payment_history", "*", array("id" => $ph_id ))->result();
    if(!isset($_SESSION['user_id']))
    {
        $get_user_details = $db->pdoQuery("SELECT * FROM tbl_users WHERE id = '".$payment_history['user_id']."'")->result();
        $_SESSION["user_id"] = $get_user_details['id'];
    }
    //echo "<pre>";print_r($payment_history);exit;
    $paypal_array['payment_gross'] = $_REQUEST['amt'];
    $paypal_array['txn_id'] = $_REQUEST['tx'];
    $paypal_array['payment_status'] = $_REQUEST['st'];
    $paypal_array['invoice'] = $payment_history['invoice_id'];
    handlePaypalPaymentResponse($paypal_array);
    if(!$payment_history) {
        $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => "{LBL_SUMMERY_PAYMENT}"));
        redirectPage(SITE_URL.'dashboard');
    }
} else {
    $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => "{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}"));
    redirectPage(SITE_URL.'dashboard');
}

$winTitle = '{LBL_PAYMENT_SUMMARY} - ' . SITE_NM;

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

$metaTag = getMetaTagsAll(array('description' => $final_description,
    'keywords' => $final_keywords,
    'og_title' => $winTitle
));

$objPaymentSummary = new Payment_summary();
$pageContent = $objPaymentSummary->getPageContent($ph_id);

require_once(DIR_TMPL . "parsing-nct.tpl.php");