<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.membership-plans-nct.php");
$module = 'membership-plans-nct';

$objMembershipPlans = new Membership_plans();

if (isset($_POST['no_of_inmails']) && $_POST['no_of_inmails'] != "") {

    if (checkWhetherToShowAdhocInmails()) {
        $no_of_inmails = filtering($_POST['no_of_inmails'], 'input', 'int');

        if ($no_of_inmails > 0) {
            
        } else {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_VALID_INMAILS}"));
            redirectPage(SITE_URL . "membership-plans");
        }
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_ADHOC_EMAIL_DISABLED}" ));
        redirectPage(SITE_URL . "membership-plans");
    }
} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME}"));
    redirectPage(SITE_URL . "membership-plans");
}

if (isset($_POST['subscribe'])) {
    
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $no_of_inmails = filtering($_POST['no_of_inmails'], 'input', 'int');

    $response = $objMembershipPlans->processInMailsSubscription($user_id, $no_of_inmails);

    if ($response['status']) {
        $invoice_id = $response['invoice_id'];
        redirectPage(SITE_URL . "checkout/txn_id/" . base64_encode($invoice_id).'/'.base64_encode($_SESSION['user_id']));
    } else {
        $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => $response['error']));
        redirectPage(SITE_URL . "membership-plans");
    }
}

$winTitle = "{LBL_ADHOC_INMAILS}- " . SITE_NM;

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
if (isset($_REQUEST['no_of_inmails']) && $_REQUEST['no_of_inmails'] != "" && $_REQUEST['action']=='adhoc_inmail_form') {
    $response = $objMembershipPlans->getAdhocInmailsDetails($_REQUEST['no_of_inmails']);
    $response = preg_replace('/\{([A-Z_]+)\}/', "$1", $response);
    echo json_encode($response);

    exit;
}
$pageContent = $objMembershipPlans->getAdhocInmailsDetails($no_of_inmails);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
