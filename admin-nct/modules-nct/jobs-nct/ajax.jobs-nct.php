<?php

$content = '';
require_once(DIR_URL."includes-nct/config-nct.php");
if ($adminUserId == 0) {
    die('Invalid request');
}
include("class.jobs-nct.php");

$module = 'jobs-nct';

chkPermission($module);
$Permission = chkModulePermission($module);

$table = 'tbl_jobs';

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
if (isset($_GET['job_id']) && $_GET['job_id'] > 0) {
    $searchArray['job_id'] = filtering($_GET['job_id'], 'input', 'int');
}

//_print($searchArray);exit;

if ($action == "updateStatus") {
    $setVal = array('status' => $value);
    $db->update($table, $setVal, array("id" => $id));
    if($value=='d'){
        $get_userdata=$db->pdoQuery("SELECT CONCAT(u.first_name,' ',u.last_name) as user_name,u.email_address,j.job_title from tbl_jobs as j LEFT JOIN tbl_users as u ON u.id=j.user_id WHERE j.id = ? ",array($id))->result();
        $arrayCont['greetings'] = ucwords($get_userdata['user_name']);
        $arrayCont['jobtitle'] = ucwords($get_userdata['job_title']);
        generateEmailTemplateSendEmail("job_deactive", $arrayCont, $get_userdata['email_address']);

    }
    
    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', "Job has been " . ($value == 'a' ? 'activated ' : 'deactivated ') . "successfully"));
    exit;
} else if ($action == "delete") {

    $aWhere = array("id" => $id);
    $db->delete($table, $aWhere);

    $activity_array = array("id" => $id, "module" => $module, "activity" => 'status', "action" => $value);
    add_admin_activity($activity_array);
    echo json_encode(array('type' => 'success', 'message' => "Job has been deleted successfully."));
    exit;
} else if (isset($_REQUEST['job_title']) && $_REQUEST['job_title'] != '' && isset($_REQUEST['id'])) {
    $job_title = filtering($_REQUEST['job_title'], 'input');
    $id = filtering($_REQUEST['id'], 'input', 'int');

    echo 'true';
    exit;
} if (isset($_REQUEST['action']) && 'getstate' == $_REQUEST['action'] && isset($_REQUEST['country_id']) && '' != $_REQUEST['country_id']) {
    $objJobs = new Jobs($module, $id, NULL, $searchArray, $action);
    
    $country_id = filtering($_REQUEST['country_id'], 'input', 'int');
    
    echo $objJobs->getStateDD($country_id);
    exit;
} else if (isset($_REQUEST['action']) && 'getcity' == $_REQUEST['action'] && isset($_REQUEST['state_id']) && '' != $_REQUEST['state_id']) {
    $objJobs = new Jobs($module, $id, NULL, $searchArray, $action);
    
    $country_id = filtering($_REQUEST['country_id'], 'input', 'int');
    $state_id = filtering($_REQUEST['state_id'], 'input', 'int');
    
    echo $pageContent = $objJobs->getCityDD($country_id, $state_id);
    exit;
}

$mainObject = new Jobs($module, $id, NULL, $searchArray, $action);
extract($mainObject->data);
echo ($content);
exit;
