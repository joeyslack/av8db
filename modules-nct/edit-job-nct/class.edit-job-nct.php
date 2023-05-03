<?php

class Edit_job extends Home {

    function __construct($job_id = '',$plaform='web') {
        $this->job_id = $job_id;
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->plaform = $plaform;


        if($this->job_id > 0) {
            $query = "SELECT jobs.last_date_of_application,jobs.job_title,jobs.company_id,jobs.is_featured,jobs.employment_type,jobs.key_responsibilities,jobs.job_category_id,jobs.relavent_experience_from,jobs.apply_flag,jobs.apply_email,jobs.apply_url,jobs.featured_till,jobs.licenses_endorsement_id,comp.company_logo,comp.company_name,comp.company_description, 
                        jcate.job_category_".$this->lId." as job_category, l.country,l.state,l.city1,l.city2    
                        FROM tbl_jobs jobs 
                        LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id 
                        LEFT JOIN tbl_job_category jcate ON jobs.job_category_id = jcate.id
                        LEFT JOIN tbl_locations l ON jobs.location_id = l.id  
                        WHERE jobs.id = ? ";

            $job_details_array = $this->db->pdoQuery($query,array($this->job_id))->result();

            //_print($job_details_array);exit;

            $this->company_name = filtering($job_details_array['company_name'], 'output');

            $city = $job_details_array['city1'] != '' ? $job_details_array['city1'] : $job_details_array['city2'];
            $state = $job_details_array['state'];
            $country = $job_details_array['country'];
            
            //$this->location = $city . ", " . $state . ", " . $country;

            $this->location = $city;
            $this->location .= (($state != '' && $this->location != '')?', ':'').$state;
            $this->location .= (($country != '' && $this->location != '')?', ':'').$country;


            $this->job_category = filtering($job_details_array['job_category'], 'output');
            $this->job_title = filtering($job_details_array['job_title'], 'output');
            $this->is_featured = filtering($job_details_array['is_featured'], 'output');
            $this->licenses_endorsement_id = isset($job_details_array['licenses_endorsement_id']) ? $job_details_array['licenses_endorsement_id'] : '0';
            // print_r($this->licenses_endorsement_id);exit;
            //$this->company_logo_url = SITE_URL . "company/" . filtering($job_details_array['company_logo']);

            require_once(DIR_MOD . 'common_storage.php');
            $edit_job_storage = new storage();

            $logo_url = DIR_NAME_COMPANY_LOGOS."/";

            $company_logo_name = getTableValue("tbl_companies", "company_logo", array("id" => $job_details_array['company_id']));
            $company_names = getTableValue("tbl_companies", "company_name", array("id" => $job_details_array['company_id']));
            $company_logos_url = '';
            $src = $edit_job_storage->getImageUrl1('av8db','th2_'.$company_logo_name,$logo_url);
            $ck = getimagesize($src);
            if (empty($ck)) {
                $company_logos_url = '<span title="' . $company_names.'" class="profile-picture-character">' . ucfirst($company_names[0]) . '</span>';
            }else 
            {   
                $company_logos_url ='<picture>
                                        <source srcset="' . $src . '" type="image/jpg">
                                        <img src="' . $src . '" class="" alt="img" /> 
                                    </picture>';
            }
            // $this->company_logo_url = getImageURL("company_logo", $job_details_array['company_id'], "th2");
            $this->company_logo_url = $company_logos_url;

            $this->company_description = filtering($job_details_array['company_description'], 'output', 'text');

            $this->employment_type = filtering($job_details_array['employment_type'], 'output');

            $this->employment_type_f_cheked = $this->employment_type_p_cheked = $this->employment_type_c_cheked= $this->employment_type_t_cheked = ''; 

            if($this->employment_type == 'f') {
                $this->employment_type_f_cheked = 'checked';
            } else if($this->employment_type == 'p') {
                $this->employment_type_p_cheked = 'checked';
            } else if($this->employment_type == 'c') {
                $this->employment_type_c_cheked = 'checked';
            } else if($this->employment_type == 't') {
                $this->employment_type_t_cheked = 'checked';
            }

           // $this->job_skills = $this->db->select("tbl_job_skills", array('skill_id'), array("job_id" => $this->job_id))->results();

            $this->job_degrees = $this->db->select("tbl_job_education", array('degree_id'), array("job_id" => $this->job_id))->results();

            $this->job_responsibility = filtering($job_details_array['key_responsibilities'], 'output', 'text');

            //$this->skills_and_exp = filtering($job_details_array['skills_and_exp'], 'output', 'text');

            $this->job_category_id = filtering($job_details_array['job_category_id'], 'output', 'int');

            $this->relavent_experience_from = filtering($job_details_array['relavent_experience_from'], 'output');
            //$this->relavent_experience_to = filtering($job_details_array['relavent_experience_to'], 'output');
            $this->last_date_of_application=filtering($job_details_array['last_date_of_application'],'output');

            $this->apply_flag = filtering($job_details_array['apply_flag'], 'output');
            $this->apply_email = filtering($job_details_array['apply_email'], 'output');
            $this->apply_url = filtering($job_details_array['apply_url'], 'output');

            $this->apply_email_r = $this->apply_url_nr = '';
            $this->apply_flag_r_checked = $this->apply_flag_nr_checked = '';

            $email = getTableValue("tbl_users", "email_address", array("id" => $this->session_user_id));
            if($this->apply_flag == 'r') {
                $this->apply_email_r = $this->apply_email!=''?$this->apply_email:$email;             
                $this->apply_flag_r_checked = 'checked';
            } else {
                $this->apply_url_nr = $this->apply_url;
                $this->apply_email_r = $email;
                $this->apply_flag_nr_checked = 'checked';
            }
            $this->featured_till = $job_details_array['featured_till'];
            
        }

        
    }

