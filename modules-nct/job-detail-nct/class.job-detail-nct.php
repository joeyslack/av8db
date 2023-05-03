<?php

class Job_detail extends Home {

    function __construct($job_id = '',$current_user_id=0,$platform='web') {
        $this->job_id = $job_id;
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->platform = $platform;
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);

        if($this->job_id > 0) {
            $query = "SELECT jobs.featured_till,jobs.last_date_of_application,jobs.job_title,jobs.company_id,jobs.added_on,jobs.key_responsibilities,jobs.skills_and_exp,jobs.user_id,jobs.is_featured,jobs.apply_flag,jobs.apply_email,jobs.apply_url,jobs.employment_type,jobs.relavent_experience_from,jobs.relavent_experience_to,comp.company_logo,comp.company_name,comp.company_description,
                    jcate.job_category_".$this->lId." as job_category,jcate.id as job_category_ids,jobs.licenses_endorsement_id ,l.country,l.state,l.city1,l.city2,i.industry_name_".$this->lId." as industry_name,
                    CONCAT(u.first_name,' ',u.last_name) as user_name,comp.company_industry_id,le.licenses_endorsements_name_".$this->lId." as licenses_endorsements_name
                    FROM tbl_jobs jobs
                    LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id
                    LEFT JOIN tbl_job_category jcate ON jobs.job_category_id = jcate.id
                    LEFT JOIN tbl_locations l ON jobs.location_id = l.id
                    LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id
                    LEFT JOIN tbl_users u ON u.id = jobs.user_id
                    LEFT JOIN tbl_license_endorsements le ON le.id = jobs.licenses_endorsement_id
                    WHERE jobs.id = ? ";

            $job_details_array = $this->db->pdoQuery($query,array($this->job_id))->result();
           // _print_r($job_details_array);exit;
            $this->company_name = filtering($job_details_array['company_name'], 'output');

            $city = $job_details_array['city1'] != '' ? $job_details_array['city1'] : $job_details_array['city2'];
            $state = $job_details_array['state'];
            $country = $job_details_array['country'];
            //$this->location = $city . ", " . $state . ", " . $country;
            $this->location = $city;
            $this->location .= (($this->location != '' && $state != '') ?', ':'').$state;
            $this->location .= (($this->location != '' && $country != '') ?', ':'').$country;

            $this->industry_id = filtering($job_details_array['company_industry_id'], 'output', 'int');
            $this->job_category_ids = filtering($job_details_array['job_category_ids'], 'output', 'int');
            $this->last_date_of_application = filtering($job_details_array['last_date_of_application']);
            $this->industry_name = filtering($job_details_array['industry_name'], 'output');
            $this->job_title = filtering($job_details_array['job_title'], 'output');

            require_once(DIR_MOD . 'common_storage.php');
            $job_detail_storage = new storage();

            $logo_url = DIR_NAME_COMPANY_LOGOS."/";

            $company_logo_name = getTableValue("tbl_companies", "company_logo", array("id" => $job_details_array['company_id']));
            $company_names = getTableValue("tbl_companies", "company_name", array("id" => $job_details_array['company_id']));
            $company_logos_url = '';
            $src = $job_detail_storage->getImageUrl1('av8db','th2_'.$company_logo_name,$logo_url);
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
            $this->company_logo_url = $company_logos_url;

            // $this->company_logo_url = getImageURL('company_logo', $job_details_array['company_id'], "th2",$this->platform);
            $this->company_id = $job_details_array['company_id'];

            $this->company_url = get_company_detail_url($job_details_array['company_id']);

            $this->posted_date = strtotime($job_details_array['added_on']);

            $this->job_responsibility = filtering($job_details_array['key_responsibilities'], 'output', 'text');

            $this->skills_and_exp = filtering($job_details_array['skills_and_exp'], 'output', 'text');

            $this->user_name = filtering($job_details_array['user_name'], 'output');

            $this->user_id = filtering($job_details_array['user_id'], 'output', 'int');
            $this->is_featured = filtering($job_details_array['is_featured'], 'output', 'output');
            $this->featured_till = filtering($job_details_array['featured_till'], 'output', 'int');
            $this->apply_flag = filtering($job_details_array['apply_flag'], 'output');
            $this->apply_email = filtering($job_details_array['apply_email'], 'output');
            $this->apply_url = filtering($job_details_array['apply_url'], 'output');

            $this->employment_type = filtering($job_details_array['employment_type'], 'output');

            $this->relavent_experience_from = filtering($job_details_array['relavent_experience_from'], 'output');
            $this->relavent_experience_to = filtering($job_details_array['relavent_experience_to'], 'output');

            $this->job_category = filtering($job_details_array['job_category'], 'output');
            $this->licenses_endorsements_name = isset($job_details_array['licenses_endorsements_name']) ? $job_details_array['licenses_endorsements_name'] : 'N/A';
            $this->licenses_endorsement_id = isset($job_details_array['licenses_endorsement_id']) ? $job_details_array['licenses_endorsement_id'] : 'N/A';
        }
    }

    public function getSocialSharingIcons() {
        //Social sharing
        $share_on_social_media = new Templater(DIR_TMPL . $this->module . "/share-on-social-media-nct.tpl.php");
        $share_on_social_media_parsed =  $share_on_social_media->parse();

        $social_media_content = $share_button = '';

        if($this->session_user_id > 0 && $this->last_date_of_application > date('Y-m-d H:i:s')){
            $share_button = $this->getShareButton(encryptIt($this->job_id));

            $fields = array("%JOB_TITLE%", "%ENC_JOB_ID%","%SHARE%");
            $fields_replace = array($this->job_title, encryptIt($this->job_id),$share_button);

            $social_media_content = str_replace($fields, $fields_replace, $share_on_social_media_parsed);
        }

        return $social_media_content;
    }

    public function getShareButton($job_id){
        $share_button = new Templater(DIR_TMPL . $this->module . "/share-nct.tpl.php");
        $share_button_parsed =  $share_button->parse();

        $fields = array("%ENC_JOB_ID%");
        $fields_replace = array($this->job_id);

        $social_button_content = str_replace($fields, $fields_replace, $share_button_parsed);

        return $social_button_content;
    }

    public function jobPostedUserDetails() {
        //Job posted by
        $job_posted_by = new Templater(DIR_TMPL . $this->module . "/job-posted-by-nct.tpl.php");
        $job_posted_by_parsed =  $job_posted_by->parse();
        $user_profile_url = get_user_profile_url($this->user_id);
        $job_title = $company_name = '';
        $job_location_id = 0;
        //$getHaedline = $this->db->select("tbl_user_experiences", array('job_title','job_location_id'), array("user_id" => $this->user_id, "is_current" => "y"))->result();
        // if ($getHaedline) {
        //     $job_title = filtering($getHaedline['job_title']);
        //     //$company_name = filtering($getHaedline['company_name']);
        //     $company_name = '';
        //     $job_location_id = filtering($getHaedline['job_location_id']);
        // }
        $headline = '';
        // $job_location_array = $this->db->select("tbl_locations", array('city1','city2','state','country','formatted_address'), array("id" => $job_location_id))->result();
        // $city = $job_location_array['city1'] != '' ? $job_location_array['city1'] : $job_location_array['city2'];
        // $state = $job_location_array['state'];
        // $country = $job_location_array['country'];
        //$job_location = $city . ", " . $state . ", " . $country;
       // $job_location = $job_location_array['formatted_address'];
        
        $fields = array("%USER_NAME%", "%USER_PROFILE_URL%", "%JOB_TITLE%", "%COMPANY_NAME%", "%JOB_LOCATION%","%COMPANY_LOGO_URL%","%USER_IMAGE_URL%","%MESSAGE_URL%","%HEADLINE%");

        require_once(DIR_MOD . 'common_storage.php');
        $job_creator_storage = new storage();

        $user_src = DIR_NAME_USERS."/".$this->user_id."/";

        $image_name = getTableValue("tbl_users", "profile_picture_name", array("id" => $this->user_id));
        $fnm = getTableValue("tbl_users", "first_name", array("id" => $this->user_id));
        
        $company_logos_url = '';
        $src = $job_creator_storage->getImageUrl1('av8db','th2_'.$image_name,$user_src);
        $ck = getimagesize($src);
        if (empty($ck)) {
            $company_logos_url = '<span title="' . $fnm.'" class="profile-picture-character">' . ucfirst($fnm[0]) . '</span>';
        }else 
        {   
            $company_logos_url ='<picture>
                                    <source srcset="' . $src . '" type="image/jpg">
                                    <img src="' . $src . '" class="" alt="img" /> 
                                </picture>';
        }
        $user_profile_picture = $company_logos_url;
        // $user_profile_picture = getImageURL("user_profile_picture", $this->user_id, "th2",$this->platform);
        $fields_replace = array(
            ucwords($this->user_name),
            $user_profile_url,
            '', //ucwords($job_title),
            ucwords($company_name),
            '',
            //$job_location,
            $this->company_logo_url,
            $user_profile_picture,
            SITE_URL . "compose-message/".encryptIt($this->user_id),
            $headline,
        );
        if($this->platform == 'app'){

            $admin_id = $this->user_id;
            $admin_name = $this->user_name;
            $admin_image = $user_profile_picture;
            $admin_headline = $headline;
            $admin_location = $job_location;

            $user_content = array('admin_id'=>$admin_id,'admin_name'=>$admin_name,'admin_image'=>$admin_image,'admin_headline'=>$admin_headline,'admin_location'=>$admin_location);
        } else {
            $user_content = str_replace($fields, $fields_replace, $job_posted_by_parsed);
        }
        return $user_content;
    }

    public function getSimilarJobs($job_id , $industry_id , $limit_flag = false, $currentPage = 1) {

        $job_content = '';

        $totalRows = $showableRows = 0;

        $limit = NO_OF_SIMILAR_JOBS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $similar_jobs = new Templater(DIR_TMPL . $this->module . "/similar-jobs-nct.tpl.php");
        $similar_jobs_parsed =  $similar_jobs->parse();

        $job_category = getTableValue("tbl_jobs","job_category_id",array("id"=>$job_id));

        $query = "SELECT jobs.id,jobs.job_title,jobs.company_id, comp.company_name,comp.company_logo,l.country,l.state,l.city1,l.city2,i.industry_name_".$this->lId." as industry_name
                    FROM tbl_jobs jobs
                    LEFT JOIN tbl_job_category jc ON jobs.job_category_id = jc.id
                    LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id
                    LEFT JOIN tbl_locations l ON jobs.location_id = l.id
                    LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id
                    WHERE jobs.status = ? AND jobs.job_category_id = ? AND comp.company_industry_id = ? AND  jobs.id != ? AND jobs.last_date_of_application >= '" . date("Y-m-d") . "' AND jobs.user_id != ? ";

        $where_arr=array('a',$job_category,$industry_id,$job_id,$this->current_user_id);
        $similar_job_array = $this->db->pdoQuery($query,$where_arr)->results();

        $total_similar_jobs = count($similar_job_array);

        if($limit_flag) {
            $query_with_limit = $query . "LIMIT 4";

            $similar_job_array = $this->db->pdoQuery($query_with_limit,$where_arr)->results();
        } else {
            $limit_query = $query . " LIMIT " . $limit . " OFFSET " . $offset;

            $similar_job_array = $this->db->pdoQuery($limit_query,$where_arr)->results();
        }

        $totalRows = $total_similar_jobs;

        require_once(DIR_MOD . 'common_storage.php');
        $similar_job_storage = new storage();

        if($similar_job_array) {
            $showableRows = count($similar_job_array);
            $fields = array("%JOB_ID%","%ENCRYPTED_JOB_ID%","%JOB_TITLE%","%LOCATION%","%COMPANY_NAME%","%COMPANY_LOGO_URL%","%JOB_URL%","%APPLY_JOB_URL%","%SAVE_JOB_URL%");
            foreach ($similar_job_array as $key => $value) {

                if($this->session_user_id > 0){

                    $apply_job_url = $this->appliedJobUrl($value['id']);
                    $save_job_url = $this->savedJobUrl($value['id']);
                }
                $city = $value['city1'] != '' ? $value['city1'] : $value['city2'];
                $state = $value['state'];
                $country = $value['country'];
                //$location = $city . ", " . $state . ", " . $country;

                $location = $city;
                $location .= (($location != '' && $state != '') ?', ':'').$state;
                $location .= (($location != '' && $country != '') ?', ':'').$country;

                $job_title = filtering($value['job_title'], 'output');
                $company_name = filtering($value['company_name'], 'output');

                $logo_url = DIR_NAME_COMPANY_LOGOS."/";

                $company_logo_name = getTableValue("tbl_companies", "company_logo", array("id" => $value['company_id']));
               
                $company_logos_url = '';
                $src = $similar_job_storage->getImageUrl1('av8db','th2_'.$company_logo_name,$logo_url);
                $ck = getimagesize($src);
                if (empty($ck)) {
                    $company_logos_url = '<span title="' . $company_name.'" class="profile-picture-character">' . ucfirst($company_name[0]) . '</span>';
                }else 
                {   
                    $company_logos_url ='<picture>
                                            <source srcset="' . $src . '" type="image/jpg">
                                            <img src="' . $src . '" class="" alt="img" /> 
                                        </picture>';
                }

                $company_logo = $company_logos_url;
                // $company_logo = getImageURL('company_logo', $value['company_id'], "th2",$this->platform);
                $company_logo_web_url = $company_logo;
                // $company_logo_web_url = ($company_logo == '') ? '<span class="profile-picture-character">'.ucfirst($company_name[0]).'</span>' :$company_logo;
                $industry_name = filtering($value['industry_name'], 'output');
                $fields_replace = array(
                    filtering($value['id'], 'output', 'int'),
                    encryptIt(filtering($value['id'], 'output', 'int')),
                    ucwords($job_title),
                    $location,
                    ucwords($company_name),
                    $company_logo_web_url,
                    get_job_detail_url(filtering($value['id'], 'output', 'int')),
                    $apply_job_url,
                    $save_job_url,
                );
                if($this->platform=='app'){
                    $job_content=array();

                    $job_id = $value['id'];
                    $job_title = $job_title;
                    $company_id = $value['company_id'];
                    $company_title = $company_name;
                    $company_logo = $company_logo;
                    $industry_name = $industry_name;

                    $job_content[] = array('job_id'=>$job_id,'job_title'=>$job_title,'company_id'=>$company_id,'company_title'=>$company_title,'company_logo'=>$company_logo,'industry_name'=>$industry_name,'job_category'=>$this->job_category,'location'=>$location);
                } else {
                    $job_content .= str_replace($fields, $fields_replace, $similar_jobs_parsed);
                }
            }
        } else {
            if($this->platform != 'app'){
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                $message = LBL_NO_SIMILAR_JOB;
                $no_result_found_tpl->set('message', $message);
                $job_content = $no_result_found_tpl->parse();
                $response['content'] = $job_content;
            }
        }


        if($this->platform=='app'){
            $response['similar_jobs'] = $job_content;
            $page_data = getPagerData($totalRows, NO_OF_SIMILAR_JOBS_PER_PAGE,$currentPage);
            $response['similar_job_pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
        } else {
            $response['content'] = $job_content;
            $response['total_similar_jobs'] = $total_similar_jobs;
            $response['job_id'] = $job_id;
            $response['industry_id'] = $industry_id;
            $response['pagination'] = getPagination($totalRows, $showableRows, NO_OF_SIMILAR_JOBS_PER_PAGE, $currentPage);
        }
        return $response;
    }

    public function getJobApplicants($job_id, $currentPage = 1) {

        $job_content = '';
        $send_inmail_text = '';

        $totalRows = $showableRows = 0;

        $limit =NO_OF_JOB_APPLICANTS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        $job_applicants = new Templater(DIR_TMPL . $this->module . "/single-job-applicant-nct.tpl.php");
        $job_applicants_parsed =  $job_applicants->parse();

        $query = "SELECT jobs.id, japp.user_id,jobs.job_title,jobs.company_id,comp.company_name,
                    comp.company_logo,CONCAT(u.first_name,  ' ' , u.last_name) as user_name, l.formatted_address
                    FROM tbl_jobs jobs
                    LEFT JOIN tbl_job_applications japp ON japp.job_id = jobs.id
                    LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id
                    LEFT JOIN tbl_users u ON u.id = japp.user_id
                    LEFT JOIN tbl_locations l ON l.id = u.location_id
                    WHERE  japp.job_id = ? ";

        $where_arr=array($job_id);            
        $job_applicants_array = $this->db->pdoQuery($query,$where_arr)->results();

        $total_job_applicants = count($job_applicants_array);


        $limit_query = $query . " LIMIT " . $limit . " OFFSET " . $offset;

        $job_applicants_array = $this->db->pdoQuery($limit_query,$where_arr)->results();

        $totalRows = $total_job_applicants;

        if($job_applicants_array) {
            require_once(DIR_MOD . 'common_storage.php');
            $job_app_storage = new storage();

            $showableRows = count($job_applicants_array);
            foreach ($job_applicants_array as $key => $value) {
                $fields = array("%JOB_ID%","%ENCRYPTED_JOB_ID%","%JOB_TITLE%","%USER_NAME%","%COMPANY_NAME%","%COMPANY_LOGO_URL%","%JOB_URL%","%USER_HEAD_LINE%","%USER_IMAGE_URL%","%USER_URL%","%FORMATTED_ADDRESS%","%SEND_INMAIL_URL%","%SEND_INMAIL_TEXT%");

                $connected_user_ids = getConnections($this->session_user_id);
                //print_r($connected_user_ids);
                //echo $value['user_id'];exit;
                if (is_array($connected_user_ids) && in_array($value['user_id'], $connected_user_ids)) {
                    $send_inmail_text = LBL_SEND_MSG;
                } else {
                    $send_inmail_text = LBL_SEND_INMAIL;
                }

                $user_src12 = DIR_NAME_USERS."/".$value['user_id']."/";
                $image_name1 = getTableValue("tbl_users", "profile_picture_name", array("id" => $value['user_id']));
                $fnm = getTableValue("tbl_users", "first_name", array("id" => $value['user_id']));
                
                $user_profile_picture = '';
                $src12 = $job_app_storage->getImageUrl1('av8db','th2_'.$image_name1,$user_src12);
                $ck12 = getimagesize($src12);
                if (empty($ck12)) {
                    $user_profile_picture = '<span title="' . $fnm.'" class="profile-picture-character">' . ucfirst($fnm[0]) . '</span>';
                }else 
                {   
                    $user_profile_picture ='<picture>
                                            <source srcset="' . $src12 . '" type="image/jpg">
                                            <img src="' . $src12 . '" class="" alt="img" /> 
                                        </picture>';
                }
                // $user_profile_picture = $company_logos_url;

                // $user_profile_picture = getImageURL("user_profile_picture", filtering($value['user_id'], 'output', 'int'), "th2",$this->platform);
                $tagline = '';
                //$tagline = getUserHeadline(filtering($value['user_id'], 'output', 'int'));
                //$experience = round(getUserExperience($value['user_id'],$value['company_id']));
                $formatted_address = filtering($value['formatted_address'], 'output');

                $logo_url = DIR_NAME_COMPANY_LOGOS."/";

                $company_logo_name = getTableValue("tbl_companies", "company_logo", array("id" => $value['company_id']));
                $company_names = getTableValue("tbl_companies", "company_name", array("id" => $value['company_id']));
                $company_logos_url = '';
                $src = $job_app_storage->getImageUrl1('av8db','th2_'.$company_logo_name,$logo_url);
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

                $fields_replace = array(
                    filtering($value['id'], 'output', 'int'),
                    encryptIt(filtering($value['id'], 'output', 'int')),
                    filtering($value['job_title'], 'output'),
                    ucwords(filtering($value['user_name'], 'output')),
                    ucwords(filtering($value['company_name'], 'output')),
                    $company_logos_url,
                    // getImageURL('company_logo', $value['company_id'], "th2"),
                    get_job_detail_url(filtering($value['id'], 'output', 'int')),
                    $tagline,
                    $user_profile_picture,
                    get_user_profile_url(filtering($value['user_id'], 'output', 'int')),
                    $formatted_address,
                    SITE_URL . 'compose-message/'.encryptIt(filtering($value['user_id'], 'output', 'int')),
                    $send_inmail_text
                );
                if($this->platform=='app'){
                    $app_array[] = array(
                        'id'=>$value['user_id'],
                        'name'=>$value['user_name'],
                        'image'=>$user_profile_picture,
                        'tagline'=>$tagline,
                        'experience'=>$experience,
                        'location'=>$formatted_address
                    );
                } else {
                    $job_content .= str_replace($fields, $fields_replace, $job_applicants_parsed);
                }
            }
            $page_data = getPagerData($totalRows, NO_OF_JOB_APPLICANTS_PER_PAGE,$currentPage);

            if ($page_data->numPages > 0 && $page_data->numPages > $currentPage ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . "/load-more-new-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getJobs_applicant/currentPage/" . ($currentPage + 1);
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $job_content .= $load_more_li_tpl->parse();
            }
            $response['content'] = $job_content;
            $response['total_job_applicants'] = $total_job_applicants;
            $response['job_id'] = $job_id;
            $response['pagination'] = getPagination($totalRows, $showableRows, NO_OF_JOB_APPLICANTS_PER_PAGE, $currentPage);
        }else {

            if ($totalRows > 0 && $currentPage > 1) {

                $response = $this->getJobApplicants($job_id,  ( $currentPage - 1));
            } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");

                $message = LBL_NO_APPLICANTS_JOB;

                $no_result_found_tpl->set('message', $message);
                $final_result_html = $no_result_found_tpl->parse();

                $response['content'] = $final_result_html;
                $response['pagination'] = "";
                $response['total_job_applicants'] = $total_job_applicants;
                $response['job_id'] = $job_id;
            }
        }
        if($this->platform == 'app'){
            $page_data = getPagerData($totalRows, $limit,$currentPage);
            $pagination=array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
            $final_app = array('applicants'=>(!empty($app_array)?$app_array:array()),'pagination'=>$pagination);
            return $final_app;
        }
        return $response;
    }
    public function getJobsPageContent() {
        $final_result = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");

        $main_content->set('share_on_social_media', $this->getSocialSharingIcons());

        $main_content->set('job_posted_by', '');
        $job_applicants_url = 'javascript:void();';
        if($this->user_id != $this->session_user_id){
            $jobPostedUserDetails = $this->jobPostedUserDetails();
            $main_content->set('job_posted_by', $jobPostedUserDetails);
        }else{
            $job_applicants_url = SITE_URL . "job-applicants/job/" . ($this->job_id);
        }

        $job_content = $this->getSimilarJobs($this->job_id, $this->industry_id,true,1);
        $total_similar_jobs = $job_content['total_similar_jobs'];
        $main_content->set('similar_jobs', $job_content['content']);

        $main_content->set('subscribed_membership_plan_details', $this->getSubscribedMembershipPlan($this->session_user_id));

        $main_content_parsed = $main_content->parse();

        $all_similar_jobs_link_visible = "hidden";
        if($total_similar_jobs > 4) {
            $all_similar_jobs_link_visible = "";
            $similar_jobs_link = SITE_URL . "similar-jobs/job/" . encryptIt($this->job_id) . "/industry/" . encryptIt($this->industry_id);
        }

        if($this->current_user_id > 0 ){
            if(($this->last_date_of_application > date('Y-m-d H:i:s') || $this->last_date_of_application == date('Y-m-d'))){
                $apply_job_url = $this->appliedJobUrl($this->job_id);
                $direct_apply_job_url = $this->directApplyJobUrl($this->job_id);
            }
            $save_job_url = $this->savedJobUrl($this->job_id);
        }

        // $skills_arr = $this->db->pdoQuery("SELECT skills.skill_name_".$this->lId." as skill_name FROM tbl_job_skills jskills
        //         LEFT JOIN tbl_skills skills ON skills.id = jskills.skill_id
        //         WHERE jskills.job_id = ? ",array($this->job_id))->results();

        // //_print($skills_arr);exit;

        // $skills = array();

        // if($skills_arr) {
        //     foreach ($skills_arr as $key => $value) {
        //         $skills[] = $value['skill_name'];
        //     }
        // }
        
         //print_r($user_data);exit;

         $fields = array(
            "%JOB_ID%",
            "%ENCRYPTED_JOB_ID%",
            "%JOB_TITLE%",
            "%INDUSTRY_NAME%",
            "%LOCATION%",
            "%LAST_DATE_OF_APPLICATION%",
            "%COMPANY_NAME%",
            "%COMPANY_LOGO_URL%",
            "%COMPANY_URL%",
            "%NO_OF_APPLICANTS%",
            "%POSTED_DATE%",
            "%RESPONSIBILITY%",
            "%SKILLS_AND_EXP%",
            "%POST_JOB_URL%",
            "%ALL_SIMILAR_JOBS_LINK%",
            "%APPLY_JOB_URL%",
            "%SAVE_JOB_URL%",
            "%JOB_APPLICANTS_URL%",
            "%ALL_SIMILAR_JOBS_LINK_VISIBLE%",
            "%EMPLOYMENT_TYPE%",
            "%EXPERIENCE%",
            "%JOB_CATEGORY%",
            "%SKILLS%",
            "%FEATURED%",
            "%COMPANY_NAME_OPTIONS%",
            "%CATEGORY_OPTIONS%",
            "%LICENSES_ENDORSEMENTS_NAME%",
            "%DIRECT_APPLY_JOB_LINK%"
        );

        //$all_similar_jobs_link = SITE_URL . "similar-jobs/job/" . encryptIt($this->job_id) . "/industry/" . encryptIt($this->industry_id);
        $location = str_replace(' ', '+', $this->location);
        $all_similar_jobs_link = SITE_URL . "search/jobs?industries[]=".$this->industry_id."&job_category[]=".$this->job_category_ids."&location=".$location."";

        $featured = '';
        $is_featured='n';
        if($this->is_featured == 'y' && $this->featured_till >= date('Y-m-d H:i:s') && ($this->last_date_of_application > date('Y-m-d H:i:s') || $this->last_date_of_application == date('Y-m-d'))){
            $featured_tpl = new Templater(DIR_TMPL . $this->module . "/featured.tpl.php");
            $featured = $featured_tpl->parse();
            $is_featured='y';
        }

        $posted_date = time_elapsed_string($this->posted_date);
        $last_date_of_application = ($this->platform == 'app') ? convertDate('onlyDate', $this->last_date_of_application) : convertDate('displayWeb', $this->last_date_of_application);

        $employment_type = $this->employment_type == 'f' ? LBL_EMPLOYMENTTYPE_FULL_TIME : ($this->employment_type == 'p' ? LBL_EMPLOYMENTTYPE_PART_TIME : ($this->employment_type == 'c' ? LBL_EMPLOYMENTTYPE_CONTRACT : LBL_EMPLOYMENTTYPE_TEMPORARY));
        $experience = $this->relavent_experience_from . " - " . $this->relavent_experience_to;
        $final_skills = $skills == array() ? " - " : implode(",",  $skills);
        $company_logo_url = $this->company_logo_url;

        $fields_replace = array(
            $this->job_id,
            encryptIt($this->job_id),
            ucwords($this->job_title),
            ucwords($this->industry_name),
            $this->location,
            $last_date_of_application,
            ucwords($this->company_name),
            $company_logo_url,
            $this->company_url,
            $this->getNoOFApplicants($this->job_id),
            $posted_date,
            $this->job_responsibility,
            $this->skills_and_exp,
            SITE_URL . "create-job-form",
            $all_similar_jobs_link,
            $apply_job_url,
            $save_job_url,
            $job_applicants_url,
            $all_similar_jobs_link_visible,
            $employment_type,
            $this->getMaximumHours(),
            $this->job_category,
            $final_skills,
            $featured,
            $this->getCompanyDD(),
            $this->getCategoriesDD(),
            $this->getLicenseList(),
            //$this->licenses_endorsements_name,
            $direct_apply_job_url,
            //$hide_direct_btn,
        );
        //print_r($fields_replace);exit;
        if($this->platform == 'app'){
            $this->apply_flag;
            $job_id = $this->job_id;
            $job_title = $this->job_title;

            $company_id = $this->company_id;
            $company_title = $this->company_name;
            $company_logo = $this->company_logo_url;
            $industry_name = $this->industry_name;

            $admin_id = $jobPostedUserDetails['admin_id'];
            $admin_name = $jobPostedUserDetails['admin_name'];
            $admin_image = $jobPostedUserDetails['admin_image'];
            $admin_headline = $jobPostedUserDetails['admin_headline'];
            $admin_location = $jobPostedUserDetails['admin_location'];
            $is_admin = (($admin_id == $this->current_user_id)?'y':'n');

            $total_applicants = $this->db->count('tbl_job_applications',array('job_id'=>$this->job_id));
            $applied_counter = $this->db->count('tbl_job_applications',array('job_id'=>$this->job_id,'user_id'=>$this->current_user_id));
            $is_applied = ($applied_counter>0?'y':'n');
            $job_detail_url = get_job_detail_url($this->job_id);


            $saved_counter = $this->db->count('tbl_saved_jobs',array('job_id'=>$this->job_id,'user_id'=>$this->current_user_id));
            $is_saved = ($saved_counter>0?'y':'n');


            $final_result = array(
                'job_id'=>$job_id,
                'job_title'=>$job_title,
                'company_id'=>$company_id,
                'company_title'=>$company_title,
                'company_logo'=>$company_logo,
                'posted'=>$posted_date,
                'industry_name'=>$industry_name,
                'last_date_of_application'=>$last_date_of_application,
                'location'=>$this->location,
                'responsibilities'=>$this->job_responsibility,
                'skills_and_exp'=>$this->skills_and_exp,
                'employment_type'=>$employment_type,
                'experience'=>$experience,
                'job_category'=>$this->job_category,
                'skills'=>$final_skills,
                'is_featured'=>$is_featured,
                'is_applied'=>$is_applied,
                'isSaved'=>$is_saved,
                'total_applicants'=>$total_applicants,
                'job_detail_url'=>$job_detail_url,
                'apply_flag'=>$this->apply_flag,
                'apply_email'=>$this->apply_email,
                'apply_url'=>$this->apply_url,
                'is_admin'=>$is_admin,
                'admin_id'=>$admin_id,
                'admin_name'=>$admin_name,
                'admin_image'=>$admin_image,
                'admin_headline'=>$admin_headline,
                'admin_location'=>$admin_location
            );
        } else {
            $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        }

        return $final_result;
    }
    public function getLicenseList(){
        $final_result = NULL;
        $idsArray = [];
        $lname = $this->db->pdoQuery('select license_ids from tbl_job_license_hours where job_id = "'.$this->job_id.'"')->results();
        foreach ($lname as $value) {
            array_push($idsArray,$value['license_ids']);
        }
        $license_names = $idsArray;
        //_print_r($idsArray);
        // exit();
        //$license_names = explode(',', $this->licenses_endorsement_id);
        $main_content = new Templater(DIR_TMPL . $this->module . "/single-licenses-endorsement-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array("%LICENSE_NAME%","%FLIGHT_HOURS%");
        for ($i = 0; $i < count($license_names); $i++) {
            
             $lname = $this->db->pdoQuery('select jl.id,l.licenses_endorsements_name_'.$this->lId.' as license_name,jl.license_hours as flight_hours from tbl_job_license_hours as jl LEFT JOIN tbl_license_endorsements as l ON jl.license_ids = l.id where license_ids = "'.$license_names[$i].'" AND l.isActive="y" AND job_id = "'.$this->job_id.'"')->result();
             //_print_r($lname);
            //$lname = $this->db->select("tbl_license_endorsements", array('id,licenses_endorsements_name_'.$this->lId.' as license_name,flight_hours'), array("id"=>$license_names[$i],"isActive" => "y"))->result();
            $license_name_display = isset($lname['license_name']) ? $lname['license_name'] : '-';
            $flight_hours = isset($lname['flight_hours']) ? $lname['flight_hours'] : '-';
            $fields_replace = array(
                    $license_name_display,
                    $flight_hours
                );
            $final_result .= str_replace($fields, $fields_replace, $main_content_parsed);
        }
        return $final_result;
    }
    public function getMaximumHours(){
        $idsArray = [];
        $lname = $this->db->pdoQuery('select license_ids from tbl_job_license_hours where job_id = "'.$this->job_id.'"')->results();
        foreach ($lname as $value) {
            array_push($idsArray,$value['license_ids']);
        }
        $license_names = $idsArray;
        //print_r($license_names);exit();
        //$license_names = explode(',', $this->licenses_endorsement_id);
        $hours_array = array();
        $fields = array("%LICENSE_NAME%","%FLIGHT_HOURS%");
        for ($i = 0; $i < count($license_names); $i++) {
            
             $lname = $this->db->pdoQuery('select id,MAX(license_hours) as max_flight from tbl_job_license_hours where job_id = "'.$this->job_id.'"')->result();
             //$lname = $this->db->pdoQuery('select id,MAX(flight_hours) as max_flight from tbl_license_endorsements where id = '.$license_names[$i].' AND isActive= "y"')->result();
             array_push($hours_array,$lname['max_flight']);
            
        }
        $max_hours = max($hours_array);
        $max_hours1 = ($max_hours != '') ? $max_hours.' '.LBL_JOB_DETAILS_HOURS : '-';
        return $max_hours1;
    }
    public function appliedJobUrl($job_id) {
        $apply_job_url = '';
        $apply_jobs = new Templater(DIR_TMPL . $this->module . "/apply-job-url-nct.tpl.php");
        $apply_jobs_parsed =  $apply_jobs->parse();
        $user_job_count=getTotalRows('tbl_jobs',"id='". $job_id ."' AND user_id = '". $this->current_user_id ."'");
        
        $hide_direct_btn = $hide_apply_btn = '';
        
        $job_app_data = $this->db->select('tbl_job_applications',array('id,applyType'),array('job_id'=>$this->job_id, 'user_id' => $this->current_user_id))->result();
       //print_r($job_app_data);exit;
        if($job_app_data != ''){
            if($job_app_data['applyType'] == 'r'){
                 $hide_apply_btn = '';
            }else{
                 $hide_apply_btn = 'hide';
            }
        }
        
        if($user_job_count == 0) {
            if(getTotalRows('tbl_job_applications', "job_id = '". $job_id ."' AND user_id = '". $this->session_user_id ."'") == 0) {
                $fields_apply_job = array('%JOB_APPLY%','%ENCRYPTED_JOB_ID%',"%HTML%","%HREF%","%TARGET%","%HIDE_CLASS%");
                $apply_flag = getTableValue('tbl_jobs',"apply_flag",array("id"=>$job_id));

                if($apply_flag == 'r') {
                    $fields_replace_apply_job = array(
                        'job_apply',
                        encryptIt($job_id),
                        LBL_APPLY,
                        'javascript:void(0);',
                        '',
                    );
                } else {
                    $fields_replace_apply_job = array(
                        '',
                        encryptIt($job_id),
                        LBL_APPLY_URL,
                        $apply_url,
                        '_blank',
                    );
                }
            } else {
                $fields_apply_job = array(
                    '%JOB_APPLY%',
                    '%ENCRYPTED_JOB_ID%',
                    "%HTML%",
                    "%HREF%",
                    "%TARGET%",
                    "%HIDE_CLASS%"
                );
                $fields_replace_apply_job = array(
                    'remove_from_job_apply',
                    encryptIt($job_id),
                    LBL_WITHDRAW,
                    'javascript:void(0);',
                    '',
                    $hide_apply_btn
                );
            }
           // print_r($fields_replace_apply_job);exit;
            $apply_job_url = str_replace($fields_apply_job, $fields_replace_apply_job, $apply_jobs_parsed);
        }
        return $apply_job_url;
    }

    public function directApplyJobUrl($job_id) {
        $directApplyJob = '';
        $direct_apply = new Templater(DIR_TMPL . $this->module . "/direct-apply-job-nct.tpl.php");
        $direct_apply_parsed =  $direct_apply->parse();
        $user_job_count=getTotalRows('tbl_jobs',"id='". $job_id ."' AND user_id = '". $this->current_user_id ."'");
        
        $hide_direct_btn = $hide_apply_btn = '';
        
        $job_app_data = $this->db->select('tbl_job_applications',array('id,applyType'),array('job_id'=>$this->job_id, 'user_id' => $this->current_user_id))->result();
       // print_r($job_app_data);exit;
        if($job_app_data != ''){
            if($job_app_data['applyType'] == 'd'){
                 $hide_apply_btn = '';
            }else{
                 $hide_apply_btn = 'hide';
            }
        }
        
        if($user_job_count == 0) {
            if(getTotalRows('tbl_job_applications', "job_id = '". $job_id ."' AND user_id = '". $this->session_user_id ."'") == 0) {
                $fields_apply_job = array('%DIRECT_JOB_APPLY%','%ENCRYPTED_JOB_ID%',"%HTML%","%HREF%","%TARGET%","%HIDE_CLASS%");
                $apply_flag = getTableValue('tbl_jobs',"apply_flag",array("id"=>$job_id));
                if($apply_flag == 'r') {
                    $fields_replace_apply_job = array(
                        'direct_job_apply',
                        encryptIt($job_id),
                        LBL_DIRECT_APPLY,
                        'javascript:void(0);',
                        '',
                        '',
                    );
                } else {
                    $fields_replace_apply_job = array(
                        '',
                        encryptIt($job_id),
                       LBL_APPLY_URL,
                        $apply_url,
                        '_blank',
                        '',
                    );
                }
            } else {
                $fields_apply_job = array(
                    '%DIRECT_JOB_APPLY%',
                    '%ENCRYPTED_JOB_ID%',
                    "%HTML%",
                    "%HREF%",
                    "%TARGET%",
                    "%HIDE_CLASS%"
                );
                $fields_replace_apply_job = array(
                    'remove_from_job_apply',
                    encryptIt($job_id),
                    LBL_WITHDRAW,
                    'javascript:void(0);',
                    '',
                    $hide_apply_btn
                );
            }
           //print_r($fields_replace_apply_job);exit;
            $directApplyJob = str_replace($fields_apply_job, $fields_replace_apply_job, $direct_apply_parsed);
        }
        return $directApplyJob;
    }

    public function savedJobUrl($job_id) {
        $save_job_url = '';

        $save_jobs = new Templater(DIR_TMPL . $this->module . "/save-job-url-nct.tpl.php");
        $save_jobs_parsed =  $save_jobs->parse();

        $user_job_count = getTotalRows('tbl_jobs', "id = '". $job_id ."' AND user_id = '". $this->session_user_id ."'");

        if($user_job_count == 0) {
            //job save url
            if(getTotalRows('tbl_saved_jobs', "job_id = '". $job_id ."' AND user_id = '". $this->session_user_id ."'") == 0) {
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

    public function getNoOFApplicants($job_id) {
        return getTotalRows('tbl_job_applications', 'job_id = "'. $job_id .'"');
    }

    public function getSimilarJobsPageContent($response) {
        $main_content = new Templater(DIR_TMPL . $this->module . "/all-similar-jobs-nct.tpl.php");
        $main_content->set('similar_jobs', $response['content']);
        $main_content->set('pagination', $response['pagination']);
        $main_content->set('job_id', $response['job_id']);
        $main_content->set('industry_id', $response['industry_id']);
        $main_content_parsed = $main_content->parse();

        return $main_content_parsed;
    }

    public function getJobApplicantsPageContent($response) {
        $main_content = new Templater(DIR_TMPL . $this->module . "/job-applicants-nct.tpl.php");
        $main_content->set('job_applicants', $response['content']);
        $main_content->set('pagination', $response['pagination']);
        $main_content->set('job_id', $response['job_id']);
        $main_content_parsed = $main_content->parse();

        return $main_content_parsed;
    }

    public function saveJobApplication($job_id) {
        
        $response = array();
        $response['status'] = false;
        $jobDetail = $this->db->pdoQuery('select apply_email,apply_url,apply_flag,relavent_experience_from as jobExperience,company_id from tbl_jobs where id = '.$job_id.'')->result();
        //$userExperience = getUserExperience($this->current_user_id,$jobDetail['company_id']);
       // if($userExperience >= $jobDetail['jobExperience']){
            if($jobDetail['apply_flag'] == 'r'){
                $user_id = $this->current_user_id;
                $alreay_applied = $this->db->count('tbl_job_applications',array('job_id' => $job_id, 'user_id' => $user_id));
                if($alreay_applied == 0){
                    $apply_id = $this->db->insert('tbl_job_applications', array('job_id' => $job_id, 'user_id' => $user_id, 'applied_on' => date('Y-m-d H:i:s')))->getLastInsertId();
                    if($apply_id) {
                        $jobPostedByUserId = getTableValue("tbl_jobs", "user_id", array("id" => $job_id ));
                        $notificationArray = array(
                            "user_id" => $jobPostedByUserId,
                            "type" => "aj",
                            "action_by_user_id" => $user_id,
                            "job_id" => $job_id,
                            "added_on" => date("Y-m-d H:i:s"),
                            "updated_on" => date("Y-m-d H:i:s")
                        );
                        $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                        //For email notification
                        $notificationStatus = getTableValue("tbl_notification_settings", "apply_job", array("user_id" => $jobPostedByUserId));
                        $job_title = getTableValue("tbl_jobs", "job_title", array("id" => $job_id));
                        if($notificationStatus == 'y'){
                            $to_user = getTableValue("tbl_users", "first_name", array("id" => $jobPostedByUserId));
                            $email_address = getTableValue("tbl_users", "email_address", array("id" => $jobPostedByUserId));
                            $job_title_with_url = '<a href="'.get_job_detail_url($job_id).'">'.$job_title.'</a>';

                            //Get apllicants detail
                            $user = $this->db->pdoQuery('select first_name,last_name,email_address from tbl_users where id = '.$user_id.'')->result();
                            $user_name = $user['first_name'].' '.$user['last_name'];
                            $user_name_with_url = '<a href="'.get_user_profile_url($user_id).'">'.$user_name.'</a>';

                            $arrayCont['greetings'] = $to_user;
                            $arrayCont['from_user'] = $user_name_with_url;
                            $arrayCont['job_name'] = $job_title_with_url;
                            $arrayCont['user_name'] = $user_name_with_url;
                            $arrayCont['email'] = $user['email_address'];
                            generateEmailTemplateSendEmail("job_applied", $arrayCont, $jobDetail['apply_email']);
                        }

                        /* Push notification */
                        $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
                        $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                        $push_data = array(
                            'user_name'=>$push_user_name,
                            'job_name'=>$job_title,
                            'notification_id'=>$notification_id,
                            "job_id" => $job_id,
                        );
                        set_notification($jobPostedByUserId,'aj',$push_data);

                        $response['status'] = 'true';
                        $response['recommanded'] = 'y';
                        $response['msg'] = LBL_JOB_APPLIED_SUCCESSFULLY;

                    } else {
                        $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                    }
                } else {
                    $response['msg'] = LBL_ALREADY_APPLIED;
                }
            }else{
                $response['status'] = 'true';
                $response['recommanded'] = 'n';
                $response['url'] = $jobDetail['apply_url'];
                $response['msg'] = LBL_CLICK_TO_APPLY;
            }
        /*}else{
            $response['status'] = 'false';
            $response['msg'] = LBL_RELEVANT_EXPERIENCE;

        }*/

        return $response;

    }
    public function removeJobApplication($job_id) {

        $response = array();
        $response['status'] = false;

        $user_id = $this->session_user_id;
        $affectedRows = $this->db->delete('tbl_job_applications', array('job_id' => $job_id, 'user_id' => $user_id))->affectedRows();

        if($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['msg'] = LBL_JOB_REMOVED_APPLIED_JOBS;
        } else {
            $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }
        return $response;
    }

    public function saveJob($job_id) {
        $response = array();
        $response['status'] = false;
        $user_id = $this->current_user_id;
        $apply_id = $this->db->insert('tbl_saved_jobs', array('job_id' => $job_id, 'user_id' => $user_id, 'added_on' => date('Y-m-d H:i:s')))->getLastInsertId();
        if($apply_id) {
            $response['status'] = true;
            $response['msg'] = LBL_JOB_SAVED;
        } else {
            $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }
        return $response;
    }

    public function removeSavedJob($job_id) {

        $response = array();
        $response['status'] = false;

        $user_id = $this->session_user_id;
        $affectedRows = $this->db->delete('tbl_saved_jobs', array('job_id' => $job_id, 'user_id' => $user_id))->affectedRows();

        if($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['msg'] = LBL_JOB_REMOVED;
        } else {
            $response['msg'] =ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }

        return $response;

    }
    public function shareNewsFeed($job_id) {

        $response = array();
        $response['status'] = false;

        $user_id = $this->current_user_id;

        $val_array = array(
            'user_id'           => $user_id,
            'shared_with'       => 'p',
            'type'              => 'j',
            'shared_job_id'     => $job_id,
            'posted_or_shared'  => 's',
            'status'            => 'p',
            'added_on'          => date('Y-m-d H:i:s'),
            'updated_on'        => date('Y-m-d H:i:s'),
        );

        $feed_id = $this->db->insert('tbl_feeds', $val_array)->getLastInsertId();

        if($feed_id) {
            $response['status'] = true;
            $response['msg'] = LBL_SHARED_SUCCESSFULLY;
        } else {
            $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }

        return $response;

    }
    public function getCompanyDD() {
        $final_result = NULL;

        $companies = $this->db->select("tbl_companies", array('id,company_name'), array("status" => "a", 'company_type' => 'r', 'user_id'=>$this->session_user_id))->results();
        if ($companies) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($companies); $i++) {
                $fields_replace = array(
                    encryptIt(filtering($companies[$i]['id'], 'input', 'int')),
                    '',
                    filtering($companies[$i]['company_name'], 'input', 'int')
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;
    }
    public function getCategoriesDD() {
        $final_result = NULL;

        $job_category = $this->db->select("tbl_job_category", array('id,job_category_'.$this->lId.' as job_category'), array("status" => "a"))->results();
        if ($job_category) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($job_category); $i++) {
                $job_category_title = filtering($job_category[$i]['job_category'], 'input', 'int');
                $fields_replace = array(
                    filtering($job_category[$i]['id'], 'input', 'int'),
                    '',
                    $job_category_title
                );
                if($this->platform=='app'){
                    $final_result[] = array('id'=>$job_category[$i]['id'],'category'=>$job_category_title);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
                }

            }
        }

        return $final_result;
    }

    public function saveDirectJobApplication($job_id,$files = array()) {
        //print_r($files);exit;
        $response = array();
        $response['status'] = false;
        $jobDetail = $this->db->pdoQuery('select apply_email,apply_url,apply_flag,relavent_experience_from as jobExperience,company_id from tbl_jobs where id = '.$job_id.'')->result();
        //$userExperience = getUserExperience($this->current_user_id,$jobDetail['company_id']);

        if($files['user_resume'] != ''){
            $allowed= array('pdf');
            $ext = substr(strrchr($_FILES['user_resume']['name'], '.'), 1);
            $upload_dir=DIR_UPD."user_resume/".$job_id."/";
            
            if(!in_array($ext,$allowed) ) {
				$response['status'] = 'false';
                $response['url'] = SITE_URL.'job/'.$job_id;
                $response['msg'] = PLEASE_SELECT_PROPER_FILE_TYPE_FOR_RESUME;
			}else{
			    if($upload_dir != ''){
    				if(!is_dir($upload_dir)){
    					mkdir($upload_dir,0777);
    				}
    			}
                $uploadReceipt = $_FILES['user_resume']['name'];
                $filename = "";
                if(isset($uploadReceipt) && $uploadReceipt != ""){
                    $allowed= array('pdf'); ;
                    $uploadDirectory = $upload_dir;     
                    $ext = substr(strrchr($uploadReceipt, '.'), 1);
    
                    $tempPath = $_FILES['user_resume']['tmp_name'];
                    $filename=time().time().".".$ext;
    
                    if(!in_array($ext,$allowed) ){
                        $response['status'] = 'false';
                        $response['url'] = SITE_URL.'job/'.$job_id;
                        $response['msg'] = MESSAGE_DIRECT_APPLY_FILE_TYPE_ERROR;
                    }
                    else
                    {
                        move_uploaded_file($tempPath,$uploadDirectory.$filename);
                        // exit;    
                        if($jobDetail['apply_flag'] == 'r'){
                            $user_id = $this->current_user_id;
                            $alreay_applied = $this->db->count('tbl_job_applications',array('job_id' => $job_id, 'user_id' => $user_id));
                            if($alreay_applied == 0){
                                $apply_id = $this->db->insert('tbl_job_applications', array(
                                    'job_id'           => $job_id,
                                    'user_id'          => $user_id,
                                    'applyType'        => 'd',
                                    'applied_on'       => date('Y-m-d H:i:s'),
                                    'resume_file_name' => $filename,
                                    'isActive'         => 'y'))->getLastInsertId();
                                if($apply_id > 0) {
                                    $jobPostedByUserId = getTableValue("tbl_jobs", "user_id", array("id" => $job_id ));
                                    
                                    $notificationArray = array(
                                        "user_id" => $jobPostedByUserId,
                                        "type" => "aj",
                                        "action_by_user_id" => $user_id,
                                        "job_id" => $job_id,
                                        "added_on" => date("Y-m-d H:i:s"),
                                        "updated_on" => date("Y-m-d H:i:s")
                                    );
                                    $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();
    
                                    //For email notification
                                    $notificationStatus = getTableValue("tbl_notification_settings", "apply_job", array("user_id" => $jobPostedByUserId));
                                    $job_title = getTableValue("tbl_jobs", "job_title", array("id" => $job_id));
                                    if($notificationStatus == 'y'){
                                        $to_user = getTableValue("tbl_users", "first_name", array("id" => $jobPostedByUserId));
                                        $email_address = getTableValue("tbl_users", "email_address", array("id" => $jobPostedByUserId));
                                        $job_title_with_url = '<a href="'.get_job_detail_url($job_id).'">'.$job_title.'</a>';
    
                                        //Get apllicants detail
                                        $user = $this->db->pdoQuery('select first_name,last_name,email_address from tbl_users where id = '.$user_id.'')->result();
                                        $user_name = $user['first_name'].' '.$user['last_name'];
                                        $user_name_with_url = '<a href="'.get_user_profile_url($user_id).'">'.$user_name.'</a>';
    
                                        $arrayCont['greetings'] = $to_user;
                                        $arrayCont['from_user'] = $user_name_with_url;
                                        $arrayCont['job_name'] = $job_title_with_url;
                                        $arrayCont['user_name'] = $user_name_with_url;
                                        $arrayCont['email'] = $user['email_address'];
                                        generateEmailTemplateSendEmail("job_applied", $arrayCont, $jobDetail['apply_email']);
                                    }
                                    /* Push notification */
                                    $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
                                    $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                                    $push_data = array(
                                        'user_name'=>$push_user_name,
                                        'job_name'=>$job_title,
                                        'notification_id'=>$notification_id,
                                        "job_id" => $job_id,
                                    );
                                    set_notification($jobPostedByUserId,'aj',$push_data);
    
                                    $response['status'] = 'true';
                                    $response['recommanded'] = 'y';
                                    $response['url'] = SITE_URL.'job/'.$job_id;
                                    $response['msg'] = LBL_JOB_APPLIED_SUCCESSFULLY;
    
                                }else{
                                    $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                                    $response['url'] = SITE_URL.'job/'.$job_id;
                                }
                            } else{
                                $response['msg'] = LBL_ALREADY_APPLIED;
                                $response['url'] = SITE_URL.'job/'.$job_id;
                            }
                        }else{
                            $response['status'] = 'true';
                            $response['recommanded'] = 'n';
                            $response['url'] = SITE_URL.'job/'.$job_id;
                            $response['msg'] = LBL_CLICK_TO_APPLY;
                        }
                    }
                }else{
                    $response['status'] = 'false';
                    $response['url'] = SITE_URL.'job/'.$job_id;
                    $response['msg'] = MESSAGE_DIRECT_APPLY_NO_SELECTED_FILE;
                }   
			} 
        }else{
            $response['status'] = 'false';
            $response['url'] = SITE_URL.'job/'.$job_id;
            $response['msg'] = MESSAGE_DIRECT_APPLY_NO_SELECTED_FILE;
        }
        return $response;
    }
}
?>