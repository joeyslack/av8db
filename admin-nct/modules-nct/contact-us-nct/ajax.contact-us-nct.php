<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");

if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.contact-us-nct.php");

$module = "contact-us-nct";
$table = "tbl_contact_us";

chkPermission($module);
$Permission = chkModulePermission($module);

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

if ($action == "delete") {

    $aWhere = array("id" => $id);
    $affected_rows = $db->delete($table, $aWhere)->affectedRows();

    if ($affected_rows && $affected_rows > 0) {
        echo json_encode(array('type' => 'success', 'message' => "Contact Information has been deleted successfully."));
        exit;
    } else {
        echo json_encode(array('type' => 'error', 'message' => "There seems to be an issue deleting Contact Information."));
        exit;
    }
}
$mainObject = new contactus($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
