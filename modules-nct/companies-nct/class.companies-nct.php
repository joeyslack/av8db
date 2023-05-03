<?php
class Companies extends Home {
    function __construct() {
        parent::__construct();
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
    }
    public function unfollowCompanies($user_id, $company_id, $page,$platform='web') {
        $response = array();
        $response['status'] = false;
        $checkIfFollowing=$this->db->count("tbl_company_followers",  array("company_id" => $company_id, "user_id" => $user_id));
        if ($checkIfFollowing>0) {
            $affectedRows = $this->db->delete("tbl_company_followers", array("company_id" => $company_id, "user_id" => $user_id))->affectedRows();
            if ($affectedRows > 0) {
                $result = $this->getCompanies($user_id, 'following_companies', $page);
                $response['status'] = true;
                $response['success'] = SUCCESS_YOU_HAVE_SUCCESSFULLY_UNFOLLOWED_THE_COMPANY;
                $response['follow_count'] = $this->getCompanyFollowers($company_id);
                if($platform=='web'){
                    $response['content'] = $result['content'];
                    $response['pagination'] = $result['pagination'];
                }
            } else {
                $response['error'] = ERROR_THER_SEEMS_TO_BE_SOME_ISSUE_WHILE_UNFOLLOWING_YOU_FROM_THE_REQUESTED_COMPANY;
            }
        } else {
            $response['error'] = ERROR_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
        }
        return $response;
    }
    public function getCompanies($user_id, $type, $currentPage = 1, $getPagination = true,$platform = 'web')
    {
        $company_logo_url = '';
        $array = array();
        $final_result_array = array();
        $final_result_html = $companies_html = NULL;
        $totalRows = $showableRows = 0;
        $limit = NO_OF_COMPANIES_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;
        if ('my_companies' == $type) {
            $data_selection_query = "SELECT comp.company_description,comp.id,comp.company_logo,comp.company_name,comp.website_of_company,comp.owner_email_address, i.industry_name_".$this->lId." as industry_name ";
            $count_selection_query = "SELECT count(comp.id) as no_of_companies ";

            $query = " FROM tbl_companies comp LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id WHERE comp.user_id = ? AND comp.status = ? AND comp.company_type = ? AND comp.isAdminVerify = ? AND comp.isCompanyEmailVerify = ? ORDER BY comp.id DESC ";
            $where_arr=array($user_id,'a','r','y','y');
        } else {
            $data_selection_query = "SELECT comp.company_description,comp.id,comp.company_logo,comp.company_name,comp.website_of_company,comp.owner_email_address, i.industry_name_".$this->lId." as industry_name ";
            $count_selection_query = "SELECT count(comp.id) as no_of_companies ";
            $query = " FROM tbl_company_followers cf LEFT JOIN tbl_companies comp ON comp.id = cf.company_id LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id WHERE cf.user_id = ? AND comp.status = ? AND comp.company_type = ? AND comp.isAdminVerify = ? AND comp.isCompanyEmailVerify = ? ORDER BY comp.id DESC ";
            $where_arr=array($user_id,'a','r','y','y');
        }
       // print_r($query);exit();
        if($getPagination == true)
            $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;
        $getAllResults = $this->db->pdoQuery($count_selection_query . $query,$where_arr)->result();
        $totalRows = $getAllResults['no_of_companies'];
        $getShowableResults = $this->db->pdoQuery($data_selection_query . $query . $limit_query,$where_arr)->results();

        if ($getShowableResults) {
            $showableRows = count($getShowableResults);
            $companies_ul_tpl = new Templater(DIR_TMPL . $this->module . "/companies-ul-nct.tpl.php");
            $single_company_li_tpl = new Templater(DIR_TMPL . $this->module . "/single-company-li-nct.tpl.php");
            if ('my_companies' == $type) {
                $edit_company_tpl = new Templater(DIR_TMPL . $this->module . "/edit-company-nct.tpl.php");
                $company_actions = $edit_company_tpl->parse();
            } else {
                $unfollow_company_tpl = new Templater(DIR_TMPL . $this->module . "/unfollow-company-nct.tpl.php");
                $company_actions = $unfollow_company_tpl->parse();
            }
            $single_company_li_tpl->set('company_actions', $company_actions);
            $single_company_li_tpl_parsed = $single_company_li_tpl->parse();
            $fields = array(
                "%COMPANY_ID_ENCRYPTED%",
                "%COMPANY_NAME%",
                "%COMPANY_PAGE_URL%",
                "%COMPANY_LOGO_URL%",
                "%COMPANY_INDUSTRY%",
                "%WEBSITE_OF_COMPANY%",
                "%OWNER_EMAIL_ADDRESS%",
                "%RANGE_OF_NO_OF_EMPLOYEES%",
                "%EDIT_COMPANY_URL%",
                "%COMPANY_DESCRIPTION%",
                "%HIDE_DESC%",
                "%COMPANY_ID%",
                "%COMPANY_RATING_TOTAL%"
            );
           // echo "<pre>";print_r($getShowableResults);exit;
            require_once(DIR_MOD . 'common_storage.php');
            $get_company_storage = new storage();
            $src2 = DIR_NAME_COMPANY_LOGOS."/";

            for ($i = 0; $i < count($getShowableResults); $i++) {
                $company_id = filtering($getShowableResults[$i]['id'], 'output', 'int');
                $company_page_url = get_company_detail_url($company_id);
                
                $company_logo_name = getTableValue("tbl_companies", "company_logo", array("id" => $getShowableResults[$i]['id']));
                $company_name = getTableValue("tbl_companies", "company_name", array("id" => $getShowableResults[$i]['id']));

                $src = $get_company_storage->getImageUrl1('av8db','th1_'.$company_logo_name,$src2);
                $ck = getimagesize($src);
                if (!empty($ck)) {
                    $company_logo_url = '<picture>
                                <source srcset="' . $src . '" type="image/jpg">
                                <img src="' . $src . '" class="" alt="img" /> 
                            </picture>';    
                }else{
                    $company_logo_url = '<span title="' . $company_name. '" class="profile-picture-character">' . ucfirst($company_name[0]) . '</span>';
                }

                /*$company_logo_url=getImageURL("company_logo", filtering($getShowableResults[$i]['id'], 'output', 'int'), "th1");
                if($platform == 'web'){
                    $company_logo_url = ($company_logo_url == '') ? '<span class="company-letter-square company-letter">'.ucfirst($getShowableResults[$i]['company_name'][0]).'</span>' : $company_logo_url;
                }*/
                //print_r($company_logo_url);exit();
                $edit_company_url = SITE_URL . "edit-company/" . encryptIt($company_id);
                $company_name = filtering($getShowableResults[$i]['company_name']);
                $industry_name = filtering($getShowableResults[$i]['industry_name']);
                $website_of_company = filtering($getShowableResults[$i]['website_of_company']);
                $owner_email_address = filtering($getShowableResults[$i]['owner_email_address']);
                $range_of_no_of_employees = isset($getShowableResults[$i]['range_of_no_of_employees']) ? $getShowableResults[$i]['range_of_no_of_employees'] : '';
                $company_description = filtering($getShowableResults[$i]['company_description']);
                $company_description = (strlen($company_description)>250) ? substr($company_description, 0, 250).'...' : substr($company_description, 0, 250);
                
                $hide_desc='';
                if($company_description == '' &&  $website_of_company == ''){
                    $hide_desc='hidden';
                }

                $fields_replace = array(
                    encryptIt($company_id),
                    ucwords($company_name),
                    $company_page_url,
                    $company_logo_url,
                    ucwords($industry_name),
                    $website_of_company,
                    $owner_email_address,
                    $range_of_no_of_employees,
                    $edit_company_url,
                    $company_description,
                    $hide_desc,
                    $company_id,
                    getRatingCount($company_id)
                );
                //print_r($fields_replace);exit();
                if($platform == 'app'){
                    $array[] = array('company_id'=>$company_id,'company_name'=>$company_name,'website_of_company'=>$website_of_company,'company_logo_url'=>$company_logo_url,'industry_name'=>$industry_name,'owner_email_address'=>$owner_email_address,'range_of_no_of_employees'=>$range_of_no_of_employees,'description'=>$company_description);
                } else {
                    $companies_html .= str_replace($fields, $fields_replace, $single_company_li_tpl_parsed);
                }
            }
            $page_data = getPagerData($totalRows, NO_OF_COMPANIES_PER_PAGE,$currentPage);

            if ($page_data->numPages > 0 && $page_data->numPages > $currentPage ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . "/load-more-new-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getCompanies_load/currentPage/" . ($currentPage + 1)."/".$type;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $companies_html .= $load_more_li_tpl->parse();
            }

            $companies_ul_tpl->set('companies', $companies_html);
            $final_result_html = $companies_ul_tpl->parse();
            $final_result_array['content'] = $final_result_html;
            $final_result_array['pagination']= getPagination($totalRows, $showableRows, NO_OF_COMPANIES_PER_PAGE, $currentPage);
            if($platform == 'app'){
                $app_array['companies'] = $array;
                $page_data = getPagerData($totalRows, NO_OF_COMPANIES_PER_PAGE,$currentPage);
                $app_array['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
            }

        } else {
            if ($totalRows > 0 && $currentPage > 1) {
                
                $final_result_array = $this->getCompanies($user_id, $type, ( $currentPage - 1));
            } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");
                if ('my_companies' == $type) {
                    $message = ERROR_YOU_HAVE_NOT_ADDED_ANY_COMPANY_YET;
                    $no_result_found_tpl->set('class', 'hidden');

                } else {
                    $message = SUCCESS_YOU_ARE_NOT_FOLLOWING_ANY_COMPANY;
                    $no_result_found_tpl->set('class', '');

                }
                $no_result_found_tpl->set('message', $message);
                $final_result_html = $no_result_found_tpl->parse();
                $final_result_array['content'] = $final_result_html;
                $final_result_array['pagination'] = "";
            }
        }
        if($platform == 'app'){
            return $app_array;
        } else {
            return $final_result_array;
        }
    }
    public function getCompaniesPageContent($type) {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        if(isset($_GET['page']) && $_GET['page'] != "" && $_GET['page'] > 1) {
            $page = filtering($_GET['page'], 'input', 'int');
        } else {
            $page = 1;
        }
        $response = $this->getCompanies($this->session_user_id, $type, $page);
        //print_r($response);exit();
        $content = $response['content'];
        $pagination = $response['pagination'];
        $main_content->set('content', $content);
        $main_content->set('pagination', $pagination);
        $main_content->set('subscribed_membership_plan_details', $this->getSubscribedMembershipPlan($this->session_user_id));
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%MY_COMPANIES_ACTIVE_CLASS%",
            "%FOLLOWING_COMPANIES_ACTIVE_CLASS%",
            "%COMPANY_INDUSTRY_OPTIONS%",
            //"%COMPANY_SIZE_OPTIONS%",
            "%COMPANY_CLOSEST_AIRPORT%",
            //"%GET_COMPANY_RATE_COUNT%"
        );
        $my_companies_active_class = $following_companies_active_class = '';
        if ('my_companies' == $type) {
            $my_companies_active_class = "active";
        } else {
            $following_companies_active_class = "active";
        }
        $fields_replace = array(
            $my_companies_active_class,
            $following_companies_active_class,
            $this->getIndustriesDD(),
            //$this->getCompnaySizesDD(),
            $this->getClosestAirport()
        );
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
     public function getIndustriesDD() {
        $final_result = NULL;
        $industries = $this->db->select("tbl_industries", array('id','industry_name_'.$this->lId), array("status" => "a"))->results();
        if ($industries) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($industries); $i++) {
                $fields_replace = array(
                    filtering($industries[$i]['id'], 'input', 'int'),
                    '',
                    filtering($industries[$i]['industry_name_'.$this->lId], 'input', 'int')
                );
                $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
            }
        }
        return $final_result;
    }
    public function getClosestAirport($platform='web') {
        $final_result = NULL;
        $airport = $airport_name = '';

        $airport = $this->db->select("tbl_airport", array('id','airport_name_'.$this->lId), array("status" => "a"))->results();

        if ($airport != '') {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($airport); $i++) {
                $airport_name = filtering($airport[$i]['airport_name_'.$this->lId], 'input', 'int');
                $fields_replace = array(
                    filtering($airport[$i]['id'], 'input', 'int'),
                    '',
                    $airport_name
                );
                if($platform == 'app') {
                    $final_result[] = array('id'=>$airport[$i]['id'],'title'=>$airport_name);
                } else {
                    $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
                }
            }
        }
        return $final_result;
    }
    public function processCompnayCreation($user_id,$platform='web') {
        
        $response = array();
        $response['status'] = false;
        $company_name = filtering($_POST['company_name'], 'input');
        $owner_email_address = filtering($_POST['owner_email_address'], 'input');
        $company_industry_id = filtering($_POST['company_industry_id'], 'input', 'int');
        //$company_size_id = filtering($_POST['company_size_id'], 'input', 'int');
        $app_array = array();
        if ($company_name == '') {
            $response['error'] = ERROR_FROM_COMPANY_ENTER_COMPANY_NAME;
            if($platform == 'app'){
                $app_error['error_company_name'] = $response['error'];
            } else {
                return $response;
            }
        }
        if ($owner_email_address == '') {
            $response['error'] = ERROR_FORM_CREATE_COMPANY_ENTER_EMAIL_ADDRESS  ;
            if($platform == 'app'){
                $app_error['error_owner_email_address'] = $response['error'];
            } else {
                return $response;
            }
        }
        if ($company_industry_id == '' || $company_industry_id == 0) {
            $response['error'] = ERROR_FORM_CREATE_COMPANY_SELECT_INDUSTRY;
            if($platform == 'app'){
                $app_error['error_company_industry'] = $response['error'];
            } else {
                return $response;
            }
        }
        // if ($company_size_id == '' || $company_size_id == 0) {
        //     $response['error'] = ERROR_FORM_CREATE_COMPANY_SELECT_SIZE_OF_COMPANY;
        //     if($platform == 'app'){
        //         $app_error['error_company_size'] = $response['error'];
        //     } else {
        //         return $response;
        //     }
        // }
        $checkIfExists = $this->db->count("tbl_companies", array("company_name" => $company_name, "company_industry_id" => $company_industry_id));
        if ($checkIfExists>0) {
            $response['error'] = ERROR_FORM_CREATE_COMPANY_ENTERED_NAME_EXIST;
            if($platform == 'app'){
                $app_error['already_exist'] = $response['error'];
                $new_app_error['form_errors'] =$app_error;
                return $new_app_error;
            } else {
                return $response;
            }
        } else {

            if($platform == 'app'){
                if(count($app_error)>0){
                    $new_app_error['form_errors'] =$app_error;
                    return $new_app_error;
                }
            }

            $company_details_array = array(
                "user_id" => $user_id,
                "company_name" => $company_name,
                "owner_email_address" => $owner_email_address,
                "company_industry_id" => $company_industry_id,
               // "company_size_id" => $company_size_id,
                "added_on" => date("Y-m-d H:i:s"),
                "updated_on" => date("Y-m-d H:i:s")
            );
            $company_id = $this->db->insert("tbl_companies", $company_details_array)->getLastInsertId();
            $response['company_id'] = $company_id;
            if ($company_id) {
                $response['status'] = true;
                $response['redirect_url'] = SITE_URL . "edit-company/" . encryptIt($company_id);
                $response['success'] = SUCCESS_COMPANY_HAS_ADDED_SUCCESSFULLY;
                //$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => $response['success']));
                return $response;
            } else {
                $response['error'] = ERROR_FORM_CREATE_COMPANY_THERE_SEEMS_TO_BE_USSUE_WHILE_ADDING_YOUR_COMPANY;
                return $response;
            }
        }
    }
    public function getCompanyFollowers($company_id) {
        $query = "SELECT count(id) as company_followers
            FROM tbl_company_followers 
            WHERE company_id = '" . $company_id . "' ";
        $company_details = $this->db->pdoQuery($query)->result();    
        return $company_details['company_followers'];    
    }
    public function getAirportsForSuggestion($user_id, $airport_name='',$platform='web') {
        $final_result = array();
        
        if($platform=='app'){
            $query = "SELECT id,airport_identifier FROM tbl_airport WHERE status = ? ORDER BY id DESC ";

        }else{
            $query = "SELECT id,airport_identifier FROM tbl_airport WHERE airport_identifier LIKE '%" . $airport_name . "%' AND status = ? ORDER BY id DESC ";
        }    
        $where_arr=array('a');
        
        if($platform == 'web'){
            $query .="LIMIT 0, 10";
        }
        $airports = $this->db->pdoQuery($query,$where_arr)->results();
        echo "result: " ;print_r($airports);
        if ($airports) {
            for ($i = 0; $i < count($airports); $i++) {
                $single_company = array();
                if($platform == 'app'){
                    $single_company['airport_id'] = encryptIt(filtering($airports[$i]['id'], 'output', 'int'));
                } else {
                    $single_company['airport_id'] = encryptIt(filtering($airports[$i]['id'], 'output', 'int'));
                }
                $single_company['airport_name'] = filtering($airports[$i]['airport_identifier']);
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
} ?>