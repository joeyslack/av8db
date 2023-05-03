<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.company_approvals-nct.php");

$module = 'company_approvals-nct';

chkPermission($module);
$Permission = chkModulePermission($module);

$table = 'tbl_companies';

$action = isset($_GET["action"]) ? trim($_GET["action"]) : (isset($_POST["action"]) ? trim($_POST["action"]) : 'datagrid');
$id = isset($_GET["id"]) ? trim($_GET["id"]) : (isset($_POST["id"]) ? trim($_POST["id"]) : 0);
$value = isset($_POST["value"]) ? trim($_POST["value"]) : isset($_GET["value"]) ? trim($_GET["value"]) : '';
$page = isset($_POST['iDisplayStart']) ? intval($_POST['iDisplayStart']) : 0;
$rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
$sort = isset($_POST["iSortTitle_0"]) ? $_POST["iSortTitle_0"] : NULL;
$order = isset($_POST["sSortDir_0"]) ? $_POST["sSortDir_0"] : NULL;
$chr = isset($_POST["sSearch"]) ? $_POST["sSearch"] : NULL;
$sEcho = isset($_POST['sEcho']) ? $_POST['sEcho'] : 1;

extract($_GET);
$searchArray = array("page" => $page, "rows" => $rows, "sort" => $sort, "order" => $order, "offset" => $page, "chr" => $chr, 'sEcho' => $sEcho);
if($action == "delete") {

    $aWhere = array("id" => $id);
    $db->delete($table, $aWhere);

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', 'message' => "Airport has been deleted successfully."));
    exit;
}else if($action == "updateStatus") {
	
    $setVal = array('status' => $value );
    $db->update($table, $setVal, array("id" => $id));

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);

    $comp_data =$db->select("tbl_companies", "*", array("id" => $id))->result();
    //print_r($comp_data);exit();
    $arrayCont['greetings'] = $comp_data['company_name'];
    $arrayCont['links'] = "<a href='" . SITE_URL . "edit-company/".encryptIt($comp_data['id']) . "' target='_blank'>Click here</a>";
    if($value == 'a'){
        generateEmailTemplateSendEmail("verify_from_admin", $arrayCont, $comp_data['owner_email_address']);
    }else{
        generateEmailTemplateSendEmail("dactivated_company", $arrayCont, $comp_data['owner_email_address']);
    }

    echo json_encode(array('type' => 'success', "Business has been " . ($value == 'a' ? 'activated ' : 'deactivated ') . "successfully"));
    exit;
}else if($action == "aprroveRejectCompany"){
    extract($_POST);
    $response = array();
    
    $db->update($table , array("isAdminVerify" => $_POST['approval']) , array("id" => $_POST['com_id']));
    $response = array("status"=>"success");
    
    echo json_encode($response);
    exit;
}else if($action == "activeDeactivateCompany"){
    extract($_POST);
    $response = array();
    
    $db->update($table , array("adminActiveDeactive" => $_POST['approval']) , array("id" => $_POST['com_id']));

    $comp_data =$db->select("tbl_companies", "*", array("id" => $_POST['com_id']))->result();
    //print_r($comp_data);exit();
    $arrayCont['greetings'] = $comp_data['company_name'];
    $arrayCont['links'] = "<a href='" . SITE_URL . "edit-company/".encryptIt($comp_data['id']) . "' target='_blank'>Click here</a>";

    generateEmailTemplateSendEmail("verify_from_admin", $arrayCont, $comp_data['owner_email_address']);

    $response = array("status"=>"success");
    
    echo json_encode($response);
    exit;
}else if($action == "company_approvals"){
    extract($_POST);
    $response = array();
    //print_r($_POST);exit();
    $db->update('tbl_adminrole' ,array("isRequestReceive" => $_POST['on_off']) , array("id" => '124'));

    $response = array("status"=>"success");
    
    echo json_encode($response);
    exit;
}
$mainObject = new CompanyApprovals($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
