<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.city-nct.php");

$module = 'city-nct';
chkPermission($module);
$Permission = chkModulePermission($module);
$table = 'tbl_city';
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
    $setVal = array('isActive' => ($value == 'a' ? 'y' : 'n'));
    $db->update($table, $setVal, array("CityId" => $id));
    echo json_encode(array('type' => 'success', 'City ' . ($value == 'a' ? 'activated ' : 'deactivated ') . 'successfully'));
    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    exit;
} else if ($action == "delete") {

    //$check_if_selected_by_user = $db->select("tbl_users", "*", array("city_id" => $id))->result();

    // if ($check_if_selected_by_user) {
    //     echo json_encode(array('type' => 'error', 'message' => "This city has been selected by one of the users. So this can't be deleted."));
    //     exit;
    // } else {

        $aWhere = array("CityId" => $id);
        $affected_rows = $db->delete($table, $aWhere)->affectedRows();

        if ($affected_rows && $affected_rows > 0) {
            $response['type'] = 'success';
            $response['message'] = "City has been deleted successfully.";
        } else {
            $response['type'] = 'error';
            $response['message'] = "There seems to be an issue while deleting this city.";
        }
        echo json_encode($response);
        exit;
//    }
}
//getting the states of particular country
if (isset($_POST['country'])) {
    $content = $selected = $state_option = '';
    $main_content = new Templater(DIR_ADMIN_TMPL . $module . "/ajax_select_state-nct.tpl.php");
    $content.= $main_content->parse();
    $search = array('%STATE_OPTION%');
    $mainObjectCity = new City($module, $id, NULL, $searchArray, $action);
    //State dropdown
    $getSelectBoxOption = $mainObjectCity->getSelectBoxOption();
    $fields_search = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
    $qrySelState = $db->pdoQuery("SELECT * FROM tbl_state where CountryID=" . filtering($_POST['country'], 'input', 'int') . " AND isActive='y' ORDER BY stateName")->results();

    foreach ($qrySelState as $fetchRes) {
        $fields_replace = array(
            filtering($fetchRes['StateID'], 'output', 'int'),
            $selected,
            filtering($fetchRes['stateName'])
        );
        $state_option.=str_replace($fields_search, $fields_replace, $getSelectBoxOption);
    }
    $replace = array(filtering($state_option, 'output', 'text'));
    $content = str_replace($search, $replace, $content);
    echo $content;
    exit;
}

$mainObject = new City($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
