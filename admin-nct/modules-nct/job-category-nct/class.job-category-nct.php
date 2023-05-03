<?php

class Job_categories extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_job_category';
        $this->get_languages = get_languages();
        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $qrySel = $this->db->select($this->table, "*", array("id" => $id))->result();
            $fetchRes = $qrySel;
            $this->data = $fetchRes;
            


            $this->data['status'] = $this->status = filtering($fetchRes['status']);
            $this->data['added_on'] = $this->added_on = $fetchRes['added_on'];
            $this->data['updated_on'] = $this->updated_on = $fetchRes['updated_on'];
        } else {
            
            

            $this->data['status'] = $this->status = 'a';
            $this->data['added_on'] = $this->added_on = '';
            $this->data['updated_on'] = $this->updated_on = '';
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
        $job_category = $job_category_description = $content = '';
        foreach ($this->get_languages as $key => $value) {
            $name = filtering($this->data['job_category_'.$value['id']]); 
            $desc = filtering($this->data['job_category_description_'.$value['id']]); 
            $job_category.=$this->displayBox(array("label"=>"Job Category(".$value['languageName'].") &nbsp;:", "value" => $name));
            $job_category_description .= $this->displayBox(array("label" => "Description (".$value['languageName'].") &nbsp;:", "value" => $desc));
        }
        $content .= $job_category;
        $content .= $job_category_description;

        $content .= 
                $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->status == 'a' ? 'Active' : 'Deactive')) .
                $this->displayBox(array("label" => "Added On&nbsp;:", "value" => convertDate('onlyDate', $this->added_on)  ) );
        return $content;
    }

    public function getForm() {

        $content = '';
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();
        $status_a = ($this->status == 'a' ? 'checked' : '');
        $status_d = ($this->status != 'a' ? 'checked' : '');
        $textarea = new Templater(DIR_ADMIN_TMPL . "/textarea-nct.tpl.php");
        $textarea = $textarea->parse();
        $textbox = new Templater(DIR_ADMIN_TMPL . "/textbox-nct.tpl.php");
        $textbox = $textbox->parse();
        $search = array('%ID%','%LABEL%','%FIELD_NAME%','%FIELD_VALUE%');
        foreach ($this->get_languages as $lang) {
            $box_label = MEND_SIGN.'Job Category('.$lang['languageName'].')';
            $box_field_name = 'job_category';
            $box_field_value = $this->id > 0 ? $this->data['job_category_'.$lang['id']] : '';
            $replace = array($lang['id'],$box_label,$box_field_name,$box_field_value);
            $job_category .= str_replace($search, $replace, $textbox);

            $area_label = MEND_SIGN.'Category Description('.$lang['languageName'].')';
            $area_field_name = 'job_category_description';
            $area_field_value = $this->id > 0 ? $this->data['job_category_description_'.$lang['id']] : '';
            $area_replace = array($lang['id'],$area_label,$area_field_name,$area_field_value);
            $job_category_description .= str_replace($search, $area_replace, $textarea);
        }

        $fields = array("%MEND_SIGN%","%JOB_CATEGORY%","%JOB_CATEGORY_DESCRIPTION%","%STATUS_A%","%STATUS_D%","%TYPE%","%ID%");
        $fields_replace = array(MEND_SIGN,$job_category,$job_category_description,$status_a,$status_d,$this->type,$this->id);

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
            $whereCond .= " WHERE ( job_category_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%' OR job_category_description_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%' )";
        }

        if (isset($sort))
            $sorting = $sort . ' ' . $order;
        else
            $sorting = 'id DESC';


        $sql = "SELECT * FROM " . $this->table . " " . $whereCond . " order by " . $sorting;
        $sql_with_limit = $sql . " LIMIT " . $offset . " ," . $rows . " ";
        
        $getTotalRows = $this->db->pdoQuery($sql)->results();
        $totalRow = count($getTotalRows);
        
        $qrySel = $this->db->pdoQuery($sql_with_limit)->results();

        foreach ($qrySel as $fetchRes) {
            $id = $fetchRes['id'];
            $status = $fetchRes['status'];

            $status = ($fetchRes['status'] == "a") ? "checked" : "";

            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status)) : '';
            $operation = '';

            $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $id . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';


            $final_array = array(
                filtering($fetchRes["id"], 'output', 'int'),
                filtering($fetchRes["job_category_".DEFAULT_LANGUAGE_ID])
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
        $final_result = $main_content->parse();
        return $final_result;
    }

}
