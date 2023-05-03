<?php

class City extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields, $sessCataId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_city';

        $this->type = ($this->id > 0 ? 'edit' : 'add');
        $this->searchArray = $searchArray;
        parent::__construct();
        if ($this->id > 0) {
            $qrySel = $this->db->select($this->table, array("CityId", "StateID", "CountryID", "cityName", "isActive"), array("CityId" => $id))->result();
            $fetchRes = $qrySel;
            $this->data['CityId'] = $this->CityId = $fetchRes['CityId'];
            $this->data['StateID'] = $this->StateID = $fetchRes['StateID'];
            $this->data['CountryID'] = $this->CountryID = $fetchRes['CountryID'];
            $this->data['cityName'] = $this->cityName = $fetchRes['cityName'];
            $this->data['isActive'] = $this->isActive = $fetchRes['isActive'];
        } else {
            $this->data['cityName'] = $this->cityName = '';
            $this->data['CityId'] = $this->CityId = '';
            $this->data['StateID'] = $this->StateID = 0;
            $this->data['CountryID'] = $this->CountryID = 0;
            $this->data['isActive'] = $this->isActive = 'y';
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
        $content = '';

        $content = $this->displayBox(array("label" => "City name &nbsp;:", "value" => $this->cityName)).
        $this->displayBox(array("label" => "Status&nbsp;:", "value" => $this->isActive == 'y' ? 'Active' : 'Deactive'));
        return $content;
    }

    public function getForm() {
        $content = $country_option = $state_option = $selected ='';

        $getSelectBoxOption = $this->getSelectBoxOption();
        $fields = array("%VALUE%", "%SELECTED%", "%DISPLAY_VALUE%");

        //country drop down
        $qrySelCountry = $this->db->pdoQuery("SELECT * FROM tbl_country where isActive='y' ORDER BY countryName ASC")->results();

        foreach ($qrySelCountry as $fetchRes) {
            $selected = ($this->CountryID == $fetchRes['CountryId']) ? "selected" : "";

            $fields_replace = array(
                filtering($fetchRes['CountryId'], 'output', 'int'),
                $selected,
                filtering($fetchRes['countryName'])
            );
            $country_option .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }
        //State dropdown
        $qrySelState = $this->db->pdoQuery("SELECT * FROM tbl_state where CountryID=" . $this->CountryID . " AND isActive='y' ORDER BY stateName")->results();

        foreach ($qrySelState as $fetchRes) {
            $selected = ($this->StateID == $fetchRes['StateID']) ? "selected" : "";

            $fields_replace = array(
                filtering($fetchRes['StateID'], 'output', 'int'),
                $selected,
                filtering($fetchRes['stateName'])
            );
            $state_option .= str_replace($fields, $fields_replace, $getSelectBoxOption);
        }


        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/form-nct.tpl.php");
        $main_content = $main_content->parse();
        $status_a = ($this->isActive == 'y' ? 'checked' : '');
        $status_d = ($this->isActive != 'y' ? 'checked' : '');

        $fields = array("%MEND_SIGN%", "%COUNTRY_OPTION%", "%STATE_OPTION%", "%CITY_NAME%", "%STATUS_A%", "%STATUS_D%", "%TYPE%", "%ID%");

        $fields_replace = array(
            MEND_SIGN,
            filtering($country_option, 'output', 'text'),
            filtering($state_option, 'output', 'text'),
            filtering($this->data['cityName']),
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

        $aWhere = array(1);
        $sWhere = ' WHERE 1 = ? ';
        if (isset($chr) && $chr != '') {
            $sWhere .= " AND (ct.cityName LIKE ? OR s.stateName LIKE ?)";
            $aWhere[] = "%$chr%";
            $aWhere[] = "%$chr%";
        }

        if (isset($sort))
            $sorting = (in_array($sort, array('stateName')) ? 's.' : 'ct.') . $sort . ' ' . $order;
        else
            $sorting = 'ct.CityId DESC';

        $totalRowTmp = $this->db->pdoQuery("SELECT COUNT(ct.CityId) AS nmrows FROM tbl_city AS ct INNER JOIN tbl_state AS s ON ct.StateID = s.StateID $sWhere", $aWhere)->result();
        $totalRow = $totalRowTmp['nmrows'];

        $qrySel = $this->db->pdoQuery("SELECT ct.*,s.stateName FROM tbl_city AS ct INNER JOIN tbl_state AS s ON ct.StateID = s.StateID $sWhere ORDER BY $sorting limit $offset , $rows", $aWhere)->results();
        foreach ($qrySel as $fetchRes) {
            $id = $fetchRes['CityId'];
            $status = $fetchRes['isActive'];

            $status = ($fetchRes['isActive'] == "y") ? "checked" : "";

            $switch = (in_array('status', $this->Permission)) ? $this->toggel_switch(array("action" => "ajax." . $this->module . ".php?id=" . $id . "", "check" => $status)) : '';
            $operation = '';

            $operation .= (in_array('edit', $this->Permission)) ? $this->operation(array("href" => "ajax." . $this->module . ".php?action=edit&id=" . $id . "", "class" => "btn default btn-xs black btnEdit", "value" => '<i class="fa fa-edit"></i>&nbsp;Edit')) : '';
            $operation .=(in_array('view', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=view&id=" . $id . "", "class" => "btn default blue btn-xs btn-viewbtn", "value" => '<i class="fa fa-laptop"></i>&nbsp;View')) : '';
            $operation .=(in_array('delete', $this->Permission)) ? '&nbsp;&nbsp;' . $this->operation(array("href" => "ajax." . $this->module . ".php?action=delete&id=" . $id . "", "class" => "btn default btn-xs red btn-delete", "value" => '<i class="fa fa-trash-o"></i>&nbsp;Delete')) : '';

            $final_array = array(
                filtering($fetchRes["CityId"]), 
                filtering($fetchRes["cityName"]));
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

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->breadcrumb = $this->getBreadcrumb();
        $final_result = $main_content->parse();
        return $final_result;
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
}
