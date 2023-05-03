<?php

class Group_detail extends Home {

    function __construct($group_id = '',$platform='web',$current_user_id=0) {
        $this->group_id = $group_id;
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        require_once('storage.php');
        $this->group_storage = new storage();

        $this->platform = $platform;
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);

        if ($this->group_id > 0) {
            $query = "SELECT g.id,g.user_id,g.group_name,g.group_logo,g.group_description,g.group_type_id,g.privacy,g.accessibility, gt.group_type_".$this->lId." as group_type,g.isGroupReported, CONCAT(u.first_name,' ',u.last_name) as user_name
             FROM tbl_groups g
            LEFT JOIN tbl_group_types gt ON g.group_type_id = gt.id
            LEFT JOIN tbl_users u ON u.id = g.user_id
            WHERE g.id = ? and g.status = ? ";

            $group_details_array = $this->db->pdoQuery($query,array($this->group_id,'a'))->result();
           
            $totalRow = $this->db->pdoQuery($query,array($this->group_id,'a'))->affectedRows();

            if($totalRow > 0){

                $this->db_group_id = $group_details_array['id'];
                $this->user_id = filtering($group_details_array['user_id'], 'output', 'int');
                $this->group_name = filtering($group_details_array['group_name'], 'output');
                $this->group_logo = filtering($group_details_array['group_logo'], 'output');
                $this->group_description = filtering($group_details_array['group_description'], 'output', 'text');
                $this->group_type_id = filtering($group_details_array['group_type_id'], 'output', 'int');

                //$this->group_industry_id = filtering($group_details_array['group_industry_id'], 'output', 'int');

                $this->privacy = filtering($group_details_array['privacy'], 'output');
                $this->accessibility = filtering($group_details_array['accessibility'], 'output');
                $this->isGroupReported = $group_details_array['isGroupReported'];
                $this->group_type = filtering($group_details_array['group_type'], 'output');
                //$this->group_industry = filtering($group_details_array['industry_name'], 'output');
                $this->user_name = filtering($group_details_array['user_name'], 'output');

            }else{
                if($platform == 'web'){
                    redirectPage(SITE_URL);
                }
            }
        }
    }

    public function getGroupsPageContent() {

        $final_result = NULL;

        $group_members_users = array();
        $group_users = array();

        $group_members_users = $this->db->pdoQuery("SELECT user_id FROM tbl_group_members
                WHERE group_id = ?
                AND action != ? AND action != ? ",array($this->group_id,'r','jr'))->results();

        //_print($group_members_users);exit;

        if($group_members_users) {
            foreach ($group_members_users as $key => $value) {
                $group_users[] = $value['user_id'];
            }
        }

        if ($this->session_user_id == $this->user_id) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/group-detail-admin-nct.tpl.php");
        } else if (in_array($this->session_user_id, $group_users)) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/group-detail-admin-nct.tpl.php");
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/group-detail-guest-nct.tpl.php");
        }

        $main_content->set('group_detail', $this->getGroupDetails($this->group_storage));
        $main_content->set('group_dec', $this->getGroupDesc());

        $group_member_active = $news_feed_active = $received_invitation_active = '';
        $group_member_hidden = $received_invitation_hidden = '';
        $invite_members_link = '';

        if ($this->session_user_id == $this->user_id) {

            $main_content->set('members', '');
            $main_content->set('news_feed', '');
            $main_content->set('received_invitation', '');
            $main_content->set('group_members_list', $this->groupMemberList($this->group_storage,$this->group_id));
            $main_content->set('group_admin', '');


            if (isset($_GET['group-members'])) {
                $main_content->set('members', $this->getMembersContainer($this->group_id));
                $group_member_active = 'active';
            } else if (isset($_GET['received-invitation'])) {
                $main_content->set('received_invitation', $this->getReceivedInvitationContainer($this->group_id));
                $received_invitation_active = 'active';
            } else {
                $main_content->set('news_feed', $this->getNewsFeed($this->group_id,$totalFeed));
                $news_feed_active = 'active';
            }

            $invire_member = new Templater(DIR_TMPL . $this->module . "/invite-member-button-nct.tpl.php");
            $invite_members_link = $invire_member->parse();

        } else if (in_array($this->session_user_id, $group_users)) {
            $group_member_hidden = "hidden";
            $received_invitation_hidden = "hidden";
            $main_content->set('members', '');
            $main_content->set('news_feed', $this->getNewsFeed($this->group_id));
            $main_content->set('received_invitation', '');
            $main_content->set('group_members_list', $this->groupMemberList($this->group_storage,$this->group_id));
            $main_content->set('group_admin', $this->groupAdminDetails($this->group_storage));
            $news_feed_active = 'active';
        } else {

            $main_content->set('group_admin', $this->groupAdminDetails($this->group_storage));
            $connection_arr = $this->getConnections();
            $main_content->set('connection', $connection_arr['connection']);
        }

        $invite_member_hidden = "";
        if($this->privacy == 'pr'){
            $invite_member_hidden = "hidden";
        }

        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%GROUP_ID%",
            "%SESSION_USER_ID%",
            "%ACCESSIBILITY%",
            "%NEWS_FEED_ACTIVE%",
            "%GROUP_MEMBER_ACTIVE%",
            "%RECEIVED_INVITATION_ACTIVE%",
            "%ENCRYPTED_GROUP_ID%",
            "%GROUP_MEMBERS%",
            "%GROUP_MEMBER_HIDDEN%",
            "%RECEIVED_INVITATION_HIDDEN%",
            "%INVITE_LINK%",
            "%INVITE_MEMBER_HIDDEN%"
        );

        $group_members = $this->getGroupMembers();

        $fields_replace = array(
            $this->group_id,
            $this->session_user_id,
            $this->accessibility,
            $news_feed_active,
            $group_member_active,
            $received_invitation_active,
            encryptIt($this->group_id),
            $group_members['count'],
            $group_member_hidden,
            $received_invitation_hidden,
            $invite_members_link,
            $invite_member_hidden
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getGroupDesc() {
        $main_content = new Templater(DIR_TMPL . $this->module . "/group-description-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%GROUP_ID%",
            "%GROUP_DESC%",
        );


        $fields_replace = array(
            $this->group_id,
            $this->group_description,
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getGroupDetails($group_storage = '', $platform='web') {

        $current_user_id = (($platform == 'app')?$_POST['user_id']:$this->session_user_id);
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%GROUP_ID%",
            "%GROUP_NAME%",
            "%GROUP_LOGO_URL%",
            "%GROUP_TYPE%",
            //"%GROUP_INDUSTRY%",
            "%GROUP_MEMBERS%",
            "%GROUP_MEMBERS_TEXT%",
            "%JOIN_LEAVE_GROUP_HTML%",
            "%ISGROUPREPORTED%",
            "%ISGROUPOWNER%"
        );
        $isGroupReported = ($this->isGroupReported == 'y') ? 'hide' : '';
        $isGroupOwner = ($this->user_id == $this->session_user_id) ? 'hide' : '';
        // $group_logo_url = SITE_UPD_GROUP_LOGOS . "th2_" . $this->group_logo;
        // if (!file_exists(DIR_UPD_GROUP_LOGOS . "th2_" . $this->group_logo)) {
        //     //$group_logo_url = SITE_THEME_IMG . "no-image.jpg";
        //     $group_logo_url = ($platform == 'web') ? '<span class="company-letter-square company-letter">'.ucfirst($this->group_name[0]).'</span>' : '';
        // }else{
        //     $group_logo_url = ($platform == 'web') ? '<img src="'.$group_logo_url.'">':$group_logo_url;
        // }

        $group_logo_url = $group_storage->getImageUrl1('av8db','th2_'.$this->group_logo,'group-logos-nct/');
        $is_image = getimagesize($group_logo_url);
        if(!empty($is_image)){
            $group_logo_url = '<img src="'.$group_logo_url.'" alt="'.$this->group_name.'">';
        }else{
            $group_logo_url = '<span class="profile-picture-character">'.ucfirst(mb_substr($this->group_name, 0, 1, 'utf-8')).'</span>';
        }

        $group_members = $this->getGroupMembers();

        $join_leave_group_html = '';
        //echo $this->privacy;exit;
        $isJoined = false;
        $app_join_leave_btn = '';

        if ($this->privacy == 'pu' && $this->user_id != $current_user_id) {
            $checkIfMemberExists = $this->db->select("tbl_group_members", "*", array("group_id" => $this->group_id, "user_id" =>$current_user_id))->result();
            if ($checkIfMemberExists) {
                if ($checkIfMemberExists['action'] == 'r') {
                    $join_leave_group_html = $this->commonActionsUrl("group_rejected");
                    $app_join_leave_btn = 'group_rejected';
                } else if ($checkIfMemberExists['action'] == 'aj') {
                    $join_leave_group_html = $this->commonActionsUrl("leave_group");
                    $app_join_leave_btn = 'leave_group';
                } else if ($checkIfMemberExists['action'] == 'jr') {
                    $join_leave_group_html = $this->commonActionsUrl("withdraw_request");
                    $app_join_leave_btn = 'withdraw_request';
                } else {
                    $join_leave_group_html = $this->commonActionsUrl("leave_group");
                    $isJoined = true;
                    $app_join_leave_btn = 'leave_group';
                }
            } else {
                if ($this->accessibility == 'rj') {
                    $join_leave_group_html = $this->commonActionsUrl("ask_to_join");
                    $app_join_leave_btn = 'ask_to_join';
                } else {
                    $join_leave_group_html = $this->commonActionsUrl("join_group");
                    $app_join_leave_btn = 'join_group';
                }
            }
        }

        $fields_replace = array(
            $this->group_id,
            ucwords($this->group_name),
            $group_logo_url,
            ucwords($this->group_type),
            //ucwords($this->group_industry),
            $group_members['count'],
            $group_members['text'],
            $join_leave_group_html,
            $isGroupReported,
            $isGroupOwner
        );

        if($platform == 'app'){

            $group_id = $this->db_group_id;
            $group_admin_app = $this->groupAdminDetails($this->group_storage,$platform);
            $group_name = $this->group_name;
            $group_logo = $group_logo_url;
            //$group_industry = $this->group_industry;
            $isJoined = $isJoined;
            $isGroupAdmin = ($this->user_id == $_POST['user_id'] ? true : false);
            $total_members = $group_members['count'];
            $group_description = $this->group_description;
            $group_admin_fullname = $group_admin_app['group_admin_fullname'];
            $group_admin_profile_image = $group_admin_app['group_admin_profile_image'];
            $group_privacy = ($this->privacy == 'pu') ? 'public' : 'private';

            $group_members_app = $this->groupMemberList($this->group_storage,$this->group_id,$platform,1);
            $headline = '';
            //$headline = getUserHeadline($group_admin_app['group_admin_id']);
            $final_result['group_detail'] = array(
                'group_id'=>$group_id,
                'group_name'=>$group_name,
                'group_logo'=>$group_logo,
                //'group_industry'=>$group_industry,
                'join_leave_status'=>$app_join_leave_btn,
                'isJoined'=>$isJoined,
                'isGroupAdmin'=>$isGroupAdmin,
                'total_members'=>$total_members,
                'group_description'=>$group_description,
                'group_admin_fullname'=>$group_admin_fullname,
                'group_admin_profile_image'=>$group_admin_profile_image,
                'group_admin_tagline'=>$headline,
                'group_status'=>$group_privacy
            );
            $final_result['group_members'] = $group_members_app;
        } else {
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $final_result;
    }

    public function getMembers($currentPage = 1, $group_id) {

        $response = array();

        $user_content = '';
        $member_details_array = array();

        $limit = NO_OF_INVITATION_PER_PAGE;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;

        $sql = 'SELECT * FROM tbl_group_members
                    WHERE group_id = ? AND action != ? AND action != ? ';

        $sql_with_limit = $sql . 'LIMIT ' . $limit . ' OFFSET ' . $offset;
        $member_details_array = $this->db->pdoQuery($sql_with_limit,array($group_id,'r','jr'))->results();

        if ($member_details_array) {

            $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_members = $this->db->pdoQuery($sql_with_next_limit,array($group_id,'r','jr'))->results();
            $next_available_records = count($next_members);

            $main_content = new Templater(DIR_TMPL . $this->module . "/single-member-nct.tpl.php");
            $main_content_parsed = $main_content->parse();

            $fields = array("%USER_NAME%", "%USER_PROFILE_PICTURE%", "%HEADLINE%", "%USER_PROFILE_URL%", "%ENCRYPTED_USER_ID%", "%ENCRYPTED_GROUP_ID%");

            foreach ($member_details_array as $key => $value) {
                // $user_profile_picture = getImageURL("user_profile_picture", $value['user_id'], 'th2');
                $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$value['user_id']));
                $user_profile_picture = $this->group_storage->getImageUrl1('av8db','th2_'.$user_pro_pic_name,'users-nct/'.$value['user_id'].'/');
                $is_image = getimagesize($user_profile_picture);
                $member_name = $this->db->select('tbl_users',array('first_name','last_name'),array('id'=>$value['user_id']))->result();
                if(!empty($is_image)){
                    $user_profile_picture = '<img src="'.$user_profile_picture.'" alt="'.$member_name['first_name'].' '.$member_name['last_name'].'">';
                }else{
                    $user_profile_picture = '<span class="profile-picture-character">'.ucfirst(mb_substr($member_name['first_name'], 0, 1, 'utf-8')).'</span>';
                }

                $user_status=get_user_status($value['user_id']);
                $user_profile_url="javascript:void(0)";
                if($user_status=='a'){
                    $user_profile_url = get_user_profile_url($value['user_id']);
 
                }

               // $user_profile_url = get_user_profile_url($value['user_id']);

                $headline = '';
                //$headline = getUserHeadline($value['user_id']);

                $user_detail_array = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $value['user_id']))->result();
                $user_name = $user_detail_array['first_name'] . " " . $user_detail_array['last_name'];


                $fields_replace = array(ucwords($user_name), $user_profile_picture, ucwords($headline), $user_profile_url, encryptIt($value['user_id']), encryptIt($group_id));
                $user_content .= str_replace($fields, $fields_replace, $main_content_parsed);
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "/load-more-new-nct.tpl.php");
                $load_more_link = SITE_URL . "getGroupmember_load/group/" . encryptIt($group_id) . "/page/" . encryptIt(($currentPage + 1));
                

               
                $load_more_li_tpl->set('load_more_link', $load_more_link);

                $user_content .= $load_more_li_tpl->parse();
            }
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-member-nct.tpl.php");
            if ($currentPage == 1) {
                $no_result_found_tpl->set('message', LBL_NO_SEARCH_FOUND);
            } else {
                $no_result_found_tpl->set('message', LBL_NO_MORE_RESULTS);
            }
            $user_content .= $no_result_found_tpl->parse();
        }

        $response['status'] = true;
        $response['member'] = $user_content;

        //print_r($response);
        //die;

        return $response;
    }

    public function getConnections($currentPage = 1) {

        $response = array();

        $user_content = '';
        $connection_details_array = array();

        $user_id = $this->session_user_id;

        $limit = 6;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;

        $main_content = new Templater(DIR_TMPL . $this->module . "/single-connection-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $user_id_arr = getConnections($user_id);
        $group_member_ids = getGroupMember($this->group_id);
        $group_member_ids = $group_member_ids>0?$group_member_ids:'0';

        $group_user_id = getTableValue("tbl_groups", "user_id", array("id" => $this->group_id));
        $where_cond = '';
        //echo implode(",", $user_id_arr);exit;
        if (!empty($user_id_arr)) {
            $where_cond .= 'WHERE user_id IN (' . implode(",", $user_id_arr) . ') AND user_id IN ('.$group_member_ids.') AND user_id != '.$group_user_id.' group by user_id';

            $sql = 'SELECT * FROM tbl_group_members  ' . $where_cond;
            $sql_with_limit = $sql . " LIMIT " . $limit . " OFFSET " . $offset;
            $connection_details_array = $this->db->pdoQuery($sql_with_limit)->results();
        }
        //_print($connection_details_array);exit;

        if ($connection_details_array) {

            $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_connections = $this->db->pdoQuery($sql_with_next_limit)->results();
            $next_available_records = count($next_connections);

            $main_content = new Templater(DIR_TMPL . $this->module . "/single-connection-nct.tpl.php");
            $main_content_parsed = $main_content->parse();

            $fields = array("%USER_NAME%", "%USER_PROFILE_PICTURE%", "%HEADLINE%", "%USER_PROFILE_URL%");

            foreach ($connection_details_array as $key => $value) {
                // $user_profile_picture = getImageURL("user_profile_picture", $value['user_id'], 'th2');

                $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$value['user_id']));
                $user_profile_picture = $this->group_storage->getImageUrl1('av8db','th2_'.$user_pro_pic_name,'users-nct/'.$value['user_id'].'/');
                $is_image = getimagesize($user_profile_picture);
                $connection_user_name = $this->db->select('tbl_users',array('first_name','last_name'),array('id'=>$value['user_id']))->result();
                if(!empty($is_image)){
                    $user_profile_picture = '<img src="'.$user_profile_picture.'" alt="'.$connection_user_name['first_name'].' '.$connection_user_name['last_name'].'">';
                }else{
                    $user_profile_picture = '<span class="profile-picture-character">'.ucfirst(mb_substr($connection_user_name['first_name'], 0, 1, 'utf-8')).'</span>';
                }

                $user_profile_url = get_user_profile_url($value['user_id']);

                $headline = '';
                //$headline = getUserHeadline($value['user_id']);

                $user_detail_array = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $value['user_id']))->result();
                $user_name = $user_detail_array['first_name'] . " " . $user_detail_array['last_name'];


                $fields_replace = array(ucwords($user_name), $user_profile_picture, ucwords($headline), $user_profile_url);
                $user_content .= str_replace($fields, $fields_replace, $main_content_parsed);
            }

            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-connection/group/" . encryptIt($user_id) . "/page/" . encryptIt(($currentPage + 1));

                $load_more_li_tpl->set('load_more_link', $load_more_link);

                $user_content .= $load_more_li_tpl->parse();
            }
        } else {

            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            if ($currentPage == 1) {
                $no_result_found_tpl->set('message', LBL_NO_SEARCH_FOUND);
            } else {
                $no_result_found_tpl->set('message', LBL_NO_MORE_RESULTS);
            }
            $user_content .= $no_result_found_tpl->parse();
        }

        $response['status'] = true;
        $response['connection'] = $user_content;

        //_print($user_content);exit;

        return $response;
    }

    public function groupAdminDetails($group_storage = '',$platform='web') {
        $main_content = new Templater(DIR_TMPL . $this->module . "/group-admin-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        // $user_profile_picture = getImageURL("user_profile_picture", $this->user_id, 'th2',$platform);
        $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$this->user_id));
        $user_profile_picture = $group_storage->getImageUrl1('av8db','th2_'.$user_pro_pic_name,'users-nct/'.$this->user_id.'/');
        $is_image = getimagesize($user_profile_picture);
        $group_admin_name = $this->db->select('tbl_users',array('first_name','last_name'),array('id'=>$this->user_id))->result();
        if(!empty($is_image)){
            $user_profile_picture = '<img src="'.$user_profile_picture.'" alt="'.$group_admin_name['first_name'].' '.$group_admin_name['last_name'].'">';
        }else{
            $user_profile_picture = '<span class="profile-picture-character">'.ucfirst(mb_substr($group_admin_name['first_name'], 0, 1, 'utf-8')).'</span>';
        }
        $user_profile_url = get_user_profile_url($this->user_id);
        $headline = '';
        //$headline = getUserHeadline($this->user_id);

        $fields = array("%USER_NAME%", "%USER_PROFILE_PICTURE%", "%HEADLINE%", "%USER_PROFILE_URL%");
        $fields_replace = array(ucwords($this->user_name), $user_profile_picture, ucwords($headline), $user_profile_url);
        if($platform == 'app'){
            return array('group_admin_id'=>$this->user_id,'group_admin_fullname'=>$this->user_name,'group_admin_profile_image'=>$user_profile_picture);
        } else {
            $user_content = str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $user_content;
    }

    public function getGroupMembers($group_id=0) {

        $response = array();
        $group_id=($group_id > 0)?$group_id:$this->group_id;
        $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members
                    WHERE  group_id = ? AND action != ? AND action != ? ',array($group_id,'r','jr'))->result();

        $count_group_members = $group_members['total_members'];
        $memeber_text = $count_group_members > 1 ? LBL_GRP_DTL_MEMBERS_TITLE : LBL_MEMBER_GRP;

        $response['count'] = $count_group_members;
        $response['text'] = $memeber_text;

        return $response;
    }

    public function askToJoin($platform='web') {
        //$this->db->select('tbl_groups','')

        $response = array();
        $response['status'] = false;
        if($platform == 'app') {
            $grp_id = filtering($_POST['group_id'], 'output', 'int');
            $usr_id = filtering($_POST['user_id'], 'output', 'int');
        } else {
            $grp_id = decryptIt(filtering($_POST['group_id'], 'output', 'int'));
            $usr_id = $this->session_user_id;
        }
        $count = $this->db->count('tbl_group_members',array('group_id'=>$grp_id,'user_id'=>$usr_id));
        if($count == 0){
            $val_array = array(
                'group_id' => $grp_id,
                'user_id' => $usr_id,
                'joining_request_on' => date('Y-m-d H:i:s'),
                'action' => 'jr',
            );
            $id = $this->db->insert('tbl_group_members', $val_array)->getLastInsertId();
            //For user notification
            $group_user_id = getTableValue("tbl_groups", "user_id", array("id" =>  $grp_id));
            $notificationArray = array(
                "user_id" => $group_user_id,
                "type" => "rgjr",
                "action_by_user_id" => $usr_id,
                "group_id" => $grp_id,
                "added_on" => date("Y-m-d H:i:s"),
                "updated_on" => date("Y-m-d H:i:s")
            );
            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();
            if ($id) {
                $response['status'] = true;
                $response['success'] = LBL_JOINING_REQUEST_SENT;
                $response['html'] = $this->commonActionsUrl("withdraw_request",encryptIt($grp_id));
                /* Push notification */
                $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$usr_id))->result();
                $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                $push_data = array(
                    'user_name'=>$push_user_name,
                    'group_name'=>$this->group_name,
                    "group_id" => $grp_id,
                    "notification_id"=>$notification_id

                );
                $notificationStatus = getTableValue("tbl_notification_settings", "receive_invitation_group", array("user_id" => $group_user_id));
                if($notificationStatus == 'y'){
                    $toUserName = getTableValue('tbl_users','first_name',array('id'=>$group_user_id));
                    $email_address = getTableValue('tbl_users','email_address',array('id'=>$group_user_id));
                    $arrayCont['greetings'] = $toUserName;
                    $arrayCont['from_user'] = $push_user_name;
                    $arrayCont['group_name'] = $this->group_name;
                    generateEmailTemplateSendEmail("join_group_invitation", $arrayCont, $email_address);
                }
                set_notification($group_user_id,'rgjr',$push_data);
            } else {
                $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
            }
        } else {
            $response['error'] = LBL_ALREADY_REQUESTED;
        }


        return $response;
    }

    public function joinGroup($platform = 'web') {

        $response = array();
        $response['status'] = false;

        if($platform == 'app') {
            $grp_id = filtering($_POST['group_id'], 'output', 'int');
            $usr_id = filtering($_POST['user_id'], 'output', 'int');
        } else {
            $grp_id = decryptIt(filtering($_POST['group_id'], 'output', 'int'));
            $usr_id = $this->session_user_id;
        }

        $val_array = array(
            'group_id' => $grp_id,
            'user_id' => $usr_id,
            'joining_request_on' => date('Y-m-d H:i:s'),
            'action' => 'aj',
            'action_taken_on' => date('Y-m-d H:i:s'),
            'joined_on' => date('Y-m-d H:i:s'),
        );

        $count = $this->db->count('tbl_group_members',array('group_id' => $grp_id,'user_id' => $usr_id,'action' => 'aj'));
        if($count>0){
            $response['error'] = LBL_YOU_HAVE_ALREADY_JOINED;
        } else {
            $id = $this->db->insert('tbl_group_members', $val_array)->getLastInsertId();

            if ($id) {
                $response['status'] = true;
                $response['success'] = LBL_JOINING_REQUEST_SENT_MSG;
                $response['html'] = $this->commonActionsUrl("leave_group",encryptIt($grp_id));
            } else {
                $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
            }
        }

        return $response;
    }

    public function leaveGroup($platform='web',$app_from = 'general') {

        $accessibility = filtering($_POST['accessibility'], 'output');
        $response = array();
        $response['status'] = false;

        if($platform == 'app'){
            $grp_id = filtering($_POST['group_id'], 'output', 'int');
            $usr_id = filtering($_POST['user_id'], 'output', 'int');
        } else {
            $grp_id = decryptIt(filtering($_POST['group_id'], 'output', 'int'));
            $usr_id = $this->session_user_id;
        }

        $val_array = array(
            'group_id' => $grp_id,
            'user_id' => $usr_id,
        );
        $affectedRows = $this->db->delete('tbl_group_members', $val_array)->affectedRows();
        $gdata = $this->db->select('tbl_groups',array('accessibility'),array('id'=>$grp_id))->result();
        if($gdata['accessibility'] == 'rj')
            $join_leave_status = 'ask_to_join';
        else
            $join_leave_status = 'join_group';
        if ($affectedRows) {
            $response['status'] = true;
            if($app_from == 'cancel_request'){
                $response['success'] = LBL_JOINING_REQUEST_CANCELLED;
            } else {
                $response['success'] = LBL_GROUP_LEFT;
                $response['join_leave_status'] = $join_leave_status;
            }
            if ($accessibility == 'rj') {
                $response['html'] = $this->commonActionsUrl("ask_to_join",encryptIt($grp_id));
            } else if ($accessibility == 'a') {
                $response['html'] = $this->commonActionsUrl("join_group",encryptIt($grp_id));
            } else {
                $response['html'] = '';
            }
        } else {
            if($platform == 'app'){
                if($app_from == 'cancel_request'){
                    $response['error'] = LBL_JOINING_REQUEST_CANCELLED;
                } else {
                    $response['error'] = LBL_YOU_ALREADY_JOINED_OR_LEFT;
                    $response['join_leave_status'] = $join_leave_status;
                }
            } else {
                $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
            }
        }

        return $response;
    }

    public function getNewsFeed($group_id,$feedCount=0) {
        $main_content = new Templater(DIR_TMPL . $this->module . "/news-feed-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        // $user_profile_picture = getImageURL("user_profile_picture", $this->session_user_id, 'th2');
        $current_user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$this->session_user_id));
        $user_profile_picture = $this->group_storage->getImageUrl1('av8db','th2_'.$current_user_pro_pic_name,'users-nct/'.$this->session_user_id.'/');
        $is_image = getimagesize($user_profile_picture);
        $current_user_name = $this->db->pdoQuery('SELECT CONCAT(first_name," ",last_name) as user_name FROM tbl_users WHERE id = '.$this->session_user_id.'')->result();
        if(!empty($is_image)){
            $user_profile_picture = '<img src="'.$user_profile_picture.'" alt="'.$current_user_name['user_name'].'">';
        }else{
            $user_profile_picture = '<span class="profile-picture-character">'.ucfirst(mb_substr($current_user_name['user_name'], 0, 1, 'utf-8')).'</span>';
        }
        $user_profile_url = get_user_profile_url($this->session_user_id);
        $post_an_update_url = SITE_URL . "post-an-update";

        $fields = array(
            "%POST_AN_UPDATE_URL%",
            "%USER_PROFILE_PICTURE%",
            "%USER_PROFILE_URL%",
            "%FEEDS%",
            "%ENC_GROUP_ID%",
        );

        $fields_replace = array(
            $post_an_update_url,
            $user_profile_picture,
            $user_profile_url,
            $this->getFeeds($group_id),
            encryptIt($group_id),
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function commonActionsUrl($case,$group_id=0) {

        $content = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/common-action-url-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%ID%",
            "%CLASS%",
            "%TEXT%",
            '%MAIM_ENCODED_ID%'
        );

        switch ($case) {
            case 'group_rejected':
                $fields_replace = array(
                    "",
                    "",
                    LBL_REJECTED,
                    ''
                );
                break;
            case 'withdraw_request':
                $fields_replace = array(
                    "withdraw_request",
                    "icon-close",
                   LBL_WITHDRAW_REQUEST,
                    $group_id
                );
                break;
            case 'leave_group':
                $fields_replace = array(
                    "leave_group",
                    "icon-close",
                    LBL_LEAVE_GROUP,
                    $group_id
                );
                break;
            case 'ask_to_join':
                $fields_replace = array(
                    "ask_to_join",
                    "fa fa-check",
                   LBL_ASK_TO_JOIN,
                    $group_id
                );
                break;
            case 'join_group':
                $fields_replace = array(
                    "join_group",
                    "fa fa-check",
                    LBL_JOIN_GROUP,
                    $group_id
                );
                break;

            default:
                $fields_replace = array(
                    "",
                    "",
                    "",
                    ''
                );
                break;
        }

        $content = str_replace($fields, $fields_replace, $main_content_parsed);

        return $content;
    }

    public function inviteMembers($invite_members_ids, $user_id, $group_id) {

        $response = array();
        $response['status'] = false;

        if ($invite_members_ids) {
            foreach ($invite_members_ids as $key => $value) {

                $notificationArray = array(
                    "user_id" => $value,
                    "type" => "rgji",
                    "action_by_user_id" => $user_id,
                    "group_id" => $group_id,
                    "added_on" => date("Y-m-d H:i:s"),
                    "updated_on" => date("Y-m-d H:i:s")
                );
                $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                $this->db->insert('tbl_invite_members', array('user_id' => $user_id, 'group_id' => $group_id, 'invite_member_id' => $value, 'added_on' => date('Y-m-d H:i:s')));

                $group_access = getTableValue("tbl_groups", "accessibility", array("id" => $group_id));
                $checkIfMemberExists = $this->db->select("tbl_group_members", "action", array("group_id" => $group_id, "user_id" =>$value))->result();
                if($checkIfMemberExists != ''){
                    if($checkIfMemberExists['action']=='r'){
                        
                        $this->db->delete('tbl_group_members',array('group_id'=>$group_id,'user_id'=>$value))->affectedRows();
                    }
                    
                }

                //For email notification
                $notificationStatus = getTableValue("tbl_notification_settings", "receive_invitation_group", array("user_id" => $value));
                $group_name = getTableValue("tbl_groups", "group_name", array("id" => $group_id));
                if($notificationStatus == 'y'){
                    $from_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                    $to_user = getTableValue("tbl_users", "first_name", array("id" => $value));
                    $email_address = getTableValue("tbl_users", "email_address", array("id" => $value));


                    $arrayCont['greetings'] = $to_user;
                    $arrayCont['from_user'] = $from_user;
                    $arrayCont['group_name'] = stripslashes($group_name);

                    generateEmailTemplateSendEmail("join_group_invitation", $arrayCont, $email_address);
                }

                /* Push notification */
                $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
                $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                $push_data = array(
                    'user_name'=>$push_user_name,
                    'group_name'=>$group_name,
                    'notification_id'=>$notification_id,
                    "group_id" => $group_id,

                );
                set_notification($value,'rgji',$push_data);
            }
        }

        if ($invite_members_ids) {
            $response['status'] = true;
            $response['success'] = LBL_INVITATION_REQUEST_SENT;
            return $response;
        } else {
            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
            return $response;
        }
    }

    public function groupMemberList($group_storage = '',$group_id,$platform='web',$current_page=1) {
        $main_content = new Templater(DIR_TMPL . $this->module . "/group-member-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $group_id=($group_id > 0)?$group_id:$this->group_id;
        $limit = 10;
        $offset = ($current_page - 1 ) * $limit;

        $content = NULL;
        $sql = 'SELECT * FROM tbl_group_members WHERE  group_id = ? AND action != ? AND action != ? ORDER BY id desc';
        $total = $this->db->pdoQuery($sql,array($group_id,'r',"jr"))->affectedRows();
        if($platform == 'app'){
            $sql .= " LIMIT ".$limit." OFFSET ".$offset;
        } else {
            $sql .= " LIMIT 6";
        }
        $group_members = $this->db->pdoQuery($sql,array($group_id,'r',"jr"))->results();

        if ($group_members) {
            foreach ($group_members as $key => $value) {
                // $user_profile_picture = getImageURL("user_profile_picture", $value['user_id'], 'th2',$platform);
                $user_profile_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$value['user_id']));
                $user_profile_picture = $group_storage->getImageUrl1('av8db','th2_'.$user_profile_pic_name,'users-nct/'.$value['user_id'].'/');
                $is_image = getimagesize($user_profile_picture);
                $member_name = $this->db->select('tbl_users',array('first_name','last_name'),array('id'=>$value['user_id']))->result();
                if(!empty($is_image)){
                    $user_profile_picture = '<img src="'.$user_profile_picture.'" alt="'.$member_name['first_name'].' '.$member_name['last_name'].'">';
                }else{
                    $user_profile_picture = '<span class="profile-picture-character">'.ucfirst(mb_substr($member_name['first_name'], 0, 1, 'utf-8')).'</span>';
                }
                $user_status=get_user_status($value['user_id']);
                $user_profile_url="javascript:void(0)";
                if($user_status=='a'){
                    $user_profile_url = get_user_profile_url($value['user_id']); 
                }
                $user_detail_array = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $value['user_id']))->result();
                $user_name = $user_detail_array['first_name'] . " " . $user_detail_array['last_name'];
                $fields = array("%USER_NAME%", "%USER_PROFILE_PICTURE%", "%USER_PROFILE_URL%");
                $fields_replace = array(ucwords($user_name), $user_profile_picture, $user_profile_url);
                if($platform == 'app'){
                    $app_user_id = $value['user_id'];
                    $app_profile_picture = $user_profile_picture;
                    $app_user_name = $user_name;
                    $tagline = '';
                    //$tagline = getUserHeadline($value['user_id']);
                    $mutual_connection = count(getCommonConnections($value['user_id'],$this->current_user_id));
                    $content[] = array('user_id'=>$app_user_id,'profile_picture'=>$app_profile_picture,'user_name'=>$app_user_name,'tagline'=>$tagline,'mutual_connection'=>$mutual_connection,'user_status'=>$user_status);
                } else {
                    $content .= str_replace($fields, $fields_replace, $main_content_parsed);
                }
            }
        }

        if($this->platform == 'app'){
            $app_array = (!empty($content)?$content:array());
            $page_data = getPagerData($total, $limit,$current_page);
            $pagination = array('current_page'=>$current_page,'total_pages'=>$page_data->numPages,'total'=>$total);
            $content = array('group_members'=>$app_array,'pagination'=>$pagination);
        }
        return $content;
    }

    public function getFeeds($group_id,$platform='web',$current_page=1) {
        $final_result = NULL;
        $limit = 5;
        $limitWeb = 10;
        $query_without_limit = '';

        $offset = ($current_page - 1 ) * $limit;
        $query = $query1 = "SELECT id FROM tbl_feeds WHERE group_id =  " . $group_id . "  AND type = 'g' ORDER BY id DESC ";
        $total = $this->db->count('tbl_feeds',array('group_id'=>$group_id));
        if($platform == 'app'){
            $query .= "limit $offset,$limit";
        }else{
            $ids = (isset($_GET['id']) && $_GET['id'] > 0) ?  $_GET['id'] : '';
        	$group_feed_id = decryptit($ids);
	        if(isset($_GET['id']) && $_GET['id'] > 0 && $group_feed_id>0){
	        	$totalRowFeed = $this->db->pdoQuery("SELECT count(id) as id from tbl_feeds where id>? AND group_id =  ?  AND type = ? ORDER BY id DESC ",array($group_feed_id,$group_id,'g'))->result();
	        	$totalFeed = 0;

	        	if($totalRowFeed['id']>0){
	        		$totalFeed = floor($totalRowFeed['id']/10) + 1;
	        	}else{
	        		$totalFeed=1;
	        	}

	           	$offsetWeb = ($totalFeed) * $limitWeb;
            	$query_without_limit .= "limit 0,$offsetWeb";
            	$query .= "limit 0,$offsetWeb";
	        }else{
	        	$offsetWeb = ($current_page - 1) * $limitWeb;
           		$query_without_limit .= "limit $offsetWeb,$limitWeb";
            	$query .= "limit $offsetWeb,$limitWeb";
	        }

            $query_with_next_limit = $query1 . " LIMIT " . $limitWeb . " OFFSET " . ( $offsetWeb + $limitWeb );
            $next_records = $this->db->pdoQuery($query_with_next_limit)->results();
            $next_available_records = count($next_records);
        }
        $feeds = $this->db->pdoQuery($query)->results();

        if ($feeds) {
            $feeds_container_tpl = new Templater(DIR_TMPL . "feeds-container-nct.tpl.php");
            $feeds_li = "";
            $feed_array = array();
            for ($i = 0; $i < count($feeds); $i++) {
                $signlefeed = getSingleFeed($this->group_storage,$feeds[$i]['id'],$platform,$this->current_user_id,$this->module);
                $feeds_li .= $signlefeed;
                $feed_array[] = $signlefeed;
            }


            $feeds_container_tpl->set('feeds_li', $feeds_li);
            $feeds_container_tpl_parsed = $feeds_container_tpl->parse();
            $fields = array(
                "%LIKE_UNLIKE_URL%",
                "%POST_COMMENT_URL%",
                "%POST_AN_UPDATE_URL%"
            );
            $fields_replace = array(
                SITE_URL . "like-unlike",
                SITE_URL . "post-comment",
                SITE_URL . "share-an-update"
            );
            //print_r($feed_array);
            if($platform == 'web'){
                $final_result = str_replace($fields, $fields_replace, $feeds_container_tpl_parsed);
                if ($next_available_records > 0) {
                    $load_more_li_tpl = new Templater(DIR_TMPL. $this->module  . "/load-more-nct.tpl.php");
                    $load_more_link = SITE_URL . "load-more-group-feeds/page/" . ($current_page + 1).'/'.$group_id;
                    $load_more_li_tpl->set('load_more_link', $load_more_link);
                    $final_result .= $load_more_li_tpl->parse();
                }

                return $final_result;
            }
        }

        if($platform == 'app'){
            $app_array = (!empty($feed_array)?$feed_array:array());
            $page_data = getPagerData($total, $limit,$current_page);
            $pagination = array('current_page'=>$current_page,'total_pages'=>$page_data->numPages,'total'=>$total);
            $final_result = array('group_feed'=>$app_array,'pagination'=>$pagination);
        } else {
            $no_feeds_tpl = new Templater(DIR_TMPL . $this->module . "/no-feeds-nct.tpl.php");
            $final_result = $no_feeds_tpl->parse();
        }

        return $final_result;
    }

    public function getReceivedInvitation($currentPage, $group_id,$platform = 'web') {
        $response = array();
        $response['status'] = false;

        $received_invitation_html = "";
        $limit = NO_OF_INVITATION_PER_PAGE;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;

        $sql = "SELECT gm.user_id,um.first_name,um.last_name,um.profile_picture_name from tbl_group_members as gm inner join tbl_users as um on (um.id=gm.user_id) where gm.group_id =  ?  and gm.action = ? order by gm.id desc ";

        $sql_with_limit = $sql . " LIMIT " . $limit . " OFFSET " . $offset;
        $members = $this->db->pdoQuery($sql_with_limit,array($group_id,'jr'))->results();
        $total = $this->db->pdoQuery($sql,array($group_id,'jr'))->affectedRows();;
        if ($members) {
            $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_members = $this->db->pdoQuery($sql_with_next_limit,array($group_id,'jr'))->results();
            $next_available_records = count($next_members);

            $members_tpl = new Templater(DIR_TMPL . $this->module . "/received-invitation-li-nct.tpl.php");
            $members_tpl_parsed = $members_tpl->parse();

            $fields = array(
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                "%HEADLINE%",
                "%ENCRYPTED_USER_ID%",
                "%ENCRYPTED_GROUP_ID%",
            );
            $app_array = array();

            for ($i = 0; $i < count($members); $i++) {
                $user_status=get_user_status($members[$i]['user_id']);
                $user_profile_url="javascript:void(0)";
                if($user_status=='a'){
                    $user_profile_url = get_user_profile_url($members[$i]['user_id']);
 
                }


                $first_name = filtering($members[$i]['first_name']);
                $last_name = filtering($members[$i]['last_name']);
                $user_name_full = $first_name . " " . $last_name;
                $headline = '';
                //$headline = getUserHeadline($members[$i]['user_id']);
                $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$members[$i]['user_id']));
                $user_profile_picture = $this->group_storage->getImageUrl1('av8db','th3_'.$user_pro_pic_name,'users-nct/'.$members[$i]['user_id'].'/');
                $is_image = getimagesize($user_profile_picture);
                $invitations_user_name = $this->db->select('tbl_users',array('first_name','last_name'),array('id'=>$members[$i]['user_id']))->result();
                if(!empty($is_image)){
                    $user_profile_picture = '<img src="'.$user_profile_picture.'" alt="'.$invitations_user_name['first_name'].' '.$invitations_user_name['last_name'].'">';
                }else{
                    $user_profile_picture = '<span class="profile-picture-character">'.ucfirst(mb_substr($invitations_user_name['first_name'], 0, 1, 'utf-8')).'</span>';
                }
                $fields_replace = array(
                    $user_profile_picture,
                    $user_profile_url,
                    ucwords($user_name_full),
                    ucwords($headline),
                    encryptIt($members[$i]['user_id']),
                    encryptIt($group_id),
                );
                if($platform == 'app'){
                    if($members[$i]['profile_picture_name'] != '' && file_exists(DIR_UPD_USERS.$members[$i]['user_id'].'/th4_'.$members[$i]['profile_picture_name'])){
                        $img_url = SITE_UPD_USERS.$members[$i]['user_id'].'/th4_'.$members[$i]['profile_picture_name'];
                    } else {
                        $img_url = "";
                    }
                    $user_id = $members[$i]['user_id'];

                    $mutual_connection = count(getCommonConnections($user_id,$this->current_user_id));

                    $app_array[] = array('user_id'=>$user_id,'first_name'=>$first_name,'last_name'=>$last_name,'image'=>$img_url,'head_line'=>$headline,'mutual_connection'=>(($mutual_connection>0)?$mutual_connection:0),'user_status'=>$user_status);
                } else {
                    $received_invitation_html .= str_replace($fields, $fields_replace, $members_tpl_parsed);
                }
            }

            if ($next_available_records > 0) {

                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-new-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-group-invitation/group/" . encryptIt($group_id) . "/page/" . ($currentPage + 1);

                $load_more_li_tpl->set('load_more_link', $load_more_link);

                $received_invitation_html .= $load_more_li_tpl->parse();
            }
        } else {
            if($total == 0){
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");

                $message = LBL_NO_GROUP_INVITATION;

                $no_result_found_tpl->set('message', $message);
                $final_result_html = $no_result_found_tpl->parse();

                $received_invitation_html .= $final_result_html;
            }else{
                $received_invitation_html .="";
            }
            
        }

        if($platform == 'app'){
            $page_data = getPagerData($total, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total);
            $group_invitation = $app_array;
            $final_app_array = array('group_invitation'=>((!empty($group_invitation))?$group_invitation:array()),'pagination'=>$pagination);
            $response = $final_app_array;
        } else {
            $response['status'] = true;
            $response['received_invitation'] = $received_invitation_html;
        }

        return $response;
    }

    public function getReceivedInvitationContainer($group_id) {

        $main_content = new Templater(DIR_TMPL . $this->module . "/received-invitation-list-container-nct.tpl.php");
        $response = $this->getReceivedInvitation(1, $group_id);
        $main_content->set('received_invitation', $response['received_invitation']);
        $main_content_parsed = $main_content->parse();
        $fields = array("");
        $fields_replace = array("");
        $content = str_replace($fields, $fields_replace, $main_content_parsed);

        return $content;
    }

    public function getMembersContainer($group_id) {

        $main_content = new Templater(DIR_TMPL . $this->module . "/member-list-nct.tpl.php");
        $response = $this->getMembers(1, $group_id);
        $main_content->set('member', $response['member']);
        $main_content_parsed = $main_content->parse();
        $fields = array("");
        $fields_replace = array("");
        $content = str_replace($fields, $fields_replace, $main_content_parsed);

        return $content;
    }

    public function accept_group_invitation($platform = 'web') {
        $response = array();
        $response['status'] = false;
        $val_array = array(
            'action' => 'a',
            'action_taken_on' => date('Y-m-d H:i:s'),
            'joined_on' => date('Y-m-d H:i:s'),
        );
        if($platform == 'app') {
            $grp_id = filtering($_POST['group_id'], 'output', 'int');
            $usr_id = filtering($_POST['user_id'], 'output', 'int');
        } else {
            $grp_id = decryptIt(filtering($_POST['group_id'], 'output', 'int'));
            $usr_id = decryptIt(filtering($_POST['user_id'], 'output', 'int'));
        }
        $action_user = getTableValue("tbl_groups", "user_id", array("id" => $grp_id));
        if($platform=='app'){
           $this->current_user_id=$action_user; 
        }
        //echo $usr_id;echo "<br>";echo $this->current_user_id;die;
        $affectedRows=$this->db->update('tbl_group_members', $val_array,array('group_id'=>$grp_id,'user_id'=>$usr_id))->affectedRows();
        if ($affectedRows) {
            $notificationArray = array(
                "user_id" => $usr_id,
                "type" => "gjra",
                "action_by_user_id" => $this->current_user_id,
                "group_id" => $grp_id,
                "added_on" => date("Y-m-d H:i:s"),
                "updated_on" => date("Y-m-d H:i:s")
            );
            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();
            $user_id = $usr_id;
            $group_id = $grp_id;
            //For email notification
            $notificationStatus = getTableValue("tbl_notification_settings", "accept_group", array("user_id" => $user_id));
            if($notificationStatus == 'y'){
                $from_user = getTableValue("tbl_users", "first_name", array("id" => $this->current_user_id));
                $to_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
                $group_name = getTableValue("tbl_groups", "group_name", array("id" => $group_id));
                $arrayCont['greetings'] = $to_user;
                $arrayCont['from_user'] = $from_user;
                $arrayCont['group_name'] = $group_name;

                generateEmailTemplateSendEmail("group_join_accepted", $arrayCont, $email_address);
            }
            /* Push notification */
            $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$this->current_user_id))->result();
            $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
            $push_data = array(
                'user_name'=>$push_user_name,
                'group_name'=>$this->group_name,
                'notification_id'=>$notification_id,
                "group_id" => $grp_id,

            );
            set_notification($user_id,'gjra',$push_data);

            $response['status'] = true;
            $response['success'] = LBL_GROUP_INVITATION_ACCEPTED;
        } else {
            $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
        }

        return $response;
    }

    public function reject_group_invitation($platform='web') {

        $response = array();
        $response['status'] = false;

        $val_array = array(
            'action' => 'r',
            'action_taken_on' => date('Y-m-d H:i:s'),
            'joined_on' => date('Y-m-d H:i:s'),
        );
        if($platform == 'app'){
            $grp_id = filtering($_POST['group_id'], 'output', 'int');
            $usr_id = filtering($_POST['user_id'], 'output', 'int');
        } else {
            $grp_id = decryptIt(filtering($_POST['group_id'], 'output', 'int'));
            $usr_id = decryptIt(filtering($_POST['user_id'], 'output', 'int'));
        }

        $affectedRows=$this->db->update('tbl_group_members',$val_array,array('group_id'=>$grp_id,'user_id'=>$usr_id))->affectedRows();
        if ($affectedRows) {
            $response['status'] = true;
            $response['success'] = LBL_INVITATION_REJECTED;
        } else {
            $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
        }

        return $response;
    }

    public function remove_group_member($platform = 'web') {

        $response = array();
        $response['status'] = false;
        if($platform == 'app'){
            $group_id = filtering($_POST['group_id'], 'output', 'int');
            $user_id = filtering($_POST['user_id'], 'output', 'int');
        } else {
            $group_id = decryptIt(filtering($_POST['group_id'], 'output', 'int'));
            $user_id = decryptIt(filtering($_POST['user_id'], 'output', 'int'));
        }
        $affectedRows=$this->db->delete('tbl_group_members',array('group_id'=>$group_id,'user_id'=>$user_id))->affectedRows();
        if ($affectedRows) {
            $response['status'] = true;
            $response['success'] = LBL_GROUP_MEMBER_REMOVED;
        } else {
            $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
        }

        return $response;
    }
     public function addGroupAsReported($group_id, $user_id){
        $response = array();
        $response['status'] = false;
        
        $group_data=array();

        $groupid=getTableValue("tbl_groups","id",array("id"=>$group_id,'isGroupReported' => 'n'));

        $group_data['isGroupReported']='y';
        $group_data['reportedUserId']=$user_id;
        $group_data['updated_on']=date('Y-m-d H:i:s');
       
        if($groupid>0){
            $this->db->update('tbl_groups',$group_data,array('id'=>$group_id));
            $response['status'] = "suc";
            $response['redirect_url'] = SITE_URL ."group/".$group_id;
            $response['message'] = SUCCESS_GROUP_REPORTED;
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."group/".$group_id;
            $response['message'] = ERROR_GROUP_REPORTED_NOT_EXISTS;
        }
        return $response;
    }
}
?>
