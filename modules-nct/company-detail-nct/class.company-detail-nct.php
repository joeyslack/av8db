<?php 
class Company_detail extends Home {
    function __construct($company_id = '',$current_user_id,$platform='web') {
        $this->company_id = $company_id;
        $this->platform = $platform;
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
        $this->app_feed_per_page=10;
        if($this->company_id > 0) {
            $query = "SELECT comp.company_type,comp.id,comp.company_name,comp.company_logo,comp.company_industry_id,comp.owner_email_address,comp.user_id,comp.website_of_company,comp.foundation_year,comp.company_description,comp.location,comp.lat,comp.lng,
            i.industry_name_".$this->lId." as industry_name,host.first_name,host.last_name,host.id as host_id
            FROM tbl_companies comp 
            LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id 
            left join tbl_users as host on (host.id = comp.user_id)
            WHERE comp.id = ? and comp.status = ? ";
            $where_array=array($this->company_id,'a');
            $company_details_array = $this->db->pdoQuery($query,$where_array)->result();
            $totalRow = $this->db->pdoQuery($query,$where_array)->affectedRows();
            
            if($totalRow > 0){
                $this->company_name=filtering($company_details_array['company_name'], 'output');
                $this->industry_id=filtering($company_details_array['company_industry_id'], 'output', 'int');
                $this->industry_name=filtering($company_details_array['industry_name'], 'output');
                $this->range_of_no_of_employees=isset($company_details_array['range_of_no_of_employees']) ? $company_details_array['range_of_no_of_employees'] : '';
                $this->owner_email_address=filtering($company_details_array['owner_email_address'], 'output');
                //$this->company_logo_url=getImageURL('company_logo', $company_details_array['id'], "th2",$this->platform);
                include "storage.php";
                $storage = new storage();
                $this->company_logo_url = $storage->getImageUrl1('av8db','th2_'.$company_details_array['company_logo'],'company-logos-nct/');
                $this->company_url=get_company_detail_url($company_details_array['id']);
                $this->user_id=filtering($company_details_array['user_id'], 'output', 'int');
                $this->website_of_company=filtering($company_details_array['website_of_company'], 'output');
                $this->foundation_year=filtering($company_details_array['foundation_year'], 'output');
                $this->company_description=filtering($company_details_array['company_description'],'output','text');
                $this->first_name=filtering($company_details_array['first_name'], 'output');
                $this->last_name=filtering($company_details_array['last_name'], 'output');
                // $this->host_image_url = getImageUrl("user_profile_picture", $this->user_id, "th2",$platform);
                $host_profile_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$company_details_array['host_id']));
                $this->host_image_url = $storage->getImageUrl1("av8db",'th2_'.$host_profile_pic_name,'users-nct/'.$company_details_array['host_id'].'/');
                $this->host_headline = '';
                //$this->host_headline = getUserHeadline($this->user_id);
                $this->company_type=filtering($company_details_array['company_type'], 'output');
                $this->location=filtering($company_details_array['location'], 'output');
                $this->lat=filtering($company_details_array['lat'], 'output');
                $this->lng=filtering($company_details_array['lng'], 'output');

            } else {
                if($this->platform == 'web')
                    redirectPage(SITE_URL);
            }
        }
    }

    public function getCompanyPageContent($page) {

        $final_result = NULL;
        $company_follower_users = array();
        $company_followers = array();
        $company_employees_detail = array();
        
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->set('share_on_social_media', $this->getSocialSharingIcons());
        $main_content->set('subscribed_membership_plan_details', $this->getSubscribedMembershipPlan($this->current_user_id));
        $followers_contaier_active = $jobs_contaier_active = $home_page_active = '';
        $show_tabs = "";
        if ($this->current_user_id == $this->user_id) {
            $main_content->set('followers_container', '');
            $main_content->set('jobs_container', '');
            $main_content->set('home_container', '');
            if (isset($_GET['company-followers'])) {
                    $main_content->set('followers_container', $this->getfollowersContainer($this->company_id));
                    $followers_contaier_active = 'active';
            } else if (isset($_GET['company-jobs'])) {
                    $main_content->set('jobs_container', $this->getJobsContainer($this->company_id));
                    $jobs_contaier_active = 'active';
            } else {
                $main_content->set('home_container', $this->getHomePageContainer($this->company_id));
                $home_page_active = 'active';
            }
        } else if (in_array($this->current_user_id, $company_followers)) {
            $show_tabs = "hidden";
            $main_content->set('followers_container', '');
            $main_content->set('jobs_container', '');
            $main_content->set('home_container', $this->getHomePageContainer($this->company_id, "guest"));
        } else {
            $show_tabs = "hidden";
            $main_content->set('followers_container', '');
            $main_content->set('jobs_container', '');
            $main_content->set('home_container', $this->getHomePageContainer($this->company_id, "guest"));
        }
        $first_degree_conenction_user_ids = '';
        // $company_follower_users = $this->db->pdoQuery("SELECT user_id FROM tbl_user_experiences 
        //         WHERE company_id = ? AND is_current = ? ",array($this->company_id,'y'))->results();
        // if($company_follower_users) {
        //     foreach ($company_follower_users as $key => $value) {
        //         $company_followers[] = $value['user_id'];
        //     }
        // }
        //$follower_user_ids = $company_followers;
        $first_connected_user_ids = getConnections($this->current_user_id);
        //_print_r( $follower_user_ids);
        $second_connected_user_ids = getSecondDegreeConnections($this->current_user_id);
        //$first_degree_conenction_user_ids = array_intersect($first_connected_user_ids, $follower_user_ids);
       // $first_degree_conenction_count = count($first_degree_conenction_user_ids);
        //$second_degree_conenction_user_ids = array_intersect($second_connected_user_ids, $follower_user_ids);
        //$second_degree_conenction_count = count($second_degree_conenction_user_ids);
        //$company_employees_detail = $this->db->pdoQuery("SELECT COUNT(*) as company_employees FROM tbl_user_experiences WHERE company_id = ? AND is_current = ?  ",array($this->company_id,'y'))->result();
       // $company_employees_count = $company_employees_detail['company_employees'];
        $connected_user_img = array();
        if($first_degree_conenction_user_ids != '') {
            foreach ($first_degree_conenction_user_ids as $key => $value) {
                $connected_user_img[$key]['user_id'] = $value;
                $connected_user_img[$key]['user_img'] = getImageURL("user_profile_picture", $value, "th4",$this->platform);
                $connected_user_img[$key]['rank'] = LBL_FIRST;
            }
        }
        //print_r($connected_user_img);exit();
        if(count($connected_user_img) < 4) {
            if($second_degree_conenction_user_ids) {
                foreach ($second_degree_conenction_user_ids as $key => $value) {
                    $connected_user_img[$key]['user_id'] = $value;
                    $connected_user_img[$key]['user_img'] = getImageURL("user_profile_picture", $value, "th4",$this->platform);
                    $connected_user_img[$key]['rank'] = LBL_SECOND;
                }
            }
        }
        $how_connected = array();
        $user_iamge_content = '';

        if($connected_user_img) {
            if(count($connected_user_img) > 4) {
                $connected_user_img =  array_slice($connected_user_img, 4);    
            }
            $connected_image_tpl = new Templater(DIR_TMPL . $this->module . "/connected-user-image-li-nct.tpl.php");
            $connected_image_tpl_parse = $connected_image_tpl->parse();
            $fields_image = array(
                "%USER_IMAGE%",
                "%RANK%",
                "%USER_URL%",
                "%USER_NAME%",
            );
            foreach ($connected_user_img as $key => $value) {
                $first_name = filtering(getTableValue("tbl_users", "first_name", array("id" => $value['user_id'])));
                $last_name = filtering(getTableValue("tbl_users", "last_name", array("id" => $value['user_id'])));
                $user_name_full = $first_name . " " . $last_name;
                $user_status=get_user_status($value['user_id']);
                $user_profile_url="javascript:void(0)";
                if($user_status=='a'){
                    $user_profile_url = get_user_profile_url($value['user_id']);
 
                }
                //$user_profile_url = get_user_profile_url($value['user_id']);
                $fields_replace_image = array(
                    $value['user_img'],
                    $value['rank'],
                    $user_profile_url,
                    ucwords($user_name_full)
                );
                if($this->platform == 'app'){
                    $how_connected[] = array('user_id'=>$value['user_id'],'user_name'=>$user_name_full,'user_img'=>$value['user_img'],'rank'=>$value['rank'],'user_status'=>$user_status);
                } else {
                    $user_iamge_content.=str_replace($fields_image, $fields_replace_image, $connected_image_tpl_parse);
                }
            }
        }
        $main_content->set("connected_images", $user_iamge_content);       
        //company admin detail start
        $company_admin_tpl = new Templater(DIR_TMPL . $this->module . "/company-admin-nct.tpl.php");
        $company_admin_tpl_parse = $company_admin_tpl->parse();                
        $fields_admin = array(
            "%COMPANY_ADMIN_URL%",
            "%COMPANY_ADMIN_NAME%",
            "%COMPANY_ADMIN_IMG_URL%",
            "%COMPANY_ADMIN_HEADLINE%"
        );

        $is_image = getimagesize($this->host_image_url);
        if(!empty($is_image)){
            $host_image_url = '<img src="'.$this->host_image_url.'">';
        }else{
            $host_image_url = '<span class="profile-picture-character">'.ucfirst(mb_substr($this->first_name, 0, 1, 'utf-8')).'</span>';
        }
        
        $fields_replace_admin = array(
            get_user_profile_url($this->user_id),
            ucwords($this->first_name)." ".ucwords($this->last_name),
            $host_image_url,
            $this->host_headline
        );
        $company_admin_content = '';
        $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        if($this->user_id != $user_id){
            $company_admin_content = str_replace($fields_admin, $fields_replace_admin, $company_admin_tpl_parse);
        }
        $hide_admin_content = ($company_admin_content == '') ? 'hide' : '';
        $main_content->set("company_admin", $company_admin_content);
        //company admin detail over
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%COMPANY_ID%",
            "%ENCRYPTED_COMPANY_ID%",
            "%INDUSTRY_NAME%",
            "%COMPANY_NAME%",
            "%COMPANY_LOGO_URL%",
            "%COMPANY_URL%",
            "%WEBSITE_OF_COMPANY%",
            "%RANGE_OF_NO_OF_EMPLOYEES%",
            "%COMPANY_FOLLOWERS%",
            "%POST_JOB_URL%",
            "%COMPANY_EMAIL%",
            "%FOUNDATION_YEAR%",
            "%COMPANY_LOCATION%",
            "%COMPANY_DESCRIPTION%",
            "%FOLLOW_COMPANY_URL%",
            "%FOLLOWERS_CONTAIER_ACTIVE%",
            "%JOBS_CONTAIER_ACTIVE%",
            "%HOME_PAGE_ACTIVE%",
            "%SHOW_TABS%",
            // "%FIRST_DEGREE_CONENCTION_COUNT%",
            // "%SECOND_DEGREE_CONENCTION_COUNT%",
            //"%COMPANY_EMPLOYEES_COUNT%",
            "%VIEW_ALL_CONNECTED_USERS%",
            "%COMPANY_EDIT_URL%",
            "%COMPANY_NAME_OPTIONS%",
            "%CATEGORY_OPTIONS%",
            "%COMPANY_LOCATION_OPTION%",
            "%JOB_POST_HIDE%",
            "%GET_REVIEW_LIST%",
            "%LICENSES_ENDORSEMENTS_OPTIONS%",
            "%HIDE_ADMIN_CONTENT%"
        );
        $company_edit_url="";
        $job_post_hide='hidden';

        if($this->user_id == $this->current_user_id){
            $company_edit_url="<a href='".SITE_URL."edit-company/".encryptIt($this->company_id)."' class='icon-edit' title='edit-company'></a>";
            $job_post_hide='';
        }
        if($this->current_user_id > 0){
            $follow_company_url = $this->followCompanyUrl($this->company_id);
        }
        $getCompanyFollowers = $this->getCompanyFollowers();
        $getCompanyHeadQuarter = $this->getCompanyHeadQuarter($this->company_id);
        // $company_logo_url = ($this->company_logo_url == '') ? '<span class="company-letter-square company-letter">'.ucfirst($this->company_name[0]).'</span>' : $this->company_logo_url;
        $is_image = getimagesize($this->company_logo_url);
        if(!empty($is_image)){
            $company_logo_url = '<img src="'.$this->company_logo_url.'">';
        }else{
            $company_logo_url = '<span class="profile-picture-character">'.ucfirst($this->company_name).'</span>';
        }
        $fields_replace = array(
            $this->company_id,
            encryptIt($this->company_id),
            ucwords($this->industry_name),
            ucwords($this->company_name),
            $company_logo_url,
            $this->company_url,
            $this->website_of_company,
            $this->range_of_no_of_employees,
            $getCompanyFollowers,
            SITE_URL . "create-job-form",
            $this->owner_email_address,
            $this->foundation_year,
            $getCompanyHeadQuarter,
            $this->company_description,
            $follow_company_url,
            $followers_contaier_active,
            $jobs_contaier_active,
            $home_page_active,
            $show_tabs,
            // $first_degree_conenction_count,
            // $second_degree_conenction_count,
            //$company_employees_count,
            SITE_URL . "search/users?company[]=".$this->company_id."",
            $company_edit_url,
            $this->getCompanyDD(),
            $this->getCategoriesDD(),
            $this->getCompanyLocation(),
            $job_post_hide,
            $this->getReviewList($this->company_id),
            $this->getLicensesEndorsements(),
            $hide_admin_content
        );
        //echo "<pre>";print_r($fields_replace);exit();
        if($this->platform == 'app'){
            $banner_image = getImageURL("company_banner", $this->company_id, "th1",$this->platform);
            
            $total_feeds = $this->db->count('tbl_feeds',array('company_id'=>$this->company_id,'type'=>'c','status'=>'p'));
            $recent_activities = $this->getFeeds($this->company_id,$page);
            $page_data = getPagerData($total_feeds, $this->app_feed_per_page,$page);
            if($page_data->numPages < $page){
                $recent_activities = array();
            }


            $is_followed_count = $this->db->count('tbl_company_followers',array('company_id'=>$this->company_id,'user_id'=>$this->current_user_id));
            $is_followed = (($is_followed_count > 0) ?'y':'n');

            $recent_activities_pagination = array('current_page'=>$page,'total_pages'=>$page_data->numPages,'total'=>$total_feeds);
            $job_avl='n';
            $where_con=' AND last_date_of_application >= CURDATE()';
            if($this->user_id == $this->current_user_id){
                $where_con='';
            }
            $company_count=$this->db->pdoQuery('SELECT id from tbl_jobs WHERE company_id = ? AND status = ? '.$where_con.' ',array($this->company_id,'a'))->affectedRows();
            if($company_count > 0 && $this->current_user_id > 0){
                $job_avl='y';
            }


            $basic = array(
                'company_name'=>$this->company_name,
                'industry_name'=>$this->industry_name,
                'company_detail_url' =>get_company_detail_url($this->company_id),
                'website_of_company'=>$this->website_of_company,
                'owner_email_address'=>$this->owner_email_address,
                'range_of_no_of_employees'=>$this->range_of_no_of_employees,
                'foundation_year'=>$this->foundation_year,
                'followers'=>$getCompanyFollowers,
                'company_logo_url'=>$this->company_logo_url,
                'banner_image'=>$banner_image,
                'headquarter'=>$getCompanyHeadQuarter,
                'description'=>$this->company_description,
                'is_followed'=>$is_followed,
                'job_avl'=>$job_avl,
                'admin_id'=>$this->user_id,
                'admin_name'=>$this->first_name." ".$this->last_name,
                'admin_image'=>$this->host_image_url,
                'admin_headline'=>$this->host_headline,
                'how_connected'=>$how_connected,
                'first_degree_conenction_count'=>$first_degree_conenction_count,
                'second_degree_conenction_count'=>$second_degree_conenction_count,
                'company_employees_count'=>$company_employees_count,
                'recent_activities'=>$recent_activities,
                'recent_activities_pagination' => $recent_activities_pagination,
                'company_type'=>$this->company_type,

            );
            $final_result = $basic;
        } else {
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        }
        return $final_result;
    }
    public function getfollowersContainer($company_id) {
        $main_content = new Templater(DIR_TMPL . $this->module . "/follower-list-container-nct.tpl.php");
        $response = $this->getFollowers(1, $company_id);
        $main_content->set('followers', $response['followers']);
        $main_content_parsed =  $main_content->parse();
        $fields = array("");
        $fields_replace = array("");
        $content = str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function getJobsContainer($company_id) {
        $main_content = new Templater(DIR_TMPL . $this->module . "/job-list-container-nct.tpl.php");
        $response = $this->getCompanyJobs(1, $company_id);
        $main_content->set('jobs', $response['jobs']);
        $main_content_parsed =  $main_content->parse();
        $fields = array("");
        $fields_replace = array("");
        $content = str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function getStatistics($company_id) {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/statistics-container-nct.tpl.php");
        $main_content_parsed =  $main_content->parse();
        $where_array=array($company_id);
        //Like statistics
        $todayLikes = $this->db->pdoQuery('select count(l.id) as todayLikes from tbl_likes as l LEFT JOIN tbl_feeds as f ON(l.feed_id = f.id) where f.company_id = ?  and date(liked_on) = DATE(NOW())',$where_array)->result();
        $weekLikes = $this->db->pdoQuery('select count(l.id) as weekLikes from tbl_likes as l LEFT JOIN tbl_feeds as f ON(l.feed_id = f.id) where f.company_id = ?  and WEEKOFYEAR(liked_on) = WEEKOFYEAR(NOW())',$where_array)->result();
        $monthLikes = $this->db->pdoQuery('select count(l.id) as monthLikes from tbl_likes as l LEFT JOIN tbl_feeds as f ON(l.feed_id = f.id) where f.company_id = ?  and MONTH(liked_on) = MONTH(NOW())',$where_array)->result();
        //Comment statistics
        $todayComment = $this->db->pdoQuery('select count(c.id) as todayComments from tbl_comments as c LEFT JOIN tbl_feeds as f ON(c.feed_id = f.id) where f.company_id = ? and date(commented_on) = DATE(NOW())',$where_array)->result();
        $weekComment = $this->db->pdoQuery('select count(c.id) as weekComment from tbl_comments as c LEFT JOIN tbl_feeds as f ON(c.feed_id = f.id) where f.company_id = ? and WEEKOFYEAR(commented_on) = WEEKOFYEAR(NOW())',$where_array)->result();
        $monthComment = $this->db->pdoQuery('select count(c.id) as monthComment from tbl_comments as c LEFT JOIN tbl_feeds as f ON(c.feed_id = f.id) where f.company_id = ? and MONTH(commented_on) = MONTH(NOW())',$where_array)->result();
        //Share statistics
        $todayShare = $this->db->pdoQuery('select count(c.id) as todayShare from tbl_feeds as f LEFT JOIN tbl_feeds AS fh
        ON (fh.id = f.shared_feed_id) LEFT JOIN tbl_companies as c ON(c.id = fh.company_id) where f.shared_feed_id > 0 and c.id = ? and date(f.added_on) = DATE(NOW())',$where_array)->result();
        $weekShare = $this->db->pdoQuery('select count(c.id) as weekShare from tbl_feeds as f LEFT JOIN tbl_feeds AS fh
        ON (fh.id = f.shared_feed_id) LEFT JOIN tbl_companies as c ON(c.id = fh.company_id) where f.shared_feed_id > 0 and c.id = ? and WEEKOFYEAR(f.added_on) = WEEKOFYEAR(NOW())',$where_array)->result();
        $monthShare = $this->db->pdoQuery('select count(c.id) as monthShare from tbl_feeds as f LEFT JOIN tbl_feeds AS fh
        ON (fh.id = f.shared_feed_id) LEFT JOIN tbl_companies as c ON(c.id = fh.company_id) where f.shared_feed_id > 0 and c.id = ? and MONTH(f.added_on) = MONTH(NOW())',$where_array)->result();      
        //Followers statistics
        $todayFollower = $this->db->pdoQuery('select count(id) as todayFollower from tbl_company_followers where company_id = ? and date(added_on) = DATE(NOW())',$where_array)->result();
        $weekFollower = $this->db->pdoQuery('select count(id) as weekFollower from tbl_company_followers where company_id = ? and WEEKOFYEAR(added_on) = WEEKOFYEAR(NOW())',$where_array)->result();
        $monthFollower = $this->db->pdoQuery('select count(id) as monthFollower from tbl_company_followers where company_id = ? and MONTH(added_on) = MONTH(NOW())',$where_array)->result();          
        $fields = array("%TODAY_LIKE%","%WEEK_LIKE%","%MONTH_LIKE%","%TODAY_COMMENT%","%WEEK_COMMENT%","%MONTH_COMMENT%","%TODAY_SHARE%","%WEEK_SHARE%","%MONTH_SHARE%","%TODAY_FOLLOWER%","%WEEK_FOLLOWER%","%MONTH_FOLLOWER%");
        $fields_replace = array($todayLikes['todayLikes'],$weekLikes['weekLikes'],$monthLikes['monthLikes'],$todayComment['todayComments'],$weekComment['weekComment'],$monthComment['monthComment'],$todayShare['todayShare'],$weekShare['weekShare'],$monthShare['monthShare'],$todayFollower['todayFollower'],$weekFollower['weekFollower'],$monthFollower['monthFollower']);
        if($this->platform=='app'){
            return array('today_likes'=>$todayLikes['todayLikes'],'week_likes'=>$weekLikes['weekLikes'],'month_likes'=>$monthLikes['monthLikes'],'today_comment'=>$todayComment['todayComments'],'week_comment'=>$weekComment['weekComment'],'month_comment'=>$monthComment['monthComment'],'today_share'=>$todayShare['todayShare'],'week_share'=>$weekShare['weekShare'],'month_share'=>$monthShare['monthShare'],'today_follower'=>$todayFollower['todayFollower'],'week_follower'=>$weekFollower['weekFollower'],'month_follower'=>$monthFollower['monthFollower']);
        } else {
            $content = str_replace($fields, $fields_replace, $main_content_parsed);
        }
        return $content;
    }
    public function getNotifications($company_id,$currentPage=1) {
        $response = array();
        $content = '';
        $user_id = filtering($this->current_user_id, 'input', 'int');
        $limit = 10;
        require_once('storage.php');
        $notification_storage = new storage();
        $offset = ($currentPage - 1 ) * $limit;
        $total_notifications = $this->db->count('tbl_notifications',array('user_id'=>$user_id,'company_id'=>$company_id));
        $query = "SELECT n.id,n.added_on,n.type,n.action_by_user_id,n.feed_id,n.group_id,n.job_id,n.company_id FROM tbl_notifications n WHERE n.user_id = ? AND n.company_id = ? ORDER BY n.id DESC ";
        if($this->platform=='app'){
            $query .= " LIMIT ".$limit." OFFSET ".$offset;
        } else {
            $query .= " LIMIT 0,10";
        }
        //$where_array=array($user_id,$company_id);
        $getAllResults = $this->db->pdoQuery($query,array($user_id,$company_id))->results();
        $totalRows = count($getAllResults);
        if ($totalRows) {
            $next_notifications = $this->db->pdoQuery($query,array($user_id,$company_id))->results();
            $next_available_records = count($next_notifications);
            $notification = new Templater(DIR_TMPL . $this->module ."/single-notification-header-nct.tpl.php");
            $notification_parsed = $notification->parse();
            $field = array(
                '%NOTIFICATION_TEXT%',
                '%NOTIFICATION_URL%',
                '%NOTIFICATION_TITLE%',
                '%NOTIFICATION_TIME%',
                '%USER_IMG%',
            );
            foreach ($getAllResults as $notification) {
                $notification_date = $notification['added_on'];
                $time_ago = time_elapsed_string(strtotime($notification['added_on']));
                $type = $notification['type'];
                $action_by_user_id = filtering($notification['action_by_user_id'], 'input', 'int');
                $feed_id = filtering($notification['feed_id'], 'input', 'int');
                $group_id = filtering($notification['group_id'], 'input', 'int');
                $job_id = filtering($notification['job_id'], 'input', 'int');
                $company_id = filtering($notification['company_id'], 'input', 'int');
                if ($action_by_user_id > 0) {
                    $action_by_user_details = $this->db->select("tbl_users", array('first_name','last_name'), array("id" => $action_by_user_id))->result();
                    $action_by_user_name = filtering($action_by_user_details['first_name']) . " " . filtering($action_by_user_details['last_name']);
                }
                if ($feed_id > 0) {
                    $feed_details = $this->db->select("tbl_feeds", array('post_title'), array("id" => $feed_id))->result();
                    $post_title = filtering($feed_details['post_title']);
                }
                if ($group_id > 0) {
                    $group_details = $this->db->select("tbl_groups", array('group_name'), array("id" => $group_id))->result();
                    $group_name = filtering($group_details['group_name']);
                }
                if ($job_id > 0) {
                    $job_details = $this->db->select("tbl_jobs", array('job_title'), array("id" => $job_id))->result();
                    $job_title = filtering($job_details['job_title']);
                }
                if ($company_id > 0) {
                    $company_details=$this->db->select("tbl_companies", array('company_name'), array("id" => $company_id))->result();
                    $company_name = filtering($company_details['company_name']);
                }
                switch ($type) {
                    case 'cra' : {
                            $notification_text = LBL_COM_DET_YOUR_CONNECTION_REQUEST_ACCEPTED." " . $action_by_user_name;
                            $notification_url = get_user_profile_url($action_by_user_id);
                            $notification_title = LBL_CONNECTION_REQUEST_ACCEPTED;
                            break;
                        }
                    case 'like' : {
                            $notification_text = $action_by_user_name . " ".LBL_LIKED_YOUR_POST." " .  $post_title;
                            $notification_url = get_user_profile_url($action_by_user_id);
                            $notification_title = $post_title;
                            break;
                        }
                    case 'comment' : {
                            $notification_text = $action_by_user_name . " ".LBL_COMMENTED_ON_YOUR_POST." " .  $post_title;
                            $notification_url = get_user_profile_url($action_by_user_id);
                            $notification_title = $post_title;
                            break;
                        }
                    case 'share' : {
                            $notification_text = $action_by_user_name . " ".LBL_SHARED_YOUR_POST." " .  $post_title;
                            $notification_url = get_user_profile_url($action_by_user_id);
                            $notification_title = $post_title;
                            break;
                        }
                    case 'rgji' : {
                            $notification_text = $action_by_user_name . " ".LBL_INVITED_YOU_TO_JOIN_GROUP." " .  $group_name;
                            $notification_url = get_group_detail_url($group_id);
                            $notification_title = LBL_GROUP_JOINING_INVITATION;
                            break;
                        }
                    case 'gjra' : {
                            $notification_text = $action_by_user_name . " ".LBL_ACCEPTED_YOUR_REQUEST_FOR_JOINING_GROUP." " .  $group_name;
                            $notification_url = get_group_detail_url($group_id);
                            $notification_title = LBL_GROUP_JOINING_REQUEST_ACCEPTED;
                            break;
                        }
                    case 'aj' : {
                            $notification_text = $action_by_user_name . " ".LBL_APPLIED_ON_JOB." " .  $job_title;
                            $notification_url = get_job_detail_url($job_id);
                            $notification_title = LBL_APPLIED_ON_JOB_CAPITAL;
                            break;
                        }
                    case 'fc' : {
                            $notification_text = $action_by_user_name . " ".LBL_FOLLOWED_COMPANY." " .  $company_name;
                            $notification_url = get_company_detail_url($company_id);
                            $notification_title = LBL_FOLLOW_COMPANY;
                            break;
                        }
                }
                $user_profile_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$action_by_user_id));
                $user_img = $notification_storage->getImageUrl1('av8db','th2_'.$user_profile_pic_name,'users-nct/'.$action_by_user_id.'/');
                $is_image = getimagesize($user_img);
                if(!empty($is_image)){
                    $user_img = '<img src="'.$user_img.'" alt="'.$action_by_user_name.'">';
                }else{
                    $user_img = '<span class="profile-picture-character">'.$action_by_user_name.'</span>';
                }
                $field_replace = array(
                    filtering($notification_text),
                    filtering($notification_url),
                    filtering($notification_title),
                    $time_ago,
                    $user_img
                );
                if($this->platform == 'app'){
                    $app_profile_photo = $user_img;
                    $app_name = $action_by_user_name;
                    $app_text = filtering($notification_title);
                    $app_timeline = $time_ago;
                    $app_notifications[] = array('profile_photo'=>$app_profile_photo,'name'=>$app_name,'text'=>$app_text,'timeline'=>$app_timeline);
                } else {
                    $content .= str_replace($field, $field_replace, $notification_parsed);
                }
                $this->db->update("tbl_notifications", array("is_notified" => 'y'), array("id" => $notification['id']));
            }
            if ($next_available_records > 0) {
                $view_all_notification_tpl = new Templater(DIR_TMPL . $this->module ."/view-all-notification-nct.tpl.php");
                $content .=  str_replace(array("%VIEW_ALL_NOTIFICATION_URL%"), array(SITE_URL . "view-all-notification/".base64_encode($company_id)), $view_all_notification_tpl->parse());
            }
        } else {
            
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = LBL_NO_DATA_FOUND;
            $no_result_found_tpl->set('message', $message);
            $final_result_html = $no_result_found_tpl->parse();
            $content = $final_result_html;
        }
        if($this->platform == 'app'){
            $page_data = getPagerData($total_notifications, $limit,$currentPage);
            $app_content['notifications'] = (empty($app_notifications)?array():$app_notifications);
            $app_content['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total_notifications);
            return $app_content;
        }
        return $content;
    }
    public function getNotificationslist($company_id){
        $content = NULL;
        $notifications = $this->getNotifications($company_id);
        $notification = new Templater(DIR_TMPL . $this->module ."/notification-nct.tpl.php");
        $notification_parsed = $notification->parse();
        $field = array("%NOTIFICATION_LIST%");
        $field_replace = array($notifications);
        $content = str_replace($field, $field_replace, $notification_parsed);
        return $content;
    }
    public function getHomePageContainer($company_id, $user_type = "admin") {
        $main_content = new Templater(DIR_TMPL . $this->module . "/home-container-nct.tpl.php");
        $main_content->set('share_on_social_media', $this->getSocialSharingIcons());

        $post_an_update_url = SITE_URL . "post-an-update";
        $share_update_panel = '';
        if($user_type == "guest") {$share_update_panel = 'hidden';}
        $fields = array(
            "%hide_banner%",
            "%POST_AN_UPDATE_URL%", 
            "%FEEDS%", 
            "%ENC_COMPANY_ID%", 
            "%COMPANY_LOCATION%", 
            "%FOUNDATION_YEAR%",
            "%COMPANY_EMAIL%",
            "%COMPANY_DESCRIPTION%",
            "%LOCATION%",
            "%LAT%",
            "%LNG%",
            "%SHARE_UPDATE_PANEL%",
            "%COMPANY_NAME%",
            "%COMPANY_URL%",
            "%COMPANY_LOGO_URL%",
            "%COMPANY_EDIT_URL%",
            "%INDUSTRY_NAME%",
            "%WEBSITE_OF_COMPANY%",
            "%RANGE_OF_NO_OF_EMPLOYEES%",
            "%FOLLOW_COMPANY_URL%",
            "%COMPANY_FOLLOWERS%",
            "%LOCATION_HIDE%",
            "%HIDE_WEB%",
            "%YEAR_HIDE%",
            "%DES_HIDE%",
            "%JOB_CLASS%",
            "%JOB_URL%",
            "%TARGET_LINK%",
            "%COVER_IMG%",
            "%COMPANY_ID%",
            "%HIDE_RATE_REVIEW_FOR_OWNER%",
            "%HIDE_WITHOUT_LOGIN%",
            "%EDIT_USER%"
        );
        $job_class='hidden';
        $job_url=SITE_URL . "search/jobs?company[]=".$company_id."";

        $target_link="target='_blank'";
        // $banner_image = getImageURL("company_banner", $company_id, "th1");
        require_once('storage.php');
        $comp_cover_storage = new storage();
        $comp_banner_name = getTableValue('tbl_companies','banner_image',array('id'=>$company_id));
        $banner_image = $comp_cover_storage->getImageUrl1('av8db','th1_'.$comp_banner_name,'company-banner-images-nct/');
        $is_image = getimagesize($banner_image);
        if(!empty($is_image)){
            $banner_image_url = $banner_image;
            $banner_image = '<img src="'.$banner_image.'" alt="'.$this->company_name.'">';
        }else{
            $banner_image_url = SITE_URL.'themes-nct/images-nct/no-image.jpg';
            $banner_image = '<img src="https://storage.googleapis.com/av8db/no-image-cover.jpg" alt="'.$this->company_name.'">';
        }

        $main_content->set('banner_image', $banner_image);
        $hideBanner = ($banner_image_url == SITE_URL.'themes-nct/images-nct/no-image.jpg' && $this->session_user_id != $this->user_id) ? 'hide' : '';
        $n_img = getimagesize($this->company_logo_url);
        $com_logos = '';
        if(!empty($n_img)){
            $com_logos = '<picture>
                            <img src="'.$this->company_logo_url.'" class="" alt="'.$this->company_name.'" /> 
                        </picture>';
        }else{
            $com_logos = '<span class="company-letter-square company-letter">'.ucfirst($this->company_name[0]).'</span>';
        }

        $company_logo_url = ($this->company_logo_url == '') ? '<span class="company-letter-square company-letter">'.ucfirst($this->company_name[0]).'</span>' : $com_logos;
        $company_edit_url="";
        $where_con=' AND last_date_of_application >= CURDATE()';
        if($this->user_id == $this->current_user_id){
            $company_edit_url="<a href='".SITE_URL."edit-company/".encryptIt($this->company_id)."' class='icon-edit' title='edit-company'></a>";
            $job_url="{SITE_URL}company/".$company_id."?company-jobs";
            $target_link='';
            $where_con='';
        }

        $company_count=$this->db->pdoQuery('SELECT id from tbl_jobs WHERE company_id = ? AND status = ? '.$where_con.' ',array($company_id,'a'))->affectedRows();
        if($company_count > 0 && $this->current_user_id > 0){
            $job_class='';
        }
        $follow_company_url="";
        if($this->current_user_id > 0){
            $follow_company_url = $this->followCompanyUrl($this->company_id);
        }
        $getCompanyFollowers = $this->getCompanyFollowers();
        $des_hide=$hide_web=$location_hide=$year_hide="";
        if($this->website_of_company == ''){
            $hide_web='hidden';
        }
        if($this->foundation_year == '' || $this->foundation_year == 0){
            $year_hide='hidden';
        }
        if($this->getCompanyHeadQuarter($company_id) == ''){
            $location_hide='hidden';
        }if($this->company_description==''){
            $des_hide="hidden";
        }
        $hide_rate_review_for_owner = '';
        $hide_rate_review_for_owner = ($this->user_id == $_SESSION['user_id']) ? 'hide' : '';
        $hide_without_login = '';
        $hide_without_login = (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '') ? '' : 'hide';

        $already_given = getTableValue("tbl_company_rate_review", "id", array("sender_id" => $_SESSION['user_id'],'company_id' => $company_id));
        if ($already_given > 0) {
            $edit_user = 'hide';
        }else{
            $edit_user = '';
        }
        //print_r($already_given);exit();
        $fields_replace = array(
            $hideBanner,
            $post_an_update_url, 
            $this->getFeeds($company_id), 
            encryptIt($company_id), 
            $this->getCompanyHeadQuarter($company_id), 
            $this->foundation_year,
            $this->owner_email_address,
            $this->company_description,
            $this->location,
            $this->lat,
            $this->lng,
            $share_update_panel,
            ucwords($this->company_name),
            $this->company_url,
            $company_logo_url,
            $company_edit_url,
            $this->industry_name,
            $this->website_of_company,
            $this->range_of_no_of_employees,
            $follow_company_url,
            $getCompanyFollowers,
            $location_hide,
            $hide_web,
            $year_hide,
            $des_hide,
            $job_class,
            $job_url,
            $target_link,
            $banner_image,
            $this->company_id,
            $hide_rate_review_for_owner,
            $hide_without_login,
            $edit_user
        );
        $main_content_parsed =  $main_content->parse();
        $content = str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function getCompanyFollowers() {
        $query = "SELECT count(id) as company_followers FROM tbl_company_followers WHERE company_id = ? ";
        $company_details = $this->db->pdoQuery($query,array($this->company_id))->result();    
        return $company_details['company_followers'];    
    }
    public function getSocialSharingIcons() {
        //Social sharing
        $share_on_social_media = new Templater(DIR_TMPL . $this->module . "/share-on-social-media-nct.tpl.php");
        $share_on_social_media_parsed =  $share_on_social_media->parse();
        $share_button = '';
        if($this->current_user_id > 0){
            $share_button = $this->getShareButton(encryptIt($this->company_id));    
        }
        $fields = array("%COMPANY_NAME%", "%ENC_COMPANY_ID%","%SHARE%");
        $fields_replace = array($this->company_name, encryptIt($this->company_id),$share_button);
        $social_media_content = str_replace($fields, $fields_replace, $share_on_social_media_parsed);
        return $social_media_content;
    }
    public function getShareButton($company_id){
        $share_button = new Templater(DIR_TMPL . $this->module . "/share-nct.tpl.php");
        $share_button_parsed =  $share_button->parse();
        $fields = array("%ENC_COMPANY_ID%");
        $fields_replace = array($company_id);
        $social_button_content = str_replace($fields, $fields_replace, $share_button_parsed);
        return $social_button_content;   
    }
    public function getCompanyHeadQuarter($company_id) {
        $query = "SELECT l.formatted_address
            FROM tbl_company_locations cl
            LEFT JOIN tbl_locations l ON l.id = cl.location_id
            WHERE cl.company_id = ? 
            AND cl.is_hq = ? ";
        $company_details = $this->db->pdoQuery($query,array($company_id,'y'))->result();    
        return $company_details['formatted_address']; 
    }
    public function followCompanyUrl($company_id) {
        $follow_company_url = '';
        $follow_company = new Templater(DIR_TMPL . $this->module . "/follow-company-url-nct.tpl.php");
        $follow_company_parsed =  $follow_company->parse();
        $user_company_count = getTotalRows('tbl_companies', "id = '". $company_id ."' AND user_id = '". $this->current_user_id ."'");
        if($user_company_count == 0) {
            if(getTotalRows('tbl_company_followers', "company_id = '". $company_id ."' AND user_id = '". $this->current_user_id ."'") == 0) {
                $fields_follow_company = array(
                    '%FOLLOW_COMPANY%',
                    '%ENCRYPTED_COMPANY_ID%',
                    "%HTML%",
                    "%HREF%",
                    "%TARGET%",
                );
                $fields_replace_follow_company = array(
                    'follow_company',
                    encryptIt($company_id),
                   LBL_FOLLOW,
                    'javascript:void(0);',
                    '',
                );
            } else {
                $fields_follow_company = array(
                    '%FOLLOW_COMPANY%',
                    '%ENCRYPTED_COMPANY_ID%',
                    "%HTML%",
                    "%HREF%",
                    "%TARGET%",
                );
                $fields_replace_follow_company = array(
                    'unfollow_company',
                    encryptIt($company_id),
                    LBL_UNFOLLOW,
                    'javascript:void(0);',
                    '',
                );
            }
            $follow_company_url = str_replace($fields_follow_company, $fields_replace_follow_company, $follow_company_parsed);
        }
        return $follow_company_url;
    }
    public function getFollowers($currentPage = 1, $company_id) {
        $response = array();
        $response['status'] = false;
        $followers_html = "";
        $limit = 10;
        require_once('storage.php');
        $followers_storage = new storage();
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $total_followers = $this->db->count('tbl_company_followers',array('company_id'=>$company_id));
        $sql = 'SELECT user_id FROM tbl_company_followers WHERE company_id = ? ';
        $sql_with_limit = $sql . " LIMIT " . $limit . " OFFSET " . $offset;
        $followers = $this->db->pdoQuery($sql_with_limit,array($company_id))->results();
        if ($followers) {
            $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_followers = $this->db->pdoQuery($sql_with_next_limit,array($company_id))->results();
            $next_available_records = count($next_followers);
            $follower_tpl = new Templater(DIR_TMPL . "liker-nct.tpl.php");
            $follower_tpl_parsed = $follower_tpl->parse();
            $fields = array(
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                // "%HEADLINE%",
                "%ENCRYPTED_USER_ID%",
                "%ENCRYPTED_COMPANY_ID%"
            );
            for ($i = 0; $i < count($followers); $i++) {
                $user_status=get_user_status($followers[$i]['user_id']);
                $user_profile_url="javascript:void(0)";
                if($user_status=='a'){
                    $user_profile_url = get_user_profile_url($followers[$i]['user_id']);
 
                }

                $first_name = filtering(getTableValue("tbl_users", "first_name", array("id" => $followers[$i]['user_id'])));
                $last_name = filtering(getTableValue("tbl_users", "last_name", array("id" => $followers[$i]['user_id'])));
                $user_name_full = $first_name . " " . $last_name;
                // $image = getImageURL("user_profile_picture", $followers[$i]['user_id'], "th3",$this->platform);
                $user_profile_pic_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$followers[$i]['user_id']));
                $image = $followers_storage->getImageUrl1('av8db','th3_'.$user_profile_pic_name,'users-nct/'.$followers[$i]['user_id'].'/');
                $is_image = getimagesize($image);
                if(!empty($is_image)){
                    $image = '<img src="'.$image.'" alt="'.$first_name.' '.$last_name.'">';
                }else{
                    $image = '<span class="profile-picture-character">'.ucfirst($first_name).'</span>';
                }
                $user_headline = '';
                //= getUserHeadline($followers[$i]['user_id']);
                $fields_replace = array(
                    $image,
                    $user_profile_url,
                    ucwords($user_name_full),
                    //$user_headline,
                    encryptIt($followers[$i]['user_id']),
                    encryptIt($company_id)
                );
                if($this->platform == 'app'){
                    $follower_id = $followers[$i]['user_id'];
                    $follower_photo = $image;
                    $follower_name = $user_name_full;
                    $follower_headline = $user_headline;
                    $app_followers[] = array('follower_id'=>$follower_id,'follower_photo'=>$follower_photo,'follower_name'=>$follower_name,'follower_headline'=>$follower_headline,'user_status'=>$user_status);
                } else {
                    $followers_html .= str_replace($fields, $fields_replace, $follower_tpl_parsed);
                }
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-follower/company/" . encryptIt($company_id) . "/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $followers_html .= $load_more_li_tpl->parse();
            }
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = LBL_NO_FOLLOWERS_FOUND;
            $no_result_found_tpl->set('message', $message);
            $final_result_html = $no_result_found_tpl->parse();
            $followers_html .= $final_result_html;
        }

        if($this->platform == 'app'){
            $page_data = getPagerData($total_followers, $limit,$currentPage);
            $app_response['followers'] = (empty($app_followers)?array():$app_followers);
            $app_response['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total_followers);
            return $app_response;
        } else {
            $response['status'] = true;
            $response['followers'] = $followers_html;
        }
        return $response;
    }
    public function getCompanyJobs($currentPage = 1, $company_id) {
        $response = array();
        $response['status'] = false;
        $jobs_html = "";
        $limit = 5;
        require_once('storage.php');
        $job_storage = new storage();
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $total_jobs = $this->db->count('tbl_jobs', array('company_id' => $company_id ));
        $sql = 'SELECT j.id,j.job_title,j.employment_type,j.added_on,j.last_date_of_application,j.company_id, l.country, l.state, l.city1, l.city2, c.company_name,jcate.job_category_'.$this->lId.' as job_category,j.is_featured
                FROM tbl_jobs j
                LEFT JOIN tbl_companies c ON c.id = j.company_id
                LEFT JOIN tbl_locations l ON l.id = j.location_id
                LEFT JOIN tbl_job_category jcate ON j.job_category_id = jcate.id
                WHERE j.company_id = ?  ';

        $where_array=array($company_id);        
        $sql_with_limit = $sql . " LIMIT " . $limit . " OFFSET " . $offset;
        $jobs = $this->db->pdoQuery($sql_with_limit,$where_array)->results();
        if ($jobs) {
            $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_jobs = $this->db->pdoQuery($sql_with_next_limit,$where_array)->results();
            $next_available_records = count($next_jobs);
            $jobs_tpl = new Templater(DIR_TMPL . $this->module . "/single-jobs-li-nct.tpl.php");
            $jobs_tpl_parsed = $jobs_tpl->parse();
            $fields = array(
                "%JOB_ID%",
                "%JOB_TITLE%",
                "%LOCATION%",
                "%EMPLOYMENT_TYPE%",
                "%SKILLS%",
                "%POSTED_DATE%",
                "%LAST_DATE_OF_JOB_APPLICANTS%",
                "%JOB_URL%",
                "%COMPANY_NAME%",
                "%COMPANY_URL%",
                "%COMPANY_IMAGE_URL%",
                "%HIDE_SKILL%"
            );
            for ($i = 0; $i < count($jobs); $i++) {
                $city = $jobs[$i]['city1'] != '' ? $jobs[$i]['city1'] : $jobs[$i]['city2'];
                $state = $jobs[$i]['state'];
                $country = $jobs[$i]['country'];
                //$location = $city . ", " . $state . ", " . $country;
                $location = $city;
                $location .= (($location != '' && $state != '')?', ':'').$state;
                $location .= (($location != '' && $country != '')?', ':'').$country;
                $is_featured = filtering($jobs[$i]['is_featured'], 'output', 'output');
                $emp_type=filtering($jobs[$i]['employment_type'], 'output') == 'f' ? LBL_EMPLOYMENTTYPE_FULL_TIME : 
                (filtering($jobs[$i]['employment_type'], 'output') == 'p' ? LBL_EMPLOYMENTTYPE_PART_TIME : 
                (filtering($jobs[$i]['employment_type'], 'output') == 'c' ? LBL_EMPLOYMENTTYPE_CONTRACT : LBL_EMPLOYMENTTYPE_TEMPORARY));
                $job_url = get_job_detail_url(filtering($jobs[$i]['id'], 'output', 'int'));

                // $qrySelSkills = $this->db->pdoQuery("SELECT skills.skill_name_".$this->lId." as skill_name FROM tbl_job_skills jskills 
                // LEFT JOIN tbl_skills skills ON skills.id = jskills.skill_id WHERE jskills.job_id = ? ",array(filtering($jobs[$i]['id'], 'output', 'int')))->results();

                // $skill_name="";
                // $skills=array();
                // if($qrySelSkills) {
                //     foreach ($qrySelSkills as $key => $value) {
                //         $skills[] = $value['skill_name'];
                //     }
                //     $skill_name = implode(", ", $skills);
                // } else {
                //     $skill_name = '';
                // }
                $hide_skill='';
                if($skill_name == ''){
                    $hide_skill='hidden';
                }
                $job_title = filtering($jobs[$i]['job_title'], 'output');
                $job_category = '';
                $job_category = filtering($jobs[$i]['job_category'], 'output');
                $job_added_on = convertDate('onlyDate', $jobs[$i]['added_on']);
                $job_last_date_of_application = convertDate('onlyDate', $jobs[$i]['last_date_of_application']);

                // $company_logo = getImageUrl("company_logo", filtering($jobs[$i]['company_id'], 'output', 'int'), "th2",$this->platform);
                // if($this->platform == 'web'){
                //     $company_logo = ($company_logo == '') ? '<span class="company-letter-square company-letter">'.ucfirst($this->company_name[0]).'</span>' : $company_logo;
                // }

                $company_logo_img_name = getTableValue('tbl_companies','company_logo',array('id'=>$jobs[$i]['company_id']));
                $company_logo = $job_storage->getImageUrl1('av8db','th2_'.$company_logo_img_name,'company-logos-nct/');
                $is_image = getimagesize($company_logo);
                if(!empty($is_image)){
                    $company_logo = '<img src="'.$company_logo.'" alt="'.$jobs[$i]['company_name'].'">';
                }else{
                    $company_logo = '<span class="profile-picture-character">'.$jobs[$i]['company_name'].'</span>';
                }

                $fields_replace = array(
                    filtering($jobs[$i]['id'], 'output', 'int'),
                    ucwords($job_title),
                    $location,
                    $emp_type,
                    $skill_name,
                    $job_added_on,
                    $job_last_date_of_application,
                    $job_url,
                    filtering($jobs[$i]['company_name'], 'output'),
                    get_company_detail_url(filtering($jobs[$i]['company_id'], 'output', 'int')),
                    $company_logo,
                    $hide_skill
                );
                if($this->platform == 'app'){
                    
                    $job_id = $jobs[$i]['id'];
                    $job_location = $location;
                    $job_category = $job_category;
                    $job_skills = $skill_name;
                    $job_employment_type = $emp_type;
                    $job_posted_date = $job_added_on;
                    $job_last_date = $job_last_date_of_application;

                    $job_category_id = '';
                    $job_category_title = '';

                    $app_jobs[] = array(
                        'job_id'=>$job_id,
                        'job_title'=>$job_title,
                        'job_category'=>$job_category,
                        'job_skills'=>$job_skills,
                        'job_location'=>$job_location,
                        'job_employment_type'=>$job_employment_type,
                        'job_posted_date'=>$job_posted_date,
                        'job_last_date'=>$job_last_date,
                        'is_featured'=>$is_featured)
                    ;
                } else {
                    $jobs_html .= str_replace($fields, $fields_replace, $jobs_tpl_parsed);
                }
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-jobs/company/" . encryptIt($company_id) . "/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $jobs_html .= $load_more_li_tpl->parse();
            }
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $message = LBL_HAVENT_CREATED_JOB;
            $no_result_found_tpl->set('message', $message);
            $final_result_html = $no_result_found_tpl->parse();
            $jobs_html = $final_result_html;
        }
        if($this->platform == 'app'){
            $page_data = getPagerData($total_jobs, $limit,$currentPage);
            $app_response['jobs'] = (empty($app_jobs)?array():$app_jobs);
            $app_response['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total_jobs);
            return $app_response;
        } else {
            $response['status'] = true;
            $response['jobs'] = $jobs_html;
        }
        return $response;
    }
    public function shareNewsFeed($company_id) {
        $response = array();
        $response['status'] = false;
        $user_id = $this->current_user_id;
        $val_array = array(
            'user_id'           => $user_id,
            'shared_with'       => 'p',
            'type'              => 'c',
            'shared_company_id' => $company_id,
            'posted_or_shared'  => 's',
            'status'            => 'p',
            'added_on'          => date('Y-m-d H:i:s'),
            'updated_on'        => date('Y-m-d H:i:s'),
        );
        $feed_id = $this->db->insert('tbl_feeds', $val_array)->getLastInsertId();
        if($feed_id) {
            $response['status'] = true;
            $response['msg'] = LBL_COMPANY_SHARED_SUCCESSFULLY;
        } else {
            $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }
        return $response;
    }
    public function getFeeds($company_id,$page=1) {
        $final_result = NULL;
        $limitWeb = 10;
        $query_without_limit = '';

        $limit = $this->app_feed_per_page;
        $offset = ($page - 1 ) * $limit;

        $query = $query1 = "SELECT id FROM tbl_feeds WHERE company_id =  " . $company_id . "  AND type = 'c' AND status = 'p' ORDER BY id DESC ";
        if($this->platform == 'app'){
            $query .= "limit $offset,$limit";
        }else{
            $offsetWeb = ($page - 1) * $limitWeb;
            $query_without_limit .= "limit $offsetWeb,$limitWeb";
            $query .= "limit $offsetWeb,$limitWeb";

            $query_with_next_limit = $query1 . " LIMIT " . $limitWeb . " OFFSET " . ( $offsetWeb + $limitWeb );
            $next_records = $this->db->pdoQuery($query_with_next_limit)->results();
            $next_available_records = count($next_records);
        }
        $feeds = $this->db->pdoQuery($query)->results();
        if ($feeds) {
            require_once('storage.php');
            $company_storage = new storage();
            $feeds_container_tpl = new Templater(DIR_TMPL .  "feeds-container-nct.tpl.php");
            $feeds_li = "";
            $app_array = array();
            for ($i = 0; $i < count($feeds); $i++) {
                if($this->platform == 'app'){
                    $app_array[] = getSingleFeed($company_storage,$feeds[$i]['id'],$this->platform,$this->current_user_id,'',0);
                } else {
                    $feeds_li .= getSingleFeed($company_storage,$feeds[$i]['id'],$this->platform,'',0);
                }
            }
            $feeds_container_tpl->set('feeds_li', $feeds_li);
            $feeds_container_tpl_parsed = $feeds_container_tpl->parse();
            $fields = array("%LIKE_UNLIKE_URL%","%POST_COMMENT_URL%","%POST_AN_UPDATE_URL%");
            $fields_replace = array(SITE_URL."like-unlike",SITE_URL . "post-comment",SITE_URL . "share-an-update");
            $final_result = str_replace($fields, $fields_replace, $feeds_container_tpl_parsed);

            if($this->platform == 'app'){
                return $final_result = $app_array;
            } else {
                if ($next_available_records > 0) {
                    $load_more_li_tpl = new Templater(DIR_TMPL. $this->module  . "/load-more-nct.tpl.php");
                    $load_more_link = SITE_URL . "load-more-company-feeds/page/" . ($page + 1).'/'.$company_id;
                    $load_more_li_tpl->set('load_more_link', $load_more_link);
                    $final_result .= $load_more_li_tpl->parse();
                }

                return $final_result;
            }
        }
        $no_feeds_tpl = new Templater(DIR_TMPL . $this->module . "/no-feeds-nct.tpl.php");
        $final_result = $no_feeds_tpl->parse();
        return $final_result;
    }
    public function remove_company_follower() {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->delete('tbl_company_followers', array('company_id' => decryptIt(filtering($_POST['company_id'], 'output', 'int')),
                    'user_id' => decryptIt(filtering($_POST['user_id'], 'output', 'int'))))->affectedRows();
        if ($affectedRows) {
            $response['status'] = true;
            $response['success'] = LBL_COMPANY_FOLLOWER_REMOVED_SUCCESSFULLY;
        } else {
            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }
        return $response;
    }
    public function getCompanyDD() {
        $final_result = NULL;

        $companies = $this->db->select("tbl_companies", array('id,company_name'), array("status" => "a", 'company_type' => 'r', 'user_id'=>$this->session_user_id))->results();
        if ($companies) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($companies); $i++) {
                $selected = ($this->company_id == $companies[$i]['id'] ? "selected='selected'" : '');
                $fields_replace = array(
                    encryptIt(filtering($companies[$i]['id'], 'input', 'int')),
                    $selected,
                    stripcslashes(filtering($companies[$i]['company_name']))
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;
    }
    public function getCategoriesDD() {
        $final_result = NULL;

        $job_category = $this->db->select("tbl_job_category", array('id,job_category_'.$this->lId.' as job_category'), array("status" => "a"))->results();
        if ($job_category) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($job_category); $i++) {
                $job_category_title = filtering($job_category[$i]['job_category'], 'input', 'int');
                $fields_replace = array(
                    filtering($job_category[$i]['id'], 'input', 'int'),
                    '',
                    $job_category_title
                );
                if($this->platform=='app'){
                    $final_result[] = array('id'=>$job_category[$i]['id'],'category'=>$job_category_title);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
                }
            }
        }

        return $final_result;
    }
    public function getCompanyLocation() {
        $final_result = NULL;

        $query = "SELECT l.formatted_address,l.id
            FROM tbl_company_locations cl
            LEFT JOIN tbl_locations l ON l.id = cl.location_id
            WHERE cl.company_id = ? ";
        $companies = $this->db->pdoQuery($query,array($this->company_id))->results();
        if ($companies) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($companies); $i++) {
                
                $fields_replace = array(
                    filtering($companies[$i]['id'], 'input', 'int'),
                    '',
                    filtering($companies[$i]['formatted_address'], 'input', 'int')
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;
    }
    public function storeRateReview($sender_id,$rating,$desc,$company_id){
        $response = array();
        $response['status'] = false;

        $send_arr = array();
        
        $send_arr['sender_id'] = $sender_id;
        $send_arr['company_id'] = $company_id;
        $send_arr['review_description'] = $desc;
        $send_arr['rating'] = $rating;
        $send_arr['createdAt'] = date("Y-m-d H:i:s");

        $lastInsertId = $this->db->insert("tbl_company_rate_review", $send_arr)->getLastInsertId();        

        if($lastInsertId > 0){
            $response['status'] = true;
            $response['redirect_url'] = SITE_URL ."company/".$company_id;
            $response['success'] = SUCCESS_COMPANY_RATE_REVIEW_MESSAGE;
            return $response;
        }else{
            $response['status'] = false;
            $response['redirect_url'] = SITE_URL ."company/".$company_id;
            $response['err'] = ERROR_COMPANY_RATE_REVIEW_MESSAGE;
            return $response;
        }
    }
    public function getReviewList($company_id){
        $final_result= $hide_edit_link = "";
        $user_img=DEFAULT_USET_IMAGE;

        $main_content = new MainTemplater(DIR_TMPL . $this->module . "/company_review-nct.tpl.php");
        
        $main_content = $main_content->parse();

        require_once('storage.php');
        $company_reviews_storage = new storage();

        $fields=array("%RID%","%USER_NAME%","%USER_IMG%","%REVIEW_DESC%","%USERURL%","%REVIEW_POSTED_DATE%","%FINAL_RESULT1%","%COMPANY_ID%","%HIDE_EDIT_LINK%","%ISREPORTED%");

        $rate_data = $this->db->pdoQuery("SELECT cr.*,u.id as userId,u.first_name,u.last_name,u.cover_photo,u.profile_picture_name FROM tbl_companies as c LEFT JOIN tbl_company_rate_review as cr ON c.id = cr.company_id LEFT JOIN tbl_users as u ON u.id = cr.sender_id WHERE cr.company_id = '".$company_id."' ORDER by cr.id DESC");
        $qryRes = $rate_data->results();
       //echo "<pre>";print_r($qryRes);exit();
        $totalRes = $rate_data->affectedRows();
        if($totalRes > 0){
            foreach($qryRes as $fetchRes){
                $firstName = isset($fetchRes['first_name']) ? $fetchRes['first_name']:'-';
                $lastName  = isset($fetchRes['last_name']) ? $fetchRes['last_name']:'-';
                $user_name = $firstName.' '.$lastName;
                // $user_img = DEFAULT_USET_IMAGE;
                // if($fetchRes['cover_photo']!="" && file_exists(DIR_UPD."user_cover-nct/".$fetchRes['userId']."/th1_".$fetchRes['cover_photo'])){
                //     $user_img = SITE_UPD."user_cover-nct/".$fetchRes['userId']."/th1_".$fetchRes['cover_photo'];
                // }

                $user_img = $company_reviews_storage->getImageUrl1('av8db','th1_'.$fetchRes['profile_picture_name'],'users-nct/'.$fetchRes['userId'].'/');
                $is_image = getimagesize($user_img);
                if(!empty($is_image)){
                    $user_img = $user_img;
                }else{
                    $user_img = $company_reviews_storage->getImageUrl('av8db','default-user.png');
                }

                if($fetchRes['userId']!="")
                {
                    $userurl = get_user_profile_url($fetchRes['userId']);
                }
                $isReported = ($fetchRes['isReported'] == 'y') ? 'hide' : '';
                $review_desc= isset($fetchRes['review_description']) ? $fetchRes['review_description'] : '-';
                $review_posted_date = isset($fetchRes['createdAt']) ? date ("d M, Y", strtotime($fetchRes['createdAt'])) : '-';
                $star_rate = isset($fetchRes['rating']) ? $fetchRes['rating'] : 0;
                $hide_edit_link = ($_SESSION['user_id'] == $fetchRes['userId']) ? '' : 'hide';
                $final_result1 = $this->getRating($star_rate);
                $replace=array($fetchRes['id'],$user_name,$user_img,$review_desc,$userurl,$review_posted_date,$final_result1,$fetchRes['company_id'],$hide_edit_link,$isReported);
               
                $final_result.=str_replace($fields,$replace,$main_content);
            }
        }else{
            $no_feeds_tpl = new Templater(DIR_TMPL . $this->module . "/no-reviews-nct.tpl.php");
            $final_result = $no_feeds_tpl->parse();
            //$final_result.='<div class="tbody"><div class="td"></div><div class="td"></div><div class="td">'.MESSAGE_COMPANY_DETAIL_NO_REVIEWS_ADDED.'</div><div class="td"></div><div class="td"></div></div>';
        }
        return $final_result;
    }
    public function getRating($totRating=0){
        $html='';
        if($totRating>0){
            for($i=0;$i<5;$i++){
                if($totRating>$i){
                    $html.='<li><a href="javascript:void(0);"><i class="fa fa-star"></i></a></li>';    
                }else{
                    $html.='<li><i class="fa fa-star"></i></li>';    
                }
            }
        }else{
            $html='<li><a class="deactivate-pointer" href="javascript:void(0);"></a><i class="fa fa-star"></i></li><li><i class="fa fa-star"></i></li><li><i class="fa fa-star"></i></li><li><i class="fa fa-star"></i></li><li><i class="fa fa-star"></i></li>';
        }
        return $html;
    }
    public function getLicensesEndorsements() {
        $final_result = NULL;

        $licenses_endorsements = $this->db->select("tbl_license_endorsements", array('id','licenses_endorsements_name_'.$this->lId), array("isActive" => "y"))->results();
        if ($licenses_endorsements != '') {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($licenses_endorsements); $i++) {
                
                $fields_replace = array(
                    filtering($licenses_endorsements[$i]['id'], 'input', 'int'),
                    '',
                    filtering($licenses_endorsements[$i]['licenses_endorsements_name_'.$this->lId], 'input', 'int')
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;
    }
    public function getEditReviewModal($review_id=0,$sender_id=0){
      $final_result='';

      $main_content = new MainTemplater(DIR_TMPL . $this->module . "/edit-review-form-nct.tpl.php");
      $main_content = $main_content->parse();
      $fields=array("%ID%","%REVIEW%","%RATING%","%BOOKING_ID%","%COMPANY_ID%");

      $qryRes=$this->db->pdoQuery("SELECT * FROM tbl_company_rate_review WHERE id = '".$sender_id."' ")->result();
      $affectedRows=$this->db->pdoQuery("SELECT * FROM tbl_company_rate_review WHERE id = '".$sender_id."'")->affectedRows(); 
      //echo $qryRes;exit;
      if($affectedRows>0){
        $fetchRes=$qryRes;
        //print_r($fetchRes['review_description']);exit();
        $replace=array($fetchRes['id'],$fetchRes['review_description'],$fetchRes['rating'],$sender_id,$this->company_id);
      }else{
        $replace=array('','',0,$sender_id);
      }

      $final_result=str_replace($fields, $replace, $main_content);

      return $final_result;
    }
    public function UpdateRateReview($sender_id,$rating,$desc,$company_id){
        $response = array();
        $response['status'] = false;
        
        $review_data=array();

        $reviewId=getTableValue("tbl_company_rate_review","id",array("company_id"=>$company_id,"sender_id" => $_SESSION['user_id']));
        $review_data['sender_id']=$_SESSION['user_id'];
        $review_data['company_id']=$company_id;
        $review_data['review_description']=$desc;
        $review_data['rating']=$rating;
        $review_data['updatedAt']=date('Y-m-d H:i:s');
       
        if($reviewId>0){
            $this->db->update('tbl_company_rate_review',$review_data,array('id'=>$reviewId));
            $response['status'] = "suc";
            $response['redirect_url'] = SITE_URL ."company/".$company_id;
            $response['message'] = SUCCESS_COMPANY_UPDATE_RATE_REVIEW_MESSAGE;
            
        }else{
            $review_data['updatedAt']=date('Y-m-d H:i:s');
            $review_id=$this->db->insert('tbl_company_rate_review',$review_data)->getLastInsertId();
            if($review_id > 0){
                $response['status'] = "suc";
                $response['redirect_url'] = SITE_URL ."company/".$company_id;
                $response['message'] = SUCCESS_COMPANY_RATE_REVIEW_MESSAGE;
            }
        }
        return $response;
    }
    public function addReviewAsReported($review_id,$company_id){
        $response = array();
        $response['status'] = false;
        
        $review_data=array();

        $reviewId=getTableValue("tbl_company_rate_review","id",array("company_id"=>$company_id,"id" => $review_id,'isReported' => 'n'));

        $review_data['isReported']='y';
        $review_data['updatedAt']=date('Y-m-d H:i:s');
       
        if($reviewId>0){
            $this->db->update('tbl_company_rate_review',$review_data,array('id'=>$review_id));
            $response['status'] = "suc";
            $response['redirect_url'] = SITE_URL ."company/".$company_id;
            $response['message'] = SUCCESS_COMPANY_REVIEW_REPORTED;
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."company/".$company_id;
            $response['message'] = ERROR_COMPANY_REPORTED_REVIEW_NOT_EXISTS;
        }
        return $response;
    }
    public function getImageUrl($bucketName, $objectName){
      return 'https://storage.cloud.google.com/'.$bucketName.'/'.$objectName;
    }
} ?>