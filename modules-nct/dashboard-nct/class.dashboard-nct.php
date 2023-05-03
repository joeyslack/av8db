<?php

require_once(DIR_URL."image-vendor/vendor/autoload.php");

use Google\Cloud\Storage\StorageClient;


class Dashboard extends Profile {

    function __construct($current_user_id=0,$platform='web') {
        parent::__construct();

        $this->platform = $platform;
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);

        $getCurrentUsersIndustry = $this->db->pdoQuery("SELECT ue.licenses_id FROM tbl_users u
                    LEFT JOIN tbl_users_licenses_endorsement ue ON ue.user_id = u.id 
                    WHERE u.email_verified = ? AND u.status = ? AND u.id = ? ",array('y','a',$this->session_user_id))->result();

        $this->usersCurrentIndustry = '';
        if ($getCurrentUsersIndustry) {
            $this->usersCurrentIndustry = $getCurrentUsersIndustry['licenses_id'];
        }
        require_once('storage.php');
        $this->dashboard_storage = new storage();
        $this->app_feed_per_page = 10;
    }

    public function getRightSidebar() {
        $final_content = '';

        $right_sidebar_tpl = new Templater(DIR_TMPL . $this->module . "/right-sidebar-nct.tpl.php");
        $right_sidebar_tpl_parsed = $right_sidebar_tpl->parse();

        $fields=array();
        $fields_replace=array();

        $final_content = str_replace($fields, $fields_replace, $right_sidebar_tpl_parsed);

        return $final_content;
    }

