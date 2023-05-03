<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='load-more-connection'){
    $_GET['action']='loadMoreConnection';
    $_GET['user_id']=$requestURI[3];
    $_GET['page']=$requestURI[5];
}else if($_REQUEST['action']=='getGroupMember'){
    $_POST['action']='getGroupMember';
}else if($_REQUEST['action']=='getNewsFeed'){
    $_POST['action']='getNewsFeed';
}else if($_REQUEST['action']=='getGroupInvitations'){
    $_REQUEST['group_id']=$requestURI[3];
}else if($_REQUEST['action']=='load-more-group-invitation'){
    $_REQUEST['action']='loadMoreInvitation';
    $_REQUEST['group_id']=$requestURI[3];
    $_REQUEST['page']=$requestURI[5];
}else if($_REQUEST['action']=='load-more-group-feeds'){
    $_GET['action']='loadMoreFeeds';
    $_GET['page']=$requestURI[3];
    $_GET['group_id']=$requestURI[4];
}else if($_REQUEST['action']=='load-more-member'){
    $_GET['action']='loadMoreMember';
    $_GET['group_id']=$requestURI[3];
    $_GET['page']=$requestURI[5];
}else if($_REQUEST['action']=='getGroupmember_load'){
    $_REQUEST['action']='loadMoreMember_new';
    $_REQUEST['group_id']=$requestURI[3];
    $_REQUEST['page']=$requestURI[5];
}else if($_REQUEST['action']=='reportGroupPost'){
    $_POST['action']='reportGroupPost';
}


$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.group-detail-nct.php");
$module = 'group-detail-nct'; 

//_print($_GET);exit;
if(isset($_POST['action']) && 'ask_to_join' == $_POST['action']) {
    $g_id = base64_decode($_POST['group_id']);
    $objGroupDetail = new Group_detail($g_id);
    $response = $objGroupDetail->askToJoin();
            

    echo json_encode($response);

    exit;
} else if(isset($_POST['action']) && 'join_group' == $_POST['action']) {
    $objGroupDetail = new Group_detail();
    $response = $objGroupDetail->joinGroup();
           

    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'leave_group' == $_POST['action']) {
    $objGroupDetail = new Group_detail();
    $response = $objGroupDetail->leaveGroup();
            
    echo json_encode($response);

    exit;
} else if(isset($_GET['action']) && $_GET['action'] == "loadMoreConnection" && isset($_GET['page']) && $_GET['page'] != "" && isset($_GET['user_id']) && $_GET['user_id'] != "") {
    $page_no = filtering(decryptIt($_GET['page']), 'input', 'int');
    $user_id = filtering(decryptIt($_GET['user_id']), 'input', 'int');
    $objGroupDetail = new Group_detail();
            

    echo $objGroupDetail->getConnections($page_no);
    exit;
} 

