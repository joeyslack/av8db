<?php


$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];


if($_REQUEST['action']=='send-message'){
    $_GET['action']='send_message';
    if(isset($requestURI[2]) && $requestURI[2]!=""){
        $_GET['conversation_id'] = $requestURI[2];    
    }
}else if($_REQUEST['action']=='ajax'){
    if($requestURI[2]=='messaging'){
        if(isset($requestURI[4]) && $requestURI[4]!=""){
            $_GET['conversation_id'] = $requestURI[4];    
        }
        if(isset($requestURI[6]) && $requestURI[6]!=""){
            $_GET['currentPage'] = $requestURI[6];    
        }
    }else if($requestURI[2]=='getConversations'){
        $_REQUEST['action']='getleft';
        if(isset($requestURI[4]) && $requestURI[4]!=""){
            $_REQUEST['currentPage'] = $requestURI[4];    
        }
    }
}else if($_REQUEST['action']=='deleteMessage'){
    $_POST['action']='deleteMessage';
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.messages-nct.php");
$module = 'messages-nct';

$objMessages = new Messages();
if (isset($_GET['conversation_id']) && $_GET['conversation_id'] != "" && isset($_GET['currentPage']) && $_GET['currentPage'] != "") {
    $response = array();
    $response['status'] = false;
    $conversation_id = filtering(decryptIt($_GET['conversation_id']), 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage'], 'input', 'int') : 1 );


    $messagesResponse = $objMessages->getConversationMessages($conversation_id, $user_id, $currentPage);
    if ($messagesResponse) {
        $response['status'] = true;
        $response['messages'] = $messagesResponse;
    } else {
        $response['error'] = "{ERROR_SOMETHING_WRONG}";
    }

    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == "deleteMessage" && isset($_POST['message_id'])) {
    $message_id = filtering(decryptIt($_POST['message_id']), 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $response = $objMessages->deleteMessages($message_id, $user_id);

    echo json_encode($response);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == "getConversation") {
    $response = array();
    $response['status'] = false;
    $conversation_id = filtering(decryptIt($_POST['conversation_id']), 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $messagesResponse = $objMessages->getSingleConversation($conversation_id, $user_id);
    if ($messagesResponse) {
        $response['status'] = true;
        $response['messages'] = $messagesResponse;
    } else {
        $response['error'] = "{ERROR_SOMETHING_WRONG}";
    }
        $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_GET['action']) && $_GET['action'] == "send_message" && isset($_GET['conversation_id']) && $_GET['conversation_id'] != "") {
    $conversation_id = filtering(decryptIt($_GET['conversation_id']), 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');

    $sendMessagesResponse = $objMessages->sendMessage($conversation_id, $user_id);



    echo json_encode($sendMessagesResponse);
    exit;
} else if (isset($_POST['action']) && $_POST['action'] == "getComposeMessageForm") {
    $response = array();
    $response['status'] = true;
    $response['form'] = $objMessages->getComposeMessageForm();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if (isset($_POST['send_message'])) {
    $response = $objMessages->submitComposeMessageForm();
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getConversations" && isset($_REQUEST['currentPage']) && $_REQUEST['currentPage'] != "") {
    
    $response = array();
    $response['status'] = false;
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $type = ( ( isset($_REQUEST['type']) ) ? filtering($_REQUEST['type'], 'input', 'string') : 'All' );
    $currentPage=( ( isset($_REQUEST['currentPage']) ) ? filtering($_REQUEST['currentPage'], 'input', 'int') : 1 );
    $messagesResponse = $objMessages->getConversations("", $currentPage, $type);
    if (isset($messagesResponse['conversation_id'])) {
        $ConversationDetail = $objMessages->getSingleConversation($messagesResponse['conversation_id'], $user_id);
        $response['status'] = true;
        $response['conversationDetail'] = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $ConversationDetail);
        $conversation_id = $messagesResponse['conversation_id'];
        $conversation_html = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $messagesResponse['conversation_html']);

        $response['messages'] = $messagesResponse;
    } else {
        
        $response['error'] = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $messagesResponse['html']);

    }
    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "getleft" && isset($_REQUEST['currentPage']) && $_REQUEST['currentPage'] != "") {
    
    $response = array();
    $response['status'] = false;
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $type = ( ( isset($_REQUEST['type']) ) ? filtering($_REQUEST['type'], 'input', 'string') : 'All' );
    $currentPage=( ( isset($_REQUEST['currentPage']) ) ? filtering($_REQUEST['currentPage'], 'input', 'int') : 1 );
    $response = $objMessages->getConversations("", $currentPage, $type);
    
    $response =preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == "deleteConversations") {

    $conversation_id = filtering(decryptIt($_POST['conversation_id']), 'input', 'int');
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $response = $objMessages->deleteConversation($conversation_id, $user_id);

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    

    echo json_encode($response);
    exit;
}
