<?php class Search extends Home {
    function __construct($current_user_id=0,$platform='web') {

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->platform = $platform;
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
        

        $getCurrentUsersIndustry = $this->db->pdoQuery("SELECT ue.industry_id FROM tbl_users u 
                    LEFT JOIN tbl_user_experiences ue ON ue.user_id = u.id  AND ue.is_current = 'y'  
                    LEFT JOIN tbl_companies c ON c.id = ue.company_id 
                    WHERE u.email_verified = ? AND u.status = ? AND u.id = ? ",array('y','a',$this->current_user_id))->result();
        $this->usersCurrentIndustry = '';
        if ($getCurrentUsersIndustry) {
            $this->usersCurrentIndustry = $getCurrentUsersIndustry['industry_id'];
        }

        $this->formatted_address = $this->address1 = $this->address2 = $this->country = $this->state = "";
        $this->city1 = $this->city2 = $this->postal_code = $this->latitude = $this->longitude = "";
        $industry_id = getTableValue("tbl_users", "location_id", array("id" => $this->current_user_id));
        if ($industry_id) {
            $currentLocationDetails=$this->db->select("tbl_locations", array('formatted_address,address1,address2,country,state,city1,city2,postal_code,latitude,longitude'), array("id" => $industry_id))->result();
            if ($currentLocationDetails) {
                $this->formatted_address = filtering($currentLocationDetails['formatted_address']);
                $this->address1 = filtering($currentLocationDetails['address1']);
                $this->address2 = filtering($currentLocationDetails['address2']);
                $this->country = filtering($currentLocationDetails['country']);
                $this->state = filtering($currentLocationDetails['state']);
                $this->city1 = filtering($currentLocationDetails['city1']);
                $this->city2 = filtering($currentLocationDetails['city2']);
                $this->postal_code = filtering($currentLocationDetails['postal_code']);
                $this->latitude = filtering($currentLocationDetails['latitude']);
                $this->longitude = filtering($currentLocationDetails['longitude']);
            }
        }
    }
    public function getEmploymentTypeFilter($filter_type = "", $employment_type_filter_hidden) {
        $final_result = NULL;
        $employment_type_filter = new Templater(DIR_TMPL . $this->module . "/employment-type-filter-nct.tpl.php");
        $employment_type_filter_parsed = $employment_type_filter->parse();
        $fields = array("%FILTER_TYPE%","%EMPLOYMENT_TYPE_FILTER_HIDDEN%","%FULL_TIME_CHECKED%","%PART_TIME_CHECKED%","%CONTRACT_CHECKED%","%TEMPORARY_CHECKED%");
        $full_time_checked = $part_time_checked = $contract_checked = $temporary_checked = "";
        if (isset($_GET['employment_type']) && !empty($_GET['employment_type'])) {
            $employment_type = $_GET['employment_type'];
            if (in_array('f', $employment_type)) {
                $full_time_checked = ' checked="checked" ';
            }
            if (in_array('p', $employment_type)) {
                $part_time_checked = ' checked="checked" ';
            }
            if (in_array('c', $employment_type)) {
                $contract_checked = ' checked="checked" ';
            }
            if (in_array('t', $employment_type)) {
                $temporary_checked = ' checked="checked" ';
            }
        }
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $employment_type_filter_hidden ),
            $full_time_checked,
            $part_time_checked,
            $contract_checked,
            $temporary_checked
        );
        $final_result = str_replace($fields, $fields_replace, $employment_type_filter_parsed);
        return $final_result;
    }
    public function getCompanySizes($currentPage = 1, $filter_type = "") {
        $final_result = NULL;
        $limit = 10;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $query = "SELECT id,company_size_".$this->lId." as company_size FROM tbl_company_sizes WHERE status = ? ";
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $companySizes = $this->db->pdoQuery($query_with_limit,array('a'))->results();
        if ($companySizes) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_records = $this->db->pdoQuery($query_with_next_limit,array('a'))->results();
            $next_available_records = count($next_records);
            $single_company_size_li_filter = new Templater(DIR_TMPL . $this->module . "/single-company-size-li-filter-nct.tpl.php");
            $single_company_size_li_filter_parsed = $single_company_size_li_filter->parse();
            $fields = array("%FILTER_TYPE%","%COMPANY_SIZE_ID%","%COMPANY_SIZE%","%CHECKED%");
            $selectedCompanySizes = array();
            if (isset($_GET['company_sizes']) && !empty($_GET['company_sizes'])) {
                $selectedCompanySizes = $_GET['company_sizes'];
            }
            for ($i = 0; $i < count($companySizes); $i++) {
                $fields_replace = array(
                    $filter_type,
                    $companySizes[$i]['id'],
                    filtering($companySizes[$i]['company_size']),
                    ( ( in_array($companySizes[$i]['id'], $selectedCompanySizes) ) ? 'checked="checked"' : '' )
                );
                $final_result .= str_replace($fields, $fields_replace, $single_company_size_li_filter_parsed);
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-groups/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_li_tpl->parse();
            }
        }
        return $final_result;
    }
    public function getCompanySizesFilter($filter_type = "", $company_size_filter_hidden) {
        $final_result = NULL;
        $company_size_filters = new Templater(DIR_TMPL . $this->module . "/company-size-filters-nct.tpl.php");
        $company_size_filters->set('company_sizes', $this->getCompanySizes(1, $filter_type));
        $company_size_filters_parsed = $company_size_filters->parse();
        $fields = array("%FILTER_TYPE%","%COMPANY_SIZES_FILTER_HIDDEN%");
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $company_size_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $company_size_filters_parsed);
        return $final_result;
    }
    public function getSortingFilter($filter_type = "", $sortings_filter_hidden) {
        $final_result = NULL;
        $sortings_filter = new Templater(DIR_TMPL . $this->module . "/sortings-filter-nct.tpl.php");
        $sortings_filter_parsed = $sortings_filter->parse();
        $fields = array(
            "%FILTER_TYPE%",
            "%SORTINGS_FILTER_HIDDEN%"
        );

        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $location_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $sortings_filter_parsed);
        return $final_result;
    }
    public function getJobCategories($currentPage = 1, $filter_type = "") {
        $final_result = NULL;
        $limit = 10;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $query = "SELECT id,job_category_".$this->lId." as job_category FROM tbl_job_category WHERE status = ? ";
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $jobCategories = $this->db->pdoQuery($query_with_limit,array('a'))->results();
        if ($jobCategories) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_records = $this->db->pdoQuery($query_with_next_limit,array('a'))->results();
            $next_available_records = count($next_records);
            $single_job_category_li_filter = new Templater(DIR_TMPL . $this->module . "/single-job-category-li-filter-nct.tpl.php");
            $single_job_category_li_filter_parsed = $single_job_category_li_filter->parse();
            $fields = array("%FILTER_TYPE%","%JOB_CATEGORY_ID%","%JOB_CATEGORY%","%CHECKED%");
            $selectedJobCategories = array();
            if (isset($_GET['job_category']) && !empty($_GET['job_category'])) {
                $selectedJobCategories = $_GET['job_category'];
            }
            for ($i = 0; $i < count($jobCategories); $i++) {
                $fields_replace = array(
                    $filter_type,
                    $jobCategories[$i]['id'],
                    filtering($jobCategories[$i]['job_category']),
                    ( ( in_array($jobCategories[$i]['id'], $selectedJobCategories) ) ? 'checked="checked"' : '' )
                );
                $final_result .= str_replace($fields, $fields_replace, $single_job_category_li_filter_parsed);
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-job-categories/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_li_tpl->parse();
            }
        }
        return $final_result;
    }
    public function getJobCategoriesFilter($filter_type = "", $job_categories_filter_hidden) {
        $final_result = NULL;
        $job_category_filters = new Templater(DIR_TMPL . $this->module . "/job-category-filters-nct.tpl.php");
        $job_category_filters->set('job_categories', $this->getJobCategories());
        $job_category_filters_parsed = $job_category_filters->parse();
        $fields = array("%FILTER_TYPE%","%JOB_CATEGORIES_FILTER_HIDDEN%");
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $job_categories_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $job_category_filters_parsed);
        return $final_result;
    }
    public function getGroupsForFilter($currentPage = 1, $filter_type = "") {
        $final_result = NULL;
        $limit = 10;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $query = "SELECT id,group_name FROM tbl_groups WHERE status = ? AND privacy = ? ";
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $wherearr=array('a','pu');
        $groups = $this->db->pdoQuery($query_with_limit,$wherearr)->results();
        $count = $this->db->count('tbl_groups',array('privacy'=>'pu','status'=>'a'));
        if ($groups) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_records = $this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records = count($next_records);
            $single_group_li_filter=new Templater(DIR_TMPL.$this->module . "/single-group-li-filter-nct.tpl.php");
            $single_group_li_filter_parsed = $single_group_li_filter->parse();
            $fields = array("%FILTER_TYPE%","%GROUP_ID%","%GROUP_NAME%","%CHECKED%");
            $selectedGroups = array();
            if (isset($_GET['groups']) && !empty($_GET['groups'])) {
                $selectedGroups = $_GET['groups'];
            }
            for ($i = 0; $i < count($groups); $i++) {
                $group_name = filtering($groups[$i]['group_name']);
                $fields_replace = array(
                    $filter_type,
                    $groups[$i]['id'],
                    $group_name,
                    ( ( in_array($groups[$i]['id'], $selectedGroups) ) ? 'checked="checked"' : '' )
                );
                if($this->platform == 'app'){
                    $app_array[] = array('id'=>$groups[$i]['id'],'group_name'=>$group_name);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_group_li_filter_parsed);
                }
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-groups/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_li_tpl->parse();
            }
        }
        if($this->platform=='app'){

            $page_data = getPagerData($count, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$count);
            $final_app = array('groups'=>(!empty($app_array)?$app_array:array()),'pagination'=>$pagination);

            return $final_app;            
        } else {
            return $final_result;
        }
    }
    public function getGroupsFilter($filter_type = "", $adv_groups_filter_hidden) {
        $final_result = NULL;
        $group_filters = new Templater(DIR_TMPL . $this->module . "/group-filters-nct.tpl.php");
        $group_filters->set('groups', $this->getGroupsForFilter(1, $filter_type));
        $group_filters_parsed = $group_filters->parse();
        $fields = array("%FILTER_TYPE%","%ADV_GROUPS_FILTER_HIDDEN%");
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $adv_groups_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $group_filters_parsed);
        return $final_result;
    }
    public function getIndustries($currentPage = 1, $filter_type = "") {
        $final_result = NULL;
        $limit = 15;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $query = "SELECT id,industry_name_".$this->lId." as industry_name FROM tbl_industries WHERE status = ? ";
        $wherearr=array('a');
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $industries = $this->db->pdoQuery($query_with_limit,$wherearr)->results();
        if ($industries) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_records = $this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records = count($next_records);
            $single_industry_li_filter = new Templater(DIR_TMPL . $this->module . "/single-industry-li-filter-nct.tpl.php");
            $single_industry_li_filter_parsed = $single_industry_li_filter->parse();
            $fields = array("%FILTER_TYPE%","%INDUSTRY_ID%","%INDUSTRY_NAME%","%CHECKED%");
            $selectedIndustries = array();
            if (isset($_GET['industries']) && !empty($_GET['industries'])) {
                $selectedIndustries = $_GET['industries'];
            }
            for ($i = 0; $i < count($industries); $i++) {
                $fields_replace = array(
                    $filter_type,
                    $industries[$i]['id'],
                    filtering($industries[$i]['industry_name']),
                    ( ( in_array($industries[$i]['id'], $selectedIndustries) ) ? 'checked="checked"' : '' )
                );
                $final_result .= str_replace($fields, $fields_replace, $single_industry_li_filter_parsed);
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-industries/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_li_tpl->parse();
            }
        }
        return $final_result;
    }
    public function getIndustriesFilter($filter_type = "", $industries_filter_hidden) {
        $final_result = NULL;
        $industry_filters = new Templater(DIR_TMPL . $this->module . "/industry-filters-nct.tpl.php");
        $industry_filters->set('industries', $this->getIndustries(1, $filter_type));
        $industry_filters_parsed = $industry_filters->parse();
        $fields = array("%FILTER_TYPE%","%INDUSTRIES_FILTER_HIDDEN%");
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $industries_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $industry_filters_parsed);
        return $final_result;
    }
    public function getCompaniesForFilter($currentPage = 1, $filter_type = "") {
        $final_result = NULL;
        $limit = 10;
        $next_available_records = 0;
        $offset = ($currentPage - 1 ) * $limit;
        $count = $this->db->count('tbl_companies',array('company_type'=>'r','status'=>'a'));
        $query = "SELECT id,company_name FROM tbl_companies WHERE company_type = ? AND status = ? AND user_id != ?";
        $wherearr=array('r','a',$this->current_user_id);
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $companies = $this->db->pdoQuery($query_with_limit,$wherearr)->results();
        if ($companies) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
            $next_records = $this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records = count($next_records);
            $single_compnay_li_filter = new Templater(DIR_TMPL . $this->module . "/single-compnay-li-filter-nct.tpl.php");
            $single_compnay_li_filter_parsed = $single_compnay_li_filter->parse();
            $fields = array("%FILTER_TYPE%","%COMPANY_ID%","%COMPANY_NAME%","%CHECKED%");
            for ($i = 0; $i < count($companies); $i++) {
                $checked = "";
                if (isset($_GET['company']) && is_array($_GET['company']) && in_array($companies[$i]['id'], $_GET['company'])) {
                    $checked = ' checked="checked" ';
                }
                $company_name = filtering($companies[$i]['company_name']);
                $fields_replace = array(
                    $filter_type,
                    $companies[$i]['id'],
                    $company_name,
                    $checked
                );
                if($this->platform == 'app'){
                    $app_array[] = array('id'=>$companies[$i]['id'],'company_name'=>$company_name);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $single_compnay_li_filter_parsed);
                }
            }
            if ($next_available_records > 0) {
                $load_more_li_tpl = new Templater(DIR_TMPL . "load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "load-more-companies/page/" . ($currentPage + 1);
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $final_result .= $load_more_li_tpl->parse();
            }
        }
        if($this->platform=='app'){

            $page_data = getPagerData($count, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$count);
            $final_app = array('companies'=>(!empty($app_array)?$app_array:array()),'pagination'=>$pagination);

            return $final_app;            
        } else {
            return $final_result;
        }
    }
    public function getCompaniesFilter($filter_type = "", $adv_company_filter_hidden) {
        $final_result = NULL;
        $company_filters = new Templater(DIR_TMPL . $this->module . "/company-filters-nct.tpl.php");
        $company_filters->set('companies', $this->getCompaniesForFilter(1, $filter_type));
        $company_filters_parsed = $company_filters->parse();
        $fields = array("%FILTER_TYPE%","%ADV_CURRENT_COMPANY_FILTER_HIDDEN%");
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $adv_company_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $company_filters_parsed);
        return $final_result;
    }
    public function getLocationFilter($filter_type = "", $location_filter_hidden) {
        $final_result = NULL;
        $location_filter = new Templater(DIR_TMPL . $this->module . "/location-filter-nct.tpl.php");
        $location_filter_parsed = $location_filter->parse();
        $fields = array(
            "%FILTER_TYPE%",
            "%LOCATION%",
            "%FORMATTED_ADDRESS%",
            "%ADDRESS1%",
            "%ADDRESS2%",
            "%COUNTRY%",
            "%STATE%",
            "%CITY1%",
            "%CITY2%",
            "%POSTAL_CODE%",
            "%LATTITUDE%",
            "%LONGITUDE%",
            "%LOCATION_FILTER_HIDDEN%"
        );

        $fields_replace = array(
            $filter_type,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ( ( $filter_type == "adv_" ) ? "" : $location_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $location_filter_parsed);
        return $final_result;
    }
    public function getNoOfFollowersFilter($filter_type = "", $no_of_members_filter_hidden) {
        $final_result = NULL;
        $followers_range_tpl = new Templater(DIR_TMPL . $this->module . "/followers-range-nct.tpl.php");
        $followers_range_tpl_parsed = $followers_range_tpl->parse();
        $fields = array("%FILTER_TYPE%","%NO_OF_MEMBERS_FILTER_HIDDEN%");
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $no_of_members_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $followers_range_tpl_parsed);
        return $final_result;
    }
    public function getRelationshipFilter($filter_type = "", $relationship_filter_hidden) {
        $final_result = NULL;
        $relationship_filters = new Templater(DIR_TMPL . $this->module . "/relationship-filters-nct.tpl.php");
        $relationship_filters_parsed = $relationship_filters->parse();
        $fields = array("%FILTER_TYPE%","%RELATIONSHIP_FILTER_HIDDEN%");
        $fields_replace = array(
            $filter_type,
            ( ( $filter_type == "adv_" ) ? "" : $relationship_filter_hidden )
        );
        $final_result = str_replace($fields, $fields_replace, $relationship_filters_parsed);
        return $final_result;
    }
    public function getUsers($current_user_id, $currentPage) {
        $response = array();
        $response['status'] = false;
        $users_html = $pagination = $applied_filters = "";
        $totalUsers = $next_available_records = 0;
        $limit = NO_OF_SEARCH_RESULTS_PER_PAGE;
        $offset = ($currentPage - 1) * $limit;
        $applied_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-filter-li-nct.tpl.php");
        $applied_filter_li_tpl_parsed = $applied_filter_li_tpl->parse();
        $applied_location_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-location-filter-li-nct.tpl.php");
        $applied_location_filter_li_tpl_parsed = $applied_location_filter_li_tpl->parse();
        $fields_applied_filter = array("%FILTER_NAME%","%DATA_ID%");
        $userSkillsLeftJoin = $userSkillsSelection = $userSkillsScore = "";

        $currentUsersSkillsArray = array();
        $currentUsersSkills = $this->db->select("tbl_user_skills", array('skill_id'), array("user_id" => $this->current_user_id))->results();
        if ($currentUsersSkills) {
            for ($i = 0; $i < count($currentUsersSkills); $i++) {
                $currentUsersSkillsArray[] = $currentUsersSkills[$i]['skill_id'];
            }
            $currentUsersSkillsImploded = implode(",", $currentUsersSkillsArray);
            $userSkillsSelection = " COUNT(us.id) as no_of_skills_matched, ";
            $userSkillsScore = " + COUNT(us.id) ";
            $userSkillsLeftJoin = " LEFT JOIN tbl_user_skills us ON us.user_id = u.id AND us.skill_id IN ( " . $currentUsersSkillsImploded . " ) ";
        }
        $locationsScore = $separateLocationsScore = "";
        if ($this->formatted_address != "") {
            $separateLocationsScore .= "  ( ";
            if ($this->country != "") {
                $locationsScore .= " + IF(l.country = '" . $this->country . "', 1, 0) ";
                $separateLocationsScore .= " IF(l.country = '" . $this->country . "', 1, 0) ";
            }
            if ($this->state != "") {
                $locationsScore .= " + IF(l.state = '" . $this->state . "', 1, 0) ";
                $separateLocationsScore .= " + IF(l.state = '" . $this->state . "', 1, 0) ";
            }
            if ($this->city1 != "") {
                $locationsScore .= " + IF(l.city1 = '" . $this->city1 . "', 1, 0) ";
                $separateLocationsScore .= " + IF(l.city1 = '" . $this->city1 . "', 1, 0) ";
            }
            if ($this->city2 != "") {
                $locationsScore .= " + IF(l.city2 = '" . $this->city2 . "', 1, 0) ";
                $separateLocationsScore .= " + IF(l.city2 = '" . $this->city2 . "', 1, 0) ";
            }
            $separateLocationsScore .= " ) as location_score, ";
        } else {
            $separateLocationsScore = " 0 as location_score, ";
        }
        
        $query = "SELECT u.id,u.first_name,u.last_name, i.industry_name_".$this->lId." as industry_name, l.formatted_address, 
                    IF( ue.industry_id = '" . $this->usersCurrentIndustry . "', 5, 0 ) as industry_matched,
                    " . $userSkillsSelection . $separateLocationsScore . "
                    ( IF( ue.industry_id = '" . $this->usersCurrentIndustry . "', 5, 0 ) " . $userSkillsScore . $locationsScore . " ) as total_score 
                    FROM tbl_users u 
                    LEFT JOIN tbl_user_experiences ue ON ue.user_id = u.id  AND ue.is_current='y'
                    LEFT JOIN tbl_companies c ON c.id = ue.company_id 
                    LEFT JOIN tbl_industries i ON i.id = ue.industry_id 
                    LEFT JOIN tbl_locations l ON l.id = u.location_id 
                    " . $userSkillsLeftJoin . "
                    WHERE u.email_verified = ? AND u.status = ? AND u.id != ?  ";

        $wherearr=array('y','a',$this->current_user_id);
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = filtering($_GET['keyword'], 'input');
            $query .= " AND ( u.first_name LIKE '%" . $keyword . "%' OR u.last_name LIKE '%" . $keyword . "%' OR CONCAT( u.first_name, ' ', u.last_name ) LIKE '%" . $keyword . "%' OR u.email_address LIKE '%" . $keyword . "%' OR ue.job_title LIKE '%" . $keyword . "%' ) ";
        }
        
        if (isset($_GET['relationship']) && !empty($_GET['relationship'])) {
            $relationship = $_GET['relationship'];
            if (in_array('1', $relationship)) {
                $connectedMembers = getConnections($this->current_user_id, true);
                if ($connectedMembers) {
                    $query .= " AND u.id IN (" . implode(",", $connectedMembers) . ") ";
                } else {
                    $query .= " AND u.id IN (0) ";
                }
                $fields_replace_applied_filter = array("{LBL_FIRST} {LBL_CONN} ","adv_relationship_fc");
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
            if (in_array('2', $relationship)) {
                $secondDegreeConnections = getSecondDegreeConnections($this->current_user_id);
                if (in_array('1', $relationship)) {
                    if ($secondDegreeConnections) {
                        $query .= " OR u.id IN (" . implode(",", $secondDegreeConnections) . ") ";
                    } else {
                        $query .= " OR u.id IN (0) ";
                    }
                } else {
                    if ($secondDegreeConnections) {
                        $query .= " AND u.id IN (" . implode(",", $secondDegreeConnections) . ") ";
                    } else {
                        $query .= " AND u.id IN (0) ";
                    }
                }
                $fields_replace_applied_filter = array("{LBL_SECOND} {LBL_CONN}","adv_relationship_sc");
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }
        if (isset($_GET['groups']) && !empty($_GET['groups'])) {
            $groups = $_GET['groups'];
            $members = getGroupMembers($groups);
            if (!empty($members)) {
                $menbersIds = implode(',', $members);
                $query .= " AND u.id IN (" . $menbersIds . ") ";
            } else {
                $query .= " AND u.id IN (0) ";
            }
        }
        if (isset($_GET['latitude']) && $_GET['latitude'] != "") {
            $formatted_address = filtering($_GET['formatted_address'], 'input');
            $address1 = filtering($_GET['address1'], 'input');
            $address2 = filtering($_GET['address2'], 'input');
            $country = filtering($_GET['country'], 'input');
            $state = filtering($_GET['state'], 'input');
            $city1 = filtering($_GET['city1'], 'input');
            $city2 = filtering($_GET['city2'], 'input');
            if ($formatted_address != "") {
                $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
            }
            if ($address1 != "") {
                $query .= " AND l.address1 = '" . $address1 . "' ";
            }
            if ($address2 != "") {
                $query .= " AND l.address2 = '" . $address2 . "' ";
            }
            if ($country != "") {
                $query .= " AND l.country = '" . $country . "' ";
            }
            if ($state != "") {
                $query .= " AND l.state = '" . $state . "' ";
            }
            if ($city1 != "") {
                $query .= " AND l.city1 = '" . $city1 . "' ";
            }
            if ($city2 != "") {
                //$query .= " AND l.city2 = '" . $city2 . "' ";
            }
            $fields_replace_applied_filter = array(
                filtering($_GET['formatted_address']),
                ""
            );
            $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
        }  else if($_GET['location'] != ''){
            $reverse_geocode = reverse_geocode($_GET['location']);
            if($reverse_geocode['status'] == 'OK'){
                $formatted_address = filtering($_GET['location'], 'input');
                $address1 = filtering($reverse_geocode['address'], 'input');
                $country = filtering($reverse_geocode['country'], 'input');
                $state = filtering($reverse_geocode['state'], 'input');
                $city1 = filtering($reverse_geocode['city'], 'input');
                if ($formatted_address != "") {
                    $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
                }
                if ($address1 != "") {
                    $query .= " AND l.address1 = '" . $address1 . "' ";
                }
                if ($address2 != "") {
                    $query .= " AND l.address2 = '" . $address2 . "' ";
                }
                if ($country != "") {
                    $query .= " AND l.country = '" . $country . "' ";
                }
                if ($state != "") {
                    $query .= " AND l.state = '" . $state . "' ";
                }
                if ($city1 != "") {
                    $query .= " AND l.city1 = '" . $city1 . "' ";
                }
                if ($city2 != "") {
                    //$query .= " AND l.city2 = '" . $city2 . "' ";
                }
                $fields_replace_applied_filter = array(
                    filtering($formatted_address),
                    ""
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
            }
        }
        if (isset($_GET['industries']) && !empty($_GET['industries']) && implode(",", $_GET['industries']) != "") {
            $industries = $_GET['industries'];
            $query .= " AND ue.industry_id IN (" . implode(",", $industries) . ") ";
            for ($i = 0; $i < count($industries); $i++) {
                $industry_name = getTableValue("tbl_industries", "industry_name", array("id" => $industries[$i]));
                $fields_replace_applied_filter = array(
                    $industry_name,
                    "adv_industry_" . $industries[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }
        if (isset($_GET['company']) && !empty($_GET['company'])) {
            $company = $_GET['company'];
            $query .= " AND c.id IN (" . implode(",", $company) . ") ";
            for ($i = 0; $i < count($company); $i++) {
                $company_name = getTableValue("tbl_companies", "company_name", array("id" => $company[$i]));
                $fields_replace_applied_filter = array(
                    $company_name,
                    "adv_company_" . $company[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }
        $groupsLeftJoinQuery = "";
        if (isset($_GET['groups']) && !empty($_GET['groups'])) {
            $groups = $_GET['groups'];
            $groupsLeftJoinQuery = " LEFT JOIN tbl_groups g ON g.user_id = u.id AND g.id IN ( " . implode(",", $groups) . " ) 
                                     LEFT JOIN tbl_group_members gm ON gm.group_id = g.id ";
            for ($i = 0; $i < count($groups); $i++) {
                $group_name = getTableValue("tbl_groups", "group_name", array("id" => $groups[$i]));
                $groupsWhereQuery = " AND (  ) ";
                $fields_replace_applied_filter = array(
                    $group_name,
                    "adv_group_" . $groups[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }
        //$query .= " GROUP BY u.id ORDER BY total_score DESC, u.id ASC ";
        $query .="GROUP BY u.id ORDER BY  CASE WHEN ue.is_headline THEN 1 WHEN ue.industry_id THEN 2 WHEN  u.location_id THEN 3 ELSE 4 END ,u.id ASC";
        //echo $query;exit;
        $totalUsers = count($this->db->pdoQuery($query,$wherearr)->results());
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $users = $this->db->pdoQuery($query_with_limit,$wherearr)->results();

        if ($users) {
            //echo "<pre>";print_r($users);exit;
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
            $next_users = $this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records = count($next_users);
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/single-user-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $fields = array(
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                "%HEADLINE%",
                "%INDUSTRY_NAME%",
                "%FORMATTED_ADDRESS%",
                "%SCORE%",
                "%CONNECT_BUTTON%",
                "%SKILLS%",
                "%DEGREE_OF_CONNECTION%",
                "%COMMON_CONNECTION%",
                "%SEND_INMAIL_CLASS%",
                "%SEND_INMAIL_TITLE%",
                "%SEND_INMAIL_TEXT%",
                "%SEND_INMAIL_URL%",
                "%CLASS_FOLLOW%",
                "%USER_ID%",
                "%USER_STATUS%",
                "%FOLLOW_TAG%",
                "%CLASS_INS%",
                "%CLASS_SKILL_HIDE%",
                "%CLASS_LOCATION%"
            );
            for ($i = 0; $i < count($users); $i++) {
                //echo "<pre>";print_r($users[$i]);exit;
                $connection_status = '';
                $user_actions = null;
                $user_id = $users[$i]['id'];
                $user_profile_url = get_user_profile_url($user_id);
                $first_name = filtering($users[$i]['first_name']);
                $last_name = filtering($users[$i]['last_name']);
                $user_name_full = $first_name . " " . $last_name;
                $connected_user_ids = getConnections($user_id);
                $second_connected_user_ids = getSecondDegreeConnections($user_id, $this->current_user_id);
                if (is_array($connected_user_ids) && in_array($this->current_user_id, $connected_user_ids)) {
                    $user_actions .= $this->addRemoveConnectionUrl($user_id, "remove_connection");
                    $connection_status = 'connected';
                } else {
                    $query = "SELECT request_from FROM tbl_connections 
                                WHERE ( ( request_from = ? AND request_to = ? ) OR ( request_from = ? AND request_to = ?  ) ) AND status = ? ";
                    $checkIfRequestIsPending = $this->db->pdoQuery($query,array($user_id,$this->current_user_id,$this->current_user_id,$user_id,'s'))->result();
                    if ($checkIfRequestIsPending) {
                        if ($checkIfRequestIsPending['request_from'] == $this->current_user_id) {
                            $user_actions .= $this->addRemoveConnectionUrl($user_id, "cancel_connection_request");
                            $connection_status = 'requested';
                        } else {
                            $user_actions .= $this->addRemoveConnectionUrl($user_id,"accept_reject_connection_request");
                            $connection_status = 'pending_to_response';
                        }
                    } else {
                        $user_actions .= $this->addRemoveConnectionUrl($user_id, "add_connection");
                        $connection_status = 'not_connected';
                    }
                    //$send_inmail_text = 'Send InMail';
                }
                $skills = implode(' , ', getUserSkills($user_id));
                $common_connection = count(getCommonConnections($user_id, $this->current_user_id));
                $send_inmail_url = '';
                $send_inmail_text = '';

                if (is_array($connected_user_ids) && in_array($this->current_user_id, $connected_user_ids)) {
                    $send_inmail_title = '{LBL_SEND_MSG}';
                    $send_inmail_text = '<i class="icon-mail-o"></i>';
                } else {
                    $send_inmail_title = '{LBL_SEND_INMAIL}';
                    $send_inmail_text = '<i class="icon-email"></i>';
                }
                $send_inmail_url = SITE_URL . 'compose-message/' . encryptIt($user_id);
                $degree_of_connection = $degree_of_connection_tpl_parsed = "";
                $connectedMembers = getConnections($this->current_user_id, true);
                $degree_of_connection = '';
                if (in_array($user_id, $connectedMembers)) {
                    $degree_of_connection = "{LBL_FIRST}";
                } else {
                    $secondDegreeConnections = getSecondDegreeConnections($this->current_user_id);
                    if (in_array($user_id, $secondDegreeConnections)) {
                        $degree_of_connection = "{LBL_SECOND}";
                    }
                }
                if ($degree_of_connection != "") {
                    $degree_of_connection_tpl = new Templater(DIR_TMPL . $this->module . "/degree-of-connection-nct.tpl.php");
                    $degree_of_connection_tpl->set('degree_of_connection', $degree_of_connection);
                    $degree_of_connection_tpl_parsed = $degree_of_connection_tpl->parse();
                }
                $headline = getUserHeadline($user_id);
                $industry_name = isset($users[$i]['industry_name'])?filtering($users[$i]['industry_name']):'';
                $formatted_address = isset($users[$i]['formatted_address'])?filtering($users[$i]['formatted_address']):'';
                $userimage_final=getImageURL("user_profile_picture", $user_id, "th3",$this->platform);
                $class='';
                if($_SESSION['user_id']==0){
                    $class='hidden';
                }
                $status=$getstatus='';
                $follow_tag=LBL_FOLLOW;
                $getstatus = getTableValue("tbl_follower", "status", array("follower_form" =>$current_user_id,'follower_to'=>$user_id));
                if($getstatus != ''){
                    $status=$getstatus;
                    if($getstatus=='f')
                        $follow_tag=LBL_MYC_FOLLOWING;
                    
                }
                $class_ins="";
                if($industry_name=='')
                    $class_ins='hidden';
                $class_skill_hide="";
                if($skills=='')
                    $class_skill_hide="hidden";
                $class_location="";
                if($formatted_address=='')
                    $class_location='hidden';

                $fields_replace = array(
                    $userimage_final,
                    $user_profile_url,
                    ucwords($user_name_full),
                    ucwords($headline),
                    ucwords($industry_name),
                    $formatted_address,
                    filtering($users[$i]['industry_matched']) . "_" . filtering($users[$i]['no_of_skills_matched']) . "_" . filtering($users[$i]['location_score']) . "_" . filtering($users[$i]['total_score']),
                    $user_actions,
                    isset($skills)?$skills:'N/A',
                    $degree_of_connection_tpl_parsed,
                    $common_connection,
                    $send_inmail_class,
                    $send_inmail_title,
                    $send_inmail_text,
                    $send_inmail_url,
                    $class,
                    encryptIt($user_id),
                    $status,
                    $follow_tag,
                    $class_ins,
                    $class_skill_hide,
                    $class_location
                );
                
                if($this->platform == 'app'){
                    $app_array[] = array(
                        'user_id'=>$user_id,
                        'user_name'=>$user_name_full,
                        'userimage'=>$userimage_final,
                        'tagline'=>$headline,
                        'industry_name'=>$industry_name,
                        'location'=>$formatted_address,
                        'degree_of_connection'=>$degree_of_connection,
                        'skills'=>($skills!=''?$skills:''),
                        'common_connection'=>$common_connection,
                        'connection_status'=>$connection_status,
                        'follow_status'=>$status
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
        } else {
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/single-user-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $no_result_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $users_html = $no_result_tpl->parse();
        }
        if ($applied_filters) {
            $reset_applied_filters=new Templater(DIR_TMPL . $this->module . "/reset-applied-filters-nct.tpl.php");
            $applied_filters .= $reset_applied_filters->parse();
        }
        if($this->platform == 'app'){
            $page_data = getPagerData($totalUsers, NO_OF_SEARCH_RESULTS_PER_PAGE,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalUsers);
            $response = array('jobs'=>(!empty($app_array)?$app_array:array()),'pagination'=>$pagination);
        } else {
            $response['status'] = true;
            $response['content'] = $users_html;
            $response['pagination'] = $pagination;
            $response['total_records'] = $totalUsers;
            $response['next_available_records'] = $next_available_records;
            $response['applied_filters'] = $applied_filters;
        }
        return $response;
    }
    public function addRemoveConnectionUrl($user_id, $case) {
        $final_result = "";
        $main_content = new Templater(DIR_TMPL . $this->module . "/add-remove-connection-url-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array("%TITLE%","%TEXT%","%DATA-VALUE%","%CLASS%","%FA_CLASS%","%ICON_CLASS%");
        if ($case == "add_connection") {
            $fields_replace = array(
                "{LBL_CONNECT}",
                '{LBL_CONNECT}',
                encryptIt($user_id),
                "send-connection-request connect-btn",
                "fa fa-check",
                "icon-follower",
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        } else if ($case == "remove_connection") {
            $fields_replace = array(
                "{LBL_REMOVE_FROM_CONNECTION}",
                '{LBL_REMOVE_FROM_CONNECTION}',
                encryptIt($user_id),
                "reject-btn remove-from-connection",
                "fa fa-times",
                "icon-connection-close"
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        } else if ($case == "cancel_connection_request") {
            $fields_replace = array(
                "{LBL_CANCEL_CONNECTION_REQUEST}",
                '{LBL_CANCEL_CONNECTION_REQUEST}',
                encryptIt($user_id),
                "reject-btn cancel-connection-request",
                "fa fa-times",
                "icon-unfollower "
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        } else if ($case == "accept_reject_connection_request") {
            $fields_replace = array(
                "{LBL_ACCEPT}",
                '{LBL_ACCEPT}',
                encryptIt($user_id),
                "accept-btn accept-connection-request",
                "fa fa-check",
                "icon-follower",
            );
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
            $fields_replace = array(
                "{LBL_REJECT}",
                '{LBL_REJECT}',
                encryptIt($user_id),
                "reject-btn reject-connection-request",
                "fa fa-times",
                "icon-connection-close"
            );
            $final_result .= str_replace($fields, $fields_replace, $main_content_parsed);
        }
        return $final_result;
    }
    public function getJobs($current_user_id, $currentPage) {
        $response = array();
        $response['status'] = false;
        $jobs_html = $pagination = $applied_filters = "";
        $totalRecords = $next_available_records = 0;
        $limit =NO_OF_SEARCH_RESULTS_PER_PAGE;
        $offset = ($currentPage - 1) * $limit;
        $applied_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-filter-li-nct.tpl.php");
        $applied_filter_li_tpl_parsed = $applied_filter_li_tpl->parse();
        $applied_location_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-location-filter-li-nct.tpl.php");
        $applied_location_filter_li_tpl_parsed = $applied_location_filter_li_tpl->parse();
        $fields_applied_filter = array("%FILTER_NAME%","%DATA_ID%");
        $userSkillsLeftJoin = $userSkillsScore = "";
        $currentUsersSkillsArray = array();
        $currentUsersSkills = $this->db->select("tbl_user_skills", array('skill_id'), array("user_id" => $this->current_user_id))->results();
        if ($currentUsersSkills) {
            for ($i = 0; $i < count($currentUsersSkills); $i++) {
                $currentUsersSkillsArray[] = $currentUsersSkills[$i]['skill_id'];
            }
            $currentUsersSkillsImploded = implode(",", $currentUsersSkillsArray);
            $userSkillsScore = " + COUNT(js.id) ";
            $userSkillsLeftJoin = " LEFT JOIN tbl_job_skills js ON js.job_id = jobs.id AND js.skill_id IN ( " . $currentUsersSkillsImploded . " ) ";
        }
        $query = "SELECT jobs.featured_till,jobs.id,jobs.is_featured,jobs.company_id,jobs.job_title,jobs.employment_type,jobs.relavent_experience_from,jobs.relavent_experience_to,jobs.last_date_of_application,comp.company_logo,comp.company_name, i.industry_name_".$this->lId." as industry_name, jcate.job_category_".$this->lId." as job_category, l.country,l.state,l.city1,l.city2,CONCAT(u.first_name,' ',u.last_name) as user_name,u.id as posted_user_id, 
            ( IF( jobs.is_featured = 'y' && jobs.featured_till >= '".date("Y-m-d")."', 1, 0 ) " . $userSkillsScore . " ) as total_score 
            FROM tbl_jobs jobs 
            LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id 
            LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id 
            LEFT JOIN tbl_job_category jcate ON jobs.job_category_id = jcate.id 
            LEFT JOIN tbl_locations l ON jobs.location_id = l.id 
            LEFT JOIN tbl_users u ON u.id = jobs.user_id
            " . $userSkillsLeftJoin . "
            WHERE jobs.user_id != ? AND last_date_of_application >= '" . date("Y-m-d") . "' AND jobs.status = ? ";
        $wherearr=array($current_user_id,'a');
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = filtering($_GET['keyword'], 'input');
            $query .= " AND ( jobs.job_title LIKE '%" . $keyword . "%' OR jobs.job_position LIKE '%" . $keyword . "%' OR jobs.key_responsibilities LIKE '%" . $keyword . "%' OR jobs.skills_and_exp LIKE '%" . $keyword . "%' ) ";
        }
        if (isset($_GET['employment_type']) && !empty($_GET['employment_type'])) {
            $employment_type = $_GET['employment_type'];
            $employment_type_query = "";
            if (in_array('f', $employment_type)) {
                $employment_type_query .= " jobs.employment_type = 'f' ";
                $fields_replace_applied_filter = array(
                    "{LBL_EMPLOYMENTTYPE_FULL_TIME}",
                    "adv_employment_type_full_time"
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
            if (in_array('p', $employment_type)) {
                if ($employment_type_query != "") {
                    $employment_type_query .= " OR ";
                }
                $employment_type_query .= " jobs.employment_type = 'p' ";
                $fields_replace_applied_filter = array(
                    "{LBL_PART_TIME}",
                    "adv_employment_type_part_time"
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
            if (in_array('c', $employment_type)) {
                if ($employment_type_query != "") {
                    $employment_type_query .= " OR ";
                }
                $employment_type_query .= " jobs.employment_type = 'c' ";
                $fields_replace_applied_filter = array(
                    "{LBL_EMPLOYMENTTYPE_CONTRACT}",
                    "adv_employment_type_contract"
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
            if (in_array('t', $employment_type)) {
                if ($employment_type_query != "") {
                    $employment_type_query .= " OR ";
                }
                $employment_type_query .= " jobs.employment_type = 't' ";
                $fields_replace_applied_filter = array(
                    "{LBL_EMPLOYMENTTYPE_TEMPORARY}",
                    "adv_employment_type_temporary"
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
            $query .= " AND ( " . $employment_type_query . " ) ";
        }
        if (isset($_GET['company']) && !empty($_GET['company'])) {
            $company = $_GET['company'];
            $query .= " AND jobs.company_id IN (" . implode(",", $company) . ") ";
            for ($i = 0; $i < count($company); $i++) {
                $company_name = getTableValue("tbl_companies", "company_name", array("id" => $company[$i]));
                $fields_replace_applied_filter = array(
                    $company_name,
                    "adv_company_" . $company[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }
        if (isset($_GET['job_category']) && !empty($_GET['job_category'])) {
            $job_category = $_GET['job_category'];
            $query .= " AND jobs.job_category_id IN (" . implode(",", $job_category) . ") ";
            for ($i = 0; $i < count($job_category); $i++) {
                $job_category_name = getTableValue("tbl_job_category", "job_category_".$this->lId, array("id" => $job_category[$i]));
                $fields_replace_applied_filter = array(
                    $job_category_name,
                    "adv_job_category_" . $job_category[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }
        if (isset($_GET['industries']) && !empty($_GET['industries'])) {
            $industries = $_GET['industries'];
            $query .= " AND comp.company_industry_id IN (" . implode(",", $industries) . ") ";
            for ($i = 0; $i < count($industries); $i++) {
                $industry_name = getTableValue("tbl_industries", "industry_name", array("id" => $industries[$i]));
                $fields_replace_applied_filter = array(
                    $industry_name,
                    "adv_industry_" . $industries[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }

        if (isset($_GET['latitude']) && $_GET['latitude'] != "") {
            $formatted_address = filtering($_GET['formatted_address'], 'input');
            $address1 = filtering($_GET['address1'], 'input');
            $address2 = filtering($_GET['address2'], 'input');
            $country = filtering($_GET['country'], 'input');
            $state = filtering($_GET['state'], 'input');
            $city1 = filtering($_GET['city1'], 'input');
            $city2 = filtering($_GET['city2'], 'input');
            if ($formatted_address != "") {
                $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
            }
            if ($address1 != "") {
                $query .= " AND l.address1 = '" . $address1 . "' ";
            }
            if ($address2 != "") {
                $query .= " AND l.address2 = '" . $address2 . "' ";
            }
            if ($country != "") {
                $query .= " AND l.country = '" . $country . "' ";
            }
            if ($state != "") {
                $query .= " AND l.state = '" . $state . "' ";
            }
            if ($city1 != "") {
                $query .= " AND l.city1 = '" . $city1 . "' ";
            }
            if ($city2 != "") {
                $query .= " AND l.city2 = '" . $city2 . "' ";
            }
            $fields_replace_applied_filter = array(
                filtering($_GET['formatted_address']),
                ""
            );
            $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
        }
        $query.=" GROUP BY jobs.id ORDER BY (jobs.featured_till >= '".date("Y-m-d")."') DESC, total_score DESC, jobs.id DESC ";
        $totalRecords = count($this->db->pdoQuery($query,$wherearr)->results());
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $jobs = $this->db->pdoQuery($query_with_limit,$wherearr)->results();

        if ($jobs) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
            $getjobs= $this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records=count($getjobs); 

            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/single-job-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $fields = array(
                "%JOB_ID%",
                "%JOB_TITLE%",
                "%JOB_CATEGORY%",
                "%INDUSTRY_NAME%",
                "%SKILLS%",
                "%LOCATION%",
                "%COMPANY_LOGO_URL%",
                "%COMPANY_NAME%",
                "%JOB_URL%",
                "%SCORE%",
                "%FEATURED_JOB_TAG%",
                "%EMPLOYMENT_TYPE%",
                "%EXPERIENCE%",
                "%LAST_DATE%",
                "%POSTED_BY%",
                "%SAVE_JOB_URL%"
            );
            $featured_job_tag_tpl = new Templater(DIR_TMPL . $this->module . "/featured-job-tag-nct.tpl.php");
            $featured_job_tag_tpl_parsed = $featured_job_tag_tpl->parse();
            
            $saved_job_data = $this->db->select('tbl_saved_jobs',array('group_concat(job_id) as ids'),array('user_id'=>$this->current_user_id))->result();
            $saved_job_array = explode(',', $saved_job_data['ids']);
            for ($i = 0; $i < count($jobs); $i++) {
                $skills = array();
                $featured_job_tag = "";
                $featured_tag_app='n';
                if ($jobs[$i]['is_featured'] == 'y' && $jobs[$i]['featured_till'] >= date('Y-m-d H:i:s')) {
                    $featured_job_tag = $featured_job_tag_tpl_parsed;
                    $featured_tag_app='y';
                }
                $job_id = $jobs[$i]['id'];
                $qrySelSkills = $this->db->pdoQuery("SELECT skills.skill_name_".$this->lId." as skill_name FROM tbl_job_skills jskills 
                    LEFT JOIN tbl_skills skills ON skills.id = jskills.skill_id WHERE jskills.job_id = ? ",array(filtering($job_id, 'output', 'int')))->results();
                if ($qrySelSkills) {
                    foreach ($qrySelSkills as $key => $value) {
                        $skills[] = filtering($value['skill_name']);
                    }
                    $skill_name = implode(", ", $skills);
                } else {
                    $skill_name = '';
                }
                $city = $jobs[$i]['city1'] != '' ? $jobs[$i]['city1'] : $jobs[$i]['city2'];
                $state = $jobs[$i]['state'];
                $country = $jobs[$i]['country'];
                if($city == '' && $state !=''){
                    $location =$state . ", " . $country;
                }else if($state == ''){
                    $location= $country;
                }else{
                    $location = $city . ", " . $state . ", " . $country;

                }
                $company_logo_url = $company_logo_web_url = getImageURL("company_logo", filtering($jobs[$i]['company_id'], 'output', 'int'), "th2",$this->platform);
                $company_logo_web_url = ($company_logo_web_url == '') ? '<span class="profile-picture-character">'.ucfirst($jobs[$i]['company_name'][0]).'</span>' : '<img src="'.$company_logo_web_url.'" alt="'.$jobs[$i]['company_name'].'">';
                $job_url = get_job_detail_url($job_id);
                $posted_by = '<a href="' . get_user_profile_url($jobs[$i]['posted_user_id']) . '">' . ucwords($jobs[$i]['user_name']) . '</a>';
                $save_job_url = $this->savedJobUrl($jobs[$i]['id']);
                $job_title = filtering($jobs[$i]['job_title'], 'output');
                $company_name = filtering($jobs[$i]['company_name'], 'output');
                $employment_type = $jobs[$i]['employment_type'] == 'f' ? 'Full time' : ($jobs[$i]['employment_type'] == 'p' ? 'Part time' : ($jobs[$i]['employment_type'] == 'c' ? 'Contract' : 'Temporary'));
               
                $last_date_of_application = convertDate('displayWeb',filtering($jobs[$i]['last_date_of_application']));
                $job_category = filtering($jobs[$i]['job_category'], 'output');
                $industry_name = filtering($jobs[$i]['industry_name'], 'output');
                $final_loc = filtering($location, 'output');
                $final_skills = filtering($skill_name, 'output');
                $fields_replace = array(
                    filtering($jobs[$i]['id'], 'output', 'int'),
                    ucwords($job_title),
                    ucwords($job_category),
                    ucwords($industry_name),
                    ucwords($final_skills),
                    $final_loc,
                    $company_logo_web_url,
                    ucwords($company_name),
                    filtering($job_url, 'output'),
                    filtering($jobs[$i]['total_score']),
                    $featured_job_tag,
                    $employment_type,
                    $jobs[$i]['relavent_experience_from'] . " - " . $jobs[$i]['relavent_experience_to'],
                    $last_date_of_application,
                    $posted_by,
                    $save_job_url
                );
                
                if($this->platform == 'app'){
                    $is_saved = (in_array($jobs[$i]['id'], $saved_job_array)?'y':'n');
                    $app_array[] = array(
                        'job_id'=>$jobs[$i]['id'],
                        'job_title'=>$job_title,
                        'company_id'=>$jobs[$i]['company_id'],
                        'company_name'=>$company_name,
                        'company_logo'=>$company_logo_url,
                        'employment_type'=>$employment_type,
                        'relavent_experience_from'=>$jobs[$i]['relavent_experience_from'],
                        'relavent_experience_to'=>$jobs[$i]['relavent_experience_to'],
                        'last_date_of_application'=>$last_date_of_application,
                        'posted_user_id'=>$jobs[$i]['posted_user_id'],
                        'posted_user_name'=>$jobs[$i]['user_name'],
                        'job_category'=>$job_category,
                        'industry_name'=>$industry_name,
                        'skills'=>$final_skills,
                        'location'=>$final_loc,
                        'is_featured'=>$featured_tag_app,
                        'is_saved'=>$is_saved,
                    );
                } else {
                    $jobs_html .= str_replace($fields, $fields_replace, $single_user_tpl_parsed);
                }
            }
            if ($next_available_records > 0) {
                $keyword=($_GET['keyword'] != '')?$_GET['keyword']:'';

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getJobs/currentPage/" . ($currentPage + 1)."/".$keyword;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $jobs_html .= $load_more_li_tpl->parse();
            }
            $pagination = getPagination($totalRecords, count($jobs), NO_OF_SEARCH_RESULTS_PER_PAGE, $currentPage);
        } else {
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/single-user-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $no_result_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $jobs_html = $no_result_tpl->parse();
        }
        if ($applied_filters) {
            $reset_applied_filters=new Templater(DIR_TMPL . $this->module . "/reset-applied-filters-nct.tpl.php");
            $applied_filters .= $reset_applied_filters->parse();
        }
        if($this->platform == 'app'){
            $page_data = getPagerData($totalRecords, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRecords);
            $response = array('jobs'=>(!empty($app_array)?$app_array:array()),'pagination'=>$pagination);
        } else {
            $response['status'] = true;
            $response['content'] = $jobs_html;
            $response['pagination'] = $pagination;
            $response['total_records'] = $totalRecords;
            $response['next_available_records'] = $next_available_records;
            $response['applied_filters'] = $applied_filters;
        }
        return $response;
    }
    public function savedJobUrl($job_id) {
        $save_job_url = '';
        $save_jobs = new Templater(DIR_TMPL . $this->module . "/save-job-url-nct.tpl.php");
        $save_jobs_parsed = $save_jobs->parse();
        $user_job_count = getTotalRows('tbl_jobs', "id = '" . $job_id . "' AND user_id = '" . $this->current_user_id . "'");
        if ($user_job_count == 0) {
            //job save url
            if (getTotalRows('tbl_saved_jobs', "job_id = '" . $job_id . "' AND user_id = '" . $this->current_user_id . "'") == 0) {
                $fields_save_job = array(
                    '%JOB_SAVE%',
                    '%ENCRYPTED_JOB_ID%',
                    "%HTML%",
                );
                $fields_replace_save_job = array(
                    'job_save',
                    encryptIt($job_id),
                    LBL_SAVE
                );
            } else {
                $fields_save_job = array(
                    '%JOB_SAVE%',
                    '%ENCRYPTED_JOB_ID%',
                    "%HTML%",
                );
                $fields_replace_save_job = array(
                    'remove_from_job_save',
                    encryptIt($job_id),
                    LBL_SAVED
                );
            }
            $save_job_url = str_replace($fields_save_job, $fields_replace_save_job, $save_jobs_parsed);
        }
        return $save_job_url;
    }
    public function getCompanies($current_user_id, $currentPage) {
        $response = array();
        $response['status'] = false;
        $companies_html = $pagination = $applied_filters = "";
        $totalRecords = $next_available_records = 0;
        $limit = NO_OF_SEARCH_RESULTS_PER_PAGE;
        $offset = ($currentPage - 1) * $limit;
        $applied_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-filter-li-nct.tpl.php");
        $applied_filter_li_tpl_parsed = $applied_filter_li_tpl->parse();
        $applied_location_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-location-filter-li-nct.tpl.php");
        $applied_location_filter_li_tpl_parsed = $applied_location_filter_li_tpl->parse();
        $applied_no_of_followers_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-no-of-followers-filter-li-nct.tpl.php");
        $applied_no_of_followers_filter_li_tpl_parsed = $applied_no_of_followers_filter_li_tpl->parse();
        $fields_applied_filter = array("%FILTER_NAME%","%DATA_ID%");
        $locationsScore = "";
        if ($this->formatted_address != "") {
            if ($this->country != "") {
                $locationsScore .= " + IF(l.country = '" . $this->country . "', 1, 0) ";
            }
            if ($this->state != "") {
                $locationsScore .= " + IF(l.state = '" . $this->state . "', 1, 0) ";
            }
            if ($this->city1 != "") {
                $locationsScore .= " + IF(l.city1 = '" . $this->city1 . "', 1, 0) ";
            }
            if ($this->city2 != "") {
                $locationsScore .= " + IF(l.city2 = '" . $this->city2 . "', 1, 0) ";
            }
        }
        $query = "SELECT comp.id,comp.company_name,comp.website_of_company,comp.company_description,comp.owner_email_address, concat_ws(' - ', cs.minimum_no_of_employee, cs.maximum_no_of_employee) as range_of_no_of_employees, i.industry_name_".$this->lId." as industry_name, l.formatted_address, COUNT(DISTINCT cf.id) as no_of_followers, 
                    ( IF( comp.company_industry_id = '" . $this->usersCurrentIndustry . "', 5, 0 ) " . $locationsScore . " ) as total_score 
                    FROM tbl_companies comp 
                    LEFT JOIN tbl_company_sizes cs ON cs.id = comp.company_size_id 
                    LEFT join tbl_company_locations cl ON (cl.company_id = comp.id AND cl.is_hq ='y')
                    LEFT JOIN tbl_locations l ON l.id = cl.location_id 
                    LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id 
                    LEFT JOIN tbl_company_followers cf ON cf.company_id = comp.id 
                    WHERE comp.user_id != ?  AND comp.company_type = ? AND comp.status = ? ";
        $wherearr=array($current_user_id,'r','a');
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = filtering($_GET['keyword'], 'input');
            $query .= " AND ( comp.company_name LIKE '%" . $keyword . "%' OR comp.company_description LIKE '%" . $keyword . "%' ) ";
        }
        if (isset($_GET['industries']) && !empty($_GET['industries'])) {
            $industries = $_GET['industries'];
            $query .= " AND comp.company_industry_id IN (" . implode(",", $industries) . ") ";
            for ($i = 0; $i < count($industries); $i++) {
                $industry_name = getTableValue("tbl_industries", "industry_name_".$this->lId, array("id" => $industries[$i]));
                $fields_replace_applied_filter = array(
                    $industry_name,
                    "adv_industry_" . $industries[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }

        // if (isset($_GET['company_sizes']) && !empty($_GET['company_sizes'])) {
        //     $company_sizes = $_GET['company_sizes'];
        //     $query .= " AND comp.company_size_id IN (" . implode(",", $company_sizes) . ") ";
        //     for ($i = 0; $i < count($company_sizes); $i++) {
        //         $company_size = getTableValue("tbl_company_sizes", "company_size", array("id" => $company_sizes[$i]));
        //         $fields_replace_applied_filter = array(
        //             $company_size,
        //             "adv_company_size_" . $company_sizes[$i]
        //         );
        //         $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
        //     }
        // }
        

        if (isset($_GET['latitude']) && $_GET['latitude'] != "") {
            $formatted_address = filtering($_GET['formatted_address'], 'input');
            $address1 = filtering($_GET['address1'], 'input');
            $address2 = filtering($_GET['address2'], 'input');
            $country = filtering($_GET['country'], 'input');
            $state = filtering($_GET['state'], 'input');
            $city1 = filtering($_GET['city1'], 'input');
            $city2 = filtering($_GET['city2'], 'input');
            if ($formatted_address != "") {
                $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
            }
            if ($address1 != "") {
                $query .= " AND l.address1 = '" . $address1 . "' ";
            }
            if ($address2 != "") {
                $query .= " AND l.address2 = '" . $address2 . "' ";
            }
            if ($country != "") {
                $query .= " AND l.country = '" . $country . "' ";
            }
            if ($state != "") {
                $query .= " AND l.state = '" . $state . "' ";
            }
            if ($city1 != "") {
                $query .= " AND l.city1 = '" . $city1 . "' ";
            }
            if ($city2 != "") {
                //$query .= " AND l.city2 = '" . $city2 . "' ";
            }
            $fields_replace_applied_filter = array(
                filtering($_GET['formatted_address']),
                ""
            );
            $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
        } else if($_GET['location'] != ''){
            $reverse_geocode = reverse_geocode($_GET['location']);
            if($reverse_geocode['status'] == 'OK'){
                $formatted_address = filtering($_GET['location'], 'input');
                $address1 = filtering($reverse_geocode['address'], 'input');
                $country = filtering($reverse_geocode['country'], 'input');
                $state = filtering($reverse_geocode['state'], 'input');
                $city1 = filtering($reverse_geocode['city'], 'input');
                if ($formatted_address != "") {
                    $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
                }
                if ($address1 != "") {
                    $query .= " AND l.address1 = '" . $address1 . "' ";
                }
                if ($address2 != "") {
                    $query .= " AND l.address2 = '" . $address2 . "' ";
                }
                if ($country != "") {
                    $query .= " AND l.country = '" . $country . "' ";
                }
                if ($state != "") {
                    $query .= " AND l.state = '" . $state . "' ";
                }
                if ($city1 != "") {
                    $query .= " AND l.city1 = '" . $city1 . "' ";
                }
                if ($city2 != "") {
                    //$query .= " AND l.city2 = '" . $city2 . "' ";
                }
                $fields_replace_applied_filter = array(
                    filtering($formatted_address),
                    ""
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
            }
        }
        $no_of_followers_query = "";
        if (isset($_GET['min_no_of_followers']) && isset($_GET['max_no_of_followers']) && ( $_GET['min_no_of_followers'] > 0 || $_GET['max_no_of_followers'] > 0  )) {
            $min_no_of_followers = filtering($_GET['min_no_of_followers'], "input", "int");
            $max_no_of_followers = filtering($_GET['max_no_of_followers'], "input", "int");
            $no_of_followers_query = " HAVING no_of_followers >=  " . $min_no_of_followers . " AND no_of_followers <=  " . $max_no_of_followers;
            $fields_replace_applied_filter = array(
                "No of followers : " . $min_no_of_followers . " - " . $max_no_of_followers,
                "adv_no_of_followers" . $min_no_of_followers . " - " . $max_no_of_followers
            );
            $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_no_of_followers_filter_li_tpl_parsed);
        }
        if(isset($_GET['sorting_lt']) && !empty($_GET['sorting_lt'])){
           $sorting_lt = $_GET['sorting_lt']; 
           //echo "s";print_r($sorting_lt);exit();
           $query .= " GROUP BY comp.id " . $no_of_followers_query . " ORDER BY crr.id DESC  ";
        }else{
            $query .= " GROUP BY comp.id " . $no_of_followers_query . " ORDER BY total_score DESC, comp.id DESC ";
        }
        
        $totalRecords = count($this->db->pdoQuery($query,$wherearr)->results());
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        
        $companies = $this->db->pdoQuery($query_with_limit,$wherearr)->results();
        if ($companies) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
            $getcompany=$this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records = count($getcompany);

            $single_company_tpl = new Templater(DIR_TMPL . $this->module . "/single-company-nct.tpl.php");
            $single_company_tpl_parsed = $single_company_tpl->parse();
            $fields = array(
                "%COMPANY_ID_ENCRYPTED%",
                "%COMPANY_NAME%",
                "%COMPANY_PAGE_URL%",
                "%COMPANY_LOGO_URL%",
                "%COMPANY_INDUSTRY%",
                "%WEBSITE_OF_COMPANY%",
                "%DESCRIPTION%",
                "%OWNER_EMAIL_ADDRESS%",
                "%COMPANY_LOCATION%",
                "%RANGE_OF_NO_OF_EMPLOYEES%",
                "%EDIT_COMPANY_URL%",
                "%FOLLOWERS%",
                "%SCORE%",
                "%HIDE_CLASS%",
                "%CLASS_HIDE_ADD%",
                "%HIDE_CLASS_URL%",
                '%FOLLOW_COMPANY%',
                '%ENCRYPTED_COMPANY_ID%',
                "%HTML%",
                "%HREF%",

                
            );
            for ($i = 0; $i < count($companies); $i++) {
                $company_id = filtering($companies[$i]['id'], 'output', 'int');
                $company_page_url = get_company_detail_url($company_id);
                $company_logo_url = $company_logo_web_url = getImageURL("company_logo", $company_id, "th2",$this->platform);
                $company_logo_web_url = ($company_logo_web_url == '') ? '<span class="profile-picture-character">'.ucfirst($companies[$i]['company_name'][0]).'</span>' : $company_logo_web_url;
                $edit_company_url = SITE_URL . "edit-company/" . encryptIt($company_id);
                $company_name = filtering($companies[$i]['company_name']);
                $industry_name_final = filtering($companies[$i]['industry_name']);
                $website_of_company = filtering($companies[$i]['website_of_company']);
                $company_description = substr(filtering($companies[$i]['company_description']), 0, 80) . "...";
                $owner_email_address = filtering($companies[$i]['owner_email_address']);
                $range_of_no_of_employees = filtering($companies[$i]['range_of_no_of_employees']);
                $no_of_followers = filtering($companies[$i]['no_of_followers']);
                $hide_class=$hide_class_add=$hide_class_url='';
                if($companies[$i]['company_description'] == ''){
                    $hide_class='hidden';
                }
                if($companies[$i]['formatted_address'] == ''){
                    $hide_class_add="hidden";
                }
                if($website_of_company == ''){
                    $hide_class_url="hidden";
                }
                $follow_company='n';
                if(getTotalRows('tbl_company_followers', "company_id = '". $company_id ."' AND user_id = '". $this->current_user_id ."'") == 0) {

                   $follow_class='follow_company';
                   $lbl=LBL_FOLLOW;
                    $follow_company='n';

                }else{
                    $follow_class='unfollow_company';
                    $lbl=LBL_UNFOLLOW;
                    $follow_company='y';
                }
                $fields_replace = array(
                    encryptIt($company_id),
                    ucwords($company_name),
                    $company_page_url,
                    $company_logo_web_url,
                    ucwords($industry_name_final),
                    $website_of_company,
                    $company_description,
                    $owner_email_address,
                    filtering($companies[$i]['formatted_address']),
                    $range_of_no_of_employees,
                    $edit_company_url,
                    $no_of_followers,
                    filtering($companies[$i]['total_score']),
                    $hide_class,
                    $hide_class_add,
                    $hide_class_url,
                    $follow_class,
                    encryptIt($company_id),
                    $lbl,
                    'javascript:void(0);',

                );
                
                if($this->platform == 'app'){
                    $app_array[] = array(
                        'id'=>$company_id,
                        'company_name'=>$company_name,
                        'company_logo'=>$company_logo_url,
                        'industry_name'=>$industry_name_final,
                        'website_of_company'=>$website_of_company,
                        'company_description'=>$company_description,
                        'owner_email_address'=>$owner_email_address,
                        'headquarter'=>$companies[$i]['formatted_address'],
                        'range_of_no_of_employees'=>$range_of_no_of_employees,
                        'no_of_followers'=>$no_of_followers,
                        'follow_company'=>$follow_company
                    );
                } else {
                    $companies_html .= str_replace($fields, $fields_replace, $single_company_tpl_parsed);
                }
                
            }
            if ($next_available_records > 0) {
                    $keyword=($_GET['keyword'] != '')?$_GET['keyword']:'';

                    $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                    $load_more_link = SITE_URL . "ajax/getCompanies/currentPage/" . ($currentPage + 1)."/".$keyword;
                    
                    $load_more_li_tpl->set('load_more_link', $load_more_link);
                    $companies_html .= $load_more_li_tpl->parse();
            }
            $pagination = getPagination($totalRecords, count($companies), NO_OF_SEARCH_RESULTS_PER_PAGE, $currentPage);
        } else {
            $no_result_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $companies_html = $no_result_tpl->parse();
        }
        if ($applied_filters) {
            $reset_applied_filters=new Templater(DIR_TMPL . $this->module . "/reset-applied-filters-nct.tpl.php");
            $applied_filters .= $reset_applied_filters->parse();
        }

        if($this->platform == 'app'){
            $page_data = getPagerData($totalRecords, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRecords);
            $response = array('companies'=>(!empty($app_array)?$app_array:array()),'pagination'=>$pagination);
        } else {
            $response['status'] = true;
            $response['content'] = $companies_html;
            $response['pagination'] = $pagination;
            $response['total_records'] = $totalRecords;
            $response['next_available_records'] = $next_available_records;
            $response['applied_filters'] = $applied_filters;
        }
        return $response;
    }
    public function getGroups($current_user_id, $currentPage) {
        $response = array();
        $response['status'] = false;
        $groups_html = $pagination = $applied_filters = "";
        $totalRecords = $next_available_records = 0;
        $limit = NO_OF_SEARCH_RESULTS_PER_PAGE;
        $offset = ($currentPage - 1) * $limit;
        $applied_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-filter-li-nct.tpl.php");
        $applied_filter_li_tpl_parsed = $applied_filter_li_tpl->parse();
        $fields_applied_filter = array("%FILTER_NAME%","%DATA_ID%");
        $query = "SELECT groups.id,groups.user_id,groups.privacy,groups.group_name,groups.accessibility,gtypes.group_type_".$this->lId." as group_type, i.industry_name_".$this->lId." as industry_name, u.first_name, u.last_name, 
                    ( IF( groups.group_industry_id = '" . $this->usersCurrentIndustry . "', 5, 0 )) as total_score 
                    FROM tbl_groups groups 
                    LEFT JOIN tbl_group_types gtypes ON groups.group_type_id = gtypes.id
                    LEFT JOIN tbl_industries i ON i.id = groups.group_industry_id 
                    LEFT JOIN tbl_users u ON u.id = groups.user_id 
                    WHERE groups.user_id != ? 
                    AND groups.privacy != ? AND groups.status = ? ";
        $wherearr=array($current_user_id,'pr','a');
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = filtering($_GET['keyword'], 'input');
            $query .= " AND ( groups.group_name LIKE '%" . $keyword . "%' OR groups.group_description LIKE '%" . $keyword . "%' OR gtypes.group_type LIKE '%" . $keyword . "%' ) ";
        }
        if (isset($_GET['industries']) && !empty($_GET['industries'])) {
            $industries = $_GET['industries'];
            $query .= " AND groups.group_industry_id IN (" . implode(",", $industries) . ") ";
            for ($i = 0; $i < count($industries); $i++) {
                $industry_name = getTableValue("tbl_industries", "industry_name", array("id" => $industries[$i]));
                $fields_replace_applied_filter = array(
                    $industry_name,
                    "adv_industry_" . $industries[$i]
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            }
        }
        $query .= " GROUP BY groups.id ORDER BY total_score DESC, groups.id DESC ";
        $totalRecords = count($this->db->pdoQuery($query,$wherearr)->results());
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $groups = $this->db->pdoQuery($query_with_limit,$wherearr)->results();
        if ($groups) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
            
            $getgroups=$this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records = count($getgroups);
            $single_group_tpl = new Templater(DIR_TMPL . $this->module . "/single-group-nct.tpl.php");
            $single_group_tpl_parsed = $single_group_tpl->parse();
            $fields = array(
                "%GROUP_ID%",
                "%GROUP_NAME%",
                "%GROUP_TYPE%",
                "%GROUP_INDUSTRY%",
                "%GROUP_LOGO_URL%",
                "%GROUP_URL%",
                "%COUNT_GROUP_MEMBERS%",
                "%MEMEBER_TEXT%",
                "%CONNECTION_COUNT%",
                "%CONNECTION_COUNT_TEXT%",
                "%CREATOR_NAME%",
                "%CREATOR_PROFILE_URL%",
                "%CREATOR_PROFILE_PICTURE%",
                "%CREATOR_HEADLINE%",
                "%SCORE%",
                "%JOIN_LEAVE_GROUP_HTML%"
            );
            for ($i = 0; $i < count($groups); $i++) {
                $group_id = filtering($groups[$i]['id'], 'output', 'int');
                $user_id_arr = getConnections($current_user_id);
                $connection_count = 0;
                if (is_array($user_id_arr) && !empty($user_id_arr)) {
                    $connected_members = $this->db->pdoQuery('SELECT COUNT(*) as total_connection FROM tbl_group_members 
                            WHERE user_id IN (' . implode(",", $user_id_arr) . ') AND group_id = ?
                            AND action != ? AND action != ? ',array($group_id,"r","jr"))->result();
                    $connection_count = $connected_members['total_connection'];
                }
                $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members 
                    WHERE  group_id = ? AND action != ? AND action != ? ',array($group_id,"r","jr"))->result();
                $count_group_members = $memeber_text = $connection_count_text = '';
                $connection_count_text = $connection_count > 1 ? LBL_GRP_DTL_MEMBERS_TITLE : LBL_MEMBER_GRP ;
                $count_group_members = $group_members['total_members'];
                $memeber_text = $count_group_members > 1 ? LBL_GRP_DTL_MEMBERS_TITLE : LBL_MEMBER_GRP ;
                $group_url = get_group_detail_url(filtering($groups[$i]['id'], 'output', 'int'));
                $group_logo_url = $group_logo_url_web = getImageURL("group_logo", $group_id, "th2",$this->platform);

                $group_logo_url_web = ($group_logo_url_web == '') ? '<span class="profile-picture-character">'.ucfirst($groups[$i]['group_name'][0]).'</span>' : '<img src="'.$group_logo_url_web.'" alt="'.$groups[$i]['group_name'].'">';
                
                $creator_id = filtering($groups[$i]['user_id'], 'output', 'int');
                $creator_name = filtering($groups[$i]['first_name']) . " " . filtering($groups[$i]['last_name']);
                $creator_profile_url = get_user_profile_url($creator_id);
                $app_join_leave_btn = '';
                $join_leave_group_html = '';
                //echo $groups[$i]['privacy'];exit;
                if ($groups[$i]['privacy'] == 'pu' && $groups[$i]['user_id'] != $current_user_id) {
                    
                    $checkIfMemberExists = $this->db->select("tbl_group_members", array('action'), array("group_id" => $groups[$i]['id'],"user_id" => filtering($current_user_id, 'input', 'int')))->result();
                    $autojoin = '';
                    if ($checkIfMemberExists) {
                        if ($checkIfMemberExists['action'] == 'r') {
                            $join_leave_group_html = $this->commonActionsUrl("group_rejected", $groups[$i]['id']);
                            $app_join_leave_btn = 'group_rejected';
                        } else if ($checkIfMemberExists['action'] == 'aj') {
                            $join_leave_group_html = $this->commonActionsUrl("leave_group", $groups[$i]['id']);
                            $app_join_leave_btn = 'leave_group';
                        } else if ($checkIfMemberExists['action'] == 'jr') {
                            $join_leave_group_html=$this->commonActionsUrl("withdraw_request", $groups[$i]['id']);
                            $app_join_leave_btn = 'withdraw_request';
                        } else {
                            $join_leave_group_html = $this->commonActionsUrl("leave_group", $groups[$i]['id']);
                            $app_join_leave_btn = 'leave_group';
                        }
                    } else {

                        if ($groups[$i]['accessibility'] == 'rj') {
                            $join_leave_group_html = $this->commonActionsUrl("ask_to_join", $groups[$i]['id']);
                            $app_join_leave_btn = 'ask_to_join';
                        } else {
                            $join_leave_group_html = $this->commonActionsUrl("join_group", $groups[$i]['id']);
                            $app_join_leave_btn = 'join_group';
                        }
                    }
                }



                $new_group_name = filtering($groups[$i]['group_name'], 'output');
                $new_industry_name = filtering($groups[$i]['industry_name'], 'output');
                $new_group_type = filtering($groups[$i]['group_type'], 'output');
                $img = getImageURL('user_profile_picture', $creator_id, "th2",$this->platform);
                $chead = getUserHeadline($creator_id);
                $fields_replace = array(
                    filtering($groups[$i]['id'], 'output', 'int'),
                    ucwords($new_group_name),
                    ucwords($new_group_type),
                    ucwords($new_industry_name),
                    $group_logo_url_web,
                    $group_url,
                    $count_group_members,
                    $memeber_text,
                    $connection_count,
                    $connection_count_text,
                    ucwords($creator_name),
                    $creator_profile_url,
                    $img,
                    $chead,
                    filtering($groups[$i]['total_score']),
                    $join_leave_group_html
                );
                if($this->platform == 'app'){
                    $total_members = $count_group_members;
                    $connected_members = $connection_count;
                    $admin_id = $creator_id;
                    $admin_name = $creator_name;
                    $admin_image = $img;
                    $admin_headline = $chead;

                    $app_array[] = array(
                        'group_id'=>$groups[$i]['id'],
                        'group_name'=>$new_group_name,
                        'group_type'=>$new_group_type,
                        'industry_name'=>$new_industry_name,
                        'group_logo_url'=>$group_logo_url,
                        'join_leave_status'=>$app_join_leave_btn,
                        'total_members'=>$total_members,
                        'connected_members'=>$connected_members,
                        
                        'autojoin'=>$autojoin,

                        'admin_id'=>$admin_id,
                        'admin_name'=>$admin_name,
                        'admin_image'=>$admin_image,
                        'admin_headline'=>$admin_headline
                    );
                } else {
                    $groups_html .= str_replace($fields, $fields_replace, $single_group_tpl_parsed);
                }
            }
            if ($next_available_records > 0) {
                    $keyword=($_GET['keyword'] != '')?$_GET['keyword']:'';

                    $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                    $load_more_link = SITE_URL . "ajax/getGroups/currentPage/" . ($currentPage + 1)."/".$keyword;
                    
                    $load_more_li_tpl->set('load_more_link', $load_more_link);
                    $groups_html .= $load_more_li_tpl->parse();
            }
            $pagination=getPagination($totalRecords, count($groups), NO_OF_SEARCH_RESULTS_PER_PAGE, $currentPage);
        } else {
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/single-user-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $no_result_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $groups_html = $no_result_tpl->parse();
        }
        if ($applied_filters) {
            $reset_applied_filters=new Templater(DIR_TMPL . $this->module . "/reset-applied-filters-nct.tpl.php");
            $applied_filters .= $reset_applied_filters->parse();
        }
        if($this->platform == 'app'){
            $page_data = getPagerData($totalRecords, $limit,$currentPage);
            $pagination = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRecords);
            $response = array('groups'=>(!empty($app_array)?$app_array:array()),'pagination'=>$pagination);
        } else {
            $response['status'] = true;
            $response['content'] = $groups_html;
            $response['pagination'] = $pagination;
            $response['total_records'] = $totalRecords;
            $response['next_available_records'] = $next_available_records;
            $response['applied_filters'] = $applied_filters;
        }
        return $response;
    }
    public function getSearchForm() {
        $final_result = NULL;
        $search_form_tpl = new Templater(DIR_TMPL . $this->module . "/search-form-nct.tpl.php");
        $search_form_tpl_parsed = $search_form_tpl->parse();
        $entity = filtering($_GET['entity'], "input");
        $fields = array(
           
            "%KEYWORD%"
        );
        $keyword = "";
        if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
            $keyword = filtering($_GET['keyword']);
        }
        $replace = array(
            $keyword
        );
        $final_result = str_replace($fields, $replace, $search_form_tpl_parsed);
        return $final_result;
    }
    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->set("search_form", $this->getSearchForm());
       // if($this->current_user_id > 0) {
            $main_content->set('subscribed_membership_plan_details', $this->getSubscribedMembershipPlan($this->current_user_id));    
        //}
        $entity = filtering($_GET['entity'], "input");
        $currentPage = ( ( isset($_GET['currentPage']) ) ? filtering($_GET['currentPage']) : 1);
        $location_filter_hidden =$user_li_active_class = $jobs_li_active_class = $company_li_active_class = $group_li_active_class = "";
        $relationship_filter_hidden = $company_size_filter_hidden = "hidden";
        $adv_company_filter_hidden = $adv_groups_filter_hidden = $industries_filter_hidden = $adv_industries_filter_hidden = $adv_job_categories_filter_hidden = "hidden";
        $employment_type_filter_hidden = $no_of_followers_filter_hidden = "hidden";
        switch ($entity) {
            case "users" : {
                $relationship_filter_hidden = $adv_company_filter_hidden = $adv_groups_filter_hidden = $adv_industries_filter_hidden = "";
                if($this->current_user_id > 0) {
                    $response = $this->getUsers($this->current_user_id, $currentPage);    
                } else {
                    $response = $this->getUsersBeforeLogin($currentPage);    
                }
                $user_li_active_class = "active ";

                break;
            }
            case "jobs" : {
                $employment_type_filter_hidden = $adv_company_filter_hidden = $adv_industries_filter_hidden = $adv_job_categories_filter_hidden = "";
                $response = $this->getJobs($this->current_user_id, $currentPage);
                $jobs_li_active_class = "active ";

                break;
            }
            case "companies" : {
                $company_size_filter_hidden = $adv_industries_filter_hidden = $no_of_followers_filter_hidden = "";
                if($this->current_user_id > 0) {
                    $response = $this->getCompanies($this->current_user_id, $currentPage);
                } else {
                    $response = $this->getCompaniesBeforeLogin($currentPage);
                }
                $company_li_active_class = "active ";

                break;
            }
            case "groups" : {
                $industries_filter_hidden = $adv_industries_filter_hidden = "";
                $location_filter_hidden = "hidden";
                $response = $this->getGroups($this->current_user_id, $currentPage);
                $group_li_active_class = "active ";

                break;
            }
        }
        

        $applied_filters = $response['applied_filters'];
        /* if ($applied_filters) {
          $reset_applied_filters_tpl = new Templater(DIR_TMPL . $this->module . "/reset-applied-filters-nct.tpl.php");
          $reset_applied_filters_tpl_parsed = $reset_applied_filters_tpl->parse();

          $applied_filters .= $reset_applied_filters_tpl_parsed;
          } */
        $main_content->set('search_results', $response['content']);
        $main_content->set('pagination', $response['pagination']);
        $main_content->set('no_of_total_results', $response['total_records']);
        $main_content->set('applied_filters', $applied_filters);
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%RELATIONSHIP_FILTER_HIDDEN%",
            "%RELATIONSHIP_FILTER%",
            "%ADV_RELATIONSHIP_FILTER%",
            "%LOCATION_FILTER_HIDDEN%",
            "%LOCATION_FILTER%",
            "%ADV_LOCATION_FILTER%",
            "%ADV_CURRENT_COMPANY_FILTER_HIDDEN%",
            "%ADV_CURRENT_COMPANY_FILTER%",
            "%INDUSTRIES_FILTER_HIDDEN%",
            "%INDUSTRIES_FILTER%",
            "%ADV_INDUSTRIES_FILTER%",
            "%ADV_GROUPS_FILTER_HIDDEN%",
            "%ADV_GROUPS_FILTER%",
            "%ADV_JOB_CATEGORIES_FILTER_HIDDEN%",
            "%ADV_JOB_CATEGORIES_FILTER%",
            "%COMPANY_SIZES_FILTER_HIDDEN%",
            "%COMPANY_SIZES_FILTER%",
            "%ADV_COMPANY_SIZES_FILTER%",
            "%EMPLOYMENT_TYPE_FILTER_HIDDEN%",
            "%EMPLOYMENT_TYPE_FILTER%",
            "%ADV_EMPLOYMENT_TYPE_FILTER%",
            "%KEYWORD%",
            "%NO_OF_FOLLOWERS_FILTER_HIDDEN%",
            "%ADV_NO_OF_FOLLOWERS_FILTER%",
            "%USERS_LI_ACTIVE_CLASS%",
            "%JOBS_LI_ACTIVE_CLASS%",
            "%COMPANY_LI_ACTIVE_CLASS%",
            "%GROUP_LI_ACTIVE_CLASS%",
            "%SORTINGS_FILTER_HIDDEN%",
            "%SORTINGS_FILTER%",
            "%ADV_SORTINGS_FILTER%",
        );
        $keyword = "";
        if (isset($_GET['keyword']) && $_GET['keyword'] != "") {
            $keyword = filtering($_GET['keyword']);
        }
        if($this->current_user_id > 0) {
            $fields_replace = array(
                $relationship_filter_hidden,
                $this->getRelationshipFilter("", $relationship_filter_hidden),
                $this->getRelationshipFilter("adv_", $relationship_filter_hidden),
                $location_filter_hidden,
                $this->getLocationFilter("", $location_filter_hidden),
                $this->getLocationFilter("adv_", $location_filter_hidden),
                $adv_company_filter_hidden,
                $this->getCompaniesFilter("adv_", $adv_company_filter_hidden),
                $adv_industries_filter_hidden,
                $this->getIndustriesFilter("", $industries_filter_hidden),
                $this->getIndustriesFilter("adv_", $adv_industries_filter_hidden),
                $adv_groups_filter_hidden,
                $this->getGroupsFilter("adv_", $adv_groups_filter_hidden),
                $adv_job_categories_filter_hidden,
                $this->getJobCategoriesFilter("adv_", $adv_job_categories_filter_hidden),
                $company_size_filter_hidden,
                $this->getCompanySizesFilter("", $company_size_filter_hidden),
                $this->getCompanySizesFilter("adv_", $company_size_filter_hidden),
                $employment_type_filter_hidden,
                $this->getEmploymentTypeFilter("", $employment_type_filter_hidden),
                $this->getEmploymentTypeFilter("adv_", $employment_type_filter_hidden),
                $keyword,
                $no_of_followers_filter_hidden,
                $this->getNoOfFollowersFilter("adv_", $no_of_followers_filter_hidden),
                $user_li_active_class,
                $jobs_li_active_class,
                $company_li_active_class,
                $group_li_active_class,
                
                $sortings_filter_hidden,
                $this->getSortingFilter("", $sortings_filter_hidden),
                $this->getSortingFilter("adv_", $sortings_filter_hidden),
            );
        } else {
            $fields_replace = array(
                '',
                '',
                '',
                '',
                $this->getLocationFilter("", $location_filter_hidden),
                $this->getLocationFilter("adv_", $location_filter_hidden),
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $company_size_filter_hidden,
                $this->getCompanySizesFilter("", $company_size_filter_hidden),
                $this->getCompanySizesFilter("adv_", $company_size_filter_hidden),
                '',
                '',
                '',
                '',
                '',
                '',
                $user_li_active_class,
                $jobs_li_active_class,
                $company_li_active_class,
                $group_li_active_class,
                $sortings_filter_hidden,
                $this->getSortingFilter("", $sortings_filter_hidden),
                $this->getSortingFilter("adv_", $sortings_filter_hidden),
            );
        }
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
    public function commonActionsUrl($case, $group_id) {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/common-action-url-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%ID%",
            "%CLASS%",
            "%TEXT%",
            "%GROUP_ID%"
        );
        switch ($case) {
            case 'group_rejected':
                $fields_replace = array(
                    "",
                    "",
                    LBL_REJECTED,
                    encryptIt($group_id)
                );
                break;
            case 'withdraw_request':
                $fields_replace = array(
                    "withdraw_request",
                    "icon-close",
                    LBL_WITHDRAW_REQUEST,
                    encryptIt($group_id)
                );
                break;
            case 'leave_group':
                $fields_replace = array(
                    "leave_group",
                    "icon-close",
                    LBL_LEAVE_GROUP,
                    encryptIt($group_id)
                );
                break;
            case 'ask_to_join':
                $fields_replace = array(
                    "ask_to_join",
                    "icon-check",
                    LBL_ASK_TO_JOIN,
                    encryptIt($group_id)
                );
                break;
            case 'join_group':
                $fields_replace = array(
                    "join_group",
                    "icon-check",
                    LBL_JOIN_GROUP,
                    encryptIt($group_id)
                );
                break;
            default:
                $fields_replace = array(
                    "",
                    "",
                    "",
                    ""
                );
                break;
        }
        $content = str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function getUsersBeforeLogin($currentPage) {
        $response = array();
        $response['status'] = false;
        $users_html = $pagination = $applied_filters = "";
        $totalUsers = $next_available_records = 0;
        $limit = NO_OF_SEARCH_RESULTS_PER_PAGE;
        $offset = ($currentPage - 1) * $limit;
        $applied_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-filter-li-nct.tpl.php");
        $applied_filter_li_tpl_parsed = $applied_filter_li_tpl->parse();
        $applied_location_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-location-filter-li-nct.tpl.php");
        $applied_location_filter_li_tpl_parsed = $applied_location_filter_li_tpl->parse();
        $fields_applied_filter = array(
            "%FILTER_NAME%",
            "%DATA_ID%"
        );

        $query = "SELECT u.id,u.first_name,u.last_name, i.industry_name_".$this->lId." as industry_name, l.formatted_address
                    FROM tbl_users u 
                    LEFT JOIN tbl_user_experiences ue ON ue.user_id = u.id AND ue.is_current='y' 
                    LEFT JOIN tbl_companies c ON c.id = ue.company_id 
                    LEFT JOIN tbl_industries i ON i.id = ue.industry_id 
                    LEFT JOIN tbl_locations l ON l.id = u.location_id 
                    WHERE u.email_verified = ? AND u.status = ? ";
        $wherearr=array('y','a');
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = filtering($_GET['keyword'], 'input');
            $query .= " AND ( u.first_name LIKE '%" . $keyword . "%' OR u.last_name LIKE '%" . $keyword . "%' OR CONCAT( u.first_name, ' ', u.last_name ) LIKE '%" . $keyword . "%' OR u.email_address LIKE '%" . $keyword . "%' OR ue.job_title LIKE '%" . $keyword . "%' ) ";
        }

        if (isset($_GET['latitude']) && $_GET['latitude'] != "") {
            $formatted_address = filtering($_GET['formatted_address'], 'input');
            $address1 = filtering($_GET['address1'], 'input');
            $address2 = filtering($_GET['address2'], 'input');
            $country = filtering($_GET['country'], 'input');
            $state = filtering($_GET['state'], 'input');
            $city1 = filtering($_GET['city1'], 'input');
            $city2 = filtering($_GET['city2'], 'input');
            if ($formatted_address != "") {
                $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
            }
            if ($address1 != "") {
                $query .= " AND l.address1 = '" . $address1 . "' ";
            }
            if ($address2 != "") {
                $query .= " AND l.address2 = '" . $address2 . "' ";
            }
            if ($country != "") {
                $query .= " AND l.country = '" . $country . "' ";
            }
            if ($state != "") {
                $query .= " AND l.state = '" . $state . "' ";
            }
            if ($city1 != "") {
                $query .= " AND l.city1 = '" . $city1 . "' ";
            }
            if ($city2 != "") {
                $query .= " AND l.city2 = '" . $city2 . "' ";
            }
            $fields_replace_applied_filter = array(
                filtering($_GET['formatted_address']),
                ""
            );
            $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
        } else if($_GET['location'] != ''){
            $reverse_geocode = reverse_geocode($_GET['location']);
            if($reverse_geocode['status'] == 'OK'){
                $formatted_address = filtering($_GET['location'], 'input');
                $address1 = filtering($reverse_geocode['address'], 'input');
                $country = filtering($reverse_geocode['country'], 'input');
                $state = filtering($reverse_geocode['state'], 'input');
                $city1 = filtering($reverse_geocode['city'], 'input');
                if ($formatted_address != "") {
                    $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
                }
                if ($address1 != "") {
                    $query .= " AND l.address1 = '" . $address1 . "' ";
                }
                if ($address2 != "") {
                    $query .= " AND l.address2 = '" . $address2 . "' ";
                }
                if ($country != "") {
                    $query .= " AND l.country = '" . $country . "' ";
                }
                if ($state != "") {
                    $query .= " AND l.state = '" . $state . "' ";
                }
                if ($city1 != "") {
                    $query .= " AND l.city1 = '" . $city1 . "' ";
                }
                if ($city2 != "") {
                    $query .= " AND l.city2 = '" . $city2 . "' ";
                }
                $fields_replace_applied_filter = array(
                    filtering($formatted_address),
                    ""
                );
                $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
            }
        }
        $query .= " GROUP BY u.id ORDER BY  CASE WHEN ue.is_headline THEN 1 WHEN ue.industry_id THEN 2 WHEN  u.location_id THEN 3 ELSE 4 END ,u.id ASC ";
        $totalUsers = count($this->db->pdoQuery($query,$wherearr)->results());
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $users = $this->db->pdoQuery($query_with_limit,$wherearr)->results();

        if ($users) {
            //echo "<pre>";print_r($users);exit;
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
            $next_users = $this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records = count($next_users);
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/single-user-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $fields = array(
                "%USER_PROFILE_PICTURE%",
                "%USER_PROFILE_URL%",
                "%USER_NAME_FULL%",
                "%HEADLINE%",
                "%INDUSTRY_NAME%",
                "%FORMATTED_ADDRESS%",
                "%SCORE%",
                "%CONNECT_BUTTON%",
                "%SKILLS%",
                "%DEGREE_OF_CONNECTION%",
                "%COMMON_CONNECTION%",
                "%SEND_INMAIL_CLASS%",
                "%SEND_INMAIL_TITLE%",
                "%SEND_INMAIL_TEXT%",
                "%SEND_INMAIL_URL%",
                "%COMMON_CONNECTION_CLASS%",
                "%CLASS_FOLLOW%",
                "%CLASS_INS%",
                "%CLASS_SKILL_HIDE%",
                "%CLASS_LOCATION%"
            );
            $common_connection_class = 'hide';
            for ($i = 0; $i < count($users); $i++) {
                //echo "<pre>";print_r($users[$i]);exit;
                $user_actions = null;
                $user_id = $users[$i]['id'];
                $user_profile_url = get_user_profile_url($user_id);
                $first_name = filtering($users[$i]['first_name']);
                $last_name = filtering($users[$i]['last_name']);
                $user_name_full = $first_name . " " . $last_name;
                $skills = implode(' , ', getUserSkills($user_id));
                $send_inmail_url = '';
                $send_inmail_text = '';
                $class_ins="";
                if($users[$i]['industry_name']=='')
                    $class_ins='hidden';
                $class_skill_hide="";
                if($skills=='')
                    $class_skill_hide="hidden";
                $class_location="";
                if($users[$i]['formatted_address']=='')
                    $class_location='hidden';

                $fields_replace = array(
                    getImageURL("user_profile_picture", $user_id, "th3"),
                    $user_profile_url,
                    ucwords($user_name_full),
                    ucwords(getUserHeadline($user_id)),
                    ucwords(filtering($users[$i]['industry_name'])),
                    filtering($users[$i]['formatted_address']),
                    '',
                    $user_actions,
                    $skills,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $common_connection_class,
                    'hidden',
                    $class_ins,
                    $class_skill_hide,
                    $class_location

                );
                $users_html .= str_replace($fields, $fields_replace, $single_user_tpl_parsed);
            }

            if ($next_available_records > 0) {
                $keyword=($_GET['keyword'] != '')?$_GET['keyword']:'';
                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getUsersBeforeLogin/currentPage/" . ($currentPage + 1)."/".$keyword;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $users_html .= $load_more_li_tpl->parse();
            }


            $pagination = getPagination($totalUsers, count($users), NO_OF_SEARCH_RESULTS_PER_PAGE, $currentPage);
        } else {
            $single_user_tpl = new Templater(DIR_TMPL . $this->module . "/single-user-nct.tpl.php");
            $single_user_tpl_parsed = $single_user_tpl->parse();
            $no_result_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $users_html = $no_result_tpl->parse();
        }
        if ($applied_filters) {
            $reset_applied_filters = new Templater(DIR_TMPL . $this->module . "/reset-applied-filters-nct.tpl.php");
            $applied_filters .= $reset_applied_filters->parse();
        }
        $response['status'] = true;
        $response['content'] = $users_html;
        $response['pagination'] = $pagination;
        $response['total_records'] = $totalUsers;
        $response['next_available_records'] = $next_available_records;
        $response['applied_filters'] = $applied_filters;
        return $response;
    }
    public function getCompaniesBeforeLogin($currentPage) {
       // print_r($_REQUEST);die;
        //print_r($_SERVER['REQUEST_URI']);die;
        $response = array();
        $response['status'] = false;
        $companies_html = $pagination = $applied_filters = "";
        $totalRecords = $next_available_records = 0;
        $limit = NO_OF_SEARCH_RESULTS_PER_PAGE;
        $offset = ($currentPage - 1) * $limit;
        $applied_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-filter-li-nct.tpl.php");
        $applied_filter_li_tpl_parsed = $applied_filter_li_tpl->parse();
        $applied_location_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-location-filter-li-nct.tpl.php");
        $applied_location_filter_li_tpl_parsed = $applied_location_filter_li_tpl->parse();
        $applied_no_of_followers_filter_li_tpl = new Templater(DIR_TMPL . $this->module . "/applied-no-of-followers-filter-li-nct.tpl.php");
        $applied_no_of_followers_filter_li_tpl_parsed = $applied_no_of_followers_filter_li_tpl->parse();
        $fields_applied_filter = array(
            "%FILTER_NAME%",
            "%DATA_ID%"
        );
        $query = "SELECT comp.*, concat_ws(' - ', cs.minimum_no_of_employee, cs.maximum_no_of_employee) as range_of_no_of_employees, i.industry_name, l.formatted_address, COUNT(DISTINCT cf.id) as no_of_followers
                    FROM tbl_companies comp 
                    LEFT JOIN tbl_company_sizes cs ON cs.id = comp.company_size_id 
                    LEFT join tbl_company_locations cl ON (cl.company_id = comp.id AND cl.is_hq = 'y') 
                    LEFT JOIN tbl_locations l ON l.id = cl.location_id 
                    LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id 
                    LEFT JOIN tbl_company_followers cf ON cf.company_id = comp.id 
                    WHERE  comp.company_type = ? AND comp.status = ? ";
        $wherearr=array('r','a');
        if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
            $keyword = filtering($_GET['keyword'], 'input');
            $query.=" AND ( comp.company_name LIKE '%" . $keyword . "%' OR comp.company_description LIKE '%" . $keyword . "%' ) ";
        }

        // if (isset($_GET['company_sizes']) && !empty($_GET['company_sizes'])) {

        //     $company_sizes = $_GET['company_sizes'];
        //     $query .= " AND comp.company_size_id IN (" . implode(",", $company_sizes) . ") ";
        //     for ($i = 0; $i < count($company_sizes); $i++) {
        //         $company_size = getTableValue("tbl_company_sizes", "company_size", array("id" => $company_sizes[$i]));
        //         $fields_replace_applied_filter = array(
        //             $company_size,
        //             "adv_company_size_" . $company_sizes[$i]
        //         );
        //         $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
        //     }
        // }

        if (isset($_GET['sorting_lt']) && !empty($_GET['sorting_lt'])) {

            $sorting_lt = $_GET['sorting_lt'];
           // $query .= " AND comp.company_size_id IN (" . implode(",", $company_sizes) . ") ";
            // for ($i = 0; $i < count($company_sizes); $i++) {
            //     $company_size = getTableValue("tbl_company_sizes", "company_size", array("id" => $company_sizes[$i]));
            //     $fields_replace_applied_filter = array(
            //         $company_size,
            //         "adv_company_size_" . $company_sizes[$i]
            //     );
            //     $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_filter_li_tpl_parsed);
            // }
        }

        if (isset($_GET['latitude']) && $_GET['latitude'] != "") {
            $formatted_address = filtering($_GET['formatted_address'], 'input');
            $address1 = filtering($_GET['address1'], 'input');
            $address2 = filtering($_GET['address2'], 'input');
            $country = filtering($_GET['country'], 'input');
            $state = filtering($_GET['state'], 'input');
            $city1 = filtering($_GET['city1'], 'input');
            $city2 = filtering($_GET['city2'], 'input');
            if ($formatted_address != "") {
                $query .= " AND l.formatted_address = '" . $formatted_address . "' ";
            }
            if ($address1 != "") {
                $query .= " AND l.address1 = '" . $address1 . "' ";
            }
            if ($address2 != "") {
                $query .= " AND l.address2 = '" . $address2 . "' ";
            }
            if ($country != "") {
                $query .= " AND l.country = '" . $country . "' ";
            }
            if ($state != "") {
                $query .= " AND l.state = '" . $state . "' ";
            }
            if ($city1 != "") {
                $query .= " AND l.city1 = '" . $city1 . "' ";
            }
            if ($city2 != "") {
                $query .= " AND l.city2 = '" . $city2 . "' ";
            }
            $fields_replace_applied_filter = array(
                filtering($_GET['formatted_address']),
                ""
            );
            $applied_filters .= str_replace($fields_applied_filter, $fields_replace_applied_filter, $applied_location_filter_li_tpl_parsed);
        }


        //$query .= " GROUP BY comp.id ORDER BY comp.id DESC ";
        $query.="GROUP BY comp.id ORDER BY  CASE WHEN cl.is_hq THEN 1 WHEN comp.company_description THEN 2 WHEN  cl.location_id THEN 3 ELSE 4 END  ASC ";
        $totalRecords = count($this->db->pdoQuery($query,$wherearr)->results());
        $query_with_limit = $query . " LIMIT " . $limit . " OFFSET " . $offset;
        $companies = $this->db->pdoQuery($query_with_limit,$wherearr)->results();
        if ($companies) {
            $query_with_next_limit = $query . " LIMIT " . $limit . " OFFSET " . ( $limit + $offset );
            $next_company= $this->db->pdoQuery($query_with_next_limit,$wherearr)->results();
            $next_available_records =count($next_company);

            $single_company_tpl = new Templater(DIR_TMPL . $this->module . "/single-company-nct.tpl.php");
            $single_company_tpl_parsed = $single_company_tpl->parse();
            $fields = array(
                "%COMPANY_ID_ENCRYPTED%",
                "%COMPANY_NAME%",
                "%COMPANY_PAGE_URL%",
                "%COMPANY_LOGO_URL%",
                "%COMPANY_INDUSTRY%",
                "%WEBSITE_OF_COMPANY%",
                "%DESCRIPTION%",
                "%OWNER_EMAIL_ADDRESS%",
                "%COMPANY_LOCATION%",
                "%RANGE_OF_NO_OF_EMPLOYEES%",
                "%EDIT_COMPANY_URL%",
                "%FOLLOWERS%",
                "%SCORE%",
                "%HIDE_CLASS%",
                "%CLASS_HIDE_ADD%",
                "%HIDE_CLASS_URL%",
                "%SHOW_CLASS%"
            );

            for ($i = 0; $i < count($companies); $i++) {
                $company_id = filtering($companies[$i]['id'], 'output', 'int');
                $company_page_url = get_company_detail_url($company_id);
                $company_logo_url = $company_logo_web_url = getImageURL("company_logo", $company_id, "th2");
                $company_logo_web_url = ($company_logo_web_url == '') ? '<span class="profile-picture-character">'.ucfirst($companies[$i]['company_name'][0]).'</span>' : $company_logo_web_url;
                $hide_class=$hide_class_add=$hide_class_url='';
                if($companies[$i]['company_description'] == ''){
                    $hide_class='hidden';
                }
                if($companies[$i]['formatted_address'] == ''){
                    $hide_class_add="hidden";
                }
                if($website_of_company == ''){
                    $hide_class_url="hidden";
                }
                $show_class='hidden';
                if($this->current_user_id > 0){
                    $show_class='';
                }
                
                $fields_replace = array(
                    encryptIt($company_id),
                    ucwords(filtering($companies[$i]['company_name'])),
                    $company_page_url,
                    $company_logo_web_url,
                    ucwords(filtering($companies[$i]['industry_name'])),
                    filtering($companies[$i]['website_of_company']),
                    substr(filtering($companies[$i]['company_description']), 0, 80) . "...",
                    filtering($companies[$i]['owner_email_address']),
                    filtering($companies[$i]['formatted_address']),
                    filtering($companies[$i]['range_of_no_of_employees']),
                    '',
                    filtering($companies[$i]['no_of_followers']),
                    '',
                    $hide_class,
                    $hide_class_add,
                    $hide_class_url,
                    $show_class
                );
                $companies_html .= str_replace($fields, $fields_replace, $single_company_tpl_parsed);
            }
            if ($next_available_records > 0) {
                $keyword=($_GET['keyword'] != '')?$_GET['keyword']:'';

                $load_more_li_tpl = new Templater(DIR_TMPL . $this->module . "/load-more-li-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getCompaniesBeforeLogin/currentPage/" . ($currentPage + 1)."/".$keyword;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $companies_html .= $load_more_li_tpl->parse();
            }
            $pagination = getPagination($totalRecords, count($companies), NO_OF_SEARCH_RESULTS_PER_PAGE, $currentPage);
        } else {
            $no_result_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
            $companies_html = $no_result_tpl->parse();
        }
        
        if ($applied_filters) {
            $reset_applied_filters = new Templater(DIR_TMPL . $this->module . "/reset-applied-filters-nct.tpl.php");
            $applied_filters .= $reset_applied_filters->parse();
        }
        $response['status'] = true;
        $response['content'] = $companies_html;
        $response['pagination'] = $pagination;
        $response['total_records'] = $totalRecords;
        $response['next_available_records'] = $next_available_records;
        $response['applied_filters'] = $applied_filters;
        return $response;
    }
} ?>