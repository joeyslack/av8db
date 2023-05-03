<?php
require_once(DIR_URL."includes-nct/config-nct.php");

if( isset( $_SESSION['user_id'] ) || isset( $_SESSION['first_name'] ) || isset( $_SESSION['last_name'] ) ) {
    unset($_SESSION['user_id']);
    unset($_SESSION['first_name']);
    unset($_SESSION['last_name']);
}

$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => "{LBL_SUCCESSFULLY_LOGOUT}"));
redirectPage(SITE_URL);
?>