<?php

class Create_job extends Home {

    function __construct($platform='web') {
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->platform = $platform;
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
    }

    public function processJobCreation($user_id) {
        if($user_id==''){
          $user_id=$this->current_user_id;  
        }
        $response = array();
        $response['status'] = false;
        //_print_r($_POST['licenses_endorsement']);exit();
        $job_title = filtering($_POST['job_title'], 'input');
        $job_location = filtering($_POST['job_location'], 'input');
        $company_name_id = filtering(decryptIt($_POST['company_name_id']), 'input', 'int');
        $category_id = filtering($_POST['category_id'], 'input', 'int');
        //$licenses_endorsement_id = isset($_POST['licenses_endorsement']) ? $_POST['licenses_endorsement'] : '';
        //$licenses_endorsement_id = implode(',', $_POST['licenses_endorsement']);

        $last_date_of_application = date("Y-m-d", strtotime($_POST['last_date_of_application']));

        // Location details
        $formatted_address = filtering($_POST['formatted_address'], 'input');
        $address1 = filtering($_POST['address1'], 'input');
        $address2 = filtering($_POST['address2'], 'input');
        $country = filtering($_POST['country'], 'input');
        $state = filtering($_POST['state'], 'input');
        $city1 = filtering($_POST['city1'], 'input');
        $city2 = filtering($_POST['city2'], 'input');
        $postal_code = filtering($_POST['postal_code'], 'input');
        $latitude = filtering($_POST['latitude'], 'input');
        $longitude = filtering($_POST['longitude'], 'input');

        if ($company_name_id == '' || $company_name_id == 0) {
            $response['error'] = LBL_SELECT_COMPANY_NAME;
            return $response;
        }

        if ($category_id == '' || $category_id == 0) {
            $response['error'] = LBL_SELECT_CATEGORY;
            return $response;
        }

        if ($job_title == '') {
            $response['error'] =ERROR_ENTER_JOB_TITLE;
            return $response;
        }

        if ($job_location == '') {
            $response['error'] = ERROR_ENTER_JOB_LOCATION;
            return $response;
        }

        $checkIfExists = $this->db->select("tbl_jobs", array('id,last_date_of_application'), array("job_title" => $job_title, "company_id" => $company_name_id, "job_category_id" => $category_id, "user_id" => $user_id))->result();
        if ($checkIfExists && $checkIfExists['last_date_of_application'] >= date('Y-m-d')) {
            
            $response['error'] = LBL_JOB_ALREADY_EXIST;
            return $response;    
            
        } else {


            if($job_location != ''){
                $location_id = $job_location;
            }
            else if($formatted_address != '' && $latitude != '' && $longitude != '') {

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

            }  else {
                $response['error'] = ERROR_ENTER_JOB_LOCATION;
                return $response;
            }

            $job_details_array = array(
                "user_id" => $user_id,
                "company_id" => $company_name_id,
                "job_category_id" => $category_id,
                //"licenses_endorsement_id" => $licenses_endorsement_id,
                "job_title" => $job_title,
                "location_id" => $location_id,
                "last_date_of_application" => $last_date_of_application,
                "added_on" => date("Y-m-d H:i:s"),
                "updated_on" => date("Y-m-d H:i:s")
            );
            //_print_r($_POST);exit();
            $job_id = $this->db->insert("tbl_jobs", $job_details_array)->getLastInsertId();
            for ($i=0; $i < sizeof($_POST['licenses_endorsement']); $i++) { 
                $this->db->insert('tbl_job_license_hours', array('user_id' => $this->session_user_id, 'job_id' => $job_id,'license_ids' => $_POST['licenses_endorsement'][$i],'license_hours' =>'','createdAt' => date('Y-m-d H:i:s')));
            }
            if (isset($company_name_id) && $company_name_id != "") {
                    $company_followers = company_follower($company_name_id);
                    $company_followers = explode(',', $company_followers);

                    $company_followers[] = filtering(getTableValue("tbl_companies", "user_id", array("id" => $company_name_id)));
                    $cname = filtering(getTableValue("tbl_companies", "company_name", array("id" => $company_name_id)));
                    
                    for ($i = 0; $i < count($company_followers); $i++) {

                        if ($company_followers[$i] != $user_id) {

                            if($company_followers[$i]!='' && $company_followers[$i] > 0){

                                $notificationArray = array(
                                    "job_id" => $job_id,
                                    "company_id"=>$company_name_id,
                                    "type" => "jpc",
                                    "action_by_user_id" => $user_id,
                                    "added_on" => date("Y-m-d H:i:s"),
                                    "updated_on" => date("Y-m-d H:i:s")
                                );
                                $notificationArray['user_id'] = $company_followers[$i];

                                $notification_id = $this->db->insert('tbl_notifications', $notificationArray)->getLastInsertId();

                                /* Push notification */
                                $user_data = $this->db->select('tbl_users',array('first_name,last_name'),array('id'=>$user_id))->result();
                                $push_user_name = filtering($user_data['first_name'].' '.$user_data['last_name']);
                                $push_data = array(
                                    'user_name'=>$push_user_name,
                                    'company_name'=>$cname,
                                    'notification_id'=>$notification_id,
                                    'job_id'=>$job_id,
                                    'company_id'=>$company_name_id

                                );
                                set_notification($company_followers[$i],'jpc',$push_data);
                            }
                        }
                    }
                }

            if ($job_id) {
                $response['status'] = true;
                $response['redirect_url'] = SITE_URL . "edit-job-form/" . encryptIt($job_id);
                $response['job_id'] = $job_id;
                //$response['success'] = "Job has been added successfully.";
                //$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => LBL_JOB_POSTED_SUCESSFULLY));
                return $response;
            } else {
                $response['error'] = ERROR_SOME_ISSUE_ADDING_JOB;
                return $response;
            }
        }
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

    public function getPageContent() {
        $final_result = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%COMPANY_NAME_OPTIONS%",
            "%CATEGORY_OPTIONS%"
        );

        $fields_replace = array(
            $this->getCompanyDD(),
            $this->getCategoriesDD(),
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

}

?>
