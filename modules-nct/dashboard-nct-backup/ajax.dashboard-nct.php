<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

//echo "<pre>";print_r($requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='reportFeedPost'){
    $_POST['action']='reportFeedPost';
    $_POST['feed_id']= $requestURI[2];
    $_POST['user_id']= $requestURI[3];
}else if($_REQUEST['action']=='like-unlike'){
    $_POST['action']='like_unlike';
}else if($_REQUEST['action']=='post-comment'){
    $_POST['action']='postComment';
}else if($_REQUEST['action']=='edit_comment'){
    $_POST['action']='edit_comment';
    $_POST['comment_id']= $requestURI[2];
    $_POST['comment']= $requestURI[3];
}else if($_REQUEST['action']=='del_comment'){
    $_POST['action']='del_comment';
    $_POST['comment_id']= $requestURI[2];
    $_POST['feed_id']= $requestURI[3];
}else if($_REQUEST['action']=='getConnectionAjax'){
    $_REQUEST['action']='getConnection';
    $_REQUEST['user_id']= $requestURI[5];    
}else if($_REQUEST['action']=='getFollowingAjax'){
    $_REQUEST['action']='getFollowing';
    $_REQUEST['user_id']= $requestURI[5];
}else if($_REQUEST['action']=='getFollowerAjax'){
    $_REQUEST['action']='getFollower';
    $_REQUEST['user_id']= $requestURI[5];
}else if($_REQUEST['action']=='getPeopleYouKnowAjax'){
    $_REQUEST['action']='getPeopleYouKnow';
    $_GET['page']=$requestURI[3];
}else if($_REQUEST['action']=='post-an-update'){
    $_POST['action']='share_an_update';
}else if($_REQUEST['action']=='load-more-feeds'){
    $_REQUEST['action']='loadMoreFeeds';
    $_GET['page']=$requestURI[3];
}else if($_REQUEST['action']=='getLikers'){
    $_GET['action']='getLikers';
    $_GET['feed_id']=$requestURI[3];
    $_GET['currentPage']=$requestURI[5];
}else if($_REQUEST['action']=='getSharedBy'){
    $_GET['action']='getSharedBy';
    $_GET['feed_id']=$requestURI[3];
    $_GET['currentPage']=$requestURI[5];
}else if($_REQUEST['action']=='getComments'){
    $_GET['action']='getComments';
    $_GET['feed_id']=$requestURI[3];
    $_GET['currentPage']=$requestURI[5];
}else if($_REQUEST['action']=='getVisitors'){
    $_GET['action']='getVisitors';
    $_GET['currentPage']=$requestURI[3];
}else if(isset($requestURI[2]) && $requestURI[2]=='getPeopleYouKnow_load'){
    $_GET['action']='getPeopleYouKnow_load';
    $_GET['currentPage']=$requestURI[4];
}else if(isset($requestURI[2]) && $requestURI[2]=='getConnection_load'){
    $_GET['action']='getConnection_load';
    $_GET['currentPage']=$requestURI[4];
    $_GET['keyword']=$requestURI[5];
}else if(isset($requestURI[2]) && $requestURI[2]=='getInvitation_load'){
    $_REQUEST['type']='getInvitation_load';
    $_REQUEST['currentPage']=$requestURI[4];
    $_REQUEST['action']=$requestURI[5];
}else if(isset($requestURI[2]) && $requestURI[2]=='getFollowing_load'){
    $_REQUEST['action']='getFollowing_load';
    $_GET['currentPage']=$requestURI[4];
    $_GET['keyword']=$requestURI[5];
}else if(isset($requestURI[2]) && $requestURI[2]=='getFollower_load'){
    $_REQUEST['action']='getFollower_load';
    $_GET['currentPage']=$requestURI[4];
    $_GET['keyword']=$requestURI[5];
}else if(isset($requestURI[2]) && $requestURI[2]=='getCommonConnection_load'){
    $_REQUEST['action']='getCommonConnection_load';
    $_GET['currentPage']=$requestURI[4];
    $_REQUEST['user_id']=$requestURI[5];
}else if(isset($requestURI[2]) && $requestURI[2]=='getNotification_Load'){
    $_REQUEST['action']='getNotification_Load';
    $_GET['currentPage']=$requestURI[4];
}

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.dashboard-nct.php");
$module = 'dashboard-nct';
$objDashboard = new Dashboard();