    public function getTariffPlans($plan_id=0) {
        $content = '';

        /*$plans_query = $this->db->pdoQuery("SELECT s.plan_id 
        from tbl_payment_history as h
        inner join tbl_subscription_history as s on (h.subscription_id = s.id and s.plan_type='fj')
        where h.user_id = 180 and h.job_id = ?",array($this->job_id));
        $total_plan = $plans_query->affectedRows();
        $plans = $plans_query->result();*/
        //_print_r($plans);
        $tariff_plans = array();
        $tariff_plans_content = new Templater(DIR_TMPL . $this->module . "/plan-duration-nct.tpl.php");
        $tariff_plans_content_parsed = $tariff_plans_content->parse();
        $tariff_plans_details = $this->db->select("tbl_tariff_plans", array('id, plan_duration_unit, plan_duration, price'), array("plan_type" => "fj", "status" => "a"))->results();
        if ($tariff_plans_details) {
            $fields = array("%NAME%","%ID%","%VALUE%","%TEXT%","%CHECKED%");
            foreach ($tariff_plans_details as $key => $value) {
               $plan_duration_unit = $value['plan_duration_unit'] == 'w' ? 'Week' : 'Month';
               /*
               if($plan_id > 0 && $value['id'] == $plan_id) {
                    $checked = 'checked';
               }*/
               $checked = '';
               if($this->last_date_of_application >= date('Y-m-d H:i:s') && $this->featured_till >= date('Y-m-d H:i:s')){
                 $checked = $plan_id > 0 ? (($value['id'] == $plan_id) ? 'checked' : 'disabled') : 'checked';
               }
              
               $fields_replace = array(
                "tariff_plan",
                "tariff_plan_".$value['id'],
                encryptIt($value['id']),
                $value['plan_duration'] . " " . $plan_duration_unit . " - " . CURRENCY_SYMBOL . $value['price'],
                $checked,
                );
               $content .= str_replace($fields, $fields_replace, $tariff_plans_content_parsed);
            }
        }
        return $content;
    }

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->set('category_option', $this->getCategoriesDD());
        $main_content->set('category_selected', $this->job_category_id);
        $job_skills_id = array();
        
        // if ($this->job_skills) {
        //     foreach ($this->job_skills as $key => $value) {
        //        $job_skills_id[] = $value['skill_id'];
        //     }
        // }

        // if ($this->licenses_endorsement_id) {
        //     foreach ($this->job_skills as $key => $value) {
        //        $job_skills_id[] = $value['skill_id'];
        //     }
        // }
        $main_content->set('skills', $this->getSkillsDD($job_skills_id));
       // $main_content->set('licenses_endorsements', $this->getLicensesEndorsements($this->licenses_endorsement_id));

