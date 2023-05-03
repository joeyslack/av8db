<?php

class Jobs extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_jobs';

        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $sql = "SELECT j.*, 
                    IF(added_by_admin = 'y', 'Admin', concat_ws(' ', u.first_name, u.last_name) ) as added_by, 
                    c.company_name, c.company_logo,
                    jc.job_category,
                    IF(employment_type = 'p', 'Part Time', IF(employment_type = 'f', 'Full Time', IF(employment_type = 'c', 'Contract', 'Temporary'))) as employment_type_text,
                    location.country, location.state, location.city1,location.city2
                    FROM " . $this->table . " j 
                    LEFT JOIN tbl_users u ON u.id = j.user_id 
                    LEFT JOIN tbl_companies c ON c.id = j.company_id 
                    LEFT JOIN tbl_job_category jc ON jc.id = j.job_category_id 
                    LEFT JOIN tbl_locations location ON location.id = j.location_id
                    WHERE j.id =  '" . $id . "' ";

            $jobDetails = $this->db->pdoQuery($sql)->result();
            //echo "<pre>";print_r($companyDetails);exit;

            $this->user_id = filtering($jobDetails['user_id'], 'input', 'int');

            $this->company_id = filtering($jobDetails['company_id'], 'input', 'int');

            $this->company_logo = filtering($jobDetails['company_logo'], 'input');
            $this->company_name = filtering($jobDetails['company_name']);

            $this->job_category_id = filtering($jobDetails['job_category_id'], 'input', 'int');
            $this->job_category = filtering($jobDetails['job_category']);

            $this->job_title = filtering($jobDetails['job_title']);
          
            $this->relavent_experience_from = filtering($jobDetails['relavent_experience_from'], 'output', 'float');
            $this->relavent_experience_to = filtering($jobDetails['relavent_experience_to'], 'output', 'float');

            $this->employment_type = filtering($jobDetails['employment_type']);
            $this->employment_type_text = filtering($jobDetails['employment_type_text']);

            $this->key_responsibilities = filtering($jobDetails['key_responsibilities'], 'input', 'text');
            $this->skills_and_exp = filtering($jobDetails['skills_and_exp'], 'input', 'text');

            $this->location_id = filtering($jobDetails['location_id'], 'input', 'int');
      
            $this->countryName = filtering($jobDetails['country']);
            $this->stateName = filtering($jobDetails['state']);
            $this->cityName = filtering($jobDetails['city1']) != '' ? filtering($jobDetails['city1']) : filtering($jobDetails['city2']);

            $this->location = $this->countryName . ", " . $this->stateName. ", " . $this->cityName;

            $this->last_date_of_application = convertDate('onlyDate', $jobDetails['last_date_of_application']);

            $this->added_by_admin = filtering($jobDetails['added_by_admin']);

            $this->status = filtering($jobDetails['status']);

            $this->added_on = convertDate('onlyDate', $jobDetails['added_on']);
            $this->updated_on = convertDate('onlyDate', $jobDetails['updated_on']);
        } else {
            $this->user_id = '';

            $this->company_id = '';
            $this->company_name = '';

            $this->job_category_id = '';
            $this->job_category = '';

            $this->job_title = '';
            
            $this->company_logo_url = '';

            $this->relavent_experience_from = '';
            $this->relavent_experience_to = '';

            $this->key_responsibilities = '';
            $this->skills_and_exp = '';

            $this->employment_type = '';
            $this->employment_type_text = '';

            $this->location_id = '';
            $this->countryName = '';

            $this->stateName = '';

            $this->cityName = '';
            $this->location = '';

            $this->last_date_of_application = '';
            $this->added_by_admin = 'y';
            $this->status = 'a';

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

        $src = $image = '';
        
        require_once(DIR_ADM_MOD . 'storage.php');
        $job_storage_view = new storage();
        
        $src2 = DIR_NAME_COMPANY_LOGOS."/";
      
        $src = $job_storage_view->getImageUrl1('av8db','th2_'.$this->company_logo,$src2);
        $ck = getimagesize($src);
        if (!empty($ck)) {
            $image = '<img src="'.$src.'" class="" id="" alt="'.$this->company_logo.'" width="100" height="44" title="'.$this->company_logo.'">';    
        }else{
            $image = '<img src="https://storage.googleapis.com/av8db/no-image.jpg" class="" id="" alt="'.$this->company_logo.'" width="100" height="44" title="'.$this->company_logo.'">';
        }

        /*if(file_exists(DIR_UPD_COMPANY_LOGOS . "th2_" .  $this->company_logo)){
            $src = SITE_UPD_COMPANY_LOGOS . "th2_" .  $this->company_logo;
        } else {
            $src = SITE_THEME_IMG . "no-image.jpg";
        }
        $image = $this->img(array(
            "src" => $src,
            "class" => "",
            "width" => "100",
            "height" => "44",
            "onlyField" => true,
            "title" => $this->company_logo,
            "alt" => $this->company_logo
        ));*/

        // $query = 'SELECT jskills.*,s.skill_name FROM tbl_job_skills jskills 
        //             LEFT JOIN tbl_skills s ON s.id = jskills.skill_id 
        //             WHERE s.status = "a" AND jskills.job_id = "'.$this->id.'"' ;

        // $skills_array = $this->db->pdoQuery($query)->results();

        // if($skills_array) {
        //     foreach ($skills_array as $key => $value) {
        //         $skills[] = $value['skill_name'];
        //     }
        //     $skills = implode(",", $skills);
        // } else {
        //     $skills = ' - ';
        // }

        $content = $this->displayBox(array("label" => "Job title &nbsp;:", "value" => $this->job_title)) .
                $this->displayBox(array("label" => "Business name &nbsp;:", "value" => $this->company_name)) .
                $this->displayBox(array("label" => "Business logo &nbsp;:", "value" => $image)) .
                $this->displayBox(array("label" => "Job category &nbsp;:", "value" => $this->job_category)) .
                
                //$this->displayBox(array("label" => "Experience &nbsp;:", "value" => $this->relavent_experience_from . "-" . $this->relavent_experience_to )) .
                $this->displayBox(array("label" => "Employment type &nbsp;:", "value" => $this->employment_type_text)) .
                $this->displayBox(array("label" => "Key Responsibilities &nbsp;:", "value" => $this->key_responsibilities)) .
                //$this->displayBox(array("label" => "Desired Skills and Experience &nbsp;:", "value" => $this->skills_and_exp)) .
                
                $this->displayBox(array("label" => "Country &nbsp;:", "value" => $this->countryName)) .
                $this->displayBox(array("label" => "State &nbsp;:", "value" => $this->stateName)) .
                $this->displayBox(array("label" => "City &nbsp;:", "value" => $this->cityName)) .

                $this->displayBox(array("label" => "Total no. of applicants &nbsp;:", "value" => $this->getNoOFApplicants($this->id))) .
                
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive')) .
                $this->displayBox(array("label" => "Added On&nbsp;:", "value" => convertDate('onlyDate', $this->added_on))).
                $this->displayBox(array("label" => "Last date of job application &nbsp;:", "value" => $this->last_date_of_application));
        return $content;
    }

    public function getCompanyDD($selected_company_id) {
        $final_result = $company_options = NULL;

        $getSelectBoxOption = $this->getSelectBoxOption();

        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        $companies = $this->db->pdoQuery("SELECT * FROM tbl_companies WHERE status='a' ORDER BY id DESC")->results();

        if ($companies) {
            foreach ($companies as $single_company) {
                $selected = ($selected_company_id == $single_company['id']) ? "selected" : "";

                $fields_replace = array(
                    filtering($single_company['id']),
                    $selected,
                    filtering($single_company['company_name'])
                );

                $company_options .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }

        $company_dd = new Templater(DIR_ADMIN_TMPL . $this->module . "/companies-dd-nct.tpl.php");
        $company_dd_parsed = $company_dd->parse();

        $fields_country = array("%COMPANY_OPTIONS%");
        $fields_country_replace = array($company_options);

        $final_result = str_replace($fields_country, $fields_country_replace, $company_dd_parsed);

        return $final_result;
    }
    
    public function getJobCategoryDD($selected_job_category_id) {
        $final_result = $job_category_options = NULL;

        $getSelectBoxOption = $this->getSelectBoxOption();

        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        $job_categories = $this->db->pdoQuery("SELECT * FROM tbl_job_category WHERE status='a' ORDER BY id DESC")->results();

        if ($job_categories) {
            foreach ($job_categories as $single_job_category) {
                $selected = ($selected_job_category_id == $single_job_category['id']) ? "selected" : "";

                $fields_replace = array(
                    filtering($single_job_category['id']),
                    $selected,
                    filtering($single_job_category['job_category'])
                );

                $job_category_options .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }

        $job_category_dd = new Templater(DIR_ADMIN_TMPL . $this->module . "/job-category-dd-nct.tpl.php");
        $job_category_dd_parsed = $job_category_dd->parse();

        $fields_country = array("%JOB_CATEGORY_OPTIONS%");
        $fields_country_replace = array($job_category_options);
        
        $final_result = str_replace($fields_country, $fields_country_replace, $job_category_dd_parsed);

        return $final_result;
    }

    public function getSkillsDD($selected_skills) {
        $final_result = NULL;

        $skils = $this->db->select("tbl_skills", "*", array("status" => "a"))->results();

        /*$skils = $this->db->pdoQuery("SELECT skills.* FROM tbl_skills skills INNER JOIN tbl_job_skills job_skills 
            ON skills.id = job_skills.skill_id  ")->results();*/

        //_print($skils); _print($selected_skills); exit;

        if ($skils) {
            $getSelectBoxOption = $this->getSelectBoxOption();

            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

            for ($i = 0; $i < count($skils); $i++) {
                
                $selected = in_array($skils[$i]['id'], $selected_skills) ? 'selected' : '';

                $fields_replace = array(
                    filtering($skils[$i]['id'], 'output', 'int'),
                    $selected,
                    filtering($skils[$i]['skill_name'], 'output')
                );

                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }

        return $final_result;
    }

    public function getForm() {

        $content = '';
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();

        $status_a = ($this->status == 'a' ? 'checked' : '');
        $status_d = ($this->status != 'a' ? 'checked' : '');

        $employment_type_p = ($this->employment_type == 'p' ? 'checked' : '');
        $employment_type_f = ($this->employment_type == 'f' ? 'checked' : '');
        $employment_type_c = ($this->employment_type == 'c' ? 'checked' : '');
        $employment_type_t = ($this->employment_type == 't' ? 'checked' : '');

        $job_skills_id = array();

        // $job_skills = $this->db->select("tbl_job_skills", "*", array("job_id" => $this->id))->results();
        // if ($job_skills) {
        //     foreach ($job_skills as $key => $value) {
        //        $job_skills_id[] = $value['skill_id'];
        //     }
        // }
        //$main_content->set('skills', $this->getSkillsDD($job_skills_id));

        $fields = array(
            "%MEND_SIGN%",
            "%COMPANY_DD%",
            "%JOB_CATEGORY_DD%",
            "%JOB_TITLE%",
            "%RELAVENT_EXPERIENCE_TO%",
            "%RELAVENT_EXPERIENCE_FROM%",
            "%EMPOYMENT_TYPE_P%",
            "%EMPOYMENT_TYPE_F%",
            "%EMPOYMENT_TYPE_C%",
            "%EMPOYMENT_TYPE_T%",
            "%KEY_RESPONSIBILITIES%",
            "%SKILLS_AND_EXP%",
            "%JOB_LOCATION%",
            "%LAST_DATE_OF_APPLICATION%",
            "%STATUS_A%",
            "%STATUS_D%",
            "%TYPE%",
            "%ID%",
            // "%SKILLS%"
        );

        
        $fields_replace = array(
            MEND_SIGN,
            $this->getCompanyDD($this->company_id),
            $this->getJobCategoryDD($this->job_category_id),
            $this->job_title,
            $this->relavent_experience_to,
            $this->relavent_experience_from,
            $employment_type_p,
            $employment_type_f,
            $employment_type_c,
            $employment_type_t,
            $this->key_responsibilities,
            $this->skills_and_exp,
            $this->location,
            $this->last_date_of_application,
            $status_a,
            $status_d,
            $this->type,
            $this->id,
            //$this->getSkillsDD($job_skills_id)
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
            $whereCond .= " WHERE 
                            job_title LIKE '%" . $chr . "%' OR 
                            IF(added_by_admin = 'y', 'Admin', concat_ws(' ', u.first_name, u.last_name) ) LIKE '%" . $chr . "%' OR 
                            company_name LIKE '%" . $chr . "%' OR 
                            job_category_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%' OR 
                            job_position LIKE '%" . $chr . "%' OR 
                            IF(employment_type = 'p', 'Part Time', 'Full Time') LIKE '%" . $chr . "%' OR 
                            DATE_FORMAT(last_date_of_application, '" . MYSQL_DATE_FORMAT . "') LIKE '%" . $chr . "%' OR 
                            concat_ws(',', location.country, location.state, location.city1, location.city2) LIKE '%" . $chr . "%' ";
        }
        
        if (isset($day) && $day != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " DAY(j.added_on) = '" . $day . "' ";
        }

        if (isset($month) && $month != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " MONTH(j.added_on) = '" . $month . "' ";
        }

        if (isset($year) && $year != '') {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " YEAR(j.added_on) = '" . $year . "' ";
        }

        if (isset($job_id) && $job_id > 0) {
            if ($whereCond) {
                $whereCond .= " AND ";
            } else {
                $whereCond .= " WHERE ";
            }
            $whereCond .= " (j.id) = '" . $job_id . "' ";
        }

        //echo $whereCond;exit;
        
        if (isset($sort))
            $sorting = $sort . ' ' . $order;
        else
            $sorting = 'id DESC';


        $sql = "SELECT j.*, 
                IF(added_by_admin = 'y', 'Admin', concat_ws(' ', u.first_name, u.last_name) ) as added_by, 
                c.company_name, 
                jc.job_category_".DEFAULT_LANGUAGE_ID.",
                location.country, location.state, location.city1, location.city2,
                IF(employment_type = 'p', 'Part Time', 'Full Time') as employment_type_text 
                FROM " . $this->table . " j 
                LEFT JOIN tbl_users u ON u.id = j.user_id 
                LEFT JOIN tbl_companies c ON c.id = j.company_id 
                LEFT JOIN tbl_job_category jc ON jc.id = j.job_category_id
                LEFT JOIN tbl_locations location ON location.id = j.location_id 
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
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';
            
            if($fetchRes['added_by_admin'] == 'y') {
                //$operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=viewApplicants&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View Applicants')) : '';
            }
            
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $id . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';

            $countryName = filtering($fetchRes['country']);
            $stateName = filtering($fetchRes['state']);
            $cityName = filtering($fetchRes['city1']) != '' ? filtering($fetchRes['city1']) : filtering($fetchRes['city2']);

            $job_location = $countryName . ", " . $stateName. ", " . $cityName;

            $final_array = array(
                filtering($fetchRes["id"], 'output', 'int'),
                filtering($fetchRes["job_title"]),
                filtering($fetchRes["job_category_".DEFAULT_LANGUAGE_ID]),
                filtering($job_location),
                filtering($fetchRes["is_featured"] == "y" ? "Yes" : "No"),
                filtering($fetchRes["company_name"]),
                convertDate('onlyDate', $fetchRes["last_date_of_application"])
            );

            if (in_array('status', $this->Permission)) {
                $final_array = array_merge($final_array, array($switch));
            }
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

        //_print($_GET);exit;

        $view_all_records_btn = '';
        if (( isset($_GET['day']) && $_GET['day'] != '' ) || ( isset($_GET['month']) && $_GET['month'] != '' ) || ( isset($_GET['year']) && $_GET['year'] != '' ) || ( isset($_GET['job_id']) && $_GET['job_id'] > 0 )) {
            $view_all_records_btn = $this->getViewAllBtn();
        }

        $fields_replace = array(
            $view_all_records_btn
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getNoOFApplicants($job_id) {
        return getTotalRows('tbl_job_applications', 'job_id = "'. $job_id .'"');
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
