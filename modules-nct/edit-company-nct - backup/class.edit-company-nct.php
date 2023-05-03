<?php class Edit_company extends Home {
    function __construct($company_id = '') {
        $this->company_id = $company_id;
        parent::__construct();
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        if ($this->company_id > 0) {
            $query = "SELECT comp.company_name,comp.company_logo,comp.banner_image,comp.company_description,comp.owner_email_address,comp.company_industry_id,comp.services_provided,comp.website_of_company,comp.foundation_year,comp.location,comp.lat,comp.lng,i.industry_name_".$this->lId." as industry_name,i.id as inid FROM tbl_companies comp 
                    LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id 
                    WHERE comp.id = ? ";

            $company_details_array = $this->db->pdoQuery($query,array($company_id))->result();
            $this->company_name = filtering($company_details_array['company_name']);
            $this->company_logo = filtering($company_details_array['company_logo']);
            $this->banner_image = filtering($company_details_array['banner_image']);
            $this->company_description = filtering($company_details_array['company_description']);
            $this->owner_email_address = filtering($company_details_array['owner_email_address']);
            $this->company_industry_id = filtering($company_details_array['company_industry_id']);
            $this->industry_name = filtering($company_details_array['industry_name']);
            //$this->company_size_id = filtering($company_details_array['company_size_id']);
            $this->range_of_no_of_employees = isset($company_details_array['range_of_no_of_employees']) ? $company_details_array['range_of_no_of_employees'] : '';
            $this->services_provided = filtering($company_details_array['services_provided']);
            $this->website_of_company = filtering($company_details_array['website_of_company']);
            $this->foundation_year = filtering($company_details_array['foundation_year']);
            $this->location = filtering($company_details_array['location']);
            $this->lat = filtering($company_details_array['lat']);
            $this->lng = filtering($company_details_array['lng']);
            $this->inid=filtering($company_details_array['inid']);
        }
    }
    public function generateCompanyLocationBox($company_location_id = '', $location_id = '',$platform='web') {
        $final_result = '';
        $response = array();
        $response['status'] = false;
        if ($company_location_id) {
            $cl_id = encryptIt($company_location_id);
            $query = "SELECT l.formatted_address,l.address1,l.address2,l.country,l.state,l.city1,l.city2,l.postal_code,l.latitude,l.longitude,cl.is_hq 
                        FROM tbl_company_locations cl 
                        LEFT JOIN tbl_locations l ON cl.location_id = l.id 
                        WHERE cl.id = ? ";
            $getCLDetails = $this->db->pdoQuery($query,array($company_location_id))->result();
        } else {
            $cl_id = '';
            $getCLDetails = $_POST;
        }
        $formatted_address = filtering($getCLDetails['formatted_address']);
        $address1 = filtering($getCLDetails['address1']);
        $address2 = filtering($getCLDetails['address2']);
        $country = filtering($getCLDetails['country']);
        $state = filtering($getCLDetails['state']);
        $city1 = filtering($getCLDetails['city1']);
        $city2 = filtering($getCLDetails['city2']);
        $postal_code = filtering($getCLDetails['postal_code']);
        $latitude = filtering($getCLDetails['latitude'], 'output', 'float');
        $longitude = filtering($getCLDetails['longitude'], 'output', 'float');
        $is_hq = filtering($getCLDetails['is_hq']);
        if ('y' == $is_hq) {
            $hq_class = "is-hq";
        } else {
            $hq_class = "make-hq";
        }
        $company_location_single_tpl = new Templater(DIR_TMPL . $this->module . "/company-location-single-nct.tpl.php");
        $company_location_single_tpl_parsed = $company_location_single_tpl->parse();
        $fields = array(
            "%UNIQUE_IDENTIFIER%",
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
            "%IS_HQ%",
            "%CL_ID%",
            "%HQ_CLASS%"
        );
        $fields_replace = array(
            time(),
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
            $is_hq,
            $cl_id,
            $hq_class
        );
        if($platform == 'app'){
            $final_result = array('location_id'=>$company_location_id,'location_text'=>$formatted_address,'headquarter'=>$is_hq);
        } else {
            $final_result = str_replace($fields, $fields_replace, $company_location_single_tpl_parsed);
            $response['status'] = true;
        }
        $response['content'] = $final_result;
        return $response;
    }
    public function getCompanyLocations($company_id,$platform='web') {
        $final_result = NULL;
        $getCompanyLocations = $this->db->select("tbl_company_locations", array('id','location_id'), array("company_id" => $company_id))->results();
        if ($getCompanyLocations) {
            for ($i = 0; $i < count($getCompanyLocations); $i++) {
                $company_location_id = filtering($getCompanyLocations[$i]['id'], 'input', 'int');
                $location_id = filtering($getCompanyLocations[$i]['location_id'], 'input', 'int');
                $response = $this->generateCompanyLocationBox($company_location_id, $location_id,$platform);
                if($platform=='app'){
                    $final_result[] = $response['content'];
                } else {
                    $final_result .= $response['content'];
                }
            }
        }
        return $final_result;
    }
    public function getConnections() {
        $final_result = $company_admin_ids = array();
        $company_admin_ids_imploded = "";
        $user_id = $this->session_user_id;
        $user_name = filtering($_POST['user_name'], 'input');
        $company_admin_ids_encrypted=( ( isset($_POST['company_admin_ids']) ) ? $_POST['company_admin_ids'] : '' );
        if (is_array($company_admin_ids_encrypted) && !empty($company_admin_ids_encrypted)) {
            for ($i = 0; $i < count($company_admin_ids_encrypted); $i++) {
                $company_admin_ids[] = decryptIt($company_admin_ids_encrypted[$i]);
            }
            $company_admin_ids_imploded = implode(",", $company_admin_ids);
        }
        $not_in_query = "";
        if ($company_admin_ids_imploded != "") {
            $not_in_query = " AND id NOT IN ( " . $company_admin_ids_imploded . " ) ";
        }
        $connectionsArray = getConnections($this->session_user_id,true);
        $connectionIds = implode(',', $connectionsArray);
        if($connectionIds != ''){
            $query = "SELECT id as user_id, concat_ws(' ', first_name, last_name) as user_name 
                      FROM tbl_users 
                      WHERE id IN(".$connectionIds.") AND (first_name like '%" . $user_name . "%' OR last_name like '%" . $user_name . "%') ".$not_in_query."
                      GROUP BY id ORDER BY id DESC LIMIT 0, 10 ";
            $final_result = $this->db->pdoQuery($query)->results();
            if ($final_result) {
                for ($i = 0; $i < count($final_result); $i++) {
                    $final_result[$i]['encrypted_id'] = encryptIt($final_result[$i]['user_id']);
                }
            }
        }
        return $final_result;
    }

    public function uploadCompanyLogo(){
        $user_logo = '';
        $images_str=$_SESSION['temp_files'];
        
        if($images_str!=""){
             
            if($images_str !="" || $images_str !=NULL){
             $to_path=DIR_UPD."temp_files/th1_$images_str";
             $file_name= DIR_UPD_COMPANY_LOGOS;
             
             if(!file_exists($file_name)){
             mkdir($file_name,0777,true);
             } 
             
             //copy($to_path, $file_name.$value);
             $uploadDir = DIR_UPD_COMPANY_LOGOS;
             $image_resize_array = unserialize(COMPANY_LOGO_RESIZE_ARRAY);
             
             $image_url = $to_path;

           /*  echo $image_url.'<br>';
             echo $uploadDir.'<br>';
             echo '<pre>';print_r($company_logo_resize_array).'<br>';
             exit;*/
             $image_name = GenerateThumbnail($image_url, $uploadDir, $image_url, $image_resize_array);
             //print_r($images_str);exit;
             if($image_name != '' && $image_name != 0)
             {
                $user_logo = $image_name;
                 //$db->update('tbl_users',array('profile_picture_name'=>$user_logo),array('id'=>$id));

              }          
            }                                
            $_SESSION['temp_files']='';
            $img_arr= explode(".",$images_str);

            unlink(DIR_UPD."temp_files/$images_str");
            unlink(DIR_UPD."temp_files/th1_$images_str");
            unlink(DIR_UPD."temp_files/th1_".$img_arr[0].".webp");

            /*$files = glob(DIR_UPD."temp_files/*"); // get all file names
                foreach($files as $file){ // iterate files
                if(is_file($file))
                unlink($file); // delete file
            }*/
        }   
        return $user_logo;     
    }

    public function uploadCompanyBanner(){
        $user_logo = '';
        $images_str=$_SESSION['temp_slider'];
      //print_r($images_str);exit;
        if($images_str!=""){
             
            if($images_str !="" || $images_str !=NULL){
             $to_path=DIR_UPD."temp_files/th1_$images_str";
             $file_name= DIR_UPD_COMPANY_BANNER_IMAGES;
             
             if(!file_exists($file_name)){
             mkdir($file_name,0777,true);
             } 
             
             //copy($to_path, $file_name.$value);
             $uploadDir = DIR_UPD_COMPANY_BANNER_IMAGES;
             $image_resize_array = unserialize(COMPANY_BANNER_IMAGE_RESIZE_ARRAY);
             
             $image_url = $to_path;
           /*  echo $image_url.'<br>';
             echo $uploadDir.'<br>';
             echo '<pre>';print_r($company_logo_resize_array).'<br>';
             exit;*/
             $image_name = GenerateThumbnail($image_url, $uploadDir, $image_url, $image_resize_array);
             
             if($image_name != '' && $image_name != 0)
             {
                $user_logo = $image_name;
                 //$db->update('tbl_users',array('profile_picture_name'=>$user_logo),array('id'=>$id));

              }       
                
            }
                                
            $_SESSION['temp_slider']='';
            $files = glob(DIR_UPD."temp_files/*"); // get all file names
                foreach($files as $file){ // iterate files
                if(is_file($file))
                unlink($file); // delete file
            }
        }   
        return $user_logo;     
    }

    public function updateCompanyDetails($company_id,$platform='web') {
        $response = $company_details_array = array();
        $response['status'] = false;
       

        $old_logo = $this->db->select('tbl_companies',array('company_logo','banner_image'),array('id'=>$company_id))->result();

        if($platform == 'web'  && isset($_SESSION['temp_files']) && $_SESSION['temp_files'] != ''){
            $image =  $this->uploadCompanyLogo();

            $company_details_array['company_logo'] = $image;
        }
        
        if (isset($_FILES['company_logo']) && !($_FILES['company_logo']['error']) && $platform == 'app') {
            $file_array = $_FILES["company_logo"];
            $upload_dir = DIR_UPD_COMPANY_LOGOS;
            $image_resize_array = unserialize(COMPANY_LOGO_RESIZE_ARRAY);
            $response = uploadImage($file_array, $upload_dir, $image_resize_array);
            if (!$response['status']) {
                return $response;
            } else {
                $company_details_array['company_logo'] = $response['image_name'];
                if($old_logo['company_logo'] != ''){
                    if(file_exists(DIR_UPD_COMPANY_LOGOS.$old_logo['company_logo'])){
                        unlink(DIR_UPD_COMPANY_LOGOS.$old_logo['company_logo']);
                    }
                    if(file_exists(DIR_UPD_COMPANY_LOGOS.'th1_'.$old_logo['company_logo'])){
                        unlink(DIR_UPD_COMPANY_LOGOS.'th1_'.$old_logo['company_logo']);
                    }
                    if(file_exists(DIR_UPD_COMPANY_LOGOS.'th2_'.$old_logo['company_logo'])){
                        unlink(DIR_UPD_COMPANY_LOGOS.'th2_'.$old_logo['company_logo']);
                    }
                }
            }
        } else {
            if ($_POST['is_logo_removed'] == "true") {
                $company_details_array['company_logo'] = "";
                if($old_logo['company_logo'] != ''){
                    if(file_exists(DIR_UPD_COMPANY_LOGOS.$old_logo['company_logo'])){
                        unlink(DIR_UPD_COMPANY_LOGOS.$old_logo['company_logo']);
                    }
                    if(file_exists(DIR_UPD_COMPANY_LOGOS.'th1_'.$old_logo['company_logo'])){
                        unlink(DIR_UPD_COMPANY_LOGOS.'th1_'.$old_logo['company_logo']);
                    }
                    if(file_exists(DIR_UPD_COMPANY_LOGOS.'th2_'.$old_logo['company_logo'])){
                        unlink(DIR_UPD_COMPANY_LOGOS.'th2_'.$old_logo['company_logo']);
                    }
                }
            }
        }
        $company_details_array['company_name'] = filtering($_POST['company_name'], 'input');
        $company_details_array['owner_email_address'] = filtering($_POST['owner_email_address'], 'input');
        $company_details_array['company_industry_id'] = filtering($_POST['company_industry_id'], 'input');
        //$company_details_array['company_size_id'] = filtering($_POST['company_size_id'], 'input');
      
        $company_details_array['company_description'] = filtering($_POST['company_description'], 'input');
        $company_details_array['website_of_company'] = filtering($_POST['website_of_company'], 'input');
        $company_details_array['foundation_year'] = filtering($_POST['foundation_year'], 'input');
        $company_details_array['location'] = filtering($_POST['location'], 'input');
        $company_details_array['lat'] = filtering($_POST['lat'], 'input');
        $company_details_array['lng'] = filtering($_POST['lng'], 'input');
        // Company Location data
        $cl_id = ( ( isset($_POST['cl_id']) ) ? $_POST['cl_id'] : array() );
        $formatted_address = ( ( isset($_POST['formatted_address']) ) ? $_POST['formatted_address'] : array() );
        $address1 = ( ( isset($_POST['address1']) ) ? $_POST['address1'] : array() );
        $address2 = ( ( isset($_POST['address2']) ) ? $_POST['address2'] : array() );
        $country = ( ( isset($_POST['country']) ) ? $_POST['country'] : array() );
        $state = ( ( isset($_POST['state']) ) ? $_POST['state'] : array() );
        $city1 = ( ( isset($_POST['city1']) ) ? $_POST['city1'] : array() );
        $city2 = ( ( isset($_POST['city2']) ) ? $_POST['city2'] : array() );
        $postal_code = ( ( isset($_POST['postal_code']) ) ? $_POST['postal_code'] : array() );
        $latitude = ( ( isset($_POST['latitude']) ) ? $_POST['latitude'] : array() );
        $longitude = ( ( isset($_POST['longitude']) ) ? $_POST['longitude'] : array() );
        $is_hq = ( ( isset($_POST['is_hq']) ) ? $_POST['is_hq'] : array() );

        if (!empty($latitude) && !empty($latitude)) {
            //echo count($formatted_address);
            if (count($formatted_address) == count($latitude) && count($latitude) == count($longitude)) {
                $cl_ids_array = array();
                $no_of_locations_to_be_inserted = ( ( count($latitude) <= 5 ) ? count($latitude) : 5);
                for ($i = 0; $i < $no_of_locations_to_be_inserted; $i++) {
                    $cl_id = filtering(decryptIt($_POST['cl_id'][$i]), 'input', 'int');
                    $job_location_details_array = array(
                        "formatted_address" => filtering($_POST['formatted_address'][$i]),
                        "address1" => filtering($_POST['address1'][$i]),
                        "address2" => filtering($_POST['address2'][$i]),
                        "country" => filtering($_POST['country'][$i]),
                        "state" => filtering($_POST['state'][$i]),
                        "city1" => filtering($_POST['city1'][$i]),
                        "city2" => filtering($_POST['city2'][$i]),
                        "postal_code" => filtering($_POST['postal_code'][$i]),
                        "latitude" => filtering($_POST['latitude'][$i]),
                        "longitude" => filtering($_POST['latitude'][$i]),
                        "date_updated" => date("Y-m-d H:i:s")
                    );
                    $company_location_array = array(
                        "company_id" => $company_id,
                        "is_hq" => filtering($_POST['is_hq'][$i]),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    if ($cl_id > 0) {
                        $this->db->update("tbl_company_locations", $company_location_array, array("id" => $cl_id))->affectedRows();
                        $cl_ids_array[] = $cl_id;
                    } else {
                        $job_location_details_array['date_added'] = date("Y-m-d H:i:s");
                        $location_id = $this->db->insert("tbl_locations", $job_location_details_array)->getLastInsertId();
                        $company_location_array['location_id'] = $location_id;
                        $company_location_array['updated_on'] = date("Y-m-d H:i:s");
                        $cl_id = $this->db->insert("tbl_company_locations", $company_location_array)->getLastInsertId();
                        $cl_ids_array[] = $cl_id;
                    }
                }
                if (!empty($cl_ids_array)) {
                    $cl_ids_array_imploded = implode(",", $cl_ids_array);
                    $query = "SELECT id,location_id FROM tbl_company_locations WHERE company_id = ? AND id NOT IN ( " . $cl_ids_array_imploded . " ) ";
                    $clinic_locations = $this->db->pdoQuery($query,array($company_id))->results();
                    if ($clinic_locations) {
                        for ($i = 0; $i < count($clinic_locations); $i++) {
                            $id = $clinic_locations[$i]['id'];
                            $location_id = $clinic_locations[$i]['location_id'];
                            //$this->db->delete("tbl_locations", array("id" => $location_id));
                            $this->db->delete("tbl_company_locations", array("id" => $id));
                        }
                    }
                }
            } else {
                $response['error'] = ERROR_EDIT_COMP_OOPS_SOMETHING_GOES_WRONG_SAVING_COMPANY_LOCATIONS;
                return $response;
            }
        } else {
            $this->db->delete("tbl_company_locations", array("id" => $company_id));
            
        }
        $company_admin_ids = ( ( isset($_POST['company_admin_ids']) ) ? $_POST['company_admin_ids'] : array() );
        if (!empty($company_admin_ids)) {
            $company_admin_ids_decrypted = array();
            for ($i = 0; $i < count($company_admin_ids); $i++) {
                $company_admin_ids_decrypted[] = $user_id = filtering(decryptIt($company_admin_ids[$i]), 'input', 'int');
                $checkIfExists = $this->db->count("tbl_compnay_admins",  array("company_id" => $company_id, "user_id" => $user_id));
                if ($checkIfExists == 0) {
                    $compnay_admins_array = array(
                        "company_id" => $company_id,
                        "user_id" => $user_id,
                        "added_on" => date("Y-m-d H:i:s")
                    );
                    $this->db->insert("tbl_compnay_admins", $compnay_admins_array)->getLastInsertId();
                }
            }            
            $company_admin_ids_imploded = implode(",", $company_admin_ids_decrypted);
            $query = "DELETE FROM tbl_compnay_admins WHERE company_id = '".$company_id."' AND user_id NOT IN (".$company_admin_ids_imploded.") ";
            $this->db->exec($query);
        }

        if($platform == 'web' && isset($_SESSION['temp_slider']) && $_SESSION['temp_slider'] != ''){
            $imageBanner =  $this->uploadCompanyBanner();

            $company_details_array['banner_image'] = $imageBanner;
        }

        if (isset($_FILES['banner_image']) && !($_FILES['banner_image']['error']) && $platform == 'app') {
            $file_array = $_FILES["banner_image"];
            $upload_dir = DIR_UPD_COMPANY_BANNER_IMAGES;
            $image_resize_array = unserialize(COMPANY_BANNER_IMAGE_RESIZE_ARRAY);
            $response = uploadImage($file_array, $upload_dir, $image_resize_array);

            if($old_logo['banner_image'] != ''){
                if(file_exists(DIR_UPD_COMPANY_BANNER_IMAGES.$old_logo['banner_image'])){
                    unlink(DIR_UPD_COMPANY_BANNER_IMAGES.$old_logo['banner_image']);
                }
                if(file_exists(DIR_UPD_COMPANY_BANNER_IMAGES.'th1_'.$old_logo['banner_image'])){
                    unlink(DIR_UPD_COMPANY_BANNER_IMAGES.'th1_'.$old_logo['banner_image']);
                }
                if(file_exists(DIR_UPD_COMPANY_BANNER_IMAGES.'th2_'.$old_logo['banner_image'])){
                    unlink(DIR_UPD_COMPANY_BANNER_IMAGES.'th2_'.$old_logo['banner_image']);
                }
            }

            if (!$response['status']) {
                return $response;
            } else {
                $company_details_array['banner_image'] = $response['image_name'];
            }
        } else {
            if ($_POST['is_banner_removed'] == "true") {
                $company_details_array['banner_image'] = "";
                if($old_logo['banner_image'] != ''){
                    if(file_exists(DIR_UPD_COMPANY_BANNER_IMAGES.$old_logo['banner_image'])){
                        unlink(DIR_UPD_COMPANY_BANNER_IMAGES.$old_logo['banner_image']);
                    }
                    if(file_exists(DIR_UPD_COMPANY_BANNER_IMAGES.'th1_'.$old_logo['banner_image'])){
                        unlink(DIR_UPD_COMPANY_BANNER_IMAGES.'th1_'.$old_logo['banner_image']);
                    }
                    if(file_exists(DIR_UPD_COMPANY_BANNER_IMAGES.'th2_'.$old_logo['banner_image'])){
                        unlink(DIR_UPD_COMPANY_BANNER_IMAGES.'th2_'.$old_logo['banner_image']);
                    }
                }
            }
        }
        $affectedRows = $this->db->update("tbl_companies", $company_details_array, array("id" => $company_id))->affectedRows();
        if ($affectedRows > 0 || $affectedRows == 0) {
            $response['status'] = true;
            $response['success'] = SUCCESS_EDIT_COMP_COMPANY_DETAILS_HAS_BEEN_SAVED;
            $response['redirect_url'] = SITE_URL . "company/".$company_id;
            $_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => $response['success']));
        } else {
            $response['error'] = ERROR_EDIT_COMP_SOMETHING_GOES_WRONG_UPDATING_COMPANY_DETAIL;
        }
        return $response;
    }
    public function insertCompanyLocation($req='front'){
        extract($_POST);
        if($req=='front'){
            $company_id = decryptIt($_POST['company_id']);
        }else{
            $company_id = ($_POST['company_id']);
        }
        if (!empty($latitude) && !empty($latitude)) {
           // echo "<pre>";print_r($_POST);
            if (count($formatted_address) == count($latitude) && count($latitude) == count($longitude)) {
                $cl_ids_array = array();
                //$no_of_locations_to_be_inserted = ( ( count($latitude) <= 5 ) ? count($latitude) : 5);
               // for ($i = 0; $i < $no_of_locations_to_be_inserted; $i++) {
                    $cl_id = filtering(decryptIt($_POST['cl_id']), 'input', 'int');
                    $job_location_details_array = array(
                        "formatted_address" => filtering($_POST['formatted_address']),
                        "address1" => filtering($_POST['address1']),
                        "address2" => filtering($_POST['address2']),
                        "country" => filtering($_POST['country']),
                        "state" => filtering($_POST['state']),
                        "city1" => filtering($_POST['city1']),
                        "city2" => filtering($_POST['city2']),
                        "postal_code" => filtering($_POST['postal_code']),
                        "latitude" => filtering($_POST['latitude']),
                        "longitude" => filtering($_POST['latitude']),
                        "date_updated" => date("Y-m-d H:i:s")
                    );
                    $company_location_array = array(
                        "company_id" => $company_id,
                        "is_hq" => filtering($_POST['is_hq']),
                        "updated_on" => date("Y-m-d H:i:s")
                    );
                    if ($cl_id > 0) {
                        $this->db->update("tbl_company_locations", $company_location_array, array("id" => $cl_id))->affectedRows();
                        $cl_ids_array[] = $cl_id;
                    } else {
                        $job_location_details_array['date_added'] = date("Y-m-d H:i:s");
                        $location_id = $this->db->insert("tbl_locations", $job_location_details_array)->getLastInsertId();
                        $company_location_array['location_id'] = $location_id;
                        $company_location_array['updated_on'] = date("Y-m-d H:i:s");
                        $cl_id = $this->db->insert("tbl_company_locations", $company_location_array)->getLastInsertId();
                        $cl_ids_array[] = $cl_id;
                    }
                //}
                /*if (!empty($cl_ids_array)) {
                    $cl_ids_array_imploded = implode(",", $cl_ids_array);
                    $query = "SELECT id,location_id FROM tbl_company_locations WHERE company_id = '" . $company_id . "' AND id NOT IN ( " . $cl_ids_array_imploded . " ) ";
                    $clinic_locations = $this->db->pdoQuery($query)->results();
                    if ($clinic_locations) {
                        for ($i = 0; $i < count($clinic_locations); $i++) {
                            $id = $clinic_locations[$i]['id'];
                            $location_id = $clinic_locations[$i]['location_id'];
                            //$this->db->delete("tbl_locations", array("id" => $location_id));
                            //$this->db->delete("tbl_company_locations", array("id" => $id));
                        }
                    }
                }*/
            } else {
                $response['error'] = ERROR_EDIT_COMP_OOPS_SOMETHING_GOES_WRONG_SAVING_COMPANY_LOCATIONS;
                return $response;
            }
        } else {
            $this->db->delete("tbl_company_locations", array("id" => $company_id));
            
        }
    }
    public function generateCompanyAdminBox($company_admin_id,$platform='web') {
        $final_result = '';
        $response = array();
        $response['status'] = false;
        $user_details = $this->db->select("tbl_users", array('first_name','last_name','profile_picture_name'), array("id" => $company_admin_id))->result();
        $first_name = filtering($user_details['first_name']);
        $last_name = filtering($user_details['last_name']);
        $profile_url = get_user_profile_url($company_admin_id);
        $profile_picture_url = SITE_URL . "image/" . DIR_NAME_USERS . "/" . filtering($user_details['profile_picture_name']);
        $healine = '';
        //$healine = getUserHeadline($company_admin_id);
        $single_company_admin_tpl = new Templater(DIR_TMPL . $this->module . "/single-company-admin-nct.tpl.php");
        $user_headline_tpl_parsed = "";
        if ($healine) {
            $user_headline_tpl = new Templater(DIR_TMPL . $this->module . "/user-headline-nct.tpl.php");
            $user_headline_tpl_parsed = $user_headline_tpl->parse();
        }
        $single_company_admin_tpl->set('user_headline', $user_headline_tpl_parsed);
        $single_company_admin_tpl_parsed = $single_company_admin_tpl->parse();
        $fields = array("%USER_ID_ENCRYPTED%","%UNIQUE_IDENTIFIER%","%USER_PROFILE_PICTURE%","%USER_NAME%","%PROFILE_URL%","%HEADLINE%");
        $unique_identifier = time();
        $img = getImageURL("user_profile_picture", $company_admin_id, "th3",$platform);
        $fields_replace = array(
            encryptIt($company_admin_id),
            $unique_identifier,
            $img,
            $first_name . " " . $last_name,
            $profile_url,
            $healine
        );
        if($platform=='app'){
            $app_array = array(
                'user_id'=>$company_admin_id,
                'user_image'=>$img,
                'user_name'=>$first_name . " " . $last_name,
                'tagline'=>$healine
            );
            $response['content'] = $app_array;
        } else {
            $final_result = str_replace($fields, $fields_replace, $single_company_admin_tpl_parsed);
            $response['status'] = true;
            $response['content'] = $final_result;
        }
        return $response;
    }
    public function getCompanyAdmins($company_id,$platform='web') {
        $final_result = NULL;
        $company_logo_url_webp = "";
        $company_admins=$this->db->select("tbl_compnay_admins",array('user_id'), array("company_id" => $company_id))->results();
        if ($company_admins) {
            for ($i = 0; $i < count($company_admins); $i++) {
                $company_admin_id = $company_admins[$i]['user_id'];
                $response = $this->generateCompanyAdminBox($company_admin_id,$platform);
                if($platform == 'app'){
                    $final_result[] = $response['content'];
                } else {
                    $final_result .= $response['content'];
                }
            }
        }
        return $final_result;
    }
    public function getPageContent() {
        $final_result = NULL;
        $company_logo_url_webp = '';
        require_once('storage.php');
        $company_storage = new storage();
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields = array(
            "%EDIT_COMPANY_FORM_ACTION_URL%",
            "%LOGO_SELECT_CONTAINER_HIDDEN_CLASS%",
            "%LOGO_PREVIEW_CONTAINER_HIDDEN_CLASS%",
            "%COMPANY_LOGO_URL%",
            "%COMPANY_DETAIL_PAGE_URL%",
            "%COMPANY_NAME%",
            "%INDUSTRY_NAME%",
            "%OWNER_EMAIL_ADDRESS%",
            "%RANGE_OF_NO_OF_EMPLOYEES%",
            "%COMPANY_DESCRIPTION%",
            "%COMPANY_ADMINS_UL_HIDDEN%",
            "%COMPANY_ADMINS%",
            "%BANNER_IMAGE_SELECT_CONTAINER_HIDDEN_CLASS%",
            "%BANNER_IMAGE_PREVIEW_CONTAINER_HIDDEN_CLASS%",
            "%COMPANY_BANNER_IMAGE_URL%",
            "%WEBSITE_OF_COMPANY%",
            "%FOUNDATION_YEAR%",
            "%COMPANY_LOCATIONS%",
            "%ENCRYPTED_COMPANY_ID%",
            '%COMPANY_INDUSTRY_OPTIONS%',
            //'%COMPANY_SIZE_OPTIONS%',
            "%COMPANY_LOGO_URL_WEBP%",
            "%LOCATION%",
            "%LAT%",
            "%LNG%"
        );
        $logo_select_container_hidden_class = $banner_image_select_container_hidden_class = $logo_preview_container_hidden_class = $company_logo_url = $banner_image_preview_container_hidden_class = $banner_image_url = "";
        if ($this->company_logo == '') {
            $logo_preview_container_hidden_class = "hidden";
        } else {
            // $company_logo_url = SITE_UPD_COMPANY_LOGOS . "th2_" . $this->company_logo;
            // $img_arr= explode(".", $this->company_logo);
            // if(file_exists(DIR_UPD_COMPANY_LOGOS. "th2_" .$img_arr[0].".webp")){
            //     $company_logo_url_webp = SITE_UPD_COMPANY_LOGOS . "th2_" .$img_arr[0].".webp";
            // }else{
            //     $company_logo_url_webp='';
            // }

            $company_logo_url = $company_storage->getImageUrl1('av8db','th2_'.$this->company_logo,'company-logos-nct/');
            $is_image = getimagesize($company_logo_url);
            if(!empty($is_image)){
                $company_logo_url_webp = $company_logo_url;
                $company_logo_url = $company_logo_url;
            }else{
                $company_logo_url_webp = '';
                $company_logo_url = '';
            }
            
            $logo_select_container_hidden_class = "hidden";
        }
        if ($this->banner_image == '') {
            $banner_image_preview_container_hidden_class = "hidden";
        } else {
            // $banner_image_url = SITE_UPD_COMPANY_BANNER_IMAGES . "th1_" . $this->banner_image;
            $banner_image_url = $company_storage->getImageUrl1('av8db','th1_'.$this->banner_image,'company-banner-images-nct/');
            $is_image = getimagesize($banner_image_url);
            if(!empty($is_image)){
                $banner_image_url = $banner_image_url;
            }else{
                $banner_image_url = '';
            }
            $banner_image_select_container_hidden_class = "hidden";
        }
        $company_detail_page_url = get_company_detail_url($this->company_id);
        $edit_company_form_action_url = SITE_URL . "update-company-details/" . encryptIt($this->company_id);
        $company_admins_ul_hidden = "hidden";
        $company_admins = $this->getCompanyAdmins($this->company_id);
        if ($company_admins != "") {
            $company_admins_ul_hidden = "";
        }

        $f_year = ($this->foundation_year == 0) ? '' : $this->foundation_year;

        $fields_replace = array(
            $edit_company_form_action_url,
            $logo_select_container_hidden_class,
            $logo_preview_container_hidden_class,
            $company_logo_url,
            $company_detail_page_url,
            $this->company_name,
            $this->industry_name,
            $this->owner_email_address,
            $this->range_of_no_of_employees,
            $this->company_description,
            $company_admins_ul_hidden,
            $company_admins,
            $banner_image_select_container_hidden_class,
            $banner_image_preview_container_hidden_class,
            $banner_image_url,
            $this->website_of_company,
            $f_year,
            $this->getCompanyLocations($this->company_id),
            encryptIt($this->company_id),
            $this->getIndustriesDD(),
            //$this->getCompnaySizesDD(),
            $company_logo_url_webp,
            $this->location,
            $this->lat,
            $this->lng
        );
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }
    public function deleteCompany($company_id) {
        $response = array();
        $response['status'] = false;
        $this->db->delete('tbl_notifications',array('company_id'=>$company_id))->affectedRows();
        $this->db->delete('tbl_feeds',array('company_id'=>$company_id))->affectedRows();
        $this->db->delete('tbl_jobs',array('company_id'=>$company_id))->affectedRows();
        $image=$this->db->select("tbl_companies", array('company_logo','banner_image'), array("id" => $company_id))->results();

        if($image['company_logo'] != ''){
            unlink(SITE_UPD_COMPANY_LOGOS ."/".$image['company_logo']);
            unlink(SITE_UPD_COMPANY_LOGOS ."/th1_".$image['company_logo']);
            unlink(SITE_UPD_COMPANY_LOGOS ."/th2_".$image['company_logo']);
            $img_arr= explode(".",$image['company_logo']);
            unlink(SITE_UPD_COMPANY_LOGOS ."/th1_".$img_arr[0].".webp");
            unlink(SITE_UPD_COMPANY_LOGOS ."/th2_".$img_arr[0].".webp");

        }
        if($image['banner_image'] != ''){
            unlink(SITE_UPD_COMPANY_BANNER_IMAGES ."/".$image['banner_image']);
            unlink(SITE_UPD_COMPANY_BANNER_IMAGES ."/th1_".$image['banner_image']);
            $img_arr= explode(".",$image['banner_image']);
            unlink(SITE_UPD_COMPANY_BANNER_IMAGES ."/th1_".$img_arr[0].".webp");


        }

        //$affectedRows = $this->db->delete("tbl_companies", array("id" => $company_id))->affectedRows();
       $affectedRows = $this->db->update('tbl_companies',array('company_type'=>'e','company_logo'=>'','banner_image'=>'','company_description'=>'','owner_email_address'=>'','website_of_company'=>'','foundation_year'=>''),array('id'=>$company_id));

        if ($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['success'] = ERROR_COMPANY_HAS_BEEN_DELTED_SUCCESSFULLY;
        } else {
            $response['error'] = ERROR_SOMETHING_GOES_WRONG_WHILE_DELTING_COMPANY;
        }
        return $response;
    }
    public function getIndustriesDD() {
        $final_result = NULL;
        $industries = $this->db->select("tbl_industries", array('id','industry_name_'.$this->lId), array("status" => "a"))->results();
        if ($industries) {
            $getSelectBoxOption = $this->getSelectBoxOption();
            $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");
            for ($i = 0; $i < count($industries); $i++) {
                $selected = ($this->inid == $industries[$i]['id'] ? "selected='selected'" : '');
                $fields_replace = array(
                    filtering($industries[$i]['id'], 'input', 'int'),
                    $selected,
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
                
    //             $selected = ($this->company_size_id == $company_sizes[$i]['id'] ? "selected='selected'" : '');
    //             $company_size_title = filtering($company_sizes[$i]['company_size_'.$this->lId], 'input', 'int');
    //             $fields_replace = array(
    //                 filtering($company_sizes[$i]['id'], 'input', 'int'),
    //                 $selected,
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
} ?>