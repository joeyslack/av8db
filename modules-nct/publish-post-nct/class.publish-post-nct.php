<?php class Publish_post extends Home { 
    function __construct($platform='web',$current_user_id=0) {
        parent::__construct();
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->platform = $platform;
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
        if(isset($_REQUEST['feed_id']) && $_REQUEST['feed_id'] != '') {
                 $feed_id = filtering(decryptIt($_REQUEST['feed_id']), 'input', 'int');
                $this->editfeed_id=$feed_id;
                $checkIfExists = $this->db->select("tbl_feeds", "*", array("id" => $feed_id))->result();
    
                if(!$checkIfExists) {
                    $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_FEED_DOESNT_EXIST));
                        redirectPage(SITE_URL . "dashboard");
                }
        
        } /*else {
            $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => ERROR_SOME_ISSUE_TRY_LATER));
            redirectPage(SITE_URL . "dashboard");
        }*/
    }
    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->set('left_sidebar', $this->getLeftSidebar($this->current_user_id));
        $query = "SELECT id 
                FROM tbl_feeds 
                WHERE user_id = ? 
                AND status = ? 
                ORDER BY id DESC LIMIT 1";
        $saved_post_details = $this->db->pdoQuery($query,array($this->current_user_id,'s'))->result();
        if($saved_post_details) {
            $post_id = $saved_post_details['id'];
        } else {
            $post_id = 0;
        }
        $post_id = 0;
        $main_content->set('right_sidebar', $this->getRightSidebar($post_id));
        $final_result = $main_content->parse();
        return $final_result;
    }
    public function getRightSidebar($post_id = 0) {
        $final_result = NULL;
        $saved_post_details = array();
        $rightbar_content = new Templater(DIR_TMPL . $this->module . "/right-sidebar-nct.tpl.php");
        $rightbar_content_parsed = $rightbar_content->parse();
        $post_id=($post_id > 0 ?$post_id:$this->editfeed_id);
        $query = "SELECT * FROM tbl_feeds 
                WHERE id = '". $post_id ."' 
                AND user_id = ?
                ORDER BY id DESC LIMIT 1";
        $saved_post_details = $this->db->pdoQuery($query,array($this->current_user_id))->results();
        if($saved_post_details) {
            foreach ($saved_post_details as $key => $value) {
                 $fields = array(
                    "%POST_ID%",
                    "%PUBLISH_POST_URL%",
                    "%POST_TITLE%",
                    "%POST_DESC%",
                    "%FEED_IMAGE_SELECT_CONTAINER_HIDDEN_CLASS%",
                    "%FEED_IMAGE_PREVIEW_CONTAINER_HIDDEN_CLASS%",
                    "%FEED_IMAGE_URL%",
                    "%SAVE_BTN_CLASS%",
                );
                $feed_image_select_container_hidden_class =  $feed_image_preview_container_hidden_class = $feed_image_url = "";
                //echo $value['image_name']; exit;
                if ($value['image_name'] == '') {
                    $feed_image_preview_container_hidden_class = "hidden";
                } else {
                    // $feed_image_url = SITE_UPD_FEEDS . $value['image_name'];
                    $feed_image_url = 'https://storage.googleapis.com/av8db/feed-images-nct/'.$value['image_name'];
                    $is_image = getimagesize($feed_image_url);
                    if(!empty($is_image)){
                        $feed_image_url = $feed_image_url;
                    }else{
                        $feed_image_url = '';
                    }
                    $feed_image_select_container_hidden_class = "hidden";
                }
                $title = filtering($value['post_title'], 'output');
                $description = filtering($value['description'], 'output', 'text');
                $field_replace = array(
                    $post_id,
                    SITE_URL . "publish-post-save",
                    $title,
                    $description,
                    $feed_image_select_container_hidden_class,
                    $feed_image_preview_container_hidden_class,
                    $feed_image_url,
                    "",
                );
                
                if($this->platform =='app'){
                    $final_result = array('title'=>$title,'image'=>$feed_image_url,'description'=>$description);
                } else {
                    $final_result .= str_replace($fields, $field_replace, $rightbar_content_parsed);
                }
            }
        } else {
            $fields = array(
                "%PUBLISH_POST_URL%",
                "%POST_TITLE%",
                "%POST_DESC%",
                "%FEED_IMAGE_SELECT_CONTAINER_HIDDEN_CLASS%",
                "%FEED_IMAGE_PREVIEW_CONTAINER_HIDDEN_CLASS%",
                "%FEED_IMAGE_URL%",
                "%SAVE_BTN_CLASS%",
            );
            $field_replace = array(
                SITE_URL . "publish-post-save",
                "",
                "",
                "",
                "hidden",
                "",
                "",
            );
            $final_result .= str_replace($fields, $field_replace, $rightbar_content_parsed);
        }

        return $final_result;
    }
    public function getLeftSidebar($user_id, $currentPage = 1) {
        $final_result = NULL;
        $limit = 10;
        $offset = ($currentPage - 1 ) * $limit;
        $saved_post_details = array();
        $leftbar_content = new Templater(DIR_TMPL . $this->module . "/left-sidebar-nct.tpl.php");
        $leftbar_content_parsed = $leftbar_content->parse();
        $total_feeds = $this->db->count('tbl_feeds',array('user_id'=>$user_id,'status'=>'s'));
        $query = "SELECT * FROM tbl_feeds 
                WHERE user_id = ? 
                AND status = ? ORDER BY id DESC
                LIMIT $limit OFFSET " . $offset . " ";
        $saved_post_details = $this->db->pdoQuery($query,array($user_id,'s'))->results();
        //_print_r($saved_post_details);
        if($saved_post_details) {
            $count = 0;
            foreach ($saved_post_details as $key => $value) {
                $count++;

                $fields = array(
                    "%POST_ID_ENCRYPTED%",
                    "%SAVED_POST_TITLE%",
                    "%SAVED_POST_DATE%",
                    "%SELECTED_POST_CLASS%",
                );
                $id = filtering($value['id'], 'output', 'int');
                $title = filtering($value['post_title'], 'output');
                $time = time_elapsed_string(strtotime($value['updated_on']));
                $field_replace = array(
                    encryptIt($id),
                    ucwords($title),
                    $time,
                    $count == 1 ? "" : "",
                );
                if($this->platform == 'app'){
                    $app_result[] = array('id'=>$id,'title'=>$title,'time'=>$time);
                } else {
                    $final_result .= str_replace($fields, $field_replace, $leftbar_content_parsed);
                }
            }
        }

        if($this->platform == 'app'){
            $page_data = getPagerData($total_feeds, $limit);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$total_feeds);
            $final_result = array('data'=>(!empty($app_result)?$app_result:array()),'pagination'=>$pagination);
        }
        return $final_result;
    }
} ?>