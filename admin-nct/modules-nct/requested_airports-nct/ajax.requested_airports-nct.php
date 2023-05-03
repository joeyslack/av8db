<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.requested_airports-nct.php");

$module = 'requested_airports-nct';

chkPermission($module);
$Permission = chkModulePermission($module);

$table = 'tbl_airport';

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
if($action == "approveAddressType" && in_array('status',$Permission)) {
        
        $value = ($value == 'y') ? 'a' : 'r';
        $setVal = array('adminApproval'=>$value);
        //print_r($id);exit();
        $db->update($table,$setVal,array("id"=>$id));

        $user_airport_id = getTableValue("tbl_user_airports", "id", array("airport_id" => $id,"isActive" => 'n',"isAdminApprove" => 'n'));
        if($user_airport_id > 0){
        	$setVal1 = array('isAdminApprove'=>'y');
			$db->update('tbl_user_airports',$setVal1,array("airport_id"=>$id));
        }
      //  print_r($user_airport_id);exit();
        echo json_encode(array('type'=>'success','message'=>'Airport has been '.($value == 'a' ? ' approved ' : 'rejected ').' successfully'));
       
        exit;
    }
$mainObject = new RequestedLicensesEndorsements($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
