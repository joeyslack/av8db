<?php

class User_dashboard extends Home {

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
            $query = "SELECT u.* ,location.country, location.state, location.city1,location.city2
                    FROM tbl_users u 
                    LEFT JOIN tbl_locations location ON location.id = u.location_id
                    WHERE u.id = '" . $this->id . "' ";

            $qrySel = $this->db->pdoQuery($query)->result();

            $fetchRes = $qrySel;

            $this->data['first_name'] = $this->first_name = filtering($fetchRes['first_name']);
            $this->data['last_name'] = $this->last_name = filtering($fetchRes['last_name']);
            $this->data['email_address'] = $this->email_address = filtering($fetchRes['email_address']);
            $this->data['profile_picture_name'] = $this->profile_picture_name = filtering($fetchRes['profile_picture_name']);
            $this->data['cover_photo'] = $this->cover_photo = filtering($fetchRes['cover_photo']);
            $this->data['gender'] = $this->gender = ( ( $fetchRes['gender'] == "m" ) ? "Male" : "Female" );

            $this->data['phone_no'] = $this->phone_no = filtering($fetchRes['phone_no']);

            $this->countryName = filtering($fetchRes['country']);
            $this->stateName = filtering($fetchRes['state']);
            $this->cityName = filtering($fetchRes['city1']) != '' ? filtering($fetchRes['city1']) : filtering($fetchRes['city2']);

            $this->data['location'] = $this->location = $this->countryName . ", " . $this->stateName. ", " . $this->cityName;
            if($this->countryName=='' && $this->stateName == '' && $this->cityName==''){
                $this->location='-';
            }

            /*$this->data['country_id'] = $this->country_id = filtering($fetchRes['country_id'], 'output', 'int');
            $this->data['state_id'] = $this->state_id = filtering($fetchRes['state_id'], 'output', 'int');
            $this->data['city_id'] = $this->city_id = filtering($fetchRes['city_id'], 'output', 'int');

            $this->data['countryName'] = $this->countryName = filtering($fetchRes['countryName']);
            $this->data['stateName'] = $this->stateName = filtering($fetchRes['stateName']);
            $this->data['cityName'] = $this->cityName = filtering($fetchRes['cityName']);*/

