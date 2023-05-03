<?php

class Users extends Home {

    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields, $sessCataId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_users';

        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();
        if ($this->id > 0) {
            $query = "SELECT u.* 
                    FROM tbl_users u 
                    WHERE u.id = '" . $this->id . "' ";

            $qrySel = $this->db->pdoQuery($query)->result();

            $fetchRes = $qrySel;

            $this->data['first_name'] = $this->first_name = filtering($fetchRes['first_name']);
            $this->data['last_name'] = $this->last_name = filtering($fetchRes['last_name']);
            $this->data['email_address'] = $this->email_address = filtering($fetchRes['email_address']);
            $this->data['gender'] = $this->gender = ( ( $fetchRes['gender'] == "m" ) ? "Male" : "Female" );

            $this->data['phone_no'] = $this->phone_no = filtering($fetchRes['phone_no']);

            $this->data['status'] = $this->status = $fetchRes['status'];
        } else {
            $this->data['first_name'] = $this->first_name = '';
            $this->data['last_name'] = $this->last_name = '';
            $this->data['email_address'] = $this->email_address = '';
            $this->data['date_of_birth'] = $this->date_of_birth = '';
            $this->data['phone_no'] = $this->phone_no = '';

            $this->data['status'] = $this->status = 'a';
        }
        switch ($type) {
            case 'add' : {
                    $this->data['content'] = (in_array('add', $this->Permission)) ? $this->getForm() : '';
                    break;
                }
            case 'edit' : {
                    $this->data['content'] = (in_array('edit', $this->Permission)) ? $this->getForm() : '';
                    break;
                }
            case 'view' : {
                    $this->data['content'] = (in_array('view', $this->Permission)) ? $this->viewForm() : '';
                    break;
                }
            case 'delete' : {
                    $this->data['content'] = (in_array('delete', $this->Permission)) ? json_encode($this->dataGrid()) : '';
                    break;
                }
            case 'datagrid' : {
                    $this->data['content'] = (in_array('module', $this->Permission)) ? json_encode($this->dataGrid()) : '';
                }
        }
    }

    public function viewForm() {
        $content = $this->displayBox(array("label" => "User Type&nbsp;:", "value" => $this->user_type)) .
                $this->displayBox(array("label" => "First Name&nbsp;:", "value" => $this->first_name)) .
                $this->displayBox(array("label" => "Last Name&nbsp;:", "value" => $this->last_name)) .
                $this->displayBox(array("label" => "Email&nbsp;:", "value" => $this->email_address)) .
                $this->displayBox(array("label" => "Gender&nbsp;:", "value" => $this->gender)) .
                $this->displayBox(array("label" => "Phone No. &nbsp;:", "value" => $this->phone_no)) .
                $this->displayBox(array("label" => "Country Name&nbsp;:", "value" => $this->countryName)) .
                $this->displayBox(array("label" => "State Name&nbsp;:", "value" => $this->stateName)) .
                $this->displayBox(array("label" => "City Name&nbsp;:", "value" => $this->cityName)) .
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive'));
        return $content;
    }

    public function getForm() {
        $content = '';

        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();
        $static_a = ($this->status == 'a' ? 'checked' : '');
        $static_d = ($this->status != 'a' ? 'checked' : '');

        $gender_m = ($this->gender == 'Male' ? 'checked' : '');
        $gender_f = ($this->gender != 'Male' ? 'checked' : '');

        $fields = array(
            "%FIRST_NAME%",
            "%LAST_NAME%",
            "%EMAIL_ADDRESS%",
            "%GENDER_M%",
            "%GENDER_F%",
            "%PHONE_NO%",
            "%STATUS_A%",
            "%STATUS_D%",
            "%TYPE%",
            "%ID%"
        );

        $fields_replace = array(
            $this->data['first_name'],
            $this->data['last_name'],
            $this->data['email_address'],
            $gender_m,
            $gender_f,
            $this->data['phone_no'],
            $static_a,
            $static_d,
            $this->type,
            $this->id
        );

        $content = str_replace($fields, $fields_replace, $main_content);
        return filtering($content, 'output', 'text');
    }

    public function dataGrid() {
        $admSl = $this->db->select("tbl_admin", array("id"), array("id ="=>(int)$_SESSION["adminUserId"]))->result();


        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);
        //$whereCond = ' where  status!=\'a\'';
        if (isset($chr) && $chr != '') {
            $whereCond .= "  WHERE (first_name LIKE '%" . $chr . "%' 
                OR last_name LIKE '%" . $chr . "%' OR email_address LIKE '%" . $chr . "%' 
                OR DATE_FORMAT(u.date_added, '" . MYSQL_DATE_FORMAT . "') LIKE '%" . $chr . "%' 
                OR concat_ws(',', location.country, location.state, location.city1, location.city2) LIKE '%" . $chr . "%' )";
        }

        if (isset($day) && $day != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " DAY(u.date_added) = '" . $day . "' ";
        }

        if (isset($month) && $month != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " MONTH(u.date_added) = '" . $month . "' ";
        }

        if (isset($year) && $year != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " YEAR(u.date_added) = '" . $year . "' ";
        }

        if (isset($sort)) {
            $sorting = $sort . ' ' . $order;
        } else {
            $sorting = 'id DESC';
        }
        if($admSl['id']==2){
            //change user type 'o' to 'c' after testing
            if ($whereCond) {
                $whereCond .= " AND u.type = 'c' ";
            } else {
                $whereCond .= " WHERE u.type = 'c' ";
            }
            $query = "SELECT u.*,location.country, location.state, location.city1, location.city2 
                    FROM tbl_users u 
                    LEFT JOIN tbl_locations location ON location.id = u.location_id 
                    " . $whereCond . " ORDER BY " . $sorting;
        }else{
            $query = "SELECT u.*,location.country, location.state, location.city1, location.city2 
                    FROM tbl_users u 
                    LEFT JOIN tbl_locations location ON location.id = u.location_id 
                    " . $whereCond . " ORDER BY " . $sorting;
        }
        

        $query_with_limit = $query . " LIMIT " . $offset . " ," . $rows . " ";

        $totalUsers = $this->db->pdoQuery($query)->results();

        $qrySel = $this->db->pdoQuery($query_with_limit)->results();
        $totalRow = count($totalUsers);

        foreach ($qrySel as $fetchRes) {
            $status = ($fetchRes['status'] == "a") ? "checked" : "";

            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $fetchRes['id'] . "", "check" => $status)) : '';
            $operation = $profile_picture_name = $profile_picture_img = '';

            // $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $fetchRes['id'] . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => SITE_ADMIN_URL. "user-dashboard/" . $fetchRes['id'] . "/education", "class" => "btn default blue btn-xs ", "value" => '<i class="fa fa-laptop"></i>&nbsp;View main')) : '';
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $fetchRes['id'] . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';
            
            $firstName = (isset($fetchRes["first_name"]) && $fetchRes["first_name"] != '') ? $fetchRes["first_name"] : 'N/A';
            $lastName = (isset($fetchRes["last_name"]) && $fetchRes["last_name"] != '') ? $fetchRes["last_name"] : 'N/A';

            $email = (isset($fetchRes["email_address"]) && $fetchRes["email_address"] != '') ? $fetchRes["email_address"] : 'N/A';

            $countryName = filtering($fetchRes['country']);
            $stateName = filtering($fetchRes['state']);
            $cityName = filtering($fetchRes['city1']) != '' ? filtering($fetchRes['city1']) : filtering($fetchRes['city2']);

            $user_location = $countryName . ", " . $stateName. ", " . $cityName;
            if($countryName == '' && $stateName == '' && $cityName == ''){
                $user_location='-';
            }

            require_once(DIR_ADM_MOD . 'storage.php');
            $user_storage_datagrid = new storage();
            
            $user_profile = DIR_NAME_USERS."/".$fetchRes['id']."/";
            $get_profile_picture = $this->db->select("tbl_users", "*", array("id" => $fetchRes['id']))->result();
            if ($get_profile_picture) {
                $profile_picture_name = filtering($get_profile_picture['profile_picture_name']);
            }
            if ($profile_picture_name == '') {
                $profile_picture_img = '<span title="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($get_profile_picture['first_name'][0]) . '</span>';
            }else 
            {   
                $img_url = $user_storage_datagrid->getImageUrl1('av8db','th2_'.$profile_picture_name,$user_profile);
                $up = getimagesize($img_url);
                if (!empty($up)) {
                    $profile_picture_img ='<picture>
                                    <source srcset="' . $img_url . '" type="image/jpg">
                                    <img src="' . $img_url . '" class="" alt="img" /> 
                                </picture>';   
                }else{
                    $profile_picture_img = '<span title="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($get_profile_picture['first_name'][0]) . '</span>';
                }
            }

            $final_array = array(
                filtering($fetchRes['id'], 'output', 'int'),
                filtering($firstName),
                filtering($lastName),
                $profile_picture_img,
                //getUserHeadline(filtering($fetchRes['id'], 'output', 'int')) != false ? getUserHeadline(filtering($fetchRes['id'], 'output', 'int')) : "-",
                $user_location,
                filtering($email),
                convertDate('onlyDate', $fetchRes['date_added'])
            );

            $final_array = array_merge($final_array, array($switch));
            $final_array = array_merge($final_array, array($operation));
            //echo "<pre>";print_r($final_array);exit;
            $row_data[] = $final_array;
        }

        $result["sEcho"] = $sEcho;
        $result["iTotalRecords"] = (int) $totalRow;
        $result["iTotalDisplayRecords"] = (int) $totalRow;
        $result["aaData"] = $row_data;
        return $result;
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

    public function getSelectBoxOption() {
        $content = '';
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/select_option-nct.tpl.php");
        $content.= $main_content->parse();
        return sanitize_output($content);
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

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->breadcrumb = $this->getBreadcrumb();

        $main_content_parsed = $final_result = $main_content->parse();

        $fields = array(
            "%VIEW_ALL_RECORDS_BTN%"
        );

        $view_all_records_btn = '';
        if (( isset($_GET['day']) && $_GET['day'] != '' ) || ( isset($_GET['month']) && $_GET['month'] != '' ) || ( isset($_GET['year']) && $_GET['year'] != '' )) {
            $view_all_records_btn = $this->getViewAllBtn();
        }

        $fields_replace = array(
            $view_all_records_btn
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

}
