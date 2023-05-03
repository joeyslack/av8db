<?php

class Companies extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_companies';

        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $sql = "SELECT c.*, concat_ws(' ', u.first_name, u.last_name) as user_name, ind.industry_name, count(j.id) as no_of_jobs 
                FROM " . $this->table . " c 
                LEFT JOIN tbl_users u ON u.id =  c.user_id 
                LEFT JOIN tbl_industries ind ON ind.id = c.company_industry_id
                LEFT JOIN tbl_jobs j ON j.company_id = c.id 
                WHERE c.id = '" . $id . "' ";

            $companyDetails = $this->db->pdoQuery($sql)->result();
            //echo "<pre>";print_r($companyDetails);exit;

            $this->com_id = filtering($companyDetails['id'], 'input', 'int');
            $this->user_id = filtering($companyDetails['user_id'], 'input', 'int');
            $this->user_name = filtering($companyDetails['user_name']);
            $this->company_name = filtering($companyDetails['company_name']);
            $this->company_logo = filtering($companyDetails['company_logo']);

            $this->owner_email_address = filtering($companyDetails['owner_email_address']);

            $this->company_description = filtering($companyDetails['company_description']);

            $this->company_industry_id = filtering($companyDetails['company_industry_id'], 'output', 'int');
            $this->industry_name = filtering($companyDetails['industry_name']);

           // $this->company_size_id = filtering($companyDetails['company_size_id'], 'output', 'int');
           // $this->company_size = filtering($companyDetails['company_size']);
            $this->foundation_year =  filtering($companyDetails['foundation_year']);
            //$this->services_provided = filtering($companyDetails['services_provided']);
            $this->website_of_company = filtering($companyDetails['website_of_company']);
            $this->company_type = filtering($companyDetails['company_type']);
            $this->status = filtering($companyDetails['status']);

            $this->added_on = convertDate('onlyDate', $companyDetails['added_on']);
            $this->updated_on = convertDate('onlyDate', $companyDetails['updated_on']);
        } else {
            $this->user_id = '';
            $this->user_name = '';
            $this->company_name = '';
            $this->company_logo = '';
            $this->owner_email_address = '';
            $this->company_description = '';

            $this->company_industry_id = '';
            $this->industry_name = '';

            $this->foundation_year = '';

            //$this->company_size_id = '';
            //$this->company_size = '';

            //$this->services_provided = '';
            $this->website_of_company = '';
            $this->company_type = '';
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
                    break;
                }
            case 'assignCompany' : {
                $this->data['content'] =  $this->assignCompanyForm();
                break;
            }
        }
    }

    public function viewForm() {

        $src = $user_img1 = $profile_picture_name = $profile_picture_img = $grp_profile_picture_name = $grp_profile_picture_img = '';

        require_once(DIR_ADM_MOD . 'storage.php');
        $company_storage_view = new storage();
        
        $src2 = DIR_NAME_COMPANY_LOGOS."/";
        $user_profile = DIR_NAME_USERS."/".$this->user_id."/";
      
        $src = $company_storage_view->getImageUrl1('av8db','th2_'.$this->company_logo,$src2);
        $ck = getimagesize($src);
        if (!empty($ck)) {
            $image = '<img src="'.$src.'" class="" id="" alt="'.$this->company_logo.'" width="100" height="44" title="'.$this->company_logo.'">';    
        }else{
            $image = '<img src="https://storage.googleapis.com/av8db/no-image.jpg" class="" id="" alt="'.$this->company_logo.'" width="100" height="44" title="'.$this->company_logo.'">';
        }

        $company_website = '<a href="'.$this->website_of_company.'" title="'.$this->company_name.'" target="_blank">'.$this->website_of_company.'</a>';
    
        $get_profile_picture = $this->db->select("tbl_users", "*", array("id" => $this->user_id))->result();
        if ($get_profile_picture) {
            $profile_picture_name = filtering($get_profile_picture['profile_picture_name']);
        }
        if ($profile_picture_name == '') {
            $profile_picture_img = '<span title="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($get_profile_picture['first_name'][0]) . '</span>';
        }else 
        {   
            $imgs = $company_storage_view->getImageUrl1('av8db','th2_'.$profile_picture_name,$user_profile);
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

        $content = $this->displayBox(array("label" => "Business name &nbsp;:", "value" => $this->company_name)) .
                $this->displayBox(array("label" => "Business logo &nbsp;:", "value" => $image)) .
                $this->displayBox(array("label" => "Business Email ID &nbsp;:", "value" => $this->owner_email_address)) .
                $this->displayBox(array("label" => "Business description &nbsp;:", "value" => $this->company_description)) .
                $this->displayBox(array("label" => "Industry of business &nbsp;:", "value" => $this->industry_name)) .
                //$this->displayBox(array("label" => "Size of company &nbsp;:", "value" => $this->company_size)) .
                $this->displayBox(array("label" => "Website of business &nbsp;:", "value" => $company_website )) .
                $this->displayBox(array("label" => "Foundation year &nbsp;:", "value" => $this->foundation_year )) .
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive')) .
                $this->displayBox(array("label" => "Added On&nbsp;:", "value" => convertDate('onlyDate', $this->added_on)));

        $content .= '<div class="title-container"><h4>User details </h4></div>';
        $content .=  $this->displayBox(array("label" => "user name &nbsp;:", "value" => $this->user_name)) .
                $this->displayBox(array("label" => "user image &nbsp;:", "value" => $profile_picture_img));


        $content .= '<div class="title-container"><h4>Admin details </h4></div>';
        $content .=  $this->displayBox(array("label" => "user name &nbsp;:", "value" => $this->user_name)) .
                $this->displayBox(array("label" => "user image &nbsp;:", "value" => $profile_picture_img));

        $query = 'SELECT location.country,location.state, location.city1, location.city2 
                    FROM tbl_company_locations cl 
                    LEFT JOIN tbl_locations location ON location.id = cl.location_id
                    WHERE cl.company_id = '. $this->id .'  ';
        $all_company_location = $this->db->pdoQuery($query)->results();

        if($all_company_location) {
            $content .= '<div class="title-container"><h4>Business location(s) </h4></div>';
            $count = 0;
            foreach ($all_company_location as $key => $value) {
                $count++;
                $content .=  $this->displayBox(array("label" => "Location $count &nbsp;:", "value" => $value['country'] . ", " . $value['state'] . ", " . $value['city1'] ));
            }
        }

        return $content;
    }

    public function assignCompanyForm(){
        $content = '';

        $content = $this->displayBox(array("label" => "Business Name &nbsp;:", "value" => $this->company_name)).
            $this->displayBox(array("label" => "Current User &nbsp;:", "value" => $this->user_name)).
            '<hr>'.'<b>Assign Business to another user</b>'.
            $this->getUsersList().
            $this->getAssignUserBtn();

        return $content;    
    }
    public function getUsersList(){
        $final_result = $users_option = NULL;

        $getSelectBoxOption = $this->getSelectBoxOption();

        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        $users = $this->db->pdoQuery("SELECT * FROM tbl_users WHERE status='a' AND email_verified = 'y' AND id != '".$this->user_id."'ORDER BY id DESC")->results();
        
        if (count($users) > 0) {
            foreach ($users as $user_data) {
                $selected = "";

                $fields_replace = array(
                    $user_data['id'],
                    $selected,
                    filtering($user_data['first_name'].' '.$user_data['last_name'])
                );
                //print_r($fields_replace);
                $users_option .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }

        $user_dd = new Templater(DIR_ADMIN_TMPL . $this->module . "/select_user-nct.tpl.php");
        $user_dd_parsed = $user_dd->parse();

        $fields_country = array("%USERS_OPTION%");
        $fields_country_replace = array($users_option);
        
        $final_result = str_replace($fields_country, $fields_country_replace, $user_dd_parsed);
        return $final_result;
    }

    public function getAssignUserBtn(){
        $final_result = '';
        $user_btn = new Templater(DIR_ADMIN_TMPL . $this->module . "/assign_company_btn-nct.tpl.php");
        $user_btn_parsed = $user_btn->parse();

        $fields_country = array("%BTN_NAME%","%COMPANY_ID%","%CURRENT_USER%");
        $fields_country_replace = array('Assign Business',$this->com_id,$this->user_id);

        $final_result = str_replace($fields_country, $fields_country_replace, $user_btn_parsed);

        return $final_result;
    }

    public function getCurrentLogoOfCompany() {
        $final_result = NULL;

        return $final_result;
    }

    public function getIndustryDD($selected_industry_id) {
        $final_result = $industry_option = NULL;

        $getSelectBoxOption = $this->getSelectBoxOption();

        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        $industries = $this->db->pdoQuery("SELECT * FROM tbl_industries WHERE status='a' ORDER BY id DESC")->results();

        if ($industries) {
            foreach ($industries as $single_industry) {
                $selected = ($selected_industry_id == $single_industry['id']) ? "selected" : "";

                $fields_replace = array(
                    filtering($single_industry['id']),
                    $selected,
                    filtering($single_industry['industry_name'])
                );

                $industry_option .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }

        $industry_dd = new Templater(DIR_ADMIN_TMPL . $this->module . "/industry-dd-nct.tpl.php");
        $industry_dd_parsed = $industry_dd->parse();

        $fields_country = array("%INDUSTRY_OPTIONS%");
        $fields_country_replace = array($industry_option);

        $final_result = str_replace($fields_country, $fields_country_replace, $industry_dd_parsed);

        return $final_result;
    }
    public function getForm() {
        $content = '';
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();

        $status_a = ($this->status == 'a' ? 'checked' : '');
        $status_d = ($this->status != 'a' ? 'checked' : '');

        $image = $src = '';
        
        require_once(DIR_ADM_MOD . 'storage.php');
        $company_getfrm_storage_edit = new storage();
        
        $src2 = DIR_NAME_COMPANY_LOGOS."/";

        $src = $company_getfrm_storage_edit->getImageUrl1('av8db','th2_'.$this->company_logo,$src2);
        $ck = getimagesize($src);
        if (!empty($ck)) {
            $image = $src;    
        }else{
            $image = 'https://storage.googleapis.com/av8db/no-image.jpg';
        }

        /*if(file_exists(DIR_UPD_COMPANY_LOGOS . "th2_" .  $this->company_logo)){
            $image = SITE_UPD_COMPANY_LOGOS . "th2_" .  $this->company_logo;
        } else {
            $image = SITE_THEME_IMG . "no-image.jpg";
        }*/

        $fields = array(
            "%MEND_SIGN%",
            "%COMPANY_NAME%",
            "%CURRENT_LOGO%",
            "%IMAGE%",
            "%COMPANY_DESCRIPTION%",
            "%COMPANY_INDUSTRY_DD%",
            //"%COMPANY_SIZE_DD%",
            "%WEBSITE_OF_COMPANY%",
            "%OWNER_EMAIL_ADDRESS%",
            "%FOUNDATION_YEAR%",
            "%COMPANY_LOCATIONS%",
            "%NO_OF_LOCATIONS%",
            "%STATUS_A%",
            "%STATUS_D%",
            "%TYPE%",
            "%ID%"
        );

        $query = 'SELECT COUNT(company_id) as no_of_locations
                    FROM tbl_company_locations cl 
                    WHERE cl.company_id = '. $this->id .'  ';
        $locations = $this->db->pdoQuery($query)->result();

        $fields_replace = array(
            MEND_SIGN,
            $this->company_name,
            $this->getCurrentLogoOfCompany(),
            $image,
            $this->company_description,
            $this->getIndustryDD($this->company_industry_id),
            //$this->getCompanySizeDD($this->company_size_id),
            $this->website_of_company,
            $this->owner_email_address,
            $this->foundation_year > 0  ?  $this->foundation_year : '',
            $this->getCompanyLocations($this->id),
            $locations['no_of_locations'],
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

        $whereCond = 'WHERE c.company_type = "r"';
        if (isset($chr) && $chr != '') {
            $whereCond .= " AND ( concat_ws(' ', u.first_name, u.last_name) LIKE '%" . $chr . "%' OR company_name LIKE '%" . $chr . "%' OR industry_name LIKE '%" . $chr . "%' OR owner_email_address LIKE '%" . $chr . "%' ) OR DATE_FORMAT(c.added_on, '" . MYSQL_DATE_FORMAT . "') LIKE '%" . $chr . "%'
            OR concat_ws(',', location.country, location.state, location.city1, location.city2) LIKE '%" . $chr . "%' ";
        }
        
        if (isset($day) && $day != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " DAY(c.added_on) = '" . $day . "' ";
        }

        if (isset($month) && $month != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " MONTH(c.added_on) = '" . $month . "' ";
        }

        if (isset($year) && $year != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " YEAR(c.added_on) = '" . $year . "' ";
        }

         if (isset($company_id) && $company_id != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " (c.id) = '" . $company_id . "' ";
        }
        
        if (isset($sort))
            $sorting = $sort . ' ' . $order;
        else
            $sorting = 'id DESC';


        $sql = "SELECT c.*, concat_ws(' ', u.first_name, u.last_name) as user_name, 
                ind.industry_name, count(j.id) as no_of_jobs,
                location.country, location.state, location.city1, location.city2 
                FROM " . $this->table . " c 
                LEFT JOIN tbl_users u ON u.id =  c.user_id 
                LEFT JOIN tbl_industries ind ON ind.id = c.company_industry_id 
                LEFT JOIN tbl_jobs j ON j.company_id = c.id 
                LEFT JOIN tbl_company_locations cl ON cl.company_id = c.id 
                LEFT JOIN tbl_locations location ON cl.location_id = location.id 
                " . $whereCond . " GROUP BY c.id ORDER BY " . $sorting;
        $sql_with_limit = $sql . " LIMIT " . $offset . " ," . $rows . " ";

        $getTotalRows = $this->db->pdoQuery($sql)->results();
        $totalRow = count($getTotalRows);

        $qrySel = $this->db->pdoQuery($sql_with_limit)->results();

        foreach ($qrySel as $fetchRes) {
            $id = $fetchRes['id'];
            $status = $fetchRes['status'];

            $status = ($fetchRes['status'] == "a") ? "checked" : "";

            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status)) : '';
            $operation = $image = '';

            $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $id . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';
            $operation .= (in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=assignCompany&id=" . $fetchRes['id'] . "", "class" => "btn default green btn-xs btn-viewbtn","extraAtt" => 'data-page_title="Assign business to another user"', "value" => '<i class="fa fa-calendar"></i>&nbsp;Assign business to another user')) : '';

            require_once(DIR_ADM_MOD . 'storage.php');
            $company_storage = new storage();
            $src2 = DIR_NAME_COMPANY_LOGOS."/";
            $company_logo = $company_storage->getImageUrl1('av8db','th2_'.$fetchRes["company_logo"],$src2);
            $ck = getimagesize($company_logo);
            if (!empty($ck)) {
                $image = '<img src="'.$company_logo.'" class="" id="" alt="'.$this->company_logo.'" width="100" height="44" title="'.$this->company_logo.'">';    
            }else{
                $image = '<img src="https://storage.googleapis.com/av8db/no-image.jpg" class="" id="" alt="'.$this->company_logo.'" width="100" height="44" title="'.$this->company_logo.'">';
            }

           /* if(file_exists(DIR_UPD_COMPANY_LOGOS . "th2_" .  $fetchRes["company_logo"])){
                $company_logo = SITE_UPD_COMPANY_LOGOS . "th2_" .  $fetchRes["company_logo"];
            } else {
                $company_logo = SITE_THEME_IMG . "no-image.jpg";
            }

            $image = $this->img(array(
                "src" => $company_logo,
                "class" => "",
                "width" => "100",
                "height" => "44",
                "onlyField" => true,
                "title" => $this->company_logo,
                "alt" => $this->company_logo
            ));*/

            $countryName = filtering($fetchRes['country']);
            $stateName = filtering($fetchRes['state']);
            $cityName = filtering($fetchRes['city1']) != '' ? filtering($fetchRes['city1']) : filtering($fetchRes['city2']);

            $company_location = $countryName . ", " . $stateName. ", " . $cityName;

            $final_array = array(
                filtering($fetchRes["id"], 'output', 'int'),
                filtering($fetchRes["company_name"]),
                $image,
                filtering($fetchRes["owner_email_address"]),
                $company_location,
                filtering($fetchRes["industry_name"]),
                filtering($fetchRes["no_of_jobs"], 'input', 'int'),
                $fetchRes["added_on"] != '0000-00-00 00:00:00' ? convertDate('onlyDate', $fetchRes["added_on"]) : '-',
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
        if (( isset($_GET['day']) && $_GET['day'] != '' ) || ( isset($_GET['month']) && $_GET['month'] != '' ) || ( isset($_GET['year']) && $_GET['year'] != '' ) || ( isset($_GET['company_id']) && $_GET['company_id'] > 0 )) {
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

    public function getCompanyLocations($company_id) {
        $final_result = NULL;

        $getCompanyLocations = $this->db->select("tbl_company_locations", "*", array("company_id" => $company_id))->results();

        if ($getCompanyLocations) {

            for ($i = 0; $i < count($getCompanyLocations); $i++) {
                $company_location_id = filtering($getCompanyLocations[$i]['id'], 'input', 'int');
                $location_id = filtering($getCompanyLocations[$i]['location_id'], 'input', 'int');

                $response = $this->generateCompanyLocationBox($company_location_id, $location_id);

                $final_result .= $response['content'];
            }
        }

        return $final_result;
    }

    public function generateCompanyLocationBox($company_location_id = '', $location_id = '') {
        $final_result = '';
        $response = array();
        $response['status'] = false;

        if ($company_location_id) {
            $cl_id = encryptIt($company_location_id);

            $query = "SELECT cl.is_hq, l.* 
                        FROM tbl_company_locations cl 
                        LEFT JOIN tbl_locations l ON cl.location_id = l.id 
                        WHERE cl.id = '" . $company_location_id . "' ";

            $getCLDetails = $this->db->pdoQuery($query)->result();
        } else {
            $cl_id = '';
            $getCLDetails = $_POST;
        }

        $formatted_address = filtering($getCLDetails['formatted_address']);
        $address1 = filtering($getCLDetails['address1']);
        $address2 = filtering($getCLDetails['address2']);
        $country = filtering($getCLDetails['country']);
        $state = filtering($getCLDetails['state']);
        $city1 = filtering($getCLDetails['city1']);
        $city2 = filtering($getCLDetails['city2']);
        $postal_code = filtering($getCLDetails['postal_code']);

        $latitude = filtering($getCLDetails['latitude'], 'output', 'float');
        $longitude = filtering($getCLDetails['longitude'], 'output', 'float');

        $is_hq = filtering($getCLDetails['is_hq']);
        if ('y' == $is_hq) {
            $hq_class = "is-hq";
        } else {
            $hq_class = "make-hq";
        }

        $company_location_single_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . "/company-location-single-nct.tpl.php");
        $company_location_single_tpl_parsed = $company_location_single_tpl->parse();

        $fields = array(
            "%UNIQUE_IDENTIFIER%",
            "%FORMATTED_ADDRESS%",
            "%ADDRESS1%",
            "%ADDRESS2%",
            "%COUNTRY%",
            "%STATE%",
            "%CITY1%",
            "%CITY2%",
            "%POSTAL_CODE%",
            "%LATITUDE%",
            "%LONGITUDE%",
            "%IS_HQ%",
            "%CL_ID%",
            "%HQ_CLASS%"
        );

        $fields_replace = array(
            time(),
            $formatted_address,
            $address1,
            $address2,
            $country,
            $state,
            $city1,
            $city2,
            $postal_code,
            $latitude,
            $longitude,
            $is_hq,
            $cl_id,
            $hq_class
        );

        $final_result = str_replace($fields, $fields_replace, $company_location_single_tpl_parsed);

        $response['status'] = true;
        $response['content'] = $final_result;

        return $response;
    }
}