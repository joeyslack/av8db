<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.edit-feed-nct.php");
$module = 'edit-feed-nct';

if (isset($_POST['share_an_update'])) {
    /*echo "<pre>";
    print_r($_POST);
    _print_r($_FILES);
    die;*/
    $objeditfeed = new editFeed();
    $response = $objeditfeed->processPostUpdate();
    echo json_encode($response);
    $response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);
    exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'remove_image') {

	$post_id = isset($_POST['feedid']) ? $_POST['feedid'] : 0;
    $image=$db->select('tbl_feeds','image_name',array('id'=>$post_id))->result();
    
    unlink(DIR_UPD_FEEDS . $image['image_name']);
    unlink(DIR_UPD_FEEDS .'th1_'. $image['image_name']);


	$db->update('tbl_feeds',array('image_name'=>''),array('id'=>$post_id));
	exit;
}else if(isset($_POST['action']) && $_POST['action'] == 'remove_video') {

	$post_id = isset($_POST['feedid']) ? $_POST['feedid'] : 0;
	$db->update('tbl_feeds',array('video_code'=>''),array('id'=>$post_id));
	exit;
}
