<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.subscribers-nct.php");

$module = 'subscribers-nct';
chkPermission($module);
$Permission = chkModulePermission($module);
$table = 'tbl_subscribers';
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
    $setVal = array('status' => ($value == 'a' ? 'a' : 'd'));
    $db->update($table, $setVal, array("id" => $id));
    echo json_encode(array('type' => 'success', 'Subscribed User ' . ($value == 'a' ? 'activated ' : 'deactivated ') . 'successfully'));
    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    exit;
} else if ($action == "delete") {
    $aWhere = array("id" => $id);
    //mailchimp code 28-12-2020
    $semail = getTableValue('tbl_subscribers','email',array('id'=>$id)); 
    $email = $semail;
    $list_id = MAILCHIMP_LIST_ID;
    $api_key = MAILCHIMP_API_KEY;
     
    $data_center = substr($api_key,strpos($api_key,'-')+1);
     
    $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members/'. md5(strtolower($email));
     
    try {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    } catch(Exception $e) {
        echo $e->getMessage();
    }
    //$db->delete($table, $aWhere);
    $affected_rows = $db->delete($table, $aWhere)->affectedRows();

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'delete');
    add_admin_activity($activity_array);

    if ($affected_rows && $affected_rows > 0) {
        echo json_encode(array('type' => 'success', 'message' => "Subscribed User has been deleted successfully."));
        exit;
    } else {
        echo json_encode(array('type' => 'error', 'message' => "There seems to be an issue deleting subscribed user."));
        exit;
    }
} 

$mainObject = new subscribedusers($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