        $job_degrees_id = array();
        if ($this->job_degrees) {
            foreach ($this->job_degrees as $key => $value) {
               $job_degrees_id[] = $value['degree_id'];
            }
        }
        $main_content->set('degress', $this->getDegreeDD($job_degrees_id));
        $getCurrentPlan = getTableValue("tbl_payment_history","plan_id",array("job_id" => $this->job_id)); 

        $main_content->set('tariff_plan', $this->getTariffPlans($getCurrentPlan));

        $job_detail = $this->db->select("tbl_jobs", array('is_featured,featured_till','last_date_of_application'), array("id" => $this->job_id))->result();
        $content = $featured_checked ='';
        $display_tariff='';
        if($job_detail['is_featured'] == 'y' && $job_detail['featured_till'] > date('Y-m-d H:i:s')) {
            $featured_text = new Templater(DIR_TMPL . $this->module . "/featured-job-text-nct.tpl.php");
            $featured_text_parsed = $featured_text->parse();
            $fields = array("%DATE%","%REMAINING_DAYS%");
            $fields_replace= array($job_detail['featured_till'] , countRemainingDays($job_detail['featured_till']));
            $content .= str_replace($fields, $fields_replace, $featured_text_parsed);
            if($this->last_date_of_application >= date('Y-m-d H:i:s') || $this->featured_till >= date('Y-m-d H:i:s')){
                            $featured_checked = 'disabled checked';

            }
            $display_tariff = 'display:none';
        }

        $hide = (($job_detail['last_date_of_application'] > date('Y-m-d H:i:s')) || ($job_detail['featured_till'] > date('Y-m-d H:i:s')) )? 'hide' : '';

        $main_content->set('featured_text', $content);
        $main_content->set('featured_checked', $featured_checked);

        $main_content_parsed = $main_content->parse();

