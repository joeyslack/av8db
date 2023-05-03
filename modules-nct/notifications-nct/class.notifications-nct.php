<?php
class Notifications extends Profile {
    function __construct() {
        foreach ($GLOBALS as $key => $values) {$this->$key = $values;}
    }
    public function getUnreadNotificationsCount($user_id) {
       
        
        if($user_id==''){
            $user_id=$_SESSION['user_id'];
        }
        $get_notifications_count = $this->db->pdoQuery("SELECT COUNT(*) as notifications_count FROM tbl_notifications WHERE user_id = ?  AND is_read = ? ",array($user_id,'n'))->result();
        $getcount=$get_notifications_count['notifications_count'];
        if($getcount==0){
            $getcount='';
        }
        return $getcount;

    }
    public function getUnreadMessagesCount($user_id) {
        if($user_id==''){
            $user_id = filtering($_SESSION['user_id'], 'input', 'int');

        }
        $query = "SELECT COUNT(m.id) as messages_count FROM tbl_messages m WHERE m.receiver_id = ? AND m.is_read = ? ";
        $get_messages_count = $this->db->pdoQuery($query,array($user_id,'n'))->result();
        $getcount=$get_messages_count['messages_count'];
        if($getcount==0){
            $getcount='';
        }
        return $getcount;
 
    }
    public function getConnectionRequestCount($user_id) {
        if($user_id==''){
                $user_id=$_SESSION['user_id'];
        }
        $invitations = $this->db->pdoQuery("SELECT *  FROM tbl_connections WHERE request_to = ? AND status = ?  ",array($user_id,'s' ))->results();
        //$invitations = $db->pdoQuery($query)->results();
        $getcount=0;
        if ($invitations) {
            for ($i = 0; $i < count($invitations); $i++) {
               $con_id= getTableValue("tbl_connections", "id", array("request_from" => $user_id,"request_to"=>$invitations[$i]['request_from'],"status"=>'a'));
               if($con_id==''){
                    $getcount=$getcount+1;
               }
                
            }
        }
        if($getcount==0){
            $getcount='';
        }
        return $getcount;

    }
    public function mark_notifications_as_read() {
        $get_notifications = $this->db->pdoQuery("SELECT * FROM tbl_notifications WHERE user_id = ?  AND is_read = ? ORDER BY id DESC",array(filtering($_SESSION['user_id'], 'input', 'int'),'n'))->results();
        if ($get_notifications) {
            foreach ($get_notifications as $notification) {
                $this->db->update("tbl_notifications", array("is_read" => 'y'), array("id" => $notification['id']));
            }
        }
    }
    public function getNotifications($enum_type = "general", $currentPage = 1, $notification_type = "regular") {
        $response = array();
        $content = $post_title = '';
        $limit = NO_OF_NOTIFICATIONS_PER_PAGE;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $totalRows = $showableRows = 0;
        $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $where_condition = "";
        if ($notification_type == 'unread') {
            $where_condition = " AND is_notified = 'n' AND is_notified = 'n' ";
        }
        $query = "SELECT n.* FROM tbl_notifications n WHERE n.user_id =  ? ". $where_condition . "  ORDER BY id DESC ";
        $query_with_limit = $query . " LIMIT 5 OFFSET 0";
        $getAllResults = $this->db->pdoQuery($query,array($user_id))->results();
        $totalRows = count($getAllResults);
        $getShowableResults = $this->db->pdoQuery($query_with_limit,array($user_id))->results();
        if ($getShowableResults) {
            $sql_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_notifications = $this->db->pdoQuery($sql_with_next_limit,array($user_id))->results();
            $next_available_records = count($next_notifications);
            $notification = new Templater(DIR_TMPL . $this->module . "/single-notification-header-nct.tpl.php");
            $notification_parsed = $notification->parse();
            $field = array('%NOTIFICATION_TEXT%','%NOTIFICATION_URL%','%NOTIFICATION_TITLE%','%NOTIFICATION_TIME%','%USER_IMG%');
            foreach ($getShowableResults as $notification) {
                $notification_date = $notification['added_on'];
                $time_ago = time_elapsed_string(strtotime($notification['added_on']));
                $type = $notification['type'];
                $action_by_user_id = filtering($notification['action_by_user_id'], 'input', 'int');
                $feed_id = filtering($notification['feed_id'], 'input', 'int');
                $group_id = filtering($notification['group_id'], 'input', 'int');
                $job_id = filtering($notification['job_id'], 'input', 'int');
                $company_id = filtering($notification['company_id'], 'input', 'int');
                if ($action_by_user_id > 0) {
                    $action_by_user_details = $this->db->select("tbl_users", "*", array("id" => $action_by_user_id))->result();
                    $action_by_user_name = filtering($action_by_user_details['first_name']) . " " . filtering($action_by_user_details['last_name']);
                }
                if ($feed_id > 0) {
                    $feed_details = $this->db->select("tbl_feeds", "*", array("id" => $feed_id))->result();
                    $post_title = filtering($feed_details['post_title']);
                }
                if ($group_id > 0) {
                    $group_details = $this->db->select("tbl_groups", "*", array("id" => $group_id))->result();
                    $group_name = filtering($group_details['group_name']);
                }
                if ($job_id > 0) {
                    $job_details = $this->db->select("tbl_jobs", "*", array("id" => $job_id))->result();
                    $job_title = filtering($job_details['job_title']);
                }
                if ($company_id > 0) {
                    $company_details=$this->db->select("tbl_companies", "*", array("id" => $company_id))->result();
                    $company_name = filtering($company_details['company_name']);
                }
                switch ($type) {
                    case 'cra' : {
                        $notification_text = LBL_COM_DET_YOUR_CONNECTION_REQUEST_ACCEPTED .' '. ucwords($action_by_user_name);
                        $notification_url = get_user_profile_url($action_by_user_id);
                        $notification_title = LBL_CONNECTION_REQUEST_ACCEPTED;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }

                    case 'like' : {
                        $notification_text = ucwords($action_by_user_name) .' '. LBL_LIKED_YOUR_POST .' '.ucwords($post_title);
                        $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                        $notification_title = ucwords($action_by_user_name) .' '. LBL_LIKED_YOUR_POST.' '.ucwords($post_title);
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'comment' : {
                        $notification_text = ucwords($action_by_user_name).' ' . LBL_COMMENTED_ON_YOUR_POST.' ' . ucwords($post_title);
                        $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                        $notification_title = ucwords($action_by_user_name).' ' . LBL_COMMENTED_ON_YOUR_POST.' ' . ucwords($post_title);
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'share' : {
                        $notification_text = ucwords($action_by_user_name).' ' . LBL_SHARED_YOUR_POST.' ' . ucwords($post_title);
                        $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                        $notification_title = ucwords($action_by_user_name).' ' . LBL_SHARED_YOUR_POST.' ' . ucwords($post_title);
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'rgji' : {
                        $notification_text = ucwords($action_by_user_name).' ' .  LBL_SENT_INVITATION.' ' . ucwords($group_name);
                        $notification_url = get_group_detail_url($group_id);
                        $notification_title = LBL_GROUP_JOINING_INVITATION;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'rgjr' : {
                        $notification_text = ucwords($action_by_user_name).' ' . LBL_SENT_INVITATION.' ' . ucwords($group_name);
                        $notification_url = get_group_detail_url($group_id)."?received-invitation";
                        $notification_title = LBL_GROUP_JOINING_INVITATION;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'gjra' : {
                        $notification_text = ucwords($action_by_user_name).' ' .  LBL_ACCEPTED_YOUR_REQUEST_FOR_JOINING_GROUP.' '  . ucwords($group_name);
                        $notification_url = get_group_detail_url($group_id);
                        $notification_title = LBL_GROUP_JOINING_REQUEST_ACCEPTED;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'aj' : {
                        $notification_text = ucwords($action_by_user_name).' ' .  LBL_APPLIED_ON_JOB.' ' . ucwords($job_title);
                        $notification_url = SITE_URL . "job-applicants/job/" . $job_id;
                        $notification_title = LBL_APPLIED_ON_JOB_CAPITAL;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'fc' : {
                        $notification_text = ucwords($action_by_user_name).' ' . LBL_FOLLOWED_COMPANY.' ' . ucwords($company_name);
                        $notification_url = get_company_detail_url($company_id).'?company-followers';
                        $notification_title =LBL_FOLLOW_COMPANY;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'nfg' : {
                        $notification_text = ucwords($action_by_user_name).' ' . LBL_POSTED_GROUP.' ' . ucwords($group_name);
                        $notification_url = get_group_detail_url($group_id).'?id='.encryptIt($feed_id).'#'.encryptIt($feed_id);
                        $notification_title = LBL_NEW_POST;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'ampg' : {
                        $notification_text = ucwords($action_by_user_name).' ' .LBL_ADDED_IN_GROUP.' '. ucwords($group_name);
                        $notification_url = get_group_detail_url($group_id);
                        $notification_title = LBL_ADDED_MEMBER;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }case 'fu' : {
                        $notification_text = ucwords($action_by_user_name).' '.LBL_FOLLOWED_USER;
                        $notification_url = get_user_profile_url($action_by_user_id);
                        $notification_title = FOLLOWING;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }case 'nfc' : {
                        $notification_text = ucwords($action_by_user_name).' ' . LBL_POSTED_COMPANY.' ' . ucwords($company_name);
                        $notification_url = get_company_detail_url($company_id);
                        $notification_title = LBL_NEW_POST;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                    case 'jpc' : {
                        $notification_text = ucwords($action_by_user_name).' ' .  LBL_POST_JOB_COMPANY.' ' . ucwords($company_name);
                        $notification_url = SITE_URL . "job/" . $job_id;
                        $notification_title = LBL_NEW_JOB;
                        // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                        break;
                    }
                }
                $user_img_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$action_by_user_id));
                $user_img_src = 'https://storage.googleapis.com/av8db/users-nct/'.$action_by_user_id.'/th2_'.$user_img_name;
                $is_image = getimagesize($user_img_src);
                if(!empty($is_image)){
                    $user_img = '<img src="'.$user_img_src.'" alt="'.$action_by_user_name.'">';
                }else{
                    $user_img = '<span class="profile-picture-character">'.ucfirst($action_by_user_details['first_name'][0]).'</span>';
                }
                $field_replace = array(
                    filtering($notification_text),
                    filtering($notification_url),
                    filtering($notification_title),
                    $time_ago,
                    $user_img
                );
                $content .= str_replace($field, $field_replace, $notification_parsed);
                $this->db->update("tbl_notifications", array("is_notified" => 'y'), array("id" => $notification['id']));
            }
            if ($next_available_records > 0) {
                $view_all_notification_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-notification-nct.tpl.php");
                $content .= str_replace(array("%VIEW_ALL_NOTIFICATION_URL%"), array(SITE_URL . "view-all-notification"), $view_all_notification_tpl->parse());
            }
        } else {
            $no_result_found = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $content = $no_result_found->parse();
        }
        $response['content'] = $content;
        return $response;
    }
    public function getMessages($currentPage = 1) {
        $response = array();
        $content = '';
        $limit = NO_OF_NOTIFICATIONS_PER_PAGE;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $totalRows = $showableRows = 0;
        $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $where_condition = " AND c.receiver_status = 'n' AND m.receiver_status = 'n' ";
        //$query = "SELECT * FROM ( SELECT m.* FROM tbl_messages m WHERE m.receiver_id = '" . $user_id . "' AND m.receiver_status = 'n' ORDER BY m.id DESC ) as table1 GROUP BY table1.conversation_id ORDER BY table1.id DESC  ";
        $query = "SELECT DISTINCT IF(m.`sender_id` != '" . $user_id . "',m.sender_id,m.`receiver_id`) AS userid, MAX(m.id) AS msgid,IF(m.`sender_id` != '" . $user_id . "',s.first_name,r.first_name) AS first_name,IF(m.`sender_id` != '" . $user_id . "',s.last_name,r.last_name) AS last_name FROM tbl_messages AS m INNER JOIN tbl_users AS s ON m.`sender_id` = s.`id` INNER JOIN tbl_users AS r ON m.`receiver_id` = r.`id` WHERE ( (m.`receiver_id` = ? AND m.receiver_status= ?)OR (m.`sender_id` = ? AND m.sender_status=?)) GROUP BY IF( m.`sender_id` != '" . $user_id . "', m.`sender_id`, m.`receiver_id` ) ORDER BY `msgid`  DESC";

        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $where_arr=array($user_id,'n',$user_id,'n');
        $getAllResults = $this->db->pdoQuery($query,$where_arr)->results();

        $totalRows = count($getAllResults);
        $getShowableResults = $this->db->pdoQuery($query_with_limit,$where_arr)->results();

        if ($getShowableResults) {
            $consersationFound = true;
            $sql_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_messages = $this->db->pdoQuery($sql_with_next_limit,$where_arr)->results();
            $next_available_records = count($next_messages);
            $message = new Templater(DIR_TMPL . $this->module . "/single-message-header-nct.tpl.php");
            $message_parsed = $message->parse();
            $field = array('%MESSAGE_TEXT%','%MESSAGE_URL%','%MESSAGE_TITLE%','%MESSAGE_TIME%','%USER_IMG%','%ACTIVE_CLASS%');
            foreach ($getShowableResults as $message) {
                $last_message = $this->db->select('tbl_messages',array('message,is_read','sent_on','conversation_id','sender_id','receiver_id'),array('id'=>$message['msgid']))->result();

                $conversation_id = $last_message['conversation_id'];
                $message_text = $last_message['message'];
                $message_text =  myTruncate($message_text,30);

                $is_read = $last_message['is_read'];
                $sent_on = $last_message['sent_on'];
                $sender_id = $last_message['sender_id'];
                $receiver_id = $last_message['receiver_id'];

                $time_ago = time_elapsed_string(strtotime($sent_on));
                $action_by_user_id = filtering($message['userid'], 'input', 'int');
                /*$message_date = $message['sent_on'];
                $time_ago = time_elapsed_string(strtotime($message['sent_on']));
                $action_by_user_id = filtering($message['sender_id'], 'input', 'int');*/
                if ($action_by_user_id > 0) {
                    $action_by_user_details = $this->db->select("tbl_users", "*", array("id" => $action_by_user_id))->result();
                    $action_by_user_name = filtering($action_by_user_details['first_name']) . " " . filtering($action_by_user_details['last_name']);
                }
               /* $last_message = $this->db->select('tbl_messages',array('message,is_read'),array('conversation_id'=>$message['conversation_id']),'order by id desc')->result();*/
                //$message_text = $last_message['message'];
                $message_url = SITE_URL . "messaging/thread/" . encryptIt(filtering($last_message['conversation_id'], 'input', 'int'));
                $message_title = $action_by_user_name;
                // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                $user_img_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$action_by_user_id));
                $user_img_src = 'https://storage.googleapis.com/av8db/users-nct/'.$action_by_user_id.'/th2_'.$user_img_name;
                $is_image = getimagesize($user_img_src);
                if(!empty($is_image)){
                    $user_img = '<img src="'.$user_img_src.'" alt="'.$action_by_user_name.'">';
                }else{
                    $user_img = '<span class="profile-picture-character">'.ucfirst($action_by_user_details['first_name'][0]).'</span>';
                }

                $field_replace = array(
                    filtering($message_text),
                    filtering($message_url),
                    ucwords(filtering($message_title)),
                    $time_ago,
                    $user_img,
                    $is_read == 'y' ? "" : "active",
                );
                $content .= str_replace($field, $field_replace, $message_parsed);
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-messages/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $content .= $load_more_li_tpl->parse();
            }
        } else {
            $consersationFound = false;
            $no_result_found = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $content .= $no_result_found->parse();
        }
        $view_all_notification_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-notification-nct.tpl.php");
        $view_all_messages = str_replace(array("%VIEW_ALL_NOTIFICATION_URL%"), array(SITE_URL . "messaging"), $view_all_notification_tpl->parse());
        $response['consersationFound'] = $consersationFound;
        $response['content'] = $content;
        $response['view_all_messages'] = $view_all_messages;
        return $response;
    }
    public function getConnectionRequest($currentPage = 1,$platform='web',$app_user_id=0 ,$invitation_pagination=true) {

        $response = array();
        $content = '';
        $limit = NO_OF_NOTIFICATIONS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;
        $app_limit = 10;
        $app_offset = ($currentPage - 1 ) * $app_limit;
        $totalRows = $showableRows = 0;
        if($platform=='app'){
            $user_id = filtering($app_user_id, 'input', 'int');
        } else {
            $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        }
        $getconnection=getConnections($user_id);
        $conn_qur='';
        if($getconnection != " "){
            $connections_array_imploded = implode(",", $getconnection);
            if($connections_array_imploded != ''){
                $conn_qur =" AND (c.request_from NOT IN ( " . $connections_array_imploded . " ))  ";

            }

        }
        //$totalRows = $getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name,ue.job_title,com.company_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_from) left join tbl_user_experiences as ue on(ue.user_id = u.id ) left join tbl_companies as com on (ue.company_id = com.id) where c.request_to = ? and c.status = ? $conn_qur group by c.id order by c.id desc ",array($user_id,'s'))->affectedRows();
        $totalRows = $getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_from) where c.request_to = ? and c.status = ? $conn_qur group by c.id order by c.id desc ",array($user_id,'s'))->affectedRows();
        $limit_query = ' LIMIT 0,5 ';
        if($platform == 'app'){
            if($invitation_pagination == true) {
                $limit_query = ' LIMIT '.$app_offset.','.$app_limit;
            } else {
                $limit_query = '';
            }
        }

        //$getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name,ue.job_title,com.company_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_from) left join tbl_user_experiences as ue on(ue.user_id = u.id ) left join tbl_companies as com on (ue.company_id = com.id) where c.request_to = ? and c.status = ? $conn_qur group by c.id order by c.id desc $limit_query",array($user_id,'s'))->results();
        $getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_from) where c.request_to = ? and c.status = ? $conn_qur group by c.id order by c.id desc $limit_query",array($user_id,'s'))->results();
        //$app_array = array();
        if ($getShowableResults) {
            $showableRows = count($getShowableResults);
            $message = new Templater(DIR_TMPL . $this->module . "/single-connection-header-nct.tpl.php");
            $message_parsed = $message->parse();
            $field = array('%MESSAGE_TEXT%','%MESSAGE_TITLE%','%USER_IMG%','%ENCRYPTED_USER_ID%');

            foreach ($getShowableResults as $fetch) {
                $action_by_user_id = filtering($fetch['request_from'], 'input', 'int');
                $action_by_user_name=filtering($fetch['first_name']) . " " . filtering($fetch['last_name']);
                $message_text = $action_by_user_name .' '. LBL_SENT_CONNECTION_REQUEST;
                $message_title = $action_by_user_name;
                // if($fetch['profile_picture_name']!='' && file_exists(DIR_UPD_USERS.$fetch['request_from'].'/th4_'.$fetch['profile_picture_name'])){
                //     $img_url = SITE_UPD_USERS.$fetch['request_from'].'/th4_'.$fetch['profile_picture_name'];
                //     $img_url = "<img src = '".$img_url."'>";
                // } else {
                //     $img_url = SITE_THEME_IMG."no-image.jpg";
                //     $img_url = "<img src = '".$img_url."'>";
                // }
                // $img_url =  getImageURL("user_profile_picture", $fetch['request_from'], "th4",$platform);
                //$img_url = "<img src = '".$img_url."'>";

                $user_img_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$action_by_user_id));
                $user_img_src = 'https://storage.googleapis.com/av8db/users-nct/'.$action_by_user_id.'/th4_'.$user_img_name;
                $is_image = getimagesize($user_img_src);
                if(!empty($is_image)){
                    $img_url = '<img src="'.$user_img_src.'" alt="'.$action_by_user_name.'">';
                }else{
                    $img_url = '<img src="https://storage.googleapis.com/av8db/no-image.jpg">';
                }

                $field_replace = array(
                    filtering($message_text),
                    filtering($message_title),
                    $img_url,
                    encryptIt($action_by_user_id),
                );
                $content .= str_replace($field, $field_replace, $message_parsed);

                // $job_title = filtering($fetch['job_title']);
                // $company_name = filtering($fetch['company_name']);
                // $tagline = $job_title;
                // $tagline .= ($tagline != '' ? " at " : '').$company_name;

                if($platform == 'app'){
                    $from_connections_query=$this->db->pdoQuery('select group_concat(id) as from_ids from tbl_connections where status = ? and (request_from = ? or request_to = ?)',array('a',$fetch['request_from'],$fetch['request_from']))->result();
                    $to_connections_query=$this->db->pdoQuery('select group_concat(id) as to_ids from tbl_connections where status = ? and (request_from = ? or request_to = ?)',array('a',$fetch['request_to'],$fetch['request_to']))->result();
                    $from_connections = explode(',', $from_connections_query['from_ids']);
                    $to_connections = explode(',', $to_connections_query['to_ids']);
                    /*new common connection code*/
                    $common_connection_array = getCommonConnections($fetch['request_to'], $fetch['request_from']);
                    $common_connection_count = count($common_connection_array);
                    /*new common connection code*/
                    $getstatus = getTableValue("tbl_follower", "status", array("follower_form" =>$app_user_id,'follower_to'=>$action_by_user_id));

                    $invitationid = $fetch['id'];
                    $userid = $action_by_user_id;
                    $username = $action_by_user_name;
                    $userimg = $img_url;
                    $tagline = $tagline;
                    $mutual_connection = count(array_intersect($from_connections, $to_connections));
                    $follow_status=$getstatus;
                    $app_array[] = array('invitationid'=>$invitationid,'userid'=>$userid,'username'=>$username,'userimg'=>$userimg,'tagline'=>$tagline,'mutual_connection'=>$common_connection_count,'follow_status'=>$follow_status);
                }
            }
        } else {
            $no_result_found = new Templater(DIR_TMPL . $this->module . "/no-connection-requests-nct.tpl.php");
            $content = $no_result_found->parse();
        }
        if($platform == 'app'){

            $response['invitation'] = (!empty($app_array)?$app_array:array());
            $page_data = getPagerData($totalRows, $app_limit,$currentPage);
            $response['invitation_pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);

        } else {
            $response['content'] = $content;
        }
        return $response;
    }
    public function getSentRequest($currentPage = 1,$platform='web',$app_user_id=0 ,$invitation_pagination=true) {

        $response = array();
        $content = '';
        $limit = NO_OF_NOTIFICATIONS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;
        $app_limit = 10;
        $app_offset = ($currentPage - 1 ) * $app_limit;
        $totalRows = $showableRows = 0;
        if($platform=='app'){
            $user_id = filtering($app_user_id, 'input', 'int');
        } else {
            $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        }
        $getconnection=getConnections($user_id);
        $conn_qur='';
        if($getconnection != " "){
            $connections_array_imploded = implode(",", $getconnection);
            if($connections_array_imploded != ''){
                $conn_qur =" AND (c.request_to NOT IN ( " . $connections_array_imploded . " ))  ";

            }

        }
        //$totalRows = $getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name,ue.job_title,com.company_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_to) left join tbl_user_experiences as ue on(ue.user_id = u.id ) left join tbl_companies as com on (ue.company_id = com.id) where c.request_from = ? and c.status = ? $conn_qur group by c.id order by c.id desc ",array($user_id,'s'))->affectedRows();
        $totalRows = $getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_to) where c.request_from = ? and c.status = ? $conn_qur group by c.id order by c.id desc ",array($user_id,'s'))->affectedRows();
        $limit_query = ' LIMIT 0,5 ';
        if($platform == 'app'){
            if($invitation_pagination == true) {
                $limit_query = ' LIMIT '.$app_offset.','.$app_limit;
            } else {
                $limit_query = '';
            }
        }

        //$getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name,ue.job_title,com.company_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_to) left join tbl_user_experiences as ue on(ue.user_id = u.id ) left join tbl_companies as com on (ue.company_id = com.id) where c.request_from = ? and c.status = ? $conn_qur group by c.id order by c.id desc $limit_query",array($user_id,'s'))->results();
        $getShowableResults = $this->db->pdoQuery("SELECT c.id,c.request_from,c.request_to,u.first_name,u.last_name,u.profile_picture_name from tbl_connections as c inner join tbl_users as u on(u.id = c.request_to) where c.request_from = ? and c.status = ? $conn_qur group by c.id order by c.id desc $limit_query",array($user_id,'s'))->results();
        //$app_array = array();
        if ($getShowableResults) {
            
            foreach ($getShowableResults as $fetch) {
                $action_by_user_id = filtering($fetch['request_to'], 'input', 'int');
                $action_by_user_name=filtering($fetch['first_name']) . " " . filtering($fetch['last_name']);
                $message_text = $action_by_user_name .' '. LBL_SENT_CONNECTION_REQUEST;
                $message_title = $action_by_user_name;
                $img_url =  getImageURL("user_profile_picture", $fetch['request_to'], "th4",$platform);
                if($platform == 'app'){
                    $from_connections_query=$this->db->pdoQuery('select group_concat(id) as from_ids from tbl_connections where status = ? and (request_from = ? or request_to = ?)',array('a',$fetch['request_to'],$fetch['request_to']))->result();
                    $to_connections_query=$this->db->pdoQuery('select group_concat(id) as to_ids from tbl_connections where status = ? and (request_from = ? or request_to = ?)',array('a',$fetch['request_from'],$fetch['request_from']))->result();
                    $from_connections = explode(',', $from_connections_query['from_ids']);
                    $to_connections = explode(',', $to_connections_query['to_ids']);
                    /*new common connection code*/
                    $common_connection_array = getCommonConnections($fetch['request_to'], $fetch['request_from']);
                    $common_connection_count = count($common_connection_array);
                    /*new common connection code*/
                    $getstatus = getTableValue("tbl_follower", "status", array("follower_form" =>$app_user_id,'follower_to'=>$action_by_user_id));

                    $invitationid = $fetch['id'];
                    $userid = $action_by_user_id;
                    $username = $action_by_user_name;
                    $userimg = $img_url;
                    $tagline = '';
                    //$tagline = getUserHeadline($fetch['request_to']);
                    $mutual_connection = count(array_intersect($from_connections, $to_connections));
                    $follow_status=$getstatus;
                    $app_array[] = array('invitationid'=>$invitationid,'userid'=>$userid,'username'=>$username,'userimg'=>$userimg,'tagline'=>$tagline,'mutual_connection'=>$common_connection_count,'follow_status'=>$follow_status);
                }
            }
        } else {
            $no_result_found = new Templater(DIR_TMPL . $this->module . "/no-connection-requests-nct.tpl.php");
            $content = $no_result_found->parse();
        }
        if($platform == 'app'){

            $response['invitation'] = (!empty($app_array)?$app_array:array());
            $page_data = getPagerData($totalRows, $app_limit,$currentPage);
            $response['invitation_pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);

        } else {
            $response['content'] = $content;
        }
        return $response;
    }
    public function getPageContent($platform="web") {$final_result = NULL;return $final_result;}
    public function getAllNotifications() {
        $response = array();
        $general_notifications = $this->getNotifications("general");
        $response['general_notifications'] = $general_notifications['content'];
        $response['job_notifications'] = '';
        $response['company_notifications'] = '';
        $response['group_notifications'] = '';
        $messages = $this->getMessages();
        $response['consersationFound'] = $messages['consersationFound'];
        $response['messages'] = $messages['content'];
        $response['view_all_messages'] = $messages['view_all_messages'];
        $connection_request = $this->getConnectionRequest();
        $response['connection_request'] = $connection_request['content'];
        $response['notifications_count'] = $this->getUnreadNotificationsCount($_SESSION['user_id']);
        $response['messages_count'] = $this->getUnreadMessagesCount($_SESSION['user_id']);
        $response['connection_request_count'] = $this->getConnectionRequestCount($_SESSION['user_id']);
        return $response;
    }
} ?>