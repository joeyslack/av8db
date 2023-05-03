<?php

class contactus extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields, $sessCataId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_contact_us';

        $this->type = "reply";
        $this->searchArray = $searchArray;
        parent::__construct();

        if ($this->id > 0) {
            $qrySel = $this->db->select($this->table, "*", array("id" => $id))->result();
            $fetchRes = $qrySel;
            $this->data['first_name'] = $this->first_name = $fetchRes['first_name'];
            $this->data['last_name'] = $this->last_name = $fetchRes['last_name'];
            $this->data['email_address'] = $this->email_address = $fetchRes['email_address'];

            /*$this->data['country_id'] = $this->country_id = filtering($fetchRes['country_id'], 'output', 'int');
            $this->data['country_name'] = $this->country_name = $fetchRes['country_name'] = filtering(getTableValue("tbl_country", "countryName", array("CountryId" => $this->country_id)));

            $this->data['state_id'] = $this->state_id = filtering($fetchRes['state_id'], 'output', 'int');
            $this->data['state_name'] = $this->state_name = $fetchRes['state_name'] = filtering(getTableValue("tbl_state", "stateName", array("CountryID" => $this->country_id, "StateID" => $this->state_id)));

            $this->data['city_id'] = $this->city_id = filtering($fetchRes['city_id'], 'output', 'int');
            $this->data['city_name'] = $this->city_name = $fetchRes['city_name'] = filtering(getTableValue("tbl_city", "cityName", array("CountryID" => $this->country_id, "StateID" => $this->state_id, "CityID" => $this->city_id)));*/

            $this->data['subject'] = $this->subject = $fetchRes['subject'];
            $this->data['message'] = $this->message = $fetchRes['message'];
            $this->data['date_added'] = $this->date_added = $fetchRes['date_added'];
        }
        

        switch ($type) {
            case 'reply' : {
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
        $content = $this->displayBox(array("label" => "First Name&nbsp;:", "value" => filtering($this->first_name))) .
                $this->displayBox(array("label" => "Last Name &nbsp;:", "value" => filtering($this->last_name))) .
                $this->displayBox(array("label" => "Email Address &nbsp;:", "value" => filtering($this->email_address))) .
                $this->displayBox(array("label" => "Subject &nbsp;:", "value" => filtering($this->subject))) .
                $this->displayBox(array("label" => "Message &nbsp;:", "value" => filtering($this->message))) .
                $this->displayBox(array("label" => "Posted date&nbsp;:", "value" => convertDate('onlyDate', $this->date_added)));

        $qrySel = $this->db->select("tbl_contact_us_replies", "*", array("contact_us_id" => $this->id))->results();

        if (!empty($qrySel)) {
            $content .= "<h5>Replied Messages:</h5>";

            foreach ($qrySel as $key => $value) {
                $content .= $this->displayBox(array("label" => "Message&nbsp;:", "value" => $value['message']));
                $content .= $this->displayBox(array("label" => "Replied on&nbsp;:", "value" => convertDate('onlyDate', $value['replied_on'])));
                $content .= "<br/>";
            }
        }

        return $content;
    }

    public function getForm() {

        $content = '';
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();

        $fields = array(
            "%MEND_SIGN%",
            "%TYPE%",
            "%ID%"
        );

        $fields_replace = array(
            MEND_SIGN,
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
            //$whereCond["first_name LIKE"] = "%$chr%";
            $whereCond = " AND ( us.first_name LIKE '%" . $chr . "%'  OR us.last_name LIKE '%" . $chr . "%' OR us.email_address LIKE '%" . $chr . "%'
              OR us.subject LIKE '%" . $chr . "%' ) ";
        }

        if (isset($sort))
            $sorting = (in_array($sort, array('countryName')) ? 'cnt.' : (in_array($sort, array('stateName')) ? 'st.' : (in_array($sort, array('cityName')) ? 'ct.' : 'us.'))) . $sort . ' ' . $order;
        //$sorting = $sort . ' ' . $order;
        else
            $sorting = 'us.id DESC';


        $query = 'SELECT us.*  
                    FROM tbl_contact_us us 
                    WHERE type = "c" '
                    . $whereCond . ' ORDER BY ' . $sorting;
        $query_with_limit = $query . ' LIMIT ' . $offset . ", " . $rows;
        
        
        $getTotalRow = $this->db->pdoQuery($query)->results();
        $totalRow = count($getTotalRow);

        $qrySel = $this->db->pdoQuery($query_with_limit)->results();

        foreach ($qrySel as $fetchRes) {
            $id = $fetchRes['id'];

            $operation = '';

            $operation .= (in_array('reply', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=reply&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Reply')) : '';

            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';

            $final_array = array(filtering($fetchRes['id'], 'output', 'int'));
            $final_array = array_merge($final_array, array(filtering($fetchRes["first_name"])));
            $final_array = array_merge($final_array, array(filtering($fetchRes["last_name"])));
            $final_array = array_merge($final_array, array(filtering($fetchRes["email_address"])));

            $final_array = array_merge($final_array, array(filtering($fetchRes["subject"])));

            if (in_array('reply', $this->Permission) || in_array('delete', $this->Permission) || in_array('view', $this->Permission)) {
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
