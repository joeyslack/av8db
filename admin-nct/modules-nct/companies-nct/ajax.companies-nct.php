<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.companies-nct.php");

$module = 'companies-nct';

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

if (isset($_GET['day']) && $_GET['day'] != '') {
    $searchArray['day'] = filtering($_GET['day'], 'input', 'int');
}
if (isset($_GET['month']) && $_GET['month'] != '') {
    $searchArray['month'] = filtering($_GET['month'], 'input', 'int');
}
if (isset($_GET['year']) && $_GET['year'] != '') {
    $searchArray['year'] = filtering($_GET['year'], 'input', 'int');
}

if (isset($_GET['company_id']) && $_GET['company_id'] > 0) {
    $searchArray['company_id'] = filtering($_GET['company_id'], 'input', 'int');
}

if ($action == "updateStatus") {
    $setVal = array('status' => $value );
    $db->update($table, $setVal, array("id" => $id));

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', "Business has been " . ($value == 'a' ? 'activated ' : 'deactivated ') . "successfully"));
    exit;
} else if ($action == "delete") {
    
    $get_userdata=$db->pdoQuery("SELECT CONCAT(u.first_name,' ',u.last_name) as user_name,u.email_address,c.company_name from tbl_companies as c LEFT JOIN tbl_users as u ON u.id=c.user_id WHERE c.id = ? ",array($id))->result();
        
    $arrayCont['greetings'] = ucwords($get_userdata['user_name']);
    $arrayCont['company_name'] = ucwords($get_userdata['company_name']);
    generateEmailTemplateSendEmail("company_delete", $arrayCont, $get_userdata['email_address']);

    $aWhere = array("id" => $id);
    $db->delete($table, $aWhere);

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', 'message' => "Business has been deleted successfully."));
    exit;
    
} else if(isset ($_REQUEST['company_name']) && $_REQUEST['company_name'] != '' && isset ($_REQUEST['id'])) {
    $company_name = filtering($_REQUEST['company_name'], 'input');
    $id = filtering($_REQUEST['id'], 'input', 'int');
    
    $sql_query = "SELECT * FROM ".$table." WHERE company_name = '".$company_name."' ";
    
    if($id > 0) {
        $sql_query .= " AND id != '".$id."'";
    }
    
    $checkIfExists = $db->pdoQuery($sql_query)->result();
    
    if($checkIfExists) {
        echo 'false';
        exit;
    } else {
        echo 'true';
        exit;
    }
    
}else if ($action == 'assign_company') {
    extract($_POST);
    //echo "<pre>";print_r($_POST);

    $com_data=$db->pdoQuery("SELECT CONCAT(u.first_name,' ',u.last_name) as user_name,u.email_address,c.company_name from tbl_companies as c LEFT JOIN tbl_users as u ON u.id=c.user_id WHERE c.id = ? ",array($companyid))->result();

    $new_user_data = $db->pdoQuery("SELECT CONCAT(u.first_name,' ',u.last_name) as user_name,u.email_address from tbl_users as u WHERE id = ?",array($user_id))->result();

    //echo "<pre>";print_r($new_user_data);exit();

    $setVal = array('user_id' => $user_id );
    $db->update($table, $setVal, array("id" => $companyid));

    $arrayCont['greetings'] = ucwords($com_data['user_name']);
    $arrayCont['company_name'] = ucwords($com_data['company_name']);
    generateEmailTemplateSendEmail("remove_company_from_user", $arrayCont,$com_data['email_address']);
    
    $arrayCont['greetings'] = ucwords($new_user_data['user_name']);
    $arrayCont['company_name'] = ucwords($com_data['company_name']);
    generateEmailTemplateSendEmail("reassign_company_to_user", $arrayCont,$new_user_data['email_address']);
    
    echo json_encode(array('type' => 'success', 'message' => "Business has been reassign successfully."));
    exit;
}

$mainObject = new Companies($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
