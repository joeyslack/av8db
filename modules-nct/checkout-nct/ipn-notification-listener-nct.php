<?php

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once(DIR_FUN . "paypal_class.php");

if (!empty($_REQUEST)) {
    
    $message = "<pre>" . print_r($_REQUEST, TRUE);
   // sendEmailAddress("urmi.maniyar@ncrypted.com", "IPN Notification", $message);

    $payment_history_id = handlePaypalPaymentResponse($_REQUEST);
    /*$message = "<pre>" . print_r($payment_history_id, TRUE);
    sendEmailAddress("bhagwan.makwana@ncrypted.com", "IPN Notification 2", $message);*/
    if($payment_history_id) {
        $payment_history_id_encoded = encryptIt($payment_history_id);
        $_SESSION['toastr_message']=disMessage(array('type' => 'suc', 'var' => LBL_PAYMENT_COMPLETED));
        redirectPage(SITE_URL.'compose-message');
        //redirectPage(SITE_URL."payment-summary/".$payment_history_id_encoded);
    } else {
        $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => LBL_TRANSACTION_CANCELLED));
        redirectPage(SITE_URL);
    }
}
echo "<h1>2</h1>";
exit;