            $this->data['status'] = $this->status = $fetchRes['status'];
        } else {
            $this->data['first_name'] = $this->first_name = '';
            $this->data['last_name'] = $this->last_name = '';
            $this->data['email_address'] = $this->email_address = '';
            $this->data['date_of_birth'] = $this->date_of_birth = '';
            $this->data['phone_no'] = $this->phone_no = '';
            $this->data['cover_photo'] = $this->cover_photo = '';

            $this->data['country_id'] = $this->country_id = '';
            $this->data['state_id'] = $this->state_id = '';
            $this->data['city_id'] = $this->city_id = '';

            $this->data['countryName'] = $this->countryName = '';
            $this->data['stateName'] = $this->stateName = '';
            $this->data['cityName'] = $this->cityName = '';

            $this->data['status'] = $this->status = 'a';

            $this->data['location'] = $this->location = '';
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
    public function getEducation($user_id) {
        $final_result = $educations_html = "";

        $education_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/education-container-nct.tpl.php');
        
        $educations = $this->db->select("tbl_user_education", "*", array("user_id" => $user_id ))->results();
        
        if($educations) {
            $education_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/education-single-row-nct.tpl.php');
            $education_single_row_parsed = $education_single_row->parse();
            
            $fields = array(
                "%DEGREE_NAME%",
                "%UNIVERSITY_NAME%",
                "%FIELD_OF_STUDY%",
                "%FROM%",
                "%TO%",
                "%GRADE_OR_PERCENTAGE%",
                "%DESCRIPTION%"
            );
            
            foreach($educations as $single_education) {
                $fields_replace = array(
                    filtering($single_education['degree_name']),
                    filtering($single_education['university_name']),
                    filtering($single_education['field_of_study']),
                    (isset($single_education['from_year']) && $single_education['from_year'] != '') ? filtering($single_education['from_year']) : '-',
                    (isset($single_education['to_year']) && $single_education['to_year'] != '') ? filtering($single_education['to_year']) : '',
                    filtering($single_education['grade_or_percentage']),
                    filtering($single_education['description'])
                );
                
                $educations_html .= str_replace($fields ,$fields_replace, $education_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '7');
            $no_records->set('no_records_message', 'Education not added yet.');
            $educations_html = $no_records->parse();
        }
        
        $education_container_tpl->set('educations', $educations_html);
        
        $final_result = $education_container_tpl->parse();

        return $final_result;
    }

    public function getLanguages($user_id) {
        $final_result = "";

        $final_result = "Langiuages";

        return $final_result;
    }

    public function getSkills($user_id) {
        $final_result = "";

        $final_result = "Skills";

        return $final_result;
    }

    public function getMyPages($user_id, $currentPage = 1) {
        $final_result = $companies_html = "";

        $totalRows = $showableRows = 0;

        $limit = NO_OF_COMPANIES_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $companies_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/companies-container-nct.tpl.php');
        
        $query = "SELECT c.*, i.industry_name FROM tbl_companies c
                    LEFT JOIN tbl_industries i ON i.id = c.company_industry_id WHERE c.user_id = '".$user_id."' AND c.company_type='r' ";
        $companies = $this->db->pdoQuery($query)->results();

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $totalRows = count($companies);

        $getShowableResults = $this->db->pdoQuery($query . $limit_query)->results();
        
        if($getShowableResults) {
            $showableRows = count($getShowableResults);
            $company_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/company-single-row-nct.tpl.php');
            $company_single_row_parsed = $company_single_row->parse();
            
            $fields = array(
                "%COMPANY_ID%",
                "%COMPANY_NAME%",
                "%COMPANY_LOGO%",
                "%COMPANY_EMAIL%",
                "%INDUSTRY_NAME%",
                "%SERVICES_PROVIDED%",
                "%WEBSITE_OF_COMPANY%",
                "%ADDED_ON%",
                "%UPDATED_ON%",
                "%YEAR_FOUNDED%",
                "%UNIQUE_IDENTIFIER%",
                "%COMPANY_LOGO_WEBP%"
            );
            require_once(DIR_ADM_MOD . 'storage.php');
            $company_storage_logo = new storage();

            foreach($getShowableResults as $single_company) {
                $company_logo = '';
                /*$company_logo='';
                $company_logo=getImageURL("company_logo", $single_company['id'], "th2");
                if($company_logo==''){
                    $company_logo=SITE_THEME_IMG . "no-image.jpg";

                }
*/                
                if ($single_company['company_logo'] == '') {
                    $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                    $company_logo_url_webp = "https://storage.googleapis.com/av8db/no-image.webp";

                   /* $company_logo=SITE_THEME_IMG . "no-image.jpg";
                    $company_logo_url_webp=SITE_THEME_IMG . "no-image.webp";*/
                } else {                    
                    $src2 = DIR_NAME_COMPANY_LOGOS."/";
                    $img_arr= explode(".", $single_company['company_logo']);

                    $company_logo_r_url = $company_storage_logo->getImageUrl1('av8db','th2_'.$single_company['company_logo'],$src2);
                    $ck1 = getimagesize($company_logo_r_url);
                    if (empty($ck1)) {
                        $company_logo = 'https://storage.googleapis.com/av8db/no-image.jpg';
                    }else{
                        $company_logo = $company_logo_r_url;
                    }
                    $company_logo_webp = $company_storage_logo->getImageUrl1('av8db','th2_'.$img_arr[0].'.webp',$src2);
                    
                    $ck = getimagesize($company_logo_webp);
                    if (empty($ck)) {
                        $company_logo_url_webp='';
                    }
                }

                $fields_replace = array(
                    filtering($single_company['id'], 'output', 'int'),
                    filtering($single_company['company_name']),
                    $company_logo,
                    filtering($single_company['owner_email_address']),
                    filtering($single_company['industry_name']),
                    filtering($single_company['services_provided']),
                    filtering($single_company['website_of_company']),
                    convertDate('onlyDate', $single_company['added_on']),
                    convertDate('onlyDate', $single_company['updated_on']),
                    filtering($single_company['foundation_year']) > 0 ? filtering($single_company['foundation_year']) : "-",
                    $single_company['id'] . uniqid(),
                    $company_logo_url_webp
                    
                );
                
                $companies_html .= str_replace($fields ,$fields_replace, $company_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '7');
            $no_records->set('no_records_message', 'Business has not been added yet.');
            $companies_html = $no_records->parse();
        }
        
        $companies_container_tpl->set('companies', $companies_html);
        $companies_container_tpl->set('user_id', $user_id);
        $companies_container_tpl->set('pagination_id', "my_pages");
        $companies_container_tpl->set('pagination', getPagination($totalRows, $showableRows, NO_OF_COMPANIES_PER_PAGE, $currentPage));
        
        $final_result = $companies_container_tpl->parse();

        return $final_result;
    }

    public function getFollowing($user_id, $currentPage = 1) {
        $final_result = $companies_html = "";

        $totalRows = $showableRows = 0;

        $limit = NO_OF_COMPANIES_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $companies_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/companies-container-nct.tpl.php');
        
        $query = "SELECT c.*, i.industry_name 
                    FROM tbl_company_followers cf 
                    LEFT JOIN tbl_companies c ON cf.company_id = c.id 
                    LEFT JOIN tbl_industries i ON i.id = c.company_industry_id
                    WHERE cf.user_id = '".$user_id."' ";
        $companies = $this->db->pdoQuery($query)->results();

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $totalRows = count($companies);

        $getShowableResults = $this->db->pdoQuery($query . $limit_query)->results();
        
        if($getShowableResults) {
            $showableRows = count($getShowableResults);
            $company_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/company-single-row-nct.tpl.php');
            $company_single_row_parsed = $company_single_row->parse();
            
            $fields = array(
                "%COMPANY_ID%",
                "%COMPANY_NAME%",
                "%COMPANY_LOGO%",
                "%COMPANY_EMAIL%",
                "%INDUSTRY_NAME%",
                "%SERVICES_PROVIDED%",
                "%WEBSITE_OF_COMPANY%",
                "%ADDED_ON%",
                "%UPDATED_ON%",
                "%YEAR_FOUNDED%",
                "%COMPANY_LOGO_WEBP%"
            );
            require_once(DIR_ADM_MOD . 'storage.php');
            $following_storage_logo = new storage();

            foreach($getShowableResults as $single_company) {
                $company_logo = '';
                if ($single_company['company_logo'] == '') {
                    $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                    $company_logo_url_webp = "https://storage.googleapis.com/av8db/no-image.webp";
                } else {
                    $src2 = DIR_NAME_COMPANY_LOGOS."/";
                    $img_arr= explode(".", $single_company['company_logo']);

                    $company_logo_f_url = $following_storage_logo->getImageUrl1('av8db','th2_'.$single_company['company_logo'],$src2);
                    $ck1 = getimagesize($company_logo_f_url);
                    if (empty($ck1)) {
                        $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                    }else{
                        $company_logo = $company_logo_f_url;
                    }
                    $company_logo_webp = $following_storage_logo->getImageUrl1('av8db','th2_'.$img_arr[0].'.webp',$src2);
                    
                    $ck = getimagesize($company_logo_webp);
                    if (empty($ck)) {
                        $company_logo_url_webp='';
                    }                    
                }
                $fields_replace = array(
                    filtering($single_company['id'], 'output', 'int'),
                    filtering($single_company['company_name']),
                    $company_logo,
                    filtering($single_company['owner_email_address']),
                    filtering($single_company['industry_name']),
                    filtering($single_company['services_provided']),
                    filtering($single_company['website_of_company']),
                    convertDate('onlyDate', $single_company['added_on']),
                    convertDate('onlyDate', $single_company['updated_on']),
                    filtering($single_company['foundation_year']) > 0 ? filtering($single_company['foundation_year']) : "-",
                    $company_logo_url_webp

                );
                
                $companies_html .= str_replace($fields ,$fields_replace, $company_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '7');
            $no_records->set('no_records_message', 'Business has not been added yet.');
            $companies_html = $no_records->parse();
        }
        
        $companies_container_tpl->set('companies', $companies_html);
        $companies_container_tpl->set('user_id', $user_id);
        $companies_container_tpl->set('pagination_id', "following");
        $companies_container_tpl->set('pagination', getPagination($totalRows, $showableRows, NO_OF_COMPANIES_PER_PAGE, $currentPage));
        
        $final_result = $companies_container_tpl->parse();

        return $final_result;
    }

    public function getMyJobs($user_id, $currentPage = 1) {
        $final_result = $jobs_html = "";

        $totalRows = $showableRows = 0;

        $limit = NO_OF_JOBS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $jobs_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/jobs-container-nct.tpl.php');
        
        $query = "SELECT j.*, 
                IF(j.employment_type = 'f', 'Full Time', IF(j.employment_type = 'p', 'Part Time', IF( j.employment_type = 'c', 'Contract', 'Temporary' ) ) ) as employment_type_text, 
                c.company_name, jc.job_category,
                location.country, location.state, location.city1,location.city2  
                    FROM tbl_jobs j 
                    LEFT JOIN tbl_companies c ON c.id = j.company_id 
                    LEFT JOIN tbl_job_category jc ON jc.id = j.job_category_id
                    LEFT JOIN tbl_locations location ON location.id = j.location_id                     
                    WHERE j.user_id = '".$user_id."' ";
        $jobs = $this->db->pdoQuery($query)->results();

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $totalRows = count($jobs);

        $getShowableResults = $this->db->pdoQuery($query . $limit_query)->results();
        
        if($getShowableResults) {
            $showableRows = count($getShowableResults);
            $job_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/job-single-row-nct.tpl.php');
            $job_single_row_parsed = $job_single_row->parse();
            
            $fields = array(
                "%JOB_ID%",
                "%COMPANY_NAME%",
                "%JOB_CATEGORY%",
                "%JOB_TITLE%",
                "%JOB_LOCATION%",
                "%EMPLOYMENT_TYPE_TEXT%",
                "%LAST_DATE_OF_APPLICATION%",
                "%ADDED_ON%",
                "%IS_FEATURED%",
                "%UNIQUE_IDENTIFIER%",
            );
            
            foreach($getShowableResults as $single_job) {

                $countryName = filtering($single_job['country']);
                $stateName = filtering($single_job['state']);
                $cityName = filtering($single_job['city1']) != '' ? filtering($single_job['city1']) : filtering($single_job['city2']);

                $location = $countryName . ", " . $stateName. ", " . $cityName;

                $fields_replace = array(
                    filtering($single_job['id'], 'output', 'int'),
                    filtering($single_job['company_name']),
                    filtering($single_job['job_category']),
                    filtering($single_job['job_title']),
                    $location,
                    filtering($single_job['employment_type_text']),
                    convertDate('onlyDate', $single_job['last_date_of_application']),
                    convertDate('onlyDate', $single_job['added_on']),
                    filtering(($single_job["is_featured"] == "y" && $single_job["featured_till"] >= date('Y-m-d H:i:s') )? "Yes" : "No"),
                    $single_job['id'] . uniqid(),
                );
                
                $jobs_html .= str_replace($fields ,$fields_replace, $job_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '11');
            $no_records->set('no_records_message', "No job has been posted yet.");
            $jobs_html = $no_records->parse();
        }
        
        $jobs_container_tpl->set('jobs', $jobs_html);
        $jobs_container_tpl->set('user_id', $user_id);
        $jobs_container_tpl->set('pagination', getPagination($totalRows, $showableRows, NO_OF_JOBS_PER_PAGE, $currentPage));
        
        $final_result = $jobs_container_tpl->parse();

        return $final_result;
    }

    public function getAppliedJobs($user_id, $currentPage = 1) {
        $final_result = $jobs_html = "";

        $totalRows = $showableRows = 0;

        $limit = NO_OF_JOBS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $jobs_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/applied-jobs-container-nct.tpl.php');
        
        $query = "SELECT j.*, IF(j.employment_type = 'f', 'Full Time', IF(j.employment_type = 'p', 'Part Time', IF( j.employment_type = 'c', 'Contract', 'Temporary' ) ) ) as employment_type_text, 
        c.company_name,c.company_logo, jc.job_category,
                    IF(added_by_admin = 'y', 'Admin', 
                        concat_ws(' ', u.first_name, u.last_name) ) as posted_by, ja.applied_on,
                         location.country, location.state, location.city1,location.city2 
                    FROM tbl_job_applications ja
                    LEFT JOIN tbl_jobs j ON j.id = ja.job_id 
                    LEFT JOIN tbl_users u ON u.id = j.user_id 
                    LEFT JOIN tbl_companies c ON c.id = j.company_id 
                    LEFT JOIN tbl_job_category jc ON jc.id = j.job_category_id
                    LEFT JOIN tbl_locations location ON location.id = j.location_id                       
                    WHERE ja.user_id = '".$user_id."' ";
        $jobs = $this->db->pdoQuery($query)->results();

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $totalRows = count($jobs);

        $getShowableResults = $this->db->pdoQuery($query . $limit_query)->results();
        
        if($getShowableResults) {
            $showableRows = count($getShowableResults);
        
            $job_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/applied-job-single-row-nct.tpl.php');
            $job_single_row_parsed = $job_single_row->parse();
            
            $fields = array(
                "%COMPANY_NAME%",
                "%JOB_CATEGORY%",
                "%JOB_TITLE%",
                "%JOB_LOCATION%",
                "%EMPLOYMENT_TYPE_TEXT%",
                "%LAST_DATE_OF_APPLICATION%",
                "%ADDED_ON%",
                "%POSTED_BY%",
                "%APPLIED_ON%",
                "%IS_FEATURED%",
                "%COMPANY_LOGO%",
            );
            require_once(DIR_ADM_MOD . 'storage.php');
            $savedjobs_storage_logo = new storage();

            foreach($getShowableResults as $single_job) {
                $countryName = filtering($single_job['country']);
                $stateName = filtering($single_job['state']);
                $cityName = filtering($single_job['city1']) != '' ? filtering($single_job['city1']) : filtering($single_job['city2']);

                $location = $countryName . ", " . $stateName. ", " . $cityName;
                $company_logo = $company_logo_name = '';
                $src2 = DIR_NAME_COMPANY_LOGOS."/";
                
                $get_company_details = $this->db->select("tbl_companies","*",array("id"=>$single_job["company_id"]))->result();
                if (!empty($get_company_details)) {
                    $company_logo_name = filtering($get_company_details['company_logo']);

                    if ($company_logo_name == '') {
                        $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                    }else 
                    {   
                        $img_url = $savedjobs_storage_logo->getImageUrl1('av8db','th2_'.$company_logo_name,$src2);
                        $up = getimagesize($img_url);
                        if (!empty($up)) {
                             $company_logo =$img_url;   
                        }else{
                            $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                        }
                    }
                }else{
                    $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                }
                $fields_replace = array(
                    filtering($single_job['company_name']),
                    filtering($single_job['job_category']),
                    filtering($single_job['job_title']),
                    $location,
                    filtering($single_job['employment_type_text']),
                    convertDate('onlyDate', $single_job['last_date_of_application']),
                    convertDate('onlyDate', $single_job['added_on']),
                    filtering($single_job['posted_by']),
                    convertDate('onlyDate', $single_job['applied_on']),
                    filtering(($single_job["is_featured"] == "y" && $single_job["featured_till"] >= date('Y-m-d H:i:s') )? "Yes" : "No"),
                    $company_logo,

                );
                
                $jobs_html .= str_replace($fields ,$fields_replace, $job_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '11');
            $no_records->set('no_records_message', "Not applied for any job yet.");
            $jobs_html = $no_records->parse();
        }
        
        $jobs_container_tpl->set('jobs', $jobs_html);
        $jobs_container_tpl->set('user_id', $user_id);
        $jobs_container_tpl->set('pagination', getPagination($totalRows, $showableRows, NO_OF_JOBS_PER_PAGE, $currentPage));
        
        $final_result = $jobs_container_tpl->parse();

        return $final_result;
    }

    public function getSavedJobs($user_id, $currentPage = 1) {
        $final_result = $jobs_html = "";

        $totalRows = $showableRows = 0;

        $limit = NO_OF_JOBS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $jobs_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/saved-jobs-container-nct.tpl.php');
        
        $query = "SELECT j.*, IF(j.employment_type = 'f', 'Full Time', IF(j.employment_type = 'p', 'Part Time', IF( j.employment_type = 'c', 'Contract', 'Temporary' ) ) ) as employment_type_text, 
        c.company_name,c.company_logo, jc.job_category,
                    IF(added_by_admin = 'y', 'Admin', 
                        concat_ws(' ', u.first_name, u.last_name) ) as posted_by, js.added_on as job_saved_date,
                         location.country, location.state, location.city1,location.city2 
                    FROM tbl_saved_jobs js
                    LEFT JOIN tbl_jobs j ON j.id = js.job_id 
                    LEFT JOIN tbl_users u ON u.id = j.user_id 
                    LEFT JOIN tbl_companies c ON c.id = j.company_id 
                    LEFT JOIN tbl_job_category jc ON jc.id = j.job_category_id
                    LEFT JOIN tbl_locations location ON location.id = j.location_id                       
                    WHERE js.user_id = '".$user_id."' ";
        $jobs = $this->db->pdoQuery($query)->results();

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $totalRows = count($jobs);

        $getShowableResults = $this->db->pdoQuery($query . $limit_query)->results();
        
        if($getShowableResults) {
            $showableRows = count($getShowableResults);
    
            $job_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/saved-job-single-row-nct.tpl.php');
            $job_single_row_parsed = $job_single_row->parse();
            
            $fields = array(
                "%COMPANY_NAME%",
                "%JOB_CATEGORY%",
                "%JOB_TITLE%",
                "%JOB_LOCATION%",
                "%EMPLOYMENT_TYPE_TEXT%",
                "%LAST_DATE_OF_APPLICATION%",
                "%ADDED_ON%",
                "%POSTED_BY%",
                "%APPLIED_ON%",
                "%IS_FEATURED%",
                "%COMPANY_LOGO%",
            );
            require_once(DIR_ADM_MOD . 'storage.php');
            $savedjobs_storage_logo = new storage();
            foreach($getShowableResults as $single_job) {
                $countryName = filtering($single_job['country']);
                $stateName = filtering($single_job['state']);
                $cityName = filtering($single_job['city1']) != '' ? filtering($single_job['city1']) : filtering($single_job['city2']);

                $location = $countryName . ", " . $stateName. ", " . $cityName;
                $company_logo = $company_logo_name = '';

                $src2 = DIR_NAME_COMPANY_LOGOS."/";
                
                $get_company_details = $this->db->select("tbl_companies","*",array("id"=>$single_job["company_id"]))->result();
                if (!empty($get_company_details)) {
                    $company_logo_name = filtering($get_company_details['company_logo']);

                    if ($company_logo_name == '') {
                        $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                    }else 
                    {   
                        $img_url = $savedjobs_storage_logo->getImageUrl1('av8db','th2_'.$company_logo_name,$src2);
                        $up = getimagesize($img_url);
                        if (!empty($up)) {
                            $company_logo =$img_url;   
                        }else{
                            $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                        }
                    }
                }else{
                    $company_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                }
                $fields_replace = array(
                    filtering($single_job['company_name']),
                    filtering($single_job['job_category']),
                    filtering($single_job['job_title']),
                    $location,
                    filtering($single_job['employment_type_text']),
                    convertDate('onlyDate', $single_job['last_date_of_application']),
                    convertDate('onlyDate', $single_job['added_on']),
                    filtering($single_job['posted_by']),
                    convertDate('onlyDate', $single_job['job_saved_date']),
                    filtering(($single_job["is_featured"] == "y" && $single_job["featured_till"] >= date('Y-m-d H:i:s') )? "Yes" : "No"),
                    $company_logo,

                );
                
                $jobs_html .= str_replace($fields ,$fields_replace, $job_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '11');
            $no_records->set('no_records_message', "Not saved for any job yet.");
            $jobs_html = $no_records->parse();
        }
        
        $jobs_container_tpl->set('jobs', $jobs_html);
        $jobs_container_tpl->set('user_id', $user_id);
        $jobs_container_tpl->set('pagination', getPagination($totalRows, $showableRows, NO_OF_JOBS_PER_PAGE, $currentPage));
        
        $final_result = $jobs_container_tpl->parse();

        return $final_result;
    }

    public function getMyGroups($user_id, $currentPage = 1) {
        $final_result = $groups_html = "";

        $totalRows = $showableRows = 0;

        $limit = NO_OF_GROUPS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $groups_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/groups-container-nct.tpl.php');
        
        $query = "SELECT g.*, concat_ws(' ', u.first_name, u.last_name) as user_name, gt.group_type, 
                IF(privacy = 'pr', 'Private', 'Public') as privacy_text, 
                IF(accessibility = 'awa', '-', IF(accessibility = 'a', 'Auto join', 'Request to join' ) ) as accessibility_text 
                FROM tbl_groups g 
                LEFT JOIN tbl_users u ON u.id = g.user_id 
                LEFT JOIN tbl_group_types gt ON gt.id = g.group_type_id  
                WHERE g.user_id = '" . $user_id . "' ORDER BY g.id DESC ";
        
        $groups = $this->db->pdoQuery($query)->results();


        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $totalRows = count($groups);

        $getShowableResults = $this->db->pdoQuery($query . $limit_query)->results();

        //_print($getShowableResults);exit;
        
        if($getShowableResults) {
            $showableRows = count($getShowableResults);
        
            $group_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/group-single-row-nct.tpl.php');
            $group_single_row_parsed = $group_single_row->parse();
            
            $fields = array(
                "%GROUP_ID%",
                "%GROUP_NAME%",
                "%GROUP_LOGO%",
                "%GROUP_TYPE%",
                "%WEBSITE_URL%",
                "%PRIVACY_TEXT%",
                "%ACCESSIBILITY_TEXT%",
                "%ADDED_ON%",
                "%UPDATED_ON%",
                "%MEMBERS%",
                "%CONNECTED_MEMBERS%",
                "%UNIQUE_IDENTIFIER%",
            );
            require_once(DIR_ADM_MOD . 'storage.php');
            $mygroup_storage_logo = new storage();
            foreach($getShowableResults as $single_group) {

                $group_id = filtering($single_group['id'], 'output', 'int');

                $user_id_arr = getConnections($user_id);

                if(is_array($user_id_arr) && !empty($user_id_arr)) {
                    $connected_members = $this->db->pdoQuery('SELECT COUNT(*) as total_connection FROM tbl_group_members 
                    WHERE user_id IN ('. implode(",", $user_id_arr) .') AND group_id = '. $group_id .' ')->result();    

                    $total_connection =  $connected_members['total_connection'];
                } else {
                    $total_connection = 0;
                }

                $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members 
                    WHERE  group_id = '. $group_id .' ')->result();
                $group_logo = $group_logo_name = '';

                $src2 = DIR_NAME_GROUP_LOGOS."/";
                $get_group_details = $this->db->select("tbl_groups","*",array("id"=>$single_group["id"]))->result();
                if (!empty($get_group_details)) {
                    $group_logo_name = filtering($get_group_details['group_logo']);

                    if ($group_logo_name == '') {
                        $group_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                    }else 
                    {   
                        $img_url = $mygroup_storage_logo->getImageUrl1('av8db','th2_'.$group_logo_name,$src2);
                        $up = getimagesize($img_url);
                        if (empty($up)) {
                            $group_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                        }else{
                            $group_logo =$img_url;
                        }
                    }
                }else{
                    $group_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                }
                $fields_replace = array(
                    filtering($single_group['id'], 'output', 'int'),
                    filtering($single_group['group_name']),
                    $group_logo,
                    filtering($single_group['group_type']),
                    filtering($single_group['website_url']),
                    filtering($single_group['privacy_text']),
                    filtering($single_group['accessibility_text']),
                    convertDate('onlyDate', $single_group['added_on']),
                    convertDate('onlyDate', $single_group['updated_on']),
                    $group_members['total_members'],
                    $total_connection,
                    $single_group['id'] . uniqid(),
                );
                
                $groups_html .= str_replace($fields ,$fields_replace, $group_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '11');
            $no_records->set('no_records_message', "Not applied for any group yet.");
            $groups_html = $no_records->parse();
        }
        
        $groups_container_tpl->set('groups', $groups_html);
        $groups_container_tpl->set('user_id', $user_id);
        $groups_container_tpl->set('pagination', getPagination($totalRows, $showableRows, NO_OF_GROUPS_PER_PAGE, $currentPage));
        
        $final_result = $groups_container_tpl->parse();

        return $final_result;
    }

    public function getJoinedGroups($user_id, $currentPage = 1) {
        $final_result = $groups_html = "";

        $totalRows = $showableRows = 0;

        $limit = NO_OF_GROUPS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $groups_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/joined-groups-container-nct.tpl.php');
        
        $query = "SELECT gm.joined_on, g.*, u.id as group_user_id, concat_ws(' ', u.first_name, u.last_name) as user_name, gt.group_type, 
                IF(privacy = 'pr', 'Private', 'Public') as privacy_text, 
                IF(accessibility = 'awa', '-', IF(accessibility = 'a', 'Auto join', 'Request to join' ) ) as accessibility_text 
                FROM tbl_group_members gm
                LEFT JOIN tbl_groups g ON g.id = gm.group_id 
                LEFT JOIN tbl_users u ON u.id = g.user_id 
                LEFT JOIN tbl_group_types gt ON gt.id = g.group_type_id  
                WHERE gm.user_id = '" . $user_id . "' AND (action = 'aj' OR action = 'a' OR action = 'aa') ";
        
        $groups = $this->db->pdoQuery($query)->results();

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $totalRows = count($groups);

        $getShowableResults = $this->db->pdoQuery($query . $limit_query)->results();
        
        if($getShowableResults) {
            $showableRows = count($getShowableResults);
        
            $group_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/joined-group-single-row-nct.tpl.php');
            $group_single_row_parsed = $group_single_row->parse();
            
            $fields = array(
                "%GROUP_NAME%",
                 "%GROUP_LOGO%",
                "%GROUP_TYPE%",
                "%WEBSITE_URL%",
                "%PRIVACY_TEXT%",
                "%ACCESSIBILITY_TEXT%",
                "%ADDED_ON%",
                "%UPDATED_ON%",
                "%JOINED_ON%",
                "%MEMBERS%",
                "%CONNECTED_MEMBERS%",
                "%USER_NAME%",
                "%USER_IMAGE%",
            );
            require_once(DIR_ADM_MOD . 'storage.php');
            $myjoinedgroup_storage_logo = new storage();

            foreach($getShowableResults as $single_group) {

                $group_id = filtering($single_group['id'], 'output', 'int');

                $user_id_arr = getConnections($user_id);
                if(is_array($user_id_arr) && !empty($user_id_arr)) {
                    $connected_members = $this->db->pdoQuery('SELECT COUNT(*) as total_connection FROM tbl_group_members 
                    WHERE user_id IN ('. implode(",", $user_id_arr) .') AND group_id = '. $group_id .' ')->result();
                    
                    $total_connection = $connected_members['total_connection'];       
                } else {
                    $total_connection = 0;
                }

                $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members 
                    WHERE  group_id = '. $group_id .' ')->result();
                $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members 
                    WHERE  group_id = '. $group_id .' ')->result();
                $group_logo = $group_logo_name = $group_user_logo = '';

                $src2 = DIR_NAME_GROUP_LOGOS."/";
                $get_group_details = $this->db->select("tbl_groups","*",array("id"=>$single_group["id"]))->result();
                if (!empty($get_group_details)) {
                    $group_logo_name = filtering($get_group_details['group_logo']);

                    if ($group_logo_name == '') {
                        $group_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                    }else 
                    {   
                        $img_url = $myjoinedgroup_storage_logo->getImageUrl1('av8db','th2_'.$group_logo_name,$src2);
                        $upl = getimagesize($img_url);
                        if (empty($upl)) {
                            $group_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                        }else{
                            $group_logo = $img_url;
                        }
                    }
                }else{
                    $group_logo = "https://storage.googleapis.com/av8db/no-image.jpg";
                }

                $user_src = DIR_NAME_USERS."/".$single_group["group_user_id"]."/";
                $get_group_user_details = $this->db->select("tbl_users","*",array("id"=>$single_group["group_user_id"]))->result();
                //echo "<pre>";print_r($get_group_user_details);
                if (!empty($get_group_user_details)) {
                    if ($get_group_user_details['profile_picture_name'] == '') {
                        $group_user_logo = '<span title="' . $get_group_user_details['first_name'].' ' .$get_group_user_details['last_name'] .'" class="profile-picture-character">' . ucfirst($get_group_user_details['first_name'][0]) . '</span>';
                    }else 
                    {   
                        $img_url = $myjoinedgroup_storage_logo->getImageUrl1('av8db','th2_'.$get_group_user_details['profile_picture_name'],$user_src);
                        $up1 = getimagesize($img_url);
                        if (empty($up1)) {
                            $group_user_logo = '<span title="' . $get_group_user_details['first_name'].' ' .$get_group_user_details['last_name'] .'" class="profile-picture-character">' . ucfirst($get_group_user_details['first_name'][0]) . '</span>';
                        }else{
                            $group_user_logo ='<picture>
                                            <source srcset="' . $img_url . '" type="image/jpg">
                                            <img src="' . $img_url . '" class="" alt="img" /> 
                                        </picture>';
                        }
                    }
                }
                $fields_replace = array(
                    filtering($single_group['group_name']),
                    $group_logo,
                    filtering($single_group['group_type']),
                    filtering($single_group['website_url']),
                    filtering($single_group['privacy_text']),
                    filtering($single_group['accessibility_text']),
                    convertDate('onlyDate', $single_group['added_on']),
                    convertDate('onlyDate', $single_group['updated_on']),
                    convertDate('onlyDate', $single_group['joined_on']),
                    $group_members['total_members'],
                    $total_connection,
                    filtering($single_group['user_name']),
                    $group_user_logo,
                );
                /*echo "string 1 ";
                echo "<pre>";print_r($fields_replace);
                echo "string 3 ";*/
                $groups_html .= str_replace($fields ,$fields_replace, $group_single_row_parsed);
            }
        } else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '11');
            $no_records->set('no_records_message', "Not joined any group yet.");
            $groups_html = $no_records->parse();
        }
        
        $groups_container_tpl->set('groups', $groups_html);
        $groups_container_tpl->set('user_id', $user_id);
        $groups_container_tpl->set('pagination', getPagination($totalRows, $showableRows, NO_OF_GROUPS_PER_PAGE, $currentPage));
        
        $final_result = $groups_container_tpl->parse();

        return $final_result;
    }

    public function getUserConnections($user_id, $currentpage = 1) {
        $final_result = "";

        $connection_html = "";

        $connection_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/connection-container-nct.tpl.php');

        $connections_array = getConnections($user_id);
        $connection_count = count($connections_array);

        $connections_array = getConnections($user_id, true, $currentpage, NO_OF_CONNECTION_PER_PAGE);

        require_once(DIR_ADM_MOD . 'storage.php');
        $connection_user_logo = new storage();

        if($connections_array) {

            $connection_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/connection-single-row-nct.tpl.php');
            $connection_single_row_parsed = $connection_single_row->parse();
            
            foreach ($connections_array as $key => $value) {
                $user_info =  $this->db->select('tbl_users', "*", array('id' => $value))->result();

                $fields = array(
                    "%USER_NAME%",
                    "%USER_IMG%",
                );

                $user_src = DIR_NAME_USERS."/".$value."/";
                $group_user_logo = '';
                if (!empty($user_info)) {
                    if ($user_info['profile_picture_name'] == '') {
                        $group_user_logo = '<span title="' . $user_info['first_name'].' ' .$user_info['last_name'] .'" class="profile-picture-character">' . ucfirst($user_info['first_name'][0]) . '</span>';
                    }else 
                    {   
                        $img_url = $connection_user_logo->getImageUrl1('av8db','th2_'.$user_info['profile_picture_name'],$user_src);
                        $up1 = getimagesize($img_url);
                        if (empty($up1)) {
                            $group_user_logo = '<span title="' . $user_info['first_name'].' ' .$user_info['last_name'] .'" class="profile-picture-character">' . ucfirst($user_info['first_name'][0]) . '</span>';
                        }else{
                            $group_user_logo ='<picture>
                                            <source srcset="' . $img_url . '" type="image/jpg">
                                            <img src="' . $img_url . '" class="" alt="img" /> 
                                        </picture>';
                        }
                    }
                }

                $fields_replace = array(
                    filtering($user_info['first_name'], 'output') . " " . filtering($user_info['last_name'], 'output'),
                    $group_user_logo,
                );
                $connection_html .= str_replace($fields ,$fields_replace, $connection_single_row_parsed);
            }
        }else {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '2');
            $no_records->set('no_records_message', "No connection found.");
            $connection_html = $no_records->parse();
        }
        
        $connection_container_tpl->set('connection', $connection_html);
        $connection_container_tpl->set('pagination', getPagination($connection_count, count($connections_array), NO_OF_CONNECTION_PER_PAGE, $currentpage));
        $connection_container_tpl->set('user_id', $user_id);

        $final_result = $connection_container_tpl->parse();

        return $final_result;
    }

    public function getMembershipPlans($user_id) { 
        $final_content = '';
        $membership_plan_purchased = false;
        $purchased_membership_plan_details  = "";

        $plan_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/membership-plan-container-nct.tpl.php');
        
        $user_inmails = $this->db->select("tbl_user_inmails", "*", array("user_id" => $user_id))->result();
        if ($user_inmails) {
            $inmails_expires_on = strtotime($user_inmails['inmails_expires_on']);

            if ($inmails_expires_on > time()) {
                $membership_plan_purchased = true;

                $plan_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/membership-plan-single-row-nct.tpl.php');
                $plan_single_row_parsed = $plan_single_row->parse();

                $query = "SELECT sh.* 
                            FROM tbl_subscription_history sh 
                            WHERE plan_type = 'r' AND user_id = '" . $user_id . "' ORDER BY sh.id DESC ";

                $plan_details = $this->db->pdoQuery($query)->result();

                //_print($plan_details);exit;

                $fields = array(
                    "%PLAN_NAME%",
                    "%PRICE%",
                    "%PURCHASED_ON%",
                    "%INMAILS_RECEIVED%",
                    "%INMAILS_UTILIZED%",
                    "%INMAILS_OUTSTANDING%",
                    "%NO_OF_REMAINING_DAYS%"
                );

                $inmails_received = filtering($user_inmails['inmails_received'], 'output', 'int');
                $inmails_outstanding = filtering($user_inmails['inmails_outstanding'], 'output', 'int');

                $inmails_utilized = $inmails_received - $inmails_outstanding;

                $no_of_remaining_days = getDateDiff(date("Y-m-d"), date("Y-m-d", $inmails_expires_on), 'day');

                $fields_replace = array(
                    filtering($plan_details['plan_name']),
                    filtering($plan_details['price'], 'output'),
                    convertDate('onlyDate', $plan_details['subscribed_on']),
                    $inmails_received,
                    $inmails_utilized,
                    $inmails_outstanding,
                    $no_of_remaining_days
                );

                $purchased_membership_plan_details = str_replace($fields, $fields_replace, $plan_single_row_parsed);
            }
        }

        if (!$membership_plan_purchased) {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '6');
            $no_records->set('no_records_message', "No current membership plan found.");
            $purchased_membership_plan_details = $no_records->parse();
        }

        $plan_container_tpl->set('purchased_membership_plan_details', $purchased_membership_plan_details);

        $final_result = $plan_container_tpl->parse();

        //_print($final_result);exit;
        return $final_result;
    }

    public function getAdhocInmails($user_id) { 
        $final_content = '';
        $membership_plan_purchased = false;
        $purchased_adhoc_inmails_details  = "";

        $plan_container_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . '/adhoc-inmails-container-nct.tpl.php');
        
        $user_inmails = $this->db->select("tbl_user_inmails", "*", array("user_id" => $user_id))->result();
        if ($user_inmails) {
            $adhoc_inmails_expires_on = strtotime($user_inmails['adhoc_inmails_expires_on']);

            if ($adhoc_inmails_expires_on > time()) {
                $membership_plan_purchased = true;

                $plan_single_row = new Templater(DIR_ADMIN_TMPL . $this->module . '/adhoc-inmails-single-row-nct.tpl.php');
                $plan_single_row_parsed = $plan_single_row->parse();

                $query = "SELECT sh.* 
                            FROM tbl_subscription_history sh 
                            WHERE plan_type = 'ah' AND user_id = '" . $user_id . "' ORDER BY sh.id DESC ";

                $plan_details = $this->db->pdoQuery($query)->result();

                $fields = array(
                    "%PLAN_NAME%",
                    "%PURCHASED_ON%",
                    "%INMAILS_RECEIVED%",
                    "%INMAILS_UTILIZED%",
                    "%INMAILS_OUTSTANDING%",
                    "%NO_OF_REMAINING_DAYS%"
                );

                $adhoc_inmails_received = filtering($user_inmails['adhoc_inmails_received'], 'output', 'int');
                $adhoc_inmails_outstanding = filtering($user_inmails['adhoc_inmails_outstanding'], 'output', 'int');

                $adhoc_inmails_utilized = $adhoc_inmails_received - $adhoc_inmails_outstanding;

                $no_of_remaining_days = getDateDiff(date("Y-m-d"), date("Y-m-d", $adhoc_inmails_expires_on), 'day');

                $fields_replace = array(
                    filtering($plan_details['plan_name']),
                    convertDate('onlyDate', $plan_details['subscribed_on']),
                    $adhoc_inmails_received,
                    $adhoc_inmails_utilized,
                    $adhoc_inmails_outstanding,
                    $no_of_remaining_days
                );

                $purchased_adhoc_inmails_details = str_replace($fields, $fields_replace, $plan_single_row_parsed);
            }
        }

        if (!$membership_plan_purchased) {
            $no_records = new Templater(DIR_ADMIN_TMPL . $this->module . '/no-records-nct.tpl.php');
            $no_records->set('colspan', '6');
            $no_records->set('no_records_message', "No adhoc inmails found.");
            $purchased_adhoc_inmails_details = $no_records->parse();
        }

        $plan_container_tpl->set('purchased_adhoc_inmails_details', $purchased_adhoc_inmails_details);

        $final_result = $plan_container_tpl->parse();

        //_print($final_result);exit;
        return $final_result;
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
        
        $user_id = filtering($this->id, 'input', 'int');
        
        $fields = array(
            "%USER_ID%",
            "%USERs_PROFILE_PICTURE_URL%",
            "%USER_NAME%",
            "%EMAIL_ADDRESS%",
            "%EXPERIENCE_URL%",
            "%EDUCATION_URL%",
            "%LANGUAGES_URL%",
            "%SKILLS_URL%",
            "%MY_PAGES_URL%",
            "%FOLLOWING_URL%",
            "%MY_JOBS_URL%",
            "%APPLIED_JOBS_URL%",
            "%SAVED_JOBS_URL%",
            "%MY_GROUPS_URL%",
            "%JOINED_GROUPS_URL%",
            "%CONNECTIONS_URL%",
            "%MEMBERSHIP_PLANS_URL%",
            "%ADHOC_INMAILS_URL%",
            "%BASIC_INFORMATION_ACTIVE_CLASS%",
            "%EXPERIENCE_ACTIVE_CLASS%",
            "%EXPERIENCE_CONTENT%",
            "%EDUACATION_ACTIVE_CLASS%",
            "%EDUACATION_CONTENT%",
            "%LANGUAGES_ACTIVE_CLASS%",
            "%LANGUAGES_CONTENT%",
            "%SKILLS_ACTIVE_CLASS%",
            "%SKILLS_CONTENT%",
            "%COMPANY_ACTIVE_CLASS%",
            "%MY_PAGES_ACTIVE_CLASS%",
            "%MY_PAGES_CONTENT%",
            "%FOLLOWING_ACTIVE_CLASS%",
            "%FOLLOWING_CONTENT%",
            "%JOB_ACTIVE_CLASS%",
            "%MY_JOBS_ACTIVE_CLASS%",
            "%MY_JOBS_CONTENT%",
            "%APPLIED_JOBS_ACTIVE_CLASS%",
            "%APPLIED_JOBS_CONTENT%",
            "%SAVED_JOBS_ACTIVE_CLASS%",
            "%SAVED_JOBS_CONTENT%",
            "%GROUPS_ACTIVE_CLASS%",
            "%MY_GROUPS_ACTIVE_CLASS%",
            "%MY_GROUPS_CONTENT%",
            "%JOINED_GROUPS_ACTIVE_CLASS%",
            "%JOINED_GROUPS_CONTENT%",
            "%CONNECTIONS_ACTIVE_CLASS%",
            "%CONNECTIONS_CONTENT%",
            "%MEMBERSHIP_PLANS_ACTIVE_CLASS%",
            "%MEMBERSHIP_PLANS_CONTENT%",
            "%ADHOC_INMAILS_ACTIVE_CLASS%",
            "%ADHOC_INMAILS_CONTENT%",
            // "%USER_HEADLINE%",
            "%USER_LANGUAGES%",
            "%USER_SKILLS%",
            "%USER_LOCATION%",
            "%USER_COVER_PHOTO%"
        );

        $experience_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/experience";
        $education_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/education";
        $languages_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/languages";
        $skills_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/skills";
        $my_pages_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/my_pages";
        $following_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/following";
        $my_jobs_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/my_jobs";
        $applied_jobs_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/applied_jobs";
        $saved_jobs_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/saved_jobs";
        $my_groups_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/my_groups";
        $joined_groups_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/joined_groups";
        $connections_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/connections";
        $membership_plans_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/membership_plans";

        $adhoc_inmails_url = SITE_ADMIN_URL . "user-dashboard/" . $this->id . "/action/adhoc_inmails";

        $basic_information_active_class = "";

        $experience_active_class = $experience_content = "";
        $education_active_class = $education_content = "";
        $languages_active_class = $languages_content = "";
        $skills_active_class = $skills_content = "";

        $company_active_class = $my_pages_active_class = $my_pages_content = $following_active_class = $following_content = "";

        $job_active_class = $my_jobs_active_class = $my_jobs_content = $applied_jobs_active_class = $applied_jobs_content = $saved_jobs_active_class = $saved_jobs_content = "";

        $groups_active_class = $my_groups_active_class = $my_groups_content = $joined_groups_active_class = $joined_groups_content = "";

        $connections_active_class = $connections_content = "";
        $membership_plans_active_class = $membership_plans_content = "";

        $adhoc_inmails_active_class = $adhoc_inmails_content = "";


        $action = filtering($_REQUEST['action']);
        switch ($action) {
            // case "experience": {
            //         $basic_information_active_class = $experience_active_class = "active";
            //         $my_pages_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

            //         $experience_content = $this->getExperience($user_id);
            //         break;
            //     }
            case "education": {
                    $basic_information_active_class = $education_active_class = "active";
                    $my_pages_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    $education_content = $this->getEducation($user_id);
                    break;
                }
            case "languages": {
                    $basic_information_active_class = $languages_active_class = "active";
                    $my_pages_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    $languages_content = $this->getLanguages($user_id);
                    break;
                }
            case "skills": {
                    $basic_information_active_class = $skills_active_class = "active";
                    $my_pages_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    $skills_content = $this->getSkills($user_id);
                    break;
                }
            case "my_pages": {
                    $company_active_class = $my_pages_active_class = "active";
                    $experience_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    $my_pages_content = $this->getMyPages($user_id);
                    break;
                }
            case "following": {
                    $company_active_class = $following_active_class = "active";
                    $experience_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    $following_content = $this->getFollowing($user_id);
                    break;
                }
            case "my_jobs": {
                    $job_active_class = $my_jobs_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_groups_active_class = "active";

                    $my_jobs_content = $this->getMyJobs($user_id);
                    break;
                }
            case "applied_jobs": {
                    $job_active_class = $applied_jobs_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_groups_active_class = "active";

                    $applied_jobs_content = $this->getAppliedJobs($user_id);
                    break;
                }
             case "saved_jobs": {
                    $job_active_class = $saved_jobs_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_groups_active_class = "active";

                    $saved_jobs_content = $this->getSavedJobs($user_id);
                    break;
                }
            case "my_groups": {
                    $groups_active_class = $my_groups_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_jobs_active_class = "active";

                    $my_groups_content = $this->getMyGroups($user_id);
                    break;
                }
            case "joined_groups": {
                    $groups_active_class = $joined_groups_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_jobs_active_class = "active";

                    $joined_groups_content = $this->getJoinedGroups($user_id);
                    break;
                }
            case "connections": {
                    $connections_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    //_print($_REQUEST);EXIT;

                    if(isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
                        $page = $_REQUEST['page'];
                    }  else {
                        $page = 1;
                    }

                    $connections_content = $this->getUserConnections($user_id, $page);
                    break;
                }
            case "membership_plans": {
                    $membership_plans_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    $membership_plans_content = $this->getMembershipPlans($user_id);
                    break;
                }
            case "adhoc_inmails": {
                    $adhoc_inmails_active_class = "active";
                    $experience_active_class = $my_pages_active_class = $my_jobs_active_class = $my_groups_active_class = "active";

                    $adhoc_inmails_content = $this->getAdhocInmails($user_id);
                    break;
                }
        }
        if ($this->profile_picture_name == "") {
            $profile_picture_name = "default_profile_pic.png";
        } else {
            $profile_picture_name = $this->profile_picture_name;
        }
        // $users_profile_picture_url = getUserProfilePictureURL($this->id, "th4");
        
        //$users_profile_picture_url = SITE_URL . "image/" . DIR_NAME_USERS . "/" . $profile_picture_name . "?w=150&h=150";
        // $user_cover= getImageURL("user_cover_picture",$this->id,"th1",'web');
        $users_profile_picture_url = $user_cover = '';
        require_once(DIR_ADM_MOD . 'storage.php');
        $user_dashboard_view = new storage();
        $src2 = DIR_NAME_USERS."/".$this->id."/";
        $src_cover = "user_cover-nct/".$this->id."/";
        
        $users_profile_picture = $user_dashboard_view->getImageUrl1('av8db','th4_'.$profile_picture_name,$src2);
        $up1 = getimagesize($users_profile_picture);
        
         if (empty($up1)) {
            $users_profile_picture_url = '<span title="' . $this->first_name.' ' .$this->last_name .'" class="profile-picture-character">' . ucfirst($this->first_name[0]) . '</span>';
        }else 
        {   
            $users_profile_picture_url ='<picture>
                                    <source srcset="' . $users_profile_picture . '" type="image/jpg">
                                    <img src="' . $users_profile_picture . '" class="" alt="img" /> 
                                </picture>';
        }
        $user_cover_img = $user_dashboard_view->getImageUrl1('av8db','th1_'.$this->cover_photo,$src_cover);
        
        $up = getimagesize($user_cover_img);
        
        if (empty($up)) {
            $user_cover = "https://storage.googleapis.com/av8db/u-pro-bg.jpg";
        }else 
        {   
            $user_cover = $user_cover_img;
        }
        $fields_replace = array(
            $this->id,
            $users_profile_picture_url,
            $this->first_name . " " . $this->last_name,
            $this->email_address,
            $experience_url,
            $education_url,
            $languages_url,
            $skills_url,
            $my_pages_url,
            $following_url,
            $my_jobs_url,
            $applied_jobs_url,
            $saved_jobs_url,
            $my_groups_url,
            $joined_groups_url,
            $connections_url,
            $membership_plans_url,
            $adhoc_inmails_url,
            $basic_information_active_class,
            $experience_active_class,
            $experience_content,
            $education_active_class,
            $education_content,
            $languages_active_class,
            $languages_content,
            $skills_active_class,
            $skills_content,
            $company_active_class,
            $my_pages_active_class,
            $my_pages_content,
            $following_active_class,
            $following_content,
            $job_active_class,
            $my_jobs_active_class,
            $my_jobs_content,
            $applied_jobs_active_class,
            $applied_jobs_content,
            $saved_jobs_active_class,
            $saved_jobs_content,
            $groups_active_class,
            $my_groups_active_class,
            $my_groups_content,
            $joined_groups_active_class,
            $joined_groups_content,
            $connections_active_class,
            $connections_content,
            $membership_plans_active_class,
            $membership_plans_content,
            $adhoc_inmails_active_class,
            $adhoc_inmails_content,
            //getUserHeadline($this->id) != false ? getUserHeadline($this->id) : "-",
            getUserLanguages($this->id) != false ? implode(", ",getUserLanguages($this->id)) : "-",
            getUserSkills($this->id) != false ? implode(", ",getUserSkills($this->id)) : "-",
            $this->location,
            $user_cover

        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }
}