    public function processShareUpdate() {
        $response = $feed_array = array();
        $response['status'] = false;
        $shared_feed_id = 0;
        $user_id = $this->current_user_id;
        if (isset($_POST['shared_feed_id']) && $_POST['shared_feed_id'] != "") {
            if($this->platform=='app'){
                $shared_feed_id = filtering($_POST['shared_feed_id'], 'input', 'int');
            } else {
                $shared_feed_id = filtering(decryptIt($_POST['shared_feed_id']), 'input', 'int');
            }
        } else {
            $response['error'] = ERROR_POST_SHARE;
            return $response;
        }

        $description = filtering($_POST['description_popup'], 'input', 'text');
        $feed_array['user_id'] = $user_id;
        $feed_array['description'] = $description;
        $feed_array['type'] = 'u';
        $feed_array['posted_or_shared'] = 's';
        $posted_or_shared = getTableValue("tbl_feeds", "posted_or_shared", array("id" => $shared_feed_id));
        if ($posted_or_shared == "s") {
            //$shared_feed_id = getTableValue("tbl_feeds", "shared_feed_id", array("id" => $shared_feed_id));
            $sharedFeedDetail = $this->db->select("tbl_feeds","*",array("id"=>$shared_feed_id))->result();
            if($sharedFeedDetail['shared_job_id']>0){
                $feed_array['shared_job_id'] = $sharedFeedDetail['shared_job_id'];
                $feed_array['shared_feed_id'] = $shared_feed_id;

            }elseif($sharedFeedDetail['shared_company_id']>0){
                $feed_array['shared_company_id'] = $sharedFeedDetail['shared_company_id'];
                $feed_array['shared_feed_id'] = $shared_feed_id;

            }elseif($sharedFeedDetail['shared_feed_id']>0){
                $feed_array['shared_feed_id'] = $sharedFeedDetail['shared_feed_id'];
            }else{
                $feed_array['shared_feed_id'] = $shared_feed_id;
            }
        }else{
         $feed_array['shared_feed_id'] = $shared_feed_id;
        }
        $shared_with = filtering($_POST['shared_with_popup'], 'input');
        if ($shared_with != "p" && $shared_with != "c") {
            $response['error'] = ERROR_SUPPLY_VALID_SHARING_STATUS;
            return $response;
        }
        $feed_array['shared_with'] = $shared_with;
        $feed_array['status'] = 'p';
        $feed_array['added_on'] = date("Y-m-d H:i:s");
        $feed_array['updated_on'] = date("Y-m-d H:i:s");
        $feed_title = getTableValue("tbl_feeds", "post_title", array("id" => $shared_feed_id));

        $lastInsertId = $this->db->insert("tbl_feeds", $feed_array)->getLastInsertId();
        $add_data=array(
                "user_id"=>$user_id,
                "feed_id"=>$lastInsertId,
                "status"=>"share",
                "addon"=>date("Y-m-d H:i:s")
            );
        $this->db->insert("tbl_feed_activity", $add_data)->getLastInsertId();
        if ($lastInsertId) {
            $feedPostedByUserId = getTableValue("tbl_feeds", "user_id", array("id" => $shared_feed_id));
            if ($feedPostedByUserId != $user_id) {
                $get_account_settings = $this->db->select("tbl_notification_settings", array('like_comment_share'), array("user_id" => $feedPostedByUserId))->result();
                if (!$get_account_settings) {
                    $lastId = $this->db->insert("tbl_notification_settings", array("user_id" => $feedPostedByUserId))->getLastInserId();
                    if ($lastId) {
                        $get_account_settings = $this->db->select("tbl_notification_settings", array('like_comment_share'), array("user_id" => $feedPostedByUserId))->result();
                    }
                }
                //if ($get_account_settings['like_comment_share'] == "y") {
                    $notificationArray = array(
                        "user_id" => $feedPostedByUserId,
                        "type" => "share",
                        "action_by_user_id" => $user_id,
                        "feed_id" => $shared_feed_id,
                        "added_on" => date("Y-m-d H:i:s"),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    $notiId = $this->db->insert("tbl_notifications", $notificationArray)->getLastInsertId();
                //}
                /* Push notification */
                $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$this->current_user_id))->result();
                $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                $push_data = array('user_name'=>$push_user_name,'feed_title'=>$feed_title,"feed_id" => $shared_feed_id,'notification_id'=>$notiId);
                set_notification($feedPostedByUserId,'share',$push_data);
            }
            $response['status'] = true;
            $response['success'] = ERROR_POST_SHARED;
            $response['shares_count']=getSharesCount(filtering(decryptIt($_POST['shared_feed_id']),'input','int'));
            return $response;
        } else {
            $response['error'] = ERROR_SOME_ISSUE_POSTING_UPDATE;
            return $response;
        }
    }

    public function processPostUpdate($posted_or_shared = "p", $type = "u", $status = "p", $shared_feed_id = "") {
        /*_print_r($_POST);
        _print_r($_FILES);
        exit;*/
        $response = $feed_array = array();
        $response['status'] = false;

        $user_id = $this->current_user_id;

        if (isset($_POST['post_description'])) {
            $_POST['description'] = $_POST['post_description'];
        }

        $description = filtering($_POST['description'], 'input', 'text');
        $description=preg_replace('#<script(.*?)>(.*?)</script>#is', '', $description);
        $img_nm = date('YmdHis') . '.original' . '.png';
        if (isset($_FILES['feed_image']) && !($_FILES['feed_image']['error'])) {
            $file_array = $_FILES["feed_image"];
            //list($width, $height, $type, $attr) = getimagesize($_FILES["Artwork"]['tmp_name']);
           // echo "<pre>";print_r();die;
            $image_resize_array = unserialize(FEED_IMAGE_RESIZE_ARRAY);
            $fsrc2 = 'feed-images-nct/';
            $result = $this->dashboard_storage->upload_object1('av8db',$_FILES['feed_image']['name'],$_FILES['feed_image']['tmp_name'],'feed-images-nct/');
            $src = $this->dashboard_storage->getImageUrl1('av8db',$_FILES['feed_image']['name'],$fsrc2);
            $ck = getimagesize($src);
            if (!empty($ck)) {
                $im2 = new Imagick($src);
                $im2->readImage($src);
                $im2->resizeImage(750, 450, Imagick::FILTER_LANCZOS, 1);
                $resize_img = $this->dashboard_storage->upload_objectBlob('av8db',$img_nm,$im2->getImageBlob(),$fsrc2);
                $im2->clear();
                $im2->destroy();

                $length = count($image_resize_array);
                for ($i = 0; $i < $length; $i++) {
                    $im1 = new Imagick($src);
                    $im1->readImage($src);
                   
                    $im1->resizeImage($image_resize_array[$i]['width'], $image_resize_array[$i]['height'], Imagick::FILTER_LANCZOS, 1);
                    $resize_img = $this->dashboard_storage->upload_objectBlob('av8db','th'.($i+1).'_'.$img_nm,$im1->getImageBlob(),$fsrc2);
                    $im1->clear();
                    $im1->destroy();
                }
                $del = $this->dashboard_storage->delete_object1('av8db',$_FILES['feed_image']['name'],'',$fsrc2); 
            }

            // $upload_dir = DIR_UPD_FEEDS;
            // $image_resize_array = unserialize(FEED_IMAGE_RESIZE_ARRAY);

            // $imageUploadResponse = uploadImage($file_array, $upload_dir, $image_resize_array);
            // compress(DIR_UPD_FEEDS.$imageUploadResponse['image_name'],DIR_UPD_FEEDS.$imageUploadResponse['image_name'],40);
            if (!$result) {
                $response['error'] = ERROR_POST_SOME_CONTENT_IMAGE;
                return $response;
            } else {
               /* $resieImage = resizeImage(DIR_UPD_FEEDS.$imageUploadResponse['image_name'],DIR_UPD_FEEDS.'urmi.jpg',600,$height,false);*/
                $feed_array['image_name'] = $img_nm;
                //$feed_array['image_name'] = $imageUploadResponse['image_name'];
            }
        }else if(isset($_POST['videocode']) && $_POST['videocode']){
            $videocode=$_POST['videocode'];
            

        } else {
            if ($description == "" && $status == "p" && $platform == 'web'){
                
                if($type != 'a'){
                    $response['error'] = ERROR_POST_SOME_CONTENT_IMAGE;
                    return $response;
                }

                if($type=='a'){
                    $response['error'] = ERROR_POST_SOME_CONTENT_IMAGE_PUBLISH;
                    return $response;
                }
            }
        }
        if(isset($_POST['is_image_removed']) && $_POST['is_image_removed'] == 'yes'){
            $feed_array['image_name'] = '';
        }

        $feed_array['user_id'] = $user_id;
        $feed_array['description'] = $description;

        $feed_array['post_title'] = isset($_POST['post_title']) ? $_POST['post_title']: "";

        $feed_array['company_id'] = isset($_POST['company_id']) ? decryptIt(filtering($_POST['company_id'], 'input', 'int')) : NULL;
        $feed_array['group_id'] = isset($_POST['group_id']) ? decryptIt(filtering($_POST['group_id'], 'input', 'int')) : NULL;

        if ($feed_array['group_id'] == NULL) {
            unset($feed_array['group_id']);
        }

        if ($feed_array['company_id'] == NULL) {
            unset($feed_array['company_id']);
        }

        $shared_with = filtering($_POST['shared_with'], 'input');
        if ($shared_with != "p" && $shared_with != "c") {
            $response['error'] = ERROR_SUPPLY_VALID_SHARING_STATUS;
            return $response;
        }

        $feed_array['shared_with'] = $shared_with;

        if ("s" == $posted_or_shared) {
            if (!$shared_feed_id) {
                $response['error'] = ERROR_ISSUE_SHARING_UPDATE;
                return $response;
            }
            $feed_array['shared_feed_id'] = $shared_feed_id;
        }

        $feed_array['type'] = $type;
        $feed_array['status'] = $status;
        $feed_array['added_on'] = date("Y-m-d H:i:s");
        $feed_array['updated_on'] = date("Y-m-d H:i:s");
        if(isset($_POST['videocode'])){
            $feed_array['video_code']=isset($_POST['videocode']) ? $_POST['videocode'] : "";
        }
       
        if(isset($_POST['post_id']) && $_POST['post_id'] > 0 && $platform !='web'){
                            
            if($_POST['video_remove']=='y'){
                
                $affectedRows = $this->db->update("tbl_feeds", array('video_code'=>''), array('id' => $_POST['post_id']))->affectedRows();
            }
            if($_POST['image_remove']=='y'){
                $image=$this->db->select('tbl_feeds','image_name',array('id'=>$_POST['post_id']))->result();

                unlink(DIR_UPD_FEEDS . $image['image_name']);
                unlink(DIR_UPD_FEEDS .'th1_'. $image['image_name']);

                $affectedRows = $this->db->update("tbl_feeds", array('image_name'=>''), array('id' => $_POST['post_id']))->affectedRows();

            }
        }
        
        if (isset($_POST['post_id']) && $_POST['post_id'] > 0) {
            $affectedRows = $this->db->update("tbl_feeds", $feed_array, array('id' => $_POST['post_id']))->affectedRows();
            if($status=='p'){
                 $add_data=array(
                "user_id"=>$user_id,
                "feed_id"=>$_POST['post_id'],
                "status"=>"post",
                "addon"=>date("Y-m-d H:i:s")
            );
            $this->db->insert("tbl_feed_activity", $add_data)->getLastInsertId();
            }

            if ($affectedRows) {
                $response['status'] = true;
                $response['success'] = ERROR_POST_UPDATED_SUCCESSFULLY;
                return $response;
            } else {
                $response['error'] = ERROR_SOME_ISSUE_POSTING_UPDATE;
                return $response;
            }
        } else {
            $feed_array['video_code']=isset($_POST['videocode']) ? $_POST['videocode'] : "";
            $lastInsertId = $this->db->insert("tbl_feeds", $feed_array)->getLastInsertId();
            if ($lastInsertId) {
                if($status=='p'){
                     $add_data=array(
                        "user_id"=>$user_id,
                        "feed_id"=>$lastInsertId,
                        "status"=>"post",
                        "addon"=>date("Y-m-d H:i:s")
                    );
                    $this->db->insert("tbl_feed_activity", $add_data)->getLastInsertId();
                }

                if (isset($feed_array['group_id']) && $feed_array['group_id'] != "") {
                    $groupMember = getGroupMember($feed_array['group_id']);
                    $groupMembers = explode(',', $groupMember);
                    $groupMembers[] = filtering(getTableValue("tbl_groups", "user_id", array("id" => $feed_array['group_id'])));
                    $gname = filtering(getTableValue("tbl_groups", "group_name", array("id" => $feed_array['group_id'])));
                    $notificationArray = array(
                        "feed_id" => $lastInsertId,
                        "group_id" => $feed_array['group_id'],
                        "type" => "nfg",
                        "action_by_user_id" => $this->current_user_id,
                        "added_on" => date("Y-m-d H:i:s"),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    for ($i = 0; $i < count($groupMembers); $i++) {
                        if ($groupMembers[$i] != $this->current_user_id) {
                            if($groupMembers[$i]!='' && $groupMembers[$i] > 0){
                                $notificationArray['user_id'] = $groupMembers[$i];
                                $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                                /* Push notification */
                                $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$this->current_user_id))->result();
                                $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                                $push_data = array('user_name'=>$push_user_name,'group_name'=>$gname,"feed_id" => $lastInsertId,
                                     "group_id" => $feed_array['group_id'],'notification_id'=>$notification_id);
                                set_notification($groupMembers[$i],'nfg',$push_data);
                            }
                        }
                    }
                }
                if (isset($feed_array['company_id']) && $feed_array['company_id'] != "") {
                    $company_followers = company_follower($feed_array['company_id']);
                    $company_followers = explode(',', $company_followers);

                    $company_followers[] = filtering(getTableValue("tbl_companies", "user_id", array("id" => $feed_array['company_id'])));
                    $cname = filtering(getTableValue("tbl_companies", "company_name", array("id" => $feed_array['company_id'])));
                    $notificationArray = array(
                        "feed_id" => $lastInsertId,
                        "company_id" => $feed_array['company_id'],
                        "type" => "nfc",
                        "action_by_user_id" => $this->current_user_id,
                        "added_on" => date("Y-m-d H:i:s"),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    for ($i = 0; $i < count($company_followers); $i++) {
                        if ($company_followers[$i] != $this->current_user_id) {
                            if($company_followers[$i]!='' && $company_followers[$i] > 0){
                                $notificationArray['user_id'] = $company_followers[$i];
                                $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                                /* Push notification */
                                $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$this->current_user_id))->result();
                                $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                                $push_data = array('user_name'=>$push_user_name,'company_name'=>$cname,"company_id" => $feed_array['company_id'],'notification_id'=>$notification_id);
                                set_notification($company_followers[$i],'nfc',$push_data);
                            }
                        }
                    }
                }


                $response['status'] = true;
                $response['success'] = ERROR_POST_HAS_BEEN_ADDED;
                $response['feed_id'] = $lastInsertId;
                return $response;
            } else {
                $response['error'] =ERROR_SOME_ISSUE_POSTING_UPDATE;
                return $response;
            }
        }
    }

    public function getLikersSharedBy($action, $feed_id, $currentPage = 1) {
        $response = array();
        $response['status'] = false;

        $likers_html = "";
        $limit = 10;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;

        if ("getLikers" == $action) {
            $sql = "SELECT l.user_id, u.first_name, u.last_name
                    FROM tbl_likes l
                    LEFT JOIN tbl_users u ON u.id = l.user_id
                    WHERE l.feed_id = ? ";
        } else {
            $sql = "SELECT f.user_id, u.first_name, u.last_name
                    FROM tbl_feeds f
                    LEFT JOIN tbl_users u ON u.id = f.user_id
                    WHERE f.shared_feed_id = ? ";
        }

        $sql_with_limit = $sql . " LIMIT " . $limit . " OFFSET " . $offset;
        $likers = $this->db->pdoQuery($sql_with_limit,array($feed_id))->results();

        if ($likers) {
            $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_likers = $this->db->pdoQuery($sql_with_next_limit,array($feed_id))->results();
            $next_available_records = count($next_likers);

            $liker_tpl = new Templater(DIR_TMPL . "liker-nct.tpl.php");
            $liker_tpl_parsed = $liker_tpl->parse();

            $fields = array(
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                // "%HEADLINE%",
            );

            for ($i = 0; $i < count($likers); $i++) {
                $user_profile_url = get_user_profile_url($likers[$i]['user_id']);
                $first_name = filtering(getTableValue("tbl_users", "first_name", array("id" => $likers[$i]['user_id'])));
                $last_name = filtering(getTableValue("tbl_users", "last_name", array("id" => $likers[$i]['user_id'])));

                $user_name_full = $first_name . " " . $last_name;

                $fields_replace = array(
                    getImageURL("user_profile_picture", $likers[$i]['user_id'], "th3"),
                    $user_profile_url,
                    ucwords($user_name_full),
                    //ucwords(getUserHeadline($likers[$i]['user_id']))
                );

                $likers_html .= str_replace($fields, $fields_replace, $liker_tpl_parsed);
            }

            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "getLikers/feed_id/" . encryptIt($feed_id) . "/currentPage/" . ($currentPage + 1);

                $load_more_li_tpl->set('load_more_link', $load_more_link);

                $likers_html .= $load_more_li_tpl->parse();
            }
        }

        $response['status'] = true;
        $response['likers'] = $likers_html;

        return $response;
    }

    public function getFeeds($user_id,$platform='web',$page=1) {
        // echo 'getFeeds 1';
        $final_result = NULL;
        $limitWeb = 10;
        $followinguseridArray = array();

        $connectionsQuery = $followingCompaniesQuery =$followinguserQuery ="";

        $followinguserid=$this->db->select("tbl_follower", array('follower_to'), array("follower_form" => $user_id,"status"=>'uf'))->results();

        // echo 'getFeeds 2';
        if ($followinguserid) {
            //$followinguseridArray = array();            

            for ($i = 0; $i < count($followinguserid); $i++) {
                $followinguseridArray[] = $followinguserid[$i]['follower_to'];
            }
        }


        $connections_array = getConnections($user_id);

        // echo 'getFeeds 3';
        if($followinguseridArray != '' && is_array($connections_array) && !empty($connections_array)){

            $connections_array_n=array_intersect($connections_array,$followinguseridArray);
           // $connections_array[] = $_SESSION['user_id'];
            $connections_array_imploded = implode(",", $connections_array_n);
            if($connections_array_imploded != ''){
                $followinguserQuery =" AND (user_id NOT IN ( " . $connections_array_imploded . " ))  ";

            }

        }
        $connections_array[] = $_SESSION['user_id'];

        // echo 'getFeeds 4';
        if (is_array($connections_array) && !empty($connections_array)) {
            $connections_array_imploded = implode(",", $connections_array);
            $connectionsQuery =" (user_id IN ( " . $connections_array_imploded . " ) AND shared_with = 'c' ) OR ";
        }
        // echo 'getFeeds 5';
        $followingCompanies = $this->db->select("tbl_company_followers", array('company_id'), array("user_id" => $user_id))->results();
        if ($followingCompanies) {
            $followingCompanyIdsArray = array();

            for ($i = 0; $i < count($followingCompanies); $i++) {
                $followingCompanyIdsArray[] = $followingCompanies[$i]['company_id'];
            }

            $followingCompanyIdsImploded = implode(",", $followingCompanyIdsArray);

            $connectionsQuery .= " ( company_id IN ( " . $followingCompanyIdsImploded . " ) ) OR ";
        }
        // echo 'getFeeds 6';
        $limit = $this->app_feed_per_page;
        $offset = ($page - 1 ) * $limit;

       // $query_without_limit = $query = "SELECT * FROM tbl_feeds WHERE ( " . $connectionsQuery . $followingCompaniesQuery . " shared_with = 'p' ) AND user_id != '" . $user_id . "' AND status = 'p' AND group_id IS NULL ORDER BY id DESC ";

        $query_without_limit = $query = $query1 = "SELECT * FROM tbl_feeds WHERE ( " . $connectionsQuery . $followingCompaniesQuery . " shared_with = 'p' ) ".$followinguserQuery." AND  status = 'p' AND group_id IS NULL ORDER BY updated_on DESC ";

        if($platform == 'web'){
            $offsetWeb = ($page - 1) * $limitWeb;
            $query_without_limit .= "limit $offsetWeb,$limitWeb";
            $query .= "limit $offsetWeb,$limitWeb";

            $query_with_next_limit = $query1 . " LIMIT " . $limitWeb . " OFFSET " . ( $offsetWeb + $limitWeb );
            $next_records = $this->db->pdoQuery($query_with_next_limit)->results();
            $next_available_records = count($next_records);

        }

        if($platform == 'app'){
            $query .= "limit $offset,$limit";
        }



        $feeds = $this->db->pdoQuery($query)->results();

        $total_feeds = $this->db->pdoQuery($query_without_limit)->affectedRows();

        $page_data = getPagerData($total_feeds, $this->app_feed_per_page,$page);
        $pagination = array('current_page'=>$page,'total_pages'=>$page_data->numPages,'total'=>$total_feeds);

        if ($feeds) {
            $feeds_container_tpl = new Templater(DIR_TMPL . "feeds-container-nct.tpl.php");
            $feeds_li = "";
            $app_array = array();
            for ($i = 0; $i < count($feeds); $i++) {
                $con = getSingleFeed($this->dashboard_storage,$feeds[$i]['id'],$platform,$user_id);
                $feeds_li .= $con;
                $app_array[] = $con;
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

            if($platform == 'app'){
                $final_app_array['feeds'] = $app_array;
                $final_app_array['pagination'] = $pagination;
                $final_result = $final_app_array;
            } else {
                $final_result = str_replace($fields, $fields_replace, $feeds_container_tpl_parsed);
                if ($next_available_records > 0) {
                    $load_more_li_tpl = new Templater(DIR_TMPL. $this->module  . "/load-more-nct.tpl.php");
                    $load_more_link = SITE_URL . "load-more-feeds/page/" . ($page + 1);
                    $load_more_li_tpl->set('load_more_link', $load_more_link);
                    $final_result .= $load_more_li_tpl->parse();
                }
            }


            return $final_result;
        }
        // echo 'getFeeds 7';
        $no_feeds_tpl = new Templater(DIR_TMPL . $this->module . "/no-feeds-nct.tpl.php");
        $final_result = $no_feeds_tpl->parse();
    }

    public function getDashboardPageContent() {
        $final_result = NULL;
        
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->set('right_sidebar', $this->getRightSidebar());
        $main_content->set('job_suggestions', $this->getJobSuggetions());
        $main_content->set('company_suggestions', $this->getCompanySuggetions());
        $main_content->set('group_suggestions', $this->getGroupSuggetions());
        $main_content->set('people_you_may_know', $this->getPeopleYouKnow());
        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%USER_NAME_FULL%",
            // "%HEADLINE%",
            "%EDIT_PROFILE_URL%",
            "%CONNECTIONS_URL%",
            "%NO_OF_CONNECTIONS%",
            "%ADD_CONNECTION_URL%",
            "%PUBLISH_POST_URL%",
            "%FEEDS%",
            "%POST_AN_UPDATE_URL%",
            "%NO_OF_VISITORS%",
            "%MEMBERSHIP_PLAN%",
            "%COVER_IMG%",
            "%IMG%"

        );
        // echo 'getDashboardPageContent 8';

        $edit_profile_url = SITE_URL . "profile";
        $connections_url = SITE_URL . "connection/" . encryptIt($this->session_user_id);
        $no_of_connections = getNoOfConnections($this->session_user_id);
        $add_connection_url = SITE_URL . "people-you-may-know";
        $publish_post_url = SITE_URL . "publish-post/".$this->session_user_id;
        // echo 'getDashboardPageContent 9';

        $post_an_update_url = SITE_URL . "post-an-update";
        $no_of_visitors = getVisitors($this->session_user_id, "count");
        // $user_cover= getImageURL("user_cover_picture",$this->user_id,"th1");
        $user_cover_name = getTableValue('tbl_users','cover_photo',array('id'=>$this->user_id));
        $user_cover = $this->dashboard_storage->getImageUrl1('av8db','th1_'.$user_cover_name,'user_cover-nct/'.$this->user_id.'/');
        $is_image = getimagesize($user_cover);
        // echo 'getDashboardPageContent 10';
        if(!empty($is_image)){
            $user_cover = $user_cover;
        }else{
            $user_cover = $this->dashboard_storage->getImageUrl('av8db','u-pro-bg.jpg');
        }
        // echo 'getDashboardPageContent 11';
        // $img=getImageURL("user_profile_picture", $this->session_user_id, "th4");
        $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$this->user_id));
        $img = $this->dashboard_storage->getImageUrl1('av8db','th4_'.$user_pro_pic_name,'users-nct/'.$this->user_id.'/');
        $is_image = getimagesize($img);
        $user_name = $this->db->pdoQuery('SELECT CONCAT(first_name," ",last_name) as user_name FROM tbl_users WHERE id = '.$this->user_id.'')->result();
        if(!empty($is_image)){
            $img = '<img src="'.$img.'" alt="'.$user_name['user_name'].'">';
        }else{
            $img ='<span class="profile-picture-character">'.ucfirst($user_name['user_name'][0]).'</span>';
        }
        // echo 'getDashboardPageContent 12';

        $fields_replace = array(
            ucwords(filtering($_SESSION['first_name'])) . " " . ucwords(filtering($_SESSION['last_name'])),
            //ucwords(getUserHeadline($this->session_user_id)),
            $edit_profile_url,
            $connections_url,
            $no_of_connections,
            $add_connection_url,
            $publish_post_url,
            $this->getFeeds($this->session_user_id),
            $post_an_update_url,
            $no_of_visitors,
            $this->getSubscribedMembershipPlan($this->session_user_id),
            $user_cover,
            $img
        );
        // echo 'getDashboardPageContent 13';

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        //print_r($final_result);exit();
        // echo 'getDashboardPageContent 14';exit;
        return $final_result;
    }

    public function getJobSuggetions() {

        $content = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/job-suggestions-nct.tpl.php");

        $query = 'SELECT DISTINCT(ue.licenses_id) FROM tbl_users_licenses_endorsement ue WHERE ue.user_id = ? ';

        $job_detail_array = $this->db->pdoQuery($query,array($this->session_user_id))->results();

        $new_array = array();

        if ($job_detail_array) {
            foreach ($job_detail_array as $key => $value) {
                $new_array[] = $value['licenses_id'];
            }
        }

        $industry_ids = '';
        if (is_array($new_array) && !empty($new_array)) {
            $industry_ids = implode(",", $new_array);
        } else {
            $industry_ids = 0;
        }
       // print_r($industry_ids);
        //die;
        //$industry_ids = 0;

        $query = "SELECT j.featured_till,j.id, j.user_id, j.company_id, j.job_title,j.relavent_experience_from,j.relavent_experience_to,j.is_featured, c.company_name,
                i.industry_name_".$this->lId." as industry_name ,jc.job_category_1,l.country,l.state,l.city1,l.city2,
                j.added_on, j.last_date_of_application,DATE_FORMAT(j.last_date_of_application,'%d-%b-%Y') as last_date,
                ja.job_id as job_applicants_job_id, ja.user_id as job_applicants_user_id, c.company_logo
                FROM tbl_jobs j
                LEFT JOIN tbl_companies c ON c.id = j.company_id
                LEFT JOIN tbl_industries i ON c.company_industry_id = i.id
                LEFT JOIN tbl_job_category jc ON j.job_category_id = jc.id
                LEFT JOIN tbl_locations l ON j.location_id = l.id
                LEFT JOIN tbl_job_applications ja ON ja.job_id = j.id
                LEFT JOIN tbl_job_license_hours jl ON j.id = jl.job_id
                LEFT JOIN tbl_users_licenses_endorsement ul ON jl.license_ids = ul.licenses_id
                WHERE jl.license_ids IN (".$industry_ids.")
                AND j.user_id != ?
                AND j.status = ?
                AND j.last_date_of_application >= CURDATE()
                GROUP BY j.id
                ORDER BY j.id DESC LIMIT 10  ";

        $job_detail_array = $this->db->pdoQuery($query,array($this->session_user_id,'a'))->results();
       // _print_r($job_detail_array);exit();
        //$job_detail_array = shuffle_assoc($job_detail_array);
        //_print($job_detail_array);exit;

        if ($job_detail_array) {
            foreach ($job_detail_array as $key => $value) {

                if ($value['job_applicants_user_id'] == $this->session_user_id && $value['id'] == $value['job_applicants_job_id']) {
                    continue;
                }

                $fields = array(
                    "%COMPANY_NAME%",
                    "%COMPANY_URL%",
                    "%COMPANY_LOGO_URL%",
                    "%INDUSTRY_NAME%",
                    "%JOB_CATEGORY%",
                    "%JOB_TITLE%",
                    "%SKILLS%",
                    "%LOCATION%",
                    "%POSTED_DATE%",
                    "%LAST_DATE_REMAINING%",
                    "%LAST_DATE%",
                    "%JOB_URL%",
                    "%REQUIRED_EXP_FROM%",
                    "%REQUIRED_EXP_TO%",
                    "%FEATURED%",
                    "%HIDE_SKILL%"
                );

                $company_url = get_company_detail_url($value['company_id']);
                $job_url = get_job_detail_url(filtering($value['id'], 'output', 'int'));
                // $company_logo_url = SITE_UPD_COMPANY_LOGOS . "th2_" . $value['company_logo'];
                // if (!file_exists(DIR_UPD_COMPANY_LOGOS . "th2_" . $value['company_logo'])) {
                //    $company_logo_url = '<span class="company-letter-square company-letter">'.ucfirst($value['company_name'][0]).'</span>';
                // }else{
                //      $img_arr= explode(".",$value['company_logo']);
                //      if(file_exists(DIR_UPD_COMPANY_LOGOS. "th2_" .$img_arr[0].".webp")){
                //         $company_logo_url_webp = SITE_UPD_COMPANY_LOGOS . "th2_" .$img_arr[0].".webp";
                //     }else{
                //         $company_logo_url_webp='';
                //     }

                //      $company_logo_url = '<picture>
                //                     <source srcset="'.$webp_path.'" type="image/webp">
                //                     <source srcset="' . $company_logo_url . '" type="image/jpg">
                //                     <img src="' . $company_logo_url . '" class="" alt="'.$value['company_name'].'" /> 
                //                 </picture>';
                //    // $company_logo_url = '<img src="'.$company_logo_url.'" alt="'.$value['company_name'].'">';
                // }

                $company_logo_url = $this->dashboard_storage->getImageUrl1('av8db','th2_'.$value['company_logo'],'company-logos-nct/');
                $is_image = getimagesize($company_logo_url);
                if(!empty($is_image)){
                    $company_logo_url = '<img src="'.$company_logo_url.'" alt="'.$value['company_name'].'">';
                }else{
                    $company_logo_url ='<span class="company-letter-square company-letter">'.ucfirst($value['company_name'][0]).'</span>';
                }

                //job skills
                // $qrySelSkills = $this->db->pdoQuery("SELECT skills.skill_name FROM tbl_job_skills jskills
                // LEFT JOIN tbl_skills skills ON skills.id = jskills.skill_id WHERE jskills.job_id = ? ",array(filtering($value['id'], 'output', 'int')))->results();

                // if ($qrySelSkills) {
                //     foreach ($qrySelSkills as $key_skills => $value_skills) {
                //         $skills[] = $value_skills['skill_name'];
                //     }

                //     $skill_name = implode(", ", $skills);
                // } else {
                //     $skill_name = '';
                // }

                //job location
                $city = $value['city1'] != '' ? $value['city1'] : $value['city2'];
                $state = $value['state'];
                $country = $value['country'];
                $location = $city . ", " . $state . ", " . $country;

                $last_date = countRemainingDays($value['last_date_of_application'], false);

                // echo $value['user_id'];echo $this->session_user_id; exit;
                if ($value['user_id'] != $this->session_user_id) {
                    if (getTotalRows('tbl_job_applications', "job_id = '" . $value['id'] . "' AND user_id = '" . $this->session_user_id . "'") == 0) {
                        $apply_content = $this->commonActionsUrl($value['id'], "apply_job");
                    } else {
                        $apply_content = $this->commonActionsUrl($value['id'], "remove_from_apply_job");
                    }
                    $main_content->set('apply_url', $apply_content);
                } else {
                    $main_content->set('apply_url', '');
                }

                //For featured button
                $featured = '';
                if ($value['is_featured'] == 'y' && $value['featured_till'] >= date('Y-m-d H:i:s')) {
                    $featured_tpl = new Templater(DIR_TMPL . $this->module . "/featured.tpl.php");
                    $featured = $featured_tpl->parse();
                }
                $class_hide_skill='';
                if($skill_name==''){
                    $class_hide_skill='hidden';
                }
                $fields_replace = array(
                    ucwords(filtering($value['company_name'], 'output')),
                    $company_url,
                    $company_logo_url,
                    ucwords(filtering($value['industry_name'], 'output')),
                    ucwords(filtering($value['job_category'], 'output')),
                    ucwords(filtering($value['job_title'], 'output')),
                    filtering($skill_name, 'output'),
                    filtering($location, 'output'),
                    time_elapsed_string(strtotime($value['added_on'])),
                    $last_date,
                    filtering($value['last_date'], 'output'),
                    $job_url,
                    filtering($value['relavent_experience_from'], 'output'),
                    filtering($value['relavent_experience_to'], 'output'),
                    $featured,
                    $class_hide_skill
                );

                $main_content_parsed = $main_content->parse();
                $content .= str_replace($fields, $fields_replace, $main_content_parsed);
            }
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = ERROR_NO_JOB_SUGGESTION_FOUND;
            $no_result_found_tpl->set('message', $message);
            $content .= $no_result_found_tpl->parse();
        }

        if ($content == '') {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = ERROR_NO_JOB_SUGGESTION_FOUND;
            $no_result_found_tpl->set('message', $message);
            $content .= $no_result_found_tpl->parse();
        }
        $user_company_add=$query = 'SELECT DISTINCT(ue.licenses_id) FROM tbl_users_licenses_endorsement ue WHERE ue.user_id = ? ';

        $user_company_add = $this->db->pdoQuery($query,array($this->session_user_id))->results();
        //print_r($user_company_add);exit();
        $user_company_add = '';
        if ($this->usersCurrentIndustry ==  "" && empty($user_company_add)) {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = ERROR_PLEASE_ADD_EXPERIENCE_TO_GET_THE_JOB_SUGGESTIONS;
            $no_result_found_tpl->set('message', $message);
            $content = $no_result_found_tpl->parse();
        }

        return $content;
    }

    public function getCompanySuggetions() {

        $content = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/company-suggestions-nct.tpl.php");

        // $query = 'SELECT DISTINCT(ue.industry_id) FROM tbl_user_experiences ue WHERE ue.user_id = ? ';

        // $company_detail_array = $this->db->pdoQuery($query,array($this->session_user_id))->results();

        $new_array = array();

        // if ($company_detail_array) {
        //     foreach ($company_detail_array as $key => $value) {
        //         $new_array[] = $value['industry_id'];
        //     }
        // }
        $industry_ids = '';
        if (is_array($new_array) && !empty($new_array)) {
            $industry_ids = implode("','", $new_array);
        } else {
            $industry_ids = 0;
        }

        $query = 'SELECT DISTINCT(company_id)
                FROM tbl_company_followers
                WHERE user_id = ? ';

        $company_array = $this->db->pdoQuery($query,array($this->session_user_id))->results();

        $new_array = array();

        if ($company_array) {
            foreach ($company_array as $key => $value) {
                $new_array[] = $value['company_id'];
            }
        }

        $company_ids = '';
        if (is_array($new_array) && !empty($new_array)) {
            $company_ids = implode("','", $new_array);
        } else {
            $company_ids = 0;
        }

        $query = "SELECT c.*,i.industry_name_".$this->lId." as industry_name,l.formatted_address,l.country,l.state,l.city1,l.city2,
                cf.company_id as follower_company_id, cf.user_id as follower_user_id
                FROM tbl_companies c
                LEFT JOIN tbl_industries i ON c.company_industry_id = i.id
                LEFT JOIN tbl_company_locations cl ON cl.company_id = c.id
                LEFT JOIN tbl_locations l ON cl.location_id = l.id                
                LEFT JOIN tbl_company_followers cf ON cf.company_id = c.id
                WHERE c.user_id != ?
                AND c.status = ?
                AND c.company_type = ?
                AND c.id NOT IN ('$company_ids')
                GROUP BY c.id
                ORDER BY c.id DESC LIMIT 10  ";

        $company_detail_array = $this->db->pdoQuery($query,array($this->session_user_id,'a','r'))->results();

        if ($company_detail_array) {
            foreach ($company_detail_array as $key => $value) {

                if ($value['follower_user_id'] == $this->session_user_id && $value['id'] == $value['follower_company_id']) {
                    continue;
                }

                $fields = array(
                    "%COMPANY_NAME%",
                    "%COMPANY_URL%",
                    "%COMPANY_LOGO_URL%",
                    "%INDUSTRY_NAME%",
                    "%LOCATION%",
                    // "%COMPANY_SIZE%",
                    "%LOCATION_HIDE%"
                );

                $company_url = get_company_detail_url($value['id']);
                // $company_logo_url = SITE_UPD_COMPANY_LOGOS . "th2_" . $value['company_logo'];
                // if (!file_exists(DIR_UPD_COMPANY_LOGOS . "th2_" . $value['company_logo'])) {
                //     $company_logo_url = '<span class="company-letter-square company-letter">'.ucfirst($value['company_name'][0]).'</span>';
                // }else{
                //     $img_arr= explode(".",$value['company_logo']);
                //      if(file_exists(DIR_UPD_COMPANY_LOGOS. "th2_" .$img_arr[0].".webp")){
                //         $company_logo_url_webp = SITE_UPD_COMPANY_LOGOS . "th2_" .$img_arr[0].".webp";
                //     }else{
                //         $company_logo_url_webp='';
                //     }

                //      $company_logo_url = '<picture>
                //                     <source srcset="'.$webp_path.'" type="image/webp">
                //                     <source srcset="' . $company_logo_url . '" type="image/jpg">
                //                     <img src="' . $company_logo_url . '" class="" alt="'.$value['company_name'].'" /> 
                //                 </picture>';
                //     //$company_logo_url = '<img src="'.$company_logo_url.'" alt="'.$value['company_name'].'">';
                // }

                $company_logo_url = $this->dashboard_storage->getImageUrl1('av8db','th2_'.$value['company_logo'],'company-logos-nct/');
                $is_image = getimagesize($company_logo_url);
                if(!empty($is_image)){
                    $company_logo_url = '<img src="'.$company_logo_url.'" alt="'.$value['company_name'].'">';
                }else{
                    $company_logo_url ='<span class="company-letter-square company-letter">'.ucfirst($value['company_name'][0]).'</span>';
                }

                //company location
                $city = $value['city1'] != '' ? $value['city1'] : $value['city2'];
                $state = $value['state'];
                $country = $value['country'];
                $location = $city . ", " . $state . ", " . $country;
                $location_hide='';
                if($city == '' && $state ==  '' && $country ==''){
                    $location_hide='hidden';
                }
                //$location=$value['formatted_address'];
                if ($value['user_id'] != $this->session_user_id && $value['follower_company_id'] == $value['id'] && $value['follower_user_id'] == $this->session_user_id) {

                   $main_content->set('follow_url', '');
                } else {
                     $follow_content = $this->commonActionsUrl($value['id'], "follow_company");
                    $main_content->set('follow_url', $follow_content);

                }

                $fields_replace = array(
                    ucwords(filtering($value['company_name'], 'output')),
                    $company_url,
                    $company_logo_url,
                    ucwords(filtering($value['industry_name'], 'output')),
                    filtering($location, 'output'),
                    //filtering($value['company_size'], 'output'),
                    $location_hide
                );

                $main_content_parsed = $main_content->parse();

                $content .= str_replace($fields, $fields_replace, $main_content_parsed);
            }
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = ERROR_NO_COMPANY_SUGGESTIONS_FOUND;
            $no_result_found_tpl->set('message', $message);
            $content .= $no_result_found_tpl->parse();
        }
        if ($content == '') {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = ERROR_NO_COMPANY_SUGGESTIONS_FOUND;
            $no_result_found_tpl->set('message', $message);
            $content .= $no_result_found_tpl->parse();
           // $content .= ERROR_NO_COMPANY_SUGGESTIONS_FOUND;
        }
        // $user_company_add=$query = 'SELECT DISTINCT(ue.industry_id) FROM tbl_user_experiences ue WHERE ue.user_id = ? ';

        // $user_company_add = $this->db->pdoQuery($query,array($this->session_user_id))->results();
        $user_company_add = '';
        if ($this->usersCurrentIndustry == "" && empty($user_company_add)) {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = ERROR_PLEASE_ADD_EXPERIENCE_TO_GET_THE_COMPANY_SUGGESTIONS;
            $no_result_found_tpl->set('message', $message);
            $content = $no_result_found_tpl->parse();
            //$content = ERROR_PLEASE_ADD_EXPERIENCE_TO_GET_THE_COMPANY_SUGGESTIONS;
        }
        return $content;
    }

    public function getmemberOfGroup()
    {
        $groupIds = '';
        $group = $this->db->pdoQuery("SELECT distinct gm.group_id
            FROM tbl_groups g
            LEFT JOIN tbl_group_members gm ON g.id = gm.group_id
            WHERE g.user_id != ?
            AND gm.user_id = ? AND ( gm.action = ? OR gm.action = ? OR gm.action = ? OR gm.action = ? )",array($this->session_user_id,$this->session_user_id,'aj','aa','a','jr'))->results();
        foreach ($group as $key => $value) {
            $groupIds .= ',' . $value['group_id'];
        }
        return substr($groupIds, 1);
    }

    public function getGroupSuggetions() {
        $content = NULL;
        //if ($this->usersCurrentIndustry != "") {

            // $query = 'SELECT DISTINCT(ue.industry_id) FROM tbl_user_experiences ue WHERE ue.user_id = ? ';

            // $group_detail_array = $this->db->pdoQuery($query,array($this->session_user_id))->results();

            $new_array = array();

            // if ($group_detail_array) {
            //     foreach ($group_detail_array as $key => $value) {
            //         $new_array[] = $value['industry_id'];
            //     }
            // }

            $industry_ids = '';
            if (is_array($new_array) && !empty($new_array)) {
                $industry_ids = implode("','", $new_array);
            } else {
                $industry_ids = 0;
            }

            $leftJoinQuery = $countQuery = $whereQuery = "";

            $memberOfGroup = $this->getmemberOfGroup();
            $memberOfGroup = ($memberOfGroup == '' ? 0 : $memberOfGroup);
             $countQuery = ", 0 as total_connected_members ";
            //echo $this->usersCurrentIndustry;die;
            $query = "SELECT g.*, u.id as creatorId, u.first_name, u.last_name, u.profile_picture_name, gt.group_type_".$this->lId." as group_type, count(distinct gm.id) as total_members ".$countQuery."
                        FROM tbl_groups g
                        LEFT JOIN tbl_users u ON u.id = g.user_id
                        LEFT JOIN tbl_group_types gt ON gt.id = g.group_type_id
                        LEFT JOIN tbl_group_members gm ON gm.group_id = g.id
                        ".$leftJoinQuery."
                        WHERE g.user_id != ?
                        AND g.status = ?
                        AND g.privacy = ?
                        AND g.id NOT IN (".$memberOfGroup.")
                        ".$whereQuery."
                        GROUP BY g.id
                        ORDER BY g.id DESC LIMIT 10 ";

            $groups = $this->db->pdoQuery($query,array($this->session_user_id,'a','pu'))->results();

            if ($groups) {
                $fields = array(
                    "%GROUP_NAME%",
                    "%GROUP_URL%",
                    "%GROUP_LOGO_URL%",
                   // "%INDUSTRY_NAME%",
                    "%GROUP_TYPE%",
                    "%GROUP_MEMBERS%",
                    "%CONNECTED_MEMBERS%",
                    "%CREATOR_NAME%",
                    "%CREATOR_PROFILE_IMAGE%"
                );

                $main_content = new Templater(DIR_TMPL . $this->module . "/group-suggestions-nct.tpl.php");

                for ($i = 0; $i < count($groups); $i++) {
                    $joined_group_content = "";
                    $group_url = get_group_detail_url($groups[$i]['id']);
                    // $group_logo_url = $group_logo_url_web = getImageURL("group_logo", $groups[$i]['id'], "th2");
                    // $group_logo_url_web = ($group_logo_url_web == '') ? '<span class="company-letter-square company-letter">'.ucfirst($groups[$i]['group_name'][0]).'</span>' : '<img src="'.$group_logo_url_web.'" alt="'.$groups[$i]['group_name'].'">';

                    $group_logo_img_name = getTableValue('tbl_groups','group_logo',array('id'=>$groups[$i]['id']));
                    $group_logo_url = $this->dashboard_storage->getImageUrl1('av8db','th2_'.$group_logo_img_name,'group-logos-nct/');
                    $is_image = getimagesize($group_logo_url);
                    if(!empty($is_image)){
                        $group_logo_url_web = $group_logo_url = '<img src="'.$group_logo_url.'" alt="'.$groups[$i]['group_name'].'">';
                    }else{
                        $group_logo_url_web = $group_logo_url ='<span class="company-letter-square company-letter">'.ucfirst($groups[$i]['group_name'][0]).'</span>';
                    }

                    if ($groups[$i]['accessibility'] == 'rj') {
                        $joined_group_content = $this->commonActionsUrl($groups[$i]['id'], "ask_to_join");
                    } else {
                        $joined_group_content = $this->commonActionsUrl($groups[$i]['id'], "join_group");
                    }
                    $main_content->set('joined_group_url', $joined_group_content);

                    $creator_name = '<a href=' . get_user_profile_url($groups[$i]['creatorId']) . '>' . ucwords($groups[$i]['first_name']) . ' ' . ucwords($groups[$i]['last_name']) . '</a>';
                    
                    $creator_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$groups[$i]['creatorId']));
                    $creator_profile_pic = $this->dashboard_storage->getImageUrl1('av8db','th3_'.$creator_pro_pic_name,'users-nct/'.$groups[$i]['creatorId'].'/');
                    $is_image = getimagesize($creator_profile_pic);
                    if(!empty($is_image)){
                        $creator_profile_pic = '<img src="'.$creator_profile_pic.'" alt="'.$groups[$i]['first_name'].' '.$groups[$i]['last_name'].'">';
                    }else{
                        $creator_profile_pic = '<span class="profile-picture-character">'.ucfirst($groups[$i]['first_name']).'</span>';
                    }

                    $creator_photo = '<a href=' . get_user_profile_url($groups[$i]['creatorId']) . '>' . $creator_profile_pic . '</a>';
                    $fields_replace = array(
                        ucwords(filtering($groups[$i]['group_name'], 'output')),
                        $group_url,
                        $group_logo_url_web,
                        //ucwords(filtering($groups[$i]['industry_name'], 'output')),
                        ucwords(filtering($groups[$i]['group_type'], 'output')),
                        filtering($groups[$i]['total_members'], 'output', 'int'),
                        filtering($groups[$i]['total_connected_members'], 'output', 'int'),
                        $creator_name,
                        $creator_photo
                    );

                    $main_content_parsed = $main_content->parse();

                    $content .= str_replace($fields, $fields_replace, $main_content_parsed);
                }
            } else {
                    $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                    $message = ERROR_NO_GROUP_FOUND_FOR_SUGGESTION;
                    $no_result_found_tpl->set('message', $message);
                    $content .= $no_result_found_tpl->parse();
                }
                if ($content == '') {
                    $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                    $message = ERROR_NO_GROUP_FOUND_FOR_SUGGESTION;
                    $no_result_found_tpl->set('message', $message);
                    $content .= $no_result_found_tpl->parse();

                }
                // $user_company_add=$query = 'SELECT DISTINCT(ue.industry_id)
                //         FROM tbl_user_experiences ue WHERE ue.user_id = ? ';

                // $user_company_add = $this->db->pdoQuery($query,array($this->session_user_id))->results();
                $user_company_add = '';
                if ($this->usersCurrentIndustry == "" && empty($user_company_add)) {
                    $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                    $message = ERROR_PLEASE_ADD_EXPERIENCE_TO_GET_THE_GROUP_SUGGESTIONS;
                    $no_result_found_tpl->set('message', $message);
                    $content = $no_result_found_tpl->parse();

                }
                return $content;

    }

    public function followCompany($company_id,$app_user_id=0,$platform='web') {
        $response = array();
        $response['status'] = false;
        $user_id = (($platform == 'app')?$app_user_id:$this->session_user_id);
        $follow_id = $this->db->insert('tbl_company_followers', array('company_id' => $company_id, 'user_id' => $user_id, 'added_on' => date('Y-m-d H:i:s')))->getLastInsertId();

        if ($follow_id) {
            $companyPostedUserId = getTableValue("tbl_companies", "user_id", array("id" => $company_id));
            $notificationArray = array(
                "user_id" => $companyPostedUserId,
                "type" => "fc",
                "action_by_user_id" => $user_id,
                "company_id" => $company_id,
                "added_on" => date("Y-m-d H:i:s"),
                "updated_on" => date("Y-m-d H:i:s")
            );
            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

            //For email notification
            $notificationStatus = getTableValue("tbl_notification_settings", "follow_company", array("user_id" => $companyPostedUserId));
            $company_name = getTableValue("tbl_companies", "company_name", array("id" => $company_id));
            if ($notificationStatus == 'y') {
                $from_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                $to_user = getTableValue("tbl_users", "first_name", array("id" => $companyPostedUserId));
                $email_address = getTableValue("tbl_users", "email_address", array("id" => $companyPostedUserId));
                $arrayCont['greetings'] = $to_user;
                $arrayCont['from_user'] = $from_user;
                $arrayCont['company_name'] = stripcslashes($company_name);
                generateEmailTemplateSendEmail("follow_company", $arrayCont, $email_address);
            }

            /* Push notification */
            $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
            $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
            $push_data = array('user_name'=>$push_user_name,'company_name'=>$company_name,"company_id" => $company_id,'notification_id'=>$notification_id);
            set_notification($companyPostedUserId,'fc',$push_data);

            $response['status'] = true;
            $response['msg'] = LBL_SUCCESSFULLY_FOLLOWED;
            $response['follow_count'] = $this->getCompanyFollowers($company_id);
        } else {
            $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }

        return $response;
    }

    public function addConnection($user_id) {
        $response = array();
        $response['status'] = false;
        $session_user_id = $this->current_user_id;
        $connection_id = getTableValue("tbl_connections", "id", array('request_from' => $session_user_id, 'request_to' => $user_id, 'status' => 's'));
        if($connection_id == ''){
            
            $connection_id = $this->db->insert('tbl_connections', array('request_from' => $session_user_id, 'request_to' => $user_id, 'status' => 's', 'added_on' => date('Y-m-d H:i:s')))->getLastInsertId();

            if ($connection_id) {
                $notificationStatus = getTableValue("tbl_notification_settings", "send_connection_request", array("user_id" => $user_id));
                if ($notificationStatus == 'y') {
                    $from_user = getTableValue("tbl_users", "first_name", array("id" => $session_user_id));
                    $to_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                    $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
                    $arrayCont['greetings'] = $to_user;
                    $arrayCont['from_user'] = $from_user;
                    generateEmailTemplateSendEmail("connection_request_received", $arrayCont, $email_address);
                }
                $response['status'] = true;
                $response['msg'] = LBL_CONNECTION_SENT;
            } else {
                $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
            }
        }else{
            $response['msg'] = ALREADY_SENT_REQ_CONNECTION;
        }

        return $response;
    }

    public function getPeopleYouKnow($currentPage = 1, $main_page = false, $call_from_ajax = false,$platform='web',$app_user_id=0,$keyword='') {
        $content = NULL;

        $common_connection_array = array();
        $common_connection_html = NULL;
        $next_available_records = 0;
        $limit = NO_OF_PEOPLE_YOU_KNOW_PER_PAGE;
        if($platform == 'app'){
            $limit=10;
        }
        $offset = ($currentPage - 1 ) * $limit;
        $wherecon='';
        if ($main_page) {
            if ($call_from_ajax) {
                $main_content = new Templater(DIR_TMPL . $this->module . "/people-you-know-main-ajax-nct.tpl.php");
            } else {
                $main_content = new Templater(DIR_TMPL . $this->module . "/people-you-know-main-nct.tpl.php");
            }
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/people-you-know-nct.tpl.php");
        }
        if($platform == 'app'){
            $session_id = $app_user_id;
        } else {
            $session_id = $this->session_user_id;
        }

        // $query = 'SELECT DISTINCT(ue.industry_id) FROM tbl_user_experiences ue WHERE ue.user_id = ? ';

        // $connection_detail_array = $this->db->pdoQuery($query,array($session_id))->results();

        $new_array = array();

        // if ($connection_detail_array) {
        //     foreach ($connection_detail_array as $key => $value) {
        //         $new_array[] = $value['industry_id'];
        //     }
        // }

        if (is_array($new_array) && !empty($new_array)) {
            $industry_ids = implode(",", $new_array);
        } else {
            $industry_ids = 0;
        }

        $connected_user_ids_arr = getConnections($session_id);
        if (is_array($connected_user_ids_arr) && !empty($connected_user_ids_arr)) {
            $connected_user_ids = implode(",", $connected_user_ids_arr);
        } else {
            $connected_user_ids = 0;
        }

        $user_id_arr = getSecondDegreeConnections($session_id);

        if (is_array($user_id_arr) && !empty($user_id_arr)) {
            $connection_user_ids = implode(",", $user_id_arr);
        } else {
            $connection_user_ids = 0;
        }
        
        if ($keyword != '') {
             $wherecon .= 'AND (u.first_name like "%'.$keyword.'%" OR u.last_name like "%' . $keyword . '%" or concat(u.first_name," ",u.last_name) like "%' . $keyword . '%" ) ';
        }

       $query = "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) as user_name
                  FROM tbl_users u WHERE u.id NOT IN (".$connected_user_ids.")
                  AND u.id != ?
                  AND u.status = ? ".$wherecon."
                  group by u.id ORDER BY  CASE WHEN u.location_id THEN 1 ELSE 2 END , u.id ASC";
        $whrExtArr = array($session_id,'a');

        if($connected_user_ids == 0 ){
            $query = "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) as user_name
                  FROM tbl_users u WHERE u.id != ? AND u.status = ? ".$wherecon." group by u.id ORDER BY  CASE WHEN   u.location_id THEN 1 ELSE 2 END  , u.id ASC";


            $whrExtArr = array($session_id,'a');
        }

        $totalRows = $this->db->pdoQuery($query,$whrExtArr)->affectedRows();

        if ($main_page) {
            $query_with_limit = $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;
        } else {
            $query_with_limit = $query . ' LIMIT 10';
        }

        $connection_detail_array = $this->db->pdoQuery($query,$whrExtArr)->results();
        //print_r($connection_detail_array);exit();
        $people_you_know_count = count($connection_detail_array);

        if($connected_user_ids == 0 ){
            $people_you_know_count= NO_OF_COUNT_PEOPLE_FOR_RANDOM;
        }
        $connection_detail_array = $this->db->pdoQuery($query_with_limit,$whrExtArr)->results();
        //print_r($connection_detail_array);exit();
        $app_array[] = '';
        if ($main_page) {

            $people_you_may_know_html = NULL;
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
             $connection_count_load = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();
             $next_available_records = count($connection_count_load);
             //print_r($connection_count_load);exit();
            if ($connection_detail_array != '') {
                foreach ($connection_detail_array as $key_connection => $value_connection) {
                    $getpuknow = $this->getAllPeopleYouKnow($value_connection['id'],$session_id,$platform);
                    $people_you_may_know_html .= $getpuknow;
                    if($platform == 'app'){
                        $app_array[] = $getpuknow;
                    }
                }
                // print_r($people_you_may_know_html);exit();
            } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                if (empty($connection_detail_array)) {
                        $message = LBL_NO_SUGGESTION;

                }else{
                        $message = ERROR_PLEASE_ADD_EXPERIENCE_TO_GET_THE_SUGGESTIONS;

                }

                $no_result_found_tpl->set('message', $message);
                $people_you_may_know_html .= $no_result_found_tpl->parse();
            }
            if ($next_available_records > 0 ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getPeopleYouKnow_load/currentPage/" . ($currentPage + 1);

                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $people_you_may_know_html .= $load_more_li_tpl->parse();
            }
            //print_r($people_you_may_know_html);exit();
            $main_content->set('people_you_may_know', $people_you_may_know_html);
            $main_content->set('pagination', getPagination($people_you_know_count, count($connection_detail_array), NO_OF_PEOPLE_YOU_KNOW_PER_PAGE, $currentPage));

            $main_content_parsed = $main_content->parse();
            $fields = array("%PROFILE_DATA%","%NAV_MENU%");
            $fields_replace = array($this->getRightSidebarLeft($session_id),$this->getnavmenu($session_id,'getPeopleYouKnow')
            );

            if($platform == 'app'){

                $content['ppluknow'] = (!empty($app_array)?$app_array:array());
                $page_data = getPagerData($totalRows, $limit,$currentPage);
                $content['ppluknow_pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);

            } else {
                //$final_result = str_replace($fields, $fields_replace, $main_content_parsed);
                $content .= str_replace($fields, $fields_replace, $main_content_parsed);
            }
        } else {
            //$connection_detail_array = shuffle_assoc($connection_detail_array);

            if ($connection_detail_array) {
                foreach ($connection_detail_array as $key => $value) {
                    $common_connection_html = NULL;

                    $user_url = get_user_profile_url($value['id']);
                    $fields = array(
                        "%USER_IMAGE%",
                        "%USER_URL%",
                        "%USER_NAME%",
                        // "%USER_HEAD_LINE%",
                        "%ENCRYPTED_USER_ID%",
                        "%NO_OF_COMMON_CONNECTIONS%",
                        "%VIEW_ALL_LINK%",
                        "%HIDE_CONNECTION_CLS%"
                    );

                    $common_connection_array = getCommonConnections($value['id'], $session_id);
                    $common_connection_count = count($common_connection_array);

                    $common_connection_array = getCommonConnections($value['id'], $session_id, true, 1, 3);


                    foreach ($common_connection_array as $key_connection => $value_connection) {
                        $common_connection_html .= $this->getCommonConnectionSection($value_connection);
                    }
                    $hide_connection_cls='hidden';
                    if($common_connection_count > 0){
                        $hide_connection_cls='';
                    }

                    $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$value['id']));
                    $user_image = $this->dashboard_storage->getImageUrl1('av8db','th3_'.$user_pro_pic_name,'users-nct/'.$value['id'].'/');
                    $is_image = getimagesize($user_image);
                    if(!empty($is_image)){
                        $user_image = '<img src="'.$user_image.'" alt="'.$value['user_name'].'">';
                    }else{
                        $user_image = '<span class="profile-picture-character">'.ucfirst($value['user_name'][0]).'</span>';
                    }

                    $fields_replace = array(
                        $user_image,
                        $user_url,
                        ucwords(filtering($value['user_name'], 'output')),
                        //ucwords(getUserHeadline($value['id'])),
                        encryptIt($value['id']),
                        $common_connection_count,
                        SITE_URL . "common-connection/" . encryptIt($value['id']),
                        $hide_connection_cls
                    );


                    $hidden_var = $common_connection_count <= 3 ? "hidden" : "";
                    $main_content->set('common_connection', $common_connection_html);

                    $main_content->set('hidden_var', $hidden_var);

                    $main_content_parsed = $main_content->parse();

                    $content .= str_replace($fields, $fields_replace, $main_content_parsed);
                }
            }else{
               // $content = no_user_found;
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                $message = no_user_found;

                $no_result_found_tpl->set('message', $message);
                $content .= $no_result_found_tpl->parse();

            }
        }

