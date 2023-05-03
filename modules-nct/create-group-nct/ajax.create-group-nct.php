<?php

$_SESSION['user_id'] = $_POST['sess_user_id'];
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.create-group-nct.php");
$module = 'create-group-nct';


if(isset($_POST['create_group'])) {
	$user_id = filtering($_SESSION['user_id'], 'input', 'int');
	$objCreategroup = new Create_group();
	$response = $objCreategroup->processGroupCreation($user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

	echo json_encode($response);
	exit;
} else if(isset($_POST['action']) && 'getConnectionsForGropus' == $_POST['action']) {
    $objCreategroup = new Create_group('');
    $response = $objCreategroup->getConnectionsForGropus($_POST);
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'getConnectionBoxForGropus' == $_POST['action'] && isset ($_POST['user_id']) && $_POST['user_id'] != '') {
    $user_id = filtering($_POST['user_id'], 'input', 'int');
    
    $objCreategroup = new Create_group();
    $response = $objCreategroup->generateApproveMemeberBox($user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'deleteGroup' == $_POST['action']) {
    
    $group_id = decryptIt(filtering($_POST['group_id'], 'input', 'int'));
    
    $objCreategroup = new Create_group();
    $response = $objCreategroup->deleteGroup($group_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    

    echo json_encode($response);
    exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'deleteMember'){
    $member_id = decryptIt(filtering($_POST['id'], 'input', 'int'));
    $group_id = decryptIt(filtering($_POST['group_id'], 'input', 'int'));
    
    $objCreategroup = new Create_group();
    $response = $objCreategroup->deleteMember($member_id,$group_id);
    $response['status'] = true;
    $response['success'] = LBL_GROUP_MEMBER_REMOVED;
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
}else if(isset($_POST['action']) && 'getInvitationForGroups' == $_POST['action']) {
    
    $objCreategroup = new Create_group();
    $response = $objCreategroup->getInvitationId();
    echo json_encode($response);
    exit;
}
?>