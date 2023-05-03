<?php 

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='pay-for-fj'){
    $_GET['plan_id'] = $_POST['plan_id'] = $requestURI[3];
    $_GET['job_id'] = $_POST['job_id'] = $requestURI[5];
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.membership-plans-nct.php");
$module = 'membership-plans-nct';

$objMembershipPlans = new Membership_plans();
if (isset($_GET['plan_id']) && $_GET['plan_id'] != "") {
    $plan_id = filtering(decryptIt($_GET['plan_id']), 'input', 'int');
    $response = $objMembershipPlans->checkPlanSubscriptionCriteria($plan_id);
    if (!$response['status']) {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => $response['error']));
        redirectPage(SITE_URL . "jobs/my-jobs");
    }
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}"));
    redirectPage(SITE_URL . "jobs/my-jobs");
}

if (isset($_GET['job_id']) && $_GET['job_id'] != "") {
    $job_id = filtering(decryptIt($_GET['job_id']), 'input', 'int');
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}"));
    redirectPage(SITE_URL . "jobs/my-jobs");
}
if (isset($_POST['subscribe'])) {
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $plan_id = filtering(decryptIt($_POST['plan_id']), 'input', 'int');
    $job_id = filtering(decryptIt($_POST['job_id']), 'input', 'int');
    $response = $objMembershipPlans->processPlanSubscriptionForFJ($user_id, $plan_id, $job_id);
    if ($response['status']) {
        $invoice_id = $response['invoice_id'];
        redirectPage(SITE_URL . "checkout/txn_id/" . base64_encode($invoice_id).'/'.base64_encode($_SESSION['user_id']));
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => $response['error']));
        redirectPage(SITE_URL . "jobs/my-jobs");
    }
}
$winTitle = '{LBL_FEATURED_JOB} - ' . SITE_NM;
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

$metaTag=getMetaTagsAll(array('description'=>$final_description,'keywords'=>$final_keywords,'og_title'=>$winTitle));
$pageContent = $objMembershipPlans->getPlanDetailsForFJ($plan_id, $job_id);
require_once(DIR_TMPL . "parsing-nct.tpl.php");