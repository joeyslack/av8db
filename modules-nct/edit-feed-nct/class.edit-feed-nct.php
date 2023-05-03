<?php

class editFeed extends Home {

    function __construct($feed_id = '') {
        $this->feed_id = $feed_id;
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
    }

    public function geteditFeedPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $feeds_container_tpl_parsed = $main_content->parse();

        $fields = array(
            "%USER_PROFILE_URL%",
            "%USER_PROFILE_PICTURE%",
            "%USER_NAME_FULL%",
            "%HEADLINE%",
            "%TIME_AGO%",
            "%DESCRIPTION%",
            "%POST_VIDEO%",
            "%POST_AN_UPDATE_URL%",
            '%IMG%',
            '%CLASS%',
            '%FEED_ID%',
            '%VIDEO_CLASS%',
            "%HIDE_CLASS%",
            "%MEMBERSHIP_PLAN%",
            "%GROUP_TITLE%",
            "%POST_TITLE%"

        );
        
        $feed_details=$this->db->select('tbl_feeds','*',array('id'=>$this->feed_id))->result();
        $first_name=filtering(getTableValue("tbl_users","first_name",array("id" => $feed_details['user_id'])));
        $last_name=filtering(getTableValue("tbl_users", "last_name", array("id" => $feed_details['user_id'])));
        $profile_url = get_user_profile_url($feed_details['user_id']);
        $postedByName = $first_name . " " . $last_name;
        $postedByHeadLine = '';
        //$postedByHeadLine = getUserHeadline($feed_details['user_id']);
        $timestamp = time_elapsed_string(strtotime($feed_details['added_on']));
        // $profile_picture = getImageURL("user_profile_picture", $feed_details['user_id'], "th3",'web');
        $profile_picture_name = getTableValue('tbl_users','profile_picture_name',array('id'=>$feed_details['user_id']));
        $profile_picture = 'https://storage.googleapis.com/av8db/users-nct/'.$feed_details['user_id'].'/'.$profile_picture_name;
        $is_image = getimagesize($profile_picture);
        if(!empty($is_image)){
            $profile_picture = '<img src="'.$profile_picture.'" alt="'.$postedByName.'">';
        }else{
            $profile_picture = '<span class="profile-picture-character">'.ucfirst($postedByName[0]).'</span>';
        }
        $feedDescription = filtering($feed_details['description'], "output", "text");
        $class="hidden";
        if ($feed_details['image_name'] != "") {
            $image_name = filtering($feed_details['image_name']);
            // $image_url = SITE_UPD_FEEDS . $image_name;
            $image_url = 'https://storage.googleapis.com/av8db/feed-images-nct/' . $image_name;
            $class='';
        }
        $post_video="";
        $video_class="hidden";
        if ($feed_details['video_code'] != ""){
                    $post_video=isset($feed_details['video_code'])?$feed_details['video_code']:'';
                    $video_class="";
                    $post_video=video_string($post_video);

        }
        $hide_class=$group_title="hidden";
        if($feed_details['type']=='u' || $feed_details['type']=='c'){
            $hide_class="";
        }
        if($feed_details['type']=='g'){
            $group_title='';
            

        }
        if($feed_details['company_id'] != ''){
            $profile_url = get_company_detail_url($feed_details['company_id']);
            // $company_logo_url = getImageURL("company_logo", $feed_details['company_id'], "th2",'web');
            $company_name=getTableValue("tbl_companies","company_name",array("id" => $feed_details['company_id']));
            $company_logo_name = getTableValue('tbl_companies','company_logo',array('id'=>$feed_details['company_id']));
            $company_logo_url = 'https://storage.googleapis.com/av8db/company-logos-nct/'.$company_logo_name;
            $is_image = getimagesize($company_logo_url);
            if(!empty($is_image)){
                $company_logo_url = '<img src="'.$company_logo_url.'" alt="'.$company_name.'">';
            }else{
                $company_logo_url = '';
            }
            
            if($company_logo_url != ''){
                
                $profile_picture=$company_logo_url;
            }else{
                $profile_picture = '<span class="profile-picture-character">' . ucfirst($company_name[0]) . '</span>';
            }
            $postedByHeadLine='';
            $postedByName=$company_name;

        }
        $fields_replace = array(
           $profile_url,
           $profile_picture,
           ucwords($postedByName),
           ucwords($postedByHeadLine),
           $timestamp,
           $feedDescription,
           $post_video,
           SITE_URL . "editfeed",
           $image_url,
           $class,
           $this->feed_id,
           $video_class,
           $hide_class,
           $this->getSubscribedMembershipPlan($this->session_user_id),
           $group_title,
            ucwords(filtering($feed_details['post_title'], "output", "text"))

        );

