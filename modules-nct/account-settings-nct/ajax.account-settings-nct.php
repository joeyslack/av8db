<?php

$reqAuth = true;

require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.account-settings-nct.php");

$module = 'account-settings-nct';

if(isset($_POST['change_password'])) {
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    
    $objAccountsettings = new Account_settings();
    $response = $objAccountsettings->processChangePassword($user_id);
    echo json_encode($response);
    exit;
}

if (isset($_POST['action']) && 'update_account_settings' == $_POST['action'] && isset($_POST['column_name']) && '' != $_POST['column_name'] && isset($_POST['column_value']) && '' != $_POST['column_value']) {

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $objAccountsettings = new Account_settings();
    $response = $objAccountsettings->processUpdateEmailpreference($user_id);

    echo json_encode($response);
}

?>