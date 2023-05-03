<?php

class LicensesEndorsements extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_license_endorsements';
        $this->get_languages = get_languages('active');
        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $qrySel = $this->db->select($this->table, "*", array("id" => $id))->result();
            $fetchRes = $qrySel;
            $this->data=$fetchRes;
//            print_r($this->data);exit();
            $this->data['licenses_endorsements_name'] = $this->licenses_endorsements_name = filtering($fetchRes['licenses_endorsements_name']);
            $this->data['licenses_endorsements_name_1'] = $this->licenses_endorsements_name_1 = filtering($fetchRes['licenses_endorsements_name_1']);
            $this->data['licenses_endorsements_name_2'] = $this->licenses_endorsements_name_2 = filtering($fetchRes['licenses_endorsements_name_2']);
            $this->data['licenses_endorsements_name_3'] = $this->licenses_endorsements_name_3 = filtering($fetchRes['licenses_endorsements_name_3']);
            $this->data['flight_hours'] = $this->flight_hours = filtering($fetchRes['flight_hours']);
            $this->data['is_default'] = $this->is_default = filtering($fetchRes['is_default']);
            $this->data['isCommercial'] = $this->isCommercial = filtering($fetchRes['isCommercial']);
            $this->data['isLicense'] = $this->isLicense = filtering($fetchRes['isLicense']);
            $this->data['isBoth'] = $this->isBoth = filtering($fetchRes['isBoth']);
            $this->data['isNone'] = $this->isNone = filtering($fetchRes['isNone']);
            $this->data['status'] = $this->status = filtering($fetchRes['isActive']);
            
        } else {
            $this->data['licenses_endorsements_name'] = $this->licenses_endorsements_name = '';
            $this->data['licenses_endorsements_name_1'] = $this->licenses_endorsements_name_1 = '';
            $this->data['licenses_endorsements_name_2'] = $this->licenses_endorsements_name_2 = '';
            $this->data['licenses_endorsements_name_3'] = $this->licenses_endorsements_name_3 = '';
            $this->data['flight_hours'] = $this->flight_hours = '';
            $this->data['is_default'] = $this->is_default = 'n';
            $this->data['isCommercial'] = $this->isCommercial = 'n';
            $this->data['isLicense'] = $this->isLicense = 'n';
            $this->data['isBoth'] = $this->isBoth = 'n';
            $this->data['isNone'] = $this->isNone = 'n';
            $this->data['status'] = $this->status = 'y';
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

    $licenses_endorsement_name = $content = '';
        foreach ($this->get_languages as $key => $value) {
            $licenses_endorsement_name .= $this->displayBox(array("label" => "Licenses & Endorsements(".$value['languageName']."):", "value" => $this->data['licenses_endorsements_name_'.$value['id']]));
        }
        $content .= $licenses_endorsement_name;
        $content .= 
                //$this->displayBox(array("label" => "Flight Hours &nbsp;:", "value" => $this->flight_hours)) .
                // $this->displayBox(array("label" => "Licenses &nbsp;:", "value" => $this->isLicense == 'y' ? 'Yes' : 'No')) .
                // $this->displayBox(array("label" => "Both &nbsp;:", "value" => $this->isBoth == 'y' ? 'Yes' : 'No')) .
                // $this->displayBox(array("label" => "None &nbsp;:", "value" => $this->isNone == 'y' ? 'Yes' : 'No')) .
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'y' ? 'Active' : 'Deactive'));
        return $content;
    }

    public function getForm() {

        $content = $licenses_endorsement = $licenses_endorsements_name_1=$licenses_endorsements_name_2 =$licenses_endorsements_name_3=$licenses_endorsements_name = '';

        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();

        $textbox = new Templater(DIR_ADMIN_TMPL . "/textbox-nct.tpl.php");
        $textbox = $textbox->parse();

        $status_a = ($this->status == 'y' ? 'checked' : '');
        $status_d = ($this->status == 'n' ? 'checked' : '');
        
        $qrySel = $this->db->select("tbl_language", array("id","languageName","created_date"),array("status"=>'a'))->results();

        $search = array('%ID%','%LABEL%','%FIELD_NAME%','%FIELD_VALUE%');
        foreach ($qrySel as $lang) {
            $box_label = MEND_SIGN.'Enter Licenses & Endorsements Name('.$lang['languageName'].')';
            $box_field_name = 'licenses_endorsement_name';
            $box_field_value = $this->id > 0 ? $this->data['licenses_endorsements_name_'.$lang['id']] : '';
            $replace = array($lang['id'],$box_label,$box_field_name,$box_field_value);
            $licenses_endorsement .= str_replace($search, $replace, $textbox);
        }

        $isCommercial = ($this->isCommercial == 'y' ? 'checked' : '');
        $isLicense = ($this->isLicense == 'y' ? 'checked' : '');
        $isBoth = ($this->isBoth == 'y' ? 'checked' : '');
        $isNone = ($this->isNone == 'y' ? 'checked' : '');
        $isDefault = ($this->is_default == 'y') ? 'hide' : '';
        $fields = array(
            "%MEND_SIGN%",
            "%LICENSES_ENDORSEMENT%",
            "%LICENSES_ENDORSEMENT_NAME%",
            "%LICENSES_ENDORSEMENT_NAME_1%",
            "%LICENSES_ENDORSEMENT_NAME_2%",
            "%LICENSES_ENDORSEMENT_NAME_3%",
            "%FLIGHT_HOURS%",
            "%ISCOMMERCIAl%",
            "%ISLICENSE%",
            "%ISBOTH%",
            "%ISNONE%",
            "%STATUS_A%",
            "%STATUS_D%",
            "%TYPE%",
            "%ID%",
            "%ISDEFAULT%"
        );
        
        $fields_replace = array(
            MEND_SIGN,
            $licenses_endorsement,
            $licenses_endorsements_name,
            $licenses_endorsements_name_1,
            $licenses_endorsements_name_2,
            $licenses_endorsements_name_3,
            $this->flight_hours,
            $isCommercial,
            $isLicense,
            $isBoth,
            $isNone,
            $status_a,
            $status_d,
            $this->type,
            $this->id,
            $isDefault
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
            $whereCond .= " WHERE (licenses_endorsements_name_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%')";
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
            $status = ($fetchRes['isActive'] == "y") ? "checked" : "";
            $disable = 'disabled="disabled"';
            $default_status = ($fetchRes['is_default']=='y')?"checked":"";
            $is_default = (isset($fetchRes['is_default']))?$fetchRes['is_default']:"";

            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status,'extraAtt'=>'data-switch_action="update_status" '.(($fetchRes['is_default'] == 'y')?$disable : ''))) : '';
            $operation = '';

            $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';            
            $operation .=(in_array('delete', $this->Permission) && $is_default != 'y') ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $id . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';

            // $licenses_endorsements_name = filtering($fetchRes['licenses_endorsements_name']);
            // $licenses_endorsements_name_1 = filtering($fetchRes['licenses_endorsements_name_1']);
            // $licenses_endorsements_name_2 = filtering($fetchRes['licenses_endorsements_name_2']);
            // $licenses_endorsements_name_3 = filtering($fetchRes['licenses_endorsements_name_3']);
            
            $final_array = array(
                filtering($id),
                filtering($fetchRes["licenses_endorsements_name_".DEFAULT_LANGUAGE_ID])
            );
            
            if (in_array('status', $this->Permission)) {
                $final_array = array_merge($final_array, array($switch));
            }
            if (in_array('edit', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) {
                $final_array = array_merge($final_array, array($operation));
            }
            //print_r($final_array);exit();
            $row_data[] = $final_array;
        }
        $result["sEcho"] = $sEcho;
        $result["iTotalRecords"] = (int) $totalRow;
        $result["iTotalDisplayRecords"] = (int) $totalRow;
        $result["aaData"] = $row_data;
        return $result;
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
