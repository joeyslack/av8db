<?php


$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_REQUEST['action'] = $requestURI[1];


if($_REQUEST['action']=='compose-message'){
    $_GET['action']='composeMessage';
    if(isset($requestURI[2]) && $requestURI[2]!=""){
        $_GET['user_id'] = $requestURI[2];    
    }
}else if($_REQUEST['action']=='messaging'){
    if(isset($requestURI[3]) && $requestURI[3]!=""){
        $_GET['conversation_id'] = $requestURI[3];    
    }
}

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.messages-nct.php");
$module = 'messages-nct';

if (isset($_GET['action']) && $_GET['action'] == 'composeMessage') {
    $action = "composeMessage";
    $winTitle = "{LBL_COMPOSE_MSG} - " . SITE_NM;

    if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
        $user_id = filtering(decryptIt($_GET['user_id']), 'input', 'int');
        if ($user_id) {
            $checkIfExists = $db->select("tbl_users", "*", array("id" => $user_id))->result();
            if ($checkIfExists) {
                
            } else {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{LBL_USER_YOU_ARE_TRYING_SEND_MSG_DOESNT_EXIST}"));
                redirectPage(SITE_URL . "dashboard");
            }
        } else {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => "{ERROR_SOME_ISSUE_TRY_LATER}"));
            redirectPage(SITE_URL . "dashboard");
        }
    }
} else {
    $action = "";
    $winTitle = "{LBL_MESSAGES} - " . SITE_NM;
}

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

$objMessages = new Messages();
$pageContent = $objMessages->getMessagesPageContent($action);

require_once(DIR_TMPL . "parsing-nct.tpl.php");
