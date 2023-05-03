<?php 
class Profile extends Home {
    function __construct($platform='web',$current_user_id=0) {
        parent::__construct();
        foreach ($GLOBALS as $key => $values) { $this->$key = $values; }
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        // print_r($_GET['user_id']);exit();
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $this->user_id = $user_id = filtering($_GET['user_id'], 'input', 'int');
        } else if(isset($_POST['user_id']) && $_POST['user_id'] > 0){
            $this->user_id = $user_id = filtering($_POST['user_id'], 'input', 'int');
        } else {
            $this->user_id = $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        }
        // print_r($this->user_id);exit();
        $this->platform = $platform;
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
        //print_r($this->current_user_id);exit();
        $query = "SELECT u.profile_picture_name,u.id,u.first_name,u.last_name,u.email_address,u.date_added,u.phone_no,u.user_home_airport,u.isFerryPilot,u.personal_details,u.address_line1,u.address_line2,u.gender,u.user_DOB,l.formatted_address, i.industry_name_".$this->lId." as industry_name,i.id as industry_id,
            l.address1,l.address2,l.country,l.state,l.city1,l.city2,l.postal_code,l.latitude,l.longitude FROM tbl_users u LEFT JOIN tbl_locations l ON u.location_id = l.id LEFT JOIN tbl_user_experiences ue ON ue.user_id = u.id LEFT JOIN tbl_companies c ON c.id = ue.company_id LEFT JOIN tbl_industries i ON i.id = ue.industry_id WHERE u.id = ? GROUP BY ue.id ORDER BY CAST(is_current AS CHAR) DESC, from_year desc ,from_month DESC";
                    
        $user_details = $this->db->pdoQuery($query,array($this->user_id))->result();
       // echo "<pre>";print_r($user_details);exit();
        $this->db_user_id    = filtering($user_details['id']);
        $this->first_name    = filtering($user_details['first_name']);
        $this->last_name     = filtering($user_details['last_name']);
        $this->email_address = isset($user_details['email_address']) ? filtering($user_details['email_address']) : '-';
        $this->formatted_address = filtering($user_details['formatted_address']);
        $this->industry_name = filtering($user_details['industry_name']);
        $this->industry_id   = filtering($user_details['industry_id']);
        $this->address1      = filtering($user_details['address1']);
        $this->address2      = filtering($user_details['address2']);
        $this->country       = filtering($user_details['country']);
        $this->state         = filtering($user_details['state']);
        $this->city1         = filtering($user_details['city1']);
        $this->city2         = filtering($user_details['city2']);
        $this->postal_code   = filtering($user_details['postal_code']);
        $this->latitude      = filtering($user_details['latitude']);
        $this->longitude     = filtering($user_details['longitude']);
        $this->profile_picture_name=filtering($user_details['profile_picture_name']);