if (isset($_POST['share_an_update'])) {
    $objDashboard = new Dashboard();
    $response = $objDashboard->processPostUpdate();
    echo json_encode($response);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    exit;
} else if (isset($_POST['share_an_update_popup'])) {
    $objDashboard = new Dashboard();
    $response = $objDashboard->processShareUpdate();
    echo json_encode($response);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    exit;
} else if (isset($_POST['share_update_from_group'])) {
    $objDashboard = new Dashboard();
    $response = $objDashboard->processPostUpdate("p", "g");
    echo json_encode($response);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'followCompany') {
    $company_id = decryptIt($_REQUEST['company_id']);

    $objDashboard = new Dashboard();
    $response = $objDashboard->followCompany($company_id);
    $response['follower_count'] = $objDashboard->getCompanyFollowers($company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addConnection') {
    $user_id = isset($_POST['orig_user_id']) ? $_POST['orig_user_id'] : decryptIt($_POST['user_id']);

    $objDashboard = new Dashboard();

    $response = $objDashboard->addConnection($user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getCommonConnection') {
    $user_id = ($_REQUEST['user_id']);
    $page = ($_REQUEST['page']);

    $objDashboard = new Dashboard();

    $response = $objDashboard->getCommonConnectionsPageContent($user_id, $page, true);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getConnection') {
    
    $user_id = ($_REQUEST['user_id'] != '')?$_REQUEST['user_id']:$_SESSION['user_id'];
    $page = ($_REQUEST['page']!='')?$_REQUEST['page']:1;

    $objDashboard = new Dashboard();
    $response = $objDashboard->getConnectionsPageContent($user_id, $page, true);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchConnection') {
    $user_id = ($_REQUEST['user_id']);
    $keyword = ($_REQUEST['keyword']);
    $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

    $objDashboard = new Dashboard();
    $response = $objDashboard->getConnectionsPageContent($user_id, $page, true, $keyword);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_POST['publish_post'])) {
    $objDashboard = new Dashboard();
    $response = $objDashboard->processPostUpdate("p", "a");
    $response['post_type']='p';
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_POST['save_post'])) {
    $objDashboard = new Dashboard();
    $response = $objDashboard->processPostUpdate("p", "a", "s");
    $response['post_type']='s';

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'remove_saved_post') {
    $objDashboard = new Dashboard();
    $post_id = decryptIt($_POST['post_id']);
    $response = $objDashboard->deleteSavedPost($post_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_GET['action']) && ( $_GET['action'] == 'getLikers' || $_GET['action'] == 'getSharedBy' )) {
    $feed_id = filtering(decryptIt($_GET['feed_id']), "input", "int");
    $currentPage = filtering($_GET['currentPage'], "input", "int");
    $action = filtering($_GET['action'], "input");

    $objDashboard = new Dashboard();

    $response = $objDashboard->getLikersSharedBy($action, $feed_id, $currentPage);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_GET['action']) && $_GET['action'] == 'getComments') {
    $feed_id = decryptIt($_GET['feed_id']);
    $currentPage = filtering($_GET['currentPage'], 'input', 'int');

    $response = array();
    $response['status'] = true;
    $response['comments_count'] = getCommentsCount($feed_id);

    $commentsResponse = getComments($feed_id, $currentPage);
    $response['comments_html'] = $commentsResponse['comments'];
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getPendingInvitations') {

    $objDashboard = new Dashboard();
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $response = $objDashboard->getInvitationPageContent($page, true, "getPendingInvitations");
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'getSentInvitations') {

    $objDashboard = new Dashboard();
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $response = $objDashboard->getInvitationPageContent($page, true, "getSentInvitations");
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'approve_invitation') {
    $objDashboard = new Dashboard();
    $user_id = isset($_POST['user_id']) ? decryptIt($_POST['user_id']) : 0;
    $session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $response = $objDashboard->approveConnection($user_id, $session_user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'deny_invitation') {
    $objDashboard = new Dashboard();

    $user_id = isset($_POST['user_id']) ? decryptIt($_POST['user_id']) : 0;
    $session_user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $response = $objDashboard->denyInvitation($user_id, $session_user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == 'cancel_request') {
    $objDashboard = new Dashboard();

    $user_id = isset($_POST['user_id']) ? decryptIt($_POST['user_id']) : 0;
    $session_user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $response = $objDashboard->cancelRequest($user_id, $session_user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'approveConnection') {
    $user_id = isset($_REQUEST['user_id']) ? decryptIt($_REQUEST['user_id']) : 0;

    $objDashboard = new Dashboard();
    $session_user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $response = $objDashboard->approveConnection($user_id,$session_user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'rejectConnection') {
    $user_id = isset($_REQUEST['user_id']) ? decryptIt($_REQUEST['user_id']) : 0;

    $objDashboard = new Dashboard();
    $response = $objDashboard->rejectConnection($user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);



    echo json_encode($response);
    exit;
} else if (isset($_POST['share_update_from_company'])) {
    $objDashboard = new Dashboard();
    $response = $objDashboard->processPostUpdate("p", "c");
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_GET['action']) && $_GET['action'] == 'getVisitors') {
    $currentPage = $_GET['currentPage'];

    $objDashboard = new Dashboard();

    $response = $objDashboard->getUserVisitors($currentPage);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getPeopleYouKnow') {
    $objDashboard = new Dashboard();
    $page = (isset($_REQUEST['page']) && $_REQUEST['page']!='')?$_REQUEST['page']:1;

    $response = $objDashboard->getPeopleYouKnow($page, true, true);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
}

else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchPeopleyoumayknow') {
    $user_id = ($_REQUEST['user_id']);
    $keyword = ($_REQUEST['keyword']);
    $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

    $objDashboard = new Dashboard();
    $response = $objDashboard->getPeopleYouKnow($page,true, true,'web',0,$keyword);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} 
 else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getNotification') {

    $page = filtering($_REQUEST['page'], 'output', 'int');
    $objDashboard = new Dashboard();
    $response = $objDashboard->getAllNotificationPageContent($page, true);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} else if ($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "like_unlike") {

    $feed_id = filtering(decryptIt($_POST['feed_id']), "input", "int");
    $user_id = filtering($_SESSION['user_id'], "input", "int");
    $objDashboard = new Dashboard();
    $response = $objDashboard->like_unlike($feed_id, $user_id);
    $response['like_count'] = getLikeCount($feed_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;





} else if ($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "postComment") {

    $feed_id = filtering(decryptIt($_POST['feed_id']), "input", "int");
    $user_id = filtering($_SESSION['user_id'], "input", "int");


    $objDashboard = new Dashboard();

    $response = $objDashboard->postComment($feed_id, $user_id);

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;



} else if($_REQUEST['action'] && $_REQUEST['action'] != "" && $_REQUEST['action'] == "loadMoreFeeds"){
    $response = array();
    $response['status'] = true;

    $user_id = filtering($_SESSION['user_id'], "input", "int");
    $page = filtering($_GET['page'], 'input', 'int');

    $objDashboard = new Dashboard();

    $response['content'] = $objDashboard->getFeeds($user_id, 'web',$page);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);



    echo json_encode($response);
    exit;

}else if (isset($_POST['action']) && $_POST['action'] == 'delete_post') {
    $objDashboard = new Dashboard();
    $feed_id = decryptIt($_POST['feed_id']);
    $response = $objDashboard->deletePost($feed_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if (isset($_POST['action']) && $_POST['action'] == 'publish_post_save') {
    $objDashboard = new Dashboard();
    $feed_id = decryptIt($_POST['feed_id']);
    $response = $objDashboard->publish_post_save($feed_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getFollowing') {
    $user_id = (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '')?$_REQUEST['user_id']:$_SESSION['user_id'];
    $page = (isset($_REQUEST['page']) && $_REQUEST['page']!='')?$_REQUEST['page']:1;

    $objDashboard = new Dashboard();
    $response = $objDashboard->getFollowingPageContent($user_id, $page, true);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchFollowing') {
    $user_id = ($_REQUEST['user_id']);
    $keyword = ($_REQUEST['keyword']);
    $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

    $objDashboard = new Dashboard();
    $response = $objDashboard->getFollowingPageContent($user_id, $page, true, $keyword);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getFollower') {
    $user_id = ($_REQUEST['user_id'] != '')?$_REQUEST['user_id']:$_SESSION['user_id'];
    $page = ($_REQUEST['page']!='')?$_REQUEST['page']:1;

    $objDashboard = new Dashboard();
    $response = $objDashboard->getFollowerPageContent($user_id, $page, true);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchFollower') {
    $user_id = ($_REQUEST['user_id']);
    $keyword = ($_REQUEST['keyword']);
    $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;

    $objDashboard = new Dashboard();
    $response = $objDashboard->getFollowerPageContent($user_id, $page, true, $keyword);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}else if ($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "del_comment") {
    $feed_id = filtering(decryptIt($_POST['feed_id']), "input", "int");

    $comment_id = filtering($_POST['comment_id'], "input", "int");
    $objDashboard = new Dashboard();

    $response = $objDashboard->del_comment($comment_id);
    $response['comments_count'] = getCommentsCount($feed_id);

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}
else if ($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "edit_comment") {

    $comment_id = filtering($_POST['comment_id'], "input", "int");
    $comment=filtering($_POST['comment'], "input", "int");
    $objDashboard = new Dashboard();
    // echo "<pre>";print_r($comment_id);
    // echo "<pre>";print_r($comment);exit();
    $response = $objDashboard->edit_comment($comment_id,$comment);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}

else if (isset($_GET['action']) && ( 'getPeopleYouKnow_load' == $_GET['action'])) {

    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objDashboard->getPeopleYouKnow($currentPage,true,true);
    $response['status'] = true;
    $response['content'] = $result;

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}
else if (isset($_GET['action']) && ( 'getConnection_load' == $_GET['action'])) {
    $user_id = ($_SESSION['user_id']);
    $keyword=((isset($_GET['keyword'])) ?filtering($_GET['keyword']):'');
    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objDashboard->getConnectionsPageContent($user_id,$currentPage,true,$keyword);
    $response['status'] = true;
    $response['content'] = $result;
   // $response['total_records']=$result['total_records'];

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getFollowing_load') {
    $user_id = ($_SESSION['user_id']);
    $page = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $keyword=((isset($_GET['keyword'])) ?filtering($_GET['keyword']):'');

    $objDashboard = new Dashboard();
    $result = $objDashboard->getFollowingPageContent($user_id, $page, true,$keyword);
    $response['status'] = true;
    $response['content'] = $result;
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getFollower_load') {
    $user_id = ($_SESSION['user_id']);
    $page =  ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $keyword=((isset($_GET['keyword'])) ?filtering($_GET['keyword']):'');

    $objDashboard = new Dashboard();
    $result = $objDashboard->getFollowerPageContent($user_id, $page, true,$keyword);
    $response['status'] = true;
    $response['content'] = $result;
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getPendingInvitations' && $_REQUEST['type'] == 'getInvitation_load') {

    $objDashboard = new Dashboard();
    $page = isset($_REQUEST['currentPage']) ? $_REQUEST['currentPage'] : 1;
    $result = $objDashboard->getInvitationPageContent($page, true, "getPendingInvitations");
     $response['status'] = true;
    $response['content'] = $result;

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getSentInvitations' && $_REQUEST['type'] == 'getInvitation_load') {
    $objDashboard = new Dashboard();
    $page = isset($_REQUEST['currentPage']) ? $_REQUEST['currentPage'] : 1;
    $result = $objDashboard->getInvitationPageContent($page, true, "getSentInvitations");
      $response['status'] = true;
    $response['content'] = $result;

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getCommonConnection_load') {
    $user_id = ($_REQUEST['user_id']);
    $page =  ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);

    $objDashboard = new Dashboard();

    $result = $objDashboard->getCommonConnectionsPageContent($user_id, $page, true);
    $response['status'] = true;
    $response['content'] = $result;

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
}  else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getNotification_Load') {

    $page = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $objDashboard = new Dashboard();
    $result = $objDashboard->getAllNotificationPageContent($page, true);
    $response['status'] = true;
    $response['content'] = $result;

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
}else if($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "reportFeedPost"){
    $response = array();
    $response['status'] = true;
    
    $feed_id = filtering($_REQUEST['feed_id'], "input", "int");
    $user_id = filtering($_REQUEST['user_id'], 'input', 'int');

    $objDashboard = new Dashboard();

    $response = $objDashboard->addFeedAsReported($feed_id, $user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;   
}