else if(isset($_GET['action']) && $_GET['action'] == "loadMoreMember" && isset($_GET['page']) && $_GET['page'] != "" && isset($_GET['group_id']) && $_GET['group_id'] != "") {
    $page_no = filtering(decryptIt($_GET['page']), 'input', 'int');
    $group_id = filtering(decryptIt($_GET['group_id']), 'input', 'int');
    $objGroupDetail = new Group_detail();
    echo $objGroupDetail->getMembers($page_no, $group_id);
    exit;
} else if(isset($_POST['action']) && $_POST['action'] == "getGroupMember" && isset($_POST['group_id']) && $_POST['group_id'] != "") {
    
    $group_id = filtering(($_POST['group_id']), 'input', 'int');
    $objGroupDetail = new Group_detail();

    $response =preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $objGroupDetail->getMembersContainer($group_id));

    echo json_encode($response);

    
    exit;
} else if(isset($_POST['action']) && $_POST['action'] == "getNewsFeed" ) {
    $group_id = decryptIt(filtering($_POST['group_id'], 'input', 'int'));
    $objGroupDetail = new Group_detail();

   
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $objGroupDetail->getNewsFeed($group_id));


    echo json_encode($response);


    exit;
}  else if(isset($_POST['send_invitation'])) {

    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $group_id = decryptIt(filtering($_POST['group_id'], 'input', 'int'));

    $invite_members_ids = isset($_POST['invite_members_name']) ? $_POST['invite_members_name'] : array();

    $objGroupDetail = new Group_detail();
                
    $response = $objGroupDetail->inviteMembers($invite_members_ids, $user_id, $group_id);
    

    echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "getGroupInvitations" ) {
    $objGroupDetail = new Group_detail();
    $group_id = filtering(decryptIt($_REQUEST['group_id']), 'input', 'int');
    $response = $objGroupDetail->getReceivedInvitationContainer($group_id);
                

    echo json_encode($response);
    exit;
}  else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "loadMoreInvitation" && isset($_REQUEST['page']) && $_REQUEST['page'] != "" && isset($_REQUEST['group_id']) && $_REQUEST['group_id'] != "") {
    
    $objGroupDetail = new Group_detail();
    $page_no = filtering($_REQUEST['page'], 'input', 'int');
    $group_id = filtering(decryptIt($_REQUEST['group_id']), 'input', 'int');
    
    $result = $objGroupDetail->getReceivedInvitation($page_no, $group_id);
    $response['status'] = true;

    $response['content'] = $result['received_invitation'];
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

           
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'accept_group_invitation' == $_POST['action']) {
    $group_id = decryptIt(filtering($_POST['group_id'], 'input', 'int'));
    $objGroupDetail = new Group_detail($group_id);
    $response = $objGroupDetail->accept_group_invitation();
    $response['member_count']=$objGroupDetail->getGroupMembers(decryptIt(filtering($_POST['group_id'], 'output', 'int')));
    $response['member_list']=$objGroupDetail->groupMemberList(decryptIt(filtering($_POST['group_id'], 'output', 'int')));        
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'reject_group_invitation' == $_POST['action']) {
    $objGroupDetail = new Group_detail();
    $response = $objGroupDetail->reject_group_invitation();
              
    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'remove_group_member' == $_POST['action']) {
    $objGroupDetail = new Group_detail();
    $response = $objGroupDetail->remove_group_member();
    $response['member_count']=$objGroupDetail->getGroupMembers(decryptIt(filtering($_POST['group_id'], 'output', 'int')));
     $response['member_list']=$objGroupDetail->groupMemberList(decryptIt(filtering($_POST['group_id'], 'output', 'int')));     


    echo json_encode($response);

    exit;
} else if($_REQUEST['action'] && $_REQUEST['action'] != "" && $_REQUEST['action'] == "loadMoreFeeds"){
    $response = array();
    $response['status'] = true;
    
    $group_id = filtering($_GET['group_id'], "input", "int");
    $page = filtering($_GET['page'], 'input', 'int');

    $objGroupDetail = new Group_detail();

    $response['content'] = $objGroupDetail->getFeeds($group_id,'web', $page);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    
    echo json_encode($response);
    exit;
   
}

 else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "loadMoreMember_new" && isset($_REQUEST['page']) && $_REQUEST['page'] != "" && isset($_REQUEST['group_id']) && $_REQUEST['group_id'] != "") {

    $page_no = filtering(decryptIt($_REQUEST['page']), 'input', 'int');
    $group_id = filtering(decryptIt($_REQUEST['group_id']), 'input', 'int');
    $objGroupDetail = new Group_detail();

    $result =$objGroupDetail->getMembers($page_no, $group_id);
    
    
    $response['status'] = true;

    $response['content'] = $result['member'];

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
}else if($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "reportGroupPost"){
    $response = array();
    $response['status'] = true;
    
    $group_id = filtering($_POST['group_id'], "input", "int");
    $user_id = filtering($_POST['user_id'], 'input', 'int');

    $objGroupDetail = new Group_detail();

    $response = $objGroupDetail->addGroupAsReported($group_id, $user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;   
}