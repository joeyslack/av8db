<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.reported_feeds-nct.php");

$module = 'reported_feeds-nct';

chkPermission($module);
$Permission = chkModulePermission($module);

$table = 'tbl_feeds';

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

    //$aWhere = array("id" => $id);
    //$db->delete($table, $aWhere);

    $objPost->isFeedReported = 'n';
    //$objPost->updated_on = date('Y-m-d H:i:s');

    $objPostArray = (array) $objPost;
          
    $db->update($table, $objPostArray, array("id" => $id));
    
    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', 'message' => "Reported Feed is successfully deleted"));
    exit;
}else if($action == "updateStatus") {
	
    $setVal = array('status' => $value );
    $db->update($table, $setVal, array("id" => $id));

    $user_airport_id = $db->select("tbl_user_airports", "*", array("airport_id" => $id,"isActive" => 'n'))->result();
     
    if ($user_airport_id['id'] > 0 ){
        if($value == 'a')
            $status = 'y';
        else
            $status = 'n';
          
        $setVal1 = array('isActive'=>$status);
        $db->update('tbl_user_airports', $setVal1, array("airport_id" => $id));

        $user_info = $db->select("tbl_users", "*", array("id" => $user_airport_id['user_id']))->result();
        if($user_info['id'] > 0){
            $arrayCont['greetings'] = ucwords($user_info['first_name']);
            $arrayCont['link'] = "<a href='".SITE_URL."profile/"."' target='_blank'>Click here</a>";
            generateEmailTemplateSendEmail("airport_accepted", $arrayCont, $user_info['email_address']);
        }
    }
    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', "Airport has been " . ($value == 'a' ? 'activated ' : 'deactivated ') . "successfully"));
    exit;
}
$mainObject = new ReportedFeeds($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
