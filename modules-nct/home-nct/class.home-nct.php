<?php class Home {
    public function __construct($current_user_id=0,$platform='web'){
        foreach($GLOBALS as $key=>$values){$this->$key=$values;}

        $_SESSION['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

        $this->platform = $platform;
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
    }
    public function getHeaderRight() {
        $final_result = "";
        $keyword = "";
        if (isset($_GET['keyword']) && $_GET['keyword'] != "") { $keyword = filtering($_GET['keyword']); }
        $selected_entity_class = "fa-user";
        $selected_entity_name = "users";
        if (isset($_GET['entity']) && $_GET['entity'] == "jobs") {
            $selected_entity_class = "fa-briefcase";
            $selected_entity_name = "jobs";
        } else if (isset($_GET['entity']) && $_GET['entity'] == "companies") {
            $selected_entity_class = "fa-building";
            $selected_entity_name = "companies";
        } else if (isset($_GET['entity']) && $_GET['entity'] == "groups") {
            $selected_entity_class = "fa-users";
            $selected_entity_name = "groups";
        }
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            global $objNotificationsGlobal;
            $notificationsReponse = $objNotificationsGlobal->getAllNotifications();
            $header_right = new Templater(DIR_TMPL . "header-right-after-login-nct.tpl.php");
            $header_right_parsed = $header_right->parse();
            $fields = array("%LOGIN_FORM%","%SITE_URL%","%MESSAGES%","%VIEW_ALL_MESSAGES_URL%","%MESSAGES_URL%","%COMPOSE_MESSAGE_URL%","%MESSAGE_COUNT%","%GENERAL_NOTIFICATIONS%","%JOB_NOTIFICATIONS%","%COMPANY_NOTIFICATIONS%","%GROUP_NOTIFICATIONS%","%NOTIFICATIONS_COUNT%","%CONNECTION_REQUESTS%","%CONNECTION_REQUESTS_COUNT%","%KEYWORD%","%SELECTED_ENTITY_CLASS%","%SELECTED_ENTITY_NAME%","%CLASS_NOT%","%CLASS_MSG%","%CLASS_CON%","%CLASS_HIDE_VIEW%");
            if($notificationsReponse['consersationFound']){$messageURL=SITE_URL."messaging";}
            else{$messageURL=SITE_URL."compose-message";}
            $class_not="blue-code";
            if($notificationsReponse['notifications_count']==''){
                $class_not='';
            }
            $class_msg='purple-code';
            if($notificationsReponse['messages_count']==''){
                $class_msg='';
            }
            $class_con='orange-code';
            if($notificationsReponse['connection_request_count']==''){
                $class_con='';
            }
            $class_hide_view='hidden';
            if($notificationsReponse['consersationFound'] != ''){
                $class_hide_view='';
            }
            $fields_replace = array($this->getLoginForm(),SITE_URL,$notificationsReponse['messages'],$messageURL,$messageURL,SITE_URL . "compose-message",$notificationsReponse['messages_count'],$notificationsReponse['general_notifications'],$notificationsReponse['job_notifications'],$notificationsReponse['company_notifications'],$notificationsReponse['group_notifications'],$notificationsReponse['notifications_count'],$notificationsReponse['connection_request'],$notificationsReponse['connection_request_count'],$keyword,$selected_entity_class,$selected_entity_name,$class_not,$class_msg,$class_con,$class_hide_view);
            $final_result = str_replace($fields, $fields_replace, $header_right_parsed);
        } else {
            $header_right = new Templater(DIR_TMPL . "header-right-before-login-nct.tpl.php");
            $header_right_parsed = $header_right->parse();
            $fields = array("%LOGIN_FORM%", "%SITE_URL%","%KEYWORD%","%SELECTED_ENTITY_CLASS%","%SELECTED_ENTITY_NAME%");
            $fields_replace = array($this->getLoginForm(), SITE_URL,$keyword,$selected_entity_class,$selected_entity_name);
            $final_result = str_replace($fields, $fields_replace, $header_right_parsed);
        }
        return $final_result;
    }
    public function getResendVerificationEmailPopup() {
        $final_result = "";
        if (!isset($_SESSION['user_id']) || filtering($_SESSION['user_id'], "input", "int") == 0) {
            $resend_email_verification_popup = new Templater(DIR_TMPL . "resend-email-verification-email-popup-nct.tpl.php");
            $final_result = $resend_email_verification_popup->parse();
        }
        return $final_result;
    }
    public function getSignupTpl(){
        $final_result = $sign_up_modal = "";
        $signup_hidden = 'hide';

        $sign_up_modal = new Templater(DIR_TMPL . $this->module . "/register-nct.tpl.php");
        $sign_up_modal1 = $sign_up_modal->parse();

        $fields = array('%SIGNUP_HIDDEN%','%TERMS_CONDITIONS_LINK%','%PRIVACY_LINK%');

        if(strpos($_SERVER['REQUEST_URI'], "signup") !== false){
            $signup_hidden = "";
        }else{
            $signup_hidden = 'hide';
        }

        $query = $this->db->pdoQuery("SELECT pageTitle,page_slug FROM tbl_content WHERE pId = 4")->result();

        $termsLink = '<a href="'.SITE_URL.'content/'.$query['page_slug'].'" target="_blank"> '.$query['pageTitle'].'</a>';
        
        $query1 = $this->db->pdoQuery("SELECT pageTitle,page_slug FROM tbl_content WHERE pId = 3")->result();

        $privacyLink = '<a href="'.SITE_URL.'content/'.$query1['page_slug'].'" target="_blank"> '.$query1['pageTitle'].'</a>';

        $fields_replace = array($signup_hidden,$termsLink,$privacyLink);
        $final_result = str_replace($fields, $fields_replace, $sign_up_modal1);
        return $final_result;   
    }
    public function getLoginForm() {
        $final_result = "";
        $login_form_tpl = new Templater(DIR_TMPL . "login-form-nct.tpl.php");
        $login_form_tpl_parsed = $login_form_tpl->parse();
        if (isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id']) && isset($_COOKIE['email_address']) && !empty($_COOKIE['email_address']) && isset($_COOKIE['password']) && !empty($_COOKIE['password'])) {
            $email_address = $_COOKIE['email_address'];
            $password = str_repeat('*',strlen(base64_decode($_COOKIE['password'])));
            $checked_status = 'checked="checked"';
        } else {
            $email_address = '';
            $password = '';
            $checked_status = '';
        }
        $fields = array('%LOGIN_EMAIL_ADDRESS%','%LOGIN_PASSWORD%','%CHECKED_STATUS%');
        $fields_replace = array(filtering($email_address),filtering($password),filtering($checked_status));
        $final_result = str_replace($fields, $fields_replace, $login_form_tpl_parsed);
        return $final_result;
    }
    public function getHeader() {
        $final_result = "";
        $user_id = "";
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '' && $_SESSION['user_id'] > 0) {
            $user_id = filtering($_SESSION['user_id'], "input", "int");
        }
        if ($this->module == 'home-nct') {$site_header = new Templater(DIR_TMPL . "header-home-nct.tpl.php");}
        else{$site_header = new Templater(DIR_TMPL . "header-nct.tpl.php");}
      //  $sticky_buttons_tpl = new Templater(DIR_TMPL . "sticky-buttons-nct.tpl.php");
        /*$contact_form_tpl = new Templater(DIR_TMPL . "contact-form-nct.tpl.php");
        $contact_form_tpl_parsed = $contact_form_tpl->parse();
        $fields = array("%FIRST_NAME%","%LAST_NAME%","%EMAIL_ADDRESS%","%READONLY%");
        $first_name = $last_name = $email_address = $readonly = '';
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "") {
            $user_id = filtering($_SESSION['user_id'], 'input', 'int');
            $first_name = filtering($_SESSION['first_name'], 'input');
            $last_name = filtering($_SESSION['last_name'], 'input');
            $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
            $readonly = ' readonly="readonly" ';
        }
        $fields_replace = array($first_name,$last_name,$email_address,$readonly);
        $contact_form_tpl_replaced = str_replace($fields, $fields_replace, $contact_form_tpl_parsed);
        $feedback_form_tpl = new Templater(DIR_TMPL . "feedback-form-nct.tpl.php");
        $feedback_form_tpl_parsed = $feedback_form_tpl->parse();
        $feedback_form_tpl_replaced = str_replace($fields, $fields_replace, $feedback_form_tpl_parsed);*/
        /*$sticky_fields = array(
            '%CONTACT_FORM%'            => $contact_form_tpl_replaced,
            '%FEEDBACK_FORM%'           => $feedback_form_tpl_replaced
        );
        $sticky_buttons_tpl_parsed = strtr($sticky_buttons_tpl->parse(), $sticky_fields);*/
        $site_header->set('module', $this->module);
        $site_header_parsed = $site_header->parse();
        if (!$user_id) {
            $site_header_parsed = strtr($site_header_parsed, array('%LOGIN_FORM%'=>$this->getLoginForm()));
        }
        $home_url = SITE_URL.($_SESSION['user_id'] > 0 ? 'dashboard' : '' );
        $fields = array("%SITE_URL%","%SITE_NM%","%SITE_LOGO_URL%","%HEADER_RIGHT%","%NAVIGATION_BAR_AFTER_LOGIN%",'%HOMR_URL%',/*'%STICKY_BUTTONS%',*/'%MSG_LIST%','%CLASS%','%CLASS_NAV_HIDE%');
        $navigation_bar_after_login_replaced = "";
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            $navigation_bar_after_login = new Templater(DIR_TMPL . "navigation-bar-after-login-nct.tpl.php");
            $navigation_bar_after_login_parsed = $navigation_bar_after_login->parse();
            $fields_navigation = array("%SITE_URL%","%PROFILE_URL%","%MY_UPDATES_URL%","%MY_CONNECTIONS_URL%","%PEOPLE_YOU_MAY_KNOW_URL%","%INVITATIONS_URL%","%MY_COMPANIES_URL%","%FOLLOWING_COMPANIES_URL%","%MY_JOBS_URL%","%APPLIED_JOBS_URL%","%SAVED_JOBS_URL%","%MY_GROUPS_URL%","%JOINED_GROUPS_URL%","%FOLLOWING_URL%","%FOLLOWER_URL%","%MY_POST_URL%","%OWNER_REFERRAL%");
            $fields_replace_navigation = array(SITE_URL,SITE_URL."profile",SITE_URL."recent-updates",SITE_URL."connection/".encryptIt($_SESSION['user_id']),SITE_URL."people-you-may-know",SITE_URL."invitation",SITE_URL."company/my-companies",SITE_URL."company/following-companies",SITE_URL."jobs/my-jobs",SITE_URL."jobs/applied-jobs",SITE_URL."jobs/saved-jobs",SITE_URL."groups/my-groups/".$_SESSION['user_id'],SITE_URL."groups/joined-groups",SITE_URL."following/".encryptIt($_SESSION['user_id']),SITE_URL."follower/".encryptIt($_SESSION['user_id']),SITE_URL."post_recent-updates",SITE_URL."referral");
            $navigation_bar_after_login_replaced = str_replace($fields_navigation, $fields_replace_navigation, $navigation_bar_after_login_parsed);
        }
        $class='hidden';

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {

        global $objNotificationsGlobal;
        $notificationsReponse = $objNotificationsGlobal->getAllNotifications();
        if($notificationsReponse['messages_count']>0)
        $class='';
        }
        $class_nav_hide='hidden';
        if($_SESSION['user_id']>0){
            $class_nav_hide='';
        }
        $msg_list_after_login_parsed = $this->getmsglist();
        $fields_replace=array(SITE_URL,SITE_NM,SITE_LOGO_URL,$this->getHeaderRight(),$navigation_bar_after_login_replaced,$home_url,/*$sticky_buttons_tpl_parsed,*/$msg_list_after_login_parsed,$class,$class_nav_hide);
        $final_result = str_replace($fields, $fields_replace, $site_header_parsed);
        return $final_result;
    }
    public function getmsglist(){
        $content = '';
        $user_id = filtering($_SESSION['user_id'], 'input', 'int');
       // $where_condition = " AND c.receiver_status = 'n' AND m.receiver_status = 'n' ";
        $query = "SELECT * FROM ( SELECT m.*
                FROM tbl_messages m
                WHERE m.receiver_id = ? AND m.receiver_status = ? AND m.is_read= ?
                ORDER BY m.id DESC
                 ) as table1
                 GROUP BY table1.conversation_id ORDER BY table1.id DESC  ";
        $getAllResults = $this->db->pdoQuery($query,array($user_id,'n','n'))->results();
        $totalRows = count($getAllResults);
        
        $getShowableResults = $this->db->pdoQuery($query,array($user_id,'n','n'))->results();
        if ($getShowableResults) {
           // $sql_with_next_limit = $query;
            //$next_messages = $this->db->pdoQuery($sql_with_next_limit)->results();
            //$next_available_records = count($next_messages);

            $msg_list = new Templater(DIR_TMPL . "msg_list_right-nct.tpl.php");
            $msg_list_after_login_parsed = $msg_list->parse();
            $field=array('%USER_IMG%','%USER_NAME%','%TIME%',"%MESSAGE_URL%");
            

            foreach ($getShowableResults as $message) {
                $message_date = $message['sent_on'];
                $time_ago = time_elapsed_string(strtotime($message['sent_on']));
                $action_by_user_id = filtering($message['sender_id'], 'input', 'int');
                if ($action_by_user_id > 0) {
                    $action_by_user_details = $this->db->select("tbl_users", "*", array("id" => $action_by_user_id))->result();
                    $action_by_user_name = filtering($action_by_user_details['first_name']) . " " . filtering($action_by_user_details['last_name']);
                }
                $message_url = SITE_URL . "messaging/thread/" . encryptIt(filtering($message['conversation_id'], 'input', 'int')).'/#message';
                
                $image_url = '';
                $user_img = DIR_NAME_USERS."/".$action_by_user_id."/";

                $user_pro_nm = getTableValue("tbl_users", "profile_picture_name", array("id" => $action_by_user_id));
                $u_first_name = getTableValue("tbl_users", "first_name", array("id" => $action_by_user_id));
                $u_last_name = getTableValue("tbl_users", "last_name", array("id" => $action_by_user_id));

                // $pro_img_url = $us_storage->getImageUrl1('av8db','th2_'.$user_pro_nm,$user_img);
                $pro_img_url = 'https://storage.googleapis.com/av8db/'.$user_img.'th2_'.$user_pro_nm;
                $up1 = getimagesize($pro_img_url);
                if (empty($up1)) {
                    $image_url = '<span title="'.$u_first_name.' '.$u_last_name.'" class="profile-picture-character">'.ucfirst($u_first_name[0]).'</span>';
                }else{
                    $image_url ='<picture>
                                    <source srcset="' . $pro_img_url . '" type="image/jpg">
                                    <img src="' . $pro_img_url . '" class="" alt="img" /> 
                                </picture>';
                }

                // $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th2");
                $user_img = $image_url;
                $fields_replace=array($user_img,$action_by_user_name,$time_ago,filtering($message_url));
                $content .= str_replace($field, $fields_replace, $msg_list_after_login_parsed);
            }
        }
        return $content;
    }
    public function getCMSPages() {
        $final_result = "";
        $get_content_pages = $this->db->select("tbl_content", "*", array("isActive" => 'y'))->results();
        if ($get_content_pages) {
            $pages_li = "";
            $footer_cms_page_li = new Templater(DIR_TMPL . "footer-cms-page-li-nct.tpl.php");
            $footer_cms_page_li_parsed = $footer_cms_page_li->parse();
            $fields = array("%PAGE_URL%","%PAGE_TITLE%","%EXTRA_ATTRIBUTE%");

            foreach ($get_content_pages as $single_page) {
                if (1 != $single_page['pId']) {
                    if($single_page['page_or_url'] == 'p') {
                        $page_url = SITE_URL . "content/" . $single_page['page_slug'];
                        $extra_attribute = ' ';
                    } else {
                        $page_url = filtering($single_page['page_url']);
                        $extra_attribute = ' target="_blank" ';
                    }
                    $pageTitle = $single_page['pageTitle_'.$this->lId];
                    $metaKeyword = $single_page['metaKeyword_'.$this->lId];
                    $metaDesc = $single_page['metaDesc_'.$this->lId];
                    $pageDesc = $single_page['pageDesc_'.$this->lId];


                    $fields_replace = array($page_url,$pageTitle,$extra_attribute);
                    if($this->platform == 'app'){
                        $app_cms_array[] = array(
                            'page_id'=>$single_page['pId'],
                            'pageTitle'=>$pageTitle,
                            'page_or_url'=>$single_page['page_or_url'],
                            'page_url'=>$single_page['page_url'],
                            'metaKeyword'=>$metaKeyword,
                            'metaDesc'=>$metaDesc,
                            'pageDesc'=>$pageDesc,
                            'isActive'=>$single_page['isActive']
                        );
                    } else {
                        $pages_li .= str_replace($fields, $fields_replace, $footer_cms_page_li_parsed);
                    }
                }

            }

            $footer_pages_ul = new Templater(DIR_TMPL . "footer-pages-ul-nct.tpl.php");
            $footer_pages_ul_pared = $footer_pages_ul->parse();
            $fields = array("%PAGES_LI%");
            $fields_replace = array($pages_li);
            $final_result = str_replace($fields, $fields_replace, $footer_pages_ul_pared);
        }
        if($this->platform=='app'){
            $app_array = (!empty($app_cms_array)?$app_cms_array:array());
            return array('pages'=>$app_array);
        } else {
            return $final_result;
        }
    }
    public function getFooterStatistics() {
        $final_result = "";
        $footer_statistics_tpl = new Templater(DIR_TMPL . "footer-statistics-nct.tpl.php");
        $footer_statistics_tpl_parsed = $footer_statistics_tpl->parse();
        $fields = array("%TOTAL_USERS%","%TOTAL_JOBS%","%TOTAL_COMPANIES%","%TOTAL_GROUPS%");
        $statisticsArray = getStatisticsArray();
        $fields_replace = array($statisticsArray['total_users'],$statisticsArray['total_jobs'],$statisticsArray['total_companies'],$statisticsArray['total_groups']);
        $final_result = str_replace($fields, $fields_replace, $footer_statistics_tpl_parsed);
        return $final_result;
    }
    public function getFooter($module) {
        $final_result = $languages ="";
        $footer_tpl = new Templater(DIR_TMPL . "footer-nct.tpl.php");
        $footer_tpl_parsed = $footer_tpl->parse();

        /*$is_daboard = ($module == 'dashboard-nct' && !isset($_GET['action'])) ? 'toggle-footer-section' : '';*/
        $is_daboard = 'toggle-footer-section';
        if($module=='home-nct')
        $is_daboard='';

        $lang_container = new Templater(DIR_TMPL . "select_option-nct.tpl.php");
        $lang_container_parsed = $lang_container->parse();
        $lang_fields = array('%VALUE%','%SELECTED%','%DISPLAY_VALUE%');
        $fields = array("%SITE_STATISTICS%","%SUBSCRIBE_URL%","%CMS_PAGES%","%COPYRIGHT%","%NCT_LOGO_URL%",'%LANGUAGES%','%CURRENT_LANGAUGE%','%DASHBOARD_ID%','%hide%','%LOGO_ATT%','%PLAY_STORE_LINK%','%PLAY_STORE_CLS%','%PLAYSTRORE_LOGO_URL%','%APPLE_STORE_LINK%','%APPLE_STORE_CLS%','%APPLE_LOGO_URL%');
        $currentLangauge = getTableValue('tbl_language','languageName',array('id'=>$this->lId,'status'=>'a'));

        $langs = $this->db->select('tbl_language',array('id','languageName'),array('status'=>'a'))->results();
        foreach($langs as $l=>$v){
            $languageName = $v['id'];
            $lang_selected = ($v['id'] == $this->lId) ? 'selected': '';
            $lang_disp_value = $v['languageName'];
            $lang_replace = array($languageName,$lang_selected,$lang_disp_value);
            if($this->platform == 'app') {
                $app_array[] = array('language_id'=>$v['id'],'language_title'=>$lang_disp_value);
            } else {
                $languages .= str_replace($lang_fields, $lang_replace, $lang_container_parsed);
            }
        }



        $footer_statistics = $hide =$logo_att ="";
        if(SHOW_FOOTER_STATISTICS) { $footer_statistics = $this->getFooterStatistics(); }
        if(isset($this->session_user_id) && $this->session_user_id>0){
            $hide = 'hide';
        }
        if($module!='home-nct'){
            $logo_att='rel="nofollow"';
        }
        $fields_replace = array(
            $footer_statistics,
            SITE_MOD . "home-nct/newsletter-subscribe-nct.php",
            $this->getCMSPages(),
            date("Y") . " " . SITE_NM,
            SITE_THEME_IMG . "nct-logo.png",
            $languages,
            $currentLangauge,
            $is_daboard,
            $hide,
            $logo_att,
            PLAY_STORE_LINK,
            PLAY_STORE_LINK==''?'hide':'',
            SITE_THEME_IMG . "google.png",
            APP_STORE_LINK,
            APP_STORE_LINK==''?'hide':'',
            SITE_THEME_IMG . "apple.png",

            
        );
        if($this->platform == 'app'){
            $final_result = $app_array;
        } else {
            $final_result = str_replace($fields, $fields_replace, $footer_tpl_parsed);
        }
        return $final_result;
    }
    public function processContactFeedbackForm($type, $user_id) {
        $response = array();
        $response['status'] = false;
        if ($user_id > 0) {
            $is_registered_user = 'y';
            //$user_id = filtering($_SESSION['user_id'], 'input', 'int');
            if($this->platform == 'app'){
                $first_name = filtering($_POST['c_first_name'], 'input');
                $last_name = filtering($_POST['c_last_name'], 'input');
                $email_address = filtering($_POST['c_email_address'], 'input');
            } else {
                $first_name = filtering($_SESSION['first_name'], 'input');
                $last_name = filtering($_SESSION['last_name'], 'input');
                $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
            }
        } else {
            $is_registered_user = 'n';
        }
        if ('c' == $type) {
            if (!$user_id) {
                $first_name = filtering($_POST['c_first_name'], 'input');
                $last_name = filtering($_POST['c_last_name'], 'input');
                $email_address = filtering($_POST['c_email_address'], 'input');
            }
            $subject = filtering($_POST['c_subject'], 'input');
            $message = filtering($_POST['c_message'], 'input');
        } else {
            if (!$user_id) {
                $first_name = filtering($_POST['f_first_name'], 'input');
                $last_name = filtering($_POST['f_last_name'], 'input');
                $email_address = filtering($_POST['f_email_address'], 'input');
            }
            $subject = "";
            $message = filtering($_POST['f_message'], 'input');
        }

        $feedback_array = array("type"=>$type,"is_registered_user"=>$is_registered_user,"first_name"=>$first_name,"last_name"=>$last_name,"email_address"=>$email_address,"subject"=>$subject,"message"=>$message,"date_added"=>date("Y-m-d H:i:s"));

        $insert_id = $this->db->insert("tbl_contact_us", $feedback_array)->getLastInsertId();
        $data = array();
        $data['admin_id'] = 1;
        $data['entity_id'] = $insert_id;
        if('c'==$type){$data['type']='cu';}else{$data['type']='fr';}
        $data['date_added'] = date('Y-m-d H:i:s');
        $this->db->insert('tbl_admin_notifications', $data);
        if ($insert_id) {
            $email_template_array = array();
            $email_template_array['full_name'] = filtering($first_name) . " " . filtering($last_name);
            $email_template_array['email_address'] = filtering($email_address);
            $email_template_array['message'] = filtering($message);
            if ('c' == $type) {
                $email_template_array['subject'] = filtering($subject);
                $email_template_name = "contact_us";
            } else {
                $email_template_name = "feedback";
            }
            generateEmailTemplateSendEmail($email_template_name, $email_template_array, ADMIN_EMAIL);
            $email_template_array = array();
            $email_template_array['greetings'] = filtering($first_name) . " " . filtering($last_name);
            if ('c' == $type) {$email_template_name = "thanks_for_contacting";}
            else{$email_template_name = "thanks_for_feedback";}
            generateEmailTemplateSendEmail($email_template_name, $email_template_array, $email_address);
            $response['status'] = true;
            if('f' == $type) {$response['success']=LBL_WE_HAVE_YOUR_FEEDBACk;}
            else{$response['success'] = LBL_WE_HAVE_YOUR_QUERY;}

            return $response;
        } else {
            $response['error'] = LBL_SEEMS_ISSUE_SENDING_YOUR . ( ( $type == 'f' ) ? LBL_FEEDBACK_S : LBL_QUERY);
        }
        return $response;
    }
    public function getSelectBoxOption() {$content = '';$main_content = new Templater(DIR_TMPL . "select_option-nct.tpl.php");$content = $main_content->parse();return sanitize_output($content);}
    
    public function getSubscribedMembershipPlan($user_id,$platform='web') {
        $final_content = '';

        $app_purchased_membership_plan_details = $app_purchased_adhoc_inmails_details = array();
        $app_purchased_membership_plan_details = array('name'=>'-','purchased_on'=>'-','inmails_received'=>'-','inmails_utilized'=>'-','inmails_outstanding'=>'-','no_of_remaining_days'=>'-');
        $app_purchased_adhoc_inmails_details = array('purchased_on'=>'-','adhoc_inmails_received'=>'-','inmails_utilized'=>'-','adhoc_inmails_outstanding'=>'-','no_of_remaining_days'=>'-','identifier_ios'=>'-','price_ios'=>'-');
        $app_final_content = array();
        $membership_plan_purchased = false;
        $purchased_membership_plan_details = $purchased_adhoc_inmails_details = "";
        $user_inmails = $this->db->select("tbl_user_inmails", "*", array("user_id" => $user_id))->result();
        
        if ($user_inmails) {

            $inmails_expires_on = strtotime($user_inmails['inmails_expires_on']);
            $adhoc_inmails_expires_on = strtotime($user_inmails['adhoc_inmails_expires_on']);

            if ($inmails_expires_on > time()) {
                $membership_plan_purchased = true;
                $query = "SELECT tp.plan_name_".$this->lId." as planName, sh.* FROM tbl_subscription_history sh LEFT JOIN tbl_tariff_plans tp ON sh.plan_id = tp.id WHERE sh.plan_type = ? AND sh.user_id = ? ORDER BY sh.id DESC ";
                $plan_details = $this->db->pdoQuery($query,array('r',$user_id))->result();
                $fields = array("%PLAN_NAME%","%PURCHASED_ON%","%INMAILS_RECEIVED%","%INMAILS_UTILIZED%","%INMAILS_OUTSTANDING%","%NO_OF_REMAINING_DAYS%");
                $inmails_received = filtering($user_inmails['inmails_received'], 'output', 'int');
                $inmails_outstanding = filtering($user_inmails['inmails_outstanding'], 'output', 'int');
                $inmails_utilized = $inmails_received - $inmails_outstanding;
                $no_of_remaining_days = getDateDiff(date("Y-m-d"), date("Y-m-d", $inmails_expires_on), 'day');
                $plan_name = filtering($plan_details['planName']);
                $date = convertDate('displayWeb', $plan_details['subscribed_on']);
                $fields_replace = array(
                    $plan_name,
                    $date,
                    $inmails_received,
                    $inmails_utilized,
                    $inmails_outstanding,
                    $no_of_remaining_days
                );
                if($platform=='app'){
                    $date = convertDate('onlyDate', $plan_details['subscribed_on']);
                    $app_purchased_membership_plan_details = array(
                        'name'=>$plan_name,
                        'purchased_on'=>$date,
                        'inmails_received'=>$inmails_received,
                        'inmails_utilized'=>$inmails_utilized,
                        'inmails_outstanding'=>$inmails_outstanding,
                        'no_of_remaining_days'=>$no_of_remaining_days
                    );
                } else {
                    if($this->module == 'dashboard-nct'){

                        $purchased_membership_plan_details_tpl = new Templater(DIR_TMPL . "dashboard-nct/purchased-membership-plan-details-nct.tpl.php");
                    }else{
                        $purchased_membership_plan_details_tpl = new Templater(DIR_TMPL . "purchased-membership-plan-details-nct.tpl.php");
                    }
                    $purchased_membership_plan_details_tpl_parsed = $purchased_membership_plan_details_tpl->parse();
                    $purchased_membership_plan_details = str_replace($fields, $fields_replace, $purchased_membership_plan_details_tpl_parsed);
                }

            }
            if ($adhoc_inmails_expires_on > time()) {
                $membership_plan_purchased = true;
                $query = "SELECT sh.* FROM tbl_subscription_history sh WHERE plan_type = ? AND user_id = ? ORDER BY sh.id DESC ";
                $plan_details = $this->db->pdoQuery($query,array('ah',$user_id))->result();
                $fields = array("%PLAN_NAME%","%PURCHASED_ON%","%INMAILS_RECEIVED%","%INMAILS_UTILIZED%","%INMAILS_OUTSTANDING%","%NO_OF_REMAINING_DAYS%");
                $adhoc_inmails_received = filtering($user_inmails['adhoc_inmails_received'], 'output', 'int');
                $adhoc_inmails_outstanding = filtering($user_inmails['adhoc_inmails_outstanding'], 'output', 'int');
                $adhoc_inmails_utilized = $adhoc_inmails_received - $adhoc_inmails_outstanding;
                $no_of_remaining_days = getDateDiff(date("Y-m-d"), date("Y-m-d", $adhoc_inmails_expires_on), 'day');
                $date = ($platform=='app' ) ? convertDate('onlyDate', $plan_details['subscribed_on']) : convertDate('displayWeb', $plan_details['subscribed_on']);
                $fields_replace = array(
                    filtering($plan_details['plan_name']),
                    $date,
                    $adhoc_inmails_received,
                    $adhoc_inmails_utilized,
                    $adhoc_inmails_outstanding,
                    $no_of_remaining_days
                );
                if($platform=='app'){
                    $app_purchased_adhoc_inmails_details = array(
                        'purchased_on'=>$date,
                        'adhoc_inmails_received'=>$adhoc_inmails_received,
                        'inmails_utilized'=>$adhoc_inmails_utilized,
                        'adhoc_inmails_outstanding'=>$adhoc_inmails_outstanding,
                        'no_of_remaining_days'=>$no_of_remaining_days,
                    );
                } else {
                    if($this->module == 'dashboard-nct'){
                        $purchased_membership_plan_details_tpl = new Templater(DIR_TMPL . "dashboard-nct/purchased-adhoc-inmails-details-nct.tpl.php");
                    }else{
                         $purchased_membership_plan_details_tpl = new Templater(DIR_TMPL . "purchased-adhoc-inmails-details-nct.tpl.php");
                    }
                    $purchased_membership_plan_details_tpl_parsed = $purchased_membership_plan_details_tpl->parse();
                    $purchased_adhoc_inmails_details = str_replace($fields, $fields_replace, $purchased_membership_plan_details_tpl_parsed);
                }

            }

            $final_content = $purchased_membership_plan_details . $purchased_adhoc_inmails_details;

        }

        if($platform=='web'){
            if (!$membership_plan_purchased) {
                $purchase_membership_plan_tpl = new Templater(DIR_TMPL . "purchase-membership-plan-nct.tpl.php");
                $final_content = $purchase_membership_plan_tpl->parse();
            }
        } else {
            $final_content=array();
            $final_content['purchased_membership_plan'] = $app_purchased_membership_plan_details;
            $final_content['purchased_adhoc_inmails'] = $app_purchased_adhoc_inmails_details;
            $final_content['identifier_ios']  = 'com.app.connectin.inMails';
            $final_content['price_ios']  = CURRENCY_SYMBOL.'0.99';
        }

        return $final_content;
    }
    public function getCompany($company_name) {
        $final_result = array();
        $query = "SELECT * FROM tbl_companies WHERE company_name LIKE '%" . $company_name . "%' AND status = ? AND company_type = ? ORDER BY id DESC LIMIT 0, 10 ";
        $companies = $this->db->pdoQuery($query,array('a','r'))->results();
        if ($companies) {
            for ($i = 0; $i < count($companies); $i++) {
                $single_company = array();
                $single_company['company_id'] = encryptIt(filtering($companies[$i]['id'], 'output', 'int'));
                $single_company['company_name'] = filtering($companies[$i]['company_name']);
                $final_result[] = $single_company;
            }
        }
        return $final_result;
    }
    public function getIndustryOptions($selected_industry_id = '') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
        $industries = $this->db->pdoQuery("SELECT * FROM tbl_industries WHERE status = ? ORDER BY id DESC",array('a'))->results();
        for ($i = 0; $i < count($industries); $i++) {
            $selected = ( ( ( $industries[$i]['id'] ) == $selected_industry_id ) ? "selected" : "" );
            $fields_replace = array(
                $industries[$i]['id'],
                $selected,
                $industries[$i]['industry_name']
            );
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    // public function getCompanySizeOption($selected_company_size_id = '') {
    //     $final_result = NULL;
    //     $getSelectBoxOption = $this->getSelectBoxOption();
    //     $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
    //     $company_sizes = $this->db->pdoQuery("SELECT * FROM tbl_company_sizes WHERE status = ?  ORDER BY id DESC",array('a'))->results();
    //     for ($i = 0; $i < count($company_sizes); $i++) {
    //         $selected = ( ( ( $company_sizes[$i]['id'] ) == $selected_company_size_id ) ? "selected" : "" );
    //         $fields_replace = array(
    //             $company_sizes[$i]['id'],
    //             $selected,
    //             $company_sizes[$i]['company_size'] . " (" . $company_sizes[$i]['minimum_no_of_employee'] . " - " . $company_sizes[$i]['maximum_no_of_employee'] . ")",
    //         );
    //         $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
    //     }
    //     return $final_result;
    // }
    public function getJobLocation($company_id) {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%DISPLAY_VALUE%");
        $query = "SELECT cl.*,l.formatted_address FROM tbl_company_locations cl LEFT JOIN tbl_locations l ON l.id = cl.location_id WHERE cl.company_id = ? ORDER BY cl.id DESC";
        $locations = $this->db->pdoQuery($query,array($company_id))->results();
        for ($i = 0; $i < count($locations); $i++) {
            $fields_replace = array($locations[$i]['location_id'],$locations[$i]['formatted_address']);
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    public function getMonthOption($selected_month_no = '') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
        $months_array = unserialize(MONTHS_ARRAY);
        for ($i = 0; $i < count($months_array); $i++) {
            $selected = ( ( ( $i + 1 ) == $selected_month_no ) ? "selected" : "" );
            $fields_replace = array(( $i + 1 ),$selected,$months_array[$i]);
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    public function getYearOption($selected_year = '') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%DISPLAY_VALUE%", "%SELECTED%");
        foreach (range((int) date("Y"), 1950) as $year) {
            $selected = $selected_year == $year ? 'selected' : '';
            $fields_replace = array($year,$year,$selected);
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content_parsed = $main_content_parsed = $main_content->parse();
        $industries = $this->getIndustryOptions();
        //$company_size = $this->getCompanySizeOption();
        $fromMonth = $this->getMonthOption();
        $fromYear = $this->getYearOption();
        $signupForm = $this->getSignupTpl();

        $fields = array('%INDUSTRY_OPTIONS%','%MONTH_OPTIONS_FROM%', '%MONTH_OPTIONS_TO%', '%YEAR_OPTIONS_FROM%',"%LOGIN_FORM%","%SIGN_UP_FORM%",'%TERMS_CONDITIONS_LINK%','%PRIVACY_LINK%');

        $query = $this->db->pdoQuery("SELECT pageTitle,page_slug FROM tbl_content WHERE page_slug LIKE '%terms%'")->result();

        $termsLink = '<a href="'.SITE_URL.'content/'.$query['page_slug'].'" target="_blank"> '.$query['pageTitle'].'</a>';
        
        $query1 = $this->db->pdoQuery("SELECT pageTitle,page_slug FROM tbl_content WHERE page_slug LIKE '%privacy%'")->result();

        $privacyLink = '<a href="'.SITE_URL.'content/'.$query1['page_slug'].'" target="_blank"> '.$query1['pageTitle'].'</a>';
        
        $fields_replace = array($industries,$fromMonth, $fromMonth, $fromYear,$this->getLoginForm(),$signupForm,$termsLink,$privacyLink);
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
}
?>