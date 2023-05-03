<?php 
class Referrals extends Home {
    function __construct($platform='web',$current_user_id=0) {
        parent::__construct();
        foreach ($GLOBALS as $key => $values) { $this->$key = $values; }
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $this->user_id = $user_id = filtering($_GET['user_id'], 'input', 'int');
        } else if(isset($_POST['user_id']) && $_POST['user_id'] > 0){
            $this->user_id = $user_id = filtering($_POST['user_id'], 'input', 'int');
        } else {
            $this->user_id = $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        }
        $this->platform = $platform;
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);

        $query = "SELECT u.profile_picture_name,u.id,u.first_name,u.last_name,u.email_address,u.date_added,u.phone_no,u.user_home_airport,u.isFerryPilot,u.personal_details,u.gender,u.user_DOB,l.formatted_address,l.address1,l.address2,l.country,l.state,l.city1,l.city2,l.postal_code,l.latitude,l.longitude FROM tbl_users u LEFT JOIN tbl_locations l ON u.location_id = l.id WHERE u.id = ? ";
                    
        $user_details = $this->db->pdoQuery($query,array($this->user_id))->result();
        $this->db_user_id    = filtering($user_details['id']);
        $this->first_name    = filtering($user_details['first_name']);
        $this->last_name     = filtering($user_details['last_name']);
        $this->email_address = isset($user_details['email_address']) ? filtering($user_details['email_address']) : '-';
        $this->formatted_address = filtering($user_details['formatted_address']);
        $this->industry_name = '';
        $this->profile_picture_name=filtering($user_details['profile_picture_name']);
    }
    public function getPageContent($platform="web") {
        $final_result = NULL;
        $actions=$remove_from_connection_url=$connections_url=$connection_level=$send_inmail_url=$send_inmail_text='';
        $send_inmail_class = 'hidden';
        $user_actions_container_tpl_parsed = '';
        
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $profile_picture_actions_parsed = $add_experience_parsed = $add_education_parsed = $add_skill_parsed = $add_language_parsed = $add_licenses_endorsement_parsed =  "";
        
        if ($this->user_id == $this->current_user_id) {
            $profile_picture_actions = new Templater(DIR_TMPL . $this->module . "/profile-picture-actions-nct.tpl.php");
            $profile_picture_actions_parsed = $profile_picture_actions->parse();
            $fields = array("%CLASS%");
            if($this->profile_picture_name == ''){
                $class='hidden';
            }else{
                $class='';
            }
            $fields_replace = array($class);
            $profile_picture_actions_parsed  = str_replace($fields, $fields_replace, $profile_picture_actions_parsed);

           
            $hide_action='';
            $class=$class_pic_oth='';
        }
        
        $main_content->set('profile_picture_actions', $profile_picture_actions_parsed);
        $main_content->set('user_actions', $user_actions_container_tpl_parsed);
        
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%USER_PROFILE_PICTURE%",
            "%USER_NAME_FULL%",
            "%CONNECTIONS_URL%",
            "%NO_OF_CONNECTIONS%",
            "%ENCRYPTED_USER_ID%",
            "%FIRST_NAME%",
            "%LAST_NAME%",
            // "%HEADLINE%",
            '%VIEW_FULL_PROFILE_CLASS%',
            "%ADD_CONNECTION_URL%",
            "%FOLLOW_TAG%",
            "%USER_ID%",
            "%USER_STATUS%",
            "%HIDE_LI%",
            "%REQUEST_RECEIVED%",
            "%COVER_IMG%",
            "%RECEIVED_REFERRALS_REVIEWS%",
            "%INDUSTRY_NAME%",
            "%FORMATTED_ADDRESS%",
            "%RIDHT_SIDEBAR%",
        );
        $no_of_connections = getNoOfConnections($this->user_id);
        $connections_url = SITE_URL . "connection/" . encryptIt($this->user_id);
        $url_user_id = decryptIt(isset($_GET['user_id']) && $_GET['user_id']);
        $user_id = $this->current_user_id;
        $url = 'javascript:void(0);';
        $add_connection_url = SITE_URL . "people-you-may-know";
        if($no_of_connections > 0){
            $url = $connections_url;
        }        
        if($this->current_user_id == '' || $this->current_user_id == 0){
            $experiences_class = $education_class = $language_class = $skill_class = 'hide';
            $hide_if_not_logged = 'hide';
        } else{
            $view_full_profile_class = 'hide';
        }
        $getUserHeadline = '';
        //$getUserHeadline = getUserHeadline($this->user_id);
        if( $this->current_user_id>0){
            $status=$getstatus='';
            $follow_tag=LBL_FOLLOW;
            $class_follow='icon-check';
            $getstatus = getTableValue("tbl_follower", "status", array("follower_form" => $this->current_user_id,'follower_to'=>$this->user_id));
            if($getstatus != ''){
                $status=$getstatus;
                if($getstatus=='f'){
                $follow_tag=LBL_UNFOLLOW;
                $class_follow='icon-close';
                }
            }
        }

        $image_url = $user_cover = '';
        $user_img = DIR_NAME_USERS."/".$this->user_id."/";

        $user_pro_nm = getTableValue("tbl_users", "profile_picture_name", array("id" => $this->user_id));

        require_once(DIR_MOD.'common_storage.php');
        $ref_storage = new storage();
        $pro_img_url = $ref_storage->getImageUrl1('av8db','th4_'.$user_pro_nm,$user_img);
        $up1 = getimagesize($pro_img_url);
        if (empty($up1)) {
            $image_url = '<span title="'.$this->first_name.' '.$this->last_name.'" class="profile-picture-character">'.ucfirst($this->first_name[0]).'</span>';
        }else{
            $image_url ='<img src="' . $pro_img_url . '" class="" alt="img" />';
        }
        // $image_url = getImageURL("user_profile_picture", $this->user_id, "th4",$platform);
        
        $src_cover = "user_cover-nct/".$this->user_id."/";
        $user_cover_nm = getTableValue("tbl_users", "cover_photo", array("id" => $this->user_id));
        $cover_img_url = $ref_storage->getImageUrl1('av8db','th1_'.$user_cover_nm,$src_cover);
        $up2 = getimagesize($cover_img_url);
        if (empty($up2)) {
            $user_cover = 'https://storage.googleapis.com/av8db/u-pro-bg.jpg';
        }else{
            $user_cover =$cover_img_url;
        }
        // $user_cover= getImageURL("user_cover_picture",$this->user_id,"th1",$platform);
        
        $hide_li=$hide_small=$hide_p='';
       
        $language_hide=$experiences_hide=$educations_hide=$skill_hide=$licenses_hide= $airport_hide = '';
        
        $getUserHeadlineData = '';
        //$getUserHeadlineData = getUserHeadlineNew($this->user_id);
        $industry_name = $this->industry_name;
        $formatted_address = $this->formatted_address;
        $fields_replace = array(
            $image_url,
            ucwords($this->first_name) . " " . ucwords($this->last_name),
            $url,
            $no_of_connections,
            encryptIt($this->current_user_id),
            $this->first_name,
            $this->last_name,
            //$getUserHeadline,
            $view_full_profile_class,
            $add_connection_url,
            $follow_tag,
            encryptIt($this->user_id),
            $status,
            $hide_li,
            $this->getReceivedRequestData($this->session_user_id,'web'),
            $user_cover,
            $this->getReceivedReferralReviews($this->session_user_id,'web'),
            ucwords($industry_name),
            $formatted_address,
            $this->getRightSidebar(),
        );
        if($platform == 'app'){
            if($this->db_user_id>0){
                $name = $this->first_name . " " . $this->last_name;
                $headline = $getUserHeadline;
                $industry = $industry_name;
                $location = $formatted_address;
                $basic = array(
                    'name'=>$name,
                    'headline'=>$headline,
                    'industry'=>$industry,
                    'location'=>$location,
                    'image_url'=>$image_url,
                    'first_name'=>$this->first_name,
                    'last_name'=>$this->last_name,
                    'cover_img_url'=>$user_cover,
                    'job_title'=>$getUserHeadlineData['job_title'],
                    'company_name'=>$getUserHeadlineData['company_name'],
                    'company_id'=>$getUserHeadlineData['company_id'],
                    'industry_id'=>$this->industry_id,
                    'company_type'=>$getUserHeadlineData['company_type'],
                    'lable_at'=>AT
                );

                if($this->current_user_id>0 && $this->current_user_id != $this->user_id){
                    $mutual_connection = getCommonConnections($this->user_id, $this->current_user_id);
                    $basic['connection_level'] = $connection_level;
                    $basic['connection_status'] = $connection_status;
                    $basic['mutual_connection'] = count($mutual_connection);
                    $basic['follow_status']=$status;
                }

            } else {
                $basic = array();
            }
            $final_app_array = $basic;
            return $final_app_array;
        }
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
    public function getPeopleSearchForReferrals($currentPage = 1, $main_page = false, $call_from_ajax = false,$platform='web',$app_user_id=0,$keyword='') {
        $users_html = '';
        $next_available_records = 0;
        $limit = NO_OF_CONNECTION_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        if ($keyword != '') {
             $wherecon .= 'AND (uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%")';
        }
        $query = "select * from tbl_users as uf WHERE uf.id != '".$this->session_user_id."' AND  status = 'a' " . $wherecon . " ";

        $totalRows = $this->db->pdoQuery($query)->affectedRows();
        $query_with_limit = $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

        $referral_user_data = $this->db->pdoQuery($query)->results();
        $connection_count_total=count($referral_user_data);

        $referral_user_data = $this->db->pdoQuery($query_with_limit,$whrExtArr)->results();
        if ($referral_user_data) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset ;
            $connection_count_load = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();
            $next_users = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();

            $next_available_records = count($next_users);
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/people-send-request-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $fields = array(
                "%USER_ID%",
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                "%USER_PROFILE_PICTURE%"
            );
            for ($i = 0; $i < count($referral_user_data); $i++) {
                $connection_status = '';
                $user_actions = null;
                $user_id = $referral_user_data[$i]['id'];
                $user_profile_url = get_user_profile_url($user_id);
                $first_name = filtering($referral_user_data[$i]['first_name']);
                $last_name = filtering($referral_user_data[$i]['last_name']);
                $user_name_full = $first_name . " " . $last_name;

                $user_img = DIR_NAME_USERS."/".$user_id."/";
                $ref_user_logo = DIR_NAME_USERS."/".$referral_user_data[$i]['id']."/";
                
                $userimage_final = $user_logo_url = '';

                $user_image_name = getTableValue("tbl_users", "profile_picture_name", array("id" => $user_id));

                require_once(DIR_MOD.'common_storage.php');
                $user_storage = new storage();
                $img_url = $user_storage->getImageUrl1('av8db','th3_'.$user_image_name,$user_img);
                $up1 = getimagesize($img_url);
                if (empty($up1)) {
                    $userimage_final = '<span title="'.$user_name_full.'" class="profile-picture-character">'.ucfirst($first_name[0]).'</span>';
                }else{
                    $userimage_final ='<picture>
                                    <source srcset="' . $img_url . '" type="image/jpg">
                                    <img src="' . $img_url . '" class="" alt="img" /> 
                                </picture>';
                }
                // $userimage_final=getImageURL("user_profile_picture", $user_id, "th3",$this->platform);
                $user_logo_name = getTableValue("tbl_users", "profile_picture_name", array("id" => $referral_user_data[$i]['id']));

                $img_url1 = $user_storage->getImageUrl1('av8db','th3_'.$user_logo_name,$ref_user_logo);
                $up2 = getimagesize($img_url1);
                if (empty($up2)) {
                    $user_logo_url = '<span title="'.$first_name.' '.$last_name.'" class="profile-picture-character">'.ucfirst($first_name[0]).'</span>';
                }else{
                    $user_logo_url ='<picture>
                                    <source srcset="' . $img_url1 . '" type="image/jpg">
                                    <img src="' . $img_url1 . '" class="" alt="img" /> 
                                </picture>';
                }                
                // $user_logo_url = getImageURL("user_profile_picture", $referral_user_data[$i]['id'], "th3");

                if($platform == 'web'){
                    $user_logo_url = ($user_logo_url == '') ? '<span class="profile-picture-character">'.$user_name_full.'</span>' : $user_logo_url;
                }
                $fields_replace = array(
                    $user_id,
                    $userimage_final,
                    $user_profile_url,
                    ucwords($user_name_full),
                    $user_logo_url
                );
                
                if($this->platform == 'app'){
                    $app_array[] = array(
                        'user_id'=>$user_id,
                        'user_name'=>$user_name_full,
                        'userimage'=>$userimage_final
                    );
                } else {
                    $users_html .= str_replace($fields, $fields_replace, $single_user_tpl_parsed);
                }
            }
            if ($next_available_records > 0) {
                $keyword=($_GET['keyword'] != '')?$_GET['keyword']:'';

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getUsers/currentPage/" . ($currentPage + 1)."/".$keyword;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $users_html .= $load_more_li_tpl->parse();
            }
            $pagination = getPagination($totalUsers, count($users), NO_OF_SEARCH_RESULTS_PER_PAGE, $currentPage);   
        }
        return $users_html;
    }
    public function sendReferralsRequest($currentPage = 1, $main_page = false, $call_from_ajax = false,$platform='web',$app_user_id=0,$user_id=''){
       
        $response = array();
        $response['status'] = false;
        
        $referral_data=array();

        $referral_data['sender_id']  =$_SESSION['user_id'];
        $referral_data['receiver_id']=$user_id;
        $referral_data['isAccept']   ='n';
        $referral_data['isActive']   ='y';
        $referral_data['createdAt']  =date('Y-m-d H:i:s');
    
        if($user_id > 0 && $_SESSION['user_id'] > 0){
            $referral_id=$this->db->insert('tbl_send_referral_request',$referral_data)->getLastInsertId();
            if($referral_id > 0){

                $user_data =$this->db->select("tbl_users", "*", array("id" => $user_id))->result();

                $arrayCont = array();
                $arrayCont['greetings'] = $user_data['first_name'] . " " . $user_data['last_name'];
                $arrayCont['reflink'] = "<a href='" . SITE_URL ."referral/"."' target='_blank'>Click here</a>";
                
                generateEmailTemplateSendEmail("referral_request_received", $arrayCont, $user_data['email_address']);
                $response['status'] = "suc";
                $response['redirect_url'] = SITE_URL ."referral/";
                $response['message'] = SUCCESS_REFERRAL_SEND_MESSAGE;
            }
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."referral/";
            $response['message'] = ERROR_REFERRAL_SEND_MESSAGE;
        }
        return json_encode($response);
    }
    public function getReceivedRequestData($userId,$platform = 'web'){
        
        $final_result = '';
        
        $query = "SELECT sr.id as referralId, sr.sender_id, u.id as userId, u.first_name, u.last_name, sr.createdAt FROM tbl_users as u LEFT JOIN tbl_send_referral_request as sr ON u.id = sr.sender_id WHERE sr.receiver_id = ? AND sr.isActive = 'y' AND sr.isAccept = 'n' ";

        $referrals = $this->db->pdoQuery($query,array($userId))->results();
       
        if (!empty($referrals)) {
            $actions = '';
            $single_language_li_tpl = new Templater(DIR_TMPL . $this->module . "/single-received-request-nct.tpl.php");
    
            $fields = array("%USER_PROFILE_URL%","%REFERRALS_ID%","%SENDER_ID%","%RECEIVER_ID%", "%FIRST_NAME%","%LAST_NAME%","%CREATED_AT%");
            for ($i = 0; $i < count($referrals); $i++) {
              
                $user_url = SITE_URL . 'profile/'.$referrals[$i]['sender_id'];
                $referrals_id = filtering($referrals[$i]['referralId']);
                $sender_id    = filtering($referrals[$i]['sender_id']);
                $receiver_id  = '';
                $first_name   = filtering($referrals[$i]['first_name']);
                $last_name    = filtering($referrals[$i]['last_name']);
                $createdAt    = isset($referrals[$i]['createdAt']) ? date ("d M, Y", strtotime($referrals[$i]['createdAt'])) : '-';
                
                $fields_replace = array(
                    $user_url,
                    $referrals_id,
                    $sender_id,
                    $receiver_id,
                    $first_name,
                    $last_name,
                    $createdAt
                );
               
                $single_language_li_tpl_parsed = $single_language_li_tpl->parse();
                
                if($platform == 'app'){
                    $array[] = array('referrals_id'=>$referrals[$i]['referralId'],'sender_id'=>$sender_id);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_language_li_tpl_parsed);
                }
            }
        } else {
            if($userId == $_SESSION['user_id']){
                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ANY_REFERRAL_REVIEWS);

                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function getReferralModal($currentPage = 1, $main_page = false, $call_from_ajax = false,$platform='web',$app_user_id=0,$referral_id,$ref_id,$sender_id){
      $final_result='';

      $main_content = new MainTemplater(DIR_TMPL . $this->module . "/referrals-reviews-nct.tpl.php");
      $main_content = $main_content->parse();
      $fields=array("%REFERRAL_ID%","%SENDER_ID%","%RECEIVER_ID%","%REVIEW_DESCRIPTOIN%");

      $ref_data = $this->db->select('tbl_referral_reviews',array('id','referral_request_id','review_description'),array('referral_request_id'=>$ref_id,'receiver_id' => $sender_id))->result();
      $review_description = '';

      if (!empty($ref_data)) {
          $review_description = $ref_data['review_description'];
      }
      $replace=array($ref_id,$sender_id,$this->session_user_id,$review_description);

      $final_result=str_replace($fields, $replace, $main_content);

      return $final_result;
    }
    public function storeReferralReviews($referral_id,$sender_id,$receiver_id,$review_desc){
        $response = array();
        $response['status'] = '';
        $lastInsertId = '';
        
        $send_arr = array();
        if($referral_id > 0){
            
            $send_arr['sender_id'] = $this->session_user_id;
            $send_arr['receiver_id'] = $sender_id;
            $send_arr['referral_request_id'] = $referral_id;
            $send_arr['review_description'] = $review_desc;
            $send_arr['isApprovePublish'] = 'n';
            $send_arr['createdAt'] = date("Y-m-d H:i:s");

            $ref_data = $this->db->select('tbl_referral_reviews',array('id'),array('referral_request_id'=>$referral_id,'receiver_id' => $sender_id))->result();
            if ($ref_data['id'] > 0) {
                $lastInsertId= $this->db->update("tbl_referral_reviews", $send_arr,array('referral_request_id'=>$referral_id,'receiver_id' => $sender_id))->affectedRows();
            }else{
                 $lastInsertId = $this->db->insert("tbl_referral_reviews", $send_arr)->getLastInsertId();   
            }
            if($lastInsertId > 0){
                
                $user_data =$this->db->select("tbl_users", "*", array("id" => $sender_id))->result();

                $arrayCont = array();
                $arrayCont['greetings'] = $user_data['first_name'] . " " . $user_data['last_name'];
                
                generateEmailTemplateSendEmail("referral_request_accepted", $arrayCont, $user_data['email_address']);

                $data = array('isAccept' => 'a');
                $affectedRows= $this->db->update("tbl_send_referral_request", $data,array("id" => $referral_id))->affectedRows();
                if($affectedRows > 0){
                    $response['status'] = "suc";
                    $response['redirect_url'] = SITE_URL ."referral/";
                    $response['message'] = SUCCESS_REFERRAL_REVIEW_MESSAGE;
                }else{
                    $response['status'] = "err";
                    $response['redirect_url'] = SITE_URL ."referral/";
                    $response['message'] = ERROR_REFERRAL_REVIEW_MESSAGE;
                }
            }else{
                $response['status'] = "err";
                $response['redirect_url'] = SITE_URL ."referral/";
                $response['message'] = ERROR_REFERRAL_REVIEW_MESSAGE;
            }   
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."referral/";
            $response['message'] = ERROR_REFERRAL_REVIEW_MESSAGE;
        }
        return $response;
    }
    public function rejectReferralRequest($currentPage = 1, $main_page = false, $call_from_ajax = false,$platform='web',$app_user_id=0,$referral_id,$ref_id,$sender_id){
        $response = array();
        $response['status'] = "";
        
        $referral_data= $rej_data = array();
        $referral_data['isAccept'] = 'r';
        $referral_data['isActive'] = 'n';

        if($ref_id > 0){
             $affectedRows= $this->db->update("tbl_send_referral_request", $referral_data,array("id" => $ref_id, "sender_id" => $sender_id))->affectedRows();
            // $affectedRows = $this->db->delete("tbl_send_referral_request", array("id" => $ref_id, "sender_id" => $sender_id))->affectedRows();
            if($affectedRows > 0){
                
                $user_data =$this->db->select("tbl_users", "*", array("id" => $sender_id))->result();

                $arrayCont = array();
                $arrayCont['greetings'] = $user_data['first_name'] . " " . $user_data['last_name'];
                
                generateEmailTemplateSendEmail("referral_request_rejected", $arrayCont, $user_data['email_address']);

                $response['status'] = "suc";
                $response['redirect_url'] = SITE_URL ."referral/";
                $response['message'] = SUCCESS_REFERRAL_REJECT_REQUEST;
            }else{
                $response['status'] = "err";
                $response['redirect_url'] = SITE_URL ."referral/";
                $response['message'] = ERROR_REFERRAL_REJECT_REQUEST; 
            }
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."referral/";
            $response['message'] = ERROR_REFERRAL_REJECT_REQUEST_NOT_INSERTED;
        }
        return json_encode($response);
    }
    public function getReceivedReferralReviews($userId,$platform = 'web'){
        $final_result = '';
        
        $query = "SELECT sr.*,rr.id as reviewId, rr.review_description,rr.isApprovePublish FROM tbl_send_referral_request as sr LEFT JOIN tbl_referral_reviews as rr ON sr.id = rr.referral_request_id WHERE sr.sender_id = ? AND sr.isActive = 'y' AND sr.isAccept = 'a' AND ((rr.isApprovePublish = 'n') OR (rr.isApprovePublish = 'ar'))";

        $referrals = $this->db->pdoQuery($query,array($userId))->results();
        if (!empty($referrals)) {
            $actions = '';
            $single_language_li_tpl = new Templater(DIR_TMPL . $this->module . "/single-received-referral-reviews-nct.tpl.php");
    
            $fields = array("%REFERRALS_ID%","%REVIEW_DESCRIPTOIN%","%REVIEW_ID%");
            for ($i = 0; $i < count($referrals); $i++) {
                
                $referrals_id = filtering($referrals[$i]['id']);
                $review_description    = filtering($referrals[$i]['review_description']);
                $reviewId    = filtering($referrals[$i]['reviewId']);
                
                $fields_replace = array(
                    $referrals_id,
                    $review_description,
                    $reviewId
                );
                $single_language_li_tpl_parsed = $single_language_li_tpl->parse();
                
                if($platform == 'app'){
                    $array[] = array('referrals_id'=>$referrals[$i]['id'],'review_description'=>$referrals[$i]['review_description']);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_language_li_tpl_parsed);
                }
            }
        } else {
            if($userId == $_SESSION['user_id']){
                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ANY_RECEIVED_REVIEWS);

                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function approvePublishReferral($currentPage = 1, $main_page = false, $call_from_ajax = false,$platform='web',$app_user_id=0,$referral_id,$review_id){
        $response = array();
        $response['status'] = "";
        
        $referral_data=array();
        $referral_data['isApprovePublish'] = 'ap';
       
        if($referral_id > 0){
             $affectedRows= $this->db->update("tbl_referral_reviews", $referral_data,array("id" => $review_id,"referral_request_id" => $referral_id, "receiver_id" => $this->session_user_id))->affectedRows();
            if($affectedRows > 0){
                $response['status'] = "suc";
                $response['redirect_url'] = SITE_URL ."referral/";
                $response['message'] = SUCCESS_REFERRAL_APPROVE_AND_PUBLISH;
            }else{
                $response['status'] = "err";
                $response['redirect_url'] = SITE_URL ."referral/";
                $response['message'] = ERROR_REFERRAL_APPROVE_AND_PUBLISH; 
            }
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."referral/";
            $response['message'] = ERROR_REFERRAL_REVIEWS_NOT_INSERTED;
        }
        return json_encode($response);
    }
    public function resendReferralsRequest($currentPage = 1, $main_page = false, $call_from_ajax = false,$platform='web',$app_user_id=0,$referral_id,$review_id){
        $response = array();
        $response['status'] = "";
        
        $referral_data= $ref_update_data = array();
        $referral_data['isApprovePublish'] = 'ar';
        
        if($referral_id > 0){
             $affectedRows= $this->db->update("tbl_referral_reviews", $referral_data,array("id" => $review_id,"referral_request_id" =>$referral_id,"receiver_id" => $this->session_user_id))->affectedRows();
            if($affectedRows > 0){
                $ref_id = $this->db->select('tbl_referral_reviews',array('referral_request_id'),array('id'=>$review_id,'isApprovePublish' => 'ar'))->result();

                $ref_update_data['isAccept'] = 'n';
                $affectedRows1 = $this->db->update("tbl_send_referral_request", $ref_update_data,array("id" => $ref_id['referral_request_id']))->affectedRows();
                if($affectedRows1 > 0){
                    $response['status'] = "suc";
                    $response['redirect_url'] = SITE_URL ."referral/";
                    $response['message'] = SUCCESS_RESEND_REFERRAL_REQUEST;
                }else{
                    $response['status'] = "err";
                    $response['redirect_url'] = SITE_URL ."referral/";
                    $response['message'] = ERROR_RESEND_REFERRAL_REQUEST_NOT_EXISTS;
                }
            }else{
                $response['status'] = "err";
                $response['redirect_url'] = SITE_URL ."referral/";
                $response['message'] = ERROR_RESEND_REFERRAL_REQUEST_NOT_EXISTS; 
            }
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."referral/";
            $response['message'] = ERROR_RESEND_REFERRAL_REQUEST_NOT_EXISTS;
        }
        return json_encode($response);
    }
    public function getRightSidebar() {
        $final_content = '';
        $right_sidebar_tpl = new Templater(DIR_TMPL . $this->module . "/right-sidebar-nct.tpl.php");
        $right_sidebar_tpl_parsed = $right_sidebar_tpl->parse();
        $fields = array("%MEMBERSHIP_PLAN%","%JOINED_GROUPS%","%FOLLOWING_COMPANIES%","%APPLIED_JOBS%","%COMMON_CONNECTIONS%","%SIMILAR_PROFILES%");
        if ($this->session_user_id == $this->user_id) {
            $fields_replace = array(
                $this->getSubscribedMembershipPlan($this->session_user_id),
                $this->getJoinedGroups(),
                $this->getFollowingCompanies(),
                $this->getAppliedJobs(),
                "",
                "",
            );
        } else {
            $fields_replace = array(
                "",
                "",
                "",
                "",
                $this->getCommonConnectionsUL((int)$_GET['user_id']),
                $this->getSimilarProfileUL((int)$_GET['user_id']),
            );
        }
        $final_content = str_replace($fields, $fields_replace, $right_sidebar_tpl_parsed);
        return $final_content;
    }
    public function getJoinedGroups() {
        $final_content = $joined_groups_html = $content = '';
        $joined_groups_tpl = new Templater(DIR_TMPL . $this->module . "/joined-groups-nct.tpl.php");
        $query = "SELECT group_id FROM tbl_group_members WHERE user_id = ? AND action != ? AND action != ? ";
        $groups = $this->db->pdoQuery($query,array($this->user_id,'r','jr'))->results();
        if ($groups) {
            require_once(DIR_MOD.'common_storage.php');
            $group_storage = new storage();
            $joined_groups_tpl_carousel = new Templater(DIR_TMPL . $this->module . "/joined-groups-carousel-nct.tpl.php");
            $joined_groups_tpl_carousel_parsed = $joined_groups_tpl_carousel->parse();
            for ($i = 0; $i < count($groups); $i++) {
                $active_class = '';
                if ($i == 0) { $active_class = ' active ';}
                $group_id = filtering($groups[$i]['group_id'], 'input', 'int');
                $joined_groups_html .= getGroupCarouselItem($group_id, $active_class,$group_storage);
            }
            $fields = array("%JOINED_GROUPS_CAROUSEL_ITEMS%","%CAROUSEL_CONTROLS_HIDDEN_CLASS%");
            $fields_replace = array($joined_groups_html,count($groups) <= 1 ? "hidden" : "");
            $content .= str_replace($fields, $fields_replace, $joined_groups_tpl_carousel_parsed);
            $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
            $view_all_link_tpl->set('view_all_link', SITE_URL . "groups/joined-groups");
            $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
        } else {
             $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
                $message = ERROR_YOU_HAVE_NOT_JOINED_ANY_GROUP;
                $url=SITE_URL."search/groups";
                $label=LBL_JOIN_GROUP;
                $no_result_found_tpl=$no_result_found_tpl->parse();
                $fields=array("%MSG%","%URL%","%LABEL%");
                $fields_replace=array($message,$url,$label);
                $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);
            //$content .= ERROR_YOU_HAVE_NOT_JOINED_ANY_GROUP;
            $view_all_link_tpl_parsed = "";
        }
        $joined_groups_tpl->set('joined_groups', $content);
        $joined_groups_tpl->set('view_all_link', $view_all_link_tpl_parsed);
        $final_content = $joined_groups_tpl->parse();
        return $final_content;
    }
    public function getFollowingCompanies() {
        $final_content = $following_companies_html = $content = '';
        $following_companies_tpl = new Templater(DIR_TMPL . $this->module . "/following-companies-nct.tpl.php");
        $query = "SELECT company_id FROM tbl_company_followers WHERE user_id = ? ";
        $companies = $this->db->pdoQuery($query,array($this->user_id))->results();
        if ($companies) {
            require_once(DIR_MOD.'common_storage.php');
            $company_storage = new storage();
            for ($i = 0; $i < count($companies); $i++) {
                $active_class = '';
                if ($following_companies_html == "") { $active_class = ' active ';}
                $company_id = filtering($companies[$i]['company_id'], 'input', 'int');
                $following_companies_html .= getCompanyCarouselItem($company_id, $active_class,$company_storage);
            }
            if ($following_companies_html) {
                $following_company_tpl_carousel = new Templater(DIR_TMPL . $this->module . "/following-companies-carousel-nct.tpl.php");
                $following_company_tpl_carousel_parsed = $following_company_tpl_carousel->parse();
                $fields = array("%FOLLOWING_COMPANIES_CAROUSEL_ITEMS%","%CAROUSEL_CONTROLS_HIDDEN_CLASS%");
                $fields_replace = array($following_companies_html,count($companies) <= 1 ? "hidden" : "");
                $content = str_replace($fields, $fields_replace, $following_company_tpl_carousel_parsed);
                $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
                $view_all_link_tpl->set('view_all_link', SITE_URL . "company/following-companies");
                $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
            } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
                $message = SUCCESS_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $url=SITE_URL."search/companies";
                $label=LBL_FOLLOW_COMPANY;
                $no_result_found_tpl=$no_result_found_tpl->parse();
                $fields=array("%MSG%","%URL%","%LABEL%");
                $fields_replace=array($message,$url,$label);
                $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);
                //$content = ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $view_all_link_tpl_parsed = "";
            }
        } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
                $message = SUCCESS_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $url=SITE_URL."search/companies";
                $label=LBL_FOLLOW_COMPANY;
                $no_result_found_tpl=$no_result_found_tpl->parse();
                $fields=array("%MSG%","%URL%","%LABEL%");
                $fields_replace=array($message,$url,$label);
                $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);
            //$content = ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $view_all_link_tpl_parsed = "";
        }
        $following_companies_tpl->set('following_companies', $content);
        $following_companies_tpl->set('view_all_link', $view_all_link_tpl_parsed);
        $final_content = $following_companies_tpl->parse();
        return $final_content;
    }
    public function getAppliedJobs() {
        $final_content = $applied_for_jobs_html = $content = '';
        $applied_for_jobs_tpl = new Templater(DIR_TMPL . $this->module . "/applied-for-jobs-nct.tpl.php");
        $query = "SELECT job_id FROM tbl_job_applications WHERE user_id = ? ";
        $jobs = $this->db->pdoQuery($query,array($this->user_id))->results();
        if ($jobs) {
            require_once(DIR_MOD.'common_storage.php');
            $job_storage = new storage();
            $applied_for_jobs_tpl_carousel = new Templater(DIR_TMPL . $this->module . "/applied-for-jobs-carousel-nct.tpl.php");
            $applied_for_jobs_tpl_carousel_parsed = $applied_for_jobs_tpl_carousel->parse();
            for ($i = 0; $i < count($jobs); $i++) {
                $active_class = '';
                if ($i == 0) {$active_class = ' active ';}
                $job_id = filtering($jobs[$i]['job_id'], 'input', 'int');
                $applied_for_jobs_html .= getJobCarouselItem($job_id, $active_class, $job_storage);
            }
            $fields = array("%APPLIED_FOR_JOBS_CAROUSEL_ITEMS%","%CAROUSEL_CONTROLS_HIDDEN_CLASS%");
            $fields_replace = array($applied_for_jobs_html,count($jobs) <= 1 ? "hidden" : "");
            $content .= str_replace($fields, $fields_replace, $applied_for_jobs_tpl_carousel_parsed);
            $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
            $view_all_link_tpl->set('view_all_link', SITE_URL . "jobs/applied-jobs");
            $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
            $message = ERROR_YOU_HAVE_NOT_APPLIED_FOR_ANY_JOBS;
            $url=SITE_URL."search/jobs";
            $label=LBL_COM_DET_JOBS;
            $no_result_found_tpl=$no_result_found_tpl->parse();
            $fields=array("%MSG%","%URL%","%LABEL%");
            $fields_replace=array($message,$url,$label);
            $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);
           // $content .= ERROR_YOU_HAVE_NOT_APPLIED_FOR_ANY_JOBS;
            $view_all_link_tpl_parsed = "";
        }
        $applied_for_jobs_tpl->set('applied_for_jobs', $content);
        $applied_for_jobs_tpl->set('view_all_link', $view_all_link_tpl_parsed);
        $final_content = $applied_for_jobs_tpl->parse();
        return $final_content;
    }
    public function getCommonConnectionsUL($user_id) {
        $content = NULL;
        $common_connection_array = array();
        $common_connection_html = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/common-connection-ul-nct.tpl.php");
        $common_connection_html = NULL;
        $fields = array("%VIEW_ALL_LINK%");
        $common_connection_array = getCommonConnections($user_id, $this->session_user_id);
        $common_connection_count = count($common_connection_array);

        $common_connection_array = getCommonConnections($user_id, $this->session_user_id, true, 1, 2);
        if ($common_connection_array) {
            foreach ($common_connection_array as $key_connection => $value_connection) {
                $common_connection_html .= $this->getCommonConnectionSection($value_connection);
            }
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
            $message = LBL_NO_COMMON_CONNECTIONS_FOUND;
            $no_result_found_tpl->set('no_data_message', $message);
            $common_connection_html .= $no_result_found_tpl->parse();
        }
        $fields_replace = array(SITE_URL . "common-connection/" . encryptIt($user_id));
        $hidden_var = $common_connection_count < 3 ? "hidden" : "";
        $main_content->set('common_connection', $common_connection_html);
        $main_content->set('hidden_var', $hidden_var);
        $main_content_parsed = $main_content->parse();
        $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function getSimilarProfileUL($user_id) {
        $content = NULL;
        $similar_profile_array = array();
        $similar_profile_html = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/similar-profile-ul-nct.tpl.php");
        $fields = array();
        if($this->session_user_id>0)
            $similar_profile_array = '';
        //getSimilarProfiles($user_id, $this->session_user_id);
        $view_all_link_tpl_parsed = "";
        if ($similar_profile_array) {
            foreach ($similar_profile_array as $key => $value) {
                $similar_profile_html .= $this->getSimilarProfilesSection($value);
            }
            $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
            //$view_all_link_tpl->set('view_all_link', SITE_URL."search/users?relationship[]=2&industries[]=".$this->industry_id);
            $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
            $message = LBL_NO_SIMILAR_PROFILE_FOUND;
            $no_result_found_tpl->set('no_data_message', $message);
            $similar_profile_html .= $no_result_found_tpl->parse();
        }
        $fields_replace = array();
        $main_content->set('similar_profiles', $similar_profile_html);
        $main_content->set('view_all_link', $view_all_link_tpl_parsed);
        $main_content_parsed = $main_content->parse();
        $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
} ?>