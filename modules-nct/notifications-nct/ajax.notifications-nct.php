<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];

if($_REQUEST['action']=='load-more-messages'){
    $_REQUEST['action']='loadMoreMessages';
    $_REQUEST['page']=$requestURI[3];
}else if($_REQUEST['action']=='load-more-notification'){
    $_REQUEST['action']='loadMoreNotification';
    $_REQUEST['type']=$requestURI[3];
    $_REQUEST['page']=$requestURI[5];
}else if($_REQUEST['action']=='mark_notifications_as_read'){
    $_REQUEST['action']='mark_read';
}

$reqAuth = true;
$allowedUserType = 'a';

require_once(DIR_URL."includes-nct/config-nct.php");
require_once(DIR_MOD . "home-nct/class.home-nct.php");
require_once(DIR_MOD . "profile-nct/class.profile-nct.php");
require_once("class.notifications-nct.php");

$module = 'notifications-nct';

$objNotifications = new Notifications();

if (isset($_REQUEST['action']) && 'get_unread_notifications' == $_REQUEST['action']) {
    $message = array();
    
    $total_notifications_count = $objNotifications->getUnreadNotificationsCount($_SESSION['user_id']);
    
    if ($total_notifications_count > 0) {
        $new_notifications_array = $objNotifications->getNotifications('header', 'unread');
        
        $message['operation_status'] = "success";
        
        
        $message['notifications'] = filtering($new_notifications_array['content'], 'output', 'text');
        $message['notifications_count'] = filtering($total_notifications_count, 'output', 'int');
        $message =preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $message);

        echo json_encode($message);
    }
} else if (isset($_REQUEST['action']) && 'mark_read' == $_REQUEST['action']) {
    
    $new_notifications = $objNotifications->mark_notifications_as_read();

    $message['operation_status'] = "success";
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($message);
    exit;
} else if (isset($_REQUEST['action']) && 'getRests' == $_REQUEST['action']) {
    
    $final_result = array();

    $page = filtering($_REQUEST['page'], 'input', 'int');
    $response = $objNotifications->getNotifications('page', 'regular', $page, true);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
} else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "loadMoreMessages" && isset($_REQUEST['page']) && $_REQUEST['page'] != "" ) {
    $page_no = filtering($_REQUEST['page'], 'input', 'int');
    
    $response = $objNotifications->getMessages($page_no);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
}  else if(isset($_REQUEST['action']) && $_REQUEST['action'] == "loadMoreNotification" && isset($_REQUEST['page']) && $_REQUEST['page'] != "" ) {
    $type = filtering($_REQUEST['type'], 'input');
    $page_no = filtering($_REQUEST['page'], 'input', 'int');
    
    $response = $objNotifications->getNotifications($type, $page_no);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    echo json_encode($response);
    exit;
}
?>