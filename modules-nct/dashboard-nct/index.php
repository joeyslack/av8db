<?php
define("DIR_URL", "/Users/jslack/working/av8db_code/");

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];
$request_id=0;
if(isset($requestURI[2]) && $requestURI[2]!=""){
    $request_id = $requestURI[2];
}

if($_REQUEST['action']=='people-you-may-know'){
    $_REQUEST['action']='getPeopleYouKnow';
}else if($_REQUEST['action']=='view-all-notification'){
    $_REQUEST['action']='getAllNotification';
    $_REQUEST['company_id']=$request_id;
}else if($_REQUEST['action']=='invitation'){
    $_REQUEST['action']='getInvitation';
}else if($_REQUEST['action']=='connection'){
    //print_r($requestURI);
    $_GET['action']='getConnection';
    $_REQUEST['action']='getConnection';
    $_REQUEST['user_id']=$request_id;
    $_GET['user_id']=$request_id;
}else if($_REQUEST['action']=='common-connection'){
    $_REQUEST['action']='getCommonConnection';
    $_REQUEST['user_id']=$request_id;
}else if($_REQUEST['action']=='following'){
    $_GET['action']='getFollowing';
    $_GET['user_id']=$request_id;
}else if($_REQUEST['action']=='follower'){
    $_GET['action']='getFollower';
    $_GET['user_id']=$request_id;
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.dashboard-nct.php");

$module = 'dashboard-nct';

$company_id = isset($_REQUEST['company_id'])?base64_decode($_REQUEST['company_id']):0;
$company_id = $company_id>0?filtering($company_id,'input','int',''):0;



$winTitle = LBL_HOME.' - ' . SITE_NM;

$styles = array();
$scripts = array();

$metas = get_meta_keyword_description(1);
if ($metas) {
    $final_description = filtering($metas['meta_description']);
    $final_keywords = filtering($metas['meta_keyword']);
} else {
    $final_description = filtering($description);
    $final_keywords = filtering($keywords);
}

$metaTag = getMetaTagsAll(array('description' => $final_description,
    'keywords' => $final_keywords,
    'og_title' => $winTitle
));

$objDashboard = new Dashboard($company_id);

if(isset($_POST['send_invitation'])){
    extract($_POST);
        
    $objDashboard1 = new Dashboard();

    $current_user_id= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $user_message = isset($user_message) ? $user_message : '';
    $user_email   = isset($user_email) ? $user_email : '';
    //$objCreateCompany = new Dashboard();
    $response = $objDashboard1->sendInvitationToUser($current_user_id,$user_message,$user_email);
    echo json_encode($response);
    exit;
}

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getCommonConnection') {

    $winTitle = '{LBL_COMMON_CONNECTIONS}';

    $user_id = decryptIt($_REQUEST['user_id']);
    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $page = $_REQUEST['page'];
    } else {
        $page = 1;
    }

    $pageContent = $objDashboard->getCommonConnectionsPageContent($user_id, $page);
     
} else if(isset($_GET['action']) && $_GET['action'] == 'getConnection') {

    $user_id = decryptIt($_GET['user_id']);
    
    /*if($user_id != $_SESSION['user_id']){
        redirectPage(SITE_URL);
    }*/

    $winTitle = '{LBL_CONN}';
    
    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $page = $_REQUEST['page'];
    } else {
        $page = 1;
    }

    $pageContent = $objDashboard->getConnectionsPageContent($user_id, $page);
    

    
     
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getInvitation') {

    $winTitle = '{LBL_SUB_HEADER_INVITATIONS}';

    //$user_id = decryptIt($_REQUEST['user_id']);
    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $page = $_REQUEST['page'];
    } else {
        $page = 1;
    }

    $pageContent = $objDashboard->getInvitationPageContent($page);
     
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getPeopleYouKnow') {

    $winTitle = '{LBL_SUB_HEADER_PEOPLE_YOU_MAY_KNOW}';
    
    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $page = $_REQUEST['page'];
    } else {
        $page = 1;
    }

    $pageContent = $objDashboard->getPeopleYouKnow($page, true);
     
}  else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getAllNotification') {

    $winTitle = LBL_COM_DET_NOTIFICATIONS;
    
    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $page = $_REQUEST['page'];
    } else {
        $page = 1;
    }

    $pageContent = $objDashboard->getAllNotificationPageContent($page, false);
     
}
//get following 19/9/18
else if(isset($_GET['action']) && $_GET['action'] == 'getFollowing') {

    $user_id = decryptIt($_GET['user_id']);
    if($user_id != $_SESSION['user_id']){
        redirectPage(SITE_URL);
    }

    $winTitle = '{FOLLOWING}';
    
    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $page = $_REQUEST['page'];
    } else {
        $page = 1;
    }

    $pageContent = $objDashboard->getFollowingPageContent($user_id, $page);
    

    
     
}  
//get follower 19/9/18
else if(isset($_GET['action']) && $_GET['action'] == 'getFollower') {

    $user_id = decryptIt($_GET['user_id']);
    if($user_id != $_SESSION['user_id']){
        redirectPage(SITE_URL);
    }

    $winTitle = '{FOLLOWERS}';
    
    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $page = $_REQUEST['page'];
    } else {
        $page = 1;
    }

    $pageContent = $objDashboard->getFollowerPageContent($user_id, $page);
    

    
     
}
 else {

    $winTitle = '{LBL_HOME} - ' . SITE_NM;
    $pageContent = $objDashboard->getDashboardPageContent();    
}

require_once(DIR_TMPL . "parsing-nct.tpl.php");