        $final_result = str_replace($fields, $fields_replace, $feeds_container_tpl_parsed);

        
        
        return $final_result;
    }
    public function processPostUpdate($posted_or_shared = "p", $type = "u", $status = "p", $shared_feed_id = "") {
        /*_print_r($_POST);
        _print_r($_FILES);
        exit;*/
        $response = $feed_array = array();
        $response['status'] = false;
        
        $feed_details=$this->db->select('tbl_feeds','*',array('id'=>$_POST['feedid']))->result();
        $type=$feed_details['type'];
        $video_code=$feed_details['video_code'];
        $image_name=$feed_details['image_name'];

        require_once(DIR_MOD.'common_storage.php');
        $edit_feed_storage = new storage();


        if (isset($_POST['post_description'])) {
            $_POST['description'] = $_POST['post_description'];
        }
        $description = filtering($_POST['description'], 'input', 'text');
        $description=preg_replace('#<script(.*?)>(.*?)</script>#is', '', $description);

        if (isset($_FILES['feed_image']) && !($_FILES['feed_image']['error'])) {
            $file_array = $_FILES["feed_image"];
            //list($width, $height, $type, $attr) = getimagesize($_FILES["Artwork"]['tmp_name']);
           // echo "<pre>";print_r();die;

            $result = $edit_feed_storage->upload_object1('av8db',$_FILES['feed_image']['name'],$_FILES['feed_image']['tmp_name'],'feed-images-nct/');

            // $upload_dir = DIR_UPD_FEEDS;
            // $image_resize_array = unserialize(FEED_IMAGE_RESIZE_ARRAY);

            // $imageUploadResponse = uploadImage($file_array, $upload_dir, $image_resize_array);
            // compress(DIR_UPD_FEEDS.$imageUploadResponse['image_name'],DIR_UPD_FEEDS.$imageUploadResponse['image_name'],40);
            if (!$result) {
                $response['error'] = ERROR_POST_SOME_CONTENT_IMAGE;
                return $response;
            } else {
               /* $resieImage = resizeImage(DIR_UPD_FEEDS.$imageUploadResponse['image_name'],DIR_UPD_FEEDS.'urmi.jpg',600,$height,false);*/
                $feed_array['image_name'] = $_FILES['feed_image']['name'];
                //$feed_array['image_name'] = $imageUploadResponse['image_name'];
            }
        }else if(isset($_POST['videocode']) && $_POST['videocode']){
            $videocode=$_POST['videocode'];
        }else {
            if($video_code != '' || $image_name !=''){

            }else{
                if ($description == "" && $status == "p"){
                    if($type != 'a' && $type != 'g'){
                        $response['error'] = ERROR_POST_SOME_CONTENT_IMAGE;
                        return $response;
                    }

                    if($type=='a' || $type == 'g'){
                        $response['error'] = ERROR_POST_SOME_CONTENT_IMAGE_PUBLISH;
                        return $response;

                    }
                }
            }
            
        }

        if(isset($_POST['is_image_removed']) && $_POST['is_image_removed'] == 'yes'){
            $feed_array['image_name'] = '';
        }

        $feed_array['user_id'] = $this->session_user_id;
        $feed_array['description'] = $description;

        $feed_array['post_title'] = isset($_POST['post_title']) ? filtering($_POST['post_title'], 'input') : "";

        $feed_array['company_id'] = isset($_POST['company_id']) ? decryptIt(filtering($_POST['company_id'], 'input', 'int')) : $feed_details['company_id'];
        $feed_array['group_id'] = isset($_POST['group_id']) ? decryptIt(filtering($_POST['group_id'], 'input', 'int')) :$feed_details['group_id'];

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

        $feed_array['type'] = isset($feed_details['type'])?$feed_details['type']:$type;
        $feed_array['status'] = $status;
        $feed_array['added_on'] = date("Y-m-d H:i:s");
        $feed_array['updated_on'] = date("Y-m-d H:i:s");
        $feed_array['video_code']=(($_POST['videocode']!='' ? $_POST['videocode'] :($feed_details['video_code']!=''? $feed_details['video_code']:'')) );
        
       
        if (isset($_POST['feedid']) && $_POST['feedid'] > 0) {
            $affectedRows = $this->db->update("tbl_feeds", $feed_array, array('id' => $_POST['feedid']))->affectedRows();

            if ($affectedRows) {
                $response['status'] = true;
                $response['success'] = ERROR_POST_UPDATED_SUCCESSFULLY;
                return $response;
            } else {
                $response['error'] = ERROR_SOME_ISSUE_POSTING_UPDATE;
                return $response;
            }
        }

    }

}
