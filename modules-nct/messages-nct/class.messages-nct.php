<?php
class Messages extends Home {
    function __construct($current_user_id = 0,$platform='web') {
        parent::__construct();
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->platform = $platform;
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
    }
    public function sendMessage($conversation_id, $user_id) {

        $response = array();
        $response['status'] = false;
        $conversationDetails=$this->db->select("tbl_conversations", array('receiver_id,sender_id'), array("id" => (int)$conversation_id))->result();
        if ($conversationDetails) {
            if ($conversationDetails['sender_id'] == $user_id || $conversationDetails['receiver_id'] == $user_id) {
                $proceedToSendMessage = false;
                if ($conversationDetails['sender_id'] == $user_id) {
                    $receiver_id = $conversationDetails['receiver_id'];
                } else {
                    $receiver_id = $conversationDetails['sender_id'];
                }
                $connectionsArray = getConnections($user_id);
                if (!in_array($receiver_id, $connectionsArray)) {
                    $proceedToSendMessage = checkIfAbleToSendInMails($user_id);
                    if (!$proceedToSendMessage) {
                        $response['error'] = LBL_YOU_HAVE_TO_PURCHASE_INMAILS;
                        return $response;
                    }
                }
                $date = date("Y-m-d H:i:s");

                $messagesArray = array(
                    "conversation_id" => $conversation_id,
                    "sender_id" => $user_id,
                    "receiver_id" => $receiver_id,
                    "message" => filtering($_POST['message'], 'input'),
                    "is_inmail" => ( (!in_array($receiver_id, $connectionsArray) ) ? 'y' : 'n' ),
                    "sent_on" => $date
                );
                $messageId = $this->db->insert("tbl_messages", $messagesArray)->getLastInsertId();
                if ($messageId) {
                    if (!in_array($receiver_id, $connectionsArray)) {
                        deductInMail($user_id);
                    }
                    $date = convertDate('display',$date);
                    $response['status'] = true;
                    $response['success'] = MSG_SENT;
                    $response['message_id'] = $messageId;
                    $response['tym'] = $date;


                    $first_name = filtering(getTableValue("tbl_users", "first_name", array("id" => $user_id)));
                    $last_name = filtering(getTableValue("tbl_users", "last_name", array("id" => $user_id)));
                    $user_name = $first_name . " " . $last_name;

                    require_once(DIR_MOD . 'common_storage.php');
                    $message_storage1 = new storage();

                    $profile_picture = '';
                    $user_img = DIR_NAME_USERS."/".$user_id."/";

                    $user_pro_nm = getTableValue("tbl_users", "profile_picture_name", array("id" => $user_id));
                    $pro_img_url = $message_storage1->getImageUrl1('av8db','th3_'.$user_pro_nm,$user_img);
                    $up1 = getimagesize($pro_img_url);
                    if (empty($up1)) {
                        $profile_picture = '<span title="'.$first_name.' '.$last_name.'" class="profile-picture-character">'.ucfirst($first_name[0]).'</span>';
                    }else{
                        $profile_picture ='<picture>
                                        <source srcset="' . $pro_img_url . '" type="image/jpg">
                                        <img src="' . $pro_img_url . '" class="" alt="img" /> 
                                    </picture>';
                    }
                    // $profile_picture = getImageURL("user_profile_picture", $user_id, "th3");
                    $response['my_message'] = $this->getMessageBox("mine", filtering($_POST['message'], 'input'), $messageId, date("Y-m-d H:i:s"), $profile_picture, $user_name);
                    return $response;
                } else {
                    $response['error'] = LBL_SOME_ISSUE_MSG;
                    return $response;
                }
            } else {
                $response['error'] = LBL_SOME_ISSUE_MSG;
                return $response;
            }
        } else {
            $response['error'] = LBL_SOME_ISSUE_MSG;
        }
        return $response;
    }
    public function getSingleConversation($conversation_id, $user_id) {
        $final_result = '';
        $conversation_details=$this->db->select("tbl_conversations",array('sender_id,receiver_id'), array("id" => $conversation_id))->result();
        if ($conversation_details['sender_id'] == $user_id) {
            $conversation_with = $conversation_details['receiver_id'];
        } else {
            $conversation_with = $conversation_details['sender_id'];
        }
        $conversation_tpl = new Templater(DIR_TMPL . $this->module . "/conversation-nct.tpl.php");
        $conversation_tpl->set('conversation_messages', $this->getConversationMessages($conversation_id, $user_id));
        $conversation_tpl_parsed = $conversation_tpl->parse();
        $fields = array(
            "%USER_NAME%",
            "%REPLY_FORM_ACTION_URL%"
        );
        $conversation_with = is_numeric($conversation_with) ? $conversation_with : 0;
        $first_name = getTableValue("tbl_users", "first_name", array("id" => $conversation_with));
        $last_name = getTableValue("tbl_users", "last_name", array("id" => $conversation_with));
        $reply_form_action_url = SITE_URL . "send-message/" . encryptIt($conversation_id);
        $field_replace = array(
            ucwords($first_name) . " " . ucwords($last_name),
            $reply_form_action_url
        );
        $final_result = str_replace($fields, $field_replace, $conversation_tpl_parsed);
        return $final_result;
    }
    public function getConversationMessages($conversation_id, $user_id, $currentPage = 1) {
        $final_result = '';
        $limit = 10;
        $offset = ($currentPage - 1 ) * $limit;


        $total_messages = $this->db->pdoQuery("select id from tbl_messages m where ( ( m.sender_id = ? AND m.sender_status = ? )
          OR
          ( m.receiver_id = ? AND m.receiver_status = ? ) ) AND m.conversation_id = ? ",array($user_id,'n',$user_id,'n',$conversation_id))->affectedRows();

        $query = "SELECT * FROM ( SELECT m.*
          FROM tbl_messages m
          WHERE
          ( ( m.sender_id = ? AND m.sender_status = ? )
          OR
          ( m.receiver_id = ? AND m.receiver_status = ? ) ) AND m.conversation_id = ?
          ORDER BY id DESC LIMIT " . $limit . " OFFSET " . $offset . " ) messages ORDER BY id ASC ";
        $messages = $this->db->pdoQuery($query,array($user_id,'n',$user_id,'n',$conversation_id))->results();

