<?php class postactivity extends Profile {
    function __construct($current_user_id,$platform='web') {
        parent::__construct();
        $this->platform = $platform;
        require_once(DIR_MOD . 'common_storage.php');
        $this->post_activity = new storage();
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
    }
    public function getFeedsLi($action, $user_id, $currentPage) {
        $final_result = NULL;
        $whereCondition = "";
        $order_by = 'ORDER BY id DESC';
        //$feed_act_id=0;
        switch ($action) {
            case "post_recent_updates": {
                $whereCondition = " AND type != 'a'  AND status = 'p' ";
                //$whereCondition = " AND status = 'p' ";
                $order_by = 'ORDER BY updated_on DESC';

                break;
            }
            case "post_published_posts": {
                $whereCondition = " AND type = 'a' AND status = 'p' AND posted_or_shared = 'p' ";
                //$whereCondition = " AND status = 'p' ";
                $order_by = 'ORDER BY updated_on DESC';

                break;
            }
            case "post_saved_posts": {
                $whereCondition = " AND type = 'a' AND status = 's' AND posted_or_shared = 'p' ";
                $order_by = 'ORDER BY updated_on DESC';

                //$whereCondition = " AND status = 's' AND posted_or_shared = 'p' ";
                break;
            }
            case "post_history": {
                $whereCondition = " AND ((type = 'u' AND status = 'p') or (type = 'a' AND status = 'p' AND posted_or_shared = 'p')) ";
                $order_by = 'ORDER BY updated_on DESC';
                break;
            }
            case "post_all_activity": {
                $whereCondition = " AND f.status = 'p'  ";
                $order_by = 'ORDER BY fa.addon DESC';
                break;
            }
        }

        $limit = 10;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        if($action=='post_all_activity'){
            $query = "SELECT  f.*,fa.id as feed_act_id FROM tbl_feeds as f 
            LEFT JOIN tbl_likes as l on (l.user_id=" .$user_id." and l.feed_id=f.id)
            LEFT JOIN tbl_comments as c on (c.user_id='".$user_id."' and c.feed_id=f.id) LEFT JOIN tbl_feed_activity as fa ON fa.feed_id=f.id WHERE (f.user_id = ? or l.user_id=? or c.user_id=?) AND fa.user_id=? " . $whereCondition . " $order_by ";
            $where_array=array($user_id,$user_id,$user_id,$user_id);
        }else{
            $query = "SELECT * FROM tbl_feeds  WHERE user_id = ?  " . $whereCondition . " $order_by ";
            $where_array=array($user_id);
        }
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $feeds = $this->db->pdoQuery($query_with_limit,$where_array)->results();


        $total_feeds = $this->db->pdoQuery($query,$where_array)->affectedRows();
        $page_data = getPagerData($total_feeds, $limit,$currentPage);
        $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total_feeds);
        if (count($feeds)) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_records = $this->db->pdoQuery($query_with_next_limit,$where_array)->results();
            $next_available_records = count($next_records);
            
            for ($i = 0; $i < count($feeds); $i++) {
                $con = getSingleFeed($this->post_activity,$feeds[$i]['id'],$this->platform,$this->current_user_id,'',$feeds[$i]['feed_act_id']);
                $final_result .= $con;
                $app_array[] = $con;
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                if($action=='post_recent_updates'){
                    $load_more_link = SITE_URL . "ajax/post_recent-updates/currentPage/" . ($currentPage + 1);
                }else if($action=='post_published_posts'){
                    $load_more_link = SITE_URL . "ajax/post_published-posts/currentPage/" . ($currentPage + 1);
                }else if($action=='post_saved_posts'){
                    $load_more_link = SITE_URL . "ajax/post_saved-posts/currentPage/" . ($currentPage + 1);
                }else if($action=='post_all_activity'){
                    $load_more_link = SITE_URL . "ajax/post_all_activity/currentPage/" . ($currentPage + 1);
                }else{
                    $load_more_link = SITE_URL . "ajax/post_recent-updates/currentPage/" . ($currentPage + 1);
                }
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_li_tpl->parse();
            }
        } else {
            if($action=='post_recent_updates'){
                $updatepost_tpl = new Templater(DIR_TMPL ."/updatepost_form-nct.tpl.php");
                $updatepost_tpl_parsed = $updatepost_tpl->parse();
                $final_result=$updatepost_tpl_parsed; 
            }else if($action=='post_published_posts'){
                $publishpost_tpl = new Templater(DIR_TMPL ."/publishpost-nct.tpl.php");
                $publishpost_tpl_parsed = $publishpost_tpl->parse();
                $fields = array("%LBL_TEXT%","%TEXT_BTN%");
                $fields_replace = array(LBL_WANT_TO_PUBLISH_POST,LBL_PUBLISH_A_POST);
                $final_result = str_replace($fields, $fields_replace, $publishpost_tpl_parsed);
        
            }
            else if($action=='post_saved_posts'){
                /*$nofeed_tpl = new Templater(DIR_TMPL ."/nofeed_found-nct.tpl.php");
                $nofeed_tpl_parsed = $nofeed_tpl->parse();
                $final_result=$nofeed_tpl_parsed;*/
                $publishpost_tpl = new Templater(DIR_TMPL ."/publishpost-nct.tpl.php");
                $publishpost_tpl_parsed = $publishpost_tpl->parse();
                $fields = array("%LBL_TEXT%","%TEXT_BTN%");
                $fields_replace = array(LBL_WANT_TO_SAVE_POST,LBL_SAVE_A_POST);
                $final_result = str_replace($fields, $fields_replace, $publishpost_tpl_parsed);
        

            }else{
                $nodata_tpl = new Templater(DIR_TMPL ."/nodata_found-nct.tpl.php");
                $nodata_tpl_parsed = $nodata_tpl->parse();
                $final_result=$nodata_tpl_parsed;
            }
        }
        if($this->platform == 'app'){
            $final_app_array['feeds'] = (!empty($app_array)?$app_array:array());
            $final_app_array['pagination'] = $pagination;
            $final_content = $final_app_array;
        } else {
            $final_content = $final_result;
        }
        return $final_content;
    }

    public function getFeeds($action, $user_id, $currentPage) {
        $final_result = NULL;
        $feeds_container_tpl = new Templater(DIR_TMPL . "feeds-container-nct.tpl.php");
        $feeds_container_tpl->set('feeds_li', $this->getFeedsLi($action, $user_id, $currentPage));
        $feeds_container_tpl_parsed = $feeds_container_tpl->parse();
        $fields = array("%LIKE_UNLIKE_URL%","%POST_COMMENT_URL%","%POST_AN_UPDATE_URL%");
        $fields_replace = array(SITE_URL . "like-unlike",SITE_URL . "post-comment",SITE_URL . "share-an-update");
        $final_result = str_replace($fields, $fields_replace, $feeds_container_tpl_parsed);
        return $final_result;
    }
    public function getRightSidebar() {
        $final_result = NULL;
        $user_cover = '';
        $right_sidebar_tpl = new Templater(DIR_TMPL . $this->module . "/right-sidebar-nct.tpl.php");
        $right_sidebar_tpl_parsed = $right_sidebar_tpl->parse();
        
        $fields=array('%EDIT_PROFILE_URL%','%USER_NAME_FULL%','%NO_OF_VISITORS%','%CONNECTIONS_URL%','%NO_OF_CONNECTIONS%',"%ADD_CONNECTION_URL%","%COVER_IMG%");
        $edit_profile_url = SITE_URL . "profile";
        $no_of_connections = getNoOfConnections($this->session_user_id);
        $connections_url = SITE_URL . "connection/" . encryptIt($this->session_user_id);
        $add_connection_url = SITE_URL . "people-you-may-know";
        
        $src_cover = "user_cover-nct/".$this->session_user_id."/";
        $cover_nm = getTableValue("tbl_users", "cover_photo", array("id" => $this->session_user_id));

        $user_cover_img = $this->post_activity->getImageUrl1('av8db','th1_'.$cover_nm,$src_cover);
        
        $up = getimagesize($user_cover_img);
        
        if (empty($up)) {
            $user_cover = '<span title="' . $_SESSION['first_name'].' ' .$_SESSION['last_name'].'" class="profile-picture-character">' . ucfirst($_SESSION['first_name'][0]) . '</span>';
        }else 
        {   
            $user_cover = $user_cover_img;
        }

        // $user_cover= getImageURL("user_cover_picture",$this->session_user_id,"th1",$platform);

        $fields_replace=array($edit_profile_url,
            filtering($_SESSION['first_name']) . " " . filtering($_SESSION['last_name']),
            //getUserHeadline($this->session_user_id),
            $no_of_visitors = getVisitors($this->session_user_id, "count"),
            $connections_url,
            $no_of_connections,
            $add_connection_url,
            $user_cover
        );
        
        $final_content = str_replace($fields, $fields_replace, $right_sidebar_tpl_parsed);

        return $final_content;
    }
    public function getpostactivityPageContent($action) {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%RECENT_UPDATES_ACTIVE_CLASS%",
            "%PUBLISHED_POSTS_ACTIVE_CLASS%",
            "%SAVED_POSTS_ACTIVE_CLASS%",
            "%ALL_ACTIVITY_CLASS%",
            "%FEEDS%",
            "%PROFILE_DATA%"
        );
        $recent_updates_active_class = $published_posts_active_class = $saved_posts_active_class = $all_activity_active_class="";
        switch ($action) {
            case "post_recent_updates": {
                $recent_updates_active_class = "active";
                break;
            }
            case "post_published_posts": {
                $published_posts_active_class = "active";
                break;
            }
            case "post_saved_posts": {
                $saved_posts_active_class = "active";
                break;
            }case "post_all_activity": {
                $all_activity_active_class = "active";
                break;
            }
        }
        $fields_replace = array(
            $recent_updates_active_class,
            $published_posts_active_class,
            $saved_posts_active_class,
            $all_activity_active_class,
            $this->getFeeds($action, $this->current_user_id, 1),
            $this->getRightSidebar()
        );
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
} ?>