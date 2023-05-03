<?php

class subscribedusers extends Home {

    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields, $sessCataId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_subscribers';

        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();
        
        switch ($type) {
            case 'delete' : {
                    $this->data['content'] = (in_array('delete', $this->Permission)) ? json_encode($this->dataGrid()) : '';
                    break;
                }
            case 'datagrid' : {
                    $this->data['content'] = (in_array('module', $this->Permission)) ? json_encode($this->dataGrid()) : '';
                }
        }
    }

    public function dataGrid() {
        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);
        //$whereCond = ' where  status!=\'a\'';
        if (isset($chr) && $chr != '') {
            $whereCond .= "  WHERE (u.first_name LIKE '%" . $chr . "%' OR u.last_name LIKE '%" . $chr . "%' OR u.email_address LIKE '%" . $chr . "%'  OR DATE_FORMAT(s.subscribed_on, '".MYSQL_DATE_FORMAT."') LIKE '%" . $chr . "%')
             ";
        }

        if (isset($sort)) {
            $sorting = (in_array($sort, array('subscribed_on')) ? 's.' : (in_array($sort, array('status')) ? 's.' : 'u.')) . $sort . ' ' . $order;
        } else {
            $sorting = 's.id DESC';
        }
        
        $query = "SELECT s.* , u.first_name, u.last_name, u.email_address 
                    FROM tbl_subscribers s 
                    LEFT JOIN tbl_users u ON u.id = s.user_id
                    " . $whereCond . " ORDER BY " . $sorting;
        
        $query_with_limit = $query. " LIMIT " . $offset . " ," . $rows . " ";
        
        $totalUsers = $this->db->pdoQuery($query)->results();
        
        $qrySel = $this->db->pdoQuery($query_with_limit)->results();
        $totalRow = count($totalUsers);

        foreach ($qrySel as $fetchRes) {
            $status = ($fetchRes['status'] == "a") ? "checked" : "";

            // $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $fetchRes['id'] . "", "check" => $status)) : '';
            $operation = '';

            //$operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $fetchRes['id'] . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $fetchRes['id'] . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';
            //$operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $fetchRes['id'] . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';

            $firstName = (isset($fetchRes["first_name"]) && $fetchRes["first_name"] != '') ? $fetchRes["first_name"] : 'N/A';
            $lastName = (isset($fetchRes["last_name"]) && $fetchRes["last_name"] != '') ? $fetchRes["last_name"] : 'N/A';
            
            $email = (isset($fetchRes["email"]) && $fetchRes["email"] != '') ? $fetchRes["email"] : 'N/A';

            $final_array = array(
                filtering($fetchRes['id'], 'output', 'int'),
                filtering($firstName),
                filtering($lastName),
                filtering($email),
                convertDate('onlyDate', $fetchRes['subscribed_on'])
            );

            // $final_array = array_merge($final_array, array($switch));
            $final_array = array_merge($final_array, array($operation));
            //echo "<pre>";print_r($final_array);exit;
            $row_data[] = $final_array;
        }

        $result["sEcho"] = $sEcho;
        $result["iTotalRecords"] = (int) $totalRow;
        $result["iTotalDisplayRecords"] = (int) $totalRow;
        $result["aaData"] = $row_data;
        return $result;
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

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->breadcrumb = $this->getBreadcrumb();
        $final_result = $main_content->parse();
        return $final_result;
    }

}
