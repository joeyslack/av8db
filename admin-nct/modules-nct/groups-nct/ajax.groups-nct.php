<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.groups-nct.php");

$module = 'groups-nct';

chkPermission($module);
$Permission = chkModulePermission($module);

$table = 'tbl_groups';

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
if (isset($_GET['group_id']) && $_GET['group_id'] > 0) {
    $searchArray['group_id'] = filtering($_GET['group_id'], 'input', 'int');
}

if ($action == "updateStatus") {
    $setVal = array('status' => $value);
    $db->update($table, $setVal, array("id" => $id));

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', "Group has been " . ($value == 'a' ? 'activated ' : 'deactivated ') . "successfully"));
    exit;
} else if ($action == "delete") {
    $get_userdata=$db->pdoQuery("SELECT CONCAT(u.first_name,' ',u.last_name) as user_name,u.email_address,g.group_name from tbl_groups as g LEFT JOIN tbl_users as u ON u.id=g.user_id WHERE g.id = ? ",array($id))->result();
        
    $arrayCont['greetings'] = ucwords($get_userdata['user_name']);
    $arrayCont['group_name'] = ucwords($get_userdata['group_name']);
    generateEmailTemplateSendEmail("group_delete", $arrayCont, $get_userdata['email_address']);


    $aWhere = array("id" => $id);
    $db->delete($table, $aWhere);

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', 'message' => "Group has been deleted successfully."));
    exit;
} else if (isset($_REQUEST['group_name']) && $_REQUEST['group_name'] != '' && isset($_REQUEST['id'])) {
    $group_name = filtering($_REQUEST['group_name'], 'input');
    $id = filtering($_REQUEST['id'], 'input', 'int');

    $sql_query = "SELECT * FROM " . $table . " WHERE group_name = '" . $group_name . "' ";

    if ($id > 0) {
        $sql_query .= " AND id != '" . $id . "'";
    }

    $checkIfExists = $db->pdoQuery($sql_query)->result();

    if ($checkIfExists) {
        echo 'false';
        exit;
    } else {
        echo 'true';
        exit;
    }
}

$mainObject = new Groups($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
