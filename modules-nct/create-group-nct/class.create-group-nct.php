<?php 
class Create_group extends Home {
    function __construct($group_id = '') {
        $this->group_id = $group_id;
        parent::__construct();
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
         if ($this->group_id > 0) {
            $query = "SELECT g.group_name,g.group_logo,g.group_description,g.group_type_id,g.privacy,g.accessibility,gt.group_type_".$this->lId." as group_type FROM tbl_groups g LEFT JOIN tbl_group_types gt ON gt.id = g.group_type_id
                    WHERE g.id = ? ";
            $group_details_array = $this->db->pdoQuery($query,array($group_id))->result();

            $this->group_name = filtering($group_details_array['group_name'], 'output');
            $this->group_logo = filtering($group_details_array['group_logo']);
            $this->group_description = filtering($group_details_array['group_description'],'output', 'text');
            $this->group_type = filtering($group_details_array['group_type'], 'output');
            $this->group_type_id = filtering($group_details_array['group_type_id'], 'output', 'int');
            //$this->group_industry = filtering($group_details_array['industry_name'], 'output');
            //$this->group_industry_id = filtering($group_details_array['group_industry_id'], 'output', 'int');
            $this->privacy = filtering($group_details_array['privacy']);
            $this->accessibility = filtering($group_details_array['accessibility']);
            $this->title_text = LBL_EDIT_GROUP_PAGE;
        } else {
            $this->title_text = LBL_CREATE_GROUP_PAGE;
            $this->group_name = $this->group_logo = $this->group_description = $this->group_type = $this->privacy = $this->accessibility = $this->group_type_id = '';
        }
    }

