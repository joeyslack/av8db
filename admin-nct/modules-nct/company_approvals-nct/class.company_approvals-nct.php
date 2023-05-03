<?php

class CompanyApprovals extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_companies';
        $this->get_languages = get_languages('active');
        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $qrySel = $this->db->select($this->table, "*", array("id" => $id))->result();
            $sql = "SELECT c.*, concat_ws(' ', u.first_name, u.last_name) as user_name,a.airport_name,i.industry_name  FROM " . $this->table . " c 
                LEFT JOIN tbl_users u ON u.id =  c.user_id LEFT JOIN tbl_airport a ON a.id = c.closest_airport_id LEFT JOIN tbl_industries as i ON i.id=c.company_industry_id WHERE c.id = '" . $this->id . "' ";
            $companyDetails = $this->db->pdoQuery($sql)->result();
            $fetchRes = $companyDetails;
            $this->data=$fetchRes;
            //_print_r($this->data);exit();
            
            $this->data['company_name'] = $this->company_name = filtering($fetchRes['company_name']);
            $this->data['owner_email_address'] = $this->owner_email_address = filtering($fetchRes['owner_email_address']);
            $this->data['website_of_company'] = $this->website_of_company = filtering($fetchRes['website_of_company']);
            $this->data['company_type'] = $this->company_type = filtering($fetchRes['company_type']);
            $this->data['airport_name'] = $this->airport_name = isset($fetchRes['airport_name']) ? $fetchRes['airport_name'] : '-';
            $this->data['industry_name'] = $this->industry_name = filtering($fetchRes['industry_name']);
            $this->data['location'] = $this->location = isset($fetchRes['location']) ? $fetchRes['location'] : '';
            $this->data['status'] = $this->status = filtering($fetchRes['status']);
        } else {
            
            $this->data['status']           = $this->status = 'a';
        }
        switch ($type) {
            case 'add' : {
                    $this->data['content'] = $this->getForm();
                    break;
                }
            case 'edit' : {
                    $this->data['content'] = $this->getForm();
                    break;
                }
            case 'view' : {
                    $this->data['content'] = $this->viewForm();
                    break;
                }
            case 'delete' : {
                    $this->data['content'] = json_encode($this->dataGrid());
                    break;
                }
            case 'datagrid' : {
                    $this->data['content'] = json_encode($this->dataGrid());
                }
        }
    }

    public function viewForm() {

        $company_name = $company_type =$content = '';

        if($this->company_type == 'r')
            $company_type = 'Regular';
        else if($this->company_type == 'e')
            $company_type = 'Experience';
        else if($this->company_type == 'j')
            $company_type = 'Created while posting job';
        else
            $company_type = '-';
        $content .= 
                $this->displayBox(array("label" => "Business Name &nbsp;:", "value" => $this->company_name)) .
                $this->displayBox(array("label" => "Business Email ID &nbsp;:", "value" => $this->owner_email_address)) .
                $this->displayBox(array("label" => "Business URL &nbsp;:", "value" => $this->website_of_company)) .
                $this->displayBox(array("label" => "Closest Airport &nbsp;:", "value" => $this->airport_name)) .
                $this->displayBox(array("label" => "Location &nbsp;:","value"=>$this->location)) .
                $this->displayBox(array("label" => "Business Type &nbsp;:","value"=>$this->industry_name)) .
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive'));
        return $content;
    }

    public function dataGrid() {

        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);

        $whereCond = '';
        if (isset($chr) && $chr != '') {
            $whereCond .= " AND (c.company_name LIKE '%".$chr."%')";
        }
        if (isset($sort))
            $sorting = $sort . ' ' . $order;
        else
            $sorting = 'c.id DESC';

        $sql = "SELECT c.*,a.airport_name,i.industry_name FROM tbl_companies as c 
        LEFT JOIN tbl_airport a ON a.id = c.closest_airport_id 
        LEFT JOIN tbl_industries as i ON i.id=c.company_industry_id
        WHERE c.isCompanyEmailVerify ='y' AND c.company_type = 'r'" . $whereCond . " order by " . $sorting;

        $sql_with_limit = $sql . " LIMIT " . $offset . " ," . $rows . " ";
       // print_r($sql);exit();
        $getTotalRows = $this->db->pdoQuery($sql)->results();
        $totalRow = count($getTotalRows);

        $qrySel = $this->db->pdoQuery($sql_with_limit)->results();
         //echo "<pre>";print_r($qrySel);exit();
        foreach ($qrySel as $fetchRes) {
          
            $id = $fetchRes['id'];
            $status = ($fetchRes['status'] == "a") ? "checked" : "";
            
            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status)) : '';
            $operation = $admin_approvals = $admin_active_deactive = '';

            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';            
                
            $admin_approvals = ($fetchRes['isAdminVerify'] == 'y') ? 'Accepted' : (($fetchRes['isAdminVerify'] == 'n') ? 'Rejected' : " <button com_id='".$fetchRes["id"]."' statusType='y' class='btn green acceptRejectStatus'>Accept</button>"
                . " <button com_id='".$fetchRes["id"]."' statusType='n' class='btn red acceptRejectStatus'>Reject</button>");

            $admin_active_deactive = ($fetchRes['adminActiveDeactive'] == 'a') ? 'Activated' : (($fetchRes['adminActiveDeactive'] == 'd') ? 'Deactivate' : " <button com_id='".$fetchRes["id"]."' statusType='a' class='btn green activateDeactivateStatus'>Activate</button>"
                . " <button com_id='".$fetchRes["id"]."' statusType='d' class='btn red activateDeactivateStatus'>Deactivate</button>");

            $company_name       = isset($fetchRes['company_name']) ? $fetchRes['company_name'] : '-';
            $company_email = isset($fetchRes['owner_email_address']) ? $fetchRes['owner_email_address'] : '-';
            $company_website           = isset($fetchRes['website_of_company']) ? $fetchRes['website_of_company'] : '-';
            $closest_airport           = isset($fetchRes['airport_name']) ? $fetchRes['airport_name'] : '-';
            
            $industry_name           = isset($fetchRes['industry_name']) ? $fetchRes['industry_name'] : '-';

            $location='-';

            if($fetchRes['location']!="" && $fetchRes['lat']!="" && $fetchRes['lng']!=""){
                $location=$fetchRes['location']." <a href='javascript:void(0);' data-lat='".$fetchRes['lat']."' data-lng='".$fetchRes['lng']."' class='view_on_map'>View On Map</a>";
            }
             
            
            $final_array = array(
                filtering($id),
                filtering($company_name),
                filtering($company_email),
                filtering($company_website),
                filtering($closest_airport),
                ($location),
                filtering($industry_name)
            );
            
            if (in_array('status', $this->Permission)) {
                $final_array = array_merge($final_array, array($admin_approvals));
                $final_array = array_merge($final_array, array($switch));
//                $final_array = array_merge($final_array, array($admin_active_deactive));
            }
            if (in_array('view', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) {
                $final_array = array_merge($final_array, array($operation));
            }
            $row_data[] = $final_array;
            //echo "<pre>";print_r($row_data);
        }
        $result["sEcho"] = $sEcho;
        $result["iTotalRecords"] = (int) $totalRow;
        $result["iTotalDisplayRecords"] = (int) $totalRow;
        $result["aaData"] = $row_data;
        return $result;
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

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->breadcrumb = $this->getBreadcrumb();

        $main_content_parsed = $final_result = $main_content->parse();

        $comp_request =$this->db->select("tbl_adminrole", "isRequestReceive", array("id" =>'124'))->result();
        $isRequestReceive = '';
        $isRequestReceive = ($comp_request['isRequestReceive'] == 'y') ? 'checked' : '';

        $fields = array(
            "%VIEW_ALL_RECORDS_BTN%",
            "%SWITCH1%",
            "%ISREQUESTRECEIVE%"
        );

        //_print($_GET);exit;

        $view_all_records_btn = '';
        if (( isset($_GET['day']) && $_GET['day'] != '' ) || ( isset($_GET['month']) && $_GET['month'] != '' ) || ( isset($_GET['year']) && $_GET['year'] != '' ) || ( isset($_GET['job_id']) && $_GET['job_id'] > 0 )) {
            $view_all_records_btn = $this->getViewAllBtn();
        }

        $switch1 = '';
        $switch1 = $this->toggel_switch(array("action" => "ajax." . $this->module . ".php", "check" => 'a'));

        $fields_replace = array(
            $view_all_records_btn,
            $switch1,
            $isRequestReceive
        );
        //echo "<pre>";print_r($fields_replace);exit();
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getNoOFApplicants($job_id) {
        return getTotalRows('tbl_job_applications', 'job_id = "'. $job_id .'"');
    }

    public function getViewAllBtn() {
        $content = '';

        $view_all_btn = new Templater(DIR_ADMIN_TMPL . "/view-all-btn-nct.tpl.php");
        $view_all_btn->set('module_url', SITE_ADM_MOD . $this->module);

        $content = $view_all_btn->parse();

        return $content;
    }

    public function img($text) {
        $text['href'] = isset($text['href']) ? $text['href'] : '';
        $text['src'] = isset($text['src']) ? $text['src'] : 'Enter Image Path Here: ';
        $text['name'] = isset($text['name']) ? $text['name'] : '';
        $text['id'] = isset($text['id']) ? $text['id'] : '';
        $text['class'] = isset($text['class']) ? '' . trim($text['class']) : '';
        $text['height'] = isset($text['height']) ? '' . trim($text['height']) : '';
        $text['width'] = isset($text['width']) ? '' . trim($text['width']) : '';
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
        $text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : '';

        if ($text['onlyField'] == true) {
            $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/img_onlyfield.tpl.php");
            $main_content = $main_content->parse();
        } else {
            $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/img.tpl.php");
            $main_content = $main_content->parse();
        }
        $fields = array("%HREF%", "%SRC%", "%CLASS%", "%ID%", "%ALT%", "%WIDTH%", "%HEIGHT%", "%EXTRA%");
        $fields_replace = array($text['href'], $text['src'], $text['class'], $text['id'], $text['name'], $text['width'], $text['height'], $text['extraAtt']);
        return str_replace($fields, $fields_replace, $main_content);
    }

}