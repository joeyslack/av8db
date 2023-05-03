<?php

class Airports extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_airport';
        $this->get_languages = get_languages('active');
        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $qrySel = $this->db->select($this->table, "*", array("id" => $id))->result();
            $fetchRes = $qrySel;
            $this->data=$fetchRes;
//            print_r($this->data);exit();
            $this->data['country_id'] = $this->country_id = filtering($fetchRes['country_id']);
            $this->data['state_id'] = $this->state_id = filtering($fetchRes['state_id']);
            $this->data['city_id'] = $this->city_id = filtering($fetchRes['city_id']);
            $this->data['location'] = $this->location = filtering($fetchRes['location']);
            $this->data['airport_identifier'] = $this->airport_identifier = filtering($fetchRes['airport_identifier']);
            $this->data['airport_name'] = $this->airport_name = filtering($fetchRes['airport_name']);
            $this->data['status'] = $this->status = filtering($fetchRes['status']);
            
        } else {
            $this->data['country_id']       = $this->country_id = '';
            $this->data['state_id']         = $this->state_id = '';
            $this->data['city_id']          = $this->city_id = '';
            $this->data['location']         = $this->location = '';
            $this->data['airport_identifier'] = $this->airport_identifier = '';
            $this->data['airport_name']     = $this->airport_name = '';
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

        $airport_name = $content = '';
        
        foreach ($this->get_languages as $key => $value) {
            $airport_name .= $this->displayBox(array("label" => "Airport Name(".$value['languageName']."):", "value" => $this->data['airport_name_'.$value['id']]));
        }
        $content .= $airport_name;
        $content .= 
                $this->displayBox(array("label" => "Airport ICAO Code &nbsp;:", "value" => $this->airport_identifier)) .
                $this->displayBox(array("label" => "Location &nbsp;:", "value" => $this->location)) .
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive'));
        return $content;
    }

    public function getForm() {

        $content = $country_option = $state_option = $city_option = $airport_name1 ='';

        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        //country drop down
        $qrySelCountry = $this->db->pdoQuery("SELECT * FROM tbl_country where isActive='y' ORDER BY countryName ASC")->results();
        
        foreach ($qrySelCountry as $fetchRes) {
        
            $selected = ($this->country_id == $fetchRes['CountryId']) ? "selected" : "";

            $fields_replace = array(
                filtering($fetchRes['CountryId'], 'output', 'int'),
                $selected,
                filtering($fetchRes['countryName'])
            );
            $country_option .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }

        //State dropdown
        $qrySelState = $this->db->pdoQuery("SELECT * FROM tbl_state where CountryID ='" .$this->country_id."' AND isActive='y' ORDER BY stateName")->results();
        foreach ($qrySelState as $fetchRes) {
            $selected = ($this->state_id == $fetchRes['StateID']) ? "selected" : "";

            $fields_replace = array(
                filtering($fetchRes['StateID'], 'output', 'int'),
                $selected,
                filtering($fetchRes['stateName'])
            );
            $state_option .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }

        //City dropdown
        $qrySelState = $this->db->pdoQuery("SELECT * FROM tbl_city where StateID='". $this->state_id."' AND isActive='y' ORDER BY cityName")->results();

        foreach ($qrySelState as $fetchRes) {
            $selected = ($this->city_id == $fetchRes['CityId']) ? "selected" : "";

            $fields_replace = array(
                filtering($fetchRes['CityId'], 'output', 'int'),
                $selected,
                filtering($fetchRes['cityName'])
            );
            $city_option .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }

        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();

        $textbox = new Templater(DIR_ADMIN_TMPL . "/textbox-nct.tpl.php");
        $textbox = $textbox->parse();

        $search = array('%ID%','%LABEL%','%FIELD_NAME%','%FIELD_VALUE%');
        foreach ($this->get_languages as $lang) {
            $box_label = MEND_SIGN.'Enter Airport Name('.$lang['languageName'].')';
            $box_field_name = 'airport_name';
            $box_field_value = $this->id > 0 ? $this->data['airport_name_'.$lang['id']] : '';
            $replace = array($lang['id'],$box_label,$box_field_name,$box_field_value);
            $airport_name1 .= str_replace($search, $replace, $textbox);
            
        }

        $status_a = ($this->status == 'a' ? 'checked' : '');
        $status_d = ($this->status != 'a' ? 'checked' : '');

        $fields = array(
            "%MEND_SIGN%",
            "%COUNTRY_OPTION%",
            "%STATE_OPTION%",
            "%CITY_OPTION%",
            "%LOCATION%",
            "%AIRPORT_IDENTIFIER%",
            "%AIRPORT_NAME%",
            "%STATUS_A%",
            "%STATUS_D%",
            "%TYPE%",
            "%ID%");

        $fields_replace = array(
            MEND_SIGN,
            filtering($country_option, 'output', 'text'),
            filtering($state_option, 'output', 'text'),
            filtering($city_option, 'output', 'text'),
            filtering($this->location),
            filtering($this->airport_identifier),
            $airport_name1,
            filtering($status_a),
            filtering($status_d),
            filtering($this->type),
            filtering($this->id, 'output', 'int')
        );
        
        $content = str_replace($fields, $fields_replace, $main_content);
        return sanitize_output($content);
    }

    public function dataGrid() {

        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);

        $whereCond = '';
        if (isset($chr) && $chr != '') {
            $whereCond .= " WHERE (airport_name_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%' OR airport_identifier LIKE '%".$chr."%')";
        }
        if (isset($sort))
            $sorting = $sort . ' ' . $order;
        else
            $sorting = 'id DESC';

        $sql = "SELECT * FROM ".$this->table . " " . $whereCond . " order by " . $sorting;

        $sql_with_limit = $sql . " LIMIT " . $offset . " ," . $rows . " ";

        $getTotalRows = $this->db->pdoQuery($sql)->results();
        $totalRow = count($getTotalRows);

        $qrySel = $this->db->pdoQuery($sql_with_limit)->results();

        foreach ($qrySel as $fetchRes) {
            //echo "<pre>";print_r($fetchRes);exit();
            $id = $fetchRes['id'];
            $status = ($fetchRes['status'] == "a") ? "checked" : "";
            
            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status)) : '';
            $operation = '';

            $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';            
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $id . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';

            $airport_name       = isset($fetchRes['airport_name']) ? $fetchRes['airport_name'] : '-';
            $airport_identifier = isset($fetchRes['airport_identifier']) ? $fetchRes['airport_identifier'] : '-';
            $location           = isset($fetchRes['location']) ? $fetchRes['location'] : '-';
            
            $final_array = array(
                filtering($id),
                filtering($fetchRes["airport_name_".DEFAULT_LANGUAGE_ID]),
                filtering($airport_identifier),
                filtering($location)
            );
            
            if (in_array('status', $this->Permission)) {
                $final_array = array_merge($final_array, array($switch));
            }
            if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) {
                $final_array = array_merge($final_array, array($operation));
            }
            $row_data[] = $final_array;
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

        $fields = array(
            "%VIEW_ALL_RECORDS_BTN%"
        );

        //_print($_GET);exit;

        $view_all_records_btn = '';
        if (( isset($_GET['day']) && $_GET['day'] != '' ) || ( isset($_GET['month']) && $_GET['month'] != '' ) || ( isset($_GET['year']) && $_GET['year'] != '' ) || ( isset($_GET['job_id']) && $_GET['job_id'] > 0 )) {
            $view_all_records_btn = $this->getViewAllBtn();
        }

        $fields_replace = array(
            $view_all_records_btn
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getNoOFApplicants($job_id) {
        return getTotalRows('tbl_job_applications', 'job_id = "'. $job_id .'"');
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