    public function getPageContent() {
        $final_result = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content_parsed = $main_content->parse();

        $fields = array(
           "%GROUP_TYPE_OPTIONS%",
           //"%GROUP_INDUSTRY_OPTIONS%",
           "%LOGO_PREVIEW_CONTAINER_HIDDEN_CLASS%",
           "%LOGO_SELECT_CONTAINER_HIDDEN_CLASS%",
           "%TITLE_TEXT%",
           "%GROUP_NAME%",
           "%GROUP_DESCRIPTION%",
           "%GROUP_LOGO_URL%",
           "%APPROVE_MEMBERS%",
           "%PRIVACY_PR_CHECKED%",
           "%PRIVACY_PU_CHECKED%",
           "%AUTO_JOIN_CHECKED%",
           "%REQUEST_JOIN_CHECKED%",
           "%GROUP_ID%",
           "%ENCRYPTED_GROUP_ID%",
           "%DELETEURL_HIDDEN%",
           "%TERMS_CONDITION_URL%",
           "%AGREEMENT%",
           '%LBL_NOTE_SITE_IS_NOTE_ALLOWED_TO_YOUR_GROUP_NAME%',
           '%LBL_SITE_IS_ALLOWED_TO_BE_USED_IN_YOUR_GROUP_NAME%'
        );

        $logo_preview_container_hidden_class = $logo_select_container_hidden_class = $group_logo_url = '' ;
        if ($this->group_logo == '') { 
            $logo_preview_container_hidden_class = "hidden";
        } else {

            $group_logo_url = 'https://storage.googleapis.com/av8db/group-logos-nct/'.$this->group_logo;
            $is_image = getimagesize($group_logo_url);
            if(!empty($is_image)){
                $group_logo_url = $group_logo_url;
                $logo_select_container_hidden_class = "hidden";
            }else{
                $logo_preview_container_hidden_class = "hidden";
            }

            // $group_logo_url = SITE_UPD_GROUP_LOGOS . "th2_" . $this->group_logo;
            // if(file_exists(DIR_UPD_GROUP_LOGOS . "th2_" . $this->group_logo)) {
            //     $logo_select_container_hidden_class = "hidden";
            // } else {
            //     $logo_preview_container_hidden_class = "hidden";
            // }
        }

        $privacy_pr_checked = $privacy_pu_checked = $auto_join_checked = $request_join_checked = '';
        if($this->privacy == 'pr') {
            $auto_join_checked = 'checked';
            $privacy_pr_checked = 'checked';
        } else if($this->privacy == 'pu') {
            $privacy_pu_checked = 'checked';
            if($this->accessibility == 'a') {
                $auto_join_checked = 'checked';                    
            } else {
                $request_join_checked = 'checked';    
            }
            
        }
        $agreement = '';
        if ($this->group_id > 0) {
            $deleteUrl_hidden = '';
            $agreement = 'checked';
            //$privacy_pr_checked = $privacy_pu_checked = 'checked';
        } else {
            $deleteUrl_hidden = 'hidden';
            //$privacy_pr_checked = $privacy_pu_checked = '';
        }

        $fields_replace = array(
           $this->getGroupType($this->group_type_id),
           //$this->getGroupIndustry($this->group_industry_id),
           $logo_preview_container_hidden_class,
           $logo_select_container_hidden_class,
           $this->title_text,
           $this->group_name,
           $this->group_description,
           $group_logo_url,
           ($this->group_id > 0) ? $this->getGroupMembers($this->group_id): '',
           $privacy_pr_checked,
           $privacy_pu_checked,
           $auto_join_checked,
           $request_join_checked,
           $this->group_id,
           encryptIt($this->group_id),
           $deleteUrl_hidden,
           SITE_URL . 'content/terms-conditions',
           $agreement,
           str_replace("%SITE_NM%",SITE_NM,LBL_NOTE_SITE_IS_NOTE_ALLOWED_TO_YOUR_GROUP_NAME),
           str_replace("%SITE_NM%",SITE_NM,LBL_SITE_IS_ALLOWED_TO_BE_USED_IN_YOUR_GROUP_NAME),
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getGroupMembers($group_id,$plateform='web') {
        $final_result = NULL;

        
        $group_members = $this->db->pdoQuery('SELECT user_id FROM tbl_group_members
                    WHERE  group_id = ? AND action != ? AND action != ?   ',array($group_id,"r","jr"))->results();


        if ($group_members) {
            for ($i = 0; $i < count($group_members); $i++) {
                $group_members_id = $group_members[$i]['user_id'];
                $response = $this->generateApproveMemeberBox($group_members_id,$plateform);
                if($plateform == 'app'){
                    $final_result[] = $response;
                } else {
                    $final_result .= $response['content'];
                }
            }
        }

        return $final_result;
    }

    public function generateApproveMemeberBox($user_id,$plateform='web') {
        $final_result = '';
        $response = array();
        $response['status'] = false;

        $user_details = $this->db->select("tbl_users", array('first_name,last_name,profile_picture_name'), array("id" => $user_id))->result();

        $first_name = filtering($user_details['first_name']);
        $last_name = filtering($user_details['last_name']);
        $profile_url = get_user_profile_url($user_id);
        $profile_picture_url = SITE_URL . "image/" . DIR_NAME_USERS . "/" . filtering($user_details['profile_picture_name']);
        //$healine = getUserHeadline($user_id);
        $healine = '';
        $single_member_admin_tpl = new Templater(DIR_TMPL . $this->module . "/single-approve-member-nct.tpl.php");

        $user_headline_tpl_parsed = "";
        if ($healine) {
            $user_headline_tpl = new Templater(DIR_TMPL . $this->module . "/user-headline-nct.tpl.php");
            $user_headline_tpl_parsed = $user_headline_tpl->parse();
        }
        $single_member_admin_tpl->set('user_headline', $user_headline_tpl_parsed);

        $single_member_admin_tpl_parsed = $single_member_admin_tpl->parse();

        $fields = array(
            "%USER_ID_ENCRYPTED%",
            "%UNIQUE_IDENTIFIER%",
            "%USER_PROFILE_PICTURE%",
            "%USER_NAME%",
            "%PROFILE_URL%",
            "%HEADLINE%",
            "%USER_ID%"
        );

        $unique_identifier = time();
        $image = getImageURL("user_profile_picture", $user_id, "th3",$plateform);
        $name = $first_name . " " . $last_name;
        $fields_replace = array(
            encryptIt($user_id),
            $unique_identifier,
            $image,
            ucwords($name),
            $profile_url,
            ucwords($healine),
            $user_id
        );

        if($plateform == 'app'){
            $final_app = array(
                'user_id'=>$user_id,
                'user_image'=>$image,
                'user_name'=>$name,
                'tagline'=>$healine
            );
            return $final_app;
        } else {
            $final_result = str_replace($fields, $fields_replace, $single_member_admin_tpl_parsed);
            $response['status'] = true;
            $response['content'] = $final_result;
            return $response;
        }

    }

    public function getConnectionsForGropus($post,$platform='web') {
        $final_result = $approve_member_ids = $users_id_arr =  array();
        $approve_member_ids_imploded = "";

        $post = ($platform == 'web') ? $_POST : $post;
        
        $user_id = ($platform == 'web') ? $this->session_user_id : $post['user_id'];
        $user_name = filtering($post['user_name'], 'input');

        $approve_member_ids_encrypted=((isset($post['approve_member_ids']) ) ? $post['approve_member_ids'] : '' );
        if (is_array($approve_member_ids_encrypted) && !empty($approve_member_ids_encrypted)) {
            for ($i = 0; $i < count($approve_member_ids_encrypted); $i++) {
                $approve_member_ids[] = decryptIt($approve_member_ids_encrypted[$i]);
            }

            $approve_member_ids_imploded = implode(",", $approve_member_ids);
        }else{
             $approve_member_ids_imploded = $approve_member_ids_encrypted;
        }

        
        $not_in_query = "";

        if ($approve_member_ids_imploded != "") {
            $not_in_query = " AND u.id NOT IN ( " . $approve_member_ids_imploded . " ) ";
        }

        $query = "SELECT u.id as user_id, concat_ws(' ', first_name, last_name) as user_name 
                    FROM tbl_connections c 
                    LEFT JOIN tbl_users u ON u.id = IF(c.request_from = '" . $user_id . "', c.request_to, c.request_from )
                    WHERE ( c.request_from = ? OR c.request_to = ? ) AND ( concat_ws(' ', first_name, last_name) LIKE ? OR first_name LIKE ? OR last_name LIKE ? ) 
                    AND u.status = ? AND c.status = ? " . $not_in_query . "
                    GROUP BY u.id ORDER BY u.id DESC LIMIT 0, 10 ";

        $final_result = $this->db->pdoQuery($query,array($user_id,$user_id,"%".$user_name."%","%".$user_name."%","%".$user_name."%",'a','a'))->results();
        return $final_result;
    }

    public function getGroupType($selected_id,$plateform='web') {
        $final_result = NULL;
        $group_type_arr = $this->db->select('tbl_group_types', '*' , array('status' => 'a'))->results();
        if($group_type_arr) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($group_type_arr); $i++) {
                $selected = $group_type_arr[$i]['id'] == $selected_id ? 'selected' : '';
                $group_type = filtering($group_type_arr[$i]['group_type_'.$this->lId], 'output', 'text');
                $fields_replace = array(
                    filtering($group_type_arr[$i]['id'], 'input', 'int'),
                    $selected,
                    ucwords($group_type)
                );
                if($plateform == 'app'){
                    $final_result[] = array('id'=>$group_type_arr[$i]['id'],'group_type'=>$group_type);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
                }
            }
        }
        return $final_result;
    }

    // public function getGroupIndustry($selected_id,$plateform='web') {
    //     $final_result = NULL;
    //     $industry_arr = $this->db->select('tbl_industries', '*' , array('status' => 'a'))->results();
    //     if($industry_arr) {
    //         $getSelectBoxOption = $this->getSelectBoxOption();
    //         $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
    //         for ($i = 0; $i < count($industry_arr); $i++) {
    //             $selected = $industry_arr[$i]['id'] == $selected_id ? 'selected' : '';
    //             $industry_name = filtering($industry_arr[$i]['industry_name_'.$this->lId], 'output', 'text');
    //             $fields_replace = array(
    //                 filtering($industry_arr[$i]['id'], 'input', 'int'),
    //                 $selected,
    //                 ucwords($industry_name)
    //             );
    //             if($plateform == 'app'){
    //                 $final_result[] = array('id'=>$industry_arr[$i]['id'],'industry_name'=>$industry_name);
    //             } else {
    //                 $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
    //             }
    //         }
    //     }
    //     return $final_result;
    // }

    public function uploadGroupLogo(){
        $user_logo = '';
        $images_str=$_SESSION['temp_files'];
        $main_url=$_SESSION['main_url'];

        $temp_src = "group-logos-nct/".$images_str;
        $temp_src2 = DIR_NAME_GROUP_LOGOS.'/';

        require_once(DIR_MOD . 'common_storage.php');
        $resize_image = new storage();

        if($images_str!=""){
             
            if($images_str !="" || $images_str !=NULL){
             $to_path=DIR_UPD."temp_files/th1_$images_str";
             $file_name= DIR_UPD_GROUP_LOGOS;
             
             $uploadDir = DIR_NAME_GROUP_LOGOS.'/';
             $image_resize_array = unserialize(GROUP_LOGO_RESIZE_ARRAY);
            
            /*$main_img = $resize_image->upload_object('av8db','',$images_str,$temp_src);
            $get_main_img = $resize_image->getImageUrl1('av8db',$images_str,$temp_src2);*/

            $length = count($image_resize_array);
            for ($i = 0; $i < $length; $i++) {
                $im1 = new Imagick($main_url);
                $im1->readImage($main_url);
               
                $im1->resizeImage($image_resize_array[$i]['width'], $image_resize_array[$i]['height'], Imagick::FILTER_LANCZOS, 1);
                $resize_img = $resize_image->upload_objectBlob('av8db','th'.($i+1).'_'.$images_str,$im1->getImageBlob(),$uploadDir);
                $im1->clear();
                $im1->destroy();
            }

            $image_name = $images_str;
            // $image_name = GenerateThumbnail($image_url, $uploadDir, $image_url, $image_resize_array);
             if($image_name != '' && $image_name != 0)
             {
                $user_logo = $image_name;
                 //$db->update('tbl_users',array('profile_picture_name'=>$user_logo),array('id'=>$id));

              }                
            }
            $del = $resize_image->delete_object('av8db',$images_str,'');
            $_SESSION['temp_files']='';
            // $files = glob(DIR_UPD."temp_files/*"); // get all file names
            /*foreach($files as $file){ // iterate files
                if(is_file($file))
                unlink($file); // delete file
            }*/
        }   
        return $user_logo;     
    }

    public function processGroupCreation($current_user_id,$platform='web') {
        //_print_r($_POST);exit;
        extract($_POST);
        require_once(DIR_MOD . 'common_storage.php');
        $edit_group_logo = new storage();

        $response = $group_details_array = array();
        $response['status'] = false;
        $image = '';
        $acknowledge_check=(isset($_POST['acknowledge_check']) && $_POST['acknowledge_check'] != '')?$_POST['acknowledge_check']:'';
        $agreement=(isset($_POST['agreement']) && $_POST['agreement'] != '')?$_POST['agreement']:'';
        if($group_name == '' && $group_decription == '' && $group_type_id == '' && $privacy == '' && $acknowledge_check == '' && $agreement == ''){

            $response['error'] = ERROR_ADD_EDIT_EDUCATION_FILL_ALL_MANDATORY_FIELDS;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => ERROR_ADD_EDIT_EDUCATION_FILL_ALL_MANDATORY_FIELDS));
 
        }else if(trim($group_name) == ''){

            $response['error'] = ERROR_ENTER_GROUP_NAME;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => ERROR_ENTER_GROUP_NAME));
        }else if(trim($group_decription) == ''){

            $response['error'] = ERROR_GROUP_DESCRIPTION;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => ERROR_GROUP_DESCRIPTION));
        }else if($group_type_id == ''){

            $response['error'] = ERROR_SELECT_GROUP_TYPE;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => ERROR_SELECT_GROUP_TYPE));
        }else if($privacy == ''){

            $response['error'] = ERROR_SELECT_PRIVACY;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => ERROR_SELECT_PRIVACY));
        }else if($acknowledge_check == ''){

            $response['error'] = ERROR_ACCEPT_USER_AGGREMENT_CONDITIONS;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => ERROR_ACCEPT_USER_AGGREMENT_CONDITIONS));
        }else if($agreement == ''){

            $response['error'] = ERROR_GROUP_ACCEPT_TERMS_SERVICE;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => ERROR_GROUP_ACCEPT_TERMS_SERVICE));
        }
        else{
            $group_details_array['group_id'] = filtering($_POST['group_id'], 'input', 'int');
            $group_details_array['group_logo'] = '';
            $old_logo = getTableValue("tbl_groups","group_logo",array("id"=>$group_details_array['group_id']));
            if($platform == 'web' && $_SESSION['temp_files'] != ''){
                if($old_logo != ''){
                        $old_src = DIR_NAME_GROUP_LOGOS.'/';
                        $del = $edit_group_logo->delete_object1('av8db',$old_logo,'',$old_src);
                        $del = $edit_group_logo->delete_object1('av8db','th1_'.$old_logo,'',$old_src);
                        $del = $edit_group_logo->delete_object1('av8db','th2_'.$old_logo,'',$old_src);
                        $del = $edit_group_logo->delete_object1('av8db','th3_'.$old_logo,'',$old_src);

                        if(file_exists(DIR_UPD_GROUP_LOGOS.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.$old_logo);
                        }
                        if(file_exists(DIR_UPD_GROUP_LOGOS.'th1_'.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.'th1_'.$old_logo);
                        }
                        if(file_exists(DIR_UPD_GROUP_LOGOS.'th2_'.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.'th2_'.$old_logo);
                        }
                        if(file_exists(DIR_UPD_GROUP_LOGOS.'th3_'.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.'th3_'.$old_logo);
                        }
                }

                $image =  $this->uploadGroupLogo();

                $group_details_array['group_logo'] = $image;
            }else if(!empty($group_details_array['group_id']) && $_POST['is_logo_removed']!= 'true' ){
                
                $group_details_array['group_logo'] = $old_logo;
            }else{
                 if($old_logo != ''){

                        if(file_exists(DIR_UPD_GROUP_LOGOS.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.$old_logo);
                        }
                        if(file_exists(DIR_UPD_GROUP_LOGOS.'th1_'.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.'th1_'.$old_logo);
                        }
                        if(file_exists(DIR_UPD_GROUP_LOGOS.'th2_'.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.'th2_'.$old_logo);
                        }
                        if(file_exists(DIR_UPD_GROUP_LOGOS.'th3_'.$old_logo)){
                            unlink(DIR_UPD_GROUP_LOGOS.'th3_'.$old_logo);
                        }
                }

                 $group_details_array['group_logo'] = '';
            }
            if($group_details_array['group_id'] > 0 && $platform == 'app') {
                if (isset($_FILES['group_logo']) && !($_FILES['group_logo']['error'])) {
                    $file_array = $_FILES["group_logo"];
                    $upload_dir = DIR_UPD_GROUP_LOGOS;
                    $image_resize_array = unserialize(GROUP_LOGO_RESIZE_ARRAY);

                    $response = uploadImage($file_array, $upload_dir, $image_resize_array);
                    if (!$response['status']) {
                        return $response;
                    } else {
                        $group_details_array['group_logo'] = $response['image_name'];
                    }
                } else {
                    $group_logo_detail = $this->db->select('tbl_groups', array('group_logo'), array('id' => $group_details_array['group_id']))->result();
                    $group_details_array['group_logo'] = $group_logo_detail['group_logo'];
                }
         
            } else {
                if (isset($_FILES['group_logo']) && !($_FILES['group_logo']['error'])) {
                    $file_array = $_FILES["group_logo"];
                    $upload_dir = DIR_UPD_GROUP_LOGOS;
                    $image_resize_array = unserialize(GROUP_LOGO_RESIZE_ARRAY);

                    /*$response = uploadImage($file_array, $upload_dir, $image_resize_array);
                    if (!$response['status']) {
                        return $response;
                    } else {
                        $group_details_array['group_logo'] = $response['image_name'];
                    }*/
                    $group_details_array['group_logo'] = $image;
                }else if ($_POST['is_logo_removed'] == "true") {
                    $company_details_array['company_logo'] = "";
                    if($old_logo['company_logo'] != ''){
                        if(file_exists(DIR_UPD_COMPANY_LOGOS.$old_logo['company_logo'])){
                            unlink(DIR_UPD_COMPANY_LOGOS.$old_logo['company_logo']);
                        }
                        if(file_exists(DIR_UPD_COMPANY_LOGOS.'th1_'.$old_logo['company_logo'])){
                            unlink(DIR_UPD_COMPANY_LOGOS.'th1_'.$old_logo['company_logo']);
                        }
                        if(file_exists(DIR_UPD_COMPANY_LOGOS.'th2_'.$old_logo['company_logo'])){
                            unlink(DIR_UPD_COMPANY_LOGOS.'th2_'.$old_logo['company_logo']);
                        }
                    }
                }
            }

            $group_details_array['group_name'] = filtering($_POST['group_name'], 'input');
            $group_details_array['group_decription'] = filtering($_POST['group_decription'], 'input', 'text');

            $group_details_array['group_type_id'] = filtering($_POST['group_type_id'], 'input', 'int');
           // $group_details_array['group_industry_id'] = filtering($_POST['group_industry_id'], 'input', 'int');

            $group_details_array['privacy'] = filtering($_POST['privacy'], 'input');

            if($group_details_array['privacy'] == 'privacy_pr') {
                $approve_member_ids=((isset($_POST['approve_member_ids']) ) ? $_POST['approve_member_ids'] : array() );
                $group_details_array['accessibility'] = 'awa';
                $group_details_array['privacy'] = 'pr';
            } else{
                $approve_member_ids = array();
                $group_details_array['accessibility'] = (isset($_POST['accessibility']) && filtering($_POST['accessibility'], 'input') == 'accessibility_a' ) ? 'a' : 'rj';
                $group_details_array['privacy'] = 'pu';
            }

            $val_array = array(
                'user_id' => $current_user_id,
                'group_name' =>  $group_details_array['group_name'],
                'group_logo' => $group_details_array['group_logo'],
                'group_description' => $group_details_array['group_decription'],
                'group_type_id' => $group_details_array['group_type_id'],
                //'group_industry_id' => $group_details_array['group_industry_id'],
                'privacy' => $group_details_array['privacy'],
                'accessibility' => $group_details_array['accessibility'],
                'status' => 'a',
                'updated_on' => date('Y-m-d H:i:s'),
            );

            if($group_details_array['group_id'] > 0) {

                $id = $group_details_array['group_id'];
               // _print_r($val_array);
                $affectedRows = $this->db->update("tbl_groups", $val_array, array('id' => $group_details_array['group_id']))->affectedRows();
                //_print_r($approve_member_ids);
                if (!empty($approve_member_ids)) {
                    for ($i = 0; $i < count($approve_member_ids); $i++) {
                        $user_id = filtering(decryptIt($approve_member_ids[$i]), 'input', 'int');

                        $checkIfExists = $this->db->select("tbl_group_members", array('id'), array("group_id" => $group_details_array['group_id'], "user_id" => $user_id))->result();
                        if (!$checkIfExists) {
                            $group_member_array = array(
                                "group_id" => $group_details_array['group_id'],
                                "user_id" => $user_id,
                                'action' => 'aa',
                                'joining_request_on' => date("Y-m-d H:i:s"),
                                'action_taken_on' => date("Y-m-d H:i:s"),
                                "joined_on" => date("Y-m-d H:i:s"),
                            );
                            $this->db->insert("tbl_group_members", $group_member_array)->getLastInsertId();

                            $notificationArray = array(                            
                                "group_id" => $group_details_array['group_id'],
                                "user_id" => $user_id,
                                "type" => "ampg",
                                "action_by_user_id" => $current_user_id,
                                "added_on" => date("Y-m-d H:i:s"),
                                "updated_on" => date("Y-m-d H:i:s")
                            );
                            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                            /* Push notification */
                            $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$current_user_id))->result();
                            $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                            $push_data = array(
                                'user_name'=>$push_user_name,
                                'group_name'=>$group_details_array['group_name'],
                                'notification_id'=>$notification_id,
                                'group_id'=>$group_details_array['group_id']

                            );
                            set_notification($user_id,'ampg',$push_data);
                        }
                    }
                }

            } else {
                
                $val_array['added_on']=date("Y-m-d H:i:s");
                $id = $this->db->insert("tbl_groups", $val_array)->getLastInsertId();
                
                if (!empty($approve_member_ids)) {
                    for ($i = 0; $i < count($approve_member_ids); $i++) {
                        $user_id = filtering(decryptIt($approve_member_ids[$i]), 'input', 'int');

                        $checkIfExists = $this->db->select("tbl_group_members", array('id'), array("group_id" => $id, "user_id" => $user_id))->result();
                        if (!$checkIfExists) {
                            $group_member_array = array(
                                "group_id" => $id,
                                "user_id" => $user_id,
                                'action' => 'aa',
                                'joining_request_on' => date("Y-m-d H:i:s"),
                                'action_taken_on' => date("Y-m-d H:i:s"),
                                "joined_on" => date("Y-m-d H:i:s"),
                            );
                            $this->db->insert("tbl_group_members", $group_member_array)->getLastInsertId();

                            $notificationArray = array(                            
                                "group_id" => $id,
                                "user_id" => $user_id,
                                "type" => "ampg",
                                "action_by_user_id" => $current_user_id,
                                "added_on" => date("Y-m-d H:i:s"),
                                "updated_on" => date("Y-m-d H:i:s")
                            );
                            $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                            /* Push notification */
                            $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$current_user_id))->result();
                            $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                            $push_data = array(
                                'user_name'=>$push_user_name,
                                'group_name'=>$group_details_array['group_name'],
                                'notification_id'=>$notification_id,
                                'group_id'=>$id
                                );
                            set_notification($user_id,'ampg',$push_data);
                        }
                    }
                }

            }
            /*require_once(DIR_MOD . 'common_storage.php');
            $delete_image = new storage();
            $del = $delete_image->delete_object('av8db',$image,'');*/
        }
        if($group_details_array['group_id'] > 0) {
                 if ($affectedRows ) {
                    $response['status'] = true;
                    $response['message'] =LBL_GROUP_UPDATED;
                    //$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => LBL_GROUP_UPDATED));
                    $response['redirect_url'] = SITE_URL . "group/" . $id;
                } else {
                    $response['message'] = $response['error'] = ERROR_SOMETHING_WRONG;
                }
            } else {
                if ($id ) {
                    $response['status'] = true;
                    $response['message'] = LBL_GROUP_ADDED;
                    $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => LBL_GROUP_ADDED));
                    $response['redirect_url'] = SITE_URL . "group/" . $id;
                } else {
                    $response['message'] = $response['error'] ;
                    if($response['message']==''){
                        $response['message'] = $response['error'] = ERROR_SOMETHING_WRONG;

                    }
                }
            }
            if($platform == 'app'){
                $response['status'] = ($response['status'] == true)?'success':'error';
            }
        return $response;
    }

    public function deleteGroup($group_id) {

        $response = array();
        $response['status'] = false;
        $image=$this->db->select('tbl_groups','group_logo',array('id'=>$group_id))->result();
    
        /*unlink(DIR_UPD_FEEDS . $image['group_logo']);
        unlink(DIR_UPD_FEEDS .'th1_'. $image['group_logo']);
        unlink(DIR_UPD_FEEDS .'th2_'. $image['group_logo']);
        unlink(DIR_UPD_FEEDS .'th3_'. $image['group_logo']);*/
        
        require_once(DIR_MOD . 'common_storage.php');
        
        $delete_group_logo_image = new storage();
        if ($image['group_logo'] != '') {
            $src2 = 'group-logos-nct/';
            $main_img = $delete_group_logo_image->getImageUrl1('av8db',$image['group_logo'],$src2);
            $is_main_img = getimagesize($main_img);
            if(!empty($is_main_img)){
                $del = $delete_group_logo_image->delete_object1('av8db',$image['group_logo'],'',$src2);
            }

            $main_img_one = $delete_group_logo_image->getImageUrl1('av8db','th1_'.$image['group_logo'],$src2);
            $is_main_img_one = getimagesize($main_img_one);
            if(!empty($is_main_img_one)){
                $del1 = $delete_group_logo_image->delete_object1('av8db','th1_'.$image['group_logo'],'',$src2);
            }

            $main_img_two = $delete_group_logo_image->getImageUrl1('av8db','th2_'.$image['group_logo'],$src2);
            $is_main_img_two = getimagesize($main_img_two);
            if(!empty($is_main_img_two)){
                $del2 = $delete_group_logo_image->delete_object1('av8db','th2_'.$image['group_logo'],'',$src2);
            }

            $main_img_three = $delete_group_logo_image->getImageUrl1('av8db','th3_'.$image['group_logo'],$src2);
            $is_main_img_three = getimagesize($main_img_three);
            if(!empty($is_main_img_three)){
                $del3 = $delete_group_logo_image->delete_object1('av8db','th3_'.$image['group_logo'],'',$src2);
            }
        }
        
        $this->db->delete('tbl_notifications',array('group_id'=>$group_id))->affectedRows();
        $affectedRows = $this->db->delete("tbl_groups", array("id" => $group_id))->affectedRows();
        
        if($affectedRows && $affectedRows > 0){
            $response['status'] = true;
            $response['success'] = LBL_GROUP_DELETED;
        } else {
            $response['error'] = ERROR_SOMETHING_WRONG ;
        }
        return $response;
    }

    public function deleteMember($member_id,$group_id){
        $response = array();
        $response['status'] = false;
        
        $affectedRows = $this->db->delete("tbl_group_members", array("user_id" => $member_id,"group_id" => $group_id))->affectedRows();

        if($affectedRows && $affectedRows > 0){
            $response['status'] = true;
            
        } else {
            $response['error'] = ERROR_SOMETHING_WRONG;
        }

        return $response;
    }
    public function getInvitationId($platform='web'){
      $final_result = $approve_member_ids = $users_id_arr =  array();
        $approve_member_ids_imploded = "";

      $user_id = ($platform == 'web') ? $this->session_user_id : '';
      $user_name = filtering($_POST['user_name'], 'input');
      $group_id=decryptIt($_POST['group_id']);
        
      $approve_member_ids_arr=$this->db->pdoQuery('SELECT user_id FROM tbl_group_members WHERE group_id = ? AND (action = ? OR action = ? OR action = ?)',array($group_id,'aj','a','aa'))->results();
      if (!empty($approve_member_ids_arr)) {
            //echo "<pre>";print_r($approve_member_ids);exit;
            for ($i = 0; $i < count($approve_member_ids_arr); $i++) {
                $approve_member_ids[] = $approve_member_ids_arr[$i]['user_id'];
            }

            $approve_member_ids_imploded = implode(',', $approve_member_ids);

      }
      $not_in_query = "";

      if ($approve_member_ids_imploded != "") {
          $not_in_query = " AND u.id NOT IN ( " . $approve_member_ids_imploded . " ) ";
      }
       $query = "SELECT u.id as user_id, concat_ws(' ', first_name, last_name) as user_name 
                    FROM tbl_connections c 
                    LEFT JOIN tbl_users u ON u.id = IF(c.request_from = '" . $user_id . "', c.request_to, c.request_from )
                    WHERE ( c.request_from = ? OR c.request_to = ? ) AND ( concat_ws(' ', first_name, last_name) LIKE ? OR first_name LIKE ? OR last_name LIKE ? ) 
                    AND u.status = ? AND c.status = ? " . $not_in_query . "
                    GROUP BY u.id ORDER BY u.id DESC LIMIT 0, 10 ";

        $final_result = $this->db->pdoQuery($query,array($user_id,$user_id,"%" . $user_name . "%","%" . $user_name . "%","%" . $user_name . "%",'a','a'))->results();
        return $final_result;
      

    }

}

?>