        if ($messages) {
            $query_for_next_records = "SELECT messages.id FROM ( SELECT m.*
                        FROM tbl_messages m
                        WHERE
                        ( ( m.sender_id = ? AND m.sender_status = ? )
                        OR
                        ( m.receiver_id = ? AND m.receiver_status = ? ) ) AND m.conversation_id = ?
                        ORDER BY id DESC LIMIT " . $limit . " OFFSET " . ( $offset + $limit ) . " ) messages ORDER BY id ASC ";
            $next_messages = $this->db->pdoQuery($query_for_next_records,array($user_id,'n',$user_id,'n',$conversation_id))->results();
            $next_available_records = count($next_messages);
            $this->db->update("tbl_messages", array("is_read" => "y"), array("conversation_id" => $conversation_id,"receiver_id"=>$this->session_user_id))->affectedRows();
            if ($next_available_records > 0) {
                $load_more_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/messaging/thread/" . encryptIt($conversation_id) . "/currentPage/" . ($currentPage + 1);
                $load_more_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_tpl->parse();
            }
            foreach ($messages as $message) {
                if ($user_id == $message['sender_id']) {
                    $conversation_with = $message['sender_id'];
                    $type = "mine";
                    $isSender = 'true';
                } else {
                    $conversation_with = $message['sender_id'];
                    $type = "others";
                    $isSender = 'false';
                }
                $conversation_with = is_numeric($conversation_with) ? $conversation_with : 0;
                $first_name=filtering(getTableValue("tbl_users", "first_name", array("id" => $conversation_with)));
                $last_name = filtering(getTableValue("tbl_users", "last_name", array("id" => $conversation_with)));
                $user_name = $first_name . " " . $last_name;

                require_once(DIR_MOD . 'common_storage.php');
                $message_storage2 = new storage();

                $profile_picture = '';
                $user_img = DIR_NAME_USERS."/".$conversation_with."/";

                $user_pro_nm = getTableValue("tbl_users", "profile_picture_name", array("id" => $conversation_with));
                $pro_img_url = $message_storage2->getImageUrl1('av8db','th3_'.$user_pro_nm,$user_img);
                $up1 = getimagesize($pro_img_url);
                if (empty($up1)) {
                    $profile_picture = '<span title="'.$first_name.' '.$last_name.'" class="profile-picture-character">'.ucfirst($first_name[0]).'</span>';
                }else{
                    $profile_picture ='<picture>
                                    <source srcset="' . $pro_img_url . '" type="image/jpg">
                                    <img src="' . $pro_img_url . '" class="" alt="img" /> 
                                </picture>';
                }
                // $profile_picture = getImageURL("user_profile_picture", $conversation_with, "th3");
                if($this->platform=='app'){
                    $sender_id = $message['sender_id'];
                    $receiver_id = $message['receiver_id'];
                    $msg = $message['message'];
                    $tym = $message['sent_on'];

                    $message_array[] = array(
                        'message_id'=>$message['id'],
                        'sender_id'=>$sender_id,
                        'receiver_id'=>$receiver_id,
                        'msg'=> $msg,
                        'tym'=>($this->platform == 'app') ? convertDate('display',$tym) : $tym,
                        'isSender'=>$isSender
                    );
                } else {
                    $final_result .= $this->getMessageBox($type, $message['message'], $message['id'], $message['sent_on'], $profile_picture, $user_name);
                }
            }
        }
        if($this->platform == 'app'){
            $app_array = (!empty($message_array)?$message_array:array());
            $page_data = getPagerData($total_messages, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total_messages);
            $final_result = array('result'=>$app_array,'pagination'=>$pagination);
        }
        return $final_result;
    }
    public function deleteMessages($message_id, $user_id) {
        $response = array();
        $response['status'] = false;
        $message_details = $this->db->select("tbl_messages", array('sender_id,receiver_id'), array("id" => $message_id))->result();
        if ($message_details) {
            if ($user_id == $message_details['sender_id'] || $user_id == $message_details['receiver_id']) {
                $array_to_be_updated = array();
                if ($user_id == $message_details['sender_id']) {
                    $array_to_be_updated['sender_status'] = "t";
                } else {
                    $array_to_be_updated['receiver_status'] = "t";
                }
                $affectedRows = $this->db->update("tbl_messages", $array_to_be_updated, array("id" => $message_id))->affectedRows();
                if ($affectedRows > 0) {
                    $response['status'] = true;
                    $response['success'] = LBL_MSG_DELETED;
                } else {
                    $response['error'] = LBL_SOMETHING_WRONG_DELETE;
                }
            } else {
                $response['error'] = LBL_SOMETHING_WRONG_DELETE;
            }
        } else {
            $response['error'] = LBL_SOMETHING_WRONG_DELETE;
        }
        return $response;
    }
    public function getMessageBox($type = "mine", $message, $message_id, $sent_on, $profile_picture, $user_name) {
        $final_result = "";
        if ("mine" == $type) {
            $message_tpl = new Templater(DIR_TMPL . $this->module . "/others-message-nct.tpl.php");
        } else {
            $message_tpl = new Templater(DIR_TMPL . $this->module . "/my-message-nct.tpl.php");
        }
        $message_parsed = $message_tpl->parse();
        $field = array(
            '%MESSAGE_ID_ENCRYPTED%',
            '%USER_MESSAGE%',
            '%MESSAGE_DATE%',
            '%PROFILE_PICTURE%',
            '%USER_NAME%'
        );
        $field_replace = array(
            encryptIt($message_id),
            filtering(nl2br($message)),
            date("d M, Y H:i", strtotime($sent_on)),
            $profile_picture,
            ucwords($user_name)
        );
        $final_result.= str_replace($field, $field_replace, $message_parsed);
        return $final_result;
    }
    public function getConversations($action, $currentPage = 1, $type = 'All') {
        $response = array();
        $final_result = NULL;
        $selected_conversation_id = 0;
        $limit = 8;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $connectionArray = getConnections($this->current_user_id,false);

        $connectionIds = '';
        if(!empty($connectionArray)){
            $connectionIds = implode(',', $connectionArray);
        }
        $connectionCon = '';
       if($connectionIds != ''){
            if($type == 'Message'){
                //$connectionCon .= "AND m.is_inmail ='n'  ";

                $connectionCon.="AND ((m.sender_id IN (".$connectionIds.") OR m.receiver_id IN (".$connectionIds."))) ";
            }else if($type == 'InMails'){
                
               // $connectionCon .= "AND m.is_inmail ='y'  ";
                $connectionCon .= "AND ((m.sender_id NOT IN (".$connectionIds.") AND m.receiver_id NOT IN (".$connectionIds.")))  ";
            }
       }else{
            if($type == 'Message'){
                $connectionCon .= "AND m.is_inmail ='n'  ";

               
            }else if($type == 'InMails'){
                
                $connectionCon .= "AND m.is_inmail ='y'  ";
               
            }
       }
        
        $keyQuery = "";
        if($_POST['keyword'] != ''){
            
            $keyword = filtering($_POST['keyword']);
            $keyQuery = "and (s.first_name like '%".$keyword."%' or s.last_name like '%".$keyword."%' or concat(s.first_name,' ',s.last_name) like '%".$keyword."%' or r.first_name like '%".$keyword."%' or r.last_name like '%".$keyword."%' or concat(r.first_name,' ',r.last_name) like '%".$keyword."%')";
        }

       /* $query = "SELECT c.id,c.sender_id,c.receiver_id,u.first_name,u.last_name,m.sent_on,m.message,m.is_read
            FROM tbl_conversations c
            left join tbl_users as u on (u.id = (if(c.sender_id=".$this->current_user_id.",c.receiver_id,c.sender_id)))
            left join tbl_messages as m on (c.id = m.conversation_id)
            WHERE
            (( c.sender_id = '" . $this->current_user_id . "' AND c.sender_status = 'n' )
            OR
            ( c.receiver_id = '" . $this->current_user_id . "' AND c.receiver_status = 'n' ))
            ".$connectionCon.$keyQuery."
            group by c.id ORDER BY c.id DESC  ";*/
            $query = "SELECT DISTINCT IF(m.`sender_id` != '" . $this->current_user_id . "',m.`sender_id`,m.`receiver_id`) AS userid, MAX(m.id) AS msgid,IF(m.`sender_id` != '" . $this->current_user_id . "',s.first_name,r.first_name) AS first_name,IF(m.`sender_id` != '" . $this->current_user_id . "',s.last_name,r.last_name) AS last_name FROM tbl_messages AS m INNER JOIN tbl_users AS s ON m.`sender_id` = s.`id` INNER JOIN tbl_users AS r ON m.`receiver_id` = r.`id` 
                WHERE ( (m.`receiver_id` = ? AND m.receiver_status= ? ) OR( m.`sender_id` = ? AND m.sender_status= ?) ) ".$connectionCon.$keyQuery."
                GROUP BY IF( m.`sender_id` != '" . $this->current_user_id . "', m.`sender_id`, m.`receiver_id` ) ORDER BY `msgid`  DESC";
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $where_arr=array($this->current_user_id,'n',$this->current_user_id,'n');
        $conversations = $this->db->pdoQuery($query_with_limit,$where_arr)->results();


        $total_conversation = $this->db->pdoQuery($query,$where_arr)->affectedRows();
        if ($conversations) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_conversations = $this->db->pdoQuery($query_with_next_limit,$where_arr)->results();
            $next_available_records = count($next_conversations);
            $left_conversation_tpl = new Templater(DIR_TMPL . $this->module . "/left-conversation-nct.tpl.php");
            $left_conversation_tpl_parsed = $left_conversation_tpl->parse();
            $fields = array(
                "%CONVERSATION_ID_ENCRYPTED%",
                "%SELECTED_CONVERSATION_CLASS%",
                "%USER_PROFILE_PICTURE%",
                "%USER_NAME%",
                "%DATE%",
                "%USER_MESSAGE%"
            );
            if (isset($_GET['conversation_id']) && $_GET['conversation_id'] != "") {
                $selected_conversation_id = filtering(decryptIt($_GET['conversation_id']), 'input', 'int');
            }

            for ($i = 0; $i < count($conversations); $i++) {
                $app_type = 'myconnection';
                $last_message = $this->db->select('tbl_messages',array('message,is_read','sent_on','conversation_id','sender_id','receiver_id'),array('id'=>$conversations[$i]['msgid']))->result();

                $conversation_id = $last_message['conversation_id'];
                $message = $last_message['message'];
                $is_read = $last_message['is_read'];
                $sent_on = $last_message['sent_on'];
                $sender_id = $last_message['sender_id'];
                $receiver_id = $last_message['receiver_id'];

                if (!$selected_conversation_id && $i == 0) {
                    $selected_conversation_id = $conversation_id;
                }
                $selected_conversation_class = "";
                $sender_id = filtering($sender_id, 'input', 'int');
                $receiver_id = filtering($receiver_id, 'input', 'int');
                if ($selected_conversation_id == $conversation_id) {
                    if ($action != 'composeMessage') {
                        $selected_conversation_class = "active-left-msg";
                        $response['conversation_id'] = $conversation_id;
                    }
                }

                if ($sender_id == $this->current_user_id) {
                    $conversation_with_user_id = $receiver_id;
                    if(!in_array($receiver_id, $connectionArray)){
                        $app_type = 'InMails';
                    }
                } else {
                    $conversation_with_user_id = $sender_id;

                    if(!in_array($sender_id, $connectionArray)){
                        $app_type = 'InMails';
                    }
                }

                $is_read_count = $this->db->count('tbl_messages',array('conversation_id'=>$conversation_id,'receiver_id'=>$this->current_user_id,'is_read'=>'n'));


                $conversation_with_user_id=is_numeric($conversation_with_user_id) ? $conversation_with_user_id : 0;
                $first_name = filtering($conversations[$i]['first_name']);
                $last_name = filtering($conversations[$i]['last_name']);
                $user_name = $first_name . " " . $last_name;
                //$date = date("d M, Y H:i", strtotime($conversations[$i]['sent_on']));
                //$user_message = filtering($conversations[$i]['message']);
                $date = date("d M, Y H:i", strtotime($sent_on));
                $message =  myTruncate($message,30);
                $user_message = filtering($message);

                require_once(DIR_MOD . 'common_storage.php');
                $message_storage3 = new storage();

                $profile_picture_final = '';
                $user_img = DIR_NAME_USERS."/".$conversation_with_user_id."/";

                $user_pro_nm = getTableValue("tbl_users", "profile_picture_name", array("id" => $conversation_with_user_id));
                $pro_img_url = $message_storage3->getImageUrl1('av8db','th2_'.$user_pro_nm,$user_img);
                $up1 = getimagesize($pro_img_url);
                if (empty($up1)) {
                    $profile_picture_final = '<span title="'.$first_name.' '.$last_name.'" class="profile-picture-character">'.ucfirst($first_name[0]).'</span>';
                }else{
                    $profile_picture_final ='<picture>
                                    <source srcset="' . $pro_img_url . '" type="image/jpg">
                                    <img src="' . $pro_img_url . '" class="" alt="img" /> 
                                </picture>';
                }

                // $profile_picture_final = getImageURL("user_profile_picture", $conversation_with_user_id, "th2",$this->platform);
                $fields_replace = array(
                    encryptIt($conversation_id),
                    $selected_conversation_class,
                    $profile_picture_final,
                    ucwords($user_name),
                    $date,
                    $user_message
                );
                $is_read = (($is_read_count==0)?'y':'n');

                if($this->platform == 'app'){
                    $app_array[] = array('conversation_id'=>$conversation_id,'isRead'=>$is_read,'userid'=>$conversation_with_user_id,'username'=>$user_name,'userimg'=>$profile_picture_final,'time'=>$date,'lastmsg'=>$user_message,'type'=>$app_type);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $left_conversation_tpl_parsed);
                }
            }
            if ($next_available_records > 0) {
                $load_more_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getConversations/currentPage/" . ($currentPage + 1);
                $load_more_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_tpl->parse();
            }
        } else {
            $no_conversation_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-conversation-found-nct.tpl.php");
            $final_result = $no_conversation_found_tpl->parse();
        }
        if($this->platform=='app'){


            $array = (!empty($app_array)?$app_array:array());
            $page_data = getPagerData($total_conversation, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total_conversation);
            $final_app = array('messages'=>$array,'pagination'=>$pagination);

            return $final_app;
        } else {
            $response['html'] = $final_result;
            return $response;
        }
    }
    public function submitComposeMessageForm() {
        $response = array();
        $response['status'] = false;

        if($this->platform == 'app'){
            $receiver_id = filtering($_POST['receiver_id'], 'input', 'int');
        } else {
            $receiver_id = filtering(decryptIt($_POST['receiver_id']), 'input', 'int');
        }
        $message = filtering($_POST['message'], 'input');

        if($receiver_id == '' || $message == ''){
            $response['error'] = ERROR_CREATE_COMPANY_FILL_ALL_MANDATORY_FIELDS ;
            //redirectPage(SITE_URL."compose-message");
            return $response;
        }
        $connectedMembers = getConnections($this->current_user_id, true);
        if (!in_array($receiver_id, $connectedMembers)) {
            $proceedToSendMessage = checkIfAbleToSendInMails($this->current_user_id);
            if (!$proceedToSendMessage) {
                $response['error'] = LBL_YOU_HAVE_TO_PURCHASE_INMAILS;
                return $response;
            }
        }
        $query = "SELECT id FROM tbl_conversations WHERE
                    ( sender_id = ? AND receiver_id = ? ) OR
                    ( sender_id = ? AND receiver_id = ? )";
        $checkIfConversationExists = $this->db->pdoQuery($query,array($this->current_user_id ,$receiver_id,$receiver_id, $this->current_user_id))->result();
        if ($checkIfConversationExists) {
            $conversation_id = $checkIfConversationExists['id'];
            $converationArray = array(
                "sender_status" => 'n',
                "receiver_status" => 'n'
            );
            $this->db->update("tbl_conversations", $converationArray, array("id" => $conversation_id));
        } else {
            $converationArray = array(
                "sender_id" => $this->current_user_id,
                "receiver_id" => $receiver_id,
                "added_on" => date("Y-m-d H:i:s")
            );
            $conversation_id = $this->db->insert("tbl_conversations", $converationArray)->getLastInsertId();
        }
        if ($conversation_id) {

            $messageArray = array(
                "conversation_id" => $conversation_id,
                "sender_id" => $this->current_user_id,
                "receiver_id" => $receiver_id,
                "message" => $message,
                "is_inmail" => ( (!in_array($receiver_id, $connectedMembers) ) ? 'y' : 'n' ),
                "sent_on" => date("Y-m-d H:i:s")
            );
            $messageId = $this->db->insert("tbl_messages", $messageArray)->getLastInsertId();
            if ($messageId) {
                if (!in_array($receiver_id, $connectedMembers)) {
                    deductInMail($this->current_user_id);
                }
                $response['status'] = true;
                $response['success'] = MSG_SENT;
                $response['conversation_id'] = encryptIt($conversation_id);
                $response['app_conversation_id'] = $conversation_id;
                $getConversationsResponse = $this->getConversations('');
                $response['conversations'] = $getConversationsResponse['html'];
                $response['single_conversation'] = $this->getSingleConversation($conversation_id, $this->current_user_id);

                return $response;
            } else {
                $response['error'] = LBL_SOME_ISSUE_MSG;
                return $response;
            }
        } else {
            $response['error'] = LBL_SOME_ISSUE_MSG;
            return $response;
        }
    }
    public function getComposeMessageForm() {
        $final_result = NULL;
        $compose_message_form = new Templater(DIR_TMPL . $this->module . "/compose-message-form.tpl.php");
        $compose_message_form_parsed = $compose_message_form->parse();
        $fields = array(
            "%COMPOSE_MESSAGE_FORM_ACTION_URL%",
            "%RECEIVER_NAME_READONLY%",
            "%RECEIVER_NAME%",
            "%RECEIVER_ID%"
        );
        if (isset($_GET['user_id']) && $_GET['user_id'] != "") {
            $receiver_id = filtering(decryptIt($_GET['user_id']), 'input', 'int');
            $userDetails = $this->db->select("tbl_users", array('first_name,last_name'), array("id" => $receiver_id))->result();
            $receiver_name = filtering($userDetails['first_name']) . " " . filtering($userDetails['last_name']);
                $receiver_name_readonly = " readonly='readonly' ";
        } else {
            $receiver_id = "";
            $receiver_name = "";
            $receiver_name_readonly = "";
        }
        $compose_message_form_action_url = SITE_URL . "send-message";
        $fields_replace = array(
            $compose_message_form_action_url,
            $receiver_name_readonly,
            $receiver_name,
            encryptIt($receiver_id)
        );
        $final_result = str_replace($fields, $fields_replace, $compose_message_form_parsed);
        return $final_result;
    }
    public function getMessagesPageContent($action) {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $getConversationsResponse = $this->getConversations($action);
        $conversation_id = ( ( isset($getConversationsResponse['conversation_id']) ) ? $getConversationsResponse['conversation_id'] : '' );
        $main_content->set('conversations', $getConversationsResponse['html']);
        if ($action == 'composeMessage') {
            $main_content->set('single_conversation', '');
            $main_content->set('compose_message_form', $this->getComposeMessageForm());
        } else {
            if (isset($_GET['conversation_id']) && $_GET['conversation_id'] != "") {
                $conversation_id = filtering(decryptIt($_GET['conversation_id']), 'input', 'int');
            }
            $main_content->set('single_conversation', $this->getSingleConversation($conversation_id, $this->session_user_id));
            $main_content->set('compose_message_form', "");
        }
        $final_result = $main_content->parse();
        return $final_result;
    }
    public function deleteConversation($conversation_id,$user_id){
        $response = array();
        $response['status'] = false;
        /*$user_id = $this->current_user_id;*/
        $type = ( ( isset($_REQUEST['type']) ) ? filtering($_REQUEST['type'], 'input', 'string') : 'All' );
        /*$conversation_id = ( ( isset($_REQUEST['conversation_id']) ) ? filtering($_REQUEST['conversation_id'], 'input', 'string') : 0 );*/
        $currentPage=((isset($_REQUEST['currentPage']))? filtering($_REQUEST['currentPage'], 'input', 'int') : 1 );
        //For remove conversation
        $receiver_id=getTableValue("tbl_conversations", "receiver_id", array("id" => $conversation_id));

        if ($user_id == $receiver_id) {
            $this->db->exec('update tbl_conversations set receiver_status = "t" where id = "' . $conversation_id . '"');
        } else {
            $this->db->exec('update tbl_conversations set sender_status = "t" where id = "' . $conversation_id . '"');
        }

        $getAllMessages = $this->db->select("tbl_messages", "*", array("conversation_id" => $conversation_id))->results();
        if ($getAllMessages) {
            for ($i = 0; $i < count($getAllMessages); $i++) {
                $deleteMessageResponse = $this->deleteMessages($getAllMessages[$i]['id'], $user_id);
            }
        }

        //For get conversation
        $messagesResponse = $this->getConversations("", $currentPage, $type);
        //$response['conversationDetail'] = $response['messages'] = array();
        if (isset($messagesResponse['html']) && $messagesResponse['html'] != "") {
            $response['status'] = true;

            if (isset($messagesResponse['conversation_id'])) {
                $ConversationDetail = $this->getSingleConversation($messagesResponse['conversation_id'], $user_id);
                $response['conversationDetail'] = $ConversationDetail;
                $response['conversation_id_encrypted'] = encryptIt($messagesResponse['conversation_id']);

            } else {
                $response['conversationDetail'] = '';
                $response['conversation_id_encrypted'] = '';
            }

            $response['messages'] = $messagesResponse;
        } else {
            $response['conversationDetail'] = '';
            $response['messages']['html'] = '';
        }
        return $response;
    }
} ?>