        $this->createdAt  = isset($user_details['date_added']) ? date("d-m-Y",strtotime($user_details['date_added'])) : '-';
        $this->phone_no  = isset($user_details['phone_no']) ? $user_details['phone_no'] : '-';
        $this->user_home_airport=isset($user_details['user_home_airport']) ? $user_details['user_home_airport'] : '-';
        $this->isFerryPilot=isset($user_details['isFerryPilot']) ? $user_details['isFerryPilot'] : 'n';
        $this->gender=isset($user_details['gender']) ? $user_details['gender'] : 'n';
        $this->personal_details=isset($user_details['personal_details']) ? $user_details['personal_details'] : '';
        $this->user_DOB = isset($user_details['user_DOB']) ? $user_details['user_DOB'] : '';
        $this->address_line1 = isset($user_details['address_line1']) ? $user_details['address_line1'] : '';
        $this->address_line2 = isset($user_details['address_line2']) ? $user_details['address_line2'] : '';
        $this->user_DOB = isset($user_details['user_DOB']) ? $user_details['user_DOB'] : '';
    }
    public function getFeedBox($feed_id) {$final_result = "";return $final_result;}
    public function getIndustryOptions($selected_industry_id = '') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
        $industries = $this->db->pdoQuery("SELECT id,industry_name_".$this->lId." as industry_name FROM tbl_industries WHERE status = ? ORDER BY id DESC",array('a'))->results();
        for ($i = 0; $i < count($industries); $i++) {
            $selected = ( ( ( $industries[$i]['id'] ) == $selected_industry_id ) ? "selected" : "" );
            $fields_replace = array(
                $industries[$i]['id'],
                $selected,
                ucwords($industries[$i]['industry_name'])
            );
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    public function getCountryOptions($selected_country_id = '') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
        $country = $this->db->pdoQuery("SELECT CountryId as id,countryName FROM tbl_country WHERE isActive = ? ORDER BY CountryId DESC",array('y'))->results();
        for ($i = 0; $i < count($country); $i++) {
            $selected = ( ( ( $country[$i]['id'] ) == $selected_country_id ) ? "selected" : "" );
            $fields_replace = array(
                $country[$i]['id'],
                $selected,
                ucwords($country[$i]['countryName'])
            );
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    // public function getCompanySizeOptions($selected_company_size_id = '') {
    //     $final_result = NULL;
    //     $getSelectBoxOption = $this->getSelectBoxOption();
    //     $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
    //     $company_sizes = $this->db->pdoQuery("SELECT id,company_size_".$this->lId." as company_size,minimum_no_of_employee,maximum_no_of_employee FROM tbl_company_sizes WHERE status = ? ORDER BY id DESC",array('a'))->results();
    //     for ($i = 0; $i < count($company_sizes); $i++) {
    //         $selected = ( ( ( $company_sizes[$i]['id'] ) == $selected_company_size_id ) ? "selected" : "" );
    //         $fields_replace = array(
    //             $company_sizes[$i]['id'],
    //             $selected,
    //             $company_sizes[$i]['company_size'] . " (" . $company_sizes[$i]['minimum_no_of_employee'] . " - " . $company_sizes[$i]['maximum_no_of_employee'] . ")",
    //         );
    //         $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
    //     }
    //     return $final_result;
    // }
    public function getCompanyLocations($company_id, $selected_location_id = '',$platform='web') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
        $query = "SELECT cl.location_id,l.formatted_address FROM tbl_company_locations cl
            LEFT JOIN tbl_locations l ON l.id = cl.location_id 
            WHERE cl.company_id = ? ORDER BY cl.is_hq ASC,cl.id DESC";
        $locations = $this->db->pdoQuery($query,array($company_id))->results();
        for ($i = 0; $i < count($locations); $i++) {
            $selected = ( ( ( $locations[$i]['location_id'] ) == $selected_location_id ) ? "selected" : "" );
            $fields_replace = array(
                $locations[$i]['location_id'],
                $selected,
                $locations[$i]['formatted_address'],
            );
            if($platform == 'app'){
                $app_array[] = array('location_id'=>$locations[$i]['location_id'],'location_title'=>$locations[$i]['formatted_address']);
            } else {
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        if($platform == 'app'){
            if(empty($app_array)){
                $final_result['locations'] = array();
                $final_result['status'] = 'success';
                $final_result['message'] = LBL_LOCATION_NOT_FOUND;
            } else {
                $final_result['locations'] = $app_array;
                $final_result['status'] = 'success';
                $final_result['message'] =LBL_SUCCESSFULLY_LISTING_LOCATION;
            }
        }
        return $final_result;
    }
    public function getMonthOptions($selected_month_no = '') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
        $months_array = unserialize(MONTHS_ARRAY);
        for ($i = 0; $i < count($months_array); $i++) {
            $selected = ( ( ( $i + 1 ) == $selected_month_no ) ? "selected" : "" );
            $fields_replace = array(
                ( $i + 1 ),
                $selected,
                $months_array[$i]
            );
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    public function addExperience($platform='web') {
        
        $response = array();
        $post_user_id = (($platform == 'app') ? $_POST['user_id'] : $this->session_user_id);
        $response['status'] = false;
        $experience_id = '';
        if (isset($_POST['experience_id']) && $_POST['experience_id'] != '') {
            $experience_id = filtering($_POST['experience_id']);
        }
        $company_name = filtering($_POST['company_name']);
        $job_title = filtering($_POST['job_title']);
        if (isset($_POST['job_location_id']) && $_POST['job_location_id'] > 0) {
            $job_location_id = filtering($_POST['job_location_id'], 'input', 'int');
        } else {
            $job_location = filtering($_POST['job_location']);
            $formatted_address = filtering($_POST['formatted_address']);
            $address1 = filtering($_POST['address1']);
            $address2 = filtering($_POST['address2']);
            $country = filtering($_POST['country']);
            $state = filtering($_POST['state']);
            $city1 = filtering($_POST['city1']);
            $city2 = filtering($_POST['city2']);
            $postal_code = filtering($_POST['postal_code']);
            $latitude = filtering($_POST['latitude']);
            $longitude = filtering($_POST['longitude']);
        }
        $from_month = filtering($_POST['from_month'], 'input', 'int');
        $from_year = filtering($_POST['from_year'], 'input', 'int');
        $to_month = "";
        $to_year = "";
        $is_current = ( isset($_POST['is_current']) ) ? 'y' : 'n';
        $is_headline = 'n';
        if ('n' == $is_current) {
            $to_month = filtering($_POST['to_month'], 'input', 'int');
            $to_year = filtering($_POST['to_year'], 'input', 'int');
        } else {
            $is_headline = ( isset($_POST['is_headline']) ) ? 'y' : 'n';
            if ($is_headline == 'y') {
                $this->db->exec('update tbl_user_experiences set is_headline = "n" where user_id = "' . $post_user_id . '"');
            }
        }
        $description = filtering($_POST['description'], 'input', 'int');
        $industry_id = filtering($_POST['industry_id'], 'input', 'int');
        //$company_size_id = filtering($_POST['company_size_id'], 'input', 'int');
        $checkIfExists = $this->db->pdoQuery("SELECT id FROM tbl_companies WHERE company_name = ? ",array($company_name))->result();
        if ($checkIfExists) {
            $company_id = $checkIfExists['id'];
            if ($industry_id && $company_size_id) {
                $addCompanyArray = array(
                    //"company_industry_id" => $industry_id,
                   // "company_size_id" => $company_size_id,
                    "updated_on" => date("Y-m-d H:i:s")
                );
                $data= $this->db->update("tbl_companies", $addCompanyArray,array('id'=>$company_id))->affectedRows();
            }
        } else {
            
            if ($industry_id) {
                $addCompanyArray = array(
                    "user_id" => $post_user_id,
                    "company_name" => $company_name,
                    "company_industry_id" => $industry_id,
                   // "company_size_id" => $company_size_id,
                    "company_type" => 'e',
                    "added_on" => date("Y-m-d H:i:s"),
                    "updated_on" => date("Y-m-d H:i:s")
                );
                $company_id = $this->db->insert("tbl_companies", $addCompanyArray)->getLastInsertId();
                if (!$company_id) {
                    $response['error'] = ERROR_THERE_SEEMS_TO_BE_SOME_ISSUE_ON_ADDING_EXPERIENCE;
                    return $response;
                }
            } else {
                $response['error'] = LBL_PLEASE_SELECT_INDUSTRY;
                return $response;
            }
        }
        $experience_details_array = array(
            "job_title" => $job_title,
            "company_id" => $company_id,
            "industry_id"=>$industry_id,
            "description" => $description,
            "from_month" => $from_month,
            "from_year" => $from_year,
            "to_month" => $to_month,
            "to_year" => $to_year,
            "is_current" => $is_current,
            "is_headline" => $is_headline,                        
            "updated_on" => date("Y-m-d H:i:s")
        );
        if (isset($_POST['job_location_id']) && $_POST['job_location_id'] > 0) {
        } else {
            $job_location_details_array = array(
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
                "date_updated" => date("Y-m-d H:i:s")
            );
        }
        if ($experience_id > 0) {
            if (isset($_POST['job_location_id']) && $_POST['job_location_id'] > 0) {
                $location_id = filtering($_POST['job_location_id'], 'input', 'int');
            } else {
                $location_id = getTableValue("tbl_user_experiences", "job_location_id", array("id" => $experience_id));
                $this->db->update("tbl_locations", $job_location_details_array, array("id" => $location_id))->affectedRows();
            }
        } else {
            if (isset($_POST['job_location_id']) && $_POST['job_location_id'] > 0) {
                $location_id = filtering($_POST['job_location_id'], 'input', 'int');
            } else {
                $job_location_details_array['date_added'] = date("Y-m-d H:i:s");
                $location_id = $this->db->insert("tbl_locations", $job_location_details_array)->getLastInsertId();
            }
        }
        if ($location_id > 0) {
            $experience_details_array['job_location_id'] = $location_id;
            if ($experience_id > 0) {
                $affectedRows = $this->db->update("tbl_user_experiences", $experience_details_array, array("id" => $experience_id))->affectedRows();
                if ($affectedRows > 0 || $affectedRows == 0) {
                    $response['status'] = true;
                    $response['success'] = SUCCESS_YOUR_EXPERIENCE_DETAILS_HAS_BEEN_UPDATED_SUCCESSFULLY;
                    $response['experiences'] = $this->getAddedExperiences($post_user_id);
                    if($platform == 'app'){
                        $app_array['status'] = 'success';
                        $app_array['message'] = $response['success'];
                        return $app_array;
                    }
                    return $response;
                } else {
                    $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
                    if($platform == 'app'){
                        $app_array['status'] = 'error';
                        $app_array['message'] = $response['error'];
                        return $app_array;
                    }
                    return $response;
                }
            } else {
                $experience_details_array['user_id'] = $post_user_id;
                $experience_details_array['job_location_id'] = $location_id;
                $experience_details_array['added_on'] = date("Y-m-d H:i:s");
                $added_id =$this->db->insert("tbl_user_experiences", $experience_details_array)->getLastInsertId();
                $response['status'] = true;
                $response['success'] = SUCCESS_YOUR_EXPERIENCE_DETAILS_HAS_BEEN_ADDED_SUCCESSFULLY;
                $response['experiences'] = $this->getAddedExperiences($post_user_id);
                $response['current_experience_app'] = $this->getAddedExperiences($post_user_id,'app',$added_id);
                return $response;
            }
        } else {
            $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
            return $response;
        }
        return $response;
    }
    public function addLicenses($platform='web') {
       //echo "<pre>";print_r($_POST);exit();
        $response = array();
        $post_user_id = (($platform == 'app') ? $_POST['user_id'] : $this->session_user_id);
        $response['status'] = false;
        $licenses_id = '';
        if (isset($_POST['licenses_endorsement_id']) && $_POST['licenses_endorsement_id'] != '') {
            $licenses_id = decryptIt($_POST['licenses_endorsement_id']);
        }
        
        $licenses_ids = $_POST['licenses_id'];
        $licenses_name = filtering($_POST['licenses_name']);
        $date_obtain = $_POST['date_obtain'];
        $institute_name = ($_POST['institute_name'] != '') ? $_POST['institute_name'] : ($_POST['institute_name1'] != '') ? $_POST['institute_name1'] : '';
        // print_r($institute_name);exit();
        $country_id = isset($_POST['country_id']) ? $_POST['country_id'] : '';
        
        $checkIfExists = $this->db->pdoQuery("SELECT id FROM tbl_license_endorsements WHERE licenses_endorsements_name_".$this->lId." = ? ",array($licenses_name))->result();
        $license_name_id = $checkIfExists['id'];
        if ($license_name_id > 0) {
            $licenses_details_array = array(
                "user_id"       => $_SESSION['user_id'],
                "licenses_id"   => $license_name_id,
                "date_obtained" =>date("Y-m-d",strtotime($date_obtain)),
                "institute_name"=> $institute_name,
                "verification_status" => 'n',
                "country_id"    => $country_id,
            );        
           if ($licenses_id > 0) {
                $licenses_details_array['updated_on'] = date("Y-m-d H:i:s");
                $affectedRows = $this->db->update("tbl_users_licenses_endorsement", $licenses_details_array, array("id" => $licenses_id))->affectedRows();
                if ($affectedRows > 0 || $affectedRows == 0) {
                    $response['status'] = true;
                    $response['success'] = SUCCESS_YOUR_LICENSE_DETAILS_HAS_BEEN_UPDATED_SUCCESSFULLY;
                    $response['licenses'] = $this->getAddedLicensesEndorsement($post_user_id);
                    return $response;
                } else {
                    $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
                    return $response;
                }
            }else{
                $licenses_details_array["added_on"]= date("Y-m-d H:i:s");
                $licenses_id1 = $this->db->insert("tbl_users_licenses_endorsement", $licenses_details_array)->getLastInsertId();
                if($licenses_id1 > 0){
                    $response['status'] = true;
                    $response['success'] = SUCCESS_YOUR_LICENSE_DETAILS_HAS_BEEN_INSERTED_SUCCESSFULLY;
                    $response['licenses'] = $this->getAddedLicensesEndorsement($post_user_id);
                    return $response;   
                }else{
                    $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
                    return $response;   
                }
            }   
        }else{

        }
        //$response['status'] = 'success';
        //$response['message'] = 'success';
        return $response;
    }
    public function addEducation($platform='web') {
        $response = array();
        $response['status'] = false;
        $education_id = '';
        if (isset($_POST['education_id']) && $_POST['education_id'] != '') {
            if($platform == 'app'){
                $education_id = filtering($_POST['education_id']);
            } else {
                $education_id = decryptIt(filtering($_POST['education_id']));
            }
        }

        $user_id = (($platform == 'app') ? $_POST['user_id'] : $this->session_user_id);

        $university_name = filtering($_POST['university_name']);
        $degree_name = filtering($_POST['degree_name']);
        $field_of_study = filtering($_POST['field_of_study']);
        $grade_or_percentage = filtering($_POST['grade_or_percentage']);
        $from_year = filtering($_POST['from_year'], 'input', 'int');
        $to_year = filtering($_POST['to_year'], 'input', 'int');
        $description = filtering($_POST['description'], 'input', 'int');
        $education_details_array = array(
            "degree_name" => $degree_name,
            "university_name" => $university_name,
            "field_of_study" => $field_of_study,
            "from_year" => $from_year,
            "to_year" => $to_year,
            "grade_or_percentage" => $grade_or_percentage,
            "description" => $description,
            "updated_on" => date("Y-m-d H:i:s")
        );
        if ($education_id > 0) {
            $affectedRows = $this->db->update("tbl_user_education", $education_details_array, array("id" => $education_id))->affectedRows();
            if ($affectedRows > 0 || $affectedRows == 0) {
                $response['status'] = true;
                $response['success'] = SUCCESS_YOUR_EDUCATION_DETAILS_HAS_BEEN_UPDATED_SUCCESSFULLY;
                $response['experiences'] = $this->getAddedEducations($user_id);
                return $response;
            } else {
                $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
                return $response;
            }
        } else {
            $education_details_array['user_id'] = $user_id;
            $education_details_array['added_on'] = date("Y-m-d H:i:s");
            $this->db->insert("tbl_user_education", $education_details_array)->getLastInsertId();
            $response['status'] = true;
            $response['success'] = SUCCESS_YOUR_EDUCATION_DETAILS_HAS_BEEN_ADDED_SUCCESSFULLY;
            $response['experiences'] = $this->getAddedEducations($user_id);
            return $response;
        }
        return $response;
    }
    public function getAddedExperiences($user_id,$platform = 'web',$experience_id=0) {
        $final_result = '';
        $temp_cond = '';
        if($experience_id>0){
            $temp_cond = " and ue.id = '" . $experience_id . "'";
        }
        $query = "SELECT c.company_type,ue.company_id,ue.is_current,ue.from_year,ue.from_month,ue.to_year,ue.to_month,ue.id,ue.job_title,ue.description, c.company_name, c.company_description, l.formatted_address 
                    FROM tbl_user_experiences ue
                    LEFT JOIN tbl_companies c ON c.id = ue.company_id 
                    LEFT JOIN tbl_company_locations cl ON cl.company_id = c.id 
                    LEFT JOIN tbl_locations l ON l.id = ue.job_location_id 
                    WHERE ue.user_id = ? $temp_cond
                    GROUP BY ue.id ORDER BY CAST(is_current AS CHAR) DESC, from_year desc ,from_month DESC";
        $experiences = $this->db->pdoQuery($query,array($user_id))->results();
        if ($experiences) {
            $actions = '';
            $delete_actions = '';
            $single_experience_tpl = new Templater(DIR_TMPL . $this->module . "/single-experience-nct.tpl.php");
            if ($this->user_id == $this->session_user_id) {
                $actions_tpl = new Templater(DIR_TMPL . $this->module . "/actions-nct.tpl.php");
                $actions_tpl_parsed = $actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array(LBL_UPDATE_EXPERIENCE, "edit-experience-icon");
                $actions = str_replace($fields, $fields_replace, $actions_tpl_parsed);
                $delete_actions_tpl = new Templater(DIR_TMPL . $this->module . "/delete-actions-nct.tpl.php");
                $delete_actions_tpl_parsed = $delete_actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array(LBL_DELETE_EXPERIENCE, "delete-experience-icon");
                $delete_actions = str_replace($fields, $fields_replace, $delete_actions_tpl_parsed);
            }
            $single_experience_tpl->set('actions', $actions);
            $single_experience_tpl->set('delete_actions', $delete_actions);
            $single_experience_tpl_parsed = $single_experience_tpl->parse();
            $fields = array("%EXPERIENCE_ID_ENCRYPTED%","%JOB_TITLE%","%COMPANY_NAME%","%FROM%","%TO%","%JOB_TENURE%","%JOB_LOCATION%","%DESCRIPTION%","%COMPANY_URL%","%CLASS_EXP_TENURE%");
            $array = array();
            for ($i = 0; $i < count($experiences); $i++) {
                $is_current = filtering($experiences[$i]['is_current']);
                $months_array = unserialize(MONTHS_ARRAY);
                $from = $months_array[filtering($experiences[$i]['from_month']) - 1] . ' ' . filtering($experiences[$i]['from_year']);
                $from_date = filtering($experiences[$i]['from_year']) . "-" . filtering($experiences[$i]['from_month']) . "-01";
                if ($is_current == 'y') {
                    $to = LBL_PRESENT;
                    $to_date = date("Y-m-d");
                    $class_exp_tenure="hidden";

                } else {
                    $to = $months_array[filtering($experiences[$i]['to_month']) - 1] . ' ' . filtering($experiences[$i]['to_year']);
                    $to_date = filtering($experiences[$i]['to_year']) . "-" . filtering($experiences[$i]['to_month']) . "-01";
                    $class_exp_tenure='';
                }
                $job_tenure = getDifference($from_date, $to_date);
                $job_title = filtering($experiences[$i]['job_title']);
                $company_name = filtering($experiences[$i]['company_name']);
                $formatted_address = filtering($experiences[$i]['formatted_address']);
                $description = filtering($experiences[$i]['description']);
                if($experiences[$i]['company_type']=='r'){
                    $company_url=SITE_URL."company/".$experiences[$i]['company_id'];

                }else{
                    $company_url=SITE_URL . "search/users?company[]=".$experiences[$i]['company_id']."";
                }
                $fields_replace = array(
                    encryptIt($experiences[$i]['id']),
                    ucwords($job_title),
                    ucwords($company_name),
                    $from,
                    $to,
                    $job_tenure,
                    $formatted_address,
                    ucwords($description),
                    $company_url,
                    $class_exp_tenure                    
                );
                if($platform == 'app'){
                    $array[] = array('experience_id'=>$experiences[$i]['id'],'job_title'=>$job_title,'company_name'=>$company_name,'from'=>$from,'to'=>$to,'job_tenure'=>$job_tenure,'formatted_address'=>$formatted_address,'description'=>$description,'company_url'=>$company_url,'company_id'=>$experiences[$i]['company_id'],'company_type'=>$experiences[$i]['company_type']);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_experience_tpl_parsed);
                }
            }
        } else {
            if($user_id == $_SESSION['user_id']){
                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ADDED_ANY_EXPERIENCE);
                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function getAddedEducations($user_id,$platform = 'web') {
        $final_result = $single_education_tpl  = '';
        $query = "SELECT ue.id,ue.university_name,ue.degree_name,ue.field_of_study,ue.grade_or_percentage,ue.from_year,ue.to_year,ue.description FROM tbl_user_education ue WHERE ue.user_id = ? ORDER BY from_year DESC ";
        $educations = $this->db->pdoQuery($query,array($user_id))->results();
        if ($educations) {
            $actions = '';
            $delete_actions = '';
            $single_education_tpl = new Templater(DIR_TMPL . $this->module . "/single-education-nct.tpl.php");
            if ($this->user_id == $this->session_user_id) {
                $actions_tpl = new Templater(DIR_TMPL . $this->module . "/actions-nct.tpl.php");
                $actions_tpl_parsed = $actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array("Update Education", "edit-education-icon");
                $actions = str_replace($fields, $fields_replace, $actions_tpl_parsed);
                $delete_actions_tpl = new Templater(DIR_TMPL . $this->module . "/delete-actions-nct.tpl.php");
                $delete_actions_tpl_parsed = $delete_actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array("Delete Education", "delete-education-icon");
                $delete_actions = str_replace($fields, $fields_replace, $delete_actions_tpl_parsed);
            }
            $single_education_tpl->set('actions', $actions);
            $single_education_tpl->set('delete_actions', $delete_actions);
            $single_education_tpl_parsed = $single_education_tpl->parse();
            $fields = array("%EDUCATION_ID_ENCRYPTED%","%UNIVERSITY_NAME%","%DEGREE_NAME%","%FIELD_OF_STUDY%","%GRADE_OR_PERCENTAGE%","%FROM_YEAR%","%TO_YEAR%","%DESCRIPTION%");
            for ($i = 0; $i < count($educations); $i++) {
                $university_name = filtering($educations[$i]['university_name']);
                $degree_name = filtering($educations[$i]['degree_name']);
                $field_of_study = filtering($educations[$i]['field_of_study']);
                $grade_or_percentage = filtering($educations[$i]['grade_or_percentage']);
                $from_year = filtering($educations[$i]['from_year']);
                $to_year = filtering($educations[$i]['to_year']);
                $description = filtering($educations[$i]['description']);
                $fields_replace = array(
                    encryptIt($educations[$i]['id']),
                    ucwords($university_name),
                    ucwords($degree_name),
                    ucwords($field_of_study),
                    $grade_or_percentage,
                    $from_year,
                    $to_year,
                    ucwords($description)
                );
                if($platform == 'app'){
                    $array[] = array('education_id'=>$educations[$i]['id'],'university_name'=>$university_name,'degree_name'=>$degree_name,'field_of_study'=>$field_of_study,'grade_or_percentage'=>$grade_or_percentage,'from_year'=>$from_year,'to_year'=>$to_year,'description'=>$description);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_education_tpl_parsed);
                }
            }
        } else {
            if($user_id == $_SESSION['user_id']){

                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ADDED_ANY_EDUCATION);
                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function getAddedSkills($user_id,$platform='web') {
        $final_result = '';
        $query = "SELECT us.id as user_skill_id, s.id as skill_id, s.skill_name_".$this->lId." as skill_name FROM tbl_user_skills us
                    LEFT JOIN tbl_skills s ON s.id = us.skill_id WHERE us.user_id = ? ";
        $skills = $this->db->pdoQuery($query,array($user_id))->results();
        if ($skills) {
            $actions = '';
            $single_skill_li_tpl = new Templater(DIR_TMPL . $this->module . "/skill-li-nct.tpl.php");
            if ($this->user_id == $this->session_user_id) {
                $remove_skill_tpl = new Templater(DIR_TMPL . $this->module . "/remove-skill-nct.tpl.php");
                $actions = $remove_skill_tpl->parse();
            }
            $fields = array("%SKILL_ID_ENCRYPTED%","%SKILL_NAME%");
            for ($i = 0; $i < count($skills); $i++) {
                $skill_name = filtering($skills[$i]['skill_name']);
                $fields_replace = array(
                    encryptIt($skills[$i]['user_skill_id']),
                    $skill_name
                );
                $single_skill_li_tpl->set('skill_actions', str_replace(array("%ENC_SKILL_ID%"), array(encryptIt($skills[$i]['skill_id'])), $actions));
                $single_skill_li_tpl_parsed = $single_skill_li_tpl->parse();

                if($platform == 'app'){
                    $array[] = array('skill_id'=>$skills[$i]['skill_id'],'skill_name'=>$skill_name);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_skill_li_tpl_parsed);
                }

            }
        } else {
            if($user_id == $_SESSION['user_id']){

                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ADDED_ANY_SKILL);
                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function getAddedLicensesEndorsement($user_id,$platform = 'web') {
        $final_result = $single_education_tpl = '';
        $query = "SELECT ue.*,l.licenses_endorsements_name_".$this->lId." as license_name,c.countryName FROM tbl_users_licenses_endorsement ue LEFT JOIN tbl_license_endorsements l ON ue.licenses_id = l.id LEFT JOIN tbl_country as c ON ue.country_id = c.CountryId WHERE ue.user_id = ? ";
        $licenses = $this->db->pdoQuery($query,array($user_id))->results();
        //print_r($licenses);exit();
        if ($licenses) {
            $actions = '';
            $delete_actions = '';
            $single_education_tpl = new Templater(DIR_TMPL . $this->module . "/single-licenses-nct.tpl.php");
            if ($this->user_id == $this->session_user_id) {
                $actions_tpl = new Templater(DIR_TMPL . $this->module . "/actions-nct.tpl.php");
                $actions_tpl_parsed = $actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array("Update licenses", "edit-licenses-icon");
                $actions = str_replace($fields, $fields_replace, $actions_tpl_parsed);
                $delete_actions_tpl = new Templater(DIR_TMPL . $this->module . "/delete-actions-nct.tpl.php");
                $delete_actions_tpl_parsed = $delete_actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array("Delete licenses", "delete-licenses-icon");
                $delete_actions = str_replace($fields, $fields_replace, $delete_actions_tpl_parsed);
            }
            $single_education_tpl->set('actions', $actions);
            $single_education_tpl->set('delete_actions', $delete_actions);
            $single_education_tpl_parsed = $single_education_tpl->parse();
            $fields = array("%LICENSES_ID_ENCRYPTED%","%LICENSE_NAME%","%DATE_OBTAIN%","%INSTITUTE_NAME%","%VERIFICATION_STATUS%","%COUNTRY_NAME%","%HIDE_VERIFICATION_LINK%","%HIDE_ANOTHER_USER%","%HIDE_FOR_OWNER%");
            for ($i = 0; $i < count($licenses); $i++) {
                //_print_r($licenses);exit();
                $license_name = filtering($licenses[$i]['license_name']);
                $date_obtained = filtering($licenses[$i]['date_obtained']);
                $institute_name = filtering($licenses[$i]['institute_name']);
                $countryName = filtering($licenses[$i]['countryName']);
                
                if($licenses[$i]['verification_status'] == 'y'){
                    $user_details = $this->db->select('tbl_users', array('id,first_name,last_name'), array('id' => $licenses[$i]['verified_user_id']))->result();
                    $verified = $user_details['first_name'].' '.$user_details['last_name'];
                    //print_r($verified);exit();
                }else{
                    $verified = LBL_UNVERIFIED;
                }
                $hide_verification = '';
                $hide_verification = ($licenses[$i]['verification_status'] == 'y' && $licenses[$i]['verified_user_id'] != 0) ? 'hide' : '';
                
                $user_info_ref = $this->db->select('tbl_users', array('id'),array('id' => $this->session_user_id,'isReferralLink' => 'y'))->result();
                if($user_info_ref != '' && $user_info_ref['id'] > 0){
                    $hide_another_user = (isset($_SESSION['user_profile_id']) && $_SESSION['user_profile_id'] != '') ? '' : 'hide';
                }else{
                    $hide_another_user = '';
                }
                
                $hide_for_owner = ($licenses[$i]['user_id'] == $this->session_user_id) ? 'hide' : '';
                // print_r($licenses[$i]['user_id']);
                // //print_r($this->session_user_id);
                // exit();
                $fields_replace = array(
                    encryptIt($licenses[$i]['id']),
                    ucwords($license_name),
                    $date_obtained,
                    ucwords($institute_name),
                    $verified,
                    $countryName,
                    $hide_verification,
                    $hide_another_user,
                    $hide_for_owner
                );
                if($platform == 'app'){
                   $array[] = array('licenses_id'=>$licenses[$i]['id'],'license_name'=>$license_name,'date_obtained'=>$date_obtained,'institute_name'=>$institute_name,'verified'=>$verified);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_education_tpl_parsed);
                }
            }
        } else {
            if($user_id == $_SESSION['user_id']){
                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ADDED_ANY_LICENSE);
                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function getAddedAirport($user_id,$platform='web') {
        $final_result = $single_home_airport_tpl = '';
        $query = "SELECT ua.id as user_airport_id,a.id as airport_id ,a.airport_name_".$this->lId." as airport_name FROM tbl_user_airports ua LEFT JOIN tbl_airport a ON a.id = ua.airport_id WHERE ua.isActive = 'y' AND ua.isAdminApprove = 'y' AND  ua.user_id = ? ";
        $airports = $this->db->pdoQuery($query,array($user_id))->results();
        
        if ($airports) {
            $actions = '';
            $delete_actions = '';
            $single_home_airport_tpl = new Templater(DIR_TMPL . $this->module . "/airport-li-nct.tpl.php");
            if ($this->user_id == $this->session_user_id) {
                $actions_tpl = new Templater(DIR_TMPL . $this->module . "/actions-nct.tpl.php");
                $actions_tpl_parsed = $actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array("Update airports", "edit-airports-icon");
                $actions = str_replace($fields, $fields_replace, $actions_tpl_parsed);
                $delete_actions_tpl = new Templater(DIR_TMPL . $this->module . "/delete-actions-nct.tpl.php");
                $delete_actions_tpl_parsed = $delete_actions_tpl->parse();
                $fields = array("%TITLE%", "%CLASS%");
                $fields_replace = array("Delete airports", "delete-airports-icon");
                $delete_actions = str_replace($fields, $fields_replace, $delete_actions_tpl_parsed);
            }
            $single_home_airport_tpl->set('actions', $actions);
            $single_home_airport_tpl->set('delete_actions', $delete_actions);
            $single_home_airport_tpl_parsed = $single_home_airport_tpl->parse();
            $fields = array("%AIRPORT_ID_ENCRYPTED%","%AIRPORT_NAME%");

            for ($i = 0; $i < count($airports); $i++) {
                $user_airport_id = filtering($airports[$i]['user_airport_id']);
                $airport_name = filtering($airports[$i]['airport_name']);
               
                $fields_replace = array(
                    encryptIt($user_airport_id),
                    ucwords($airport_name)
                );
                if($platform == 'app'){
                    $array[] = array('user_airport_id'=>$airports[$i]['id'],'airport_name'=>$airport_name);
                } 
                else {
                    $final_result .= str_replace($fields, $fields_replace, $single_home_airport_tpl_parsed);
                }
                //print_r($fields_replace);exit();
            }
        } else {
            if($user_id == $_SESSION['user_id']){
                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ADDED_ANY_LICENSE);
                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function getExperienceForm($experience_id = '') {
        $response = array();
        $response['status'] = false;
        $final_result = '';
        $experience_form_tpl = new Templater(DIR_TMPL . $this->module . "/experience-form-nct.tpl.php");
        $experience_form_tpl_parsed = $experience_form_tpl->parse();

        $fields = array(
            "%EXPERIENCE_ID_ENCRYPTED%",
            "%COMPANY_NAME%",
            "%COMPANY_ID%",
            "%INDUSTRY_DD_CONTAINER_HIDDEN_CLASS%",
            "%INDUSTRY_OPTIONS%",
            "%COMPANY_SIZE_DD_CONTAINER_HIDDEN_CLASS%",
            //"%COMPANY_SIZE_OPTIONS%",
            "%JOB_LCOATION_CONTAINER_HIDDEN_CLASS%",
            "%JOB_LOCATION_DD_CONTAINER_HIDDEN_CLASS%",
            "%JOB_LOCATION_OPTIONS%",
            "%JOB_TITLE%",
            "%JOB_LOCATION%",
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
            "%YEAR_MAX_LIMIT%",
            "%MONTH_OPTIONS_FROM%",
            "%FROM_YEAR%",
            "%TO_DATE_CONTAINER_DISPLAY_NONE%",
            "%MONTH_OPTIONS_TO%",
            "%TO_YEAR%",
            "%IS_CURRENT_CHECKED%",
            "%DESCRIPTION%"
        );
        $company_name = $job_title = $job_location = $month_options_from = $from_year = $month_options_to = $to_year = $is_current_checked = $description = '';
        $to_date_container_display_none = '';
        $formatted_address = $address1 = $address2 = $country = $state = $city1 = $city2 = $postal_code = $latitude = $longitude = '';
        $month_options_from = $this->getMonthOptions();
        $month_options_to = $this->getMonthOptions();
        $company_id = '';
        $from_year = $to_year = $this->getYearOption();
        $industry_dd_container_hidden_class = "";
        $company_size_dd_container_hidden_class = "";
        $job_location_dd_container_hidden_class = "hidden";
        $job_lcoation_container_hidden_class = "";
        $industry_options = $this->getIndustryOptions();
       // $company_size_options = $this->getCompanySizeOptions();
        $job_location_options = '';
        if ($experience_id > 0) {
            $experience_id_encrypted = encryptIt($experience_id);

            $query = "SELECT ue.company_id,ue.job_title,l.formatted_address,l.address1,l.address2,l.country,l.state,l.city1,l.city2,l.postal_code,l.latitude,l.longitude,ue.description,ue.from_month,ue.from_year,ue.to_month,ue.to_year,ue.is_current,ue.job_location_id, c.company_name,c.company_description,c.company_type,c.company_industry_id,ue.is_headline,ue.industry_id
                        FROM tbl_user_experiences ue 
                        LEFT JOIN tbl_companies c ON c.id = ue.company_id 
                        LEFT JOIN tbl_locations l ON l.id = ue.job_location_id 
                        WHERE ue.id = ? ";

            $experience_details = $this->db->pdoQuery($query,array($experience_id))->result();
            $company_name = filtering($experience_details['company_name']);
            $company_id = filtering($experience_details['company_id'], 'input', 'int');
            $job_title = filtering($experience_details['job_title']);
            $formatted_address = filtering($experience_details['formatted_address']);
            $address1 = filtering($experience_details['address1']);
            $address2 = filtering($experience_details['address2']);
            $country = filtering($experience_details['country']);
            $state = filtering($experience_details['state']);
            $city1 = filtering($experience_details['city1']);
            $city2 = filtering($experience_details['city2']);
            $postal_code = filtering($experience_details['postal_code']);
            $latitude = filtering($experience_details['latitude'], 'output', 'float');
            $longitude = filtering($experience_details['longitude'], 'output', 'float');
            $description = filtering($experience_details['description']);
            $from_month = filtering($experience_details['from_month']);
            $from_year = filtering($experience_details['from_year']);
            $to_month = filtering($experience_details['to_month']);
            $to_year = filtering($experience_details['to_year']);
            $month_options_from = $this->getMonthOptions($from_month);
            $month_options_to = $this->getMonthOptions($to_month);
            $from_year = $this->getYearOption($from_year);
            $to_year = $this->getYearOption($to_year);
            $is_current = filtering($experience_details['is_current']);
            $company_type = filtering($experience_details['company_type']);
            if ($is_current == 'y') {
                $is_current_checked = ' checked="checked" ';
                $to_date_container_display_none = 'display: none; ';
            }
            if ($company_type == 'e') {
                $industry_dd_container_hidden_class = "";
                $company_size_dd_container_hidden_class = "";
                $job_lcoation_container_hidden_class = "";
                $job_location_dd_container_hidden_class = "hidden";
                $industry_options=$this->getIndustryOptions(filtering($experience_details['company_industry_id']));
               // $company_size_options = $this->getCompanySizeOptions(filtering($experience_details['company_size_id']));
            } else {
                $industry_dd_container_hidden_class = "hidden";
                $company_size_dd_container_hidden_class = "hidden";
                $job_lcoation_container_hidden_class = "hidden";
                $job_location_dd_container_hidden_class = "";
                $job_location_options = $this->getCompanyLocations(filtering($experience_details['company_id']), filtering($experience_details['job_location_id']));
                $industry_options=$this->getIndustryOptions(filtering($experience_details['industry_id']));
            }
        }
        $year_max_limit = date("Y");
        $fields_replace = array(
            $experience_id,
            $company_name,
            $company_id,
            $industry_dd_container_hidden_class,
            $industry_options,
            $company_size_dd_container_hidden_class,
           // $company_size_options,
            $job_lcoation_container_hidden_class,
            $job_location_dd_container_hidden_class,
            $job_location_options,
            $job_title,
            $formatted_address,
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
            $year_max_limit,
            $month_options_from,
            $from_year,
            $to_date_container_display_none,
            $month_options_to,
            $to_year,
            $is_current_checked,
            $description
        );
        //echo "<pre>";print_r($fields_replace);exit();
        if($this->platform=='app'){
            $response = '';
            $response = array(
                'company_id'=>$company_id,
                'company_name'=>$company_name,
                'company_industry_id'=>$experience_details['company_industry_id'],
                //'company_size_id'=>$experience_details['company_size_id'],
                'job_title'=>$job_title,
                'location'=>$formatted_address,
                'from_month'=>$from_month,
                'from_year'=>$experience_details['from_year'],
                'to_month'=>$to_month,
                'to_year'=>$experience_details['to_year'],
                'is_current'=>$is_current,
                'is_headline'=>$experience_details['is_headline'],
                'description'=>$description,
                
                

                /*
                'job_title'=>$job_title,'formatted_address'=>$formatted_address,'formatted_address'=>$formatted_address,'address1'=>$address1,'address2'=>$address2,'country'=>$country,'state'=>$state,'city1'=>$city1,'city2'=>$city2,'postal_code'=>$postal_code,'latitude'=>$latitude,'longitude'=>$longitude,'year_max_limit'=>$year_max_limit,'month_options_from'=>$month_options_from,'from_year'=>$from_year,'to_date_container_display_none'=>$to_date_container_display_none,'month_options_to'=>$month_options_to,'to_year'=>$to_year,'is_current_checked'=>$is_current_checked,'description'=>$description*/);
        } else {
            $final_result = str_replace($fields, $fields_replace, $experience_form_tpl_parsed);
            $response['status'] = true;
            $response['experience_form'] = $final_result;
        }
        return $response;
    }
    public function getAddedLicensesEndorsementForm($licenses_id = '') {
        $response = array();
        $response['status'] = false;

        $final_result = '';
        
        $experience_form_tpl = new Templater(DIR_TMPL . $this->module . "/licenses-endorsement-form-nct.tpl.php");
        $experience_form_tpl_parsed = $experience_form_tpl->parse();

        $fields = array(
            "%LICENSES_ID_ENCRYPTED%",
            "%LICENSE_NAME%",
            "%LICENSE_ID%",
            "%DATE_OBTAIN%",
            "%INSTITUTE_NAME%",
            "%COUNTRY_OPTIONS%",
            "%VERIFICATION_STATUS%",
        );
        if($licenses_id > 0){
            $query = "SELECT ue.*,l.licenses_endorsements_name_".$this->lId." as license_name FROM tbl_users_licenses_endorsement ue LEFT JOIN tbl_license_endorsements l ON ue.licenses_id = l.id where ue.id ='".$licenses_id."'";
            $experience_details = $this->db->pdoQuery($query)->result();
        }        
        if($experience_details['verification_status'] == 'y'){
            $verified = 'Yes';
        }else{
            $verified = LBL_UNVERIFIED;   
        }
        $fields_replace = array(
            encryptIt($experience_details['id']),
            $experience_details['license_name'],
            $experience_details['id'],
            $experience_details['date_obtained'],
            $experience_details['institute_name'],
            $industry_options = $this->getCountryOptions($experience_details['country_id']),
            $verified,           
        );
        //print_r($fields_replace);exit();
        $final_result = str_replace($fields, $fields_replace, $experience_form_tpl_parsed);
            $response['status'] = true;
            $response['licenses_form'] = $final_result;
            $response['get_license'] = $this->getLicenseList($this->session_user_id);
        //echo "<pre>";print_r($fields_replace);exit();
     //    if($this->platform=='app'){
     //        $response = '';
     //        $response = array(
     //            'company_id'=>$company_id,
     //            'company_name'=>$company_name,
     //            'company_industry_id'=>$experience_details['company_industry_id'],
     //           // 'company_size_id'=>$experience_details['company_size_id'],
     //            'job_title'=>$job_title,
     //            'location'=>$formatted_address,
     //            'from_month'=>$from_month,
     //            'from_year'=>$experience_details['from_year'],
     //            'to_month'=>$to_month,
     //            'to_year'=>$experience_details['to_year'],
     //            'is_current'=>$is_current,
     //            'is_headline'=>$experience_details['is_headline'],
     //            'description'=>$description,
     // );
     //    } else {
            
        //}
        return $response;
    }
    public function getAddedAirportForm($airport_id = '') {
        $response = array();
        $response['status'] = false;

        $final_result = '';
        
        $experience_form_tpl = new Templater(DIR_TMPL . $this->module . "/licenses-endorsement-form-nct.tpl.php");
        $experience_form_tpl_parsed = $experience_form_tpl->parse();

        $fields = array(
            "%LICENSES_ID_ENCRYPTED%",
            "%LICENSE_NAME%",
            "%LICENSE_ID%",
            "%DATE_OBTAIN%",
            "%INSTITUTE_NAME%",
            "%COUNTRY_OPTIONS%",
            "%VERIFICATION_STATUS%",
        );
        if($licenses_id > 0){
            $query = "SELECT ue.*,l.licenses_endorsements_name_".$this->lId." as license_name FROM tbl_users_licenses_endorsement ue LEFT JOIN tbl_license_endorsements l ON ue.licenses_id = l.id where ue.id ='".$licenses_id."'";
            $experience_details = $this->db->pdoQuery($query)->result();
        }        
        if($experience_details['verification_status'] == 'y'){
            $verified = 'Yes';
        }else{
            $verified = LBL_UNVERIFIED;   
        }
        $fields_replace = array(
            encryptIt($experience_details['id']),
            $experience_details['license_name'],
            $experience_details['id'],
            $experience_details['date_obtained'],
            $experience_details['institute_name'],
            $industry_options = $this->getCountryOptions($experience_details['country_id']),
            $verified,           
        );
        //print_r($fields_replace);exit();
        $final_result = str_replace($fields, $fields_replace, $experience_form_tpl_parsed);
            $response['status'] = true;
            $response['licenses_form'] = $final_result;
        return $response;
    }
    public function getYearOptions($selected_year = '') {
        $final_result = NULL;
        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
        for ($i = date("Y"); $i > 1900; $i--) {
            $selected = ( ( ( $i ) == $selected_year ) ? "selected" : "" );
            $fields_replace = array($i,$selected,$i);
            $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        return $final_result;
    }
    public function getEducationForm($education_id = '') {
        $response = array();
        $response['status'] = false;
        $final_result = '';
        $education_form_tpl = new Templater(DIR_TMPL . $this->module . "/education-form-nct.tpl.php");
        $education_form_tpl_parsed = $education_form_tpl->parse();
        $fields = array("%EDUCATION_ID_ENCRYPTED%","%UNIVERSITY_NAME%","%DEGREE_NAME%","%FIELD_OF_STUDY%","%GRADE_OR_PERCENTAGE%","%YEAR_OPTIONS_FROM%","%YEAR_OPTIONS_TO%","%DESCRIPTION%");
        $education_id_encrypted = $university_name = $degree_name = $field_of_study = $grade_or_percentage = $year_options_from = $year_options_to = $description = '';
        $year_options_from = $this->getYearOptions();
        $year_options_to = $this->getYearOptions();

        if ($education_id > 0) {
            $education_id_encrypted = encryptIt($education_id);
            $query = "SELECT ue.university_name,ue.degree_name,ue.field_of_study,ue.grade_or_percentage,ue.from_year,ue.to_year,ue.description FROM tbl_user_education ue WHERE ue.id = ? ";

            $education_details = $this->db->pdoQuery($query,array($education_id))->result();
            $university_name = filtering($education_details['university_name']);
            $degree_name = filtering($education_details['degree_name']);
            $field_of_study = filtering($education_details['field_of_study']);
            $grade_or_percentage = filtering($education_details['grade_or_percentage']);
            $from_year = filtering($education_details['from_year'], 'output', 'int');
            $to_year = filtering($education_details['to_year'], 'output', 'int');
            $year_options_from = $this->getYearOptions($from_year);
            $year_options_to = $this->getYearOptions($to_year);
            $description = filtering($education_details['description']);
        }
        if($this->platform == 'app'){
            $response = array('university_name'=>$university_name,'degree_name'=>$degree_name,'field_of_study'=>$field_of_study,'grade_or_percentage'=>$grade_or_percentage,'from_year'=>$from_year,'to_year'=>$to_year,'description'=>$description,);
        } else {
            $fields_replace = array($education_id_encrypted,$university_name,$degree_name,$field_of_study,$grade_or_percentage,$year_options_from,$year_options_to,$description);
            $final_result = str_replace($fields, $fields_replace, $education_form_tpl_parsed);
            $response['status'] = true;
            $response['education_form'] = $final_result;
        }
        return $response;
    }
    public function getUserDetailForm($education_id = '') {
        $response = array();
        $response['status'] = false;
        $final_result = '';
        $user_details_form_tpl = new Templater(DIR_TMPL . $this->module . "/user-details-form-nct.tpl.php");
        $user_details_form_tpl_parsed = $user_details_form_tpl->parse();
        $fields = array("%FIRST_NAME%","%LAST_NAME%","%FORMATTED_ADDRESS%","%USER_LOCATION%","%ADDRESS1%","%ADDRESS2%","%COUNTRY%","%STATE%","%CITY1%","%CITY2%","%POSTAL_CODE%","%LATITUDE%","%LONGITUDE%","%CREATED_DATE%","%USER_EMAIL_ADDRESS%","%USER_CONTACT_NO%","%PERSONAL_DETAILS%","%ADDRESS_LINE1%","%ADDRESS_LINE2%","%USER_DOB%","%MALE_SELECTED%","%FEMALE_SELECTED%","%FERRY_Y%","%FERRY_N%","%HIDE_COMMERCIAL_NOT_VERIFIED%");
        $createdAt = convertDate('displayWeb',filtering($this->createdAt, 'output', 'output'));
        $ferry_y = ($this->isFerryPilot == 'y') ? 'checked' : '';
        $ferry_n = ($this->isFerryPilot == 'n') ? 'checked' : '';
        
        $user_DOB=($this->user_DOB!='0000-00-00')?$this->user_DOB:'';
        $male_selected=($this->gender=='m')?'selected="selected"':'';
        $female_selected=($this->gender=='f')?'selected="selected"':'';

        $user_info = $this->db->select('tbl_users_licenses_endorsement', array('id'),array('user_id' => $this->session_user_id,'licenses_id' => '1','verification_status' => 'y'))->result();
       
        if ($user_info['id'] > 0 && $user_info != '') {
            $hide_commercial_not_verified = '';    
        }else{
            $hide_commercial_not_verified = 'hide';
        }
        //print_r($hide_commercial_not_verified);exit();

        $fields_replace = array($this->first_name,$this->last_name,$this->formatted_address,$this->formatted_address,$this->address1,$this->address2,$this->country,$this->state,$this->city1,$this->city2,$this->postal_code,$this->latitude,$this->longitude,$createdAt,$this->email_address,$this->phone_no,$this->personal_details,$this->address_line1,$this->address_line2,$user_DOB,$male_selected,$female_selected,$ferry_y,$ferry_n,$hide_commercial_not_verified);
        //echo "<pre>";print_r($fields_replace);exit();
        $final_result = str_replace($fields, $fields_replace, $user_details_form_tpl_parsed);
        $response['status'] = true;
        $response['user_detail_form'] = $final_result;
        return $response;
    }
    public function getCompaniesForSuggestion($user_id, $company_name='',$platform='web',$company_type='r') {
        $final_result = array();
        if($company_type=='r'){
            $query = "SELECT id,company_name,company_industry_id FROM tbl_companies WHERE company_name LIKE '%" . $company_name . "%' AND status = ? AND company_type = ? ORDER BY id DESC ";
            $where_arr=array('a',$company_type);
        }else{
            if($platform=='app'){
                $query = "SELECT id,company_name,company_industry_id FROM tbl_companies WHERE status = ? AND company_type = ? ORDER BY id DESC ";

            }else{
                $query = "SELECT id,company_name,company_industry_id FROM tbl_companies WHERE company_name LIKE '%" . $company_name . "%' AND status = ? AND company_type = ? ORDER BY id DESC ";
            }
            
            $where_arr=array('a',$company_type);

        }
        if($platform == 'web'){
            $query .="LIMIT 0, 10";
        }
        $companies = $this->db->pdoQuery($query,$where_arr)->results();
        if ($companies) {
            for ($i = 0; $i < count($companies); $i++) {
                $single_company = array();
                if($platform == 'app'){
                    $single_company['company_id'] = filtering($companies[$i]['id'], 'output', 'int');
                    $single_company['company_industry_id'] = filtering($companies[$i]['company_industry_id'], 'output', 'int');
                    //$single_company['company_size_id'] = filtering($companies[$i]['company_size_id'], 'output', 'int');
                } else {
                    $single_company['company_id'] = encryptIt(filtering($companies[$i]['id'], 'output', 'int'));
                    $single_company['company_industry_id'] = filtering($companies[$i]['company_industry_id'], 'output', 'int');
                    //$single_company['company_size_id'] = filtering($companies[$i]['company_size_id'], 'output', 'int');
                }
                $single_company['company_name'] = filtering($companies[$i]['company_name']);
                $final_result[] = $single_company;
            }
        }

        if (empty($final_result)) {
            if($platform == 'app'){
                $final_result=array('companies'=>array(),'status'=>'success','message'=>LBL_NO_RESULTS_FOUND);
            }
        } else {
            if($platform == 'app'){
                $final_result = array('companies'=>$final_result,'status'=>'success','message'=>LBL_SUCCESS_COMPANIES_LISTING);
            }
        }
        return $final_result;
    }
    public function getLicensesForSuggestion($user_id, $licenses_name='',$platform='web') {
        $final_result = array();
        
        $query = "SELECT id,licenses_endorsements_name_".$this->lId." as licenses_endorsements_name FROM tbl_license_endorsements WHERE licenses_endorsements_name_".$this->lId." LIKE '%" . $licenses_name . "%' AND isActive = ? ORDER BY id DESC ";

        $where_arr=array('y');

        if($platform == 'web'){
            $query .="LIMIT 0, 10";
        }
        $licenses = $this->db->pdoQuery($query,$where_arr)->results();
        if ($licenses != '') {
            for ($i = 0; $i < count($licenses); $i++) {
                $single_company = array();
                if($platform == 'app'){
                    $single_company['licenses_id'] = filtering($licenses[$i]['id'], 'output', 'int');
                } else {
                    $single_company['licenses_id'] = encryptIt(filtering($licenses[$i]['id'], 'output', 'int'));
                }
                 $single_company['licenses_endorsements_name'] = $licenses[$i]['licenses_endorsements_name'];
                $final_result[] = $single_company;
            }
        }

        if (empty($final_result)) {
            if($platform == 'app'){
                $final_result=array('licenses'=>array(),'status'=>'success','message'=>LBL_NO_RESULTS_FOUND);
            }
        } else {
            if($platform == 'app'){
                $final_result = array('licenses'=>$final_result,'status'=>'success','message'=>LBL_SUCCESS_COMPANIES_LISTING);
            }
        }
        return $final_result;
    }
    public function getSkillsForSuggestion($user_id, $skill_name,$skill_id,$platform='web') {
        $final_result = array();
        
        $query = "SELECT us.id as user_skill_id, s.id as skill_id, s.skill_name_".$this->lId." as skill_name FROM tbl_user_skills us LEFT JOIN tbl_skills s ON s.id = us.skill_id WHERE us.user_id = ? ";
        $user_skills = $this->db->pdoQuery($query,array($user_id))->results();
        $user_skills_imploded = '';
        $user_skills_array = array();
        
        if ($user_skills) {
            for ($i = 0; $i < count($user_skills); $i++) {
                $user_skills_array[] = filtering($user_skills[$i]['skill_id'], 'input', 'int');
            }
            $user_skills_imploded = implode(",", $user_skills_array);
        }
        if($skill_id == "" || $skill_id == "null")
            $skill_id ='0';
        $not_in_query = " AND id NOT IN(".$skill_id.")";
        if ($user_skills_imploded != '') {
            $not_in_query .= " AND id NOT IN ( " . $user_skills_imploded . " ) ";
        }
        $limit_query = 'LIMIT 0, 10';
        if($platform == 'app'){
            //$not_in_query = '';
            $limit_query = '';
        }
        $query = "SELECT id,skill_name_".$this->lId." as skill_name FROM tbl_skills WHERE skill_name_".$this->lId." LIKE '%" . $skill_name . "%' AND status = ? " . $not_in_query . " ORDER BY id DESC  $limit_query";

        $get_skills = $this->db->pdoQuery($query,array('a'))->results();
        if ($get_skills) {
            for ($i = 0; $i < count($get_skills); $i++) {
                $single_skill = array();
                $single_skill['skill_id'] = encryptIt(filtering($get_skills[$i]['id'], 'output', 'int'));
                $single_app_array['skill_id'] = $single_skill['skill_id_orig'] = (filtering($get_skills[$i]['id'], 'output', 'int'));
                $single_app_array['skill_name'] = $single_skill['skill_name'] = filtering($get_skills[$i]['skill_name']);
                if($platform == 'app'){
                    $final_result[] = $single_app_array;
                } else {
                    $final_result[] = $single_skill;
                }
            }
        }
        if (empty($final_result)) {
            $single_language['skill_id'] = 0;
            $single_language['skill_name'] = LBL_NO_RESULTS_FOUND;

            if($platform == 'app'){
                $final_result=array('skills'=>array(),'status'=>'success','message'=>$single_language['skill_name']);
            } else {
                $final_result[] = $single_language;
            }
        } else {
            if($platform == 'app'){
                $final_result = array('skills'=>$final_result,'status'=>'success','message'=>LBL_SUCCESS_SKILL_LISTING);
            }
        }



        return $final_result;
    }
    public function getAirportsForSuggestion($user_id, $airport_name,$airport_id,$platform='web') {
        $final_result = array();
        
        $query = "SELECT ua.id as user_airport_id, a.id as airport_id, a.airport_name_".$this->lId." as airport_name FROM tbl_user_airports ua LEFT JOIN tbl_airport a ON a.id = ua.airport_id WHERE ua.user_id = ? ";
        $user_airports = $this->db->pdoQuery($query,array($user_id))->results();
        $user_airports_imploded = '';
        $user_airports_array = array();
        
        if ($user_airports) {
            for ($i = 0; $i < count($user_airports); $i++) {
                $user_airports_array[] = filtering($user_airports[$i]['airport_id'], 'input', 'int');
            }
            $user_airports_imploded = implode(",", $user_airports_array);
        }
        if($airport_id == "" || $airport_id == "null")
            $airport_id ='0';
        $not_in_query = " AND id NOT IN(".$airport_id.")";
        if ($user_airports_imploded != '') {
            $not_in_query .= " AND id NOT IN ( " . $user_airports_imploded . " ) ";
        }
        $limit_query = 'LIMIT 0, 10';
        if($platform == 'app'){
            $limit_query = '';
        }
        $query = "SELECT id,airport_name_".$this->lId." as airport_name FROM tbl_airport WHERE airport_name_".$this->lId." LIKE '%" . $airport_name . "%' AND status = ? " . $not_in_query . " ORDER BY id DESC  $limit_query";

        $get_airports = $this->db->pdoQuery($query,array('a'))->results();
        if ($get_airports) {
            for ($i = 0; $i < count($get_airports); $i++) {
                $single_skill = array();
                $single_skill['airport_id'] = encryptIt(filtering($get_airports[$i]['id'], 'output', 'int'));
                $single_app_array['airport_id'] = $single_skill['airport_id_orig'] = (filtering($get_airports[$i]['id'], 'output', 'int'));
                $single_app_array['airport_name'] = $single_skill['airport_name'] = filtering($get_airports[$i]['airport_name']);
                if($platform == 'app'){
                    $final_result[] = $single_app_array;
                } else {
                    $final_result[] = $single_skill;
                }
            }
        }
        if (empty($final_result)) {
            $single_language['airport_id'] = 0;
            $single_language['airport_name'] = LBL_NO_RESULTS_FOUND;

            if($platform == 'app'){
                $final_result=array('airports'=>array(),'status'=>'success','message'=>$single_language['airport_name']);
            } else {
                $final_result[] = $single_language;
            }
        } else {
            if($platform == 'app'){
                $final_result = array('airports'=>$final_result,'status'=>'success','message'=>LBL_SUCCESS_AIRPORT_LISTING);
            }
        }
        return $final_result;
    }
    public function getSkillForm() {
        $response = array();
        $response['status'] = false;
        $final_result = '';
        $skill_form_tpl = new Templater(DIR_TMPL . $this->module . "/skill-form-nct.tpl.php");
        $skill_form_tpl_parsed = $skill_form_tpl->parse();
        $response['status'] = true;
        $response['skill_form'] = $skill_form_tpl_parsed;
        return $response;
    }
    public function getAirportForm($airport_id = 0) {
        $response = array();
        $response['status'] = false;
        $final_result = '';
        $experience_details = array();
        $skill_form_tpl = new Templater(DIR_TMPL . $this->module . "/airport-form-nct.tpl.php");
        $skill_form_tpl_parsed = $skill_form_tpl->parse();

        $fields = array(
            "%AIRPORT_ID_ENCRYPTED%",
            "%AIRPORT_NAME%",
            "%AIRPORT_ID%"
        );
        if($airport_id > 0){
            $query = "SELECT ua.*,a.airport_name_".$this->lId." as airport_name FROM tbl_user_airports ua LEFT JOIN tbl_airport a ON ua.airport_id = a.id where ua.id ='".$airport_id."'";
            $experience_details = $this->db->pdoQuery($query)->result();
        }  
        //print_r($experience_details);exit();      
        $fields_replace = array(
            encryptIt(isset($experience_details['id']) ? $experience_details['id'] : ''),
            isset($experience_details['airport_name']) ? $experience_details['airport_name'] : '',
            isset($experience_details['airport_id']) ? $experience_details['airport_id'] : '',
        );
       // print_r($fields_replace);exit();
        $final_result = str_replace($fields, $fields_replace, $skill_form_tpl_parsed);
            
        $response['status'] = true;
        $response['airport_form'] = $final_result;
        return $response;
    }
    public function updateProfilePicture() {
        $output['status'] = false;
        $file_name = $_FILES['profile_picture']['name'];
        $type = $_FILES['profile_picture']['type'];
        $user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $file_array = $_FILES["profile_picture"];
        $upload_dir = DIR_UPD_USERS;
        $image_resize_array = unserialize(USER_PROFILE_PICTURE_RESIZE_ARRAY);
        $response = uploadImage($file_array, $upload_dir, $image_resize_array);
        if (!$response['status']) {
            return $response;
        } else {
            $old_profile_picture_name=getTableValue("tbl_users", "profile_picture_name", array("id" => $user_id));
            $profile_picture_name = $response['image_name'];
            $affected_rows = $this->db->update("tbl_users", array("profile_picture_name" => $profile_picture_name,"date_updated" => date("Y-m-d H:i:s")), array("id" => $user_id))->affectedRows();
            if ($affected_rows) {if ($old_profile_picture_name != "") { unlink(DIR_UPD_USERS . $old_profile_picture_name);}}
            if ($affected_rows) {
                $output['status'] = true;
                $output['image_medium'] = getImageURL("user_profile_picture", $user_id, "th4");
                $output['image_small'] = getImageURL("user_profile_picture", $user_id, "th1");
                $output['success'] = LBL_PROFILE_UPDATED;
            }
        }
        echo json_encode($output);
        exit;
    }
    
    public function getJoinedGroups() {
        $final_content = $joined_groups_html = $content = '';
        $joined_groups_tpl = new Templater(DIR_TMPL . $this->module . "/joined-groups-nct.tpl.php");
        $query = "SELECT group_id FROM tbl_group_members WHERE user_id = ? AND action != ? AND action != ? ";
        $groups = $this->db->pdoQuery($query,array($this->user_id,'r','jr'))->results();
        if ($groups) {
            $joined_groups_tpl_carousel = new Templater(DIR_TMPL . $this->module . "/joined-groups-carousel-nct.tpl.php");
            $joined_groups_tpl_carousel_parsed = $joined_groups_tpl_carousel->parse();
            for ($i = 0; $i < count($groups); $i++) {
                $active_class = '';
                if ($i == 0) { $active_class = ' active ';}
                $group_id = filtering($groups[$i]['group_id'], 'input', 'int');
                $joined_groups_html .= getGroupCarouselItem($group_id, $active_class);
            }
            $fields = array("%JOINED_GROUPS_CAROUSEL_ITEMS%","%CAROUSEL_CONTROLS_HIDDEN_CLASS%");
            $fields_replace = array($joined_groups_html,count($groups) <= 1 ? "hidden" : "");
            $content .= str_replace($fields, $fields_replace, $joined_groups_tpl_carousel_parsed);
            $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
            $view_all_link_tpl->set('view_all_link', SITE_URL . "groups/joined-groups");
            $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
        } else {
            

             $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
                $message = ERROR_YOU_HAVE_NOT_JOINED_ANY_GROUP;
                $url=SITE_URL."search/groups";
                $label=LBL_JOIN_GROUP;
                $no_result_found_tpl=$no_result_found_tpl->parse();
                $fields=array("%MSG%","%URL%","%LABEL%");
                $fields_replace=array($message,$url,$label);
                $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);


            //$content .= ERROR_YOU_HAVE_NOT_JOINED_ANY_GROUP;
            $view_all_link_tpl_parsed = "";
        }
        $joined_groups_tpl->set('joined_groups', $content);
        $joined_groups_tpl->set('view_all_link', $view_all_link_tpl_parsed);
        $final_content = $joined_groups_tpl->parse();
        return $final_content;
    }
    public function getFerryPilotSubscription() {
        $final_content = $following_companies_html = $content = '';
        $following_companies_tpl = new Templater(DIR_TMPL . $this->module . "/ferry-pilot-subscription-nct.tpl.php");
       // $query = "SELECT company_id FROM tbl_company_followers WHERE user_id = ? ";
        //$companies = $this->db->pdoQuery($query,array($this->user_id))->results();
        // if ($companies) {
        //     for ($i = 0; $i < count($companies); $i++) {
        //         $active_class = '';
        //         if ($following_companies_html == "") { $active_class = ' active ';}
        //         $company_id = filtering($companies[$i]['company_id'], 'input', 'int');
        //         $following_companies_html .= getCompanyCarouselItem($company_id, $active_class);
        //     }
        //     if ($following_companies_html) {
        //         $following_company_tpl_carousel = new Templater(DIR_TMPL . $this->module . "/following-companies-carousel-nct.tpl.php");
        //         $following_company_tpl_carousel_parsed = $following_company_tpl_carousel->parse();
        //         $fields = array("%FOLLOWING_COMPANIES_CAROUSEL_ITEMS%","%CAROUSEL_CONTROLS_HIDDEN_CLASS%");
        //         $fields_replace = array($following_companies_html,count($companies) <= 1 ? "hidden" : "");
        //         $content = str_replace($fields, $fields_replace, $following_company_tpl_carousel_parsed);
        //         $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
        //         $view_all_link_tpl->set('view_all_link', SITE_URL . "company/following-companies");
        //         $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
        //     } else {
        //         $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
        //         $message = SUCCESS_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
        //         $url=SITE_URL."search/companies";
        //         $label=LBL_FOLLOW_COMPANY;
        //         $no_result_found_tpl=$no_result_found_tpl->parse();
        //         $fields=array("%MSG%","%URL%","%LABEL%");
        //         $fields_replace=array($message,$url,$label);
        //         $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);
        //         //$content = ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
        //         $view_all_link_tpl_parsed = "";
        //     }
        // } else {
        //         $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
        //         $message = SUCCESS_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
        //         $url=SITE_URL."search/companies";
        //         $label=LBL_FOLLOW_COMPANY;
        //         $no_result_found_tpl=$no_result_found_tpl->parse();
        //         $fields=array("%MSG%","%URL%","%LABEL%");
        //         $fields_replace=array($message,$url,$label);
        //         $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);

        //     //$content = ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
        //         $view_all_link_tpl_parsed = "";
        // }
       // $following_companies_tpl->set('following_companies', $content);
        //$following_companies_tpl->set('view_all_link', $view_all_link_tpl_parsed);
        $final_content = $following_companies_tpl->parse();
        return $final_content;
    }
    public function getFollowingCompanies() {
        $final_content = $following_companies_html = $content = '';
        $following_companies_tpl = new Templater(DIR_TMPL . $this->module . "/following-companies-nct.tpl.php");
        $query = "SELECT company_id FROM tbl_company_followers WHERE user_id = ? ";
        $companies = $this->db->pdoQuery($query,array($this->user_id))->results();
        if ($companies) {
            for ($i = 0; $i < count($companies); $i++) {
                $active_class = '';
                if ($following_companies_html == "") { $active_class = ' active ';}
                $company_id = filtering($companies[$i]['company_id'], 'input', 'int');
                $following_companies_html .= getCompanyCarouselItem($company_id, $active_class);
            }
            if ($following_companies_html) {
                $following_company_tpl_carousel = new Templater(DIR_TMPL . $this->module . "/following-companies-carousel-nct.tpl.php");
                $following_company_tpl_carousel_parsed = $following_company_tpl_carousel->parse();
                $fields = array("%FOLLOWING_COMPANIES_CAROUSEL_ITEMS%","%CAROUSEL_CONTROLS_HIDDEN_CLASS%");
                $fields_replace = array($following_companies_html,count($companies) <= 1 ? "hidden" : "");
                $content = str_replace($fields, $fields_replace, $following_company_tpl_carousel_parsed);
                $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
                $view_all_link_tpl->set('view_all_link', SITE_URL . "company/following-companies");
                $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
            } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
                $message = SUCCESS_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $url=SITE_URL."search/companies";
                $label=LBL_FOLLOW_COMPANY;
                $no_result_found_tpl=$no_result_found_tpl->parse();
                $fields=array("%MSG%","%URL%","%LABEL%");
                $fields_replace=array($message,$url,$label);
                $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);
                //$content = ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $view_all_link_tpl_parsed = "";
            }
        } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
                $message = SUCCESS_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $url=SITE_URL."search/companies";
                $label=LBL_FOLLOW_COMPANY;
                $no_result_found_tpl=$no_result_found_tpl->parse();
                $fields=array("%MSG%","%URL%","%LABEL%");
                $fields_replace=array($message,$url,$label);
                $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);

            //$content = ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                $view_all_link_tpl_parsed = "";
        }
        $following_companies_tpl->set('following_companies', $content);
        $following_companies_tpl->set('view_all_link', $view_all_link_tpl_parsed);
        $final_content = $following_companies_tpl->parse();
        return $final_content;
    }
    public function getAppliedJobs() {
        $final_content = $applied_for_jobs_html = $content = '';
        $applied_for_jobs_tpl = new Templater(DIR_TMPL . $this->module . "/applied-for-jobs-nct.tpl.php");
        $query = "SELECT job_id FROM tbl_job_applications WHERE user_id = ? ";
        $jobs = $this->db->pdoQuery($query,array($this->user_id))->results();
        if ($jobs) {
            $applied_for_jobs_tpl_carousel = new Templater(DIR_TMPL . $this->module . "/applied-for-jobs-carousel-nct.tpl.php");
            $applied_for_jobs_tpl_carousel_parsed = $applied_for_jobs_tpl_carousel->parse();
            for ($i = 0; $i < count($jobs); $i++) {
                $active_class = '';
                if ($i == 0) {$active_class = ' active ';}
                $job_id = filtering($jobs[$i]['job_id'], 'input', 'int');
                $applied_for_jobs_html .= getJobCarouselItem($job_id, $active_class);
            }
            $fields = array("%APPLIED_FOR_JOBS_CAROUSEL_ITEMS%","%CAROUSEL_CONTROLS_HIDDEN_CLASS%");
            $fields_replace = array($applied_for_jobs_html,count($jobs) <= 1 ? "hidden" : "");
            $content .= str_replace($fields, $fields_replace, $applied_for_jobs_tpl_carousel_parsed);
            $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
            $view_all_link_tpl->set('view_all_link', SITE_URL . "jobs/applied-jobs");
            $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
        } else {
                
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-nct.tpl.php");
                $message = ERROR_YOU_HAVE_NOT_APPLIED_FOR_ANY_JOBS;
                $url=SITE_URL."search/jobs";
                $label=LBL_COM_DET_JOBS;
                $no_result_found_tpl=$no_result_found_tpl->parse();
                $fields=array("%MSG%","%URL%","%LABEL%");
                $fields_replace=array($message,$url,$label);
                $content .= str_replace($fields, $fields_replace, $no_result_found_tpl);


           // $content .= ERROR_YOU_HAVE_NOT_APPLIED_FOR_ANY_JOBS;
            $view_all_link_tpl_parsed = "";
        }
        $applied_for_jobs_tpl->set('applied_for_jobs', $content);
        $applied_for_jobs_tpl->set('view_all_link', $view_all_link_tpl_parsed);
        $final_content = $applied_for_jobs_tpl->parse();
        return $final_content;
    }
    public function getRightSidebar() {
        $final_content = '';
        $right_sidebar_tpl = new Templater(DIR_TMPL . $this->module . "/right-sidebar-nct.tpl.php");
        $right_sidebar_tpl_parsed = $right_sidebar_tpl->parse();
        $fields = array("%MEMBERSHIP_PLAN%","%JOINED_GROUPS%","%FOLLOWING_COMPANIES%","%APPLIED_JOBS%","%COMMON_CONNECTIONS%","%SIMILAR_PROFILES%","%FERRY_PILOT_SUBSCRIPTION%");
        if ($this->session_user_id == $this->user_id) {
            $fields_replace = array(
                $this->getSubscribedMembershipPlan($this->session_user_id),
                $this->getJoinedGroups(),
                $this->getFollowingCompanies(),
                $this->getAppliedJobs(),
                "",
                "",
                $this->getFerryPilotSubscription(),
            );
        } else {
            $fields_replace = array(
                "",
                "",
                "",
                "",
                $this->getCommonConnectionsUL((int)$_GET['user_id']),
                $this->getSimilarProfileUL((int)$_GET['user_id']),
                "",
            );
        }
        $final_content = str_replace($fields, $fields_replace, $right_sidebar_tpl_parsed);
        return $final_content;
    }
    public function getCommonConnectionSection($user_id) {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/common-connection-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $user_info = $this->db->select('tbl_users', array('id,first_name,last_name'), array('id' => $user_id))->result();
        $fields = array(
            "%USER_ID%",
            "%SESSION_USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            "%USER_HEAD_LINE%",
            "%USER_URL%",
            "%UNIQUE_IDENTIFIER%",
            "%SESSION_USER_ID%",
            "%ENCRYPTED_USER_ID%",
            "%MESSAGE_URL%"
        );
        $fields_replace = array(
            (filtering($user_info['id'], 'output', 'int')),
            $this->session_user_id,
            ucwords(filtering($user_info['first_name'], 'output')) . " " . ucwords(filtering($user_info['last_name'], 'output')),
            getUserProfilePictureURL($user_id, "th3"),
            getUserHeadline($user_id),
            get_user_profile_url($user_id),
            filtering($user_info['id'], 'output', 'int') . uniqid(),
            $this->session_user_id,
            encryptIt(filtering($user_info['id'], 'output', 'int')),
            SITE_URL . 'compose-message/' . encryptIt(filtering($user_info['id'], 'output', 'int'))
        );
        $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function getPageContent($platform="web") {
        $final_result = NULL;
        $follow_actions_container_tpl_parsed = '';
        $actions=$remove_from_connection_url=$connections_url=$connection_level=$send_inmail_url=$send_inmail_text='';
        $send_inmail_class = 'hidden';
        $user_actions_container_tpl_parsed = '';
      
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $profile_picture_actions_parsed = $add_experience_parsed = $add_education_parsed = $add_skill_parsed = $add_language_parsed = $add_licenses_endorsement_parsed =  "";
      
        if ($this->user_id == $this->current_user_id) {
            $profile_picture_actions = new Templater(DIR_TMPL . $this->module . "/profile-picture-actions-nct.tpl.php");
            $profile_picture_actions_parsed = $profile_picture_actions->parse();
            $fields = array("%CLASS%");
            if($this->profile_picture_name == ''){
                $class='hidden';
            }else{
                $class='';
            }
            $fields_replace = array($class);
            $profile_picture_actions_parsed  = str_replace($fields, $fields_replace, $profile_picture_actions_parsed);

            $add_experience = new Templater(DIR_TMPL . $this->module . "/add-experience-link-nct.tpl.php");
            $add_experience_parsed = $add_experience->parse();
            
            $add_education = new Templater(DIR_TMPL . $this->module . "/add-education-link-nct.tpl.php");
            $add_education_parsed = $add_education->parse();
            
            $add_skill = new Templater(DIR_TMPL . $this->module . "/add-skill-link-nct.tpl.php");
            $add_skill_parsed = $add_skill->parse();
            
            $add_language = new Templater(DIR_TMPL . $this->module . "/add-language-link-nct.tpl.php");
            $add_language_parsed = $add_language->parse();
            
            $add_licenses_endorsement = new Templater(DIR_TMPL . $this->module . "/add-licenses-endorsement-link-nct.tpl.php");
            $add_licenses_endorsement_parsed = $add_licenses_endorsement->parse();

            $add_airport = new Templater(DIR_TMPL . $this->module . "/add-airport-link-nct.tpl.php");
            $add_airport_parsed = $add_airport->parse();

            $actions_tpl = new Templater(DIR_TMPL . $this->module . "/actions-nct.tpl.php");
            $actions_tpl_parsed = $actions_tpl->parse();
            
            $fields = array("%TITLE%", "%CLASS%");
            $fields_replace = array("{LBL_UPDATE_USER_DETAILS}", "edit-user-details-icon");
            $actions = str_replace($fields, $fields_replace, $actions_tpl_parsed);
            $hide_action='';
            $class=$class_pic_oth='';
        } else {
            $user_actions = '';
            $follow_actions='';
            $hide_action='hidden';
            $user_actions_container_tpl = new Templater(DIR_TMPL . $this->module . "/user-actions-container-nct.tpl.php");
            $follow_actions_container_tpl = new Templater(DIR_TMPL . $this->module . "/follow-nct.tpl.php");
            $send_inmail_class = '';
            $send_inmail_url = SITE_URL . 'compose-message/' . encryptIt($this->user_id);
            $connected_user_ids = getConnections($this->user_id);
            $second_connected_user_ids = getSecondDegreeConnections($this->user_id, $this->current_user_id);
            $connection_status = '';
            if (is_array($connected_user_ids) && in_array($this->current_user_id, $connected_user_ids)) {
                $user_actions .= $this->addRemoveConnectionUrl($this->user_id, "remove_connection");
                $connection_level = LBL_FIRST;
                $send_inmail_title = LBL_SEND_MESSAGE;
                $send_inmail_text = '<i class="icon-mail-o"></i>';
                $connection_status = 'connected';
            } else {
                $query = "SELECT request_from FROM tbl_connections WHERE ( ( request_from = ? AND request_to = ? ) OR ( request_from = ? AND request_to = ? ) ) AND status = ? ";

                $checkIfRequestIsPending = $this->db->pdoQuery($query,array($this->user_id,$this->current_user_id,$this->current_user_id,$this->user_id,'s'))->result();
                if ($checkIfRequestIsPending) {
                    if ($checkIfRequestIsPending['request_from'] == $this->current_user_id) {
                        $user_actions.=$this->addRemoveConnectionUrl($this->user_id, "cancel_connection_request");
                        $connection_status = 'request_sent';
                    } else {
                        $user_actions .= $this->addRemoveConnectionUrl($this->user_id,"accept_reject_connection_request");
                        $connection_status = 'request_received';
                    }
                } else {
                    $user_actions .= $this->addRemoveConnectionUrl($this->user_id, "add_connection");
                    $connection_status = 'not_connected';
                }
                $send_inmail_title = LBL_SEND_INMAIL;
                $send_inmail_text = '<i class="icon-email"></i>';
            }
            if (is_array($second_connected_user_ids) && in_array($this->current_user_id, $second_connected_user_ids)) {
                $connection_level = LBL_SECOND;
            }
            $user_actions_container_tpl->set('actions', $user_actions);
            $user_actions_container_tpl_parsed = $user_actions_container_tpl->parse();
            $follow_actions_container_tpl->set('follow_actions', $follow_actions);
            $follow_actions_container_tpl_parsed = $follow_actions_container_tpl->parse();
            if($this->current_user_id>0)
                insertVisitors($this->current_user_id, $this->user_id);
            $class='hidden';
            $class_pic_oth="otheruser_picture_container";

        }
        $main_content->set('profile_picture_actions', $profile_picture_actions_parsed);
        $main_content->set('user_actions', $user_actions_container_tpl_parsed);
        $main_content->set('follow_actions', $follow_actions_container_tpl_parsed);
        $main_content->set('actions', $actions);
        $main_content->set('remove_from_connection_url', $remove_from_connection_url);
        $main_content->set('connections_url', $connections_url);
        $degree_of_connection_tpl_parsed = "";
        if ($connection_level != "") {
            $degree_of_connection_tpl = new Templater(DIR_TMPL . $this->module . "/degree-of-connection-nct.tpl.php");
            $degree_of_connection_tpl->set('degree_of_connection', $connection_level);
            $degree_of_connection_tpl_parsed = $degree_of_connection_tpl->parse();
        }
        $main_content->set('connection_level', $degree_of_connection_tpl_parsed);
        $main_content->set('add_experience_link', $add_experience_parsed);
        $main_content->set('add_education_link', $add_education_parsed);
        $main_content->set('add_skill_link', $add_skill_parsed);
        $main_content->set('add_licenses_endorsement', $add_licenses_endorsement_parsed);
        $main_content->set('add_airport_link', $add_airport_parsed);
        $main_content->set('add_language_link', $add_language_parsed);
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%USER_PROFILE_PICTURE%",
            "%USER_NAME_FULL%",
            "%CONNECTIONS_URL%",
            "%NO_OF_CONNECTIONS%",
            "%EXPERIENCES%",
            "%EDUCATIONS%",
            "%SKILLS%",
            "%LANGUAGES%",
            "%RIDHT_SIDEBAR%",
            "%ENCRYPTED_USER_ID%",
            "%FIRST_NAME%",
            "%LAST_NAME%",
            "%FORMATTED_ADDRESS%",
            "%HEADLINE%",
            "%INDUSTRY_NAME%",
            "%SEND_INMAIL_CLASS%",
            "%SEND_INMAIL_TITLE%",
            "%SEND_INMAIL_TEXT%",
            "%SEND_INMAIL_URL%",
            '%EXPERIENCES_CLASS%',
            '%EDUCATION_CLASS%',
            '%LANGUAGE_CLASS%',
            '%SKILL_CLASS%',
            '%VIEW_FULL_PROFILE_CLASS%',
            '%HIDE_IF_NOT_LOGGED%',
            "%ADD_CONNECTION_URL%",
            "%FOLLOW_TAG%",
            "%USER_ID%",
            "%USER_STATUS%",
            "%HIDE_LI%",
            "HIDE_SMALL",
            "HIDE_P",
            "%HIDE_ACTION%",
            "%EXPERIENCES_HIDE%",
            "%EDUCATION_HIDE%",
            "%LANGUAGE_HIDE%",
            "%SKILLS_HIDE%",
            "%NO_DATA_HIDE%",
            "%CLASS%",
            "%COVER_IMG%",
            "%CLASS_FOLLOW%",
            "%CLASS_PIC_OTH%",
            "%URL_INDUSTRY%",
            "%JOB_TITLE%",
            "%JOB_TITLE_URL%",
            "%COMPANY_NAME%",
            "%COMPANY_NAME_URL%",
            "%HEADLINE_DISPLAY%",
            "%LICENSES_ENDORSEMENTS%",
            "%PERESONAL_DETAILS%",
            "%USER_DOB%",
            "%GENDER%",
            "%AIRPORT%",
            "%ISREVIEWDISPLAY%",
            "%ISCONTAINSAIRPORT%",
            "%FERRY_PILOT_RATE_REVIEW%",
            "%HIDE_OWNER_FERRY_PILOT_REVIEWS%",
            "%ALREADY_REVIEW_ADDED%",
            "%REFERRAL_DETAILS%",
            "%HIDE_REFERRAL_FOR_OWNER%",
            "%GET_USER_LICENSES_LIST%",
            "%IS_FERRY_AND_ACCTIVE_SUBSCRIPTION_PLAN%"
        );
        $no_of_connections = getNoOfConnections($this->user_id);
        $connections_url = SITE_URL . "connection/" . encryptIt($this->user_id);
        $url_user_id = decryptIt(isset($_GET['user_id']) && $_GET['user_id']);
        $user_id = $this->current_user_id;
        $url = 'javascript:void(0);';
        $add_connection_url = SITE_URL . "people-you-may-know";
        if($no_of_connections > 0){
            $url = $connections_url;
        }    
        $hide_if_not_logged  = $skill_class  = $language_class = $education_class  = $experiences_class = '';    
        if($this->current_user_id == '' || $this->current_user_id == 0){
            $experiences_class = $education_class = $language_class = $skill_class = 'hide';
            $hide_if_not_logged = 'hide';
        } else{
            $view_full_profile_class = 'hide';
        }
        $getUserHeadline = getUserHeadline($this->user_id);
        $industry_name = $this->industry_name;
        $formatted_address = /*$this->address_line1.', '.$this->address_line2.', '.*/$this->formatted_address;
        if( $this->current_user_id>0){
            $status=$getstatus='';
            $follow_tag=LBL_FOLLOW;
            $class_follow='icon-check';
            $getstatus = getTableValue("tbl_follower", "status", array("follower_form" => $this->current_user_id,'follower_to'=>$this->user_id));
            if($getstatus != ''){
                $status=$getstatus;
                if($getstatus=='f'){
                $follow_tag=LBL_UNFOLLOW;
                $class_follow='icon-close';
                }
            }
        }
        $image_url = getImageURL("user_profile_picture", $this->user_id, "th4",$platform);
        $user_cover= getImageURL("user_cover_picture",$this->user_id,"th1",$platform);

        $hide_li=$hide_small=$hide_p='';
        if(($industry_name=='') && $formatted_address==''){
            $hide_li='hidden';
        }
        if($industry_name==''){
            $hide_small='hidden';
        }
        if($formatted_address==''){
            $hide_p='hidden';
        }
        $language=$this->getAddedLanguages($this->user_id);
        $experiences=$this->getAddedExperiences($this->user_id);
        $educations=$this->getAddedEducations($this->user_id);
        $licenses=$this->getAddedLicensesEndorsement($this->user_id);
        $airport=$this->getAddedAirport($this->user_id);
        $skill=$this->getAddedSkills($this->user_id);
        //$skill=$this->getAddedSkills($this->user_id);
        
        $language_hide=$experiences_hide=$educations_hide=$skill_hide=$licenses_hide= $airport_hide = '';
        
        if($language==''){
            $language_hide='hidden';
        }
        if($experiences==''){
            $experiences_hide='hidden';
        }
        if($educations==''){
            $educations_hide='hidden';
        }
        if($licenses==''){
            $licenses_hide='hidden';
        }
        if($skill==''){
            $skill_hide='hidden';
        }
        if($airport==''){
            $airport_hide='hidden';
        }
        $no_data_hide='hidden';
        if($language=='' && $experiences=='' && $educations=='' && $licenses == '' && $skill==''&& $airport == '' && $this->user_id != $_SESSION['user_id']){
            $no_data_hide='';
        }
        if($_SESSION['user_id'] == 0){
            $no_data_hide='hidden';
        }
        $industry_url=SITE_URL . "search/users?industries[]=".$this->industry_id."";
        $getUserHeadlineData = getUserHeadlineNew($this->user_id);
        $job_title=ucwords($getUserHeadlineData['job_title']);
        $job_title_url=SITE_URL . "search/users?keyword=".$getUserHeadlineData['job_title']."";;
        $company_name=ucwords($getUserHeadlineData['company_name']);
        if($getUserHeadlineData['company_type']=='r'){
            $comapany_name_url=SITE_URL."company/".$getUserHeadlineData['company_id'];

        }else{
            $comapany_name_url=SITE_URL . "search/users?company[]=".$getUserHeadlineData['company_id']."";
        }
        $headlineDisplay='';
        if(empty($getUserHeadlineData)){
            $headlineDisplay='hidden';
        }

        $personal_details=$this->personal_details;
       
        $user_DOB = convertDate('displayWeb',filtering($this->user_DOB, 'output', 'output'));
        $gender = ($this->gender=='m')?'Male':(($this->gender=='f')?'Female':'');

        $user_data1 = getTableValue("tbl_users", "isFerryPilot", array("id" => $this->user_id));
        if ($user_data1['isFerryPilot'] == 'y') {
            $isFerryPilotReviewDisplay = '';
        }else{
            $isFerryPilotReviewDisplay = 'hide';
        }
        //$isFerryPilotReviewDisplay = ($this->isFerryPilot == 'y') ? '' : 'hide';
        //print_r($isFerryPilotReviewDisplay);exit();
        $isAddAirportHide = '';
        $isAirport = getTableValue("tbl_user_airports", "id", array("user_id" => $this->current_user_id,'isActive'=>'y'));
        $isFerryPilotOwner = ($this->db_user_id == $_SESSION['user_id']) ? 'hide' : '';
        if($isAirport > 0){
            $isAddAirportHide = "hide";
        }else{
            $isAddAirportHide = "";
        }

        $already_given = getTableValue("tbl_ferry_pilot_rating", "id", array("sender_id" => $_SESSION['user_id'],'receiver_id' => $this->user_id));
        if ($already_given > 0) {
            $edit_user = 'hide';
        }else{
            $edit_user = '';
        }
        
        $hide_referral_for_owner = '';
        $hide_referral_for_owner = ($this->session_user_id == $this->user_id) ? 'hide' : '';

        $user_info = $this->db->select('tbl_users_licenses_endorsement', array('id'),array('user_id' => $this->user_id,'licenses_id' => '1','verification_status' => 'y'))->result();
        
        $plan_info = $this->db->select('tbl_subscription_history', array('id'),array('user_id' => $this->user_id,'plan_id' => FERRY_PILOT_PLAN_ID,'isActive' => 'y'))->result();
       
        $isFerrAndActiveSubscriptionPlan = '';
        if($user_info['id'] > 0 && $user_info != '' && $plan_info != '' && $plan_info['id'] > 0){
            $isFerrAndActiveSubscriptionPlan = '';
        }else{
            $isFerrAndActiveSubscriptionPlan = 'hide';
        }
       
        $fields_replace = array(
            $image_url,
            ucwords($this->first_name) . " " . ucwords($this->last_name),
            $url,
            $no_of_connections,
            $experiences,
            $educations,
            $skill,
            $language,
            $this->getRightSidebar(),
            encryptIt($this->current_user_id),
            $this->first_name,
            $this->last_name,
            $formatted_address,
            $getUserHeadline,
            ucwords($industry_name),
            $send_inmail_class,
            $send_inmail_title,
            $send_inmail_text,
            $send_inmail_url,
            $experiences_class,
            $education_class,
            $language_class,
            $skill_class,
            $view_full_profile_class,
            $hide_if_not_logged,
            $add_connection_url,
            $follow_tag,
            encryptIt($this->user_id),
            $status,
            $hide_li,
            $hide_small,
            $hide_p,
            $hide_action,
            $experiences_hide,
            $educations_hide,
            $language_hide,
            $skill_hide,
            $no_data_hide,
            $class,
            $user_cover,
            $class_follow,
            $class_pic_oth,
            $industry_url,
            $job_title,
            $job_title_url,
            $company_name,
            $comapany_name_url,
            $headlineDisplay,
            $licenses,
            $personal_details,
            $user_DOB,
            $gender,
            $airport,
            $isFerryPilotReviewDisplay,
            $isAddAirportHide,
            $this->getReviewList($this->user_id),
            $isFerryPilotOwner,
            $edit_user,
            $this->getReferralDetails($this->user_id),
            $hide_referral_for_owner,
            $this->getLicenseList($this->session_user_id),
            $isFerrAndActiveSubscriptionPlan
        );
        if($platform == 'app'){
            if($this->db_user_id>0){
                $name = $this->first_name . " " . $this->last_name;
                $headline = $getUserHeadline;
                $industry = $industry_name;
                $location = $formatted_address;
                $basic = array(
                    'name'=>$name,
                    'headline'=>$headline,
                    'industry'=>$industry,
                    'location'=>$location,
                    'image_url'=>$image_url,
                    'first_name'=>$this->first_name,
                    'last_name'=>$this->last_name,
                    'cover_img_url'=>$user_cover,
                    'job_title'=>$getUserHeadlineData['job_title'],
                    'company_name'=>$getUserHeadlineData['company_name'],
                    'company_id'=>$getUserHeadlineData['company_id'],
                    'industry_id'=>$this->industry_id,
                    'company_type'=>$getUserHeadlineData['company_type'],
                    'lable_at'=>AT
                );

                if($this->current_user_id>0 && $this->current_user_id != $this->user_id){
                    $mutual_connection = getCommonConnections($this->user_id, $this->current_user_id);
                    $basic['connection_level'] = $connection_level;
                    $basic['connection_status'] = $connection_status;
                    $basic['mutual_connection'] = count($mutual_connection);
                    $basic['follow_status']=$status;
                }

            } else {
                $basic = array();
            }
            $final_app_array = $basic;
            return $final_app_array;
        }
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
    public function addRemoveConnectionUrl($user_id, $case) {
        $final_result = "";
        $main_content = new Templater(DIR_TMPL . $this->module . "/add-remove-connection-url-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array("%TITLE%","%TEXT%","%DATA-VALUE%","%CLASS%","%FA_CLASS%");
        if ($case == "add_connection") {
            $fields_replace = array(
                LBL_CONNECT,
                '<i class=" icon-follower"></i>',
                encryptIt($user_id),
                "send-connection-request",
                "icon-check",
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        } else if ($case == "remove_connection") {
            $fields_replace = array(
                LBL_REMOVE_FROM_CONNECTION,
                '<i class=" icon-connection-close"></i>',
                encryptIt($user_id),
                "remove-from-connection",
                "icon-close",
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        } else if ($case == "cancel_connection_request") {
            $fields_replace = array(
                LBL_CANCEL_CONNECTION_REQUEST,
                '<i class=" icon-unfollower"></i>',
                encryptIt($user_id),
                "cancel-connection-request",
                "icon-close",
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        } else if ($case == "accept_reject_connection_request") {
            $fields_replace = array(
                LBL_ACCEPT,
                '<i class="icon-follower"></i>',
                encryptIt($user_id),
                "accept-connection-request",
                "icon-check",
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
            $fields_replace = array(
                LBL_REJECT,
                '<i class=" icon-connection-close"></i>',
                encryptIt($user_id),
                "reject-connection-request",
                "icon-close",
            );
            $final_result .= str_replace($fields, $fields_replace, $main_content_parsed);
        }
        return $final_result;
    }
    public function getCommonConnectionsUL($user_id) {
        $content = NULL;
        $common_connection_array = array();
        $common_connection_html = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/common-connection-ul-nct.tpl.php");
        $common_connection_html = NULL;
        $fields = array("%VIEW_ALL_LINK%");
        $common_connection_array = getCommonConnections($user_id, $this->session_user_id);
        $common_connection_count = count($common_connection_array);

        $common_connection_array = getCommonConnections($user_id, $this->session_user_id, true, 1, 2);
        if ($common_connection_array) {
            foreach ($common_connection_array as $key_connection => $value_connection) {
                $common_connection_html .= $this->getCommonConnectionSection($value_connection);
            }
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
            $message = LBL_NO_COMMON_CONNECTIONS_FOUND;
            $no_result_found_tpl->set('no_data_message', $message);
            $common_connection_html .= $no_result_found_tpl->parse();
        }
        $fields_replace = array(SITE_URL . "common-connection/" . encryptIt($user_id));
        $hidden_var = $common_connection_count < 3 ? "hidden" : "";
        $main_content->set('common_connection', $common_connection_html);
        $main_content->set('hidden_var', $hidden_var);
        $main_content_parsed = $main_content->parse();
        $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function removeConnection($session_user_id, $user_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("DELETE FROM tbl_connections WHERE (request_from = ? AND request_to = ?) OR (request_from = ? AND request_to = ?)", array($session_user_id, $user_id, $user_id, $session_user_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = LBL_CONNECTION_REMOVED_SUCCESSFULLY;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function getSimilarProfileUL($user_id) {
        $content = NULL;
        $similar_profile_array = array();
        $similar_profile_html = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/similar-profile-ul-nct.tpl.php");
        $fields = array();
        if($this->session_user_id>0)
            $similar_profile_array = getSimilarProfiles($user_id, $this->session_user_id);
        $view_all_link_tpl_parsed = "";
        if ($similar_profile_array) {
            foreach ($similar_profile_array as $key => $value) {
                $similar_profile_html .= $this->getSimilarProfilesSection($value);
            }
            $view_all_link_tpl = new Templater(DIR_TMPL . $this->module . "/view-all-link-nct.tpl.php");
            $view_all_link_tpl->set('view_all_link', SITE_URL."search/users?relationship[]=2&industries[]=".$this->industry_id);
            $view_all_link_tpl_parsed = $view_all_link_tpl->parse();
        } else {
            $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
            $message = LBL_NO_SIMILAR_PROFILE_FOUND;
            $no_result_found_tpl->set('no_data_message', $message);
            $similar_profile_html .= $no_result_found_tpl->parse();
        }
        $fields_replace = array();
        $main_content->set('similar_profiles', $similar_profile_html);
        $main_content->set('view_all_link', $view_all_link_tpl_parsed);
        $main_content_parsed = $main_content->parse();
        $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function getSimilarProfilesSection($user_id) {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/similar-profile-li-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $user_info = $this->db->select('tbl_users', array('id,first_name,last_name'), array('id' => $user_id))->result();
        $fields = array(
            "%USER_ID%",
            "%USER_NAME%",
            "%USER_IMG%",
            "%USER_HEAD_LINE%",
            "%USER_URL%",
            "%UNIQUE_IDENTIFIER%",
            "%SESSION_USER_ID%",
            "%ENCRYPTED_USER_ID%",
        );
        $fields_replace = array(
            (filtering($user_info['id'], 'output', 'int')),
            ucwords(filtering($user_info['first_name'],'output'))." ".ucwords(filtering($user_info['last_name'],'output')),
            getImageURL('user_profile_picture',$user_id,'th3'),
            getUserHeadline($user_id),
            get_user_profile_url($user_id),
            filtering($user_info['id'], 'output', 'int') . uniqid(),
            $this->session_user_id,
            encryptIt(filtering($user_info['id'], 'output', 'int')),
        );
        $content .= str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function addSkills($platform='web') {
        $response = array();
        $response['status'] = false;
        $skill_details_array['skill_id'] = decryptIt($_POST['skill_id']);
        $skill_details_array['user_id'] = $this->session_user_id;
        $skill_details_array['added_on'] = date("Y-m-d H:i:s");
        $id = $this->db->insert("tbl_user_skills", $skill_details_array)->getLastInsertId();
        if ($id) {
            $response['status'] = true;
            $response['success'] = SUCCESS_LBL_SKILL_HAS_ADDED_SUCCESSFULLY;
            $response['skills'] = $this->getAddedSkills($this->session_user_id);
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function add_skills_multiple($platform='web') {
        $response = array();
        $response['status'] = false;
        $skill_details_array['user_id'] = (($platform == 'app') ? $_POST['user_id'] : $this->session_user_id);
        $skill_details_array['added_on'] = date("Y-m-d H:i:s");
        for($i=0; $i<count($_POST['skill_id']); $i++){
            $skill_details_array['skill_id'] = $_POST['skill_id'][$i];
            $id = $this->db->insert("tbl_user_skills", $skill_details_array)->getLastInsertId();
        }
        if ($id) {
            $response['status'] = true;
            $response['success'] = SUCCESS_LBL_SKILL_HAS_ADDED_SUCCESSFULLY;
            $response['skills'] = $this->getAddedSkills($this->session_user_id);
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function add_airports_multiple($platform='web') {
        $response = array();
        $post_user_id = (($platform == 'app') ? $_POST['user_id'] : $this->session_user_id);
        $response['status'] = false;
        $previous_airport_id = '';
        if (isset($_POST['airport_id']) && $_POST['airport_id'] != '') {
            $previous_airport_id = decryptIt($_POST['airport_id']);
        } 
        $skill_details_array['user_id'] = $_SESSION['user_id'];
        $skill_details_array['airport_id'] = $_POST['airport_id_hidden'];
        if ($previous_airport_id > 0) {
            $skill_details_array['updated_on'] = date("Y-m-d H:i:s");
            $affectedRows = $this->db->update("tbl_user_airports", $skill_details_array, array("id" => $previous_airport_id))->affectedRows();
            if ($affectedRows > 0 || $affectedRows == 0) {
                $response['status'] = true;
                $response['success'] = SUCCESS_YOUR_LICENSE_DETAILS_HAS_BEEN_UPDATED_SUCCESSFULLY;
                $response['airports'] = $this->getAddedAirport($this->user_id);
                return $response;
            } else {
                $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
                return $response;
            }     
        }else{
            $skill_details_array['added_on'] = date("Y-m-d H:i:s");
            $id = $this->db->insert("tbl_user_airports", $skill_details_array)->getLastInsertId();
            if ($id) {
                $response['status'] = true;
                $response['success'] = SUCCESS_LBL_AIRPORT_HAS_ADDED_SUCCESSFULLY;
                $response['airports'] = $this->getAddedAirport($this->user_id);
            } else {
                $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
            }
        }
        return $response;
    }
    public function getAddedLanguages($user_id,$platform='web') {
        $final_result = '';
        $query = "SELECT ul.id as user_language_id, l.id as language_id, l.language 
                    FROM tbl_user_languages ul
                    LEFT JOIN tbl_languages l ON l.id = ul.language_id
                    WHERE ul.user_id = ? /*AND l.status = 'a'*/ ";
        $languages = $this->db->pdoQuery($query,array($user_id))->results();
        if ($languages) {
            $actions = '';
            $single_language_li_tpl = new Templater(DIR_TMPL . $this->module . "/language-li-nct.tpl.php");
            if ($this->user_id == $this->session_user_id) {
                $remove_language_tpl = new Templater(DIR_TMPL . $this->module . "/remove-language-nct.tpl.php");
                $actions = $remove_language_tpl->parse();
            }
            $fields = array("%LANGUAGE_ID_ENCRYPTED%","%LANGUAGE%");
            for ($i = 0; $i < count($languages); $i++) {
                $language = filtering($languages[$i]['language']);
                $fields_replace = array(
                    encryptIt($languages[$i]['user_language_id']),
                    $language
                );
                $single_language_li_tpl->set('language_actions', str_replace(array("%ENC_LANGUAGE_ID%"), array(encryptIt($languages[$i]['language_id'])), $actions));
                $single_language_li_tpl_parsed = $single_language_li_tpl->parse();

                if($platform == 'app'){
                    $array[] = array('language_id'=>$languages[$i]['language_id'],'language_title'=>$language);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_language_li_tpl_parsed);
                }

            }
        } else {
            if($user_id == $_SESSION['user_id']){
                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_YOU_HAVE_NOT_ADDED_ANY_LANGUAGES_YET);

                $final_result = $no_data_tpl->parse();
            }else{
                $final_result='';
            }
        }
        if($platform == 'app'){
            return $final_result = $array;
        } else {
            return $final_result;
        }
    }
    public function getLanguageForm() {
        $response = array();
        $response['status'] = false;
        $final_result = '';
        $language_form_tpl = new Templater(DIR_TMPL . $this->module . "/language-form-nct.tpl.php");
        $language_form_tpl_parsed = $language_form_tpl->parse();
        $response['status'] = true;
        $response['language_form'] = $language_form_tpl_parsed;
        return $response;
    }
    public function getLanguagesForSuggestion($user_id, $language,$language_id,$platform='web') {
        $final_result = array();
        $query = "SELECT ul.id as user_language_id, l.id as language_id, l.language 
                    FROM tbl_user_languages ul
                    LEFT JOIN tbl_languages l ON l.id = ul.language_id 
                    WHERE ul.user_id = ? ";
        $user_languages = $this->db->pdoQuery($query,array($user_id))->results();
        $user_languages_imploded = '';
        $user_languages_array = array();
        if ($user_languages) {
            for ($i = 0; $i < count($user_languages); $i++) {
                $user_languages_array[] = filtering($user_languages[$i]['language_id'], 'input', 'int');
            }
            $user_languages_imploded = implode(",", $user_languages_array);
        }
        if($language_id == "" || $language_id == "null")
            $language_id ='0';
        $not_in_query = " AND id NOT IN(".$language_id.")";
        if ($user_languages_imploded != '') {
            $not_in_query .= " AND id NOT IN ( " . $user_languages_imploded . " ) ";
        }
        $query = "SELECT id,language FROM tbl_languages WHERE language LIKE '%" . $language . "%' AND status = ? " . $not_in_query . " ORDER BY id DESC LIMIT 0, 10 ";
        $get_languages = $this->db->pdoQuery($query,array('a'))->results();
        if ($get_languages) {
            for ($i = 0; $i < count($get_languages); $i++) {
                $single_language = array();
                $single_language['language_id'] = encryptIt(filtering($get_languages[$i]['id'], 'output', 'int'));
                $single_app_array['language_id'] = $single_language['language_id_orig'] = filtering($get_languages[$i]['id'], 'output', 'int');
                $single_app_array['language_title'] = $single_language['language'] = filtering($get_languages[$i]['language']);
                if($platform == 'app'){
                    $final_result[] = $single_app_array;
                } else {
                    $final_result[] = $single_language;
                }
            }
        }
        if (empty($final_result)) {
            $single_language['language_id'] = 0;
            $single_language['language'] = LBL_NO_RESULTS_FOUND;
            if($platform == 'app'){
                $final_result = array('languages'=>array(),'status'=>'success','message'=>$single_language['language']);
            } else {
                $final_result[] = $single_language;
            }
        } else {
            if($platform == 'app'){
                $final_result = array('languages'=>$final_result,'status'=>'success','message'=>LBL_SUCCESS_LANGUAGE_LISTING);
            }
        }
        return $final_result;
    }
    public function addLanguages() {
        $response = array();
        $response['status'] = false;
        $language_details_array['language_id'] = decryptIt($_POST['language_id']);
        $language_details_array['user_id'] = $this->session_user_id;
        $language_details_array['added_on'] = date("Y-m-d H:i:s");
        $id = $this->db->insert("tbl_user_languages", $language_details_array)->getLastInsertId();
        if ($id) {
            $response['status'] = true;
            $response['success'] = SUCCESS_LANGUAGE_HAS_BEEN_ADDED_SUCCESSFULLY;
            $response['languages'] = $this->getAddedLanguages($this->session_user_id);
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function addLanguagesMultiple($platform='web'){
        $response = array();
        $response['status'] = false;
        $language_details_array['user_id'] = (($platform == 'app') ? $_POST['user_id'] : $this->session_user_id);
        $language_details_array['added_on'] = date("Y-m-d H:i:s");

        for($i=0; $i<count($_POST['language_id']); $i++){
            $language_details_array['language_id'] = $_POST['language_id'][$i];
            $id = $this->db->insert("tbl_user_languages", $language_details_array)->getLastInsertId();    
        }
        if ($id) {
            $response['status'] = true;
            $response['success'] = SUCCESS_LANGUAGE_HAS_BEEN_ADDED_SUCCESSFULLY;
            $response['languages'] = $this->getAddedLanguages($this->session_user_id);
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;   
    }
    public function removeLanguage($language_id, $user_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("DELETE FROM tbl_user_languages WHERE (user_id = ? AND language_id = ?) ", array($user_id, $language_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = SUCCESS_LANGUAGE_REMOVED_SUCCESSFULLY;
            //lang count
            $count = getTableValue("tbl_user_languages","count(id)",array("user_id"=>$user_id));
            $response['msg'] = ($count > 0) ? '' : ERROR_YOU_HAVE_NOT_ADDED_ANY_LANGUAGES_YET;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function removeSkill($skill_id, $user_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("DELETE FROM tbl_user_skills WHERE (user_id = ? AND skill_id = ?) ", array($user_id, $skill_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = SUCCESS_SKILL_HAS_BEEN_REMOVED_SUCCESSFULLY;
            //lang count
            $count = getTableValue("tbl_user_skills","count(id)",array("user_id"=>$user_id));
            $response['msg'] = ($count > 0) ? '' : ERROR_YOU_HAVE_NOT_ADDED_ANY_SKILL;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function updateUserDetails($platform='web') {
        $user_id = (($platform == 'app') ? $_POST['user_id'] : $this->session_user_id);
        //_print_r($_POST);exit();
        $response = array();
        $response['status'] = false;
        
        $first_name     = filtering($_POST['first_name']);
        $last_name      = filtering($_POST['last_name']);
        $user_email     = isset($_POST['user_email']) ? filtering($_POST['user_email']) : '';
        $contact_no     = isset($_POST['contact_no']) ? filtering($_POST['contact_no']) : '';
        $user_location  = filtering($_POST['user_location']);
        $formatted_address = filtering($_POST['formatted_address']);
        $address1       = filtering($_POST['address1']);
        $address2       = filtering($_POST['address2']);
        $country        = filtering($_POST['country']);
        $state          = filtering($_POST['state']);
        $city1          = filtering($_POST['city1']);
        $city2          = filtering($_POST['city2']);
        $postal_code    = filtering($_POST['postal_code']);
        $latitude       = filtering($_POST['latitude']);
        $longitude      = filtering($_POST['longitude']);
        $personal_details      = filtering($_POST['personal_details']);
        $address_line1      = filtering($_POST['address_line1']);
        $address_line2      = filtering($_POST['address_line2']);
        $user_DOB      = filtering($_POST['user_DOB']);
        $gender      = filtering($_POST['gender']);
        $is_ferry1      = isset($_POST['is_ferry1']) ? $_POST['is_ferry1'] : 'n';
        $user_details_array  = array(
            "first_name"     => $first_name,
            "last_name"      => $last_name,
            "email_address"  => $user_email,
            "phone_no"       => $contact_no,
            "isFerryPilot"   => $is_ferry1,
            "personal_details"       => $personal_details,
            "address_line1"       => $address_line1,
            "address_line2"       => $address_line2,
            "user_DOB"       => $user_DOB,
            "gender"       => $gender,
            "date_updated"   => date("Y-m-d H:i:s")
        );
        $select=$this->db->select('tbl_locations',array('id'),array("formatted_address"=>$formatted_address,"address1"=>$address1,"address2"=>$address2,"country"=>$country,"state"=>$state,"city1"=>$city1,"city2"=>$city2,"postal_code"=>$postal_code,"latitude"=>$latitude,"longitude"=>$longitude))->result();
        if($select['id']>0){
            $location_id = $select['id'];
        } else {
            $user_location_details_array = array("formatted_address" => $formatted_address,"address1" => $address1,"address2" => $address2,"country" => $country,"state" => $state,"city1" => $city1,"city2" => $city2,"postal_code" => $postal_code,"latitude" => $latitude,"longitude" => $longitude,"date_updated" => date("Y-m-d H:i:s"));
            $user_location_details_array['date_added'] = date("Y-m-d H:i:s");
            $location_id = $this->db->insert("tbl_locations", $user_location_details_array)->getLastInsertId();
        }
        if ($location_id > 0) {
            $user_details_array['location_id'] = $location_id;
            $affectedRows = $this->db->update("tbl_users", $user_details_array, array('id' => $user_id))->affectedRows();
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $response['status'] = true;
            $response['success'] = SUCCESS_USER_DETAIL_HAS_BEEN_UPDATED_SUCCESSFULLY;
            $response['user_details'] = self::__construct();
            return $response;
        } else {
            $response['error'] = ERROR_OOPS_SOMETHING_WENT_WRONG_TRY_AFTER_SOME_TIME;
            return $response;
        }
        return $response;
    }
    public function deleteExperience($experience_id) {
        $response = array();
        $response['status'] = false;
        $company_id = getTableValue("tbl_user_experiences","company_id",array("id"=>$experience_id));

        $this->db->pdoQuery("DELETE FROM tbl_companies WHERE (id = ? AND company_type = ?) ", array($company_id,'e'))->affectedRows();

        $affectedRows = $this->db->pdoQuery("DELETE FROM tbl_user_experiences WHERE (id = ?) ", array($experience_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = SUCCESS_USER_EXPERIENCE_REMOVED_SUCCESSFULLY;
            $count = getTableValue("tbl_user_experiences","count(id)",array("user_id"=>$this->session_user_id));
            $response['msg'] = ($count > 0) ? '' : ERROR_YOU_HAVE_NOT_ADDED_ANY_EXPERIENCE;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function deleteEducation($education_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("DELETE FROM tbl_user_education WHERE (id = ?) ", array($education_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = SUCCESS_EDUCATION_REMOVED_SUCCESSFULLY;
            $count = getTableValue("tbl_user_education","count(id)",array("user_id"=>$this->session_user_id));
            $response['msg'] = ($count > 0) ? '' : ERROR_YOU_HAVE_NOT_ADDED_ANY_EDUCATION;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function deleteLicense($licenses_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("DELETE FROM tbl_users_licenses_endorsement WHERE (id = ?) ", array($licenses_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = SUCCESS_LICENSE_REMOVED_SUCCESSFULLY;
            $count = getTableValue("tbl_users_licenses_endorsement","count(id)",array("user_id"=>$this->session_user_id));
            $response['msg'] = ($count > 0) ? '' : ERROR_YOU_HAVE_NOT_ADDED_ANY_LICENSE;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function deleteAirport($airport_id) {
        $response = array();
        $response['status'] = false;
        $affectedRows = $this->db->pdoQuery("DELETE FROM tbl_user_airports WHERE (id = ?) ", array($airport_id))->affectedRows();
        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = SUCCESS_HOME_AIRPORT_REMOVED_SUCCESSFULLY;
            $count = getTableValue("tbl_user_airports","count(id)",array("user_id"=>$this->session_user_id));
            $response['msg'] = ($count > 0) ? '' : ERROR_YOU_HAVE_NOT_ADDED_ANY_HOME_AIRPORT;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function followuser($user_id,$status,$current_user_id) {
        $response = array();
    
        $response['status'] = false;
        $getid = getTableValue("tbl_follower", "id", array("follower_form" =>$current_user_id,'follower_to'=>$user_id));
        if($getid !=''){
                $user_detail['follower_form'] = $current_user_id;
                $user_detail['follower_to'] = $user_id;
                $user_detail['status'] = $status;
                $user_detail['updateon'] = date("Y-m-d H:i:s");
                $row=$this->db->update("tbl_follower", $user_detail, array("follower_form" =>$current_user_id,'follower_to'=>$user_id))->affectedRows();
                if($status=='f'){
                    $notificationArray = array(
                        "user_id" => $user_id,
                        "type" => "fu",
                        "action_by_user_id" => $current_user_id,
                        "added_on" => date("Y-m-d H:i:s"),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                     //For email notification
                    $notificationStatus = getTableValue("tbl_notification_settings", "follow_user", array("user_id" => $user_id));
                    /*$company_name = getTableValue("tbl_companies", "company_name", array("id" => $company_id));*/
                    if ($notificationStatus == 'y') {
                        $from_user = getTableValue("tbl_users", "first_name", array("id" => $current_user_id));
                        $to_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                        $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
                        $arrayCont['greetings'] = $to_user;
                        $arrayCont['from_user'] = $from_user;
                       generateEmailTemplateSendEmail("follow_user", $arrayCont, $email_address);
                    }
                    /* Push notification */
                    $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$current_user_id))->result();
                    $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                    $push_data = array(
                        'user_name'=>$push_user_name,
                        'notification_id'=>$notification_id
                    );
                    set_notification($user_id,'fu',$push_data);
                }

                if ($row) {
                    $response['status'] = true;
                    if($status=='f')
                        $response['success'] = FOLLOWED_SUCCESSFULLY;
                    else
                        $response['success'] =UNFOLLOWED_SUCCESSFULLY;
                } else {
                $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
                }
                
        }else{
           $user_detail['follower_form'] = $current_user_id;
           $user_detail['follower_to'] = $user_id;
           $user_detail['status'] = $status;
           $user_detail['addon'] = date("Y-m-d H:i:s");
           $user_detail['updateon'] = date("Y-m-d H:i:s");

            $id = $this->db->insert("tbl_follower", $user_detail)->getLastInsertId();
            if($status=='f'){
                    $notificationArray = array(
                        "user_id" => $user_id,
                        "type" => "fu",
                        "action_by_user_id" => $current_user_id,
                        "added_on" => date("Y-m-d H:i:s"),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                     //For email notification
                    $notificationStatus = getTableValue("tbl_notification_settings", "follow_user", array("user_id" => $user_id));
                    /*$company_name = getTableValue("tbl_companies", "company_name", array("id" => $company_id));*/
                    if ($notificationStatus == 'y') {
                        $from_user = getTableValue("tbl_users", "first_name", array("id" => $this->session_user_id));
                        $to_user = getTableValue("tbl_users", "first_name", array("id" => $user_id));
                        $email_address = getTableValue("tbl_users", "email_address", array("id" => $user_id));
                        $arrayCont['greetings'] = $to_user;
                        $arrayCont['from_user'] = $from_user;
                        generateEmailTemplateSendEmail("follow_user", $arrayCont, $email_address);
                    }
                    /* Push notification */
                    $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$current_user_id))->result();
                    $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                    $push_data = array(
                        'user_name'=>$push_user_name,
                        'notification_id'=>$notification_id
                    );
                    set_notification($user_id,'fu',$push_data);
                }
            if ($id) {
            $response['status'] = true;
            $response['success'] = FOLLOWED_SUCCESSFULLY;
            } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
            } 
        }
        
        return $response;
    }
    public function removeFollowing($user_id,$session_user_id) {
        $response = array();
        $response['status'] = false;
        
        $row=$this->db->update("tbl_follower", array('status'=>'uf'), array("follower_form" =>$session_user_id,'follower_to'=>$user_id))->affectedRows();       
        if ($row && $row > 0) {
            $response['status'] = true;
            $response['success'] = UNFOLLOWED_SUCCESSFULLY;
        } else {
            $response['error'] = ERROR_THERE_SEEMS_TO_BE_ISSUE_TRY_AFTER_SOME_TIME;
        }
        return $response;
    }
    public function storeFerryPilotRateReview($rating,$desc){
        $response = array();
        $response['status'] = false;

        $send_arr = array();
        
        $send_arr['sender_id'] = $_SESSION['user_id'];
        $send_arr['receiver_id'] = $this->user_id;
        $send_arr['review_description'] = $desc;
        $send_arr['rating'] = $rating;
        $send_arr['createdAt'] = date("Y-m-d H:i:s");
       // print_r($send_arr);exit();
        $lastInsertId = $this->db->insert("tbl_ferry_pilot_rating", $send_arr)->getLastInsertId();        

        if($lastInsertId > 0){
            $response['status'] = true;
            $response['redirect_url'] = SITE_URL ."profile/".$this->user_id;
            $response['success'] = SUCCESS_COMPANY_RATE_REVIEW_MESSAGE;
            return $response;
        }else{
            $response['status'] = false;
            $response['redirect_url'] = SITE_URL ."profile/".$this->user_id;
            $response['err'] = ERROR_COMPANY_RATE_REVIEW_MESSAGE;
            return $response;
        }
    }
    public function getAirportsForSuggestion1($user_id, $airport_name='',$platform='web') {
        $final_result = array();
        
        if($platform=='app'){
            $query = "SELECT id,airport_name FROM tbl_airport WHERE status = ? ORDER BY id DESC ";
        }else{
            $query = "SELECT id,airport_name FROM tbl_airport WHERE airport_name LIKE '%" . $airport_name . "%' AND status = ? ORDER BY id DESC ";
        }    
        $where_arr=array('a');
        
        if($platform == 'web'){
            $query .="LIMIT 0, 10";
        }
        $airports = $this->db->pdoQuery($query,$where_arr)->results();
        //print_r($airports);exit();
        if ($airports) {
            for ($i = 0; $i < count($airports); $i++) {
                $single_company = array();
                if($platform == 'app'){
                    $single_company['airport_id'] = filtering($airports[$i]['id'], 'output', 'int');
                } else {
                    $single_company['airport_id'] = filtering($airports[$i]['id'], 'output', 'int');
                }
                $single_company['airport_name'] = filtering($airports[$i]['airport_name']);
                $final_result[] = $single_company;
            }
        }
        if (empty($final_result)) {
            if($platform == 'app'){
                $final_result=array('airports'=>array(),'status'=>'success','message'=>LBL_NO_RESULTS_FOUND);
            }
        } else {
            if($platform == 'app'){
                $final_result = array('airports'=>$final_result,'status'=>'success','message'=>LBL_SUCCESS_COMPANIES_LISTING);
            }
        }
        return $final_result;
    }
    public function requestForAirportAddition($user_id,$requested_airport_name,$platform='web'){
        $response = array();
        $response['status'] = false;
        //print_r($requested_airport_name);exit();
        if($requested_airport_name != ''){
            $add_airport = array(
                "user_id"      => $user_id,
                "country_id"   => '254',
                "state_id"     => '122',
                "city_id"      => '10',
                "airport_name_".$this->lId => $requested_airport_name,
                "adminApproval"=> 'p',
                "status"       => 'd',
                "createdAt"    => date("Y-m-d H:i:s"),
            );
            $airport_id = $this->db->insert("tbl_airport", $add_airport)->getLastInsertId();
            if ($airport_id > 0) {
                $add_user_airport = array(
                    "user_id"      => $user_id,
                    "airport_id"   => $airport_id,
                    "isActive"     => 'n',
                    "isAdminApprove"=>'n',
                    "added_on"      => date("Y-m-d H:i:s"),
                );
                $user_airport_id = $this->db->insert("tbl_user_airports", $add_user_airport)->getLastInsertId();

                $data = array(
                    "admin_id" => '1',
                    "entity_id"=> $user_id,
                    "type"     => 'raa',
                    "is_notified"=> 'y',
                    "date_added" => date('Y-m-d H:i:s'),
                );
                //print_r($data);exit();            
                $this->db->insert('tbl_admin_notifications', $data);

                $response['status'] = 'true'; 
                $response['message'] =SUCCESS_YOUR_AIRPORT_HAS_BEEN_INSERTED_SUCCESSFULLY_AND_ADMIN_REQUEST;
            }else{
                $response['status'] = 'false'; 
                $response['message'] = ERROR_YOUR_AIRPORT_HAS_BEEN_INSERTED_SUCCESSFULLY;
            }
        }else{
            $response['status'] = 'false'; 
            $response['message'] = ERROR_AIRPORT_NAME_IS_EMPTY;
        }
        return $response;
    }
    public function getReviewList($user_id){
        $final_result= $hide_edit_link = "";
        $user_img=DEFAULT_USET_IMAGE;

        $main_content = new MainTemplater(DIR_TMPL . $this->module . "/ferry_pilot_rate_review-nct.tpl.php");
        
        $main_content = $main_content->parse();

        $fields=array("%RID%","%USER_NAME%","%USER_IMG%","%REVIEW_DESC%","%USERURL%","%REVIEW_POSTED_DATE%","%FINAL_RESULT1%","%COMPANY_ID%","%HIDE_EDIT_LINK%");

        $rate_data = $this->db->pdoQuery("SELECT fr.*,u.id as userId,u.first_name,u.last_name,u.cover_photo FROM tbl_ferry_pilot_rating as fr LEFT JOIN tbl_users as u ON u.id = fr.sender_id WHERE fr.receiver_id = '".$user_id."' ORDER by fr.id DESC");
        $qryRes = $rate_data->results();
      //echo "<pre>";print_r($qryRes);exit();
        $totalRes = $rate_data->affectedRows();
        if($totalRes > 0){
            foreach($qryRes as $fetchRes){
                $firstName = isset($fetchRes['first_name']) ? $fetchRes['first_name']:'-';
                $lastName  = isset($fetchRes['last_name']) ? $fetchRes['last_name']:'-';
                $user_name = $firstName.' '.$lastName;
                $user_img = DEFAULT_USET_IMAGE;
                if($fetchRes['cover_photo']!="" && file_exists(DIR_UPD."user_cover-nct/".$fetchRes['userId']."/th1_".$fetchRes['cover_photo'])){
                    $user_img = SITE_UPD."user_cover-nct/".$fetchRes['userId']."/th1_".$fetchRes['cover_photo'];
                }
                if($fetchRes['userId']!="")
                {
                    $userurl = get_user_profile_url($fetchRes['userId']);
                }

                $review_desc= isset($fetchRes['review_description']) ? $fetchRes['review_description'] : '-';
                $review_posted_date = isset($fetchRes['createdAt']) ? date ("d M, Y", strtotime($fetchRes['createdAt'])) : '-';
                $star_rate = isset($fetchRes['rating']) ? $fetchRes['rating'] : 0;
                $hide_edit_link = ($_SESSION['user_id'] == $fetchRes['sender_id']) ? '' : 'hide';
                // echo $_SESSION['user_id'];
                // echo $fetchRes['userId'];
                $final_result1 = $this->getRating($star_rate);
                $replace=array($fetchRes['id'],$user_name,$user_img,$review_desc,$userurl,$review_posted_date,$final_result1,$fetchRes['sender_id'],$hide_edit_link);
                
                $final_result.=str_replace($fields,$replace,$main_content);
            }
            //exit();
        }else{
            $final_result.='<div class="tbody"><div class="td"></div><div class="td"></div><div class="td">No review added.</div><div class="td"></div><div class="td"></div></div>';
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
    public function getEditReviewModal($receiver_id=0,$rate_id=0){
      $final_result='';

      $main_content = new MainTemplater(DIR_TMPL . $this->module . "/edit-ferry-pilot-review-form-nct.tpl.php");
      $main_content = $main_content->parse();
      $fields=array("%ID%","%REVIEW%","%RATING%","%SENDER_ID%","%RECEIVER_ID%","%COMPANY_ID%");

      $qryRes=$this->db->pdoQuery("SELECT * FROM tbl_ferry_pilot_rating WHERE id = '".$rate_id."' ")->result();
      $affectedRows=$this->db->pdoQuery("SELECT * FROM tbl_ferry_pilot_rating WHERE id = '".$rate_id."'")->affectedRows(); 
      //print_r($qryRes);exit;
      if($affectedRows>0){
        $fetchRes=$qryRes;
        //print_r($fetchRes['review_description']);exit();
        $replace=array($fetchRes['id'],$fetchRes['review_description'],$fetchRes['rating'],$fetchRes['sender_id'],$fetchRes['receiver_id'],$this->current_user_id);
      }else{
        $replace=array('','',0,$rate_id);
      }

      $final_result=str_replace($fields, $replace, $main_content);

      return $final_result;
    }
    public function UpdateRateReview($sender_id,$rating,$desc,$receiver_id,$rate_id){
        $response = array();
        $response['status'] = false;
        
        $review_data=array();

        $reviewId=getTableValue("tbl_ferry_pilot_rating","id",array("receiver_id"=>$receiver_id,"sender_id" => $sender_id));
        $review_data['sender_id']=$_SESSION['user_id'];
        $review_data['receiver_id']=$receiver_id;
        $review_data['review_description']=$desc;
        $review_data['rating']=$rating;
       
        //echo $reviewId;
        //print_r($review_data);exit();
        if($reviewId>0){
            $review_data['updatedAt']=date('Y-m-d H:i:s');
            $this->db->update('tbl_ferry_pilot_rating',$review_data,array('id'=>$rate_id));
            $response['status'] = "suc";
            $response['redirect_url'] = SITE_URL ."profile/".$receiver_id;
            $response['message'] = SUCCESS_COMPANY_UPDATE_RATE_REVIEW_MESSAGE;
        }else{
            $review_id=$this->db->insert('tbl_ferry_pilot_rating',$review_data)->getLastInsertId();
            if($review_id > 0){
                $response['status'] = "suc";
                $response['redirect_url'] = SITE_URL ."profile/".$receiver_id;
                $response['message'] = SUCCESS_COMPANY_RATE_REVIEW_MESSAGE;
            }
        }
        return $response;
    }
    public function getInviteUserList($user_id, $currentpage = 1, $call_from_ajax = false, $keyword = '',$platform = 'web') 
    {

        $final_result = '';
        $connection_html = NULL;
        $common_connection_array = array();
        $next_available_records = 0;
        $limit = NO_OF_CONNECTION_PER_PAGE;
        $offset = ($currentpage - 1 ) * $limit;
        
        if($call_from_ajax) {
            $main_content = new Templater(DIR_TMPL . $this->module . "/invite-user-ajax-nct.tpl.php");
        }else{
            $main_content = new Templater(DIR_TMPL . $this->module . "/invite-user-nct.tpl.php");
        }
        if ($keyword != '') {
             $wherecon .= 'AND uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%"';
        }
        $query = "select * from tbl_users as uf WHERE uf.id != ? AND status = ? " . $wherecon . " ";

        $whrExtArr=array($this->session_user_id,'a');

        $totalRows = $this->db->pdoQuery($query,$whrExtArr)->affectedRows();
        $query_with_limit = $query . ' LIMIT ' . $limit . ' OFFSET ' . $offset;

        $connection_detail_array = $this->db->pdoQuery($query,$whrExtArr)->results();

        $connection_count_total=count($connection_detail_array);

        $connection_detail_array = $this->db->pdoQuery($query_with_limit,$whrExtArr)->results();
        if ($connection_detail_array) {
           // print_r($connection_detail_array);exit();
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset ;
            $connection_count_load = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();
            $next_users = $this->db->pdoQuery($query_with_next_limit,$whrExtArr)->results();
            //print_r($connection_count_load);exit();

            $next_available_records = count($next_users);
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/invite-user-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $fields = array(
                "%USER_ID%",
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                "%USER_PROFILE_PICTURE%"
            );
            for ($i = 0; $i < count($connection_detail_array); $i++) {
                //echo "<pre>";print_r($connection_detail_array[$i]);exit;
                $connection_status = '';
                $user_actions = null;
                $user_id = $connection_detail_array[$i]['id'];
                $user_profile_url = get_user_profile_url($user_id);
                $first_name = filtering($connection_detail_array[$i]['first_name']);
                $last_name = filtering($connection_detail_array[$i]['last_name']);
                $user_name_full = $first_name . " " . $last_name;
                $userimage_final=getImageURL("user_profile_picture", $user_id, "th3",$this->platform);
                $user_logo_url = getImageURL("user_profile_picture", $connection_detail_array[$i]['id'], "th3");
                
                if($platform == 'web'){
                    $user_logo_url = ($user_logo_url == '') ? '<span class="profile-picture-character">'.$user_name_full.'</span>' : $user_logo_url;
                }

                $fields_replace = array(
                     $user_id,
                    $userimage_final,
                    $user_profile_url,
                    ucwords($user_name_full),
                    $user_logo_url
                );
                
                if($this->platform == 'app'){
                    $app_array[] = array(
                        'user_id'=>$user_id,
                        'user_name'=>$user_name_full,
                        'userimage'=>$userimage_final
                    );
                } else {
                    $users_html .= str_replace($fields, $fields_replace, $single_user_tpl_parsed);
                }
            }
            if ($next_available_records > 0) {
                $keyword=($_GET['keyword'] != '')?$_GET['keyword']:'';

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getUsers/currentPage/" . ($currentPage + 1)."/".$keyword;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $users_html .= $load_more_li_tpl->parse();
            }
            $pagination = getPagination($totalUsers, count($users), NO_OF_SEARCH_RESULTS_PER_PAGE, $currentPage);   
        }
        return $users_html;
    }
    public function getReferralDetails($userId){
        $final_result= $hide_edit_link = "";
        $user_img=DEFAULT_USET_IMAGE;

        $main_content = new MainTemplater(DIR_TMPL . $this->module . "/single-referral-details-nct.tpl.php");
        
        $main_content = $main_content->parse();

        $fields=array("%REVIEW_DESCRIPTOIN%");

        $rate_data = $this->db->pdoQuery("SELECT * FROM tbl_referral_reviews WHERE isApprovePublish ='ap' AND receiver_id = '".$userId."'");
        $qryRes = $rate_data->results();
        $totalRes = $rate_data->affectedRows();
        if($totalRes > 0){
            foreach($qryRes as $fetchRes){
                $firstName = isset($fetchRes['review_description']) ? $fetchRes['review_description']:'-';
                
                $replace=array($firstName);
                
                $final_result.=str_replace($fields,$replace,$main_content);
            }
        }else{
                $no_data_tpl = new Templater(DIR_TMPL . $this->module . "/no-data-nct.tpl.php");
                $no_data_tpl->set('no_data_message', ERROR_NOT_ADDED_ANY_REFERRALS);
                $final_result = $no_data_tpl->parse();
            
        }
        return $final_result;    
    }
    public function sendInvitationOffPlatform($current_user_id,$user_email){
        $response = array();
        $response['status'] = false;

        $send_arr = array();
        
        $send_arr['sender_id'] = $current_user_id;
        $send_arr['receiver_email'] = $user_email;
        $send_arr['createdAt'] = date("Y-m-d H:i:s");

        $lastInsertId = $this->db->insert("tbl_invite_user_verify_licenses", $send_arr)->getLastInsertId();

        if($lastInsertId > 0){
            $arrayCont['greetings'] = $user_email;
            $arrayCont['referrallink'] = "Click <a href='" . SITE_URL . "signup/?user=" . 'anotheruser' . "&profile=" . $current_user_id . "' target='_blank'>here</a> to verify license.";

            generateEmailTemplateSendEmail("send_license_invitation_off_platform", $arrayCont, $user_email);
            $response['status'] = "suc";
            $response['redirect_url'] = SITE_URL ."profile/";
            $response['message'] = SUCCESS_INVITE_SEND_TO_VERIFY_LICENSE;
            return $response;
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."profile/";
            $response['message'] = ERROR_INVITATION_TO_VERIFY_LICENSE;
            return $response;
        }
    }
    public function verifyLicenseEndorsement($license_id,$platform='web'){
        $response = array();
        $response['status'] = false;
        $license_id = decryptIt($license_id);
        //print_r($license_id);exit();
        if($license_id > 0){
            $verify_data = array();

            $verify_data['verification_status'] = 'y';
            $verify_data['verified_user_id'] = $this->session_user_id;
    
            $affectedRows = $this->db->update("tbl_users_licenses_endorsement", $verify_data, array("id" => $license_id))->affectedRows();
            
            if ($affectedRows > 0) {
                $response['status'] = 'suc'; 
                $response['redirect_url'] = SITE_URL.'profile/'.$this->user_id; 
                $response['message'] =SUCCESS_LICENSE_ENDORSEMENT_VERIFY;
            }else{
                $response['status'] = 'err'; 
                $response['redirect_url'] = SITE_URL.'profile/'.$this->user_id;
                $response['message'] = ERROR_LICENSE_ENDORSEMENT_VERIFY;
            }
        }else{
            $response['status'] = 'err';
            $response['redirect_url'] = SITE_URL.'profile/'.$this->user_id; 
            $response['message'] = ERROR_LICENSE_ENDORSEMENT_NOT_AVAILABLE;
        }
        return $response;
    }
    public function getLicenseList($userId){
        $final_result = NULL;
        $licenses = $this->db->pdoQuery("SELECT ul.id,le.licenses_endorsements_name_".$this->lId." as license_name FROM tbl_users_licenses_endorsement as ul LEFT JOIN tbl_license_endorsements as le ON ul.licenses_id = le.id WHERE ul.user_id = ? AND ul.verification_status = ? AND le.isActive = ? ORDER BY ul.id DESC",array($userId,'n','y'))->results();
        //_print_r($licenses);exit();
        
        if ($licenses) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($licenses); $i++) {
                $fields_replace = array(
                    filtering($licenses[$i]['id'], 'input', 'int'),
                    '',
                    filtering($licenses[$i]['license_name'], 'input', 'int')
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;    
    }
    public function inviteUserOnPlatform($user_id,$selected_license,$platform = 'web'){
        $response = array();
        $response['status'] = false;
        if($user_id > 0 && $selected_license > 0){
            $send_arr = array();
        
            $send_arr['sender_id'] = $this->session_user_id;
            $send_arr['receiver_id'] = $user_id;
            $send_arr['license_endorsement_id'] = $selected_license;
            $send_arr['isVerify '] = 'p';
            $send_arr['isActive '] = 'y';
            $send_arr['createdAt'] = date("Y-m-d H:i:s");

            $lastInsertId = $this->db->insert("tbl_invite_platform_user", $send_arr)->getLastInsertId();

            if($lastInsertId > 0){
                $user_details = $this->db->select('tbl_users', array('id,first_name,last_name,email_address'), array('id' => $user_id))->result();

                $arrayCont['greetings'] = $user_details['first_name'].' '.$user_details['last_name'];
                $arrayCont['referrallink'] = "Click <a href='" . SITE_URL . "profile/".$this->session_user_id. "' target='_blank'>here</a> to verify license.";

                generateEmailTemplateSendEmail("send_license_invitation_on_platform", $arrayCont, $user_details['email_address']);

                $response['status'] = "suc";
                $response['redirect_url'] = SITE_URL ."profile/";
                $response['message'] = SUCCESS_ON_PLATFORM_SEND_VERIFICATION_REQUEST;
            }else{
                $response['status'] = "err";
                $response['redirect_url'] = SITE_URL ."profile/";
                $response['message'] = ERROR_ON_PLATFORM_SEND_VERIFICATION_REQUEST;
            }
        }else{
            $response['status'] = "err";
            $response['redirect_url'] = SITE_URL ."profile/";
            $response['message'] = ERROR_ON_PLATFORM_USER_LICENSE_NOT_EXISTS;
        }
        return json_encode($response);
    }
    public function getInstituteSuggestion($user_id, $institute_name,$platform = 'web') {
        $final_result = array();
        
        $query = "SELECT id,industry_name_".$this->lId." as company_type FROM tbl_industries WHERE industry_name_".$this->lId." LIKE '%" . $institute_name . "%' AND status = ? ORDER BY id DESC ";

        $where_arr=array('a');

        if($platform == 'web'){
            $query .="LIMIT 0, 10";
        }
        $licenses = $this->db->pdoQuery($query,$where_arr)->results();
        if ($licenses != '') {
            for ($i = 0; $i < count($licenses); $i++) {
                $single_company = array();
                if($platform == 'app'){
                    $single_company['industry_id'] = filtering($licenses[$i]['id'], 'output', 'int');
                } else {
                    $single_company['industry_id'] = encryptIt(filtering($licenses[$i]['id'], 'output', 'int'));
                }
                 $single_company['company_type'] = $licenses[$i]['company_type'];
                $final_result[] = $single_company;
            }
        }

        if (empty($final_result)) {
            if($platform == 'app'){
                $final_result=array('company_type'=>array(),'status'=>'success','message'=>LBL_NO_RESULTS_FOUND);
            }
        } else {
            if($platform == 'app'){
                $final_result = array('company_type'=>$final_result,'status'=>'success','message'=>LBL_SUCCESS_COMPANIES_LISTING);
            }
        }
        return $final_result;
    }
} ?>