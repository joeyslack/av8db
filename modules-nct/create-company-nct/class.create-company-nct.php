<?php
class Create_company extends Home {
    function __construct() {
        parent::__construct();
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
    }
    public function processCompnayCreation($user_id,$platform='web') {
        // echo "<pre>";print_r($_POST);
        // exit();
        
        require_once(DIR_MOD . 'common_storage.php');
        
        $company_storage = new storage();
        
        $response = array();
        $response['status'] = false;
        
        $company_id_old=isset($_POST['company_id']) ? $_POST['company_id'] : '';
        
        $th_arr = array();        
        $th_arr[0] = array('width' => '460', 'height' => '460');
        $th_arr[1] = array('width' => '350', 'height' => '350');
        
        $img_name = date('YmdHis') . '.original' . '.png';
        $temp_src = "temp_files/".$img_name;
        
        $src2 = DIR_NAME_COMPANY_LOGOS."/".$img_name;
        $src = DIR_NAME_COMPANY_LOGOS."/";
        // $temp_src2 = "temp_files/";
        
        $images_str=$_SESSION['temp_files'];
        $main_url=$_SESSION['main_url'];
        $image_resize_array = unserialize(COMPANY_LOGO_RESIZE_ARRAY);
        // $length = count($image_resize_array);
        $length = count($th_arr);

        if($images_str != ''){
            
            $im1 = new Imagick($main_url);
            $im1->readImage($main_url);
            for ($i = 0; $i < $length; $i++) {
                $im1->resizeImage($th_arr[$i]['width'], $th_arr[$i]['height'], Imagick::FILTER_LANCZOS, 1);
                $resize_img = $company_storage->upload_objectBlob('av8db','th'.($i+1).'_'.$images_str,$im1->getImageBlob(),$src);
            }

            $im1->clear();
            $im1->destroy();
            $image_name = $company_logo = $images_str;
            if($image_name != '' && $image_name != 0)
            {
                $del = $company_storage->delete_object('av8db',$images_str,'');
                $_SESSION['temp_files']='';
            }
           /* $temp_main_img = $company_storage->upload_object('av8db','',$_POST['company_logo'],$temp_src);
            
            $main_img = $company_storage->upload_object('av8db','',$_POST['company_logo'],$src2);
            
            $get_main_img = $company_storage->getImageUrl1('av8db',$img_name,$src);
            
            $im1 = new Imagick($get_main_img);
            $im1->readImage($get_main_img);
            
            $length = count($th_arr);
            for ($i = 0; $i < $length; $i++) {
                $im1->resizeImage($th_arr[$i]['width'], $th_arr[$i]['height'], Imagick::FILTER_LANCZOS, 1);
                $resize_img = $company_storage->upload_objectBlob('av8db','th'.($i+1).'_'.$img_name,$im1->getImageBlob(),$src);
            }
            
            $im1->clear();
            $im1->destroy();*/
        }
        
        // $del = $company_storage->delete_object('av8db',$images_str,'');
        
        $company_name = filtering( $_POST['company_name'], 'input');
        $owner_email_address = filtering($_POST['owner_email_address'], 'input');
        $company_url = isset($_POST['company_url']) ? $_POST['company_url'] : '';
        $company_employees = isset($_POST['company_employees']) ? $_POST['company_employees'] : '';
        $closest_airport = isset($_POST['airport_id']) ? decryptIt($_POST['airport_id']) : '0';
        $company_type = isset($_POST['company_type']) ? $_POST['company_type'] : '';
        $location = isset($_POST['location']) ? $_POST['location'] : '';
        $lat = isset($_POST['lat']) ? $_POST['lat'] : '';
        $lng = isset($_POST['lng']) ? $_POST['lng'] : '';
        
        //$company_industry_id = filtering($_POST['company_industry_id'], 'input', 'int');
        //$company_size_id = filtering($_POST['company_size_id'], 'input', 'int');
        $accept_terms=isset($_POST['accept_terms'])?filtering($_POST['accept_terms']):'';
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
        if($accept_terms==''){
            $response['error'] = ERROR_FORM_CREATE_COMPANY_ACCEPT_TERMS_AND_CONDITIONS;
            if($platform == 'app'){
                $app_error['error_accept_terms'] = $response['error'];
            } else {
                return $response;
            }
        }
        
        $comp_request =$this->db->select("tbl_adminrole", "isRequestReceive", array("id" =>'124'))->result();
        
        //print_r($comp_request['isRequestReceive']);exit();
        $checkIfExists = $this->db->count("tbl_companies", array("company_name" => $company_name,"company_type"=>'r'));
        
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
            
            $checkIfEXPExists = $this->db->count("tbl_companies", array("company_name" => $company_name,"company_type"=>'e'));
            
            if($checkIfEXPExists > 0){
                
                $company_details_array = array(
                    "user_id" => $user_id,
                    "company_name" => $company_name,
                    "company_logo" => $company_logo,
                    "owner_email_address" => $owner_email_address,
                    "website_of_company" => $company_url,
                    "company_employees" => $company_employees,
                    "closest_airport_id" => $closest_airport,
                    "company_industry_id" => $company_type,
                    "location" => $location,
                    "lat" => $lat,
                    "lng" => $lng,
                    "company_type"=>"r",
                    "updated_on" => date("Y-m-d H:i:s")
                );
                if($platform=='app'){
                    $company_id =$company_id_old;

                }else{
                    $company_id =decryptIt($company_id_old);

                }
                $affectedRows = $this->db->update("tbl_companies", $company_details_array, array('id' => $company_id))->affectedRows();
            }else{
                
                if($comp_request['isRequestReceive'] == 'y'){
                    
                     $company_details_array = array(
                        "user_id"             => $user_id,
                        "company_name"        => $company_name,
                        "company_logo"        => $company_logo,
                        "owner_email_address" => $owner_email_address,
                        "website_of_company"  => $company_url,
                        "company_employees"   => $company_employees,
                        "closest_airport_id"  => $closest_airport,
                        "company_industry_id" => $company_type,
                        "location" => $location,
                        "lat" => $lat,
                        "lng" => $lng,
                        "isCompanyEmailVerify"=> 'n',
                        "isAdminVerify"       => '',
                        "adminActiveDeactive" => '',
                        "added_on"            => date("Y-m-d H:i:s"),
                        "updated_on"          => date("Y-m-d H:i:s")
                    );
                }else{
                    
                     $company_details_array = array(
                        "user_id"             => $user_id,
                        "company_name"        => $company_name,
                        "company_logo"        => $company_logo,
                        "owner_email_address" => $owner_email_address,
                        "website_of_company"  => $company_url,
                        "company_employees"   => $company_employees,
                        "closest_airport_id"  => $closest_airport,
                        "company_industry_id" => $company_type,
                        "location" => $location,
                        "lat" => $lat,
                        "lng" => $lng,
                        "isCompanyEmailVerify"=> 'n',
                        "isAdminVerify"       => 'y',
                        "adminActiveDeactive" => 'a',
                        "added_on"            => date("Y-m-d H:i:s"),
                        "updated_on"          => date("Y-m-d H:i:s")
                    );
                }
                $company_id = $this->db->insert("tbl_companies", $company_details_array)->getLastInsertId();
            }
            
            //print_r($company_id);
            $response['company_id'] = $company_id;
            if ($company_id) {
                
                $user_data =$this->db->select("tbl_users", "*", array("id" => $user_id))->result();

                $arrayCont = array();
                $arrayCont['greetings'] = $user_data['first_name'] . " " . $user_data['last_name'];
                $arrayCont['activationLink'] = "<a href='" . SITE_URL . "company/verifyCompany/" . encryptIt($company_id) . "' target='_blank'>Click here</a>";
                
                generateEmailTemplateSendEmail("company_verification", $arrayCont, $owner_email_address);
                
                $response['status'] = true;
                $response['redirect_url'] = SITE_URL . "company/my-companies/";
                $response['success'] = SUCCESS_COMPANY_HAS_ADDED_SUCCESSFULLY;
                return $response;
            } else {
                $response['error'] = ERROR_FORM_CREATE_COMPANY_THERE_SEEMS_TO_BE_USSUE_WHILE_ADDING_YOUR_COMPANY;
                return $response;
            }
        }
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
    // public function getCompnaySizesDD($platform='web') {
    //     $final_result = NULL;
    //     $company_sizes = $this->db->select("tbl_company_sizes", array('id','company_size_'.$this->lId), array("status" => "a"))->results();
    //     if ($company_sizes) {
    //         $getSelectBoxOption = $this->getSelectBoxOption();
    //         $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
    //         for ($i = 0; $i < count($company_sizes); $i++) {
    //             $company_size_title = filtering($company_sizes[$i]['company_size_'.$this->lId], 'input', 'int');
    //             $fields_replace = array(
    //                 filtering($company_sizes[$i]['id'], 'input', 'int'),
    //                 '',
    //                 $company_size_title
    //             );
    //             if($platform == 'app') {
    //                 $final_result[] = array('id'=>$company_sizes[$i]['id'],'title'=>$company_size_title);
    //             } else {
    //                 $final_result .= str_replace($fields, $fields_replace, $getSelectBoxOption);
    //             }
    //         }
    //     }
    //     return $final_result;
    // }

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%COMPANY_INDUSTRY_OPTIONS%",
            //"%COMPANY_SIZE_OPTIONS%"
        );
        $fields_replace = array(
            $this->getIndustriesDD(),
            //$this->getCompnaySizesDD(),
        );
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
} ?>