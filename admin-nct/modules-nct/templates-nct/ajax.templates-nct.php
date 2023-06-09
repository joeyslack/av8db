<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.templates-nct.php");

$module = 'templates-nct';
chkPermission($module);
$Permission = chkModulePermission($module);

$table = 'tbl_email_templates';
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
if ($action == "updateStatus") {
    $setVal = array('status' => ($value == 'a' ? 'y' : 'n'));
    $db->update($table, $setVal, array("id" => $id));
    echo json_encode(array('type' => 'success', 'Email template ' . ($value == 'a' ? 'activated ' : 'deactivated ') . 'successfully'));
    exit;
} else if ($action == "delete") {
    $setVal = array('status' => 't');
    $db->update($table, $setVal, array("id" => $id));
}
$mainObject = new Templates($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
