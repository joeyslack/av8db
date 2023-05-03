<?php
require_once("../config-nct.php");

$curr_date = date('Y-m-d H:i:s');

$qry = $db->pdoQuery("SELECT s.id,s.user_id as plan_user_id,s.expires_on from tbl_subscription_history as s INNER JOIN tbl_users as u ON s.user_id = u.id where s.expires_on<='".$curr_date."' and s.plan_type='r' AND s.isActive='y'");
if($qry->affectedRows()>0){
    $rows = $qry->results();
    
    foreach($rows as $row){
    	$update_arr = array();
	    if($row['expires_on']<=$curr_date){
	        $update_arr['isActive'] = 'n';
	    }
	    $db->update('tbl_subscription_history',$update_arr,array('id'=>$row['id']));
	   $db->update('tbl_users',array('isFerryPilot'=>'n'),array('id'=>$row['plan_user_id']));
	    $user_details = $db->select('tbl_users', array('id,first_name,last_name,email_address'), array('id' => $row['plan_user_id']))->result();
	   
	    $arrayCont['greetings'] = $user_details['first_name'].' '.$user_details['last_name'];
	    $arrayCont['referrallink'] = "Click <a href='" . SITE_URL . "membership-plans/"."' target='_blank'>here</a>";
	    $s = generateEmailTemplateSendEmail("cron_plan_expires", $arrayCont,'vidhi.bhatt@ncrypted.com');
    }    
}

?>