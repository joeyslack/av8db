<?php
$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

$reqAuth = true;

$_SESSION['user_id'] = $_POST['sess_user_id'];
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.edit-job-nct.php");
$module = 'edit-job-nct';

if($_REQUEST['action']=='getDegreesForSuggestion'){
    $_REQUEST['action']='getDegrees';
    $_REQUEST['degree_name']=$_REQUEST['degree_name'];
    $_REQUEST['degree_id']=$_REQUEST['degree_id'];
}

if(isset($_POST['edit_job'])) {

	$user_id = filtering($_SESSION['user_id'], 'input', 'int');

	$objEditJob = new Edit_job();
	$response = $objEditJob->processJobUpdation($user_id);
	echo json_encode($response);
	exit;
} 
else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'saveJobData') {
	$affectedRows = $db->update('tbl_jobs', array($_REQUEST['name'] => filtering($_REQUEST['value'], 'input', 'text')), array("id" => $_REQUEST['pk']))->affectedRows();
	if($affectedRows) {
	}
} 

else if(isset($_REQUEST['action']) && 'addJobLocation' == $_REQUEST['action']) {
    $objEditJob = new Edit_job();
    $response = $objEditJob->InsertJobLocation($_REQUEST);

    $city = $_REQUEST['city1'] != '' ? $_REQUEST['city1'] : $_REQUEST['city2'];
    $state = $_REQUEST['state'];
    $country = $_REQUEST['country'];
    $location = $city . ", " . $state . ", " . $country;

    $response['location'] = $location;
    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSkills') {
	//_print($_REQUEST);exit;
     $objEditJob = new Edit_job();
    
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $skill_name = filtering($_REQUEST['skill_name'], 'input');
    $skill_id = str_replace("'",'',$_REQUEST['skill_id']);    
    $response = $objEditJob->getSkillsForSuggestion($user_id, $skill_name,$skill_id);
    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getLicensesEndorsements') {
//    _print($_REQUEST);exit;
     $objEditJob = new Edit_job();
    
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $licenses_endorsement_name = filtering($_REQUEST['licenses_endorsement_name'], 'input');
    $licenses_endorsement_id = str_replace("'",'',$_REQUEST['licenses_endorsement_id']);    
    $response = $objEditJob->getLicensesEndorsementsSuggestion($user_id, $licenses_endorsement_name,$licenses_endorsement_id);
    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getDegrees') {
    $objEditJob = new Edit_job();
    
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $degree_name = filtering($_REQUEST['degree_name'], 'input');
    $degree_id = str_replace("'",'',$_REQUEST['degree_id']);
    $response = $objEditJob->getDegreesForSuggestion($user_id, $degree_name,$degree_id);
    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSelectedLicenseName') {
     $objEditJob = new Edit_job();
    
    $selected_value = filtering($_REQUEST['selected_value'], 'input');

    $response = $objEditJob->getLicenseName($selected_value);
    echo json_encode($response);
    exit;
}else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addLicense') {
     $objEditJob = new Edit_job();
    //print_r($_POST['countries']);exit();
    $selected_value = $_POST['countries'];
    //print_r($selected_value);exit();
    $response = $objEditJob->insertSelectedLicense($selected_value);
    echo json_encode($response);
    exit;
}