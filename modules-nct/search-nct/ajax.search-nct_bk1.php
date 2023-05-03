<?php

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.search-nct.php");
$module = 'search-nct';

$objSearch = new Search();


if (isset($_GET['entity']) && $_GET['entity'] != "") {
    $response = array();
    $response['status'] = true;
    $response['content'] = "";
    $response['pagination'] = "";
    $response['total_records'] = "";
    $response['next_available_records'] = "";

    $entity = filtering($_GET['entity'], "input");

    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);

    $user_id = filtering($_SESSION['user_id'], "input", "int");

    $objSearch = new Search();
    switch ($entity) {
        case "users" : {
                if($user_id > 0) {
                    $response = $objSearch->getUsers($user_id, $currentPage);
                } else {
                    $response = $objSearch->getUsersBeforeLogin($currentPage);    
                }
                break;
            }
        case "jobs" : {
            
                $response = $objSearch->getJobs($user_id, $currentPage);
                $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

                break;
            }
        case "companies" : {
                if($user_id > 0) {
                    $response = $objSearch->getCompanies($user_id, $currentPage);
                } else {
                    $response = $objSearch->getCompaniesBeforeLogin($currentPage);    
                }
                break;
            }
        case "groups" : {
                $response = $objSearch->getGroups($user_id, $currentPage);
                break;
            }
    }
        $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);



    echo json_encode($response);
    exit;
} else if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['page'] && $_GET['page'] != "") {
    $response = array();
    $response['status'] = true;
    
    $action = filtering($_GET['action'], 'input', 'int');
    $page = filtering($_GET['page'], 'input', 'int');
    
    switch ($action) {
        case 'loadMoreCompanies' : {
            $response['content'] = $objSearch->getCompaniesForFilter($page, "adv_");
            break;
        }
        case 'loadMoreIndustries' : {
            $response['content'] = $objSearch->getIndustries($page, "adv_");
            break;
        }
        case 'loadMoreGroups' : {
            $response['content'] = $objSearch->getGroupsForFilter($page, "adv_");
            break;
        }
        case 'loadMoreJobCategories' : {
            $response['content'] = $objSearch->getJobCategories($page, "adv_");
            break;
        }
        case 'loadMoreCompanySizes' : {
            $response['content'] = $objSearch->getCompanySizes($page, "adv_");
            break;
        }
    }
        $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


    
    echo json_encode($response);
    exit;
} else if (isset($_GET['action']) && ( 'getUsersBeforeLogin' == $_GET['action'])) { 
    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objSearch->getUsersBeforeLogin($currentPage);
    $response['status'] = true;
    $response['content'] = $result['content'];
    
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}
else if (isset($_GET['action']) && ( 'getCompaniesBeforeLogin' == $_GET['action'])) { 
    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objSearch->getCompaniesBeforeLogin($currentPage);
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['total_records']=$result['total_records'];

    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}
else if (isset($_GET['action']) && ( 'getUsers' == $_GET['action'])) { 
    $user_id = filtering($_SESSION['user_id'], "input", "int");

    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objSearch->getUsers($user_id,$currentPage);
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['total_records']=$result['total_records'];
    
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}
else if (isset($_GET['action']) && ( 'getJobs' == $_GET['action'])) { 
    $user_id = filtering($_SESSION['user_id'], "input", "int");

    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objSearch->getJobs($user_id,$currentPage);
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['total_records']=$result['total_records'];
    
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}
else if (isset($_GET['action']) && ( 'getCompanies' == $_GET['action'])) { 
    $user_id = filtering($_SESSION['user_id'], "input", "int");

    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objSearch->getCompanies($user_id,$currentPage);
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['total_records']=$result['total_records'];
    
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}
else if (isset($_GET['action']) && ( 'getGroups' == $_GET['action'])) { 
    $user_id = filtering($_SESSION['user_id'], "input", "int");

    $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
    $result = $objSearch->getGroups($user_id,$currentPage);
    $response['status'] = true;
    $response['content'] = $result['content'];
    $response['total_records']=$result['total_records'];
    
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);

    echo json_encode($response);
    exit;

}

