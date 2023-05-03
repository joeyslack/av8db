<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
include("class.contact-us-nct.php");
$module = "contact-us-nct";
$table = "tbl_contact_us_replies";
$styles = array(array("data-tables/DT_bootstrap.css", SITE_ADM_PLUGIN),
    array("bootstrap-switch/css/bootstrap-switch.min.css", SITE_ADM_PLUGIN));

$scripts = array("core/datatable.js",
    array("data-tables/jquery.dataTables.js", SITE_ADM_PLUGIN),
    array("data-tables/DT_bootstrap.js", SITE_ADM_PLUGIN),
    array("bootstrap-switch/js/bootstrap-switch.min.js", SITE_ADM_PLUGIN));

chkPermission($module);
$Permission = chkModulePermission($module);
$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    "author" => SITE_NM));

$id = isset($_GET["id"]) ? (int) trim($_GET["id"]) : 0;
$postType = isset($_POST["type"]) ? trim($_POST["type"]) : '';
$type = isset($_GET["type"]) ? trim($_GET["type"]) : $postType;

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Contact Us';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);
if (isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    extract($_POST);
    
    $objPost->message = isset($message) ? filtering($message, 'input') : '';
    $objPost->replied_on = date("Y-m-d H:i:s");

    if ($objPost->message != "") {
            //if (in_array('add', $Permission)) {

                $qrySel = $db->select("tbl_contact_us", "*", array("id" => $id))->result();
                $fetchRes = $qrySel;
                $first_name = $fetchRes['first_name'];
                $last_name = $fetchRes['last_name'];
                $email_address = $fetchRes['email_address'];
                $subject = $fetchRes['subject'];
                
                $valArray = array(
                    "contact_us_id" => $id,
                    "message" => $objPost->message,
                    "replied_on" => $objPost->replied_on
                );
                $id = $db->insert("tbl_contact_us_replies", $valArray)->getLastInsertId();
                
                if($id) {
                    //echo 'test';exit;
                    $arrayCont = array();
                    $arrayCont['subject'] = $subject;
                    $arrayCont['greetings'] = $first_name . " " . $last_name;
                    $arrayCont['reply'] = $objPost->message;

                    generateEmailTemplateSendEmail("contact_us_reply_from_admin", $arrayCont, $email_address);
                    
                    $activity_array = array("id" => $id, "module" => $module, "activity" => 'add');
                    add_admin_activity($activity_array);
                    $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'Message sent successfully'));
                } else {
                    $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'There seems to be some issue while replying the user.'));
                }
                
            //} 
            /*else {
                $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'NoPermission'));
            }*/
        
        redirectPage(SITE_ADM_MOD . $module);
    } else {
        $toastr_message = array('type' => 'err', 'var' => 'fillAllvalues');
    }
}

$objContactUs = new contactus($module, $id, NULL);
$pageContent = $objContactUs->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");
