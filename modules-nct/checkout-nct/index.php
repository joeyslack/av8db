<?php
$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

if(!empty($_REQUEST['userId']) && $_REQUEST['userId'] == ''){
    $reqAuth = true;
}
if ($requestURI[4] != '') {
    $_SESSION['user_id'] = base64_decode($requestURI[4]);
    $ph_id_encrypted = $_GET['ph_id'] = $requestURI[3];
}

require_once(DIR_URL."includes-nct/config-nct.php");
// require_once("../../includes-nct/config-nct.php");

require_once(DIR_FUN . "paypal_class.php");
$p = new paypal_class();

$p->admin_mail = ADMIN_EMAIL;
$ph_id_encrypted = $_GET['ph_id'];
$ph_id = base64_decode($ph_id_encrypted);
$query = "SELECT ph.*, tp.plan_type, tp.plan_name_".$lId." as plan_name, tp.plan_duration, tp.no_of_inmails, tp.price 
                FROM tbl_payment_history ph 
                LEFT JOIN tbl_tariff_plans tp ON ph.plan_id = tp.id 
                WHERE ph.invoice_id = '" . $ph_id . "' ";

$payment_history_details = $db->pdoQuery($query)->result();
if ($payment_history_details) {
    $payment_id = filtering($payment_history_details['id'], 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));

    $invoice_id = filtering($payment_history_details['invoice_id']);
    $plan_type = filtering($payment_history_details['plan_type']);
    $item_number = filtering($payment_history_details['plan_id'], 'input', 'int');

    if ('ah' == $plan_type) {
        $quantity = filtering($payment_history_details['quantity'], 'input', 'int');
        $package_description = $quantity . " " . filtering($payment_history_details['plan_name']) . " for " . filtering($payment_history_details['plan_duration']) . " Month(s)";
        $amount = filtering($payment_history_details['unit_price'], 'output', 'float');
    } else {
        $package_description = filtering($payment_history_details['plan_name']) . " for " . filtering($payment_history_details['plan_duration']) . " Month(s)";
        $amount = filtering($payment_history_details['price'], 'output', 'float');
        $quantity = 1;
    }

    $return_url = SITE_URL.'payment-summary/'.encryptIt($payment_id);

    $url_paypal = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    $url_paypal.="?business=".urlencode('test1@ncrypted.com');
    $url_paypal.="&cmd=".urlencode('_xclick');
    // $url_paypal.="&item_name=".urlencode("Event Ticket Payment To Creator");
    // $url_paypal.="&item_number=".urlencode($id);  
    $url_paypal.="&custom=".$_SESSION['user_id'];
    // $url_paypal.="&amount=".urlencode($payableAmt);
    $url_paypal.="&currency_code=".urlencode(PAYPAL_CURRENCY_CODE);
    $url_paypal.="&handling=".urlencode('0');
    $url_paypal.="&rm=2";
    $url_paypal.="&return=".$return_url;
    $url_paypal.="&cancel_return=".urlencode(CANCEL_RETURN_URL);
    $url_paypal.="&notify_url=".urlencode(NOTIFY_URL);
    $url_paypal.="&invoice=".$invoice_id;
    $url_paypal.="&item_name=".$package_description;
    $url_paypal.="&item_number=".$item_number;
    $url_paypal.="&quantity=".$quantity;
    $url_paypal.="&amount=".$amount;
    redirectPage($url_paypal);

    /*$p->add_field('business', 'test1@ncrypted.com'); // Call the facilitator eaccount
    $p->add_field('cmd', '_cart');
    $p->add_field('upload', '1');
    $p->add_field('return', $return_url);
    $p->add_field('cancel_return', CANCEL_RETURN_URL);
    $p->add_field('notify_url', NOTIFY_URL);
    $p->add_field('rm', 2);
    $p->add_field('bn', PAYPAL_BN_CODE);
    $p->add_field('currency_code', PAYPAL_CURRENCY_CODE);
    $p->add_field('invoice', $invoice_id);
    $counter = 1;

    $p->add_field('item_name_1', $package_description);
    $p->add_field('item_number_1', $item_number);
    $p->add_field('quantity_1', $quantity);
    $p->add_field('amount_1', $amount);

    $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));

    $p->add_field('email', $email_address);
    $p->submit_paypal_post();*/

} else {
    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_PAYPAL_PAYMENT));
    redirectPage(SITE_URL . "membership-plans");
}