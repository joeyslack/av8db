<?php

class Groups extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_groups';

        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $sql = "SELECT g.*, concat_ws(' ', u.first_name, u.last_name) as user_name, gt.group_type, IF(privacy = 'pr', 'Private', 'Public') as privacy_text, 
                IF(accessibility = 'awa', '-', IF(accessibility = 'a', 'Auto join', 'Request to join' ) ) as accessibility_text 
                FROM " . $this->table . " g 
                LEFT JOIN tbl_users u ON u.id = g.user_id 
                LEFT JOIN tbl_group_types gt ON gt.id = g.group_type_id 
                WHERE g.id = '" . $id . "' ";

            $groupDetails = $this->db->pdoQuery($sql)->result();
            //echo "<pre>";print_r($companyDetails);exit;

            $this->user_id = filtering($groupDetails['user_id'], 'input', 'int');
            $this->user_name = filtering($groupDetails['user_name']);
            $this->group_name = filtering($groupDetails['group_name']);
            $this->group_logo = filtering($groupDetails['group_logo']);
            $this->group_description = filtering($groupDetails['group_description']);

            $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members 
                    WHERE  group_id = '. $this->id .' ')->result();
            $this->total_members = $group_members['total_members'];

            $this->group_type_id = filtering($groupDetails['group_type_id'], 'output', 'int');
            $this->group_type = filtering($groupDetails['group_type']);

            //$this->group_industry_id = filtering($groupDetails['group_industry_id'], 'output', 'int');
            //$this->group_industry = filtering($groupDetails['industry_name']);

            $this->privacy = filtering($groupDetails['privacy']);
            $this->privacy_text = filtering($groupDetails['privacy_text']);

            $this->accessibility = filtering($groupDetails['accessibility']);
            $this->accessibility_text = filtering($groupDetails['accessibility_text']);

            $this->status = filtering($groupDetails['status']);

            $this->added_on = convertDate('onlyDate', $groupDetails['added_on']);
            $this->updated_on = convertDate('onlyDate', $groupDetails['updated_on']);
        } else {
            $this->user_id = '';
            $this->group_name = '';
            $this->group_logo = '';
            $this->group_description = '';
            $this->user_name = '';
            $this->total_members = '';

            $this->group_type_id = '';
            $this->group_type = '';

            //$this->group_industry_id = '';
           // $this->group_industry = '';

            $this->privacy = '';
            $this->privacy_text = '';

            $this->accessibility = '';
            $this->accessibility_text = '';

            $this->status = '';

            $this->added_on = '';
            $this->updated_on = '';
        }
        switch ($type) {
            case 'add' : {
                    $this->data['content'] = $this->getForm();
                    break;
                }
            case 'edit' : {
                    $this->data['content'] = $this->getForm();
                    break;
                }
            case 'view' : {
                    $this->data['content'] = $this->viewForm();
                    break;
                }
            case 'delete' : {
                    $this->data['content'] = json_encode($this->dataGrid());
                    break;
                }
            case 'datagrid' : {
                    $this->data['content'] = json_encode($this->dataGrid());
                }
        }
    }

    public function viewForm() {

        $src = $user_img1 = $profile_picture_name = $profile_picture_img = $grp_profile_picture_name = $grp_profile_picture_img = '';
        require_once(DIR_ADM_MOD . 'storage.php');
        $group_storage_view = new storage();
        
        $src2 = "group-logos-nct/";
        $user_profile = DIR_NAME_USERS."/".$this->user_id."/";

        $src = $group_storage_view->getImageUrl1('av8db','th2_'.$this->group_logo,$src2);
        $ck = getimagesize($src);
        if (!empty($ck)) {
            $image = '<img src="'.$src.'" class="" id="" alt="'.$this->group_logo.'" width="100" height="44" title="'.$this->group_logo.'">';    
        }else{
            $image = '<img src="https://storage.googleapis.com/av8db/no-image.jpg" class="" id="" alt="'.$this->group_logo.'" width="100" height="44" title="'.$this->group_logo.'">';
        }

        $get_profile_picture = $this->db->select("tbl_users", "*", array("id" => $this->user_id))->result();
        if ($get_profile_picture) {
            $profile_picture_name = filtering($get_profile_picture['profile_picture_name']);
        }

        if ($profile_picture_name == '') {
            $profile_picture_img = '<span title="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($get_profile_picture['first_name'][0]) . '</span>';
        }else 
        {
            $imgs = $group_storage_view->getImageUrl1('av8db','th2_'.$profile_picture_name,$user_profile);
            $up = getimagesize($imgs);
            if (!empty($up)) {
                   $profile_picture_img = '<picture>
                                            <source srcset="' . $imgs . '" type="image/jpg">
                                            <img src="' . $imgs . '" class="" alt="img" /> 
                                        </picture>';
            }else{
                $profile_picture_img = '<span title="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($get_profile_picture['first_name'][0]) . '</span>';
            }            
        }

        $content = $this->displayBox(array("label" => "Group name &nbsp;:", "value" => $this->group_name)) .
                $this->displayBox(array("label" => "Group logo &nbsp;:", "value" => $image)) .
                $this->displayBox(array("label" => "Group description &nbsp;:", "value" => $this->group_description));

        $content .=   $this->displayBox(array("label" => "Total members &nbsp;:", "value" => $this->total_members)) . 
                $this->displayBox(array("label" => "Group type &nbsp;:", "value" => $this->group_type)) .
                //$this->displayBox(array("label" => "Group industry &nbsp;:", "value" => $this->group_industry)) .
                $this->displayBox(array("label" => "Privacy &nbsp;:", "value" => $this->privacy_text)) .
                $this->displayBox(array("label" => "Accessibility &nbsp;:", "value" => $this->accessibility_text)) .
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive')) .
                $this->displayBox(array("label" => "Added On&nbsp;:", "value" => convertDate('onlyDate', $this->added_on)));


        $content .= '<div class="title-container"><h4>Group admin details </h4></div>';
        $content .=  $this->displayBox(array("label" => "user name &nbsp;:", "value" => $this->user_name)) .
                $this->displayBox(array("label" => "user image &nbsp;:", "value" => $profile_picture_img));

        $query = 'SELECT * FROM tbl_group_members 
                    WHERE group_id = '. $this->id .' ';
        $all_member_detail = $this->db->pdoQuery($query)->results();

        if($all_member_detail) {
            $content .= '<div class="title-container"><h4>Group member details </h4></div>';
            foreach ($all_member_detail as $key => $value) {
                $user_profile1 = '';
                $user_profile1 = DIR_NAME_USERS."/".$value['user_id']."/";
                $group_member_picture = $this->db->select("tbl_users", "*", array("id" => $value['user_id']))->result();
                if ($group_member_picture) {
                    $grp_profile_picture_name = filtering($group_member_picture['profile_picture_name']);
                }

                if ($grp_profile_picture_name == '') {
                    $grp_profile_picture_img = '<span title="' . $group_member_picture['first_name'] . ' ' . $group_member_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($group_member_picture['first_name'][0]) . '</span>';
                }else 
                {   
                    $img = $group_storage_view->getImageUrl1('av8db','th2_'.$grp_profile_picture_name,$user_profile1);
                    $gup = getimagesize($img);
                    if (!empty($gup)) {
                        $grp_profile_picture_img ='<picture>
                                            <source srcset="' . $img . '" type="image/jpg">
                                            <img src="' . $img . '" class="" alt="img" /> 
                                        </picture>';  
                    }else{
                        $grp_profile_picture_img = '<span title="' . $group_member_picture['first_name'] . ' ' . $group_member_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($group_member_picture['first_name'][0]) . '</span>';
                    }                    
                }

                $user_detail_array = $this->db->select('tbl_users', array('first_name', 'last_name'), array('id'=>$value['user_id']))->result();
                $user_name = $user_detail_array['first_name'] . " " . $user_detail_array['last_name'];

                $content .=  $this->displayBox(array("label" => "member name &nbsp;:", "value" => $user_name)) .
                $this->displayBox(array("label" => "member image &nbsp;:", "value" => $grp_profile_picture_img));
            }
        }
        return $content;
    }

    public function getCurrentLogoOfGroup() {
        $final_result = NULL;

        return $final_result;
    }

    public function getGroupTypeDD($selected_group_type_id) {
        $final_result = $group_type_options = NULL;

        $getSelectBoxOption = $this->getSelectBoxOption();

        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        $group_types = $this->db->pdoQuery("SELECT * FROM tbl_group_types WHERE status='a' ORDER BY id DESC")->results();

        if ($group_types) {
            foreach ($group_types as $single_group_type) {
                $selected = ($selected_group_type_id == $single_group_type['id']) ? "selected" : "";

                $fields_replace = array(
                    filtering($single_group_type['id']),
                    $selected,
                    filtering($single_group_type['group_type'])
                );

                $group_type_options .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }

        $group_type_dd = new Templater(DIR_ADMIN_TMPL . $this->module . "/group-type-dd-nct.tpl.php");
        $group_type_dd_parsed = $group_type_dd->parse();

        $fields_country = array("%GROUP_TYPE_OPTIONS%");
        $fields_country_replace = array($group_type_options);

        $final_result = str_replace($fields_country, $fields_country_replace, $group_type_dd_parsed);

        return $final_result;
    }

    // public function getGroupIndustryDD($selected_group_industry_id) {
    //     $final_result = $group_industry_options = NULL;

    //     $getSelectBoxOption = $this->getSelectBoxOption();

    //     $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

    //     $group_types = $this->db->pdoQuery("SELECT * FROM tbl_industries WHERE status='a' ORDER BY id DESC")->results();

    //     if ($group_types) {
    //         foreach ($group_types as $single_group_type) {
    //             $selected = ($selected_group_industry_id == $single_group_type['id']) ? "selected" : "";

    //             $fields_replace = array(
    //                 filtering($single_group_type['id']),
    //                 $selected,
    //                 filtering($single_group_type['industry_name'])
    //             );

    //             $group_industry_options .= str_replace($fields, $fields_replace, $getSelectBoxOption);
    //         }
    //     }

    //     $group_industry_dd = new Templater(DIR_ADMIN_TMPL . $this->module . "/group-industry-dd-nct.tpl.php");
    //     $group_industry_dd_parsed = $group_industry_dd->parse();

    //     $fields_country = array("%GROUP_INDUSTRY_OPTIONS%");
    //     $fields_country_replace = array($group_industry_options);

    //     $final_result = str_replace($fields_country, $fields_country_replace, $group_industry_dd_parsed);

    //     return $final_result;
    // }

    public function getForm() {

        $content = $image = '';
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();

        $status_a = ($this->status == 'a' ? 'checked' : '');
        $status_d = ($this->status != 'a' ? 'checked' : '');

        $privacy_pu = ($this->privacy == 'pu' ? 'checked' : '');
        $privacy_pr = ($this->privacy != 'pu' ? 'checked' : '');

        $accessibility_hidden_class = ($this->privacy == 'pr' ? 'hidden' : '');

        $accessibility_a = 'checked';
        $accessibility_rj = '';
        if ($this->privacy == 'pu') {
            $accessibility_a = ($this->accessibility == 'a' ? 'checked' : '');
            $accessibility_rj = ($this->accessibility == 'rj' ? 'checked' : '');
        }

        require_once(DIR_ADM_MOD . 'storage.php');
        $group_storage_edit = new storage();
        
        $src2 = "group-logos-nct/";

        $src = $group_storage_edit->getImageUrl1('av8db','th2_'.$this->group_logo,$src2);
        $ck = getimagesize($src);
        if (!empty($ck)) {
            $image = $src;    
        }else{
            $image = 'https://storage.googleapis.com/av8db/no-image.jpg';
        }

        $fields = array(
            "%MEND_SIGN%",
            "%GROUP_NAME%",
            "%IMAGE%",
            "%GROUP_DESCRIPTION%",
            "%GROUP_TYPE_DD%",
            //"%GROUP_INDUSTRY_DD%",
            "%PRIVACY_PU%",
            "%PRIVACY_PR%",
            "%ACCESSIBILITY_HIDDEN_CLASS%",
            "%ACCESSIBILITY_A%",
            "%ACCESSIBILITY_RJ%",
            "%STATUS_A%",
            "%STATUS_D%",
            "%TYPE%",
            "%ID%"
        );

        $fields_replace = array(
            MEND_SIGN,
            $this->group_name,
            $image,
            $this->group_description,
            $this->getGroupTypeDD($this->group_type_id),
            //$this->getGroupIndustryDD($this->group_industry_id),
            $privacy_pu,
            $privacy_pr,
            $accessibility_hidden_class,
            $accessibility_a,
            $accessibility_rj,
            $status_a,
            $status_d,
            $this->type,
            $this->id
        );

        $content = str_replace($fields, $fields_replace, $main_content);
        return sanitize_output($content);
    }

    public function dataGrid() {

        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);

        $whereCond = '';
        if (isset($chr) && $chr != '') {
            $whereCond .= " WHERE ( concat_ws(' ', u.first_name, u.last_name) LIKE '%" . $chr . "%' OR group_name LIKE '%" . $chr . "%' OR group_type_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%' OR IF(privacy = 'pr', 'Private', 'Public') LIKE '%" . $chr . "%' OR  IF(accessibility = 'awa', '-', IF(accessibility = 'a', 'Auto join', 'Request to join' ) ) LIKE '%" . $chr . "%' )";
        }
        
        if (isset($day) && $day != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " DAY(g.added_on) = '" . $day . "' ";
        }

        if (isset($month) && $month != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " MONTH(g.added_on) = '" . $month . "' ";
        }

        if (isset($year) && $year != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " YEAR(g.added_on) = '" . $year . "' ";
        }

        if (isset($group_id) && $group_id != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " (g.id) = '" . $group_id . "' ";
        }

        if (isset($sort)) {
            $sorting = $sort . ' ' . $order; 
        } 
        else {
            $sorting = 'id DESC';
        }

        $sql = "SELECT g.*, concat_ws(' ', u.first_name, u.last_name) as user_name, gt.group_type_".DEFAULT_LANGUAGE_ID." as group_type, IF(privacy = 'pr', 'Private', 'Public') as privacy_text, 
                IF(accessibility = 'awa', '-', IF(accessibility = 'a', 'Auto join', 'Request to join' ) ) as accessibility_text 
                FROM " . $this->table . " g 
                LEFT JOIN tbl_users u ON u.id = g.user_id 
                LEFT JOIN tbl_group_types gt ON gt.id = g.group_type_id 
                " . $whereCond . " ORDER BY " . $sorting;

        $sql_with_limit = $sql . " LIMIT " . $offset . " ," . $rows . " ";

        $getTotalRows = $this->db->pdoQuery($sql)->results();
        $totalRow = count($getTotalRows);

        $qrySel = $this->db->pdoQuery($sql_with_limit)->results();

        foreach ($qrySel as $fetchRes) {
            $id = $fetchRes['id'];
            $status = $fetchRes['status'];

            $status = ($fetchRes['status'] == "a") ? "checked" : "";

            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status)) : '';
            $operation = '';

            $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $id . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';

            require_once(DIR_ADM_MOD . 'storage.php');
            $group_storage = new storage();
            $src2 = "group-logos-nct/";
            $group_logo = $group_storage->getImageUrl1('av8db','th2_'.$fetchRes["group_logo"],$src2);
            $ck = getimagesize($group_logo);
            if (!empty($ck)) {
                $image = '<img src="'.$group_logo.'" class="" id="" alt="'.$this->group_logo.'" width="100" height="44" title="'.$this->group_logo.'">';    
            }else{
                $image = '<img src="https://storage.googleapis.com/av8db/no-image.jpg" class="" id="" alt="'.$this->group_logo.'" width="100" height="44" title="'.$this->group_logo.'">';
            }
           /* if(file_exists(DIR_UPD_GROUP_LOGOS . "th2_" .  $fetchRes["group_logo"])){
                $group_logo = SITE_UPD_GROUP_LOGOS . "th2_" .  $fetchRes["group_logo"];
            } else {
                $group_logo = SITE_THEME_IMG . "no-image.jpg";
            }*/

            
            /*$image = $this->img(array(
                "src" => $group_logo,
                "class" => "",
                "width" => "100",
                "height" => "44",
                "onlyField" => true,
                "title" => $this->group_logo,
                "alt" => $this->group_logo
            ));*/

            $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members 
                    WHERE  group_id = '. $fetchRes["id"] .' ')->result();
            $count_group_members = $group_members['total_members'];

            $final_array = array(
                filtering($fetchRes["id"], 'output', 'int'),
                filtering($fetchRes["user_name"]),
                filtering($fetchRes["group_name"]),
                $image,
                $count_group_members,
                filtering($fetchRes["group_type"]),
                //filtering($fetchRes["industry_name"]),
                filtering($fetchRes["privacy_text"]),
                filtering($fetchRes["accessibility_text"])
            );

            /*if (in_array('status', $this->Permission)) {
                $final_array = array_merge($final_array, array($switch));
            }*/
            if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) {
                $final_array = array_merge($final_array, array($operation));
            }

            $row_data[] = $final_array;
        }
        $result["sEcho"] = $sEcho;
        $result["iTotalRecords"] = (int) $totalRow;
        $result["iTotalDisplayRecords"] = (int) $totalRow;
        $result["aaData"] = $row_data;
        return $result;
    }

    public function toggel_switch($text) {
        $text['action'] = isset($text['action']) ? $text['action'] : 'Enter Action Here: ';
        $text['check'] = isset($text['check']) ? $text['check'] : '';
        $text['name'] = isset($text['name']) ? $text['name'] : '';
        $text['class'] = isset($text['class']) ? '' . trim($text['class']) : '';
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';

        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . '/switch-nct.tpl.php');
        $main_content = $main_content->parse();
        $fields = array("%NAME%", "%CLASS%", "%ACTION%", "%EXTRA%", "%CHECK%");
        $fields_replace = array($text['name'], $text['class'], $text['action'], $text['extraAtt'], $text['check']);
        return str_replace($fields, $fields_replace, $main_content);
    }

    public function operation($text) {

        $text['href'] = isset($text['href']) ? $text['href'] : 'Enter Link Here: ';
        $text['value'] = isset($text['value']) ? $text['value'] : '';
        $text['name'] = isset($text['name']) ? $text['name'] : '';
        $text['class'] = isset($text['class']) ? '' . trim($text['class']) : '';
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . '/operation-nct.tpl.php');
        $main_content = $main_content->parse();
        $fields = array("%HREF%", "%CLASS%", "%VALUE%", "%EXTRA%");
        $fields_replace = array($text['href'], $text['class'], $text['value'], $text['extraAtt']);
        return str_replace($fields, $fields_replace, $main_content);
    }

    public function displaybox($text) {

        $text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
        $text['value'] = isset($text['value']) ? $text['value'] : '';
        $text['name'] = isset($text['name']) ? $text['name'] : '';
        $text['class'] = isset($text['class']) ? 'form-control-static ' . trim($text['class']) : 'form-control-static';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';

        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . '/displaybox.tpl.php');
        $main_content = $main_content->parse();
        $fields = array("%LABEL%", "%CLASS%", "%VALUE%");
        $fields_replace = array($text['label'], $text['class'], $text['value']);
        return str_replace($fields, $fields_replace, $main_content);
    }

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->breadcrumb = $this->getBreadcrumb();

        $main_content_parsed = $final_result = $main_content->parse();

        $fields = array(
            "%VIEW_ALL_RECORDS_BTN%"
        );

        $view_all_records_btn = '';
        if (( isset($_GET['day']) && $_GET['day'] != '' ) || ( isset($_GET['month']) && $_GET['month'] != '' ) || ( isset($_GET['year']) && $_GET['year'] != '' ) || ( isset($_GET['group_id']) && $_GET['group_id'] > 0 )) {
            $view_all_records_btn = $this->getViewAllBtn();
        }

        $fields_replace = array(
            $view_all_records_btn
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

     public function img($text) {
        $text['href'] = isset($text['href']) ? $text['href'] : '';
        $text['src'] = isset($text['src']) ? $text['src'] : 'Enter Image Path Here: ';
        $text['name'] = isset($text['name']) ? $text['name'] : '';
        $text['id'] = isset($text['id']) ? $text['id'] : '';
        $text['class'] = isset($text['class']) ? '' . trim($text['class']) : '';
        $text['height'] = isset($text['height']) ? '' . trim($text['height']) : '';
        $text['width'] = isset($text['width']) ? '' . trim($text['width']) : '';
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : '';

        if ($text['onlyField'] == true) {
            $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/img_onlyfield.tpl.php");
            $main_content = $main_content->parse();
        } else {
            $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/img.tpl.php");
            $main_content = $main_content->parse();
        }
        $fields = array("%HREF%", "%SRC%", "%CLASS%", "%ID%", "%ALT%", "%WIDTH%", "%HEIGHT%", "%EXTRA%");
        $fields_replace = array($text['href'], $text['src'], $text['class'], $text['id'], $text['name'], $text['width'], $text['height'], $text['extraAtt']);
        return str_replace($fields, $fields_replace, $main_content);
    }

}
