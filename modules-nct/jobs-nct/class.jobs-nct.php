<?php class Jobs extends Home {
    function __construct($current_user_id = 0,$platform='web') {
        parent::__construct();
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->platform = $platform;
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
        
        
    }

    public function getJobsPageContent($type) {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        if(isset($_GET['page']) && $_GET['page'] != "" && $_GET['page'] > 1) {
            $page = filtering($_GET['page'], 'input', 'int');
        } else {
            $page = 1;
        }
        
        $response = $this->getJobs($this->session_user_id, $type, $page);
        $content = $response['content'];
        $pagination = $response['pagination'];
        $main_content->set('content', $content);
        $main_content->set('pagination', $pagination);
        $main_content->set('subscribed_membership_plan_details', $this->getSubscribedMembershipPlan($this->session_user_id));
        $main_content_parsed = $main_content->parse();
        $fields = array("%MY_JOBS_ACTIVE_CLASS%","%APPLIED_JOBS_ACTIVE_CLASS%","%SAVED_JOBS_ACTIVE_CLASS%","%COMPANY_NAME_OPTIONS%","%CATEGORY_OPTIONS%","%COMPANY_COUNT%","%COMPANY_COUNT_EDIT%","%LICENSES_ENDORSEMENTS_OPTIONS%");
        $my_jobs_active_class = $applied_jobs_active_class = $saved_jobs_active_class = '';
        if ('my_jobs' == $type) {
            $my_jobs_active_class = "active";
        } else if ('saved_jobs' == $type) {
            $saved_jobs_active_class = "active";
        } else if ('applied_jobs' == $type) {
            $applied_jobs_active_class = "active";
        }
        $company_count=$this->db->pdoQuery("SELECT count('c.id') as count FROM tbl_companies c  WHERE c.user_id = ? AND c.company_type= ? AND c.status=? ",array($this->session_user_id,'r','a'))->result();
        $company_count=$company_count['count'];
        $company_count_edit=$this->db->pdoQuery("SELECT count('c.id') as count FROM tbl_companies c right JOIN tbl_company_locations cl on c.id = cl.company_id WHERE c.user_id = ? AND c.company_type= ? AND c.status=? ",array($this->session_user_id,'r','a'))->result();
        $company_count_edit=$company_count_edit['count'];
        
        $fields_replace = array($my_jobs_active_class,$applied_jobs_active_class,$saved_jobs_active_class,$this->getCompanyDD(),$this->getCategoriesDD(),$company_count,$company_count_edit,$this->getLicensesEndorsements());
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
    
    public function getJobs($user_id, $type, $currentPage = 1, $getPagination = true) {
        $final_result_array = array();
        $final_result_html = $jobs_html = NULL;
        $totalRows = $showableRows = 0;
        $limit = NO_OF_JOBS_PER_PAGE;
        $currentPage = (int)filtering($currentPage, 'input', 'int');

        require_once('storage.php');
        $job_job_storage = new storage();
        
        $offset = ($currentPage - 1 ) * $limit;
        $manage_btn_html = '';
        $delete_btn_html = '';
        if ('my_jobs' == $type) {

            $data_selection_query = "SELECT jobs.status,jobs.id,jobs.last_date_of_application,jobs.company_id,jobs.is_featured,jobs.featured_till,jobs.job_title,comp.company_logo,comp.company_name, i.industry_name_".$this->lId." as industry_name, jcate.job_category_".$this->lId." as job_category, l.country,l.state,l.city1,l.city2  ";

            $count_selection_query = "SELECT count(jobs.id) as no_of_jobs";

            $query = " FROM tbl_jobs jobs 
                        LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id 
                        LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id
                        LEFT JOIN tbl_job_category jcate ON jobs.job_category_id = jcate.id
                        LEFT JOIN tbl_locations l ON jobs.location_id = l.id 
                        WHERE jobs.user_id = ? ORDER BY jobs.id DESC ";

            $where_arr=array($user_id);


        } else if ('saved_jobs' == $type) {

            $data_selection_query = "SELECT jobs.id,jobs.last_date_of_application,jobs.company_id,jobs.is_featured,jobs.featured_till,jobs.job_title,comp.company_logo,comp.company_name, i.industry_name_".$this->lId." as industry_name, jcate.job_category_".$this->lId." as job_category, l.country,l.state,l.city1,l.city2 ";

            $count_selection_query = "SELECT count(job_saved.id) as no_of_jobs ";

            $query = " FROM tbl_saved_jobs job_saved 
                        LEFT JOIN tbl_jobs jobs ON jobs.id = job_saved.job_id 
                        LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id 
                        LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id
                        LEFT JOIN tbl_job_category jcate ON jobs.job_category_id = jcate.id
                        LEFT JOIN tbl_locations l ON jobs.location_id = l.id 
                        WHERE job_saved.user_id = ? AND jobs.status = ?  ORDER BY job_saved.id DESC ";
            $where_arr=array($user_id,'a');

        } else {
            $data_selection_query = "SELECT jobs.id,jobs.last_date_of_application,jobs.company_id,jobs.is_featured,jobs.featured_till,jobs.job_title,comp.company_logo,comp.company_name, i.industry_name_".$this->lId." as industry_name, jcate.job_category_".$this->lId." as job_category, l.country,l.state,l.city1,l.city2 ";
            $count_selection_query = "SELECT count(job_app.id) as no_of_jobs ";
            $query = " FROM tbl_job_applications job_app 
                        LEFT JOIN tbl_jobs jobs ON jobs.id = job_app.job_id 
                        LEFT JOIN tbl_companies comp ON jobs.company_id = comp.id 
                        LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id
                        LEFT JOIN tbl_job_category jcate ON jobs.job_category_id = jcate.id
                        LEFT JOIN tbl_locations l ON jobs.location_id = l.id 
                        WHERE job_app.user_id  = ? AND jobs.status = ?   ORDER BY job_app.id DESC ";
            $where_arr=array($user_id,'a');

        }

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;
        $getAllResults = $this->db->pdoQuery($count_selection_query . $query,$where_arr)->result();
        $totalRows = $getAllResults['no_of_jobs'];
        $getShowableResults = $this->db->pdoQuery($data_selection_query . $query . $limit_query,$where_arr)->results();
        if ($getShowableResults) {
            $showableRows = count($getShowableResults);
            $jobs_ul_tpl = new Templater(DIR_TMPL . $this->module . "/jobs-ul-nct.tpl.php");
            $single_jobs_li_tpl = new Templater(DIR_TMPL . $this->module . "/single-jobs-li-nct.tpl.php");
            $single_jobs_li_tpl_parsed = $single_jobs_li_tpl->parse();
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
                "%MANAGE_BTN_HTML%",
                "%DELETE_BTN_HTML%",
                "%APPLICANT_COUNT%",
                "%FEATURED%",
                "%EXPIRED%",
                "%HIDE_EXP%",
                "%URL_EDIT_JOB%",
                "%HIDE_DEACTIVE%",
                "%HIDE_MANAGE%"

            );
           // echo "<pre>";print_r($getShowableResults);exit;
            for ($i = 0; $i < count($getShowableResults); $i++) {
                // $qrySelSkills = $this->db->pdoQuery("SELECT skills.skill_name_".$this->lId." as skill_name FROM tbl_job_skills jskills 
                // LEFT JOIN tbl_skills skills ON skills.id = jskills.skill_id WHERE jskills.job_id = ? ",array(filtering($getShowableResults[$i]['id'], 'output', 'int')))->results();
                // $skills=array();
                // $skill_name='';
                // if($qrySelSkills) {
                //     foreach ($qrySelSkills as $key => $value) {
                //         $skills[] = $value['skill_name'];
                //     }
                //     $skill_name = implode(", ", $skills);
                // } else {
                //     //$skill_name = '-';
                //     $skill_name = '';
                // }
                $city = $getShowableResults[$i]['city1'] != '' ? $getShowableResults[$i]['city1'] : $getShowableResults[$i]['city2'];
                $state = $getShowableResults[$i]['state'];
                $country = $getShowableResults[$i]['country'];
                //$location = $city . ", " . $state . ", " . $country;

                $location = $city;
                $location .= (($location != '' && $state != '') ?', ':'').$state;
                $location .= (($location != '' && $country != '') ?', ':'').$country;

                // $company_logo_url = getImageURL("company_logo", filtering($getShowableResults[$i]['company_id'], 'output', 'int'), "th2",$this->platform);
                // if($this->platform == 'web'){

                // $company_logo_url = ($company_logo_url == '') ? '<span class="company-letter-square company-letter">'.ucfirst($getShowableResults[$i]['company_name'][0]).'</span>' : $company_logo_url;
                // }

                $company_logo_image_name = getTableValue('tbl_companies','company_logo',array('id'=>$getShowableResults[$i]['company_id']));
                $company_logo_url = $job_job_storage->getImageUrl1('av8db','th2_'.$company_logo_image_name,'company-logos-nct/');
                $is_image = getimagesize($company_logo_url);
                if(!empty($is_image)){
                    $company_logo_url = '<img src="'.$company_logo_url.'" alt="'.$getShowableResults[$i]['company_name'][0].'">';
                }else{
                    $company_logo_url = '<span class="profile-picture-character">'.ucfirst($getShowableResults[$i]['company_name'][0]).'</span>';
                }

                $job_url = get_job_detail_url(filtering($getShowableResults[$i]['id'], 'output', 'int'));
                $manage_btn_html = $this->getManageJobURL($getShowableResults[$i]['id'], $type);
                $hide_exp=$hide_deactive='hidden';
                $make_featured_btn='n';
                if($type=="my_jobs" && ($getShowableResults[$i]['last_date_of_application'] > date('Y-m-d H:i:s') || $getShowableResults[$i]['last_date_of_application'] == date('Y-m-d')) && $getShowableResults[$i]['is_featured'] == 'n' /*&& $getShowableResults[$i]['featured_till'] < date('Y-m-d H:i:s') && $getShowableResults[$i]['featured_till'] != date('Y-m-d')*/ ){
                   
                    $hide_exp='';
                    $make_featured_btn='y';
    

                }
                $hide_manege='';
                if($type=='my_jobs' && $getShowableResults[$i]['status']=='d'){
                    $hide_deactive='';
                    $hide_manege='hidden';
                }
                if($type == "my_jobs") {
                    $delete_btn_html = $this->getManageJobURL($getShowableResults[$i]['id'], $type, "delete");
                }
                $featured =$expired= '';
                $is_featured=$is_expired='n';
                if($getShowableResults[$i]['is_featured'] == 'y' && $getShowableResults[$i]['featured_till'] >= date('Y-m-d H:i:s') && ($getShowableResults[$i]['last_date_of_application'] > date('Y-m-d H:i:s') || $getShowableResults[$i]['last_date_of_application'] == date('Y-m-d'))){
                    $featured_tpl = new Templater(DIR_TMPL . $this->module . "/featured.tpl.php");
                    $featured = $featured_tpl->parse();
                    $is_featured='y';
                }else if($getShowableResults[$i]['last_date_of_application'] < date('Y-m-d H:i:s') && $getShowableResults[$i]['last_date_of_application'] != date('Y-m-d') && $getShowableResults[$i]['status']=='a'){
                    $featured_tpl = new Templater(DIR_TMPL . $this->module . "/expired.tpl.php");
                    $expired = $featured_tpl->parse();
                    $is_expired='y';
                }

                $getApplicant = getTableValue("tbl_job_applications","count(id)",array("job_id"=>$getShowableResults[$i]['id']));
                $job_title = filtering($getShowableResults[$i]['job_title'], 'output');
                $job_category = filtering($getShowableResults[$i]['job_category'], 'output');
                $industry_name = filtering($getShowableResults[$i]['industry_name'], 'output');
                $location = filtering($location, 'output');
                $company_name = filtering($getShowableResults[$i]['company_name'], 'output');
                $applicant_count = filtering($getShowableResults[$i]['applicant_count'], 'output');
                $url_edit_job=SITE_URL . "edit-job-form/" . encryptIt($getShowableResults[$i]['id']);
                $fields_replace = array(
                    filtering($getShowableResults[$i]['id'], 'output', 'int'),
                    ucwords($job_title),
                    ucwords($job_category),
                    ucwords($industry_name),
                    filtering($skill_name, 'output'),
                    $location,
                    $company_logo_url,
                    ucwords($company_name),
                    filtering($job_url, 'output'),
                    $manage_btn_html,
                    $delete_btn_html,
                    $getApplicant,
                    $featured,
                    $expired,
                    $hide_exp,
                    $url_edit_job,
                    $hide_deactive,
                    $hide_manege
                );
                if($this->platform == 'app'){
                    $job_id = $getShowableResults[$i]['id'];
                    $company_id = $getShowableResults[$i]['company_id'];
                    //$is_featured = $getShowableResults[$i]['is_featured'];
                    $app_jobs[] = array('job_id'=>$job_id,'job_title'=>$job_title,'company_id'=>$company_id,'company_name'=>$company_name,'company_logo'=>$company_logo_url,'job_category'=>$job_category,'industry_name'=>$industry_name,'location'=>$location,'is_featured'=>$is_featured,'job_status'=>$getShowableResults[$i]['status'],'make_featured_btn'=>$make_featured_btn,'is_expired'=>$is_expired);
                } else {
                    $jobs_html .= str_replace($fields, $fields_replace, $single_jobs_li_tpl_parsed);
                }
            }
            $page_data = getPagerData($totalRows, NO_OF_JOBS_PER_PAGE,$currentPage);

            if ($page_data->numPages > 0 && $page_data->numPages > $currentPage ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . "/load-more-new-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getJobs_load/currentPage/" . ($currentPage + 1)."/".$type;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $jobs_html .= $load_more_li_tpl->parse();
            }
            if($this->platform == 'app'){
                $final_result_array['jobs'] = (!empty($app_jobs)?$app_jobs:array());
                $page_data = getPagerData($totalRows, NO_OF_JOBS_PER_PAGE,$currentPage);
                $final_result_array['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
            } else {
                $jobs_ul_tpl->set('jobs', $jobs_html);
                $final_result_html = $jobs_ul_tpl->parse();
                $final_result_array['content'] = $final_result_html;
                $final_result_array['pagination'] = getPagination($totalRows, $showableRows, NO_OF_JOBS_PER_PAGE, $currentPage);
            }
        } else {
            if($this->platform == 'app'){
                $final_result_array['jobs'] = (!empty($app_jobs)?$app_jobs:array());
                $page_data = getPagerData($totalRows, NO_OF_JOBS_PER_PAGE,$currentPage);
                $final_result_array['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
            } else {
                if ($totalRows > 0 && $currentPage > 1) {
                    $final_result_array = $this->getJobs($user_id, $type, ( $currentPage - 1));
                } else {
                    $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                    $message = LBL_HAVNT_APPLIED_JOB;
                    $no_result_found_tpl->set('class', '');

                    if ('my_jobs' == $type) {
                        $message = LBL_HAVENT_CREATED_JOB;
                        $no_result_found_tpl->set('class', 'hidden');

                    } 
                    if ('saved_jobs' == $type) {
                        $message = LBL_HAVENT_SAVED_JOBS;
                        $no_result_found_tpl->set('class', '');

                    } 
                    $no_result_found_tpl->set('message', $message);
                    $final_result_html = $no_result_found_tpl->parse();
                    $final_result_array['content'] = $final_result_html;
                    $final_result_array['pagination'] = "";
                }
            }
        }
        return $final_result_array;
    }
    public function getManageJobURL($job_id, $type, $id="manage") {
        $content = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/manage-job-url-nct.tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array("%HREF%", "%TITLE%", "%ID%", "%DATA-ID%", "%TEXT%");
        if($id == "manage") {
            if($type == 'saved_jobs') {
                $fields_replace = array("javascript:void(0)", LBL_REMOVE, "removeSavedJobs", encryptIt($job_id), LBL_REMOVE);
            } else if($type == 'my_jobs') {
                $getdate = getTableValue("tbl_jobs","last_date_of_application",array("id"=>$job_id));
                $lbl=LBL_MYC_MANAGE;
                if($getdate < date('Y-m-d H:i:s') && $getdate != date('Y-m-d')){
                    $lbl=LBL_REPOST_JOB;
                }

                $fields_replace = array(SITE_URL . "edit-job-form/" . encryptIt($job_id) ,$lbl, "", "", LBL_MYC_MANAGE);
            } else if($type == 'applied_jobs') {
                $fields_replace = array("javascript:void(0)", LBL_WITHDRAW, "withdrawAppliedJobs", encryptIt($job_id), LBL_WITHDRAW);
            } else {
                $fields_replace = array("", "", "", "", "");
            }    
        } else {
            $fields_replace = array("javascript:void(0)", LBL_DELETE, "deleteJob", encryptIt($job_id), LBL_DELETE);
        }
        $content = str_replace($fields, $fields_replace, $main_content_parsed);
        return $content;
    }
    public function removeJobs($user_id, $job_id) {
        $response = array();
        $affectedRows = $this->db->delete("tbl_saved_jobs", array("job_id" => $job_id, "user_id" => $user_id))->affectedRows();
        if($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['msg'] = LBL_REMOVED_JOB_LIST;
        } else {
            $response['status'] = false;
            $response['msg'] = ERROR_SOME_ISSUE_TRY_LATER;
        }
        return $response;
    }
    public function withdrawJobs($user_id, $job_id) {
        $response = array();
        $affectedRows = $this->db->delete("tbl_job_applications", array("job_id" => $job_id, "user_id" => $user_id))->affectedRows();
        if($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['msg'] =LBL_WITHDRAW_FROM_JOB_LIST;
        } else {
            $response['status'] = false;
            $response['msg'] = ERROR_SOME_ISSUE_TRY_LATER;
        }
        return $response;
    }
    public function deleteJob($user_id, $job_id) {
        $response = array();
        $this->db->delete('tbl_notifications',array('job_id'=>$job_id))->affectedRows();
        $affectedRows=$this->db->delete("tbl_jobs", array("id" => $job_id, "user_id" => $user_id))->affectedRows();
        if($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['msg'] = JOB_DELETED;
        } else {
            $response['status'] = false;
            $response['msg'] = ERROR_SOME_ISSUE_TRY_LATER;
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
                    stripcslashes(filtering($companies[$i]['company_name']))
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
    public function getLicensesEndorsements() {
        $final_result = NULL;

        $licenses_endorsements = $this->db->select("tbl_license_endorsements", array('id','licenses_endorsements_name_'.$this->lId), array("isActive" => "y"))->results();
        if ($licenses_endorsements != '') {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($licenses_endorsements); $i++) {
                
                $fields_replace = array(
                    filtering($licenses_endorsements[$i]['id'], 'input', 'int'),
                    '',
                    filtering($licenses_endorsements[$i]['licenses_endorsements_name_'.$this->lId], 'input', 'int')
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;
    }
} ?>