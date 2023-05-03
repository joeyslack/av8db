<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.publish-post-nct.php");
$module = 'publish-post-nct';
$objPublishPost = new Publish_post();

if(isset($_POST['action']) && $_POST['action'] == 'add_new_post') {
	$response = $objPublishPost->getRightSidebar();
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


	echo json_encode($response);
	exit;
} else if(isset($_POST['action']) && $_POST['action'] == 'edit_post') {

	$post_id = decryptIt(filtering($_POST['post_id'], 'output', 'int'));

	$response = $objPublishPost->getRightSidebar($post_id);
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


	echo json_encode($response);
	exit;
} else if(isset($_POST['action']) && $_POST['action'] == 'getPreviousPosts') {

	$currentPage = isset($_POST['currentPage']) ? $_POST['currentPage'] : 1;
	$user_id =  filtering($_SESSION['user_id'], 'input', 'int');
	$response = $objPublishPost->getLeftSidebar($user_id, $currentPage);
	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


	echo json_encode($response);
	exit;
} else if(isset($_POST['action']) && $_POST['action'] == 'removeImage') {

	$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
	$db->update('tbl_feeds',array('image_name'=>''),array('id'=>$post_id));
	exit;
}	