       $fields = array(
            "%JOB_ID%",
            "%ENCRYPTED_JOB_ID%",
            "%COMPANY_NAME%",
            "%LOCATION%",
            "%JOB_CATEGORY%",
            "%JOB_TITLE%",
            "%COMPANY_LOGO_URL%",
            "%COMPANY_DESC%",
            "%EMPL_TYPE_F_CHECKED%",
            "%EMPL_TYPE_P_CHECKED%",
            "%EMPL_TYPE_C_CHECKED%",
            "%EMPL_TYPE_T_CHECKED%",
            "%RESPONSIBILITY%",
            //"%SKILLS_AND_EXP%",
            "%EXP_FROM%",
            //"%EXP_TO%",
            "%APPLY_EMAIL_R%",
            "%APPLY_URL_NR%",
            "%APPLY_FLAG_R_CHECKED%",
            "%APPLY_FLAG_NR_CHECKED%",
            "%DISPLAY_TARIFF%",
            "%hide%",
            "%LASTDATE%",
            "%HIDE_CLASS_FEATURED%",
            "%LICENSES_ENDORSEMENTS_OPTIONS%",
            "%GET_ADDED_LICENSE_LIST%",
            "%GET_ADDED_LICENSE_VALUES%"
        );
       $company_logo_url = $this->company_logo_url;
       // $company_logo_url = ($this->company_logo_url == '') ? '<span class="company-letter-square company-letter">'.ucfirst($this->company_name[0]).'</span>' : $this->company_logo_url;
       $hide_class_features='';
       if($this->last_date_of_application < date('Y-m-d')){
            $hide_class_features='hidden';
       }
       $fields_replace = array(
            $this->job_id,
            encryptIt($this->job_id),
            $this->company_name,
            $this->location,
            $this->job_category,
            $this->job_title,
            $company_logo_url,
            $this->company_description,
            $this->employment_type_f_cheked,
            $this->employment_type_p_cheked,
            $this->employment_type_c_cheked,
            $this->employment_type_t_cheked,
            $this->job_responsibility,
            //$this->skills_and_exp,
            $this->relavent_experience_from,
            //$this->relavent_experience_to,
            $this->apply_email_r,
            $this->apply_url_nr,
            $this->apply_flag_r_checked,
            $this->apply_flag_nr_checked,
            $display_tariff,
            $hide,
            date(' M d, Y',strtotime($this->last_date_of_application)),
            $hide_class_features,
            $this->getLicensesEndorsements(),
            $this->getLicensesEndorsementsList(),
            $this->getAddedLicenseValues()
        );
       // _print_r($fields_replace);exit;
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }

    public function getCategoriesDD() {
        $final_result = array();
        $job_category = $this->db->select("tbl_job_category", array('id,job_category_'.$this->lId.' as job_category'), array("status" => "a"))->results();
        if ($job_category) {
            for ($i = 0; $i < count($job_category); $i++) {
                $final_result[$job_category[$i]['id']] = $job_category[$i]['job_category'];
            }
        }
        return $final_result;
    }

    public function getSkillsDD($selected_skills) {
        $final_result = NULL;
        $skils = $this->db->select("tbl_skills", array('id,skill_name_'.$this->lId.' as skill_name'), array("status" => "a"))->results();
        /*$skils = $this->db->pdoQuery("SELECT skills.* FROM tbl_skills skills INNER JOIN tbl_job_skills job_skills 
            ON skills.id = job_skills.skill_id  ")->results();*/
        //_print($skils); _print($selected_skills); exit;
        if ($skils) {
            $getSelectBoxOption = $this->getSelectBoxOption();

            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

            for ($i = 0; $i < count($skils); $i++) {
               // if(in_array($skils[$i]['id'], $selected_skills)){
                    $selected = in_array($skils[$i]['id'], $selected_skills) ? 'selected' : '';

                    $fields_replace = array(
                        filtering($skils[$i]['id'], 'output', 'int'),
                        $selected,
                        filtering($skils[$i]['skill_name'], 'output')
                    );

                    $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
               // }
            }
        }

        return $final_result;
    } 

    public function getDegreeDD($selected_degree) {
        $final_result = NULL;

        $degrees = $this->db->select("tbl_degrees", array('id,degree_name_'.$this->lId.' as degree_name' ), array("status" => "a"))->results();
        

        /*$skils = $this->db->pdoQuery("SELECT skills.* FROM tbl_skills skills INNER JOIN tbl_job_skills job_skills 
            ON skills.id = job_skills.skill_id  ")->results();*/
        //_print($skils); _print($selected_skills); exit;

        if ($degrees) {
            $getSelectBoxOption = $this->getSelectBoxOption();

            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

            for ($i = 0; $i < count($degrees); $i++) {
                if(in_array($degrees[$i]['id'], $selected_degree)){
                    $selected = in_array($degrees[$i]['id'], $selected_degree) ? 'selected' : '';
                    $fields_replace = array(
                        filtering($degrees[$i]['id'], 'output', 'int'),
                        $selected,
                        filtering($degrees[$i]['degree_name'], 'output')
                    );
                    $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
                }
            }
        }

        return $final_result;
    }

    public function getSkillsForSuggestion($user_id, $skill_name,$skill_id) {
        $final_result = array();

        if($skill_id == "" || $skill_id == "null")
            $skill_id ='0';

        $query = "SELECT id,skill_name_".$this->lId." as skill_name FROM tbl_skills 
                    WHERE skill_name_".$this->lId." LIKE '%" . $skill_name . "%' AND id NOT IN(".$skill_id.") AND status = ? 
                    ORDER BY id DESC  ";

        if($this->plaform=='web')
            $query .= " LIMIT 0, 10 ";
      
        $get_skills = $this->db->pdoQuery($query,array('a'))->results();
        if ($get_skills) {
            for ($i = 0; $i < count($get_skills); $i++) {
                $single_skill = array();
                $single_skill['skill_id'] = encryptIt(filtering($get_skills[$i]['id'], 'output', 'int'));
                $single_skill['skill_name'] = filtering($get_skills[$i]['skill_name']);
                $single_skill['skill_id_orig'] = (filtering($get_skills[$i]['id'], 'output', 'int'));
                $final_result[] = $single_skill;
            }
        }
        //_print($final_result);exit;
        return $final_result;
    }

    public function getDegreesForSuggestion($user_id, $degree_name,$degree_id) {
        $final_result = array();
        if($degree_id == "" || $degree_id == "null")
            $degree_id ='0';

        $query = "SELECT id,degree_name_".$this->lId." as degree_name FROM tbl_degrees WHERE degree_name_".$this->lId." LIKE '%" . $degree_name . "%' AND id NOT IN(".$degree_id.") AND status = ? ORDER BY id DESC ";

        if($this->plaform=='web')
            $query .= " LIMIT 0, 10 ";

        $get_degree = $this->db->pdoQuery($query,array('a'))->results();
        
        if ($get_degree) {
            for ($i = 0; $i < count($get_degree); $i++) {
                $single_degree = array();
                $single_degree['degree_id'] = encryptIt(filtering($get_degree[$i]['id'], 'output', 'int'));
                $app_degree['degree_id'] = $single_degree['degree_id_orig'] = (filtering($get_degree[$i]['id'], 'output', 'int'));
                $app_degree['degree_name']=$single_degree['degree_name']=filtering($get_degree[$i]['degree_name']);
                if($this->plaform=='app'){
                    $final_result[] = $app_degree;
                } else {
                    $final_result[] = $single_degree;
                }
            }
        }
        return $final_result;
    }

    public function processJobUpdation($user_id) {
        
        $response = array();
        $response['status'] = false;

        $employment_type = filtering($_POST['employment_type'], 'input');
        //$job_skills = filtering($_POST['job_skills'], 'input', 'text');
        //$job_education = filtering($_POST['job_education'], 'input', 'text');
        $relavent_experience_from = filtering($_POST['relavent_experience_from'], 'input');
        //$relavent_experience_to = filtering($_POST['relavent_experience_to'], 'input');
        $key_responsibilities = filtering($_POST['key_responsibilities'], 'input', 'text');
        //$skills_and_exp = filtering($_POST['skills_and_exp'], 'input', 'text');
        //$licenses_endorsement = isset($_POST['licenses_endorsement']) ? $_POST['licenses_endorsement'] : '0';
        //$licenses_endorsement = implode(',', $_POST['licenses_endorsement']);
        
        $apply_flag = filtering($_POST['apply_flag'], 'input');
        $email_recommended = filtering($_POST['email_recommended'], 'input');
        $url_not_recommended = filtering($_POST['url_not_recommended'], 'input');

        $is_featured = isset($_POST['is_featured']) ? filtering($_POST['is_featured'], 'input') : 'n';

        $tariff_plan = isset($_POST['tariff_plan']) ? filtering($_POST['tariff_plan'], 'input') : '';

         if ($employment_type == '' || !in_array($employment_type, array('f','p','c','t'))) {
            $response['error'] = LBL_PLEASE_SELECT_EMPLOYMENT_TYPE;
            return $response;
        }

        if ($key_responsibilities == '') {
            $response['error'] = LBL_ENETR_KEY_RESPONSIBILITY;
            return $response;
        }

        $apply_email = $apply_url = '';
        if($apply_flag == 'r') {
            $apply_email = $email_recommended;
        } else {
            $apply_url = $url_not_recommended;
        }
        if($this->plaform == 'app'){
            $job_id = filtering($_POST['job_id'], 'input', 'int');
        } else {
            $job_id = decryptIt(filtering($_POST['job_id'], 'input', 'int'));
        }
        
        $string = trim($_POST['selected_value_array'], ",");
        $s = str_replace('l_','',$string);
        $s1 = array_filter(array_unique(explode(',',$s)));
        $comma_separated = implode(",", $s1);
        $final_arr = explode(',',$comma_separated);
        
        $result=array();
        foreach($final_arr as $key=>$value ){
          $val=$_POST['license_hours'][$key];
          $result[$key]=array($value,$val);
        }
        $checkIfResExists = $this->db->select("tbl_job_license_hours", "id", array("job_id" => $job_id))->result();
        if($checkIfResExists['id'] > 0){
            $this->db->delete("tbl_job_license_hours", array("job_id" => $job_id));
            for ($i=0; $i < sizeof($result); $i++) { 
                $this->db->insert('tbl_job_license_hours', array('user_id' => $this->session_user_id, 'job_id' => $job_id,'license_ids' => $result[$i][0],'license_hours' =>$result[$i][1] ,'createdAt' => date('Y-m-d H:i:s'))); 
            }
        }else{
            for ($i=0; $i < sizeof($result); $i++) { 
                $this->db->insert('tbl_job_license_hours', array('user_id' => $this->session_user_id, 'job_id' => $job_id,'license_ids' => $result[$i][0],'license_hours' =>$result[$i][1] ,'createdAt' => date('Y-m-d H:i:s'))); 
            }
        }
        /*$checkIfResExists = $this->db->select("tbl_job_responsibilities", "*", array("job_id" => $job_id))->result();

        if($checkIfResExists) {
            $this->db->update("tbl_job_responsibilities", array("responsibility" => $key_responsibilities, "updated_on" => date('Y-m-d H:i:s')), array("job_id" => $job_id))->affectedRows();            
        } else {
            $resposibility_id = $this->db->insert("tbl_job_responsibilities", array("job_id" => $job_id, "responsibility" => $key_responsibilities, "added_on" => date('Y-m-d H:i:s')))->getLastInsertId();            
        }*/

        $job_details_array = array(
            "employment_type" => $employment_type,
            "relavent_experience_from" => $relavent_experience_from,
            //"relavent_experience_to" => $relavent_experience_to,
            //"licenses_endorsement_id" => $licenses_endorsement,
            "apply_flag" => $apply_flag,
            "apply_email" => $apply_email,
            "apply_url" => $apply_url,
            //"skills_and_exp" => $skills_and_exp,
            "key_responsibilities" => $key_responsibilities,
            "updated_on" => date('Y-m-d H:i:s')
        );

         
            $last_date_old = getTableValue("tbl_jobs", "last_date_of_application", array("id" => $job_id));
            
            $last_date = (isset($_POST['last_date_of_application']) && $_POST['last_date_of_application'] != '' )? date("Y-m-d", strtotime($_POST['last_date_of_application'])) :$last_date_old;
           $job_details_array_1 = array("last_date_of_application"=> $last_date);
           $job_details_array = array_merge($job_details_array,$job_details_array_1);
         
         

        $affectedRows = $this->db->update("tbl_jobs", $job_details_array, array("id" => $job_id))->affectedRows();

       // $this->db->delete("tbl_job_skills", array("job_id" => $job_id));

        // if(isset($_POST['skill_id']) && $_POST['skill_id'] != '') {
        //     foreach ($_POST['skill_id'] as $key => $value) {
        //         $this->db->insert('tbl_job_skills', array('job_id' => $job_id, 'skill_id' => $value, 'added_on' => date('Y-m-d H:i:s')));
        //     }
        // }

         $this->db->delete("tbl_job_education", array("job_id" => $job_id));

        if(isset($_POST['degree_id']) && $_POST['degree_id'] != '') {
            foreach ($_POST['degree_id'] as $key => $value) {
                $this->db->insert('tbl_job_education', array('job_id' => $job_id, 'degree_id' => $value, 'added_on' => date('Y-m-d H:i:s')));
            }
        }

        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            if($is_featured == 'y' && $tariff_plan != '') {
                $response['redirect_url'] = SITE_URL . "pay-for-fj/plan/" . ($tariff_plan) . "/job/" . encryptIt($job_id);
            }  else {
                $response['redirect_url'] = SITE_URL . "job/" . $job_id;    
            }
            
            //$response['success'] = "Job has been updated successfully.";
            //$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => LBL_JOB_UPDATED_SUCCESSFULLY));
            return $response;
        } else {
            $response['error'] = LBL_THERE_SEEMS_TO_BE_ISSUE_UPDATE_JOB;
            return $response;
        }
    }

    public function InsertJobLocation($location_detail_array) {


        $job_id = filtering($location_detail_array['job_id'], 'input', 'int');
        // Location details
        $formatted_address = filtering($location_detail_array['formatted_address'], 'input');
        $address1 = filtering($location_detail_array['address1'], 'input');
        $address2 = filtering($location_detail_array['address2'], 'input');
        $country = filtering($location_detail_array['country'], 'input');
        $state = filtering($location_detail_array['state'], 'input');
        $city1 = filtering($location_detail_array['city1'], 'input');
        $city2 = filtering($location_detail_array['city2'], 'input');
        $postal_code = filtering($location_detail_array['postal_code'], 'input');
        $latitude = filtering($location_detail_array['latitude'], 'input');
        $longitude = filtering($location_detail_array['longitude'], 'input');

        if($formatted_address != '' && $latitude != '' && $longitude != '') {

            $location_details_array = array(
                "formatted_address" => $formatted_address,
                "address1" => $address1,
                "address2" => $address2,
                "country" => $country,
                "state" => $state,
                "city1" => $city1,
                "city2" => $city2,
                "postal_code" => $postal_code,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "date_added" => date("Y-m-d H:i:s"),
                "date_updated" => date("Y-m-d H:i:s")
            );

            $location_id = $this->db->insert("tbl_locations", $location_details_array)->getLastInsertId();

            $affectedRows = $this->db->update("tbl_jobs", array('location_id' => $location_id), array("id" => $job_id))->affectedRows();
        }
    }
    public function getLicensesEndorsements() {
        $final_result = NULL;
        $myArray = [];
    
        $licenses_endorsements1 = $this->db->pdoQuery('select jl.license_hours,l.id,l.licenses_endorsements_name_'.$this->lId.' as license_name from tbl_job_license_hours as jl LEFT JOIN tbl_license_endorsements as l ON jl.license_ids = l.id where job_id = "'.$this->job_id.'"')->results();
        foreach ($licenses_endorsements1 as $value) {
            array_push($myArray,$value['id']);
        }
        $licenses_endorsements = $this->db->select("tbl_license_endorsements", array('id,licenses_endorsements_name_'.$this->lId.' as license_name'), array("isActive" => "y"))->results();
       // $myArray = explode(',', $this->licenses_endorsement_id);
        if ($licenses_endorsements != '') {
            $getSelectBoxOption = $this->getSelectBoxOption();

            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            
            for ($i = 0; $i < count($licenses_endorsements); $i++) {
                if (in_array($licenses_endorsements[$i]['id'],$myArray)){
                    $selected = 'selected';
                }else{
                    $selected = '';
                }                
                $fields_replace = array(
                    filtering('l_'.$licenses_endorsements[$i]['id'], 'output', 'int'),
                    $selected,
                    filtering($licenses_endorsements[$i]['license_name'], 'output')
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;
    }
    public function getAddedLicenseValues() {
        $final_result = NULL;
        $myArray = [];
        // echo "s";
        $licenses_endorsements1 = $this->db->pdoQuery('select jl.license_hours,l.id,l.licenses_endorsements_name_'.$this->lId.' as license_name from tbl_job_license_hours as jl LEFT JOIN tbl_license_endorsements as l ON jl.license_ids = l.id where job_id = "'.$this->job_id.'"')->results();
        foreach ($licenses_endorsements1 as $value) {
            array_push($myArray,$value['id']);
        }
        $str = implode(',',$myArray);
        return $str;
    }
    public function getLicensesEndorsementsList() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/single-added-licenses-endorsement-nct.tpl.php");
        $main_content_parsed = $main_content->parse();

        $licenses_endorsements1 = $this->db->pdoQuery('select jl.license_hours,jl.id,l.id as license_id,l.licenses_endorsements_name_'.$this->lId.' as license_name from tbl_job_license_hours as jl LEFT JOIN tbl_license_endorsements as l ON jl.license_ids = l.id where job_id = "'.$this->job_id.'"')->results();

        $fields = array("%LICENSE_NAME%","%LICENSE_ID%","%JOB_ID%","%LICENSE_HOURS%");
        for ($i = 0; $i < count($licenses_endorsements1); $i++) {
            //_print_r($licenses_endorsements1[$i]['license_name']);
            $license_name = isset($licenses_endorsements1[$i]['license_name']) ? $licenses_endorsements1[$i]['license_name'] : '';
            $license_id = isset($licenses_endorsements1[$i]['license_id']) ? $licenses_endorsements1[$i]['license_id'] : '';
            $job_id = isset($licenses_endorsements1[$i]['id']) ? $licenses_endorsements1[$i]['id'] : '';
            $license_hours = isset($licenses_endorsements1[$i]['license_hours']) ? $licenses_endorsements1[$i]['license_hours'] : '';

            $fields_replace = array(
                    $license_name,
                    $license_id,
                    $job_id,
                    $license_hours
                );
            //_print_r($fields_replace);
            $final_result .= str_replace($fields, $fields_replace, $main_content_parsed);
        }
        //exit();
        return $final_result;
    }
    // public function insertSelectedLicense($selected_value = array()){
    //     print_r($selected_values);exit();
    // }
    // public function getLicensesEndorsements() {
    //     $final_result = NULL;
    //     $licenses_endorsements = $this->db->select("tbl_license_endorsements", array('id,licenses_endorsements_name_'.$this->lId.' as licenses_endorsement_name'), array("isActive" => "y"))->results();
    //     $myArray = explode(',', $this->licenses_endorsement_id);
    //     if ($licenses_endorsements != '') {
    //         $getSelectBoxOption = $this->getSelectBoxOption();

    //         $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            
    //         for ($i = 0; $i < count($licenses_endorsements); $i++) {
    //             if (in_array($licenses_endorsements[$i]['id'],$myArray)){
    //                 $selected = 'selected';
    //             }else{
    //                 $selected = '';
    //             }                
    //             $fields_replace = array(
    //                 filtering($licenses_endorsements[$i]['id'], 'output', 'int'),
    //                 $selected,
    //                 filtering($licenses_endorsements[$i]['licenses_endorsement_name'], 'output')
    //             );
    //             $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
    //         }
    //     }
    //     return $final_result;
    // }
    public function getLicensesEndorsementsSuggestion($user_id, $licenses_endorsement_name,$licenses_endorsement_id) {
        $final_result = array();

        $query = "SELECT id,licenses_endorsements_name_".$this->lId." as licenses_endorsement_name FROM tbl_license_endorsements WHERE licenses_endorsements_name_".$this->lId." LIKE '%" . $licenses_endorsement_name . "%' AND isActive = ? 
                    ORDER BY id DESC  ";

        if($this->plaform=='web')
            $query .= " LIMIT 0, 10 ";
      
        $get_licenses_endorsement = $this->db->pdoQuery($query,array('y'))->results();
        if ($get_licenses_endorsement != '') {
            for ($i = 0; $i < count($get_licenses_endorsement); $i++) {
                $single_skill = array();
                $single_skill['licenses_endorsement_id'] = encryptIt(filtering($get_licenses_endorsement[$i]['id'], 'output', 'int'));
                $single_skill['licenses_endorsements_name'] = filtering($get_licenses_endorsement[$i]['licenses_endorsement_name']);
                $single_skill['licenses_endorsement_id_orig'] = (filtering($get_licenses_endorsement[$i]['id'], 'output', 'int'));
                $final_result[] = $single_skill;
            }
        }
        return $final_result;
    }
    // public function getLicensesEndorsementsSuggestion($user_id, $licenses_endorsement_name,$licenses_endorsement_id) {
    //     $final_result = array();
        
    //     $query = "SELECT id,licenses_endorsements_name_".$this->lId." as licenses_endorsement_name FROM tbl_license_endorsements WHERE licenses_endorsements_name_".$this->lId." LIKE '%" . $licenses_endorsement_name . "%' AND isActive = ? 
    //                 ORDER BY id DESC  ";

    //     if($this->plaform=='web')
    //         $query .= " LIMIT 0, 10 ";
      
    //     $get_licenses_endorsement = $this->db->pdoQuery($query,array('y'))->results();
    //     if ($get_licenses_endorsement != '') {
    //         for ($i = 0; $i < count($get_licenses_endorsement); $i++) {
    //             $single_skill = array();
    //             $single_skill['licenses_endorsement_id'] = encryptIt(filtering($get_licenses_endorsement[$i]['id'], 'output', 'int'));
    //             $single_skill['licenses_endorsements_name'] = filtering($get_licenses_endorsement[$i]['licenses_endorsement_name']);
    //             $single_skill['licenses_endorsement_id_orig'] = (filtering($get_licenses_endorsement[$i]['id'], 'output', 'int'));
    //             $final_result[] = $single_skill;
    //         }
    //     }
    //     return $final_result;
    // }
}
?>
