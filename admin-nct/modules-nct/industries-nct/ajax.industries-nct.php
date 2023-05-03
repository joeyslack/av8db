<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.industries-nct.php");

$module = 'industries-nct';

chkPermission($module);
$Permission = chkModulePermission($module);

$table = 'tbl_industries';

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
    $setVal = array('status' => $value );
    $db->update($table, $setVal, array("id" => $id));

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', "Business type has been " . ($value == 'a' ? 'activated ' : 'deactivated ') . "successfully"));
    exit;
} else if ($action == "delete") {
    
    $aWhere = array("id" => $id);
    $companyIndustry = getTableValue("tbl_companies","count(id)",array("company_industry_id"=>$id));

    if($companyIndustry<=0){
        $db->delete($table, $aWhere);

        $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
        add_admin_activity($activity_array);
        echo json_encode(array('type' => 'success', 'message' => "Business type has been deleted successfully."));
    }else{
        echo json_encode(array('type' => 'error', 'message' => "Business type is currently in use.You can't delete."));
    }
    exit;
    
} else if(isset ($_REQUEST['industry_name']) && $_REQUEST['industry_name'] != '' && isset ($_REQUEST['id'])) {
    $industry_name = filtering($_REQUEST['industry_name'], 'input');
    $id = filtering($_REQUEST['id'], 'input', 'int');
    
    $sql_query = "SELECT * FROM ".$table." WHERE industry_name = '".$industry_name."' ";
    
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
    
}

$mainObject = new Industries($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
