<?php

class membershipplan extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields, $sessCataId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_tariff_plans';
        $this->get_languages = get_languages();
        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();
        if ($this->id > 0) {

            $query = "SELECT *, 
                    IF(plan_type = 'r', 'Regular', IF(plan_type = 'ah', 'Ad hoc inmails', 'Featured Job' ) ) as plan_type_text, 
                    IF(plan_type = 'r', concat_ws(' ',plan_duration, IF( plan_duration_unit = 'm', IF( plan_duration > 1, 'Months', 'Month' ), IF( plan_duration > 1, 'Years', 'Year' ) ) ), '-' ) as plan_duration_text, 
                    IF(plan_type = 'r', no_of_inmails, '-' ) as no_of_inmails_custom, 
                    concat_ws('', '" . CURRENCY_SYMBOL . "',price) as price_custom 
                    FROM " . $this->table . " 
                    WHERE id = '" . $this->id . "' ";

            $plan_details = $this->db->pdoQuery($query)->result();
            //echo "<pre>";print_r($plan_details);exit;
            $this->data = $plan_details;
            $this->plan_type = filtering($plan_details['plan_type']);
            $this->plan_name = filtering($plan_details['plan_name']);
            $this->plan_description = filtering($plan_details['plan_description']);
            $this->plan_duration = filtering($plan_details['plan_duration'], 'output', 'int');
            $this->plan_duration_unit = filtering($plan_details['plan_duration_unit']);
            $this->no_of_inmails = isset($plan_details['no_of_inmails']) ? $plan_details['no_of_inmails'] : '1' ;
            $this->price = filtering($plan_details['price'], 'output', 'float');

            $this->status = filtering($plan_details['status']);

            $this->plan_type_text = filtering($plan_details['plan_type_text']);
            $this->plan_duration_text = filtering($plan_details['plan_duration_text']);
            $this->no_of_inmails_custom = filtering($plan_details['no_of_inmails_custom']);
            $this->price_custom = filtering($plan_details['price_custom']);

            $this->added_on = convertDate("onlyDate", $plan_details['added_on']);
            $this->updated_on = convertDate("onlyDate", $plan_details['updated_on']);
        } else {
            $this->plan_type = '';
            $this->plan_name = '';
            $this->plan_description = '';
            $this->plan_duration = '';
            $this->plan_duration_unit = '';
            $this->no_of_inmails = '';
            $this->price = '';

            $this->status = 'a';

            $this->plan_type_text = '';
            $this->plan_duration_text = '';
            $this->no_of_inmails_custom = '';
            $this->price_custom = '';

            $this->added_on = '';
            $this->updated_on = '';
        }
        switch ($type) {
            case 'edit' : {
                    $this->data['content'] = $this->getForm();
                    break;
                }
            case 'view' : {
                    $this->data['content'] = $this->viewForm();
                    break;
                }
            case 'datagrid' : {
                    $this->data['content'] = json_encode($this->dataGrid());
                }
        }
    }

    public function viewForm() {


        $name_content = $description_content = $content = '';
        foreach ($this->get_languages as $key => $value) {
            $name = filtering($this->data['plan_name_'.$value['id']]);
            $description = filtering($this->data['plan_description_'.$value['id']]);
            $name_content .= $this->displayBox(array("label" => "Plan Name(".$value['languageName'].") &nbsp;:", "value" => $name));
            $description_content .= $this->displayBox(array("label" => "Description (".$value['languageName'].") &nbsp;:", "value" => $description));
        }
        $content .= $name_content;
        $content .= $description_content;

        $content .= 
                $this->displayBox(array("label" => "Plan Type&nbsp;:", "value" => filtering($this->plan_type_text))) .
                $this->displayBox(array("label" => "Plan Duration&nbsp;:", "value" => filtering($this->plan_duration_text))) .
                $this->displayBox(array("label" => "No. of in mails&nbsp;:", "value" => filtering($this->no_of_inmails_custom))) .
                $this->displayBox(array("label" => "Price&nbsp;:", "value" => filtering($this->price_custom))) .
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive')) .
                $this->displayBox(array("label" => "Added on&nbsp;:", "value" => $this->added_on)) .
                $this->displayBox(array("label" => "Updated on&nbsp;:", "value" => $this->updated_on));
        return $content;
    }
    
    public function getRegularPlanDetails() {
        $content = '';
        
        $regular_plan_details_tpl = new Templater(DIR_ADMIN_TMPL . $this->module . "/regular-plan-details-nct.tpl.php");
        $content = $regular_plan_details_tpl->parse();
        return $content;
    }

    public function getPriceForDuration() {
        $content = '';

        $featured_jobs = $this->db->select("tbl_tariff_plans", "*", array("plan_type" => "fj"))->results();
        if($featured_jobs) {
            $featured_job_duration_price = new Templater(DIR_ADMIN_TMPL . $this->module . "/featured-job-duration-price-nct.tpl.php");
            $featured_job_duration_price_parsed = $featured_job_duration_price->parse();

            $fields = array(
                "%MEND_SIGN%",
                "%DURATION%",
                "%PRICE%",
                "%PLAN_ID%"
            );

            foreach($featured_jobs as $single_job) {
                $plan_duration = filtering($single_job['plan_duration'], 'output', 'int');
                $plan_duration_unit = filtering($single_job['plan_duration_unit']);

                $plan_duration_unit_text = '';

                switch($plan_duration_unit) {
                    case "d": {
                        $plan_duration_unit_text = 'Day(s)';
                        break;
                    }
                    case "w": {
                        $plan_duration_unit_text = 'Week(s)';
                        break;
                    }
                    case "m": {
                        $plan_duration_unit_text = 'Month(s)';
                        break;
                    }
                    case "y": {
                        $plan_duration_unit_text = 'Year(s)';
                        break;
                    }
                    case "n": {
                        $plan_duration_unit_text = '';
                        break;
                    }
                }

                $fields_replace = array(
                    MEND_SIGN,
                    $plan_duration." ". $plan_duration_unit_text,
                    filtering($single_job['price'], 'output', 'float'),
                    filtering($single_job['id'], 'output', 'int')
                );

                $content .= str_replace($fields, $fields_replace, $featured_job_duration_price_parsed);
            }
        }

        return $content;
    }

    public function getForm($plan_type = 'r') {
        $content = $plan_name = $plan_description ='';

        if($plan_type == 'ah' || $plan_type == 'fj') {
            $query = "SELECT *, 
                IF(plan_type = 'r', 'Regular', IF(plan_type = 'ah', 'Ad hoc inmails', 'Featured Job' ) ) as plan_type_text, 
                IF(plan_type = 'r', concat_ws(' ',plan_duration, IF( plan_duration_unit = 'm', IF( plan_duration > 1, 'Months', 'Month' ), IF( plan_duration > 1, 'Years', 'Year' ) ) ), '-' ) as plan_duration_text, 
                IF(plan_type = 'r', no_of_inmails, '-' ) as no_of_inmails_custom, 
                concat_ws('', '" . CURRENCY_SYMBOL . "',price) as price_custom 
                FROM " . $this->table . " 
                WHERE plan_type = '" . $plan_type . "' ";

            $plan_details = $this->db->pdoQuery($query)->result();

            $this->plan_type = filtering($plan_details['plan_type']);
            $this->plan_name = filtering($plan_details['plan_name']);
            $this->plan_description = filtering($plan_details['plan_description']);
            $this->plan_duration = filtering($plan_details['plan_duration'], 'output', 'int');
            $this->plan_duration_unit = filtering($plan_details['plan_duration_unit']);
            $this->no_of_inmails = isset($plan_details['no_of_inmails']) ? $plan_details['no_of_inmails'] : '1';
           // $this->no_of_inmails = filtering($plan_details['no_of_inmails'], 'output', 'int');
            $this->price = filtering($plan_details['price'], 'output', 'float');
            $this->status = filtering($plan_details['status']);
            $this->plan_type_text = filtering($plan_details['plan_type_text']);
            $this->plan_duration_text = filtering($plan_details['plan_duration_text']);
            $this->no_of_inmails_custom = filtering($plan_details['no_of_inmails_custom']);
            $this->price_custom = filtering($plan_details['price_custom']);
            $this->added_on = convertDate("onlyDate", $plan_details['added_on']);
            $this->updated_on = convertDate("onlyDate", $plan_details['updated_on']);
            $this->type = "edit";
            $this->id = filtering($plan_details['id'], 'output', 'int');
        }
        

        if ($plan_type == 'r') {
            $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
            $main_content->set('regular_plan_details', $this->getRegularPlanDetails());

            $textarea = new Templater(DIR_ADMIN_TMPL . "/textarea-nct.tpl.php");
            $textarea = $textarea->parse();
            $textbox = new Templater(DIR_ADMIN_TMPL . "/textbox-nct.tpl.php");
            $textbox = $textbox->parse();
            $search = array('%ID%','%LABEL%','%FIELD_NAME%','%FIELD_VALUE%');

            foreach ($this->get_languages as $lang) {
                $box_label = MEND_SIGN.'Plan name('.$lang['languageName'].')';
                $box_field_name = 'plan_name';
                $box_field_value = $this->id > 0 ? $this->data['plan_name_'.$lang['id']] : '';
                $replace = array($lang['id'],$box_label,$box_field_name,$box_field_value);
                $plan_name .= str_replace($search, $replace, $textbox);

                $area_label = MEND_SIGN.'Plan description('.$lang['languageName'].')';
                $area_field_name = 'plan_description';
                $area_field_value = $this->id > 0 ? $this->data['plan_description_'.$lang['id']] : '';
                $area_replace = array($lang['id'],$area_label,$area_field_name,$area_field_value);
                $plan_description .= str_replace($search, $area_replace, $textarea);
            }

        } else if ($plan_type == 'fj') {
            $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/featured-job-form-nct.tpl.php");
            $main_content->set('duration_and_price', $this->getPriceForDuration());
        } else if ($plan_type == 'ah') {
            $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/adhoc-inmails-form-nct.tpl.php");
        }
        
        $main_content_parsed = $main_content->parse();

        $status_a = ($this->status == 'a' ? 'checked' : '');
        $status_d = ($this->status != 'a' ? 'checked' : '');

        $plan_duration_m_checked = ($this->plan_duration_unit == 'm' ? 'checked' : '');
        $plan_duration_y_checked = ($this->plan_duration_unit == 'y' ? 'checked' : '');
        $hide_no_of_inmails = ($this->plan_type == 'r' && $this->plan_name == 'plan_name' || $this->id == '8') ? 'hide' : '';
        $fields = array(
            "%TITLE%",
            "%DESRIPTION%",
            "%MEND_SIGN%",
            "%PLAN_DURATION%",
            "%PLAN_DURATION_M_CHECKED%",
            "%PLAN_DURATION_Y_CHECKED%",
            "%NO_OF_INMAILS%",
            "%PRICE%",
            "%STATUS_A%",
            "%STATUS_D%",
            "%TYPE%",
            "%ID%",
            "%PLAN_NAME_R%",
            "%PLAN_DESCRIPTION_R%",
            "%HIDE_NO_OF_INMAILS%",
            "%A%"
        );
        $no_of_inmails = ($this->no_of_inmails == '0') ? '1' : '2';
        $fields_replace = array(
            $plan_name,
            $plan_description,
            MEND_SIGN,
            $this->plan_duration,
            $plan_duration_m_checked,
            $plan_duration_y_checked,
            $no_of_inmails,
            $this->price,
            $status_a,
            $status_d,
            $this->type,
            $this->id,
            $this->plan_name,
            $this->plan_description,
            $hide_no_of_inmails
        );
        //print_r($this->no_of_inmails);exit();
        $content = str_replace($fields, $fields_replace, $main_content_parsed);
        return sanitize_output($content);
    }

    public function dataGrid() {
        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);
        $whereCond = '';
        if (isset($chr) && $chr != '') {
            $whereCond = " AND (
                        plan_name_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%'  OR 
                        IF(plan_type = 'r', 'Regular', IF(plan_type = 'ah', 'Ad hoc inmails', 'Featured Job' ) ) LIKE '%" . $chr . "%'  OR 
                        IF(plan_type = 'r', concat_ws(' ',plan_duration, IF( plan_duration_unit = 'm', IF( plan_duration > 1, 'Months', 'Month' ), IF( plan_duration > 1, 'Years', 'Year' ) ) ), '-' ) LIKE '%" . $chr . "%'  OR 
                        IF(plan_type = 'r', no_of_inmails, '-' ) LIKE '%" . $chr . "%'  OR 
                        concat_ws('', '" . CURRENCY_SYMBOL . "',price) LIKE '%" . $chr . "%' 
                        ) ";
        }

        if (isset($sort))
            $sorting = $sort . ' ' . $order;
        else
            $sorting = 'id DESC';

        $query = "SELECT *, 
                    IF(plan_type = 'r', 'Regular', IF(plan_type = 'ah', 'Ad hoc inmails', 'Featured Job' ) ) as plan_type_text, 
                    IF(plan_type = 'r', concat_ws(' ',plan_duration, IF( plan_duration_unit = 'm', IF( plan_duration > 1, 'Months', 'Month' ), IF( plan_duration > 1, 'Years', 'Year' ) ) ), '-' ) as plan_duration_text, 
                    IF(plan_type = 'r', no_of_inmails, '-' ) as no_of_inmails_custom, 
                    concat_ws('', '" . CURRENCY_SYMBOL . "',price) as price_custom 
                    FROM " . $this->table . " 
                    WHERE plan_type = 'r' " . $whereCond . "
                    ORDER BY " . $sorting;
        
        $query_with_limit = $query . " LIMIT " . $offset . " ," . $rows . " ";

        $totalUsers = $this->db->pdoQuery($query)->results();

        $qrySel = $this->db->pdoQuery($query_with_limit)->results();
        $totalRow = count($totalUsers);

        foreach ($qrySel as $fetchRes) {
            $id = $fetchRes['id'];
            $status = $fetchRes['status'];

            $status = ($fetchRes['status'] == "a") ? "checked" : "";

            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status)) : '';
            $operation = '';

            $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $fetchRes['id'] . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';


            //$row_data[] = array($fetchRes["countryName"],$switch,$operation);	
            $final_array = array(filtering($fetchRes['id'], 'output', 'int'));
            $final_array = array_merge($final_array, array(filtering($fetchRes["plan_name_".DEFAULT_LANGUAGE_ID])));
            $final_array = array_merge($final_array, array(filtering($fetchRes["plan_type_text"])));
            $final_array = array_merge($final_array, array(filtering($fetchRes["plan_duration_text"])));
            $final_array = array_merge($final_array, array(filtering($fetchRes["no_of_inmails_custom"])));

            $final_array = array_merge($final_array, array(filtering($fetchRes["price_custom"])));
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

        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%ADHOC_INMAILS_FORM%",
            "%FEATURED_JOB_FORM%"
        );

        $fields_replace = array(
            $this->getForm('ah'),
            $this->getForm('fj')
        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

}
