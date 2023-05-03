<?php

class RequestedLicensesEndorsements extends Home {

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

        
        switch ($type) {
           
            case 'datagrid' : {
                    $this->data['content'] = json_encode($this->dataGrid());
                }
        }
    }

   
    public function dataGrid() {

        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);

        $whereCond = '';
        if (isset($chr) && $chr != '') {
            $whereCond .= " AND (airport_name_".DEFAULT_LANGUAGE_ID." LIKE '%" . $chr . "%' OR u.first_name LIKE '%" . $chr . "%' OR u.last_name LIKE '%" . $chr . "%')";
        }
        if (isset($sort))
            $sorting = $sort . ' ' . $order;
        else
            $sorting = 'id DESC';

        $sql = "SELECT l.*,u.first_name,u.last_name FROM tbl_airport as l 
        LEFT JOIN tbl_users as u ON u.id=l.user_id 
        WHERE user_id>0 " . $whereCond . " order by " . $sorting;

        $sql_with_limit = $sql . " LIMIT " . $offset . " ," . $rows . " ";

        $getTotalRows = $this->db->pdoQuery($sql)->results();
        $totalRow = count($getTotalRows);

        $qrySel = $this->db->pdoQuery($sql_with_limit)->results();

        foreach ($qrySel as $fetchRes) {
            //echo "<pre>";print_r($fetchRes);exit();
            $switchApprove = '-';
            $id_disable="";
            if($fetchRes['adminApproval'] == "a" || $fetchRes['adminApproval'] == "r"){
                $id_disable = ' disabled ';
            }
            $check_approve = ($fetchRes['adminApproval'] == "a") ? "checked" : "";
            $check_pending = ($fetchRes['adminApproval'] == "p") ? "checked" : "";
            $check_disapprove = ($fetchRes['adminApproval'] == "r") ? "checked" : "";
            $switchApprove = (in_array('status', $this->Permission)) ? $this->toggel_switch_approve(array("action" => "ajax." . $this->module . ".php?id=" . $fetchRes['id'] . "&action=approveAddressType", "id" => $fetchRes['id'], "check_approve" => $check_approve, "check_pending" => $check_pending, "check_disapprove" => $check_disapprove,"extraAtt"=>$id_disable)) : '';

            $user_name=$fetchRes['first_name'].' '.$fetchRes['last_name'];
            $final_array = array(
                filtering($fetchRes['id']),
                //filtering($id),
                filtering($fetchRes["airport_name_".DEFAULT_LANGUAGE_ID]),
                $fetchRes["airport_identifier"],
                $fetchRes["location"],
                $user_name
            );
            
            if (in_array('status', $this->Permission)) {
                $final_array = array_merge($final_array, array($switchApprove));
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

    public function toggel_switch_approve($text){
        $text['id']    = isset($text['id']) ? '' . trim($text['id']) : '0';
        $text['check_approve']    = isset($text['check_approve']) ? '' . trim($text['check_approve']) : '';
        $text['check_pending']    = isset($text['check_pending']) ? '' . trim($text['check_pending']) : '';
        $text['check_disapprove']    = isset($text['check_disapprove']) ? '' . trim($text['check_disapprove']) : '';
        $text['value_approve']    = isset($text['value_approve']) ? '' . trim($text['value_approve']) : 'y';
        $text['value_pending']    = isset($text['value_pending']) ? '' . trim($text['value_pending']) : 'p';
        $text['value_disapprove']    = isset($text['value_disapprove']) ? '' . trim($text['value_disapprove']) : 'n';
        $text['class']    = isset($text['class']) ? '' . trim($text['class']) : '';
        $text['action']    = isset($text['action']) ? '' . trim($text['action']) : '';
        $text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
        $main_content     = new Templater(DIR_ADMIN_TMPL . $this->module . '/switch-approve-nct.tpl.php');
        $main_content     = $main_content->parse();

        $replace = array(
            "%ID%"                  => $text['id'],
            "%VALUE_APPROVE%"       => $text['value_approve'],
            "%VALUE_PENDING%"       => $text['value_pending'],
            "%VALUE_DISAPPROVE%"    => $text['value_disapprove'],
            "%CHECKED_APPROVE%"     => $text['check_approve'],
            "%CHECKED_PENDING%"     => $text['check_pending'],
            "%CHECKED_DISAPPROVE%"  => $text['check_disapprove'],
            "%CLASS%"               => $text['class'],
            "%ACTION%"              => $text['action'],
            "%EXTRA%"               => $text['extraAtt']
        );
        return str_replace(array_keys($replace),array_values($replace), $main_content);
    }


    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->breadcrumb = $this->getBreadcrumb();

        $final_result = $main_content->parse();

        return $final_result;
    }

   

}
