<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='load-more-company-feeds'){
    $_REQUEST['action']='loadMoreFeeds';
    $_GET['page']=$requestURI[3];
    $_GET['company_id']=$requestURI[4];
}else if($_REQUEST['action']=='load-more-follower'){
    $_REQUEST['action']='loadMoreFollower';
    $_REQUEST['company_id']=$requestURI[3];
    $_REQUEST['page']=$requestURI[5];
}else if($_REQUEST['action']=='load-more-jobs'){
    $_REQUEST['action']='loadMoreJob';
    $_REQUEST['company_id']=$requestURI[3];
    $_REQUEST['page']=$requestURI[5];
}else if($_REQUEST['action']=='getCompanyActivities' || $_REQUEST['action']=='getFollowerContent' || $_REQUEST['action']=='getJobContent' || $_REQUEST['action']=='getNotificationContent'){
    $_REQUEST['company_id']=$requestURI[3];
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.company-detail-nct.php");
$module = 'company-detail-nct'; 


if(isset($_REQUEST['action']) && $_REQUEST['action'] == "getCompanyActivities" ) {
    
    $company_id = filtering(decryptIt($_REQUEST['company_id']), 'input', 'int');
    $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);
    $response = $objCompanydetail->getHomePageContainer($company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "getFollowerContent" ) {
    $company_id = filtering(decryptIt($_REQUEST['company_id']), 'input', 'int');
    $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);

    $response = $objCompanydetail->getfollowersContainer($company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "getJobContent" ) {
    $company_id = filtering(decryptIt($_REQUEST['company_id']), 'input', 'int');
    $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);

    $response = $objCompanydetail->getJobsContainer($company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
}  else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "loadMoreFollower" && isset($_REQUEST['page']) && $_REQUEST['page'] != "" && isset($_REQUEST['company_id']) && $_REQUEST['company_id'] != "") {
    $page_no = filtering($_REQUEST['page'], 'input', 'int');
    $company_id = filtering(decryptIt($_REQUEST['company_id']), 'input', 'int');
    $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);
    $response = $objCompanydetail->getFollowers($page_no, $company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "loadMoreJob" && isset($_REQUEST['page']) && $_REQUEST['page'] != "" && isset($_REQUEST['company_id']) && $_REQUEST['company_id'] != "") {
    $page_no = filtering($_REQUEST['page'], 'input', 'int');
    $company_id = filtering(decryptIt($_REQUEST['company_id']), 'input', 'int');
    $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);
    $response = $objCompanydetail->getCompanyJobs($page_no, $company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'shareNewsFeed') {
    $company_id = decryptIt($_REQUEST['company_id']);

    $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);
    $response = $objCompanydetail->shareNewsFeed($company_id);
     
    echo json_encode($response);exit;

} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "getNotificationContent" ) {
    $response = NULL;
    $company_id = filtering(decryptIt($_REQUEST['company_id']), 'input', 'int');
        $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);

    $response = $objCompanydetail->getStatistics($company_id);
    $response .= $objCompanydetail->getNotificationslist($company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    //$response = preg_replace('/\{([A-Z_]+)\}/e', "$1", $response);

    echo json_encode($response);
    exit;
} else if(isset($_POST['action']) && 'remove_company_follower' == $_POST['action']) {
    $objCompanydetail = new Company_detail('',$_SESSION['user_id']);
    $response = $objCompanydetail->remove_company_follower();
    echo json_encode($response);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    exit;
} else if($_REQUEST['action'] && $_REQUEST['action'] != "" && $_REQUEST['action'] == "loadMoreFeeds"){
    $response = array();
    $response['status'] = true;
    
    $company_id = filtering($_GET['company_id'], "input", "int");
    $page = filtering($_GET['page'], 'input', 'int');

    $objCompanydetail = new Company_detail($company_id,$_SESSION['user_id']);

    $response['content'] = $objCompanydetail->getFeeds($company_id, $page);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;   
}else if($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "checkReview"){
    $response = array();
    $response['status'] = true;
    
    $company_id = filtering($_POST['company_id'], "input", "int");
    $user_id = filtering($_POST['user_id'], 'input', 'int');

    $objCompanydetail = new Company_detail($company_id,$user_id);

    $response['content'] = $objCompanydetail->getEditReviewModal($company_id, $user_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;   
}else if($_POST['action'] && $_POST['action'] != "" && $_POST['action'] == "reportCompanyReviews"){
    $response = array();
    $response['status'] = true;
    
    $company_id = filtering($_POST['company_id'], "input", "int");
    $review_id = filtering($_POST['review_id'], 'input', 'int');

    $objCompanydetail = new Company_detail($review_id,$company_id);

    $response = $objCompanydetail->addReviewAsReported($review_id, $company_id);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;   
}