/*        if ($this->usersCurrentIndustry == "" && $main_page == false) {
            $content = ERROR_PLEASE_ADD_EXPERIENCE_TO_GET_THE_SUGGESTIONS;
        }
*/
        return $content;
    }

    public function getCommonConnectionSection($user_id) {

        $content = NULL;
        $hide = ($this->module == 'dashboard-nct' && !isset($_GET['action'])) ? 'hide' : '';

        $main_content = new Templater(DIR_TMPL . $this->module . "/common-connection-section-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $user_info = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $user_id))->result();
        //print_r($user_info);exit();
        $fields = array(
            "%USER_NAME%",
            "%USER_IMG%",
            // "%USER_HEAD_LINE%",
            "%USER_URL%",
            "%is_hide%"
        );

        $fields_replace = array(
            ucwords(filtering($user_info['first_name'], 'output')) . " " .ucwords(filtering($user_info['last_name'], 'output')),
            getUserProfilePictureURL($user_id, "th2"),
            //ucwords(getUserHeadline($user_id)),
            get_user_profile_url($user_id),
            $hide
        );
        //print_r($fields_replace);exit();
        $content .= str_replace($fields, $fields_replace, $main_content_parsed);

        return $content;
    }

    public function getCommonConnectionsPageContent($user_id, $currentpage = 1, $call_from_ajax = false) {
        $final_result = NULL;
        $common_connection_html = NULL;

        if ($call_from_ajax) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/common-connection-ajax-nct.tpl.php");
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/common-connection-nct.tpl.php");
        }


        $common_connection_array = getCommonConnections($user_id, $this->session_user_id);
        $common_connection_count = count($common_connection_array);

        $common_connection_array = getCommonConnections($user_id, $this->session_user_id, true, $currentpage, NO_OF_COMMON_CONNECTION_PER_PAGE);

        foreach ($common_connection_array as $key_connection => $value_connection) {
            $common_connection_html .= $this->getAllCommonConnection($value_connection);
        }

        $page_data = getPagerData($common_connection_count, NO_OF_COMMON_CONNECTION_PER_PAGE,$currentpage);

        if ($page_data->numPages > 0 && $page_data->numPages > $currentpage ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getCommonConnection_load/currentPage/" . ($currentpage + 1)."/".$user_id;

                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $common_connection_html .= $load_more_li_tpl->parse();
        }
        $main_content->set('common_connection', $common_connection_html);
        $main_content->set('user_id', $user_id);
        $main_content->set('pagination', getPagination($common_connection_count, count($common_connection_array), NO_OF_COMMON_CONNECTION_PER_PAGE, $currentpage));

        $user_info = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $user_id))->result();

        $fields = array(
            "%USER_NAME%",
        );

        $fields_replace = array(
            ucwords(filtering($user_info['first_name'], 'output')) . " " . ucwords( filtering($user_info['last_name'], 'output')),
        );

        $main_content_parsed = $main_content->parse();
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getAllCommonConnection($user_id) {
        $content = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/common-connection-all-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $user_info = $this->db->select('tbl_users', array('id,first_name,last_name'), array('id' => $user_id))->result();

        $fields = array(
            "%USER_ID%",
            "%SESSION_USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            // "%USER_HEAD_LINE%",
            "%USER_PROFILE_URL%",
            "%ENCRYPTED_USER_ID%",
        );

        $fields_replace = array(
            filtering($user_info['id'], 'output', 'int'),
            $this->session_user_id,
            ucwords(filtering($user_info['first_name'], 'output')) . " " .ucwords( filtering($user_info['last_name'], 'output')),
            getUserProfilePictureURL($user_id, "th4"),
            //ucwords(getUserHeadline($user_id)),
            get_user_profile_url($user_id),
            encryptIt(filtering($user_info['id'], 'output', 'int')),
        );

        $content .= str_replace($fields, $fields_replace, $main_content_parsed);

        return $content;
    }


    public function getConnectionsPageContent($user_id, $currentpage = 1, $call_from_ajax = false, $keyword = '',$platform = 'web') {

        $final_result = NULL;
        $connection_html = NULL;
        $common_connection_array = array();
        $next_available_records = 0;
        $limit = NO_OF_CONNECTION_PER_PAGE;
        $offset = ($currentpage - 1 ) * $limit;
        //$connections_array[] = '';

        if ($call_from_ajax) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/connection-ajax-nct.tpl.php");
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/connection-nct.tpl.php");
        }

        if($platform == 'app'){
            $session_id = $app_user_id;
        } else {
            $session_id = $this->session_user_id;
        }

        if ($keyword != '') {
             $wherecon .= 'and case when ut.id='.$user_id.' then (uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%" ) when uf.id=' . $user_id . ' then (ut.first_name like "%' . $keyword . '%" OR ut.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%") end';
        }
        $query = "select * from tbl_connections as uc left join tbl_users as uf ON(uf.id = uc.request_from) left join tbl_users as ut ON(ut.id = uc.request_to) WHERE ( request_from = ? OR request_to = ? ) AND uc.status = ? AND uf.status=? AND ut.status=? " . $wherecon . " ";

        $whrExtArr=array($user_id,$user_id,'a','a','a');


        $totalRows = $this->db->pdoQuery($query,$whrExtArr)->affectedRows();
        $query_with_limit = $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;
           $connection_detail_array = $this->db->pdoQuery($query,$whrExtArr)->results();
        $connection_count_total=count($connection_detail_array);

        $connection_detail_array = $this->db->pdoQuery($query_with_limit,$whrExtArr)->results();

        if ($connection_detail_array) {
            for ($i = 0; $i < count($connection_detail_array); $i++) {
                $request_from = $connection_detail_array[$i]['request_from'];
                $request_to = $connection_detail_array[$i]['request_to'];
                if ($request_from == $user_id) {
                    $connections_array[] = $request_to;
                } else {
                    $connections_array[] = $request_from;
                }
            }
        }
        $connection_count = count($connections_array);

        if(!empty($connections_array))
            $connection_string = implode(',', $connections_array);
        else
            $connection_string = 0;

        $uData = $this->db->pdoQuery('select id,first_name,last_name,location_id,profile_picture_name from `tbl_users` where id in ('.$connection_string.')')->results();

        foreach ($uData as $key_connection => $value_connection) {
            $con_htm = $this->getAllConnection($value_connection['id'],$value_connection,$platform);
            $connection_html .= $con_htm;
            if($platform == 'app'){
                $app_array_main[] = $con_htm;
            }
        }
        if ($connection_html == '' && $call_from_ajax == true) {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = NO_FOLLOWING_FOUND;

            $no_result_found_tpl->set('message', $message);
            $connection_html .= $no_result_found_tpl->parse();
        }
        if ($connection_html == '') {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-connection-found-nct.tpl.php");
            $message = NO_CONNECTION_FOUND_MSG;

            $no_result_found_tpl->set('message', $message);
            $connection_html .= $no_result_found_tpl->parse();
        }
        
        $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
        $connection_count_load = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();
        $next_available_records = count($connection_count_load);
        if ($next_available_records > 0 ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getConnection_load/currentPage/" . ($currentpage + 1)."/".$keyword;

                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $connection_html .= $load_more_li_tpl->parse();
        }
        $main_content->set('connection', $connection_html);
        $main_content->set('user_id', $user_id);

        $pagination = getPagination($connection_count, count($connection_array), NO_OF_CONNECTION_PER_PAGE, $currentpage);
        if($platform == 'app'){
            $page_data = getPagerData($connection_count_total, NO_OF_CONNECTION_PER_PAGE,$currentpage);

            //pagination=current_page,total_pages,total
            $app_pagination = array('current_page'=>$currentpage,'total_pages'=>$page_data->numPages,'total'=>$connection_count_total);

        }
        $main_content->set('pagination', $pagination);

        $user_info = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $user_id))->result();

        $fields = array("%USER_NAME%","%PROFILE_DATA%","%NAV_MENU%");
        $fields_replace = array(
            ucwords(filtering($user_info['first_name'], 'output')) . " " .ucwords( filtering($user_info['last_name'], 'output')),$this->getRightSidebarLeft($user_id),$this->getnavmenu($user_id,'getConnection')
        );

        $main_content_parsed = $main_content->parse();
        if($platform == 'app'){
            $app_array['pagination'] = $app_pagination;
            $app_array['connections'] = $app_array_main;
            $final_result = $app_array;
        } else {
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $final_result;
        }
        public function getnavmenu($user_id,$action) {
                $final_content = '';

                $right_sidebar_tpl = new Templater(DIR_TMPL . $this->module . "/nav_menu-nct.tpl.php");
                $right_sidebar_tpl_parsed = $right_sidebar_tpl->parse();

                $fields=array(
                    "%HIDE_TAB%",
                    "%CONNECTION_CLASS%",
                    "%PEOPLE_YOU_MAY_KNOW_CLASS%",
                    "%FOLLOWING_CLASS%",
                    "%FOLLOWER_CLASS%"

                );

                $hide_tab="hidden";
                if($user_id == $this->current_user_id){
                    $hide_tab='';
                }
                $connection_class=$people_you_may_know_class=$following_class=$follower_class='';
                if($action=='getConnection'){
                    $connection_class='active';
                }else if($action=='getPeopleYouKnow'){
                    $people_you_may_know_class='active';
                }else if($action=='getFollowing'){
                    $following_class='active';
                }else if($action=='getFollower'){
                    $follower_class='active';
                }

                $fields_replace=array(
                    $hide_tab,
                    $connection_class,
                    $people_you_may_know_class,
                    $following_class,
                    $follower_class



                );

                $final_content = str_replace($fields, $fields_replace, $right_sidebar_tpl_parsed);

                return $final_content;
        }


        public function getAllConnection($user_id,$user_info,$platform) {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/connection-all-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $removeButton = '';
        if ($user_id != $this->session_user_id) {
            $removeButton .= '<a class="" href="javascript:void(0);" id="remove_connection" data-value="' . encryptIt($user_id) . '" title="{LBL_REMOVE_FROM_CONNECTION}">
            {LBL_DASHBOARD_REMOVE_CONNECTION}
            </a>';
        }
        $fields = array(
            "%USER_ID%",
            "%SESSION_USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            // "%USER_HEAD_LINE%",
            "%USER_PROFILE_URL%",
            "%ENCRYPTED_USER_ID%",
            "%REMOVE_BUTTON%",
            "%USER_LOCATION%",
            "%NUMBER_OF_SHARED_CONNATION%",
            "%MESSAGE_URL%",
            "%MESSAGE_TEXT%",
            "%FOLLOW_TAG%",
            "%USER_F_ID%",
            "%USER_STATUS%"
        );

        if($user_info['location_id'] == "")
            $user_info['location_id']=0;


        $connected_user_ids = getConnections($user_id);
        if (is_array($connected_user_ids) && in_array($this->session_user_id, $connected_user_ids)) {
            $send_inmail_url = SITE_URL . "compose-message/" . encryptIt($user_id);
            $send_inmail_text = LBL_SEND_MESSAGE;
        } else {
            $send_inmail_url = SITE_URL . "compose-message/" . encryptIt($user_id);
            $send_inmail_text = LBL_SEND_MESSAGE;
        }
        $loc = getTableValue("tbl_locations", "formatted_address", array("id" => $user_info['location_id']));
        $status=$getstatus='';
        $follow_tag=LBL_FOLLOW;
        $getstatus = getTableValue("tbl_follower", "status", array("follower_form" =>$this->current_user_id,'follower_to'=>$user_info['id']));
        if($getstatus != ''){
                $status=$getstatus;
                if($getstatus=='f')
                $follow_tag=LBL_MYC_FOLLOWING;
        }
        $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$user_info['id']));
        $user_image = $this->dashboard_storage->getImageUrl1('av8db','th4_'.$user_pro_pic_name,'users-nct/'.$user_info['id'].'/');
        $is_image = getimagesize($user_image);
        if(!empty($is_image)){
            $user_image = '<img src="'.$user_image.'" alt="'.$user_info['first_name'].' '.$user_info['last_name'].'">';
        }else{
            $user_image = '<span class="profile-picture-character">'.ucfirst($user_info['first_name'][0]).'</span>';
        }
        $fields_replace = array(
            filtering($user_info['id'], 'output', 'int'),
            $this->session_user_id,
            ucwords(filtering($user_info['first_name'], 'output')) . " " .ucwords( filtering($user_info['last_name'], 'output')),
            $user_image,
            //ucwords(getUserHeadline($user_id)),
            get_user_profile_url($user_id),
            encryptIt(filtering($user_info['id'], 'output', 'int')),
            $removeButton,
            $loc,
            count(getCommonConnections($this->session_user_id,$user_info['id'])),
            $send_inmail_url,
            $send_inmail_text,
            $follow_tag,
            encryptIt($user_info['id']),
            $status,
        );

        if($platform == 'app'){
            $mutual_count = count(getCommonConnections($this->current_user_id,$user_info['id']));

            if($user_info['profile_picture_name'] != '' && file_exists(DIR_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'])){
                $user_image = SITE_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'];
            } else {
                $user_image = "";
            }

            $user_id = $user_info['id'];
            $user_name=filtering($user_info['first_name'], 'output') . " " . filtering($user_info['last_name'], 'output');
           // $tagline = getUserHeadline($user_info['id']);
            $location = $loc;
            $follow_status=$status;

            $content = array('user_id'=>$user_id,'user_name'=>$user_name,'user_image'=>$user_image,'location'=>$location,'mutual_connection'=>$mutual_count,'follow_status'=>$follow_status);
        } else {
            $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $content;
    }

    public function deleteSavedPost($post_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->delete('tbl_feeds', array('id' => $post_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = LBL_POST_DELETED;
        } else {
            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME ;
        }
        return $response;
    }
    public function getRightSidebarLeft($user_id) {
        $final_content = '';

        $right_sidebar_tpl = new Templater(DIR_TMPL . $this->module . "/right-sidebar-nct.tpl.php");
        $right_sidebar_tpl_parsed = $right_sidebar_tpl->parse();

        $fields=array(
            '%COVER_IMG%',
            '%EDIT_PROFILE_URL%',
            '%USER_NAME_FULL%',
            // '%HEADLINE%',
            '%NO_OF_VISITORS%',
            '%NO_OF_CONNECTIONS%',
            '%ADD_CONNECTION_URL%',
            "%CONNECTIONS_URL%",
            "%IMG%",
            "%CLASS_DIS%"

        );
        // $user_cover= getImageURL("user_cover_picture",$user_id,"th1",$platform = 'web');
        $edit_profile_url = SITE_URL . "profile";
        $no_of_visitors = getVisitors($user_id, "count");
        $no_of_connections = getNoOfConnections($user_id);
        $add_connection_url = SITE_URL . "people-you-may-know";
        $connections_url = SITE_URL . "connection/" . encryptIt($user_id);
        // $img=getImageURL("user_profile_picture", $user_id, "th4");
        $user_info = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $user_id))->result();
        $class_dis="hidden";
        if($user_id == $this->current_user_id){
            $class_dis='';
        }

        $cover_photo_name = getTableValue('tbl_users','cover_photo',array('id'=>$user_id));
        $user_cover = $this->dashboard_storage->getImageUrl1('av8db','th1_'.$cover_photo_name,'user_cover-nct/'.$user_id.'/');
        $is_image = getimagesize($user_cover);
        if(!empty($is_image)){
            $user_cover = $user_cover;
        }else{
            $user_cover = 'https://storage.googleapis.com/av8db/u-pro-bg.jpg';
        }

        $img_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$user_id));
        $img = $this->dashboard_storage->getImageUrl1('av8db','th4_'.$img_name,'users-nct/'.$user_id.'/');
        $is_image = getimagesize($img);
        if(!empty($is_image)){
            $img = '<img src="'.$img.'" alt="'.$user_info['first_name'].' '.$user_info['last_name'].'">';
        }else{
            $img = '<span class="profile-picture-character">'.ucfirst($user_info['first_name'][0]).'</span>';
        }


        $fields_replace=array(
            $user_cover,
            $edit_profile_url,
            ucwords(filtering($user_info['first_name'])) . " " . ucwords(filtering($user_info['last_name'])),
            //ucwords(getUserHeadline($user_id)),
            $no_of_visitors,
            $no_of_connections,
            $add_connection_url,
            $connections_url,
            $img,
            $class_dis
        );

        $final_content = str_replace($fields, $fields_replace, $right_sidebar_tpl_parsed);

        return $final_content;
    }

    public function getInvitationPageContent($currentpage = 1, $call_from_ajax = false, $action = "getPendingInvitations") {
        $final_result = NULL;
        $invitation_html = NULL;
        if ($call_from_ajax) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/invitation-ajax-nct.tpl.php");
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/invitation-nct.tpl.php");
        }

        if ($action == "getPendingInvitations") {
            $invitation_array = getPendingInvitations($this->session_user_id);
            $connection_count = count($invitation_array);
            $invitation_array = getPendingInvitations($this->session_user_id, true, $currentpage, NO_OF_INVITATION_PER_PAGE);
        } else {
            $invitation_array = getSentInvitations($this->session_user_id);
            $connection_count = count($invitation_array);
            $invitation_array = getSentInvitations($this->session_user_id, true, $currentpage, NO_OF_INVITATION_PER_PAGE);
        }
        foreach ($invitation_array as $key_connection => $value_connection) {
            $invitation_html .= $this->getAllInvitations($value_connection, $action);
        }

        $message = '';
        if ($invitation_html == '') {
            $message = LBL_NO_INVITATION_AVAILABLE;
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $no_result_found_tpl->set('message', $message);
            $invitation_html .= $no_result_found_tpl->parse();
        }
        $page_data = getPagerData($connection_count, NO_OF_INVITATION_PER_PAGE,$currentpage);


        if ($page_data->numPages > 0 && $page_data->numPages > $currentpage  ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getInvitation_load/currentPage/" . ($currentpage + 1)."/".$action;

                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $invitation_html .= $load_more_li_tpl->parse();
        }
        $main_content->set('invitation', $invitation_html);
        //$main_content->set('user_id', $user_id);

        $main_content->set('pagination', getPagination($connection_count, count($invitation_array), NO_OF_INVITATION_PER_PAGE, $currentpage));

        $main_content_parsed = $main_content->parse();
        $fields=array("%PROFILE_DATA%"," %RECEIVE_INVITATION_CLASS%","%SENT_INVITATION_CLASS%");

        $receive_invitation_active_class = $sent_invitation_active_class ="";

        if($action=='getPendingInvitations'){
            $receive_invitation_active_class = "active";

        }
        if($action=='getSentInvitations'){
            $sent_invitation_active_class = "active";

        }

        $fields_replace=array($this->getRightSidebarLeft($this->session_user_id),$receive_invitation_active_class,$sent_invitation_active_class);


        $final_result =str_replace($fields, $fields_replace, $main_content_parsed);




        return $final_result;
    }

    public function getAllInvitations($user_id, $action) {
        $content = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/invitation-all-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $user_info = $this->db->select('tbl_users', array('id,first_name,last_name'), array('id' => $user_id))->result();

        $fields = array(
            "%USER_ID%",
            "%SESSION_USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            // "%USER_HEAD_LINE%",
            "%USER_PROFILE_URL%",
            "%APPROVE_INVITIATION%",
            "%DENY_INVITIATION%",
            "%CANCEL_REQUEST%",
            "%FOLLOW_TAG%",
            "%USER_F_ID%",
            "%USER_STATUS%"
        );
        $status=$getstatus='';
        $follow_tag=LBL_FOLLOW;
        $getstatus = getTableValue("tbl_follower", "status", array("follower_form" =>$_SESSION['user_id'],'follower_to'=>$user_info['id']));
        if($getstatus != ''){
                $status=$getstatus;
                if($getstatus=='f')
                $follow_tag=LBL_MYC_FOLLOWING;
        }
        $fields_replace = array(
            filtering($user_info['id'], 'output', 'int'),
            $this->session_user_id,
            ucwords(filtering($user_info['first_name'], 'output')) . " " .ucwords( filtering($user_info['last_name'], 'output')),
            getUserProfilePictureURL($user_id, "th4"),
            //ucwords(getUserHeadline($user_id)),
            get_user_profile_url($user_id),
            $action == "getPendingInvitations" ? $this->commonActionsUrl($user_info["id"], "approve_invitation") : "",
            $action == "getPendingInvitations" ? $this->commonActionsUrl($user_info["id"], "deny_invitation") : "",
            $action != "getPendingInvitations" ? $this->commonActionsUrl($user_info["id"], "cancel_request") : "",
            $follow_tag,
            encryptIt($user_info['id']),
            $status,
        );

        $content .= str_replace($fields, $fields_replace, $main_content_parsed);

        return $content;
    }

    public function commonActionsUrl($data_id, $case) {
        $content = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/common-action-url-nct.tpl.php");

        $fields = array(
            "%DATA_ID%",
            "%ID%",
            "%TEXT%",
            "%CLASS%",
            "%TITLE%"
        );

        switch ($case) {
            case 'approve_invitation':
                $fields_replace = array(
                    encryptIt($data_id),
                    "approve_invitation",
                    "",
                    "icon-follower",
                    "{LBL_ACCEPT}"
                );
                break;

            case 'deny_invitation':
                $fields_replace = array(
                    encryptIt($data_id),
                    "deny_invitation",
                    "",
                    "icon-connection-close",
                    "{LBL_REJECT}"
                );
                break;

            case 'cancel_request':
                $fields_replace = array(
                    encryptIt($data_id),
                    "cancel_request",
                    "",
                    "icon-close",
                    '{LBL_CANCEL_CONNECTION_REQUEST}'
                );
                break;
            case 'apply_job':
                $fields_replace = array(
                    encryptIt($data_id),
                    "apply_job",
                    LBL_APPLY,
                    "",
                );
                break;
            case 'remove_from_apply_job':
                $fields_replace = array(
                    encryptIt($data_id),
                    "remove_from_job_apply",
                    LBL_REMOVE_APPLIED_JOB,
                    "",
                );
                break;
            case 'follow_company':
                $fields_replace = array(
                    encryptIt($data_id),
                    "follow_company",
                    LBL_FOLLOW,
                    "icon-check",
                );
                break;
            case 'ask_to_join':
                $fields_replace = array(
                    encryptIt($data_id),
                    "ask_to_join",
                    LBL_ASK_TO_JOIN,
                    "",
                );
                break;
            case 'join_group':
                $fields_replace = array(
                    encryptIt($data_id),
                    "join_group",
                    LBL_JOIN,
                    "",
                );
                break;


            default:
                $fields_replace = array(
                    "",
                    "",
                    "",
                    "",
                );
                break;
        }

        $main_content_parsed = $main_content->parse();

        $content = str_replace($fields, $fields_replace, $main_content_parsed);

        return $content;
    }

    public function approveInvitation($user_id, $session_user_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("UPDATE tbl_connections SET status = 'a' WHERE (request_from = ? AND request_to = ?)
            ", array($user_id, $session_user_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = LBL_INVITATION_APPROVE;

             $notificationArray = array(
                "user_id" => $user_id,
                "type" => "cra",
                "action_by_user_id" => $session_user_id,
                "added_on" => date("Y-m-d H:i:s"),
                "updated_on" => date("Y-m-d H:i:s")
            );

            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();


            //For email notification
            $notificationStatus = getTableValue("tbl_notification_settings", "accept_connection", array("user_id" => $user_id));

            if ($notificationStatus == 'y') {
                $from_user = getTableValue("tbl_users", "first_name", array("id" => $session_user_id));
                $to_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));

                $arrayCont['greetings'] = $to_user;
                $arrayCont['from_user'] = $from_user;

                generateEmailTemplateSendEmail("connection_request_approved", $arrayCont, $email_address);

            }


            /* Push notification */
            $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$session_user_id))->result();
            $push_user = filtering($user_data['first_name'].' '.$user_data['last_name']);
            $push_data = array('user_name'=>$push_user);
            $push_data['notification_id']=$notification_id;
            set_notification($user_id,'cra',$push_data);
        } else {
            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }
        return $response;
    }

    public function denyInvitation($user_id, $session_user_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("UPDATE tbl_connections SET status = 'r' WHERE (request_from = ? AND request_to = ?)
            ", array($user_id, $session_user_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = LBL_INVITATION_DENIED;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }

    public function cancelRequest($user_id, $session_user_id) {
        //echo 1;exit;

        $response = array();

        $response['status'] = false;

        $affectedRows = $this->db->pdoQuery("DELETE FROM  tbl_connections WHERE (request_from = ? AND request_to = ?)
            ", array($session_user_id, $user_id))->affectedRows();

        //echo $affectedRows;exit;

        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = LBL_INVITATION_CANCELED;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }

        //_print($response);exit;

        return $response;
    }

    public function approveConnection($user_id,$session_user_id) {

        $response = array();
        $response['status'] = false;
        if($session_user_id == ''){
                $session_user_id = $this->session_user_id;

        }
        $affectedRows = $this->db->update('tbl_connections', array('status' => 'a', 'updated_on' => date('Y-m-d H:i:s')), array('request_from' => $user_id, 'request_to' => $session_user_id))->affectedRows();

        if ($affectedRows) {
            //add in follwing

            $getid = getTableValue("tbl_follower", "id", array("follower_form" =>$session_user_id,'follower_to'=>$user_id));
            if($getid == ''){

               $user_detail['follower_form'] = $session_user_id;
               $user_detail['follower_to'] = $user_id;
               $user_detail['status'] = 'f';
               $user_detail['addon'] = date("Y-m-d H:i:s");
               $user_detail['updateon'] = date("Y-m-d H:i:s");

                $id = $this->db->insert("tbl_follower", $user_detail)->getLastInsertId();


            }else{
                $affectedRows = $this->db->update('tbl_follower', array('status' => 'f', 'updateon' => date('Y-m-d H:i:s')), array('follower_form' => $session_user_id, 'follower_to' => $user_id))->affectedRows();


            }
             $notificationArray = array(
                            "user_id" => $user_id,
                            "type" => "fu",
                            "action_by_user_id" => $session_user_id,
                            "added_on" => date("Y-m-d H:i:s"),
                            "updated_on" => date("Y-m-d H:i:s")
            );
            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

             //For email notification
            $notificationStatus = getTableValue("tbl_notification_settings", "follow_user", array("user_id" => $user_id));

            if ($notificationStatus == 'y') {
                $from_user = getTableValue("tbl_users", "first_name", array("id" => $session_user_id));
                $to_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
                $arrayCont['greetings'] = $to_user;
                $arrayCont['from_user'] = $from_user;
                generateEmailTemplateSendEmail("follow_user", $arrayCont, $email_address);
            }
            /* Push notification */
            $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$session_user_id))->result();
            $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
            $push_data = array('user_name'=>$push_user_name,'notification_id'=>$notification_id);
            set_notification($user_id,'fu',$push_data);


            $getid = getTableValue("tbl_follower", "id", array("follower_form" =>$user_id,'follower_to'=>$session_user_id));
            if($getid == ''){

               $user_detail['follower_form'] = $user_id;
               $user_detail['follower_to'] = $session_user_id;
               $user_detail['status'] = 'f';
               $user_detail['addon'] = date("Y-m-d H:i:s");
               $user_detail['updateon'] = date("Y-m-d H:i:s");

                $id = $this->db->insert("tbl_follower", $user_detail)->getLastInsertId();


            }else{
                $affectedRows = $this->db->update('tbl_follower', array('status' => 'f', 'updateon' => date('Y-m-d H:i:s')), array('follower_form' => $user_id, 'follower_to' => $session_user_id))->affectedRows();


            }
            $notificationArray = array(
                            "user_id" =>$session_user_id,
                            "type" => "fu",
                            "action_by_user_id" =>  $user_id,
                            "added_on" => date("Y-m-d H:i:s"),
                            "updated_on" => date("Y-m-d H:i:s")
            );
            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

             //For email notification
            $notificationStatus = getTableValue("tbl_notification_settings", "follow_user", array("user_id" => $user_id));

            if ($notificationStatus == 'y') {
                $from_user = getTableValue("tbl_users", "first_name", array("id" =>  $user_id));
                $to_user = getTableValue("tbl_users", "first_name", array("id" => $session_user_id));
                $email_address = getTableValue("tbl_users", "email_address", array("id" => $session_user_id));
                $arrayCont['greetings'] = $to_user;
                $arrayCont['from_user'] = $from_user;
               generateEmailTemplateSendEmail("follow_user", $arrayCont, $email_address);
            }
            /* Push notification */
            $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
            $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
            $push_data = array('user_name'=>$push_user_name);
            $push_data['notification_id']=$notification_id;

            set_notification($session_user_id,'fu',$push_data);



            $notificationArray = array(
                "user_id" => $user_id,
                "type" => "cra",
                "action_by_user_id" => $session_user_id,
                "added_on" => date("Y-m-d H:i:s"),
                "updated_on" => date("Y-m-d H:i:s")
            );

            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();


            //For email notification
            $notificationStatus = getTableValue("tbl_notification_settings", "accept_connection", array("user_id" => $user_id));

             /* Push notification */
                $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$session_user_id))->result();
                $push_user = filtering($user_data['first_name'].' '.$user_data['last_name']);
                $push_data = array('user_name'=>$push_user);
                $push_data['notification_id']=$notification_id;

                set_notification($user_id,'cra',$push_data);
            if ($notificationStatus == 'y') {
                $from_user = getTableValue("tbl_users", "first_name", array("id" => $session_user_id));
                $to_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));

                $arrayCont['greetings'] = $to_user;
                $arrayCont['from_user'] = $from_user;

                generateEmailTemplateSendEmail("connection_request_approved", $arrayCont, $email_address);

            }

            $response['status'] = true;
            $response['success'] = LBL_INVITATION_APPROVE;
            $response['msg'] = LBL_CONNECTION_REQUEST_ACCEPTED;
        } else {

           $response['error'] = $response['msg'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }

        return $response;
    }

    public function rejectConnection($user_id) {
        $response = array();
        $response['status'] = false;
        $session_user_id = $this->session_user_id;
        $affectedRows = $this->db->update('tbl_connections', array('status' => 'r', 'updated_on' => date('Y-m-d H:i:s')), array('request_from' => $user_id, 'request_to' => $session_user_id))->affectedRows();
        if ($affectedRows) {
            $response['status'] = true;
            $response['msg'] = LBL_CONNECTION_REJECTED;
        } else {
            $response['msg'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }

    public function getUserVisitors($currentPage = 1) {
        $response = array();
        $response['status'] = false;

        $visitors_html = "";
        $limit = 10;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;

        $sql = "SELECT u.*, pv.visitor_id
                    FROM tbl_profile_visits pv
                    LEFT JOIN tbl_users u ON u.id = pv.visitor_id
                    WHERE pv.visited_id = '" . $this->session_user_id . "'
                    AND visited_on >= '" . date('Y-m-d H:i:s', strtotime("-1 day")) . "'
                    GROUP BY pv.visitor_id
                    ORDER BY pv.id DESC ";

        $sql_with_limit = $sql . " LIMIT " . $limit . " OFFSET " . $offset;

        $visitors = $this->db->pdoQuery($sql_with_limit)->results();

        if ($visitors) {
            $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_visitors = $this->db->pdoQuery($sql_with_next_limit)->results();
            $next_available_records = count($next_visitors);
            //liker-nct.tpl.php
            $visitors_tpl = new Templater(DIR_TMPL . "liker-without-close-nct.tpl.php");
            $visitors_tpl_parsed = $visitors_tpl->parse();

            $fields = array(
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                // "%HEADLINE%",
            );

            for ($i = 0; $i < count($visitors); $i++) {
                $user_profile_url = get_user_profile_url($visitors[$i]['id']);
                $first_name = filtering($visitors[$i]['first_name'], 'output');
                $last_name = filtering($visitors[$i]['last_name'], 'output');

                $user_name_full = $first_name . " " . $last_name;

                $fields_replace = array(
                    getImageURL("user_profile_picture", $visitors[$i]['id'], "th3"),
                    $user_profile_url,
                    ucwords($user_name_full),
                    //ucwords(getUserHeadline($visitors[$i]['id']))
                );

                $visitors_html .= str_replace($fields, $fields_replace, $visitors_tpl_parsed);
            }

            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "getVisitors/currentPage/" . ($currentPage + 1);

                $load_more_li_tpl->set('load_more_link', $load_more_link);

                $visitors_html .= $load_more_li_tpl->parse();
            }
        }

        $response['status'] = true;
        $response['visitors'] = $visitors_html;

        return $response;
    }

    public function getAllPeopleYouKnow($user_id,$session_id=0,$platform='web') {
        $content = NULL;
        $common_connection_html = '';
        $main_content = new Templater(DIR_TMPL . $this->module . "/people-you-know-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $user_info = $this->db->select('tbl_users', array('id','first_name','last_name','profile_picture_name'), array('id' => $user_id))->result();

        $common_connection_array = getCommonConnections($user_info['id'], $session_id);
        $common_connection_count = count($common_connection_array);

        $common_connection_array = getCommonConnections($user_info['id'], $session_id, true, 1, 3);
        foreach ($common_connection_array as $key_connection => $value_connection) {
            $common_connection_html .= $this->getCommonConnectionSection($value_connection);
        }
        //print_r($user_info);exit();
        $fields = array(
            "%USER_ID%",
            "%SESSION_USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            // "%USER_HEAD_LINE%",
            "%USER_PROFILE_URL%",
            "%ENCRYPTED_USER_ID%",
            "%NO_OF_COMMON_CONNECTIONS%",
            "%COMMON_CONNECTION%",
            "%FOLLOW_TAG%",
            "%USER_F_ID%",
            "%USER_STATUS%"
        );
        $status=$getstatus='';
        $follow_tag=LBL_FOLLOW;
        $getstatus = getTableValue("tbl_follower", "status", array("follower_form" =>$session_id,'follower_to'=>$user_info['id']));

        if($getstatus != ''){
                $status=$getstatus;
                if($getstatus=='f')
                $follow_tag=LBL_MYC_FOLLOWING;
        }
        $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$user_id));
        $user_image = $this->dashboard_storage->getImageUrl1('av8db','th4_'.$user_pro_pic_name,'users-nct/'.$user_id.'/');
        $is_image = getimagesize($user_image);
        if(!empty($is_image)){
            $user_image = '<img src="'.$user_image.'" alt="'.$user_info['first_name'].'">';
        }else{
            $user_image = '<span class="profile-picture-character">'.ucfirst($user_info['first_name'][0]).'</span>';
        }
        $fields_replace = array(
            filtering($user_info['id'], 'output', 'int'),
            $this->session_user_id,
            ucwords(filtering($user_info['first_name'], 'output')) . " " . ucwords( filtering($user_info['last_name'], 'output')),
            $user_image,
            //ucwords(getUserHeadline($user_id)),
            get_user_profile_url($user_id),
            encryptIt(filtering($user_info['id'], 'output', 'int')),
            $common_connection_count,
            $common_connection_html,
            $follow_tag,
            encryptIt($user_info['id']),
            $status,
        );
        if($platform == 'app'){

            if($user_info['profile_picture_name']!='' && file_exists(DIR_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'])){
                $userimg = SITE_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'];
            } else {
                $userimg = "";
            }

            $userid = filtering($user_info['id'], 'output', 'int');
            $username = filtering($user_info['first_name'], 'output') . " " . filtering($user_info['last_name'], 'output');
            //$tagline = getUserHeadline($user_id);
            $mutualconnection = $common_connection_count;
            $follow_status=$status;

            $content = array('userid'=>$userid,'username'=>$username,'userimg'=>$userimg,'tagline'=>'','mutual_connection'=>$mutualconnection,'follow_status'=>$follow_status);



        } else {
           // print_r($fields_replace);exit();
            $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        }
        return $content;
    }

    public function getCompanyFollowers($company_id) {
        $query = "SELECT count(id) as company_followers
            FROM tbl_company_followers
            WHERE company_id = ? ";

        $company_details = $this->db->pdoQuery($query,array($company_id))->result();

        return $company_details['company_followers'];
    }

    public function getAllNotificationPageContent($currentpage, $call_from_ajax) {
        $final_result = NULL;
        $notification_html = NULL;

        $limit = (($this->platform == 'app')?10:NO_OF_INVITATION_PER_PAGE);
        $offset = ($currentpage - 1 ) * $limit;
        $where_cond = '';
        if (isset($this->company_id) && $this->company_id > 0) {
            $where_cond = 'AND n.company_id = ' . $this->company_id . '';
        }

        $query = "select n.id as postid,n.added_on,n.type,n.action_by_user_id,n.feed_id,n.group_id,n.job_id,n.company_id  FROM tbl_notifications n WHERE n.user_id = " . $this->current_user_id . " " . $where_cond . " ORDER BY id DESC ";

        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;

        $getAllResults = $this->db->pdoQuery($query)->results();
        $totalRows = count($getAllResults);
        $getShowableResults = $this->db->pdoQuery($query_with_limit)->results();
        if ($call_from_ajax) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/notification-ajax-nct.tpl.php");
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/notification-nct.tpl.php");
        }
        $notification = new Templater(DIR_TMPL . $this->module . "/notification-all-li-nct.tpl.php");
        $notification_parsed = $notification->parse();
        $field = array('%NOTIFICATION_TEXT%','%NOTIFICATION_URL%','%NOTIFICATION_TITLE%','%NOTIFICATION_TIME%','%USER_IMG%',"%CLASS%");

        foreach ($getShowableResults as $notification) {
            $notification_date = $notification['added_on'];
            $time_ago = time_elapsed_string(strtotime($notification['added_on']));
            $type = $notification['type'];
            $action_by_user_id = filtering($notification['action_by_user_id'], 'input', 'int');
            $feed_id = filtering($notification['feed_id'], 'input', 'int');
            $group_id = filtering($notification['group_id'], 'input', 'int');
            $job_id = filtering($notification['job_id'], 'input', 'int');
            $company_id = filtering($notification['company_id'], 'input', 'int');
            $class = "";
            if ($action_by_user_id > 0) {
                $action_by_user_details = $this->db->select("tbl_users", array('first_name,last_name'), array("id" => $action_by_user_id))->result();
                $action_by_user_name = ucwords(filtering($action_by_user_details['first_name'])) . " " . ucwords(filtering($action_by_user_details['last_name']));
            }

            if ($feed_id > 0) {
                $feed_details = $this->db->select("tbl_feeds", array('post_title'), array("id" => $feed_id))->result();
                $post_title = ucwords(filtering($feed_details['post_title']));
            }

            if ($group_id > 0) {
                $group_details = $this->db->select("tbl_groups", array('group_name'), array("id" => $group_id))->result();
                $group_name = ucwords(filtering($group_details['group_name']));
            }

            if ($job_id > 0) {
                $job_details = $this->db->select("tbl_jobs", array('job_title'), array("id" => $job_id))->result();
                $job_title = ucwords(filtering($job_details['job_title']));
            }

            if ($company_id > 0) {
                $company_details = $this->db->select("tbl_companies", array('company_name'), array("id" => $company_id))->result();
                $company_name = ucwords(filtering($company_details['company_name']));
            }

            switch ($type) {
                case 'cra' : {
                    $notification_type = 'connection_request_accept';
                    $notification_text = LBL_COM_DET_YOUR_CONNECTION_REQUEST_ACCEPTED .' '. $action_by_user_name;
                    $notification_url = get_user_profile_url($action_by_user_id);
                    $notification_title = LBL_CONNECTION_REQUEST_ACCEPTED;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-check';
                    break;
                }
                case 'like' : {
                    $notification_type = 'like_post';
                    $notification_text = $action_by_user_name . ' '.LBL_LIKED_YOUR_POST . ' '. $post_title;
                    $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                    $notification_title = $action_by_user_name . ' '.LBL_LIKED_YOUR_POST.' ' . $post_title;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-thumbs-up';
                    break;
                }
                case 'comment' : {
                    $notification_type = 'comment_on_post';
                    $notification_text = $action_by_user_name . ' '.LBL_COMMENTED_ON_YOUR_POST.' ' . $post_title;
                    $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                    $notification_title = $action_by_user_name . ' '.LBL_COMMENTED_ON_YOUR_POST.' ' . $post_title;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-comments';
                    break;
                }
                case 'share' : {
                    $notification_type = 'share_post';
                    $notification_text = $action_by_user_name .' '. LBL_SHARED_YOUR_POST .' '. $post_title;
                    $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                    $notification_title = $action_by_user_name .' '. LBL_SHARED_YOUR_POST.' ' . $post_title;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-share';
                    break;
                }

                case 'rgji' : {
                    $notification_type = 'group_join_invitaion';
                    $notification_text= $action_by_user_name.' ' . LBL_INVITED_YOU_TO_JOIN_GROUP.' ' . $group_name;
                    $notification_url = get_group_detail_url($group_id);
                    $notification_title = LBL_GROUP_JOINING_INVITATION;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-bell';
                    break;
                }
                case 'gjra' : {
                    $notification_type = 'request_accept_to_join_group';
                    $notification_text = $action_by_user_name.' ' .LBL_ACCEPTED_YOUR_REQUEST_FOR_JOINING_GROUP .' ' . $group_name;
                    $notification_url = get_group_detail_url($group_id);
                    $notification_title = LBL_GROUP_JOINING_REQUEST_ACCEPTED ;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-check';
                    break;
                }
                case 'rgjr' : {
                    $notification_type = 'group_join_request';
                    $notification_text = $action_by_user_name.' ' . LBL_SENT_REQUEST_JOIN_GROUP.' '  . $group_name;
                    $notification_url = get_group_detail_url($group_id);
                    $notification_title = LBL_SENT_REQUEST_JOINING_GROUP;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-check';
                    break;
                }
                case 'aj' : {
                    $notification_type = 'applied_on_job';
                    $notification_text = $action_by_user_name.' ' . LBL_APPLIED_ON_JOB.' ' . $job_title;
                    $notification_url = SITE_URL . "job-applicants/job/" . $job_id;
                    $notification_title = LBL_APPLIED_ON_JOB_CAPITAL;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-check-square-o';
                    break;
                }

                case 'fc' : {
                    $notification_type = 'follow_company';
                    $notification_text = $action_by_user_name.' ' . LBL_FOLLOWED_COMPANY .' '. $company_name;
                    $notification_url = get_company_detail_url($company_id);
                    $notification_title = LBL_FOLLOW_COMPANY;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-angle-double-right';
                    break;
                }
                case 'nfg' : {
                    $notification_type = 'posted_in_group';
                    $notification_text = $action_by_user_name.' ' . LBL_POSTED_GROUP.' '. $group_name;
                    $notification_url = get_group_detail_url($group_id).'?id='.encryptIt($feed_id).'#'.encryptIt($feed_id);
                    $notification_title = LBL_NEW_POST;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-angle-double-right';
                    break;
                }

                case 'ampg' : {
                    $notification_type = 'added_in_group';
                    $notification_text = $action_by_user_name.' ' . LBL_ADDED_IN_GROUP .' '. $group_name;
                    $notification_url = get_group_detail_url($group_id);
                    $notification_title = LBL_ADDED_MEMBER;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-angle-double-right';
                    break;
                }
                case 'fu' : {
                    $notification_type = 'follow_user';
                    $notification_text = $action_by_user_name.' '.LBL_FOLLOWED_USER;
                    $notification_url = get_user_profile_url($action_by_user_id);
                    $notification_title = Following;
                    $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                    $class = 'fa-check';
                    break;
                }case 'nfc' : {
                        $notification_type = 'notify_when_company_post';

                        $notification_text = $action_by_user_name.' ' . LBL_POSTED_COMPANY.' ' . $company_name;
                        $notification_url = get_company_detail_url($company_id);
                        $notification_title = LBL_NEW_POST;
                        $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                        break;
                    }
                    case 'jpc' : {
                        $notification_type = 'job_post_in_company';

                        $notification_text = $action_by_user_name.' ' .  LBL_POST_JOB_COMPANY.' ' . $company_name;
                        $notification_url = SITE_URL . "job/" . $job_id;
                        $notification_title = LBL_NEW_JOB;
                        $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",$this->platform);
                        break;
                    }
            }

            $field_replace = array(
                filtering($notification_text),
                filtering($notification_url),
                filtering($notification_title),
                $time_ago,
                $user_img,
                $class
            );
            if($this->platform=='app'){
                $app_array[] = array('postid'=>$notification['postid'],'userId'=>$action_by_user_id,'username'=>$action_by_user_name,'userImg'=>$user_img,'notification_type'=>$notification_type,'notification_msg'=>$notification_text,'time'=>$time_ago,'action_by_user_id'=>$action_by_user_id,'feed_id'=>$feed_id,'group_id'=>$group_id,'job_id'=>$job_id,'company_id'=>$company_id);
            } else {
                $notification_html .= str_replace($field, $field_replace, $notification_parsed);
            }
        }
        $page_data = getPagerData($totalRows, $limit,$currentpage);

        if ($page_data->numPages > 0 && $page_data->numPages > $currentpage ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-notification-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getNotification_Load/currentPage/" . ($currentpage + 1);

                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $notification_html .= $load_more_li_tpl->parse();
        }
        if($this->platform=='app'){
            $final_result['notification'] = (!empty($app_array)?$app_array:array());
            $page_data = getPagerData($totalRows, $limit,$currentpage);
            $final_result['pagination'] = array('current_page'=>$currentpage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
            $final_result['status'] = 'success';
            $final_result['message']=API_SUCCESS_NOTIFICATION_LISTING;
        } else {
            $main_content->set('notification', $notification_html);
            $main_content->set('pagination', getPagination($totalRows, count($getShowableResults), NO_OF_INVITATION_PER_PAGE, $currentpage));
            $main_content_parsed = $main_content->parse();
            $final_result = $main_content_parsed;
        }
        return $final_result;
    }
    function like_unlike($feed_id =0 ,$user_id=0){
        $response = array();
        $response['status'] = false;

        $checkIfLiked=$this->db->select("tbl_likes", "*", array("user_id" => $user_id, "feed_id" => $feed_id))->result();
        if ($checkIfLiked) {
            $affectedRows=$this->db->delete("tbl_likes", array("user_id" => $user_id, "feed_id" => $feed_id))->affectedRows();
            if ($affectedRows > 0) {
                $feedPostedByUserId = getTableValue("tbl_feeds", "user_id", array("id" => $feed_id));
                $response['status'] = true;
                $response['like_unlike_text'] = '<i class="fa fa-thumbs-up" aria-hidden="true"></i> '.LBL_COM_DET_LIKE;
            }
        } else {
            $arrayToBeInserted = array(
                "user_id" => $user_id,
                "feed_id" => $feed_id,
                "liked_on" => date("Y-m-d H:i:s")
            );
            $like_id = $this->db->insert("tbl_likes", $arrayToBeInserted)->getLastInsertId();
            $add_data=array(
                "user_id"=>$user_id,
                "feed_id"=>$feed_id,
                "status"=>"like",
                "addon"=>date("Y-m-d H:i:s")
            );
            $checkIf=$this->db->select("tbl_feed_activity", "*", array("user_id" => $user_id, "feed_id" => $feed_id,"status"=>'like'))->result();
            if($checkIf == ''){
                    $this->db->insert("tbl_feed_activity", $add_data)->getLastInsertId();

            }else{
                $affectedRows = $this->db->update("tbl_feed_activity", $add_data,array("user_id" => $user_id, "feed_id" => $feed_id,"status"=>'like'))->affectedRows();

            }

            if ($like_id) {
                $feedPostedByUserId = getTableValue("tbl_feeds", "user_id", array("id" => $feed_id));
                if($user_id != $feedPostedByUserId){
                    $notificationArray = array(
                        "user_id" => $feedPostedByUserId,
                        "type" => "like",
                        "action_by_user_id" => $user_id,
                        "feed_id" => $feed_id,
                        "added_on" => date("Y-m-d H:i:s"),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    $notiId = $this->db->insert("tbl_notifications", $notificationArray)->getLastInsertId();
                    /* Push notification */
                    $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
                    $push_liked_by = filtering($user_data['first_name'].' '.$user_data['last_name']);
                    $push_data = array('liked_by'=>$push_liked_by,'notification_id'=>$notiId,"feed_id" => $feed_id);
                    set_notification($feedPostedByUserId,'LIKED',$push_data);
                }

                $response['status'] = true;
                $response['like_unlike_text'] = '<i class="fa fa-thumbs-down" aria-hidden="true"></i> '.LBL_UNLIKE;
            }
        }
        return $response;
    }
    function postComment($feed_id =0 ,$user_id=0) {
        
        $response = array();
        $response['status'] = false;
        $comment = filtering($_POST['comment'], "input");
        $arrayToBeInserted = array(
            "user_id" => $user_id,
            "feed_id" => $feed_id,
            "comment" => $comment,
            "commented_on" => date("Y-m-d H:i:s"),
            "update_on"=>date("Y-m-d H:i:s")
        );
        
        if($comment != ''){
            $comment_id = $this->db->insert("tbl_comments", $arrayToBeInserted)->getLastInsertId();
            $add_data=array(
                "user_id"=>$user_id,
                "feed_id"=>$feed_id,
                "status"=>"comment",
                "addon"=>date("Y-m-d H:i:s")
            );
            $this->db->insert("tbl_feed_activity", $add_data)->getLastInsertId();
            if ($comment_id) {
                $feedPostedByUserId = getTableValue("tbl_feeds", "user_id", array("id" => $feed_id));
                if($feedPostedByUserId != $user_id) {
                    $notificationArray = array(
                        "user_id" => $feedPostedByUserId,
                        "type" => "comment",
                        "action_by_user_id" => $user_id,
                        "feed_id" => $feed_id,
                        "added_on" => date("Y-m-d H:i:s"),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    $notiId = $this->db->insert("tbl_notifications", $notificationArray)->getLastInsertId();
                    /* Push notification */
                    $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
                    $push_posted_by = filtering($user_data['first_name'].' '.$user_data['last_name']);
                    $push_data = array('commented_by'=>$push_posted_by,"feed_id" => $feed_id,'notification_id'=>$notiId);
                    set_notification($feedPostedByUserId,'COMMENT',$push_data);
                }
                $response['status'] = true;
                $response['comments_count'] = getCommentsCount($feed_id);
                $response['comment_html'] = getSingleCommentBox($this->dashboard_storage,$feed_id, $comment_id);
                $response['comment_id']=$comment_id;
                $response['success'] = API_COMMENT_POSTED;
            } else {
                $response['error'] = ERROR_COMMENT;
            }
        } else {
            $response['error'] = COMMENT_REQUIRED_MSG;
        }
        return $response;
    }
    //delete feed 12-9-18
    public function deletePost($post_id) {
        $response = array();
        $response['status'] = false;
        $image=$this->db->select('tbl_feeds','image_name',array('id'=>$post_id))->result();

        unlink(DIR_UPD_FEEDS . $image['image_name']);
        unlink(DIR_UPD_FEEDS .'th1_'. $image['image_name']);

        $affectedRows = $this->db->delete('tbl_feeds', array('id' => $post_id))->affectedRows();

        $this->db->delete('tbl_notifications', array('feed_id' => $post_id));
        if ($affectedRows && $affectedRows > 0) {
            $src2 = 'feed-images-nct/';
            $main_img = $this->dashboard_storage->getImageUrl1('av8db',$image['image_name'],$src2);
            $is_main_img = getimagesize($main_img);
            if(!empty($is_main_img)){
                $del = $this->dashboard_storage->delete_object1('av8db',$image['image_name'],'',$src2);
            }
            $main_img_one = $this->dashboard_storage->getImageUrl1('av8db','th1_'.$image['image_name'],$src2);
            $is_main_img_one = getimagesize($main_img_one);
            if(!empty($is_main_img_one)){
                $del1 = $this->dashboard_storage->delete_object1('av8db','th1_'.$image['image_name'],'',$src2);
            }
            
            $response['status'] = true;
            $response['success'] = LBL_POST_DELETED;
        } else {
            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME ;
        }
        return $response;
    }
    public function publish_post_save($post_id) {
        $response = array();
        $response['status'] = false;
        
        $affectedRows = $this->db->update("tbl_feeds",array('status'=>'p','updated_on' => date("Y-m-d H:i:s")), array('id' => $post_id))->affectedRows();

        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = LBL_POST_ADDED;
        } else {
            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME ;
        }
        return $response;
    }
    public function getFollowingPageContent($user_id, $currentpage = 1, $call_from_ajax = false, $keyword = '',$platform = 'web') {
        $final_result = NULL;
        $connection_html = NULL;
        $common_connection_array = array();
        $next_available_records = 0;
        $limit = NO_OF_CONNECTION_PER_PAGE;
        //$limit=2;
        $offset = ($currentpage - 1 ) * $limit;



        if ($call_from_ajax) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/following-ajax-nct.tpl.php");
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/following-nct.tpl.php");
        }


        /*$connection_array = getSearchFollowing($keyword,$user_id);
        $connection_count = count($connection_array);

        $connection_array = getSearchFollowing($keyword,$user_id, true, $currentpage, NO_OF_CONNECTION_PER_PAGE);*/
        $wherecon = '';
        if ($keyword != '') {
             $wherecon .= 'and case when ut.id='.$user_id.' then (uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%" ) when uf.id=' . $user_id . ' then (ut.first_name like "%' . $keyword . '%" OR ut.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%") end';
        }
        $query = "select uc.* from tbl_follower as uc left join tbl_users as uf ON(uf.id = uc.follower_form) left join tbl_users as ut ON(ut.id = uc.follower_to) WHERE follower_form = ?  AND uc.status = ? " . $wherecon . " ";

        $whrExtArr=array($user_id,'f');


        $totalRows = $this->db->pdoQuery($query,$whrExtArr)->affectedRows();
        $query_with_limit = $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

        $connection_detail_array = $this->db->pdoQuery($query,$whrExtArr)->results();


        $connection_detail_array = $this->db->pdoQuery($query_with_limit,$whrExtArr)->results();

        if ($connection_detail_array) {
            for ($i = 0; $i < count($connection_detail_array); $i++) {
                $follower_form = $connection_detail_array[$i]['follower_form'];
                $follower_to = $connection_detail_array[$i]['follower_to'];
                if ($follower_form == $user_id) {
                    $connections_array[] = $follower_to;
                } else {
                    $connections_array[] = $follower_form;
                }
            }
        }
        $connection_count = count($connections_array);

        if(!empty($connections_array))
            $connection_string = implode(',', $connections_array);
        else
            $connection_string = 0;

        $uData = $this->db->pdoQuery('select id,first_name,last_name,location_id,profile_picture_name from `tbl_users` where id in ('.$connection_string.')')->results();

        foreach ($uData as $key_connection => $value_connection) {
            $con_htm = $this->getAllFollwing($value_connection['id'],$value_connection,$platform);
            $connection_html .= $con_htm;
            if($platform == 'app'){
                $app_array_main[] = $con_htm;
            }
        }

        if ($connection_html == '') {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = NO_FOLLOWING_FOUND;

            $no_result_found_tpl->set('message', $message);
            $connection_html .= $no_result_found_tpl->parse();
        }
        $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
        $connection_count_load = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();
        $next_available_records = count($connection_count_load);
        if ($next_available_records > 0 ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getFollowing_load/currentPage/" . ($currentpage + 1)."/".$keyword;

                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $connection_html .= $load_more_li_tpl->parse();
        }
        $main_content->set('following', $connection_html);
        $main_content->set('user_id', $user_id);

        $pagination = getPagination($connection_count, count($connection_array), NO_OF_CONNECTION_PER_PAGE, $currentpage);
        if($platform == 'app'){
            $page_data = getPagerData($connection_count, NO_OF_CONNECTION_PER_PAGE,$currentpage);

            //pagination=current_page,total_pages,total
            $app_pagination = array('current_page'=>$currentpage,'total_pages'=>$page_data->numPages,'total'=>$connection_count);

        }


        $main_content->set('pagination', $pagination);


        $user_info = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $user_id))->result();

        $fields = array("%USER_NAME%","%PROFILE_DATA%","%NAV_MENU%");
        $fields_replace = array(
            ucwords(filtering($user_info['first_name'], 'output')) . " " . ucwords( filtering($user_info['last_name'], 'output')),$this->getRightSidebarLeft($user_id),$this->getnavmenu($user_id,'getFollowing')
        );

        $main_content_parsed = $main_content->parse();
        if($platform == 'app'){
            $app_array['pagination'] = $app_pagination;
            $app_array['connections'] = $app_array_main;
            $final_result = $app_array;
        } else {
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $final_result;
    }

    public function getAllFollwing($user_id,$user_info,$platform) {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/following-all-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $removeButton = '';
        if ($user_id != $this->session_user_id) {
            $removeButton .= '<a class="purple-text" href="javascript:void(0);" id="remove_following" data-value="' . encryptIt($user_id) . '" title="{LBL_UNFOLLOW}">
            {LBL_UNFOLLOW}
            </a>';
        }
        $fields = array(
            "%USER_ID%",
            "%SESSION_USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            // "%USER_HEAD_LINE%",
            "%USER_PROFILE_URL%",
            "%ENCRYPTED_USER_ID%",
            "%REMOVE_BUTTON%",
            "%USER_LOCATION%",
            "%NUMBER_OF_SHARED_CONNATION%",
            "%MESSAGE_URL%",
            "%MESSAGE_TEXT%"
        );

        if($user_info['location_id'] == "")
            $user_info['location_id']=0;


        $connected_user_ids = getFollowing($user_id);
        if (is_array($connected_user_ids) && in_array($this->session_user_id, $connected_user_ids)) {
            $send_inmail_url = SITE_URL . "compose-message/" . encryptIt($user_id);
            $send_inmail_text = LBL_SEND_MESSAGE;
        } else {
            $send_inmail_url = SITE_URL . "compose-message/" . encryptIt($user_id);
            $send_inmail_text = LBL_SEND_MESSAGE;
        }
        $loc = getTableValue("tbl_locations", "formatted_address", array("id" => $user_info['location_id']));

        $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$user_info['id']));
        $user_image = $this->dashboard_storage->getImageUrl1('av8db','th4_'.$user_pro_pic_name,'users-nct/'.$user_info['id'].'/');
        $is_image = getimagesize($user_image);
        if(!empty($is_image)){
            $user_image = '<img src="'.$user_image.'" alt="'.$user_info['first_name'].' '.$user_info['last_name'].'">';
        }else{
            $user_image = '<span class="profile-picture-character">'.ucfirst($user_info['first_name'][0]).'</span>';
        }

        $fields_replace = array(
            filtering($user_info['id'], 'output', 'int'),
            $this->session_user_id,
            ucwords(filtering($user_info['first_name'], 'output')) . " " . ucwords(filtering($user_info['last_name'], 'output')),
            $user_image,
            //ucwords(getUserHeadline($user_id)),
            get_user_profile_url($user_id),
            encryptIt(filtering($user_info['id'], 'output', 'int')),
            $removeButton,
            $loc,
            count(getCommonConnections($this->session_user_id,$user_info['id'])),
            $send_inmail_url,
            $send_inmail_text
        );

        if($platform == 'app'){
            $mutual_count = count(getCommonConnections($this->current_user_id,$user_info['id']));

            if($user_info['profile_picture_name'] != '' && file_exists(DIR_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'])){
                $user_image = SITE_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'];
            } else {
                $user_image = "";
            }

            $user_id = $user_info['id'];
            $user_name=filtering($user_info['first_name'], 'output') . " " . filtering($user_info['last_name'], 'output');
            //$tagline = getUserHeadline($user_info['id']);
            $location = $loc;

            $content = array('user_id'=>$user_id,'user_name'=>$user_name,'user_image'=>$user_image,'tagline'=>'','location'=>$location,'mutual_connection'=>$mutual_count);
        } else {
            $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $content;
    }
     public function getFollowerPageContent($user_id, $currentpage = 1, $call_from_ajax = false, $keyword = '',$platform = 'web') {
        $final_result = NULL;
        $connection_html = NULL;
        $next_available_records = 0;
        $limit = NO_OF_CONNECTION_PER_PAGE;

        $offset = ($currentpage - 1 ) * $limit;


        if ($call_from_ajax) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/follower-ajax-nct.tpl.php");
        } else {
            $main_content = new Templater(DIR_TMPL . $this->module . "/follower-nct.tpl.php");
        }


        
        if ($keyword != '') {
             $wherecon .= 'and case when ut.id='.$user_id.' then (uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%" ) when uf.id=' . $user_id . ' then (ut.first_name like "%' . $keyword . '%" OR ut.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%") end';
        }
        $query = "select uc.* from tbl_follower as uc left join tbl_users as uf ON(uf.id = uc.follower_form) left join tbl_users as ut ON(ut.id = uc.follower_to) WHERE ( follower_to = ?  ) AND uc.status = ? " . $wherecon . "  ";

        $whrExtArr=array($user_id,'f');


        $totalRows = $this->db->pdoQuery($query,$whrExtArr)->affectedRows();

        $query_with_limit = $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

        $connection_detail_array = $this->db->pdoQuery($query,$whrExtArr)->results();


        $connection_detail_array = $this->db->pdoQuery($query_with_limit,$whrExtArr)->results();

        if ($connection_detail_array) {
            for ($i = 0; $i < count($connection_detail_array); $i++) {
            $follower_form = $connection_detail_array[$i]['follower_form'];
            $follower_to = $connection_detail_array[$i]['follower_to'];
            if ($follower_to == $user_id) {
                $connections_array[] = $follower_form;
            } else {
                $connections_array[] = $follower_to;
            }
        }
        }
        $connection_count = count($connections_array);




        if(!empty($connections_array))
            $connection_string = implode(',', $connections_array);
        else
            $connection_string = 0;

        $uData = $this->db->pdoQuery('select id,first_name,last_name,location_id,profile_picture_name from `tbl_users` where id in ('.$connection_string.')')->results();


        foreach ($uData as $key_connection => $value_connection) {
            $con_htm = $this->getAllFollwer($value_connection['id'],$value_connection,$platform);
            $connection_html .= $con_htm;
            if($platform == 'app'){
                $app_array_main[] = $con_htm;
            }
        }

        if ($connection_html == '') {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = NO_FOLLOWING_FOUND;

            $no_result_found_tpl->set('message', $message);
            $connection_html .= $no_result_found_tpl->parse();
        }
        $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
        $connection_count_load = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();
        $next_available_records = count($connection_count_load);
        if ($next_available_records > 0 ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getFollower_load/currentPage/" . ($currentpage + 1)."/".$keyword;

                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $connection_html .= $load_more_li_tpl->parse();
        }

        $main_content->set('follower', $connection_html);
        $main_content->set('user_id', $user_id);

        $pagination = getPagination($connection_count, count($connection_array), NO_OF_CONNECTION_PER_PAGE, $currentpage);
        if($platform == 'app'){
            $page_data = getPagerData($connection_count, NO_OF_CONNECTION_PER_PAGE,$currentpage);

            //pagination=current_page,total_pages,total
            $app_pagination = array('current_page'=>$currentpage,'total_pages'=>$page_data->numPages,'total'=>$connection_count);

        }


        $main_content->set('pagination', $pagination);


        $user_info = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id' => $user_id))->result();

        $fields = array("%USER_NAME%","%PROFILE_DATA%","%NAV_MENU%");
        $fields_replace = array(
            ucwords(filtering($user_info['first_name'], 'output')) . " " . ucwords(filtering($user_info['last_name'], 'output')),$this->getRightSidebarLeft($user_id),$this->getnavmenu($user_id,'getFollower')
        );

        $main_content_parsed = $main_content->parse();
        if($platform == 'app'){
            $app_array['pagination'] = $app_pagination;
            $app_array['connections'] = $app_array_main;
            $final_result = $app_array;
        } else {
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $final_result;
    }

    public function getAllFollwer($user_id,$user_info,$platform) {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/follower-all-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $removeButton = '';
        if ($user_id != $this->session_user_id) {
            $removeButton .= '<a class="" href="javascript:void(0);" id="remove_following" data-value="' . encryptIt($user_id) . '" title="{LBL_DASHBOARD_REMOVE_FOLLOWING}">
            {LBL_DASHBOARD_REMOVE_FOLLOWING}
            </a>';
        }
        $fields = array(
            "%USER_ID%",
            "%SESSION_USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            // "%USER_HEAD_LINE%",
            "%USER_PROFILE_URL%",
            "%ENCRYPTED_USER_ID%",
            "%REMOVE_BUTTON%",
            "%USER_LOCATION%",
            "%NUMBER_OF_SHARED_CONNATION%",
            "%MESSAGE_URL%",
            "%MESSAGE_TEXT%"
        );

        if($user_info['location_id'] == "")
            $user_info['location_id']=0;


        $connected_user_ids = getFollower($user_id);
        if (is_array($connected_user_ids) && in_array($this->session_user_id, $connected_user_ids)) {
            $send_inmail_url = SITE_URL . "compose-message/" . encryptIt($user_id);
            $send_inmail_text = LBL_SEND_MESSAGE;
        } else {
            $send_inmail_url = SITE_URL . "compose-message/" . encryptIt($user_id);
            $send_inmail_text = LBL_SEND_MESSAGE;
        }
        $loc = getTableValue("tbl_locations", "formatted_address", array("id" => $user_info['location_id']));

        $user_pro_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$user_info['id']));
        $user_image = $this->dashboard_storage->getImageUrl1('av8db','th4_'.$user_pro_pic_name,'users-nct/'.$user_info['id'].'/');
        $is_image = getimagesize($user_image);
        if(!empty($is_image)){
            $user_image = '<img src="'.$user_image.'" alt="'.$user_info['first_name'].' '.$user_info['last_name'].'">';
        }else{
            $user_image = '<span class="profile-picture-character">'.ucfirst($user_info['first_name'][0]).'</span>';
        }

        $fields_replace = array(
            filtering($user_info['id'], 'output', 'int'),
            $this->session_user_id,
            ucwords(filtering($user_info['first_name'], 'output')) . " " . ucwords(filtering($user_info['last_name'], 'output')),
            $user_image,
            //ucwords(getUserHeadline($user_id)),
            get_user_profile_url($user_id),
            encryptIt(filtering($user_info['id'], 'output', 'int')),
            $removeButton,
            $loc,
            count(getCommonConnections($this->session_user_id,$user_info['id'])),
            $send_inmail_url,
            $send_inmail_text
        );

        if($platform == 'app'){
            $mutual_count = count(getCommonConnections($this->current_user_id,$user_info['id']));

            if($user_info['profile_picture_name'] != '' && file_exists(DIR_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'])){
                $user_image = SITE_UPD_USERS.$user_info['id'].'/th4_'.$user_info['profile_picture_name'];
            } else {
                $user_image = "";
            }

            $user_id = $user_info['id'];
            $user_name=filtering($user_info['first_name'], 'output') . " " . filtering($user_info['last_name'], 'output');
            //$tagline = getUserHeadline($user_info['id']);
            $location = $loc;

            $content = array('user_id'=>$user_id,'user_name'=>$user_name,'user_image'=>$user_image,'tagline'=>'','location'=>$location,'mutual_connection'=>$mutual_count);
        } else {
            $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $content;
    }
    public function del_comment($comment_id =0){


        $response = array();
        $response['status'] = false;

        $affectedRows = $this->db->delete('tbl_comments', array('id' => $comment_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success']=COMMENT_DELETE_SUCCESSFULLY;

        }
        return $response;

    }
    public function edit_comment($comment_id=0,$comment=''){
        $response = array();
        $response['status'] = false;
        $update_on=date("Y-m-d H:i:s");
        $affectedRows = $this->db->update("tbl_comments", array('comment'=>$comment,"update_on"=>$update_on), array('id' => $comment_id))->affectedRows();

        if ($affectedRows && $affectedRows > 0) {
            $dateDifference = get_time_difference($update_on, date("Y-m-d H:i:s"));
                if ($dateDifference['days']) {
                    $time_ago = $dateDifference['days'] ." ". LBL_DAYS_AGO;
                } else if ($dateDifference['hours']) {
                    $time_ago = $dateDifference['hours'] ." ".LBL_HOURS_AGO;
                } else if ($dateDifference['minutes']) {
                    $time_ago = $dateDifference['minutes'] ." ".LBL_MINS_AGO;
                } else if ($dateDifference['seconds']) {
                    $time_ago = $dateDifference['seconds'] ." ".LBL_SEC_AGO;
                } else {
                    $time_ago = LBL_AGO;
                }
            $response['status'] = true;
            $response['date']=$time_ago;
            $response['success']=EDIT_COMMENT_SUCCESS;
        }
        return $response;
    }

    public function sendInvitationToUser($current_user_id,$user_message,$user_email){
        $response = array();
        $response['status'] = false;

        $send_arr = array();
        
        $send_arr['sender_id'] = $current_user_id;
        $send_arr['custom_message'] = $user_message;
        $send_arr['receiver_email'] = $user_email;
        $send_arr['createdAt'] = date("Y-m-d H:i:s");

        $lastInsertId = $this->db->insert("tbl_send_invitation", $send_arr)->getLastInsertId();        

        $user_data = $this->db->select("tbl_users","*",array("id"=>$current_user_id))->result();
        if($lastInsertId > 0){
            $arrayCont['SITE_NM'] = SITE_NM;
            $arrayCont['SITE_LOGO_URL'] = SITE_LOGO_URL;
            $arrayCont['greetings'] = $user_email;
            $arrayCont['user_message'] = $user_message;
            $arrayCont['user_name'] = INVITATION_EMAIL_MESSAGE .' <a href="' . SITE_URL .'signup" target="_blank">Click here</a> '.INVITATION_EMAIL_MESSAGE_LAST;
            
            $d = generateEmailTemplateSendEmail("invite_friend", $arrayCont, $user_email);
            $response['status'] = true;
            $response['redirect_url'] = SITE_URL ."dashboard/";
            $response['success'] = SUCCESS_INVITE_FRIEND_MESSAGE;
            return $response;
        }else{
            $response['status'] = false;
            $response['redirect_url'] = SITE_URL ."dashboard/";
            $response['err'] = ERROR_INVITE_FRIEND_MESSAGE;
            return $response;
        }
    }
    public function addFeedAsReported($feed_id, $user_id){
        $response = array();
        $response['status'] = false;

        $feed_data=array();

        $feedId=getTableValue("tbl_feeds","id",array("id"=>$feed_id,'isFeedReported' => 'n'));

        $feed_data['isFeedReported']='y';
        $feed_data['reportedUser']=$user_id;
        //$feed_data['updated_on']=date('Y-m-d H:i:s');
        if($feedId>0){
            $this->db->update('tbl_feeds',$feed_data,array('id'=>$feed_id));
            $response['status'] = "suc";
            $response['redirect_url'] = SITE_URL ."dashboard/";
            $response['message'] = SUCCESS_FEED_REPORTED;
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."dashboard/";
            $response['message'] = ERROR_FEED_REPORTED_NOT_EXISTS;
        }
        return $response;
    }
    public function upload_object($bucketName, $objectName, $source)
{
    $storage = new StorageClient();
    $file = fopen($source, 'r');
    $bucket = $storage->bucket($bucketName);
    $object = $bucket->upload($file, [
        'name' => $objectName
    ]);
    return $object;
}
} ?>