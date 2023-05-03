<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[3];
$_SESSION['user_id'] = $_POST['sess_user_id'];
if($_REQUEST['action']=='subscribe-plan' || $_REQUEST['action'] == 'subscribe-plan-nct.php'){
    $_GET['plan_id'] = $_REQUEST['plan_id'];
}
$_SESSION['user_id'] = $_POST['sess_user_id'];
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.membership-plans-nct.php");
$module = 'membership-plans-nct';

$objMembershipPlans = new Membership_plans();

if (isset($_GET['plan_id']) && $_GET['plan_id'] != "") {
    $plan_id = filtering(base64_decode($_GET['plan_id']), 'input', 'int');
    $response = $objMembershipPlans->checkPlanSubscriptionCriteria($plan_id);
    if (!$response['status']) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => $response['error']));
        redirectPage(SITE_URL . "membership-plans");
    }
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_PLAN_TRYING_PURCHASE_DOESNT_EXIST}"));
    redirectPage(SITE_URL . "membership-plans");
}

if (isset($_POST['subscribe'])) {
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $plan_id = filtering(base64_decode($_POST['plan_id']), 'input', 'int');

    $response = $objMembershipPlans->processPlanSubscription($user_id, $plan_id);

    if ($response['status']) {
        $invoice_id = $response['invoice_id'];
        redirectPage(SITE_URL . "checkout/txn_id/" . base64_encode($invoice_id).'/'.base64_encode($_SESSION['user_id']));
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => $response['error']));
        redirectPage(SITE_URL . "membership-plans");
    }
}


$winTitle = '{LBL_MEMBERSHIP_PLANS} - ' . SITE_NM;

$styles = '';
$scripts = '';

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
if (isset($_GET['plan_id']) && $_GET['plan_id'] != "" && $_POST['action']=='plandetail') {
    $plan_id = filtering(base64_decode($_GET['plan_id']), 'input', 'int');
    $response = $objMembershipPlans->getPlanDetails($plan_id);
    $response = preg_replace('/\{([A-Z_]+)\}/', "$1", $response);
    echo json_encode($response);

    exit;
}
$pageContent = $objMembershipPlans->getPlanDetails($plan_id);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
