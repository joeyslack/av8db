<?php

class Payment_summary extends Home{

    function __construct() {
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
    }

    public function getPaymentInfos($payment_history) {
        $final_content = "";

        $payment_infos_tpl = new Templater(DIR_TMPL . $this->module . "/payment-infos-nct.tpl.php");
        $payment_infos_tpl_parsed = $payment_infos_tpl->parse();

        $fields = array(
            "%PAYMENT_STATUS%",
            "%INVOICE_ID%",
            "%TRANSACTION_ID%",
            "%AMOUNT%"
        );

        //$st = ((isset($_GET['st']) && $_GET['st'] != '')?$_GET['st']:'');
        $st = ((isset($_REQUEST['payment_status']) && $_REQUEST['payment_status'] != '')?$_REQUEST['payment_status']:'');

        $payment_status = filtering($payment_history['payment_status']);
        if ($payment_status == 'c' || $st == 'Completed') {
            $payment_status_text = "Completed";
        } else if ($payment_status == 'pr') {
            $payment_status_text = "Processing";
        } else if ($payment_status == 'p') {
            $payment_status_text = "Pending";
        } else if ($payment_status == 'd') {
            $payment_status_text = "Denied";
        }
        $tx = ((isset($_GET['tx']) && $_GET['tx'] != '') ? $_GET['tx'] : (isset($_REQUEST['tx']) && $_REQUEST['tx'] != '') ? $_REQUEST['tx'] : '-');
        $txn_id = filtering($payment_history['transaction_id']);
        $fields_replace = array(
            $payment_status_text,
            filtering($payment_history['invoice_id']),
            ( ( $payment_history['transaction_id'] ) ? $txn_id : $tx ),
            CURRENCY_SYMBOL . filtering($payment_history['total_price'], 'output', 'float')
        );

        $final_content = str_replace($fields, $fields_replace, $payment_infos_tpl_parsed);

        return $final_content;
    }

    public function getPageContent($ph_id) {
        
        $payment_history = $this->db->select("tbl_payment_history", "*", array("id" => $ph_id))->result();
        
       //$st = ((isset($_REQUEST['st']) && $_REQUEST['st'] != '')?$_REQUEST['st']:'');
       $payment_status = ((isset($_REQUEST['payment_status']) && $_REQUEST['payment_status'] != '')?$_REQUEST['payment_status']:'');
        if(isset($_REQUEST['payment_status']) && $_REQUEST['payment_status'] != ''){
            if($_REQUEST['payment_status']='Completed'){
                $main_content = new Templater(DIR_TMPL. $this->module . "/payment-successful-nct.tpl.php");
            } else {
                $main_content = new Templater(DIR_TMPL . $this->module . "/payment-failed-nct.tpl.php");
            }
        } else {
            if ($payment_history['payment_status'] == 'c') {
                $main_content = new Templater(DIR_TMPL . $this->module . "/payment-successful-nct.tpl.php");
            } else {
                $main_content = new Templater(DIR_TMPL . $this->module . "/payment-failed-nct.tpl.php");
            }
        }
        $main_content->set('payment_infos', $this->getPaymentInfos($payment_history));
        $final_result = $main_content->parse();
        $fields=array("%MEMBERSHIP_PLAN%");
        $fields_replace=array($this->getSubscribedMembershipPlan($_SESSION['user_id']));
        $final_content = str_replace($fields, $fields_replace, $final_result);
        return $final_content;
    }
}
?>