<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.airports-nct.php");

$module = 'airports-nct';

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
if($action == "delete") {

    $aWhere = array("id" => $id);
    $db->delete($table, $aWhere);

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', 'message' => "Airport has been deleted successfully."));
    exit;
}else if($action == "updateStatus") {
    $setVal = array('status' => $value );

    $user_airport_id = $db->select("tbl_user_airports", "*", array("airport_id" => $id))->result();
    $airport_details = $db->select("tbl_airport", "*", array("id" => $id))->result();

    //if ($user_airport_id['id'] > 0){
        if ($airport_details['airport_name'] != '') {
            
            $db->update($table, $setVal, array("id" => $id));
            if($value == 'a'){
                $status = 'y';
            }
            else{
                $status = 'n';
            }
            $setVal1 = array('isActive'=>$status);
            $db->update('tbl_user_airports', $setVal1, array("airport_id" => $id));
            if ($user_airport_id['id'] > 0) {
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
        }else{
            echo json_encode(array('type' => 'error', "Please enter airport name"));
        }
    //}
    exit;
}else if (isset($_POST['country'])) {
    $content = $selected = $state_option ='';
    $main_content = new Templater(DIR_ADMIN_TMPL . $module . "/ajax_select_state-nct.tpl.php");
    $content.= $main_content->parse();
    $search = array('%STATE_OPTION%');
    $mainObjectCity = new Airports($module, $id, NULL, $searchArray, $action);
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
}else if (isset($_POST['state'])) {
    $content = $selected = $city_option ='';
    $main_content = new Templater(DIR_ADMIN_TMPL . $module . "/ajax_select_city-nct.tpl.php");
    $content.= $main_content->parse();
    $search = array('%CITY_OPTION%');
    $mainObjectCity1 = new Airports($module, $id, NULL, $searchArray, $action);
    //City dropdown
    $getSelectBoxOption = $mainObjectCity1->getSelectBoxOption();
    $fields_search = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
    $qrySelState = $db->pdoQuery("SELECT * FROM tbl_city where StateID=" . filtering($_POST['state'], 'input', 'int') . " AND isActive='y' ORDER BY cityName")->results();

    foreach ($qrySelState as $fetchRes) {
        $fields_replace = array(
            filtering($fetchRes['CityId'], 'output', 'int'),
            $selected,
            filtering($fetchRes['cityName'])
        );
        $city_option.=str_replace($fields_search, $fields_replace, $getSelectBoxOption);
    }

    $replace = array(filtering($city_option, 'output', 'text'));
    $content = str_replace($search, $replace, $content);
    echo $content;
    exit;
}
// else if($action == "addAirports"){
//     extract($_POST);
//     //echo "<pre>";print_r($_POST);
//     $box = $_POST['str'];
    
//     foreach ($box as $x) {
//         echo "<pre>";print_r($x);
//         foreach ($x as $key => $value) {
//              //print_r($value);
//         }
//     }
//     exit();
// }
$mainObject = new Airports($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
